<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/*
 * ������create geotable��
 */
function lbsBaiduCreateGeotable($paramArr){
    $options = array(
        'name'              => '', // string(45) geotable������ ����ĸ��
        'geotype'           => 1,  // int32 geotable�������ݵ����� 1���㣻2���ߣ�3���档Ĭ��Ϊ1����ǰ��֧�֡��ߡ���
        'is_published'      => 1,  // int32 �Ƿ񷢲������� 0��δ�Զ��������Ƽ���  1���Զ��������Ƽ�����
        'ak'                => '', // string(50) �û��ķ���Ȩ��key
    );
    $data = array_merge($options, $paramArr);
    
    $apiUrl  = "http://api.map.baidu.com/geodata/v3/geotable/create";
    
    $content = lbsPostCurl($data, $apiUrl);
    
    if($content){
        $return = json_decode($content, true);
        $return = lbs_iconv_recurrence($return);
        return $return;
    }else{
        return false;
    }
    
}

/*
 * ��ѯ��list geotable��
 */
function lbsBaiduListGeotable($paramArr){
    $options = array(
        'name'              => '', // string(45) geotable������
        'ak'                => '', // string(50) �û��ķ���Ȩ��key
    );
    $data = array_merge($options, $paramArr);
    
    $apiUrl  = "http://api.map.baidu.com/geodata/v3/geotable/list"."?name=".$data['name']."&ak=".$data['ak'];
    
    $content = lbsGetHtml($apiUrl);
    
    if($content){
        $return = json_decode($content, true);
        $return = lbs_iconv_recurrence($return);
        return $return;
    }else{
        return false;
    }
}

/*
 * ɾ����geotable���ӿ�
 */
function lbsBaiduDeleteGeotable($paramArr){
    $options = array(
        'id'              => '', // uint32 ������
        'ak'              => '', // string(50) �û��ķ���Ȩ��key
    );
    $data = array_merge($options, $paramArr);
    
    $apiUrl  = "http://api.map.baidu.com/geodata/v3/geotable/delete";
    
    $content = lbsPostCurl($data, $apiUrl);
    
    if($content){
        $return = json_decode($content, true);
        $return = lbs_iconv_recurrence($return);
        return $return;
    }else{
        return false;
    }
}

/*
 * �����У�create column��
 */
function lbsBaiduCreateColumn($paramArr){
    $options = array(
        'name'                  => '',  // string(45) column��������������
        'key'                   => '',  // string(45) column�洢������key  ͬһ��geotable�ڵ����ֲ�����ͬ
        'type'                  => 1,   // uint32 �洢��ֵ������ ö��ֵ1:Int64, 2:double, 3:string, 4:����ͼƬurl
        'max_length'            => '',  // uint32 ��󳤶�  ���ֵ2048����СֵΪ1����typeΪstring���ֶ���Ч����ʱ���ֶα����ֵ����utf8�ĺ��ָ����������ֽڸ���
        'default_value'         => '',  // string(45) ����Ĭ��ֵ
        'is_sortfilter_field'   => 0,   // uint32 �Ƿ�����������ֵ����ɸѡ�ֶ� 1�����ǣ�0��������ú�������LBS�Ƽ���ʱ����Ը��ֶν������򡣸��ֶ�ֻ��Ϊint��double���ͣ��������15��
        'is_search_field'       => 0,   // uint32 �Ƿ����������ı������ֶ� 1����֧�֣�0Ϊ��֧�֡�ֻ��typeΪstringʱ�������ü����ֶΣ�ֻ�������ַ������͵�������󳤶Ȳ��ܳ���512���ֽ�
        'is_index_field'        => 0,   // uint32 �Ƿ�洢����������ֶ� ���ڴ洢�ӿڲ�ѯ:1����֧�֣�0Ϊ��֧�� ע��is_index_field=1 ʱ�����ڸ��ݸ�������ֵ����ʱ����������
        'is_unique_field'       => 0,   // uint32 �Ƿ��ƴ洢Ψһ�����ֶΣ�������£�ɾ������ѯ  ��ѡ��1�����ǣ�0��������ú������ݴ����͸���ʱ���и��ֶ�Ψһ�Լ�飬�������Դ��ֶ�Ϊ�����������ݵĸ��¡�ɾ���Ͳ�ѯ���������1��
        'geotable_id'           => 0,   // string(50) �����ڵ�geotable_id
        'ak'                    => ''   // string(50) �û��ķ���Ȩ��key
    );
    $data = array_merge($options, $paramArr);
    
    $apiUrl  = "http://api.map.baidu.com/geodata/v3/column/create";
    
    $content = lbsPostCurl($data, $apiUrl);
    
    if($content){
        $return = json_decode($content, true);
        $return = lbs_iconv_recurrence($return);
        return $return;
    }else{
        return false;
    }
}

