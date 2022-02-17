<?php

require_once dirname(__FILE__).'/ListingModelAbstract.php';

class UniversityModel extends ListingModelAbstract
{
	protected $defaultFilters = array('general','locations','admission_contact','snapshot_departments','campus_accommodation','campuses','contact_details','media','snapshot_courses','announcement');

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
		return $this->indexFilteredDataByListingIds($data,'university_id',array('general','view_count'));
	}

	function getUniversityNameById($universityId)
    {
		$sql =  "select name,university_id from university where status='live' and university_id= ?";
		
		$results = $this->db->query($sql,array($universityId))->result_array();
		return $results;
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
					$data['general'] = $this->_getGeneralInfo($universityIds);
					break;
				case 'locations':
					$data['locations'] = $this->_getLocations($universityIds);
					break;
				case 'media':
					$data['media'] = $this->_getMedia($universityIds);
					break;
				case 'admission_contact':
					$data['admission_contact'] = $this->_getAdmissionContact($universityIds);
					break;
				case 'snapshot_departments':
					$data['snapshot_departments'] = $this->_getSnapshotDepartments($universityIds);
					break;
				case 'campus_accommodation':
					$data['campus_accommodation'] = $this->_getCampusAccommodation($universityIds);
					break;
				case 'campuses':
					$data['campuses'] = $this->_getCampuses($universityIds);
					break;
				case 'contact_details':
					$data['contact_details'] = $this->_getContactDetails($universityIds);
					break;
				case 'snapshot_courses':
					//$data['snapshot_courses'] = $this->_getSnapshotCourses($universityIds);
					break;
				case 'announcement':
					$data['announcement'] = $this->_getAnnouncementDetails($universityIds);
					break;
			}
		}
		
		return $data;
	}

	/*
	 * General info includes all the data from `university` table
	 * Alongwith data from `listings_main`
	 */
	
	private function _getGeneralInfo($universityIds)
	{
		$sql =  "SELECT u.*,".
		"lm.listing_seo_url,lm.listing_seo_title,lm.listing_seo_description,lm.listing_seo_keywords,lm.viewCount as cumulativeViewCount,lm.pack_type,lm.last_modify_date ".
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
		 * Add external links and attributes to original result set for general info
		 */
		for($i=0;$i<count($results);$i++) {
			$result = $results[$i];
			$universityId = $result['university_id'];
			foreach($externalLinks[$universityId] as $linkType => $link) {
				$result[$linkType] = $link;
			}
			foreach($attributes[$universityId] as $caption => $attributeValue) {
				$result[$caption] = $attributeValue;
			}
			$results[$i] = $result;
		}
		return $results;	
	}
	
	/*
	 * Location info includes all the data from `university_location_table` table
	 * Alongwith data from `listing_contact_details`,countryCityTable,stateTable,localityCityMapping,
	 * tZoneMapping and virtualCityMapping
	 */
	private function _getLocations($universityIds)
	{
		$sql = "SELECT loc.university_location_id,loc.university_id,loc.address, ".
                "city.city_name,city.enabled,city.tier,city.city_id, ".
                "country.countryId,country.name,country.enabled,country.urlName,".
                "state.state_id,state.state_name,state.enabled,".
				"trm.regionid, tregion.regionname ".
                "FROM university_location_table loc ".
                "LEFT JOIN countryCityTable city ON city.city_id = loc.city_id ".
                "LEFT JOIN stateTable state ON state.state_id = city.state_id ".
                "LEFT JOIN countryTable country ON country.countryId = loc.country_id ".
                "LEFT JOIN tregionmapping trm on (loc.country_id = trm.id AND trm.regionmapping = 'country') ".
                "LEFT JOIN tregion on tregion.regionid = trm.regionid ".
                "WHERE loc.status = 'live' ".
				"AND loc.university_id IN (?)";
			
		$result = $this->db->query($sql,array($universityIds))->result_array();
		return $result;			
	}
	
	private function _getAdmissionContact($universityIds)
	{
		$sql = "SELECT acd.listing_type_id as university_id,acd.admission_contact_person,acd.admission_website_url, ".
                "city.city_name,city.enabled,city.tier,city.city_id,city.countryId,city.state_id ".
                "FROM listing_admission_contact_details acd ".
                "LEFT JOIN countryCityTable city ON city.city_id = acd.city_id ".
                "WHERE acd.status = 'live' ".
				"AND acd.listing_type_id IN (?)";

		$result = $this->db->query($sql,array($universityIds))->result_array();
		return $result;			
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
		$sql = "SELECT university_id, accommodation_details, accommodation_website_url, living_expenses, currency, living_expense_details, living_expense_website_url, currency.id as currency_id, currency.currency_code, currency.currency_name, currency.country_id as currency_country_id ".
                "FROM university_campus_accommodation LEFT JOIN currency on university_campus_accommodation.currency = currency.id ".
                "WHERE status = 'live' ".
				"AND university_id IN (?)";

		$result = $this->db->query($sql,array($universityIds))->result_array();
		return $result;			
	}
	
	private function _getCampuses($universityIds)
	{
		$sql = "SELECT university_id, campus_name,campus_website_url,campus_address ".
                "FROM university_campuses ".
                "WHERE status = 'live' ".
				"AND university_id IN (?)";

		$result = $this->db->query($sql,array($universityIds))->result_array();
		return $result;			
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
		$sql =  "SELECT lm.type_id as university_id,lm.media_id,lm.media_type,".
				"im.name,im.url,im.thumburl, ".
		        "im.institute_location_id ".
				"FROM listing_media_table lm ".
				"INNER JOIN institute_uploaded_media im ON (im.listing_type_id = lm.type_id AND im.media_id = lm.media_id AND im.listing_type = 'university') ".
				"WHERE lm.type = 'university' ".
				"AND lm.type_id IN (?) ".
				"AND lm.status = 'live' ".
				"AND im.status = 'live'";

		return $this->db->query($sql,array($universityIds))->result_array();
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
		return $result;			
	}
}