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
		\skillbase\skill_setvalue(753, 'lvl', '0', $pa);
	}
	
	function lost753(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(753, 'lvl', $pa);
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
						$log .= "<span class=\"cyan b\">追加攻击！</span><br>";
						$pa['wep_kind'] = \weapon\get_attack_method($pa);
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
					eval(import_module('logger'));
					$pa['skill753_flag'] = 1;
					foreach ($skill753_arr as $v)
					{
						\skill81\swapitem81($pa, 'wep', $v);
						if(strpos($pa['itmk'.$v] , 'WN') === 0 || !$pa['itms'.$v])
						{
							$pa['itm'.$v] = $pa['itmk'.$v] = $pa['itmsk'.$v] = '';
							$pa['itme'.$v] = $pa['itms'.$v] = 0;
						}
						$log .= "<span class=\"cyan b\">追加攻击！</span><br>";
						$pa['wep_kind'] = \weapon\get_attack_method($pa);
						$chprocess($pa,$pd,$active);
					}
					$v = $skill753_arr[0];
					if(strpos($pa['itmk'.$v] , 'WN') !== 0 && $pa['itms'.$v])
					{
						swap($pa['wep'], $pa['itm'.$v]);
						swap($pa['wepk'], $pa['itmk'.$v]);
						swap($pa['wepe'], $pa['itme'.$v]);
						swap($pa['weps'], $pa['itms'.$v]);
						swap($pa['wepsk'], $pa['itmsk'.$v]);
						if(strpos($pa['itmk'.$v] , 'WN') === 0 || !$pa['itms'.$v])
						{
							$pa['itm'.$v] = $pa['itmk'.$v] = $pa['itmsk'.$v] = '';
							$pa['itme'.$v] = $pa['itms'.$v] = 0;
						}
						for ($i=1; $i<count($skill753_arr); $i++)
						{
							$v1 = $skill753_arr[$i-1];
							$v2 = $skill753_arr[$i];
							swap($pa['itm'.$v1], $pa['itm'.$v2]);
							swap($pa['itmk'.$v1], $pa['itmk'.$v2]);
							swap($pa['itme'.$v1], $pa['itme'.$v2]);
							swap($pa['itms'.$v1], $pa['itms'.$v2]);
							swap($pa['itmsk'.$v1], $pa['itmsk'.$v2]);
						}
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