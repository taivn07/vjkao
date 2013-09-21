<?php
class Block_BlkMxh extends Zend_View_Helper_Abstract{

	public function blkMxh($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			include(BLOCK_PATH . '/BlkMxh/'.$template.'.php');
		}
	}
}