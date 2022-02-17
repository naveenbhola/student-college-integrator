<?php

class CategoryPageModel extends MY_Model
{
    private $locationModel;
	function __construct()
    {
	$this->config->load('categoryPageConfig');
        parent::__construct('CategoryList');
		$this->db = $this->getReadHandle();
    }
	
	public function init(LocationModel $locationMode = NULL)
    {
    	return;
		$this->locationModel = $locationMode;
    }
	
	public function getFiltersToHide($type,$typeId)
	{
		return;
		$sql =  "SELECT mode,courseLevel ".
				"FROM LDBCourseFilterHide ".
                "WHERE `type` = ? AND typeId = ? AND status = 'live' ";
				
		//return $this->db->query($sql, array($type, $typeId))->row_array();
	}
	
	public function getAllFiltersToHide() {
		return;
		$sql =  "SELECT mode,courseLevel,type,typeId ".
				"FROM LDBCourseFilterHide ".
				"WHERE status = 'live' ";
		
		//$resultData =  $this->db->query($sql)->result_array();
		$filtersToHide = array();
		foreach($resultData as $data) {
			if($data['mode'])
			{	
			 $filtersToHide[$data['type']][$data['typeId']]['mode'] =  array_map('trim',explode(',',$data['mode']));
			}
			if($data['courseLevel'])
			{
			  $filtersToHide[$data['type']][$data['typeId']]['courseLevel'] =  array_map('trim',explode(',',$data['courseLevel']));
			}
		}
		return $filtersToHide;
	}
	
	public function getHeaderText(CategoryPageRequest $request)
	{
		return;
		$pageType = $request->isLDBCoursePage() ? 'ldbcourse' : ($request->isSubcategoryPage() ? 'subcategory' : 'category');
		
		if($request->isLDBCoursePage()) {
			$pageType = 'ldbcourse';
			$typeId = $request->getLDBCourseId();
		}
		if($request->isSubcategoryPage()) {
			$pageType = 'subcategory';
			$typeId = $request->getSubCategoryId();
		}
		if($request->isMainCategoryPage()) {
			$pageType = 'category';
			$typeId = $request->getCategoryId();
		}
		
		if($request->isLocalityPage()) {
			$locationType = 'locality';
			$locationId = $request->getLocalityId();
		}
		else if($request->isZonePage()) {
			$locationType = 'zone';
			$locationId = $request->getZoneId();
		}
		else if($request->isCityPage()) {
			$locationType = 'city';
			$locationId = $request->getCityId();
		}
		else if($request->isStatePage()) {
			$locationType = 'state';
			$locationId = $request->getStateId();
		}
		
		$sql =  "SELECT `text` ".
				"FROM categoryPageHeaderText ".
                "WHERE page_type = ? ".
				"AND type_id = ? ".
				"AND location_id = ? ".
				"AND location_type = ? ".
				"AND status = 'live' ";
		
		//$row = $this->db->query($sql, array($pageType, $typeId, $locationId, $locationType))->row_array();
		return $row['text'];
	}
	
	public function getDynamicLDBCoursesList(CategoryPageRequest $categoryPageRequest)
	{
		return;
		if($categoryPageRequest->isMainCategoryPage() || $categoryPageRequest->isStudyAbroadPage()) {
			return array();
		}

		$city_clause = $this->_getCityClause($categoryPageRequest);
		$sql 		 =  "SELECT DISTINCT `ldb_course_id` ".
						"FROM `categoryPageData` ".
						"WHERE ".$city_clause." ".
						"`country_id` = ? ".
						"AND `category_id` = ? ".
						"AND `status` = 'live' ";

		//$LDBCourses =  $this->db->query($sql, array($categoryPageRequest->getCountryId(), $categoryPageRequest->getSubCategoryId()))->result_array();
		$tempList   = array();

		foreach($LDBCourses as $c){
			$tempList[] = $c['ldb_course_id'];
		}

		array_unique($tempList);

		return $tempList;
	}
	
	public function getDynamicCategoryList(CategoryPageRequest $categoryPageRequest)
	{
		return;
		if(!$categoryPageRequest->isStudyAbroadPage()) {
			$city_clause = $this->_getCityClause($categoryPageRequest);
			$sql =  "SELECT DISTINCT `category_id` ".
					"FROM `categoryPageData` ".
					",`categoryBoardTable` ".
					"WHERE ".$city_clause." ".
					"parentId = ? ".
					"AND `category_id` = boardId ".
					"AND `status` = 'live' ";

		//$LDBCourses =  $this->db->query($sql, array($categoryPageRequest->getCategoryId()))->result_array();
		}
		else{
			if($categoryPageRequest->getCountryId() == 1){
				$sql =  "SELECT DISTINCT `category_id` ".
						"FROM `categoryPageData` ".
						",`categoryBoardTable` ".
						", `tregionmapping` ".
						"WHERE ".
						"parentId = ? ".
						"AND country_id = id ".
						"AND regionid = ? ".
						"AND `category_id` = boardId ".
						"AND `status` = 'live' ";

				//$LDBCourses =  $this->db->query($sql, array($categoryPageRequest->getCategoryId(), $categoryPageRequest->getRegionId()))->result_array();
			}else{
				$sql =  "SELECT DISTINCT `category_id` ".
						"FROM `categoryPageData` ".
						",`categoryBoardTable` ".
						"WHERE ".
						"parentId = ? ".
						"AND country_id = ? ".
						"AND `category_id` = boardId ".
						"AND `status` = 'live' ";

				//$LDBCourses =  $this->db->query($sql, array($categoryPageRequest->getCategoryId(), $categoryPageRequest->getCountryId()))->result_array();
			}
		}
		
		$tempList = array();
		foreach($LDBCourses as $c){
			$tempList[] = $c['category_id'];
		}
		array_unique($tempList);
		return $tempList;
	}

	public function getDynamicLocationList(CategoryPageRequest $categoryPageRequest)
	{
		return; /*
		if($categoryPageRequest->isStudyAbroadPage()) {
			$countryClause = " `country_id` > 2 ";
		}else{
			$countryClause = " `country_id` = ".$categoryPageRequest->getCountryId()." ";
		}
		if($categoryPageRequest->isMainCategoryPage()){
			$sql =  "SELECT DISTINCT `virtualCityId`,`state_id`,`country_id` ".
				"FROM `categoryPageData` c,`categoryBoardTable`,`virtualCityMapping` v ".
				"WHERE ".
				$countryClause.
				"AND parentId = ? ".
				"AND c.city_id = v.city_id ".
				"AND `category_id` = boardId ".
				"AND `status` = 'live' ";

			//$tlists =  $this->db->query($sql, array($categoryPageRequest->getCategoryId()))->result_array();
		}
		elseif($categoryPageRequest->isSubCategoryPage()){
			$sql =  "SELECT DISTINCT `virtualCityId`,`state_id`,`country_id` ".
				"FROM `categoryPageData` c,`virtualCityMapping` v ".
				"WHERE ".
				$countryClause.
				"AND c.city_id = v.city_id ".
				"AND `category_id` = ? ".
				"AND `status` = 'live' ";

			//$tlists =  $this->db->query($sql, array($categoryPageRequest->getSubCategoryId()))->result_array();
		}
		else{
			$sql =  "SELECT DISTINCT `virtualCityId`,`state_id`,`country_id` ".
				"FROM `categoryPageData` c,`virtualCityMapping` v ".
				"WHERE ".
				$countryClause.
				"AND c.city_id = v.city_id ".
				"AND `ldb_course_id` = ? ".
				"AND `status` = 'live' ";

				//$tlists =  $this->db->query($sql, array($categoryPageRequest->getLDBCourseId()))->result_array();
		}
		
		$tempList = array();
		foreach($tlists as $c){	
			$tempList['states'][] = $c['state_id'];
			$tempList['countries'][] = $c['country_id'];
			$tempList['cities'][] = $c['virtualCityId'];
		}
		array_unique($tempList['states']);
		array_unique($tempList['cities']);
		array_unique($tempList['countries']);
		return $tempList; */
	}

	private function _getCityClause($categoryPageRequest)
	{
		return; /*
		$countryId = $categoryPageRequest->getCountryId();
		$cityId = $categoryPageRequest->getCityId();
		$stateId = $categoryPageRequest->getStateId();
		
		if($countryId > 2) {
			return '';
		}
		$city_clause = " ";
		if($cityId > 1){
			$cityList = $this->locationModel->getCitiesForVirtualCity($cityId);
			if(is_array($cityList) && count($cityList) > 0) {
				$city_clause = "city_id in (".implode(',',$cityList).") AND";
			}
			else {
				$city_clause = "city_id in (0) AND";
			}
			
		}
		else if($stateId > 1){
			$city_clause = "state_id = ".$stateId." AND";
		}
		
		return $city_clause; */
	}
	
