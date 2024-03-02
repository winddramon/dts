<?php

namespace skill745
{
	function init()
	{
		define('MOD_SKILL745_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[745] = '凑合';
	}
	
	function acquire745(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost745(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked745(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calc_skillbook_efct($itme, $skcnt, $ws_sum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($itme, $skcnt, $ws_sum);
		if ($ret < 0 && \skillbase\skill_query(745)) sk745_process();
		return $ret;
	}
	
	function calc_skillmed_efct($itme, $skcnt, $ws_sum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($itme, $skcnt, $ws_sum);
		if ($ret < 0 && \skillbase\skill_query(745)) sk745_process();
		return $ret;
	}
	
	function sk745_process()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player', 'logger'));
		$log .= "<span class=\"yellow b\">你似乎意识到不得不想办法了……</span><br>";
		\skillbase\skill_acquire(710, $sdata);
		\skillbase\skill_lost(745, $sdata);
	}
	
}

?>