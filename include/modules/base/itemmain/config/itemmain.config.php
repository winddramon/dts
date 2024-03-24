<?php

namespace itemmain
{
	$item_equip_list = Array('itm0','itm1','itm2','itm3','itm4','itm5','itm6');
	
	$item_hotkey_id_list = array(4=>'qq', 5=>'ww', 6=>'ee', 1=>'aa', 2=>'ss', 3=>'dd');//跟界面排列顺序有关系
	
	$itemmain_drophint = '将离开视线';
	
	$item_allow_find_and_use = true;//是否即拾即用
	
	//■ 无限耐久度 ■
	$nosta = '∞';
	//■ 无属性 ■
	$nospk = '--';
	
	//道具发现基础几率(百分比);
	$item_obbs = 60;
	
	//不会有物品掉落的地区列表
	$map_noitemdrop_arealist = Array(0, 34);
	
	//可装备的道具类型开头，应该主要用于显示
	$itemkind_equipable = Array('W','D','A');
	
	//物品类型
	//请在对应模块中赋值，这里只写注释代表哪些名字已经被哪些模块占用了
	$iteminfo = Array(
		//A* armor_art 饰品
		//B* item_ub 电池
		//C* item_uc 各类异常状态治疗药剂
		//D* armor 各类防具
		//EE item_uee 干扰设备
		//ER radar 雷达
		//EW weather 天气控制道具
		//GA wep_b 箭矢
		//GB* ammunition 各类弹药
		//HH,HS,HB edible 各类命体恢复
		//HM,HT song 歌魂补给
		//M* item_um 各类强化药
		'N' => '无',	
		//P* poison 各类被下毒的补给
		//p,ygo,fy 各类礼包
		//ss song 歌词卡片
		//T* trap 各类陷阱
		//U item_misc
		//V* item_uv 技能书籍
		//W* weapon 各类武器
		'X'=> '合成专用',
		'Y' => '特殊',
		'Z' => '特殊',#不可合并
		);
	
	//物品属性类型
	//请在对应模块中赋值，这里只写注释代表哪些名字已经被哪些模块占用了
	//属性允许以下三种写法
	//1、单字母或者符号，如A=>物防。目前可用字符几乎已经用完，不建议继续增加。另外下划线_和分隔符|另有他用。
	//2、^数字。如^001=>同调。
	//3、^字母数字。如^dd20=>降防20%。注意：就算效果和数字无关，使用时也必须以数字结尾。
	//此外，第三项在字母后加下划线，可以通过get_comp_itmsk_info($itmsk)获取下划线后面至数字之间的内容，具体见attrbase模块
	//\itemmain\count_itmsk_num(属性)可以计数物品属性个数
	$itemspkinfo = Array(
		//A   ex_phy_def 物防（全系防御）
		//a   ex_dmg_def 属防（属性防御）
		//B   ex_phy_nullify 物抹（伤害抹消）
		//b   ex_dmg_nullify 属抹（属性抹消）
		//C   ex_phy_def 防投
		//c   rage 集气（重击辅助）
		//D   ex_phy_def 防爆
		//d   ex_dmg_att 爆炸
		//E   ex_dmg_def 绝缘
		//e   ex_dmg_att 电击
		//F   ex_phy_def 防符
		//f   ex_dmg_att 灼焰
		//G   ex_phy_def 防弹
		//g   ex_gender_attr 同志
		//H   ex_hp_def 控噬（HP制御）
		//h   ex_hp_def 控伤（伤害制御）
		//I   ex_dmg_def 防冻
		//i   ex_dmg_att 冻气
		//J   itemmix_overlay 超量素材
		//j   wepchange 多重
		//K   ex_phy_def 防斩
		//k   ex_dmg_att 冰华
		//L   ex_direct 直击
		//l   ex_gender_attr 热恋
		//M   ex_attr_trap 探雷（陷阱探测）
		//m   ex_attr_trap 防雷（陷阱迎击）
		//N   ex_attr_charge 冲击 
		//n   ex_attr_pierce 物穿
		//O   ex_cursed 诅咒
		//o   ammunition 枪械一发
		//P   ex_phy_def 防殴
		//p   ex_dmg_att 带毒
		//Q   ///////////////////////////
		//q   ex_dmg_def 防毒
		//R   ex_rapid_def 防连
		//r   ex_rapid_attr 连击		
		//S   ex_attr_silencer 消音
		//s   itemmix_sync 调整（同调合成必需）
		//T   ///////////////////////////
		//t   ex_dmg_att 音爆
		//U   ex_dmg_def 防火
		//u   ex_dmg_att 火焰
		//V   ex_seckill 弑神
		//v   ex_seckill 直死
		//W   ex_dmg_def 隔音
		//w   ex_dmg_att 音波
		//X   ex_vanish 唯我
		'x' => '奇迹',
		//Y   ex_purity 纯粹
		//y   ex_attr_pierce 属穿
		//Z   blessstone 菁英
		'z' => '天然',
		//|   特殊分隔符
		//
		//^001   itemmix_sync 同调（标记是同调产物）
		//^002   itemmix_sync 变星
		
		//以下复合属性，注意所有复合属性在实际使用时都必须用数字结尾，哪怕你的效果跟数字无关！
		//^ac   ex_attr_crack 碎甲
		//^alt   ex_alternative 多态
		//^atype   ex_alternative 多态类型
		//^ari   wep_b 箭矢（记录所用箭矢的信息）
		//^dd   ex_def_down 降防 	
		//^dg   ex_attr_digit 编号范例，没有实际作用
		//^hlog   ex_uselog 讯息（在前）
		//^hu   ex_mhp_temp_up 升血
		//^l   item_recipe 连接
		//^mbid   item_umb 状态药物技能编号
		//^mbtime   item_umb 状态药物技能时效
		//^mblvl   item_umb 状态药物技能等级
		//^psr   poison 下毒者编号（在ex_digit模块接受定义）
		//^rdsk   radar 探测器等级（在ex_digit模块接受定义）
		//^res   ex_residue 余物
		//^rtype   ex_residue 如何获得/改变记录物品
		//^reptype   ex_residue 如何代表记录物品
		//^sa   ex_attr_song 激奏
		//^sv   ex_attr_song 变奏
		//^scls   item_randskills 备选技能
		//^st   ex_storage 储物
		//^su   item_ext_armor 外甲显示
		//^arn   item_ext_armor 外甲原防具名
		//^are   item_ext_armor 外甲原防具效果值
		//^ars   item_ext_armor 外甲原防具耐久值
		//^tlog   ex_uselog 讯息（在后）
		//^vol   ex_storage 储物容量
		//^wc   ex_weapon_crack 碎刃
		//^wid   weather 天气控制参数值（模块中未做定义，只是占位，在ex_digit模块接受定义）
		//^xyz   itemmix_overlay 超量
		
		'^TEST' => '蛋疼',
	);
	
	$itemspkdesc = array(
		'x' => '人类，可以超越神么？……',
		'z' => '使用后会变成冴冴一样的天然呆',
		'^TEST' => '你的蛋疼度增加<:skn:>%',
	);

	$itemspkdesc_help = array(//帮助界面替换
		
	);
	
	$itemspkremark = array(
		'x' => '……',
		'z' => '……',
	);
}

?>