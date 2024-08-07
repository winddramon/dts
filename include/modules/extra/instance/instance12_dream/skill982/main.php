<?php

namespace skill982
{
	function init()
	{
		define('MOD_SKILL982_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[982] = '梦蚀';
	}
	
	function acquire982(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(982,'lvl','0',$pa);
	}
	
	function lost982(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked982(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_shopiteminfo($item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($item);
		if (\skillbase\skill_query(982))
		{
			$r = 1.2;
			if ((int)\skillbase\skill_getvalue(982, 'lvl') > 0) $r = 1.25;
			$ret['price']=round($ret['price']*$r);
		}
		return $ret;
	}
	
	function prepare_shopitem($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($sn);
		if (\skillbase\skill_query(982))
		{
			$r = 1.2;
			if ((int)\skillbase\skill_getvalue(982, 'lvl') > 0) $r = 1.25;
			for ($i=0; $i<count($ret); $i++)
				$ret[$i]['price']=round($ret[$i]['price']*$r);
		}
		return $ret;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(982, $pa) && ((int)\skillbase\skill_getvalue(982, 'lvl', $pa) > 0))
		{
			eval(import_module('logger'));
			if ($active) $log .= "<span class=\"red b\">「梦蚀」使敌人受到的最终伤害降低了10%！</span><br>";
			else $log .= "<span class=\"red b\">「梦蚀」使敌人造成的最终伤害降低了10%！</span><br>";
			$r = array(0.9);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		if (\skillbase\skill_query(982, $pa) && ((int)\skillbase\skill_getvalue(982, 'lvl', $pa) > 0))
		{
			\skillbase\skill_setvalue(952, 'itmarr', '', $pa);
			for ($i=3; $i<=6; $i++)
			{
				$pa['itm'.$i] = $pa['itmk'.$i] = $pa['itmsk'.$i] = '';
				$pa['itme'.$i] = $pa['itms'.$i] = 0;
			}
		}
	}
	
}

?>