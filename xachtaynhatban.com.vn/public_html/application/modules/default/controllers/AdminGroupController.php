<?php
class AdminGroupController extends Zendvn_Controller_Action{
	
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
		$this->_currentController = '/' . $this->_arrParam['module'] . '/' 
									. $this->_arrParam['controller'];
		
		//Duong dan cua Action chinh
		$this->_actionMain = '/' . $this->_arrParam['module'] . '/' 
								. $this->_arrParam['controller'] . '/index';
		
		$this->_arrParam['paginator'] = $this->_paginator;
		
		//Luu cac du lieu filter vaof SESSION
		//Dat ten SESSION
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
			$this->_page = '/page/' . $this->_arrParam['page'];
		}
		
		//Truyen thong tin phan trang vao mang du lieu
		$this->_arrParam['paginator'] = $this->_paginator;
		
		//$ssFilter->unsetAll();
		if(empty($ssFilter->col)){
			$ssFilter->keywords = '';
			$ssFilter->col 		= 'g.id';
			$ssFilter->order 	= 'ASC';
		}
		$this->_arrParam['ssFilter']['keywords'] 	= $ssFilter->keywords;
		$this->_arrParam['ssFilter']['col'] 		= $ssFilter->col;
		$this->_arrParam['ssFilter']['order'] 		= $ssFilter->order;
		
		//Truyen ra ngoai view
		$this->view->arrParam = $this->_arrParam;
		$this->view->currentController = $this->_currentController;
		$this->view->actionMain = $this->_actionMain;
		
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/admin/" . $siteConfig['template']['admin'];
		$this->loadTemplate($template_path, 'template.ini', 'template');
		
		$language = Zend_Registry::get('language');
		$this->view->language = $language['language'];
	}
	
	public function indexAction(){		
		$this->view->Title = 'Nhóm thành viên :: Danh sách';
		$this->view->headTitle($this->view->Title, true);
		
		$tblGroup = new Default_Model_UserGroup();
		$this->view->Items = $tblGroup->listItem($this->_arrParam, array('task'=>'admin-list'));
		$totalItem = $tblGroup->countItem($this->_arrParam);

		$paginator = new Zendvn_Paginator();
		$this->view->paginator = $paginator->createPaginator($totalItem, $this->_paginator);

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
		
		if($this->_arrParam['type'] == 'order'){
			$ssFilter->col = $this->_arrParam['col'];
			$ssFilter->order = $this->_arrParam['by'];
		}
		
		$this->_redirect($this->_actionMain);
		
		$this->_helper->viewRenderer->setNoRender();
	}
	
	public function addAction(){
		$this->view->Title = 'Nhóm thành viên :: Thêm mới';
		$this->view->headTitle($this->view->Title, true);
		
		$tblGroup = new Default_Model_UserGroup();
		$this->view->Privileges = $tblGroup->privileges($this->_arrParam,array('task'=>'admin-add'));
		$this->view->PrivilegesModule = $tblGroup->privileges($this->_arrParam,array('task'=>'admin-module'));
		
		if($this->_request->isPost()){
			$validator = new Default_Form_ValidateGroup($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			}else{
				$arrParam = $validator->getData();
				$tblGroup->saveItem($this->_arrParam,array('task'=>'admin-add'));
				$this->_redirect($this->_actionMain);
			}
		}
	}
	
	public function infoAction(){
		$this->view->Title = 'Nhóm thành viên :: Xem thông tin';
		$this->view->headTitle($this->view->Title, true);
		$tblGroup = new Default_Model_UserGroup();
		$this->view->Item = $tblGroup->getItem($this->_arrParam,array('task'=>'admin-info'));

	}
	
	public function editAction(){
		$this->view->Title = 'Nhóm thành viên :: Sửa';
		$this->view->headTitle($this->view->Title, true);
		$tblGroup = new Default_Model_UserGroup();
		$this->view->Item = $tblGroup->getItem($this->_arrParam,array('task'=>'admin-edit'));
		$this->view->Item['privileges'] = $tblGroup->getItem($this->_arrParam,array('task'=>'admin-permission'));
		$user_files = $tblGroup->getItem($this->_arrParam,array('task'=>'admin-files'));
		if(!empty($user_files)){
			foreach ($user_files AS $key => $val){
				$this->view->Item[$key] = $val;
			}
		}
		
		$this->view->Privileges = $tblGroup->privileges($this->_arrParam,array('task'=>'admin-add'));
		$this->view->PrivilegesModule = $tblGroup->privileges($this->_arrParam,array('task'=>'admin-module'));
		
		if($this->_request->isPost()){
			$validator = new Default_Form_ValidateGroup($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			}else{
				$tblGroup = new Default_Model_UserGroup();
				$arrParam = $validator->getData();
				$tblGroup->saveItem($arrParam,array('task'=>'admin-edit'));
				$this->_redirect($this->_actionMain . $this->_page);
			}
		}
	}
	
	public function deleteAction(){
		$this->view->Title = 'Nhóm thành viên :: Xóa';
		$this->view->headTitle($this->view->Title, true);
		if($this->_request->isPost()){
			if(($this->_arrParam['type'] == 'multi-delete') && ($this->_arrParam['task'] == 'ok')){
				$tblGroup = new Default_Model_UserGroup();
				$tblGroup->deleteItem($this->_arrParam, array('task'=>'admin-delete-muti'));
				$this->_redirect($this->_actionMain . $this->_page);
			}else
			if(!empty($this->_arrParam['id'])){
				$tblGroup = new Default_Model_UserGroup();
				$tblGroup->deleteItem($this->_arrParam, array('task'=>'admin-delete'));
				$this->_redirect($this->_actionMain . $this->_page);
			}
		}
	}
	
	public function statusAction(){
		$tblGroup = new Default_Model_UserGroup();
		$tblGroup->changeStatus($this->_arrParam);
		$this->_redirect($this->_actionMain . $this->_page);
		$this->_helper->viewRenderer->setNoRender();
	}
	
	public function statusAcpAction(){
		$tblGroup = new Default_Model_UserGroup();
		$tblGroup->changeStatusAcp($this->_arrParam);
		$this->_redirect($this->_actionMain . $this->_page);
		$this->_helper->viewRenderer->setNoRender();
	}
	
	public function sortAction(){
		if($this->_request->isPost()){
			$tblGroup = new Default_Model_UserGroup();
			$tblGroup->sortItem($this->_arrParam, array('task'=>'admin-sort'));
			$this->_redirect($this->_actionMain . $this->_page);
		}
		$this->_helper->viewRenderer->setNoRender();
	}
}




