<?php

namespace skill965
{
	function init() 
	{
		define('MOD_SKILL965_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[965] = '聚合';
	}
	
	function acquire965(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost965(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked965(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_defdown965(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(964, $pa)) return 0;
		$pcount964 = \skill964\get_packcount964($pa);
		$defdown = 0;
		foreach ($pcount964 as $v)
		{
			if ($v > 0) $defdown += 10;
		}
		return $defdown;
	}
	
	function get_external_def_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(965, $pa))
		{
			$defdown = get_defdown965($pa);
			if ($defdown > 0)
			{
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"yellow b\">「聚合」使你的攻击无视了敌人{$defdown}%的装备防御力！</span><br>";
				else  $log .= "<span class=\"yellow b\">「聚合」使敌人的攻击无视了你{$defdown}%的装备防御力！</span><br>";
			}
			return $chprocess($pa, $pd, $active) * (1 - $defdown / 100);
		}
		else return $chprocess($pa, $pd, $active);
	}
	
}

?>
