<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if($_GET['act'] == 'save' && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 1,
    );
    
    $money                    = intval($_GET['money'])>0? intval($_GET['money']):0;
    $type_id                  = intval($_GET['type_id'])>0? intval($_GET['type_id']):1;
    $alipay_zhanghao          = isset($_GET['alipay_zhanghao'])? daddslashes(diconv(urldecode($_GET['alipay_zhanghao']),'utf-8')):'';
    $alipay_truename          = isset($_GET['alipay_truename'])? daddslashes(diconv(urldecode($_GET['alipay_truename']),'utf-8')):'';
    
    if(is_numeric($money) && $money > 0){
    }else{
        $outArr = array(
            'status'=> 301,
        );
        echo json_encode($outArr); exit;
    }
    
    if($money < $tongchengConfig['min_tixian_money'] ){
        $outArr = array(
            'status'=> 302,
        );
        echo json_encode($outArr); exit;
    }
    
    if($__UserInfo['money'] < $money){
        $outArr = array(
            'status'=> 303,
        );
        echo json_encode($outArr); exit;
    }
    
    if($type_id == 2){
        if(empty($alipay_zhanghao) || empty($alipay_truename)){
            $outArr = array(
                'status'=> 304,
            );
            echo json_encode($outArr); exit;
        }
    }
    
    $insertData = array();
    $insertData['user_id']              = $__UserInfo['id'];
    $insertData['type_id']              = $type_id;
    $insertData['money']                = $money;
    $insertData['alipay_zhanghao']      = $alipay_zhanghao;
    $insertData['alipay_truename']      = $alipay_truename;
    $insertData['status']               = 1;
    $insertData['tixian_ip']            = $_G['clientip'];
    $insertData['tixian_time']          = TIMESTAMP;
    if(C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->insert($insertData)){
        
        $tixianId = C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->insert_id();
        
        $updateData = array();
        $updateData['money']            = $__UserInfo['money'] - $money;
        $updateData['tixian_money']     = $__UserInfo['tixian_money'] + $money;
        C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);
        
        $insertData = array();
        $insertData['user_id']      = $__UserInfo['id'];
        $insertData['type_id']      = 1;
        $insertData['change_money'] = $money;
        $insertData['old_money']    = $__UserInfo['money'];
        $insertData['tag']          = lang("plugin/tom_tongcheng", "tixian_tag");
        $insertData['tixian_id']    = $tixianId;
        $insertData['log_ip']       = $_G['clientip'];
        $insertData['log_time']     = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_money_log')->insert($insertData);
        
        include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/templatesms.class.php';
        $access_token = $weixinClass->get_access_token();
        
        $toUser = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengConfig['manage_user_id']);

        if($access_token && !empty($toUser['openid'])){
            $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=index");
            $template_first = str_replace("{NAME}",$__UserInfo['nickname'], lang('plugin/tom_tongcheng','tixian_template_first'));
            $template_first = str_replace("{MONEY}",$money, $template_first);
            $smsData = array(
                'first'         => $template_first,
                'keyword1'      => '',
                'keyword2'      => '',
                'remark'        => ''
            );
            $r = $templateSmsClass->sendSms01($toUser['openid'],$tongchengConfig['template_id'],$smsData);

        }
        
        
        
        $outArr = array(
            'status'=> 200,
        );
        echo json_encode($outArr); exit;
        
    }
    
    $outArr = array(
        'status'=> 404,
    );
    echo json_encode($outArr); exit;
    
}

if(empty($__UserInfo['openid'])){
    $tongchengConfig['tixian_type'] = 2;
}

$must_phone_projectArr = unserialize($tongchengConfig['must_phone_project']);
$showMustPhoneBtn = 0;
if(array_search('3',$must_phone_projectArr) !== false && empty($__UserInfo['tel'])){
    $showMustPhoneBtn = 1;
    $phone_back_url = $weixinClass->get_url();
    $phone_back_url = urlencode($phone_back_url);
    $phoneUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=phone&phone_back={$phone_back_url}";
}

$saveUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=moneytixian&act=save";


$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:moneytixian");  




