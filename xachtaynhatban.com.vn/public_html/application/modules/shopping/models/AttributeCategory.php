<?php
class Shopping_Model_AttributeCategory extends Zend_Db_Table{
	
	protected $_name = 'product_attribute_category';
	protected $_primary ='id';
	
	protected $_ids;
	
	public function getItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('product_attribute_category AS pac')
			->where('pac.id = ?', $arrParam['id'], INTEGER);
				
			$result = $db->fetchRow($select);
		}
		return $result;
	}
	
	public function itemInSelectbox($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		$ssFilter  = $arrParam['ssFilter'];
		if($options == null){
			$select = $db->select()
			->from('product_attribute_category AS pac', array('id','name'))
			->order('pac.order ASC');
			if(!empty($ssFilter['lang_code'])){
				$select->where("pac.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
			$result = $db->fetchPairs($select);
			$result[0] = 'Chọn nhóm thuộc tính';
			ksort($result);
		}
		
		return $result;
	}
	
	public function countItem($arrParam = null, $options = null){
		$ssFilter  = $arrParam['ssFilter'];
		if($options['task'] == 'admin-list'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('product_attribute_category AS pac', array('COUNT(pac.id) AS totalItem'));
			
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('pac.name LIKE ?', $keywords, STRING);
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("pac.lang_code = '" . $ssFilter['lang_code'] . "'");
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
			->from('product_attribute_category AS pac');
		
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
				$select->where('pac.name LIKE ?', $keywords, STRING);
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("pac.lang_code = '" . $ssFilter['lang_code'] . "'");
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
			$row->order 			= $arrParam['order'];
			$row->status 			= $arrParam['status'];
			$row->lang_code			= stripslashes($arrParam['lang_code']);

			$row->save();
		}
	
		if($options['task'] == 'admin-edit'){
				
			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);
				
			$row->name 				= stripslashes($arrParam['name']);
			$row->order 			= $arrParam['order'];
			$row->status 			= $arrParam['status'];
			$row->lang_code			= stripslashes($arrParam['lang_code']);
				
			$row->save();
		}
	}

	public function deleteItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		if($options['task'] == 'admin-delete'){
			
			$select_item = $db->select()
			->from('product_attribute AS pa', array('COUNT(pa.id) AS totalItem'))
			->where('cat_id = ?',$arrParam['id'], INTERGER);
			$result_item = $db->fetchOne($select_item);
			if($result_item == 0){
				$where = ' id=' . $arrParam['id'];
				$result = $this->delete($where);
			}
		}
	
		if($options['task'] == 'admin-delete-muti'){
			$cid = explode(',', $arrParam['cid']);
			if(!empty($cid) && isset($arrParam['cid'])){
				$ids = implode(',', $cid);
				
				foreach ($cid AS $key => $val){
					$select_item = $db->select()
					->from('product_attribute AS pa', array('COUNT(pa.id) AS totalItem'))
					->where('cat_id = ?',$val, INTERGER);
					$result_item = $db->fetchOne($select_item);
					if($result_item == 0){
						$where = ' id=' . $val;
						$this->delete($where);
					}
				}
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