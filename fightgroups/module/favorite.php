<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$weid = $_W['uniacid'];
$favorite = DB::fetch_all("SELECT * FROM " . DB::table('ims_tg_collect') . " WHERE uniacid = '{$_W['uniacid']}' AND openid = '{$_W['openid']}'");
if (!empty($favorite)) {
	foreach ($favorite as $key => $value) {
		$goods = DB::fetch_first("SELECT * FROM " . Db::table('ims_tg_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND id = '{$value['sid']}'");
		$favorite[$key]['goods'] = $goods;
	}
}
include_once template($plign_name.':index/favorite');
?>