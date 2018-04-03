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

$get_list_url_value = get_list_url("tom_tongcheng_admin_index_list");
if($get_list_url_value){
    $modListUrl = $get_list_url_value;
}

if($_GET['act'] == 'add'){
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'show'){
    
    $updateData = array();
    $updateData['status']     = $tongchengConfig['admin_tongcheng_show_value'];
    C::t('#tom_tongcheng#tom_tongcheng')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'hide'){
    
    $updateData = array();
    $updateData['status']     = $tongchengConfig['admin_tongcheng_hide_value'];
    C::t('#tom_tongcheng#tom_tongcheng')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'finish'){
    
    $updateData = array();
    $updateData['finish']     = $tongchengConfig['admin_tongcheng_finish_value'];
    C::t('#tom_tongcheng#tom_tongcheng')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'nofinish'){
    
    $updateData = array();
    $updateData['finish']     = 0;
    C::t('#tom_tongcheng#tom_tongcheng')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'shenhe'){
    
    $shenhe_status     = isset($_GET['shenhe_status'])? intval($_GET['shenhe_status']):1;
    
    $updateData = array();
    $updateData['shenhe_status']     = $shenhe_status;
    C::t('#tom_tongcheng#tom_tongcheng')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'refresh'){
    
    $updateData = array();
    $updateData['refresh_time']     = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'edittop'){
    $info = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $toptime     = isset($_GET['toptime'])? addslashes($_GET['toptime']):'';
        $toptime     = strtotime($toptime);
        $updateData = array();
        if($toptime < TIMESTAMP && $tongchengConfig['admin_tongcheng_top_a_value'] == 1){
            $updateData['topstatus'] = 0;
            $updateData['toprand']   = 1;
        }else{
            $updateData['topstatus'] = 1;
            $updateData['toprand'] = 10000;
            $updateData['refresh_time'] = TIMESTAMP;
        }
        $updateData['toptime'] = $toptime;
        C::t('#tom_tongcheng#tom_tongcheng')->update($_GET['id'],$updateData);
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
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'editcate'){
    $info = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($_GET['id']);
    
    $model_id     = isset($_GET['model_id'])? intval($_GET['model_id']):0;
    $type_id     = isset($_GET['type_id'])? intval($_GET['type_id']):0;
    $cate_id     = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
    
    if(submitcheck('submit')){
        $updateData = array();
        $updateData['model_id'] = $model_id;
        $updateData['type_id'] = $type_id;
        $updateData['cate_id'] = $cate_id;
        C::t('#tom_tongcheng#tom_tongcheng')->update($_GET['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        showformheader($modFromUrl.'&act=editcate&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        $content = cutstr(contentFormat($info['content']),20,"...");
        echo '<tr><th colspan="15" class="partition"><font color="#238206">'.$content.'</font>&nbsp;>&nbsp;' . $Lang['edit_cate_title'] .'</th></tr>';
        
        $modelList = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list(" "," ORDER BY paixu ASC,id DESC ",0,100);
        $modelStr = '<tr class="header"><th>'.$Lang['edit_cate_model'].'</th><th></th></tr>';
        $modelStr.= '<tr><td width="300"><select style="width: 260px;" name="model_id" id="model_id" onchange="refreshmodel();">';
        $modelStr.=  '<option value="0">'.$Lang['edit_cate_model'].'</option>';
        foreach ($modelList as $key => $value){
            if($value['id'] == $model_id){
                $modelStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
            }else{
                $modelStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
        }
        $modelStr.= '</select></td><td></td></tr>';
        echo $modelStr;
        
        $typeList = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_all_list(" AND model_id={$model_id} "," ORDER BY paixu ASC,id DESC ",0,100);
        $typeStr = '<tr class="header"><th>'.$Lang['edit_cate_type'].'</th><th></th></tr>';
        $typeStr.= '<tr><td width="300"><select style="width: 260px;" name="type_id" id="type_id" onchange="refreshtype();">';
        $typeStr.=  '<option value="0">'.$Lang['edit_cate_type'].'</option>';
        foreach ($typeList as $key => $value){
            if($value['id'] == $type_id){
                $typeStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
            }else{
                $typeStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
        }
        $typeStr.= '</select></td><td></td></tr>';
        echo $typeStr;
        
        $cateList = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_all_list(" AND type_id={$type_id} "," ORDER BY paixu ASC,id DESC ",0,100);
        $cateStr = '<tr class="header"><th>'.$Lang['edit_cate_cate'].'</th><th></th></tr>';
        $cateStr.= '<tr><td width="300"><select style="width: 260px;" name="cate_id" id="cate_id">';
        $cateStr.=  '<option value="0">'.$Lang['edit_cate_cate'].'</option>';
        foreach ($cateList as $key => $value){
            if($value['id'] == $cate_id){
                $cateStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
            }else{
                $cateStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
        }
        $cateStr.= '</select></td><td></td></tr>';
        echo $cateStr;
        
        if($type_id > 0){
            showsubmit('submit', 'submit');
        }
        showtablefooter();
        showformfooter();
        
        $adminurl = $modBaseUrl.'&act=editcate&id='.$_GET['id'].'&formhash='.FORMHASH;
        echo <<<SCRIPT
<script type="text/javascript">

function refreshmodel() {
	location.href = "$adminurl"+"&model_id="+jq('#model_id').val();
}
function refreshtype() {
	location.href = "$adminurl"+"&model_id="+jq('#model_id').val() + "&type_id="+jq('#type_id').val();
}
</script>
SCRIPT;
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tongcheng#tom_tongcheng')->delete_by_id($_GET['id']);
    C::t('#tom_tongcheng#tom_tongcheng_attr')->delete_by_tongcheng_id($_GET['id']);
    C::t('#tom_tongcheng#tom_tongcheng_photo')->delete_by_tongcheng_id($_GET['id']);
    C::t('#tom_tongcheng#tom_tongcheng_tag')->delete_by_tongcheng_id($_GET['id']);
    C::t('#tom_tongcheng#tom_tongcheng_content')->delete_by_tongcheng_id($_GET['id']);
    C::t('#tom_tongcheng#tom_tongcheng_sfc_cache')->delete_by_tongcheng_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else{
    
    set_list_url("tom_tongcheng_admin_index_list");
    
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
.span0 {color: #35a6ee !important;border: 1px solid #35a6ee}
.span1 {color: #f0962a !important;border: 1px solid #f0962a}
.span2 {color: #1fbf8c !important;border: 1px solid #1fbf8c}
.span3 {color: #B992F6 !important;border: 1px solid #B992F6}
.span4 {color: #2B8DAD !important;border: 1px solid #2B8DAD}
.span5 {color: #35a6ee !important;border: 1px solid #35a6ee}
.span6 {color: #f0962a !important;border: 1px solid #f0962a}
.span7 {color: #1fbf8c !important;border: 1px solid #1fbf8c}
.span8 {color: #B992F6 !important;border: 1px solid #B992F6}
.span9 {color: #2B8DAD !important;border: 1px solid #2B8DAD}
.span0,.span1,.span2,.span3,.span4,.span5,.span6,.span7,.span8,.span9,.span-cat {font-size: 13px}
</style>
EOF;
    echo $csstr;
    
    showtableheader();
    $Lang['tc_help_1']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['tc_help_1']);
    $Lang['tc_help_1']  = tom_link_replace($Lang['tc_help_1']);
    $Lang['tc_help_3']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['tc_help_3']);
    $Lang['tc_help_5']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['tc_help_5']);
    $Lang['tc_help_6']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['tc_help_6']);
    echo '<tr><th colspan="15" class="partition">' . $Lang['tc_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['tc_help_1'] . '</li>';
    echo '<li><font color="#FF0000">' . $Lang['tc_help_4'] . '</font></li>';
    echo '<li>' . $Lang['tc_help_2_a'] . '<a target="_blank" href="http://www.tomwx.cn/index.php?m=help&t=plugin&pluginid=tom_tongcheng"><font color="#FF0000">' . $Lang['tc_help_2_b'] . '</font></a></li>';
    echo '<li>' . $Lang['tc_help_3'] . '</li>';
    if($tongchengConfig['open_yun'] == 2){
        echo '<li>' . $Lang['tc_help_5'] . '</li>';
    }
    if($tongchengConfig['open_yun'] == 3){
        echo '<li>' . $Lang['tc_help_6'] . '</li>';
    }
    echo '</ul></td></tr>';
    showtablefooter();
    
    $site_id      = isset($_GET['site_id'])? intval($_GET['site_id']):0;
    $tongcheng_id = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    $model_id = intval($_GET['model_id'])>0? intval($_GET['model_id']):0;
    $type_id  = intval($_GET['type_id'])>0? intval($_GET['type_id']):0;
    $cate_id  = intval($_GET['cate_id'])>0? intval($_GET['cate_id']):0;
    $user_id  = intval($_GET['user_id'])>0? intval($_GET['user_id']):0;
    $keyword  = isset($_GET['keyword'])? addslashes($_GET['keyword']):'';
    $page  = intval($_GET['page'])>0? intval($_GET['page']):1;

    $whereStr = '';
    if(!empty($model_id)){
        $whereStr.= " AND model_id={$model_id} ";
    }
    if(!empty($type_id)){
        $whereStr.= " AND type_id={$type_id} ";
    }
    if(!empty($cate_id)){
        $whereStr.= " AND cate_id={$cate_id} ";
    }
    if(!empty($user_id)){
        $whereStr.= " AND user_id={$user_id} ";
    }
    if(!empty($tongcheng_id)){
        $whereStr.= " AND id={$tongcheng_id} ";
    }
    if(!empty($site_id)){
        $whereStr.= " AND site_id={$site_id} ";
    }
    
    $pagesize       = 8;
    if(!empty($keyword)){
        $pagesize       = 50;
    }
    $start          = ($page - 1)*$pagesize;
    $count          = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count($whereStr,$keyword);
    $tongchengList  = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list($whereStr," ORDER BY refresh_time DESC,id DESC ",$start,$pagesize,$keyword);
    
    $modBasePageUrl = $modBaseUrl."&model_id={$model_id}&type_id={$type_id}&cate_id={$cate_id}&user_id={$user_id}&site_id={$site_id}";
    
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
    
    // nav
    tomshownavheader();
    tomshownavli($Lang['help_nav_title'],$adminBaseUrl.'&tmod=help',false);
    tomshownavli($Lang['popup_nav_title'],$adminBaseUrl.'&tmod=popup',false);
    tomshownavli($Lang['nav_nav_title'],$adminBaseUrl.'&tmod=nav',false);
    tomshownavli($Lang['topnews_nav_title'],$adminBaseUrl.'&tmod=topnews',false);
    tomshownavli($Lang['focuspic_nav_title'],$adminBaseUrl.'&tmod=focuspic',false);
    tomshownavli($Lang['tousu_nav_title'],$adminBaseUrl.'&tmod=tousu',false);
    tomshownavli($Lang['xieyi_title'],$adminBaseUrl.'&tmod=common',false);
    //tomshownavli($Lang['help_title'],$adminBaseUrl.'&tmod=common',false);
    tomshownavli($Lang['forbid_word_title'],$adminBaseUrl.'&tmod=common',false);
    tomshownavli($Lang['sfc_title'],$adminBaseUrl.'&tmod=common',false);
    tomshownavli($Lang['doDao_title_1'],$adminBaseUrl.'&tmod=doDao',false);
    tomshownavli($Lang['pinglun_list'],$adminBaseUrl.'&tmod=pinglun',false);
    tomshownavli($Lang['pinglun_reply_list'],$adminBaseUrl.'&tmod=pinglunReplay',false);
    tomshownavli($Lang['pm_message_list'],$adminBaseUrl.'&tmod=pmMessage',false);
    tomshownavfooter();
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['index_list_title'] . '</th></tr>';
    echo '<tr class="header" style="background-color: #e6f2fb;">';
    echo '<th>' . $Lang['index_user'] . '</th>';
    echo '<th>' . $Lang['index_type'] . '</th>';
    echo '<th>' . $Lang['index_content'] . '</th>';
    echo '<th>' . $Lang['index_status'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($tongchengList as $key => $value) {
        
        $userInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']); 
        
        $modelInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_by_id($value['model_id']);
        $typeInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($value['type_id']);
        $cateInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_by_id($value['cate_id']);
        $siteInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($value['site_id']);
        $areaInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($value['area_id']);
        $streetInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($value['street_id']);
        
        $tongchengAttrListTmp = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY id DESC ",0,50);
        $tongchengTagListTmp = C::t('#tom_tongcheng#tom_tongcheng_tag')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY id DESC ",0,50);
        $tongchengPhotoListTmpTmp = C::t('#tom_tongcheng#tom_tongcheng_photo')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY id ASC ",0,50);
        
        $tongchengPhotoListTmp = array();
        if(is_array($tongchengPhotoListTmpTmp) && !empty($tongchengPhotoListTmpTmp)){
            foreach ($tongchengPhotoListTmpTmp as $kk => $vv){
                if(!preg_match('/^http/', $vv['picurl']) ){
                    if(strpos($vv['picurl'], 'source/plugin/tom_tongcheng/data/') === false){    
                        $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$vv['picurl'];
                    }else{
                        $picurl = $vv['picurl'];
                    }
                }else{
                    $picurl = $vv['picurl'];
                }
                $tongchengPhotoListTmp[$kk] = $picurl;
            }
        }
        
        echo '<tr style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #70b4e6;">';
        echo '<td><div class="tc_header_box"><ul>';
        echo '<li><img src="'.$userInfoTmp['picurl'].'" width="40" /></li>';
        echo '<li>'.$userInfoTmp['nickname'].'(UID:'.$userInfoTmp['id'].')</li>';
        if($value['site_id'] > 1){
            echo '<li><font color="#0894fb"><b>'.$siteInfoTmp['name'].'</b></font></li>';
        }else{
            echo '<li><font color="#0894fb"><b>'.$Lang['sites_one'].'</b></font></li>';
        }
        echo '<li><font color="#238206">'.$areaInfoTmp['name'].' '.$streetInfoTmp['name'].'</font></li>';
        echo '</ul></div></td>';
        echo '<td>';
        if($modelInfoTmp){
            echo '<span class="span_model">' . $modelInfoTmp['name'] . '</span>';
        }
        if($typeInfoTmp){
            echo '<span class="span_type">' . $typeInfoTmp['name'] . '</span>';
        }
        if($cateInfoTmp){
            echo '<span class="span_cate">' . $cateInfoTmp['name'] . '</span>';
        }
        echo '</td>';
        echo '<td><div class="tc_content_box">';
        if(is_array($tongchengTagListTmp) && !empty($tongchengTagListTmp)){
            echo '<div class="tc_content_box_tag"><ul>';
            foreach ($tongchengTagListTmp as $k1 => $v1){
                echo '<li class="span'.$k1.'">'.$v1['tag_name'].'</li>';
            }
            echo '</ul></div>';
        }
        echo '<div class="tc_content_box_clear"></div>';
        echo '<div class="tc_content_box_attr"><ul>';
        echo '<li><b>'.$Lang['index_title'].'&nbsp;:&nbsp;</b>'.$value[$tongchengConfig['admin_tongcheng_index_title']].'</li>';
        echo '<li><b>'.$Lang['index_xm'].'&nbsp;:&nbsp;</b>'.$value['xm'].'</li>';
        echo '<li><b>'.$Lang['index_tel'].'&nbsp;:&nbsp;</b>'.$value['tel'].'</li>';
        if(is_array($tongchengAttrListTmp) && !empty($tongchengAttrListTmp)){
            foreach ($tongchengAttrListTmp as $k2 => $v2){
                echo '<li><b>'.$v2['attr_name'].'&nbsp;:&nbsp;</b>'.$v2['value'];
                if($v2['unit']){
                    echo '/'.$v2['unit'];
                }
                echo '</li>';
            }
        }
        echo '</ul></div>';
        echo '<div class="tc_content_box_clear"></div>';
        $value['content'] = contentFormat($value['content']);
        echo '<div class="tc_content_box_content">'.$value['content'].'</div>';
        echo '<div class="tc_content_box_clear"></div>';
        if(is_array($tongchengPhotoListTmp) && !empty($tongchengPhotoListTmp)){
            echo '<div class="tc_content_box_photo"><ul>';
            foreach ($tongchengPhotoListTmp as $k3 => $v3){
                echo '<li><a href="'.$v3.'" target="_blank"><img src="'.$v3.'" width="40" height="40" /></a></li>';
            }
            echo '</ul></div>';
        }
        echo '</div></td>';
        echo '<td><div class="tc_content_box_status"><ul>';
        if($value['pay_status'] == 1){
            echo '<li><b>'.$Lang['index_pay_status'].'&nbsp;:&nbsp;</b><font color="#f00">' . $Lang['index_pay_status_1'] . '</font></li>';
        }else if($value['pay_status'] == 2){
            echo '<li><b>'.$Lang['index_pay_status'].'&nbsp;:&nbsp;</b><font color="#0a9409">' . $Lang['index_pay_status_2'] . '</font></li>';
        }
        if($value['topstatus'] == 1 ){
            echo '<li><b>'.$Lang['index_topstatus'].'&nbsp;:&nbsp;</b><font color="#0a9409">' . $Lang['index_topstatus_1'] . '</font>&nbsp;&nbsp;<font color="#f00">(' . dgmdate($value['toptime'],"Y-m-d",$tomSysOffset) . '&nbsp;' . $Lang['index_toptime'] . ')</font></li>';
        }
        $sheheBtnStr = '&nbsp;(&nbsp;<a href="'.$modBaseUrl.'&act=shenhe&id='.$value['id'].'&shenhe_status=1&formhash='.FORMHASH.'">' . $Lang['index_shenhe_btn_1']. '</a>&nbsp;|&nbsp;<a href="'.$modBaseUrl.'&act=shenhe&id='.$value['id'].'&shenhe_status=3&formhash='.FORMHASH.'">' . $Lang['index_shenhe_btn_3']. '</a>)';
        if($value['shenhe_status'] == 1 ){
            echo '<li><b>'.$Lang['index_shenhe_status'].'&nbsp;:&nbsp;</b><font color="#0a9409">' . $Lang['index_shenhe_status_1'] . '</font>'.$sheheBtnStr.'</li>';
        }else if($value['shenhe_status'] == 2){
            echo '<li><b>'.$Lang['index_shenhe_status'].'&nbsp;:&nbsp;</b><font color="#f70404">' . $Lang['index_shenhe_status_2'] . '</font>'.$sheheBtnStr.'</li>';
        }else if($value['shenhe_status'] == 3){
            echo '<li><b>'.$Lang['index_shenhe_status'].'&nbsp;:&nbsp;</b><font color="#f70404">' . $Lang['index_shenhe_status_3'] . '</font>'.$sheheBtnStr.'</li>';
        }
        if($value['status'] == 1 ){
            echo '<li><b>'.$Lang['index_status'].'&nbsp;:&nbsp;</b><font color="#0a9409">' . $Lang['index_status_1'] . '</font></li>';
        }else{
            echo '<li><b>'.$Lang['index_status'].'&nbsp;:&nbsp;</b><font color="#f70404">' . $Lang['index_status_2'] . '</font></li>';
        }
        if($value['finish'] == 1 ){
            echo '<li><b>'.$Lang['index_finish'].'&nbsp;:&nbsp;</b><font color="#0a9409">' . $Lang['index_finish_1'] . '</font></li>';
        }else{
            echo '<li><b>'.$Lang['index_finish'].'&nbsp;:&nbsp;</b><font color="#928c8c">' . $Lang['index_finish_0'] . '</font></li>';
        }
        echo '<li><b>'.$Lang['index_refresh_time'].'&nbsp;:&nbsp;</b>' . dgmdate($value['refresh_time'],"Y-m-d H:i",$tomSysOffset) . '</li>';
        echo '<li><b>'.$Lang['index_add_time'].'&nbsp;:&nbsp;</b>' . dgmdate($value['add_time'],"Y-m-d H:i",$tomSysOffset) . '</li>';
        echo '</ul></div></td>';
        echo '<td><div class="tc_content_box_handle"><ul>';
        echo '<li><a href="'.$modBaseUrl.'&act=refresh&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['edit_refresh']. '</a></li>';
        echo '<li><a href="'.$modBaseUrl.'&act=editcate&id='.$value['id'].'&model_id='.$value['model_id'].'&type_id='.$value['type_id'].'&cate_id='.$value['cate_id'].'&formhash='.FORMHASH.'">' . $Lang['edit_cate_title']. '</a></li>';
        echo '<li><a href="'.$modBaseUrl.'&act=edittop&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['edit_top_title']. '</a></li>';
        echo '<li><a href="'.$adminBaseUrl.'&tmod=content&tongcheng_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['content_title']. '</a></li>';
        if($value['status'] == 1 ){
            echo '<li><a href="'.$modBaseUrl.'&act=hide&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['index_status_2']. '</a></li>';
        }else{
            echo '<li><a href="'.$modBaseUrl.'&act=show&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['index_status_1']. '</a></li>';
        }
        if($value['finish'] == 1 ){
            echo '<li><a href="'.$modBaseUrl.'&act=nofinish&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['index_finish_0']. '</a></li>';
        }else{
            echo '<li><a href="'.$modBaseUrl.'&act=finish&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['index_finish_1']. '</a></li>';
        }
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
</script>
EOF;
    echo $jsstr;
    
}


