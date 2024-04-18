<?php

namespace item_uvo_extra
{
	//允许卡牌包额外功能的模式，暂时仅有肉鸽模式
	$allow_uvo_extra_gametype = array(20);
	
	//允许组队赠送卡片的模式，暂时仅有肉鸽模式
	$allow_uvo_extra_cardsend_gametype = array(20);
	
	//素材卡
	$material_cards = array(1101,1102,1103,1104,1105,1106,1107,1108,1109,1110,1111,1112,1113);
	
	function init()
	{
	}
	
	//记录获得的卡片，$get_cards可为单个卡片id或卡片id数组，$tmp为0表示暂存的卡片，为1表示已使用过的卡片
	function add_card_uvo_extra($get_cards, &$pa, $tmp = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$cards_uvo = get_cards_uvo_extra($pa, $tmp);
		if (is_array($get_cards)) $cards_uvo = array_merge($cards_uvo, $get_cards);
		else $cards_uvo[] = $get_cards;
		if ($tmp) $k = 'cards_used';
		else $k = 'cards_temp';
		\skillbase\skill_setvalue(951, $k, encode_uvo_extra($cards_uvo), $pa);
	}
	
	//移除获得的卡片，$tmp同上
	function remove_card_uvo_extra($cardid, &$pa, $tmp = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($tmp) $k = 'cards_used';
		else $k = 'cards_temp';
		if ($cardid == 'all')
		{
			\skillbase\skill_setvalue(951, $k, '', $pa);
			return;
		}
		$cards_uvo = get_cards_uvo_extra($pa, $tmp);
		$key = array_search($cardid, $cards_uvo);
		if ($key !== false)
		{
			unset($cards_uvo[$key]);
			\skillbase\skill_setvalue(951, $k, encode_uvo_extra($cards_uvo), $pa);
		}
	}
	
	//获得记录的卡片列表，$tmp同上
	function get_cards_uvo_extra(&$pa, $tmp = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($tmp) $k = 'cards_used';
		else $k = 'cards_temp';
		return decode_uvo_extra(\skillbase\skill_getvalue(951, $k, $pa));
	}
	
	function encode_uvo_extra($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return implode('_', $arr);
	}
	
