<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$sql = <<<EOF
DROP TABLE IF EXISTS `pre_ims_tg_address`;
CREATE TABLE `pre_ims_tg_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `openid` varchar(300)   NOT NULL,
  `cname` varchar(30)   NOT NULL,
  `tel` varchar(20)   NOT NULL,
  `province` varchar(20)  NOT NULL ,
  `city` varchar(20)  NOT NULL,
  `county` varchar(20) NOT NULL,
  `detailed_address` varchar(225),
  `uniacid` int(10) NOT NULL ,
  `addtime` varchar(45)   NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;
DROP TABLE IF EXISTS `pre_ims_tg_adv`;
CREATE TABLE `pre_ims_tg_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
DROP TABLE IF EXISTS `pre_ims_tg_category`;
CREATE TABLE `pre_ims_tg_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' ,
  `name` varchar(50) NOT NULL ,
  `thumb` varchar(255) NOT NULL ,
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' ,
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL ,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' ,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
DROP TABLE IF EXISTS `pre_ims_tg_collect`;
CREATE TABLE `pre_ims_tg_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
DROP TABLE IF EXISTS `pre_ims_tg_goods`;
CREATE TABLE `pre_ims_tg_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gname` varchar(225) NOT NULL,
  `fk_typeid` int(10) unsigned NOT NULL,
  `gsn` varchar(50) NOT NULL ,
  `gnum` int(10) unsigned NOT NULL DEFAULT '0',
  `groupnum` int(10) unsigned NOT NULL ,
  `mprice` decimal(10,2) NOT NULL,
  `gprice` decimal(10,2) NOT NULL ,
  `oprice` decimal(10,2) NOT NULL,
  `freight` decimal(10,2) NOT NULL,
  `gdesc` longtext NOT NULL ,
  `gdesc1` varchar(100) DEFAULT NULL ,
  `gdesc2` varchar(100) DEFAULT NULL ,
  `gdesc3` varchar(100) DEFAULT NULL ,
  `gimg` varchar(225) DEFAULT NULL ,
  `gubtime` int(10) unsigned NOT NULL ,
  `isshow` tinyint(4) NOT NULL DEFAULT '0' ,
  `salenum` int(10) unsigned NOT NULL ,
  `ishot` tinyint(4) NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL,
  `createtime` int(10) unsigned NOT NULL ,
  `uniacid` int(10) NOT NULL ,
  `endtime` int(11) NOT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
DROP TABLE IF EXISTS `pre_ims_tg_goods_atlas`;
CREATE TABLE `pre_ims_tg_goods_atlas` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `g_id` int(11) NOT NULL ,
  `thumb` varchar(145) NOT NULL,
  `seq` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
DROP TABLE IF EXISTS `pre_ims_tg_goods_imgs`;
CREATE TABLE `pre_ims_tg_goods_imgs` (
  `id` int(10) NOT NULL AUTO_INCREMENT ,
  `fk_gid` int(10) NOT NULL ,
  `albumpath` varchar(225) NOT NULL ,
  `uniacid` int(10) NOT NULL ,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_gid` (`fk_gid`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM ;
DROP TABLE IF EXISTS `pre_ims_tg_goods_param`;
CREATE TABLE `pre_ims_tg_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM ;
DROP TABLE IF EXISTS `pre_ims_tg_goods_type`;
CREATE TABLE `pre_ims_tg_goods_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cname` varchar(30) NOT NULL ,
  `pid` int(10) DEFAULT NULL ,
  `uniacid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM ;
DROP TABLE IF EXISTS `pre_ims_tg_member`;
CREATE TABLE `pre_ims_tg_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL ,
  `from_user` varchar(50) NOT NULL ,
  `nickname` varchar(20) NOT NULL ,
  `avatar` varchar(255) NOT NULL ,
  `addtime` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
DROP TABLE IF EXISTS `pre_ims_tg_order`;
CREATE TABLE `pre_ims_tg_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `uniacid` varchar(45) NOT NULL ,
  `gnum` int(11) NOT NULL ,
  `openid` varchar(45) NOT NULL ,
  `ptime` varchar(45) NOT NULL ,
  `orderno` varchar(45) NOT NULL ,
  `price` varchar(45) NOT NULL ,
  `status` int(9) NOT NULL,
  `addressid` int(11) NOT NULL ,
  `g_id` int(11) NOT NULL ,
  `tuan_id` int(11) NOT NULL ,
  `is_tuan` int(2) NOT NULL,
  `createtime` varchar(45) NOT NULL ,
  `pay_type` int(4) NOT NULL ,
  `starttime` varchar(45) NOT NULL,
  `endtime` int(45) NOT NULL ,
  `tuan_first` int(11) NOT NULL ,
  `success` int(11) NOT NULL ,
  `express` varchar(50) DEFAULT NULL ,
  `expresssn` varchar(50) DEFAULT NULL ,
  `transid` varchar(50) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `addname` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `address` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
DROP TABLE IF EXISTS `pre_ims_tg_order_goods`;
CREATE TABLE `pre_ims_tg_order_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT ,
  `fk_orderid` int(10) NOT NULL ,
  `fk_goodid` int(10) NOT NULL ,
  `uniacid` int(10) NOT NULL ,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_orderid` (`fk_orderid`),
  UNIQUE KEY `fk_goodid` (`fk_goodid`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM ;
DROP TABLE IF EXISTS `pre_ims_tg_order_print`;
CREATE TABLE `pre_ims_tg_order_print` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `sid` int(10) NOT NULL,
  `pid` int(3) NOT NULL,
  `oid` int(10) NOT NULL,
  `foid` varchar(50) NOT NULL,
  `status` int(3) NOT NULL,
  `addtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;
DROP TABLE IF EXISTS `pre_ims_tg_print`;
CREATE TABLE `pre_ims_tg_print` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `sid` int(10) NOT NULL,
  `name` varchar(45) NOT NULL,
  `print_no` varchar(50) NOT NULL,
  `key` varchar(50) NOT NULL,
  `member_code` varchar(50) NOT NULL,
  `print_nums` int(3) NOT NULL,
  `qrcode_link` varchar(100) NOT NULL,
  `status` int(3) NOT NULL,
  `mode` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;
DROP TABLE IF EXISTS `pre_ims_tg_refund_record`;
CREATE TABLE `pre_ims_tg_refund_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transid` int(11) NOT NULL ,
  `createtime` varchar(45) NOT NULL,
  `status` int(11) NOT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;
DROP TABLE IF EXISTS `pre_ims_tg_rules`;
CREATE TABLE `pre_ims_tg_rules` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rulesname` varchar(40) NOT NULL ,
  `rulesdetail` varchar(4000) DEFAULT NULL ,
  `uniacid` int(10) NOT NULL ,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rulesname` (`rulesname`)
) ENGINE=MyISAM ;
DROP TABLE IF EXISTS `pre_ims_tg_users`;
CREATE TABLE `pre_ims_tg_users` (
  `id` int(10) NOT NULL ,
  `username` varchar(30) NOT NULL,
  `password` varchar(20) NOT NULL ,
  `email` varchar(60) NOT NULL ,
  `tel` varchar(20) NOT NULL ,
  `uniacid` int(10) NOT NULL ,
  `openid` varchar(100) NOT NULL ,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `uniacid` (`uniacid`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM ;
EOF;

runquery($sql);

$finish = true;
?>
