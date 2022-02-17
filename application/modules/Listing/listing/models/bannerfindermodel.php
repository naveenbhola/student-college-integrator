<?php

require_once dirname(__FILE__).'/ListingModelAbstract.php';

class BannerFinderModel extends ListingModelAbstract
{
	private $categoryId;
	private $subCategoryId;
	private $cityId;
	private $stateId;
	private $countryId;
	
    function __construct()
    {
        parent::__construct();
    }
	
	public function getCategoryPageBannersForAbroad($categoryPageRequest) {
		if (! in_array ( 1, $categoryPageRequest->getCountryId () )) {
			$LocationClause = " AND bl.countryid IN (" . implode ( ',', $categoryPageRequest->getCountryId () ) . ") ";
		}
		if ($categoryPageRequest->getCategoryId () != 1) {
		   $categoryClause = " AND bl.categoryid = '" . $categoryPageRequest->getCategoryId () . "'";
		} else if($categoryPageRequest->getLDBCourseId () != 1) {
			global $studyAbroadPopularCourseToCategoryMapping; // Mapping of popular courses to category
			$categoryClause = " AND bl.categoryid IN (" . implode(',', $studyAbroadPopularCourseToCategoryMapping[$categoryPageRequest->getLDBCourseId ()]). ") ";
		} else {
			throw new InvalidArgumentException('Invalid request object type bannerfindermodel : getCategoryPageBannersForAbroad');
		}
		
		global $studyAbroadPopularCourseToLevelMapping;
		// clause to check against course level		
		$desiredCourseId = $categoryPageRequest->getLDBCourseId();
		if($studyAbroadPopularCourseToLevelMapping[$desiredCourseId]) {
		    $courseLevel = $studyAbroadPopularCourseToLevelMapping[$desiredCourseId];
		} else {
		    $courseLevel = ucfirst($categoryPageRequest->getCourseLevel());
		}
		global $courseExpandedLevels;
		if(!empty($courseExpandedLevels[strtolower($courseLevel)])){
			$courseLevelClause = 	" AND bl.course_level in ('All','".implode("','",$courseExpandedLevels[strtolower($courseLevel)])."') ";
		}else{
			$courseLevelClause = 	" AND bl.course_level in ('All','".$courseLevel."') ";
		}
		//#AND ls.course_level in ('All','".ucfirst($categoryPageRequest->getCourseLevel())."')
		$sql = "SELECT DISTINCT bl.sno,b.bannerurl,ls.listing_type_id as institute_id  " . "
					FROM `tbannerlinks` bl " . "JOIN tbanners b ON (bl.bannerid = b.bannerid AND b.status='live') " . "
					LEFT JOIN tcoupling c ON (c.bannerlinkid = bl.bannerlinkid AND c.status='coupled') " . "
					LEFT JOIN tlistingsubscription ls ON (ls.listing_type = 'university' 
					AND ls.listingsubsid = c.listingsubsid 
					AND ls.status='" . ENT_SA_PRE_LIVE_STATUS . "') " . 
					"where " . " CURDATE() >= bl.startdate 
					AND CURDATE() <= bl.enddate " . $LocationClause .$categoryClause.$courseLevelClause. " AND bl.status = '" . ENT_SA_PRE_LIVE_STATUS . "' ";
			
			$results = $this->db->query ( $sql )->result_array ();
			$banners = array ();
			foreach ( $results as $result ) {
				$banners [$result ['sno']] = $result;
			}
			return $banners;
		
	}
	
	
	/*
	 * Select banners which expired in last [$numDays]
	 */ 
	public function getExpiredBanners($numDays)
	{
		$numDays = (int) $numDays;
		$expireStart = date('Y-m-d 00:00:00',strtotime("-$numDays Day"));
		$today = date('Y-m-d 00:00:00');
		
		$sql =  "SELECT sno ".
				"FROM tbannerlinks ".
				"WHERE enddate > ? ".
				"AND enddate < ? ".
				"AND status = 'live' ".
				"AND countryid = 2 ";
					
		return $this->getColumnArray($this->db->query($sql,array($expireStart,$today))->result_array(),'sno');
	}
	
	/*
	 * Select banners which were modified in given criteria
	 */ 
	public function getModifiedBanners($criteria)
	{
		$debugLog = 0;
		$interval = $criteria['interval'];
		
		$sql =  "SELECT sno ".
				"FROM tbannerlinks ".
				"WHERE lastModificationDate >= ? ".
				"AND lastModificationDate < ? ".
				"AND countryid = 2 ";
		
		if($debugLog)
		{  
			$results = $this->db->query($sql,array($interval['start'],$interval['end']))->result_array();			 
			error_log($sql,3,"/tmp/refreshCache.log");
			$r = print_r($results,1);
			error_log(" \n Banners sno For refreshing id \n".$r,3,"/tmp/refreshCache.log");
		}
		return $this->getColumnArray($this->db->query($sql,array($interval['start'],$interval['end']))->result_array(),'sno');
	}
}
