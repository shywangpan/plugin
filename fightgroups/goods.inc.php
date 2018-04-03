<?php
if(!defined('IN_DISCUZ') && !$_G['adminid']) {
	exit('Access Denied');
}
$pmod = 'goods';
require_once DISCUZ_ROOT.'./source/plugin/fightgroups/config.inc.php';
$operation = isset($_GET['act'])? addslashes($_GET['act']):'display';

$userInfo = array();
$tab = C::t('#'.$plign_name.'#ims_tg_'.$pmod);
$id = intval($_GET['sid']);
if ($operation == 'display') {
	if (!empty($_GET['displayorder'])) {
		foreach ($_GET['displayorder'] as $id => $displayorder) {
			$tab->update_(array('displayorder' => $displayorder), array('id' => $id));
		}
		cpmsg($L['g1'].'', W(), 'success');
	}
	$category = C::t('#'.$plign_name.'#ims_tg_category')->getList(" and enabled=1 " , " ORDER BY displayorder DESC " , 0 , 10000);
	$pagesize = 15;
	$page = intval($_GET['page'])>0? intval($_GET['page']):1;
	$start = ($page-1)*$pagesize;
	$condition = " ";
	$_GET['pay_type'] = addslashes($_GET['pay_type']);
	if (!empty($_GET['pay_type'])) {
		$condition .= " AND fk_typeid = '{$_GET['pay_type']}'";
	}
	$_GET['keyword'] = addslashes($_GET['keyword']);
	if (!empty($_GET['keyword'])) {
		$condition .= " AND gname = '{$_GET['keyword']}'";
	}
    $count = $tab->getCount($condition);
	$goodses = $tab->getList($condition," ORDER BY id DESC ",$start,$pagesize);
	$multi = multi($count, $pagesize, $page, createWebUrl($pmod , array('pay_type' => $_GET['pay_type'] , 'keyword' => $_GET['keyword'])));	
	if(!empty($goodses)){
		foreach ($goodses as $key => $value) {
			$goodses[$key]['category'] = C::t('#'.$plign_name.'#ims_tg_category')->getRow($value['fk_typeid']);
		}
	}
	include_once template($plign_name.':'.$pmod);
}elseif($operation == 'delete' && $_GET['formhash'] == FORMHASH){
	$tab->delete_($id , 'id');
	cpmsg($L['g2'].'', W(), 'succeed');
}elseif ($operation == 'post'){
	$id = intval($_GET['sid']);
	$category = C::t('#'.$plign_name.'#ims_tg_category')->getList(" and enabled=1 " , " ORDER BY displayorder DESC " , 0 , 10000);
	$goods = array();
	if(!empty($id)){
		$goods = $tab->getRow($id);
		$goods['gubtime'] = date('Y-m-d');

		$listt = C::t('#'.$plign_name.'#ims_tg_goods_atlas')->getList(' and g_id='.$id);
		$piclist = array();
		if(is_array($listt)){
			foreach($listt as $p){
				$piclist[$p['seq']] = $p['thumb'];
			}
		}
	
		$params = C::t('#'.$plign_name.'#ims_tg_goods_param')->getList(' and goodsid='.$id);
		if(empty($goods)){
			cpmsg($L['g3'].'.', W(array('act' => $operation , 'sid' => $id)) , 'error');
		}

		$orders = C::t('#'.$plign_name.'#ims_tg_order')->getList(' and g_id='.$id);
		
		$arr = array();
		foreach ($orders as $key => $value) {
			$arr['endtime'] = $value['endtime'];
		}
		$endtime = $arr['endtime'];
	}else{
		$goods['isshow'] = 1;
	}
	if(submitcheck('submit')){
		$data = $_GET['goods'];
		$data['gdesc'] = $_GET['guize'];
		empty($data['gname']) && cpmsg($L['g4'].'' , W(array('act' => 'post' ,'sid' => $id)) , 'error');
		empty($data['fk_typeid']) && cpmsg($L['g5'].'', W(array('act' => 'post' ,'sid' => $id)) , 'error');
		empty($data['gnum']) && cpmsg($L['g6'].'', W(array('act' => 'post' ,'sid' => $id)) , 'error');
		empty($data['gprice']) && cpmsg($L['g7'].'', W(array('act' => 'post' ,'sid' => $id)) , 'error');
		empty($data['oprice']) && cpmsg($L['g8'].'', W(array('act' => 'post' ,'sid' => $id)) , 'error');
		empty($data['gdesc']) && cpmsg($L['g9'].'', W(array('act' => 'post' ,'sid' => $id)) , 'error');
		$data['gdesc'] = addslashes($data['gdesc']);
		$data['gubtime'] = strtotime(addslashes($data['gubtime']));
		$data['endtime'] = $_GET['endtime'];
		$data['createtime'] = time();
		$thumb = array();
		$gimg = "";
		if ($_FILES['gimg']['size'] > 0){
			$gimg = upload_('gimg' , 'adv');
			if($gimg == -1){
				cpmsg($L['a3'].'', W(array('act' => 'post' , 'sid' => $id)), 'error');
			}
			$data['gimg'] = $gimg;
		}	
		
		if($id == 0){
			$tab->insert_($data);
			$id = DB::insert_id();
		}else{
			$tab->update_($data , array('id' => $id));
		}
		for ($i = 1 ; $i <= 5 ; $i++){
			$thumb = $_FILES['fileupload'.$i]['size'] > 0 ? upload_('fileupload'.$i , 'g/'.$id) : '';
			if($thumb != ''){
				if($id > 0){
					C::t('#'.$plign_name.'#ims_tg_goods_atlas')->delete_2(" and seq=$i and g_id=$id");
				}
				C::t('#'.$plign_name.'#ims_tg_goods_atlas')->insert_(array('seq'=>$i,'g_id'=>$id,'thumb'=>$thumb));
			}
		}
		cpmsg($L['g10']."" , W() , 'success');	
	}
	include_once template($plign_name.':'.$pmod);
}

?>