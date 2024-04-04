<?php

namespace randrecipe
{
	//随机配方素材的作用：'main'：主素材，'sub'：副素材，'itmsk'：提供属性的额外素材，'itme'：提供效果值的额外素材，'itms'：提供耐久值的额外素材
	
	//随机名称生成
	$randrecipe_resultname = array(
		'rand_prefix' => array('光辉的','神圣的','不朽的','巨大的','奥术的','古老的','魔力的','自然的','迷人的','罕见的'),
		'E_prefix' => array('神秘','沉默','毁灭','黑暗','荣耀','诡异','闪耀','幽灵','贪婪','恐怖','狡猾','腐蚀','机械','平凡','狂野','幻觉','无畏'),
		'H_prefix' => array('美味','恶臭','香脆','难以下咽','闪耀','柔滑','清新','浓郁','热辣','冰凉','酸甜','香辣','温暖','果味'),
		'WP' => array('铁锤','皮鞭','手杖'),
		'WK' => array('大刀','长剑','利刃','刺枪'),
		'WG' => array('手枪','火铳','火箭炮'),
		'WC' => array('雪球','飞镖','卡牌'),
		'WD' => array('炸弹','爆弹','地雷'),
		'WF' => array('符卡','魔弹','节杖'),
		'WB' => array('猎弓','角弓','箭雨'),
		'WJ' => array('巨炮','狙击枪','重枪'),
		'DB' => array('盔甲','大衣','西装','战甲B'),
		'DH' => array('头盔','护目镜','风帽','战甲H'),
		'DA' => array('盾牌','利爪','手套','战甲A'),
		'DF' => array('跑鞋','皮靴','尾巴','战甲F'),
		'A' => array('挂件','项链','插件','饰品A'),
		'HH' => array('面包','伤药','压缩饼干'),
		'HS' => array('糊糊','伤药','混合物'),
		'HB' => array('秘药','伤药','罐头')
	);
	
	//合成产物的基础属性池
	$randrecipe_itmsk_list = Array
	(
		'W' => array('u','e','i','w','p','u','e','i','w','p','c','c','d','N'),
		'D' => array('P','K','C','G','D','F','U','E','I','W','q','M','a','A','c','H'),
		'A' => array('c','H','M','A','a'),
	);
	
	//合成产物的素材提供属性池
	$randrecipe_stuff_itmsk_list = Array
	(
		'W' => array('u','e','i','w','p','u','c','d','N','n','r'),
		'D' => array('P','K','C','G','D','F','U','E','I','W','q','M','a','A','c','H','B','b'),
		'A' => array('c','H','M','A','a','B','b'),
	);
	
	//合成产物的奖励属性池，效果值达到键名的阈值后有概率额外抽取
	$randrecipe_bonus_itmsk_list = Array
	(
		'W' => array(500=>array('d','n','y','L'), 1000=>array('f','k','t','^ac1','Z'), 3000=>array('v','B','b'), 100000=>array('V')),
		'D' => array(1000=>array('B','b','^wc1'), 3000=>array('h','Z')),
		'H' => array(300=>array('Z')),//会有人强化补给？
	);
	
