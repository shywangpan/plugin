<?php
if(!defined('IN_DISCUZ') && !$_G['adminid']) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/fightgroups/config.inc.php';
$operation = isset($_GET['act'])? addslashes($_GET['act']):'display';
$pmod = $_GET['pmod'];
$userInfo = array();
$tab = C::t('#'.$plign_name.'#ims_tg_category');
$id = intval($_GET['sid']);
if ($operation == 'display') {
	if (!empty($_GET['displayorder'])) {
		foreach ($_GET['displayorder'] as $id => $displayorder) {
			$tab->update_(array('displayorder' => $displayorder) , array('id' => $id));
		}
		cpmsg($L['a1'].'' , W() , 'success');
	}
	$children = array();
	$category = $tab->getList(' ' , ' ORDER BY parentid ASC, displayorder DESC ' , 0 , 10000);
	if(!empty($category)){
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][] = $row;
				unset($category[$index]);
			}
		}
	}
	include_once template($plign_name.':category');
}elseif($operation == 'delete' && $_GET['formhash'] == FORMHASH){
	$tab->delete_($id , 'id');
	$tab->delete_($id , 'parentid');
	cpmsg($L['a2'].'', W(), 'succeed');
}elseif ($operation == 'post'){
	$parentid = intval($_GET['parentid']);
	$id = intval($_GET['sid']);
	if (!empty($id)) {
		$category = $tab->getRow($id);
	} else {
		$category = array(
			'displayorder' => 0,
			'isrecommand' => 1,
			'enabled' => 1,
		);
	}
	if (!empty($parentid)) {
		$parent = $tab->getRow($parentid);
		if (empty($parent)) {
			cpmsg($L['c1'].'', W(array('act' => 'post' , 'sid' => $id), 'error'));
		}
	}
	if(submitcheck('submit')){
		if (empty($_GET['catename'])) {
			cpmsg($L['c2'].'', W(array('act' => 'post' , 'sid' => $id)), 'error');
		}
		$fileupload = "";
		if ($_FILES['fileupload']['size'] > 0){
			$fileupload = upload_('fileupload' , 'adv');
			if($fileupload == -1){
				cpmsg($L['a3'].'', W(array('act' => 'post' , 'sid' => $id)), 'error');
			}
		}	
	
		$data = array(
			'name' => addslashes($_GET['catename']),
			'enabled' => intval($_GET['enabled']),
			'displayorder' => intval($_GET['displayorder']),
			'isrecommand' => intval($_GET['isrecommand']),
			'description' => addslashes($_GET['description']),
			'parentid' => intval($parentid),
			
		);
		if($fileupload != ''){
			$data['thumb'] = $fileupload;
		}
		if (!empty($id)) {
			unset($data['parentid']);
			$tab->update_($data, array('id' => $id));
		} else {
			$tab->insert_($data);
		}
		cpmsg($L['c3'].'', W(), 'success');
	}
	include_once template($plign_name.':category');
}

?>