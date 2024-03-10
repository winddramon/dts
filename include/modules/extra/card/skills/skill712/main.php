<?php

namespace skill712
{
	$skill712_cd = 30;
	
	function init() 
	{
		define('MOD_SKILL712_INFO','card;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[712] = '宝槌';
		$bufficons_list[712] = Array(
			'dummy' => 1,//占位符，如果其他设置全部都自动生成的话可以占位用……
		);
	}
	
	function acquire712(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill712'));
		\skillbase\skill_setvalue(712,'end_ts',$now-1,$pa);	
		\skillbase\skill_setvalue(712,'cd_ts',$now+$skill712_cd,$pa);	
	}
	
	function lost712(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked712(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function activate712()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','skill712'));
		\player\update_sdata();
		list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(712, 0, $skill712_cd);
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}
		//$log.='<span class="lime b">技能「宝槌」发动成功。</span><br>';
		skill712_process();
	}
	
	function skill712_process()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE pls = '$pls'");
		$itemnum = $db->num_rows($result);
		if ($itemnum <= 0){
			$log .= '<span class="yellow b">周围没有任何物品，你换了个锤子！</span><br>';
			return;
		}
		$mipool = Array();
		while($r = $db->fetch_array($result)){
			if(\itemmain\discover_item_filter($r))
				$mipool[] = $r;
		}
		shuffle($mipool);
		//将全部视野置入记忆
		\searchmemory\change_memory_unseen('ALL');
		\skill1006\add_beacon_from_itempool($mipool, \searchmemory\calc_memory_slotnum());
		$log .= '<span class="yellow b">你挥动万宝槌，将全部视野刷新了！</span><br>';
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill712') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「宝槌」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>