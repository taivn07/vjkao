<?php
class AdminController extends Zendvn_Controller_Action{
	public function init(){
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/admin/" . $siteConfig['template']['admin'];
		$this->loadTemplate($template_path, 'template.ini', 'template');
		//$this->_helper->viewRenderer->setNoRender();
	}
	
	public function indexAction(){
		/* $info = new Zendvn_System_Info();
		$info->getInfo();
		echo "<pre>";
		print_r($info->getInfo());
		echo "</pre>";
		echo __METHOD__; */
		$tblUser = new Default_Model_Users();
		$this->view->totalUser = $tblUser->countItem();
		
		$tblCategoryArticle = new Article_Model_Category();
		$this->view->totalCategoryArticle = $tblCategoryArticle->countItem();
		
		$tblArticle = new Article_Model_Item();
		$this->view->totalArticle = $tblArticle->countItem();
		
		$tblCategoryProduct = new Shopping_Model_Category();
		$this->view->totalCategoryProduct = $tblCategoryProduct->countItem();
		
		$tblProduct = new Shopping_Model_Item();
		$this->view->totalProduct = $tblProduct->countItem();
		
		$tblInvoice = new Shopping_Model_Invoice();
		$this->view->totalInvoiceOn = $tblInvoice->countItem(array(),array('task'=>'admin-on'));
		$this->view->totalInvoiceOff = $tblInvoice->countItem(array(),array('task'=>'admin-off'));
		$this->view->totalInvoiceAll = $tblInvoice->countItem();
		
		$tblContact = new Default_Model_Contact();
		$this->view->totalContactOn = $tblContact->countItem(array(),array('task'=>'admin-on'));
		$this->view->totalContactOff = $tblContact->countItem(array(),array('task'=>'admin-off'));
		$this->view->totalContactAll = $tblContact->countItem();
	}
	
	public function countAction(){
		$this->_helper->layout->disableLayout();
	}
}