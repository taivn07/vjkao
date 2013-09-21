<?php
class AdminSystemController extends Zendvn_Controller_Action{
	
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
	
		//Truyen ra ngoai view
		$this->view->arrParam = $this->_arrParam;
		$this->view->currentController = $this->_currentController;
		$this->view->actionMain = $this->_actionMain;
	
		$siteConfig = Zend_Registry::get('siteConfig');
		$template_path = TEMPLATE_PATH . "/admin/" . $siteConfig['template']['admin'];
		$this->loadTemplate($template_path, 'template.ini', 'template');
	}
	
	public function indexAction(){
		$this->view->Title = 'Thông tin hệ thống';
		$this->view->headTitle($this->view->Title, true);
		
		$this->view->info = array();
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		if (isset($_SERVER['SERVER_SOFTWARE'])) {
			$sf = $_SERVER['SERVER_SOFTWARE'];
		}
		else {
			$sf = getenv('SERVER_SOFTWARE');
		}
		$this->view->info['php']			= php_uname();
		$this->view->info['dbversion']		= $db->getServerVersion();
		$this->view->info['phpversion']		= phpversion();
		$this->view->info['server']			= $sf;
		$this->view->info['useragent']		= $_SERVER['HTTP_USER_AGENT'];
		
		if (is_null($this->view->php_info))
		{
			ob_start();
			date_default_timezone_set('UTC');
			phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
			$phpinfo = ob_get_contents();
			ob_end_clean();
			preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpinfo, $output);
			$output = preg_replace('#<table[^>]*>#', '<table class="adminlist">', $output[1][0]);
			$output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
			$output = preg_replace('#<hr />#', '', $output);
			$output = str_replace('<div class="center">', '', $output);
			$output = preg_replace('#<tr class="h">(.*)<\/tr>#', '<thead><tr class="h">$1</tr></thead><tbody>', $output);
			$output = str_replace('</table>', '</tbody></table>', $output);
			$output = str_replace('</div>', '', $output);
			$this->view->php_info = $output;
		}
	}
	
	public function cacheAction(){
		$this->view->Title = 'Xóa cache';
		$this->view->headTitle($this->view->Title, true);
		
		$cache = new Zendvn_File_Cache();
		$cache->clear();
	}

}



