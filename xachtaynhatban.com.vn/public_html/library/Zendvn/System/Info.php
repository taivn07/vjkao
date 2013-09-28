<?php
class Zendvn_System_Info{
	
	//Ham khoi tao cua lop
	public function __construct(){
		$ns = new Zend_Session_Namespace('info');
		$ns->setExpirationSeconds(1800);
	}
	
	//Tao thong tin cua nguoi dang nhap
	public function createInfo(){
		$auth = Zend_Auth::getInstance();
		$infoAuth = $auth->getIdentity();
		$this->setMemberInfo($infoAuth);
		$this->setGroupInfo($infoAuth);
		$this->setAclInfo();
	}
	
	//Huy thong tin nguoi dung khi logout
	public function destroyInfo(){
		$ns = new Zend_Session_Namespace('info');
		$ns->unsetAll();
	}
	
	//Thiet lap Lay thong tin cua User khi login
	public function setMemberInfo($infoAuth){
		$db = Zend_Registry::get('connectDb');
		$select = $db->select()
					->from('users')
					->where('id = ?',$infoAuth->id,INTERGER);
		$result = $db->fetchRow($select);
		
		$ns = new Zend_Session_Namespace('info');
		$ns->member = $result;
	}
	
	//Thiet lap thong tin phan quyen cua nhom
	public function setAclInfo(){
		$acl = new Zendvn_System_Acl();
		$acl->createPrivilegeArray();
		$acl->createRole();
		$acl->createFile();
	}
	
	//Lay thong tin phan quyen cua nhom
	public function getAclInfo($part = null){
		$ns = new Zend_Session_Namespace('info');
		$nsInfo = $ns->getIterator();
		
		if($part == null){
			$info = $nsInfo['acl'];
		}else{
			$info = $nsInfo['acl'];
			$info = $info[$part];
		}
		
		return $info;
	}
	
	//Thiet lap Lay thong tin cua nhom chua User khi login
	Public function setGroupInfo($infoAuth){
		$db = Zend_Registry::get('connectDb');
		$select = $db->select()
		->from('user_group')
		->where('id = ?',$infoAuth->group_id,INTERGER);
		$result = $db->fetchRow($select);
		
		$ns = new Zend_Session_Namespace('info');
		$ns->group = $result;
	}
	
	//Lay thong tin cua User
	public function getMemberInfo($part = null){
		$ns = new Zend_Session_Namespace('info');
		$nsInfo = $ns->getIterator();
		
		if($part == null){
			$info = $nsInfo['member'];
		}else{
			$info = $nsInfo['member'];
			$info = $info[$part];
		}
		
		return $info;
	}
	
	//Lay thong tin cua nhom chua User khi login
	Public function getGroupInfo($part = null){
		$ns = new Zend_Session_Namespace('info');
		$nsInfo = $ns->getIterator();
		
		if($part == null){
			$info = $nsInfo['group'];
		}else{
			$info = $nsInfo['group'];
			$info = $info[$part];
		}
		
		return $info;
	}
	
	//Lay tat ca cac thong tin
	public function getInfo(){
		$ns = new Zend_Session_Namespace('info');
		$info = $ns->getIterator();
		return $info;
	}
}


