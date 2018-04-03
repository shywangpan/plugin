<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_common_plugin extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'common_plugin';
		$this->_pk    = 'id';
	}

    public function fetch_by_identifier($identifier,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE identifier=%s", array($this->_table, $id));
	}
    
}



