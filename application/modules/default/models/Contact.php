<?php
class Default_Model_Contact extends Zend_Db_Table{
	
	protected $_name = 'contact';
	protected $_primary ='id';
	
	protected $_ids;
	
	public function getItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('contact AS c')
			->where('c.id = ?', $arrParam['id'], INTEGER);
		
			$result = $db->fetchRow($select);
		}
		
		if($options['task'] == 'public-contact'){
			$select = $db->select()
			->from('blocks AS b')
			->where('b.id = ?', 3, INTERGER);
			$result = $db->fetchRow($select);
		}
		return $result;
	}
	
	public function countItem($arrParam = null, $options = null){
		$ssFilter  = $arrParam['ssFilter'];
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		if($options['task'] == 'admin-list'){
				
			$select = $db->select()
			->from('contact AS c', array('COUNT(c.id) AS totalItem'));
				
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('c.name LIKE ?', $keywords, STRING)
						->orWhere('c.address LIKE ?', $keywords, STRING)
						->orWhere('c.tel LIKE ?', $keywords, STRING)
						->orWhere('c.fax LIKE ?', $keywords, STRING)
						->orWhere('c.phone LIKE ?', $keywords, STRING)
						->orWhere('c.email LIKE ?', $keywords, STRING)
						->orWhere('c.created LIKE ?', $keywords, STRING)
						->orWhere('c.ip LIKE ?', $keywords, STRING);
			}
				
			if(!empty($ssFilter['lang_code'])){
				$select->where("c.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
				
			$result = $db->fetchOne($select);
		}
		
		if($options == null){
			$select = $db->select()
			->from('contact AS c', array('COUNT(c.id) AS totalItem'));
		
			$result = $db->fetchOne($select);
		}
		if($options['task'] == 'admin-on'){
			$select = $db->select()
			->from('contact AS c', array('COUNT(c.id) AS totalItem'))
			->where('c.status = ?',1,INTERGER);
				
			$result = $db->fetchOne($select);
		}
		
		if($options['task'] == 'admin-off'){
			$select = $db->select()
			->from('contact AS c', array('COUNT(c.id) AS totalItem'))
			->where('c.status = ?',0,INTERGER);
		
			$result = $db->fetchOne($select);
		}
		return $result;
	}
	
	public function listItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		$ssFilter  = $arrParam['ssFilter'];
		if($options['task'] == 'admin-list'){
				
			$paginator = $arrParam['paginator'];
				
			$select = $db->select()
			->from('contact AS c');
	
			if(!empty($ssFilter['col']) && !empty($ssFilter['order'])){
				$select->order($ssFilter['col'] . ' ' . $ssFilter['order']);
			}
	
			if($paginator['itemCountPerPage'] > 0){
				$page = $paginator['currentPage'];
				$rowCount = $paginator['itemCountPerPage'];
				$select->limitPage($page, $rowCount);
			}
	
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('c.name LIKE ?', $keywords, STRING)
						->orWhere('c.address LIKE ?', $keywords, STRING)
						->orWhere('c.tel LIKE ?', $keywords, STRING)
						->orWhere('c.fax LIKE ?', $keywords, STRING)
						->orWhere('c.phone LIKE ?', $keywords, STRING)
						->orWhere('c.email LIKE ?', $keywords, STRING)
						->orWhere('c.created LIKE ?', $keywords, STRING)
						->orWhere('c.ip LIKE ?', $keywords, STRING);
			}
				
			if(!empty($ssFilter['lang_code'])){
				$select->where("c.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
			
			$result = $db->fetchAll($select);
		}
	
		return $result;
	}

	public function saveItem($arrParam = null, $options = null){
		//$AddIP = new Zendvn_Filter_GetAddIp();
		if($options['task'] == 'contact-add'){
			
			$row 				= $this->fetchNew();
			
			$row->name 			= $arrParam['name'];
			$row->address 		= $arrParam['address'];
			$row->tel 			= $arrParam['tel'];
			$row->fax 			= $arrParam['fax'];
			$row->phone 		= $arrParam['phone'];
			$row->email 		= $arrParam['email'];
			$row->content  		= $arrParam['content'];
			$row->created 		= @date("Y-m-d h:m:s");
			$row->ip 			= ' ';
			$row->lang_code 	= $arrParam['lang'];
			
			$row->save();
		}
	}
	
	public function deleteItem($arrParam = null, $options = null){
		if($options['task'] == 'admin-delete'){
			$where = ' id=' . $arrParam['id'];
			$result = $this->delete($where);
		}
	
		if($options['task'] == 'admin-delete-muti'){
			$cid = explode(',', $arrParam['cid']);
			if(!empty($cid) && isset($arrParam['cid'])){
				$ids = implode(',', $cid);
				$where = 'id IN (' . $ids . ')';
				$this->delete($where);
			}
	
		}
	}
	
	public function changeStatus($arrParam = null, $options = null){
		$cid = $arrParam['cid'];
		if(count($cid) > 0){
			if($arrParam['type'] == 1){
				$status = 1;
			}else{
				$status = 0;
			}
	
			$id = implode(',', $cid);
			$data = array('status' => $status);
			$where = 'id IN (' . $id . ')';
			$this->update($data, $where);
		}
		if($arrParam['id'] > 0){
			if($arrParam['type'] == 1){
				$status = 1;
			}else{
				$status = 0;
			}
			$data = array('status' => $status);
			$where = 'id = ' . $arrParam['id'];
			$this->update($data, $where);
		}
	}
	
}