<?php

class UniversityLocation
{
	private $university_location_id;
	private $address;
	private $state;
	private $city;
	private $country;
	private $region;
        
	function __construct()
	{

	}
	
	public function getLocationId()
	{
		return $this->university_location_id;
	}
	
	public function getAddress()
	{
		return $this->address;
	}
	
	public function getCity()
	{
		return $this->city;
	}

	public function getCountry()
	{
		return $this->country;
	}

	public function setCity(City $city)
	{
		$this->city = $city;
	}

	public function setCountry(Country $country)
	{
		$this->country = $country;
	}
	
	public function getRegion()
	{
		return $this->region;
	}
	 
	public function getState()
	{
		return $this->state;
	}
	
	public function setState(State $state)
	{
		return $this->state = $state;
	}
	
	function __set($property,$value)
	{
		$this->$property = $value;
	}
	
	public function cleanForCategoryPage(){
		unset($this->address);
		//unset($this->state);
		unset($this->region);
	}
}
