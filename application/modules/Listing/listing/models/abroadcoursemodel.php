<?php

require_once dirname(__FILE__).'/ListingModelAbstract.php';

class AbroadCourseModel extends ListingModelAbstract
{
	protected $defaultFilters = array('general','attributes','recruiting_companies','class_profile','job_profile','exams','locations','application_details','rmccounsellor_details','scholarship_details','customScholarship_details');


	function __construct()
	{
		parent::__construct();
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
	public function getDataForMultipleCourses($courseIds,$filters = '')
	{
		Contract::mustBeNonEmptyArrayOfIntegerValues($courseIds,'Course IDs');

		$data = $this->_getFilterwiseConsolidatedData($courseIds,$filters);
		return $this->indexFilteredDataByListingIds($data,'course_id',array('general','ease_of_immigration'));
	}

	private function _getFilterwiseConsolidatedData($courseIds,$filters = '')
	{
		$validFilters = $this->validateFilters($filters);

		$data = array();

		foreach($validFilters as $filter){
			switch($filter) {
				case 'general':
					$data['general'] = $this->_getGeneralInfo($courseIds);
					break;
				case 'attributes':
					$data['attributes'] = $this->_getAttributes($courseIds);
					break;
				case 'exams':
					$data['exams'] = $this->_getExams($courseIds);
					break;
				case 'recruiting_companies':
					$data['recruiting_companies'] = $this->_getRecruitingCompanies($courseIds);
					break;
				case 'class_profile':
					$data['class_profile'] = $this->_getClassProfile($courseIds);
					break;
				case 'job_profile':
					$data['job_profile'] = $this->_getJobProfile($courseIds);
					break;
				case 'locations':
					$data['locations'] = $this->_getLocations($courseIds);
					break;
				case 'application_details':
					$data['application_details'] = $this->_getCourseApplicationDetails($courseIds);
					break;
				case 'rmccounsellor_details':
					$data['rmccounsellor_details'] = $this->_getRmcCounsellorDetails($courseIds);
					break;
				case 'scholarship_details':
					$data['scholarship_details'] = $this->_getCourseScholarshipDetails($courseIds);
					break;
				case 'customScholarship_details':
					$data['customScholarship_details'] = $this->_getCourseCustomScholarshipDetails($courseIds);
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
		$sql =  "SELECT  cd.*,".
				"currency.id as currency_id, currency.currency_code, currency.currency_name, currency.country_id as currency_country_id,".
				"lm.pack_type,lm.listing_seo_url,lm.listing_seo_title,lm.listing_seo_description,lm.listing_seo_keywords,".
				"lm.last_modify_date,lm.expiry_date,lm.username,lm.viewCount as cumulativeViewCount,".
				"i.institute_name,".
				"uni.university_id,uni.name as university_name,uni.type_of_institute2 as university_type,".
				"city.city_id,city.city_name,".
				"country.countryId as country_id,country.name as country_name, ".
				"lct.category_id as courseSubCategory ".
				"FROM course_details cd ".
				"LEFT JOIN listings_main lm ON (lm.listing_type_id = cd.course_id AND lm.listing_type = 'course' AND lm.status = 'live') ".
				"LEFT JOIN institute i ON (i.institute_id = cd.institute_id AND i.status in( 'live','dummydept')) ".
				"LEFT JOIN institute_location_table loc ON (loc.institute_id = cd.institute_id AND loc.status = 'live') ".
				"LEFT JOIN countryCityTable city ON (city.city_id = loc.city_id) ".
				"LEFT JOIN countryTable country ON (country.countryId = loc.country_id) ".
				"LEFT JOIN institute_university_mapping unimp ON (unimp.institute_id = i.institute_id AND unimp.status = 'live') ".
				"LEFT JOIN university uni ON (uni.university_id = unimp.university_id AND uni.status = 'live') ".
				"LEFT JOIN currency ON (currency.id = cd.fees_unit) ".
				"LEFT JOIN listing_category_table lct ON (lct.listing_type_id = cd.course_id AND lct.listing_type='course' AND lct.status='live')".
				"WHERE cd.status = 'live' ".
				"AND cd.course_id IN (?)";
		$results = $this->db->query($sql, array($courseIds))->result_array();
                
		/**
		 * External links
		 */ 
		$sql = "SELECT listing_type_id as course_id, link_type, link ".
                "FROM listing_external_links ".
                "WHERE status = 'live' ".
				"AND listing_type = 'course' AND listing_type_id IN (?)";

		$externalLinkResults = $this->db->query($sql, array($courseIds))->result_array();
		
		$externalLinks = array();
		foreach($externalLinkResults as $result) {
			$externalLinks[$result['course_id']][$result['link_type']] = $result['link'];
		}
		
		/**
		 * Attributes
		 */
		$sql = "SELECT listing_type_id as course_id, caption, attributeValue ".
                "FROM listing_attributes_table ".
                "WHERE status = 'live' ".
				"AND listing_type = 'course' AND listing_type_id IN (?)";

		$attributeResults = $this->db->query($sql, array($courseIds))->result_array();
                
		$attributes = array();
		foreach($attributeResults as $result) {
			$attributeKey = '';
			switch($result['caption']) {
				case 'Course Description':
					$attributeKey = 'course_description';
					break;
			}
			$attributes[$result['course_id']][$attributeKey] = $result['attributeValue'];
		}
		
		/**
		 * Start dates
		 */ 
		$sql = "SELECT course_id, start_date_month ".
                "FROM course_start_date_info ".
                "WHERE status = 'live' ".
				"AND course_id IN (?)";

		$startDateResults = $this->db->query($sql, array($courseIds))->result_array();
		
		$startDates = array();
		foreach($startDateResults as $result) {
			$startDates[$result['course_id']][] = $result['start_date_month'];
		}
		
		/**
		 * Desired course and LDB Course ID of the client courses..
		 */ 
		$sql = "SELECT clientCourseID as course_id, LDBCourseID ".
                "FROM clientCourseToLDBCourseMapping ".
                "WHERE status = 'live' ".
				"AND clientCourseID IN (?)";

		$ldbCourseResults = $this->db->query($sql, array($courseIds))->result_array();
    	global $studyAbroadPopularCourseToCategoryMapping;
        $desiredCourseArray = array_keys($studyAbroadPopularCourseToCategoryMapping);   
		foreach($ldbCourseResults as $result) {			
			if(in_array($result['LDBCourseID'], $desiredCourseArray)) {
				$externalLinks[$result['course_id']]['desiredCourseId'] = $result['LDBCourseID'];
			} else {
				$externalLinks[$result['course_id']]['ldbCourseId'] = $result['LDBCourseID'];
			}
		}
		
		/*
		 * Custom fees value
		 */
		$customFeildSql = "SELECT course_id,caption,value FROM `abroad_course_custom_values_mapping` "
				."WHERE `course_id` IN (?) "
				."AND `valueType` = 'fees' AND `status` = 'live' ";
		
                $customFeildResult = $this->db->query($customFeildSql, array($courseIds))->result_array();
                
                $customFeildFees = array();
		foreach($customFeildResult as $customFeildResultCell){
			if($customFeildResultCell['value'] > 0){
				$customFeildFees[$customFeildResultCell['course_id']][] = array('caption'	=>$customFeildResultCell['caption'],
												'value'		=>$customFeildResultCell['value']
											);
			}
		}
		
		/**
		 * Add external links and attributes to original result set for general info
		 */
		for($i=0;$i<count($results);$i++) {
			$result = $results[$i];
			$courseId = $result['course_id'];
			foreach($externalLinks[$courseId] as $linkType => $link) {
				$result[$linkType] = $link;
			}
			foreach($attributes[$courseId] as $caption => $attributeValue) {
				$result[$caption] = $attributeValue;
			}
			foreach($startDates[$courseId] as $startDate) {
				$result['startDates'][] = $startDate;
			}
			
			// Adding custom fees values
			$result['customFees'] = $customFeildFees[$courseId];
			
			$results[$i] = $result;
		}
		return $results;	
	}
        
	private function _getAttributes($courseIds)
	{
		$sql =  "SELECT * ".
				"FROM course_attributes ".
				"WHERE status = 'live' ".
				"AND course_id IN (?)";

		return $this->db->query($sql, array($courseIds))->result_array();
	}
	
	private function _getLocations($courseIds)
	{
		$sql = "SELECT cd.course_id,loc.institute_location_id,loc.institute_id,".//loc.address_3, ".
                /*"loc.address_2,*/"loc.address_1,"./*loc.pincode, ".*/
                "city.city_name,city.enabled,city.tier,city.city_id, ".
                "country.countryId,country.name,country.enabled,country.urlName,".
                "state.state_id,state.state_name,state.enabled,".
                "trm.regionid, ".
                "tregion.regionname ".
		"FROM course_details cd ".	
                "LEFT JOIN institute_location_table loc ON (loc.institute_id = cd.institute_id AND loc.status = 'live')".
                "LEFT JOIN countryCityTable city ON city.city_id = loc.city_id ".
                "LEFT JOIN stateTable state ON state.state_id = city.state_id ".
                "LEFT JOIN countryTable country ON country.countryId = loc.country_id ".
                "LEFT JOIN tregionmapping trm on (loc.country_id = trm.id AND trm.regionmapping = 'country') ".
                "LEFT JOIN tregion on tregion.regionid = trm.regionid ".
                "WHERE cd.status = 'live' ".
		"AND cd.course_id IN (?)";
		
		$result = $this->db->query($sql, array($courseIds))->result_array();
		return $result;			
	}
	/*
	 * function to get course application details for given array of courseIds
	 */
	private function _getCourseApplicationDetails($courseIds)
	{ 
		$this->db->select('id,courseId as course_id');
		$this->db->from('courseApplicationDetails');
		$this->db->where('status','live');
		$this->db->where_in('courseId',$courseIds);
		$result = $this->db->get()->result_array();
		return $result;			
	}

	/*
	 * function to get rmc counsellor mapping details for given array of courseIds
	 */
	private function _getRmcCounsellorDetails($courseIds)
	{ 
			$this->db->select('
			IFNULL(rcum.id,0) as counsellorCount,
			ium.university_id,
			IFNULL(rcum.status,0) as status,
			cd.course_id ',false);

			$this->db->from('course_details cd');
			$this->db->join('institute_university_mapping ium ', 'cd.institute_id = ium.institute_id and cd.status = ium.status and cd.status ="live"','inner');
			$this->db->join('rmcCounsellorUniversityMapping rcum', 'rcum.universityId = ium.university_id and rcum.status = ium.status and rcum.status ="live"','left');
			$this->db->where_in('cd.course_id',$courseIds);
			$this->db->where('cd.status','live');  
	    	$result = $this->db->get()->result_array();
	    	//echo $this->db->last_query();
			//_p($result);
			return $result;			
	}
        
	private function _getExams($courseIds)
	{
	
		$sql =  "SELECT lea.listing_type_id as course_id,lea.examName as examNameOther,lea.examId,lea.cutoff,lea.comments,".
				"mt.exam as examName,mt.examName as examDescription,mt.minScore,mt.maxScore,mt.range,mt.type,mt.priority,mt.listingPriority ".
				"FROM listingExamAbroad lea ".
				"LEFT JOIN abroadListingsExamsMasterTable mt ON mt.examId = lea.examId ".
				"WHERE lea.status = 'live' ".
				"AND lea.listing_type = 'course' ".
				"AND lea.listing_type_id IN (?) order by mt.listingPriority ASC ";
				
		$results = $this->db->query($sql, array($courseIds))->result_array();
                
		for($i=0;$i<count($results);$i++) {
			$exam = $results[$i];
			if($exam['examId'] == '-1') {
				$exam['examName'] = $exam['examNameOther'];
			}
			unset($exam['examNameOther']);
			$results[$i] = $exam;
		}
				
		return $results;
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

		return $this->db->query($sql, array($courseIds))->result_array();
	}
	
	private function _getClassProfile($courseIds)
	{
		$sql =  "SELECT * ".
				"FROM course_class_profile ".
				"WHERE status = 'live' ".
				"AND course_id IN (?) ";

		return $this->db->query($sql, array($courseIds))->result_array();
	}
	
	private function _getJobProfile($courseIds)
	{
		$sql =  "SELECT *, currency.id as currency_id, currency.currency_code, currency.currency_name, currency.country_id as currency_country_id ".
				"FROM course_job_profile LEFT JOIN currency ON course_job_profile.average_salary_currency_id = currency.id ".
				"WHERE status = 'live' ".
				"AND course_id IN (?) ";

		return $this->db->query($sql, array($courseIds))->result_array();
	}
	/*
	 * to get the highest rank of the listing (course/university :: default course)
	 * params : listing type id , listing type (course by deafult)
	 * previusly :: getHighestRankOfCourse
	 */
	public function getHighestRankOfListing($listingTypeId, $listingType = 'course')
	{
		$query = "SELECT sarp.ranking_page_id,
                        sarp.last_modified,
                        sar.rank,
						sar.listing_id
                        FROM study_abroad_ranking_pages sarp
                        inner join
                        study_abroad_rankings sar
                        ON(sarp.ranking_page_id = sar.ranking_page_id)
                        WHERE 1
                        AND sarp.status = 'live'
                        AND sarp.type = ? ";
                if(is_array($listingTypeId)){
                    $query .=" AND sar.listing_id IN (?) ";
		}
		else{
                    $query .=" AND sar.listing_id = ? ";
		}
		$query .=" AND sar.status = 'live'
				ORDER BY sar.rank desc, sarp.last_modified desc ";
		return $this->db->query($query,array($listingType,$listingTypeId))->result_array();
	}
	
	public function getCurrencyExchangeRate($sourceCurrencyId = NULL, $destinationCurrencyId = NULL) {
		$exchangeRate = 0;
		if(!empty($sourceCurrencyId) && !empty($destinationCurrencyId)){
			$sql 			= "SELECT conversion_factor FROM currency_exchange_rates WHERE source_currency_id = ? AND destination_currency_id = ? AND status = 'live' LIMIT 1";
			$result 		= $this->db->query($sql, array($sourceCurrencyId, $destinationCurrencyId));
			if($result->num_rows() > 0){
				$data 			= $result->row_array();
				$exchangeRate   = $data['conversion_factor'];	
			}
		}
		return $exchangeRate;
	}
	
	public function getCurrencyExchangeRateDump() {
		$sql 			= "SELECT * FROM currency_exchange_rates WHERE status = 'live'";
		return $this->db->query($sql)->result_array();
	}
	
	// get all live abroad course ids
	public function getLiveAbroadCourses()
	{
		$sql =  "SELECT DISTINCT course_id ".
				"FROM abroadCategoryPageData ".
				"WHERE status = 'live'";
		
		return $this->getColumnArray($this->db->query($sql)->result_array(),'course_id');
	}
	
	/**
	* Purpose       : Method to get total number of courses university-wise
	* Params        : 1. $universityIds : Array of university ids
	* To Do         : none
	* Author        : Romil Goel
	*/
	public function getCourseCountOfUniversities( $universityIds )
	{
		$selectStmt = " SELECT
				univ.university_id as university_id,
				count(DISTINCT cd.course_id) as course_num";
				
		$tableStmt = "  university univ
				LEFT JOIN institute_university_mapping ium
				on(ium.university_id = univ.university_id AND ium.status = 'live')
				LEFT JOIN course_details cd
				on(ium.institute_id = cd.institute_id and cd.status  = 'live')";
		
		$whereClause = " AND univ.status = 'live'
				 AND univ.university_id IN (?)";
		
		$tailStmt    = " group by univ.university_id
				 order by univ.university_id ";
	
		$sqlStmt = $selectStmt." FROM ".$tableStmt." WHERE 1 ".$whereClause.$tailStmt;
		
		$result = $this->db->query($sqlStmt, array($universityIds))->result_array();
		return $result;
	}
	
	public function checkIfCourseIdBelongsToAbroad($courseId) {
		$this->db = $this->getWriteHandle();
		$sql = "SELECT  distinct cd.course_id as course_id, i.institute_type FROM institute i INNER JOIN course_details cd on (cd.course_id = ? and i.institute_id = cd.institute_id )";
		 
		//$sql = "SELECT distinct course_id FROM `abroadCategoryPageData` where course_id = $couseId and status = '".ENT_SA_PRE_LIVE_STATUS."'";
		$results = $this->db->query($sql,array($courseId))->result_array();
		return $results[0];
	
	}
	
	public function fetchDiffOfValidAbroadCourseIds($courseIds) {
		
		if(count($courseIds) > 0) {
		//	$sql = "SELECT  GROUP_CONCAT(distinct course_id) as courseIds FROM `abroadCategoryPageData` where course_id IN (".implode(',',$courseIds).") and status = '".ENT_SA_PRE_LIVE_STATUS."'";
			$sql = "SELECT  distinct course_id FROM institute i INNER JOIN course_details cd on (cd.course_id IN (?) and i.institute_id = cd.institute_id and i.institute_type IN ('Department','Department_Virtual'))";
			$courseIds = $this->getColumnArray($this->db->query($sql, array($courseIds))->result_array(),'course_id');
		} else {
			$courseIds = array();
		}
		return $courseIds;
	}
	public function getShikshaCourseBrochureUrl($courseId){
		$sql = "select ebrochureUrl from listings_ebrochures where listingType='course' and listingTypeId = ? and status='live'";
		return reset(reset($this->db->query($sql,array($courseId))->result_array()));
		
	}
	
	public function getCourseIdOfUniversities( $universityIds = '')
	{
		$selectStmt = " SELECT
				univ.university_id as university_id,
				cd.course_id ";
				
		$tableStmt = "  university univ
				LEFT JOIN institute_university_mapping ium
				on(ium.university_id = univ.university_id AND ium.status = 'live')
				LEFT JOIN course_details cd
				on(ium.institute_id = cd.institute_id and cd.status  = 'live')";
						
		$whereClause = " univ.status = 'live'
				 AND univ.university_id IN (?)";
		
		$tailStmt    = " order by univ.university_id ";
	
		$sqlStmt = $selectStmt." FROM ".$tableStmt." WHERE ".$whereClause.$tailStmt;
		$result = $this->db->query($sqlStmt, array($universityIds))->result_array();		
		return $result;
	}

	public function getCourseCustomFees($courseId){
		if(empty($courseId)){
			return 0;
		}

		$sql = "SELECT course_id, SUM(value) as customFees 
				FROM abroad_course_custom_values_mapping 
				WHERE course_id = ? 
				AND valueType ='fees' 
				AND status = 'live' 
				GROUP BY course_id";

		$result = $this->db->query($sql,array($courseId))->row_array();
		return $result['customFees'];

	}
	
	/*
	 * function to get course scholarship details for given array of courseIds
	 */
	private function _getCourseScholarshipDetails($courseIds)
	{ 
		$this->db->select('acsm.id,acsm.course_id,acsm.description,acsm.amount,acsm.currency,acsm.eligibility,acsm.deadline,acsm.link,currency.id as currency_id, currency.currency_code, currency.currency_name, currency.country_id as currency_country_id');
		$this->db->from('abroad_course_scholarship_mapping acsm');
		$this->db->where('status','live');
		$this->db->where_in('course_id',$courseIds);
		$this->db->join('currency','currency.id = acsm.currency','left');
		$result = $this->db->get()->result_array();
		return $result;			
	}
	
	private function _getCourseCustomScholarshipDetails($courseIds)
	{ 
		$this->db->select('course_id,caption,value');
		$this->db->from('abroad_course_custom_values_mapping');
		$this->db->where_in('course_id',$courseIds);
		$this->db->where('valueType','scholarship');
		$this->db->where('status','live');
		$customDataresult = $this->db->get()->result_array();
		
		$result = array();
		foreach($customDataresult as $key=>$value)
		{
			$result[$value['course_id']][] = $value;
		}
		return $result;			
	}
	/*
	 * function to get cat,subcat,level,desired course for each course
	 */
	public function getAllCourseCatSubcatLevelDesiredCourse()
	{ 
		global $studyAbroadPopularCourseToCategoryMapping;
        $desiredCourseArray = array_keys($studyAbroadPopularCourseToCategoryMapping);
        $desiredCourseString =    implode(",", $desiredCourseArray);

		$this->db->select('course_id, course_level, category_id, sub_category_id, group_concat(if(ldb_course_id in ('.$desiredCourseString.'),ldb_course_id,0)) as ldb_course_id',false);
		$this->db->from('abroadCategoryPageData');
		$this->db->group_by('course_id');
		$this->db->where('status','live');
		$customDataResult = $this->db->get()->result_array();
		return $customDataResult;			
	}

	function getTestCourseReco($courseId, $detailsCourseIds){

		if(empty($courseId)){
			return 0;
		}

		$sql = "SELECT b.listing_type_id,b.listing_title,b.listing_seo_url from listings_main b where b.listing_type='course' and b.status='live' and b.listing_type_id=? limit 1";

		$courseInfo = $this->db->query($sql,array($courseId))->result_array();

		if(!empty($detailsCourseIds)){
			$sql = "SELECT b.listing_type_id,b.listing_title,b.listing_seo_url from listings_main b where b.listing_type='course' and b.status='live' and b.listing_type_id in (".implode(",", $detailsCourseIds).") ";

			$detailsCourses = $this->db->query($sql,array($courseId))->result_array();
		}

		$sql = "SELECT b.listing_type_id,b.listing_title,b.listing_seo_url from abroadCourseReco a inner join listings_main b on(a.recommendedCourseId=b.listing_type_id and b.listing_type='course' and b.status='live') where courseId=? order by weight desc limit 20";

		$resultReco = $this->db->query($sql,array($courseId))->result_array();
		$result['reco'] = $resultReco;
		$result['courseInfo'] = $courseInfo;
		$result['detailsCourses'] = $detailsCourses;


		return $result;
	}

}
