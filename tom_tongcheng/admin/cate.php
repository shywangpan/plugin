<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=cate&model_id='.$_GET['model_id'].'&type_id='.$_GET['type_id'];
$modListUrl = $adminListUrl.'&tmod=type&act=edit&model_id='.$_GET['model_id'].'&id='.$_GET['type_id'];
$modFromUrl = $adminFromUrl.'&tmod=cate&model_id='.$_GET['model_id'].'&type_id='.$_GET['type_id'];

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_tongcheng#tom_tongcheng_model_cate')->insert($insertData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=add','enccate');
        showtableheader();
        __create_info_html();
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
}else if($_GET['act'] == 'edit'){
    $cateInfo = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($cateInfo);
        C::t('#tom_tongcheng#tom_tongcheng_model_cate')->update($cateInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enccate');
        showtableheader();
        __create_info_html($cateInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tongcheng#tom_tongcheng_model_cate')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}

function __get_post_data($infoArr = array()){
    $data = array();
    
    $name        = isset($_GET['name'])? addslashes($_GET['name']):'';
    $paixu       = isset($_GET['paixu'])? intval($_GET['paixu']):100;

    $data['model_id']   = $_GET['model_id'];
    $data['type_id']   = $_GET['type_id'];
    $data['name']       = $name;
    $data['paixu']      = $paixu;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'name'           => '',
        'paixu'          => 100,
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['cate_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['cate_name_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['cate_paixu'],'name'=>'paixu','value'=>$options['paixu'],'msg'=>$Lang['cate_paixu_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl,$modListUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['cate_add'],"",true);
        tomshownavli($Lang['back_title'],ADMINSCRIPT.'?'.$modListUrl,false);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['cate_edit'],"",true);
        tomshownavli($Lang['back_title'],ADMINSCRIPT.'?'.$modListUrl,false);
    }
    tomshownavfooter();
}


