<?php
class Zendvn_Validate_Phone extends Zend_Validate_Abstract{
	
	const INVALID = 'inValid';
	
	/**
	 * @var array
	 */
	protected $_messageTemplates = array(
			self::INVALID => "Khong phai la so phone theo yeu cau",
	);
	
	/* public function __construct($password){
		$this->_password = $password;
	} */
	
	public function isValid($value){
		$pattern = '#^084-[0-9]{2}-[0-9]{2}\.[0-9]{6}$#';
		if(preg_match($pattern, $value) != 1){
			$this->_error('inValid');
			return false;
		}
		return true;
	}
	
}