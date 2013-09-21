<?php
class Faqs_Form_ValidateFaqs{
	
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
					->addValidator(new Zend_Validate_StringLength(2,40),true);
		
		if(!$validator->isValid($arrParam['name'])){
			$message = $validator->getMessages();
			$this->_messagesError['name'] = current($message);
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
		//KIEM TRA title
		//=========================================
		
		$validator = new Zend_Validate();
		
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_StringLength(5,200),true);
		
		if(!$validator->isValid($arrParam['title'])){
			$message = $validator->getMessages();
			$this->_messagesError['title'] = current($message);
		}
		
		//=========================================
		//KIEM TRA content
		//=========================================
		
		$validator = new Zend_Validate();
		
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_StringLength(5,1000),true);
		
		if(!$validator->isValid($arrParam['content'])){
			$message = $validator->getMessages();
			$this->_messagesError['content'] = current($message);
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
		//KIEM TRA status
		//=========================================
		if(empty($arrParam['status']) || !isset($arrParam['status'])){
			$arrParam['status'] = 0;
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


