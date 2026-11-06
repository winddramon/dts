<?php
if(PHP_SAPI !== 'cli') exit('CLI only');
if(!defined('IN_GAME')) define('IN_GAME', true);
if(!defined('GAME_ROOT')) define('GAME_ROOT', dirname(__DIR__).DIRECTORY_SEPARATOR);
require_once GAME_ROOT.'./include/user.func.php';
require_once GAME_ROOT.'./include/paperdoll.func.php';

// No deployment configuration or live database is loaded by this regression script.
function gencode($value) { return base64_encode(gzencode(json_encode($value))); }
function gdecode($value, $assoc = false) { return !$value ? array() : json_decode(gzdecode(base64_decode($value)), $assoc); }
function gexit($message, $file = '', $line = 0) { throw new RuntimeException($message); }
function paperdoll_test_assert($condition, $message)
{
    if(!$condition) { fwrite(STDERR, "FAIL: $message\n"); exit(1); }
    echo "PASS: $message\n";
}
function paperdoll_test_reject($callback, $message)
{
    try { $callback(); }
    catch(InvalidArgumentException $e) { paperdoll_test_assert(true, $message); return; }
    paperdoll_test_assert(false, $message);
}
function paperdoll_test_user($gold)
{
    return array('uid' => 1, 'username' => 'paperdoll-test', 'password' => create_storedpass('paperdoll-test', md5('synthetic-pass')), 'groupid' => 1, 'gold' => $gold, 'u_settings' => gencode(array('skip_opening' => 1)));
}
class PaperdollTestResult
{
    public $rows;
    function __construct($row = null) { $this->rows = $row === null ? array() : array($row); }
}
class PaperdollTestDb
{
    public $user;
    public $updates = 0;
    public $affected = 0;
    public $race = null;
    function matches($where)
    {
        if(preg_match_all("/username=X'([0-9a-f]*)'/", $where, $values)) foreach($values[1] as $value) if($this->user['username'] !== hex2bin($value)) return false;
        if(preg_match_all("/username='([^']*)'/", $where, $values)) foreach($values[1] as $value) if($this->user['username'] !== $value) return false;
        if(preg_match("/password=X'([0-9a-f]*)'/", $where, $value) && $this->user['password'] !== hex2bin($value[1])) return false;
        if(strpos($where, 'groupid>0') !== false && $this->user['groupid'] <= 0) return false;
        if(preg_match('/gold>=(\d+)/', $where, $value) && $this->user['gold'] < (int)$value[1]) return false;
        if(preg_match("/BINARY COALESCE\\(u_settings,''\\)=X'([0-9a-f]*)'/", $where, $value) && (string)$this->user['u_settings'] !== hex2bin($value[1])) return false;
        return true;
    }
    function query($sql, $silent = '')
    {
        if(preg_match('/^SELECT (.+) FROM test_users WHERE (.+)$/s', trim($sql), $match)) return new PaperdollTestResult($this->matches($match[2]) ? $this->user : null);
        if(preg_match('/^UPDATE test_users SET (.+) WHERE (.+)$/s', trim($sql), $match)) {
            $this->updates++; $this->affected = 0;
            if($this->race) { $race = $this->race; $this->race = null; $race($this); }
            if(!$this->matches($match[2])) return true;
            $next = $this->user;
            foreach(explode(',', $match[1]) as $set) {
                if(preg_match("/^`?([a-zA-Z0-9_]+)`?=X'([0-9a-f]*)'$/", $set, $value)) $next[$value[1]] = hex2bin($value[2]);
                elseif(preg_match('/^gold=gold-(\d+)$/', $set, $value)) {
                    if(strpos($match[2], 'gold>='.$value[1]) === false) throw new RuntimeException('Missing balance condition');
                    $next['gold'] -= (int)$value[1];
                } else throw new RuntimeException('Unsupported test assignment: '.$set);
            }
            if($next !== $this->user) { $this->user = $next; $this->affected = 1; }
            return true;
        }
        throw new RuntimeException('Unsupported test query: '.$sql);
    }
    function array_update($table, $updates, $where) {
        $set = array(); foreach($updates as $key => $value) $set[] = '`'.$key.'`='.paperdoll_sql($value);
        return $this->query('UPDATE '.$table.' SET '.implode(',', $set).' WHERE '.$where);
    }
    function num_rows($result) { return count($result->rows); }
    function fetch_array($result) { return array_shift($result->rows); }
    function affected_rows() { return $this->affected; }
}
$userdb_remote_storage = ''; $userdb_forced_local = 0; $userdb_forced_key = '';
$userdb_remote_storage_sign = ''; $userdb_remote_storage_pass = ''; $userdb_remote_reconnect_times = 2; $userdb_remote_reconnect_duration = 0;

