<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_WEATHER__VARS__wthinfo,$___LOCAL_WEATHER__VARS__weather_itemfind_obbs,$___LOCAL_WEATHER__VARS__weather_meetman_obbs,$___LOCAL_WEATHER__VARS__weather_active_obbs,$___LOCAL_WEATHER__VARS__weather_attack_modifier,$___LOCAL_WEATHER__VARS__weather_defend_modifier; $wthinfo=&$___LOCAL_WEATHER__VARS__wthinfo; $weather_itemfind_obbs=&$___LOCAL_WEATHER__VARS__weather_itemfind_obbs; $weather_meetman_obbs=&$___LOCAL_WEATHER__VARS__weather_meetman_obbs; $weather_active_obbs=&$___LOCAL_WEATHER__VARS__weather_active_obbs; $weather_attack_modifier=&$___LOCAL_WEATHER__VARS__weather_attack_modifier; $weather_defend_modifier=&$___LOCAL_WEATHER__VARS__weather_defend_modifier;   ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td colspan="2" class="b1"><span>天气:<?php } else { echo '___aaio'; } ?><?php echo $wthinfo[$weather]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td><?php } else { echo '___aae8'; } ?>