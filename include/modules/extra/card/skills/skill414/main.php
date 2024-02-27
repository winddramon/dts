<?php

namespace skill414
{
	$skill414_cd = 0;
	
	function init() 
	{
		define('MOD_SKILL414_INFO','card;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[414] = '鹰眼';
		$bufficons_list[414] = Array(
			'disappear' => 1,
		);
	}
	
	function acquire414(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill414'));
		\skillbase\skill_setvalue(414,'end_ts',1,$pa);	
		\skillbase\skill_setvalue(414,'cd_ts',0,$pa);	
	}
	
	function lost414(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked414(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate414()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill414','player','logger','sys'));
		\player\update_sdata();
		$st = \bufficons\bufficons_check_buff_state(414);
		if (!$st){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		if (1 == $st){
			$log.='你已经发动过这个技能了！<br>';
			return;
		}
		if (2 == $st){
			$log.='技能冷却中！<br>';
			return;
		}
		$flag = \bufficons\bufficons_set_timestamp(414, 600+$wc*4, $skill414_cd);
		if(!$flag) {
			$log.='发动失败！<br>';
			return;
		}
		addnews ( 0, 'bskill414', $name );
		$log.='<span class="lime b">技能「鹰眼」发动成功。</span><br>';
	}
	
	function get_hitrate_change(&$pa,&$pd,$active,$hitrate)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1!=\bufficons\bufficons_check_buff_state(414,$pa) || $pa['wep_kind']=='D' || $pa['wepk']=='WJ') return $chprocess($pa, $pd, $active,$hitrate);
		return 10000;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill414') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「鹰眼」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>