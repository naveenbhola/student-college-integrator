<?php

class shipment extends MX_Controller{

    private function _init(& $displayData){
        $displayData['validateuser'] = $this->checkUserValidation();
        $displayData['isValidStudyAbroadUser'] = 0;
        if($displayData['validateuser'] !== 'false') {
          $this->load->model('user/usermodel');
          $usermodel = new usermodel;
          $userId = $displayData['validateuser'][0]['userid'];
          $user = $usermodel->getUserById($userId,true);
          if (is_object($user)) {
            $loc = $user->getLocationPreferences();
            foreach($loc  as $location) {
              $countryId = $location->getCountryId();
              if($countryId > 2)
              {
                $displayData['isValidStudyAbroadUser'] = 1;
                break;
              }
            }
          }
        }
        $this->load->config('shipment/shipmentConfig');
        $this->load->config('shipment/studentTestimonialsConfig');
        $this->shipmentCommonLib = $this->load->library('shipment/shipmentCommonLib');
    }

    function welcomePage(){
      $displayData = array();
      $this->_init($displayData);
      // url validation
      $seoDetails = $this->config->item('seoDetails');
      $this->shipmentCommonLib->validateURL($seoDetails['welcomePage']['url']);
      // prepare seo details
      $displayData['schedulePickupPageUrl'] = $seoDetails['schedulePickupPage']['url'];
      $displayData['confirmationPageUrl'] = $seoDetails['confirmationPage']['url'];
      $displayData['seoDetails'] = $seoDetails['welcomePage'];

      // student testimonials
      $displayData['studentTestimonialsForShipment'] = $this->config->item('studentTestimonialsForShipment');
      
      $displayData['shipmentPriceComparision'] = $this->config->item('shipmentPriceComparision');

      $displayData['alreadySchedulePickups'] = false;
      if($displayData['validateuser'] !== "false"){
        // check if user already placed a shipment request.
        $displayData['alreadySchedulePickups'] = $this->shipmentCommonLib->checkIfUserAlreadySchdulePickups((integer)$displayData['validateuser'][0]['userid']);
      }      

      // MIS Tracking
      $displayData['beaconTrackData'] = $this->shipmentCommonLib->prepareTrackingData('shipmentWelcomePage');
      $displayData['trackForPages'] = true;
      $displayData['trackingKeyIdBookYourPickupTop'] = 1129;
      $displayData['trackingKeyIdBookYourPickupBottom'] = 1130;
      $this->load->view('shipment/welcomePageOverview',$displayData);
    }

    function schedulePickupPage(){
      $displayData = array();
      $this->_init($displayData);
      $seoDetails = $this->config->item('seoDetails');
      $this->_checkSchedulePageRedirections($displayData,$seoDetails);  // need to uncomment this

      $displayData['destinationCountries'] = $this->shipmentCommonLib->getDestinationCountries();
      $displayData['USStateDetails'] = $this->shipmentCommonLib->getUSStateDetails();
      $displayData['isdCodes'] = $this->shipmentCommonLib->getIsdCodeData($displayData['destinationCountries']);
      $displayData['pickageWeight'] = $this->config->item('shipmentWeight');
      
      $this->_preparePickupDetailsData($displayData);
      $displayData['seoDetails'] = $seoDetails['schedulePickupPage'];
      $displayData['beaconTrackData'] = $this->shipmentCommonLib->prepareTrackingData('schedulePickupPage');
      $displayData['trackForPages'] = true;
      //_p($displayData);die;
      $this->load->view('shipment/schdulePickupOverview',$displayData);
    }

