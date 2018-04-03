<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=common'; 
$modListUrl = $adminListUrl.'&tmod=common';
$modFromUrl = $adminFromUrl.'&tmod=common';

$commonInfo = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_by_id(1);
if(!$commonInfo){
    $insertData = array();
    $insertData['id']      = 1;
    C::t('#tom_tongcheng#tom_tongcheng_common')->insert($insertData);
}
if(submitcheck('submit')){
    $updateData = array();
    $updateData = __get_post_data($commonInfo);
    C::t('#tom_tongcheng#tom_tongcheng_common')->update(1,$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    tomloadcalendarjs();
    loadeditorjs();
    __create_nav_html();
    showformheader($modFromUrl,'enctype');
    showtableheader();
    __create_info_html($commonInfo);
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
}

function __get_post_data($infoArr = array()){
    $data = array();
    
    $xieyi_txt          = isset($_GET['xieyi_txt'])? addslashes($_GET['xieyi_txt']):'';
    $help_txt           = isset($_GET['help_txt'])? addslashes($_GET['help_txt']):'';
    $kefu_txt           = isset($_GET['kefu_txt'])? addslashes($_GET['kefu_txt']):'';
    $forbid_word        = isset($_GET['forbid_word'])? addslashes($_GET['forbid_word']):'';
    $sfc_txt            = isset($_GET['sfc_txt'])? addslashes($_GET['sfc_txt']):'';

    $data['xieyi_txt']    = $xieyi_txt;
    $data['forbid_word']  = $forbid_word;
    $data['sfc_txt']      = $sfc_txt;
    //$data['help_txt']   = $help_txt;
    //$data['kefu_txt']   = $kefu_txt;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'xieyi_txt'        => '',
        'help_txt'         => '',
        'kefu_txt'         => '',
        'forbid_word'      => '',
        'sfc_txt'          => '',
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['forbid_word_title'],'name'=>'forbid_word','value'=>$options['forbid_word'],'msg'=>$Lang['forbid_word_title_msg']),"textarea");
    tomshowsetting(true,array('title'=>$Lang['xieyi_title'],'name'=>'xieyi_txt','value'=>$options['xieyi_txt'],'msg'=>$Lang['xieyi_title_msg']),"text");
    tomshowsetting(true,array('title'=>$Lang['sfc_title'],'name'=>'sfc_txt','value'=>$options['sfc_txt'],'msg'=>$Lang['sfc_title_msg']),"text");
    //tomshowsetting(true,array('title'=>$Lang['help_title'],'name'=>'help_txt','value'=>$options['help_txt'],'msg'=>$Lang['help_title_msg']),"text");
    //tomshowsetting(true,array('title'=>$Lang['kefu_title'],'name'=>'kefu_txt','value'=>$options['kefu_txt'],'msg'=>$Lang['kefu_title_msg']),"text");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    tomshownavli($Lang['common_title'],"",true);
    tomshownavfooter();
}


