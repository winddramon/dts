<?php

namespace randnpc
{
	function init()
	{
		eval(import_module('player'));
		$typeinfo[51] = '杂鱼';
		$typeinfo[52] = '妖幻碎片';//实际会用到的只有52-57
		$typeinfo[53] = '妖幻碎片i';
		$typeinfo[54] = '妖幻片段';
		$typeinfo[55] = '妖幻片段i';
		$typeinfo[56] = '妖幻倩影';
		$typeinfo[57] = '妖幻倩影i';
		$typeinfo[58] = '实验体C型';
		$typeinfo[59] = '实验体B型';
		$typeinfo[60] = '实验体A型';
		$typeinfo[62] = '代码聚合体';
		$typeinfo[63] = '数据碎片';
		$typeinfo[64] = '红杀将军';
		$typeinfo[65] = '幻境卫队';
		$typeinfo[66] = '幻境守卫';
		$typeinfo[67] = '武神？';
	}
	
	//生成若干个标准格式的随机NPC
	//rank：NPC的强度等级，为1-20
	//num：生成数量
	//offens_tend：攻击倾向（0-100整数），越高NPC越容易有高攻击力和熟练度，越容易出现强袭姿态和重视反击
	//defens_tend：防御倾向（0-100整数），越高NPC越容易有高生命值和防御力，越容易出现作战姿态和重视防御
	//variety：变化范围（0-50整数），越高则随机属性的变化范围越大
	//use_preset：是否基于预设生成，若为数组，表示基于数组中元素的npc类别生成，若为1，则基于本模块中的预设生成，若为0，则不按预设生成
	function generate_randnpc($rank, $num=1, $offens_tend=0, $defens_tend=0, $variety=0, $use_preset=1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$randnpcs = array();
		for ($i=0; $i<$num; $i++)
		{
			$randnpcs[] = generate_single_randnpc($rank, $offens_tend, $defens_tend, $variety, $use_preset);
		}
		return $randnpcs;
	}
	
