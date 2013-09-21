<?php
class Shopping_Model_Manu extends Zend_Db_Table{
	
	protected $_name = 'product_manu';
	protected $_primary ='id';
	
	protected $_ids;
	
	public function getItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('product_manu AS pm')
			->where('pm.id = ?', $arrParam['id'], INTEGER);
				
			$result = $db->fetchRow($select);
		}
		return $result;
	}
	
	public function itemInSelectbox($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		if($options['task'] == 'get-sources'){
			$select = $db->select()
			->from('product_manu AS pm',array('id','name'));
			$result = $db->fetchPairs($select);
			$result[0] = ' -- Chọn hãng sản xuất -- ';
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
			->from('product_manu AS pm', array('COUNT(pm.id) AS totalItem'));
			
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('pm.name LIKE ?', $keywords, STRING);
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
			->from('product_manu AS pm');
		
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
				$select->where('pm.name LIKE ?', $keywords, STRING);
			}
		
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-list'){
			$select = $db->select()
			->from('product_manu AS pm',array('id','name'))
			->where('pm.status = ?',1,INTERGER)
			->order('pm.order ASC')
			->order('pm.id ASC');
			
			if(!empty($arrParam['cid']) > 0){
				
				$select_category = $db->select()
				->from('product_category',array('id','name','parents'))
				->where('status = ?',1,INTERGER);
				$resultCategory = $db->fetchAll($select_category);
				$system = new Zendvn_System_Recursive($resultCategory);
				$newArray = $system->builArray($arrParam['cid']);
				$tmp[] = $arrParam['cid'];
				if(count($newArray)>0){
					foreach ($newArray as $key => $val){
						$tmp[] = $val['id'];
					}
				}
				
				$ids = implode(',', $tmp);
				
				$select_item = $db->select()
				->from('products AS p',array('id','manu_id'))
				->where('p.cat_id IN (' . $ids . ')')
				->where('p.manu_id > ?',0,INTERGER)
				->group('p.manu_id');
				$result_item = $db->fetchAll($select_item);
				
				$strManu = '';
				foreach ($result_item AS $key => $val){
					if($key == 0){
						$strManu .= $val['manu_id'];
					}else{
						$strManu .= ',' . $val['manu_id'];
					}
				}
				
				if($strManu != ''){
					$select->where('pm.id IN (' . $strManu . ')');
				}
				
			}else{
				$ssFilter  = $arrParam['ssFilter'];
				$select_item = $db->select()
				->from('products AS p',array('id','manu_id'))
				->where('p.manu_id > ?',0,INTERGER)
				->group('p.manu_id');
				
				if($arrParam['action'] == 'khuyen-mai'){
					$select_item->where('(p.khuyenmai != "") or (p.selloff != 0)');
				}
				
				if($arrParam['action'] == 'ban-chay'){
					$select_item->where('p.block_banchay = ?',1,INTERGER);
				}
				
				if($arrParam['action'] == 'noi-bat'){
					$select_item->where('p.block_noibat = ?',1,INTERGER);
				}
				
				if($arrParam['action'] == 'hot'){
					$select_item->where('p.block_hot = ?',1,INTERGER);
				}
				
				if($arrParam['action'] == 'search'){
					$select_item->where("(p.name like '%" . $ssFilter['keywords'] . "%') OR (p.alias like '%" . $ssFilter['keywords'] . "%')");
				}
				
				if($arrParam['action'] == 'tags'){
					if(!empty($arrParam['key'])){
						$select_item->where("p.tags like '%" . $arrParam['key'] . "%'");
					}
				}

				$result_item = $db->fetchAll($select_item);
				
				$strManu = '';
				foreach ($result_item AS $key => $val){
					if($key == 0){
						$strManu .= $val['manu_id'];
					}else{
						$strManu .= ',' . $val['manu_id'];
					}
				}
				
				if($strManu != ''){
					$select->where('pm.id IN (' . $strManu . ')');
				}
			}
			
			if(count($result_item) > 0){
				$result = $db->fetchAll($select);
			}else{
				$result = array();
			}
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

			$row->save();
		}
	
		if($options['task'] == 'admin-edit'){
				
			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);
				
			$info 					= new Zendvn_System_Info();
			$modified_by 			= $info->getMemberInfo('id');
			
			$row->name 				= stripslashes($arrParam['name']);
			$row->order 			= $arrParam['order'];
			$row->status 			= $arrParam['status'];
				
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