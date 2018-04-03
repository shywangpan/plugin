<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act  = isset($_GET['act'])? addslashes($_GET['act']):'';

$ajaxHongbaoTzUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=hongbao_tz&&formhash='.$formhash;


$kefuQrcodeSrc = $tongchengConfig['kefu_qrcode'];
if($__SitesInfo['id'] > 1){
    if(!preg_match('/^http/', $__SitesInfo['kefu_qrcode'])){
        if(strpos($__SitesInfo['kefu_qrcode'], 'source/plugin/tom_tongcheng/') === FALSE){
            $kefuQrcodeSrc = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$__SitesInfo['kefu_qrcode'];
        }else{
            $kefuQrcodeSrc = $__SitesInfo['kefu_qrcode'];
        }
    }else{
        $kefuQrcodeSrc = $__SitesInfo['kefu_qrcode'];
    }
}

$hehuorenFcOpen = 0;
if($site_id > 1 && $__SitesInfo['hehuoren_fc_open'] == 1){
    $hehuorenFcOpen = 1;
}else if($site_id == 1){
    $hehuorenFcOpen = 1;
}

$showHehuorenBtn = 0;
if($__ShowTchehuoren == 1){
    $tchehuorenInfoTmp = C::t('#tom_tchehuoren#tom_tchehuoren')->fetch_by_user_id($__UserInfo['id']);
    if($tchehuorenInfoTmp){
        $showHehuorenBtn = 1;
    }
}

$showTcshopBtn = 0;
if($__ShowTcshop == 1){
    $tcshopListTmp = C::t('#tom_tcshop#tom_tcshop')->fetch_all_list(" AND user_id={$__UserInfo['id']} "," ORDER BY id DESC ",0,10);
    if(is_array($tcshopListTmp) && !empty($tcshopListTmp)){
        $showTcshopBtn = 1;
    }
}

## tcmajia start
$openMajiaStatus = 0;
if($__ShowTcmajia == 1 && $tongchengConfig['open_mobile'] == 1 && $ucenterfilenameExists){
    if($tcmajiaConfig['use_majia_admin_1'] == $__UserInfo['id']){
        $openMajiaStatus = 1;
    }
    if($tcmajiaConfig['use_majia_admin_2'] == $__UserInfo['id']){
        $openMajiaStatus = 1;
    }
    if($tcmajiaConfig['use_majia_admin_3'] == $__UserInfo['id']){
        $openMajiaStatus = 1;
    }
    if($tcmajiaConfig['use_majia_admin_4'] == $__UserInfo['id']){
        $openMajiaStatus = 1;
    }
    if($tcmajiaConfig['use_majia_admin_5'] == $__UserInfo['id']){
        $openMajiaStatus = 1;
    }
    if($__UserInfo['is_majia'] == 1){
        $openMajiaStatus = 1;
    }
}
## tcmajia end

$address_back_url = $weixinClass->get_url();
$address_back_url = urlencode($address_back_url);

$ajaxLoginOutUrl = 'plugin.php?id=tom_ucenter:ajax&site='.$site_id.'&act=loginout&&formhash='.$formhash;
$bbsLoginOutBackUrl = urlencode("plugin.php?id=tom_tongcheng&site={$site_id}&mod=index");
$bbsLoginOut = "member.php?mod=logging&action=logout&referer={$bbsLoginOutBackUrl}&formhash={$formhash}&handlekey=logout";

if($act == 'shop'){
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_tongcheng:personal_shop");  
}else if($act == 'set'){
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_tongcheng:personal_set");
}else{
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_tongcheng:personal");
}




