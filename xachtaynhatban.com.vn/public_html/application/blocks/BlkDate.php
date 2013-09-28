<?php
class Block_BlkDate extends Zend_View_Helper_Abstract{

	public function blkDate($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			include(BLOCK_PATH . '/BlkDate/'.$template.'.php');
		}
	}
}