<?php
class Zendvn_Captcha_Image extends Zend_Captcha_Image{
	
	public function __construct(){
		
		//2. Thiet lap duong dan den thu muc chua hinh anh se duoc tao
		$this->setImgDir(CAPTCHA_PATH . '/img');		
		//3. Thiet lap duong dan Url den thu muc chua hinh anh
		$this->setImgUrl(CAPTCHA_URL . '/img');		
		//4. Thiet lap chieu dai chuoi hien thi trong hinh
		$this->setWordlen(6);		
		//5. Thiet lap duong dan den thu muc chua font
		$this->setFont(CAPTCHA_PATH . '/font/utm-helvetins.ttf');		
		//6. Thiet lap kich co font
		$this->setFontSize(30);		
		//7. Thiet lap kich thuoc hinh anh
		$this->setWidth(250);
		$this->setHeight(70);
		$this->setTimeout(100);		
		//8. Tao ra Captcha
		$this->generate();
		
		$thisSession = new Zend_Session_Namespace('Zend_Form_Captcha_' . $this->getId());
		$thisSession->word = $this->getWord();
		
	}
	
	public function removeImg($captcha_id){
		$filename = CAPTCHA_PATH . '/img/' . $captcha_id . $this->getSuffix();
		@unlink($filename);
	}
}