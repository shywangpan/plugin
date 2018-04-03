<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$openid = $_W['openid'];
$op = addslashes($_GET['opt']);
$g_id = addslashes($_GET['g_id']);
$groupnum = addslashes($_GET['groupnum']);
if($op == 'changeaddres'){
	$all = array(
	'g_id' =>$g_id,
	'groupnum' =>$groupnum
	);
}
$address = DB::fetch_all("select * from " . DB::table('ims_tg_address')." where openid='$openid' ");

include_once template($plign_name.':index/addmanage');
?>

