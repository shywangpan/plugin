<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=cate'; 
$modListUrl = $adminListUrl.'&tmod=cate';
$modFromUrl = $adminFromUrl.'&tmod=cate';

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_tcshop#tom_tcshop_cate')->insert($insertData);
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
    
}else if($_GET['act'] == 'addchild'){
    $parent_id       = isset($_GET['parent_id'])? intval($_GET['parent_id']):0;
    if(submitcheck('submit')){
        
        $name        = isset($_GET['name'])? addslashes($_GET['name']):'';
        $csort       = isset($_GET['csort'])? intval($_GET['csort']):10;
        
        $insertData = array();
        $insertData['name'] = $name;
        $insertData['csort'] = $csort;
        $insertData['parent_id'] = $parent_id;
        C::t('#tom_tcshop#tom_tcshop_cate')->insert($insertData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=addchild&parent_id='.$parent_id,'enctype');
        showtableheader();
        tomshowsetting(true,array('title'=>$Lang['cate_name'],'name'=>'name','value'=>'','msg'=>$Lang['cate_name_msg']),"input");
        tomshowsetting(true,array('title'=>$Lang['cate_csort'],'name'=>'csort','value'=>10,'msg'=>$Lang['cate_csort_msg']),"input");
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
}else if($_GET['act'] == 'edit'){
    $cateInfo = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($cateInfo);
        C::t('#tom_tcshop#tom_tcshop_cate')->update($cateInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($cateInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['act'] == 'editchild'){
    $cateInfo = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        
        $name        = isset($_GET['name'])? addslashes($_GET['name']):'';
        $csort       = isset($_GET['csort'])? intval($_GET['csort']):10;
        
        $updateData = array();
        $updateData['name'] = $name;
        $updateData['csort'] = $csort;
        C::t('#tom_tcshop#tom_tcshop_cate')->update($cateInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=editchild&id='.$_GET['id'],'enctype');
        showtableheader();
        tomshowsetting(true,array('title'=>$Lang['cate_name'],'name'=>'name','value'=>$cateInfo['name'],'msg'=>$Lang['cate_name_msg']),"input");
        tomshowsetting(true,array('title'=>$Lang['cate_csort'],'name'=>'csort','value'=>$cateInfo['csort'],'msg'=>$Lang['cate_csort_msg']),"input");
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'import'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_tcshop/config/import.data.php';
    
    foreach ($cateArr as $key => $value){
        $insertData = array();
        $insertData['name']     = $value['name'];
        $insertData['picurl']   = $value['picurl'];
        $insertData['csort']    = $key;
        C::t('#tom_tcshop#tom_tcshop_cate')->insert($insertData);
        $parent_id = C::t('#tom_tcshop#tom_tcshop_cate')->insert_id();
        
        foreach ($value['childs'] as $k1 => $v1){
            $insertData = array();
            $insertData['parent_id']     = $parent_id;
            $insertData['name']         = $v1;
            $insertData['csort']        = $k1;
            C::t('#tom_tcshop#tom_tcshop_cate')->insert($insertData);
        }
    }
    
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tcshop#tom_tcshop_cate')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else{
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['cate_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li><a href="javascript:void(0);" onclick="import_confirm(\''.$modBaseUrl.'&act=import&formhash='.FORMHASH.'\');" class="addtr" ><font color="#F60">'.$Lang['cate_import'].'</font></a></li>';
    echo '</ul></td></tr>';
    showtablefooter();
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $pagesize = 100;
    $start = ($page-1)*$pagesize;	
    $cateList = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_all_list(" AND parent_id=0 "," ORDER BY csort ASC,id DESC ",$start,$pagesize);
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['cate_picurl'] . '</th>';
    echo '<th>' . $Lang['cate_name'] . '</th>';
    echo '<th>' . $Lang['cate_csort'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($cateList as $key => $value) {
        
        if(!preg_match('/^http/', $value['picurl']) ){
            if(strpos($value['picurl'], 'source/plugin/tom_tcshop/') === FALSE){
                $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
            }else{
                $picurl = $value['picurl'];
            }
        }else{
            $picurl = $value['picurl'];
        }
        
        echo '<tr>';
        echo '<td><img src="'.$picurl.'" width="40" /></td>';
        echo '<td>' . $value['name'] . '</td>';
        echo '<td>' . $value['csort'] . '</td>';
        echo '<td>';
        if($value['parent_id'] == 0){
            echo '<a href="'.$modBaseUrl.'&act=addchild&parent_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['cate_addchild']. '</a>&nbsp;|&nbsp;';
        }
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['cate_edit']. '</a>&nbsp;|&nbsp;';
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
        
        $childCateList = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_all_list(" AND parent_id={$value['id']} "," ORDER BY csort ASC,id DESC ",0,100);
        if(is_array($childCateList) && !empty($childCateList)){
            foreach ($childCateList as $k => $v){
                echo '<tr>';
                echo '<td>&nbsp;</td>';
                echo '<td><img src="source/plugin/tom_tcshop/images/cates_admin_ico.png"/>' . $v['name'] . '</td>';
                echo '<td>' . $v['csort'] . '</td>';
                echo '<td>';
                echo '<a href="'.$modBaseUrl.'&act=editchild&id='.$v['id'].'&formhash='.FORMHASH.'">' . $Lang['cate_editchild']. '</a>&nbsp;|&nbsp;';
                echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$v['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
                echo '</td>';
                echo '</tr>';
            }
        }
        
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
function import_confirm(url){
  var r = confirm("{$Lang['makesure_import_msg']}")
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
    
    $name        = isset($_GET['name'])? addslashes($_GET['name']):'';
    $csort       = isset($_GET['csort'])? intval($_GET['csort']):10;
    
    $youhui_model_name     = isset($_GET['youhui_model_name'])? addslashes($_GET['youhui_model_name']):'';
    //$youhui_model_id       = isset($_GET['youhui_model_id'])? intval($_GET['youhui_model_id']):0;
    $youhui_model_ids      = isset($_GET['youhui_model_ids'])? addslashes($_GET['youhui_model_ids']):'';
    
    $picurl = "";
    if($_GET['act'] == 'add'){
        $picurl        = tomuploadFile("picurl");
    }else if($_GET['act'] == 'edit'){
        $picurl        = tomuploadFile("picurl",$infoArr['picurl']);
    }

    $data['name']               = $name;
    $data['picurl']             = $picurl;
    $data['youhui_model_name']  = $youhui_model_name;
    //$data['youhui_model_id']    = $youhui_model_id;
    $data['youhui_model_ids']   = $youhui_model_ids;
    $data['csort']              = $csort;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'name'                  => '',
        'picurl'                => '',
        'youhui_model_name'     => '',
        'youhui_model_id'       => 0,
        'youhui_model_ids'      => '',
        'csort'                 => 10,
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['cate_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['cate_name_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['cate_picurl'],'name'=>'picurl','value'=>$options['picurl'],'msg'=>$Lang['cate_picurl_msg']),"file");
    
    tomshowsetting(true,array('title'=>$Lang['cate_youhui_model_name'],'name'=>'youhui_model_name','value'=>$options['youhui_model_name'],'msg'=>$Lang['cate_youhui_model_name_msg']),"input");
    //tomshowsetting(true,array('title'=>$Lang['cate_youhui_model_id'],'name'=>'youhui_model_id','value'=>$options['youhui_model_id'],'msg'=>$Lang['cate_youhui_model_id_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['cate_youhui_model_ids'],'name'=>'youhui_model_ids','value'=>$options['youhui_model_ids'],'msg'=>$Lang['cate_youhui_model_ids_msg']),"input");
    
    tomshowsetting(true,array('title'=>$Lang['cate_csort'],'name'=>'csort','value'=>$options['csort'],'msg'=>$Lang['cate_csort_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['cate_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['cate_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['cate_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['cate_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['cate_edit'],"",true);
    }else if($_GET['act'] == 'addchild'){
        tomshownavli($Lang['cate_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['cate_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['cate_addchild'],"",true);
    }else if($_GET['act'] == 'editchild'){
        tomshownavli($Lang['cate_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['cate_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['cate_editchild'],"",true);
    }else{
        tomshownavli($Lang['cate_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['cate_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}


