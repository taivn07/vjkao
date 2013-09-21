<?php
class Shopping_Model_Invoice extends Zend_Db_Table{
	
	protected $_name = 'invoice';
	protected $_primary ='id';
	
	public function saveItem($arrParam = null, $options = null){
	
		if($options['task'] == 'public-order'){
			$row 				= $this->fetchNew();
			$row->full_name 	= $arrParam['full_name'];
			$row->email 		= $arrParam['email'];
			$row->phone 		= $arrParam['phone'];
			$row->address 		= $arrParam['address'];
			$row->shipping 		= $arrParam['shipping'];
			$row->comment 		= $arrParam['comment'];
			$row->created 		= @date("Y-m-d H:m:s");
			$row->status 		= 0;
			$row->id_user 		= $arrParam['id_user'];
	
			$id = $row->save();
		}
		return $id;
	}
	
	public function countItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		if($options == null){
			$select = $db->select()
			->from('invoice AS i', array('COUNT(i.id) AS totalItem'));
				
			$result = $db->fetchOne($select);
		}
		if($options['task'] == 'admin-on'){
			$select = $db->select()
			->from('invoice AS i', array('COUNT(i.id) AS totalItem'))
			->where('i.status = ?',1,INTERGER);
			
			$result = $db->fetchOne($select);
		}
		
		if($options['task'] == 'admin-off'){
			$select = $db->select()
			->from('invoice AS i', array('COUNT(i.id) AS totalItem'))
			->where('i.status = ?',0,INTERGER);
				
			$result = $db->fetchOne($select);
		}
		
		return $result;
	}
}