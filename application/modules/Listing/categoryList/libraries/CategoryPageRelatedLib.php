<?php
define("ENT_MAX_LINKS_LIMIT"		  , 4);
define("CATEGORY_MAX_LINKS_LIMIT"	  , 5);

define("LOCATION_ONLY"			      , 0);
define("EXAM_ONLY"			          , 1);
define("SPECIALIZATION_ONLY"		  , 2);
define("EXAM_SPECIALIZATION_ONLY"	  , 3);
define("FEES_ONLY"                    , 4);
define("EXAM_FEES_ONLY"               , 5);
define("SPECIALIZATION_FEES_ONLY"     , 6);
define("EXAM_SPECIALIZATION_FEES_ONLY", 7);

class CategoryPageRelatedLib
{
	public function __construct($categoryPageURLQueryString = '', $newUrlFlag = false) {

 		$this->CI 			= & get_instance();
		
		$this->CI->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		$this->CI->config->load('categoryPageConfig');
		$this->CI->load->library("categoryList/cache/CategoryPageCache");
		$this->CI->load->helper('categoryList/category_page');
		
		$this->rankingURLManager  	= RankingPageBuilder::getURLManager();
		$this->categoryPageCache	= new CategoryPageCache();
		$this->pageType			= LOCATION_ONLY;
		$this->uniqueLinksArr 		= array();
	}
	
	/**
	* Purpose : Method to get related ranking links for given category page request
	* Params  : 1. $categoryPageRequest - Category page request(identifier)
	* Author  : Romil
	* Note 	  : Fees and affiliation dimensions of category page don't make any difference in ranking page links.
	*/
	public function getRankingPageRelatedLinks($categoryPageRequest){
	    
	    $this->uniqueLinksArr 		= array();
	    $this->categorypagemodel 		= $this->CI->load->model("categorypagemodel");

	    // fetch the category page identifiers
	    $this->subcategoryId		= $categoryPageRequest->getSubCategoryId();
	    $this->examName 			= $categoryPageRequest->getExamName();
	    $this->ldbCourseId 			= $categoryPageRequest->getLDBCourseId();
	    $this->cityId 			= $categoryPageRequest->getCityId();
	    $this->stateId 			= $categoryPageRequest->getStateId();
	    $this->isMultilocationPage 		= $categoryPageRequest->isMultilocationPage();

	    // determine the category page type
	    if($this->examName != '' && $this->ldbCourseId != 1)
		$this->pageType = EXAM_SPECIALIZATION_ONLY;
	    else if($this->examName != '')
		$this->pageType = EXAM_ONLY;
	    else if($this->ldbCourseId != 1)
		$this->pageType = SPECIALIZATION_ONLY;
	    else
		    $this->pageType = LOCATION_ONLY;

	    // if category page is a multilocation page then get all the links by using each location(selected) one-by-one(in order of their selection)
	    if($this->isMultilocationPage)
	    {
		$locationOrder = $categoryPageRequest->getUserPreferredLocationOrder();
		
		foreach($locationOrder as $locationOrderRow)
		{
		    if($locationOrderRow['location_type'] == 'city'){
			    $this->cityId  = $locationOrderRow['location_id'];
			    $this->stateId = 0;
		    }
		    else{
			    $this->cityId  = 0;
			    $this->stateId = $locationOrderRow['location_id'];
		    }
		    $this->getRelatedRankingLinks($categoryPageRequest);
		}
	    }
	    else
	    {
		$cacheData = $this->categoryPageCache->getRankingRelatedLinks($categoryPageRequest);
		if(!$cacheData){
		    $this->getRelatedRankingLinks($categoryPageRequest);
		}
		else{
		    return $cacheData;
		}
	    }

	    $this->uniqueLinksArr = array_slice($this->uniqueLinksArr, 0 ,ENT_MAX_LINKS_LIMIT);

	    // get the static ranking link to be shown same on each subcategory page
	    $this->getStaticLink($categoryPageRequest);

	    // build the ranking links and their title
	    $urls = array();
	    foreach($this->uniqueLinksArr as $row)
	    {
		$urls[] = $this->getRankingPageUrl($row);
	    }
	    
	    if(!$this->isMultilocationPage)
		$this->categoryPageCache->storeRankingRelatedLinks($categoryPageRequest, $urls);

	    return $urls;
	}
	
