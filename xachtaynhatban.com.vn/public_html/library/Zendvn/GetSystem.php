<?php
class Zendvn_GetSystem{
		
	public function getAll($options = null){
		// action body
		$frontController = Zend_Controller_Front::getInstance();
		$all = array(); // mang chua tat ca cac module, controller, action
		$arrModule = array();
		// Duyet tat cac cac folder controller
		foreach ($frontController->getControllerDirectory() as $module => $path){
			$arrModule[] = $module;
			// Duyet lay ra tat cac cac file trong folder controller
			foreach(scandir($path) as $file){
				// Kiem tra xem co phai la file dinh nghia class hay khong
				if(strstr($file, "Controller.php") !== false){
					if(strstr($file, 'Controller.php')){
						$controller = substr($file, 0, strpos($file, 'Controller.php'));
						if($module === $frontController->getDefaultModule()){
							$class = $controller . 'Controller';
						}else{
							$class = ucfirst($module) . '_' . $controller . 'Controller';
						}
						$controller = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '-\\1', $controller));
		
						include_once $path . DIRECTORY_SEPARATOR . $file;
						$actions = array();
						foreach (get_class_methods($class) as $action){
							if(strstr($action, 'Action') !== false){
								$action = substr($action, 0, strpos($action, 'Action'));
								$action = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '-\\1', $action));
								$actions[] = $action;
							}
						}
		
						$all[$module][$controller] = $actions;
					}
				}
			}
		}
		
		$result = $all;
		return $result;
	}
}