<?php

/*
 * Generic entity that stores date type of information in examPagesCMS form
 */
class ExamDate
{
	private $id;
    private $start_date;
    private $end_date;
    private $event_name;
	private $article_id;
	private $section_name;
	private $page_id;
	private $date_order;
	private $updatedOn;
	private $eventCategory;
    
    function __construct() {
        
    }
	
	function __set($property,$value) {
        $this->$property = $value;
    }

    public function getId() {
		return $this->id;
	}

	public function getDateOrder() {
		return $this->date_order;
	}
    
	public function getStartDate() {
		return $this->start_date;
	}
	
	public function getEndDate() {
		return $this->end_date;
	}
	
	public function getEventName() {
		return $this->event_name;
	}
	
	public function getArticleId() {
		return $this->article_id;
	}

	public function getExamContentId(){
		return $this->page_id;
	}

	public function getSectionName(){
		return $this->section_name;
	}

	public function getExamdateBySection($sectionName){

	}

	public function getUpdatedOn() {
		return $this->updatedOn;
	}

    public function getEventCategory()
    {
        return $this->eventCategory;
    }
}
