<?php

namespace skill805
{
	$skill805_except_skilllist = array(952,964,981,982,983,1001,1002); //不计入总数，也不能被遗忘的技能
	
	function init() 
	{
		define('MOD_SKILL805_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[805] = '传统';
	}
	
	function acquire805(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(805,'lvl','42',$pa);//表示能记住多少个技能
	}
	
	function lost805(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(805,'lvl',$pa);
	}
	
	function check_unlocked805(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//统计持有技能数
	function skill805_get_skill_count(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$acquired_skills = \skillbase\get_acquired_skill_array($pa);
		$count = 0;
		if (!empty($acquired_skills))
		{
			eval(import_module('clubbase','skill805'));
			foreach ($acquired_skills as $skillid)
			{
				if (in_array($skillid, $skill805_except_skilllist)) continue;
				if (isset($clubskillname[$skillid]) && (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'hidden;') === false)) $count += 1;
			}
		}
		return $count;
	}
	
	//可遗忘的技能列表和无法被遗忘的技能列表（feature类技能会计入技能数但不能遗忘）
	function skill805_get_sklist(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('clubbase','skill805'));
		$sklist = array();
		$locksklist = array();
		$acquired_skills = \skillbase\get_acquired_skill_array($pa);
		foreach ($acquired_skills as $skillid)
		{
			if (in_array($skillid, $skill805_except_skilllist)) continue;
			if (isset($clubskillname[$skillid]) && (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'hidden;') === false))
			{
				if (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;') === false) $sklist[] = $skillid;
				else $locksklist[] = $skillid;
			}
		}
		return array($sklist, $locksklist);
	}
	
	//超过技能数上限时无法行动，必须先忘记技能
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($mode == 'special' && $command == 'skill805_special') 
		{
			$chprocess();
			return;
		}
		if (\skillbase\skill_query(805, $sdata))
		{
			$skcount = skill805_get_skill_count($sdata);
			$clv = (int)\skillbase\skill_getvalue(805,'lvl',$sdata);
			if ($skcount > $clv)
			{
				eval(import_module('sys','logger'));
				$log .= "<span class=\"yellow b\">你最多只能同时记住{$clv}个技能（现有{$skcount}个），请先遗忘技能！</span><br>";
				$mode = 'special'; $command = 'skill805_special';
			}
		}
		$chprocess();
	}
	
	function cast_skill805()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(805, $sdata)) 
		{
			$log .= '你无法进行此操作。';
			return;
		}
		$skill805_skillid = (int)get_var_input('skill805_skillid');
		if (!empty($skill805_skillid))
		{
			skill805_forget_skill($skill805_skillid);
			$mode = 'command';
			return;
		}
		include template(MOD_SKILL805_CASTSK805);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	function skill805_forget_skill($skillid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('clubbase','skill805','logger'));
		if (!\skillbase\skill_query($skillid))
		{
			$log .= "你没有此技能！<br>";
			return;
		}
		if (!isset($clubskillname[$skillid]) || (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'hidden;') !== false) || (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;') !== false) || in_array($skillid, $skill805_except_skilllist))
		{
			$log .= "输入参数有误！<br>";
			return;
		}
		\skillbase\skill_lost($skillid);
		$log .= "<span class=\"yellow b\">你忘记了技能【{$clubskillname[$skillid]}】！</span><br>";
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($mode == 'special' && $command == 'skill805_special') 
		{
			cast_skill805();
			return;
		}
		$chprocess();
	}
	
	//显示技能介绍
	function skill805_parse_skilldesc($skillid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		ob_start();
		include template(constant('MOD_SKILL'.$skillid.'_DESC'));
		$str=ob_get_contents();
		ob_end_clean();
		//取得desc.htm中间的介绍部分
		$i=strpos($str,'_____TEMP_SKLEARN_START_FLAG_____')+strlen('_____TEMP_SKLEARN_START_FLAG_____');
		$j=strpos($str,'_____TEMP_SKLEARN_END_FLAG_____');
		$str = trim(substr($str,$i,$j-$i));
		//提取第一个td中间的部分，这里需要用到正则表达式
		preg_match('|<td[^>]*?skilldesc_left.*?>(.*?)<\\/td>\s*<td[^>]*?skilldesc_right|s', $str, $matches);
		if($matches) $str = $matches[1];
		else $str = '';
		return $str;
	}
	
}

?>