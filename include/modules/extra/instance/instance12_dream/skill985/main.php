<?php

namespace skill985
{
	function init()
	{
		define('MOD_SKILL985_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[985] = '重置';
	}
	
	function acquire985(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(985,'rflag',0,$pa);
	}
	
	function lost985(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(985,'rflag',$pa);
	}
	
	function check_unlocked985(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos($itmk, 'Y') === 0 || strpos($itmk, 'Z') === 0)
		{
			if ($itm == '梦境礼盒' && \skillbase\skill_query(985, $sdata) && get_var_input('pbx_choice') == '-1') 
			{
				if (\skillbase\skill_getvalue(985, 'rflag', $sdata))
				{
					$log .= '这一波次你已经重置过了。<br>';
					return;
				}
				$itmsk = '';
				$log .= '<span class="yellow b">你重置了梦境礼盒的选项。</span><br>';
				\skillbase\skill_setvalue(985, 'rflag', 1, $sdata);
				ob_start();
				include template(MOD_SKILL981_USE_PRIZEBOX);
				$cmd = ob_get_contents();
				ob_end_clean();
				return;
			}
		}
		$chprocess($theitem);
	}
	
	function skill981_bonus_items(&$pa, $stage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $stage);
		if (\skillbase\skill_query(985,$pa) && check_unlocked985($pa))
		{
			\skillbase\skill_setvalue(985,'rflag',0,$pa);
		}
		return $ret;
	}

}

?>