	public function getCategoryPageParameters($entity,$entityId,$criteria)
	{
		return; /*
		$params = array();
		
		switch($entity) {
			case 'course':
				$params = $this->_getCategoryPageParametersForCourse($entityId,$criteria);
				break;
			case 'banner':
				$params = $this->_getCategoryPageParametersForBanner($entityId,$criteria);
				break;
			case 'sticky':
				$params = $this->_getCategoryPageParametersForStickyInstitutes($entityId,$criteria);
				break;
			case 'main':
				$params = $this->_getCategoryPageParametersForMainInstitutes($entityId,$criteria);
				break;
		}
		
		return (array) $params; */
	}
	
	private function _getCategoryPageParametersForCourse($courseId,$criteria)
	{
		return;
		$criteria = (array) $criteria;
		if(!isset($criteria['status'])) {
			$criteria['status'] = 'live';
		}
		
		$where = array();
		foreach($criteria as $key => $value) {
			$where[] = " cpd.$key  = '".$value."' ";
		}
		
		$sql =  "SELECT cbt.parentId as categoryId, cpd.category_id as subCategoryId, cpd.ldb_course_id as LDBCourseId,".
				"cpd.city_id as cityId, cpd.state_id as stateId,cpd.country_id as countryId,r.regionid as regionId,v.virtualCityId ".
				"FROM categoryPageData cpd ".
				"LEFT JOIN categoryBoardTable cbt ON cbt.boardId = cpd.category_id ".
				"LEFT JOIN tregionmapping r ON r.id = cpd.country_id ".
				"LEFT JOIN virtualCityMapping v ON (v.city_id = cpd.city_id AND v.virtualCityId != cpd.city_id) ".
				"WHERE course_id = ? AND ".implode(' AND ',$where);
		
		//return $this->db->query($sql, array($courseId))->result_array();
	}
	
	/*
	 * Category page parameters for banners
	 */
	
	private function _getCategoryPageParametersForBanner($bannerId,$criteria)
	{
		return;
		$sql =  "SELECT categoryid,subcategoryid,countryid ".
				"FROM tbannerlinks WHERE sno = ? ";
		
		//$row = $this->db->query($sql, array($bannerId))->row_array();		
		
		if($row['subcategoryid']) {
			
			$sql =  "SELECT cb.parentId as categoryId,t.subcategoryid as subCategoryId,L.ldbCourseID as LDBCourseId, ".
					"t.cityid as cityId,t.stateId,t.countryid as countryId,r.regionid as regionId ".
					"FROM tbannerlinks t ".
					"INNER JOIN categoryBoardTable cb ON cb.boardId = t.subcategoryid ".
					"LEFT JOIN LDBCoursesToSubcategoryMapping L ON (L.categoryID = t.subcategoryid AND L.status = 'live') ".
					"LEFT JOIN tregionmapping r ON r.id = t.countryid ".
					"WHERE t.sno = ? ";
					
			//return $this->db->query($sql, array($bannerId))->result_array();		
		}
		else if($row['categoryid']) {

			$flag = $row['countryid'] > 2 ? 'studyabroad' : 'national';
			
			$sql =  "SELECT t.categoryid as categoryId, cb.boardId as subCategoryId, L.ldbCourseID as LDBCourseId, ".
					"t.cityid as cityId, t.stateId, t.countryid as countryId,r.regionid as regionId ".
					"FROM tbannerlinks t ".
					"INNER JOIN categoryBoardTable cb ON (cb.parentId = t.categoryid AND cb.flag =  ?) ".
					"LEFT JOIN LDBCoursesToSubcategoryMapping L ON (L.categoryID = cb.boardId AND L.status =  'live')  ".
					"LEFT JOIN tregionmapping r ON r.id = t.countryid ".
					"WHERE t.sno = ? ";
	
			//return $this->db->query($sql, array($flag, $bannerId))->result_array();		
		}
	}
	
	/*
	 * Category page parameters for sticky institutes
	 */
	
	private function _getCategoryPageParametersForStickyInstitutes($stickyId,$criteria)
	{
		return;
		$sql =  "SELECT categoryid,subcategory,countryid ".
				"FROM tlistingsubscription WHERE listingsubsid = ? ";
		
		//$row = $this->db->query($sql, array($stickyId))->row_array();		
		
		if($row['subcategory']) {
			
			$sql =  "SELECT cb.parentId as categoryId,t.subcategory as subCategoryId,L.ldbCourseID as LDBCourseId, ".
					"t.cityid as cityId,t.stateid as stateId,t.countryid as countryId,r.regionid as regionId ".
					"FROM tlistingsubscription t ".
					"INNER JOIN categoryBoardTable cb ON cb.boardId = t.subcategory ".
					"LEFT JOIN LDBCoursesToSubcategoryMapping L ON (L.categoryID = t.subcategory AND L.status = 'live') ".
					"LEFT JOIN tregionmapping r ON r.id = t.countryid ".
					"WHERE t.listingsubsid = ? ";
			
			//return $this->db->query($sql, array($stickyId))->result_array();		
		}
		else if($row['categoryid']) {
			
			$flag = $row['countryid'] > 2 ? 'studyabroad' : 'national';
			
			$sql =  "SELECT t.categoryid as categoryId, cb.boardId as subCategoryId, L.ldbCourseID as LDBCourseId, ".
					"t.cityid as cityId, t.stateid as stateId, t.countryid as countryId,r.regionid as regionId ".
					"FROM tlistingsubscription t ".
					"INNER JOIN categoryBoardTable cb ON (cb.parentId = t.categoryid AND cb.flag =  ?) ".
					"LEFT JOIN LDBCoursesToSubcategoryMapping L ON (L.categoryID = cb.boardId AND L.status =  'live')  ".
					"LEFT JOIN tregionmapping r ON r.id = t.countryid ".
					"WHERE t.listingsubsid = ? ";
			
			//return $this->db->query($sql, array($flag, $stickyId))->result_array();		
		}
	}
	
	/*
	 * Category page parameters for main institutes
	 */
	private function _getCategoryPageParametersForMainInstitutes($mainId,$criteria)
	{
		return;
		$sql =  "SELECT t.categoryId,t.subCategoryId,t.countryId ".
				"FROM PageCollegeDb p ".
				"INNER JOIN  tPageKeyCriteriaMapping t ON t.keyPageId = p.KeyId ".
				"WHERE p.id = ? ";
		
		//$row = $this->db->query($sql, array($mainId))->row_array();		
		
		if($row['subCategoryId']) {
			
			$sql =  "SELECT cb.parentId as categoryId,t.subCategoryId,L.ldbCourseID as LDBCourseId, ".
					"t.cityId,t.stateId,t.countryId,r.regionid as regionId ".
					"FROM PageCollegeDb p ".
					"INNER JOIN  tPageKeyCriteriaMapping t ON t.keyPageId = p.KeyId ".
					"INNER JOIN categoryBoardTable cb ON cb.boardId = t.subCategoryId ".
					"LEFT JOIN LDBCoursesToSubcategoryMapping L ON (L.categoryID = t.subCategoryId AND L.status = 'live') ".
					"LEFT JOIN tregionmapping r ON r.id = t.countryId ".
					"WHERE p.id = ? ";
					
			//return $this->db->query($sql, array($mainId))->result_array();		
		}
		else if($row['categoryId']) {
			
			$flag = $row['countryId'] > 2 ? 'studyabroad' : 'national';
			
			$sql =  "SELECT t.categoryId, cb.boardId as subCategoryId, L.ldbCourseID as LDBCourseId, ".
					"t.cityId, t.stateId, t.countryId,r.regionid as regionId  ".
					"FROM PageCollegeDb p ".
					"INNER JOIN  tPageKeyCriteriaMapping t ON t.keyPageId = p.KeyId ".
					"INNER JOIN categoryBoardTable cb ON (cb.parentId = t.categoryId AND cb.flag =  ?) ".
					"INNER JOIN LDBCoursesToSubcategoryMapping L ON (L.categoryID = cb.boardId AND L.status =  'live')  ".
					"LEFT JOIN tregionmapping r ON r.id = t.countryId ".
					"WHERE p.id = ? ";
					
			//return $this->db->query($sql, array($flag, $mainId))->result_array();		
		}
	}
	
	public function setCategoryPageDataInCacheMemory($courseIds)
	{	
		return;
		$this->db = $this->getWriteHandle();
		$sql =  "UPDATE categoryPageData ".
				"SET cache_state = 'not_in_memory' ".
				"WHERE course_id IN (".implode(',',$courseIds).") ".
				"AND status != 'live' ";
		
		//$this->db->query($sql);
		
		$sql =  "UPDATE categoryPageData ".
				"SET cache_state = 'in_memory' ".
				"WHERE course_id IN (".implode(',',$courseIds).") ".
				"AND status = 'live' ";
				
		//$this->db->query($sql);
	}
	
