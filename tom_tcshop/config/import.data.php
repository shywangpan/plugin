<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$cateArr = array();

$cateArr[1] = array(
    'name' => '������ʳ',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_1.png',
	'childs' => array('������','�������','�������','�տ�����','ҹ�����','�������','�������','��Ʒ����','��ʳ�ز�','����ˮ��','��������'),
);
$cateArr[2] = array(
    'name' => '��������',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_2.png',
	'childs' => array('��������','��Ϸ����','���廧��','��������','����','��Ӿ��','ʱ������','����','��Ħ����','��ԡϴԡ','������','KTV','�ư�','��ӰԺ','���'),
);
$cateArr[3] = array(
    'name' => '�Ƶ�����',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_3.png',
	'childs' => array('���޿�ջ','�Ǽ��Ƶ�','���ΰ���','�������','������','���ξ���','ũ����','�ȼٴ�'),
);
$cateArr[4] = array(
    'name' => '�������',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_4.png',
	'childs' => array('�ľ߰칫','���ݻ���','����Ƽ�','��������','��װЬ��','�۾���Ʒ','���õ���','�ֻ�ר��','�����˶�','�����̾�','�鱦�ӱ�','�ʻ���Ʒ','���г���','�����ز�'),
);
$cateArr[5] = array(
    'name' => '�������',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_5.png',
	'childs' => array('��ʿ/����','��������','��ˮվ','�������','��������','�ܵ���ͨ','�ճ�ά��','���ֻ���','����ϴ��','��ҷ���','��ݷ���','��������','��������'),
);
$cateArr[6] = array(
    'name' => '��������',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_6.png',
	'childs' => array('Ħ�г�/�綯��','4S��','��������','ά�ޱ���','��У����','��������','�����Ŵ�','��������','��������'),
);
$cateArr[7] = array(
    'name' => 'ĸӤר��',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_7.png',
	'childs' => array('��ͯ���','ĸӤʳƷ','ĸӤ��Ʒ','ĸӤ����','ĸӤ����','ĸӤ����'),
);
$cateArr[8] = array(
    'name' => '������Ӱ',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_8.png',
	'childs' => array('���ĸ�ױ','Ӱ������','��ͯ��Ӱ','���칫˾','�������','�鳵����','ϲ������','����˾��','��ɴ��Ӱ'),
);
$cateArr[9] = array(
    'name' => '������ѵ',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_9.png',
	'childs' => array('�칫��ѵ','�س���ѵ','ְҵ����','�ҽ̸���','�׶�԰'),
);
$cateArr[10] = array(
    'name' => '�Ҿ߽���',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_10.png',
	'childs' => array('ľ�ذ�','��˽�Ҿ�','�մ���ԡ','�¹����','����Ϳ��','װ��װ��','��𽨲�','ˮ��ܵ�','����ǽֽ','���ι���','�����ҷ�','�Ŵ���¯','���εƾ�','���ܼҾ�'),
);
$cateArr[11] = array(
    'name' => '�������',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_11.png',
	'childs' => array('��¥��','�����н�','��������'),
);
$cateArr[12] = array(
    'name' => '�������',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_12.png',
	'childs' => array('��洫ý','ӡˢ��װ','����Ӫ��','������ѯ','����ע��','������','��Ʋ߻�','��ҵ����','�������'),
);
$cateArr[13] = array(
    'name' => '���ڷ���',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_13.png',
	'childs' => array('���ٴ���','�䵱��Ѻ','���չ�˾','POS��','Ͷ�ʹ�˾','��Ʊ�ڻ�','�ۺϽ���','��������'),
);
$cateArr[14] = array(
    'name' => 'ũ������',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_14.png',
	'childs' => array('ũ����','԰�ֻ���','������ֳ'),
);
$cateArr[15] = array(
    'name' => 'ҽ�ƽ���',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_15.png',
	'childs' => array('��ǻ����','ҩ��ҩ��','��������','ҽԺ����','�������'),
);
$cateArr[16] = array(
    'name' => '�Զ���',
    'picurl' => 'source/plugin/tom_tcshop/images/shop_nav_16.png',
	'childs' => array('�Զ���1','�Զ���2','�Զ���3','�Զ���4','�Զ���5'),
);

if (CHARSET == 'utf-8') {
    $cateArr = tom_iconv($cateArr,'gbk','utf-8');
}

