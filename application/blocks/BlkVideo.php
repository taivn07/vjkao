<?php
class Block_BlkVideo extends Zend_View_Helper_Abstract{

	public function blkVideo($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('video AS v')
			->where('v.status = ?', 1, INTERGER)
			->where("v.module = '" . $view->arrParam['module'] . "'")
			->order('v.order ASC');
			$row = $db->fetchAll($select);
			if(count($row) == 0){
				$select = $db->select()
				->from('video AS v')
				->where('v.status = ?', 1, INTERGER)
				->where("v.module = 'default'")
				->order('v.order ASC');
				$row = $db->fetchAll($select);
			}
			if(count($row) > 0){
				require_once (BLOCK_PATH . '/BlkVideo/'.$template.'.php');
			}
		}
	}
}