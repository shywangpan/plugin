<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=resou'; 
$modListUrl = $adminListUrl.'&tmod=resou';
$modFromUrl = $adminFromUrl.'&tmod=resou';

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_tcshop#tom_tcshop_resou')->insert($insertData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=add','enctype');
        showtableheader();
        __create_info_html();
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
}else if($_GET['act'] == 'edit'){
    $resouInfo = C::t('#tom_tcshop#tom_tcshop_resou')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($resouInfo);
        C::t('#tom_tcshop#tom_tcshop_resou')->update($resouInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($resouInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tcshop#tom_tcshop_resou')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    $site_id      = isset($_GET['site_id'])? intval($_GET['site_id']):0;
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    
    $where = "";
    if(!empty($site_id)){
        $where.= " AND site_id={$site_id} ";
    }
    
    $pagesize = 100;
    $start = ($page-1)*$pagesize;	
    $resouList = C::t('#tom_tcshop#tom_tcshop_resou')->fetch_all_list("{$where}"," ORDER BY paixu ASC,id DESC ",$start,$pagesize);
    
    $modBasePageUrl = $modBaseUrl."&site_id={$site_id}";
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['resou_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['resou_help_1'] . '</li>';
    echo '</ul></td></tr>';
    showtablefooter();
    
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['resou_keywords'] . '</th>';
    echo '<th>' . $Lang['resou_paixu'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($resouList as $key => $value) {
        echo '<tr>';
        echo '<td>' . $value['keywords'] . '</td>';
        echo '<td>' . $value['paixu'] . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['resou_edit']. '</a>&nbsp;|&nbsp;';
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
        $i++;
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBasePageUrl);	
    showsubmit('', '', '', '', $multi, false);
    
    $jsstr = <<<EOF
<script type="text/javascript">
function del_confirm(url){
  var r = confirm("{$Lang['makesure_del_msg']}")
  if (r == true){
    window.location = url;
  }else{
    return false;
  }
}
</script>
EOF;
    echo $jsstr;
    
}

function __get_post_data($infoArr = array()){
    $data = array();
    
    $site_id       = isset($_GET['site_id'])? intval($_GET['site_id']):1;
    $keywords           = isset($_GET['keywords'])? addslashes($_GET['keywords']):'';
    $paixu       = isset($_GET['paixu'])? intval($_GET['paixu']):10;
    
    $data['site_id']    = $site_id;
    $data['keywords']      = $keywords;
    $data['paixu']      = $paixu;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'site_id'        => 1,
        'keywords'              => '',
        'paixu'          => 10,
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['resou_keywords'],'name'=>'keywords','value'=>$options['keywords'],'msg'=>$Lang['resou_keywords_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['resou_paixu'],'name'=>'paixu','value'=>$options['paixu'],'msg'=>$Lang['resou_paixu_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['resou_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['resou_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['resou_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['resou_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['resou_edit'],"",true);
    }else{
        tomshownavli($Lang['resou_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['resou_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}


