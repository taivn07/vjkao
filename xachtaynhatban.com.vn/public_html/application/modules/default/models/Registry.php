<?php
class Default_Model_Registry extends Zend_Db_Table{
	
	protected $_name = 'users';
	protected $_primary ='id';
	
	public function saveItem($arrParam = null, $options = null){
	
		if($options['task'] == 'user-add'){
			$row 				= $this->fetchNew();
			$encode = new Zendvn_Encode();
			
			$row->user_name 		= stripslashes($arrParam['user_name']);
			$row->password 			= $encode->password($arrParam['password']);
			
			$row->member_hoten 	= stripslashes($arrParam['member_hoten']);
			$row->member_ngaysinh 	= $arrParam['ngaysinh'] . '-' . $arrParam['thangsinh'] . '-' . $arrParam['namsinh'];
			$row->member_gioitinh 	= stripslashes($arrParam['member_gioitinh']);
			$row->member_honnhan 	= stripslashes($arrParam['member_honnhan']);
			$row->member_diachi 	= stripslashes($arrParam['member_diachi']);
			$row->member_tinh 		= stripslashes($arrParam['member_tinh']);
			$row->member_dienthoai = stripslashes($arrParam['member_dienthoai']);
			$row->member_email 	= stripslashes($arrParam['member_email']);
			
			$row->register_date 	= @date("Y-m-d h:m:s");
			$row->register_ip 		= @$_SERVER['REMOTE_ADDR'];
			//$row->active_code 		= $encode->password(@rand()); Kich hoạt tài khoản
			$row->active_code 		= 0;
			$row->status 			= 1;
			$row->phanloai 			= 1;
			$row->group_id 			= 0;

			$id = $row->save();
			
			return $id;
		}
	
		if($options['task'] == 'user-edit'){

			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);

			$row->member_hoten 	= stripslashes($arrParam['member_hoten']);
			$row->member_ngaysinh 	= $arrParam['ngaysinh'] . '-' . $arrParam['thangsinh'] . '-' . $arrParam['namsinh'];
			$row->member_gioitinh 	= stripslashes($arrParam['member_gioitinh']);
			$row->member_honnhan 	= stripslashes($arrParam['member_honnhan']);
			$row->member_diachi 	= stripslashes($arrParam['member_diachi']);
			$row->member_tinh 		= stripslashes($arrParam['member_tinh']);
			$row->member_dienthoai = stripslashes($arrParam['member_dienthoai']);
			$row->member_email 	= stripslashes($arrParam['member_email']);
			$row->member_avatar 	= $arrParam['user_avatar'];
			
			$row->visited_date 		= @date("Y-m-d h:m:s");
			$row->visited_ip 		= @$_SERVER['REMOTE_ADDR'];
			
			$row->save();

		}
		
		if($options['task'] == 'user-password'){
				
			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);
				
			$encode = new Zendvn_Encode();
			$row->password 	= $encode->password($arrParam['password']);
				
			$row->save();
		}
	}
	
	public function getItem($arrParam = null, $options = null){
	
		if($options['task'] == 'user-info' || $options['task'] == 'user-edit'){
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