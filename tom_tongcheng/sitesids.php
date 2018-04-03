<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql_in_site_ids = $site_id;

if($site_id > 1){
    if(is_array($__SitesInfo) && !empty($__SitesInfo)){
        $sitesInfoTmp = $__SitesInfo;
    }else{
        $sitesInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($site_id);
    }
    if($sitesInfoTmp){
        if(!empty($sitesInfoTmp['site_ids'])){
            $sql_in_site_ids = $sitesInfoTmp['site_ids'].','.$site_id;
        }
    }else{
        $site_id = 1;
    }
}

if($site_id == 1){
    $siteOneArr = array();
    $siteOneListTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(" AND site_one=1 AND status=1 "," ORDER BY id DESC ",0,1000);
    if(is_array($siteOneListTmp) && !empty($siteOneListTmp)){
        foreach ($siteOneListTmp as $key => $value){
            $siteOneArr[] = $value['id'];
        }
    }
    if(is_array($siteOneArr) && !empty($siteOneArr)){
        $siteOneStr = implode(',', $siteOneArr);
        $sql_in_site_ids = $siteOneStr.',1';
    }
}