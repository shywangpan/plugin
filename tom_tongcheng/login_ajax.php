<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$__UserInfo = array();
$userStatus = false;

$ucenterfilenameExists = false;
$ucenterfilename = DISCUZ_ROOT.'./source/plugin/tom_ucenter/tom_ucenter.inc.php';
if(file_exists($ucenterfilename)){
    $ucenterfilenameExists = true;
}
if($tongchengConfig['open_mobile'] == 1 && $ucenterfilenameExists){
    
    $__MemberInfo = array();
    $cookieUid = getcookie('tom_ucenter_member_uid');
    $cookieKey = getcookie('tom_ucenter_member_key');
    if(!empty($cookieUid) && !empty($cookieKey)){
        $__MemberInfoTmp = C::t('#tom_ucenter#tom_ucenter_member')->fetch_by_uid($cookieUid);
        if($__MemberInfoTmp && !empty($__MemberInfoTmp['mykey'])){
            if(md5($__MemberInfoTmp['uid'].'|||'.$__MemberInfoTmp['mykey']) == $cookieKey){
                $__MemberInfo = $__MemberInfoTmp;
                $userInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_member_id($__MemberInfo['uid']);
                if($userInfoTmp){
                    $__UserInfo = $userInfoTmp;
                    $userStatus = true;
                }
            }
        }
    }
    
}else{
    $cookieOpenid = getcookie('tom_tongcheng_user_openid');
    if(!empty($cookieOpenid)){
        $__UserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_openid($cookieOpenid);
        if($__UserInfo && !empty($__UserInfo['openid'])){
            $userStatus = true;
        }
    }   
}


