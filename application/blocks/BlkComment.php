<?php
class Block_BlkComment extends Zend_View_Helper_Abstract{

	public function blkComment($template = 'default',$option = null){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		$linkComment = $option['url'];
		$widthComment = 730;
		if(!empty($option['width'])){
			$widthComment = $option['width'];
		}
		$c_module = '';
		if(!empty($option['c_module'])){
			$c_module = '/c_module/' . $option['c_module'];
		}
		$c_id = '';
		if(!empty($option['c_id'])){
			$c_id = '/c_id/' . $option['c_id'];
		}
		if($flagShow == true){
			include(BLOCK_PATH . '/BlkComment/'.$template.'.php');
		}
	}
}