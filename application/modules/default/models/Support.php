<?php
class Default_Model_Support extends Zend_Db_Table{
	
	protected $_name = 'support';
	protected $_primary ='id';
	
	protected $_ids;
	
	public function getItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('support AS s')
			->where('s.id = ?', $arrParam['id'], INTEGER);
				
			$result = $db->fetchRow($select);
		}
		return $result;
	}
	
	public function countItem($arrParam = null, $options = null){
		$ssFilter  = $arrParam['ssFilter'];
		if($options['task'] == 'admin-list'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('support AS s', array('COUNT(s.id) AS totalItem'));
			
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('s.name LIKE ?', $keywords, STRING);
			}
			
			if(!empty($ssFilter['lang_code'])){
				if($ssFilter['lang_code'] != '*'){
					$select->where("s.lang_code = '" . $ssFilter['lang_code'] . "'");
				}
			}
			
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
			->from('support AS s');
		
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
				$select->where('s.name LIKE ?', $keywords, STRING);
			}
			
			if(!empty($ssFilter['lang_code'])){
				if($ssFilter['lang_code'] != '*'){
					$select->where("s.lang_code = '" . $ssFilter['lang_code'] . "'");
				}
			}
		
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-list'){
			
			$select = $db->select()
			->from('support AS s')
			->where('status = ?',1,INTERGER);
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("s.lang_code = '" . $ssFilter['lang_code'] . "'")
						->orwhere("s.lang_code = '*'");
			}
		
			$result = $db->fetchAll($select);
		}
		
		return $result;
	}

	public function saveItem($arrParam = null, $options = null){
	
		if($options['task'] == 'admin-add'){
			$row 				= $this->fetchNew();
			
			$info 					= new Zendvn_System_Info();
			$created_by 			= $info->getMemberInfo('id');

			$row->name 				= stripslashes($arrParam['name']);
			$row->yahoo 			= $arrParam['yahoo'];
			$row->skype				= stripslashes($arrParam['skype']);
			$row->email 			= stripslashes($arrParam['email']);
			$row->phone 			= $arrParam['phone'];
			$row->tel 				= $arrParam['tel'];
			$row->order 			= $arrParam['order'];
			$row->status 			= $arrParam['status'];
			$row->lang_code 		= $arrParam['lang_code'];

			$row->save();
		}
	
		if($options['task'] == 'admin-edit'){
				
			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);
				
			$info 					= new Zendvn_System_Info();
			$modified_by 			= $info->getMemberInfo('id');
			
			$row->name 				= stripslashes($arrParam['name']);
			$row->yahoo 			= $arrParam['yahoo'];
			$row->skype				= stripslashes($arrParam['skype']);
			$row->email 			= stripslashes($arrParam['email']);
			$row->phone 			= $arrParam['phone'];
			$row->tel 				= $arrParam['tel'];
			$row->order 			= $arrParam['order'];
			$row->status 			= $arrParam['status'];
			$row->lang_code 		= $arrParam['lang_code'];
				
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
	
	public function sortItem($arrParam = null, $options = null){
		$cid = $arrParam['cid'];
		$order = $arrParam['order'];
		if($options['task'] == 'admin-sort'){
			if(count($cid) > 0){
				foreach ($cid as $key => $val){
					$data = array('order' => $order[$val]);
					$where = 'id = ' . $val;
					$this->update($data, $where);
				}
			}
		}
	}
}