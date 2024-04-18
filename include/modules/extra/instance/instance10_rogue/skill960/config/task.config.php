<?php

namespace skill960
{
	//适用调查度的游戏模式
	$invscore_available_mode = array(20);
	
	//各个难度的任务编号
	$tasks_index = array
	(
		1 => array(1,2,3,4,5,6,7,8,9,10,11,12,13,14),
		2 => array(31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46),
		3 => array(61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76),
		4 => array(91,92,93,94,95,96,97,98,99,100,101,102,103),
		5 => array(121,122,123,124,125,126,127,128,129,130,131,132,133),
		6 => array(151,152,153,154,155,156,157,158,159,160,161,162,163),
		7 => array(181,182,183,184,185,186,187,188,189,190,191)
	);
	
	//name：任务名
	//rank: 任务等级
	//tasktype：任务类型，'battle_kill'：击杀角色，'item_search'：提交道具，'item_use'：使用道具，'special'：特殊，关联一个其他任务技能
	//taskreq：任务条件
	//'battle_kill'类型任务条件包括：'name'：NPC名称，'type'：NPC类别，'lvl'：NPC下限等级，'wepk'：NPC武器类别，'num'：需求击杀数
	//'item_search'类型任务条件包括：'itm'：道具名称列表，'itm_match'：0(默认):严格匹配，1:包含，'itmk'：道具类别列表，'num'：需求提交道具数
	//'item_use'类型任务条件包括：'itm'：道具名称列表，'itm_match'：0(默认):严格匹配，1:包含，'itmk'：道具类别列表，'num'：需求提交道具数
	//'special'类型任务条件包括：'skillid'：任务技能编号，'lvl'：任务技能需求等级
	//reward：任务奖励，'money'：金钱，'exp'：经验，'item'：道具，'invscore'：调查度，'card'：卡片（需要模块item_uvo_extra）
	//elite：是否为精英任务，默认为0，精英任务无法被刷新
	$tasks_info = array
	(
		//1级任务
		1 => array
		(
			'name' => '小试牛刀',
			'rank' => 1,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>90,'num'=>9),
			'reward' => array('item'=>array(array('itm'=>'陈旧的大逃杀卡牌包','itmk'=>'VO9','itme'=>1,'itms'=>3,'itmsk'=>'')),'invscore'=>5),
		),
		2 => array
		(
			'name' => '武器入门·殴',
			'rank' => 1,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>90,'wepk'=>'WP','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'《殴系指南》','itmk'=>'VP','itme'=>30,'itms'=>3,'itmsk'=>'')),'card'=>array(6),'invscore'=>5),
		),
		3 => array
		(
			'name' => '武器入门·斩',
			'rank' => 1,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>90,'wepk'=>'WK','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'《斩系指南》','itmk'=>'VK','itme'=>30,'itms'=>3,'itmsk'=>'')),'card'=>array(7),'invscore'=>5),
		),
		4 => array
		(
			'name' => '武器入门·投',
			'rank' => 1,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>90,'wepk'=>'WC','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'《投系指南》','itmk'=>'VC','itme'=>30,'itms'=>3,'itmsk'=>'')),'card'=>array(8),'invscore'=>5),
		),
		5 => array
		(
			'name' => '武器入门·射',
			'rank' => 1,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>90,'wepk'=>'WG','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'《射系指南》','itmk'=>'VG','itme'=>30,'itms'=>3,'itmsk'=>'')),'card'=>array(9),'invscore'=>5),
		),
		6 => array
		(
			'name' => '武器入门·灵',
			'rank' => 1,
			'tasktype' => 'item_search',
			'taskreq' => array('itmk'=>array('WF'),'num'=>3),
			'reward' => array('item'=>array(array('itm'=>'《灵系指南》','itmk'=>'VF','itme'=>30,'itms'=>3,'itmsk'=>'')),'card'=>array(10),'invscore'=>5),
		),
		7 => array
		(
			'name' => '武器入门·爆',
			'rank' => 1,
			'tasktype' => 'item_search',
			'taskreq' => array('itmk'=>array('WD'),'num'=>3),
			'reward' => array('item'=>array(array('itm'=>'《爆系指南》','itmk'=>'VD','itme'=>30,'itms'=>3,'itmsk'=>'')),'card'=>array(11),'invscore'=>5),
		),
		8 => array
		(
			'name' => '傍身利器',
			'rank' => 1,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('方块'),'itm_match'=>1,'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'原型兵器『落星』','itmk'=>'WF','itme'=>160,'itms'=>120,'itmsk'=>'tc^alt_<:comp_itmsk:>{WP,WK,WC,WG,WD}1')),'invscore'=>5),
		),
		9 => array
		(
			'name' => '废品回收',
			'rank' => 1,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('罐子','空瓶','喷雾器罐','塑料','空罐头','鱼骨','苹果皮','香蕉皮','西瓜皮','钢琴线'),'itm_match'=>1,'num'=>3),
			'reward' => array('money'=>1800,'invscore'=>5),
		),
		10 => array
		(
			'name' => '寂寞如雪',
			'rank' => 1,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('寂寞','节操'),'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'C级技能核心','itmk'=>'SCC2','itme'=>1,'itms'=>3,'itmsk'=>'')),'invscore'=>5),
		),
		11 => array
		(
			'name' => '小道消息',
			'rank' => 1,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('提示纸条'),'itm_match'=>1,'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>''),array('itm'=>'《ACFUN大逃杀攻略》','itmk'=>'VV','itme'=>20,'itms'=>3,'itmsk'=>'')),'invscore'=>5),
		),
		12 => array
		(
			'name' => '开卷有益',
			'rank' => 1,
			'tasktype' => 'item_use',
			'taskreq' => array('itmk'=>array('VP','VK','VC','VG','VF','VD','VV','VS'),'num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>'')),'invscore'=>5),
		),
		13 => array
		(
			'name' => '药食同源',
			'rank' => 1,
			'tasktype' => 'item_use',
			'taskreq' => array('itmk'=>array('M'),'num'=>2),
			'reward' => array('item'=>array(array('itm'=>'秋刀鱼罐头','itmk'=>'HB','itme'=>70,'itms'=>30,'itmsk'=>'')),'invscore'=>5),
		),
		14 => array
		(
			'name' => '饮水思源',
			'rank' => 1,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('水','天然水','蒸馏水','杯装水'),'num'=>5),
			'reward' => array('item'=>array(array('itm'=>'矿泉水（7.5L装）','itmk'=>'HS','itme'=>240,'itms'=>25,'itmsk'=>'')),'invscore'=>5),
		),
		//2级任务
		31 => array
		(
			'name' => '战力测试1',
			'rank' => 2,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>52,'num'=>9),
			'reward' => array('item'=>array(array('itm'=>'普通的大逃杀卡牌包','itmk'=>'VO2','itme'=>1,'itms'=>4,'itmsk'=>'')),'invscore'=>5),
		),
		32 => array
		(
			'name' => '战斗训练1·殴',
			'rank' => 2,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>52,'wepk'=>'WP','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>'')),'invscore'=>5),
		),
		33 => array
		(
			'name' => '战斗训练1·斩',
			'rank' => 2,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>52,'wepk'=>'WK','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>'')),'invscore'=>5),
		),
		34 => array
		(
			'name' => '战斗训练1·投',
			'rank' => 2,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>52,'wepk'=>'WC','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>'')),'invscore'=>5),
		),
		35 => array
		(
			'name' => '战斗训练1·射',
			'rank' => 2,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>52,'wepk'=>'WG','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>'')),'invscore'=>5),
		),
		36 => array
		(
			'name' => '战斗训练1·灵',
			'rank' => 2,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>52,'wepk'=>'WF','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>'')),'invscore'=>5),
		),
		37 => array
		(
			'name' => '战斗训练1·爆',
			'rank' => 2,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>52,'wepk'=>'WD','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>'')),'invscore'=>5),
		),
		38 => array
		(
			'name' => '三秒规则',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('霜火雪糕','魔王咖喱','院长红酒','美味菜包','水果月饼','电子寿司','地雷酥糖','埃克索特三明治'),'num'=>3),
			'reward' => array('item'=>array(array('itm'=>'秋刀鱼罐头','itmk'=>'HB','itme'=>210,'itms'=>30,'itmsk'=>'')),'invscore'=>5),
		),
		39 => array
		(
			'name' => '化方为圆',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('方块'),'itm_match'=>1,'num'=>3),
			'reward' => array('item'=>array(array('itm'=>'B级技能核心','itmk'=>'SCB2','itme'=>1,'itms'=>3,'itmsk'=>'')),'invscore'=>5),
		),
		40 => array
		(
			'name' => '宝石魔法',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('方块'),'itm_match'=>1,'num'=>3),
			'reward' => array('money'=>800,'card'=>array(28),'invscore'=>5),
		),
		41 => array
		(
			'name' => '节奏大师',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itmk'=>array('HM','HT'),'num'=>2),
			'reward' => array('item'=>array(array('itm'=>'【雨だれの歌】','itmk'=>'ss','itme'=>60,'itms'=>1,'itmsk'=>'')),'card'=>array(358),'invscore'=>5),
		),
		42 => array
		(
			'name' => '不如打牌',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('游戏王'),'itm_match'=>1,'num'=>5),
			'reward' => array('item'=>array(array('itm'=>'《现代游戏王》','itmk'=>'ygo2','itme'=>1,'itms'=>10,'itmsk'=>'')),'invscore'=>5),
		),
		43 => array
		(
			'name' => '爆破技巧',
			'rank' => 2,
			'tasktype' => 'item_use',
			'taskreq' => array('itmk'=>array('T'),'num'=>5),
			'reward' => array('item'=>array(array('itm'=>'密封的酒瓶','itmk'=>'X','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		44 => array
		(
			'name' => '活学活用',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('提示纸条'),'itm_match'=>1,'num'=>2),
			'reward' => array('item'=>array(array('itm'=>'★褪色的配方纸条★','itmk'=>'R','itme'=>1,'itms'=>1,'itmsk'=>'301'),array('itm'=>'★褪色的配方纸条★','itmk'=>'R','itme'=>1,'itms'=>1,'itmsk'=>'302'),array('itm'=>'★褪色的配方纸条★','itmk'=>'R','itme'=>1,'itms'=>1,'itmsk'=>'303')),'invscore'=>5),
		),
		45 => array
		(
			'name' => '以毒攻毒',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('桔黄色的果酱','浓厚粘稠果汁'),'num'=>10),
			'reward' => array('money'=>600,'card'=>array(24),'invscore'=>5),
		),
		46 => array
		(
			'name' => '点石成金',
			'rank' => 2,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('岩石'),'num'=>4),
			'reward' => array('money'=>2400,'invscore'=>5),
		),
		//3级任务
		61 => array
		(
			'name' => '战力测试2',
			'rank' => 3,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>53,'num'=>9),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>3,'itmsk'=>'')),'invscore'=>5),
		),
		62 => array
		(
			'name' => '战斗训练2·殴',
			'rank' => 3,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>53,'wepk'=>'WP','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		63 => array
		(
			'name' => '战斗训练2·斩',
			'rank' => 3,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>53,'wepk'=>'WK','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		64 => array
		(
			'name' => '战斗训练2·投',
			'rank' => 3,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>53,'wepk'=>'WC','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		65 => array
		(
			'name' => '战斗训练2·射',
			'rank' => 3,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>53,'wepk'=>'WG','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		66 => array
		(
			'name' => '战斗训练2·灵',
			'rank' => 3,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>53,'wepk'=>'WF','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		67 => array
		(
			'name' => '战斗训练2·爆',
			'rank' => 3,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>53,'wepk'=>'WD','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'N型技能核心·改','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		68 => array
		(
			'name' => '情报交易',
			'rank' => 3,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('提示纸条'),'itm_match'=>1,'num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>5,'itmsk'=>'')),'invscore'=>5),
		),
		69 => array
		(
			'name' => '突破极限',
			'rank' => 3,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('X方块','Y方块'),'num'=>2),
			'reward' => array('item'=>array(array('itm'=>'A级技能核心·改','itmk'=>'SCA1','itme'=>1,'itms'=>2,'itmsk'=>'')),'invscore'=>5),
		),
		70 => array
		(
			'name' => '有机肥料',
			'rank' => 3,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('苹果皮','香蕉皮','西瓜皮'),'itm_match'=>1,'num'=>3),
			'reward' => array('item'=>array(array('itm'=>'★黑白色的烂苹果★','itmk'=>'HB','itme'=>666,'itms'=>66,'itmsk'=>'')),'invscore'=>5),
		),
		71 => array
		(
			'name' => '以力证道',
			'rank' => 3,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('球'),'itm_match'=>1,'num'=>6),
			'reward' => array('item'=>array(array('itm'=>'《小黄的收服特训》','itmk'=>'VC','itme'=>253,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		72 => array
		(
			'name' => '即兴料理',
			'rank' => 3,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('更改菜谱后的不甜酱包'),'num'=>1),
			'reward' => array('money'=>1200,'card'=>array(386),'invscore'=>5),
		),
		73 => array
		(
			'name' => '异端审判',
			'rank' => 3,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('汽油','火把'),'num'=>2),
			'reward' => array('item'=>array(array('itm'=>'FFF团之怒','itmk'=>'HR','itme'=>50,'itms'=>10,'itmsk'=>'')),'card'=>array(140),'invscore'=>5),
		),
		74 => array
		(
			'name' => '以酒会友',
			'rank' => 3,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('伏特加','一杯八分满的啤酒','咖啡酒','百利甜','院长红酒'),'num'=>3),
			'reward' => array('card'=>array(241,288,355),'invscore'=>5),
		),
		75 => array
		(
			'name' => '浅尝辄止',
			'rank' => 3,
			'tasktype' => 'item_use',
			'taskreq' => array('itm'=>array('肥料'),'num'=>1),
			'reward' => array('card'=>array(410,383,1112),'invscore'=>5),
		),
		76 => array
		(
			'name' => '重回旧地',
			'rank' => 3,
			'tasktype' => 'item_use',
			'taskreq' => array('itm'=>array('Untainted Glory'),'num'=>1),
			'reward' => array('money'=>233,'card'=>array(420)),
		),
		//4级任务
		91 => array
		(
			'name' => '战力测试3',
			'rank' => 4,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>54,'num'=>9),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心','itmk'=>'SCB2','itme'=>1,'itms'=>2,'itmsk'=>'')),'invscore'=>5),
		),
		92 => array
		(
			'name' => '战斗训练3·殴',
			'rank' => 4,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>54,'wepk'=>'WP','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		93 => array
		(
			'name' => '战斗训练3·斩',
			'rank' => 4,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>54,'wepk'=>'WK','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		94 => array
		(
			'name' => '战斗训练3·投',
			'rank' => 4,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>54,'wepk'=>'WC','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		95 => array
		(
			'name' => '战斗训练3·射',
			'rank' => 4,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>54,'wepk'=>'WG','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		96 => array
		(
			'name' => '战斗训练3·灵',
			'rank' => 4,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>54,'wepk'=>'WF','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		97 => array
		(
			'name' => '战斗训练3·爆',
			'rank' => 4,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>54,'wepk'=>'WD','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'B级技能核心·改','itmk'=>'SCB1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		98 => array
		(
			'name' => '歌姬计划',
			'rank' => 4,
			'tasktype' => 'item_search',
			'taskreq' => array('itmk'=>array('ss'),'num'=>1),
			'reward' => array('money'=>1200,'card'=>array(385),'invscore'=>5),
		),
		99 => array
		(
			'name' => '棋开得胜',
			'rank' => 4,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('像围棋子一样的饼干'),'num'=>1),
			'reward' => array('money'=>2000,'card'=>array(14),'invscore'=>5),
		),
		100 => array
		(
			'name' => '雷霆之握',
			'rank' => 4,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('增幅设备','某种电子零件','某种机械设备'),'num'=>3),
			'reward' => array('item'=>array(array('itm'=>'★强化电能手套★','itmk'=>'DA','itme'=>600,'itms'=>240,'itmsk'=>'Nea')),'invscore'=>5),
		),
		101 => array
		(
			'name' => '魔力共鸣',
			'rank' => 4,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('向日葵','月光碎片','魔导书'),'itm_match'=>1,'num'=>4),
			'reward' => array('item'=>array(array('itm'=>'《魔女的魔导书》','itmk'=>'X','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		102 => array
		(
			'name' => '幕后黑手',
			'rank' => 4,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('毒药'),'num'=>4),
			'reward' => array('money'=>1600,'card'=>array(21),'invscore'=>5),
		),
		103 => array
		(
			'name' => '赛博浪客',
			'rank' => 4,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('电池','探测器'),'itm_match'=>1,'num'=>4),
			'reward' => array('money'=>1600,'card'=>array(80),'invscore'=>5),
		),
		//5级任务
		121 => array
		(
			'name' => '战力测试4',
			'rank' => 5,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>55,'num'=>9),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>3,'itmsk'=>''),array('itm'=>'A级技能核心','itmk'=>'SCA2','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		122 => array
		(
			'name' => '战斗训练4·殴',
			'rank' => 5,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>55,'wepk'=>'WP','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'A级技能核心·改','itmk'=>'SCA1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		123 => array
		(
			'name' => '战斗训练4·斩',
			'rank' => 5,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>55,'wepk'=>'WK','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'A级技能核心·改','itmk'=>'SCA1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		124 => array
		(
			'name' => '战斗训练4·投',
			'rank' => 5,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>55,'wepk'=>'WC','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'A级技能核心·改','itmk'=>'SCA1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		125 => array
		(
			'name' => '战斗训练4·射',
			'rank' => 5,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>55,'wepk'=>'WG','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'A级技能核心·改','itmk'=>'SCA1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		126 => array
		(
			'name' => '战斗训练4·灵',
			'rank' => 5,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>55,'wepk'=>'WF','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'A级技能核心·改','itmk'=>'SCA1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		127 => array
		(
			'name' => '战斗训练4·爆',
			'rank' => 5,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>55,'wepk'=>'WD','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'A级技能核心·改','itmk'=>'SCA1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		128 => array
		(
			'name' => '一枪穿云',
			'rank' => 5,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('驱云弹'),'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'A级技能核心','itmk'=>'SCA2','itme'=>1,'itms'=>2,'itmsk'=>'')),'card'=>array(136),'invscore'=>5),
		),
		129 => array
		(
			'name' => '友谊魔法',
			'rank' => 5,
			'tasktype' => 'item_search',
			'taskreq' => array('itmk'=>array('WJ'),'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'★彩虹光束炮·改★','itmk'=>'WJ','itme'=>333,'itms'=>333,'itmsk'=>'uiwepo')),'invscore'=>5),
		),
		130 => array
		(
			'name' => '原始回归',
			'rank' => 5,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('MEGA宝石方块'),'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		131 => array
		(
			'name' => '年年有鱼',
			'rank' => 5,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('安康鱼','河豚鱼','凸眼鱼'),'num'=>3),
			'reward' => array('item'=>array(array('itm'=>'★咸鱼大炮★','itmk'=>'WG','itme'=>666,'itms'=>666,'itmsk'=>'ikd^ac1')),'invscore'=>5),
		),
		132 => array
		(
			'name' => '旁门左道',
			'rank' => 5,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('《BR大逃杀》','《枪械杂志》','《防身术图解》','《飞镖投掷法》','《剑道社教材》','《化学课本》','《太极拳指南》'),'num'=>2),
			'reward' => array('money'=>3000,'card'=>array(111),'invscore'=>5),
		),
		133 => array
		(
			'name' => '虹彩魔术',
			'rank' => 5,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('水晶方块'),'num'=>1),
			'reward' => array('money'=>233,'item'=>array(array('itm'=>'棱镜八面体','itmk'=>'Z','itme'=>1,'itms'=>1,'itmsk'=>'x'))),
		),
		//6级任务
		151 => array
		(
			'name' => '战力测试5',
			'rank' => 6,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>56,'num'=>9),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>3,'itmsk'=>''),array('itm'=>'S级技能核心','itmk'=>'SCS2','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		152 => array
		(
			'name' => '战斗训练5·殴',
			'rank' => 6,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>56,'wepk'=>'WP','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		153 => array
		(
			'name' => '战斗训练5·斩',
			'rank' => 6,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>56,'wepk'=>'WK','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		154 => array
		(
			'name' => '战斗训练5·投',
			'rank' => 6,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>56,'wepk'=>'WC','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		155 => array
		(
			'name' => '战斗训练5·射',
			'rank' => 6,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>56,'wepk'=>'WG','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		156 => array
		(
			'name' => '战斗训练5·灵',
			'rank' => 6,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>56,'wepk'=>'WF','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		157 => array
		(
			'name' => '战斗训练5·爆',
			'rank' => 6,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>56,'wepk'=>'WD','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>2,'itmsk'=>''),array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		158 => array
		(
			'name' => '万能灵药',
			'rank' => 6,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('大天使的气息','大夭使的气息'),'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'A级技能核心','itmk'=>'SCA2','itme'=>1,'itms'=>5,'itmsk'=>'')),'invscore'=>5),
		),
		159 => array
		(
			'name' => '以笔为剑',
			'rank' => 6,
			'tasktype' => 'item_search',
			'taskreq' => array('itmk'=>array('HB铅笔','2H铅笔'),'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'S级技能核心','itmk'=>'SCS2','itme'=>1,'itms'=>1,'itmsk'=>''),array('itm'=>'A级技能核心','itmk'=>'SCA2','itme'=>1,'itms'=>3,'itmsk'=>'')),'invscore'=>5),
		),
		160 => array
		(
			'name' => '第三只眼',
			'rank' => 6,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('小五拖鞋'),'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'《基础心理学》','itmk'=>'VS','itme'=>1,'itms'=>1,'itmsk'=>'252'),array('itm'=>'精致的大逃杀卡牌包','itmk'=>'VO3','itme'=>1,'itms'=>6,'itmsk'=>'')),'invscore'=>5),
		),
		161 => array
		(
			'name' => '假冒粉丝',
			'rank' => 6,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('签名CD'),'itm_match'=>1,'num'=>3),
			'reward' => array('item'=>array(array('itm'=>'A级技能核心·改','itmk'=>'SCA1','itme'=>1,'itms'=>4,'itmsk'=>'')),'invscore'=>5),
		),
		162 => array
		(
			'name' => '波纹呼吸',
			'rank' => 6,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('【波纹疾走】'),'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'闪耀的大逃杀卡牌包','itmk'=>'VO4','itme'=>1,'itms'=>3,'itmsk'=>'1107')),'invscore'=>5),
		),
		163 => array
		(
			'name' => '酣畅淋漓',
			'rank' => 6,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('★全图唯一的野生巨大香蕉★'),'num'=>1),
			'reward' => array('money'=>2333,'item'=>array(array('itm'=>'★艾哲的赤石★','itmk'=>'A','itme'=>1,'itms'=>1,'itmsk'=>'OB^sa6^sa5^sa4^sa3^sa2^sa1'))),
		),
		//7级任务
		181 => array
		(
			'name' => '战力测试6',
			'rank' => 7,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>57,'num'=>9),
			'reward' => array('item'=>array(array('itm'=>'闪耀的大逃杀卡牌包','itmk'=>'VO4','itme'=>1,'itms'=>3,'itmsk'=>'1113'),array('itm'=>'S级技能核心','itmk'=>'SCS2','itme'=>1,'itms'=>2,'itmsk'=>'')),'invscore'=>5),
		),
		182 => array
		(
			'name' => '战斗训练6·殴',
			'rank' => 7,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>57,'wepk'=>'WP','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'闪耀的大逃杀卡牌包','itmk'=>'VO4','itme'=>1,'itms'=>3,'itmsk'=>'1108'),array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		183 => array
		(
			'name' => '战斗训练6·斩',
			'rank' => 7,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>57,'wepk'=>'WK','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'闪耀的大逃杀卡牌包','itmk'=>'VO4','itme'=>1,'itms'=>3,'itmsk'=>'1106'),array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		184 => array
		(
			'name' => '战斗训练6·投',
			'rank' => 7,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>57,'wepk'=>'WC','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'闪耀的大逃杀卡牌包','itmk'=>'VO4','itme'=>1,'itms'=>3,'itmsk'=>'1110'),array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		185 => array
		(
			'name' => '战斗训练6·射',
			'rank' => 7,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>57,'wepk'=>'WG','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'闪耀的大逃杀卡牌包','itmk'=>'VO4','itme'=>1,'itms'=>3,'itmsk'=>'1109'),array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		186 => array
		(
			'name' => '战斗训练6·灵',
			'rank' => 7,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>57,'wepk'=>'WF','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'闪耀的大逃杀卡牌包','itmk'=>'VO4','itme'=>1,'itms'=>3,'itmsk'=>'1105'),array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		187 => array
		(
			'name' => '战斗训练6·爆',
			'rank' => 7,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>57,'wepk'=>'WD','num'=>3),
			'reward' => array('item'=>array(array('itm'=>'闪耀的大逃杀卡牌包','itmk'=>'VO4','itme'=>1,'itms'=>3,'itmsk'=>'1104'),array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'invscore'=>5),
		),
		188 => array
		(
			'name' => '诡异的光',
			'rank' => 7,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('仰望星空派'),'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'S级技能核心','itmk'=>'SCS2','itme'=>1,'itms'=>2,'itmsk'=>'')),'invscore'=>5),
		),
		189 => array
		(
			'name' => '鸭力测试',
			'rank' => 7,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('南京挂花鸭'),'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'闪耀的大逃杀卡牌包','itmk'=>'VO4','itme'=>1,'itms'=>7,'itmsk'=>'1106')),'invscore'=>5),
		),
		190 => array
		(
			'name' => '钻石！',
			'rank' => 7,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('钻石'),'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'传说中的大逃杀卡牌包','itmk'=>'VO5','itme'=>1,'itms'=>3,'itmsk'=>'')),'invscore'=>5),
		),
		191 => array
		(
			'name' => '深渊之盒',
			'rank' => 7,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('残存的礼品盒'),'num'=>1),
			'reward' => array('money'=>5200,'card'=>array(403),'invscore'=>5),
		),
		//精英任务，肉鸽boss
		//npc资源暂未完成
		301 => array
		(
			'name' => '挑战者I',
			'rank' => 99,
			'elite' => 1,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>62,'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'A级技能核心·改','itmk'=>'SCA1','itme'=>1,'itms'=>1,'itmsk'=>'')),'money'=>3000),
		),
		302 => array
		(
			'name' => '挑战者II',
			'rank' => 99,
			'elite' => 1,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>63,'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'S级技能核心·改','itmk'=>'SCS1','itme'=>1,'itms'=>1,'itmsk'=>'')),'money'=>6000),
		),
		303 => array
		(
			'name' => '挑战者III',
			'rank' => 99,
			'elite' => 1,
			'tasktype' => 'battle_kill',
			'taskreq' => array('type'=>64,'num'=>1),
			'reward' => array('item'=>array(array('itm'=>'测试用结局道具·解禁','itmk'=>'Z','itme'=>1,'itms'=>1,'itmsk'=>'')),'money'=>12000),
		),
		//1001号之后的任务为卡片任务
		1001 => array
		(
			'name' => '废品增速',
			'rank' => 233,
			'tasktype' => 'item_search',
			'taskreq' => array('itm'=>array('罐子','空瓶','喷雾器罐','塑料','空罐头','鱼骨','苹果皮','香蕉皮','西瓜皮','钢琴线'),'itm_match'=>1,'num'=>20),
			'reward' => array('money'=>2500,'qiegao'=>666),
		),
		1002 => array
		(
			'name' => '饕餮盛宴',
			'rank' => 233,
			'tasktype' => 'item_use',
			'taskreq' => array('itm'=>array('肥料','雪','「机关傀儡 梦魇」 ★8','『我是说在座的各位都是垃圾』'),'num'=>3),
			'reward' => array('item'=>array(array('itm'=>'会员制大餐','itmk'=>'HB','itme'=>1919,'itms'=>810,'itmsk'=>'O'))),
		),
	);
}

?>