$catalog = array(
	'unit_price' => 20,
	'default' => array('hair' => array('id' => 'hair01', 'color' => '#334455'), 'top' => array('id' => 'top01', 'color' => '#223344'), 'bottom' => array('id' => 'bottom01', 'color' => '#445566'), 'accessories' => array()),
	'hair' => array(array('id' => 'hair01'), array('id' => 'hair02')),
	'top' => array(array('id' => 'top01'), array('id' => 'top02')),
	'bottom' => array(array('id' => 'bottom01'), array('id' => 'bottom02')),
	'accessories' => array(array('id' => 'glasses01', 'slot' => 'eyes'), array('id' => 'glasses02', 'slot' => 'eyes'), array('id' => 'hat01', 'slot' => 'head'))
);
$gtablepre = 'test_';
$db = new PaperdollTestDb();
$db->user = paperdoll_test_user(200);

function paperdoll_test_input($user, $appearance)
{
	return array('base' => hash('sha256', paperdoll_user_raw($user)), 'appearance' => $appearance);
}

$appearance = paperdoll_default($catalog);
$appearance['classic'] = true;
$result = paperdoll_save($db->user, paperdoll_test_input($db->user, $appearance), $catalog);
paperdoll_test_assert($result['cost'] === 0 && !$result['state']['free_used'] && $result['state']['classic'], '只切换经典显示不扣费且保留首次免费');
$appearance['hair']['id'] = 'hair02';
$appearance['top']['id'] = 'top02';
$appearance['accessories'] = array(array('id' => 'hat01', 'color' => '#ABCDEF'));
$first_input = paperdoll_test_input($db->user, $appearance);
$result = paperdoll_save($db->user, $first_input, $catalog);
paperdoll_test_assert($result['changed'] === 3 && $result['cost'] === 0 && $result['credits2'] === 200 && $result['state']['free_used'], '第一次实际自定义无论改几个部件都免费');
paperdoll_test_assert($result['state']['accessories'][0]['color'] === '#abcdef', '服务端将颜色规范为小写');
$result = paperdoll_save($db->user, $first_input, $catalog);
paperdoll_test_assert($result['cost'] === 0 && $result['credits2'] === 200, '第一次保存的相同重试不会再次收费');

$appearance = $result['state'];
$appearance['hair']['color'] = '#112233';
$appearance['free_used'] = false;
$result = paperdoll_save($db->user, paperdoll_test_input($db->user, $appearance), $catalog);
paperdoll_test_assert($result['changed'] === 1 && $result['cost'] === 20 && $result['credits2'] === 180 && $result['state']['free_used'], '仅换色算一个改动，客户端不能重置免费资格');
paperdoll_test_assert(paperdoll_load(paperdoll_user_raw($db->user), $catalog) === $result['state'], '重新加载保留形象、偏好和首次免费标记');

$appearance = $result['state'];
$appearance['accessories'][] = array('id' => 'glasses01', 'color' => '#121212');
$result = paperdoll_save($db->user, paperdoll_test_input($db->user, $appearance), $catalog);
paperdoll_test_assert($result['cost'] === 20 && $result['changed'] === 1, '新增一件饰品只收一次费用');
$appearance = $result['state'];
$appearance['accessories'][0]['id'] = 'glasses02';
$result = paperdoll_save($db->user, paperdoll_test_input($db->user, $appearance), $catalog);
paperdoll_test_assert($result['cost'] === 20 && $result['changed'] === 1, '同位置替换饰品算一次改动');
$appearance = $result['state'];
$appearance['accessories'] = array_reverse($appearance['accessories']);
$result = paperdoll_save($db->user, paperdoll_test_input($db->user, $appearance), $catalog);
paperdoll_test_assert($result['cost'] === 0, '饰品数组顺序变化不算换装');

