<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
runquery("DROP TABLE IF EXISTS `pre_ims_tg_address`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_adv`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_category`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_collect`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_goods`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_goods_atlas`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_goods_imgs`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_goods_param`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_goods_type`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_member`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_order`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_order_goods`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_order_print`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_print`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_refund_record`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_rules`");
runquery("DROP TABLE IF EXISTS `pre_ims_tg_users`");
$finish = true;
?>