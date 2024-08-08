<?php

namespace skill756
{
	$skill756_buff = array(
		1 => "受到属性伤害变为等量回复",
		2 => "受到物理伤害变为等量回复",
		3 => "受到全部伤害变为等量回复",
	);
	
	$skill756_recover_rate = array(
		0 => 50,
		1 => 80,
	);
	
	function init() 
	{
		define('MOD_SKILL756_INFO','card;feature;');
		eval(import_module('clubbase'));
		$clubskillname[756] = '解意';
	}
	
	function acquire756(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(756,'lvl',0,$pa);
		\skillbase\skill_setvalue(756,'btype',rand(1,3),$pa);
	}
	
	function lost756(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(756,'lvl',$pa);
		\skillbase\skill_delvalue(756,'btype',$pa);
	}
	
	function check_unlocked756(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//切换状态
	function skill756_btype_change(&$pa, $showlog = 1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$btype = (int)\skillbase\skill_getvalue(756,'btype',$pa);
		$btype_new = array_randompick(array_diff(range(1,3), [$btype]));
		\skillbase\skill_setvalue(756,'btype',$btype_new,$pa);
		if ($showlog)
		{
			eval(import_module('logger'));
			$log .= "<span class=\"lime b\">{$pa['name']}切换了状态，准备吸收";
			if ($btype_new == 1) $log .= "属性伤害！</span><br>";
			elseif ($btype_new == 2) $log .= "物理伤害！</span><br>";
			elseif ($btype_new == 3) $log .= "全部伤害！</span><br>";
		}
	}
	
	//先攻切换状态
	function attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!$pa['is_counter'] && \skillbase\skill_query(756,$pa) && check_unlocked756($pa))
		{
			skill756_btype_change($pa);
		}
		$chprocess($pa, $pd, $active);
	}
	
	//挨打切换状态
	function attack_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(756,$pd) && check_unlocked756($pd) && $pd['hp'] && $pa['is_hit'])
		{
			skill756_btype_change($pd);
		}
		$chprocess($pa, $pd, $active);
	}
	
	//免伤
	function check_physical_nullify(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(756,$pd) && check_unlocked756($pd) && in_array((int)\skillbase\skill_getvalue(756,'btype',$pd), array(2,3)))
		{
			eval(import_module('logger','skill756'));
			$clv = (int)\skillbase\skill_getvalue(756,'lvl',$pd);
			$phy_dmg = \weapon\get_physical_dmg($pa, $pd, $active);
			$hpup = min($phy_dmg, ceil($skill756_recover_rate[$clv] * $pd['mhp'] / 100));
			$pd['hp'] += $hpup + 1;
			if ($active) $log .= "<span class=\"yellow b\">{$pd['name']}完全吸收了你造成的物理伤害，回复了{$hpup}点生命值！</span><br>";
			else $log .= "<span class=\"yellow b\">你完全吸收了{$pa['name']}造成的物理伤害，回复了{$hpup}点生命值！</span><br>";
			$pd['physical_nullify_success'] = 1;
			return Array();
		}
		return $chprocess($pa, $pd, $active);
	}
	
	function check_ex_dmg_nullify(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		$ex_att_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
		//有属性攻击才进入判断
		$flag = 0;
		foreach ($ex_attack_list as $key) {
			if (\attrbase\check_in_itmsk($key,$ex_att_array)) { 
				$flag = 1;
				break;
			}
		}
		if ($flag && \skillbase\skill_query(756,$pd) && check_unlocked756($pd) && in_array((int)\skillbase\skill_getvalue(756,'btype',$pd), array(1,3)))
		{
			eval(import_module('logger','skill756'));
			$clv = (int)\skillbase\skill_getvalue(756,'lvl',$pd);
			$ex_dmg = \ex_dmg_att\calculate_ex_attack_dmg_base($pa, $pd, $active);
			$hpup = min($ex_dmg, ceil($skill756_recover_rate[$clv] * $pd['mhp'] / 100));
			$pd['hp'] += $hpup + 1;
			if ($active) $log .= "<span class=\"yellow b\">{$pd['name']}完全吸收了你造成的属性伤害，回复了{$hpup}点生命值！</span><br>";
			else $log .= "<span class=\"yellow b\">你完全吸收了{$pa['name']}造成的属性伤害，回复了{$hpup}点生命值！</span><br>";
			$pa['ex_dmg_dealt'] = 1;
			$pd['exdmg_nullify_success'] = 1;
			return 1;
		}
		return $chprocess($pa, $pd, $active);
	}
	
}

?>
