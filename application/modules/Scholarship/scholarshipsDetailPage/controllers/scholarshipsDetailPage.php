<?php

class scholarshipsDetailPage extends MX_Controller{

    private function _init(& $displayData){
        $this->scholarshipCommonLib = $this->load->library('scholarshipsDetailPage/scholarshipCommonLib');
        $this->scholarshipHomePageLib = $this->load->library('scholarshipHomepage/scholarshipHomePageLib');
    }

    private function _initScholarships(&$displayData, $scholarshipId){
      $this->load->builder('scholarshipsDetailPage/scholarshipBuilder');
      $this->load->config('scholarshipConfig');

      $this->scholarshipBuilder        = new scholarshipBuilder();
      $this->scholarshipRepository     = $this->scholarshipBuilder->getScholarshipRepository();
      $scholarshipObj   = $this->scholarshipRepository->find($scholarshipId,'full');     
      $displayData['scholarshipObj'] = $scholarshipObj;
      $this->scholarshipCommonLib->getCountryAndCourseLevelData($displayData,$scholarshipObj);
      $this->scholarshipCommonLib->getCurrencyConvertRate($displayData);
      $this->scholarshipCommonLib->getMisceleneousConfigItems($displayData);
      $this->scholarshipCommonLib->getEligibilityExamsList($displayData);
      $this->scholarshipCommonLib->getEligibleStudentNationalities($displayData);
      $this->scholarshipCommonLib->getRequiredDocuments($displayData);
      $this->scholarshipCommonLib->getIntakeYearData($displayData);
      $this->scholarshipCommonLib->getImpDatesData($displayData);
    }

    public function index(){

      $displayData = array();
      $this->_init($displayData);
      // url validation
      $scholarshipId = $this->scholarshipCommonLib->validateUrl();

      $this->_initScholarships($displayData, $scholarshipId);
      
      $displayData['scholarshipId'] = $scholarshipId;
      
      // prepare seo details
      $displayData['seoDetails'] = $this->scholarshipCommonLib->getScholarshipSeoData($displayData['scholarshipObj']);
      $displayData['breadcrumbData'] = $this->_prepareBredCrumb($displayData['scholarshipObj']);
	  $displayData['newSAOverlay'] = true;
      $this->scholarshipCommonLib->getSimiralScholarships($displayData);
      // need to add extra data after object creation completes
      // MIS Tracking
      $displayData['beaconTrackData'] = $this->scholarshipCommonLib->prepareTrackingData('scholarshipDetailPage', $scholarshipId, $displayData);
      $displayData['trackForPages'] = true;
      $displayData['firstFoldCssPath'] = 'scholarshipsDetailPage/css/firstFoldCss';

      
      $this->scholarshipHomePageLib->getCountryMappingWithId($displayData, $this->config->item('scholarshipCountryIds'));
      $this->scholarshipHomePageLib->getScholarshipStatsByCountry($displayData, false, $this->config->item('scholarshipCountryIds'));
      $displayData['allLazyLoad'] = true;

      $this->_getCTATrackingIds($displayData);
      $this->load->view('scholarshipsDetailPage/scholarshipDetailPageOverview',$displayData);
    }
    private function _getCTATrackingIds(&$displayData){
      $displayData['trackingIdForLeftBrochure']     = 1318;
      $displayData['trackingIdForLeftApply']        = 1319;
      $displayData['trackingIdForBellyBrochure']    = 1320;
      $displayData['trackingIdForBellyApply']       = 1322;  
      $displayData['trackingIdForBottomBrochure']   = 1321;
      $displayData['trackingIdForBottomApply']      = 1323;
    }
    private function _prepareBredCrumb($scholarshipObj){
        $returnArray = array();
        $returnArray[] = array('title' => 'Home', 'url' => SHIKSHA_STUDYABROAD_HOME);
        $returnArray[] = array('title'=>'Scholarships','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships');
        $returnArray[] = array('title'=>$scholarshipObj->getName());
        return $returnArray;
    }
    

    public function testScholarship(){
      $this->_initScholarships();
      
      $sectionData['basic'] = array('seoTitle','scholarshipType2','type');
      $sectionData['eligibility'] = array('workXPRequired', 'workXP');
      $sectionData['specialRestrictions'] = array('full');
      $sectionData['hierarchy'] = array('courseLevel');
      $scholarshipObj   = $this->scholarshipRepository->find('25',$sectionData);     
      _p($scholarshipObj);
    }
    
    public function submitFeedback() {

        $feedbackMobile = '';
        
	$validity = $this->checkUserValidation();
    	if (! (($validity == "false") || ($validity == ""))) {
    		$feedbackMobile = $validity [0] ['mobile'];
        }
        
	$feedbackEmail          = $this->input->post('feedbackEmail', true);
	$feedbackComments 	= trim($this->input->post('feedbackComments', true));
	$sessionId 	  	= $this->input->post('sessionId', true);
	$sourcePageUrl   	= $this->input->post('sourcePageUrl', true);
	$scholarshipId = '';
	$scholarshipId   	= $this->input->post('scholarshipId', true);
	    
        $sourceApplication = isMobileRequest() ? 'mobile' : 'desktop';
        
        $feedbackData  = array( 'email'   => $feedbackEmail,
                                        'scholarshipId' => $scholarshipId,
                                        'mobile' 		=> $feedbackMobile ,
                                        'feedbackComment'	=> $feedbackComments ,
                                        'sessionId' 	  	=> $sessionId 	,
                                        'submitTime'	  	=> date("Y-m-d H:i:s"),
                                        'sourcePageUrl'  	=> $sourcePageUrl,
                                        'sourceApplication' => $sourceApplication);
        $this->scholarshipCommonLib = $this->load->library('scholarshipsDetailPage/scholarshipCommonLib');
	$result  = $this->scholarshipCommonLib->saveFeedbackData($feedbackData);
        
	$this->_sendMail($feedbackData);
	echo json_encode($result);
    }
    
    private function _sendMail($feedbackData) {
	$this->load->library('alerts_client');
	$alerts_client = new Alerts_client();	
	
        $email_content = $this->_emailContentReportIncorrect($feedbackData);
        $subject 	= $email_content['subject'];
        $content 	= $email_content['content'];
        $email          = 'joydeep.biswas@naukri.com';
      //$ccEmail        = 'shamender.srivastav@shiksha.com';
        $bccEmail       = 'simrandeep.singh@shiksha.com';
        $response	= $alerts_client->externalQueueAdd("12",SA_ADMIN_EMAIL,$email,$subject,$content,"html",'','n',array(),$ccEmail,$bccEmail);
    }
    
    private function _emailContentReportIncorrect($params) {
        $response_array = array();
        $response_array['subject'] = "A user has reported incorrect information on Scholarship id ".$params['scholarshipId'];
        $response_array['content'] = $this->load->view('widget/reportIncorrectScholarshipMailAdmin',$params,true);
        return $response_array;
    }

    public function getScholarshipUrl(){
      $validity = $this->checkUserValidation();
      if (! (($validity == "false") || ($validity == ""))) {
        $schrId = $this->input->post('schrId');
        if($schrId > 0){
          $this->load->builder('scholarshipsDetailPage/scholarshipBuilder');
          $this->scholarshipBuilder    = new scholarshipBuilder();
          $this->scholarshipRepository = $this->scholarshipBuilder->getScholarshipRepository();
          $sections = array('application'=>array('applyNowLink'));
          $scholarshipObj = $this->scholarshipRepository->find($schrId, $sections);
          echo $scholarshipObj->getApplicationData()->getApplyNowLink();
          return;
        }
      }
    }

}
