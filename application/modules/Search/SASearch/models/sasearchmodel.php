<?php
class sasearchmodel extends MY_Model {
	private $dbHandle = '';
	private $staticSearchCountBoundary = 0;
   
    function __construct(){
		parent::__construct('Search');
		//$this->CI = &get_instance();
    }
	
	private function initiateModel($mode = "write"){
		$this->dbHandle = NULL;
		if($mode == 'read'){
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
	}
    /*
	 * functionto get all specializations of a course
	 */
    public function getSpecializationsByCourseId($courseIds = array())
	{
		if(count($courseIds)==0)
		{
			return false;
		}
		$this->initiateModel("read");
		$this->dbHandle->select('cctlcm.clientCourseID, cctlcm.LDBCourseID, tcsm.SpecializationName');
		$this->dbHandle->from("clientCourseToLDBCourseMapping cctlcm");
		$this->dbHandle->join("tCourseSpecializationMapping tcsm", "cctlcm.ldbCourseId = tcsm.SpecializationId and tcsm.status = cctlcm.status", "inner" );
		$this->dbHandle->where_in("cctlcm.clientCourseID",$courseIds);
		$this->dbHandle->where("cctlcm.status","live");
		$this->dbHandle->where("tcsm.isEnabled",1);
		$this->dbHandle->where("tcsm.scope","abroad");
		$this->dbHandle->where("tcsm.SpecializationName != 'All'","",false);
		$result = $this->dbHandle->get()->result_array();
		//_p($result);die;
		return $result;
	}
	/*
	 * function to get all university ids
	 */
	public function getAllAbroadUniversitiesWithCourses( $univIds = array() )
	{
		$this->initiateModel("read");
		$this->dbHandle->select('distinct course_id, university_id',false);
		$this->dbHandle->from("abroadCategoryPageData");
		$this->dbHandle->where("status","live");
		if(count($univIds)>0)
		{
			$this->dbHandle->where_in("university_id",$univIds);
		}
		$result = $this->dbHandle->get()->result_array();
		// echo $this->dbHandle->last_query();
		$resArray = array();
		foreach($result as $row)
		{
			if(!is_array($resArray[$row['university_id']]))
			{
				$resArray[$row['university_id']] = array();
			}
			$resArray[$row['university_id']][] = $row['course_id'];
		}
		return $resArray;
	}
	/*
	 * function to get country continent mappings
	 */
	public function getCountryContinentMappings()
	{
		$this->initiateModel("read");
		$this->dbHandle->select('ctry.countryId, ctnt.continent_id as continentId, ctnt.name as continentName',false);
		$this->dbHandle->from("continentTable ctnt");
		$this->dbHandle->join("countryTable ctry","ctnt.continent_id = ctry.continent_id and ctry.countryId >2","inner");
		$result = $this->dbHandle->get()->result_array();
		$resArray = array();
		foreach($result as $row)
		{
			$resArray[$row['countryId']] = array('id'=>$row['continentId'],'name'=>$row['continentName']);
		}
		//_p($resArray);die;
		return $resArray;
	}

	public function getSpecializationData($specializationIds){
		if(empty($specializationIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('t.SpecializationName name');
		$this->dbHandle->select('t.SpecializationId id');
		$this->dbHandle->select('t.CategoryId categoryId');
		$this->dbHandle->select('m.categoryId subcategoryId');
		$this->dbHandle->from('tCourseSpecializationMapping t');
		$this->dbHandle->join('LDBCoursesToSubcategoryMapping m','t.SpecializationId = m.ldbCourseID','inner');
		$this->dbHandle->where('m.status','live');
		$this->dbHandle->where('t.scope','abroad');
		$this->dbHandle->where('t.isEnabled','1');
		$this->dbHandle->where_in('t.SpecializationId',$specializationIds);
		$res = $this->dbHandle->get()->result_array();
		return $res;
	}

	public function getCategorySubcategoryNames($ids){
		if(empty($ids)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('name');
		$this->dbHandle->select('boardId');
		$this->dbHandle->where_in('boardId',$ids);
		$res = $this->dbHandle->get('categoryBoardTable')->result_array();
		$result = array();
		foreach($res as $row){
			$result[$row['boardId']] = $row['name'];
		}
		return $result;	

	}

	public function getSubcategoryData($subcatIds){
		if(empty($subcatIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('t.SpecializationName');
		$this->dbHandle->select('t.SpecializationId');
		$this->dbHandle->select('m.categoryId subcategoryId');
		$this->dbHandle->from('tCourseSpecializationMapping t');
		$this->dbHandle->join('LDBCoursesToSubcategoryMapping m','t.SpecializationId = m.ldbCourseID','inner');
		$this->dbHandle->where_in('m.categoryId',$subcatIds);
		$this->dbHandle->where('m.status','live');
		$this->dbHandle->where('t.scope','abroad');
		$this->dbHandle->where('t.isEnabled','1');
		return $this->dbHandle->get()->result_array();
	}

	public function getSubcategoriesForCategories($categoryIds){
		if(empty($categoryIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('boardId');
		$this->dbHandle->from('categoryBoardTable');
		$this->dbHandle->where_in('parentId',$categoryIds);
		$data = $this->dbHandle->get()->result_array();
		$res = array_map(function($ele){return $ele['boardId'];},$data);
		return $res;
	}

	public function getCityDataByStateIds($stateIds){
		if(empty($stateIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('city_name');
		$this->dbHandle->select('city_id');
		$this->dbHandle->select('state_id');
		$this->dbHandle->where_in('state_id',$stateIds);
		$data = $this->dbHandle->get('countryCityTable')->result_array();
		$this->dbHandle->select('state_name name');
		$this->dbHandle->select('state_id');
		$this->dbHandle->where_in('state_id',$stateIds);
		$namedata = $this->dbHandle->get('stateTable')->result_array();
		$names = array();
		foreach($namedata as $row){
			$names[$row['state_id']] = $row['name'];
		}
		$result = array();
		foreach($data as $row){
			$result[$row['state_id']]['name'] = $names[$row['state_id']];
			$result[$row['state_id']][$row['city_id']]['name'] = $row['city_name'];

		}
		return $result;
	}

	public function getCountryStateStructureByCountryIds($countryIds){
		if(empty($countryIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('state_id stateId');
		$this->dbHandle->select('countryId');
		$this->dbHandle->select('state_name name');
		$this->dbHandle->where_in('countryId',$countryIds);
		return $this->dbHandle->get('stateTable')->result_array();
	}
        
        public function getContinentIdByCountryId($countryIdList) {
            if (empty($countryIdList)) {
                return array();
            }
            $this->initiateModel('read');
            $this->dbHandle->select('continent_id continentId');
            $this->dbHandle->where_in('countryId', $countryIdList);
            return $this->dbHandle->get('countryTable')->result_array();
        }
        
        public function getStateContinentCountryIdByCityStateID($entityList, $entityName = 'city') {
            if (empty($entityList)) {
                return array();
            }
            $this->initiateModel('read');
            $this->dbHandle->select('cct.state_id stateId');
            $this->dbHandle->select('ct.continent_id continentId');
            $this->dbHandle->select('cct.countryId countryId ');
            $this->dbHandle->from('countryCityTable cct');
            $this->dbHandle->join('countryTable ct', 'cct.countryId=ct.countryId');
            if ($entityName == 'city') {
				$this->dbHandle->select('cct.city_id cityId ');
                $this->dbHandle->where_in('cct.city_id', $entityList);
            } else {
				$this->dbHandle->select('cct.state_id stateId ');
                $this->dbHandle->where_in('cct.state_id', $entityList);
            }
            return $this->dbHandle->get()->result_array();
            }
            
            public function getSubCatIdAndCatIdBySpecializationId($specilizationIDList){
                if(!isset($specilizationIDList) || empty($specilizationIDList) || count($specilizationIDList)==0){
                    return array();
                }
                $this->initiateModel('read');
                $this->dbHandle->select('tcsm.categoryId');
                $this->dbHandle->select('lcsm.categoryID subCatId');
                $this->dbHandle->select('tcsm.SpecializationId specializationId');
                $this->dbHandle->select('tcsm.SpecializationName SpecializationName');
                $this->dbHandle->from('tCourseSpecializationMapping tcsm');
                $this->dbHandle->join('LDBCoursesToSubcategoryMapping lcsm','tcsm.SpecializationId = lcsm.ldbCourseId');
                $this->dbHandle->where('lcsm.status','live');
                $this->dbHandle->where('tcsm.courseReach','national');
                $this->dbHandle->where('scope','abroad');
                $this->dbHandle->where('isEnabled','1');
                $this->dbHandle->where_in('tcsm.SpecializationId',$specilizationIDList);
                return $this->dbHandle->get()->result_array();
            }
            public function getCategoryIdBySubCatId($subCatIdList){
                if(empty($subCatIdList)){
                    return array();
                }
                $this->initiateModel('read');
                $this->dbHandle->select('parentId catId');
                $this->dbHandle->from('categoryBoardTable');
                $this->dbHandle->where('flag','studyabroad');
                $this->dbHandle->where_in('boardId',$subCatIdList);
                return $this->dbHandle->get()->result_array();
            }

	public function getCountryNames($countryIds){
		if(empty($countryIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('name, countryId');
		$this->dbHandle->where_in('countryId',$countryIds);
		$res = $this->dbHandle->get('countryTable')->result_array();
		$data = array();
		foreach($res as $row){
			$data[$row['countryId']] = $row['name'];
		}
		return $data;
	}

	public function getCountryCityStructureByCountryIds($countryIds){
		if(empty($countryIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('city_id cityId');
		$this->dbHandle->select('countryId');
		$this->dbHandle->select('city_name name');
		$this->dbHandle->where_in('countryId',$countryIds);
		return $this->dbHandle->get('countryCityTable')->result_array();
	}

	public function getCityDataByIds($cityIds){
		if(empty($cityIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('city_id cityId');
		$this->dbHandle->select('city_name name');
		$this->dbHandle->where_in('city_id',$cityIds);
		$res = $this->dbHandle->get('countryCityTable')->result_array();
		$data = array();
		foreach($res as $row){
			$data[$row['cityId']] = $row['name'];
		}
		return $data;
	}

	public function getContinentCountryStructureByContinentIds($continentIds){
		if(empty($continentIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('countryId, continent_id continentId');
		$this->dbHandle->where_in('continent_id',$continentIds);
		$this->dbHandle->from('countryTable');
		$result = $this->dbHandle->get()->result_array();
		$map = array();
		foreach($result as $row){
			$map[$row['countryId']] = $row['continentId'];
		}
		return $map;
	}
	
	public function getPopularityCountDataForCourses(){
		$this->initiateModel('read');
		$this->dbHandle->select('sum(acrc.responseCount) as totalCount,acrc.`courseId`,acrc.coursePostedDate',false);
		$this->dbHandle->from('abroadCourseResponseCountDetails acrc');
		$this->dbHandle->join('course_details cd',"cd.`course_id`=acrc.`courseId` and cd.status='live' ");
		$this->dbHandle->where("acrc.popularityFlag",1);
		$this->dbHandle->group_by("acrc.courseId");
		//$this->dbHandle->order_by("totalCount");
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		return $result;
	}
	
	public function getListingViewCountAndSubmitDate($listingidsArr,$listingType='course'){
		$this->initiateModel('read');
		$this->dbHandle->select('lm.viewCount,lm.submit_date,lm.listing_type_id',false);
		$this->dbHandle->from('listings_main lm');
		$this->dbHandle->where_in("lm.listing_type_id",$listingidsArr);
		$this->dbHandle->where("lm.listing_type",$listingType);
		$this->dbHandle->where("lm.status",'live');
		//$this->dbHandle->order_by("totalCount");
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		return $result;
	}
	/*
	 * get records from abroad index log 
	 */
	public function getAbroadIndexLogData($status, $startDate = '')
	{
		$this->initiateModel('write');
		$this->dbHandle->select('listingType, listingTypeId, operation');
		$this->dbHandle->from('abroadIndexLog');
		if(!empty($status)){
			$this->dbHandle->where('status',$status);
		}
		if(!empty($startDate)){
			$this->dbHandle->where('addedAt >=',$startDate);
		}
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	/*
	 * get records from abroad index log for scholarship 
	 */
	public function getAbroadScholarshipIndexLogData($status, $startDate = '')
	{
		$this->initiateModel('read');
		$this->dbHandle->select('listingType, listingTypeId, operation');
		$this->dbHandle->from('abroadIndexLog');
		if(!empty($status)){
			$this->dbHandle->where('status',$status);
		}
		if(!empty($startDate)){
			$this->dbHandle->where('addedAt >=',$startDate);
		}
		$this->dbHandle->where('listingType','scholarship');
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}
	/*
	 * update index log whenever indexing starts, ends
	 */
	public function updateAbroadIndexLog($data)
	{
		if(!($data['listingTypeId']>0 || count($data['listingTypeId'])>0))
		{
			return false;
		}
		$this->initiateModel('write');
		$updateArr = array();
		// case : index start 	
		if($data['indexingStartAt'] != "")
		{
			$updateArr['indexingStartAt'] = $data['indexingStartAt'];
			$this->dbHandle->where_in("listingTypeId",$data['listingTypeId']);
			$this->dbHandle->where("listingType",$data['listingType']);
		}
		else{ // end
			$updateArr['indexingEndAt'] = $data['indexingEndAt'];
			$updateArr['status'] = $data['status'];
			$this->dbHandle->where("operation",$data['operation']);
			$this->dbHandle->where("listingType",$data['listingType']);
			$this->dbHandle->where("listingTypeId",$data['listingTypeId']);
		}
		$this->dbHandle->where("status",'pending');
		$this->dbHandle->update('abroadIndexLog',$updateArr);
	}

	public function trackSearchData($trackingData){
		$this->initiateModel("write");
		$this->dbHandle->insert('searchTrackingSA',$trackingData);
		return $this->dbHandle->insert_id();
	}

	public function trackSearchPageInteraction($trackingData){
		$this->initiateModel('write');
		$this->dbHandle->insert('searchPageInteractionTrackingSA',$trackingData);
	}

	public function updateExistingSearchHistory($data){
		$this->initiateModel('write');
		$sql = "update searchTrackingSA set userId = ? where userId = -1 and sessionId = ?";
		$this->dbHandle->query($sql,array($data['userId'],$data['sessionId']));
		$sql = "update SearchPageSortingAppliedTrackingSA set userId = ? where userId = -1 and sessionId = ?";
		$this->dbHandle->query($sql,array($data['userId'],$data['sessionId']));
		$sql = "update SearchPageFiltersAppliedTrackingSA set userId = ? where userId = -1 and sessionId = ?";
		$this->dbHandle->query($sql,array($data['userId'],$data['sessionId']));
	}

	public function getUserSearchHistory($userId){
		$this->initiateModel('read');
		$this->dbHandle->select('max(id) as id,mainSearchBoxText');
		$this->dbHandle->where('userId',$userId);
		$this->dbHandle->order_by('id desc');
        $this->dbHandle->group_by('mainSearchBoxText');
		$res = $this->dbHandle->get('searchTrackingSA')->result_array();
		$result = array_map(function($ele){return htmlentities($ele['mainSearchBoxText']);}, $res);
		return $result;
	}

	public function completeSearchTrack($trackingKeyId,$updateData){
		$this->initiateModel('write');
		$this->dbHandle->where('id',$trackingKeyId);
		$this->dbHandle->where('totalResults','-1');
		$this->dbHandle->update('searchTrackingSA',$updateData);
	}

	public function prepareSearchLayerPrefillData($trackingKeyId){
		$this->initiateModel('read');
		$this->dbHandle->select('mainSearchBoxText, advancedFilterSelection, searchType');
		$this->dbHandle->from('searchTrackingSA');
		$this->dbHandle->where("id",$trackingKeyId);
		return $this->dbHandle->get()->result_array();
	}
        public function getSpecializationNameByID($specializationIds){
            if(is_null($specializationIds) || count($specializationIds)==0){
                return array();
            }
          	$this->initiateModel('read');
          	$this->dbHandle->select('SpecializationName');
                $this->dbHandle->select('SpecializationId id');
		$this->dbHandle->from('tCourseSpecializationMapping');
		$this->dbHandle->where("CourseReach",'national');
                $this->dbHandle->where("Status",'live');
                $this->dbHandle->where("scope",'abroad');
                $this->dbHandle->where_in("SpecializationId",$specializationIds);
                return $this->dbHandle->get()->result_array();
            	
        }
        public function getCategoryIdByName($entityName) {
            if($entityName=="All"){
                return array(array('boardId'=>'All'));
            }
        $this->initiateModel('read');
        $this->dbHandle->select('boardId');
        $this->dbHandle->from('categoryBoardTable');
        $this->dbHandle->where("name", $entityName);
        $this->dbHandle->where("parentId", 1);
        $this->dbHandle->where("flag", 'studyabroad');
        return $this->dbHandle->get()->result_array();
    }

	public function getContinentNames($continents){
		$this->initiateModel('read');
		$this->dbHandle->select('continent_id, name');
		$this->dbHandle->from('continentTable');
		$this->dbHandle->where_in('continent_id',$continents);
		$data = $this->dbHandle->get()->result_array();
		$res = array();
		foreach($data as $row){
			$res[$row['continent_id']] = $row['name'];
		}
		return $res;
	}

	public function trackSortClick($insertData){
		$this->initiateModel('write');
		$this->dbHandle->insert('SearchPageSortingAppliedTrackingSA',$insertData);
	}

	public function getExamIdsWithPages(){
		$this->initiateModel('read');
		$this->dbHandle->select('sac.exam_id as exam_type');
		$this->dbHandle->from('sa_content sac');
		$this->dbHandle->where('sac.type','examContent');
		$this->dbHandle->where('sac.status','live');
		$this->dbHandle->where('sac.is_homepage','1');
		$res = $this->dbHandle->get()->result_array();
		return array_map(function($ele){return reset($ele);}, $res);
	}
	public function trackSearchExecutionData($insertData)
	{
        if(!is_array($insertData) || count($insertData)==0){
             return;
        }
		$this->initiateModel('write');
                if(is_array($insertData[0])){
                    $this->dbHandle->insert_batch('searchExecutionLogSA',$insertData);
                }else{
                    $this->dbHandle->insert('searchExecutionLogSA',$insertData);
                }
		
	}
	public function getQERExecutionData($tid)
	{
        if(!is_numeric($tid) || $tid<0){
                    return array();
        }
		$this->initiateModel('read');
		$this->dbHandle->select('keyword, memoryUsed, url, timeTaken, response');
		$this->dbHandle->from('searchExecutionLogSA');
		$this->dbHandle->where('searchTrackingSAId',$tid);
		$this->dbHandle->where('component','QER');
		$res = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query(); _p($res);die;
		return $res;
	}

	public function trackFilterClick($insertData){
		$this->initiateModel('write');
		$this->dbHandle->insert('SearchPageFiltersAppliedTrackingSA',$insertData);
	}
	
	public function getAbroadStates()
	{
		$this->initiateModel("read");
		$this->dbHandle->select("distinct state_id as stateId",false);
		$this->dbHandle->from("abroadCategoryPageData");
		$this->dbHandle->where("status","live");
		$res = $this->dbHandle->get()->result_array();
		return $res;
	}
	/*
	 * not in use
	 */
	public function getCatSponsorUnivs($univIds) 
	{
		$this->initiateModel("read");
		$this->dbHandle->select("distinct listing_type_id as universityId",false);
		$this->dbHandle->from("tlistingsubscription");
		$this->dbHandle->where("status","live");
		$this->dbHandle->where("listing_type","university");
		$this->dbHandle->where("CURDATE() >= startDate","",false);
		$this->dbHandle->where("CURDATE() <= endDate","", false);
		if(count($univIds)>0){
			$this->dbHandle->where_in("listing_type_id",$univIds);
		}
		$res = $this->dbHandle->get()->result_array();
		$resArr = array();
		foreach($res as $row)
		{
			$resArr[$row['universityId']] = true;
		}
		return $resArr;
	}
	
	public function getCatSponsorUnivsWithDetails($univIds)
	{
		$this->initiateModel("read");
		$this->dbHandle->select("distinct listing_type_id, categoryid , course_level, countryid",false);
		$this->dbHandle->from("tlistingsubscription");
		$this->dbHandle->where("status","live");
		$this->dbHandle->where("listing_type","university");
		$this->dbHandle->where("CURDATE() >= startDate","",false);
		$this->dbHandle->where("CURDATE() <= endDate","", false);
		if(count($univIds)>0){
			$this->dbHandle->where_in("listing_type_id",$univIds);
		}
		$res = $this->dbHandle->get()->result_array();
//		echo $this->dbHandle->last_query();
		$resArr = array();
		foreach($res as $row)
		{
			$resArr[$row['listing_type_id']][] = array(
													 'categoryId'=> $row['categoryid'],
													 'courseLevel'=>$row['course_level'],
													 'countryId'=>$row['countryid']
													);
		}
		return $resArr;
	}
	/*
	 * not in use
	 */
	public function getMainUnivs($univIds)
	{
		$this->initiateModel("read");
		$this->dbHandle->select("distinct listing_type_id as universityId",false);
		$this->dbHandle->from("PageCollegeDb");
		$this->dbHandle->where("status","live");
		$this->dbHandle->where("listing_type","university");
		$this->dbHandle->where("CURDATE() >= StartDate","",false);
		$this->dbHandle->where("CURDATE() <= EndDate","", false);
		if(count($univIds)>0){
			$this->dbHandle->where_in("listing_type_id",$univIds);
		}
		$res = $this->dbHandle->get()->result_array();
		$resArr = array();
		foreach($res as $row)
		{
			$resArr[$row['universityId']] = true;
		}
		return $resArr;
	}
	public function getMainUnivsWithDetails($univIds)
	{
		$this->initiateModel("read");
		$this->dbHandle->select("distinct p.listing_type_id as universityId, tp.countryId, tp.categoryId",false);
		$this->dbHandle->from("PageCollegeDb p",false);
		$this->dbHandle->join("tPageKeyCriteriaMapping tp","p.KeyId = tp.keyPageId","inner",false);
		$this->dbHandle->where("p.status","live");
		$this->dbHandle->where("p.listing_type","university");
		$this->dbHandle->where("CURDATE() >= StartDate","",false);
		$this->dbHandle->where("CURDATE() <= EndDate","", false);
		if(count($univIds)>0){
			$this->dbHandle->where_in("listing_type_id",$univIds);
		}
		$res = $this->dbHandle->get()->result_array();
		$resArr = array();
		foreach($res as $row)
		{
			$resArr[$row['universityId']][] = array(
													 'categoryId'=> $row['categoryId'],
													 'countryId'=>$row['countryId']
													);
		}
		return $resArr;
	}
	/*
	 * function to get univs that were category sponsors & expired lastday
	 * @params: lastDate
	 */
	public function getExpiredCategorySponsorUniv($lastDate)
	{
		$this->initiateModel("read");
		$this->dbHandle->select("distinct listing_type_id as universityId",false);
		$this->dbHandle->from("tlistingsubscription");
		$this->dbHandle->where("status","live");
		$this->dbHandle->where("listing_type","university");
		$this->dbHandle->where("date(endDate)",$lastDate);
		$res = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		$resArr = array_map(function($a){return $a['universityId'];},$res);
		return $resArr;
	}
	
	/*
	 * function to get univs that were main univs & expired lastday
	 * @params: lastDate
	 */
	public function getExpiredMainUniv($lastDate)
	{
		$this->initiateModel("read");
		$this->dbHandle->select("distinct listing_type_id as universityId",false);
		$this->dbHandle->from("PageCollegeDb");
		$this->dbHandle->where("status","live");
		$this->dbHandle->where("listing_type","university");
		$this->dbHandle->where("date(EndDate)",$lastDate);
		$res = $this->dbHandle->get()->result_array();
		$resArr = array_map(function($a){return $a['universityId'];},$res);
		return $resArr;
	}
	/*
	 * function to check which courses (among a given list of courses) have an internal scholarship mapped to them
	 */
	public function checkScholarshipsByCourseId($courseList = array())
	{
		$this->initiateModel("read");
		$this->dbHandle->select("distinct attributeValue as courseId",false);
		$this->dbHandle->from("scholarshipAttributesMapping");
		$this->dbHandle->where("status","live");
		$this->dbHandle->where("attributeType","course");
		$this->dbHandle->where_in("attributeValue",$courseList);
		$res = $this->dbHandle->get()->result_array();
		$resArr = array();
		foreach($res as $v)
		{
			$resArr[$v['courseId']] = true;
		}
		return $resArr;
	}
	/**
	 * get courseIds whose fees currency exch rate was modified in last 24 hours
	 */
	public function getCourseIdsForModifiedFeesCurrencyRate()
	{
		$this->initiateModel("read");
		$this->dbHandle->select("distinct cd.course_id as courseId",false);
		$this->dbHandle->from("currency_exchange_rates cer");
		$this->dbHandle->join("course_details cd","cer.status=cd.status and cer.source_currency_id=cd.fees_unit","inner");
		$this->dbHandle->where("cer.status","live");
		$date24HrsAgo = date('Y-m-d', strtotime('-24 hours', strtotime(date('Y-m-d')))) ;// cron runs once a day
		$this->dbHandle->where('cer.modified>="'.$date24HrsAgo.'"','',false); 
		$res = $this->dbHandle->get()->result_array();
		$result = array();
		foreach($res as $row)
		{
			$result[] = $row['courseId'];
		}
		return $result;
	}

	public function saveSearchStaticUrls($cityData,$stateData){
		$this->initiateModel();

		$this->dbHandle->trans_start();

		//softDelete Search Static Urls 	
		$this->dbHandle->where('status','live');
		$this->dbHandle->update('sa_search_static_pages',array('status' => 'history'));


		// inserting new data
    	$this->dbHandle->insert_batch('sa_search_static_pages',$cityData);
    	$this->dbHandle->insert_batch('sa_search_static_pages',$stateData);

    	$this->dbHandle->trans_complete();
			
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Static Search Transaction Failed');
        }
	}

	
	public function getSearchStaticUrl($locId,$type){
		$this->initiateModel("read");
		$this->dbHandle->select("url");
		$this->dbHandle->from("sa_search_static_pages");		
		$this->dbHandle->where("status","live");
		$this->dbHandle->where("count >",$this->staticSearchCountBoundary);
		if($type == 'city'){
			$this->dbHandle->where("city_id",$locId);			
		}else{
			$this->dbHandle->where("state_id",$locId);							
		}
		$res = $this->dbHandle->get()->row_array();
		return $res['url'];
	}

	public function getSearchStaticParamsByUrl($url){
		$this->initiateModel("read");
		$this->dbHandle->select("city_id as city,state_id as state");
		$this->dbHandle->from("sa_search_static_pages");		
		$this->dbHandle->where("status","live");
		$this->dbHandle->where("count >",$this->staticSearchCountBoundary);
		$this->dbHandle->where("url",$url);					
		$res = $this->dbHandle->get()->row_array();
		if(empty($res['city'])){
			unset($res['city']);
		}
		if(empty($res['state'])){
			unset($res['state']);
		}
		return $res;
	}

	public function getAllSearchStaticUrl(){
		$this->initiateModel("read");
		$this->dbHandle->select("url");
		$this->dbHandle->from("sa_search_static_pages");		
		$this->dbHandle->where("status","live");
		$this->dbHandle->where("count >",$this->staticSearchCountBoundary);
		$res = $this->dbHandle->get()->result_array();
		return $res;
	}
}