	function generate_single_randnpc($rank, $offens_tend=0, $defens_tend=0, $variety=0, $use_preset=1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//生成npc基础属性
		eval(import_module('npc'));
		if ($use_preset == 1)
		{
			eval(import_module('randnpc'));
			$npc = array_merge($npcinit, array_randompick($randnpc_presets[$rank]));
		}
		elseif (is_array($use_preset))
		{
			$ntype = array_randompick($use_preset);
			$ninfo = $npcinfo;//这里是标准局的NPC列表
			if (!isset($ninfo[$ntype])) $ntype = 90;
			$npcs = $ninfo[$ntype];
			$tmp_sub = $npcs['sub'];
			unset($npcs['sub']);
			$npc = array_merge($npcinit, $npcs);
			if(!empty($tmp_sub) && is_array($tmp_sub))
			{
				$npc = array_merge($npc, array_randompick($tmp_sub));
			}
			$npc['type'] = $ntype;
			$npc['pls'] = 99;
			$dice = rand(0,10);
			if ($dice < 4) $npc['wepsk'] = generate_randnpc_itmsk($rank, $npc['wepk'], $npc['wepsk']);
			elseif ($dice < 5) $npc['arbsk'] = generate_randnpc_itmsk($rank, 'DB', $npc['arbsk']);
			elseif ($dice < 6) $npc['arhsk'] = generate_randnpc_itmsk($rank, 'DH', $npc['arhsk']);
			elseif ($dice < 7) $npc['arfsk'] = generate_randnpc_itmsk($rank, 'DF', $npc['arfsk']);
			elseif ($dice < 8) $npc['arask'] = generate_randnpc_itmsk($rank, 'DA', $npc['arask']);
			else $npc['artsk'] = generate_randnpc_itmsk($rank, 'A', $npc['artsk']);
		}
		else
		{
			$npc = $npcinit;
			//生成名字和头像，待修改
			if ($rank < 3) $npc['name'] = $rank.'级虚像';
			elseif ($rank < 7)
			{
				$dice = rand(0,3);
				if ($dice == 0) $npc['name'] = '攻击型人形';
				elseif ($dice == 1) $npc['name'] = '防御型人形';
				elseif ($dice == 2) $npc['name'] = '攻击型魔像';
				else $npc['name'] = '防御型魔像';
				$npc['icon'] = 420 + $dice;
				if ($rank > 4) $npc['name'] .= ' LV2';
			}
			elseif ($rank < 11)
			{
				$dice = rand(0,5);
				if ($dice == 0) $npc['name'] = '草妖精 米迦';
				elseif ($dice == 1) $npc['name'] = '水妖精 苏';
				elseif ($dice == 2) $npc['name'] = '太阳妖精 李';
				elseif ($dice == 3) $npc['name'] = '月亮妖精 雅斯特';
				elseif ($dice == 4) $npc['name'] = '火龙 锭蓝';
				else $npc['name'] = '水龙 玫红';
				$npc['icon'] = 424 + $dice;
			}
			else
			{
				$dice = rand(0,7);
				if ($dice == 0) $npc['name'] = '影9643';//为什么是这些个数字？
				elseif ($dice == 1) $npc['name'] = '影9580';
				elseif ($dice == 2) $npc['name'] = '影9545';
				elseif ($dice == 3) $npc['name'] = '影9501';
				elseif ($dice == 4) $npc['name'] = '影9502';
				elseif ($dice == 5) $npc['name'] = '影9496';
				elseif ($dice == 6) $npc['name'] = '影9450';
				else $npc['name'] = '影9543';
				if ($dice < 4) $npc['icon'] = 430 + $dice;
				else $npc['icon'] = 426 + $dice;
			}
			if (rand(0,1)) $npc['gd'] = 'f';
			$var1 = pow(1.35, $rank);
			$var2 = pow(1.25, $rank);
			$npc['mhp'] = $var1 * 320;
			$npc['msp'] = $rank * 100;
			$npc['att'] = round($var2 * 50);
			$npc['def'] = round($var2 * 20);
			$npc['skill'] = round($var2 * 25);
			$npc['lvl'] = $rank * 5;
			$npc['money'] = array(140,180,240,260,280,300,320,340,360,380,400,420,440,460,480,500,520,540,560,580)[$rank-1];
			//武器
			if ($rank > 12) $npc['wepk'] = array_randompick(array('WP','WK','WC','WG','WF','WD','WB','WP','WK','WC','WG','WF','WD','WB','WJ'));
			elseif ($rank > 8) $npc['wepk'] = array_randompick(array('WP','WK','WC','WG','WF','WD','WB'));
			else $npc['wepk'] = array_randompick(array('WP','WK','WC','WG','WF','WD'));
			list($npc['wep'], $npc['wepsk']) = generate_randnpc_item($npc['wepk'], 1);
			$npc['wepe'] = max($rank*10, $var1) * 5; $npc['weps'] = $rank * 50;
			if ($npc['wepk'] == 'WD') $npc['wepe'] = $npc['wepe'] * 1.2;
			elseif ($npc['wepk'] != 'WF') $npc['wepe'] = $npc['wepe'] * 1.5;
			//防具
			$npc['arbk'] = 'DB'; $npc['arhk'] = 'DH'; $npc['arfk'] = 'DF'; $npc['arak'] = 'DA';
			list($npc['arb'], $npc['arbsk']) = generate_randnpc_item('DB', 1);
			list($npc['arh'], $npc['arhsk']) = generate_randnpc_item('DH', 1);
			list($npc['arf'], $npc['arfsk']) = generate_randnpc_item('DF', 1);
			list($npc['ara'], $npc['arask']) = generate_randnpc_item('DA', 1);
			$npc['arbe'] = round($var2 * 40);
			$npc['arhe'] = $npc['arfe'] = $npc['arae'] = round($var2 * 30);
			$npc['arbs'] = $npc['arhs'] = $npc['arfs'] = $npc['aras'] = $rank * 24;
			//饰品
			list($npc['art'], $npc['artsk']) = generate_randnpc_item('A', 1);
			$npc['artk'] = 'A'; $npc['arte'] = 1; $npc['arts'] = 1;
			
			//非预设的属性调整
			$npc['club'] = array_randompick(array(1,2,3,4,5,6,7,8,9,10,11,13,14,17,18,19,20,21,24));
			$npc['pose'] = array_randompick(array(0,1,4));
			if (rand(0,99) < $offens_tend + 3*$rank) $npc['pose'] = 2;
			$npc['tactic'] = array_randompick(array(0,2,3,4));
			if (rand(0,99) < $defens_tend) $npc['tactic'] = 2;
			$npc['pls'] = 99;
			$npc['skill'] = round($npc['skill'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
			$npc['lvl'] = max($npc['lvl'] + rand(-5,5), 1);
			$npc['money'] = round($npc['money'] * rand(70-$variety,130+$variety) / 100);
			//装备调整
			if ($npc['club']==19) //铁拳
			{
				$npc['att'] += 2 * round($npc['wepe'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
				$npc['wep'] = '拳头';
				$npc['wepk'] = 'WN';
				$npc['wepe'] = 0; $npc['weps'] = '∞';
				$npc['wepsk'] = '';
			}
			else
			{
				$npc['wepe'] = round($npc['wepe'] * rand(40-$variety+$offens_tend,160+$variety+$offens_tend) / 100);
				$npc['weps'] = round($npc['weps'] * rand(40-$variety+$offens_tend,160+$variety+$offens_tend) / 100);
				//生成武器属性
				$npc['wepsk'] = generate_randnpc_itmsk($rank, $npc['wepk'], $npc['wepsk']);
			}
			//生成防具和饰品属性
			$npc['arbsk'] = generate_randnpc_itmsk($rank, 'DB', $npc['arbsk']);
			$npc['arhsk'] = generate_randnpc_itmsk($rank, 'DH', $npc['arhsk']);
			$npc['arfsk'] = generate_randnpc_itmsk($rank, 'DF', $npc['arfsk']);
			$npc['arask'] = generate_randnpc_itmsk($rank, 'DA', $npc['arask']);
			$npc['artsk'] = generate_randnpc_itmsk($rank, 'A', $npc['artsk']);
		}
		//通用属性调整
		$npc['mhp'] = round($npc['mhp'] * rand(80-$variety+$defens_tend,120+$variety+$defens_tend) / 100);
		$npc['arbe'] = round($npc['arbe'] * rand(70-$variety+$defens_tend,130+$variety+$defens_tend) / 100);
		$npc['arhe'] = $npc['arfe'] = $npc['arae'] = round($npc['arhe'] * rand(70-$variety+$defens_tend,130+$variety+$defens_tend) / 100);
		$npc['arbs'] = $npc['arhs'] = $npc['arfs'] = $npc['aras'] = round($npc['arbs'] * rand(70-$variety+$defens_tend,130+$variety+$defens_tend) / 100);
		$bonus = rand(100,200);
		$bonus_pos = array_randompick(array('arb','arh','arf','ara'));
		$npc[$bonus_pos.'e'] = round($npc[$bonus_pos.'e'] * $bonus / 100);
		
		//添加技能
		if (!isset($npc['skills'])) $npc['skills'] = array();
		$npc['skills'] = generate_randnpc_skills($rank, $npc['skills']);
		
		//不是BOSS概率出特殊奖励道具
		if ($use_preset) return $npc;
		$dice = rand(0,99);
		if ($dice == 0)
		{
			$pos = array_randompick(array('arb','arh','arf','ara','art'));
			$npc[$pos.'sk'] .= '^st1^vol'.rand(1,4);
			$npc[$pos] = '空间之'.$npc[$pos];
		}
		elseif ($dice == 1)
		{
			$skillid = array_rand($npc['skills'], 1);
			if ($npc['skills'][$skillid] == 0)
			{
				$pos = array_randompick(array('arb','arh','arf','ara','art'));
				$npc[$pos.'sk'] .= '^eqpsk'.$skillid;
				$npc[$pos] = '秘传之'.$npc[$pos];
			}
		}
		elseif ($dice == 2)
		{
			$pos = array_randompick(array('arb','arh','arf','ara'));
			$npc[$pos.'k'] .= 'S';
			$npc[$pos] = '战甲之'.$npc[$pos];
		}
		elseif ($dice == 3)
		{
			$dice2 = rand(0,3);
			if ($dice2 == 0)
			{
				$npc['itm1'] = '【神经强化剂】';
				$npc['itmk1'] = 'ME';
			}
			elseif ($dice2 == 1)
			{
				$npc['itm1'] = '【超级战士药剂】';
				$npc['itmk1'] = 'MV';
			}
			elseif ($dice2 == 2)
			{
				$npc['itm1'] = '【肉体强化剂】';
				$npc['itmk1'] = 'MH';
			}
			else
			{
				$npc['itm1'] = '【线粒体强化剂】';
				$npc['itmk1'] = 'MS';
			}
			$npc['itme1'] = 20;
			$npc['itms1'] = rand(1,3);
			$npc['itmsk1'] = '';
		}
		elseif ($dice < 7)
		{
			$npc['itm1'] = array_randompick(array('魔法布丁','魔法灵药','能量饮料'));
			$npc['itmk1'] = array_randompick(array('HB','HH','HS','PB'));
			$npc['itme1'] = rand(30,150);
			$npc['itms1'] = rand(10,30);
			$npc['itmsk1'] = '';
		}
		elseif ($dice < 10)
		{
			$npc['itm1'] = '紧急药剂';
			$npc['itmk1'] = 'Ca';
			$npc['itme1'] = 1;
			$npc['itms1'] = rand(1,5);
			$npc['itmsk1'] = '';
		}
		
		return $npc;
	}
	
	//生成随机道具，$randname为1则仅生成道具名但不生成属性，$randname为0则从随机道具池中挑选道具并带有相应的属性
	function generate_randnpc_item($itmk, $randname=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('randnpc'));
		$itm = '';
		$itmsk = '';
		if ($randname)
		{
			if (isset($randnpc_item_randname[$itmk])) $itm = array_randompick($randnpc_item_randname['prefix']).array_randompick($randnpc_item_randname[$itmk]);
		}
		else
		{
			if (isset($randnpc_item[$itmk]))
			{
				$r = array_randompick($randnpc_item[$itmk]);
				if (isset($r[0])) $itm = $r[0];
				if (isset($r[1])) $itmsk = $r[1];
			}
		}
		return [$itm, $itmsk];
	}
	
	//生成随机道具的属性
	function generate_randnpc_itmsk($rank, $itmk, $itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!empty($itmsk)) $itmsk_arr = \itemmain\get_itmsk_array($itmsk);
		else $itmsk_arr = array();
		if ($itmk[0] == 'W')
		{
			$min_itmsk_count = array(0, 0, 0, 0, 1, 1, 1, 1, 2, 2, 2, 2, 3, 3, 3, 3, 4, 5, 6, 8)[$rank-1];
			$max_itmsk_count = array(1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 7, 8, 10)[$rank-1];
		}
		else
		{
			$min_itmsk_count = array(0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 2, 2, 2, 3, 3, 3, 4)[$rank-1];
			$max_itmsk_count = array(1, 1, 1, 2, 2, 2, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 5, 6)[$rank-1];
		}
		eval(import_module('randnpc'));
		$r = min(ceil($rank / 5), 3);
		
		$skpool = $randnpc_itmsk[$itmk[0]][$r];
		if ($r > 1) $skpool = array_merge($skpool, $randnpc_itmsk[$itmk[0]][$r-1]);
		
		$guarant_rate = 3;
		if (rand(0,99) < $guarant_rate) $itmsk_arr[] = array_randompick($randnpc_itmsk[$itmk[0]][$r+1]);
		$sk_count = rand($min_itmsk_count, $max_itmsk_count);
		if ($sk_count > 1) $itmsk_arr = array_merge($itmsk_arr, array_randompick($skpool, $sk_count));
		elseif ($sk_count == 1) $itmsk_arr[] = array_randompick($skpool);
		$itmsk_arr = array_unique($itmsk_arr);
		//灵系和重枪特判
		if (($itmk == 'WF' || $itmk == 'WJ') && $rank < 17) $itmsk_arr = array_diff($itmsk_arr, array('r'));
		$itmsk = implode('', $itmsk_arr);
		return $itmsk;
	}
	
	//生成随机技能
	function generate_randnpc_skills($rank, $skills)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('randnpc'));
		$min_skills_count = array(0, 0, 0, 0, 1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4)[$rank-1];
		$max_skills_count = array(1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 6, 6, 7, 8)[$rank-1];
		
		$skills_count = rand($min_skills_count, $max_skills_count);
		$r = max(ceil(($rank - 1) / 3) - 1, 1);
		$k = array_rand($randnpc_skills[$r]);
		$skills[$k] = $randnpc_skills[$r][$k];
		
		for ($i=0;$i<$skills_count;$i++)
		{
			$skr = rand(max($r-3, 1), $r);
			$k = array_rand($randnpc_skills[$skr]);
			$skills[$k] = $randnpc_skills[$skr][$k];
		}
		return $skills;
	}
	
