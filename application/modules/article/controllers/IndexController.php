<?php
class Article_IndexController extends Zendvn_Controller_Action {
		
	//Mang tham so nhan duoc khi mot Action chay
	protected $_arrParam;
	
	//Duong dan cua Controller
	protected $_currentController;
	
	//Duong dan cua Action chinh
	protected $_actionMain;
	
	//Thong so phan trang
	protected $_paginator = array(
									'itemCountPerPage' => 8,
									'pageRange' => 10,
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

		$filename = APPLICATION_PATH . '/modules/'.$this->_arrParam['module'].'/config/config.ini';
		$section = 'module-settings';
		$moduleConfig = new Zend_Config_Ini($filename, $section);
		$moduleConfig = $moduleConfig->toArray();
		$this->view->moduleConfig = $moduleConfig['module'];
		$this->_paginator['itemCountPerPage'] = $moduleConfig['module']['countPage'];
		$this->_paginator['pageRange'] = $moduleConfig['module']['pageRange'];
	
		//Trang hien tai
		if(isset($this->_arrParam['page'])){
			$this->_paginator['currentPage'] = $this->_arrParam['page'];
			$this->_page = '?page=' . $this->_arrParam['page'];
		}
		
		$this->_namespace = $this->_arrParam['module'] . '-' . $this->_arrParam['controller'];
		$ssFilter = new Zend_Session_Namespace($this->_namespace);
		
		if(empty($ssFilter->lang_code)){
			$language = new Zend_Session_Namespace('language');
			$this->_arrParam['ssFilter']['lang_code'] = $language->lang;
		}else{
			$this->_arrParam['ssFilter']['lang_code'] = $ssFilter->lang_code;
		}
		
		//Truyen thong tin phan trang vao mang du lieu
		$this->_arrParam['paginator'] = $this->_paginator;
		$this->_arrParam['ssFilter']['keywords'] 	= $ssFilter->keywords;
	
		//Truyen ra ngoai view
		$this->view->arrParam = $this->_arrParam;
		$this->view->currentController = $this->_currentController;
		$this->view->actionMain = $this->_actionMain;
	
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/public/" . $siteConfig['template']['site'];
		$this->loadTemplate($template_path, 'template.ini', 'article');
		
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

	public function categoryAction(){
		$tblArticle = new Article_Model_Item();
		
		$this->view->category = $tblArticle->getItem($this->_arrParam,array('task'=>'public-category'));
		if($this->view->category['meta_title'] != ''){
			$this->view->headTitle($this->view->category['meta_title'], true);
		}else{
			$this->view->headTitle($this->view->category['name'], true);
		}
		$this->view->headMeta()->setName('description',$this->view->category['meta_description']);
		$this->view->headMeta()->setName('keywords',$this->view->category['meta_keywords']);
		$this->view->headMeta()->setName('title',$this->view->category['meta_title']);
		
		$this->view->Items = $tblArticle->listItem($this->_arrParam,array('task'=>'public-category'));
		$this->view->totalItem = $totalItem = $tblArticle->countItem($this->_arrParam,array('task'=>'public-category'));
		
		if($totalItem == 1){
			$this->view->ItemArticle = $tblArticle->getItem($this->view->Items[0],array('task'=>'public-detail'));
		}
		
		$paginator = new Zendvn_Paginator();
		$this->view->paginator = $paginator->createPaginator($totalItem, $this->_paginator);
	}
	
	public function detailAction(){
		$tblArticle = new Article_Model_Item();
		$this->view->Item = $tblArticle->getItem($this->_arrParam,array('task'=>'public-detail'));

		if($this->view->Item['meta_title'] != ''){
			$this->view->headTitle($this->view->Item['meta_title'], true);
		}else{
			$this->view->headTitle($this->view->Item['name'], true);
		}
		$this->view->headMeta()->setName('description',$this->view->Item['meta_description']);
		$this->view->headMeta()->setName('keywords',$this->view->Item['meta_keywords']);
		$this->view->headMeta()->setName('title',$this->view->Item['meta_title']);
		
		$this->view->itemNew = $tblArticle->listItem($this->_arrParam,array('task'=>'public-new'));
		$this->view->itemOld = $tblArticle->listItem($this->_arrParam,array('task'=>'public-old'));

		$tblArticle->saveItem($this->view->Item, array('task' => 'public-hits'));
	}
	
	public function filterAction(){
		$ssFilter = new Zend_Session_Namespace($this->_namespace);
	
		if($this->_arrParam['type'] == 'search'){
			$ssFilter->keywords = trim($this->_arrParam['keywords']);
		}
	
		$this->_redirect('/' . $this->_arrParam['module'] . '/'	. $this->_arrParam['controller'] . '/search');
	
		$this->_helper->viewRenderer->setNoRender();
	}
	
	public function searchAction() {
		$tblArticle = new Article_Model_Item();
	
		$this->view->title = $this->view->language['ketQuaTimKiem'];
		$this->view->headTitle($this->view->title, true);
	
		$this->view->Items = $tblArticle->listItem($this->_arrParam,array('task'=>'public-search'));
	
		$totalItem = $this->view->totalItem = $tblArticle->countItem($this->_arrParam,array('task'=>'public-search'));
	
		$paginator = new Zendvn_Paginator();
		$this->view->paginator = $paginator->createPaginator($totalItem, $this->_paginator);
	}
	
}






