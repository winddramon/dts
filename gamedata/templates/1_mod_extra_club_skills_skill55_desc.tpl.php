<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill55')); $___TEMP_SKILL_ID=55; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
家教
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php $skill55_nchoice=(int)\skillbase\skill_getvalue(55,'l'); if($skill55_nchoice==0) { ?>
学习任意一个非限定技能<br>
可以随意变更学习的技能，但每次变更技能需花费<span class="yellow"><?php echo $skill55_need?></span>元<br>
<?php } else { ?>
你当前习得的技能是“<span class="yellow"><?php echo $clubskillname[$skill55_nchoice]?></span>”<br>
可以重新学习一个技能，但这将花费<span class="yellow"><?php echo $skill55_need?></span>元<br>
<?php } \bubblebox\bubblebox_set_style('id:learntable;height:400;width:655;cancellable:1;margin-right:8;'); include template('MOD_BUBBLEBOX_START'); ?>
<p style="margin-left:0px; margin-top:0px; margin-right:0px; margin-bottom:0px;">
以下是你可以学习的称号技能列表。限定技不可以被学习，因此没有显示。<br>
<span class="yellow">请注意使用“查看”按钮查看技能时，技能的解锁条件没有显示。</span>请阅读帮助以获得完整的技能解锁要求。<br>
<?php if($skill55_nchoice!=0) { ?>
本次学习将花费<span class="yellow"><?php echo $skill55_need?></span>元，并失去目前已习得的技能<span class="yellow"><?php echo $clubskillname[$skill55_nchoice]?></span>。<br>
<?php } ?>
<br>
</p>
<?php \sklearn_util\get_skilllearn_table('\\skill55\\sklearn_checker55'); include template('MOD_BUBBLEBOX_END'); ?>
</span></td>
<td class=b3 width=46>
<?php if($skill55_nchoice==0 || $money>=$skill55_need) { ?>
<input type="button" style="width:46px" onclick="bubblebox_show('learntable');" value="学习">
<?php } else { ?>
<input type="button" style="width:46px" disabled="true" value="学习">
<?php } ?>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
3级时解锁
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
