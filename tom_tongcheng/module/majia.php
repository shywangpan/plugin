<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

## tcmajia start
$openMajiaStatus = 0;
if($__ShowTcmajia == 1){
    if($tcmajiaConfig['use_majia_admin_1'] == $__UserInfo['id']){
        $openMajiaStatus = 1;
    }
    if($tcmajiaConfig['use_majia_admin_2'] == $__UserInfo['id']){
        $openMajiaStatus = 1;
    }
    if($tcmajiaConfig['use_majia_admin_3'] == $__UserInfo['id']){
        $openMajiaStatus = 1;
    }
    if($tcmajiaConfig['use_majia_admin_4'] == $__UserInfo['id']){
        $openMajiaStatus = 1;
    }
    if($tcmajiaConfig['use_majia_admin_5'] == $__UserInfo['id']){
        $openMajiaStatus = 1;
    }
    if($__UserInfo['is_majia'] == 1){
        $openMajiaStatus = 1;
    }
}
## tcmajia end

if($openMajiaStatus == 0){
    tomheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=index");exit;
}

if($_GET['act'] == 'login' && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 1,
    );
    
    $majia_user_id = intval($_GET['majia_user_id'])>0? intval($_GET['majia_user_id']):0;
    
    $majiaUserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($majia_user_id);
    
    if($majiaUserInfo['member_id']){
        $memberInfoTmp = C::t('#tom_ucenter#tom_ucenter_member')->fetch_by_uid($majiaUserInfo['member_id']);
        if($memberInfoTmp){
            $lifeTime = 86400;
            dsetcookie('tom_ucenter_member_uid',$memberInfoTmp['uid'],$lifeTime);
            dsetcookie('tom_ucenter_member_key',md5($memberInfoTmp['uid'].'|||'.$memberInfoTmp['mykey']),$lifeTime);
            $outArr = array(
                'status'=> 200,
            );
            echo json_encode($outArr); exit;
        }
    }
    
    $mykey = substr(str_shuffle("012345678901234567890123456789"), 0, 6);
    $insertData = array();
    $insertData['openid']               = '';
    $insertData['unionid']              = '';
    $insertData['nickname']             = $majiaUserInfo['nickname'];
    $insertData['picurl']               = $majiaUserInfo['picurl'];
    $insertData['mykey']                = $mykey;
    $insertData['last_login_type']      = 'majia';
    $insertData['last_login_time']      = TIMESTAMP;
    $insertData['add_time']             = TIMESTAMP;
    if(C::t('#tom_ucenter#tom_ucenter_member')->insert($insertData)){
        $member_id = C::t('#tom_ucenter#tom_ucenter_member')->insert_id();
        
        $updateData                 = array();
        $updateData['member_id']    = $member_id;
        C::t('#tom_tongcheng#tom_tongcheng_user')->update($majiaUserInfo['id'],$updateData);
        
        $memberInfoTmp = C::t('#tom_ucenter#tom_ucenter_member')->fetch_by_uid($member_id);
        $lifeTime = 86400;
        dsetcookie('tom_ucenter_member_uid',$memberInfoTmp['uid'],$lifeTime);
        dsetcookie('tom_ucenter_member_key',md5($memberInfoTmp['uid'].'|||'.$memberInfoTmp['mykey']),$lifeTime);
        $outArr = array(
            'status'=> 200,
        );
        echo json_encode($outArr); exit;
    }
    
    echo json_encode($outArr); exit;
}else if($_GET['act'] == 'login_out' && $_GET['formhash'] == FORMHASH){
    $lifeTime = 86400;
    dsetcookie('tom_ucenter_member_uid',0,$lifeTime);
    dsetcookie('tom_ucenter_member_key','',$lifeTime);
    $outArr = array(
        'status'=> 200,
    );
    echo json_encode($outArr); exit;
}

$tcmajiaList = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_all_list(' AND is_majia = 1 ', 'ORDER BY id DESC', 0, 500);

$loginUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=majia&act=login&formhash=".FORMHASH;
$loginOutUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=majia&act=login_out&formhash=".FORMHASH;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:majia");  




