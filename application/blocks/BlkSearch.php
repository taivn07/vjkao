<?php
class Block_BlkSearch extends Zend_View_Helper_Abstract{

	public function blkSearch($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			include(BLOCK_PATH . '/BlkSearch/'.$template.'.php');
		}
	}
}