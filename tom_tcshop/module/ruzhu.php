<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$cateArr = array();
$cateList = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_all_list(" AND parent_id=0 "," ORDER BY csort ASC,id DESC ",0,100);
$i = 0;
if(is_array($cateList) && !empty($cateList)){
    foreach ($cateList as $key => $value){
        $cateArr[$i]['id'] = $value['id'];
        $cateArr[$i]['name'] = diconv($value['name'],CHARSET,'utf-8');
        $childCateList = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_all_list(" AND parent_id={$value['id']} "," ORDER BY csort ASC,id DESC ",0,100);
        $j = 0;
        if(is_array($childCateList) && !empty($childCateList)){
            foreach ($childCateList as $kk => $vv){
                $cateArr[$i]['sub'][$j]['id'] = $vv['id'];
                $cateArr[$i]['sub'][$j]['name'] = diconv($vv['name'],CHARSET,'utf-8');
                $j++;
            }
        }
        $i++;
    }
}
$cateData = urlencode(json_encode($cateArr));

$areaList = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($__CityInfo['id']);
$cityList = array();
$i = 0;
if(is_array($areaList) && !empty($areaList)){
    foreach ($areaList as $key => $value){
        $cityList[$i]['id'] = $value['id'];
        $cityList[$i]['name'] = diconv($value['name'],CHARSET,'utf-8');
        $streetListTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($value['id']);
        $j = 0;
        if(is_array($streetListTmp) && !empty($streetListTmp)){
            foreach ($streetListTmp as $kk => $vv){
                $cityList[$i]['sub'][$j]['id'] = $vv['id'];
                $cityList[$i]['sub'][$j]['name'] = diconv($vv['name'],CHARSET,'utf-8');
                $j++;
            }
        }
        $i++;
    }
}
$cityData = urlencode(json_encode($cityList));

$commonInfo = C::t('#tom_tcshop#tom_tcshop_common')->fetch_by_id(1);
if(!$commonInfo){
    $insertData = array();
    $insertData['id']      = 1;
    C::t('#tom_tcshop#tom_tcshop_common')->insert($insertData);
}
$xieyi_txt = stripslashes($commonInfo['xieyi_txt']);
$vip_txt = stripslashes($commonInfo['vip_txt']);

if(!empty($tcshopConfig['score_yuan'])){
    $score_yuan = $tcshopConfig['score_yuan'];
}else{
    $score_yuan = $tongchengConfig['score_yuan'];
}
$ruzhuBackScore = $tcshopConfig['ruzhu_price'] * $score_yuan;
$ruzhuVipBackScore = $tcshopConfig['vip_ruzhu_price'] * $score_yuan;
$ruzhuVipTwoBackScore = $tcshopConfig['vip_ruzhu_price_two'] * $score_yuan;

$ruzhuDiscountPrice = $vipRuzhuDiscountPrice = $vipRuzhuDiscountPriceTwo = 0;
$showDiscountPrice = 0;
$showInviteCodeInput = 0;
if($__ShowTchehuoren == 1){
    $tcshop_ruzhu_discount_arr = array(2=>0.95,3=>0.9,4=>0.85,5=>0.8,6=>0.75,7=>0.7);
    $tcshop_ruzhu_discount = 0;
    if(isset($tcshop_ruzhu_discount_arr[$tchehuorenConfig['tcshop_ruzhu_discount']])){
        $showDiscountPrice = 1;
        $tcshop_ruzhu_discount      = $tcshop_ruzhu_discount_arr[$tchehuorenConfig['tcshop_ruzhu_discount']];
        $ruzhuDiscountPrice         = number_format($tcshopConfig['ruzhu_price']*$tcshop_ruzhu_discount,2);
        $vipRuzhuDiscountPrice      = number_format($tcshopConfig['vip_ruzhu_price']*$tcshop_ruzhu_discount,2);
        $vipRuzhuDiscountPriceTwo   = number_format($tcshopConfig['vip_ruzhu_price_two']*$tcshop_ruzhu_discount,2);
        $tcshop_ruzhu_discount_msg = $tcshop_ruzhu_discount*10;
    }
    
    if($site_id > 1){
        if($__SitesInfo['hehuoren_fc_open'] == 1){
            $showInviteCodeInput = 1;
        }
    }else if($site_id == 1){
        $showInviteCodeInput = 1;
    }
    if($tchehuorenConfig['tcshop_type'] == 2){
        $showInviteCodeInput = 0;
    }
}

