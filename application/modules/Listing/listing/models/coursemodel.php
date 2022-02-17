<?php

require_once dirname(__FILE__).'/ListingModelAbstract.php';

class CourseModel extends ListingModelAbstract
{
	protected $defaultFilters = array('general','attributes','salient_features','exams','recruiting_companies','events','ease_of_immigration','ranking','locations','ldb_courses');
    protected $customFilter = array('basic_info','head_location');
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * Get ebrochure upload status for a list of courses
	 * Returns array
	 */
	public function getEbrochureStatusForCourses($courses = array()) {
		
		if(count($courses) == 0) {
			return array();
		}
		
		$result = $this->_getEbrochureStatusForCourses($courses);
		foreach ($result as $row) {
			$course_ebrochure_index_array[$row['course_id']] = $row['course_request_brochure_link']; 	
		}
		
		return $course_ebrochure_index_array;
	}
	
	/*
	 * Get viewcount data of courses
	 * Returns array
	 */
	public function getRecentViewCount($courseIds)
	{
		if(!is_array($courseIds)) {
			$courseIds = array($courseIds);
		}
		$results = $this->_getRecentViewCount($courseIds);
		
		if(!is_array($results) || count($results) == 0) {
			return array();
		}
		
		$results_modified = array();
		
		foreach($results as $result) {
				$results_modified[$result['course_id']] = $result['viewCount'];
		}
		unset($results);
		error_log("#####".print_r($result,true));
		return $results_modified;
	}
	/*
	 * Get viewcount data of courses
	 * Returns array
	 */
	public function getCourseViewCount($courseIds)
	{
		if(!is_array($courseIds)) {
			$courseIds = array($courseIds);
		}
		$results = $this->_getCourseViewCount($courseIds);
		if(!is_array($results) || count($results) == 0) {
			return array();
		}
		return $results;
	}
	/*
	 * Get contact details for course from institute
	 * Returns array
	 */
	public function getCourseContactDetailsFromInstitute($locationIds,$inst_id)
	{
		return array(); // not in use
		$results = $this->_getCourseContactDetailsFromInstitute($locationIds,$inst_id);
		if(!is_array($results) || count($results) == 0) {
			return array();
		}
		foreach ($results as $result) {
			$result_array[$result['institute_location_id']] = $result;
		}
		unset($results);
		return $result_array;
	}
	/*
	 * Get main contact details of course
	 * Returns array
	 */
	public function getMainCourseContactDetails($courseId)
	{
		Contract::mustBeNumericValueGreaterThanZero($courseId,'Institute ID');
		$results = $this->_getMainCourseContactDetails($courseId);
		if(!is_array($results) || count($results) == 0) {
			return array();
		}
		return $results[0];
	}
	/*
	 * Get consolidated data for course description attributes
	 * Returns array
	 */
	public function getDataForCourseDescriptionAttributes($courseId)
	{
		Contract::mustBeNumericValueGreaterThanZero($courseId,'Course ID');
		$results = $this->_getDataForCourseDescriptionAttributes($courseId);
		if(!is_array($results) || count($results) == 0) {
			return array();
		}
		return $results;
	}
	/*
	 * Get consolidated info for a course
	 * Required info can be specified via Filters -
	 * Filters are pipe separated e.g. 'general|attributes'
	 * Returned data is indexed by filters
	 * i.e. array('general' => array(),'attributes' => array())
	 */
	public function getData($courseId,$filters = '')
	{
		Contract::mustBeNumericValueGreaterThanZero($courseId,'Course ID');

		$courseIds = array($courseId);
		$data = $this->_getFilterwiseConsolidatedData($courseIds,$filters);
		return $this->removeExtraDepthForKeys($data,array('general','ease_of_immigration'));
	}

	/*
	 * Get consolidated info for a set of courses at once
	 * Returned data is indexed by 'course ids -> Filters'
	 */
	public function getDataForMultipleCourses($courseIds,$filters = '',$isCustomFilter = FALSE)
	{ 
		Contract::mustBeNonEmptyArrayOfIntegerValues($courseIds,'Course IDs');
        
		$data = $this->_getFilterwiseConsolidatedData($courseIds,$filters,$isCustomFilter);
		
		
		return $this->indexFilteredDataByListingIds($data,'course_id',array('general','ease_of_immigration','basic_info'));
	}

	private function _getFilterwiseConsolidatedData($courseIds,$filters = '',$isCustomFilter = FALSE)
	{
		error_log('Code Usability Check:coursemodel: _getFilterwiseConsolidatedData', 3, '/tmp/listing_server.log'); 
		if($isCustomFilter) {
			$validFilters = explode('|',$filters);
		} else {
		  $validFilters = $this->validateFilters($filters);
		}
		
		$data = array();

		foreach($validFilters as $filter){
			switch($filter) {
				case 'general':
					$data['general'] = $this->_getGeneralInfo($courseIds);
					break;
				case 'attributes':
					$data['attributes'] = $this->_getAttributes($courseIds);
					break;
				case 'salient_features':
					$data['salient_features'] = $this->_getSalientFeatures($courseIds);
					break;
				case 'exams':
					$data['exams'] = $this->_getExams($courseIds);
					break;
				case 'recruiting_companies':
					$data['recruiting_companies'] = $this->_getRecruitingCompanies($courseIds);
					break;
				case 'events':
					$data['events'] = $this->_getEvents($courseIds);
					break;
				case 'ease_of_immigration':
					$data['ease_of_immigration'] = $this->_getEaseOfImmigration($courseIds);
					break;
				case 'ranking':
					$data['ranking'] = $this->_getRanking($courseIds);
					break;
				case 'locations':
					$data['locations'] = $this->_getLocations($courseIds);
					break;
				case 'basic_info':
					$data['basic_info'] = $this->_getBasicInfo($courseIds);
					break;
				case 'head_location':
					$data['head_location'] = $this->_getHeadLocation($courseIds);
					break;
				case 'ldb_courses':
					$data['ldb_courses'] = $this->_getLdbCourseIds($courseIds);
					break;
				}
		}

		return $data;
	}

