<?php
namespace onlineFormEnterprise\libraries\Filter;

abstract class AbstractFilter{
	protected $field;
	protected $value;
	protected $includeExcludeFlag;
	protected $currentVal;
	protected $converted;
	
	function __construct($filter){
        
		$this->field = $filter['field'];
		$this->value = trim($filter['value']);
		if($filter['includeExcludeFlag'] == "true"){
			$this->includeExcludeFlag = true;
		}else{
			$this->includeExcludeFlag = false;
		}
		
    }
	
	public function applyFilter($form,$fm){
		$this->currentVal = trim($fm->getFieldValue($form,$this->field));
		if($fm->getFieldType($form,$this->field) == "date" && !$this->converted){
			$this->convertToDate();
			$this->converted = 1;
		}
	}
	
	public function getValue(){
		return $this->value;
	}
	
	function convertToDate(){
		$arr = explode('-', $this->value);
		$this->value = $arr[2].'-'.$arr[1].'-'.$arr[0];
	}
}
