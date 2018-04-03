<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')){
	exit('Access Denied');
}
$table  = C::t('#mandou_seokeyword#mandou_seokeyword');
$manLang = $scriptlang['mandou_seokeyword'];
$Operation = in_array($_GET[Operation], array('Del', 'Add', 'Edit','List','View')) ? $_GET[Operation] : 'List';
$CpMsgUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier='.$plugin['identifier'].'&pmod='.$module['name'];
if($Operation == 'List'){	
	if(submitcheck("del_submit")){
		$table->delete($_POST['delete']);
		cpmsg($manLang['a2'],$CpMsgUrl, 'succeed');
	}

	$keyword = trim(getgpc('keyword'));

	$srchadd = $searchtext = $extra = $srchuid = '';
	$page = max(1, intval($_GET['page']));
	$orderby = '  o.id  desc';
	$where = '';
	

	if($keyword != ''){
		$where .= " and a.seo_title like '%$keyword%'";
		$extra = '&keyword='.$keyword;
	}
	
	
	$extra = '&Operation='.$Operation;
	$perpage = 20;
	
	$count = $table->getCount($where);
	
	$count = $count['num'];
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier='.$plugin['identifier'].'&pmod='.$module['name']);
	showtableheader($manLang['a12'].$count, '', 'style="border:1px solid #F0F7FD"');
	showtablerow('', '', $manLang['a13'].'<input type="text" name="keyword" value="'.$keyword.'" class="txt" style="width:200px;" /> 
		<input type="submit" value="'.$manLang['a14'].'" class="btn" name="searchsubmit"/>');
	showtablefooter();
	showformfooter();
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier='.$plugin['identifier'].'&pmod='.$module['name']);
	showtableheader('', 'nobottom');
	showsubtitle(array('','id',$manLang['a15'],$manLang['a16'],$manLang['seo_title'],$manLang['seo_keyword'],$manLang['seo_decripation']));


	$multipage=multi($count, $perpage, $page, ADMINSCRIPT."?".$CpMsgUrl);

	$datalist = $table->getListPage($where , $page , $perpage);

	foreach($datalist as $value) {
	    if($value[stype] == 1){
	        $value[stype] = '帖子';
	        $value[linkname] = '<a href="forum.php?mod=viewthread&tid='.$value['aid'].'" target="_blank">'.$table->gettitle($value['aid'] , 1).'</a>';
	    }else if($value[stype] == 2){
	        $value[stype] = '文章';
	        $value[linkname] = '<a href="portal.php?mod=view&aid='.$value['aid'].'" target="_blank">'.$table->gettitle($value['aid'] , 2).'</a>';
	    }if($value[stype] == 3){
	        $value[stype] = '群组';
	        $value[linkname] = '<a href="group.php?gid='.$value['aid'].'" target="_blank">'.$table->gettitle($value['aid'] , 3).'</a>';
	    }
		showtablerow('', array('class="td25"', 'class="td28"','', ''), array(
		"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$value[id]]\" value=\"$value[id]\"><input  type=\"hidden\" name=\"auth[]\" value=\"$value[id]\">",
		"{$value[id]}",
		"{$value[stype]}",
		"{$value[linkname]}",
		 $value['seo_title'],
		 $value['seo_keyword'],
		 $value['seo_decripation'],
		));

	}
	showsubmit('del_submit', $manLang['a1'], 'del', '', $multipage.$manLang['a4'].$count, false).$multipage;


	showtablefooter();
	showformfooter();
}
?>