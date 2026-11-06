<?php
// php -d short_open_tag=1 tests/user_lock_test.php
// 使用真实锁文件与并发 PHP 进程；数据库探针只检查获取失败后不会执行账户读取。
if(PHP_SAPI !== 'cli') exit('CLI only');
define('IN_GAME', true);
define('CURSCRIPT', 'user_lock_test');
define('IN_DAEMON', true);
define('GEXIT_RETURN_JSON', true);
define('GAME_ROOT', dirname(__DIR__).'/');
require GAME_ROOT.'include/global.func.php';
require GAME_ROOT.'include/user.func.php';

$userdb_remote_storage = '';
$userdb_remote_key = '';
$userdb_forced_key = '';
$userdb_forced_local = 0;
$gtablepre = 'test_';
$udata_lock_pool = array();
$lockdir = GAME_ROOT.'gamedata/tmp/userlock/';

if(isset($argv[1]) && $argv[1] === '--shutdown-worker') {
	echo json_encode(array('result' => create_user_lock($argv[2])))."\n";
	if($argv[3] === 'warning') trigger_error('Expected lock cleanup warning', E_USER_WARNING);
	else throw new RuntimeException('Expected lock cleanup exception');
	exit;
}

if(isset($argv[1]) && $argv[1] === '--worker') {
	$until = microtime(true) + 6;
	while(!is_file($argv[3]) && microtime(true) < $until) usleep(10000);
	$result = create_user_lock($argv[2]);
	if($result === 0) {
		usleep(6500000); // Windows 的 10ms sleep 可能向上取整，留出超时余量。
		release_user_lock($argv[2]);
	}
	echo json_encode(array('result' => $result, 'pool' => $udata_lock_pool));
	exit;
}

$checks = 0;
function lock_check($condition, $message) {
	global $checks;
	if(!$condition) throw new RuntimeException($message);
	$checks++;
}

class UserLockReadProbe {
	public $queries = array();
	public $names = array();
	function query($sql) {
		$this->queries[] = $sql;
		if(strpos($sql, 'SELECT username ') !== 0) throw new RuntimeException('Read continued without the account lock');
		return true;
	}
	function num_rows($result) { return count($this->names); }
	function fetch_array($result) { return array_shift($this->names); }
}

