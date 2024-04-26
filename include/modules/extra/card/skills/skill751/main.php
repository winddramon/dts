<?php

namespace skill751
{
	function init() 
	{
		define('MOD_SKILL751_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[751] = '史家';
	}
	
	function acquire751(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost751(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked751(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function meetman_alternative($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(751, $sdata) && check_unlocked751($sdata) && ($edata['type'] == 0))
		{
			\team\findteam($edata);
			return;
		}
		$chprocess($edata);
	}
	
	function senditem_check_teammate($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(751, $sdata) && check_unlocked751($sdata) && ($edata['type'] == 0))
		{
			return true;
		}
		return $chprocess($edata);
	}
}

?>
