<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$modelArr = array();

$modelArr[1] = array(
    'name' => '顺风车',
    'picurl' => 'source/plugin/tom_tongcheng/images/86z.png',
    'type' => array(
        1 => array(
            'name' => '人找车',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(
                1 => array(
                    'type' => 3,
                    'name' => '乘车时间',
                ),
                2 => array(
                    'type' => 2,
                    'name' => '乘车人数',
                    'list' => array('1人','2人','3人','4人','5人','6人','7人','8人','9人','10人以上'),
                ),
                3 => array(
                    'type' => 1,
                    'name' => '出发地',
                ),
                4 => array(
                    'type' => 1,
                    'name' => '目的地',
                ),
            ),
            'desc_title' => '其他要求',
            'desc_content' => '简要补充说明上车时间，上车地点等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array('需后备箱','有小孩','安全第一','需走高速','分摊路费','货物代运'),
        ),
        2 => array(
            'name' => '车找人',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(
                1 => array(
                    'type' => 3,
                    'name' => '乘车时间',
                ),
                2 => array(
                    'type' => 2,
                    'name' => '可乘人数',
                    'list' => array('1人','2人','3人','4人','5人','6人','7人','8人','9人','10人以上'),
                ),
                3 => array(
                    'type' => 1,
                    'name' => '出发地',
                ),
                4 => array(
                    'type' => 1,
                    'name' => '目的地',
                ),
            ),
            'desc_title' => '其他要求',
            'desc_content' => '简要补充说明上车时间，上车地点等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array('按时发车','有偿乘坐','限男性','限女性','能开车','男女不限'),
        ),
    ),
);


$modelArr[2] = array(
    'name' => '求职招聘',
    'picurl' => 'source/plugin/tom_tongcheng/images/86x.png',
    'type' => array(
        1 => array(
            'name' => '招聘',
            'cate_title' => '招聘行业',
            'cate' => array('酒店餐饮','建筑家装','娱乐休闲','零售百货','家政保安','汽车服务','物流运输','教育培训','广告传媒','金融保险','医疗保健','厂矿企业','其他'),
            'attr' => array(),
            'desc_title' => '职位描述',
            'desc_content' => '请说明招聘岗位、任职要求、公司介绍等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array('五险一金','包吃','包住','双休','年底双薪','住房补助','话费补助','交通补助','餐费补助','加班补助'),
        ),
        2 => array(
            'name' => '求职',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '自我评价',
            'desc_content' => '个人介绍，工作经历、职位等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array('沟通力强','学习力强','执行力强','有亲和力','有责任心','能吃苦','开朗健谈','创业经历','沉稳内敛','人脉广泛'),
        ),
    ),
);

$modelArr[3] = array(
    'name' => '二手车',
    'picurl' => 'source/plugin/tom_tongcheng/images/80o.png',
    'type' => array(
        1 => array(
            'name' => '出售',
            'cate_title' => '车辆级别',
            'cate' => array('轿车','SUV','MPV','微面','皮卡','电动车','其他车型'),
            'attr' => array(),
            'desc_title' => '车辆描述',
            'desc_content' => '请填写车辆型号、价格、里程、上牌时间等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array('准新车','无事故','可迁外地','保险可查','维修可查','原厂保修'),
        ),
        2 => array(
            'name' => '求购',
            'cate_title' => '车辆级别',
            'cate' => array('轿车','SUV','MPV','微面','皮卡','电动车','其他车型'),
            'attr' => array(),
            'desc_title' => '车辆描述',
            'desc_content' => '请介绍你对车的需求、品牌、车龄等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array('中介勿扰','可过户','无事故','可验车','全款交易'),
        ),
    ),
);

$modelArr[4] = array(
    'name' => '房屋租售',
    'picurl' => 'source/plugin/tom_tongcheng/images/808.png',
    'type' => array(
        1 => array(
            'name' => '出售',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '房屋介绍',
            'desc_content' => '几室几厅、面积、价格、装修、交通、周边环境等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array('随时看房','交通便利','带车位','靠近商圈','靠近医院','电梯房'),
        ),
        2 => array(
            'name' => '出租',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '房屋介绍',
            'desc_content' => '几室几厅、面积、租金、装修、交通、周边环境等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array('随时看房','交通便利','带家具','马上入住'),
        ),
        3 => array(
            'name' => '求租',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '房屋介绍',
            'desc_content' => '户型、面积、租金、租期、交通、特殊要求等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array('中介勿扰','拎包入驻','带厨卫','交通便利','电梯房'),
        ),
        4 => array(
            'name' => '求购',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '房屋介绍',
            'desc_content' => '几室几厅、面积、位置、产权、价格等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array('中介勿扰','全款买房','带学位','交通便利','双证齐全','电梯房'),
        ),
    ),
);

