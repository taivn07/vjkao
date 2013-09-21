<?php
class Shopping_Form_ValidateOrder{
	
	//Chua nhung thong bao loi cua form
	protected $_messagesError = null;
	
	//MANG CHUA DU LIEU SAU KHI KIEM TRA
	protected $_arrData;
	
	public function __construct($arrParam = array(), $options = null){
		
		$language = Zend_Registry::get('language');
		$language = $language['language'];
		
		//=========================================
		//KIEM TRA name
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_StringLength(6,32),true);
		
		if(!$validator->isValid($arrParam['full_name'])){
			$message = $validator->getMessages();
			$this->_messagesError['full_name'] = current($message);
		}
		
		//=========================================
		//KIEM TRA địa chỉ
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_StringLength(2,300),true);
		
		if(!$validator->isValid($arrParam['address'])){
			$message = $validator->getMessages();
			$this->_messagesError['address'] = current($message);
		}
		
		//=========================================
		//KIEM TRA shipping
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_StringLength(2,300),true);
		
		if(!$validator->isValid($arrParam['shipping'])){
			$message = $validator->getMessages();
			$this->_messagesError['shipping'] = current($message);
		}
		
		//=========================================
		//KIEM TRA phone
		//=========================================
		$validator = new Zend_Validate();
		$pattern = '#^[0-9\.\s]+$#';
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
		->addValidator(new Zend_Validate_StringLength(10,15),true)
		->addValidator(new Zend_Validate_Regex($pattern),true);
		
		if(!$validator->isValid($arrParam['phone'])){
			$message = $validator->getMessages();
			$this->_messagesError['phone'] = current($message);
		}
		
		//=========================================
		//KIEM TRA email
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_EmailAddress(),true);
		
		if(!$validator->isValid($arrParam['email'])){
			$message = $validator->getMessages();
			$this->_messagesError['email'] = current($message);
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


