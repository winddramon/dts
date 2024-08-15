<?php

namespace skill806
{
	$skill806_attr = array
	(
		0 => array('z'),//纯净水
		//防御属性
		1 => array('A','a'),//铁壁药水
		2 => array('B','b'),//神佑药水
		3 => array('h'),//护心药水
		4 => array('^hu1000'),//活力药水
		5 => array('^sa10'),//爽喉喷雾
		//攻击属性
		31 => array('n','Y'),//穿透药水
		32 => array('N'),//巨力药水
		33 => array('Y'),//纯化药水
		34 => array('L'),//心眼药水
		35 => array('v'),//神力药水
		36 => array('V'),//神性药水
		37 => array('g','l')//媚药
	);
	
	function init() 
	{
		define('MOD_SKILL806_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[806] = '药水';
	}
	
	function acquire806(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(806,'lvl','0',$pa);
	}
	
	function lost806(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(806,'lvl',$pa);
	}
	
	function check_unlocked806(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(806,$pa))
		{
			eval(import_module('skill806'));
			$clv = (int)\skillbase\skill_getvalue(806,'lvl',$pa);
			if (isset($skill806_attr[$clv]) && $clv > 30)
			{
				foreach($skill806_attr[$clv] as $v)
				{
					array_push($ret, $v);
				}
			}
		}
		return $ret;
	}
	
	function get_ex_def_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(806,$pd))
		{
			eval(import_module('skill806'));
			$clv = (int)\skillbase\skill_getvalue(806,'lvl',$pd);
			if (isset($skill806_attr[$clv]) && $clv <= 30)
			{
				foreach($skill806_attr[$clv] as $v)
				{
					array_push($ret, $v);
				}
			}
		}
		return $ret;
	}
	
	function skill806_attrtext(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$clv = (int)\skillbase\skill_getvalue(806,'lvl',$pa);
		eval(import_module('skill806'));
		$attrs = isset($skill806_attr[$clv]) ? $skill806_attr[$clv] : $skill806_attr[0];
		$s = '';
		foreach ($attrs as $k => $v)
		{
			if ($k) $s .= "、";
			$s .= \itemmain\get_itmsk_words_single($v);
		}
		return $s;
	}
	
}

?>