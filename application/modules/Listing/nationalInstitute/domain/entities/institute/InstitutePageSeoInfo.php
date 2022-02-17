<?php
class InstitutePageSeoInfo{

	private $listing_id;
	private $description_type;
	private $page_h1;
	private $page_title;
	private $page_description;

	function __set($property,$value)
	{
		$this->$property = $value;
	}

	function getListingId(){
		return $this->listing_id;
	}

	function getDescriptionType(){
		return $this->description_type;
	}

	function getPageHeading(){
		return $this->page_h1;
	}

	function getTitle(){
		return $this->page_title;
	}

	function getDescription(){
		return $this->page_description;
	}

}
?>