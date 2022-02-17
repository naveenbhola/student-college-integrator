<?php

class RankingBanner extends ShikshaMobileWebSite_Controller {
    
	private $rankingCommonLib;
	private $rankingEnterpriseLib;
	private $rankingModel;
	private $max_banner_size = 1048576; //1 MB
	private $tabId = 57;
	
    function init() {
	    $this->load->library(array('miscelleneous','ajax','category_list_client','listing_client','register_client','enterprise_client','sums_manage_client','cacheLib','sums_manage_client', 'sums_product_client', 'Category_list_client', 'register_client', 'Subscription_client'));
	    $this->load->library(array('Category_list_client', 'register_client'));
		$this->load->helper(array("utility_helper", "shikshautility"));
		$this->config->load('ranking_config');
		$this->load->model('ranking/ranking_model');
		$this->load->builder('RankingPageBuilder', 'ranking');
		
		$this->rankingCommonLib 	= RankingPageBuilder::getRankingPageCommonLib();
		$this->rankingEnterpriseLib	= RankingPageBuilder::getRankingPageEnterpriseLib();
		$this->rankingModel			= new ranking_model();
	}
    
	function index() {
		$this->rankingBannerCmsHome();
	}
    
    public function rankingBannerCmsHome() {
		$this->init();
		$cmsPageArr = array();
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup']!='cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$userid 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$validity 	= $cmsUserInfo['validity'];
		
		$messages    = $this->getMessageParams();
		if(!empty($messages)){
			$cmsPageArr['afterPost'] = $messages;
		}
		
		$bannerFormPost = false;
		if(isset($_REQUEST['bannerformpost'])){
			$bannerFormPost = true;
		}
		if($bannerFormPost) {
			$guid 			= $_COOKIE['guid'];
			$guidFromPost 	= trim($this->input->post('guid'));
			if($guidFromPost == $guid){
				$cmsPageArr['afterPost'] = $this->handleBannerFormSubmit();
			} else {
				$cmsPageArr['afterPost']['error'][] = "You are trying to repost the same form data again!";
			}
		}
		
		$params = array();
		$params['status'] 	= array("live");
		$tempRankingPageResults = $this->rankingEnterpriseLib->getRankingPagesWithPageData($params);
		$rankingPageResults = array();
		$this->load->builder('RankingPageBuilder', 'ranking');
		$rankingPageRepository  = RankingPageBuilder::getRankingPageRepository();
		foreach($tempRankingPageResults as $rankingPageTemp){
			$id = $rankingPageTemp['id'];
			$page = $rankingPageRepository->find($id);
			if(!empty($page)){
				$rankingPageResults[] = $rankingPageTemp;
			}
		}
		
		$cmsPageArr['rankingPageArray'] = $rankingPageResults;
		
		$params = array();
		$params['limit']  	= 10;
		if(isset($_REQUEST['offset']) && is_numeric($_REQUEST['offset'])){
			$params['offset']  	= $_REQUEST['offset'];
		} else {
			$params['offset']  	= 0;
		}
		$bannerData 	= $this->rankingModel->getAllBanners($params['limit'], $params['offset']);
		$banners 		= $this->rankingCommonLib->populateCityStateInBannerData($bannerData['results']);
		$cmsPageArr['banner_data']  	= $banners;
		if($bannerData['total_rows'] != -1){
			$paginationHTML = doPagination($bannerData['total_rows'],"/ranking/RankingBanner/index/?offset=@start@", $params['offset'], $params['limit'], 5);
		}
		$guid = generateRandomAlphanumericGUID();
		setcookie("guid", $guid, time() + 3600); //set cookie value for 1 hour
		
		$cmsPageArr['userid'] 		= 	$userid;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=   $this->tabId;
		$cmsPageArr['pagination']   =   $paginationHTML;
		$cmsPageArr['guid']			= 	$guid;
		$this->load->view('ranking/ranking_banner/addBannerCms', $cmsPageArr);
	}
    
