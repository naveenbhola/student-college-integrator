<?php

class scholarshipHomepage extends MX_Controller{
    function __construct(){
        $this->scholarshipHomePageLib = $this->load->library('scholarshipHomePageLib');
    }

    public function index(){
        $displayData = array();
        $this->scholarshipHomePageLib->_validateUrl();
       
        $this->scholarshipHomePageLib->getMISTrackingDetails($displayData);
	    $displayData['newSAOverlay'] = true;
        $displayData['trackForPages'] = true;
        $displayData['firstFoldCssPath'] = 'scholarshipHomepage/css/firstFoldCss';
        $displayData['findScholarshipTrackingId'] = 1317;
        $this->_getHomePageWidgets($displayData);
         $this->scholarshipHomePageLib->getSEODetails($displayData);
        $this->load->view("scholarshipHomepage/scholarshipHomepageOverview", $displayData);
    }

    private function _getHomePageWidgets(&$displayData){
        $this->scholarshipHomePageLib->getCountryMappingWithId($displayData);
        $this->scholarshipHomePageLib->getScholarshipStatsByCountry($displayData);
        $this->scholarshipHomePageLib->abroadCategoriesListForFindScholarshipWidget($displayData);
        $this->scholarshipHomePageLib->getPopularScholarship($displayData);
        $this->scholarshipHomePageLib->getScholarshipRelatedContent($displayData);
    }
}
