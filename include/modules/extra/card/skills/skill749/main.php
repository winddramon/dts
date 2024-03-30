<?php

namespace skill749
{
	function init() 
	{
		define('MOD_SKILL749_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[749] = '逆袭';
	}
	
	function acquire749(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost749(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked749(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(749, $pa) && check_unlocked749($pa) && ($pd['type'] == 0))
		{
			eval(import_module('cardbase'));
			if (!empty($pd['card']) && $cards[$pd['card']]['rare'] == 'S')
			{
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"red b\">你的叛逆心理被{$pd['name']}激发了！</span><br>";
				else $log .= "<span class=\"red b\">你激发了{$pa['name']}的叛逆心理！</span><br>";
				\skillbase\skill_acquire(606, $pd);
				\skill_temp\set_skill_temp_time(606, 30, $pd);
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(749, $pa) && check_unlocked749($pa) && ($pd['type'] == 0)) 
		{
			eval(import_module('cardbase'));
			if (!empty($pd['card']) && $cards[$pd['card']]['rare'] == 'S')
			{
				$r[] = 1.3;
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}

}

?>
