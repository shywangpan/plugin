<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$tongcheng_id     = isset($_GET['tongcheng_id'])? intval($_GET['tongcheng_id']):0;

$modBaseUrl = $adminBaseUrl.'&tmod=content&tongcheng_id='.$tongcheng_id; 
$modListUrl = $adminListUrl.'&tmod=content&tongcheng_id='.$tongcheng_id;
$modFromUrl = $adminFromUrl.'&tmod=content&tongcheng_id='.$tongcheng_id; 

$get_list_url_value = get_list_url("tom_tongcheng_admin_index_list");
if($get_list_url_value){
    $modListUrl = $get_list_url_value;
}

$tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
$tongchengInfo['content'] = contentFormat($tongchengInfo['content']);

$contentInfo = C::t('#tom_tongcheng#tom_tongcheng_content')->fetch_by_tongcheng_id($tongcheng_id);
if(!$contentInfo){
    $insertData = array();
    $insertData['tongcheng_id']      = $tongcheng_id;
    $insertData['is_show']           = 0;
    $insertData['content']           = $tongchengInfo['content'];
    $insertData['add_time']          = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng_content')->insert($insertData);
    $contentInfo = C::t('#tom_tongcheng#tom_tongcheng_content')->fetch_by_tongcheng_id($tongcheng_id);
}
if(submitcheck('submit')){
    
    $updateData = array();
    $updateData = __get_post_data($contentInfo);
    C::t('#tom_tongcheng#tom_tongcheng_content')->update($contentInfo['id'],$updateData);
    
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    tomloadcalendarjs();
    loadeditorjs();
    __create_nav_html();
    showformheader($modFromUrl,'enctype');
    showtableheader();
    __create_info_html($contentInfo);
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
}

function __get_post_data($infoArr = array()){
    $data = array();
    
    $is_show     = isset($_GET['is_show'])? intval($_GET['is_show']):0;
    $content     = isset($_GET['content'])? addslashes($_GET['content']):'';

    $data['is_show']     = $is_show;
    $data['content']     = $content;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'is_show'        => 0,
        'content'        => '',
    );
    $options = array_merge($options, $infoArr);
    
    $content_is_show_item = array(0=>$Lang['content_is_show_0'],1=>$Lang['content_is_show_1']);
    tomshowsetting(true,array('title'=>$Lang['content_is_show'],'name'=>'is_show','value'=>$options['is_show'],'msg'=>$Lang['content_is_show_msg'],'item'=>$content_is_show_item),"radio");
    tomshowsetting(true,array('title'=>$Lang['content_content'],'name'=>'content','value'=>$options['content'],'msg'=>$Lang['content_content_msg']),"text");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    tomshownavli($Lang['content_title'],"",true);
    tomshownavfooter();
}


