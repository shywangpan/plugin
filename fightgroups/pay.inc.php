<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once DISCUZ_ROOT.'./source/plugin/fightgroups/config.inc.php';
$operation = isset($_GET['act'])? addslashes($_GET['act']):'display';


$appid    = trim($plugininfo['APPID']);
$mchid    = trim($plugininfo['MCHID']);
$key      = trim($plugininfo['key']);
$appsecret = trim($plugininfo['AppSecret']);

define("C6iTAPPID", $appid);
define("C6iTMCHID", $mchid);
define("C6iTKEY", $key);
define("C6iTAPPSECRET", $appsecret);
if($operation == "pay" && $_GET['formhash'] == FORMHASH){
	$openid = $_W['openid'];
	$groupnum =intval($_GET['groupnum']);
	$id = intval($_GET['gid']);
	$tuan_id = intval($_GET['tuan_id']);
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
	$data = array(
		'uniacid' => $_W['uniacid'],
		'gnum' => 1,
		'openid' => $openid,
		'ptime' =>'',
		'orderno' => date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99)),
		'price' => $price+$goods['freight'],
		'status' => 0,
		'addressid' => $adress['id'],
		'addname' => $adress['cname'],
		'mobile' => $adress['tel'],
		'address' => $adress['province'].$adress['city'].$adress['county'].$adress['detailed_address'],
		'g_id' => $id,
		'tuan_id'=>$tuan_id,
		'is_tuan'=>$is_tuan,
		'tuan_first' => $tuan_first,
		'starttime'=>TIMESTAMP,
		'endtime'=>$goods['endtime'],
		'success'=>$success,
		'createtime' => TIMESTAMP
	);
	DB::insert('ims_tg_order', $data);
	$orderid = DB::insert_id();
	if(empty($tuan_id)){
		DB::update('ims_tg_order',array('tuan_id' => $orderid), array('id' => $orderid));
	}
	include DISCUZ_ROOT.'./source/plugin/fightgroups/class/wxpay/lib/WxPay.Api.php';
	$notifyUrl = HEJIN_PATH.'/notify.php';
	
	$orderInput = new WxPayUnifiedOrder();
	$orderInput->SetBody($goods['gname']);
	$orderInput->SetAttach("tuan");
	$orderInput->SetOut_trade_no($data['orderno']);
	$orderInput->SetTotal_fee($data['price'] *100);

	$orderInput->SetGoods_tag("null");
	$orderInput->SetNotify_url($notifyUrl);
	$orderInput->SetTrade_type("JSAPI");
	$orderInput->SetOpenid($_SESSION['wx']['openid']);
	$orderInfo = WxPayApi::unifiedOrder($orderInput);

	if(is_array($orderInfo) && $orderInfo['result_code']=='SUCCESS' && $orderInfo['return_code']=='SUCCESS'){
		$jsapi = new WxPayJsApiPay();
		$jsapi->SetAppid($orderInfo["appid"]);
		$timeStamp = time();
		$timeStamp = "$timeStamp";
		$jsapi->SetTimeStamp($timeStamp);
		$jsapi->SetNonceStr(WxPayApi::getNonceStr());
		$jsapi->SetPackage("prepay_id=" . $orderInfo['prepay_id']);
		$jsapi->SetSignType("MD5");
		$jsapi->SetPaySign($jsapi->MakeSign());
		$parameters = $jsapi->GetValues();

		$outArr = array(
			'status'=> 200,
			'tstatus'=> 0,
			'tuan_id'=> $tuan_id,
			'parameters' => $parameters,
		);
		echo json_encode($outArr); exit;
	}else{
		$outArr = array(
            'status'=> 302,
        );
        echo json_encode($outArr); exit;
	}
}


?>