<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(isset($_GET['xxid'])){
    $tongcheng_id = intval($_GET['xxid'])>0? intval($_GET['xxid']):0;
}else{
    $tongcheng_id = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
}

$tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);

if(!$tongchengInfo){
    tomheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=index");exit;
}

if($tongchengInfo['shenhe_status'] != 1){
    tomheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=mylist&type=4");exit;
}

if($tongchengInfo['status'] != 1){
    tomheader('location:'.$_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=index");exit;
}

$open_edit_pinglun = 0;
if($tongchengInfo['user_id'] == $__UserInfo['id'] && $tongchengConfig['open_fbr_remove_pinglun'] == 1){
    $open_edit_pinglun = 1;
}else if($__UserInfo['groupid'] == 1 && $site_id == 1){
    $open_edit_pinglun = 1;
}else if($__UserInfo['groupid'] == 2 && $site_id == $__UserInfo['groupsiteid']){
    $open_edit_pinglun = 1;
}

$contentInfo = C::t('#tom_tongcheng#tom_tongcheng_content')->fetch_by_tongcheng_id($tongchengInfo['id']);
$showNewContent = 0;
$newContent = '';
if($contentInfo && $contentInfo['is_show'] == 1){
    $showNewContent = 1;
    $newContent = stripslashes($contentInfo['content']);
}

$content = contentFormat($tongchengInfo['content']);
$contentTmp = strip_tags($content);
$contentTmp = str_replace("\r\n","",$contentTmp);
$contentTmp = str_replace("\n","",$contentTmp);
$contentTmp = str_replace("\r","",$contentTmp);

$content = str_replace("\r\n","<br/>",$content);
$content = str_replace("\n","<br/>",$content);
$content = str_replace("\r","<br/>",$content);

$title = cutstr($contentTmp,20,"...");
$desc = cutstr($contentTmp,80,"...");
if(empty($tongchengInfo['title'])){
    $tongchengInfo['title'] = cutstr($contentTmp,80,"...");
}
if($tongchengConfig['open_fabu_title'] == 0){
    $tongchengInfo['title'] = cutstr($contentTmp,80,"...");
}

$addressStr = "";
$areaNameTmp = '';
if(!empty($tongchengInfo['area_id'])){
    $areaInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($tongchengInfo['area_id']);
    $areaNameTmp = $areaInfoTmp['name'];
}
$streetNameTmp = '';
if(!empty($tongchengInfo['street_id'])){
    $streetInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($tongchengInfo['street_id']);
    $streetNameTmp = $streetInfoTmp['name'];
}
if(!empty($areaNameTmp) && !empty($streetNameTmp)){
    $addressStr = $areaNameTmp." ".$streetNameTmp;
}

$userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengInfo['user_id']); 
$modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($tongchengInfo['model_id']);
$typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);
$cateInfo = array();
if($tongchengInfo['cate_id'] > 0){
    $cateInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_by_id($tongchengInfo['cate_id']);
    if($cateInfoTmp){
        $cateInfo = $cateInfoTmp;
    }
}
$siteInfo = array('id'=>1,'name'=>$tongchengConfig['plugin_name']);
if($tongchengInfo['site_id'] > 1){
    $siteInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($tongchengInfo['site_id']);
}

$zhapian_msg = $tongchengConfig['zhapian_msg'];
if(!empty($typeInfo['zhapian_msg'])){
    $zhapian_msg = $typeInfo['zhapian_msg'];
}

