<?php
$operation = !empty($_GET['opt']) ? addslashes($_GET['opt']) : 'remove';
if ($operation=='add') {
	$goodsid = addslashes($_GET['goodsid']) ;
	if (empty($goodsid)) {
		echo 0;
		exit;
	}else{
		$data=array(
			'openid' => $_W['openid'],
			'sid'=>$goodsid
		);
		if($_GET['formhash'] == FORMHASH){
		if (DB::insert('ims_tg_collect', $data)) {
			echo 1;
		}else{
			echo 0;
		}
		}
	}
}
if ($operation=='remove') {
	$goodsid = addslashes($_GET['goodsid']) ;
	if (empty($goodsid)) {
		echo 0;
		exit;
	}else{
		if($_GET['formhash'] == FORMHASH){
		if (DB::delete('ims_tg_collect', array('openid' =>$_W['openid'], 'sid' => $goodsid))) {
			echo 1;
		}else{
			echo 0;
		}
		}
	}
}
?>