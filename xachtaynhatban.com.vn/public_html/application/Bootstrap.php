<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	
	protected function _initSession(){
		Zend_Session::start();
	}
	
	protected function _initDb(){
		$optionResources = $this->getOption('resources');
		$dbOption = $optionResources['db'];
		//Thiet lap lai thong tin database
		$dbOption['params']['host'] = 'localhost';
		$dbOption['params']['username'] = 'bb30027_thuoc';
		$dbOption['params']['password'] = '234@erfhhgg';
		$dbOption['params']['dbname'] = 'bb30027_thuoc';
		$dbOption['params']['charset'] = 'utf8';

		$adapter = $dbOption['adapter'];
		$config = $dbOption['params'];
		
		$db = Zend_Db::factory($adapter, $config);
		$db->setFetchMode(Zend_Db::FETCH_ASSOC);
		$db->query("SET NAMES 'utf8'");
		$db->query("SET CHARACTER SET 'utf8'");
		
		Zend_Registry::set('connectDb', $db);
		
		Zend_Db_Table::setDefaultAdapter($db);
		
		return $db;
	}
	
	protected function _initFrontcontroller(){
		$front = Zend_Controller_Front::getInstance();
		$front->addModuleDirectory(APPLICATION_PATH . '/modules');
		$front->setDefaultModule('default');
		$front->registerPlugin(new Zendvn_Plugin_Permission());
		$error = new Zend_Controller_Plugin_ErrorHandler(array('module'=>'default','controller'=>'public','action'=>'error'));
		
		//Dang ky thong bao error
		$front->registerPlugin($error);
		return $front;
	}
	
	protected function _initLoadRouter(){		
		$filename = CONFIG_PATH . '/app-router.ini';
		$config = new Zend_Config_Ini($filename,'setup-router');
		
		$objRouter = new Zend_Controller_Router_Rewrite();
		$router = $objRouter->addConfig($config,'routers');

		$front = Zend_Controller_Front::getInstance();
		$front->setRouter($router);
	}
	
	protected function _initConfig(){
		$filename = CONFIG_PATH . '/config_system.ini';
		$section = 'site-settings';
		$siteConfig = new Zend_Config_Ini($filename, $section);
		$siteConfig = $siteConfig->toArray();
		Zend_Registry::set('siteConfig', $siteConfig);
		
		//-------------------Ngôn ngữ hệ thống----------------//
		$ns = new Zend_Session_Namespace('language');
		//$ns->lang = 'vi';
		//Nguyen ngon ngu neu da ngon ngu
		if(empty($ns->lang)){
			$ns->lang = $siteConfig['config_site']['site_language_default'];
		}
		
		// ------------------ Ngôn ngữ site ------------------//
		$fileLang = LANGUAGE_PATH . '/' . $ns->lang . '/lang.ini';
		if(file_exists($fileLang)){
			$section = 'language';
			$siteConfigLang = new Zend_Config_Ini($fileLang, $section);
			$siteConfigLang = $siteConfigLang->toArray();
			Zend_Registry::set('language', $siteConfigLang);
		}else{
			$fileLang = LANGUAGE_PATH . '/vi/lang.ini';
			$section = 'language';
			$siteConfigLang = new Zend_Config_Ini($fileLang, $section);
			$siteConfigLang = $siteConfigLang->toArray();
			Zend_Registry::set('language', $siteConfigLang);
		}
		
		$locale = new Zend_Locale($ns->lang);
		Zend_Registry::set('Zend_Locale', $locale);
		
		$locale = $ns->lang;
		$file = APPLICATION_PATH . '/languages/' . $locale . '/validate/lang.tmx';
		$options = array(
				'adapter'=>'Tmx',
				'content'=>$file,
				'locale'=>$locale,
		);
		$translate = new Zend_Translate($options);
		Zend_Validate_Abstract::setDefaultTranslator($translate);
		
		//-------------------KHAI BAO DUONG DAN URL DEN CAC THU MUC----------------//
		
		//Duong dan url den thu muc ung dung
		define('APPLICATION_URL', $siteConfig['config_site']['site_dir']);
		define('SCIPTS_URL', APPLICATION_URL . '/public/scripts');
		define('PUBLIC_URL', APPLICATION_URL . '/public');
		
		//Duong dan url den thu muc /templates
		define('TEMPLATE_URL', '/public/templates');
		define('CAPTCHA_URL', APPLICATION_URL . '/public/captcha');
		define('FILE_URL', APPLICATION_URL . '/public/files');
		
	}
}





