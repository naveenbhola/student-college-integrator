<?php
namespace onlineFormEnterprise\libraries\Filter;

class LessThanFilter extends AbstractFilter{
	
	function __construct($filter)
    {
        parent::__construct($filter);
    }
	
	public function applyFilter($form,$fm){
		parent::applyFilter($form,$fm);
		if($this->currentVal !== ""){
			if($this->currentVal < $this->value){ //Business Logic
				return $this->includeExcludeFlag;
			}else{
				return !$this->includeExcludeFlag;
			}
		}else{
			return !$this->includeExcludeFlag;
		}
	}
	
}