$modelArr[5] = array(
    'name' => '生意转让',
    'picurl' => 'source/plugin/tom_tongcheng/images/809.png',
    'type' => array(
        1 => array(
            'name' => '生意转让',
            'cate_title' => '行业',
            'cate' => array('酒店餐饮','娱乐休闲','零售百货','生活服务','电子通讯','汽车美容','医药保健','家具建材','教育培训','公司工厂','其他'),
            'attr' => array(),
            'desc_title' => '转让说明',
            'desc_content' => '请填写转让内容、转让时间、转让原因等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array('低租金','营业中','可空转','证照齐全','临街铺面'),
        ),
    ),
);

$modelArr[6] = array(
    'name' => '本地服务',
    'picurl' => 'source/plugin/tom_tongcheng/images/86w.png',
    'type' => array(
        1 => array(
            'name' => '本地服务',
            'cate_title' => '行业',
            'cate' => array('家政服务','物流服务','汽车服务','维修服务','装修服务','婚庆摄影','教育培训','金融服务','旅游服务','休闲服务','商务服务','其他'),
            'attr' => array(),
            'desc_title' => '服务介绍',
            'desc_content' => '请填写服务介绍等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array(),
        ),
    ),
);

$modelArr[7] = array(
    'name' => '物品买卖',
    'picurl' => 'source/plugin/tom_tongcheng/images/80n.png',
    'type' => array(
        1 => array(
            'name' => '出售',
            'cate_title' => '类别',
            'cate' => array('电脑办公','家具家电','手机','文体户外','服装配饰','儿童母婴','美容保健','数码产品','居家日常','交通工具','其他'),
            'attr' => array(),
            'desc_title' => '物品描述',
            'desc_content' => '请简要说明你的物品名称、价格、参数等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array('全新未用','验货面付','快递包邮','保修期内','专柜正品','有发票'),
        ),
        2 => array(
            'name' => '求购',
            'cate_title' => '类别',
            'cate' => array('电脑办公','家具家电','手机','文体户外','服装配饰','儿童母婴','美容保健','数码产品','居家日常','交通工具','其他'),
            'attr' => array(),
            'desc_title' => '需求描述',
            'desc_content' => '请简要说明你需要的物品信息，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array(),
        ),
    ),
);

$modelArr[8] = array(
    'name' => '优惠信息',
    'picurl' => 'source/plugin/tom_tongcheng/images/80p.png',
    'type' => array(
        1 => array(
            'name' => '优惠信息',
            'cate_title' => '所在行业',
            'cate' => array('美食','酒店客栈','休闲娱乐','生活服务','购物','旅游','其他'),
            'attr' => array(),
            'desc_title' => '优惠信息',
            'desc_content' => '请简要说明优惠内容、时间、地点等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array(),
        ),
    ),
);

$modelArr[9] = array(
    'name' => '农林牧渔',
    'picurl' => 'source/plugin/tom_tongcheng/images/2wtb.png',
    'type' => array(
        1 => array(
            'name' => '农林牧渔',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '供求信息',
            'desc_content' => '请填写你的农林牧渔产品供求信息等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '禁止微商、刷单在此栏目发布，否则一率删除。',
            'tag' => array(),
        ),
    ),
);

$modelArr[10] = array(
    'name' => '自定义',
    'picurl' => 'source/plugin/tom_tongcheng/images/86y.png',
    'type' => array(
        1 => array(
            'name' => '自定义类别',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '产品服务',
            'desc_content' => '请填写你的产品、服务等等，为了保护隐私，请不要填写手机号和QQ',
            'warning_msg' => '',
            'tag' => array(),
        ),
    ),
);

if (CHARSET == 'utf-8') {
    $modelArr = tom_iconv($modelArr,'gbk','utf-8');
}

