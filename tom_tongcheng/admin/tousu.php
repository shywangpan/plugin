<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=tousu'; 
$modListUrl = $adminListUrl.'&tmod=tousu';
$modFromUrl = $adminFromUrl.'&tmod=tousu';

$act = $_GET['act'];
$formhash =  $_GET['formhash']? $_GET['formhash']:'';

if($formhash == FORMHASH && $act == 'info'){
    
}else{
    
    $where = "";
    
    $pagesize = 10;
    if(!empty($nickname)){
		$pagesize = 100;
	}
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_tongcheng#tom_tongcheng_tousu')->fetch_all_count($where);
    $tousuList = C::t('#tom_tongcheng#tom_tongcheng_tousu')->fetch_all_list($where,"ORDER BY add_time DESC",$start,$pagesize);
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['tousu_list_title'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $Lang['tousu_tongcheng'] . '</th>';
    echo '<th>' . $Lang['tousu_xm'] . '</th>';
    echo '<th>' . $Lang['tousu_tel'] . '</th>';
    echo '<th>' . $Lang['tousu_reason'] . '</th>';
    echo '<th>' . $Lang['tousu_content'] . '</th>';
    echo '<th>' . $Lang['tousu_add_time'] . '</th>';
    echo '</tr>';
    foreach ($tousuList as $key => $value){
        $modelUrl = $adminBaseUrl.'&tmod=index';
        $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($value['tongcheng_id']);
        echo '<tr>';
        echo '<td><a target="_blank" href="'.$modelUrl.'&tongcheng_id='.$value['tongcheng_id'].'">'.cutstr(contentFormat($tongchengInfo['content']),20,"...").'</a></td>';
        echo '<td>'.$value['xm'].'</td>';
        echo '<td>'.$value['tel'].'</td>';
        echo '<td>'.$value['reason'].'</td>';
        echo '<td>'.$value['content'].'</td>';
        echo '<td>'.dgmdate($value['add_time'],"Y-m-d H:i",$tomSysOffset).'</td>';
        echo '</tr>';
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl);	
    showsubmit('', '', '', '', $multi, false);
}

