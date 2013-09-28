<?php
class Block_BlkProductCategoryHome extends Zend_View_Helper_Abstract{

	public function blkProductCategoryHome($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$filename = APPLICATION_PATH . '/modules/shopping/config/config.ini';
		$section = 'module-settings';
		$moduleConfig = new Zend_Config_Ini($filename, $section);
		$moduleConfig = $moduleConfig->toArray();
		$moduleConfig = $moduleConfig['module'];
		$language = new Zend_Session_Namespace('language');
		$flagShow = true;
		if($flagShow == true){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
					->from('product_category AS pc',array('id','name','alias','parents'))
					->where('status = ?',1,INTERGER)
					->where('block_body = ?',1,INTERGER)
					->order('order ASC');
			$language = new Zend_Session_Namespace('language');
			$select->where("pc.lang_code = '" . $language->lang . "'");
			$row = $db->fetchAll($select);
			foreach ($row as $key => $val){
				
				$select_cat = $db->select()
				->from('product_category AS pc',array('id','name','alias','parents'))
				->where('status = ?',1,INTERGER);
				$language = new Zend_Session_Namespace('language');
				$select_cat->where("pc.lang_code = '" . $language->lang . "'");
				$resultCategory = $db->fetchAll($select_cat);
				
				$system = new Zendvn_System_Recursive($resultCategory);
				$newArray = $system->builArray($row[$key]['id']);
				$tmp[$key][] = $row[$key]['id'];
				if(count($newArray)>0){
					foreach ($newArray as $key_cat => $val_cat){
						$tmp[$key][] = $val_cat['id'];
					}
				}
				$ids = implode(',', $tmp[$key]);
				
				$select_item = $db->select()
					->from('products AS p')
					->join('product_category AS pc', 'pc.id = p.cat_id',array('name AS category_name', 'pc.alias AS category_alias'))
					->where('p.status = ?',1,INTERGER)
					->where('p.cat_id IN (' . $ids . ')')
					->limit(12,0)
					->order('p.order ASC')
					->order('p.id DESC');
				$row_item = $db->fetchAll($select_item);
				$row[$key]['items'] = $row_item;
			}
			if (count ( $row ) > 0) {
				require_once (BLOCK_PATH . '/BlkProductCategoryHome/'.$template.'.php');
			}
		}
	}
}