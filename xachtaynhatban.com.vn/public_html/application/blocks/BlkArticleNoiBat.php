<?php
class Block_BlkArticleNoiBat extends Zend_View_Helper_Abstract{

	public function blkArticleNoiBat(){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
					->from('articles AS a',array('id','name','alias','picture','cat_id'))
					->join('article_category AS ac', 'ac.id = a.cat_id',array('name AS category_name','alias AS category_alias'))
					->where('a.block_noibat = ?',1,INTERGER)
					->limit(5,0)
					->order('a.order ASC')
					->order('a.id DESC');
			
			$language = new Zend_Session_Namespace('language');
			$select->where("a.lang_code = '" . $language->lang . "'");
			
			$row = $db->fetchAll($select);
			require_once (BLOCK_PATH . '/BlkArticleNoiBat/default.php');
		}
	}
}