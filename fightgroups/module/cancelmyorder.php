<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

	$orderno = addslashes($_GET['orderno']);	
	$openid = addslashes($_GET['openid']);	
	
	
	
	
	header('location:plugin.php?id=fightgroups&op=myorder&opt=0');
?>