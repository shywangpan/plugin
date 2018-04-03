<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page = intval($_GET['page'])>0? intval($_GET['page']):1;

$pagesize = 10;
$start = ($page-1)*$pagesize;	

$count = C::t('#tom_tongcheng#tom_tongcheng_collect')->fetch_all_count(" AND user_id={$__UserInfo['id']} ");
$collectListTmp = C::t('#tom_tongcheng#tom_tongcheng_collect')->fetch_all_list(" AND user_id={$__UserInfo['id']} "," ORDER BY id DESC ",$start,$pagesize);
$collectList = array();
if(is_array($collectListTmp) && !empty($collectListTmp)){
    foreach ($collectListTmp as $key => $value){
        $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($value['tongcheng_id']);
        if($tongchengInfo){
            $collectList[$key] = $value;
            $collectList[$key]['tongchengInfo'] = $tongchengInfo;
            $tongchengInfo['content'] = cutstr(contentFormat($tongchengInfo['content']),20,"...");
            $collectList[$key]['tongchengInfo']['content'] = $tongchengInfo['content'];

            $userInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengInfo['user_id']); 
            $typeInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);
            $collectList[$key]['userInfo'] = $userInfoTmp;
            $collectList[$key]['typeInfo'] = $typeInfoTmp;
        }
    }
}

$showNextPage = 1;
if(($start + $pagesize) >= $count){
    $showNextPage = 0;
}
$allPageNum = ceil($count/$pagesize);
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mycollect&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=mycollect&page={$nextPage}";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:mycollect");  