	/*
	 * General info includes all the data from `course_details` table
	 * Alongwith data from `listings_main`
	 */
	private function _getGeneralInfo($courseIds)
	{
		$sql =  "SELECT  cd.*,lm.pack_type,lm.listing_seo_url,lm.listing_seo_title,lm.listing_seo_description,lm.listing_seo_keywords,i.institute_name,city.city_id,lm.last_modify_date, lm.expiry_date , lm.username, city.city_name,country.countryId as country_id,country.name as country_name, lm.viewCount as cumulativeViewCount, lm.editedBy as last_modifed_by, lm.use_stored_seo_data_flag as useStoredSeoDataFlag ".
				"FROM course_details cd ".
				"LEFT JOIN listings_main lm ON (lm.listing_type_id = cd.course_id AND lm.listing_type = 'course' AND lm.status = 'live') ".
				"LEFT JOIN institute i ON (i.institute_id = cd.institute_id AND i.status = 'live') ".
				"LEFT JOIN institute_location_table loc ON (loc.institute_id = cd.institute_id AND loc.status = 'live') ".
				"LEFT JOIN countryCityTable city ON (city.city_id = loc.city_id) ".
				"LEFT JOIN countryTable country ON (country.countryId = loc.country_id) ".
				"WHERE cd.status = 'live' ".
				"AND cd.course_id IN (?)";

		$data = $this->db->query($sql,array($courseIds))->result_array();
		$data[0]['fees_value'] = ltrim($data[0]['fees_value'],'0');
		return $data;
	}
	
	private function _getBasicInfo($courseIds)
	{	
		$courses = implode(',',$courseIds);
		$sql =  "SELECT  cd.*,lm.pack_type,lm.listing_seo_url,lm.listing_seo_title,lm.listing_seo_description,lm.listing_seo_keywords,i.institute_name,lm.last_modify_date, lm.expiry_date , lm.username, lm.viewCount as cumulativeViewCount, lm.editedBy as last_modifed_by, lm.use_stored_seo_data_flag as useStoredSeoDataFlag ".
				"FROM course_details cd ".
				"LEFT JOIN listings_main lm ON (lm.listing_type_id = cd.course_id AND lm.listing_type = 'course' AND lm.status = 'live') ".
				"LEFT JOIN institute i ON (i.institute_id = cd.institute_id AND i.status = 'live') ".
				"WHERE cd.status = 'live' ".
				"AND cd.course_id IN (?) ".
				"ORDER BY FIELD(cd.course_id,".$this->db->escape_str($courses).")";
	   
		$data = $this->db->query($sql,array($courseIds))->result_array();
		$data[0]['fees_value'] = ltrim($data[0]['fees_value'],'0');
		
		return $data;
	}
	
	private function _getLdbCourseIds($courseIds) {
		$sql = "SELECT clientCourseID as course_id, LDBCourseID as ldbCourseId ".	
				"FROM clientCourseToLDBCourseMapping ".
				"WHERE status = 'live' AND clientCourseID IN (?)";
		$data = $this->db->query($sql,array($courseIds))->result_array();
		
		return $data;
	}

	private function _getAttributes($courseIds)
	{
		$sql =  "SELECT * ".
				"FROM course_attributes ".
				"WHERE status = 'live' ".
				"AND course_id IN (?)";

		return $this->db->query($sql,array($courseIds))->result_array();
	}

	private function _getSalientFeatures($courseIds)
	{
		$sql =  "SELECT cf.listing_id as course_id, cf.salient_feature_id,".
				"sf.feature_name,sf.value,sf.display_order ".
				"FROM course_features cf ".
				"INNER JOIN salient_features sf ON sf.feature_id = cf.salient_feature_id ".
				"WHERE cf.status = 'live' ".
				"AND cf.listing_id IN (?) ".
				"AND sf.display_flag = 'Yes' ".
				"ORDER BY sf.feature_id";

		return $this->db->query($sql,array($courseIds))->result_array();
	}

	private function _getExams($courseIds)
	{
		$sql =  "SELECT lem.typeId as course_id,lem.examId,lem.typeOfMap,lem.marks,lem.marks_type,".
				"bt.blogTitle as examName,bt.acronym,lem.valueIfAny as practiceTestsOffered ".
				"FROM listingExamMap lem ".
				"INNER JOIN blogTable bt ON bt.blogId = lem.examId ".
				"WHERE lem.status = 'live' AND bt.status = 'live' ".
				"AND lem.type = 'course' ".
				"AND lem.typeId IN (?)";

		return $this->db->query($sql,array($courseIds))->result_array();
	}

	private function _getRecruitingCompanies($courseIds)
	{
		$sql =  "SELECT clm.listing_id as course_id,clm.company_order,clm.logo_id as company_id,".
				"cl.company_name,cl.logo_url ".
				"FROM company_logo_mapping clm ".
				"INNER JOIN company_logos cl ON cl.id = clm.logo_id ".
				"WHERE clm.status = 'live' ".
				"AND clm.listing_id IN (?) ".
				"AND clm.linked = 'yes' ".
				"ORDER BY clm.company_order";

		return $this->db->query($sql,array($courseIds))->result_array();
	}

