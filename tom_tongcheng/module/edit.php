<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$tongcheng_id = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;

$tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
$modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($tongchengInfo['model_id']);
$typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);

# check start
if($tongchengInfo['user_id'] != $__UserInfo['id']){
    if($__UserInfo['groupid'] == 1){
    }else if($__UserInfo['groupid'] == 2){
        if($tongchengInfo['site_id'] == $__UserInfo['groupsiteid']){
        }else{
            tomheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=index");exit;
        }
    }else{
        tomheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=index");exit;
    }
}
# check end

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
    
    $tcshop_id  = isset($_GET['tcshop_id'])? intval($_GET['tcshop_id']):0;
    $cate_id    = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
    $area_id    = isset($_GET['area_id'])? intval($_GET['area_id']):0;
    $street_id  = isset($_GET['street_id'])? intval($_GET['street_id']):0;
    $title      = isset($_GET['title'])? addslashes($_GET['title']):'';
    $xm         = isset($_GET['xm'])? addslashes($_GET['xm']):'';
    $tel        = isset($_GET['tel'])? addslashes($_GET['tel']):'';
    $content    = isset($_GET['content'])? addslashes($_GET['content']):'';

    $__CommonInfo = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_by_id(1);
    if(!empty($__CommonInfo['forbid_word'])){
        $forbid_word = preg_quote(trim($__CommonInfo['forbid_word']), '/');
        $forbid_word = str_replace(array("\\*"), array('.*'), $forbid_word);
        $forbid_word = '.*('.$forbid_word.').*';
        $forbid_word = '/^('.str_replace(array("\r\n", ' '), array(').*|.*(', ''), $forbid_word).')$/i';
        if(@preg_match($forbid_word, $content,$matches)) {
            $i = count($matches)-1;
            $word = '';
            if(isset($matches[$i]) && !empty($matches[$i])){
                $word = diconv($matches[$i],CHARSET,'utf-8');
            }
            $outArr = array(
                'status'=> 505,
                'word'=> $word,
            );
            echo json_encode($outArr); exit;
        }
                
    }
    
    $attrnameArr = $attrArr = $attrdateArr = $tagnameArr = $photoArr = array();
    foreach($_GET as $key => $value){
        if(strpos($key, "attrname_") !== false){
            $attr_id = intval(ltrim($key, 'attrname_'));
            $attrnameArr[$attr_id] = addslashes($value);
        }
        if(strpos($key, "attrpaixu_") !== false){
            $attr_id = intval(ltrim($key, 'attrpaixu_'));
            $attrpaixuArr[$attr_id] = addslashes($value);
        }
        if(strpos($key, "attrunit_") !== false){
            $attr_id = intval(ltrim($key, 'attrunit_'));
            $attrunitArr[$attr_id] = addslashes($value);
        }
        if(strpos($key, "attr_") !== false){
            $attr_id = intval(ltrim($key, 'attr_'));
            if(is_array($value)){
                $valueTmp = implode(" ", $value);
                $attrArr[$attr_id] = addslashes($valueTmp);
            }else{
                $attrArr[$attr_id] = addslashes($value);
            }
        }
        if(strpos($key, "attrdate_") !== false){
            $attr_id = intval(ltrim($key, 'attrdate_'));
            $value = str_replace("T", " ", $value);
            $attrdateArr[$attr_id] = addslashes($value);
        }
        if(strpos($key, "tagname_") !== false){
            $tag_id = intval(ltrim($key, 'tagname_'));
            $tagnameArr[$tag_id] = addslashes($value);
        }
        if(strpos($key, "photo_") !== false){
            $photoArr[] = addslashes($value);
        }
    }
    
    $tagArr = array();
    if(isset($_GET['tag']) && is_array($_GET['tag'])){
        foreach ($_GET['tag'] as $key => $value){
            $tagArr[] = intval($value);
        }
    }
    
    $search_content = '';
    if(is_array($attrArr) && !empty($attrArr)){
        foreach ($attrArr as $key => $value){
            $search_content.=''.$attrnameArr[$key].$value.'';
        }
    }
    if(is_array($tagArr) && !empty($tagArr)){
        foreach ($tagArr as $key => $value){
            $search_content.=''.$tagnameArr[$value].'';
        }
    }
    $updateData = array();
    if($__UserInfo['id'] == $tongchengInfo['user_id']){
        $updateData['tcshop_id']      = $tcshop_id;
    }
    $updateData['cate_id']      = $cate_id;
    $updateData['area_id']      = $area_id;
    $updateData['street_id']    = $street_id;
    $updateData['title']        = $title;
    $updateData['xm']           = $xm;
    $updateData['tel']          = $tel;
    $updateData['content']      = $content.'|+|+|+|+|+|+|+|+|+|'.$search_content.'-'.$xm.'-'.$tel.'-'.  mt_rand(111111, 666666);
    if($modelInfo['must_shenhe'] == 1){
        if($__UserInfo['groupid'] == 1 || $__UserInfo['groupid'] == 2 ){
            $updateData['shenhe_status']       = 1;
        }else{
            $updateData['shenhe_status']       = 2;
        }
    }else{
        $updateData['shenhe_status']       = 1;
    }
    $updateData['part1']          = TIMESTAMP;
    if(C::t('#tom_tongcheng#tom_tongcheng')->update($tongcheng_id,$updateData)){
        
        C::t('#tom_tongcheng#tom_tongcheng_attr')->delete_by_tongcheng_id($tongcheng_id);
        C::t('#tom_tongcheng#tom_tongcheng_photo')->delete_by_tongcheng_id($tongcheng_id);
        C::t('#tom_tongcheng#tom_tongcheng_tag')->delete_by_tongcheng_id($tongcheng_id);
        
        if(is_array($attrArr) && !empty($attrArr)){
            foreach ($attrArr as $key => $value){
                $insertData = array();
                $insertData['model_id']     = $modelInfo['id'];
                $insertData['type_id']      = $typeInfo['id'];
                $insertData['tongcheng_id'] = $tongcheng_id;
                $insertData['attr_id']      = $key;
                $insertData['attr_name']    = $attrnameArr[$key];
                $insertData['value']        = $value;
                $insertData['unit']         = $attrunitArr[$key];
                $insertData['paixu']        = $attrpaixuArr[$key];
                $insertData['add_time']     = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_attr')->insert($insertData);
            }
        }
        
        if(is_array($attrdateArr) && !empty($attrdateArr)){
            foreach ($attrdateArr as $key => $value){
                $insertData = array();
                $insertData['model_id']     = $modelInfo['id'];
                $insertData['type_id']      = $typeInfo['id'];
                $insertData['tongcheng_id'] = $tongcheng_id;
                $insertData['attr_id']      = $key;
                $insertData['attr_name']    = $attrnameArr[$key];
                $insertData['value']        = $value;
                $insertData['time_value']   = strtotime($value);
                $insertData['unit']         = $attrunitArr[$key];
                $insertData['paixu']        = $attrpaixuArr[$key];
                $insertData['add_time']     = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_attr')->insert($insertData);
            }
        }
        
        if(is_array($tagArr) && !empty($tagArr)){
            foreach ($tagArr as $key => $value){
                $insertData = array();
                $insertData['model_id']     = $modelInfo['id'];
                $insertData['type_id']      = $typeInfo['id'];
                $insertData['tongcheng_id'] = $tongcheng_id;
                $insertData['tag_id']       = $value;
                $insertData['tag_name']     = $tagnameArr[$value];
                $insertData['add_time']     = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_tag')->insert($insertData);
            }
        }
        
        if(is_array($photoArr) && !empty($photoArr)){
            foreach ($photoArr as $key => $value){
                $insertData = array();
                $insertData['tongcheng_id'] = $tongcheng_id;
                $insertData['picurl'] = $value;
                $insertData['add_time']     = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_photo')->insert($insertData);
            }
        }
        
        if($modelInfo['is_sfc'] == 1){
            $sfcChufaAttrInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND attr_id = {$typeInfo['sfc_chufa_attr_id']} AND tongcheng_id = {$tongcheng_id}", 'ORDER BY id DESC', 0, 1);
            $sfcMudeAttrInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND attr_id = {$typeInfo['sfc_mude_attr_id']} AND tongcheng_id = {$tongcheng_id}", 'ORDER BY id DESC', 0, 1);
            $sfcTimeAttrInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND attr_id = {$typeInfo['sfc_time_attr_id']} AND tongcheng_id = {$tongcheng_id}", 'ORDER BY id DESC', 0, 1);
            $sfcRenshuAttrInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND attr_id = {$typeInfo['sfc_renshu_attr_id']} AND tongcheng_id = {$tongcheng_id}", 'ORDER BY id DESC', 0, 1);

            if(is_array($sfcChufaAttrInfoTmp) && !empty($sfcChufaAttrInfoTmp[0]) && is_array($sfcMudeAttrInfoTmp) && !empty($sfcMudeAttrInfoTmp[0])){
                $sfcCacheInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sfc_cache')->fetch_all_list(" AND tongcheng_id = {$tongcheng_id} ", 'ORDER BY id DESC', 0, 1);
                if(is_array($sfcCacheInfoTmp) && !empty($sfcCacheInfoTmp[0])){
                    $updateData = array();
                    $updateData['model_id']     = $modelInfo['id'];
                    $updateData['type_id']      = $typeInfo['id'];
                    $updateData['chufa']        = $sfcChufaAttrInfoTmp[0]['value'];
                    $updateData['mude']         = $sfcMudeAttrInfoTmp[0]['value'];
                    $updateData['chufa_time']   = $sfcTimeAttrInfoTmp[0]['value'];
                    $updateData['chufa_int_time']  = $sfcTimeAttrInfoTmp[0]['time_value'];
                    $updateData['renshu']       = $sfcRenshuAttrInfoTmp[0]['value'];
                    $updateData['add_time']     = TIMESTAMP;
                    C::t('#tom_tongcheng#tom_tongcheng_sfc_cache')->update($sfcCacheInfoTmp[0]['id'], $updateData);
                }
            }
        }
        
        $outArr = array(
            'status'=> 200,
        );
        echo json_encode($outArr); exit;
        
    }else{
        $outArr = array(
            'status'=> 404,
        );
        echo json_encode($outArr); exit;
    }
    
}else{
    
    $tongchengAttrListTmp = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND tongcheng_id={$tongchengInfo['id']} "," ORDER BY id DESC ",0,50);
    $tongchengAttrList = array();
    if(is_array($tongchengAttrListTmp) && !empty($tongchengAttrListTmp)){
        foreach ($tongchengAttrListTmp as $kk => $vv){
            if($vv['time_value'] > 0){
                $vv['value'] = str_replace(" ", "T", $vv['value']);
                $tongchengAttrList[$vv['attr_id']] = $vv['value'];
            }else{
                $tongchengAttrList[$vv['attr_id']] = $vv['value'];
            }
        }
    }
    
    $cateList = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_all_list(" AND type_id={$tongchengInfo['type_id']}  "," ORDER BY paixu ASC,id DESC ",0,50);
    $tagList = C::t('#tom_tongcheng#tom_tongcheng_model_tag')->fetch_all_list(" AND type_id={$tongchengInfo['type_id']} "," ORDER BY paixu ASC,id DESC ",0,50);
    $attrListTmp = C::t('#tom_tongcheng#tom_tongcheng_model_attr')->fetch_all_list(" AND type_id={$tongchengInfo['type_id']} "," ORDER BY paixu ASC,id DESC ",0,50);
    $attrList = array();
    if(is_array($attrListTmp) && !empty($attrListTmp)){
        foreach ($attrListTmp as $key => $value){
            $attrList[$key] = $value;
            if($value['type'] == 2){
                $value_listStr = str_replace("\r\n","{n}",$value['value']); 
                $value_listStr = str_replace("\n","{n}",$value_listStr);
                $attrList[$key]['list'] = explode("{n}", $value_listStr);
            }
            if($value['type'] == 4){
                $value_listStr = str_replace("\r\n","{n}",$value['value']); 
                $value_listStr = str_replace("\n","{n}",$value_listStr);
                $value_listArrTmp = explode("{n}", $value_listStr);
                $value_listArr = array();
                if(is_array($value_listArrTmp) && !empty($value_listArrTmp)){
                    foreach ($value_listArrTmp as $kk => $vv){
                        $value_listArr[$kk]['value'] = $vv;
                        $checkArrTmp = explode(" ", $tongchengAttrList[$value['id']]);
                        if(in_array($vv, $checkArrTmp)){
                            $value_listArr[$kk]['check'] = 1;
                        }
                    }
                }
                $attrList[$key]['list'] = $value_listArr;
            }
        }
    }
    
    $tongchengTagListTmp = C::t('#tom_tongcheng#tom_tongcheng_tag')->fetch_all_list(" AND tongcheng_id={$tongchengInfo['id']} "," ORDER BY id DESC ",0,50);
    $tongchengTagList = array();
    if(is_array($tongchengTagListTmp) && !empty($tongchengTagListTmp)){
        foreach ($tongchengTagListTmp as $kk => $vv){
            $tongchengTagList[$vv['tag_id']] = 1;
        }
    }
    
    $tongchengPhotoListTmp = C::t('#tom_tongcheng#tom_tongcheng_photo')->fetch_all_list(" AND tongcheng_id={$tongchengInfo['id']} "," ORDER BY id ASC ",0,50);
    $tongchengPhotoList = array();
    $photoCount = 0;
    if(is_array($tongchengPhotoListTmp) && !empty($tongchengPhotoListTmp)){
        foreach ($tongchengPhotoListTmp as $kk => $vv){
            $photoCount++;
            if(!preg_match('/^http/', $vv['picurl']) ){
                if(strpos($vv['picurl'], 'source/plugin/tom_tongcheng/data/') === false){
                    $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$vv['picurl'];
                }else{
                    $picurl = $vv['picurl'];
                }
            }else{
                $picurl = $vv['picurl'];
            }
            $tongchengPhotoList[$kk]['picurl'] = $vv['picurl'];
            $tongchengPhotoList[$kk]['src'] = $picurl;
            $tongchengPhotoList[$kk]['li_i'] = $photoCount;
            
        }
    }
    
    $content = contentFormat($tongchengInfo['content']);
    
    $minDateTime = TIMESTAMP-60;
    $minDateTime = dgmdate($minDateTime,"Y-m-d H:i:s",$tomSysOffset);
    $maxDateTime = TIMESTAMP + 365*86400;
    $maxDateTime = dgmdate($maxDateTime,"Y-m-d H:i:s",$tomSysOffset);
    
    $areaList = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($tongchengInfo['city_id']);
    $cityList = array();
    $chooseI = $chooseJ = 0;
    $i = 0;
    if(is_array($areaList) && !empty($areaList)){
        foreach ($areaList as $key => $value){
            $cityList[$i]['id'] = $value['id'];
            $cityList[$i]['name'] = diconv($value['name'],CHARSET,'utf-8');
            if($tongchengInfo['area_id'] == $value['id']){
                $chooseI = $i;
            }
            $streetListTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($value['id']);
            $j = 0;
            if(is_array($streetListTmp) && !empty($streetListTmp)){
                foreach ($streetListTmp as $kk => $vv){
                    $cityList[$i]['sub'][$j]['id'] = $vv['id'];
                    $cityList[$i]['sub'][$j]['name'] = diconv($vv['name'],CHARSET,'utf-8');
                    if($tongchengInfo['street_id'] == $vv['id']){
                        $chooseJ = $j;
                    }
                    $j++;
                }
            }
            $i++;
        }
    }
    $cityData = urlencode(json_encode($cityList));
    
    $areaName = '';
    if(!empty($tongchengInfo['area_id'])){
        $areaInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($tongchengInfo['area_id']);
        $areaName = $areaInfoTmp['name'];
    }

    $streetName = '';
    if(!empty($tongchengInfo['street_id'])){
        $streetInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($tongchengInfo['street_id']);
        $streetName = $streetInfoTmp['name'];
    }
    
    
    $picYasuSize = 640;
    if($tongchengConfig['pic_yasu_size'] > 100){
        $picYasuSize = $tongchengConfig['pic_yasu_size'];
    }
    
    if($__ShowTcshop == 1){
        $tcshopListTmp = C::t('#tom_tcshop#tom_tcshop')->fetch_all_list(" AND status=1 AND shenhe_status=1 AND vip_level=1 AND user_id={$__UserInfo['id']} "," ORDER BY id DESC ",0,100);
        $tcshopList = array();
        if(is_array($tcshopListTmp) && !empty($tcshopListTmp)){
            foreach ($tcshopListTmp as $key => $value){
                $tcshopList[$key] = $value;
            }
        }
    }
    
    $is_weixin = 0;
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
        $is_weixin = 1;
    }
    
    $saveUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=edit&act=save";
    $uploadUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=upload&act=photo&formhash=".FORMHASH;
    $wxUploadUrl = "plugin.php?id=tom_tongcheng:wxMediaDowmload&site={$site_id}&act=photo&formhash=".FORMHASH;
    
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_tongcheng:edit");  
    
}





