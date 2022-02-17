 <?php

require_once dirname(__FILE__).'/NationalListingModelAbstract.php';

class InstituteModel extends NationalListingModelAbstract
{
	protected $defaultFilters;

	function __construct()
	{
		parent::__construct();
		$this->load->config('nationalInstitute/instituteSectionConfig');
		global $instituteSections;
		$this->defaultFilters = $instituteSections;
	}
	
	public function getData($instituteId,$filters = '')
	{
//		_p("FROM DB : instituteId: ".$instituteId.", filters: ".$filters);
		Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');

		$instituteIds = array($instituteId);
		$data = $this->_getFilterwiseConsolidatedData($instituteIds,$filters);
		return array($instituteId => $data);
	}

	public function getDataForMultipleInstitutes($instituteIds,$filters = '')
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds,'Institute IDs');

		$data = $this->_getFilterwiseConsolidatedData($instituteIds,$filters);
		$data = $this->indexFilteredDataByListingIds($data,'listing_id');
		return $data;
	}

	/*
	 * Run each filter, retrive data for the same and consolidate
	 */
	private function _getFilterwiseConsolidatedData($instituteIds,$filters = '')
	{
		$validFilters = $this->validateFilters($filters);
		$data = array();
        global $saveDbQueries;
		if(isset($saveDbQueries) && !$saveDbQueries){
            $this->db->save_queries = false;
        }
		foreach($validFilters as $filter){

			switch($filter) {
				case 'basic':
					$data['basic'] = $this->_getBasicInfo($instituteIds);
					break;
				case 'location' : 
					$data['location'] = $this->_getLocationInfo($instituteIds);

					// set main location data in basic section
					foreach ($data['location'] as $value) {
						if($value['is_main'] == 1){
							$data['basic'][$value['listing_id']]['main_location'] = $value;
						}
					}
					break;
				case 'academic' : 
					$data['academic'] = $this->_getAcademicInfo($instituteIds);
				case 'research_project':
				case 'usp':
					if(!isset($additionalData)){
						$additionalData = $this->_getAdditionalInfo($instituteIds);
						foreach ($additionalData as $additionalDataRow) {
							$data[$additionalDataRow['description_type']][] = $additionalDataRow;
						}
					}
					break;
				case 'facility' : 
					$data['facility'] = $this->_getFacilityInfo($instituteIds);
					break;
				case 'event' :
					$data['event'] = $this->_getEventInfo($instituteIds);
					break;
				case 'media' :
					$data['media'] = $this->_getMediaInfo($instituteIds);
					break;
				case 'scholarship' : 
					$data['scholarship'] = $this->_getScholarshipInfo($instituteIds);
					break;
				case 'company' : 
					$data['company'] = $this->_getCompaniesInfo($instituteIds);
					break;
				case 'childPageExists':
					$childPageInfo = $this->_getChildPageInfo($instituteIds);
					//to convert string to boolean
					$childPageExists = array();
					foreach ($childPageInfo as $key => $childPageData) {
						$childPageData['placementPageExists'] = boolval($childPageData['placementPageExists']);							
						$childPageData['flagshipCoursePlacementDataExists'] = boolval($childPageData['flagshipCoursePlacementDataExists']);
						$childPageData['naukriPlacementDataExists'] = boolval($childPageData['naukriPlacementDataExists']);
						$childPageData['cutoffPageExists'] = boolval($childPageData['cutoffPageExists']);
						$childPageData['reviewPageExists'] = boolval($childPageData['reviewPageExists']);
						$childPageData['admissionPageExists'] = boolval($childPageData['admissionPageExists']);
						$childPageData['allCoursePageExists'] = boolval($childPageData['allCoursePageExists']);
						$childPageExists[] = $childPageData;
						
					}
					$data['childPageExists'] = $childPageExists;
					break;
				case 'brochureSectionData':
					$data['brochureSectionData'] = $this->_getBrochureInfo($instituteIds);
					break;
				case 'wikiSection':
					$data['wikiSection'] = $this->_getWikiSectionInfo($instituteIds);
					break;
				case 'seoData':
					$data['seoData'] = $this->_getSeoDataInfo($instituteIds);
					break;
			}
		}
		return $data;
	}

	private function _getSeoDataInfo($instituteIds){
		$sql = "SELECT listing_id, description_type, page_h1, page_title, page_description FROM shiksha_institutes_additional_attributes WHERE status = 'live' AND listing_id IN (?) AND description_type NOT IN ('faculty_highlights','research_project','usp') AND (page_h1 IS NOT NULL OR page_title IS NOT NULL OR page_description IS NOT NULL)";
		$data = $this->db->query($sql,array($instituteIds))->result_array();
		return $data;
	}

	private function _getBrochureInfo($instituteIds) {
		$sql = "SELECT i.listing_id, i.listing_type, b.cta, b.brochure_url, b.brochure_size, b.brochure_year ".
				"FROM shiksha_institutes i ".
				"INNER JOIN shiksha_listings_brochures b ON (i.listing_id = b.listing_id AND b.status='live' AND i.listing_type = b.listing_type ) ".
				"WHERE i.status='live' AND i.listing_type IN ('institute','university') AND i.listing_id IN (?)";

		$data = $this->db->query($sql,array($instituteIds))->result_array();

		foreach ($data as $key => $value) {
			switch ($value['cta']) {
				case 'brochure':
					$info[$value['listing_id']]['listing_id'] = $value['listing_id'];
					$info[$value['listing_id']]['brochure_url'] = $value['brochure_url'];
					$info[$value['listing_id']]['brochure_size'] = $value['brochure_size'];
					break;

				default:
					$info[$value['listing_id']]['listing_id'] = $value['listing_id'];
					$info[$value['listing_id']][$value['cta'].'_cta_pdf_url'] = $value['brochure_url'];
			}
		}

		return $info;
	}

	private function _getBasicInfo($instituteIds){

		$sql = "SELECT i.listing_id,
					   i.name,
					   i.short_name,
					   i.listing_type,
					   i.institute_specification_type,
					   i.university_specification_type,
					   i.parent_listing_id,
					   i.parent_listing_type,
					   i.primary_listing_id,
					   i.primary_listing_type,
					   i.synonym,
					   i.abbreviation,
					   i.establish_year,
					   i.is_satellite,
					   i.is_autonomous,
					   i.is_dummy,
					   i.is_national_importance,
					   i.is_aiu_membership,
					   i.is_open_university,
					   i.is_ugc_approved,
					   i.student_type,
					   i.logo_url,
					   i.disabled_url,
					   i.ownership,
					   i.accreditation,
					   i.secondary_name,
					   b.brochure_url,
					   b.brochure_size,
					   b.brochure_year
					    FROM shiksha_institutes i LEFT JOIN shiksha_listings_brochures b ON (i.listing_id = b.listing_id AND b.status='live' AND b.cta = 'brochure' AND i.listing_type = b.listing_type )
				WHERE i.status='live' AND i.listing_type IN ('institute','university') AND i.listing_id IN (?)";

		$basicData = $this->db->query($sql,array($instituteIds))->result_array();
		$basicInfo = array();
		foreach ($basicData as $key => $value) {
			$basicInfo[$value['listing_id']] = $value;
		}

		$listingMainStatus = $this->config->item("listingMainStatus");
		$listingsMainLiveStatus = $listingMainStatus['live'];

		$sql = "SELECT lm.listing_type_id,
					   lm.pack_type,
					   lm.listing_seo_url, 
					   lm.listing_seo_title,
					   lm.listing_seo_description
					   FROM listings_main lm
				WHERE lm.status=? AND lm.listing_type IN ('institute', 'university_national') AND lm.listing_type_id IN (?)";

		$listing_main_info = $this->db->query($sql, array($listingsMainLiveStatus,$instituteIds))->result_array();

		foreach($listing_main_info as $val){
				$basicInfo[$val['listing_type_id']]['pack_type'] = $val['pack_type'];
				$basicInfo[$val['listing_type_id']]['seo_url'] = $val['listing_seo_url'];
				$basicInfo[$val['listing_type_id']]['seo_title'] = $val['listing_seo_title'];
				$basicInfo[$val['listing_type_id']]['seo_description'] = $val['listing_seo_description'];

		}

		$sql = "SELECT listing_id,description, posted_on FROM shiksha_institutes_additional_attributes WHERE status = 'live' AND description_type = 'admission_info' AND listing_id IN (?)";
		$admissionInfo = $this->db->query($sql,array($instituteIds))->result_array();
		
		foreach ($admissionInfo as $admissionKey => $admissionValue) {
			$basicInfo[$admissionValue['listing_id']]['admissionDetails'] = $admissionValue['description'];
			$basicInfo[$admissionValue['listing_id']]['admissionPostedDate'] = $admissionValue['posted_on'];
		}
		return $basicInfo;
	}

	private function _getLocationInfo($instituteIds){

		$sql = "SELECT loc.listing_location_id,
					   loc.listing_id,
					   loc.state_id,
					   loc.city_id,
					   loc.locality_id,
					   loc.is_main
					    FROM shiksha_institutes_locations loc 
				WHERE loc.status='live' AND loc.listing_type IN ('institute', 'university') AND loc.listing_id IN (?)";

		$locationsResult = $this->db->query($sql,array($instituteIds))->result_array();
		$locations   = array();
		$stateIds    = array();
		$cityIds     = array();
		$localityIds = array();
		// _p($locationsResult);die;
		foreach ($locationsResult as $key => $value) {
			$locations[$value['listing_location_id']] = $value;
			$stateIds[]                               = $value['state_id'];
			$cityIds[]                                = $value['city_id'];
			$localityIds[]                            = $value['locality_id'];
		}
		// _p($locations);die;

		$stateIds    = array_unique(array_filter($stateIds));
		$cityIds     = array_unique(array_filter($cityIds));
		$localityIds = array_unique(array_filter($localityIds));

		$statesData     = array();
		$citiesData     = array();
		$localitiesData = array();

		if($stateIds){
			$sql = "SELECT state_id,state_name FROM stateTable WHERE state_id IN (?)";
			$data = $this->db->query($sql,array($stateIds))->result_array();			

			foreach ($data as $value) {
				$statesData[$value['state_id']] = $value['state_name'];
			}
		}

		if($cityIds){
			$sql = "SELECT city_id,city_name FROM countryCityTable WHERE city_id IN (?)";
			$data = $this->db->query($sql,array($cityIds))->result_array();			

			foreach ($data as $value) {
				$citiesData[$value['city_id']] = $value['city_name'];
			}
		}

		if($localityIds){
			$sql = "SELECT localityId,localityName FROM localityCityMapping WHERE localityId IN (?)";
			$data = $this->db->query($sql,array($localityIds))->result_array();			

			foreach ($data as $value) {
				$localitiesData[$value['localityId']] = $value['localityName'];
			}
		}

		foreach ($locations as &$value) {
			if($value['state_id'])
				$value['state_name'] = $statesData[$value['state_id']];

			if($value['city_id'])
				$value['city_name'] = $citiesData[$value['city_id']];

			if($value['locality_id'])
				$value['locality_name'] = $localitiesData[$value['locality_id']];
		}

// _p($locations);die;		

		$sql = "SELECT con.listing_location_id,
					   con.website_url,
					   con.address,
					   con.latitude,
					   con.longitude,
					   con.admission_contact_number,
					   con.admission_email,
					   con.generic_contact_number,
					   con.generic_email,
					   con.google_url
					    FROM 
					    shiksha_listings_contacts con
				WHERE con.status='live' AND con.listing_type IN ('institute', 'university') AND con.listing_id IN (?)";

		$contactsResult = $this->db->query($sql,array($instituteIds))->result_array();
		$contactDetails = array();
		foreach ($contactsResult as $contactRow) {
			$contactDetails[$contactRow['listing_location_id']] = $contactRow;
			
		}
		foreach ($locations as $key=>&$locationRow) {
			$locationRow['contact_details'] = $contactDetails[$key];
		}

		return $locations;
	}

	private function _getAcademicInfo($instituteIds){

		$sql = "SELECT acad.listing_id,
					   acad.name,
					   acad.type_id,
					   acad.current_designation,
					   acad.education_background,
					   acad.professional_highlights,
					   acad.display_order
					    FROM shiksha_institutes_academic_staffs acad 
				WHERE acad.status='live' AND acad.listing_type IN ('institute', 'university') AND acad.listing_id IN (?)";

		return $this->db->query($sql,array($instituteIds))->result_array();
	}

	private function _getFacilityInfo($instituteIds){

        $sql = "SELECT fac.listing_id,
                       fac.facility_id,
                       fac.description,
                       fac.additional_info,
                       fac.has_facility,
                       f.name as facility_name,
                       f.order,
                       f.parent_id,
                       f.ask_details
                    FROM  shiksha_institutes_facilities fac JOIN shiksha_institutes_facilities_master f ON (fac.facility_id = f.id AND f.status='live') WHERE fac.status='live' AND fac.has_facility in (1,0) AND fac.listing_type IN ('institute', 'university') AND fac.listing_id IN (?)";

        $result =  $this->db->query($sql,array($instituteIds))->result_array();
        foreach($result as $key=>$val){

        	if($val['additional_info'] !== NULL){
        		$val['additional_info'] = (array)json_decode($val['additional_info'], true);
        	}
            if($val['parent_id']!=0){
                $instFacilityInfo[$val['listing_id']][$val['parent_id']]['child_facilities'][] = $val;
                if(!key_exists('listing_id',$instFacilityInfo[$val['listing_id']][$val['parent_id']])){
            		$instFacilityInfo[$val['listing_id']][$val['parent_id']]['listing_id'] = $val['listing_id'];
            		$instFacilityInfo[$val['listing_id']][$val['parent_id']]['facility_id'] = $val['parent_id'];
            		$instFacilityInfo[$val['listing_id']][$val['parent_id']]['name'] = 'Others';
            		$instFacilityInfo[$val['listing_id']][$val['parent_id']]['parent_id'] = '0';
            	} 
            	// if(key_exists('listing_id',$instFacilityInfo[$val['listing_id']][$val['parent_id']]['child_facilities'][$val['facility_id']])){
            	// 	unset($instFacilityInfo[$val['listing_id']][$val['parent_id']]['child_facilities']);

            	// }
            }else{
                $instFacilityInfo[$val['listing_id']][$val['facility_id']] = $val;
            }        
        }
       
       $sql = "SELECT fm.facility_id,
                        f.name as parent_facility_name,
                        f.parent_id,
                        fm.value_id,
                        fm.listing_id,
                        ba.value_name as facility_name
                FROM shiksha_institutes_facilities_mappings fm 
                JOIN shiksha_institutes_facilities_master f ON (fm.facility_id = f.id AND f.status = 'live')
                LEFT JOIN base_attribute_list ba 
                ON (ba.value_id = fm.value_id AND ba.status = 'live' AND ba.value_name IS NOT NULL)
                WHERE fm.status='live' AND fm.listing_type IN ('institute', 'university') AND fm.listing_id IN (?) 
                
                UNION
                
                SELECT fm.facility_id,
                       f.name as parent_facility_name,
                       f.parent_id,
                       fm.value_id,
                       fm.listing_id,
                       fm.custom_name as facility_name
                FROM shiksha_institutes_facilities_mappings fm
                JOIN shiksha_institutes_facilities_master f ON (fm.facility_id = f.id AND f.status = 'live')
                WHERE fm.status = 'live' AND fm.custom_name IS NOT NULL AND fm.listing_type IN ('institute', 'university') AND fm.listing_id IN (?)";
        $result1 =  $this->db->query($sql,array($instituteIds,$instituteIds))->result_array();
                  	        
        foreach($instFacilityInfo as $key=>$facility){
        	foreach($result1 as $key1=>$otherFacility){
        		if($key == $otherFacility['listing_id']){
        			$instFacilityInfo[$otherFacility['listing_id']][$otherFacility['parent_id']]['child_facilities'][] = $otherFacility;
        		}

        	}
        }
        $instituteFacilities = array();
        foreach($instFacilityInfo as $listingId=>$instituteFacility){
        	foreach ($instituteFacility as $key => $facilityData) {
        		$instituteFacilities[] = $facilityData;
        	}
        }

        return $instituteFacilities;

    }
	private function _getAdditionalInfo($instituteIds){

		$sql = "SELECT attr.listing_id,
					   attr.description,
					   attr.description_type
					 FROM shiksha_institutes_additional_attributes attr WHERE attr.listing_type IN ('institute', 'university') AND attr.status='live' AND attr.listing_id IN (?)";

		return $this->db->query($sql,array($instituteIds))->result_array();
	}

	private function _getEventInfo($instituteIds){

		$sql = "SELECT master.value_name as event_type_name,
					   eve.listing_id,
					   eve.id as event_id,
					   eve.event_type_id,
					   eve.name as event_name,
					   eve.description,
					   eve.position
					FROM shiksha_institutes_events eve 
					INNER JOIN
					base_attribute_list master ON(master.value_id = eve.event_type_id AND master.attribute_name = 'Event Type' AND master.status='live') 
					WHERE eve.listing_type IN ('institute', 'university') AND eve.status='live' AND eve.listing_id IN (?) ORDER BY eve.position ASC";

		return $this->db->query($sql,array($instituteIds))->result_array();
	}

	private function _getMediaInfo($instituteIds){

		$instituteMediaInfo = array();

		$sql = "SELECT mloc.listing_id,
					   mloc.media_id,
					   mloc.media_type,
					   m.media_url,
					   m.media_title,
					   m.media_type,
					   m.media_order,
					   m.media_thumb_url,
					   mloc.listing_location_id as listing_location_ids,
					   m.uploaded_date
					FROM shiksha_institutes_media_locations_mapping mloc LEFT JOIN shiksha_institutes_medias m ON (mloc.media_id = m.media_id AND mloc.media_type = m.media_type  AND m.status = 'live') WHERE mloc.listing_type IN ('institute', 'university') AND mloc.status = 'live' AND mloc.listing_id IN (?) ";
		$result = $this->db->query($sql,array($instituteIds))->result_array();
				
		foreach($result as $key=>$val){
			$mediaInfo[$val['listing_id']] [$val['media_id']][] = $val;
			$mediaIds[] = $val['media_id'];
		}

		$mediaIdArray = array_unique($mediaIds);
		
		foreach($mediaInfo as $key1=>$value){
			foreach($value as $key2=>$val2){
				foreach ($val2 as $key3 => $val3) {
					if($key3 == 0){
						$mediaInfo[$key1][$key2][0]['listing_location_ids'] = array($val3['listing_location_ids']);
					}else{
						$mediaInfo[$key1][$key2][0]['listing_location_ids'] = array_unique(array_merge(array($val3['listing_location_ids']), $mediaInfo[$key1][$key2][0]['listing_location_ids']));
						unset($mediaInfo[$key1][$key2][$key3]);
					}
				}
			}
		}

		if(!empty($mediaIdArray)){
			$sql = "SELECT mt.tag_id,mt.tag_type,lt.tags as tag_name,events.name as event_name,mt.listing_id,mt.media_id 
						FROM shiksha_institutes_media_tags_mapping mt
					LEFT JOIN
					listing_tags lt ON(mt.tag_id = lt.id AND lt.status='live' AND mt.tag_type='tag')
					LEFT JOIN
					shiksha_institutes_events events ON(mt.tag_id = events.id AND events.status='live' AND mt.tag_type='event')
		    		WHERE mt.status = 'live' AND mt.media_id IN (?)";

			$result = $this->db->query($sql,array($mediaIdArray))->result_array();
			
			foreach($result as $key => $val){
				$tagName = $val['tag_name'] != NULL ? $val['tag_name'] : ($val['event_name'] != NULL ? $val['event_name']  : "");
				$mediaInfo[$val['listing_id']][$val['media_id']][0]['tag_ids'][] = array("id"=>$val['tag_id'], "type"=>$val['tag_type'], "name"=> $tagName);
			}

			 foreach($mediaInfo as $listingId=>$media){
	        	foreach ($media as $key1 => $instituteMedia){
	        		foreach ($instituteMedia as $key2 => $mediaData){
	        			$instituteMediaInfo[] = $mediaData;
	        		}
	        	}
	        }
    	}
		
		return $instituteMediaInfo;
	}

	private function _getScholarshipInfo($instituteIds){

		$sql = "SELECT scho.listing_id,
					   scho.scholarship_type_id,
					   ba.value_name as scholarship_type_name,
					   scho.description
					FROM shiksha_institutes_scholarships scho 
					INNER JOIN
					base_attribute_list ba ON(ba.value_id = scho.scholarship_type_id AND ba.attribute_name = 'Scholarship Type' AND ba.status='live') 
					 WHERE scho.listing_type IN ('institute', 'university') AND scho.status='live' AND scho.listing_id IN (?)";

		return $this->db->query($sql,array($instituteIds))->result_array();
	}

	private function _getCompaniesInfo($instituteIds){

		$sql = "SELECT com.listing_id,
					   com.company_id,
					   logos.company_name,
					   logos.logo_url as company_logo,
					   com.order
					FROM shiksha_institutes_companies_mapping com
					INNER JOIN
					company_logos logos
					ON(logos.id=com.company_id AND logos.status = 'live')
 					WHERE com.listing_type IN ('institute', 'university') AND com.status='live' AND com.listing_id IN (?) ORDER BY com.order ASC";
					 
		return $this->db->query($sql,array($instituteIds))->result_array();
	}

	private function _getChildPageInfo($instituteIds){

		$sql = "SELECT i.listing_id,
					   i.is_placement_page_exists as placementPageExists,
					   i.is_flagship_course_placement_data_exists as flagshipCoursePlacementDataExists,
					   i.is_naukri_placement_data_exists as naukriPlacementDataExists,
					   i.is_cutoff_page_exists as cutoffPageExists,
					   i.cutoff_page_exam_name as cutoffPageExamName,
					   i.is_review_page_exists as reviewPageExists,
					   i.is_all_course_page_exists as allCoursePageExists,
					   i.is_admission_page_exists as admissionPageExists
					 FROM shiksha_institutes i
					 WHERE i.status='live' AND i.listing_type IN ('institute','university') AND i.listing_id IN (?)";

		return $this->db->query($sql,array($instituteIds))->result_array();
	}

	private function _getWikiSectionInfo($instituteIds){
		$sql = "SELECT i.listing_id,
					   i.about_college as aboutCollege
					 FROM shiksha_institutes i
					 WHERE i.status='live' AND i.listing_type IN ('institute','university') AND i.listing_id IN (?)";

		$dataInstt = $this->db->query($sql,array($instituteIds))->result_array();

		$sql = "SELECT listing_id, description, description_type
				FROM shiksha_institutes_additional_attributes 
				WHERE status = 'live' AND description_type IN ('placement_info', 'cutoff_info','acp_info','bip_info','sip_info','icop_info','icox_info') AND listing_id IN (?)";

		$dataChildPages = $this->db->query($sql,array($instituteIds))->result_array();
		
		foreach ($dataInstt as $key2 => $value) {
			$data[$value['listing_id']]['listing_id'] = $value['listing_id'];
			$data[$value['listing_id']]['aboutCollege'] = $value['aboutCollege'];
		}

		foreach ($dataChildPages as $key1 => $childValue) {
			$data[$childValue['listing_id']]['listing_id'] = $childValue['listing_id'];
			switch ($childValue['description_type']) {
				case 'cutoff_info':
					$data[$childValue['listing_id']]['aboutCollegeCutOff'] = $childValue['description'];
					break;
				
				case 'placement_info':
					$data[$childValue['listing_id']]['aboutCollegePlacement'] = $childValue['description'];
					break;
			}
		}

		return $data;
	}

	function getCoursesOfInstitutes($instituteIds, $sortBy = '',$dbHandle='writeHandle'){
		if($dbHandle == 'readHandle'){
			$this->db = $this->getReadHandle();			
		}else{
			$this->db = $this->getWriteHandle();
		}
		$coursesInstituteWise = array();

		if(empty($instituteIds))
			return $coursesInstituteWise;

		$this->db->select('primary_id,course_id')->where('status','live')->where_in('primary_id',$instituteIds);

		if($sortBy == 'name'){
			$this->db->order_by('name','asc');
		}

		$sql = "SELECT primary_id,course_id FROM `shiksha_courses` WHERE primary_id IN (?) AND status='live' ".$sortByClause;
	
		$result = $this->db->get('shiksha_courses')->result_array();

		foreach ($result as $value) {
			$coursesInstituteWise[$value['primary_id']][] = $value['course_id'];
		}

		return $coursesInstituteWise;
	}

	public function getLastModificationDate($instituteId=null){
		if($instituteId == null){
			return null;
		}

		$sql = "SELECT last_modify_date,listing_type_id
				FROM listings_main
				WHERE listing_type_id = ?
				AND listing_type = 'institute'
				AND status = 'live'";

		$query = $this->db->query($sql,array($instituteId));

		$result = $query->result_array();

		$finalResult = array();
		foreach ($result as $key => $value) {
			$finalResult[$value['listing_type_id']] = $value['last_modify_date'];
		}
		return $finalResult;
	}
    
    public function getInstituteBasicsDataByStatus($instituteId,$status) {
		
		if($instituteId == null){
			return null;
		}
				
		if(empty($status)) {			
			$status = 'live';
		}
		
		$sql = "SELECT listing_title,listing_type_id,listing_type
				FROM listings_main
				WHERE listing_type_id = ?
				AND listing_type in ('institute','university_national')
				AND status = ?";

		$query = $this->db->query($sql,array($instituteId,$status));

		$result = $query->result_array();

		$finalResult = array();
		foreach ($result as $key => $value) {
			if($value['listing_type'] == 'university_national') {
				$value['listing_title'] = $value['listing_title']."(University)";
				$finalResult[$value['listing_type_id']] = $value;
			} else {
				$finalResult[$value['listing_type_id']] = $value;
			}
		}
		
		return $finalResult;	
		
    }

    public function getNaukriSalaryData($instituteId, $numberOfInstitutes = 'single'){
		if($numberOfInstitutes == 'single') {
			$sql = "SELECT * FROM naukri_salary_data WHERE institute_id = ?";
			$results = $this->db->query($sql, (int) $instituteId)->result_array();
		}
		else if($numberOfInstitutes == 'multiple') {
			$sql = "SELECT * FROM naukri_salary_data WHERE institute_id IN (?)";
			$results = $this->db->query($sql,array($instituteId))->result_array();
		}
		
		return $results;
	}


}
