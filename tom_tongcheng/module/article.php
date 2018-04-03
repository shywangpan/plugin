<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}



$act  = isset($_GET['act'])? addslashes($_GET['act']):'xieyi';

$title = "";
$content = "";

if($act == 'xieyi'){
    $title = lang("plugin/tom_tongcheng", "xieyi_title");
    $content = stripslashes($__CommonInfo['xieyi_txt']);
}else if($act == 'help'){
    $title = lang("plugin/tom_tongcheng", "help_title");
    $content = stripslashes($__CommonInfo['help_txt']);
}else if($act == 'kefu'){
    $title = lang("plugin/tom_tongcheng", "kefu_title");
    $content = stripslashes($__CommonInfo['kefu_txt']);
}
$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:article");  




