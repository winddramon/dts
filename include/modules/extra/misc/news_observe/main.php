<?php

namespace news_observe
{
	function init() {}
	
	//转发隔壁房间状况直接用现成的代码

	//可用房间判定，需要排除自己房间
	//传参为从game表读取的sql数组
	function check_observe_groomid_allowed($gamedata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($gamedata['groomid'] == get_var_in_module('groomid', 'sys')) return false;
		return true;
	}

	//获得可以围观的房间id数组
	function get_observe_roomlist()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$ret = array();
		$roomresult = $db->query("SELECT * FROM {$gtablepre}game WHERE gamestate >= 20");
		while ($gamedata = $db->fetch_array($roomresult))
		{
			if(check_observe_groomid_allowed($gamedata)){
				$ret[$gamedata['groomid']] = $gamedata;
			}
		}
		return $ret;
	}

	//获得当前窥屏的房间号，-1为没有在窥屏
	function get_observe_groomid()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$obsv_on = \skillbase\skill_getvalue(1003,'obsv_on');
		if(empty($obsv_on)) return -1;
		return \skillbase\skill_getvalue(1003,'obsv_id');
	}

	//修改skill1003记录的当前房间聊天号
	//返回1表示成功修改，0表示修改失败
	function set_observe_groomid($obsv_id) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($obsv_id < 0) {//传值为-1视为关闭ob
			\skillbase\skill_setvalue(1003,'obsv_on',0);
			eval(import_module('sys'));
			$uip['value']['obsv_flag'] = '0';
			$uip['effect']['chat_observe_off'] = 1;
			return 1;
		}
		$obsv_list = get_observe_roomlist();
		if(!in_array($obsv_id, array_keys($obsv_list))) return 0;
		\skillbase\skill_setvalue(1003,'obsv_on',1);
		\skillbase\skill_setvalue(1003,'obsv_id',$obsv_id);
		return 1;
	}

	//当前房间聊天房间号的实际更改
	function parse_interface_gameinfo() 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$chprocess();
		if(\skillbase\skill_query(1003) && !empty(\skillbase\skill_getvalue(1003,'obsv_on'))){
			$obsv_id = \skillbase\skill_getvalue(1003,'obsv_id');
			$uip['value']['croomid'] = (int)$obsv_id;
			$uip['value']['obsv_flag'] = '1';
			$uip['innerHTML']['chat_floating_banner_inner'] = '窥屏中……房间号：'.$obsv_id.'号房间';
			$uip['effect']['chat_observe_on'] = 1;
		}
	}

	//判定是否允许窥屏，比如持有道具之类的
	//本模块不允许窥屏。需要窥屏的模块比如instance请重载这个函数修改返回值。
	function check_observe_act_allowed()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return true;
		//return false;
	}

	//窥屏状态切换的主函数
	function observe_main($obsv_id)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if(!check_observe_act_allowed()) 
		{
			$log .= '<span class="red b">当前游戏设置不允许窥屏，或者你并未满足窥屏需求！</span><br>';
			return;
		}
		if($obsv_id < 0)//发送负数视为关闭窥屏状态
		{
			$ret = set_observe_groomid($obsv_id);
			if($ret) $log .= '<span class="yellow b">你停止了窥屏。</span><br>';
			else $log .= '<span class="red b">停止窥屏失败！</span><br>';
			return;
		}
		$obsv_list = get_observe_roomlist();
		if(empty($obsv_list)) 
		{
			$log .= '<span class="red b">当前没有可以窥屏的房间！</span><br>';
			return;
		}
		if(!in_array($obsv_id, array_keys($obsv_list))) 
		{
			$log .= '<span class="red b">该房间并未开启，或者不允许窥屏！</span><br>';
			return;
		}
		$ret = set_observe_groomid($obsv_id);
		if($ret) $log .= '<span class="yellow b">你关注着'.$obsv_id.'号房间的状况，开始了窥屏……</span><br>';
		else $log .= '<span class="red b">窥屏失败！</span><br>';
		return;
	}

	//接收窥屏指令
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));

		if($mode == 'obsv_select' && 'obsv' == substr($command, 0, 4)) {
			observe_main((int)substr($command, 4));
			return;
		}

		$chprocess();
	}

	//拉取进行状况时的房间号重写
	function getnews($start=0, $range=0, $room_prefix='default'){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$croomid = get_var_input('croomid');
		$obsv_flag = get_var_input('obsv_flag');
		if(NULL !== $croomid && !empty($obsv_flag)) {
			$room_prefix = room_id2prefix($croomid);
		}
		return $chprocess($start, $range, $room_prefix);
	}

	//测试用窥屏道具
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];

		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) {	
			if ($itm == '测试用窥屏显示器') {
				ob_start();
				include template('MOD_NEWS_OBSERVE_OBSERVE_SELECT');
				$cmd = ob_get_contents();
				ob_end_clean();
				$log .= '你打开了'.$itm.'的开关……<br><br>';
				return;
			}
		}
		
		$chprocess($theitem);
	}

	//房间用窥屏道具
	// function use_armor(&$theitem, $pos = '')
	// {
	// 	if (eval(__MAGIC__)) return $___RET_VALUE;

	// 	$flag = '窥屏用头戴式显示器' == $theitem['itm'] ? 1 : 0;

	// 	$chprocess($theitem, $pos);

	// 	if(!empty($flag)) {
	// 		ob_start();
	// 		include template('MOD_NEWS_OBSERVE_OBSERVE_SELECT');
	// 		$cmd = ob_get_contents();
	// 		ob_end_clean();
	// 	}
	// }
}

?>