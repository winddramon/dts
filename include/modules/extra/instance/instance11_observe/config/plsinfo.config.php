<?php

namespace instance11
{
	//地点数据，地点编号 => 地点名
	$plsinfo11 = Array(
		101 => '作战会议室',
		102 => '蓝凝的卧室',
		103 => '蓝凝的展示室',
		104 => '洗手间',
	);

	//我真傻，真的，我单知道import_module会自动识别namespace里定义的局部变量，却不知道import_module进来的也算是局部变量……
	//言归正传，不允许在函数外部进行import_module！
	//因为在函数外面的变量都会被import_module识别成namespace的局部变量，包括import_module自己导入进来的也是如此！
	//轻则导致变量引用关系错乱，重则循环引用等不可预料的问题

	// eval(import_module('map'));

	// if(!empty($xyinfo)) {
	// 	$xyinfo += $xyinfo11;
	// 	$areainfo += $areainfo11;
	// }
}

?>