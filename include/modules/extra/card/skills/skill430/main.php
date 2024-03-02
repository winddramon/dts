<?php

namespace skill430
{
	$skill430_cd = 60;
	
	function init() 
	{
		define('MOD_SKILL430_INFO','card;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[430] = '搬运';
		$bufficons_list[430] = Array(
			'dummy' => 1,
		);
	}
	
	function acquire430(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill430'));
		\skillbase\skill_setvalue(430,'end_ts',$now-1,$pa);	
		\skillbase\skill_setvalue(430,'cd_ts',$now+$skill430_cd,$pa);	
	}
	
	function lost430(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked430(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate430()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill430','player','logger','sys','itemmain'));
		\player\update_sdata();
		
		list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(430, 0, $skill430_cd);
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}
		$log.='<span class="lime b">技能「搬运」发动成功。</span><br>';

		$file=GAME_ROOT."./include/modules/base/itemmain/config/mapitem.config.php";//真是丑陋！
		$itemlist = openfile($file);
		$in = sizeof($itemlist);
		do{
			$i=rand(4,$in-1);//妈了个臀
			list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
		} while (!is_numeric($iarea) || $iarea > 100 || \itemmain\check_in_itmsk('x', $iskind));
		$itm0=$iname;
		$itme0=$ieff;
		$itms0=$ista;
		$itmk0=$ikind;
		$itmsk0=\attrbase\config_process_encode_comp_itmsk($iskind);
		addnews ( 0, 'bskill430', $name,$iname );
		\itemmain\itemget();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill430') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「搬运」</span>，获得了<span class=\"yellow b\">{$b}</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>