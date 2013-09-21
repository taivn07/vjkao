<?php
class Default_Form_ValidateLoginPublic{
	
	//Chua nhung thong bao loi cua form
	protected $_messagesError = null;
	
	//MANG CHUA DU LIEU SAU KHI KIEM TRA
	protected $_arrData;
	
	public function __construct($arrParam = array(), $options = null){
		
		//=========================================
		//KIEM TRA user_name
		//=========================================

		$validator = new Zend_Validate();
		//$validator->setDefaultTranslator();
		
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_EmailAddress(),true);
		
		if(!$validator->isValid($arrParam['user_name'])){
			$message = $validator->getMessages();
			$this->_messagesError['user_name'] = current($message);
			$arrParam['user_name'] = '';
		}
		
		//=========================================
		//KIEM TRA password
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_StringLength(3,32),true)
					->addValidator(new Zend_Validate_Regex('#^[a-zA-Z0-9@\#\$%\^&\*\-\+]+$#'),true);
		
		if(!$validator->isValid($arrParam['password'])){
			$message = $validator->getMessages();
			$this->_messagesError['password'] = current($message);
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