	function edit($bannerId) {
		$this->init();
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup']!='cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$userid 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$validity 	= $cmsUserInfo['validity'];
		
		$cmsPageArr = array();
		$cmsPageArr['userid'] 		= 	$userid;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		=   $this->tabId;
		
		$bannerFormPost = false;
		if(isset($_REQUEST['bannereditformpost'])){
			$bannerFormPost = true;
		}
		if($bannerFormPost) {
			$guid 			= $_COOKIE['editguid'];
			$guidFromPost 	= trim($this->input->post('guid'));
			if($guidFromPost == $guid){
				$cmsPageArr['afterPost'] = $this->handleBannerEditFormPost();
			} else {
				$cmsPageArr['afterPost']['error'][] = "You are trying to repost the same form data again!";
			}
		}
		$bannerDetails 	= $this->getBannerDataForEditOperation($bannerId);
		$cmsPageArr 	= array_merge($cmsPageArr, $bannerDetails);
		
		$guid = generateRandomAlphanumericGUID();
		setcookie("editguid", $guid, time() + 3600); //set cookie value for 1 hour
		$cmsPageArr['guid'] = $guid;
		$this->load->view('ranking/ranking_banner/editBannerCms', $cmsPageArr);
	}
	
	public function handleBannerEditFormPost(){
		$client_id 				= trim($this->input->post('client_id'));
		$ranking_page_id 		= trim($this->input->post('ranking_page_id'));
		$city_id				= trim($this->input->post('city_id'));
		$state_id 				= trim($this->input->post('state_id'));
		$subscription_id 		= trim($this->input->post('subscription_id'));
		$landing_url 			= trim($this->input->post('landing_url'));
		$bannerId 				= trim($this->input->post('banner_id'));
		
		$postData = array();
		$postData['client_id'] 			= $client_id;
		$postData['ranking_page_id'] 	= $ranking_page_id;
		$postData['city_id'] 			= $city_id;
		$postData['state_id'] 			= $state_id;
		$postData['subscription_id'] 	= $subscription_id;
		$postData['landing_url'] 		= $landing_url;
		
		$file_path = "";
		if(isset($_FILES['myImage']['tmp_name'][0]) && !empty($_FILES['myImage']['tmp_name'][0]) ){
			$uploadDetails 	= $this->uploadBanner($_FILES, $postData);
			if(array_key_exists('error', $uploadDetails)){
				$afterPost['error'] = $uploadDetails['error'];
			} else {
				$file_path = $uploadDetails['upload'][0][0]['imageurl'];
				if(trim($file_path) == "") {
					$afterPost['error'][] = "Server side error occured while uploading banner file";
				}
			}
		}
		if(count($afterPost['error']) > 0){ //Upload error occured, return it from here.
			return $afterPost;
		} else if(empty($file_path)){
			$afterPost['error'][] = "Banner field should be non empty.";
			return $afterPost;
		}
		
		$data = array();
		$data['landing_url'] 	= $landing_url;
		$data['file_path'] 		= $file_path;
		$bannerUpdateStatus = $this->rankingModel->updateBanner($bannerId,	$data);
		if($bannerUpdateStatus) {
			$afterPost['success'][] = "Banner details updated successfully.";
		}  else {
			$afterPost['error'][] = "Server error occurred. Please try again.";
		}
		return $afterPost;
	}
	