	private function _getEvents($courseIds)
	{
		$sql =  "SELECT e.event_id,e.event_title,e.fromOthers,e.listing_type_id as course_id,".
				"ed.start_date,ed.end_date ".
				"FROM event e ".
				"LEFT JOIN event_date ed ON ed.event_id = e.event_id ".
				"WHERE e.listingType = 'course' ".
				"AND e.listing_type_id IN (?)  AND ed.end_date > now()";

		return $this->db->query($sql,array($courseIds))->result_array();
	}

	private function _getEaseOfImmigration($courseIds)
	{
		$sql =  "SELECT cpd.course_id,eim.id as easeOfImmigration ".
				"FROM categoryPageData cpd ".
				"LEFT JOIN easesImmigrationMapping eim ON (eim.LDBCourseId = cpd.ldb_course_id AND eim.countryId = cpd.country_id AND eim.status = 'live') ".
				"WHERE cpd.course_id IN (?) ".
				"AND cpd.status = 'live'";

		return $this->db->query($sql,array($courseIds))->result_array();
	}

	private function _getRanking($courseIds)
	{
		$sql =  "SELECT cpd.course_id,r.ranking,rs.sourceName,rs.sourceURL ".
				"FROM categoryPageData cpd ".
				"LEFT JOIN listingRankings r ON (r.listingId = cpd.course_id AND r.listingType = 'course' AND r.status = 'live') ".
				"LEFT JOIN listingRankingSources rs ON (rs.rankingType = r.rankingType AND rs.countryId = cpd.country_id) ".
				"WHERE cpd.course_id IN (?) ".
				"AND cpd.status = 'live'";

		return $this->db->query($sql,array($courseIds))->result_array();
	}
	/*
	 * Returns results for course description attribute
	 */
	private function _getDataForCourseDescriptionAttributes($id) {
		$sql =  "SELECT distinct la.keyId as attributeId,la.caption as attributeName, ".
		"la.attributeValue as attributeValue,la.isPaid as isPaid,la.version as version ".
		"FROM listing_attributes_table la, listing_fields_table  lf ".
		"WHERE (la.`keyId`= lf.keyId OR la.keyId =-1) AND la.`listing_type`='course' ".
		"AND la.`status`='live' AND `listing_type_id`= ? ORDER BY lf.detailPageOrder";
		//error_log("join reason data".$sql);
		return $this->db->query($sql,array($id))->result_array();
	}
	/*
	 * Returns location details for courses
	 */
	private function _getLocations($courseIds)
	{
		$sql =  "SELECT loc.institute_location_id,loc.institute_id,loc.address_3, ".
                "loc.address_2,loc.address_1,loc.pincode,loc.locality_name as customLocalityName,cla.course_id, ".
                "city.city_name,city.enabled,city.tier,vcm.virtualCityId,city.city_id, ".
                "country.countryId,country.name,country.enabled,country.urlName,country.tier as countryTier,".
                "state.state_id,state.state_name,state.enabled,state.tier as stateTier,".
                "locality.localityId,locality.localityName,".
                "zone.zoneId,zone.zoneName ".
                "FROM institute_location_table loc ".
				"JOIN course_location_attribute cla ".
                "LEFT JOIN countryCityTable city ON city.city_id = loc.city_id ".
                "LEFT JOIN stateTable state ON state.state_id = city.state_id ".
                "LEFT JOIN countryTable country ON country.countryId = loc.country_id ".
                "LEFT JOIN localityCityMapping locality ON locality.localityId = loc.locality_id ".
                "LEFT JOIN tZoneMapping zone ON zone.zoneId = loc.zone ".
				"LEFT JOIN virtualCityMapping vcm ON (vcm.city_id = loc.city_id AND vcm.virtualCityId != loc.city_id) ".
                "WHERE loc.status = 'live' ".
				"AND loc.institute_location_id = cla.institute_location_id ".
				"AND cla.status='live' ".
				"AND cla.attribute_type = 'Head Office' ".
				"AND cla.course_id IN (?)";
		//error_log('aditya'.$sql);
		// listing attributes query
		$result_array = $this->db->query($sql,array($courseIds))->result_array();
		// add locations attributes
		$final_result_array = $this->_getLocationsAttributes($result_array,$courseIds);
		// add contact details for locations
		$final_result_array_new = $this->_getContactDetailsForLocations($final_result_array,$courseIds);
		return $final_result_array_new;
	}

    /*
    *   Get Head location of Course ids
    */

