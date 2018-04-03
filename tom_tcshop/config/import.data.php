<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$cateArr = array();

$cateArr[1] = array(
    'name' => '餐饮美食',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_1.png',
	'childs' => array('早点早餐','饭店餐厅','快餐外卖','烧烤麻辣','夜宵天地','火锅香辣','茶餐西餐','甜品饮料','零食特产','生鲜水果','海鲜肉类'),
);
$cateArr[2] = array(
    'name' => '休闲娱乐',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_2.png',
	'childs' => array('美容美发','游戏电玩','文体户外','汗蒸养生','网吧','游泳馆','时尚丽人','健身房','按摩推拿','足浴洗浴','咖啡厅','KTV','酒吧','电影院','茶馆'),
);
$cateArr[3] = array(
    'name' => '酒店旅游',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_3.png',
	'childs' => array('民宿客栈','星级酒店','旅游包车','商务宾馆','旅行社','旅游景点','农家乐','度假村'),
);
$cateArr[4] = array(
    'name' => '购物天地',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_4.png',
	'childs' => array('文具办公','美容护肤','数码科技','保健养生','服装鞋包','眼镜饰品','家用电器','手机专卖','户外运动','茗茶烟酒','珠宝钟表','鲜花礼品','商行超市','生鲜特产'),
);
$cateArr[5] = array(
    'name' => '生活服务',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_5.png',
	'childs' => array('的士/代驾','家政服务','送水站','宠物服务','开锁修锁','管道疏通','日常维修','二手回收','衣物洗护','搬家服务','快递服务','物流服务','其他服务'),
);
$cateArr[6] = array(
    'name' => '汽车服务',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_6.png',
	'childs' => array('摩托车/电动车','4S店','汽车美容','维修保养','驾校教练','汽配销售','保险信贷','汽车销售','汽车租赁'),
);
$cateArr[7] = array(
    'name' => '母婴专区',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_7.png',
	'childs' => array('儿童玩具','母婴食品','母婴用品','母婴健康','母婴教育','母婴服务'),
);
$cateArr[8] = array(
    'name' => '婚庆摄影',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_8.png',
	'childs' => array('跟拍跟妆','影视制作','儿童摄影','婚庆公司','庆典礼仪','婚车租赁','喜糖铺子','主持司仪','婚纱摄影'),
);
$cateArr[9] = array(
    'name' => '教育培训',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_9.png',
	'childs' => array('办公培训','特长培训','职业技能','家教辅导','幼儿园'),
);
$cateArr[10] = array(
    'name' => '家具建材',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_10.png',
	'childs' => array('木地板','家私家具','陶瓷卫浴','衣柜橱柜','油漆涂料','装修装饰','五金建材','水电管道','背景墙纸','家饰工艺','窗帘家纺','门窗灶炉','灯饰灯具','智能家具'),
);
$cateArr[11] = array(
    'name' => '房产相关',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_11.png',
	'childs' => array('新楼盘','房屋中介','房产评估'),
);
$cateArr[12] = array(
    'name' => '商务服务',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_12.png',
	'childs' => array('广告传媒','印刷包装','网络营销','法律咨询','工商注册','财务会计','设计策划','创业服务','软件服务'),
);
$cateArr[13] = array(
    'name' => '金融服务',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_13.png',
	'childs' => array('快速贷款','典当抵押','保险公司','POS机','投资公司','股票期货','综合金融','代还养卡'),
);
$cateArr[14] = array(
    'name' => '农林牧渔',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_14.png',
	'childs' => array('农作物','园林花卉','畜禽养殖'),
);
$cateArr[15] = array(
    'name' => '医疗健康',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_15.png',
	'childs' => array('口腔健康','药店药房','美容整形','医院诊所','健康体检'),
);
$cateArr[16] = array(
    'name' => '自定义',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_16.png',
	'childs' => array('自定义1','自定义2','自定义3','自定义4','自定义5'),
);

if (CHARSET == 'utf-8') {
    $cateArr = tom_iconv($cateArr,'gbk','utf-8');
}

