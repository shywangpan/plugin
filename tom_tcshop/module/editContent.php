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
    
    $content            = isset($_GET['content'])? addslashes($_GET['content']):'';
    
    $photoArr = $photoTxtArr = $photoPaixuArr = array();
    foreach($_GET as $key => $value){
        if(strpos($key, "photo_") !== false){
            $kk = intval(ltrim($key, "photo_"));
            $photoArr[$kk] = addslashes($value);
        }
        if(strpos($key, "txt_") !== false){
            $kk = intval(ltrim($key, "txt_"));
            $photoTxtArr[$kk] = addslashes($value);
        }
        if(strpos($key, "paixu_") !== false){
            $kk = intval(ltrim($key, "paixu_"));
            $photoPaixuArr[$kk] = addslashes($value);
        }
    }
    
    $updateData = array();
    $updateData['content']          = dhtmlspecialchars($content);
    $updateData['admin_edit']       = 0;
    $updateData['part1']            = TIMESTAMP;
    if(C::t('#tom_tcshop#tom_tcshop')->update($tcshop_id,$updateData)){
        
        C::t('#tom_tcshop#tom_tcshop_tuwen')->delete_by_tcshop_id($tcshop_id);
        
        if(is_array($photoArr) && !empty($photoArr)){
            foreach ($photoArr as $key => $value){
                $insertData = array();
                $insertData['tcshop_id'] = $tcshop_id;
                $insertData['picurl']    = $value;
                $insertData['txt']       = dhtmlspecialchars($photoTxtArr[$key]);
                $insertData['paixu']     = $photoPaixuArr[$key];
                C::t('#tom_tcshop#tom_tcshop_tuwen')->insert($insertData);
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

$tcshopTuwenListTmp = C::t('#tom_tcshop#tom_tcshop_tuwen')->fetch_all_list(" AND tcshop_id={$tcshopInfo['id']} "," ORDER BY paixu ASC,id ASC ",0,50);
$tcshopTuwenList = array();
$photoCount = 0;
if(is_array($tcshopTuwenListTmp) && !empty($tcshopTuwenListTmp)){
    foreach ($tcshopTuwenListTmp as $kk => $vv){
        $photoCount++;
        if(!preg_match('/^http/', $vv['picurl']) ){
            $picurlTmp = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$vv['picurl'];
        }else{
            $picurlTmp = $vv['picurl'];
        }
        $tcshopTuwenList[$kk]['picurl'] = $vv['picurl'];
        $tcshopTuwenList[$kk]['txt'] = $vv['txt'];
        $tcshopTuwenList[$kk]['paixu'] = $vv['paixu'];
        $tcshopTuwenList[$kk]['src'] = $picurlTmp;
        $tcshopTuwenList[$kk]['li_i'] = $photoCount;

    }
}

$tcshopInfo['content'] = strip_tags($tcshopInfo['content']);
    
$saveUrl = "plugin.php?id=tom_tcshop&site={$site_id}&mod=editContent&act=save";
$uploadUrl1 = "plugin.php?id=tom_tcshop&site={$site_id}&mod=upload&act=picurl&formhash=".FORMHASH;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tcshop:editContent");




