<?php

namespace skill981
{
	//每一个等级的敌人配置，共11级
	//每个数组中键名为随机敌人的等级（randnpc模块中的rank），键值为刷新数量
	$skill981_enemies = array(
		1 => array(3=>3,4=>1),
		2 => array(4=>3,5=>1),
		3 => array(5=>3,6=>1),
		4 => array(6=>3,7=>1),
		5 => array(7=>3,8=>1),
		6 => array(8=>3,9=>1),
		7 => array(9=>2,10=>1),
		8 => array(10=>2,11=>1),
		9 => array(11=>2,12=>1),
		10 => array(12=>2,13=>1),
		11 => array(13=>2,14=>1)
	);
	
	
	//所有等级的奖励道具列表，共20级
	//道具的数值依次为：名称、类别、效果值、耐久值、属性、权重
	//特殊的道具属性：rdsk_S、rdsk_A、rdsk_B、rdsk_C表示对应等级的一个随机技能；card_S、card_A、card_B、card_C表示对应等级的一张随机卡片，在生成奖励时确定
	$skill981_prizeitems = array(
		1 => array(
			array('食堂的盒饭','HR',50,1,'',1),
			array('矿泉水','HS',100,1,'',1),
			array('体力回复药','HS',200,1,'',1),
			array('圆形罐头','HS',400,1,'',1),
		),
		2 => array(
			array('食堂的盒饭','HR',50,1,'',1),
			array('矿泉水','HS',100,1,'',1),
			array('体力回复药','HS',200,1,'',1),
			array('圆形罐头','HS',400,1,'',1),
		),
		3 => array(
			array('食堂的盒饭','HR',50,1,'',1),
			array('矿泉水','HS',100,1,'',1),
			array('体力回复药','HS',200,1,'',1),
			array('圆形罐头','HS',400,1,'',1),
		),
		4 => array(
			array('食堂的盒饭','HR',50,1,'',1),
			array('矿泉水','HS',100,1,'',1),
			array('体力回复药','HS',200,1,'',1),
			array('圆形罐头','HS',400,1,'',1),
		),
		5 => array(
			array('食堂的盒饭','HR',50,1,'',1),
			array('矿泉水','HS',100,1,'',1),
			array('体力回复药','HS',200,1,'',1),
			array('圆形罐头','HS',400,1,'',1),
		),
		6 => array(
			array('食堂的盒饭','HR',50,1,'',1),
			array('矿泉水','HS',100,1,'',1),
			array('体力回复药','HS',200,1,'',1),
			array('圆形罐头','HS',400,1,'',1),
		),
		7 => array(
			array('食堂的盒饭','HR',50,1,'',1),
			array('矿泉水','HS',100,1,'',1),
			array('体力回复药','HS',200,1,'',1),
			array('圆形罐头','HS',400,1,'',1),
		),
		8 => array(
			array('食堂的盒饭','HR',50,1,'',1),
			array('矿泉水','HS',100,1,'',1),
			array('体力回复药','HS',200,1,'',1),
			array('圆形罐头','HS',400,1,'',1),
		),
		9 => array(
			array('食堂的盒饭','HR',50,1,'',1),
			array('矿泉水','HS',100,1,'',1),
			array('体力回复药','HS',200,1,'',1),
			array('圆形罐头','HS',400,1,'',1),
		),
		10 => array(
			array('食堂的盒饭','HR',50,1,'',1),
			array('矿泉水','HS',100,1,'',1),
			array('体力回复药','HS',200,1,'',1),
			array('圆形罐头','HS',400,1,'',1),
		),
		11 => array(
			array('游戏解除钥匙','Y',1,1,'',1),
		),
	);
	
}

?>