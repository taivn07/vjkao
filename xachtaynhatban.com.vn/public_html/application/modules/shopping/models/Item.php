<?php
class Shopping_Model_Item extends Zend_Db_Table{
	
	protected $_name = 'products';
	protected $_primary ='id';
	
	protected $_ids;
	
	public function getItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('products AS p')
			->joinLeft('product_category AS pc', 'p.cat_id = pc.id',array('pc.name AS category_name','pc.alias AS category_alias'))
			->joinLeft('product_units AS pu', 'p.unit_id = pu.id',array('pu.name AS units_name'))
			->joinLeft('product_sources AS ps', 'p.sources_id = ps.id',array('ps.name AS sources_name'))
			->joinLeft('users AS u', 'p.created_by = u.id',array('u.user_name AS user_name'))
			->where('p.id = ?', $arrParam['id'], INTEGER);
				
			$result = $db->fetchRow($select);
		}
		
		if($options['task'] == 'public-detail'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('products AS p')
			->joinLeft('product_category AS pc', 'p.cat_id = pc.id',array('pc.name AS category_name','pc.alias AS category_alias'))
			->joinLeft('product_units AS pu', 'p.unit_id = pu.id',array('pu.name AS units_name'))
			->joinLeft('product_sources AS ps', 'p.sources_id = ps.id',array('ps.name AS sources_name'))
			->joinLeft('users AS u', 'p.created_by = u.id',array('u.user_name AS user_name'))
			->where('p.id = ?', $arrParam['id'], INTEGER);
				
			$result = $db->fetchRow($select);
		}
		
		if($options['task'] == 'public-category'){
			if(!empty($arrParam['cid'])){
				$db = Zend_Registry::get('connectDb');
				//$db = Zend_Db::factory($adapter, $config);
				$select = $db->select()
				->from('product_category AS pc',array('id','name','alias','picture','picture_multi','content','meta_title','meta_description','meta_keywords'))
				->where('pc.id = ?', $arrParam['cid'], INTEGER);
				$result = $db->fetchRow($select);
			}else{
				$language = Zend_Registry::get('language');
				$result = array('name' => $language['language']['productSanPham'],
						'meta_title' => '',
						'meta_description' => '',
						'meta_keywords' => ''
				);
			}
		}
		
		return $result;
	}
	
	public function getOther($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);

		if($options['task'] == 'get-sources'){
			$select = $db->select()
			->from('product_sources AS ps',array('id','name'));
			$result = $db->fetchPairs($select);
			$result[0] = ' -- Chọn nhà cung cấp -- ';
			ksort($result);
		}
		if($options['task'] == 'get-shop'){
			
			$select = $db->select()
				->from('product_shop AS ps',array('id','name'));
			
			if(!empty($arrParam['cat_id'])){
				$select->where('ps.cat_id = ?', $arrParam['cat_id'], INTERGER);
			}
			
			$result = $db->fetchPairs($select);
			$result[0] = ' -- Chọn shop -- ';
			ksort($result);
		}
		return $result;
	}
	
	public function countItem($arrParam = null, $options = null){
		
		if($options == null){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('products AS p', array('COUNT(p.id) AS totalItem'));
			$result = $db->fetchOne($select);
		}
		if($options['task'] == 'admin-list'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$ssFilter  = $arrParam['ssFilter'];
			$select = $db->select()
			->from('products AS p', array('COUNT(p.id) AS totalItem'));
			
			if(!empty($ssFilter['keywords'])){
				$keywords = '%' . $ssFilter['keywords'] . '%';
				$select->where('p.name LIKE ?', $keywords, STRING);
			}
			
			if($ssFilter['cat_id']>0){
				$select_category = $db->select()
				->from('product_category',array('id','name','parents'))
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
				$select->where('p.cat_id IN (' . $ids . ')');
			}
			
			if(!empty($ssFilter['blocks'])){
				$select->where('p.' . $ssFilter['blocks'] . '= ?', 1, INTERGER);
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
			
			$result = $db->fetchOne($select);
		}

		if($options['task'] == 'public-category'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			if(!empty($arrParam['cid'])){
				$select = $db->select()
				->from('products AS p',array('COUNT(p.id) AS totalItem'))
				->where('p.status = ?',1,INTERGER)
				->where('p.cat_id IN (' . $this->_ids . ')');
			}else{
				$select = $db->select()
				->from('products AS p',array('COUNT(p.id) AS totalItem'))
				->where('p.status = ?',1,INTERGER);
			}
			
			if(!empty($arrParam['manu'])){
				$select->where('p.manu_id = ?',$arrParam['manu'],INTERGER);
			}
				
			if(!empty($arrParam['min'])){
				$select->where('p.price > ?',$arrParam['min'],INTERGER);
			}
				
			if(!empty($arrParam['max'])){
				$select->where('p.price < ?',$arrParam['max'],INTERGER);
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
				
			$result = $db->fetchOne($select);
		}
		
		if($options['task'] == 'public-index'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			if(!empty($arrParam['cid'])){
				$select = $db->select()
				->from('products AS p',array('COUNT(p.id) AS totalItem'))
				->where('p.status = ?',1,INTERGER)
				->where('p.cat_id IN (' . $this->_ids . ')');
			}else{
				$select = $db->select()
				->from('products AS p',array('COUNT(p.id) AS totalItem'))
				->where('p.status = ?',1,INTERGER);
			}
				
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
				
			$result = $db->fetchOne($select);
		}
		
		if($options['task'] == 'public-search'){
			$ssFilter  = $arrParam['ssFilter'];
			$db = Zend_Registry::get('connectDb');
			$select = $db->select()
			->from('products AS p',array('COUNT(p.id) AS totalItem'))
			->where('p.status = ?',1,INTERGER)
			->order('id DESC');

			if(!empty($ssFilter['keywords'])){
				$select->where("(p.name like '%" . $ssFilter['keywords'] . "%') OR (p.alias like '%" . $ssFilter['keywords'] . "%')");
			}
			
			if(!empty($arrParam['manu'])){
				$select->where('p.manu_id = ?',$arrParam['manu'],INTERGER);
			}
			
			if(!empty($arrParam['min'])){
				$select->where('p.price > ?',$arrParam['min'],INTERGER);
			}
			
			if(!empty($arrParam['max'])){
				$select->where('p.price < ?',$arrParam['max'],INTERGER);
			}
		
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
			$result = $db->fetchOne($select);
		}
		
		if($options['task'] == 'public-block'){
			$ssFilter  = $arrParam['ssFilter'];
			$db = Zend_Registry::get('connectDb');
			$select = $db->select()
			->from('products AS p',array('COUNT(p.id) AS totalItem'))
			->where('p.status = ?',1,INTERGER);
			
			if(!empty($options['block'])){
				if($options['block'] == 'block_khuyenmai'){
					$select->where('(p.khuyenmai != "") or (p.selloff != 0)');
				}else{
					$select->where('p.'.$options['block'].' = ?',1,INTERGER);
				}
			}
		
			if(!empty($arrParam['manu'])){
				$select->where('p.manu_id = ?',$arrParam['manu'],INTERGER);
			}
		
			if(!empty($arrParam['min'])){
				$select->where('p.price > ?',$arrParam['min'],INTERGER);
			}
		
			if(!empty($arrParam['max'])){
				$select->where('p.price < ?',$arrParam['max'],INTERGER);
			}
		
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
			$result = $db->fetchOne($select);
		}
		
		if($options['task'] == 'public-filter'){
			
			$ssFilter  = $arrParam['ssFilter'];
			$db = Zend_Registry::get('connectDb');
			$select = $db->select()
			->from('products AS p',array('COUNT(p.id) AS totalItem'))
			->where('p.status = ?',1,INTERGER)
			->order('id DESC');
			
			if($options['filter'] == 'tags'){
				if(!empty($arrParam['key'])){
					$select->where("p.tags like '%" . $arrParam['key'] . "%'");
				}
			}
			
			if($options['filter'] == 'search'){
				if(!empty($ssFilter['keywords'])){
					$select->where("(p.name like '%" . $ssFilter['keywords'] . "%') OR (p.alias like '%" . $ssFilter['keywords'] . "%')");
				}
			}
				
			if(!empty($arrParam['manu'])){
				$select->where('p.manu_id = ?',$arrParam['manu'],INTERGER);
			}
				
			if(!empty($arrParam['min'])){
				$select->where('p.price > ?',$arrParam['min'],INTERGER);
			}
				
			if(!empty($arrParam['max'])){
				$select->where('p.price < ?',$arrParam['max'],INTERGER);
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
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
			->from('product_category AS pc',array('id','name','parents','status','order','created_by'))
			->order('pc.order ASC')
			->order('pc.id ASC');
			if(!empty($ssFilter['lang_code'])){
				$select->where("pc.lang_code = '" . $ssFilter['lang_code'] . "'");
			}else{
				$language = new Zend_Session_Namespace('language');
				$select->where("pc.lang_code = '" . $language->lang . "'");
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
		
		if($options['task'] == 'admin-list'){
			
			$paginator = $arrParam['paginator'];
			$ssFilter  = $arrParam['ssFilter'];
			
			$select = $db->select()
			->from('products AS p',array('id','code','name','alias','picture','thumb','price','selloff','units_money','cat_id','order','hits','status','lang_code'))
			->joinLeft('product_category AS pc', 'pc.id = p.cat_id',array('pc.name AS category_name'));
		
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
				$select->where('p.name LIKE ?', $keywords, STRING);
			}
				
			if($ssFilter['cat_id']>0){
				$select_category = $db->select()
				->from('product_category',array('id','name','parents'))
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
				$select->where('p.cat_id IN (' . $ids . ')');
			}
			
			if(!empty($ssFilter['blocks'])){
				$select->where('p.' . $ssFilter['blocks'] . '= ?', 1, INTERGER);
			}
			
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
		
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'view-cart'){
			if(count($arrParam['cart'])>0){
				$i = 1;
				foreach ($arrParam['cart'] as $key => $val){
					if($i == 1){
						$ids .= $key;
					}else{
						$ids .= ',' . $key;
					}
					$i++;
				}
				$select = $db->select()
				->from('products AS p',array('id','code','name','alias','picture','thumb','cat_id','price','selloff','units_money'))
				->join('product_category AS pc', 'pc.id = p.cat_id',array('pc.name AS category_name', 'pc.alias AS category_alias'))
				->where('p.status = ?',1,INTERGER)
				->where('p.id IN (' . $ids . ')');
				$result = $db->fetchAll($select);
			}
		}
		
		if($options['task'] == 'public-category'){
			if(!empty($arrParam['cid'])){
				$select = $db->select()
				->from('product_category',array('id','name','parents'))
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
				->from('products AS p')
				->join('product_category AS pc', 'pc.id = p.cat_id',array('name AS category_name', 'pc.alias AS category_alias'))
				->where('p.status = ?',1,INTERGER)
				->where('p.cat_id IN (' . $ids . ')')
				->order('id DESC');
			}else{
				$paginator = $arrParam['paginator'];
				$select = $db->select()
				->from('products AS p')
				->join('product_category AS pc', 'pc.id = p.cat_id',array('name AS category_name', 'pc.alias AS category_alias'))
				->where('p.status = ?',1,INTERGER)
				->order('id DESC');
			}
				
			if($paginator['itemCountPerPage'] > 0){
				$page = $paginator['currentPage'];
				$rowCount = $paginator['itemCountPerPage'];
				$select->limitPage($page, $rowCount);
			}
			
			if(!empty($arrParam['manu'])){
				$select->where('p.manu_id = ?',$arrParam['manu'],INTERGER);
			}
			
			if(!empty($arrParam['min'])){
				$select->where('p.price > ?',$arrParam['min'],INTERGER);
			}
			
			if(!empty($arrParam['max'])){
				$select->where('p.price < ?',$arrParam['max'],INTERGER);
			}
		
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
				
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-khac'){
			$select = $db->select()
				->from('products AS p')
				->join('product_category AS pc', 'pc.id = p.cat_id',array('name AS category_name', 'pc.alias AS category_alias'))
				->where('p.status = ?',1,INTERGER)
				->where('p.cat_id = ?', $arrParam['cid'], INTERGER)
				->where('p.id != ?', $arrParam['id'], INTERGER)
				->order('rand()')
				->limit(3,0);
		
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
				
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-khac-cart'){
			$select = $db	->	select()
							->	from('products AS p')
							->	join('product_category AS pc', 'pc.id = p.cat_id',array('name AS category_name', 'pc.alias AS category_alias'))
							->	where('p.status = ?',1)
							->	order('rand()')
							->	limit(5,0);
		
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
		
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-search'){
			$ssFilter  = $arrParam['ssFilter'];
			$paginator = $arrParam['paginator'];
			$select = $db->select()
				->from('products AS p')
				->join('product_category AS pc', 'pc.id = p.cat_id',array('name AS category_name', 'pc.alias AS category_alias'))
				->where('p.status = ?',1,INTERGER)
				->order('id DESC');
			
			if(!empty($ssFilter['keywords'])){
				$select->where("(p.name like '%" . $ssFilter['keywords'] . "%') OR (p.alias like '%" . $ssFilter['keywords'] . "%')");
			}
			
			if(!empty($arrParam['manu'])){
				$select->where('p.manu_id = ?',$arrParam['manu'],INTERGER);
			}
				
			if(!empty($arrParam['min'])){
				$select->where('p.price > ?',$arrParam['min'],INTERGER);
			}
				
			if(!empty($arrParam['max'])){
				$select->where('p.price < ?',$arrParam['max'],INTERGER);
			}

			if($paginator['itemCountPerPage'] > 0){
				$page = $paginator['currentPage'];
				$rowCount = $paginator['itemCountPerPage'];
				$select->limitPage($page, $rowCount);
			}
		
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
			}

			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-block'){
			$ssFilter  = $arrParam['ssFilter'];
			$paginator = $arrParam['paginator'];
			$select = $db->select()
			->from('products AS p')
			->join('product_category AS pc', 'pc.id = p.cat_id',array('name AS category_name', 'pc.alias AS category_alias'))
			->where('p.status = ?',1,INTERGER)
			->order('p.order ASC')
			->order('p.id DESC');
			
			if(!empty($options['block'])){
				if($options['block'] == 'block_khuyenmai'){
					$select->where('(p.khuyenmai != "") or (p.selloff != 0)');
				}else{
					$select->where('p.'.$options['block'].' = ?',1,INTERGER);
				}
			}
		
			if(!empty($arrParam['manu'])){
				$select->where('p.manu_id = ?',$arrParam['manu'],INTERGER);
			}
		
			if(!empty($arrParam['min'])){
				$select->where('p.price > ?',$arrParam['min'],INTERGER);
			}
		
			if(!empty($arrParam['max'])){
				$select->where('p.price < ?',$arrParam['max'],INTERGER);
			}
		
			if($paginator['itemCountPerPage'] > 0){
				$page = $paginator['currentPage'];
				$rowCount = $paginator['itemCountPerPage'];
				$select->limitPage($page, $rowCount);
			}
		
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
		
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-filter'){
			$ssFilter  = $arrParam['ssFilter'];
			$paginator = $arrParam['paginator'];
			$select = $db->select()
			->from('products AS p',array('id','code','name','alias','picture','thumb','cat_id','price','selloff','units_money','khuyenmai'))
			->join('product_category AS pc', 'pc.id = p.cat_id',array('name AS category_name', 'pc.alias AS category_alias'))
			->where('p.status = ?',1,INTERGER)
			->order('id DESC');
			
			if($options['filter'] == 'tags'){
				if(!empty($arrParam['key'])){
					$select->where("p.tags like '%" . $arrParam['key'] . "%'");
				}
			}
			
			if($options['filter'] == 'search'){
				if(!empty($ssFilter['keywords'])){
					$select->where("(p.name like '%" . $ssFilter['keywords'] . "%') OR (p.alias like '%" . $ssFilter['keywords'] . "%')");
				}
			}
				
			if(!empty($arrParam['manu'])){
				$select->where('p.manu_id = ?',$arrParam['manu'],INTERGER);
			}
		
			if(!empty($arrParam['min'])){
				$select->where('p.price > ?',$arrParam['min'],INTERGER);
			}
		
			if(!empty($arrParam['max'])){
				$select->where('p.price < ?',$arrParam['max'],INTERGER);
			}
		
			if($paginator['itemCountPerPage'] > 0){
				$page = $paginator['currentPage'];
				$rowCount = $paginator['itemCountPerPage'];
				$select->limitPage($page, $rowCount);
			}
		
			if(!empty($ssFilter['lang_code'])){
				$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
			}
		
			$result = $db->fetchAll($select);
		}
		
		if($options['task'] == 'public-ajax'){
			if($arrParam['keywords'] != ''){
				$ssFilter  = $arrParam['ssFilter'];
					
				$select = $db->select()
				->from('products AS p')
				->join('product_category AS pc', 'pc.id = p.cat_id',array('name AS category_name', 'pc.alias AS category_alias'))
				->where('p.status = ?',1,INTERGER)
				->where("(p.name like '%" . $arrParam['keywords'] . "%') OR (p.alias like '%" . $arrParam['keywords'] . "%')")
				->limit(12,0)
				->order('id DESC');
					
				if(!empty($ssFilter['lang_code'])){
					$select->where("p.lang_code = '" . $ssFilter['lang_code'] . "'");
				}
			}
		
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
			
			$row->code 				= $arrParam['code'];
			$row->name 				= stripslashes($arrParam['name']);
			$row->alias 			= $alias;
			$row->picture 			= $arrParam['picture'];
			$row->thumb 			= $thumb->filter($arrParam['picture']);
			$row->picture_multi		= stripslashes($arrParam['picture_multi']);
			$row->price 			= $filter->filter($arrParam['price']);
			$row->units_money		= $arrParam['units_money'];
			$row->selloff			= $arrParam['selloff'];
			$row->product_number	= $filter->filter($arrParam['product_number']);
			$row->money_sources		= $filter->filter($arrParam['money_sources']);
			$row->sources_money		= $arrParam['sources_money'];
			$row->synopsis			= stripslashes($arrParam['synopsis']);
			$row->content 			= stripslashes($arrParam['content']);
			$row->created 			= @date("Y-m-d h:m:s");
			$row->created_by		= $created_by;
			$row->order 			= $arrParam['order'];
			$row->status 			= $arrParam['status'];
			$row->tinh_trang 		= $arrParam['tinh_trang'];
			$row->block_noibat 		= $arrParam['block_noibat'];
			$row->block_moi 		= $arrParam['block_moi'];
			$row->block_hot 		= $arrParam['block_hot'];
			$row->block_banchay 	= $arrParam['block_banchay'];
			$row->cat_id 			= $arrParam['cat_id'];
			$row->unit_id 			= $arrParam['unit_id'];
			$row->sources_id 		= $arrParam['sources_id'];
			$row->manu_id	 		= $arrParam['manu_id'];
			$row->vat 				= $arrParam['vat'];
			$row->baohanh_number 	= $arrParam['baohanh_number'];
			$row->baohanh_date	 	= $arrParam['baohanh_date'];
			$row->vanchuyen	 		= $arrParam['vanchuyen'];
			$row->khuyenmai	 		= $arrParam['khuyenmai'];
			$row->tags	 			= stripslashes($arrParam['tags']);
			$row->meta_title 		= stripslashes($arrParam['meta_title']);
			$row->meta_description 	= stripslashes($arrParam['meta_description']);
			$row->meta_keywords 	= stripslashes($arrParam['meta_keywords']);
			$row->lang_code 		= $arrParam['lang_code'];
			$row->danhgia	 		= $arrParam['danhgia'];
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
			
			$row->code 				= $arrParam['code'];
			$row->name 				= stripslashes($arrParam['name']);
			$row->alias 			= $alias;
			$row->picture 			= $arrParam['picture'];
			$row->thumb 			= $thumb->filter($arrParam['picture']);
			$row->picture_multi		= stripslashes($arrParam['picture_multi']);
			$row->price 			= $filter->filter($arrParam['price']);
			$row->units_money		= $arrParam['units_money'];
			$row->selloff			= $arrParam['selloff'];
			$row->product_number	= $filter->filter($arrParam['product_number']);
			$row->money_sources		= $filter->filter($arrParam['money_sources']);
			$row->sources_money		= $arrParam['sources_money'];
			$row->synopsis			= stripslashes($arrParam['synopsis']);
			$row->content 			= stripslashes($arrParam['content']);
			$row->modified 			= @date("Y-m-d h:m:s");
			$row->modified_by		= $modified_by;
			$row->order 			= $arrParam['order'];
			$row->status 			= $arrParam['status'];
			$row->tinh_trang 		= $arrParam['tinh_trang'];
			$row->block_noibat 		= $arrParam['block_noibat'];
			$row->block_moi 		= $arrParam['block_moi'];
			$row->block_hot 		= $arrParam['block_hot'];
			$row->block_banchay 	= $arrParam['block_banchay'];
			$row->cat_id 			= $arrParam['cat_id'];
			$row->unit_id 			= $arrParam['unit_id'];
			$row->sources_id 		= $arrParam['sources_id'];
			$row->manu_id	 		= $arrParam['manu_id'];
			$row->vat 				= $arrParam['vat'];
			$row->baohanh_number 	= $arrParam['baohanh_number'];
			$row->baohanh_date	 	= $arrParam['baohanh_date'];
			$row->vanchuyen	 		= $arrParam['vanchuyen'];
			$row->khuyenmai	 		= $arrParam['khuyenmai'];
			$row->tags	 			= stripslashes($arrParam['tags']);
			$row->meta_title 		= stripslashes($arrParam['meta_title']);
			$row->meta_description 	= stripslashes($arrParam['meta_description']);
			$row->meta_keywords 	= stripslashes($arrParam['meta_keywords']);
			$row->lang_code 		= $arrParam['lang_code'];
			$row->danhgia	 		= $arrParam['danhgia'];
				
			$row->save();
		}
		
		if($options['task'] == 'public-hits'){
			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);
				
			$row->hits 		= $arrParam['hits'] + 1;
				
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