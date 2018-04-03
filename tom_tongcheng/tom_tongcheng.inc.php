<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$site_id = intval($_GET['site'])>0? intval($_GET['site']):1;

session_start();
define('TPL_DEFAULT', true);
$formhash = FORMHASH;
$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];
$tomSysOffset = getglobal('setting/timeoffset');
$nowDayTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),dgmdate($_G['timestamp'], 'j',$tomSysOffset),dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;
require_once libfile('function/discuzcode');
//echo '<pre>';print_r($tongchengConfig);
$appid = trim($tongchengConfig['wxpay_appid']);  
$appsecret = trim($tongchengConfig['wxpay_appsecret']);
$prand = rand(1, 1000);
$cssJsVersion = "20171225";

$__IsWeixin = 0;
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
    $__IsWeixin = 1;
}

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/weixin.class.php';
$weixinClass = new weixinClass($appid,$appsecret);

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/function.core.php';
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/qqface.php';

$wxJssdkConfig = array();
$wxJssdkConfig["appId"]     = "";
$wxJssdkConfig["timestamp"] = time();
$wxJssdkConfig["nonceStr"]  = "";
$wxJssdkConfig["signature"] = "";
$wxJssdkConfig = $weixinClass->get_jssdk_config();
$shareTitle = $tongchengConfig['wx_share_title'];
$shareDesc  = $tongchengConfig['wx_share_desc'];
$shareUrl   = $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=index";
$shareLogo  = $tongchengConfig['wx_share_pic'];

//echo '<pre>';print_r($wxJssdkConfig);
$__SitesInfo = array('id'=>1,'name'=>$tongchengConfig['plugin_name'],'dingyue_qrcode'=>$tongchengConfig['dingyue_qrcode']);
$__CityInfo = array('id'=>0,'name'=>'');
if($site_id > 1){
    $sitesInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($site_id);
    if($sitesInfoTmp){
        $__SitesInfo = $sitesInfoTmp;
        if($__SitesInfo['status'] == 2){
            dheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site=1&mod=index");exit;
        }
        if(!empty($__SitesInfo['city_id'])){
            $cityInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($__SitesInfo['city_id']);
            if($cityInfoTmp){
                $__CityInfo = $cityInfoTmp;
            }
        }
        $shareTitle = $__SitesInfo['share_title'];
        $shareDesc  = $__SitesInfo['share_desc'];
        if(!preg_match('/^http/', $__SitesInfo['share_pic']) ){
            $shareLogo = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$__SitesInfo['share_pic'];
        }else{
            $shareLogo = $__SitesInfo['share_pic'];
        }
        if(!empty($__SitesInfo['dingyue_qrcode'])){
            if(!preg_match('/^http/', $__SitesInfo['dingyue_qrcode']) ){
                $__SitesInfo['dingyue_qrcode'] = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$__SitesInfo['dingyue_qrcode'];
            }else{
                $__SitesInfo['dingyue_qrcode'] = $__SitesInfo['dingyue_qrcode'];
            }
        }
        
    }else{
        $site_id = 1;
    }
}
if($site_id == 1){
    $cityInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_level1_name($tongchengConfig['city_name']);
    if($cityInfoTmp){
        $__CityInfo = $cityInfoTmp;
    }
}

## tchehuoren start
$__ShowTchehuoren = 0;
$tchehuorenConfig = array();
if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_tchehuoren/tom_tchehuoren.inc.php')){
    $tchehuorenConfig = $_G['cache']['plugin']['tom_tchehuoren'];
    if($tchehuorenConfig['open_tchehuoren'] == 1){
        $__ShowTchehuoren = 1;
    }
}
## tchehuoren end

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/login.php';

$ajaxLoadListUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=list&&formhash='.$formhash;
$ajaxCollectUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=collect&&formhash='.$formhash;
$ajaxClicksUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=clicks&&formhash='.$formhash.'&tongcheng_id=';
$searchUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=get_search_url';
$ajaxCommonClicksUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=commonClicks&&formhash='.$formhash;
$ajaxUpdateTopstatusUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=updateTopstatus&&formhash='.$formhash;
$ajaxUpdateToprandUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=updateToprand&&formhash='.$formhash;
$ajaxAutoClickUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=auto_click&&formhash='.$formhash;
$ajaxAutoZhuanfaUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=auto_zhuanfa&&formhash='.$formhash;
$ajaxShenheSmsUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=shenhe_sms&&formhash='.$formhash;
$ajaxZhuanfaScoreUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=zhuanfaScore&&formhash='.$formhash;
$ajaxLoadPopupUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=load_popup&&formhash='.$formhash;
$ajaxClosePopupUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=close_popup&&formhash='.$formhash;

