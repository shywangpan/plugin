<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/fightgroups/config.inc.php';
if (!$_G['adminid']) {
	return false;
}
$operation = isset($_GET['act'])? addslashes($_GET['act']):'display';
$pmod = 'member';
$tab = C::t('#'.$plign_name.'#ims_tg_member');
$id = intval($_GET['sid']);
if ($operation == 'display') {
	$pagesize = 15;
	$page = intval($_GET['page'])>0? intval($_GET['page']):1;
	$start = ($page-1)*$pagesize;
	$condition = " ";
	$count = $tab->getCount($condition);
	$members = $tab->getList($condition," ORDER BY id DESC ",$start,$pagesize);
	if(!empty($members)){
		foreach ($members as $key => $val){
			$order = pdo_fetchall("SELECT * FROM ".tablename('tg_order')." WHERE openid='{$member['from_user']}'");
			$arr  = array();
			foreach ($order as $k => $v) {
				$arr['addressid'] = $v['addressid'];
			}
			$address = array();
			if($arr['addressid'] > 0){
				$address = pdo_fetch("SELECT * FROM ".tablename('tg_address')." WHERE id=".$v['addressid']);
				$members[$key]['address'] = $address;
			}
		}
	}
	$multi = multi($count, $pagesize, $page, W());	
	include_once template($plign_name.':'.$pmod);
}elseif($operation == 'delete' && $_GET['formhash'] == FORMHASH){
	$tab->delete_($id , 'id');
	cpmsg($L['a2'], W(), 'succeed');
}


?>