	function getBannerDataForEditOperation($bannerId = NULL) {
		$bannerPageDetails = array();
		if(empty($bannerId)){
			return $bannerPageDetails;
		}
		$bannerDetails 		= $this->rankingModel->getBannerData($bannerId);
		if(empty($bannerDetails)){
			return $bannerPageDetails;
		}
		
		$this->load->builder('LocationBuilder', 'location');
		$locationBuilder 		= new LocationBuilder;
		$locationRepository 	= $locationBuilder->getLocationRepository();
		$client_id 				= $bannerDetails['client_id'];
		$ranking_page_id 		= $bannerDetails['ranking_page_id'];
		$city_id 				= $bannerDetails['city_id'];
		$state_id 				= $bannerDetails['state_id'];
		$subscription_id 		= $bannerDetails['subscription_id'];
		$landingURL 			= $bannerDetails['landing_url'];
		
		
		$userDetails 				= $this->getEnterpriseUserDetails($client_id, 'no_ajax');
		$subscriptionDetails 		= $this->getUserSubscriptionDetails($subscription_id, $client_id, 'noAjax');
		
		$city 	= NULL;
		$state 	= NULL;
		$bannerPageDetails['city_name'] = "";
		$bannerPageDetails['state_name']= "";
		if(!empty($city_id)){
			$city 	= $locationRepository->findCity($city_id);
			$bannerPageDetails['city_name'] = $city->getName();
		}
		if(!empty($state_id)){
			$state 	= $locationRepository->findState($state_id);
			$bannerPageDetails['state_name'] = $state->getName();
		}
		$this->load->builder('RankingPageBuilder', 'ranking');
		$rankingPageRepository  = RankingPageBuilder::getRankingPageRepository();
		$rankingPage   			= $rankingPageRepository->find($ranking_page_id);
		$rankingPageName = "";
		if($rankingPage){
			$rankingPageName = $rankingPage->getName();
		}
		
		$bannerPageDetails['banner_id']				= $bannerId;
		$bannerPageDetails['client_id']				= $client_id;
		$bannerPageDetails['userDetails']			= $userDetails;
		$bannerPageDetails['subscriptionDetails']	= $subscriptionDetails;
		$bannerPageDetails['ranking_page_name'] 	= $rankingPageName;
		$bannerPageDetails['landingpage_url'] 	= $landingURL;
		
		return $bannerPageDetails;
	}
	
	public function deleteBanner($banner_id){
		$this->init();
		$status = false;
		if(!empty($banner_id)){
			$status = $this->rankingModel->deleteBanner($banner_id);
		}
		if($status == true){
			echo 1;
		} else {
			echo 0;
		}
	}
	
	function getEnterpriseUserDetails($clientId, $callType = 'ajax'){
		$this->init();
		$request['clientId'] 	= $clientId;
		$objSumsManage 			= new Sums_Manage_client();
		$clientArray 			=  $objSumsManage->getUserForQuotation(1,$request);
		if(empty($clientArray)) {
			$error['error']['message'] = "Please provide valid client id.";
			echo json_encode($error);
			return;
		}
		if($callType =='ajax') {
			echo json_encode($clientArray);	
		} else {
			return $clientArray;	
		}
	}

	function getAllRelevantCities($rankingPageId = NULL, $callType="ajax") {
		$cityAndStateArray = array();
		$this->load->builder('RankingPageBuilder', 'ranking');
		if(!empty($rankingPageId)){
			$rankingPageRepository  = RankingPageBuilder::getRankingPageRepository();
			$rankingPage   			= $rankingPageRepository->find($rankingPageId);
			//Extra all cities added at the top for india 
			$cityAndStateArray['cities']['All']  = 1;
			foreach($rankingPage->getRankingPageData() as $rankingPageData) {
				$cityAndStateArray['cities'][$rankingPageData->getCityName()]  = $rankingPageData->getCityId();
				$cityAndStateArray['states'][$rankingPageData->getStateName()] = $rankingPageData->getStateId();
			}
		}
		if($callType =='ajax'){
			echo json_encode($cityAndStateArray);
			return;
		} else {
			return $cityAndStateArray;
		}	
	}
	
	function getUserSubscriptionDetails($subscriptionId = NULL, $userId = NULL, $callType = "ajax"){
		$this->init();
		$returnValue = array();
		if(!empty($subscriptionId) && !empty($userId)) {
			$objSumsProduct =  new Sums_Product_client();
			$userSubscriptionDetails = $objSumsProduct->getAllSubscriptionsForUser(1, array('userId'=> $userId));
			$subscriptionKeys = array_keys($userSubscriptionDetails);
			if(in_array($subscriptionId, $subscriptionKeys)) {
				$subscription = $userSubscriptionDetails[$subscriptionId];
				$baseProductId = $subscription['BaseProductId'];
			}
			$returnValue = $subscription;
		}
		if($callType == 'ajax') {
			echo json_encode($returnValue);
		} else {
			return $returnValue;
		}
	}
	
