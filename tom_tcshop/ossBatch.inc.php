<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
   阿里云OSS 图片上传脚本
   图片裁剪文档 ：https://help.aliyun.com/document_detail/44688.html
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

ignore_user_abort(true);
set_time_limit(0);

$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];

$alioss_access_id   = trim($tongchengConfig['alioss_access_id']);
$alioss_access_key  = trim($tongchengConfig['alioss_access_key']);
$alioss_endpoint    = trim($tongchengConfig['alioss_endpoint']);
$alioss_buckey      = trim($tongchengConfig['alioss_buckey']);

define("TOM_OSS_ACCESS_ID", $alioss_access_id);  // [阿里OSS]ACCESS_ID
define("TOM_OSS_ACCESS_KEY", $alioss_access_key); // [阿里OSS]ACCESS_KEY
define("TOM_OSS_ENDPOINT", $alioss_endpoint); // [阿里OSS]ENDPOINT
define("TOM_OSS_BUCKET", $alioss_buckey); // [阿里OSS]BUCKET

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/OSS/load.php';

$whereStr   = " AND oss_status=0 ";
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
                $fileext = oss_fileext($filepicurl);
                if(oss_is_image_ext($fileext)){
                }else{
                    $fileext = 'jpg';
                }
                $objname = md5($filepicurl).'.'.$fileext;
                @$r = oss_upload($objname,$filepicurl);
                if(!empty($r['picurl'])){
                    $okStatus = 1;
                    $updateData = array();
                    $updateData['oss_status'] = 1;
                    $updateData['oss_picurl'] = $r['picurl'];
                    C::t('#tom_tcshop#tom_tcshop_photo')->update($value['id'],$updateData);
                }else{
                    if(isset($_G['uid']) && $_G['uid'] > 0 && $_G['groupid'] == 1){
                        var_dump($r);echo '<hr>';
                        $errorStatus = 1;
                    }else{
                        echo 'oss upload error';echo '<hr>';
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
    $ossBatchUrl = $_G['siteurl']."plugin.php?id=tom_tcshop:ossBatch";
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






