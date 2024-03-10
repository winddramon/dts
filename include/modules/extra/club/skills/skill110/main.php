<?php

namespace skill110
{
	$skill110_cd = 240;
	
	function init()
	{
		define('MOD_SKILL110_INFO','club;locked;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[110] = '通才';
		$bufficons_list[110] = Array(
			'onclick' => "$('mode').value='special';$('command').value='skill110_special';$('subcmd').value='castsk110';postCmd('gamecmd','command.php',this);",
		);
	}
	
	function acquire110(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(110,'end_ts',1,$pa);	
		\skillbase\skill_setvalue(110,'cd_ts',0,$pa);	
	}
	
	function lost110(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked110(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=5;
	}
	
	function skill110_tempskill_list(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$tsklist = array();
		$acquired_skills = \skillbase\get_acquired_skill_array($pa);
		foreach ($acquired_skills as $skillid)
		{
			$tsk_expire = \skillbase\skill_getvalue($skillid, 'tsk_expire', $pa);
			if (!empty($tsk_expire)) $tsklist[] = $skillid;
		}
		return $tsklist;
	}
	
	function skill110_learn_tempskill($skillid, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$acquired_skills = \skillbase\get_acquired_skill_array($pa);
		if (!in_array($skillid, $acquired_skills))
		{
			$log .= "你没有这个技能。<br>";
			return;
		}
		eval(import_module('sys'));
		$tsk_expire = \skillbase\skill_getvalue($skillid, 'tsk_expire', $pa);
		if (empty($tsk_expire) || ($now > $tsk_expire))
		{
			$log .= "输入参数不正确。<br>";
			return;
		}
		\skillbase\skill_delvalue($skillid, 'tsk_expire', $pa);
		eval(import_module('clubbase'));
		$log .= "你记住了技能<span class=\"yellow b\">「{$clubskillname[$skillid]}」</span>。<br>";
	}
	
	function cast_skill110()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(110, $sdata) || !check_unlocked110($sdata)) 
		{
			$log .= '你没有这个技能。';
			return;
		}
		$skill110_skillid = (int)get_var_input('skill110_skillid');
		if (!empty($skill110_skillid))
		{
			eval(import_module('skill110'));
			list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(110, 0, $skill110_cd);
			if(!$is_successful) {
				$log .= $fail_hint;
				return;
			}
			skill110_learn_tempskill($skill110_skillid, $sdata);
			$mode = 'command';
			return;
		}
		include template(MOD_SKILL110_CASTSK110);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($mode == 'special' && $command == 'skill110_special' && get_var_input('subcmd')=='castsk110') 
		{
			cast_skill110();
			return;
		}
		$chprocess();
	}
}

?>