<?php
class Default_Form_ValidatePassword{
	
	//Chua nhung thong bao loi cua form
	protected $_messagesError = null;
	
	//MANG CHUA DU LIEU SAU KHI KIEM TRA
	protected $_arrData;
	
	public function __construct($arrParam = array(), $options = null){
		
		$info 					= new Zendvn_System_Info();
		$admin_info 			= $info->getMemberInfo();
		$encode = new Zendvn_Encode();
		
		//=========================================
		// Kiểm tra mật khẩu hiện tại
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true);
		
		if(!$validator->isValid($arrParam['password'])){
			$message = $validator->getMessages();
			$this->_messagesError['password'] = 'Mật khẩu hiện tại: ' . current($message);
			$arrParam['password'] = '';
		}else if($encode->password($arrParam['password']) != $admin_info['password']){
			$this->_messagesError['password'] = 'Mật khẩu hiện tại bạn nhập không chính xác';
			$arrParam['password'] = '';
		}
		
		//=========================================
		// Kiểm tra mật khẩu mới
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
				->addValidator(new Zend_Validate_StringLength(6,32),true);
		
		if(!$validator->isValid($arrParam['password_new'])){
			$message = $validator->getMessages();
			$this->_messagesError['password_new'] = 'Mật khẩu mới: ' . current($message);
			$arrParam['password_new'] = '';
		}
		
		//=========================================
		// Kiểm tra mật khẩu mới nhập lại
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
		->addValidator(new Zend_Validate_StringLength(6,32),true);
		
		if(!$validator->isValid($arrParam['password_confirm'])){
			$message = $validator->getMessages();
			$this->_messagesError['password_confirm'] = 'Nhập lại mật khẩu mới: ' . current($message);
			$arrParam['password_confirm'] = '';
		}else if($encode->password($arrParam['password_new']) != $encode->password($arrParam['password_confirm'])){
			$this->_messagesError['password_confirm'] = 'Nhập lại mật khẩu mới không chính xác';
			$arrParam['password_confirm'] = '';
		}
		
		//=========================================
		//TRUYEN CAC GIA TRI DUNG VAO MANG $_arrData
		//=========================================
		$this->_arrData = $arrParam;
		
	}
	
	//Kiem tra Error
	//return true neu co loi xuat hien
	public function isError(){
		if(count($this->_messagesError) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	//Tra ve mot mang cac thong bao loi
	public function getMessageError(){
		return $this->_messagesError;
	}
	
	//Tra ve mot du lieu sau khi kiem tra
	public function getData($options = null){
		return $this->_arrData;
	}
	
}


