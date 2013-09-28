<?php
class Default_Form_ValidateGroup{
	
	//Chua nhung thong bao loi cua form
	protected $_messagesError = null;
	
	//MANG CHUA DU LIEU SAU KHI KIEM TRA
	protected $_arrData;
	
	public function __construct($arrParam = array(), $options = null){
		
		//=========================================
		//KIEM TRA group_name
		//=========================================
		if($arrParam['action'] == 'add'){
			$options = array('table'=>'user_group','field'=>'group_name');
		}else if($arrParam['action'] == 'edit'){
			$clause = ' id !=' . $arrParam['id'];
			$options = array('table'=>'user_group','field'=>'group_name','exclude'=>$clause);
		}
		
		$validator = new Zend_Validate();
		
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_StringLength(3,32),true)
					->addValidator(new Zend_Validate_Db_NoRecordExists($options),true);
		
		if(!$validator->isValid($arrParam['group_name'])){
			$message = $validator->getMessages();
			$this->_messagesError['group_name'] = 'Tên nhóm: ' . current($message);
			$arrParam['group_name'] = '';
		}
		
		//=========================================
		//KIEM TRA group_acp
		//=========================================
		if(empty($arrParam['group_acp']) || !isset($arrParam['group_acp'])){
			$arrParam['group_acp'] = 0;
		}
		
		//=========================================
		//KIEM TRA group_default
		//=========================================
		if(empty($arrParam['group_default']) || !isset($arrParam['group_default'])){
			$arrParam['group_default'] = 0;
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


