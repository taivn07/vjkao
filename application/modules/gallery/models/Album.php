<?php
class Gallery_Model_Album extends Zend_Db_Table{
	
	protected $_name = 'gallery_album';
	protected $_primary ='id';
	
	protected $_ids;
	
	public function getItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('gallery_album AS ga')
			->joinLeft('gallery_category AS gc', 'ga.cat_id = gc.id',array('gc.name AS category_name'))
			->joinLeft('users AS u', 'ga.created_by = u.id',array('u.user_name AS user_name'))
			->where('ga.id = ?', $arrParam['id'], INTEGER);
				
			$result = $db->fetchRow($select);
		}
		
		if($options['task'] == 'public-detail'){
			$select = $this->select()
					->where('id = ?',$arrParam['id'],INTERGER);
			$result = $this->fetchRow($select)->toArray();
		}
		
		if($options['task'] == 'public-category'){
			if(!empty($arrParam['cid'])){
				$db = Zend_Registry::get('connectDb');
				//$db = Zend_Db::factory($adapter, $config);
				$select = $db->select()
				->from('gallery_category AS gc',array('name','content','picture','meta_title','meta_description','meta_keywords'))
				->where('gc.id = ?', $arrParam['cid'], INTEGER);
				$result = $db->fetchRow($select);
			}else{
				$language = Zend_Registry::get('language');
				$result = array('name' => $language['language']['galleryThuVienAnh'],
						'meta_title' => '',
						'meta_description' => '',
						'meta_keywords' => ''
				);
			}
		}
		
		return $result;
	}
	
	public function countItem($arrParam = null, $options = null){
		$ssFilter  = $arrParam['ssFilter'];
		if($options['task'] == 'admin-list'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			
			$select = $db->select()
			->from('gallery_album AS ga', array('COUNT(ga.id) AS totalItem'));
			
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('ga.name LIKE ?', $keywords, STRING);
			}
			
			if($ssFilter['cat_id']>0){
				$select_category = $db->select()
				->from('gallery_category',array('id','name','parents'))
				->where('status = ?',1,INTERGER);
				$resultCategory = $db->fetchAll($select_category);
				$system = new Zendvn_System_Recursive($resultCategory);
				$newArray = $system->builArray($ssFilter['cat_id']);
				$tmp[] = $ssFilter['cat_id'];
				if(count($newArray)>0){
					foreach ($newArray as $key => $val){
						$tmp[] = $val['id'];
					}
				}
				$ids = implode(',', $tmp);
				$select->where('ga.cat_id IN (' . $ids . ')');
			}
			
			if(!empty($ssFilter['blocks'])){
				$select->where('ga.' . $ssFilter['blocks'] . '= ?', 1, INTERGER);
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("ga.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
			
			$result = $db->fetchOne($select);
		}
		
		if($options['task'] == 'public-category'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			if(!empty($arrParam['cid'])){
				$select = $db->select()
				->from('gallery_album AS ga',array('COUNT(ga.id) AS totalItem'))
				->where('ga.status = ?',1,INTERGER);
				if(!empty($this->_ids)){
					$select->where('ga.cat_id IN (' . $this->_ids . ')');
				}
			}else{
				$select = $db->select()
				->from('gallery_album AS ga',array('COUNT(ga.id) AS totalItem'))
				->where('ga.status = ?',1,INTERGER);
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("ga.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
			
			$result = $db->fetchOne($select);
		}
		return $result;
	}
	
	public function itemInSelectbox($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		$ssFilter  = $arrParam['ssFilter'];
		if($options == null){
			$select = $db->select()
			->from('gallery_category AS gc',array('id','name','parents','status','order','created_by'))
			->order('gc.order ASC');
			if(!empty($ssFilter['lang_code'])){
				$select->where("gc.lang_code = '" . $ssFilter['lang_code'] . "'");
			}else{
				$language = new Zend_Session_Namespace('language');
				$select->where("gc.lang_code = '" . $language->lang . "'");
			}
			$result = $db->fetchAll($select);
		}
		
		$system = new Zendvn_System_Recursive($result);
		$result = $system->builArray(0);
		$tmp = array('id' => 0,'name'=>'Chọn danh mục','level'=>1,'parents'=>0,'order'=>1);
		
		if(count($result)>0){
			array_unshift($result, $tmp);
		}else{
			$result[] = $tmp;
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
			->from('gallery_album AS ga',array('id','name','alias','picture','thumb','cat_id','order','hits','status','lang_code'))
			->joinLeft('gallery_category AS gc', 'gc.id = ga.cat_id',array('gc.name AS category_name'))
			->joinLeft('gallery_image AS gi', 'gi.album_id = ga.id','COUNT(gi.id) AS total_image')
			->group('ga.id');
		
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
				$select->where('ga.name LIKE ?', $keywords, STRING);
			}
				
			if($ssFilter['cat_id']>0){
				$select_category = $db->select()
				->from('gallery_category',array('id','name','parents'))
				->where('status = ?',1,INTERGER);
				$resultCategory = $db->fetchAll($select_category);
				$system = new Zendvn_System_Recursive($resultCategory);
				$newArray = $system->builArray($ssFilter['cat_id']);
				$tmp[] = $ssFilter['cat_id'];
				if(count($newArray)>0){
					foreach ($newArray as $key => $val){
						$tmp[] = $val['id'];
					}
				}
				$ids = implode(',', $tmp);
				$select->where('ga.cat_id IN (' . $ids . ')');
			}
			
			if(!empty($ssFilter['blocks'])){
				$select->where('ga.' . $ssFilter['blocks'] . '= ?', 1, INTERGER);
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("ga.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
		
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-category'){
			if(!empty($arrParam['cid'])){
				$select = $db->select()
						->from('gallery_category',array('id','name','parents'))
						->where('status = ?',1,INTERGER);
				$resultCategory = $db->fetchAll($select);
				$system = new Zendvn_System_Recursive($resultCategory);
				$newArray = $system->builArray($arrParam['cid']);
				$tmp[] = $arrParam['cid'];
				if(count($newArray)>0){
					foreach ($newArray as $key => $val){
						$tmp[] = $val['id'];
					}
				}
				$ids = implode(',', $tmp);
				$this->_ids = $ids;
				$paginator = $arrParam['paginator'];
				$select = $db->select()
				->from('gallery_album AS ga',array('id','name','alias','picture','thumb','synopsis','cat_id'))
				->join('gallery_category AS gc', 'gc.id = ga.cat_id',array('name AS category_name', 'gc.alias AS category_alias'))
				->where('ga.status = ?',1,INTERGER)
				->where('ga.cat_id IN (' . $ids . ')')
				->order('ga.order ASC')
				->order('ga.id DESC');
			}else{
				$paginator = $arrParam['paginator'];
				$select = $db->select()
				->from('gallery_album AS ga',array('id','name','alias','picture','thumb','synopsis','cat_id'))
				->join('gallery_category AS gc', 'gc.id = ga.cat_id',array('name AS category_name', 'gc.alias AS category_alias'))
				->where('ga.status = ?',1,INTERGER)
				->order('ga.order ASC')
				->order('ga.id DESC');
			}
			
			if($paginator['itemCountPerPage'] > 0){
				$page = $paginator['currentPage'];
				$rowCount = $paginator['itemCountPerPage'];
				$select->limitPage($page, $rowCount);
			}
				
			if(!empty($ssFilter['lang_code'])){
				$select->where("ga.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
			
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-khac'){
			$select = $db->select()
			->from('gallery_album AS ga',array('id','name','alias','picture','thumb','synopsis','cat_id'))
			->join('gallery_category AS gc', 'gc.id = ga.cat_id',array('name AS category_name', 'gc.alias AS category_alias'))
			->where('ga.status = ?',1,INTERGER)
			->where('ga.cat_id = ?', $arrParam['cid'], INTERGER)
			->where('ga.id != ?', $arrParam['id'], INTERGER)
			->order('rand()')
			->limit(3,0);
		
			if(!empty($ssFilter['lang_code'])){
				$select->where("ga.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
		
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-comment'){
				
			$paginator = $arrParam['paginator'];
				
			$select = $db->select()
			->from('comment AS c')
			->where('status = ?',1,INTERGER)
			->where('item_id = ?',$arrParam['id'],INTERGER)
			->order('id ASC');
				
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
			$row->synopsis			= stripslashes($arrParam['synopsis']);
			$row->content 			= stripslashes($arrParam['content']);
			$row->created 			= @date("Y-m-d h:m:s");
			$row->created_by		= $created_by;
			$row->order 			= $arrParam['order'];
			$row->status 			= $arrParam['status'];
			$row->block_noibat 		= $arrParam['block_noibat'];
			$row->block_hot 		= $arrParam['block_hot'];
			$row->cat_id 			= $arrParam['cat_id'];
			$row->tags	 			= stripslashes($arrParam['tags']);
			$row->meta_title 		= stripslashes($arrParam['meta_title']);
			$row->meta_description 	= stripslashes($arrParam['meta_description']);
			$row->meta_keywords 	= stripslashes($arrParam['meta_keywords']);
			$row->lang_code 		= $arrParam['lang_code'];

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
			$row->synopsis			= stripslashes($arrParam['synopsis']);
			$row->content 			= stripslashes($arrParam['content']);
			$row->modified 			= @date("Y-m-d h:m:s");
			$row->modified_by		= $modified_by;
			$row->order 			= $arrParam['order'];
			$row->status 			= $arrParam['status'];
			$row->block_noibat 		= $arrParam['block_noibat'];
			$row->block_hot 		= $arrParam['block_hot'];
			$row->cat_id 			= $arrParam['cat_id'];
			$row->tags	 			= stripslashes($arrParam['tags']);
			$row->meta_title 		= stripslashes($arrParam['meta_title']);
			$row->meta_description 	= stripslashes($arrParam['meta_description']);
			$row->meta_keywords 	= stripslashes($arrParam['meta_keywords']);
			$row->lang_code 		= $arrParam['lang_code'];
				
			$row->save();
		}
	}

	public function deleteItem($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		$id = $arrParam['id'];
		if($options['task'] == 'admin-delete'){
			$select_item = $db->select()
			->from('gallery_image AS gi', array('COUNT(gi.id) AS totalItem'))
			->where('album_id = ?',$id, INTERGER);
			$result_item = $db->fetchOne($select_item);
			if($result_item == 0){
				$where = ' id=' . $arrParam['id'];
				$result = $this->delete($where);
			}
		}
	
		if($options['task'] == 'admin-delete-muti'){
			$cid = explode(',', $arrParam['cid']);
			if(!empty($cid) && isset($arrParam['cid'])){
				$ids = implode(',', $cid);
				$select_item = $db->select()
				->from('gallery_image AS gi', array('COUNT(gi.id) AS totalItem'))
				->where('album_id IN (' . $ids . ')');
				$result_item = $db->fetchOne($select_item);
				
				if($result_item == 0){
					foreach ($cid as $key => $val){
						$where = ' id=' . $val;
						$result = $this->delete($where);
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