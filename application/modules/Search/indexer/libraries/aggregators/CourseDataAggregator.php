<?php 

class CourseDataAggregator{
	
	private $_CI;
	private $courseId;

	public function __construct()
	{
		$this->_CI = & get_instance();
		$this->isOnlyFeesCase = false;

		$this->_CI->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepo = $courseBuilder->getCourseRepository();
		$this->courseRepo->disableCaching();


		$this->_CI->load->library(array(
										'indexer/processors/course/CourseBasicData',
										'indexer/processors/course/CourseLocationData',
										'indexer/processors/course/CourseExamData',
										'indexer/processors/course/CourseTypeInformationData',
										'indexer/processors/course/CourseFeesData',
										'ShikshaPopularity/ShikshaPopularityDataLib',
										'indexer/processors/course/CourseStatusData',
										'listingCommon/ListingCommonLib',
										'nationalCourse/CourseDetailLib'
									));
		$this->_CI->load->config('indexer/nationalIndexerConfig');

		
	}

	/**
	* Function to get the Course Object and Process/Format the Data for Solr
	*/
	public function getData($courseId, $fieldArray = array(), $extraData = array()){

		// Get the Course Object
		$sections =  $this->_filterSectionsInformation($fieldArray);

		$courseObject = $this->courseRepo->find($courseId,$sections);
		$idFromObject = $courseObject->getId();
		if(empty($idFromObject)){
			return null;
		}

		// $courseListingHieracyData = $this->courseRepo->getCourseListingHierarchy($courseObject->getId());
		$listingHieracyData = $this->_CI->coursedetaillib->getCourseListingHierarchyData(array($courseId),array($courseId => $courseObject));
		// $listingHieracyData = $this->_CI->coursedetaillib->getCourseListingHierarchyDataNew(array($courseId),array($courseId => $courseObject));
		$courseListingHieracyData = $listingHieracyData[$courseId];
		unset($listingHieracyData);
		$courseData = $this->_processDataFromObject($courseId, $courseObject,$courseListingHieracyData,$sections, $extraData);
		return $courseData;
	}

	// 
	private function _processDataFromObject($courseId,$courseObject,$courseListingHieracyData,$sections, $extraData){
		
		// For Each Sections, iterate and process the Data
		foreach ($sections as $key => $sectionName) {
			$courseData[$sectionName] = $this->_processSections($courseId, $courseObject, $sectionName,$courseListingHieracyData);
		}
		return $courseData;
		//$this->_separateSections($courseData, 'basic', 'course_order', array());
	}

