<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

if (!$_G['adminid']) {
	return false;
}
require_once DISCUZ_ROOT.'./source/plugin/fightgroups/config.inc.php';
include_once template($plign_name.':view');
?>
