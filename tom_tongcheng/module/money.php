<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$moneylogListTmp = C::t('#tom_tongcheng#tom_tongcheng_money_log')->fetch_all_list(" AND user_id={$__UserInfo['id']} ","ORDER BY id DESC",0,6);
$moneylogList = array();
if(is_array($moneylogListTmp) && !empty($moneylogListTmp)){
    foreach ($moneylogListTmp as $key => $value){
        $moneylogList[$key] = $value;
        if($value['type_id'] == 1){
            $tixianInfo = C::t('#tom_tongcheng#tom_tongcheng_money_tixian')->fetch_by_id($value['tixian_id']);
            $moneylogList[$key]['tixianInfo'] = $tixianInfo;
        }
    }
}


$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_tongcheng:money");  




