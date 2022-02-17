<?php

class NationalLocation
{
	private $listing_location_id;
	private $state_id;
	private $city_id;
	private $locality_id;
	private $state_name;
	private $city_name;
	private $locality_name;
	private $is_main;
	private $contact_details;
	private $fees;

	function getStateId(){
		return $this->state_id;
	}

	function getLocalityId(){
		return $this->locality_id;
	}

	function getLocationId(){
		return $this->listing_location_id;
	}

	function getCityId(){
		return $this->city_id;
	}



	function getLocalityName(){
		return $this->locality_name;
	}	

	function isMainLocation(){
		if($this->is_main == 1){
			return true;
		}
		return false;
	}

	function __set($property,$value)
	{
		$this->$property = $value;
	}

	/**
	 * @return The state name
	 */
	public function getStateName()
	{
		return $this->state_name;
	}

	/**
	 * @return The city name
	 */
	public function getCityName()
	{
		return $this->city_name;
	}

	public function getFees(){
		return $this->fees;
	}

	
	function addFees($fees){
		$this->fees = $fees;
	}

	function addContactDetails($contact_details){
		$this->contact_details = $contact_details;
	}

	function getContactDetail(){
		return $this->contact_details;
	}

	function isHeadOffice(){
		return $this->is_main ? true : false;
	}

	function getPropertyObject($property) {
		switch ($property) {
			case 'contact_details':
				return new NationalContact();
		}
	}
}