	private function _storeCronStats($cronId,$stats)
	{
		return; /*
		$this->db = $this->getWriteHandle();
		if(is_array($stats) && count($stats)) {
			
			$institutes = (isset($stats['institutes']) && is_array($stats['institutes'])) ? implode(',',$stats['institutes']) : '';
			$instituteCourses = (isset($stats['instituteCourses']) && is_array($stats['instituteCourses'])) ? implode(',',$stats['instituteCourses']) : '';
			$courses = (isset($stats['courses']) && is_array($stats['courses'])) ? implode(',',$stats['courses']) : '';
			$banners = (isset($stats['banners']) && is_array($stats['banners'])) ? implode(',',$stats['banners']) : '';
			$sticky = (isset($stats['sticky']) && is_array($stats['sticky'])) ? implode(',',$stats['sticky']) : '';
			$main = (isset($stats['main']) && is_array($stats['main'])) ? implode(',',$stats['main']) : '';
			$numKeys = $stats['numCacheKeys'];
			
			$sql = "INSERT INTO categoryPageCacheCronStats(`cronId`,`institutes`,`instituteCourses`,`courses`,`banners`,`sticky`,`main`,`numKeys`) ".
					"VALUES ('".$cronId."','".$institutes."','".$instituteCourses."','".$courses."','".$banners."','".$sticky."','".$main."','".$numKeys."')";
			
			//$this->db->query($sql);
			
			$cacheKeys = $stats['keys'];
			foreach($cacheKeys as $source => $sourceKeys) {
				foreach($sourceKeys as $sourceId => $keys) {
					foreach($keys as $key) {
						$sql = "INSERT INTO categoryPageCacheKeys (`cronId`,`key`,`source`,`sourceId`) VALUES ('".$cronId."','".$key."','".$source."','".$sourceId."')";
						//$this->db->query($sql);
					}
				}
			}
		}
		*/
	}
	
	public function raiseAlert($type,$data)
	{
		return; /*
		$this->db = $this->getWriteHandle();

		$dataArr = array( "alertType" => $type, 
						  "data"	  => $data,
						  "time"	  => date('Y-m-d H:i:s'));

		$this->db->insert("categoryPageAlerts", $dataArr); */
	}
	
	public function trackFilters($sessionId,$categoryPageRequest,$appliedFilters, $resultCount)
	{
		return; /*
                $disabledAndCheckedFilters = json_decode($this->input->post('disabledAndCheckedFilters'),true);
                $trackingData = array();
		$data = array();
		$this->db 							= $this->getWriteHandle();
		$data['sessionId'] 					= $sessionId;
		$data['categoryId'] 				= $categoryPageRequest->getCategoryId();
		$data['subCategoryId'] 				= $categoryPageRequest->getSubCategoryId();
		$data['LDBCourseId'] 				= $categoryPageRequest->getLDBCourseId();
		$data['localityId'] 				= $categoryPageRequest->getLocalityId();
		$data['zoneId'] 					= $categoryPageRequest->getZoneId();
		$data['cityId'] 					= $categoryPageRequest->getCityId();
		$data['stateId'] 					= $categoryPageRequest->getStateId();
		$data['countryId'] 					= $categoryPageRequest->getCountryId();
		$data['regionId'] 					= $categoryPageRequest->getRegionId();
		// For RNR Phase 2
		$data['affiliationName'] 			= ( $categoryPageRequest->getAffiliationName() != "" ) ? $categoryPageRequest->getAffiliationName() : "none";
		$data['examName'] 		 			= ( $categoryPageRequest->getExamName() != "" ) ? $categoryPageRequest->getExamName() : "none";
		$data['feesValue'] 		 			= ( $categoryPageRequest->getFeesValue() != -1 ) ? $categoryPageRequest->getFeesValue() : "none";
		if(empty($data['feesValue'])){
			$data['feesValue'] = "none";
		}
		
		$data['date'] = date('Y-m-d H:i:s');
		$data['result_count'] = $resultCount;
		$data['url'] = ($_SERVER['HTTP_REFERER'] != '') ? $_SERVER['HTTP_REFERER'] : '';
		foreach($appliedFilters as $filter => $filterValues) {
			$data['filter'] = $filter;
			if($filter == 'durationRange') {
				$data['value'] = $filterValues['range']." ".$filterValues['type'];
                                $valueOfVilter = explode('_', $data['value']);
                                $data['filter_status'] = (in_array($valueOfVilter[0], $disabledAndCheckedFilters[$filter])) ? 'disabled': 'enabled';
                                array_push($trackingData,$data);
			}
			else {
				foreach($filterValues as $filterValue) {
					if(!is_null($filterValue)){
						$data['value'] = $filterValue;
                                        $valueOfVilter = explode('_', $data['value']);
                                        $data['filter_status'] = (in_array($valueOfVilter[0], $disabledAndCheckedFilters[$filter])) ? 'disabled': 'enabled';
                                        array_push($trackingData,$data);
					}
				}
			}
		}
                if(!empty($trackingData)) {
                    $this->db->insert_batch('categoryPageFiltersTracking',$trackingData);
                }
                */
	}
	
	/*
	 * Cron specific functions
	 */ 
	public function getAlreadyRunningCron()
	{
		return;
		$sql =  "SELECT id,pid ".
				"FROM categoryPageCron ".
				"WHERE status = ? ";
		//return $this->db->query($sql, array(CP_CRON_ON))->row();
	}
	
	function registerCron($pid,$status,$ipAddress)
	{
		return;
		/*
		$this->db = $this->getWriteHandle();
		$pid = (int) $pid;
		if($pid > 0) {
			if($status == CP_CRON_TERMINATE) {
				$this->db->query("UPDATE categoryPageCron ".
								"SET status = ? ".
								"WHERE status = ? ", array(CP_CRON_TERMINATE, CP_CRON_ON));
				$status = CP_CRON_ON;
			}
		
			$data = array(
						   'pid' => $pid,
						   'startTime' => date('Y-m-d H:i:s'),
						   'status' => $status,
						   'ipAddress' => $ip_address
						);
			if($this->db->insert('categoryPageCron', $data)) {
				$cronId = $this->db->insert_id();
				return $cronId;
			}
			else  {
				return false;
			}
		}
		else  {
			return false;
		}
		*/
	}
	
	function updateCron($cronId,$status,$timeWindow,$stats)
	{
		return;
		/*
		$this->db = $this->getWriteHandle();
		$endTime = '0000-00-00 00:00:00';	
		if($status == CP_CRON_OFF) {
			$endTime = date('Y-m-d H:i:s');
		}
		
		$query = $this->db->query("UPDATE categoryPageCron ".
								  "SET status = ?,lastProcessedTimeWindow = ?,endTime = ? ".
								  "WHERE id = ? "
								, array($status, $timeWindow, $endTime, $cronId));
		$this->_storeCronStats($cronId,$stats);
		*/
	}
	
	function getCronFailCount($cronId)
	{
		return;
		/*
		$query = $this->db->query("SELECT count(*) as failCount ".
								  "FROM categoryPageCron ".
								  "WHERE startTime > (SELECT startTime FROM categoryPageCron WHERE id = ?) ". 
								  "AND status = ? "
								, array($cronId, CP_CRON_FAIL));
		$row = $query->row();
		return $row->failCount;
		*/
	}
	
	function getLastProcessedTimeWindow()
	{
		return;
		/*
		$query = $this->db->query("SELECT lastProcessedTimeWindow ".
								  "FROM categoryPageCron ".
								  "WHERE lastProcessedTimeWindow != '0000-00-00 00:00:00' ".
								  "ORDER BY startTime DESC ".
								  "LIMIT 1 ");
		$row = $query->row();
		return $row->lastProcessedTimeWindow;
		*/
	}

    public function getDynamicLocationListForBrowseInstitute(CategoryPageRequest $categoryPageRequest)
	{
		return;
		/*
		if($categoryPageRequest->isStudyAbroadPage()) {
			$countryClause = " `country_id` > 2 ";
		}else{
			$countryClause = " `country_id` = ".$categoryPageRequest->getCountryId()." ";
		}
		if($categoryPageRequest->isMainCategoryPage()){
			$sql =  "SELECT DISTINCT `virtualCityId`,`state_id`,`country_id`,`category_id`,`ldb_course_id` ".
				"FROM `categoryPageData` c,`categoryBoardTable`,`virtualCityMapping` v ".
				"WHERE ".
			$countryClause.
				"AND parentId = ? ".
				"AND c.city_id = v.city_id ".
				"AND `category_id` = boardId ".
				"AND `status` = 'live' ";

			$tlists =  $this->db->query($sql, array($categoryPageRequest->getCategoryId()))->result_array();
		}elseif($categoryPageRequest->isSubCategoryPage()){
			$sql =  "SELECT DISTINCT `virtualCityId`,`state_id`,`country_id` ".
				"FROM `categoryPageData` c,`virtualCityMapping` v ".
				"WHERE ".
			$countryClause.
				"AND c.city_id = v.city_id ".
				"AND `category_id` = ? ".
				"AND `status` = 'live' ";

			$tlists =  $this->db->query($sql, array($categoryPageRequest->getSubCategoryId()))->result_array();
		}else{
			$sql =  "SELECT DISTINCT `virtualCityId`,`state_id`,`country_id` ".
				"FROM `categoryPageData` c,`virtualCityMapping` v ".
				"WHERE ".
			$countryClause.
				"AND c.city_id = v.city_id ".
				"AND `ldb_course_id` = ? ".
				"AND `status` = 'live' ";

			$tlists =  $this->db->query($sql, array($categoryPageRequest->getLDBCourseId()))->result_array();
		}
		error_log('LOCATION_QUERY'.$sql.$countryClause);
		// $tlists =  $this->db->query($sql)->result_array();
		$tempList = array();
		foreach($tlists as $c){
			$tempList['states'][] = $c['state_id'];
			$tempList['countries'][] = $c['country_id'];
			$tempList['cities'][] = $c['virtualCityId'];
			if(array_key_exists('category_id',$c)) {
				$tempList['category_id'][] = $c['category_id'];
			}
			if(array_key_exists('ldb_course_id',$c)) {
				$tempList['ldb_course_id'][] = $c['ldb_course_id'];
			}
			$tempList['main'][] = $c;
		}
		$tempList['states'] = array_unique($tempList['states']);
		$tempList['cities'] = array_unique($tempList['cities']);
		$tempList['countries'] = array_unique($tempList['countries']);
		//$tempList['main'] = array_unique($tempList['main']);
		if(array_key_exists('category_id',$tempList)) {
			$tempList['category_id'] = array_unique($tempList['category_id']);
		}
		if(array_key_exists('ldb_course_id',$tempList)) {
			$tempList['ldb_course_id'] = array_unique($tempList['ldb_course_id']);
		}
		if($categoryPageRequest->isStudyAbroadPage()) {
			if(count($tempList['countries'])>0) {
				$query = "select distinct regionid from tregionmapping where regionmapping = 'country' and id in (".implode(',',$tempList['countries']).")";
				error_log('LOCATION_QUERY1'.$query);
				$tlists1 =  $this->db->query($query)->result_array();
				foreach($tlists1 as $c1){
					$tempList['regionid'][] = $c1['regionid'];
				}
			}
		}
		return $tempList;
		*/
	}

