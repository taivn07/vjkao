<?php
class Default_Form_ValidateRegistry{
	
	//Chua nhung thong bao loi cua form
	protected $_messagesError = null;
	
	//MANG CHUA DU LIEU SAU KHI KIEM TRA
	protected $_arrData;
	
	public function __construct($arrParam = array(), $options = null){
		
		if($options == null){
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
						->addValidator(new Zend_Validate_EmailAddress(),true)
						->addValidator(new Zend_Validate_Db_NoRecordExists($options),true);
			
			if(!$validator->isValid($arrParam['user_name'])){
				$message = $validator->getMessages();
				$this->_messagesError['user_name'] = current($message);
			}
			
			//=========================================
			//KIEM TRA password
			//=========================================
			if($arrParam['action'] == 'registry'){
				$validator = new Zend_Validate();
				$validator->addValidator(new Zend_Validate_NotEmpty(),true)
							->addValidator(new Zend_Validate_StringLength(6,32),true)
							->addValidator(new Zend_Validate_Regex('#^[a-zA-Z0-9@\#\$%\^&\*\-\+]+$#'),true);
				if(!$validator->isValid($arrParam['password'])){
					$message = $validator->getMessages();
					$this->_messagesError['password'] = current($message);
				}
			}
			
			//=========================================
			// Kiểm tra mật khẩu mới nhập lại
			//=========================================
			if($arrParam['action'] == 'registry'){
				$validator = new Zend_Validate();
				$validator->addValidator(new Zend_Validate_NotEmpty(),true)
				->addValidator(new Zend_Validate_StringLength(6,32),true);
				
				if(!$validator->isValid($arrParam['password_confirm'])){
					$message = $validator->getMessages();
					$this->_messagesError['password_confirm'] = current($message);
					$arrParam['password_confirm'] = '';
				}else if($encode->password($arrParam['password']) != $encode->password($arrParam['password_confirm'])){
					$this->_messagesError['password_confirm'] = 'Nhập lại mật khẩu không chính xác';
				}
			}
			
			//=========================================
			//KIEM TRA member_hoten
			//=========================================
			$validator = new Zend_Validate();
			$validator->addValidator(new Zend_Validate_NotEmpty(),true)
						->addValidator(new Zend_Validate_StringLength(2,40),true);
			if(!$validator->isValid($arrParam['member_hoten'])){
				$message = $validator->getMessages();
				$this->_messagesError['member_hoten'] = current($message);
			}
			
			//=========================================
			//KIEM TRA user_avatar
			//=========================================
			if($arrParam['action'] == 'edit'){
				$upload = new Zend_File_Transfer_Adapter_Http();
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
				}
			}
			
			//=========================================
			//KIEM TRA Captcha
			//=========================================
			if($arrParam['action'] == 'registry'){
				if($arrParam['captcha'] == ''){
					$this->_messagesError['captcha'] = 'Giá trị này không được để trống';
				}else{
					$captchaID = $arrParam['captchaID'];
					$valueCaptcha = $arrParam['captcha'];
					$vadilator = new Zendvn_Validate_Captcha($captchaID);
					if(!$vadilator->isValid($valueCaptcha)){
						$message = $vadilator->getMessages();
						$this->_messagesError['captcha'] = current($message);
					}
				}
			}
			
			//=========================================
			//KIEM TRA chapnhansudung
			//=========================================
			if($arrParam['action'] == 'registry'){
				if(empty($arrParam['chap_nhan_su_dung'])){
					$this->_messagesError['chap_nhan_su_dung'] = 'Bạn chưa xác nhận';
				}
			}
		}
		
		if($options['task'] == password){
			$info 					= new Zendvn_System_Info();
			$admin_info 			= $info->getMemberInfo();
			$encode = new Zendvn_Encode();
			
			//=========================================
			// Kiểm tra mật khẩu hiện tại
			//=========================================
			$validator = new Zend_Validate();
			$validator->addValidator(new Zend_Validate_NotEmpty(),true);
			
			if(!$validator->isValid($arrParam['password_old'])){
				$message = $validator->getMessages();
				$this->_messagesError['password_old'] = current($message);
				$arrParam['password_old'] = '';
			}else if($encode->password($arrParam['password_old']) != $admin_info['password']){
				$this->_messagesError['password_old'] = 'Mật khẩu cũ bạn nhập không chính xác';
				$arrParam['password_old'] = '';
			}
			
			//=========================================
			//KIEM TRA password
			//=========================================
			$validator = new Zend_Validate();
			$validator->addValidator(new Zend_Validate_NotEmpty(),true)
						->addValidator(new Zend_Validate_StringLength(6,32),true)
						->addValidator(new Zend_Validate_Regex('#^[a-zA-Z0-9@\#\$%\^&\*\-\+]+$#'),true);
			if(!$validator->isValid($arrParam['password'])){
				$message = $validator->getMessages();
				$this->_messagesError['password'] = current($message);
			}
			
			//=========================================
			// Kiểm tra mật khẩu mới nhập lại
			//=========================================
			$validator = new Zend_Validate();
			$validator->addValidator(new Zend_Validate_NotEmpty(),true)
				->addValidator(new Zend_Validate_StringLength(6,32),true);
			
			if(!$validator->isValid($arrParam['password_confirm'])){
				$message = $validator->getMessages();
				$this->_messagesError['password_confirm'] = current($message);
				$arrParam['password_confirm'] = '';
			}else if($encode->password($arrParam['password']) != $encode->password($arrParam['password_confirm'])){
				$this->_messagesError['password_confirm'] = 'Nhập lại mật khẩu không chính xác';
				$arrParam['password_confirm'] = '';
			}
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


