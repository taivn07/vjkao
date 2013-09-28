<?php
class Block_BlkArticleRight extends Zend_View_Helper_Abstract{

	public function blkArticleRight($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$language = new Zend_Session_Namespace('language');
		$flagShow = true;
		if($flagShow == true){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			
			$select = $db->select()
			->from('article_category AS ac',array('id','name','alias','picture','alias','parents'))
			->where('ac.status = ?',1, INTERGER)
			->where('ac.block_right = ?',1, INTERGER)
			->order('ac.order ASC');
			$row = $db->fetchAll($select);
			
			foreach ($row AS $key => $val){
				
				$select_tmp = $db->select()
				->from('article_category',array('id','name','parents'))
				->where('status = ?',1,INTERGER);
				$resultCategory = $db->fetchAll($select_tmp);
				$system = new Zendvn_System_Recursive($resultCategory);
				$newArray = $system->builArray($val['id']);
				$tmp[$key][] = $val['id'];
				if(count($newArray)>0){
					foreach ($newArray as $key_tmp => $val_tmp){
						$tmp[$key][] = $val_tmp['id'];
					}
				}
				$ids = implode(',', $tmp[$key]);
				//echo '<br>' . $ids;
				$select_item = $db->select()
				->from('articles AS a',array('id','name','alias','picture','thumb','synopsis','cat_id'))
				->join('article_category AS ac', 'ac.id = a.cat_id',array('name AS category_name', 'ac.alias AS category_alias'))
				->where('a.status = ?',1,INTERGER)
				->where('a.cat_id IN (' . $ids . ')')
				->order('id DESC')
				->limit(5,0);
				
				$select_item->where("a.lang_code = '" . $language->lang . "'");
				
				$result_item = $db->fetchAll($select_item);
				$row[$key]['items'] = $result_item;
			}
			
			require_once (BLOCK_PATH . '/BlkArticleRight/'.$template.'.php');
		}
	}
}