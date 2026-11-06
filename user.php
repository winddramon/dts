<?php

define('CURSCRIPT', 'user');

require './include/common.inc.php';

eval(import_module('cardbase'));

require_once GAME_ROOT.'./include/paperdoll.func.php';

if(isset($mode) && $mode === 'paperdoll') {
	header('Content-Type: application/json; charset=utf-8');
	header('Cache-Control: no-store');
	if(ob_get_length()) ob_clean();
	try {
		if(empty($cuser) || empty($cpass) || empty($cudata) || (int)$cudata['groupid'] <= 0 || !pass_compare($cuser, $cpass, $cudata['password'])) throw new InvalidArgumentException('登录状态已失效，请重新登录。');
		if(($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') throw new InvalidArgumentException('请通过形象编辑器保存。');
		// 直接读取 JSON，不经过旧表单过滤对引号的替换。
		$paperdoll_body = file_get_contents('php://input', false, null, 0, 8193);
		$paperdoll_input = strlen($paperdoll_body) <= 8192 ? json_decode($paperdoll_body, true) : null;
		if(!is_array($paperdoll_input) || !isset($paperdoll_input['csrf']) || !is_string($paperdoll_input['csrf']) || !hash_equals(hash_hmac('sha256', 'paperdoll:'.$cuser, $cpass), $paperdoll_input['csrf'])) throw new InvalidArgumentException('页面已失效，请刷新后重试。');
		echo paperdoll_json(paperdoll_request($cuser, $cpass, $paperdoll_input));
	} catch(InvalidArgumentException $e) {
		http_response_code(409);
		echo paperdoll_json(array('error' => $e->getMessage()));
	} catch(Throwable $e) {
		http_response_code(503);
		error_log('Paperdoll: '.$e->getMessage());
		echo paperdoll_json(array('error' => '形象暂时无法保存，请稍后重试。'));
	} finally { release_user_lock_from_pool(); }
	exit;
}

$udata = udata_check();

if(!isset($mode)){
	$mode = 'show';
}
if($mode == 'edit') {
	//各项参数初始化
	$gamedata=Array();$gamedata['innerHTML']['info'] = '';
	$passarr = array();
	//修改密码判定
	if($opass && $npass && $rnpass){
		$pass_right = true;
		$pass_check = pass_check($npass,$rnpass);
		if($pass_check!='pass_ok'){
			$gamedata['innerHTML']['info'] .= $_ERROR[$pass_check];
			$pass_right = false;
		}
		$opass = create_cookiepass($opass);
		$npass = create_cookiepass($npass);
		if(!pass_compare($udata['username'], $opass, $udata['password'])){
			$gamedata['innerHTML']['info'] .= $_ERROR['wrong_pw'];
			$pass_right = false;
		}
		if($pass_right){
			gsetcookie('pass',$npass);
			$nspass = create_storedpass($udata['username'], $npass);
			$passarr = array('password' => $nspass, 'alt_pswd' => 1);
			$gamedata['innerHTML']['info'] .= $_INFO['pass_success'];
		}else{
			$gamedata['innerHTML']['info'] .= $_INFO['pass_failure'];
		}
	}else{
		$gamedata['innerHTML']['info'] .= $_INFO['pass_failure'];
	}
	$cardlist = \cardbase\get_cardlist_energy_from_udata($udata)[0];
	if(!in_array($card, $cardlist)) {
		$card = 0;
	}

	$updarr = Array();
	//有字段的资料的修改
	$updkeys = Array('gender', 'icon', 'motto', 'killmsg', 'lastword', 'card', 'u_templateid');
	
	foreach($updkeys as $pm) {
		if(isset(${$pm}) && ${$pm} != $udata[$pm]) {
			$updarr[$pm] = ${$pm};
		}
	}
	//$u_settings字段的修改。这个字段是用gencode格式储存的
	$u_settings = \user_settings\get_u_settings($udata);
	
	$ustgkeys = Array(
		'skip_opening' => 'int', //是否跳过开场剧情
		'item_auto_merge' => 'int', //是否自动合并可合并的道具
		'gscroll_type' => 'int',//滚动条样式
	);
	$ustgflag = 0;
	foreach($ustgkeys as $pm => $tp) {
		if(isset(${$pm}) && (!isset($u_settings[$pm]) || ${$pm} != $u_settings[$pm])) {
			$u_settings[$pm] = ${$pm};
			if('int' == $tp) $u_settings[$pm] = (int)$u_settings[$pm];
			$ustgflag = 1;
		}
	}
	if($ustgflag) {
		$updarr['u_settings'] = gencode($u_settings);
	}

	//把密码的修改合并进$updarr
	if(!empty($passarr)) 
		$updarr = array_merge($updarr, $passarr);
	
	if(!empty($updarr)) {
		$gamedata['innerHTML']['info'] .= update_udata_by_username($updarr, $cuser) !== false ? $_INFO['data_success'] : $_INFO['data_failure'];
	}else{
		$gamedata['innerHTML']['info'] .= $_INFO['data_failure'];
	}

	//$gamedata['innerHTML']['info'] .= var_export($updarr,1).' '.var_export($u_settings, 1);

	$gamedata['value']['opass'] = $gamedata['value']['npass'] = $gamedata['value']['rnpass'] = '';
	if(isset($error)){$gamedata['innerHTML']['error'] = $error;}
	ob_clean();
	$jgamedata = gencode($gamedata);
	echo $jgamedata;
	ob_end_flush();
	
} else {
	//把$udata中的内容全部解出来
	extract($udata);
	//$iconarray = get_iconlist($icon);
	$select_icon = $icon;
	
	//卡片部分
	$userCardData = \cardbase\get_user_cardinfo($cuser);
	$card_ownlist = $userCardData['cardlist'];;
	$card_energy = $userCardData['cardenergy'];
	$card_data_fetched = $userCardData['card_data'];
	$cardChosen = $userCardData['cardchosen'];
	$card_disabledlist=Array();
	$card_error=Array();
	$packlist = \cardbase\pack_filter($packlist);
	
	$card_achieved_list = array();
	if(defined('MOD_ACHIEVEMENT_BASE')) {
		$d_achievements = \achievement_base\decode_achievements($udata);
		if(!empty($d_achievements['326'])) $card_achieved_list = $d_achievements['326'];
	}

	//u_settings部分，gdecode之后全部解出来，用于显示
	if(!empty($u_settings)) {
		$u_settings = gdecode($u_settings, 1);
	}
	if(!is_array($u_settings)) {
		$u_settings = Array();
	}
	extract($u_settings);

	header('Cache-Control: no-store');
	try {
		$paperdoll_account = paperdoll_current_user($cuser, $cpass);
		if(!empty($userdb_remote_storage) && empty($userdb_forced_local) && !paperdoll_sync_local($cuser, $paperdoll_account['u_settings'])) throw new RuntimeException('游戏形象镜像尚未同步。');
		$paperdoll_boot = paperdoll_json(paperdoll_account_state($paperdoll_account) + array('catalog' => paperdoll_catalog(), 'csrf' => hash_hmac('sha256', 'paperdoll:'.$cuser, $cpass), 'endpoint' => 'user.php?mode=paperdoll'));
	} catch(Throwable $e) {
		error_log('Paperdoll bootstrap: '.$e->getMessage());
		$paperdoll_boot = paperdoll_json(array('error' => '形象编辑器暂时无法载入，请稍后重试。'));
	}
	
	include template('user');
}

?>
