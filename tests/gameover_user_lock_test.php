<?php
// php -d short_open_tag=1 tests/gameover_user_lock_test.php
// 调用真实 gameover 与账户取锁逻辑；只替换数据库读取、模块导入和后续结算边界。
namespace {
	if(PHP_SAPI !== 'cli') exit('CLI only');
	define('IN_GAME', true);
	define('CURSCRIPT', 'game');
	define('IN_DAEMON', true);
	define('GEXIT_RETURN_JSON', true);
	define('GAME_ROOT', dirname(__DIR__).'/');
	define('__MAGIC__', 'return false;');
	require GAME_ROOT.'include/global.func.php';
	require GAME_ROOT.'include/user.func.php';
	function import_module() { return 'global $db,$tablepre,$sdata,$gamestate,$gamevars,$starttime,$gameover_plist,$gameover_ulist;'; }
	class GameoverLockStop extends \Exception {}
}
namespace sys {
	function process_lock() {}
	function load_gameinfo() {
		$GLOBALS['load_calls']++;
		if($GLOBALS['load_calls'] > 1 && $GLOBALS['settled_by_another']) $GLOBALS['gamestate'] = 0;
	}
	function save_gameinfo() {
		$GLOBALS['events'][] = 'save-game';
		$GLOBALS['saved_states'][] = $GLOBALS['gamestate'];
		throw new \GameoverLockStop('Stop at the first game-state write');
	}
}
namespace player {
	function fetch_playerdata($name) { return array('name' => $name, 'hp' => 100, 'type' => 0); }
}
namespace {
	require GAME_ROOT.'include/modules/core/sys/gamectl.php';
	class GameoverLockRows {
		public $rows;
		function __construct($rows) { $this->rows = $rows; }
	}
	class GameoverLockReadProbe {
		public $account_reads = 0;
		public $missing_account = false;
		function query($sql) {
			if(strpos($sql, 'SELECT name FROM test_players ') === 0) return new GameoverLockRows(array(array('name' => $GLOBALS['test_name'])));
			if(strpos($sql, 'SELECT * FROM test_users ') === 0) {
				$this->account_reads++;
				$GLOBALS['events'][] = 'accounts';
				if(empty($GLOBALS['udata_lock_pool'][$GLOBALS['test_name']])) throw new \RuntimeException('Settlement read an unlocked account');
				return new GameoverLockRows($this->missing_account ? array() : array(array('username' => $GLOBALS['test_name'], 'gold' => 100)));
			}
			throw new \RuntimeException('Unexpected SQL before the settlement boundary: '.$sql);
		}
		function num_rows($result) { return count($result->rows); }
		function fetch_array($result) { return array_shift($result->rows); }
	}
	$checks = 0;
	function settlement_check($condition, $message) {
		global $checks;
		if(!$condition) throw new \RuntimeException($message);
		$checks++;
	}
	function reset_settlement_probe() {
		$GLOBALS['gamestate'] = 30;
		$GLOBALS['starttime'] = 123456;
		$GLOBALS['gamevars'] = array();
		$GLOBALS['sdata'] = array();
		$GLOBALS['events'] = $GLOBALS['saved_states'] = array();
		$GLOBALS['load_calls'] = 0;
		$GLOBALS['settled_by_another'] = false;
		$GLOBALS['db'] = new GameoverLockReadProbe();
	}
	$test_name = '_paperdoll_settlement_test_'.bin2hex(random_bytes(8));
	$path = GAME_ROOT.'gamedata/tmp/userlock/'.$test_name.'.nlk';
	$tablepre = $gtablepre = 'test_';
	$udata_lock_pool = array();
	$userdb_remote_storage = $userdb_remote_key = $userdb_forced_key = '';
	$userdb_forced_local = 0;
	$user_lock_required = false;
	try {
		foreach(array('command_end', 'command_winner', 'command_valid', 'command_roomcmd') as $page) {
			settlement_check(user_lock_unnecessity(), $page.' normally permits an unlocked read');
			$user_lock_required = true;
			settlement_check(create_user_lock($test_name, 'busy-owner') === 0, 'Create competing account operation');
			$user_lock_required = false;
			$udata_lock_pool = array();
			touch($path, time() - 20);
			reset_settlement_probe();
			$blocked = false;
			try { \sys\gameover(); }
			catch(GameoverLockStop $e) { throw new \RuntimeException('Blocked settlement closed the game'); }
			catch(\Exception $e) { $blocked = strpos(gdecode($e->getMessage(), true)['errormsg'], '稍后重试') !== false; }
			settlement_check($blocked && $gamestate === 30 && $starttime === 123456 && !$gamevars && !$saved_states && $db->account_reads === 0, $page.' lock failure leaves the game open and writes no settlement');
			settlement_check($user_lock_required === false && user_lock_unnecessity(), 'Forced-lock flag is restored after failure');
			$udata_lock_pool[$test_name] = 1;
			release_user_lock_from_pool('busy-owner');

			reset_settlement_probe();
			try { \sys\gameover(); }
			catch(GameoverLockStop $e) {}
			settlement_check($events === array('accounts', 'save-game') && $saved_states === array(0) && $gamestate === 0 && $db->account_reads === 1, $page.' reads locked accounts before closing the game');
			settlement_check(isset($udata_lock_pool[$test_name]) && $user_lock_required === false, 'Account remains held while the temporary flag is restored');
			release_user_lock_from_pool();
		}

		reset_settlement_probe();
		$db->missing_account = true;
		\sys\gameover();
		settlement_check($gamestate === 30 && !$saved_states && $user_lock_required === false, 'Missing accounts leave the game open for a later retry');
		release_user_lock_from_pool();

		reset_settlement_probe();
		$settled_by_another = true;
		\sys\gameover();
		settlement_check($load_calls === 2 && $gamestate === 0 && !$saved_states && $events === array('accounts'), 'After waiting for accounts, a completed game is not settled twice');
		settlement_check($user_lock_required === false, 'Duplicate settlement exit restores daemon state');
		echo "PASS: $checks settlement account-lock checks (PHP ".PHP_VERSION.")\n";
	} finally {
		release_user_lock_from_pool();
		if(is_file($path)) unlink($path);
	}
}
