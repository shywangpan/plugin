<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/plugin');

if(isset($_G['uid']) && $_G['uid'] > 0 && $_G['groupid'] == 1){
    
    $sql = '';

    $tom_tongcheng_field = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_field();
    if (!isset($tom_tongcheng_field['site_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng` ADD `site_id` int(11) DEFAULT '1';\n";
    }
    if (!isset($tom_tongcheng_field['city_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng` ADD `city_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_field['area_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng` ADD `area_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_field['street_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng` ADD `street_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_field['auto_click_time'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng` ADD `auto_click_time` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_field['shenhe_status'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng` ADD `shenhe_status` int(11) DEFAULT '1';\n";
    }
    if (!isset($tom_tongcheng_field['title'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng` ADD `title` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tongcheng_field['zhuanfa'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng` ADD `zhuanfa` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_field['auto_zhuanfa_time'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng` ADD `auto_zhuanfa_time` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_field['tcshop_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng` ADD `tcshop_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_field['top_sq_time'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng` ADD `top_sq_time` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_field['toprand'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng` ADD `toprand` int(11) DEFAULT '1';\n";
    }

    $tom_tongcheng_common_field = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_all_field();
    if (!isset($tom_tongcheng_common_field['forbid_word'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_common` ADD `forbid_word` text;\n";
    }
    if (!isset($tom_tongcheng_common_field['sfc_txt'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_common` ADD `sfc_txt` text;\n";
    }

    $tom_tongcheng_focuspic_field = C::t('#tom_tongcheng#tom_tongcheng_focuspic')->fetch_all_field();
    if (!isset($tom_tongcheng_focuspic_field['site_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_focuspic` ADD `site_id` int(11) DEFAULT '1';\n";
    }

    $tom_tongcheng_model_field = C::t('#tom_tongcheng#tom_tongcheng_model')->fetch_all_field();
    if (!isset($tom_tongcheng_model_field['area_select'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model` ADD `area_select` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_model_field['must_shenhe'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model` ADD `must_shenhe` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_model_field['only_editor'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model` ADD `only_editor` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_model_field['is_show'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model` ADD `is_show` int(11) DEFAULT '1';\n";
    }
    if (!isset($tom_tongcheng_model_field['sites_show'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model` ADD `sites_show` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_model_field['is_sfc'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model` ADD `is_sfc` int(11) DEFAULT '0';\n";
    }

    $tom_tongcheng_order_field = C::t('#tom_tongcheng#tom_tongcheng_order')->fetch_all_field();
    if (!isset($tom_tongcheng_order_field['site_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_order` ADD `site_id` int(11) DEFAULT '1';\n";
    }
    if (!isset($tom_tongcheng_order_field['tcshop_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_order` ADD `tcshop_id` int(11) DEFAULT '0';\n";
    }

    $tom_tongcheng_topnews_field = C::t('#tom_tongcheng#tom_tongcheng_topnews')->fetch_all_field();
    if (!isset($tom_tongcheng_topnews_field['site_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_topnews` ADD `site_id` int(11) DEFAULT '1';\n";
    }

    $tom_tongcheng_user_field = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_all_field();
    if (!isset($tom_tongcheng_user_field['last_smstp_time'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_user` ADD `last_smstp_time` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_user_field['editor'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_user` ADD `editor` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_user_field['score'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_user` ADD `score` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_user_field['money'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_user` ADD `money` decimal(10,2) DEFAULT '0.00';\n";
    }
    if (!isset($tom_tongcheng_user_field['tixian_money'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_user` ADD `tixian_money` decimal(10,2) DEFAULT '0.00';\n";
    }
    if (!isset($tom_tongcheng_user_field['hongbao_tz'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_user` ADD `hongbao_tz` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_user_field['hongbao_tz_first'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_user` ADD `hongbao_tz_first` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_user_field['is_majia'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_user` ADD `is_majia` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_user_field['member_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_user` ADD `member_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_user_field['unionid'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_user` ADD `unionid` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tongcheng_user_field['tj_hehuoren_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_user` ADD `tj_hehuoren_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_user_field['last_login_time'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_user` ADD `last_login_time` int(11) DEFAULT '0';\n";
    }
    
    $tom_tongcheng_model_type_field = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_all_field();
    if (!isset($tom_tongcheng_model_type_field['info_share_title'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model_type` ADD `info_share_title` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tongcheng_model_type_field['info_share_desc'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model_type` ADD `info_share_desc` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tongcheng_model_type_field['over_time_attr_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model_type` ADD `over_time_attr_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_model_type_field['over_time_do'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model_type` ADD `over_time_do` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_model_type_field['zhapian_msg'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model_type` ADD `zhapian_msg` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tongcheng_model_type_field['sfc_chufa_attr_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model_type` ADD `sfc_chufa_attr_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_model_type_field['sfc_mude_attr_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model_type` ADD `sfc_mude_attr_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_model_type_field['sfc_time_attr_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model_type` ADD `sfc_time_attr_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_model_type_field['sfc_renshu_attr_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model_type` ADD `sfc_renshu_attr_id` int(11) DEFAULT '0';\n";
    }
    
    $tom_tongcheng_model_attr_field = C::t('#tom_tongcheng#tom_tongcheng_model_attr')->fetch_all_field();
    if (!isset($tom_tongcheng_model_attr_field['unit'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model_attr` ADD `unit` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tongcheng_model_attr_field['is_must'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_model_attr` ADD `is_must` int(11) DEFAULT '0';\n";
    }
    
    $tom_tongcheng_attr_field = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_field();
    if (!isset($tom_tongcheng_attr_field['unit'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_attr` ADD `unit` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tongcheng_attr_field['paixu'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_attr` ADD `paixu` int(11) DEFAULT '0';\n";
    }
    
    $tom_tongcheng_photo_field = C::t('#tom_tongcheng#tom_tongcheng_photo')->fetch_all_field();
    if (!isset($tom_tongcheng_photo_field['oss_picurl'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_photo` ADD `oss_picurl` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tongcheng_photo_field['oss_status'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_photo` ADD `oss_status` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_photo_field['qiniu_status'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_photo` ADD `qiniu_status` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_photo_field['qiniu_picurl'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_photo` ADD `qiniu_picurl` varchar(255) DEFAULT NULL;\n";
    }
    
    if (!empty($sql)) {
        runquery($sql);
    }
    
    $sql = <<<EOF
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_district` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) DEFAULT NULL,
      `level` tinyint(3) unsigned DEFAULT '0',
      `upid` mediumint(8) unsigned DEFAULT '0',
      `displayorder` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `idx_upid` (`upid`)
    ) ENGINE=MyISAM;
        
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_nav` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `site_id` int(11) DEFAULT '1',
      `type` int(11) DEFAULT '0',
      `model_id` int(11) DEFAULT '0',
      `title` varchar(255) DEFAULT NULL,
      `picurl` varchar(255) DEFAULT NULL,
      `link` varchar(255) DEFAULT NULL,
      `nsort` int(11) DEFAULT '10',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
        
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_sites` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `manage_openid1` varchar(255) DEFAULT NULL,
      `manage_openid2` varchar(255) DEFAULT NULL,
      `manage_openid3` varchar(255) DEFAULT NULL,
      `manage_user_id` int(11) DEFAULT '0',
      `city_id` int(11) DEFAULT '0',
      `name` varchar(255) DEFAULT NULL,
      `logo` varchar(255) DEFAULT NULL,
      `kefu_qrcode` varchar(255) DEFAULT NULL,
      `share_title` varchar(255) DEFAULT NULL,
      `share_desc` varchar(255) DEFAULT NULL,
      `share_pic` varchar(255) DEFAULT NULL,
      `status` int(11) DEFAULT '1',
      `add_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_tz` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(11) DEFAULT '0',
      `type` int(11) DEFAULT '0',
      `content` text,
      `tz_time` int(11) DEFAULT '0',
      `is_read` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_pinglun` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `tongcheng_id` int(11) DEFAULT '0',
        `user_id` int(11) DEFAULT NULL,
        `content` text,
        `ping_time` int(11) DEFAULT '0',
        `part1` varchar(255) DEFAULT NULL,
        `part2` varchar(255) DEFAULT NULL,
        `part3` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_pinglun_reply` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `ping_id` int(11) DEFAULT '0',
        `tongcheng_id` int(11) DEFAULT '0',
        `reply_user_id` int(11) DEFAULT '0',
        `reply_user_nickname` varchar(255) DEFAULT NULL,
        `reply_user_avatar` varchar(255) DEFAULT NULL,
        `content` text,
        `reply_time` int(11) DEFAULT '0',
        `part1` varchar(255) DEFAULT NULL,
        `part2` varchar(255) DEFAULT NULL,
        `part3` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_score_log` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(11) DEFAULT '0',
      `score_value` int(11) DEFAULT '0',
      `old_value` int(11) DEFAULT '0',
      `log_type` tinyint(4) DEFAULT '0',
      `log_time` int(11) DEFAULT '0',
      `time_key` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_popup` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `site_ids` varchar(255) DEFAULT NULL,
      `title` varchar(255) DEFAULT NULL,
      `link` varchar(255) DEFAULT NULL,
      `content` text,
      `clicks` int(11) DEFAULT '0',
      `status` int(11) DEFAULT '0',
      `add_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_money_log` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(11) DEFAULT '0',
      `type_id` int(11) DEFAULT '0',
      `change_money` decimal(10,2) DEFAULT '0.00',
      `old_money` decimal(10,2) DEFAULT '0.00',
      `tixian_id` int(11) DEFAULT '0',
      `tag` varchar(255) DEFAULT NULL,
      `beizu` text,
      `log_ip` varchar(255) DEFAULT NULL,
      `log_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
        
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_money_tixian` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `tx_order_no` varchar(255) DEFAULT NULL,
      `user_id` int(11) DEFAULT '0',
      `type_id` int(11) DEFAULT '0',
      `money` decimal(10,2) DEFAULT '0.00',
      `alipay_zhanghao` varchar(255) DEFAULT NULL,
      `alipay_truename` varchar(255) DEFAULT NULL,
      `beizu` text,
      `status` int(11) DEFAULT '0',
      `tixian_ip` varchar(255) DEFAULT NULL,
      `tixian_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_content` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `tongcheng_id` int(11) DEFAULT '0',
      `is_show` int(11) DEFAULT '0',
      `content` text,
      `add_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_help` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(255) DEFAULT NULL,
      `content` text,
      `paixu` int(11) DEFAULT '100',
      `add_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_sites_price` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `site_id` int(11) DEFAULT '0',
      `type_id` int(11) DEFAULT '0',
      `free_status` int(11) DEFAULT '1',
      `fabu_price` decimal(10,2) DEFAULT '0.00',
      `refresh_price` decimal(10,2) DEFAULT '0.00',
      `top_price` decimal(10,2) DEFAULT '0.00',
      `add_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tongcheng_sfc_cache` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `site_id` int(11) DEFAULT '0',
        `tongcheng_id` int(11) DEFAULT '0',
        `model_id` int(11) DEFAULT '0',
        `type_id` int(11) DEFAULT '0',
        `chufa` varchar(255) DEFAULT NULL,
        `mude` varchar(255) DEFAULT NULL,
        `chufa_time` varchar(255) DEFAULT NULL,
        `chufa_int_time` int(11) DEFAULT '0',
        `renshu` varchar(255) DEFAULT NULL,
        `add_time` int(11) DEFAULT '0',
        `part1` varchar(255) DEFAULT NULL,
        `part2` varchar(255) DEFAULT NULL,
        `part3` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;

EOF;

    runquery($sql);
    
    $sql = '';

    $tom_tongcheng_sites_field = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_field();
    if (!isset($tom_tongcheng_sites_field['manage_user_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `manage_user_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_sites_field['virtual_clicks'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `virtual_clicks` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_sites_field['virtual_fabunum'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `virtual_fabunum` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_sites_field['virtual_rznum'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `virtual_rznum` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_sites_field['dingyue_qrcode'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `dingyue_qrcode` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tongcheng_sites_field['fl_fc_scale'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `fl_fc_scale` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_sites_field['shop_fc_scale'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `shop_fc_scale` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_sites_field['qg_fc_scale'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `qg_fc_scale` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_sites_field['hehuoren_fc_open'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `hehuoren_fc_open` int(11) DEFAULT '2';\n";
    }
    if (!isset($tom_tongcheng_sites_field['pt_fc_scale'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `pt_fc_scale` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_sites_field['kj_fc_scale'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `kj_fc_scale` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_sites_field['site_one'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `site_one` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tongcheng_sites_field['site_ids'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `site_ids` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tongcheng_sites_field['model_ids'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `model_ids` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tongcheng_sites_field['lbs_name'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `lbs_name` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tongcheng_sites_field['lbs_keywords'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_sites` ADD `lbs_keywords` varchar(255) DEFAULT NULL;\n";
    }

    
    $tom_tongcheng_score_log_field = C::t('#tom_tongcheng#tom_tongcheng_score_log')->fetch_all_field();
    if (!isset($tom_tongcheng_score_log_field['time_key'])) {
        $sql .= "ALTER TABLE `pre_tom_tongcheng_score_log` ADD `time_key` int(11) DEFAULT '0';\n";
    }

    if (!empty($sql)) {
        runquery($sql);
    }

    echo 'OK';exit;
    
}else{
    exit('Access Denied');
}



