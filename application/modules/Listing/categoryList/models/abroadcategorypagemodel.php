<?php

class abroadcategorypagemodel extends MY_Model
{
    private $locationModel;
	function __construct()
    {
    	parent::__construct('CategoryList');
		$this->db = $this->getReadHandle();
    }
	
	public function init(LocationModel $locationMode = NULL)
    {
		$this->locationModel = $locationMode;
    }
   	
	public function getAbroadCourseForRequest(AbroadCategoryPageRequest $categoryPageRequest) 
	{
		$queryArray = array();

		if($categoryPageRequest->getCityId() > 1){
			$LocationClause = "AND acp1.city_id = ? ";
			$locationParam = $categoryPageRequest->getCityId();
		} else if($categoryPageRequest->getStateId() > 1){
			$LocationClause = "AND acp1.state_id = ? ";
			$locationParam = $categoryPageRequest->getStateId();
		} else if(count($categoryPageRequest->getCountryId()) >0 ){
			if(!in_array(1, $categoryPageRequest->getCountryId())){
			$LocationClause = "AND acp1.country_id IN (?) ";
			$locationParam = $categoryPageRequest->getCountryId();
			}
		}

		global $courseExpandedLevels;
		if ($categoryPageRequest->isLDBCoursePage()) {
			$sql = "SELECT distinct course_id,institute_id,university_id,sub_category_id,pack_type  
					FROM `abroadCategoryPageData` acp1
					where status ='live' 
					AND ldb_course_id = ? "
				    .$LocationClause;
			$queryArray[] = $categoryPageRequest->getLDBCourseId();
			$queryArray[] = $locationParam;
		} else if ($categoryPageRequest->isCategoryCourseLevelPage()) {
			$sql = "SELECT distinct course_id,institute_id,university_id,sub_category_id,pack_type 
					FROM `abroadCategoryPageData` acp1
					where status ='live' 
					AND category_id = ?";
					$queryArray[] = $categoryPageRequest->getCategoryId();
			if(!empty($courseExpandedLevels[strtolower($categoryPageRequest->getCourseLevel())]))
			{
				$sql .=  " AND course_level in (?) ";
				$queryArray[] = $courseExpandedLevels[strtolower($categoryPageRequest->getCourseLevel())];
			}
			else{
				$sql .= " AND course_level = ?";
				$queryArray[] = $categoryPageRequest->getCourseLevel();
			}
			$sql .= $LocationClause;
			$queryArray[] = $locationParam;
		} else if ($categoryPageRequest->isCategorySubCategoryCourseLevelPage()) {
			$sql = "SELECT distinct course_id,institute_id,university_id,sub_category_id,pack_type 
					FROM `abroadCategoryPageData` acp1
					where status ='live' ";
			if(is_array($subcats = $categoryPageRequest->getSubCategoryId())){	
				$sql.= " AND sub_category_id IN (?) ";
				$queryArray[] = $subcats;
			}
			else{
				$sql.= " AND sub_category_id = ?";
				$queryArray[] = $categoryPageRequest->getSubCategoryId();
			}
			if(!empty($courseExpandedLevels[strtolower($categoryPageRequest->getCourseLevel())]))
			{
				$sql .=  " AND course_level in (?) ";
				$queryArray[] = $courseExpandedLevels[strtolower($categoryPageRequest->getCourseLevel())];
			}
			else{
				$sql .= " AND course_level = ?";
				$queryArray[] = $categoryPageRequest->getCourseLevel();
			}
			$sql .= $LocationClause;
			$queryArray[] = $locationParam;				
		}
		else if ($categoryPageRequest->isLDBCourseSubCategoryPage()) {
			$sql = "SELECT distinct course_id,institute_id,university_id,sub_category_id,pack_type 
					FROM `abroadCategoryPageData` acp1
					where status ='live' ";
			if(is_array($subcats = $categoryPageRequest->getSubCategoryId())){	
				$sql.= " AND sub_category_id IN (?) ";
				$queryArray[] = $subcats;
			}
			else{
				$sql.= " AND sub_category_id = ?";
				$queryArray[] = $categoryPageRequest->getSubCategoryId();
			}
			$sql .= " AND ldb_course_id = ? "
					.$LocationClause;
			$queryArray[] = $categoryPageRequest->getLDBCourseId();
			$queryArray[] = $locationParam;			
		}
		if(!empty($sql)) {
                    $data['mainResult'] = $this->db->query($sql,$queryArray)->result_array();
		}
        return $data;
	}
	
