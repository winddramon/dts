<?php

namespace skill247
{
	$skill247_cd = 60;
	
	function init() 
	{
		define('MOD_SKILL247_INFO','card;club;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[247] = '挖坑';
		$bufficons_list[247] = Array(
			'dummy' => 1,
		);
	}
	
	function acquire247(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill247'));
		\skillbase\skill_setvalue(247,'end_ts',$now-1,$pa);
		\skillbase\skill_setvalue(247,'cd_ts',$now+$skill247_cd,$pa);
	}
	
	function lost247(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked247(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill247_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(247, $pa) || !check_unlocked247($pa)) return 0;
		eval(import_module('sys','player','skill247'));
		$l=\skillbase\skill_getvalue(247,'lastuse',$pa);
		if (($now-$l)<=$skill247_cd) return 2;
		return 3;
	}
	
	function get_skill247_trap_eff()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$skill247_lst = (int)\skillbase\skill_getvalue(247,'end_ts'); 
		$skill247_time = $now-$skill247_lst; 
		return round(min($skill247_time/60,3)*$att);
	}
	
	function activate247()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill247','player','logger','sys'));
		\player\update_sdata();
		list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(247, 0, $skill247_cd);
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}
		$log.='<span class="lime b">技能「挖坑」发动成功。</span><br>';
		$itm_name_arr = Array('毒性【ACFUN大逃杀3.0】', '毒性【ACFUN大逃杀革新企划书】', '毒性【大逃杀新界面】', '毒性【新电波大逃杀剧情模式】','有毒的腿？');
		$itme = get_skill247_trap_eff();
		for($i=0;$i<2;$i++){
			shuffle($itm_name_arr);
			$trapitem=Array(
				'itm' => $itm_name_arr[0],
				'itmk' => 'TO',
				'itme' => $itme,
				'itms' => 1,
				'itmsk' => ''
			);
			\trap\trap_use($trapitem); 
		}
		addnews ( 0, 'bskill247', $name );
		$log.='你满意地看着你刚挖的大坑，它们一定能给玩家们带来笑容。<br>';
	}

	//提示文字的重载
	function bufficons_display_single($token, $config, &$pa=NULL) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($src, $config_ret) = $chprocess($token, $config, $pa);
		if(247 == $token && \skillbase\skill_query(247,$pa) && check_unlocked247($pa)) {
			$config_ret['activate_hint'] = '点击发动技能「挖坑」：<br>在当前地图布设2个'.get_skill247_trap_eff().'效果的毒性陷阱';
		}
		return Array($src, $config_ret);
	}
	
	function get_traplist() 	//不会踩自己的陷阱
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(247)) return $chprocess();
		eval(import_module('sys','player'));
		return $db->query("SELECT * FROM {$tablepre}maptrap WHERE pls = '$pls' AND itmsk <> '$pid' ORDER BY itmk DESC");
	}
	
	//不能获得肌肉兄贵称号
	function club_choice_probability_process($clublist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(247)) return $chprocess($clublist);
		if(isset($clublist[14])) $clublist[14]['probability'] = 0;
		return $clublist;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill247') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「挖坑」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>