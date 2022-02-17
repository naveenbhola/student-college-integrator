<?php

class AbstractListingPost extends MX_Controller
{
    protected $userStatus;
    
    function __construct()
    {
    	ini_set('memory_limit', '2048M');
        $this->load->helper(array('form', 'url','date','image','shikshaUtility'));
		$this->userStatus = $this->checkUserValidation();
    }
    
    protected function cmsUserValidation($usergroupAllowed = array('cms','enterprise','sums'))
    {
		$validity = $this->checkUserValidation();
		global $logged;
		global $userid;
		global $usergroup;
		$thisUrl = $_SERVER['REQUEST_URI'];
		
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
			header('location:/enterprise/Enterprise/loginEnterprise');
			exit();
		}
		else {
			$logged = "Yes";
			$userid = $validity[0]['userid'];
			$usergroup = $validity[0]['usergroup'];
			if ($usergroup=="user" || $usergroup == "requestinfouser" || $usergroup == "quicksignupuser" || $usergroup == "tempuser" || $usergroup == "fbuser") {
				header("location:/enterprise/Enterprise/migrateUser");
				exit;
			}
			if(!in_array($usergroup,$usergroupAllowed)){
				header("location:/enterprise/Enterprise/unauthorizedEnt");
				exit();
			}
		}
		
		$this->load->library('enterprise_client');
		$entObj = new Enterprise_client();
		$headerTabs = $entObj->getHeaderTabs(1,$validity[0]['usergroup'],$validity[0]['userid']);
		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		$myProductDetails = $objSumsProduct->getProductsForUser(1,array('userId'=>$userid));

		$returnArr['userid'] = $userid;
		$returnArr['usergroup'] = $usergroup;
		$returnArr['logged'] = $logged;
		$returnArr['thisUrl'] = $thisUrl;
		$returnArr['validity'] = $validity;
		$returnArr['headerTabs'] = $headerTabs;
		$returnArr['myProducts'] = $myProductDetails;

		return $returnArr;
	}
    
    protected function validateSubscriptionInfo($userGroup, $onBehalfOf, $subscriptionId, $clientId)
	{
		$responseArray[0] = 1;
		if(!($userGroup == 'cms' && $onBehalfOf == "false" ) && $subscriptionId != "") {
			$objSumsProduct = $this->load->library('sums_product_client');
			$subscriptions = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$clientId));
			if(is_array($subscriptions[$subscriptionId])) {
				$chosenSubsArray = $subscriptions[$subscriptionId];
			} else {
				$chosenSubsArray = "";
			}

			if(!(is_array($chosenSubsArray) && $chosenSubsArray['BaseProdPseudoRemainingQuantity'] > 0)) {
                $responseArray[0] = 0;
			    $responseArray[1] = 'Your chosen subscription has been consumed with other listings. Please select some other subscription to proceed.';
			    $responseArray[2] = $subscriptionId;				
			}
		}
		return $responseArray;
	}
	
	protected function validateLocationInfo($locationInfoArray, $listingType)
	{
		$responseArray[0] = 1;
		if($listingType == 'institute') {
			foreach($locationInfoArray as $key => $locationInfo) {
				$currentLocation = "'".$locationInfo['city_name'].($locationInfo['locality_name'] == "" ? "" : " -- ".$locationInfo['locality_name'])."' location";
				// Check for valid Country ID..
				if(!is_numeric($locationInfo['country_id']) || $locationInfo['country_id'] < 1) {
					$responseArray[0] = 0;
					$responseArray[1] = "Please edit $currentLocation and enter the valid Country.";
					break;
				}
				// Check for valid city ID..
				if(!is_numeric($locationInfo['city_id']) || $locationInfo['city_id'] < 1) {
					$responseArray[0] = 0;
					$responseArray[1] = "Please edit $currentLocation and enter the valid City.";
					break;
				}
				// Check for non empty pin_code..
				if($locationInfo['pin_code'] == "") {
					$responseArray[0] = 0;
					$responseArray[1] = "Please edit $currentLocation and enter the valid Pin Code.";
					break;
				}
			}
		}
		else {                
			// Check for the Head Ofc location first..
			if(!is_numeric($locationInfoArray['head_ofc_location_id']) || $locationInfoArray['head_ofc_location_id'] < 1) {
				$responseArray[0] = 0;
				$responseArray[1] = "Please select the Head Office location first.";
			}

			// Now validate the assigned locations..
			$institute_location_ids_array = explode(",", $locationInfoArray['institute_location_ids']);
			foreach($institute_location_ids_array as $key => $institute_location_id) {
				$institute_location_id = trim($institute_location_id);
				if(!is_numeric($institute_location_id) || $institute_location_id < 1) {
					$responseArray[0] = 0;
					$responseArray[1] = "Please assign the valid Location.";
					break;
				}
			}
		}

		return $responseArray;	
	}
    
    protected function setFlags($cmsUserInfo)
    {
		if($cmsUserInfo['usergroup'] == 'cms'){
			$flagArray['moderation'] = 1;
			if($this->input->post('onBehalfOf') == 'true'){
				$flagArray['userid'] = $this->input->post('clientId');
				$flagArray['packType'] = $this->input->post('required_packtype');
			}
			else{
				$flagArray['userid'] = $cmsUserInfo['userid'];
				$flagArray['packType'] = BRONZE_LISTINGS_BASE_PRODUCT_ID;
			}
		}
		else{
			$flagArray['moderation'] = 0;
			$flagArray['userid'] = $cmsUserInfo['userid'];
            $required_type = $this->input->post('required_packtype');
			$flagArray['packType'] = GOLD_SL_LISTINGS_BASE_PRODUCT_ID;
            if(!empty($required_type)) {
				$flagArray['packType'] = $required_type;
            }
		}
		return $flagArray;
	}
	
	protected function buildListingCache($listings)
	{
		$GLOBALS['forceListingWriteHandle'] = 1;
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$courseRepository = $listingBuilder->getCourseRepository();
		$instituteRepository->disableCaching();
		$courseRepository->disableCaching();
		
		$instituteId = 0;
		$instituteRefreshed = FALSE;
		
		foreach($listings as $listing) {
			if($listing['type'] == "course") {
				$course = $courseRepository->find($listing['typeId']);
				$instituteId = $course->getInstId();
			}
			else {
				$instituteRepository->find($listing['typeId']);
				$instituteRefreshed = TRUE;
			}
		}
		
		if(!$instituteRefreshed) {
			$instituteRepository->find($instituteId);
		}
	}
	
	protected function updateProfileCompletion($listings)
	{
		$instituteId = 0;
		$this->load->library('listing/ListingProfileLib');
		
		$listingClientObj = new Listing_client();
		$listingProfileObj = new ListingProfileLib();
		
		foreach($listings as $listing) {
			if($listing['type'] == "institute") {
				$instituteId = $listing['typeId'];				
			}
			else if($listing['type'] == 'course'){
				$instituteId = $listingClientObj->getInstituteIdForCourseId(1,$listing['typeId']);
				$listingProfileObj->updateCourseProfileCompletion($listing['typeId']);
			}
		}
		
		if($instituteId >0) {
			$listingProfileObj->updateProfileCompletion($instituteId);
		}
	}
}