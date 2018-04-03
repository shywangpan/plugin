<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$site_id = intval($_GET['site'])>0? intval($_GET['site']):1;

session_start();
loaducenter();
$formhash = FORMHASH;
$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];
$tomSysOffset = getglobal('setting/timeoffset');
$appid = trim($tongchengConfig['wxpay_appid']);  
$appsecret = trim($tongchengConfig['wxpay_appsecret']);

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/weixin.class.php';
$weixinClass = new weixinClass($appid,$appsecret);

if($_GET['act'] == "photo" && $_GET['formhash'] == $formhash){
    $outAtr = array(
        'status' => 1,
    );
    
    $media_id = isset($_GET['serverId'])? daddslashes($_GET['serverId']): 0;
    
    $access_token = $weixinClass->get_access_token();
    $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$media_id}";
    $return = curl_request($url);
    $types = array('image/bmp'=>'.bmp', 'image/gif'=>'.gif', 'image/jpeg'=>'.jpg', 'image/png'=>'.png');
    if(isset($types[$return['info']['content_type']])){
        
        $imageDir = "/source/plugin/tom_tongcheng/data/photo/".date("Ym")."/".date("d")."/";
        $imageName = "source/plugin/tom_tongcheng/data/photo/".date("Ym")."/".date("d")."/".md5($media_id).$types[$return['info']['content_type']];
        
        $tomDir = DISCUZ_ROOT.'.'.$imageDir;
        if(!is_dir($tomDir)){
            mkdir($tomDir, 0777,true);
        }else{
            chmod($tomDir, 0777);
        }
        if(false !== file_put_contents(DISCUZ_ROOT.'./'.$imageName, $return['content'])){
            require_once libfile('class/image');
            $image = new image();
            $image->Thumb(DISCUZ_ROOT.'./'.$imageName,'', 720, 720, 1, 1);
            $outAtr = array(
                'status' => 200,
                'picurl' => $imageName,
            );
        }else{
            $outAtr = array(
                'status' => 302,
                'picurl' => $imageName,
            );
        }
        
    }else{
        $outAtr = array(
            'status' => 301,
        );
    }
    
    echo json_encode($outAtr); exit;
}else{
    echo 1; exit;
}

function curl_request($url){
    if(function_exists('curl_init')){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $package = curl_exec($ch);
        $httpInfo = curl_getinfo($ch);
        curl_close($ch); 
        $return = array_merge(array('content' => $package), array('info' => $httpInfo));
        return $return;
    }
    return false;
}