	function add_randnpc($rank, $num=1, $offens_tend=0, $defens_tend=0, $variety=0, $use_preset=1, $pls_available=0, $addnews=0) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$randnpcs = generate_randnpc($rank, $num, $offens_tend, $defens_tend, $variety, $use_preset);
		if (empty($randnpcs)) return;
		if (empty($pls_available)) $pls_available = \map\get_safe_plslist();
		$summon_ids = array();
		for($i=0;$i<$num;$i++)
		{
			$npc = $randnpcs[$i];
			if ($use_preset == 0) $npc['type'] = 50 + min(ceil($rank / 2), 7);
			$npc['sNo'] = $i;
			$npc = \npc\init_npcdata($npc,$pls_available);
			$npc = \player\player_format_with_db_structure($npc);
			$db->array_insert("{$tablepre}players", $npc);
			$summon_ids[] = $db->insert_id();
			
			if ($addnews)
			{
				$newsname = $typeinfo[$npc['type']].' '.$npc['name'];
				// addnews($now, 'addnpc', $newsname);
				addnews($now, 'addnpc_pls', $newsname, '', $npc['pls']);
			}
		}
		return $summon_ids;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0)
		{	
			if ($itm == '测试用NPC召唤器')
			{
				$log .= "使用了<span class=\"yellow b\">$itm</span>。<br>";
				$rank = rand(1,20);
				add_randnpc($rank, 3, 0, 0, 0, 0);
				return;
			}
		}
		$chprocess($theitem);
	}
	
}

?>