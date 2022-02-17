<?php

class Exam
{
    private $examId;
    private $typeOfMap;
    private $marks;
    private $marks_type;
    private $examName;
    private $acronym;
    private $practiceTestsOffered;
    
    function __construct()
    {
        
    }
    
    public function getId()
    {
        return $this->examId;
    }
    
    public function getAcronym()
    {
        return $this->acronym;
    }
    public function getMarks()
	{
		return $this->marks;
	}
	public function getMarksType()
	{
		return $this->marks_type;
	}
    public function getPracticeTestsOffered() {
    	return $this->practiceTestsOffered;
    }

	public function getTypeOfMap()
	{
		return $this->typeOfMap;
	}

    function __set($property,$value)
    {
        $this->$property = $value;
    }
}
