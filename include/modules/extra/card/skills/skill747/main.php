<?php

namespace skill747
{
	function init()
	{
		define('MOD_SKILL747_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[747] = '音响';
		if (defined('MOD_NOISE'))
		{
			eval(import_module('noise'));
			$noiseinfo['skill747']='欢快的歌声';
		}
	}
	
	function acquire747(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost747(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked747(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('player','metman'));
		if (\skillbase\skill_query(747, $sdata) && check_unlocked747($sdata) && $hp > 0 && empty($itms0) && empty($tdata))
		{
			$dice = rand(0,99);
			if ($dice < 3)
			{
				eval(import_module('logger','song'));
				$log .= '<br><br>';
				$sid = array_rand($songlist);
				\skillbase\skill_setvalue(747,'temp_sid',$sid,$sdata);
				\song\ss_sing($songlist[$sid]['songname']);
				\skillbase\skill_delvalue(747,'temp_sid',$sdata);
			}
			elseif ($dice < 15)
			{
				eval(import_module('map'));
				$lyric = '♪ Chipi chipi chapa chapa Dubi dubi daba daba ♪';
				$now = get_var_in_module('now', 'sys');
				addnews($now,'song',$name,$plsinfo[$pls],'Chipi Chipi Chapa Chapa');
				\sys\addchat(0, $lyric, $name);
				if (defined('MOD_NOISE')) \noise\addnoise($pls,'skill747',$pid);
			}
		}
	}
	
	function get_available_songlist(&$pdata = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ls = $chprocess($pdata);
		if (empty($pdata))
		{
			eval(import_module('player'));
			$pdata = $sdata;
		}
		if (\skillbase\skill_query(747, $pdata) && check_unlocked747($pdata))
		{
			$temp_sid = (int)\skillbase\skill_getvalue(747, 'temp_sid', $pdata);
			if ($temp_sid) $ls[] = $temp_sid;
		}
		return $ls;
	}
	
	function ss_cost_proc($cost)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(747, $sdata) && check_unlocked747($sdata) && !empty(\skillbase\skill_getvalue(747, 'temp_sid', $sdata))) return 0;
		return $chprocess($cost);
	}
	
	function learn_song_process($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(747, $sdata) && check_unlocked747($sdata) && !empty(\skillbase\skill_getvalue(747, 'temp_sid', $sdata))) return;
		$chprocess($sn);
	}
	
}

?>