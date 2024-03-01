<?php

namespace skill744
{
	$sk744_actrate = array(50,70,90);
	$sk744_tmpflag = 0;
	$sk744_tmprate = 0;
	
	function init()
	{
		define('MOD_SKILL744_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[744] = '连环';
	}
	
	function acquire744(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(744,'lvl','0',$pa);
		\skillbase\skill_setvalue(744,'exflag','0',$pa);
	}
	
	function lost744(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(744,'lvl',$pa);
		\skillbase\skill_delvalue(744,'exflag',$pa);
	}
	
	function check_unlocked744(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function post_traphit_events(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $tritm, $damage);
		eval(import_module('skill744'));
		if (\skillbase\skill_query(744, $pa))
		{
			$clv = (int)\skillbase\skill_getvalue(744,'lvl',$pa);
			$exflag744 = (int)\skillbase\skill_getvalue(744,'exflag',$pa);
			if ($exflag744) $sk744_tmpflag = 2;
			else $sk744_tmpflag = 1;
			$sk744_tmprate = $sk744_actrate[$clv];
		}
		else $sk744_tmpflag = 0;
	}
	
	function calculate_real_trap_obbs_change($var)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill744'));
		if ($sk744_tmpflag) return 999983;
		return $var;
	}
	
	function trapcheck()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		if (!$ret) return 0;
		eval(import_module('skill744'));
		if ($sk744_tmpflag)
		{
			eval(import_module('logger','player'));
			$log .= "<span class=\"red b\">你好像走进了雷区……</span><br>";
		}
		if ($sk744_tmpflag == 2)
		{
			while ($ret && (rand(0,99) < $sk744_tmprate))
			{
				$hp_temp = $hp;
				$ret = $chprocess();
				if ($hp_temp <= $hp) break;
			}
		}
		else
		{
			while ($ret && $sk744_tmpflag && (rand(0,99) < $sk744_tmprate))
			{
				$hp_temp = $hp;
				$ret = $chprocess();
				if ($hp_temp <= $hp) break;
			}
		}
		$sk744_tmpflag = 0;
		$sk744_tmprate = 0;
		return 1;
	}
	
}

?>