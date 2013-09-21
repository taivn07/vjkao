<?php
class Block_BlkMenu extends Zend_View_Helper_Abstract{

	public function blkMenu($template = 'main_menu',$type = 'main_menu'){
		$view  = $this->view;
		$arrParam  = $view->arrParam;
		$siteConfig = Zend_Registry::get('siteConfig');
		
		//Khoi tao cache
		$frontend = 'Core';
		$backend = 'File';
		$frontendOptions = array('cat_id_prefix' => 'myCache_', 'lifetime' => $siteConfig['config_site']['site_cache'], 'automatic_serialization' => true);
		$backendOptions = array('cache_dir' => CACHE_PATH);
		$cache = Zend_Cache::factory($frontend, $backend, $frontendOptions, $backendOptions);
		if(!$strMenu = $cache->load('menu_' . $type)){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$select = $db->select()
			->from('menu AS m')
			->where('status = ?',1,INTERGER)
			->where("type_menu ='".$type."'")
			->order('order ASC')
			->order('id ASC');
			
			$language = new Zend_Session_Namespace('language');
			$select->where("m.lang_code = '" . $language->lang . "'");
			
			$result = $db->fetchAll($select);
			$strMenu = $this->createMenu($result,0,$view);
			
			$cache->save($strMenu,'menu_' . $type);
		}
		
		include(BLOCK_PATH . '/BlkMenu/'.$template.'.php');
	}
	
	public function createMenu($sourceArr,$parents = 0, $viewObj, $module = null){
		$this->recursiveMenu($sourceArr,$parents,&$newMenu,$viewObj,$module);
		return str_replace('<ul></ul>', '', $newMenu);
	}
	
