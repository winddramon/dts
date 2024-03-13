<?php

namespace club28
{
	function init()
	{
		eval(import_module('clubbase'));
		$clubinfo[28] = '六魂天火';
		$clublist[28] = Array(
			'type' => 1,
			'probability' => 100,
			'skills' => Array(
				10,11,12,
				114,115,
				116,117,118,119,120,121,
			)
		);
	}
}

?>