$showPromptBox = 0;
$tcshopListTmp = C::t('#tom_tcshop#tom_tcshop')->fetch_all_list(" AND user_id={$__UserInfo['id']} AND (pay_status = 2 || pay_status = 0) AND is_ok = 0 ", " ORDER BY id DESC ", 0, 1);
if(is_array($tcshopListTmp) && !empty($tcshopListTmp[0])){
    $showPromptBox = 1;
}

$subscribeFlag = 0;
$open_subscribe = 0;
$access_token = $weixinClass->get_access_token();
if($tongchengConfig['open_subscribe']==1 && $tongchengConfig['open_child_subscribe_sites']==1){
    $open_subscribe = 1;
}else if($tongchengConfig['open_subscribe']==1 && $site_id==1){
    $open_subscribe = 1;
}
if($open_subscribe==1 && !empty($__UserInfo['openid']) && !empty($access_token)){
    $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$__UserInfo['openid']}&lang=zh_CN";
    $return = get_html($get_user_info_url);
    if(!empty($return)){
        $tcContent = json_decode($return,true);
        if(is_array($tcContent) && !empty($tcContent) && isset($tcContent['subscribe'])){
            if($tcContent['subscribe'] == 1){
                $subscribeFlag = 1;
            }else{
                $subscribeFlag = 2;
            }
        }
    }
}

$showGuanzuBox = 0;
$tcshopListTmp = C::t('#tom_tcshop#tom_tcshop')->fetch_all_list(" AND user_id={$__UserInfo['id']} AND shenhe_status = 2 AND (pay_status = 2 || pay_status = 0)", " ORDER BY id DESC ", 0, 1);
if(is_array($tcshopListTmp) && !empty($tcshopListTmp[0]) && $subscribeFlag == 2){
    $showPromptBox = 0;
    $subscribeFlag = 0;
    $showGuanzuBox = 1;
}

$showRuzhuXzBox = 0;
$tcshopListTmp = C::t('#tom_tcshop#tom_tcshop')->fetch_all_list(" AND user_id={$__UserInfo['id']} ", " ORDER BY id DESC ", 0, 1);
if(is_array($tcshopListTmp) && !empty($tcshopListTmp[0]) && $tcshopConfig['open_ruzhu_xz'] == 1){
    $showRuzhuXzBox = 1;
    $showPromptBox = 0;
    $subscribeFlag = 0;
    $showGuanzuBox = 0;
}

$shareUrl = $_G['siteurl']."plugin.php?id=tom_tcshop&site={$site_id}&mod=ruzhu";

$tchehuorenAjaxUrl = "plugin.php?id=tom_tchehuoren:ajax&site={$site_id}&formhash=".FORMHASH;
$payUrl = "plugin.php?id=tom_tcshop:pay&site={$site_id}";
$backLinkUrl = "plugin.php?id=tom_tcshop&site={$site_id}&mod=edit&fromlist=mylist&tcshop_id=";
$myListUrl = "plugin.php?id=tom_tcshop&site={$site_id}&mod=mylist&type=2";
$uploadUrl1 = "plugin.php?id=tom_tcshop&site={$site_id}&mod=upload&act=picurl&formhash=".FORMHASH;
$uploadUrl2 = "plugin.php?id=tom_tcshop&site={$site_id}&mod=upload&act=photo&formhash=".FORMHASH;
$uploadUrl3 = "plugin.php?id=tom_tcshop&site={$site_id}&mod=upload&act=kefuqrcode&formhash=".FORMHASH;
$uploadUrl4 = "plugin.php?id=tom_tcshop&site={$site_id}&mod=upload&act=businesslicence&formhash=".FORMHASH;
$wxUploadUrl2 = "plugin.php?id=tom_tcshop:wxMediaDowmload&site={$site_id}&act=photo&formhash=".FORMHASH;
$ossBatchUrl = 'plugin.php?id=tom_tcshop:ossBatch';
$qiniuBatchUrl = 'plugin.php?id=tom_tcshop:qiniuBatch';

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tcshop:ruzhu");




