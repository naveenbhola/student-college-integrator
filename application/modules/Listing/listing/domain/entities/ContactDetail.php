<?php
class ContactDetail {

	private $contact_details_id;
	private $contact_person;
	private $contact_email;
	private $contact_main_phone;
	private $contact_cell;
	private $contact_alternate_phone;
	private $contact_fax;
	private $website;
	private $institute_location_id;

	function __construct()
	{

	}
	
	public function getContactId()
	{
		return $this->contact_details_id;
	}
	
	public function getContactPerson()
	{
		return $this->contact_person;
	}

	public function getContactEmail()
	{
		return $this->contact_email;
	}

	public function getContactMainPhone()
	{
		return $this->contact_main_phone;
	}

	public function getContactCell() {
		return $this->contact_cell;
	}

	public function getContactAltPhone() {
		return $this->contact_alternate_phone;
	}

	public function getContactFax() {
		return $this->contact_fax;
	}

	public function getContactWebsite() {
		return $this->website;
	}
	
	public function getContactNumbers(){
		return implode(", ",array_filter(array($this->contact_main_phone,$this->contact_cell,$this->contact_alternate_phone)));
	}
	public function getLocationId() {
		return $this->institute_location_id;
	}
	
	function __set($property,$value)
	{
		$this->$property = $value;
	}
}
