<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/*
 * 创建表（create geotable）
 */
function lbsBaiduCreateGeotable($paramArr){
    $options = array(
        'name'              => '', // string(45) geotable的名称 （字母）
        'geotype'           => 1,  // int32 geotable持有数据的类型 1：点；2：线；3：面。默认为1（当前不支持“线”）
        'is_published'      => 1,  // int32 是否发布到检索 0：未自动发布到云检索  1：自动发布到云检索；
        'ak'                => '', // string(50) 用户的访问权限key
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
 * 查询表（list geotable）
 */
function lbsBaiduListGeotable($paramArr){
    $options = array(
        'name'              => '', // string(45) geotable的名字
        'ak'                => '', // string(50) 用户的访问权限key
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
 * 删除表（geotable）接口
 */
function lbsBaiduDeleteGeotable($paramArr){
    $options = array(
        'id'              => '', // uint32 表主键
        'ak'              => '', // string(50) 用户的访问权限key
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
 * 创建列（create column）
 */
function lbsBaiduCreateColumn($paramArr){
    $options = array(
        'name'                  => '',  // string(45) column的属性中文名称
        'key'                   => '',  // string(45) column存储的属性key  同一个geotable内的名字不能相同
        'type'                  => 1,   // uint32 存储的值的类型 枚举值1:Int64, 2:double, 3:string, 4:在线图片url
        'max_length'            => '',  // uint32 最大长度  最大值2048，最小值为1。当type为string该字段有效，此时该字段必填。此值代表utf8的汉字个数，不是字节个数
        'default_value'         => '',  // string(45) 设置默认值
        'is_sortfilter_field'   => 0,   // uint32 是否检索引擎的数值排序筛选字段 1代表是，0代表否。设置后，在请求LBS云检索时可针对该字段进行排序。该字段只能为int或double类型，最多设置15个
        'is_search_field'       => 0,   // uint32 是否检索引擎的文本检索字段 1代表支持，0为不支持。只有type为string时可以设置检索字段，只能用于字符串类型的列且最大长度不能超过512个字节
        'is_index_field'        => 0,   // uint32 是否存储引擎的索引字段 用于存储接口查询:1代表支持，0为不支持 注：is_index_field=1 时才能在根据该列属性值检索时检索到数据
        'is_unique_field'       => 0,   // uint32 是否云存储唯一索引字段，方便更新，删除，查询  可选，1代表是，0代表否。设置后将在数据创建和更新时进行该字段唯一性检查，并可以以此字段为条件进行数据的更新、删除和查询。最多设置1个
        'geotable_id'           => 0,   // string(50) 所属于的geotable_id
        'ak'                    => ''   // string(50) 用户的访问权限key
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
 * 查询列（list column）
 */
function lbsBaiduListColumn($paramArr){
    $options = array(
        'geotable_id'       => '', // string(50) 所属于的geotable_id
        'ak'                => '', // string 用户的访问权限key
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
 * 创建数据（create poi）
 */
function lbsBaiduCreatePoi($paramArr){
    $options = array(
        'title'                     => '',  // string(256) poi名称
        'address'                   => '',  // string(256) 地址
        'tags'                      => '',  // string(256) tags
        'latitude'                  => '',  // double 用户上传的纬度
        'longitude'                 => '',  // double 用户上传的经度
        'coord_type'                => 3,   // uint32 用户上传的坐标的类型  1：GPS经纬度坐标
        'geotable_id'               => 0,   // string(50) 记录关联的geotable的标识
        'ak'                        => ''   // string(50) 用户的访问权限key
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
 * 查询指定id的数据（poi）详情
 */
function lbsBaiduDetailPoi($paramArr){
    $options = array(
        'id'                => '', // uint64 poi主键
        'geotable_id'       => '', // int32 所属于的geotable_id
        'ak'                => '', // string(50) 用户的访问权限key
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
 * 修改数据（poi）
 */
function lbsBaiduUpdatePoi($paramArr){
    $options = array(
        'id'                        => '',  // uint64 poi主键
        'title'                     => '',  // string(256) poi名称
        'address'                   => '',  // string(256) 地址
        'tags'                      => '',  // string(256) tags
        'latitude'                  => '',  // double 用户上传的纬度
        'longitude'                 => '',  // double 用户上传的经度
        'coord_type'                => 3,   // uint32 用户上传的坐标的类型  1：GPS经纬度坐标
        'geotable_id'               => 0,   // string(50) 记录关联的geotable的标识
        'ak'                        => ''   // string(50) 用户的访问权限key
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
 * 删除数据（poi）
 */
function lbsBaiduDeletePoi($paramArr){
    $options = array(
        'id'                        => '',  // uint64 poi主键
        'geotable_id'               => 0,   // string(50) 记录关联的geotable的标识
        'is_total_del'              => 0,   // int32 标记为批量删除
        'ak'                        => ''   // string(50) 用户的访问权限key
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
 * 批量删除数据（poi）
 */
function lbsBaiduBatchDeletePoi($paramArr){
    $options = array(
        'ids'                        => '', // 以,分隔的id 最多1000个id, 如果设置了批量删除is_total_del = 1并且没有设置id字段，则优先根据ids删除多条poi, 其它条件将被忽略.
        'geotable_id'               => 0,   // string(50) 记录关联的geotable的标识
        'is_total_del'              => 1,   // int32 标记为批量删除
        'ak'                        => ''   // string(50) 用户的访问权限key
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
 * 计算两点地理坐标之间的距离
 * @param  Decimal $longitude1 起点经度
 * @param  Decimal $latitude1  起点纬度
 * @param  Decimal $longitude2 终点经度
 * @param  Decimal $latitude2  终点纬度
 * @param  Int     $unit       单位 1:米 2:公里
 * @param  Int     $decimal    精度 保留小数位数
 * @return Decimal
 */
function lbsGetDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=1, $decimal=0){

    $EARTH_RADIUS = 6370.996; // 地球半径系数
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
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
    //设置header
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //post提交方式
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //运行curl
    $data = curl_exec($ch);
    //返回结果
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