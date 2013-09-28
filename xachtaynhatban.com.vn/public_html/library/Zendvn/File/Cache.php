<?php
class Zendvn_File_Cache{
	
	public function clear($value = null){
		$frontend = 'Core';
		$backend = 'File';
		$frontendOptions = array('cat_id_prefix' => 'myCache_', 'lifetime' => 900, 'automatic_serialization' => true);
		$backendOptions = array('cache_dir' => CACHE_PATH);
		$cache = Zend_Cache::factory($frontend, $backend, $frontendOptions, $backendOptions);
		if($value == null){
			$cache->clean('all');
		}else{
			$cache->remove($value);
		}
	}
}