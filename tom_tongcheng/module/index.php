<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/sitesids.php';

# lbs start
if($tongchengConfig['open_sites_lbs'] > 1){
    $tongchengConfig['open_sites'] = 1;
}
if(empty($tongchengConfig['baidu_js_ak'])){
    $tongchengConfig['open_sites_lbs'] = 1;
}
$sitesList = array();
if($tongchengConfig['open_sites'] == 1){
    $sitesList = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(" AND status=1 "," ORDER BY id DESC ",0,1000);
}
$ajaxLbsCheckUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=lbs_check';
$ajaxLbsCloseUrl = 'plugin.php?id=tom_tongcheng:ajax&site='.$site_id.'&act=lbs_close&formhash='.$formhash;
if($_GET['lbs_must'] == 1){}else{
    $cookie_lbs = getcookie('tom_tongcheng_sites_lbs');
    if($cookie_lbs && $cookie_lbs == 1){
        $tongchengConfig['open_sites_lbs'] = 1;
    }
}
if($_GET['lbs_no'] == 1){
    $lifeTime = 86400;
    dsetcookie('tom_tongcheng_sites_lbs', 1, $lifeTime);
    $tongchengConfig['open_sites_lbs'] = 1;
}
$lbs_show = 0;
if($_GET['lbs_show'] == 1){
    $lbs_show = 1;
}
# lbs end

$modelWhereStr = " AND is_show=1 ";
if($site_id > 1){
    if(!empty($__SitesInfo['model_ids'])){
        $modelWhereStr.= " AND id IN({$__SitesInfo['model_ids']}) ";
    }
}else{
    $modelWhereStr.= " AND sites_show=0 ";
}
$modelListTmp = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" {$modelWhereStr} "," ORDER BY paixu ASC,id DESC ",0,50);

$navList = array();
$modelList = array();
$i = 1;
$navCount = 0;
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
        
        $navList[$value['id']]['i'] = $i;
        $navList[$value['id']]['title']     = $value['name'];
        $navList[$value['id']]['picurl']    = $picurl;
        $navList[$value['id']]['link']      = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=list&model_id={$value['id']}";
        
        $i++;
        $navCount++;
    }
}

if($__ShowTcqianggou == 1){
    $showQianggouArr = unserialize($tongchengConfig['index_choose_show']);
    
    $showQianggouBtn = 0;
    if(array_search('1',$showQianggouArr) !== false){
        $showQianggouBtn = 1;
        $ajaxLoadQianggouListUrl = 'plugin.php?id=tom_tcqianggou:ajax&site='.$site_id.'&act=list&tongcheng_show=1&formhash='.$formhash;
    }
    
    $showCouponBtn = 0;
    if(array_search('2',$showQianggouArr) !== false){
        $showCouponBtn = 1;
        $ajaxLoadCouponListUrl = 'plugin.php?id=tom_tcqianggou:ajax&site='.$site_id.'&act=list&tongcheng_show=2&formhash='.$formhash;
    }
}

$navListTmpTmp = C::t('#tom_tongcheng#tom_tongcheng_nav')->fetch_all_list(" AND site_id={$site_id} "," ORDER BY nsort ASC,id DESC ",0,100);
if(is_array($navListTmpTmp) && !empty($navListTmpTmp)){
}else{
    $navListTmpTmp = C::t('#tom_tongcheng#tom_tongcheng_nav')->fetch_all_list(" AND site_id=1 "," ORDER BY nsort ASC,id DESC ",0,100);
}
if(is_array($navListTmpTmp) && !empty($navListTmpTmp)){
    $i = 1;
    $navCount = 0;
    $navListTmp = $navList;
    $navList = array();
    foreach ($navListTmpTmp as $key => $value){
        $navList[$key] = $value;
        $navList[$key]['i'] = $i;
        
        if(!preg_match('/^http/', $value['picurl']) ){
            if(strpos($value['picurl'], 'source/plugin/tom_tongcheng/') === FALSE){
                $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
            }else{
                $picurl = $value['picurl'];
            }
        }else{
            $picurl = $value['picurl'];
        }
        $navList[$key]['picurl'] = $picurl;
        
        if($value['type'] == 1 && $value['model_id'] > 0 && isset($navListTmp[$value['model_id']])){
            $navList[$key]['title']     = $navListTmp[$value['model_id']]['title'];
            $navList[$key]['picurl']    = $navListTmp[$value['model_id']]['picurl'];
            $navList[$key]['link']      = $navListTmp[$value['model_id']]['link'];
        }
        
        $i++;
        $navCount++;
    }
}

