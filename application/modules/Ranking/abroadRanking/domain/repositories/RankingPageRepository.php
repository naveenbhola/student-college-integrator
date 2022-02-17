<?php

class RankingPageRepository extends EntityRepository {
	
	private $universityRepo; 	// university repository
	private $abroadCourseRepo; 	// abroad course repository
	private $rankingLib;		// common ranking library that gets the ranking data from db
	private $rankingCache;		// ranking cache library
	
	public function __construct($rankingLib, $universityRepo, $courseRepo, $rankingCache) {
		parent::__construct();
		$this->CI = & get_instance();

		if(!empty($rankingLib) &&
		   !empty($universityRepo) &&
		   !empty($courseRepo) &&
		   !empty($rankingCache)
		   ){
			$this->rankingLib 	= $rankingLib;
			$this->universityRepo 	= $universityRepo;
			$this->courseRepo    	= $courseRepo;
			$this->rankingCache    	= $rankingCache;
		}
	}

	/*
	 * function to search & get a ranking page object for a given ranking page id
	 * params: ranking page id (single numeric id / array of ids), a boolean flg to skip listing objects if not required (optional, default false)
	 * return val : aray of objects of Class RankingPage (abroadRanking)
	 */
	public function find($rankingPageId = NULL, $skipListingObjectFlag = false){
		$rankingPage 		= false;
		$rankingPageObject 	= false;
		//$this->cache 		= false; // no cache for now
		
		if(is_null($rankingPageId))
		{
			return false;
		}
		else if(!is_array($rankingPageId) && is_numeric($rankingPageId))
		{
			$rankingPageId = array($rankingPageId);
		}
		$rankingPageObjects = array();
		// get from cache
		if($this->caching != false){
			$rankingPageObjects = $this->rankingCache->getMultipleRankingPages($rankingPageId);
			$remainingRankingPage = array();
			$remainingRankingPageObjects = array();
			foreach($rankingPageObjects as $rank => $rankingPageObject)
			{
				if($rankingPageObject == "VALUE NOT SET"){
					unset($rankingPageObjects[$rank]);
					continue;
				}
			}
		}
		$remainingRankingPage = array_diff($rankingPageId,array_keys($rankingPageObjects)) ;
		// if not found fetch fom db.. 
		if($this->caching == false || count($remainingRankingPage) > 0){
			// fetch ranking page data from db
			if($this->caching == false){
				$rankingPage = $this->rankingLib->getRankingData($rankingPageId);
			}else{
				$rankingPage = $this->rankingLib->getRankingData($remainingRankingPage);
			}
			// populate ranking page object from data fetched from db
			if(!empty($rankingPage)){
				$remainingRankingPageObjects  = $this->populateRankingPageObject($rankingPage);
			}
			//.. and store in cache
			if($this->caching == false){
				foreach($rankingPageId as $id){
				$this->rankingCache->storeRankingPage($id,$remainingRankingPageObjects[$id]);
				}
			}else{
				foreach($remainingRankingPage as $id){
					$this->rankingCache->storeRankingPage($id,$remainingRankingPageObjects[$id]);
				}
			}	
		}
	
		if($remainingRankingPageObjects != null){
		$rankingPageObjects = $remainingRankingPageObjects + $rankingPageObjects;
		}
		// add course/ university objects to ranking page object ( not cached )
		if($skipListingObjectFlag !== true)
		{
			foreach($rankingPageObjects as $rank => $rankingPageObject)
			{
				$rankings = $rankingPageObject->getRankingPageData();
				$rankings = $this->getRankedListingObjects($rankings,$rankingPageObject->getType());
				$rankingPageObjects[$rank]->setRankingPageData($rankings);
			}
		}
		return $rankingPageObjects;
	}
	/*
	 * function to create an object of class RankingPage (abroadRanking) based on values fetched from db
	 * params : ranking page details fetched from db
	 * return val : array of objects of Class RankingPage (abroadRanking)
	 */
	private function populateRankingPageObject($rankingPages = array()){
		$rankingPageObjects =array();
		
		foreach($rankingPages as $rank => $rankingPageDetails){
			if(empty($rankingPageDetails))
			{
				continue;
			}
			$rankingPage = array();
			$rankingPage['rankingPageId']		= $rankingPageDetails['ranking_page_id'];
			$rankingPage['rankingPageName'] 	= $rankingPageDetails['name'];
			$rankingPage['rankingPageTitle'] 	= $rankingPageDetails['title'];
			$rankingPage['rankingPageType'] 	= $rankingPageDetails['type'];
			$rankingPage['parentCategoryId'] 	= $rankingPageDetails['parentcategory_id'];
			$rankingPage['subCategoryId'] 		= $rankingPageDetails['subcategory_id'];
			$rankingPage['ldbCourseId'] 		= $rankingPageDetails['ldb_course_id'];
			$rankingPage['countryId'] 		= $rankingPageDetails['country_id'];
			$rankingPage['created'] 		= $rankingPageDetails['created'];
			$rankingPage['lastModified'] 		= $rankingPageDetails['last_modified'];
			$rankingPage['lastModifiedBy'] 		= $rankingPageDetails['last_modified_by'];
			$rankingPage['createdBy'] 		= $rankingPageDetails['created_by'];
			$rankingPage['seoTitle'] 		= $rankingPageDetails['seo_title'];
			$rankingPage['seoDescription'] 		= $rankingPageDetails['seo_description'];
			$rankingPage['seoKeywords'] 		= $rankingPageDetails['seo_keywords'];
			$rankingPage['rankings'] 		= $rankingPageDetails['ranking'];
			// initialize a ranking page object
			$rankingPageObject = new RankingPage();
			// fill it with values
			//_p($rankingPage);
			$this->fillObjectWithData($rankingPageObject, $rankingPage);
			// push it into array of objects
			$rankingPageObjects[$rank]= $rankingPageObject;
		}
		//_p($rankingPageObjects);
		return $rankingPageObjects;
	}
	/*
	 * function to load objects of course/university based on the ranking page type passed
	 * params :: individual rankings , ranking page type
	 */
	private function getRankedListingObjects($rankings = array(), $rankingPageType = ''){
		$rankedListings = array();
		if(empty($rankings) || $rankingPageType == "")
		{
			return false;
		}
		$this->CI->load->config('listing/studyAbroadListingCacheConfig');
		$universityFields = $this->CI->config->item('universityObjectFieldsRanking');
		if($rankingPageType == "university")
		{
			$listingRepo = $this->universityRepo;
			$objectFields = $universityFields;
		}
		else{
			$listingRepo = $this->courseRepo;
			$objectFields = $this->CI->config->item('courseObjectFieldsRanking');
		}
		foreach($rankings as $rank => $listing)
		{
			$listingObject = $listingRepo->find($listing['listing_id'],$objectFields);
			// set subcategory on course
			$courseValid = false;
			if($rankingPageType == 'course' && ($listingObject instanceof AbroadCourse && $listingObject->getId() > 0))
			{
				// get the subcategory object
				/*$categoryData = Modules::run('listing/abroadListings/getCategoryOfAbroadCourse',$listing['listing_id']);
				$subCategory = $this->rankingLib->getCategoryById($categoryData['subcategoryId']);
				// set the subcategory in course object
				$listingObject->setCourseSubCategoryObj($subCategory);*/
				$courseIds[] = $listing['listing_id'];
				$courseValid = true;
			}
			if($rankingPageType == 'course'){
				if($courseValid && $listingObject->getUniversityId() >0)
				{
					$universityObject = $this->universityRepo->find($listingObject->getUniversityId(),$universityFields);
				}
				else{
					$universityObject = NULL;
				}
			}
			else{
				$universityObject = $listingObject;
			}
			if(($universityObject instanceof University && $universityObject->getId() > 0)){
			$rankedListings[$rank] = array(
						       "course" => ($rankingPageType == 'course' ? $listingObject : false),
						       "university" => $universityObject 
						       );
			}
		}

		// Set subcategory of course objects when ranking page is of course and we have courseIds on that page
		if($rankingPageType == 'course' && !empty($courseIds)){
			$abroadListingCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
			$categorySubcatCourseMapping = $abroadListingCommonLib->getCategoryOfAbroadCourse($courseIds);
			foreach($rankedListings as &$rankedListingItem){
				if($rankedListingItem['course'] instanceof AbroadCourse && $rankedListingItem['course']->getId() > 0){
					$subCategory = $this->rankingLib->getCategoryById($categorySubcatCourseMapping[$rankedListingItem['course']->getId()]['subcategoryId']);
					$rankedListingItem['course']->setCourseSubCategoryObj($subCategory);
				}
			}
		}
		
		return $rankedListings;
	}
	
	public function getCurrencyDetials(){
		if($this->rankingCache && $currencyConversionData = $this->rankingCache->getCurrencyDetails())
		{
		   return $currencyConversionData;
		}
		$currencyData = $this->rankingLib->getCurrencyDetailsForRankingPage();
		$currencyConversionData[1][1] = 1;
		foreach($currencyData as $row){
		   $currencyConversionData[1][$row['source_currency_id']] = $row['conversion_factor'];
		}
		$this->rankingCache->storeCurrencyDetails($currencyConversionData);
		return $currencyConversionData;
	}
	
	public function disableCaching(){
		$this->caching = false;
	}
	
}
