<?php

class InstituteRepository extends EntityRepository
{
	private $instituteFinderDao;
	private $courseRepository;
	private $locationRepository;
	private $abroadInstRepo;

	function __construct($dao,$cache,$instituteFinderDao,CourseRepository $courseRepository,LocationRepository $locationRepository,$model)
	{
		parent::__construct($dao,$cache,$model);
		$this->instituteFinderDao = $instituteFinderDao;
		$this->courseRepository = $courseRepository;
		$this->locationRepository = $locationRepository;
       
		/*
		 * Load entities required
		 */
		$this->CI->load->entities(array('Institute','InstituteLocation','HeaderImage','Ranking','ListingMedia','AlumaniFeedback','InstituteDescriptionAttribute','InstituteJoinReason','ContactDetail','ListingViewCount','InstituteFacility'),'listing');
		$this->CI->load->entities(array('Locality','Zone','City','State','Country', 'Region'),'location');
		
		$this->CI->load->entities(array('AbroadInstitute','InstituteLocation','ContactDetail'),'listing');
		$this->CI->load->entities(array('Locality','Zone','City','State','Country', 'Region'),'location');
	}
	/**
	 * return institute object with required value objects
	 *
	 * @param integer $institute_id institute id
	 * @param array $value_objects_array
	 * @return Object
	 */
	public function findInstituteWithValueObjects($institute_id,$value_objects_array = array(), $base_institute_object = NULL) {
		// check if institute id is valid or not
		Contract::mustBeNumericValueGreaterThanZero($institute_id,'Institute ID');
		// get base institute object first
		if(!isset($base_institute_object)) {
		 $base_institute_object = $this->find($institute_id);
		}
			
		foreach ($value_objects_array as $value_object_name) {
			$this->_populateValueObject($institute_id,$value_object_name,$base_institute_object);
		}
		return $base_institute_object;
	}
	/**
	 * return location wise course id list for an institute
	 *
	 * @param integer $institute_id institute id
	 * @return array
	 */
	public function getLocationwiseCourseListForInstitute($institute_id) {
		// check if institute id is valid or not
		Contract::mustBeNumericValueGreaterThanZero($institute_id,'Institute ID');

		// caching it only for Edukart institute(having huge amt of data)
		if($institute_id == 35861){
			if($this->caching && $results = $this->cache->getLocationwiseCourseListForInstitute($institute_id)) {
	       		return $results;
	       	}

			$results = $this->model->getLocationwiseCourseListForInstitute($institute_id);

			if(!empty($results)){
	       		$this->cache->storeLocationwiseCourseListForInstitute($results,$institute_id);
	       	}
       	}
       	else{
       		$results = $this->model->getLocationwiseCourseListForInstitute($institute_id);
       	}

		if(count($results) == 0) {
			return $this->populateErrorObject();
		}
		return $results;
	}
	/**
	 * return category ids list of an istitute or course
	 *
	 * @param integer $institute_id institute id
	 * @param integer $listing_type type of listing (institute or course)
	 * @return array contains category list
	 */
	public function getCategoryIdsOfListing($listing_id,$listing_type,$indexFlag='false', $multipleEntries = FALSE) {
		// check if institute id is valid or not
		//Contract::mustBeNumericValueGreaterThanZero($listing_id,'Institute ID');
		$category_list = $this->model->getCategoryIdsOfListing($listing_id,$listing_type,$indexFlag, $multipleEntries);

		if(count($category_list) == 0) {
			return $this->populateErrorObject();
		}
		return $category_list;
	}
	
	/**
	 * return main category ids list of an istitute or course
	 *
	 * @param integer $institute_id institute id
	 * @param integer $listing_type type of listing (institute or course)
	 * @return array contains category list
	 */
	public function getMainCategoryIdsOfListing($listing_id,$listing_type) {
		// check if institute id is valid or not
		//Contract::mustBeNumericValueGreaterThanZero($listing_id,'Institute ID');
		$category_list = $this->model->getMainCategoryIdsOfListing($listing_id,$listing_type);

		if(count($category_list) == 0) {
			return $this->populateErrorObject();
		}
		return $category_list;
	}
	
	
	/**
	 * return alumani reviews for an institute
	 *
	 * @param integer $institute_id institute id
	 * @param integer $start start record
	 * @param integer $limit maximum number of results
	 * @return array contains alumanireviews and threadlist
	 */
	public function findAlumanisReviewsOnInstitute($institute_id,$start=0,$limit=0) {
		// check if institute id is valid or not
		Contract::mustBeNumericValueGreaterThanZero($institute_id,'Institute ID');
		$alumaniReviewsResults = $this->model->getDataForAlumaniReviews($institute_id,$start,$limit);

		if(count($alumaniReviewsResults) == 0) {
			return $this->populateErrorObject();
		}

		foreach ($alumaniReviewsResults as $result) {
			if($result['course_id'] > 0) {
				$result['course_name'] = $this->courseRepository->find($result['course_id'])->getName();				
			}
			
			$object = new AlumaniFeedback();
			$this->fillObjectWithData($object,$result);
			$alumaniReviews['alumniReviews'][] = $object;
			if(isset($result['thread_id'])) {
				$alumaniReviews['threadlist'][] = $result['thread_id'];
			}
		}
		return $alumaniReviews;
	}
	