$attrList = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND tongcheng_id={$tongchengInfo['id']} "," ORDER BY paixu ASC,id DESC ",0,50);
$tagList = C::t('#tom_tongcheng#tom_tongcheng_tag')->fetch_all_list(" AND tongcheng_id={$tongchengInfo['id']} "," ORDER BY id DESC ",0,50);
$photoCount = C::t('#tom_tongcheng#tom_tongcheng_photo')->fetch_all_count(" AND tongcheng_id={$tongchengInfo['id']} ");
$photoListTmp = C::t('#tom_tongcheng#tom_tongcheng_photo')->fetch_all_list(" AND tongcheng_id={$tongchengInfo['id']} "," ORDER BY id ASC ",0,50);
$photoList = array();
if(is_array($photoListTmp) && !empty($photoListTmp)){
    foreach ($photoListTmp as $kk => $vv){
        if($tongchengConfig['open_yun'] == 2 && !empty($vv['oss_picurl'])){
            $picurl = $vv['oss_picurl'];
        }else if($tongchengConfig['open_yun'] == 3 && !empty($vv['qiniu_picurl'])){
            $picurl = $vv['qiniu_picurl'];
        }else{
            if(!preg_match('/^http/', $vv['picurl']) ){
                if(strpos($vv['picurl'], 'source/plugin/tom_tongcheng/data/') === false){
                    $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$vv['picurl'];
                }else{
                    $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$vv['picurl'];
                }
            }else{
                $picurl = $vv['picurl'];
            }
        }
        $photoList[$kk] = $picurl;
    }
}
$photoListStr = implode('|', $photoList);

$modelListTmp = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" AND is_show=1 "," ORDER BY paixu ASC,id DESC ",0,50);
    
$modelList = array();
$i = 1;
$modelCount = 0;
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
        $modelCount++;
    }
}
$tcCount = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count(" AND user_id={$userInfo['id']} AND status=1 ");
DB::query("UPDATE ".DB::table('tom_tongcheng')." SET clicks=clicks+1 WHERE id='$tongcheng_id' ", 'UNBUFFERED');

$pinglunListTmp = C::t('#tom_tongcheng#tom_tongcheng_pinglun')->fetch_all_list(" AND tongcheng_id = {$tongchengInfo['id']} ", 'ORDER BY ping_time DESC,id DESC', 0, 5);
$pinglunList = array();
if(is_array($pinglunListTmp) && !empty($pinglunListTmp)){
    foreach($pinglunListTmp as $key => $value){
        $pinglunList[$key] = $value;
        $pinglunList[$key]['content'] = qqface_replace(dhtmlspecialchars($value['content']));
        $pinglunList[$key]['userInfo'] = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']);
        $pinglunList[$key]['reply_list'] = '';
        $replyListTmp = C::t('#tom_tongcheng#tom_tongcheng_pinglun_reply')->fetch_all_list(" AND tongcheng_id = {$tongchengInfo['id']} AND ping_id = {$value['id']} ", "ORDER BY reply_time ASC,id ASC", 0, 1000);
        if(is_array($replyListTmp) && !empty($replyListTmp)){
            $pinglunList[$key]['reply_list'] .= '<div class="comment_reply_pinglun_box">';
            foreach($replyListTmp as $k => $v){
                if($tongchengInfo['user_id'] == $v['reply_user_id']){
                    $pinglunList[$key]['reply_list'].= '<div class="comment-item-content-text" id="comment-item-content-text_'.$v['id'].'"><span>'.$v['reply_user_nickname'].'&nbsp;<span class="floor_main">'.lang('plugin/tom_tongcheng', 'info_pinglun_floor_main').'</span>'.lang('plugin/tom_tongcheng','pinglun_hueifu_dian').'&nbsp;</span>'.qqface_replace(dhtmlspecialchars($v['content'])).'&nbsp;&nbsp;<span class="remove" onClick="removeReply('.$v['id'].');">'.lang('plugin/tom_tongcheng','info_comment_del').'</span></div>'; 
                }else{
                    $pinglunList[$key]['reply_list'].= '<div class="comment-item-content-text" id="comment-item-content-text_'.$v['id'].'"><span>'.$v['reply_user_nickname'].lang('plugin/tom_tongcheng','pinglun_hueifu_dian').'&nbsp;</span>'.qqface_replace(dhtmlspecialchars($v['content'])).'&nbsp;&nbsp;<span class="remove" onClick="removeReply('.$v['id'].');">'.lang('plugin/tom_tongcheng','info_comment_del').'</span></div>'; 
                }
            }  
            $pinglunList[$key]['reply_list'] .= '</div>';
        }
    }
}
$must_phone_projectArr = unserialize($tongchengConfig['must_phone_project']);
$showMustPhoneBtn = 0;
if(array_search('2',$must_phone_projectArr) !== false && empty($__UserInfo['tel']) && $__UserInfo['editor']==0 && $__UserInfo['is_majia']==0){
    $showMustPhoneBtn = 1;
    $phone_back_url = $weixinClass->get_url();
    $phone_back_url = urlencode($phone_back_url);
    $phoneUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=phone&phone_back={$phone_back_url}";
}

