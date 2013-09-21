<?php
class Default_Model_Menu extends Zend_Db_Table{
	
	protected $_name = 'menu';
	protected $_primary ='id';
	
	public function itemInSelectbox($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		$ssFilter  = $arrParam['ssFilter'];
		if($options == null){
			$select = $db->select()
			->from('menu AS m',array('id','name','parents','status','order','created_by'))
			->order('m.order ASC');
			if(isset($arrParam['type_menu'])){
				$select->where("m.type_menu='" . $arrParam['type_menu'] . "'");
			}else{
				$select->where("m.type_menu='main_menu'");
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("m.lang_code = '" . $ssFilter['lang_code'] . "'");
			}else{
				$language = new Zend_Session_Namespace('language');
				$select->where("m.lang_code = '" . $language->lang . "'");
			}
			
			$result = $db->fetchAll($select);
			
		}
		
		if($options['task'] == 'admin-edit'){
			$id = $arrParam['id'];
			$select = $db->select()
			->from('menu AS m',array('id','name','parents','status','order','created_by'))
			->where('id != ?',$id,INTERGER)
			->order('m.order ASC');
			if(isset($arrParam['type_menu'])){
				$select->where("m.type_menu='" . $arrParam['type_menu'] . "'");
			}else{
				$select->where("m.type_menu='main_menu'");
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("m.lang_code = '" . $ssFilter['lang_code'] . "'");
			}else{
				$language = new Zend_Session_Namespace('language');
				$select->where("m.lang_code = '" . $language->lang . "'");
			}
			
			$result = $db->fetchAll($select);
			
		}
		$system = new Zendvn_System_Recursive($result);
		$result = $system->builArray(0);
		if(count($result)>0){
			$tmp = array('id' => 0,'name'=>'Danh mục gốc','level'=>1,'parents'=>0,'order'=>1);
			array_unshift($result, $tmp);
		}else{
			$result = array('0'=>array('id' => 0,'name'=>'Danh mục gốc','level'=>1,'parents'=>0,'order'=>1));
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
			->from('menu AS m',array('id','name','parents','status','order','created_by','module_options','lang_code'))
			->joinLeft('users AS u', 'u.id = m.created_by','user_name')
			->order('m.order ASC');
			
			if(isset($arrParam['type_menu'])){
				$select->where("m.type_menu='" . $arrParam['type_menu'] . "'");
			}else{
				$select->where("m.type_menu='main_menu'");
			}
			
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('m.name LIKE ?', $keywords, STRING);
			}
			
			$parents = 0;
			if(!empty($ssFilter['parents'])){
				$parents = $ssFilter['parents'];
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("m.lang_code = '" . $ssFilter['lang_code'] . "'");
			}else{
				$language = new Zend_Session_Namespace('language');
				$select->where("m.lang_code = '" . $language->lang . "'");
			}

			$result = $db->fetchAll($select);
			$system = new Zendvn_System_Recursive($result);
			$result = $system->builArray($parents);
		}
		
		return $result;
	}
	
	public function saveItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-add'){
			if(empty($arrParam['alias'])){
				$filter = new Zendvn_Filter_RewriteUrl();
				$alias 	= $filter->filter($arrParam['name']);
			}else{
				$alias 	= $arrParam['alias'];
			}
			$info 					= new Zendvn_System_Info();
			$created_by 			= $info->getMemberInfo('id');
			$row 					= $this->fetchNew();
			$row->name 				= stripslashes($arrParam['name']);
			$row->alias 			= $alias;
			$row->picture 			= $arrParam['picture'];
			$row->parents 			= $arrParam['parents'];
			$row->created 			= @date("Y-m-d h:m:s");
			$row->created_by		= $created_by;
			$row->module_options	= $arrParam['module_options'];
			$row->controller_options= 'index';
			$row->action_options	= 'category';
			$row->type_menu			= $arrParam['type_menu'];
			$row->url				= $arrParam['url'];
			$row->cat_id			= $arrParam['cat_id'];
			$row->target			= $arrParam['target'];
			$row->auto_submenu		= $arrParam['auto_submenu'];
			$row->auto_submenu		= $arrParam['auto_submenu'];
			$row->order 			= $arrParam['order'];
			$row->status 			= $arrParam['status'];
			$row->lang_code 		= $arrParam['lang_code'];

			$row->save();
		}
		
		if($options['task'] == 'admin-edit'){
			if(empty($arrParam['alias'])){
				$filter = new Zendvn_Filter_RewriteUrl();
				$alias 	= $filter->filter($arrParam['name']);
			}else{
				$alias 	= $arrParam['alias'];
			}
			$where 					= ' id=' . $arrParam['id'];
			$row 					= $this->fetchRow($where);
			$info 					= new Zendvn_System_Info();
			$modified_by 			= $info->getMemberInfo('id');
			$row->name 				= stripslashes($arrParam['name']);
			$row->alias 			= $alias;
			$row->picture 			= $arrParam['picture'];
			$row->parents 			= $arrParam['parents'];
			$row->modified 			= @date("Y-m-d h:m:s");
			$row->modified_by 		= $modified_by;
			$row->module_options	= $arrParam['module_options'];
			$row->controller_options= 'index';
			$row->action_options	= 'category';
			$row->type_menu			= $arrParam['type_menu'];
			$row->url				= $arrParam['url'];
			$row->cat_id			= $arrParam['cat_id'];
			$row->target			= $arrParam['target'];
			$row->auto_submenu		= $arrParam['auto_submenu'];
			$row->auto_submenu		= $arrParam['auto_submenu'];
			$row->order 			= $arrParam['order'];
			$row->status 			= $arrParam['status'];
			$row->lang_code 		= $arrParam['lang_code'];
			
			$row->save();
		}
	}
	
	public function getItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){ 
			$where = ' id=' . $arrParam['id'];
			$result = $this->fetchRow($where)->toArray();
		}
		return $result;
		
	}
	
	public function deleteItem($arrParam = null, $options = null){
		if($options['task'] == 'admin-delete'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$id = $arrParam['id'];
			$select = $db->select()
				->from('menu AS m',array('id','name','parents','status','order','created_by'));
			$result = $db->fetchAll($select);
			$system = new Zendvn_System_Recursive($result);
			$result = $system->builArray($id);
			if(count($result)>0){
				array_unshift($result, array('id'=>$arrParam['id']));
			}else{
				$result[] = array('id' => $arrParam['id']);
			}
			foreach ($result as $key => $val){
				$where = ' id=' . $val['id'];
				$result = $this->delete($where);
			}
		}
		
		if($options['task'] == 'admin-delete-muti'){
			$cid = explode(',', $arrParam['cid']);
			if(!empty($cid) && isset($arrParam['cid'])){
				$db = Zend_Registry::get('connectDb');
				//$db = Zend_Db::factory($adapter, $config);
				$id = $arrParam['id'];
				$select = $db->select()
					->from('menu AS m',array('id','name','parents','status','order','created_by'));
				$result = $db->fetchAll($select);
				$newArray = array();
				foreach ($cid as $key => $val){
					$id = $val;
					$newArray[] = array('id' => $id);
					$system = new Zendvn_System_Recursive($result);
					$tmp = $system->builArray($id);
					foreach ($tmp as $keyTmp => $valTmp){
						$newArray[] = $valTmp;
					}
				}
			}

			if(count($newArray)>0){
				foreach ($newArray as $keyNew => $valNew){
					$where = ' id=' . $valNew['id'];
					$result = $this->delete($where);
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




