<?php
namespace onlineFormEnterprise\libraries\Filter;

class SimilarFilter extends AbstractFilter{
	
	function __construct($filter)
    {
        parent::__construct($filter);
    }
	
	public function applyFilter($form,$fm){
		parent::applyFilter($form,$fm);
		if($this->currentVal !== ""){
			if(strpos(strtolower($this->currentVal),strtolower($this->value)) !== false){ //Business Logic
				return $this->includeExcludeFlag;
			}else{
				return !$this->includeExcludeFlag;
			}
		}else{
			return !$this->includeExcludeFlag;
		}
	}
	
}
