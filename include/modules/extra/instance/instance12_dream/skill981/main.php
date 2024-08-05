<?php

namespace skill981
{
	function init() 
	{
		define('MOD_SKILL981_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[981] = '演练';
	}
	
	function acquire981(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(981,'stage','0',$pa);
		\skillbase\skill_setvalue(981,'rm','0',$pa);
		\skillbase\skill_setvalue(981,'maxstage','11',$pa);
	}
	
	function lost981(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(981,'stage',$pa);
		\skillbase\skill_delvalue(981,'rm',$pa);
		\skillbase\skill_delvalue(981,'maxstage',$pa);
	}
	
	function check_unlocked981(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
		
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if ($n == '3秒死机的AIPC')
		{
			$ret = '可用于解除禁区，进入英灵殿。<br><span class=&quot;yellow b&quot;>“我打黑熊……？诶……真的假的？”</span>';
		}
		elseif ($n == '梦境的前路')
		{
			$ret = '可用于开启第12-16波次，以达成『幻境解离』胜利';
		}
		elseif ($n == '深暗幻想')
		{
			$ret = '可用于开启无尽波次的挑战，使用后将会面对<span class=&quot;red b&quot;>极其强大</span>的敌人</span>';
		}
		return $ret;
	}
	
	function assault_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		//玩家主动攻击打死NPC
		if (\skillbase\skill_query(981,$pa) && check_unlocked981($pa) && $pd['hp'] <= 0)
		{
			$theplayer = & $pa;
		}
		//一般是NPC先制玩家被打死
		elseif (\skillbase\skill_query(981,$pd) && check_unlocked981($pd) && $pa['hp'] <= 0) {
			$theplayer = & $pd;
		}
		if(!empty($theplayer)) {
			$rm = (int)\skillbase\skill_getvalue(981,'rm',$theplayer);
			if ($rm == 1)
			{
				eval(import_module('logger','skill981'));
				$stage = (int)\skillbase\skill_getvalue(981,'stage',$theplayer);
				$theitem = array('itm'=>'梦境礼盒','itmk'=>'Y','itme'=>$stage,'itms'=>$skill981_prizebox_num[$stage],'itmsk'=>'');
				\skill952\skill952_sendin_core($theitem, $theplayer);
				$log .= "<span class=\"lime b\">奖励道具被送到了你的奖励箱中。</span><br>";
			}
			$rm = max($rm - 1, 0);
			\skillbase\skill_setvalue(981,'rm',$rm,$theplayer);
		}
		
		$chprocess($pa,$pd,$active);
	}
	
