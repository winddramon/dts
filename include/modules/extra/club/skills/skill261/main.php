<?php

namespace skill261
{
	$skill261_cd = 1919810;
	$skill261_wploss = 5;
	$tmp_skill261_flag = 0;//本次行动是否已经扣过功德（划掉）殴熟
	
	function init() 
	{
		define('MOD_SKILL261_INFO','club;upgrade;locked;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[261] = '决战';
		$bufficons_list[261] = Array(
			'dummy' => 1,
		);
	}
	
	function acquire261(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill261'));
		\skillbase\skill_setvalue(261,'end_ts',1,$pa);
		\skillbase\skill_setvalue(261,'cd_ts',0,$pa);
	}
	
	function lost261(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_getvalue(261,'end_ts',$pa) > 1) 
			$pa['wp']=floor($pa['wp']/2);
	}
	
	function check_unlocked261(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=19;
	}
	
	function activate261()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill261','player','logger','sys','itemmain'));
		\player\update_sdata();
		list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(261, 0, $skill261_cd);
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}
		$wp *= 2;
		addnews ( 0, 'bskill261', $name );
		$log.='<span class="red b">技能「决战」发动成功。</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill261_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \bufficons\bufficons_check_buff_state(261,$pa);
	}

	//每次行动扣殴熟
	function skill261_wploss(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$pa) {
			eval(import_module('player'));
			$pa = & $sdata;
		}
		eval(import_module('skill261'));
		if(!$tmp_skill261_flag) {
			$pa['wp']-=$skill261_wploss;
			if($pa['wp']<50) $pa['wp']=50;
			$tmp_skill261_flag = 1;
		}
	}

	function discover($schmode){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($schmode);
		eval(import_module('sys','player','skill261'));
		if (2 == check_skill261_state($sdata))
		{
			skill261_wploss($sdata);
		}
		return $ret;
	}
	
	function move_to_area($moveto)	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill261'));
		if (2 == check_skill261_state($sdata))
		{
			skill261_wploss($sdata);
		}
		return $chprocess($moveto);
	}
	
	//格斗生效概率上升
	function get_skill263_chance(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (2 == check_skill261_state($pd))
		{
			$ret += 15;
		}
		return $ret;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill261') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"red b\">「决战」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>