<?php
class PublicController extends Zendvn_Controller_Action{
	
	//Mang tham so nhan duoc khi mot Action chay
	protected $_arrParam;
	
	//Duong dan cua Controller
	protected $_currentController;
	
	//Duong dan cua Action chinh
	protected $_actionMain;
	
	public function init(){
		//Mang tham so nhan duoc khi mot Action chay
		$this->_arrParam = $this->_request->getParams();
	
		//Duong dan cua Controller
		$this->_currentController = '/' . $this->_arrParam['module'] . '/' . $this->_arrParam['controller'];
	
		//Duong dan cua Action chinh
		$this->_actionMain = '/' . $this->_arrParam['module'] . '/'	. $this->_arrParam['controller'] . '/index';	
	
		//Truyen ra ngoai view
		$this->view->arrParam = $this->_arrParam;
		$this->view->currentController = $this->_currentController;
		$this->view->actionMain = $this->_actionMain;
	
		$this->view->siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/admin/" . $this->view->siteConfig['template']['admin'];
		$this->loadTemplate($template_path, 'template.ini', 'template');
	}
	
	//Ham chay sau ham action
	public function postDispatch(){
		$siteConfig = Zend_Registry::get('siteConfig');
		$this->view->headMeta()->setName('description',$siteConfig['config_meta']['description']);
		$this->view->headMeta()->setName('keywords',$siteConfig['config_meta']['keywords']);
		$this->view->headMeta()->setHttpEquiv('Refresh',$siteConfig['config_meta']['refresh']);
		$this->view->headMeta()->setHttpEquiv('content-language',$siteConfig['config_meta']['content_language']);
		$this->view->headMeta()->setName('classification',$siteConfig['config_meta']['classification']);
		$this->view->headMeta()->setName('language',$siteConfig['config_meta']['language']);
		$this->view->headMeta()->setName('robots',$siteConfig['config_meta']['robots']);
		$this->view->headMeta()->setName('author',$siteConfig['config_meta']['author']);
		$this->view->headMeta()->setName('copyright',$siteConfig['config_meta']['copyright']);
		$this->view->headMeta()->setName('revisit-after',$siteConfig['config_meta']['revisit_after']);
	}
	
	public function errorAction(){
		$template_path = TEMPLATE_PATH . "/error/default";
		$this->loadTemplate($template_path, 'template.ini', 'template');
		$this->view->Title = 'Message: Error!';
		$this->view->headTitle($this->view->Title, true);
	}
	
	public function noAccessAction(){
		$template_path = TEMPLATE_PATH . "/error/default";
		$this->loadTemplate($template_path, 'template.ini', 'noaccess');
		$this->view->Title = 'No Access!';
		$this->view->headTitle($this->view->Title, true);
	}
	
	public function loginAction(){
		$this->view->Title = 'Đăng nhập';
		$this->view->headTitle($this->view->Title, true);
		
		$tblLanguages = new Default_Model_Language();
		$this->view->slbLanguages = $tblLanguages->itemInSelectbox($this->_arrParam);
		
		$currentUrl = 'default/admin';
		if($this->_arrParam['action'] != 'login'){
			$siteConfig = Zend_Registry::get('siteConfig');
			$currentUrl = explode($siteConfig['config_site']['site_dir'] . '/', $_SERVER['REDIRECT_URL']);
			$currentUrl = $currentUrl[1];
		}
		
		if($this->_request->isPost()){
			$validator = new Default_Form_ValidateLogin($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			} else {
				$auth = new Zendvn_System_Auth();
				if($auth->login($this->_arrParam) == true){
					$info = new Zendvn_System_Info();
					$info->createInfo();
					$ns = new Zend_Session_Namespace('language');
					$ns->lang = $this->_arrParam['lang_code'];
					
					$tblUser = new Default_Model_Users();
					$tblUser->saveItem(array('user_name' => $this->_arrParam['user_name']), array('task' => 'admin-visited'));
					$this->_redirect($currentUrl);
				} else {
					$error[] = $auth->getError();
					$this->view->messageError = $error;
				}
			}
		}
		
		$this->view->siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/admin/" . $this->view->siteConfig['template']['admin'];
		$this->loadTemplate($template_path, 'template.ini', 'login');
	}
	
	public function logoutAction(){
		$this->view->Title = 'Logout';
		$this->view->headTitle($this->view->Title, true);
		$auth = new Zendvn_System_Auth();
		$auth->logout();
		
		$info = new Zendvn_System_Info();
		$info->destroyInfo();
		
		$currentUrl = 'default/admin';
		$this->_redirect($currentUrl);
	}
	
	public function viewImageAction() {
		$imgName = explode('/images/', $this->view->url());
		
		$fileName = $imgName[1];
		if(substr($imgName[1], -1, 1) == '/'){
			$fileName = substr($imgName[1],0,-1);
		}

		if(!empty($this->_arrParam['images'])){
			$picture = Zendvn_File_Images::create( FILE_PATH . '/editor-upload/images/' . $fileName);
			$width = 200;
			if(isset($this->_arrParam['width'])){
				$width = $this->_arrParam['width'];
			}
			$height = 200;
			if(isset($this->_arrParam['height'])){
				$height = $this->_arrParam['height'];
			}
			$picture->resize($width,$height);
			$picture->show();
		}
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
	}
	
	public function languageAction(){
		$arrparam = $this->_request->getParams();
		$ns = new Zend_Session_Namespace('language');
		if(!empty($arrparam['change'])){
			$ns->lang = $arrparam['change'];
		}
		$this->_redirect('/index');
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
	}
	
	public function offAction(){
		$template_path = TEMPLATE_PATH . "/error/default";
		$this->loadTemplate($template_path, 'template.ini', 'template');
		$this->view->Title = 'Thông báo từ hệ thống';
		$this->view->headTitle($this->view->Title, true);
		
		$siteConfig = Zend_Registry::get('siteConfig');
		if($siteConfig['config_site']['offline'] == 1){
			$this->view->note = $siteConfig['config_site']['offline_message'];
		}else{
			$this->_redirect('/');
		}
	}
	
	public function enabledAction(){
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/public/" . $siteConfig['template']['site'];
		$this->loadTemplate($template_path, 'template.ini', 'user');
		
		$this->view->Title = 'Thông báo từ hệ thống';
		$this->view->headTitle($this->view->Title, true);
		//$this->_helper->layout->disableLayout();
	}
}