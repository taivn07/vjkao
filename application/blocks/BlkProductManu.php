<?php
class Block_BlkProductManu extends Zend_View_Helper_Abstract{

	public function blkProductManu($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		
		if($arrParam['module'] != 'shopping'){
			$flagShow = false;
		}
		if($flagShow == true){
			
			$tblManu = new Shopping_Model_Manu();
			$listManu = $tblManu->listItem($arrParam,array('task' => 'public-list'));
			
			if(count($listManu) > 0){
				include (BLOCK_PATH . '/BlkProductManu/'.$template.'.php');
			}
		}
	}
}