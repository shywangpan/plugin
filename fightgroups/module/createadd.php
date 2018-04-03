<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$groupnum=addslashes($_GET['groupnum']);
$g_id = intval(addslashes($_GET['g_id']));
$tuan_id = intval(addslashes($_GET['tuan_id']));
	
$all = array(
'g_id' =>$g_id,
'groupnum' =>$groupnum
);
$operation = addslashes($_GET['opt']);
$id= intval($_GET['aid']);
$weid = $_W['uniacid'];
$openid = $_W['openid'];

if ($operation == 'display') {
	if($id){
		$addres = DB::fetch_first("SELECT * FROM " . DB::table('ims_tg_address')." where id={$id}");
		if(!empty($all)){
			$addresschange = 1;
		}
	}
}elseif($operation == 'conf'){
	if(!empty($all)){
		$con = 1;
	}
}elseif ($operation == 'post' && $_GET['formhash'] == FORMHASH) {
	if(!empty($id)){
		
		$status = DB::fetch_firs("SELECT * FROM " . DB::table('ims_tg_address')." where id={$id}");
		$data=array(
		'openid' => $openid,
		'uniacid'=>$weid,
		'cname'=>ic(addslashes($_GET['lxr_val'])),
		'tel'=>addslashes($_GET['mobile_val']),
		'province'=>ic(addslashes($_GET['province_val'])),
		'city'=>ic(addslashes($_GET['city_val'])),
		'county'=>ic(addslashes($_GET['area_val'])),
		'detailed_address'=>ic(addslashes($_GET['address_val'])),
		'status'=>$status['status'],
		'addtime'=>time()
		);
		if(DB::update('ims_tg_address',$data,array('id' => $id))){
			echo 1;
			exit;
		}else{
			echo 0;
			exit;

		}
	}else{
		$data1=array(
		'openid' => $openid,
		'uniacid'=>$weid,
		'cname'=>ic( addslashes($_GET['lxr_val'])),
		'tel'=>addslashes($_GET['mobile_val']),
		'province'=>ic(addslashes($_GET['province_val'])),
		'city'=>ic(addslashes($_GET['city_val'])),
		'county'=>ic(addslashes($_GET['area_val'])),
		'detailed_address'=>ic(addslashes($_GET['address_val'])),
		'status'=>'1',
		'addtime'=>time()
		);
	
		
		$moren =  DB::fetch_first("SELECT * FROM ".DB::table('ims_tg_address')." where status=1 and openid='$openid'");
		DB::update('ims_tg_address',array('status' => 0),array('id' => $moren['id']));
		if(DB::insert('ims_tg_address',$data1)){

			echo 1;
			exit;
		}else{
			echo 0;
			exit;
		}
	}

}elseif($operation == 'deletes' && $_GET['formhash'] == FORMHASH){

	if($id){
		if(DB::update('ims_tg_address',array('id' => $id )))
		{
			echo 1;
			exit;
		}else{
			echo 0;
			exit;
		}
	}else{
		echo 2;
		exit;
	}

}elseif($operation == 'moren' && $_GET['formhash'] == FORMHASH){
	if(!empty($id))
	{
		$moren =  DB::fetch_first("SELECT * FROM ".DB::table('ims_tg_address')." where status=1 and openid='$openid'");
		DB::update('ims_tg_address',array('status' => 0),array('id' => $moren['id']));
		if(DB::update('ims_tg_address',array('status' =>1),array('id' => $id))){
			echo 1;
			exit;
		}else{
			echo 0;
			exit;
		}

	}else{
		echo 2;
		exit;
	}

}

include_once template($plign_name.':index/createadd');
 ?>