<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

  $tuan_id = intval($_GET['tuan_id']);

  if(!empty($tuan_id)){
  	$profile = DB::fetch_first("SELECT * FROM " . DB::table('ims_tg_member') . " WHERE   from_user = '{$_W['openid']}'");
  	$profileall = DB::fetch_all("SELECT * FROM " . DB::table('ims_tg_member') . " WHERE uniacid ='{$_W['uniacid']}' GROUP BY from_user");

    $orders = DB::fetch_all("SELECT * FROM " . DB::table('ims_tg_order') . " WHERE uniacid ='{$_W['uniacid']}' and tuan_id = {$tuan_id} and status = 1 order by id asc");
   
    foreach($orders as $key=>$value){
    	$order['g_id']=$value['g_id'];
    }

 
    $myorder = DB::fetch_first("SELECT * FROM " . DB::table('ims_tg_order') . " WHERE openid = '{$_W['openid']}' and tuan_id = {$tuan_id} and status = 1");
  	
  	$goods = DB::fetch_first("SELECT * FROM ".DB::table('ims_tg_goods')." WHERE id = {$order['g_id']}");

    $sql= "SELECT * FROM ".DB::table('ims_tg_order')." where tuan_id=$tuan_id and status = 1 and pay_type <> 0";
    $alltuan = DB::fetch_all($sql);
    $item = array();
    foreach ($alltuan as $num => $all) {
    $item[$num] = $all['id'];
    }
 
    $n = intval($goods['groupnum']) - count($item);
    $nn = intval($goods['groupnum'])-1;
    $arr = array();
    for ($i=0; $i <$n ; $i++) { 
       $arr[$i]=0;
    }
    
    $tuan_first_order = DB::fetch_first("SELECT * FROM " . DB::table('ims_tg_order') . " WHERE tuan_id = {$tuan_id} and tuan_first = 1");
    $hours=$tuan_first_order['endtime'];
    $time = time();
    $date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); 
    $endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
  
    $date1 = date('Y-m-d H:i:s',$time); 
    $lasttime2 = strtotime($endtime)-strtotime($date1);
    $lasttime = $tuan_first_order['endtime'];
    
  }


  include_once template($plign_name.':index/group');
  
?>
