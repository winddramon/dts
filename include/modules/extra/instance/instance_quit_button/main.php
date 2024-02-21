<?php

namespace instance_quit_button
{
	$gametype_allow_quit_button = Array(17, 21);//允许退出房间并删除角色的游戏类型
	$gametype_delete_player_after_quit = Array(21);//退出时立刻删除角色的游戏类型

	function init() {
	}

	//判定是否符合退出房间的条件：游戏模式符合要求
	//返回true为符合，返回false不符合
	function check_instance_quit_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//必须是允许关闭的房间类型
		eval(import_module('sys','instance_quit_button'));
		if(!in_array($gametype, $gametype_allow_quit_button)) return false;
		return true;
	}
	
	//关闭房间指令
	function instance_quit(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if(!check_instance_quit_available()) {
			eval(import_module('logger'));
			$log .= '<span class="red b">当前游戏模式不允许退出房间！</span>';
			return;
		}
		
		eval(import_module('sys','player','instance_quit_button'));
		addnews($now, 'instance_quit', $cuser);	//发送进行状况
		
		if($hp > 0 && $state <= 3) {
			$alivenum--;
			save_gameinfo();
		}

		$sdata['endtime'] = -1;//负数会在command_act.php最后被变为0。如果是唯一房，下次进房会触发唯一房间重置角色功能
		set_current_roomid(0);
		$gamedata['url']='index.php';

		if(in_array($gametype, $gametype_delete_player_after_quit)) {//如果退出时立刻删除角色，会把角色丢到一个其他人遇不到的地图
			$pls = 233;
		}
		return;
	}
	
	//关闭房间指令，接管pre_act()
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(strpos($command,'instance_quit')===0){
			instance_quit();
			return;
		}
		$chprocess();
	}

	//相关的进行状况描述
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if($news == 'instance_quit') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}退出了当前房间</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>