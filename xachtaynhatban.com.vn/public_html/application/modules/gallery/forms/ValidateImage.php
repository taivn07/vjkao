<?php
class Gallery_Form_ValidateImage{
	
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
			$this->_messagesError['name'] = 'Tiêu đề: ' . current($message);
			$arrParam['name'] = '';
		}
		
		//=========================================
		//KIEM TRA album_id
		//=========================================
		if($arrParam['cat_id'] < 0){
			$this->_messagesError['album_id'] = 'Bạn cần chọn một album';
		}
		
		//=========================================
		//KIEM TRA multi_image
		//=========================================
		if($arrParam['action'] == 'add'){
			if($arrParam['multi_image'] == ''){
				$this->_messagesError['multi_image'] = 'Hình ảnh: Bạn cần phải chọn ít nhất 1 hình ảnh';
			}
		}else{
			if(substr_count($arrParam['picture'],'http://') == true){
				$this->_messagesError['picture'] = 'Hình ảnh: Bắt buộc phải tải lên server';
			}
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


