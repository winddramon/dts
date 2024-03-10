<?php

namespace skill555
{
	$skill555_cd = 180;
	$skill555_act_time = 20;
	
	function init() 
	{
		define('MOD_SKILL555_INFO','card;upgrade;buffer;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[555] = '迷彩';
		$bufficons_list[555] = Array(
			'dummy' => 1,
		);
	}
	
	function acquire555(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill555'));
		\skillbase\skill_setvalue(555,'end_ts',1,$pa);	
		\skillbase\skill_setvalue(555,'cd_ts',0,$pa);
	}
	
	function lost555(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked555(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate555()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill555','player','logger','sys'));
		\player\update_sdata();
		list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(555, $skill555_act_time, $skill555_cd);
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}
		$log .= '<span class="lime b">你发动了「光学迷彩」，现在谁也注意不到你了！——虽然有效时间很短。</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill555_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \bufficons\bufficons_check_buff_state(555,$pa);
	}
	
	//迷彩状态不会主动遇到敌人和尸体
	function discover_player()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));		
		if (1 == check_skill555_state($sdata)) {
			$log .= "<span class=\"yellow b\">你借助光学迷彩避开了全部敌人，专心摸鱼。</span><br>";
			return false;
		}
		return $chprocess();
	}
}

?>