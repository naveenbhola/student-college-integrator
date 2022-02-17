<?php

class LDBCourseRepository extends EntityRepository
{
    function __construct($dao,$cache)
    {
        parent::__construct($dao,$cache);
        
        /*
         * Load entities required
         */ 
        $this->CI->load->entities(array('LDBCourse'),'LDB');
    }
    
    public function find($LDBCourseId)
    {
        Contract::mustBeNumericValueGreaterThanZero($LDBCourseId,'LDB Course ID');
        
        if($this->caching && $LDBCourse = $this->cache->getLDBCourse($LDBCourseId)) {
            return $LDBCourse;
        }
        
        $LDBCourseDataResults = $this->dao->getLDBCourse($LDBCourseId);
        $LDBCourse = new LDBCourse;
        $this->fillObjectWithData($LDBCourse,$LDBCourseDataResults);
        $this->cache->storeLDBCourse($LDBCourse);
        return $LDBCourse;
    }

    public function findMultiple($LDBCourseIds) {
        Contract::mustBeNonEmptyArrayOfIntegerValues($LDBCourseIds, 'LDB Course Id');

        $cachedLDBCourses = $this->cache->getMultiLDBCourses($LDBCourseIds);
        $toGetLDBCoursesId = array();

        foreach ($LDBCourseIds as $id) {
            if (empty($cachedLDBCourses[$id])) {
                $toGetLDBCoursesId[] = $id;
            }
        }
        
        if (empty($toGetLDBCoursesId)) {
            return $cachedLDBCourses;
        }

        $LDBCourseDataResults = $this->dao->getMutlipleLDBCourses($toGetLDBCoursesId);
        $LDBCourseObjects = $cachedLDBCourses;

        foreach ($LDBCourseDataResults as $result) {
            $LDBCourse = new LDBCourse;
            $this->fillObjectWithData($LDBCourse, $result);
            $LDBCourseObjects[] = $LDBCourse;
            $this->cache->storeLDBCourse($LDBCourse);
        }
        return $LDBCourseObjects;
    }

    public function getLDBCoursesForSubCategory($subCategoryId) {
        Contract::mustBeNumericValueGreaterThanZero($subCategoryId, 'Subcategory ID');

        if ($this->caching && $LDBCourses = $this->cache->getLDBCoursesForSubCategory($subCategoryId)) {
            return $LDBCourses;
        }

        $LDBCourseDataResults = $this->dao->getLDBCoursesForSubCategory($subCategoryId);
        $LDBCourses = array();
        foreach($LDBCourseDataResults as $result) {
            $LDBCourse = new LDBCourse;
            $this->fillObjectWithData($LDBCourse,$result);
            
            $LDBCourses[] = $LDBCourse;
        }
        $this->cache->storeLDBCoursesForSubCategory($subCategoryId,$LDBCourses);
        return $LDBCourses;
    }
    
    public function getLDBCoursesForClientCourse($clientCourseId)
    {
        Contract::mustBeNumericValueGreaterThanZero($clientCourseId,'Client Course ID');
        
        $LDBCourseDataResults = $this->dao->getLDBCoursesForClientCourse($clientCourseId);
        $LDBCourses = array();
        foreach($LDBCourseDataResults as $result) {
            $LDBCourse = new LDBCourse;
            $this->fillObjectWithData($LDBCourse,$result);
            
            $LDBCourses[] = $LDBCourse;
        }
        return $LDBCourses;
    }
    
    public function getSpecializations($LDBCourseId)
    {
        $specializationResults = $this->dao->getSpecializations($LDBCourseId);
        
        $specializations = array();
        foreach($specializationResults as $result) {
            $specialization = new LDBCourse;
            $this->fillObjectWithData($specialization,$result);
            
            $specializations[] = $specialization;
        }
        return $specializations;
    }

    public function getSubCategoriesForMultipleClientCourse($clientCourseIds) {
        Contract::mustBeNonEmptyArrayOfIntegerValues($clientCourseIds,'Client Course IDs');
        $LDBCourseModel = $this->CI->load->model('LDB/ldbcoursemodel');
        return $LDBCourseModel->getSubCategoriesForMultipleClientCourse($clientCourseIds);
    }
}