<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$modelArr = array();

$modelArr[1] = array(
    'name' => '˳�糵',
    'picurl' => 'source/plugin/tom_tongcheng/images/86z.png',
    'type' => array(
        1 => array(
            'name' => '���ҳ�',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(
                1 => array(
                    'type' => 3,
                    'name' => '�˳�ʱ��',
                ),
                2 => array(
                    'type' => 2,
                    'name' => '�˳�����',
                    'list' => array('1��','2��','3��','4��','5��','6��','7��','8��','9��','10������'),
                ),
                3 => array(
                    'type' => 1,
                    'name' => '������',
                ),
                4 => array(
                    'type' => 1,
                    'name' => 'Ŀ�ĵ�',
                ),
            ),
            'desc_title' => '����Ҫ��',
            'desc_content' => '��Ҫ����˵���ϳ�ʱ�䣬�ϳ��ص�ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array('�����','��С��','��ȫ��һ','���߸���','��̯·��','�������'),
        ),
        2 => array(
            'name' => '������',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(
                1 => array(
                    'type' => 3,
                    'name' => '�˳�ʱ��',
                ),
                2 => array(
                    'type' => 2,
                    'name' => '�ɳ�����',
                    'list' => array('1��','2��','3��','4��','5��','6��','7��','8��','9��','10������'),
                ),
                3 => array(
                    'type' => 1,
                    'name' => '������',
                ),
                4 => array(
                    'type' => 1,
                    'name' => 'Ŀ�ĵ�',
                ),
            ),
            'desc_title' => '����Ҫ��',
            'desc_content' => '��Ҫ����˵���ϳ�ʱ�䣬�ϳ��ص�ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array('��ʱ����','�г�����','������','��Ů��','�ܿ���','��Ů����'),
        ),
    ),
);


$modelArr[2] = array(
    'name' => '��ְ��Ƹ',
    'picurl' => 'source/plugin/tom_tongcheng/images/86x.png',
    'type' => array(
        1 => array(
            'name' => '��Ƹ',
            'cate_title' => '��Ƹ��ҵ',
            'cate' => array('�Ƶ����','������װ','��������','���۰ٻ�','��������','��������','��������','������ѵ','��洫ý','���ڱ���','ҽ�Ʊ���','������ҵ','����'),
            'attr' => array(),
            'desc_title' => 'ְλ����',
            'desc_content' => '��˵����Ƹ��λ����ְҪ�󡢹�˾���ܵȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array('����һ��','����','��ס','˫��','���˫н','ס������','���Ѳ���','��ͨ����','�ͷѲ���','�Ӱಹ��'),
        ),
        2 => array(
            'name' => '��ְ',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '��������',
            'desc_content' => '���˽��ܣ�����������ְλ�ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array('��ͨ��ǿ','ѧϰ��ǿ','ִ����ǿ','���׺���','��������','�ܳԿ�','���ʽ�̸','��ҵ����','��������','�����㷺'),
        ),
    ),
);

$modelArr[3] = array(
    'name' => '���ֳ�',
    'picurl' => 'source/plugin/tom_tongcheng/images/80o.png',
    'type' => array(
        1 => array(
            'name' => '����',
            'cate_title' => '��������',
            'cate' => array('�γ�','SUV','MPV','΢��','Ƥ��','�綯��','��������'),
            'attr' => array(),
            'desc_title' => '��������',
            'desc_content' => '����д�����ͺš��۸���̡�����ʱ��ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array('׼�³�','���¹�','��Ǩ���','���տɲ�','ά�޿ɲ�','ԭ������'),
        ),
        2 => array(
            'name' => '��',
            'cate_title' => '��������',
            'cate' => array('�γ�','SUV','MPV','΢��','Ƥ��','�綯��','��������'),
            'attr' => array(),
            'desc_title' => '��������',
            'desc_content' => '�������Գ�������Ʒ�ơ�����ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array('�н�����','�ɹ���','���¹�','���鳵','ȫ���'),
        ),
    ),
);

$modelArr[4] = array(
    'name' => '��������',
    'picurl' => 'source/plugin/tom_tongcheng/images/808.png',
    'type' => array(
        1 => array(
            'name' => '����',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '���ݽ���',
            'desc_content' => '���Ҽ�����������۸�װ�ޡ���ͨ���ܱ߻����ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array('��ʱ����','��ͨ����','����λ','������Ȧ','����ҽԺ','���ݷ�'),
        ),
        2 => array(
            'name' => '����',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '���ݽ���',
            'desc_content' => '���Ҽ�������������װ�ޡ���ͨ���ܱ߻����ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array('��ʱ����','��ͨ����','���Ҿ�','������ס'),
        ),
        3 => array(
            'name' => '����',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '���ݽ���',
            'desc_content' => '���͡������������ڡ���ͨ������Ҫ��ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array('�н�����','�����פ','������','��ͨ����','���ݷ�'),
        ),
        4 => array(
            'name' => '��',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '���ݽ���',
            'desc_content' => '���Ҽ����������λ�á���Ȩ���۸�ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array('�н�����','ȫ����','��ѧλ','��ͨ����','˫֤��ȫ','���ݷ�'),
        ),
    ),
);

