<?php
if(!defined('IN_GAME')) exit('Access Denied');

function paperdoll_json($value)
{
	$json = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
	if(json_last_error() !== JSON_ERROR_NONE) throw new RuntimeException('无法编码纸娃娃数据。');
	return $json;
}

function paperdoll_catalog()
{
	static $catalog = null;
	if($catalog === null) {
		$raw = file_get_contents(GAME_ROOT.'./gamedata/paperdoll/catalog.json');
		$catalog = json_decode($raw, true);
		if(!is_array($catalog) || !isset($catalog['default'], $catalog['unit_price']) || !is_int($catalog['unit_price']) || $catalog['unit_price'] < 0) {
			throw new RuntimeException('纸娃娃资源配置无效。');
		}
	}
	return $catalog;
}

function paperdoll_normalize_appearance($input, $catalog = null)
{
	if($catalog === null) $catalog = paperdoll_catalog();
	if(!is_array($input) || !isset($input['classic']) || !is_bool($input['classic'])) throw new InvalidArgumentException('请选择游戏界面的形象显示方式。');
	$indexes = array();
	foreach(array('hair', 'top', 'bottom', 'accessories') as $category) {
		$indexes[$category] = array();
		foreach($catalog[$category] as $part) $indexes[$category][$part['id']] = $part;
	}
	$state = array('classic' => $input['classic']);
	foreach(array('hair', 'top', 'bottom') as $category) {
		$part = $input[$category] ?? null;
		if(!is_array($part) || !isset($part['id'], $part['color']) || !is_string($part['id']) || !isset($indexes[$category][$part['id']])) throw new InvalidArgumentException('请选择列表中的发型、上装和下装。');
		if(!is_string($part['color']) || !preg_match('/\A#[0-9a-fA-F]{6}\z/', $part['color'])) throw new InvalidArgumentException('部件颜色无效，请重新选择。');
		$state[$category] = array('id' => $part['id'], 'color' => strtolower($part['color']));
	}
	if(!isset($input['accessories']) || !is_array($input['accessories']) || array_values($input['accessories']) !== $input['accessories'] || count($input['accessories']) > count($indexes['accessories'])) throw new InvalidArgumentException('饰品选择无效。');
	$accessories = array();
	foreach($input['accessories'] as $part) {
		if(!is_array($part) || !isset($part['id'], $part['color']) || !is_string($part['id']) || !isset($indexes['accessories'][$part['id']])) throw new InvalidArgumentException('请选择列表中的饰品。');
		if(!is_string($part['color']) || !preg_match('/\A#[0-9a-fA-F]{6}\z/', $part['color'])) throw new InvalidArgumentException('饰品颜色无效，请重新选择。');
		$slot = $indexes['accessories'][$part['id']]['slot'];
		if(isset($accessories[$slot])) throw new InvalidArgumentException('同一位置只能佩戴一件饰品。');
		$accessories[$slot] = array('id' => $part['id'], 'color' => strtolower($part['color']));
	}
	ksort($accessories);
	$state['accessories'] = array_values($accessories);
	return $state;
}

function paperdoll_default($catalog = null)
{
	if($catalog === null) $catalog = paperdoll_catalog();
	$state = paperdoll_normalize_appearance(array('classic' => false) + $catalog['default'], $catalog);
	return array('free_used' => false, 'revision' => 0) + $state;
}

function paperdoll_load($raw, $catalog = null)
{
	if($catalog === null) $catalog = paperdoll_catalog();
	if($raw === null || $raw === '') return paperdoll_default($catalog);
	if(!is_string($raw) || strlen($raw) > 8192) throw new RuntimeException('形象读取失败，请联系管理员；原有形象已保留。');
	$state = json_decode($raw, true);
	if(!is_array($state) || !isset($state['free_used']) || !is_bool($state['free_used'])) throw new RuntimeException('形象读取失败，请联系管理员；原有形象已保留。');
	$revision = $state['revision'] ?? 0;
	if(!is_int($revision) || $revision < 0 || $revision >= PHP_INT_MAX) throw new RuntimeException('形象读取失败，请联系管理员；原有形象已保留。');
	try {
		return array('free_used' => $state['free_used'], 'revision' => $revision) + paperdoll_normalize_appearance($state, $catalog);
	} catch(InvalidArgumentException $e) {
		throw new RuntimeException('形象读取失败，请联系管理员；原有形象已保留。');
	}
}

function paperdoll_display_state($raw)
{
	$catalog = paperdoll_catalog();
	try { return paperdoll_load($raw, $catalog); }
	catch(RuntimeException $e) {
		// 损坏的账户外观不阻断对局，也不能借默认显示重领首次免费。
		$state = paperdoll_default($catalog);
		$state['free_used'] = true;
		return $state;
	}
}

