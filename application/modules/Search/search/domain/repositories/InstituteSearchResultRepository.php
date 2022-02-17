<?php

class InstituteSearchResultsRepository extends EntityRepository {
	
	private $searchWrapper;
	private $searchCommonLib;
	public function __construct() {
		parent::__construct();
		$this->CI->load->entities(array('Course','CourseAttribute','SalientFeature','RecruitingCompany','CourseFees','CourseDuration','Institute' ,'CourseDescriptionAttribute','ContactDetail','InstituteLocation','CourseLocationAttribute','ListingViewCount', 'HeaderImage'),'listing');
		$this->CI->load->entities(array('Exam'),'common');
		$this->CI->load->entities(array('Locality','Zone','City','State','Country'),'location');
		$this->CI->load->entities(array('CourseDocument'),'search');
		$this->CI->load->entities(array('Category'),'categoryList');
		$this->CI->load->builder('SearchBuilder');
		
		$this->searchWrapper = SearchBuilder::getSearchWrapper();
		SearchBuilder::loadSolrDataProcessor();
		
		$this->searchCommonLib 	   = SearchBuilder::getSearchCommon();
	}
	
	/**
	 * @method array getInstitutes: This function takes an array of courses and return array of courseDocument objects.
	 * @param array $instituteData : instituteData array contains all the courses array
	 * Sample array format
	 * array(
		'institute_id' => array(
							'data' => array(
									'0' => course array,
									'1' => course array
									...
							)
						)
	    )
	 * @return array :
	 * * array(
		'institute_id' => array(
							'course_id' => CourseDocumentEntity Object
							'course_id' => CourseDocumentEntity Object
							...
						)
		)
	 *
	*/
	public function getInstitutes($instituteData = array(), $sponsorType = "normal", $sortType = "best", $qerParams = array()) {
		if(!is_array($instituteData) || empty($instituteData)){
			return array();	
		}
		$courseDocumentListGroupedByInstitute = $this->getInstituteSearchResultsAsEntites($instituteData, $sponsorType, $sortType, $qerParams);
		return $courseDocumentListGroupedByInstitute;
	}
	
	private function getInstituteSearchResultsAsEntites($instituteResultList = array(), $sponsorType = "normal", $sortType = "best", $qerParams = array()){
		if(!is_array($instituteResultList) || empty($instituteResultList)){
			return false;
		}
		$courseDocumentListGroupedByInstitute = array();
		foreach($instituteResultList as $instituteId => $courseSolrDocuments){
			$courseSolrDocumentList = $courseSolrDocuments['data'];
			if(is_array($courseSolrDocumentList) && !empty($courseSolrDocumentList)){
				$courseDocumentListGroupedByInstitute[$instituteId] = $this->getMultipleCourseDocumentsEntitiesFromSolrDocuments($courseSolrDocumentList, $sponsorType, $sortType, $qerParams);
			}
		}
		return $courseDocumentListGroupedByInstitute;
	}
	
	private function getMultipleCourseDocumentsEntitiesFromSolrDocuments($solrCourseDocumentList = array(), $sponsorType = "normal", $sortType = "best", $qerParams = array()){
		$courseDocumentEntities = array();
		$locationPropertySet = getLocationPrioritySet($qerParams);
		if($sponsorType == "normal"){
			$solrCourseDocumentList = $this->searchCommonLib->applyQERLocationSet($solrCourseDocumentList, $locationPropertySet);	
		}
		if(is_array($solrCourseDocumentList) && !empty($solrCourseDocumentList)){
			switch($sortType){
				case 'best':
					uasort($solrCourseDocumentList, 'courseOrderCmp'); //Sorted on course_order
					break;
				case 'popular':
					uasort($solrCourseDocumentList, 'courseViewCountCmp'); //Sorted on course view count
					break;
			}
			foreach($solrCourseDocumentList as $solrCourseDocument){
				if(!empty($solrCourseDocument)){
					$courseDocumentEntities[$solrCourseDocument['course_id']] = $this->getCourseDocumentEntityFromSolrDocument($solrCourseDocument);	
				}
			}
		}
		return $courseDocumentEntities;
	}
	
