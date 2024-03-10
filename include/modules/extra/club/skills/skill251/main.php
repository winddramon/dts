<?php

namespace skill251
{
	$skill251_act_time = 5;
	$skill251_no_effect_array = Array(1,9,20,21,22,88);
	
	function init() 
	{
		define('MOD_SKILL251_INFO','club;buffer;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[251] = '天佑';
		$bufficons_list[251] = Array(
			'disappear' => 0,
			'clickable' => 0,
			'hint' => "技能「天佑」生效中，免疫一切战斗或陷阱伤害<br>（对少数NPC无效）",
			'activate_hint' => "技能「天佑」目前未触发",
		);
	}
	
	function acquire251(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(251,'end_ts',1,$pa);
		\skillbase\skill_setvalue(251,'cd_ts',0,$pa);
	}
	
	function lost251(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked251(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill251_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \bufficons\bufficons_check_buff_state(251,$pa);
	}
	
	function apply_total_damage_modifier_invincible(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if(1 == check_skill251_state($pd)) {
			eval(import_module('logger','skill251'));
			if(!in_array($pa['type'],$skill251_no_effect_array)){
				$pa['dmg_dealt']=0;
				if ($active) $log .= "<span class=\"yellow b\">敌人的技能「天佑」使你的攻击没有造成任何伤害！</span><br>";
				else $log .= "<span class=\"yellow b\">你的技能「天佑」使敌人的攻击没有造成任何伤害！</span><br>";
			}
		}

		$chprocess($pa,$pd,$active);
	}
	
	function kill(&$pa, &$pd)	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		eval(import_module('skill251'));
		if (in_array($pd['state'],Array(20,21,22,23,24,25,27,29)))
			if (\skillbase\skill_query(251,$pd) && in_array($pa['type'],$skill251_no_effect_array) && 1 == check_skill251_state($pd))
			{
				eval(import_module('logger'));
				$log.="<span class=\"cyan b\">都告诉你了，无敌对某些NPC无效……快去死吧。</span><br>";
			}
		
		return $ret;
	}
	
	function get_trap_final_damage_modifier_down(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(1 == check_skill251_state($pd)) {
			eval(import_module('logger'));
			$log .= "<span class=\"yellow b\">你的技能「天佑」使你免疫了陷阱伤害！</span><br>";
			$ret = 0;
		}else {
			$ret = $chprocess($pa,$pd,$tritm,$damage);
		}
		return $ret;
	}
	
	function apply_damage(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(251,$pd) && $pa['dmg_dealt']>=$pd['mhp']*0.35 && $pd['hp']>0)
		{
			eval(import_module('logger','skill251'));
			$flag = \bufficons\bufficons_set_timestamp(251, $skill251_act_time, 0);
			if(!$flag) {
				$log.='发动失败！<br>';
			}else{
				if ($active) 
					$log .= '<span class="yellow b">敌人的技能「天佑」被触发，暂时进入了无敌状态。</span><br>';
				else  $log .= '<span class="yellow b">你的技能「天佑」被触发，暂时进入了无敌状态！</span><br>';
			}
		}
		return $ret;
	}
	
	function post_traphit_events($pa, $sdata, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(251,$sdata) && $damage>=$sdata['mhp']*0.35 && $sdata['hp']>0)
		{
			eval(import_module('logger','skill251'));
			$flag = \bufficons\bufficons_set_timestamp(251, $skill251_act_time, 0);
			if(!$flag) {
				$log.='发动失败！<br>';
			}else{
				$log .= '<span class="yellow b">你的技能「天佑」被触发，暂时进入了无敌状态！</span><br>';
			}
		}
		$chprocess($pa, $sdata, $tritm, $damage);
	}
}

?>