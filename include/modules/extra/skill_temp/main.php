<?php

namespace skill_temp
{
	$tsk_hint = '';//用于暂存提示信息

	function init() {
		eval(import_module('bufficons'));
		$bufficons_list['tsk'] = Array(
			'disappear' => 1,
			'clickable' => 1,
			'onclick' => '',
			'activate_hint' => '临时技能生效时间已经全部结束',
			'src' => 'img/icon_mb.gif',
		);
	}

	//判定给定的技能id的标签是不是在临时技能的考虑范围内
	//目前是只判定带club或者带card标签的技能
	function check_skillinfo_allowed_for_skill_temp($skillid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return (\skillbase\check_skill_info($skillid,'club') || \skillbase\check_skill_info($skillid,'card'));
	}
	
	//该函数可保留空接口，临时技能的判定考虑移到别的模块
	function check_skill_available($skillid, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($skillid, $pa);
		$tsk_expire = \skillbase\skill_getvalue($skillid, 'tsk_expire', $pa);
		if (\skillbase\skill_query($skillid, $pa) && !empty($tsk_expire))
		{
			$now = get_var_in_module('now', 'sys');
			if ($now > $tsk_expire)
			{
				\skillbase\skill_lost($skillid, $pa);
				\skillbase\skill_delvalue($skillid, 'tsk_expire', $pa);
			}
		}
	}

	//获得临时技能的处理
	//暂时不支持按毫秒处理
	//需要是一个在$clubskillname定义了名称的技能
	function skill_temp_acquire($skillid, $buff_time = 0, $buff_lvl = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','clubbase','player','logger'));
		if (defined('MOD_SKILL'.$skillid) && !empty($clubskillname[$skillid]))//这里暗示需要给状态技能定义名称
		{
			if (\skillbase\skill_query($skillid))
			{
				if (empty(\skillbase\skill_getvalue($skillid, 'tsk_expire')))
				{
					//临时技能不会盖掉非临时的技能
					$log .= "但是这里面的技巧你早就刻在DNA里了！<br>";
					return;
				}
				//否则失去该技能，用于刷新该技能状态
				\skillbase\skill_lost($skillid);
			}
			if (!empty($buff_time))
			{
				$log .= "你获得了临时技能「<span class=\"yellow b\">".$clubskillname[$skillid]."</span>」，持续时间<span class=\"yellow b\">".$buff_time."</span>秒！<br>";
			}
			else $log .= "你获得了状态「<span class=\"yellow b\">".$clubskillname[$skillid]."</span>」！<br>";
			
			\skillbase\skill_acquire($skillid);

			//如果是需要发动一次的技能（如隐身），立刻发动
			$activate_funcname = 'skill'.$skillid.'\\activate'.$skillid;
			if(function_exists($activate_funcname)) {
				$tmp_log = $log;//阻止中途的函数显示
				$activate_funcname();
				$log = $tmp_log;
			}

			//如果$buff_time非空，则设置持续时间；仅适用于非时效技能
			if (!empty($buff_time)) \skillbase\skill_setvalue($skillid, 'tsk_expire', $now + $buff_time);
			//如果$buff_lvl非空，则设置技能等级
			if (!empty($buff_lvl)) \skillbase\skill_setvalue($skillid, 'lvl', $buff_lvl);
		}
		else
		{
			$log .= "参数错误，这应该是一个BUG，请联系管理员。<br>";
			return;
		}
	}

	//技能图标显示。对应的$token标记为tsk，应该是第一个不是数字的$token
	//暂时不支持按毫秒计算
	function bufficons_check_buff_state($token, &$pa=NULL, $msec=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($token, $pa, $msec);
		if('tsk' == $token) {
			eval(import_module('sys','bufficons','skill_temp'));
			$arr = \skillbase\get_acquired_skill_array($pa);
			$tsk_idlist = array();
			$tsk_rmlist = array();
			$buff_tmax = 0;
			//所有临时技能共用一个icon，要不然感觉有显示不下的风险
			foreach ($arr as $key)
			{
				if (defined('MOD_SKILL'.$key.'_INFO') && check_skillinfo_allowed_for_skill_temp($key))
				{
					$tsk_expire = \skillbase\skill_getvalue($key, 'tsk_expire', $pa);
					if (!empty($tsk_expire))
					{
						$tsk_idlist[] = $key;
						$tsk_rmlist[] = $tsk_expire - $now;
						if ($tsk_expire > $buff_tmax) $buff_tmax = $tsk_expire;
					}
				}
			}
			$buff_rmmax = $buff_tmax - $now;
			if (!empty($tsk_idlist))
			{
				if ($now < $buff_tmax)
				{
					$ret = 1;
					//可能同时有多个时长不同的buff，icon仅提示有buff存在
					$tmp_totsec = $buff_rmmax;
					$tmp_nowsec = 0;
					$tsk_hint = show_tsk_hint($tsk_idlist);
				}
				else
				{
					$ret = 3;
				}
			}
		}
		return $ret;
	}

	//提示文字的重载
	function bufficons_display_single($token, $config, &$pa=NULL) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($src, $config_ret) = $chprocess($token, $config, $pa);
		if('tsk' == $token) {
			eval(import_module('skill_temp'));
			$config_ret['hint'] = $tsk_hint;
		}
		return Array($src, $config_ret);
	}
	
	//技能图标的剩余时间提示
	function show_tsk_hint($idlist)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$s = '';
		foreach ($idlist as $id)
		{
			eval(import_module('sys','clubbase'));
			//显示每个技能的剩余时间。因为只有act()后会执行init_buff_timing()，载入game.php则没有定义，所以这里需要做个判定。
			$display_t = !empty($uip['timing']['skill'.$id]['timing_r']) ? $uip['timing']['skill'.$id]['timing_r'] : '---';
			$s .= "「{$clubskillname[$id]}」 剩<span class=\"yellow b\" id=\"skill{$id}\">{$display_t}</span>秒<br>";
		}
		return $s;
	}
	
	//技能图标的倒计时初始化
	function init_buff_timing($buffid, $buffrm){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if(!isset($uip['timing'])) $uip['timing'] = array();
		$timing_r = sprintf("%02d", floor($buffrm/60)).':'.sprintf("%02d", $buffrm%60);
		$uip['timing']['skill'.$buffid] = array(
			'on' => true,
			'mode' => 0,
			'timing' => $buffrm * 1000,
			'timing_r' => $timing_r,
			'format' => 'mm:ss'
		);
	}
	
	//每次行动后，准备临时技能的显示信息
	//因为执行顺序，不能在check_state那里合并
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('sys','player'));
		$arr = \skillbase\get_acquired_skill_array($sdata);
		foreach ($arr as $key)
		{
			if (defined('MOD_SKILL'.$key.'_INFO') && check_skillinfo_allowed_for_skill_temp($key))
			{
				$tsk_expire = \skillbase\skill_getvalue($key, 'tsk_expire', $pa);
				if(!empty($tsk_expire))	init_buff_timing($key, $tsk_expire - $now);
			}
		}
	}
}

?>