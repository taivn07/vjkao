<?php
class AdminMenuController extends Zendvn_Controller_Action{
	
	//Mang tham so nhan duoc khi mot Action chay
	protected $_arrParam;
	
	//Duong dan cua Controller
	protected $_currentController;
	
	//Duong dan cua Action chinh
	protected $_actionMain;
	
	//Thong so phan trang
	protected $_paginator = array(
									'itemCountPerPage' => 15,
									'pageRange' => 10,
									'currentPage' => 1
									);
	protected $_namespace;
	
	//
	protected $_arrPost = '';
	protected $_type = '/type_menu/main_menu';
	
	public function init(){
		//Mang tham so nhan duoc khi mot Action chay
		$this->_arrParam = $this->_request->getParams();
	
		//Duong dan cua Controller
		$this->_currentController = '/' . $this->_arrParam['module'] . '/' . $this->_arrParam['controller'];
	
		//Duong dan cua Action chinh
		$this->_actionMain = '/' . $this->_arrParam['module'] . '/'	. $this->_arrParam['controller'] . '/index';	
	
		//Luu cac du lieu filter vaof SESSION
		//Dat ten SESSION
		$this->_namespace = $this->_arrParam['module'] . '-' . $this->_arrParam['controller'];
		$ssFilter = new Zend_Session_Namespace($this->_namespace);
		
		//$ssFilter->unsetAll();
		/* if(empty($ssFilter->col)){
			$ssFilter->keywords = '';
		} */
		$this->_arrParam['ssFilter']['keywords'] 	= $ssFilter->keywords;
		$this->_arrParam['ssFilter']['parents'] 	= $ssFilter->parents;
		if(empty($ssFilter->lang_code)){
			$language = new Zend_Session_Namespace('language');
			$this->_arrParam['ssFilter']['lang_code'] = $language->lang;
		}else{
			$this->_arrParam['ssFilter']['lang_code'] 	= $ssFilter->lang_code;
		}

		//Trang hien tai
		if(isset($this->_arrParam['type_menu'])){
			$this->_type = '/type_menu/' . $this->_arrParam['type_menu'];
		}
	
		//Truyen ra ngoai view
		$this->view->arrParam = $this->_arrParam;
		$this->view->currentController = $this->_currentController;
		$this->view->actionMain = $this->_actionMain;
	
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/admin/" . $siteConfig['template']['admin'];
		$this->loadTemplate($template_path, 'template.ini', 'template');
	}

	public function indexAction(){
		$this->view->Title = 'Quản lý menu';
		$this->view->headTitle($this->view->Title, true);
		$tblMenu = new Default_Model_Menu();
		$this->view->slbMenu = $tblMenu->itemInSelectbox($this->_arrParam);
		
		$tblLanguages = new Default_Model_Language();
		$this->view->slbLanguages = $tblLanguages->itemInSelectbox($this->_arrParam);
		
		$this->view->Items = $tblMenu->listItem($this->_arrParam, array('task'=>'admin-list'));
	}
	
	public function filterAction(){
		$ssFilter = new Zend_Session_Namespace($this->_namespace);
		
		if($this->_arrParam['type'] == 'search'){
			if($this->_arrParam['key'] == 1){
				$ssFilter->keywords = trim($this->_arrParam['keywords']);
			}else{
				$ssFilter->keywords = '';
			}
		}
		
		if($this->_arrParam['type'] == 'category'){
			$ssFilter->parents = $this->_arrParam['parents'];
		}
		
		if($this->_arrParam['type'] == 'lang'){
			$ssFilter->lang_code = $this->_arrParam['lang_code'];
		}
	
		$this->_redirect($this->_actionMain);
	
		$this->_helper->viewRenderer->setNoRender();
	}
	
	public function addAction(){
		$this->view->Title = 'Menu :: Thêm mới';
		$this->view->headTitle($this->view->Title, true);
		$tblMenu = new Default_Model_Menu();
		$this->view->slbMenu = $tblMenu->itemInSelectbox($this->_arrParam);
		
		$tblLanguages = new Default_Model_Language();
		$this->view->slbLanguages = $tblLanguages->itemInSelectbox($this->_arrParam);
		
		if($this->_request->isPost()){
			$validator = new Default_Form_ValidateMenu($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			}else{
				$tblMenu->saveItem($this->_arrParam,array('task'=>'admin-add'));
				$cache = new Zendvn_File_Cache();
				$cache->clear();
				$this->_redirect($this->_actionMain . $this->_type);
			}
		}

	}
	
