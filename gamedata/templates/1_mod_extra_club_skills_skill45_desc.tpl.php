<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill45')); $___TEMP_SKILL_ID=45; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
重拳
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<span class="yellow">战斗技</span>：本次攻击物理伤害<span class="yellow">+30%</span>；发动消耗<span class="yellow"><?php echo $ragecost?></span>点怒气。<br>
</span></td>
<td class=b3 width=46>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
3级时解锁
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
