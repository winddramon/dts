<?php

namespace skill768
{
	$skill768_cd = 60;
	
	//道具的数值依次为：名称、类别、最低效果值、最高效果值、耐久值、属性
	$skill768_items = array
	(
		array('垃圾', array('X','PB','PB2'), 1, 100, 1, ''),
		array('卡片福袋', 'VO1', 1, 1, 1, '166'),
		array('凸眼鱼', array('Y','PB','PB2'), 30, 140, 1, ''),
		array('安康鱼', array('HB','HB','HB','PB'), 100, 300, 1, ''),
		array('河豚鱼', array('HB','PB2','PB2','PB2'), 300, 1100, 1, ''),
		array('金枪鱼', array('HB','HB','MH'), 30, 160, 1, ''),
		array('沙丁鱼', array('HB','SCC1'), 5, 30, 1, ''),
		array('石斑鱼', array('HB','SCA1'), 30, 80, 1, ''),
		array('鲷鱼', array('HB','SC01'), 30, 80, 1, ''),
		array('鲈鱼', array('HB','SC01'), 30, 80, 1, ''),
		array('鲑鱼', array('HB','MH'), 60, 160, 1, ''),
		array('鲤鱼', array('HB','SCA1'), 30, 130, 1, ''),
		array('鲶鱼', array('HB','HB','MH'), 30, 160, 1, ''),
		array('鲱鱼', array('HB','SCB1'), 20, 60, 1, ''),
		array('鳗鱼', array('HB','HB','MH'), 30, 200, 1, ''),
		array('章鱼', array('HB','HB','WG'), 30, 130, 1, ''),
		array('鱿鱼', array('HB','HB','WG'), 30, 130, 1, ''),
		array('鲟鱼', array('HB','HB','MH'), 30, 160, 1, ''),
		array('龙虾', array('HB','HB','WK'), 30, 120, 1, ''),
		array('螃蟹', array('HB','HB','WK'), 30, 120, 1, ''),
		array('鲨鱼鳍', array('HB','PB2'), 120, 120, 1, ''),
		array('萨卡斑甲鱼', array('HB','WP'), 60, 180, 1, ''),
	);
	
	function init() 
	{
		define('MOD_SKILL768_INFO','card;upgrade;');
		eval(import_module('clubbase','bufficons'));
		$clubskillname[768] = '钓客';
		$bufficons_list[768] = Array(
			'dummy' => 1,//占位符，如果其他设置全部都自动生成的话可以占位用……
		);
	}
	
	function acquire768(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill768'));
		\skillbase\skill_setvalue(768,'fishids','',$pa);
		\skillbase\skill_setvalue(768,'complete','0',$pa);
		\skillbase\skill_setvalue(768,'end_ts',$now-1,$pa);	
		\skillbase\skill_setvalue(768,'cd_ts',$now+$skill768_cd,$pa);
	}
	
	function lost768(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked768(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function add_fishid768($fishid, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$fishids768 = get_fishids768($pa);
		if (!in_array($fishid, $fishids768)) $fishids768[] = $fishid;
		\skillbase\skill_setvalue(768, 'fishids', encode768($fishids768), $pa);
		if (count($fishids768) < 22) return;
		elseif ((int)\skillbase\skill_getvalue(768,'complete',$pa) == 0 && strpos($pa['ara'], '钓鱼竿') !== false)
		{
			eval(import_module('logger'));
			$log .= '<span class="L5 b">你已经钓到了所有的鱼类！</span><br><br>';
			$pa['arae'] = max($pa['arae'], 2333);
			if (is_numeric($pa['aras'])) $pa['aras'] = max($pa['aras'], 2333);
			foreach(array('B', 'b', 'h', 'V', 'Z') as $v)
			{
				if (!\itemmain\check_in_itmsk($v, $pa['arask'])) $pa['arask'] .= $v;
			}
			if (defined('MOD_LOGISTICS'))
			{
				$pt = '这个包裹上没有署名的样子……';
				$prizecode = 'getlogitem_306;getlogitemnum_1;';
				include_once './include/messages.func.php';
				message_create($pa['name'], '神秘快递', $pt, $prizecode);
			}
			\skillbase\skill_setvalue(768,'complete','1',$pa);
		}
	}
	
	function get_fishids768(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return decode768(\skillbase\skill_getvalue(768, 'fishids', $pa));
	}
	
	function encode768($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return implode('_', $arr);
	}
	
	function decode768($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($str)) return Array();
		return explode('_', $str);
	}
	
	function activate768()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','sys','skill768'));
		\player\update_sdata();
		$lastuse = \skillbase\skill_getvalue(768,'end_ts');
		list($is_successful, $fail_hint) = \bufficons\bufficons_activate_buff(768, 0, $skill768_cd);
		if(!$is_successful) {
			$log .= $fail_hint;
			return;
		}
		$log.='<span class="lime b">技能「钓客」发动成功。</span><br>';
		get_skill768_item($lastuse);
	}
	
	function get_skill768_item($lastuse)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','skill768'));
		$fid = array_rand($skill768_items);
		add_fishid768($fid, $sdata);
		$fi = $skill768_items[$fid];
		$itm0 = $fi[0];
		$itmk0 = is_array($fi[1]) ? array_randompick($fi[1]) : $fi[1];
		$itme0 = rand($fi[2], $fi[3]);
		$itms0 = $fi[4];
		$itmsk0 = $fi[5];
		addnews(0, 'bskill768', $name, $itm0);
		\itemmain\itemget();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill768') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「钓客」</span>，获得了<span class=\"yellow b\">{$b}</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>