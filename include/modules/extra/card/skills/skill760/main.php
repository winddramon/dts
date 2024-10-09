<?php

namespace skill760
{
	function init()
	{
		define('MOD_SKILL760_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[760] = '激怒';
	}
	
	function acquire760(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(760,'lvl','0',$pa);
	}
	
	function lost760(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(760,'lvl',$pa);
	}
	
	function check_unlocked760(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(760, $pd) && $pa['bskill'] != 0)
		{
			$clv = (int)\skillbase\skill_getvalue(760,'lvl',$pd);
			\skillbase\skill_setvalue(760,'lvl',$clv+1,$pd);
			eval(import_module('sys','logger'));
			if ($active)
			{
				$log .= '<span class="red b">「激怒」使敌人的力量增强了！</span><br>';
				$sk_log = "<span class=\"yellow b\">「激怒」使你的力量增强了！</span><br>";
				\logger\logsave($pd['pid'], $now, $sk_log, 'b');
			}
			else $log .= '<span class="red b">「激怒」使你的力量增强了！</span><br>';
		}
	}
	
	function check_skill760_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(760, $pa) && check_unlocked760($pa))
		{
			$clv = (int)\skillbase\skill_getvalue(760,'lvl',$pa);
			if ($clv > 0)
			{
				eval(import_module('logger'));
				$attgain = 20 * $clv;
				$log .= "<span class=\"yellow b\">「激怒」使此次攻击的物理伤害增加了{$attgain}%！</span><br>";
				$dmggain = (100 + $attgain) / 100;
				return array($dmggain);
			}
		}
		return array();
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = check_skill760_proc($pa,$pd,$active);
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>