	function handleBannerFormSubmit(){
		$this->init();
		$client_id 				= trim($this->input->post('client_id'));
		$ranking_page_id 		= trim($this->input->post('ranking_page_id'));
		$city_id				= trim($this->input->post('city_id'));
		$state_id 				= trim($this->input->post('state_id'));
		$subscription_id 		= trim($this->input->post('subscription_id'));
		$landing_url 			= trim($this->input->post('landing_url'));
		
		$postData = array();
		$postData['client_id'] 			= $client_id;
		$postData['ranking_page_id'] 	= $ranking_page_id;
		$postData['city_id'] 			= $city_id;
		$postData['state_id'] 			= $state_id;
		$postData['subscription_id'] 	= $subscription_id;
		$postData['landing_url'] 		= $landing_url;
		
		$afterPost = array();
		if((strtolower($city_id) == "select") && strtolower($state_id) == "select") {
			$afterPost['error'][] = "Please select atleast one of the city and state";
		}
		//if($landing_url == "" || empty($landing_url)) {
		//	$afterPost['error'][] = "Please select landing url for banner.";
		//}
		
		if(count($afterPost['error']) > 0){ //Errors in form post, so return it from here only.
			return $afterPost;
		}
		
		$subscriptionClientObj = new Subscription_client();
		$subscriptionDetails   = $subscriptionClientObj->getSubscriptionDetails(1, $subscription_id);
		$baseProductId 			= $subscriptionDetails[0]['BaseProductId'];
		$remainingQuantity 		= $subscriptionDetails[0]['BaseProdRemainingQuantity'];
		$subscriptionEndDate  	= strtotime($subscriptionDetails[0]['SubscriptionEndDate']);
		$subscriptionStartDate  = strtotime($subscriptionDetails[0]['SubscriptionStartDate']);
		$remainingQuantity      = $subscriptionDetails[0]['BaseProdRemainingQuantity'];
		
		$currentDate 		  	= strtotime(date("Y-m-d"));
		if($currentDate < $subscriptionStartDate){
			$afterPost['error'][] = "Your subscription hasn't started yet.";
		}
		if($currentDate > $subscriptionEndDate){
			$afterPost['error'][] = "Your subscription has expired.";
		}
		if($remainingQuantity <= 0){
			$afterPost['error'][] = "You have no subscription points left for this operation.";
		}
		
		$productCategory = $this->rankingEnterpriseLib->checkProductCategory($baseProductId);
		$alreadyAddedBanners = array();
		if($productCategory == "city"){
			$locationId  			= $city_id;
			$alreadyAddedBanners 	= $this->rankingModel->getBannersByLocationAndRankingPage($ranking_page_id, "city", $locationId, $client_id);
		} else if($productCategory == "state"){
			$locationId  			= $state_id;
			$alreadyAddedBanners 	= $this->rankingModel->getBannersByLocationAndRankingPage($ranking_page_id, "state", $locationId, $client_id);
		}
		if(count($alreadyAddedBanners) > 0){
			$afterPost['error'][] = "You cannot have more than 1 banner on same ranking page and location combination.";
		}
		
		if(count($afterPost['error']) > 0){ //Errors in subscription itself, so return it from here. No need to do upload as of now
			return $afterPost;
		}
		
		$file_path = "";
		$uploadDetails = $this->uploadBanner($_FILES, $postData); //Basic checks are fine, upload the SWF file now.
		
		if(array_key_exists('error', $uploadDetails)){
			$afterPost['error'] = $uploadDetails['error'];
		} else {
			$file_path = $uploadDetails['upload'][0][0]['imageurl'];
			if(trim($file_path) == "") {
				$afterPost['error'][] = "Server side error occured while uploading banner file";
			}
		}
		if(count($afterPost['error']) > 0){ //Upload error occured, return it from here.
			return $afterPost;
		}
		
		$remainingQuantity--;
		$consumeResult 		= $subscriptionClientObj->consumeSubscription(1, $subscription_id, $remainingQuantity, $client_id, $this->userid, $baseProductId, $ranking_page_id, 'ranking', $subscription_startDate, $subscription_endDate);
		
		$data = array();
		$data['client_id'] 			= $client_id;
		$data['ranking_page_id'] 	= $ranking_page_id;
		if($productCategory == "city"){
			$data['city_id'] 		= $city_id;
			$data['state_id'] 		= 0;
		}
		if($productCategory == "state"){
			$data['city_id'] 		= 0;
			$data['state_id'] 		= $state_id;
		}
		
		$data['subscription_id'] 		= $subscription_id;
		$data['landing_url'] 			= $landing_url;
		$data['subscription_startDate'] = $subscriptionDetails[0]['SubscriptionStartDate'];
		$data['subscription_endDate'] 	= $subscriptionDetails[0]['SubscriptionEndDate'];
		$data['file_path'] 				= $file_path;
		
		$return = $this->rankingModel->insertBannerDetails($data);
		if($return){
			$afterPost['success'][] = "Banner uploaded successfully.";
		} else {
			$afterPost['error'][] = "Server side error occured";
		}
		return $afterPost;
	}
	
