<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_tom_tongcheng_popup extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_tongcheng_popup';
		$this->_pk    = 'id';
	}

    public function fetch_by_id($id,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE id=%d ", array($this->_table, $id));
	}
    
    public function fetch_all_by_site_ids($condition,$orders = '',$start = 0,$limit = 10,$site_id = '') {
        $data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i AND site_ids LIKE %s $orders LIMIT $start,$limit",array($this->_table,$condition,'%'.$site_id.'%'));
		return $data;
	}
    
    public function fetch_all_list($condition,$orders = '',$start = 0,$limit = 10) {
		$data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
		return $data;
	}
    
    public function fetch_all_count($condition) {
        $return = DB::fetch_first("SELECT count(*) AS num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return['num'];
	}
    
    public function delete_by_id($id) {
		return DB::query("DELETE FROM %t WHERE id=%d", array($this->_table, $id));
	}

}

