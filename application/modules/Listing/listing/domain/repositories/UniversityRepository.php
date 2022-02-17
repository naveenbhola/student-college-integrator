<?php

class UniversityRepository extends EntityRepository
{
	function __construct($dao,$cache)
	{
		parent::__construct($dao,$cache,$model);
		
		//$this->caching = true;
		/*
		 * Load entities required
		 */
		$this->CI->load->entities(array('University','UniversityLocation','AdmissionContact','SnapshotDepartment','CampusAccommodation','Campus','ContactDetail','ListingMedia','SnapshotCourse', 'Currency', 'UniversityAnnouncement'),'listing');
		$this->CI->load->entities(array('Locality','Zone','City','State','Country', 'Region'),'location');
	}
		
	/*
	 * Find a university using university id
	 */
	public function find($universityId)
	{
		Contract::mustBeNumericValueGreaterThanZero($universityId,'University ID');
		if($this->caching && $cachedUniversity = $this->cache->getUniversity($universityId)) {
			return $cachedUniversity;
		}
		$universityDataResults = $this->dao->getData($universityId);
		$university = $this->_loadOne($universityDataResults);
		$this->cache->storeUniversity($university);
		return $university;
	}

	/*
	 * Find multiple universities using university ids
	 */
	public function findMultiple($universityIds,$categoryPageFlag = false)
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($universityIds,'University IDs');
		$orderOfUniversityIds = $universityIds;
		$universitiesFromCache = array();

		if($this->caching) {
			$universitiesFromCache = $this->cache->getMultipleUniversities($universityIds,$categoryPageFlag);
			$foundInCache = array_keys($universitiesFromCache);
			$universityIds = array_diff($universityIds,$foundInCache);
		}

		if(count($universityIds) > 0) {
			$universityDataResults = $this->dao->getDataForMultipleUniversities($universityIds);
			$universitiesFromDB = $this->_load($universityDataResults);
			foreach($universitiesFromDB as $university) {
				$this->cache->storeUniversity($university);
			}
		}

		$universities = array();
		foreach($orderOfUniversityIds as $universityId) {
			if(isset($universitiesFromCache[$universityId])) {
				$universities[$universityId] = $universitiesFromCache[$universityId];
			}
			else if(isset($universitiesFromDB[$universityId])) {
				$universities[$universityId] = $universitiesFromDB[$universityId];
			}
		}

