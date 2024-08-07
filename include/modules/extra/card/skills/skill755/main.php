<?php

namespace skill755
{
	function init() 
	{
		define('MOD_SKILL755_INFO','card;feature;');
		eval(import_module('clubbase'));
		$clubskillname[755] = '重生';
	}
	
	function acquire755(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(755,'lvl','2',$pa);
	}
	
	function lost755(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked755(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_remaintime755(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return (int)\skillbase\skill_getvalue(755,'lvl',$pa);
	}
	
	//复活判定注册
	function set_revive_sequence(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd);
		if(\skillbase\skill_query(755,$pd) && check_unlocked755($pd)){
			$pd['revive_sequence'][170] = 'skill755';
		}
		return;
	}	

	//复活判定
	function revive_check(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $rkey);
		if('skill755' == $rkey && in_array($pd['state'],Array(20,21,22,23,24,25,27,29,39,40,41))){
			if(get_remaintime755($pd) > 0)
			$ret = true;
		}
		return $ret;
	}
	
	//发复活状况
	function post_revive_events(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $rkey);
		if('skill755' == $rkey){
			//$pd['hp']=$pd['mhp'];
			$pd['skill755_flag']=1;
			$rmtime = get_remaintime755($pd);
			\skillbase\skill_setvalue(755,'lvl',$rmtime-1,$pd);
			$pd['rivival_news'] = array('revival755', $pd['name']);
		}
		return;
	}
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		eval(import_module('sys','logger'));
		
		if(!empty($pd['skill755_flag'])){
			if ($pd['o_state']==27)	//陷阱
			{
				$log.= "<span class=\"yellow b\">不死鸟的力量唤醒了你，你化为一团火焰重生了！</span><br>";
				if(!$pd['sourceless']){
					$w_log = "<span class=\"yellow b\">但是，{$pd['name']}化为一团火焰重生了！</span><br>";
					\logger\logsave ( $pa['pid'], $now, $w_log ,'b');
				}
			}
		}
		return $ret;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('sys','logger'));
		if (isset($pd['skill755_flag']) && $pd['skill755_flag'])
		{
			if ($active)
			{
				$log.='<span class="yellow b">但是，敌人化为一团火焰重生了！</span><br>';
				$pd['battlelog'].='<span class="yellow b">不死鸟的力量唤醒了你，你化为一团火焰重生了！</span>';
			}
			else
			{
				$log.='<span class="yellow b">不死鸟的力量唤醒了你，你化为一团火焰重生了！</span><br>';
				$pd['battlelog'].='<span class="yellow b">但是，敌人化为一团火焰重生了！</span>';
			}
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'revival755') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}在烈火中重生了！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>