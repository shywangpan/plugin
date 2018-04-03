<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=help'; 
$modListUrl = $adminListUrl.'&tmod=help';
$modFromUrl = $adminFromUrl.'&tmod=help';

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_tongcheng#tom_tongcheng_help')->insert($insertData);
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
    $helpInfo = C::t('#tom_tongcheng#tom_tongcheng_help')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($helpInfo);
        C::t('#tom_tongcheng#tom_tongcheng_help')->update($helpInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($helpInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tongcheng#tom_tongcheng_help')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    
    $where = "";
    $pagesize = 100;
    $start = ($page-1)*$pagesize;	
    $helpList = C::t('#tom_tongcheng#tom_tongcheng_help')->fetch_all_list("{$where}"," ORDER BY paixu ASC,id DESC ",$start,$pagesize);
    
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['help_title'] . '</th>';
    echo '<th>' . $Lang['help_paixu'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    $i = 1;
    foreach ($helpList as $key => $value) {
        
        echo '<tr>';
        echo '<td>' . $value['title'] . '</td>';
        echo '<td>' . $value['paixu'] . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['help_edit']. '</a>&nbsp;|&nbsp;';
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
    
    $title       = isset($_GET['title'])? addslashes($_GET['title']):'';
    $content     = isset($_GET['content'])? addslashes($_GET['content']):'';
    $paixu       = isset($_GET['paixu'])? intval($_GET['paixu']):10;
    
    $data['title']      = $title;
    $data['content']    = $content;
    $data['paixu']      = $paixu;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'title'          => '',
        'content'        => '',
        'paixu'          => 100,
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['help_title'],'name'=>'title','value'=>$options['title'],'msg'=>$Lang['help_title_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['help_paixu'],'name'=>'paixu','value'=>$options['paixu'],'msg'=>$Lang['help_paixu_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['help_content'],'name'=>'content','value'=>$options['content'],'msg'=>$Lang['help_content_msg']),"text");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['help_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['help_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['help_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['help_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['help_edit'],"",true);
    }else{
        tomshownavli($Lang['help_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['help_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}


