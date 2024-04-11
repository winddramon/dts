<?php

namespace skill383
{
	//各级要完成的成就名，如果不存在则取低的
	$ach383_name = array(
		1=>'肉鸽来客 LV1',
		2=>'肉鸽来客 LV2',
		3=>'肉鸽来客 LV3',
		4=>'肉鸽来客 LV4'
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach383_desc= array(
		1=>'在<span class="gold b">肉鸽模式</span>完成<:threshold:>个不同结局',
	);
	
	$ach383_proc_words = '已完成';
	
	$ach383_unit = '个';
	
	$ach383_proc_words2 = '（悬浮查看完成情况）';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach383_threshold = array(
		1 => 1,
		2 => 2,
		3 => 3,
		4 => 4,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach383_qiegao_prize = array(
		1 => 777,
		2 => 1777,
		3 => 2777,
		4 => 3777
	);
	
	//各级给的卡片奖励
	$ach383_card_prize = array(
		4 => 420
	);
	
	//卡片奖励的碎闪等级
	$ach383_card_prize_blink = array(
		4 => 20
	);
	
	//各级给的道具奖励
	$ach383_logitem_prize = array(
		1 => 301,
		3 => 302
	);
	
	//各级给的道具奖励数量
	$ach383_logitem_prize_num = array(
		1 => 1,
		3 => 1
	);
	
	function init() 
	{
		define('MOD_SKILL383_INFO','achievement;');
		define('MOD_SKILL383_ACHIEVEMENT_ID','83');
	}
	
	function acquire383(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost383(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 383){
			if(!is_array($ret)) $ret = array();
			eval(import_module('sys'));
			if(\sys\is_winner($pa['name'],$winner) && in_array($winmode, array(2,3,5,7)) && !in_array($winmode, $ret)) {
				$ret[] = $winmode;
				$ret = array_unique($ret);
			}
		}
		return $ret;
	}
	
	//判定数据与阈值的关系，这里是计算$data的元素个数，然后跟阈值相比较
	function ach_finalize_check_progress(&$pa, $t, $data, $achid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (383 == $achid) {
			if (empty($data)) return false;
			return sizeof((Array)$data) >= $t;
		}
		return $chprocess($pa, $t, $data, $achid);
	}
	
	//显示已完成结局
	function show_ach_title_3($achid, $adata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($achid, $adata);
		if (383 == $achid) {
			if (empty($adata)) return '无';
			$ret = '';
			$gwin = get_var_in_module('gwin', 'sys');
			foreach($adata as $val){
				$ret .= $gwin[$val].'&nbsp; ';
			}
			if (empty($ret)) return '无';
		}
		return $ret;
	}
	
	//成就进度值处理
	function parse_achievement_progress_var($achid, $x){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$x = $chprocess($achid, $x);
		if (383 == $achid) {
			if (empty($x)) return 0;
			$x = sizeof((Array)$x);
		}
		return $x;
	}
	
}

?>
