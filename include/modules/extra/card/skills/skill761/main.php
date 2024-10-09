<?php

namespace skill761
{
	function init()
	{
		define('MOD_SKILL761_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[761] = '两仪';
	}
	
	function acquire761(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost761(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked761(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_search_sp_cost()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(761, $sdata) && check_unlocked761($sdata) && $rage <= 50) return $chprocess()-12;
		return $chprocess();
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = Array();
		$dmgrate = 1;
		if (\skillbase\skill_query(761, $pa) && check_unlocked761($pa) && $pa['rage'] > 50) $dmgrate *= 2;
		if (\skillbase\skill_query(761, $pd) && check_unlocked761($pd) && $pd['rage'] > 50) $dmgrate *= 2;
		if ($dmgrate > 1)
		{
			eval(import_module('logger'));
			$var_761 = 100 * $dmgrate;
			$log .= "<span class=\"red b\">愤怒状态使此次伤害增加至{$var_761}%！</span><br>";
			$r = array($dmgrate);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>
