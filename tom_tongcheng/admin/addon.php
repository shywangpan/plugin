<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$TOMCLOUDHOST = "http://discuzapi.tomwx.cn";
$urlBaseUrl = $_G['siteurl'].ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_tongcheng&pmod='; 
dheader('location:'.$TOMCLOUDHOST.'/api/addon.php?ver=20&addonId=tom_tongcheng&baseUrl='.urlencode($urlBaseUrl));
