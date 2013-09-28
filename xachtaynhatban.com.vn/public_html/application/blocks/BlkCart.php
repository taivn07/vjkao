<?php
class Block_BlkCart extends Zend_View_Helper_Abstract{
	
	public function blkCart(){
		$view = $this->view;
		$yourCart = new Zend_Session_Namespace('cart');
		$ssInfo = $yourCart->getIterator();
		$tmp = $yourCart->cart;
		$countItem = 0;
		if(count($tmp)>0){
			foreach ($tmp as $key => $val){
				$countItem += $val;
			}
		}
		require(BLOCK_PATH . '/BlkCart/default.php');
	}
}