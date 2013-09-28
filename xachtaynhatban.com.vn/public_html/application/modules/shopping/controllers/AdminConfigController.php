<?php
class Shopping_AdminConfigController extends Zendvn_Controller_Action{
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
		$this->_currentController = '/' . $this->_arrParam['module'] . '/' 
									. $this->_arrParam['controller'];
		
		//Duong dan cua Action chinh
		$this->_actionMain = '/' . $this->_arrParam['module'] . '/' 
								. $this->_arrParam['controller'] . '/index';
		
		//Truyen ra ngoai view
		$this->view->arrParam = $this->_arrParam;
		$this->view->currentController = $this->_currentController;
		$this->view->actionMain = $this->_actionMain;
		
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/admin/" . $siteConfig['template']['admin'];
		$this->loadTemplate($template_path, 'template.ini', 'template');
	}
	
	public function indexAction(){
		$this->view->Title = 'Cấu hình module sản phẩm';
		$this->view->headTitle($this->view->Title, true);
		
		$tblLanguages = new Default_Model_Language();
		$this->view->slbLanguages = $tblLanguages->itemInSelectbox($this->_arrParam);
		
		$filename = MODULE_PATH . '/'.$this->_arrParam['module'].'/config/config.ini';
		
		$section = 'module-settings';
		$siteSettings = new Zend_Config_Ini($filename, $section);
		$this->view->moduleSettings = $siteSettings->toArray();
		
		if($this->_request->isPost()){
			$config = new Zend_Config_Ini($filename,$section,true);
			
			$config->module->countPage 			= $this->_arrParam['countPage'];
			$config->module->pageRange 			= $this->_arrParam['pageRange'];
			$config->module->showPrice 			= $this->_arrParam['showPrice'];
			$config->module->showVat 			= $this->_arrParam['showVat'];
			$config->module->showTinhTrang 		= $this->_arrParam['showTinhTrang'];
			$config->module->showHuongDan 		= $this->_arrParam['showHuongDan'];
			$config->module->showAddCart		= $this->_arrParam['showAddCart'];
			$config->module->showBaoHanh		= $this->_arrParam['showBaoHanh'];
			$config->module->showVanChuyen		= $this->_arrParam['showVanChuyen'];
			$config->module->showKhuyenMai		= $this->_arrParam['showKhuyenMai'];
			$config->module->showSynopsis 		= $this->_arrParam['showSynopsis'];
			$config->module->showTags	 		= $this->_arrParam['showTags'];
			$config->module->showMxh	 		= $this->_arrParam['showMxh'];
			$config->module->showComment 		= $this->_arrParam['showComment'];
			$config->module->checkComment		= $this->_arrParam['checkComment'];
			$config->module->typeComment 		= $this->_arrParam['typeComment'];
			$config->module->nganluong 			= $this->_arrParam['nganluong'];
			$config->module->baokim 			= $this->_arrParam['baokim'];
			
			$write = new Zend_Config_Writer_Ini();
			$write->write($filename,$config);
		
			$this->_redirect($this->_actionMain . '/type/save');
		}
	}
}