	//主素材要求
	//x为数字，'+x'表示增加效果与耐久值x点，'-x'表示减少效果与耐久值x点，'*x'表示效果与耐久值变为x倍
	$main_stuff = array
	(
		'WP' => array('itmk'=>array('WP'=>''), 'itm'=>array('拳'=>'*1.2','棍棒'=>'+30','锤'=>'+50','伞'=>'+10','钟摆'=>'+30','球棒'=>'+40','半身像'=>'+20')),
		'WK' => array('itmk'=>array('WK'=>''), 'itm'=>array('刀'=>'*1.5','刃'=>'*2','剑'=>'*2','水果刀'=>'+30','羽毛'=>'+15','镰刀'=>'+80','太刀'=>'+80','匕首'=>'+80')),
		'WC' => array('itmk'=>array('WC'=>''), 'itm'=>array('球'=>'+30','镖'=>'+90','团子'=>'+1','青蛙'=>'+9','岩石'=>'+40','麻将'=>'+320')),
		'WG' => array('itmk'=>array('WG'=>''), 'itm'=>array('枪'=>'+20','炮'=>'+50','喷雾器罐'=>'+30','激光'=>'+10','RPG'=>'+80')),
		'WD' => array('itmk'=>array('WD'=>''), 'itm'=>array('打火机'=>'+20','信管'=>'+30','导火线'=>'+30','炸弹'=>'+50','爆竹'=>'+30','鞭炮'=>'+30')),
		'WF' => array('itmk'=>array('WF'=>''), 'itm'=>array('空白符卡'=>'','方块'=>'+25','波纹'=>'+35','心灵'=>'+50')),
		'DB' => array('itmk'=>array('DB'=>''), 'itm'=>array('针线包'=>'+5','死库水'=>'+5','背心'=>'+30','迷彩'=>'+120','内裤'=>'+240','睡衣'=>'+120','铠甲'=>'+240')),
		'DH' => array('itmk'=>array('DH'=>''), 'itm'=>array('针线包'=>'+5','帽'=>'+30','镜'=>'+50','头盔'=>'+80','面具'=>'+80','发圈'=>'+60')),
		'DA' => array('itmk'=>array('DA'=>''), 'itm'=>array('针线包'=>'+5','手套'=>'+30','盾'=>'+80','伞'=>'+10','戒指'=>'+160')),
		'DF' => array('itmk'=>array('DF'=>''), 'itm'=>array('针线包'=>'+5','鞋'=>'+30','靴'=>'+40','袜'=>'+70')),
		'A' => array('itmk'=>array('A'=>''), 'itm'=>array('方块'=>'','荣光'=>'','护符'=>'','玩偶'=>'','饰品'=>'')),
		'HH' => array('itmk'=>array('HH'=>'','HS'=>''), 'itm'=>array('面包'=>'+5','药'=>'*1.5','团子'=>'','雏菊'=>'+30','包子'=>'+60','蘑菇'=>'+50','豆腐'=>'+5')),
		'HS' => array('itmk'=>array('HH'=>'','HS'=>''), 'itm'=>array('水'=>'+5','药'=>'*1.5','团子'=>'','雏菊'=>'+30','牛奶'=>'+30','妹汁'=>'+90','饮料'=>'+20','豆腐'=>'+5')),
		'HB' => array('itmk'=>array('HH'=>'','HS'=>'','HB'=>''), 'itm'=>array('面包'=>'+5','水'=>'+5','药'=>'*1.5','团子'=>'','雏菊'=>'+30','咖喱'=>'+80','盒饭'=>'+50','豆腐'=>'+5'))
	);
	
