<?php

class AbroadExam
{
    private $examId;
    private $examName;
    private $examDescription;
    private $minScore;
    private $maxScore;
    private $range;
    private $type;
    private $comments;
    private $cutoff;
    private $priority;
    private $listingPriority;
    
    function __construct()
    {
        
    }
    
    public function getListingPriority()
    {
	return $this->listingPriority;
    }
    
    public function getPriority()
    {
	return $this->priority;
    }
    
    public function getId()
    {
        return $this->examId;
    }
    
    public function getName()
    {
	return $this->examName;
    }
    
    public function getCutOff()
    {
	return $this->cutoff;
    }
    
    public function getDescription()
    {
	return $this->examDescription;
    }
    
    public function getMinScore()
    {
	return $this->minScore;
    }
    
    public function getMaxScore()
    {
	return $this->maxScore;
    }
    
    public function getRange()
    {
	return $this->range;
    }
    
    public function getMarksType()
    {
	return $this->type;
    }
    
    public function getComments()
    {
	return $this->comments;
    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }
	
	function cleanForCategoryPage(){
		unset($this->minScore);
		unset($this->maxScore);
		unset($this->examDescription);
		unset($this->comments);
		unset($this->type);
		unset($this->range);
	}
}
