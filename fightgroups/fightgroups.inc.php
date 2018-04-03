<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once DISCUZ_ROOT.'./source/plugin/fightgroups/config.inc.php';


$operation = isset($_GET['op'])? addslashes($_GET['op']):'display';
if ($operation=='display') {
	$code = $_GET['code'];
	if($_SESSION['wx']['openid'] == '' ){
		if( $plugininfo['AppSecret'] && $code != ''){
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$plugininfo['AppID']."&secret=".$plugininfo['AppSecret']."&code=".$code."&grant_type=authorization_code";
			$result = https_request_($url);
			$jsoninfo = json_decode($result, true);
			$access_token = $jsoninfo["access_token"];
			$_SESSION['access_token'] = $access_token;
			$openid = $jsoninfo["openid"];
			$_SESSION['openid'] = $openid;
			$url="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";
			$wxuserinfo = https_request_($url);
			$wxuserinfo = json_decode($wxuserinfo, true);
			if($_G['charset']=='gbk'){
				$wxuserinfo['nickname'] = iconv("UTF-8", "GB2312//IGNORE", $wxuserinfo['nickname']);
			}
			$user = DB::fetch_first("select * from ".DB::table('ims_tg_member')." where from_user='$openid'");
			$wxuserinfo['openid'] = $openid;
			if(empty($user)){
				$weinxi_user = array(
					'from_user' => $wxuserinfo['openid'],
					'nickname' => $wxuserinfo['nickname'],
					'avatar' => $wxuserinfo['headimgurl'],
					'addtime' => date('Y-m-d H:i:s'),
				);
				DB::Insert('ims_tg_member' , $weinxi_user);
				
				$data['nickname'] = $weinxi_user['nickname'];
				$data['avatar'] = $weinxi_user['headimgurl'];
			}
			$_SESSION['wx'] = array(
				'openid' => $wxuserinfo['openid'],
				'nickname' => $wxuserinfo['nickname'],
			);
			$_W = $_SESSION['wx'];
			$_W['token'] = FORMHASH;
		}else{
			$REH = HEJIN_URL;
			$code = $_GET['code'];
			$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$plugininfo['AppID']."&redirect_uri=".$REH."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			header('Location: '.$url, true, 301);
		}
	}
	$slideNum =1;
	$category = C::t('#'.$plign_name.'#ims_tg_category')->getList(' and enabled=1 ' , ' ORDER BY parentid ASC, displayorder DESC ' , 0 , 10000);
	foreach ($category as $key => $value) {
		if (!empty($value['description'])) {
			$pindex = max(1, intval($_GET['page'])); 
			$psize = intval(100);	
			$sqlmess = " LIMIT 0".','.$psize;
		}
		$category[$key]['goodses'] = DB::fetch_all("SELECT * FROM ".DB::table('ims_tg_goods')." WHERE   isshow = 1 AND fk_typeid = '{$value['id']}' ORDER BY displayorder DESC, id desc".$sqlmess);
	}

	$advs = DB::fetch_all("select * from " . DB::table('ims_tg_adv') . " where enabled=1 ");
	foreach ($advs as $key => $adv) {
		if (substr($adv['link'], 0, 5) != 'http:') {
			$advs[$key]['link'] = "http://" . $adv['link'];
			$advs[$key]['seq'] = $key+1;
		}
	}
	unset($adv);
	include_once template($plign_name.':index/index');
}else if ($operation=='search') {
	$condition = '';
	if (!empty($_GET['gid'])) {
		$cid = intval($_GET['gid']);
		$condition .= " AND fk_typeid = '{$cid}'";
	}
	$_GET['keyword'] = addslashes($_GET['keyword']);
	if (!empty($_GET['keyword'])) {
		
		$condition .= " AND gname LIKE '%{$_GET['keyword']}%'";
	}
	$goodses = DB::fetch_all("SELECT * FROM ".DB::table('ims_tg_goods')." WHERE 1 AND isshow = 1 $condition ");

	include_once template($plign_name.':index/index');
}else if ($operation=='index'){
	header('location:'.'plugin.php?id='.$plign_name);
}
else{
	include('source/plugin/fightgroups/module/'.$operation.'.php');
}
$_W['token'] = FORMHASH;



?>