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
    
    $model_id    = isset($_GET['model_id'])? intval($_GET['model_id']):0;
    $type_id    = isset($_GET['type_id'])? intval($_GET['type_id']):0;
    $cate_id    = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
    
    
    $oldTypeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongcheng_id['type_id']);
    $newTypeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($type_id);
    
    if($__UserInfo['groupid'] == 1 || $__UserInfo['groupid'] == 2 ){
    }else{
        if($oldTypeInfo['free_status'] == 2 && $oldTypeInfo['fabu_price'] > 0){
            if($newTypeInfo['free_status'] == 2 && $newTypeInfo['fabu_price'] > 0){
                if($newTypeInfo['fabu_price'] > $oldTypeInfo['fabu_price']){
                    $outArr = array(
                        'status'=> 301,
                    );
                    echo json_encode($outArr); exit;
                }
            }
        }else{
            if($newTypeInfo['free_status'] == 2 && $newTypeInfo['fabu_price'] > 0){
                $outArr = array(
                    'status'=> 302,
                );
                echo json_encode($outArr); exit;
            }
        }
    }
    
    
    $updateData = array();
    $updateData['model_id']      = $model_id;
    $updateData['type_id']       = $type_id;
    $updateData['cate_id']       = $cate_id;
    C::t('#tom_tongcheng#tom_tongcheng')->update($tongcheng_id,$updateData);
    
    $outArr = array(
        'status'=> 200,
    );
    echo json_encode($outArr); exit;
    
}else{
    
    $model_id = intval($_GET['model_id'])>0? intval($_GET['model_id']):0;
    $type_id = intval($_GET['type_id'])>0? intval($_GET['type_id']):0;
    
    $modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($model_id);
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($type_id);
    
    $modelList = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" "," ORDER BY paixu ASC,id DESC ",0,50);
    
    $typeList = array();
    if(!empty($model_id)){
        $typeList = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_all_list(" AND model_id={$model_id} "," ORDER BY paixu ASC,id DESC ",0,20);
    }
    
    $cateList = array();
    if(!empty($type_id)){
        $cateList = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_all_list(" AND type_id={$type_id} "," ORDER BY paixu ASC,id DESC ",0,100);
    }
    
    $saveUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=editcate&act=save";
    $backUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=edit&tongcheng_id={$tongcheng_id}&fromlist=".$_GET['fromlist'];
    
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_tongcheng:editcate");  
    
}





