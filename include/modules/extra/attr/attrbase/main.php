<?php

namespace attrbase
{
	function init() {}
	
	//下面这两个获取属性的函数规则如下：
	//添加：请在get_ex_XXX_array_core()里使用array_push()
	//删除/改变：请在get_ex_XXX_array()里删除或者直接赋值
	//也就是说，删除的效果一定覆盖添加的效果，至于删除怎么判定再说
	
	//获取防御属性列表（全部战斗装备）
	function get_ex_def_array(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return get_ex_def_array_core($pa, $pd, $active);
	}
	
	function get_ex_def_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$ret = Array();
		foreach ($battle_equip_list as $itm)
			foreach (\itemmain\get_itmsk_array($pd[$itm.'sk']) as $key)
				array_push($ret,$key);
				
		return $ret;
	}
	
	//获取攻击属性列表（武器防具和饰品）
	function get_ex_attack_array(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return get_ex_attack_array_core($pa, $pd, $active);
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (attr_dmg_check_not_WPG($pa, $pd, $active))
			$ret = \itemmain\get_itmsk_array($pa['wepsk']);
		else $ret = array();
		
		if (defined('MOD_ARMOR'))
		{		
			eval(import_module('armor'));
			foreach ($armor_equip_list as $itm)
				foreach (\itemmain\get_itmsk_array($pa[$itm.'sk']) as $key)
					array_push($ret,$key);	
		}
		
		if (defined('MOD_ARMOR_ART'))
		{
			$ret = array_merge($ret,\itemmain\get_itmsk_array($pa['artsk']));
			//奇葩的饰品特判…… 木有办法……
			if ($pa['artk']=='Al') array_push($ret,'l');
			if ($pa['artk']=='Ag') array_push($ret,'g');
		}
		return $ret;
	}
	
	//判定武器是否作为本系属性使用，名字是遗留问题	
	function attr_dmg_check_not_WPG(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//必须作为本系武器使用才有属性伤害（枪械当钝器没有）
		return (strpos($pa['wepk'],$pa['wep_kind'])!==false);
	}
	
	//从单属性字段，获得复合属性的代号和数值。非复合属性返回NULL
	//返回两个变量：^+字母部分和数字部分
	//如果^+字母部分有下划线，那么把下划线后的部分作为第三个变量返回
	function get_comp_itmsk_info($str){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!is_string($str) || '^' != $str[0]) return NULL;
		$skk = $skn = '';
		for($i=1;$i<strlen($str);$i++) {
			if('0'<=$str[$i] && $str[$i]<='9') {
				if(empty($skk)) return NULL;
				else $skn .= $str[$i];
			}else{
				$skk .= $str[$i];
			}
		}
		$skk = '^'.$skk;
		$sks = '';
		if(strpos($skk, '_')!==false) list($null, $sks) = explode('_', $skk, 2);
		return array($skk, $skn, $sks);
	}
	
	//判断属性代号$ssk是不是与$mark一致。其中$ssk是从具体道具属性字段里取出的，$mark是需要判定是否存在的（复合属性时，$mark应只有^字母）
	function check_itmsk_single_mark($ssk, $mark)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$flag = false;
		if($ssk === $mark) $flag = true;
		elseif(strpos($mark, '^')===0 && strpos($ssk, $mark)===0) $flag = true;
		return $flag;
	}
	
	//判定一个属性数组里是不是有给定的属性代号
	//输入$mark是用来判定的单字母属性或者以^字母为形式的复合属性前缀
	//输入$skarr是已经经过get_itmsk_array()处理的属性数组，直接输入属性字符串也会自动转换
	//如果$count==1，则会统计属性总数。
	//如果属性不存在，返回false；存在则返回1；如果是复合属性或者要统计总数，会返回具体数值；如果有记录特殊变量，则返回特殊变量。
	
	function check_in_itmsk($mark, $skarr, $count = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(strpos($mark, '^')===0) {
			if(!is_array($skarr)) $skarr = \itemmain\get_itmsk_array($skarr);
			
			//判定是不是合法的复合属性
			$compret = get_comp_itmsk_info($mark);
			if(NULL !== $compret) {
				$flag = false;
				$skk = $compret[0];
				foreach($skarr as $v) {
					//真正判定，对属性数组中的每一个变量，判定头部是否相等
					if (check_itmsk_single_mark($v, $skk)) {
						list($null, $skn, $sks) = get_comp_itmsk_info($v);
						//返回值默认是数字部分
						$flag = !$flag ? $skn : $flag + $skn;
						//如果get_comp_itmsk_info()的第三个返回值非空（记录特殊变量）那么丢弃数字部分，返回第三个返回值（此时$count失效）
						if(!empty($sks)) {
							$flag = $sks;
							$count = 0;
						}
						if(!$count) break;
					}
				}
				return $flag;
			}
		}
		
		return $chprocess($mark, $skarr, $count);
	}
	
	//检查$pa是否具有$nm属性，如$pa为NULL则检查当前玩家
	//警告：本函数不供战斗使用！！！！！本函数只应当被用来检查非战斗相关属性！！！
	function check_itmsk($nm, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if ($pa == NULL)
		{
			foreach ($battle_equip_list as $itm)
				foreach (\itemmain\get_itmsk_array(${$itm.'sk'}) as $key)
					if (check_itmsk_single_mark($key, $nm))
						return 1;
			return 0;
		}
		else
		{
			foreach ($battle_equip_list as $itm)
				foreach (\itemmain\get_itmsk_array($pa[$itm.'sk']) as $key)
					if (check_itmsk_single_mark($key, $nm))
						return 1;
			return 0;
		}
	}
	
	//使itmsk描述增加对复合属性的支持
	function get_itmsk_words_single($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($str);
		
		if(empty($ret)){
			$cinfo = get_comp_itmsk_info($str);
			if(!empty($cinfo) && check_comp_itmsk_visible($cinfo)) {
				eval(import_module('itemmain'));
				$ck = $cinfo[0];
				if(strpos($ck, '_')!==false) $ck = explode('_', $ck)[0];
				if(!empty($itemspkinfo[$ck])) $ret = $itemspkinfo[$ck];
				else $ret = '';
			}
		}
		
		return $ret;
	}
	
	//使itmsk描述(悬浮显示)增加对复合属性的支持
	function get_itmsk_desc_single($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($str);
		
		if(empty($ret)){
			$cinfo = get_comp_itmsk_info($str);
			if(!empty($cinfo) && check_comp_itmsk_visible($cinfo)) {
				eval(import_module('itemmain'));
				$ck = $cinfo[0];
				if(strpos($ck, '_')!==false) $ck = explode('_', $ck)[0];
				$ret = str_replace('<:skn:>', get_itmsk_desc_single_comp_process($ck, $cinfo[1], $cinfo[2]), $itemspkdesc[$ck]);
			}
		}
		
		return $ret;
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return true;
	}
	
	//对复合属性数值的处理接口，某些需要调整显示的功能可以继承这个
	function get_itmsk_desc_single_comp_process($skk, $skn, $sks) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $skn;
	}
	
	//把任意字符串转换为可以嵌入复合属性的非标准base64字符串（用其他符号一一替代数字，但不含已经被base64使用的+ / =）
	function base64_encode_comp_itmsk($str){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$dict = ')!@#$%-&*(';//对应键盘数字键的上标符号，其中^替换成-
		$ret = base64_encode($str);
		for($i=0;$i<=9;$i++){
			$ret = str_replace($i, $dict[$i], $ret);
		}
		return $ret;
	}
	
	//对应的逆运算
	function base64_decode_comp_itmsk($str){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$dict = ')!@#$%-&*(';//对应键盘数字键的上标符号，其中^替换成-
		for($i=0;$i<=9;$i++){
			$str = str_replace($dict[$i], $i, $str);
		}
		$ret = base64_decode($str);
		return $ret;
	}
}

?>