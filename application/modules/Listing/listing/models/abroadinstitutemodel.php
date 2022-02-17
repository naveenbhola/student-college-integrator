<?php

require_once dirname(__FILE__).'/ListingModelAbstract.php';

class AbroadInstituteModel extends ListingModelAbstract
{
	protected $defaultFilters = array('general','locations');

	function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * Get consolidated info for an institute
	 * Required info can be specified via Filters -
	 * Filters are pipe separated e.g. 'general|locations'
	 * Returned data is indexed by filters
	 * i.e. array('general' => array(),'locations' => array())
	 */
	public function getData($instituteId,$filters = '')
	{
		Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');

		$instituteIds = array($instituteId);
		$data = $this->_getFilterwiseConsolidatedData($instituteIds,$filters);
		return $this->removeExtraDepthForKeys($data,array('general','view_count'));
	}

	/*
	 * Get consolidated info for a set of institutes at once
	 * Returned data is indexed by 'institute ids -> Filters'
	 */
	public function getDataForMultipleInstitutes($instituteIds,$filters = '')
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds,'Institute IDs');

		$data = $this->_getFilterwiseConsolidatedData($instituteIds,$filters);
		return $this->indexFilteredDataByListingIds($data,'institute_id',array('general','view_count'));
	}

	/*
	 * Run each filter, retrive data for the same and consolidate
	 */
	private function _getFilterwiseConsolidatedData($instituteIds,$filters = '')
	{
		$validFilters = $this->validateFilters($filters);
		$data = array();
		foreach($validFilters as $filter){
			switch($filter) {
				case 'general':
					$data['general'] = $this->_getGeneralInfo($instituteIds);
					break;
				case 'locations':
					$data['locations'] = $this->_getLocations($instituteIds);
					break;
			}
		}
		
		return $data;
	}

	/*
	 * General info includes all the data from `institute` table
	 * Alongwith data from `listings_main` and `institute_mediacount_rating_info`
	 */
	private function _getGeneralInfo($instituteIds)
	{
		if(empty($instituteIds)){
			return array();
		}
		if(!(is_array($instituteIds))){
			$instituteIds = explode(',', $instituteIds);
		}
		$sql =  "SELECT i.*,".
				"uni.university_id,uni.name as university_name,uni.type_of_institute2 as university_type,".
				"lm.listing_seo_url,lm.listing_seo_title,lm.listing_seo_description,lm.listing_seo_keywords,".
				"lm.viewCount as cumulativeViewCount,lm.pack_type,lm.last_modify_date,".
                "im.photo_count,im.video_count,im.alumni_rating,im.ratings_json ".
                "FROM institute i ".
                "LEFT JOIN listings_main lm ON (lm.listing_type_id = i.institute_id AND lm.listing_type = 'institute' AND lm.status = 'live') ".
				"LEFT JOIN institute_university_mapping iu ON (iu.institute_id = i.institute_id AND iu.status = 'live') ".
				"LEFT JOIN university uni ON (iu.university_id = uni.university_id AND uni.status = 'live') ".
                "LEFT JOIN institute_mediacount_rating_info im ON im.institute_id = i.institute_id ".
                "WHERE i.status = 'live' ".
				"AND i.institute_id IN (?)";
		
		$results = $this->db->query($sql,array($instituteIds))->result_array();
		
		/**
		 * External links
		 */ 
		$sql = "SELECT listing_type_id as institute_id, link_type, link ".
                "FROM listing_external_links ".
                "WHERE status = 'live' ".
				"AND listing_type = 'institute' AND listing_type_id IN (?)";

		$externalLinkResults = $this->db->query($sql,array($instituteIds))->result_array();
		
		$externalLinks = array();
		foreach($externalLinkResults as $result) {
			$linkKey = '';
			switch($result['link_type']) {
				case 'FB_PAGE':
					$linkKey = 'facebook_page';
					break;
				case 'FACULTY_PAGE':
					$linkKey = 'faculty_page_link';
					break;
				case 'ALUMNI_PAGE':
					$linkKey = 'alumni_page_link';
					break;
			}
			$externalLinks[$result['institute_id']][$linkKey] = $result['link'];
		}
		
		/**
		 * Attributes
		 */
		$sql = "SELECT listing_type_id as institute_id, caption, attributeValue ".
                "FROM listing_attributes_table ".
                "WHERE status = 'live' ".
				"AND listing_type = 'institute' AND listing_type_id IN (?)";

		$attributeResults = $this->db->query($sql,array($instituteIds))->result_array();
		
		$attributes = array();
		foreach($attributeResults as $result) {
			$attributeKey = '';
			switch($result['caption']) {
				case 'DEPT_DESCRIPTION':
					$attributeKey = 'institute_description';
					break;
				case 'DEPT_ACCREDITATION_DETAILS':
					$attributeKey = 'accreditation';
					break;
			}
			$attributes[$result['institute_id']][$attributeKey] = $result['attributeValue'];
		}
		
		
		/**
		 * Add external links and attributes to original result set for general info
		 */
		for($i=0;$i<count($results);$i++) {
			$result = $results[$i];
			$instituteId = $result['institute_id'];
			foreach($externalLinks[$instituteId] as $linkType => $link) {
				$result[$linkType] = $link;
			}
			foreach($attributes[$instituteId] as $caption => $attributeValue) {
				$result[$caption] = $attributeValue;
			}
			$results[$i] = $result;
		}
		
		return $results;	
	}
	/*
	 * Location info includes all the data from `institute_location_table` table
	 * Alongwith data from `listing_contact_details`,countryCityTable,stateTable,localityCityMapping,
	 * tZoneMapping and virtualCityMapping
	 */
	private function _getLocations($instituteIds)
	{
		if(empty($instituteIds)){
			return array();
		}
		if(!(is_array($instituteIds))){
			$instituteIds = explode(',', $instituteIds);
		}
		$sql = "SELECT loc.institute_location_id,loc.institute_id,".//loc.address_3, ".
                /*"loc.address_2,*/"loc.address_1,"./*loc.pincode, ".*/
                "city.city_name,city.enabled,city.tier,city.city_id, ".
                "country.countryId,country.name,country.enabled,country.urlName,".
                "state.state_id,state.state_name,state.enabled,".
                "trm.regionid, ".
                "tregion.regionname ".
                "FROM institute_location_table loc ".
                "LEFT JOIN countryCityTable city ON city.city_id = loc.city_id ".
                "LEFT JOIN stateTable state ON state.state_id = city.state_id ".
                "LEFT JOIN countryTable country ON country.countryId = loc.country_id ".
                "LEFT JOIN tregionmapping trm on (loc.country_id = trm.id AND trm.regionmapping = 'country') ".
                "LEFT JOIN tregion on tregion.regionid = trm.regionid ".
                "WHERE loc.status = 'live' ".
		"AND loc.institute_id IN (?)";

		$result = $this->db->query($sql,array($instituteIds))->result_array();
		// set flagship_course_location
		//$result= $this->_getLocationOfFlagshipCourse($result,$instituteIds);
		// add contact details to institutes locations
		$result = $this->_getContactDetailsForLocations($result,$instituteIds);
		return $result;			
	}

	/*
	 * Returns contact details for all the locations of institute
	 */
	private function _getContactDetailsForLocations($result,$instituteIds)
	{
		if(empty($instituteIds)){
			return array();
		}
		if(!(is_array($instituteIds))){
			$instituteIds = explode(',', $instituteIds);
		}
		// contact details query
		$listing_contact_details_query =
		          "select listing_type_id, institute_location_id, ".
		          "contact_details_id,contact_person,contact_email, ".
                  "contact_main_phone,contact_cell,contact_alternate_phone,contact_fax,website ".
                  "FROM listing_contact_details where listing_type_id IN (?) ".
		          "AND listing_type ='institute' AND status='live'";
		
		// get result array
		$contact_details_array = $this->db->query($listing_contact_details_query,array($instituteIds))->result_array();
		// get contact details for locations
		foreach($contact_details_array as $contact) {
			$contact_details_array_new[$contact['listing_type_id']][$contact['institute_location_id']] = $contact;
		}
		$final_result_array_new = array();
		// prepare final result set
		foreach($result as $row) {
			if($contact_details_array_new[$row['institute_id']][$row['institute_location_id']]) {
				$final_result_array_new[] = array_merge($row,$contact_details_array_new[$row['institute_id']][$row['institute_location_id']]);
			} else {
				$final_result_array_new[] = $row;
			}

		}
		return $final_result_array_new;
	}
	
	/*
	 * All institutes which are currently live
	 */ 
	public function getLiveAbroadInstitutes()
	{
		$sql =  "SELECT DISTINCT institute_id ".
				"FROM abroadCategoryPageData ".
				"WHERE status = 'live'";
		
		return $this->getColumnArray($this->db->query($sql)->result_array(),'institute_id');
	}
	
	
	public function checkIfInstituteIdBelongsToAbroad($instituteId) {
		$this->db = $this->getWriteHandle();
		$sql = "SELECT  distinct `institute_id` as institute_id, institute_type FROM `institute` WHERE  `institute_id` =? ";
		$results = $this->db->query($sql,array($instituteId))->result_array();
		return $results[0];
	}
	
	public function fetchDiffOfValidAbroadInstituteIds($instituteIds) {
		
		if(empty($instituteIds)){
			return array();
		}
		if(!(is_array($instituteIds))){
			$instituteIds = explode(',', $instituteIds);
		}
		
		if(count($instituteIds) > 0) {
			$sql = "SELECT  distinct institute_id FROM `institute` WHERE  `institute_id` IN (?)  and `institute_type` IN ('Department','Department_Virtual')";
			$instituteIds = $this->getColumnArray($this->db->query($sql,array($instituteIds))->result_array(),'institute_id');
		
		} else {
			$instituteIds = array();
		}
		return $instituteIds;
	}
	
	public function getCoursesOfDepartments($departmentIds){
		if(empty($departmentIds)){
			return array();
		}
		if(!(is_array($departmentIds))){
			$departmentIds = explode(',', $departmentIds);
		}
		$sql = "select course_id, institute_id as deptid from course_details where status = 'live' and institute_id in (?)";
		$results = $this->db->query($sql,array($departmentIds))->result_array();
		return $results;
	}

}