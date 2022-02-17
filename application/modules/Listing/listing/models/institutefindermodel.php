<?php

require_once dirname(__FILE__).'/ListingModelAbstract.php';

class InstituteFinderModel extends ListingModelAbstract
{
	private $locationModel;
	private $categoryModel;
	
    function __construct()
    {
        parent::__construct();
    }
	
	public function init(LocationModel $locationMode = NULL,CategoryModel $categoryModel = NULL)
    {
		$this->locationModel = $locationMode;
		$this->categoryModel = $categoryModel;
    }
	
	public function getCategoryPageInstitutes($categoryPageRequest)
	{
		$institutes = array();
		
		$institutes['sticky'] = $this->getStickyInstitutes($categoryPageRequest);
		$institutes['main'] = $this->getMainInstitutes($categoryPageRequest);
		
		$exclusionList = array();
		
		$stickyInstituteIds = array();
		foreach($institutes['sticky'] as $type => $stickyInstitutesInType) {
			foreach($stickyInstitutesInType as $stickyInstituteId => $stickyInstitute) {
				$exclusionList[] = $stickyInstituteId;
			}
		}
		
		$mainInstituteIds = array();
		foreach($institutes['main'] as $type => $mainInstitutesInType) {
			foreach($mainInstitutesInType as $mainInstituteId => $mainInstitute) {
				$exclusionList[] = $mainInstituteId;
			}
		}
		
		$institutes['paid'] = $this->getPaidInstitutes($categoryPageRequest,$exclusionList);
		$institutes['free'] = $this->getFreeInstitutes($categoryPageRequest,$exclusionList);
		return $institutes; 
	}
	
	/*
	 * Get Paid Institutes
	 */
	public function getPaidInstitutes($categoryPageRequest,$exclusion = array())
	{
		$this->validateCategoryPageRequest($categoryPageRequest);
		$where = array('pack_type IN ('.GOLD_SL_LISTINGS_BASE_PRODUCT_ID.','.SILVER_LISTINGS_BASE_PRODUCT_ID.','.GOLD_ML_LISTINGS_BASE_PRODUCT_ID.')');
		if(is_array($exclusion) && count($exclusion) > 0) {
			$where[] = 'institute_id NOT IN ('.implode(',',$exclusion).')';
		}
		// $data = $this->_getInstitutes($categoryPageRequest,$where);
		return $data;
	}
	
	/*
	 * Get Free Institutes
	 */
	public function getFreeInstitutes($categoryPageRequest,$exclusion = array())
	{
		$this->validateCategoryPageRequest($categoryPageRequest);
		$where = array('pack_type IN (-10,-5,0,'.BRONZE_LISTINGS_BASE_PRODUCT_ID.')');
		if(is_array($exclusion) && count($exclusion) > 0) {
			$where[] = 'institute_id NOT IN ('.implode(',',$exclusion).')';
		}
		// $data = $this->_getInstitutes($categoryPageRequest,$where);
		return $data;
	}

	private function _getCityClause($categoryPageRequest)
	{
		$countryId = $categoryPageRequest->getCountryId();
		$cityId = $categoryPageRequest->getCityId();
		$stateId = $categoryPageRequest->getStateId();
		
		if($countryId > 2) {
			return '';
		}
		
		$city_clause = " ";
		if($cityId > 1){
			$cityList = $this->locationModel->getCitiesForVirtualCity($cityId);
			if(is_array($cityList) && count($cityList) > 0) {
				$city_clause = "city_id in (".implode(',',$cityList).") AND";
			}
			else {
				$city_clause = "city_id in (0) AND";
			}
			
		}
		else if($stateId > 1){
			$city_clause = "state_id = ".$stateId." AND";
		}
		
		return $city_clause;
	}
	
	/*
	 * Get Main Institutes
	 */
	public function getMainInstitutes($categoryPageRequest)
	{
		$this->validateCategoryPageRequest($categoryPageRequest);
		$returnArray = array();
		
		// $pageKeylist = $this->_getPageKeyForMainInstitutes($categoryPageRequest,'category');
		$pageKeylist = $pageKeylist?$pageKeylist:0;
		// $returnArray['category'] = $this->_executeGetMainInstitutesQuery($categoryPageRequest,$pageKeylist);
		
		// $pageKeylist = $this->_getPageKeyForMainInstitutes($categoryPageRequest,'subcategory');
		$pageKeylist = $pageKeylist?$pageKeylist:0;
		// $returnArray['subcategory'] = $this->_executeGetMainInstitutesQuery($categoryPageRequest,$pageKeylist);
		
		// $pageKeylist = $this->_getPageKeyForMainInstitutes($categoryPageRequest,'country');
		$pageKeylist = $pageKeylist?$pageKeylist:0;
		// $returnArray['country'] = $this->_executeGetMainInstitutesQuery($categoryPageRequest,$pageKeylist);
		
		return $returnArray;
	}
	
