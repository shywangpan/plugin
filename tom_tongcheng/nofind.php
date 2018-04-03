<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$__urlTmp = $weixinClass->get_url();

$linkConfig = array();
if(file_exists(DISCUZ_ROOT."./source/plugin/tom_link/data/rule.php")){
    $linkConfig = $_G['cache']['plugin']['tom_link'];
    if($linkConfig['close_default'] == 1){
        if(strpos($__urlTmp, "tom_tchongbao") !== FALSE){
            if($_GET['mod'] == 'index'){
                echo '404 Not Found...';exit;
            }
        }
    }
}


