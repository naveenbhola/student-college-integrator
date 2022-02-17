<?php

require_once dirname(__FILE__).'/ListingModelAbstract.php';

class InstituteModel extends ListingModelAbstract
{
	protected $defaultFilters = array('general','locations','header_images','view_count','ranking','media','institute_facilities');

	function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * Get owner info for a list of institutes
	 * Returns array
	 */
	public function getInstitutesOwnerInfo($institutes = array()) {

		if(count($institutes) == 0) {
			return array();
		}
		 
		$result = $this->_getInstitutesOwnerInfo($institutes);
		$institutes_owner_index_array = array();
		foreach ($result as $row) {
			$institutes_owner_index_array[$row['listing_type_id']] = $row;
		}

		return $institutes_owner_index_array;

	}
	
	/*
	 * Get recent viewcount data for a list of institutes
	 * Returns array
	 */
	public function getRecentViewCount($instituteIds)
	{
		if(!is_array($instituteIds)) {
			$instituteIds = array($instituteIds);
		}
		$results = $this->_getViewCount($instituteIds);
		
		if(!is_array($results) || count($results) == 0) {
			return array();
		}
		
		$results_modified = array();
		
		foreach($results as $result) {
				$results_modified[$result['institute_id']] = $result['viewCount'];
		}
		unset($results);
		error_log("#####".print_r($result,true));
		return $results_modified;
	}
	/*
	 * Get getCourses For Institutes 
	 * Returns array of InstituteIds and CourseList
	 */
	public function getCoursesForInstitutes($instituteids = array(), $courseType = 'ALL') {

		if(count($instituteids) == 0) {
			return array();
		}
		// get data from table
		$result = $this->_getCoursesForInstitutes($instituteids, $courseType);
		$result_array = array();
		if(count($result)>0) {
			foreach ($result as $row) 
			{
				$result_array[$row['institute_id']][] = $row['courseid'];
				$course_id_name_array[$row['institute_id']][$row['courseid']] = $row['courseTitle']; 
			}
		} else {
			return array();
		}
	
		$ans =array();
		foreach($result_array as $key =>$value){
			$temp = implode(',',$value);
			$ans[$key] =array('institute_id'=>$key , 'courseList'=>$temp,'course_title_list'=>$course_id_name_array[$key]); 
		}
		return $ans;
	}
	/*
	 * Get profile compeletion for a list of institutes
	 * Returns array
	 */
	public function getProfileCompletionForaListofLisitngs($institute_ids_array){

		if(count($institute_ids_array) == 0) {

			return array();
		}

		$profileCompletion_list =array();

		$select_query = "select profile_percentage_completion as percentage_completion,institute_id from institute where institute_id in (?) ";
		$query = $this->db->query($select_query,array($institute_ids_array));
		foreach ($query->result_array() as $res){
			$profileCompletion_list[$res['institute_id']] =$res['percentage_completion'];
		}
		return $profileCompletion_list;
	}
	/*
	 * Get redirection id for deleted institute
	 * Returns array
	 */
	public function getRedirectionIdForDeletedInstitute($listing_id,$listing_type) {
		// get data from table
		$result = $this->_getRedirectionIdForDeletedInstitute($listing_id,$listing_type);
		if(count($result)>0) {
			return $result[0]['replacement_lisiting_type_id'];
		} else {
			return 0;
		}
	}
	/*
	 * Get viewcount data of institutes
	 * Returns array
	 */
	public function getInstituteViewCount($instituteIds)
	{
		// send array of institute ids
		if(!is_array($instituteIds)) {
			$instituteIds = array($instituteIds);
		}
		$results = $this->_getInstituteViewCount($instituteIds);
		if(!is_array($results) || count($results) == 0) {
			return array();
		}
		return $results;
	}
	/*
	 * Get consolidated data for institute description attributes
	 * Returns array
	 */
	public function getDataForInstituteDescriptionAttributes($instituteId)
	{
		Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');
		$results = $this->_getDataForInstituteDescriptionAttributes($instituteId);
		if(!is_array($results) || count($results) == 0) {
			return array();
		}
		return $results;
	}

	/*
	 * Get consolidated data for Institute join reason
	 * Returns array
	 */
	public function getDataForInstituteJoinReason($instituteId)
	{
		Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');
		$results = $this->_getDataForInstituteJoinReason($instituteId);
		if(!is_array($results) || count($results) == 0) {
			return array();
		}
		return $results[0];
	}
	/*
	 * Get consolidated location wise course id list for an institute
	 * Returns array
	 */
	public function getLocationwiseCourseListForInstitute($instituteId)
	{
		Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');
		$results = $this->_getLocationwiseCourseListForInstitute($instituteId);
		if(!is_array($results) || count($results) == 0) {
			return array();
		}
		foreach ($results as $result) {
			$results_array[$result['institute_location_id']]['courselist'][$result['course_id']] = $result['course_id'];
			$results_array[$result['institute_location_id']]['city_id'] = $result['city_id'];
			$results_array[$result['institute_location_id']]['locality_id'] = $result['locality_id'];
		}
		unset($results);
		return $results_array;
	}
	/*
	 * Get consolidated list of category ids
	 * Returns array
	 */
	public function getCategoryIdsOfListing($listing_id,$listing_type,$indexFlag ='false', $multipleEntries = FALSE)
	{
		//Contract::mustBeNumericValueGreaterThanZero($listing_id,'Institute ID');
		$categorylist = $this->_getCategoryIdsOfListing($listing_id,$listing_type);
		if(!is_array($categorylist) || count($categorylist) == 0) {
			return array();
		}
		foreach ($categorylist as $category) {
			
			if($indexFlag =='true')
			{
				if($multipleEntries){
					$category_array[$category['listing_type_id']][] = $category['category_id'];
				} else {
					$category_array[$category['listing_type_id']] = $category['category_id'];
				}
			}
			else
			{
				$category_array[] = $category['category_id'];
			}
		}
		unset($categorylist);
		return $category_array;
	}
	
