<?php

namespace skill601
{

	function init() 
	{
		define('MOD_SKILL601_INFO','hidden;debuff;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[601] = 'EMP';
		$bufficons_list[601] = Array(
			'hint' => '状态「EMP」<br>行动冷却时间延长',
		);
	}
	
	function acquire601(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost601(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function get_move_coldtime(&$dest){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill601_state()) return $chprocess($dest)*1.8;
		return $chprocess($dest);
	}
	
	function get_search_coldtime(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill601_state()) return $chprocess()*1.8;
		return $chprocess();
	}
	
	function get_itemuse_coldtime(&$item){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill601_state()) return $chprocess($item)*3;
		return $chprocess($item);
	}
	
	function check_skill601_state(&$pa = NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$pa) {
			eval(import_module('player'));
			$pa = & $sdata;
		}
		return \bufficons\bufficons_check_buff_state(601, $pa);
	}
}

?>