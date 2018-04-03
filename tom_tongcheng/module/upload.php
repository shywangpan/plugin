<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$server_yasu_size = 720;
if($tongchengConfig['server_yasu_size'] > 0){
    $server_yasu_size = $tongchengConfig['server_yasu_size'];
}

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/tom.upload.php';

if($_GET['act'] == 'photo' && $_GET['formhash'] == FORMHASH){
    
    $upload = new tom_upload();
    $_FILES["filedata1"]['name'] = addslashes(diconv(urldecode($_FILES["filedata1"]['name']), 'UTF-8'));
    
    if(!getimagesize($_FILES['filedata1']['tmp_name']) || !$upload->init($_FILES['filedata1'], 'tomwx', random(3, 1), random(8)) || !$upload->save()) {
        echo 'NO|url';exit;
    }
    /*if($tongchengConfig['server_yasu'] == 1){
        require_once libfile('class/image');
        $image = new image();
        $image->Thumb($upload->attach['target'], '', $server_yasu_size, $server_yasu_size, 1, 1); 
    }*/
    $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$upload->attach['attachment'];
    echo 'OK|'.$picurl.'|'.$upload->attach['attachment'];exit;
    
}else if($_GET['act'] == 'avatar' && $_GET['formhash'] == FORMHASH){

    $upload = new tom_upload();
    $_FILES["filedata"]['name'] = addslashes(diconv(urldecode($_FILES["filedata"]['name']), 'UTF-8'));
    
    if(!getimagesize($_FILES['filedata']['tmp_name']) || !$upload->init($_FILES['filedata'], 'tomwx', random(3, 1), random(8)) || !$upload->save()) {
        echo 'NO|url';exit;
    }
    
    require_once libfile('class/image');
    $image = new image();
    $image->Thumb($upload->attach['target'], '', 200, 200, 2, 1);
    
    $picurl = 'data/attachment/tomwx/'.$upload->attach['attachment'];
    echo 'OK|'.$picurl;exit;
    
}