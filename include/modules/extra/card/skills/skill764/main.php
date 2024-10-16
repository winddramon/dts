<?php

namespace skill764
{
	function init() 
	{
		define('MOD_SKILL764_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[764] = '炸鱼';
	}
	
	function acquire764(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost764(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked764(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_sk764_dmggain(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa_wingames = \skillbase\skill_getvalue(1003, 'wingames', $pa);
		$pd_wingames = \skillbase\skill_getvalue(1003, 'wingames', $pd);
		if (NULL === $pa_wingames)
		{
			$udata = fetch_udata_by_username($pa['name']);
			$pa_wingames = $udata['wingames'];
			\skillbase\skill_setvalue(1003, 'wingames', $pa_wingames, $pa);	
		}
		if (NULL === $pd_wingames)
		{
			$udata = fetch_udata_by_username($pd['name']);
			$pd_wingames = $udata['wingames'];
			\skillbase\skill_setvalue(1003, 'wingames', $pd_wingames, $pd);	
		}
		if ($pa_wingames > $pd_wingames) return 30;
		return -10;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (!$pa['type'] && !$pd['type'] && \skillbase\skill_query(764, $pa) && check_unlocked764($pa))
		{
			eval(import_module('logger'));
			$sk764_dmggain = get_sk764_dmggain($pa, $pd);
			if ($sk764_dmggain > 0)
			{
				if ($active) $log .= "<span class=\"yellow b\">你丰富的鱼塘遨游经验使你造成的最终伤害增加了{$sk764_dmggain}%！</span><br>";
				else $log .= "<span class=\"yellow b\">敌人丰富的鱼塘遨游经验使敌人造成的最终伤害增加了{$sk764_dmggain}%！</span><br>";
				$r = array(1 + $sk764_dmggain / 100);
			}
			elseif ($sk764_dmggain < 0)
			{
				$sk764_dmgreduce = -$sk764_dmggain;
				if ($active) $log .= "<span class=\"yellow b\">你在鱼塘里遇到了鲨鱼，最终伤害降低了{$sk764_dmgreduce}%！</span><br>";
				else $log .= "<span class=\"yellow b\">敌人在鱼塘里遇到了鲨鱼，最终伤害降低了{$sk764_dmgreduce}%！</span><br>";
				$r = array(1 + $sk764_dmggain / 100);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>