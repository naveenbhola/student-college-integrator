<?php

class scholarshipHierarchy {
    private $courseLevel;
    private $parentCategory;
    private $subcategory;
    private $specialization;
    private $courseCategoryComments;
    private $university;
    private $department;
    private $course;
    private $allCategorizations;
    public function getCourseLevel(){
        if(!isset($this->courseLevel)){
            throw new Exception("Please load the Scholarship Object with 'courseLevel' field in hierarchy section", 1);
        }
        if($this->allCategorizations=='1'){
            return array('all');
        }
        return $this->courseLevel;
    }
    public function getParentCategory(){
        if(!isset($this->parentCategory)){
            throw new Exception("Please load the Scholarship Object with 'parentCategory' field in hierarchy section", 1);
        }
        if($this->allCategorizations=='1'){
            return array('all');
        }
        return $this->parentCategory;
    }
    public function getSubCategory(){
        if(!isset($this->subcategory)){
            throw new Exception("Please load the Scholarship Object with 'subcategory' field in hierarchy section", 1);
        }
        if($this->allCategorizations=='1'){
            return array('all');
        }
        return $this->subcategory;
    }
    public function getSpecialization(){
        if(!isset($this->specialization)){
            throw new Exception("Please load the Scholarship Object with 'specialization' field in hierarchy section", 1);
        }
        if($this->allCategorizations=='1'){
            return array('all');
        }
        return $this->specialization;
    }
    public function getCourseCategoryComments(){
        if(!isset($this->courseCategoryComments)){
            throw new Exception("Please load the Scholarship Object with 'courseCategoryComments' field in hierarchy section", 1);
        }
        return $this->courseCategoryComments;
    }
    public function getUniversity(){
        if(!isset($this->university)){
            throw new Exception("Please load the Scholarship Object with 'university' field in hierarchy section", 1);
        }
        return $this->university;
    }
    public function getDepartments(){
        if(!isset($this->department)){
            throw new Exception("Please load the Scholarship Object with 'department' field in hierarchy section", 1);
        }
        return $this->department;
    }
    public function getCourses(){
        if(!isset($this->course)){
            throw new Exception("Please load the Scholarship Object with 'course' field in hierarchy section", 1);
        }
        return $this->course;
    }
    
        function __set($property,$value){
        $this->$property = $value;
    }
}