	//副素材要求
	//x为数字，'+x'表示增加效果与耐久值x点，'-x'表示减少效果与耐久值x点，'*x'表示效果与耐久值变为x倍
	$sub_stuff = array
	(
		'WP' => array('itmk'=>array('WP'=>'+20','WK'=>'+20','WD'=>'+20'), 'itm'=>array('拳'=>'*1.2','棍棒'=>'+30','钉'=>'+10','钟摆'=>'+30','球棒'=>'+40','半身像'=>'+20')),
		'WK' => array('itmk'=>array('WP'=>'+20','WK'=>'+20'), 'itm'=>array('刀'=>'*1.5','剑'=>'*2','磨刀石'=>'+10','水果刀'=>'+30','羽毛'=>'+15','镰刀'=>'+80','太刀'=>'+80','匕首'=>'+80')),
		'WC' => array('itmk'=>array('WC'=>'+20','ygo'=>'+30'), 'itm'=>array('球'=>'+40','镖'=>'+80','团子'=>'+1','青蛙'=>'+9')),
		'WG' => array('itmk'=>array('WG'=>'+20'), 'itm'=>array('枪'=>'+20','炮'=>'+30','喷雾器罐'=>'+30','电子'=>'+30','激光'=>'+10','RPG'=>'+80')),
		'WD' => array('itmk'=>array('WD'=>'+20','T'=>'+20'), 'itm'=>array('伏特加'=>'+20','信管'=>'+30','导火线'=>'+30','地雷'=>'+10')),
		'WF' => array('itmk'=>array('WF'=>'+20','V'=>'+50'), 'itm'=>array('方块'=>'+30','弹幕'=>'+20','波纹'=>'+35','心灵'=>'+50')),
		'DB' => array('itmk'=>array('DB'=>'+20'), 'itm'=>array('校服'=>'+50','死库水'=>'+10','背心'=>'+30','迷彩'=>'+120','内裤'=>'+240','睡衣'=>'+120')),
		'DH' => array('itmk'=>array('DH'=>'+20'), 'itm'=>array('镜'=>'+50','帽'=>'+20','头盔'=>'+80','面具'=>'+80','发圈'=>'+60')),
		'DA' => array('itmk'=>array('DA'=>'+20'), 'itm'=>array('装甲'=>'+20','手套'=>'+20','盾'=>'+80','伞'=>'+10','戒指'=>'+160')),
		'DF' => array('itmk'=>array('DF'=>'+20'), 'itm'=>array('裤'=>'+20','鞋'=>'+20','靴'=>'+40','袜'=>'+70')),
		'A' => array('itmk'=>array('A'=>''), 'itm'=>array('方块'=>'','荣光'=>'','护符'=>'')),
		'HH' => array('itmk'=>array('HH'=>'+20','HS'=>'+20'), 'itm'=>array('面包'=>'+5','水'=>'+5','药'=>'+10','果'=>'+10','团子'=>'','雏菊'=>'+30','包子'=>'+60','蘑菇'=>'+50','豆腐'=>'+5')),
		'HS' => array('itmk'=>array('HH'=>'+20','HS'=>'+20'), 'itm'=>array('水'=>'+5','药'=>'+5','果'=>'+10','酒'=>'+10','团子'=>'','雏菊'=>'+30','牛奶'=>'+30','妹汁'=>'+120','饮料'=>'+20','豆腐'=>'+5')),
		'HB' => array('itmk'=>array('HH'=>'+20','HS'=>'+20','HB'=>'+30'), 'itm'=>array('面包'=>'+5','水'=>'+5','药'=>'+10','果'=>'+10','酒'=>'+15','团子'=>'','雏菊'=>'+30','咖喱'=>'+80','盒饭'=>'+50','豆腐'=>'+5'))
	);
	
