<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
   微信支付回调接口文件
*/

if(!defined('IN_DISCUZ') || !defined('IN_TOM_PAY')) {
	exit('Access Denied');
}

$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];
$tomSysOffset = getglobal('setting/timeoffset');
$nowDayTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),dgmdate($_G['timestamp'], 'j',$tomSysOffset),dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;
$nowMonthTime = dgmdate($_G['timestamp'], 'Ym',$tomSysOffset);
$nowWeekTime = dgmdate($_G['timestamp'], 'YW',$tomSysOffset);

## tchehuoren start
$__ShowTchehuoren = 0;
$tchehuorenConfig = array();
if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_tchehuoren/tom_tchehuoren.inc.php')){
    $tchehuorenConfig = $_G['cache']['plugin']['tom_tchehuoren'];
    if($tchehuorenConfig['open_tchehuoren'] == 1){
        $__ShowTchehuoren = 1;
    }
}
## tchehuoren end
## tcadmin start
$__ShowTcadmin = 0;
$tcadminConfig = array();
if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_tcadmin/tom_tcadmin.inc.php')){
    $tcadminConfig = $_G['cache']['plugin']['tom_tcadmin'];
    if($tcadminConfig['open_fc'] == 1){
        $__ShowTcadmin = 1;
    }
}
## tcadmin end

$appid = trim($tongchengConfig['wxpay_appid']);  
$appsecret = trim($tongchengConfig['wxpay_appsecret']); 
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/weixin.class.php';
$weixinClass = new weixinClass($appid,$appsecret);
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/templatesms.class.php';
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/function.core.php';

