<?php

namespace skill76
{
	$skill76_cd = 600;
	
	function init() 
	{
		define('MOD_SKILL76_INFO','club;upgrade;locked;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[76] = '充能';
		$bufficons_list[76] = Array(
			'dummy' => 1,
		);
	}
	
	function acquire76(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill76'));
		\skillbase\skill_setvalue(76,'end_ts',1,$pa);	
		\skillbase\skill_setvalue(76,'cd_ts',0,$pa);
		\skillbase\skill_setvalue(76,'rmt',2,$pa);
	}
	
	function lost76(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked76(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=11;
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill76_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \bufficons\bufficons_check_buff_state(76, $pa);
	}
	
	function activate76()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill76','player','logger','sys'));
		\player\update_sdata();
		list($can_activate, $fail_hint) = \bufficons\bufficons_check_buff_state_shell(76);
		if(!$can_activate) {
			$log .= $fail_hint;
			return;
		}
		if($rage >= (int)\player\get_max_rage()){
			$log.='你的怒气已经满了！<br>';
			return;
		}
		$skill76_real_cd = $skill76_cd;
		$r=(int)\skillbase\skill_getvalue(76,'rmt',$pa);
		if ($r>0)
		{
			\skillbase\skill_setvalue(76,'rmt',$r-1,$pa);
			$skill76_real_cd = 0.1;
		}
		$flag = \bufficons\bufficons_set_timestamp(76, 0, $skill76_real_cd);
		if(!$flag) {
			$log.='发动失败！<br>';
			return;
		}
		$rage = \player\get_max_rage();
		addnews ( 0, 'bskill76', $name );
		$log.='<span class="lime b">技能「充能」发动成功。</span><br>';
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill76') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「充能」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>