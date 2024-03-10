<?php

namespace skill603
{

	function init() 
	{
		define('MOD_SKILL603_INFO','hidden;debuff;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[603] = '静止';
		$bufficons_list[603] = Array(
			'msec' => 1,
			'hint' => '你的时间被停止了，无法进行任何行动！',
		);
	}
	
	function acquire603(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost603(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_skill603_state(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \bufficons\bufficons_check_buff_state(603, $pa);
	}
	
	function set_stun_period603($t, &$pa)	//单位毫秒
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\bufficons\bufficons_impose_buff(603, $t/1000, 0, $pa, 1, 1);
		$pa['new_stun_flag']=1;
	}

	function check_cooltime_on()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill603_state()) return 0;	//不显示冷却时间提示
		return $chprocess();
	}
	
	function calculate_active_obbs_change(&$ldata,&$edata,$active_r)	//不会先手敌人
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill603_state($ldata)) $change_to = 0;
		if (1 == check_skill603_state($edata)) $change_to = 100;
		if(isset($change_to)){
			$ldata['active_words'] .= '→'.$change_to;
			return $change_to;
		}
		return $chprocess($ldata,$edata,$active_r);
	}
	
	//若要接管此函数，请阅读base\battle\battle.php里的注释，并加以判断
	function check_can_counter(&$pa, &$pd, $active)			//不会反击敌人
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//注意判定的是$pa能否反击$pd
		if (1 == check_skill603_state($pa)) return 0; 
		return $chprocess($pa, $pd, $active);
	}
	
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill603_state())
		{
			eval(import_module('sys','logger','bufficons'));
			$rmt = number_format($tmp_totsec - $tmp_nowsec, 1);
			$rmt_msec = $rmt * 1000;
			$log .= '<span class="yellow b">时间被静止了，无法动弹！<br>持续时间还剩<span id="timer">'.$rmt.'</span>秒</span><br><img style="display:none;" type="hidden" src="img/blank.png" onload="demiSecTimerStarter('.$rmt_msec.');">';
			$mode = 'command'; $command = 'menu';
		}
		$chprocess();
	}
	
}

?>