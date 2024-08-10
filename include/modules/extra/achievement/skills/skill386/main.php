<?php

namespace skill386
{
	//各级要完成的成就名，如果不存在则取低的
	$ach386_name = array(
		1=>'梦境来客',
		2=>'梦的背面',
		3=>'深暗幻想'
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach386_desc= array(
		1=>'在<span class="seagreen b">梦境演练</span>中完成结局：锁定解除或幻境解离，共<:threshold:>次',
	);
	
	$ach386_proc_words = '目前进度';
	
	$ach386_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach386_threshold = array(
		1 => 1,
		2 => 5,
		3 => 10,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach386_qiegao_prize = array(
		1 => 777,
		2 => 1337,
		3 => 2333
	);

	//各级给的卡片奖励
	$ach386_card_prize = array(
		2 => 233//阿林百人众
	);

	//各级给的道具奖励
	$ach386_logitem_prize = array(
		3 => 304
	);
	
	//各级给的道具奖励数量
	$ach386_logitem_prize_num = array(
		3 => 1
	);
	
	function init() 
	{
		define('MOD_SKILL386_INFO','achievement;');
		define('MOD_SKILL386_ACHIEVEMENT_ID','86');
	}
	
	function acquire386(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost386(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 386){
			eval(import_module('sys'));
			if(\sys\is_winner($pa['name'],$winner) && in_array($winmode, array(3,7))) {
				$ret += 1;
			}
		}
		return $ret;
	}
	
}

?>