$shareUrl   = $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=info&tongcheng_id=".$tongcheng_id;
if(strpos($userInfo['picurl'], 'data/attachment/tomwx') !== false){
    $shareLogo  = $_G['siteurl'].$userInfo['picurl'];
}else if(strpos($userInfo['picurl'], 'uc_server/') !== false){
    $shareLogo  = $_G['siteurl'].$userInfo['picurl'];
}else{
    $shareLogo  = $userInfo['picurl'];
}

$kefuQrcodeSrc = $tongchengConfig['kefu_qrcode'];
if($__SitesInfo['id'] > 1){
    if(!preg_match('/^http/', $__SitesInfo['kefu_qrcode'])){
        if(strpos($__SitesInfo['kefu_qrcode'], 'source/plugin/tom_tongcheng/') === FALSE){
            $kefuQrcodeSrc = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$__SitesInfo['kefu_qrcode'];
        }else{
            $kefuQrcodeSrc = $__SitesInfo['kefu_qrcode'];
        }
    }else{
        $kefuQrcodeSrc = $__SitesInfo['kefu_qrcode'];
    }
}

if(is_array($photoList) && !empty($photoList) && !empty($photoList[0])){
    $shareLogo = $photoList[0];
}

$isCollect = 0;
if($__UserInfo){
    $collectListTmp = C::t('#tom_tongcheng#tom_tongcheng_collect')->fetch_all_list(" AND user_id={$__UserInfo['id']} AND tongcheng_id={$tongcheng_id} "," ORDER BY id DESC ",0,1);
    if(is_array($collectListTmp) && !empty($collectListTmp)){
        $isCollect = 1;
    }
}

if($__ShowTcshop == 1){
    $ajaxLoadShopListUrl = "plugin.php?id=tom_tcshop:ajax&site={$site_id}&act=list&user_id={$userInfo['id']}&formhash=".$formhash;
}

$lqHongbaoStatus = 0;
$tchongbaoInfo = array();
$tchongbaoLogCount = 0;
$tchongbaoLogList = array();
$openLocaltionDistance = 0;
if($__ShowTchongbao == 1){
    $tchongbaoInfoTmp = C::t('#tom_tchongbao#tom_tchongbao')->fetch_all_list(" AND tongcheng_id = {$tongcheng_id} AND pay_status = 2 AND only_show = 1 ", 'ORDER BY add_time DESC,id DESC', 0, 1);
    if(is_array($tchongbaoInfoTmp) && !empty($tchongbaoInfoTmp[0])){
        $tchongbaoInfo = $tchongbaoInfoTmp[0];
        
        $tchongbaoLogCount = C::t('#tom_tchongbao#tom_tchongbao_qiang_log')->fetch_all_count(" AND hongbao_id = {$tchongbaoInfo['id']} ");
        $tchongbaoLogListTmp = C::t('#tom_tchongbao#tom_tchongbao_qiang_log')->fetch_all_list(" AND hongbao_id = {$tchongbaoInfo['id']} ", 'ORDER BY log_time DESC,id DESC', 0, 10);
        if(is_array($tchongbaoLogListTmp) && !empty($tchongbaoLogListTmp[0])){
            foreach($tchongbaoLogListTmp as $key => $value){
                $tchongbaoLogList[$key] = $value;
            }
        }
        
        if($tchongbaoLogCount >= $tchongbaoInfo['hb_count'] || $tchongbaoInfo['money'] <= 0){
            $updateData = array();
            $updateData['status'] = 2;
            C::t('#tom_tchongbao#tom_tchongbao')->update($tchongbaoInfo['id'], $updateData);
            $tchongbaoInfo['status'] = 2;
        }
        
        if($__UserInfo){
            $hongbaoLogInfo = C::t('#tom_tchongbao#tom_tchongbao_qiang_log')->fetch_all_list(" AND hongbao_id = {$tchongbaoInfo['id']} AND user_id = {$__UserInfo['id']} ", 'ORDER BY id DESC', 0, 1);
            if(is_array($hongbaoLogInfo) && !empty($hongbaoLogInfo[0])){
                $lqHongbaoStatus = 1;
            }
        }
        
        if($__UserInfo['hongbao_tz'] == 0 && $__UserInfo['hongbao_tz_first'] != 1){
            $updateData = array();
            $updateData['hongbao_tz'] = 1;
            $updateData['hongbao_tz_first'] = 1;
            C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'], $updateData);
        }

        if($tchongbaoConfig['open_hb_position'] == 1){
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false){
                if(!empty($tchongbaoConfig['baidu_js_ak'])){
                    $openLocaltionDistance = 1;
                }else{
                    $openLocaltionDistance = 3;
                }
            }else{
                $openLocaltionDistance = 2;
            }
            
            $hongbaoLocationInfo = C::t('#tom_tchongbao#tom_tchongbao_location')->fetch_by_user_id($__UserInfo['id']);
            $hongbaoLocationStatus = 0;
            if(is_array($hongbaoLocationInfo) && !empty($hongbaoLocationInfo)){
                $overTime = ($tchongbaoConfig['hongbao_update_location_num'] * 86400) + $hongbaoLocationInfo['last_time'];
                if($overTime > TIMESTAMP){
                    if($hongbaoLocationInfo['location_status'] == 1){
                        $hongbaoLocationStatus = 1;
                    }else{
                        $hongbaoLocationStatus = 2;
                    }
                }
            }
        }
        
        $show_hongbao_button = 1;
        if(!$__UserInfo){
            $show_hongbao_button = 0;
        }else{
            if($__IsWeixin != 1 && $tchongbaoConfig['hb_lq_type'] != 1){
                $show_hongbao_button = 0;
            }
        }
    }
}

