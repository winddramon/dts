<?php

namespace ex_vanish
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['X'] = '消失';
		$itemspkdesc['X'] = '这一道具会在持有角色死亡或玩家获得时消失';
		$itemspkremark['X'] = '……';
	}
	
	//持有角色死亡时消失
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if ($pd['hp'] <= 0)
		{
			eval(import_module('logger'));
			$vanish_item_names = array();
			foreach (array('wep','arb','arh','ara','arf','art','itm0','itm1','itm2','itm3','itm4','itm5','itm6') as $itempos) 
			{
				if (substr($itempos,0,3) == 'itm') $itmsk = 'itmsk'.$itempos[-1];
				else $itmsk = $itempos.'sk';
				if (\itemmain\check_in_itmsk('X', $pd[$itmsk]))
				{
					$vanish_item_names[] = $pd[$itempos];
					\itemmain\item_destroy_core($itempos, $pd);
				}
			}
			if ($vanish_item_names)
			{
				$vicount = count($vanish_item_names);
				if ($vicount > 1) $vi_words = implode('、', array_slice($vanish_item_names, 0, $vicount - 1)).'和'.end($vanish_item_names);
				else $vi_words = $vanish_item_names[0];
				$log .= "<span class=\"yellow b\">$vi_words</span>化为灰烬消失了。<br>";
			}
		}
	}
	
	//玩家获得时消失
	function itemget()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\itemmain\check_in_itmsk('X', $itmsk0))
		{
			eval(import_module('sys','logger'));
			$log .= "<span class=\"yellow b\">$itm0</span>化为灰烬消失了。<br>";
			$itm0 = $itmk0 = $itmsk0 = '';
			$itme0 = $itms0 = 0;
			$mode = 'command';
			return;
		}
		$chprocess();
	}
	
}

?>
