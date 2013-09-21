<?php
class Default_Model_Comment extends Zend_Db_Table{
	
	protected $_name = 'comment';
	protected $_primary ='id';
	
	protected $_ids;
	
	public function getItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('comment AS c')
			->where('c.id = ?', $arrParam['id'], INTEGER);
		
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
			->from('comment AS c', array('COUNT(c.id) AS totalItem'))
			->where("module = '" . $arrParam['module'] . "'");
				
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('c.name LIKE ?', $keywords, STRING)
						->orWhere('c.email LIKE ?', $keywords, STRING)
						->orWhere('c.date LIKE ?', $keywords, STRING);
			}
				
			if(!empty($ssFilter['lang_code'])){
				$select->where("c.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
				
			$result = $db->fetchOne($select);
		}
		
		if($options['task'] == 'public-comment'){
			
			$select = $db->select()
			->from('comment AS c', array('COUNT(c.id) AS totalItem'))
			->where('status = ?',1,INTERGER);
			
			if(!empty($arrParam['c_module'])){
				$select->where("module = '" . $arrParam['c_module'] . "'");
			}
				
			if(!empty($arrParam['c_id'])){
				$select->where('item_id = ?',$arrParam['c_id'],INTERGER);
			}
				
			if(!empty($ssFilter['lang_code'])){
				$select->where("c.lang_code = '" . $ssFilter['lang_code'] . "'");
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
			->from('comment AS c')
			->where("module = '" . $arrParam['module'] . "'");
	
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
						->orWhere('c.email LIKE ?', $keywords, STRING)
						->orWhere('c.date LIKE ?', $keywords, STRING);
			}
				
			if(!empty($ssFilter['lang_code'])){
				$select->where("c.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
			
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-comment'){
			
			$paginator = $arrParam['paginator'];
			
			$select = $db->select()
			->from('comment AS c')
			->where('status = ?',1,INTERGER)
			->order('id ASC');
			
			if(!empty($arrParam['c_module'])){
				$select->where("module = '" . $arrParam['c_module'] . "'");
			}
			
			if(!empty($arrParam['c_id'])){
				$select->where('item_id = ?',$arrParam['c_id'],INTERGER);
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("c.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
			
			if($paginator['itemCountPerPage'] > 0){
				$page = $paginator['currentPage'];
				$rowCount = $paginator['itemCountPerPage'];
				$select->limitPage($page, $rowCount);
			}
			
			$result = $db->fetchAll($select);
		}
	
		return $result;
	}

	public function saveItem($arrParam = null, $options = null){

		if($options['task'] == 'comment-add'){
			
			$filename = APPLICATION_PATH . '/modules/'.$arrParam['c_module'].'/config/config.ini';
			$section = 'module-settings';
			$moduleConfig = new Zend_Config_Ini($filename, $section);
			$moduleConfig = $moduleConfig->toArray();
			$moduleConfig = $moduleConfig['module'];
			$status = 1;
			if($moduleConfig['checkComment'] == 1){
				$status = 0;
			}
			
			$row 				= $this->fetchNew();
			
			$row->name 			= $arrParam['txtName'];
			$row->email 		= $arrParam['txtEmail'];
			$row->content 		= $arrParam['txtContent'];
			$row->date	 		= @date("d/m/Y h:i:s");
			$row->status 		= $status;
			$row->module 		= $arrParam['c_module'];
			$row->item_id 		= $arrParam['c_id'];
			$row->lang_code 	= $arrParam['ssFilter']['lang_code'];
			
			$row->save();
		}
	}
	
	public function deleteItem($arrParam = null, $options = null){
		if($options['task'] == 'admin-delete'){
			$where = ' id=' . $arrParam['id'];
			$result = $this->delete($where);
			
			$tblCommentReply = new Default_Model_CommentReply();
			$tblCommentReply->deleteItem(array('comment_id' => $arrParam['id']), array('task'=>'admin-delete-multi'));
			
		}
	
		if($options['task'] == 'admin-delete-muti'){
			$cid = explode(',', $arrParam['cid']);
			if(!empty($cid) && isset($arrParam['cid'])){
				$ids = implode(',', $cid);
				$where = 'id IN (' . $ids . ')';
				$this->delete($where);
			}
			
			if(!empty($cid) && isset($arrParam['cid'])){
				foreach ($cid AS $key => $val){
					$tblCommentReply = new Default_Model_CommentReply();
					$tblCommentReply->deleteItem(array('comment_id' => $val), array('task'=>'admin-delete-multi'));
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