		return $universities;
	}

	/**
	 * @param string $courseType course pack type (can be 'ALL', 'FREE', 'PAID')
	 * @return array of live courses of the universities
     */
	public function getCoursesOfUniversities($universities, $courseType = 'ALL')
	{
		$universityCoursesArray = $this->dao->getCoursesForUniversities($universities, $courseType);
		return $universityCoursesArray;
    }
	
	public function getMainCategoryIdsOfListing($listing_id,$listing_type)
	{
		$category_list = $this->model->getMainCategoryIdsOfListing($listing_id,$listing_type);
		return $category_list;
	}
	
	/*
	 * ORM Functions
	 * Create domain objects from Db resultsets
	 */
	private function _loadOne($results)
	{
		$universities = $this->_load(array($results));
		return current($universities);
	}
	
	// handles multiple institute objects
	private function _load($results)
	{
		$universities = array();
		if(is_array($results) && count($results)) {
			foreach($results as $universityId => $result) {
				$university = $this->_createUniversity($result);
				$this->_loadChildren($university,$result);
				$universities[$universityId] = $university;
			}
		}

		return $universities;
	}
	
	// create Institute object
	private function _createUniversity($result)
	{
		$university = new UniversityOld;
		$universityData = (array) $result['general'] + (array) $result['view_count'];
		$this->fillObjectWithData($university,$universityData);
		return $university;
	}
	
	// loads all the children of institute
	private function _loadChildren($university,$result)
	{
		$children = array(
							'locations',
							'admission_contact',
							'snapshot_departments',
							'campus_accommodation',
							'campuses',
							'contact_details',
							'media',
							'snapshot_courses',
							'announcement'
						);
		
		foreach($children as $child) {
			if(is_array($result[$child]) && count($result[$child]) > 0) {
				foreach($result[$child] as $childResult) {
					$this->_loadChild($child,$university,$childResult);
				}
			}
			else {
				/*
				 * Load empty child
				 */
				$this->_loadChild($child,$university);
			}
		}
	}
	
	// load indivisual child of a university
	private function _loadChild($child,$university,$childResult = NULL)
	{
		switch($child) {
			case 'locations':
				$this->_loadLocation($university,$childResult);
				break;
			case 'media':
				$this->_loadMedia($university,$childResult);
				break;
			case 'admission_contact':
				$this->_loadAdmissionContact($university,$childResult);
				break;
			case 'snapshot_departments':
				$this->_loadSnapshotDepartment($university,$childResult);
				break;
			case 'campus_accommodation':
				$this->_loadCampusAccommodation($university,$childResult);
				break;
			case 'campuses':
				$this->_loadCampus($university,$childResult);
				break;
			case 'contact_details':
				$this->_loadContactDetails($university,$childResult);
				break;
			case 'snapshot_courses':
				$this->_loadSnapshotCourses($university,$childResult);
				break;
			case 'announcement':
				$this->_loadAnnouncement($university,$childResult);
				break;
		}
	}
	
	// creates location object
	private function _loadLocation($university,$result = NULL)
	{
		$location = $this->_createLocation($result);
		$university->addLocation($location);
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

		$location = new UniversityLocation;
		$this->fillObjectWithData($location,$result);

		return $location;
	}
	
	// populates institute media object
	private function _loadMedia($institute,$result = NULL)
	{
		$media = new ListingMedia;
		$this->fillObjectWithData($media,$result);
		$institute->addMedia($media);
	}
	
	// populates university admission contact objecct
	private function _loadAdmissionContact($university,$result)
	{
		$result['entities'] = array();

        $city = new City;
		$this->fillObjectWithData($city,$result);
		$result['entities']['city'] = $city;
		
		$admissionContact = new AdmissionContact;
		$this->fillObjectWithData($admissionContact,$result);
		$university->setAdmissionContact($admissionContact);
	}
	
	// populates university snapshot departments
	private function _loadSnapshotDepartment($university,$result)
	{
		$snapshotDepartment = new SnapshotDepartment;
		$this->fillObjectWithData($snapshotDepartment,$result);
		$university->addSnapshotDepartment($snapshotDepartment);
	}
	
	// populates university campus accommodation
	private function _loadCampusAccommodation($university,$result)
	{
		$campusAccommodation = new CampusAccommodation;
		$this->fillObjectWithData($campusAccommodation,$result);
		$currency = new Currency;
		$this->fillObjectWithData($currency, $result);
		$campusAccommodation->setCurrencyEntity($currency);
		$university->setCampusAccommodation($campusAccommodation);
	}
	
	// populates university campuses
	private function _loadCampus($university,$result)
	{
		$campus = new Campus;
		$this->fillObjectWithData($campus,$result);
		$university->addCampus($campus);
	}
	
	// populates university contact details
	private function _loadContactDetails($university,$result)
	{
		$contactDetails = new ContactDetail;
		$this->fillObjectWithData($contactDetails,$result);
		$university->setContactDetails($contactDetails);
	}
	
	// populates snapshot courses
	private function _loadSnapshotCourses($university,$result)
	{
		//$snapshotCourse = new SnapshotCourse;
		//$this->fillObjectWithData($snapshotCourse,$result);
		//$university->addSnapshotCourse($snapshotCourse);
		$university->addSnapshotId($result['course_id']);
	}
	
	// get all abroad universities ids
	public function getLiveAbroadUniversities()
	{
		return $this->dao->getLiveAbroadUniversities();
	}
	
	// populates university announcement
	private function _loadAnnouncement($university,$result)
	{
		$announcementDetails = new UniversityAnnouncement;
		$this->fillObjectWithData($announcementDetails,$result);
		$university->setAnnouncement($announcementDetails);
	}
	
	// disable cache for this object
	public function disableCaching() {
		$this->caching = FALSE;
	}
}
