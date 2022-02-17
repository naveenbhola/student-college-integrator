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
    
 	public function extractValue(University $university,AbroadInstitute $institute,AbroadCourse $course)
    {
    	$courseSubCategoryId = $course->getCourseSubCategoryObj()->getId();
		$courseSubCategoryName = $course->getCourseSubCategoryObj()->getName();
		$this->values[$courseSubCategoryId] = $courseSubCategoryName;
		if(array_key_exists($courseSubCategoryId,$this->noOfCouresHavingSubCategory)){
			$this->noOfCouresHavingSubCategory[$courseSubCategoryId]++;
		}else{
			$this->noOfCouresHavingSubCategory[$courseSubCategoryId] = 1;
		}
    	return $course->getCourseSubCategoryObj()->getId();
    	
    }
	
	public function extractSnapshotValue(University $university, SnapshotCourse $course,$subcatArray)
    {
    	$courseSubCategoryId = $course->getCategoryId();
		if(is_numeric($courseSubCategoryId)){
			$courseSubCategoryName = $subcatArray[$courseSubCategoryId];
			$this->values[$courseSubCategoryId] = $courseSubCategoryName;
			if(array_key_exists($courseSubCategoryId,$this->noOfCouresHavingSubCategory)){
				$this->noOfCouresHavingSubCategory[$courseSubCategoryId]++;
			}else{
				$this->noOfCouresHavingSubCategory[$courseSubCategoryId] = 1;
			}
		}
    	return $course->getCategoryId();
    	
    }
    
    public function addValue(University $university,AbroadInstitute $institute,AbroadCourse $course)
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
	
	public function addSnapshotValue(University $university, SnapshotCourse $course,$subcatArray)
    {
		$courseSubCategoryId = $course->getCategoryId();
		if(is_numeric($courseSubCategoryId)){
			$courseSubCategoryName = $subcatArray[$courseSubCategoryId];
			$this->values[$courseSubCategoryId] = $courseSubCategoryName;
			if(array_key_exists($courseSubCategoryId,$this->noOfCouresHavingSubCategory)){
				$this->noOfCouresHavingSubCategory[$courseSubCategoryId]++;
			}else{
				$this->noOfCouresHavingSubCategory[$courseSubCategoryId] = 1;
			}
		}
    }
}