$topnewsList = C::t('#tom_tongcheng#tom_tongcheng_topnews')->fetch_all_list(" AND site_id={$site_id} "," ORDER BY paixu ASC,id DESC ",0,10);
if(is_array($topnewsList) && !empty($topnewsList)){
}else{
    $topnewsList = C::t('#tom_tongcheng#tom_tongcheng_topnews')->fetch_all_list(" AND site_id=1 "," ORDER BY paixu ASC,id DESC ",0,10);
}

$focuspicListTmp = C::t('#tom_tongcheng#tom_tongcheng_focuspic')->fetch_all_list(" AND site_id={$site_id} "," ORDER BY fsort ASC,id DESC ",0,10);
if(is_array($focuspicListTmp) && !empty($focuspicListTmp)){
}else{
    $focuspicListTmp = C::t('#tom_tongcheng#tom_tongcheng_focuspic')->fetch_all_list(" AND site_id=1 "," ORDER BY fsort ASC,id DESC ",0,10);
}
$focuspicList = array();
foreach ($focuspicListTmp as $key => $value) {
    $focuspicList[$key] = $value;    
    if(!preg_match('/^http/', $value['picurl']) ){
        $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
    }else{
        $picurl = $value['picurl'];
    }
    $focuspicList[$key]['picurl'] = $picurl;
}


$commonClicks = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_all_sun_clicks(" AND id IN({$sql_in_site_ids}) ");
if($site_id == 1){
    $clicksNum = $commonClicks + $tongchengConfig['virtual_clicks'];
}else{
    $clicksNum = $commonClicks + $__SitesInfo['virtual_clicks'];
}

$clicksNumTxt = $clicksNum;
if($clicksNum>10000){
    $clicksNumTmp = $clicksNum/10000;
    $clicksNumTxt = number_format($clicksNumTmp,2); 
}else if($clicksNum>1000000){
    $clicksNumTmp = $clicksNum/10000;
    $clicksNumTxt = number_format($clicksNumTmp,0);
}

$fabuNum = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count("  AND status=1 AND site_id IN({$sql_in_site_ids}) ");
if($site_id == 1){
    $fabuNum = $fabuNum + $tongchengConfig['virtual_fabunum'];
}else{
    $fabuNum = $fabuNum + $__SitesInfo['virtual_fabunum'];
}
$fabuNumTxt = $fabuNum;
if($fabuNum>10000){
    $fabuNumTmp = $fabuNum/10000;
    $fabuNumTxt = number_format($fabuNumTmp,2); 
}else if($fabuNum>1000000){
    $fabuNumTmp = $fabuNum/10000;
    $fabuNumTxt = number_format($fabuNumTmp,0);
}

if($__ShowTcshop == 1){
    $ruzhuNum = C::t('#tom_tcshop#tom_tcshop')->fetch_all_count("  AND status=1 AND site_id IN({$sql_in_site_ids}) ");
    if($site_id == 1){
        $ruzhuNum = $ruzhuNum + $tongchengConfig['virtual_rznum'];
    }else{
        $ruzhuNum = $ruzhuNum + $__SitesInfo['virtual_rznum'];
    }
    $ruzhuNumTxt = $ruzhuNum;
    if($ruzhuNum>10000){
        $ruzhuNumTmp = $ruzhuNum/10000;
        $ruzhuNumTxt = number_format($ruzhuNumTmp,2); 
    }else if($ruzhuNum>1000000){
        $ruzhuNumTmp = $ruzhuNum/10000;
        $ruzhuNumTxt = number_format($ruzhuNumTmp,0);
    }
}