    function confirmationPage(){
	    $displayData = array();
        $this->_init($displayData);
        // prepare seo details
        $seoDetails = $this->config->item('seoDetails');
		$displayData['redirectUrl'] = $seoDetails['welcomePage']['url'];
		$this->shipmentCommonLib->validateURL($seoDetails['confirmationPage']['url']);
		if($displayData['validateuser'] == 'false')
		{
			redirect($displayData['redirectUrl'],'location',301);
		}
		$userId = $displayData['validateuser'][0]['userid'];
		$displayData['email'] = explode('|',$displayData['validateuser'][0]['cookiestr']);
		$displayData['email'] = $displayData['email'][0];
		
		$userShipmentId = $this->input->get('shipmentNum');
		$displayData['seoDetails'] = $seoDetails['confirmationPage'];
		// get all AWB tracking for user
		$displayData['shipmentTrackingData'] = $this->shipmentCommonLib->getAllShipmentTrackingByUser($userId, $userShipmentId);
		if($displayData['shipmentTrackingData'] === false ||
		   (is_null($displayData['shipmentTrackingData']['topSection']) && $userShipmentId>0)
		   )
		{
			//send to second page
			redirect($displayData['redirectUrl'],'location',301);
		}
		// if records found send to 2nd page via "book another pick up button"
		if(count($displayData['shipmentTrackingData']['tableData'])>0)
		{
				$displayData['redirectUrl'] = $seoDetails['schedulePickupPage']['url'];
		}else{
				$displayData['redirectUrl'] = $seoDetails['welcomePage']['url'];
		}

		// get page tracking data
		$trackData = array();
		if(array_key_exists('topSection',$displayData['shipmentTrackingData']))
		{
			$trackData = $this->shipmentCommonLib->getExtraDataForBeaconTrack($displayData['shipmentTrackingData']['topSection']['shipmentId']);
			$displayData['seoDetails']['title'] = $seoDetails['confirmationPage']['title']['confirmation'];
			$displayData['seoDetails']['description'] = $seoDetails['confirmationPage']['description']['confirmation'];
		}
		else{
			$displayData['seoDetails']['title'] = $seoDetails['confirmationPage']['title']['track'];
			$displayData['seoDetails']['description'] = $seoDetails['confirmationPage']['description']['track'];
		}
        $displayData['beaconTrackData'] = $this->shipmentCommonLib->prepareTrackingData('shipmentConfirmationPage',$trackData);
        $displayData['trackForPages'] = true;
        $this->load->view('shipment/confirmationPageOverview',$displayData);
    }
	/*
	 * download AWB 
	 */
	public function downloadAWB($url){
		$this->load->helper('download');
		downloadFileInChunks(base64_decode($url), 400000);
	}
	/*
	 * get courses in a univ for autosuggestor
	 */
	public function getUnivCourseList()
	{
		$universityId = $this->input->post('universityId');
		if(is_null($universityId) || $universityId == ""){
			return false;
		}else{
			$this->load->library('SASearch/AutoSuggestorSolrClient');
			$this->autoSuggestorSolrClient = new AutoSuggestorSolrClient;
			$univCourseList = $this->autoSuggestorSolrClient->getUnivCourseListFromSolr(array('universityId'=>$universityId));
			if(count($univCourseList)>0)
			{
				echo json_encode($univCourseList);
			}else{
				return false;
			}
		}
	}
	public function uploadShipmentAttachment(){
		$this->uploadClient = $this->load->library('common/upload_client');
		$res = $this->uploadClient->uploadFile(1,"pdf",$_FILES,array(),"-1","shikshaApplyPDF","somefile");
		echo serialize($res);
    	}

    private function _checkSchedulePageRedirections($displayData,$seoDetails){
      if($displayData['validateuser'] === 'false'){       // If user isn't logged in, send to page 1
        header("Cache-Control: no-cache");
        header('Location: '.$seoDetails['welcomePage']['url'],TRUE,302);
        exit();
      }
      $this->shipmentCommonLib->validateURL($seoDetails['schedulePickupPage']['url']);    //If URL is wrong
    }

