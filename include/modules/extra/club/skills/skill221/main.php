<?php

namespace skill221
{
	function init() 
	{
		define('MOD_SKILL221_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[221] = '衰弱';
	}
	
	function acquire221(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost221(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked221(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=7;
	}
	
	function get_skill221_lasttime(&$pa,&$pd,&$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 10;
	}

	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['is_hit'] && \skillbase\skill_query(221,$pa))
		{
			eval(import_module('logger','skill221'));
			$var_221=get_skill221_lasttime($pa,$pd,$active);//持续时间
			list($is_successful, $fail_hint) = \bufficons\bufficons_impose_buff(600,$var_221, 0, $pd);//被黑衣连续命中不会叠加衰弱时间
			if(!$is_successful) {
				$log .= $fail_hint;
			}
		}
		$chprocess($pa,$pd,$active);
	}
}

?>