<?php

namespace skill499
{
	$skill499_act_time = 110;
	$skill499_no_effect_array = Array();//Array(1,9,20,21,22,88);	//不免疫的npc类别
	
	function init() 
	{
		define('MOD_SKILL499_INFO','card;buffer;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[499] = '决然';
		$bufficons_list[499] = Array(
			'disappear' => 1,
		);
	}
	
	function acquire499(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill499'));
		\skillbase\skill_setvalue(499,'start_ts',$now,$pa);	
		\skillbase\skill_setvalue(499,'end_ts',$now + $skill499_act_time,$pa);	
		\skillbase\skill_setvalue(499,'cd_ts',$now + $skill499_act_time,$pa);
	}
	
	function lost499(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked499(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function apply_total_damage_modifier_invincible(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == \bufficons\bufficons_check_buff_state(499, $pd)){
			eval(import_module('logger','skill499'));
			if(!in_array($pa['type'],$skill499_no_effect_array)) {
				$pa['dmg_dealt']=0;
				$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class='yellow b'><:pd_name:>的技能「决然」使<:pa_name:>的攻击没有造成任何伤害！</span><br>");
			}
		}
		$chprocess($pa,$pd,$active);
	}
	
	function get_trap_final_damage_modifier_down(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(499,$pd)) return $chprocess($pa,$pd,$tritm,$damage);
		if ($damage && 1 == \bufficons\bufficons_check_buff_state(499, $pd))
		{
			eval(import_module('logger'));
			$log .= "<span class=\"yellow b\">你的技能「决然」使你免疫了陷阱伤害！</span><br>";
			return 0;
		}	
		return $chprocess($pa,$pd,$tritm,$damage);
	}
}

?>