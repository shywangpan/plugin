<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=tixian';
$modListUrl = $adminListUrl.'&tmod=tixian';
$modFromUrl = $adminFromUrl.'&tmod=tixian';

if($_GET['act'] == 'ok1'){
    
    $tixianInfo = C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->fetch_by_id($_GET['id']);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tixianInfo['user_id']);
    
    $wxpay_mchid = trim($tongchengConfig['wxpay_mchid']);
    $wxpay_key = trim($tongchengConfig['wxpay_key']);
    $wxpay_appid = trim($tongchengConfig['wxpay_appid']);
    $wxpay_ip = trim($tongchengConfig['wxpay_ip']);
    
    define("TOM_WXPAY_MCHID", $wxpay_mchid); // 填写商户号
    define("TOM_WXPAY_KEY", $wxpay_key); // 微信支付秘钥
    define("TOM_WXPAY_APPID", $wxpay_appid); // 微信appID
    define("TOM_WXPAY_IP", $wxpay_ip); // 服务器IP
    
    $tixian_wx_desc = '['.$tongchengConfig['plugin_name'].']'.$Lang['tixian_wx_desc'];
	$desc = diconv($tixian_wx_desc,CHARSET,'utf-8');
    $price = $tixianInfo['money']*100;
    
    if(empty($tixianInfo['tx_order_no'])){
        $order_no = TOM_WXPAY_MCHID.date('YmdHis').rand(1000, 9999);
        $updateData = array();
        $updateData['tx_order_no']     = $order_no;
        C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->update($tixianInfo['id'],$updateData);
        $tixianInfo['tx_order_no'] = $order_no;
    }
    
	# 企业付款
	define("TOM_WXPAY_SSLCERT_PATH", DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/WxFuKuanApi/cert/apiclient_cert.pem');
	define("TOM_WXPAY_SSLKEY_PATH", DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/WxFuKuanApi/cert/apiclient_key.pem');
	define("TOM_WXPAY_SSLROOT_PATH", DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/WxFuKuanApi/cert/rootca.pem');

	include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/WxFuKuanApi/WxFuKuanApi.php';
	$fukuan = new WxFuKuanApi();
	$r = $fukuan->send($userInfo['openid'],$price,$tixianInfo['tx_order_no'],$desc);
    if($r['status'] == 1){
        $updateData['status'] = 2;
        $updateData['beizu'] = wx_iconv_recurrence($r['response_xml']);
        //$updateData['tixian_time']     = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->update($tixianInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        $response_xml = wx_iconv_recurrence($r['response_xml']);
        $response_xml = str_replace("<","&lt;",$response_xml);
        $response_xml = str_replace(">","&gt;",$response_xml);
        cpmsg($response_xml, $modListUrl, 'error');
    }
    
}else if($_GET['act'] == 'batch_ok1'){
    
    $wxpay_mchid    = trim($tongchengConfig['wxpay_mchid']);
    $wxpay_key      = trim($tongchengConfig['wxpay_key']);
    $wxpay_appid    = trim($tongchengConfig['wxpay_appid']);
    $wxpay_ip       = trim($tongchengConfig['wxpay_ip']);

    define("TOM_WXPAY_MCHID", $wxpay_mchid); // 填写商户号
    define("TOM_WXPAY_KEY", $wxpay_key); // 微信支付秘钥
    define("TOM_WXPAY_APPID", $wxpay_appid); // 微信appID
    define("TOM_WXPAY_IP", $wxpay_ip); // 服务器IP

    # 企业付款
    define("TOM_WXPAY_SSLCERT_PATH", DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/WxFuKuanApi/cert/apiclient_cert.pem');
    define("TOM_WXPAY_SSLKEY_PATH", DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/WxFuKuanApi/cert/apiclient_key.pem');
    define("TOM_WXPAY_SSLROOT_PATH", DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/WxFuKuanApi/cert/rootca.pem');

    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/WxFuKuanApi/WxFuKuanApi.php';
    $fukuan = new WxFuKuanApi();
            
    if(is_array($_GET['ids']) && !empty($_GET['ids'])){
        foreach ($_GET['ids'] as $key => $value){
            $tixianInfo = array();
            $tixianInfo = C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->fetch_by_id($value);
            if($tixianInfo['status'] == 1 && $tixianInfo['type_id'] == 1){
                $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tixianInfo['user_id']);

                $tixian_wx_desc = '['.$tongchengConfig['plugin_name'].']'.$Lang['tixian_wx_desc'];
                $desc = diconv($tixian_wx_desc,CHARSET,'utf-8');
                $price = $tixianInfo['money']*100;

                if(empty($tixianInfo['tx_order_no'])){
                    $order_no = TOM_WXPAY_MCHID.date('YmdHis').rand(1000, 9999);
                    $updateData = array();
                    $updateData['tx_order_no']     = $order_no;
                    C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->update($tixianInfo['id'],$updateData);
                    $tixianInfo['tx_order_no'] = $order_no;
                }

                $r = $fukuan->send($userInfo['openid'],$price,$tixianInfo['tx_order_no'],$desc);
                if($r['status'] == 1){
                    $updateData['status'] = 2;
                    $updateData['beizu'] = wx_iconv_recurrence($r['response_xml']);
                    //$updateData['tixian_time']     = TIMESTAMP;
                    C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->update($tixianInfo['id'],$updateData);
                }else{
                    $response_xml = wx_iconv_recurrence($r['response_xml']);
                    $response_xml = str_replace("<","&lt;",$response_xml);
                    $response_xml = str_replace(">","&gt;",$response_xml);
                    cpmsg($response_xml, $modListUrl, 'error');
                }
            }
        }
    }
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['act'] == 'ok2'){
    
    $tixianInfo = C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->fetch_by_id($_GET['id']);

    $updateData['status'] = 2;
    $updateData['tixian_time']     = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->update($tixianInfo['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['act'] == 'cancel'){
    $tixianInfo = C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->fetch_by_id($_GET['id']);
    $updateData['status'] = 3;
    $updateData['tixian_time']     = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->update($tixianInfo['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else{
    
    $status = intval($_GET['status'])>0? intval($_GET['status']):0;
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $pagesize = 50;
    $start = ($page-1)*$pagesize;
    
    $where = "";
    if($status == 1){
        $where = " AND status=1 ";
    }
    $count = C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->fetch_all_count(" {$where} ");
    $tixianList = C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->fetch_all_list(" {$where} "," ORDER BY id DESC ",$start,$pagesize);
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['tixian_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    $file_apiclient_cert = DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/WxFuKuanApi/cert/apiclient_cert.pem';
    $file_apiclient_key = DISCUZ_ROOT.'source/plugin/tom_tongcheng/class/WxFuKuanApi/cert/apiclient_key.pem';
    if(!file_exists($file_apiclient_cert) || !file_exists($file_apiclient_key)){
        echo '<li><font color="#fd0d0d"><b>' . $Lang['tixian_error_1'] . '</b></font></li>';
    }
    if(empty($tongchengConfig['wxpay_ip'])){
        echo '<li><font color="#fd0d0d"><b>' . $Lang['tixian_error_2'] . '</b></font></li>';
    }
    echo '</ul></td></tr>';
    showtablefooter();
    
    tomshownavheader();
    if($status == 1){
        tomshownavli($Lang['tixian_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['tixian_status_1'],$modBaseUrl."&status=1",true);
    }else{
        tomshownavli($Lang['tixian_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['tixian_status_1'],$modBaseUrl."&status=1",false);
    }
    tomshownavfooter();
    
    echo '<form name="cpform2" id="cpform2" method="post" autocomplete="off" action="'.ADMINSCRIPT.'?action='.$modFromUrl.'&formhash='.FORMHASH.'" onsubmit="return ok1_form();">'.
		'<input type="hidden" name="formhash" value="'.FORMHASH.'" />';
    showtableheader();
    echo '<tr class="header">';
    echo '<th> - </th>';
    echo '<th>' . $Lang['tixian_wx_nickname'] . '</th>';
    echo '<th>' . $Lang['tixian_money'] . '</th>';
    echo '<th>' . $Lang['tixian_type_id'] . '</th>';
    echo '<th>' . $Lang['tixian_status'] . '</th>';
    echo '<th>' . $Lang['tixian_time'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($tixianList as $key => $value) {
        
        $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']);
        
        echo '<tr>';
        if($value['status'] == 1 && $value['type_id'] == 1){
            echo '<td><input class="checkbox" type="checkbox" name="ids[]" value="' . $value['id'] . '" ></td>';
        }else{
            echo '<td>-</td>';
        }
        echo '<td>' .$userInfo['nickname'] . '(<a target="_blank" href="'.$adminBaseUrl.'&tmod=user'.'&act=moneylog&user_id='.$userInfo['id'].'&formhash='.FORMHASH.'">' . $Lang['moneylog_list_title']. '</a>)</td>';
        echo '<td>' .$value['money'] . '</td>';
        echo '<td>';
        if($value['type_id'] == 1){
            echo '<b><font color="#238206">'.$Lang['tixian_type_id_1'].'</font></b><br/>';
            echo "".$Lang['tixian_wx_openid'].'<font color="#8e8e8e">'.$userInfo['openid'].'</font><br/>';
            echo "".$Lang['tixian_order_no'].'<font color="#8e8e8e">'.$value['tx_order_no'].'</font><br/>';
        }else if($value['type_id'] == 2){
            echo '<b><font color="#0894fb">'.$Lang['tixian_type_id_2'].'</font></b><br/>';
            echo "".$Lang['tixian_alipay_zhanghao'].'<font color="#8e8e8e">'. $value['alipay_zhanghao'].'</font><br/>';
            echo "".$Lang['tixian_alipay_truename'].'<font color="#8e8e8e">'. $value['alipay_truename'].'</font><br/>';
        }
        echo '</td>';
        if($value['status'] == 1){
            echo '<td><font color="#fd0d0d">' . $Lang['tixian_status_1'] . '</font></td>';
        }else if($value['status'] == 2){
            echo '<td><font color="#238206">' . $Lang['tixian_status_2'] . '</font></td>';
        }else if($value['status'] == 3){
            echo '<td><font color="#8e8e8e">' . $Lang['tixian_status_3'] . '</font></td>';
        }else{
            echo '<td>-</td>';
        }
        echo '<td>'.dgmdate($value['tixian_time'],"Y-m-d H:i",$tomSysOffset).'</td>';
        echo '<td>';
        if($value['status'] == 1){
            if($value['type_id'] == 1){
                echo '<a href="javascript:void(0);" onclick="ok1_confirm(\''.$modBaseUrl.'&act=ok1&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['tixian_ok1'] . '</a>&nbsp;|&nbsp;';
                echo '<a href="javascript:void(0);" onclick="ok2_confirm(\''.$modBaseUrl.'&act=ok2&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['tixian_ok3'] . '</a>';
            }else if($value['type_id'] == 2){
                echo '<a href="javascript:void(0);" onclick="ok2_confirm(\''.$modBaseUrl.'&act=ok2&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['tixian_ok2'] . '</a>';
            }
            //echo '<a href="javascript:void(0);" onclick="cancel_confirm(\''.$modBaseUrl.'&act=cancel&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['tixian_status_3'] . '</a>';
        }else{
            echo '<td>-</td>';
        }
        echo '</td>';
        echo '</tr>';
        $i++;
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl);
    showsubmit('', '', '', '', $multi, false);
    
$formstr = <<<EOF
        <tr>
            <td class="td25">
                <input type="checkbox" name="chkall" id="chkallFh9R" class="checkbox" onclick="checkAll('prefix', this.form, 'ids')" />
                <label for="chkallFh9R">{$Lang['checkall']}</label>
            </td>
            <td class="td25">
                <select name="act" >
                    <option value="batch_ok1">{$Lang['tixian_ok1']}</option>
                </select>
            </td>
            <td colspan="15">
                <div class="fixsel"><input type="submit" class="btn" id="submit_announcesubmit" name="announcesubmit" value="{$Lang['batch_btn']}" /></div>
            </td>
        </tr>
        <script type="text/javascript">
        function ok1_form(){
          var r = confirm("{$Lang['batch_make_sure']}")
          if (r == true){
            return true;
          }else{
            return false;
          }
        }
        </script>
EOF;
        echo $formstr;
        showformfooter();
    
    $jsstr = <<<EOF
<script type="text/javascript">
function ok1_confirm(url){
  var r = confirm("{$Lang['tixian_makesure_status_1_msg']}")
  if (r == true){
    window.location = url;
  }else{
    return false;
  }
}
function ok2_confirm(url){
  var r = confirm("{$Lang['tixian_makesure_status_2_msg']}")
  if (r == true){
    window.location = url;
  }else{
    return false;
  }
}
function cancel_confirm(url){
  var r = confirm("{$Lang['tixian_makesure_status_3_msg']}")
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

