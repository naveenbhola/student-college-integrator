<?php

/*
 * Generic entity that stores date type of information in examPagesCMS form
 */
class ExamPageDate
{
    private $startDate;
    private $endDate;
    private $eventName;
	private $articleId;
    
    function __construct() {
        
    }
	
	function __set($property,$value) {
        $this->$property = $value;
    }
    
	public function getStartDate() {
		return $this->startDate;
	}
	
	public function getEndDate() {
		return $this->endDate;
	}
	
	public function getEventName() {
		return $this->eventName;
	}
	
	public function getArticleId() {
		return $this->articleId;
	}
}