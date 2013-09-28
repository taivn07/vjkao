<?php
class Default_Model_CommentReply extends Zend_Db_Table{

	protected $_name = 'comment_reply';
	protected $_primary ='id';

	protected $_ids;

	public function getItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);

		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('comment_reply AS cr')
			->where('cr.id = ?', $arrParam['id'], INTEGER);

			$result = $db->fetchRow($select);
		}

		return $result;
	}

	/* public function countItem($arrParam = null, $options = null){
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
			->where('status = ?',1,INTERGER)
			->where('item_id = ?',$arrParam['id'],INTERGER);

			if(!empty($ssFilter['lang_code'])){
				$select->where("c.lang_code = '" . $ssFilter['lang_code'] . "'");
			}

			$result = $db->fetchOne($select);
		}

		return $result;
	} */

	public function listItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		if($options['task'] == 'admin-list'){

			$paginator = $arrParam['paginator'];

			$select = $db->select()
			->from('comment_reply AS cr')
			->where('comment_id = ?', $arrParam['comment_id'], INTERGER);
				
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-list'){
		
			$paginator = $arrParam['paginator'];
		
			$select = $db->select()
			->from('comment_reply AS cr')
			->where('comment_id = ?', $arrParam['comment_id'], INTERGER);
		
			$result = $db->fetchAll($select);
		}

		return $result;
	}

	public function saveItem($arrParam = null, $options = null){

		if($options['task'] == 'admin-addReply'){
			
			$info 				= new Zendvn_System_Info();
			$created_by 		= $info->getMemberInfo('id');
			
			$row 				= $this->fetchNew();
			
			$row->name 			= $info->getMemberInfo('user_name');
			$row->email 		= $info->getMemberInfo('email');
			$row->content 		= $arrParam['content_reply'];
			$row->date	 		= @date("d/m/Y h:i:s");
			$row->user_id 		= $info->getMemberInfo('id');
			$row->comment_id 	= $arrParam['comment_id'];
			
			$row->save();
		}
		
		if($options['task'] == 'admin-editReply'){
				
			$info 				= new Zendvn_System_Info();
			$created_by 		= $info->getMemberInfo('id');
				
			$where 				= ' id=' . $arrParam['reply'];
			$row 				= $this->fetchRow($where);
				
			$row->name 			= $info->getMemberInfo('user_name');
			$row->email 		= $info->getMemberInfo('email');
			$row->content 		= $arrParam['content_reply'];
			$row->date	 		= @date("d/m/Y h:i:s");
			$row->user_id 		= $info->getMemberInfo('id');
			$row->comment_id 	= $arrParam['comment_id'];
				
			$row->save();
		}
	}

	public function deleteItem($arrParam = null, $options = null){
		if($options['task'] == 'admin-delete'){
			$where = ' id=' . $arrParam['reply'];
			$result = $this->delete($where);
		}
		if($options['task'] == 'admin-delete-multi'){
			$where = ' comment_id=' . $arrParam['comment_id'];
			$result = $this->delete($where);
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