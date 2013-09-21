<?php
class Shopping_Model_Bill extends Zend_Db_Table{
	
	protected $_name = 'invoice';
	protected $_primary ='id';
	
	protected $_ids;
	
	public function getItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$select = $db->select()
			->from('invoice AS i')
			->where('i.id = ?', $arrParam['id'], INTEGER);
			$result = $db->fetchRow($select);
			
			$select_detail = $db->select()
			->from('invoice_detail AS id')
			->joinLeft('products AS p', 'id.product_id = p.id',array('p.name AS product_name','p.picture','p.thumb','p.alias','p.units_money','p.cat_id'))
			->joinLeft('product_category AS pc', 'p.cat_id = pc.id',array('pc.name AS category_name','pc.alias AS category_alias'))
			->where('id.invoice_id = ?', $arrParam['id'], INTEGER);
			$result['product'] = $db->fetchAll($select_detail);
		}
		
		if($options['task'] == 'user-info'){
			$select = $db	->	select()
							->	from('invoice AS i')
							->	where('i.id = ?', $arrParam['id'], INTEGER)
							->	where('i.id_user = ?', $arrParam['user_info']['id']);
			$result = $db->fetchRow($select);
				
			$select_detail = $db	->	select()
									->	from('invoice_detail AS id')
									->	joinLeft('products AS p', 'id.product_id = p.id',array('p.name AS product_name','p.picture','p.thumb','p.alias','p.units_money','p.cat_id'))
									->	joinLeft('product_category AS pc', 'p.cat_id = pc.id',array('pc.name AS category_name','pc.alias AS category_alias'))
									->	where('id.invoice_id = ?', $arrParam['id'], INTEGER);
			$result['product'] = $db->fetchAll($select_detail);
		}
		
		if($options['task'] == 'ua-thich'){
			$select = $db	->	select()
							->	from('invoice AS i')
							->	where('i.id_user = ?', $arrParam['user_info']['id']);
			$result = $db->fetchCol($select);
			$cid = implode(',', $result);
			$select_detail = $db	->	select()
									->	from('invoice_detail AS id')
									->	joinLeft('products AS p', 'id.product_id = p.id',array('p.name AS product_name','p.picture','p.thumb','p.alias','p.units_money','p.cat_id'))
									->	joinLeft('product_category AS pc', 'p.cat_id = pc.id',array('pc.name AS category_name','pc.alias AS category_alias'))
									->	where('id.invoice_id IN(' . $cid . ')');
			$result['product'] = $db->fetchAll($select_detail);
		}
		return $result;
	}
	
	public function getOther($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		if($options['task'] == 'get-units'){
			$select = $db->select()
					->from('product_units AS pu');
			$result = $db->fetchPairs($select);
		}
		if($options['task'] == 'get-money'){
			$select = $db->select()
			->from('product_money AS pm',array('code','currency'));
			$result = $db->fetchPairs($select);
		}
		if($options['task'] == 'get-sources'){
			$select = $db->select()
			->from('product_sources AS ps',array('id','name'));
			$result = $db->fetchPairs($select);
			$result[0] = ' -- Chọn nhà cung cấp -- ';
			ksort($result);
		}
		return $result;
	}
	
	public function countItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-list'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$ssFilter  = $arrParam['ssFilter'];
			$select = $db->select()
			->from('invoice AS i', array('COUNT(i.id) AS totalItem'));
			
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('i.full_name LIKE ?', $keywords, STRING);
				$select->orWhere('i.email LIKE ?', $keywords, STRING);
				$select->orWhere('i.phone LIKE ?', $keywords, STRING);
				$select->orWhere('i.address LIKE ?', $keywords, STRING);
				$select->orWhere('i.created LIKE ?', $keywords, STRING);
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
			->from('invoice AS i',array('id','full_name','email','phone','address','created','status'));
		
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
				$select->where('i.full_name LIKE ?', $keywords, STRING);
				$select->orWhere('i.email LIKE ?', $keywords, STRING);
				$select->orWhere('i.phone LIKE ?', $keywords, STRING);
				$select->orWhere('i.address LIKE ?', $keywords, STRING);
				$select->orWhere('i.created LIKE ?', $keywords, STRING);
			}
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'user-list'){
			
			$select = $db	->	select()
							->	from('invoice AS i',array('id','full_name','email','phone','address',"DATE_FORMAT(created, '%d/%m/%Y %H:%i:%s') AS created",'status'))
							->	joinLeft('invoice_detail AS d', 'd.invoice_id = i.id', array('SUM(d.quantity) AS total', 'SUM(d.price) AS price'))
							->	group('i.id')
							->	where('i.id_user = ?', $arrParam['user_info']['id']);

			$result = $db->fetchAll($select);
		}
		return $result;
	}

	public function saveItem($arrParam = null, $options = null){
	
		if($options['task'] == 'admin-add'){
			$row 				= $this->fetchNew();
			if(empty($arrParam['alias'])){
				$filter = new Zendvn_Filter_RewriteUrl();
				$alias 	= $filter->filter($arrParam['name']);
			}else{
				$alias 	= $arrParam['alias'];
			}
			$info 					= new Zendvn_System_Info();
			$created_by 			= $info->getMemberInfo('id');
			
			$filter = new Zend_Filter_Alnum(true);
			$thumb = new Zendvn_Filter_GetThumb();
			
			$row->name 				= stripslashes($arrParam['name']);
			$row->alias 			= $alias;
			$row->picture 			= $arrParam['picture'];
			$row->thumb 			= $thumb->filter($arrParam['picture']);

			$row->save();
		}
	
		if($options['task'] == 'admin-edit'){
				
			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);
				
			if(empty($arrParam['alias'])){
				$filter = new Zendvn_Filter_RewriteUrl();
				$alias 	= $filter->filter($arrParam['name']);
			}else{
				$alias 	= $arrParam['alias'];
			}
			$info 					= new Zendvn_System_Info();
			$modified_by 			= $info->getMemberInfo('id');
			
			$filter = new Zend_Filter_Alnum(true);
			$thumb = new Zendvn_Filter_GetThumb();
			
			
			$row->name 				= stripslashes($arrParam['name']);
			$row->alias 			= $alias;
			$row->picture 			= $arrParam['picture'];
			$row->thumb 			= $thumb->filter($arrParam['picture']);
				
			$row->save();
		}
	}

	public function deleteItem($arrParam = null, $options = null){
		if($options['task'] == 'admin-delete'){				
			$where = ' id=' . $arrParam['id'];
			$result = $this->delete($where);
			
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$table = 'invoice_detail';
			$where = ' invoice_id = ' . $arrParam['id'];
			$delete = $db->delete($table,$where);
		}
	
		if($options['task'] == 'admin-delete-muti'){
			$cid = explode(',', $arrParam['cid']);
			if(!empty($cid) && isset($arrParam['cid'])){
				foreach ($cid AS $key => $val){
					$where = ' id=' . $val;
					$result = $this->delete($where);
						
					$db = Zend_Registry::get('connectDb');
					//$db = Zend_Db::factory($adapter, $config);
					$table = 'invoice_detail';
					$where = ' invoice_id = ' . $val;
					$delete = $db->delete($table,$where);
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
}