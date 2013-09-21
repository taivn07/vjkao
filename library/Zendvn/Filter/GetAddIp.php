<?php
class Zendvn_Filter_GetAddIp implements Zend_Filter_Interface{
	
	public function filter($value = null){
		$ip = $_SERVER['REMOTE_ADDR'];
		return $ip;
	}
}