$appearance = paperdoll_default($catalog);
$result = paperdoll_save($db->user, paperdoll_test_input($db->user, $appearance), $catalog);
paperdoll_test_assert($result['state']['free_used'] && $result['cost'] === 80, '恢复默认按实际改动收费，首次资格不会恢复');
$unchanged = $db->user;
$appearance = $result['state'];
$appearance['top']['id'] = 'top02';
$db->user['gold'] = 19;
paperdoll_test_reject(function() use($db, $appearance, $catalog) { paperdoll_save($db->user, paperdoll_test_input($db->user, $appearance), $catalog); }, '余额不足拒绝保存');
paperdoll_test_assert($db->user['u_settings'] === $unchanged['u_settings'] && $db->user['gold'] === 19, '失败不改变形象和余额');

$db->user['gold'] = 200;
$stale_user = $db->user;
$stale_input = paperdoll_test_input($stale_user, $appearance);
$newer = paperdoll_load(paperdoll_user_raw($db->user), $catalog);
$newer['bottom']['id'] = 'bottom02';
paperdoll_save($db->user, paperdoll_test_input($db->user, $newer), $catalog);
paperdoll_test_reject(function() use($db, $stale_input, $catalog) { paperdoll_save($db->user, $stale_input, $catalog); }, '旧页面不能覆盖新形象');
$before_race = $db->user;
$db->race = function($database) { $settings = paperdoll_settings($database->user['u_settings']); $settings['paperdoll']['classic'] = true; $database->user['u_settings'] = gencode($settings); };
$appearance = paperdoll_load(paperdoll_user_raw($db->user), $catalog);
$appearance['top']['id'] = 'top02';
paperdoll_test_reject(function() use($db, $appearance, $catalog) { paperdoll_save($db->user, paperdoll_test_input($db->user, $appearance), $catalog); }, '读取后发生并发保存时条件更新拒绝旧请求');
paperdoll_test_assert($db->user['gold'] === $before_race['gold'], '并发保存失败不扣费');

$db->race = function($database) { $database->user['gold'] = 0; };
paperdoll_test_reject(function() use($db, $appearance, $catalog) { paperdoll_save($db->user, paperdoll_test_input($db->user, $appearance), $catalog); }, '读取后余额被其他消费使用时再次校验余额');
paperdoll_test_assert($db->user['gold'] === 0, '余额不会扣为负数');

$db->user['gold'] = 200;
$db->race = function($database) { $database->user['gold'] -= 7; };
$result = paperdoll_save($db->user, paperdoll_test_input($db->user, $appearance), $catalog);
paperdoll_test_assert($result['credits2'] === 173 && $db->user['gold'] === 173, '并发其他消费保留，保存响应返回实际余额');

$appearance_a = $result['state'];
$appearance_b = $appearance_a;
$appearance_b['hair']['id'] = 'hair02';
$old_a_input = paperdoll_test_input($db->user, $appearance_b);
paperdoll_save($db->user, $old_a_input, $catalog);
paperdoll_save($db->user, paperdoll_test_input($db->user, $appearance_a), $catalog);
paperdoll_test_reject(function() use($db, $old_a_input, $catalog) { paperdoll_save($db->user, $old_a_input, $catalog); }, '换装 A→B→A 后旧页面不会重新获得扣费权限');

