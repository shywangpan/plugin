<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function tomreplaceMap(){
    $r = array(
        'code'      => 0,
        'search'    => array(),
        'replace'   => array(),
    );
    
    $tom_link_rule = include DISCUZ_ROOT."./source/plugin/tom_link/data/rule.php";
    
    if(isset($tom_link_rule['tom_ucenter'])){
        $r['code']      = 1;
        $r['search'][]  = 'plugin.php?id=tom_ucenter';
        $r['replace'][] = $tom_link_rule['tom_ucenter']['rk']."?id=".$tom_link_rule['tom_ucenter']['bs'];
    }
    if(isset($tom_link_rule['tom_tongcheng'])){
        $r['code']      = 1;
        $r['search'][]  = 'plugin.php?id=tom_tongcheng';
        $r['replace'][] = $tom_link_rule['tom_tongcheng']['rk']."?id=".$tom_link_rule['tom_tongcheng']['bs'];
    }
    if(isset($tom_link_rule['tom_tcshop'])){
        $r['code']      = 1;
        $r['search'][]  = 'plugin.php?id=tom_tcshop';
        $r['replace'][] = $tom_link_rule['tom_tcshop']['rk']."?id=".$tom_link_rule['tom_tcshop']['bs'];
    }
    if(isset($tom_link_rule['tom_tchongbao'])){
        $r['code']      = 1;
        $r['search'][]  = 'plugin.php?id=tom_tchongbao';
        $r['replace'][] = $tom_link_rule['tom_tchongbao']['rk']."?id=".$tom_link_rule['tom_tchongbao']['bs'];
    }
    if(isset($tom_link_rule['tom_tcqianggou'])){
        $r['code']      = 1;
        $r['search'][]  = 'plugin.php?id=tom_tcqianggou';
        $r['replace'][] = $tom_link_rule['tom_tcqianggou']['rk']."?id=".$tom_link_rule['tom_tcqianggou']['bs'];
    }
    if(isset($tom_link_rule['tom_tcptuan'])){
        $r['code']      = 1;
        $r['search'][]  = 'plugin.php?id=tom_tcptuan';
        $r['replace'][] = $tom_link_rule['tom_tcptuan']['rk']."?id=".$tom_link_rule['tom_tcptuan']['bs'];
    }
    if(isset($tom_link_rule['tom_tckjia'])){
        $r['code']      = 1;
        $r['search'][]  = 'plugin.php?id=tom_tckjia';
        $r['replace'][] = $tom_link_rule['tom_tckjia']['rk']."?id=".$tom_link_rule['tom_tckjia']['bs'];
    }
    if(isset($tom_link_rule['tom_tchehuoren'])){
        $r['code']      = 1;
        $r['search'][]  = 'plugin.php?id=tom_tchehuoren';
        $r['replace'][] = $tom_link_rule['tom_tchehuoren']['rk']."?id=".$tom_link_rule['tom_tchehuoren']['bs'];
    }
    
    return $r;
}

function tomoutput(){
    global $_G;
    
    $INminiprogram = false;
    $cookie_tom_miniprogram = getcookie('tom_miniprogram');
    if($cookie_tom_miniprogram == 1 || $_GET['f'] == 'miniprogram'){
        $INminiprogram = true;
    }
    
    $no_link_rule = 0;
    if(file_exists(DISCUZ_ROOT."./source/plugin/tom_link/data/rule.php")){
        $r = tomreplaceMap();
        if($r['code'] == 1){
            $content = ob_get_contents();
            if($INminiprogram){
                $content = preg_replace('#\s+href="(plugin.php?[^"]*)"#', ' data-href="'.$_G['siteurl'].'\\1" onclick="jumpMiniprogram(\''.$_G['siteurl'].'\\1&f=miniprogram\');"', $content);
                $content = str_replace('res.wx.qq.com/open/js/jweixin-1.0.0.js', 'res.wx.qq.com/open/js/jweixin-1.3.2.js', $content);
                $content = str_replace('res.wx.qq.com/open/js/jweixin-1.2.0.js', 'res.wx.qq.com/open/js/jweixin-1.3.2.js', $content);
            }
            $content = str_replace($r['search'], $r['replace'], $content);
            ob_end_clean();
            $_G['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();
            echo $content;
            echo '<script src="source/plugin/tom_tongcheng/images/miniprogram.js?v=20180227"></script>';
        }else{
            $no_link_rule = 1;
        }
    }else{
        $no_link_rule = 1;
    }
    
    if($no_link_rule == 1 && $INminiprogram){
        $content = ob_get_contents();
        $content = preg_replace('#\s+href="(plugin.php?[^"]*)"#', ' data-href="'.$_G['siteurl'].'\\1" onclick="jumpMiniprogram(\''.$_G['siteurl'].'\\1&f=miniprogram\');"', $content);
        $content = str_replace('res.wx.qq.com/open/js/jweixin-1.0.0.js', 'res.wx.qq.com/open/js/jweixin-1.3.2.js', $content);
        ob_end_clean();
        $_G['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();
        echo $content;
        echo '<script src="source/plugin/tom_tongcheng/images/miniprogram.js"></script>';
    }
    
    exit;
}

function tomheader($string){
    if(file_exists(DISCUZ_ROOT."./source/plugin/tom_link/data/rule.php")){
        $r = tomreplaceMap();
        if($r['code'] == 1){
            $string = str_replace($r['search'], $r['replace'], $string);
        }
    }
    dheader($string);
    exit;
}

function tom_link_replace($string){
    global $_G;
    
    $INminiprogram = false;
    $cookie_tom_miniprogram = getcookie('tom_miniprogram');
    if($cookie_tom_miniprogram == 1 || $_GET['f'] == 'miniprogram'){
        $INminiprogram = true;
    }
    
    $no_link_rule = 0;
    if(file_exists(DISCUZ_ROOT."./source/plugin/tom_link/data/rule.php")){
        $r = tomreplaceMap();
        if($r['code'] == 1){
            if($INminiprogram){
                $string = preg_replace('#\s+href="(plugin.php?[^"]*)"#', ' data-href="'.$_G['siteurl'].'\\1" onclick="jumpMiniprogram(\''.$_G['siteurl'].'\\1&f=miniprogram\');"', $string);
            }
            $string = str_replace($r['search'], $r['replace'], $string);
        }else{
            $no_link_rule = 1;
        }
    }else{
        $no_link_rule = 1;
    }
    
    if($no_link_rule == 1 && $INminiprogram){
        $string = preg_replace('#\s+href="(plugin.php?[^"]*)"#', ' data-href="'.$_G['siteurl'].'\\1" onclick="jumpMiniprogram(\''.$_G['siteurl'].'\\1&f=miniprogram\');"', $string);
    }
    return $string;
}

