<?php

namespace skill84
{		
	function init() 
	{
		define('MOD_SKILL84_INFO','club;hidden;buffer;');
		eval(import_module('bufficons'));
		$bufficons_list[84] = Array(
			'disappear' => 1,
			'clickable' => 0,
			'hint' => '探测器开启中！<br>你的先制攻击率略微上升',
		);
	}
	
	function acquire84(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		\skillbase\skill_setvalue(84,'start_ts',$now,$pa);	
		$skill84_time = get_skill84_time($pa);
		\skillbase\skill_setvalue(84,'end_ts',$now+$skill84_time,$pa);	
		\skillbase\skill_setvalue(84,'cd_ts',$now+$skill84_time,$pa);	
	}
	
	function lost84(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//锡安有加成
	function get_skill84_time(&$pa){//单位是秒
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(7 == $pa['club']) return 20;
		return 15;
	}
	
	function get_skill84_effect(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(7 == $pa['club']) return 1.05;
		return 1.03;
	}

	function check_skill84_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$st = \bufficons\bufficons_check_buff_state(84,$pa);
		if(1 == $st) return 1;
		return 0;
	}
	
	//先攻率+3%
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (check_skill84_state($ldata)) $r*=get_skill84_effect($ldata);
		if($r != 1) $ldata['active_words'] = \attack\multiply_format($r, $ldata['active_words'],0);
		if (check_skill84_state($edata)) $r/=get_skill84_effect($edata);
		if($r != 1) $ldata['active_words'] = \attack\multiply_format($r, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)*$r;
	}
	
	//避难所探测器不显示特殊指令页面（就是有返回按钮的那个页面）
	function check_include_radar_cmdpage($radarsk = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$radar_digit = \radar\check_radar_digit($radarsk);
		
		if($radar_digit & 32) return false;
		return $chprocess($radarsk);
	}
	
	//使用雷达后的事件。现在避难所生命探测器是在这里实现
	function post_radar_event($radarsk = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($radarsk);
		
		$radar_digit = \radar\check_radar_digit($radarsk);
		
		if($radar_digit & 32) {
			eval(import_module('sys','player', 'logger'));
			if(!\skillbase\skill_query(84)) {//没有获得BUFF的情况下才会获得BUFF
				\skillbase\skill_acquire(84);
				$remtime = \skillbase\skill_getvalue(84,'end_ts') - $now;
				$log .= '在<span class="lime b">'.$remtime.'秒</span>内你可以无消耗使用避难所生命探测器，且你的<span class="lime b">先制率+'.round(\skill84\get_skill84_effect($sdata)*100-100).'%</span>。<br><br>';
			}
			// else{
			// 	$log .= '你已经在使用避难所生命探测器防备敌袭了。<br>';
			// }
		}
		return $ret;
	}
	
	//避难所生命探测器开启中不需要消耗电力
	function item_radar_reduce(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		
		$radar_digit = \radar\check_radar_digit($theitem['itmsk']);
		
		if($radar_digit & 32 && check_skill84_state($sdata)){
			return false;
		}
		
		return $chprocess($theitem);
	}
}

?>