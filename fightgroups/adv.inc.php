<?php
if(!defined('IN_DISCUZ') && !$_G['adminid']) {
	exit('Access Denied');
}

require_once DISCUZ_ROOT.'./source/plugin/fightgroups/config.inc.php';
$operation = isset($_GET['act'])? addslashes($_GET['act']):'display';
$pmod = 'adv';
$userInfo = array();
$tab = C::t('#'.$plign_name.'#ims_tg_adv');
$id = intval($_GET['sid']);
if ($operation == 'display') {
	
	$list = $tab->getList(' ' , ' ORDER BY displayorder DESC ' , 0 , 10000);
	
	include_once template($plign_name.':adv');
}elseif($operation == 'delete' && $_GET['formhash'] == FORMHASH){
	$tab->delete_($id , 'id');
	cpmsg($L['a2'].'', W(), 'succeed');
}elseif ($operation == 'post'){
	$id = intval($_GET['sid']);
	if (!empty($id)) {
		$adv = $tab->getRow($id);
	} else {
		$adv = array(
			'displayorder' => 0,
			'enabled' => 1,
		);
	}
	if(submitcheck('submit')){
		$advname = addslashes($_GET['advname']);
		if (empty($advname)) {
			cpmsg($L['a3'].'', W(array('act' => 'post' , 'sid' => $id)), 'error');
		}
		$fileupload = "";
		if ($_FILES['fileupload']['size'] > 0){
			$fileupload = upload_('fileupload' , 'adv');
			if($fileupload == -1){
				cpmsg($L['a3'].'', W(array('act' => 'post' , 'sid' => $id)), 'error');
			}
		}	
		$data = array(
			'advname' => $advname,
			'link' => addslashes($_GET['link']),
			'enabled' => intval($_GET['enabled']),
			'displayorder' => intval($_GET['displayorder']),
		);
		if($fileupload != ''){
			$data['thumb'] = $fileupload;
		}
		if (!empty($id)) {
			$tab->update_($data, array('id' => $id));
		} else {
			$tab->insert_($data);
		}
		cpmsg($L['a4'].'', W(), 'success');
	}
	include_once template($plign_name.':adv');
}

?>