<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

	$orderno = addslashes($_GET['orderno']);	 
	$openid = $_W['openid'];	

	if (!empty($orderno)) {
		$sql = "SELECT * FROM ".DB::table('ims_tg_order')." WHERE orderno='$orderno' ";
		$order = DB::fetch_first($sql);
		if(empty($order)){
			alertgo($L['ph1'].'', createMobileUrl('orderdetails', array('orderid'=>$orderno)),'error');
			$tip = "failure";
		}else{
			if($_GET['formhash'] == FORMHASH){
				$ret = DB::update('ims_tg_order', array('status'=>3), array('orderno'=>$orderno));
				$tip = "sucess";
			}	
		}
	}
	$url_9080 = createMobileUrl('orderdetails', array('orderid'=>$orderno));  
    Header("Location: $url_9080");  
?>