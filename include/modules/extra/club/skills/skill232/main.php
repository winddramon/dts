<?php

namespace skill232
{
	$shieldgain = Array(110,140,170,200,230,300);
	$shieldeff = Array(10,15,20,25,30,50);
	$upgradecost = Array(4,4,5,5,6,-1);
	$skill232_cd = Array(150,120,120,90,60,45);
	
	function init() 
	{
		define('MOD_SKILL232_INFO','club;upgrade;locked;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[232] = '力场';
		$bufficons_list[232] = Array(
			'dummy' => 1,
		);
	}
	
	function acquire232(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(232,'lvl','0',$pa);
		\skillbase\skill_setvalue(232,'end_ts',1,$pa);
		\skillbase\skill_setvalue(232,'cd_ts',0,$pa);
	}
	
	function lost232(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked232(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=5;
	}
	
	function upgrade232()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill232','player','logger'));
		if (!\skillbase\skill_query(232))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(232,'lvl');
		$ucost = $upgradecost[$clv];
		if ($clv == -1)
		{
			$log.='你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint<$ucost) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$skillpoint-=$ucost; \skillbase\skill_setvalue(232,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function activate232()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill232','player','logger','sys'));
		\player\update_sdata();
		$clv=\skillbase\skill_getvalue(232,'lvl');
		list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(232, 0, $skill232_cd[$clv]);
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}
		
		$sc = $shieldgain[$clv];
		if ($hp<($mhp+$sc)) $hp=$mhp+$sc;
		addnews ( 0, 'bskill232', $name );
		$log.='<span class="lime b">技能「力场」发动成功。</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill232_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \bufficons\bufficons_check_buff_state(232,$pa);
	}
	
	function check_skill232_shield_on(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = true;
		if (!\skillbase\skill_query(232, $pa) || !check_unlocked232($pa)) $ret = false;
		if ($pa['hp'] <= $pa['mhp']) $ret = false;
		return $ret;
	}
	
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (check_skill232_shield_on($pd)) 
		{
			eval(import_module('logger','skill232'));
			$clv = (int)\skillbase\skill_getvalue(232,'lvl',$pd);
			$v=$shieldeff[$clv];
			$log.=\battle\battlelog_parser($pa,$pd,$active,'力场护盾抵消了<:pd_name:>受到的<span class="yellow b">'.$v.'</span>点伤害！<br>');
			$ret -= $v;
			$pa['mult_words_fdmgbs'] = \attack\add_format(-$v, $pa['mult_words_fdmgbs']);
		}
		return $ret;
	}

	//有盾时不受反噬
	function calculate_hp_rev_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (check_skill232_shield_on($pa)){
			return 0;
		}
		return $chprocess($pa,$pd,$active);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill232') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「力场」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>