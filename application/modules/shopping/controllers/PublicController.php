<?php
class Shopping_PublicController extends Zendvn_Controller_Action {
		
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
		$this->view->moduleConfig = $moduleConfig['module'];
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
		
		$info 		= new Zendvn_System_Info();
		$user_info 	= $info->getMemberInfo();
		$this->_arrParam['userInfo'] = $user_info;
	
		//Truyen ra ngoai view
		$this->view->arrParam = $this->_arrParam;
		$this->view->currentController = $this->_currentController;
		$this->view->actionMain = $this->_actionMain;
	
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/public/" . $siteConfig['template']['site'];
		$this->loadTemplate($template_path, 'template.ini', 'product_public');
		
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
	
	public function viewCartAction(){		
		$this->view->Title = $this->view->language['productGioHang'];
		$this->view->headTitle($this->view->Title, true);
		
		$tblMoney = new Default_Model_Money();
		$this->view->money = $tblMoney->getItem(null,array('task' => 'public-info'));
		
		$yourCart = new Zend_Session_Namespace('cart');
		if($this->_request->isPost()){
			$itemProduct = $this->_arrParam['itemProduct'];
			if(count($itemProduct)>0){
				foreach ($itemProduct as $key => $val){
					if($val == 0){
						unset($itemProduct[$key]);
					}
				}
			}
			$yourCart->cart = $itemProduct;
		}
		
		if(!empty($this->_arrParam['delete'])){
			unset($yourCart->cart[$this->_arrParam['delete']]);
		}

		$ssInfo = $yourCart->getIterator();
		$tblProduct = new Shopping_Model_Item();
		$this->_arrParam['cart'] = $ssInfo['cart'];
		$this->view->cart = $ssInfo['cart'];
		$this->view->Items = $tblProduct->listItem($this->_arrParam,array('task'=>'view-cart'));
	}
	
	public function addItemAction(){
		$yourCart = new Zend_Session_Namespace('cart');
		$ssInfo = $yourCart->getIterator();
		$filter = new Zend_Filter_Digits();
		$id = $filter->filter($this->_arrParam['id']);
		if(count($yourCart->cart) < 1){
			$cart[$id] = 1;
			$yourCart->cart = $cart;
		}else{
			$tmp = $ssInfo['cart'];
			if(array_key_exists($id, $tmp) == true){
				$tmp[$id] = $tmp[$id] + 1;
			}else{
				$tmp[$id] = 1;
			}
			if(isset($tmp[''])){
				unset($tmp['']);
			}
			$yourCart->cart = $tmp;
		}
		$ssInfo = $yourCart->getIterator();
		$this->_redirect($this->_currentController . '/view-cart');
		$this->_helper->viewRenderer->setNoRender();
	}
	
	public function orderAction(){
		if(count($this->_arrParam['userInfo']) > 0){
			if($this->_arrParam['userInfo']['active_code'] != '0'){
				$this->_forward('enabled','public','default');
			}
		} else {
			$this->_forward('login','user','default');
		}

		$siteConfig = Zend_Registry::get('siteConfig');
		
		$this->view->Title = $this->view->language['productGuiThongTinDatHang'];
		$this->view->headTitle($this->view->Title, true);
		
		$tblMoney = new Default_Model_Money();
		$this->view->money = $tblMoney->getItem(null,array('task' => 'public-info'));
		
		$yourCart = new Zend_Session_Namespace('cart');		
		$ssInfo = $yourCart->getIterator();
		$tblProduct = new Shopping_Model_Item();
		$this->_arrParam['cart'] = $ssInfo['cart'];
		$this->view->cart = $ssInfo['cart'];
		$this->view->Items = $tblProduct->listItem($this->_arrParam,array('task'=>'view-cart'));
		if(count($this->view->Items) < 1){
			$this->_redirect('shopping/public/view-cart');
		}
		
		$captcha = new Zendvn_Captcha_Image();
		//9. Truyen gia tri Captcha ra view
		$this->view->captcha = $captcha->render($this->view);
		$this->view->captcha_id = $captcha->getId();
		
		if($this->_request->isPost()){
			
			$validator = new Shopping_Form_ValidateOrder($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			}else{
				$arrParam = $validator->getData();
				
				$tblInvoice = new Shopping_Model_Invoice();
				$invoice_id = $tblInvoice->saveItem($this->_arrParam,array('task'=>'public-order'));
				
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
					$options['subject'] = 'Có một thông tin đặt hàng lúc ' . @date('d/m/Y H:i:s');//Tiêu đề thư
				
					$content = '<div><p>Xin chào bạn,</p>
					<p>Có một khách hàng đã đặt hàng với các thông tin sau:</p>
					<p>Họ tên:&nbsp;<strong>'.$arrParam['full_name'].'</p>
					<p>Email:&nbsp;<strong>'.$arrParam['email'].'</p>
					<p>Điện thoại:&nbsp;<strong>'.$arrParam['phone'].'</p>
					<p>Địa chỉ nhận hàng:&nbsp;<strong>'.$arrParam['shipping'].'</p>
					<p><strong>Nội dung:</strong></p>
					<p>'.$arrParam['comment'].'</p><br>
					<p><em>Bạn truy cập vào admin để biết thông tin chi tiết hơn về khách hàng đã đặt hàng</em></p>';
				
					$mail = new Zendvn_Phpmailer();
					$mail->send($options,$content);
				}
					
				$tblInvoiceDetail = new Shopping_Model_InvoiceDetail();
				$this->_arrParam['invoice_id'] = $invoice_id;
				$tblInvoiceDetail->saveItem($this->_arrParam);
				$yourCart->unsetAll();
				
				$this->_redirect('shopping/public/view-cart/save/ok');
			}
			$captcha->removeImg($this->_arrParam['captchaID']);
		}
		
		$this->_imgCaptcha = $captcha->getId() . $captcha->getSuffix();
		$captchaSession = new Zend_Session_Namespace('captcha');
		$captchaSession->imgCaptcha = $this->_imgCaptcha;
		
	}
	
	public function guideAction(){
		$this->view->title = $this->view->language['productHuongDanMuaHang'];
		$this->view->headTitle($this->view->title, true);
	}
	
	public function tagsAction(){
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/public/" . $siteConfig['template']['site'];
		$this->loadTemplate($template_path, 'template.ini', 'product_block');
		
		$this->view->headTitle($this->_arrParam['key'], true);
	
		$tblProduct = new Shopping_Model_Item();
		$this->view->Items = $tblProduct->listItem($this->_arrParam,array('task'=>'public-filter', 'filter' => 'tags'));
		$this->view->totalItem = $totalItem = $tblProduct->countItem($this->_arrParam,array('task'=>'public-filter', 'filter' => 'tags'));
	
		$paginator = new Zendvn_Paginator();
		$this->view->paginator = $paginator->createPaginator($totalItem, $this->_paginator);
	}
	
	public function preDispatch(){
		$captchaSession = new Zend_Session_Namespace('captcha');
		if(!empty($captchaSession->imgCaptcha)){
			$filename = CAPTCHA_PATH . '/img/' . $captchaSession->imgCaptcha;
			@unlink($filename);
		}
	}
	
}






