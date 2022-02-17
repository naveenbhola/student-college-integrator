<?php

class NationalFacility{
	private $facility_id;
	private $description;
	private $additional_info;
	private $has_facility;
	private $parent_facility_name;
	private $facility_name;
	private $order;
	private $parent_id;
	private $ask_details;
	private $child_facilities;

	function __set($property,$value)
	{
		$this->$property = $value;
	}

	function getFacilityId(){
		return $this->facility_id;
	}

	function getFacilityName(){
		return $this->facility_name;
	}

	function getFacilityStatus(){
		return $this->has_facility;
	}

	function getChildFacilities(){
		return $this->child_facilities;
	}
	function getFacilityDescription()
	{
		return $this->description;
	}

	function getAdditionalInfo()
	{
		return  $this->additional_info;
	}
	function getParentFacilityName()
	{
		return $this->parent_facility_name;
	}
	function getParentFacilityId()
	{
		return $this->parent_id;
	}
}
?>