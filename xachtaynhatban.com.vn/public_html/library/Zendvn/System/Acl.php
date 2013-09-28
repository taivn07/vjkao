<?php
class Zendvn_System_Acl{
	
	protected $_acl;
	protected $_role;
	
	public function __construct($aclInfo = null, $options = null){
		if(!empty($aclInfo)){
			$acl = new Zend_Acl();
			$this->_role = $aclInfo['role'];
			$acl->addRole(new Zend_Acl_Role($this->_role));
			
			$groupPrivileges = $aclInfo['privileges'];
			$acl->allow($this->_role, null, $groupPrivileges);
			$this->_acl = $acl;
		}
	}
	
	public function isAllowed($arrParam = null){
		$ns = new Zend_Session_Namespace('info');
		$nsInfo = $ns->getIterator();
		$privileges = $arrParam['module'] . '_' . $arrParam['controller'] . '_' . $arrParam['action'];
		$flagAccess = false;
		if($this->_acl->isAllowed($this->_role,null,$privileges)){
			$flagAccess = true;
		}
		return $flagAccess;
	}
	
	public function createPrivilegeArray($options = null){
		$ns = new Zend_Session_Namespace('info');
		$nsInfo = $ns->getIterator();
		$info = $nsInfo['member'];
		$group_id = $info['group_id'];
		
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		$select = $db->select()
					->from('privileges AS p')
					->join('user_group_privileges AS gp', 'gp.privilege_id = p.id')
					->where('gp.status = ?',1,INTERGER)
					->where('group_id = ?',$group_id,INTERGER);
		
		$result = $db->fetchAll($select);
		if(!empty($result)){
			$arrPrivileges = array();
			foreach ($result as $key){
				$arrPrivileges[] =  $key['module'] . '_' . $key['controller'] . '_' . $key['action'];
			}
		}
		$ns->acl['privileges'] = $arrPrivileges;
	}
	
	public function createRole($options = null){
		$ns = new Zend_Session_Namespace('info');
		$nsInfo = $ns->getIterator();
		$info = $nsInfo['group'];
		$group_name = $info['group_name'];
		$ns->acl['role'] = $group_name;
	}
	
	public function createFile($options = null){
		$ns = new Zend_Session_Namespace('info');
		$nsInfo = $ns->getIterator();
		$info = $nsInfo['member'];
		$group_id = $info['group_id'];
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		$select = $db->select()
		->from('user_files AS uf')
		->where('group_id = ?',$group_id,INTERGER);
		
		$result = $db->fetchRow($select);
		$ns->acl['media_files'] = $result;
	}
}




