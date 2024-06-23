<?php

namespace skill752
{
	function init() 
	{
		define('MOD_SKILL752_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[752] = '重力';
	}
	
	function acquire752(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost752(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked752(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_hitrate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(752, $pa) || \skillbase\skill_query(752, $pd)) $r *= 5/3;
		return $chprocess($pa, $pd, $active)*$r;
	}
	
}

?>