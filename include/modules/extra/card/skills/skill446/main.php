<?php

namespace skill446
{
	$skill446_act_time = 30;
	
	function init() 
	{
		define('MOD_SKILL446_INFO','card;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[446] = '死线';
		$bufficons_list[446] = Array(
			'disappear' => 1,
		);
	}
	
	function acquire446(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill446'));
		\skillbase\skill_setvalue(446,'end_ts',1,$pa);	
		\skillbase\skill_setvalue(446,'cd_ts',0,$pa);
	}
	
	function lost446(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked446(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate446()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill446','player','logger','sys'));
		\player\update_sdata();
		list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(446, $skill446_act_time, 0);
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}
		addnews ( 0, 'bskill446', $name );
		$log.='<span class="lime b">技能「死线」发动成功。</span><br>';
	}
	
	function apply_total_damage_modifier_invincible(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == \bufficons\bufficons_check_buff_state(446,$pd)){
			eval(import_module('logger'));
			$pa['dmg_dealt']=0;
			if ($active) $log .= "<span class=\"yellow b\">敌人的技能「死线」使你的攻击没有造成任何伤害！</span><br>";
			else $log .= "<span class=\"yellow b\">你的技能「死线」使敌人的攻击没有造成任何伤害！</span><br>";
		}
		$chprocess($pa,$pd,$active);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill446') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「死线」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>