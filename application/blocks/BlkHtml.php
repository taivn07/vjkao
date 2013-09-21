<?php
class Block_BlkHtml extends Zend_View_Helper_Abstract{

	public function blkHtml($template = 'default',$id = null){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			if($id != null){
				$select = $db->select()
						->from('blocks AS b',array('id','content'))
						->where('b.id = ?', $id, INTERGER);
				
				$row = $db->fetchAll($select);
			}
			if(count($row) > 0){
				include(BLOCK_PATH . '/BlkHtml/'.$template.'.php');
			}
		}
	}
}