<?php

namespace skill963
{
	function init()
	{
		define('MOD_SKILL963_INFO','feature;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[963] = '行商';
	}
	
	function acquire963(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(963, 'itmarr', '', $pa);//商品数组
		\skillbase\skill_setvalue(963, 'updtime', 0, $pa);//上一次更新货物的时间
	}
	
	function lost963(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(963, 'itmarr', $pa);
		\skillbase\skill_delvalue(963, 'updtime', $pa);
	}
	
	//遇到行商NPC后可通过选项访问商店
	function meetman_alternative($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($edata['type'] > 0 && $edata['hp'] > 0 && \skillbase\skill_query(963, $edata) && empty($edata['battle_prepare']))
		{
			$pa_shopnpc_flag = (int)\skillbase\skill_getvalue(951, 'shopnpc_flag', $pa);
			if ($pa_shopnpc_flag >= 0)
			{
				find_shopnpc($edata); 
				return;
			}
		}
		$chprocess($edata);
	}
	
	//遭遇行商NPC的界面
	function find_shopnpc($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','metman','logger'));
		
		extract($edata,EXTR_PREFIX_ALL,'w');
		$action = 'shopnpc'.$edata['pid'];
		$sdata['keep_shopnpc'] = 1;
		$battle_title = '发现商人';
		\metman\init_battle(1);
		
		if (empty($edata['shop_prepare']))
		{
			$log .= "你发现了商人<span class=\"yellow b\">{$tdata['name']}</span>！<br>";
			include template(MOD_SKILL963_FIND_SHOPNPC);
		}
		else
		{
			$log .= "<span class=\"yellow b\">{$tdata['name']}</span>向你展示着他的商品……<br>";
			include template(MOD_SKILL963_SHOW_SHOPNPC_ITEMS);
		}
		$cmd = ob_get_contents();
		ob_clean();
		$main = MOD_METMAN_MEETMAN;
		return;
	}
	
	function post_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('player'));
		if(empty($sdata['keep_shopnpc']) && strpos($action, 'shopnpc')===0){
			$action = '';
			unset($sdata['keep_shopnpc']);
		}
	}
	
	function act() //购物的判定还没有写
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		if ($mode == 'shopnpc')
		{
			$shopnpcid = str_replace('shopnpc','',$action);
			if (!$shopnpcid || strpos($action,'shopnpc')===false)
			{
				$log .= '<span class="yellow b">你没有遇到队友，或已经离开现场！</span><br>';
				$mode = 'command';
				return;
			}
			$edata = \player\fetch_playerdata_by_pid($shopnpcid);
			if ($edata['hp'] <= 0)
			{
				$log .= '<span class="yellow b">目标已经死亡，无法进行此操作。</span><br>';
				$mode = 'command';
				return;
			}
			if ($edata['type'] == 0 || !\skillbase\skill_query(963, $edata))
			{
				$log .= '<span class="yellow b">目标角色不合法。</span><br>';
				$mode = 'command';
				return;
			}
			if (((int)\skillbase\skill_getvalue(951, 'shopnpc_flag', $pd) == -1))
			{
				$edata['pose'] = 2;
				\metman\meetman_alternative($edata);
				return;
			}
			if ($command == 'battle')
			{
				$edata['battle_prepare'] = 1;
				\metman\meetman_alternative($edata);
				return;
			}
			elseif ($command == 'shop')
			{
				$edata['shop_prepare'] = 1;
				\metman\meetman_alternative($edata);
				return;
			}
			elseif ($command == 'tip')
			{
				$tip = generate_shopnpc_tip($edata);
				$log .= "<span class=\"yellow b\">“{$tip}”</span><br><br>";
				\metman\meetman_alternative($edata);
				return;
			}
			elseif ($command == 'shopback')
			{
				\metman\meetman_alternative($edata);
				return;
			}
			elseif (strpos($command, 'buy')===0)
			{
				$shopitemid = (int)substr($command, 3) - 1;
				buy_shopnpc_shopitem($edata, $shopitemid);
				$edata['shop_prepare'] = 1;
				\metman\meetman_alternative($edata);
				return;
			}
		}
		$chprocess();
	}
	
	function get_shopnpc_shopitem(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$updtime = (int)\skillbase\skill_getvalue(963, 'updtime', $pa);
		if (empty($updtime) || $now - $updtime > 240)
		{
			update_shopnpc_shopitem($pa);
		}
		$ret = \skillbase\skill_getvalue(963, 'itmarr', $pa);
		if (!empty($ret)) $ret = skill963_decode_itmarr($ret);
		else $ret = array();
		return $ret;
	}
	
	function update_shopnpc_shopitem(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill963','cardbase','item_randskills'));
		if (empty($gamevars['instance10_stage'])) $stage = 1;
		else $stage = $gamevars['instance10_stage'];
		//生成随机商品列表
		$itmarr = array();
		foreach($skill963_shopitems as $ls)
		{
			$itmpool = array();
			$wsum = 0;
			foreach($ls['items'] as $v)
			{
				if ($v[9] <= $stage && $v[10] >= $stage)
				{
					$itmpool[] = $v;
					$wsum += $v[8];
				}
			}
			if (empty($itmpool) || empty($wsum)) continue;
			$num = rand($ls['min'],$ls['max']);
			for ($i=0;$i<$num;$i++)
			{
				$dice = rand(1,$wsum);
				$r = $itmpool[0];
				foreach ($itmpool as $v)
				{
					if ($dice <= $v[8])
					{
						$r = $v;
						break;
					}
					else $dice -= $v[8];
				}
				$itmnew = array_slice($r, 0, 6);
				if ($r[6])
				{
					$t = rand(1,$r[6]);
					$itmnew[3] *= $t;
					$itmnew[5] *= $t;
				}
				if ($r[7])
				{
					$itmnew[5] = (int)($itmnew[5] * rand(100-$r[7],100+$r[7]) / 100);
					$itmnew[5] = max($itmnew[5], 1);
				}
				if (strpos($itmnew[4], 'rdsk_') === 0)
				{
					$rdsklvl = $itmnew[4][5];
					$itmnew[4] = array_randompick($rs_cardskills[$rdsklvl]);
				}
				elseif (strpos($itmnew[4], 'card_') === 0)
				{
					$rare = $itmnew[4][5];
					$itmnew[4] = array_randompick($cardindex[$rare]);
				}
				elseif(defined('MOD_ATTRBASE')) {
					$itmnew[4] = \attrbase\config_process_encode_comp_itmsk($itmnew[4]);
				}
				$itmarr[] = $itmnew;
			}
		}
		\skillbase\skill_setvalue(963,'itmarr',skill963_encode_itmarr($itmarr),$pa);
		\skillbase\skill_setvalue(963,'updtime',$now,$pa);
		\player\player_save($pa);
	}
	
	function buy_shopnpc_shopitem(&$pa, $shopitemid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		$shopnpc_shopitem = get_shopnpc_shopitem($pa);
		if (!isset($shopnpc_shopitem[$shopitemid]))
		{
			$log .= '该商品不存在。<br>';
			return;
		}
		$si = $shopnpc_shopitem[$shopitemid];
		$cost = $si[5];
		if (\skillbase\skill_query(69, $sdata))
		{
			$flag1 = 1;
			$cost = max(1, round($cost*0.75));
		}
		if ((int)\skillbase\skill_getvalue(951, 'shopnpc_flag', $sdata) == 1)
		{
			$flag2 = 1;
			$cost = max(1, round($cost*0.5));
		}
		if ($money < $cost)
		{
			$log .= '你的钱不够，不能购买此物品。<br>';
			return;
		}
		if (!empty($flag1)) $log .= '你的富家子弟身份让对方给你打了七五折。<br>';
		if (!empty($flag2))
		{
			$log .= '看在你上次给他帮了个小忙的份上，对方给你额外打了五折。<br>';
			\skillbase\skill_setvalue(951, 'shopnpc_flag', 0, $sdata);
		}
		$log .= "购买成功，花费<span class='yellow b'>$cost</span>元。已购买的商品已加入你的奖励盒中。<br>";
		$money -= $cost;
		$theitem = array('itm'=>$si[0], 'itmk'=>$si[1], 'itme'=>$si[2], 'itms'=>$si[3], 'itmsk'=>$si[4]);
		\skill952\skill952_sendin_core($theitem, $sdata);
		unset($shopnpc_shopitem[$shopitemid]);
		\skillbase\skill_setvalue(963,'itmarr',skill963_encode_itmarr($shopnpc_shopitem),$pa);
		\player\player_save($pa);
	}
	
	function generate_shopnpc_tip(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$tip = array_randompick(array('咕咕咕……','咕咕咕！','咕咕咕？','咕——'));//待完成，先咕着吧
		return $tip;
	}
	
	function skill963_encode_itmarr($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gencode($arr);
	}
	
	function skill963_decode_itmarr($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gdecode($str, 1);
	}
	
	//被攻击后，添加敌对玩家标记
	function attack_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if ($pd['type'] > 0 && \skillbase\skill_query(963, $pd))
		{
			\skillbase\skill_setvalue(951, 'shopnpc_flag', -1, $pa);
		}
	}
	
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		//玩家击杀行商NPC敌对玩家后，添加打折标记
		if ($pd['hp'] <= 0 && ((int)\skillbase\skill_getvalue(951, 'shopnpc_flag', $pd) == -1) && ((int)\skillbase\skill_getvalue(951, 'shopnpc_flag', $pa) >= 0))
		{
			\skillbase\skill_setvalue(951, 'shopnpc_flag', 1, $pa);
		}
		//商人被击杀时掉落一部分商品
		elseif ($pd['type'] > 0 && \skillbase\skill_query(963, $pd) && ($pd['hp'] <= 0))
		{
			$shopnpc_shopitem = get_shopnpc_shopitem($pd);
			$prize_itm = array_randompick($shopnpc_shopitem, min(7, count($shopnpc_shopitem)));
			if (!is_array($prize_itm)) $prize_itm = array($prize_itm);
			$i = 0;
			foreach ($prize_itm as $v)
			{
				$pd['itm'.$i] = $v[0];
				$pd['itmk'.$i] = $v[1];
				$pd['itme'.$i] = $v[2];
				$pd['itms'.$i] = $v[3];
				$pd['itmsk'.$i] = $v[4];
				$i += 1;
			}
		}
	}
	
}

?>