<?php

namespace skill984
{
	function init() 
	{
		define('MOD_SKILL984_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[984] = '抉择';
	}
	
	function acquire984(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(984,'lvl','0',$pa);
	}
	
	function lost984(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(984,'lvl',$pa);
	}
	
	function check_unlocked984(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill981_bonus_items(&$pa, $stage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $stage);
		if (\skillbase\skill_query(984,$pa) && check_unlocked984($pa))
		{
			$clv = (int)\skillbase\skill_getvalue(984,'lvl',$pa);
			if ($clv == 1 && rand(0,1))
			{
				$theitem = array('itm'=>'梦境礼盒','itmk'=>'Y','itme'=>1,'itms'=>1,'itmsk'=>'');
			}
			elseif ($clv == 2)
			{
				$dice = round(rand(0, 999) * (0.03 * $stage + 0.9));
				if ($dice < 700) $theitem = array('itm'=>'改造核心·C级','itmk'=>'EC','itme'=>1,'itms'=>1,'itmsk'=>'');
				elseif ($dice < 850) $theitem = array('itm'=>'改造核心·B级','itmk'=>'EC','itme'=>1,'itms'=>1,'itmsk'=>'1');
				elseif ($dice < 950) $theitem = array('itm'=>'改造核心·A级','itmk'=>'EC','itme'=>1,'itms'=>1,'itmsk'=>'2');
				else $theitem = array('itm'=>'改造核心·S级','itmk'=>'EC','itme'=>1,'itms'=>1,'itmsk'=>'3');
			}
			elseif ($clv == 3)
			{
				$dice = round(rand(0, 999) * (0.01 * $stage + 0.98));
				if ($dice < 900) $theitem = array('itm'=>'沾满灰尘的大逃杀卡牌包','itmk'=>'VO8','itme'=>1,'itms'=>1,'itmsk'=>'');
				elseif ($dice < 960) $theitem = array('itm'=>'陈旧的大逃杀卡牌包','itmk'=>'VO9','itme'=>1,'itms'=>1,'itmsk'=>'');
				elseif ($dice < 989) $theitem = array('itm'=>'普通的大逃杀卡牌包','itmk'=>'VO2','itme'=>1,'itms'=>1,'itmsk'=>'');
				elseif ($dice < 999) $theitem = array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>1,'itmsk'=>'');
				else $theitem = array('itm'=>'闪耀的大逃杀卡牌包','itmk'=>'VO5','itme'=>1,'itms'=>1,'itmsk'=>'');
			}
			if (!empty($theitem))
			{
				eval(import_module('logger'));
				$log .= "<span class=\"lime b\">你获得了额外的奖励道具！</span><br>";
				$ret[] = $theitem;
			}
		}
		return $ret;
	}
	
}

?>
