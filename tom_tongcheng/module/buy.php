<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act  = isset($_GET['act'])? addslashes($_GET['act']):'top';

$tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
$tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);

$modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($tongchengInfo['model_id']);
$typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);

$sites_priceTmp = C::t('#tom_tongcheng#tom_tongcheng_sites_price')->fetch_all_list(" AND site_id={$tongchengInfo['site_id']} AND type_id={$tongchengInfo['type_id']} "," ORDER BY id DESC ",0,1);
if(is_array($sites_priceTmp) && !empty($sites_priceTmp) && $sites_priceTmp[0]['id']>0){
    $typeInfo['top_price'] = $sites_priceTmp[0]['top_price'];
}

$pay_price_arr = array();
for($i=1;$i<=30;$i++){
    $pay_price_arr[$i] = $typeInfo['top_price']*$i;
}

$toptime = dgmdate($tongchengInfo['toptime'],"Y-m-d",$tomSysOffset);

$topCount = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count(" AND id != {$tongcheng_id} AND model_id={$tongchengInfo['model_id']} AND topstatus=1 AND status=1 AND shenhe_status=1 ");
$limit_top_num = $tongchengConfig['limit_top_num'];
$min_top_num = $tongchengConfig['min_top_num'];
$day_num = 7;
$xianZhiShow = 0;
if($limit_top_num > 0 && $topCount >= $limit_top_num){
    $day_num = $min_top_num;
    $xianZhiShow = 1;
}

$payTopUrl = "plugin.php?id=tom_tongcheng:pay&site={$site_id}&act=top&formhash=".$formhash;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:buy");  




