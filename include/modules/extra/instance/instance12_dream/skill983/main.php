<?php

namespace skill983
{
	function init()
	{
		define('MOD_SKILL983_INFO','card;');
		eval(import_module('clubbase','player','addnpc'));
		global $skill983_npc, $skill983_boss_npc;
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
			$pa['sp'] += 200;
			\team\teammake('试炼者', '2333');
		}
		
		$vip = \player\fetch_playerdata_by_pid($vippid[0]);
		
		$stage = (int)\skillbase\skill_getvalue(981,'stage',$pa);
		if ($stage < 11)
		{
			$log .= "你的眼前出现了一位大约十一二岁的少女，<br>
				她浑身散发着不可思议的气息，但似乎并没有什么恶意。<br>
				少女开口说话，虽然你听到的都是奇怪的韵律，<br>
				但你还是可以理解韵律中的话语。<br>
				<br>
				<span class=\"white b\">“我是繁花的种火，可以叫我种火花。<br>
				如果你同意让我旁观你在这里的战斗，<br>
				那到你醒来为止，我将和你并肩作战。<br>
				……此外，从本机体分配的角色的角度来说，<br>
				如果你能将数据喂食给我，我也可以给你点好处。<br>
				<br>
				…………问我什么是数据？<br>
				你身上的东西，地上的东西，其他实体身上的东西，都是数据。<br>
				反正小女子照单全收啦~”</span><br>";
		}
		else
		{
			$log .= "你的眼前出现了一位大约十一二岁的少女，<br>
			她浑身散发着不可思议的气息，但似乎并没有什么恶意。<br>
			当然因为理所应当的原因，你是能听懂她的话语的。<br>
			<br>
			<span class=\"white b\">“时机太奇怪啦！但规则就是规则，角色就是角色。<br>
			所以自报家门还是要进行的。<br>
			本机体名繁花的种火，简称种火花。<br>
			角色是对数据的吸收和分析，包括你的。<br>
			所以不要顾虑，来喂我数据吧~”</span><br>";
		}	
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
			$log .= "<span class=\"yellow b\">你现在无法退出队伍。</span><br>";
			return;
		}
		$chprocess();
	}
	
	function findteam(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(983) && $edata['pid'] == \skillbase\skill_getvalue(983,'vippid'))
		{
			eval(import_module('logger'));
			$met_text = array_randompick(array("“如果有什么用不着的数据就丢给我吧，我会善用的。”","“为什么要收集数据？之前也说了这是分配给本机的角色。”","“没事做就来摸摸我吧！<br>你也没少摸过纸片人吧……虽然我不是呢。”","“我的好感度是可以刷的哦~<br>这也是本机的角色的一部分。<br>虽然我自己都不知道怎么刷以及刷高了会怎么样就是了。”","“在其他地方看见了和我长得一样的实体？<br>日有所思，夜有所梦，这也是很正常的吧。”"));
			$log .= "<span class=\"white b\">$met_text</span><br><br>";
		}
		$chprocess($edata);
	}
	
	//NPC接收道具
	function senditem()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman'));
		if(!\skillbase\skill_query(983,$sdata)){
			$chprocess();
			return;
		}
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
					$log .= "<span class=\"white b\">“获得攻击面升级了，非常感谢！<br>每天都以最新的版本面对挑战可谓是基本呢。”</span><br><br>";
					$log .= "<span class=\"yellow b\">$itm</span>化作七色的光芒，融入了<span class=\"yellow b\">{$edata['name']}</span>的武器中。<br>";
					$edata['wepe'] += min(rand(round(0.05 * $itme), round(0.3 * $itme)), round(0.15 * $edata['wepe']));
					if ($itms == '∞') $itms = 100;
					$edata['weps'] += min(rand(round(0.05 * $itms), round(0.3 * $itme)), round(0.15 * $edata['weps']));
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
					$log .= "<span class=\"white b\">“获得防御面升级了，非常感谢！<br>每天都以最新的版本面对伤痛也算是基本吧。”</span><br><br>";
					$log .= "<span class=\"yellow b\">$itm</span>化作七色的光芒，融入了<span class=\"yellow b\">{$edata['name']}</span>的身躯中。<br>";
					$skillup = min(rand(round(0.05 * $itme), round(0.3 * $itme)), round(0.2 * $edata['wc']));
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
					$log .= "<span class=\"white b\">“获得攻击面升级了，非常感谢！<br>每天都以最新的版本面对挑战也算是基本吧。”</span><br><br>";
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
						$log .= "<span class=\"white b\">“已确定个体稳定性提升，非常感谢！<br>按照说好的那样，为你分享一些我的「性质」。”</span><br><br>";
						$log .= "<span class=\"yellow b\">你感觉自己受到了祝福！</span><br>";
						$buffid = rand(19,23);//正面buff
						\skillbase\skill_setvalue(983,'buff',$buffid,$sdata);
						$log .= "当前「余火」状态为：<span class=\"yellow b\">".skill983_bufftext($sdata)."</span><br>";
					}
					else
					{
						$log .= "<span class=\"white b\">“已确定个体稳定性提升，未确认到特别的质变。<br>因为没有特别的质变，也没办法给你什么特殊的东西，抱歉。”</span><br><br>";
						$log .= "<span class=\"yellow b\">什么也没有发生。</span><br>";
					}
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
				elseif (strpos($itmk, 'P') === 0) //给毒补给
				{
					$log .= "<span class=\"white b\">“让我解析一下……<br>唔，这算图灵测试吗？我可是对此特别自豪呢。<br>既然对我恶作剧，那我也相应地恶搞你一下吧~”</span><br><br>";
					$log .= "<span class=\"yellow b\">你感觉自己变得虚弱了……</span><br>";
					$buffid = rand(13,18);//负面buff
					\skillbase\skill_setvalue(983,'buff',$buffid,$sdata);
					$log .= "当前「余火」状态为：<span class=\"yellow b\">".skill983_bufftext($sdata)."</span><br>";
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
				elseif (strpos($itmk, 'V') === 0) //给技能书
				{
					$log .= "<span class=\"white b\">“获得更新数据了，让我解析一下。<br>没关系，不会给你藏着的，马上就用到我们的敌手身上。”</span><br><br>";
					if (strpos($itmk, 'S') !== false)
					{
						eval(import_module('clubbase'));
						$sk_kind = (int)$itmsk;
						if ($sk_kind < 1) $sk_kind = 1;
						if (defined('MOD_SKILL'.$sk_kind) && $clubskillname[$sk_kind] != '')
						{
							if (!\skillbase\skill_query($sk_kind, $edata))
							{
								$log .= "<span class=\"yellow b\">{$edata['name']}</span>获得了技能「<span class=\"yellow b\">".$clubskillname[$sk_kind]."</span>」！<br>";
								\skillbase\skill_acquire($sk_kind, $edata);
							}
						}
					}
					else
					{
						$log .= "<span class=\"yellow b\">$itm</span>化作七色的光芒，融入了<span class=\"yellow b\">{$edata['name']}</span>的身躯中。<br>";
						$edata['wc'] += $itme * $itms;
					}
					\player\player_save($edata);
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
				elseif (strpos($itmk, 'M') === 0) //给强化药
				{
					$log .= "<span class=\"white b\">“获得升级数据了，让我应用一下。<br>虽然从我的角度说这件事会比较怪，<br>但是请不要随便喂食可爱的野生AI哟——尤其是我之外的。”</span><br><br>";
					$log .= "<span class=\"yellow b\">$itm</span>化作七色的光芒，融入了<span class=\"yellow b\">{$edata['name']}</span>的身躯中。<br>";
					$edata['wc'] += rand(round(0.2 * $itme * $itms), $itme * $itms);
					$edata['att'] += rand(round(0.5 * $itme * $itms), $itme * $itms);
					\player\player_save($edata);
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
				elseif ($itmk == 'X' && strpos($itm, '宝石方块') !== false) //给宝石方块
				{
					if (rand(0,3) == 0)
					{
						$log .= "<span class=\"white b\">“啊……这个是……？<br>作为送我有趣的数据的回礼，我送你一个对你来说更有趣的东西吧~”</span><br><br>";
						$log .= "你获得了<span class=\"yellow b\">梦境礼盒</span>。<br>";
						$itm = '梦境礼盒';
						$itmk = 'Y';
						$itme = rand(1,6);
						$itms = 1;
						$itmsk = '';
					}
					else
					{
						$log .= "<span class=\"white b\">“获得额外数据了，让我为你解析一下，<br>这样就输入完毕。你感觉变强了么？”<br></span>种火花伸出手摸了摸你的头，你感觉变强了。<br><span class=\"white b\">“……什么？想要摸头以外的方式？人类还真是复杂呢。”</span><br><br>";
						$hpup = rand(30,100) * $itms;
						$mhp += $hpup;
						$hp += $hpup;
						$log .= "你的生命上限提高了<span class=\"yellow b\">$hpup</span>点！<br>";
						\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					}
					$mode = 'command';
					return;
				}
				elseif ($itmk == 'X' && strpos($itm, '方块') !== false) //给方块
				{
					$log .= "<span class=\"white b\">“获得额外数据了，让我为你解析一下，<br>这样就输入完毕。你感觉变强了么？”<br></span>种火花伸出手摸了摸你的头，你感觉变强了。<br><span class=\"white b\">“……什么？想要摸头以外的方式？人类还真是复杂呢。”</span><br><br>";
					$hpup = rand(10,30) * $itms;
					$mhp += $hpup;
					$hp += $hpup;
					$log .= "你的生命上限提高了<span class=\"yellow b\">$hpup</span>点！<br>";
					\itemmain\item_destroy_core('itm'.$itmn, $sdata);
					$mode = 'command';
					return;
				}
				else
				{
					$log .= "<span class=\"white b\">“这个不知道该怎么使用呢……”</span><br><br>";
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
						$att_text = array_randompick(array("“你想的没错，梦里面真的啥都有！”","“选择模式……输出数据攻击。”","“那就继续陪你玩玩！”"));
						$log .= "<span class=\"white b\">$att_text</span><br>";
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
		eval(import_module('logger'));
		if ($stage < 11)
		{
			if ($stage < 7) $buffid = rand(1,12);
			else $buffid = rand(1,18);
			$log .= "<span class=\"white b\">“获得更新数据了，非常感谢！<br>按照说好的那样，为你分享一些我的「性质」。”</span><br><br>";
			if ($buffid <= 12) $log .= "<span class=\"yellow b\">你感觉自己受到了祝福！</span><br>";
			else $log .= "<span class=\"yellow b\">你感觉自己受到了祝福……对、对吗？</span><br><span class=\"lime b\">试着送她一些补给品类型的道具吧……</span><br>";
			\skillbase\skill_setvalue(983,'buff',$buffid,$sdata);
			$log .= "当前「余火」状态为：<span class=\"yellow b\">".skill983_bufftext($sdata)."</span><br>";
			return $chprocess($stage);
		}
		else
		{
			$log .= "<span class=\"white b\">“和你说一句哦，场上现在有一个比我可爱强大300倍以上的实体出现了。<br>虽然和我是同种，但她大概没那么好心。<br>不过也别太担心，毕竟这只是一场梦而已对吧？”</span><br>";
			\addnpc\addnpc(107, 0, 1, 201);
			return 1;
		}
	}
	
	function skill983_get_dmg_multiplier(&$pa, $t=1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$dmggain = 0;
		$buffid = (int)\skillbase\skill_getvalue(983,'buff',$pa);
		if ($t == 1)//物理伤害系数
		{
			$skill983_dmggain = array(7 => -50, 8 => 100, 16 => -80, 22 => 60);
			if (isset($skill983_dmggain[$buffid])) $dmggain = $skill983_dmggain[$buffid];
		} 
		elseif ($t == 2)//属性伤害系数
		{
			$skill983_dmggain = array(7 => 100, 8 => -50, 17 => -80, 23 => 60);
			if (isset($skill983_dmggain[$buffid])) $dmggain = $skill983_dmggain[$buffid];
		}
		elseif ($t == 3)//最终伤害系数
		{
			$skill983_dmggain = array(11 => 50, 12 => -50, 15 => -50, 21 => 50);
			if (isset($skill983_dmggain[$buffid])) $dmggain = $skill983_dmggain[$buffid];
			elseif (($buffid == 1 && $pa['wep_kind'] == 'F') || ($buffid == 2 && $pa['wep_kind'] == 'P') || ($buffid == 3 && $pa['wep_kind'] == 'D') || ($buffid == 4 && $pa['wep_kind'] == 'K') || ($buffid == 5 && $pa['wep_kind'] == 'G') || ($buffid == 6 && $pa['wep_kind'] == 'C')) $dmggain = -50;
			elseif (($buffid == 1 && $pa['wep_kind'] == 'P') || ($buffid == 2 && $pa['wep_kind'] == 'D') || ($buffid == 3 && $pa['wep_kind'] == 'K') || ($buffid == 4 && $pa['wep_kind'] == 'G') || ($buffid == 5 && $pa['wep_kind'] == 'C') || ($buffid == 6 && $pa['wep_kind'] == 'F')) $dmggain = 100;
		}
		elseif ($t == 4)//受最终伤害系数
		{
			$skill983_dmggain = array(11 => 50, 12 => -50, 14 => 50, 20 => -50);
			if (isset($skill983_dmggain[$buffid])) $dmggain = $skill983_dmggain[$buffid];
		}
		return $dmggain;
	}
	
	//物理伤害变化
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(983, $pa))
		{
			$dmggain = skill983_get_dmg_multiplier($pa, 1);
			if ($dmggain != 0)
			{
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
			$dmggain = skill983_get_dmg_multiplier($pa, 2);
			if ($dmggain != 0)
			{
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
			elseif ($buffid == 10 || $buffid == 19) $ret = 8;
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
			$dmggain = skill983_get_dmg_multiplier($pa, 3);
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
			$dmggain = skill983_get_dmg_multiplier($pd, 4);
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
	
	function apply_total_damage_modifier_limit(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(983, $pa) && (int)\skillbase\skill_getvalue(983,'buff',$pa) == 18)
		{
			if ($pa['dmg_dealt'] > 1997)
			{
				eval(import_module('logger'));
				$pa['dmg_dealt'] = 1997;
				if ($active) $log .= "<span class=\"yellow b\">「余火」使{$pd['name']}受到的伤害被限制到了1997点！</span><br>";
				else $log .= " <span class=\"yellow b\">「余火」使你受到的伤害被限制到了1997点！</span><br>";
			}
		}
	}
	
	//技能描述文字
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
			18 => "最终伤害限制为<span class=\"yellow b\">1997</span>",
			//正面效果
			19 => "射程变为<span class=\"yellow b\">8</span>（等同于重型枪械）",
			20 => "受到伤害<span class=\"yellow b\">-50%</span>",
			21 => "造成伤害<span class=\"yellow b\">+50%</span>",
			22 => "造成的物理伤害<span class=\"yellow b\">+60%</span>",
			23 => "造成的属性伤害<span class=\"yellow b\">+60%</span>",
		);
		if (isset($bufftext[$buffid])) $s = $bufftext[$buffid];
		return $s;
	}
	
}

?>