<?php

namespace skill754
{
	function init() 
	{
		define('MOD_SKILL754_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[754] = '敕令';
	}
	
	function acquire754(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost754(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked754(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function apply_total_damage_modifier_seckill(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(754,$pa) && check_unlocked754($pa))
		{
			if ($pa['wepe'] < 200 && rand(0,99) < 15 && $pa['is_hit'])
			{
				$pa['dmg_dealt']=$pd['hp'];
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"red b\">“啊♂乖乖站好”</span><br>";
				else $log .= "<span class=\"red b\">“YES♂SIR”</span><br>";
				$pa['seckill'] = 1;
			}
		}
		$chprocess($pa,$pd,$active);
	}
}

?>