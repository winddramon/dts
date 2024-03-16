<?php

namespace skill748
{
	function init() 
	{
		define('MOD_SKILL748_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[748] = '解烦';	
	}
	
	function acquire748(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost748(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked748(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(748, $pa) && check_unlocked748($pa) && $pd['type']==0 && \skillbase\skill_query(960, $pd) && !empty(\skill960\get_taskarr($pd)))
		{
			eval(import_module('logger'));
			if ($active) $log .= "<span class=\"yellow b\">你好心地试图帮助对方解决困扰的问题！</span><br>";
			else $log .= "<span class=\"yellow b\">{$pa['name']}好心地试图帮助你解决困扰的问题！</span><br>";
			$r = array(1.5);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>
