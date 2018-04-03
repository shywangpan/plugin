<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')){
	exit('Access Denied');
}
$table  = C::t('#mandou_shop#mandou_shop_cat');
$Operation = in_array($_GET[Operation], array('Del', 'Add', 'Edit','List','View')) ? $_GET[Operation] : 'List';
$CpMsgUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier='.$plugin['identifier'].'&pmod='.$module['name'];

if($Operation == 'List'){
	if(submitcheck("del_submit")){
		$table->delete($_POST['delete']);
		cpmsg('删除成功',$CpMsgUrl, 'succeed');
	}
	$srchadd = $searchtext = $extra = $srchuid = '';
	$page = max(1, intval($_GET['page']));
	$orderby = '  o.id  desc';
	$where = '';
	$extra = '&Operation='.$Operation;
	$perpage = 20;
	$count = $table->getCount($where);
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier='.$plugin['identifier'].'&pmod='.$module['name']);
	$htmlcp = '<a class="addtr" href="'.ADMINSCRIPT.'?'.rawurldecode(cpurl()).'&Operation=Add">新增</a>'.'  总数：'.$count;
	showtableheader($htmlcp, '', 'style="border:1px solid #F0F7FD"');
	showtablefooter();
	showformfooter();
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier='.$plugin['identifier'].'&pmod='.$module['name']);
	showtableheader('', 'nobottom');
	showsubtitle(array('select','id','分类标题','上级','操作'));
    //<tr><th></th></tr>	
	$multipage = multi($count, $perpage, $page, ADMINSCRIPT."?".$CpMsgUrl.$extra);
	$datalist = $table->getListPage($where , $page , $perpage);
	foreach($datalist as $value) {
		$arr = array(
			"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$value[id]]\" value=\"$value[id]\"><input  type=\"hidden\" name=\"auth[]\" value=\"$value[id]\">",
			"{$value[id]}",
			"{$value[cname]}",
			"{$value[pid]}",
			'<a href="'.ADMINSCRIPT.'?'.$CpMsgUrl.'&Operation=Edit&aid='.$value['id'].'">修改</a>',
		);
		showtablerow('', array('class="td25"', 'class="td28"','', ''), $arr);
	}
	$kchtml = $multipage.'总数'.$count;
	showsubmit('del_submit', '删除', 'del', '', $kchtml, false).$multipage;
	showtablefooter();
	showformfooter();
}elseif($Operation == 'Edit' || ($Operation == 'Add')){
	$aid = intval(getgpc('aid'));
	if($Operation == 'Add') {
		$OpClassTitle = '新增';
	}else{
		$OpClassTitle = '修改';
		$rs = $table->getRow($aid);	
	}
	if(!submitcheck('DetailSubmit')) {
		$formUrl = ltrim(rawurldecode(cpurl()),'action=');
		showformheader($formUrl,'enctype');
		showtableheader();
		showtitle($OpClassTitle);
		//
		showsetting('标题', 'cname', $rs['cname'], 'text');//file
		showsetting('上级', 'pid', $rs['pid'], 'select');
		showsetting('seo_title', 'seo_title', $rs['seo_title'], 'textarea');
		showsetting('seo_keyword', 'seo_keyword', $rs['seo_keyword'], 'textarea');
		showsetting('seo_descripation', 'seo_descripation', $rs['seo_descripation'], 'textarea');
		showtablefooter();
		showsubmit('DetailSubmit');
		showformfooter();
	}else{
		$cname = getgpc('cname');
		$pid = intval(getgpc('pid'));
		$seo_title = getgpc('seo_title');
		$seo_keyword= getgpc('seo_keyword');
		$seo_descripation= getgpc('seo_descripation');
		$data = array();
		$data['cname'] = $cname;
		$data['pid'] = $pid;
		$data['seo_title'] = $seo_title;
		$data['seo_keyword'] = $seo_keyword;
		$data['seo_descripation'] = $seo_descripation;
		
		if($cname == ''){
			cpmsg('标题不能为空',$CpMsgUrl.'&Operation='.$Operation.'&aid='.$aid,'error');
		}	
		if($aid > 0){
			$table->update($data , $aid);
		}else{
			$table->insert($data , $aid);
		}
		cpmsg('保存',$CpMsgUrl, 'succeed');
	}
	
}

?>