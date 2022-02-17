<?php

class RankingPageRepository extends EntityRepository {
	
	private $locationRepo;
	private $instituteRepo;
	private $courseRepo;
	private $searchModel;
	private $rankingPageCommonLib;
        private $abroadListingCommonLib;
        
        public function __construct($rankingPageCommonLib, $instituteRepo, $courseRepo, $rankingPageManager,$rankingCache,$abroadListingCommonLib) {
		parent::__construct();
		if(!empty($rankingPageCommonLib)  &&
		   !empty($instituteRepo) &&
		   !empty($courseRepo) &&
		   !empty($rankingPageManager)  &&
		   	!empty($rankingCache)
		   ){
			$this->rankingPageCommonLib = $rankingPageCommonLib;
			$this->instituteRepo 		= $instituteRepo;
			$this->courseRepo    		= $courseRepo;
			$this->rankingPageManager 	= $rankingPageManager;
			$this->rankingCache 		= $rankingCache;
			$this->abroadListingCommonLib=$abroadListingCommonLib;
		}
		//common place for maintaining ranking page cache
		$this->CI->enableRankingPageCache = false;
	}
	
	/**
	 *@method: Complete ranking page object with details
	 *@example:
	 * RankingPage Object (
				[rankingPageId] => 12
				[rankingPageName] => MBA22
				......
				......
				[rankingPageData] => Array (
					[0] => RankingPageData Object (
						[id] => 8
						[courseId] => 1653
						[courseName] => MBA
						[sourceRank] => 2] => Array
						(
						    [0] => Array
							(
							    [source_id] => 2
							    [parameter_id] => 2
						.....
						)
	
	*
	*/
	public function find($rankingPageId = NULL, $status = array('live'), $publisherId, $availablePublisherIds = array()){
		$rankingPage 		= false;
		$rankingPageObject 	= false;
		  
		if(empty($rankingPageId)){
                  	return $rankingPageObject;
		}

		//if cache is enabled
 		if($this->CI->enableRankingPageCache){
 			//disabling cache for sorting on PC+MS
			if(($this->CI->input->post('isAjaxCall') == 1 && $this->CI->input->post('columnType') == 'source') || !empty($source_id)) {
				$rankingPageObject = false;
			}
			else{
				$rankingPageObject = $this->rankingCache->getRankingPageObject($rankingPageId,implode(',', $status));
			}
		}
        $this->CI->benchmark->mark('object_from_db_start');
		
		$rankingPage = $this->rankingPageManager->getRankingPage($rankingPageId, $publisherId, $availablePublisherIds);
		
		if(!empty($rankingPage['ranking_page_details']) && !empty($rankingPage['ranking_page_data'])){
			$rankingPageDetails = $rankingPage['ranking_page_details'];
			$rankingPageData    = $rankingPage['ranking_page_data'];
			$rankingPageObject  = $this->populateRankingPageObject($rankingPageDetails, $rankingPageData, $rankingPage['rankingPageSourceData']);
		}
		$this->CI->benchmark->mark('object_from_db_end');
		// populate the ranking page's sources data
		// $this->populateRankingSourceData($rankingPageId, $status, $source_id, $rankingPage['rankingPageSourceData']);

		if(count($status) == 1 && $this->CI->input->post('isAjaxCall') != 1){
			// $this->rankingCache->storeRankingPageObject($rankingPageId,implode(',', $status),$rankingPageObject);
		}

		return $rankingPageObject;
	}
	
	private function populateRankingPageObject($rankingPageDetails = array(), $rankingPageData = array(), $rankingPageSourceData =array()){
		$rankingPageDataObjectsList = array();
		$rankingPageObject = false;
		if(!empty($rankingPageData)){
			$rankingPageDataObjectsList = $this->populateRankingPageDataObject($rankingPageData);
		}
                
		if(!empty($rankingPageDetails)){
			$rankingPageObject = $this->populateRankingPageDetailsObject($rankingPageDetails, $rankingPageDataObjectsList, $rankingPageSourceData);
		}
		// _p($rankingPageObject); die;
		return $rankingPageObject;
	}
	
