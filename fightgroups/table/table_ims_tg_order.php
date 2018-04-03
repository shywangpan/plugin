<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_ims_tg_order extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'ims_tg_order';
		$this->_pk    = 'id';
	}

    public function insert_($data){
    	return  DB::insert($this->_table , $data);
    }
	public function delete_($id , $col = 'id') {
		return DB::query("DELETE FROM %t WHERE $col=$id", array($this->_table));
	}
   
    public function getCount($where = ''){
    	$rs = DB::fetch_first("select count(*) as sn from %t  where 1 $where" , array($this->_table));
    	return $rs['sn'];
    }
    public function  getList($where = '' , $orderby = '', $start = 0 , $end = 10000){
   		return  DB::fetch_all("SELECT * FROM %t WHERE 1 $where %i limit $start,$end " , array($this->_table  , $orderby));
    }
    function getRow($id){
    	return DB::fetch_first("select * from %t where id=".$id , array($this->_table));
    }
    public function update_($data , $condition){
    	return DB::update($this->_table , $data , $condition);
    }
}


?>
