<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><p><span class="lime">BOSS NPC简介</span>：</p>
此类NPC对玩家<span class="yellow">均有较大威胁</span>，在<span class="yellow">没有足够高的防御</span>的情况下，请<span class="yellow">尽可能避免和它们的接触</span>，也<span class="yellow">不要贸然攻击它们</span>，否则很可能导致自己被击杀。<br>
当然，如果成功击杀了这些NPC，它们也会掉落<span class="lime">大量有用的道具或金钱</span>。<br>
<br>
<?php } else { echo '___aapS'; } ?><?php if(is_array(Array(6,5,1,9,88))) { foreach(Array(6,5,1,9,88) as $key) { \npcinfo\npcinfo_get_npc_description_all($key); } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<p><span class="lime">真职人 NPC简介</span>：</p>
此类NPC在开局对<span class="yellow">无防具的玩家有较大威胁</span>，这类NPC的特点是<span class="yellow">防御非常高</span>，且<span class="yellow">武器均带有“电击+冻气+带毒+火焰+音波”属性</span>，很容易导致玩家中异常状态。<br>如果防具很差，或没有属性防御也没钱买药剂，请不要贸然攻击它们，否则很可能导致自己中大量异常状态后难以恢复，苦不堪言。<br>
当然，击杀这些NPC后可以获取<span class="lime">极为优秀的防具</span>，在游戏中后期<span class="lime">击杀它们并拾取它们的防具保护自己</span>往往是玩家取胜的关键。<br>
<br>
<?php } else { echo '___aapT'; } ?><?php if(is_array(Array(11))) { foreach(Array(11) as $key) { \npcinfo\npcinfo_get_npc_description_all($key); } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<p><span class="lime">全息幻想NPC、小兵NPC 简介</span>：</p>
此类NPC对玩家基本无威胁，是玩家<span class="yellow">最主要的熟练度、经验来源，也是最主要的击杀对象</span>。每个小兵NPC掉落金钱220元，是玩家<span class="lime">主要金钱来源</span>。<br>全息幻象掉落<span class="lime">更多的金钱</span>以及<span class="lime">各系优秀的武器或强化道具</span>，往往是<span class="yellow">拉开玩家差距、建立优势乃至取得最终胜利的关键</span>。<br>
<br>
<?php } else { echo '___aapU'; } ?><?php if(is_array(Array(2,90))) { foreach(Array(2,90) as $key) { \npcinfo\npcinfo_get_npc_description_all($key); } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<p><span class="lime">特殊NPC 简介</span>：</p>
此类NPC对玩家无威胁，但当玩家击杀它们后，它们会变身为<span class="yellow">“第二形态”</span>，此时<span class="yellow">攻击力会变得极强</span>。<br>可别不小心击杀了它们后被第二形态秒杀哦～ 不过，当自己处于劣势时，偷偷击杀这类NPC，并期望对手撞上它们并被它们秒杀，也是不错的翻盘思路哦～<br>
<br>
<?php } else { echo '___aapV'; } ?><?php if(is_array(Array(14))) { foreach(Array(14) as $key) { \npcinfo\npcinfo_get_npc_description_all($key); } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<p><span class="lime">佣兵NPC 简介</span>：</p>
佣兵是<u><A href="help.php#称号与技能">富家子弟的称号技能</A></u>召唤出的NPC。不同的佣兵实力差距很大，能召唤出什么佣兵纯靠运气。<br>
以下是所有佣兵的列表：<input class="cmdbutton" onclick="bubblebox_show('skill56_merc');" value="点击这里查看" type="button"><br>
<br>
<?php } else { echo '___aapW'; } ?><?php \bubblebox\bubblebox_set_style('id:yl_npc;height:560;width:772;cancellable:1;margin-top:20px;margin-bottom:20px;margin-left:20px;margin-right:10px;'); include template('MOD_BUBBLEBOX_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><p style="margin-left:0px; margin-top:0px; margin-right:0px; margin-bottom:0px;">
以下是英灵殿中的主要NPC简介。<br><br>
<span class="lime">天神NPC 简介</span>：
</p>
<p>天神称号授予对ACFUN大逃杀的创造和发展有里程碑式贡献的人物。目前只有冴月麟和四面两人。<br>
虽然理论上还应该有初七等人，但是由于GM的偷懒并没有加入。该等级的NPC对玩家基本就是<span class="yellow">一击即死</span>。<br>
但是因为没有<span class="yellow">伤害制御和强袭姿态</span>，威胁实际上不如武神。 <br></p>
<br>
<?php } else { echo '___aapX'; } ?><?php if(is_array(Array(22))) { foreach(Array(22) as $key) { \npcinfo\npcinfo_get_npc_description_all($key); } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<p><span class="lime">武神NPC 简介</span>：</p>
<p>武神称号授予参与过ACFUN大逃杀开发工作的人员。武神装备有接近10000总防御的防具，拥有很高的基础攻防，并各有特色。<br>
武神统一拥有<span class="yellow">“伤害制御”</span>属性，<span class="lime">有90%的几率把受到的伤害压缩到2000点</span>。<br>
由於大多数武神拥有<span class="yellow">“强袭姿态”</span>，先攻率远高于其他NPC，是进军英灵殿玩家的最大威胁。<br></p>
<br>
<?php } else { echo '___aapY'; } ?><?php if(is_array(Array(21))) { foreach(Array(21) as $key) { \npcinfo\npcinfo_get_npc_description_all($key); } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br> 
<?php } else { echo '___aapZ'; } ?><?php include template('MOD_BUBBLEBOX_END'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><p><span class="lime">英灵殿NPC 简介</span>：</p>
此外，<span class="yellow">英灵殿</span>中还有大量高强度NPC，供有兴趣的PVE玩家挑战。<br>
它们与正常游戏几乎没有什么关系，但如果你依然有兴趣，请点击下面的按钮查看它们的数据。<br>
<input class="cmdbutton" onclick="bubblebox_show('yl_npc');" value="点击这里查看" type="button">
<br><?php } else { echo '___aap0'; } ?>