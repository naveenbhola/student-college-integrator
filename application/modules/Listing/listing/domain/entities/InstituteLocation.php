<?php

class InstituteLocation
{
	private $institute_location_id;

	private $address_1;
	private $address_2;
	private $address_3;

	private $pincode;
	private $customLocalityName;

	private $locality;
	private $zone;
	private $city;
	private $state;
	private $country;
        private $region;
	private $contact_detail;
	private $attributes;
	private $is_flagship_course_location;

	function __construct()
	{

	}
	
	function addAttribute(CourseLocationAttribute $attribute) {
		$this->attributes[$attribute->getName()] = $attribute;
	}
	
	function isHeadOffice() {
		if(array_key_exists('Head Office', $this->attributes) && strtolower($this->attributes['Head Office']->getValue()) == "true") {
			return true;
		} else {
			return false;	
		}
	}
	
	function getLocationId(){

		return $this->institute_location_id;
	}
	
	
	function getAddress() {

		return implode(", ",array_filter(array($this->address_1,$this->address_2,$this->address_3,($this->locality?$this->locality->getName():""),$this->city->getName(),$this->state->getName(),$this->country->getName().$this->getPincode()),'strlen'));
	}
	
	function getAddress1() {

		return $this->address_1;
	}
	
	function getAddress2() {

		return $this->address_2;
	}
	
	function getAddress3() {

		return $this->address_3;
	}
	
	function getPincode() {

		return ($this->pincode?("- ".$this->pincode):"");
	}

	function getCustomLocalityName() {

		return $this->customLocalityName;
	}
	
	function getContactDetail() {

		return $this->contact_detail;
	}
	
	function getLocality()
	{
		return $this->locality;
	}

	function getZone()
	{
		return $this->zone;
	}

	function getCity()
	{
		return $this->city;
	}

	function getState()
	{
		return $this->state;
	}

	function getCountry()
	{
		return $this->country;
	}

	function getRegion()
	{
		return $this->region;
	}

	function setLocality(Locality $locality)
	{
		$this->locality = $locality;
	}

	function setZone(Zone $zone)
	{
		$this->zone = $zone;
	}

	function setCity(City $city)
	{
		$this->city = $city;
	}

	function setState(State $state)
	{
		$this->state = $state;
	}

	function setCountry(Country $country)
	{
		$this->country = $country;
	}

	function setContactDetail(ContactDetail $conatct) {
		$this->contact_detail = $conatct;
	}

	function getAttributes() {

		return $this->attributes;
	}
	
	function isFlagshipCourseLocation() {
		if($this->is_flagship_course_location == 'YES') {
			return true;
		}
		return false;
	}
	 
	function __set($property,$value)
	{
		$this->$property = $value;
	}
}
