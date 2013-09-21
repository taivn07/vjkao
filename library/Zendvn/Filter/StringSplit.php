<?php
class Zendvn_Filter_StringSplit implements Zend_Filter_Interface{
	
	protected $_num;
	protected $_result = array();
	
	public function __construct($num){
		$this->_num	= $num;
	}
	
	public function filter($value){
		if (!$this->_num)  $this->_num=1;
		$arr=array();
		$x=floor(strlen($value)/$this->_num);
		$i=0;
		$j=0;
		while ($i <= $x)
		{
			$y = substr($value,$j,$this->_num);
			echo '<br>' . $y;
			if ($y) {
				$this->_result[] = $y;
				//array_push($this->_result,$y);
			}
			$i++;
			$j = $j + $this->_num;
		}
		return $this->_result;
	}
}