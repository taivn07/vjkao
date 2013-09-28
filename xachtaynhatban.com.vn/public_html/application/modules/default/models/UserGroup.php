<?php
class Default_Model_UserGroup extends Zend_Db_Table{
	
	protected $_name = 'user_group';
	protected $_primary ='id';
	
	public function itemInSelectbox($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		if($options == null){
			$select = $db->select()
			->from('user_group', array('id','group_name'));
			$result = $db->fetchPairs($select);
			$result[0] = ' -- Select an Item -- ';
			ksort($result);
		}
		return $result;
	}
	
	public function countItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		$ssFilter  = $arrParam['ssFilter'];
		$select = $db->select()
		->from('user_group AS g', array('COUNT(g.id) AS totalItem'));
		
		if(!empty($ssFilter['keywords'])){
			$keywords = '%' . $ssFilter['keywords'] . '%';
			$select->where('g.group_name LIKE ?', $keywords, STRING);
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
		
		if($options['task'] == 'admin-list'){
			$select = $db->select()
			->from('user_group AS g',array('id','group_name','group_acp','status','order'))
			->joinLeft('users AS u', 'g.id = u.group_id','COUNT(u.id) AS members')
			->group('g.id');
			
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
				$select->where('g.group_name LIKE ?', $keywords, STRING);
			}
			
			$result = $db->fetchAll($select);
		}
		
