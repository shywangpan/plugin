<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

	$profile = DB::fetch_first("SELECT * FROM " . DB::table('ims_tg_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$_W['openid']}'");
	include_once template($plign_name.':index/person');
?>
