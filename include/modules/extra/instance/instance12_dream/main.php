<?php

namespace instance12
{
	function init() {
		eval(import_module('skillbase','cardbase','map','map_display'));
		if(!isset($valid_skills[22])) {
			$valid_skills[22] = array();
		}
		$valid_skills[22] += array(68,181,252,951,952,964,981);
		//地图显示的配置组
		$map_display_group[2] = Array(
			'x' => 10,
			'y' => 10,//用字母表示
			'background-image' => 'map/neomap.jpg',
			'background-width' => 500,
			'background-height' => 500,
		);
		//不能在config里面直接import
		$xyinfo += Array(
			201 => 'A-1',
		);
		$areainfo += Array(
			201 => "透明的地面隐约映照出你的身影，四周、头顶以及地面之下，皆被无垠的深邃星空覆盖。<br>你的视线无法触及这片地面的尽头，但你似乎看到远处有晃动的人影。<br>……这里究竟是哪？<br>",
		);
	}
	
	//禁止难度卡外的其他卡片
	function card_validate_get_forbidden_cards($card_disabledlist, $card_ownlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		$card_disabledlist = $chprocess($card_disabledlist, $card_ownlist);
		if (22 == $gametype)
		{
			foreach($card_ownlist as $cv){
				if(!in_array($cv, array(1201, 1202, 1203, 1204))) $card_disabledlist[$cv][]='e3';
			}
		}
		return $card_disabledlist;
	}
	
	//梦境演练选卡界面特殊显示
	function card_validate_display($cardChosen, $card_ownlist, $packlist, $hideDisableButton){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		list($cardChosen, $card_ownlist, $packlist, $hideDisableButton) = $chprocess($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
		if (22 == $gametype)
		{
			$cardChosen = 1201;
			$card_ownlist = array_merge($card_ownlist, array(1201, 1202, 1203, 1204));
			$packlist[] = 'Pungeon';
			$hideDisableButton = 0;
		}
		return array($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
	}
	
	//梦境演练特殊地图数目。仅在$use_config == 1时触发
	function get_plsnum($use_config = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($use_config && 22 == get_var_in_module('gametype','sys')) return sizeof(get_var_in_module('plsinfo12','instance12'));
		return $chprocess($use_config);
	}

	//梦境演练特殊地图数据。仅在$use_config == 1时触发
	function get_all_plsno($use_config = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($use_config && 22 == get_var_in_module('gametype','sys')) return array_keys(get_var_in_module('plsinfo12','instance12'));
		return $chprocess($use_config);
	}
	
	//梦境演练入场道具
	function init_enter_battlefield_items($ebp){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ebp = $chprocess($ebp);
		eval(import_module('sys'));
		if (22 == $gametype){
			$ebp['itm5'] = '全恢复药剂'; $ebp['itmk5'] = 'Ca'; $ebp['itme5'] = 1; $ebp['itms5'] = 3;$ebp['itmsk5'] = '';
			$ebp['itm6'] = '梦境礼盒'; $ebp['itmk6'] = 'Y'; $ebp['itme6'] = 0; $ebp['itms6'] = 1;$ebp['itmsk6'] = '';
		}
		return $ebp;
	}
	
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance12'));
		if (22 == $gametype){
			return $npcinfo_instance12;
		}else return $chprocess();
	}
	
	function get_shopconfig(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (22 == $gametype){
			$file = __DIR__.'/config/shopitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_itemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (22 == $gametype){
			$file = __DIR__.'/config/mapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function mapitem_row_data_process($data, $no = -1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($data, $no);
		eval(import_module('sys'));
		if (22 == $gametype)
		{
			$coef = ($ret[1] == 99) ? 30 : 5;
			$ret[2] = floor($ret[2] / $coef) + (rand(1, $coef) <= $ret[2] % $coef ? 1 : 0);
			if ($ret[1] != 34) $ret[1] = 201;
		}
		return $ret;
	}
	
	function get_startingitemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (22 == $gametype){
			$file = __DIR__.'/config/stitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_startingwepfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (22 == $gametype){
			$file = __DIR__.'/config/stwep.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_trapfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (22 == $gametype){
			$file = __DIR__.'/config/trapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//只要玩家没死就不会连斗
	function checkcombo($time){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(22 == $gametype && $alivenum > 0) return;
		$chprocess($time);
	}
	
	//开局天气和地图初始化
	function rs_game($xmode = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','instance12'));
		if((22 == $gametype)&&($xmode & 2)) {//初始英灵殿禁区
			$area_on_start = Array(34);
		}
		$chprocess($xmode);
		eval(import_module('sys'));
		if ((22 == $gametype)&&($xmode & 2)) 
		{
			$weather = 1;
			$areatime = $starttime + 7200;
			$gamevars['map_display_group'] = 2;
			$gamevars['plsinfo'] = $plsinfo12;
		}
	}
	
	//开局位于英灵殿（？）
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (22 == $gametype) 
		{
			$pa['pls'] = 34;
		}
		$chprocess($pa);
	}

	//特殊的商店地图
	//直接每个地区都能访问商店吧
	function check_in_shop_area($p)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (22 == $gametype){
			return true;
		}
		return $chprocess($p);
	}
	
	//保持0禁
	function get_area_wavenum(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (22 == $gametype) return 0;
		return $chprocess();
	}
	
	//禁区时结束游戏
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if (22 == $gametype){
			\sys\gameover($atime,'end1');
			return;
		}
		$chprocess($atime);
	}
	