	private function getCourseDocumentEntityFromSolrDocument($solrCourseDocument = array()){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$courseDocument = new CourseDocument();
			//Preprocess the data coming from solr results and make it compatible with Entity class variables.
			$courseDocumentData = SolrDataProcessor::getCourseDocumentData($solrCourseDocument);
			
			//Takes EntityObject and Data array, the data array keys should be compatible with Entity Object.
			$this->fillObjectWithData($courseDocument, $courseDocumentData);
			
			$this->loadCourseAttributesEntity($courseDocument, $solrCourseDocument);
			
			$this->loadCourseSalientFeaturesEntity($courseDocument, $solrCourseDocument);
			
			$this->loadCourseFeesEntity($courseDocument, $solrCourseDocument);
			
			$this->loadCourseRequiredExamEntity($courseDocument, $solrCourseDocument);
			
			$this->loadCourseTestPrepExamEntity($courseDocument, $solrCourseDocument);
			
			$this->loadCourseDurationEntity($courseDocument, $solrCourseDocument);
			
			$this->loadCourseViewCountEntity($courseDocument, $solrCourseDocument);
			
			$this->loadCourseLocationEntity($courseDocument, $solrCourseDocument);
			
			$this->loadCourseOtherLocationEntity($courseDocument, $solrCourseDocument);
			
			$this->loadCourseParentCategory($courseDocument, $solrCourseDocument);
			
			$this->loadCourseChildCategory($courseDocument, $solrCourseDocument);
			
			$this->loadInstituteData($courseDocument, $solrCourseDocument);
			
			return $courseDocument;
		}
	}
	
	private function loadCourseAttributesEntity($courseDocument, $solrCourseDocument){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$courseAttributeList = SolrDataProcessor::getCourseDocumentAttributes($solrCourseDocument);
			foreach($courseAttributeList as $courseAttribute){
				$courseAttributeEntity = new CourseAttribute();
				$this->fillObjectWithData($courseAttributeEntity, $courseAttribute);
				$courseDocument->setCourseAttributeEntity($courseAttributeEntity);
			}
		}
	}
	
	private function loadCourseSalientFeaturesEntity($courseDocument, $solrCourseDocument){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$courseSalientFeaturesList = SolrDataProcessor::getCourseDocumentSalientFeatures($solrCourseDocument);
			foreach($courseSalientFeaturesList as $courseSalientFeature){
				$salientFeatureEntity = new SalientFeature();
				$this->fillObjectWithData($salientFeatureEntity, $courseSalientFeature);
				$courseDocument->setCourseSalientFeatureEntity($salientFeatureEntity);
			}
		}
	}
	
	private function loadCourseFeesEntity($courseDocument, $solrCourseDocument){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$courseFees = SolrDataProcessor::getCourseDocumentFees($solrCourseDocument);
			$courseFeesEntity = new CourseFees();
			$this->fillObjectWithData($courseFeesEntity, $courseFees);
			$courseDocument->setCourseFeesEntity($courseFeesEntity);
		}
	}
	
	private function loadCourseDurationEntity($courseDocument, $solrCourseDocument){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$courseDuration = SolrDataProcessor::getCourseDocumentDuration($solrCourseDocument);
			$courseDurationEntity = new CourseDuration();
			$this->fillObjectWithData($courseDurationEntity, $courseDuration);
			$courseDocument->setCourseDurationEntity($courseDurationEntity);
		}
	}
	
	private function loadCourseViewCountEntity($courseDocument, $solrCourseDocument){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$courseViewCount = SolrDataProcessor::getCourseDocumentViewCount($solrCourseDocument);
			$courseViewCountEntity = new ListingViewCount();
			$this->fillObjectWithData($courseViewCountEntity, $courseViewCount);
			$courseDocument->setCourseViewCountEntity($courseViewCountEntity);
		}
	}
	
	private function loadCourseRequiredExamEntity($courseDocument, $solrCourseDocument){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$courseRequiredExams = SolrDataProcessor::getCourseDocumentRequiredExams($solrCourseDocument);
			foreach($courseRequiredExams as $exam){
				$courseRequiredExamEntity = new Exam();
				$this->fillObjectWithData($courseRequiredExamEntity, $exam);
				$courseDocument->setCourseRequiredExamEntity($courseRequiredExamEntity);
			}
		}
	}
	
	private function loadCourseTestPrepExamEntity($courseDocument, $solrCourseDocument){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$courseTestPrepExams = SolrDataProcessor::getCourseDocumentTestPrepExams($solrCourseDocument);
			foreach($courseTestPrepExams as $exam){
				$courseTestPrepEntity = new Exam();
				$this->fillObjectWithData($courseTestPrepEntity, $exam);
				$courseDocument->setCourseTestPrepExamEntity($courseTestPrepEntity);
			}
		}
	}
	
	private function loadCourseLocationAttributes($locationEntity, $locationAttributeList) {
		if(is_array($locationAttributeList) && !empty($locationAttributeList)){
			foreach($locationAttributeList as $locationAttributeKey => $locationAttributeValue) {
				$attributeList = array();
				$attributeList['attribute_type'] = $locationAttributeKey;
				$attributeList['attribute_value'] = $locationAttributeValue;
				$courseLocationAttribute = new CourseLocationAttribute();
				$this->fillObjectWithData($courseLocationAttribute, $attributeList);
				$locationEntity->addAttribute($courseLocationAttribute);
			}
		}
	}
	
	private function loadCourseLocationEntity($courseDocument, $solrCourseDocument){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$courseLocation = SolrDataProcessor::getCourseDocumentLocation($solrCourseDocument);
			$locationEntity = $this->getLocationEntity($courseLocation);
			$courseDocument->setCourseLocationEntity($courseLocation['institute_location_id'], $locationEntity);
		}
	}
	
	private function loadCourseOtherLocationEntity($courseDocument, $solrCourseDocument){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$otherCourseLocations = SolrDataProcessor::getCourseOtherLocations($solrCourseDocument);
			foreach($otherCourseLocations as $location){
				$courseLocation = SolrDataProcessor::getCourseDocumentLocation($location);
				$locationEntity = $this->getLocationEntity($courseLocation);
				$courseDocument->setCourseOtherLocationEntity($courseLocation['institute_location_id'], $locationEntity);
			}
		}
	}
	
	private function getLocationEntity($courseLocation = array()){
		$result['institute_location_id'] = $courseLocation['institute_location_id'];
		$result['entities'] = array();
			
		$country = new Country;
		$this->fillObjectWithData($country, $courseLocation);
		$result['entities']['country'] = $country;

		$state = new State;
		$this->fillObjectWithData($state, $courseLocation);
		$result['entities']['state'] = $state;

		$city = new City;
		$this->fillObjectWithData($city, $courseLocation);
		$result['entities']['city'] = $city;

		$zone = new Zone;
		$this->fillObjectWithData($zone, $courseLocation);
		$result['entities']['zone'] = $zone;

		$locality = new Locality;
		$this->fillObjectWithData($locality, $courseLocation);
		$result['entities']['locality'] = $locality;
	
		$result['customLocalityName'] = $courseLocation['customLocalityName'];
		$location = new InstituteLocation;
		$this->fillObjectWithData($location, $result);
		
		$courseLocationAttributes = $courseLocation['course_location_attributes'];
		$courseLocationAttributesList = json_decode(html_entity_decode($courseLocationAttributes), true);
		$this->loadCourseLocationAttributes($location, $courseLocationAttributesList);
		return $location;
	}
	
	private function loadCourseParentCategory($courseDocument, $solrCourseDocument){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$courseParentCategory = SolrDataProcessor::getCourseDocumentParentCategory($solrCourseDocument);
			foreach($courseParentCategory as $category){
				$categoryEntity = new Category();
				$this->fillObjectWithData($categoryEntity, $category);
				$courseDocument->setCourseParentCategoryEntity($category['boardId'], $categoryEntity);
			}
		}
	}
	
	private function loadCourseChildCategory($courseDocument, $solrCourseDocument){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$courseChildCategories = SolrDataProcessor::getCourseChildCategory($solrCourseDocument);
			foreach($courseChildCategories as $category){
				$categoryEntity = new Category();
				$this->fillObjectWithData($categoryEntity, $category);
				$courseDocument->setCourseChildCategoryEntity($category['boardId'], $categoryEntity);
			}
		}
	}
	
	private function loadInstituteData($courseDocument, $solrCourseDocument){
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$instituteData = SolrDataProcessor::getInstituteDocumentData($solrCourseDocument);
			$instituteEntity = new Institute();
			$this->fillObjectWithData($instituteEntity, $instituteData);
			
			$headerImageEntity = $this->loadInstituteHeaderImages($solrCourseDocument);
			$instituteEntity->addHeaderImage($headerImageEntity);
			
			$instituteViewCountEntity = $this->loadInstituteViewCount($solrCourseDocument);
			$instituteEntity->addViewCount($instituteViewCountEntity);
			
			$courseDocument->setCourseInstituteEntity($instituteEntity);
		}
	}
	
	private function loadInstituteHeaderImages($solrCourseDocument){
		$headerImageEntity = "";
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$instituteHeaderImages = SolrDataProcessor::getInstituteHeaderImages($solrCourseDocument);
			$headerImageEntity = new HeaderImage();
			$this->fillObjectWithData($headerImageEntity, $instituteHeaderImages);
		}
		return $headerImageEntity;
	}
	
	private function loadInstituteViewCount($solrCourseDocument){
		$instituteViewCountEntity = "";
		if(is_array($solrCourseDocument) && !empty($solrCourseDocument)){
			$instituteViewCount = SolrDataProcessor::getInstituteViewCountDetails($solrCourseDocument);
			$instituteViewCountEntity = new ListingViewCount();
			$this->fillObjectWithData($instituteViewCountEntity, $instituteViewCount);
		}
		return $instituteViewCountEntity;
	}
}
?>