$ajaxHongbaoUrl = 'plugin.php?id=tom_tchongbao:ajax&formhash='.FORMHASH;
$ajaxDistanceHongbaoUrl = 'plugin.php?id=tom_tchongbao:ajax&act=distance&site='.$site_id.'&formhash='.FORMHASH;
$myMoneyUrl= 'plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=money';
$hongbaoLogListUrl= 'plugin.php?id=tom_tchongbao&mod=loglist&site='.$site_id.'&hongbao_id='.$tchongbaoInfo['id'];
$hongbaoIndexUrl = 'plugin.php?id=tom_tchongbao&mod=index&site='.$site_id;

$infoShareTitle = str_replace("{TITLE}",$tongchengInfo['title'], $typeInfo['info_share_title']);
$infoShareDesc = str_replace("{TITLE}",$tongchengInfo['title'], $typeInfo['info_share_desc']);
$infoShareTitle = str_replace("{SITENAME}",$__SitesInfo['name'], $infoShareTitle);
$infoShareDesc = str_replace("{SITENAME}",$__SitesInfo['name'], $infoShareDesc);
if(!empty($cateInfo)){
    $infoShareTitle = str_replace("{CATENAME}",$cateInfo['name'], $infoShareTitle);
    $infoShareDesc = str_replace("{CATENAME}",$cateInfo['name'], $infoShareDesc);
}
if(is_array($attrList) && !empty($attrList)){
    foreach ($attrList as $key => $value){
        $value['value'] = str_replace(" ",'|', $value['value']);
        $infoShareTitle = str_replace("{ATTR".$value['attr_id']."}",$value['value'], $infoShareTitle);
        $infoShareDesc = str_replace("{ATTR".$value['attr_id']."}",$value['value'], $infoShareDesc);
    }
}
$infoShareTitle = str_replace(" ",'', $infoShareTitle);
$infoShareDesc = str_replace(" ",'', $infoShareDesc);
$shareTitle = lang("plugin/tom_tongcheng", "kuohao_left")."{$typeInfo['name']}".lang("plugin/tom_tongcheng", "kuohao_right")."{$tongchengInfo['title']}-{$__SitesInfo['name']}";
$shareDesc = $desc;
if(!empty($infoShareTitle)){
    $shareTitle = $infoShareTitle;
}
if(!empty($infoShareDesc)){
    $shareDesc = $infoShareDesc;
}
if($tchongbaoInfo && $tchongbaoInfo['status']==1 && $tchongbaoConfig && isset($tchongbaoConfig['hongbao_tongcheng_prefix'])){
    $shareTitle = $tchongbaoConfig['hongbao_tongcheng_prefix'].$shareTitle;
}

