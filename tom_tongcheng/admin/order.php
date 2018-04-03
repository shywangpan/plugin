<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=order'; 
$modListUrl = $adminListUrl.'&tmod=order';
$modFromUrl = $adminFromUrl.'&tmod=order';

$act = $_GET['act'];
$formhash =  $_GET['formhash']? $_GET['formhash']:'';

if($formhash == FORMHASH && $act == 'info'){
    
}else{
    
    $site_id      = isset($_GET['site_id'])? intval($_GET['site_id']):0;
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    
    $where = " AND order_status != 3 ";
    if(!empty($site_id)){
        $where.= " AND site_id={$site_id} ";
    }
    $pagesize = $tongchengConfig['admin_tongcheng_order_pagesize'];
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_count($where);
    $orderList = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_list($where,"ORDER BY id DESC",$start,$pagesize);
    
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
    
    $todayPayPrice = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_sun_pay_price(" AND pay_time > $nowDayTime AND order_status=2 ");
    $monthPayPrice = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_sun_pay_price(" AND pay_time > $nowMonthTime AND order_status=2 ");
    $allPayPrice = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_sun_pay_price(" AND order_status=2 ");
    echo '<div style="background-color: #f1f1f1;line-height: 30px;height: 30px;" >&nbsp;&nbsp;';
    echo $Lang['today_pay_price_title'].'<font color="#fd0d0d">('.$todayPayPrice.')</font>&nbsp;&nbsp;';
    echo $Lang['month_pay_price_title'].'<font color="#fd0d0d">('.$monthPayPrice.')</font>&nbsp;&nbsp;';
    echo $Lang['all_pay_price_title'].'<font color="#fd0d0d">('.$allPayPrice.')</font>&nbsp;&nbsp;';
    echo '</div>';
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['order_list_title'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $Lang['order_tongcheng'] . '</th>';
    echo '<th>' . $Lang['sites_title'] . '</th>';
    echo '<th>' . $Lang['user_picurl'] . '</th>';
    echo '<th>' . $Lang['user_nickname'] . '</th>';
    echo '<th>' . $Lang['order_no'] . '</th>';
    echo '<th>' . $Lang['order_type'] . '</th>';
    echo '<th>' . $Lang['order_pay_price'] . '</th>';
    echo '<th>' . $Lang['order_status'] . '</th>';
    echo '<th>' . $Lang['order_time'] . '</th>';
    echo '<th>' . $Lang['order_pay_time'] . '</th>';
    echo '</tr>';
    foreach ($orderList as $key => $value){
        $modelUrl = $adminBaseUrl.'&tmod=index';
        $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($value['tongcheng_id']);
        $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']);
        $siteInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($value['site_id']);
        if($value['tcshop_id'] > 0){
            $tcshopInfoTmp = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($value['tcshop_id']);
        }
        echo '<tr>';
        if($value['tcshop_id'] > 0){
            echo '<td><font color="#0894fb"><b>'. $Lang['order_list_shop'] .'</b></font>'.$tcshopInfoTmp['name'].'</td>';
        }else{
            echo '<td><a target="_blank" href="'.$modelUrl.'&tongcheng_id='.$value['tongcheng_id'].'">'.cutstr(contentFormat($tongchengInfo['content']),20,"...").'</a></td>';
        }
         if($value['site_id'] > 1){
            echo '<td><font color="#0894fb"><b>' . $siteInfoTmp['name'] . '</b></font></td>';
        }else{
            echo '<td><font color="#0894fb"><b>' . $Lang['sites_one'] . '</b></font></td>';
        }
        echo '<td><img src="'.$userInfo['picurl'].'" width="40" /></td>';
        echo '<td>'.$userInfo['nickname'].'</td>';
        echo '<td>'.$value['order_no'].'</td>';
        if($value['order_type'] == 1){
            echo '<td>'.$Lang['order_type_1'].'</td>';
        }else if($value['order_type'] == 2){
            echo '<td>'.$Lang['order_type_2'].'</td>';
        }else if($value['order_type'] == 3){
            $order_type_3_msg = str_replace("{DAYS}", $value['time_value'], $Lang['order_type_3_msg']);
            echo '<td>'.$Lang['order_type_3'].$order_type_3_msg.'</td>';
        }else{
            echo '<td>-</td>';
        }
        echo '<td><font color="#0a9409">'.$value['pay_price'].'</font></td>';
        if($value['order_status'] == 1){
            echo '<td><font color="#f00">'.$Lang['order_status_1'].'</font></td>';
        }else if($value['order_status'] == 2){
            echo '<td><font color="#0a9409">'.$Lang['order_status_2'].'</font></td>';
        }else if($value['order_status'] == 3){
            echo '<td><font color="#c3c1c1">'.$Lang['order_status_3'].'</font></td>';
        }else{
            echo '<td>-</td>';
        }
        echo '<td>'.dgmdate($value['order_time'],"Y-m-d H:i",$tomSysOffset).'</td>';
        if($value['pay_time'] > 0){
            echo '<td>'.dgmdate($value['pay_time'],"Y-m-d H:i",$tomSysOffset).'</td>';
        }else{
            echo '<td>-</td>';
        }
        
        echo '</tr>';
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBasePageUrl);	
    showsubmit('', '', '', '', $multi, false);
}

