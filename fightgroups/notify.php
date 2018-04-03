<?php
define('APPTYPEID', 127);
define('CURSCRIPT', 'plugin');
define('DISABLEXSSCHECK', true); 

$_GET['id'] = 'c6ittuan';

require substr(dirname(__FILE__), 0, -26).'/source/class/class_core.php';

$discuz = C::app();
$cachelist = array('plugin', 'diytemplatename');

$discuz->cachelist = $cachelist;
$discuz->init();

define('CURMODULE', 'c6ittuan');

$_G['siteurl'] = substr($_G['siteurl'], 0, -26);
$_G['siteroot'] = substr( $_G ['siteroot'], 0, - 26);


require_once DISCUZ_ROOT.'./source/plugin/fightgroups/config.inc.php';

$wxpay_appid        = trim($pintuanConfig['wxpay_appid']);
$wxpay_mchid        = trim($pintuanConfig['wxpay_mchid']);
$wxpay_key          = trim($pintuanConfig['wxpay_key']);
$wxpay_appsecret    = trim($pintuanConfig['wxpay_appsecret']);

$appid    = trim($plugininfo['APPID']);
$mchid    = trim($plugininfo['MCHID']);
$key      = trim($plugininfo['key']);
$appsecret = trim($plugininfo['AppSecret']);

define("C6iTAPPID", $appid);
define("C6iTMCHID", $mchid);
define("C6iTKEY", $key);
define("C6iTAPPSECRET", $appsecret);

include DISCUZ_ROOT.'./source/plugin/fightgroups/class/wxpay/lib/WxPay.Api.php';
include DISCUZ_ROOT.'./source/plugin/fightgroups/class/wxpay/lib/WxPay.Notify.php';
include DISCUZ_ROOT.'./source/plugin/fightgroups/class/wxpay/log.php';

$logDir = DISCUZ_ROOT."./source/fightgroups/tom_pintuan/logs/";
if(!is_dir($logDir)){
    mkdir($logDir, 0777,true);
}else{
    chmod($logDir, 0777); 
}
$logHandler= new CLogFileHandler(DISCUZ_ROOT."./source/plugin/fightgroups/logs/".date("Y-m-d").".log");
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify{
    
	public function Queryorder($transaction_id){
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
        Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS"){
			return true;
		}
		return false;
	}
	
	public function NotifyProcess($data, &$msg){
        
        Log::DEBUG("call back:" . json_encode($data));
        
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
            Log::DEBUG("error:can shu cuo wu");
            $msg = "can shu cuo wu";
			return false;
		}
		if(!$this->Queryorder($data["transaction_id"])){
            Log::DEBUG("error:ding dan cha xu shi bai");
            $msg = "ding dan cha xu shi bai";
			return false;
		}
        
        if(isset($data['result_code']) && $data['result_code']=='SUCCESS'){
        }else{
            Log::DEBUG("error:result_code error");
            $msg = "result_code error";
            return false;
        }
        
        if(isset($data['out_trade_no']) && !empty($data['out_trade_no'])){
        }else{
            Log::DEBUG("error:out_trade_no error");
            $msg = "out_trade_no error";
            return false;
        }
        
        $orderInfo = DB::fetch_first("SELECT * FROM ".DB::table('ims_tg_order')." WHERE orderno='{$data['out_trade_no']}'");
        if($orderInfo && $orderInfo['status'] == 0){
            $updateData = array();
            $updateData['status'] = 1;
            $updateData['pay_type'] = 1;
            DB::update('ims_tg_order' , $updateData , array('orderno' => $data['out_trade_no']));
            
            Log::DEBUG("update order:" . json_encode($orderInfo));
             
            
        }
        
		return true;
	}
    
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
?>
