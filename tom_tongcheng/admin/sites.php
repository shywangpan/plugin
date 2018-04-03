<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=sites';
$modListUrl = $adminListUrl.'&tmod=sites';
$modFromUrl = $adminFromUrl.'&tmod=sites';

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        
        $siteList = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(" ","ORDER BY id DESC",0,1);
        $siteId = 100001;
        if(is_array($siteList) && !empty($siteList) && isset($siteList['0']) && $siteList['0']['id']>0){
            $siteId = $siteList['0']['id']+1;
        }
        
        $insertData = array();
        $insertData = __get_post_data();
        $insertData['id'] = $siteId;
        C::t('#tom_tongcheng#tom_tongcheng_sites')->insert($insertData);
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
    $sitesInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($sitesInfo);
        C::t('#tom_tongcheng#tom_tongcheng_sites')->update($sitesInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($sitesInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['act'] == 'site_ids'){
    
    $sitesInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($_GET['id']);
    
    if(submitcheck('submit')){
        
        $site_ids = '';
        if(is_array($_GET['site_ids']) && !empty($_GET['site_ids'])){
            $site_ids = implode(',', $_GET['site_ids']);
        }
        
        $updateData = array();
        $updateData['site_ids'] = $site_ids;
        C::t('#tom_tongcheng#tom_tongcheng_sites')->update($sitesInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $Lang['site_ids_help_title'] . '</th></tr>';
        echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
        echo '<li>' . $Lang['site_ids_help_1'].'<font color="#fd0d0d">'.$sitesInfo['name'] . '</font></li>';
        echo '</ul></td></tr>';
        showtablefooter();
        
        $site_ids_arr = array();
        if(!empty($sitesInfo['site_ids'])){
            $site_ids_arr = explode(',', $sitesInfo['site_ids']);
        }
        
        showformheader($modFromUrl.'&act=site_ids&id='.$_GET['id'],'enctype');
        showtableheader();
        $sitesList = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(" AND status=1 AND id != {$sitesInfo['id']} "," ORDER BY id DESC ",0,1000);
        $sitesStr = '<tr class="header"><th>'.$Lang['site_ids'].'</th><th></th></tr>';
        $sitesStr.= '<tr><td width="300">';
        if(in_array(1, $site_ids_arr)){
            $sitesStr.=  '<input name="site_ids[]" type="checkbox" value="1" checked />'.$Lang['sites_one'].'&nbsp;&nbsp;';
        }else{
            $sitesStr.=  '<input name="site_ids[]" type="checkbox" value="1" />'.$Lang['sites_one'].'';
        }
        foreach ($sitesList as $key => $value){
            if(in_array($value['id'], $site_ids_arr)){
                $sitesStr.=  '<input name="site_ids[]" type="checkbox" value="'.$value['id'].'" checked />'.$value['name'].'&nbsp;&nbsp;';
            }else{
                $sitesStr.=  '<input name="site_ids[]" type="checkbox" value="'.$value['id'].'" />'.$value['name'].'';
            }
        }
        $sitesStr.= '</td><td></td></tr>';
        echo $sitesStr;
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['act'] == 'model_ids'){
    
    $sitesInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($_GET['id']);
    
    if(submitcheck('submit')){
        
        $model_ids = '';
        if(is_array($_GET['model_ids']) && !empty($_GET['model_ids'])){
            $model_ids = implode(',', $_GET['model_ids']);
        }
        
        $updateData = array();
        $updateData['model_ids'] = $model_ids;
        C::t('#tom_tongcheng#tom_tongcheng_sites')->update($sitesInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        
        tomloadcalendarjs();
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $Lang['model_ids_help_title'] . '</th></tr>';
        echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
        echo '<li>' . $Lang['model_ids_help_1'] . '</li>';
        echo '<li><font color="#fd0d0d">' . $Lang['model_ids_help_2'] . '</font></li>';
        echo '</ul></td></tr>';
        showtablefooter();
        
        $model_ids_arr = array();
        if(!empty($sitesInfo['model_ids'])){
            $model_ids_arr = explode(',', $sitesInfo['model_ids']);
        }
        
        showformheader($modFromUrl.'&act=model_ids&id='.$_GET['id'],'enctype');
        showtableheader();
        $modelList  = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" AND is_show=1 "," ORDER BY paixu ASC,id DESC ",0,1000);
        $modelStr   = '<tr class="header"><th>'.$Lang['site_ids'].'</th><th></th></tr>';
        $modelStr.= '<tr><td width="800" style="line-height: 30px;">';
        foreach ($modelList as $key => $value){
            if(in_array($value['id'], $model_ids_arr)){
                $modelStr.=  '<input name="model_ids[]" type="checkbox" value="'.$value['id'].'" checked />'.$value['name'].'&nbsp;&nbsp;';
            }else{
                $modelStr.=  '<input name="model_ids[]" type="checkbox" value="'.$value['id'].'" />'.$value['name'].'';
            }
        }
        $modelStr.= '</td><td></td></tr>';
        echo $modelStr;
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'open'){
    
    $updateData = array();
    $updateData['status'] = 1;
    C::t('#tom_tongcheng#tom_tongcheng_sites')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'close'){
    
    $updateData = array();
    $updateData['status'] = 2;
    C::t('#tom_tongcheng#tom_tongcheng_sites')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'url'){
    
    $sitesInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($_GET['id']);
    $url = "{SITEURL}plugin.php?id=tom_tongcheng&site={$_GET['id']}&mod=index";
    $url  = tom_link_replace($url);
    $url  = str_replace("{SITEURL}", $_G['siteurl'], $url);
    __create_nav_html();
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' .$sitesInfo['name'].'</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['sites_url_title'] . '&nbsp;<input name="" readonly="readonly" type="text" value="'.$url.'" size="100" />' . $Lang['sites_url_title_msg'] . '</li>';
    echo '</ul></td></tr>';
    showtablefooter();
}else{
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $pagesize = 1000;
    $start = ($page-1)*$pagesize;	
    $sitesList = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(""," ORDER BY id DESC ",$start,$pagesize);
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['sites_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li><font color="#fd0d0d">' . $Lang['sites_help_1'] . '</font></li>';
    echo '</ul></td></tr>';
    showtablefooter();
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['sites_id'] . '</th>';
    echo '<th>' . $Lang['sites_name'] . '</th>';
    echo '<th>' . $Lang['sites_city'] . '</th>';
    echo '<th>' . $Lang['sites_site_one'] . '</th>';
    echo '<th>' . $Lang['sites_status'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($sitesList as $key => $value) {
        
        $cityInfo = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($value['city_id']);
        
        echo '<tr>';
        echo '<td>' . $value['id'] . '</td>';
        echo '<td>' . $value['name'] . '</td>';
        echo '<td>' . $cityInfo['name'] . '</td>';
        if($value['site_one'] == 1){
            echo '<td><font color="#238206">' . $Lang['sites_site_one_1'] . '</font></td>';
        }else{
            echo '<td><font color="#fd0d0d">' . $Lang['sites_site_one_0'] . '</font></td>';
        }
        if($value['status'] == 1){
            echo '<td><font color="#238206">' . $Lang['sites_status_1'] . '</font></td>';
        }else{
            echo '<td><font color="#fd0d0d">' . $Lang['sites_status_2'] . '</font></td>';
        }
        echo '<td style="line-height: 30px;">';
        echo '<a href="'.$modBaseUrl.'&act=url&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['sites_url_title']. '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['sites_edit']. '</a><br/>';
        echo '<a href="'.$modBaseUrl.'&act=model_ids&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['model_ids_help_title']. '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=site_ids&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['site_ids_help_title']. '</a><br/>';
        echo '<a href="'.$adminBaseUrl.'&tmod=sitesPrice&site_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['sites_price_list_title']. '</a>&nbsp;|&nbsp;';
        if($value['status'] == 1){
            echo '<a href="'.$modBaseUrl.'&act=close&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['sites_status_2']. '</a>';
        }else{
            echo '<a href="'.$modBaseUrl.'&act=open&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['sites_status_1']. '</a>';
        }
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
    
    $manage_user_id     = isset($_GET['manage_user_id'])? intval($_GET['manage_user_id']):0;
    $name               = isset($_GET['name'])? addslashes($_GET['name']):'';
    $lbs_name           = isset($_GET['lbs_name'])? addslashes($_GET['lbs_name']):'';
    $lbs_keywords       = isset($_GET['lbs_keywords'])? addslashes($_GET['lbs_keywords']):'';
    $city_id            = isset($_GET['city_id'])? intval($_GET['city_id']):0;
    $site_one           = isset($_GET['site_one'])? intval($_GET['site_one']):0;
    $share_title        = isset($_GET['share_title'])? addslashes($_GET['share_title']):'';
    $share_desc         = isset($_GET['share_desc'])? addslashes($_GET['share_desc']):'';
    $virtual_clicks     = isset($_GET['virtual_clicks'])? intval($_GET['virtual_clicks']):0;
    $virtual_fabunum    = isset($_GET['virtual_fabunum'])? intval($_GET['virtual_fabunum']):0;
    $virtual_rznum      = isset($_GET['virtual_rznum'])? intval($_GET['virtual_rznum']):0;
    
    $fl_fc_scale        = isset($_GET['fl_fc_scale'])? intval($_GET['fl_fc_scale']):0;
    $shop_fc_scale      = isset($_GET['shop_fc_scale'])? intval($_GET['shop_fc_scale']):0;
    $qg_fc_scale        = isset($_GET['qg_fc_scale'])? intval($_GET['qg_fc_scale']):0;
    $pt_fc_scale        = isset($_GET['pt_fc_scale'])? intval($_GET['pt_fc_scale']):0;
    $kj_fc_scale        = isset($_GET['kj_fc_scale'])? intval($_GET['kj_fc_scale']):0;
    $hehuoren_fc_open   = isset($_GET['hehuoren_fc_open'])? intval($_GET['hehuoren_fc_open']):2;
    
    $logo = "";
    if($_GET['act'] == 'add'){
        $logo        = tomuploadFile("logo");
    }else if($_GET['act'] == 'edit'){
        $logo        = tomuploadFile("logo",$infoArr['logo']);
    }
    
    $kefu_qrcode = "";
    if($_GET['act'] == 'add'){
        $kefu_qrcode        = tomuploadFile("kefu_qrcode");
    }else if($_GET['act'] == 'edit'){
        $kefu_qrcode        = tomuploadFile("kefu_qrcode",$infoArr['kefu_qrcode']);
    }
    
    $share_pic = "";
    if($_GET['act'] == 'add'){
        $share_pic        = tomuploadFile("share_pic");
    }else if($_GET['act'] == 'edit'){
        $share_pic        = tomuploadFile("share_pic",$infoArr['share_pic']);
    }
    
    $dingyue_qrcode = "";
    if($_GET['act'] == 'add'){
        $dingyue_qrcode        = tomuploadFile("dingyue_qrcode");
    }else if($_GET['act'] == 'edit'){
        $dingyue_qrcode        = tomuploadFile("dingyue_qrcode",$infoArr['dingyue_qrcode']);
    }
    
    $data['manage_user_id']     = $manage_user_id;
    $data['name']               = $name;
    $data['lbs_name']           = $lbs_name;
    $data['lbs_keywords']       = $lbs_keywords;
    $data['logo']               = $logo;
    $data['kefu_qrcode']        = $kefu_qrcode;
    $data['site_one']           = $site_one;
    $data['city_id']            = $city_id;
    $data['share_title']        = $share_title;
    $data['share_desc']         = $share_desc;
    $data['share_pic']          = $share_pic;
    $data['virtual_clicks']     = $virtual_clicks;
    $data['virtual_fabunum']    = $virtual_fabunum;
    $data['virtual_rznum']      = $virtual_rznum;
    
    $data['fl_fc_scale']        = $fl_fc_scale;
    $data['shop_fc_scale']      = $shop_fc_scale;
    $data['qg_fc_scale']        = $qg_fc_scale;
    $data['pt_fc_scale']        = $pt_fc_scale;
    $data['kj_fc_scale']        = $kj_fc_scale;
    $data['dingyue_qrcode']     = $dingyue_qrcode;
    $data['hehuoren_fc_open']   = $hehuoren_fc_open;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'manage_user_id'            => 0,
        'name'                      => '',
        'lbs_name'                  => '',
        'lbs_keywords'              => '',
        'logo'                      => '',
        'kefu_qrcode'               => '',
        'city_id'                   => 0,
        'site_one'                  => 0,
        'share_title'               => '',
        'share_desc'                => '',
        'share_pic'                 => '',
        'virtual_clicks'            => 0,
        'virtual_fabunum'           => 0,
        'virtual_rznum'             => 0,
        'fl_fc_scale'               => 0,
        'shop_fc_scale'             => 0,
        'qg_fc_scale'               => 0,
        'pt_fc_scale'               => 0,
        'kj_fc_scale'               => 0,
        'dingyue_qrcode'            => '',
        'hehuoren_fc_open'          => 2,
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['sites_manage_user_id'],'name'=>'manage_user_id','value'=>$options['manage_user_id'],'msg'=>$Lang['sites_manage_user_id_msg']),"input");
    $cityList = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_level(1);
    $cityStr = '<tr class="header"><th>'.$Lang['sites_city'].'</th><th></th></tr>';
    $cityStr.= '<tr><td width="300"><select style="width: 260px;" name="city_id" id="city_id">';
    foreach ($cityList as $key => $value){
        if($value['id'] == $options['city_id']){
            $cityStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
        }else{
            $cityStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
    }
    $cityStr.= '</select></td><td>'.$Lang['sites_city_msg'].'</td></tr>';
    echo $cityStr;
    $site_one_item = array(0=>$Lang['sites_site_one_0'],1=>$Lang['sites_site_one_1']);
    tomshowsetting(true,array('title'=>$Lang['sites_site_one'],'name'=>'site_one','value'=>$options['site_one'],'msg'=>$Lang['sites_site_one_msg'],'item'=>$site_one_item),"radio");
    tomshowsetting(true,array('title'=>$Lang['sites_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['sites_name_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_lbs_name'],'name'=>'lbs_name','value'=>$options['lbs_name'],'msg'=>$Lang['sites_lbs_name_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_lbs_keywords'],'name'=>'lbs_keywords','value'=>$options['lbs_keywords'],'msg'=>$Lang['sites_lbs_keywords_msg']),"input");
    $open_hehuoren_item = array(1=>$Lang['open'],2=>$Lang['close']);
    tomshowsetting(true,array('title'=>$Lang['sites_hehuoren_fc_open'],'name'=>'hehuoren_fc_open','value'=>$options['hehuoren_fc_open'],'msg'=>$Lang['sites_hehuoren_fc_open_msg'],'item'=>$open_hehuoren_item),"radio");
    tomshowsetting(true,array('title'=>$Lang['sites_fl_fc_scale'],'name'=>'fl_fc_scale','value'=>$options['fl_fc_scale'],'msg'=>'<font color="#fd0d0d">%</font>&nbsp;&nbsp;'.$Lang['sites_fl_fc_scale_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_shop_fc_scale'],'name'=>'shop_fc_scale','value'=>$options['shop_fc_scale'],'msg'=>'<font color="#fd0d0d">%</font>&nbsp;&nbsp;'.$Lang['sites_shop_fc_scale_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_qg_fc_scale'],'name'=>'qg_fc_scale','value'=>$options['qg_fc_scale'],'msg'=>'<font color="#fd0d0d">%</font>&nbsp;&nbsp;'.$Lang['sites_qg_fc_scale_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_pt_fc_scale'],'name'=>'pt_fc_scale','value'=>$options['pt_fc_scale'],'msg'=>'<font color="#fd0d0d">%</font>&nbsp;&nbsp;'.$Lang['sites_pt_fc_scale_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_kj_fc_scale'],'name'=>'kj_fc_scale','value'=>$options['kj_fc_scale'],'msg'=>'<font color="#fd0d0d">%</font>&nbsp;&nbsp;'.$Lang['sites_kj_fc_scale_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_logo'],'name'=>'logo','value'=>$options['logo'],'msg'=>$Lang['sites_logo_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['sites_kefu_qrcode'],'name'=>'kefu_qrcode','value'=>$options['kefu_qrcode'],'msg'=>$Lang['sites_kefu_qrcode_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['sites_dingyue_qrcode'],'name'=>'dingyue_qrcode','value'=>$options['dingyue_qrcode'],'msg'=>$Lang['sites_dingyue_qrcode_msg']),"file");
    
    tomshowsetting(true,array('title'=>$Lang['virtual_clicks'],'name'=>'virtual_clicks','value'=>$options['virtual_clicks'],'msg'=>$Lang['virtual_clicks_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['virtual_fabunum'],'name'=>'virtual_fabunum','value'=>$options['virtual_fabunum'],'msg'=>$Lang['virtual_fabunum_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['virtual_rznum'],'name'=>'virtual_rznum','value'=>$options['virtual_rznum'],'msg'=>$Lang['virtual_rznum_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_share_title'],'name'=>'share_title','value'=>$options['share_title'],'msg'=>$Lang['sites_share_title_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_share_desc'],'name'=>'share_desc','value'=>$options['share_desc'],'msg'=>$Lang['sites_share_desc_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['sites_share_pic'],'name'=>'share_pic','value'=>$options['share_pic'],'msg'=>$Lang['sites_share_pic_msg']),"file");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['sites_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['sites_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['sites_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['sites_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['sites_edit'],"",true);
    }else{
        tomshownavli($Lang['sites_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['sites_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}


