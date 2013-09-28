<?php
class Shopping_BlockController extends Zendvn_Controller_Action {
		
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
		$this->_arrParam['moduleConfig'] = $moduleConfig['module'];
		$this->_paginator['itemCountPerPage'] = $moduleConfig['module']['countPage'];
		$this->_paginator['pageRange'] = $moduleConfig['module']['pageRange'];
		
		$this->_namespace = $this->_arrParam['module'] . '-' . $this->_arrParam['controller'];
		$ssFilter = new Zend_Session_Namespace($this->_namespace);
		//Lay thong tin so phan tu tren mot trang
		if(isset($this->_arrParam['limitPage'])){
			$ssFilter->limitPage = $this->_request->getParam('limitPage');
			$this->_paginator['itemCountPerPage'] = $ssFilter->limitPage;
		}elseif(!empty($ssFilter->limitPage)){
			$this->_paginator['itemCountPerPage'] = $ssFilter->limitPage;
		}
		
		//Trang hien tai
		if(isset($this->_arrParam['page'])){
			$this->_paginator['currentPage'] = $this->_arrParam['page'];
		}
		
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
		$this->loadTemplate($template_path, 'template.ini', 'product_block');
		
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
	
	public function moiAction() {
		$tblProduct = new Shopping_Model_Item();
	
		$this->view->title = $this->view->language['productSanPhamMoi'];
		$this->view->headTitle($this->view->title, true);
	
		$this->view->Items = $tblProduct->listItem($this->_arrParam,array('task'=>'public-block'));
	
		$totalItem = $tblProduct->countItem($this->_arrParam,array('task'=>'public-block'));
	
		$paginator = new Zendvn_Paginator();
		$this->view->paginator = $paginator->createPaginator($totalItem, $this->_paginator);
	}
	
	public function banChayAction() {
		$tblProduct = new Shopping_Model_Item();
	
		$this->view->title = $this->view->language['productSanPhamBanChay'];
		$this->view->headTitle($this->view->title, true);
	
		$this->view->Items = $tblProduct->listItem($this->_arrParam,array('task'=>'public-block','block' => 'block_banchay'));
	
		$totalItem = $tblProduct->countItem($this->_arrParam,array('task'=>'public-block','block' => 'block_banchay'));
	
		$paginator = new Zendvn_Paginator();
		$this->view->paginator = $paginator->createPaginator($totalItem, $this->_paginator);
	}
	
	public function khuyenMaiAction() {
		$tblProduct = new Shopping_Model_Item();
	
		$this->view->title = $this->view->language['productSanPhamKhuyenMai'];
		$this->view->headTitle($this->view->title, true);
	
		$this->view->Items = $tblProduct->listItem($this->_arrParam,array('task'=>'public-block','block' => 'block_khuyenmai'));
	
		$totalItem = $tblProduct->countItem($this->_arrParam,array('task'=>'public-block','block' => 'block_khuyenmai'));
	
		$paginator = new Zendvn_Paginator();
		$this->view->paginator = $paginator->createPaginator($totalItem, $this->_paginator);
	}
	
	public function noiBatAction() {
		$tblProduct = new Shopping_Model_Item();
	
		$this->view->title = $this->view->language['productSanPhamNoiBat'];
		$this->view->headTitle($this->view->title, true);
	
		$this->view->Items = $tblProduct->listItem($this->_arrParam,array('task'=>'public-block','block' => 'block_noibat'));
	
		$totalItem = $tblProduct->countItem($this->_arrParam,array('task'=>'public-block','block' => 'block_noibat'));
	
		$paginator = new Zendvn_Paginator();
		$this->view->paginator = $paginator->createPaginator($totalItem, $this->_paginator);
	}
	
	public function hotAction() {
		$tblProduct = new Shopping_Model_Item();
	
		$this->view->title = $this->view->language['productSanPhamHot'];
		$this->view->headTitle($this->view->title, true);
	
		$this->view->Items = $tblProduct->listItem($this->_arrParam,array('task'=>'public-block','block' => 'block_hot'));
	
		$totalItem = $tblProduct->countItem($this->_arrParam,array('task'=>'public-block','block' => 'block_hot'));
	
		$paginator = new Zendvn_Paginator();
		$this->view->paginator = $paginator->createPaginator($totalItem, $this->_paginator);
	}
	
	public function ajaxAction() {
	
		$this->_helper->layout->disableLayout();
	
		$tblProduct = new Shopping_Model_Item();
		$this->view->Items = $tblProduct->listItem($this->_arrParam,array('task'=>'public-ajax'));
	}
}






