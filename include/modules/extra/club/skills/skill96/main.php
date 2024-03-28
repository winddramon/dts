<?php

namespace skill96
{
	function init()
	{
		define('MOD_SKILL96_INFO','club;buffer;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[96] = '魂音';
		$bufficons_list[96] = Array(
			'disappear' => 1,
			'clickable' => 0,
			'hint' => '状态「魂音」：歌曲使你获得了强化！',
		);
	}
	
	function acquire96(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost96(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked96(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//最终伤害变化
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (1 == \bufficons\bufficons_check_buff_state(96, $pa))
		{
			$skill96_type = \skillbase\skill_getvalue(96, 'type', $pa);
			if (!empty($skill96_type) && ($skill96_type[0] == '1'))
			{
				eval(import_module('logger'));
				$skill96_effect = (int)\skillbase\skill_getvalue(96, 'effect', $pa);
				$dmggain = 20 + round(0.1 * $skill96_effect);
				if ($active) $log .= "<span class=\"yellow b\">「魂音」使你造成的伤害增加了{$dmggain}%！</span><br>";
				else $log .= "<span class=\"yellow b\">「魂音」使{$pa['name']}造成的伤害增加了{$dmggain}%！</span><br>";
				$r[] = 1 + $dmggain / 100;
			}
		}
		if (1 == \bufficons\bufficons_check_buff_state(96, $pd)) 
		{
			$skill96_type = \skillbase\skill_getvalue(96, 'type', $pd);
			if (!empty($skill96_type) && ($skill96_type[1] == '3'))
			{
				eval(import_module('logger'));
				$skill96_effect = (int)\skillbase\skill_getvalue(96, 'effect', $pd);
				$dmgdown = 30 + round(0.1 * $skill96_effect);
				if ($active) $log .= "<span class=\"yellow b\">「魂音」使{$pd['name']}受到的伤害降低了{$dmgdown}%！</span><br>";
				else $log .=" <span class=\"yellow b\">「魂音」使你受到的伤害降低了{$dmgdown}%！</span><br>";
				$r[] = 1 - $dmgdown / 100;
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	//先制率增加
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (1 == \bufficons\bufficons_check_buff_state(96, $ldata))
		{
			$skill96_type = \skillbase\skill_getvalue(96, 'type', $ldata);
			if (!empty($skill96_type) && ($skill96_type[0] == '2'))
			{
				$skill96_effect = (int)\skillbase\skill_getvalue(96, 'effect', $ldata);
				$r *= 1 + round(0.06 * $skill96_effect) / 100;
			}
		}
		if (1 == \bufficons\bufficons_check_buff_state(96, $edata))
		{
			$skill96_type = \skillbase\skill_getvalue(96, 'type', $edata);
			if (!empty($skill96_type) && ($skill96_type[0] == '2'))
			{
				$skill96_effect = (int)\skillbase\skill_getvalue(96, 'effect', $edata);
				$r /= 1 + round(0.06 * $skill96_effect) / 100;
			}
		}
		if($r != 1) $ldata['active_words'] = \attack\multiply_format($r, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)*$r;
	}
	
	//获得升血和激奏3
	function get_ex_def_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (1 == \bufficons\bufficons_check_buff_state(96, $pd))
		{
			$skill96_type = \skillbase\skill_getvalue(96, 'type', $pd);
			if (!empty($skill96_type))
			{
				if ($skill96_type[0] == '3')
				{
					$skill96_effect = (int)\skillbase\skill_getvalue(96, 'effect', $pd);
					$hu_e = 7 * $skill96_effect - 500;
					array_push($ret,'^hu'.$hu_e);
				}
				if ($skill96_type[1] == '1') array_push($ret,'^sa3');
			}
		}
		return $ret;
	}
	
	//获得音爆
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (1 == \bufficons\bufficons_check_buff_state(96, $pa))
		{
			$skill96_type = \skillbase\skill_getvalue(96, 'type', $pa);
			if (!empty($skill96_type) && ($skill96_type[1] == '2')) array_push($ret,'t');
		}
		return $ret;
	}
	
}

?>