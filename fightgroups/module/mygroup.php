<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

	 $op = intval($_GET['opt']); 
	 $openid = $_W['openid'];

	if($op==0){
			$sql = "SELECT * FROM ".DB::table('ims_tg_order')." a inner join  ".DB::table('ims_tg_goods')." b on  b.id = a.g_id  WHERE a.openid = '$openid'  and a.status = 1 and a.pay_type !=0  order by ptime desc";
			$orders = DB::fetch_all($sql); 
		
			$mytuan = DB::fetch_all("SELECT * FROM ".DB::table('ims_tg_order')." where openid = '{$openid}' and is_tuan = 1 ");
			
	}
	
	include_once template($plign_name.':index/mygroup');
?>