<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=pinglunReplay'; 
$modListUrl = $adminListUrl.'&tmod=pinglunReplay';
$modFromUrl = $adminFromUrl.'&tmod=pinglunReplay';

$get_list_url_value = get_list_url("tom_tcshop_admin_pinglun_reply_list");
if($get_list_url_value){
    $modListUrl = $get_list_url_value;
}

if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_tcshop#tom_tcshop_pinglun_reply')->delete($_GET['id']);
    
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    set_list_url("tom_tcshop_admin_pinglun_reply_list");
    
    $pagesize = 100;
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_tcshop#tom_tcshop_pinglun_reply')->fetch_all_count("");
    $replyList = C::t('#tom_tcshop#tom_tcshop_pinglun_reply')->fetch_all_list("","ORDER BY reply_time DESC",$start,$pagesize);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['pinglun_reply_list'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $Lang['tcshop_name'] . '</th>';
    echo '<th>' . $Lang['user_id'] . '</th>';
    echo '<th>' . $Lang['user_picurl'] . '</th>';
    echo '<th>' . $Lang['user_nickname'] . '</th>';
    echo '<th>' . $Lang['pinglun_reply_content'] . '</th>';
    echo '<th>' . $Lang['pinglun_reply_time'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    foreach ($replyList as $key => $value){
        
        $modelUrl = $adminBaseUrl.'&tmod=index';
        $tcshopInfo = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($value['tcshop_id']);
        $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['reply_user_id']);

        echo '<tr style="background-color: #FCFAFA;">';
        echo '<td>'.$tcshopInfo['name'].'</td>';
        echo '<td>'.$userInfo['id'].'</td>';
        echo '<td><img src="'.$userInfo['picurl'].'" width="40" /></td>';
        echo '<td>'.$userInfo['nickname'].'</td>';
        echo '<td>' . $value['content'] . '</td>';
        echo '<td>' . dgmdate($value['reply_time'], 'Y-m-d H:i:s',$tomSysOffset) . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['del'] . '</a>';
        echo '</td>';
        echo '</tr>';
        
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl);	
    showsubmit('', '', '', '', $multi, false);
}