$bad = $appearance; $bad['top']['id'] = '../../outside';
paperdoll_test_reject(function() use($bad, $catalog) { paperdoll_normalize_appearance($bad, $catalog); }, '拒绝不存在的部件及路径输入');
$bad = $appearance; $bad['hair']['color'] = 'url(javascript:evil)';
paperdoll_test_reject(function() use($bad, $catalog) { paperdoll_normalize_appearance($bad, $catalog); }, '拒绝非十六进制颜色');
$bad = $appearance; $bad['classic'] = 'false';
paperdoll_test_reject(function() use($bad, $catalog) { paperdoll_normalize_appearance($bad, $catalog); }, '显示偏好要求布尔值');
$bad = $appearance; $bad['accessories'] = array(array('id' => 'glasses01', 'color' => '#000000'), array('id' => 'glasses02', 'color' => '#000000'));
paperdoll_test_reject(function() use($bad, $catalog) { paperdoll_normalize_appearance($bad, $catalog); }, '拒绝同位置叠加饰品');
$bad = $appearance; $bad['accessories'] = array(array('id' => 'glasses01', 'color' => '#000000'), array('id' => 'glasses01', 'color' => '#000000'));
paperdoll_test_reject(function() use($bad, $catalog) { paperdoll_normalize_appearance($bad, $catalog); }, '拒绝重复饰品');
try { paperdoll_load('{broken', $catalog); paperdoll_test_assert(false, '损坏状态不得默认为免费账户'); }
catch(RuntimeException $e) { paperdoll_test_assert(true, '损坏状态不得默认为免费账户'); }
paperdoll_test_assert(strpos(paperdoll_json(array('label' => '</script>')), '</script>') === false, 'bootstrap编码不会提前关闭script标签');

// 使用正式目录补足资源与账户契约的集成检查，不连接或修改真实数据库。
$actual_catalog = paperdoll_catalog();
$actual_default = paperdoll_default();
paperdoll_test_assert(paperdoll_normalize_appearance($actual_default) === paperdoll_normalize_appearance($actual_catalog['default'] + array('classic' => false)), '正式目录默认形象规范化一致');
$assets = array($actual_catalog['base']);
foreach(array('hair', 'top', 'bottom', 'accessories') as $category) {
	paperdoll_test_assert(count($actual_catalog[$category]) === 16, '正式目录 '.$category.' 有16种部件');
	$ids = array();
	foreach($actual_catalog[$category] as $part) {
		$ids[] = $part['id'];
		$sample = $actual_default;
		$selection = array('id' => $part['id'], 'color' => $part['color']);
		if($category === 'accessories') $sample[$category] = array($selection);
		else $sample[$category] = $selection;
		paperdoll_normalize_appearance($sample);
		$assets[] = $part['src'];
	}
	paperdoll_test_assert(count(array_unique($ids)) === 16, '正式目录 '.$category.' 编号无重复且全部可选择');
}
foreach($assets as $asset) {
	$dimensions = is_file(GAME_ROOT.$asset) ? getimagesize(GAME_ROOT.$asset) : false;
	if(!$dimensions || $dimensions[0] !== 110 || $dimensions[1] !== 160 || $dimensions['mime'] !== 'image/png') {
		paperdoll_test_assert(false, '正式素材存在且尺寸格式正确：'.$asset);
	}
}
paperdoll_test_assert(count($assets) === 65, '65张正式PNG素材均存在且为110×160');
$db = new PaperdollTestDb();
$db->user = paperdoll_test_user(100);
$result = paperdoll_save($db->user, paperdoll_test_input($db->user, $actual_default));
paperdoll_test_assert($result['cost'] === 0 && $result['changed'] === 0 && !$result['state']['free_used'], '正式默认形象原样保存不消耗免费资格');
$custom = $actual_default;
$custom['hair'] = array('id' => $actual_catalog['hair'][1]['id'], 'color' => $actual_catalog['hair'][1]['color']);
$custom['top'] = array('id' => $actual_catalog['top'][0]['id'], 'color' => $actual_catalog['top'][0]['color']);
$custom['bottom'] = array('id' => $actual_catalog['bottom'][1]['id'], 'color' => $actual_catalog['bottom'][1]['color']);
$custom['accessories'] = array(array('id' => $actual_catalog['accessories'][0]['id'], 'color' => $actual_catalog['accessories'][0]['color']));
$result = paperdoll_save($db->user, paperdoll_test_input($db->user, $custom));
paperdoll_test_assert($result['cost'] === 0 && $result['changed'] === 4 && $result['credits2'] === 100 && $result['state']['free_used'], '正式目录首次四部件修改免费保存');
$custom = $result['state'];
$custom['hair']['color'] = '#123456';
$result = paperdoll_save($db->user, paperdoll_test_input($db->user, $custom));
paperdoll_test_assert($result['cost'] === 20 && $result['changed'] === 1 && $result['credits2'] === 80, '正式目录之后换色一次扣20切糕');
paperdoll_test_assert(paperdoll_load(paperdoll_user_raw($db->user)) === $result['state'], '正式目录持久化后重读一致');