	/*
	 * Get Sticky Institutes
	 */
	public function getStickyInstitutes($categoryPageRequest)
	{
		$this->validateCategoryPageRequest($categoryPageRequest);
		
		$countryId = $categoryPageRequest->getCountryId();
		$cityId = $categoryPageRequest->getCityId();
		$stateId = $categoryPageRequest->getStateId();
		
		$where = array();
		
		if($countryId > 2) {
			$where['country'] = "countryid = '".$countryId."'";	
		}
		else if($countryId == 2) {
			if($cityId > 1 || ($cityId == 1 && $stateId == 1)) {
				$where['city'] = "cityid = '".$cityId."'";
			}
			else {
				$where['state'] = "stateid = '".$stateId."'";
			}	
		}
			
		$categoryId = $categoryPageRequest->getCategoryId();
		$subCategoryId = $categoryPageRequest->getSubCategoryId();	
					
		$where['category'] = "categoryid = '".$categoryId."'";
		$where['subcategory'] = "subcategory = 0";
		// $stickyInstitutesInCategory =  $this->_executeStickyInstitutesQuery($categoryPageRequest,$where);

		$where['category'] = "categoryid = 0";
		$where['subcategory'] = "subcategory = '".$subCategoryId."'";
		// $stickyInstitutesInSubCategory =  $this->_executeStickyInstitutesQuery($categoryPageRequest,$where);

		$where['category'] = "categoryid = 0";
		$where['subcategory'] = "subcategory = 0";
		// $stickyInstitutesInCountry =  $this->_executeStickyInstitutesQuery($categoryPageRequest,$where);

		return array(
					'category' => $stickyInstitutesInCategory,
					'subcategory' => $stickyInstitutesInSubCategory,
					'country' => $stickyInstitutesInCountry
				);
	}
	
	/*
	 * Select all the institutes which were modified in given criteria
	 */ 
	public function getModifiedInstitutes($criteria)
	{
		$interval = $criteria['interval'];
		$debugLog = 0;
		$sql =  "SELECT distinct listing_type_id ".
				"FROM listings_main lm ".
				"inner join ".
				"categoryPageData cpd ON(lm.listing_type_id = cpd.institute_id) ".
				"WHERE lm.last_modify_date >= ? ".
				"AND lm.last_modify_date < ? ".
				"AND lm.listing_type = 'institute' ".
				"AND (lm.status = 'live' OR lm.status = 'deleted')";
			
		$results = $this->db->query($sql,array($interval['start'],$interval['end']))->result_array();
		
		if($debugLog){
			error_log($sql,3,"/tmp/refreshCache.log");
			$r = print_r($results,1);
			error_log("\n institue ids \n ".$r,3,"/tmp/refreshCache.log");
		}
		
		return $this->getColumnArray($results,'listing_type_id');
	}
	
	/*
	 * Select sticky institutes which expired in last [$numDays]
	 */ 
	public function getExpiredStickyInstitutes($numDays)
	{
		$numDays = (int) $numDays;
		$expireStart = date('Y-m-d 00:00:00',strtotime("-$numDays Day"));
		$today = date('Y-m-d 00:00:00');
		
		$sql =  "SELECT listingsubsid ".
				"FROM tlistingsubscription ".
				"WHERE enddate > ? ".
				"AND enddate < ? ".
				"AND status = 'live' ".
				"AND listing_type = 'institute' ";
		
		return $this->getColumnArray($this->db->query($sql,array($expireStart,$today))->result_array(),'listingsubsid');
	}
	
	/*
	 * Select sticky institutes which were modified in given criteria
	 */ 
	public function getModifiedStickyInstitutes($criteria)
	{
		$interval = $criteria['interval'];
		
		/*
		 * Sticky institutes added/deleted
		 */ 
		$sql =  "SELECT listingsubsid ".
				"FROM tlistingsubscription ".
				"WHERE lastModificationDate >= ? ".
				"AND lastModificationDate < ? ".
				"AND listing_type = 'institute' ";
		
		$modifiedStickyInstitutes =  (array) $this->getColumnArray($this->db->query($sql,array($interval['start'],$interval['end']))->result_array(),'listingsubsid');

		/*
		 * Sticky institutes coupled/decoupled
		 */
		/*
		$sql =  "SELECT listingsubsid ".
				"FROM tcoupling ".
				"WHERE lastModificationDate >= '".$interval['start']."' ".
				"AND lastModificationDate < '".$interval['end']."' ";
				*/
		$sql =  "SELECT tc.listingsubsid ".
				"FROM tcoupling tc,  tlistingsubscription tls ".
				"WHERE tc.lastModificationDate >= ? ".
				"AND tc.lastModificationDate < ? ".
				"AND tls.listingsubsid = tc.listingsubsid AND tls.listing_type = 'institute' ";
				
		$coupledStickyInstitutes =  (array) $this->getColumnArray($this->db->query($sql,array($interval['start'],$interval['end']))->result_array(),'listingsubsid');
		
		$allModifiedStickyInstitutes = array_merge($modifiedStickyInstitutes, $coupledStickyInstitutes);
		return $allModifiedStickyInstitutes;
	}
	
