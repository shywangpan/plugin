<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$modelWhereStr = " AND is_show=1 ";
if($site_id > 1){
    if(!empty($__SitesInfo['model_ids'])){
        $modelWhereStr.= " AND id IN({$__SitesInfo['model_ids']}) ";
    }
}else{
    $modelWhereStr.= " AND sites_show=0 ";
}

$modelListTmp = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" {$modelWhereStr} "," ORDER BY paixu ASC,id DESC ",0,50);
    
$modelList = array();
$i = 1;
if(is_array($modelListTmp) && !empty($modelListTmp)){
    foreach ($modelListTmp as $key => $value){
        $modelList[$key] = $value;
        if(!preg_match('/^http/', $value['picurl']) ){
            if(strpos($value['picurl'], 'source/plugin/tom_tongcheng/') === FALSE){
                $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
            }else{
                $picurl = $value['picurl'];
            }
        }else{
            $picurl = $value['picurl'];
        }
        $modelList[$key]['picurl'] = $picurl;
        $modelList[$key]['i'] = $i;
        $i++;
    }
}

$shareUrl   = $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=search";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:search");  




