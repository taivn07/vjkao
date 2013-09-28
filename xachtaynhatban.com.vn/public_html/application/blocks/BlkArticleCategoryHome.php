<?php
class Block_BlkArticleCategoryHome extends Zend_View_Helper_Abstract{

	public function blkArticleCategoryHome($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
					->from('article_category AS ac',array('id','name','alias','parents'))
					->where('status = ?',1,INTERGER)
					->where('block_body = ?',1,INTERGER)
					->order('order ASC');
			$language = new Zend_Session_Namespace('language');
			$select->where("ac.lang_code = '" . $language->lang . "'");
			$row = $db->fetchAll($select);
			foreach ($row as $key => $val){
				
				$select_cat = $db->select()
				->from('article_category AS ac',array('id','name','alias','parents'))
				->where('status = ?',1,INTERGER);
				$language = new Zend_Session_Namespace('language');
				$select_cat->where("ac.lang_code = '" . $language->lang . "'");
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
					->from('articles AS a',array('id','name','alias','picture','thumb','synopsis','cat_id'))
					->join('article_category AS ac', 'ac.id = a.cat_id',array('name AS category_name', 'ac.alias AS category_alias'))
					->where('a.status = ?',1,INTERGER)
					->where('a.cat_id IN (' . $ids . ')')
					->limit(5,0)
					->order('a.id DESC');
				$row_item = $db->fetchAll($select_item);
				$row[$key]['items'] = $row_item;
			}
			if(count($row) > 0){
				require_once (BLOCK_PATH . '/BlkArticleCategoryHome/'.$template.'.php');
			}
		}
	}
}