	/**
	 * return alumani reviews for an institute
	 *
	 * @param integer $institute_id institute id
	 * @param integer $start start record
	 * @param integer $limit maximum number of results
	 * @return array contains alumanireviews and threadlist
	 */
	public function findAlumanisReviewsOnInstitutes($institute_ids,$start=0,$limit=0) {
		
		Contract::mustBeNonEmptyArrayOfIntegerValues($institute_ids,'Institute IDs');
		$alumaniReviewsResults = $this->model->getAlumniReviewsForInstitutes($institute_ids,$start,$limit);
		
		$alumniReviews = array();
		
		foreach ($alumaniReviewsResults as $instituteId => $alumniReviewsForInstitute) {
			
			if(!$alumniReviewsForInstitute) {
				$alumniReviews[$instituteId] = $this->populateErrorObject();
			}
			else {
				foreach($alumniReviewsForInstitute as $alumniReviewData) {
					$object = new AlumaniFeedback();
					$this->fillObjectWithData($object,$alumniReviewData);
					$alumaniReviews[$instituteId]['alumniReviews'][] = $object;
					if(isset($alumniReviewsForInstitute['thread_id'])) {
						$alumaniReviews[$instituteId]['threadlist'][] = $alumniReviewsForInstitute['thread_id'];
					}
				}
			}
		}
		return $alumaniReviews;
	}
	
	
	/*
	 * Find an institute using institute id
	 */
	public function find($instituteId)
	{
		Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');

		if($this->caching && $cachedInstitute = $this->cache->getInstitute($instituteId)) {
			return $cachedInstitute;
		}
        
		if($this->model->checkIfInstituteIdBelongsToAbroad($instituteId)) {
			$this->_loadAbroadInstituteRepo();
			return $this->abroadInstRepo->find($instituteId);
		}
        
		$instituteDataResults = $this->dao->getDataForInstitute($instituteId);
		$institute = $this->_loadOne($instituteDataResults);
		$this->cache->storeInstitute($institute);
		return $institute;
	}

