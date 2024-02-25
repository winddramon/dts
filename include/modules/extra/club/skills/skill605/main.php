<?php

namespace skill605
{
	
	function init() 
	{
		define('MOD_SKILL605_INFO','hidden;debuff;card;');
		eval(import_module('clubbase'));
		$clubskillname[605] = '夜盲';
	}
	
	function acquire605(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost605(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function calc_memory_slotnum(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa);
		if (\skillbase\skill_query(605, $pa)) $ret = min(2, $ret);
		return $ret;
	}
}

?>