$logoSrc = "source/plugin/tom_tongcheng/images/logo.png";
if(!empty($tongchengConfig['logo_src'])){
    $logoSrc = $tongchengConfig['logo_src'];
}
$kefuQrcodeSrc = $tongchengConfig['kefu_qrcode'];
if($__SitesInfo['id'] > 1){
    if(!preg_match('/^http/', $__SitesInfo['logo'])){
        if(strpos($__SitesInfo['logo'], 'source/plugin/tom_tongcheng/') === FALSE){
            $logoSrc = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$__SitesInfo['logo'];
        }else{
            $logoSrc = $__SitesInfo['logo'];
        }
    }else{
        $logoSrc = $__SitesInfo['logo'];
    }
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

if($__ShowTcshop == 1){
    $tcshopListTmp = C::t('#tom_tcshop#tom_tcshop')->fetch_all_list(" AND status=1 AND shenhe_status=1 AND site_id IN({$sql_in_site_ids}) "," ORDER BY topstatus DESC, vip_level DESC,clicks DESC,id DESC ",0,$tongchengConfig['index_shop_num']);
    $tcshopList = array();
    if(is_array($tcshopListTmp) && !empty($tcshopListTmp)){
        foreach ($tcshopListTmp as $key => $value){
            $tcshopList[$key] = $value;
            if(!preg_match('/^http/', $value['picurl']) ){
                $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
            }else{
                $picurl = $value['picurl'];
            }
            $tcshopList[$key]['picurl'] = $picurl;
            
            if($value['vip_level'] > 0 && !empty($value['video_url'])){
                if(strpos($value['video_url'], 'youku.com') !== FALSE){
                    $video_type = 1;
                }else if(strpos($value['video_url'], 'qq.com') !== FALSE){
                    $video_type = 1;
                }else{
                    $video_type = 2;
                }
            }else{
                $video_type = 0;
            }

            $tcshopList[$key]['video_type'] = $video_type;
            
            if($tcshopConfig['open_list_area'] == 2){
                $areaInfo = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($value['area_id']);
                $streetInfo = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($value['street_id']);
                $tcshopList[$key]['address'] = $areaInfo['name'].'&nbsp;'.$streetInfo['name'];
            }
            
        }
    }

    $newTcshopList = C::t('#tom_tcshop#tom_tcshop')->fetch_all_list(" AND status=1 AND shenhe_status=1 AND site_id IN({$sql_in_site_ids}) "," ORDER BY id DESC ",0,10);
}

$str_replace_search_array   = array('{KANJIA}','{PINTUAN}','{HONGBAO}','{QIANGGOU}','{HAODIAN}','{HEHUOREN}','{RUZHU}','{FABU}');
$str_replace_search_replace = array(
    "plugin.php?id=tom_tckjia&site={$site_id}&mod=index",
    "plugin.php?id=tom_tcptuan&site={$site_id}&mod=index",
    "plugin.php?id=tom_tchongbao&site={$site_id}&mod=index",
    "plugin.php?id=tom_tcqianggou&site={$site_id}&mod=index",
    "plugin.php?id=tom_tcshop&site={$site_id}&mod=index",
    "plugin.php?id=tom_tchehuoren&site={$site_id}&mod=inlet",
    "plugin.php?id=tom_tcshop&site={$site_id}&mod=ruzhu",
    "plugin.php?id=tom_tongcheng&site={$site_id}&mod=fabu",
);

$colorMenuList = $whiteMenuList = array();
if(!empty($tongchengConfig['index_color_menu'])){
    $index_color_menu_str = str_replace($str_replace_search_array, $str_replace_search_replace, $tongchengConfig['index_color_menu']);
    $index_color_menu_str = str_replace("\r\n","{n}",$index_color_menu_str); 
    $index_color_menu_str = str_replace("\n","{n}",$index_color_menu_str);
    $index_color_menu_arr = explode("{n}", $index_color_menu_str);
    if(is_array($index_color_menu_arr) && !empty($index_color_menu_arr)){
        foreach ($index_color_menu_arr as $key => $value){
            $colorMenuList[$key] = explode("|", $value);
        }
    }
}
if(!empty($tongchengConfig['index_white_menu'])){
    $index_white_menu_str = str_replace($str_replace_search_array, $str_replace_search_replace, $tongchengConfig['index_white_menu']);
    $index_white_menu_str = str_replace("\r\n","{n}",$index_white_menu_str); 
    $index_white_menu_str = str_replace("\n","{n}",$index_white_menu_str);
    $index_white_menu_arr = explode("{n}", $index_white_menu_str);
    if(is_array($index_white_menu_arr) && !empty($index_white_menu_arr)){
        foreach ($index_white_menu_arr as $key => $value){
            $whiteMenuList[$key] = explode("|", $value);
        }
    }
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
        $content = json_decode($return,true);
        if(is_array($content) && !empty($content) && isset($content['subscribe'])){
            if($content['subscribe'] == 1){
                $subscribeFlag = 1;
            }else{
                $subscribeFlag = 2;
            }
        }
    }
}

$md5HostUrl = md5($_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=index");

$fabuUrl = "plugin.php?id=tom_tongcheng&site={$site_id}&mod=fabu&model_id=";

if($__ShowTchehuoren == 1){
    $tchehuorenInfoTmp = C::t('#tom_tchehuoren#tom_tchehuoren')->fetch_by_user_id($__UserInfo['id']);
    if($tchehuorenInfoTmp && $tchehuorenInfoTmp['status'] == 1){
        $shareUrl   = $shareUrl."&tj_hehuoren_id={$tchehuorenInfoTmp['id']}";
    }
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:index");