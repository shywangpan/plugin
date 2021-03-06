<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_mandou_shop_cat extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'mandou_shop_cat';
		$this->_pk    = 'id';
	}
	public function delete($ids) {
		return DB::query("delete from %t where " . DB::field("id", $ids), array($this->_table));
		
	}
    public function getCount($where){
		$rs = DB::fetch_first("SELECT count(*) as num FROM ".DB::table($this->_table)."  a where 1 ".$where);
    	return $rs['num'];
    }
    public function  getList(){
   		return DB::fetch_all("SELECT * FROM %t order by  id desc" , array($this->_table));
    }
	public function  getListWhere(){
   		$sql = "SELECT * FROM ".DB::table($this->_table)."  where 1 order by id desc ";
		return DB::fetch_all($sql);
    }
    public function  getListPage($where , $page , $perpage){
        $sql = "SELECT a.* FROM ".DB::table($this->_table)." a  where 1 ".$where.' order by a.id desc limit '.(($page - 1) * $perpage).','.$perpage;
        return $datalist = DB::fetch_all($sql);
    }
	public function insert($data){
    	return  DB::insert($this->_table , $data);
    }
	public function update($data , $hid){
    	return  DB::update($this->_table , $data , array($this->_pk => $hid));
    }
    public function getRow($id){
    	$data = DB::fetch_first("SELECT * FROM %t WHERE id=".$id,array($this->_table));
		return $data;
    }

}


?>