	public function getRegionsForCountries($array) {
		return; /*
				$query = "select distinct regionid from tregionmapping where regionmapping = 'country' and id in (".implode(',',$array).")";
				error_log('LOCATION_QUERY_REGION'.$query);
				$tlists1 =  $this->db->query($query)->result_array();
				foreach($tlists1 as $c1){
					$tempList['regionid'][] = $c1['regionid'];
				}
				return $tempList; */
	}
	
	/**
	 * Purpose : To fetch the category page data for provided subcategroies
	**/
	public function getCategoryPageDataForSubCategories( $value )
	{
		return; /*
	    $query = "SELECT
	        DISTINCT
		CBT.parentId AS categoryId, 
		CPD.category_id as subCategoryId,
		CPD.ldb_course_id as LDBCourseId,
		CPD.city_id as cityId,
		CT.city_name	  as cityName,
		CASE WHEN CPD.state_id < 1 THEN 1 ELSE CPD.state_id END as stateId,
		ST.state_name	  as stateName,
		CPD.country_id as countryId,
		CASE WHEN ISNULL(VCM.virtualCityId) THEN 0 ELSE VCM.virtualCityId  END AS virtualCityId,
		CASE WHEN ISNULL(TRM.regionid) THEN 0 ELSE TRM.regionid END AS regionId
		FROM `categoryPageData` CPD
		LEFT JOIN categoryBoardTable CBT ON ( CBT.boardId = CPD.category_id )
		LEFT JOIN stateTable ST ON(ST.state_id = CPD.state_id)
		LEFT JOIN countryCityTable CT ON(CT.city_id = CPD.city_id)
		LEFT JOIN tregionmapping TRM ON(TRM.id = CPD.country_id)
		LEFT JOIN virtualCityMapping VCM ON(VCM.city_id = CPD.city_id AND VCM.virtualCityId != CPD.city_id)
		WHERE CPD.`category_id` IN ( ".$value .")
		AND CPD.status = 'live'
		AND CPD.city_id NOT IN (10166)
		;";
	    
	    $catPageData = $this->db->query($query)->result_array();
	    
	    error_log("\n".date("Y-m-d:H:i:s")." Fetched Results FOR city : ".$this->db->affected_rows(),3,"/tmp/zeroresults.log");
	    
	    return $catPageData; */
	}
	
	/**
	 * Purpose : To fetch the category page data along with locality data for provided subcategories
	**/
	public function getCategoryPageDataWithLocalityForSubCategories( $value )
	{
		return; /*
	    $query =   "SELECT
		    DISTINCT 
		    A.category_id as subCategoryId,
		    A.ldb_course_id as LDBCourseId,
		    A.city_id as cityId,
		    CT.city_name as cityName,
		    CASE WHEN A.state_id < 1 THEN 1 ELSE A.state_id END as stateId,
		    ST.state_name as stateName,
		    A.country_id as countryId,
		    CASE WHEN ISNULL(VCM.virtualCityId) THEN 0 ELSE VCM.virtualCityId  END AS virtualCityId,
		    CASE WHEN ISNULL(TRM.regionid) THEN 0 ELSE TRM.regionid END AS regionId,
		    CASE WHEN C.locality_id < 0 THEN 0 ELSE C.locality_id END as localityId,
		    C.zone as zoneId
		    FROM 
		    categoryPageData A
		    LEFT JOIN
		    stateTable ST ON(ST.state_id = A.state_id)
		    LEFT JOIN
		    countryCityTable CT ON(CT.city_id = A.city_id)
		    LEFT JOIN 
		    course_location_attribute B
		    ON(A.course_id = B.course_id AND B.status = 'live')
		    INNER JOIN
		    institute_location_table C
		    ON(B.institute_location_id = C.institute_location_id AND C.locality_id IS NOT NULL AND C.locality_id NOT IN(0) AND C.status='live')
		    LEFT JOIN
		    tregionmapping TRM ON(TRM.id = A.country_id)
		    LEFT JOIN
		    virtualCityMapping VCM ON(VCM.city_id = A.city_id AND VCM.virtualCityId != A.city_id)
		    WHERE 1
		    AND A.category_id IN(".$value.")
		    AND A.status='live'
		    AND A.city_id NOT IN (10166)
		    AND A.city_id = C.city_id ;";
		    
	    $subCatData = $this->db->query($query)->result_array();
	
	    error_log("\n".date("Y-m-d:H:i:s")." Fetched Results FOR locality: ".$this->db->affected_rows(),3,"/tmp/zeroresults.log");
    
	    return $subCatData;
	*/
	}
	
	/**
	 * Purpose : To fetch the resultset for the provided query
	**/
	public function getResultSetFromQuery( $selectStmt, $tableStmt, $whereStmt )
	{
		return;
	    $sql = $selectStmt." FROM ".$tableStmt." WHERE 1 ".$whereStmt;
	    
	    _p("$sql");
	
	    //$rs = $this->db->query($sql)->result_array();
	    error_log("\n".date("Y-m-d:H:i:s")." Resultset Fetched ".$count,3,"/tmp/zeroresults.log");
	    return $rs;
	
	}
	
	/**
	 * Purpose : To insert multiple rows at a time
	**/
	public function insertDataInBulk( $insertStmt )
	{
		return;
	    $dbconn = $this->getWriteHandle();
	    error_log("\n".date("Y-m-d:H:i:s")." ========= Inserting :  ".$sql,3,"/tmp/zeroresults.log");
	    
	    //$dbconn->query($insertStmt);
	    error_log("\n".date("Y-m-d:H:i:s")." ================== Inserted ".$count,3,"/tmp/zeroresults.log");
	    
	}

	/**
	 * Purpose : To get the name of the category-page non zero pages table name for the next month
	 * 		eg : category_page_non_zero_pages_Jan_2014
	**/	
	public function getNonZeroCategoryPageResultTableNameNextMonth()
	{
		return;
	    $nextMonth = date("M_Y",strtotime("+1 Months")); //date("M_Y"); 
	    $tableName = "category_page_non_zero_pages_".$nextMonth;
	    return $tableName;
	}
	
	/**
	 * Purpose : To get the name of the category-page non zero pages table name for the current month
	 * 		eg : category_page_non_zero_pages_Dec_2013
	**/
	public function getNonZeroCategoryPageResultTableNameCurrentMonth()
	{
		return;
	    $nextMonth = date("M_Y"); 
	    $tableName = "category_page_non_zero_pages_".$nextMonth;
	    return $tableName;
	}

