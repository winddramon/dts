<?php

namespace skill246
{
	$skill246_act_time = 45;
	
	function init() 
	{
		define('MOD_SKILL246_INFO','club;upgrade;locked;unique;');
		eval(import_module('clubbase','wep_j','dualwep','bufficons'));
		$clubskillname[246] = '隐身';
		$wj_allowed_bskill[] = 246;
		$dualwep_allowed_bskill[] = 246;
		$bufficons_list[246] = Array(
			'disappear' => 1,
		);
	}
	
	function acquire246(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill246'));
		\skillbase\skill_setvalue(246,'end_ts',1,$pa);	
		\skillbase\skill_setvalue(246,'cd_ts',0,$pa);
	}
	
	function lost246(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked246(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate246()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill246','player','logger','sys'));
		\player\update_sdata();
		list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(246, $skill246_act_time, 0);
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}
		$log.='<span class="lime b">你发动了技能「隐身」，完全融入了阴影中！</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill246_state(&$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \bufficons\bufficons_check_buff_state(246, $pa);
	}
	
	//隐身状态下攻击不会显示战斗技
	function check_battle_skill_available(&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill246_state()) return false;
		else return $chprocess($edata,$skillno);
	}
	
	//隐身状态下攻击不允许使用其他战斗技能，但自动触发破隐效果
	//隐身刚结束那一下无法使用任何技能
	function load_user_battleskill_command(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$st = check_skill246_state($pdata);
		if (1 == $st)
			$pdata['bskill']=246; 
		elseif (2 == $st)
			$pdata['bskill']=0; 
		else $chprocess($pdata);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=246) {
			$chprocess($pa, $pd, $active);
			return;
		}
		eval(import_module('logger'));
		if (!check_skill246_state($pa))
		{
			$log .= '你没有这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$log .= '<span class="yellow b">敌人完全没有预料到你的存在，你对着措手不及的敌人发起了致命一击！</span><br>';
			\skillbase\skill_lost(246, $pa);
			addnews ( 0, 'bskill246', $pa['name'], $pd['name'] );
		}
		$chprocess($pa, $pd, $active);
	}	
	
	//命中率增加
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($pa, $pd, $active);
		if ($pa['bskill']!=246) return $ret;
		return $ret*1.3;
	}
	
	//不会被玩家发现
	function check_alive_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill246_state($edata)) $ret = 0;
		else $ret = $chprocess($edata);
		return $ret;
	}
	
	//不会被先手
	function check_enemy_meet_active(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill246_state($ldata)) $ret = 1;
		else $ret = $chprocess($ldata,$edata);
		return $ret;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill246') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「破隐一击」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>