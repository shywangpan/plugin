<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_tcshop extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_tcshop';
		$this->_pk    = 'id';
	}

    public function fetch_by_id($id,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE id=%d", array($this->_table, $id));
	}
	
    public function fetch_all_list($condition,$orders = '',$start = 0,$limit = 10,$name = '',$address = '',$tabs = '') {
        if(!empty($name)){
            $data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i AND name LIKE %s $orders LIMIT $start,$limit",array($this->_table,$condition,'%'.$name.'%'));
        }else if(!empty($address)){
            $data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i AND address LIKE %s $orders LIMIT $start,$limit",array($this->_table,$condition,'%'.$address.'%'));
        }else if(!empty($tabs)){
            $data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i AND tabs LIKE %s $orders LIMIT $start,$limit",array($this->_table,$condition,'%'.$tabs.'%'));
        }else{
            $data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
        }
		return $data;
	}
    
    public function insert_id() {
		return DB::insert_id();
	}
    
    public function fetch_all_count($condition,$name = '',$address = '',$tabs = '') {
        if(!empty($name)){
            $return = DB::fetch_first("SELECT count(*) AS num FROM %t WHERE 1 %i AND name LIKE %s ",array($this->_table,$condition,'%'.$name.'%'));
        }else if(!empty($address)){
            $return = DB::fetch_first("SELECT count(*) AS num FROM %t WHERE 1 %i AND address LIKE %s ",array($this->_table,$condition,'%'.$address.'%'));
        }else if(!empty($tabs)){
            $return = DB::fetch_first("SELECT count(*) AS num FROM %t WHERE 1 %i AND tabs LIKE %s ",array($this->_table,$condition,'%'.$tabs.'%'));
        }else{
            $return = DB::fetch_first("SELECT count(*) AS num FROM %t WHERE 1 %i ",array($this->_table,$condition));
        }
		return $return['num'];
	}
	
	public function delete_by_id($id) {
		return DB::query("DELETE FROM %t WHERE id=%d", array($this->_table, $id));
	}

}



