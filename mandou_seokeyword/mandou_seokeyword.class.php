<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_mandou_seokeyword{
    public function show_setseo($stype , $tid){
        global $scriptlang;
        return '<button type="button" class="pn pnc vm" onclick="showWindow(\'setseo\', \'plugin.php?id=mandou_seokeyword&stype='.$stype.'&tid='.$tid.'\');">'.lang('plugin/mandou_seokeyword', 's2').'</button>';
    }
    public function group_mandou_output() {
        
    }
    public function common(){
        global $_G,$metakeywords,$metadescription;
        $daid = intval(getgpc('gid'));
        if($daid > 0){
            $rs =  C::t('#mandou_seokeyword#mandou_seokeyword')->getRow($daid , 3);
            if($rs){
                $_G['setting']['bbname'] = dhtmlspecialchars(trim($rs['seo_title'] , '-'));
                $metakeywords = dhtmlspecialchars($rs['seo_keyword']);
                $metadescription = dhtmlspecialchars($rs['seo_decripation']);
            }
        }
    }
}
class plugin_mandou_seokeyword_forum extends plugin_mandou_seokeyword{
    public function viewthread_title_extra_output($param) {
        global $_G;
		$config = $_G['cache']['plugin']['mandou_seokeyword'];
		if($_G['uid'] == 0){
		    return '';
		}
		if(!in_array($_G['uid'], explode(',', $config['admin_uid']))){
		    return '';
		}
		$tid = intval(getgpc('tid'));
		return $this->show_setseo(1, $tid);
    }
    public function viewthread_output($param = array()){
        global $_G,$metakeywords,$metadescription;
     
        $daid = intval(getgpc('tid'));
        if($daid > 0){
            $rs =  C::t('#mandou_seokeyword#mandou_seokeyword')->getRow($daid , 1);
            if($rs){
                $_G['setting']['bbname'] = dhtmlspecialchars(trim($rs['seo_title'] , '-'));
                $metakeywords = dhtmlspecialchars($rs['seo_keyword']);
                $metadescription = dhtmlspecialchars($rs['seo_decripation']);
            }
        }
        return $param;
    }
}
class plugin_mandou_seokeyword_portal extends plugin_mandou_seokeyword{
    public function view_article_top_output(){
        global $_G;
        $config = $_G['cache']['plugin']['mandou_seokeyword'];
        if($_G['uid'] == 0){
            return '';
        }
        if(!in_array($_G['uid'], explode(',', $config['admin_uid']))){
            return '';
        }
        $tid = intval(getgpc('aid'));
        return $this->show_setseo(2, $tid);
    }
    public function view_output(){
        global $_G,$metakeywords,$metadescription;
        $daid = intval(getgpc('aid'));
        if($daid > 0){
           $rs =  C::t('#mandou_seokeyword#mandou_seokeyword')->getRow($daid , 2);
           if($rs){
               $_G['setting']['bbname'] = dhtmlspecialchars(trim($rs['seo_title'] , '-'));
               $metakeywords = dhtmlspecialchars($rs['seo_keyword']);
               $metadescription = dhtmlspecialchars($rs['seo_decripation']);
           }
        }
   }
}
class plugin_mandou_seokeyword_group extends plugin_mandou_seokeyword{
    public function  index_top_output($param) {
        global $_G;
        $config = $_G['cache']['plugin']['mandou_seokeyword'];
        if($_G['uid'] == 0){
            return '';
        }
        if(!in_array($_G['uid'], explode(',', $config['admin_uid']))){
            return '';
        }
        $tid = intval(getgpc('gid'));
        return $this->show_setseo(3, $tid);
    }
    
}
?>