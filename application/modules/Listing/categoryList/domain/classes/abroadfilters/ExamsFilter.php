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
    
    
    
   
	public function extractValue(University $university,AbroadInstitute $institute,AbroadCourse $course)
    {
    	
    	$exams = $course->getEligibilityExams();
    	$examNames = array();
    	$examIds = array();
    	foreach($exams as $exam) {
    		$examName = $exam->getName();
    		$examId = $exam->getId();
    		if(!empty($examName) && $examId > 0) {
    			$examIds[] = $examId;
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
    	return $examIds;
    }
	
	public function extractSnapshotValue(University $university,SnapshotCourse $course)
    {
    	return array();	//SnapshotCourses do not have exams.
    }
    
    public function addValue(University $university,AbroadInstitute $institute,AbroadCourse $course)
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
	
	public function addSnapshotValue(University $university,SnapshotCourse $course)
    {
		//Snapshot Courses do not have exams.
    }
}
