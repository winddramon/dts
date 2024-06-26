<?php

namespace skill604
{
	
	function init() 
	{
		define('MOD_SKILL604_INFO','hidden;debuff;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[604] = '灾厄';
		$bufficons_list[604] = Array(
			'hint' => '<span class="purple b">你已经梦魇缠身了！</span>',
		);
	}
	
	function acquire604(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost604(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_skill604_state(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$pa) {
			eval(import_module('player'));
			$pa = & $sdata;
		}
		return \bufficons\bufficons_check_buff_state(604, $pa);
	}
	
	//命中率降低30%
	function get_hitrate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (1 == check_skill604_state($pa))
		{
			$r = 0.7;
		}
		return $chprocess($pa, $pd, $active)*$r;
	}
	
	//先制率降低30%
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (1 == check_skill604_state($ldata)) 
		{
			$r = 0.7;
		}elseif(1 == check_skill604_state($edata))
		{
			$r = 1/0.7;
		}
		if($r != 1) $ldata['active_words'] = \attack\multiply_format($r, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)*$r;
	}
	
	//物防成功率降低30%
	function get_ex_phy_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(1 == check_skill604_state($pd)) {
			$ret *= 0.7;
		}
		return $ret;
	}
	
	//属防
	function get_ex_dmg_def_proc_rate(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $key);
		if(1 == check_skill604_state($pd)) {
			$ret *= 0.7;
		}
		return $ret;
	}
	
	//物抹
	function get_ex_phy_nullify_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(1 == check_skill604_state($pd)) {
			$ret *= 0.7;
		}
		return $ret;
	}
	
	//属抹
	function get_ex_dmg_nullify_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(1 == check_skill604_state($pd)) {
			$ret *= 0.7;
		}
		return $ret;
	}
	
	//控伤效果发生率
	function get_dmg_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if(1 == check_skill604_state($pd)) {
			$ret *= 0.7;
		}
		return $ret;
	}
}

?>