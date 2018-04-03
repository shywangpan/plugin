<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

# check start
if($__UserInfo['groupid'] == 1 || $__UserInfo['groupid'] == 2 ){
}else{
    tomheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=index");exit;
}
# check end

if($_GET['act'] == 'shenhe' && $_GET['formhash'] == FORMHASH){
    
    if('utf-8' != CHARSET) {
        if(defined('IN_MOBILE')){
        }else{
            foreach($_POST AS $pk => $pv) {
                if(!is_numeric($pv)) {
                    $_GET[$pk] = $_POST[$pk] = wx_iconv_recurrence($pv);	
                }
            }
        }
    }
    
    $tongcheng_id = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    $shenhe_status = intval($_GET['shenhe_status'])>0? intval($_GET['shenhe_status']):0;
    $content        = isset($_GET['content'])? daddslashes($_GET['content']):'';
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    
    $updateData = array();
    $updateData['shenhe_status']      = $shenhe_status;
    C::t('#tom_tongcheng#tom_tongcheng')->update($tongcheng_id,$updateData);
    
    $toUser = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengInfo['user_id']);
    
    if($tongchengInfo){
        $message = strip_tags($tongchengInfo['content']);
        $message = cutstr(contentFormat($message),50,"...");
        $message = $message.'<br/><a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=info&tongcheng_id='.$tongchengInfo['id'].'">['.lang("plugin/tom_tongcheng", "template_dianjichakan").']</a>';
        
        $insertData = array();
        $insertData['user_id']      = $toUser['id'];
        $insertData['type']     = 1;
        if($shenhe_status == 1){
            $insertData['content']      = '<b><font color="#238206">'.lang("plugin/tom_tongcheng", "shenhe_status_1_title").'</font></b><br/>'.$message;
        }else if($shenhe_status == 3){
            $insertData['content']      = '<b><font color="#fd0d0d">'.lang("plugin/tom_tongcheng", "shenhe_status_3_title").'</font></b><br/><font color="#8e8e8e">'.$content.'</font><br/>'.$message;
        }
        $insertData['is_read']     = 0;
        $insertData['tz_time']     = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_tz')->insert($insertData);
        
    }
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/templatesms.class.php';
    $access_token = $weixinClass->get_access_token();
    $nextSmsTime = $toUser['last_smstp_time'] + 0;
    
    $content = strip_tags($tongchengInfo['content']);
    $content = cutstr(contentFormat($content),20,"...");

    if($access_token && !empty($toUser['openid']) && TIMESTAMP > $nextSmsTime ){
        $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=message");
        if($shenhe_status == 1){
            $template_first = lang("plugin/tom_tongcheng", "shenhe_status_1_title");
        }else if($shenhe_status == 3){
            $template_first = lang("plugin/tom_tongcheng", "shenhe_status_3_title");
        }
        $smsData = array(
            'first'         => $template_first,
            'keyword1'      => $__SitesInfo['name'],
            'keyword2'      => $content,
            'remark'        => ''
        );
        $r = $templateSmsClass->sendSms01($toUser['openid'],$tongchengConfig['template_id'],$smsData);

        if($r){
            $updateData = array();
            $updateData['last_smstp_time'] = TIMESTAMP;
            C::t('#tom_tongcheng#tom_tongcheng_user')->update($toUser['id'],$updateData);
        }

    }
    
    echo 200;exit;
    
}else if($_GET['act'] == 'fenhao' && $_GET['formhash'] == FORMHASH){
    
    $tongcheng_id = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    
    $updateData = array();
    $updateData['status']     = 2;
    C::t('#tom_tongcheng#tom_tongcheng_user')->update($tongchengInfo['user_id'],$updateData);
    
    echo 200;exit;
    
}else if($_GET['act'] == 'jiefen' && $_GET['formhash'] == FORMHASH){
    
    $tongcheng_id = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    
    $updateData = array();
    $updateData['status']     = 1;
    C::t('#tom_tongcheng#tom_tongcheng_user')->update($tongchengInfo['user_id'],$updateData);
    
    echo 200;exit;
    
}else if($_GET['act'] == 'get_search_url' && $_GET['formhash'] == FORMHASH){
    
    $keyword = isset($_GET['keyword'])? daddslashes(diconv(urldecode($_GET['keyword']),'utf-8')):'';
    
    $url = $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=managerList&keyword=".urlencode(trim($keyword));
    
    echo $url;exit;
    
}else if($_GET['act'] == 'shenhe_show'){
    
    $tongcheng_id = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    
    $content = contentFormat($tongchengInfo['content']);
    
    $ajaxShenheUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=managerList&act=shenhe&";
    
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_tongcheng:managerShenhe");exit;
    
}


