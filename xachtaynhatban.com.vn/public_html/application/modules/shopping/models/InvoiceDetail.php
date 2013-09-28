<?php
class Shopping_Model_InvoiceDetail extends Zend_Db_Table{
	
	protected $_name = 'invoice_detail';
	protected $_primary ='id';
	
	public function saveItem($arrParam = null, $options = null){
		if($options == null){
			if(count($arrParam['cart'])>0){
				$i = 1;
				foreach ($arrParam['cart'] as $key => $val){
					if($i == 1){
						$ids .= $key;
					}else{
						$ids .= ',' . $key;
					}
					$i++;
				}
				$db = Zend_Registry::get('connectDb');
				$select = $db->select()
				->from('products AS p',array('id','price'))
				->where('p.id IN (' . $ids . ')');
				$result = $db->fetchAll($select);
				$tmp = array();
				$cart = $arrParam['cart'];
				foreach ($result as $key => $val){
					$val['quantity'] = $cart[$val['id']];
					$tmp[] = $val;
				}				
			}
			foreach ($tmp as $key_1 => $info){
				$row 					= $this->fetchNew();
				$row->product_id 		= $info['id'];
				$row->quantity 			= $info['quantity'];
				$row->price 			= $info['price'];
				$row->invoice_id 		= $arrParam['invoice_id'];
				$row->save();
			}
		}	
	}
}