$prefix = '_paperdoll_lock_test_'.bin2hex(random_bytes(8));
$name = $prefix.'_account';
$path = $lockdir.$name.'.nlk';
$processes = array();
try {
	lock_check(create_user_lock($name) === 0, 'Local lock acquisition');
	$ownedkey = $udata_lock_pool[$name];
	lock_check(is_string($ownedkey) && file_get_contents($path) === $ownedkey, 'Local lock records its owner');
	lock_check(create_user_lock($name) === 1 && $udata_lock_pool[$name] === $ownedkey, 'Same request reuses its local lock');
	release_user_lock_from_pool();
	lock_check(!is_file($path) && empty($udata_lock_pool), 'Local shutdown releases the owned lock');

	lock_check(create_user_lock($name, 'remote-owner') === 0, 'Remote lock acquisition');
	$udata_lock_pool = array(); // 下一次 RPC 使用相同显式 key。
	lock_check(create_user_lock($name, 'remote-owner') === 0, 'Remote key resumes its existing lock');
	release_user_lock_from_pool(); // 接收端普通 shutdown 不应提前释放。
	lock_check(is_file($path), 'Receiver shutdown preserves a remote lock');
	touch($path, time() - 20);
	lock_check(create_user_lock($name, 'remote-owner') === 0, 'The remote owner resumes even after a long settlement');
	release_user_lock_from_pool();
	touch($path, time() - 20);
	lock_check(create_user_lock($name, 'other-owner') === 2 && empty($udata_lock_pool), 'An old active lock is not bypassed or added to the pool');
	release_user_lock($name, 'other-owner');
	lock_check(is_file($path), 'Failed contender cannot release the owner lock');
	$udata_lock_pool[$name] = 1;
	release_user_lock_from_pool('other-owner');
	lock_check(is_file($path), 'Wrong remote key cannot release even an old lock');
	$udata_lock_pool[$name] = 1;
	release_user_lock_from_pool('remote-owner');
	lock_check(!is_file($path), 'Remote owner explicitly releases its lock');

	lock_check(create_user_lock($name, 'held-owner') === 0, 'Hold an account for read failure tests');
	$udata_lock_pool = array();
	touch($path, time() - 5);
	$db = new UserLockReadProbe();
	$blocked = false;
	try { fetch_udata('*', "username='$name'"); }
	catch(Exception $e) { $blocked = strpos(gdecode($e->getMessage(), true)['errormsg'], '稍后重试') !== false; }
	lock_check($blocked && !$db->queries, 'A blocked named fetch stops before reading account data');
	lock_check(is_file($path) && empty($udata_lock_pool), 'Failed fetch leaves the other owner intact');

	$db = new UserLockReadProbe();
	$db->names = array(array('username' => $name));
	$blocked = false;
	try { fetch_udata('*', 'uid=1'); }
	catch(Exception $e) { $blocked = strpos(gdecode($e->getMessage(), true)['errormsg'], '稍后重试') !== false; }
	lock_check($blocked && count($db->queries) === 1, 'A discovered username is locked before its data is read');

	$partial = $prefix.'_partial';
	lock_check(create_user_lock($partial) === 0, 'Acquire a partial batch lock');
	$db = new UserLockReadProbe();
	$blocked = false;
	try { fetch_udata('*', "username IN ('".implode("','", array_fill(0, 501, $name))."')"); }
	catch(Exception $e) { $blocked = strpos(gdecode($e->getMessage(), true)['errormsg'], '稍后重试') !== false; }
	lock_check($blocked && !$db->queries, 'A batch over 500 names does not skip locks');
	lock_check(!is_file($lockdir.$partial.'.nlk') && is_file($path), 'Failed batch releases only locks it acquired');
	$udata_lock_pool[$name] = 1;
	release_user_lock_from_pool('held-owner');

	foreach(array('warning', 'exception') as $failure) {
		$shutdown_name = $prefix.'_'.$failure;
		$pipes = array();
		$process = proc_open(array(PHP_BINARY, '-d', 'short_open_tag=1', __FILE__, '--shutdown-worker', $shutdown_name, $failure), array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
		if(!is_resource($process)) throw new RuntimeException('Cannot start shutdown worker');
		fclose($pipes[0]);
		$output = stream_get_contents($pipes[1]);
		$errors = stream_get_contents($pipes[2]);
		fclose($pipes[1]); fclose($pipes[2]);
		$exit = proc_close($process);
		$data = json_decode(explode("\n", $output)[0], true);
		lock_check(isset($data['result']) && $data['result'] === 0 && strpos($errors, 'Expected lock cleanup '.$failure) !== false, 'Worker really holds a lock and raises '.$failure);
		lock_check($failure === 'warning' ? $exit === 0 : $exit !== 0, 'Expected worker termination for '.$failure);
		lock_check(!is_file($lockdir.$shutdown_name.'.nlk') && create_user_lock($shutdown_name) === 0, 'Lock is available after '.$failure.' shutdown');
		release_user_lock_from_pool();
	}

	$race = $prefix.'_race';
	$barrier = $lockdir.$prefix.'.start';
	for($i = 0; $i < 2; $i++) {
		$pipes = array();
		$process = proc_open(array(PHP_BINARY, '-d', 'short_open_tag=1', __FILE__, '--worker', $race, $barrier), array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
		if(!is_resource($process)) throw new RuntimeException('Cannot start lock contender');
		fclose($pipes[0]);
		$processes[] = array($process, $pipes);
	}
	touch($barrier);
	$results = array();
	foreach($processes as $worker) {
		$output = stream_get_contents($worker[1][1]);
		$errors = stream_get_contents($worker[1][2]);
		fclose($worker[1][1]);
		fclose($worker[1][2]);
		$exit = proc_close($worker[0]);
		lock_check($exit === 0 && $errors === '', 'Lock contender exits without warnings');
		$data = json_decode($output, true);
		lock_check(is_array($data) && empty($data['pool']), 'Contender leaves no claimed locks');
		$results[] = $data['result'];
	}
	$processes = array();
	sort($results);
	lock_check($results === array(0, 2), 'Two real PHP processes cannot both acquire the lock: '.json_encode($results));
	lock_check(!is_file($lockdir.$race.'.nlk'), 'Winning process releases its file');
	echo "PASS: $checks account lock checks (PHP ".PHP_VERSION.")\n";
} finally {
	foreach($processes as $worker) if(is_resource($worker[0])) proc_terminate($worker[0]);
	$udata_lock_pool = array();
	foreach(glob($lockdir.$prefix.'*') as $testfile) unlink($testfile);
}
