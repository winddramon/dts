<?php

namespace skill385
{
	//各级要完成的成就名，如果不存在则取低的
	$ach385_name = array(
		1=>'团结之力'
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach385_desc= array(
		1=>'在<span class="gold b">肉鸽模式</span>获胜<:threshold:>次且至少有1个队友',
	);
	
	$ach385_proc_words = '已完成';
	
	$ach385_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach385_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach385_qiegao_prize = array(
		1 => 888
	);
	
	function init() 
	{
		define('MOD_SKILL385_INFO','achievement;');
		define('MOD_SKILL385_ACHIEVEMENT_ID','85');
	}
	
	function acquire385(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost385(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 385){
			eval(import_module('sys'));
			if(\sys\is_winner($pa['name'],$winner) && (false !== strpos($winner, ',')) && in_array($winmode, array(2,3,5,7))) {
				$ret += 1;
			}
		}
		return $ret;
	}
	
}

?>
