<?php

class CourseCategoryFilter extends AbstractFilter
{
    public $noOfCouresHavingSubCategory = array();
    function __construct()
    {
        parent::__construct();
    }
    
    public function getFilteredValues()
    {
        //return $this->values;
	arsort($this->noOfCouresHavingSubCategory);
	foreach($this->noOfCouresHavingSubCategory as $key=>$value){
	    $subCategorySorted[$key] = $this->values[$key];
	}
	return $subCategorySorted;
    }
    
 	public function extractValue(University $university,AbroadCourse $course)
    {
    	
    	return $course->getCourseSubCategoryObj()->getId();
    	
    }
    
    public function addValue(University $university,AbroadCourse $course)
    {
    	//$this->values[$course->getCourseSubCategoryObj()->getId()] = $course->getCourseSubCategoryObj()->getName();
		$courseSubCategoryId = $course->getCourseSubCategoryObj()->getId();
		$courseSubCategoryName = $course->getCourseSubCategoryObj()->getName();
		$this->values[$courseSubCategoryId] = $courseSubCategoryName;
		if(array_key_exists($courseSubCategoryId,$this->noOfCouresHavingSubCategory)){
			$this->noOfCouresHavingSubCategory[$courseSubCategoryId]++;
		}else{
			$this->noOfCouresHavingSubCategory[$courseSubCategoryId] = 1;
		}
    }
}
