<?php

namespace bufficons
{
	$bufficons_list = Array();//需要判断显示的技能或者效果列表

	$tmp_totsec = $tmp_nowsec = 0;//计算剩余时间用的临时变量

	function init() {}
	
	//一个完整的技能描述（$bufficons_list的元素数组，也是bufficon_show()接收到的$para变量）应包含以下字段：
	//disappear: 冷却时间结束后是否消失（1为消失）
	//clickable: 冷却时间结束后，如果技能不消失，图标是否变亮且可点击（1为可点击）
	//hint： 技能的描述文字。
	//activate_hint： 激活技能的提示文字（或不能激活技能时的说明文字），如果本技能不是主动技能，与hint一样即可。
	//onclick： 点击时的js操作（clickable时有效）
	//corner-text: （可选）在右下角显示的内容。
	//msec: （可选）显示时是否考虑毫秒（1为考虑，0为不考虑）注意这只是显示，在设置时间戳时还需要手动提供参数

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
			$para['lsec']=(int)($para['totsec']-$para['nowsec']);
			include template('MOD_BUFFICONS_ICON_STYLE_1');
		}
		if ($para['style']==2)
		{
			$wh=round($para['nowsec']/$para['totsec']*32).'px';
			$para['lsec']=(int)($para['totsec']-$para['nowsec']);
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
			$msec = !empty($config['msec']) ? 1 : 0;
			$buff_state = bufficons_check_buff_state($token, $pa, $msec);
			//一些参数的自动识别，依赖于技能标签
			if(!empty($buff_state)) {
				$src = !empty($config['src']) ? $config['src'] : 'img/skill'.$token.'.gif';
				if(!isset($config['disappear'])) {
					$config['disappear'] = 1;
					if(\skillbase\check_skill_info($token, 'upgrade;') || \skillbase\check_skill_info($token, 'battle;')) $config['disappear'] = 0;
				}
				if(!isset($config['clickable'])) {//这里先判定设定上是否需要激活
					$config['clickable'] = 0;
					if(\skillbase\check_skill_info($token, 'upgrade;')) $config['clickable'] = 1;
				}
				if(empty($config['onclick']) && !empty($config['clickable'])) {
					$config['onclick'] = "$('mode').value='special';$('command').value='skill".$token."_special';$('subcmd').value='activate';postCmd('gamecmd','command.php',this);";
				}
				if(empty($config['hint'])){
					eval(import_module('clubbase'));
					if(!empty($clubskillname[$token])){
						if(\skillbase\check_skill_info($token, 'battle;')) {
							$config['hint'] = '战斗技「'.$clubskillname[$token].'」';
						}else{
							$config['hint'] = '技能「'.$clubskillname[$token].'」';
						}
						if(empty($config['activate_hint'])) {
							if(!empty($config['clickable'])) {
								$config['activate_hint'] = '点击发动技能「'.$clubskillname[$token].'」';
							}else{
								if(\skillbase\check_skill_info($token, 'battle;')) {
									$config['activate_hint'] = '战斗技「'.$clubskillname[$token].'」已就绪<br>在战斗界面可以发动';
								}else{
									$config['activate_hint'] = '技能「'.$clubskillname[$token].'」冷却完毕';
								}
							}
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
	//传参$tp代表时间累加方式，1为获得技能时刷新时长，2为时长累加
	//传参$msec代表时间计算时考虑毫秒，1为考虑，0为不考虑
	//返回true为设置成功，否则设置失败
	function bufficons_set_timestamp($token, $end, $cd, &$pa=NULL, $tp=1, $msec=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(is_numeric($token) && defined('MOD_SKILL'.$token) && \skillbase\skill_query($token, $pa) && \skillbase\skill_check_unlocked($token, $pa) && ($end || $cd)){
			$now = get_var_in_module('now','sys');
			$end_ts = $now + (int)$end;
			$cd_ts = $end_ts + (int)$cd;
			if(2 == $tp && 1 == \bufficons\bufficons_check_buff_state($token, $pa, $msec)) {//如果技能正在生效，判定是否累加时间
				eval(import_module('bufficons'));
				$addsec = $tmp_totsec - $tmp_nowsec;
				$end_ts += $addsec;
				$cd_ts += $addsec;
			}
			\skillbase\skill_setvalue($token,'start_ts',$now,$pa);
			\skillbase\skill_setvalue($token,'end_ts',$end_ts,$pa);
			\skillbase\skill_setvalue($token,'cd_ts',$cd_ts,$pa);
			if(!empty($msec)) {//考虑毫秒，则把毫秒尾数也同时记录
				$now_calc = \sys\get_now(1);
				$addmsec = (int)(1000*($now_calc - $now));
				$end_addmsec = (int)(1000*($end - (int)$end)) + $addmsec;
				$cd_addmsec = (int)(1000*($cd - (int)$cd)) + $addmsec;
				\skillbase\skill_setvalue($token,'start_msec',$addmsec,$pa);
				\skillbase\skill_setvalue($token,'end_msec',$end_addmsec,$pa);
				\skillbase\skill_setvalue($token,'cd_msec',$cd_addmsec,$pa);
			}
			return true;
		}
		return false;
	}

	//判定buff图标状态（实际上是buff状态）
	//同时也会计算当前状态的剩余时间（生效/冷却时间），存放在本模块的$tmp_countdown变量中，请即算即用
	//传参$token为技能或者效果的标记，可以是数字也可以是字符串。传参$msec为1则表示时间需要考虑毫秒
	//返回值为0时表示不适用，返回值为1时表示生效，返回值为2时表示冷却中，返回值为3时表示冷却完毕
	//本模块把$token当做技能id处理
	//几个特殊用法：end_ts非空，cd_ts是0的时候是开局就能使用（end_ts是0的话不会进判断）
	function bufficons_check_buff_state($token, &$pa=NULL, $msec=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('bufficons'));
		$ret = 0;
		if(!empty($bufficons_list[$token]) && is_numeric($token) && \skillbase\skill_query($token, $pa) && \skillbase\skill_check_unlocked($token, $pa)) {
			$now_calc = get_var_in_module('now','sys');
			$tmp_totsec = $tmp_nowsec = 0;
			$start_ts = (int)\skillbase\skill_getvalue($token,'start_ts',$pa);
			$end_ts = (int)\skillbase\skill_getvalue($token,'end_ts',$pa);
			$cd_ts = (int)\skillbase\skill_getvalue($token,'cd_ts',$pa);
			if(!empty($msec)) {//时间需要考虑毫秒。注意毫秒尾数在skillpara里是按整数储存的，但是在计算时是化为时间戳的小数部分
				$now_calc = \sys\get_now(1);
				$start_ts += ((int)\skillbase\skill_getvalue($token,'start_msec',$pa))/1000;
				$end_ts += ((int)\skillbase\skill_getvalue($token,'end_msec',$pa))/1000;
				$cd_ts += ((int)\skillbase\skill_getvalue($token,'cd_msec',$pa))/1000;
			}
			if(!empty($end_ts)) {//这里的数字是原始的，在显示的时候再按需要四舍五入
				if($now_calc <= $end_ts && $end_ts > $start_ts) {
					$ret = 1;
					if(!$start_ts) {
						$tmp_totsec = max($end_ts - $now_calc, 0.1);
						$tmp_nowsec = 0;
					}else{
						$tmp_totsec = $end_ts - $start_ts;
						$tmp_nowsec = $now_calc - $start_ts;
					}
				}
				elseif($now_calc <= $cd_ts && $cd_ts > $end_ts) {
					$ret = 2;
					$tmp_totsec = $cd_ts - $end_ts;
					$tmp_nowsec = $now_calc - $end_ts;
				}
				else {
					$ret = 3;
				}		
			}
		}
		return $ret;
	}

	//常见的一组bufficons_check_buff_state()状态判定逻辑，用于判定是否允许激活技能
	//会自动调用bufficons_check_buff_state()，生成判定失败时的反馈，并根据是否允许来返回true/false
	//其他模块要增加判定可以继承这个函数
	function bufficons_check_buff_state_shell($token, &$pa=NULL, $msec=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('bufficons'));
		$st = \bufficons\bufficons_check_buff_state($token, $pa, $msec);
		
		$can_activate = true;
		$fail_hint = '';
		if (!$st){
			$fail_hint = '你不能使用这个技能！<br>';
		}
		elseif (1 == $st){
			$fail_hint = '你已经发动过这个技能了！<br>';
		}
		elseif (2 == $st){
			$fail_hint = '技能冷却中！<br>';
		}
		
		if($st <= 2) $can_activate = false;
		return Array($can_activate, $fail_hint);
	}

	//激活buff：常见的一组激活buff技能的执行逻辑，使用对象主要是主动技能，需要事先获得技能
	//传参$tp代表时间累加方式，1为获得技能时刷新时长，2为时长累加
	//传参$msec代表时间计算时考虑毫秒，1为考虑，0为不考虑
	function bufficons_activate_buff($token, $end, $cd, &$pa=NULL, $tp=1, $msec=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$is_successful = true;
		$fail_hint = '';
		list($is_successful, $fail_hint) = \bufficons\bufficons_check_buff_state_shell($token, $pa, $msec);
		if($is_successful){
			$is_successful = \bufficons\bufficons_set_timestamp($token, $end, $cd, $pa, $tp, $msec);
			if(!$is_successful) {
				$fail_hint = '因技能编号错误或者时间为0等原因，发动技能失败！<br>';
			}
		}
		
		return Array($is_successful, $fail_hint);
	}

	//施加buff：常见的另一组激活buff技能的执行逻辑，使用对象主要是debuff，不需要事先获得技能（会自动获得）
	//传参$tp代表时间累加方式，1为获得技能时刷新时长，2为时长累加
	//传参$msec代表时间计算时考虑毫秒，1为考虑，0为不考虑。注意不是传一个真实毫秒值进来
	function bufficons_impose_buff($token, $end, $cd, &$pa=NULL, $tp=1, $msec=0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!\skillbase\skill_query($token, $pa)) {
			\skillbase\skill_acquire($token, $pa);
		}
		$fail_hint = '';
		$is_successful = \bufficons\bufficons_set_timestamp($token, $end, $cd, $pa, $tp, $msec);
		if(!$is_successful) {
			$fail_hint = '因技能编号错误或者时间为0等原因，施加异常状态失败！<br>';
		}

		return Array($is_successful, $fail_hint);
	}
}

?>