<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$__SitesInfo = array(
    'id'                => 1,
    'name'              => $tongchengConfig['plugin_name'],
    'lbs_name'          => $tongchengConfig['lbs_name'],
    'dingyue_qrcode'    => $tongchengConfig['dingyue_qrcode'],
);
$__CityInfo  = array('id'=>0,'name'=>'');

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
        if(!empty($__SitesInfo['share_pic'])){
            if(!preg_match('/^http/', $__SitesInfo['share_pic']) ){
                $__SitesInfo['share_pic'] = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$__SitesInfo['share_pic'];
            }
        }
        if(!empty($__SitesInfo['dingyue_qrcode'])){
            if(!preg_match('/^http/', $__SitesInfo['dingyue_qrcode']) ){
                $__SitesInfo['dingyue_qrcode'] = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$__SitesInfo['dingyue_qrcode'];
            }
        }
    }else{
        $site_id = 1;
    }
}

if($site_id == 1){
    $cityInfoTmp = array();
    if(!empty($tongchengConfig['city_id'])){
        $cityInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($tongchengConfig['city_id']);
    }else{
        $cityInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_level1_name($tongchengConfig['city_name']);
    }
    if(!empty($cityInfoTmp)){
        $__CityInfo = $cityInfoTmp;
    }
}

