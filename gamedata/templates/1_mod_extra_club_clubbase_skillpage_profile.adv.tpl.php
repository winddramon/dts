<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" id="sp_skillpage" name="sp_skillpage" value="技能列表" onclick="$('mode').value='special';$('command').value='viewskills';postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aaeS'; } ?>