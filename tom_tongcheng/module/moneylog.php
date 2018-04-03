<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page = intval($_GET['page'])>0? intval($_GET['page']):1;
$type_id = intval($_GET['type_id'])>0? intval($_GET['type_id']):0;

$pagesize = 50;
$start = ($page-1)*$pagesize;

$whereStr = " AND user_id={$__UserInfo['id']} ";
if(!empty($type_id)){
    $whereStr.= " AND type_id=$type_id ";
}

$count = C::t('#tom_tongcheng#tom_tongcheng_money_log')->fetch_all_count(" {$whereStr} ");
$moneylogListTmp = C::t('#tom_tongcheng#tom_tongcheng_money_log')->fetch_all_list(" {$whereStr} ","ORDER BY id DESC",$start,$pagesize);
$moneylogList = array();
if(is_array($moneylogListTmp) && !empty($moneylogListTmp)){
    foreach ($moneylogListTmp as $key => $value){
        $moneylogList[$key] = $value;
        if($value['type_id'] == 1){
            $tixianInfo = C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->fetch_by_id($value['tixian_id']);
            $moneylogList[$key]['tixianInfo'] = $tixianInfo;
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
$prePageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=moneylog&type_id={$type_id}&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=moneylog&type_id={$type_id}&page={$nextPage}";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:moneylog");




