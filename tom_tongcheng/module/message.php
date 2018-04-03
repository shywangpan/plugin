<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act  = isset($_GET['act'])? addslashes($_GET['act']):'list';

if($act == 'create' && $_GET['formhash'] == FORMHASH){
    
    $tongcheng_id = isset($_GET['tongcheng_id'])? intval($_GET['tongcheng_id']):0;
    $to_user_id = isset($_GET['to_user_id'])? intval($_GET['to_user_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    
    $max_use_id = $min_use_id = 0;
    if($to_user_id == $__UserInfo['id']){
        dheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=message");exit;
    }else if($to_user_id > $__UserInfo['id']){
        $max_use_id = $to_user_id;
        $min_use_id = $__UserInfo['id'];
    }else if($to_user_id < $__UserInfo['id']){
        $max_use_id = $__UserInfo['id'];
        $min_use_id = $to_user_id;
    }
    $pmListTmp = C::t('#tom_tongcheng#tom_tongcheng_pm_lists')->fetch_all_list(" AND min_use_id={$min_use_id} AND max_use_id={$max_use_id} "," ORDER BY id DESC ",0,1);
    
    if(is_array($pmListTmp) && !empty($pmListTmp[0]['id']) ){
        $pm_lists_id = $pmListTmp[0]['id'];
    }else{
        $insertData = array();
        $insertData['user_id']      = $__UserInfo['id'];
        $insertData['min_use_id']   = $min_use_id;
        $insertData['max_use_id']   = $max_use_id;
        //$insertData['last_content'] = lang("plugin/tom_tongcheng", "template_hello");
        $insertData['last_content'] = 'NULL-NULL-NULL-NULL-NULL-NULL';
        $insertData['last_time']    = TIMESTAMP;
        $insertData['last_time']     = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_pm_lists')->insert($insertData);
        $pm_lists_id = C::t('#tom_tongcheng#tom_tongcheng_pm_lists')->insert_id();
        
//        $insertData = array();
//        $insertData['user_id']      = $__UserInfo['id'];
//        $insertData['pm_lists_id']  = $pm_lists_id;
//        $insertData['content']      = lang("plugin/tom_tongcheng", "template_hello");
//        $insertData['add_time']     = TIMESTAMP;
//        C::t('#tom_tongcheng#tom_tongcheng_pm_message')->insert($insertData);
        
        $insertData = array();
        $insertData['user_id']      = $__UserInfo['id'];
        $insertData['pm_lists_id']  = $pm_lists_id;
        $insertData['new_num']      = 0;
        $insertData['last_time']     = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_pm')->insert($insertData);
        
        $insertData = array();
        $insertData['user_id']      = $to_user_id;
        $insertData['pm_lists_id']  = $pm_lists_id;
        $insertData['new_num']      = 0;
        $insertData['last_time']     = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_pm')->insert($insertData);
        
    }
    
    if($tongchengInfo){
        
        $message = strip_tags($tongchengInfo['content']);
        $message = cutstr(contentFormat($message),50,"...");
        $message = $message.'<a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=info&tongcheng_id='.$tongchengInfo['id'].'">['.lang("plugin/tom_tongcheng", "template_dianjichakan").']</a>';
        
        $insertData = array();
        $insertData['user_id']      = $__UserInfo['id'];
        $insertData['pm_lists_id']  = $pm_lists_id;
        $insertData['content']      = $message;
        $insertData['add_time']     = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_pm_message')->insert($insertData);
        
        DB::query("UPDATE ".DB::table('tom_tongcheng_pm')." SET new_num=new_num+1,last_time='".TIMESTAMP."' WHERE user_id='".$to_user_id."' AND pm_lists_id='$pm_lists_id' ", 'UNBUFFERED');
        
    }
    
    dheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=message&act=sms&pm_lists_id=".$pm_lists_id);exit;
    
    
}else if($act == 'send' && $_GET['formhash'] == FORMHASH){
    
    $content = isset($_GET['content'])? daddslashes(diconv(urldecode($_GET['content']),'utf-8')):'';
    $content = strip_tags($content);
    $pm_lists_id = isset($_GET['pm_lists_id'])? intval($_GET['pm_lists_id']):0;
    $to_user_id = isset($_GET['to_user_id'])? intval($_GET['to_user_id']):0;
    
    $insertData = array();
    $insertData['user_id']      = $__UserInfo['id'];
    $insertData['pm_lists_id']  = $pm_lists_id;
    $insertData['content']      = $content;
    $insertData['add_time']     = TIMESTAMP;
    if(C::t('#tom_tongcheng#tom_tongcheng_pm_message')->insert($insertData)){
        
        $updateData = array();
        $updateData['last_content'] = $content;
        $updateData['last_time'] = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_pm_lists')->update($pm_lists_id,$updateData);
        
        DB::query("UPDATE ".DB::table('tom_tongcheng_pm')." SET new_num=new_num+1,last_time='".TIMESTAMP."' WHERE user_id='$to_user_id' AND pm_lists_id='$pm_lists_id' ", 'UNBUFFERED');
        
        $toUser = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($to_user_id);
        include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/templatesms.class.php';
        $access_token = $weixinClass->get_access_token();
        $nextSmsTime = $toUser['last_smstp_time'] + 60;
        
        if($access_token && !empty($toUser['openid']) && TIMESTAMP > $nextSmsTime ){
            $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=message");
            $message_template_first = str_replace("{NICKNAME}",$__UserInfo['nickname'], lang('plugin/tom_tongcheng','message_template_first'));
            $smsData = array(
                'first'         => $message_template_first,
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
        
        echo '200';exit;
    }else{
        echo '100';exit;
    }
    
}else if($act == 'sms'){
    
    $pm_lists_id    = intval($_GET['pm_lists_id'])>0? intval($_GET['pm_lists_id']):0;
    $page           = intval($_GET['page'])>0? intval($_GET['page']):1;
    
    $pmListsInfo = C::t('#tom_tongcheng#tom_tongcheng_pm_lists')->fetch_by_id($pm_lists_id);
    if($pmListsInfo['min_use_id'] == $__UserInfo['id']){
        $toUserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($pmListsInfo['max_use_id']);
    }else{
        $toUserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($pmListsInfo['min_use_id']);
    }
    
    $pagesize = 10;
    $start = ($page-1)*$pagesize;
    
    $messageListTmp = C::t('#tom_tongcheng#tom_tongcheng_pm_message')->fetch_all_list(" AND pm_lists_id={$pm_lists_id} "," ORDER BY add_time DESC,id DESC ",$start,$pagesize);
    $count = C::t('#tom_tongcheng#tom_tongcheng_pm_message')->fetch_all_count(" AND pm_lists_id={$pm_lists_id} ");
    $messageList = array();
    if(is_array($messageListTmp) && !empty($messageListTmp)){
        asort($messageListTmp);
        foreach ($messageListTmp as $key => $value){
            $messageList[$key] = $value;
            $messageList[$key]['content'] = strip_tags($value['content'],'<a><br/>');
            $userInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']);
            $messageList[$key]['userInfo'] = $userInfoTmp;
        }
    }
    
    DB::query("UPDATE ".DB::table('tom_tongcheng_pm')." SET new_num=0 WHERE user_id='{$__UserInfo['id']}' AND pm_lists_id='$pm_lists_id' ", 'UNBUFFERED');
    
    $showNextPage = 1;
    if(($start + $pagesize) >= $count){
        $showNextPage = 0;
    }
    $allPageNum = ceil($count/$pagesize);
    $prePage = $page - 1;
    $nextPage = $page + 1;
    $prePageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=message&act=sms&pm_lists_id={$pm_lists_id}&page={$prePage}";
    $nextPageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=message&act=sms&pm_lists_id={$pm_lists_id}&page={$nextPage}";
    
    $smsUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=message";
    
}else if($act == 'tz'){
    
    $page           = intval($_GET['page'])>0? intval($_GET['page']):1;
    
    $pagesize = 10;
    $start = ($page-1)*$pagesize;
    
    $tzListTmp = C::t('#tom_tongcheng#tom_tongcheng_tz')->fetch_all_list(" AND user_id='{$__UserInfo['id']}' "," ORDER BY id DESC ",$start,$pagesize);
    $count = C::t('#tom_tongcheng#tom_tongcheng_tz')->fetch_all_count(" AND user_id='{$__UserInfo['id']}' ");
    $tzList = array();
    if(is_array($tzListTmp) && !empty($tzListTmp)){
        foreach ($tzListTmp as $key => $value){
            $tzList[$key] = $value;
            $tzList[$key]['content'] = stripslashes($value['content']);
        }
    }
    
    DB::query("UPDATE ".DB::table('tom_tongcheng_tz')." SET is_read=1 WHERE user_id='{$__UserInfo['id']}' ", 'UNBUFFERED');
    
    $showNextPage = 1;
    if(($start + $pagesize) >= $count){
        $showNextPage = 0;
    }
    $allPageNum = ceil($count/$pagesize);
    $prePage = $page - 1;
    $nextPage = $page + 1;
    $prePageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=message&act=tz&page={$prePage}";
    $nextPageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=message&act=tz&page={$nextPage}";
    
}else{
    
    $tzCount = C::t('#tom_tongcheng#tom_tongcheng_tz')->fetch_all_count(" AND user_id='{$__UserInfo['id']}' ");
    $noReadTzCount = C::t('#tom_tongcheng#tom_tongcheng_tz')->fetch_all_count(" AND user_id='{$__UserInfo['id']}' AND is_read=0 ");
    $lastTzListTmp = C::t('#tom_tongcheng#tom_tongcheng_tz')->fetch_all_list(" AND user_id='{$__UserInfo['id']}' "," ORDER BY id DESC ",0,1);
    $lastTzList = array();
    if(is_array($lastTzListTmp) && !empty($lastTzListTmp[0])){
        $lastTzList = $lastTzListTmp[0];
        $lastTzList['content'] = strip_tags($lastTzListTmp[0]['content']);
    }
    
    $ajaxLoadPmListUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=loadPmlist&formhash='.$formhash;
    
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:message");  