/*
 * ��ѯ�У�list column��
 */
function lbsBaiduListColumn($paramArr){
    $options = array(
        'geotable_id'       => '', // string(50) �����ڵ�geotable_id
        'ak'                => '', // string �û��ķ���Ȩ��key
    );
    $data = array_merge($options, $paramArr);
    
    $apiUrl  = "http://api.map.baidu.com/geodata/v3/column/list"."?geotable_id=".$data['geotable_id']."&ak=".$data['ak'];
    
    $content = lbsGetHtml($apiUrl);
    
    if($content){
        $return = json_decode($content, true);
        $return = lbs_iconv_recurrence($return);
        return $return;
    }else{
        return false;
    }
}

/*
 * �������ݣ�create poi��
 */
function lbsBaiduCreatePoi($paramArr){
    $options = array(
        'title'                     => '',  // string(256) poi����
        'address'                   => '',  // string(256) ��ַ
        'tags'                      => '',  // string(256) tags
        'latitude'                  => '',  // double �û��ϴ���γ��
        'longitude'                 => '',  // double �û��ϴ��ľ���
        'coord_type'                => 3,   // uint32 �û��ϴ������������  1��GPS��γ������
        'geotable_id'               => 0,   // string(50) ��¼������geotable�ı�ʶ
        'ak'                        => ''   // string(50) �û��ķ���Ȩ��key
    );
    $data = array_merge($options, $paramArr);
    
    $apiUrl  = "http://api.map.baidu.com/geodata/v3/poi/create";
    
    $content = lbsPostCurl($data, $apiUrl);
    
    if($content){
        $return = json_decode($content, true);
        $return = lbs_iconv_recurrence($return);
        return $return;
    }else{
        return false;
    }
}

/*
 * ��ѯָ��id�����ݣ�poi������
 */
function lbsBaiduDetailPoi($paramArr){
    $options = array(
        'id'                => '', // uint64 poi����
        'geotable_id'       => '', // int32 �����ڵ�geotable_id
        'ak'                => '', // string(50) �û��ķ���Ȩ��key
    );
    $data = array_merge($options, $paramArr);
    
    $apiUrl  = "http://api.map.baidu.com/geodata/v3/poi/detail"."?id=".$data['id']."&geotable_id=".$data['geotable_id']."&ak=".$data['ak'];
    
    $content = lbsGetHtml($apiUrl);
    
    if($content){
        $return = json_decode($content, true);
        $return = lbs_iconv_recurrence($return);
        return $return;
    }else{
        return false;
    }
}

/*
 * �޸����ݣ�poi��
 */
function lbsBaiduUpdatePoi($paramArr){
    $options = array(
        'id'                        => '',  // uint64 poi����
        'title'                     => '',  // string(256) poi����
        'address'                   => '',  // string(256) ��ַ
        'tags'                      => '',  // string(256) tags
        'latitude'                  => '',  // double �û��ϴ���γ��
        'longitude'                 => '',  // double �û��ϴ��ľ���
        'coord_type'                => 3,   // uint32 �û��ϴ������������  1��GPS��γ������
        'geotable_id'               => 0,   // string(50) ��¼������geotable�ı�ʶ
        'ak'                        => ''   // string(50) �û��ķ���Ȩ��key
    );
    $data = array_merge($options, $paramArr);
    
    $apiUrl  = "http://api.map.baidu.com/geodata/v3/poi/update";
    
    $content = lbsPostCurl($data, $apiUrl);
    
    if($content){
        $return = json_decode($content, true);
        $return = lbs_iconv_recurrence($return);
        return $return;
    }else{
        return false;
    }
}