	/**
	 * Purpose : Function to create Non-zero category-page data table for next month
	**/
	public function createNonZeroCategoryPageResultTable()
	{
		return; /*
	    $dbconn = $this->getWriteHandle();
	    
	    $tableName = $this->getNonZeroCategoryPageResultTableNameNextMonth();
	    
	    $sql = "CREATE TABLE IF NOT EXISTS ".$tableName." (
			`id` int(11) NOT NULL AUTO_INCREMENT,
		    `category_page_key` varchar(100) NOT NULL,
		    `category_id` int(5) NOT NULL,
		    `sub_category_id` int(5) NOT NULL,
		    `LDB_course_id` int(5) NOT NULL,
		    `locality_id` int(5) NOT NULL DEFAULT '0',
		    `zone_id` int(5) NOT NULL DEFAULT '0',
		    `city_id` int(5) NOT NULL DEFAULT '1',
		    `city_name` varchar(50) DEFAULT '',
		    `state_id` int(5) NOT NULL DEFAULT '1',
		    `state_name` varchar(50) DEFAULT '',
		    `country_id` int(5) NOT NULL DEFAULT '2',
		    `region_id` int(5) NOT NULL DEFAULT '0',
		    `exam_value` varchar(50) NOT NULL DEFAULT 'none',
		    `affiliation_value` varchar(30) NOT NULL DEFAULT 'none',
		    `fees_value` varchar(10) NOT NULL DEFAULT 'none',
			`count` int(11) NULL,
		    KEY `category_city_index` (`category_id`,`country_id`,`city_id`,`city_name`),
		    KEY `category_state_index` (`category_id`,`country_id`,`state_id`,`state_name`),
		    KEY `subcategory_city_index` (`category_id`,`sub_category_id`,`country_id`,`city_id`,`city_name`),
		    KEY `subcategory_state_index` (`category_id`,`sub_category_id`,`country_id`,`state_id`,`state_name`),
		    KEY `course_city_index` (`category_id`,`sub_category_id`,`LDB_course_id`,`country_id`,`city_id`,`city_name`),
		    KEY `course_state_index` (`category_id`,`sub_category_id`,`LDB_course_id`,`country_id`,`state_id`,`state_name`),
		    KEY `exam_index` (`exam_value`),
		    KEY `affiliation_index` (`affiliation_value`),
		    KEY `fees_index` (`fees_value`)
		    ) ENGINE=MyISAM;";
		    
	    $dbconn->query($sql); */
		    
	}
	
	/**
	 * Purpose : Function to fetch Non-RNR subcategories from DB
	**/	
	public function getNonRnRSubcategories()
	{
		return; /*
	    $RNRSubcategoryIds = array_keys($this->config->item("CP_SUB_CATEGORY_NAME_LIST"));
	    $RNRSubcategoryIds = implode(",", $RNRSubcategoryIds);
	
	    $query =   "SELECT
			group_concat(Distinct a.`category_id`) as subCategoryIds
			FROM `categoryPageData` a
			WHERE  a.`category_id` not in (".$RNRSubcategoryIds.")";
		
	    $Ids = $this->db->query($query)->result_array();

	    return $Ids; */
	}
	
	/**
	 * Purpose : Function to fetch non-zero category pages for Browse section functionality
	**/	
	public function getNonZeroCategoryPagesForBrowse( $searchDetailsArr, $type )
	{
		return; /*
	    $selectStmt = "";
	    $whereStmt  = " 1 ";
	    $fromStmt   = "";
	    
	    $selectStmt = "SELECT DISTINCT
			   A.category_page_key 	as page_key,
			   A.city_id 		as city_id,
			   A.city_name 		as city_name ,
			   A.state_id 		as state_id,
			   A.state_name 	as state_name,
			   A.country_id 	as country_id,
			   A.exam_value 	as exam,
			   A.affiliation_value as affiliation,
			   A.fees_value 	as fees
			   ";
	    $tablename = $this->getNonZeroCategoryPageResultTableNameCurrentMonth();
	    
	    $fromStmt = $tablename." A ";

	    // prepare the where statement for the conditions provided
	    if( !empty($searchDetailsArr['categoryId']) )
		$whereStmt .= " AND A.category_id = ".$searchDetailsArr['categoryId'];

	    if( !empty($searchDetailsArr['subCategoryId']) )
		$whereStmt .= " AND A.sub_category_id = ".$searchDetailsArr['subCategoryId'];
		
	    if( !empty($searchDetailsArr['LDB_course_id']) )
		$whereStmt .= " AND A.LDB_course_id = ".$searchDetailsArr['LDB_course_id'];

	    if( !empty($searchDetailsArr['country_id']) )
		$whereStmt .= " AND A.country_id = ".$searchDetailsArr['country_id'];

	    if( !empty($searchDetailsArr['exam_value']) )
		$whereStmt .= " AND A.exam_value LIKE '".$searchDetailsArr['exam_value']."'";

	    if( !empty($searchDetailsArr['affiliation_value']) )
		$whereStmt .= " AND A.affiliation_value LIKE '".$searchDetailsArr['affiliation_value']."'";

	    if( !empty($searchDetailsArr['fees_value']) )
		$whereStmt .= " AND A.fees_value = ".$searchDetailsArr['fees_value'];
		
	    // check whether results are to be fetched for city or state
	    if( $type == "city" )
	    {
		if( !empty($searchDetailsArr['nameInitial']) )
		    $whereStmt .= " AND A.city_name LIKE '".$searchDetailsArr['nameInitial']."%'";
	    }
	    else if( $type == "state" )
	    {
		if( !empty($searchDetailsArr['nameInitial']) )
		   {
			 $whereStmt .= " AND A.state_name LIKE '".$searchDetailsArr['nameInitial']."%'";
			$whereStmt .= " AND A.city_id IN (0,1) ";
			}
	    }
	    
	    $sql  = $selectStmt. " FROM ". $fromStmt. " WHERE " .$whereStmt ;
	    
	    $data = $this->db->query($sql)->result_array();
	    
	    return $data; */
	}
	
	/**
	 * 
	 * Purpose : Function to get Exam Name for a Category,Subcategory Combination;
	 * 
	 */
	
	  public function getExamNameForCatgoryIdAndSubcatID($searchDetailsArr){
	  	return; /*
	  	$selectStmt = "";
	  	$whereStmt  = " 1 ";
	  	$fromStmt   = "";
	  	$selectStmt = "SELECT DISTINCT
				   A.exam_value 	as exam ";
	  	$tablename = $this->getNonZeroCategoryPageResultTableNameCurrentMonth();
	  	if( !empty($searchDetailsArr['categoryId']) )
	  		$whereStmt .= " AND A.category_id = ".$searchDetailsArr['categoryId'];
	  	
	  	if( !empty($searchDetailsArr['subCategoryId']) )
	  		$whereStmt .= " AND A.sub_category_id = ".$searchDetailsArr['subCategoryId'];
	  		$whereStmt.= " AND A.exam_value <> 'none'";
	  	$fromStmt = $tablename." A ";
	  	$sql  = $selectStmt. " FROM ". $fromStmt. " WHERE " .$whereStmt ;
	  	$data = $this->db->query($sql)->result_array();
	  	$sortedDataWithPriorityOrder = $this->_examsWithPriorityOrder($data);
	  	return $sortedDataWithPriorityOrder; */
	  }
	
	  private function _examsWithPriorityOrder($unSortedexamList)
	  {
	  	return; /*
	  	$examListWithWeightage = array();
	  	global $exam_weightage_array;
	  	foreach ($unSortedexamList as $exam)
	  	{   $exam = $exam['exam'];
	  		$examWeightage = $exam_weightage_array[$exam];
	  		$examWeightage = $examWeightage >0 ?  $examWeightage : 0;
	  		if(array_key_exists($examWeightage,$examListWithWeightage) && (!in_array($exam,$examListWithWeightage[$examWeightage])))
	  		{
	  			array_push($examListWithWeightage [$examWeightage],$exam);
	  		}
	  		else
	  		{
	  			$examListWithWeightage [$examWeightage] = array($exam);
	  		}
	  	}
	  	ksort($examListWithWeightage);
	  	$examListWithWeightage = array_reverse($examListWithWeightage);
	  	$examListWithPriorityOrder = array();
	  	foreach($examListWithWeightage as $key => $examList){
	  		foreach($examList as $exam)
	  		{
	  			$examListWithPriorityOrder[]['exam'] = $exam;
	  		}
	  
	  	}
	  	return $examListWithPriorityOrder;
	  */
	  }
	  
	/*
	 * Purpose :  Function to get Locality Id,CityId,StateId,CountryId For a Exam Name;
	 *  
	 */  

	 public function getLocationFromExam($arrayOfExmas){
	 	return; /*
	 	$selectStmt = "";
	 	$whereStmt  = " 1 ";
	 	$fromStmt   = "";
	 	$selectStmt = "SELECT DISTINCT
			   A.city_id 		as city_id,
	 		   A.state_id 	as state_id,
	 	       A.locality_id 	as locality_id	  ";
	 	$tablename = $this->getNonZeroCategoryPageResultTableNameCurrentMonth();
	   	$fromStmt = $tablename." A ";
	 	$whereStmt.= " AND A.exam_value IN ('".implode("','",$arrayOfExmas)."') AND country_id = 2 ORDER BY city_name ASC";
	 	$sql  = $selectStmt. " FROM ". $fromStmt. " WHERE " .$whereStmt ;
	 	$resultdata = $this->db->query($sql)->result_array();
	  	$this->load->builder('LocationBuilder','location');
	 	$locationBuilder = new LocationBuilder;
	 	$this->locationRepository = $locationBuilder->getLocationRepository();
	 	$localityIdArray;
	 	$stateIdArray;
	 	$cityIdArray;
	  	$LocationArrayObjWithTiers; 
	 	
	 	foreach($resultdata as $locationArrray ){
	    	if($locationArrray['city_id']>0 && !in_array($locationArrray['city_id'],$cityIdArray))
	 		{
	 			$cityIdArray[]= $locationArrray['city_id'];
	 			$cityObj = $this->locationRepository->findCity($locationArrray['city_id']);
	 			$tier =$cityObj->getTier();
	 			$LocationArrayObjWithTiers['city'][$tier][] = $cityObj;
	 		}
	 		if($locationArrray['state_id']>0 && !in_array($locationArrray['state_id'],$stateIdArray))
	 		{
	 			$stateIdArray[]= $locationArrray['state_id'];
	 			$stateObj = $this->locationRepository->findState($locationArrray['state_id']);
	 			$tier =$stateObj->getTier();
	 			$LocationArrayObjWithTiers['state'][0][] = $stateObj;
	 		}
	 		if($locationArrray['locality_id']>0 && !in_array($locationArrray['locality_id'],$localityIdArray))
	 		{
	 			$localityIdArray[]= $locationArrray['locality_id'];
	 			$localityObj = $this->locationRepository->findLocality($locationArrray['locality_id']);
	 			$localityCity = $localityObj->getCityId();
	 			$LocationArrayObjWithTiers['locality'][$localityCity][] = $localityObj;
	 		}
	 	}
	 	return $LocationArrayObjWithTiers; */
	 }
	
