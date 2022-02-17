<?php

class NationalMedia{

	private $media_id;
	private $media_type;
	private $media_url;
	private $media_title;
	private $media_order;
	private $listing_location_ids;
	private $tag_ids;
	private $media_thumb_url;
	private $uploaded_date;

	function getType(){
		return $this->media_type;
	}

	function getUrl(){
		$this->media_url =  addingDomainNameToUrl(array('url' => $this->media_url , 'domainName' =>MEDIA_SERVER));
		return $this->media_url;
	}
	
	function __set($property,$value)
	{
		$this->$property = $value;
	}
	function getId() 
	{
		return $this->media_id;
	}
	function getTitle()
	{
		return $this->media_title;
	}
	function getTags()
	{
		return $this->tag_ids;
	}
	function getLocationIds()
	{
		return $this->listing_location_ids;
	}
	function isMappedToAllLocation()
	{
		return $this->listing_location_ids[0] == 0 ? true : false;
	}
	function getThumbUrl()
	{
		$this->media_thumb_url =  addingDomainNameToUrl(array('url' => $this->media_thumb_url , 'domainName' =>MEDIA_SERVER));
		return $this->media_thumb_url;
	}
	function getUploadedDate(){
		return $this->uploaded_date;
	}
	
}
?>