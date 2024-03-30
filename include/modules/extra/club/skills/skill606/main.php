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
	}
	
}

?>