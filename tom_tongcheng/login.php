<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$__UserInfo = array();
$__MemberInfo = array();
$userStatus = false;
$firstLoginStatus = false;
$loginStatus = 0;
$pmNewNum = 0;

$mustLogin = true;
if($_GET['id'] == 'tom_tongcheng' && ($_GET['mod'] == 'index' || $_GET['mod'] == 'list' || $_GET['mod'] == 'info' || $_GET['mod'] == 'home')){
    $mustLogin = false;
}
if($_GET['id'] == 'tom_tcshop' && ($_GET['mod'] == 'index' || $_GET['mod'] == 'list' || $_GET['mod'] == 'search' || $_GET['mod'] == 'details')){
    $mustLogin = false;
}
if($_GET['id'] == 'tom_tcqianggou' && ($_GET['mod'] == 'index' || $_GET['mod'] == 'details' || $_GET['mod'] == 'coupon')){
    $mustLogin = false;
}
if($_GET['id'] == 'tom_tchongbao' && $_GET['mod'] == 'index'){
    $mustLogin = false;
}
if($_GET['id'] == 'tom_tcptuan' && ($_GET['mod'] == 'index' || $_GET['mod'] == 'goodsinfo' || $_GET['mod'] == 'cates' || $_GET['mod'] == 'list')){
    $mustLogin = false;
}
if($_GET['id'] == 'tom_tckjia' && ($_GET['mod'] == 'index')){
    $mustLogin = false;
}
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
    $mustLogin = true;
}

$tj_hehuoren_id = 0;
if($__ShowTchehuoren == 1){
    $tj_hehuoren_id = isset($_GET['tj_hehuoren_id'])?intval($_GET['tj_hehuoren_id']):0;
}

