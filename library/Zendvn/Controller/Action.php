<?php
class Zendvn_Controller_Action extends Zend_Controller_Action{
	
	public function init(){
		/* $template_path = TEMPLATE_PATH . "/admin/system";
		$this->loadTemplate($template_path, 'template.ini', 'template'); */
	}
	
	protected function loadTemplate($template_path, $fileConfig = 'template.ini', $sectionConfig = 'template'){
		
		//Xoa nhung du lieu cua layout truoc
		$this->view->headTitle()->set('');
		$this->view->headMeta()->getContainer()->exchangeArray(array());
		$this->view->headLink()->getContainer()->exchangeArray(array());
		$this->view->headScript()->getContainer()->exchangeArray(array());
		
		$filename = $template_path . "/" . $fileConfig;
		$section = $sectionConfig;
		$config = new Zend_Config_Ini($filename,$section);
		$config = $config->toArray();
		
		$baseUrl = $this->_request->getBaseUrl();
		$templateUrl = $baseUrl . $config['url'];
		$cssUrl = $templateUrl . $config['dirCss'];
		$jsUrl = $templateUrl . $config['dirJs'];
		$imgUrl = $templateUrl . $config['dirImg'];
		
		
		//Nap title cho layput
		$this->view->headTitle($config['title']);
		
		//Nap cac the meta vao layout
		if(count($config['metaHttp'])>0){
			foreach ($config['metaHttp'] as $key => $value){
				$tmp = explode('|', $value);
				$this->view->headMeta()->appendHttpEquiv($tmp[0],$tmp[1]);
			}
		}
		
		//Nap cac the metaName vao layout
		if(count($config['metaName'])>0){
			foreach ($config['metaName'] as $key => $value){
				$tmp = explode('|', $value);
				$this->view->headMeta()->appendName($tmp[0],$tmp[1]);
			}
		}
		
		//Nap cac the css vao layout
		if(count($config['fileCss'])>0){
			foreach ($config['fileCss'] as $key => $css){
				$this->view->headLink()->appendStylesheet($cssUrl . $css,'screen');
			}
		}
		
		//Nap cac the javascript vao layout
		if(count($config['fileJs'])>0){
			foreach ($config['fileJs'] as $key => $js){
				$this->view->headScript()->appendFile($jsUrl . $js,'text/javascript');
			}
		}
		
		$this->view->templateUrl = $templateUrl;
		$this->view->cssUrl = $cssUrl;
		$this->view->jsUrl = $jsUrl;
		$this->view->imgUrl = $imgUrl;
		
		
		$option = array('layoutPath'=>$template_path, 'layout'=>$config['layout']);
		Zend_Layout::startMvc($option);
		
	}
}