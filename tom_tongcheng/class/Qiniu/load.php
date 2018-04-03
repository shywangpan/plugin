<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Http/Client.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Http/Error.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Http/Request.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Http/Response.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Processing/ImageUrlBuilder.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Processing/Operation.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Processing/PersistentFop.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Storage/BucketManager.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Storage/FormUploader.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Storage/ResumeUploader.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Storage/UploadManager.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Auth.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Config.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Etag.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/functions.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/Qiniu/Zone.php';

// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;


function qiniu_upload($name, $file){
    
    $return = array('picurl'=>'');
    
    try{
        
        // 构建鉴权对象
        $auth = new Auth(TOM_QINIU_ACCESS_KEY,TOM_QINIU_SECRET_KEY);

        // 生成上传 Token
        $token = $auth->uploadToken(TOM_QINIU_BUCKET);

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $name, $file);
        
        if ($err !== null) {
            $return['err'] = $err->message();
        }else{
            $return['ret'] = $ret;
            if(isset($ret['key']) && !empty($ret['key'])){
                $return['picurl'] = rtrim(TOM_QINIU_URL, '/').'/'.$ret['key'];
            }
        }
        
    }catch (Exception $e){
        $return['e'] = $e->getMessage();
    }
    
    return $return;
}

function qiniu_fileext($filename) {
    return addslashes(strtolower(substr(strrchr($filename, '.'), 1, 10)));
}

function qiniu_is_image_ext($ext) {
    static $imgext  = array('jpg', 'png', 'bmp', 'gif', 'webp', 'tiff');
    return in_array($ext, $imgext) ? 1 : 0;
}