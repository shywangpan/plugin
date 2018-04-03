<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];
$tomSysOffset = getglobal('setting/timeoffset');

$page   = isset($_GET['page'])? intval($_GET['page']):1;
$site_id     = isset($_GET['site_id'])? intval($_GET['site_id']):0;
$model_id    = isset($_GET['model_id'])? intval($_GET['model_id']):0;
$type_id     = isset($_GET['type_id'])? intval($_GET['type_id']):0;
$cate_id     = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
$refresh_rand     = isset($_GET['refresh_rand'])? intval($_GET['refresh_rand']):0;
$topstatus   = isset($_GET['topstatus'])? intval($_GET['topstatus']):0;

$where = " AND shenhe_status = 1 AND status = 1  ";
if($site_id){
    $where.= " AND site_id = {$site_id} ";
}
if($model_id){
    $where.= " AND model_id = {$model_id} ";
}
if($refresh_rand > 0){
    $minTime = TIMESTAMP - $refresh_rand * 86400;
    $where.= " AND refresh_time > {$minTime} ";
}
if($type_id){
    $where.= " AND type_id = {$type_id} ";
}
if($cate_id){
    $where.= " AND cate_id = {$cate_id} ";
}
if($topstatus == 1){
    $where.= " AND topstatus = 1 AND toptime > ".TIMESTAMP;
}

$pagesize = 100;
$start = ($page-1)*$pagesize;
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/function.core.php';

if(isset($_G['uid']) && $_G['uid'] > 0 && $_G['groupid'] == 1){
    $tonchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list($where, " ORDER BY add_time DESC,id DESC ",$start,$pagesize);
    $tonchengList = array();
    foreach ($tonchengListTmp as $key => $value) {
        $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']);
        
        $modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($value['model_id']);
        $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($value['type_id']);
        $cateInfo = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_by_id($value['cate_id']);
        $siteInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($value['site_id']);
        $areaInfo = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($value['area_id']);
        $streetInfo = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($value['street_id']);
        
        $tongchengAttrList = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY id DESC ",0,50);
        $tongchengTagList = C::t('#tom_tongcheng#tom_tongcheng_tag')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY id DESC ",0,50);
        
        $tonchengList[$key]['xm_tel'] = lang('plugin/tom_tongcheng', 'doDao_fbr').$value['xm'].'  '.$value['tel'];
        $tonchengList[$key]['biaoti'] = lang('plugin/tom_tongcheng', 'doDao_title').$value['title'];
        $tonchengList[$key]['fenlei'] = lang('plugin/tom_tongcheng', 'doDao_fenlei');
        if($modelInfo){
            $tonchengList[$key]['fenlei'].= $modelInfo['name'];
        }
        if($typeInfo){
            $tonchengList[$key]['fenlei'].= ' > '.$typeInfo['name'];
        }
        if($cateInfo){
            $tonchengList[$key]['fenlei'].= ' > '.$cateInfo['name'];
        }
        $tonchengList[$key]['biaoqian'] = lang('plugin/tom_tongcheng', 'doDao_biaoqian');
        if(is_array($tongchengTagList) && !empty($tongchengTagList)){
            foreach($tongchengTagList as $k => $v){
                $tonchengList[$key]['biaoqian'].= '['.$v['tag_name'].']';
            }
        }
        $tonchengList[$key]['doDao_refresh_time'] = lang('plugin/tom_tongcheng', 'doDao_refresh_time').dgmdate($value['refresh_time'],"Y-m-d H:i",$tomSysOffset);
        if($value['topstatus'] == 1 ){
            $tonchengList[$key]['doDao_topstatus'] = lang('plugin/tom_tongcheng', 'doDao_topstatus').lang('plugin/tom_tongcheng', 'doDao_topstatus_1');
        }else{
            $tonchengList[$key]['doDao_topstatus'] = lang('plugin/tom_tongcheng', 'doDao_topstatus').lang('plugin/tom_tongcheng', 'doDao_topstatus_2');
        }
        $tonchengList[$key]['doDao_details'] = lang('plugin/tom_tongcheng', 'doDao_details').contentFormat($value['content']);
        
    }

    Header("Content-type:application/octet-stream"); 
    Header("Accept-Ranges:bytes"); 
    header("Content-Disposition:attachment;filename=tongcheng.txt"); 
    header("Expires:0"); 
    header("Cache-Control:must-revalidate,post-check=0,pre-check=0"); 
    header("Pragma:public"); 
    foreach ($tonchengList as $fields){
        foreach ($fields as $k=> $v){
            $str = @diconv("$v",CHARSET,"GB2312");
            echo $str ."\r\n";
        }
        echo "\r\n-------------------------------------------------------------------------------------------------------\r\n";
    }
    exit;
}else{
    exit('Access Denied');
}

