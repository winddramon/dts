<?php

namespace skill420
{
	$skill420_cd = 180;
	
	function init() 
	{
		define('MOD_SKILL420_INFO','card;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[420] = '结晶';
		$bufficons_list[420] = Array(
			'dummy' => 1,//占位符，如果其他设置全部都自动生成的话可以占位用……
		);
	}
	
	function acquire420(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill420'));
		\skillbase\skill_setvalue(420,'end_ts',$now,$pa);	
		\skillbase\skill_setvalue(420,'cd_ts',$now+$skill420_cd,$pa);	
	}
	
	function lost420(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked420(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate420()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill420','player','logger','sys','itemmain'));
		\player\update_sdata();
		//在bufficons_check_buff_state()里已经做了技能存在性判定
		// if (!\skillbase\skill_query(420) || !check_unlocked420($sdata))
		// {
		// 	$log.='你没有这个技能！<br>';
		// 	return;
		// }
		$st = \bufficons\bufficons_check_buff_state(420);
		if (!$st){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		if ($st<=2){
			$log.='技能冷却中！<br>';
			return;
		}
		if ($skillpoint<1){
			$log.='你需要消耗1个技能点来发动这个技能！<br>';
			return;
		}
		$flag = \bufficons\bufficons_set_timestamp(420, 0, $skill420_cd);
		if(!$flag) {
			$log.='发动失败！<br>';
			return;
		}
		
		$skillpoint--;
		
		// \skillbase\skill_setvalue(420,'end_ts',$now);
		// \skillbase\skill_setvalue(420,'cd_ts',$now+$skill420_cd);
		addnews ( 0, 'bskill420', $name );
		$r=rand(0,9);
		if ($r==9)
			$log.='<span class="yellow b">“咕咕咕！”</span><span class="lime b">技能「结晶」发动……成功？</span><br>';
		else
			$log.='<span class="lime b">技能「结晶」发动成功。</span><br>';
		
		$nl=array('红色方块','黄色方块','蓝色方块','绿色方块','金色方块','银色方块','水晶方块','黑色方块','白色方块','黄鸡方块');
		$itm0=$nl[$r];
		$itme0=1;$itms0=1;$itmsk0='';$itmk0='X';
		\itemmain\itemget();	
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	// function check_skill420_state(&$pa){
	// 	if (eval(__MAGIC__)) return $___RET_VALUE;
	// 	if (!\skillbase\skill_query(420, $pa) || !check_unlocked420($pa)) return 0;
	// 	eval(import_module('sys','player','skill420'));
	// 	$l=\skillbase\skill_getvalue(420,'lastuse',$pa);
	// 	if (($now-$l)<=$skill420_cd) return 2;
	// 	return 3;
	// }
	
	// function bufficons_list()
	// {
	// 	if (eval(__MAGIC__)) return $___RET_VALUE;
	// 	eval(import_module('sys','player'));
	// 	\player\update_sdata();
	// 	if ((\skillbase\skill_query(420,$sdata))&&check_unlocked420($sdata))
	// 	{
	// 		eval(import_module('skill420'));
	// 		$skill420_lst = (int)\skillbase\skill_getvalue(420,'lastuse'); 
	// 		$skill420_time = $now-$skill420_lst; 
	// 		$z=Array(
	// 			'disappear' => 0,
	// 			'clickable' => 1,
	// 			'hint' => '技能「结晶」',
	// 			'activate_hint' => '点击发动技能「结晶」',
	// 			'onclick' => "$('mode').value='special';$('command').value='skill420_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
	// 		);
	// 		if ($skill420_time<$skill420_cd)
	// 		{
	// 			$z['style']=2;
	// 			$z['totsec']=$skill420_cd;
	// 			$z['nowsec']=$skill420_time;
	// 		}
	// 		else 
	// 		{
	// 			$z['style']=3;
	// 		}
	// 		\bufficons\bufficon_show('img/skill420.gif',$z);
	// 	}
	// 	$chprocess();
	// }
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill420') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「结晶」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>