$cookiepass = md5('synthetic-pass');
paperdoll_test_assert(paperdoll_current_user('paperdoll-test', $cookiepass)['username'] === 'paperdoll-test', '账户验证使用 DTS 加盐密码比较');
paperdoll_test_reject(function() { paperdoll_current_user('paperdoll-test', 'wrong-cookie'); }, '错误登录密码不得保存形象');
$db->user['groupid'] = 0;
paperdoll_test_reject(function() use($cookiepass) { paperdoll_current_user('paperdoll-test', $cookiepass); }, '被封禁账户不得保存形象');
$db->user['groupid'] = 1;
$saved_state = paperdoll_user_display_state($db->user);
update_udata_by_username(array('u_settings' => gencode(array('skip_opening' => 0, 'paperdoll' => paperdoll_default()))), 'paperdoll-test');
paperdoll_test_assert(paperdoll_user_display_state($db->user) === $saved_state && paperdoll_settings($db->user['u_settings'])['skip_opening'] === 0, '普通设置保存更新原设置但不能覆盖外观和免费资格');
$db->race = function($database) {
    $settings = paperdoll_settings($database->user['u_settings']);
    $settings['paperdoll']['revision']++;
    $settings['paperdoll']['hair']['color'] = '#778899';
    $database->user['u_settings'] = gencode($settings);
};
update_udata_by_username(array('u_settings' => gencode(array('skip_opening' => 1))), 'paperdoll-test');
paperdoll_test_assert(paperdoll_user_display_state($db->user)['hair']['color'] === '#778899' && paperdoll_settings($db->user['u_settings'])['skip_opening'] === 1, '普通设置与形象竞态时重读保护最新外观');
$saved_state = paperdoll_user_display_state($db->user);
paperdoll_test_assert(update_udata_multilist(array(array('username' => 'paperdoll-test', 'u_settings' => gencode(array('skip_opening' => 0))))) && paperdoll_user_display_state($db->user) === $saved_state, '战后批量设置入口保留外观');

