<?php
class Block_BlkHeader extends Zend_View_Helper_Abstract{

	public function blkHeader($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			include(BLOCK_PATH . '/BlkHeader/'.$template.'.php');
		}
	}
}