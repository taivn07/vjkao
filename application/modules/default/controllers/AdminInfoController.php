<?php
class AdminInfoController extends Zendvn_Controller_Action{
	
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
		$this->_actionMain = '/' . $this->_arrParam['module'] . '/'	. $this->_arrParam['controller'] . '/edit';	
	
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
	
	public function editAction(){
		$this->view->Title = 'Thông tin tài khoản';
		$this->view->headTitle($this->view->Title, true);
		
		$info 					= new Zendvn_System_Info();
		$admin_info 			= $info->getMemberInfo();
		$this->view->adminInfo 	= $admin_info;
		
		$tblUser = new Default_Model_Users();
		$this->view->Item = $tblUser->getItem(array('id' => $admin_info['id']),array('task'=>'admin-edit'));
		
		if($this->_request->isPost()){
			$validator = new Default_Form_ValidateUser($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			}else{
		
				$arrParam = $validator->getData(array('upload'=>true));
				$tblUser->saveItem($arrParam,array('task'=>'admin-edit'));
				$this->_redirect($this->_actionMain . '/save/ok');
			}
		}
	}
	
	public function passwordAction(){
		$this->view->Title = 'Đổi mật khẩu';
		$this->view->headTitle($this->view->Title, true);
		
		$info 					= new Zendvn_System_Info();
		$admin_info 			= $info->getMemberInfo();
		$this->view->adminInfo 	= $admin_info;
		
		$tblUser = new Default_Model_Users();
		$this->view->Item = $tblUser->getItem(array('id' => $admin_info['id']),array('task'=>'admin-edit'));
		
		if($this->_request->isPost()){
			$validator = new Default_Form_ValidatePassword($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			}else{
				$arrParam = $validator->getData();
				$tblUser->saveItem($arrParam,array('task'=>'admin-password'));
				$this->_redirect('/' . $this->_arrParam['module'] . '/'	. $this->_arrParam['controller'] . '/password/save/ok');
			}
		}
	}

}



