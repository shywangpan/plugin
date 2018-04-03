<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$table = DB::table('mandou_seokeyword');
$sql = <<<EOF
DROP TABLE IF EXISTS `$table`;
CREATE TABLE `$table` (
  `aid` int(10) NOT NULL,
  `stype` char(1) NOT NULL DEFAULT '1',
  `seo_title` varchar(255) DEFAULT '',
  `seo_keyword` varchar(255) DEFAULT '',
  `seo_decripation` varchar(355) DEFAULT '',
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`aid`,`stype`,`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB
EOF;
runquery($sql);
$finish = TRUE;
?>