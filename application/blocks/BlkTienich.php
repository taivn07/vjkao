<?php
class Block_BlkTienich extends Zend_View_Helper_Abstract{

	public function blkTienich($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			include(BLOCK_PATH . '/BlkTienich/'.$template.'.php');
		}
	}
}