	/**
	 * Purpose : Function to delete Non-zero category-page data table for next month
	**/
	public function deleteNonZeroCategoryPageResultTable()
	{
		return; /*
	    $dbconn = $this->getWriteHandle();

	    $tableName = $this->getNonZeroCategoryPageResultTableNameNextMonth();
	    
	    // check if the table exists or not
	    $query = "SHOW TABLES LIKE '".$tableName."'";
	    
	    $data = $dbconn->query($query)->result_array();
	    
	    if( !empty($data) )
	    {
		//delete the data of the table
		$query = "DELETE FROM ".$tableName;
		
		$dbconn->query($query);
	    } */
	}
	
	/*
	 *Purpose: Function to track view similar institute data
	 */
	public function dbTrackViewSimilarInstt($data) {
		return;
		$dbconn = $this->getWriteHandle();
		
		$dataArr = array("UserId" => $data['userId'],
						 "CourseIdSearched" => $data['courseIdSearched'],
						 "InstituteIdSearched" => $data['instituteIdSearched'],
						 "PageType" => $data['pageType'],
						 "ButtonType" => $data['buttonType'],
						 "TextEntered" => $data['textEntered'],
						 "CourseIdChosen" => $data['courseIdChosen'],
						 "ZeroResult" => $data['zeroResult']);
		
		//$dbconn->insert("viewSimilarInstituteTracking", $dataArr);
	}
	
	/*
	 * Purpose : Function to track user selected locations on multi-location overlay
	 */
	public function trackLocationSelected($data){
		return;
	    //$dbhandle = $this->getWriteHandle();
	    //$dbhandle->insert('trackLocationCategoryPage',$data);
	}
	
	/**
     * @unused // TODO Delete after review
	 * Purpose : Function to fetch non-zero category pages data
	**/	
	public function getNonZeroCategoryPagesData($limit = false, $offset = false)
	{
		return; /*
	    $tablename = $this->getNonZeroCategoryPageResultTableNameCurrentMonth();
	    if(!empty($limit) && !empty($offset)) {
			$limitSql = 'LIMIT '.$limit.' OFFSET '.$offset;
		} else {
			$limitSql = '';
		}
		$sql = "SELECT DISTINCT category_id	,
						sub_category_id,
						LDB_course_id,
						locality_id,
						city_id,
						city_name,
						zone_id,
						state_id,
						country_id,
						region_id,
						exam_value,
						affiliation_value,
						fees_value
				FROM ".$tablename." where country_id = 2 ".
				$limitSql;
		
	    $data = $this->db->query($sql)->result_array();
	    
	    return $data; */
	}
	
	public function saveFeedbackRatingsFromCategoryPage($institute_id = NULL, $rating = NULL, $sessionId = NULL, $userId = -1, $location = "")
	{
		return; /*
		if(empty($institute_id) || empty($sessionId)){
			return;
		}
		$this->db = $this->getWriteHandle();
		$data = array();
		$data['institute_id'] 	= $institute_id;
		$data['rating'] 		= $rating;
		$data['user_id'] 		= $userId;
		$data['session_id'] 	= $sessionId;
		$data['location']		= $location;
		$data['created'] 		= date('Y-m-d H:i:s');
		$queryCmd 			= $this->db->insert_string('RNR_CP_listing_feedback', $data);
		$query 				= $this->db->query($queryCmd); 
        $unique_insert_id 	= $this->db->insert_id();
		return $unique_insert_id; */
	}
	
	public function getFeedbackIdForSameSessionSameInstitute($sessionId = NULL, $institute_id = NULL) {
		return; /*
		if(empty($sessionId) || empty($institute_id)){
			return false;
		}
		
		$queryCmd 	= "SELECT * FROM RNR_CP_listing_feedback where session_id = ? AND institute_id = ?";
		$query 		= $this->db->query($queryCmd, array($sessionId, $institute_id));
		$data 		= $query->result_array();
		return $data; */
	}
	
	public function updateFeedbackRatingsFromCategoryPage($feedbackId, $message = "") {
		return; /*
		if(empty($feedbackId)){
			return;
		}
		$this->db = $this->getWriteHandle();
		$queryCmd = "UPDATE RNR_CP_listing_feedback set message = ?, created = ? WHERE id = ?";
		$query 	  = $this->db->query($queryCmd, array($message, date('Y-m-d H:i:s'), $feedbackId)); */
    }
	
	public function checkIfCategoryPageIsNonZero($data) {
		return; /*
		if(empty($data)){
			return false;
		}
		$currentMonth = date("M");
		$currentYear = date("Y");
		$tableName = 'category_page_non_zero_pages_'.$currentMonth.'_'.$currentYear;
		
		$queryCmd 	= "SELECT * FROM $tableName where ";
		end($data);
		$lastKey = key($data);

		foreach($data as $name => $val){
			if($name != $lastKey){
				$queryCmd .= "$name = ? and ";
			}
			else{
				$queryCmd .= "$name = ?";
			}
		}

		$query 		= $this->db->query($queryCmd,array_values($data));
		$data 		= $query->result_array();
		
		if(!empty($data)) {
			return true;
		} else {
			return false;
		}
		*/
	}
	
	/*
	 * Purpose : Function to fetch non-zero category pages data of next month
	 */
	public function getNonZeroCategoryPagesDataNextMonth($type = false, $limit = false, $offset = false)
	{
		return; /*
	    $tablename = $this->getNonZeroCategoryPageResultTableNameNextMonth();
		
		$whereSql = '';
		if(!empty($type)) {
			if($type == 'rnr') {
				$whereSql = 'WHERE sub_category_id IN (23,56) ';
			} else if($type == 'non_rnr') {
				$whereSql = 'WHERE sub_category_id NOT IN (23,56) ';
			} else if($type == 'remaining') {
				$whereSql = 'WHERE count IS NULL ';
			}
		}
		
		if(!empty($limit)) {
			$limitSql = 'LIMIT '.$limit.' OFFSET '.$offset;
		} else {
			$limitSql = '';
		}
		
	    $sql = "SELECT  id, ".
						"category_id	as categoryId, ".
						"sub_category_id as subCategoryId, ".
						"LDB_course_id as LDBCourseId, ".
						"locality_id as localityId, ".
						"city_id as cityId, ".
						"zone_id as zoneId, ".
						"state_id as stateId, ".
						"country_id as countryId, ".
						"region_id as regionId, ".
						"exam_value as examName, ".
						"affiliation_value as affiliation, ".
						"fees_value as feesValue ".
				"FROM ".$tablename." ".
				$whereSql.
				" ORDER BY id ".
				$limitSql;
		
	    $data = $this->db->query($sql)->result_array();
	    
	    return $data; */
	}
	
	/*
	 * Purpose : Function to write count of category pages data for next month
	 */
	public function writeCategoryPageResultCount($countArr) {
		return; /*
		$logFilePath = '/tmp/log_category_page_zero_result_entries_'.date('y-m-d');
		$tablename = $this->getNonZeroCategoryPageResultTableNameNextMonth();
		
		$this->db = $this->getWriteHandle();
		
		error_log("Row id of entries with count 0\n", 3, $logFilePath);
		$data = array();
		foreach($countArr as $id=>$count) {
			$data[] = array('id'=>$id, 'count'=>$count);
			if($count === 0) {
				$dataWithZeroResult[$id] = $count;
				error_log($id."\n", 3, $logFilePath);
			}
			if($count === NULL) {
				$dataWithNullResult[$id] = $count;
				error_log($id."\n", 3, '/tmp/log_category_page_null_row_ids_'.date('y-m-d'));
			}
		}
		error_log("Data fed to update_batch \n".print_r($data, true)."\n", 3, '/tmp/log_category_page_count_query_data_'.date('y-m-d'));
		$totalNumOfZeroResults = sizeof($dataWithZeroResult);
		error_log("Total zero result entries: ".$totalNumOfZeroResults, 3, $logFilePath);
		
		$this->db->update_batch($tablename, $data, 'id');
		return $totalNumOfZeroResults; */
	}
	
