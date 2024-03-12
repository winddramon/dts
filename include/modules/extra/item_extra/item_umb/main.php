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