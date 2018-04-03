<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$site_id = intval($_GET['site'])>0? intval($_GET['site']):1;

$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];

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

## tcmajia start
$__ShowTcmajia = 0;
$tcmajiaConfig = array();
if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_tcmajia/tom_tcmajia.inc.php')){
    $tcmajiaConfig = $_G['cache']['plugin']['tom_tcmajia'];
    if($tcmajiaConfig['open_tcmajia'] == 1){
        $__ShowTcmajia = 1;
    }
}
## tcmajia end

$act = isset($_GET['act'])? addslashes($_GET['act']):"fabu";

if($act == "fabu" && $_GET['formhash'] == FORMHASH){
    
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
    
    $user_id    = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    $model_id   = isset($_GET['model_id'])? intval($_GET['model_id']):0;
    $type_id    = isset($_GET['type_id'])? intval($_GET['type_id']):0;
    $cate_id    = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
    $city_id    = isset($_GET['city_id'])? intval($_GET['city_id']):0;
    $area_id    = isset($_GET['area_id'])? intval($_GET['area_id']):0;
    $street_id  = isset($_GET['street_id'])? intval($_GET['street_id']):0;
    $tcshop_id    = isset($_GET['tcshop_id'])? intval($_GET['tcshop_id']):0;
    $title      = isset($_GET['title'])? addslashes($_GET['title']):'';
    $xm         = isset($_GET['xm'])? addslashes($_GET['xm']):'';
    $tel        = isset($_GET['tel'])? addslashes($_GET['tel']):'';
    $content    = isset($_GET['content'])? addslashes($_GET['content']):'';
    $content    = filterEmoji($content);
    $open_majia = isset($_GET['open_majia'])? intval($_GET['open_majia']):0;
    
    $zd        = isset($_GET['zd']) ? addslashes($_GET['zd']): 0;
    $bz        = isset($_GET['bz']) ? addslashes($_GET['bz']): 0;
    $jf        = isset($_GET['jf']) ? addslashes($_GET['jf']): 0;
    $fs        = isset($_GET['fs']) ? addslashes($_GET['fs']): 0;
    $sc        = isset($_GET['tel']) ? addslashes($_GET['sc']): 0;
    $td        = isset($_GET['td']) ? addslashes($_GET['td']): 0;
    if($zd == 1){
    	 $zdend = time();
    	 $zdend += $sc * 86400;
    }
    
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($user_id);
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($type_id);
    $modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($typeInfo['model_id']);
    
    if(empty($userInfo) || empty($typeInfo)){
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
    
    $lastTongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list(" AND user_id={$user_id} "," ORDER BY id DESC ",0,1);
    if($lastTongchengListTmp && $lastTongchengListTmp[0]['add_time'] > 0 && $userInfo['editor']==0){
        $nextFabuTime = $lastTongchengListTmp[0]['add_time'] + $tongchengConfig['fabu_next_minute']*60;
        if($nextFabuTime > TIMESTAMP){
            $outArr = array(
                'status'=> 305,
            );
            echo json_encode($outArr); exit;
        }
    }
    
    $__CommonInfo = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_by_id(1);
    if(!empty($__CommonInfo['forbid_word'])){
        $forbid_word = preg_quote(trim($__CommonInfo['forbid_word']), '/');
        $forbid_word = str_replace(array("\\*"), array('.*'), $forbid_word);
        $forbid_word = '.*('.$forbid_word.').*';
        $forbid_word = '/^('.str_replace(array("\r\n", ' '), array(').*|.*(', ''), $forbid_word).')$/i';
        if(@preg_match($forbid_word, $content,$matches)) {
            $i = count($matches)-1;
            $word = '';
            if(isset($matches[$i]) && !empty($matches[$i])){
                $word = diconv($matches[$i],CHARSET,'utf-8');
            }
            $outArr = array(
                'status'=> 505,
                'word'=> $word,
            );
            echo json_encode($outArr); exit;
        }
                
    }
    
    $last20TongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list(" AND status=1 AND shenhe_status=1 "," ORDER BY id DESC ",0,20);
    if(is_array($last20TongchengListTmp) && !empty($last20TongchengListTmp)){
        foreach ($last20TongchengListTmp as $key => $value){
            $contentTmp = contentFormat($value['content']);
            similar_text($content,$contentTmp,$percentTmp);
            if($percentTmp > 85){
                $outArr = array(
                    'status'=> 501,
                    'percent'=> $percentTmp,
                );
                echo json_encode($outArr); exit;
            }
        }
    }
    
    $attrnameArr = $attrArr = $attrunitArr = $attrpaixuArr = $attrdateArr = $tagnameArr = $photoArr = array();
    foreach($_GET as $key => $value){
        if(strpos($key, "attrname_") !== false){
            $attr_id = intval(ltrim($key, 'attrname_'));
            $attrnameArr[$attr_id] = addslashes($value);
        }
        if(strpos($key, "attrpaixu_") !== false){
            $attr_id = intval(ltrim($key, 'attrpaixu_'));
            $attrpaixuArr[$attr_id] = addslashes($value);
        }
        if(strpos($key, "attrunit_") !== false){
            $attr_id = intval(ltrim($key, 'attrunit_'));
            $attrunitArr[$attr_id] = addslashes($value);
        }
        if(strpos($key, "attr_") !== false){
            $attr_id = intval(ltrim($key, 'attr_'));
            if(is_array($value)){
                $valueTmp = implode(" ", $value);
                $attrArr[$attr_id] = addslashes($valueTmp);
            }else{
                $attrArr[$attr_id] = addslashes($value);
            }
        }
        if(strpos($key, "attrdate_") !== false){
            $attr_id = intval(ltrim($key, 'attrdate_'));
            $value = str_replace("T", " ", $value);
            $attrdateArr[$attr_id] = addslashes($value);
        }
        if(strpos($key, "tagname_") !== false){
            $tag_id = intval(ltrim($key, 'tagname_'));
            $tagnameArr[$tag_id] = addslashes($value);
        }
        if(strpos($key, "photo_") !== false){
            $photoArr[] = addslashes($value);
        }
    }
    
    $tagArr = array();
    if(isset($_GET['tag']) && is_array($_GET['tag'])){
        foreach ($_GET['tag'] as $key => $value){
            $tagArr[] = intval($value);
        }
    }
    
    $search_content = '';
    if(is_array($attrArr) && !empty($attrArr)){
        foreach ($attrArr as $key => $value){
            $search_content.=''.$attrnameArr[$key].$value.'';
        }
    }
    if(is_array($tagArr) && !empty($tagArr)){
        foreach ($tagArr as $key => $value){
            $search_content.=''.$tagnameArr[$value].'';
        }
    }
    
    $fabuPayStatus = 0;
    if($typeInfo['free_status'] == 2 && $typeInfo['fabu_price'] > 0 && $userInfo['editor']==0){
        $fabuPayStatus = 1;
        if($tongchengConfig['score_yuan'] > 0){
            $useScore = $tongchengConfig['score_yuan']*$typeInfo['fabu_price'];
            $useScore = ceil($useScore);
            if($userInfo['score'] > $useScore){
                $fabuPayStatus = 2;
            }
        }
    }
    
    ## tcmajia start
    $openMajiaStatus = 0;
    if($__ShowTcmajia == 1 && $userInfo['editor']==1){
        if($tcmajiaConfig['use_majia_admin_1'] == $userInfo['id']){
            $openMajiaStatus = 1;
        }
        if($tcmajiaConfig['use_majia_admin_2'] == $userInfo['id']){
            $openMajiaStatus = 1;
        }
        if($tcmajiaConfig['use_majia_admin_3'] == $userInfo['id']){
            $openMajiaStatus = 1;
        }
        if($tcmajiaConfig['use_majia_admin_4'] == $userInfo['id']){
            $openMajiaStatus = 1;
        }
        if($tcmajiaConfig['use_majia_admin_5'] == $userInfo['id']){
            $openMajiaStatus = 1;
        }
    }

    if($open_majia == 1){
        $tcmajiaCount = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_all_count(" AND is_majia = 1 ");
        if($openMajiaStatus == 1 && $tcmajiaCount > 0){
            $num = $tcmajiaCount - 1;
            if($num == 0){
                $tcmajiaInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_all_list(' AND is_majia = 1 ', 'ORDER BY id DESC', 0, 1);
            }else{
                $randomNum = mt_rand(0, $num);
                $tcmajiaInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_all_list(' AND is_majia = 1 ', 'ORDER BY id DESC', $randomNum, 1);
            }

            if(is_array($tcmajiaInfoTmp) && !empty($tcmajiaInfoTmp[0])){
                $tcshop_id = 0;
                $user_id = $tcmajiaInfoTmp[0]['id'];
            }else{
                $outArr = array(
                    'status'=> 306,
                );
                echo json_encode($outArr); exit;
            }
            
        }else{
            $outArr = array(
                'status'=> 306,
            );
            echo json_encode($outArr); exit;
        }
        
    }
    ## tcmajia end

    $insertData = array();
    $insertData['site_id']      = $site_id;
    $insertData['user_id']      = $user_id;
    $insertData['model_id']     = $model_id;
    $insertData['type_id']      = $type_id;
    $insertData['cate_id']      = $cate_id;
    $insertData['city_id']      = $city_id;
    $insertData['area_id']      = $area_id;
    $insertData['street_id']    = $street_id;
    $insertData['tcshop_id']    = $tcshop_id;
    $insertData['title']        = $title;
    $insertData['xm']           = $xm;
    $insertData['tel']          = $tel;
    $insertData['content']      = $content.'|+|+|+|+|+|+|+|+|+|'.$search_content.'-'.$xm.'-'.$tel;
    $insertData['refresh_time'] = TIMESTAMP;
    $insertData['add_time']     = TIMESTAMP;
    
    $insertData['sc'] = $sc;
    $insertData['toptime'] = $zdend;
    $insertData['topstatus'] = 0;
    $insertData['bz'] = $bz;
    $insertData['jf'] = $jf;
    $insertData['fs'] = $fs;
    $insertData['td'] = $td;
    
    
    if($fabuPayStatus == 1){
        $insertData['status']       = 2;
        $insertData['pay_status']   = 1;
    }else{
        $insertData['status']       = 1;
        $insertData['pay_status']   = 0;
    }
    if($modelInfo['must_shenhe'] == 1 && $userInfo['editor']==0){
        $insertData['shenhe_status']       = 2;
    }else{
        $insertData['shenhe_status']       = 1;
    }
    if(C::t('#tom_tongcheng#tom_tongcheng')->insert($insertData)){
        
        $tongchengId = C::t('#tom_tongcheng#tom_tongcheng')->insert_id();
        
        if($fabuPayStatus == 2){
            
            $updateData = array();
            $updateData['score'] = $userInfo['score'] - $useScore;
            C::t('#tom_tongcheng#tom_tongcheng_user')->update($userInfo['id'],$updateData);
            
            $insertData = array();
            $insertData['user_id']          = $userInfo['id'];
            $insertData['score_value']      = $useScore;
            $insertData['old_value']        = $userInfo['score'];
            $insertData['log_type']         = 4;
            $insertData['log_time']         = TIMESTAMP;
            C::t('#tom_tongcheng#tom_tongcheng_score_log')->insert($insertData);
        }
        
       
      
       
        
        
        if(is_array($attrArr) && !empty($attrArr)){
            foreach ($attrArr as $key => $value){
                $insertData = array();
                $insertData['model_id']     = $model_id;
                $insertData['type_id']      = $type_id;
                $insertData['tongcheng_id'] = $tongchengId;
                $insertData['attr_id']      = $key;
                $insertData['attr_name']    = $attrnameArr[$key];
                $insertData['value']        = $value;
                $insertData['unit']         = $attrunitArr[$key];
                $insertData['paixu']        = $attrpaixuArr[$key];
                $insertData['add_time']     = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_attr')->insert($insertData);
            }
        }
        
        if(is_array($attrdateArr) && !empty($attrdateArr)){
            foreach ($attrdateArr as $key => $value){
                $insertData = array();
                $insertData['model_id']     = $model_id;
                $insertData['type_id']      = $type_id;
                $insertData['tongcheng_id'] = $tongchengId;
                $insertData['attr_id']      = $key;
                $insertData['attr_name']    = $attrnameArr[$key];
                $insertData['value']        = $value;
                $insertData['time_value']   = strtotime($value);
                $insertData['unit']         = $attrunitArr[$key];
                $insertData['paixu']        = $attrpaixuArr[$key];
                $insertData['add_time']     = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_attr')->insert($insertData);
            }
        }
        
        if(is_array($tagArr) && !empty($tagArr)){
            foreach ($tagArr as $key => $value){
                $insertData = array();
                $insertData['model_id']     = $model_id;
                $insertData['type_id']      = $type_id;
                $insertData['tongcheng_id'] = $tongchengId;
                $insertData['tag_id']       = $value;
                $insertData['tag_name']     = $tagnameArr[$value];
                $insertData['add_time']     = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_tag')->insert($insertData);
            }
        }
        
        if(is_array($photoArr) && !empty($photoArr)){
            foreach ($photoArr as $key => $value){
                $insertData = array();
                $insertData['tongcheng_id'] = $tongchengId;
                $insertData['picurl'] = $value;
                $insertData['add_time']     = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_photo')->insert($insertData);
            }
        }
        $money = 0;
        $jf = 0;
        if(($zd == 1) && ($sc > 0)){
        	$money += $sc * $typeInfo['top_price'];
        }
        if(($td > 0) && ($typeInfo['free_status'] == 2)){
        	$money += $td * $typeInfo['fabu_price'];
        }
        if(($td > 0) && ($typeInfo['free_status'] == 1)){
        	DB::update('tom_tongcheng' , array('refresh_time' => time() + (86400 * $td)) , array('id' => $tongcheng_id));
        }
        $jf = $money * $tongchengConfig['score_yuan'];
        if($jf <= $userInfo['score']){
        	$updateData = array();
        	$updateData['score'] = $userInfo['score'] - $jf;
        	C::t('#tom_tongcheng#tom_tongcheng_user')->update($userInfo['id'],$updateData);

        	$insertData = array();
        	$insertData['user_id']          = $userInfo['id'];
        	$insertData['score_value']      = $jf;
        	$insertData['old_value']        = $userInfo['score'];
        	$insertData['log_type']         = 4;
        	$insertData['log_time']         = TIMESTAMP;
        	C::t('#tom_tongcheng#tom_tongcheng_score_log')->insert($insertData);
        	DB::update('tom_tongcheng' , array('topstatus' => 1 ) , array('id' => $tongchengId));
        }else if($money > 0){
        	if(!file_exists(DISCUZ_ROOT.'./source/plugin/tom_pay/tom_pay.inc.php')){
        		$outArr = array(
        		'status'=> 307,
        		);
        		echo json_encode($outArr); exit;
        	}

        	$order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);

        	$insertData = array();
        	$insertData['site_id']          = $site_id;
        	$insertData['order_no']         = $order_no;
        	$insertData['order_type']       = 1;
        	$insertData['user_id']          = $user_id;
        	$insertData['openid']           = $userInfo['openid'];
        	$insertData['tongcheng_id']     = $tongchengId;
        	$insertData['pay_price']        = $money;
        	$insertData['order_status']     = 1;
        	$insertData['order_time']       = TIMESTAMP;
        	if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){
        		$order_id = C::t('#tom_tongcheng#tom_tongcheng_order')->insert_id();

        		$insertData = array();
        		$insertData['plugin_id']       = 'tom_tongcheng';
        		$insertData['order_no']        = $order_no;
        		$insertData['goods_id']        = $tongchengId;
        		$insertData['goods_name']      = '置顶/排序显示花费金额';
        		$insertData['goods_beizu']     = '';
        		$insertData['goods_url']       = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=info&tongcheng_id={$tongchengId}";
        		$insertData['succ_back_url']   = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=fabu&act=step2&type_id={$type_id}&pay_ok=1&zd=1&tongcheng_id={$tongchengId}";
        		$insertData['fail_back_url']   = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist";
        		$insertData['allow_alipay']    = 1;
        		$insertData['pay_price']       = $money;
        		$insertData['order_status']    = 1;
        		$insertData['add_time']        = TIMESTAMP;
        		if(C::t('#tom_pay#tom_pay_order')->insert($insertData)){
        			$outArr = array(
	        			'tongcheng_id'=> $tongchengId,
	        			'status' => 320,
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
        
        
        ## pay start
        if($typeInfo['free_status'] == 2 && ($typeInfo['fabu_price'] > 0)){
            if(!file_exists(DISCUZ_ROOT.'./source/plugin/tom_pay/tom_pay.inc.php')){
                $outArr = array(
                    'status'=> 307,
                );
                echo json_encode($outArr); exit;
            }
            
            $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);
            
            $insertData = array();
            $insertData['site_id']          = $site_id;
            $insertData['order_no']         = $order_no;
            $insertData['order_type']       = 1;
            $insertData['user_id']          = $user_id;
            $insertData['openid']           = $userInfo['openid'];
            $insertData['tongcheng_id']     = $tongchengId;
            $insertData['pay_price']        = $typeInfo['fabu_price'];
            $insertData['order_status']     = 1;
            $insertData['order_time']       = TIMESTAMP;
            if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){
                $order_id = C::t('#tom_tongcheng#tom_tongcheng_order')->insert_id();

                $insertData = array();
                $insertData['plugin_id']       = 'tom_tongcheng';
                $insertData['order_no']        = $order_no;            
                $insertData['goods_id']        = $tongchengId;           
                $insertData['goods_name']      = lang('plugin/tom_tongcheng','order_type_1');
                $insertData['goods_beizu']     = lang('plugin/tom_tongcheng','order_type_1');
                $insertData['goods_url']       = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=info&tongcheng_id={$tongchengId}";
                $insertData['succ_back_url']   = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=fabu&act=step2&type_id={$type_id}&pay_ok=1&tongcheng_id={$tongchengId}";
                $insertData['fail_back_url']   = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist";
                $insertData['allow_alipay']    = 1;         
                $insertData['pay_price']       = $typeInfo['fabu_price'];    
                $insertData['order_status']    = 1;             
                $insertData['add_time']        = TIMESTAMP;     
                if(C::t('#tom_pay#tom_pay_order')->insert($insertData)){
                    $outArr = array(
                        'tongcheng_id'=> $tongchengId,
                        'status' => 200,
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
            'status'=> 200,
            'tongcheng_id'=> $tongchengId,
        );
        echo json_encode($outArr); exit;
        
    }else{
        $outArr = array(
            'status'=> 404,
        );
        echo json_encode($outArr); exit;
    }
    
    
}else if($act == "pay" && $_GET['formhash'] == FORMHASH){
    
    $tongcheng_id   = isset($_GET['tongcheng_id'])? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengInfo['user_id']); 
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);
    
    $orderListTmp = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_list(" AND tongcheng_id={$tongcheng_id} AND user_id={$userInfo['id']} AND order_type=1 AND order_status=1 ","ORDER BY id DESC",0,10);
    if(is_array($orderListTmp) && !empty($orderListTmp)){
        foreach ($orderListTmp as $key => $value){
            $updateData = array();
            $updateData['order_status'] = 3;
            C::t('#tom_tongcheng#tom_tongcheng_order')->update($value['id'],$updateData);
        }
    }
    
    if($typeInfo['free_status'] == 2 && $typeInfo['fabu_price'] > 0){
        if(!file_exists(DISCUZ_ROOT.'./source/plugin/tom_pay/tom_pay.inc.php')){
            $outArr = array(
                'status'=> 307,
            );
            echo json_encode($outArr); exit;
        }
        
        $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);

        $insertData = array();
        $insertData['site_id']          = $tongchengInfo['site_id'];
        $insertData['order_no']         = $order_no;
        $insertData['order_type']       = 1;
        $insertData['user_id']          = $userInfo['id'];
        $insertData['openid']           = $userInfo['openid'];
        $insertData['tongcheng_id']     = $tongcheng_id;
        $insertData['pay_price']        = $typeInfo['fabu_price'];
        $insertData['order_status']     = 1;
        $insertData['order_time']       = TIMESTAMP;
        if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){
            $order_id = C::t('#tom_tongcheng#tom_tongcheng_order')->insert_id();

            $insertData = array();
            $insertData['plugin_id']       = 'tom_tongcheng';
            $insertData['order_no']        = $order_no;            
            $insertData['goods_id']        = $tongcheng_id;           
            $insertData['goods_name']      = lang('plugin/tom_tongcheng','order_type_1');
            $insertData['goods_beizu']     = lang('plugin/tom_tongcheng','order_type_1');
            $insertData['goods_url']       = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=info&tongcheng_id={$tongcheng_id}";
            $insertData['succ_back_url']   = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist";
            $insertData['fail_back_url']   = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist";
            $insertData['allow_alipay']    = 1;         
            $insertData['pay_price']       = $typeInfo['fabu_price'];    
            $insertData['order_status']    = 1;             
            $insertData['add_time']        = TIMESTAMP;     
            if(C::t('#tom_pay#tom_pay_order')->insert($insertData)){
                $outArr = array(
                    'status'=> 200,
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
    
}else if($act == "refresh" && $_GET['formhash'] == FORMHASH){
    
    $tongcheng_id   = isset($_GET['tongcheng_id'])? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengInfo['user_id']); 
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);
    
    $orderListTmp = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_list(" AND tongcheng_id={$tongcheng_id} AND user_id={$userInfo['id']} AND order_type=2 AND order_status=1 ","ORDER BY id DESC",0,10);
    if(is_array($orderListTmp) && !empty($orderListTmp)){
        foreach ($orderListTmp as $key => $value){
            $updateData = array();
            $updateData['order_status'] = 3;
            C::t('#tom_tongcheng#tom_tongcheng_order')->update($value['id'],$updateData);
        }
    }
    
    if($typeInfo['refresh_price'] > 0){
        if(!file_exists(DISCUZ_ROOT.'./source/plugin/tom_pay/tom_pay.inc.php')){
            $outArr = array(
                'status'=> 307,
            );
            echo json_encode($outArr); exit;
        }
        
        $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);

        $insertData = array();
        $insertData['site_id']          = $tongchengInfo['site_id'];
        $insertData['order_no']         = $order_no;
        $insertData['order_type']       = 2;
        $insertData['user_id']          = $userInfo['id'];
        $insertData['openid']           = $userInfo['openid'];
        $insertData['tongcheng_id']     = $tongcheng_id;
        $insertData['pay_price']        = $typeInfo['refresh_price'];
        $insertData['order_status']     = 1;
        $insertData['order_time']       = TIMESTAMP;
        if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){
            $order_id = C::t('#tom_tongcheng#tom_tongcheng_order')->insert_id();
            
            $insertData = array();
            $insertData['plugin_id']       = 'tom_tongcheng';
            $insertData['order_no']        = $order_no;            
            $insertData['goods_id']        = $tongcheng_id;           
            $insertData['goods_name']      = lang('plugin/tom_tongcheng','order_type_2');
            $insertData['goods_beizu']     = lang('plugin/tom_tongcheng','order_type_2');
            $insertData['goods_url']       = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=info&tongcheng_id={$tongcheng_id}";
            $insertData['succ_back_url']   = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist";
            $insertData['fail_back_url']   = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist";
            $insertData['allow_alipay']    = 1;         
            $insertData['pay_price']       = $typeInfo['refresh_price'];    
            $insertData['order_status']    = 1;             
            $insertData['add_time']        = TIMESTAMP;     
            if(C::t('#tom_pay#tom_pay_order')->insert($insertData)){
                $outArr = array(
                    'status'=> 200,
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
    
    $tongcheng_id   = isset($_GET['tongcheng_id'])? intval($_GET['tongcheng_id']):0;
    $days   = intval($_GET['days'])>1? intval($_GET['days']):1;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengInfo['user_id']); 
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);
    
    $orderListTmp = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_list(" AND tongcheng_id={$tongcheng_id} AND user_id={$userInfo['id']} AND order_type=3 AND order_status=1 ","ORDER BY id DESC",0,10);
    if(is_array($orderListTmp) && !empty($orderListTmp)){
        foreach ($orderListTmp as $key => $value){
            $updateData = array();
            $updateData['order_status'] = 3;
            C::t('#tom_tongcheng#tom_tongcheng_order')->update($value['id'],$updateData);
        }
    }
    
    $topCount = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count(" AND id != {$tongcheng_id} AND model_id={$tongchengInfo['model_id']} AND topstatus=1 AND status=1 AND shenhe_status=1 ");
    $limit_top_num = $tongchengConfig['limit_top_num'];
    $min_top_num = $tongchengConfig['min_top_num'];
    if($limit_top_num > 0 && $topCount >= $limit_top_num){
        if($days < $min_top_num){
            $outArr = array(
                'status'=> 302,
            );
            echo json_encode($outArr); exit;
        }
    }
    
    if($typeInfo['top_price'] > 0){
        if(!file_exists(DISCUZ_ROOT.'./source/plugin/tom_pay/tom_pay.inc.php')){
            $outArr = array(
                'status'=> 307,
            );
            echo json_encode($outArr); exit;
        }
        
        $order_no = "TC".date("YmdHis")."-".mt_rand(111111, 666666);

        $insertData = array();
        $insertData['site_id']          = $tongchengInfo['site_id'];
        $insertData['order_no']         = $order_no;
        $insertData['order_type']       = 3;
        $insertData['user_id']          = $userInfo['id'];
        $insertData['openid']           = $userInfo['openid'];
        $insertData['tongcheng_id']     = $tongcheng_id;
        $insertData['pay_price']        = $typeInfo['top_price']*$days;
        $insertData['time_value']       = $days;
        $insertData['order_status']     = 1;
        $insertData['order_time']       = TIMESTAMP;
        if(C::t('#tom_tongcheng#tom_tongcheng_order')->insert($insertData)){
            $order_id = C::t('#tom_tongcheng#tom_tongcheng_order')->insert_id();
            
            $insertData = array();
            $insertData['plugin_id']       = 'tom_tongcheng';
            $insertData['order_no']        = $order_no;            
            $insertData['goods_id']        = $tongcheng_id;           
            $insertData['goods_name']      = lang('plugin/tom_tongcheng','order_type_3');
            $insertData['goods_beizu']     = lang('plugin/tom_tongcheng','order_type_3');
            $insertData['goods_url']       = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=info&tongcheng_id={$tongcheng_id}";
            $insertData['succ_back_url']   = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist";
            $insertData['fail_back_url']   = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=buy&tongcheng_id={$tongcheng_id}";
            $insertData['allow_alipay']    = 1;         
            $insertData['pay_price']       = $typeInfo['top_price']*$days;    
            $insertData['order_status']    = 1;             
            $insertData['add_time']        = TIMESTAMP;     
            if(C::t('#tom_pay#tom_pay_order')->insert($insertData)){
                $outArr = array(
                    'status'=> 200,
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
    
}elseif ($act == 'zdprice'){

}
else{
    $outArr = array(
        'status'=> 111111,
    );
    echo json_encode($outArr); exit;
}


    

