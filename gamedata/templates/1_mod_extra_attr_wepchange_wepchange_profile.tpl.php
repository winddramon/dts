<?php if(!defined('IN_GAME')) exit('Access Denied'); if(strpos($wepsk,'j')!==false) { ?>
<input type="button" class="cmdbutton" id="sp_weapon" name="sp_weapon" value="武器模式" onclick="$('command').value='special';$('subcmd').name='sp_cmd';$('subcmd').value='sp_weapon';postCmd('gamecmd','command.php');this.disabled=true;">
<?php } ?>
