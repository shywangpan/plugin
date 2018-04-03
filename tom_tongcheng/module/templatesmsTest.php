<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/templatesms.class.php';
$access_token = $weixinClass->get_access_token();
$nextSmsTime = $__UserInfo['last_smstp_time'] + 0;


if($access_token && !empty($__UserInfo['openid']) && TIMESTAMP > $nextSmsTime ){
    $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=index");
    $smsData = array(
        'first'         => 'templatesms OK',
        'keyword1'      => $__SitesInfo['name'],
        'keyword2'      => 'test',
        'remark'        => ''
    );
    $r = $templateSmsClass->sendSms01($__UserInfo['openid'],$tongchengConfig['template_id'],$smsData);

}

echo 'templatesms TEST';exit;






