<?php
class Default_Model_Language extends Zend_Db_Table{
	
	protected $_name = 'languages';
	protected $_primary ='id';
	
	public function itemInSelectbox($arrParam = null, $options = null){
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		if($options == null){
			$select = $db->select()
			->from('languages', array('lang_code','title'))
			->order('id ASC');
			$result = $db->fetchPairs($select);
		}
		return $result;
	}
	
	
}




