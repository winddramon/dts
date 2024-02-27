<?php

namespace ex_alternative
{
	$tmp_ex_alternative_atype = 0;//暂时储存多态类型用于判定

	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^alt'] = '多态';//现在不显示了
		$itemspkdesc['^alt'] = '这一道具也能当做<:skn:>使用';
		$itemspkremark['^alt'] = '游戏中不会显示。<br>在使用时会额外显示一个列表，让玩家决定当做哪个类别、名称或属性使用。';
		$itemspkinfo['^atype'] = '可改变哪一项';//不显示，0:类别；1:名称；2:属性
		$itemspkdesc_help['^alt'] = '这一道具能当做其他类别、名称或属性使用';
		$itemspkinfo['^ahid'] = '是否隐藏文字显示并打乱';//不显示，1:仅隐藏，2:隐藏并打乱
	}
	
	//$rand表示是否打乱，默认为不打乱
	function get_altlist($itmsk, $rand = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//该函数没有类别或属性是否合法的检测
		$alts = \itemmain\check_in_itmsk('^alt', $itmsk);
		if (empty($alts)) return array();
		$alts = \attrbase\base64_decode_comp_itmsk($alts);
		$altlist = explode(',', $alts);
		if ($rand)
		{
			$keys = range(0, count($altlist) - 1);
			shuffle($keys);
			$altlist = array_combine($keys, $altlist);
		}
		return $altlist;
	}
	
	//encode前形似WC,WP,WK这样，用半角逗号分割
	//属性切换不需要在切换的属性字符串里写^alt和^atype
	function alt_change(&$theitem, $atype, $idx = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itm=&$theitem['itm'];
		$itmk=&$theitem['itmk'];
		$itmsk=&$theitem['itmsk'];
		if (2 == $atype) $key = 'itmsk';
		elseif (1 == $atype) $key = 'itm';
		else $key = 'itmk';
		$altlist = get_altlist($itmsk);
		if (!empty($altlist))
		{
			$itmsk = \itemmain\replace_in_itmsk('^alt','',$itmsk);
			if (2 == $atype) $itmsk = \itemmain\replace_in_itmsk('^atype','',$itmsk);
			swap($theitem[$key], $altlist[$idx]);
			$alts = implode(',', $altlist);
			$itmsk .= '^alt_'.\attrbase\base64_encode_comp_itmsk($alts).'1';
			if (2 == $atype) $itmsk .= '^atype2';
		}
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		//如果是装着箭的弓或者装着外甲的防具，不会触发变化
		if (!\itemmain\check_in_itmsk('^ari', $itmsk) && !\itemmain\check_in_itmsk('^su', $itmsk) && \itemmain\check_in_itmsk('^alt', $itmsk)) 
		{
			$alternative_choice = get_var_in_module('alternative_choice', 'input');
			if (empty($alternative_choice))
			{
				ob_start();
				include template(MOD_EX_ALTERNATIVE_USE_ALTERNATIVE);
				$cmd = ob_get_contents();
				ob_end_clean();	
				return;
			}
			else
			{
				$ahid = (int)\itemmain\check_in_itmsk('^ahid', $itmsk);
				$rand = $ahid==2 ? 1 : 0;
				if (1 == $alternative_choice)
				{
					$chprocess($theitem);
				}
				else
				{
					$altlist = get_altlist($itmsk, $rand);
					if ($alternative_choice > count($altlist) + 1)
					{
						$log .= '参数不合法。<br>';
						$mode = 'command';
						return;
					}
					$atype = (int)\itemmain\check_in_itmsk('^atype', $itmsk);
					if (!$ahid)
					{
						$altwords = get_altwords($altlist[$alternative_choice-2], $atype, 1);
						$log .= "你把<span class=\"yellow b\">$itm</span>当做了<span class=\"yellow b\">$altwords</span>使用。<br>";
					}
					alt_change($theitem, $atype, $alternative_choice - 2);
					$chprocess($theitem);
				}
			}
		}
		else $chprocess($theitem);
	}
	
	//$atype: 0:类别；1:名称；2:属性，$suf: 是否加后缀（属性、类别），默认不显示，$hid: 是否隐藏，默认不隐藏
	function get_altwords($alts, $atype, $suf = 0, $hid = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($hid) return array_randompick(array('■','■■','■■■','■■■■'));
		if (2 == $atype)
		{
			//先把变化属性抹掉
			$alts = \itemmain\replace_in_itmsk('^alt','',$alts);
			//可以切换成白板道具，真会出现这样的情况吗？总之先考虑进去了
			$altwords = !empty($alts) ? \itemmain\parse_itmsk_words($alts,1) : '';
			if (empty($altwords)) $altwords = '无';
			if ($suf) $altwords .= '属性';
		}
		elseif (1 == $atype)
		{
			$altwords = $alts;
		}
		else
		{
			$altwords = \itemmain\parse_itmk_words($alts);
			if ($suf) $altwords .= '类别';
		}
		return $altwords;
	}	
	
	//显示多态信息
	function get_itmsk_desc_single_comp_process($skk, $skn, $sks)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skn = $chprocess($skk, $skn, $sks);
		if(strpos($skk, '^alt')===0) {
			eval(import_module('ex_alternative'));
			if ($tmp_ex_alternative_atype == 4) return "<span class='yellow b' style='font-size:12px;'>■■■</span>";
			$skarr = explode(',',\attrbase\base64_decode_comp_itmsk($sks));
			$sknarr = Array();
			foreach($skarr as $v){
				switch($tmp_ex_alternative_atype){
					case 1:
						$sknarr[] = $v;
						break;
					case 2:
						$sknarr[] = \itemmain\get_itmsk_words_single($v);
						break;
					default:
						$sknarr[] = \itemmain\parse_itmk_words($v);
					break;
				}
			}
			$skn = "<span class='yellow b' style='font-size:12px;'>".implode(' ', $sknarr)."</span>";
			//$skn = \itemmain\parse_itmk_words($sks);
		}
		return $skn;
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		$ret = $chprocess($cinfo);
		if ($ret) {
			//if (strpos($cinfo[0], '^alt') === 0) return false;
			if ('^atype' == $cinfo[0]) return false;
			if ('^ahid' == $cinfo[0]) return false;
		}
		return $ret;
	}
	
	//在处理单个道具的itmsk显示时，预先记录atype。
	//出于性能考虑和避免潜在的无限嵌套问题，单纯用字符串判定而非check_in_itmsk()
	function parse_itmsk_desc($sk_value){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$i = strpos($sk_value, '^ahid');
		if(false !== $i) {
			eval(import_module('ex_alternative'));
			$tmp_ex_alternative_atype = 4;
		}
		else
		{
			$i = strpos($sk_value, '^atype');
			if(false !== $i) {
				eval(import_module('ex_alternative'));
				$tmp_ex_alternative_atype = (int)substr($sk_value, $i+6, 1);
			}
		}

		$ret = $chprocess($sk_value);

		if (!empty($tmp_ex_alternative_atype)) {
			$tmp_ex_alternative_atype = 0;
		}
		
		return $ret;
	}
}

?>
