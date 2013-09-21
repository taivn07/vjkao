<?php
class ContactController extends Zendvn_Controller_Action {
		
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
		$this->_paginator['itemCountPerPage'] = $moduleConfig['module']['countPage'];
		$this->_paginator['pageRange'] = $moduleConfig['module']['pageRange'];
	
		//Trang hien tai
		if(isset($this->_arrParam['page'])){
			$this->_paginator['currentPage'] = $this->_arrParam['page'];
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
		
		//Truyen ra ngoai view
		$this->view->arrParam = $this->_arrParam;
		$this->view->currentController = $this->_currentController;
		$this->view->actionMain = $this->_actionMain;
	
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/public/" . $siteConfig['template']['site'];
		$this->loadTemplate($template_path, 'template.ini', 'contact');
		
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
		
		$siteConfig = Zend_Registry::get('siteConfig');
		$this->view->headTitle($this->view->language['lienHe'], true);
		
		$tblContact = new Default_Model_Contact();
		$this->view->ContactInfo = $tblContact->getItem($this->_arrParam, array('task'=>'public-contact'));
		
		$captcha = new Zendvn_Captcha_Image();
		//9. Truyen gia tri Captcha ra view
		$this->view->captcha = $captcha->render($this->view);
		$this->view->captcha_id = $captcha->getId();
		
		if($this->_request->isPost()){
			$validator = new Default_Form_ValidateContact($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			}else{
				$arrParam = $validator->getData();
				
				$options = $siteConfig['config_mail'];
				if($options['status'] == 1){
					$options['smtpsecure'] = $siteConfig['config_mail']['smtpsecure'];
					$options['smtphost'] = $siteConfig['config_mail']['smtphost'];
					$options['smtpport'] = $siteConfig['config_mail']['smtpport'];
					$options['smtpuser'] = $siteConfig['config_mail']['smtpuser'];
					$options['smtppass'] = $siteConfig['config_mail']['smtppass'];
					$options['mailfrom'] = $siteConfig['config_mail']['mailfrom'];
					$options['fromname'] = $siteConfig['config_mail']['fromname'];
					$options['tomail'] = $siteConfig['config_mail']['tomail'];//Mail người nhận
					$options['title'] = 'Thông báo';//Tên người gửi
					$options['subject'] = 'Có một thông tin liên hệ lúc ' . @date('d/m/Y h:i:s');//Tiêu đề thư
				
					$content = '<div><p>Xin chào bạn,</p>
					<p>Có một khách hàng đã liên hệ với bạn với các thông tin sau:</p>
					<p>Họ tên:&nbsp;<strong>'.$arrParam['name'].'</p>
					<p>Địa chỉ:&nbsp;<strong>'.$arrParam['address'].'</p>
					<p>Điện thoại:&nbsp;<strong>'.$arrParam['phone'].'</p>
					<p>Email:&nbsp;<strong>'.$arrParam['email'].'</p>
					<p><strong>Nội dung:</strong></p>
					<p>'.$arrParam['content'].'</p><br>
					<p><em>Bạn truy cập vào admin để biết thông tin chi tiết hơn về khách hàng đã liên hệ</em></p>';
				
					$mail = new Zendvn_Phpmailer();
					$mail->send($options,$content);
				}
				
				$tblContact->saveItem($this->_arrParam,array('task'=>'contact-add'));
				$this->_redirect('contact.html?save=ok');
			}
			$captcha->removeImg($this->_arrParam['captchaID']);
		}
		$this->_imgCaptcha = $captcha->getId() . $captcha->getSuffix();
		$captchaSession = new Zend_Session_Namespace('captcha');
		$captchaSession->imgCaptcha = $this->_imgCaptcha;
		
		if($this->_arrParam['save'] == 'ok'){
			$scipt = "alert('".$this->view->language['sendInfo']."')";
			$this->view->headScript()->appendScript($scipt);
		}
	}
	
	public function preDispatch(){
		$captchaSession = new Zend_Session_Namespace('captcha');
		if(!empty($captchaSession->imgCaptcha)){
			$filename = CAPTCHA_PATH . '/img/' . $captchaSession->imgCaptcha;
			@unlink($filename);
		}
	}
	
}