/*
 * ɾ�����ݣ�poi��
 */
function lbsBaiduDeletePoi($paramArr){
    $options = array(
        'id'                        => '',  // uint64 poi����
        'geotable_id'               => 0,   // string(50) ��¼������geotable�ı�ʶ
        'is_total_del'              => 0,   // int32 ���Ϊ����ɾ��
        'ak'                        => ''   // string(50) �û��ķ���Ȩ��key
    );
    $data = array_merge($options, $paramArr);
    
    $apiUrl  = "http://api.map.baidu.com/geodata/v3/poi/update";
    
    $content = lbsPostCurl($data, $apiUrl);
    
    if($content){
        $return = json_decode($content, true);
        $return = lbs_iconv_recurrence($return);
        return $return;
    }else{
        return false;
    }
}

/*
 * ����ɾ�����ݣ�poi��
 */
function lbsBaiduBatchDeletePoi($paramArr){
    $options = array(
        'ids'                        => '', // ��,�ָ���id ���1000��id, �������������ɾ��is_total_del = 1����û������id�ֶΣ������ȸ���idsɾ������poi, ����������������.
        'geotable_id'               => 0,   // string(50) ��¼������geotable�ı�ʶ
        'is_total_del'              => 1,   // int32 ���Ϊ����ɾ��
        'ak'                        => ''   // string(50) �û��ķ���Ȩ��key
    );
    $data = array_merge($options, $paramArr);
    
    $apiUrl  = "http://api.map.baidu.com/geodata/v3/poi/update";
    
    $content = lbsPostCurl($data, $apiUrl);
    
    if($content){
        $return = json_decode($content, true);
        $return = lbs_iconv_recurrence($return);
        return $return;
    }else{
        return false;
    }
}

/**
 * ���������������֮��ľ���
 * @param  Decimal $longitude1 ��㾭��
 * @param  Decimal $latitude1  ���γ��
 * @param  Decimal $longitude2 �յ㾭��
 * @param  Decimal $latitude2  �յ�γ��
 * @param  Int     $unit       ��λ 1:�� 2:����
 * @param  Int     $decimal    ���� ����С��λ��
 * @return Decimal
 */
function lbsGetDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=1, $decimal=0){

    $EARTH_RADIUS = 6370.996; // ����뾶ϵ��
    $PI = 3.1415926;

    $radLat1 = $latitude1 * $PI / 180.0;
    $radLat2 = $latitude2 * $PI / 180.0;

    $radLng1 = $longitude1 * $PI / 180.0;
    $radLng2 = $longitude2 * $PI / 180.0;

    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;

    $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
    $distance = $distance * $EARTH_RADIUS * 1000;

    if($unit==2){
        $distance = $distance / 1000;
    }

    return round($distance, $decimal);

}

function lbsGetHtml($url){
    if(function_exists('curl_init')){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $return = curl_exec($ch);
        curl_close($ch); 
        return $return;
    }
    return false;
}


function lbsPostCurl($data, $url, $second = 300)
{		
    $data = lbs_iconv_utf8($data);
    
    $ch = curl_init();
    //���ó�ʱ
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//�ϸ�У��
    //����header
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //Ҫ����Ϊ�ַ������������Ļ��
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //post�ύ��ʽ
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //����curl
    $data = curl_exec($ch);
    //���ؽ��
    if($data){
        curl_close($ch);
        return $data;
    } else { 
        $error = curl_errno($ch);
        curl_close($ch);
        return false;
    }
}

function lbs_iconv_recurrence($value) {
    if(is_array($value)) {
        foreach($value AS $key => $val) {
            $value[$key] = lbs_iconv_recurrence($val);
        }
    } else {
        $value = diconv($value, 'utf-8', CHARSET);
    }
    return $value;
}

function lbs_iconv_utf8($value) {
    if(is_array($value)) {
        foreach($value AS $key => $val) {
            $value[$key] = lbs_iconv_utf8($val);
        }
    } else {
        $value = diconv($value, CHARSET, 'utf-8');
    }
    return $value;
}