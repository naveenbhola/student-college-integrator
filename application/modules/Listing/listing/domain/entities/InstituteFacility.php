<?php 
class InstituteFacility {
	private $facility_id;
	private $facilityName;
	private $facilityDescription;
	private $orderId;

	function __construct(){

	}

	function getFacilityId(){
		return $this->facility_id;
	}

	function getFacilityName(){
		return $this->facilityName;
	}

	function getFacilityDescription(){
		//make first letter capital of each word
		$facilityDescArr = explode(' ', $this->facilityDescription);
		$facilityDescArr[0] = ucfirst($facilityDescArr[0]);
		$this->facilityDescription = implode(' ', $facilityDescArr);
		
		return $this->facilityDescription;
	}

	function getOrderId(){
		return $this->orderId;
	}

	function __set($property,$value)
	{
		$this->$property = $value;
	}
}
?>