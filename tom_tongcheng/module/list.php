<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/sitesids.php';

$model_id   = intval($_GET['model_id'])>0? intval($_GET['model_id']):0;
$type_id    = intval($_GET['type_id'])>0? intval($_GET['type_id']):0;
$cate_id    = intval($_GET['cate_id'])>0? intval($_GET['cate_id']):0;
$city_id    = intval($_GET['city_id'])>0? intval($_GET['city_id']):0;
$area_id    = intval($_GET['area_id'])>0? intval($_GET['area_id']):0;
$street_id  = intval($_GET['street_id'])>0? intval($_GET['street_id']):0;
$keyword    = isset($_GET['keyword'])? addslashes(urldecode($_GET['keyword'])):'';
$ordertype  = !empty($_GET['ordertype'])? addslashes($_GET['ordertype']):'new';
$sfcChufa   = isset($_GET['chufa'])? addslashes(urldecode($_GET['chufa'])):'';
$sfcMude    = isset($_GET['mude'])? addslashes(urldecode($_GET['mude'])):'';

$cateInfo = array();
if(!empty($cate_id)){
    $cateInfo = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_by_id($cate_id);
    $model_id = $cateInfo['model_id'];
    $type_id = $cateInfo['type_id'];
}

$typeInfo = array();
if(!empty($type_id)){
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($type_id);
    $model_id = $typeInfo['model_id'];
}

$modelInfo = array();
if(!empty($model_id)){
    $modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($model_id);
}

$areaInfo = array();
if(!empty($area_id)){
    $areaInfo = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($area_id);
}

$streetInfo = array();
if(!empty($street_id)){
    $streetInfo = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($street_id);
}

$modelWhereStr = " AND is_show=1 ";
if($site_id > 1){
    if(!empty($__SitesInfo['model_ids'])){
        $modelWhereStr.= " AND id IN({$__SitesInfo['model_ids']}) ";
    }
}else{
    $modelWhereStr.= " AND sites_show=0 ";
}
$modelList = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" {$modelWhereStr} "," ORDER BY paixu ASC,id DESC ",0,100);

$typeList = array();
$typeListCount = 0;
if(!empty($model_id)){
    $typeList = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_all_list(" AND model_id={$model_id} "," ORDER BY paixu ASC,id DESC ",0,100);
    $typeListCount = count($typeList);
}
$showTypeList = 0;
if($typeListCount > 1 && $typeListCount <= 4){
    $showTypeList = 1;
}else if($typeListCount > 4){ 
    $showTypeList = 2;
}

$areaListTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($__CityInfo['id']);
$areaList = array();
if(is_array($areaListTmp) && !empty($areaListTmp)){
    $areaList = $areaListTmp;
}


## count Start
$whereStr = ' AND status=1 AND shenhe_status=1 ';
if(!empty($sql_in_site_ids)){
    $whereStr.= " AND site_id IN({$sql_in_site_ids}) ";
}
if(!empty($model_id)){
    $whereStr.= " AND model_id={$model_id} ";
}
if(!empty($type_id)){
    $whereStr.= " AND type_id={$type_id} ";
}
if(!empty($cate_id)){
    $whereStr.= " AND cate_id={$cate_id} ";
}

if(!empty($city_id)){
    $whereStr.= " AND city_id={$city_id} ";
}
if(!empty($area_id)){
    $whereStr.= " AND area_id={$area_id} ";
}
if(!empty($street_id)){
    $whereStr.= " AND street_id={$street_id} ";
}
$tongchengCount = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count($whereStr,$keyword);
## count END

if(!empty($type_id)){
    $fabuUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=fabu&act=step2&type_id={$type_id}";
}else if(!empty($model_id)){
    $fabuUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=fabu&model_id=".$model_id;
}else{
    $fabuUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=fabu";
}

$ajaxLoadListUrl = "plugin.php?id=tom_tongcheng:ajax&site={$site_id}&act=list&&formhash=".$formhash;
$ajaxGetStreetUrl = "plugin.php?id=tom_tongcheng:ajax&site={$site_id}&act=list_get_street&&formhash=".$formhash;
$ajaxGetCateUrl = "plugin.php?id=tom_tongcheng:ajax&site={$site_id}&act=list_get_cate&&formhash=".$formhash;

$title = "";
if($modelInfo){
    $title = $modelInfo['name'];
}else{
    $title = urldecode($keyword);
}

$showSfcTxtStatus = 0;
if(!empty($__CommonInfo['sfc_txt'])){
    $showSfcTxtStatus = 1;
    $sfcCutTxt = strip_tags(cutstr(stripslashes($__CommonInfo['sfc_txt']), 100 ,'...'));
    $sfcTxt = stripslashes($__CommonInfo['sfc_txt']);
}

$md5HostUrl = md5($_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=list&model_id={$model_id}&type_id={$type_id}&cate_id={$cate_id}");

$shareUrl   = $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=list&model_id={$model_id}&type_id={$type_id}&cate_id={$cate_id}";
$sfcSearchUrl = "plugin.php?id=tom_tongcheng:ajax&site={$site_id}&act=get_sfc_search_url&model_id={$model_id}&type_id={$type_id}&cate_id={$cate_id}&city_id={$city_id}&area_id={$area_id}&street_id={$street_id}";

$ajaxSfcCacheUrl = "plugin.php?id=tom_tongcheng:ajax&site={$site_id}&act=sfc_cache&&formhash=".$formhash;

if($__ShowTchehuoren == 1){
    $tchehuorenInfoTmp = C::t('#tom_tchehuoren#tom_tchehuoren')->fetch_by_user_id($__UserInfo['id']);
    if($tchehuorenInfoTmp && $tchehuorenInfoTmp['status'] == 1){
        $shareUrl   = $shareUrl."&tj_hehuoren_id={$tchehuorenInfoTmp['id']}";
    }
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:list");  