$orderInfo = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_by_order_no($order_no);
if($orderInfo && $orderInfo['order_status'] == 1){
    $updateData = array();
    $updateData['order_status'] = 2;
    $updateData['pay_time'] = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng_order')->update($orderInfo['id'],$updateData);
    
    Log::DEBUG("update order:" . json_encode(iconv_to_utf8($orderInfo['order_no'])));
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($orderInfo['tongcheng_id']);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengInfo['user_id']);
    
    if($orderInfo['order_type'] == 1){
        
        $updateData = array();
        $updateData['status'] = 1;
        $updateData['pay_status'] = 2;
        $updateData['refresh_time'] = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng')->update($orderInfo['tongcheng_id'],$updateData);
        
    }else if($orderInfo['order_type'] == 2){
        
        $updateData = array();
        $updateData['refresh_time'] = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng')->update($orderInfo['tongcheng_id'],$updateData);
        
    }else if($orderInfo['order_type'] == 3){
        
        $toptime = TIMESTAMP;
        if($tongchengInfo['toptime'] > TIMESTAMP){
            $toptime = $tongchengInfo['toptime'] + $orderInfo['time_value']*86400;
        }else{
            $toptime = TIMESTAMP + $orderInfo['time_value']*86400;
        }
        $updateData = array();
        $updateData['topstatus'] = 1;
        $updateData['toprand'] = 10000;
        $updateData['toptime'] = $toptime;
        $updateData['refresh_time'] = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng')->update($tongchengInfo['id'],$updateData);
    }

    # fc start
    $adminFc = false;
    if($__ShowTchehuoren == 1 && $userInfo['tj_hehuoren_id'] > 0){

        $shenyu_money = $orderInfo['pay_price'];
        $child_site_fc_money = $tchehuoren_fc_money = $tctchehuorenParent_fc_money = 0;
        
        $tchehuorenInfo = C::t('#tom_tchehuoren#tom_tchehuoren')->fetch_by_id($userInfo['tj_hehuoren_id']);
        if($tchehuorenInfo){
            $tchehuorenDengji = C::t('#tom_tchehuoren#tom_tchehuoren_dengji')->fetch_by_id($tchehuorenInfo['dengji_id']);
            $tchehuorenUserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_openid($tchehuorenInfo['openid']);
        }
        
        $tctchehuorenParentInfo = $tctchehuorenParentDengji = $tctchehuorenParentUserInfo = array();
        if($tchehuorenInfo['tj_hehuoren_id'] > 0 && $tchehuorenConfig['open_subordinate'] == 1){
            $tctchehuorenParentInfo = C::t("#tom_tchehuoren#tom_tchehuoren")->fetch_by_id($tchehuorenInfo['tj_hehuoren_id']);
            $tctchehuorenParentDengji = C::t('#tom_tchehuoren#tom_tchehuoren_dengji')->fetch_by_id($tctchehuorenParentInfo['dengji_id']);
            $tctchehuorenParentUserInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_openid($tctchehuorenParentInfo['openid']);
        }
        
        if($tchehuorenInfo && $tchehuorenInfo['status'] == 1 && $tchehuorenDengji['fl_fc_open'] == 1){
            if($orderInfo['site_id'] > 1){
                $sitesInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($orderInfo['site_id']);
                $sitename = $sitesInfo['name'];
                if($__ShowTcadmin && $sitesInfo['hehuoren_fc_open'] == 1){
                    
                    $tchehuoren_fc_money = $orderInfo['pay_price'] * ($tchehuorenDengji['fl_fc_scale']/100);
                    $tchehuoren_fc_money = number_format($tchehuoren_fc_money,2);
                    
                    $fc_scale = $tcadminConfig['fc_scale'];
                    if($sitesInfo['fl_fc_scale'] > 0){
                        $fc_scale = $sitesInfo['fl_fc_scale'];
                    }
                    $child_site_fc_money = $orderInfo['pay_price'] * ($fc_scale/100);
                    $child_site_fc_money = number_format($child_site_fc_money,2);
                    $child_site_fc_money = $child_site_fc_money - $tchehuoren_fc_money;
                    
                    if(!empty($tctchehuorenParentInfo) && $tctchehuorenParentInfo['status'] == 1){
                        $tctchehuorenParent_fc_money = $tchehuoren_fc_money * ($tctchehuorenParentDengji['tuijian_fc_scale']/100);
                        $tctchehuorenParent_fc_money = number_format($tctchehuorenParent_fc_money,2);
                        $tchehuoren_fc_money   = $tchehuoren_fc_money - $tctchehuorenParent_fc_money;
                    }
                    
                    $shenyu_money = $shenyu_money - $child_site_fc_money - $tchehuoren_fc_money;

                }else{
                    if($__ShowTcadmin == 1){
                        $sitesInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($orderInfo['site_id']);
                        $fc_scale = $tcadminConfig['fc_scale'];
                        if($sitesInfo['fl_fc_scale'] > 0){
                            $fc_scale = $sitesInfo['fl_fc_scale'];
                        }
                        $child_site_fc_money = $orderInfo['pay_price'] * ($fc_scale/100);
                        $child_site_fc_money = number_format($child_site_fc_money,2);
                    }
                    
                    $shenyu_money = $shenyu_money - $child_site_fc_money;
                    
                }
                
            }else{
                $sitename = $tongchengConfig['plugin_name'];
                $tchehuoren_fc_money = $orderInfo['pay_price'] * ($tchehuorenDengji['fl_fc_scale']/100);
                $tchehuoren_fc_money = number_format($tchehuoren_fc_money,2);
                
                if(!empty($tctchehuorenParentInfo) && $tctchehuorenParentInfo['status'] == 1){
                    $tctchehuorenParent_fc_money = $tchehuoren_fc_money * ($tctchehuorenParentDengji['tuijian_fc_scale']/100);
                    $tctchehuorenParent_fc_money = number_format($tctchehuorenParent_fc_money,2);
                    $tchehuoren_fc_money   = $tchehuoren_fc_money - $tctchehuorenParent_fc_money;
                }
                
                $shenyu_money = $shenyu_money - $tchehuoren_fc_money - $tctchehuorenParent_fc_money;
            }
            
            Log::DEBUG("update shenyu_money:" . $shenyu_money);
            Log::DEBUG("update child_site_fc_money:" . $child_site_fc_money);
            Log::DEBUG("update tchehuoren_fc_money:" . $tchehuoren_fc_money);
            Log::DEBUG("update TCtchehuoren_fc_money:" . $tctchehuorenParent_fc_money);
            
            if($orderInfo['pay_price'] >= ($child_site_fc_money +  $tchehuoren_fc_money + $tctchehuorenParent_fc_money)){  

                if($child_site_fc_money > 0){
                    $walletInfo = C::t('#tom_tcadmin#tom_tcadmin_wallet')->fetch_by_site_id($orderInfo['site_id']);
                    
                    $old_money = 0;
                    if($walletInfo){
                        $old_money = $walletInfo['account_balance'];

                        $updateData = array();
                        $updateData['account_balance']   = $walletInfo['account_balance'] + $child_site_fc_money;
                        $updateData['total_income']   = $walletInfo['total_income'] + $child_site_fc_money;
                        C::t('#tom_tcadmin#tom_tcadmin_wallet')->update($walletInfo['id'],$updateData);
                    }else{
                        $insertData = array();
                        $insertData['site_id']              = $orderInfo['site_id'];
                        $insertData['account_balance']      = $child_site_fc_money;
                        $insertData['total_income']         = $child_site_fc_money;
                        $insertData['add_time']             = TIMESTAMP;
                        C::t('#tom_tcadmin#tom_tcadmin_wallet')->insert($insertData);
                    }

                    $insertData = array();
                    $insertData['site_id']      = $orderInfo['site_id'];
                    $insertData['log_type']     = 1;
                    $insertData['change_money'] = $child_site_fc_money;
                    $insertData['old_money']    = $old_money;
                    $insertData['beizu']        = contentFormat($tongchengInfo['content']);
                    $insertData['order_no']     = $orderInfo['order_no'];
                    $insertData['order_type']   = $orderInfo['order_type'];
                    $insertData['log_ip']       = $_G['clientip'];
                    $insertData['log_time']     = TIMESTAMP;
                    C::t('#tom_tcadmin#tom_tcadmin_wallet_log')->insert($insertData); 
                }
                
                $type = '';
                if($orderInfo['order_type'] == 1){
                    $type = lang('plugin/tom_tongcheng', 'order_type_1');
                }else if($orderInfo['order_type'] == 2){
                    $type = lang('plugin/tom_tongcheng', 'order_type_2');
                }else if($orderInfo['order_type'] == 3){
                    $type = lang('plugin/tom_tongcheng', 'order_type_3');
                }
                
                $sendTemplateTchehuoren = false;
                if($tchehuoren_fc_money > 0){
                    $sendTemplateTchehuoren = true;
                    
                    $insertData = array();
                    $insertData['order_no']         = $orderInfo['order_no'];
                    $insertData['hehuoren_id']      = $tchehuorenInfo['id'];
                    $insertData['ly_user_id']       = $userInfo['id'];
                    $insertData['child_hehuoren_id'] = 0;
                    $insertData['today_time']       = $nowDayTime;
                    $insertData['week_time']        = $nowWeekTime;
                    $insertData['month_time']       = $nowMonthTime;
                    $insertData['type']             = $type;
                    $insertData['shouyi_price']     = $tchehuoren_fc_money;
                    $insertData['content']          = lang('plugin/tom_tongcheng', 'hehuoren_beizu_1') . $tongchengInfo['id'] . lang('plugin/tom_tongcheng', 'hehuoren_beizu_2') . $userInfo['nickname'];
                    $insertData['shouyi_status']    = 1;
                    $insertData['add_time']         = TIMESTAMP;
                    C::t('#tom_tchehuoren#tom_tchehuoren_shouyi')->insert($insertData);
                }
                
                $sendTemplateTchehuorenParent = false;
                if($tctchehuorenParent_fc_money > 0){
                    $sendTemplateTchehuorenParent = true;
                    
                    $insertData = array();
                    $insertData['order_no']         = $orderInfo['order_no'];
                    $insertData['hehuoren_id']      = $tctchehuorenParentInfo['id'];
                    $insertData['ly_user_id']       = $userInfo['id'];
                    $insertData['child_hehuoren_id'] = $tchehuorenInfo['id'];
                    $insertData['today_time']       = $nowDayTime;
                    $insertData['week_time']        = $nowWeekTime;
                    $insertData['month_time']       = $nowMonthTime;
                    $insertData['type']             = $type;
                    $insertData['shouyi_price']     = $tctchehuorenParent_fc_money;
                    $insertData['content']          = lang('plugin/tom_tongcheng', 'hehuoren_beizu_1') . $tongchengInfo['id'] . lang('plugin/tom_tongcheng', 'hehuoren_beizu_2') . $userInfo['nickname'];
                    $insertData['shouyi_status']    = 1;
                    $insertData['add_time']         = TIMESTAMP;
                    C::t('#tom_tchehuoren#tom_tchehuoren_shouyi')->insert($insertData);
                }
                
                if($sendTemplateTchehuoren == true){
                    $access_token = $weixinClass->get_access_token();
                    if($access_token && !empty($tchehuorenInfo['openid'])){
                        $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_tchehuoren&site={$orderInfo['site_id']}&mod=index");
                        $shouyiText = str_replace("{NICKNAME}",$userInfo['nickname'], lang('plugin/tom_tongcheng', 'paynotify_hehuoren_template'));
                        $shouyiText = str_replace("{TONGCHNEG}",$sitename, $shouyiText);
                        $shouyiText = str_replace("{TYPE}",$type, $shouyiText);
                        $shouyiText = str_replace("{MONEY}",$tchehuoren_fc_money, $shouyiText);
                        $smsData = array(
                            'first'         => $shouyiText,
                            'keyword1'      => $tchehuorenConfig['plugin_name'],
                            'keyword2'      => dgmdate(TIMESTAMP,"Y-m-d H:i:s",$tomSysOffset),
                            'remark'        => ''
                        );
                        if(!empty($tchehuorenConfig['template_id'])){
                            $template_id = $tchehuorenConfig['template_id'];
                        }else{
                            $template_id = $tongchengConfig['template_id'];
                        }
                        @$r = $templateSmsClass->sendSms01($tchehuorenInfo['openid'], $template_id, $smsData);
                    }
                }
                
                if($sendTemplateTchehuorenParent == true){
                    
                    $access_token = $weixinClass->get_access_token();
                    if($access_token && !empty($tctchehuorenParentInfo['openid'])){
                        $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_tchehuoren&site={$orderInfo['site_id']}&mod=index");
                        $shouyiText = str_replace("{TCHEHUOREN}",$tchehuorenInfo['xm'], lang('plugin/tom_tongcheng', 'paynotify_hehuorenparent_template'));
                        $shouyiText = str_replace("{NICKNAME}",$userInfo['nickname'], $shouyiText);
                        $shouyiText = str_replace("{TONGCHNEG}",$sitename, $shouyiText);
                        $shouyiText = str_replace("{TYPE}",$type, $shouyiText);
                        $shouyiText = str_replace("{MONEY}",$tctchehuorenParent_fc_money, $shouyiText);
                        $smsData = array(
                            'first'         => $shouyiText,
                            'keyword1'      => $tchehuorenConfig['plugin_name'],
                            'keyword2'      => dgmdate(TIMESTAMP,"Y-m-d H:i:s",$tomSysOffset),
                            'remark'        => ''
                        );
                        if(!empty($tchehuorenConfig['template_id'])){
                            $template_id = $tchehuorenConfig['template_id'];
                        }else{
                            $template_id = $tongchengConfig['template_id'];
                        }
                        @$r = $templateSmsClass->sendSms01($tctchehuorenParentInfo['openid'], $template_id, $smsData);
                    }
                }
            }
            
        }else{
            $adminFc = true;
        }
    }else{
        $adminFc = true;
    }
    
    if($__ShowTcadmin == 1 && $adminFc){

        $sitesInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($orderInfo['site_id']);
        $fc_scale = $tcadminConfig['fc_scale'];
        if($sitesInfo['fl_fc_scale'] > 0){
            $fc_scale = $sitesInfo['fl_fc_scale'];
        }
        $fc_money = $orderInfo['pay_price']*($fc_scale/100);
        $fc_money = number_format($fc_money,2);

        Log::DEBUG("update fc_money:" . $fc_money);

        $walletInfo = C::t('#tom_tcadmin#tom_tcadmin_wallet')->fetch_by_site_id($orderInfo['site_id']);

        $old_money = 0;
        if($walletInfo){
            $old_money = $walletInfo['account_balance'];

            $updateData = array();
            $updateData['account_balance']   = $walletInfo['account_balance'] + $fc_money;
            $updateData['total_income']   = $walletInfo['total_income'] + $fc_money;
            C::t('#tom_tcadmin#tom_tcadmin_wallet')->update($walletInfo['id'],$updateData);
        }else{
            $insertData = array();
            $insertData['site_id']              = $orderInfo['site_id'];
            $insertData['account_balance']      = $fc_money;
            $insertData['total_income']         = $fc_money;
            $insertData['add_time']             = TIMESTAMP;
            C::t('#tom_tcadmin#tom_tcadmin_wallet')->insert($insertData);
        }

        $insertData = array();
        $insertData['site_id']      = $orderInfo['site_id'];
        $insertData['log_type']     = 1;
        $insertData['change_money'] = $fc_money;
        $insertData['old_money']    = $old_money;
        $insertData['beizu']        = contentFormat($tongchengInfo['content']);
        $insertData['order_no']     = $orderInfo['order_no'];
        $insertData['order_type']   = $orderInfo['order_type'];
        $insertData['log_ip']       = $_G['clientip'];
        $insertData['log_time']     = TIMESTAMP;
        C::t('#tom_tcadmin#tom_tcadmin_wallet_log')->insert($insertData);Log::DEBUG("update fc_end:" . $fc_money);
    }
    # fc end
    
    
}
