<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<input type="button" class="cmdbutton" id="itemmerge" name="itemmerge" value="包裹管理" onclick="$('command').value='itemmain';$('subcmd').name='itemcmd';$('subcmd').value='itemmerge';postCmd('gamecmd','command.php');this.disabled=true;">
<!--<input type="button" class="cmdbutton" id="itemdrop" name="itemdrop" value="道具移动" onclick="$('command').value='itemmain';$('subcmd').name='itemcmd';$('subcmd').value='itemmove';postCmd('gamecmd','command.php');this.disabled=true;">-->
<input type="button" class="cmdbutton" id="itemdrop" name="itemdrop" value="道具丢弃" onclick="$('command').value='itemmain';$('subcmd').name='itemcmd';$('subcmd').value='itemdrop';postCmd('gamecmd','command.php');this.disabled=true;">
<br>