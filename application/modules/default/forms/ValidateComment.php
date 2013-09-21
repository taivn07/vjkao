<?php
class Default_Form_ValidateComment{
	
	//Chua nhung thong bao loi cua form
	protected $_messagesError = null;
	
	//MANG CHUA DU LIEU SAU KHI KIEM TRA
	protected $_arrData;
	
	public function __construct($arrParam = array(), $options = null){
		
		//=========================================
		//KIEM TRA txtName
		//=========================================
		$validator = new Zend_Validate();
		
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_StringLength(3,32),true);
		
		if(!$validator->isValid($arrParam['txtName'])){
			$message = $validator->getMessages();
			$this->_messagesError['txtName'] = current($message);
		}
		
		//=========================================
		//KIEM TRA txtEmail
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_EmailAddress(),true);
			
		if(!$validator->isValid($arrParam['txtEmail'])){
			$message = $validator->getMessages();
			$this->_messagesError['txtEmail'] = current($message);
		}
		
		//=========================================
		//KIEM TRA content
		//=========================================
		$validator = new Zend_Validate();
		
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
		->addValidator(new Zend_Validate_StringLength(10,1000),true);
		
		if(!$validator->isValid($arrParam['txtContent'])){
			$message = $validator->getMessages();
			$this->_messagesError['txtContent'] = current($message);
		}
		
		//=========================================
		//KIEM TRA Captcha
		//=========================================
		$captchaID = $arrParam['captchaID'];
		$valueCaptcha = $arrParam['captcha'];
		$vadilator = new Zendvn_Validate_Captcha($captchaID);
		if(!$vadilator->isValid($valueCaptcha)){
			$message = $vadilator->getMessages();
			$this->_messagesError['captcha'] = current($message);
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


