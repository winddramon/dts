<?php

namespace gtype7
{
	function init() {
		eval(import_module('skillbase'));
		if(!isset($valid_skills[7])) {
			$valid_skills[7] = array();
		}
		$valid_skills[7] += array(1001);
	}
	
	function init_enter_battlefield_items($ebp){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ebp = $chprocess($ebp);
		eval(import_module('sys'));
		if (7==$gametype){
			$ebp['itm4'] = '生命探测器'; $ebp['itmk4'] = 'ER'; $ebp['itme4'] = 3; $ebp['itms4'] = 1;$ebp['itmsk4'] = '';
			$ebp['itm5'] = '全恢复药剂'; $ebp['itmk5'] = 'Ca'; $ebp['itme5'] = 1; $ebp['itms5'] = 3;$ebp['itmsk5'] = '';
			$ebp['itm6'] = '陈旧的大逃杀卡牌包'; $ebp['itmk6'] = 'VO9'; $ebp['itme6'] = 1; $ebp['itms6'] = 1;$ebp['itmsk6'] = '';
		}
		return $ebp;
	}
	
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','gtype7'));
		if (7==$gametype){
			return $npcinfo_gtype7;
		}else return $chprocess();
	}
	
	//开局卡片固定为挑战者
	function get_enter_battlefield_card($card){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$card = $chprocess($card);
		if (7==$gametype){
			$card=0;
		}
		return $card;
	}
	
	function card_validate_get_forbidden_cards($card_disabledlist, $card_ownlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		$card_disabledlist = $chprocess($card_disabledlist, $card_ownlist);
		if(7==$gametype)
		{
			foreach($card_ownlist as $cv){
				if(0 != $cv) $card_disabledlist[$cv][]='e3';
			}
		}
		return $card_disabledlist;
	}
	
	//卡片乱斗模式选卡界面特殊显示
	function card_validate_display($cardChosen, $card_ownlist, $packlist, $hideDisableButton){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		list($cardChosen, $card_ownlist, $packlist, $hideDisableButton) = $chprocess($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
		
		if(7==$gametype)
		{
			$cardChosen = 0;//自动选择挑战者
			$card_ownlist[] = 0;
			$cards[0]['pack'] = 'Standard Pack';
			$hideDisableButton = 0;
		}
		
		return array($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
	}
	
	function prepare_new_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (room_check_subroom($room_prefix)) {
			$chprocess();
			return;
		}
		
		if (!$disable_event){
			list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
 			if ($wday==6 && $hour>=19 && $hour<21){ //周六19点-21点
 				$gametype=7;
 			}
 		}
 		
		$chprocess();
	}
	
}

?>