	/*
	 * Returns categorylist
	 */
	public function getMainCategoryIdsOfListing($listing_id,$listing_type) {
        
        $clause = "listing_type_id = ".$listing_id;
        
		$sql =  "SELECT DISTINCT cbt.parentId ".
				"FROM listing_category_table lct,categoryBoardTable cbt ".
				"WHERE cbt.boardId = lct.category_id and listing_type_id = ? ".
				"AND listing_type = ? AND status ='live'";
		
		return $this->getColumnArray($this->db->query($sql, array($listing_id, $listing_type))->result_array(),'parentId');
	}
	
	/*
	 * Get consolidated info alumani reviews on institute
	 * Returns array
	 */
	public function getDataForAlumaniReviews($instituteId,$start,$limit)
	{
		Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');
		$alumani_reviews = $this->_getAlumanisReviews($instituteId, $start, $limit);
		if(!is_array($alumani_reviews)) {
			return array();
		}
		return $alumani_reviews;
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

		$start = microtime(TRUE);
		foreach($validFilters as $filter){

			switch($filter) {
				case 'general':
					$data['general'] = $this->_getGeneralInfo($instituteIds);
					break;
				case 'locations':
					$data['locations'] = $this->_getLocations($instituteIds);
					break;
				case 'header_images':
					$data['header_images'] = $this->_getHeaderImages($instituteIds);
					break;
				case 'view_count':
					$data['view_count'] = $this->_getViewCount($instituteIds);
					break;
				case 'ranking':
					$data['ranking'] = $this->_getRanking($instituteIds);
					break;
				case 'media':
					$data['media'] = $this->_getMedia($instituteIds);
					break;
				case 'institute_facilities':
					$data['institute_facilities'] = $this->_getFacilities($instituteIds);
					break;
			}
		}
		$end = microtime(TRUE);
		//error_log("QUERY TIME: AA"." -- ".($end-$start));

		return $data;
	}

	private function _getFacilities($instituteIds){
		$sql = "SELECT i.listing_type_id as institute_id,i.facility_id,ifa.attributeTitle as facilityName,i.description as facilityDescription, ifa.orderId from institute_facilities i INNER JOIN institute_facilities_attributes ifa on ifa.attributeId = i.facility_id where i.listing_type ='institute' and i.status='live' and ifa.status='live' and i.listing_type_id in (".implode(',', $instituteIds).") order by ifa.orderId";

		return $this->db->query($sql,array($instituteIds))->result_array();
	}

	/*
	 * General info includes all the data from `institute` table
	 * Alongwith data from `listings_main` and `institute_mediacount_rating_info`
	 */
	private function _getGeneralInfo($instituteIds)
	{
		
		$sql =  "SELECT i.*,".
				"lm.listing_seo_url,lm.listing_seo_title,lm.listing_seo_description,lm.listing_seo_keywords,lm.viewCount as cumulativeViewCount,lm.pack_type,lm.last_modify_date, ".
                "im.photo_count,im.video_count,im.alumni_rating, im.ratings_json ".
                "FROM institute i ".
                "LEFT JOIN listings_main lm ON (lm.listing_type_id = i.institute_id AND lm.listing_type = 'institute' AND lm.status = 'live') ".
                "LEFT JOIN institute_mediacount_rating_info im ON im.institute_id = i.institute_id ".
                "WHERE i.status = 'live' ".
				"AND i.institute_id IN (?)";
				
		return $this->db->query($sql,array($instituteIds))->result_array();
	}
	/*
	 * Location info includes all the data from `institute_location_table` table
	 * Alongwith data from `listing_contact_details`,countryCityTable,stateTable,localityCityMapping,
	 * tZoneMapping and virtualCityMapping
	 */
	private function _getLocations($instituteIds)
	{
		$sql =  "SELECT loc.institute_location_id,loc.institute_id,loc.address_3, ".
                "loc.address_2,loc.address_1,loc.pincode,loc.locality_name as customLocalityName, ".
                "city.city_name,city.enabled,city.tier,vcm.virtualCityId,city.city_id, ".
                "country.countryId,country.name,country.enabled,country.urlName,".
                "state.state_id,state.state_name,state.enabled,".
                "locality.localityId,locality.localityName,".
                "zone.zoneId,zone.zoneName, ".
                "trm.regionid, ".
                "tregion.regionname ".
                "FROM institute_location_table loc ".
                //"JOIN course_location_attribute cla ".
                "LEFT JOIN countryCityTable city ON city.city_id = loc.city_id ".
                "LEFT JOIN stateTable state ON state.state_id = city.state_id ".
                "LEFT JOIN countryTable country ON country.countryId = loc.country_id ".
                "LEFT JOIN localityCityMapping locality ON locality.localityId = loc.locality_id ".
                "LEFT JOIN tZoneMapping zone ON zone.zoneId = loc.zone ".
				"LEFT JOIN virtualCityMapping vcm ON (vcm.city_id = loc.city_id AND vcm.virtualCityId != loc.city_id) ".
                "LEFT JOIN tregionmapping trm on (loc.country_id = trm.id AND trm.regionmapping = 'country') ".
                "LEFT JOIN tregion on tregion.regionid = trm.regionid ".
                "WHERE loc.status = 'live' ".
                //"AND loc.institute_location_id = cla.institute_location_id ".
                //"AND cla.status = 'live' ".
                //"AND cla.attribute_type = 'Head Office' ".
				"AND loc.institute_id IN (?)";
        
		$result = $this->db->query($sql,array($instituteIds))->result_array();
		// set flagship_course_location
		$result= $this->_getLocationOfFlagshipCourse($result,$instituteIds);
		// add contact details to institutes locations
		$result = $this->_getContactDetailsForLocations($result,$instituteIds);
		return $result;			
	}

