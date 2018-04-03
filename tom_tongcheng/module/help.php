<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$helpListTmp = C::t('#tom_tongcheng#tom_tongcheng_help')->fetch_all_list(""," ORDER BY paixu ASC,id DESC ",0,100);

$i = 1;
$helpList = array();
if(is_array($helpListTmp) && !empty($helpListTmp)){
    foreach ($helpListTmp as $key => $value){
        $helpList[$key] = $value;
        $helpList[$key]['i'] = $i;
        $helpList[$key]['content'] = stripslashes($value['content']);
        $i++;
    }
}

$kefuQrcodeSrc = $tongchengConfig['kefu_qrcode'];
if($__SitesInfo['id'] > 1){
    if(!preg_match('/^http/', $__SitesInfo['kefu_qrcode'])){
        if(strpos($__SitesInfo['kefu_qrcode'], 'source/plugin/tom_tongcheng/') === FALSE){
            $kefuQrcodeSrc = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$__SitesInfo['kefu_qrcode'];
        }else{
            $kefuQrcodeSrc = $__SitesInfo['kefu_qrcode'];
        }
    }else{
        $kefuQrcodeSrc = $__SitesInfo['kefu_qrcode'];
    }
}

$shareUrl   = $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=help";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:help");  