function paperdoll_changed_count($old, $next, $catalog = null)
{
	if($catalog === null) $catalog = paperdoll_catalog();
	$count = 0;
	foreach(array('hair', 'top', 'bottom') as $category) if($old[$category] !== $next[$category]) $count++;
	$slots = array();
	foreach($catalog['accessories'] as $part) $slots[$part['id']] = $part['slot'];
	$old_parts = $new_parts = array();
	foreach($old['accessories'] as $part) $old_parts[$slots[$part['id']]] = $part;
	foreach($next['accessories'] as $part) $new_parts[$slots[$part['id']]] = $part;
	foreach(array_unique(array_merge(array_keys($old_parts), array_keys($new_parts))) as $slot) {
		if(($old_parts[$slot] ?? null) !== ($new_parts[$slot] ?? null)) $count++;
	}
	return $count;
}

function paperdoll_settings($raw)
{
	if($raw === null || $raw === '') return array();
	if(!is_string($raw)) throw new RuntimeException('账户设置读取失败，原有设置已保留。');
	$settings = gdecode($raw, true);
	if(!is_array($settings)) throw new RuntimeException('账户设置读取失败，原有设置已保留。');
	return $settings;
}

function paperdoll_user_raw($user)
{
	$settings = paperdoll_settings($user['u_settings'] ?? '');
	if(!array_key_exists('paperdoll', $settings)) return '';
	if(!is_array($settings['paperdoll'])) throw new RuntimeException('形象读取失败，原有形象已保留。');
	return paperdoll_json($settings['paperdoll']);
}

function paperdoll_user_display_state($user)
{
	try { return paperdoll_display_state(paperdoll_user_raw($user)); }
	catch(RuntimeException $e) {
		$state = paperdoll_default(); $state['free_used'] = true;
		return $state;
	}
}

function paperdoll_sql($value) { return "X'".bin2hex((string)$value)."'"; }

function paperdoll_current_user($username, $cookiepass)
{
	$users = fetch_udata('uid,username,password,groupid,gold,u_settings', 'username='.paperdoll_sql($username));
	$user = $users ? reset($users) : null;
	if(!$user || (int)$user['groupid'] <= 0 || !pass_compare($user['username'], $cookiepass, $user['password'])) throw new InvalidArgumentException('登录状态已失效，请重新登录。');
	return $user;
}

function paperdoll_account_state($user, $catalog = null)
{
	$raw = paperdoll_user_raw($user);
	// credits2 是编辑器既有响应字段；DTS 的切糕实际储存在 gold。
	return array('state' => paperdoll_load($raw, $catalog), 'base' => hash('sha256', $raw), 'credits2' => (int)$user['gold']);
}

function paperdoll_save($user, $input, $catalog = null)
{
	global $db, $gtablepre;
	if($catalog === null) $catalog = paperdoll_catalog();
	if(!is_array($input) || !isset($input['base']) || !is_string($input['base']) || !preg_match('/\A[0-9a-f]{64}\z/', $input['base'])) throw new InvalidArgumentException('页面已失效，请刷新后重试。');
	$raw = paperdoll_user_raw($user);
	$old = paperdoll_load($raw, $catalog);
	$next = array('free_used' => $old['free_used'], 'revision' => $old['revision']) + paperdoll_normalize_appearance($input['appearance'] ?? null, $catalog);
	$changed = paperdoll_changed_count($old, $next, $catalog);
	if($changed === 0 && $next['classic'] === $old['classic']) {
		// 相同配置的重试不再次扣费，旧页面也不能覆盖另一个已经保存的形象。
		$message = hash_equals(hash('sha256', $raw), $input['base']) ? '形象没有变化，未消耗切糕。' : '形象已保存，重复请求未再次扣费。';
		return paperdoll_account_state($user, $catalog) + array('cost' => 0, 'changed' => 0, 'message' => $message, 'cache_settings' => $user['u_settings'] ?? '');
	}
	if(!hash_equals(hash('sha256', $raw), $input['base'])) throw new InvalidArgumentException('形象已在其他页面更新，请刷新后重新选择。');
	$cost = $old['free_used'] ? $changed * $catalog['unit_price'] : 0;
	if((int)$user['gold'] < $cost) throw new InvalidArgumentException('切糕不足，形象未保存。');
	if($changed > 0) $next['free_used'] = true;
	// 换回曾用过的形象也产生新状态，避免旧页面的保存凭据重新生效。
	$next['revision']++;
	$settings = paperdoll_settings($user['u_settings'] ?? '');
	$settings['paperdoll'] = $next;
	$encoded = gencode($settings);
	// 外观、首次免费标记和扣费在同一账户行内条件更新，兼容既有 MyISAM 部署。
	$query = "UPDATE {$gtablepre}users SET u_settings=".paperdoll_sql($encoded).",gold=gold-".$cost.
		" WHERE username=".paperdoll_sql($user['username'])." AND password=".paperdoll_sql($user['password'])." AND groupid>0 AND BINARY COALESCE(u_settings,'')=".paperdoll_sql($user['u_settings'] ?? '')." AND gold>=".$cost;
	if(!$db->query($query, 'SILENT')) throw new RuntimeException('无法保存纸娃娃账户。');
	if($db->affected_rows() !== 1) throw new InvalidArgumentException('形象或切糕余额已变化，请刷新后重试；本次未扣费。');
	$result = $db->query("SELECT uid,username,password,groupid,gold,u_settings FROM {$gtablepre}users WHERE username=".paperdoll_sql($user['username']), 'SILENT');
	if(!$result || !($user = $db->fetch_array($result))) throw new RuntimeException('无法读取已保存的形象，请刷新确认。');
	$message = $changed === 0 ? '显示方式已保存，未消耗切糕。' : (!$old['free_used'] ? '形象已保存，本次首次自定义免费。' : '形象已保存，消耗 '.$cost.' 切糕。');
	return paperdoll_account_state($user, $catalog) + array('cost' => $cost, 'changed' => $changed, 'message' => $message, 'cache_settings' => $user['u_settings']);
}

