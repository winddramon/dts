<?php

namespace skill453
{
	$skill453_cd = 36000;
	$skill453_buff_lose_time = 120;	//失去buff的时间，秒
	$skill453_factor = 10;
	
	function init() 
	{
		define('MOD_SKILL453_INFO','card;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[453] = '狂击';
		$bufficons_list[453] = Array(
			'disappear' => 0,
			'clickable' => 0,
		);
	}
	
	function acquire453(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill453'));
		\skillbase\skill_setvalue(453,'end_ts',1,$pa);
		\skillbase\skill_setvalue(453,'cd_ts',0,$pa);
		\skillbase\skill_setvalue(453,'target','',$pa);
		\skillbase\skill_setvalue(453,'tarpid',0,$pa);
		\skillbase\skill_setvalue(453,'cnt',0,$pa);
	}
	
	function lost453(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked453(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(453,$pa) && !$pa['is_counter']) 	//反击不触发
		{
			sk453_skill_status_update(\bufficons\bufficons_check_buff_state(453, $pa),$pa);
			eval(import_module('sys','logger','skill453'));
			$tarpid = (int)\skillbase\skill_getvalue(453,'tarpid',$pa); 
			$skill453_count = (int)\skillbase\skill_getvalue(453,'cnt',$pa); 
			
			if ($pd['pid']!=$tarpid || $skill453_count==0)
			{
				//主动攻击其他目标，重置层数
				$skill453_count=0;
				\skillbase\skill_setvalue(453,'target',$pd['name'],$pa); 
				\skillbase\skill_setvalue(453,'tarpid',$pd['pid'],$pa); 
			}
			$skill453_count++;
			$rat=$skill453_count*$skill453_factor;
			if ($active)
				$log.='<span class="yellow b">你对敌人的连续攻击使伤害增加了'.$rat.'%！</span><br>';
			else  $log.='<span class="yellow b">敌人对你的连续攻击使伤害增加了'.$rat.'%！</span><br>';
			$r=Array(1+$rat/100.0);
			\skillbase\skill_setvalue(453,'cnt',$skill453_count,$pa); 
			\bufficons\bufficons_set_timestamp(453,0,$skill453_buff_lose_time,$pa);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function sk453_skill_status_update($st,&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill453'));
		if(3 == $st) {//冷却完成就判定降低
			$now = get_var_in_module('now','sys');
			$skill453_end_ts = (int)\skillbase\skill_getvalue(453,'end_ts',$pa); 
			$skill453_count = (int)\skillbase\skill_getvalue(453,'cnt',$pa); 
			if($skill453_count > 0) {
				//自动失去buff
				$tm=$now-$skill453_end_ts;
				$down=min(floor($tm/$skill453_buff_lose_time),$skill453_count);
				if ($down>0)
				{
					$skill453_count-=$down;
					\skillbase\skill_setvalue(453,'cnt',$skill453_count,$pa);
					if($skill453_count>0){
						$skill453_end_ts+=$skill453_buff_lose_time*$down;
						\skillbase\skill_setvalue(453,'end_ts',$skill453_end_ts,$pa);
						$skill453_cd_ts=$skill453_end_ts+$skill453_buff_lose_time;
						\skillbase\skill_setvalue(453,'cd_ts',$skill453_cd_ts,$pa);
						$st = 2;
					}else{
						$st = 3;
					}
				}
			}
		}
		return $st;
	}

	//提示文字的重载
	function bufficons_display_single($token, $config, &$pa=NULL) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(453 == $token && \skillbase\skill_query(453, $pa) && check_unlocked453($pa)) {
			//在确定冷却时间显示数值之前先刷新一下本技能状态
			sk453_skill_status_update(\bufficons\bufficons_check_buff_state($token, $pa),$pa);
		}

		list($src, $config_ret) = $chprocess($token, $config, $pa);

		if(453 == $token && \skillbase\skill_query(453, $pa) && check_unlocked453($pa)) {
			eval(import_module('sys','skill453'));
			$skill453_target = \skillbase\skill_getvalue(453,'target', $pa); 
			$skill453_count = (int)\skillbase\skill_getvalue(453,'cnt', $pa); 
			$rat=$skill453_count*$skill453_factor;
			if ($skill453_count>0 && 2 == $config_ret['style'])
			{
				$rm = $config_ret['totsec'] - $config_ret['nowsec'];
				//由于js怪异的写法，这里需要把时间精确放在第二个<span>里面
				$config_ret['activate_hint'] = $config_ret['cooling_hint'] = "<span>你对{$skill453_target}造成的最终伤害将增加{$rat}%<br></span><span>{$rm}</span>秒内没有攻击将会自动失去一层效果";
				$config_ret['corner-text'] = $skill453_count;
			}
			else
			{
				$config_ret['activate_hint'] = "你连续主动攻击同一目标时<br>造成的伤害每次增加{$skill453_factor}%";
			}
		}

		return Array($src, $config_ret);
	}
}

?>
