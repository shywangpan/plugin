<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=pmMessage'; 
$modListUrl = $adminListUrl.'&tmod=pmMessage';
$modFromUrl = $adminFromUrl.'&tmod=pmMessage';

$get_list_url_value = get_list_url("tom_tongcheng_admin_pm_message_list");
if($get_list_url_value){
    $modListUrl = $get_list_url_value;
}

if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tongcheng#tom_tongcheng_pm_message')->delete($_GET['id']);
    
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    set_list_url("tom_tongcheng_admin_pm_message_list");
    
    $pagesize = 100;
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_tongcheng#tom_tongcheng_pm_message')->fetch_all_count("");
    $messageList = C::t('#tom_tongcheng#tom_tongcheng_pm_message')->fetch_all_list("","ORDER BY add_time DESC",$start,$pagesize);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['pm_message_list'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $Lang['user_id'] . '</th>';
    echo '<th>' . $Lang['user_picurl'] . '</th>';
    echo '<th>' . $Lang['user_nickname'] . '</th>';
    echo '<th>' . $Lang['pm_message_content'] . '</th>';
    echo '<th>' . $Lang['pm_message_time'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    foreach ($messageList as $key => $value){
        
        $modelUrl = $adminBaseUrl.'&tmod=index';
        $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']);

        echo '<tr style="background-color: #FCFAFA;">';
        echo '<td>'.$userInfo['id'].'</td>';
        echo '<td><img src="'.$userInfo['picurl'].'" width="40" /></td>';
        echo '<td>'.$userInfo['nickname'].'</td>';
        echo '<td>' . $value['content'] . '</td>';
        echo '<td>' . dgmdate($value['add_time'], 'Y-m-d H:i:s',$tomSysOffset) . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['del'] . '</a>';
        echo '</td>';
        echo '</tr>';
        
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl);
    showsubmit('', '', '', '', $multi, false);
}
