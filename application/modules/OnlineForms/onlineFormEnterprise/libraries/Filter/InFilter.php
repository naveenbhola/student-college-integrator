<?php
namespace onlineFormEnterprise\libraries\Filter;

class InFilter extends AbstractFilter{
	
	function __construct($filter)
    {
        parent::__construct($filter);
    }
	
	public function applyFilter($form,$fm){
		parent::applyFilter($form,$fm);
		if($this->currentVal !== ""){
			$values = explode(",",$this->value);
			$values = array_map('strtolower', array_map('trim', $values));
			if(in_array(strtolower($this->currentVal),$values)){ //Business Logic
				return $this->includeExcludeFlag;
			}else{
				return !$this->includeExcludeFlag;
			}
		}else{
			return !$this->includeExcludeFlag;
		}
	}
	
	function convertToDate(){
		
		$values = explode(",",$this->value);
		$values = array_map('trim', $values);
		$newValues = array();
		foreach($values as $value){
			$newValues[] = implode('-', array_reverse(explode('-',$value)));
		}
		$this->value = implode(",",$newValues);
	}
	
}
