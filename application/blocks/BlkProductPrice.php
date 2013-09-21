<?php
class Block_BlkProductPrice extends Zend_View_Helper_Abstract{

	public function blkProductPrice($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($arrParam['module'] != 'shopping'){
			$flagShow = false;
		}
		if($flagShow == true){
			include (BLOCK_PATH . '/BlkProductPrice/'.$template.'.php');
		}
	}
}