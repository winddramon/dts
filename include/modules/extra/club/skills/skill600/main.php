<?php

namespace skill600
{

	function init() 
	{
		define('MOD_SKILL600_INFO','hidden;debuff;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[600] = '衰弱';
		$bufficons_list[600] = Array(
			'disappear' => 1,
			'clickable' => 0,
			'hint' => '状态「衰弱」<br>无法解除异常状态或包扎伤口',
		);
	}
	
	function acquire600(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$now = get_var_in_module('now','sys');
		\skillbase\skill_setvalue(600,'end_ts',$now+60,$pa);//默认时间1分钟
		\skillbase\skill_setvalue(600,'cd_ts',0,$pa);
	}
	
	function lost600(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function chginf($infpos)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','sys','player','skill600'));
		if (1 == check_skill600_state()) 
		{
			$log .= '你现在不能处理伤口或异常状态！';
			$mode = 'command';
			return;
		}
		$chprocess($infpos);
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','skill600'));
		if ((strpos ( $theitem['itmk'], 'C' ) === 0)&&(1 == check_skill600_state())) 
		{
			$log .= '你喝了一小口药剂，感觉自己根本就喝不下去！';
			$mode = 'command';
			return;
		}
		$chprocess($theitem);
	}
	
	function upgrade12()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','skill600'));
		if (1 == check_skill600_state())
		{
			$log.='你现在不能处理伤口或异常状态！<br>';
			$mode = 'command';
			return;
		}
		$chprocess();
	}
	
	//检查状态，返回值同bufficons_check_buff_state()
	function check_skill600_state(&$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$pa) {
			eval(import_module('player'));
			$pa = & $sdata;
		}
		return \bufficons\bufficons_check_buff_state(600, $pa);
	}
	
}

?>