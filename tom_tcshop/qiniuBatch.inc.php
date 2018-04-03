<?php

/*
   This is NOT a freeware, use is subject to license terms
   °æÈ¨ËùÓÐ£ºTOMÎ¢ÐÅ www.tomwx.cn
   ÆßÅ£ÔÆ Í¼Æ¬ÉÏ´«½Å±¾
   Í¼Æ¬²Ã¼ôÎÄµµ £ºhttps://developer.qiniu.com/dora/manual/1279/basic-processing-images-imageview2
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

ignore_user_abort(true);
set_time_limit(0);

$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];

$qiniu_access_key   = trim($tongchengConfig['qiniu_access_key']);
$qiniu_secret_key   = trim($tongchengConfig['qiniu_secret_key']);
$qiniu_bucket       = trim($tongchengConfig['qiniu_bucket']);
$qiniu_url          = trim($tongchengConfig['qiniu_url']);

define("TOM_QINIU_ACCESS_KEY", $qiniu_access_key);  
define("TOM_QINIU_SECRET_KEY", $qiniu_secret_key);
define("TOM_QINIU_BUCKET", $qiniu_bucket);
define("TOM_QINIU_URL", $qiniu_url);

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/Qiniu/load.php';

$whereStr   = " AND qiniu_status=0 ";
$photoList  = C::t('#tom_tcshop#tom_tcshop_photo')->fetch_all_list(" {$whereStr} "," ORDER BY id ASC ",0,10);
$overStatus = 0;
$errorStatus = 0;
$okStatus = 0;
if(is_array($photoList) && !empty($photoList)){
    foreach ($photoList as $key => $value){
        if(!preg_match('/^http/', $value['picurl']) ){
            if(strpos($value['picurl'], 'source/plugin/tom_tcshop/data/') === false){
                $filepicurl = DISCUZ_ROOT.'./data/attachment/tomwx/'.$value['picurl'];
            }else{
                $filepicurl = DISCUZ_ROOT.'./'.$value['picurl'];
            }
            if(file_exists($filepicurl)){
                $fileext = qiniu_fileext($filepicurl);
                if(qiniu_is_image_ext($fileext)){
                }else{
                    $fileext = 'jpg';
                }
                $objname = md5($filepicurl).'.'.$fileext;
                $r = qiniu_upload($objname,$filepicurl);
                if(!empty($r['picurl'])){
                    $okStatus = 1;
                    $updateData = array();
                    $updateData['qiniu_status'] = 1;
                    $updateData['qiniu_picurl'] = $r['picurl'];
                    C::t('#tom_tcshop#tom_tcshop_photo')->update($value['id'],$updateData);
                }else{
                    if(isset($_G['uid']) && $_G['uid'] > 0 && $_G['groupid'] == 1){
                        var_dump($r);echo '<hr>';
                        $errorStatus = 1;
                    }else{
                        echo 'qiniu upload error';echo '<hr>';
                        $errorStatus = 1;
                    }
                }
            }
        }
    }
}else{
    $overStatus = 1;
}

if($errorStatus == 1){
    echo 'error ! error ! error ! error !';exit;
}

if($okStatus == 1){
    $page++;
    $ossBatchUrl = $_G['siteurl']."plugin.php?id=tom_tcshop:qiniuBatch";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ossBatchUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $return = curl_exec($ch);
}

if($overStatus){
    echo 'over';exit;
}else{
    echo 'doing... please wait !!!';exit;
}






