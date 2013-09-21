<?php
class Default_Form_ValidateMoney{
	
	//Chua nhung thong bao loi cua form
	protected $_messagesError = null;
	
	//MANG CHUA DU LIEU SAU KHI KIEM TRA
	protected $_arrData;
	
	public function __construct($arrParam = array(), $options = null){
		
		//=========================================
		//KIEM TRA code
		//=========================================
		
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true);
		
		if(!$validator->isValid($arrParam['code'])){
			$message = $validator->getMessages();
			$this->_messagesError['code'] = 'Ký hiệu: ' . current($message);
			$arrParam['code'] = '';
		}
		
		//=========================================
		//KIEM TRA currency
		//=========================================
		
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true);
		
		if(!$validator->isValid($arrParam['currency'])){
			$message = $validator->getMessages();
			$this->_messagesError['currency'] = 'Tên: ' . current($message);
			$arrParam['currency'] = '';
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


