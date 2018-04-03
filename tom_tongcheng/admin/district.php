<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

set_time_limit(0);

$modBaseUrl = $adminBaseUrl.'&tmod=district';
$modListUrl = $adminListUrl.'&tmod=district';
$modFromUrl = $adminFromUrl.'&tmod=district';

$values = array(intval($_GET['cid']), intval($_GET['aid']), intval($_GET['sid']));
$elems = array($_GET['city'], $_GET['area'], $_GET['street']);
$level = 1;
$upids = array(0);
$theid = 0;
for($i=0;$i<3;$i++) {
	if(!empty($values[$i])) {
		$theid = intval($values[$i]);
		$upids[] = $theid;
		$level++;
	} else {
		for($j=$i; $j<3; $j++) {
			$values[$j] = '';
		}
		break;
	}
}

if(submitcheck('editsubmit')) {

	$delids = array();
	foreach(C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($theid) as $value) {
		if(!isset($_POST['district'][$value['id']])) {
			$delids[] = $value['id'];
		} elseif($_POST['district'][$value['id']] != $value['name'] || $_POST['displayorder'][$value['id']] != $value['displayorder']) {
			C::t('#tom_tongcheng#tom_tongcheng_district')->update($value['id'], array('name'=>$_POST['district'][$value['id']], 'displayorder'=>$_POST['displayorder'][$value['id']]));
		}
	}
	if($delids) {
		$ids = $delids;
		for($i=$level; $i<4; $i++) {
			$ids = array();
			foreach(C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($delids) as $value) {
				$value['id'] = intval($value['id']);
				$delids[] = $value['id'];
				$ids[] = $value['id'];
			}
			if(empty($ids)) {
				break;
			}
		}
		C::t('#tom_tongcheng#tom_tongcheng_district')->delete($delids);
	}
	if(!empty($_POST['districtnew'])) {
		$inserts = array();
		$displayorder = '';
		foreach($_POST['districtnew'] as $key => $value) {
			$displayorder = trim($_POST['districtnew_order'][$key]);
			$value = trim($value);
			if(!empty($value)) {
				C::t('#tom_tongcheng#tom_tongcheng_district')->insert(array('name' => $value, 'level' => $level, 'upid' => $theid, 'displayorder' => $displayorder));
			}
		}
	}
	cpmsg('setting_district_edit_success', $modListUrl.'&cid='.$values[0].'&aid='.$values[1].'&sid='.$values[2], 'succeed');

}else{
	showtips('district_tips');
    
	showformheader($modFromUrl.'&cid='.$values[0].'&aid='.$values[1].'&sid='.$values[2]);
	showtableheader();

	$options = array(1=>array(), 2=>array(), 3=>array());
	$thevalues = array();
	foreach(C::t('#tom_tongcheng#tom_tongcheng_district')->fetch_all_by_upid($upids) as $value) {
		$options[$value['level']][] = array($value['id'], $value['name']);
		if($value['upid'] == $theid) {
			$thevalues[] = array($value['id'], $value['name'], $value['displayorder'], $value['usetype']);
		}
	}

	$names = array('city', 'area', 'street');
	for($i=0; $i<3;$i++) {
		$elems[$i] = !empty($elems[$i]) ? $elems[$i] : $names[$i];
	}
	$html = '';
	for($i=0;$i<3;$i++) {
		$l = $i+1;
		$jscall = ($i == 0 ? 'this.form.area.value=\'\';this.form.street.value=\'\';' : '')."refreshdistrict('$elems[0]', '$elems[1]', '$elems[2]')";
		$html .= '<select name="'.$elems[$i].'" id="'.$elems[$i].'" onchange="'.$jscall.'">';
		$html .= '<option value="">'.$Lang['district_level_'.$l].'</option>';
		foreach($options[$l] as $option) {
			$selected = $option[0] == $values[$i] ? ' selected="selected"' : '';
			$html .= '<option value="'.$option[0].'"'.$selected.'>'.$option[1].'</option>';
		}
		$html .= '</select>&nbsp;&nbsp;';
	}
	echo cplang('district_choose').' &nbsp; '.$html;
    
    $titleName = "name";
    $showId = 0;
    if(isset($_GET['sid']) && !empty($_GET['sid'])){
        $titleName = $Lang['district_level_name'];
    }else if(isset($_GET['cid']) && !empty($_GET['cid']) && isset($_GET['aid']) && !empty($_GET['aid'])){
        $titleName = $Lang['district_level_3'];
    }else if(isset($_GET['cid']) && !empty($_GET['cid'])){
        $titleName = $Lang['district_level_2'];
    }else{
        $showId = 1;
        $titleName = $Lang['district_level_1'];
    }
    
    if($showId == 1){
        showsubtitle(array('', 'display_order',$titleName,$Lang['district_id'], 'operation'));
    }else{
        showsubtitle(array('', 'display_order',$titleName, 'operation'));
    }
	
	foreach($thevalues as $value) {
		$valarr = array();
		$valarr[] = '';
		$valarr[] = '<input type="text" id="displayorder_'.$value[0].'" class="txt" name="displayorder['.$value[0].']" value="'.$value[2].'"/>';
		$valarr[] = '<p id="p_'.$value[0].'"><input type="text" id="input_'.$value[0].'" class="txt" name="district['.$value[0].']" value="'.$value[1].'"/></p>';
        if($showId == 1){
            $valarr[] = '<font color="#fd0d0d">'.$value[0].'</font>';
        }
		$valarr[] = '<a href="javascript:;" onclick="deletedistrict('.$value[0].');return false;">'.cplang('delete').'</a>';
		showtablerow('id="td_'.$value[0].'"', array('', 'class="td25"','','','',''), $valarr);
	}
	showtablerow('', array('colspan=2'), array(
			'<div><a href="javascript:;" onclick="addrow(this, 0, 1);return false;" class="addtr">'.cplang('add').'</a></div>'
		));
	showsubmit('editsubmit', 'submit');
	$adminurl = $adminBaseUrl.'&tmod=district';
echo <<<SCRIPT
<script type="text/javascript">
var rowtypedata = [
	[[1,'', ''],[1,'<input type="text" class="txt" name="districtnew_order[]" value="0" />', 'td25'],[2,'<input type="text" class="txt" name="districtnew[]" value="" />', '']],
];

function refreshdistrict(city, area, street) {
	location.href = "$adminurl"
		+ "&city="+city+"&area="+area+"&street="+street
		+"&cid="+$(city).value + "&aid="+$(area).value+"&sid="+$(street).value;
}

function editdistrict(did) {
	$('input_' + did).style.display = "block";
	$('span_' + did).style.display = "none";
}

function deletedistrict(did) {
	var elem = $('p_' + did);
	elem.parentNode.removeChild(elem);
	var elem = $('td_' + did);
	elem.parentNode.removeChild(elem);
}
</script>
SCRIPT;
	showtablefooter();
	showformfooter();
}

