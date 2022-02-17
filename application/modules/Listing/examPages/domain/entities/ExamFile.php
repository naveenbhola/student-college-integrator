<?php

/*
 * Generic entity that stores date type of information in examPagesCMS form
 */
class ExamFile
{
	private $id;
    private $page_id;
    private $file_name;
    private $file_url;
    private $thumbnail_url;
	private $file_order;
	private $file_type;
	private $status;
	private $creationTime;
	private $updationTime;
	private $updatedOn;
    
    function __construct() {
        
    }
	
	function __set($property,$value) {
        $this->$property = $value;
    }

    public function getId() {
		return $this->id;
	}

	public function getPageId() {
		return $this->page_id;
	}
    
	public function getFileName() {
		return $this->file_name;
	}
	
	public function getFileUrl() {
		return 'https://'.MEDIA_SERVER_IP.$this->file_url;
	}

	public function getThumbnailUrl(){
		return 'https://'.MEDIA_SERVER_IP.$this->thumbnail_url;	
	}
	
	public function getFileOrder() {
		return $this->file_order;
	}
	
	public function getFileType() {
		return $this->file_type;
	}

	public function getStatus(){
		return $this->status;
	}

	public function getCreationTime(){
		return $this->creationTime;
	}

	public function getUpdationTime(){
		return $this->updationTime;
	}

	public function getExamdateBySection($sectionName){

	}

	public function getUpdatedOn() {
		return $this->updatedOn;
	}
}
