<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/fightgroups/config.inc.php';
if (!$_G['adminid']) {
	return false;
}

include_once template($plign_name.':help');
?>
