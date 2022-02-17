<?php 
class scholarshipHomePage extends MX_Controller{

    private $scholarshipHomePageLib;
    function __construct(){
        $this->scholarshipHomePageLib = $this->load->library('scholarshipHomepage/scholarshipHomePageLib');
    }

    public function index(){
        $displayData = array();
        $this->scholarshipHomePageLib->_validateUrl();
        
        $this->scholarshipHomePageLib->getMISTrackingDetails($displayData);
        $this->_getHomePageWidgets($displayData);
        $this->scholarshipHomePageLib->getSEODetails($displayData);
        $this->_getMISTrackingIdsForHomepage($displayData);
        $displayData['trackForPages'] = true;
        $displayData['firstFoldCssPath'] = 'scholarshipHomePage/css/FirstFoldCssSA';
        $this->load->view("scholarshipHomePage/scholarshipHomePageOverview", $displayData);
    }

    //temporary function made by virender to check google pagespeed score
    public function indexHome(){
        $displayData = array();
        //$this->scholarshipHomePageLib->_validateUrl();
        $this->scholarshipHomePageLib->getSEODetails($displayData);
        $this->scholarshipHomePageLib->getMISTrackingDetails($displayData);
        $this->_getHomePageWidgets($displayData);
        $this->_getMISTrackingIdsForHomepage($displayData);
        $displayData['trackForPages'] = true;
        $displayData['firstFoldCssPath'] = 'scholarshipHomePage/css/FirstFoldCssSA';
        $this->load->view("scholarshipHomePage/scholarshipHomePageOverview", $displayData);
    }

    private function _getMISTrackingIdsForHomepage(&$displayData){
        $displayData['trackingPageKeyIdForReg']   = 1327;
        $displayData['findScholarshipTrackingId'] = 1328;
    }

    private function _getHomePageWidgets(&$displayData){
        $this->scholarshipHomePageLib->getCountryMappingWithId($displayData);
        $this->scholarshipHomePageLib->getScholarshipStatsByCountry($displayData, true);
        $this->scholarshipHomePageLib->getPopularScholarship($displayData,true);
        $this->scholarshipHomePageLib->abroadCategoriesListForFindScholarshipWidget($displayData);
        $this->scholarshipHomePageLib->getScholarshipRelatedContent($displayData);
    }
}
