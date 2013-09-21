<?php
class Zendvn_Filter_RewriteUrl implements Zend_Filter_Interface{
	
	public function filter($value){

		$filter = new Zend_Filter();
		$filter->addFilter(new Zend_Filter_StringToLower(array('encoding'=>'UTF-8')))
				->addFilter(new Zendvn_Filter_RemoveCircumflex())
				->addFilter(new Zend_Filter_Alnum(true))
				->addFilter(new Zend_Filter_StringTrim())
				->addFilter(new Zend_Filter_PregReplace(array('match'=>'#\s+#','replace'=>'-')))
				->addFilter(new Zend_Filter_Word_SeparatorToDash());
		
		$result = $filter->filter($value);
		
		return $result;
	}
}