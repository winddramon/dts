<?php

namespace item_uvo_extra
{
	//允许卡牌包额外功能的模式，暂时仅有肉鸽模式
	$allow_uvo_extra_gametype = array(20);
	
	function init()
	{
	}
	
	//记录获得的卡片，$tmp为0表示暂存的卡片，为1表示已使用过的卡片
	function add_card_uvo_extra($cardid, &$pa, $tmp = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$cards_uvo = get_cards_uvo_extra($pa, $tmp);
		$cards_uvo[] = $cardid;
		if ($tmp) $k = 'cards_temp';
		else $k = 'cards_used';
		\skillbase\skill_setvalue(951, $k, encode_uvo_extra($cards_uvo), $pa);
	}
	
	//获得记录的卡片列表
	function get_cards_uvo_extra(&$pa, $tmp = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($tmp) $k = 'cards_temp';
		else $k = 'cards_used';
		return decode_uvo_extra(\skillbase\skill_getvalue(951, $k, $pa));
	}
	
	function encode_uvo_extra($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return implode('_', $arr);
	}
	
	function decode_uvo_extra($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($str)) return Array();
		return explode('_', $str);
	}
	
	function item_uv_getcard($get_card_id, $get_cardinfo, $itm, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','item_uvo_extra','logger'));
		if (!in_array($gametype, $allow_uvo_extra_gametype)) return $chprocess($get_card_id, $get_cardinfo, $itm, $pa);
		
		//将卡片记录到技能中
		add_card_uvo_extra($get_card_id, $pa, 0);
		
		//碎闪等级和是否为新在游戏结束发卡时候再判断
		$blink = 0;
		$is_new = 0;
		
		$log .= '<span class="yellow b">你获得了卡片「'.$get_cardinfo['name'].'」！暂存的卡片可在卡片列表中查看。<br>暂存的和已使用的卡片会在游戏结束时获得。</span><br>';
		
		addnews ( 0, 'VOgetcard', $pa['name'], $itm, $get_cardinfo['name'] );
		
		return array($blink, $is_new);
	}
	
	//使用暂存的卡片
	
	//合成暂存的卡片
	
	//游戏结束时获得暂存的和已使用的卡片
	
	
}

?>
