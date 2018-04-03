<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=index';
$modListUrl = $adminListUrl.'&tmod=index';
$modFromUrl = $adminFromUrl.'&tmod=index';

$get_list_url_value = get_list_url("tom_tcshop_admin_index_list");
if($get_list_url_value){
    $modListUrl = $get_list_url_value;
}

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        $insertData['ruzhu_time']  = TIMESTAMP;
        C::t('#tom_tcshop#tom_tcshop')->insert($insertData);
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
    $tcshopInfo = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($tcshopInfo);
        if($tcshopInfo['lbs_status'] == 1){
            $updateData['lbs_status']       = 2;
        }
        C::t('#tom_tcshop#tom_tcshop')->update($tcshopInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($tcshopInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'editcate'){
    
    $info = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($_GET['id']);
    
    $cate_id            = isset($_GET['cate_id'])? intval($_GET['cate_id']):$info['cate_id'];
    $cate_child_id      = isset($_GET['cate_child_id'])? intval($_GET['cate_child_id']):$info['cate_child_id'];
    
    if(submitcheck('submit')){
        $updateData = array();
        $updateData['cate_id']          = $cate_id;
        $updateData['cate_child_id']    = $cate_child_id;
        C::t('#tom_tcshop#tom_tcshop')->update($_GET['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        
        tomloadcalendarjs();
        showformheader($modFromUrl.'&act=editcate&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition"><font color="#238206">'.$info['name'].'</font>&nbsp;>&nbsp;' . $Lang['edit_cate_title'] .'</th></tr>';
        
        $cateList = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_all_list(" AND parent_id=0 "," ORDER BY csort ASC,id DESC ",0,100);
        $cateStr = '<tr class="header"><th>'.$Lang['tcshop_cate_id'].'</th><th></th></tr>';
        $cateStr.= '<tr><td width="300"><select style="width: 260px;" name="cate_id" id="cate_id" onchange="refreshcate();">';
        $cateStr.=  '<option value="0">'.$Lang['tcshop_cate_id'].'</option>';
        foreach ($cateList as $key => $value){
            if($value['id'] == $cate_id){
                $cateStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
            }else{
                $cateStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
        }
        $cateStr.= '</select></td><td></td></tr>';
        echo $cateStr;
        
        if($cate_id > 0){
            $childCateList = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_all_list(" AND parent_id={$cate_id} "," ORDER BY csort ASC,id DESC ",0,100);
            $childCateStr = '<tr class="header"><th>'.$Lang['tcshop_cate_child_id'].'</th><th></th></tr>';
            $childCateStr.= '<tr><td width="300"><select style="width: 260px;" name="cate_child_id" id="cate_child_id">';
            $childCateStr.=  '<option value="0">'.$Lang['tcshop_cate_child_id'].'</option>';
            foreach ($childCateList as $key => $value){
                if($value['id'] == $cate_child_id){
                    $childCateStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
                }else{
                    $childCateStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                }
            }
            $childCateStr.= '</select></td><td></td></tr>';
            echo $childCateStr;
        
            showsubmit('submit', 'submit');
        }
        showtablefooter();
        showformfooter();
        
        $adminurl = $modBaseUrl.'&act=editcate&id='.$_GET['id'].'&formhash='.FORMHASH;
        echo <<<SCRIPT
<script type="text/javascript">

function refreshcate() {
	location.href = "$adminurl"+"&cate_id="+jq('#cate_id').val();
}
</script>
SCRIPT;
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'editregion'){
    
    $info = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($_GET['id']);
    
    $area_id            = isset($_GET['area_id'])? intval($_GET['area_id']):$info['area_id'];
    $street_id          = isset($_GET['street_id'])? intval($_GET['street_id']):$info['street_id'];
    
    if(submitcheck('submit')){
        $updateData = array();
        $updateData['area_id']          = $area_id;
        $updateData['street_id']        = $street_id;
        C::t('#tom_tcshop#tom_tcshop')->update($_GET['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        
        tomloadcalendarjs();
        showformheader($modFromUrl.'&act=editregion&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition"><font color="#238206">'.$info['name'].'</font>&nbsp;>&nbsp;' . $Lang['edit_region_title'] .'</th></tr>';
        if($info['site_id'] > 1){
            $siteInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($info['site_id']);
            $cityInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($siteInfoTmp['city_id']);
            if($cityInfoTmp){
                $__CityInfo = $cityInfoTmp;
            }
        }else{
            $cityInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_level1_name($tongchengConfig['city_name']);
            if($cityInfoTmp){
                $__CityInfo = $cityInfoTmp;
            }
        }
        
        $areaList = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($__CityInfo['id']);
        $areaStr = '<tr class="header"><th>'.$Lang['tcshop_region_area_id'].'</th><th></th></tr>';
        $areaStr.= '<tr><td width="300"><select style="width: 260px;" name="area_id" id="area_id" onchange="refresharea();">';
        $areaStr.=  '<option value="0">'.$Lang['tcshop_region_area_id'].'</option>';
        foreach ($areaList as $key => $value){
            if($value['id'] == $area_id){
                $areaStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
            }else{
                $areaStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
        }
        $areaStr.= '</select></td><td></td></tr>';
        echo $areaStr;
        
        if($area_id > 0){
            $childAreaList = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($area_id);
            $childAreaStr = '<tr class="header"><th>'.$Lang['tcshop_region_street_id'].'</th><th></th></tr>';
            $childAreaStr.= '<tr><td width="300"><select style="width: 260px;" name="street_id" id="street_id">';
            $childAreaStr.=  '<option value="0">'.$Lang['tcshop_region_street_id'].'</option>';
            foreach ($childAreaList as $key => $value){
                if($value['id'] == $street_id){
                    $childAreaStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
                }else{
                    $childAreaStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                }
            }
            $childAreaStr.= '</select></td><td></td></tr>';
            echo $childAreaStr;
        
            showsubmit('submit', 'submit');
        }
        showtablefooter();
        showformfooter();
        
        $adminurl = $modBaseUrl.'&act=editregion&id='.$_GET['id'].'&formhash='.FORMHASH;
        echo <<<SCRIPT
<script type="text/javascript">

function refresharea() {
	location.href = "$adminurl"+"&area_id="+jq('#area_id').val();
}
</script>
SCRIPT;
    }
}else if($_GET['act'] == 'photo'){
    
    $tcshop_id = $_GET['tcshop_id'];
    
    if(submitcheck('submit')){
        
        $upload = new tom_upload();
        if(!$upload->init($_FILES["picurl"], 'tomwx', random(3, 1), random(8)) || !$upload->save()) {
            cpmsg($upload->errormessage(), '', 'error');
        }
        
        $picurl = $upload->attach['attachment'];
        
        ## thumb start
        $imageData = file_get_contents($upload->attach['target']);
        $imageDir = "/source/plugin/tom_tcshop/data/photo/".date("Ym")."/";
        $imageName = "source/plugin/tom_tcshop/data/photo/".date("Ym")."/".md5($upload->attach['attachment']).".jpg";

        $tomDir = DISCUZ_ROOT.'.'.$imageDir;
        if(!is_dir($tomDir)){
            mkdir($tomDir, 0777,true);
        }else{
            chmod($tomDir, 0777);
        }
        file_put_contents(DISCUZ_ROOT.'./'.$imageName, $imageData);

        require_once libfile('class/image');
        $image = new image();
        $image->Thumb(DISCUZ_ROOT.'./'.$imageName,'', 640, 320, 2, 1);
        ## thumb end
        
        $insertData = array();
        $insertData['tcshop_id']    = $tcshop_id;
        $insertData['picurl']       = $picurl;
        $insertData['thumb']        = $imageName;
        $insertData['add_time']     = TIMESTAMP;
        C::t('#tom_tcshop#tom_tcshop_photo')->insert($insertData);
        
        cpmsg($Lang['act_success'], $adminListUrl.'&tmod=index'."&act=photo&tcshop_id=".$_GET['tcshop_id'], 'succeed');
        
    }
    
    $tcshopInfo = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($_GET['tcshop_id']);
    $pagesize = 15;
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_tcshop#tom_tcshop_photo')->fetch_all_count(" AND tcshop_id={$tcshop_id} ");
    $photoList = C::t('#tom_tcshop#tom_tcshop_photo')->fetch_all_list(" AND tcshop_id={$tcshop_id} ","ORDER BY id ASC",$start,$pagesize);
    __create_nav_html();
    showformheader($modFromUrl.'&act=photo&tcshop_id='.$tcshop_id,'enctype');
    showtableheader();
    tomshowsetting(true,array('title'=>$Lang['photo_picurl'],'name'=>'picurl','value'=>$options['picurl'],'msg'=>$Lang['photo_picurl_msg']),"file");
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
    
    $modBasePageUrl = $adminBaseUrl.'&tmod=index&act=photo&tcshop_id='.$tcshop_id.'&formhash='.FORMHASH;
    
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['photo_picurl'] . '</th>';
    echo '<th>' . $Lang['photo_thumb_picurl'] . '</th>';
    echo '<th>' . $Lang['photo_type'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    foreach ($photoList as $key => $value) {
        echo '<tr>';
        echo '<td><img src="' . tomgetfileurl($value['picurl']) . '" width="60" /></td>';
        echo '<td><img src="' . $value['thumb'] . '" width="60" /></td>';
        if($value['type_id'] == 1){
            echo '<td>'. $Lang['photo_type_1'] .'</td>';
        }else if($value['type_id'] == 2){
            echo '<td>'. $Lang['photo_type_2'] .'</td>';
        }
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=delphoto&id='.$value['id'].'&tcshop_id='.$tcshop_id.'&formhash='.FORMHASH.'">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBasePageUrl);
    showsubmit('', '', '', '', $multi, false);
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'delphoto'){
    
    C::t('#tom_tcshop#tom_tcshop_photo')->delete_by_id($_GET['id']);
    
    cpmsg($Lang['act_success'], $adminListUrl.'&tmod=index'."&act=photo&tcshop_id=".$_GET['tcshop_id'], 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'shenhe_ok'){
    
    $updateData = array();
    $updateData['shenhe_status']     = 1;
    C::t('#tom_tcshop#tom_tcshop')->update($_GET['id'],$updateData);
    
    $tcshopInfo = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($_GET['id']);
    $tcUserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tcshopInfo['user_id']);
    
    $shenhe = str_replace('{SHOPNAME}', $tcshopInfo['name'], $Lang['template_tcshop_shenhe_ok']);

    $access_token = $weixinClass->get_access_token();
    if($access_token && !empty($tcUserInfo['openid'])){
        $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_tcshop&site={$tcshopInfo['site_id']}&mod=details&tcshop_id=".$tcshopInfo['id']);
        $smsData = array(
            'first'         => $shenhe,
            'keyword1'      => $tcshopConfig['plugin_name'],
            'keyword2'      => dgmdate(TIMESTAMP,"Y-m-d H:i:s",$tomSysOffset),
            'remark'        => ''
        );

        @$r = $templateSmsClass->sendSms01($tcUserInfo['openid'], $tongchengConfig['template_id'], $smsData);
        if($r){
            $cpmsg = $Lang['tcshop_shenhe_tz_succ'];
        }else{
            $cpmsg = $Lang['tcshop_shenhe_tz_fail'];
        }
    }
    
    $insertData = array();
    $insertData['user_id']      = $tcUserInfo['id'];
    $insertData['type']         = 1;
    $insertData['content']      = '<font color="#238206">'.$tcshopConfig['plugin_name'].'</font><br/>'.$shenhe.'<br/>'.dgmdate(TIMESTAMP,"Y-m-d H:i:s",$tomSysOffset);
    $insertData['is_read']      = 0;
    $insertData['tz_time']      = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng_tz')->insert($insertData);
    
    cpmsg($cpmsg, $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'shenhe_no'){
    
    $tcshopInfo = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($_GET['id']);
    
    if(submitcheck('submit')){
        $text = isset($_GET['text'])? addslashes($_GET['text']):'';
        
        $tcUserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tcshopInfo['user_id']);
        
        $updateData = array();
        $updateData['shenhe_status']     = 3;
        C::t('#tom_tcshop#tom_tcshop')->update($_GET['id'],$updateData);
        
        $shenhe = str_replace('{SHOPNAME}', $tcshopInfo['name'], $Lang['template_tcshop_shenhe_no']);
        
        $access_token = $weixinClass->get_access_token();
        if($access_token && !empty($tcUserInfo['openid'])){
            $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_tcshop&site={$tcshopInfo['site_id']}&mod=edit&tcshop_id=".$tcshopInfo['id']);
            $smsData = array(
                'first'         => $shenhe,
                'keyword1'      => $tcshopConfig['plugin_name'],
                'keyword2'      => dgmdate(TIMESTAMP,"Y-m-d H:i:s",$tomSysOffset),
                'remark'        => $text
            );
            @$r = $templateSmsClass->sendSms01($tcUserInfo['openid'], $tongchengConfig['template_id'], $smsData);
            if($r){
                $cpmsg = $Lang['tcshop_shenhe_tz_succ'];
            }else{
                $cpmsg = $Lang['tcshop_shenhe_tz_fail'];
            }
        }
        
        $insertData = array();
        $insertData['user_id']      = $tcUserInfo['id'];
        $insertData['type']         = 1;
        $insertData['content']      = '<font color="#238206">'.$tcshopConfig['plugin_name'].'</font><br/>'.$shenhe.'<br/>'.$Lang['tcshop_shenhe_fail_title'].$text.'<br/>'.dgmdate(TIMESTAMP,"Y-m-d H:i:s",$tomSysOffset);
        $insertData['is_read']      = 0;
        $insertData['tz_time']      = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_tz')->insert($insertData);
        
        cpmsg($cpmsg, $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=shenhe_no&id='.$_GET['id'],'enctype');
        showtableheader();
        tomshowsetting(true,array('title'=>$Lang['tcshop_shenhe_fail_title'],'name'=>'text','value'=>'','msg'=>$Lang['tcshop_shenhe_fail_title_msg']),"textarea");
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'pay'){
    
    $pay_status     = isset($_GET['pay_status'])? intval($_GET['pay_status']):1;
    
    $updateData = array();
    $updateData['pay_status']     = $pay_status;
    C::t('#tom_tcshop#tom_tcshop')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'edittop'){
    $info = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $toptime     = isset($_GET['toptime'])? addslashes($_GET['toptime']):'';
        $toptime     = strtotime($toptime);
        $updateData = array();
        if($toptime < TIMESTAMP){
            $updateData['topstatus'] = 0;
        }else{
            $updateData['topstatus'] = 1;
        }
        $updateData['toptime'] = $toptime;
        C::t('#tom_tcshop#tom_tcshop')->update($_GET['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        showformheader($modFromUrl.'&act=edittop&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $Lang['edit_top_title'] .'</th></tr>';
        tomshowsetting(true,array('title'=>$Lang['edit_top_time'],'name'=>'toptime','value'=>$info['toptime'],'msg'=>$Lang['edit_top_time_msg']),"calendar");
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'editvip'){
    $info = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $vip_time     = isset($_GET['vip_time'])? addslashes($_GET['vip_time']):'';
        $vip_time     = strtotime($vip_time);
        $updateData = array();
        if($vip_time < TIMESTAMP){
            $updateData['vip_level'] = 0;
        }else{
            $updateData['vip_level'] = 1;
        }
        $updateData['vip_time'] = $vip_time;
        C::t('#tom_tcshop#tom_tcshop')->update($_GET['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        showformheader($modFromUrl.'&act=editvip&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $Lang['edit_vip_title'] .'</th></tr>';
        tomshowsetting(true,array('title'=>$Lang['edit_vip_time'],'name'=>'vip_time','value'=>$info['vip_time'],'msg'=>$Lang['edit_vip_time_msg']),"calendar");
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'editbase'){
    $info = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $base_level    = isset($_GET['base_level'])? intval($_GET['base_level']):1;
        $base_time     = isset($_GET['base_time'])? addslashes($_GET['base_time']):'';
        $base_time     = strtotime($base_time);
        $updateData = array();
        if($base_level == 1){
            $updateData['base_level'] = 1;
            $updateData['base_time'] = 0;
        }else if($base_level == 2){
            $updateData['base_level'] = 2;
            $updateData['base_time'] = $base_time;
        }else{
            $updateData['status'] = 3;
            $updateData['base_level'] = 2;
            $updateData['base_time'] = TIMESTAMP;
        }
        C::t('#tom_tcshop#tom_tcshop')->update($_GET['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        showformheader($modFromUrl.'&act=editbase&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $Lang['edit_base_title'] .'</th></tr>';
        $base_level_item = array(1=>$Lang['edit_base_level_1'],2=>$Lang['edit_base_level_2'],3=>$Lang['edit_base_level_3']);
        tomshowsetting(true,array('title'=>$Lang['edit_base_level'],'name'=>'base_level','value'=>$info['base_level'],'msg'=>$Lang['edit_base_level_msg'],'item'=>$base_level_item),"radio");
        tomshowsetting(true,array('title'=>$Lang['edit_base_time'],'name'=>'base_time','value'=>$info['base_time'],'msg'=>$Lang['edit_base_time_msg']),"calendar");
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'show'){
    
    $updateData = array();
    $updateData['status']     = 1;
    C::t('#tom_tcshop#tom_tcshop')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'hide'){
    
    $updateData = array();
    $updateData['status']     = 2;
    C::t('#tom_tcshop#tom_tcshop')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tcshop#tom_tcshop')->delete_by_id($_GET['id']);
    
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else{
    
    set_list_url("tom_tcshop_admin_index_list");
    
    $csstr = <<<EOF
<style type="text/css">
.tc_header_box { max-width: 120px; }
.tc_header_box li{ list-style-type: none; padding: 2px;    text-align: center; }
.tc_content_box { max-width: 500px; }
.tc_content_box_clear{ clear: both; height: 1px; }
.tc_content_box_tag li{ margin-bottom: 2px;list-style-type: none; float: left; height: 20px; margin-right: 10px; padding-right: 10px; padding-left: 10px; line-height: 20px;}
.tc_content_box_attr li{ list-style-type: none; height: 20px; line-height: 20px; }
.tc_content_box_attr li span{ color: #666; }
.tc_content_box_photo li{ list-style-type: none; height: 40px; line-height: 25px; width: 40px; margin: 5px; float: left; }
.span_model{color: #F75000 !important;border: 1px solid #F75000; height: 25px; width: 100px; line-height: 25px; text-align: center; color: #FFF; display: block; margin-bottom: 5px;}
.span_type{color: #0080FF !important;border: 1px solid #0080FF; height: 25px; width: 100px; line-height: 25px; text-align: center; color: #FFF;  display: block;margin-bottom: 5px;}
.span_cate{color: #009500 !important;border: 1px solid #009500; height: 25px; width: 100px; line-height: 25px; text-align: center; color: #FFF;  display: block;margin-bottom: 5px;}
.tc_content_box_content{ border: 1px dashed #caced0; padding: 5px; }
.tc_content_box_status li{ list-style-type: none; height: 25px; line-height: 25px; }
.tc_content_box_handle li{ list-style-type: none; height: 25px; line-height: 25px; }
.tc_content_box_handle li a{ border: 1px solid #d6d4d3;padding: 3px 10px;color: #6a6d6a; }
.tc_content_box_handle li a:hover{color: #F75000;border: 1px solid #F75000;}
.tc_content_box_status li span{ color: #666; }
</style>
EOF;
    echo $csstr;
    
    $site_id        = isset($_GET['site_id'])? intval($_GET['site_id']):0;
    $keyword        = isset($_GET['keyword'])? addslashes($_GET['keyword']):'';
    $shenhe_status  = isset($_GET['shenhe_status']) ? intval($_GET['shenhe_status']) : 0;
    $page           = intval($_GET['page'])>0? intval($_GET['page']):1;
    
    $where = "";
    if(!empty($site_id)){
        $where.= " AND site_id={$site_id} ";
    }
    if($shenhe_status > 0){
        $where .= " AND shenhe_status = {$shenhe_status} ";
    }
    
    $pagesize = 8;
    if(!empty($keyword)){
        $pagesize       = 50;
    }
    $start = ($page-1)*$pagesize;
    $count      = C::t('#tom_tcshop#tom_tcshop')->fetch_all_count("{$where}",$keyword);
    $tcshopList = C::t('#tom_tcshop#tom_tcshop')->fetch_all_list("{$where}"," ORDER BY id DESC ",$start,$pagesize,$keyword);
    
    showtableheader();
    $Lang['tcshop_help_3']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['tcshop_help_3']);
    $Lang['tcshop_help_4']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['tcshop_help_4']);
    $Lang['tcshop_help_5']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['tcshop_help_5']);
    echo '<tr><th colspan="15" class="partition">' . $Lang['tcshop_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['tcshop_help_3'] . '</li>';
    if($tongchengConfig['open_yun'] == 2){
        echo '<li>' . $Lang['tcshop_help_4'] . '</li>';
    }else if($tongchengConfig['open_yun'] == 3){
        echo '<li>' . $Lang['tcshop_help_5'] . '</li>';
    }
    echo '</ul></td></tr>';
    showtablefooter();
    
    $modBasePageUrl = $modBaseUrl."&site_id={$site_id}";
    
    showformheader($modFromUrl.'&formhash='.FORMHASH);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['index_search_list'] . '</th></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['index_search_keyword'] . '</b></td><td><input name="keyword" type="text" value="'.$keyword.'" size="40" /></td></tr>';
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
    
    tomshownavheader();
    tomshownavli($Lang['xieyi_title'],$adminBaseUrl.'&tmod=common',false);
    tomshownavli($Lang['vip_title'],$adminBaseUrl.'&tmod=common',false);
    tomshownavli($Lang['pinglun_list'],$adminBaseUrl.'&tmod=pinglun',false);
    tomshownavli($Lang['pinglun_reply_list'],$adminBaseUrl.'&tmod=pinglunReplay',false);
    tomshownavli($Lang['resou_list_title'],$adminBaseUrl.'&tmod=resou',false);
    tomshownavfooter();

    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['tcshop_picurl'] . '</th>';
    echo '<th>' . $Lang['tcshop_name'] . '</th>';
    echo '<th>' . $Lang['tcshop_business_licence'] . '</th>';
    echo '<th>' . $Lang['tcshop_cate'] . '</th>';
    echo '<th>' . $Lang['tcshop_status'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($tcshopList as $key => $value) {
        
        $cateInfoTmp = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_by_id($value['cate_id']);
        $cateChildInfoTmp = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_by_id($value['cate_child_id']);
        $siteInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($value['site_id']);
        $areaInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($value['area_id']);
        $streetInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($value['street_id']);
        
        if($value['tj_hehuoren_id'] && file_exists(DISCUZ_ROOT.'./source/plugin/tom_tchehuoren/tom_tchehuoren.inc.php')){
            $tchehuorenInfoTmp = C::t('#tom_tchehuoren#tom_tchehuoren')->fetch_by_id($value['tj_hehuoren_id']);
            $tjHehuoren = '<font color="#0a9409">'.$tchehuorenInfoTmp['xm'].'('.$tchehuorenInfoTmp['id'].')</font>';
        }else{
            $tjHehuoren = '';
        }
        
        if(!preg_match('/^http/', $value['picurl']) ){
            $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
        }else{
            $picurl = $value['picurl'];
        }
        
        if(!preg_match('/^http/', $value['business_licence']) ){
            $business_licence = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['business_licence'];
        }else{
            $business_licence = $value['business_licence'];
        }
        
        echo '<tr style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #70b4e6;">';
        echo '<td><img src="'.$picurl.'" width="40" /></td>';
        echo '<td>';
            echo '<div>'. $value['name'] .'<font color="#fd0d0d">(ID:'. $value['id'] .')</font></div>';
            if(!empty($value['shopkeeper_tel'])){
                echo '<div>'. $Lang['tcshop_shopkeeper_tel'] .'<font color="#0a9409">'. $value['shopkeeper_tel'] .'</font></div>';
            }
            echo '<div style="margin-top:10px;">'. $tjHehuoren .'</div>';
        echo '</td>';
        echo '<td><a href="'.$business_licence.'" target="_blank"><img src="'.$business_licence.'" width="40" /></a></td>';
        echo '<td>';
        if($value['site_id'] > 1){
            echo '<span class="span_cate">'.$siteInfoTmp['name'].'</span>';
        }else{
            echo '<span class="span_cate">'.$Lang['sites_one'].'</span>';
        }
        if($cateInfoTmp){
            echo '<span class="span_model">' . $cateInfoTmp['name'] . '</span>';
        }
        if($cateChildInfoTmp){
            echo '<span class="span_type">' . $cateChildInfoTmp['name'] . '</span>';
        }
        echo '<b>' . $areaInfoTmp['name'] .' '.$streetInfoTmp['name']. '</b>';
        echo '</td>';
        echo '<td><div class="tc_content_box_status"><ul>';
        if($value['is_ok'] == 1){
            echo '<li><b>'.$Lang['tcshop_is_ok'].'&nbsp;:&nbsp;</b><font color="#0a9409">' . $Lang['tcshop_is_ok_1'] . '</font></li>';
        }else{
            echo '<li><b>'.$Lang['tcshop_is_ok'].'&nbsp;:&nbsp;</b><font color="#f00">' . $Lang['tcshop_is_ok_0'] . '</font></li>';
        }
        $payBtnStr = '&nbsp;(&nbsp;<a href="javascript:;" onclick="pay_confirm(\''.$modBaseUrl.'&act=pay&id='.$value['id'].'&pay_status=1&formhash='.FORMHASH.'\', \''.$Lang['tcshop_pay_status_1_msg'].'\');">' . $Lang['tcshop_pay_status_1']. '</a>&nbsp;|&nbsp;<a href="javascript:;" onclick="pay_confirm(\''.$modBaseUrl.'&act=pay&id='.$value['id'].'&pay_status=2&formhash='.FORMHASH.'\', \''.$Lang['tcshop_pay_status_2_msg'].'\');">' . $Lang['tcshop_pay_status_2']. '</a>)';
        if($value['pay_status'] == 1){
            echo '<li><b>'.$Lang['tcshop_pay_status'].'&nbsp;:&nbsp;</b><font color="#f00">' . $Lang['tcshop_pay_status_1'] . $payBtnStr . '</font></li>';
        }else if($value['pay_status'] == 2){
            echo '<li><b>'.$Lang['tcshop_pay_status'].'&nbsp;:&nbsp;</b><font color="#0a9409">' . $Lang['tcshop_pay_status_2'] . $payBtnStr . '</font></li>';
        }
        if($value['vip_level'] == 1 ){
            echo '<li><b>'.$Lang['tcshop_vip_level'].'&nbsp;:&nbsp;</b><font color="#0a9409">' . $Lang['tcshop_vip_level_1'] . '</font>&nbsp;&nbsp;<font color="#f00">(' . dgmdate($value['vip_time'],"Y-m-d",$tomSysOffset) . '&nbsp;' . $Lang['tcshop_vip_time'] . ')</font></li>';
        }else{
            echo '<li>';
            echo '<b>'.$Lang['tcshop_vip_level'].'&nbsp;:&nbsp;</b><font color="#0a9409">' . $Lang['tcshop_vip_level_0'] . '</font>&nbsp;&nbsp;';
            if($value['base_level'] == 1){
                echo '<font color="#f00">(' . $Lang['tcshop_yongjiu_time'] . ')</font>';
            }else if($value['base_level'] == 2){
                echo '<font color="#f00">(' . dgmdate($value['base_time'],"Y-m-d",$tomSysOffset) . '&nbsp;' . $Lang['tcshop_vip_time'] . ')</font>';
            }
            echo '</li>';
        }
        if($value['topstatus'] == 1 ){
            echo '<li><b>'.$Lang['tcshop_topstatus'].'&nbsp;:&nbsp;</b><font color="#0a9409">' . $Lang['tcshop_topstatus_1'] . '</font>&nbsp;&nbsp;<font color="#f00">(' . dgmdate($value['toptime'],"Y-m-d",$tomSysOffset) . '&nbsp;' . $Lang['tcshop_toptime'] . ')</font></li>';
        }
        $sheheBtnStr = '&nbsp;(&nbsp;<a href="'.$modBaseUrl.'&act=shenhe_ok&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['tcshop_shenhe_btn_1']. '</a>&nbsp;|&nbsp;<a href="'.$modBaseUrl.'&act=shenhe_no&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['tcshop_shenhe_btn_3']. '</a>)';
        if($value['shenhe_status'] == 1 ){
            echo '<li><b>'.$Lang['tcshop_shenhe_status'].'&nbsp;:&nbsp;</b><font color="#0a9409">' . $Lang['tcshop_shenhe_status_1'] . '</font>'.$sheheBtnStr.'</li>';
        }else if($value['shenhe_status'] == 2){
            echo '<li><b>'.$Lang['tcshop_shenhe_status'].'&nbsp;:&nbsp;</b><font color="#f70404">' . $Lang['tcshop_shenhe_status_2'] . '</font>'.$sheheBtnStr.'</li>';
        }else if($value['shenhe_status'] == 3){
            echo '<li><b>'.$Lang['tcshop_shenhe_status'].'&nbsp;:&nbsp;</b><font color="#f70404">' . $Lang['tcshop_shenhe_status_3'] . '</font>'.$sheheBtnStr.'</li>';
        }
        if($value['status'] == 1 ){
            echo '<li><b>'.$Lang['tcshop_status'].'&nbsp;:&nbsp;</b><font color="#0a9409">' . $Lang['tcshop_status_1'] . '</font></li>';
        }else{
            echo '<li><b>'.$Lang['tcshop_status'].'&nbsp;:&nbsp;</b><font color="#f70404">' . $Lang['tcshop_status_2'] . '</font></li>';
        }
        echo '<li><b>'.$Lang['tcshop_ruzhu_time'].'&nbsp;:&nbsp;</b>' . dgmdate($value['ruzhu_time'],"Y-m-d H:i",$tomSysOffset) . '</li>';
        echo '</ul></div></td>';
        echo '<td><div class="tc_content_box_handle"><ul>';
        if($value['vip_level'] == 0 ){
            echo '<li><a href="'.$modBaseUrl.'&act=editbase&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['edit_base_title']. '</a></li>';
        }
        echo '<li><a href="'.$modBaseUrl.'&act=editvip&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['edit_vip_title']. '</a></li>';
        echo '<li><a href="'.$modBaseUrl.'&act=edittop&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['edit_top_title']. '</a></li>';
        if($value['status'] == 1 ){
            echo '<li><a href="'.$modBaseUrl.'&act=hide&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['tcshop_status_2']. '</a></li>';
        }else{
            echo '<li><a href="'.$modBaseUrl.'&act=show&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['tcshop_status_1']. '</a></li>';
        }
        echo '<li><a href="'.$modBaseUrl.'&act=editcate&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['edit_cate_title']. '</a></li>';
        echo '<li><a href="'.$modBaseUrl.'&act=photo&tcshop_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['tcshop_photo']. '</a></li>';
        echo '<li><a href="'.$modBaseUrl.'&act=editregion&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['edit_region_title']. '</a></li>';
        echo '<li><a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['tcshop_edit']. '</a></li>';
        echo '<li><a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a></li>';
        echo '</ul></div></td>';
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

function pay_confirm(url,msg){
  var r = confirm(msg)
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
    
    $site_id            = isset($_GET['site_id'])? intval($_GET['site_id']):1;
    $user_id            = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    $name               = isset($_GET['name'])? addslashes($_GET['name']):'';
    $tel                = isset($_GET['tel'])? addslashes($_GET['tel']):'';
    $shopkeeper_tel     = isset($_GET['shopkeeper_tel'])? addslashes($_GET['shopkeeper_tel']):'';
    $business_hours     = isset($_GET['business_hours'])? addslashes($_GET['business_hours']):'';
    $tabs               = isset($_GET['tabs'])? addslashes($_GET['tabs']):'';
    $latitude           = isset($_GET['latitude'])? addslashes($_GET['latitude']):'';
    $longitude          = isset($_GET['longitude'])? addslashes($_GET['longitude']):'';
    $address            = isset($_GET['address'])? addslashes($_GET['address']):'';
    $admin_edit         = isset($_GET['admin_edit'])? intval($_GET['admin_edit']):0;
    $content            = isset($_GET['content'])? addslashes($_GET['content']):'';
    $zan_txt            = isset($_GET['zan_txt'])? addslashes($_GET['zan_txt']):'';
    $gonggao            = isset($_GET['gonggao'])? addslashes($_GET['gonggao']):'';
    $video_url          = isset($_GET['video_url'])? addslashes($_GET['video_url']):'';
    $mp3_url            = isset($_GET['mp3_url'])? addslashes($_GET['mp3_url']):'';
    $link_name          = isset($_GET['link_name'])? addslashes($_GET['link_name']):'';
    $link               = isset($_GET['link'])? addslashes($_GET['link']):'';
    
    $picurl = "";
    if($_GET['act'] == 'add'){
        $picurl        = tomuploadFile("picurl");
    }else if($_GET['act'] == 'edit'){
        $picurl        = tomuploadFile("picurl",$infoArr['picurl']);
    }
    
    $kefu_qrcode = "";
    if($_GET['act'] == 'add'){
        $kefu_qrcode        = tomuploadFile("kefu_qrcode");
    }else if($_GET['act'] == 'edit'){
        $kefu_qrcode        = tomuploadFile("kefu_qrcode",$infoArr['kefu_qrcode']);
    }
    
    $business_licence = "";
    if($_GET['act'] == 'add'){
        $business_licence        = tomuploadFile("business_licence");
    }else if($_GET['act'] == 'edit'){
        $business_licence        = tomuploadFile("business_licence",$infoArr['business_licence']);
    }
    
    $data['site_id']            = $site_id;
    $data['user_id']            = $user_id;
    $data['name']               = $name;
    $data['picurl']             = $picurl;
    $data['tel']                = $tel;
    $data['shopkeeper_tel']     = $shopkeeper_tel;
    $data['business_hours']     = $business_hours;
    $data['tabs']               = $tabs;
    $data['business_licence']   = $business_licence;
    $data['latitude']           = $latitude;
    $data['longitude']          = $longitude;
    $data['address']            = $address;
    $data['kefu_qrcode']        = $kefu_qrcode;
    $data['admin_edit']         = $admin_edit;
    if($admin_edit == 1){
        $data['content']            = $content;
    }
    $data['zan_txt']            = $zan_txt;
    $data['gonggao']            = $gonggao;
    $data['video_url']          = $video_url;
    $data['mp3_url']            = $mp3_url;
    $data['link_name']          = $link_name;
    $data['link']               = $link;
    $data['is_ok']              = 1;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'site_id'           => 1,
        'user_id'           => 0,
        'name'              => '',
        'picurl'            => '',
        'business_licence'  => '',
        'tel'               => '',
        'shopkeeper_tel'    => '',
        'business_hours'    => '',
        'tabs'              => '',
        'latitude'          => '',
        'longitude'         => '',
        'address'           => '',
        'kefu_qrcode'       => '',
        'admin_edit'        => 0,
        'content'           => '',
        'zan_txt'           => '',
        'gonggao'           => '',
        'video_url'         => '',
        'mp3_url'           => '',
        'link_name'           => '',
        'link'           => '',
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['tcshop_site_id'],'name'=>'site_id','value'=>$options['site_id'],'msg'=>$Lang['tcshop_site_id_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['tcshop_user_id'],'name'=>'user_id','value'=>$options['user_id'],'msg'=>$Lang['tcshop_user_id_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['tcshop_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['tcshop_name_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['tcshop_picurl'],'name'=>'picurl','value'=>$options['picurl'],'msg'=>$Lang['tcshop_picurl_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['tcshop_tel'],'name'=>'tel','value'=>$options['tel'],'msg'=>$Lang['tcshop_tel_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['tcshop_shopkeeper_tel'],'name'=>'shopkeeper_tel','value'=>$options['shopkeeper_tel'],'msg'=>$Lang['tcshop_shopkeeper_tel_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['tcshop_business_hours'],'name'=>'business_hours','value'=>$options['business_hours'],'msg'=>$Lang['tcshop_business_hours_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['tcshop_tabs'],'name'=>'tabs','value'=>$options['tabs'],'msg'=>$Lang['tcshop_tabs_msg']),"textarea");
    tomshowsetting(true,array('title'=>$Lang['tcshop_business_licence'],'name'=>'business_licence','value'=>$options['business_licence'],'msg'=>$Lang['tcshop_business_licence_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['tcshop_latitude'],'name'=>'latitude','value'=>$options['latitude'],'msg'=>$Lang['tcshop_latitude_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['tcshop_longitude'],'name'=>'longitude','value'=>$options['longitude'],'msg'=>$Lang['tcshop_longitude_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['tcshop_address'],'name'=>'address','value'=>$options['address'],'msg'=>$Lang['tcshop_address_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['tcshop_kefu_qrcode'],'name'=>'kefu_qrcode','value'=>$options['kefu_qrcode'],'msg'=>$Lang['tcshop_kefu_qrcode_msg']),"file");
    
    $admin_edit_item = array(0=>$Lang['tcshop_admin_edit_0'],1=>$Lang['tcshop_admin_edit_1']);
    tomshowsetting(true,array('title'=>$Lang['tcshop_admin_edit'],'name'=>'admin_edit','value'=>$options['admin_edit'],'msg'=>$Lang['tcshop_admin_edit_msg'],'item'=>$admin_edit_item),"radio");
    
    tomshowsetting(true,array('title'=>$Lang['tcshop_content'],'name'=>'content','value'=>$options['content'],'msg'=>$Lang['tcshop_content_msg']),"text");
    tomshowsetting(true,array('title'=>$Lang['tcshop_zan_txt'],'name'=>'zan_txt','value'=>$options['zan_txt'],'msg'=>$Lang['tcshop_zan_txt_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['tcshop_gonggao'],'name'=>'gonggao','value'=>$options['gonggao'],'msg'=>$Lang['tcshop_gonggao_msg']),"textarea");
    tomshowsetting(true,array('title'=>$Lang['tcshop_video_url'],'name'=>'video_url','value'=>$options['video_url'],'msg'=>$Lang['tcshop_video_url_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['tcshop_link_name'],'name'=>'link_name','value'=>$options['link_name'],'msg'=>$Lang['tcshop_link_name_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['tcshop_link'],'name'=>'link','value'=>$options['link'],'msg'=>$Lang['tcshop_link_msg']),"input");
    //tomshowsetting(true,array('title'=>$Lang['tcshop_mp3_url'],'name'=>'mp3_url','value'=>$options['mp3_url'],'msg'=>$Lang['tcshop_mp3_url_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl,$shenhe_status;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['tcshop_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['tcshop_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['tcshop_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['tcshop_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['tcshop_edit'],"",true);
    }else if($_GET['act'] == 'photo'){
        tomshownavli($Lang['tcshop_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['tcshop_photo'],"",true);
    }else{
        tomshownavli($Lang['tcshop_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['tcshop_add'],$modBaseUrl."&act=add",false);
        if($shenhe_status == 2){
            tomshownavli($Lang['tcshop_shenhe_status_2'],$modBaseUrl."&shenhe_status=2",true);
            tomshownavli($Lang['tcshop_shenhe_status_3'],$modBaseUrl."&shenhe_status=3",false);
        }else if($shenhe_status == 3){
            tomshownavli($Lang['tcshop_shenhe_status_2'],$modBaseUrl."&shenhe_status=2",false);
            tomshownavli($Lang['tcshop_shenhe_status_3'],$modBaseUrl."&shenhe_status=3",true);
        }else{
            tomshownavli($Lang['tcshop_shenhe_status_2'],$modBaseUrl."&shenhe_status=2",false);
            tomshownavli($Lang['tcshop_shenhe_status_3'],$modBaseUrl."&shenhe_status=3",false);
        }
        
    }
    tomshownavfooter();
}


