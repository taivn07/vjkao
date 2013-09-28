<?php
class Default_Form_ValidateUserPublic{
	
	//Chua nhung thong bao loi cua form
	protected $_messagesError = null;
	
	//MANG CHUA DU LIEU SAU KHI KIEM TRA
	protected $_arrData;
	
	public function __construct($arrParam = array(), $options = null){
		
		$info 					= new Zendvn_System_Info();
		$admin_info 			= $info->getMemberInfo();
		$encode = new Zendvn_Encode();
		
		$language = Zend_Registry::get('language');
		$language = $language['language'];
		
		//=========================================
		//KIEM TRA user_name
		//=========================================
		if($arrParam['action'] == 'registry'){
			$options = array('table'=>'users','field'=>'user_name');
		}else if($arrParam['action'] == 'edit'){
			$clause = ' id !=' . $arrParam['id'];
			$options = array('table'=>'users','field'=>'user_name','exclude'=>$clause);
		}
		
		$validator = new Zend_Validate();
		
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_StringLength(6,32),true)
					->addValidator(new Zend_Validate_Regex('#^[a-zA-Z0-9]+$#'),true)
					->addValidator(new Zend_Validate_Db_NoRecordExists($options),true);
		
		if(!$validator->isValid($arrParam['user_name'])){
			$message = $validator->getMessages();
			$this->_messagesError['user_name'] = 'Tài khoản: ' . current($message);
			$arrParam['user_name'] = '';
		}
		
		//=========================================
		//KIEM TRA user_avatar
		//=========================================
		/* $upload = new Zend_File_Transfer_Adapter_Http();
		$fileInfo = $upload->getFileInfo('user_avatar');
		$fileName = $fileInfo['user_avatar']['name'];
		if(!empty($fileName)){
			//echo 'co file dc upload';
			$upload->addValidator('Extension',true,array('jpg','gif','png'),'user_avatar');
			$upload->addValidator('Size',true,array('min'=>'2KB','max'=>'1000KB'),'user_avatar');
			if(!$upload->isValid('user_avatar')){
				$message = $upload->getMessages();
				$this->_messagesError['user_avatar'] = 'Avatar: ' . current($message);
			}
		}else if(!empty($arrParam['current_user_avatar'])){
			$arrParam['user_avatar'] = $arrParam['current_user_avatar'];
		} */
		
		//=========================================
		//KIEM TRA password
		//=========================================
		$flag = false;
		if($arrParam['action'] == 'registry'){
			$flag = true;
		}else if($arrParam['action'] == 'edit'){
			if(!empty($arrParam['password'])){
				$flag = true;
			}
		}
		
		if($flag == true){
			$validator = new Zend_Validate();
			$validator->addValidator(new Zend_Validate_NotEmpty(),true)
						->addValidator(new Zend_Validate_StringLength(6,32),true)
						->addValidator(new Zend_Validate_Regex('#^[a-zA-Z0-9@\#\$%\^&\*\-\+]+$#'),true);
			if(!$validator->isValid($arrParam['password'])){
				$message = $validator->getMessages();
				$this->_messagesError['password'] = 'Mật khẩu: ' . current($message);
			}
		}
		
		//=========================================
		// Kiểm tra mật khẩu mới nhập lại
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
		->addValidator(new Zend_Validate_StringLength(6,32),true);
		
		if(!$validator->isValid($arrParam['password_confirm'])){
			$message = $validator->getMessages();
			$this->_messagesError['password_confirm'] = 'Nhập lại mật khẩu: ' . current($message);
			$arrParam['password_confirm'] = '';
		}else if($encode->password($arrParam['password']) != $encode->password($arrParam['password_confirm'])){
			$this->_messagesError['password_confirm'] = 'Nhập lại mật khẩu không chính xác';
			$arrParam['password_confirm'] = '';
		}
		
		//=========================================
		//KIEM TRA email
		//=========================================
		if($arrParam['action'] == 'registry'){
			$options = array('table'=>'users','field'=>'email');
		}else if($arrParam['action'] == 'edit'){
			$clause = ' id !=' . $arrParam['id'];
			$options = array('table'=>'users','field'=>'email','exclude'=>$clause);
		}
		