	private function _getLocationOfFlagshipCourse($result,$instituteIds) {
		// query to geg flagship course id for a list of institutes
		$sql =   "SELECT cd.course_order,cd.course_id,cd.institute_id from ".
                 "(SELECT cd1.institute_id,min(cd1.course_order) as course_order ".
                 "from course_details cd1 ".
		         "WHERE cd1.institute_id IN (?) and cd1.status='live' ".
		         "group by cd1.institute_id) X ".
                 "JOIN course_details cd ON (cd.institute_id =  X.institute_id and ".
		         "cd.course_order =  X.course_order) ".  
                 "WHERE cd.institute_id  IN (?) and cd.status='live' group by cd.institute_id ";
		//result set to get courses ids
		$sql_order = $this->db->query($sql,array($instituteIds,$instituteIds))->result_array();
		foreach($sql_order as $order) {
			$courses_ids_array[] = $order['course_id'];
		}
		if(count($courses_ids_array) == 0){
			$courses_ids_array[] = 0;
		}
		// get the location info for flagshipcourse
		$sql =    "SELECT cla.`institute_location_id`,cd.institute_id ".
		          "from course_location_attribute cla,course_details cd ".
		          "where cla.`course_id`=cd.`course_id` AND ".
		          "cd.institute_id IN (?) AND cla.status='live' ".
		          "AND cd.status='live' AND cla.attribute_type='Head Office' ".
		          "AND cla.attribute_value='TRUE' AND cla.`course_id` IN (?)";
		// index flagship course result
		$flagship_result = $this->db->query($sql,array($instituteIds,$courses_ids_array))->result_array();
		foreach ($flagship_result as $flagship) {
			$flagship_array[$flagship['institute_location_id']][$flagship['institute_id']] = $flagship['institute_location_id'];
		}
		// unset result set
		unset($flagship_result);
		// finally filter the locations of institute and set whether it's a flagship location or not
		foreach($result as $row) {
			if($flagship_array[$row['institute_location_id']][$row['institute_id']] == $row['institute_location_id']) {
				$row['is_flagship_course_location'] = 'YES';
			} else {
				$row['is_flagship_course_location'] = 'NO';
			}
			$result_array[] = $row;
		}
		// unset temp arrays to free memory
		unset($flagship_result);
		unset($result);
		// return required array
		return $result_array;
	}

	private function _getHeaderImages($instituteIds)
	{
		$sql =  "SELECT * ".
                "FROM header_image ".
                "WHERE status = 'live' ".
                "AND linked ='yes'".
				"AND institute_id IN (?)";

		return $this->db->query($sql,array($instituteIds))->result_array();
	}

	private function _getViewCount($instituteIds)
	{
		$sql =  "SELECT SUM(no_Of_Views) as viewCount, listing_id as institute_id ".
                "FROM view_Count_Details ".
                "WHERE is_Deleted = 0 ".
				"AND listing_id IN (?) ".
				"AND listingType IN ('institute_free', 'institute_paid') ".
				"GROUP BY listing_id";

		return $this->db->query($sql,array($instituteIds))->result_array();
	}