$subscribeFlag = 0;
$open_subscribe = 0;
$access_token = $weixinClass->get_access_token();
if($tongchengConfig['open_subscribe']==1 && $tongchengConfig['open_child_subscribe_sites']==1){
    $open_subscribe = 1;
}else if($tongchengConfig['open_subscribe']==1 && $site_id==1){
    $open_subscribe = 1;
}
if($open_subscribe==1 && !empty($__UserInfo['openid']) && !empty($access_token)){
    $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$__UserInfo['openid']}&lang=zh_CN";
    $return = get_html($get_user_info_url);
    if(!empty($return)){
        $tcContent = json_decode($return,true);
        if(is_array($tcContent) && !empty($tcContent) && isset($tcContent['subscribe'])){
            if($tcContent['subscribe'] == 1){
                $subscribeFlag = 1;
            }else{
                $subscribeFlag = 2;
            }
        }
    }
}

$zanCount = C::t('#tom_tongcheng#tom_tongcheng_collect')->fetch_all_count(" AND tongcheng_id = {$tongchengInfo['id']} ");
$zanListTmp = C::t('#tom_tongcheng#tom_tongcheng_collect')->fetch_all_list(" AND tongcheng_id = {$tongchengInfo['id']} ", 'ORDER BY id DESC', 0 ,20);
$zanList = array();
if(is_array($zanListTmp) && !empty($zanListTmp)){
    foreach($zanListTmp as $key => $value){
        $zanList[$key] = $value;
        $userInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']);
        $zanList[$key]['userInfo'] = $userInfoTmp;
    }
}

$messageUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=message&act=create&tongcheng_id=".$tongchengInfo['id'].'&to_user_id='.$userInfo['id'].'&formhash='.FORMHASH;
$tousuUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=tousu&tongcheng_id=".$tongchengInfo['id'];
$addPinglunUrl = "plugin.php?id=tom_tongcheng:ajax&act=pinglun&formhash=".FORMHASH;
$showPinglunUrl = "plugin.php?id=tom_tongcheng:ajax&act=loadPinglun&tongcheng_id={$tongchengInfo['id']}&formhash=".FORMHASH;
$removePinglunUrl = "plugin.php?id=tom_tongcheng:ajax&act=removePinglun&tongcheng_id={$tongchengInfo['id']}&formhash=".FORMHASH;
$removeReplyUrl = "plugin.php?id=tom_tongcheng:ajax&act=removeReplyPinglun&tongcheng_id={$tongchengInfo['id']}&formhash=".FORMHASH;
$ajaxZhuanfaUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=zhuanfa&tongcheng_id='.$tongcheng_id.'&formhash='.FORMHASH;

if($__ShowTchehuoren == 1){
    $tchehuorenInfoTmp = C::t('#tom_tchehuoren#tom_tchehuoren')->fetch_by_user_id($__UserInfo['id']);
    if($tchehuorenInfoTmp && $tchehuorenInfoTmp['status'] == 1){
        $shareUrl   = $shareUrl."&tj_hehuoren_id={$tchehuorenInfoTmp['id']}";
    }
}

$__TongchengHost =  '';
if($__ShowTchongbao == 1){
    $tchongbaoConfig['tongcheng_hosts'] = trim($tchongbaoConfig['tongcheng_hosts']);
    $tchongbaoConfig['hongbao_hosts']      = trim($tchongbaoConfig['hongbao_hosts']);
    if($tchongbaoConfig['open_only_hosts'] == 1 && !empty($tchongbaoConfig['tongcheng_hosts']) && !empty($tchongbaoConfig['hongbao_hosts'])){
        if(strpos($_G['siteurl'],$tchongbaoConfig['tongcheng_hosts']) === FALSE && strpos($_G['siteurl'],$tchongbaoConfig['hongbao_hosts']) !== FALSE){
            $__TongchengHost = str_replace($tchongbaoConfig['hongbao_hosts'], $tchongbaoConfig['tongcheng_hosts'], $_G['siteurl']);
            if($tchongbaoConfig['must_http'] == 1){
                if(strpos($__TongchengHost,'https') === FALSE){
                    $__TongchengHost = str_replace("http", "https", $__TongchengHost);
                }
            }
        }
    }
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:info");  




