<?php

namespace skill762
{
	function init() 
	{
		define('MOD_SKILL762_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[762] = '礼节';
	}
	
	function acquire762(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(762,'pids','',$pa);
	}
	
	function lost762(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(762,'pids',$pa);
	}
	
	function check_unlocked762(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function add_pids762($epid, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pids762 = get_pids762($pa);
		if (!in_array($epid, $pids762))
		{
			$pids762[] = $epid;
			\skillbase\skill_setvalue(762, 'pids', encode762($pids762), $pa);
		}
	}
	
	function check_in_pids762($epid, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pids762 = get_pids762($pa);
		if(in_array($epid, $pids762)) return true;
		return false;
	}
	
	function get_pids762(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return decode762(\skillbase\skill_getvalue(762, 'pids', $pa));
	}
	
	function encode762($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return implode('_', $arr);
	}
	
	function decode762($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($str)) return Array();
		return explode('_', $str);
	}
	
	function attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(762, $pd) && check_unlocked762($pd)) add_pids762($pa['pid'], $pd);
		if (\skillbase\skill_query(762, $pa) && check_unlocked762($pa))
		{
			add_pids762($pd['pid'], $pa);
			eval(import_module('logger'));
			$log .= '<span class="yellow b">你对着敌人发起了偷袭！</span><br>';
			if (!in_array($pd['type'], array(2, 90, 52, 53, 54, 55, 56, 57))) $pa['domoflag'] = 1;//无名小卒不需要问候
		}
		$chprocess($pa, $pd, $active);
	}
	
	function attack_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (!empty($pa['domoflag']))
		{
			$pa['domoflag'] = 0;
			if ($pd['hp'] > 0)//尸体不需要问候
			{
				$msg = "DOMO，{$pd['name']}=SAN，{$pa['name']} DESU.";
				\sys\addchat(0, $msg, $pa['name']);
			}
		}
	}
	
	function check_enemy_meet_active(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(762,$ldata) && check_unlocked762($ldata) && !check_in_pids762($edata['pid'], $ldata)) $ret = 1;
		else $ret = $chprocess($ldata,$edata);
		return $ret;
	}
	
}

?>