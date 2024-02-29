<?php

namespace skill456
{
	$skill456_act_time = 360;
	
	function init() 
	{
		define('MOD_SKILL456_INFO','card;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[456] = '突击';
		$bufficons_list[456] = Array(
			'disappear' => 1,
		);
	}
	
	function acquire456(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill456'));
		if ($now < $starttime + $skill456_act_time) {
			\skillbase\skill_setvalue(456,'start_ts',$starttime,$pa);
			\skillbase\skill_setvalue(456,'end_ts',$starttime+$skill456_act_time,$pa);	
			\skillbase\skill_setvalue(456,'cd_ts',0,$pa);
		}else{
			\skillbase\skill_lost(456,$pa);
		}
	}
	
	function lost456(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked456(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(456,$pa)) 
		{
			if (1 == \bufficons\bufficons_check_buff_state(456,$pa) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.='<span class="yellow b">你抱着破釜沉舟之心，对敌人打出致命一击！</span><br>';
				else  $log.='<span class="yellow b">敌人抱着破釜沉舟之心，对你打出致命一击！</span><br>';
				$r=Array(1.4);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>