	function cast_skill981()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(981)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$rm = (int)\skillbase\skill_getvalue(981,'rm');
		if ($rm > 0)
		{
			$log .= '上一波次尚未结束。<br>';
			return;
		}
		$stage = (int)\skillbase\skill_getvalue(981,'stage');
		$maxstage = (int)\skillbase\skill_getvalue(981,'maxstage');
		if ($stage >= $maxstage)
		{
			$log .= '你已经战胜了所有的敌人。<br>';
			return;
		}
		$stage += 1;
		$log .= "<span class=\"yellow b\">新的敌人加入了战场！</span><br>";
		addnews($now, 'instance12_nextwave', $stage);
		eval(import_module('skill981'));
		$rm = 0;
		if ($stage <= 16)
		{
			foreach($skill981_enemies[$stage] as $k => $v){
				\randnpc\add_randnpc($k, $v, 0, 0, 0, 0, array(201));
				$rm += $v;
			}
		}
		else
		{
			$def_tend = ($stage - 17) * 10;
			\randnpc\add_randnpc(19, 2, 0, $def_tend, 0, array(21, 21, 22), array(201));//用两个21表示武神的权重翻倍，有点幽默但就先这样吧
			$rm += 2;
		}
		\skillbase\skill_setvalue(981,'stage',$stage);
		\skillbase\skill_setvalue(981,'rm',$rm);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($mode == 'special' && $command == 'skill981_special')
		{
			cast_skill981();
			return;
		}
		$chprocess();
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos($itmk, 'Y') === 0 || strpos($itmk, 'Z') === 0)
		{
			if ($itm == '梦境礼盒') 
			{
				$log .= "你使用了<span class=\"yellow b\">{$itm}</span>。<br>";
				$pbx_choice = get_var_input('pbx_choice');
				if (!empty($pbx_choice))
				{
					$pbx_choice = (int)$pbx_choice;
					$pbx_itemlist = \skill981\get_prizebox_itemlist($itme, $itmsk);
					if (!isset($pbx_itemlist[$pbx_choice-1]))
					{
						$log .= '参数不合法。<br>';
						$mode = 'command';
						return;
					}
					$prizeitem = $pbx_itemlist[$pbx_choice-1];
					if ($prizeitem['itmk'] == 'YY')
					{
						$money += $prizeitem['itme'];
						$log .= "你获得了<span class=\"yellow b\">{$prizeitem['itme']}</span>元金钱。<br>";
					}
					else
					{
						\skill952\skill952_sendin_core($prizeitem, $pa);
						$log .= "<span class=\"yellow b\">{$prizeitem['itm']}</span>被送到了你的奖励箱中。<br>";
					}
					\itemmain\itms_reduce($theitem);
					$itmsk = '';
				}
				if (empty($pbx_choice) || $itms > 0)
				{
					ob_start();
					include template(MOD_SKILL981_USE_PRIZEBOX);
					$cmd = ob_get_contents();
					ob_end_clean();
				}
				return;
			}
			//剧情道具
			elseif ($itm == '梦境的前路')
			{
				$log .= "你使用了<span class=\"yellow b\">$itm</span>。<br><span class=\"red b\">你决定挑战更强的敌人。</span><br>";
				\skillbase\skill_setvalue(981,'maxstage','16',$sdata);
				cast_skill981();
				addnews($now, 'instance12_stageup1', $name, $itm);
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
				return;
			}
			elseif ($itm == '深暗幻想')
			{
				$log .= "你使用了<span class=\"yellow b\">$itm</span>。<br><span class=\"red b\">你决定挑战无边的黑暗。</span><br>";
				\skillbase\skill_setvalue(981,'maxstage','114514',$sdata);
				cast_skill981();
				addnews($now, 'instance12_stageup2', $name, $itm);
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
				return;
			}
		}
		$chprocess($theitem);
	}
	
	function get_prizebox_itemlist($itme, &$itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\itemmain\check_in_itmsk('^pbx', $itmsk)) 
		{
			$pbx_itemlist = add_prizebox_choices($itme, $itmsk);
		}
		$pbx_str = \itemmain\check_in_itmsk('^pbx', $itmsk);
		$pbx_str = \attrbase\base64_decode_comp_itmsk($pbx_str);
		$pbx_arr = explode(',',$pbx_str);
		$pbx_itemlist = array();
		eval(import_module('skill981'));
		foreach($pbx_arr as $v)
		{
			if (is_numeric($v) && isset($skill981_prizeitems[$itme][(int)$v]))
			{
				$pi = $skill981_prizeitems[$itme][(int)$v];
				$newitem = array('itm'=>$pi[0],'itmk'=>$pi[1],'itme'=>$pi[2],'itms'=>$pi[3],'itmsk'=>$pi[4]);
				if (!empty($newitem['itmsk'])) $newitem['itmsk'] = \attrbase\config_process_encode_comp_itmsk($newitem['itmsk']);
			}
			elseif (strpos($v, 's') === 0) $newitem = array('itm'=>'技能芯片','itmk'=>'VS','itme'=>1,'itms'=>1,'itmsk'=>substr($v,1));
			elseif (strpos($v, 'c') === 0) $newitem = array('itm'=>'卡片福袋','itmk'=>'VO1','itme'=>1,'itms'=>1,'itmsk'=>substr($v,1));
			$pbx_itemlist[] = $newitem;
		}
		return $pbx_itemlist;
	}
	
	function add_prizebox_choices($itme, &$itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill981'));
		if ($itme <= 16) $pbx_pool = $skill981_prizeitems[$itme];
		else $pbx_pool = $skill981_prizeitems[999];
		$pbx_arr = [];
		$c_count = min(count($pbx_pool), 6);
		for ($i = 0; $i < $c_count; $i++)
		{
			if (empty($pbx_pool)) break;
			$pbx_arr[] = get_prizeitem($pbx_pool);
		}
		$pbx_str = implode(',',$pbx_arr);
		$itmsk .= '^pbx_'.\attrbase\base64_encode_comp_itmsk($pbx_str).'1';
	}
	
	function get_prizeitem(&$arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$wtotal = array_sum(array_column($arr, 5));
		$wr = rand(1, $wtotal);
		$w = 0;
		foreach ($arr as $k => $v) {
			$w += $v[5];
			if ($wr <= $w) {
				unset($arr[$k]);
				if (strpos($v[4], 'rdsk_') === 0)
				{
					eval(import_module('item_randskills'));
					$rdsklvl = $v[4][5];
					return 's'.array_randompick($rs_cardskills[$rdsklvl]);
				}
				if (strpos($v[4], 'card_') === 0)
				{
					eval(import_module('cardbase'));
					$rare = $v[4][5];
					return 'c'.array_randompick($cardindex[$rare]);
				}
				return $k;
			}
		}
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if ($ret) {
			if (strpos($cinfo[0], '^pbx') === 0) return false;
		}
		return $ret;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'instance12_nextwave') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">第{$a}波次敌人加入了战场！</span></li>";
		elseif($news == 'instance12_stageup1') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}使用了{$b}，开始挑战更强的敌人！</span></li>";
		elseif($news == 'instance12_stageup2') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}使用了{$b}，开始挑战无边的黑暗！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>