<?php

namespace skill750
{
	function init() 
	{
		define('MOD_SKILL750_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[750] = '重置';
	}
	
	function acquire750(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost750(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function enter_battlefield_cardproc($ebp, $card)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$ret = $chprocess($ebp, $card);
		if (isset($ret[1][750]))
		{
			unset($ret[1][750]);
			$ebp = &$ret[0];
			//获得1个肉鸽模式开局武器和2个肉鸽模式开局道具
			$weplist = openfile(GAME_ROOT.'./include/modules/extra/instance/instance10_rogue/config/stwep.config.php');
			do {
				$index = rand(1,count($weplist)-1);
				list($ebp['itm4'],$ebp['itmk4'],$ebp['itme4'],$ebp['itms4'],$ebp['itmsk4']) = \itemmain\startingitem_row_data_seperate($weplist[$index]);
				if(defined('MOD_ATTRBASE')) {
					$ebp['itmsk4'] = \attrbase\config_process_encode_comp_itmsk($ebp['itmsk4']);
				}
			} while(!$ebp['itms4']);
			$stitemlist = openfile(GAME_ROOT.'./include/modules/extra/instance/instance10_rogue/config/stitem.config.php');
			for($i=5;$i<=6;$i++){
				do {
					$index = rand(1,count($stitemlist)-1);
					list($ebp['itm'.$i],$ebp['itmk'.$i],$ebp['itme'.$i],$ebp['itms'.$i],$ebp['itmsk'.$i]) = \itemmain\startingitem_row_data_seperate($stitemlist[$index]);
					if(defined('MOD_ATTRBASE')) {
						$ebp['itmsk'.$i] = \attrbase\config_process_encode_comp_itmsk($ebp['itmsk'.$i]);
					}
				} while(!$ebp['itms'.$i]);
			}
		}
		return $ret;
	}
	
}

?>
