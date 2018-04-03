<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$action = addslashes(getgpc("action"));
$action = $action ? $action : 'show';
$table = C::t('#mandou_seokeyword#mandou_seokeyword');
$stype = intval(getgpc('stype'));
$tid = intval(getgpc('tid'));
if($action == 'show'){
    if($tid > 0){
        $result = $table->getRow($tid , $stype);
        $url = 'plugin.php?id=mandou_seokeyword&action=save&stype='.$stype.'&tid='.$tid;
        include template('common/header_ajax');
        include template("mandou_seokeyword:seo");
        echo $return;
        include template('common/footer_ajax');
    }
}elseif ($action == 'save'){
    $result = $table->getRow($tid , $stype);
    if(submitcheck('seosubmit')){
        $seo_title = addslashes(getgpc('seo_title'));
        $seo_keyword = addslashes(getgpc('seo_keyword'));
        $seo_decripation = addslashes(getgpc('seo_decripation'));
        if($result){
            $table->update(array('seo_title' => $seo_title,'seo_keyword' => $seo_keyword , 'seo_decripation' => $seo_decripation) , $result['id']);
        }else {
            $table->insert(array('seo_title' => $seo_title,'seo_keyword' => $seo_keyword , 'seo_decripation' => $seo_decripation,'aid'=>$tid , 'stype' => $stype));
        }
        $url = '';
        if($stype == 1){
            $url = 'forum.php?mod=viewthread&tid='.$tid;
        }else if ($stype == 2){
            $url = 'portal.php?mod=view&aid='.$tid;
        }else if ($stype == 3){
            $url = 'group.php?gid='.$tid;
        }
        showmessage(lang('plugin/mandou_seokeyword', 's1'),$url);
    }
}
?>