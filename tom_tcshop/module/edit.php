<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$tcshop_id = intval($_GET['tcshop_id'])>0? intval($_GET['tcshop_id']):0;

$tcshopInfo = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($tcshop_id);

$notEditBusinessLicence = 0;
if($tcshopConfig['open_not_edit_business_licence'] == 1 && !empty($tcshopInfo['business_licence']) && $tcshopInfo['shenhe_status'] == 1){
    $notEditBusinessLicence = 1;
}

if($__UserInfo['id'] != $tcshopInfo['user_id']){
    dheader('location:'.$_G['siteurl']."plugin.php?id=tom_tcshop&site={$site_id}&mod=index");exit;
}

if($_GET['act'] == 'save' && $_GET['formhash'] == FORMHASH){
    
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
    
    $name               = isset($_GET['name'])? addslashes($_GET['name']):'';
    $picurl             = isset($_GET['picurl'])? addslashes($_GET['picurl']):'';
    $kefu_qrcode        = isset($_GET['kefu_qrcode'])? addslashes($_GET['kefu_qrcode']):'';
    $business_licence   = isset($_GET['business_licence'])? addslashes($_GET['business_licence']):'';
    $cate_id            = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
    $cate_child_id      = isset($_GET['cate_child_id'])? intval($_GET['cate_child_id']):0;
    $area_id            = isset($_GET['area_id'])? intval($_GET['area_id']):0;
    $street_id          = isset($_GET['street_id'])? intval($_GET['street_id']):0;
    $tabs               = isset($_GET['tabs'])? addslashes($_GET['tabs']):'';
    $business_hours     = isset($_GET['business_hours'])? addslashes($_GET['business_hours']):'';
    $tel                = isset($_GET['tel'])? addslashes($_GET['tel']):'';
    $shopkeeper_tel     = isset($_GET['shopkeeper_tel'])? addslashes($_GET['shopkeeper_tel']):'';
    $video_url          = isset($_GET['video_url'])? addslashes($_GET['video_url']):'';
    $zan_txt            = isset($_GET['zan_txt'])? addslashes($_GET['zan_txt']):'';
    $gonggao            = isset($_GET['gonggao'])? addslashes($_GET['gonggao']):'';
    
    $lng               = isset($_GET['lng'])? addslashes($_GET['lng']):'';
    $lat               = isset($_GET['lat'])? addslashes($_GET['lat']):'';
    $address           = isset($_GET['address'])? addslashes($_GET['address']):'';
    $type              = isset($_GET['type'])? addslashes($_GET['type']):'';
    
    $photoArr = $photoThumbArr = array();
    foreach($_GET as $key => $value){
        if(strpos($key, "photo_") !== false){
            $kk = intval(ltrim($key, "photo_"));
            $photoArr[$kk] = addslashes($value);
        }
        if(strpos($key, "photothumb_") !== false){
            $kk = intval(ltrim($key, "photothumb_"));
            $photoThumbArr[$kk] = addslashes($value);
        }
    }
    
    $updateData = array();
    $updateData['name']             = $name;
    $updateData['picurl']           = $picurl;
    $updateData['kefu_qrcode']      = $kefu_qrcode;
    if($notEditBusinessLicence == 0){
        $updateData['business_licence'] = $business_licence;
    }
    $updateData['cate_id']          = $cate_id;
    $updateData['cate_child_id']    = $cate_child_id;
    $updateData['area_id']          = $area_id;
    $updateData['street_id']        = $street_id;
    $updateData['tabs']             = $tabs;
    $updateData['business_hours']   = $business_hours;
    $updateData['tel']              = $tel;
    $updateData['shopkeeper_tel']   = $shopkeeper_tel;
    $updateData['latitude']         = $lat;
    $updateData['longitude']        = $lng;
    $updateData['address']          = $address;
    if($type == 'save'){
        $updateData['is_ok']            = 1;
        if($tcshopInfo['is_ok'] == 0){
            $updateData['status']       = 1;
        }
    }
    if($tcshopInfo['vip_level'] == 1){
        $updateData['video_url']        = $video_url;
        $updateData['zan_txt']          = $zan_txt;
        $updateData['gonggao']          = dhtmlspecialchars($gonggao);
    }
    if($tcshopInfo['lbs_status'] == 1){
        $updateData['lbs_status']       = 2;
    }
    if($tcshopInfo['shenhe_status'] == 3){
        $updateData['shenhe_status']    = 2;
    }
    $updateData['part1']            = TIMESTAMP;
    if(C::t('#tom_tcshop#tom_tcshop')->update($tcshop_id,$updateData)){
        
        C::t('#tom_tcshop#tom_tcshop_photo')->delete_by_tcshop_id($tcshop_id);
        
        if(is_array($photoArr) && !empty($photoArr)){
            foreach ($photoArr as $key => $value){
                $insertData = array();
                $insertData['tcshop_id'] = $tcshop_id;
                $insertData['picurl']    = $value;
                $insertData['thumb']     = $photoThumbArr[$key];
                $insertData['add_time']  = TIMESTAMP;
                C::t('#tom_tcshop#tom_tcshop_photo')->insert($insertData);
            }
        }

        if($tcshopInfo['shenhe_status'] == 2 && $tcshopInfo['is_ok'] == 0){
            $toUser = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengConfig['manage_user_id']);

            include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/templatesms.class.php';
            $access_token = $weixinClass->get_access_token();
            if($access_token && !empty($toUser['openid'])){
                $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_tcshop&site={$tcshopInfo['site_id']}&mod=index");

                $smsData = array(
                    'first'         => lang('plugin/tom_tcshop','shenhe_template_first'),
                    'keyword1'      => $tongchengConfig['plugin_name'],
                    'keyword2'      => dgmdate(TIMESTAMP,"Y-m-d H:i:s",$tomSysOffset),
                    'remark'        => ''
                );
                @$r = $templateSmsClass->sendSms01($toUser['openid'],$tongchengConfig['template_id'],$smsData);
            }
        }
        
        $outArr = array(
            'status'=> 200,
            'tcshop_id'=> $tcshopId,
        );
        echo json_encode($outArr); exit;
        
    }else{
        $outArr = array(
            'status'=> 404,
        );
        echo json_encode($outArr); exit;
    }
    
}

