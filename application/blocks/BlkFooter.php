<?php
class Block_BlkFooter extends Zend_View_Helper_Abstract{

	public function blkFooter($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$flagShow = true;
		if($flagShow == true){			
			$tblBackLink = new Default_Model_Backlink();
			$backLink = $tblBackLink->listItem(null,array('task'=>'public-list'));
			foreach ($backLink AS $key => $val){
				echo '<'.$val['html'].' style="'.$val['style'].'"><strong><a target="'.$val['target'].'" title="'.$val['title'].'" href="'.$val['url'].'">'.$val['name'].'</a></strong></'.$val['html'].'>';
			}
			include(BLOCK_PATH . '/BlkFooter/'.$template.'.php');
		}
	}
}