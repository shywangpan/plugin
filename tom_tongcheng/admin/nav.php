<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=nav'; 
$modListUrl = $adminListUrl.'&tmod=nav';
$modFromUrl = $adminFromUrl.'&tmod=nav';

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_tongcheng#tom_tongcheng_nav')->insert($insertData);
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
    $navInfo = C::t('#tom_tongcheng#tom_tongcheng_nav')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($navInfo);
        C::t('#tom_tongcheng#tom_tongcheng_nav')->update($navInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($navInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tongcheng#tom_tongcheng_nav')->delete_by_id($_GET['id']);
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
    $navList = C::t('#tom_tongcheng#tom_tongcheng_nav')->fetch_all_list("{$where}"," ORDER BY nsort ASC,id DESC ",$start,$pagesize);
    
    $modBasePageUrl = $modBaseUrl."&site_id={$site_id}";
    
    showformheader($modFromUrl.'&formhash='.FORMHASH);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['index_search_list'] . '</th></tr>';
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
    echo '<th>' . $Lang['sites_title'] . '</th>';
    echo '<th>' . $Lang['nav_type'] . '</th>';
    echo '<th>' . $Lang['nav_model_id'] . '</th>';
    echo '<th>' . $Lang['nav_title'] . '</th>';
    echo '<th>' . $Lang['nav_picurl'] . '</th>';
    echo '<th>' . $Lang['nav_link'] . '</th>';
    echo '<th>' . $Lang['nav_nsort'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($navList as $key => $value) {
        
        if(!preg_match('/^http/', $value['picurl']) ){
            $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
        }else{
            $picurl = $value['picurl'];
        }
        $siteInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($value['site_id']);
        echo '<tr>';
        if($value['site_id'] > 1){
            echo '<td>' . $siteInfo['name'] . '</td>';
        }else{
            echo '<td>' . $Lang['sites_one'] . '</td>';
        }
        if($value['type'] == 1){
            echo '<td>' . $Lang['nav_type_1'] . '</td>';
        }else if($value['type'] == 2){
            echo '<td>' . $Lang['nav_type_2'] . '</td>';
        }else{
            echo '<td>-</td>';
        }
        if($value['type'] == 1 && $value['model_id'] > 0){
            $modelInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($value['model_id']);
            if($modelInfoTmp){
                echo '<td>' . $modelInfoTmp['name'] . '</td>';
            }else{
                echo '<td>-</td>';
            }
        }else{
            echo '<td>-</td>';
        }
        
        echo '<td>' . $value['title'] . '</td>';
        if($value['type'] == 2){
            echo '<td><img src="'.$picurl.'" width="40" /></td>';
        }else{
            echo '<td></td>';
        }
        echo '<td>' . $value['link'] . '</td>';
        echo '<td>' . $value['nsort'] . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['nav_edit']. '</a>&nbsp;|&nbsp;';
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
    $type       = isset($_GET['type'])? intval($_GET['type']):1;
    $model_id       = isset($_GET['model_id'])? intval($_GET['model_id']):0;
    $title           = isset($_GET['title'])? addslashes($_GET['title']):'';
    $link           = isset($_GET['link'])? addslashes($_GET['link']):'';
    $nsort       = isset($_GET['nsort'])? intval($_GET['nsort']):10;
    
    $picurl = "";
    if($_GET['act'] == 'add'){
        $picurl        = tomuploadFile("picurl");
    }else if($_GET['act'] == 'edit'){
        $picurl        = tomuploadFile("picurl",$infoArr['picurl']);
    }
    
    $data['site_id']    = $site_id;
    $data['type']    = $type;
    $data['model_id']    = $model_id;
    $data['title']      = $title;
    $data['picurl']     = $picurl;
    $data['link']       = $link;
    $data['nsort']      = $nsort;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'site_id'        => 1,
        'type'           => 1,
        'model_id'       => 0,
        'title'          => '',
        'picurl'         => '',
        'link'           => '',
        'nsort'          => 10,
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
    
    $nav_type_item = array(1=>$Lang['nav_type_1'],2=>$Lang['nav_type_2']);
    tomshowsetting(true,array('title'=>$Lang['nav_type'],'name'=>'type','value'=>$options['type'],'msg'=>$Lang['nav_type_msg'],'item'=>$nav_type_item),"radio");
    
    $modelList = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" "," ORDER BY id DESC ",0,100);
    $modelStr = '<tr class="header"><th>'.$Lang['nav_model_id'].'</th><th></th></tr>';
    $modelStr.= '<tr><td width="300"><select style="width: 260px;" name="model_id" id="model_id">';
    $modelStr.=  '<option value="1">'.$Lang['nav_model_id'].'</option>';
    foreach ($modelList as $key => $value){
        if($value['id'] == $options['site_id']){
            $modelStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
        }else{
            $modelStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
    }
    $modelStr.= '</select></td><td></td></tr>';
    echo $modelStr;
    
    tomshowsetting(true,array('title'=>$Lang['nav_title'],'name'=>'title','value'=>$options['title'],'msg'=>$Lang['nav_title_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['nav_picurl'],'name'=>'picurl','value'=>$options['picurl'],'msg'=>$Lang['nav_picurl_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['nav_link'],'name'=>'link','value'=>$options['link'],'msg'=>$Lang['nav_link_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['nav_nsort'],'name'=>'nsort','value'=>$options['nsort'],'msg'=>$Lang['nav_nsort_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['nav_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['nav_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['nav_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['nav_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['nav_edit'],"",true);
    }else{
        tomshownavli($Lang['nav_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['nav_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}


