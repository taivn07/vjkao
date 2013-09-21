<?php
class Block_BlkUser extends Zend_View_Helper_Abstract{

	public function blkUser($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			include(BLOCK_PATH . '/BlkUser/'.$template.'.php');
		}
	}
}