	//属性素材要求，单纯的提供属性
	$itmsk_stuff = array
	(
		'N' => array('itmsk'=>array('N'), 'itm'=>array('棍棒','太鼓','巨大'), 'itmk'=>array('WD','WP')),
		'n' => array('itmsk'=>array('n'), 'itm'=>array('针','刀','剑'), 'itmk'=>array('WK','ER')),
		'y' => array('itmsk'=>array('y'), 'itm'=>array('破坏','星尘'), 'itmk'=>array('WF','GA')),
		'r' => array('itmsk'=>array('r'), 'itm'=>array('激光','震荡'), 'itmk'=>array('WG')),
		'u' => array('itmsk'=>array('u'), 'itm'=>array('火','油','恶魔')),
		'i' => array('itmsk'=>array('i'), 'itm'=>array('冰','雪','死灵','寂寞','⑨')),
		'w' => array('itmsk'=>array('w'), 'itm'=>array('弹','音波'), 'itmk'=>array('HM','HT')),
		'e' => array('itmsk'=>array('e'), 'itm'=>array('电','脉冲'), 'itmk'=>array('B','EE')),
		'p' => array('itmsk'=>array('p'), 'itm'=>array('毒','垃圾','蘑菇','僵尸','肥料')),
		'd' => array('itm'=>array('地雷','炸弹','魔法','波纹'),'itmsk'=>array('d'), 'itmk'=>array('WD')),
		'P' => array('itm'=>array('针线包','方块','装甲','锅')),
		'K' => array('itm'=>array('针线包','方块','装甲','锅')),
		'C' => array('itm'=>array('针线包','方块','装甲','手套')),
		'G' => array('itm'=>array('针线包','方块','装甲','防弹','背心')),
		'F' => array('itm'=>array('针线包','方块','装甲','埃克法')),
		'D' => array('itm'=>array('针线包','方块','装甲','金属')),
		'A' => array('itmsk'=>array('A'), 'itm'=>array('宝石方块','希望')),
		'B' => array('itmsk'=>array('B'), 'itm'=>array('悲叹之种','斗篷')),
		'U' => array('itm'=>array('针线包','方块','装甲','节操')),
		'E' => array('itm'=>array('针线包','方块','装甲','节操')),
		'I' => array('itm'=>array('针线包','方块','装甲','节操')),
		'W' => array('itm'=>array('针线包','方块','装甲','节操')),
		'q' => array('itm'=>array('针线包','方块','装甲','节操')),
		'a' => array('itmsk'=>array('a'), 'itm'=>array('宝石方块','梦想')),
		'b' => array('itmsk'=>array('b'), 'itm'=>array('悲叹之种','天使'),'itmk'=>array('ss')),
		'M' => array('itmsk'=>array('M'), 'itm'=>array('翅膀','羽毛'), 'itmk'=>array('DF','EE','ER','U')),
		'c' => array('itmsk'=>array('c'), 'itm'=>array('埃克法'), 'itmk'=>array('GA','DA')),
		'H' => array('itmsk'=>array('H'), 'itm'=>array('布偶','领主'), 'itmk'=>array('DA','HM')),
		'Z' => array('itmsk'=>array('Z'), 'itm'=>array('『祝福宝石』', '脸'))
	);
		
	//效果素材要求
	//x为数字，'+x'表示增加效果值x点，'-x'表示减少效果值x点，'*x'表示效果值变为x倍，'u'表示类别强化（射变重枪，投变弓）
	$itme_stuff = array
	(
		'WP' => array('itm'=>array('钉'=>'+20','机械'=>'*2','丝带'=>'*4','键盘'=>'*1.5'),'itmsk'=>array('N'=>'*1.3')),
		'WK' => array('itm'=>array('磨刀石'=>'+20','丝带'=>'*4','英雄'=>'*3'),'itmsk'=>array('n'=>'*1.5')),
		'WC' => array('itm'=>array('青蛙'=>'+9','明信片'=>'+20','爆裂'=>'*3'),'itmk'=>array('DA'=>'+20','EI'=>'u')),
		'WG' => array('itm'=>array('彩虹'=>'*1.5','风暴'=>'*3'),'itmk'=>array('GB'=>'+20','ER'=>'+80','EI'=>'u')),
		'WD' => array('itm'=>array('防线'=>'*1.2','阔剑'=>'*1.6','酒瓶'=>'*2.5'),'itmk'=>array('HR'=>'+70','EE'=>'+50','TN'=>'+20')),
		'WF' => array('itm'=>array('心灵'=>'*1.3','波纹'=>'*1.8','东方'=>'*3'),'itmk'=>array('VF'=>'+80','V'=>'+40'),'itmsk'=>array('d'=>'+20')),
		'DB' => array('itm'=>array('针线包'=>'+10','防御'=>'+30','盾'=>'+60','宝石'=>'+180','数据……碎片'=>'+32768')),
		'DH' => array('itm'=>array('针线包'=>'+10','防御'=>'+30','挂件'=>'+30','宝石'=>'+180'),'itmk'=>array('ER'=>'+30')),
		'DA' => array('itm'=>array('针线包'=>'+10','防御'=>'+30','盾'=>'+50','宝石'=>'+180')),
		'DF' => array('itm'=>array('针线包'=>'+10','防御'=>'+30','羽毛'=>'+50','宝石'=>'+180')),
		'HH' => array('itmk'=>array('M'=>'+50','MH'=>'+100','C'=>'+5'),'itm'=>array('锅'=>'+50','碗'=>'+30','药'=>'+20','蘑菇'=>'+10','苹果'=>'+5','香蕉'=>'+5','西瓜'=>'+10')),
		'HS' => array('itmk'=>array('M'=>'+50','MS'=>'+100','C'=>'+5'),'itm'=>array('锅'=>'+50','碗'=>'+30','药'=>'+20','蘑菇'=>'+10','苹果'=>'+5','苹果'=>'+5','香蕉'=>'+5','西瓜'=>'+10','媚药'=>'+30')),
		'HB' => array('itmk'=>array('M'=>'+50','MH'=>'+100','MS'=>'+100','C'=>'+5'),'itm'=>array('锅'=>'+50','碗'=>'+30','蘑菇'=>'+10','苹果'=>'+5','药'=>'+20','苹果'=>'+5','香蕉'=>'+5','西瓜'=>'+10')),
		'common' => array('itmsk'=>array('Z'=>'+50'), 'itm'=>array('黄金'=>'*1.3','『祝福宝石』'=>'*1.3','增幅设备'=>'*1.2','魔导书'=>'*1.2','钻石'=>'*10','大师'=>'*77','最终战术'=>'*512'))
	);
	
