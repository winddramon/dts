<?php

namespace skill962
{
	$skill962_cd = 180;
	$skill962_base_cost = 200;
	
	function init()
	{
		define('MOD_SKILL962_INFO','card;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[962] = '寻路';
		$bufficons_list[962] = Array(
			'onclick' => "$('mode').value='special';$('command').value='skill960_special';$('subcmd').value='show';postCmd('gamecmd','command.php');this.disabled=true;",
			'activate_hint' => '打开任务界面以更换任务',
		);
	}
	
	function acquire962(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill962'));
		\skillbase\skill_setvalue(962,'end_ts',$now-1,$pa);	
		\skillbase\skill_setvalue(962,'cd_ts',$now+$skill962_cd,$pa);
	}
	
	function lost962(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked962(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//return 0:没有这个技能 1:CD中，金钱不足（小于500*2^任务等级） 2:CD中，金钱足够 3:CD完毕
	function check_skill962_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$st = \bufficons\bufficons_check_buff_state(962, $pa);
		if(!$st) return 0;
		
		eval(import_module('skill962'));
		$invscore = (int)\skillbase\skill_getvalue(960,'invscore',$pa);
		$stage = \instance10\get_stage($invscore);
		$skill962_cost = $skill962_base_cost * pow(2, $stage);
		if (2 == $st){
			if ($pa['money'] < $skill962_cost) return 1;
			return 2;
		}
		return 3;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
	
		if ($mode == 'special' && $command == 'skill962_special') 
		{
			activate962();
			return;
		}
		
		$chprocess();
	}
	
	function activate962()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill960','skill962','player','logger'));
		\player\update_sdata();
		
		$st = check_skill962_state($sdata);
		if (!$st)
		{
			$log.='你没有这个技能！<br>';
			return;
		}elseif (!\skillbase\skill_query(960, $sdata)){
			$log.='你没有任务技能！<br>';
			return;
		}elseif ($st==1){
			$log.='你的金钱不足！<br>';
			return;
		}
		
		$taskid = get_var_input('taskid_submit');
		$taskarr = \skill960\get_taskarr($sdata);
		if (!isset($tasks_info[$taskid]) || !in_array($taskid, $taskarr))
		{
			$log .= '输入参数错误。<br>';
			return;
		}
		if ($st==2)
		{
			$skill962_cost = get_skill962_cost($sdata);
			$money -= $skill962_cost;
			$log .= '<span class="lime b">消耗了'.$skill962_cost.'元，</span>';
		}
		$log .= '<span class="lime b">技能「寻路」发动成功。</span><br>';
		$flag = \bufficons\bufficons_set_timestamp(962, 0, $skill962_cd);
		if (!$flag)
		{
			$log.='发动失败！<br>';
			return;
		}
		\skill960\remove_task($sdata, $taskid);
		if(empty($itms0)) {//为了防止卡死，手里是空的才显示界面
			ob_start();
			include template(MOD_SKILL960_CASTSK960);
			$cmd=ob_get_contents();
			ob_end_clean();
		}
	}
	
	function get_skill962_cost(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill962'));
		$invscore = (int)\skillbase\skill_getvalue(960,'invscore',$pa);
		$stage = \instance10\get_stage($invscore);
		$skill962_cost = $skill962_base_cost * pow(2, $stage);
		return $skill962_cost;
	}
	
	function show_extra_task_cmds(&$pa, $taskid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $taskid);
		$st = check_skill962_state($pa);
		if ($st == 2)
		{
			$skill962_cost = get_skill962_cost($pa);
			$ret .= '<input type="button" class="cmdbutton" value="更换" title="<span class=\'red b\'>需要支付'.$skill962_cost.'元</span>" onclick="$(\'taskid_submit\').value=\''.$taskid.'\';$(\'command\').value=\'skill962_special\';postCmd(\'gamecmd\',\'command.php\');this.disabled=true;">';
		}
		elseif ($st == 3)
		{
			$ret .= '<input type="button" class="cmdbutton" value="更换" onclick="$(\'taskid_submit\').value=\''.$taskid.'\';$(\'command\').value=\'skill962_special\';postCmd(\'gamecmd\',\'command.php\');this.disabled=true;">';
		}
		elseif ($st == 1)
		{
			$ret .= '<input type="button" class="cmdbutton" disabled="true" value="更换">';
		}
		return $ret;
	}

}

?>