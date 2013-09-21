<?php
class Default_Form_ValidateBacklink{
	
	//Chua nhung thong bao loi cua form
	protected $_messagesError = null;
	
	//MANG CHUA DU LIEU SAU KHI KIEM TRA
	protected $_arrData;
	
	public function __construct($arrParam = array(), $options = null){
		
		//=========================================
		//KIEM TRA name
		//=========================================
		
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true);
		
		if(!$validator->isValid($arrParam['name'])){
			$message = $validator->getMessages();
			$this->_messagesError['name'] = 'Name: ' . current($message);
			$arrParam['name'] = '';
		}
		
		//=========================================
		//KIEM TRA title
		//=========================================
		
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true);
		
		if(!$validator->isValid($arrParam['title'])){
			$message = $validator->getMessages();
			$this->_messagesError['title'] = 'Title: ' . current($message);
			$arrParam['title'] = '';
		}
		
		//=========================================
		//KIEM TRA url
		//=========================================
		
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true);
		
		if(!$validator->isValid($arrParam['url'])){
			$message = $validator->getMessages();
			$this->_messagesError['url'] = 'Url: ' . current($message);
			$arrParam['url'] = '';
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


