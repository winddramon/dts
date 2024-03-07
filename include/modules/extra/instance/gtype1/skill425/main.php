<?php

namespace skill425
{
	$skill425_cd = 90;
	$skill425_cost = 500;
	
	function init() 
	{
		define('MOD_SKILL425_INFO','card;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[425] = '重载';
		$bufficons_list[425] = Array(
			'dummy' => 1,
		);
	}
	
	function acquire425(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill425'));
		\skillbase\skill_setvalue(425,'end_ts',1,$pa);	
		\skillbase\skill_setvalue(425,'cd_ts',0,$pa);
		\skillbase\skill_setvalue(425,'bribe_times','0',$pa);
	}
	
	function lost425(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked425(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//注意这个技能的判定和其他时效技能不一样
	//return 0:没有这个技能 1:CD中，金钱少于500 2:CD中，金钱大于500（花钱） 3:CD完毕 4:花钱使用次数不足
	function check_skill425_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$st = \bufficons\bufficons_check_buff_state(425, $pa);
		if(!$st) return 0;

		eval(import_module('sys','player','skill425'));
		$b=\skillbase\skill_getvalue(425,'bribe_times',$pa);
		if (2 == $st){
			if ($pa['money'] < $skill425_cost) return 1;
			elseif($b >= get_skill425_max_times()) return 4;
			return 2;
		} 
		return 3;
	}
	
	function activate425()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill425','skill424','player','logger','sys','skillbase'));
		\player\update_sdata();

		$st=check_skill425_state($sdata);
		if (!$st)
		{
			$log.='你没有这个技能！<br>';
			return;
		}elseif (!(\skillbase\skill_query(424, $sdata) && \skill424\check_unlocked424($sdata))){
			$log.='你没有除错技能！<br>';
			return;
		}elseif ($st==1){
			$log.='你的金钱不足！<br>';
			return;
		}elseif($st==4){
			$log.='贿赂次数已经用完，请升级层数后再尝试！<br>';
			return;
		}
		if ($st==2){
			$money-=$skill425_cost;
			$log.='<span class="lime b">消耗了'.$skill425_cost.'元，</span>';
			$bt=\skillbase\skill_getvalue(425,'bribe_times',$sdata);
			\skillbase\skill_setvalue(425,'bribe_times',$bt+1,$sdata);
		}
		$log.='<span class="lime b">技能「重载」发动成功。</span><br>';
		$flag = \bufficons\bufficons_set_timestamp(425, 0, $skill425_cd);
		if(!$flag) {
			$log.='发动失败！<br>';
			return;
		}
		\skill424\wdebug_reset();
		$log .='下次除错需要物品'.\skill424\wdebug_showreq();
	}
	
	//每3级有1次花钱重载机会
	function get_skill425_max_times()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$clv=\skillbase\skill_getvalue(424,'lvl');
		return ceil($clv/3);
	}
}

?>