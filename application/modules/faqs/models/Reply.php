<?php
class Faqs_Model_Reply extends Zend_Db_Table{
	
	protected $_name = 'faqs_reply';
	protected $_primary ='id';
	
	protected $_ids;
	
	public function getItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('faqs_reply AS gr')
			->where('gr.id = ?', $arrParam['id'], INTEGER);
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
			->from('faqs_reply AS gr', array('COUNT(gr.id) AS totalItem'));
			
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('gr.name LIKE ?', $keywords, STRING);
			}
			
			if($arrParam['faqs_id']>0){
				$select->where('gr.faqs_id = ?',$arrParam['faqs_id'],INTERGER);
			}
			
			$result = $db->fetchOne($select);
		}
		
		if($options['task'] == 'public-reply'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			if(!empty($arrParam['id'])){
				$select = $db->select()
				->from('faqs_reply AS gr',array('COUNT(gr.id) AS totalItem'))
				->where('gr.faqs_id = ?', $arrParam['id'], INTERGER);
			}
			$result = $db->fetchOne($select);
		}
		
		return $result;
	}
	
	public function itemInSelectbox($arrParam = null, $options = null){
		
	}
	
	public function getOrther($arrParam = null, $options = null){
		if($options['task'] == 'admin-faqs'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			if(!empty($arrParam['faqs_id'])){
				$select = $db->select()
				->from('faqs AS f')
				->joinLeft('faqs_category AS fc', 'f.cat_id = fc.id',array('fc.name AS category_name'))
				->where('f.id = ?',$arrParam['faqs_id'],INTERGER);
				$result = $db->fetchRow($select);
			}
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
			->from('faqs_reply AS gr');
		
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
				$select->where('gr.name LIKE ?', $keywords, STRING);
			}
				
			if($arrParam['faqs_id']>0){
				$select->where('gr.faqs_id = ?',$arrParam['faqs_id'],INTERGER);
			}
		
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-reply'){
			if(!empty($arrParam['id'])){
				$paginator = $arrParam['paginator'];
				$select = $db->select()
				->from('faqs_reply AS gr')
				->where('gr.faqs_id = ?',$arrParam['id'],INTERGER)
				->order('gr.id ASC');
				
				if($paginator['itemCountPerPage'] > 0){
					$page = $paginator['currentPage'];
					$rowCount = $paginator['itemCountPerPage'];
					$select->limitPage($page, $rowCount);
				}
				
				$result = $db->fetchAll($select);
			}
		}
		return $result;
		
	}

	public function saveItem($arrParam = null, $options = null){
	
		if($options['task'] == 'admin-add'){
			
			$info 				= new Zendvn_System_Info();
			$created_by 		= $info->getMemberInfo('id');
			
			$row 				= $this->fetchNew();
			
			$row->name 			= $info->getMemberInfo('user_name');
			$row->email 		= $info->getMemberInfo('email');
			$row->content 		= $arrParam['content'];
			$row->date	 		= @date("d/m/Y h:i:s");
			$row->user_id 		= $info->getMemberInfo('id');
			$row->faqs_id 		= $arrParam['faqs_id'];

			$row->save();
		}
	
		if($options['task'] == 'admin-edit'){
				
			$info 				= new Zendvn_System_Info();
			$created_by 		= $info->getMemberInfo('id');
				
			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);
				
			$row->name 			= $info->getMemberInfo('user_name');
			$row->email 		= $info->getMemberInfo('email');
			$row->content 		= $arrParam['content'];
			$row->date	 		= @date("d/m/Y h:i:s");
			$row->user_id 		= $info->getMemberInfo('id');
			$row->faqs_id 		= $arrParam['faqs_id'];
				
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