<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/plugin');

if(isset($_G['uid']) && $_G['uid'] > 0 && $_G['groupid'] == 1){
    
    $sql = '';

    $tom_tcshop_field = C::t('#tom_tcshop#tom_tcshop')->fetch_all_field();
    if (!isset($tom_tcshop_field['ruzhu_level'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `ruzhu_level` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tcshop_field['admin_edit'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `admin_edit` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tcshop_field['city_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `city_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tcshop_field['area_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `area_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tcshop_field['street_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `street_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tcshop_field['tabs'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `tabs` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tcshop_field['is_ok'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `is_ok` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tcshop_field['business_licence'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `business_licence` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tcshop_field['tj_hehuoren_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `tj_hehuoren_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tcshop_field['link'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `link` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tcshop_field['link_name'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `link_name` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tcshop_field['base_level'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `base_level` int(11) DEFAULT '1';\n";
    }
    if (!isset($tom_tcshop_field['base_time'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `base_time` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tcshop_field['shopkeeper_tel'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop` ADD `shopkeeper_tel` varchar(255) DEFAULT NULL;\n";
    }
    
    $tom_tcshop_cate_field = C::t('#tom_tcshop#tom_tcshop_cate')->fetch_all_field();
    if (!isset($tom_tcshop_cate_field['youhui_model_name'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop_cate` ADD `youhui_model_name` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tcshop_cate_field['youhui_model_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop_cate` ADD `youhui_model_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tcshop_cate_field['youhui_model_ids'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop_cate` ADD `youhui_model_ids` varchar(255) DEFAULT NULL;\n";
    }
    
    $tom_tcshop_photo_field = C::t('#tom_tcshop#tom_tcshop_photo')->fetch_all_field();
    if (!isset($tom_tcshop_photo_field['oss_picurl'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop_photo` ADD `oss_picurl` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tcshop_photo_field['oss_status'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop_photo` ADD `oss_status` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tcshop_photo_field['qiniu_picurl'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop_photo` ADD `qiniu_picurl` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_tcshop_photo_field['qiniu_status'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop_photo` ADD `qiniu_status` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_tcshop_photo_field['type_id'])) {
        $sql .= "ALTER TABLE `pre_tom_tcshop_photo` ADD `type_id` int(11) DEFAULT '1';\n";
    }

    if (!empty($sql)) {
        runquery($sql);
    }
    
    $sql = <<<EOF
    CREATE TABLE IF NOT EXISTS `pre_tom_tcshop_clerk` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `tcshop_id` int(11) DEFAULT '0',
      `user_id` int(11) DEFAULT '0',
      `add_time` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tcshop_tuwen` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `tcshop_id` int(11) DEFAULT '0',
      `picurl` varchar(255) DEFAULT NULL,
      `txt` text,
      `paixu` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tcshop_clicks_log` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `tcshop_id` int(11) DEFAULT '0',
        `ip` int(11) unsigned DEFAULT '0',
        `today_time` int(11) DEFAULT '0',
        `log_time` int(11) DEFAULT '0',
        `part1` varchar(255) DEFAULT NULL,
        `part2` varchar(255) DEFAULT NULL,
        `part3` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;
            
    CREATE TABLE IF NOT EXISTS `pre_tom_tcshop_pinglun_photo` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `pinglun_id` int(11) DEFAULT '0',
        `picurl` varchar(255) DEFAULT NULL,
        `add_time` int(11) DEFAULT '0',
        `part1` varchar(255) DEFAULT NULL,
        `part2` varchar(255) DEFAULT NULL,
        `part3` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;

EOF;

    runquery($sql);
    

    echo 'OK';exit;
    
}else{
    exit('Access Denied');
}