    private function _preparePickupDetailsData(& $displayData){
      $pickupData = array();
      $user = $displayData['validateuser'][0];
      $pickupData['firstname'] = $user['firstname'];
      $pickupData['lastname'] = $user['lastname'];
      $pickupData['email'] = reset(explode('|', $user['cookiestr']));
      $pickupData['mobile'] = $user['mobile'];
      $userCity = $user['cityname'];
      $dhlCityId = $this->shipmentCommonLib->getDHLCityId($userCity);
      $citiesData = $this->shipmentCommonLib->getDHLCityData();
      uasort($citiesData, function($a,$b){if($a['name'] < $b['name']) return -1; return 1;});
      $displayData['dhlCityData'] = $citiesData;
      if($dhlCityId > 0){
        $cityData = $citiesData[$dhlCityId];
        $pickupData['cityDetails'] = $cityData;
        $pickupData['dateDetails'] = $this->shipmentCommonLib->getDHLPickupTimeDetails($cityData);
      }
      $displayData['pickupData'] = $pickupData;
    }

    public function getDHLPickupTimeDetails(){
      $cityId = (integer)$this->input->post('cityId',true);
      if($cityId < 0){    // To prevent xss or the like
        exit();
      }
      $this->_init();
      if($cityId > 0){
        $cityData = $this->shipmentCommonLib->getPickupDataForCity($cityId);
        $displayData['pickupData']['dateDetails'] = $this->shipmentCommonLib->getDHLPickupTimeDetails($cityData);  
      }else{
        $displayData['pickupData']['dateDetails'] = array();  
      }
      $this->load->view('shipment/widgets/pickupDateOptions',$displayData);
    }   
    
    
    public function placeOrder(){
        $displayData = array();
        $this->_init($displayData);
        $seoData = $this->config->item('seoDetails');
        $response = array();
        if($displayData['validateuser'] == 'false'){
            $response['invalidUser'] = true;
            $response['url'] = $seoData['welcomePage']['url'];
            echo json_encode($response);
            exit();
	       }
        $fromFields = $this->input->post('pickupFormFields',true);
        $toFields   = $this->input->post('destinationFormFields',true);
        $postData['sender']   = $fromFields;
        $postData['receiver'] = $toFields;
        $trackingId = $this->input->cookie('schdulePickupTrackingKeyId', TRUE);
        if(empty($trackingId)){
            $trackingId = '1144';
        }
        $postData['visitorSessionId'] = getVisitorSessionId();
        $postData['trackingId'] = $trackingId;
        if(!empty($postData['receiver']['universityId'])){
            if(!$this->shipmentCommonLib->validCountryOfUniversity($postData['receiver']['countryId'],$postData['receiver']['universityId'])){
                
                $response['status']     =   SHIPMENT_FAILURE;
                $response['statusCode'] =   SHIPMENT_INVALID_DATA_CODE;
                $response['description']=   "Could not find the university in the selected country";
                echo json_encode($response);
                exit();
            }
        }
        trimAssociativeArray($postData);
        $requestData = $this->shipmentCommonLib->prepareOrderRequest($postData,$displayData['validateuser'][0]);
        $response = $this->shipmentcommonlib->placeOrderWithDHL($requestData);
        if($response['status']==SHIPMENT_SUCCESS){
            $userShipmentInsertId = $this->shipmentcommonlib->saveShipmentData($postData,$response,$displayData['validateuser'][0]);
            $this->shipmentcommonlib->sendNotifications($postData,$response,$displayData['validateuser'][0]);
        }
        if($userShipmentInsertId){
            $url = $seoData['confirmationPage']['url'];
            $response['url'] = $url.'?shipmentNum='.$userShipmentInsertId;
        }
        echo json_encode($response);
    }
        public function checkPrice(){
            $fromFields = $this->input->post('pickupFormFields',true);
            $toFields   = $this->input->post('pickupToFields',true);
            $displayData = array();
            $this->_init($displayData);
            if($displayData['validateuser'] == 'false'){
                echo json_encode('false');
                exit();
            }
            $postData['sender']   = $fromFields;
            $postData['receiver'] = $toFields;
            trimAssociativeArray($postData);
            $requestData = $this->shipmentCommonLib->prepareOrderRequest($postData,$displayData['validateuser'][0]);
            $response    = $this->shipmentCommonLib->getQuote($requestData);
            echo json_encode($response);
        }

}
