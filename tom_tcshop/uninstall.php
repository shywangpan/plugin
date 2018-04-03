<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS pre_tom_tcshop;
DROP TABLE IF EXISTS pre_tom_tcshop_cate;
DROP TABLE IF EXISTS pre_tom_tcshop_common;
DROP TABLE IF EXISTS pre_tom_tcshop_focuspic;
DROP TABLE IF EXISTS pre_tom_tcshop_guanzu;
DROP TABLE IF EXISTS pre_tom_tcshop_photo;
DROP TABLE IF EXISTS pre_tom_tcshop_pinglun;
DROP TABLE IF EXISTS pre_tom_tcshop_pinglun_reply;
DROP TABLE IF EXISTS pre_tom_tcshop_resou;
DROP TABLE IF EXISTS pre_tom_tcshop_topnews;
DROP TABLE IF EXISTS pre_tom_tcshop_clerk;
DROP TABLE IF EXISTS pre_tom_tcshop_tuwen;
DROP TABLE IF EXISTS pre_tom_tcshop_clicks_log;
DROP TABLE IF EXISTS pre_tom_tcshop_pinglun_photo;
        
EOF;

runquery($sql);

$finish = TRUE;

