<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


function wx_iconv_recurrence($value) {
    if(is_array($value)) {
        foreach($value AS $key => $val) {
            $value[$key] = wx_iconv_recurrence($val);
        }
    } else {
        $value = diconv($value, 'utf-8', CHARSET);
    }
    return $value;
}
