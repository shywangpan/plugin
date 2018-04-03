<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
session_start();
$plign_name = 'fightgroups';

define('HEJIN_PATH', $_G['siteurl'].'source/plugin/'.$plign_name.'/');
define('HEJIN_URL', $_G['siteurl'].'plugin.php?id='.$plign_name);
define('SITE_URL', $_G['siteurl']);
define('HEJIN_ROOT', dirname(__FILE__));
$plugininfo = $_G['cache']['plugin'][$plign_name];

$L = $scriptlang[$plign_name];

$uid = intval($_G['uid']);
$formhash = $_G['formhash'];

if($_G['charset']=='gbk'){
	$charset = 'gb2312';
}
elseif($_G['charset']=='utf-8'){
	$charset = 'UTF-8';
}

if($plugininfo['AppID'] != '' && $plugininfo['AppSecret'] != ''){
	include "source/plugin/fightgroups/class/jssdk.php";
	$jssdk = new JSSDK($plugininfo['AppID'], $plugininfo['AppSecret']);
	$signPackage = $jssdk->GetSignPackage();
}
function alertgo($msg, $url = '', $js = '', $js_run = false) {
	global  $charset;
	$script = '';
	if (!headers_sent ()) {
		header("Content-type: text/html; charset=".$charset); 
	}
	$script .= "<script language='javascript'>" . chr ( 13 );
	if (trim ( $msg ) != "") {
		$script .= "alert('" . $msg . "');" . chr ( 13 );
	}
	if (trim ( $url ) != "") {
		$script .= "location.href='" . $url . "'" . chr ( 13 );
	}
	$script .= "</" . "script>" . chr ( 13 );
	if ($js_run) {
		$script = $js . chr ( 13 ) . $script;
	}
	print $script;
	exit ();
}
function createWebUrl($pmod , $param){
	global $pluginid , $plign_name;
	$ext = '';
	if(!empty($param)){
		foreach ($param as $key => $val){
			$ext .= '&'.$key.'='.$val;
		}
	}
	$url = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier='.$plign_name.'&pmod='.$pmod.$ext ;
	return  $url;
}
function ic($val){
	global $charset;
	if($charset == 'gb2312'){
		return iconv("UTF-8", "GB2312//IGNORE", $val);
	}else{
		return $val;
	}
}
function https_request_($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
function createMobileUrl($pmod = '' , $param = array()){
	global $pluginid , $plign_name;
	if($pmod == '') return $url = 'plugin.php?id='.$plign_name;
	$ext = '';
	if(!empty($param)){
		foreach ($param as $key => $val){
			$ext .= '&'.$key.'='.$val;
		}
	}
	$url = 'plugin.php?id='.$plign_name.'&op='.$pmod.$ext ;
	return  $url;
}

function W( $param = array()){
	global $pluginid , $plign_name,$pmod;
	$ext = '';
	if(!empty($param)){
		foreach ($param as $key => $val){
			$ext .= '&'.$key.'='.$val;
		}
	}
	$url = 'action=plugins&operation=config&do='.$pluginid.'&identifier='.$plign_name.'&pmod='.$pmod.$ext ;
	return  $url;
}

function dump($val){
	echo '<pre>';print_r($val);
}
function upload_($file , $p = ''){
	global $plign_name;
	$exts = array("gif","jpg","jpeg","png");
	$path = 'source/plugin/'.$plign_name.'/upload/'.$p;

	if(!is_dir($path)) mkdir($path);
	$fileupload = "";
	if ($_FILES[$file]['size'] > 0){
		$exname = strtolower(substr($_FILES[$file]['name'],(strrpos($_FILES[$file]['name'],'.')+1)));
		if(!in_array($exname , $exts)){
			return -1;
		}
		$fileupload = $path.'/'.md5(time().microtime_float()).'.'.$exname;
		move_uploaded_file($_FILES[$file]['tmp_name'], $fileupload);
		return $fileupload;
	}
}
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
function tablename($table){
	return  '  '.DB::table('ims_'.$table).'  ';
}
function pdo_fetch($sql){
	return DB::fetch_first($sql);
}
function pdo_fetchcolumn($sql){
	$rs = DB::fetch_first($sql);
	return $rs['sn'];
}
function pdo_insert($table , $data){
	return DB::insert('ims_'.$table , $data);
}
function pdo_delete($table , $data){
	return DB::delete('ims_'.$table , $data);
}
function pdo_update($table , $data , $condition){
	return DB::update('ims_'.$table ,$data , $condition );
}
function pdo_fetchall($sql){
	return DB::fetch_all($sql);
}
function pdo_insertid(){
	return DB::insert_id();
}
?>