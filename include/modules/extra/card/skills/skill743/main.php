<?php

namespace skill743
{
	function init()
	{
		define('MOD_SKILL743_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[743] = '风霜';
	}
	
	function acquire743(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(743,'trapnames','',$pa);
	}
	
	function lost743(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(743,'trapnames',$pa);
	}
	
	function check_unlocked743(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//把陷阱名加入列表
	function add_trapnames743($trapname, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$trapnames743 = get_trapnames743($pa);
		if (!in_array($trapname, $trapnames743)) $trapnames743[] = $trapname;
		\skillbase\skill_setvalue(743, 'trapnames', encode743($trapnames743), $pa);
	}
	
	//判定陷阱名是否在列表中
	function check_in_trapnames743($trapname, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$trapnames743 = get_trapnames743($pa);
		if(in_array($trapname, $trapnames743)) return true;
		return false;
	}
	
	//获得记录的陷阱名列表
	function get_trapnames743(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return decode743(\skillbase\skill_getvalue(743, 'trapnames', $pa));
	}
	
	function encode743($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return implode('_', $arr);
	}
	
	function decode743($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($str)) return Array();
		return explode('_', $str);
	}
	
	function post_traphit_events(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(743, $pd)) add_trapnames743($tritm['itm'], $pd);
		$chprocess($pa, $pd, $tritm, $damage);
	}
	
	function get_trap_final_damage_modifier_down(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(743, $pd) && check_in_trapnames743($tritm['itm'], $pd))
		{
			eval(import_module('logger'));
			$log .= "<span class=\"yellow b\">靠着上一次踩坑的经验，你免疫了这次伤害！</span><br>";
			return 0;
		}
		return $chprocess($pa,$pd,$tritm,$damage);
	}
	
}

?>