	public function getLastYearViewResponseCountForInstitute($institute_id,$courseIdList) {
		if(!empty($courseIdList))
		{
		$sqlforCourseVisitCount = "SELECT SUM(no_Of_Views) as viewCount ".
				"FROM view_Count_Details ".
				"WHERE DATE_SUB(CURDATE(),INTERVAL 365 DAY) <= view_Date ".
				"AND listing_id IN (?) ".
				"AND listingType IN ('course_free', 'course_paid') ";
	
		$viewdataforCourse = $this->db->query($sqlforCourseVisitCount,array($courseIdList))->result_array();
		}
		if(!empty($institute_id))
		{
		$sqlForInstituteVisitCount =  "SELECT SUM(no_Of_Views) as viewCount ".
				"FROM view_Count_Details ".
				"WHERE DATE_SUB(CURDATE(),INTERVAL 365 DAY) <= view_Date ".
				"AND listing_id =  ? ".
				"AND listingType IN ('institute_free', 'institute_paid') ";
		$viewdataforInstitute = $this->db->query($sqlForInstituteVisitCount, (int) $institute_id)->result_array();
		}
		
		$data[$institute_id]['viewCount'] = $viewdataforCourse[0]['viewCount']+$viewdataforInstitute[0]['viewCount'];
		if(!empty($courseIdList))
		{
		$sqlForCourseResponseCount = " SELECT COUNT(*) as responseCount ".
				"FROM tempLMSTable ".
				"WHERE listing_type = 'course' ".
                                "AND listing_subscription_type='paid' ".
				"AND DATE_SUB(CURDATE(),INTERVAL 365 DAY) <= submit_date ".
				"AND listing_type_id IN (?) ";
		//_p($sqlForCourseResponseCount);
		$responsedataforCourse = $this->db->query($sqlForCourseResponseCount,array($courseIdList))->result_array();
		}
		if(!empty($institute_id))
		{
		$sqlForInstituteResponseCount = " SELECT COUNT(*) as responseCount ".
				"FROM tempLMSTable ".
				"WHERE listing_type = 'institute' ".
                                "AND listing_subscription_type='paid' ".
				"AND DATE_SUB(CURDATE(),INTERVAL 365 DAY) <= submit_date ".
				"AND listing_type_id =  ? ";
		
		//_p($sqlForInstituteResponseCount);
		$responsedataforInstitute = $this->db->query($sqlForInstituteResponseCount, (int) $institute_id)->result_array();
		}
		$data[$institute_id]['responseCount'] = $responsedataforCourse[0]['responseCount']+$responsedataforInstitute[0]['responseCount'];
		
		return $data;
	}
	
	private function _getRanking($instituteIds)
	{
		$sql =  "SELECT loc.institute_id,r.ranking,rs.sourceName,rs.sourceURL ".
				"FROM listingRankings r ".
				"LEFT JOIN institute_location_table loc ON (loc.institute_id = r.listingId AND loc.status = 'live') ".
				"LEFT JOIN listingRankingSources rs ON (rs.rankingType = r.rankingType AND rs.countryId = loc.country_id) ".
				"WHERE r.listingId IN (?) ".
				"AND r.listingType = 'institute' ".
				"AND r.status = 'live'";

		return $this->db->query($sql,array($instituteIds))->result_array();
	}

	private function _getMedia($instituteIds)
	{
		$sql =  "SELECT lm.type_id as institute_id,lm.media_id,lm.media_type,".
				"im.name,im.url,im.thumburl, ".
		        "im.institute_location_id ".
				"FROM listing_media_table lm ".
				"INNER JOIN institute_uploaded_media im ON (im.listing_type_id = lm.type_id AND im.media_id = lm.media_id AND im.listing_type = 'institute' AND im.status = 'notlinked') ".
				"WHERE lm.type = 'institute' ".
				"AND lm.type_id IN (?) ".
				"AND lm.status = 'live'";

		return $this->db->query($sql,array($instituteIds))->result_array();
	}

	/*
	 * Update status to 'deleted' of all expired sticky institutes
	 */
	public function unpublishExpiredStickyInstitutes()
	{
		$this->db = $this->getWriteHandle();
		$today = date('Y-m-d 00:00:00');

		$sql =  "UPDATE tlistingsubscription ".
				"SET status = 'deleted' ".
				"WHERE enddate < ? ".
				"AND status = 'live' ".
				"AND listing_type = 'institute' ";

		$this->db->query($sql,array($today));
	}