		$validator = new Zend_Validate();
		
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_EmailAddress(),true)
					->addValidator(new Zend_Validate_Db_NoRecordExists($options),true);
		if(!$validator->isValid($arrParam['email'])){
			$message = $validator->getMessages();
			$this->_messagesError['email'] = 'Email: ' . current($message);
			$arrParam['email'] = '';
		}
		
		//=========================================
		//KIEM TRA first_name
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_StringLength(2),true);
		if(!$validator->isValid($arrParam['first_name'])){
			$message = $validator->getMessages();
			$this->_messagesError['first_name'] = 'Tên: ' . current($message);
			$arrParam['first_name'] = '';
		}
		
		//=========================================
		//KIEM TRA first_name
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true)
					->addValidator(new Zend_Validate_StringLength(2),true);
		if(!$validator->isValid($arrParam['last_name'])){
			$message = $validator->getMessages();
			$this->_messagesError['last_name'] = 'Họ đệm: ' . current($message);
			$arrParam['last_name'] = '';
		}
		
		//=========================================
		//KIEM TRA birthday
		//=========================================
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true);
		if(!$validator->isValid($arrParam['date'])){
			$message = $validator->getMessages();
			$this->_messagesError['date'] = 'Ngày sinh: ' . current($message);
			$arrParam['date'] = '';
		}
		
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true);
		if(!$validator->isValid($arrParam['month'])){
			$message = $validator->getMessages();
			$this->_messagesError['month'] = 'Tháng sinh: ' . current($message);
			$arrParam['month'] = '';
		}
		
		$validator = new Zend_Validate();
		$validator->addValidator(new Zend_Validate_NotEmpty(),true);
		if(!$validator->isValid($arrParam['year'])){
			$message = $validator->getMessages();
			$this->_messagesError['year'] = 'Năm sinh: ' . current($message);
			$arrParam['year'] = '';
		}
		
		//=========================================
		//KIEM TRA Captcha
		//=========================================
		$captchaID = $arrParam['captchaID'];
		$valueCaptcha = $arrParam['captcha'];
		$vadilator = new Zendvn_Validate_Captcha($captchaID);
		if(!$vadilator->isValid($valueCaptcha)){
			$message = $vadilator->getMessages();
			$this->_messagesError['captcha'] = $language['captcha'] . ': ' . current($message);
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
		if($options['upload'] == true){
			$this->_arrData['user_avatar'] = $this->uploadFile();
		}
		return $this->_arrData;
	}
	
	//=========================================
	// 1.Upload user_avatar
	// 2.Resize kich thuoc (100x100 va 450x450)
	// 3.Tra ve ten tap tin upload
	//=========================================
	public function uploadFile(){
		//Duong dan den thu muc upload
		$upload_dir = FILE_PATH . '/users/';
		
		//=========================================
		//UPLOAD FILE user_avatar
		//=========================================
		$upload = new Zendvn_File_Upload();
		$fileInfo = $upload->getFileInfo('user_avatar');
		$fileName = $fileInfo['user_avatar']['name'];
		if(!empty($fileName)){
			$fileName = $upload->upload('user_avatar', $upload_dir . '/orignal',array('task'=>'rename'),'user_');
			
			$thumb = Zendvn_File_Images::create($upload_dir . '/orignal/' . $fileName);
			$thumb->resize(100,100)->save($upload_dir . '/img100x100/' . $fileName);
			
			$thumb = Zendvn_File_Images::create($upload_dir . '/orignal/' . $fileName);
			$thumb->resize(450,450)->save($upload_dir . '/img450x450/' . $fileName);
			
			if($this->_arrData['action'] == 'edit'){
				$upload->removeFile($upload_dir . '/orignal/' . $this->_arrData['current_user_avatar']);
				$upload->removeFile($upload_dir . '/img100x100/' . $this->_arrData['current_user_avatar']);
				$upload->removeFile($upload_dir . '/img450x450/' . $this->_arrData['current_user_avatar']);
			}
		}else{
			if($this->_arrData['action'] == 'edit'){
				$fileName = $this->_arrData['current_user_avatar'];
			}
		}
		
		return $fileName;
	}
}


