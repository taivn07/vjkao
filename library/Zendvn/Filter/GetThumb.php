<?php
class Zendvn_Filter_GetThumb implements Zend_Filter_Interface{

	public function filter($value){
		if($value == ''){
			$string = '';
		}else{
			$pattern = '#\/images\/#imsU';
			$replace = '/_thumbs/images/';
			$string = preg_replace($pattern, $replace, $value);
		}
		return $string;
	}
}