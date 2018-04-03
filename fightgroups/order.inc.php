<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/fightgroups/config.inc.php';
if (!$_G['adminid']) {
	return false;
}
$pmod = 'order';
$operation = !empty($_GET['op']) ? addslashes($_GET['op']) : 'display';
if ($operation == 'display') {
	$status = addslashes($_GET['status']);
	$is_tuan = addslashes($_GET['is_tuan']);
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}
	$param_array = array();
	$param_array['op'] = 'display';
	$_GET['start'] = addslashes($_GET['start']);
	if($_GET['start'] != ''){
		$starttime = strtotime($_GET['start']);
		$param_array['start'] = $_GET['start'];
		$condition .= " AND o.createtime >= $starttime  ";
	}
	$_GET['end'] = addslashes($_GET['end']);
	if($_GET['end'] != ''){
		$endtime = strtotime($_GET['end']) + 86399;
		$param_array['end'] = $_GET['end'];
		$condition .= "  AND o.createtime <= $endtime ";
	}
	$_GET['pay_type'] = addslashes($_GET['pay_type']);
	if (!empty($_GET['pay_type'])) {
		$condition .= " AND o.pay_type = '{$_GET['pay_type']}'";
		$param_array['pay_type'] = $_GET['pay_type'];
	} elseif ($_GET['pay_type'] === '0') {
		$condition .= " AND o.pay_type = '{$_GET['pay_type']}'";
		$param_array['pay_type'] = $_GET['pay_type'];
	}
	$_GET['keyword'] = addslashes($_GET['keyword']);
	if (!empty($_GET['keyword'])) {
		$condition .= " AND o.orderno LIKE '%{$_GET['keyword']}%'";
		$param_array['keyword'] = $_GET['keyword'];
	}
	$_GET['member'] = addslashes($_GET['member']);
	if (!empty($_GET['member'])) {
		$condition .= " AND (a.cname LIKE '%{$_GET['member']}%' or a.tel LIKE '%{$_GET['member']}%')";
		$param_array['member'] = $_GET['member'];
	}

	if ($status != '') {
		$condition .= " AND o.status = '" . intval($status) . "'";
		$param_array['status'] = $_GET['status'];
	}

	if ($is_tuan != '') {
		$pp = 1;
		$condition .= " AND o.is_tuan = 1";
		$param_array['is_tuan'] = $_GET['is_tuan'];
	}
	$sql = "select o.* , a.cname,a.tel from ".DB::table('ims_tg_order')." o"
	." left join ".DB::table('ims_tg_address')." a on o.addressid = a.id "
	. "  where 1 $condition ORDER BY o.status DESC, o.createtime DESC ";
	$pagesize = 15;
	$page = intval($_GET['page'])>0? intval($_GET['page']):1;
	$start = ($page-1)*$pagesize;
	$count = count(DB::fetch_all($sql));
	
	$list = DB::fetch_all($sql." limit $start , $pagesize");
	$paytype = array (
		'0' => array('css' => 'default', 'name' => $L['o1'].''),
		'1' => array('css' => 'info', 'name' => $L['o2'].''),
		'3' => array('css' => 'warning', 'name' => $L['o3'].'')
	);
	$orderstatus = array (
		'9' => array('css' => 'default', 'name' => $L['o4'].''),
		'0' => array('css' => 'danger', 'name' => $L['o5'].''),
		'1' => array('css' => 'info', 'name' => $L['o6'].''),
		'2' => array('css' => 'warning', 'name' => $L['o7'].''),
		'3' => array('css' => 'success', 'name' => $L['o8'].'')
	);
	
	foreach ($list as &$value) {
		$s = $value['status'];
		$value['statuscss'] = $orderstatus[$value['status']]['css'];
		$value['status'] = $orderstatus[$value['status']]['name'];
		if ($s < 1) {
			$value['css'] = $paytype[$s]['css'];
			$value['paytype'] = $paytype[$s]['name'];
			continue;
		}
		$value['css'] = $paytype[$value['paytype']]['css'];
		if ($value['paytype'] == 2) {
			if (empty($value['transid'])) {
				$value['paytype'] = $L['o9'].'';
			} else {
				$value['paytype'] = $L['o10'].'';
			}
		} else {
			$value['paytype'] = $paytype[$value['paytype']]['name'];
		}
	}
	
	$multi = multi($count, $pagesize, $page, createWebUrl($pmod , $param_array));	
	include_once template($plign_name.':'.$pmod);
} elseif ($operation == 'detail') {
	
	$id = intval($_GET['id']);
	$is_tuan = intval($_GET['is_tuan']);
	$item = DB::fetch_first("SELECT * FROM " . DB::table('ims_tg_order') . "  where id = $id");
	if (empty($item)) {
		cpmsg($L['o11']."",  W(array('op' => 'detail' , 'id' => $id , 'is_tuan' => $is_tuan)), "error");
	}
	
	if (submitcheck('confirmsend')) {
		if (!empty($_GET['isexpress']) && empty($_GET['expresssn'])) {
			cpmsg($L['o12'].'', W(array('op' => 'detail' , 'id' => $id , 'is_tuan' => $is_tuan)), 'error');
		}

		DB::update(
			'ims_tg_order',
			array(
				'status' => 2,
				'express' => $_GET['express'],
				'expresssn' => $_GET['expresssn'],
			),
			array('id' => $id)
		);
		cpmsg($L['o13'].'', W(array('op' => 'detail' , 'id' => $id , 'is_tuan' => $is_tuan)), 'success');
	}

	if (submitcheck('cancelsend')) {
		
		DB::update(
			'ims_tg_order',
			array('status' => 1),
			array('id' => $id)
		);
		cpmsg($L['o14'].'', W(array('op' => 'detail' , 'id' => $id , 'is_tuan' => $is_tuan)), 'success');
	}
	if (submitcheck('finish')) {
		DB::update('ims_tg_order', array('status' => 3), array('id' => $id));
		cpmsg($L['o15'].'', W(array('op' => 'detail' , 'id' => $id , 'is_tuan' => $is_tuan)), 'success');
	}
	if (submitcheck('refund')) {

		cpmsg($L['o16'].'', W(array('op' => 'detail' , 'id' => $id , 'is_tuan' => $is_tuan)), 'success');
	}

	if (submitcheck('cancel')) {
		DB::update('ims_tg_order', array('status' => 1), array('id' => $id));
		cpmsg($L['o17'].'', W(array('op' => 'detail' , 'id' => $id , 'is_tuan' => $is_tuan)), 'success');
	}
	if (submitcheck('cancelpay')) {
		DB::update('ims_tg_order', array('status' => 0), array('id' => $id));
		cpmsg($L['o18'].'', W(array('op' => 'detail' , 'id' => $id , 'is_tuan' => $is_tuan)), 'success');
	}

	if (submitcheck('confrimpay')) {
		DB::update('ims_tg_order', array('status' => 1, 'pay_type' => 2), array('id' => $id));
		cpmsg($L['o19'].'', W(array('op' => 'detail' , 'id' => $id , 'is_tuan' => $is_tuan)), 'success');
	
	}
	
	if (submitcheck('close1')) {
		$item = DB::fetch_first("SELECT transid FROM " . DB::table('ims_tg_order') . "  where id = $id");
		DB::update('ims_tg_order', array('status' => -1, 'remark' => $_GET['reson']), array('id' => $id));
		cpmsg($L['o20'].'', W(array('op' => 'detail' , 'id' => $id , 'is_tuan' => $is_tuan)), 'success');
	}

	if (submitcheck('open')) {
		DB::update('ims_tg_order', array('status' => 0, 'remark' => $_GET['remark']), array('id' => $id));
		cpmsg($L['o21'].'', W(array('op' => 'detail' , 'id' => $id , 'is_tuan' => $is_tuan)), 'success');
	}
	$item['user'] = DB::fetch_first("SELECT * FROM " . DB::table('ims_tg_order') . "  where id = {$item['addressid']}");
	$goods = DB::fetch_all("select * from " . DB::table('ims_tg_order') ."  where id={$item['g_id']}");
	$item['goods'] = $goods;
} elseif ($operation == 'delete' && $_GET['formhash'] == FORMHASH) {
	$orderid = intval($_GET['id']);
	$tuan_id = intval($_GET['tuan_id']);
	if(!empty($tuan_id)){
		if(DB::delete('ims_tg_order', array('tuan_id' => $tuan_id))){
			cpmsg($L['o22'].'', W(array('op' => 'tuan')), 'success');
		}
	}
	if (DB::delete('ims_tg_order', array('id' => $orderid))) {
		cpmsg($L['o23'].'', W( array('op' => 'display')), 'success');
	} else {
		cpmsg($L['o24'].'', W( array('op' => 'display')), 'error');
	}
} elseif ($operation == 'tuan') {
	$pagesize = 15;
	$page = intval($_GET['page'])>0? intval($_GET['page']):1;
	$start = ($page-1)*$pagesize;
	
	
	$param_array = array();
	$param_array['op'] = 'tuan';
	$is_tuan = $_GET['is_tuan'];
	if (!empty($_GET['keyword'])) {
		$condition .= " AND tuan_id LIKE '%{$_GET['keyword']}%'";
		$param_array['keyword'] = $_GET['keyword'];
	}
	if ($is_tuan != '') {
		$condition .= " AND is_tuan = 1";
		$param_array['is_tuan'] = $_GET['is_tuan'];
	}
	$sql = "select DISTINCT tuan_id from ".DB::table('ims_tg_order').
	"  where 1 $condition order by id desc ";
	$count = count(DB::fetch_all($sql));
	$tuan_id = DB::fetch_all($sql." limit $start , $pagesize");
	
	
	foreach ($tuan_id as $key => $tuan) {
		$alltuan = DB::fetch_all("select * from ".DB::table('ims_tg_order')."  where tuan_id={$tuan['tuan_id']}");
		$ite = array();
		foreach ($alltuan as $num => $all) {
			$ite[$num] = $all['id'];
			$goods = DB::fetch_all("select * from ".DB::table('ims_tg_goods')." where id = {$all['g_id']}");
		}

		$tuan_id[$key]['itemnum'] = count($ite);
		$tuan_id[$key]['groupnum'] = $goods['groupnum'];

		$tuan_first_order = DB::fetch_first("SELECT * FROM ".DB::table('ims_tg_order')."   where tuan_id={$tuan['tuan_id']} and tuan_first = 1");
		$hours=$tuan_first_order['endtime'];
		$time = time();
		$date = date('Y-m-d H:i:s',$tuan_first_order['createtime']);
		$endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
		$date1 = date('Y-m-d H:i:s',$time); 
		$lasttime = strtotime($endtime)-strtotime($date1);
		$tuan_id[$key]['lasttime'] = $lasttime;
	}
	
	$multi = multi($count, $pagesize, $page, createWebUrl($pmod , $param_array));	
	include_once template($plign_name.':'.$pmod);
} elseif ($operation == 'tuan_detail'){
	
	$num = intval($_GET['num']);
	$tuan_id = intval($_GET['tuan_id']);
	$is_tuan = intval($_GET['is_tuan']);
	$H = W(array('op' => 'tuan_detail' , 'tuan_id' => $tuan_id,'is_tuan' => $is_tuan));
	$orders = DB::fetch_all("SELECT * FROM " . DB::table('ims_tg_order') . "  where tuan_id = {$tuan_id}");
	foreach ($orders as $key => $order) {
		$address = DB::fetch_first("SELECT * FROM ".DB::table('ims_tg_address')."  where id={$order['addressid']}");
		$orders[$key]['cname'] = $address['cname'];
		$orders[$key]['tel'] = $address['tel'];
		$orders[$key]['province'] = $address['province'];
		$orders[$key]['city'] = $address['city'];
		$orders[$key]['county'] = $address['county'];
		$orders[$key]['detailed_address'] = $address['detailed_address'];
		$goods = DB::fetch_first("select * from ".DB::table('ims_tg_goods')."  where id={$order['g_id']}");

	}
	$goodsid  = array();
	foreach ($orders as $key => $value) {
		$goodsid['id'] = $value['g_id'];
	}
	$goods2 = DB::fetch_first("SELECT * FROM " .DB::table('ims_tg_goods') . "  where id = {$goodsid['id']}");
	if (empty($orders)) {
		cpmsg($L['o25']."", $H, "error");
	}
	foreach ($orders as $key => $value) {
		$it['status'] = $value['status'];
	}

	$sql2= "SELECT * FROM ".DB::table('ims_tg_order')."  where tuan_id=$tuan_id and tuan_first = 1";
	$tuan_first_order = DB::fetch_first($sql2);
	$hours=$tuan_first_order['endtime'];
	$time = time();
	$date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); 
	$endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));

	$date1 = date('Y-m-d H:i:s',$time); 
	$lasttime2 = strtotime($endtime)-strtotime($date1);
	
}
include_once template($plign_name.':'.$pmod);
?>
