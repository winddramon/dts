<?php

namespace ex_deflect
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^dfl'] = '借势';
		$itemspkdesc['^dfl'] = '攻击时攻击力会附加敌人总攻击力的<:skn:>%，效果可叠加';
		$itemspkremark['^dfl'] = '具体附加比例视装备而定';
	}
	
	function get_att_change(&$pa,&$pd,$active,$att)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $att);
		if (empty($pd['pid'])) return $ret;
		$dfl_rate = \attrbase\check_in_itmsk('^dfl', \attrbase\get_ex_attack_array($pa, $pd, $active), 1);
		if (false !== $dfl_rate)
		{
			$att_e = \weapon\get_att_base($pd, $pa, $active);
			$attup = round($att_e * $dfl_rate / 100);
			if ($attup > 0)
			{
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"yellow b\">你借着敌人的攻势发起了更猛烈的攻击！</span><br>";
				else $log .= "<span class=\"yellow b\">敌人借着你的攻势发起了更猛烈的攻击！</span><br>";
				$ret += $attup;
			}
		}
		return $ret;
	}
	
}

?>
