<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.cn
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_tongcheng_model_cate extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_tongcheng_model_cate';
		$this->_pk    = 'id';
	}

    public function fetch_by_id($id,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE id=%d", array($this->_table, $id));
	}
	
    public function fetch_all_list($condition,$orders = '',$start = 0,$limit = 10) {
		$data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
		return $data;
	}
    
    public function insert_id() {
		return DB::insert_id();
	}
    
    public function fetch_all_count($condition) {
        $return = DB::fetch_first("SELECT count(*) AS num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return['num'];
	}
	
	public function delete_by_id($id) {
		return DB::query("DELETE FROM %t WHERE id=%d", array($this->_table, $id));
	}
    
    public function delete_by_model_id($model_id) {
		return DB::query("DELETE FROM %t WHERE model_id=%d", array($this->_table, $model_id));
	}
    
    public function delete_by_type_id($type_id) {
		return DB::query("DELETE FROM %t WHERE type_id=%d", array($this->_table, $type_id));
	}

}