    private function _getHeadLocation($courseIds) {
    	$courses = implode(',',$courseIds);
         $sql =  "SELECT loc.institute_location_id,loc.institute_id,loc.address_3, ".
                "loc.address_2,loc.address_1,loc.pincode,loc.locality_name as customLocalityName,cla.course_id, ".
                "cla.course_location_attribute_id,cla.attribute_type,cla.attribute_value,".
                "city.city_name,city.enabled,city.tier,vcm.virtualCityId,city.city_id, ".
                "country.countryId,country.name,country.enabled,country.urlName,country.tier as countryTier,".
                "state.state_id,state.state_name,state.enabled,state.tier as stateTier,".
                "locality.localityId,locality.localityName,".
                "zone.zoneId,zone.zoneName ".
                "FROM institute_location_table loc ".
				"JOIN course_location_attribute cla ".
                "LEFT JOIN countryCityTable city ON city.city_id = loc.city_id ".
                "LEFT JOIN stateTable state ON state.state_id = city.state_id ".
                "LEFT JOIN countryTable country ON country.countryId = loc.country_id ".
                "LEFT JOIN localityCityMapping locality ON locality.localityId = loc.locality_id ".
                "LEFT JOIN tZoneMapping zone ON zone.zoneId = loc.zone ".
				"LEFT JOIN virtualCityMapping vcm ON (vcm.city_id = loc.city_id AND vcm.virtualCityId != loc.city_id) ".
                "WHERE loc.status = 'live' ".
				"AND loc.institute_location_id = cla.institute_location_id ".
				"AND cla.status='live' ".
				"AND cla.attribute_type = 'Head Office' ".
				"AND cla.course_id IN (?) AND cla.attribute_value = 'TRUE' ".
				"ORDER BY FIELD(cla.course_id,".$this->db->escape_str($courses).")";    	
    
			$result_array = $this->db->query($sql,array($courseIds))->result_array();
    	    foreach ($result_array as $result) {
			$result['locationattribute'][][] =  array(
			  											"course_location_attribute_id" => $result['course_location_attribute_id'],
                                    					"attribute_type" => $result['attribute_type'],
                                    					"attribute_value" => $result['attribute_value'],
                                    					"institute_location_id" => $result['institute_location_id'],
                                    					"course_id" => $result['course_id']
                                    					);
			    $final_result_array[] = $result;
		       }
		// usnet temp arrays
		unset($result_array);

		return $final_result_array;

    }

