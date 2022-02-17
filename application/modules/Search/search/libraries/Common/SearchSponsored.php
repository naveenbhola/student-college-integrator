<?php

class SearchSponsored {
	
	private $_ci;
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->config->load('search_config');
		$this->_ci->load->builder('SearchBuilder');
		$this->_ci->load->builder('CategoryBuilder','categoryList');
		$this->_ci->load->builder('ListingBuilder','listing');
		$this->_ci->load->builder("LocationBuilder", "location");
		$this->_ci->load->model('search/SearchModel', '', true);
		$this->_ci->load->library(array('sums_manage_client', 'sums_product_client', 'Category_list_client', 'register_client', 'Subscription_client'));
		
		$this->searchCommonLib 	   = SearchBuilder::getSearchCommon();
		$this->config = $this->_ci->config;
	}
	
	public function checkIfUserHasSubscription($subscriptionId = NULL, $clientUserId = NULL){
		$errorKeys = array();
		$userSubscriptionDetails = array();
		if($subscriptionId != NULL && $clientUserId != NULL){
			$sumsProductObject =  new Sums_Product_client();
			$userSubscriptions = $sumsProductObject->getAllSubscriptionsForUser(1, array('userId'=>$clientUserId));
			$subscriptionPresent = false;
			foreach($userSubscriptions as $subscriptionKey => $subscriptionDetails){
				if($subscriptionKey == $subscriptionId){
					$userSubscriptionDetails 	=  $subscriptionDetails;
					$subscriptionPresent 	= true;
					$currentDate 		  	= strtotime(date("Y-m-d"));
					$subscriptionEndDate  	= strtotime($subscriptionDetails['SubscriptionEndDate']);
					$subscriptionStartDate  = strtotime($subscriptionDetails['SubscriptionStartDate']);
					if($currentDate < $subscriptionStartDate){
						$errorKeys[] = "SUBSCRIPTION_NOT_STARTED_YET";
					}
					if($currentDate > $subscriptionEndDate){
						$errorKeys[] = "SUBSCRIPTION_EXPIRED";
					}
				}
			}
			if(!$subscriptionPresent){
				$errorKeys[] = "USER_DONT_HAVE_SUBSCRIPTION";
			}
		} else {
			$errorKeys[] = "INVALID_INPUTS_IN_CHECKIFUSERHASSUBSCRIPTION";
		}
		$returnArr = array();
		if($subscriptionPresent == true){
			if(count($errorKeys) > 0){
				$returnArr['success'] 	 = false;
				$returnArr['error_keys'] = $errorKeys;
			} else {
				$returnArr['success'] 	 = true;
				$returnArr['error_keys'] = array();
				$returnArr['subscription_details'] = $userSubscriptionDetails;
			}
		} else {
			$returnArr['success'] 	 = false;
			$returnArr['error_keys'] = $errorKeys;
		}
		return $returnArr;
	}
	
	public function getLiveSponsoredResults($params){
		$returnList = array();
		$searchModel = new SearchModel();
		$searchListings = $searchModel->getLiveSponsoredResults($params);
		return $searchListings;
	}
	
	public function prepareAssociativeListForInsert($searchType, $subscriptionDetails, $sumsUserId = "", $params = array()){
		$returnArr = false;
		if(!empty($params)){
			$data = array();
			switch($searchType){
				case 'course':
					$courseIdsURLParam = $this->_ci->security->xss_clean($_REQUEST['courses']);
					$courses = trim(trim($courseIdsURLParam), ",");
					$courseList = explode(",", $courses);
					$dateString = $params['sp_start_year'] ."-". $params['sp_start_month'] ."-". $params['sp_start_day'];
					$sponsorType = $this->getSponsorTypeByProductId($subscriptionDetails);
					if($sponsorType !== false){
						foreach($courseList as $course){
							$rand = rand(1, 1000);
							$bmsKey = "";
							if($sponsorType == "featured"){
								$bmsKey = "shiksha_sfp_".$params['sp_listing_id']."_".$params['sp_category_id']."_".$params['sp_location_id']."_".time() . $rand;
							} else if($sponsorType == "banner"){
								$bmsKey = "shiksha_sbp_".$params['sp_listing_id']."_".$params['sp_category_id']."_".$params['sp_location_id']."_".time() . $rand;
							}
							$dataArr = array();
							$dataArr['listing_id'] 				= $course;
							$dataArr['listing_type'] 			= 'course';
							$dataArr['parent_type'] 			= 'institute';
							$dataArr['parent_id'] 				= $params['sp_listing_id'];
							$dataArr['search_type'] 			= $params['sp_search_type'];
							$dataArr['sponsor_type'] 			= $sponsorType;
							$dataArr['category_id'] 			= $params['sp_category_id'];
							$dataArr['location_id'] 			= $params['sp_location_id'];
							$dataArr['product_reach'] 			= $params['sp_product_reach'];
							$dataArr['set_userid'] 				= $sumsUserId;
							$dataArr['subscription_start_date'] =  date('Y-m-d H:i:s');
							$dataArr['subscription_end_date'] 	= $subscriptionDetails['SubscriptionEndDate'];
							$dataArr['subscription_id'] 		= $subscriptionDetails['SubscriptionId'];
							$dataArr['bmskey']					= $bmsKey;
							$data[] = $dataArr;
						}	
					}
					break;
			}
			$returnArr = $data;
		}
		return $returnArr;
	}
	
	public function getSponsorTypeByProductId($subscriptionDetails = array()){
		$sponsorType = false;
		$baseProductId = $subscriptionDetails['BaseProductId'];
		$sponsoredProductKeys = $this->config->item('sponsored_base_product_keys');
		$featuredProductKeys  = $this->config->item('featured_base_product_keys');
		$bannerProductKeys  = $this->config->item('banner_base_product_keys');
		foreach($sponsoredProductKeys as $sponsoredProductKey){
			$productId = $this->config->item($sponsoredProductKey);
			if($productId == $baseProductId){
				$sponsorType = "sponsored";
				break;
			}
		}
		
		if($sponsorType == false){
			foreach($featuredProductKeys as $featuredProductKey){
				$productId = $this->config->item($featuredProductKey);
				if($productId == $baseProductId){
					$sponsorType = "featured";
					break;
				}
			}	
		}
		
		if($sponsorType == false){
			foreach($bannerProductKeys as $bannerProductKey){
				$productId = $this->config->item($bannerProductKey);
				if($productId == $baseProductId){
					$sponsorType = "banner";
					break;
				}
			}	
		}
		
		return $sponsorType;
	}
	
	public function checkIfListingAlreadySetSponsored($liveSponsoredResults = array(), $listingIds = array(), $listingType = "course"){
		$listingAlreadySetSponsored = array();
		foreach($liveSponsoredResults as $result){
			foreach($listingIds as $listingId){
				if($listingId == $result['listing_id'] && $listingType == $result['listing_type']){
					$listingAlreadySetSponsored[] = $listingId;
				}
			}
		}
		$returnArray = array();
		$returnArray['success'] = true;
		if(count($listingAlreadySetSponsored) > 0){
			$returnArray['success'] = false;
			$returnArray['listings_already_set'] = array_unique($listingAlreadySetSponsored);
		}
		return $returnArray;
	}
	
	public function checkIfMaxLimitCriteriaReachedForSponsored($sponsorType, $liveSponsoredResults = array()){
		$maxLimitReached = false;
		$uniqueParentId = array();
		foreach($liveSponsoredResults as $result){
			if(!in_array($result['parent_id'], $uniqueParentId)){
				array_push($uniqueParentId, $result['parent_id']);
			}
		}
		if($sponsorType == "featured"){
			$maxLimit = $this->config->item('max_featured_institutes_for_cat_loc_tier');
		}
		if($sponsorType == "sponsored"){
			$maxLimit = $this->config->item('max_sponsored_institutes_for_cat_loc_tier');
		}
		if($sponsorType == "banner"){
			$maxLimit = $this->config->item('max_banner_institutes_for_cat_loc_tier');
		}
		
		if(count($uniqueParentId) >= $maxLimit ){
			$maxLimitReached = true;
		}
		return $maxLimitReached;
	}
	
	public function checkIfMaxLimitReaching($sponsorType, $coursesCount){
		$maxLimitReached = false;
		if($sponsorType == "featured"){
			$maxLimit = $this->config->item('max_featured_institutes_for_cat_loc_tier');
		}
		if($sponsorType == "sponsored"){
			$maxLimit = $this->config->item('max_sponsored_institutes_for_cat_loc_tier');
		}
		if($sponsorType == "banner"){
			$maxLimit = $this->config->item('max_banner_institutes_for_cat_loc_tier');
		}
		if($coursesCount > $maxLimit){
			$maxLimitReached = true;
		}
		return $maxLimitReached;
	}
	
	public function getCityCategoryForSearchProduct($baseProductId = NULL){
		$cityList = array();
		$categoryList = array();
		$countryList = array();
		$flag = "national";
		if(!empty($baseProductId)){
			$locationBuilder = new LocationBuilder();
			$locationRepo = $locationBuilder->getLocationRepository();
			$categoryBuilder = new CategoryBuilder();
			$categoryRepo = $categoryBuilder->getCategoryRepository();
			$categoryListObj = new Category_list_client();
			
			$tier1CountryBaseProductIds = $this->getSAProductsByCountryTier(1);
			$tier2CountryBaseProductIds = $this->getSAProductsByCountryTier(2);
			
			$tier1CategoryBaseProductsIds = $this->getSAProductsByCategoryTier(1);
			$tier2CategoryBaseProductsIds = $this->getSAProductsByCategoryTier(2);
			$tier3CategoryBaseProductsIds = $this->getSAProductsByCategoryTier(3);
			$tier4CategoryBaseProductsIds = $this->getSAProductsByCategoryTier(4);
			$tier5CategoryBaseProductsIds = $this->getSAProductsByCategoryTier(5);
			
			$searchModel = new SearchModel();
			$subcategoryList = array();
			switch($baseProductId){
				/* National Products */
				case $this->config->item('ssp_india_subcat1_tier1'):
				case $this->config->item('sfp_india_subcat1_tier1'):
				case $this->config->item('sbp_india_subcat1_tier1'):
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(1);
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(1), 2);
					break;
				
				case $this->config->item('ssp_india_subcat1_tier2'):
				case $this->config->item('sfp_india_subcat1_tier2'):
				case $this->config->item('sbp_india_subcat1_tier2'):
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(1);
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(2), 2);
					break;
				
				case $this->config->item('ssp_india_subcat1_tier3'):
				case $this->config->item('sfp_india_subcat1_tier3'):
				case $this->config->item('sbp_india_subcat1_tier3'):
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(1);
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(3), 2);
					break;
				
				case $this->config->item('ssp_india_subcat2_tier1'):
				case $this->config->item('sfp_india_subcat2_tier1'):
				case $this->config->item('sbp_india_subcat2_tier1'):
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(1), 2);
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(2);
					break;
				
				case $this->config->item('ssp_india_subcat2_tier2'):
				case $this->config->item('sfp_india_subcat2_tier2'):
				case $this->config->item('sbp_india_subcat2_tier2'):
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(2);
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(2), 2);
					break;
				
				case $this->config->item('ssp_india_subcat2_tier3'):
				case $this->config->item('sfp_india_subcat2_tier3'):
				case $this->config->item('sbp_india_subcat2_tier3'):
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(3), 2);
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(2);
					break;
				
				case $this->config->item('ssp_india_subcat3_tier1'):
				case $this->config->item('sfp_india_subcat3_tier1'):
				case $this->config->item('sbp_india_subcat3_tier1'):
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(1), 2);
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(3);
					break;
				
				case $this->config->item('ssp_india_subcat3_tier2'):
				case $this->config->item('sfp_india_subcat3_tier2'):
				case $this->config->item('sbp_india_subcat3_tier2'):
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(2), 2);
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(3);
					break;
				
				case $this->config->item('ssp_india_subcat3_tier3'):
				case $this->config->item('sfp_india_subcat3_tier3'):
				case $this->config->item('sbp_india_subcat3_tier3'):
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(3), 2);
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(3);
					break;
				
				case $this->config->item('ssp_india_subcat4_tier1'):
				case $this->config->item('sfp_india_subcat4_tier1'):
				case $this->config->item('sbp_india_subcat4_tier1'):
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(1), 2);
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(4);
					break;
				
				case $this->config->item('ssp_india_subcat4_tier2'):
				case $this->config->item('sfp_india_subcat4_tier2'):
				case $this->config->item('sbp_india_subcat4_tier2'):
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(2), 2);
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(4);
					break;
				
				case $this->config->item('ssp_india_subcat4_tier3'):
				case $this->config->item('sfp_india_subcat4_tier3'):
				case $this->config->item('sbp_india_subcat4_tier3'):
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(3), 2);
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(4);
					break;
				
				case $this->config->item('ssp_india_subcat5_tier1'):
				case $this->config->item('sfp_india_subcat5_tier1'):
				case $this->config->item('sbp_india_subcat5_tier1'):
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(1), 2);
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(5);
					break;
				
				case $this->config->item('ssp_india_subcat5_tier2'):
				case $this->config->item('sfp_india_subcat5_tier2'):
				case $this->config->item('sbp_india_subcat5_tier2'):
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(2), 2);
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(5);
					break;
				
				case $this->config->item('ssp_india_subcat5_tier3'):
				case $this->config->item('sfp_india_subcat5_tier3'):
				case $this->config->item('sbp_india_subcat5_tier3'):
					$cityList = $locationRepo->getCitiesByMultipleTiers(array(3), 2);
					$subcategoryList = $searchModel->getSubCategoriesListBasedOnTier(5);
					break;
				
				/* StudyAbroad products */
				default:
					$countryList = $locationRepo->getCountries();
					if(in_array($baseProductId, $tier1CategoryBaseProductsIds)){
						$parentCatlist = $categoryListObj->getCategoryBasedOnTier(1, 1);
					}
					if(in_array($baseProductId, $tier2CategoryBaseProductsIds)){
						$parentCatlist = $categoryListObj->getCategoryBasedOnTier(1, 2);
					}
					if(in_array($baseProductId, $tier3CategoryBaseProductsIds)){
						$parentCatlist = $categoryListObj->getCategoryBasedOnTier(1, 3);
					}
					if(in_array($baseProductId, $tier4CategoryBaseProductsIds)){
						$parentCatlist = $categoryListObj->getCategoryBasedOnTier(1, 4);
					}
					if(in_array($baseProductId, $tier5CategoryBaseProductsIds)){
						$parentCatlist = $categoryListObj->getCategoryBasedOnTier(1, 5);
					}
					$flag = "studyabroad";
					break;
			}
			
			//Prepare cities data
			//$virtualCityMapping = $this->config->item('virtual_city_mapping');
			$virtualCityMapping = $this->searchCommonLib->getVirtualCityMappingForSearch();
			
			$virtualCities = array();
			foreach($virtualCityMapping as $key => $value){
				$virtualCities = array_merge($virtualCities, $value);
			}
			
			$cities = array();
			if(!empty($cityList)){
				foreach($cityList as $tier => $citiInfo){
					foreach($citiInfo as $value){
						if(!in_array((int)trim($value->getId()), $virtualCities)){ //We don't want to show cities that comes under some virtual city mapping
							$cities[trim($value->getId())] = trim($value->getName());	
						}
					}
				}
			}
			
			//Prepare countries data
			$countries = array();
			if(!empty($countryList)){
				foreach($countryList as $key => $countryInfo){
					if(trim($countryInfo->getId()) != 2){
						if(in_array($baseProductId, $tier1CountryBaseProductIds)){
							if((int)$countryInfo->getTier() == 1){
								$countries[trim($countryInfo->getId())] = trim($countryInfo->getName());
							}
						}
						if(in_array($baseProductId, $tier2CountryBaseProductIds)){
							if((int)$countryInfo->getTier() == 2){
								$countries[trim($countryInfo->getId())] = trim($countryInfo->getName());
							}
						}
					}
				}
			}
			
			//Prepare category data
			$categoryList = array();
			if($flag == "national"){
				if(!empty($subcategoryList)){
					$parentCategoryIds = array();
					foreach($subcategoryList as $categoryData){
						$parentCategoryIds[] = $categoryData['parent_category_id'];
					}
					$parentCategoryIds = array_unique($parentCategoryIds);
					if(count($parentCategoryIds) > 0){
						$parentCategoryData = $searchModel->getCategoryDetail($parentCategoryIds);
						if(!empty($parentCategoryData)){
							foreach($parentCategoryData as $parentData){
								$tempCatData = array();
								$parentCategoryId 	= $parentData['category_id'];
								$parentCategoryName = $parentData['category_name'];
								$tempCatData = array();
								$tempCatData['parent_category_id'] = $parentCategoryId;
								$tempCatData['parent_category_name'] = trim($parentCategoryName);
								$tempCatData['subcategories'] = array();
								
								foreach($subcategoryList as $subcategoryData){
									if($subcategoryData['parent_category_id'] == $parentCategoryId){
										$tempCatData['subcategories'][$subcategoryData['category_id']] = $subcategoryData['category_name'];
									}
								}
								$categoryList[$parentCategoryId] = $tempCatData;
							}
						}
					}
				}
			} else if($flag == "studyabroad"){
				$categoryList = array();
				if(!empty($parentCatlist)){
					foreach($parentCatlist as $key => $value){
						$tempCatArray = array();
						if(!empty($value['categoryId'])){
							$parentCategoryId = trim($value['categoryId']);
						} else if(!empty($value['categoryID'])){
							$parentCategoryId = trim($value['categoryID']);
						}
						if($parentCategoryId != 1){
							$tempCatArray['parent_category_id'] = $parentCategoryId;
							$tempCatArray['parent_category_name'] = trim($value['categoryName']);
							$categoryList[$tempCatArray['parent_category_id']] = $tempCatArray;	
						}
					}
				}
			}
		}
		
		$returnValue = array();
		$returnValue['flag'] = $flag;
		$returnValue['cities'] = $cities;
		$returnValue['categories'] = $categoryList;
		$returnValue['countries'] = $countries;
		return $returnValue;
	}
	
	public function increaseSponsoredListingImpressions($courseIds = array()){
		if(!empty($courseIds)){
			$searchModel = new SearchModel();
			foreach($courseIds as $sponsorType => $listingIds){
				$searchModel->increaseSponsoredListingImpressions($sponsorType, $listingIds, 'course');
			}
		}
	}
	
	public function getSAProductsByCountryTier($tier = 1){
		$countryProducts = array();
		switch($tier){
			case 1:
				$countryProducts = array(
										$this->config->item('ssp_abroad_category1_country1'),
										$this->config->item('ssp_abroad_category2_country1'),
										$this->config->item('ssp_abroad_category3_country1'),
										$this->config->item('ssp_abroad_category4_country1'),
										$this->config->item('ssp_abroad_category5_country1'),
										
										$this->config->item('sfp_abroad_category1_country1'),
										$this->config->item('sfp_abroad_category2_country1'),
										$this->config->item('sfp_abroad_category3_country1'),
										$this->config->item('sfp_abroad_category4_country1'),
										$this->config->item('sfp_abroad_category5_country1'),
										
										$this->config->item('sbp_abroad_category1_country1'),
										$this->config->item('sbp_abroad_category2_country1'),
										$this->config->item('sbp_abroad_category3_country1'),
										$this->config->item('sbp_abroad_category4_country1'),
										$this->config->item('sbp_abroad_category5_country1'),
								);
				break;
			
			case 2:
				$countryProducts = array(
										$this->config->item('ssp_abroad_category1_country2'),
										$this->config->item('ssp_abroad_category2_country2'),
										$this->config->item('ssp_abroad_category3_country2'),
										$this->config->item('ssp_abroad_category4_country2'),
										$this->config->item('ssp_abroad_category5_country2'),
										
										$this->config->item('sfp_abroad_category1_country2'),
										$this->config->item('sfp_abroad_category2_country2'),
										$this->config->item('sfp_abroad_category3_country2'),
										$this->config->item('sfp_abroad_category4_country2'),
										$this->config->item('sfp_abroad_category5_country2'),
										
										$this->config->item('sbp_abroad_category1_country2'),
										$this->config->item('sbp_abroad_category2_country2'),
										$this->config->item('sbp_abroad_category3_country2'),
										$this->config->item('sbp_abroad_category4_country2'),
										$this->config->item('sbp_abroad_category5_country2'),
									);
				break;
		}
		return $countryProducts;
	}
	
	public function getSAProductsByCategoryTier($tier = 1){
		$categoryProducts = array();
		switch($tier){
			case 1:
				$categoryProducts = array(
										$this->config->item('ssp_abroad_category1_country1'),
										$this->config->item('sfp_abroad_category1_country1'),
										$this->config->item('sbp_abroad_category1_country1'),
										$this->config->item('ssp_abroad_category1_country2'),
										$this->config->item('sfp_abroad_category1_country2'),
										$this->config->item('sbp_abroad_category1_country2'),
									);
				break;
				
			case 2:
				$categoryProducts = array(
										$this->config->item('ssp_abroad_category2_country1'),
										$this->config->item('sfp_abroad_category2_country1'),
										$this->config->item('sbp_abroad_category2_country1'),
										$this->config->item('ssp_abroad_category2_country2'),
										$this->config->item('sfp_abroad_category2_country2'),
										$this->config->item('sbp_abroad_category2_country2'),
									);
				break;
			
			case 3:
				$categoryProducts = array(
										$this->config->item('ssp_abroad_category3_country1'),
										$this->config->item('sfp_abroad_category3_country1'),
										$this->config->item('sbp_abroad_category3_country1'),
										$this->config->item('ssp_abroad_category3_country2'),
										$this->config->item('sfp_abroad_category3_country2'),
										$this->config->item('sbp_abroad_category3_country2'),
									);
				break;
			
			case 4:
				$categoryProducts = array(
										$this->config->item('ssp_abroad_category4_country1'),
										$this->config->item('sfp_abroad_category4_country1'),
										$this->config->item('sbp_abroad_category4_country1'),
										$this->config->item('ssp_abroad_category4_country2'),
										$this->config->item('sfp_abroad_category4_country2'),
										$this->config->item('sbp_abroad_category4_country2'),
									);
				break;
			
			case 5:
				$categoryProducts = array(
										$this->config->item('ssp_abroad_category5_country1'),
										$this->config->item('sfp_abroad_category5_country1'),
										$this->config->item('sbp_abroad_category5_country1'),
										$this->config->item('ssp_abroad_category5_country2'),
										$this->config->item('sfp_abroad_category5_country2'),
										$this->config->item('sbp_abroad_category5_country2'),
									);
				break;
		}
		return $categoryProducts;
	}
}
