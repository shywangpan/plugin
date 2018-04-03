<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$id = intval($_GET['gid']);
$tuan_id = intval($_GET['tuan_id']);
$tab = C::t('#'.$plign_name.'#ims_tg_goods');
if(!empty($id)){
	
	$goods = $tab->getRow($id);

	$advs = $listt = C::t('#'.$plign_name.'#ims_tg_goods_atlas')->getList(' and g_id='.$id);;
	$collect = DB::fetch_first("select * from " . DB::table('ims_tg_collect') . " where  openid = '{$_W['openid']}' and sid = '{$id}'");

	foreach ($advs as &$adv) {
		if (substr($adv['link'], 0, 5) != 'http:') {
			$adv['link'] = "http://" . $adv['link'];
		}
	}
	unset($adv);
	if(empty($goods)){
		exit();
	}

	

		$sql  = "select * from ".DB::table('ims_tg_order')." where g_id=$id and is_tuan=1 and status = 1 and tuan_first =1 and success = 1";
		$tuan_ids = DB::fetch_all($sql);
		
		if (!empty($tuan_ids)) {
			foreach ($tuan_ids as $key => $value) {
				$pttime = $value['starttime'] + 60*60*$value['endtime'];
				if ($pttime <= TIMESTAMP) {
					unset($tuan_ids[$key]);
				}else{
					$profile = DB::fetch_first("SELECT * FROM " . DB::table('ims_tg_member') . " WHERE 1 and from_user = '{$value['openid']}'");
					$tuan_ids[$key]['nickname'] = $profile['nickname'];
					$tuan_ids[$key]['avatar'] = $profile['avatar'];
					$pnumber = DB::fetch_first("SELECT COUNT(*) as sn FROM ".DB::table('ims_tg_order')." t where t.tuan_id = '{$value['tuan_id']}' and t.status = 1 ");
					$pnumber = $pnumber['sn'];
					$tuan_ids[$key]['snumber'] = $goods['groupnum'] - $pnumber;
				}
			}
		}
		
}
include_once template($plign_name.':index/gooddetails');
?>