<?php

class Exam
{
    private $examId;
    private $typeOfMap;
    private $marks;
    private $marks_type;
    private $examName;
    private $acronym;
    
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
    
    public function getMarksType()
    {
    	return $this->marks_type;
    }
        
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}