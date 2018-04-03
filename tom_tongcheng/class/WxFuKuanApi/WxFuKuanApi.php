<?php

class WxFuKuanApi {

    private $app_id = '';
    private $app_mchid = '';
	private $create_ip = '';

    function __construct(){

		$this->app_mchid	= TOM_WXPAY_MCHID;
		$this->app_id		= TOM_WXPAY_APPID;
		$this->create_ip	= TOM_WXPAY_IP;

	}

    /**
     * 付款
     */
    public function send($openid,$price,$order_no,$desc)
    {
        
		include_once('WxFuKuanHelper.php');
        $commonUtil = new CommonUtil();
        $wxFuKuanHelper = new WxFuKuanHelper();

		$wxFuKuanHelper->setParameter("mch_appid", $this->app_id);
		$wxFuKuanHelper->setParameter("mchid", $this->app_mchid);			//商户号
		$wxFuKuanHelper->setParameter("nonce_str", $this->great_rand());	//随机字符串，丌长于 32 位
		$wxFuKuanHelper->setParameter("partner_trade_no", $order_no);		//订单号
        $wxFuKuanHelper->setParameter("openid", $openid);					// openid
		$wxFuKuanHelper->setParameter("check_name", 'NO_CHECK');
		$wxFuKuanHelper->setParameter("amount", $price);					//付款金额，单位分
		$wxFuKuanHelper->setParameter("desc", $desc);						// 说明信息
        $wxFuKuanHelper->setParameter("spbill_create_ip", $this->create_ip);	//调用接口的机器 Ip 地址
        $postXml = $wxFuKuanHelper->create_xml();
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $responseXml = $wxFuKuanHelper->curl_post_ssl($url, $postXml);
		$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);

		if($responseObj->return_code == 'SUCCESS' && $responseObj->result_code == 'SUCCESS'){
			return array('status'=>1,'response_xml'=>$responseXml);
		}else{
			return array('status'=>2,'response_xml'=>$responseXml);
		}
		
    }
    

    /**
     * 生成随机数
     */     
    public function great_rand(){
        $str = '1234567890abcdefghijklmnopqrstuvwxyz';
		$t1 = "";
        for($i=0;$i<30;$i++){
            $j=rand(0,35);
            $t1 .= $str[$j];
        }
        return $t1;    
    }
}
?>