<?php

class scholarshipCategoryPage extends MX_Controller{

    function __construct(){
       $this->scholarshipCategoryPageLib = $this->load->library('scholarshipCategoryPage/scholarshipCategoryPageLib');
       $this->scholarshipCategoryPageURLParserLib = $this->load->library('scholarshipCategoryPage/scholarshipCategoryPageURLParserLib');
       $this->load->config('scholarshipCategoryPage/scholarshipCategoryPageConfig');
    }
    public function index($pageName,$pageIdentifier){
        $requestData = $this->scholarshipCategoryPageURLParserLib->parseAndValidateUrl($pageName,$pageIdentifier);
        $this->load->builder('scholarshipCategoryPage/scholarshipCategoryPageBuilder');
        $scholarshipCategoryPageBuilder = new scholarshipCategoryPageBuilder();
        $scholarshipCategoryPageRepository = $scholarshipCategoryPageBuilder->getScholarshipCategoryPageRepository($requestData);
        $displayData['request'] = $scholarshipCategoryPageRepository->getScholarshipCategoryPageRequest();
        $scholarshipData = $scholarshipCategoryPageRepository->getScholarships();
        $displayData['scholarshipData'] = $scholarshipData;
        if(count($displayData['scholarshipData']['scholarshipObjs'])>0)
        {
            $this->scholarshipCategoryPageLib->formatTupleData($displayData);
            unset($displayData['scholarshipData']['scholarshipObjs']);
        }
        //$displayData['breadcrumbData'] = $this->_prepareBreadCrumb($displayData['request']);
        $displayData['beaconTrackData'] = $this->scholarshipCategoryPageLib->prepareTrackingData('scholarshipCategoryPage', $displayData['request']);
        $displayData['newSAOverlay'] = true;
        //need to change total tuple count 
        $displayData['totalTupleCount'] = $displayData['request']->getTotalResults();
        $this->scholarshipCategoryPageLib->prepareSeoData($displayData);
        $displayData['trackForPages'] = true;
        $displayData['tupleDownloadBrochureTrackingId'] = 1324;
        $displayData['firstFoldCssPath'] = 'scholarshipCategoryPage/css/firstFoldCss';
        $this->load->view("scholarshipCategoryPage/scholarshipCategoryPageOverview",$displayData);
    }
    
    /*private function _prepareBreadCrumb(&$request){
        $returnArray = array();
        $returnArray[] = array('title' => 'Home', 'url' => SHIKSHA_STUDYABROAD_HOME);
        $returnArray[] = array('title'=>'Scholarships','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships');
        if($request->getType()=='courseLevel'){
            $returnArray[] = array('title'=>  ucfirst($request->getLevel())." Courses");
        }
        else if($request->getType()=='country'){
            $returnArray[] = array('title'=>$request->getCountryName());
        }
        return $returnArray;
    }*/

    public function generateScholarshipURL(){

        $data = array();
        $data['validateuser'] = $this->checkUserValidation();        
        if($data['validateuser'] !== 'false') {
            $data['userId'] = $data['validateuser'][0]['userid'];
        }else{
            $data['userId'] = -1;
        }
        $data['scholarshipURLObj'] = $this->input->post('scholarshipURLObj',true);
        if(isMobileRequest()){
            $data['device'] = 'mobile';
        }else{
            $data['device'] = 'desktop';
        }
        $url = $this->scholarshipCategoryPageLib->generateScholarshipURL($data);
        if(empty($url) || $url==false){
            echo json_encode(array('success'=>false));
        }else{
            echo json_encode(array('success'=>true,'url'=>$url));
        }
    }
    
    public function trackFilters()
    {
        $filterData['appliedFilters'] = $this->input->post('appliedFilters',true);
        // result count
        $filterData['resultCount'] = $this->input->post('resultCount',true);
        // url which will be saved as the base page url
        $filterData['filterAppliedUrl'] = $this->input->post('filterAppliedUrl',true);
        // userId
        $validity = $this->checkUserValidation ();
    	if (! (($validity == "false") || ($validity == ""))) {
            $filterData['userId'] = $validity [0] ['userid'];
    	}else{
            $filterData['userId'] = NULL;
        }
        $filterData['pageDimension'] = json_decode($this->input->post('pageDimension',true),true);
        
        return $this->scholarshipCategoryPageLib->trackFilters($filterData);
    }
}
