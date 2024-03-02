<?php

namespace skill593
{
	$skill593_cd = 60;
	
	function init() 
	{
		define('MOD_SKILL593_INFO','card;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[593] = '秘药';
		$bufficons_list[593] = Array(
			'dummy' => 1,//占位符，如果其他设置全部都自动生成的话可以占位用……
		);
	}
	
	function acquire593(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill593'));
		\skillbase\skill_setvalue(593,'end_ts',$now-1,$pa);	
		\skillbase\skill_setvalue(593,'cd_ts',$now+$skill593_cd,$pa);	
	}
	
	function lost593(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked593(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate593()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','sys','skill593'));
		\player\update_sdata();
		$lastuse = \skillbase\skill_getvalue(593,'end_ts');
		list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(593, 0, $skill593_cd);
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}
		$log.='<span class="lime b">技能「秘药」发动成功。</span><br>';

		get_skill593_item($lastuse);
	}
	
	function get_skill593_item($lastuse)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain'));
		$file = __DIR__.'/config/skill593.config.php';
		$itemlist = openfile($file);
		$in = sizeof($itemlist);
		
		$i = rand(0, $in-1);				
		if (rand(0,1) > 0)
		{
			$sk593_time = min($now - $lastuse, 300);
			$i2 = rand((int)($sk593_time * 0.3), $in-1);
			$i = max($i, $i2);
		}
		
		list($iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
		$itm0=$iname;
		$itmk0=$ikind;
		$itme0=$ieff;
		$itms0=$ista;
		$itmsk0=\attrbase\config_process_encode_comp_itmsk($iskind);
		addnews(0, 'bskill593', $name, $iname);
		\itemmain\itemget();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill593') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「秘药」</span>，获得了<span class=\"yellow b\">{$b}</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if ($n == '「蝴蝶梦丸」'){
			$ret .= '服用后会精神舒畅';
		}elseif ($n == '「蝴蝶梦丸噩梦」') {
			$ret .= '服用后会噩梦缠身';
		}elseif ($n == '「国士无双之药」') {
			$ret .= '服用后身体会变得强壮，但使用第四次后会发生意外';
		}elseif ($n == '「蓬莱之药」') {
			$ret .= '服用后会受到永生的诅咒';
		}
		return $ret;
	}
	
}

?>