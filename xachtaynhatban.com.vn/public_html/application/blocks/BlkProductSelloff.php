<?php
class Block_BlkProductSelloff extends Zend_View_Helper_Abstract{

	public function blkProductSelloff(){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
					->from('products AS p',array('id','name','alias','picture','thumb','price','selloff','units_money','cat_id','shop_id'))
					->where('p.status = ?',1,INTERGER)
					->where('p.selloff != ""')
					->limit(6,0)
					->order('rand()');
			
			$language = new Zend_Session_Namespace('language');
			$select->where("p.lang_code = '" . $language->lang . "'");
			
			$row = $db->fetchAll($select);
			require_once (BLOCK_PATH . '/BlkProductSelloff/default.php');
		}
	}
}