	/*
	 * Find multiple institutes using institute ids
	 */
	public function findMultiple($instituteIds)
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds,'Institute IDs');

		$orderOfInstituteIds = $instituteIds;
		$institutesFromCache = array();

		global $restrictedInstituteIds;
		if(DISABLE_RESTRICTED_LISTING) {
			foreach ($instituteIds as $key => $value) {
				if(in_array($value, $restrictedInstituteIds)) {
					unset($instituteIds[$key]);
				}
			}
		}

		if($this->caching) {
			$institutesFromCache = $this->cache->getMultipleInstitutes($instituteIds);
			$foundInCache = array_keys($institutesFromCache);
			$instituteIds = array_diff($instituteIds,$foundInCache);
		}
        
		$abroadInstituteIds = $this->model->fetchDiffOfValidAbroadInstituteIds($instituteIds);
		$instituteFromAbroadRepo = array();
		if(count($abroadInstituteIds) > 0) {
			$this->_loadAbroadInstituteRepo();
			$instituteFromAbroadRepo = $this->abroadInstRepo->findMultiple($abroadInstituteIds);
			$instituteIds = array_diff($instituteIds,$abroadInstituteIds);
		}
				
		if(count($instituteIds) > 0) {
			$instituteDataResults = $this->dao->getDataForMultipleInstitutes($instituteIds);
			$institutesFromDB = $this->_load($instituteDataResults);
			foreach($institutesFromDB as $institute) {
				$this->cache->storeInstitute($institute);
			}
		}

		$institutes = array();
		foreach($orderOfInstituteIds as $instituteId) {
			if(isset($institutesFromCache[$instituteId])) {
				$institutes[$instituteId] = $institutesFromCache[$instituteId];
			}
			else if(isset($institutesFromDB[$instituteId])) {
				$institutes[$instituteId] = $institutesFromDB[$instituteId];
			}
			else if(isset($instituteFromAbroadRepo[$instituteId])) {
				$institutes[$instituteId] = $instituteFromAbroadRepo[$instituteId];
			}
		}

		return $institutes;
	}

	/***** LOAD ABROAD INST REPO ***/

	private function _loadAbroadInstituteRepo(){
	 if(empty($this->abroadInstRepo)) {
	 	$this->CI->load->builder('ListingBuilder','listing');
	 	$listingBuilder 			= new ListingBuilder;
	 	$this->abroadInstRepo = $listingBuilder->getAbroadInstituteRepository();
	 }
	}
	
	
	/*
	 * Find institutes with given courses
	 * Accepts array <Institute Id> => <Array of course ids>
	 * e.g.
	 * [12 => array(45,876)]
	 * [56 => array(89,65)]
	 */
	public function findWithCourses($institutesWithCourses = array())
	{
		/*
		 * Extract institute ids and courses ids
		 * So that these can be used in findMultiple()
		 */
		$instituteIds = array();
		$courseIds = array();

		global $restrictedInstituteIds;
		foreach($institutesWithCourses as $instituteId => $instituteCourseIds) {
			if(!(DISABLE_RESTRICTED_LISTING && in_array($instituteId, $restrictedInstituteIds))) {
				$instituteIds[] = $instituteId;
				$instituteCourseIds = (array) $instituteCourseIds;
				foreach($instituteCourseIds as $instituteCourseId) {
					$courseIds[] = $instituteCourseId;
				}
			}
		}

		if(count($instituteIds) > 0 && count($courseIds) > 0) {
			$sTime = microtime(true);
			$institutes = $this->findMultiple($instituteIds);
			$courses = $this->courseRepository->findMultiple($courseIds);

			if(EN_LOG_FLAG) error_log("\narray( section => 'Inside findWithCourses data from cache', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);

			foreach($institutesWithCourses as $instituteId => $instituteCourseIds) {
				$instituteCourseIds = (array) $instituteCourseIds;
				$courseCount = 0;   // to check if at least one course exists for an institute or not 
				foreach($instituteCourseIds as $instituteCourseId) {
					if(isset($institutes[$instituteId])) {
						$courseCount++;
						$institutes[$instituteId]->addCourse($courses[$instituteCourseId]);
					}
				}
                if($courseCount == 0) {
                	unset($institutes[$instituteId]);
                	$this->_initiateErrorReportingLib();
                	$this->listingErrorReportingLib->registerToSendErrorAlert($instituteId, "Institute", $_SERVER['SCRIPT_URI'], "Course not present in DB","National Category Pages","Institute Repo","286");
                	$this->listingErrorReportingLib->sentErrorAlert();
                }
			}

			return $institutes;
		}
	}

	public function getCategoryPageInstitutes(CategoryPageRequest $request)
	{
		// load the models
		$this->CI->load->model('listing/institutefindermodel');
		$this->CI->load->model('location/locationmodel');
		$this->CI->load->model('categoryList/categorymodel');
		
		// get the objects of the models
		$instituteFinderModelObj 	= new InstituteFinderModel;
		$locationModelObj 		= new LocationModel;
		$categoryModelObj		= new CategoryModel;
		// initialize the model
		$instituteFinderModelObj->init($locationModelObj, $categoryModelObj );
		
		return $instituteFinderModelObj->getCategoryPageInstitutes($request);
		//return $this->instituteFinderDao->getCategoryPageInstitutes($request);
	}

	public function getTopInstitutesInCategory($categoryId)
	{
		return $this->instituteFinderDao->getTopInstitutesInCategory($categoryId);
	}

	public function getViewCount($instituteIds)
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds,'Institute IDs');
		return $this->dao->getDataForMultipleInstitutes($instituteIds,array('view_count'));
	}

	public function getModifiedInstitutes($criteria)
	{
		return $this->instituteFinderDao->getModifiedInstitutes($criteria);
	}

	public function getExpiredStickyInstitutes($numDays)
	{
		return $this->instituteFinderDao->getExpiredStickyInstitutes($numDays);
	}

	public function getModifiedStickyInstitutes($criteria)
	{
		return $this->instituteFinderDao->getModifiedStickyInstitutes($criteria);
	}

	public function getExpiredMainInstitutes($numDays)
	{
		return $this->instituteFinderDao->getExpiredMainInstitutes($numDays);
	}

	public function getModifiedMainInstitutes($criteria)
	{
		return $this->instituteFinderDao->getModifiedMainInstitutes($criteria);
	}

	public function getLiveInstitutes()
	{
		return $this->instituteFinderDao->getLiveInstitutes();
	}

	public function unpublishExpiredStickyInstitutes()
	{
		return $this->dao->unpublishExpiredStickyInstitutes();
	}

	public function unpublishExpiredMainInstitutes()
	{
		return $this->dao->unpublishExpiredMainInstitutes();
	}

	/*
	 * ORM Functions
	 * Create domain objects from Db resultsets
	 */
	private function _loadOne($results)
	{
		$institutes = $this->_load(array($results));
		return current($institutes);
	}
	// handles multiple institute objects
	private function _load($results)
	{
		$institutes = array();

		if(is_array($results) && count($results)) {
			foreach($results as $instituteId => $result) {
				$institute = $this->_createInstitute($result);
				$this->_loadChildren($institute,$result);
				$institutes[$instituteId] = $institute;
			}
		}

		return $institutes;
	}
	// create Institute object
	private function _createInstitute($result)
	{
		$institute = new Institute;
		$instituteData = (array) $result['general'] + (array) $result['view_count'];
		$this->fillObjectWithData($institute,$instituteData);
		return $institute;
	}
	// loads all the children of institute
	private function _loadChildren($institute,$result)
	{
		$children = array('locations','header_images','ranking','media','institute_facilities');
		foreach($children as $child) {
			if(is_array($result[$child]) && count($result[$child]) > 0) {
				foreach($result[$child] as $childResult) {
					$this->_loadChild($child,$institute,$childResult);
				}
			}
			else {
				/*
				 * Load empty child
				 */
				$this->_loadChild($child,$institute);
			}
		}
	}
	// load indivisual child of an institute
	private function _loadChild($child,$institute,$childResult = NULL)
	{
		switch($child) {
			case 'locations':
				$this->_loadLocation($institute,$childResult);
				break;
			case 'header_images':
				$this->_loadHeaderImage($institute,$childResult);
				break;
			case 'ranking':
				$this->_loadRanking($institute,$childResult);
				break;
			case 'media':
				$this->_loadMedia($institute,$childResult);
				break;
			case 'institute_facilities':
				$this->_loadFacilities($institute,$childResult);
		}
	}
	private function _loadFacilities($institute,$result = NULL){
		if(!empty($result)){
			$facility = new InstituteFacility;
			$this->fillObjectWithData($facility,$result);
			$institute->addInstituteFacility($facility);
		}
	}

	// creates location object
	private function _loadLocation($institute,$result = NULL)
	{
		$location = $this->_createLocation($result);
		$institute->addLocation($location);
	}
	// populates institute location and it's children
	private function _createLocation($result)
	{
		$result['entities'] = array();

                $region = new Region;
		$this->fillObjectWithData($region,$result);
		$result['entities']['region'] = $region;

                $country = new Country;
		$this->fillObjectWithData($country,$result);
		$result['entities']['country'] = $country;

		$state = new State;
		$this->fillObjectWithData($state,$result);
		$result['entities']['state'] = $state;

		$city = new City;
		$this->fillObjectWithData($city,$result);
		$result['entities']['city'] = $city;

		$zone = new Zone;
		$this->fillObjectWithData($zone,$result);
		$result['entities']['zone'] = $zone;

		$locality = new Locality;
		$this->fillObjectWithData($locality,$result);
		$result['entities']['locality'] = $locality;

		$contact_detail = new ContactDetail();
		$this->fillObjectWithData($contact_detail,$result);
		$result['entities']['contact_detail'] = $contact_detail;

		$location = new InstituteLocation;
		$this->fillObjectWithData($location,$result);

		return $location;
	}
	// populates institute header image object
	private function _loadHeaderImage($institute,$result = NULL)
	{
		$headerImage = new HeaderImage;
		$this->fillObjectWithData($headerImage,$result);
		$institute->addHeaderImage($headerImage);
	}
	// populates institute ranking objecct
	private function _loadRanking($institute,$result)
	{
		$ranking = new Ranking;
		$this->fillObjectWithData($ranking,$result);
		$institute->setRanking($ranking);
	}
	// populates institute media object
	private function _loadMedia($institute,$result = NULL)
	{
		$media = new ListingMedia;
		$this->fillObjectWithData($media,$result);
		$institute->addMedia($media);
	}
	// manages valueobjects population for institute
	private function _populateValueObject($institute_id,$value_object_name,$base_institute_object) {
		// populate required object
		switch($value_object_name) {
			case 'joinreason':
				$result = $this->model->getDataForInstituteJoinReason($institute_id);
				$this->_loadJoinReason($base_institute_object,$result);
				break;
			case 'description':
				$result = $this->model->getDataForInstituteDescriptionAttributes($institute_id);
				$this->_loadDescription($base_institute_object,$result);
				break;
			case 'viewcount':
				$result = $this->model->getInstituteViewCount($institute_id);
				$this->_loadViewCount($base_institute_object,$result[$institute_id]);
				break;
		}
	}
	// populates whyjoinreason object of institute
	private function _loadJoinReason($base_institute_object,$result) {
		// instantiate and populate object
		$joinreason = new InstituteJoinReason();
		$this->fillObjectWithData($joinreason,$result);
		$base_institute_object->setJoinReason($joinreason);
	}
	// populates institute descriptions object
	private function _loadDescription($base_institute_object,$result) {
		// instantiate and populate object
		foreach ($result as $row) {
			$description = new InstituteDescriptionAttribute();
			$this->fillObjectWithData($description, $row);
			$base_institute_object->setDescriptionAttribute($description);
		}
	}
	// populates view count object
	private function _loadViewCount($base_institute_object,$result) {
		// instantiate and populate object
		$viewCount = new ListingViewCount();
		$this->fillObjectWithData($viewCount,$result);
		$base_institute_object->addViewCount($viewCount);
	}
	/**
	 * return institute id
	 *
	 * @param integer $listing_id listing id
	 * @param array $listing_type
	 * @return int
	 */
	public function getRedirectionIdForDeletedInstitute($listing_id,$listing_type) {
		if(!($listing_id > 0 && $listing_type == 'institute')) {
			return 0;
		}
		$redirection_id = $this->model->getRedirectionIdForDeletedInstitute($listing_id,$listing_type);
		return $redirection_id;
	}

        public function getAlumniFeedbackRatingCount($instituteId)
	{
		Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');
		return $this->model->getAlumniFeedbackRatingCount($instituteId);
    }
    
        /**
         * @param string $courseType course pack type (can be 'ALL', 'FREE', 'PAID')
         * @return array of live courses of the institutes
        */
        public function getCoursesOfInstitutes($institutes, $courseType = 'ALL') {
            $instituteCoursesArray = $this->model->getCoursesForInstitutes($institutes, $courseType);
            return $instituteCoursesArray;
        }

       public function getLastYearViewResponseCountForInstitute($institute_id,$courseIdList) {

       	Contract::mustBeNumericValueGreaterThanZero($institute_id,'Institute ID');
       	if($this->caching && $cachedYearlyInfo = $this->cache->getCachedLastYearViewResponseCountForInstitute($institute_id)) {
       		return $cachedYearlyInfo;
       	}
       	
       	$YearlyInfo = $this->model->getLastYearViewResponseCountForInstitute($institute_id,$courseIdList);
       	if(!empty($YearlyInfo))
       	{
       		$this->cache->storeLastYearViewResponseCountForInstitute($YearlyInfo,$institute_id);
       	}
       	
       	return $YearlyInfo;
        	
        }
        
	public function getFlagshipCourseOfInstitute($instituteId) {
		$coursesArray = $this->getCoursesOfInstitutes(array($instituteId));
		$coursesIdsArray = explode(",", $coursesArray[$instituteId]['courseList']);
		return $coursesIdsArray[0];
	}

	
	public function getInstitutesViewCount($instituteIds = -1) {
		return; //LF-4387: This API was used only for sort by view count on category pages is now removed.
        $viewCountForInstitutes = $this->cache->getViewCountOfInstitutes();
		if(empty($viewCountForInstitutes)) {
			$viewCountForInstitutes = $this->model->getInstitutesViewCount();
			if(!empty($viewCountForInstitutes)) {
				$this->cache->storeViewCountOfInstitutes($viewCountForInstitutes);
			}
		} 
		
		if($instituteIds == -1) {
			return $viewCountForInstitutes;
		} else {
			if(is_array($instituteIds)) 
			{
				$data = array();
				foreach($instituteIds as $instituteId) {
					if(array_key_exists($instituteId, $viewCountForInstitutes)) {
						$data[$instituteId] = $viewCountForInstitutes[$instituteId];
					} else {
						$data[$instituteId] = 0;
					}
				}
			} else {
				$data[$instituteIds] = isset($viewCountForInstitutes[$instituteIds]) ? $viewCountForInstitutes[$instituteIds] : 0;
			}	
	    }
	    return $data;
	 }	 
	 
	 private function _initiateErrorReportingLib() {
	 	$this->CI->load->library('listing/ListingErrorReportingLib');
	 	$this->listingErrorReportingLib = new ListingErrorReportingLib();
	 }
}
