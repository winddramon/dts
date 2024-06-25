<?php

namespace instance12
{
	function init() {
		eval(import_module('skillbase','cardbase','map','map_display'));
		if(!isset($valid_skills[22])) {
			$valid_skills[22] = array();
		}
		$valid_skills[22] += array(181,951,952,964,981);
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
	
	//梦境演练自动选择吉祥物
	function get_enter_battlefield_card($card){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$card = $chprocess($card);
		if (22 == $gametype){
			$card = 129;
		}
		return $card;
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
	
	//不会连斗
	function checkcombo($time){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(22 == $gametype) return;
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
			$areatime = $starttime + 1800;
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
	
}

?>