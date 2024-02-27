<?php

namespace bufficons
{
	$bufficons_list = Array();//需要判断显示的技能或者效果列表

	$tmp_totsec = $tmp_nowsec = 0;//计算剩余时间用的临时变量

	function init() {}
	
	//一个完整的技能描述（$bufficons_list的元素数组，也是bufficon_show()接收到的$para变量）应包含以下字段：
	//disappear: 生效结束后是消失还是进入冷却状态（1为消失）
	//clickable: 如不考虑冷却时间，本技能目前是否已满足主动激活条件。如果提供一个函数名，则会调用这个函数来判定
	//hint： 技能的描述文字。如果提供一个函数名，则会调用这个函数来生成描述文字。
	//activate_hint： 激活技能的提示文字（或不能激活技能时的说明文字），如果本技能不是主动技能，与hint一样即可。如果提供一个函数名，则会调用这个函数来生成描述文字。
	//onclick： 点击时的js操作（clickable时有效）
	//corner-text: （可选）在右下角显示的内容。如果提供一个函数名，则会调用这个函数来生成内容

	//以下数据必需，但技能类有数字id的可以由bufficons_display_single()自动生成
	//src为图片链接
	//style: 当前技能状态： 1 生效状态 2 冷却状态 3 冷却完成，保留图标 4 冷却完成，图标消失，失去技能。
	//totsec: 倒计时总时长，即总生效/冷却时间，取决于技能目前所处状态
	//nowsec: 倒计时已经过时长，即当前已经生效/冷却了的时间 
	
	
	function bufficon_show($src, $para)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($para['style']==1)
		{
			$wh=round($para['nowsec']/$para['totsec']*32).'px';
			$para['lsec']=$para['totsec']-$para['nowsec'];
			include template('MOD_BUFFICONS_ICON_STYLE_1');
		}
		if ($para['style']==2)
		{
			$wh=round($para['nowsec']/$para['totsec']*32).'px';
			$para['lsec']=$para['totsec']-$para['nowsec'];
			include template('MOD_BUFFICONS_ICON_STYLE_2');
		}
		if ($para['style']==3)
		{
			include template('MOD_BUFFICONS_ICON_STYLE_3');
		}
	}
	
	//需要显示buff图标只需接管这个函数调用bufficon_show即可
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	//2024.02.26 原bufficons_list()函数大量重复实现太愚蠢了，改为每个技能或者效果只提供必要参数，在这里统一实现
	function bufficons_display(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('bufficons'));
		foreach($bufficons_list as $token => $config) {
			list($src, $para) = bufficons_display_single($token, $config, $pa);
			if(!empty($src) && !empty($para)) bufficon_show($src, $para);
		}
	}

	//单个buff图标的判定，$token为标记（如技能id），$config为技能或者效果模块设定好的参数
	//返回值有两个：技能图标链接和提供给bufficon_show()使用的参数数组
	//要求所有时效性技能定义3个参数：start_ts（效果开始时间戳）、end_ts（效果结束时间戳）、cd_ts（冷却完成时间戳）。
	//其中end_ts必须非0，cd_ts可以为0表示无CD
	function bufficons_display_single($token, $config, &$pa=NULL) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$src = '';
		if(is_numeric($token) && defined('MOD_SKILL'.$token) && \skillbase\skill_query($token, $pa)){
			eval(import_module('bufficons'));
			$src = !empty($config['src']) ? $config['src'] : 'img/skill'.$token.'.gif';
			$buff_state = bufficons_check_buff_state($token, $pa);
			//一些参数的自动识别，依赖于技能标签
			if(!empty($buff_state)) {
				if(empty($config['disappear'])) {
					$config['disappear'] = 1;
					if(\skillbase\check_skill_info($token, 'upgrade')) $config['disappear'] = 0;
				}
				if(empty($config['clickable'])) {//这里先判定设定上是否需要激活
					$config['clickable'] = 0;
					if(\skillbase\check_skill_info($token, 'upgrade')) $config['clickable'] = 1;
				}
				if(empty($config['onclick']) && !empty($config['clickable'])) {
					$config['onclick'] = "$('mode').value='special';$('command').value='skill".$token."_special';$('subcmd').value='activate';postCmd('gamecmd','command.php',this);";
				}
				if(empty($config['hint'])){
					eval(import_module('clubbase'));
					if(!empty($clubskillname[$token])){
						$config['hint'] = '技能「'.$clubskillname[$token].'」';
						if(empty($config['activate_hint']) && !empty($config['clickable'])) {
							$config['activate_hint'] = '点击发动技能「'.$clubskillname[$token].'」';
						}
					}
				}
			}
			//stype、totsec和nowsec的自动识别
			if(3 == $buff_state) {//冷却时间已结束
				if(!empty($config['disappear']) && !empty(\skillbase\skill_getvalue($token,'cd_ts',$pa))) {
					$config['style'] = 4;
					\skillbase\skill_lost($token, $pa);//在这里判定失去技能
				}else{
					$config['style'] = 3;
				}
			}elseif(2 == $buff_state) {//冷却中
				$config['style'] = 2;
				$config['totsec'] = $tmp_totsec;
				$config['nowsec'] = $tmp_nowsec;
			}elseif(1 == $buff_state) {//生效中
				$config['style'] = 1;
				$config['totsec'] = $tmp_totsec;
				$config['nowsec'] = $tmp_nowsec;
			}
		}
		return Array($src, $config);
	}
	
	//激活buff图标通用的时间戳设置
	//本模块只做数字id技能的判定
	//会用$now填充$start_st，需要生效时间和冷却时间两个值，不能同时为零
	//返回true为设置成功，否则设置失败
	function bufficons_set_timestamp($token, $end, $cd, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(is_numeric($token) && defined('MOD_SKILL'.$token) && \skillbase\skill_query($token, $pa) && \skillbase\skill_check_unlocked($token, $pa) && ($end || $cd)){
			$now = get_var_in_module('now','sys');
			$end_ts = $now + (int)$end;
			$cd_ts = $end_ts + (int)$cd;
			\skillbase\skill_setvalue($token,'start_ts',$now,$pa);
			\skillbase\skill_setvalue($token,'end_ts',$end_ts,$pa);
			\skillbase\skill_setvalue($token,'cd_ts',$cd_ts,$pa);
			return true;
		}
		return false;
	}

	//判定buff图标状态（实际上是buff状态）
	//同时也会计算当前状态的剩余时间（生效/冷却时间），存放在本模块的$tmp_countdown变量中，请即算即用
	//传参$token为技能或者效果的标记，可以是数字也可以是字符串
	//返回值为0时表示不适用，返回值为1时表示生效，返回值为2时表示冷却中，返回值为3时表示冷却完毕
	//本模块把$token当做技能id处理
	//几个特殊用法：end_ts非空，cd_ts是0的时候是开局就能使用（end_ts是0的话不会进判断）
	function bufficons_check_buff_state($token, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('bufficons'));
		$ret = 0;
		if(!empty($bufficons_list[$token]) && is_numeric($token) && \skillbase\skill_query($token, $pa) && \skillbase\skill_check_unlocked($token, $pa)) {
			$now = get_var_in_module('now','sys');
			$tmp_totsec = $tmp_nowsec = 0;
			$start_ts = (int)\skillbase\skill_getvalue($token,'start_ts',$pa);
			$end_ts = (int)\skillbase\skill_getvalue($token,'end_ts',$pa);
			$cd_ts = (int)\skillbase\skill_getvalue($token,'cd_ts',$pa);
			if(!empty($end_ts)) {
				if($now <= $end_ts && $end_ts > $start_ts) {
					$ret = 1;
					if(!$start_ts) {
						$tmp_totsec = max($end_ts - $now, 1);
						$tmp_nowsec = 0;
					}else{
						$tmp_totsec = $end_ts - $start_ts;
						$tmp_nowsec = $now - $start_ts;
					}
				}
				elseif($now <= $cd_ts && $cd_ts > $end_ts) {
					$ret = 2;
					$tmp_totsec = $cd_ts - $end_ts;
					$tmp_nowsec = $now - $end_ts;
				}
				else {
					$ret = 3;
				}		
			}
		}
		return $ret;
	}
}

?>