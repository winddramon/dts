<?php

namespace instance10
{
	function init() {
		eval(import_module('skillbase'));
		if(!isset($valid_skills[20])) {
			$valid_skills[20] = array();
		}
		$valid_skills[20] += array(181,951,952,960,962,964);
	}
	
	//肉鸽模式自动选择鸽勇者
	function get_enter_battlefield_card($card){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$card = $chprocess($card);
		if (20 == $gametype){
			$card=1002;
		}
		return $card;
	}
	
	//肉鸽模式自动选择鸽勇者，禁止其他卡片
	function card_validate_get_forbidden_cards($card_disabledlist, $card_ownlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		$card_disabledlist = $chprocess($card_disabledlist, $card_ownlist);
		if (20 == $gametype)
		{
			foreach($card_ownlist as $cv){
				if(1002 != $cv) $card_disabledlist[$cv][]='e3';
			}
		}
		return $card_disabledlist;
	}
	
	//肉鸽模式选卡界面特殊显示
	function card_validate_display($cardChosen, $card_ownlist, $packlist, $hideDisableButton){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		list($cardChosen, $card_ownlist, $packlist, $hideDisableButton) = $chprocess($cardChosen, $card_ownlist, $packlist, $hideDisableButton);	
		if (20 == $gametype)
		{
			$cardChosen = 1002;//自动选择鸽勇者
			$card_ownlist[] = 1002;
			$packlist[] = $cards[1002]['pack'] = 'Pungeon';
			$hideDisableButton = 0;
		}
		return array($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
	}
	
	//肉鸽模式入场道具
	function init_enter_battlefield_items($ebp){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ebp = $chprocess($ebp);
		eval(import_module('sys'));
		if (20 == $gametype){
			$ebp['itm5'] = '全恢复药剂'; $ebp['itmk5'] = 'Ca'; $ebp['itme5'] = 1; $ebp['itms5'] = 3;$ebp['itmsk5'] = '';
		}
		return $ebp;
	}
	
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance10'));
		if (20 == $gametype){
			return $npcinfo_instance10;
		}else return $chprocess();
	}
	
	//商店功能之后用事件替换
	function get_shopconfig(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (20 == $gametype){
			$file = __DIR__.'/config/shopitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//网购可以正常访问商店，但商品较少
	function get_shop_tag_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','instance10'));
		if (20 == $gametype){
			return $shop_tag_list10;
		}
		return $chprocess();
	}
	
	function get_itemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (20 == $gametype){
			$file = __DIR__.'/config/mapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_startingitemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (20 == $gametype){
			$file = __DIR__.'/config/stitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_startingwepfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (20 == $gametype){
			$file = __DIR__.'/config/stwep.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_trapfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (20 == $gametype){
			$file = __DIR__.'/config/trapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//不会连斗
	function checkcombo($time){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','gameflow_combo'));
		if(20 == $gametype) return;
		$chprocess($time);
	}
	
	//开局天气初始化；开局时，只有随机6个地点不为禁区
	function rs_game($xmode = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($xmode);
		eval(import_module('sys'));
		if ((20 == $gametype)&&($xmode & 2)) 
		{
			$weather = 1;
			//添加禁区
			$plsnum = sizeof($arealist);
			$areanum = $plsnum - 6;
			//进行一次回避禁区……真丑陋！
			$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type=90");
			$pls_available = \map\get_safe_plslist();
			while($sub = $db->fetch_array($result))
			{
				$pid = $sub['pid'];
				$sub['pls'] = array_randompick($pls_available);
				$db->array_update("{$tablepre}players",$sub,"pid='$pid'");
			}
		}
	}
	
	//开局90分钟后禁区
	function rs_areatime(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(20 == $gametype){
			return $starttime + 60*90;//1禁恒为90分钟
		}
		return $chprocess();
	}
	
	//保持0禁
	function get_area_wavenum(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (20 == $gametype) return 0;
		return $chprocess();
	}
	
	//禁区时结束游戏
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if (20 == $gametype){
			if($alivenum <= 0){//这个确定要这么判定吗？过禁时全灭就结束？
				\sys\gameover($atime,'end1');
			}
			elseif (\map\get_area_wavenum() >= 1){//限时1禁
				//胜利条件改为使用特定道具结束游戏
				// $result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0 ORDER BY card LIMIT 1");
				// $wdata = $db->fetch_array($result);
				// $winner = $wdata;
				// \sys\gameover($atime,'end8',$winner);
				\sys\gameover($atime,'end1');
			}
			return;
		}
		$chprocess($atime);
	}
	
	//商店功能之后用事件替换
	function check_in_shop_area($p)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (20 == $gametype)
		{
			// if (!isset($gamevars['instance10_shops']))
			// {
				// $gamevars['instance10_shops'] = array_randompick(range(1, 33), 4);
				// $gamevars['instance10_shops'][] = 0;
			// }
			// save_gameinfo();
			// return in_array($p, $gamevars['instance10_shops']);
			return false;
		}
		else return $chprocess($p);
	}
	
