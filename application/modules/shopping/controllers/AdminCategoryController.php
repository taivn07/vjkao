<?php
class Shopping_AdminCategoryController extends Zendvn_Controller_Action{
	
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
	
	//Url page
	protected $_page = '';
	
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
			$ssFilter->lang_code= 'vi';
		} */
		
		$this->_arrParam['ssFilter']['keywords'] 	= $ssFilter->keywords;
		$this->_arrParam['ssFilter']['parents'] 	= $ssFilter->parents;
		if(empty($ssFilter->lang_code)){
			$language = new Zend_Session_Namespace('language');
			$this->_arrParam['ssFilter']['lang_code'] = $language->lang;
		}else{
			$this->_arrParam['ssFilter']['lang_code'] 	= $ssFilter->lang_code;
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
		$this->view->Title = 'Sản phẩm :: Danh mục';
		$this->view->headTitle($this->view->Title, true);
		$tblCategory = new Shopping_Model_Category();
		$this->view->slbCategory = $tblCategory->itemInSelectbox($this->_arrParam);
		
		$tblLanguages = new Default_Model_Language();
		$this->view->slbLanguages = $tblLanguages->itemInSelectbox($this->_arrParam);
		
		$this->view->Items = $tblCategory->listItem($this->_arrParam, array('task'=>'admin-list'));
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
		$this->view->Title = 'Sản phẩm :: Danh mục :: Thêm mới';
		$this->view->headTitle($this->view->Title, true);
		$tblCategory = new Shopping_Model_Category();
		$this->view->slbCategory = $tblCategory->itemInSelectbox($this->_arrParam);
		
		if($this->_request->isPost()){
			$validator = new Shopping_Form_ValidateCategory($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			}else{
				$cache = new Zendvn_File_Cache();
				$cache->clear();
				
				$arrParam = $validator->getData();
				$tblCategory->saveItem($arrParam,array('task'=>'admin-add'));
				$this->_redirect($this->_actionMain);
			}
		}
	}
	
	public function infoAction(){
		$this->view->Title = 'Sản phẩm :: Danh mục :: Xem thông tin';
		$this->view->headTitle($this->view->Title, true);
		$tblCategory = new Shopping_Model_Category();
		$this->view->Item = $tblCategory->getItem($this->_arrParam,array('task'=>'admin-info'));
		
		$tblLanguages = new Default_Model_Language();
		$this->view->slbLanguages = $tblLanguages->itemInSelectbox($this->_arrParam);
	}
	
	public function editAction(){
		$this->view->Title = 'Sản phẩm :: Danh mục :: Sửa';
		$this->view->headTitle($this->view->Title, true);
		$tblCategory = new Shopping_Model_Category();
		$this->view->Item = $tblCategory->getItem($this->_arrParam,array('task'=>'admin-edit'));
		$this->view->slbCategory = $tblCategory->itemInSelectbox($this->_arrParam,array('task'=>'admin-edit'));
		
		if($this->_request->isPost()){
			$validator = new Shopping_Form_ValidateCategory($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			}else{
				$cache = new Zendvn_File_Cache();
				$cache->clear();
				
				$arrParam = $validator->getData();
				$tblCategory->saveItem($arrParam,array('task'=>'admin-edit'));
				$this->_redirect($this->_actionMain);
			}
		}
	}
	
	public function deleteAction(){
		$this->view->Title = 'Sản phẩm :: Danh mục :: Xóa';
		$this->view->headTitle($this->view->Title, true);
		if($this->_request->isPost()){
			if(($this->_arrParam['type'] == 'multi-delete') && ($this->_arrParam['task'] == 'ok')){
				$tblCategory = new Shopping_Model_Category();
				$tblCategory->deleteItem($this->_arrParam, array('task'=>'admin-delete-muti'));
				$this->_redirect($this->_actionMain . $this->_page);
			}else
				if(!empty($this->_arrParam['id'])){
				$tblCategory = new Shopping_Model_Category();
				$tblCategory->deleteItem($this->_arrParam, array('task'=>'admin-delete'));
				$this->_redirect($this->_actionMain . $this->_page);
			}
			$cache = new Zendvn_File_Cache();
			$cache->clear();
		}
	}
	
	public function statusAction(){
		$cache = new Zendvn_File_Cache();
		$cache->clear();
		
		$tblCategory = new Shopping_Model_Category();
		$tblCategory->changeStatus($this->_arrParam);
		$this->_redirect($this->_actionMain . $this->_page);
		$this->_helper->viewRenderer->setNoRender();
	}
	
	public function sortAction(){
		if($this->_request->isPost()){
			$cache = new Zendvn_File_Cache();
			$cache->clear();
			
			$tblCategory = new Shopping_Model_Category();
			$tblCategory->sortItem($this->_arrParam, array('task'=>'admin-sort'));
			$this->_redirect($this->_actionMain . $this->_page);
		}
		$this->_helper->viewRenderer->setNoRender();
	}

}



