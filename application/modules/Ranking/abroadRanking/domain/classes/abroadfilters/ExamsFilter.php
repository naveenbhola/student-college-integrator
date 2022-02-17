<?php

class ExamsFilter extends AbstractFilter
{
    public $noOfCouresHavingExam = array();
    
	function __construct()
    {
        parent::__construct();
    }
    
    public function getFilteredValues()
    {   
       arsort($this->noOfCouresHavingExam);
       foreach($this->noOfCouresHavingExam as $key => $val) {
        	$examsSortedByResultCount [$key] = $this->values[$key];
        }
       return $examsSortedByResultCount ;
    }
    
    
    
   
	public function extractValue(University $university,AbroadCourse $course)
    {
    	
    	$exams = $course->getEligibilityExams();
    	
    	$examIds = array();
    	foreach($exams as $exam) {
    		$examName = $exam->getName();
    		$examId = $exam->getId();
    		if(!empty($examName) && $examId > 0) {
    			$examIds[] = $examId;
    		}
    	}
    	return $examIds;
    }
	
    public function addValue(University $university,AbroadCourse $course)
    {   $exams = $course->getEligibilityExams();
    	$examNames = array();
    	foreach($exams as $exam) {
    		$examName = $exam->getName();
    		$examId = $exam->getId();
    		if(!empty($examName) && $examId > 0) {
    			$this->values[$examId] = $examName;
                if(array_key_exists($examId, $this->noOfCouresHavingExam))
    			{	
    			$this->noOfCouresHavingExam[$examId]++;
    			}
    			else{
    				
    				$this->noOfCouresHavingExam[$examId] = 1;
    			}
    		}
    	}
    }
	
}
