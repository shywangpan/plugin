<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$site_id = intval($_GET['site'])>0? intval($_GET['site']):1;

session_start();
loaducenter();
$formhash = FORMHASH;
$tongchengConfig = $_G['cache']['plugin']['tom_tongcheng'];
$tomSysOffset = getglobal('setting/timeoffset');
$nowYear = dgmdate($_G['timestamp'], 'Y',$tomSysOffset);
$nowDayTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),dgmdate($_G['timestamp'], 'j',$tomSysOffset),dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;
$appid = trim($tongchengConfig['wxpay_appid']);  
$appsecret = trim($tongchengConfig['wxpay_appsecret']);

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/login_ajax.php';

## tcshop start
$__ShowTcshop = 0;
if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_tcshop/tom_tcshop.inc.php') && $tongchengConfig['open_tcshop'] == 1){
    $__ShowTcshop = 1;
}
## tcshop end
## tchongbao start
$__ShowTchongbao = 0;
if(file_exists(DISCUZ_ROOT.'./source/plugin/tom_tchongbao/tom_tchongbao.inc.php')){
    if($tongchengConfig['open_tchongbao'] == 1){
        $__ShowTchongbao = 1;
    }
}
## tchongbao end

include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/function.core.php';
include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/qqface.php';

if($_GET['act'] == 'list' && $_GET['formhash'] == FORMHASH){
    
    $outStr = '';
    
    $tcshop_id  = intval($_GET['tcshop_id'])>0? intval($_GET['tcshop_id']):0;
    $model_id   = intval($_GET['model_id'])>0? intval($_GET['model_id']):0;
    $model_ids  = isset($_GET['model_ids'])? daddslashes($_GET['model_ids']):'';
    $type_id  = intval($_GET['type_id'])>0? intval($_GET['type_id']):0;
    $cate_id  = intval($_GET['cate_id'])>0? intval($_GET['cate_id']):0;
    $user_id  = intval($_GET['user_id'])>0? intval($_GET['user_id']):0;
    $city_id  = intval($_GET['city_id'])>0? intval($_GET['city_id']):0;
    $area_id  = intval($_GET['area_id'])>0? intval($_GET['area_id']):0;
    $street_id  = intval($_GET['street_id'])>0? intval($_GET['street_id']):0;
    $keyword = isset($_GET['keyword'])? daddslashes(diconv(urldecode($_GET['keyword']),'utf-8')):'';
    $page  = intval($_GET['page'])>0? intval($_GET['page']):1;
    $pagesize  = intval($_GET['pagesize'])>0? intval($_GET['pagesize']):6;
    $ordertype  = !empty($_GET['ordertype'])? addslashes($_GET['ordertype']):'new';
    
    $oneCityInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_level1_name($tongchengConfig['city_name']);
    $city_id_tmp = 0;
    if($site_id > 1){
        $sitesInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($site_id);
        $city_id_tmp = $sitesInfoTmp['city_id'];
    }else{
        $city_id_tmp = $oneCityInfoTmp['id'];
    }
    $citySitesArr = array();
    if($city_id_tmp>0){
        $citySitesListTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_all_list(" AND city_id={$city_id_tmp} AND status=1 "," ORDER BY id DESC ",0,1000);
        if(is_array($citySitesListTmp) && !empty($citySitesListTmp)){
            foreach ($citySitesListTmp as $key => $value){
                $citySitesArr[] = $value['id'];
            }
        }
    }
    if(is_array($citySitesArr) && !empty($citySitesArr)){
        if($oneCityInfoTmp['id'] == $city_id_tmp){
            $citySitesArr[] = 1;
        }
    }else{
        $citySitesArr = array($site_id);
    }
    $citySitesStr = implode(',', $citySitesArr);
    

    $whereStr = ' AND status=1  ';
    if(($_REQUEST['index'] > 0)){
    	$whereStr .= ' and topstatus = 1';
    	$whereStr .= " and toptime > ".time();
    }
    

    if(!empty($citySitesStr)){
        $whereStr.= " AND site_id IN({$citySitesStr}) ";
    }
    if(!empty($tcshop_id)){
        $whereStr.= " AND tcshop_id={$tcshop_id} ";
    }
    if(!empty($model_id)){
        $whereStr.= " AND model_id={$model_id} ";
    }

    if(!empty($model_ids)){
        $model_ids_arr = explode('|', $model_ids);
        $modelIdsArr = array();
        if(is_array($model_ids_arr) && !empty($model_ids_arr)){
            foreach ($model_ids_arr as $key => $value){
                $value = (int)$value;
                if(!empty($value)){
                    $modelIdsArr[] = $value;
                }
            }
        }
        if(!empty($modelIdsArr)){
            $whereStr.= " AND model_id in(".  implode(',', $modelIdsArr).") ";
        }
    }
    if(!empty($type_id)){
        $whereStr.= " AND type_id={$type_id} ";
    }
    if(!empty($cate_id)){
        $whereStr.= " AND cate_id={$cate_id} ";
    }
    if(!empty($user_id)){
        $whereStr.= " AND user_id={$user_id} ";
    }
    if(!empty($city_id)){
        $whereStr.= " AND city_id={$city_id} ";
    }
    if(!empty($area_id)){
        $whereStr.= " AND area_id={$area_id} ";
    }
    if(!empty($street_id)){
        $whereStr.= " AND street_id={$street_id} ";
    }

    $orderStr = " ORDER BY topstatus DESC,refresh_time DESC";
	//echo $whereStr;exit();
    $pagesize = $pagesize;
    $start = ($page - 1)*$pagesize;
    $tongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list($whereStr,$orderStr,$start,$pagesize,$keyword);
    
    $tongchengList = array();
    foreach ($tongchengListTmp as $key => $value) {
        
//        if($value['topstatus'] == 1 && (TIMESTAMP - $value['refresh_time']) > 1800){
//            $toprand = mt_rand(111, 999);
//            DB::query("UPDATE ".DB::table('tom_tongcheng')." SET toprand=".$toprand." WHERE id='{$value['id']}' ", 'UNBUFFERED');
//        }
        
        $typeInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($value['type_id']);
        if($value['topstatus'] == 0 && $typeInfoTmp['over_time_attr_id'] > 0 && $typeInfoTmp['over_time_do'] > 0){
            $tongchengAttrInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND tongcheng_id={$value['id']} AND attr_id={$typeInfoTmp['over_time_attr_id']} "," ORDER BY id DESC ",0,1);
            if(is_array($tongchengAttrInfoTmp) && !empty($tongchengAttrInfoTmp) && $tongchengAttrInfoTmp[0] && $tongchengAttrInfoTmp[0]['time_value'] > 0){
                if($tongchengAttrInfoTmp[0]['time_value'] < TIMESTAMP){
                    if($typeInfoTmp['over_time_do'] == 1){
                        DB::query("UPDATE ".DB::table('tom_tongcheng')." SET status=2 WHERE id='{$value['id']}' ", 'UNBUFFERED');
                        continue;
                    }else if($typeInfoTmp['over_time_do'] == 2){
                        $value['finish'] = 1;
                        DB::query("UPDATE ".DB::table('tom_tongcheng')." SET finish=1 WHERE id='{$value['id']}' ", 'UNBUFFERED');
                    }
                }
            }
        }
        
        if($value['topstatus'] == 0 && $tongchengConfig['over_time_limit'] > 0 && $value['finish'] == 0){
            if(($value['refresh_time']+$tongchengConfig['over_time_limit']*86400) < TIMESTAMP){
                DB::query("UPDATE ".DB::table('tom_tongcheng')." SET finish=1 WHERE id='{$value['id']}' ", 'UNBUFFERED');
                $value['finish'] = 1;
            }
        }
        
        $tongchengList[$key] = $value;
        $tongchengList[$key]['content'] = contentFormat($value['content']);
        
        $userInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']); 
        $siteInfoTmp = array('id'=>1,'name'=>$tongchengConfig['plugin_name']);
        if($value['site_id'] > 1){
            $siteInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($value['site_id']);
        }
        
        $cateInfoTmp = array();
        if($value['cate_id'] > 0){
            $cateInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_by_id($value['cate_id']);
        }
        
        $tongchengList[$key]['address'] = '';
        $areaNameTmp = '';
        if(!empty($value['area_id'])){
            $areaInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($value['area_id']);
            $areaNameTmp = $areaInfoTmp['name'];
        }
        $streetNameTmp = '';
        if(!empty($value['street_id'])){
            $streetInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_by_id($value['street_id']);
            $streetNameTmp = $streetInfoTmp['name'];
        }
        if(!empty($areaNameTmp) && !empty($streetNameTmp)){
            $tongchengList[$key]['address'] = $areaNameTmp." ".$streetNameTmp;
        }
        
        $tchongbaoInfo = array();
        if($__ShowTchongbao == 1){
            $tchongbaoInfoTmp = C::t('#tom_tchongbao#tom_tchongbao')->fetch_all_list(" AND tongcheng_id = {$value['id']} AND pay_status = 2 AND only_show = 1 ", 'ORDER BY add_time DESC,id DESC', 0, 1);
            if(is_array($tchongbaoInfoTmp) && !empty($tchongbaoInfoTmp[0])){
                $tchongbaoInfo = $tchongbaoInfoTmp[0];
            }
        }
        
        $tongchengAttrListTmp = C::t('#tom_tongcheng#tom_tongcheng_attr')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY paixu ASC,id DESC ",0,50);
        $tongchengTagListTmp = C::t('#tom_tongcheng#tom_tongcheng_tag')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY id DESC ",0,50);
        $tongchengPhotoListTmpTmp = C::t('#tom_tongcheng#tom_tongcheng_photo')->fetch_all_list(" AND tongcheng_id={$value['id']} "," ORDER BY id ASC ",0,50);
        $tongchengPhotoListTmp = array();
        $tongchengAlbumListTmp = array();
        if(is_array($tongchengPhotoListTmpTmp) && !empty($tongchengPhotoListTmpTmp)){
            foreach ($tongchengPhotoListTmpTmp as $kk => $vv){
                if($tongchengConfig['open_yun'] == 2 && !empty($vv['oss_picurl'])){
                    $picurl = $vv['oss_picurl'].'?x-oss-process=image/resize,m_fill,h_120,w_120';
                    $albumurl = $vv['oss_picurl'];
                }else if($tongchengConfig['open_yun'] == 3 && !empty($vv['qiniu_picurl'])){
                    $picurl = $vv['qiniu_picurl'].'?imageView2/1/w/120/h/120';
                    $albumurl = $vv['qiniu_picurl'];
                }else{
                    if(!preg_match('/^http/', $vv['picurl']) ){
                        if(strpos($vv['picurl'], 'source/plugin/tom_tongcheng/data/') === false){
                            $picurl = $albumurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$vv['picurl'];
                        }else{
                            $picurl = $albumurl = $_G['siteurl'].$vv['picurl'];
                        }
                    }else{
                        $picurl = $albumurl = $vv['picurl'];
                    }
                }
                $tongchengPhotoListTmp[$kk]['picurl'] = $picurl;
                $tongchengPhotoListTmp[$kk]['albumurl'] = $albumurl;
                $tongchengAlbumListTmp[$kk] = $albumurl;
            }
        }
        $tongchengList[$key]['userInfo'] = $userInfoTmp;
        $tongchengList[$key]['typeInfo'] = $typeInfoTmp;
        $tongchengList[$key]['cateInfo'] = $cateInfoTmp;
        $tongchengList[$key]['attrList'] = $tongchengAttrListTmp;
        $tongchengList[$key]['tagList']  = $tongchengTagListTmp;
        $tongchengList[$key]['photoList'] = $tongchengPhotoListTmp;
        $tongchengList[$key]['albumList'] = $tongchengAlbumListTmp;
        $tongchengList[$key]['siteInfo'] = $siteInfoTmp;
        $tongchengList[$key]['tchongbaoInfo'] = $tchongbaoInfo;
        
        $tongchengList[$key]['tcshopInfo'] = array();
        if($__ShowTcshop == 1 && $value['tcshop_id'] > 0){
            $tcshopInfo = C::t('#tom_tcshop#tom_tcshop')->fetch_by_id($value['tcshop_id']);
            if($tcshopInfo){
                if(!preg_match('/^http/', $tcshopInfo['picurl']) ){
                    if(strpos($tcshopInfo['picurl'], 'source/plugin/tom_tcshop/') === FALSE){
                        $picurl = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$tcshopInfo['picurl'];
                    }else{
                        $picurl = $tcshopInfo['picurl'];
                    }
                }else{
                    $picurl = $tcshopInfo['picurl'];
                }
                $tcshopInfo['picurl'] = $picurl;
                $tongchengList[$key]['tcshopInfo'] = $tcshopInfo;
            }
        }
    }
    
    if(is_array($tongchengList) && !empty($tongchengList)){
        $i = 0;
        $outStr .= '<div id="list"><ul>';
        foreach ($tongchengList as $key => $val){
			$val['shop'] = DB::fetch_first("select  * from pre_tom_tcshop  where user_id=".$val['user_id']);
            $i++;
            //$outStr .= '<li>';
            if($tongchengConfig['open_load_list_clicks'] == 1){
                DB::query("UPDATE ".DB::table('tom_tongcheng')." SET clicks=clicks+1 WHERE id='{$val['id']}' ", 'UNBUFFERED');
            }
            $messageUrl = 'plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=message&act=create&tongcheng_id='.$val['id'].'&to_user_id='.$val['userInfo']['id'].'&formhash='.FORMHASH;
            $tousuUrl = 'plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=tousu&tongcheng_id='.$val['id'];
			/*
			if($val['topstatus'] == 1){
            $outStr .= '<span class="title cla"><A href="javascript:;"><font color="#f8a543;">顶</font> </A></span>';	
            }
            $outStr.= '<a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=home&uid='.$val['userInfo']['id'].'"><img src="'.$val['userInfo']['picurl'].'" class="img"/></a><div>';
            $outStr .= '<span class="title cla"><font color="#f8a543;">【</font><a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=list&type_id='.$val['typeInfo']['id'].'">'.$val['typeInfo']['name'].'</a><font color="#f8a543;">】</font></span>  ';
            $outStr .= '<span class="title"><a  href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=home&uid='.$val['userInfo']['id'].'">'.cutstr($val['xm'],16).'  &nbsp;</a> </span>';
          
            if(!empty($val['tchongbaoInfo'])){
             	
                $outStr.= '</a>';
                
            }
            $outStr .= '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
            if ($val['tchongbaoInfo']['status'] == 1){
               $outStr.= '<a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=info&tongcheng_id='.$val['id'].'" class="ext-act tchongbao"><img src="source/plugin/tom_tchongbao/images/hongbao-ico.png" style="width: 11px;"> '.lang('plugin/tom_tongcheng', 'ajax_qiang_hb').' </a>';
            }else{
              
           }
            $outStr .= '</span>';
            $val['content'] = str_replace("\r\n","<br/>",$val['content']);
            $val['content'] = str_replace("\n","<br/>",$val['content']);
            $val['content'] = str_replace("\r","<br/>",$val['content']);
            $outStr .= '<p><a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=info&tongcheng_id='.$val['id'].'">'.$val['content'].'</a></p>';
            $outStr .= '<p class="zan">';
           if(($val['refresh_time'] - $val['add_time']) > 3600){
               //$outStr.= '<span>'.lang("plugin/tom_tongcheng", "template_refresh_title").dgmdate($val['refresh_time'], 'u','9999','m-d').'</span>';
           }else{
               //$outStr.= '<span>'.dgmdate($val['refresh_time'], 'u','9999','m-d').'</span>';
           }
		   $outStr.= '<span>'.time_tran($val['add_time']).'</span>';
		   
           $outStr .= ' '.$val['clicks'].' 人浏览 </p>';
           $outStr .= '<p>';
           $data_tel = "tel:".$val['tel'];
           if($val['finish'] == 1){
               $data_tel = "javascript:void(0);";
           }                  
        
           $outStr.= '<div class="act-bar" style="display:none">';
           if($val['finish'] == 0){
               $outStr.= '<a href="tel:'.$val['tel'].'" class="act blue"><img src="source/plugin/tom_tongcheng/images/icon-tel.png" style="width: 13px;">&nbsp;'.lang("plugin/tom_tongcheng", "template_tel").'</a>';
           }
           $outStr.= '<a href="'.$messageUrl.'" class="act"><img src="source/plugin/tom_tongcheng/images/icon-email.png" style="width: 13px;">&nbsp;'.lang("plugin/tom_tongcheng", "template_sms").'</a>';
           $outStr.= '</div>';
           $outStr.= '<div class="detail-time-icon" style="display:none" data-id="'.$val['id'].'" data-message="'.$messageUrl.'" data-tousu="'.$tousuUrl.'" data-tel="'.$data_tel.'" data-user-id="'.$__UserInfo['id'].'"></div>';
           $outStr .= '</p>';
		   */
            //
            
            $outStr.= '<div class="tcline-item">';
                   $outStr.= '<div class="avatar-label">';
                        $outStr.= '<a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=home&uid='.$val['userInfo']['id'].'"><img src="'.$val['userInfo']['picurl'].'" class="avatar" /></a>';
                        if(!empty($val['tchongbaoInfo'])){
                            $outStr.= '<a class="hb-label" href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=info&tongcheng_id='.$val['id'].'">';
                                $outStr.= '<img src="source/plugin/tom_tchongbao/images/list-hongbao.png">';
                            $outStr.= '</a>';
                        }
                   $outStr.= '</div>';
                   $outStr.= '<div class="tcline-detail" data-id='.$val['id'].'>';
                        if($showTop == 1 && $val['topstatus'] == 1){
                            $outStr.= '<span><a style="background-color: #f15555;">'.lang("plugin/tom_tongcheng", "top").'</a></span>&nbsp; ';
                        }
                        $outStr.= '<span><a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=list&type_id='.$val['typeInfo']['id'].'">'.$val['typeInfo']['name'].'</a></span>&nbsp; ';
                        if($tongchengConfig['open_list_xm'] == 1){
                            $val['xm'] = cutstr($val['xm'], 18, '...');
                            $outStr.= '<a class="username" href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=home&uid='.$val['userInfo']['id'].'">'.$val['xm'].'</a>';
                        }else{
                            $val['userInfo']['nickname'] = cutstr($val['userInfo']['nickname'], 18, '...');
                            $outStr.= '<a class="username" href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=home&uid='.$val['userInfo']['id'].'">'.$val['userInfo']['nickname'].'</a>';
                        }
						if($val['topstatus'] == 1){
							$outStr .= '<A href="javascript:;"><font color="#f8a543;">&nbsp; 顶 &nbsp;</font> </A>';	
						}
						
                        if ($val['tchongbaoInfo']['status'] == 1){
                            $outStr.= '<a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=info&tongcheng_id='.$val['id'].'" class="ext-act tchongbao"><img src="source/plugin/tom_tchongbao/images/hongbao-ico.png" style="width: 11px;"> '.lang('plugin/tom_tongcheng', 'ajax_qiang_hb').' </a>';
                        }else{
                            $outStr.= '<a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=info&tongcheng_id='.$val['id'].'" class="ext-act"><img src="source/plugin/tom_tongcheng/images/icon-show.png" style="width: 12px;"> '.lang("plugin/tom_tongcheng", "template_xiangqing").' </a>';
                        }
                        if(is_array($val['tagList']) && !empty($val['tagList'])){
                            $outStr.= '<article style="max-height: 90px;">';
                        }else{
                            $outStr.= '<article>';
                        }
                        if($tongchengConfig['open_list_quanwen'] == 0){
                            $outStr.= '<a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=info&tongcheng_id='.$val['id'].'">';
                        }
                            if(is_array($val['tagList']) && !empty($val['tagList'])){
                             $outStr.= '<div class="detail-tags">';
                                 foreach ($val['tagList'] as $k1 => $v1){
                                  $outStr.= '<span class="span'.$k1.'">'.$v1['tag_name'].'</span>';
                                 }
                                  $outStr.= '<div class="clear"></div>';
                             $outStr.= '</div>';
                            }
                            $val['content'] = str_replace("\r\n","<br/>",$val['content']);
                            $val['content'] = str_replace("\n","<br/>",$val['content']);
                            $val['content'] = str_replace("\r","<br/>",$val['content']);
						    if(!empty($val['shop']) && ($val['shop']['shenhe_status'] == 1)){
                            	$outStr.= '<p><font color="#f60">商家&nbsp;:&nbsp;</font><a class="username" href="plugin.php?id=tom_tcshop&site=1&mod=details&tcshop_id='.$val['shop']['id'].'">'.$val['shop']['name'].' <img src="source/plugin/tom_tongcheng/images/shop.jpg"  style="width: 18px;height: 18px;border-radius: 0;"></a> </p>';
                            }
                            if(is_array($val['cateInfo']) && !empty($val['cateInfo'])){
                                $outStr.= '<p><font color="#f60">'.lang("plugin/tom_tongcheng", "template_cate").'&nbsp;:&nbsp;</font>'.$val['cateInfo']['name'].'</p>';
                            }
                            if(is_array($val['attrList']) && !empty($val['attrList'])){
                                 foreach ($val['attrList'] as $k2 => $v2){
                                    if(!empty($v2['value'])){
                                        $outStr.= '<p><font color="#F60">'.$v2['attr_name'].'&nbsp;:&nbsp;</font></b>'.$v2['value'];
                                        if($v2['unit']){
                                            $outStr.= ''.$v2['unit'];
                                        }
                                        $outStr.= '</p>';
                                    }
                                 }
                             }
                             if(!empty($val['address'])){
                                $outStr.= '<p><font color="#F60">'.lang("plugin/tom_tongcheng", "template_address").'&nbsp;:&nbsp;</font></b>'.$val['address'].'</p>';
                             }
                             $outStr.= '<p>'.$val['content'].'</p>';
                         if($tongchengConfig['open_list_quanwen'] == 0){
                             $outStr.= '</a>';
                         }
                        $outStr.= '</article>';
                        $outStr.= '<div class="act-bar">';
                             if($val['finish'] == 0){
                                $outStr.= '<a href="tel:'.$val['tel'].'" class="act blue"><img src="source/plugin/tom_tongcheng/images/icon-tel.png" style="width: 13px;">&nbsp;'.lang("plugin/tom_tongcheng", "template_tel").'</a>';
                             }
                             $outStr.= '<a href="'.$messageUrl.'" class="act"><img src="source/plugin/tom_tongcheng/images/icon-email.png" style="width: 13px;">&nbsp;'.lang("plugin/tom_tongcheng", "template_sms").'</a>';
                        $outStr.= '</div>';
                        $outStr.= '<div class="detail-toggle">'.lang("plugin/tom_tongcheng", "template_quanwen").'</div>';
                        $outStr.= '<div class="detail-toggle2" style="display:none;">'.lang("plugin/tom_tongcheng", "template_shouqi").'</div>';
                        if(is_array($val['photoList']) && !empty($val['photoList'])){
                        $outStr.= '<div class="detail-pics"><input type="hidden" name="photo_list_'.$val['id'].'" id="photo_list_'.$val['id'].'" value="'.implode('|', $val['albumList']).'">';
                            foreach ($val['photoList'] as $k3 => $v3){
                                $outStr.= '<a href="javascript:void(0);" onclick="showPic(\''.$v3['albumurl'].'\','.$val['id'].');"><img src="'.$v3['picurl'].'"></a>';
                            }
                        $outStr.= '</div>';
                        }
                        
                        //if(is_array($val['photoList']) && !empty($val['photoList'])){
                        //    if(is_array($val['tcshopInfo']) && !empty($val['tcshopInfo'])){
                        //        $outStr.= '<a href="plugin.php?id=tom_tcshop&site='.$site_id.'&mod=details&tcshop_id='.$val['tcshopInfo']['id'].'" class="detail-shoplink"><img src="source/plugin/tom_tongcheng/images/detail-shoplink-ico.png" style="width: 13px;">'.$val['tcshopInfo']['name'].'</a>';
                        //    }
                        //}else{
                            if(is_array($val['tcshopInfo']) && !empty($val['tcshopInfo'])){
                                $outStr.= '<a class="detail-link" href="plugin.php?id=tom_tcshop&site='.$val['tcshopInfo']['site_id'].'&mod=details&tcshop_id='.$val['tcshopInfo']['id'].'" target="_blank"><img src="'.$val['tcshopInfo']['picurl'].'"><b>'.$val['tcshopInfo']['name'].'</b><br><img src="source/plugin/tom_tongcheng/images/detail-link-ico.png" style="width: 12px;height: 17px;margin-right: 0px;">&nbsp;&nbsp;'.$val['tcshopInfo']['address'].'</a>';
                            }
                        //}
                        
                        $outStr.= '<div class="detail-time">';
                             $outStr.= '<a>';
                             //$refresh_log_count = C::t('#tom_tongcheng#tom_tongcheng_refresh_log')->fetch_all_count(" AND tongcheng_id={$val['id']} ");
                             if(($val['refresh_time'] - $val['add_time']) > 3600){
                                 $outStr.= '<span>'.lang("plugin/tom_tongcheng", "template_refresh_title").dgmdate($val['refresh_time'], 'u','9999','m-d H:i').'</span>';
                             }else{
                                 $outStr.= '<span>'.dgmdate($val['refresh_time'], 'u','9999','m-d H:i').'</span>';
                             }
                             
                             if($tongchengConfig['show_site_name'] == 1){
                                 $outStr.= '<span>&nbsp;'.lang("plugin/tom_tongcheng", "template_laiyuan").$val['siteInfo']['name'].'</span>';
                             }
                             $outStr.= '</a>';
                             $data_tel = "tel:".$val['tel'];
                             if($val['finish'] == 1){
                                 $data_tel = "javascript:void(0);";
                             }
                             $outStr.= '<div class="detail-time-icon" data-id="'.$val['id'].'" data-message="'.$messageUrl.'" data-tousu="'.$tousuUrl.'" data-tel="'.$data_tel.'" data-user-id="'.$__UserInfo['id'].'"></div>';
                        $outStr.= '</div>';
                        if($val['finish'] == 1){
                            $outStr.= '<section class="mark-img succ"></section>';
                        }
                        $outStr.= '<div class="detail-cmt-wrap">';
                             $outStr.= '<i class="detail-cmtr"></i>';
                             $outStr.= '<div class="detail-cmt">';
                              $outStr.= '<div class="like-list">';
                                    $outStr.= $val['clicks'].' '.lang("plugin/tom_tongcheng", "template_clicks").'';
                                    $outStr.= '<span >'.$val['zhuanfa'].'</span> '.lang("plugin/tom_tongcheng", "template_zhuanfa").' ';
                                    $outStr.= '<span >'.$val['collect'].'</span> '.lang("plugin/tom_tongcheng", "template_collect").' ';
                              $outStr.= '</div>';
                             $outStr.= '</div>';
                        $outStr.= '</div>';
                   $outStr.= '</div>';
              $outStr.= '</div>';
            /*
            if(is_array($val['photoList']) && !empty($val['photoList'])){
            	$outStr.= '<div class="detail-pics"><input type="hidden" name="photo_list_'.$val['id'].'" id="photo_list_'.$val['id'].'" value="'.implode('|', $val['albumList']).'">';
            	foreach ($val['photoList'] as $k3 => $v3){
            		$outStr.= '<a href="javascript:void(0);" onclick="showPic(\''.$v3['albumurl'].'\','.$val['id'].');"><img src="'.$v3['picurl'].'"></a>';
            	}
            	$outStr.= '</div>';
            }
            if(is_array($val['attrList']) && !empty($val['attrList'])){
            	foreach ($val['attrList'] as $k2 => $v2){
            		if(!empty($v2['value'])){
            			$outStr.= '<p><font color="#F60">'.$v2['attr_name'].'&nbsp;:&nbsp;</font></b>'.$v2['value'];
            			if($v2['unit']){
            				$outStr.= ''.$v2['unit'];
            			}
            			$outStr.= '</p>';
            		}
            	}
            }
            */
           // $outStr  .= '</div></li>';
        }
        //$outStr .= '</ul></div>';
        if($tongchengConfig['open_load_list_clicks'] == 1 && $tongchengConfig['open_tj_commonclicks'] == 1){
            DB::query("UPDATE ".DB::table('tom_tongcheng_common')." SET clicks=clicks+{$i} WHERE id='$site_id' ", 'UNBUFFERED');
        }
    }else{
        $outStr = '205';
    }
    
    $outStr = diconv($outStr,CHARSET,'utf-8');
    echo json_encode($outStr); exit;
    
}else if($_GET['act'] == 'collect' && $_GET['formhash'] == FORMHASH  && $userStatus){
    
    $user_id        = intval($_GET['user_id'])>0? intval($_GET['user_id']):0;
    $tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    $collectListTmp = C::t('#tom_tongcheng#tom_tongcheng_collect')->fetch_all_list(" AND user_id={$user_id} AND tongcheng_id={$tongcheng_id} "," ORDER BY id DESC ",0,1);
    
    if(is_array($collectListTmp) && !empty($collectListTmp)){
        echo 100;exit;
    }
    
    $insertData = array();
    $insertData['user_id']      = $user_id;
    $insertData['tongcheng_id'] = $tongcheng_id;
    $insertData['add_time']     = TIMESTAMP;
    if(C::t('#tom_tongcheng#tom_tongcheng_collect')->insert($insertData)){
        DB::query("UPDATE ".DB::table('tom_tongcheng')." SET collect=collect+1 WHERE id='$tongcheng_id' ", 'UNBUFFERED');
        echo 200;exit;
    }
    
    echo 404;exit;
    
}else if($_GET['act'] == 'clicks' && $_GET['formhash'] == FORMHASH){
    
    $tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    DB::query("UPDATE ".DB::table('tom_tongcheng')." SET clicks=clicks+1 WHERE id='$tongcheng_id' ", 'UNBUFFERED');
    if($tongchengConfig['open_tj_commonclicks'] == 1){
        DB::query("UPDATE ".DB::table('tom_tongcheng_common')." SET clicks=clicks+1 WHERE id='$site_id' ", 'UNBUFFERED');
    }
    echo 200;exit;
    
}else if($_GET['act'] == 'commonClicks' && $_GET['formhash'] == FORMHASH){
    
    DB::query("UPDATE ".DB::table('tom_tongcheng_common')." SET clicks=clicks+1 WHERE id='$site_id' ", 'UNBUFFERED');
    echo 200;exit;
    
}else if($_GET['act'] == 'zhuanfa' && $_GET['formhash'] == FORMHASH){
    
    $tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    DB::query("UPDATE ".DB::table('tom_tongcheng')." SET zhuanfa=zhuanfa+1 WHERE id='$tongcheng_id' ", 'UNBUFFERED');
    echo 200;exit;
    
}else if($_GET['act'] == 'updateTopstatus' && $_GET['formhash'] == FORMHASH){
    
    $cookiesTopstatus = getcookie('tom_tongcheng_update_topstatus');
    if(!empty($cookiesTopstatus) && $cookiesTopstatus==1){
    }else{
        $tongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list(" AND topstatus=1 AND toptime<".TIMESTAMP." "," ORDER BY toptime ASC,id DESC ",0,10);
        if(is_array($tongchengListTmp) && !empty($tongchengListTmp)){
            foreach ($tongchengListTmp as $key => $value){
                $updateData = array();
                $updateData['topstatus']     = 0;
                $updateData['toprand']       = 1;
                C::t('#tom_tongcheng#tom_tongcheng')->update($value['id'],$updateData);
            }
        }
        dsetcookie('tom_tongcheng_update_topstatus',1,300);
    }
    echo 200;exit;
    
}else if($_GET['act'] == 'updateToprand' && $_GET['formhash'] == FORMHASH){
    
    $cookiesToprand = getcookie('tom_tongcheng_update_toprand');
    if(!empty($cookiesToprand) && $cookiesToprand==1){
    }else{
        $tongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list(" AND topstatus=1 "," ORDER BY toprand DESC,id DESC ",0,6);
        if(is_array($tongchengListTmp) && !empty($tongchengListTmp)){
            foreach ($tongchengListTmp as $key => $value){
                if($value['topstatus'] == 1 && (TIMESTAMP - $value['refresh_time']) > 1800){
                    $toprand = mt_rand(111, 999);
                    DB::query("UPDATE ".DB::table('tom_tongcheng')." SET toprand=".$toprand." WHERE id='{$value['id']}' ", 'UNBUFFERED');
                }
            }
        }
        dsetcookie('tom_tongcheng_update_toprand',1,5);
    }
    echo 200;exit;
    
}else if($_GET['act'] == 'get_search_url' && $_GET['formhash'] == FORMHASH){
    
    $keyword = isset($_GET['keyword'])? daddslashes(diconv(urldecode($_GET['keyword']),'utf-8')):'';
    
    $url = $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=list&keyword=".urlencode(trim($keyword));
    echo $url;exit;
    
}else if($_GET['act'] == 'updateStatus' && $_GET['formhash'] == FORMHASH && $userStatus){
    
    $tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    
    if($tongchengInfo['user_id'] != $__UserInfo['id']){
        echo '404';exit;
    }
    
    if($_GET['status'] == 1){
        $updateData = array();
        $updateData['status'] = 1;
        C::t('#tom_tongcheng#tom_tongcheng')->update($tongcheng_id,$updateData);
    }else if($_GET['status'] == 2){
        $updateData = array();
        $updateData['status'] = 2;
        C::t('#tom_tongcheng#tom_tongcheng')->update($tongcheng_id,$updateData);
    }
    
    echo 200;exit;
}else if($_GET['act'] == 'updateFinish' && $_GET['formhash'] == FORMHASH && $userStatus){
    
    $tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    
    if($tongchengInfo['user_id'] != $__UserInfo['id']){
        echo '404';exit;
    }
    
    $updateData = array();
    $updateData['finish'] = 1;
    C::t('#tom_tongcheng#tom_tongcheng')->update($tongcheng_id,$updateData);
    
    echo 200;exit;
    
}else if($_GET['act'] == 'refresh' && $_GET['formhash'] == FORMHASH && $userStatus){
    
    $tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    
    if($tongchengInfo['user_id'] != $__UserInfo['id']){
        echo '404';exit;
    }
    
    $updateData = array();
    $updateData['refresh_time'] = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng')->update($tongcheng_id,$updateData);
    
    $insertData = array();
    $insertData['tongcheng_id'] = $tongcheng_id;
    $insertData['time_key']     = $nowDayTime;
    $insertData['add_time']     = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng_refresh_log')->insert($insertData);
    
    echo 200;exit;
}else if($_GET['act'] == 'refresh3' && $_GET['formhash'] == FORMHASH && $userStatus){
    
    $tongcheng_id   = intval($_GET['tongcheng_id'])>0? intval($_GET['tongcheng_id']):0;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    $typeInfo = C::t('#tom_tongcheng#tom_tongcheng_model_type')->fetch_by_id($tongchengInfo['type_id']);
    
    if($tongchengInfo['user_id'] != $__UserInfo['id']){
        echo '404';exit;
    }
    
    $updateData = array();
    $updateData['refresh_time'] = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng')->update($tongcheng_id,$updateData);
    
    $useScore = $tongchengConfig['score_yuan']*$typeInfo['refresh_price'];
    $useScore = ceil($useScore);
    
    $updateData = array();
    $updateData['score'] = $__UserInfo['score'] - $useScore;
    C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);

    $insertData = array();
    $insertData['user_id']          = $__UserInfo['id'];
    $insertData['score_value']      = $useScore;
    $insertData['old_value']        = $__UserInfo['score'];
    $insertData['log_type']         = 5;
    $insertData['log_time']         = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng_score_log')->insert($insertData);
    
    $insertData = array();
    $insertData['tongcheng_id'] = $tongcheng_id;
    $insertData['time_key']     = $nowDayTime;
    $insertData['add_time']     = TIMESTAMP;
    C::t('#tom_tongcheng#tom_tongcheng_refresh_log')->insert($insertData);
    
    echo 200;exit;
}else if($_GET['act'] == 'list_get_street' && $_GET['formhash'] == FORMHASH){
    
    $outStr = '';
    
    $area_id   = intval($_GET['area_id'])>0? intval($_GET['area_id']):0;
    
    $streetList = C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($area_id);
    
    if($area_id > 0 && is_array($streetList) && !empty($streetList)){
        $outStr = '<li class="item" data-id="0" data-name="'.lang("plugin/tom_tongcheng", "template_list_all").'">'.lang("plugin/tom_tongcheng", "template_list_all").'</li>';
        foreach ($streetList as $key => $value){
           $outStr.= '<li class="item" data-id="'.$value['id'].'" data-name="'.$value['name'].'">'.$value['name'].'</li>';
        }
    }else{
       $outStr = '100';
    }
    
    $outStr = diconv($outStr,CHARSET,'utf-8');
    echo json_encode($outStr); exit;
    
}else if($_GET['act'] == 'list_get_cate' && $_GET['formhash'] == FORMHASH){
    
    $outStr = '';
    
    $type_id   = intval($_GET['type_id'])>0? intval($_GET['type_id']):0;
    
    $cateList = C::t('#tom_tongcheng#tom_tongcheng_model_cate')->fetch_all_list(" AND type_id={$type_id}  "," ORDER BY paixu ASC,id DESC ",0,50);
    
    if(is_array($cateList) && !empty($cateList)){
        $outStr = '<li class="item" data-id="0" data-name="'.lang("plugin/tom_tongcheng", "template_list_all").'">'.lang("plugin/tom_tongcheng", "template_list_all").'</li>';
        foreach ($cateList as $key => $value){
           $outStr.= '<li class="item" data-id="'.$value['id'].'" data-name="'.$value['name'].'">'.$value['name'].'</li>';
        }
    }else{
       $outStr = '100';
    }
    
    $outStr = diconv($outStr,CHARSET,'utf-8');
    echo json_encode($outStr); exit;
    
}else if($_GET['act'] == 'auto_click' && $_GET['formhash'] == FORMHASH){
    
    $cookies_auto_click_status = getcookie('tom_tongcheng_auto_click_status');
    $halfhour = TIMESTAMP - 1800;
    $threedays = TIMESTAMP - 86400*3;
    if($tongchengConfig['open_auto_click'] == 1){
        
        $auto_min_num = 5;
        $auto_max_num = 10;
        if($tongchengConfig['auto_min_num'] < $tongchengConfig['auto_max_num']){
            $auto_min_num = $tongchengConfig['auto_min_num'];
            $auto_max_num = $tongchengConfig['auto_max_num'];
        }
        
        if(!empty($cookies_auto_click_status) && $cookies_auto_click_status==1){
        }else{
            $tongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list(" AND status=1 AND shenhe_status=1 AND add_time<".$halfhour." AND auto_click_time<".$nowDayTime." AND refresh_time>".$threedays." "," ORDER BY id DESC ",0,10);
            if(is_array($tongchengListTmp) && !empty($tongchengListTmp)){
                $i = 0;
                foreach ($tongchengListTmp as $key => $value){
                    
                    $auto_click_num = mt_rand($auto_min_num, $auto_max_num);
                    $i = $i + $auto_click_num;
                    $updateData = array();
                    $updateData['clicks']     = $value['clicks'] + $auto_click_num;
                    $updateData['auto_click_time']     = $nowDayTime;
                    C::t('#tom_tongcheng#tom_tongcheng')->update($value['id'],$updateData);
                }
                if($tongchengConfig['open_tj_commonclicks'] == 1){
                    DB::query("UPDATE ".DB::table('tom_tongcheng_common')." SET clicks=clicks+{$i} WHERE id='$site_id' ", 'UNBUFFERED');
                }
            }
            dsetcookie('tom_tongcheng_auto_click_status',1,300);
        }
    }
    
    echo 200;exit;

}else if($_GET['act'] == 'auto_zhuanfa' && $_GET['formhash'] == FORMHASH){
    
    $cookies_auto_zhuanfa_status = getcookie('tom_tongcheng_auto_zhuanfa_status');
    $halfhour = TIMESTAMP - 1800;
    $threedays = TIMESTAMP - 86400*3;
    if($tongchengConfig['open_auto_zhuanfa'] == 1){
        
        $auto_min_num = 1;
        $auto_max_num = 10;
        if($tongchengConfig['min_zhuanfa_num'] < $tongchengConfig['max_zhuanfa_num']){
            $auto_min_num = $tongchengConfig['min_zhuanfa_num'];
            $auto_max_num = $tongchengConfig['max_zhuanfa_num'];
        }

        if(!empty($cookies_auto_zhuanfa_status) && $cookies_auto_zhuanfa_status==1){
        }else{
            $tongchengListTmp = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_list(" AND status=1 AND shenhe_status=1 AND add_time<".$halfhour." AND auto_zhuanfa_time<".$nowDayTime." AND refresh_time>".$threedays." "," ORDER BY id DESC ",0,10);
            if(is_array($tongchengListTmp) && !empty($tongchengListTmp)){
                foreach ($tongchengListTmp as $key => $value){
                    
                    $auto_zhuanfa_num = mt_rand($auto_min_num, $auto_max_num);
                    
                    if(($value['zhuanfa'] + $auto_zhuanfa_num) < $value['clicks']){
                        $updateData = array();
                        $updateData['zhuanfa']     = $value['zhuanfa'] + $auto_zhuanfa_num;
                        $updateData['auto_zhuanfa_time']     = $nowDayTime;
                        C::t('#tom_tongcheng#tom_tongcheng')->update($value['id'],$updateData);
                    }
                }
            }
            dsetcookie('tom_tongcheng_auto_zhuanfa_status',1,300);
        }
    }
    
    echo 200;exit;
    
}else if($_GET['act'] == 'shenhe_sms' && $_GET['formhash'] == FORMHASH){
    
    $cookies_shenhe_sms_status = getcookie('tom_tongcheng_shenhe_sms_status');
    
    if(!empty($cookies_shenhe_sms_status) && $cookies_shenhe_sms_status==1){
        echo 404;exit;
    }else{
        $noShenheCount = C::t('#tom_tongcheng#tom_tongcheng')->fetch_all_count(" AND site_id={$site_id} AND pay_status!=1 AND shenhe_status=2 ");
    
        if($noShenheCount >0){
        }else{
            echo 0;exit;
        }

        $sitesInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($site_id);

        $toUser = array();
        $toUserId = 0;
        if(!empty($sitesInfo['manage_user_id'])){
            $toUserId = $sitesInfo['manage_user_id'];
        }else if(!empty($tongchengConfig['manage_user_id'])){
            $toUserId = $tongchengConfig['manage_user_id'];
        }

        if(empty($toUserId)){
            echo 1;exit;
        }

        $toUserTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($toUserId);
        if($toUserTmp && !empty($toUserTmp['openid'])){
            $toUser = $toUserTmp;
        }else{
            echo 2;exit;
        }
        
        $appid = trim($tongchengConfig['wxpay_appid']);  
        $appsecret = trim($tongchengConfig['wxpay_appsecret']);
        include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/weixin.class.php';
        $weixinClass = new weixinClass($appid,$appsecret);

        include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/templatesms.class.php';
        $access_token = $weixinClass->get_access_token();
        $nextSmsTime = $toUser['last_smstp_time'] + 300;

        if($access_token && !empty($toUser['openid']) && TIMESTAMP > $nextSmsTime ){
            $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=managerList&type=1");
            $shenhe_template_first = str_replace("{NUM}",$noShenheCount, lang('plugin/tom_tongcheng','shenhe_template_first'));
            $smsData = array(
                'first'         => $shenhe_template_first,
                'keyword1'      => '',
                'keyword2'      => '',
                'remark'        => ''
            );
            $r = $templateSmsClass->sendSms01($toUser['openid'],$tongchengConfig['template_id'],$smsData);

            if($r){
                $updateData = array();
                $updateData['last_smstp_time'] = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_user')->update($toUser['id'],$updateData);
            }

        }

        dsetcookie('tom_tongcheng_shenhe_sms_status',1,300);
    }
    
    echo 200;exit;

}else if($_GET['act'] == 'pinglun' && $_GET['formhash'] == FORMHASH  && $userStatus){
    if('utf-8' != CHARSET) {
        if(defined('IN_MOBILE')){
        }else{
            foreach($_POST AS $pk => $pv) {
                if(!is_numeric($pv)) {
                    $_GET[$pk] = $_POST[$pk] = wx_iconv_recurrence($pv);	
                }
            }
        }
    }
    
    $user_id = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    $pinglun_id = isset($_GET['pinglun_id'])? intval($_GET['pinglun_id']):0;
    $content = isset($_GET['txtContentAdd'])? daddslashes($_GET['txtContentAdd']):'';
    $tongcheng_id = isset($_GET['tongcheng_id'])? intval($_GET['tongcheng_id']):0;
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($user_id);
    
    $__CommonInfo = C::t('#tom_tongcheng#tom_tongcheng_common')->fetch_by_id(1);
    if(!empty($__CommonInfo['forbid_word'])){
        $forbid_word = preg_quote(trim($__CommonInfo['forbid_word']), '/');
        $forbid_word = str_replace(array("\\*"), array('.*'), $forbid_word);
        $forbid_word = '.*('.$forbid_word.').*';
        $forbid_word = '/^('.str_replace(array("\r\n", ' '), array(').*|.*(', ''), $forbid_word).')$/i';
        if(@preg_match($forbid_word, $content,$matches)) {
            $i = count($matches)-1;
            $word = '';
            if(isset($matches[$i]) && !empty($matches[$i])){
                $word = diconv($matches[$i],CHARSET,'utf-8');
            }
            $outArr = array(
                'status'=> 505,
                'word'=> $word,
            );
            echo json_encode($outArr); exit;
        }
                
    }
    
    if($tongchengInfo['site_id'] == 1){
        $sitename = $tongchengConfig['plugin_name'];
    }else{
        $siteInfo = C::t('#tom_tongcheng#tom_tongcheng_sites')->fetch_by_id($tongchengInfo['site_id']);
        $sitename = $siteInfo['name'];
    }
    
    if(empty($tongchengInfo['title'])){
        $tongchengInfo['title'] = cutstr(contentFormat($tongchengInfo['content']),20,"...");
    }
    
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/weixin.class.php';
    include DISCUZ_ROOT.'./source/plugin/tom_tongcheng/class/templatesms.class.php';
    $appid = trim($tongchengConfig['wxpay_appid']);  
    $appsecret = trim($tongchengConfig['wxpay_appsecret']);
    $weixinClass = new weixinClass($appid,$appsecret);
    $access_token = $weixinClass->get_access_token();
    $nextSmsTime = $userInfo['last_smstp_time'] + 0;
    $smsContent = strip_tags($content);
    $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_tongcheng&site={$site_id}&mod=message");
    
    if($pinglun_id > 0){
        $pinglunInfo = C::t('#tom_tongcheng#tom_tongcheng_pinglun')->fetch_by_id($pinglun_id);
        if($pinglunInfo){
            $toUser = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($pinglunInfo['user_id']);
        }
        
        $insertData = array();
        $insertData['ping_id'] = $pinglun_id;
        $insertData['tongcheng_id'] = $tongcheng_id;
        $insertData['reply_user_id'] = $userInfo['id'];
        $insertData['reply_user_nickname'] = $userInfo['nickname'];
        $insertData['reply_user_avatar'] = $userInfo['picurl'];
        $insertData['content'] = $content;
        $insertData['reply_time'] = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_pinglun_reply')->insert($insertData);
        
        if($tongchengInfo){
            $message = strip_tags($content);
            $message = contentFormat($message);
            $message = $message.'<br/><a href="plugin.php?id=tom_tongcheng&site='.$tongchengInfo['site_id'].'&mod=info&tongcheng_id='.$tongchengInfo['id'].'">['.lang("plugin/tom_tongcheng", "template_dianjichakan").']</a>';

            $insertData = array();
            $insertData['user_id']      = $toUser['id'];
            $insertData['type']     = 1;
            $insertData['content']      = '<font color="#238206">'.lang('plugin/tom_tongcheng', 'ajax_pinglun_title').'</font><br/>'.$userInfo['nickname'].lang('plugin/tom_tongcheng', 'ajax_touser_1_reply').$tongchengInfo['title'].lang('plugin/tom_tongcheng', 'ajax_touser_2_reply').dhtmlspecialchars($pinglunInfo['content']).';<br/>'.lang('plugin/tom_tongcheng', 'ajax_touser_reply_pinglun').$message;
            $insertData['is_read']     = 0;
            $insertData['tz_time']     = TIMESTAMP;
            C::t('#tom_tongcheng#tom_tongcheng_tz')->insert($insertData);
        }
        
        if($access_token && !empty($toUser['openid']) && TIMESTAMP > $nextSmsTime ){
            $smsData = array(
                'first'         => $userInfo['nickname'].lang('plugin/tom_tongcheng', 'ajax_pinglun_reply_hueifu'),
                'keyword1'      => $sitename,
                'keyword2'      => $smsContent,
                'remark'        => ''
            );
            $r = $templateSmsClass->sendSms01($toUser['openid'],$tongchengConfig['template_id'],$smsData);

            if($r){
                $updateData = array();
                $updateData['last_smstp_time'] = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_user')->update($toUser['id'],$updateData);
            }
        }
        $outArr = array(
            'status'=> 200200,
        );
        echo json_encode($outArr); exit;
    }else{
        $toUser = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($tongchengInfo['user_id']);
        
        $insertData = array();
        $insertData['tongcheng_id'] = $tongcheng_id;
        $insertData['content'] = $content;
        $insertData['user_id'] = $user_id;
        $insertData['ping_time'] = TIMESTAMP;
        C::t('#tom_tongcheng#tom_tongcheng_pinglun')->insert($insertData);
        
        if($tongchengInfo){
            $message = strip_tags($content);
            $message = contentFormat($message);
            $message = $message.'<br/><a href="plugin.php?id=tom_tongcheng&site='.$tongchengInfo['site_id'].'&mod=info&tongcheng_id='.$tongchengInfo['id'].'">['.lang("plugin/tom_tongcheng", "template_dianjichakan").']</a>';

            $insertData = array();
            $insertData['user_id']      = $toUser['id'];
            $insertData['type']     = 1;
            $insertData['content']      = '<font color="#238206">'.lang('plugin/tom_tongcheng', 'ajax_pinglun_title').'</font><br/>'.$userInfo['nickname'].lang('plugin/tom_tongcheng', 'ajax_touser_1').$tongchengInfo['title'].lang('plugin/tom_tongcheng', 'ajax_touser_2').'<br/>'.lang('plugin/tom_tongcheng', 'ajax_touser_pinglun_content').$message;
            $insertData['is_read']     = 0;
            $insertData['tz_time']     = TIMESTAMP;
            C::t('#tom_tongcheng#tom_tongcheng_tz')->insert($insertData);
        }
        
        if($access_token && !empty($toUser['openid']) && TIMESTAMP > $nextSmsTime ){
            $smsData = array(
                'first'         => $userInfo['nickname'].lang('plugin/tom_tongcheng', 'ajax_pinglun_reply_hueifu'),
                'keyword1'      => $sitename,
                'keyword2'      => $smsContent,
                'remark'        => ''
            );
            $r = $templateSmsClass->sendSms01($toUser['openid'],$tongchengConfig['template_id'],$smsData);

            if($r){
                $updateData = array();
                $updateData['last_smstp_time'] = TIMESTAMP;
                C::t('#tom_tongcheng#tom_tongcheng_user')->update($toUser['id'],$updateData);
            }
        }
        $outArr = array(
            'status'=> 200,
        );
        echo json_encode($outArr); exit;
    }
    $outArr = array(
        'status'=> 1
    );
    echo json_encode($outArr); exit;
    
}else if($_GET['act'] == 'loadPinglun' && $_GET['formhash'] == FORMHASH){
    $outStr = '';
    
    $tongcheng_id = isset($_GET['tongcheng_id'])? intval($_GET['tongcheng_id']):0;
    $loadPage = isset($_GET['loadPage'])? intval($_GET['loadPage']):0;
    $pinglun_num = isset($_GET['pinglun_num'])? intval($_GET['pinglun_num']):0;
    $pagesize = 5;
    $start = ($loadPage - 1) * $pagesize;
    $start = $start - $pinglun_num;
    
    $tongchengInfo = C::t('#tom_tongcheng#tom_tongcheng')->fetch_by_id($tongcheng_id);
    $pinglunListTmp = C::t('#tom_tongcheng#tom_tongcheng_pinglun')->fetch_all_list(" AND tongcheng_id = {$tongcheng_id} ", 'ORDER BY ping_time DESC,id DESC', $start, $pagesize);

    if(is_array($pinglunListTmp) && !empty($pinglunListTmp)){
        foreach($pinglunListTmp as $key => $value){
            $userInfo = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($value['user_id']);
            $outStr.= '<div class="comment-item clearfix" id="comment-item_'.$value['id'].'">';
                $outStr.= '<div class="comment-item-avatar"><img src="'.$userInfo['picurl'].'"></div>';
                $outStr.= '<div class="comment-item-content">';
                
                if($tongchengInfo['user_id'] == $value['user_id']){
                  $outStr.= '<h5>'.$userInfo['nickname'].'<span class="floor_main">'.lang('plugin/tom_tongcheng', 'info_pinglun_floor_main').'</span>&nbsp;<span>'.dgmdate($value['ping_time'],"Y-m-d",$tomSysOffset).'</span><span class="right" onClick="comment_reply(\'comment_pinglun_'.$value['id'].'\', '.$value['id'].',\''.$userInfo['nickname'].'\');">'.lang('plugin/tom_tongcheng','pinglun_hueifu').'</span><span class="right remove" onClick="removePinglun('.$value['id'].');">'.lang('plugin/tom_tongcheng', 'info_comment_del').'</span></h5>';
                }else{
                  $outStr.= '<h5>'.$userInfo['nickname'].'&nbsp;&nbsp;<span>'.dgmdate($value['ping_time'],"Y-m-d",$tomSysOffset).'</span><span class="right" onClick="comment_reply(\'comment_pinglun_'.$value['id'].'\', '.$value['id'].',\''.$userInfo['nickname'].'\');">'.lang('plugin/tom_tongcheng','pinglun_hueifu').'</span><span class="right remove" onClick="removePinglun('.$value['id'].');">'.lang('plugin/tom_tongcheng', 'info_comment_del').'</span></h5>';

                }
                
                    $outStr.= '<div class="comment-item-content-text">'.qqface_replace(dhtmlspecialchars($value['content'])).'</div>';
                    $outStr.= '<div id="comment_pinglun_'.$value['id'].'" class="comment-item-content-text"></div>';
                    $replyListTmp = C::t('#tom_tongcheng#tom_tongcheng_pinglun_reply')->fetch_all_list(" AND tongcheng_id = {$tongcheng_id} AND ping_id = {$value['id']} ", "ORDER BY reply_time ASC,id ASC", 0, 1000);
                    if(is_array($replyListTmp) && !empty($replyListTmp)){
                        $outStr .= '<div class="comment_reply_pinglun_box">';
                        foreach($replyListTmp as $k => $v){
                            if($tongchengInfo['user_id'] == $v['reply_user_id']){
                                $outStr.= '<div id="comment-item-content-text_'.$v['id'].'" class="comment-item-content-text"><span>'.$v['reply_user_nickname'].'&nbsp;<span class="floor_main">'.lang('plugin/tom_tongcheng', 'info_pinglun_floor_main').'</span>'.lang('plugin/tom_tongcheng','pinglun_hueifu_dian').'&nbsp;</span>'.qqface_replace(dhtmlspecialchars($v['content'])).'&nbsp;&nbsp;<span class="remove" onClick="removeReply('.$v['id'].');">'.lang('plugin/tom_tongcheng','info_comment_del').'</span></div>';
                            }else{
                                $outStr.= '<div id="comment-item-content-text_'.$v['id'].'" class="comment-item-content-text"><span>'.$v['reply_user_nickname'].lang('plugin/tom_tongcheng','pinglun_hueifu_dian').'&nbsp;</span>'.qqface_replace(dhtmlspecialchars($v['content'])).'&nbsp;&nbsp;<span class="remove" onClick="removeReply('.$v['id'].');">'.lang('plugin/tom_tongcheng','info_comment_del').'</span></div>';
                            }
                        }
                        $outStr .= '</div>';
                    }
                    
                $outStr.= '</div>';
            $outStr.= '</div>';        
        }
        
    }else{
        $outStr = '201';
    }
    
    $outStr = diconv($outStr,CHARSET,'utf-8');
    echo json_encode($outStr); exit;
    
}else if($_GET['act'] == 'removePinglun' && $_GET['formhash'] == FORMHASH  && $userStatus){
    $ping_id = isset($_GET['ping_id'])? intval($_GET['ping_id']): 0;
    if(C::t('#tom_tongcheng#tom_tongcheng_pinglun')->delete_by_id($ping_id)){
        C::t('#tom_tongcheng#tom_tongcheng_pinglun_reply')->delete_pinglun_id($ping_id);
        echo 200;exit;
    }
    echo 1;exit;
    
}else if($_GET['act'] == 'removeReplyPinglun' && $_GET['formhash'] == FORMHASH  && $userStatus){
    $reply_id = isset($_GET['reply_id'])? intval($_GET['reply_id']): 0;
    C::t('#tom_tongcheng#tom_tongcheng_pinglun_reply')->delete_by_id($reply_id);
    echo 200;exit;
    
}else if($_GET['act'] == 'browser_shouchang' && $_GET['formhash'] == FORMHASH){
    $user_id = isset($_GET['user_id'])? intval($_GET['user_id']): 0;
    
    $lifeTime = 86400*3;
    dsetcookie('tom_tongcheng_browser_shouchang_'.$user_id, $user_id, $lifeTime);
    echo '200';exit;
    
}else if($_GET['act'] == 'zhuanfaScore' && $_GET['formhash'] == FORMHASH && $userStatus){
    
    $score_log_count = C::t('#tom_tongcheng#tom_tongcheng_score_log')->fetch_all_count(" AND user_id={$__UserInfo['id']} AND log_type=6 AND time_key={$nowDayTime} ");
    
    if($tongchengConfig['score_share_time'] > $score_log_count){
        $updateData = array();
        $updateData['score'] = $__UserInfo['score'] + $tongchengConfig['score_share_num'];
        C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);

        $insertData = array();
        $insertData['user_id']          = $__UserInfo['id'];
        $insertData['score_value']      = $tongchengConfig['score_share_num'];
        $insertData['old_value']        = $__UserInfo['score'];
        $insertData['log_type']         = 6;
        $insertData['log_time']         = TIMESTAMP;
        $insertData['time_key']         = $nowDayTime;
        C::t('#tom_tongcheng#tom_tongcheng_score_log')->insert($insertData);
        
        echo 200;exit;
    }
    echo 201;exit;
    
}else if($_GET['act'] == 'load_popup' && $_GET['formhash'] == FORMHASH){
    
    $outStr = '';
    
    $popupTmp = C::t('#tom_tongcheng#tom_tongcheng_popup')->fetch_all_by_site_ids(" AND status=1 " ," ORDER BY id DESC ",0,1,'['.$site_id.']' );
    if($popupTmp && !empty($popupTmp[0])){
        $cookies_popup = getcookie('tom_tongcheng_popup_'.$popupTmp[0]['id']);
        if(!empty($cookies_popup) && $cookies_popup==1){
            echo 201;exit;
        }else{
            
            DB::query("UPDATE ".DB::table('tom_tongcheng_popup')." SET clicks=clicks+1 WHERE id='{$popupTmp[0]['id']}' ", 'UNBUFFERED');
            
            $popupTmp[0]['link'] = str_replace("{site}",$site_id, $popupTmp[0]['link']);
            
            $outStr.= '<div id="popup_ads_animation" class="popup_ads_box">';
                $outStr.= '<h5>'.$popupTmp[0]['title'].'</h5>';
                $outStr.= '<div class="content">'.stripslashes($popupTmp[0]['content']).'</div>';
                $outStr.= '<div class="btn">';
                    $outStr.= '<a href="javascript:void(0);" class="no" onclick="closePopup('.$popupTmp[0]['id'].');">'.lang("plugin/tom_tongcheng", "popup_no_btn").'</a>';
                    $outStr.= '<a href="javascript:void(0);" class="ok" onclick="likePopup('.$popupTmp[0]['id'].',\''.$popupTmp[0]['link'].'\')" >'.lang("plugin/tom_tongcheng", "popup_ok_btn").'</a>';
                $outStr.= '</div>';
                $outStr.= '<div class="close" onclick="closePopup('.$popupTmp[0]['id'].');"></div>';
            $outStr.= '</div>';
            
            $outStr = diconv($outStr,CHARSET,'utf-8');
            echo json_encode($outStr); exit;
        }
    }
    
    
    echo 201;exit;
    
}else if($_GET['act'] == 'close_popup' && $_GET['formhash'] == FORMHASH){
    
    $popup_id = isset($_GET['popup_id'])? intval($_GET['popup_id']): 0;
    dsetcookie('tom_tongcheng_popup_'.$popup_id,1,86400);
    
    echo 201;exit;
    
}else if($_GET['act'] == 'hongbao_tz' && $_GET['formhash'] == FORMHASH && $userStatus){
    
    $new_hongbao_tz = 0;
    if($__UserInfo['hongbao_tz'] == 1){
        $new_hongbao_tz = 0;
    }else{
        $new_hongbao_tz = 1;
    }
    
    $updateData = array();
    $updateData['hongbao_tz'] = $new_hongbao_tz;
    C::t('#tom_tongcheng#tom_tongcheng_user')->update($__UserInfo['id'],$updateData);
    
    if($new_hongbao_tz == 1){
        echo 200;exit;
    }else{
        echo 100;exit;
    }
    
}else if($_GET['act'] == 'loadPmlist' && $_GET['formhash'] == FORMHASH && $userStatus){
    
    $outStr = '';
    
    $page           = intval($_GET['page'])>0? intval($_GET['page']):1;

    $pagesize       = 8;
    $start          = ($page - 1)*$pagesize;
    $pmListTmp = C::t('#tom_tongcheng#tom_tongcheng_pm')->fetch_all_list(" AND user_id={$__UserInfo['id']} "," ORDER BY last_time DESC,id DESC ",$start,$pagesize);
    $pmList = array();
    if(is_array($pmListTmp) && !empty($pmListTmp)){
        foreach ($pmListTmp as $key => $value){
            $pmListsTmp = C::t('#tom_tongcheng#tom_tongcheng_pm_lists')->fetch_by_id($value['pm_lists_id']);
            if($pmListsTmp['min_use_id'] == $__UserInfo['id']){
                $toUserInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($pmListsTmp['max_use_id']);
            }else{
                $toUserInfoTmp = C::t('#tom_tongcheng#tom_tongcheng_user')->fetch_by_id($pmListsTmp['min_use_id']);
            }
            if('NULL-NULL-NULL-NULL-NULL-NULL' != $pmListsTmp['last_content']){
                $pmList[$key] = $value;
                $pmList[$key]['last_content'] = dhtmlspecialchars($pmListsTmp['last_content']);
                $pmList[$key]['toUserInfo'] = $toUserInfoTmp;
            }else if($value['new_num']>0){
                DB::query("UPDATE ".DB::table('tom_tongcheng_pm')." SET new_num=0 WHERE user_id='{$__UserInfo['id']}' AND pm_lists_id='{$value['pm_lists_id']}' ", 'UNBUFFERED');
            }
        }
    }
    
    if(is_array($pmList) && !empty($pmList)){
        foreach ($pmList as $key => $val){
            $outStr.= '<section class="msg-list">';
               $outStr.= '<a href="plugin.php?id=tom_tongcheng&site='.$site_id.'&mod=message&act=sms&pm_lists_id='.$val['pm_lists_id'].'">';
                    $outStr.= '<section class="msg-list-pic">';
                         $outStr.= '<img src="'.$val['toUserInfo']['picurl'].'" />';
                    $outStr.= '</section>';
                    $outStr.= '<section class="msg-list-web">';
                         $outStr.= '<h3><span>'.dgmdate($val['last_time'], 'u','9999','m-d H:i').'</span>'.$val['toUserInfo']['nickname'].'&nbsp;</h3>';
                         $outStr.= '<p>';
                         if($val['new_num']>0){
                             $outStr.= '<i>'.$val['new_num'].'</i>';
                         }
                         $outStr.= ''.$val['last_content'];
                         $outStr.= '</p>';
                    $outStr.= '</section>';
               $outStr.= '</a>';
               $outStr.= '<section class="clear"></section>';
            $outStr.= '</section>';
        }
    }else{
        $outStr = '205';
    }
    
    $outStr = diconv($outStr,CHARSET,'utf-8');
    echo json_encode($outStr); exit;
    
}else{
    echo 'error';exit;
}
function time_tran($time ){
    $text = '';
    $time = $time === NULL || $time > time() ? time() : intval($time);
    $t = time() - $time; //时间差 （秒）
    $y = date('Y', $time)-date('Y', time());//是否跨年
    switch($t){
     case $t == 0:
       $text = '刚刚';
       break;
     case $t < 60:
      $text = $t . '秒前'; // 一分钟内
      break;
     case $t < 60 * 60:
      $text = floor($t / 60) . '分钟前'; //一小时内
      break;
     case $t < 60 * 60 * 24:
      $text = floor($t / (60 * 60)) . '小时前'; // 一天内
      break;
     case $t < 60 * 60 * 24 * 3:
      $text = floor($time/(60*60*24)) ==1 ?'昨天 ' . date('H:i', $time) : '前天 ' . date('H:i', $time) ; //昨天和前天
      break;
     case $t < 60 * 60 * 24 * 30:
      $text = date('m-d H:i', $time); //一个月内
      break;
     case $t < 60 * 60 * 24 * 365&&$y==0:
      $text = date('m-d', $time); //一年内
      break;
     default:
      $text = date('Y-m-d日-', $time); //一年以前
      break;
    }
        
    return $text;
 }

