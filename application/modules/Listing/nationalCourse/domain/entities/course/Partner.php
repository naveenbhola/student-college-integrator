<?php

class Partner{
	private $partner_id;
	private $partner_type;
	private $duration_value;
	private $duration_unit;
	private $scope;

	function __set($property,$value)
	{
		$this->$property = $value;
	}

    function getId(){
            return $this->partner_id;
    }

    function getType(){
            return $this->partner_type;
    }

    function getDurationValue() {
    	return $this->duration_value;
    }

    function getDurationUnit() {
        if($this->duration_value == 1){
            if (substr($this->duration_unit, -1) == 's'){
                    $this->duration_unit = substr($this->duration_unit, 0, -1);
            }
        }
    	return $this->duration_unit;
    }

    function getScope() {
    	return $this->scope;
    }

}
