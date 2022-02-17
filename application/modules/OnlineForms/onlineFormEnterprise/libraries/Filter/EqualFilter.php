<?php
namespace onlineFormEnterprise\libraries\Filter;

class EqualFilter extends AbstractFilter{
	
	function __construct($filter)
    {
        parent::__construct($filter);
    }
	
	public function applyFilter($form,$fm){
		parent::applyFilter($form,$fm);
		if($this->currentVal !== ""){
			if(strcmp($this->value,$this->currentVal) == 0){ //Business Logic
				return $this->includeExcludeFlag;
			}else{
				return !$this->includeExcludeFlag;
			}
		}else{
			return !$this->includeExcludeFlag;
		}
	}
	
}
