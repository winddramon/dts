<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill54')); $___TEMP_SKILL_ID=54; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
圣盾
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php $clv=(int)\skillbase\skill_getvalue(54,'lvl'); $totlv = count($upgradecost)-1;  ?>
你受到的所有属性伤害<span class="yellow">-<?php echo $dmgreduction[$clv]?>%</span><br>
<?php if($upgradecost[$clv] !== -1) { ?>
可花费<span class="lime"><?php echo $upgradecost[$clv]?></span>点技能升级，升级后减伤提高至<span class="yellow">-<?php echo $dmgreduction['1']?>%</span>
<?php } ?>
<br>
</td>
<td class=b3 width=46>
<?php if($upgradecost[$clv] !== -1) { if($skillpoint>=$upgradecost[$clv]) { ?>
<input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill54_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="升级">
<?php } else { ?>
<input type="button" style="width:46px" disabled="true" value="升级">
<?php } } ?>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
3级时解锁
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
