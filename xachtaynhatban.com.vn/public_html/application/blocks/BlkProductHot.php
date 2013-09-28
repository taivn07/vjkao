<?php
class Block_BlkProductHot extends Zend_View_Helper_Abstract{

	public function blkProductHot($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$filename = APPLICATION_PATH . '/modules/shopping/config/config.ini';
		$section = 'module-settings';
		$moduleConfig = new Zend_Config_Ini($filename, $section);
		$moduleConfig = $moduleConfig->toArray();
		$moduleConfig = $moduleConfig['module'];
		$flagShow = true;
		if($flagShow == true){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
				->from('products AS p',array('id','name','alias','picture','thumb','synopsis','price','selloff','cat_id','units_money','khuyenmai'))
				->join('product_category AS pc', 'pc.id = p.cat_id',array('name AS category_name','alias AS category_alias'))
				->where('p.status = ?',1,INTERGER)
				->where('p.block_hot = ?',1,INTERGER)
				->limit(8,0)
				->order('p.order ASC')
				->order('p.id ASC');
			$language = new Zend_Session_Namespace('language');
			$select->where("p.lang_code = '" . $language->lang . "'");
			
			if(!empty($arrParam['cid'])){
				$selectCategory = $db->select()
				->from('product_category',array('id','name','parents'))
				->where('status = ?',1,INTERGER);
				$resultCategory = $db->fetchAll($selectCategory);
				$system = new Zendvn_System_Recursive($resultCategory);
				$newArray = $system->builArray($arrParam['cid']);
				$tmp[] = $arrParam['cid'];
				if(count($newArray)>0){
					foreach ($newArray as $key => $val){
						$tmp[] = $val['id'];
					}
				}
				$ids = implode(',', $tmp);
				
				$select->where('p.cat_id IN (' . $ids . ')');
			}
			$row = $db->fetchAll($select);
			if(count($row) > 0){
				require_once (BLOCK_PATH . '/BlkProductHot/'.$template.'.php');
			}
		}
	}
}