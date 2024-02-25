<?php

namespace instance11
{
	function init() {
		eval(import_module('skillbase','cardbase','map','map_display'));
		//卡片入场需要冷却时间和禁止同名卡
		$card_force_different_gtype[] = 21;
		$card_need_charge_gtype[] = 21;
		//卡片冷却时间降低80%
		$card_cooldown_discount_gtype[21] = 0.8;
		//开局获得技能：凤凰、扭蛋、打牌、占卜、欺货
		if(!isset($valid_skills[21])) {
			$valid_skills[21] = array();
		}
		$valid_skills[21] += array(545,716,733,734,731);
		//地图显示的配置组
		$map_display_group[1] = Array(
			'x' => 6,
			'y' => 6,//用字母表示
			'background-image' => 'map/ob_room.png',
			'background-width' => 500,
			'background-height' => 500,
		);
		//不能在config里面直接import
		$xyinfo += Array(
			101 => 'D-2',
			102 => 'D-5',
			103 => 'E-4',
			104 => 'C-2',
		);
		$areainfo += Array(
			101 => "这是一个用旧车间改造而成的小型会议室。<br>简洁现代的桌椅似乎在竭力装作一副严肃的模样，但四周墙上贴满了的各种二次元海报让它们的努力都化为了泡影。<br>见到此情此景你不仅再一次小声嘀咕：<span class=\"b\">我是来开会的，这些人要干什么？</span><br>另外，<span class=\"yellow b\">墙角还有一台商品琳琅满目的自动售货机，可以用来购买道具。</span><br>",
			102 => "鲜红色、粉红色、朱红色、棕红色……<br>和其人的印象色截然不同，蓝凝的这间卧室完全是一片红颜色的海洋。<br>很难把这些粉红色的蓬松被褥和可可爱爱的抱枕同「红杀精英」、「时空特使」这样的字眼联系到一起。<br>",
			103 => "这里摆满了蓝凝的个人收藏。<br><span class='cyan b'>“这些都是我的宝贝，可不准乱碰！”</span><br>甚至还有一个其本人的高仿幻影在守卫着这里……<br>",
			104 => "一个狭小但是干净的卫生间。<br>值得注意的是那个大得足以容纳得下一个人的马桶。<br>美少女显然是不需要如厕的，这个东西是用来干什么的呢？<br>",
		);
	}

	//蹲站模式禁用林无月和卡片男
	function card_validate_get_forbidden_cards($card_disabledlist, $card_ownlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		$card_disabledlist = $chprocess($card_disabledlist, $card_ownlist);
		if (21==$gametype)//蹲站模式禁用低维生物、林无月和卡片男
		{
			foreach(Array(196,209,231) as $c){
				if (in_array($c,$card_ownlist)) $card_disabledlist[$c][]='e3';
			}
		}
		return $card_disabledlist;
	}

	//蹲站房特殊地图数目。仅在$use_config == 1时触发
	function get_plsnum($use_config = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($use_config && 21 == get_var_in_module('gametype','sys')) return sizeof(get_var_in_module('plsinfo11','instance11'));
		return $chprocess($use_config);
	}

	//蹲站房特殊地图数据。仅在$use_config == 1时触发
	function get_all_plsno($use_config = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($use_config && 21 == get_var_in_module('gametype','sys')) return array_keys(get_var_in_module('plsinfo11','instance11'));
		return $chprocess($use_config);
	}
	
	//蹲站房入场道具
	function init_enter_battlefield_items($ebp){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ebp = $chprocess($ebp);
		eval(import_module('sys'));
		if (21 == $gametype){
			$ebp['money'] += 980;//初始资金
			$ebp['itm6'] = '窥屏用头戴式显示器'; $ebp['itmk6'] = 'DH'; $ebp['itme6'] = 76; $ebp['itms6'] = 5;$ebp['itmsk6'] = '';
			$ebp['itm0'] = '蓝凝的便签'; $ebp['itmk0'] = 'Z'; $ebp['itme0'] = 1; $ebp['itms0'] = 1;$ebp['itmsk0'] = '';
		}
		return $ebp;
	}
	
