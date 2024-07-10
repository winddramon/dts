<?php

namespace skill753
{
	function init()
	{
		define('MOD_SKILL753_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[753] = '连携';
	}
	
	function acquire753(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(54, 'lvl', '0', $pa);
	}
	
	function lost753(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(54, 'lvl', $pa);
	}
	
	function check_unlocked753(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill753_get_wepchange_list($wep, $wepsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$weplist = array();
		$wep_o = $wep;
		while (1)
		{
			if (!\itemmain\check_in_itmsk('j', $wepsk)) return $weplist;
			$wobj = \wepchange\get_weaponswap_obj($wep);
			if (empty($wobj)) return $weplist;
			list($null,$wep,$wepk,$wepe,$weps,$wepsk) = $wobj;
			if ($wep == $wep_o) return $weplist;
			if (strpos($wepk,'W') !== 0) return $weplist;
			if (\itemmain\check_in_itmsk('J', $wepsk)) return $weplist;
			$newwep = array($wep,$wepk,$wepe,$weps,$wepsk);
			if (in_array($newwep, $weplist)) return $weplist;
			$weplist[] = $newwep;
		}
	}
	
	function attack(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(753,$pa) && check_unlocked753($pa))
		{
			$clv = (int)\skillbase\skill_getvalue(753, 'lvl', $pa);
			if ($clv == 0)
			{
				$skill753_weplist = skill753_get_wepchange_list($pa['wep'], $pa['wepsk']);
				if (!empty($skill753_weplist))
				{
					eval(import_module('logger','weapon'));
					$wep_o = $pa['wep'];
					$wepk_o = $pa['wepk'];
					$wepe_o = $pa['wepe'];
					$weps_o = $pa['weps'];
					$wepsk_o = $pa['wepsk'];
					$pa['skill753_flag'] = 1;
					foreach ($skill753_weplist as $v)
					{
						$pa['wep'] = $v[0];
						$pa['wepk'] = $v[1];
						$pa['wepe'] = $v[2];
						$pa['weps'] = $v[3];
						$pa['wepsk'] = $v[4];
						$log.="<span class=\"cyan b\">追加攻击！</span><br>";
						if($weps_o != $nosta && $pa['weps'] == $nosta) $pa['wep_kind'] = \weapon\get_attack_method($pa);
						$chprocess($pa,$pd,$active);
					}
					$pa['wep'] = $wep_o;
					$pa['wepk'] = $wepk_o;
					$pa['wepe'] = $wepe_o;
					$pa['weps'] = $weps_o;
					$pa['wepsk'] = $wepsk_o;
				}
			}
			else
			{
				$skill753_arr = \skill81\check_swapable_items81($pa, 'W');
				if (!empty($skill753_arr))
				{
					eval(import_module('logger','weapon'));
					$pa['skill753_flag'] = 1;
					foreach ($skill753_arr as $v)
					{
						\skill81\swapitem81($pa, 'wep', $v);
						$log.="<span class=\"cyan b\">追加攻击！</span><br>";
						\weapon\get_attack_method($pa);
						$chprocess($pa,$pd,$active);
					}
				}
			}
		}
	}
	
	//不显示耐久扣减log
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$temp_log = $log;
        $chprocess($pa, $pd, $active);
		if (isset($pa['skill753_flag'])) $log = $temp_log;
	}

}

?>