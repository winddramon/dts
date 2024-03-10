<?php

namespace skill500
{
	$skill500_cd = 30;
	$skill500_act_time = 3;
	
	$skill500_rage = 30;
	
	function init() 
	{
		define('MOD_SKILL500_INFO','card;upgrade;timectl;buffer;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[500] = '时停';
		$bufficons_list[500] = Array(
			'msec' => 1,
		);
	}
	
	function acquire500(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(500,'end_ts',1,$pa);	
		\skillbase\skill_setvalue(500,'cd_ts',0,$pa);
	}
	
	function lost500(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked500(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate500()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill500','player','logger','sys'));
		\player\update_sdata();

		//所有主动技能统一的触发语句
		list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(500, $skill500_act_time, $skill500_cd, $sdata, 1, 1);//考虑毫秒
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}
		
		addnews ( 0, 'bskill500', $name );
		$gamevars['timestopped'] = 1;//设一个全局变量
		save_gameinfo();
		$rage -= $skill500_rage;
		//所有同地点玩家获得3秒的眩晕
		$pids = array();
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type=0 AND pls='$pls' AND pid!='$pid'");
		if($db->num_rows($result)){
			while($r = $db->fetch_array($result)){
				$pids[] = $r['pid'];
			}
		}
		foreach($pids as $piv){
			$edata = \player\fetch_playerdata_by_pid($piv);
			if(check_timectl($edata)) continue; //拥有“时间操作”标签的技能的角色不受影响
			\skill603\set_stun_period603($skill500_act_time*1000,$edata);
			//就不挨个发送提示了
			\skillbase\skill_setvalue(603,'timestop',1,$edata);
			\player\player_save($edata);
		}
		
		$log.='<span class="lime b">技能「时停」发动成功，你让时间暂时停止了！</span><br>';
	}

	//能否触发技能的特殊判定
	function bufficons_check_buff_state_shell($token, &$pa=NULL, $msec=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($can_activate, $fail_hint) = $chprocess($token, $pa, $msec);
		if ($can_activate && 500 == $token){
			eval(import_module('skill500'));
			if(!$pa) {
				eval(import_module('player'));
				$pa = & $sdata;
			}
			if($pa['rage'] < $skill500_rage){
				$fail_hint.='怒气不足，需要<span class="yellow b">'.$skill500_rage.'点怒气</span>！<br>';
				$can_activate = false;
			}
		}
		return array($can_activate, $fail_hint);
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill500_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \bufficons\bufficons_check_buff_state(500, $pa);
	}
	
	//如果是时间停止导致的眩晕，在眩晕结束时自动判定一下是不是需要发送时间停止结束的讯息
	function lost603(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		eval(import_module('sys'));
		if(!empty($gamevars['timestopped'])) {
			$gamevars['timestopped'] = 0;
			save_gameinfo();
			addnews ( 0, 'bskill500_end');
		}
	}
	
	//命中率变为原来的150%
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($pa, $pd, $active);
		if (1 == check_skill500_state($pa) && !check_timectl($pd)) $ret *= 1.5;
		return $ret;
	}
	
	//不会被发现
	function check_alive_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill500_state($edata) && !check_timectl())
			return 0;
		else  return $chprocess($edata);
	}
	
	//不会被先手
	function check_enemy_meet_active(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill500_state($ldata) && !check_timectl($edata)) {
			eval(import_module('enemy'));
			$findenemy_active_words = '在只属于你的时间里，对方毫无防备、任你宰割！';
			return 1;
		}
		return $chprocess($ldata,$edata);
	}
	
	//不会踩到陷阱
	function calculate_real_trap_obbs_change($var)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($var);
		eval(import_module('player'));
		if(1 == check_skill500_state($sdata)) $ret = 0;
		return $ret;
	}
	
	//不会受到敌人反击
	//若要接管此函数，请阅读base\battle\battle.php里的注释，并加以判断
	function check_can_counter(&$pa, &$pd, $active)			
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//注意判定的是$pa能否反击$pd
		if (1 == check_skill500_state($pd) && !check_timectl($pa)) return 0; 
		return $chprocess($pa, $pd, $active);
	}

	//不会有无法反击的flag
	function player_cannot_counter(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!(1 == check_skill500_state($pd) && !check_timectl($pa))) $chprocess($pa, $pd, $active);
	}

	//判定$pa是否拥有带有时间操作标签的技能，如果有则返回1，否则返回0
	function check_timectl(&$pa = NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \skillbase\check_have_skill_info('timectl;', $pa);
	}

	//第一次进入CD时侦测一下全局变量
	function bufficons_display_single($token, $config, &$pa=NULL) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//事先记录全局时间状态
		if(500 == $token && \skillbase\skill_query(500,$pa) && check_unlocked500($pa)) {
			$timestopped_flag = & get_var_in_module('gamevars', 'sys')['timestopped'];
		}
		list($src, $config_ret) = $chprocess($token, $config, $pa);
		if(!empty($timestopped_flag) && $config_ret['style'] >= 2) {
			$timestopped_flag = 0;
			save_gameinfo();
			addnews ( 0, 'bskill500_end');
		}
		return Array($src, $config_ret);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill500') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能「时停」，让时间暂时停止了流动！</span></li>";
		elseif($news == 'bskill500_end') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">时间重新开始流动了！</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>