	public function getNonZeroRankingPages($filters, $limit = false){
	    return; /*
	    $sql = "SELECT * FROM ranking_non_zero_pages where 1 ";
		    
	    $whereCondition 	= "";
	    $orderByClause 	= " ORDER BY number_of_results desc";
	    
	    if(isset($filters['subcategoryId']))
		$whereCondition .= " AND sub_category_id = ".$filters['subcategoryId'];
	    if(isset($filters['ldbCourseId']))
		$whereCondition .= " AND LDB_course_id = ".$filters['ldbCourseId'];
	    if(isset($filters['cityId']))
	    {
		if(is_array($filters['cityId']) && !empty($filters['cityId']))
		    $whereCondition .= " AND city_id IN (".implode(",",$filters['cityId']).")";
		else
		    $whereCondition .= " AND city_id = ".$filters['cityId'];
	    }
	    if(isset($filters['stateId']))
		$whereCondition .= " AND state_id = ".$filters['stateId'];
	    if(isset($filters['countryId']))
		$whereCondition .= " AND country_id = ".$filters['countryId'];
	    if(isset($filters['examId']))
		$whereCondition .= " AND exam_id = ".$filters['examId'];
	    if(isset($filters['examName']))
		$whereCondition .= " AND exam_name = '".$filters['examName']."'";
	    if($filters['examNeeded'] == 1)
		$whereCondition .= " AND exam_id != 0";
	    if($filters['specializationNeeded'] == 1)
		$whereCondition .= " AND LDB_course_id != 0";

	    $whereCondition .= " AND status = 'live'";

	    $limitClause = '';
	    if($limit)
		$limitClause = ' LIMIT '.$limit;

	    $sql = $sql.$whereCondition.$orderByClause.$limitClause;

	    $data = $this->db->query($sql)->result_array();
	    
	    return $data; */
	}
	
	public function getNonZeroCategoryPages($searchDetailsArr){
		return; /*
		$selectStmt = "SELECT DISTINCT
			   A.category_id as category_id,
			   A.LDB_course_id as ldbCourseId,
			   A.sub_category_id as sub_category_id,
			   A.city_id 		as city_id,
			   A.city_name 		as city_name ,
			   A.state_id 		as state_id,
			   A.state_name 	as state_name,
			   A.country_id 	as country_id,
			   A.exam_value 	as exam,
			   A.affiliation_value as affiliation,
			   A.fees_value 	as fees,
			   A.count 	        as count
			   ";

		//$tablename = 'category_page_non_zero_pages_Feb_2015';
		$tablename = $this->getNonZeroCategoryPageResultTableNameCurrentMonth();
  		$fromStmt = $tablename." A ";

  		// prepare the where statement for the conditions provided
  		
  		
		$whereStmt .= "  A.locality_id = 0 AND A.affiliation_value = 'none' AND A.zone_id = 0 AND A.count > 0";

	    if( !empty($searchDetailsArr['categoryId']) )
		$whereStmt .= " AND A.category_id = ".$searchDetailsArr['categoryId'];

	    if( !empty($searchDetailsArr['subCategoryId']) )
		$whereStmt .= " AND A.sub_category_id = ".$searchDetailsArr['subCategoryId'];
		
	    if( !empty($searchDetailsArr['ldbCourseId']) )
		$whereStmt .= " AND A.LDB_course_id = ".$searchDetailsArr['ldbCourseId'];

	    if( !empty($searchDetailsArr['countryId']) )
		$whereStmt .= " AND A.country_id = ".$searchDetailsArr['countryId'];

	    if( !empty($searchDetailsArr['stateId']) )
		$whereStmt .= " AND A.state_id = ".$searchDetailsArr['stateId'];

	    if( !empty($searchDetailsArr['cityId']) && is_numeric($searchDetailsArr['cityId']) )
		$whereStmt .= " AND A.city_id = ".$searchDetailsArr['cityId'];

	    if( !empty($searchDetailsArr['examName']) )
		$whereStmt .= " AND A.exam_value LIKE '".$searchDetailsArr['examName']."'";

	    if( !empty($searchDetailsArr['fees']) )
		$whereStmt .= " AND A.fees_value = '".$searchDetailsArr['fees']."'";

		if(!empty($searchDetailsArr['examNeeded']))
		$whereStmt .= " AND A.exam_value != 'none' ";			
		

		if(!empty($searchDetailsArr['feesNeeded']))
		$whereStmt .= " AND A.fees_value != 'none' ";			

		//exclude ldb course id 2 
		//Reason: Full time specialization category page doesnt exist
		if(!empty($searchDetailsArr['specializationNeeded']))
		$whereStmt .= " AND A.LDB_course_id NOT IN (1,2) ";			
		

		if(!empty($searchDetailsArr['stateNeeded']))
		$whereStmt .= " AND A.state_id != 1 ";			
		

		if(!empty($searchDetailsArr['cityNeeded']))
		$whereStmt .= " AND A.city_id != 1 ";			
		

		$orderByStmt = " ORDER BY A.count DESC";

		if(!empty($searchDetailsArr['limit']))
		$limitStmt .= " LIMIT ".$searchDetailsArr['limit'];			
		

		
	 	$sql  = $selectStmt. " FROM ". $fromStmt. " WHERE " .$whereStmt ." ".$orderByStmt." ".$limitStmt;
	
		$data = $this->db->query($sql)->result_array();
	    
	    return $data;
		*/
	}
	
	function getCoursesBasicDetails($courseIds){
		return; /*
		if(empty($courseIds))
			return array();

		$queryCmd 	= "SELECT institute_id, course_id, courseTitle, course_request_brochure_link FROM course_details where status = 'live' AND course_id IN (".implode(',', $courseIds).") ORDER BY course_order";
		$query 		= $this->db->query($queryCmd);
		$data 		= $query->result_array();

		return $data; */
	}
	
	function getInstitutesBrochureLinks($instituteIds){
		return; /*
		if(empty($instituteIds))
			return array();

		$queryCmd 	= "SELECT institute_id, institute_request_brochure_link FROM institute where status = 'live' AND institute_id IN (".implode(',', $instituteIds).") ";
		$query 		= $this->db->query($queryCmd);
		$data 		= $query->result_array();

		$output = array();
		foreach ($data as $value) {
			if($value['institute_request_brochure_link'])
				$output[$value['institute_id']] = $value['institute_request_brochure_link'];
		}

		return $output; */
	}
	
	function sortInstitutesOnRanking($data) {
		return; /*
		$queryCmd 	= "SELECT rpdn.institute_id as institute_id, rpdn.course_id, rpcsd.rank ".
					  "from ranking_pages_new as rpn ".
					  "INNER JOIN ranking_page_source_mapping as rpsm ON rpn.id = ranking_page_id AND source_id = 6 ".
					  "INNER JOIN ranking_page_data_new as rpdn ON rpn.id = rpdn.ranking_page_id AND rpdn.institute_id IN (".implode(',', $data['instituteIds']).") ".
					  "INNER JOIN ranking_page_course_source_data as rpcsd ON rpcsd.ranking_page_course_id = rpdn.id AND rpcsd.source_id = 6 AND rpcsd.parameter_id = 23 ".
					  "where rpn.subcategory_id = ".$data['subcatId']." and rpn.specialization_id = ".$data['specializationId']." and rpn.status = 'live' ".
					  "GROUP BY institute_id ".
					  "ORDER BY rank";
		$query 		= $this->db->query($queryCmd);
		$result		= $query->result_array();
		return $result; */
	}
	
	function getQuickLinkCatPagesForAllIndiaPages($data) {
		return; /*
		$tablename = $this->getNonZeroCategoryPageResultTableNameCurrentMonth();
		$queryCmd  = "SELECT DISTINCT city_id FROM ".$tablename.
					" WHERE city_id IN (".implode(',', $data['tier1CityIds']).")".
					" AND category_id = ".$data['categoryId'].
					" AND sub_category_id = ".$data['subcategoryId'].
					" AND LDB_course_id = ".$data['ldbCourseId'].
					" AND exam_value = '".$data['examName']."'".
					" AND zone_id = 0".
					" AND locality_id = 0".
					" AND country_id = 2".
					" AND fees_value = 'none'".
					" AND affiliation_value = 'none'".
					" AND count > 0".
					" ORDER BY count desc".
					" LIMIT 5";
		
		$query 	= $this->db->query($queryCmd);
		$result	= $query->result_array();
		return $result; */
	}

	function getQuickLinkCatPagesForStatePages($data) {
		return; /*
		$tablename = $this->getNonZeroCategoryPageResultTableNameCurrentMonth();
		$queryCmd  = "SELECT DISTINCT city_id FROM ".$tablename.
					" WHERE state_id = ".$data['stateId'].
					" AND city_id != 1".
					" AND category_id = ".$data['categoryId'].
					" AND sub_category_id = ".$data['subcategoryId'].
					" AND LDB_course_id = ".$data['ldbCourseId'].
					" AND exam_value = '".$data['examName']."'".
					" AND zone_id = 0".
					" AND locality_id = 0".
					" AND country_id = 2".
					" AND fees_value = 'none'".
					" AND affiliation_value = 'none'".
					" AND count > 0".
					" ORDER BY count desc".
					" LIMIT 5";
		
		$query 	= $this->db->query($queryCmd);
		$result	= $query->result_array();
		return $result; */
	}