	public function getSubCatsForParentCatAndCourseLevel($parentCatId, $courseLevel) {
		global $courseExpandedLevels;
		if( is_string($courseLevel) && !empty($courseExpandedLevels[strtolower($courseLevel)]) ){
			$courseLevel = $courseExpandedLevels[strtolower($courseLevel)];
		}
		if($parentCatId && $courseLevel) {
			$this->db->select("DISTINCT (sub_category_id)",false);
			$this->db->from("abroadCategoryPageData");
			$this->db->where("category_id",$parentCatId);
			$this->db->where("status",'live');
			if(is_array($courseLevel))
			{
				$this->db->where_in("course_level",$courseLevel);
			}
			else{
				$this->db->where("course_level",$courseLevel);
			}
			$result = $this->db->get()->result_array();
		}
		return $result;
	}
        
        public function getSubCatsForParentCatOnly($parentCatId) {
		if($parentCatId) {
			$sql = "SELECT DISTINCT (`boardId`) as sub_category_id, name as sub_category_name
					FROM `categoryBoardTable`
					WHERE `parentId` = ? AND flag = 'studyabroad' AND isOldCategory = '0'";
				
			if(!empty($sql)) {
				$result = $this->db->query($sql,array($parentCatId))->result_array();
			}
		}
		
		return $result;
	}
	
	public function getSubCatsForDesiredCourses($ldbCourseId) {
		if($ldbCourseId == "undefined" || reset($ldbCourseId) == "undefined" || $ldbCourseId == ''){
			return ;
		}
	    if(!is_array($ldbCourseId)) {
		$ldbCourseId = array($ldbCourseId);	
	    }
	    if($ldbCourseId) {
			$this->db->select('DISTINCT (sub_category_id),ldb_course_id',false);
			$this->db->from('abroadCategoryPageData');
			$this->db->where('status','live');
			$this->db->where_in('ldb_course_id',$ldbCourseId);
			$result = $this->db->get()->result_array();
	    }
	    return $result;
	}
	
	public function getCountriesForParentCatAndCourseLevel($parentCatId, $courseLevel, $subcatId) {
		if($parentCatId == '' || $courseLevel == ''){
		    return array();
		}
		global $courseExpandedLevels;
		if( is_string($courseLevel) && !empty($courseExpandedLevels[strtolower($courseLevel)]) ){
			$courseLevel = $courseExpandedLevels[strtolower($courseLevel)];
		}
		
		$this->db->select("DISTINCT (country_id)",false);
		$this->db->from("abroadCategoryPageData");
		$this->db->where("status",'live');
		$this->db->where("category_id",$parentCatId);
		if(is_array($courseLevel))
		{
			$this->db->where_in("course_level",$courseLevel);
		}
		else{
			$this->db->where("course_level",$courseLevel);
		}
		// subcat (if any)
		if($subcatId) {
			if(is_array($subcatId))
			{
				$this->db->where_in("sub_category_id",$subcatId);
			}
			else{
				$this->db->where("sub_category_id",$subcatId);
			}
		}
		// sort by country id
		$this->db->order_by("country_id","asc");

		$result = $this->db->get()->result_array();
		return $result;
	}
	