	private function populateRankingPageDetailsObject($rankingPageDetails = array(), $rankingPageDataObjectsList = array(), $rankingPageSourceData = array()){
		$rankingPageObject = false;
		if(empty($rankingPageDetails) || empty($rankingPageDataObjectsList)){
			return $rankingPageObject;
		}
		
		$rankingPage = array();
        $rankingPage['rankingPageId']   = $rankingPageDetails['id'];
        $rankingPage['rankingPageName'] = $rankingPageDetails['ranking_page_text'];
        $rankingPage['publisherData']   = $rankingPageSourceData;
        $rankingPage['created']         = $rankingPageDetails['created'];
        $rankingPage['updated']         = $rankingPageDetails['updated'];
        $rankingPage['disclaimer']      = $rankingPageDetails['disclaimer'];
        $rankingPage['stream_id']       = $rankingPageDetails['stream_id'];
        $rankingPage['substream_id']    = $rankingPageDetails['substream_id'];
        $rankingPage['specialization_id'] = $rankingPageDetails['specialization_id'];
        $rankingPage['education_type']  = $rankingPageDetails['education_type'];
        $rankingPage['delivery_method'] = $rankingPageDetails['delivery_method'];
        $rankingPage['credential']      = $rankingPageDetails['credential'];
        $rankingPage['base_course_id']  = $rankingPageDetails['base_course_id'];
        $rankingPage['publisherId']     = $rankingPageDetails['default_publisher'];
        $rankingPage['tupleType']       = $rankingPageDetails['tuple_type'];
        $rankingPage['rankingPageData'] = $rankingPageDataObjectsList;
		
		$rankingPageObject = new RankingPage();
		$this->fillObjectWithData($rankingPageObject, $rankingPage);
		// _p($rankingPageObject); die;
		return $rankingPageObject;
	}
	
	private function populateRankingPageDataObject($rankingPageData = array()){
		$rankingPageDataObjectsList = array();
		if(empty($rankingPageData)){
			return $rankingPageDataObjectsList;
		}

		foreach($rankingPageData as $data){
			$rankingPageDataArray                        = array();
			$rankingPageDataArray['id']                  = $data['id'];
			$rankingPageDataArray['courseId']            = $data['course_id'];
			$rankingPageDataArray['instituteId']         = $data['institute_id'];		
			$rankingPageDataArray['sourceRank']         = $data['sourceRank'];		
			$rankingPageData = new RankingPageData();
			$this->fillObjectWithData($rankingPageData, $rankingPageDataArray);
			
			$rankingPageDataObjectsList[$data['course_id']] = $rankingPageData;
		}
		// _p($rankingPageDataObjectsList); die;
		return $rankingPageDataObjectsList;
	}

	/**
	 *@method: Method to populate RankingSource object with given data 
	 *@params: 1. Ranking page source data to be filled
	 *@return: Filled RankingSource object
	 *@author: Romil
	 */
	private function populateRankingSourceObject($rankingPageSourceData = array()){
		//error_log(print_r($rankingPageSourceData,true));
		$rankingPageSourceObject = false;
		if(empty($rankingPageSourceData)){
			return $rankingPageSourceObject;
		}
		
		$rankingPageSource                 = array();
		$rankingPageSource['source_id']    = $rankingPageSourceData['source_id'];
		$rankingPageSource['publisher_id'] = $rankingPageSourceData['publisher_id'];
		$rankingPageSource['name']         = $rankingPageSourceData['name'];
		$rankingPageSource['year']         = $rankingPageSourceData['year'];
		
		$rankingPageSourceObject = new RankingSource();
		$this->fillObjectWithData($rankingPageSourceObject, $rankingPageSource);
		
		return $rankingPageSourceObject;
	}
	
	/**
	 *@method: Method to get ranking page source data for a ranking page
	 *@params: 1. Ranking page id
	 *	   2. Status
	 *	   3. Ranking page Source data(optional)
	 *@return: Array containing ranking page source data
	 *@author: Romil
	 */
	public function populateRankingSourceData($rankingPageId, $status, $source_id, $rankingSourceData = array()){
		
		$this->rankingPageSources = array();
		
		// get ranking page source data if not provided
		if(empty($rankingSourceData))
		{
			$rankingSourceData = $this->rankingPageManager->getRankingSourceData($rankingPageId, $status, $source_id);
		}
		
		// get all ranking page source objects
		foreach($rankingSourceData as $rankingPageSourceRow)
		{
			$this->rankingPageSources[$rankingPageSourceRow['source_id']] = $this->populateRankingSourceObject($rankingPageSourceRow);
		}
	}
	
	public function getRankingPageSourceData(){
		return $this->rankingPageSources;
	}
	
	public function getMainSourceId(){
		return $this->mainSourceId;
	}
	
	public function getRankingPageCourses(){
		if(empty($this->rankingPageCoursesList))
			return array();
		else
			return $this->rankingPageCoursesList;
	}
	
	public function getRankingPageInstitutes(){
		if(empty($this->rankingPageInstitutesList))
			return array();
		else
			return $this->rankingPageInstitutesList;
	}
	
	public function getInstituteCourseMapping(){
		if(empty($this->instituteCourseMapping))
			return array();
		else
			return $this->instituteCourseMapping;
	}
}