	private function getRelatedRankingLinks($categoryPageRequest){

	    /******************* Stage 1 **********************
	     * 1. LOCATION_ONLY 		: location
	     * 2. EXAM_ONLY			: location + exam
	     * 3. SPECIALIZATION_ONLY		: location + specialization
	     * 4. EXAM_SPECIALIZATION_ONLY 	: location + specialization + exam
	     */
	    $filters 				= array();
	    $filters['subcategoryId'] 		= $this->subcategoryId;
	    $filters['ldbCourseId'] 		= $this->ldbCourseId == 1 ? 0 : $this->ldbCourseId;
	    $filters['cityId'] 			= (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
	    $filters['stateId'] 		= (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
	    $filters['countryId'] 		= 0;
	    $filters['examName'] 		= $this->examName;
	    
	    $data 				= $this->categorypagemodel->getNonZeroRankingPages($filters);
	    $this->uniqueLinksArr 		= array_merge($this->uniqueLinksArr, $data);
	    
	    if(count($this->uniqueLinksArr) >= ENT_MAX_LINKS_LIMIT) return;
	    
	    /******************* Stage 2 **********************
	     * 1. LOCATION_ONLY 		: location + exam
	     * 2. EXAM_ONLY			: location + exam + specialization
	     * 3. SPECIALIZATION_ONLY		: location + specialization + exam
	     * 4. EXAM_SPECIALIZATION_ONLY 	: -
	     */
	    $filters 				= array();
	    $filters['subcategoryId'] 		= $this->subcategoryId;
	    $filters['cityId'] 			= (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
	    $filters['stateId'] 		= (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
	    $filters['countryId'] 		= 0;
	    
	    $data = array();
	    if($this->pageType == EXAM_ONLY){
		$filters['examName'] 		    	= $this->examName;
		$filters['specializationNeeded']    	= 1;
		$data 					= $this->categorypagemodel->getNonZeroRankingPages($filters);
		$this->uniqueLinksArr 		= array_merge($this->uniqueLinksArr, $data);
	    }
	    else if($this->pageType == SPECIALIZATION_ONLY){
		$filters['examNeeded'] 		    	= 1;
		$filters['ldbCourseId']    		= $this->ldbCourseId;
		$data 					= $this->categorypagemodel->getNonZeroRankingPages($filters);
		$this->uniqueLinksArr 		= array_merge($this->uniqueLinksArr, $data);
	    }
	    else if($this->pageType == LOCATION_ONLY){
		$filters['examNeeded'] 		    	= 1;
		$filters['ldbCourseId']    		= 0;
		$data 					= $this->categorypagemodel->getNonZeroRankingPages($filters);
		$this->uniqueLinksArr 		= array_merge($this->uniqueLinksArr, $data);
	    }
	    
	    if(count($this->uniqueLinksArr) >= ENT_MAX_LINKS_LIMIT) return;
	    
	    /******************* Stage 3 **********************
	     * 1. LOCATION_ONLY 		: exam
	     * 2. EXAM_ONLY			: exam + state(in case of city) / top 3 cities(in case of state)
	     * 3. SPECIALIZATION_ONLY		: specialization + state(in case of city) / top 3 cities(in case of state)
	     * 4. EXAM_SPECIALIZATION_ONLY 	: specialization + exam + state(in case of city) / top 3 cities(in case of state)
	     */
	    $this->CI->load->builder('LocationBuilder','location');
	    $locationBuilder = new LocationBuilder;
	    $locationRepository = $locationBuilder->getLocationRepository();
	    
	    $filters 				= array();
	    $filters['subcategoryId'] 		= $this->subcategoryId;
	    $filters['countryId'] 		= 0;
	    if(!(empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID)){
		// if city page then set its state
		
		// check for Virtual cities(hard-code checks as discussed with product)
		$stateId = 0;
		if($this->cityId == 10223)
		    $stateId = 128;
		else if($this->cityId == 10224)
		    $stateId = 114;
		else if($this->cityId == 12292)
		    $stateId = 134;
		else{
		    $cityObj = $locationRepository->findCity($this->cityId);
		    $stateId = $cityObj->getStateId();
		}
		
		$filters['cityId'] 		= 0;
		$filters['stateId'] 		= $stateId;
	    }
	    else if(!empty($this->stateId) && $this->stateId != CP_DEFAULT_VAL_STATE_ID){
		// else if state page then set its cities
		$this->cityIds = array();
		$cities = $locationRepository->getCitiesByState($this->stateId);
		foreach($cities as $cityobj)
		    $this->cityIds[] = $cityobj->getId();
		
		$filters['cityId'] 	    	= $this->cityIds;
		$filters['stateId'] 		= 0;
	    }
	    
	    if($this->pageType == EXAM_ONLY){
		$filters['examName'] 		    	= $this->examName;
		$filters['ldbCourseId']			= 0;
		$data 			= $this->categorypagemodel->getNonZeroRankingPages($filters);
		$this->uniqueLinksArr 	= array_merge($this->uniqueLinksArr, $data);
	    }
	    else if($this->pageType == SPECIALIZATION_ONLY){
		$filters['examName'] 			= '';
		$filters['ldbCourseId']    		= $this->ldbCourseId;
		$data 			= $this->categorypagemodel->getNonZeroRankingPages($filters);
		$this->uniqueLinksArr 	= array_merge($this->uniqueLinksArr, $data);
	    }
	    else if($this->pageType == LOCATION_ONLY && !$this->isMultilocationPage){
		$filters['examNeeded'] 	        	= 1;
		$filters['ldbCourseId']    		= 0;
		$filters['cityId'] 	    		= 0;
		$filters['stateId'] 			= 0;
		$filters['countryId'] 			= 2;
		$data 			= $this->categorypagemodel->getNonZeroRankingPages($filters);
		$this->uniqueLinksArr 	= array_merge($this->uniqueLinksArr, $data);
	    }
	    else if($this->pageType == EXAM_SPECIALIZATION_ONLY){
		$filters['examName'] 	        	= $this->examName;
		$filters['ldbCourseId']    		= $this->ldbCourseId;
		$data 			= $this->categorypagemodel->getNonZeroRankingPages($filters);
		$this->uniqueLinksArr 	= array_merge($this->uniqueLinksArr, $data);
	    }
	    
	    
	    if(count($this->uniqueLinksArr) >= ENT_MAX_LINKS_LIMIT) return;
	    
	    /******************* Stage 4 **********************
	     * 1. LOCATION_ONLY 		: -
	     * 2. EXAM_ONLY			: exam
	     * 3. SPECIALIZATION_ONLY		: specialization
	     * 4. EXAM_SPECIALIZATION_ONLY 	: specialization + exam
	     */
	    $filters 				= array();
	    $filters['subcategoryId'] 		= $this->subcategoryId;
	    $filters['cityId'] 			= 0;
	    $filters['stateId'] 		= 0;
	    $filters['countryId'] 		= 2;
	    
	    $data = array();
	    if($this->pageType == EXAM_ONLY){
		$filters['examName'] 		    	= $this->examName;
		$filters['ldbCourseId']			= 0;
		$data 					= $this->categorypagemodel->getNonZeroRankingPages($filters);
		$this->uniqueLinksArr 		= array_merge($this->uniqueLinksArr, $data);
	    }
	    else if($this->pageType == SPECIALIZATION_ONLY){
		$filters['examName'] 			= '';
		$filters['ldbCourseId']    		= $this->ldbCourseId;
		$data 					= $this->categorypagemodel->getNonZeroRankingPages($filters);
		$this->uniqueLinksArr 		= array_merge($this->uniqueLinksArr, $data);
	    }
	    else if($this->pageType == EXAM_SPECIALIZATION_ONLY){
		$filters['examName'] 	        	= $this->examName;
		$filters['ldbCourseId']    		= $this->ldbCourseId;
		$data 					= $this->categorypagemodel->getNonZeroRankingPages($filters);
		$this->uniqueLinksArr 		= array_merge($this->uniqueLinksArr, $data);
	    }
	    
	    if(count($this->uniqueLinksArr) >= ENT_MAX_LINKS_LIMIT) return;

	    /******************* Stage 5 **********************
	     * 1. LOCATION_ONLY 		: -
	     * 2. EXAM_ONLY			: -
	     * 3. SPECIALIZATION_ONLY		: specialization + exam
	     * 4. EXAM_SPECIALIZATION_ONLY 	: exam
	     */
	    $filters 				= array();
	    $filters['subcategoryId'] 		= $this->subcategoryId;
	    $filters['cityId'] 			= 0;
	    $filters['stateId'] 		= 0;
	    $filters['countryId'] 		= 2;
	    
	    $data = array();
	    if($this->pageType == SPECIALIZATION_ONLY){
		$filters['examNeeded'] 			= 1;
		$filters['ldbCourseId']    		= $this->ldbCourseId;
		$data 					= $this->categorypagemodel->getNonZeroRankingPages($filters);
		$this->uniqueLinksArr = array_merge($this->uniqueLinksArr, $data);
	    }
	    else if($this->pageType == EXAM_SPECIALIZATION_ONLY){
		$filters['examName'] 	        	= $this->examName;
		$filters['ldbCourseId']    		= 0;
		$data 					= $this->categorypagemodel->getNonZeroRankingPages($filters);
		$this->uniqueLinksArr = array_merge($this->uniqueLinksArr, $data);
	    }
	}

	function getStaticLink(){

	    $filters 				= array();
	    $filters['subcategoryId'] 		= $this->subcategoryId;
	    $filters['ldbCourseId'] 		= 0;
	    $filters['cityId'] 			= 0;
	    $filters['stateId'] 		= 0;
	    $filters['countryId'] 		= 2;
	    $filters['examName'] 		= '';
	    
	    $data = $this->categorypagemodel->getNonZeroRankingPages($filters, 1);
	    
	    $this->uniqueLinksArr = array_merge($this->uniqueLinksArr, $data);
	    
	}
	
	function getRankingPageUrl($row){

	    $pageIdentifier = $row['ranking_page_id']."-".$row['country_id']."-".$row['state_id']."-".$row['city_id']."-".$row['exam_id'];
	    $request = $this->rankingURLManager->getRankingPageRequest($pageIdentifier);

	    return $this->rankingURLManager->buildURL($request, 'urltitle', 1);
	}


	/**
	 * purpose to prepare related category links based on current url 
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-02-01
	 * @param  Object $categoryPageRequest current Url Request
	 * @return Array of category related links
	 */
	public function getCategoryPageRelatedLinksOld($categoryPageRequest){
		$this->uniqueLinksArr      = array();
		$this->categorypagemodel   = $this->CI->load->model("categorypagemodel");
		

		$this->categoryId          = $categoryPageRequest->getCategoryId();
		$this->subcategoryId       = $categoryPageRequest->getSubCategoryId();
		$this->examName            = $categoryPageRequest->getExamName();
		$this->fees                = ($categoryPageRequest->getFeesValue() != -1)? $categoryPageRequest->getFeesValue() : '';
		$this->ldbCourseId         = $categoryPageRequest->getLDBCourseId();
		$this->cityId              = $categoryPageRequest->getCityId();
		$this->stateId             = $categoryPageRequest->getStateId();
		$this->isMultilocationPage = $categoryPageRequest->isMultilocationPage();

		//_p($categoryPageRequest->getPageKey());
		
		// set pageType
		if($this->examName != '' && $this->ldbCourseId != 1 && $this->fees != '')
		$this->pageType = EXAM_SPECIALIZATION_FEES_ONLY;
		else if($this->examName != '' && $this->ldbCourseId != 1)
		$this->pageType = EXAM_SPECIALIZATION_ONLY;
		else if($this->ldbCourseId != 1 && $this->fees != '')
		$this->pageType = SPECIALIZATION_FEES_ONLY;
		else if($this->examName != '' && $this->fees != '')
		$this->pageType = EXAM_FEES_ONLY;
	    else if($this->examName != '')
		$this->pageType = EXAM_ONLY;
	    else if($this->fees != '')
		$this->pageType = FEES_ONLY;
		else if($this->ldbCourseId != 1)
		$this->pageType = SPECIALIZATION_ONLY;
	    else
		$this->pageType = LOCATION_ONLY;

	    // check for multi Loaction
	    if($this->isMultilocationPage)
	    {
	    	// get User selected multi locations
			$locationOrder = $categoryPageRequest->getUserPreferredLocationOrder();
		
			foreach($locationOrder as $locationOrderRow)
			{
			    if($locationOrderRow['location_type'] == 'city'){
				    $this->cityId  = $locationOrderRow['location_id'];
				    $this->stateId = 0;
			    }
			    else{
				    $this->cityId  = 0;
				    $this->stateId = $locationOrderRow['location_id'];
			    }
		    	$this->getRelatedCategoryLinks($categoryPageRequest);
			}
	    }else{
			$cacheData = $this->categoryPageCache->getCategoryRelatedLinks($categoryPageRequest);
			if(!$cacheData){
	    		$this->getRelatedCategoryLinks($categoryPageRequest);	
	    	}else{
	    		return $cacheData;
	    	}
	    }	
	    
		
	    // slicing unique links array
		$this->uniqueLinksArr = array_slice($this->uniqueLinksArr, 0 ,CATEGORY_MAX_LINKS_LIMIT);
		
		// array to store category related links
		$urls = array();

		foreach($this->uniqueLinksArr as $row)
	    {
	    	// get url and url title 
			$urls[] = $this->getCategoryPageUrl($row);
	    }
		
		if(!$this->isMultilocationPage){
			$this->categoryPageCache->storeCategoryRelatedLinks($categoryPageRequest, $urls);	
		}
		
	
	    return $urls;
		

	}


	/**
	 * Logic to get category related links
	 * Stage 1 :
	 * LOCATION_ONLY                : Location and exam (exam needed)
	 * EXAM_ONLY                    : Location, exam and fee (fee needed)
	 * FEES_ONLY                    : Location, fee and exam (exam needed)
	 * SPECIALIZATION_ONLY          : Location, specialization and exam (exam needed)
	 * EXAM_FEES_ONLY               : Location, specialization, exam and fee (specialization needed)
	 * SPECIALIZATION_FEES_ONLY     : Location, specialization, exam and fee (exam needed)
	 * EXAM_SPECIALIZATION_ONLY     : Location, specialization, exam and fee (fee needed)
	 * EXAM_SPECIALIZATION_FEES_ONLY: Location, specialization, exam and fee (city/state)
	 *
	 * Stage 2 :
	 * LOCATION_ONLY                : Location and fee (fee needed)
	 * EXAM_ONLY                    : Location, exam and specialization (specialization needed)
	 * FEES_ONLY                    : Location, fee and specialization (specialization needed)
	 * SPECIALIZATION_ONLY          : Location, fee, exam and specialization (fee and exam needed)
	 * EXAM_FEES_ONLY               : Location, fee, exam and specialization (specialization and city/state needed)
	 * SPECIALIZATION_FEES_ONLY     : Location, fee and specialization (city/state needed)
	 * EXAM_SPECIALIZATION_ONLY     : Location, exam and specialization (city/state needed)
	 * EXAM_SPECIALIZATION_FEES_ONLY: Location, exam and specialization (city/state needed)
	 *
	 * Stage 3 :
	 * LOCATION_ONLY                : NONE
	 * EXAM_ONLY                    : Location and exam (city/state needed)
	 * FEES_ONLY                    : Location and fee (city/state needed)
	 * SPECIALIZATION_ONLY          : Location and specialization (city/state needed)
	 * EXAM_FEES_ONLY               : Location, exam and fee (city/state needed)
	 * SPECIALIZATION_FEES_ONLY     : Location
	 * EXAM_SPECIALIZATION_ONLY     : Location
	 * EXAM_SPECIALIZATION_FEES_ONLY: Location
	 *
	 * Stage 4 :
	 * LOCATION_ONLY                : NONE
	 * EXAM_ONLY                    : NONE
	 * FEES_ONLY                    : Location,fee and exam (exam and city/state needed)
	 * SPECIALIZATION_ONLY          : NONE
 	 * EXAM_FEES_ONLY               : NONE
	 * SPECIALIZATION_FEES_ONLY     : Location and exam (exam needed)
	 * EXAM_SPECIALIZATION_ONLY     : Location and exam (exam needed)
	 * EXAM_SPECIALIZATION_FEES_ONLY: Location and exam (exam needed)
	 *
	 *
	 * Stage 5 :
	 * LOCATION_ONLY                : NONE
	 * EXAM_ONLY                    : NONE
	 * FEES_ONLY                    : NONE
	 * SPECIALIZATION_ONLY          : NONE
	 * EXAM_FEES_ONLY               : NONE
	 * SPECIALIZATION_FEES_ONLY     : Location (city/state needed)
	 * EXAM_SPECIALIZATION_ONLY     : Location (city/state needed)
	 * EXAM_SPECIALIZATION_FEES_ONLY: Location (city/state needed)
	 * 
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-02-01
	 * @param  Object $categoryPageRequest current Url Request
	 * @return [type]                          [description]
	 */
	public function getRelatedCategoryLinks($categoryPageRequest){
		

		///////////////
		// Stage 1 //
		///////////////
		
		$filters                  = array();
		$filters['categoryId']    = $this->categoryId;
		$filters['subCategoryId'] = $this->subcategoryId;
		$filters['countryId']     = 2;
		$filters['limit']         = CATEGORY_MAX_LINKS_LIMIT;
		
		if ($this->pageType == LOCATION_ONLY) {
			// loction only
			$filters['fees']        = 'none';
			$filters['ldbCourseId'] = 1;
			
			$filters['cityId']      = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']     = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			
			$filters['examNeeded']  = 1;

		} elseif ($this->pageType == EXAM_ONLY) {
			$filters['ldbCourseId'] = 1;
			
			$filters['cityId']      = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']     = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			$filters['examName']    = $this->examName;

			$filters['feesNeeded']  = 1;
			
		} elseif ($this->pageType == FEES_ONLY) {
			$filters['ldbCourseId'] = 1;
			
			$filters['cityId']      = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']     = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			$filters['fees']        = $this->fees;

			$filters['examNeeded']  = 1;

		} elseif ($this->pageType == SPECIALIZATION_ONLY) {
			$filters['fees']        = 'none';

			$filters['ldbCourseId'] = $this->ldbCourseId;
			$filters['cityId']      = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']     = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			
			$filters['examNeeded']  = 1;

		} elseif ($this->pageType == EXAM_FEES_ONLY) {
			
			$filters['examName']             = $this->examName;
			$filters['fees']                 = $this->fees;
			$filters['cityId']               = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']              = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			
			$filters['specializationNeeded'] = 1;	

		} elseif ($this->pageType == SPECIALIZATION_FEES_ONLY) {
			
			$filters['fees']        = $this->fees;
			$filters['ldbCourseId'] = $this->ldbCourseId;
			$filters['cityId']      = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']     = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			
			$filters['examNeeded']  = 1;

		} elseif ($this->pageType == EXAM_SPECIALIZATION_ONLY) {

			$filters['ldbCourseId'] = $this->ldbCourseId;
			$filters['examName']    = $this->examName;
			$filters['cityId']      = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']     = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			
			$filters['feesNeeded']  = 1;

		} elseif ($this->pageType == EXAM_SPECIALIZATION_FEES_ONLY) {

			$filters['examName']    = $this->examName;
			$filters['fees']        = $this->fees;
			$filters['ldbCourseId'] = $this->ldbCourseId;
			$locFilters             = $this->getCityStateFilter($categoryPageRequest);
			$filters                = array_merge($filters,$locFilters);

		}

		
		
		$this->getNonZeroCategoryPages($filters);
    	if(count($this->uniqueLinksArr) >= CATEGORY_MAX_LINKS_LIMIT) return;


	    //////////////
	    // Stage 2 //
	    //////////////
	    
		$filters                  = array();
		$filters['categoryId']    = $this->categoryId;
		$filters['subCategoryId'] = $this->subcategoryId;
		$filters['countryId']     = 2;
		$filters['fees']          = $this->fees;
		$filters['limit']         = CATEGORY_MAX_LINKS_LIMIT;

		if ($this->pageType == LOCATION_ONLY) {
			$filters['examName']    = 'none';
			$filters['ldbCourseId'] = 1;
			
			$filters['cityId']      = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']     = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			
			$filters['feesNeeded']  = 1;
			 
		} elseif ($this->pageType == EXAM_ONLY) {
			
			$filters['fees']                 = 'none';
			
			$filters['cityId']               = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']              = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			$filters['examName']             = $this->examName;

			$filters['specializationNeeded'] = 1;

		} elseif ($this->pageType == FEES_ONLY) {
			$filters['examName']             = 'none';
			
			$filters['cityId']               = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']              = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			$filters['fees']                 = $this->fees;

			$filters['specializationNeeded'] = 1;

		} elseif ($this->pageType == SPECIALIZATION_ONLY) {

			$filters['ldbCourseId'] = $this->ldbCourseId;
			$filters['cityId']      = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']     = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			
			$filters['feesNeeded']  = 1;
			$filters['examNeeded']  = 1;	
			
		} elseif ($this->pageType == EXAM_FEES_ONLY) {

			$filters['fees']                 = $this->fees;
			$filters['examName']             = $this->examName;			
			$locFilters                      = $this->getCityStateFilter($categoryPageRequest);
			$filters                         = array_merge($filters,$locFilters);
			
			$filters['specializationNeeded'] = 1;

		} elseif ($this->pageType == SPECIALIZATION_FEES_ONLY) {
			
			$filters['examName']    = 'none';

			$filters['fees']        = $this->fees;
			$filters['ldbCourseId'] = $this->ldbCourseId;
			$locFilters             = $this->getCityStateFilter($categoryPageRequest);
			$filters                = array_merge($filters,$locFilters);

		} elseif (in_array($this->pageType, array(EXAM_SPECIALIZATION_ONLY,EXAM_SPECIALIZATION_FEES_ONLY))) {

			$filters['fees']        = 'none';
			
			$filters['ldbCourseId'] = $this->ldbCourseId;
			$filters['examName']    = $this->examName;
			$locFilters             = $this->getCityStateFilter($categoryPageRequest);
			$filters                = array_merge($filters,$locFilters);
			
		} 
		
		
		$this->getNonZeroCategoryPages($filters);
    	if(count($this->uniqueLinksArr) >= CATEGORY_MAX_LINKS_LIMIT) return;

	    //////////////
	    // Stage 3 //
	    //////////////
		
		$filters                  = array();
		$filters['categoryId']    = $this->categoryId;
		$filters['subCategoryId'] = $this->subcategoryId;
		$filters['countryId']     = 2;
		$filters['limit']         = CATEGORY_MAX_LINKS_LIMIT;
		
		if (in_array($this->pageType, array(SPECIALIZATION_FEES_ONLY,EXAM_SPECIALIZATION_ONLY,EXAM_SPECIALIZATION_FEES_ONLY))) {
			
			$filters['ldbCourseId'] = 1;
			$filters['examName']    = 'none';
			$filters['fees']        = 'none';

			$filters['cityId']      = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']     = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			
		} elseif ($this->pageType == EXAM_ONLY) {
			$filters['fees']        = 'none';
			$filters['ldbCourseId'] = 1;
			
			$filters['examName']    = $this->examName;
			$locFilters             = $this->getCityStateFilter($categoryPageRequest);
			$filters                = array_merge($filters,$locFilters);
			
		} elseif ($this->pageType == FEES_ONLY) {
			$filters['examName']    = 'none';
			$filters['ldbCourseId'] = 1;
			
			$filters['fees']        = $this->fees;
			$locFilters             = $this->getCityStateFilter($categoryPageRequest);
			$filters                = array_merge($filters,$locFilters);

		} elseif ($this->pageType == SPECIALIZATION_ONLY) {
			$filters['fees']        = 'none';
			$filters['examName']    = 'none';

			$filters['ldbCourseId'] = $this->ldbCourseId;
			$locFilters = $this->getCityStateFilter($categoryPageRequest);
			$filters = array_merge($filters,$locFilters);
			
		} elseif($this->pageType == EXAM_FEES_ONLY) {

			$filters['ldbCourseId'] = 1;
			
			$filters['fees']        = $this->fees;
			$filters['examName']    = $this->examName;
			$locFilters             = $this->getCityStateFilter($categoryPageRequest);
			$filters                = array_merge($filters,$locFilters);
		} 

		
		if($this->pageType != LOCATION_ONLY){
			$this->getNonZeroCategoryPages($filters);
    		if(count($this->uniqueLinksArr) >= CATEGORY_MAX_LINKS_LIMIT) return;	
		}

		
		

		///////////////
		// Stage 4 //
		///////////////
		
		
			
		$filters                  = array();
		$filters['categoryId']    = $this->categoryId;
		$filters['subCategoryId'] = $this->subcategoryId;
		$filters['countryId']     = 2;
		$filters['limit']         = CATEGORY_MAX_LINKS_LIMIT;
		
		if (in_array($this->pageType, array(SPECIALIZATION_FEES_ONLY,EXAM_SPECIALIZATION_ONLY,EXAM_SPECIALIZATION_FEES_ONLY))) {
			$filters['fees']        = 'none';
			$filters['ldbCourseId'] = 1;
			
			$filters['cityId']      = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? 0 : $this->cityId;
			$filters['stateId']     = (empty($this->cityId) || $this->cityId == CP_DEFAULT_VAL_CITY_ID) ? $this->stateId : 0;
			
			$filters['examNeeded']  = 1;

			$this->getNonZeroCategoryPages($filters);
	    	if(count($this->uniqueLinksArr) >= CATEGORY_MAX_LINKS_LIMIT) return;
		} elseif ($this->pageType == FEES_ONLY) {
			$filters['ldbCourseId'] = 1;
			
			$filters['fees']        = $this->fees;
			$locFilters             = $this->getCityStateFilter($categoryPageRequest);
			$filters                = array_merge($filters,$locFilters);
			
			$filters['examNeeded']  = 1;

			$this->getNonZeroCategoryPages($filters);
	    	if(count($this->uniqueLinksArr) >= CATEGORY_MAX_LINKS_LIMIT) return;
		}

		
		


		///////////////
		// Stage 5 //
		///////////////
		
		$filters                  = array();
		$filters['categoryId']    = $this->categoryId;
		$filters['subCategoryId'] = $this->subcategoryId;
		$filters['countryId']     = 2;
		$filters['limit']         = CATEGORY_MAX_LINKS_LIMIT;

		if (in_array($this->pageType, array(SPECIALIZATION_FEES_ONLY,EXAM_SPECIALIZATION_ONLY,EXAM_SPECIALIZATION_FEES_ONLY))) {

			$filters['fees']        = 'none';
			$filters['ldbCourseId'] = 1;
			$filters['exam']        = 'none';

			$locFilters = $this->getCityStateFilter($categoryPageRequest);
			$filters = array_merge($filters,$locFilters);


			$this->getNonZeroCategoryPages($filters);
	    	if(count($this->uniqueLinksArr) >= CATEGORY_MAX_LINKS_LIMIT) return;
		}
	}


	/**
	 * [getCityStateFilter description]
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-02-01
	 * @param  [type]     $categoryPageRequest [description]
	 * @return [type]                          [description]
	 */
	public function getCityStateFilter($categoryPageRequest){

		$filters = array();

		if ($categoryPageRequest->isCityPage()) {

				$this->CI->load->builder('LocationBuilder','location');
				$locationBuilder = new LocationBuilder;
				$locationRepository = $locationBuilder->getLocationRepository();

					    // check for Virtual cities(hard-code checks as discussed with product)
						$stateId = 0;
						if($this->cityId == 10223){
						    $stateId = 128;
						}
						else if($this->cityId == 10224){
						    $stateId = 114;
						}
						else if($this->cityId == 12292){
						    $stateId = 134;
						}
						else{
						    $cityObj = $locationRepository->findCity($this->cityId);
						    $stateId = $cityObj->getStateId();
						}
				$filters['stateId']       = $stateId;
				$filters['cityId']        = 1;
				$filters['stateNeeded']   = 1;
			} elseif ($categoryPageRequest->isStatePage()) {
				$filters['stateId']        = $this->stateId;
				$filters['cityNeeded'] = 1;
			}

		return $filters;

	}


	/**
	 * [getNonZeroCategoryPages description]
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-02-01
	 * @param  [type]     $filters [description]
	 * @return [type]              [description]
	 */
	public function getNonZeroCategoryPages($filters){

		$data = array();
		$data = $this->categorypagemodel->getNonZeroCategoryPages($filters);
		$this->uniqueLinksArr = array_merge($this->uniqueLinksArr, $data);
		return;
	}

	/**
	 * [getCategoryPageUrl description]
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-02-01
	 * @param  [type]     $key                 [description]
	 * @return [type]                          [description]
	 */
	public function getCategoryPageUrl($key){

    
	      
	     $this->CI->load->library('categoryList/CategoryPageRequest');

		 $request = new CategoryPageRequest();
		 $request->setNewURLFlag(1);
         $request->setData(array(
									'categoryId'    =>	$key['category_id'],
									'subCategoryId' =>	$key['sub_category_id'],
									'LDBCourseId'   =>	$key['ldbCourseId'],
									'cityId'        =>	$key['city_id'],
									'stateId'       =>	$key['state_id'],
									'countryId'     =>	$key['country_id'],
									'feesValue'     =>	$key['fees'],
									'examName'      =>	$key['exam']
         						 ));

		if($key['isNONRNR']){
			$pageTitle        = ucfirst($key['urlTitle']);
			$data['urlTitle'] = getPageHeadingTextForNONRNR($pageTitle,$request);	
		}else{
			$pageTitle        = computeFinalCrumb($request);
			$pageTitle        = implode("",$pageTitle);
			$data['urlTitle'] = getPageHeadingTextForRNR('',$pageTitle,'',$request);	
		}
		$data['url']      = $request->getURL();
		return $data;
		
	}

	function sortInstitutesOnRanking($data) {
		if(empty($data['subcatId']) || $data['subcatId'] <= 1 || empty($data['instituteIds'])) {
			return;
		}
		$this->categorypagemodel = $this->CI->load->model("categorypagemodel");
		$result = $this->categorypagemodel->sortInstitutesOnRanking($data);
		foreach ($result as $value) {
			$output[$value['institute_id']] = $value['rank'];
		}
		return $output;
	}

	/*
	 * Nikita Jain
	 * -----------
	 * All India Page 	 - Top 5 tier 1 city links(including virtual cities)
	 * State page 		 - Top 5 city links from that state
	 * City Page(Tier 1) - State category page link + 4 top tier 1 city links(excluding current city)
	 * City Page(Tier 2) - State category page link + 4 top city links from that state(excluding current city)
	 */
	function getCategoryPageRelatedLinks($categoryPageRequest) {
		$this->CI->load->builder('LocationBuilder','location');
	    $locationBuilder = new LocationBuilder;
	    $this->locationRepository    = $locationBuilder->getLocationRepository();
		$this->categorypagemodel   	 = $this->CI->load->model("categorypagemodel");

		$data['categoryId']          = $categoryPageRequest->getCategoryId();
		$data['subcategoryId']       = $categoryPageRequest->getSubCategoryId();
		$data['stateId']             = $categoryPageRequest->getStateId();
		$data['cityId']              = $categoryPageRequest->getCityId();
		$data['ldbCourseId']         = $categoryPageRequest->getLDBCourseId();
		$data['examName']            = $categoryPageRequest->getExamName();
		$data['isMultilocationPage'] = $categoryPageRequest->isMultilocationPage();
		if(empty($data['examName'])) {
			$data['examName'] = 'none';
		}

		$dataForCategoryLink['category_id'] = $data['categoryId'];
		$dataForCategoryLink['sub_category_id'] = $data['subcategoryId'];
		$dataForCategoryLink['ldbCourseId'] = $data['ldbCourseId'];
		$dataForCategoryLink['country_id'] = 2;
		$dataForCategoryLink['exam'] = $data['examName'];

		/* 
		 * ---All India Page---
		 * 1. Get tier 1 cities
		 * 2. Get distinct city ids for these cities (+ specialization + exam) in category_page_non_zero_pages table in decreasing order of count, limit 5
		 * 3. For top 5 cities, create urls and text - getCategoryPageUrl()
		 */
		if(($data['cityId'] == 1 && $data['stateId'] == 1) || $data['isMultilocationPage'] == 1) {
			$cacheKey = 'multilocationQuickLinks-'.$data['subcategoryId'].'-'.$data['ldbCourseId'].'-'.$data['examName'];
			$cachedUrls = $this->categoryPageCache->getCategoryPageQuickLinks($cacheKey);
			if($cachedUrls) {
				return $cachedUrls;
			}
			
			$tier1CityList = $this->locationRepository->getCitiesByMultipleTiers(array(1), 2);
			foreach ($tier1CityList[1] as $cityObj) {
				$data['tier1CityIds'][] = $cityObj->getId();
			}
			
			$topCityIds = $this->categorypagemodel->getQuickLinkCatPagesForAllIndiaPages($data);
		}

		/*
		 * ---State Page---
		 * 1. Get distinct city ids for this state (+ specialization + exam) in category_page_non_zero_pages table in decreasing order of count, limit 5
		 * 2. For top 5 cities, create urls and text - getCategoryPageUrl()
		 */
		elseif($data['cityId'] == 1 && $data['stateId'] > 1) {
			$cacheKey = 'stateQuickLinks-'.$data['subcategoryId'].'-'.$data['stateId'].'-'.$data['ldbCourseId'].'-'.$data['examName'];
			$cachedUrls = $this->categoryPageCache->getCategoryPageQuickLinks($cacheKey);
			if($cachedUrls) {
				return $cachedUrls;
			}

			$topCityIds = $this->categorypagemodel->getQuickLinkCatPagesForStatePages($data);
		}

		/*
		 * ---City Page---
		 * 1. Get tier 1 cities
		 * 2. Check if this city is tier 1
		 * 		3. Get distinct city ids among tier 1 cities(excluding this city) (+ specialization + exam) in category_page_non_zero_pages table in decreasing order of count, limit 4
		 * 2. else
		 *		3. Get distinct city ids for this city's state(excluding this city) (+ specialization + exam) in category_page_non_zero_pages table in decreasing order of count, limit 4
		 * 4. Create urls and text for this city's state + for top 4 cities
		 */
		elseif($data['cityId'] > 1) {
			$cacheKey = 'cityQuickLinks-'.$data['subcategoryId'].'-'.$data['cityId'].'-'.$data['ldbCourseId'].'-'.$data['examName'];
			$cachedUrls = $this->categoryPageCache->getCategoryPageQuickLinks($cacheKey);
			if($cachedUrls) {
				return $cachedUrls;
			}

			$tier1CityList = $this->locationRepository->getCitiesByMultipleTiers(array(1), 2);
			$data['isCityTier1'] = 0; $data['isVirtualCity'] = 0;
			foreach ($tier1CityList[1] as $cityObj) {
				$data['tier1CityIds'][] = $cityObj->getId();
				if($cityObj->getId() == $data['cityId']) {
					$data['isCityTier1'] = 1;
					if($cityObj->isVirtualCity()) {
						$data['isVirtualCity'] = 1;
					}
				}
			}
			$topCityIds = $this->categorypagemodel->getQuickLinkCatPagesForCityPages($data);

			if(!$data['isVirtualCity']) {
				//get state page link
				$dataForCategoryLink['state_id'] = $data['stateId'];
				$dataForCategoryLink['city_id'] = 1;
				$urls[] = $this->getCategoryPageUrl($dataForCategoryLink);
			}

			$dataForCategoryLink['state_id'] = '';
		}
		
		//get city page links
		foreach ($topCityIds as $value) {
			$dataForCategoryLink['city_id'] = $value['city_id'];
			$urls[] = $this->getCategoryPageUrl($dataForCategoryLink);
		}
		
		$this->categoryPageCache->storeCategoryPageQuickLinks($cacheKey, $urls);
		return $urls;
	}
}