	public function getCountriesForDesiredCourses($ldbCourseId, $subcatId) {
		if($ldbCourseId == '' || empty($ldbCourseId)){
		    return array();
		}
		if(!empty($subcatId)){
			$this->db->select("DISTINCT (country_id)",false);
			$this->db->from("abroadCategoryPageData");
			$this->db->where(ldb_course_id,$ldbCourseId);
			if(is_array($subcatId)){
				$this->db->where_in("sub_category_id",$subcatId);
			}else{
				$this->db->where("sub_category_id",$subcatId);
			}
			$this->db->where("status","live");
		}else{
			$this->db->select("DISTINCT (country_id)",false);
			$this->db->from("abroadCategoryPageData use index (`ldbCourse_status`)");
			$this->db->where("status","live");
			if(is_array($ldbCourseId)){
				$this->db->where_in("ldb_course_id",$ldbCourseId);
			}else{
				$this->db->where("ldb_course_id",$ldbCourseId);
			}
		}
		// sort by country id
		$this->db->order_by("country_id","asc");
		$result = $this->db->get()->result_array();
		return $result;
	}
 
	public function getCurrencyDetailsForCategoryPage(){
		$sql = " SELECT cer.conversion_factor,cer.source_currency_id  
				FROM currency_exchange_rates cer 
				INNER JOIN currency c on (cer.destination_currency_id = c.id and c.id = 1 and cer.status = 'live')";
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	
    public function getCountryInfo($countryNames) {
	if($countryNames == "") {
	    return ;
	}
	$countryNames = str_replace("holland", "netherlands", $countryNames);
	$countryNamesArr = explode(',',$countryNames);
	$finalCountryName = array();
	foreach ($countryNamesArr as $key => $value) {
		$value = str_replace("'", "", $value);
		$finalCountryName[] = $value;
	}
	$sql = "SELECT `countryId` , `name` , `creationDate` , `continent_id` , `tier`, REPLACE( `name` , ' ', '' ) shortName ".
		" FROM ".ENT_SA_COUNTRY_TABLE_NAME."  WHERE REPLACE( `name`, ' ', '') in (?) ".
		" order by FIELD(REPLACE( `name` , ' ', '' ), ?)";	
	$result = $this->db->query($sql,array($finalCountryName,$finalCountryName))->result_array();
	return $result;
    }
    
    /**
    * Purpose       : Method to get all universities of a country sorted on the basis of last 14 days view count
    * Params        : 1. $paginationArr : Data for pagination
    * 		      2. $countryId  : country id for which data is needed(optional)
    * To Do         : none
    * Author        : Romil Goel
    */
    public function getAbroadCountryPageData( $paginationArr, $countryId = 0 )
    {
	$LimitOffset 	= $paginationArr["limitOffset"];
	$LimitRowCount 	= $paginationArr["limitRowCount"];

	$selectStmt = " select SQL_CALC_FOUND_ROWS 
			uni.university_id as university_id,
			uni.name as university_name,
			case when ISNULL(alvcd.viewCount) then 0 else sum(alvcd.viewCount) end as totalViewCount ";
			
	$whereClause = " uni.status='live' ";
	
	$tableClause = " university uni 
			left join
			abroadListingViewCountDetails alvcd
			on(alvcd.listingId = uni.university_id and alvcd.listingType = 'university' AND alvcd.viewDate between DATE_SUB( CURDATE( ) , INTERVAL 14 DAY ) and CURDATE())
			left join university_location_table ult
			on(uni.university_id = ult.university_id and ult.status = 'live') ";
	
	// cases when data is needed for a particular country
	if(!empty($countryId))
	{
	    $whereClause .= " AND ult.country_id = ?";
	}
	
	$sql = $selectStmt. " FROM ". $tableClause." WHERE ".$whereClause;
	
	$sql .= " group by uni.university_id
		  order by totalViewCount desc, university_name ASC
		  LIMIT ?,?";
	
	$result = $this->db->query($sql,array($countryId,$LimitOffset,$LimitRowCount))->result_array();
	
	$resultArr["result"] = $result;
	
	// fetch the count of total rows fetched
	$query = "SELECT FOUND_ROWS() as TotalCount";
    	$row = $this->db->query($query)->row_array();
    	$resultArr['totalCount'] = $row['TotalCount'];

	return $resultArr;
    }
    
    /**
    * Purpose       : Method to total number of courses university-wise for a particular country
    * Params        : 1. $countryId  : country id for which data is needed(optional)
    * To Do         : none
    * Author        : Romil Goel
    */
    public function getCoursesCountOfUniversity( $countryId = 0 )
    {
		$selectStmt = " SELECT
			univ.university_id as university_id,
			univ.name,
			group_concat(DISTINCT cd.course_id) as courseList
		      ";
			
		$tableStmt = "  university univ
			INNER JOIN institute_university_mapping ium
			on(ium.university_id = univ.university_id AND ium.status = 'live')
			LEFT JOIN course_details cd
			on(ium.institute_id = cd.institute_id and cd.status  = 'live')";
	
		$whereClause = " univ.status = 'live' ";
	
	// cases when country-id is provided
	if(!empty($countryId))
	{
	    $tableStmt .= " INNER JOIN university_location_table ult
			    on(ult.university_id = univ.university_id AND ult.status = univ.status)";
	    
	    $whereClause .= " AND ult.country_id = ?";
	}

	$tailStmt = " group by univ.university_id
		      order by univ.university_id desc ";

	$sqlStmt = $selectStmt." FROM ".$tableStmt." WHERE ".$whereClause.$tailStmt;
	
	if(!empty($countryId))
	{
		$result = $this->db->query($sqlStmt,array($countryId))->result_array();
	}else{
		$result = $this->db->query($sqlStmt)->result_array();
	}
	
	$finalResult = array();
	foreach($result as $res)
	{
	    $finalResult[$res['university_id']] = $res;
	}
	return $finalResult;
    }

   
    public function getStickyListingForAbroad(AbroadCategoryPageRequest $categoryPageRequest) {
        global $studyAbroadPopularCourseToLevelMapping;
    	$queryArray = array();
		if ($categoryPageRequest->getCategoryId () != 1) {
			$categoryClause = " AND categoryid = ?";
			$queryArray[] = $categoryPageRequest->getCategoryId ();
		}else if($categoryPageRequest->getLDBCourseId () != 1) {
			global $studyAbroadPopularCourseToCategoryMapping; // Mapping of popular courses to category
			$categoryClause = " AND categoryid IN (?) ";
			$queryArray[] = $studyAbroadPopularCourseToCategoryMapping[$categoryPageRequest->getLDBCourseId()];
		}else {
			throw new InvalidArgumentException('Invalid request object type abroadcategorypagemodel : getPageKeyForMainUniversities');
		}

		if(!in_array(1, $categoryPageRequest->getCountryId())){
    			$LocationClause = " AND countryid IN (?) "; 
    			$queryArray[] = $categoryPageRequest->getCountryId();
    	}
		// clause to check against course level
		
		if(!is_null($studyAbroadPopularCourseToLevelMapping[$categoryPageRequest->getLDBCourseId()])) {
            $courseLevel = ucfirst($studyAbroadPopularCourseToLevelMapping[$categoryPageRequest->getLDBCourseId()]);
		} else {
		    $courseLevel = ucfirst($categoryPageRequest->getCourseLevel());
		}
	       
		// clause to check against course level
		global $courseExpandedLevels;
		if(!empty($courseExpandedLevels[strtolower($courseLevel)]))
		{
			$courseLevels = array();
			$courseLevels = $courseExpandedLevels[strtolower($courseLevel)];
			$courseLevels[] = 'All';
			$courseLevelClause =     " AND course_level in (?) ";
			$queryArray[] = $courseLevels;
		}
		else{
			$courseLevelClause =     " AND course_level in ('All',?) ";
			$queryArray[] = $courseLevel;
		}
    			$sql = "SELECT DISTINCT group_concat(listing_type_id) as university_id 
    			FROM `tlistingsubscription` 
    			WHERE CURDATE() >= startDate 
    			AND CURDATE() <= endDate ".
			$categoryClause. $LocationClause. $courseLevelClause.
			" AND status = 'live' 
    			  AND listing_type = 'university' ";
			
    			$data = $this->db->query($sql,$queryArray)->result_array();
    			return $data[0]['university_id'];
         }
    
    
    function getMainListingForAbroad(AbroadCategoryPageRequest $categoryPageRequest) {
    	$pageKeylist = $this->getPageKeyForMainUniversities($categoryPageRequest);
    	if(!(is_array($pageKeylist))){
    		$pageKeylist = explode(',', $pageKeylist);
    	}
    	if(count($pageKeylist)==0){
    		$pageKeylist = array(0);
    	}
    	
    	$sql =  "SELECT DISTINCT group_concat(listing_type_id) as university_id ".
    			"FROM `PageCollegeDb` ".
    			"WHERE KeyId IN (?) ".
    			"AND CURDATE() >= StartDate ".
    			"AND CURDATE() <= EndDate ".
    			"AND status = 'live' ".
    			"AND listing_type = 'university' "  ;
    	$data = $this->db->query($sql,array($pageKeylist))->result_array();
    	return $data[0]['university_id'];
    }
    
    
    function getPageKeyForMainUniversities(AbroadCategoryPageRequest $categoryPageRequest)
    {
        $queryArray = array();       
    	if(!in_array(1, $categoryPageRequest->getCountryId())){
    		$LocationClause = " AND countryId IN (?) ";
    		$queryArray[] = $categoryPageRequest->getCountryId();
    	}
    	if ($categoryPageRequest->getCategoryId () != 1) {
    		$categoryClause = " AND categoryid = ?";
    		$queryArray[] = $categoryPageRequest->getCategoryId();
    	} else if($categoryPageRequest->getLDBCourseId () != 1) {
    		global $studyAbroadPopularCourseToCategoryMapping; // Mapping of popular courses to category
    		$categoryClause = " AND categoryid IN (?) ";
    		$queryArray[] = $studyAbroadPopularCourseToCategoryMapping[$categoryPageRequest->getLDBCourseId ()];
    	} else {
    		throw new InvalidArgumentException('Invalid request object type abroadcategorypagemodel : getPageKeyForMainUniversities');
    	}
    	$sql = "SELECT group_concat(keyPageId) as ids
    	FROM `tPageKeyCriteriaMapping`
    	WHERE flag = 'shiksha'
    	$LocationClause
    	$categoryClause
    	";
       	$data = $this->db->query($sql,$queryArray)->result_array();
    	return $data[0]['ids'];
    	 
    }
    
    function abroadCategoryPageFiltersTracking($filterTrackingData)
    {
    	$writeHandle = $this->getWriteHandle();
    	$writeHandle->insert_batch('abroadCategoryPageFiltersTracking',$filterTrackingData);
    }
    
    function getAllSubcategoriesOfSnapshotCourses($extraWhereClause = "")
    {
    	die;
	$query = "SELECT sc.category_id as subCatId,
		   cbt.parentId as category_id,
		   sc.course_type
		   FROM snapshot_courses sc
		   inner join
		   categoryBoardTable cbt
		   on(cbt.boardId = sc.category_id)
		   where sc.status = 'live'
		    ".$extraWhereClause." 
		   group by sc.category_id ";
	$data = $this->db->query($query)->result_array();
	
	$snapshot_subcategories = array();
	foreach($data as $row)
	{
	    $snapshot_subcategories[$row["category_id"]][$row["course_type"]][] = $row["subCatId"];
	}
	return $snapshot_subcategories;
    }
    
    function getAllCountriesOfSnapShotCourses($extraWhereClause = "")
    {
    	die;
	$query = "SELECT sc.category_id,
		    sc.country_id,
		    sc.course_type
		    FROM snapshot_courses sc
		    where sc.status = 'live'
		     ".$extraWhereClause." 
		    group by sc.category_id, sc.country_id ";
	    
	$data = $this->db->query($query)->result_array();
	
	$snapshot_countries = array();
	foreach($data as $row)
	{
	    $snapshot_countries[$row["course_type"]][$row["category_id"]][] = $row["country_id"];
	}
	return $snapshot_countries;
    }
    
    function getCoursesCountriesLDBCourseWise($ldbCourse)
    {
	$query = " SELECT country_id, sub_category_id
		    FROM  `abroadCategoryPageData` 
		    WHERE `ldb_course_id` = ?
		    AND STATUS = 'live'  
		    group by country_id, sub_category_id";
		    
	$data = $this->db->query($query,array($ldbCourse))->result_array();
	
	$countries_ldbCoursewise = array();
	foreach($data as $row)
	{
	    $countries_ldbCoursewise[$row["sub_category_id"]][] = $row["country_id"];
	}
	return $countries_ldbCoursewise;
		    
    }
    
    function getCoursesCountriesSubCatAndCourseLevelWise()
    {
    	$query = "SELECT country_id,
		  category_id,
		  sub_category_id,
		  course_level
		  FROM `abroadCategoryPageData`
		  WHERE STATUS = 'live'
		  group by country_id, category_id, sub_category_id, course_level";

	$data = $this->db->query($query)->result_array();
	global $certificateDiplomaLevels ;
	$countries_subcatAndCourselevelWise = array();
	foreach($data as $row)
	{
		$level = (in_array($row["course_level"],$certificateDiplomaLevels)!== false?'Certificate - Diploma':$row["course_level"]);
	    $countries_subcatAndCourselevelWise[$row["category_id"]][$level][$row["sub_category_id"]][] = $row["country_id"];
	}
	return $countries_subcatAndCourselevelWise;
    }
    
    function getAbroadCoursesSubCategories()
    {
	$query = " SELECT sub_category_id,
		    course_level,
		    category_id
                    FROM `abroadCategoryPageData`
                    WHERE STATUS = 'live'
		    group by category_id, sub_category_id, course_level";

	$data = $this->db->query($query)->result_array();
	
	$subcats_catAndLevelWise = array();
	foreach($data as $row)
	{
	    $subcats_catAndLevelWise[$row["category_id"]][$row["course_level"]][] = $row["sub_category_id"];
	}
	return $subcats_catAndLevelWise;
		    
    }
    
    function getAllCountriesOfSnapShotCoursesCatAndLevelWise($extraWhereClause = "")
    {
    	die;
	$query = "SELECT 
		   cbt.parentId as category_id,
		   sc.course_type,
		   sc.country_id
		   FROM snapshot_courses sc
		   inner join
		   categoryBoardTable cbt
		   on(cbt.boardId = sc.category_id)
		   where sc.status = 'live'
		    ".$extraWhereClause." 
		   group by cbt.parentId, sc.country_id, sc.course_type";
	$data = $this->db->query($query)->result_array();
	
	$snapshot_countries = array();
	foreach($data as $row)
	{
	    $snapshot_countries[$row["category_id"]][$row["course_type"]][] = $row["country_id"];
	}
	return $snapshot_countries;
    }
    
    function getAllCoursesForUniversities($universityIds = array()){
	
	$universityIds =  (array)$universityIds;
	$whereClause = 1;
	$queryArray = array();
	if(!empty($universityIds)){
	    $whereClause = "university_id IN (?) ";  
	    $queryArray[] = $universityIds;  
	}
	$sql = "SELECT  `university_id`,group_concat(distinct `course_id`) as courseList ";
	$sql .= "FROM `abroadCategoryPageData` WHERE status = 'live' and ".$whereClause." group by `university_id`";
	$result = $this->db->query($sql,$queryArray)->result_array();
	return $result;
    }
    
    public function getCountriesHavingUniversities()
    {
	$sql = "select 
		    ult.country_id,
		    ct.name as name,
		    count(ult.country_id) as university_count
		from
		    university_location_table ult,
		    countryTable ct
		where
		    ult.status = 'live' and ult.country_id = ct.countryId
		group by ult.country_id
		order by university_count desc
		; ";
	$result = $this->db->query($sql)->result_array();
	return $result;
    }
	
	public function getPopularCourseCountForCountry($countryId = 1, $ldbCourseIds){
		//$this->initiatemodel('read');
		$this->db->select("ldb_course_id, count(distinct university_id) as value");
		if($countryId > 1) {
			$this->db->where("country_id",$countryId);
	                $this->db->from("abroadCategoryPageData");
                }
                else{
                    $this->db->from("abroadCategoryPageData use index (`ldbCourse_status`)");
                }
                
		$this->db->where_in("ldb_course_id",$ldbCourseIds);
		$this->db->where("status","live");
		$this->db->group_by("ldb_course_id");
		$result = $this->db->get()->result_array();
		$counts = array();
		foreach($result as $row){
			$counts[$row['ldb_course_id']] = $row['value'];
		}
		return $counts;
	}
	
	public function getLDBCourseCountsForCountries($countryIdArray,$ldbCourseIds,$examId = ''){
        if(empty($ldbCourseIds)) {
            return array();
        }
		$this->db->select("acpd.ldb_course_id as ldb_course_id, acpd.country_id as country_id, count(distinct university_id) as value");
		$this->db->from("abroadCategoryPageData acpd");
		$this->db->where_in("acpd.country_id",$countryIdArray);
		$this->db->where_in("acpd.ldb_course_id",$ldbCourseIds);
		$this->db->where("acpd.status","live");
		$this->db->group_by("acpd.country_id,acpd.ldb_course_id");
		if($examId != ''){
			$this->db->join("listingExamAbroad lea","lea.listing_type_id = acpd.course_id","INNER");
			$this->db->where("lea.status","live");
			$this->db->where("lea.listing_type","course");
			$this->db->where("lea.examId",$examId);
		}
		$data = $this->db->get()->result_array();
		$result = array();
		foreach($data as $row){
			$result[$row['country_id']][$row['ldb_course_id']] = $row['value'];
		}
		return $result;
	}
	
	public function getFeaturedCollegesData($countryId){
		$this->db->select("distinct(listing_type_id)");
		$this->db->where("countryid",$countryId);
		$this->db->where("listing_type","university");
		$this->db->where("status","live");
		$this->db->where("enddate > now()");
		$result = $this->db->get("tlistingsubscription")->result_array();
		$returnVal = array();
		foreach($result as $row){
		   $returnVal[] = $row['listing_type_id'];
		}
		return $returnVal;
    }
	
	public function getCountryPageCourseDataForUniversities($countryId, $queryData = array()){
		$this->db->select("distinct(acpd.course_id),acpd.university_id");
		if($countryId >2){
		    $this->db->where("acpd.country_id",$countryId);
		}
		$this->db->where("acpd.status","live");
		if(count($queryData)>0 && $queryData['ldbCourseId']>0)
		{
			$this->db->where('acpd.ldb_course_id',$queryData['ldbCourseId']);
		}
		$result = $this->db->get("abroadCategoryPageData acpd")->result_array();
		//_p($this->db->last_query());die;
		return $result;
	}
	
	public function getCounsellorRatingComments($courseIds,$userId){
		/*
		$this->db->select('activityLogId, courseId');
		$this->db->from('rmcUpdatesForUsers');
		$this->db->where('userId',$userId);
		$this->db->where_in('courseId',$courseIds);
		$updateData = $this->db->get()->result_array();
		$logIds = array_map(function($ele){return $ele['activityLogId'];},$updateData);

		$this->db->select("message, id");
		$this->db->from("rmcActivityLogForUsers");
		$this->db->where_in('id',$logIds);
		$activityLogData = $this->db->get()->result_array();
		$finalArray = array();
		foreach($updateData as $updateRow){
			foreach($activityLogData as $activityRow){
				if($updateRow['activityLogId'] = $activityRow['id']){
					$finalArray[$activityRow['id']] = array('message'=>$activityRow['message'],'courseId'=>$updateData['courseId']);
				}
			}
		}
		ksort($finalArray);
		return $finalArray;
		*/


		$this->db->select("ral.message, ru.courseId");
		$this->db->from("rmcUpdatesForUsers ru");
		$this->db->join("rmcActivityLogForUsers ral","ru.activityLogId = ral.id","inner");
		$this->db->where("ru.userId",$userId);
		$this->db->where_in("ru.courseId",$courseIds);
		// $this->db->order_by("ru.id asc"); This is unneeded.
		return $this->db->get()->result_array();
	}

	public function getCountOfScholarshipUniversitiesByCountryIdDesiredCourse($countryId,$ldbCourseId){
		$this->db->select("count(distinct acpd.university_id)",false);
		$this->db->from("abroadCategoryPageData acpd");
		$this->db->join("listing_external_links lel","lel.listing_type='course' and lel.listing_type_id = acpd.course_id and lel.status = 'live'","inner");
		$this->db->where("acpd.status","live");
		$this->db->where_in("lel.link_type",array('scholarshipLinkCourseLevel' , 'scholarshipLinkUniversityLevel','scholarshipLinkDeptLevel'));
		$this->db->where("acpd.country_id",$countryId);
		$this->db->where("acpd.ldb_course_id",$ldbCourseId);
		$res = reset(reset($this->db->get()->result_array()));
		return $res;
	}

	public function getCountOfPublicUniversitiesByCountryIdDesiredCourse($countryId,$ldbCourseId){
		$this->db->select("count(distinct acpd.university_id)",false);
		$this->db->from("abroadCategoryPageData acpd");
		$this->db->join("university u","u.university_id = acpd.university_id and u.status = 'live'","inner");
		$this->db->where("acpd.status","live");
		$this->db->where("u.type_of_institute","public");
		$this->db->where("acpd.ldb_course_id",$ldbCourseId);
		$this->db->where("acpd.country_id",$countryId);
		$result = reset(reset($this->db->get()->result_array()));
		return $result;
	}
	/*
	 * function to get category Id [parent as well as subcategory id ] from name
	 * @params: name, isParent
	 */
	public function getCategoryIdByName($name, $isParent = false)
	{
		$this->db->select("boardId");
		$this->db->from("categoryBoardTable");
		$this->db->where("flag","studyabroad");
		$this->db->where("isOldCategory='0'","",false);
		if(!$isParent)
		{
			$this->db->where("parentId>1","",false);
		}
                else{
                    $this->db->where("parentId=1","",false);
                }
		$this->db->where(" replace( replace( replace( replace(name,',','') ,' &','' ), ')',''),'(','') = '$name'","",false);
		$result = $this->db->get()->result_array();//echo $this->db->last_query();
		return $result[0]['boardId'];
	}

	public function getUniversityIdForSiteMap(){
		$this->db->select('distinct(ct.countryId) as countryId');
		$this->db->from('countryTable ct');
		$this->db->join('abroadCategoryPageData acpd','ct.countryId = acpd.country_id','inner');
		$this->db->where('acpd.status','live');
		$result = $this->db->get()->result_array();
		return $result;
	}
}
