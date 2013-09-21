<?php
class Block_BlkProductCategory extends Zend_View_Helper_Abstract{
	
	public function blkProductCategory($template = 'default'){
		$view = $this->view;
		$siteConfig = Zend_Registry::get('siteConfig');
		
		//Khoi tao cache
		$frontend = 'Core';
		$backend = 'File';
		$frontendOptions = array('cat_id_prefix' => 'myCache_', 'lifetime' => $siteConfig['config_site']['site_cache'], 'automatic_serialization' => true);
		$backendOptions = array('cache_dir' => CACHE_PATH);
		$cache = Zend_Cache::factory($frontend, $backend, $frontendOptions, $backendOptions);
		if(!$strMenu = $cache->load('product_category')){
			
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
					->from('product_category AS pc',array('id','name','alias','parents'))
					->where('status = ?',1,INTERGER)
					->order('order ASC');
			
			$language = new Zend_Session_Namespace('language');
			$select->where("pc.lang_code = '" . $language->lang . "'");
			
			$result = $db->fetchAll($select);
			$strMenu = $this->createMenu($result,0,$view);
			
			$cache->save($strMenu,'product_category');
		}

		include(BLOCK_PATH . '/BlkProductCategory/'.$template.'.php');
	}
	
	public function createMenu($sourceArr,$parents = 0, $viewObj){
		$this->recursiveMenu($sourceArr,$parents = 0,&$newMenu,$viewObj);
		return str_replace('<ul></ul>', '', $newMenu);
	}
	
	public function recursiveMenu($sourceArr,$parents = 0,&$newMenu, $viewObj){
		//$filter = new Zendvn_Filter_RewriteUrl();
		if(count($sourceArr)>0){
			$newMenu .= '<ul>';
			$i=0;
			foreach ($sourceArr as $key => $value){
				$i++;
				if($value['parents'] == $parents){
					$class = '';
					if($i == 1){
						$class = 'class="start"';
					}
					
					$urlOptions = array('module'=>'shopping','controller'=>'index','action'=>'category',
							'cid'=>$value['id'],
							'alias'=>$value['alias'],
					);
					$link = $viewObj->url($urlOptions,'shop-category');
					//$link = $viewObj->baseUrl('/shopping/index/category/cid/'.$value['id'].'/name/'.$filter->filter($value['name']));
					$newMenu .= '<li><a href="'.$link.'" '.$class.' title="'.$value['name'].'">' . $value['name'] . '</a>';
					
					$newParents = $value['id'];
					unset($sourceArr[$key]);
					$this->recursiveMenu($sourceArr,$newParents,$newMenu, $viewObj);
					$newMenu .= '</li>';
				}
			}
			$newMenu .= '</ul>';
		}
	}
}