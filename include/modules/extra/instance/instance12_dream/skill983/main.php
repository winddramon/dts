<?php

namespace skill983
{
	function init()
	{
		define('MOD_SKILL983_INFO','card;');
		eval(import_module('clubbase','player','addnpc'));
		global $skill983_npc;
		$clubskillname[983] = '余火';
		$typeinfo[106] = '残像回声';
		$anpcinfo[106] = $skill983_npc;
		$typeinfo[107] = '种火';
		$anpcinfo[107] = $skill983_boss_npc;
	}
	
	function acquire983(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger'));
		$vippid = \addnpc\addnpc(106, 0, 1, $pa['pls']);
		//如果没有队伍则创建一个
		if (empty($pa['teamID']))
		{
			\team\teammake('试炼者', '2333');
		}
		
		$vip = \player\fetch_playerdata_by_pid($vippid[0]);
		$log .= "<span class=\"yellow b\">{$vip['name']}出现在了你的身旁。</span><br>";//待补充台词
		$vip['teamID'] = $pa['teamID'];
		$vip['teamPass'] = $pa['teamPass'];
		\player\player_save($vip);
		\skillbase\skill_setvalue(951,'flag983','1',$pa);
		\skillbase\skill_setvalue(983,'vippid',$vippid[0],$pa);
		\skillbase\skill_setvalue(983,'buff','0',$pa);
	}
	
	function lost983(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(983,'vippid',$pa);
		\skillbase\skill_setvalue(983,'buff',$pa);
	}
	
