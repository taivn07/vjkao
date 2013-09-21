<?php
class IndexController extends Zendvn_Controller_Action {
		
//Mang tham so nhan duoc khi mot Action chay
	protected $_arrParam;
	
	//Duong dan cua Controller
	protected $_currentController;
	
	//Duong dan cua Action chinh
	protected $_actionMain;
	
	//Thong so phan trang
	protected $_paginator = array(
									'itemCountPerPage' => 8,
									'pageRange' => 4,
									'currentPage' => 1
									);
	protected $_namespace;
	
	//Url page
	protected $_page = '';
	
	public function init(){
		//Mang tham so nhan duoc khi mot Action chay
		$this->_arrParam = $this->_request->getParams();
	
		//Duong dan cua Controller
		$this->_currentController = '/' . $this->_arrParam['module'] . '/' . $this->_arrParam['controller'];
	
		//Duong dan cua Action chinh
		$this->_actionMain = '/' . $this->_arrParam['module'] . '/'	. $this->_arrParam['controller'] . '/index';	
	
		//Trang hien tai
		if(isset($this->_arrParam['page'])){
			$this->_paginator['currentPage'] = $this->_arrParam['page'];
			$this->_page = '/page/' . $this->_arrParam['page'];
		}
		
		//Truyen thong tin phan trang vao mang du lieu
		$this->_arrParam['paginator'] = $this->_paginator;
		
	
		//Truyen ra ngoai view
		$this->view->arrParam = $this->_arrParam;
		$this->view->currentController = $this->_currentController;
		$this->view->actionMain = $this->_actionMain;
	
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/public/" . $siteConfig['template']['site'];
		$this->loadTemplate($template_path, 'template.ini', 'template');
		
		$language = Zend_Registry::get('language');
		$this->view->language = $language['language'];
	}
	
	//Ham chay sau ham action
	public function postDispatch(){
		$siteConfig = Zend_Registry::get('siteConfig');
		$this->view->headMeta()->setHttpEquiv('Refresh',$siteConfig['config_meta']['refresh']);
		$this->view->headMeta()->setHttpEquiv('content-language',$siteConfig['config_meta']['content_language']);
		$this->view->headMeta()->setName('classification',$siteConfig['config_meta']['classification']);
		$this->view->headMeta()->setName('language',$siteConfig['config_meta']['language']);
		$this->view->headMeta()->setName('robots',$siteConfig['config_meta']['robots']);
		$this->view->headMeta()->setName('author',$siteConfig['config_meta']['author']);
		$this->view->headMeta()->setName('copyright',$siteConfig['config_meta']['copyright']);
		$this->view->headMeta()->setName('revisit-after',$siteConfig['config_meta']['revisit_after']);
		
		if($siteConfig['config_site']['offline'] == 1){
			$this->_forward('off','public','default');
		}
	}
		
	public function indexAction() {
		//$this->_forward('index','register','default'); //Goi controller tu module khac
		$siteConfig = Zend_Registry::get('siteConfig');
		$this->view->headTitle($siteConfig['config_site']['sitename'], true);
		$this->view->headMeta()->setName('description',$siteConfig['config_meta']['description']);
		$this->view->headMeta()->setName('keywords',$siteConfig['config_meta']['keywords']);
		$this->view->headMeta()->setName('title',$siteConfig['config_site']['sitename']);
		//Them 1 tap tin css
		//$this->view->headLink()->appendStylesheet( PUBLIC_URL . '/templates/public/shop_001/css/blockSlide/blockSlide.css','screen');
	}	
	
	public function cacheAction(){
		//Cach text
		$this->_helper->layout->disableLayout();
		echo __METHOD__ . '<br>';
		
		$frontend = 'Core';
		$backend = 'File';
		$frontendOptions = array('cat_id_prefix' => 'myCache_', 'lifetime' => 900);
		$backendOptions = array('cache_dir' => CACHE_PATH);
		$cache = Zend_Cache::factory($frontend, $backend, $frontendOptions, $backendOptions);
		
		/* echo "<pre>";
		print_r($cache);
		echo "</pre>"; */
		$myVar = 'This is a test';
		if(!$myValue = $cache->load('myValue')){
			echo '<br>' . 'Giá trị này chưa tồn tại trong cache';
			$cache->save($myVar,'myValue');
			$myValue = $myVar;
		}else{
			echo '<br>' . 'Load gia tri từ cache';
		}
		$cache->remove('myValue');
		echo '<br>' . $myValue;
	}
	
	public function cache2Action(){
		//Cache mang
		$this->_helper->layout->disableLayout();
		echo '<br>' . __METHOD__;
	
		$frontend = 'Core';
		$backend = 'File';
		$frontendOptions = array('cat_id_prefix' => 'myCache_', 'lifetime' => 900, 'automatic_serialization' => true);
		$backendOptions = array('cache_dir' => CACHE_PATH);
		$cache = Zend_Cache::factory($frontend, $backend, $frontendOptions, $backendOptions);
	
		/* echo "<pre>";
			print_r($cache);
		echo "</pre>"; */
		
		$book = array('id' => 10, 'title' => 'php');
		if(!$bookArr = $cache->load('myBook')){
			$cache->save($book,'myBook');
			$bookArr = $book;
		}
		
		echo "<pre>";
		print_r($bookArr);
		echo "</pre>";
	}
	
	public function cache3Action(){
		//Cache tags
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		echo '<br>' . __METHOD__;
	
		$frontend = 'Core';
		$backend = 'File';
		$frontendOptions = array('cat_id_prefix' => 'myCache_', 'lifetime' => 900, 'automatic_serialization' => true);
		$backendOptions = array('cache_dir' => CACHE_PATH);
		$cache = Zend_Cache::factory($frontend, $backend, $frontendOptions, $backendOptions);
	
		/* echo "<pre>";
		 print_r($cache);
		echo "</pre>"; */
		$phpBook = array('id' => 10, 'title' => 'php');
		$aspBook = array('id' => 10, 'title' => 'asp');
		$jspBook = array('id' => 10, 'title' => 'jsp');
		
		$cache->save($phpBook,'phpBook',array('tagBook'));
		$cache->save($aspBook,'aspBook',array('tagBook'));
		$cache->save($jspBook,'jspBook',array('tagBook'));
		
		$tmp = $cache->getIdsMatchingTags(array('tagBook'));
		echo "<pre>";
		print_r($tmp);
		echo "</pre>";
		foreach ($tmp AS $key => $val){
			echo "<pre>";
			print_r($cache->load($val));
			echo "</pre>";
		}
		
		$cache->clean('all',array('tagBook'));
		//$cache->remove('aspBook');
	}
	
	public function tienichAction(){
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/public/" . $siteConfig['template']['site'];
		$this->loadTemplate($template_path, 'template.ini', 'tienich');
	}
	
}
