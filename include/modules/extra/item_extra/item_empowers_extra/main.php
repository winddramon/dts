<?php

namespace item_empowers_extra
{
	$ec_words = array(
		0 => array(1=>'效果值+100', 2=>'耐久值+50', 3=>'获得1-3个属性'),
		1 => array(1=>'效果值+500', 2=>'耐久值+300', 3=>'获得1-3个属性'),
		2 => array(1=>'效果值+2000', 2=>'耐久值变为∞', 3=>'获得2-4个属性'),
		3 => array(1=>'效果值+5000', 2=>'强化武器类别', 3=>'获得2-4个属性')
	);
	
	function init()
	{
		eval(import_module('itemmain'));
		$iteminfo['EC'] = '改造核心';
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if (strpos($k,'EC')===0)
		{
			$ret .= '使用后可以选择强化手中武器的效果值、耐久值或属性';
		}
		return $ret;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos($itmk, 'EC') === 0) 
		{
			if (!$weps || !$wepe || strpos($wepk,'W') !== 0)
			{
				$log .= '请先装备武器。<br>';
				$mode = 'command';
				return;
			}
			if (\itemmain\check_in_itmsk('j', $wepsk))
			{
				$log .= '多重武器不能改造。<br>';
				$mode = 'command';
				return;
			}
			$log .= "你使用了<span class=\"yellow b\">{$itm}</span>。<br>";
			$ec_lvl = get_ec_lvl($itmsk);
			$ec_choice = get_var_input('ec_choice');
			if (empty($ec_choice))
			{
				ob_start();
				include template(MOD_ITEM_EMPOWERS_EXTRA_USE_EMPOWERCORE);
				$cmd = ob_get_contents();
				ob_end_clean();	
				return;
			}
			else
			{
				$ec_choice = (int)$ec_choice;
				if (!in_array($ec_choice, array(1,2,3)))
				{
					$log .= '参数不合法。<br>';
					$mode = 'command';
					return;
				}
				if ($ec_choice == 1)
				{
					$wepe += array(100,500,2000,5000)[$ec_lvl];
					$log .= "<span class=\"yellow b\">{$wep}</span>的效果值增加了！<br>";
				}
				elseif ($ec_choice == 2)
				{
					if ($ec_lvl < 3)
					{
						if ($weps == $nosta)
						{
							$log .= '无法进行这项改造。<br>';
							$mode = 'command';
							return;
						}
						if ($ec_lvl == 0) $weps += 50;
						elseif ($ec_lvl == 1) $weps += 300;
						else $weps = $nosta;
						$log .= "<span class=\"yellow b\">{$wep}</span>的耐久值得到了强化！<br>";
					}
					else
					{
						if (substr($wepk, 0, 2) == 'WG') $wepk = 'WJ'.substr($wepk, 2);
						elseif (substr($wepk, 0, 2) == 'WC') $wepk = 'WB'.substr($wepk, 2);
						elseif (!in_array(substr($wepk, 2, 1), array('P','K','G','C','D','F','J','B')))
						{
							$newk = array_randompick(array_diff(array('P','K','G','C','D','F'), array(substr($wepk, 1, 1))));
							$wepk = substr($wepk, 0, 2).$newk.substr($wepk, 2);
						}
						else
						{
							$log .= '无法进行这项改造。<br>';
							$mode = 'command';
							return;
						}
						$log .= "<span class=\"yellow b\">{$wep}</span>的类别发生了神奇的变化！<br>";
					}
				}
				else
				{
					if ($ec_lvl == 0) $skpool = array('u','e','i','w','p','N','H','c','z');
					elseif ($ec_lvl == 1) $skpool = array('d','r','u','e','i','w','p','N','H');
					elseif ($ec_lvl == 2) $skpool = array('f','k','t','d','r','L','u','e','i','w','p','N');
					else $skpool = array('f','k','t','d','r','L','n','y','^ac1');
					
					$skpool = array_diff($skpool, \itemmain\get_itmsk_array($wepsk));
					if (empty($skpool))
					{
						$log .= '无法进行这项改造。<br>';
						$mode = 'command';
						return;
					}
					if ($ec_lvl < 2) $newsk_count = min(rand(1,3), count($skpool));
					else $newsk_count = min(rand(2,4), count($skpool));
					$tmpsk = array_randompick($skpool, $newsk_count);
					if (is_array($tmpsk))
					{
						foreach ($tmpsk as $sk)
						{
							$wepsk .= $sk;
						}
					}
					else $wepsk .= $tmpsk;
					$log .= "<span class=\"yellow b\">{$wep}</span>获得了额外的属性！<br>";
				}
				if (strpos($wep, '-改') === false) $wep = $wep.'-改';
			}
			\itemmain\itms_reduce($theitem);
			return;
		}
		$chprocess($theitem);
	}
	
	function get_ec_lvl($itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!in_array((int)$itmsk, array(1,2,3))) return 0;
		return (int)$itmsk;
	}
	
}

?>