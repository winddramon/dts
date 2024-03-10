<?php

namespace skill440
{
	$skill440_cd = 200;
	$skill440_cause600_period = 40;
	
	function init() 
	{
		define('MOD_SKILL440_INFO','card;battle;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[440] = '父爱';
		$bufficons_list[440] = Array(
			'disappear' => 0,
			'clickable' => 0,
			'hint' => '战斗技「父爱」',
			'activate_hint' => '战斗技「父爱」已就绪<br>在战斗界面可以发动',
		);
	}
	
	function acquire440(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill440'));
		\skillbase\skill_setvalue(440,'end_ts',$now-1,$pa);	
		\skillbase\skill_setvalue(440,'cd_ts',$now+$skill440_cd,$pa);	
	}
	
	function lost440(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked440(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	
	function check_battle_skill_unactivatable(&$ldata,&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ldata,$edata,$skillno);
		if(440 == $skillno && 0 == $ret){//额外判定对方是不是玩家
			if($edata['type'] >0) $ret = 8;
		}
		return $ret;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=440) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(440,$pa) || !check_unlocked440($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			eval(import_module('logger','skill440'));
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,440) ){
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「父爱」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「父爱」！</span><br>";
				$pd['skill440_flag']=1;
				\bufficons\bufficons_set_timestamp(440, 0, $skill440_cd, $pa);
				addnews ( 0, 'bskill440', $pa['name'], $pd['name'] );
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
	
	function skill_enabled_core($skillid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		$skillid=(int)$skillid;
		if ($pa!=NULL && !empty($pa['skill440_flag']))
		{
			//所有技能失效
			if (!\skillbase\check_skill_info($skillid,'achievement') && !\skillbase\check_skill_info($skillid,'hidden'))
				return 0;
		}
		return $chprocess($skillid,$pa);
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=440) {
			$chprocess($pa, $pd, $active);
			return;
		}
		eval(import_module('logger','skill440'));
		list($is_successful, $fail_hint) = \bufficons\bufficons_impose_buff(600, $skill440_cause600_period, 0, $pd, 2);
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}
		if (strpos($pd['inf'],'u')===false)
		{
			$pd['inf'].='u';
		}
		if (strpos($pd['inf'],'p')===false)
		{
			$pd['inf'].='p';
		}
		if ($active)
			$log.='<span class="red b">敌人已经大难临头了！</span><br>';
		else  $log.="<span class=\"red b\">你已经大难临头了！</span><br>";
		$chprocess($pa,$pd,$active);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill440') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「父爱」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>