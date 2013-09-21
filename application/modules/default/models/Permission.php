<?php
class Default_Model_Permission extends Zend_Db_Table{
	
	protected $_name = 'privileges';
	protected $_primary ='id';
	
	protected $_ids;
	
	public function getItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('privileges AS p',array('id','name','p.module AS p_module','p.controller AS p_controller','p.action AS p_action'))
			->where('p.id = ?', $arrParam['id'], INTEGER);
				
			$result = $db->fetchRow($select);
		}
		return $result;
	}
	
	public function countItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-list'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$ssFilter  = $arrParam['ssFilter'];
			$select = $db->select()
			->from('privileges AS p', array('COUNT(p.id) AS totalItem'));
			
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('p.name LIKE ?', $keywords, STRING);
			}
			
			if(!empty($ssFilter['p_module']) != '0'){
				$select->where("p.module ='" . $ssFilter['p_module'] . "'");
			}
			
			$result = $db->fetchOne($select);
		}
		return $result;
	}
	
	public function itemInSelectbox($arrParam = null, $options = null){
		
	}
	
	public function listItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		
		if($options['task'] == 'admin-list'){
			
			$paginator = $arrParam['paginator'];
			$ssFilter  = $arrParam['ssFilter'];
			
			$select = $db->select()
			->from('privileges AS p');
		
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
				$select->where('p.name LIKE ?', $keywords, STRING);
			}
			
			if(!empty($ssFilter['p_module']) != '0'){
				$select->where("p.module ='" . $ssFilter['p_module'] . "'");
			}
		
			$result = $db->fetchAll($select);
		}
		
		return $result;
	}

	public function saveItem($arrParam = null, $options = null){
	
		if($options['task'] == 'admin-add'){
			$row 				= $this->fetchNew();
			
			$row->name 				= stripslashes($arrParam['name']);
			$row->module			= stripslashes($arrParam['p_module']);
			$row->controller		= stripslashes($arrParam['p_controller']);
			$row->action			= stripslashes($arrParam['p_action']);

			$row->save();
		}
	
		if($options['task'] == 'admin-edit'){
				
			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);

			$row->name 				= stripslashes($arrParam['name']);
			$row->module			= stripslashes($arrParam['p_module']);
			$row->controller		= stripslashes($arrParam['p_controller']);
			$row->action			= stripslashes($arrParam['p_action']);
				
			$row->save();
		}
	}

	public function deleteItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		if($options['task'] == 'admin-delete'){				
			$where = ' id=' . $arrParam['id'];
			$result = $this->delete($where);
			
			$table = 'user_group_privileges';
			$where = ' privilege_id = ' . $arrParam['id'];
			$delete = $db->delete($table,$where);
			
		}
	
		if($options['task'] == 'admin-delete-muti'){
			$cid = explode(',', $arrParam['cid']);
			if(!empty($cid) && isset($arrParam['cid'])){
				$ids = implode(',', $cid);
				$where = 'id IN (' . $ids . ')';
				$this->delete($where);
				
				$table = 'user_group_privileges';
				$where = ' privilege_id IN (' . $ids . ')';
				$delete = $db->delete($table,$where);
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