<?php

namespace skill426
{
	$upgradecost = Array(5,5,5,5,-1);
	$skill426_cd = array(180,150,120,90,60);
	
	function init() 
	{
		define('MOD_SKILL426_INFO','card;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[426] = '整备';
		$bufficons_list[426] = Array(
			'dummy' => 1,
		);
	}
	
	function acquire426(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(426,'lvl','0',$pa);
		\skillbase\skill_setvalue(426,'end_ts',1,$pa);	
		\skillbase\skill_setvalue(426,'cd_ts',0,$pa);
	}
	
	function lost426(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked426(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade426()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill426','player','logger'));
		if (!\skillbase\skill_query(426))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(426,'lvl');
		$ucost = $upgradecost[$clv];
		if ($clv == -1)
		{
			$log.='你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint<$ucost) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$skillpoint-=$ucost; \skillbase\skill_setvalue(426,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function activate426()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill426','player','logger','sys'));
		\player\update_sdata();
		//整备根据情况有不同的CD，特殊处理，正常请参见skill500等模块，用\bufficons\bufficons_activate_buff()统一处理
		list($can_activate, $fail_hint) = \bufficons\bufficons_check_buff_state_shell(426);
		if(!$can_activate) {
			$log .= $fail_hint;
			return;
		}
		if ( $hp>=$mhp && $sp>=$msp && empty($inf)){
			$log.='你十分健康，不需要使用这个技能。<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(426,'lvl');
		$flag = \bufficons\bufficons_set_timestamp(426, 0, (int)($skill426_cd[$clv]));
		if(!$flag) {
			$log.='发动失败！<br>';
			return;
		}
		$hp=max($hp,$mhp);$sp=max($sp,$msp);$inf = '';
		$log.='<span class="lime b">技能「整备」发动成功。</span><br>';
		$log.='<span class="lime b">你的身体已经焕然一新了！</span><br>';
	}
	
	function check_skill426_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \bufficons\bufficons_check_buff_state(426, $pa);
	}
}

?>