<?php

namespace skill182
{
	$tmp_hint182 = '';//用于暂存提示信息

	function init() 
	{
		define('MOD_SKILL182_INFO','club;hidden;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[182] = '共鸣';
		$bufficons_list[182] = Array(
			'disappear' => 1,
			'clickable' => 1,
			'onclick' => '',
		);
	}
	
	function acquire182(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(182, 'skey', '', $pa);
		\skillbase\skill_setvalue(182, 'svalue', '', $pa);
		\skillbase\skill_setvalue(182, 'stime', '', $pa);
		\skillbase\skill_setvalue(182, 'sstart', 0, $pa);
	}
	
	function lost182(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ss_record_tempbuff($key, $value, $bufftime, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$expire = $now + $bufftime;
		if (!\skillbase\skill_query(182, $pa)) {
			\skillbase\skill_acquire(182, $pa);
		}
		$skey = \skillbase\skill_getvalue(182, 'skey', $pa);
		$svalue = \skillbase\skill_getvalue(182, 'svalue', $pa);
		//此处的time是结束时间
		$stime = \skillbase\skill_getvalue(182, 'stime', $pa);
		if ('' === $skey)
		{
			$lskey = array();
			$lsvalue = array();
			$lstime = array();
		}
		else
		{
			$lskey = explode('_', $skey);
			$lsvalue = explode('_', $svalue);
			$lstime = explode('_', $stime);
		}
		if(empty($lstime) || $now > max($lstime)) $newflag = 1;
		$lskey[] = $key;
		$lsvalue[] = $value;
		$lstime[] = $expire;
		$skey = implode('_',$lskey);
		$svalue = implode('_',$lsvalue);
		$stime = implode('_',$lstime);
		\skillbase\skill_setvalue(182, 'skey', $skey, $pa);
		\skillbase\skill_setvalue(182, 'svalue', $svalue, $pa);
		\skillbase\skill_setvalue(182, 'stime', $stime, $pa);
		if(!empty($newflag)) {//新获得技能时记录开始时间
			\skillbase\skill_setvalue(182, 'sstart', $now, $pa);
		}
		
		$s = "持续时间<span class=\"yellow b\">".$bufftime."</span>秒";
		return $s;
	}

	//歌系统有自己的一套实现。但歌部分暂时不支持按毫秒计算
	function bufficons_check_buff_state($token, &$pa=NULL, $msec=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($token, $pa, $msec);
		if(182 == $token && \skillbase\skill_query(182,$pa)) {
			eval(import_module('sys','bufficons','skill182'));
			$skey = \skillbase\skill_getvalue(182, 'skey', $pa);
			if (!empty($skey))
			{
				$svalue = \skillbase\skill_getvalue(182, 'svalue', $pa);
				$stime = \skillbase\skill_getvalue(182, 'stime', $pa);
				$lskey = explode('_', $skey);
				$lsvalue = explode('_', $svalue);
				$lstime = explode('_', $stime);
				$tmax = max($lstime) - $now;
				if ($tmax > 0)
				{
					$ret = 1;
					$sstart = \skillbase\skill_getvalue(182, 'sstart', $pa);
					if(!empty($sstart)) {
						$tmp_totsec = max($lstime) - $sstart;
						$tmp_nowsec = $now - $sstart;
					}else{
						$tmp_totsec = $tmax;
						$tmp_nowsec = 0;
					}
					//这里为了省一步get，放到check_state()函数里
					$tmp_hint182 = show_songbuff_hint($lskey, $lsvalue);
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
	//因为在bufficons_display_single()的时候已经把$bufficons_list[182]的值导入进$config了，在前面那个bufficons_check_buff_state()改动是没用的
	function bufficons_display_single($token, $config, &$pa=NULL) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($src, $config_ret) = $chprocess($token, $config, $pa);
		if(182 == $token && \skillbase\skill_query(182,$pa)) {
			eval(import_module('skill182'));
			$config_ret['hint'] = $tmp_hint182;
			$config_ret['activate_hint'] = '歌唱效果已经结束';
		}
		return Array($src, $config_ret);
	}
	
	function show_songbuff_hint($lskey, $lsvalue)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$s = '';
		eval(import_module('sys','song'));
		foreach ($lskey as $i => $key)
		{
			if (!empty($key))
			{
				if ($lsvalue[$i] > 0) $o = '+';
				else $o ='';
				$timing_str = '';
				if(!empty($uip['timing']['songbuff'.$i]['timing_r'])) $timing_str = $uip['timing']['songbuff'.$i]['timing_r'];
				$s .= "{$ef_type[$key]}{$o}{$lsvalue[$i]} 剩<span class=\"yellow b\" id=\"songbuff{$i}\">$timing_str</span>秒<br>";
			}
		}
		return $s;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		if (\skillbase\skill_query(182,$pa))
		{
			$skey = \skillbase\skill_getvalue(182, 'skey', $pa);
			if (!empty($skey))
			{
				eval(import_module('sys'));
				$svalue = \skillbase\skill_getvalue(182, 'svalue', $pa);
				$stime = \skillbase\skill_getvalue(182, 'stime', $pa);
				$lskey = explode('_', $skey);
				$lsvalue = explode('_', $svalue);
				$lstime = explode('_', $stime);			
				$flag = 0;
				foreach ($lstime as $i => $time)
				{
					if ((int)$time < $now)
					{
						$flag = 1;
						$ek = $lskey[$i];
						$change = -(int)$lsvalue[$i];		
						if ($change > 0)
						{
							if (!isset($pa['m'.$ek]) || $pa[$ek] < $pa['m'.$ek])
							{
								if (isset($pa['m'.$ek]) && ($pa[$ek] + $change > $pa['m'.$ek])) $change = $pa['m'.$ek] - $pa[$ek];
								$pa[$ek] += $change;
							}
						}
						else
						{
							if ($pa[$ek] + $change < 1 && in_array($ek, array('hp','mhp','sp','msp'))) $change = 1 - $pa[$ek];
							elseif ($pa[$ek] + $change < 0) $change = -$pa[$ek];
							$pa[$ek] += $change;
						}
						unset($lskey[$i]);
						unset($lsvalue[$i]);
						unset($lstime[$i]);
					}
				}
				if ($flag)
				{
					$skey = implode('_',$lskey);
					$svalue = implode('_',$lsvalue);
					$stime = implode('_',$lstime);
					\skillbase\skill_setvalue(182, 'skey', $skey, $pa);
					\skillbase\skill_setvalue(182, 'svalue', $svalue, $pa);
					\skillbase\skill_setvalue(182, 'stime', $stime, $pa);
				}
			}
		}
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		init_songbuff_timing_process();
	}
	
	function ss_sing($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($sn);
		init_songbuff_timing_process();
	}
	
	function init_songbuff_timing_process()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$skey = \skillbase\skill_getvalue(182, 'skey', $pa);
		if (!empty($skey))
		{
			$svalue = \skillbase\skill_getvalue(182, 'svalue', $pa);
			$stime = \skillbase\skill_getvalue(182, 'stime', $pa);
			$lskey = explode('_', $skey);
			$lstime = explode('_', $stime);
			foreach ($lskey as $i => $key)
			{
				if (!empty($key)) init_songbuff_timing($i, $lstime[$i] - $now);
			}
		}
	}

	function init_songbuff_timing($i, $rm){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if(!isset($uip['timing'])) $uip['timing'] = array();
		$timing_r = sprintf("%02d", floor($rm/60)).':'.sprintf("%02d", $rm%60);
		$uip['timing']['songbuff'.$i] = array(
			'on' => true,
			'mode' => 0,
			'timing' => $rm * 1000,
			'timing_r' => $timing_r,
			'format' => 'mm:ss'
		);
	}
	
}

?>