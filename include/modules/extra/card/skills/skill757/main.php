<?php

namespace skill757
{
	function init()
	{
		define('MOD_SKILL757_INFO','card;feature;');
		eval(import_module('clubbase'));
		$clubskillname[757] = '碎梦';
	}
	
	function acquire757(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(757,'lvl','0',$pa);
	}
	
	function lost757(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(757,'lvl',$pa);
	}
	
	function check_unlocked757(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(757,$pd) && check_unlocked757($pd))
		{
			$pd['skill757_hp'] = $pd['hp'];
		}
		$chprocess($pa, $pd, $active);
	}
	
	function attack_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(757,$pd) && check_unlocked757($pd) && isset($pd['skill757_hp']) && $pd['hp'] >= $pd['skill757_hp'])
		{
			eval(import_module('logger'));
			if ($active) $log .= "<span class=\"red b\">{$pd['name']}汲取了梦境的力量！</span><br>";
			else $log .= "<span class=\"red b\">你汲取了梦境的力量！</span><br>";
			$clv = (int)\skillbase\skill_getvalue(757,'lvl',$pd);
			\skillbase\skill_setvalue(757,'lvl',$clv+1,$pd);
			addnews(0, 'skill757', $pd['name']);
		}
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(757,$pa) && check_unlocked757($pa) && \skillbase\skill_getvalue(757,'lvl',$pa))
		{
			eval(import_module('logger'));
			if ($active) $log .= "<span class=\"lime b\">你操纵着梦境的力量投射向{$pd['name']}！</span><br>";
			else $log .= "<span class=\"lime b\">{$pa['name']}操纵着梦境的力量投射向你！</span><br>";
		}
		$chprocess($pa, $pd, $active);
	}
	
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(757,$pa) && check_unlocked757($pa) && \skillbase\skill_getvalue(757,'lvl',$pa)) 
		{
			$dmgup = (int)\skillbase\skill_getvalue(757,'lvl',$pa) * 200;
			$ret += $dmgup;
			$pa['mult_words_fdmgbs'] = \attack\add_format($dmgup, $pa['mult_words_fdmgbs']);
		}
		return $ret;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'skill757') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}汲取了梦境的力量！</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
