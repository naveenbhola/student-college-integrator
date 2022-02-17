<?php

class CoursePageCron extends MX_Controller {

    public function prepareRecentThreadsForCoursePages(){
        $this->validateCron();
        $courseCommonLib = $this->load->library('coursepages/CoursePagesCommonLib');
        $COURSE_PAGES_SUB_CAT_ARRAY = $courseCommonLib->getCourseHomePageDictionary();
        $subCategoryToTagMapping    = array();
        foreach($COURSE_PAGES_SUB_CAT_ARRAY as $subCategoryId => $coursePageConfigData){
            if($coursePageConfigData['tagId'] > 0){
                $subCategoryToTagMapping[$subCategoryId]    = $coursePageConfigData['tagId'];
            }
        }
        $this->load->library('CoursePagesUrlRequest');
        $this->coursepagesurlrequest->prepareRecentThreadsForCoursePages($subCategoryToTagMapping);
        
        echo 'DONE';
    }
}