	/*
	 * Update status to 'deleted' of all expired main institutes
	 */
	public function unpublishExpiredMainInstitutes()
	{
		$this->db = $this->getWriteHandle();
		$today = date('Y-m-d 00:00:00');

		$sql =  "UPDATE PageCollegeDb ".
				"SET status = 'deleted' ".
				"WHERE EndDate < ? ".
				"AND status = 'live' ".
				"AND listing_type = 'institute' ";

		$this->db->query($sql,array($today));
	}
	/*
	 * Returns alumani reviews
	 */
	private function _getAlumanisReviews($instituteId,$start,$limit)
	{
		$limit_clause = '';
		if($limit>0) {
			$limit_clause = "LIMIT $start, $limit";
		}
                //$orderByClause = 'ORDER BY '. implode(',',$sort);
		$removeEmptyEntries = "AND (tfr.institute_id, tfr.email, tfr.criteria_id) NOT IN (select tfrA.institute_id,tfrA.email,tfrA.criteria_id from talumnus_feedback_rating AS tfrA where tfrA.criteria_rating=0 AND tfrA.criteria_desc='') ";
		if($instituteId>0)
	  		$excludeCourseList = 'AND (tfr.institute_id, tfr.email, tfr.criteria_id) NOT IN (select tecm.institute_id,tecm.email,tecm.criteria_id from talumnus_feedback_rating as tecm where FIND_IN_SET( '. $this->db->escape($instituteId) .', tecm.excluded_course_id ))';
		else
	  		$excludeCourseList = '';
	 
		$sql = 'SELECT tad.*, tfc.criteria_id, tfc.criteria_name, tfr.criteria_rating, tfr.criteria_desc,tfr.status, tfr.thread_id FROM talumnus_details AS tad INNER JOIN  talumnus_feedback_rating AS tfr ON (tfr.institute_id = tad.institute_id AND tfr.email = tad.email) INNER JOIN talumnus_feedback_criteria AS tfc ON tfc.criteria_id = tfr.criteria_id WHERE tad.institute_id='. $this->db->escape($instituteId) .' AND tfr.status ="published" '.$removeEmptyEntries.' '.$excludeCourseList.' GROUP BY tad.email,tad.institute_id,tfr.criteria_id ORDER BY criteria_id,course_comp_year desc '.$limit_clause;
		/*$sql =  "SELECT tad.*, tfc.criteria_id, tfc.criteria_name, tfr.criteria_rating,".
				"tfr.criteria_desc,tfr.status, tfr.thread_id ".
				"FROM talumnus_details AS tad ".
				"INNER JOIN talumnus_feedback_rating AS tfr ON (tfr.institute_id = tad.institute_id AND tfr.email = tad.email) ".
				"INNER JOIN talumnus_feedback_criteria AS tfc ON tfc.criteria_id = tfr.criteria_id ".
				"WHERE tad.institute_id=$instituteId AND tfr.status !='discarded' AND ".
				"!(tfr.criteria_rating =0 AND tfr.criteria_desc = '') AND (concat_ws(',', tfr.excluded_course_id) not like '%,$instituteId,%') ".
		         "GROUP BY tad.email, tad.institute_id, tfr.criteria_id ORDER BY criteria_id,course_comp_year desc $limit_clause";*/
                error_log('alumanibug'.$sql);

		return $this->db->query($sql)->result_array();
	}
	
	public function getAlumniReviewsForInstitutes($instituteIds,$start,$limit)
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($instituteIds,'Institute IDs');
		
		$limit_clause = '';
		if($limit>0) {
			$limit_clause = "LIMIT $start, $limit";
		}
        
		$removeEmptyEntries = "AND (tfr.institute_id, tfr.email, tfr.criteria_id) NOT IN (select tfrA.institute_id,tfrA.email,tfrA.criteria_id from talumnus_feedback_rating AS tfrA where tfrA.criteria_rating=0 AND tfrA.criteria_desc='') ";
		
		$sql = 'SELECT tad.*, tfc.criteria_id, tfc.criteria_name, tfr.criteria_rating, tfr.criteria_desc,tfr.status, tfr.thread_id FROM talumnus_details AS tad INNER JOIN  talumnus_feedback_rating AS tfr ON (tfr.institute_id = tad.institute_id AND tfr.email = tad.email) INNER JOIN talumnus_feedback_criteria AS tfc ON tfc.criteria_id = tfr.criteria_id WHERE tad.institute_id IN ('. implode(',',$instituteIds).') AND tfr.status ="published" '.$removeEmptyEntries.' GROUP BY tad.email,tad.institute_id,tfr.criteria_id ORDER BY criteria_id,course_comp_year desc '.$limit_clause;
		
