<?php

namespace skill387
{
	//各级要完成的成就名，如果不存在则取低的
	$ach387_name = array(
		1=>'早睡早起',
		2=>'午夜凶铃',
		3=>'凌晨4点的洛杉矶',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach387_desc= array(
		1=>'在<span class="seagreen b">梦境演练</span>中获胜时结算分数达到<:threshold:>分',
	);
	
	$ach387_proc_words = '最高纪录';
	
	$ach387_unit = '分';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach387_threshold = array(
		1 => 11,
		2 => 24,
		3 => 40,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach387_qiegao_prize = array(
		1 => 777,
		2 => 1337,
		3 => 2333
	);
	
	//各级给的卡片奖励
	$ach387_card_prize = array(
		3 => 422
	);
	
	function init() 
	{
		define('MOD_SKILL387_INFO','achievement;');
		define('MOD_SKILL387_ACHIEVEMENT_ID','87');
	}
	
	function acquire387(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost387(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 387){
			eval(import_module('sys'));
			if(\sys\is_winner($pa['name'],$winner))
			{
				$inst12_score = \instance12\inst12_get_score($pa);
				if ($inst12_score > $ret)
				{
					$ret = $alvl;
				}
			}
		}
		return $ret;
	}
}

?>