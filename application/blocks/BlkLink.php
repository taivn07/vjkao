<?php
class Block_BlkLink extends Zend_View_Helper_Abstract{
	
	public function blkLink($template = 'default'){
		$view = $this->view;
		$tblLink = new Default_Model_Link();
		$row = $tblLink->listItem(null,array('task'=>'public-list'));
		
		if(count($row) > 0){
			require_once BLOCK_PATH . '/BlkLink/'.$template.'.php';
		}
	}
}