<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/Result.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/OssClient.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/XmlConfig.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Core/MimeTypes.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Core/OssException.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Core/OssUtil.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Http/RequestCore.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Http/RequestCore_Exception.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Http/ResponseCore.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/BucketInfo.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/BucketListInfo.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/CorsConfig.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/CorsRule.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/GetLiveChannelHistory.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/GetLiveChannelInfo.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/GetLiveChannelStatus.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/LifecycleAction.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/LifecycleConfig.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/LifecycleRule.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/ListMultipartUploadInfo.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/ListPartsInfo.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/LiveChannelConfig.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/LiveChannelHistory.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/LiveChannelInfo.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/LiveChannelListInfo.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/LoggingConfig.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/ObjectInfo.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/ObjectListInfo.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/PartInfo.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/PrefixInfo.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/RefererConfig.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/UploadInfo.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/WebsiteConfig.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Model/CnameConfig.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/PutSetDeleteResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/AclResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/AppendResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/BodyResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/CallbackResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/CopyObjectResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/DeleteObjectsResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/ExistResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/GetCnameResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/GetCorsResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/GetLifecycleResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/GetLiveChannelHistoryResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/GetLiveChannelInfoResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/GetLiveChannelStatusResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/GetLoggingResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/GetRefererResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/GetWebsiteResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/HeaderResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/InitiateMultipartUploadResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/ListBucketsResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/ListLiveChannelResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/ListMultipartUploadResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/ListObjectsResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/ListPartsResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/PutLiveChannelResult.php';
include_once DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/OSS/Result/UploadPartResult.php';

use \OSS\OssClient;

function oss_upload($name, $file){
    
    $return = array('picurl'=>'');
    
    try{
        
        $ossClient = new OssClient(TOM_OSS_ACCESS_ID, TOM_OSS_ACCESS_KEY, TOM_OSS_ENDPOINT, false);
        $ret = $ossClient->uploadFile(TOM_OSS_BUCKET, $name, $file);
        
        if(is_array($ret) && isset($ret['oss-request-url']) && !empty($ret['oss-request-url'])){
            $return['picurl'] = $ret['oss-request-url'];
        }else{
            $return['ret'] = $ret;
        }
            
    }catch (Exception $e){
        $return['e'] = $e->getMessage();
    }
    
    return $return;
}

function oss_fileext($filename) {
    return addslashes(strtolower(substr(strrchr($filename, '.'), 1, 10)));
}

function oss_is_image_ext($ext) {
    static $imgext  = array('jpg', 'png', 'bmp', 'gif', 'webp', 'tiff');
    return in_array($ext, $imgext) ? 1 : 0;
}