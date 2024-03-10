<?php

namespace skill483
{
	$skill483_effect_duration = 120;

	$skill483_cd = 0;
	
	function init() 
	{
		define('MOD_SKILL483_INFO','card;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[483] = '氪金';
		$bufficons_list[483] = Array(
			'dummy' => 1,
		);
	}
	
	function acquire483(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill483'));
		\skillbase\skill_setvalue(483,'end_ts',1,$pa);	
		\skillbase\skill_setvalue(483,'cd_ts',0,$pa);
		\skillbase\skill_setvalue(483,'cost',500,$pa);
	}
	
	function lost483(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked483(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate483()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill483','player','logger','sys'));
		\player\update_sdata();

		//所有主动技能统一的触发语句
		list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(483, $skill483_effect_duration, 0);
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}

		$c=(int)\skillbase\skill_getvalue(483,'cost',$pa);
		$money-=$c;
		\skillbase\skill_setvalue(483,'cost',$c*2);
		addnews ( 0, 'bskill483', $name );
		$log.='<span class="lime b">技能「氪金」发动成功。</span><br>';
	}

	//能否触发技能的特殊判定
	function bufficons_check_buff_state_shell($token, &$pa=NULL, $msec=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($can_activate, $fail_hint) = $chprocess($token, $pa, $msec);
		if ($can_activate && 483 == $token){
			if(!$pa) {
				eval(import_module('player'));
				$pa = & $sdata;
			}
			$c=(int)\skillbase\skill_getvalue(483,'cost',$pa);
			if($pa['money'] < $c) {
				$fail_hint.='<span class="yellow b">金钱不足！用你的脑子想一想，不充钱你会变得更强吗？</span><br>';
				$can_activate = false;
			}
		}
		return array($can_activate, $fail_hint);
	}
	
	//复活判定注册
	function set_revive_sequence(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd);
		if(1 == \bufficons\bufficons_check_buff_state(483, $pd)){
			$pd['revive_sequence'][100] = 'skill483';
		}
		return;
	}	
	
	//复活判定
	function revive_check(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $rkey);
		if('skill483' == $rkey && in_array($pd['state'],Array(20,21,22,23,24,25,29)) && $pa['type']==0){
			$ret = true;
		}
		return $ret;
	}
	
	//回满血，发复活状况
	function post_revive_events(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $rkey);
		if('skill483' == $rkey){
			$pd['hp']=$pd['mhp'];
			$pd['skill483_flag']=1;
			$pd['rivival_news'] = array('revival483', $pa['name'], $pd['name']);
			//addnews ( 0, 'revival483', $pa['name'], $pd['name'] );
		}
		return;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('sys','logger'));
		if (isset($pd['skill483_flag']) && $pd['skill483_flag'])
		{
			if ($active)
			{
				$log.='<span class="lime b">但是氪金战士不可能死！敌人又站了起来！</span><br>';
				$pd['battlelog'].='<span class="lime b">但是氪金战士不可能死！</span>你又站了起来，';
			}
			else
			{
				$log.='<span class="lime b">但是氪金战士不可能死！你又站了起来！</span><br>';
				$pa['battlelog'].='<span class="lime b">但是氪金战士不可能死！</span>敌人又站了起来，';
			}
		}
	}

	//提示文字的重载
	function bufficons_display_single($token, $config, &$pa=NULL) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($src, $config_ret) = $chprocess($token, $config, $pa);
		if(483 == $token && \skillbase\skill_query(483,$pa) && check_unlocked483($pa)) {
			$config_ret['activate_hint'] = '点击发动技能「氪金」，消耗'.\skillbase\skill_getvalue(483,'cost',$pa).'元';
		}
		return Array($src, $config_ret);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill483') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「氪金」</span></span></li>";
		
		if($news == 'revival483') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}击杀了{$b}，却不料{$b}是传说中的氪金战士！{$b}又站了起来！</span></li>";
		
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