if($tcshopInfo['pay_status'] == 1){
    dheader('location:'.$_G['siteurl']."plugin.php?id=tom_tcshop&site={$site_id}&mod=mylist");exit;
}

$cateInfo = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_by_id($tcshopInfo['cate_id']);
$cateChildInfo = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_by_id($tcshopInfo['cate_child_id']);

if(!preg_match('/^http/', $tcshopInfo['picurl']) ){
    if(strpos($tcshopInfo['picurl'], 'source/plugin/tom_tcshop/') === FALSE){
        $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$tcshopInfo['picurl'];
    }else{
        $picurl = $tcshopInfo['picurl'];
    }
}else{
    $picurl = $tcshopInfo['picurl'];
}

if(!preg_match('/^http/', $tcshopInfo['kefu_qrcode']) ){
    if(strpos($tcshopInfo['kefu_qrcode'], 'source/plugin/tom_tcshop/') === FALSE){
        $kefu_qrcode = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$tcshopInfo['kefu_qrcode'];
    }else{
        $kefu_qrcode = $tcshopInfo['kefu_qrcode'];
    }
}else{
    $kefu_qrcode = $tcshopInfo['kefu_qrcode'];
}

if(!preg_match('/^http/', $tcshopInfo['business_licence']) ){
    if(strpos($tcshopInfo['business_licence'], 'source/plugin/tom_tcshop/') === FALSE){
        $business_licence = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$tcshopInfo['business_licence'];
    }else{
        $business_licence = $tcshopInfo['business_licence'];
    }
}else{
    $business_licence = $tcshopInfo['business_licence'];
}

$tcshopPhotoListTmp = C::t('#tom_tcshop#tom_tcshop_photo')->fetch_all_list(" AND tcshop_id={$tcshopInfo['id']} AND type_id = 1 "," ORDER BY id ASC ",0,50);
$tcshopPhotoList = array();
$photoCount = 0;
if(is_array($tcshopPhotoListTmp) && !empty($tcshopPhotoListTmp)){
    foreach ($tcshopPhotoListTmp as $kk => $vv){
        $photoCount++;
        if(!preg_match('/^http/', $vv['picurl']) ){
            if(strpos($vv['picurl'], 'source/plugin/tom_tcshop/data/') === false){
                $picurlTmp = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$vv['picurl'];
            }else{
                $picurlTmp = $vv['picurl'];
            }
        }else{
            $picurlTmp = $vv['picurl'];
        }
        $tcshopPhotoList[$kk]['picurl'] = $vv['picurl'];
        $tcshopPhotoList[$kk]['thumb'] = $vv['thumb'];
        $tcshopPhotoList[$kk]['src'] = $picurlTmp;
        $tcshopPhotoList[$kk]['li_i'] = $photoCount;

    }
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

$areaName = '';
if(!empty($tcshopInfo['area_id'])){
    $areaInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($tcshopInfo['area_id']);
    $areaName = $areaInfoTmp['name'];
}

$streetName = '';
if(!empty($tcshopInfo['street_id'])){
    $streetInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($tcshopInfo['street_id']);
    $streetName = $streetInfoTmp['name'];
}

$saveUrl = "plugin.php?id=tom_tcshop&site={$site_id}&mod=edit&act=save";
$uploadUrl1 = "plugin.php?id=tom_tcshop&site={$site_id}&mod=upload&act=picurl&formhash=".FORMHASH;
$uploadUrl2 = "plugin.php?id=tom_tcshop&site={$site_id}&mod=upload&act=photo&formhash=".FORMHASH;
$uploadUrl3 = "plugin.php?id=tom_tcshop&site={$site_id}&mod=upload&act=kefuqrcode&formhash=".FORMHASH;
$uploadUrl4 = "plugin.php?id=tom_tcshop&site={$site_id}&mod=upload&act=businesslicence&formhash=".FORMHASH;
$wxUploadUrl2 = "plugin.php?id=tom_tcshop:wxMediaDowmload&site={$site_id}&act=photo&formhash=".FORMHASH;
$ossBatchUrl = 'plugin.php?id=tom_tcshop:ossBatch';
$qiniuBatchUrl = 'plugin.php?id=tom_tcshop:qiniuBatch';

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tcshop:edit");