	public function uploadBanner($files, $info, $appId = 1){
		$client_id 		= $info['client_id'];
		$uploadMesasge 	= array();
        if(trim($_FILES['myImage']['tmp_name'][0]) == ''){
			$uploadMessage['error'][] =  'Please select valid file to upload.';
            return $uploadMessage;
		} else {
            $type = $_FILES['myImage']['type'];
			$size = $_FILES['myImage']['size'];
			if(is_array($size)){
				$size = $_FILES['myImage']['size'][0];
			}
            if(is_array($type)) {
                $type = $_FILES['myImage']['type'][0];
            }
            if($type != 'application/x-shockwave-flash'){
                $uploadMessage['error'][] = "Please upload valid swf file only.";
            }
			if($size > $this->max_banner_size){
				$uploadMessage['error'][] = "Banner size can not exceed 1MB.";
            }
			if(count($uploadMessage) > 0){
				return $uploadMessage;
			}

            if($type ==  'application/x-shockwave-flash') {
                $uptype = 'video';
            }
			$this->load->library('Upload_client');
            $uploadClient = new Upload_client();
            $uploadMessage['upload'][] = $uploadClient->uploadFile($appId, $uptype, $_FILES, array(), $client_id, "categoryselector", "myImage");
			return $uploadMessage;
		}
    }
    