$keyword  = isset($_GET['keyword'])? addslashes(urldecode($_GET['keyword'])):'';
$page  = intval($_GET['page'])>0? intval($_GET['page']):1;
$type  = intval($_GET['type'])>0? intval($_GET['type']):0;

$where = " AND pay_status!=1 ";
if($type == 1){
    $where.= " AND shenhe_status=2 ";
}
if($type == 2){
    $where.= " AND shenhe_status=3 ";
}
if($__UserInfo['groupid'] == 2){
    $where.= " AND site_id={$site_id} ";
}

$pagesize = 8;
$start = ($page - 1)*$pagesize;

$count = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count(" {$where} ",$keyword);
$tongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list(" {$where} "," ORDER BY refresh_time DESC,id DESC ",$start,$pagesize,$keyword);

$tongchengList = array();
foreach ($tongchengListTmp as $key => $value) {
    $tongchengList[$key] = $value;
    $tongchengList[$key]['content'] = contentFormat($value['content']);

    $userInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']); 
    $typeInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($value['type_id']);
    $siteInfoTmp = array('id'=>1,'name'=>$tongchengConfig['plugin_name']);
    if($value['site_id'] > 1){
        $siteInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($value['site_id']);
    }

    $tongchengAttrListTmp = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY paixu ASC,id DESC ",0,50);
    $tongchengTagListTmp = C::t('#tom_tongcheng#tom_tongcheng_tag')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY id DESC ",0,50);
    $tongchengPhotoListTmpTmp = C::t('#tom_tongcheng#tom_tongcheng_photo')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY id ASC ",0,50);
    $tongchengPhotoListTmp = array();
    if(is_array($tongchengPhotoListTmpTmp) && !empty($tongchengPhotoListTmpTmp)){
        foreach ($tongchengPhotoListTmpTmp as $kk => $vv){
            if(!preg_match('/^http/', $vv['picurl']) ){
                if(strpos($vv['picurl'], 'source/plugin/tom_tongcheng/data/') === false){
                    $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$vv['picurl'];
                }else{
                    $picurl = $vv['picurl'];
                }
            }else{
                $picurl = $vv['picurl'];
            }
            $tongchengPhotoListTmp[$kk] = $picurl;
        }
    }
    $tongchengList[$key]['userInfo'] = $userInfoTmp;
    $tongchengList[$key]['typeInfo'] = $typeInfoTmp;
    $tongchengList[$key]['attrList'] = $tongchengAttrListTmp;
    $tongchengList[$key]['tagList'] = $tongchengTagListTmp;
    $tongchengList[$key]['photoList'] = $tongchengPhotoListTmp;
    $tongchengList[$key]['siteInfo'] = $siteInfoTmp;
    
    $payRefreshStatus = 0;
    $shengyuRefreshTimes = 0;
    if($tongchengConfig['free_refresh_times'] > 0){
        $refresh_log_count = C::t('#tom_tongcheng#tom_tongcheng_refresh_log')->fetch_all_count(" AND tongcheng_id={$value['id']} AND time_key={$nowDayTime} ");
        if($tongchengConfig['free_refresh_times'] > $refresh_log_count){
            $shengyuRefreshTimes = $tongchengConfig['free_refresh_times'] - $refresh_log_count;
        }else{
            $payRefreshStatus = 1;
        }
    }else{
        $payRefreshStatus = 1;
    }
    
    $tongchengList[$key]['payRefreshStatus'] = $payRefreshStatus;
    $tongchengList[$key]['shengyuRefreshTimes'] = $shengyuRefreshTimes;
    
}

$showNextPage = 1;
if(($start + $pagesize) >= $count){
    $showNextPage = 0;
}
$allPageNum = ceil($count/$pagesize);
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=managerList&type={$type}&page={$prePage}&keyword=".$_GET['keyword'];
$nextPageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=managerList&type={$type}&page={$nextPage}&keyword=".$_GET['keyword'];

$ajaxShenheUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=managerList&act=shenhe&&formhash=".$formhash;
$ajaxFenhaoUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=managerList&act=fenhao&&formhash=".$formhash;
$ajaxJiefenUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=managerList&act=jiefen&&formhash=".$formhash;

$searchUrl = 'plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=managerList&act=get_search_url';


$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:managerList");  