	//英灵殿无事件
	function event_available(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys', 'player'));
		if(22 == $gametype && $pls == 34) return false;
		return $chprocess();
	}
	
	//道具发现率降低
	function calculate_itemfind_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$r = 0;
		if (22 == $gametype) $r = -30;
		return $chprocess()+$r;
	}
	
	//敌人发现率增加
	function calculate_findman_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$r = 0;
		if (22 == $gametype) $r = 60;
		return $chprocess($edata)+$r;
	}
	
	//无法使用部分道具
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) {
			if (in_array($itm, array('奇怪的按钮','不要按这个按钮','好想按这个按钮','这个是什么按钮')))
			{
				$log .= '<span class="yellow b">看起来这个按钮没有任何功能。</span><br>';
				$mode = 'command';
				return;
			}
		}
		$chprocess($theitem);
	}
	
	//结算分数
	function inst12_get_score(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$stage = (int)\skillbase\skill_getvalue(981,'stage',$pa);
		$rm = (int)\skillbase\skill_getvalue(981,'rm',$pa);
		if ($rm > 0) $stage -= 1;
		$inst12_difficulty = array(1201 => 1, 1202 => 2, 1203 => 4, 1204 => 8);
		$r = 0;
		if (isset($inst12_difficulty[$pa['card']])) $r = $inst12_difficulty[$pa['card']];
		$score = max($r * $stage, 0);
		return $score;
	}
	
	//切糕奖励
	function gameover_set_credits()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('sys'));
		if ($gametype != 22) return;
		if (empty($gameover_plist)) return;
		$pa = array_values($gameover_plist)[0];//单人模式
		$score = inst12_get_score($pa);
		$qiegao_prize = $score * 50;
		$score_text = '得分'.$score.'分，计'.$qiegao_prize.'切糕';
		//结局奖励
		$inst12_qiegao = array(3 => 500, 7 => 1200);
		if (isset($inst12_qiegao[$winmode]))
		{
			$qiegao_prize += $inst12_qiegao[$winmode];
			$score_text .= '，结局额外奖励'.$inst12_qiegao[$winmode].'切糕';
		}
		if ($qiegao_prize)
		{
			include_once './include/messages.func.php';
			message_create(
				$pa['name'],
				'梦境演练奖励',
				'你在房间第'.$gamenum.'局梦境演练中'.$score_text.'。<br>',
				'getqiegao_'.$qiegao_prize
			);
		}
	}
	
}

?>