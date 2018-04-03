<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

	$op = intval($_GET['opt']); 
	$openid = $_W['openid'];//ÓÃ»§µÄopenid

	if($op==0){
		$sql = "SELECT * FROM ".DB::table('ims_tg_order')." WHERE openid = '$openid'  ORDER BY id desc";
		$orders = DB::fetch_all($sql); 
	}elseif ($op==1) {					
		$sql = "SELECT * FROM ".DB::table('ims_tg_order')." WHERE openid = '$openid'  and status =0 ORDER BY id desc"; 
		$orders = DB::fetch_all($sql); 
	}elseif ($op==2) {					
		$sql = "SELECT * FROM ".DB::table('ims_tg_order')." WHERE openid =  '$openid'  and status =2 ORDER BY id desc"; 
		$orders = DB::fetch_all($sql); 
	}else{
		showmessage($L['s5'].'', createMobileUrl('myorder',array('op' => '0')) , 'error');
	}

	$sql_order = "SELECT g_id FROM ".DB::table('ims_tg_order')." WHERE openid = '$openid'";
	$sql_order = "SELECT gname, gimg FROM ".DB::table('ims_tg_goods')." WHERE   and id in ('$sql_order')";
	
	$sql_0 = "SELECT count(*) as num FROM ".DB::table('ims_tg_order')." a,".DB::table('ims_tg_goods')." b WHERE a.openid = '$openid'  and b.id = a.g_id"; 
	$orders_num_0 = DB::fetch_first($sql_0); 
	$orders_num_0 = $orders_num_0['num'];
	if(empty($orders_num_0)){
		$orders_num_0 = 0;								
	}

	$sql_1 = "SELECT count(*) as num FROM ".DB::table('ims_tg_order')." a,".DB::table('ims_tg_goods')." b WHERE a.openid = '$openid'  and b.id = a.g_id and status = 0";
	$orders_num_1 = DB::fetch_first($sql_1); 
	$orders_num_1 = $orders_num_1['num'];
	if(empty($orders_num_1)){
		$orders_num_1 = 0;								
	}

	$sql_2 = "SELECT count(*) as num FROM ".DB::table('ims_tg_order')." a, ".DB::table('ims_tg_goods')." b WHERE a.openid = '$openid'   and b.id = a.g_id and status = 2"; 
	$orders_num_2 = DB::fetch_first($sql_2); 
	$orders_num_2 = $orders_num_2['num'];
	if(empty($orders_num_2)){
		$orders_num_2 = 0;								
	}
	include_once template($plign_name.':index/myorder');
?>