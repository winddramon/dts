<?php

namespace skill384
{
	//各级要完成的成就名，如果不存在则取低的
	$ach384_name = array(
		1=>'漫游幻境',
		2=>'幻境的呼唤',
		3=>'幻境融解'
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach384_desc= array(
		1=>'在<span class="gold b">肉鸽模式</span>中完成结局：最后幸存、锁定解除、幻境解离或核爆全灭 <:threshold:>次',
	);
	
	$ach384_proc_words = '目前进度';
	
	$ach384_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach384_threshold = array(
		1 => 5,
		2 => 10,
		3 => 20,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach384_qiegao_prize = array(
		1 => 600,
		2 => 1200,
		3 => 2000
	);
	
	function init() 
	{
		define('MOD_SKILL384_INFO','achievement;');
		define('MOD_SKILL384_ACHIEVEMENT_ID','84');
	}
	
	function acquire384(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost384(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 384){
			eval(import_module('sys'));
			if(\sys\is_winner($pa['name'],$winner) && in_array($winmode, array(2,3,5,7))) {
				$ret += 1;
			}
		}
		return $ret;
	}
	
}

?>
