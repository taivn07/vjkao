<?php
class Block_BlkHoTro extends Zend_View_Helper_Abstract{

	public function blkHoTro($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('support AS s')
			->where('s.status = ?', 1, INTERGER)
			->order('s.order ASC');
			$row = $db->fetchAll($select);
			require_once (BLOCK_PATH . '/BlkHoTro/'.$template.'.php');
		}
	}
}