// Transport stub: use the real request/retry functions with an isolated authoritative DB.
$remote_db = new PaperdollTestDb(); $remote_db->user = paperdoll_test_user(100);
$db = new PaperdollTestDb(); $db->user = paperdoll_test_user(7);
$userdb_remote_storage = 'http://synthetic.invalid/userdb_receive.php';
$remote_drop_once = false; $remote_reject = false; $remote_offline = false; $remote_calls = 0;
function curl_post($url, $context)
{
    global $db, $remote_db, $userdb_forced_local, $remote_drop_once, $remote_reject, $remote_offline, $remote_calls;
    $remote_calls++;
    if($remote_offline) return '';
    if($remote_reject) return gencode(array('error' => '远端拒绝保存。'));
    $local_db = $db; $old_forced = $userdb_forced_local;
    $db = $remote_db; $userdb_forced_local = 1;
    try {
        if($context['command'] === 'paperdoll_save') {
            try { $ret = paperdoll_save(paperdoll_current_user($context['para1'], $context['para2']), gdecode($context['para3'], true)); }
            catch(InvalidArgumentException $e) { $ret = array('error' => $e->getMessage()); }
        } elseif($context['command'] === 'update_udata') {
            $ret = update_udata(gdecode($context['para1'], true), $context['para2']);
        } else throw new RuntimeException('Unexpected RPC');
    } finally { $db = $local_db; $userdb_forced_local = $old_forced; }
    if($remote_drop_once) { $remote_drop_once = false; return ''; }
    return gencode($ret);
}
$appearance = paperdoll_default(); $appearance['hair']['color'] = '#102030';
$result = paperdoll_request('paperdoll-test', $cookiepass, paperdoll_test_input($remote_db->user, $appearance));
paperdoll_test_assert($result['cost'] === 0 && $result['credits2'] === 100 && paperdoll_user_display_state($db->user) === $result['state'], '远端首次修改免费并同步本地对局外观');
paperdoll_test_assert($db->user['gold'] === 7 && !isset($result['cache_settings']), '外观镜像不覆盖本地余额且响应不暴露内部缓存');
$remote_drop_once = true; $before_calls = $remote_calls;
$appearance = $result['state']; $appearance['top']['color'] = '#405060';
$input = paperdoll_test_input($remote_db->user, $appearance);
$result = paperdoll_request('paperdoll-test', $cookiepass, $input);
paperdoll_test_assert($remote_calls === $before_calls + 2 && $remote_db->user['gold'] === 80 && $result['credits2'] === 80, '远端扣费成功但响应丢失时自动重试只扣一次');
paperdoll_test_assert(paperdoll_user_display_state($db->user) === paperdoll_user_display_state($remote_db->user), '远端重试后本地镜像保留已付费外观');
$stale_input = $input;
$appearance = $result['state']; $appearance['bottom']['color'] = '#708090';
$db->race = function($database) { $settings = paperdoll_settings($database->user['u_settings']); $settings['local_only'] = 'preserved'; $database->user['u_settings'] = gencode($settings); };
$result = paperdoll_request('paperdoll-test', $cookiepass, paperdoll_test_input($remote_db->user, $appearance));
paperdoll_test_assert(paperdoll_user_display_state($db->user) === $result['state'] && paperdoll_settings($db->user['u_settings'])['local_only'] === 'preserved', '镜像 CAS 冲突重读重试并保留其他本地设置');
paperdoll_test_reject(function() use($cookiepass, $stale_input) { paperdoll_request('paperdoll-test', $cookiepass, $stale_input); }, '远端旧请求拒绝覆盖新外观');
$before = $remote_db->user;
$remote_reject = true;
paperdoll_test_reject(function() use($cookiepass, $input) { paperdoll_request('paperdoll-test', $cookiepass, $input); }, '远端明确失败不会伪报保存成功');
paperdoll_test_assert($remote_db->user === $before, '远端失败不改变外观与余额');
$remote_reject = false;
$saved_state = paperdoll_user_display_state($remote_db->user);
update_udata_by_username(array('u_settings' => gencode(array('skip_opening' => 1))), 'paperdoll-test');
paperdoll_test_assert(paperdoll_user_display_state($remote_db->user) === $saved_state && paperdoll_user_display_state($db->user) === $saved_state, '远端普通设置和本地镜像都不能回滚已付费外观');
$older = $remote_db->user['u_settings'];
$appearance = $saved_state; $appearance['hair']['color'] = '#a0b0c0';
$result = paperdoll_request('paperdoll-test', $cookiepass, paperdoll_test_input($remote_db->user, $appearance));
paperdoll_sync_local('paperdoll-test', $older);
paperdoll_test_assert(paperdoll_user_display_state($db->user) === $result['state'], '迟到镜像响应不能回滚较新 revision');
$before = $remote_db->user; $remote_offline = true; $before_calls = $remote_calls;
try { paperdoll_request('paperdoll-test', $cookiepass, $input); paperdoll_test_assert(false, '远端断连不得当作空数组成功'); }
catch(RuntimeException $e) { paperdoll_test_assert($remote_calls === $before_calls + 2 && $remote_db->user === $before, '远端持续断连有限重试后报错且不扣费'); }
$remote_offline = false;
$local_settings = paperdoll_settings($db->user['u_settings']); unset($local_settings['paperdoll']); $db->user['u_settings'] = gencode($local_settings);
$mirror_race = function($database) use(&$mirror_race) {
    $settings = paperdoll_settings($database->user['u_settings']);
    $settings['other_counter'] = ($settings['other_counter'] ?? 0) + 1;
    $database->user['u_settings'] = gencode($settings); $database->race = $mirror_race;
};
$db->race = $mirror_race;
paperdoll_test_assert(!paperdoll_sync_local('paperdoll-test', $remote_db->user['u_settings']), '本地镜像持续冲突有限重试后报告失败');
$db->race = null;
paperdoll_test_assert(paperdoll_sync_local('paperdoll-test', $remote_db->user['u_settings']) && paperdoll_user_display_state($db->user) === paperdoll_user_display_state($remote_db->user), '账户页再次读取权威数据后能够补回镜像');
echo "Paperdoll regression checks completed.\n";