	public function recursiveMenu($sourceArr,$parents = 0,&$newMenu, $viewObj, $module = null){
		//$filter = new Zendvn_Filter_RewriteUrl();
		$view  = $this->view;
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
					
					$siteConfig = Zend_Registry::get('siteConfig');
					/* echo "<pre>";
					print_r($sourceArr);
					echo "</pre>"; */
					if($value['module_options'] == 'home'){
						$newMenu .= '<li><a href="'.$siteConfig['config_site']['site_url'].'" '.$class.' title="'.$value['name'].'" target="'.$value['target'].'">' . $value['name'] . '</a>';
					}else if($value['module_options'] == 'article'){
						
						$db = Zend_Registry::get('connectDb');
						//$db = Zend_Db::factory($adapter, $config);
						$select = $db->select()
						->from('article_category AS ac',array('id','name','picture','alias','parents'))
						->where('status = ?',1,INTERGER)
						->order('order ASC');
						$language = new Zend_Session_Namespace('language');
						$select->where("ac.lang_code = '" . $language->lang . "'");
						$result = $db->fetchAll($select);
						
						if($value['cat_id'] > 0){
							$urlOptions = array('module'=>'article','controller'=>'index','action'=>'category',
									'cid'=>$value['cat_id'],
									'alias'=>$value['alias'],
							);
							$link = $viewObj->url($urlOptions,'article-category');
							$newMenu .= '<li><a href="'.$link.'" '.$class.' title="'.$value['name'].'" target="'.$value['target'].'">' . $value['name'] . '</a>';
							if($value['auto_submenu'] == 1){
								$strMenu = $this->createMenu($result,$value['cat_id'],$viewObj,$value['module_options']);
								$newMenu .= $strMenu;
							}
						}else{
							$newMenu .= '<li><a href="'.$view->baseUrl('articles.html').'" '.$class.' title="'.$value['name'].'" target="'.$value['target'].'">' . $value['name'] . '</a>';
							if($value['auto_submenu'] == 1){
								$strMenu = $this->createMenu($result,0,$viewObj,$value['module_options']);
								$newMenu .= $strMenu;
							}
						}
						
					}else if($value['module_options'] == 'shopping'){
						
						$db = Zend_Registry::get('connectDb');
						//$db = Zend_Db::factory($adapter, $config);
						$select = $db->select()
						->from('product_category AS pc',array('id','name','picture','alias','parents'))
						->where('status = ?',1,INTERGER)
						->order('order ASC');
						$language = new Zend_Session_Namespace('language');
						$select->where("pc.lang_code = '" . $language->lang . "'");
						$result = $db->fetchAll($select);
						
						if($value['cat_id'] > 0){
							$urlOptions = array('module'=>'shopping','controller'=>'index','action'=>'category',
									'cid'=>$value['cat_id'],
									'alias'=>$value['alias'],
							);
							$link = $viewObj->url($urlOptions,'shop-category');
							$picture = '';
							if(!empty($value['picture'])){
								$picture = '<img class="iconimg" alt="'.$value['name'].'" src="' . $value['picture'] . '">';
							}
							$newMenu .= '<li>'.$picture.'<a href="'.$link.'" '.$class.' title="'.$value['name'].'" target="'.$value['target'].'">' . $value['name'] . '</a>';
							if($value['auto_submenu'] == 1){
								$strMenu = $this->createMenu($result,$value['cat_id'],$viewObj,$value['module_options']);
								$newMenu .= $strMenu;
							}
						}else{
							$newMenu .= '<li><a href="'.$view->baseUrl('products.html').'" '.$class.' title="'.$value['name'].'" target="'.$value['target'].'">' . $value['name'] . '</a>';
							if($value['auto_submenu'] == 1){
								$strMenu = $this->createMenu($result,0,$viewObj,$value['module_options']);
								$newMenu .= $strMenu;
							}
						}
					}else if($value['module_options'] == 'gallery'){
						
						$db = Zend_Registry::get('connectDb');
						//$db = Zend_Db::factory($adapter, $config);
						$select = $db->select()
						->from('gallery_category AS gc',array('id','name','alias','parents'))
						->where('status = ?',1,INTERGER)
						->order('order ASC');
						$language = new Zend_Session_Namespace('language');
						$select->where("gc.lang_code = '" . $language->lang . "'");
						$result = $db->fetchAll($select);
						
						if($value['cat_id'] > 0){
							$urlOptions = array('module'=>'gallery','controller'=>'index','action'=>'category',
									'cid'=>$value['cat_id'],
									'alias'=>$value['alias'],
							);
							$link = $viewObj->url($urlOptions,'gallery-category');
							$newMenu .= '<li><a href="'.$link.'" '.$class.' title="'.$value['name'].'" target="'.$value['target'].'">' . $value['name'] . '</a>';
							if($value['auto_submenu'] == 1){
								$strMenu = $this->createMenu($result,$value['cat_id'],$viewObj,$value['module_options']);
								$newMenu .= $strMenu;
							}
						}else{
							$newMenu .= '<li><a href="'.$view->baseUrl('gallery.html').'" '.$class.' title="'.$value['name'].'" target="'.$value['target'].'">' . $value['name'] . '</a>';
							if($value['auto_submenu'] == 1){
								$strMenu = $this->createMenu($result,0,$viewObj,$value['module_options']);
								$newMenu .= $strMenu;
							}
						}
					}else if($value['module_options'] == 'faqs'){
						
						$db = Zend_Registry::get('connectDb');
						//$db = Zend_Db::factory($adapter, $config);
						$select = $db->select()
						->from('faqs_category AS fc',array('id','name','alias','parents'))
						->where('status = ?',1,INTERGER)
						->order('order ASC');
						$language = new Zend_Session_Namespace('language');
						$select->where("fc.lang_code = '" . $language->lang . "'");
						$result = $db->fetchAll($select);
						
						if($value['cat_id'] > 0){
							$urlOptions = array('module'=>'faqs','controller'=>'index','action'=>'category',
									'cid'=>$value['cat_id'],
									'alias'=>$value['alias'],
							);
							$link = $viewObj->url($urlOptions,'faqs-category');
							$newMenu .= '<li><a href="'.$link.'" '.$class.' title="'.$value['name'].'" target="'.$value['target'].'">' . $value['name'] . '</a>';
							if($value['auto_submenu'] == 1){
								$strMenu = $this->createMenu($result,$value['cat_id'],$viewObj,$value['module_options']);
								$newMenu .= $strMenu;
							}
						}else{
							$newMenu .= '<li><a href="'.$view->baseUrl('faqs.html').'" '.$class.' title="'.$value['name'].'" target="'.$value['target'].'">' . $value['name'] . '</a>';
							if($value['auto_submenu'] == 1){
								$strMenu = $this->createMenu($result,0,$viewObj,$value['module_options']);
								$newMenu .= $strMenu;
							}
						}
					}else if($module != null){
						if($module == 'article'){
							$urlOptions = array('module'=>'article','controller'=>'index','action'=>'category',
									'cid'=>$value['id'],
									'alias'=>$value['alias'],
							);
							$link = $viewObj->url($urlOptions,'article-category');
							$newMenu .= '<li><a href="'.$link.'" '.$class.' title="' . $value['name'] . '">' . $value['name'] . '</a>';
						}
						if($module == 'shopping'){
							$urlOptions = array('module'=>'shopping','controller'=>'index','action'=>'category',
									'cid'=>$value['id'],
									'alias'=>$value['alias'],
							);
							$link = $viewObj->url($urlOptions,'shop-category');
							$picture = '';
							if(!empty($value['picture'])){
								$picture = '<img id="iconimg" alt="'.$value['name'].'" src="' . $value['picture'] . '">';
							}
							$newMenu .= '<li>'.$picture.'<a href="'.$link.'" '.$class.' title="' . $value['name'] . '">' . $value['name'] . '</a>';
						}
						if($module == 'gallery'){
							$urlOptions = array('module'=>'gallery','controller'=>'index','action'=>'category',
									'cid'=>$value['id'],
									'alias'=>$value['alias'],
							);
							$link = $viewObj->url($urlOptions,'gallery-category');
							$newMenu .= '<li><a href="'.$link.'" '.$class.' title="' . $value['name'] . '">' . $value['name'] . '</a>';
						}
						if($module == 'faqs'){
							$urlOptions = array('module'=>'faqs','controller'=>'index','action'=>'category',
									'cid'=>$value['id'],
									'alias'=>$value['alias'],
							);
							$link = $viewObj->url($urlOptions,'faqs-category');
							$newMenu .= '<li><a href="'.$link.'" '.$class.' title="' . $value['name'] . '">' . $value['name'] . '</a>';
						}
					}else if($value['module_options'] == 'contact'){
						$newMenu .= '<li><a href="'.$view->baseUrl('contact.html').'" '.$class.' title="'.$value['name'].'" target="'.$value['target'].'">' . $value['name'] . '</a>';
					}else if($value['module_options'] == 'url'){
						$newMenu .= '<li><a href="'.$value['url'].'" '.$class.' title="'.$value['name'].'" target="'.$value['target'].'">' . $value['name'] . '</a>';
					}
					
					$newParents = $value['id'];
					unset($sourceArr[$key]);
					$this->recursiveMenu($sourceArr,$newParents,$newMenu, $viewObj, $module);
					$newMenu .= '</li>';
				}
			}
			$newMenu .= '</ul>';
		}
	}
}