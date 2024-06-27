<?php

namespace skill981
{
	function init() 
	{
		define('MOD_SKILL981_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[981] = '演练';
	}
	
	function acquire981(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(981,'stage','0',$pa);
		\skillbase\skill_setvalue(981,'rm','0',$pa);
	}
	
	function lost981(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(981,'stage',$pa);
		\skillbase\skill_delvalue(981,'rm',$pa);
	}
	
	function check_unlocked981(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if (\skillbase\skill_query(981,$pa) && check_unlocked981($pa) && $pd['hp'] <= 0)
		{
			$rm = (int)\skillbase\skill_getvalue(981,'rm',$pa);
			if ($rm == 1)
			{
				eval(import_module('logger'));
				$stage = (int)\skillbase\skill_getvalue(981,'stage');
				$theitem = array('itm'=>$stage.'级奖励盒','itmk'=>'Y','itme'=>1,'itms'=>3,'itmsk'=>$stage);
				\skill952\skill952_sendin_core($theitem, $pa);
				$log .= "<span class=\"lime b\">奖励道具被送到了你的奖励箱中。</span><br>";
			}
			$rm = max($rm - 1, 0);
			\skillbase\skill_setvalue(981,'rm',$rm,$pa);
		}
		$chprocess($pa,$pd,$active);
	}
	
	function cast_skill981()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(981)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$rm = (int)\skillbase\skill_getvalue(981,'rm');
		if ($rm > 0)
		{
			$log .= '上一波次尚未结束。<br>';
			return;
		}
		$stage = (int)\skillbase\skill_getvalue(981,'stage');
		if ($stage >= 11)
		{
			$log .= '你已经战胜了所有的敌人。<br>';
			return;
		}
		$stage += 1;
		$log .= "<span class=\"yellow b\">新的敌人加入了战场！</span><br>";
		addnews($now, 'instance12_nextwave', $stage);
		eval(import_module('skill981'));
		$rm = 0;
		foreach($skill981_enemies[$stage] as $k => $v){
			\randnpc\add_randnpc($k, $v, 0, 0, 0, 0, array(201));
			$rm += $v;
		}
		\skillbase\skill_setvalue(981,'stage',$stage);
		\skillbase\skill_setvalue(981,'rm',$rm);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($mode == 'special' && $command == 'skill981_special')
		{
			cast_skill981();
			return;
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'instance12_nextwave') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">第{$a}波次敌人加入了战场！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>