$ucenterfilenameExists = false;
$ucenterfilename = DISCUZ_ROOT.'./source/plugin/tom_ucenter/tom_ucenter.inc.php';
if(file_exists($ucenterfilename)){
    $ucenterfilenameExists = true;
}
if($tongchengConfig['open_mobile'] == 1 && $ucenterfilenameExists){
    
    $ucenterConfig = $_G['cache']['plugin']['tom_ucenter'];
    
    # new login start
    $cookieUid = getcookie('tom_ucenter_member_uid');
    $cookieKey = getcookie('tom_ucenter_member_key');
    if(!empty($cookieUid) && !empty($cookieKey)){
        $__MemberInfoTmp = C::t('#tom_ucenter#tom_ucenter_member')->fetch_by_uid($cookieUid);
        if($__MemberInfoTmp && !empty($__MemberInfoTmp['mykey'])){
            if(md5($__MemberInfoTmp['uid'].'|||'.$__MemberInfoTmp['mykey']) == $cookieKey){
                $__MemberInfo = $__MemberInfoTmp;
                $userStatus = true;
                $mustLogin = true;
            }
        }
    }

    if($userStatus){
        $loginStatus = 1;
        
        $userInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_member_id($__MemberInfo['uid']);
        if($userInfoTmp){
            $__UserInfo = $userInfoTmp;
            if(!empty($__MemberInfo['unionid']) && $__UserInfo['unionid'] != $__MemberInfo['unionid'] && $__UserInfo['openid'] == $__MemberInfo['openid']){
                $updateData             = array();
                $updateData['unionid']  = $__MemberInfo['unionid'];
                C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);
            }
            if(!empty($__MemberInfo['tel']) && $__UserInfo['tel'] != $__MemberInfo['tel']){
                $updateData             = array();
                $updateData['tel']      = $__MemberInfo['tel'];
                C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);
            }
            if(!empty($__MemberInfo['openid']) && $__MemberInfo['openid'] != $__UserInfo['openid']){
                $userInfoTmpOpenid = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_openid($__MemberInfo['openid']);
                if($userInfoTmpOpenid){
                }else{
                    $updateData             = array();
                    $updateData['openid']   = $__MemberInfo['openid'];
                    C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);
                }
            }
        }else{
            $createNewUser = false;
            if($__MemberInfo['last_login_type'] == 'weixin'){

                $userInfoTmpOpenid = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_openid($__MemberInfo['openid']);
                $userInfoTmpUnionid = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_unionid($__MemberInfo['unionid']);
                if(!empty($__MemberInfo['openid']) && $userInfoTmpOpenid){
                    $__UserInfo = $userInfoTmpOpenid;

                    $updateData                 = array();
                    $updateData['member_id']    = $__MemberInfo['uid'];
                    C::t('#tom_tongcheng#tom_tongcheng_user')->update($userInfoTmpOpenid['id'],$updateData);

                    if(empty($__MemberInfo['tel']) && !empty($userInfoTmpOpenid['tel'])){
                        $memberInfoTmpTel = C::t('#tom_ucenter#tom_ucenter_member')->fetch_by_tel($userInfoTmpOpenid['tel']);
                        if(!$memberInfoTmpTel){
                            $updateData = array();
                            $updateData['tel']     = $userInfoTmpOpenid['tel'];
                            C::t('#tom_ucenter#tom_ucenter_member')->update($__MemberInfo['uid'],$updateData);
                        }
                    }
                }else if(!empty($__MemberInfo['unionid']) && $userInfoTmpUnionid){
                    $__UserInfo = $userInfoTmpUnionid;
                    $updateData                 = array();
                    $updateData['openid']       = $__MemberInfo['openid'];
                    $updateData['member_id']    = $__MemberInfo['uid'];
                    C::t('#tom_tongcheng#tom_tongcheng_user')->update($userInfoTmpUnionid['id'],$updateData);
                }else{
                    $createNewUser = true;
                }
            }else if($__MemberInfo['last_login_type'] == 'tel'){
                $userInfoTmpTel = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_tel($__MemberInfo['tel']);
                if(!empty($__MemberInfo['tel']) && $userInfoTmpTel){
                    $__UserInfo = $userInfoTmpTel;

                    $updateData                 = array();
                    $updateData['member_id']    = $__MemberInfo['uid'];
                    C::t('#tom_tongcheng#tom_tongcheng_user')->update($userInfoTmpTel['id'],$updateData);

                    if(empty($__MemberInfo['openid']) && !empty($userInfoTmpTel['openid'])){
                        $memberInfoTmpOpenid = C::t('#tom_ucenter#tom_ucenter_member')->fetch_by_openid($userInfoTmpTel['openid']);
                        if(!$memberInfoTmpOpenid){
                            $updateData = array();
                            $updateData['openid']     = $userInfoTmpTel['openid'];
                            C::t('#tom_ucenter#tom_ucenter_member')->update($__MemberInfo['uid'],$updateData);
                        }
                    }

                }else{
                    $createNewUser = true;
                }

            }else if($__MemberInfo['last_login_type'] == 'app'){
                $userInfoTmpUnionid = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_unionid($__MemberInfo['unionid']);
                if(!empty($__MemberInfo['unionid']) && $userInfoTmpUnionid){
                    $__UserInfo = $userInfoTmpUnionid;
                    $updateData                 = array();
                    $updateData['member_id']    = $__MemberInfo['uid'];
                    C::t('#tom_tongcheng#tom_tongcheng_user')->update($userInfoTmpUnionid['id'],$updateData);
                }else{
                    $createNewUser = true;
                }
            }else if($__MemberInfo['last_login_type'] == 'bbs'){
                $createNewUser = true;
            }

            if($createNewUser){
                $insertData = array();
                $insertData['member_id']        = $__MemberInfo['uid'];
                $insertData['openid']           = $__MemberInfo['openid'];
                $insertData['tj_hehuoren_id']   = $tj_hehuoren_id;
                $insertData['unionid']          = $__MemberInfo['unionid'];
                $insertData['nickname']         = $__MemberInfo['nickname'];
                $insertData['picurl']           = $__MemberInfo['picurl'];
                $insertData['tel']              = $__MemberInfo['tel'];
                $insertData['score']            = $tongchengConfig['new_user_give_score'];
                if($tongchengConfig['open_hongbao_tz'] == 1){
                    $insertData['hongbao_tz']   = 1;
                }
                $insertData['add_time']         = TIMESTAMP;
                if(C::t('#tom_tongcheng#tom_tongcheng_user')->insert($insertData)){
                    $__UserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_member_id($__MemberInfo['uid']);
                    $firstLoginStatus = true;
                }
            }
        }

    }else if($mustLogin){
        $login_back_url = $weixinClass->get_url();
        $login_back_url = urlencode($login_back_url);
        if(function_exists('tomheader')){
            tomheader('location:'.$_G['siteurl']."plugin.php?id=tom_ucenter&mod=login&t_from=tongcheng&t_back=".$login_back_url);exit;
        }else{
            dheader('location:'.$_G['siteurl']."plugin.php?id=tom_ucenter&mod=login&t_from=tongcheng&t_back=".$login_back_url);exit;
        }
    }

    # new login end
    
}else{

    # old weixin login start
    $cookieOpenid = getcookie('tom_tongcheng_user_openid');
    if(!empty($cookieOpenid)){
        $__UserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_openid($cookieOpenid);
        if($__UserInfo && !empty($__UserInfo['openid'])){
            $userStatus = true;
            $mustLogin = true;
        }
    }

    if($mustLogin){
        $loginStatus = 1;

        if(!$userStatus){
            $openid     = '';
            $unionid    = '';
            $nickname   = '';
            $headimgurl = '';
            include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/oauth3.php';
            $nickname = diconv($nickname,'utf-8');
            $__UserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_openid($openid);
            if($__UserInfo){
                if(strpos($__UserInfo['picurl'], 'wx.qlogo.cn') !== FALSE){
                    $updateData = array();
                    //$updateData['nickname']   = $nickname;
                    $updateData['picurl']     = $headimgurl;
                    C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);
                }
                if(empty($__UserInfo['unionid']) && !empty($unionid)){
                    $updateData = array();
                    $updateData['unionid']     = $unionid;
                    C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);
                }
                $lifeTime = 86400;
                dsetcookie('tom_tongcheng_user_openid',$openid,$lifeTime);
            }else{
                $insertData = array();
                $insertData['openid']           = $openid;
                $insertData['tj_hehuoren_id']   = $tj_hehuoren_id;
                $insertData['unionid']          = $unionid;
                $insertData['nickname']         = $nickname;
                $insertData['picurl']           = $headimgurl;
                $insertData['score']            = $tongchengConfig['new_user_give_score'];
                if($tongchengConfig['open_hongbao_tz'] == 1){
                    $insertData['hongbao_tz']   = 1;
                }
                $insertData['add_time']         = TIMESTAMP;
                if(C::t('#tom_tongcheng#tom_tongcheng_user')->insert($insertData)){
                    $__UserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_openid($openid);
                    $lifeTime = 86400;
                    dsetcookie('tom_tongcheng_user_openid',$openid,$lifeTime);
                    $firstLoginStatus = true;
                }
            }
        }

    }

    # old weixin login end
}

