<?php

namespace logistics
{
	//L5成功奖励
	function addnews($t = 0, $n = '',$a='',$b='',$c = '', $d = '', $e = '')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($t, $n, $a, $b, $c, $d, $e);
		if ($n == 'suisidefail')
		{
			eval(import_module('player'));
			$pt = '这个包裹上没有署名的样子……';
			$prizecode = 'getlogitem_303;getlogitemnum_1;';
			include_once './include/messages.func.php';
			message_create($name, '神秘快递', $pt, $prizecode);
		}
	}
	
}

?>