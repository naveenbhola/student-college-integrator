<?php 
class scholarshipDetailPage extends MX_Controller{
    private $scholarshipCommonLib;
    private $scholarshipMobileCommonLib;
    private function _init(& $displayData){
        $this->scholarshipCommonLib = $this->load->library('scholarshipsDetailPage/scholarshipCommonLib');
        $this->scholarshipMobileCommonLib = $this->load->library('scholarshipPage/scholarshipMobileCommonLib');
        $this->scholarshipHomePageLib = $this->load->library('scholarshipHomepage/scholarshipHomePageLib');
    }

    private function _initScholarships(&$displayData, $scholarshipId){
      $this->load->builder('scholarshipsDetailPage/scholarshipBuilder');
      $this->load->config('scholarshipsDetailPage/scholarshipConfig');
      $this->scholarshipBuilder    = new scholarshipBuilder();
      $this->scholarshipRepository = $this->scholarshipBuilder->getScholarshipRepository();

      $scholarshipObj = $this->scholarshipRepository->find($scholarshipId, 'full');
      $displayData['scholarshipObj'] = $scholarshipObj;

      $this->scholarshipCommonLib->getCountryAndCourseLevelData($displayData,$scholarshipObj);
      $this->scholarshipCommonLib->getCurrencyConvertRate($displayData);
      $this->scholarshipCommonLib->getIntakeYearData($displayData);
      $this->scholarshipCommonLib->getMisceleneousConfigItems($displayData);
      $this->scholarshipCommonLib->getEligibilityExamsList($displayData);
      $this->scholarshipCommonLib->getEligibleStudentNationalities($displayData);
      $this->scholarshipCommonLib->getRequiredDocuments($displayData);
      $this->scholarshipCommonLib->getImpDatesData($displayData);
      $this->scholarshipMobileCommonLib->getCTAData($displayData);
    }

    function index(){
      $displayData = array();
      $this->_init($displayData);
      
      // url validation
      $scholarshipId = $this->scholarshipCommonLib->validateUrl($displayData);
      
      $this->_initScholarships($displayData, $scholarshipId);

      // prepare seo details
      $displayData['seoDetails'] = $this->scholarshipCommonLib->getScholarshipSeoData($displayData['scholarshipObj']);
      $this->scholarshipCommonLib->getSimiralScholarships($displayData);

      // MIS Tracking
      $displayData['beaconTrackData'] = $this->scholarshipCommonLib->prepareTrackingData('scholarshipDetailPage', $scholarshipId, $displayData);
      $displayData['trackForPages'] = true; //For JSB9 Tracking
      $displayData['trackingPageKeyIdForReg'] = 1260;
      $displayData['firstFoldCssPath'] = 'scholarshipDetailPage/css/FirstFoldCssSA';

      $this->scholarshipHomePageLib->getCountryMappingWithId($displayData, $this->config->item('scholarshipCountryIds'));
      $this->scholarshipHomePageLib->getScholarshipStatsByCountry($displayData, true, $this->config->item('scholarshipCountryIds'));
       $displayData['allLazyLoad'] = true;
       
      $this->load->view('scholarshipDetailPage/scholarshipDetailPageOverview', $displayData);
    }
}
