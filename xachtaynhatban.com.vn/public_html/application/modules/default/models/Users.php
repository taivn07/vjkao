<?php
class Default_Model_Users extends Zend_Db_Table{
	
	protected $_name = 'users';
	protected $_primary ='id';
	
	public function countItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		$ssFilter  = $arrParam['ssFilter'];
		$select = $db->select()
		->from('users AS u', array('COUNT(u.id) AS totalItem'));
	
		if(!empty($ssFilter['keywords'])){
			$keywords = '%' . $ssFilter['keywords'] . '%';
			$select->where('u.user_name LIKE ?', $keywords, STRING);
		}
		
		if($ssFilter['group_id']>0){
			$select->where('u.group_id = ?', $ssFilter['group_id'], INTERGER);
		}
	
		$result = $db->fetchOne($select);
		return $result;
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
	
	public function listItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
	
		$paginator = $arrParam['paginator'];
		$ssFilter  = $arrParam['ssFilter'];
		
		$info 					= new Zendvn_System_Info();
		$user_id 				= $info->getMemberInfo('id');
	
		if($options['task'] == 'admin-list'){
			$select = $db->select()
			->from('users AS u',array('user_name','status','email','register_date','id'))
			->joinLeft('user_group AS g', 'g.id = u.group_id','group_name')
			->where('u.id != ?', $user_id, INTERGER);
				
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
				$select->where('u.user_name LIKE ?', $keywords, STRING);
			}
			
			if($ssFilter['group_id']>0){
				$select->where('u.group_id = ?', $ssFilter['group_id'], INTERGER);
			}
				
			$result = $db->fetchAll($select);
		}
	
		return $result;
	}
	
	public function saveItem($arrParam = null, $options = null){
	
		if($options['task'] == 'admin-add'){
			$row 				= $this->fetchNew();
			$encode = new Zendvn_Encode();
			$row->user_name 	= stripslashes($arrParam['user_name']);
			$row->alias		 	= stripslashes($arrParam['alias']);
			$row->password 		= $encode->password($arrParam['password']);
			$row->email 		= $arrParam['email'];
			$row->group_id 		= $arrParam['group_id'];
			$row->first_name 	= $arrParam['first_name'];
			$row->last_name 	= $arrParam['last_name'];
			$row->birthday 		= $arrParam['birthday'];
			$row->status 		= $arrParam['status'];
			$row->sign 			= stripslashes($arrParam['sign']);
			$row->register_date = @date("Y-m-d h:m:s");
			$row->register_ip 	= $_SERVER['REMOTE_ADDR'];
			$row->user_avatar 	= $arrParam['user_avatar'];
			$row->save();
		}
	
		if($options['task'] == 'admin-edit'){
			
			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);
			
			$encode = new Zendvn_Encode();
			$row->user_name 	= stripslashes($arrParam['user_name']);
			$row->alias		 	= stripslashes($arrParam['alias']);
			if(!empty($arrParam['password'])){
				$row->password 	= $encode->password($arrParam['password']);
			}
			$row->email 		= $arrParam['email'];
			$row->group_id 		= $arrParam['group_id'];
			$row->first_name 	= $arrParam['first_name'];
			$row->last_name 	= $arrParam['last_name'];
			$row->birthday 		= $arrParam['birthday'];
			$row->status 		= $arrParam['status'];
			$row->sign 			= stripslashes($arrParam['sign']);
			$row->visited_date 	= @date("Y-m-d h:m:s");
			$row->visited_ip 	= $_SERVER['REMOTE_ADDR'];
			$row->user_avatar 	= $arrParam['user_avatar'];
			
			$row->save();
		}
		
		if($options['task'] == 'admin-password'){
				
			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);
				
			$encode = new Zendvn_Encode();
			$row->password 	= $encode->password($arrParam['password_new']);
				
			$row->save();
		}
		
		if($options['task'] == 'user-add'){
			$AddIP = new Zendvn_Filter_GetAddIp();
			
			$row 				= $this->fetchNew();
			$encode 			= new Zendvn_Encode();
			$row->user_name 	= stripslashes($arrParam['user_name']);
			$row->password 		= $encode->password($arrParam['password']);
			$row->email 		= $arrParam['email'];
			$row->group_id 		= 0;
			$row->first_name 	= $arrParam['first_name'];
			$row->last_name 	= $arrParam['last_name'];
			$row->birthday 		= $arrParam['year'] . '-' . $arrParam['month'] . '-' . $arrParam['date'];
			$row->status 		= 1;
			$row->sign 			= '';
			$row->register_date = @date("Y-m-d h:m:s");
			$row->register_ip 	= $AddIP->filter();
			$row->user_avatar 	= '';
			$row->save();
		}
		
	}
	
	public function getItem($arrParam = null, $options = null){
	
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit' || $options['task'] == 'public'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('users AS u')
			->joinLeft('user_group AS g', 'u.group_id = g.id',array('group_name'))
			->where('u.id = ?', $arrParam['id'], INTEGER);
			
			$result = $db->fetchRow($select);
		}
		
		if($options['task'] == 'delete'){
			$where = ' id = ' . $arrParam['id'];
			$result = $this->fetchRow($where)->toArray();
		}
		
		if($options['task'] == 'user-success'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('users AS u')
			->joinLeft('user_group AS g', 'u.group_id = g.id',array('group_name'))
			->where('u.id = ?', $arrParam['id'], INTEGER)
			->where("u.active_code = '".$arrParam['active_code']."'");
				
			$result = $db->fetchRow($select);
		}
		
		return $result;
	
	}
	
	public function deleteItem($arrParam = null, $options = null){
		if($options['task'] == 'admin-delete'){
			
			//------------- LAY TEN HINH ANH user_avatar -----------//
			$row = $this->getItem($arrParam, array('task'=>'delete'));
			
			//------------- XOA user_avatar -----------------------//
			$upload_dir = FILE_PATH . '/users';
			$upload = new Zendvn_File_Upload();
			$upload->removeFile($upload_dir . '/orignal/' . $row['user_avatar']);
			$upload->removeFile($upload_dir . '/img100x100/' . $row['user_avatar']);
			$upload->removeFile($upload_dir . '/img450x450/' . $row['user_avatar']);
			
			$where = ' id=' . $arrParam['id'];
			$result = $this->delete($where);
		}
	
		if($options['task'] == 'admin-delete-muti'){
			$cid = explode(',', $arrParam['cid']);
			if(!empty($cid) && isset($arrParam['cid'])){
				foreach ($cid as $key){
					$arrParam['id'] = $key;
					
					//------------- LAY TEN HINH ANH user_avatar -----------//
					$row = $this->getItem($arrParam, array('task'=>'delete'));
					
					//------------- XOA user_avatar -----------------------//
					$upload_dir = FILE_PATH . '/users';
					$upload = new Zendvn_File_Upload();
					$upload->removeFile($upload_dir . '/orignal/' . $row['user_avatar']);
					$upload->removeFile($upload_dir . '/img100x100/' . $row['user_avatar']);
					$upload->removeFile($upload_dir . '/img450x450/' . $row['user_avatar']);

				}
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
	
	public function changeStatusAcp($arrParam = null, $options = null){
		if($arrParam['id'] > 0){
			if($arrParam['type'] == 1){
				$status = 1;
			}else{
				$status = 0;
			}
			$data = array('group_acp' => $status);
			$where = 'id = ' . $arrParam['id'];
			$this->update($data, $where);
		}
	}
}