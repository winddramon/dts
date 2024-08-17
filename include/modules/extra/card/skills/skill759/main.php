<?php

namespace skill759
{
	function init() 
	{
		define('MOD_SKILL759_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[759] = '捞针';
	}
	
	function acquire759(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$rpls = add_skill759_mapitem();
		\skillbase\skill_setvalue(759,'rpls',$rpls,$pa);
	}
	
	function lost759(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(759,'rpls',$pa);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if (\skillbase\skill_query(759))
		{
			$rpls = (int)\skillbase\skill_getvalue(759,'rpls');
			eval(import_module('map', 'logger'));
			$tip_chars = array('常', '地', '高', '幻', '界', '精', '究', '林', '梦', '墓', '森', '社', '神', '使', '世', '斯', '天', '校', '学', '研', '镇', '之', '中');
			shuffle($tip_chars);
			$log .= "<span class=\"yellow b\">你手边的纸条上写着：■■■■位■■■■";
			foreach($tip_chars as $v)
			{
				if (strpos($plsinfo[$rpls], $v) !==false)
				{
					$log .= $v;
					break;
				}
			}
			$log .= "■■■■■……</span><br><br>";
			\skillbase\skill_lost(759);
		}
		$chprocess();
	}
	
	function add_skill759_mapitem()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','itemmain'));
		$plsno_arr = \map\get_all_plsno();
		do {
			$rpls = array_randompick($plsno_arr);
		} while (in_array($rpls,$map_noitemdrop_arealist));
		$itm = '改造核心·S级';
		$itmk = 'EC';
		$itme = 1;
		$itms = 1;
		$itmsk = '3';
		$db->query("INSERT INTO {$tablepre}mapitem (itm, itmk, itme, itms, itmsk, pls) VALUES ('$itm', '$itmk', '$itme', '$itms', '$itmsk', '$rpls')");
		return $rpls;
	}
	
}

?>
