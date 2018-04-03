<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$table = DB::table('mandou_seokeyword');
$sql = <<<EOF
DROP TABLE IF EXISTS `$table`;
EOF;
runquery($sql);
$finish = TRUE;
?>