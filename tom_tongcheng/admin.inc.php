<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$Lang = $scriptlang['tom_tongcheng'];
$adminBaseUrl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_tongcheng&pmod=admin'; 
$adminListUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_tongcheng&pmod=admin';
$adminFromUrl = 'plugins&operation=config&do=' . $pluginid . '&identifier=tom_tongcheng&pmod=admin';
$uSiteUrl = urlencode($_G['siteurl']);

$tomSysOffset = getglobal('setting/timeoffset');
$nowDayTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),dgmdate($_G['timestamp'], 'j',$tomSysOffset),dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;
$nowMonthTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),1,dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;

//include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/tom.form.php';
//include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/tongcheng.core.php';
//include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/tom.upload.php';
//include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/function.core.php';
//include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/link.func.php';
$plugin_config = get_plugin_config($pluginid);
//$Lang = formatLang($Lang);

if($_GET['tmod'] == 'index'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/index.php';
}else if($_GET['tmod'] == 'model'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/model.php';
}else if($_GET['tmod'] == 'type'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/type.php';
}else if($_GET['tmod'] == 'cate'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/cate.php';
}else if($_GET['tmod'] == 'attr'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/attr.php';
}else if($_GET['tmod'] == 'tag'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/tag.php';
}else if($_GET['tmod'] == 'topnews'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/topnews.php';
}else if($_GET['tmod'] == 'user'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/user.php';
}else if($_GET['tmod'] == 'tousu'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/tousu.php';
}else if($_GET['tmod'] == 'focuspic'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/focuspic.php';
}else if($_GET['tmod'] == 'order'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/order.php';
}else if($_GET['tmod'] == 'common'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/common.php';
}else if($_GET['tmod'] == 'sites'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/sites.php';
}else if($_GET['tmod'] == 'sitesPrice'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/sitesPrice.php';
}else if($_GET['tmod'] == 'district'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/district.php';
}else if($_GET['tmod'] == 'nav'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/nav.php';
}else if($_GET['tmod'] == 'addon'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/addon.php';
}else if($_GET['tmod'] == 'doDao'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/doDao.php';
}else if($_GET['tmod'] == 'popup'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/popup.php';
}else if($_GET['tmod'] == 'tixian'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/tixian.php';
}else if($_GET['tmod'] == 'gold'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/gold.php';
}else if($_GET['tmod'] == 'pinglun'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/pinglun.php';
}else if($_GET['tmod'] == 'pinglunReplay'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/pinglunReplay.php';
}else if($_GET['tmod'] == 'pmMessage'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/pmMessage.php';
}else if($_GET['tmod'] == 'content'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/content.php';
}else if($_GET['tmod'] == 'help'){
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/help.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/admin/index.php';
}