	function decode_uvo_extra($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($str)) return Array();
		return explode('_', $str);
	}
	
	function item_uv_getcard($get_card_id, $get_cardinfo, $itm, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','item_uvo_extra','logger'));
		if (!in_array($gametype, $allow_uvo_extra_gametype)) return $chprocess($get_card_id, $get_cardinfo, $itm, $pa);
		
		//将卡片记录到技能中
		add_card_uvo_extra($get_card_id, $pa, 0);
		
		//碎闪等级和是否为新在游戏结束发卡时候再判断
		$blink = 0;
		$is_new = 0;
		
		$log .= '<span class="yellow b">你获得了卡片「'.$get_cardinfo['name'].'」！获得的卡片可在卡片列表中查看。</span><br>';
		
		addnews ( 0, 'VOgetcard', $pa['name'], $itm, $get_cardinfo['name'] );
		
		return array($blink, $is_new);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		eval(import_module('sys'));
		if ($mode == 'command' && $command == 'card')
		{
			eval(import_module('player','logger','item_uvo_extra'));
			if (!in_array($gametype, $allow_uvo_extra_gametype))
			{
				$log .= '该游戏模式无法进行此操作。<br>';
				$mode = 'command';
				return;
			}
			$card_choice1 = (int)get_var_in_module('card_choice1', 'input');
			$card_choice2 = (int)get_var_in_module('card_choice2', 'input');
			if (!empty($card_choice1))
			{
				if ($card_choice2 == -1)
				{
					use_card_uvo_extra($card_choice1, $sdata);
					$mode = 'command';
					return;
				}
				elseif ($card_choice2 > 0)
				{
					mix_cards_uvo_extra($card_choice1, $card_choice2, $sdata);
				}
				else
				{
					$log .= '参数不合法。<br>';
				}
			}
			$subcmd = get_var_input('subcmd');
			if ($subcmd == 'carduse') include template(MOD_ITEM_UVO_EXTRA_UVO_EXTRA_USE);
			elseif ($subcmd == 'cardmix') include template(MOD_ITEM_UVO_EXTRA_UVO_EXTRA_MIX);
			$cmd = ob_get_contents();
			ob_clean();
			return;
		}
		$chprocess();
	}
	
	//使用暂存的卡片
	function use_card_uvo_extra($cardid, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$cards_uvo = get_cards_uvo_extra($pa);
		if (!in_array($cardid, $cards_uvo))
		{
			$log .= '输入卡片参数错误。<br>';
			return;
		}
		eval(import_module('cardbase','item_uvo_extra'));
		if (in_array($cardid, $material_cards))
		{
			$log .= '素材卡不能使用。<br>';
			return;
		}
		$cardid_o = $cardid;
		if(!empty($cards[$cardid]['valid']['cardchange']))
		{
			$cardid = \cardbase\cardchange($cardid);
			if(empty($cards[$cardid])) $cardid = 0;
			$log .= "<span class=\"yellow b\">卡片「{$cards[$cardid_o]['name']}」变成了「{$cards[$cardid]['name']}」的样子！</span><br>";
		}
		
		$card_valid_info = $cards[$cardid]['valid'];
		list($items, $skills) = use_card_uvo_process($card_valid_info, $pa);
		
		if ($cardid == 168)//飞雪大大 
		{
			$items = array(
				array('itm'=>'魔王の剑','itmk'=>'WK','itme'=>300,'itms'=>300,'itmsk'=>'u')
				);
		}
		elseif ($cardid == 196)//低维生物
		{
			$skills = array();
			$items = array(
				array('itm'=>'界线『Curse of Dimensionality』','itmk'=>'WFD','itme'=>233,'itms'=>'∞','itmsk'=>'trend')
				);
		}
		elseif ($cardid == 209)//林无月 
		{
			$skills = array();
			$items = array(
				array('itm'=>'银色盒子','itmk'=>'p','itme'=>1,'itms'=>55,'itmsk'=>'')
				);
		}
		elseif ($cardid == 292)//邪教徒 
		{
			if(defined('MOD_ATTRBASE')) {
				$newitmsk = \attrbase\config_process_encode_comp_itmsk('^tlog_<:comp_itmsk:>{<span class="lightblue b">“咔咔！”</span>}1');
				$items = array(
					array('itm'=>'邪教徒头套','itmk'=>'DH','itme'=>6,'itms'=>66,'itmsk'=>$newitmsk)
					);
			}
		}
		elseif ($cardid == 344)//油库里
		{
			$skills = array();
		}
		elseif ($cardid == 381)//双料特工
		{
			$skills = array();
			$items = array(
				array('itm'=>'手榴弹','itmk'=>'WC','itme'=>40,'itms'=>1,'itmsk'=>''),
				array('itm'=>'毒物说明书','itmk'=>'A','itme'=>1,'itms'=>1,'itmsk'=>'')
				);
		}
		elseif ($cardid == 420)//肉鸽挑战者
		{
			$skills = array();
			$items = array();
			
			$weplist = openfile(GAME_ROOT.'./include/modules/extra/instance/instance10_rogue/config/stwep.config.php');
			do {
				$index = rand(1,count($weplist)-1);
				$newitem = array();
				list($newitem['itm'],$newitem['itmk'],$newitem['itme'],$newitem['itms'],$newitem['itmsk']) = \itemmain\startingitem_row_data_seperate($weplist[$index]);
				if(defined('MOD_ATTRBASE')) {
					$newitem['itmsk'] = \attrbase\config_process_encode_comp_itmsk($newitem['itmsk']);
				}
			} while(!$newitem['itms']);
			$items[] = $newitem;
			$stitemlist = openfile(GAME_ROOT.'./include/modules/extra/instance/instance10_rogue/config/stitem.config.php');
			for($i=1;$i<=2;$i++){
				do {
					$index = rand(1,count($stitemlist)-1);
					$newitem = array();
					list($newitem['itm'],$newitem['itmk'],$newitem['itme'],$newitem['itms'],$newitem['itmsk']) = \itemmain\startingitem_row_data_seperate($stitemlist[$index]);
					if(defined('MOD_ATTRBASE')) {
						$newitem['itmsk'] = \attrbase\config_process_encode_comp_itmsk($newitem['itmsk']);
					}
				} while(!$newitem['itms']);
				$items[] = $newitem;
			}
		}
		
		remove_card_uvo_extra($cardid_o, $pa, 0);
		add_card_uvo_extra($cardid_o, $pa, 1);
		
		$log .= "<span class=\"yellow b\">卡片「{$cards[$cardid_o]['name']}」的力量融入了你的体内！</span><br>";
		
		//获得技能
		$acquired_skills = \skillbase\get_acquired_skill_array($pa);
		foreach ($skills as $key=>$value)
		{
			if (in_array($key, $acquired_skills)) continue;
			\skillbase\skill_acquire($key,$pa);
			if(is_array($value)){
				foreach($value as $vk => $vv){
					\skillbase\skill_setvalue($key,$vk,$vv,$pa);
				}
			}elseif ($value>0){
				\skillbase\skill_setvalue($key,'lvl',$value,$pa);
			}
		}
		
		//获得道具
		\skill1006\multi_itemget($items, $pa);
	}
	
	//卡片效果处理
	function use_card_uvo_process($card_valid_info, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itempos_processed = array();
		$items = array();
		$skills = array();
		
		$skills_ignore = array(10,11,12,83);//不会获得尊严
		$skills_pass = array(19,20,24,51,59,106,224,231);//部分涉及多个技能绑定的技能，如百出和虹光等
		
		//针对称号卡片的调整，非称号特性的本称号技能只有40%获得
		if (isset($card_valid_info['club']))
		{
			eval(import_module('clubbase'));
			$clubskills = $clublist[$card_valid_info['club']]['skills'];
			foreach($card_valid_info['skills'] as $sk => $sv){
				if (in_array($sk, $skills_ignore)) unset($card_valid_info['skills'][$sk]);
				if (in_array($sk, $skills_pass)) continue;
				if (in_array($sk, $clubskills) && !\skillbase\check_skill_info($sk,'feature'))
				{
					if (rand(0,99) < 60) unset($card_valid_info['skills'][$sk]);
				}
			}
		}
		//随机分支提前处理
		if (isset($card_valid_info['rand_sets']))
		{
			$rand_v = array_randompick($card_valid_info['rand_sets']);
			unset($card_valid_info['rand_sets']);
			if (isset($rand_v['skills']))
			{
				foreach($rand_v['skills'] as $sk => $sv){
					if (in_array($sk, $skills_ignore)) continue;
					$skills[$sk] = $sv;
				}
				unset($rand_v['skills']);
			}
			$card_valid_info = array_merge($card_valid_info, $rand_v);
		}
		
		foreach ($card_valid_info as $key => $value)
		{
			if('skills' == $key) {
				foreach($value as $sk => $sv){
					if (in_array($sk, $skills_ignore)) continue;
					$skills[$sk] = $sv;
				}
				continue;
			}elseif('rand_skills' == $key){
				foreach($value as $rk => $rv){
					$rnum = 1;
					if(!empty($rv['rnum'])) $rnum = $rv['rnum'];
					unset($rv['rnum']);
					$rkeys = array_keys($rv);
					shuffle($rkeys);
					for($i = 1;$i <= $rnum;$i++){
						$skills[$rkeys[$i]] = $rv[$rkeys[$i]];
					}
				}
				continue;
			}elseif(in_array(substr($key,0,3), Array('wep','arb','arh','ara','arf','art','itm'))){
				$itempos = substr($key,0,3);
				if ($itempos == 'itm')
				{
					$ik = $key[-1];
					$itempos .= $ik;
					$keys = array('itm'.$ik,'itmk'.$ik,'itme'.$ik,'itms'.$ik,'itmsk'.$ik);
				}
				else $keys = array($itempos,$itempos.'k',$itempos.'e',$itempos.'s',$itempos.'sk');
				
				if (in_array($itempos,$itempos_processed)) continue;
				$itempos_processed[] = $itempos;
				
				$flag = 1;
				foreach($keys as $k)
				{
					if (!isset($card_valid_info[$k]))//只有是一个完整的道具的时候才会获得
					{
						$flag = 0;
						break;
					}
					if (is_array($card_valid_info[$k])){
						$card_valid_info[$k]= array_randompick($card_valid_info[$k]);
					}
				}
				if ($flag)
				{
					$item_new = array('itm'=>$card_valid_info[$keys[0]],'itmk'=>$card_valid_info[$keys[1]],'itme'=>$card_valid_info[$keys[2]],'itms'=>$card_valid_info[$keys[3]],'itmsk'=>$card_valid_info[$keys[4]]);
					if(defined('MOD_ATTRBASE')) {
						$item_new['itmsk'] = \attrbase\config_process_encode_comp_itmsk($item_new['itmsk']);
					}
					$items[] = $item_new;
				}
			}elseif(in_array($key, Array('hp','mhp','sp','msp','ss','mss','att','def','exp','money','rage','skillpoint','wp','wk','wc','wg','wf','wd'))){
				$val1 = substr($value, 0, 1);
				$val2 = substr($value, 1);
				if(in_array($val1, Array('+','-','=')) && is_numeric($val2)){
					if('+' == $val1) $pa[$key] += $val2;
					elseif('-' == $val1) $pa[$key] -= $val2;
					else $pa[$key] = $val2;
				}else{
					$pa[$key] = $value;
				}
			}
		}
		return array($items, $skills);
	}
	
	//合成暂存的卡片
	function mix_cards_uvo_extra($cardid1, $cardid2, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		eval(import_module('logger'));
		$cards_uvo = get_cards_uvo_extra($pa);
		if (!in_array($cardid1, $cards_uvo) || !in_array($cardid2, $cards_uvo))
		{
			$log .= '输入卡片参数错误。<br>';
			return;
		}
		if ($cardid1 == $cardid2)
		{
			$card_counts = array_count_values($cards_uvo);
			if (!isset($card_counts[$cardid1]) || $card_counts[$cardid1] < 2)
			{
				$log .= '输入卡片参数错误。<br>';
				return;
			}
		}
		eval(import_module('cardbase','item_uvo_extra'));
		$rare1 = $cards[$cardid1]['rare'];
		$rare2 = $cards[$cardid2]['rare'];
		$pack = '';
		
		//素材卡特判
		$skip_flag = 0;
		if (in_array($cardid1,$material_cards) && in_array($cardid2,$material_cards))
		{
			$log .= '一次只能使用一张素材卡。<br>';
			return;
		}
		if (in_array($cardid1,$material_cards)) $mcardpos = 1;
		elseif (in_array($cardid2,$material_cards)) $mcardpos = 2;
		if (!empty($mcardpos))
		{
			$mcardid = ${'cardid'.$mcardpos};
			if ($mcardid == 1101)
			{
				$rare = 'C';
				$skip_flag = 1;
			}
			elseif ($mcardid == 1103)
			{
				if ($mcardpos == 1) $rare1 = $rare2;
				else $rare2 = $rare1;
			}
			elseif ($mcardid == 1104)
			{
				$pack = 'Ranmen';
			}
			elseif ($mcardid == 1105)
			{
				$pack = '東埔寨Protoject';
			}
			elseif ($mcardid == 1106)
			{
				$pack = 'Top Players';
			}
			elseif ($mcardid == 1107)
			{
				$rare = 'S';
				$skip_flag = 1;
			}
			elseif ($mcardid == 1108)
			{
				$pack = 'Standard Pack';
			}
			elseif ($mcardid == 1109)
			{
				$pack = 'Crimson Swear';
			}
			elseif ($mcardid == 1110)
			{
				$pack = 'Way of Life';
			}
			elseif ($mcardid == 1111)
			{
				$pack = 'Best DOTO';
			}
			elseif ($mcardid == 1112)
			{
				$pack = 'Balefire Rekindle';
			}
			elseif ($mcardid == 1113)
			{
				$pack = 'Event Bonus';
			}
		}
		if (empty($get_card_id))
		{
			if (empty($rare)) $rare = get_card_rare_uvo($rare1, $rare2);
			if (empty($rare))
			{
				$log .= '这两张卡不能合成。<br>';
				return;
			}
			$get_card_id = get_card_id_uvo($rare, $pack);
		}
		
		remove_card_uvo_extra($cardid1, $pa, 0);
		remove_card_uvo_extra($cardid2, $pa, 0);
		add_card_uvo_extra($get_card_id, $pa, 0);
		$blink = 0;
		$is_new = 0;
		$log .= '<span class="yellow b">你用「'.$cards[$cardid1]['name'].'」和「'.$cards[$cardid2]['name'].'」合成了卡片「'.$cards[$get_card_id]['name'].'」！</span><br>';
		addnews(0, 'cardmix', $pa['name'], $cards[$cardid1]['name'], $cards[$cardid2]['name'], $cards[$get_card_id]['name']);
		
		ob_clean();
		include template('MOD_CARDBASE_CARDFLIP_RESULT');
		$log .= ob_get_contents();
		ob_clean();
	}
	
	function get_card_rare_uvo($rare1, $rare2)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$rare = '';
		//丑陋但有效
		if ($rare1 == $rare2)
		{
			if ($rare1 == 'A') $rare = 'S';
			elseif ($rare1 == 'B') $rare = 'A';
			elseif ($rare1 == 'C') $rare = 'B';
			elseif ($rare1 == 'M') $rare = 'S';
		}
		elseif ($rare1 == 'M') $rare = $rare2;
		elseif ($rare2 == 'M') $rare = $rare1;
		elseif (($rare1 == 'S' && $rare2 == 'A') || ($rare1 == 'A' && $rare2 == 'S')) $rare = 'S';
		elseif (($rare1 == 'A' && $rare2 == 'B') || ($rare1 == 'B' && $rare2 == 'A')) $rare = 'A';
		elseif (($rare1 == 'B' && $rare2 == 'C') || ($rare1 == 'C' && $rare2 == 'B')) $rare = 'B';
		return $rare;
	}
	
	function get_card_id_uvo($rare, $pack = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$cardpool = $cardindex[$rare];
		if (!empty($pack))
		{
			if (in_array($pack, $pack_ignore_kuji)) $cardpool = array_merge($cardpool, $cardindex['EB_'.$rare]);
			$c = 0;
			do{
				$get_card_id = array_randompick($cardpool);
				$c += 1;
			}while($cards[$get_card_id]['pack'] !== $pack && $c < 99);//不会真有人写爆炸吧
		}
		else $get_card_id = array_randompick($cardpool);
		return $get_card_id;
	}
	
	//游戏结束时，所有未使用的和已使用的卡片，如果是BR或EB包发卡牌包，其他的发对应卡片
	function gameover_set_credits()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('sys','item_uvo_extra'));
		if(!in_array($gametype, $allow_uvo_extra_gametype)) return;
		if(empty($gameover_plist)) return;
		eval(import_module('cardbase'));
		foreach($gameover_plist as $key => $pa)
		{
			$pa_cards = array_merge(get_cards_uvo_extra($pa), get_cards_uvo_extra($pa, 1));
			if (!empty($pa_cards))
			{
				$prizepack_count = array(206=>0, 204=>0, 203=>0, 202=>0);
				foreach ($pa_cards as $get_card_id)
				{
					if (in_array($cards[$get_card_id]['pack'], $pack_ignore_kuji) && !in_array($get_card_id, array(159,165,420)))//特判氪金、NIKO、肉鸽挑战者
					{
						if($cards[$get_card_id]['rare'] == 'S') $prizepack_count[206] += 2;
						elseif($cards[$get_card_id]['rare'] == 'A') $prizepack_count[204] += 2;
						elseif($cards[$get_card_id]['rare'] == 'B') $prizepack_count[203] += 2;
						else $packcount[202] += 2;
						continue;
					}
					if($room_prefix) {
						$ext = '来自'.$gtinfo[$gametype].'的卡片奖励。';
					}else{
						$ext = '来自第'.$gamenum.'局的卡片奖励。';
					}
					if($cards[$get_card_id]['rare'] == 'A') $ext.='运气不错！';
					elseif($cards[$get_card_id]['rare'] == 'S') $ext.='一定是欧洲人吧！';
					$blink = \cardbase\get_card_calc_blink($get_card_id, $pa);
					$is_new = \cardbase\get_card_message($get_card_id,$ext,$blink,$pa);
				}
				foreach ($prizepack_count as $k => $v)
				{
					if ($v > 0)
					{
						include_once './include/messages.func.php';
						message_create(
							$pa['name'],
							$gtinfo[$gametype].'奖励',
							'祝贺你在房间第'.$gamenum.'局中获得了卡牌包奖励！<br>',
							'getlogitem_'.$k.';getlogitemnum_'.$v.';'
						);
					}
				}
			}
		}
	}
	
	//杀人夺卡
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		eval(import_module('sys','item_uvo_extra'));
		if (in_array($gametype, $allow_uvo_extra_gametype) && $pa['type'] == 0 && $pd['hp'] <= 0)
		{
			$pd_cards = get_cards_uvo_extra($pd);
			if (!empty($pd_cards))
			{
				eval(import_module('logger'));
				if ($active)
				{
					$log .= "<br><span class=\"yellow b\">物尽其用！你夺走了对方所有未使用的卡片。</span><br>";
					$pd['battlelog'] .= '<span class="red b">对方夺走了你所有未使用的卡片！</span>';
				}
				else
				{
					$log .= "<br><span class=\"red b\">你所有未使用的卡片被对方夺走了！</span><br>";
					$pa['battlelog'] .= "<span class=\"yellow b\">你夺走了对方所有未使用的卡片！</span>";
				}
				add_card_uvo_extra($pd_cards, $pa);
				remove_card_uvo_extra('all', $pd);
			}
		}
	}
	
	//组队送卡
	function senditem_extra($ldata, $edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','item_uvo_extra'));
		if (in_array($gametype, $allow_uvo_extra_cardsend_gametype) && (strpos($command,'card') === 0))
		{
			eval(import_module('logger'));
			$cardid = substr($command, 4);
			$cards_uvo = get_cards_uvo_extra($ldata);
			if (!in_array($cardid, $cards_uvo))
			{
				$log .= '你没有这张卡片。<br>';
				return 0;
			}
			remove_card_uvo_extra($cardid, $ldata);
			add_card_uvo_extra($cardid, $edata);
			
			eval(import_module('cardbase'));
			$cardname = $cards[$cardid]['name'];
			$log .= "你将卡片<span class=\"yellow b\">“{$cardname}”</span>送给了<span class=\"yellow b\">{$edata['name']}</span>。<br>";
			$x = "<span class=\"yellow b\">{$ldata['name']}</span>将卡片<span class=\"yellow b\">“{$cardname}”</span>送给了你。";
			if(!$edata['type']) \logger\logsave($edata['pid'],$now,$x,'t');
			addnews($now,'sendcard',$ldata['name'],$edata['name'],$cardname);
			\player\player_save($edata);
			return 1;
		}
		return $chprocess($ldata, $edata);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if ($news == 'cardmix')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}用卡片“{$b}”和“{$c}”合成了卡片“{$d}”！</span></li>";
		elseif ($news == 'sendcard') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}将卡片<span class=\"yellow b\">“{$c}”</span>赠送给了{$b}</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
