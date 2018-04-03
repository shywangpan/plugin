<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act  = isset($_GET['act'])? addslashes($_GET['act']):'';

if($act == 'save' && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 200,
    );
    $nickname = isset($_GET['nickname'])? daddslashes(diconv(urldecode($_GET['nickname']),'utf-8')):'';
    $picurl = isset($_GET['picurl'])? daddslashes($_GET['picurl']):'';
    
    if(empty($nickname)){
        $nickname = $__UserInfo['nickname'];
    }
    $updateData = array();
    $updateData['nickname'] = $nickname;
    $updateData['picurl'] = $picurl;
    C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);
    
    echo json_encode($outArr); exit;
}

$picYasuSize = 640;
if($tongchengConfig['pic_yasu_size'] > 100){
    $picYasuSize = $tongchengConfig['pic_yasu_size'];
}

$mustUploadAvatar = 0;
if(empty($__UserInfo['picurl'])){
    $mustUploadAvatar = 1;
}

$uploadUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=upload&act=avatar&formhash=".FORMHASH;
$saveUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=myedit&act=save";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:myedit");  




