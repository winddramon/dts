<?php

namespace skill765
{
	function init() 
	{
		define('MOD_SKILL765_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[765] = '先取';
	}
	
	function acquire765(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost765(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked765(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill'])
		{
			$chprocess($pa, $pd, $active);
			return;
		}
		if (\skillbase\skill_query(765, $pd) && check_unlocked765($pd))
		{
			$skills_pd = \skillbase\get_acquired_skill_array($pd);
			$blist_e = array();
			foreach ($skills_pd as $key)
			{
				if (\skillbase\check_skill_info($key, 'battle') && \clubbase\skill_query_unlocked($key, $pd) && !(\clubbase\check_battle_skill_unactivatable($pa, $pd, $key)))
				{
					$blist_e[] = $key;
				}
			}
			if (!empty($blist_e)) $pa['sk765_flag'] = 1;
		}
		$chprocess($pa, $pd, $active);
		if (!empty($pa['sk765_flag']))
		{
			$bskid = array_randompick($blist_e);
			eval(import_module('player', 'clubbase'));
			$skname = '';
			if (!empty($clubskillname[$bskid])) $skname = '「'.$clubskillname[$bskid].'」';
			eval(import_module('logger'));
			if ($active) $log .= "<span class=\"yellow b\">你用敌人的招式{$skname}向敌人发起了攻击！</span><br>";
			else $log .= "<span class=\"yellow b\">敌人用你的招式{$skname}向你发起了攻击！</span><br>";
			$pa['bskill'] = $bskid;
		}
	}
	
}

?>