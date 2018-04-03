<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$openid = $_W['openid'];
$groupnum =intval($_GET['groupnum']);
$id = intval($_GET['gid']);
$tuan_id = intval($_GET['tuan_id']);
$all = array(
	'groupnum' =>$groupnum,
	'id'=> $id
);
if (!empty($id)) {
	$goods = DB::fetch_first("select * from ".DB::table('ims_tg_goods')." where id = $id");
	
	$adress = DB::fetch_first("select * from ".DB::table('ims_tg_address')." where openid='$openid' and status=1");
	if(!empty($groupnum)){
		if($groupnum>1){
			$price = $goods['gprice'];
			$is_tuan=1;
			if(empty($tuan_id)){
				$tuan_first = 1;
			}else{
				$tuan_first = 0;
			}
			$success = 1;
		}else{
			$price = $goods['oprice'];
			$is_tuan=0;
			$tuan_first = 0;
			$success = 0;
		}
	}
}

include_once template($plign_name.':index/confirm');
?>