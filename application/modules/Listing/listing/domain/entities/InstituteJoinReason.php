<?php

class InstituteJoinReason
{
	private $photo_title;
	private $photo_url;
	private $details;
	private $version;
	private $institute_id;

	function __construct()
	{

	}

	public function getPhotoTitle()
	{
		return $this->photo_title;
	}

	public function getPhotoUrl()
	{
		return $this->photo_url;
	}

	public function getDetails()
	{
		return $this->details;
	}
	
	public function getVersion() {
		return $this->version;
	}
	
	public function getId() {
		return $this->institute_id;
	}
	
	function __set($property,$value)
	{
		$this->$property = $value;
	}
}