		return $result;
	}
	
	public function privileges($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		
		if($options['task'] == 'admin-add'){
			$select = $db->select()
			->from('privileges AS pr');				
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'admin-module'){
			$select = $db->select()
			->from('privileges AS pr','module')
			->group('pr.module');
			$result = $db->fetchCol($select);
		}
		
		return $result;
	}
	
	
	public function saveItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-add'){
			$info 					= new Zendvn_System_Info();
			$created_by 			= $info->getMemberInfo('id');
			$row 				= $this->fetchNew();
			$row->group_name 	= stripslashes($arrParam['group_name']);
			$row->avatar 		= $arrParam['avatar'];
			$row->ranking 		= $arrParam['ranking'];
			$row->group_acp 	= $arrParam['group_acp'];
			$row->group_default = $arrParam['group_default'];
			$row->created 		= @date("Y-m-d h:m:s");
			$row->created_by 	= $created_by;
			$row->status 		= $arrParam['status'];
			$row->order 		= $arrParam['order'];
			
			if(isset($arrParam['fullAccess']) == 'on'){
				$row->permission 		= 'Full Access';
			}else{
				$row->permission 		= 'Limit Access';
			}
			
			$id = $row->save();
			
			if(!isset($arrParam['fullAccess']) && count($arrParam['privileges']) > 0){
				$db = Zend_Registry::get('connectDb');
				//$db = Zend_Db::factory($adapter, $config);
				$table = 'user_group_privileges';
				foreach ($arrParam['privileges'] AS $key => $val){
					$bind = array(
							'privilege_id' => $val,
							'group_id' => $id,
							'status' => 1
					);
					$db->insert($table, $bind);
				}
			}
			
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$table = 'user_files';
			$bind = array(
					'group_id' => $id,
					'disabled' => $arrParam['disabled'],
					'denyZipDownload' => $arrParam['denyZipDownload'],
					'denyExtensionRename' => $arrParam['denyExtensionRename'],
					'files_upload' => $arrParam['files_upload'],
					'files_delete' => $arrParam['files_delete'],
					'files_copy' => $arrParam['files_copy'],
					'files_move' => $arrParam['files_move'],
					'files_rename' => $arrParam['files_rename'],
					'dirs_create' => $arrParam['dirs_create'],
					'dirs_delete' => $arrParam['dirs_delete'],
					'dirs_rename' => $arrParam['dirs_rename'],
			);
			$db->insert($table, $bind);
		}
		
		if($options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			
			$table = 'user_group_privileges';
			$where = ' group_id = ' . $arrParam['id'];
			$delete = $db->delete($table,$where);
			
			$table = 'user_files';
			$where = ' group_id = ' . $arrParam['id'];
			$delete = $db->delete($table,$where);
			
			$info 					= new Zendvn_System_Info();
			$modified_by 			= $info->getMemberInfo('id');
			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);
			$row->group_name 	= stripslashes($arrParam['group_name']);
			$row->avatar 		= $arrParam['avatar'];
			$row->ranking 		= $arrParam['ranking'];
			$row->group_acp 	= $arrParam['group_acp'];
			$row->group_default = $arrParam['group_default'];
			$row->modified 		= @date("Y-m-d h:m:s");
			$row->modified_by 	= $modified_by;
			$row->status 		= $arrParam['status'];
			$row->order 		= $arrParam['order'];
			
			if(isset($arrParam['fullAccess']) == 'on'){
				$row->permission 		= 'Full Access';
			}else{
				$row->permission 		= 'Limit Access';
			}
			
			$row->save();
			
			$id = $arrParam['id'];
			if(!isset($arrParam['fullAccess']) && count($arrParam['privileges']) > 0){
				
				$table = 'user_group_privileges';
				foreach ($arrParam['privileges'] AS $key => $val){
					$bind = array(
							'privilege_id' => $val,
							'group_id' => $id,
							'status' => 1
					);
					$db->insert($table, $bind);
				}
			}
			
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$table = 'user_files';
			$bind = array(
					'group_id' => $id,
					'disabled' => $arrParam['disabled'],
					'denyZipDownload' => $arrParam['denyZipDownload'],
					'denyExtensionRename' => $arrParam['denyExtensionRename'],
					'files_upload' => $arrParam['files_upload'],
					'files_delete' => $arrParam['files_delete'],
					'files_copy' => $arrParam['files_copy'],
					'files_move' => $arrParam['files_move'],
					'files_rename' => $arrParam['files_rename'],
					'dirs_create' => $arrParam['dirs_create'],
					'dirs_delete' => $arrParam['dirs_delete'],
					'dirs_rename' => $arrParam['dirs_rename'],
			);
			$db->insert($table, $bind);
		}
	}
	
	public function getItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){ 
			$where = ' id=' . $arrParam['id'];
			$result = $this->fetchRow($where)->toArray();
		}
		
		if($options['task'] == 'admin-permission'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('user_group_privileges','privilege_id')
			->where('group_id = ?',$arrParam['id'],INTERGER)
			->where('status = ?',1,INTERGER);
			$result = $db->fetchCol($select);
		}
		
		if($options['task'] == 'admin-files'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('user_files')
			->where('group_id = ?',$arrParam['id'],INTERGER);
			$result = $db->fetchRow($select);
		}
		
		return $result;
		
	}
	
	public function deleteItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		if($options['task'] == 'admin-delete'){

			
			$select_item = $db->select()
				->from('users AS u', array('COUNT(u.id) AS totalItem'))
				->where('group_id = ?',$arrParam['id'],INTERGER);
			$result_item = $db->fetchOne($select_item);
				
			if($result_item == 0){
				if($arrParam['id'] != 1 && $arrParam['id'] != 2 && $arrParam['id'] != 3 && $arrParam['id'] != 4){
					$where = ' id=' . $arrParam['id'];
					$result = $this->delete($where);
					
					$table = 'user_group_privileges';
					$where = ' group_id = ' . $arrParam['id'];
					$delete = $db->delete($table,$where);
					
					$table = 'user_files';
					$where = ' group_id = ' . $arrParam['id'];
					$delete = $db->delete($table,$where);
				}
			}
			
		}
		
		if($options['task'] == 'admin-delete-muti'){
			$cid = $arrParam['cid'];
			if(!empty($cid) && isset($arrParam['cid'])){
				$ids = $cid;
				
				$select_item = $db->select()
				->from('users AS u', array('COUNT(u.id) AS totalItem'))
				->where('group_id IN (' . $ids . ')');
				$result_item = $db->fetchOne($select_item);
				
				if($result_item == 0){
					$newArr = explode(',', $cid);
					foreach ($newArr AS $key => $val){
						if($val != 1 && $val != 2 && $val != 3 && $val != 4){
							$where = ' id=' . $val;
							$result = $this->delete($where);
							
							$table = 'user_group_privileges';
							$where = ' group_id = ' . $val;
							$delete = $db->delete($table,$where);
							
							$table = 'user_files';
							$where = ' group_id = ' . $val;
							$delete = $db->delete($table,$where);
						}
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




