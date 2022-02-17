<?php 

	class NationalIndexingLibrary {
	
	private $_CI;


	public function __construct()
	{
		$this->_CI = & get_instance();
		$this->validIndexTypes = array("institute","course","autosuggestor","university","exam", "examChildPage","allexam","collegereview","question","question_tag","article","location","collegeshortlist");

		$this->_CI->load->model("indexer/NationalIndexingModel");
		$this->nationalIndexingModel = new NationalIndexingModel();

		$this->_CI->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;

		$this->locationRepository = $locationBuilder->getLocationRepository();
		$this->_CI->load->library('listingBase/BaseAttributeLibrary');
		$this->baseAttributeLibrary = new BaseAttributeLibrary;

		$this->_CI->load->model('search/SearchModel');
		$this->_CI->load->builder('SearchBuilder', 'search');

		$this->_CI->config->load('search_config');
		$this->searchServer = $this->_CI->config->item('search_server');

		$this->_CI->load->library('listing/AbroadListingCommonLib');
		$this->abroadLib = new AbroadListingCommonLib;

		$this->_CI->load->library('indexer/SolrServerLib');
		$this->solrServerLib = new SolrServerLib;

		$this->_CI->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$this->popularGroupRepository = $listingBase->getPopularGroupRepository();
		$this->baseCourseRepository = $listingBase->getBaseCourseRepository();

		$this->_CI->load->config('indexer/nationalIndexerConfig');

		$this->_CI->load->library('CA/CADiscussionHelper');
        $this->caDiscussionHelper =  new CADiscussionHelper();

        $this->_CI->load->helper("search/SearchUtility");

        $this->_CI->load->library('indexer/aggregators/CourseDataAggregator');
        $this->courseDataAggregator = new CourseDataAggregator;

        $this->_CI->load->library('indexer/aggregators/InstituteDataAggregator');
        $this->instituteDataAggregator = new InstituteDataAggregator;

        $this->_CI->load->library('indexer/SolrDocuementGeneratorLib');
        $this->solrDocuementGeneratorLib = new SolrDocuementGeneratorLib;

        $this->_CI->load->library('rankingV2/RankingCommonLibv2');
        $this->rankingCommonLibv2 = new RankingCommonLibv2();

	}

	/** 
	* Function To Index the Institute
	* @param $id - Integer - Institute ID to index
	* @param $typeOfIndex - String - VALUES - complete / partial - Whether to Index Particular Field or Complete Institute - 
	*
	*/
	public function indexInstitute($id=0,$fieldsData=array()){
		try{
			$fieldsArray = array();
			$extraData = array();
			if(!empty($fieldsData)){
				if(isset($fieldsData['fields'])){
					$fieldsArray = $fieldsData['fields'];
				}
				if(isset($fieldsData['extraData'])){
					$extraData = $fieldsData['extraData'];	
				}
			}

			// $fieldsArray = array(POPULARITY_SECTION);

			// Fetch All the Intitute Data
			$instituteData = $this->getDataForInstituteToIndex($id,$fieldsArray,$extraData);


			$possibleInstituteSections = $this->_CI->config->item('INSTITUTE_SECTIONS');
			$possibleCourseSections = $this->_CI->config->item('COURSE_SECTIONS');

			$instituteSection = false;
			$courseSection = false;

			// DETECTING SECTIONS

			$instituteFieldsPresent = array_intersect($possibleInstituteSections, $fieldsArray);
			$courseFieldsPresent = array_intersect($possibleCourseSections, $fieldsArray);

			if(!empty($fieldsArray) && !empty($instituteFieldsPresent)){
				$instituteSection = true;
			}

			if(!empty($fieldsArray) && !empty($courseFieldsPresent)){
				$courseSection = true;
			}

			
			// Blank Institute Data AND NOT CASE FOR COURSE SECTIONS => MEANING NO DATA TO INDEX
			if(empty($instituteData) && !$courseSection) {
				return;
			}

			// If full Indexing(Emoty Fields Array OR course Sections are present)
			if(empty($fieldsArray) || $courseSection){	
				$coursesList = $this->instituteDataAggregator->getCoursesListForInstitues($id);
				$indexResponse = $this->indexAllCourses($coursesList,$fieldsArray,$extraData,$instituteData);
			}
			else if($instituteSection){ // If Only Institute Sections are Present
				$indexingEntity['institute'] = $id;
				$indexResponse = $this->solrDocuementGeneratorLib->generateDocumenteAndIndex(0, null,$instituteData,$indexingEntity,$fieldsArray, $extraData);

			}
		}
		catch(Exception $e){
			$this->logException("Exception Occurs While Indexing the Institute with Id ".$id);
		}
		return $indexResponse;
		
	}


	public function indexInstituteForHierarchyChange($instituteId = 0){
		$fieldsArray = array(INSTITUTE_COURSE_STATUS);
		$solrUniqueIdConditions['exactCond'] = "fq=nl_course_hierarchy_institute_id:$instituteId+OR+nl_course_affiliations_institute_id:$instituteId";
		$uniqueIdList = $this->solrServerLib->getUniqueIdsToUpdate($solrUniqueIdConditions);
		$uniqueIdListData = $this->solrServerLib->parseUniqueKey($uniqueIdList,array('course_id'));
		$indexedCourses = array();
		foreach ($uniqueIdListData as $key => $value) {
			if(!in_array($value['course_id'], $indexedCourses)){
				$indexedCourses[] = $value['course_id'];
				$courseId = $value['course_id'];
				$this->indexAllCourses(array($courseId),$fieldsArray);
			}
			
		}
	}

	/**
	* Function to Fetch the Data for Particular Institue
	* @param - InstituteId - Integer 
	*
	* @return $instituteData - One D Array with Institute Data
	*/

	private function getDataForInstituteToIndex($instituteId = null,$fieldsArray=array(),$extraData=array()){
		
		try{
			if($instituteId == null){
				return array();
			}
			$instituteData = $this->instituteDataAggregator->getData($instituteId,$fieldsArray,$extraData);
			return $instituteData;
		}
		catch(Exception $e){
			$this->logException("Exception Occurs While Fetching the Data for Institute with Id ".$instituteId,true);
		}
		
	}	

	// Function to Index the Course
	public function indexAllCourses($courseList,$fieldsArray=array(),$extraData=array(),$instituteData=array()){

		//get ranking data for course, if available
		$latestRankSourceData = $this->rankingCommonLibv2->getLatestRankingSourcesForCourses($courseList);
		foreach ($courseList as $courseId) {
			// Fetch The Data for institute
			if(!empty($latestRankSourceData)){
				$extraData['latestRankSourceData'] = $latestRankSourceData[$courseId];
			}
			$indexResponse = $this->indexCourse($courseId,$fieldsArray,$extraData,$instituteData);
		}
		return $indexResponse;
	}

	private function indexCourse($courseId,$fieldsArray=array(),$extraData=array(),$instituteData=array()){

		try{

			// $fieldsArray = array(INSTITUTE_COURSE_STATUS);
			// Fetch Course Data From Repo
			$courseData = $this->courseDataAggregator->getData($courseId, $fieldsArray, $extraData);
			
			// Fetch Institute Data(Needed in Case of Course Indexing)
			if(empty($instituteData) && isset($courseData['basic']['nl_institute_id'])){
				$instituteId = $courseData['basic']['nl_institute_id'];
				if($instituteId != null && $instituteId != 0){
					$instituteData = $this->getDataForInstituteToIndex($instituteId,$fieldsArray,$extraData);					
				}else{
					return;
				}
			}

			// Process Course & Institute Data For Popularity(Map Popularity to hierarchy)
			if(isset($courseData['course_type_information'])){
				$courseData['course_type_information'] = $this->courseDataAggregator->popularityData($courseData['course_type_information'],$instituteData['popularity']);
				unset($instituteData['popularity']);
			}
			if(!empty($extraData['latestRankSourceData']['latestSourceIdNameMap'])){
				// Adding rank source data to course data
				if(isset($extraData['latestRankSourceData']['rankingPageIds'])){
					$courseData['ranking_source_id']['nl_course_ranking_page_id'] = $extraData['latestRankSourceData']['rankingPageIds'];
					$courseData['ranking_source_id']['nl_course_ranking_source_id'] = $extraData['latestRankSourceData']['latestSourceIds'];
				}
				if(isset($extraData['latestRankSourceData']['latestSourceIdNameMap'])){
					foreach ($extraData['latestRankSourceData']['latestSourceIdNameMap'] as $pageId => $sourceNameData) {
						if(count($sourceNameData) > 0){
							$courseData['ranking_page_sources']['nl_course_ranking_sources_'.$pageId] = array();
							foreach ($sourceNameData as $k => $v) {
								$sourceId = explode(':', $v);
								$courseData['ranking_page_sources']['nl_course_ranking_sources_'.$pageId][] = $sourceId[1];
							}
						}
						$courseData['ranking_source_name_id_map']['nl_course_ranking_source_name_id_map_'.$pageId] = $sourceNameData;
					}
				}
				if(isset($extraData['latestRankSourceData']['sourceRankData'])){
					foreach ($extraData['latestRankSourceData']['sourceRankData'] as $pageId => $rankData) {
						foreach ($rankData as $source => $rank) {
							$courseData['source_rank_data']['nl_course_rank_source_'.$source.'_'.$pageId] = $rank;
						}
					}
				}
				unset($extraData['latestRankSourceData']);
			}

			// Generate Document & Indexing Call
			$indexingEntity['course'] = $courseId;
			$indexResponse = $this->solrDocuementGeneratorLib->generateDocumenteAndIndex($courseId, $courseData, $instituteData, $indexingEntity, $fieldsArray, $extraData);

		}
		catch(Exception $e){
			$this->logException("Exception occurs while indexing the course with courseId => ".$courseId);
			error_log($e->getMessage());
			$dataToIndex = array();
		}
		return $indexResponse;
		
	}

	/**
	* Function to Validate whether the Request is valid or not for Indexing
	*/
	public function validateIndexingRequest($type,$id){

		if(!in_array($type, $this->validIndexTypes)) return false;
		return true;

		if($type == "autosuggestor"){
			$result = true;
		}else if(!ctype_digit($id) || $id <= 0){
			$result = false;
		}else{
			$result = true;
		}

		return $result;
	}

	public function logException($exceptionMessage,$throwException=false){
		error_log($exceptionMessage, 3, "/tmp/indexing_exceptions.log");
		_p($exceptionMessage);
		if($throwException === true){
			throw new Exception($exceptionMessage);
		}
	}

	public function addToNationalIndexQueue($listingId=null, $listingType=null,$operation = 'index',$sections=array(),$extraData = null){
		if(empty($listingId) || empty($listingType)){
			return;
		}

		$this->nationalIndexingModel->addToIndexQueue($listingId, $listingType,$operation, $sections, $extraData);
	}
}

?>