	function check_unlocked983(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//无法退队
	function teamquit()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_getvalue(951,'flag983'))
		{
			eval(import_module('logger'));
			$log .= "<span class=\"yellow b\">你现在无法退出队伍。</span><br>";//待补充台词
			return;
		}
		$chprocess();
	}
	
	//NPC接收道具
	function senditem()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman'));
		if('back' == $command){
			$mode = 'command';
			return;
		}
		$mateid = str_replace('team','',$action);
		if ($mateid == \skillbase\skill_getvalue(983,'vippid'))
		{
			if (strpos($command,'item') === 0)
			{
				$itmn = substr($command, 4);
				if (!${'itms'.$itmn}) {
					$log .= '此道具不存在！';
					$mode = 'command';
					return;
				}
				$itm = & ${'itm'.$itmn};
				$itmk = & ${'itmk'.$itmn};
				$itme = & ${'itme'.$itmn};
				$itms = & ${'itms'.$itmn};
				$itmsk = & ${'itmsk'.$itmn};
				$edata = \player\fetch_playerdata_by_pid($mateid);
				if (strpos($itmk, 'W') === 0) //给武器
				{
					//$log .= "";//待补充台词
					$log .= "<span class=\"yellow b\">$itm</span>化作七色的光芒，融入了<span class=\"yellow b\">{$edata['name']}</span>的武器中。<br>";
					$edata['wepe'] += min(rand(round(0.2 * $itme), $itme), round(0.5 * $edata['wepe']));
					if ($itms == '∞') $itms = 100;
					$edata['weps'] += min(rand(round(0.2 * $itms), $itms), round(0.5 * $edata['weps']));
					if (rand(0,1))
					{
						$tmpsk = array_intersect(\itemmain\get_itmsk_array($itmsk) ,array('f','k','t','d','r','N','L','n','y','u','e','i','w','p','c','N','H','B','b','Z','h','A','a','^ac1','^wc1','h','v','V'));
						foreach ($tmpsk as $sk)
						{
							if (!\itemmain\check_in_itmsk($sk, $edata['wepsk'])) $edata['wepsk'] .= $sk;
						}
					}
					\player\player_save($edata);
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
				elseif (strpos($itmk, 'D') === 0) //给防具
				{
					//$log .= "";//待补充台词
					$log .= "<span class=\"yellow b\">$itm</span>化作七色的光芒，融入了<span class=\"yellow b\">{$edata['name']}</span>的身躯中。<br>";
					$skillup = min(rand(round(0.2 * $itme), $itme), round(0.5 * $edata['wc']));
					$edata['wc'] += max($skillup, 10);
					if (rand(0,1))
					{
						$tmpsk = array_intersect(\itemmain\get_itmsk_array($itmsk) ,array('f','k','t','d','r','N','L','n','y','u','e','i','w','p','c','N','H','B','b','Z','h','A','a','^ac1','^wc1','h','v','V'));
						foreach ($tmpsk as $sk)
						{
							if (!\itemmain\check_in_itmsk($sk, $edata['artsk'])) $edata['artsk'] .= $sk;
						}
					}
					\player\player_save($edata);
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
				elseif (strpos($itmk, 'A') === 0) //给饰品
				{
					//$log .= "";//待补充台词
					$log .= "<span class=\"yellow b\">$itm</span>化作七色的光芒，融入了<span class=\"yellow b\">{$edata['name']}</span>的身躯中。<br>";
					if (rand(0,1))
					{
						$tmpsk = array_intersect(\itemmain\get_itmsk_array($itmsk) ,array('f','k','t','d','r','N','L','n','y','u','e','i','w','p','c','N','H','B','b','Z','h','A','a','^ac1','^wc1','h','v','V'));
						foreach ($tmpsk as $sk)
						{
							if (!\itemmain\check_in_itmsk($sk, $edata['artsk'])) $edata['artsk'] .= $sk;
						}
					}
					\player\player_save($edata);
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
				elseif (strpos($itmk, 'H') === 0) //给无毒补给
				{
					if (strpos($itmk, 'HM') === 0 || strpos($itmk, 'HT') === 0 || $itms == '∞' || $itme * $itms >= 3000)
					{
						//$log .= "";//待补充台词
						$buffid = rand(18,22);//正面buff
						\skillbase\skill_setvalue(983,'buff',$buffid,$sdata);
					}
					else
					{
						//$log .= "";//待补充台词
					}
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
				elseif (strpos($itmk, 'P') === 0) //给毒补给
				{
					//$log .= "";//待补充台词
					$buffid = rand(13,17);//负面buff
					\skillbase\skill_setvalue(983,'buff',$buffid,$sdata);
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
				elseif (strpos($itmk, 'V') === 0) //给技能书
				{
					//$log .= "";//待补充台词
					if (strpos($itmk, 'S') !== false)
					{
						eval(import_module('clubbase'));
						$sk_kind = (int)$itmsk;
						if ($sk_kind < 1) $sk_kind = 1;
						if (defined('MOD_SKILL'.$sk_kind) && $clubskillname[$sk_kind] != '')
						{
							if (!\skillbase\skill_query($sk_kind, $edata))
							{
								$log .= "<span class=\"yellow b\">{$edata['name']}</span>获得了技能「<span class=\"yellow b\">".$clubskillname[$sk_kind]."</span>」'<br>";
								\skillbase\skill_acquire($sk_kind, $edata);
							}
						}
					}
					else $edata['wc'] += $itme * $itms;
					\player\player_save($edata);
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
				elseif (strpos($itmk, 'M') === 0) //给强化药
				{
					//$log .= "";//待补充台词
					$edata['wc'] += rand(round(0.2 * $itme * $itms), $itme * $itms);
					$edata['att'] += rand(round(0.5 * $itme * $itms), $itme * $itms);
					\player\player_save($edata);
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
				elseif ($itmk == 'X' && strpos($itm, '宝石方块') !== false) //给宝石方块
				{
					if (rand(0,3)== 0)
					{
						//$log .= "";//待补充台词
						$itm = '梦境礼盒';
						$itmk = 'Y';
						$itme = rand(1,6);
						$itms = 1;
						$itmsk = '';
					}
					else
					{
						//$log .= "";//待补充台词
						$hpup = rand(30,100) * $itms;
						$mhp += $hpup;
						$hp += $hpup;
						\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					}
					$mode = 'command';
					return;
				}
				elseif ($itmk == 'X' && strpos($itm, '方块') !== false) //给方块
				{
					//$log .= "";//待补充台词
					$hpup = rand(10,30) * $itms;
					$mhp += $hpup;
					$hp += $hpup;
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
			}
		}
		$chprocess();
	}
	
	//移动时NPC跟着移动
	function move($moveto = 99)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($moveto);
		eval(import_module('player'));
		if (\skillbase\skill_query(983,$sdata))
		{
			$vippid = (int)\skillbase\skill_getvalue(983,'vippid',$sdata);
			if ($vippid > 0)
			{
				$vip = \player\fetch_playerdata_by_pid($vippid);
				if (($vip['hp']>0) && ($vip['pls']!=$pls))
				{
					eval(import_module('map','logger'));
					$log .= "<span class=\"yellow b\">{$vip['name']}跟随你来到了{$plsinfo[$pls]}。</span><br>";
					$vip['pls'] = $pls;
					\player\player_save($vip);
				}
			}
		}
	}
	
	//NPC助攻
	function attack(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(983,$pa) && $pd['type'] != 107)
		{
			if (rand(0,99) < 20)
			{
				$vippid = (int)\skillbase\skill_getvalue(983,'vippid',$pa);
				if ($vippid > 0)
				{
					$vip = \player\fetch_playerdata_by_pid($vippid);
					if (($vip['hp']>0) && ($vip['pls']==$pa['pls']))
					{
						eval(import_module('logger'));
						$log .= "<span class=\"cyan b\">{$vip['name']}的追加攻击！</span><br>";
						$log .= "<span class=\"white b\">“稍微帮你一点小忙吧！”</span><br>";//待补充台词
						//补充攻击方式和必要参数
						$vip['skill983_flag'] = 1;
						$vip['wep_kind'] = $vip['wepk'][1];
						$vip['bskill'] = 0;
						$vip['fin_skill'] = \weapon\get_skill($vip,$pd,$active);
						$vip['fin_hitrate'] = \weapon\get_hitrate($vip,$pd,$active);
						$vip['mult_words_fdmgbs'] = '';
						$vip['physical_dmg_dealt'] = 0;
						$vip['dmg_dealt'] = 0;
						$vip['actual_rapid_time'] = 0;
						$vip['wepimp'] = 0;
						$chprocess($vip,$pd,$active);
						\player\player_save($vip);
						//伤害追加到主玩家伤害
						if ($vip['dmg_dealt'])
						{
							$pa['dmg_dealt'] += $vip['dmg_dealt'];
							$pa['mult_words_fdmgbs'] = \attack\add_format($vip['dmg_dealt'], $pa['mult_words_fdmgbs']);
							$pa['skill983_helped'] = 1;
						}
					}
				}
			}
		}
	}
	
	//击杀显示处理
	function deathnews(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','player'));
		if (\skillbase\skill_query(983,$pa) && isset($pa['skill983_helped']))
		{
			$vippid = (int)\skillbase\skill_getvalue(983,'vippid',$pa);
			$vip = \player\fetch_playerdata_by_pid($vippid);
			$chprocess($vip, $pd);
		}
		else $chprocess($pa, $pd);
	}
	
	//助攻不显示耐久扣减log
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$temp_log = $log;
		$chprocess($pa, $pd, $active);
		if (isset($pa['skill983_flag'])) $log = $temp_log;
	}
	
	//特殊BOSS NPC
	function skill981_add_enemy($stage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (!\skillbase\skill_query(983, $sdata) || $stage > 11) return $chprocess($stage);
		if ($stage < 11)
		{
			if ($stage < 5) $buffid = rand(1,12);
			else $buffid = rand(1,17);
			eval(import_module('logger'));
			$log .= "<span class=\"white b\">“……”</span><br><span class=\"yellow b\">你感觉自己受到了祝福！</span><br>";//待补充台词
			\skillbase\skill_setvalue(983,'buff',$buffid,$sdata);
			return $chprocess($stage);
		}
		else
		{
			eval(import_module('logger'));
			$log .= "<span class=\"white b\">“……”</span><br><span class=\"yellow b\">新的敌人加入了战场！</span><br>";//待补充台词
			\addnpc\addnpc(107, 0, 1, 201);
			return 1;
		}
	}
	
	//物理伤害变化
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(983, $pa))
		{
			$buffid = (int)\skillbase\skill_getvalue(983,'buff',$pa);
			$skill983_dmggain = array(7 => -50, 8 => 100, 16 => -80, 21 => 60);
			if (isset($skill983_dmggain[$buffid]))
			{
				$dmggain = $skill983_dmggain[$buffid];
				eval(import_module('logger'));
				if ($dmggain > 0)
				{
					if ($active) $log .= "<span class=\"yellow b\">「余火」使你造成的物理伤害增加了{$dmggain}%！</span><br>";
					else $log .= " <span class=\"yellow b\">「余火」使{$pd['name']}造成的物理伤害增加了{$dmggain}%！</span><br>";
				}
				else
				{
					$dmgdown = -$dmggain;
					if ($active) $log .= "<span class=\"yellow b\">「余火」使你造成的物理伤害降低了{$dmgdown}%！</span><br>";
					else $log .= " <span class=\"yellow b\">「余火」使{$pd['name']}造成的物理伤害降低了{$dmgdown}%！</span><br>";
				}
				$r = array(1 + $dmggain / 100);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	//属性伤害变化
	function calculate_ex_attack_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(983, $pa))
		{
			$buffid = (int)\skillbase\skill_getvalue(983,'buff',$pa);
			$skill983_dmggain = array(7 => 100, 8 => -50, 17 => -80, 22 => 60);
			if (isset($skill983_dmggain[$buffid]))
			{
				$dmggain = $skill983_dmggain[$buffid];
				eval(import_module('logger'));
				if ($dmggain > 0)
				{
					if ($active) $log .= "<span class=\"yellow b\">「余火」使你造成的属性伤害增加了{$dmggain}%！</span><br>";
					else $log .= " <span class=\"yellow b\">「余火」使{$pd['name']}造成的属性伤害增加了{$dmggain}%！</span><br>";
				}
				else
				{
					$dmgdown = -$dmggain;
					if ($active) $log .= "<span class=\"yellow b\">「余火」使你造成的属性伤害降低了{$dmgdown}%！</span><br>";
					else $log .= " <span class=\"yellow b\">「余火」使{$pd['name']}造成的属性伤害降低了{$dmgdown}%！</span><br>";
				}
				$r = array(1 + $dmggain / 100);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	//射程变化
	function get_weapon_range(&$pa, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $active);
		if (\skillbase\skill_query(983, $pa)) 
		{
			$buffid = (int)\skillbase\skill_getvalue(983,'buff',$pa);
			if ($buffid == 9 || $buffid == 13) $ret = 1;
			elseif ($buffid == 10 || $buffid == 18) $ret = 8;
		}
		return $ret;
	}
	
	//最终伤害变化
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(983, $pa))
		{
			$buffid = (int)\skillbase\skill_getvalue(983,'buff',$pa);
			$skill983_dmggain = array(11 => 50, 12 => -50, 15 => -50, 20 => 50);
			$dmggain = 0;
			if (isset($skill983_dmggain[$buffid])) $dmggain = $skill983_dmggain[$buffid];
			elseif (($buffid == 1 && $pa['wep_kind'] == 'F') || ($buffid == 2 && $pa['wep_kind'] == 'P') || ($buffid == 3 && $pa['wep_kind'] == 'D') || ($buffid == 4 && $pa['wep_kind'] == 'K') || ($buffid == 5 && $pa['wep_kind'] == 'G') || ($buffid == 6 && $pa['wep_kind'] == 'C')) $dmggain = -50;
			elseif (($buffid == 1 && $pa['wep_kind'] == 'P') || ($buffid == 2 && $pa['wep_kind'] == 'D') || ($buffid == 3 && $pa['wep_kind'] == 'K') || ($buffid == 4 && $pa['wep_kind'] == 'G') || ($buffid == 5 && $pa['wep_kind'] == 'C') || ($buffid == 6 && $pa['wep_kind'] == 'F')) $dmggain = 100;
			eval(import_module('logger'));
			if ($dmggain > 0)
			{
				if ($active) $log .= "<span class=\"yellow b\">「余火」使你造成的伤害增加了{$dmggain}%！</span><br>";
				else $log .= " <span class=\"yellow b\">「余火」使{$pa['name']}造成的伤害增加了{$dmggain}%！</span><br>";
				$r[] = 1 + $dmggain / 100;
			}
			elseif ($dmggain < 0)
			{
				$dmgdown = -$dmggain;
				if ($active) $log .= "<span class=\"yellow b\">「余火」使你造成的伤害降低了{$dmgdown}%！</span><br>";
				else $log .= " <span class=\"yellow b\">「余火」使{$pa['name']}造成的伤害降低了{$dmgdown}%！</span><br>";
				$r[] = 1 + $dmggain / 100;
			}
		}
		if (\skillbase\skill_query(983, $pd))
		{
			$buffid = (int)\skillbase\skill_getvalue(983,'buff',$pa);
			$skill983_dmggain = array(11 => 50, 12 => -50, 14 => 50, 19 => -50);
			$dmggain = 0;
			if (isset($skill983_dmggain[$buffid])) $dmggain = $skill983_dmggain[$buffid];
			eval(import_module('logger'));
			if ($dmggain > 0)
			{
				if ($active) $log .= "<span class=\"yellow b\">「余火」使{$pd['name']}受到的伤害增加了{$dmggain}%！</span><br>";
				else $log .= " <span class=\"yellow b\">「余火」使你受到的伤害增加了{$dmggain}%！</span><br>";
				$r[] = 1 + $dmggain / 100;
			}
			elseif ($dmggain < 0)
			{
				$dmgdown = -$dmggain;
				if ($active) $log .= "<span class=\"yellow b\">「余火」使{$pd['name']}受到的伤害降低了{$dmgdown}%！</span><br>";
				else $log .= " <span class=\"yellow b\">「余火」使你受到的伤害降低了{$dmgdown}%！</span><br>";
				$r[] = 1 + $dmggain / 100;
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	//技能描述文字，待完成
	function skill983_bufftext(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$s = "你未受到任何来自种火的增益。";
		$buffid = (int)\skillbase\skill_getvalue(983,'buff',$pa);
		$bufftext = array(
			//正面+负面效果
			1 => "造成的灵系伤害<span class=\"yellow b\">-50%</span>，殴系伤害<span class=\"yellow b\">+100%</span>",
			2 => "造成的殴系伤害<span class=\"yellow b\">-50%</span>，爆系伤害<span class=\"yellow b\">+100%</span>",
			3 => "造成的爆系伤害<span class=\"yellow b\">-50%</span>，斩系伤害<span class=\"yellow b\">+100%</span>",
			4 => "造成的斩系伤害<span class=\"yellow b\">-50%</span>，射系伤害<span class=\"yellow b\">+100%</span>",
			5 => "造成的射系伤害<span class=\"yellow b\">-50%</span>，投系伤害<span class=\"yellow b\">+100%</span>",
			6 => "造成的投系伤害<span class=\"yellow b\">-50%</span>，灵系伤害<span class=\"yellow b\">+100%</span>",
			7 => "造成的物理伤害<span class=\"yellow b\">-50%</span>，属性伤害<span class=\"yellow b\">+100%</span>",
			8 => "造成的属性伤害<span class=\"yellow b\">-50%</span>，物理伤害<span class=\"yellow b\">+100%</span>",
			9 => "射程变为<span class=\"yellow b\">1</span>（等同于灵系），造成伤害<span class=\"yellow b\">+100%</span>",
			10 => "射程变为<span class=\"yellow b\">8</span>（等同于重型枪械），造成伤害<span class=\"yellow b\">-50%</span>",
			11 => "受到伤害<span class=\"yellow b\">+50%</span>，造成伤害<span class=\"yellow b\">+50%</span>",
			12 => "造成伤害<span class=\"yellow b\">-50%</span>，受到伤害<span class=\"yellow b\">-50%</span>",
			//负面效果
			13 => "射程变为<span class=\"yellow b\">1</span>（等同于灵系）",
			14 => "受到伤害<span class=\"yellow b\">+50%</span>",
			15 => "造成伤害<span class=\"yellow b\">-50%</span>",
			16 => "造成的物理伤害<span class=\"yellow b\">-80%</span>",
			17 => "造成的属性伤害<span class=\"yellow b\">-80%</span>",
			//正面效果
			18 => "射程变为<span class=\"yellow b\">8</span>（等同于重型枪械）",
			19 => "受到伤害<span class=\"yellow b\">-50%</span>",
			20 => "造成伤害<span class=\"yellow b\">+50%</span>",
			21 => "造成的物理伤害<span class=\"yellow b\">+60%</span>",
			22 => "造成的属性伤害<span class=\"yellow b\">+60%</span>",
		);
		if (isset($bufftext[$buffid])) $s = $bufftext[$buffid];
		return $s;
	}
	
}

?>