<?php
class Zendvn_Validate_confirmPassword extends Zend_Validate_Abstract{
	
	const NOT_EQUAL = 'notEqual';
	
	protected $_password;
	
	/**
	 * @var array
	 */
	protected $_messageTemplates = array(
			self::NOT_EQUAL => "password is not confirm password",
	);
	
	public function __construct($password){
		$this->_password = $password;
	}
	
	public function isValid($value){
		if(strcmp($value, $this->_password) != 0){
			$this->_error('notEqual');
			return false;
		}
		return true;
	}
	
}