		$results = $this->db->query($sql)->result_array();
		$alumniReviews = array();
		foreach($results as $result) {
			$alumniReviews[$result['institute_id']][] = $result;
		}
		return $alumniReviews;
	}
	
	/*
	 * Returns categorylist
	 */
	private function _getCategoryIdsOfListing($listing_id,$listing_type) {
		$clause=false;
        if(is_array($listing_id)){
	    if(count($listing_id) > 0) {	
	            	$clause = true;
	    }
        }
        else {
				if(!is_numeric($listing_id)){
						error_log(":::BUG::: Listing ID from function call : $listing_id; Situation Handled; Please check flow!");
				}
            	$clause = true;
				$listing_id = array($listing_id);
        }
	if(!$clause) {
		return array();
	}
		$sql =  "SELECT DISTINCT category_id ,listing_type_id ".
				"FROM listing_category_table ".
				"WHERE listing_type_id in (?) ".
				"AND listing_type = ? AND status ='live' order by `listing_category_id` ASC";
		
		return $this->db->query($sql, array($listing_id,$listing_type))->result_array();

	}

        /*
	 * Returns course list location wise
	 */
	private function _getLocationwiseCourseListForInstitute($instituteId) {
		return $this->_getLocationwiseCourseListForInstituteNoJoin($instituteId);
		/*
		$exceptionListForNoJoinQuery = array(33544, 24644);
		if(in_array($instituteId, $exceptionListForNoJoinQuery)){
			return $this->_getLocationwiseCourseListForInstituteNoJoin($instituteId);
		}
		$sql =  "SELECT il.institute_location_id ,city_id,locality_id, cla.course_id ".
				"FROM institute_location_table il, course_location_attribute cla ".
				"WHERE il.institute_id =$instituteId ".
				"AND il.status = 'live' ".
		        "AND il.institute_location_id = cla.institute_location_id ".
		        "AND cla.status = 'live' ".
		        "AND cla.attribute_type = 'Head Office' ";
		return $this->db->query($sql)->result_array();
		*/
	}
	
	private function _getLocationwiseCourseListForInstituteNoJoin($instituteId) {
		$time_start = microtime(true);
		$sql =  "SELECT il.institute_location_id, il.city_id, il.locality_id ".
				"FROM institute_location_table il ".
				"WHERE il.institute_id = ? ".
				"AND il.status = 'live' ";
		
		$instituteLocationDetails = $this->db->query($sql, (int) $instituteId)->result_array();
		
		$instituteLocationIds = array();
		$processedLocationDetails = array();
		foreach($instituteLocationDetails as $detail){
			$instituteLocationIds[] = $detail['institute_location_id'];
			$processedLocationDetails[$detail['institute_location_id']] = $detail; //assuming that institute location id is unique
		}
		$claDetails = array();
		if(!empty($processedLocationDetails) && !empty($instituteLocationIds)) {
			$claSQL = "SELECT cla.course_id, cla.institute_location_id FROM course_location_attribute cla, course_details cd WHERE cla.institute_location_id IN (?) AND cla.status = 'live' AND cla.attribute_type = 'Head Office' AND cd.institute_id = ? and cd.course_id = cla.course_id and cd.status = 'live'";
			$claDetails = $this->db->query($claSQL, array($instituteLocationIds,(int) $instituteId))->result_array();
		}
		$result = array();
		foreach($claDetails as $cd){
			$temp = array();
			$locationDetails 				= $processedLocationDetails[$cd['institute_location_id']];
			$temp['course_id'] 				= $cd['course_id'];
			$temp['institute_location_id'] 	= $cd['institute_location_id'];
			$temp['city_id'] 				= $locationDetails['city_id'];
			$temp['locality_id'] 			= $locationDetails['locality_id'];
			$result[] = $temp;
		}
		$time_end = microtime(true);
		$time = $time_end - $time_start;
		error_log("getLocationwiseCourseListForInstituteNoJoin: MILISEC TIME : " . ( $time * 1000 ));
		return $result;
	}
	
	/*
	 * Returns results for institute join reason object
	 */
	private function _getDataForInstituteJoinReason($id) {
		$sql =  "SELECT institute_id,photo_title,photo_url,details,version ".
				"FROM institute_join_reason WHERE institute_id = ? AND status='live'";
		
		return $this->db->query($sql, (int) $id)->result_array();
	}
	/*
	 * Returns results for institute description attribute
	 */
	private function _getDataForInstituteDescriptionAttributes($id) {
		$sql =  "SELECT distinct la.keyId as attributeId,la.caption as attributeName, ".
				"la.attributeValue as attributeValue,la.isPaid as isPaid,la.version as version ".
				"FROM listing_attributes_table la, listing_fields_table  lf ".
				"WHERE (la.`keyId`= lf.keyId OR la.keyId =-1) AND la.`listing_type`='institute' ".
				"AND la.`status`='live' AND `listing_type_id`=? ORDER BY lf.detailPageOrder";

		return $this->db->query($sql, (int) $id)->result_array();
	}
	/*
	 * Returns results for institute view count
	 */
	private function _getInstituteViewCount($instituteIds) {

		$sql =  "SELECT lm.viewCount,inst.institute_id as id ".
				"FROM listings_main lm,institute inst ".
				"WHERE inst.institute_id IN (".implode(',',$instituteIds).") ".
				"AND lm.status='live' AND inst.status='live' ".
				"AND inst.institute_id = lm.listing_type_id AND lm.listing_type = 'institute' ";

		$result_array = array();
		foreach ($this->db->query($sql)->result_array() as $row) {
			$result_array[$row['id']] = $row;
		}
		return $result_array;
	}
	/*
	 * Returns contact details for all the locations of institute
	 */
	private function _getContactDetailsForLocations($result,$instituteIds) {
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
        
	private function _getRedirectionIdForDeletedInstitute($listing_id,$listing_type) {
		$sql = "SELECT dlmp.replacement_lisiting_type_id ".
				"FROM deleted_listings_mapping_table dlmp,listings_main lm ".
		        "WHERE dlmp.listing_type_id= ? AND dlmp.listing_type= ? AND ".
		        "lm.listing_type_id=dlmp.replacement_lisiting_type_id AND ".
		        "lm.listing_type= ? AND lm.status='live'";
		error_log("aditya".$sql);
		return $this->db->query($sql,array($listing_id,$listing_type,$listing_type))->result_array();
	}

        /*
	 * Returns Total Alumni Feedback Rating count..
	 */
        public function getAlumniFeedbackRatingCount($instituteId) {
		// get data from table
		$result = $this->_getAlumniFeedbackRatingCount($instituteId);
		if(count($result)>0) {
			return $result[0]['totalFeedbacks'];
		} else {
			return 0;
		}
	}

        private function _getAlumniFeedbackRatingCount($instituteId) {
            $sql =  "SELECT count(*) as totalFeedbacks  FROM `talumnus_feedback_rating` WHERE `institute_id` = ? and status = 'published' AND criteria_rating>0 AND criteria_desc!=''";		
            return $this->db->query($sql,array($instituteId))->result_array();
        }

        private function _getCoursesForInstitutes($institutesids, $courseType = 'ALL') {
        	$sqlArgs = array($institutesids);
            switch($courseType) {
                    case 'PAID':
                        $courseTypeClause = ' AND lm.pack_type IN (?) ';
            			array_push($sqlArgs, array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID));
                        break;
                    case 'FREE':
                        $courseTypeClause = ' AND lm.pack_type NOT IN (?) ';
            			array_push($sqlArgs, array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID));
                        break;
                    default :
                        $courseTypeClause = '';
                        break;
            }
            $sql = "SELECT cd.course_id as courseid,cd.institute_id,cd.courseTitle ".
                "FROM course_details cd, listings_main lm WHERE lm.listing_type_id = cd.course_id AND lm.listing_type = 'course' AND lm.status = 'live' AND cd.status = 'live' AND cd.institute_id IN (?) ".$courseTypeClause;
            error_log('course_is_the_order'.$sql); 
            return $this->db->query($sql,$sqlArgs)->result_array();
        }
        
        private function _getInstitutesOwnerInfo($institutes) {
        	 
        	$sql = "SELECT listing_type_id,username,displayname,email,mobile,usergroup ".
        		   "FROM listings_main,tuser ".
        		   "WHERE status='live' AND listing_type='institute' ".
        		   "AND username=userid AND listing_type_id IN(?)";

        	return $this->db->query($sql,array($institutes))->result_array();
        }

        public function updatePopularInstitute($course_ids = array(),$type="") {
		
		if(count($course_ids) == 0 || empty($type)) {
			return "";
		}
		$this->_updatePopularInstitute($course_ids,$type);
	}

	private function _updatePopularInstitute($course_ids,$type) {
		
		$this->db = $this->getWriteHandle();
		if($type == 'course') {
			$sql = "UPDATE  popular_institutes ".
			"SET courseId = NULL where status='live' and courseId in (?)";
		} else if($type == 'institute') {
			$sql = "UPDATE  popular_institutes ".
			"SET STATUS = 'history' where institute_id in (?)";
		}
                error_log('this is the one'.$sql);
		$this->db->query($sql,array($course_ids));
	}
	
	public function reportContactNumbers($listing_id,$listing_type,$numbers_array) {
		
		$this->db = $this->getWriteHandle();
		$data = array();
		error_log('INPUTARR'.print_r($numbers_array,true));
		$numbers_array = array_unique($numbers_array);
		error_log('INPUTARRAFTER'.print_r($numbers_array,true));
		foreach ($numbers_array as $number) {
			$data[] = array('listing_type'=>$listing_type,'listing_type_id'=>$listing_id,'reported_number'=>$number,'session_id'=>sessionId());
		}	
		
		$this->db->insert_batch('listing_reported_contact_numbers', $data);
		
		return $return_msg = json_encode(array('msg'=>'success'));
	}
 
	public function getNaukriSalaryData($instituteId, $numberOfInstitutes = 'single')
	{
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

      public function getNaukriEmployeesData ($instituteId) {       	       			
               $sql = "SELECT * FROM naukri_alumni_stats WHERE institute_id = ?";
	       $results = $this->db->query($sql, array($instituteId))->result_array();
	       return $results;
      }
      
      public function getCountryForDeletedInstitute($institute_id){
		$sql = "select country_id from institute_location_table where institute_id = ? and status = 'deleted'";
		$result = $this->db->query($sql,array($institute_id))->result_array();
		return $result[0]['country_id'];
      }
         
      public function checkIfInstituteIdBelongsToAbroad($instituteId) {
      	//$sql = "SELECT distinct institute_id FROM `abroadCategoryPageData` where institute_id = $instituteId and status = '".ENT_SA_PRE_LIVE_STATUS."'";
      	$sql = "SELECT  distinct `institute_id` as institute_id FROM `institute` WHERE  `institute_id` = ?  and `institute_type` IN ('Department','Department_Virtual')";
		
      	$results = $this->db->query($sql, (int) $instituteId)->result_array();
		
      	return $results[0]['institute_id'];
      	
      }
      
  public function fetchDiffOfValidAbroadInstituteIds($instituteIds) {
      	
      	if(count($instituteIds) > 0) {
      		$sql = "SELECT  distinct institute_id FROM `institute` WHERE  `institute_id` IN (?)  and `institute_type` IN ('Department','Department_Virtual')";
      		
      		//$sql = "SELECT  GROUP_CONCAT(distinct institute_id) as institute_ids FROM `abroadCategoryPageData` where institute_id IN (".implode(',',$instituteIds).") and status = '".ENT_SA_PRE_LIVE_STATUS."'";
      		$instituteIds = $this->getColumnArray($this->db->query($sql,array($instituteIds))->result_array(),'institute_id');
         	} else {
      		$instituteIds = array();
      	} 
      	return $instituteIds;
      } 

      public function getNaukriEmployeesDataForCompare ($instituteId,$count = 10) {
               $sql = "SELECT comp_label,count(*) cc FROM naukri_alumni_stats WHERE institute_id = ? GROUP BY comp_label ORDER BY cc DESC LIMIT ?";
               $results = $this->db->query($sql, array($instituteId,(int)$count))->result_array();
               return $results;
      }

      public function getCoursesInSubCategory ($categoryList, $instituteId) {
	       $sql = "SELECT c.course_id,c.courseTitle,l.category_id FROM course_details c, listing_category_table l WHERE c.institute_id = ? AND c.status = 'live' AND l.listing_type_id = c.course_id AND l.listing_type = 'course' AND l.status = 'live' AND l.category_id IN (?) GROUP BY c.course_id";
		
               $results = $this->db->query($sql, array($instituteId,$categoryList))->result_array();
               return $results;
      }

	function getAverageNaukriSalaryData($range){
		$sql = "select avg(ctc50)  as averageSalary from naukri_salary_data where exp_bucket = ? ";
		$result = $this->db->query($sql,array($range))->result_array();
		return round($result[0]['averageSalary'],2);	
	}

      public function getInstitutesViewCount()
      {
      	$sql =  "SELECT SUM(no_Of_Views) as viewCount, listing_id as institute_id ".
      			"FROM view_Count_Details ".
      			"WHERE is_Deleted = 0 ".
      			"AND listingType IN ('institute_free', 'institute_paid') ".
      			"GROUP BY listing_id";
      
      	$query = $this->db->query($sql);
      	$data 	= array();
      	foreach($query->result_array() as $row){
      		$data[$row['institute_id']] = $row['viewCount'];
      	}
      	return $data;
      }

	/*
	 * function that gets institute_location_id based on the city & locality passed to it
	 * params: array(instituteId, cityId, localityId)
	 */
	public function getInstituteLocationIdByCityLocality($data){
		$sql = "SELECT institute_location_id FROM institute_location_table ".
					"WHERE institute_id = ? AND status = 'live' AND city_id = ? ".
					($data['localityId'] ? "AND locality_id = ?" : "");
					error_log("SRB:QUERY::".$sql."||||| array::::".print_r($data,true));
		if($data['localityId'])
		{
			$query = $this->db->query($sql,array($data['instituteId'],$data['cityId'],$data['localityId']));
		}
		else {
			$query = $this->db->query($sql,array($data['instituteId'],$data['cityId']));
		}
		$row = $query->row_array();
		error_log("SRB's answer:".$row['institute_location_id']);
		return  $row['institute_location_id'];
	}
	public function insertToResponseLocationPref($Ldata) {
		$this->db = $this->getWriteHandle();
		$queryCmd = $this->db->insert_string('responseLocationPref',$Ldata);
		$query = $this->db->query($queryCmd);
		return $query;
	}

	/*
	* Extract only FT MBA courses of an institute
	* @param : institute id
	*/
	public function getCoursesForInstitute($instId){

		$sql = "SELECT cd.course_id as courseid, cd.institute_id, cd.courseTitle 
                FROM course_details cd 
                left JOIN categoryPageData cpd ON cd.course_id  = cpd.course_id 
				WHERE cd.status = 'live' AND cpd.status = 'live' 
				AND cpd.category_id = 23 AND cd.institute_id = ?
                GROUP BY cd.course_id";

        return $this->db->query($sql, $instId)->result_array();
		
	}

	/*
	* Extract locality for a institute through its course id
	* @params : course Id
	*/

	public function getMultilocationsForInstitute($courseId){

		$sql = "SELECT c.course_id, i.city_id, i.city_name, i.locality_id, i.locality_name
				from institute_location_table i
				left join course_location_attribute c on (i.institute_location_id = c.institute_location_id)
				where c.course_id IN (?) and i.status = 'live' and c.status='live'
				group by c.institute_location_id, c.course_id";

		return $this->db->query($sql,array($courseId))->result_array();
	}

	/*
	* Filter Multilocation courses from list of courses
	* @params : courseIds => Array of courseIds
	*/

	function filterMultilocationCourses($courseIds){

		$sql ="select course_id from course_location_attribute 
				where course_id
				IN (?) 
				AND status = 'live' 
				AND attribute_value = 'FALSE' 
				AND attribute_type =  'Head Office' 
				group by course_id";

		return $this->db->query($sql,array($courseIds))->result_array();
	}

	/*
	* Extract locality for a institute through its course id
	* @params : course Id
	*/

	public function getMultilocationsForSingleInstitute($courseId){

		$sql = "SELECT c.course_id, i.city_id, i.city_name, i.locality_id, i.locality_name
				from institute_location_table i
				left join course_location_attribute c on (i.institute_location_id = c.institute_location_id)
				where c.course_id = ? and i.status = 'live' and c.status='live'
				AND c.attribute_type =  'Head Office'
				group by c.institute_location_id, c.course_id";

		return $this->db->query($sql, array($courseId))->result_array();
	}

	public function getDeletedInstituteDetails($instituteId) {

		$sql =  "SELECT i.*,".
				"lm.listing_seo_url,lm.listing_seo_title,lm.listing_seo_description,lm.listing_seo_keywords,lm.viewCount as cumulativeViewCount,lm.pack_type,lm.last_modify_date, ".
                "im.photo_count,im.video_count,im.alumni_rating, im.ratings_json ".
                "FROM institute i ".
                "LEFT JOIN listings_main lm ON (lm.listing_type_id = i.institute_id AND lm.listing_type = 'institute' AND lm.status = 'deleted') ".
                "LEFT JOIN institute_mediacount_rating_info im ON im.institute_id = i.institute_id ".
                "WHERE i.status = 'deleted' ".
				"AND i.institute_id = ?";
				
		return $this->db->query($sql,array($instituteId))->result_array();

	}


}
