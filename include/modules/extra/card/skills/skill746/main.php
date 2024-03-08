<?php

namespace skill746
{
	function init()
	{
		define('MOD_SKILL746_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[746] = '艺术';
	}
	
	function acquire746(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost746(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked746(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function cast_skill746()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(746,$sdata) || !check_unlocked746($sdata)) 
		{
			$log .= '你没有这个技能。';
			return;
		}
		$skill746_choice = get_var_input('skill746_choice');
		if (!empty($skill746_choice))
		{
			$z = (int)$skill746_choice;
			if ((1<=$z) && ($z<=6) && ${'itms'.$z} && \itemmain\check_in_itmsk('^st', ${'itmsk'.$z}))
			{
				skill746_item_process($z);
				$mode = 'command';
				return;
			}
			else
			{
				$log .= '参数不合法。<br>';
			}
		}
		include template(MOD_SKILL746_CASTSK746);
		$cmd=ob_get_contents();
		ob_end_clean();
	}
	
	function skill746_item_process($itmn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','player'));
		$storage_itmarr = \ex_storage\get_item_storage(${'itmsk'.$itmn});
		if (empty($storage_itmarr))
		{
			$log .= "<span class=\"yellow b\">{${'itm'.$itmn}}</span>中空空如也。";
			return;
		}
		$itme_tmp = 0;
		$itmsk_tmp = array();
		foreach ($storage_itmarr as $v)
		{
			if (($v['itmk'][0] != 'W') || (strpos($v['itmk'], 'D') === false))
			{
				$log .= "<span class=\"yellow b\">{${'itm'.$itmn}}</span>中有并非爆炸物的物品。";
				return;
			}
			$itme_tmp += $v['itme'];
			$itmsk_tmp = array_merge($itmsk_tmp, \itemmain\get_itmsk_array($v['itmsk']));
		}
		$log .= "你将<span class=\"yellow b\">{${'itm'.$itmn}}</span>改造成了";
		${'itm'.$itmn} = '★炸药包★';
		$log .= "<span class=\"yellow b\">{${'itm'.$itmn}}</span>！<br>";
		${'itmk'.$itmn} = 'WD';
		${'itme'.$itmn} = $itme_tmp;
		${'itms'.$itmn} = 1;
		foreach ($itmsk_tmp as $k => $v)
		{
			if ($v[0] == '^' && strpos($v, '^ac') !== 0 && strpos($v, '^wc') !== 0)
			{
				unset($itmsk_tmp[$k]);
			}
		}
		${'itmsk'.$itmn} = implode('', array_unique($itmsk_tmp));
		\skillbase\skill_lost(746, $sdata);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($mode == 'special' && $command == 'skill746_special' && get_var_input('subcmd')=='castsk746')
		{
			cast_skill746();
			return;
		}
		$chprocess();
	}
	
}

?>