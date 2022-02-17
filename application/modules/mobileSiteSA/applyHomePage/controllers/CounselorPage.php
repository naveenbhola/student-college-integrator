<?php
class CounselorPage extends MX_Controller{
      
   private $applyHomeLib;
   
   public function __construct()
   {
	  parent::__construct();
	  $this->applyHomeLib = $this->load->library('applyHome/ApplyHomeLib');
       $this->load->config('applyHome/counselorReviewConfig');
   }
   
   private function _prepareTrackingData(&$displayData, $entityId)   
   {      
	  $displayData['beaconTrackData'] = array(
										   'pageIdentifier' => 'counselorPage',
										   'pageEntityId' => $entityId,
										   'extraData' => null
										   );
   }
   
   /*
	* controller function for counselor pages
	* @params : counselor name & id derived from url
	*/
   public function counselorPage($counselorName,$pageEntityId){
	  $displayData = array();
      $counsellorData = $this->applyHomeLib->validateUrl();
      $displayData['validateUser']  = $this->checkUserValidation();
	  $displayData['seoData']       = $this->applyHomeLib->getCounselorHomeSeoData($counsellorData);
      $displayData['trackForPages'] = true; //For JSB9 Tracking
      $this->_prepareTrackingData($displayData, $pageEntityId);
//	  $abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
//	  $displayData['examMasterList'] 		= $abroadListingCommonLib->getAbroadExamsMasterListFromCache();
      $counsellorData['counselorImageUrl']= $this->applyHomeLib->getCounsellorCMSImageUrlById($counsellorData['counsellor_id']);
      $displayData['counselorId']       = $counsellorData['counsellor_id'];
      $displayData['counsellorInfo']    = $counsellorData;
      $displayData['counsellorInfo']['firstName'] = explode(" ", $displayData['counsellorInfo']['counsellor_name'])[0];
	  
	  $displayData['counselorRatings'] 	= reset($this->applyHomeLib->getRatingInfoByCounselorIds(array($displayData['counselorId'])));
      $displayData['reviewPerPageLimit']= REVIEW_PER_PAGE;
	  $reviewResult                     = $this->applyHomeLib->getReviewByCounselorId($displayData['counselorId'],$displayData['reviewPerPageLimit'],true);
      $displayData['studentInfo']       = $this->applyHomeLib->getStudentsInfo($reviewResult['result']);
      $displayData['reviewInfo']        = $reviewResult['result'];
      foreach($displayData['reviewInfo'] as $review){
          $displayData['userEligibleForReviewDeletion'][$review['id']] = $this->applyHomeLib->checkUserEligibilityForReviewDeletion($displayData['validateUser'],$review);
      }
	  $displayData['totalReviewCount']  = $reviewResult['totalReviewCount'];
    $displayData['reviewPostTrackingKey'] = 1362;
	  // get reviews
//	  $displayData['security'] = $this->security;
    $this->_checkIfPopUpTobeTriggered($displayData);
      $displayData['firstFoldCssPath'] = '/applyHomePage/css/counsellorPageFirstFoldCssSA';
	  $this->load->view('applyHomePage/counselorPageOverview',$displayData);
	  return;
   }

   private function _checkIfPopUpTobeTriggered(& $displayData)
  {
    parse_str($this->input->server('QUERY_STRING',true),$output);
    $output = json_decode(base64_decode($output['q']));
    $displayData['triggerReviewCheck'] = $output->reviewAllowed===false;
  }

   
   public function getMoreReview(){
   		$counselorId = base64_decode($this->input->post('counselorId',true));
   		$limit = $this->input->post('limit',true);
   		$lastReviewId = $this->input->post('lastReviewId',true);
   		if($counselorId == '' || $limit==''){
   			return false;
   		}
        $reviewResult 	= $this->applyHomeLib->getReviewByCounselorId($counselorId,$limit,false,$lastReviewId);
        $displayData['studentInfo']       = $this->applyHomeLib->getStudentsInfo($reviewResult['result']);
        $displayData['reviewInfo']        = $reviewResult['result'];
        $displayData['validateUser']      = $this->checkUserValidation();
        foreach($displayData['reviewInfo'] as $review){
          $displayData['userEligibleForReviewDeletion'][$review['id']] = $this->applyHomeLib->checkUserEligibilityForReviewDeletion($displayData['validateUser'],$review);
        }
        $html = "";
   		foreach ($reviewResult['result'] as $key => $value) {
   			$html = $html.$this->load->view('applyHomePage/widgets/counselorReviewTuple',array('value'=>$value,
                                                                    'studentInfo'=>$displayData['studentInfo'],
                                                                    'validateUser'=>$displayData['validateUser'],
                                                                    'userEligibleForReviewDeletion'=>$displayData['userEligibleForReviewDeletion']
                                                                    ),true);
   		}
        $lastReview = end($reviewResult['result']);
        $lastReviewId = $lastReview['id'];
        echo json_encode(array('html'=>$html,'lastReviewId'=>$lastReviewId));
   }
}