function paperdoll_request($username, $cookiepass, $input)
{
	global $db, $gtablepre, $userdb_remote_storage, $userdb_forced_local;
	if(!empty($userdb_remote_storage) && empty($userdb_forced_local)) {
		$result = curl_udata_cmd('paperdoll_save', $username, $cookiepass, gencode($input));
		if(!is_array($result) || !empty($result['error'])) throw new InvalidArgumentException(is_array($result) && isset($result['error']) ? $result['error'] : '远端形象服务暂时不可用，请稍后重试。');
		if(!isset($result['state'], $result['cache_settings'], $result['credits2'])) throw new RuntimeException('远端形象服务响应无效。');
		if(!paperdoll_sync_local($username, $result['cache_settings'])) $result['message'] .= ' 游戏显示暂未同步，请刷新账户页面。';
	} else {
		$result = paperdoll_save(paperdoll_current_user($username, $cookiepass), $input);
	}
	unset($result['cache_settings']);
	return $result;
}

// 对局读本地镜像。只合并外观，不让迟到响应覆盖更新的形象、余额或其他设置。
function paperdoll_sync_local($username, $raw_settings)
{
	global $db, $gtablepre;
	$remote = paperdoll_settings($raw_settings);
	if(!isset($remote['paperdoll'])) return true;
	$next = paperdoll_load(paperdoll_json($remote['paperdoll']));
	for($attempt = 0; $attempt < 3; $attempt++) {
		$query = $db->query("SELECT u_settings FROM {$gtablepre}users WHERE username=".paperdoll_sql($username), 'SILENT');
		if(!$query) return false;
		$local = $db->fetch_array($query);
		// 缺失的本地用户仍由既有 fetch_udata 首次进入游戏时建立完整镜像。
		if(!$local) return true;
		$state = paperdoll_user_display_state($local);
		if($state['revision'] >= $next['revision']) return true;
		$settings = paperdoll_settings($local['u_settings'] ?? '');
		$settings['paperdoll'] = $next;
		$query = $db->query("UPDATE {$gtablepre}users SET u_settings=".paperdoll_sql(gencode($settings))." WHERE username=".paperdoll_sql($username)." AND BINARY COALESCE(u_settings,'')=".paperdoll_sql($local['u_settings'] ?? ''), 'SILENT');
		if(!$query) return false;
		if($db->affected_rows() === 1) return true;
	}
	return false;
}

// 普通资料、战后结算和物流会保存整包 u_settings，只允许专用付费入口改动 paperdoll。
function paperdoll_preserve_settings_update($updates, $where)
{
	global $db, $gtablepre;
	$incoming = paperdoll_settings($updates['u_settings']);
	$query = $db->query("SELECT * FROM {$gtablepre}users WHERE {$where}", 'SILENT');
	if(!$query) return false;
	$rows = array(); while($row = $db->fetch_array($query)) $rows[] = $row;
	foreach($rows as $row) {
		$saved = false;
		for($attempt = 0; $attempt < 3; $attempt++) {
			if($attempt) {
				$query = $db->query("SELECT * FROM {$gtablepre}users WHERE ({$where}) AND username=".paperdoll_sql($row['username']), 'SILENT');
				if(!$query || !($row = $db->fetch_array($query))) return false;
			}
			$current = paperdoll_settings($row['u_settings'] ?? '');
			$merged = $incoming;
			if(array_key_exists('paperdoll', $current)) $merged['paperdoll'] = $current['paperdoll'];
			else unset($merged['paperdoll']);
			$write = $updates; $write['u_settings'] = gencode($merged);
			$set = array();
			foreach($write as $field => $value) {
				if(!preg_match('/\A[a-zA-Z0-9_]+\z/', $field) || (!is_scalar($value) && $value !== null)) throw new RuntimeException('账户更新字段无效。');
				if((string)($row[$field] ?? '') !== (string)$value) $set[] = '`'.$field.'`='.paperdoll_sql($value);
			}
			if(!$set) { $saved = true; break; }
			$query = $db->query("UPDATE {$gtablepre}users SET ".implode(',', $set)." WHERE ({$where}) AND username=".paperdoll_sql($row['username'])." AND BINARY COALESCE(u_settings,'')=".paperdoll_sql($row['u_settings'] ?? ''), 'SILENT');
			if(!$query) return false;
			if($db->affected_rows() === 1) { $saved = true; break; }
		}
		if(!$saved) return false;
	}
	return true;
}
