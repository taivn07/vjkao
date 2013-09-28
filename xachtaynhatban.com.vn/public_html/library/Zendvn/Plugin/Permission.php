<?php
class Zendvn_Plugin_Permission extends Zend_Controller_Plugin_Abstract{
	
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
	
		$auth = Zend_Auth::getInstance();
		
		$moduleName = $this->_request->getModuleName();
		$controllerName = $this->_request->getControllerName();
		$flagAdmin = false;
		if($controllerName == 'admin' || $controllerName == 'user'){
			$flagAdmin = true;
			$permissionController = $controllerName;
		}else{
			$tmp = explode('-', $controllerName);
			if($tmp[0] == 'admin' || $tmp[0] == 'user'){
				$flagAdmin = true;
				$permissionController = $tmp[0];
			}
		}
			
		//echo $permission;
		if($permissionController == 'admin'){
		
			$flagPage = 'none';
			if($flagAdmin == true){
				if($auth->hasIdentity() == false){
					$flagPage = 'login';
				}else{
					$info = new Zendvn_System_Info();
					$group_acp = $info->getGroupInfo('group_acp');
					if($group_acp != 1){
						$flagPage = 'login';
					}else{
						$permission = $info->getGroupInfo('permission');
						if($permission != 'Full Access'){
							$aclInfo = $info->getAclInfo();
							$acl = new Zendvn_System_Acl($aclInfo);
							$arrParam = $this->_request->getParams();
							if($acl->isAllowed($arrParam) == false){
								$flagPage = 'no-access';
							}
						}
					}
				}
			}
		
			if($flagPage != 'none'){
				if($flagPage == 'login'){
					$this->_request->setModuleName('default');
					$this->_request->setControllerName('public');
					$this->_request->setActionName('login');
				}
		
				if($flagPage == 'no-access'){
					$this->_request->setModuleName('default');
					$this->_request->setControllerName('public');
					$this->_request->setActionName('no-access');
				}
			}
		}
			
		//----------------- KIEM TRA QUYEN TRUY CAP VAO ADMIN ----------------//
		if($permissionController == 'user'){
			
			$flagPage = 'none';
			if($flagAdmin == true){
				if($auth->hasIdentity() == false){
					$flagPage = 'login';
				}else{
					$info = new Zendvn_System_Info();
					$aclInfo = $info->getAclInfo();
					$acl = new Zendvn_System_Acl($aclInfo);
					$arrParam = $this->_request->getParams();
					if($acl->isAllowed($arrParam) == false){
						$flagPage = 'no-access';
					}
				}
			}
			
			if(($this->_request->getActionName() != 'registry') && ($this->_request->getActionName() != 'success')){
				if($flagPage != 'none'){
					if($flagPage == 'login'){
						$this->_request->setModuleName('default');
						$this->_request->setControllerName('user');
						$this->_request->setActionName('login');
					}
				
					if($flagPage == 'no-access'){
						$this->_request->setModuleName('default');
						$this->_request->setControllerName('user');
						$this->_request->setActionName('no-access');
					}
				}
			}
		}
	}
}