<?php

class ViewCountSorter extends AbstractSorter
{
    protected $sortKey = ABROAD_CP_SORTER_VIEWCOUNT;
    
    function __construct($params = array(), AbroadListingCommonLib $abroadListingCommonLib)
    {
        parent::__construct($params);
        $this->abroadListingCommonLib = $abroadListingCommonLib;
    }
    
    public function extractSortValue(AbroadCourse $course)
    {
        $courseId[] = $course->getId();
        
        $viewCount = $this->abroadListingCommonLib->getViewCountForCourseListByDays($courseId,7);
        return $viewCount[$courseId[0]];
    }
}