	private function _processSections($courseId, $courseObject,$sectionName,$courseListingHieracyData){
		$courseData = array();
		$customData['listingId'] = $courseId;
		$customData['listingType'] = 'course';
		$customData['sectionName'] = $sectionName;
		switch ($sectionName) {
			case 'basic':
			case 'course_status':
				// Process Basic Section Data
				$customData['courseListingHieracyData'] =  $courseListingHieracyData;
				$sectionData = $this->_CI->coursebasicdata->compileData($courseObject,$customData);
				unset($customData['courseListingHieracyData']);
				$statusData = $this->_CI->coursestatusdata->processCourseStatusValues($courseId, $courseObject, $courseListingHieracyData);
				foreach ($statusData as $key => $value) {
					$sectionData[$key] = $value;
				}
				break;

			case 'location' : 
				// Process Location Section Data
				$sectionData = $this->_CI->courselocationdata->compileData($courseObject,$customData);
				break;

			case 'fees' :
				// Process Fees Section Data
				$sectionData = $this->_CI->coursefeesdata->compileData($courseObject,$customData);
				break;

			case 'eligibility' :
				//Process Exams Section Data

				$sectionData = $this->_CI->courseexamdata->compileData($courseObject,$customData);
				break;

			case 'course_type_information' : 
				// Process course type Section Data(Entry / Exit Course)

				$sectionData = $this->_CI->coursetypeinformationdata->compileData($courseObject,$customData);
				break;

			case 'course_cr_exist' :
				// CR Exist or NOT
				$crExist = $this->courseRepo->isCRExistForCourse($courseId);
				$sectionData['nl_course_cr_exist'] = $crExist;
				break;


			case 'review_count' :
				// Review Count
				$reviewCount = $courseObject->getReviewCount();
				if(empty($reviewCount)){
					$reviewCount = 0;
				}
				$sectionData['nl_course_review_count'] = $reviewCount;
				$courseId = $courseObject->getId();
				$aggRatingData = modules::run('ListingScripts/generateReviewsCacheForCourses', $courseId, true);
				$aggRating =  $aggRatingData[$courseId]['aggregateRating']['averageRating']['mean'];
				if(!empty($aggRating) && $aggRating > 0) {
					$sectionData['nl_course_review_rating_agg'] = $aggRating;
				}
				break;

			case 'last_modify_date':
				// Last modify Date
				$lastModifyDate = $this->_CI->listingcommonlib->getNationalListingsLastModifyDate('course',array($courseId));
				if(!empty($lastModifyDate)){
					$sectionData['nl_course_last_modify_date'] = solrDateFormater($lastModifyDate[$courseId]);
				}else{
					$sectionData['nl_course_last_modify_date'] = null;
				}
				break;
				
			case 'course_order':
			case 'view_count':
				$insttid = $courseObject->getInstituteId();

				$viewCount = $this->_CI->listingcommonlib->listingViewCount('course',array($courseId));

				if(!empty($viewCount) && !empty($viewCount[$courseId])){
					$sectionData['nl_course_view_count_year'] = $viewCount[$courseId];	
					$finalSolrViewCount = $viewCount[$courseId];
				}else{
					$sectionData['nl_course_view_count_year'] = 0;	
					$finalSolrViewCount = 0;
				}
				
				$courseOrder = $courseObject->getOrder();
				if(!empty($courseOrder)){
					$sectionData['nl_course_order'] = $courseOrder;
					$finalSolrCourseOrder = $courseOrder;
				}else{
					$sectionData['nl_course_order'] = null;
					$finalSolrCourseOrder = "";
				}

				$sectionData['nl_insttId_courseId_cOrder_viewCnt'] = $insttid.":".$courseId.":".$finalSolrCourseOrder.":".$finalSolrViewCount;

				break;

		}
		return $sectionData;
	}


	/*
	* Map Popularity To Hierachy
	*/
	function popularityData($courseTypeInformationData, $institutePopularityData){

		foreach ($courseTypeInformationData as $key => $value) {
			$streamId = $value['nl_stream_id'];
			$substreamId = $value['nl_substream_id'];
			$baseCourse = $value['nl_base_course_id'];
			$result = $this->_CI->shikshapopularitydatalib->mapPopularityToBaseEntities($streamId, $substreamId, $baseCourse,$institutePopularityData);

			if(isset($result['popularity_score_stream'])){
				$courseTypeInformationData[$key]['nl_institute_popularity_score_stream'] = $result['popularity_score_stream'];	
			}
			if(isset($result['popularity_score_stream_substream'])){
				$courseTypeInformationData[$key]['nl_institute_popularity_score_substream'] = $result['popularity_score_stream_substream'];	
			}
			if(isset($result['popularity_score_base_course'])){
				$courseTypeInformationData[$key]['nl_institute_popularity_score_base_course'] = $result['popularity_score_base_course'];	
			}
		}
		return $courseTypeInformationData;
	}


	private function _filterSectionsInformation($sections=array()){

		if(in_array(COURSE_FEES_SECTION_DATA, $sections)){
			$sections[] = COURSE_LOCATION_SECTION_DATA;
			$this->isOnlyFeesCase = true;
		}

		$sections = array_unique($sections);

		$sectionsForObject = array();
		$sectionMappings = $this->_CI->config->item('CourseSectionMappings');

		if(empty($sections)){
			$sections = array_keys($sectionMappings);
		}

		foreach ($sections as $key => $value) {
			$sectionsForObject[] = $sectionMappings[$value];
		}
		
		if(in_array("basic", $sectionsForObject) && in_array("course_status", $sectionsForObject)){
			$key = array_search("course_status",$sectionsForObject);
			unset($sectionsForObject[$key]);
		}

		return $sectionsForObject;

	}


}

?>
