<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=popup';
$modListUrl = $adminListUrl.'&tmod=popup';
$modFromUrl = $adminFromUrl.'&tmod=popup';

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        $insertData['add_time']      = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_popup')->insert($insertData);
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
    $popupInfo = C::t('#tom_tongcheng#tom_tongcheng_popup')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($popupInfo);
        C::t('#tom_tongcheng#tom_tongcheng_popup')->update($popupInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($popupInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tongcheng#tom_tongcheng_popup')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    
    $where = "";
    
    $pagesize = 100;
    $start = ($page-1)*$pagesize;
    $popupList = C::t('#tom_tongcheng#tom_tongcheng_popup')->fetch_all_list("{$where}"," ORDER BY id DESC ",$start,$pagesize);
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['popup_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['popup_help_1'] . '</li>';
    echo '</ul></td></tr>';
    showtablefooter();
    
    $modBasePageUrl = $modBaseUrl;
    
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['popup_title'] . '</th>';
    echo '<th>' . $Lang['popup_clicks'] . '</th>';
    echo '<th>' . $Lang['popup_status'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($popupList as $key => $value) {
        echo '<tr>';
        echo '<td>' . $value['title'] . '</td>';
        echo '<td>' . $value['clicks'] . '</td>';
        if($value['status'] == 1){
            echo '<td><font color="#238206">' . $Lang['popup_status_1'] . '</font></td>';
        }else{
            echo '<td><font color="#fd0d0d">' . $Lang['popup_status_0'] . '</font></td>';
        }
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['popup_edit']. '</a>&nbsp;|&nbsp;';
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
    
    $title          = isset($_GET['title'])? addslashes($_GET['title']):'';
    $link           = isset($_GET['link'])? addslashes($_GET['link']):'';
    $content        = isset($_GET['content'])? addslashes($_GET['content']):'';
    $status         = isset($_GET['status'])? intval($_GET['status']):1;
    
    $site_ids = '';
    if(is_array($_GET['site_ids']) && !empty($_GET['site_ids'])){
        $site_ids = implode(',', $_GET['site_ids']);
    }
    
    $data['site_ids']       = $site_ids;
    $data['title']      = $title;
    $data['link']       = $link;
    $data['content']    = $content;
    $data['status']    = $status;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'site_ids'       => '',
        'title'          => '',
        'link'           => '',
        'content'        => '',
        'status'        => 1,
    );
    $options = array_merge($options, $infoArr);
    
    $status_item = array(0=>$Lang['popup_status_0'],1=>$Lang['popup_status_1']);
    tomshowsetting(true,array('title'=>$Lang['popup_status'],'name'=>'status','value'=>$options['status'],'msg'=>$Lang['popup_status_msg'],'item'=>$status_item),"radio");
    tomshowsetting(true,array('title'=>$Lang['popup_title'],'name'=>'title','value'=>$options['title'],'msg'=>$Lang['popup_title_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['popup_link'],'name'=>'link','value'=>$options['link'],'msg'=>$Lang['popup_link_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['popup_content'],'name'=>'content','value'=>$options['content'],'msg'=>$Lang['popup_content_msg']),"text");
    
    $site_ids_arr = array();
    if(!empty($options['site_ids'])){
        $site_ids_arr = explode(',', $options['site_ids']);
    }
    $sitesList = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(" AND status=1 "," ORDER BY id DESC ",0,100);
    $sitesStr = '<tr class="header"><th>'.$Lang['popup_site_ids'].'</th><th></th></tr>';
    $sitesStr.= '<tr><td width="300">';
    if(in_array('[1]', $site_ids_arr)){
        $sitesStr.=  '<input name="site_ids[]" type="checkbox" value="[1]" checked />'.$Lang['sites_one'].'&nbsp;&nbsp;';
    }else{
        $sitesStr.=  '<input name="site_ids[]" type="checkbox" value="[1]" />'.$Lang['sites_one'].'';
    }
    foreach ($sitesList as $key => $value){
        if(in_array('['.$value['id'].']', $site_ids_arr)){
            $sitesStr.=  '<input name="site_ids[]" type="checkbox" value="['.$value['id'].']" checked />'.$value['name'].'&nbsp;&nbsp;';
        }else{
            $sitesStr.=  '<input name="site_ids[]" type="checkbox" value="['.$value['id'].']" />'.$value['name'].'';
        }
    }
    $sitesStr.= '</td><td>'.$Lang['popup_site_ids_msg'].'</td></tr>';
    echo $sitesStr;
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['popup_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['popup_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['popup_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['popup_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['popup_edit'],"",true);
    }else{
        tomshownavli($Lang['popup_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['popup_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}


