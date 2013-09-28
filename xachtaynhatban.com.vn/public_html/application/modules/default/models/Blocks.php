<?php
class Default_Model_Blocks extends Zend_Db_Table{
	
	protected $_name = 'blocks';
	protected $_primary ='id';
	
	protected $_ids;
	
	public function getItem($arrParam = null, $options = null){
		
		if($options['task'] == 'admin-info' || $options['task'] == 'admin-edit'){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('blocks AS b')
			->where('b.id = ?', $arrParam['id'], INTEGER);
				
			$result = $db->fetchRow($select);
		}
		
		if($options['task'] == 'public-detail'){
			$select = $this->select()
					->where('id = ?',$arrParam['id'],INTERGER);
			$result = $this->fetchRow($select)->toArray();
		}
		return $result;
	}

	public function saveItem($arrParam = null, $options = null){
	
		if($options['task'] == 'admin-add'){
			$row 				= $this->fetchNew();			
			$row->content 		= stripslashes($arrParam['content']);
			$row->params 		= stripslashes($arrParam['params']);
			$row->save();
		}
	
		if($options['task'] == 'admin-edit'){
			$where 				= ' id=' . $arrParam['id'];
			$row 				= $this->fetchRow($where);
			$row->content 		= stripslashes($arrParam['content']);
			$row->params 		= stripslashes($arrParam['params']);
			$row->save();
		}
	}
}