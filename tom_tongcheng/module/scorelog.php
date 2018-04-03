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

$count = C::t('#tom_tongcheng#tom_tongcheng_score_log')->fetch_all_count(" AND user_id={$__UserInfo['id']} ");
$scorelogListTmp = C::t('#tom_tongcheng#tom_tongcheng_score_log')->fetch_all_list(" AND user_id={$__UserInfo['id']} ","ORDER BY id DESC",$start,$pagesize);
$scorelogList = array();
if(is_array($scorelogListTmp) && !empty($scorelogListTmp)){
    foreach ($scorelogListTmp as $key => $value){
        $scorelogList[$key] = $value;
    }
}

$showNextPage = 1;
if(($start + $pagesize) >= $count){
    $showNextPage = 0;
}
$allPageNum = ceil($count/$pagesize);
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=scorelog&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=scorelog&page={$nextPage}";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:scorelog");  




