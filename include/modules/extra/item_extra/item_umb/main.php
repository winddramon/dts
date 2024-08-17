<?php

namespace item_umb
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['MB'] = '状态药物';
		$itemspkinfo['^mbid'] = '状态药物技能编号';//实际上这个是不会显示的
		$itemspkinfo['^mbtime'] = '状态药物技能时效';//这个也是不会显示的
		$itemspkinfo['^mblvl'] = '状态药物技能等级';//这个还是不会显示的
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if (strpos($k, 'MB') === 0)
		{
			eval(import_module('clubbase'));
			$flag = \attrbase\check_in_itmsk('^mbid', $sk);
			if(!empty($flag)) {
				$buff_id = (int)$flag;
			}
			elseif(is_numeric($sk)) {
				$buff_id = (int)$sk;
			}
			if(!empty($clubskillname[$buff_id])){
				$if_temp = '';
				$buff_lvl = (int)\attrbase\check_in_itmsk('^mblvl', $sk);
				$buff_time = (int)\attrbase\check_in_itmsk('^mbtime', $sk);
				if ($buff_time) $if_temp = '临时';
				$ret .= '使用后获得'.$if_temp.'技能「'.$clubskillname[$buff_id].'」';
				if ($buff_time) $ret .= '（'.$buff_time.'秒）';
				//技能描述
				if ($buff_id == 400){
					$ret .= '：造成物理伤害有概率增加';
				}elseif ($buff_id == 401) {
					$ret .= '：受到物理伤害减少';
				}elseif ($buff_id == 404) {
					$ret .= '：生命值在50%以下时攻击附加固定物理伤害';
				}elseif ($buff_id == 461) {
					$ret .= '：免疫时效性负面状态和异常状态';
				}elseif ($buff_id == 710) {
					$ret .= '：解除使用熟练技能书或熟练药物的衰减';
				}elseif ($buff_id == 806) {
					$ret .= '：视为具有'.\skill806\skill806_attrtext($buff_lvl).'属性';
				}elseif ($buff_id == 984 && $buff_lvl == 1) {
					$ret .= '：完成每一波次50%获得1个第1波次的梦境礼盒';
				}elseif ($buff_id == 984 && $buff_lvl == 2) {
					$ret .= '：完成每一波次50%获得1个改造核心，且随波次数增加有更高概率出现高等级的改造核心';
				}elseif ($buff_id == 984 && $buff_lvl == 3) {
					$ret .= '：完成每一波次50%获得1个卡牌包，且随波次数增加有更高概率出现高等级的卡牌包';
				}
			}
		}
		return $ret;
	}

	function itemuse_um(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm = &$theitem['itm'];
		$itmk = &$theitem['itmk'];
		$itmsk = &$theitem['itmsk'];
		
		//状态药物MB，效果是获得时效buff类的技能
		if (0 === strpos($itmk, 'MB'))
		{
			eval(import_module('logger'));
			
			$log .= "你服用了<span class=\"red b\">$itm</span>。<br>";
			
			//建议把技能编号以^mbidXXX的形式记录，如果非时效技能，可额外设置^mbtimeXXX表示获得该技能多长时间
			$flag = \attrbase\check_in_itmsk('^mbid', $itmsk);
			if(!empty($flag)) {
				$buff_id = (int)$flag;
			}
			elseif(is_numeric($itmsk)) {//兼容数字属性，但不建议使用
				$buff_id = (int)$itmsk;
			}
			
			if ($buff_id < 1) $buff_id = 1;
			
			//注意仅适用于非时效技能，否则可能有怪问题
			$buff_time = \attrbase\check_in_itmsk('^mbtime', $itmsk);
			//技能等级
			$buff_lvl = \attrbase\check_in_itmsk('^mblvl', $itmsk);
			\skill_temp\skill_temp_acquire($buff_id, $buff_time, $buff_lvl);
			
			\itemmain\itms_reduce($theitem);
		}
		else $chprocess($theitem);
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if($ret) {
			if('^mbid' == $cinfo[0]) return false;
			if('^mbtime' == $cinfo[0]) return false;
			if('^mblvl' == $cinfo[0]) return false;
		}
		return $ret;
	}

}

?>