	function getQuickLinkCatPagesForCityPages($data) {
		return; /*
		$tablename = $this->getNonZeroCategoryPageResultTableNameCurrentMonth();
		if($data['isCityTier1']) {
			$locationClause = 'city_id IN ('.implode(',', $data['tier1CityIds']).') AND city_id != '.$data['cityId'];
		} else {
			$locationClause = 'state_id = '.$data['stateId'].' AND city_id NOT IN (1,'.$data['cityId'].')';
		}
		$limit = 4;
		if($data['isVirtualCity']) {
			$limit = 5;
		}
		
		$queryCmd  = "SELECT DISTINCT city_id FROM ".$tablename.
					" WHERE ".$locationClause.
					" AND category_id = ".$data['categoryId'].
					" AND sub_category_id = ".$data['subcategoryId'].
					" AND LDB_course_id = ".$data['ldbCourseId'].
					" AND exam_value = '".$data['examName']."'".
					" AND zone_id = 0".
					" AND locality_id = 0".
					" AND country_id = 2".
					" AND fees_value = 'none'".
					" AND affiliation_value = 'none'".
					" AND count > 0".
					" ORDER BY count desc".
					" LIMIT ".$limit;
		
		$query 	= $this->db->query($queryCmd);
		$result	= $query->result_array();
		return $result; */
	}

	/**
    * function : getNonZeroCategoryFatFooter
    * param: $searchDetailsArr (array)
    * desc : prepare data for fat Footer
    * type : return list array for India and State urls based on category and subcategory
    * added by akhter
    **/    
	public function getNonZeroCategoryFatFooter($searchDetailsArr){
		return; /*
		$selectStmt = "SELECT DISTINCT
			   A.category_id as category_id,
			   A.LDB_course_id as ldbCourseId,
			   A.sub_category_id as sub_category_id,
			   A.city_id 		as city_id,
			   A.state_id 		as state_id,
			   A.country_id 	as country_id,
			   A.exam_value 	as exam,
			   A.affiliation_value as affiliation,
			   A.fees_value 	as fees ";

		$tablename = $this->getNonZeroCategoryPageResultTableNameCurrentMonth();
  		$fromStmt = $tablename." A ";

  		// prepare the where statement for the conditions provided
  		
		$whereStmt .= "  A.locality_id = 0 AND A.affiliation_value = 'none' AND A.zone_id = 0 AND A.count > 1";

	    if( !empty($searchDetailsArr['categoryId']) )
		$whereStmt .= " AND A.category_id = ".$searchDetailsArr['categoryId'];

	    if( !empty($searchDetailsArr['subCategoryId']) )
		$whereStmt .= " AND A.sub_category_id in (".$searchDetailsArr['subCategoryId'].")";
		
	    if( !empty($searchDetailsArr['ldbCourseId']) )
		$whereStmt .= " AND A.LDB_course_id = ".$searchDetailsArr['ldbCourseId'];

	    if( !empty($searchDetailsArr['countryId']) )
		$whereStmt .= " AND A.country_id = ".$searchDetailsArr['countryId'];

	    if( !empty($searchDetailsArr['stateId']) )
		$whereStmt .= " AND A.state_id in (".$searchDetailsArr['stateId'].")";

	    if( !empty($searchDetailsArr['cityId']) )
		$whereStmt .= " AND A.city_id in (".$searchDetailsArr['cityId'].")";

		$orderByStmt = " ORDER BY A.count DESC";

		if(!empty($searchDetailsArr['limit']))
		$limitStmt .= " LIMIT ".$searchDetailsArr['limit'];			
				
	 	$sql  = $selectStmt. " FROM ". $fromStmt. " WHERE " .$whereStmt ." ".$orderByStmt." ".$limitStmt;

		$data['state'] = $this->db->query($sql)->result_array();
	    
	    return $data; */
		
	}

	/**
    * function : getNonZeroCategoryCityState
    * param: $searchDetailsArr (array)
    * desc : prepare data for fat Footer
    * type : return array list for City and State urls based on category and subcategory
    * added by akhter
    **/ 
	function getNonZeroCategoryCityState($searchDetailsArr){
		return; /*

		$tablename = $this->getNonZeroCategoryPageResultTableNameCurrentMonth();
  		$fromStmt = $tablename." A ";
        
        if($searchDetailsArr['entityType'] == 'subcat' && $searchDetailsArr['cityId'] == 1){
        	$cityState = " AND A.city_id != 1 AND A.state_id = ".$searchDetailsArr['stateId'];
        }else if($searchDetailsArr['entityType'] == 'subcat' && $searchDetailsArr['cityId'] > 1){
			$cityState = " AND A.city_id not in (".$searchDetailsArr['cityId'].", 1)";
        }else{
        	$cityState = " AND A.city_id in (".$searchDetailsArr['cityId'].")";
        }

		$sql = "Select DISTINCT A.category_id as category_id, A.LDB_course_id as ldbCourseId, A.sub_category_id as sub_category_id, A.city_id as city_id, A.state_id as state_id, A.country_id as country_id, A.exam_value as exam, A.affiliation_value as affiliation, A.fees_value as fees FROM ".$fromStmt."  WHERE A.locality_id = 0 AND A.affiliation_value = 'none' AND A.zone_id = 0 AND A.count > 1 AND A.category_id = ".$searchDetailsArr['categoryId']." AND A.sub_category_id in (".$searchDetailsArr['subCategoryId'].")  AND A.LDB_course_id = ".$searchDetailsArr['ldbCourseId']." AND A.country_id = ".$searchDetailsArr['countryId']. $cityState."  ORDER BY A.count DESC LIMIT 8 ";
		
		$data['city'] = $this->db->query($sql)->result_array();
		if($searchDetailsArr['entityType'] == 'subcat'){
			$data['state'] = $this->getSubCategoryState($searchDetailsArr,$fromStmt);
		}else{
			$data['state'] = $this->getCategoryStateSpecific($searchDetailsArr,$fromStmt);	
		}
	    return $data; */
	}
	
	/**
    * function : getSubCategoryState
    * param: $searchDetailsArr (array)
    * desc : prepare data for fat Footer
    * type : return array list for State urls based on subcategory
    * added by akhter
    **/ 
	function getSubCategoryState($searchDetailsArr,$fromStmt){
		return; /*
		if($searchDetailsArr['entityType'] == 'subcat'){
        	$cityState = " AND A.city_id = 1 AND A.state_id not in (".$searchDetailsArr['stateId'].", 1)";
        }

		$sql= "Select DISTINCT A.category_id as category_id, A.LDB_course_id as ldbCourseId, A.sub_category_id as sub_category_id, A.city_id as city_id, A.city_name as city_name , A.state_id as state_id, A.state_name as state_name, A.country_id as country_id, A.exam_value as exam, A.affiliation_value as affiliation, A.fees_value as fees FROM category_page_non_zero_pages_Apr_2016 A WHERE A.locality_id = 0 AND A.affiliation_value = 'none' AND A.zone_id = 0 AND A.count > 1  AND A.category_id = ".$searchDetailsArr['categoryId']." AND A.sub_category_id in (".$searchDetailsArr['subCategoryId'].") AND A.LDB_course_id = ".$searchDetailsArr['ldbCourseId']." AND A.country_id =  ".$searchDetailsArr['countryId'].$cityState." ORDER BY A.count DESC LIMIT 8";
		return $this->db->query($sql)->result_array(); */
	}


	/**
    * function : getCategoryStateSpecific
    * param: $searchDetailsArr (array)
    * desc : prepare data for fat Footer
    * type : return array list for State urls based on category
    * added by akhter
    **/ 
	function getCategoryStateSpecific($searchDetailsArr,$fromStmt)
	{
		return; /*
		$sql= "Select DISTINCT A.category_id as category_id, A.LDB_course_id as ldbCourseId, A.sub_category_id as sub_category_id, A.city_id as city_id, A.city_name as city_name , A.state_id as state_id, A.state_name as state_name, A.country_id as country_id, A.exam_value as exam, A.affiliation_value as affiliation, A.fees_value as fees FROM category_page_non_zero_pages_Apr_2016 A WHERE A.locality_id = 0 AND A.affiliation_value = 'none' AND A.zone_id = 0 AND A.count > 1 AND A.category_id = ".$searchDetailsArr['categoryId']." AND A.sub_category_id = 1 AND A.LDB_course_id = ".$searchDetailsArr['ldbCourseId']." AND A.country_id =  ".$searchDetailsArr['countryId']."  AND A.state_id != 1 AND A.city_id = 1 ORDER BY A.count DESC LIMIT 8";
		return $this->db->query($sql)->result_array(); */
	}	
}