	//肉鸽模式中，商店道具的禁区次数改用游戏阶段判定，现取消
	// function shopitem_row_data_process($data)
	// {
		// if (eval(__MAGIC__)) return $___RET_VALUE;
		// $ret = $chprocess($data);
		// eval(import_module('sys'));
		// if (20 == $gametype)
		// {
			// if (!isset($gamevars['instance10_stage'])) return $ret;
			// if ((int)$ret[3] + 1 >= $gamevars['instance10_stage']) $ret[3] = 0;
		// }
		// return $ret;
	// }
	
	//合成产物的效果、耐久、属性可能发生变化
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (20 == $gametype)
		{
			if (in_array($itmk0[0], array('W','D')))
			{
				$itme0 = max(round((100 + rand(0,30))/100 * $itme0), 1);
				if ($itms0 != $nosta) $itms0 = max(round((100 + rand(0,30))/100 * $itms0), 1);
				if ($itmk0[0] == 'W')
				{
					$dice = rand(0,99);
					if ($dice < 3)
					{
						$tmpsk = array_randompick(array('f','k','t','d','r','n','y'));
						if (!\itemmain\check_in_itmsk($tmpsk, $itmsk0)) $itmsk0 .= $tmpsk;
					}
					elseif ($dice < 30)
					{
						$tmpsk = array_randompick(array('u','e','i','w','p','N','H','z'));
						if (!\itemmain\check_in_itmsk($tmpsk, $itmsk0)) $itmsk0 .= $tmpsk;
					}
				}
				elseif ($itmk0[0] == 'D')
				{
					$dice = rand(0,99);
					if ($dice < 3)
					{
						$tmpsk = array_randompick(array('B','b','Z','h'));
						if (!\itemmain\check_in_itmsk($tmpsk, $itmsk0)) $itmsk0 .= $tmpsk;
					}
					elseif ($dice < 30)
					{
						$tmpsk = array_randompick(array('A','a','P','K','G','C','D','F','R','q','U','I','E','W','H','M','m','z'));
						if (!\itemmain\check_in_itmsk($tmpsk, $itmsk0)) $itmsk0 .= $tmpsk;
					}
				}
			}
		}
		$chprocess();
	}
	
	//记录吃技能核心次数，已取消
	// function use_skcore_success(&$pa)
	// {
		// if (eval(__MAGIC__)) return $___RET_VALUE;
		// eval(import_module('sys'));
		// if (20 == $gametype)
		// {
			// if (\skillbase\skill_query(951,$pa))
			// {
				// $sc_count = (int)\skillbase\skill_getvalue(951,'sc_count',$pa);
				// \skillbase\skill_setvalue(951,'sc_count',$sc_count+1,$pa);
			// }
		// }
		// $chprocess($pa);
	// }
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];	
		if (20 == $gametype)
		{
			//无法使用移动PC
			if (strpos($itmk, 'EE') === 0)
			{
				$log .= "你使用了{$itm}，却发现没有可以连接上的网络。怎么会这样？<br>";
				return;
			}
			//每个人只能吃7个技能核心，已取消
			// elseif (strpos($itmk, 'SC') === 0)
			// {
				// $sc_count = (int)\skillbase\skill_getvalue(951,'sc_count',$sdata);
				// if ($sc_count >= 7)
				// {
					// $log .= "<span class=\"yellow b\">你已经使用过7个技能核心，无法再使用了。</span><br>";
					// return;
				// }
			// }
			//使用结局道具
			elseif (strpos($itmk, 'Y') === 0 || strpos($itmk, 'Z') === 0)
			{
				if ($itm == '测试用结局道具·幸存')
				{
					if ($alivenum > 1)
					{
						$log .= "<span class=\"red b\">还有其他存活的玩家。</span><br>";
						return;
					}
					else
					{
						$winner_flag = 2;
						\player\player_save($sdata, 1);
						$url = 'end.php';
						\sys\gameover($now, 'end2', $name);
					}
				}
				elseif ($itm == '测试用结局道具·解离')
				{
					$ueen = $theitem['itmn'];
					$uee_extra_pos = (int)get_var_input('uee_extra_pos');
					if($uee_extra_pos == 0) {
						if (empty($gamevars['hack_state'])) \item_uee_extra\itemuse_uee_extra_reset();
						ob_start();
						include template(MOD_ITEM_UEE_EXTRA_USE_UEE_EXTRA);
						$cmd = ob_get_contents();
						ob_end_clean();
					}
					elseif ($uee_extra_pos <= 0 || $uee_extra_pos > \item_uee_extra\uee_extra_get_hack_num())
					{
						$log .= "输入参数错误。<br>";
						$mode = 'command';
						return true;
					}
					else
					{
						$ret = \item_uee_extra\itemuse_uee_extra($uee_extra_pos);
						if ($ret)
						{
							$winner_flag = 7;
							\player\player_save($sdata, 1);
							$url = 'end.php';
							\sys\gameover($now, 'end7', $name);
						}
						else
						{
							include template(MOD_ITEM_UEE_EXTRA_USE_UEE_EXTRA);
							$cmd = ob_get_contents();
							ob_end_clean();
						}
					}
					return;
				}
			}
		}
		$chprocess($theitem);
	}
	
	//进场后随机获得三个1级任务
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		eval(import_module('sys'));
		if (20 == $gametype)
		{
			\skill960\get_rand_task($pa, 1, 3);
		}
	}
	
	//失去任务时，根据调查度获得新的任务
	function remove_task(&$pa, $taskid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $taskid);
		eval(import_module('sys'));
		if (20 == $gametype)
		{
			eval(import_module('skill960'));
			if (isset($tasks_info[$taskid]['rank']) && $tasks_info[$taskid]['rank'] <= 10)
			{
				$rank_old = $tasks_info[$taskid]['rank'];
				$rank_new = get_newtask_rank($pa);
				//如果没升层，只刷新完成的任务
				if ($rank_old == $rank_new)
				{
					if ($rank_new >= 7) return;
					\skill960\get_rand_task($pa, $rank_new, 1);
				}
				else //如果升层，刷新全部任务
				{
					\skill960\remove_task($pa, 'all');
					if ($rank_new >= 7) \skill960\get_rand_task($pa, $rank_new, 2);
					else \skill960\get_rand_task($pa, $rank_new, 3);
					//获得BOSS任务，在3,5,7层
					if ($rank_new >= 7) \skill960\add_task($pa, 303);
					elseif ($rank_new == 5) \skill960\add_task($pa, 302);
					elseif ($rank_new == 3) \skill960\add_task($pa, 301);
				}
			}
		}
	}
	
	//获取任务奖励约等于完成任务，在这里加禁区解锁的判定
	function get_task_reward(&$pa, $taskid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $taskid);
		eval(import_module('sys'));
		if (20 == $gametype)
		{
			if (!isset($gamevars['instance10_topinv'])) $gamevars['instance10_topinv'] = 0;
			if (!isset($gamevars['instance10_stage'])) $gamevars['instance10_stage'] = 1;
			$invscore = (int)\skillbase\skill_getvalue(960,'invscore',$pa);
			if ($invscore > $gamevars['instance10_topinv'])
			{
				//最高调查度每加10，推进游戏1个阶段，同时减少4个禁区
				$map_unlock = floor($invscore/10) - floor($gamevars['instance10_topinv']/10);
				if ($map_unlock > 0)
				{
					//解锁新地点
					eval(import_module('map','logger'));
					$log .= "<span class=\"yellow b\">你发现了新的地点！</span><br>";
					$areanum -= 4 * $map_unlock;
					$areanum = max($areanum, 0);
					//增加新npc
					$log .= "<span class=\"yellow b\">新的敌人加入了战场……</span><br>";
					$newstage = get_stage($invscore);
					for ($i=$gamevars['instance10_stage']+1; $i<=$newstage; $i++)
					{
						\randnpc\add_randnpc(2*$i-1, 20, 0, 0, 0, 0);
						\randnpc\add_randnpc(2*$i, 20, 0, 0, 0, 0);
						//刷新boss，未完成
						if ($i == 3) {}
						elseif ($i == 5) {}
						elseif ($i == 7) {}
					}
					$gamevars['instance10_stage'] = $newstage;
					addnews($now, 'instance10_newstage', $pa['name']);
					//刷新商店
					\sys\rs_game(32);
				}
				$gamevars['instance10_topinv'] = $invscore;
			}
			save_gameinfo();
		}
	}
	
	//根据调查度决定新任务等级
	function get_newtask_rank(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$invscore = (int)\skillbase\skill_getvalue(960,'invscore',$pa);
		return get_stage($invscore);
	}
	
	//根据调查度计算游戏阶段
	function get_stage($invscore)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//以后可能需要细调，不写成算式了
		if ($invscore < 10) return 1;
		elseif ($invscore < 20) return 2;
		elseif ($invscore < 30) return 3;
		elseif ($invscore < 40) return 4;
		elseif ($invscore < 50) return 5;
		elseif ($invscore < 60) return 6;
		else return 7;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'instance10_newstage') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}完成了任务，解锁了新的地区！同时，新的敌人加入了战场！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>