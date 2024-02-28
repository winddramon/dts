<?php

namespace npc_pick_item
{
	$gametype_npc_pick_item = Array(18);//暂时只在荣耀模式测试

	$npc_pick_item_on = false;//本功能是否开启的开关，只在开局rs_game时开启

	$npctype_npc_pick_item = Array(//开局自动拾取一部分道具的NPC类型及方针
		90 => Array(
			'num' => Array(0,2),
		)
	);

	$pool_npc_npc_pick_item = Array();//开局决定哪些NPC截留的数据池

	$pool_itemno_npc_pick_item = Array();//随机数池

	$pool_item_npc_pick_item = Array();//截留的道具池

	function init() {}
	
	//判定本功能是否开启
	function is_npc_pick_allowed(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(in_array(get_var_in_module('gametype', 'sys'), get_var_in_module('gametype_npc_pick_item', 'npc_pick_item'))) return true;
		return false;
	}

	//在地图道具放置前决定截留哪些数据，在放置后刷给NPC
	//注意只会在开局触发
	function lay_mapitem($lpls = -1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(is_npc_pick_allowed() && !\map\get_area_wavenum()) {
			eval(import_module('sys','npc_pick_item'));
			
			//决定有哪些NPC截留数据，分别截留几个，在什么位置，分别占用随机道具的哪几号
			if(!empty($npctype_npc_pick_item)){
				$npc_pick_item_on = true;
				$pnum_total = 0;
				$where = "'".implode("','", array_keys($npctype_npc_pick_item))."'";
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE type IN ($where)");
				while($d = $db->fetch_array($result)){
					$pnum_min = $npctype_npc_pick_item[$d['type']]['num'][0];
					$pnum_max = $npctype_npc_pick_item[$d['type']]['num'][1];
					$pnum = rand($pnum_min, $pnum_max);

					$pool_npc_npc_pick_item[$d['pid']] = Array(
						'pnum' => $pnum,
						'ppos' => Array(),
					);
					
					if($pnum){
						$i = 0;
						foreach(Array(1,2,3,4,5,6,0) as $j){
							if(empty($d['itms'.$j])) {
								$pool_npc_npc_pick_item[$d['pid']]['ppos'][] = $j;
								$i ++ ;
								if($i >= $pnum) break;
							}
						}
						$pnum_total += $pnum;
					}
					
				}
				//暂时按总数2800来计算，后面处理时会再次缩放
				$tmp_randno_pool = Array();
				for($i = 0; $i < $pnum_total; $i++){
					do{
						$tmp_pno = rand(0,2800);
					}while(in_array($tmp_pno, $pool_itemno_npc_pick_item));
					$pool_itemno_npc_pick_item[] = $tmp_pno;
				}
				//file_put_contents('itemno0.txt',var_export($pool_itemno_npc_pick_item,1)."\r\n");
			}
		}
		$chprocess($lpls);
		if(!empty($npc_pick_item_on) && !empty($pool_item_npc_pick_item)) {
			//给预先定好的NPC刷上道具
			$upd = Array();
			$i = 0;
			foreach($pool_npc_npc_pick_item as $nid => $nd){
				$tmp_upd = Array();
				foreach($nd['ppos'] as $pos) {
					$itmarr = $pool_item_npc_pick_item[$pool_itemno_npc_pick_item[$i]];
					//file_put_contents('itmarr.txt', var_export($pool_itemno_npc_pick_item[$i],1).' '.var_export($pool_item_npc_pick_item[$pool_itemno_npc_pick_item[$i]],1)."\r\n", FILE_APPEND);
					$tmp_upd['itm'.$pos] = $itmarr[0];
					$tmp_upd['itmk'.$pos] = $itmarr[1];
					$tmp_upd['itme'.$pos] = $itmarr[2];
					$tmp_upd['itms'.$pos] = $itmarr[3];
					$tmp_upd['itmsk'.$pos] = $itmarr[4];
					$i++;
				}
				if(!empty($tmp_upd)) {
					$tmp_upd['pid'] = $nid;
					$upd[] = $tmp_upd;
				}
			}
			if(!empty($upd)) {
				$db->multi_update("{$tablepre}players", $upd, 'pid');//一次性更新player表
			}
			$npc_pick_item_on = false;
		}
	}

	//计算道具总数并对随机数池进行缩放
	//这个钩子函数竟然用上了，太感动了
	function itemlist_data_process($data){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ret = $chprocess($data);
		if(is_npc_pick_allowed() && get_var_in_module('npc_pick_item_on', 'npc_pick_item')){
			eval(import_module('npc_pick_item'));
			$i_99 = 0;//第一个随机道具的编号，注意这里必须把刷的数目考虑在内。这里假设地图随机道具全部放在文件后部
			$t_99 = 0;//随机道具总数
			$t_i = 0;//道具总数
			$in = sizeof($ret);
			for($i = 0; $i < $in; $i++){
				if(!empty($ret[$i]) && substr($ret[$i], 0, 1) != '=' && strpos($ret[$i],',')!==false){
					$tmp_arr = explode(',', $ret[$i]);
					if(0 == $tmp_arr[0] || 99 == $tmp_arr[0]) {//只计算开局刷出的
						$t_i += $tmp_arr[2];
						if(99 != $tmp_arr[1]) {
							$i_99 += $tmp_arr[2];
						}
					}
				}
			}
			$t_99 = $t_i - $i_99;
			foreach($pool_itemno_npc_pick_item as &$v){
				$v = (int)$i_99 + floor($v * $t_99 / 2800);
			}
			//file_put_contents('itemno.txt', $i_99.' '.$t_99.' '.$t_i."\r\n".var_export($pool_itemno_npc_pick_item,1)."\r\n", FILE_APPEND);
		}
		return $ret;
	}

	function mapitem_single_data_attr_process($iname, $ikind, $ieff, $ista, $iskind, $imap, $count = -1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$data = $chprocess($iname, $ikind, $ieff, $ista, $iskind, $imap, $count);
		if(is_npc_pick_allowed() && get_var_in_module('npc_pick_item_on', 'npc_pick_item') && $count >= 0) {
			eval(import_module('npc_pick_item'));
			if(in_array($count, $pool_itemno_npc_pick_item)){
				$pool_item_npc_pick_item[$count] = $data;
				//file_put_contents('item.txt', $count.' => '.implode(',',$data)."\r\n", FILE_APPEND);
				$data[3] = 0;//消除这一行的数据
			}
		}
		return $data;
	}
}
?>