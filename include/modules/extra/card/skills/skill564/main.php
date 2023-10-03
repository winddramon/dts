<?php

namespace skill564
{
	$sk564_lvl_req = 5;
	$sk564_chance_limit = 8;
	//亡灵的复活和一些没名字的隐藏技能
	//先全ban了再说，杀杀杀
	$sk564_banlist = array(19,20,21,22,24,29,31,39,56,57,58,59,79);
	$sk564_allowclublist = array(1,2,3,4,5,6,7,8,9,10,11,13,14,18,19,20,21,24);
	
	function init() 
	{	
		define('MOD_SKILL564_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[564] = '塔客';
	}
	
	function acquire564(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill564'));
		\skillbase\skill_setvalue(564, 'lastlvl', $pa['lvl'], $pa);
		\skillbase\skill_setvalue(564, 'chance', 1, $pa);
		\skillbase\skill_setvalue(564, 'limit', $sk564_chance_limit, $pa);
		\skillbase\skill_setvalue(564, 'choices', generate_sk564_choice(1), $pa);
	}
	
	function lost564(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(564, 'lastlvl', $pa);
		\skillbase\skill_delvalue(564, 'chance', $pa);
		\skillbase\skill_delvalue(564, 'limit', $pa);
		\skillbase\skill_delvalue(564, 'choices', $pa);
	}
	
	function check_unlocked564(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function sklearn_basecheck($skillid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(564) && check_unlocked564())
		{
			eval(import_module('skill564'));
			if (in_array((int)$skillid, $sk564_banlist)) return 0;
			if (defined('MOD_SKILL'.$skillid.'_INFO') && 
				strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'club;')!==false && 
				strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;')!==false)
					return 1;
			if (defined('MOD_SKILL'.$skillid.'_INFO') && 
				strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'club;')!==false && 
				strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'locked;')!==false)
					return 1;
			if (defined('MOD_SKILL'.$skillid.'_INFO') && 
				strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'club;')!==false && 
				strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'limited;')!==false)
					return 1;
		}
		return $chprocess($skillid);
	}
		
	function sklearn_checker564($a='', $b='')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(564) && check_unlocked564() && \skillbase\skill_getvalue(564, 'chance') <= 0) return 0;
		if ($a=='caller_id') return 564;
		if ($a=='show_cost') return 0;
		if ($a=='is_learnable')
		{
			eval(import_module('skill564'));
			if (in_array((int)$b, $sk564_banlist)) return 0;
			eval(import_module('player'));
			$choices = \skillbase\skill_getvalue(564, 'choices', $sdata);
			if ('' !== $choices)
			{
				$ls = explode('_', $choices);
				if (in_array($b, $ls)) return 1;
			}
			return 0;
		}
		if ($a=='now_learnable') return 1;
	}
	
	function generate_sk564_choice($first=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('clubbase','player','skill564'));	
		$ls_skills = array();
		foreach ($clublist as $club => $arr)
		{
			if (in_array($club, $sk564_allowclublist))
			{
				foreach ($arr['skills'] as $skillid) 
				{
					//不会随机出已学会的技能和被ban的技能
					if (sklearn_basecheck($skillid) && !\skillbase\skill_query($skillid, $sdata))
					{
						//第一次必然为三个称号特性选一
						if (1 == $first)
						{
							if (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;')!==false) $ls_skills[] = $skillid;
						}
						else $ls_skills[] = $skillid;
					}
				}
			}
		}
		$randkeys = array_rand($ls_skills, 3);
		$ls = array();
		foreach ($randkeys as $key) $ls[] = $ls_skills[$key];
		$choices = implode('_', $ls);
		return $choices;
	}
	
	//升5级获得一次学习机会
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(564,$pa) && check_unlocked564($pa))
		{
			eval(import_module('skill564'));
			$lastlvl = (int)\skillbase\skill_getvalue(564, 'lastlvl', $pa);
			$chance = (int)\skillbase\skill_getvalue(564, 'chance', $pa);
			$limit = (int)\skillbase\skill_getvalue(564, 'limit', $pa);
			if ($limit > 0)
			{
				if ($pa['lvl'] - $lastlvl >= $sk564_lvl_req)
				{
					 \skillbase\skill_setvalue(564, 'lastlvl', $pa['lvl'], $pa);
					 \skillbase\skill_setvalue(564, 'chance', $chance + 1, $pa);
					 \skillbase\skill_setvalue(564, 'limit', $limit - 1, $pa);
				}
			}	
		}
		$chprocess($pa);
	}
	
	function upgrade564()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill564','player','logger','input'));
		$skillpara1 = (int)$skillpara1;
		$chance = (int)\skillbase\skill_getvalue(564, 'chance', $pa);
		if (!\skillbase\skill_query(564) || !check_unlocked564($sdata)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if ($chance <= 0)
		{
			$log .= '你没有学习技能的次数了！<br>';
			return;
		}			
		if (!\sklearn_util\sklearn_basecheck($skillpara1) || !sklearn_checker564('is_learnable',$skillpara1)) 
		{
			$log .= '你不可以学习这个技能！<br>';
			return;
		}
		if (!sklearn_checker564('now_learnable',$skillpara1))
		{
			$log .= '现在尚无法学习这个技能！<br>';
			return;
		}
		if (\skillbase\skill_query($skillpara1))
		{
			$log .= '你已经拥有这个技能了！<br>';
			return;
		}
		\skillbase\skill_acquire($skillpara1);
		\skillbase\skill_setvalue(564, 'chance', $chance - 1, $pa);
		\skillbase\skill_setvalue(564, 'choices', generate_sk564_choice(), $pa);	
		$log .= '学习成功。<br>';
	}
}

?>