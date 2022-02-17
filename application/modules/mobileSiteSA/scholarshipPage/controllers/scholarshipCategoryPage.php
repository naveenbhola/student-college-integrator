<?php 
class scholarshipCategoryPage extends MX_Controller
{
    private $scholarshipCategoryPageLib;
    function __construct(){
        $this->scholarshipCategoryPageLib = $this->load->library('scholarshipCategoryPage/scholarshipCategoryPageLib');
        $this->scholarshipCategoryPageURLParserLib = $this->load->library('scholarshipCategoryPage/scholarshipCategoryPageURLParserLib');
    }

    function index($pageName,$pageIdentifier){
      $displayData = array();
      $requestData = $this->scholarshipCategoryPageURLParserLib->parseAndValidateUrl($pageName, $pageIdentifier);
      $this->load->builder('scholarshipCategoryPage/scholarshipCategoryPageBuilder');
      $scholarshipCategoryPageBuilder = new scholarshipCategoryPageBuilder();
      $scholarshipCategoryPageRepository = $scholarshipCategoryPageBuilder->getScholarshipCategoryPageRepository($requestData);
      $displayData['request'] = $scholarshipCategoryPageRepository->getScholarshipCategoryPageRequest();
      $displayData['scholarshipData'] = $scholarshipCategoryPageRepository->getScholarships();
      if(count($displayData['scholarshipData']['scholarshipObjs'])>0)
        {
            $this->scholarshipCategoryPageLib->formatTupleData($displayData);
            unset($displayData['scholarshipData']['scholarshipObjs']);
        }
      $displayData['beaconTrackData'] = $this->scholarshipCategoryPageLib->prepareTrackingData('scholarshipCategoryPage', $displayData['request']);

      $displayData['totalTupleCount'] = $displayData['request']->getTotalResults();
      $this->_getRelNextPrevLinks($displayData);
      $this->scholarshipCategoryPageLib->prepareSeoData($displayData,true);

      $displayData['trackingPageKeyIdForReg'] = 1316;
      $displayData['trackForPages'] = true;
      $displayData['tupleAJAX'] = $this->input->post("tupleAJAX",true);
      $this->load->view('scholarshipCategoryPage/scholarshipCategoryPageOverview', $displayData);
    }
    // adds rel = next & prev links to display data so that it is read by headerV2
    private function _getRelNextPrevLinks(&$displayData){
      if($displayData['totalTupleCount'] > ($displayData['request']->getPageNumber()*$displayData['request']->getSnippetsPerPage())){
        $displayData['relNext'] = $displayData['request']->getPaginatedUrl(($displayData['request']->getPageNumber())+1,true);
      }
      if($displayData['request']->getPageNumber() > 1){
        $displayData['relPrev'] = $displayData['request']->getPaginatedUrl(($displayData['request']->getPageNumber())-1,true);
      }
    }
}
