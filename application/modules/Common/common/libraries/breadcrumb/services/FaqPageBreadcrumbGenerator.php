<?php

class FaqPageBreadcrumbGenerator extends BreadcrumbGenerator {

    public $CI;
    public $CoursePagesUrlRequest;
    public $COURSE_HOME_ARRAY;
    public $courseHomePageId;
    private $courseResource;
    private $breadCrumbText = array("Home", "text", "text");

    function __construct($options) {
        $this->CI = & get_instance();
        $this->CoursePagesUrlRequest = clone $options['request'];
        $this->COURSE_HOME_ARRAY = $options['courseHomePageArray'];
        $this->courseHomePageId = $options['courseHomePageId'];
        $this->courseResource=$options['resources'];
        $this->Breadcrumbs = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');
    }

    public function prepareBreadcrumbHtml() {

        $courseHomePageUrlGeneratorObject = $this->CI->load->library('coursepages/CourseHomePageUrlGenerator');
        $courseBreadCrumbStructureArray = $courseHomePageUrlGeneratorObject->getCourseHomePageUrlViaCourseHomePageArray($this->COURSE_HOME_ARRAY[$this->courseHomePageId], 'array');
        $this->addBreadCrumbStructure($courseBreadCrumbStructureArray);
        if(isset($this->courseResource[$this->courseHomePageId])){
            $this->Breadcrumbs->addItem('Resources',$this->courseResource[$this->courseHomePageId]['url']);
        }else{
            $this->Breadcrumbs->addItem('Resources');
        }
        
        $this->Breadcrumbs->addItem('FAQ');
        return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
    }

    private function addBreadCrumbStructure($courseBreadCrumbStructureArray) {
        $this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
        $courseNickName = array_pop($courseBreadCrumbStructureArray);
        foreach ($courseBreadCrumbStructureArray as $courseBreadCrumb) {
            $this->addEntityToBreadCrumb($courseBreadCrumb);
        }
       $this->Breadcrumbs->addItem($courseNickName,  $this->COURSE_HOME_ARRAY[$this->courseHomePageId]['url']);
    }

    private function checkIfCourseHomePageExistsWithGivenUrl($courseHomePageUrl) {
        foreach ($this->COURSE_HOME_ARRAY as $courseHomeDetails) {
            $originalUrl = $courseHomeDetails['url'];
            $courseHomeDetails['url'] = substr($courseHomeDetails['url'], 0, strpos($courseHomeDetails['url'], "-chp"));
            if ($courseHomeDetails['url'] == $courseHomePageUrl) {
                return $originalUrl;
            }
        }
        return '';
    }

    private function getUrlForEntity($entityName) {
        $entityName = strtolower($entityName);
        $entityName=  str_replace(" ", "-", $entityName);
        return SHIKSHA_HOME . '/' . $entityName;
    }

    private function addEntityToBreadCrumb($entityName) {
        $entityUrl = $this->getUrlForEntity($entityName);
        if (($fullUrl = $this->checkIfCourseHomePageExistsWithGivenUrl($entityUrl)) != '') {
            $this->Breadcrumbs->addItem($entityName, $fullUrl);
        } else {
            $this->Breadcrumbs->addItem($entityName);
        }
    }

}