$modelArr[5] = array(
    'name' => '����ת��',
    'picurl' => 'source/plugin/tom_tongcheng/images/809.png',
    'type' => array(
        1 => array(
            'name' => '����ת��',
            'cate_title' => '��ҵ',
            'cate' => array('�Ƶ����','��������','���۰ٻ�','�������','����ͨѶ','��������','ҽҩ����','�Ҿ߽���','������ѵ','��˾����','����'),
            'attr' => array(),
            'desc_title' => 'ת��˵��',
            'desc_content' => '����дת�����ݡ�ת��ʱ�䡢ת��ԭ��ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array('�����','Ӫҵ��','�ɿ�ת','֤����ȫ','�ٽ�����'),
        ),
    ),
);

$modelArr[6] = array(
    'name' => '���ط���',
    'picurl' => 'source/plugin/tom_tongcheng/images/86w.png',
    'type' => array(
        1 => array(
            'name' => '���ط���',
            'cate_title' => '��ҵ',
            'cate' => array('��������','��������','��������','ά�޷���','װ�޷���','������Ӱ','������ѵ','���ڷ���','���η���','���з���','�������','����'),
            'attr' => array(),
            'desc_title' => '�������',
            'desc_content' => '����д������ܵȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array(),
        ),
    ),
);

$modelArr[7] = array(
    'name' => '��Ʒ����',
    'picurl' => 'source/plugin/tom_tongcheng/images/80n.png',
    'type' => array(
        1 => array(
            'name' => '����',
            'cate_title' => '���',
            'cate' => array('���԰칫','�Ҿ߼ҵ�','�ֻ�','���廧��','��װ����','��ͯĸӤ','���ݱ���','�����Ʒ','�Ӽ��ճ�','��ͨ����','����'),
            'attr' => array(),
            'desc_title' => '��Ʒ����',
            'desc_content' => '���Ҫ˵�������Ʒ���ơ��۸񡢲����ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array('ȫ��δ��','����渶','��ݰ���','��������','ר����Ʒ','�з�Ʊ'),
        ),
        2 => array(
            'name' => '��',
            'cate_title' => '���',
            'cate' => array('���԰칫','�Ҿ߼ҵ�','�ֻ�','���廧��','��װ����','��ͯĸӤ','���ݱ���','�����Ʒ','�Ӽ��ճ�','��ͨ����','����'),
            'attr' => array(),
            'desc_title' => '��������',
            'desc_content' => '���Ҫ˵������Ҫ����Ʒ��Ϣ��Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array(),
        ),
    ),
);

$modelArr[8] = array(
    'name' => '�Ż���Ϣ',
    'picurl' => 'source/plugin/tom_tongcheng/images/80p.png',
    'type' => array(
        1 => array(
            'name' => '�Ż���Ϣ',
            'cate_title' => '������ҵ',
            'cate' => array('��ʳ','�Ƶ��ջ','��������','�������','����','����','����'),
            'attr' => array(),
            'desc_title' => '�Ż���Ϣ',
            'desc_content' => '���Ҫ˵���Ż����ݡ�ʱ�䡢�ص�ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array(),
        ),
    ),
);

$modelArr[9] = array(
    'name' => 'ũ������',
    'picurl' => 'source/plugin/tom_tongcheng/images/2wtb.png',
    'type' => array(
        1 => array(
            'name' => 'ũ������',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '������Ϣ',
            'desc_content' => '����д���ũ�������Ʒ������Ϣ�ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '��ֹ΢�̡�ˢ���ڴ���Ŀ����������һ��ɾ����',
            'tag' => array(),
        ),
    ),
);

$modelArr[10] = array(
    'name' => '�Զ���',
    'picurl' => 'source/plugin/tom_tongcheng/images/86y.png',
    'type' => array(
        1 => array(
            'name' => '�Զ������',
            'cate_title' => '',
            'cate' => array(),
            'attr' => array(),
            'desc_title' => '��Ʒ����',
            'desc_content' => '����д��Ĳ�Ʒ������ȵȣ�Ϊ�˱�����˽���벻Ҫ��д�ֻ��ź�QQ',
            'warning_msg' => '',
            'tag' => array(),
        ),
    ),
);

if (CHARSET == 'utf-8') {
    $modelArr = tom_iconv($modelArr,'gbk','utf-8');
}

