<?php
class AdmissionContact
{
	private $admission_contact_person;
	private $admission_website_url;
	private $city;
	
	function __construct()
	{

	}
	
	public function getContactPerson()
	{
		return $this->admission_contact_person;
	}
	
	public function getWebsiteURL()
	{
		return $this->admission_website_url;
	}

	public function getCity()
	{
		return $this->city;
	}

	public function setCity(City $city)
	{
		$this->city = $city;
	}
	
	function __set($property,$value)
	{
		$this->$property = $value;
	}
}
