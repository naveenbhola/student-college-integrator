<?php

class SearchEnterprise extends MX_Controller {
	
	private $searchCommonLib;
	private $searchSponsoredLib;
	private $userStatus;
	
	public function __construct(){
		$this->config->load('search_config');
		$this->load->builder('SearchBuilder');
		$this->load->builder('CategoryBuilder','categoryList');
		$this->load->builder('ListingBuilder','listing');
		$this->load->builder("LocationBuilder", "location");
		$this->load->model('search/SearchModel', '', true);
		
		$this->searchCommonLib 	   = SearchBuilder::getSearchCommon();
		$this->searchSponsoredLib  = SearchBuilder::getSearchSponsored();
		
		$this->load->library(array('sums_manage_client', 'sums_product_client', 'Category_list_client', 'register_client', 'Subscription_client'));
		$this->userStatus = $this->checkUserValidation();
		if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
            $this->userid = $this->userStatus[0]['userid'];
        } else {
            $this->userid = -1;
        }
	}
	
	function setSponsoredListing() {
		$startTime = microtime(true);
		$cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
		if($cmsUserInfo['usergroup']!='cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$userid 	= $cmsUserInfo['userid'];
		$usergroup 	= $cmsUserInfo['usergroup'];
		$thisUrl 	= $cmsUserInfo['thisUrl'];
		$validity 	= $cmsUserInfo['validity'];
		
		$cmsPageArr = array();
		$cmsPageArr['userid'] 		= 	$userid;
		$cmsPageArr['validateuser'] = 	$validity;
		$cmsPageArr['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
		$cmsPageArr['prodId'] 		= '23'; //Tab ID
		$cmsPageArr['current_year'] = date('Y');
		
		$onBehalfOf = $this->input->post('onBehalfOf');
		$showErrorMessage = "true";
		
		$clientUidURLParam = $this->security->xss_clean($_REQUEST['clientuid']);
		$fromURLParam = $this->security->xss_clean($_REQUEST['from']);
		
		if($onBehalfOf=="true") {
			$userid = $this->input->post('selectedUserId',true);
			$regObj = new Register_client();
			$arr = $regObj->userdetail(1,$userid);
			$cmsPageArr['userDetails'] = $arr[0];
			$cmsPageArr['userDetails']['clientUserId'] = $userid;
			$showErrorMessage = "false";
		} else if(!empty($clientUidURLParam) && !empty($fromURLParam) && $fromURLParam == "url"){
			$userid = $clientUidURLParam;
			$regObj = new Register_client();
			$arr = $regObj->userdetail(1,$userid);
			$cmsPageArr['userDetails'] = $arr[0];
			$cmsPageArr['userDetails']['clientUserId'] = $userid;
			$showErrorMessage = "false";
		}
		
		$entObj = new Enterprise_client();
		$objSumsProduct =  new Sums_Product_client();
		$cmsPageArr['subscriptionDetails'] = $objSumsProduct->getAllSubscriptionsForUser(1, array('userId' => $userid));
		$cmsPageArr['landing_error'] = $showErrorMessage;
		
		$userArr['userid'] 		= $userid;
		$userArr['startFrom'] 	= 0;
		$userArr['countOffset'] = 10;
		$userListings = $entObj->getListingsByClient(1, $userArr);
		$cmsPageArr['clientListings'] = $userListings;
		
		$params = array();
		$cmsPageArr['productInfo'] = $objSumsProduct->getProductFeatures(1, $params);
		/* Get all relevant search products */
		// Sponsored products
		$sponsoredProductKeys = $this->config->item('sponsored_base_product_keys');
		foreach($sponsoredProductKeys as $sponsoredProductKey){
			$productId = $this->config->item($sponsoredProductKey);
			$cmsPageArr['sponsoredProductIds'][] = $productId;
		}
		// Featured products
		$featuredProductKeys = $this->config->item('featured_base_product_keys');
		foreach($featuredProductKeys as $featuredProductKey){
			$productId = $this->config->item($featuredProductKey);
			$cmsPageArr['featuredProductIds'][] = $productId;
		}
		// Banner products
		$bannerProductKeys = $this->config->item('banner_base_product_keys');
		foreach($bannerProductKeys as $bannerProductKey){
			$productId = $this->config->item($bannerProductKey);
			$cmsPageArr['bannerProductIds'][] = $productId;
		}
		
		$this->load->view('search/search_enterprise/search_sponsored_listing',$cmsPageArr);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}
	
	public function getCityCategoryForSubscription($subscriptionId = NULL, $userId = NULL, $callType = "ajax"){
		$returnValue = array();
		
		if(!empty($subscriptionId) && !empty($userId) && is_numeric($userid) && is_numeric($subscriptionId) ){
			$objSumsProduct =  new Sums_Product_client();
			$userSubscriptionDetails = $objSumsProduct->getAllSubscriptionsForUser(1, array('userId'=> $userId));
			
			$subscriptionKeys = array_keys($userSubscriptionDetails);
			if(in_array($subscriptionId, $subscriptionKeys)){
				$subscription = $userSubscriptionDetails[$subscriptionId];
				$baseProductId = $subscription['BaseProductId'];
				$returnValue = $this->searchSponsoredLib->getCityCategoryForSearchProduct($baseProductId);
			}
		}
		
		if($callType  == "ajax"){
			echo json_encode($returnValue);	
		} else if($callType  == "functioncall"){
			return $returnValue;
		}
	}
	
	
	public function getCoursesByLocationCategory($subscriptionId = NULL, $instituteId = NULL, $cityId = NULL, $subCategoryId = NULL){
		$validCourseList = array();
		$sponsorType  = "";
		$productReach = "national";
		if(!empty($subscriptionId) && !empty($instituteId) && !empty($cityId) && !empty($subCategoryId)){
			$listingBuilder = new ListingBuilder();
			$instituteRepo = $listingBuilder->getInstituteRepository();
			$courseRepo = $listingBuilder->getCourseRepository();
			$locationWiseCourses = $instituteRepo->getLocationwiseCourseListForInstitute($instituteId);
			$courseList = array();
			$virtualCityMapping = $this->searchCommonLib->getVirtualCityMappingForSearch();
			//$virtualCityMapping = $this->config->item('virtual_city_mapping');
			if(array_key_exists($cityId, $virtualCityMapping)){
				$validCityIds = $virtualCityMapping[$cityId];
				foreach($locationWiseCourses as $courses){
					if(in_array($courses['city_id'], $validCityIds)){
						$courseList = array_merge($courseList, $courses['courselist']);
					}
				}
			} else {
				foreach($locationWiseCourses as $courses){
					if($courses['city_id'] == trim($cityId)){
						$courseList = array_merge($courseList, $courses['courselist']);
					}
				}
			}
			
			foreach($courseList as $courseId){
				$categories = $instituteRepo->getCategoryIdsOfListing($courseId, "course");
				if(in_array($subCategoryId, $categories)){
					$courseObj = $courseRepo->find($courseId);
					$validCourseList[$courseObj->getId()] = $courseObj->getName();
				}
			}
			
			$subscriptionClientObj = new Subscription_client();
			$subscriptionDetails   = $subscriptionClientObj->getSubscriptionDetails(1, $subscriptionId);
			$sponsorType = $this->searchSponsoredLib->getSponsorTypeByProductId($subscriptionDetails[0]);
			
			$baseProductId = $subscriptionDetails[0]['BaseProductId'];
			
			$sponsoredProductKeys = $this->config->item('sponsored_base_product_keys');
			$featuredProductKeys  = $this->config->item('featured_base_product_keys');
			$bannerProductKeys    = $this->config->item('banner_base_product_keys');
			$allProductKeys = array_merge($sponsoredProductKeys, $featuredProductKeys, $bannerProductKeys);
			foreach($allProductKeys as $productKey){
				$productId = $this->config->item($productKey);
				if($productId == $baseProductId){
					if(strpos($productKey, "abroad")){
						$productReach = "studyabroad";
						break;	
					}
				}
			}
		}
		
		$returnValue = array();
		$returnValue['count'] 			  = count($validCourseList);
		$returnValue['courselist'] 		  = $validCourseList;
		$returnValue['flag'] 			  = $productReach;
		$returnValue['subscription_type'] = $sponsorType;
		echo json_encode($returnValue);
	}
	
	public function getStudyAbroadCoursesByLocationCategory($subscriptionId = NULL, $instituteId = NULL, $countryId = NULL, $categoryId = NULL){
		$validCourseList = array();

		if($instituteId != NULL && $countryId != NULL && $categoryId != NULL && is_numeric($instituteId) && is_numeric($countryId) && is_numeric($categoryId)){
			$listingBuilder = new ListingBuilder();
			$locationBuilder = new LocationBuilder();
			$locationRepo = $locationBuilder->getLocationRepository();
			$instituteRepo = $listingBuilder->getInstituteRepository();
			$courseRepo = $listingBuilder->getCourseRepository();
			
			$locationWiseCourses = $instituteRepo->getLocationwiseCourseListForInstitute($instituteId);
			if(!property_exists($locationWiseCourses, 'ERROR_MESSAGE')){
				$courseList = array();
				foreach($locationWiseCourses as $courses){
					$cityEntity = $locationRepo->findCity($courses['city_id']);
					$countryIdFromEntity = $cityEntity->getCountryId();
					if($countryIdFromEntity == trim($countryId)){
						$courseList = array_merge($courseList, $courses['courselist']);
					}
				}
				
				$categoryBuilder = new CategoryBuilder();
				$categoryRepo = $categoryBuilder->getCategoryRepository();
				foreach($courseList as $courseId){
					$categories = $instituteRepo->getCategoryIdsOfListing($courseId, "course");
					foreach($categories as $tempCategoryId){
						$categoryEntity = $categoryRepo->find($tempCategoryId);
						$categoryIdFromEntity = $categoryEntity->getParentId();
						if($categoryIdFromEntity == $categoryId){
							$courseObj = $courseRepo->find($courseId);
							$validCourseList[$courseObj->getId()] = $courseObj->getName();
						}
					}
				}
			}
		}
		$returnValue = array();
		$returnValue['count'] = count($validCourseList);
		$returnValue['courselist'] = $validCourseList;
		$returnValue['flag'] = 'studyabroad';
		$returnValue['subscription_type'] = "sponsored";
		echo json_encode($returnValue);
	}
	
	private function checkSubscriptionParams($params = array()){
		$everythingPresent = true;
		$generalParamsRequired = array(
									'sp_client_userid',
									'sp_listing_id',
									'sp_subscription_id',
									'sp_product_reach',
									'sp_search_type',
									'sp_base_product_id'
									);
		$paramsRequiredForCourseSubscription = array(
													'courses' ,
													'sp_category_id',
													'sp_location_id'
													);
		$paramKeys = array_keys($params);
		foreach($generalParamsRequired as $param){
			if(!in_array($param, $paramKeys)){
				$everythingPresent = false;
				break;
			}
		}
		
		if($everythingPresent){
			switch($params['sp_search_type']) {
				case 'course':
					foreach($paramsRequiredForCourseSubscription as $param){
						if(!in_array($param, $paramKeys)){
							$everythingPresent = false;
							break;
						}
					}
					break;
			}
		}
		return $everythingPresent;
	}
	
	public function consumeSearchProductSubscription(){
		$returnValue = array("error" => 0, "success" => 0);
		$requiredPramsPresent = $this->checkSubscriptionParams($_REQUEST);
		$sponsorType = "";
		if($requiredPramsPresent){
			$subscriptionIdURLParam 			= $this->security->xss_clean($_REQUEST['sp_subscription_id']);
			$subscriptionClientUidURLParam 		= $this->security->xss_clean($_REQUEST['sp_client_userid']);
			$subscriptionLocationIdURLParam 	= $this->security->xss_clean($_REQUEST['sp_location_id']);
			$subscriptionCategoryIdURLParam 	= $this->security->xss_clean($_REQUEST['sp_category_id']);
			$subscriptionProductReachURLParam 	= $this->security->xss_clean($_REQUEST['sp_product_reach']);
			$subscriptionListingIdURLParam 		= $this->security->xss_clean($_REQUEST['sp_listing_id']);
			$subscriptionSearchTypeURLParam 	= $this->security->xss_clean($_REQUEST['sp_search_type']);
			
			$subscriptionId = (!empty($subscriptionIdURLParam)) ? trim($subscriptionIdURLParam) : "";
			$clientUserId 	= (!empty($subscriptionClientUidURLParam)) ? trim($subscriptionClientUidURLParam) : "";
			$locationId 	= (!empty($subscriptionLocationIdURLParam)) ? trim($subscriptionLocationIdURLParam) : ""; 
			$categoryId 	= (!empty($subscriptionCategoryIdURLParam)) ? trim($subscriptionCategoryIdURLParam) : "";
			$productReach 	= (!empty($subscriptionProductReachURLParam)) ? trim($subscriptionProductReachURLParam) : "";
			$parentId 		= (!empty($subscriptionListingIdURLParam)) ? trim($subscriptionListingIdURLParam) : "";
			$searchType     = (!empty($subscriptionSearchTypeURLParam)) ? trim($subscriptionSearchTypeURLParam) : "";
			
			//Check if the specified location and category is valid for this subscription
			$locationCategoryCheck = $this->checkLocationCategoryAgainstSubscription($subscriptionId, $clientUserId, $locationId, $categoryId, $productReach);
			if($locationCategoryCheck['success'] == true){
				//Check if user has this subscription
				$userSubscriptionCheck = $this->searchSponsoredLib->checkIfUserHasSubscription($subscriptionId, $clientUserId);
				if($userSubscriptionCheck['success'] == true){
					$sponsorType = $this->searchSponsoredLib->getSponsorTypeByProductId($userSubscriptionCheck['subscription_details']);
					$params = array(
									"category_id" 	=> $categoryId,
									"location_id" 	=> $locationId,
									"product_reach" => $productReach,
									"sponsor_type" 	=> $sponsorType
									);
					$liveSponsoredResults = $this->searchSponsoredLib->getLiveSponsoredResults($params); //Get sponsored results from DB according to the condition
					//Check if maximum limit reached for sponsor type
					$maxLimitReached = $this->searchSponsoredLib->checkIfMaxLimitCriteriaReachedForSponsored($sponsorType, $liveSponsoredResults);
					if($maxLimitReached == false){
						$courseIdsURLParam = $this->security->xss_clean($_REQUEST['courses']);
						$tempCourses = trim(trim($courseIdsURLParam), ",");
						$tempCourseList = explode(",", $tempCourses);
						$tempCourseCount = count($tempCourseList);
						$maxLimitReaching = $this->searchSponsoredLib->checkIfMaxLimitReaching($sponsorType, $tempCourseCount);
						if($maxLimitReaching == false){
							//MAX limit didn't reached yet
							$params = array(
										"parent_id" 	=> $parentId,
										"parent_type"	=> "institute",
										"category_id" 	=> $categoryId,
										"location_id" 	=> $locationId,
										"product_reach" => $productReach,
										"sponsor_type" 	=> $sponsorType
										);
							$liveSponsoredResults = $this->searchSponsoredLib->getLiveSponsoredResults($params); //Get sponsored results from DB according to the condition
							$courseIdsURLParam = $this->security->xss_clean($_REQUEST['courses']);
							$courses = trim(trim($courseIdsURLParam), ",");
							$courseList = explode(",", $courses);
							//Check If some of the listings already set as sponsored
							$listingAlreadySet = $this->searchSponsoredLib->checkIfListingAlreadySetSponsored($liveSponsoredResults, $courseList, "course");
							if($listingAlreadySet['success'] == true){
								$userSubscriptionDetails = $userSubscriptionCheck['subscription_details']; //user subscription details
								$remainingQuantity = $userSubscriptionDetails['BaseProdRemainingQuantity'];
								$baseProductId 	   = $userSubscriptionDetails['BaseProductId'];
								//Check for subscription points quantity
								if($remainingQuantity >= count($courseList)){
									$data = $this->searchSponsoredLib->prepareAssociativeListForInsert($searchType, $userSubscriptionDetails, $this->userid, $_REQUEST);
									if($data !== false && !empty($data)){
										$searchModel = new SearchModel();
										//Insert sponsored listings in DB
										$insertStatus = $searchModel->insertSponsoredListing($data);
										if($insertStatus === true){
											//If insert is successful than consume the subscriptions
											$subscriptionClientObj = new Subscription_client();
											$startDate = $data[0]['subscription_start_date'];
											$endDate   = $data[0]['subscription_end_date'];
											foreach($courseList as $course){
												$remainingQuantity--;
												//Consume subscription course wise.
												$consumeResult = $subscriptionClientObj->consumeSubscription(1, $subscriptionId, $remainingQuantity, $clientUserId, $this->userid, $baseProductId, $course, 'course', $startDate, $endDate);
											}
											$objSumsProduct =  new Sums_Product_client();
											$updatedSubscriptionDetails = $objSumsProduct->getAllSubscriptionsForUser(1, array('userId' => $clientUserId));
											
											$returnValue['error'] 		 = "0";
											$returnValue['success'] 	 = "1";
											$returnValue['count'] 		 = count($courseList);
											$returnValue['bmskey'] 		 = $data[0]['bmskey'];
											$returnValue['remainingQuantity'] = $remainingQuantity;
											$returnValue['updatedSubscriptionDetails'] = $updatedSubscriptionDetails;
											foreach($data as $dVal){
												if(array_key_exists('listing_type', $dVal) && array_key_exists('listing_id', $dVal)){
													$indexingType = $dVal['listing_type'];
													$validIndexTypes = array('course', 'institute');
													if(in_array($indexingType, $validIndexTypes)){
														modules::run('search/Indexer/addToQueue', $dVal['listing_id'], $indexingType, 'index');
													}
												}
											}
										} else {
											//insert sponsored listings failed DB error 
											$returnValue['error'] 			= "1";
											$returnValue['error_type'][] 	= "INSERT_SPONSORED_LISTING_FAILED";
										}
									} else {
										//associative data list is not proper 
										$returnValue['error'] 			= "1";
										$returnValue['error_type'][] 	= "DATA_IMPROPER";
									}
								} else {
									//User don't have enough subscription point left 
									$returnValue['error'] 			= "1";
									$returnValue['error_type'][] 	= "NOT_ENOUGH_SUBSCRIPTION_POINTS";
								}
							} else {
								////Listing already set as sponsored 
								$returnValue['error'] 				= "1";
								$returnValue['error_type'][] 		= "LISTING_ALREADY_SPONSORED_SET";
								$returnValue['sponsored_listing'] 	= $listingAlreadySet['listings_already_set'];
							}
						} else {
							$returnValue['error'] 			= "1";
							$returnValue['error_type'][] 	= "MAX_LIMIT_WILL_REACH";
						}
					} else {
						$returnValue['error'] 			= "1";
						$returnValue['error_type'][] 	= "MAX_LIMIT_REACHED_FOR_SPONSOR_TYPE";
					}
				} else {
					//User don't have this subscription or may be expired.
					$returnValue['error'] 		= "1";
					$returnValue['error_type'] 	= $userSubscriptionCheck['error_keys'];
				}
			} else {
				//Location and category specified for subscription are not valid.
				$returnValue['error'] 		= "1";
				$returnValue['error_type'] 	= $locationCategoryCheck['error_keys'];
			}
		} else {
			//Not all required params are present for this subscription to use
			$returnValue['error'] 			= "1";
			$returnValue['error_type'][] 	= "NOT_ALL_REQD_PARAMS";
		}
		$returnValue['sponsor_type'] = $sponsorType;
		
		echo json_encode($returnValue);
	}
	
	public function checkLocationCategoryAgainstSubscription($subscriptionId = NULL, $clientUserId = NULL, $locationId = NULL, $categoryId = NULL, $productReach = "national"){
		$returnFlag = true;
		$errorKeys = array();
		if($subscriptionId != NULL && $locationId != NULL && $categoryId != NULL){
			$cityCategoryInfo = $this->getCityCategoryForSubscription($subscriptionId, $clientUserId, 'functioncall');
			if(!empty($cityCategoryInfo)){
				$productReach = $cityCategoryInfo['flag'];
				switch($productReach){
					case 'national':
						$cities 	= $cityCategoryInfo['cities'];
						$citiIds 	= array_keys($cities);
						if(!in_array($locationId, $citiIds)){
							$errorKeys[] = "INVALID_LOCATION_FOR_SUBSCRIPTION";
							$returnFlag  = false;
						}
						
						$subCategoryIds = array();
						$categories = $cityCategoryInfo['categories'];
						foreach($categories as $mainCategoryId => $mainCatData){
							$subCategories 		= $mainCatData['subcategories'];
							$subCategoryIds 	= array_merge($subCategoryIds, array_keys($subCategories));
						}
						
						if(!in_array($categoryId, $subCategoryIds)){
							$errorKeys[] = "INVALID_CATEGORY_FOR_SUBSCRIPTION";
							$returnFlag  = false;
						}
						break;
						
					case 'studyabroad':
						$countries 	= $cityCategoryInfo['countries'];
						$countryIds = array_keys($countries);
						if(!in_array($locationId, $countryIds)){
							$errorKeys[] = "INVALID_LOCATION_FOR_SUBSCRIPTION";
							$returnFlag  = false;
						}
						
						$parentCategoryIds = array_keys($cityCategoryInfo['categories']);
						if(!in_array($categoryId, $parentCategoryIds)){
							$errorKeys[] = "INVALID_CATEGORY_FOR_SUBSCRIPTION";
							$returnFlag  = false;
						}
						break;
				}
			}
		}
		$returnArr = array();
		$returnArr['success'] 	 = $returnFlag;
		$returnArr['error_keys'] = $errorKeys;
		return $returnArr;
	}
	
	public function getBMSKeysByInstitute($instituteId = array(), $callType = "ajax"){
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
		$locationBuilder = new LocationBuilder();
		$locationRepo = $locationBuilder->getLocationRepository();
		$params = array(
						"parent_id" 	=> $instituteId,
						"parent_type"	=> "institute",
						);
		$liveSponsoredResults = $this->searchSponsoredLib->getLiveSponsoredResults($params); //Get sponsored results from DB according to the condition
		$listingBuilder = new ListingBuilder();
		$courseRepo = $listingBuilder->getCourseRepository();
		$bannerCourses = array();
		$featuredCourses = array();
		foreach($liveSponsoredResults as $sponsoredResult){
			if($sponsoredResult['parent_type'] == "institute" && $sponsoredResult['listing_type'] == "course" && !empty($sponsoredResult['bmskey'])){
				$data = array();
				$courseId = $sponsoredResult['listing_id'];
				$locationId = $sponsoredResult['location_id'];
				$categoryId = $sponsoredResult['category_id'];
				$locationObj = $locationRepo->findCity($locationId);
				$locationName = "";
				$categoryName = "";
				if($locationObj){
					$locationName = $locationObj->getName();
				}
				$categoryObj = $categoryRepository->find($categoryId);
				if($categoryObj){
					$categoryName = $categoryObj->getName();
				}
				$data = array();
				$data['course_id'] = $courseId;
				$data['bmskey']    = $sponsoredResult['bmskey'];
				$courseObj = $courseRepo->find($courseId);
				$data['course_name'] = $courseObj->getName();
				$data['category_name'] = $categoryName;
				$data['location_name'] = $locationName;
				if($sponsoredResult['sponsor_type'] == "featured"){
					array_push($featuredCourses, $data);
				} else if($sponsoredResult['sponsor_type'] == "banner"){
					array_push($bannerCourses, $data);
				}
			}
		}
		$returnData = array();
		$returnData['banner'] = $bannerCourses;
		$returnData['featured'] = $featuredCourses;
		if($callType == "ajax"){
			echo json_encode($returnData);	
		} else {
			return $returnData;
		}
	}
	
}
