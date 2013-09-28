<?php
class Default_Form_ValidatePermission{
	
	//Chua nhung thong bao loi cua form
	protected $_messagesError = null;
	
	//MANG CHUA DU LIEU SAU KHI KIEM TRA
	protected $_arrData;
	
	public function __construct($arrParam = array(), $options = null){
		
		//=========================================
		//KIEM TRA name
		//=========================================
		if($arrParam['action'] == 'add'){
			$options = array('table'=>'privileges','field'=>'name');
		}else if($arrParam['action'] == 'edit'){
			$clause = ' id !=' . $arrParam['id'];
			$options = array('table'=>'privileges','field'=>'name','exclude'=>$clause);
		}
		
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
				->addValidator(new Zend_Validate_Db_NoRecordExists($options),true);
		
		if(!$validator->isValid($arrParam['name'])){
			$message = $validator->getMessages();
			$this->_messagesError['name'] = 'Tiêu đề: ' . current($message);
			$arrParam['name'] = '';
		}
		
		//=========================================
		//KIEM TRA p_module
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true);
		
		if(!$validator->isValid($arrParam['p_module'])){
			$message = $validator->getMessages();
			$this->_messagesError['p_module'] = 'Module: ' . current($message);
			$arrParam['p_module'] = '';
		}
		
		//=========================================
		//KIEM TRA p_controller
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true);
		
		if(!$validator->isValid($arrParam['p_controller'])){
			$message = $validator->getMessages();
			$this->_messagesError['p_controller'] = 'Controller: ' . current($message);
			$arrParam['p_controller'] = '';
		}
		
		//=========================================
		//KIEM TRA p_action
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true);
		
		if(!$validator->isValid($arrParam['p_action'])){
			$message = $validator->getMessages();
			$this->_messagesError['p_action'] = 'Action: ' . current($message);
			$arrParam['p_action'] = '';
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


