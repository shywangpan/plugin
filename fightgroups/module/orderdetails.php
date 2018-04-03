<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

	$id = addslashes($_GET['orderid']); 
	if(!empty($id)){
	
		$sql = "SELECT * FROM ".DB::table('ims_tg_order')." WHERE orderno='$id' "; 
		$order = DB::fetch_first($sql); 
		if(empty($order)){
			showmessage($L['s5'].''.$id, createMobileUrl('index'),'error');	
		}
		$sql = "SELECT cname,tel, province, city, county, detailed_address FROM ".DB::table('ims_tg_address')." WHERE id=".$order['addressid']; 
		$adds = DB::fetch_first($sql); 
		if(empty($adds)){
			showmessage($L['s6'].'', createMobileUrl('index'),'error');	
		}

		$sql = "SELECT gname,gprice,oprice,gimg,freight FROM ".DB::table('ims_tg_goods')." WHERE id= ".$order['g_id']; 
		$good = DB::fetch_first($sql);
		if(empty($good)){
		}
	}

	include_once template($plign_name.':index/orderdetails');
?>

