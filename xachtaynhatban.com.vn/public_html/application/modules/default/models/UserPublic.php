<?php
class Default_Model_UserPublic extends Zend_Db_Table{
	
	protected $_name = 'users';
	protected $_primary ='id';
	
	public function saveItem($arrParam = null, $options = null){
	
		if($options['task'] == 'admin-add'){
			$row 				= $this->fetchNew();
			$encode = new Zendvn_Encode();
			$row->user_name 	= stripslashes($arrParam['user_name']);
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
	}
	
	public function getItem($arrParam = null, $options = null){
	
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
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
		
		return $result;
	
	}
}