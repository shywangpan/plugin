<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act                = isset($_GET['act'])? addslashes($_GET['act']):"";
$buying             = isset($_GET['buying'])? intval($_GET['buying']):0;
$address_back_url   = isset($_GET['address_back_url'])? addslashes($_GET['address_back_url']):"";
$address_back_url_encode = urlencode($address_back_url);

$addressUrl = "plugin.php?id=tom_tongcheng&site=".$site_id."&mod=address&buying=".$buying."&address_back_url=".urlencode($address_back_url);

if($act == 'add'){
    
    $provinceList = C::t('#tom_ucenter#tom_ucenter_district')->fetch_all_by_upid(0);
    $aList = array();
    $i = 0;
    if(is_array($provinceList) && !empty($provinceList)){
        foreach ($provinceList as $key => $value){
            $aList[$i]['id'] = $value['id'];
            $aList[$i]['name'] = diconv($value['name'],CHARSET,'utf-8');
            $cityListTmp = C::t('#tom_ucenter#tom_ucenter_district')->fetch_all_by_upid($value['id']);
            $j = 0;
            if(is_array($cityListTmp) && !empty($cityListTmp)){
                foreach ($cityListTmp as $k1 => $v1){
                    $aList[$i]['sub'][$j]['id'] = $v1['id'];
                    $aList[$i]['sub'][$j]['name'] = diconv($v1['name'],CHARSET,'utf-8');
                    $areaListTmp = C::t('#tom_ucenter#tom_ucenter_district')->fetch_all_by_upid($v1['id']);
                    $z = 0;
                    if(is_array($areaListTmp) && !empty($areaListTmp)){
                        foreach ($areaListTmp as $k2 => $v2){
                            $aList[$i]['sub'][$j]['sub'][$z]['id'] = $v2['id'];
                            $aList[$i]['sub'][$j]['sub'][$z]['name'] = diconv($v2['name'],CHARSET,'utf-8');
                            $z++;
                        }
                    }
                    $j++;
                }
            }
            $i++;
        }
    }
    
    $aData = urlencode(json_encode($aList));
    
    $ajaxSaveUrl = "plugin.php?id=tom_tongcheng&site=".$site_id."&mod=address&act=addsave";
    
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_tongcheng:addressadd"); 
    
}else if($act == 'addsave' && $_GET['formhash'] == FORMHASH){
    
    $default    = isset($_GET['adddefault'])? intval($_GET['adddefault']):0;
    $xm         = isset($_GET['addxm'])? daddslashes(diconv(urldecode($_GET['addxm']),'utf-8')):'';
    $tel        = isset($_GET['addtel'])? daddslashes(diconv(urldecode($_GET['addtel']),'utf-8')):'';
    $province   = isset($_GET['province'])? intval($_GET['province']):0;
    $city       = isset($_GET['city'])? intval($_GET['city']):0;
    $area       = isset($_GET['area'])? intval($_GET['area']):0;
    $info       = isset($_GET['addinfo'])? daddslashes(diconv(urldecode($_GET['addinfo']),'utf-8')):'';
    
    if($default == 1){
        $addressListTmp = C::t('#tom_ucenter#tom_ucenter_address')->fetch_all_list(" AND uid={$__MemberInfo['uid']} AND default_id=1 ","ORDER BY id DESC",0,100);
        if(is_array($addressListTmp) && !empty($addressListTmp)){
            foreach ($addressListTmp as $key => $value){
                $updateData = array();
                $updateData['default_id']      = 0;
                C::t('#tom_ucenter#tom_ucenter_address')->update($value['id'],$updateData);
            }
        }
    }
    
    $addressListCount = C::t('#tom_ucenter#tom_ucenter_address')->fetch_all_count(" AND uid={$__MemberInfo['uid']} ");
    if($addressListCount == 0){
        $default = 1;
    }
    
    $provinceStr = "";
    $cityStr = "";
    $areaStr = "";
    $provinceInfo = C::t('#tom_ucenter#tom_ucenter_district')->fetch_by_id($province);
    $cityInfo = C::t('#tom_ucenter#tom_ucenter_district')->fetch_by_id($city);
    $areaInfo = C::t('#tom_ucenter#tom_ucenter_district')->fetch_by_id($area);
    if($provinceInfo){
        $provinceStr = $provinceInfo['name'];
    }
    if($cityInfo){
        $cityStr = $cityInfo['name'];
    }
    if($areaInfo){
        $areaStr = $areaInfo['name'];
    }
    
    $insertData = array();
    $insertData['uid']          = $__MemberInfo['uid'];
    $insertData['default_id']   = $default;
    $insertData['xm']           = $xm;
    $insertData['tel']          = $tel;
    $insertData['province_id']  = $province;
    $insertData['city_id']      = $city;
    $insertData['area_id']      = $area;
    $insertData['area_str']     = $provinceStr." ".$cityStr." ".$areaStr;
    $insertData['info']         = $info;
    if(C::t('#tom_ucenter#tom_ucenter_address')->insert($insertData)){
        echo 200;exit;
    }
    echo 400;exit;
}else if($act == 'edit'){
    
    $address_id = isset($_GET['address_id'])? intval($_GET['address_id']):0;
    
    $addressInfo = C::t('#tom_ucenter#tom_ucenter_address')->fetch_by_id($address_id);
    
    $provinceName = '';
    if(!empty($addressInfo['province_id'])){
        $provinceInfoTmp = C::t('#tom_ucenter#tom_ucenter_district')->fetch_by_id($addressInfo['province_id']);
        $provinceName = $provinceInfoTmp['name'];
    }
    $cityName = '';
    if(!empty($addressInfo['city_id'])){
        $cityInfoTmp = C::t('#tom_ucenter#tom_ucenter_district')->fetch_by_id($addressInfo['city_id']);
        $cityName = $cityInfoTmp['name'];
    }
    $areaName = '';
    if(!empty($addressInfo['area_id'])){
        $areaInfoTmp = C::t('#tom_ucenter#tom_ucenter_district')->fetch_by_id($addressInfo['area_id']);
        $areaName = $areaInfoTmp['name'];
    }

    
    $provinceList = C::t('#tom_ucenter#tom_ucenter_district')->fetch_all_by_upid(0);
    $aList = array();
    $i = 0;
    if(is_array($provinceList) && !empty($provinceList)){
        foreach ($provinceList as $key => $value){
            $aList[$i]['id'] = $value['id'];
            $aList[$i]['name'] = diconv($value['name'],CHARSET,'utf-8');
            $cityListTmp = C::t('#tom_ucenter#tom_ucenter_district')->fetch_all_by_upid($value['id']);
            $j = 0;
            if(is_array($cityListTmp) && !empty($cityListTmp)){
                foreach ($cityListTmp as $k1 => $v1){
                    $aList[$i]['sub'][$j]['id'] = $v1['id'];
                    $aList[$i]['sub'][$j]['name'] = diconv($v1['name'],CHARSET,'utf-8');
                    $areaListTmp = C::t('#tom_ucenter#tom_ucenter_district')->fetch_all_by_upid($v1['id']);
                    $z = 0;
                    if(is_array($areaListTmp) && !empty($areaListTmp)){
                        foreach ($areaListTmp as $k2 => $v2){
                            $aList[$i]['sub'][$j]['sub'][$z]['id'] = $v2['id'];
                            $aList[$i]['sub'][$j]['sub'][$z]['name'] = diconv($v2['name'],CHARSET,'utf-8');
                            $z++;
                        }
                    }
                    $j++;
                }
            }
            $i++;
        }
    }
    $aData = urlencode(json_encode($aList));
    
    $ajaxSaveUrl = "plugin.php?id=tom_tongcheng&site=".$site_id."&mod=address&act=editsave";
    $delUrl = "plugin.php?id=tom_tongcheng&site=".$site_id."&mod=address&act=del&formhash=".FORMHASH."&address_id=";
    
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_tongcheng:addressedit");  
    
}else if($act == 'editsave' && $_GET['formhash'] == FORMHASH){
    
    $address_id = isset($_GET['address_id'])? intval($_GET['address_id']):0;
    $default    = isset($_GET['adddefault'])? intval($_GET['adddefault']):0;
    $xm         = isset($_GET['addxm'])? daddslashes(diconv(urldecode($_GET['addxm']),'utf-8')):'';
    $tel        = isset($_GET['addtel'])? daddslashes(diconv(urldecode($_GET['addtel']),'utf-8')):'';
    $province   = isset($_GET['province'])? intval($_GET['province']):0;
    $city       = isset($_GET['city'])? intval($_GET['city']):0;
    $area       = isset($_GET['area'])? intval($_GET['area']):0;
    $info       = isset($_GET['addinfo'])? daddslashes(diconv(urldecode($_GET['addinfo']),'utf-8')):'';
    
    if($default == 1){
        $addressListTmp = C::t('#tom_ucenter#tom_ucenter_address')->fetch_all_list(" AND uid={$__MemberInfo['uid']} AND default_id=1 ","ORDER BY id DESC",0,100);
        if(is_array($addressListTmp) && !empty($addressListTmp)){
            foreach ($addressListTmp as $key => $value){
                $updateData = array();
                $updateData['default_id']      = 0;
                C::t('#tom_ucenter#tom_ucenter_address')->update($value['id'],$updateData);
            }
        }
    }
    
    $provinceStr = "";
    $cityStr = "";
    $areaStr = "";
    $provinceInfo = C::t('#tom_ucenter#tom_ucenter_district')->fetch_by_id($province);
    $cityInfo = C::t('#tom_ucenter#tom_ucenter_district')->fetch_by_id($city);
    $areaInfo = C::t('#tom_ucenter#tom_ucenter_district')->fetch_by_id($area);
    if($provinceInfo){
        $provinceStr = $provinceInfo['name'];
    }
    if($cityInfo){
        $cityStr = $cityInfo['name'];
    }
    if($areaInfo){
        $areaStr = $areaInfo['name'];
    }
    
    $updateData = array();
    $updateData['default_id']   = $default;
    $updateData['xm']           = $xm;
    $updateData['tel']          = $tel;
    $updateData['province_id']  = $province;
    $updateData['city_id']      = $city;
    $updateData['area_id']      = $area;
    $updateData['area_str']     = $provinceStr." ".$cityStr." ".$areaStr;
    $updateData['info']         = $info;
    if(C::t('#tom_ucenter#tom_ucenter_address')->update($address_id,$updateData)){
        echo 200;exit;
    }
    echo 400;exit;
}else if($act == 'del' && $_GET['formhash'] == FORMHASH){
    
   $address_id    = isset($_GET['address_id'])? intval($_GET['address_id']):0;
   C::t('#tom_ucenter#tom_ucenter_address')->delete_by_id($address_id);
   
   tomheader('location:'.$_G['siteurl'].$addressUrl);
   exit;
   
}else{
    
    $address_id   = isset($_GET['address_id'])? intval($_GET['address_id']):0;
    
    $addressList = C::t('#tom_ucenter#tom_ucenter_address')->fetch_all_list(" AND uid={$__MemberInfo['uid']} ","ORDER BY id DESC",0,100);
    
    $editUrl = "plugin.php?id=tom_tongcheng&site=".$site_id."&mod=address&act=edit&address_id=";
    
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_tongcheng:address");
}