	/*
	 * Get main contact details which is defined at course level
	 */
	private function _getMainCourseContactDetails($id) {
		$sql = "select contact_details_id,contact_person,contact_email,contact_main_phone,contact_cell ".
		        "contact_alternate_phone,contact_fax,website, institute_location_id ".
		        "FROM listing_contact_details ".
		        "WHERE listing_type='course' AND listing_type_id= ? ".
		        "AND institute_location_id = 0 AND status ='live'";

		return $this->db->query($sql,array($id))->result_array();
	}
	/*
	 * Get contact details defined at institute level for a location
	 * NOT IN USE
	 */
	private function _getCourseContactDetailsFromInstitute($locationIds,$inst_id) {
		if(empty($inst_id)){return ;}
		$sql = "select ilt.institute_location_id,lcd.contact_details_id,lcd.contact_person,lcd.contact_email,lcd.contact_main_phone,lcd.contact_cell, ".
		        "lcd.contact_alternate_phone,lcd.contact_fax,lcd.website ".
		        "FROM listing_contact_details lcd ,".
		        "institute_location_table ilt ".
		        "WHERE ilt.institute_id= ? ".
		        "AND ilt.institute_location_id = lcd.institute_location_id ".
		        "AND lcd.listing_type_id = ? ".
		        "AND lcd.listing_type = 'institute' ".
		        "AND ilt.institute_location_id IN (".implode(',',$locationIds).") ".
		        "AND ilt.status ='live' ".
		        "AND lcd.status = 'live'";

		return $this->db->query($sql,array($inst_id,$inst_id))->result_array();
	}
	/*
	 * Returns course location attributes, data from course_location_attribute
	 */
	private function _getLocationsAttributes($result_array,$courseIds) {
		$sql = "SELECT course_location_attribute_id,attribute_type,attribute_value, ".
		       "institute_location_id,course_id FROM course_location_attribute WHERE course_id IN (?)"." and status='live'";

		$attribute_result = $this->db->query($sql,array($courseIds))->result_array();
		for($i = 0; $i < sizeof($attribute_result);$i++){
			if($attribute_result[$i]['attribute_type'] == 'Course Fee Value'){
				$attribute_result[$i]['attribute_value'] = ltrim($attribute_result[$i]['attribute_value'],'0');
			}
		}
		foreach ($attribute_result as $attribute) {
			$attribute_array[$attribute['course_id']][$attribute['institute_location_id']][] = $attribute;
		}

		foreach ($result_array as $result) {
			$result['locationattribute'][] = $attribute_array[$result['course_id']][$result['institute_location_id']];
			$final_result_array[] = $result;
		}
		// usnet temp arrays
		unset($result_array);
		unset($attribute_array);
		unset($attribute_result);

		return $final_result_array;
	}
	/*
	 * Returns results for institute view count
	 */
	private function _getCourseViewCount($courseIds) {
		$sql =  "SELECT lm.viewCount ,cd.course_id as id ".
				"FROM course_details cd, listings_main lm ".
				"WHERE cd.course_id IN (".implode(',',$courseIds).") ".
				"AND lm.status='live' AND cd.status='live' ".
				"AND cd.course_id = lm.listing_type_id AND lm.listing_type = 'course' ";

		$result_array = array();
		foreach ($this->db->query($sql)->result_array() as $row) {
			$result_array[$row['id']] = $row;
		}
		return $result_array;
	}
	/*
	 * Returns contact details for all the locations of course
	 */
	private function _getContactDetailsForLocations($final_result_array,$courseIds) {
		// contact details query
		$listing_contact_details_query = "select listing_type_id, institute_location_id,contact_details_id,contact_person,contact_email, ".
                "contact_main_phone,contact_cell,contact_alternate_phone,contact_fax,website ".
                "FROM listing_contact_details where listing_type_id IN (?) AND listing_type ='course' ".
                "AND status='live'";
		//error_log('aditya'.$listing_contact_details_query);
		$contact_details_array = $this->db->query($listing_contact_details_query,array($courseIds))->result_array();
		// get contact details for locations
		foreach($contact_details_array as $contact) {
			$contact_details_array_new[$contact['listing_type_id']][$contact['institute_location_id']] = $contact;
		}
		// prepare final result set
		foreach($final_result_array as $row) {
			if($contact_details_array_new[$row['course_id']][$row['institute_location_id']]) {
				$final_result_array_new[] = array_merge($row,$contact_details_array_new[$row['course_id']][$row['institute_location_id']]);
			} else {
				$final_result_array_new[] = $row;
			}

		}
		return $final_result_array_new;
	}
	/*
	 * Returns contact details for all the locations of course
	 *
	 * NOT IN USE
	 */
	private function _getRecentViewCount($courseIds)
	{	return array();
		error_log('Code Usability Check:coursemodel: _getRecentViewCount', 3, '/tmp/listing_server.log'); 
		$sql =  "SELECT SUM(no_Of_Views) as viewCount, listing_id as course_id ".
                "FROM view_Count_Details ".
                "WHERE is_Deleted = 0 ".
				"AND listing_id IN (".implode(',',$courseIds).") ".
				"AND listingType IN ('course_free', 'course_paid') ".
				"GROUP BY listing_id";

		return $this->db->query($sql)->result_array();
	}
	/*
	 * NOT IN USE
	 */
	public function getSpecializationIdsByClientCourse($courseIds = array(),$ldb_course_required = FALSE){
		return array();
		error_log('Code Usability Check:coursemodel: getSpecializationIdsByClientCourse', 3, '/tmp/listing_server.log'); 
		if(empty($courseIds)){
			return array();
		}
		if(!is_array($courseIds)) {
			$courseIds = array($courseIds);
		}
		$data = array();
		$queryCmd = "
					SELECT
					*
					FROM
					tCourseSpecializationMapping as TCSM,
					clientCourseToLDBCourseMapping as CCTLM
					WHERE
					CCTLM.LDBCourseID = TCSM.SpecializationId AND
					CCTLM.clientCourseID IN (". implode("," ,$courseIds) .") AND
					TCSM.SpecializationName not in ('All', 'ALL') AND
					TCSM.ParentId != -1 AND
					CCTLM.status = 'live' AND
					TCSM.status = 'live'
					";
                  		
                if($ldb_course_required) {
                      
			$queryCmd = "
			SELECT
			TCSM.SpecializationId,TCSM.CourseName,CCTLM.clientCourseID,TCSM.ParentId
			FROM
			tCourseSpecializationMapping as TCSM,
			clientCourseToLDBCourseMapping as CCTLM
			WHERE
			CCTLM.LDBCourseID = TCSM.SpecializationId AND
			CCTLM.clientCourseID IN (". implode("," ,$courseIds) .") AND			
			TCSM.ParentId != -1 AND
			CCTLM.status = 'live' AND
			TCSM.status = 'live'			
                        ";
                } 
   
		$query = $this->db->query($queryCmd);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$courseId = $row->clientCourseID;
				$data[$courseId][] = (array)$row;
			}
		}
		return $data;
	}
	
	/*
	 * Returns course list by parent category id
	 * NOT IN USE
	 */
	public function getClientCoursesByParentCategory($parent_ids = array(),$limit='') {
		return array();
		error_log('Code Usability Check:coursemodel: getClientCoursesByParentCategory', 3, '/tmp/listing_server.log'); 
		if(count($parent_ids) == 0) {
			return array();
		}
		
		$append_query = "";
		if(!empty($limit)) {
			$append_query = "LIMIT $limit";
		}
		
		$sql = "SELECT listing_type_id,parentId FROM listing_category_table,categoryBoardTable ".
				"WHERE listing_type='course' and category_id=boardId and ".
				"parentId in (".implode(",", $parent_ids).") $append_query";
		
		$result = array();
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$courseId = $row->listing_type_id;
				$parent_id = $row->parentId;
				$result[$parent_id][] = $courseId;
			}
		}
		
		return $result;
	}
	
	/*
	 * Returns course list by parent category id
	 * NOT IN USE
	 */
	public function getParentCategoryOfCourses($course_ids = array()) {
		return array();
		error_log('Code Usability Check:coursemodel: getParentCategoryOfCourses', 3, '/tmp/listing_server.log'); 
		if(count($course_ids) == 0) {
			return array();
		}

		$sql = "SELECT listing_type_id,parentId FROM listing_category_table,categoryBoardTable ".
				"WHERE listing_type='course' and category_id=boardId and ".
				"listing_type_id in (".implode(",", $course_ids).")";

		$result = array();
		$query = $this->db->query($sql);

		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$courseId = $row->listing_type_id;
				$parent_id = $row->parentId;
				$result[$courseId][] = $parent_id;
			}
		}

		return $result;
	}
	 	/*
	 * Returns ebrochure requested URL for a list of courses
	 * NOT IN USE
	 */
	private function _getEbrochureStatusForCourses($courses) {
		return array();
		error_log('Code Usability Check:coursemodel: _getEbrochureStatusForCourses', 3, '/tmp/listing_server.log'); 
		$sql = "SELECT course_id,course_request_brochure_link ".
			   "FROM course_details ".
			   "WHERE course_id IN(".implode(",", $courses).") AND status='live'";
		
		return $this->db->query($sql)->result_array();
	}
	/*
	 ** NOT IN USE
	 */
	function getCourseReachForCourses($course_ids_array = array()) {
		return array();
		error_log('Code Usability Check:coursemodel: getCourseReachForCourses', 3, '/tmp/listing_server.log'); 
		if(count($course_ids_array) == 0) {
			return array();
		}
		
		$query_to_get_ldb_course_id = "SELECT clientCourseID,CourseReach FROM clientCourseToLDBCourseMapping, ".
					      "tCourseSpecializationMapping WHERE LDBCourseID=SpecializationId AND ".
                                              "clientCourseID IN (".implode(',',$course_ids_array).") AND ".
                                              "clientCourseToLDBCourseMapping.status='live' ".
                                              "AND tCourseSpecializationMapping.status = 'live' GROUP BY clientCourseID";

		error_log('percentage_aditya '.$query_to_get_ldb_course_id);
		
		$query = $this->db->query($query_to_get_ldb_course_id);
		$course_reach_array = array();
		foreach($query->result_array() as $row) {
			$course_reach_array[$row['clientCourseID']] = $row['CourseReach'];
		} 
		return $course_reach_array;
	}

        /*
	 * Get profile compeletion for a list of courses
	 * Returns array
	 * NOT IN USE
	 */
	public function getProfileCompletionForCourses($course_ids_array){
		return array();
		error_log('Code Usability Check:coursemodel: getProfileCompletionForCourses', 3, '/tmp/listing_server.log');
		if(count($course_ids_array) == 0) {

			return array();
		}

		$profileCompletion_list =array();

		$select_query = "SELECT profile_percentage_completion as percentage_completion,course_id ".
				"FROM  course_details ".
				"WHERE course_id in (".implode(",", $course_ids_array).") ".
				"AND status='live'";

		$query = $this->db->query($select_query);
		foreach ($query->result_array() as $res){
			$profileCompletion_list[$res['course_id']] =$res['percentage_completion'];
		}

		return $profileCompletion_list;
	}
	
	public function trackFreeBrochureDownload($userInfo) {		
		$this->db = $this->getWriteHandle();
		$userInfo['session_id'] = isset($userInfo['session_id'])?$userInfo['session_id']:""; 
		
		$data = array(
						'listingType'	=>	'course',
						'listingTypeId'	=>	$userInfo['course_id'],
						'userId'		=>	$userInfo['user_id'],
						'downloadedAt'	=>	date('Y-m-d H:i:s'),
						'downloadedFrom'=>	$userInfo['downloadedFrom'],
						'brochureUrl'	=>	$userInfo['brochureUrl'],
						'sessionId'		=>	$userInfo['session_id']
					 );
		$this->db->insert('listingsBrochureDownloadTracking', $data);
		
		$insert_id = $this->db->insert_id();
		return $insert_id ;
	}
	
	public function trackBrochureEmail($userInfo) {		
		$this->db = $this->getWriteHandle();
		$data = array('listingType'	=> 'course',
			      'listingTypeId'	=> $userInfo['course_id'],
			      'userId'		=> $userInfo['user_id'],
			      'tempLmsId'	=> $userInfo['tempLmsTableId'],
			      'tMailQueueId'	=> $userInfo['tMailQueueId'],
			      'source'          => $userInfo['source'],
                              'createdAt'       => date('Y-m-d H:i:s', time())
			      );
		if($data['tempLmsId'] != NULL && !empty($data['tempLmsId'])){
			$this->db->insert('listingsBrochureEmailTracking', $data);
			$insert_id = $this->db->insert_id();
			return $insert_id ;
		}		
	}
	
	public function updateBrochureEmailTracking($trackInfo) {		
		$this->db = $this->getWriteHandle();
		if($trackInfo['tempLmsTableId'] == 0  || $trackInfo['tempLmsTableId'] == 1)
		{ // when the layer is closed, the last inserted tempLMS id gets lost hence need to find that...
			$sql = "select id  from tempLMSTable where userid = ? and listing_type = 'course' and listing_type_id = ?
				and date(submit_date) >= curdate() - INTERVAL 1 DAY ";
			
			$results = $this->db->query($sql,array($trackInfo['user_id'],$trackInfo['course_id']))->result_array();
			$trackInfo['tempLmsTableId'] = $results[0]['id'];
		}
		$sql = "update listingsBrochureEmailTracking 
			set downloadTrackingId = ?
			where listingTypeId = ?
			and userId = ? 
			and tempLmsId = ?";
		$this->db->query($sql,array($trackInfo['downloadTrackingId'],$trackInfo['course_id'],$trackInfo['user_id'],$trackInfo['tempLmsTableId']));
	}
   	public function updateProfileCompletion($course_id,$profile_completion) {
		if(empty($course_id) || empty($profile_completion)) {
			return false;
		}

		$update_query = "UPDATE course_details ".
				"SET profile_percentage_completion = ? ".
				"WHERE status='live' AND course_id = ?";

		//error_log('theyaquery'.$update_query);
		$this->db = $this->getWriteHandle();
		$this->db->query($update_query,array($profile_completion,$course_id));

	}

	public function updateBatchProfileCompletionCourses($inputArray){
	 if(empty($inputArray)){
	 	return false;
	 }
		$this->db = $this->getWriteHandle();
        $this->db->where(array('status' => 'live'));
        $this->db->update_batch('course_details', $inputArray, 'course_id'); 

        //echo $this->db->last_query();
	}
	
	public function getTotalBrochureDownloads($courseId) {
		if($courseId == "" || $courseId == 0) {
			return ;
		}
		
		$sql = "select count(*) as totalDownloads from  listingsBrochureDownloadTracking where courseId = ?";
		return $this->db->query($sql,array($courseId))->result_array();
	}
    
   public function reportContactNumbers($listing_id,$listing_type,$numbers_array) {

		$this->db = $this->getWriteHandle();
		$data = array();
		$numbers_array = array_unique($numbers_array);
		foreach ($numbers_array as $number) {
			$data[] = array('listing_type'=>$listing_type,'listing_type_id'=>$listing_id,'reported_number'=>$number,'session_id'=>sessionId());
		}	
		
		$this->db->insert_batch('listing_reported_contact_numbers', $data);
		
		return $return_msg = json_encode(array('msg'=>'success'));

	}
	
	/*
	 * Returns course list by parent category id
	 * NOT IN USE
	 */
	
	public function getSubCategoryOfCourses($course_ids = array()) {
		return array();
		error_log('Code Usability Check:coursemodel: getSubCategoryOfCourses', 3, '/tmp/listing_server.log'); 
		if(count($course_ids) == 0) {
			return array();
		}

		$sql = "SELECT distinct listing_type_id,category_id as category_ids FROM listing_category_table ".
				"WHERE listing_type='course' and ".
				"listing_type_id in (".implode(",", $course_ids).") and status='live'";

		$result = array();
		$query = $this->db->query($sql);

		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$courseId = $row->listing_type_id;
				$category_id = $row->category_ids;
				$result[$courseId][] = $category_id;
			}
		}

		return $result;
	}
	
	public function getCoursesByCategoryAndLocation($categoryId,$cityId)
	{
		$sql = "SELECT c.course_id as courseId,c.courseTitle as courseName,c.institute_id as instituteId,i.institute_name as instituteName,l.username,u.displayname as clientName ".
			   "FROM categoryPageData cpd ".
			   "INNER JOIN categoryBoardTable cbt ON cbt.boardId = cpd.category_id ".
			   "INNER JOIN course_details c ON (c.course_id = cpd.course_id AND c.status = 'live') ".
			   "INNER JOIN institute i ON (c.institute_id = i.institute_id AND i.status = 'live') ".
			   "INNER JOIN listings_main l ON (l.listing_type_id = cpd.course_id and l.listing_type = 'course' AND l.status = 'live') ".
			   "INNER JOIN tuser u ON u.userid = l.username ".
			   "WHERE cbt.parentId = ? AND cpd.city_id = ? AND cpd.status = 'live' ".
			   "ORDER BY l.username,c.institute_id,c.course_id";
		$query = $this->db->query($sql,array($categoryId,$cityId));
		$results = $query->result_array();
		$courses = array();
		foreach($results as $result) {
			$courses[$result['courseId']] = $result;
		}
		return $courses;
	}

	public function getPreviousDownloadCount($userId, $courseId, $sessionId )
	{
		$this->db->select('*');
		$this->db->from('listingsBrochureDownloadTracking');
		$this->db->where('listingType','course');
		$this->db->where('listingTypeId',$courseId);
		$this->db->where('userId',$userId);
		$this->db->like('sessionId',$sessionId);
		$data = $this->db->get()->result_array();
		//_p($data);
		
		return count($data);
	}
	
	public function getCoursesForClients($clients = array())
	{
		if(count($clients) == 0) {
			return array();
		}
		$client_course_index_array = array();
		$result = $this->_getCoursesForClients($clients);
		
		foreach ($result as $row) {
			$client_course_index_array[$row['username']] = $row['client_courses_ids'];
		}
		
		return $client_course_index_array;
	}
	/*
	 * NOT IN USE
	 */
	private function _getCoursesForClients($clients)
	{
		return array();
		error_log('Code Usability Check:coursemodel: _getCoursesForClients', 3, '/tmp/listing_server.log'); 
		$sql = "SELECT group_concat(listing_type_id) as client_courses_ids, username FROM listings_main" .
		       " WHERE listing_type = 'course' AND username in (".implode(",", $clients).") AND pack_type in ('1','375') AND status='live'";
		
		return $this->db->query($sql)->result_array();
	}
	/*
	 * NOT IN USE
	 */
	public function getCourseNamesForCourseIds($courseIds)
	{
		return array();
		error_log('Code Usability Check:coursemodel: getCourseNamesForCourseIds', 3, '/tmp/listing_server.log'); 
		$sql = "SELECT course_id, courseTitle FROM course_details WHERE course_id in ($courseIds) AND status='live'";
		
		$results = $this->db->query($sql)->result_array();
		
		$courseNameMapping = array();
		
		foreach($results as $result) {
			$courseNameMapping[$result['course_id']] = $result['courseTitle'];
		}
		
		return $courseNameMapping;
	}
	
	public function add_data($table_name, $data)
	{
		$this->db->insert($table_name,$data);
		return $this->db->insert_id();
	}
	
	public function isStudyAboradListing($listingTypeId = NULL, $listingType = NULL, $tabStatus = 'live') {

		if($tabStatus == 'live') 
			$statusClause = " and ilt.status = 'live' ";
		else if($tabStatus == 'deleted')
			$statusClause = " and ilt.status in ('live','deleted') ";

		if($listingType == 'course') {
			$sql = "SELECT ilt.country_id FROM institute_location_table ilt INNER JOIN course_location_attribute cla ON ilt.institute_location_id = cla.institute_location_id ".
					" WHERE cla.course_id = ? AND cla.attribute_type = 'Head Office' AND cla.attribute_value = 'TRUE' ".
					" AND cla.status = ? ".$statusClause." LIMIT 1";

			$data = $this->db->query($sql,array($listingTypeId,$tabStatus))->row_array();

		}  else {
			$sql = "  SELECT ilt.country_id FROM institute_location_table ilt ".
					" WHERE ilt.institute_id =  ? " . $statusClause . " LIMIT 1";

			$data = $this->db->query($sql,array($listingTypeId))->row_array();
			
		}
		
		
		$studyAbroadListing = FALSE;
		if(!empty($data)) {
			if($data['country_id'] == 2){
				$studyAbroadListing = FALSE;
			} else if($data['country_id'] != 2 && $data['country_id'] > 0) {
				$studyAbroadListing = TRUE;
			}
		}
		return $studyAbroadListing;
	}

	
	public function getDeletedCourseCategory($courseId){
		$sql = "select lct.category_id from listing_category_table lct where lct.listing_type = 'course' and lct.status = 'deleted' and lct.listing_type_id = ? ";
		$results = $this->db->query($sql,array($courseId))->result_array();
		return $results[0]['category_id'];
	}
	
	public function getDeletedCourseInstituteById($courseId){
		$sql = "
			select institute_id from course_details
			where course_id = ? and status='deleted'
		";
		$results = $this->db->query($sql,array($courseId))->result_array();
		return $results[0]['institute_id'];
	}
	
	public function getDeletedCourseLevelById($courseId){
		$sql="select course_level_1 from course_details where status='deleted' and course_id = ?";
		$results = $this->db->query($sql,array($courseId))->result_array();
		return $results[0]['course_level_1'];
	}


   	public function checkIfCourseIdBelongsToAbroad($courseId) {
   		$sql = "SELECT  distinct cd.course_id as course_id FROM institute i INNER JOIN course_details cd on (cd.course_id = ? and i.institute_id = cd.institute_id and i.institute_type IN ('Department','Department_Virtual'))";
		$results = $this->db->query($sql, (int) $courseId)->result_array();
		return $results[0]['course_id'];
		
	 }
	 
	 public function fetchDiffOfValidAbroadCourseIds($courseIds) { 
	 	
	 	if(count($courseIds) > 0) {
	 	$sql = "SELECT distinct course_id FROM institute i INNER JOIN course_details cd on (cd.course_id IN (?) and i.institute_id = cd.institute_id and i.institute_type IN ('Department','Department_Virtual'))";
	    $courseIds = $this->getColumnArray($this->db->query($sql, array($courseIds))->result_array(),'course_id');

	    } else {
	    	$courseIds = array();
	    }
	    return $courseIds;
	 }
	 
	public function getCutOffRanksForCourse($courseId) {
		$sql = "SELECT cpcrm.`categoryName` , cpcrm.`closingRank` , cpcrm.`rankType` , cpcrm.`roundNum` , cbi.branchId, cpc.locId, cpc.exams
				FROM CollegePredictor_CategoryRoundRankMapping cpcrm, CollegePredictor_Colleges cpc, CollegePredictor_BranchInformation cbi
				WHERE cbi.`shikshaCourseId` = ?
				AND cbi.STATUS = 'live'
				AND cpcrm.branchId = cbi.branchId
				AND cpcrm.status = 'live'
				AND cpc.id = cbi.clmId
				AND cpc.status = 'live'";
		$data = $this->db->query($sql, (int) $courseId)->result_array();
		return $data;
	}
	/*
	 * NOT IN USE
	 */
	public function getNationalCourseDetails($courseIds){
		return array();
		error_log('Code Usability Check:coursemodel: getNationalCourseDetails', 3, '/tmp/listing_server.log');
		if(empty($courseIds)){
			return array();
		}

		$sql = "SELECT cd.course_id AS courseId, cd.courseTitle , i.institute_name 
				FROM course_details cd 
				INNER JOIN institute i ON cd.institute_id = i.institute_id
				WHERE cd.status='live' AND i.status='live' AND cd.course_id IN (".implode(',', $courseIds).")";
		return $this->db->query($sql)->result_array();	
	}
	public function getInstituteDetails($allCourseIds,$customFilter)
	{
		if (empty($allCourseIds))
		{
			return array();
		}

		if($customFilter =="primary_id")
		{
			$sql = "select distinct si.name, $customFilter from shiksha_courses sc join shiksha_institutes  si on si.status ='live' and sc.status='live' and sc.course_id  in (?) and sc.primary_id=si.listing_id";
		}
		else
		{	
			$sql = "select distinct si.name, $customFilter from shiksha_courses sc join shiksha_institutes  si on si.status ='live' and sc.status='live' and sc.course_id  in (?) and sc.parent_id=si.listing_id";
		}
		
				return $this->db->query($sql, array($allCourseIds))->result_array();
	}


	public function getLocationFromListingTypeId($listingTypeIds) {

		if (empty($listingTypeIds)){
			return;
		} 
		$sql = "SELECT cla.course_id,ilt.country_id FROM institute_location_table ilt INNER JOIN course_location_attribute cla ON ilt.institute_location_id = cla.institute_location_id  WHERE cla.course_id in (?) AND cla.attribute_type = 'Head Office' AND cla.attribute_value = 'TRUE'  AND cla.status = 'live'  and ilt.status = 'live'";
		$data = $this->db->query($sql,array($listingTypeIds))->result_array(); 
		return $data;

	}
}
