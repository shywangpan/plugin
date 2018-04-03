<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=doDao'; 
$modListUrl = $adminListUrl.'&tmod=doDao';
$modFromUrl = $adminFromUrl.'&tmod=doDao';

$site_id     = isset($_GET['site_id'])? intval($_GET['site_id']):0;
$model_id    = isset($_GET['model_id'])? intval($_GET['model_id']):0;
$type_id     = isset($_GET['type_id'])? intval($_GET['type_id']):0;
$cate_id     = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
$refresh_rand     = isset($_GET['refresh_rand'])? intval($_GET['refresh_rand']):0;
$topstatus   = isset($_GET['topstatus'])? intval($_GET['topstatus']):0;

if(submitcheck('submit')){
    
}else{
    tomloadcalendarjs();
    showformheader($modFromUrl.'&formhash='.FORMHASH);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['doDao_site_title'] .'</th></tr>';
    
    $siteList = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(" "," ORDER BY add_time ASC,id DESC ",0,1000);
    $siteStr = '<tr class="header"><th>'.$Lang['doDao_site'].'</th><th></th></tr>';
    $siteStr.= '<tr><td width="300"><select style="width: 260px;" name="site_id" id="site_id" onchange="refreshsite();">';
    $siteStr.=  '<option value="0">'.$Lang['doDao_site'].'</option>';
    if($value['id'] == 1){
        $siteStr.=  '<option value="1" selected>'.$tongchengConfig['plugin_name'].'</option>';
    }else{
        $siteStr.=  '<option value="1">'.$tongchengConfig['plugin_name'].'</option>';
    }
    foreach ($siteList as $key => $value){
        if($value['id'] == $site_id){
            $siteStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
        }else{
            $siteStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
    }
    $siteStr.= '</select></td><td></td></tr>';
    echo $siteStr;
    
    $modelList = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_list("    "," ORDER BY paixu ASC,id DESC ",0,100);
    $modelStr = '<tr class="header"><th>'.$Lang['doDao_model'].'</th><th></th></tr>';
    $modelStr.= '<tr><td width="300"><select style="width: 260px;" name="model_id" id="model_id" onchange="refreshmodel();">';
    $modelStr.=  '<option value="0">'.$Lang['doDao_model'].'</option>';
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
    $typeStr = '<tr class="header"><th>'.$Lang['doDao_type'].'</th><th></th></tr>';
    $typeStr.= '<tr><td width="300"><select style="width: 260px;" name="type_id" id="type_id" onchange="refreshtype();">';
    $typeStr.=  '<option value="0">'.$Lang['doDao_type'].'</option>';
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
    $cateStr = '<tr class="header"><th>'.$Lang['doDao_cate'].'</th><th></th></tr>';
    $cateStr.= '<tr><td width="300"><select style="width: 260px;" name="cate_id" id="cate_id" onchange="refreshcate();">';
    $cateStr.=  '<option value="0">'.$Lang['doDao_cate'].'</option>';
    foreach ($cateList as $key => $value){
        if($value['id'] == $cate_id){
            $cateStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
        }else{
            $cateStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
    }
    $cateStr.= '</select></td><td></td></tr>';
    echo $cateStr;
    
    $refreshRandStr = '<tr class="header"><th>'.$Lang['doDao_rand_date'].'</th><th></th></tr>';
    $refreshRandStr.= '<tr><td width="300"><select style="width: 260px;" name="refresh_rand" id="refresh_rand" onchange="refreshrand();">';
    $refreshRandStr.=  '<option value="0">'.$Lang['doDao_rand_date'].'</option>';
    for($i = 1; $i<= 7 ;$i++){
        if($i == $refresh_rand){
            $refreshRandStr.=  '<option value="'.$i.'" selected>'.$Lang['doDao_refresh_'.$i.''].'</option>';
        }else{
            $refreshRandStr.=  '<option value="'.$i.'">'.$Lang['doDao_refresh_'.$i.''].'</option>';
        }
    }
    $refreshRandStr.= '</select></td><td></td></tr>';
    echo $refreshRandStr;
    
    $top_1 = $top_2 = '';
    if($topstatus == 1){ $top_1 = 'checked';}
    if($topstatus == 0){ $top_2 = 'checked';}
    $topStr = '<tr class="header"><th>'.$Lang['doDao_top'].'</th><th></th></tr>';
    $topStr.= '<tr><td width="300"><label><input type="radio" name="topstatus" value="1" '.$top_1.' onchange="refreshtop();">'.$Lang['doDao_top_yes'].'<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    $topStr.=  '<label><input type="radio" name="topstatus" value="0" '.$top_2.' onchange="refreshtop();">'.$Lang['doDao_top_no'].'<label>';
    
    $topStr.= '</td><td></td></tr>';
    echo $topStr;
    
    $where = " AND {$tongchengConfig['admin_tongcheng_doDao_status']} = 1 AND status = 1  ";
    if($site_id){
        $where.= " AND site_id = {$site_id} ";
    }
    if($model_id){
        $where.= " AND model_id = {$model_id} ";
    }
    if($refresh_rand > 0){
        $minTime = TIMESTAMP - $refresh_rand * 86400;
        $where.= " AND refresh_time > {$minTime} ";
    }
    if($type_id){
        $where.= " AND type_id = {$type_id} ";
    }
    if($cate_id){
        $where.= " AND cate_id = {$cate_id} ";
    }
    if($topstatus == 1){
        $where.= " AND {$tongchengConfig['admin_tongcheng_doDao_top']} = 1 AND toptime > ".TIMESTAMP;
    }
    showtablefooter();
    showformfooter();
    
    showtableheader();
    $count = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count($where);
    $num = ceil($count/100);
    $doDaoUrl = $_G['siteurl']."plugin.php?id=tom_tongcheng:doDao&shenhe_status=1&status=1&site_id={$site_id}&model_id={$model_id}&refresh_rand={$refresh_rand}&type_id={$type_id}&cate_id={$cate_id}&topstatus={$topstatus}";
    echo '<tr>';
    for($i = 1; $i <= $num; $i++){
        $max_number = $i * 100;
        $min_number = $max_number - 100;
        if($i%6 == 0){
            echo '<td><a href="'.$doDaoUrl.'&page='.$i.'" target="_blank" style="color: #FA6A03; padding:2px 7px; font-weight:600; margin-left: 10px; border-radius: 5px; border: 1px solid #FA6A03;">'.$Lang['daDao_chu'].$min_number.'-'.$max_number.'</a></td></tr><tr>';
        }else{
            echo '<td><a href="'.$doDaoUrl.'&page='.$i.'" target="_blank" style="color: #FA6A03; padding:2px 7px; font-weight:600; margin-left: 10px; border-radius: 5px; border: 1px solid #FA6A03;">'.$Lang['daDao_chu'].$min_number.'-'.$max_number.'</a></td>';
        }
    }
    echo '</tr>';
    showtablefooter();

    $adminurl = $modBaseUrl.'&formhash='.FORMHASH;
    echo <<<SCRIPT
<script type="text/javascript">

function refreshsite() {
	location.href = "$adminurl"+"&site_id="+jq('#site_id').val() +"&model_id="+jq('#model_id').val() + "&type_id="+jq('#type_id').val() + "&cate_id="+jq('#cate_id').val() + "&refresh_rand="+jq('#refresh_rand').val() + "&topstatus="+jq("input[type='radio']:checked").val();
}          
function refreshmodel() {
	location.href = "$adminurl"+"&site_id="+jq('#site_id').val() +"&model_id="+jq('#model_id').val() + "&type_id="+jq('#type_id').val() + "&cate_id="+jq('#cate_id').val() + "&refresh_rand="+jq('#refresh_rand').val() + "&topstatus="+jq("input[type='radio']:checked").val();
}
function refreshtype() {
	location.href = "$adminurl"+"&site_id="+jq('#site_id').val() +"&model_id="+jq('#model_id').val() + "&type_id="+jq('#type_id').val() + "&cate_id="+jq('#cate_id').val() + "&refresh_rand="+jq('#refresh_rand').val() + "&topstatus="+jq("input[type='radio']:checked").val();
}
function refreshcate() {
	location.href = "$adminurl"+"&site_id="+jq('#site_id').val() +"&model_id="+jq('#model_id').val() + "&type_id="+jq('#type_id').val() + "&cate_id="+jq('#cate_id').val() + "&refresh_rand="+jq('#refresh_rand').val() + "&topstatus="+jq("input[type='radio']:checked").val();
}
function refreshrand() {
	location.href = "$adminurl"+"&site_id="+jq('#site_id').val() +"&model_id="+jq('#model_id').val() + "&type_id="+jq('#type_id').val() + "&cate_id="+jq('#cate_id').val() + "&refresh_rand="+jq('#refresh_rand').val() + "&topstatus="+jq("input[type='radio']:checked").val();
}
function refreshtop() {
	location.href = "$adminurl"+"&site_id="+jq('#site_id').val() +"&model_id="+jq('#model_id').val() + "&type_id="+jq('#type_id').val() + "&cate_id="+jq('#cate_id').val() + "&refresh_rand="+jq('#refresh_rand').val() + "&topstatus="+jq("input[type='radio']:checked").val();
}
   
</script>
SCRIPT;
}

