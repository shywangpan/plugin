<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page = intval($_GET['page'])>0? intval($_GET['page']):1;

$pagesize = 50;
$start = ($page-1)*$pagesize;	

$count = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_count(" AND user_id={$__UserInfo['id']} AND order_status=2 ");
$orderListTmp = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_list(" AND user_id={$__UserInfo['id']} AND order_status=2 ","ORDER BY id DESC",$start,$pagesize);
$orderList = array();
if(is_array($orderListTmp) && !empty($orderListTmp)){
    foreach ($orderListTmp as $key => $value){
        $orderList[$key] = $value;
        if($value['tongcheng_id'] > 0){
            $tongchengInfoTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($value['tongcheng_id']);
            $orderList[$key]['content'] = cutstr(contentFormat($tongchengInfoTmp['content']),10,"...");
        }else if($value['tcshop_id'] > 0){
            $tcshopInfoTmp = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($value['tcshop_id']);
            $orderList[$key]['content'] = $tcshopInfoTmp['name'];
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
$prePageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=myorder&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=myorder&page={$nextPage}";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:myorder");  




