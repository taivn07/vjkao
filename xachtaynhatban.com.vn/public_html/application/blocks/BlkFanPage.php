<?php
class Block_BlkFanPage extends Zend_View_Helper_Abstract{

	public function blkFanPage($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			include(BLOCK_PATH . '/BlkFanPage/'.$template.'.php');
		}
	}
}