	function getAllRelevantSubscriptions($ranking_page_id = NULL, $city_id = "Select", $state_id = "Select", $client_id = NULL, $callType ='ajax'){
		$this->init();
		$subscriptionArray = array();
		if(empty($ranking_page_id) || empty($client_id)){
			echo json_encode($subscriptionArray);
			return;
		}
		
		$this->load->builder('CategoryBuilder', 'categoryList');
		$this->load->builder('RankingPageBuilder', 'ranking');
		$this->load->builder('LocationBuilder', 'location');
		
		$categoryBuilder = new CategoryBuilder;
		$locationBuilder = new LocationBuilder;
		
		$rankingPageRepository  = RankingPageBuilder::getRankingPageRepository();
		$locationRepository 	= $locationBuilder->getLocationRepository();
        $categoryRepository 	= $categoryBuilder->getCategoryRepository();
		
		$cityTier 	= -1;
		$stateTier 	= -1; 
		
		$rankingPage = $rankingPageRepository->find($ranking_page_id);
		if(empty($rankingPage)){
			echo json_encode($subscriptionArray);
			return;
		}
		$category    = $categoryRepository->find($rankingPage->getSubCategoryId());
		$subcatTier  = $category->getTier();
		
		$isStateTierLimitReached = false;
		$isCityTierLimitReached  = false;
		
		if(strtolower($state_id) != "select") {
			$state       = $locationRepository->findState($state_id);
			$stateTier   = $state->getTier();
			$isStateTierLimitReached = $this->rankingEnterpriseLib->checkIfMaxLimitReachedForBanner($ranking_page_id, "state", $state_id);
		}
		
		if(strtolower($city_id) != "select" && $city_id != 1){
			$city 		 = $locationRepository->findCity($city_id);
			$cityTier    = $city->getTier();
			$isCityTierLimitReached  = $this->rankingEnterpriseLib->checkIfMaxLimitReachedForBanner($ranking_page_id, "city", $city_id);
		} else if($city_id == 1){ //All cities treat it like india
			$isCityTierLimitReached  = $this->rankingEnterpriseLib->checkIfMaxLimitReachedForBanner($ranking_page_id, "city", $city_id);
		}
		
		if($isCityTierLimitReached && strtolower($state_id) == "select") {
			$error['error']['subscription'] = "Maximum number of banners on this ranking page for this city has already been uploaded.";
			echo json_encode($error);
			return;
		}
		
		if($isStateTierLimitReached && strtolower($city_id) == "select") {
			$error['error']['subscription'] = "Maximum number of banners on this ranking page for this state has already been uploaded.";
			echo json_encode($error);
			return;
		}
		
		if($isStateTierLimitReached && $isCityTierLimitReached) {
			$error['error']['subscription'] = "Maximum number of banners on this ranking page for this state/state has already been uploaded.";
			echo json_encode($error);
			return;
		}
		
		$validBaseProducts = array();
		if($cityTier != -1 && !$isCityTierLimitReached) {
			$key = 'RBP_TIER_'.$cityTier.'_CITY_SUBCAT';
			$validBaseProductIds = $this->config->item($key);
			if(array_key_exists($subcatTier, $validBaseProductIds)){
				$validBaseProducts[] = $validBaseProductIds[$subcatTier];
			}
		} else if(!$isCityTierLimitReached && $cityTier == -1 && $city_id == 1){
			$key = 'RBP_ALL_CITY_SUBCAT';
			$validBaseProductIds = $this->config->item($key);
			if(array_key_exists($subcatTier, $validBaseProductIds)){
				$validBaseProducts[] = $validBaseProductIds[$subcatTier];
			}
		}
		
		if($stateTier !=-1 && !$isStateTierLimitReached) {
			$key = 'RBP_TIER_'.$stateTier.'_STATE_SUBCAT';
			$validBaseProductIds = $this->config->item($key);
			if(array_key_exists($subcatTier, $validBaseProductIds)){
				$validBaseProducts[] = $validBaseProductIds[$subcatTier];
			}
		}
		
		$subscriptionClientObj 	=  new Subscription_client();
		$objSumsProduct 		=  new Sums_Product_client();
		
		$userSubscriptionDetails = $objSumsProduct->getAllSubscriptionsForUser(1, array('userId'=> $client_id));
		foreach($userSubscriptionDetails as $subscriptionId => $subscription) {
			$baseProductId   = $subscription['BaseProductId'];
			$baseProductName = $subscription['BaseProdSubCategory'];
			if(in_array($baseProductId, $validBaseProducts)){
				$subscriptionArray[$baseProductName] = $subscriptionId;
			}
		}
		
		if($callType == 'ajax') {
			echo json_encode($subscriptionArray);
		} else {
			return $subscriptionArray;
		}
	}
	
	public function getMessageParams(){
		$params = array();
		if(!empty($_REQUEST['op'])){
			switch($_REQUEST['op']){
				case 'banner_delete':
					$params['success'] = array();
					$params['success'][] = "Banner deleted successfully.";
					break;
				case 'banner_delete_failed':
					$params['error'] = array();
					$params['error'][] = "Banner deletion operation was not successful.";
					break;
			}
		}
		return $params;
	}
}
