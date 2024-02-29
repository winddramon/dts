<?php

namespace skill1013
{
	function init() 
	{
		define('MOD_SKILL1013_INFO','active;unique;');
		eval(import_module('clubbase'));
		$clubskillname[1013] = '禁录';
	}
	
	function acquire1013(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost1013(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked1013(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill1013_sub_page()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		include template(MOD_SKILL1013_SUB_PAGE);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	function skill1013_acquire_skill($skillid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','clubbase','logger'));

		if(empty($skillid)) {
			$log .= '变量名错误！<br>';
			$mode = 'command';$command = '';
			return;
		}
		if(\skillbase\skill_query($skillid)) {
			$log .= '你已经拥有本技能了！<br>';
			$mode = 'command';$command = '';
			return;
		}
		\skillbase\skill_acquire($skillid);
		if(!empty($clubskillname[$skillid])) {
			$log .= '<span class="cyan b">你学会了技能：'.$clubskillname[$skillid].'！</span><br>';
		}else{
			$log .= '<span class="cyan b">你学会了'.$skillid.'号无名技能！</span><br>';
		}
		
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
	
		if ($mode == 'special' && $command == 'skill1013_special') 
		{
			if (!\skillbase\skill_query(1013)) 
			{
				$log.='你没有这个技能。';
				$mode = 'command';$command = '';
				return;
			}
			$subcmd = get_var_input('subcmd');
			if(!isset($subcmd)){
				$mode = 'command';$command = '';
				return;
			}elseif($subcmd == 'sub_page') {
				skill1013_sub_page();
				return;
			}elseif($subcmd == 'acquire'){
				$skillid = get_var_input('skillid');
				skill1013_acquire_skill($skillid);
				return;
			}else{
				$log .= '命令参数错误。';
				$mode = 'command';$command = '';
				return;
			}
		}
		$chprocess();
	}
}

?>