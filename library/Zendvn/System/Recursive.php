<?php
class Zendvn_System_Recursive{
	
	protected $_sourceArr;
	public function __construct($sourceArr = null){
		$this->_sourceArr = $sourceArr;
	}
	
	public function builArray($parents = 0){
		$this->recursive($this->_sourceArr, $parents, 1, $resultArr);
		return $resultArr;
	}
	
	public function recursive($sourceArr,$parents = 0,$level = 1,&$resultArr){
		if(count($sourceArr)>0){
			foreach ($sourceArr as $key => $value){
				if($value['parents'] == $parents){
					$value['level'] = $level;
					$resultArr[] = $value;
					$newParents = $value['id'];
					unset($sourceArr[$key]);
					$this->recursive($sourceArr,$newParents,$level + 1,$resultArr);
				}
			}
		}
	}
}