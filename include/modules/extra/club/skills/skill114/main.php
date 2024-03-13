<?php

namespace skill114
{
	function init()
	{
		define('MOD_SKILL114_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[114] = '魂火';
		$clubdesc_h[28] = $clubdesc_a[28] = '开局解锁一个随机魂火，战斗获得怒气+3<br>称号技能需要通过击杀敌人解锁，可以在战斗中连锁触发';
	}
	
	function acquire114(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(rand(116,121),'unlocked','1',$pa);
	}
	
	function lost114(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked114(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_attack_rage_gain_base(&$pa, &$pd, $active, $fixed_val=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $fixed_val);
		if (\skillbase\skill_query(114, $pd) && check_unlocked114($pd)) $ret += 3;
		return $ret;
	}
	
}

?>