	//耐久素材要求
	//x为数字，'+x'表示增加耐久值x点，'-x'表示减少耐久值x点，'*x'表示耐久值变为x倍，'i'表示耐久变为无限
	$itms_stuff = array
	(
		'WP' => array('itmk'=>array('B'=>'+20'),'itm'=>array('《殴系指南》'=>'+50','机械'=>'+50')),
		'WK' => array('itmk'=>array('WK'=>'+20'),'itm'=>array('《斩系指南》'=>'+50','光束'=>'+30')),
		'WC' => array('itmk'=>array('GA'=>'+20'),'itm'=>array('《投系指南》'=>'+50','卡包'=>'+20')),
		'WG' => array('itmk'=>array('GB'=>'+20'),'itm'=>array('《射系指南》'=>'+50')),
		'WD' => array('itmk'=>array('B'=>'+10','TN'=>'+10'),'itm'=>array('《爆系指南》'=>'+50')),
		'WF' => array('itmk'=>array('V'=>'+20'),'itmsk'=>array('z'=>'+20'),'itm'=>array('《灵系指南》'=>'+50','魔导书'=>'+20')),
		'DB' => array('itmk'=>array('YS'=>'+20'),'itm'=>array('针线包'=>'+10','盾'=>'+30')),
		'DH' => array('itmk'=>array('YS'=>'+20'),'itm'=>array('针线包'=>'+10','盾'=>'+30')),
		'DA' => array('itmk'=>array('YS'=>'+20'),'itm'=>array('针线包'=>'+10','盾'=>'+30')),
		'DF' => array('itmk'=>array('YS'=>'+20'),'itm'=>array('针线包'=>'+10','盾'=>'+30')),
		'HH' => array('itmk'=>array('M'=>'+30','MH'=>'+60','C'=>'+5'),'itm'=>array('锅'=>'+50','碗'=>'+30','药'=>'+20','蘑菇'=>'+10','苹果'=>'+5','香蕉'=>'+5','西瓜'=>'+10')),
		'HS' => array('itmk'=>array('M'=>'+30','MS'=>'+60','C'=>'+5'),'itm'=>array('锅'=>'+50','碗'=>'+30','药'=>'+20','蘑菇'=>'+10','苹果'=>'+5','香蕉'=>'+5','西瓜'=>'+10')),
		'HB' => array('itmk'=>array('M'=>'+30','MH'=>'+50','MS'=>'+50','C'=>'+5'),'itm'=>array('锅'=>'+50','碗'=>'+30','药'=>'+20','蘑菇'=>'+10','苹果'=>'+5','香蕉'=>'+5','西瓜'=>'+10')),
		'common' => array('itmsk'=>array('Z'=>'+30'), 'itm'=>array('埃克法'=>'+5','妖精'=>'+30','方块'=>'+30','『祝福宝石』'=>'*1.5','[+5]'=>'i'))
	);
	
}
?>