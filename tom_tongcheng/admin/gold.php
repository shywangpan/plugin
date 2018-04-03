<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=gold&user_id='.$_GET['user_id'];
$modListUrl = $adminListUrl.'&tmod=gold&user_id='.$_GET['user_id'];
$modFromUrl = $adminFromUrl.'&tmod=gold&user_id='.$_GET['user_id'];

if($_GET['act'] == 'add' && $_GET['formhash'] == FORMHASH){
    $gold_num = isset($_GET['gold_num'])? intval($_GET['gold_num']):0;
    $type = isset($_GET['type'])? intval($_GET['type']):0;
    $user_id = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($user_id);
    if($type == 1){
        $updateData = array();
        $updateData['score'] = $userInfo['score'] + $gold_num;
        C::t('#tom_tongcheng#tom_tongcheng_user')->update($userInfo['id'], $updateData);
        
        $insertData = array();
        $insertData['user_id']          = $userInfo['id'];
        $insertData['score_value']      = $gold_num;
        $insertData['old_value']        = $userInfo['score'];
        $insertData['log_type']         = 8;
        $insertData['log_time']         = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_score_log')->insert($insertData);
        
    }else if($type == 2){
        $shengyuScore = $userInfo['score'] - $gold_num;
        if($shengyuScore < 0){
            $shengyuScore = 0;
            $gold_num = $userInfo['score'];
        }
        
        $updateData = array();
        $updateData['score'] = $shengyuScore;
        C::t('#tom_tongcheng#tom_tongcheng_user')->update($userInfo['id'], $updateData);
        
        $insertData = array();
        $insertData['user_id']          = $userInfo['id'];
        $insertData['score_value']      = $gold_num;
        $insertData['old_value']        = $userInfo['score'];
        $insertData['log_type']         = 9;
        $insertData['log_time']         = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_score_log')->insert($insertData);
        
    }
    
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else{
    
    $user_id = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($user_id);
    
    tomshownavheader();
    tomshownavli($userInfo['nickname'],"",true);
    tomshownavli(' > '.$Lang['gold_shengyu_score'].' '.$userInfo['score'],"",true);
    tomshownavfooter();
    showformheader($modFromUrl.'&act=add&user_id='.$user_id.'&formhash='.$formhash,'enctype');
    showtableheader();
    
    tomshowsetting(true,array('title'=>$Lang['gold_num'],'name'=>'gold_num','value'=>$options['gold_num'],'msg'=>$Lang['gold_num_msg']),"input");
    $type_item = array(1=>$Lang['gold_type1'],2=>$Lang['gold_type2']);
    tomshowsetting(true,array('title'=>$Lang['gold_type'],'name'=>'type','value'=>1,'msg'=>$Lang['gold_type_msg'],'item'=>$type_item),"radio");
    
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $pagesize = 1000;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_tongcheng#tom_tongcheng_score_log')->fetch_all_count("AND user_id={$user_id}");
    $scorelogList = C::t('#tom_tongcheng#tom_tongcheng_score_log')->fetch_all_list("AND user_id={$user_id}"," ORDER BY id DESC ",$start,$pagesize);
    
    tomshownavheader();
    tomshownavli($userInfo['nickname'],"",true);
    tomshownavli(' > '.$Lang['moneylog_edit_gold'],"",true);
    tomshownavfooter();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . ID . '</th>';
    echo '<th>' . $Lang['gold_list_biaoqian'] . '</th>';
    echo '<th>' . $Lang['gold_list_change_value'] . '</th>';
    echo '<th>' . $Lang['gold_list_old_value'] . '</th>';
    echo '<th>' . $Lang['gold_list_log_time'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($scorelogList as $key => $value) {
        $log_type = '';
        if($value['log_type'] == 1){
            $log_type = $Lang['gold_type_title1'];
        }else if($value['log_type'] == 2){
            $log_type = $Lang['gold_type_title2'];
        }else if($value['log_type'] == 3){
            $log_type = $Lang['gold_type_title3'];
        }else if($value['log_type'] == 4){
            $log_type = $Lang['gold_type_title4'];
        }else if($value['log_type'] == 5){
            $log_type = $Lang['gold_type_title5'];
        }else if($value['log_type'] == 6){
            $log_type = $Lang['gold_type_title6'];
        }else if($value['log_type'] == 7){
            $log_type = $Lang['gold_type_title7'];
        }else if($value['log_type'] == 8){
            $log_type = $Lang['gold_type_title8'];
        }else if($value['log_type'] == 9){
            $log_type = $Lang['gold_type_title9'];
        }else if($value['log_type'] == 10){
            $log_type = $Lang['gold_type_title10'];
        }else if($value['log_type'] == 11){
            $log_type = $Lang['gold_type_title11'];
        }else if($value['log_type'] == 12){
            $log_type = $Lang['gold_type_title12'];
        }
        
        echo '<tr>';
        echo '<td>' . $value['id'] . '</td>';
        echo '<td>' . $log_type . '</td>';
        echo '<td>' . $value['score_value'] . '</td>';
        echo '<td>' . $value['old_value'] . '</td>';
        echo '<td>' . dgmdate($value['log_time'],"Y-m-d H:i",$tomSysOffset) . '</td>';
        
        $i++;
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl);	
    showsubmit('', '', '', '', $multi, false);
     
}

