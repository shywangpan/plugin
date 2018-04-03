<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=topnews'; 
$modListUrl = $adminListUrl.'&tmod=topnews';
$modFromUrl = $adminFromUrl.'&tmod=topnews';

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_tongcheng#tom_tongcheng_topnews')->insert($insertData);
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
    $topnewsInfo = C::t('#tom_tongcheng#tom_tongcheng_topnews')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($topnewsInfo);
        C::t('#tom_tongcheng#tom_tongcheng_topnews')->update($topnewsInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($topnewsInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tongcheng#tom_tongcheng_topnews')->delete_by_id($_GET['id']);
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
    $topnewsList = C::t('#tom_tongcheng#tom_tongcheng_topnews')->fetch_all_list("{$where}"," ORDER BY paixu ASC,id DESC ",$start,$pagesize);
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['topnews_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['topnews_help_1'] . '</li>';
    echo '</ul></td></tr>';
    showtablefooter();
    
    $modBasePageUrl = $modBaseUrl."&site_id={$site_id}";
    
    showformheader($modFromUrl.'&formhash='.FORMHASH);
    showtableheader();
    $sitesList = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(" "," ORDER BY id DESC ",0,100);
    $sitesStr = '<tr><td width="100" align="right"><b>'.$Lang['sites_title'].'</b></td>';
    $sitesStr.= '<td><select style="width: 260px;" name="site_id" id="site_id">';
    $sitesStr.=  '<option value="0">'.$Lang['sites_all'].'</option>';
    $sitesStr.=  '<option value="1">'.$Lang['sites_one'].'</option>';
    foreach ($sitesList as $key => $value){
        $sitesStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
    }
    $sitesStr.= '</select></td></tr>';
    echo $sitesStr;
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
    
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['topnews_title'] . '</th>';
    echo '<th>' . $Lang['topnews_link'] . '</th>';
    echo '<th>' . $Lang['sites_title'] . '</th>';
    echo '<th>' . $Lang['topnews_paixu'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($topnewsList as $key => $value) {
        $siteInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($value['site_id']);
        echo '<tr>';
        echo '<td>' . $value['title'] . '</td>';
        echo '<td>' . $value['link'] . '</td>';
        if($value['site_id'] > 1){
            echo '<td>' . $siteInfo['name'] . '</td>';
        }else{
            echo '<td>' . $Lang['sites_one'] . '</td>';
        }
        echo '<td>' . $value['paixu'] . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['topnews_edit']. '</a>&nbsp;|&nbsp;';
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
    $title           = isset($_GET['title'])? addslashes($_GET['title']):'';
    $link           = isset($_GET['link'])? addslashes($_GET['link']):'';
    $paixu       = isset($_GET['paixu'])? intval($_GET['paixu']):10;
    
    $data['site_id']    = $site_id;
    $data['title']      = $title;
    $data['link']       = $link;
    $data['paixu']      = $paixu;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'site_id'        => 1,
        'title'              => '',
        'picurl'         => '',
        'link'          => '',
        'paixu'          => 10,
    );
    $options = array_merge($options, $infoArr);
    
    $sitesList = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(" AND status=1 "," ORDER BY id DESC ",0,100);
    $sitesStr = '<tr class="header"><th>'.$Lang['sites_title'].'</th><th></th></tr>';
    $sitesStr.= '<tr><td width="300"><select style="width: 260px;" name="site_id" id="site_id">';
    $sitesStr.=  '<option value="1">'.$Lang['sites_one'].'</option>';
    foreach ($sitesList as $key => $value){
        if($value['id'] == $options['site_id']){
            $sitesStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
        }else{
            $sitesStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
    }
    $sitesStr.= '</select></td><td></td></tr>';
    echo $sitesStr;
    tomshowsetting(true,array('title'=>$Lang['topnews_title'],'name'=>'title','value'=>$options['title'],'msg'=>$Lang['topnews_title_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['topnews_link'],'name'=>'link','value'=>$options['link'],'msg'=>$Lang['topnews_link_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['topnews_paixu'],'name'=>'paixu','value'=>$options['paixu'],'msg'=>$Lang['topnews_paixu_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['topnews_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['topnews_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['topnews_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['topnews_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['topnews_edit'],"",true);
    }else{
        tomshownavli($Lang['topnews_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['topnews_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}


