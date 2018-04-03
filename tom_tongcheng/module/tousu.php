<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$tongcheng_id = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;

if($_GET['act'] == 'save' && $_GET['formhash'] == FORMHASH){
    
    if('utf-8' != CHARSET) {
        if(defined('IN_MOBILE')){
        }else{
            foreach($_POST AS $pk => $pv) {
                if(!is_numeric($pv)) {
                    $_GET[$pk] = $_POST[$pk] = wx_iconv_recurrence($pv);	
                }
            }
        }
    }
    
    $tongcheng_id   = isset($_GET['tongcheng_id'])? intval($_GET['tongcheng_id']):'';
    $reason         = isset($_GET['reason'])? daddslashes($_GET['reason']):'';
    $content        = isset($_GET['content'])? daddslashes($_GET['content']):'';
    $tel            = isset($_GET['tel'])? daddslashes($_GET['tel']):'';
    $xm             = isset($_GET['xm'])? daddslashes($_GET['xm']):'';
    
    $insertData = array();
    $insertData['tongcheng_id'] = $tongcheng_id;
    $insertData['xm']           = $xm;
    $insertData['tel']          = $tel;
    $insertData['reason']       = $reason;
    $insertData['content']      = $content;
    $insertData['add_time']     = TIMESTAMP;
    if(C::t('#tom_tongcheng#tom_tongcheng_tousu')->insert($insertData)){
        echo 200;exit;
    }
    echo 404;exit;
}


$ajaxTousuUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=tousu&act=save";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:tousu");  