	public function infoAction(){
		$this->view->Title = 'Menu :: Xem thông tin';
		$this->view->headTitle($this->view->Title, true);
		$tblMenu = new Default_Model_Menu();
		$this->view->Item = $tblMenu->getItem($this->_arrParam,array('task'=>'admin-info'));
		
		$tblLanguages = new Default_Model_Language();
		$this->view->slbLanguages = $tblLanguages->itemInSelectbox($this->_arrParam);
	}
	
	public function editAction(){
		$this->view->Title = 'Menu :: Sửa';
		$this->view->headTitle($this->view->Title, true);
		$tblMenu = new Default_Model_Menu();
		$this->view->Item = $tblMenu->getItem($this->_arrParam,array('task'=>'admin-edit'));
		$this->view->slbMenu = $tblMenu->itemInSelectbox($this->_arrParam,array('task'=>'admin-edit'));
		
		$tblLanguages = new Default_Model_Language();
		$this->view->slbLanguages = $tblLanguages->itemInSelectbox($this->_arrParam);
		
		if($this->_request->isPost()){
			$validator = new Default_Form_ValidateMenu($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			}else{
				$tblMenu->saveItem($this->_arrParam,array('task'=>'admin-edit'));
				$cache = new Zendvn_File_Cache();
				$cache->clear();
				$this->_redirect($this->_actionMain . $this->_type);
			}
		}
	}
	
	public function deleteAction(){
		$this->view->Title = 'Menu :: Xóa';
		$this->view->headTitle($this->view->Title, true);
		if($this->_request->isPost()){
			$cache = new Zendvn_File_Cache();
			$cache->clear();
			if(($this->_arrParam['type'] == 'multi-delete') && ($this->_arrParam['task'] == 'ok')){
				$tblMenu = new Default_Model_Menu();
				$tblMenu->deleteItem($this->_arrParam, array('task'=>'admin-delete-muti'));
				$this->_redirect($this->_actionMain . $this->_type);
			}else
				if(!empty($this->_arrParam['id'])){
				$tblMenu = new Default_Model_Menu();
				$tblMenu->deleteItem($this->_arrParam, array('task'=>'admin-delete'));
				$this->_redirect($this->_actionMain . $this->_type);
			}
		}
	}
	
	public function statusAction(){
		$cache = new Zendvn_File_Cache();
		$cache->clear();
		
		$tblMenu = new Default_Model_Menu();
		$tblMenu->changeStatus($this->_arrParam);
		$this->_redirect($this->_actionMain . $this->_type);
		$this->_helper->viewRenderer->setNoRender();
	}
	
	public function sortAction(){
		$cache = new Zendvn_File_Cache();
		$cache->clear();
		
		if($this->_request->isPost()){
			$tblMenu = new Default_Model_Menu();
			$tblMenu->sortItem($this->_arrParam, array('task'=>'admin-sort'));
			$this->_redirect($this->_actionMain . $this->_type);
		}
		$this->_helper->viewRenderer->setNoRender();
	}
	
	public function homeAction(){
		$this->view->Item = $this->_arrParam;
		$this->_helper->layout->disableLayout();
	}
	
	public function articleAction(){
		$tblArticles = new Article_Model_Category();
		$this->view->slbCategory = $tblArticles->itemInSelectbox($this->_arrParam);
		$this->view->Item = $this->_arrParam;
		$this->_helper->layout->disableLayout();
	}
	
	public function shoppingAction(){
		$tblShopping = new Shopping_Model_Category();
		$this->view->slbCategory = $tblShopping->itemInSelectbox($this->_arrParam);
		$this->view->Item = $this->_arrParam;
		$this->_helper->layout->disableLayout();
	}
	
	public function galleryAction(){
		$tblGallery = new Gallery_Model_Category();
		$this->view->slbCategory = $tblGallery->itemInSelectbox($this->_arrParam);
		$this->view->Item = $this->_arrParam;
		$this->_helper->layout->disableLayout();
	}
	
	public function faqsAction(){
		$tblFaqs = new Faqs_Model_Category();
		$this->view->slbCategory = $tblFaqs->itemInSelectbox($this->_arrParam);
		$this->view->Item = $this->_arrParam;
		$this->_helper->layout->disableLayout();
	}
	
	public function contactAction(){
		$this->view->Item = $this->_arrParam;
		$this->_helper->layout->disableLayout();
	}
	
	public function sitemapAction(){
		$this->view->Item = $this->_arrParam;
		$this->_helper->layout->disableLayout();
	}
	
	public function urlAction(){
		$this->view->Item = $this->_arrParam;
		$this->_helper->layout->disableLayout();
	}

}