	//特殊的NPC列表
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance11'));
		if (21 == $gametype){
			return $npcinfo_instance11;
		}else return $chprocess();
	}

	//特殊的商店类别
	function get_shop_tag_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','instance11'));
		if (21 == $gametype){
			return $shop_tag_list11;
		}
		return $chprocess();
	}
	
	//特殊的商店道具列表
	function get_shopconfig(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance11'));
		if (21 == $gametype){
			$file = __DIR__.'/config/shopitem.config.php';
			$l = openfile($file);
			return $l;
		}
		return $chprocess();
	}

	//特殊的商店地图
	function check_in_shop_area($p)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','instance11'));
		if (21 == $gametype){
			return in_array($p, $shops11);
		}
		return $chprocess($p);
	}
	
	//特殊的地图道具列表
	function get_itemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (21 == $gametype){
			$file = __DIR__.'/config/mapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//特殊的开局随机道具列表
	function get_startingitemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (21 == $gametype){
			$file = __DIR__.'/config/stitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//特殊的开局随机武器列表
	function get_startingwepfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (21 == $gametype){
			$file = __DIR__.'/config/stwep.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//特殊的陷阱列表
	function get_trapfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (21 == $gametype){
			$file = __DIR__.'/config/trapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//不会连斗
	function checkcombo($time){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(21 == $gametype) return;
		$chprocess($time);
	}
	
	//开局天气极光、禁区时间追加、地图显示配置组修改、$plsinfo重载数据修改
	function rs_game($xmode = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','instance11'));
		if((21 == $gametype)&&($xmode & 2)) {//取消初始无月禁区
			$area_on_start = Array();
		}
		$chprocess($xmode);
		if ((21 == $gametype)&&($xmode & 2)) 
		{
			$weather = 18;//开局天气为冷气
			$areatime = $starttime + 1919810;//相当于22天
			$gamevars['map_display_group'] = 1;
			$gamevars['plsinfo'] = $plsinfo11;
		}
	}

	//不显示禁区时间
	function init_areatiming(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (21 == $gametype){
			$uip['timing']['area_timing'] = array(
				'on' => false,
			);
			return;
		}
		$chprocess();
	}

	//开局位于会议室
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (21 == $gametype) 
		{
			$pa['pls'] = 101;
		}
		$chprocess($pa);
	}

	//允许窥屏——摘自《不允许窥屏》
	function check_observe_act_allowed()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(21 == get_var_in_module('gametype','sys')) return true;
		return $chprocess();
	}
	
	//无法使用结局道具、窥屏道具的使用
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		if ((strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) && $itm == '棱镜八面体')
		{
			$log .= "你使用了<span class=\"yellow b\">{$itm}</span>。<br>";
			octitem_rotate($theitem, $theitem['itmn']);
			return;
		}
		if (21 == $gametype)
		{
			if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) {
				if ($itm == '游戏解除钥匙' || $itm == '奇怪的按钮' || $itm == '『G.A.M.E.O.V.E.R』' || $itm == '好想按这个按钮')
				{
					$log .= '这好像只是一个完成度非常高的手办……<br>';
					$mode = 'command';
					return;
				}
				elseif ($itm == '蓝凝的便签')
				{
					 $log .= '你再次看向那张已经有点揉皱了的纸条。<br><br><span class="ltazure b">“<span style="background-color:rgb(110,210,255);">林苍月</span>要我通知你们到我的房间集合，所以我就通知了。不准不来哦！<br><br>PS：这家伙还不让我写他名字”</span><br><br>她甚至没说她自己什么时候会来……<br><br>总之，比起干等，不如做点事吧。<br><br>';
					if(!empty($itms0)) \itemmain\itemget();
					return;
				}
			}
			elseif(check_item_observer($itm, $itmk)){
				$obsv_flag = 1;
			}
		}
		$chprocess($theitem);
		if(21 == $gametype && !empty($obsv_flag) && check_item_observer($arh, $arhk)) {
			ob_start();
			include template('MOD_NEWS_OBSERVE_OBSERVE_SELECT');
			$cmd = ob_get_contents();
			ob_end_clean();
			$log .= '你打开了'.$arh.'的开关……<br><br>';
			return;
		}
	}

	//每次行动后如果处于窥屏状态那么进行判定
	//没有戴着窥屏显示器那么结束窥屏状态，否则增加一个前往房间的按钮
	function post_act(){//每次行动记录得到的金钱
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (21 == $gametype)
		{
			$obsv_id = \news_observe\get_observe_groomid();
			if($obsv_id >= 0) {
				if(!check_item_observer($arh, $arhk)) {
					\news_observe\observe_main(-1);
				}else{
					$uip['cmd_buttons'][] = Array(
						'id' => 'observe_jump'.$obsv_id,
						'value' => $obsv_id ? '前往'.$obsv_id.'号房间' : '前往大房间',
						'onclick' => "$('command').value='observe_jump{$obsv_id}';"
					);
				}
			}
		}
		$chprocess();
	}

	//接收跳转指令
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));

		if('observe_jump' == substr($command, 0, 12)) {
			observe_jump_to_room((int)substr($command, 12));
			return;
		}

		$chprocess();
	}

	//从窥屏状态直接跳转进入进入要看的房间
	function observe_jump_to_room($rid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger'));
		if(!check_observe_act_allowed()) {
			$log .= '<span class="red b">本模式不允许跳跃到其他房间！</span><br><br>';
			$mode = 'command';
			return;
		}
		$rid = (int)$rid;
		$olist = \news_observe\get_observe_roomlist();
		if(empty($olist[$rid])) {
			$log .= '<span class="red b">该房间不可用！</span><br><br>';
			$mode = 'command';
			return;
		}
		if($olist[$rid]['gamestate']<20 || $olist[$rid]['gamestate']>=30) {
			$log .= '<span class="red b">该房间不可进入！</span><br><br>';
			$mode = 'command';
			return;
		}
		set_current_roomid($rid);
		eval(import_module('player'));
		$gamedata['url']='game.php';
	}

	//判定是否戴着窥屏显示器
	function check_item_observer($itm, $itmk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return ('窥屏用头戴式显示器' == $itm && 'DH' == $itmk);
	}
	
	//棱镜八面体相关
	function itemdrop($item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($item == 'wep'){
			$itm = & $wep;
			$itmk = & $wepk;
			$itme = & $wepe;
			$itms = & $weps;
			$itmsk = & $wepsk;
		} elseif(strpos($item,'ar') === 0) {
			$itmn = substr($item,2,1);
			$itm = & ${'ar'.$itmn};
			$itmk = & ${'ar'.$itmn.'k'};
			$itme = & ${'ar'.$itmn.'e'};
			$itms = & ${'ar'.$itmn.'s'};
			$itmsk = & ${'ar'.$itmn.'sk'};

		} elseif(strpos($item,'itm') === 0) {
			$itmn = substr($item,3,1);
			$itm = & ${'itm'.$itmn};
			$itmk = & ${'itmk'.$itmn};
			$itme = & ${'itme'.$itmn};
			$itms = & ${'itms'.$itmn};
			$itmsk = & ${'itmsk'.$itmn};
		}
		if (21 == $gametype)
		{
			if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) {
				if ($itm == '棱镜八面体')
				{
					eval(import_module('logger'));
					$theitem = array('itm' => &$itm, 'itmk' => &$itmk, 'itme' => &$itme,'itms' => &$itms,'itmsk' => &$itmsk);
					$log .= "<span class=\"yellow b\">{$itm}</span>落到地上，然后立了起来。这是怎么做到的？<br>你仔细地端详着它。<br>";
					octitem_rotate($theitem, 7);
				}
			}
		}
		return $chprocess($item);
	}
	
	//棱镜八面体相关
	function itemmove($from,$to)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($from,$to);
		eval(import_module('player','logger'));
		if ($ret && (${'itm'.$from} == '棱镜八面体' || ${'itm'.$to} == '棱镜八面体'))
		{
			$log .= "<br><span class=\"yellow b\">棱镜八面体</span>发出了轻微的“咔哒”声，但是看起来并没有什么变化……真的是这样吗？<br>";
		}
		return $ret;
	}
	
	//棱镜八面体相关
	function octitem_rotate(&$theitem, $rotpos, $showlog = 1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		$oct_colors_words = array('<span class="red b">红</span>','<span class="lime b">绿</span>','<span class="cyan b">蓝</span>','<span class="yellow b">黄</span>','<span class="gold b">金</span>','<span class="linen b">银</span>','<span class="black b white-shadow">黑</span>','<span class="white b">白</span>');
		
		$oct_seq = \itemmain\check_in_itmsk('^os', $itmsk);
		if (false === $oct_seq || strlen($oct_seq) != 8)
		{
			$oct_seq = range(0, 7);
			shuffle($oct_seq);
			$itmsk .= '^os'.implode('', $oct_seq);
		}
		else $oct_seq = str_split($oct_seq);
		
		$oct_colors = \itemmain\check_in_itmsk('^oc', $itmsk);
		if (false === $oct_colors || strlen($oct_colors) != 8)
		{
			$oct_colors = range(0, 7);
			shuffle($oct_colors);
		}
		else $oct_colors = str_split($oct_colors);
		
		//改变选中面和另两个面的颜色
		$oct_colors[$rotpos] = ($oct_colors[$rotpos] + 1) % 8;
		$rotpos2 = ($rotpos + 1) % 8;
		$oct_colors[$rotpos2] = ($oct_colors[$rotpos2] + 1) % 8;
		$rotpos3 = ($rotpos + 2) % 8;
		$oct_colors[$rotpos3] = ($oct_colors[$rotpos3] + 1) % 8;
		$itmsk = \itemmain\replace_in_itmsk('^oc','',$itmsk);
		$itmsk .= '^oc'.implode('', $oct_colors);
		
		if ($showlog)
		{
			eval(import_module('logger'));
			$log .= "<br><span class=\"yellow b\">{$itm}</span>八个面的颜色为：<br>";
			foreach ($oct_seq as $v)
			{
				$log .=	$oct_colors_words[$oct_colors[$v]].' ';
			}
			$log .= "<br>";
		}
		
		//结果检查
		$oc_count = count(array_unique($oct_colors));
		if ($oc_count == 1)
		{
			if ($showlog)
			{
				$log .= "<span class=\"yellow b\">{$itm}</span>的形状发生了变化……<br>";
			}
			eval(import_module('sys'));
			if (21 == $gametype)
			{
				$itm = '★棱镜二面体模样的卡牌包★'; $itmk = 'VO';
				$itme = $itms = 1; $itmsk = '409';
			}
			else
			{
				$itm = '★棱镜五面体模样的技能核心★'; $itmk = array_randompick(array('SCA1','SCA2','SCS1','SCS2'));
				$itme = 1; $itms = 2; $itmsk = 'x';
			}
		}
	}
	
	//在厕所不能打架
	function meetman_alternative($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($gametype == 21 && $pls == 104 && !$edata['type'])
		{
			eval(import_module('logger'));
			$log .= '在洗手间要有洗手间的礼帽！你向对方友好地打了个招呼。<br>';
			\team\findteam($edata);
			return;
		}
		$chprocess($edata);
	}
	
}

?>