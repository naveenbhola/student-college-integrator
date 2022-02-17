<?php

require_once dirname(__FILE__).'/ListingModelAbstract.php';

class UniversityModelV2 extends ListingModelAbstract
{
	protected $defaultFilters = array('general','locations','admission_contact','snapshot_departments','campus_accommodation','campuses','contact_details','media','snapshot_courses','announcement','customAttribute');

	function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * Get consolidated info for a university
	 * Required info can be specified via Filters -
	 * Filters are pipe separated e.g. 'general|locations'
	 * Returned data is indexed by filters
	 * i.e. array('general' => array(),'locations' => array())
	 */
	public function getData($universityId,$filters = '')
	{
		Contract::mustBeNumericValueGreaterThanZero($universityId,'University ID');

		$universityIds = array($universityId);
		$data = $this->_getFilterwiseConsolidatedData($universityIds,$filters);
		return $this->removeExtraDepthForKeys($data,array('general','external_links'));
	}

	/*
	 * Get consolidated info for a set of universities at once
	 * Returned data is indexed by 'university ids -> Filters'
	 */
	public function getDataForMultipleUniversities($universityIds,$filters = '')
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($universityIds,'University IDs');
		
		$data = $this->_getFilterwiseConsolidatedData($universityIds,$filters);	
		return $data;
		//return $this->indexFilteredDataByListingIds($data,'university_id',array('general','view_count'));
	}

	function getUniversityNameById($universityId)
    {
		$sql =  "select name,university_id from university where status='live' and university_id= ?";
		
		$results = $this->db->query($sql,array($universityId))->result_array();
		return $results;
    }

    private function mergeArraysWithSameKey($array1,$array2){
    	
    	foreach ($array1 as $key => $data) {
    		if(array_key_exists($key, $array2)){
    			$array1[$key] = array_merge($data,$array2[$key]);
    		}
    	}

    	return $array1;    
    }

	/*
	 * Run each filter, retrive data for the same and consolidate
	 */
	private function _getFilterwiseConsolidatedData($universityIds,$filters = '')
	{
		$validFilters = $this->validateFilters($filters);

		$data = array();
		
		foreach($validFilters as $filter) {
			switch($filter) {
				case 'general':
					$data = $this->_getGeneralInfo($universityIds);		
					break;
				case 'locations':
					$dataLocation = $this->_getLocations($universityIds);
					$data 		  = $this->mergeArraysWithSameKey($data,$dataLocation);
					foreach ($data as $universityId => $value) {
						if(isset($data[$universityId]['universityLocation'])){
							if(isset($data[$universityId]['latitude']) && !empty($data[$universityId]['latitude'])){
								$data[$universityId]['universityLocation']['geographicalCoordinates']['latitude'] =$data[$universityId]['latitude'];
								$data[$universityId]['universityLocation']['geographicalCoordinates']['latitudeDirection'] =$data[$universityId]['latitudeDir']; 
								unset($data[$universityId]['latitude']);
								unset($data[$universityId]['latitudeDir']);
							}
							if(isset($data[$universityId]['longitude']) && !empty($data[$universityId]['longitude'])){
								$data[$universityId]['universityLocation']['geographicalCoordinates']['longitude'] =$data[$universityId]['longitude'];
								$data[$universityId]['universityLocation']['geographicalCoordinates']['longitudeDirection'] =$data[$universityId]['longitudeDir']; 
								unset($data[$universityId]['longitude']);
								unset($data[$universityId]['longitudeDir']);
							}
						}
					}
					break;
				case 'media':
					$dataMediaArray = $this->_getMedia($universityIds);		
					$dataMedia = $dataMediaArray['mediaData'];
					$ulpDefaultImg = $dataMediaArray['ulpDefaultImg'];
					$data      = $this->mergeArraysWithSameKey($data,$dataMedia);
					$data      = $this->mergeArraysWithSameKey($data,$ulpDefaultImg);
					break;
				// case 'admission_contact':
				// 	$dataAdmissionContact = $this->_getAdmissionContact($universityIds);
				// 	_p($dataAdmissionContact);die;
				// 	$data                 = $this->mergeArraysWithSameKey($data,$dataAdmissionContact);
				// 	break;
				// case 'snapshot_departments':
				// 	$data['snapshot_departments'] = $this->_getSnapshotDepartments($universityIds);
				// 	break;
				case 'campus_accommodation':
					$dataCampusAccommodation = $this->_getCampusAccommodation($universityIds);
					$data      = $this->mergeArraysWithSameKey($data,$dataCampusAccommodation);
					foreach ($data as $universityId => $value) {
						if(!empty($value['livingExpenses'])){						
							unset($data[$universityId]['livingExpenses']['university_id']);
							if(isset($value['livingExpenses']['accomodationDetails'])){
									$data[$universityId]['accomodationDetails'] = $value['livingExpenses']['accomodationDetails'];
									unset($data[$universityId]['livingExpenses']['accomodationDetails']);
							}

							if(isset($value['livingExpenses']['accomodationWebsite'])){
									$data[$universityId]['accomodationWebsite'] = $value['livingExpenses']['accomodationWebsite'];
									unset($data[$universityId]['livingExpenses']['accomodationWebsite']);
							}
						}
					}
					break;
				case 'campuses':
					$dataCampuses = $this->_getCampuses($universityIds);
					$data      = $this->mergeArraysWithSameKey($data,$dataCampuses);
					break;
				// case 'contact_details':
				// 	$data['contact_details'] = $this->_getContactDetails($universityIds);
				// 	break;
				// case 'snapshot_courses':
				// 	//$data['snapshot_courses'] = $this->_getSnapshotCourses($universityIds);
				// 	break;
				case 'announcement':
					$dataAnnouncement = $this->_getAnnouncementDetails($universityIds);
					$data      = $this->mergeArraysWithSameKey($data,$dataAnnouncement);
					break;
                case 'customAttribute':
                    $customAttributeData = $this->_getCustomAttributeData($universityIds);
                    $data = $this->mergeArraysWithSameKey($data,$customAttributeData);
			}
		}
		return $data;
	}

	private function _getCustomAttributeData($universityIds){
	    $this->db->select('custom_label as customLabel, custom_value as customValue, university_id');
	    $this->db->from('sa_university_custom_attributes');
	    $this->db->where_in('university_id',$universityIds);
	    $this->db->where('status','live');
	    $result = $this->db->get()->result_array();
	    $customAttributeData = array();
	    foreach ($result as $key=>$value){
	        $universityId = $value['university_id'];
	        unset($value['university_id']);
	        $customAttributeData[$universityId]['customAttribute'][] = $value;
        }
	    return $customAttributeData;
    }

	/*
	 * General info includes all the data from `university` table
	 * Alongwith data from `listings_main`
	 */
	
	private function _getGeneralInfo($universityIds)
	{
		$sql =  "SELECT ".
				"u.university_id as id, ".
				"u.name, ".
				"u.acronym, ".
				"u.establish_year as establishmentYear , ".
				"u.logo_link as logoURL , ".
				"u.type_of_institute as fundingType , ".
				"u.type_of_institute2 as type , ".
				"u.affiliation, ".
				"u.accreditation, ".
				"u.brochure_link as brochureURL, ".
				"u.conditionalOffer, ".
				"u.conditionalOfferDescription, ".
				"u.conditionalOfferLink, ".
				"u.expert_id as expertId, ".
				//"u.percentage_profile_completion, ".
				"lm.listing_seo_url as seoURL, ".
				"lm.listing_seo_title, ".
				"lm.listing_seo_description, ".
				"lm.listing_seo_keywords, ".
				"lm.viewCount as cumulativeViewCount, ".
				"lm.pack_type as packType, ".
				"lm.last_modify_date as lastModifyDate ".				
                "FROM university u ".
                "LEFT JOIN listings_main lm ON (lm.listing_type_id = u.university_id AND lm.listing_type = 'university' AND lm.status = 'live') ".
                "WHERE u.status = 'live' ".
		"AND u.university_id IN (?)";
		
		$results = $this->db->query($sql,array($universityIds))->result_array();
		
		/**
		 * External links
		 */ 
		$sql = "SELECT listing_type_id as university_id, link_type, link ".
                "FROM listing_external_links ".
                "WHERE status = 'live' ".
		"AND listing_type = 'university' AND listing_type_id IN (?)";

		$externalLinkResults = $this->db->query($sql,array($universityIds))->result_array();
		
		$externalLinks = array();
		foreach($externalLinkResults as $result) {
			$externalLinks[$result['university_id']][$result['link_type']] = $result['link'];
		}
		
		/**
		 * Attributes
		 */
		$sql = "SELECT listing_type_id as university_id, caption, attributeValue ".
                "FROM listing_attributes_table ".
                "WHERE status = 'live' ".
		"AND listing_type = 'university' AND listing_type_id IN (?)";

		$attributeResults = $this->db->query($sql,array($universityIds))->result_array();
		$attributes = array();
		foreach($attributeResults as $result) {
			$attributes[$result['university_id']][$result['caption']] = $result['attributeValue'];
		}

		/**
		* Contact Details
		*/
		$sql = "SELECT listing_type_id as university_id, contact_person as contactPersonName, contact_email as contactEmail , contact_main_phone as contactNumber, website as contactWebsite ".
                "FROM listing_contact_details ".
                "WHERE status = 'live' ".
				"AND listing_type = 'university' AND listing_type_id IN (?)";
		
		$contactDetailResults = $this->db->query($sql,array($universityIds))->result_array();
		$contactDetails = array();
		foreach($contactDetailResults as $result) {
			$contactDetails[$result['university_id']] = $result;
		}

	  /**
		* AdmissionContactDetails
		*/
		$sql = "SELECT acd.listing_type_id as university_id,acd.admission_contact_person as admissionContactPerson, ".
                "acd.city_id as admissionCityId ".
                "FROM listing_admission_contact_details acd ".                
                "WHERE acd.status = 'live' ".
				"AND acd.listing_type_id IN (?)";

		$admissionContactResult = $this->db->query($sql,array($universityIds))->result_array();

		$admissionContactDetails = array();
		foreach($admissionContactResult as $result) {
			$admissionContactDetails[$result['university_id']] = $result;
		}


		/**
		 * Add external links and attributes to original result set for general info
		 */		
		for($i=0;$i<count($results);$i++) {
			$result = $results[$i];
			$universityId = $result['id'];
			
			if(empty($result['listing_seo_description'])){
				$result['seoDetails']['description'] = 'Check {course_count} courses of '.$result['name'].' along with Rankings, fees, entry criteria, admission process, scholarships and more details on '.$result['name'].' @ studyabroad.shiksha.com.';
			}else{
				$result['seoDetails']['description'] = $result['listing_seo_description'];
			}

			$result['seoDetails']['keyword'] = $result['listing_seo_keywords'];
			if(empty($result['listing_seo_title'])){
				$result['seoDetails']['title'] = $result['name'].' - Ranking, Courses, Fees, Entry criteria, Admissions, & Scholarships | Shiksha';
			}else{
				$result['seoDetails']['title'] = $result['listing_seo_title'];
			}

			foreach($externalLinks[$universityId] as $linkType => $link) {
				if($linkType == 'international_students_page_link'){
					$result['intlStudentsURL'] = $link;
				}else if($linkType == 'website_link'){
					$result['website'] = $link;
				}else if($linkType == 'india_consultants_page_link'){
					$result['indianConsultantsURL'] = $link;
				}else if($linkType == 'asian_students_page_link'){
					$result['indianStudentsURL'] = $link;
				}else if($linkType == 'facebook_page'){
					$result['facebookURL'] = $link;
				}else{
					$result[$linkType] = $link;
				}
				
			}
			foreach($attributes[$universityId] as $caption => $attributeValue) {
				if($caption == 'why_join'){
					$result['highlights'] = $attributeValue;
				}else if($caption == 'univ_wiki'){
					$result['wiki'] = $attributeValue;
				}else if($caption == 'endowmentsCurr'){
					$result['universityEndowments']['endowmentsCurrency'] = $attributeValue;
				}else if($caption == 'endowmentsVal'){
					$result['universityEndowments']['endowmentsValue'] = $attributeValue;
				}else if($caption == 'percentIntlStud'){
					$result['percentIntlStudents'] = $attributeValue;
				}else if($caption == 'totalIntlStud'){
					$result['totalIntlStudents'] = $attributeValue;
				}else if($caption == 'genderRatio'){
					$result['maleFemaleRatio'] = $attributeValue;
				}else if($caption == 'studentFacultyRatio'){
					$result['facultyStudentRatio'] = $attributeValue;
				}else{
					$result[$caption] = $attributeValue;
				}				
			}

			foreach($contactDetails[$universityId] as $contactKey => $contactVal) {
				if($contactKey != 'university_id'){				
					$result[$contactKey] =$contactVal;
				}
			}

			foreach($admissionContactDetails[$universityId] as $admissionKey => $admissionVal) {
				if($admissionKey != 'university_id'){				
					$result[$admissionKey] =$admissionVal;
				}
			}
			$results[$i] = $result;
			
		}		

		$finalData = array();
		foreach ($results as $key => $val) {
			$finalData[$val['id']] = $val;
		}

		return $finalData;
	}
	
	/*
	 * Location info includes all the data from `university_location_table` table
	 * Alongwith data from `listing_contact_details`,countryCityTable,stateTable,localityCityMapping,
	 * tZoneMapping and virtualCityMapping
	 */
	private function _getLocations($universityIds)
	{
		$sql = "SELECT loc.university_location_id,loc.university_id,loc.address, ".
                "city.city_name,city.enabled,city.tier,city.city_id as cityId, ".
                "country.countryId,country.name as countryName,country.enabled,country.urlName,".
                "state.state_id as stateId,state.state_name ".
                "FROM university_location_table loc ".
                "LEFT JOIN countryCityTable city ON city.city_id = loc.city_id ".
                "LEFT JOIN stateTable state ON state.state_id = city.state_id ".
                "LEFT JOIN countryTable country ON country.countryId = loc.country_id ".
                "LEFT JOIN tregionmapping trm on (loc.country_id = trm.id AND trm.regionmapping = 'country') ".
                "LEFT JOIN tregion on tregion.regionid = trm.regionid ".
                "WHERE loc.status = 'live' ".
				"AND loc.university_id IN (?)";
			
		$result = $this->db->query($sql,array($universityIds))->result_array();		
		$finalData = array();
		foreach ($result as $key => $val) {
			$finalData[$val['university_id']]['universityLocation']['cityId'] = $val['cityId'];
			$finalData[$val['university_id']]['universityLocation']['stateId'] = $val['stateId'];
			$finalData[$val['university_id']]['universityLocation']['countryId'] = $val['countryId'];
			$finalData[$val['university_id']]['universityLocation']['address'] = $val['address'];
		}
		return $finalData;			
	}
	
	private function _getAdmissionContact($universityIds)
	{
		$sql = "SELECT acd.listing_type_id as university_id,acd.admission_contact_person as admissionContactPerson, ".
                "acd.city_id ".
                "FROM listing_admission_contact_details acd ".                
                "WHERE acd.status = 'live' ".
				"AND acd.listing_type_id IN (?)";

		$result = $this->db->query($sql,array($universityIds))->result_array();
		$universityWiseAdmission = array();
		foreach ($result as $key => $value) {
			$universityWiseAdmission[$value['university_id']]['admissionContact']=$value;
		}
		return $universityWiseAdmission;			
	}
	
	private function _getSnapshotDepartments($universityIds)
	{
		$sql = "SELECT university_id,department_name,department_website_url ".
                "FROM university_departments ".
                "WHERE status = 'live' ".
		"AND university_id IN (?)";
		$result = $this->db->query($sql,array($universityIds))->result_array();
		return $result;			
	}
	
	private function _getSnapshotCourses($universityIds)
	{
		$sql = "SELECT university_id,course_id,course_name,course_type,website_link,category_id,created, last_modified, listing_seo_url ".
                "FROM snapshot_courses ".
                "WHERE status = 'live' ".
		"AND university_id IN (".mysql_escape_string(implode(',',$universityIds)).")";
		$result = $this->db->query($sql)->result_array();
		return $result;			
	}

	private function _getCampusAccommodation($universityIds)
	{
		$sql = "SELECT university_id, accommodation_details as accomodationDetails, accommodation_website_url as accomodationWebsite, living_expenses as livingExpensesValue, currency as livingExpensesCurrency
		, living_expense_details as livingExpensesDesc, living_expense_website_url as livingExpensesURL ".
                "FROM university_campus_accommodation LEFT JOIN currency on university_campus_accommodation.currency = currency.id ".
                "WHERE status = 'live' ".
				"AND university_id IN (?)";

		$result = $this->db->query($sql,array($universityIds))->result_array();
		$universityCampusAccommodation = array();

		foreach ($result as $key => $value) {
			$universityCampusAccommodation[$value['university_id']]['livingExpenses']=$value;
		}		

		return $universityCampusAccommodation;			
	}
	
	private function _getCampuses($universityIds)
	{
		$sql = "SELECT university_id, campus_name,campus_website_url as campus_link,campus_address ".
                "FROM university_campuses ".
                "WHERE status = 'live' ".
				"AND university_id IN (?)";

		$result = $this->db->query($sql,array($universityIds))->result_array();
		$universityCampus = array();

		foreach ($result as $key => $value) {
			$universityId                                  = $value['university_id'];
			unset($value['university_id']);
			$universityCampus[$universityId]['campuses'][] = $value;
		}
		return $universityCampus;			
	}
	
	private function _getContactDetails($universityIds)
	{
		$sql = "SELECT listing_type_id as university_id, contact_details_id, contact_person, contact_email, contact_main_phone, website ".
                "FROM listing_contact_details ".
                "WHERE status = 'live' ".
				"AND listing_type = 'university' AND listing_type_id IN (?)";
		
		$result = $this->db->query($sql,array($universityIds))->result_array();
		return $result;			
	}
	
	private function _getMedia($universityIds)
	{
		$sql =  "SELECT lm.type_id as university_id,lm.media_type as mediaType,".
				"im.name,im.url,im.thumburl as thumbUrl ".		        
				"FROM listing_media_table lm ".
				"INNER JOIN institute_uploaded_media im ON (im.listing_type_id = lm.type_id AND im.media_id = lm.media_id AND im.listing_type = 'university') ".
				"WHERE lm.type = 'university' ".
				"AND lm.type_id IN (?) ".
				"AND lm.status = 'live' ".
				"AND im.status = 'live'";

		$mediaData = $this->db->query($sql,array($universityIds))->result_array();
		$finalMediaData = array();
		$universityWiseMedia = array();
		$ulpDefaultImg = array();
		foreach ($mediaData as $key => &$value) {
			$universityId  = $value['university_id'];
			unset($value['university_id']);
			if(empty($ulpDefaultImg[$universityId]) && $value['mediaType'] == 'photo'){
				$ulpDefaultImg[$universityId]['universityDefaultImgUrl'] = $value['url'];
			}
			if(strpos($value['name'],'picture')=== false && strpos($value['name'],'video')=== false)
			{
				$value['caption'] = $value['name'];
			}else{
				$value['caption'] = null;
			}
			$universityWiseMedia[$universityId]['media'][]=$value;
		}		
		return array('mediaData' => $universityWiseMedia,'ulpDefaultImg'=>$ulpDefaultImg);
	}
	
	// Get all live abroad universities ids
	public function getLiveAbroadUniversities()
	{
		$sql =  "SELECT DISTINCT university_id ".
				"FROM abroadCategoryPageData ".
				"WHERE status = 'live'";
		
		return $this->getColumnArray($this->db->query($sql)->result_array(),'university_id');
	}
	
	public function getCoursesForUniversities($universityids = array(), $courseType = 'ALL')
	{
		if(count($universityids) == 0) {
			return array();
		}
		// get data from table
		$result = $this->_getCoursesForUniversities($universityids, $courseType);
		$result_array = array();
		if(count($result)>0) {
			foreach ($result as $row) {
				$result_array[$row['university_id']][] = $row['courseid'];
				$course_id_name_array[$row['university_id']][$row['courseid']] = $row['courseTitle']; 
			}
		}
		else {
			return array();
		}
	
		$ans =array();
		foreach($result_array as $key => $value){
			$temp = implode(',',$value);
			$ans[$key] =array('university_id' => $key, 'courseList'=>$temp,'course_title_list'=>$course_id_name_array[$key]); 
		}
		return $ans;
	}
	
	private function _getCoursesForUniversities($universityids, $courseType = 'ALL')
	{
		switch($courseType) {
			case 'PAID':
				$courseTypeClause = ' AND lm.pack_type IN ('.GOLD_SL_LISTINGS_BASE_PRODUCT_ID.', '.GOLD_ML_LISTINGS_BASE_PRODUCT_ID.', '.SILVER_LISTINGS_BASE_PRODUCT_ID.') ';
				break;
			case 'FREE':
				$courseTypeClause = ' AND lm.pack_type NOT IN ('.GOLD_SL_LISTINGS_BASE_PRODUCT_ID.', '.GOLD_ML_LISTINGS_BASE_PRODUCT_ID.', '.SILVER_LISTINGS_BASE_PRODUCT_ID.') ';
				break;
			default :
				$courseTypeClause = '';
				break;
		}
		
		$sql =  "SELECT cd.course_id as courseid,um.university_id,cd.courseTitle ".
				"FROM course_details cd, institute_university_mapping um,listings_main lm ".
				"WHERE um.institute_id = cd.institute_id ".
				"AND lm.listing_type_id = cd.course_id ".
				"AND lm.listing_type = 'course' ".
				"AND lm.status = 'live' ".
				"AND um.status = 'live' ".
				"AND cd.status = 'live' ".
				"AND um.university_id IN (?) ".$courseTypeClause/*." ORDER BY cd.course_order"*/;
		return $this->db->query($sql,array($universityids))->result_array();
	}
	
	public function getViewCountOfUniversities($universityIds){
        if(count($universityIds)==0){
            return array();
        }
		$sql = "select viewCount, listing_type_id from listings_main where listing_type = 'university' and
			listing_type_id in (?) and status = 'live'";
		return $this->db->query($sql,array($universityIds))->result_array();
	}
	
	public function getDepartmentsOfUniversities($universityIds){
        if(count($universityIds)==0){
            return array();
        }
		$sql = "select ium.institute_id, ium.university_id from institute_university_mapping ium
			where ium.status = 'live' and ium.university_id in (?)";
		return $this->db->query($sql,array($universityIds))->result_array();
	}
	
	private function _getAnnouncementDetails($universityIds)
	{
		$sql = 	"SELECT announcementId, universityId as university_id, announcementText, callToActionText as announcementActionText, startDate as announcementStartDate, endDate as announcementEndDate ".
                "FROM university_announcements ".
                "WHERE status = 'live' AND universityId IN (?)";
		
		$result = $this->db->query($sql,array($universityIds))->result_array();
		$universityAnnouncement = array();
		foreach ($result as $key => $value) {
			$universityId = $value['university_id'];
			unset($value['university_id']);
			$universityAnnouncement[$universityId]['announcement']=$value;
		}
		return $universityAnnouncement;	
	}

}