	/*
	 * Select main institutes which expired in last [$numDays]
	 */ 
	public function getExpiredMainInstitutes($numDays)
	{
		$numDays = (int) $numDays;
		$expireStart = date('Y-m-d 00:00:00',strtotime("-$numDays Day"));
		$today = date('Y-m-d 00:00:00');
		
		$sql =  "SELECT id  ".
				"FROM PageCollegeDb ".
				"WHERE EndDate > ? ".
				"AND EndDate < ? ".
				"AND status = 'live' ".
				"AND listing_type = 'institute' ";
				
		return $this->getColumnArray($this->db->query($sql,array($expireStart,$today))->result_array(),'id');
	}
	
	/*
	 * Select main institutes which were modified in given criteria
	 */ 
	public function getModifiedMainInstitutes($criteria)
	{
		$interval = $criteria['interval'];
		$sql =  "SELECT id ".
				"FROM PageCollegeDb ".
				"WHERE lastModificationDate >= ? ".
				"AND lastModificationDate < ? ".
				"AND listing_type = 'institute' ";
				
		return $this->getColumnArray($this->db->query($sql,array($interval['start'],$interval['end']))->result_array(),'id');
	}

	/*
	 * All institutes which are currently live
	 */ 
	public function getLiveInstitutes()
	{
		$sql =  "SELECT DISTINCT institute_id ".
				"FROM categoryPageData ".
				"WHERE status = 'live' AND country_id = 2 ";
		
		return $this->getColumnArray($this->db->query($sql)->result_array(),'institute_id');		
	}

	public function getActiveAndDeletedInstitutesForOwners($owners) {
		$returnArr = array();
		
		if(count($owners) == 0) {
			return $returnArr;
		}

		$chunkArray = array();
		$chunkArray = array_chunk($owners, 50);
		
		foreach ($chunkArray as $ownersArray) {

			$sql = "SELECT listing_type_id,listing_title,username,a.listing_type FROM listings_main a,tuser b,shiksha_institutes c WHERE a.username=b.userid ".
					"AND a.listing_type in ('institute','university_national') AND a.listing_type_id = c.listing_id AND c.status = 'live' ".
					//"AND c.institute_type != 'Department' AND c.institute_type != 'Department_Virtual' ".
					"AND a.username in (".implode(',', $ownersArray).") AND (a.status='live') ";
			
			error_log('getActiveAndDeletedInstitutesForOwners '.$sql);
			
			$results = $this->db->query($sql)->result_array();
			
			foreach($results as $row) {

				$instituteList[] = $row['listing_type_id'];

				if($row['listing_type'] == 'university_national') {

					$row['listing_type'] = "University";
					$clientInstituteMapping[$row['username']][$row['listing_type_id']] = $row['listing_title']."(".$row['listing_type'].")";	

				} else {

					$clientInstituteMapping[$row['username']][$row['listing_type_id']] = $row['listing_title'];

				}

			}
			
		}

		$returnArr['instituteList'] 		 = $instituteList;
		$returnArr['clientInstituteMapping'] = $clientInstituteMapping;
		
		unset($instituteList);
		unset($clientInstituteMapping);

		// if($list) {
		// 	foreach($results as $row) {
		// 		$returnArr[] = $row['listing_type_id'];
		// 	}
		// }
		// else {
		// 	foreach($results as $row) {
		// 		if($append_type && $row['listing_type'] == 'university_national') {
		// 			$row['listing_type'] = "University";
		// 			$returnArr[$row['username']][$row['listing_type_id']] = $row['listing_title']."(".$row['listing_type'].")";	
		// 		} else {
		// 			$returnArr[$row['username']][$row['listing_type_id']] = $row['listing_title'];
		// 		}
				
		// 	}
		// }
		
		return $returnArr;
	}
	
	public function getActiveAndDeletedUniversitiesForOwners($owners) {
		$returnArr = array();
		
		if(count($owners) == 0) {
			return $returnArr;
		}
		
		$sql = "SELECT listing_type_id,listing_title,username FROM listings_main,tuser WHERE username=userid ".
				"AND listing_type = 'university' ".
				"AND username in (?) AND (status='live') ";
		
		error_log('getActiveAndDeletedUniversitiesForOwners '.$sql);
		
		$results = $this->db->query($sql, array($owners))->result_array();
		
		foreach($results as $row) {
				$returnArr['universityList'][] 													 = $row['listing_type_id'];
				$returnArr['clientUniversityMapping'][$row['username']][$row['listing_type_id']] = $row['listing_title'];
		}
		
		return $returnArr;
	}
}
