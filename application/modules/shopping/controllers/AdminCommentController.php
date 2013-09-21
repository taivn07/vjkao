<?php
class Shopping_AdminCommentController extends Zendvn_Controller_Action{
	
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
			$ssFilter->col 		= 'c.id';
			$ssFilter->id 		= 'DESC';
			$ssFilter->order 	= 'DESC';
		}
		$this->_arrParam['ssFilter']['keywords'] 	= $ssFilter->keywords;
		$this->_arrParam['ssFilter']['col'] 		= $ssFilter->col;
		$this->_arrParam['ssFilter']['order'] 		= $ssFilter->order;
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
		$this->view->Title = 'Danh sách bình luận sản phẩm';
		$this->view->headTitle($this->view->Title, true);
		
		$tblComment = new Default_Model_Comment();
		$this->view->Items = $tblComment->listItem($this->_arrParam, array('task'=>'admin-list'));
		
		$tblLanguages = new Default_Model_Language();
		$this->view->slbLanguages = $tblLanguages->itemInSelectbox($this->_arrParam);
		
		$totalItem = $tblComment->countItem($this->_arrParam, array('task'=>'admin-list'));
		
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
	
		if($this->_arrParam['type'] == 'lang'){
			$ssFilter->lang_code = $this->_arrParam['lang_code'];
		}
	
		$this->_redirect($this->_actionMain);
	
		$this->_helper->viewRenderer->setNoRender();
	}
	
	public function addReplyAction(){
		$this->view->Title = 'Bình luận :: Trả lời';
		$this->view->headTitle($this->view->Title, true);
		
		$tblComment = new Default_Model_Comment();
		$this->view->Item = $tblComment->getItem($this->_arrParam,array('task'=>'admin-info'));
		
		if($this->_request->isPost()){
			$validator = new Default_Form_ValidateCommentReply($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			}else{
				$arrParam = $validator->getData();
				$tblCommentReply = new Default_Model_CommentReply();
				$tblCommentReply->saveItem($arrParam,array('task'=>'admin-addReply'));
				$this->_redirect($this->_actionMain);
			}
		}
	}
	
	public function infoAction(){
		$this->view->Title = 'Bình luận :: Xem thông tin';
		$this->view->headTitle($this->view->Title, true);
		$tblComment = new Default_Model_Comment();
		$this->view->Item = $tblComment->getItem($this->_arrParam,array('task'=>'admin-info'));
		
	}
	
	public function editReplyAction(){
		$this->view->Title = 'Bình luận :: Trả lời :: Sửa';
		$this->view->headTitle($this->view->Title, true);
		
		$tblComment = new Default_Model_Comment();
		$this->view->Item = $tblComment->getItem($this->_arrParam,array('task'=>'admin-info'));
		
		$tblCommentReply = new Default_Model_CommentReply();
		$this->view->ItemReply = $tblCommentReply->getItem(array('id' => $this->_arrParam['reply']),array('task'=>'admin-info'));
		
		if($this->_request->isPost()){
			$validator = new Default_Form_ValidateCommentReply($this->_arrParam);
			if($validator->isError() == true){
				$this->view->messageError = $validator->getMessageError();
				$this->view->Item = $validator->getData();
			}else{
				$arrParam = $validator->getData();
				$tblCommentReply->saveItem($arrParam,array('task'=>'admin-editReply'));
				$this->_redirect($this->_actionMain);
			}
		}
	}
	
	public function deleteAction(){
		$this->view->Title = 'Bình luận :: Xóa';
		$this->view->headTitle($this->view->Title, true);
		if($this->_request->isPost()){
			if(($this->_arrParam['type'] == 'multi-delete') && ($this->_arrParam['task'] == 'ok')){
				$tblComment = new Default_Model_Comment();
				$tblComment->deleteItem($this->_arrParam, array('task'=>'admin-delete-muti'));
				$this->_redirect($this->_actionMain . $this->_page);
			}else if(!empty($this->_arrParam['id'])){
				$tblComment = new Default_Model_Comment();
				$tblComment->deleteItem($this->_arrParam, array('task'=>'admin-delete'));
				$this->_redirect($this->_actionMain . $this->_page);
			}
		}
	}
	
	public function deleteReplyAction(){
		$this->view->Title = 'Bình luận :: Trả lời :: Xóa';
		$this->view->headTitle($this->view->Title, true);
		if($this->_request->isPost()){
			if(!empty($this->_arrParam['id'])){
				$tblCommentReply = new Default_Model_CommentReply();
				$tblCommentReply->deleteItem($this->_arrParam, array('task'=>'admin-delete'));
				$this->_redirect($this->_actionMain . $this->_page);
			}
		}
	}
	
	public function statusAction(){
		$tblComment = new Default_Model_Comment();
		$tblComment->changeStatus($this->_arrParam);
		$this->_redirect($this->_actionMain . $this->_page);
		$this->_helper->viewRenderer->setNoRender();
	}

}



