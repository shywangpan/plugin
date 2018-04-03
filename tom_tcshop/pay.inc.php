<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$site_id = intval($_GET['site'])>0? intval($_GET['site']):1;

$tcshopConfig = $_G['cache']['plugin']['tom_tcshop'];
$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];
$tchehuorenConfig = array();
if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_tchehuoren/tom_tchehuoren.inc.php')){
    $tchehuorenConfig = $_G['cache']['plugin']['tom_tchehuoren'];
}

$wxpay_appid        = trim($tongchengConfig['wxpay_appid']);
$wxpay_mchid        = trim($tongchengConfig['wxpay_mchid']);
$wxpay_key          = trim($tongchengConfig['wxpay_key']);
$wxpay_appsecret    = trim($tongchengConfig['wxpay_appsecret']);

define("TOM_WXPAY_APPID", $wxpay_appid);
define("TOM_WXPAY_MCHID", $wxpay_mchid);
define("TOM_WXPAY_KEY", $wxpay_key);
define("TOM_WXPAY_APPSECRET", $wxpay_appsecret);

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/wxpay/lib/WxPay.Api.php';
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/function.core.php';

$act = isset($_GET['act'])? addslashes($_GET['act']):"ruzhu";

if($act == "ruzhu" && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 1,
    );
    
    if('utf-8' != CHARSET) {
        if(defined('IN_MOBILE')){
        }else{
            foreach($_POST AS $pk => $pv) {
                if(!is_numeric($pv)) {
                    $_GET[$pk] = $_POST[$pk] = wx_iconv_recurrence($pv);	
                }
            }
        }
    }
    
    $invite_code        = isset($_GET['invite_code'])? addslashes($_GET['invite_code']):'';
    $invite_code        = trim($invite_code);
    $invite_code        = str_replace(" ", "", $invite_code);
    $ruzhu_level        = isset($_GET['ruzhu_level'])? intval($_GET['ruzhu_level']):0;
    $user_id            = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    $name               = isset($_GET['name'])? addslashes($_GET['name']):'';
    $cate_id            = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
    $cate_child_id      = isset($_GET['cate_child_id'])? intval($_GET['cate_child_id']):0;
    $city_id            = isset($_GET['city_id'])? intval($_GET['city_id']):0;
    $area_id            = isset($_GET['area_id'])? intval($_GET['area_id']):0;
    $street_id          = isset($_GET['street_id'])? intval($_GET['street_id']):0;
    $business_hours     = isset($_GET['business_hours'])? addslashes($_GET['business_hours']):'';
    $tel                = isset($_GET['tel'])? addslashes($_GET['tel']):'';
    $shopkeeper_tel     = isset($_GET['shopkeeper_tel'])? addslashes($_GET['shopkeeper_tel']):'';
    $picurl             = isset($_GET['picurl'])? addslashes($_GET['picurl']):'';
    $kefu_qrcode        = isset($_GET['kefu_qrcode'])? addslashes($_GET['kefu_qrcode']):'';
    $business_licence   = isset($_GET['business_licence'])? addslashes($_GET['business_licence']):'';
    $content            = isset($_GET['content'])? addslashes($_GET['content']):'';
    
    $lng               = isset($_GET['lng'])? addslashes($_GET['lng']):'';
    $lat               = isset($_GET['lat'])? addslashes($_GET['lat']):'';
    $address           = isset($_GET['address'])? addslashes($_GET['address']):'';
    
    $photoArr = $photoThumbArr = array();
    foreach($_GET as $key => $value){
        if(strpos($key, "photo_") !== false){
            $kk = intval(ltrim($key, "photo_"));
            $photoArr[$kk] = addslashes($value);
        }
        if(strpos($key, "photothumb_") !== false){
            $kk = intval(ltrim($key, "photothumb_"));
            $photoThumbArr[$kk] = addslashes($value);
        }
    }

    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($user_id);
    
    if(empty($userInfo)){
        $outArr = array(
            'status'=> 500,
        );
        echo json_encode($outArr); exit;
    }
    
    if($userInfo['status'] != 1){
        $outArr = array(
            'status'=> 301,
        );
        echo json_encode($outArr); exit;
    }
    
    $tjTchehuorenId = 0;
    if($tchehuorenConfig && $tchehuorenConfig['tcshop_type'] == 1){
        if(!empty($invite_code)){
            $tchehuorenInfoTmp = C::t('#tom_tchehuoren#tom_tchehuoren')->fetch_all_list(" AND invite_code = '{$invite_code}' AND status=1 ", 'ORDER BY id DESC', 0, 1);
            if(is_array($tchehuorenInfoTmp) && !empty($tchehuorenInfoTmp[0])){
                $tjTchehuorenId = $tchehuorenInfoTmp[0]['id'];
            }
        }
    }else if($tchehuorenConfig && $tchehuorenConfig['tcshop_type'] == 2){
        if($userInfo['tj_hehuoren_id'] > 0){
            $tchehuorenInfoTmp = C::t('#tom_tchehuoren#tom_tchehuoren')->fetch_by_id($userInfo['tj_hehuoren_id']);
            if($tchehuorenInfoTmp && $tchehuorenInfoTmp['status'] == 1){
                $tjTchehuorenId = $userInfo['tj_hehuoren_id'];
            }
        }
    }
    
    $pay_price = 0;
    if($ruzhu_level == 2){
        $pay_price = $tcshopConfig['vip_ruzhu_price'];
    }else if($ruzhu_level == 3){
        $pay_price = $tcshopConfig['vip_ruzhu_price_two'];
    }else{
        $pay_price = $tcshopConfig['ruzhu_price'];
    }
    if($pay_price > 0 && $tjTchehuorenId > 0 && $tchehuorenConfig && $tchehuorenConfig['tcshop_type'] == 1){
        $tcshop_ruzhu_discount_arr = array(2=>0.95,3=>0.9,4=>0.85,5=>0.8,6=>0.75,7=>0.7);
        if(isset($tcshop_ruzhu_discount_arr[$tchehuorenConfig['tcshop_ruzhu_discount']])){
            $tcshop_ruzhu_discount      = $tcshop_ruzhu_discount_arr[$tchehuorenConfig['tcshop_ruzhu_discount']];
            $pay_price         = number_format($pay_price*$tcshop_ruzhu_discount,2);
        }
    }
    
    $insertData = array();
    $insertData['site_id']          = $site_id;
    $insertData['user_id']          = $user_id;
    if($ruzhu_level > 1){
        $insertData['vip_level']    = 1;
    }else if($tcshopConfig['base_time_type'] > 1 && $tcshopConfig['base_time_type'] < 7){
        $insertData['base_level']    = 2;
        if($pay_price > 0){
        }else{
            if($tcshopConfig['base_time_type'] == 2){
                $insertData['base_time'] = TIMESTAMP + 7*86400;
            }else if($tcshopConfig['base_time_type'] == 3){
                $insertData['base_time'] = TIMESTAMP + 30*86400;
            }else if($tcshopConfig['base_time_type'] == 4){
                $insertData['base_time'] = TIMESTAMP + 90*86400;
            }else if($tcshopConfig['base_time_type'] == 5){
                $insertData['base_time'] = TIMESTAMP + 180*86400;
            }else if($tcshopConfig['base_time_type'] == 6){
                $insertData['base_time'] = TIMESTAMP + 365*86400;
            }
        }
    }
    $insertData['tj_hehuoren_id']   = $tjTchehuorenId;
    $insertData['name']             = $name;
    $insertData['cate_id']          = $cate_id;
    $insertData['cate_child_id']    = $cate_child_id;
    $insertData['city_id']          = $city_id;
    $insertData['area_id']          = $area_id;
    $insertData['street_id']        = $street_id;
    $insertData['business_hours']   = $business_hours;
    $insertData['tel']              = $tel;
    $insertData['shopkeeper_tel']   = $shopkeeper_tel;
    $insertData['picurl']           = $picurl;
    $insertData['kefu_qrcode']      = $kefu_qrcode;
    $insertData['business_licence'] = $business_licence;
    $insertData['content']          = $content;
    $insertData['latitude']         = $lat;
    $insertData['longitude']        = $lng;
    $insertData['address']          = $address;
    $insertData['ruzhu_time']       = TIMESTAMP;
    if($tcshopConfig['open_simplify_ruzhu'] == 1){
        $insertData['is_ok']            = 0;
        $insertData['status']           = 2;
    }else{
        $insertData['is_ok']            = 1;
        if($pay_price > 0){
            $insertData['status']       = 2;
        }else{
            $insertData['status']       = 1;
        }
    }
    if($pay_price > 0){
        $insertData['pay_status']   = 1;
    }else{
        $insertData['pay_status']   = 0;
    }
    if($tcshopConfig['must_shenhe'] == 1 ){
        $insertData['shenhe_status']       = 2;
    }else{
        $insertData['shenhe_status']       = 1;
    }
    $insertData['ruzhu_level']             = $ruzhu_level;
    if(C::t('#tom_tcshop#tom_tcshop')->insert($insertData)){
        
        $tcshopId = C::t('#tom_tcshop#tom_tcshop')->insert_id();
        
        if(is_array($photoArr) && !empty($photoArr)){
            foreach ($photoArr as $key => $value){
                $insertData = array();
                $insertData['tcshop_id'] = $tcshopId;
                $insertData['picurl']    = $value;
                $insertData['thumb']     = $photoThumbArr[$key];
                $insertData['add_time']  = TIMESTAMP;
                C::t('#tom_tcshop#tom_tcshop_photo')->insert($insertData);
            }
        }
        
        ## pay start
        if($pay_price > 0){
            
            if(!file_exists(DISCUZ_ROOT.'./source/plugin/tom_pay/tom_pay.inc.php')){
                $outArr = array(
                    'status'=> 302,
                );
                echo json_encode($outArr); exit;
            }
            
            $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);
            
            $insertData = array();
            $insertData['site_id']          = $site_id;
            $insertData['order_no']         = $order_no;
            $insertData['order_type']       = 4;
            $insertData['user_id']          = $user_id;
            $insertData['openid']           = $userInfo['openid'];
            $insertData['tcshop_id']        = $tcshopId;
            $insertData['pay_price']        = $pay_price;
            $insertData['order_status']     = 1;
            $insertData['order_time']       = TIMESTAMP;
            if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){

                $insertData = array();
                $insertData['plugin_id']       = 'tom_tcshop';      
                $insertData['order_no']        = $order_no;            
                $insertData['goods_id']        = $tcshopId;           
                $insertData['goods_name']      = lang('plugin/tom_tongcheng','order_type_4');
                $insertData['goods_beizu']     = lang('plugin/tom_tongcheng','order_type_4');
                $insertData['goods_url']       = "plugin.php?id=tom_tcshop&site={$site_id}&mod=details&tcshop_id={$tcshopId}";
                $insertData['succ_back_url']   = "plugin.php?id=tom_tcshop&site={$site_id}&mod=edit&tcshop_id={$tcshopId}&fromlist=mylist";
                $insertData['fail_back_url']   = "plugin.php?id=tom_tcshop&site={$site_id}&mod=ruzhu";
                $insertData['allow_alipay']    = 1;         
                $insertData['pay_price']       = $pay_price;    
                $insertData['order_status']    = 1;             
                $insertData['add_time']        = TIMESTAMP;     
                if(C::t('#tom_pay#tom_pay_order')->insert($insertData)){
                    $outArr = array(
                        'pay_status' => 1,
                        'status'    => 200,
                        'payurl' => "plugin.php?id=tom_pay&order_no=".$order_no,
                    );
                    echo json_encode($outArr); exit;
                    
                }else{
                    $outArr = array(
                        'status'=> 303,
                    );
                    echo json_encode($outArr); exit;
                }

            }else{
                $outArr = array(
                    'status'=> 304,
                );
                echo json_encode($outArr); exit;
            }

        }
        ## pay end
        $outArr = array(
            'pay_status' => 0,
            'status'=> 200,
            'tcshop_id'=> $tcshopId,
        );
        echo json_encode($outArr); exit;
        
    }else{
        $outArr = array(
            'status'=> 404,
        );
        echo json_encode($outArr); exit;
    }
    
    
}else if($act == "pay" && $_GET['formhash'] == FORMHASH){
    
    $tcshop_id   = isset($_GET['tcshop_id'])? intval($_GET['tcshop_id']):0;
    
    $tcshopInfo = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($tcshop_id);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tcshopInfo['user_id']); 
    
    $pay_price = 0;
    if($tcshopInfo['vip_level'] == 1){
        if($tcshopInfo['ruzhu_level'] == 3){
            $pay_price = $tcshopConfig['vip_ruzhu_price_two'];
        }else{
            $pay_price = $tcshopConfig['vip_ruzhu_price'];
        }
    }else{
        $pay_price = $tcshopConfig['ruzhu_price'];
    }
    
    $orderListTmp = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_list(" AND tcshop_id={$tcshop_id} AND user_id={$userInfo['id']} AND order_type=4 AND order_status=1 ","ORDER BY id DESC",0,10);
    if(is_array($orderListTmp) && !empty($orderListTmp)){
        foreach ($orderListTmp as $key => $value){
            $updateData = array();
            $updateData['order_status'] = 3;
            C::t('#tom_tongcheng#tom_tongcheng_order')->update($value['id'],$updateData);
        }
    }
    
    if($pay_price > 0){
        
        if(!file_exists(DISCUZ_ROOT.'./source/plugin/tom_pay/tom_pay.inc.php')){
            $outArr = array(
                'status'=> 302,
            );
            echo json_encode($outArr); exit;
        }
        
        $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);
        
        $insertData = array();
        $insertData['site_id']          = $tcshopInfo['site_id'];
        $insertData['order_no']         = $order_no;
        $insertData['order_type']       = 4;
        $insertData['user_id']          = $userInfo['id'];
        $insertData['openid']           = $userInfo['openid'];
        $insertData['tcshop_id']        = $tcshop_id;
        $insertData['pay_price']        = $pay_price;
        $insertData['order_status']     = 1;
        $insertData['order_time']       = TIMESTAMP;
        if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){

            $insertData = array();
            $insertData['plugin_id']       = 'tom_tcshop';      
            $insertData['order_no']        = $order_no;            
            $insertData['goods_id']        = $tcshop_id;           
            $insertData['goods_name']      = lang('plugin/tom_tongcheng','order_type_4');
            $insertData['goods_beizu']     = lang('plugin/tom_tongcheng','order_type_4');
            $insertData['goods_url']       = "plugin.php?id=tom_tcshop&site={$site_id}&mod=details&tcshop_id={$tcshop_id}";
            $insertData['succ_back_url']   = "plugin.php?id=tom_tcshop&site={$site_id}&mod=edit&tcshop_id={$tcshop_id}&fromlist=mylist";
            $insertData['fail_back_url']   = "plugin.php?id=tom_tcshop&site={$site_id}&mod=edit&tcshop_id={$tcshop_id}&fromlist=mylist";
            $insertData['allow_alipay']    = 1;         
            $insertData['pay_price']       = $pay_price;    
            $insertData['order_status']    = 1;             
            $insertData['add_time']        = TIMESTAMP;     
            if(C::t('#tom_pay#tom_pay_order')->insert($insertData)){
                $outArr = array(
                    'pay_status' => 1,
                    'status'    => 200,
                    'payurl' => "plugin.php?id=tom_pay&order_no=".$order_no,
                );
                echo json_encode($outArr); exit;
                
            }else{
                $outArr = array(
                    'status'=> 303,
                );
                echo json_encode($outArr); exit;
            }
            
        }else{
            $outArr = array(
                'status'=> 304,
            );
            echo json_encode($outArr); exit;
        }
    
    }else{
        $outArr = array(
            'status'=> 400,
        );
        echo json_encode($outArr); exit;
    }
    
}else if($act == "vip" && $_GET['formhash'] == FORMHASH){
    
    $tcshop_id   = isset($_GET['tcshop_id'])? intval($_GET['tcshop_id']):0;
    
    $tcshopInfo = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($tcshop_id);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tcshopInfo['user_id']);
    
    if($tcshopInfo['vip_level'] == 1){
        $outArr = array(
            'status'=> 500,
        );
        echo json_encode($outArr); exit;
    }
    
    $pay_price = $tcshopConfig['vip_upgrade_price'];
    
    $orderListTmp = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_list(" AND tcshop_id={$tcshop_id} AND user_id={$userInfo['id']} AND order_type=6 AND order_status=1 ","ORDER BY id DESC",0,10);
    if(is_array($orderListTmp) && !empty($orderListTmp)){
        foreach ($orderListTmp as $key => $value){
            $updateData = array();
            $updateData['order_status'] = 3;
            C::t('#tom_tongcheng#tom_tongcheng_order')->update($value['id'],$updateData);
        }
    }
    
    if($pay_price > 0){
        
        if(!file_exists(DISCUZ_ROOT.'./source/plugin/tom_pay/tom_pay.inc.php')){
            $outArr = array(
                'status'=> 302,
            );
            echo json_encode($outArr); exit;
        }
        
        $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);
        
        $insertData = array();
        $insertData['site_id']          = $tcshopInfo['site_id'];
        $insertData['order_no']         = $order_no;
        $insertData['order_type']       = 6;
        $insertData['user_id']          = $userInfo['id'];
        $insertData['openid']           = $userInfo['openid'];
        $insertData['tcshop_id']        = $tcshop_id;
        $insertData['pay_price']        = $pay_price;
        $insertData['order_status']     = 1;
        $insertData['order_time']       = TIMESTAMP;
        if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){

            $insertData = array();
            $insertData['plugin_id']       = 'tom_tcshop';
            $insertData['order_no']        = $order_no;
            $insertData['goods_id']        = $tcshop_id;
            $insertData['goods_name']      = lang('plugin/tom_tongcheng','order_type_6');
            $insertData['goods_beizu']     = lang('plugin/tom_tongcheng','order_type_6');
            $insertData['goods_url']       = "plugin.php?id=tom_tcshop&site={$site_id}&mod=details&tcshop_id={$tcshop_id}";
            $insertData['succ_back_url']   = "plugin.php?id=tom_tcshop&site={$site_id}&mod=mylist&tcshop_id={$tcshop_id}";
            $insertData['fail_back_url']   = "plugin.php?id=tom_tcshop&site={$site_id}&mod=mylist&tcshop_id={$tcshop_id}";
            $insertData['allow_alipay']    = 1;
            $insertData['pay_price']       = $pay_price;
            $insertData['order_status']    = 1;
            $insertData['add_time']        = TIMESTAMP;
            if(C::t('#tom_pay#tom_pay_order')->insert($insertData)){
                $outArr = array(
                    'status'    => 200,
                    'payurl' => "plugin.php?id=tom_pay&order_no=".$order_no,
                );
                echo json_encode($outArr); exit;
            }else{
                $outArr = array(
                    'status'=> 303,
                );
                echo json_encode($outArr); exit;
            }

        }else{
            $outArr = array(
                'status'=> 304,
            );
            echo json_encode($outArr); exit;
        }

    }else{
        $outArr = array(
            'status'=> 400,
        );
        echo json_encode($outArr); exit;
    }
    
}else if($act == "xufei" && $_GET['formhash'] == FORMHASH){
    
    $tcshop_id   = isset($_GET['tcshop_id'])? intval($_GET['tcshop_id']):0;
    
    $tcshopInfo = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($tcshop_id);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tcshopInfo['user_id']);
    
    if($tcshopInfo['base_level'] == 1){
        $outArr = array(
            'status'=> 500,
        );
        echo json_encode($outArr); exit;
    }
    
    if($tcshopInfo['status'] != 3){
        $outArr = array(
            'status'=> 500,
        );
        echo json_encode($outArr); exit;
    }
    
    $pay_price = $tcshopConfig['base_xufei_price'];
    
    $orderListTmp = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_list(" AND tcshop_id={$tcshop_id} AND user_id={$userInfo['id']} AND order_type=7 AND order_status=1 ","ORDER BY id DESC",0,10);
    if(is_array($orderListTmp) && !empty($orderListTmp)){
        foreach ($orderListTmp as $key => $value){
            $updateData = array();
            $updateData['order_status'] = 3;
            C::t('#tom_tongcheng#tom_tongcheng_order')->update($value['id'],$updateData);
        }
    }
    
    if($pay_price > 0){
        
        if(!file_exists(DISCUZ_ROOT.'./source/plugin/tom_pay/tom_pay.inc.php')){
            $outArr = array(
                'status'=> 302,
            );
            echo json_encode($outArr); exit;
        }
        
        $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);
        
        $insertData = array();
        $insertData['site_id']          = $tcshopInfo['site_id'];
        $insertData['order_no']         = $order_no;
        $insertData['order_type']       = 7;
        $insertData['user_id']          = $userInfo['id'];
        $insertData['openid']           = $userInfo['openid'];
        $insertData['tcshop_id']        = $tcshop_id;
        $insertData['pay_price']        = $pay_price;
        $insertData['order_status']     = 1;
        $insertData['order_time']       = TIMESTAMP;
        if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){

            $insertData = array();
            $insertData['plugin_id']       = 'tom_tcshop';
            $insertData['order_no']        = $order_no;
            $insertData['goods_id']        = $tcshop_id;
            $insertData['goods_name']      = lang('plugin/tom_tongcheng','order_type_7');
            $insertData['goods_beizu']     = lang('plugin/tom_tongcheng','order_type_7');
            $insertData['goods_url']       = "plugin.php?id=tom_tcshop&site={$site_id}&mod=details&tcshop_id={$tcshop_id}";
            $insertData['succ_back_url']   = "plugin.php?id=tom_tcshop&site={$site_id}&mod=mylist&tcshop_id={$tcshop_id}";
            $insertData['fail_back_url']   = "plugin.php?id=tom_tcshop&site={$site_id}&mod=mylist&tcshop_id={$tcshop_id}";
            $insertData['allow_alipay']    = 1;
            $insertData['pay_price']       = $pay_price;
            $insertData['order_status']    = 1;
            $insertData['add_time']        = TIMESTAMP;
            if(C::t('#tom_pay#tom_pay_order')->insert($insertData)){
                $outArr = array(
                    'status'    => 200,
                    'payurl' => "plugin.php?id=tom_pay&order_no=".$order_no,
                );
                echo json_encode($outArr); exit;
            }else{
                $outArr = array(
                    'status'=> 303,
                );
                echo json_encode($outArr); exit;
            }

        }else{
            $outArr = array(
                'status'=> 304,
            );
            echo json_encode($outArr); exit;
        }

    }else{
        $outArr = array(
            'status'=> 400,
        );
        echo json_encode($outArr); exit;
    }
    
}else if($act == "top" && $_GET['formhash'] == FORMHASH){
    
    $tcshop_id   = isset($_GET['tcshop_id'])? intval($_GET['tcshop_id']):0;
    $days   = intval($_GET['days'])>1? intval($_GET['days']):1;
    
    $tcshopInfo = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($tcshop_id);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tcshopInfo['user_id']); 
    
    $orderListTmp = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_list(" AND tcshop_id={$tcshop_id} AND user_id={$userInfo['id']} AND order_type=5 AND order_status=1 ","ORDER BY id DESC",0,10);
    if(is_array($orderListTmp) && !empty($orderListTmp)){
        foreach ($orderListTmp as $key => $value){
            $updateData = array();
            $updateData['order_status'] = 3;
            C::t('#tom_tongcheng#tom_tongcheng_order')->update($value['id'],$updateData);
        }
    }
    
    if($tcshopConfig['top_price'] > 0){
        
        if(!file_exists(DISCUZ_ROOT.'./source/plugin/tom_pay/tom_pay.inc.php')){
            $outArr = array(
                'status'=> 302,
            );
            echo json_encode($outArr); exit;
        }
        
        $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);
        $pay_price = $tcshopConfig['top_price'] * $days;

        $insertData = array();
        $insertData['site_id']          = $tcshopInfo['site_id'];
        $insertData['order_no']         = $order_no;
        $insertData['order_type']       = 5;
        $insertData['user_id']          = $userInfo['id'];
        $insertData['openid']           = $userInfo['openid'];
        $insertData['tcshop_id']        = $tcshop_id;
        $insertData['pay_price']        = $pay_price;
        $insertData['time_value']       = $days;
        $insertData['order_status']     = 1;
        $insertData['order_time']       = TIMESTAMP;
        if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){

            $insertData = array();
            $insertData['plugin_id']       = 'tom_tcshop';
            $insertData['order_no']        = $order_no;
            $insertData['goods_id']        = $tcshop_id;
            $insertData['goods_name']      = lang('plugin/tom_tongcheng','order_type_5');
            $insertData['goods_beizu']     = lang('plugin/tom_tongcheng','order_type_5');
            $insertData['goods_url']       = "plugin.php?id=tom_tcshop&site={$site_id}&mod=details&tcshop_id={$tcshop_id}";
            $insertData['succ_back_url']   = "plugin.php?id=tom_tcshop&site={$site_id}&mod=mylist&tcshop_id={$tcshop_id}";
            $insertData['fail_back_url']   = "plugin.php?id=tom_tcshop&site={$site_id}&mod=buy&tcshop_id={$tcshop_id}";
            $insertData['allow_alipay']    = 1;
            $insertData['pay_price']       = $pay_price;
            $insertData['order_status']    = 1;
            $insertData['add_time']        = TIMESTAMP;
            if(C::t('#tom_pay#tom_pay_order')->insert($insertData)){
                $outArr = array(
                    'status'    => 200,
                    'payurl' => "plugin.php?id=tom_pay&order_no=".$order_no,
                );
                echo json_encode($outArr); exit;
                
            }else{
                $outArr = array(
                    'status'=> 303,
                );
                echo json_encode($outArr); exit;
            }
            
        }else{
            $outArr = array(
                'status'=> 304,
            );
            echo json_encode($outArr); exit;
        }

    }else{
        $outArr = array(
            'status'=> 400,
        );
        echo json_encode($outArr); exit;
    }
    
}else{
    $outArr = array(
        'status'=> 111111,
    );
    echo json_encode($outArr); exit;
}


    

