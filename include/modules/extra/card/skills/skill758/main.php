<?php

namespace skill758
{
	function init()
	{
		define('MOD_SKILL758_INFO','card;feature;');
		eval(import_module('clubbase'));
		$clubskillname[758] = '梦隙';
	}
	
	function acquire758(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost758(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked758(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_dmg_def_attr(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(758,$pa) && check_unlocked758($pa) && \attrbase\check_in_itmsk('h',\attrbase\get_ex_def_array($pa, $pd, $active)))
		{
			eval(import_module('logger'));
			$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="red b"><:pa_name:>的攻击无视了<:pd_name:>的控伤属性！</span><br>');
			return;
		}
		$chprocess($pa, $pd, $active);
	}
	
}

?>
