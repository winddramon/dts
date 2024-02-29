<?php

namespace ex_equipskill
{
	function init()
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^eqpsk'] = '秘传';
		$itemspkdesc['^eqpsk'] = "装备时视为拥有技能<span class='yellow b' style='font-size:12px;'>「<:skn:>」</span>";
		$itemspkremark['^eqpsk'] = '该属性仅在装备上时有效';
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$itmk=&$theitem['itmk']; $itmsk=&$theitem['itmsk'];
		
		$eqpsk_id = (int)\itemmain\check_in_itmsk('^eqpsk', $itmsk);
		if ((in_array($itmk[0], array('W','D','A'))) && $eqpsk_id)
		{
			$eqpsk_flag = 1;
		}
		$chprocess($theitem);
		if (!empty($eqpsk_flag))
		{
			eval(import_module('clubbase'));
			if (defined('MOD_SKILL'.$eqpsk_id) && !empty($clubskillname[$eqpsk_id]) && !\skillbase\skill_query($eqpsk_id, $sdata))
			{
				//是否成功装备上
				$dummy = \player\create_dummy_playerdata();
				$skarr = \attrbase\get_ex_def_array($dummy, $sdata, 0);
				if (in_array('^eqpsk'.$eqpsk_id, $skarr))
				{
					eval(import_module('logger'));
					\skillbase\skill_acquire($eqpsk_id, $sdata);
					\skillbase\skill_setvalue($eqpsk_id, 'eqpsk_flag', 1, $sdata);
					$log .= "你现在能使用技能<span class=\"yellow b\">「{$clubskillname[$eqpsk_id]}」</span>了！";
				}
			}
		}
	}
	
	//如果装备中不再有该属性则失去此技能
	function check_skill_available($skillid, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($skillid, $pa);
		if (\skillbase\skill_query($skillid, $pa) && !empty(\skillbase\skill_getvalue($skillid, 'eqpsk_flag', $pa)))
		{
			$dummy = \player\create_dummy_playerdata();
			$skarr = \attrbase\get_ex_def_array($dummy, $pa, 0);
			if (!in_array('^eqpsk'.$skillid, $skarr))
			{
				\skillbase\skill_lost($skillid, $pa);
				\skillbase\skill_delvalue($skillid, 'eqpsk_flag', $pa);
			}
		}
	}
	
	//显示装备技能信息
	function get_itmsk_desc_single_comp_process($skk, $skn, $sks)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(strpos($skk, '^eqpsk')===0)
		{
			eval(import_module('clubbase'));
			if (defined('MOD_SKILL'.$skn) && !empty($clubskillname[$skn])) return $clubskillname[$skn];
			return '';
		}
		return $chprocess($skk, $skn, $sks);
	}
	
}

?>
