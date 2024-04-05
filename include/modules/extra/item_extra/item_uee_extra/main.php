<?php

namespace item_uee_extra
{
	//允许解禁小游戏的模式，与该模式中解禁小游戏需要调多少个数值，可以是4,5,7,8
	$allow_uee_extra_gametype_num = array(18 => 5, 20 => 4);
	
	//解禁小游戏中的显示用字符串
	$uee_extra_words = array(
		'<span class="red b">कल्पना</span>',
		'<span class="white b">Fantaisie</span>',
		'<span class="yellow b">Phantasie</span>',
		'<span class="cyan b">Фантазия</span>',
		'<span class="lime b">خيال</span>',
		'<span class="purple b">פנטזיה</span>',
		'<span class="linen b">Φαντασία</span>',
		'<span class="gold b">幻想</span>',
	);
	
	//解禁小游戏中的显示用操作名
	$uee_extra_buttons = array('猜测密码','伪造输入','寻找漏洞','洪水攻击','殴打键盘','社会工程','木马程序','烧香拜佛');
	
	function init()
	{
	}
	
	//获取解禁小游戏需要调多少个数值
	function uee_extra_get_hack_num()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','item_uee_extra'));
		if (!isset($allow_uee_extra_gametype_num[$gametype])) return 0;
		return $allow_uee_extra_gametype_num[$gametype];
	}
	
	//使用移动PC可选择原PC功能或玩解禁小游戏
	function itemuse_uee_core($itmn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','item_uee_extra'));
		if (\skillbase\skill_query(503,$sdata) && \skill503\check_unlocked503($sdata)) return $chprocess($itmn); //专业选手不许下场（503是幻禁）
		if (!isset($allow_uee_extra_gametype_num[$gametype])) return $chprocess($itmn);
		$uee_extra_cmd = (int)get_var_input('uee_extra_cmd');
		$ueen = $itmn;
		if($uee_extra_cmd == 0) {
			ob_start();
			include template(MOD_ITEM_UEE_EXTRA_USE_UEE_CMD);
			$cmd = ob_get_contents();
			ob_end_clean();
		}
		elseif ($uee_extra_cmd == 1)
		{
			return $chprocess($itmn);
		}
		elseif ($uee_extra_cmd == 2)
		{
			$uee_extra_pos = (int)get_var_input('uee_extra_pos');
			if($uee_extra_pos == 0) {
				if (empty($gamevars['hack_state'])) itemuse_uee_extra_reset();
				ob_start();
				include template(MOD_ITEM_UEE_EXTRA_USE_UEE_EXTRA);
				$cmd = ob_get_contents();
				ob_end_clean();
			}
			elseif ($uee_extra_pos <= 0 || $uee_extra_pos > $allow_uee_extra_gametype_num[$gametype])
			{
				$log .= "输入参数错误。<br>";
				$mode = 'command';
				return true;
			}
			else
			{
				$ret = itemuse_uee_extra($uee_extra_pos);
				if ($ret)
				{
					eval(import_module('logger'));
					$hack = 1;
					$log .= '干扰成功了！全部禁区都被解除了！<br>';
					addnews($now,'hack',$name);
					\sys\systemputchat($now,'hack');
					save_gameinfo();
				}
				else
				{
					include template(MOD_ITEM_UEE_EXTRA_USE_UEE_EXTRA);
					$cmd = ob_get_contents();
					ob_end_clean();
				}
				return $ret;//没解开不耗电
			}
			return true;
		}
	}
	
	//解禁小游戏的游戏变量初始化
	function itemuse_uee_extra_reset()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$hack_num = uee_extra_get_hack_num();
		$hack_state = range(0, $hack_num - 1);
		shuffle($hack_state);
		$gamevars['hack_state'] = $hack_state;
		if (!isset($hack_seq))
		{
			$hack_seq = range(0, $hack_num - 1);
			shuffle($hack_seq);
			$gamevars['hack_seq'] = $hack_seq;
		}
		save_gameinfo();
	}
	
	//解禁小游戏
	function itemuse_uee_extra($pos)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$hack_num = uee_extra_get_hack_num();
		if ($pos <= 0 || $pos > $hack_num) return false;
		
		if (empty($gamevars['hack_state'])) itemuse_uee_extra_reset();
		
		$pos -= 1;
		$gamevars['hack_state'][$pos] = ($gamevars['hack_state'][$pos] + 1) % $hack_num;
		$pos2 = ($pos + 1) % $hack_num;
		$gamevars['hack_state'][$pos2] = ($gamevars['hack_state'][$pos2] + 1) % $hack_num;
		$pos3 = ($pos + 2) % $hack_num;
		$gamevars['hack_state'][$pos3] = ($gamevars['hack_state'][$pos3] + 1) % $hack_num;
		
		//结果检查
		$hs_count = count(array_unique($gamevars['hack_state']));
		if ($hs_count == 1)
		{
			itemuse_uee_extra_reset();
			return true;
		}
		else
		{
			save_gameinfo();
			return false;
		}
	}
	
}

?>
