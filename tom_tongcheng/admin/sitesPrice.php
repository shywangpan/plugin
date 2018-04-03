<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=sitesPrice&site_id='.$_GET['site_id'];
$modListUrl = $adminListUrl.'&tmod=sitesPrice&site_id='.$_GET['site_id'];
$modFromUrl = $adminFromUrl.'&tmod=sitesPrice&site_id='.$_GET['site_id'];

$sitesInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($_GET['site_id']);

showtableheader();
echo '<tr><th colspan="15" class="partition">' .$sitesInfo['name'].' >> '. $Lang['sites_price_help_title'] . '</th></tr>';
echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
echo '<li>' . $Lang['sites_price_help_1'] . '</li>';
echo '</ul></td></tr>';
showtablefooter();

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_tongcheng#tom_tongcheng_sites_price')->insert($insertData);
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
    $sites_priceInfo = C::t('#tom_tongcheng#tom_tongcheng_sites_price')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($sites_priceInfo);
        C::t('#tom_tongcheng#tom_tongcheng_sites_price')->update($sites_priceInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($sites_priceInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tongcheng#tom_tongcheng_sites_price')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    
    $sites_priceList = C::t('#tom_tongcheng#tom_tongcheng_sites_price')->fetch_all_list(" AND site_id={$sitesInfo['id']} "," ORDER BY id DESC ",0,100);
    
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['model_name'] . '</th>';
    echo '<th>' . $Lang['type_free_status'] . '</th>';
    echo '<th>' . $Lang['type_price'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($sites_priceList as $key => $value) {
        
        $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($value['type_id']);
        $modelInfo = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($typeInfo['model_id']);
        
        echo '<tr>';
        echo '<td>' . $modelInfo['name'] . ' <font color="#FA6A03">>></font> ' . $typeInfo['name'] . '</td>';
        if($value['free_status'] == $tongchengConfig['admin_tongcheng_type_free_status_1']){
            echo '<td><font color="#0a9409">' . $Lang['type_free_status_1'] . '</font></td>';
        }else if($value['free_status'] == $tongchengConfig['admin_tongcheng_type_free_status_2']){
            echo '<td><font color="#f00">' . $Lang['type_free_status_2'] . '</font></td>';
        }else{
            echo '<td>-</td>';
        }
        echo '<td><font color="#f00">' . $value['fabu_price'].'</font>'. $Lang['type_price_fabu'].'&nbsp;,&nbsp;<font color="#f00">'. $value['refresh_price'].'</font>'. $Lang['type_price_refresh'] .'&nbsp;,&nbsp;<font color="#f00">'. $value['top_price'].'</font>'. $Lang['type_price_top']  . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['sites_price_edit']. '</a>&nbsp;|&nbsp;';
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
        $i++;
    }
    showtablefooter();
    
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
    
    $type_id          = isset($_GET['type_id'])? intval($_GET['type_id']):1;
    $free_status      = isset($_GET['free_status'])? intval($_GET['free_status']):'1';
    $fabu_price       = isset($_GET['fabu_price'])? addslashes($_GET['fabu_price']):'';
    $refresh_price    = isset($_GET['refresh_price'])? addslashes($_GET['refresh_price']):'';
    $top_price        = isset($_GET['top_price'])? addslashes($_GET['top_price']):'';
    
    $data['site_id']        = $_GET['site_id'];
    $data['type_id']        = $type_id;
    $data['free_status']    = $free_status;
    $data['fabu_price']     = $fabu_price;
    $data['refresh_price']  = $refresh_price;
    $data['top_price']      = $top_price;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'type_id'           => '',
        'free_status'       => 1,
        'fabu_price'        => '1.00',
        'refresh_price'     => '1.00',
        'top_price'         => '1.00',
    );
    $options = array_merge($options, $infoArr);
    
    $modelStr = '<tr class="header"><th>'.$Lang['model_name'].'</th><th></th></tr>';
    $modelStr.= '<tr><td width="300"><select style="width: 260px;" name="type_id" id="type_id">';
    $modelList = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" "," ORDER BY paixu ASC,id DESC ",0,1000);
    foreach ($modelList as $key => $value) {
        $typeList = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_all_list(" AND model_id={$value['id']} "," ORDER BY paixu ASC,id DESC ",0,50);
        if(is_array($typeList) && !empty($typeList)){
            foreach ($typeList as $kk => $vv){
                if($vv['id'] == $options['type_id']){
                    $modelStr.=  '<option value="'.$vv['id'].'" selected>'.$value['name'].'>>'.$vv['name'].'</option>';
                }else{
                    $modelStr.=  '<option value="'.$vv['id'].'">'.$value['name'].'>>'.$vv['name'].'</option>';
                }
            }
        }
    }
    $modelStr.= '</select></td><td></td></tr>';
    echo $modelStr;
    $free_status_item = array(1=>$Lang['type_free_status_1'],2=>$Lang['type_free_status_2']);
    tomshowsetting(true,array('title'=>$Lang['type_free_status'],'name'=>'free_status','value'=>$options['free_status'],'msg'=>$Lang['type_free_status_msg'],'item'=>$free_status_item),"radio");
    tomshowsetting(true,array('title'=>$Lang['type_fabu_price'],'name'=>'fabu_price','value'=>$options['fabu_price'],'msg'=>$Lang['type_fabu_price_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['type_refresh_price'],'name'=>'refresh_price','value'=>$options['refresh_price'],'msg'=>$Lang['type_refresh_price_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['type_top_price'],'name'=>'top_price','value'=>$options['top_price'],'msg'=>$Lang['type_top_price_msg']),"input");
    
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['sites_price_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['sites_price_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['sites_price_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['sites_price_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['sites_price_edit'],"",true);
    }else{
        tomshownavli($Lang['sites_price_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['sites_price_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}


