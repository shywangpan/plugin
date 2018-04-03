<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$TOMCLOUDHOST = "http://discuzapi.tomwx.cn";
$urlBaseUrl = $_G['siteurl'].ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_tcshop&pmod='; 
dheader('location:'.$TOMCLOUDHOST.'/api/addon.php?ver=10&addonId=tom_tcshop&baseUrl='.urlencode($urlBaseUrl));
