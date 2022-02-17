<?php
class Fees {
	private $fees_unit;
	private $fees_unit_name;
	private $fees_value;
	private $category;
	private $fees_type;
	private $period;
	private $total_includes;
	private $fees_disclaimer;

	function __set($property,$value) {
		$this->$property = $value;
	}

	function getFeesValue(){
		return $this->fees_value;
	}	

	function getFeesUnit(){
		return $this->fees_unit;
	}	

	function getFeesUnitName(){
		return $this->fees_unit_name;
	}

	function getTotalFeesIncludes(){
		return $this->total_includes;
	}

	function getFeeDisclaimer(){
		if(!empty($this->fees_disclaimer)){
			return true;
		}else{
			return false;
		}
	}

	function getCategory(){
		return $this->category;
	}
}