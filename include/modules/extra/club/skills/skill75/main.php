<?php

namespace skill75
{
	$skill75_cd = 90;
	$wep_skillkind_req = 'wk';
	
	function init() 
	{
		define('MOD_SKILL75_INFO','club;battle;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[75] = '剑心';
		$bufficons_list[75] = Array(
			'dummy' => 1,
		);
	}
	
	function acquire75(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill75'));
		if ($pa['club']==2) $t = $now - $skill75_cd;
		else $t = $now;
		\skillbase\skill_setvalue(75,'end_ts',$t-1,$pa);	
		\skillbase\skill_setvalue(75,'cd_ts',$t+$skill75_cd,$pa);	
	}
	
	function lost75(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked75(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill75_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \bufficons\bufficons_check_buff_state(75, $pa);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=75) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(75,$pa) || !check_unlocked75($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			eval(import_module('logger','skill75'));
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,75) )
			{
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「剑心」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「剑心」！</span><br>";
				\bufficons\bufficons_set_timestamp(75, 0, $skill75_cd, $pa);
				addnews ( 0, 'bskill75', $pa['name'], $pd['name'] );
			}
			else
			{
				if ($active)
				{
					$log.='冷却时间未到或其他原因不能发动。<br>';
				}
				$pa['bskill']=0;
			}
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if ($pa['bskill']==75 && $pa['is_hit']) 
		{
			eval(import_module('logger'));
			$d=$pa['lvl']+30;
			$log.='<span class="yellow b">「剑心」附加了'.$d.'点伤害！</span><br>';
			$ret += $d;
			$pa['mult_words_fdmgbs'] = \attack\add_format($d, $pa['mult_words_fdmgbs']);
		}
		return $ret;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill75') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「剑心」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>