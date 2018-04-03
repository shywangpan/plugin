<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=lbs';
$modListUrl = $adminListUrl.'&tmod=lbs';
$modFromUrl = $adminFromUrl.'&tmod=lbs';

include DISCUZ_ROOT.'./source/plugin/tom_tcshop/class/function.lbs.php';

$tcshopConfig['baidu_ak'] = trim($tcshopConfig['baidu_ak']);

if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'create_geotable'){
    
    $createData = array('name' => 'tcshop','ak'=> $tcshopConfig['baidu_ak']);
    
    $data = lbsBaiduCreateGeotable($createData);
    if($data['status'] != 0){
        cpmsg($data['message'], $modListUrl, 'error');exit;
    }
    $geotable_id = $data['id'];
    
    $columnData1 = array(
        'name'                  => $Lang['lbs_tcshop_id_title'],
        'key'                   => 'tcshop_id',
        'is_sortfilter_field'   => 1,
        'is_index_field'        => 1,
        'is_unique_field'       => 1,
        'geotable_id'           => $geotable_id,
        'ak'                    => $tcshopConfig['baidu_ak']
        );

    $data = lbsBaiduCreateColumn($columnData1);
    if($data['status'] != 0){
        cpmsg("createGeotable:".$data['message'], $modListUrl, 'error');exit;
    }
    
    $columnData2 = array(
        'name'                  => $Lang['lbs_cate_id_title'],
        'key'                   => 'cate_id',
        'is_sortfilter_field'   => 1,
        'is_index_field'        => 1,
        'geotable_id'           => $geotable_id,
        'ak'                    => $tcshopConfig['baidu_ak']
        );

    $data = lbsBaiduCreateColumn($columnData2);
    if($data['status'] != 0){
        cpmsg("cate_id:".$data['message'], $modListUrl, 'error');exit;
    }
    
    $columnData3 = array(
        'name'                  => $Lang['lbs_cate_child_id_title'],
        'key'                   => 'cate_child_id',
        'is_sortfilter_field'   => 1,
        'is_index_field'        => 1,
        'geotable_id'           => $geotable_id,
        'ak'                    => $tcshopConfig['baidu_ak']
        );

    $data = lbsBaiduCreateColumn($columnData3); 
    if($data['status'] != 0){
        cpmsg("cate_child_id:".$data['message'], $modListUrl, 'error');exit;
    }
    
    
    $cacheData = array();
    $cacheData['geotable_id'] = $geotable_id;
    $dataDir = DISCUZ_ROOT.'.'."/source/plugin/tom_tcshop/config/";
    chmod($dataDir, 0777); 
    file_put_contents($dataDir.'baidulbs.php', "<?php\nreturn " . var_export($cacheData, true) . ";\n?>");
    
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['lbs_help_title'] . '</th></tr>';
    echo '<tr><td class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['lbs_help_1'] . '</li>';
    echo '</ul></td></tr>';
    showtablefooter();
    
    if(empty($tcshopConfig['baidu_ak'])){
        echo '<div class="lbs_error_msg">';
        echo $Lang['lbs_baidu_ak_error'];
        echo '</div>';
        exit;
    }
    
    $listGeotableUrl = $listGeotableApi."?name=tcshop&ak=".$tcshopConfig['baidu_ak'];
    
    $listGeotableData = lbsBaiduListGeotable(array('name'=>'tcshop','ak'=>$tcshopConfig['baidu_ak']));
    
    $isHaveGeotableFlag = 0;
    if($listGeotableData['status'] == 0 && is_array($listGeotableData['geotables']) && $listGeotableData['geotables'][0]['name'] == 'tcshop' ){
        $isHaveGeotableFlag = 1;
        $geotable_id = $listGeotableData['geotables'][0]['id'];
    }else if($listGeotableData['status'] == 0 && empty($listGeotableData['geotables'])){
        $isHaveGeotableFlag = 2;
    }else{
        $isHaveGeotableFlag = 3;
    }
    
    if($isHaveGeotableFlag == 1){
        echo '<div class="lbs_ok_msg">';
        echo $Lang['lbs_is_have_geotable_flag1_msg']."tcshop";
        echo '</div>';
    }
    
    if($isHaveGeotableFlag == 2){
        echo '<div class="lbs_error_msg">';
        echo $Lang['lbs_is_have_geotable_flag2_msg']."tcshop";
        echo '</div>';
        echo '<div class="lbs_btn">';
        echo '<a style="border-color: #34a200;color: #34a200;" href="'.$modBaseUrl.'&act=create_geotable&formhash='.FORMHASH.'">'.$Lang['lbs_create_geotable_btn'].'</a>';
        echo '</div>';
        exit;
    }
    
    if($isHaveGeotableFlag == 3){
        echo '<div class="lbs_error_msg">';
        echo $Lang['lbs_is_have_geotable_flag3_msg'].$listGeotableData['message'];
        echo '</div>';
        exit;
    }
    
    
    $listColumnData = lbsBaiduListColumn(array('geotable_id'=>$geotable_id,'ak'=>$tcshopConfig['baidu_ak']));
    
    if($listColumnData['status'] == 0 && is_array($listColumnData['columns']) && !empty($listColumnData['columns'])){
        foreach ($listColumnData['columns'] as $key => $value){
            echo '<div class="lbs_ok_msg">';
            echo $Lang['lbs_columns_ok_msg'].$value['key'];
            echo '</div>';
        }
    }else{
        echo '<div class="lbs_error_msg">';
        echo $Lang['lbs_columns_error_msg'].$listColumnData['message'];
        echo '</div>';
    }
    
    exit;
}


