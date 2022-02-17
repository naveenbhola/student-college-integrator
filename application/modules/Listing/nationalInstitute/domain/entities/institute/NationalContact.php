<?php

class NationalContact{

	private $listing_location_id;
	private $website_url;
	private $address;
	private $latitude;
	private $longitude;
	private $admission_contact_number;
	private $admission_email;
	private $generic_contact_number;
	private $generic_email;
	private $google_url;
	private $actual_listing_location_id;

	function __set($property,$value)
	{
		$this->$property = $value;
	}
	
	public function getGenericContactNumber() {
		return $this->generic_contact_number;
	}

	public function getGenericEmail() {
		return $this->generic_email;
	}

	public function setListingLocationId($listing_location_id){
		$this->listing_location_id = $listing_location_id;
	}

	public function getListingLocationId(){
		return $this->listing_location_id;
	}

	public function getWebsiteUrl() {
		return $this->website_url;
	}

	public function getAddress() {
		return $this->address;
	}

	public function getAdmissionContactNumber() {
		return $this->admission_contact_number;
	}

	public function getAdmissionEmail() {
		return $this->admission_email;
	}

	public function getLatitude(){
		return $this->latitude;
	}

	public function getLongitude(){
		return $this->longitude;
	}
	public function getGoogleStaticMap()
	{
		$this->google_url =  addingDomainNameToUrl(array('url' => $this->google_url , 'domainName' =>MEDIA_SERVER));
		return $this->google_url;
	}
	public function setActualListingLocationId($actual_listing_location_id)
	{
		$this->actual_listing_location_id = $actual_listing_location_id; 
	}
	public function getActualListingLocationId()
	{
		return $this->actual_listing_location_id;
	}
}
?>