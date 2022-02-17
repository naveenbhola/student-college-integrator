<?php
class UniversityAnnouncement
{
	private $announcementId;
    private $announcementText;
	private $announcementActionText;
    private $announcementStartDate;
    private $announcementEndDate;
	
	function __construct()
	{

	}
	
	public function getAnnouncementId()
	{
		return $this->announcementId;
	}
	
	public function getAnnouncementText()
	{
		return $this->announcementText;
	}
	
	public function getAnnouncementActionText()
	{
		return $this->announcementActionText;
	}

	public function getAnnouncementStartDate()
	{
		return $this->announcementStartDate;
	}
	
	public function getAnnouncementEndDate()
	{
		return $this->announcementEndDate;
	}
	
	function __set($property, $value)
	{
		$this->$property = $value;
	}
}
