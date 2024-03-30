<?php

namespace skill606
{
	function init() 
	{
		define('MOD_SKILL606_INFO','hidden;debuff;card;');
		eval(import_module('clubbase'));
		$clubskillname[606] = '反转';
	}
	
	function acquire606(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost606(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$uip = & get_var_in_module('uip','sys');
		$uip['effect']['class_changeto'] = Array('rotater_2', 'rotater');
	}
	
	function parse_interface_gameinfo()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		if(\skillbase\skill_query(606))
		{
			$uip = & get_var_in_module('uip','sys');
			$uip['effect']['class_changeto'] = Array('rotater', 'rotater_2');
		}
	}
	
}

?>