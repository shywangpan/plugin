<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$Lang = $scriptlang['tom_tcshop'];
$adminBaseUrl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_tcshop&pmod=admin'; 
$adminListUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_tcshop&pmod=admin';
$adminFromUrl = 'plugins&operation=config&do=' . $pluginid . '&identifier=tom_tcshop&pmod=admin';

$tomSysOffset = getglobal('setting/timeoffset');

include DISCUZ_ROOT.'./source/plugin/tom_tcshop/class/tom.form.php';
include DISCUZ_ROOT.'./source/plugin/tom_tcshop/class/function.admin.php';
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/tom.upload.php';
include DISCUZ_ROOT.'./source/plugin/tom_tcshop/class/function.core.php';
$tcshopConfig = get_tcshop_config($pluginid);
$Lang = formatLang($Lang);
$tongchengPlugin = C::t('#tom_tcshop#common_plugin')->fetch_by_identifier('tom_tongcheng');
$tongchengConfig = get_plugin_config($tongchengPlugin['pluginid']);
$appid = trim($tongchengConfig['wxpay_appid']);
$appsecret = trim($tongchengConfig['wxpay_appsecret']);
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/weixin.class.php';
$weixinClass = new weixinClass($appid,$appsecret);
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/templatesms.class.php';

if($_GET['tmod'] == 'index'){
    include DISCUZ_ROOT.'./source/plugin/tom_tcshop/admin/index.php';
}else if($_GET['tmod'] == 'cate'){
    include DISCUZ_ROOT.'./source/plugin/tom_tcshop/admin/cate.php';
}else if($_GET['tmod'] == 'resou'){
    include DISCUZ_ROOT.'./source/plugin/tom_tcshop/admin/resou.php';
}else if($_GET['tmod'] == 'lbs'){
    include DISCUZ_ROOT.'./source/plugin/tom_tcshop/admin/lbs.php';
}else if($_GET['tmod'] == 'focuspic'){
    include DISCUZ_ROOT.'./source/plugin/tom_tcshop/admin/focuspic.php';
}else if($_GET['tmod'] == 'common'){
    include DISCUZ_ROOT.'./source/plugin/tom_tcshop/admin/common.php';
}else if($_GET['tmod'] == 'addon'){
    include DISCUZ_ROOT.'./source/plugin/tom_tcshop/admin/addon.php';
}else if($_GET['tmod'] == 'pinglun'){
    include DISCUZ_ROOT.'./source/plugin/tom_tcshop/admin/pinglun.php';
}else if($_GET['tmod'] == 'pinglunReplay'){
    include DISCUZ_ROOT.'./source/plugin/tom_tcshop/admin/pinglunReplay.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_tcshop/admin/index.php';
}


