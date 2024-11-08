<?php

namespace skill763
{
	function init() 
	{
		define('MOD_SKILL763_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[763] = '〇度';
	}
	
	function acquire763(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost763(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked763(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		eval(import_module('player'));
		if (($itm == '笔记本电脑' || $itm == '手机') && !empty($inf) && \skillbase\skill_query(763))
		{
			eval(import_module('logger'));
			if ($itme <= 0)
			{
				$log .= "<span class=\"yellow b\">$itm</span>没电了，需要换电池了。<br>";
				return;
			}
			else
			{
				eval(import_module('wound'));
				$log .= "你按照<span class=\"yellow b\">$itm</span>上搜索到的方法开始治疗自己……<br>";
				$itme -= 1;
				$dice = rand(0, 99);
				$inf_arr = str_split($inf);
				$inf_rm = array_diff(array('h', 'b', 'a', 'f', 'p', 'u', 'i', 'e', 'w'), $inf_arr);
				if ($dice < 3)
				{
					$log .= "<span class=\"lime b\">你幸运地完全康复了！<br>";
					$inf = '';
				}
				elseif ($dice < 25)
				{
					$i_heal = array_randompick($inf_arr);
					$inf = str_replace($i_heal,'',$inf);
					$log .= "你幸运地解除了{$infname[$i_heal]}状态！<br>";
				}
				elseif ($dice < 40 && !empty($inf_rm))
				{
					$i_heal = array_randompick($inf_arr);
					$i_new = array_randompick($inf_rm);
					$log .= "但方法并不靠谱，你虽然解除了{$infname[$i_heal]}状态，但又{$infname[$i_new]}了！<br>你的怒气增加了5点！<br>";
					$inf = str_replace($i_heal,$i_new,$inf);
					\rage\get_rage(5, $sdata);
				}
				elseif ($dice < 97 && !empty($inf_rm))
				{
					$log .= "<span class=\"red b\">但方法并不靠谱，你将自己治得伤痕累累！</span><br>你的怒气和经验增加了10点！<br>";
					if (count($inf_rm) == 1) $inf .= $inf_rm[0];
					else
					{
						$i_new = array_randompick($inf_rm, 2);
						$inf .= implode('', $i_new);
					}
					\bufficons\bufficons_impose_buff(600, 10, 0, $sdata);
					\rage\get_rage(10, $sdata);
					\lvlctl\getexp(10, $sdata);
				}
				else
				{
					$log .= "<span class=\"red b\">但方法并不靠谱，你将自己治得奄奄一息！</span><br>你的怒气和经验增加了30点！<br>";
					$inf = 'hbafpuiew';
					$hp = 1;
					$mhp = max(1, $mhp - 100);
					\bufficons\bufficons_impose_buff(600, 60, 0, $sdata);
					\rage\get_rage(30, $sdata);
					\lvlctl\getexp(30, $sdata);
				}
				return;
			}
		}
		$chprocess($theitem);
	}

}

?>