$__CommonInfo = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_by_id(1);
if(!$__CommonInfo){
    $insertData = array();
    $insertData['id']      = 1;
    C::t('#tom_tongcheng#tom_tongcheng_common')->insert($insertData);
}
if($site_id > 1){
    $__siteCommonInfo = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_by_id($site_id);
    if(!$__siteCommonInfo){
        $insertData = array();
        $insertData['id']      = $site_id;
        C::t('#tom_tongcheng#tom_tongcheng_common')->insert($insertData);
    }
}

$browser_shouchang_show = 1;
$browser_uid = getcookie('tom_tongcheng_browser_shouchang_'.$__UserInfo['id']);
if($browser_uid || $__IsWeixin == 0){
    $browser_shouchang_show = 0;
}
if($tongchengConfig['open_shouchang'] == 0){
    $browser_shouchang_show = 0;
}

$ajaxShouchangUrl = "plugin.php?id=tom_tongcheng:ajax&act=browser_shouchang&formhash=".FORMHASH;

## tcshop start
$__ShowTcshop = 0;
if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_tcshop/tom_tcshop.inc.php')){
    $tcshopConfig = $_G['cache']['plugin']['tom_tcshop'];
    if($tongchengConfig['open_tcshop'] == 1){
        $__ShowTcshop = 1;
    }
}
## tcshop end
## tcqianggou start
$__ShowTcqianggou = 0;
if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_tcqianggou/tom_tcqianggou.inc.php')){
    if($tongchengConfig['open_tcqianggou'] == 1){
        $__ShowTcqianggou = 1;
    }
}
## tcqianggou end
## tchongbao start
$__ShowTchongbao = 0;
$tchongbaoConfig = array();
if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_tchongbao/tom_tchongbao.inc.php')){
    if($tongchengConfig['open_tchongbao'] == 1){
        $__ShowTchongbao = 1;
        $tchongbaoConfig = $_G['cache']['plugin']['tom_tchongbao'];
    }
}
## tchongbao end
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
## tcptuan start
$__ShowTcptuan = 0;
if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_tcptuan/tom_tcptuan.inc.php')){
    $tcptuanConfig = $_G['cache']['plugin']['tom_tcptuan'];
    if($tcptuanConfig['open_tcptuan'] == 1){
        $__ShowTcptuan = 1;
    }
}
## tcptuan end

$footer_nav_content_name = $footer_nav_content_link = '';
if($tongchengConfig['footer_nav_mod'] == 1){
    if(!empty($tongchengConfig['footer_nav_content'])){
        $footer_nav_content = explode("|", $tongchengConfig['footer_nav_content']);
        $footer_nav_content_name = $footer_nav_content[0];
        $footer_nav_content_link = $footer_nav_content[1];
    }
}

if($_GET['mod'] == 'index'){

    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/index.php';
    
}else if($_GET['mod'] == 'fabu'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/fabu.php';
    
}else if($_GET['mod'] == 'search'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/search.php';
    
}else if($_GET['mod'] == 'list'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/list.php';
    
}else if($_GET['mod'] == 'info'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/info.php';
    
}else if($_GET['mod'] == 'home'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/home.php';
    
}else if($_GET['mod'] == 'personal'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/personal.php';
    
}else if($_GET['mod'] == 'message'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/message.php';
    
}else if($_GET['mod'] == 'mycollect'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/mycollect.php';
    
}else if($_GET['mod'] == 'mylist'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/mylist.php';
    
}else if($_GET['mod'] == 'tousu'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/tousu.php';
    
}else if($_GET['mod'] == 'buy'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/buy.php';
    
}else if($_GET['mod'] == 'article'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/article.php';
    
}else if($_GET['mod'] == 'myorder'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/myorder.php';
    
}else if($_GET['mod'] == 'phone'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/phone.php';
    
}else if($_GET['mod'] == 'edit'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/edit.php';
    
}else if($_GET['mod'] == 'editcate'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/editcate.php';
    
}else if($_GET['mod'] == 'managerList'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/managerList.php';
    
}else if($_GET['mod'] == 'upload'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/upload.php';
    
}else if($_GET['mod'] == 'myedit'){

    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/myedit.php';
    
}else if($_GET['mod'] == 'templatesmsTest'){

    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/templatesmsTest.php';
    
}else if($_GET['mod'] == 'scorelog'){

    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/scorelog.php';
    
}else if($_GET['mod'] == 'money'){

    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/money.php';
    
}else if($_GET['mod'] == 'moneylog'){

    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/moneylog.php';
    
}else if($_GET['mod'] == 'moneytixian'){

    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/moneytixian.php';
    
}else if($_GET['mod'] == 'address'){

    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/address.php';
    
}else if($_GET['mod'] == 'majia'){

    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/majia.php';
    
}else if($_GET['mod'] == 'help'){

    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/help.php';
    
}else{
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/module/index.php';
}


