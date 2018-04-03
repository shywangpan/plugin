<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$uid = intval($_GET['uid'])>0? intval($_GET['uid']):0;
$act  = isset($_GET['act'])? addslashes($_GET['act']):'list';

$user = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($uid);

$tcCount = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count(" AND user_id={$uid} AND status=1 ");

$visitorCount = C::t('#tom_tongcheng#tom_tongcheng_visitor')->fetch_all_count(" AND user_id={$uid} ");
$visitorListTmp = C::t('#tom_tongcheng#tom_tongcheng_visitor')->fetch_all_list(" AND user_id={$uid} "," ORDER BY id DESC ",0,10);
$visitorList = array();
if(is_array($visitorListTmp) && !empty($visitorListTmp)){
    foreach ($visitorListTmp as $key => $value){
        $visitorList[$key] = $value;
        $visitUserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['visit_user_id']);
        $visitorList[$key]['visitUserInfo'] = $visitUserInfo;
    }
}

if($__UserInfo && $__UserInfo['id'] != $uid ){
    $visitTag = getcookie('tom_tongcheng_visitor'.$uid);
    if($visitTag == 1){
    }else{
        $insertData = array();
        $insertData['user_id']      = $uid;
        $insertData['visit_user_id'] = $__UserInfo['id'];
        $insertData['add_time']     = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_visitor')->insert($insertData);
        dsetcookie('tom_tongcheng_visitor'.$uid,1,86400);
    }
}

$add_time = dgmdate($user['add_time'],"Y-m-d",$tomSysOffset);

$shareUrl   = $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=home&uid=".$uid;
$shareLogo  = $user['picurl'];

$messageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=message&act=create&&to_user_id=".$user['id'].'&formhash='.FORMHASH;
$ajaxLoadListUrl = "plugin.php?id=tom_tongcheng:ajax&site={$site_id}&act=list&&formhash=".$formhash;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:home");  




