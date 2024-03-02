<?php

namespace skill455
{
	$skill455_act_time = 360;
	$skill455_no_effect_array = Array(1,9,20,21,22,88);	//不免疫的npc类别
	
	function init() 
	{
		define('MOD_SKILL455_INFO','card;buffer;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[455] = '无敌';
		$bufficons_list[455] = Array(
			'disappear' => 1,
			'activate_hint' => '<span class="red b">技能「无敌」生效时间已经结束</span>',
		);
	}
	
	function acquire455(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill455'));
		if ($now < $starttime + $skill455_act_time) {
			\skillbase\skill_setvalue(455,'start_ts',$starttime,$pa);
			\skillbase\skill_setvalue(455,'end_ts',$starttime+$skill455_act_time,$pa);	
			\skillbase\skill_setvalue(455,'cd_ts',0,$pa);
		}else{
			\skillbase\skill_lost(455,$pa);
		}
	}
	
	function lost455(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked455(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function apply_total_damage_modifier_invincible(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if (1 == \bufficons\bufficons_check_buff_state(455,$pd) && !in_array($pa['type'],get_var_in_module('skill455_no_effect_array','skill455'))){	//scp和蓝凝无效
			eval(import_module('logger'));
			$pa['dmg_dealt']=0;
			if ($active) $log .= "<span class=\"yellow b\">敌人的技能「无敌」使你的攻击没有造成任何伤害！</span><br>";
			else $log .= "<span class=\"yellow b\">你的技能「无敌」使敌人的攻击没有造成任何伤害！</span><br>";
		}
		$chprocess($pa,$pd,$active);
	}
	
	function kill(&$pa, &$pd)	//在遇到SCP或蓝凝而死时放嘲讽
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		if (in_array($pd['state'],Array(20,21,22,23,24,25,27,29)) && in_array($pa['type'],get_var_in_module('skill455_no_effect_array','skill455')) && 1 == \bufficons\bufficons_check_buff_state(455,$pd))
		{
			eval(import_module('logger'));
			$log.="<span class=\"cyan b\">都告诉你了，无敌对某些NPC无效……快去死吧。</span><br>";
		}
		return $ret;
	}
	
	function get_trap_final_damage_modifier_down(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == \bufficons\bufficons_check_buff_state(455,$pd))
		{
			eval(import_module('logger'));
			$log .= "<span class=\"yellow b\">你的技能「无敌」使你免疫了陷阱伤害！</span><br>";
			return 0;
		}	
		return $chprocess($pa,$pd,$tritm,$damage);
	}
}

?>