<?php

namespace instance12
{
	function init() {
		eval(import_module('skillbase','cardbase','map','map_display'));
		if(!isset($valid_skills[22])) {
			$valid_skills[22] = array();
		}
		$valid_skills[22] += array(68,181,951,952,964,981);
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
			$card_ownlist[] = array_merge($card_ownlist, array(1201, 1202, 1203, 1204));
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
			$areatime = $starttime + 3600;
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
	
	//切糕奖励
	function post_winnercheck_events($winner)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($winner);
		eval(import_module('sys'));
		if ($gametype == 22)
		{
			$inst12_qiegao = array(3 => 1337, 7 => 5120);
			if (isset($inst12_qiegao[$winmode]))
			{
				$pa = \player\fetch_playerdata($winner);
				$qiegao_prize = $inst12_qiegao[$winmode];
				if ($qiegao_prize)
				{
					include_once './include/messages.func.php';
					message_create(
						$pa['name'],
						'梦境演练奖励',
						'祝贺你在房间第'.$gamenum.'局梦境演练获得了奖励！<br>',
						'getqiegao_'.$qiegao_prize
					);
				}
			}
		}
	}
	
}

?>