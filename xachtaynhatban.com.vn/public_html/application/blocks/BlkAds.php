<?php
class Block_BlkAds extends Zend_View_Helper_Abstract{

	public function blkAds($template = 'default',$id){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		$siteConfig = Zend_Registry::get('siteConfig');
		$flagShow = true;
		if($flagShow == true){			
			//Khoi tao cache
			$frontend = 'Core';
			$backend = 'File';
			$frontendOptions = array('cat_id_prefix' => 'myCache_', 'lifetime' => $siteConfig['config_site']['site_cache'], 'automatic_serialization' => true);
			$backendOptions = array('cache_dir' => CACHE_PATH);
			$cache = Zend_Cache::factory($frontend, $backend, $frontendOptions, $backendOptions);
			if(!$row = $cache->load('ads_' . $id)){
				$db = Zend_Registry::get('connectDb');
				//$db = Zend_Db::factory($adapter, $config);
				$select = $db->select()
				->from('ads AS a',array('id','name','picture','url','width','height','type','target'))
				->where('a.cat_id = ?', $id, INTERGER)
				->where('a.status = ?', 1, INTERGER)
				->order('a.order ASC');
				$row = $db->fetchAll($select);
				
				$cache->save($row,'ads_' . $id);
			}
			if(count($row)>0){
				include(BLOCK_PATH . '/BlkAds/'.$template.'.php');
			}
		}
	}
}