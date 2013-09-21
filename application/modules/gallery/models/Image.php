<?php
class Gallery_Model_Image extends Zend_Db_Table{
	
	protected $_name = 'gallery_image';
	protected $_primary ='id';
	
	protected $_ids;
	
	public function getItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('gallery_image AS gi')
			->joinLeft('gallery_category AS gc', 'gi.album_id = gc.id',array('gc.name AS album_name'))
			->where('gi.id = ?', $arrParam['id'], INTEGER);
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
				->from('gallery_category AS gc',array('name','meta_title','meta_description','meta_keywords'))
				->where('gc.id = ?', $arrParam['cid'], INTEGER);
				$result = $db->fetchRow($select);
			}else{
				$result = array('name' => 'Gallery',
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
			->from('gallery_image AS gi', array('COUNT(gi.id) AS totalItem'));
			
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('gi.name LIKE ?', $keywords, STRING);
			}
			
			if($arrParam['album_id']>0){
				$select->where('gi.album_id = ?',$arrParam['album_id'],INTERGER);
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
		
		if($options['task'] == 'public-image'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			if(!empty($arrParam['id'])){
				$paginator = $arrParam['paginator'];
				$select = $db->select()
				->from('gallery_image AS gi',array('COUNT(gi.id) AS totalItem'))
				->where('gi.status = ?',1,INTERGER)
				->where('gi.album_id = ?',$arrParam['id'],INTERGER);
			}
				
			$result = $db->fetchOne($select);
		}
		
		return $result;
	}
	
	public function itemInSelectbox($arrParam = null, $options = null){
		
	}
	
	public function getOrther($arrParam = null, $options = null){
		if($options['task'] == 'admin-album'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			if(!empty($arrParam['album_id'])){
				$select = $db->select()
				->from('gallery_album AS ga',array('name'))
				->joinLeft('gallery_category AS gc', 'ga.cat_id = gc.id',array('gc.name AS category_name'))
				->where('ga.id = ?',$arrParam['album_id'],INTERGER);
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
			->from('gallery_image AS gi',array('id','name','alias','picture','thumb','album_id','order','status'))
			->joinLeft('gallery_album AS ga', 'ga.id = gi.album_id',array('ga.name AS album_name'));
		
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
				$select->where('gi.name LIKE ?', $keywords, STRING);
			}
				
			if($arrParam['album_id']>0){
				$select->where('gi.album_id = ?',$arrParam['album_id'],INTERGER);
			}
		
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-image'){
			if(!empty($arrParam['id'])){
				$paginator = $arrParam['paginator'];
				$select = $db->select()
				->from('gallery_image AS gi',array('id','name','alias','picture','thumb','album_id'))
				->join('gallery_album AS ga', 'ga.id = gi.album_id',array('name AS category_name', 'ga.alias AS category_alias'))
				->where('gi.status = ?',1,INTERGER)
				->where('gi.album_id = ?',$arrParam['id'],INTERGER)
				->order('gi.order ASC')
				->order('gi.id DESC');
				
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
			
			$multi_image = explode("\n", $arrParam['multi_image']);

			if(count($multi_image) > 0){
				for($i = 0; $i < count($multi_image) - 1; $i++){
					$row 				= $this->fetchNew();
					
					$row->name 			= stripslashes($arrParam['name']);
					$row->alias 		= $alias;
					$row->picture 		= $multi_image[$i];
					$row->thumb 		= $thumb->filter($multi_image[$i]);
					$row->order 		= $arrParam['order'];
					$row->status 		= $arrParam['status'];
					$row->album_id 		= $arrParam['album_id'];
					
					$row->save();
				}
			}
			
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
			$row->order 			= $arrParam['order'];
			$row->status 			= $arrParam['status'];
			$row->album_id 			= $arrParam['album_id'];
				
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