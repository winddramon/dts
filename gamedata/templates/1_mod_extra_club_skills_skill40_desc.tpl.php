<?php if(!defined('IN_GAME')) exit('Access Denied'); $___TEMP_SKILL_ID=40; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
活化
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
每次攻击基础攻击<span class="yellow">+1</span>点，每次被攻击基础防御<span class="yellow">+1</span>点
</span></td>
<td class=b3 width=46>

</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">

</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