if($tongchengConfig['new_user_give_score'] > 0 && $firstLoginStatus){
    $insertData = array();
    $insertData['user_id']          = $__UserInfo['id'];
    $insertData['score_value']      = $tongchengConfig['new_user_give_score'];
    $insertData['old_value']        = 0;
    $insertData['log_type']         = 10;
    $insertData['log_time']         = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng_score_log')->insert($insertData);
}
if(!empty($tongchengConfig['new_user_tz']) && $firstLoginStatus){
    $insertData = array();
    $insertData['user_id']      = $__UserInfo['id'];
    $insertData['type']         = 1;
    $insertData['content']      = '<font color="#238206">'.$__SitesInfo['name'].'</font><br/>'.str_replace("{SITENAME}", $__SitesInfo['name'], $tongchengConfig['new_user_tz']);
    $insertData['is_read']      = 0;
    $insertData['tz_time']      = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng_tz')->insert($insertData);
}

if($mustLogin){

    $pmNewNum = C::t('#tom_tongcheng#tom_tongcheng_pm')->fetch_all_newnum(" AND user_id={$__UserInfo['id']} ");
    $noReadTzCount = C::t('#tom_tongcheng#tom_tongcheng_tz')->fetch_all_count(" AND user_id='{$__UserInfo['id']}' AND is_read=0 ");
    $pmNewNum = $pmNewNum + $noReadTzCount;

    $__UserInfo['groupid'] = 0;
    $__UserInfo['groupsiteid'] = 0;
    if($tongchengConfig['manage_user_id'] == $__UserInfo['id']){
        $__UserInfo['groupid'] = 1;
    }
    if($__UserInfo['groupid'] == 0){
        $siteInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_manage_user_id($__UserInfo['id']);
        if($siteInfoTmp){
            $tcadminfilename = DISCUZ_ROOT.'./source/plugin/tom_tcadmin/tom_tcadmin.inc.php';
            if(file_exists($tcadminfilename)){
                $__UserInfo['groupid'] = 2;
                $__UserInfo['groupsiteid'] = $siteInfoTmp['id'];
            }
        }
    }
    
    if(empty($__UserInfo['openid']) && $__IsWeixin == 1 && $__UserInfo['is_majia'] == 0){
        include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/oauth2.php';
        $updateData = array();
        $updateData['openid']     = $openid;
        C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);
    }
    
    $last_login_time_cookie = getcookie('tom_tongcheng_last_login_time');
    if($last_login_time_cookie && $last_login_time_cookie == 1){
    }else{
        $updateData = array();
        $updateData['last_login_time']     = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);
        $lifeTime = 300;
        dsetcookie('tom_tongcheng_last_login_time',1,$lifeTime);
    }
    
    if(empty($__UserInfo['picurl']) && $_GET['mod'] != 'myedit' && $_GET['mod'] != 'upload'){
        dheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=myedit");exit;
    }

    if($__UserInfo['status'] == 2 && $_GET['mod'] != 'personal' && $_GET['mod'] != 'article'){
        dheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=personal");exit;
    }
}