<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
   
   unionid 同步脚本

*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

ignore_user_abort(true);
set_time_limit(0);

$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];

$appid      = trim($tongchengConfig['wxpay_appid']);  
$appsecret  = trim($tongchengConfig['wxpay_appsecret']);

$page = intval($_GET['page'])>0? intval($_GET['page']):1;

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/weixin.class.php';
$weixinClass = new weixinClass($appid,$appsecret);

$ucenterfilenameExists = false;
$ucenterfilename = DISCUZ_ROOT.'./source/plugin/tom_ucenter/tom_ucenter.inc.php';
if(file_exists($ucenterfilename)){
    $ucenterfilenameExists = true;
}

$pagesize = 20;
$start = ($page-1)*$pagesize;
$whereStr   = " AND is_majia=0 ";
$userList  = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_all_list(" {$whereStr} "," ORDER BY id DESC ",$start,$pagesize);
$overStatus = 0;
if(is_array($userList) && !empty($userList)){
    foreach ($userList as $key => $value){
        $access_token = $weixinClass->get_access_token();
        if($access_token && !empty($value['openid']) && $value['openid'] != 'XIAO' && empty($value['unionid']) ){
            $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$value['openid']}&lang=zh_CN";
            $return = get_html($get_user_info_url);
            $unionid = '';
            $openid = '';
            if(!empty($return)){
                $content = json_decode($return,true);
                if(is_array($content) && !empty($content) && isset($content['unionid']) && !empty($content['unionid'])){
                    $openid = $content['openid'];
                    $unionid = $content['unionid'];
                }
            }
            
            if(!empty($unionid) && !empty($openid) && $openid == $value['openid']){
                $updateData             = array();
                $updateData['unionid']  = $unionid;
                C::t('#tom_tongcheng#tom_tongcheng_user')->update($value['id'],$updateData);
                
                if(!empty($value['member_id']) && $ucenterfilenameExists){
                    $memberInfoTmp = C::t('#tom_ucenter#tom_ucenter_member')->fetch_by_uid($value['member_id']);
                    if($memberInfoTmp && $memberInfoTmp['openid'] == $openid ){
                        $updateData             = array();
                        $updateData['unionid']  = $unionid;
                        C::t('#tom_ucenter#tom_ucenter_member')->update($memberInfoTmp['uid'],$updateData);
                    }
                }
            }
        }
    }
}else{
    $overStatus = 1;
}

if($overStatus == 0){
    $page++;
    $unionidBatchUrl = $_G['siteurl']."plugin.php?id=tom_tongcheng:unionidBatch&page=".$page;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $unionidBatchUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $return = curl_exec($ch);
}

if($overStatus){
    echo 'over';exit;
}else{
    echo 'doing... please wait !!!';exit;
}






