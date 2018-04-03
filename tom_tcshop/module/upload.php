<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/tom.upload.php';

if($_GET['act'] == 'picurl' && $_GET['formhash'] == FORMHASH){
    
    $upload = new tom_upload();
    $_FILES["filedata1"]['name'] = addslashes(diconv(urldecode($_FILES["filedata1"]['name']), 'UTF-8'));
    
    if(!$upload->init($_FILES['filedata1'], 'tomwx', random(3, 1), random(8)) || !$upload->save()) {
        echo 'NO|url';exit;
    }
    $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$upload->attach['attachment'];
    echo 'OK|'.$picurl.'|'.$upload->attach['attachment'];exit;
    
}else if($_GET['act'] == 'photo' && $_GET['formhash'] == FORMHASH){

    $upload = new tom_upload();
    $_FILES["filedata2"]['name'] = addslashes(diconv(urldecode($_FILES["filedata2"]['name']), 'UTF-8'));
    
    if(!$upload->init($_FILES['filedata2'], 'tomwx', random(3, 1), random(8)) || !$upload->save()) {
        echo 'NO|url';exit;
    }
    
    $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$upload->attach['attachment'];
    
    ## thumb start
    $imageData = file_get_contents($upload->attach['target']);
    $imageDir = "/source/plugin/tom_tcshop/data/photo/".date("Ym")."/";
    $imageName = "source/plugin/tom_tcshop/data/photo/".date("Ym")."/".md5($upload->attach['attachment']).".jpg";

    $tomDir = DISCUZ_ROOT.'.'.$imageDir;
    if(!is_dir($tomDir)){
        mkdir($tomDir, 0777,true);
    }else{
        chmod($tomDir, 0777);
    }
    file_put_contents(DISCUZ_ROOT.'./'.$imageName, $imageData);
    
    require_once libfile('class/image');
    $image = new image();
    $image->Thumb(DISCUZ_ROOT.'./'.$imageName,'', 640, 320, 2, 1);
    ## thumb end
    
    echo 'OK|'.$picurl.'|'.$upload->attach['attachment'].'|'.$imageName;exit;
    
}else if($_GET['act'] == 'kefuqrcode' && $_GET['formhash'] == FORMHASH){
    
    $upload = new tom_upload();
    $_FILES["filedata3"]['name'] = addslashes(diconv(urldecode($_FILES["filedata3"]['name']), 'UTF-8'));
    
    if(!$upload->init($_FILES['filedata3'], 'tomwx', random(3, 1), random(8)) || !$upload->save()) {
        echo 'NO|url';exit;
    }
    $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$upload->attach['attachment'];
    echo 'OK|'.$picurl.'|'.$upload->attach['attachment'];exit;
    
}else if($_GET['act'] == 'businesslicence' && $_GET['formhash'] == FORMHASH){
    
    $upload = new tom_upload();
    $_FILES["filedata4"]['name'] = addslashes(diconv(urldecode($_FILES["filedata4"]['name']), 'UTF-8'));
    
    if(!$upload->init($_FILES['filedata4'], 'tomwx', random(3, 1), random(8)) || !$upload->save()) {
        echo 'NO|url';exit;
    }
    $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$upload->attach['attachment'];
    echo 'OK|'.$picurl.'|'.$upload->attach['attachment'];exit;
    
}else if($_GET['act'] == 'pinglun_picurl' && $_GET['formhash'] == FORMHASH){
    
    $upload = new tom_upload();
    $_FILES["pinglunPic"]['name'] = addslashes(diconv(urldecode($_FILES["pinglunPic"]['name']), 'UTF-8'));
    
    if(!$upload->init($_FILES['pinglunPic'], 'tomwx', random(3, 1), random(8)) || !$upload->save()) {
        echo 'NO|url';exit;
    }
    $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$upload->attach['attachment'];
    echo 'OK|'.$picurl.'|'.$upload->attach['attachment'];exit;
}else if($_GET['act'] == 'shop_focuspic' && $_GET['formhash'] == FORMHASH){

    $upload = new tom_upload();
    $_FILES["shop_focuspic"]['name'] = addslashes(diconv(urldecode($_FILES["shop_focuspic"]['name']), 'UTF-8'));
    
    if(!$upload->init($_FILES['shop_focuspic'], 'tomwx', random(3, 1), random(8)) || !$upload->save()) {
        echo 'NO|url';exit;
    }
    
    $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$upload->attach['attachment'];
    
    ## thumb start
    $imageData = file_get_contents($upload->attach['target']);
    $imageDir = "/source/plugin/tom_tcshop/data/photo/".date("Ym")."/";
    $imageName = "source/plugin/tom_tcshop/data/photo/".date("Ym")."/".md5($upload->attach['attachment']).".jpg";

    $tomDir = DISCUZ_ROOT.'.'.$imageDir;
    if(!is_dir($tomDir)){
        mkdir($tomDir, 0777,true);
    }else{
        chmod($tomDir, 0777);
    }
    file_put_contents(DISCUZ_ROOT.'./'.$imageName, $imageData);
    
    require_once libfile('class/image');
    $image = new image();
    $image->Thumb(DISCUZ_ROOT.'./'.$imageName,'', 640, 320, 2, 1);
    ## thumb end
    
    echo 'OK|'.$picurl.'|'.$upload->attach['attachment'].'|'.$imageName;exit;
}