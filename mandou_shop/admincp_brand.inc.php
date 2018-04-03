<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')){
	exit('Access Denied');
}
$table  = C::t('#mandou_shop#mandou_shop_brand');
//$manLang = $scriptlang['mamdou_saoma'];
//照抄 start
$Operation = in_array($_GET[Operation], array('Del', 'Add', 'Edit','List','View')) ? $_GET[Operation] : 'List';
$CpMsgUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier='.$plugin['identifier'].'&pmod='.$module['name'];
//照抄 end
if($Operation == 'List'){
	if(submitcheck("del_submit")){
	    foreach(array_keys($_POST['delete']) as $v){
        	$r = $table->getRow(intval($v));
        	@unlink($r['logo']);
        }
		$table->delete($_POST['delete']);
		cpmsg($_POST['delete'],$CpMsgUrl, 'succeed');
	}
	////照抄 start
	$srchadd = $searchtext = $extra = $srchuid = '';
	
	$page = max(1, intval($_GET['page']));
	//照抄 end
	
	$orderby = '  o.id  desc';
	//照抄 start
	$where = '';
	$extra = '&Operation='.$Operation;
	$perpage = 20;
	//照抄 end
	$count = $table->getCount($where);
	//照抄 start
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier='.$plugin['identifier'].'&pmod='.$module['name']);
	$htmlcp = '<a class="addtr" href="'.ADMINSCRIPT.'?'.rawurldecode(cpurl()).'&Operation=Add">新增</a>'.'  总数：'.$count;
	showtableheader($htmlcp, '', 'style="border:1px solid #F0F7FD"');
	showtablefooter();
	showformfooter();
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier='.$plugin['identifier'].'&pmod='.$module['name']);
	showtableheader('', 'nobottom');
	//照抄 end
	showsubtitle(array('select','id','品牌','图片','操作'));

	/**
	 * 
	 * 
	 * @example  multi($count, $perpage, $page, ADMINSCRIPT."?".$CpMsgUrl);
	 * @method   分页函数
	 * @var $count                      数据库当前总数
	 * @var $perpage                    每页的个数
	 * @var $page                       第几页
	 *      ADMINSCRIPT."?".$CpMsgUrl   当前url  
	 */
	$multipage = multi($count, $perpage, $page, ADMINSCRIPT."?".$CpMsgUrl.$extra);
	
	$datalist = $table->getListPage($where , $page , $perpage);
	/**
	 * showtablerow      只管第三个参数  
	 */
	foreach($datalist as $value) {
		$arr = array(
			"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$value[id]]\" value=\"$value[id]\"><input  type=\"hidden\" name=\"auth[]\" value=\"$value[id]\">",
			"{$value[id]}",
			"{$value[bname]}", 
		    '<img src="'.$value[logo].'" alt="" width="60px" height="60px">',
			'<a href="'.ADMINSCRIPT.'?'.$CpMsgUrl.'&Operation=Edit&aid='.$value['id'].'">修改</a>',
		);
		showtablerow('', array('class="td25"', 'class="td28"','', ''), $arr);

	}
	/**
	 * showsubmit  
	 */
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
		//form action
		$formUrl = ltrim(rawurldecode(cpurl()),'action=');
		showformheader($formUrl,'enctype');
		showtableheader();
		showtitle($OpClassTitle);
		//
		showsetting('品牌 名', 'bname', $rs['bname'], 'text');//file
		showsetting('图片', 'logo', $rs['logo'], 'file');
		showsetting('seo_title', 'seo_title', $rs['seo_title'], 'textarea');
		showsetting('seo_keyword', 'seo_keyword', $rs['seo_keyword'], 'textarea');
		showsetting('seo_descripation', 'seo_descripation', $rs['seo_descripation'], 'textarea');
		showtablefooter();
		showsubmit('DetailSubmit');
		showformfooter();
	}else{
		include './source/plugin/mandou_shop/function/function.shop.php';
		//echo '<pre>';print_r($_FILES);exit();
		$bname = getgpc('bname');
		//$logo= intval(getgpc('logo'));
		$path = 'source/plugin/mandou_shop/static/brand';
		if(!is_dir($path)) mkdir($path);
		$logo = "";
		if ($_FILES['logo']['size'] > 0){
			$logo = shop_upload('logo' , $path);
		}
		$seo_title = getgpc('seo_title');
		$seo_keyword= getgpc('seo_keyword');
		$seo_descripation= getgpc('seo_descripation');
		$data = array();
		$data['bname'] = $bname;
		$data['logo'] = $logo;
		$data['seo_title'] = $seo_title;
		$data['seo_keyword'] = $seo_keyword;
		$data['seo_descripation'] = $seo_descripation;
		
		if($bname == ''){
			cpmsg('标题不能为空',$CpMsgUrl.'&Operation='.$Operation.'&aid='.$aid,'error');
		}	
		if($aid > 0){
			$r = $table->getRow($aid);
			@unlink($r['logo']);
			$table->update($data , $aid);
		}else{
			$table->insert($data , $aid);
		}
		cpmsg('保存',$CpMsgUrl, 'succeed');
	}
	
}

?>