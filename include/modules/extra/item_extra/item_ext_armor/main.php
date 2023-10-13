<?php

namespace item_ext_armor
{
	function init() {
		eval(import_module('itemmain'));
		$iteminfo['DBS'] = '身体外甲';
		$iteminfo['DHS'] = '头部外甲';
		$iteminfo['DAS'] = '手臂外甲';
		$iteminfo['DFS'] = '腿部外甲';		
		$itemspkinfo['^su'] = '外甲';
		$itemspkdesc['^su'] = '当前装备外甲的信息为：<:skn:>';
		$itemspkinfo['^are'] = '原防具效果值';//不会显示
		$itemspkinfo['^ars'] = '原防具耐久值';//不会显示
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if (strpos(substr($k, 2), 'S') !== false)
		{
			$ret .= '这一防具可以叠加装备在相同位置的防具上';
		}
		return $ret;
	}
	
	//显示外甲信息
	function get_itmsk_desc_single_comp_process($skk, $skn, $sks) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skn = $chprocess($skk, $skn, $sks);
		if(strpos($skk, '^su')===0) {
			$skarr = explode(',',\attrbase\base64_decode_comp_itmsk($sks));
			$itm = $skarr[0];
			$itmk_words = \itemmain\parse_itmk_words($skarr[1]);
			$itme = $skarr[2];
			$itms = $skarr[3];
			$itmsk_words = \itemmain\parse_itmsk_words($skarr[4]);
			$skn = $itm.'/'.$itmk_words.'/'.$itme.'/'.$itms.(!empty($itmsk_words) ? '/'.$itmsk_words : '');
		}
		return $skn;
	}
	
	//使用外甲时，如果防具已经包含外甲，则替换此外甲；如果防具不包含外甲，则使该防具装备外甲；如果未装备防具，则直接作为防具穿上
	function use_armor(&$theitem, $pos = '')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','armor','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
				
		if(!$pos) {
			if(strpos ( $itmk, 'DB' ) === 0) {
				$pos = 'arb';
				$noeqp = 'DN';
			}elseif(strpos ( $itmk, 'DH' ) === 0) {
				$pos = 'arh';
				$noeqp = '';
			}elseif(strpos ( $itmk, 'DA' ) === 0) {
				$pos = 'ara';
				$noeqp = '';
			}elseif(strpos ( $itmk, 'DF' ) === 0) {
				$pos = 'arf';
				$noeqp = '';
			}
		}		
		
		//如果要穿的外甲也包含外甲，则会替换当前防具
		if (false !== strpos(substr($itmk,2),'S') && !\itemmain\check_in_itmsk('^su', $itmsk))
		{
			if ((!empty($noeqp) && strpos(${$pos.'k'}, $noeqp) !== 0) || ${$pos.'s'})
			{
				$aritm = array('itm' => &${$pos}, 'itmk' => &${$pos.'k'}, 'itme' => &${$pos.'e'},'itms' => &${$pos.'s'},'itmsk' => &${$pos.'sk'});
				$moveto = array('itm' => &$itm0, 'itmk' => &$itmk0, 'itme' => &$itme0,'itms' => &$itms0,'itmsk' => &$itmsk0);
				
				$result = armor_remove_su($aritm, $moveto);
				
				${$pos.'sk'} = armor_put_su(${$pos.'sk'}, $theitem);
				$itmsk_arr = \itemmain\get_itmsk_array($itmsk);
				if (!empty($itmsk_arr)){
					${$pos.'sk'} .= '|'.implode('', $itmsk_arr).'|';
				}
				
				//根据装备外甲后，损耗的是效果还是耐久，记录效果值或耐久值
				if ($itms === $nosta)
				{
					${$pos.'sk'} .= '^are'.${$pos.'e'};
				}
				else
				{
					if (${$pos.'s'} === $nosta)
					{
						${$pos.'sk'} .= '^ars'.'0';
						${$pos.'s'} = $itms;
					}				
					else
					{
						${$pos.'sk'} .= '^ars'.${$pos.'s'};
						${$pos.'s'} += $itms;
					}
				}			
				${$pos.'e'} += $itme;
							
				if (1 === $result)
				{	
					$log .= "你脱下了<span class=\"yellow b\">$itm0</span>，然后在<span class=\"yellow b\">${$pos}</span>外面套上了<span class=\"yellow b\">$itm</span>。<br>";
					\itemmain\itemget();
				}
				else $log .= "你在<span class=\"yellow b\">${$pos}</span>外面套上了<span class=\"yellow b\">$itm</span>。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
				return;
			}
		}	
		$chprocess($theitem, $pos);
	}
	
	//防具受损时，检测外甲是否完全损坏
	function armor_hurt(&$pa, &$pd, $active, $which, $hurtvalue)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('armor','wound','logger'));
		if (in_array($which,$armor_equip_list) && isset($pd[$which.'e']) && $pd[$which.'e']>0)	//有防具
		{
			$suitem = armor_get_su($pd[$which.'sk']);
			if ($suitem)
			{
				$su = $suitem['itm'];
				$suk = $suitem['itmk'];
				$sue = $suitem['itme'];
				$sus = $suitem['itms'];
				$susk = $suitem['itmsk'];
				$su_break_flag = 0;
				$are = \itemmain\check_in_itmsk('^are', $pd[$which.'sk']);
				if (false !== $are)
				{
					if ($hurtvalue >= $pd[$which.'e'] - $are)
					{
						$x = max($pd[$which.'e'] - $are, 1);
						$su_break_flag = 1;
					}
					$pd[$which.'e'] -= $x;
					if ($active)
					{
						$log .= "{$pd['name']}的外甲".$su."的效果值下降了{$x}！<br>";
					}
					else
					{
						$log .= "你的外甲".$su."的效果值下降了{$x}！<br>";
					}					
					if (1 === $su_break_flag)
					{
						suit_break($pa, $pd, $active, $which);
					}
				}
				else
				{
					$ars = \itemmain\check_in_itmsk('^ars', $pd[$which.'sk']);
					if (false === $ars) $ars = 1;
					if (0 === (int)$ars)
					{
						$x = min($pd[$which.'s'], $hurtvalue);
						$pd[$which.'s'] -= $x;
						if ($pd[$which.'s']<=0)
						{
							$pd[$which.'s'] = $nosta;
							$pd[$which.'e'] -= $sue;
							if ($pd[$which.'e'] < 0) $pd[$which.'e'] = 0;
							if ($active)
							{
								$log .= "{$pd['name']}的外甲".$su."的耐久度下降了{$x}！<br>";
							}
							else
							{
								$log .= "你的外甲".$su."的耐久度下降了{$x}！<br>";
							}
							suit_break($pa, $pd, $active, $which);
						}
					}
					else
					{		
						if ($hurtvalue >= $pd[$which.'s'] - $ars)
						{
							$x = max($pd[$which.'s'] - $ars, 1);
							$su_break_flag = 1;
						}
						if ($active)
						{
							$log .= "{$pd['name']}的外甲".$su."的耐久度下降了{$x}！<br>";
						}
						else
						{
							$log .= "你的外甲".$su."的耐久度下降了{$x}！<br>";
						}
						$pd[$which.'s'] -= $x;					
						if (1 === $su_break_flag)
						{
							$pd[$which.'e'] -= $sue;
							if ($pd[$which.'e'] < 0) $pd[$which.'e'] = 0;
							suit_break($pa, $pd, $active, $which);
						}
					}
				}
				return $x;
			}
		}
		$chprocess($pa, $pd, $active, $which, $hurtvalue);
	}
	
	//外甲损坏的效果和耐久值处理在armor_hurt中完成
	function suit_break(&$pa, &$pd, $active, $whicharmor)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		
		eval(import_module('logger'));
		$suitem = armor_get_su($pd[$whicharmor.'sk']);
		if ($active)
		{
			$log .= "{$pd['name']}的外甲<span class=\"red b\">".$suitem['itm']."</span>受损过重，无法再装备了！<br>";
			$pd['armorbreaklog'] .= "你的外甲<span class=\"red b\">".$suitem['itm']."</span>受损过重，无法再装备了！<br>";
		}
		else  $log .= "你的外甲<span class=\"red b\">".$suitem['itm']."</span>受损过重，无法再装备了！<br>";
		
		$pd[$whicharmor.'sk'] = \itemmain\replace_in_itmsk('^su', '', $pd[$whicharmor.'sk']);
		$pd[$whicharmor.'sk'] = \itemmain\replace_in_itmsk('^are', '', $pd[$whicharmor.'sk']);
		$pd[$whicharmor.'sk'] = \itemmain\replace_in_itmsk('^ars', '', $pd[$whicharmor.'sk']);
		armor_clean_suit_sk($pd[$whicharmor.'sk']);		
	}
	
	//卸下防具时，如果防具包含外甲，则优先卸下外甲
	function itemoff($item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if (strpos($item, 'ar') === 0)
		{
			eval(import_module('player','logger','armor'));
			$itmn = substr($item,2,1);
			$itm = & ${'ar'.$itmn};
			$itmk = & ${'ar'.$itmn.'k'};
			$itme = & ${'ar'.$itmn.'e'};
			$itms = & ${'ar'.$itmn.'s'};
			$itmsk = & ${'ar'.$itmn.'sk'};
			
			if(!\itemmain\itemoff_valid_check($itm, $itmk, $itme, $itms, $itmsk))
			{
				$mode = 'command';
				return;
			}
			
			$aritm = array('itm' => &${'ar'.$itmn}, 'itmk' => &${'ar'.$itmn.'k'}, 'itme' => &${'ar'.$itmn.'e'},'itms' => &${'ar'.$itmn.'s'},'itmsk' => &${'ar'.$itmn.'sk'});
			$moveto = array('itm' => &$itm0, 'itmk' => &$itmk0, 'itme' => &$itme0,'itms' => &$itms0,'itmsk' => &$itmsk0);
			
			$result = armor_remove_su($aritm, $moveto);
			if (1 === $result)
			{
				$log .= "你卸下了外甲<span class=\"yellow b\">$itm0</span>。<br>";
				\itemmain\itemget();
				return;
			}
		}
		$chprocess($item);
	}
		
	//在尸体上拾取防具时，如果包含外甲，则仅拾取外甲
	function getcorpse_action(&$edata, $item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		if(strpos($item,'ar') === 0)
		{
			$aritm = array('itm' => &$edata[$item], 'itmk' => &$edata[$item.'k'], 'itme' => &$edata[$item.'e'],'itms' => &$edata[$item.'s'],'itmsk' => &$edata[$item.'sk']);
			$moveto = array('itm' => &$itm0, 'itmk' => &$itmk0, 'itme' => &$itme0,'itms' => &$itms0,'itmsk' => &$itmsk0);
			
			$result = armor_remove_su($aritm, $moveto);
			if (1 === $result)
			{
				\itemmain\itemget();
				$mode = 'command';
				return;
			}
		}
		$chprocess($edata, $item);
	}
	
	function armor_get_su($itmsk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = Array();
		$sus = \itemmain\check_in_itmsk('^su', $itmsk);
		if(empty($sus)) return $ret;
		$sus = \attrbase\base64_decode_comp_itmsk($sus);
		
		if(!empty($sus)) {
			$suarr = explode(',', $sus);
			$ret = Array(
				'itm' => $suarr[0],
				'itmk' => $suarr[1],
				'itme' => $suarr[2],
				'itms' => $suarr[3],
				'itmsk' => $suarr[4]
			);
		}
		return $ret;
	}
	
	function armor_put_su($itmsk, $suarr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$nowsus = \itemmain\check_in_itmsk('^su', $itmsk);
		if(!empty($nowsus)) {
			$itmsk = \itemmain\replace_in_itmsk('^su','',$itmsk);
		}
		if(!empty($suarr)){
			$sus = $suarr['itm'].','.$suarr['itmk'].','.$suarr['itme'].','.$suarr['itms'].','.$suarr['itmsk'];
			$itmsk .= '^su_'.\attrbase\base64_encode_comp_itmsk($sus).'1';
		}
		return $itmsk;
	}
	
	//将一件防具上的外甲移动到另一个道具位，通常用于卸下外甲到itm0
	function armor_remove_su(&$itm, &$moveto){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('armor'));
		$suitem = armor_get_su($itm['itmsk']);			
		if ($suitem)
		{
			armor_clean_suit_sk($itm['itmsk']);
			$itm['itmsk'] = \itemmain\replace_in_itmsk('^su','',$itm['itmsk']);	
			
			$moveto['itm'] = $suitem['itm'];
			$moveto['itmk'] = $suitem['itmk'];
			$moveto['itme'] = $suitem['itme'];
			$moveto['itms'] = $suitem['itms'];
			$moveto['itmsk']= $suitem['itmsk'];
			$are = \itemmain\check_in_itmsk('^are', $itm['itmsk']);
			//如果记录了原防具的效果值，则先恢复防具效果到记录值
			if (false !== $are)
			{
				$moveto['itme'] = max($itm['itme'] - $are, 0);
				$itm['itme'] = $are;
				$itm['itmsk'] = \itemmain\replace_in_itmsk('^are','',$itm['itmsk']);
			}
			else
			{
				$itm['itme'] -= $moveto['itme'];
				if ($itm['itme'] < 0) $itm['itme'] = 0;
				//如果记录了原防具的耐久值，则先恢复防具耐久到记录值
				$ars = \itemmain\check_in_itmsk('^ars', $itm['itmsk']);
				if (false === $ars) $ars = 1;
				if (0 === (int)$ars)
				{
					$moveto['itms'] = $itm['itms'];
					$itm['itms'] = $nosta;
				}
				else
				{
					$moveto['itms'] = max($itm['itms'] - $ars, 1);
					$itm['itms'] = $ars;
				}
				$itm['itmsk'] = \itemmain\replace_in_itmsk('^ars','',$itm['itmsk']);
			}
			return 1;
		}
		return 0;
	}
	
	function armor_clean_suit_sk(&$itmsk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(strpos($itmsk,'|')===false) return '';
		if(substr_count($itmsk, '|') % 2) $itmsk .= '|';
		preg_match('/\|.*\|/s',$itmsk,$matches);
		$ret = '';
		if(!empty($matches)) {
			$itmsk = preg_replace('/\|.*?\|/s','',$itmsk);
			$ret = substr($matches[0], 1, -1);
		}
		return $ret;
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if($ret) {
			if('^are' == $cinfo[0]) return false;
			if('^ars' == $cinfo[0]) return false;
		}
		return $ret;
	}
	
	//外甲道具名的显示
	function parse_item_words($edata, $simple = 0, $elli = 0)	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($edata, $simple, $elli);
		eval(import_module('itemmain','player'));
		if($edata['pid'] == $pid) $selflag = 1;
		foreach($equip_list as $pos) {
			if(strpos($pos,'itm')===0) $suitem = armor_get_su($edata['itmsk'.substr($pos, 3)]);
			else $suitem = armor_get_su($edata[$pos.'sk']);
			if(!empty($suitem)) {
				$suitem_name = $suitem['itm'];
				$itm = \itemmain\parse_itmname_words($suitem_name, $elli);
				$itm_short = \itemmain\parse_itmname_words($suitem_name, 1, 15);
				$ret[$pos.'_words'] = $itm . (!empty($selflag) ? '<br>' : '') . '(' . $ret[$pos.'_words'] . ')'; //如果是玩家界面的调用，换个行
				$ret[$pos.'_words_short'] = $itm_short . (!empty($selflag) ? '<br>' : '') . '(' . $ret[$pos.'_words_short'] . ')';
			}
		}
		return $ret;
	}
	
	//NPC载入时，如果存在外甲数据，自动装上外甲

}

?>