<?php

class exampagemodel extends MY_Model {
	private $dbHandle = '';
    private $dbHandleMode = '';
	
	private $examPageId;
	private $preview; //for preview option on CMS side, set as 'true' or 'false' as request param in url
	private $status;  //tell if data is to be loaded of status 'live' or 'draft'
	protected $defaultFilters = array('basic', 'home', 'syllabus', 'importantDates', 'result', 'section'); //array keys in which data will be sent to repository
	
	function __construct() {
		parent::__construct('Listing');
		$this->load->helper("shikshautility");
    }
    
    private function initiateModel($mode = "write"){
		if($this->dbHandle && $this->dbHandleMode == 'write') {
		    return;
		}
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }
	
    /*
	 * Get consolidated info for exams
	 */
	public function getData($examName, $preview = 'false') {
		$this->preview = $preview;
		
		$data = $this->_getFilterwiseConsolidatedData($examName);
		return $data;
	}
	
	/*
	 * Returns all of exampage data as fetched from model, divided in different sections with keys mentioned in $defaultFilters
	 */
	public function _getFilterwiseConsolidatedData($examName) {
		$data = array();
		$this->initiateModel('read');   // initiate DB Handle

		foreach($this->defaultFilters as $filter) {
			switch($filter) {
				case 'basic':
					$data['basic'] = $this->_getBasicInfo($examName);
					break;
				
				case 'home':
					$data['home'] = $this->_getHomePageInfo();
					break;
				
				case 'syllabus':
					$data['syllabus'] = $this->_getSyllabus();
					break;
				
				case 'importantDates':
					$data['importantDates'] = $this->_getImportantDates();
					break;
				
				case 'result':
					$data['result'] = $this->_getResult();
					break;
				
				case 'section':
					$data['section'] = $this->_getSection();
					break;
			}
		}
		return $data;
	}
	
	/*
	 * Get exampage basic information from master table, along with grade type and discussion ids
	 */
	function _getBasicInfo($examName) {
		//Obtaing Read-Mode on DB
		//$this->initiateModel('read');

		$sql =  "SELECT ".
					"em.exampage_id as exampageId, ".
					"emain.name as examName, ".
					"em.exam_id as examId, ".
					"em.category_name as categoryName, ".
					"em.exam_full_form as examFullForm, ".
					"em.exam_article_tags as examArticleTags, ".
					"ecm.client_type as gradeType, ".
					"em.updated as lastModifyDate, ".
					"em.updated_by as lastModifedBy, ".
					"ed.discussion_ids as discussionIds, ".
					"ec.tile_link as tileLink, ".
					"em.status as status ".
			    "FROM ".
								"exampage_master as em ".
					"LEFT JOIN 	exampage_main emain ON em.exam_id = emain.id ".
					"LEFT JOIN 	exampage_clienttype_mapping ecm ON emain.name = ecm.exam_name ".
					"LEFT JOIN 	exampage_discussion ed ON em.exampage_id = ed.exampage_id AND ed.status = ? ".
					"LEFT JOIN 	exampage_college ec ON em.exampage_id = ec.exampage_id AND ec.status = ? ".
				"WHERE ".
					"em.status = ? AND emain.name = ?";
		
		//if preview is set to true, consider status as 'draft'
		if(isset($this->preview) && $this->preview == 'true') {
			$result = $this->db->query($sql, array('draft', 'draft', 'draft', $examName));
			$this->status = 'draft';
		}
		
		//if preview is not set, or is set to false, or if no data is found in 'draft' state, then fetch data with status 'live'
		if(!isset($this->preview) || $this->preview == 'false' || (!empty($result) && $result->num_rows() <= 0)) {
			$result = $this->dbHandle->query($sql, array('live', 'live', 'live', $examName));
			$this->status = 'live';
		}

		$resultSet = $result->result_array();
		
		if(!empty($resultSet)) {
			//set exampageId
			$this->examPageId = $resultSet[0]['exampageId'];
		}
		
		return $resultSet[0]; //returns empty data set if data is not found in either 'live' or 'draft'
	}
	
	/*
	 * Get home page information
	 */
	function _getHomePageInfo() {
		$examPageId =  $this->examPageId;
		if(empty($examPageId)) {
			return false;
		}
		$sql =  "SELECT label, description, `order`, show_expanded as expansionStatus, type ".
			    "FROM exampage_home ".
                "WHERE status = ? ".
				"AND exampage_id = ? ";
		
		$result = $this->dbHandle->query($sql, array($this->status,$examPageId))->result_array();
		
		return $result;
	}
	
	/*
	 * Get syllabus information
	 */
	function _getSyllabus() {
		$examPageId =  $this->examPageId;
		if(empty($examPageId)) {
			return false;
		}
		$sql =  "SELECT syllabus_content as description, show_expanded as expansionStatus ".
			    "FROM exampage_syllabus ".
                "WHERE status = ? ".
				"AND exampage_id = ?";
		
		$result = $this->dbHandle->query($sql, array($this->status,$examPageId))->result_array();
		$result[0]['label'] = 'Syllabus';
		$result[0]['order'] = 1;
		$result[0]['type'] = 'fixed';
		
		return $result;
	}
	
	/*
	 * Get important dates information
	 */
	function _getImportantDates() {
		$examPageId =  $this->examPageId;
		if(empty($examPageId)) {
			return false;
		}
		$sql =  "SELECT exam_from_date as startDate, exam_to_date as endDate, event_name as eventName, article_id as articleId ".
			    "FROM exampage_dates ".
                "WHERE status = ? AND exampage_id = ? ".
				"ORDER BY startDate";
		
		$result = $this->dbHandle->query($sql, array($this->status,$examPageId))->result_array();
		
		return $result;
	}
	
	/*
	 * Get results data
	 */
	function _getResult() {
		$examPageId =  $this->examPageId;
		if(empty($examPageId)) {
			return false;
		}
		$sql =  "SELECT exam_from_result_date as startDate, exam_to_result_date as endDate, event_name as eventName, article_id as articleId, exam_analysis, ".
						"exam_analysis_show_expanded, exam_reaction, exam_reaction_show_expanded ".
			    "FROM exampage_result ".
                "WHERE status = ? AND exampage_id = ?";
		
		$result['basic'] = $this->dbHandle->query($sql, array($this->status,$examPageId))->result_array();
		
		$sql =  "SELECT interview as description, `order`, show_expanded as expansionStatus ".
			    "FROM exampage_interview ".
                "WHERE status = ? AND exampage_id = ? ".
				"ORDER BY `order`";
		
		$result['interview'] = $this->dbHandle->query($sql, array($this->status,$examPageId))->result_array();
		
		$result = $this->formatResultData($result);
		return $result;
	}
	
	/*
	 * Format data from 'result' section query, to get date, interview and other fields separately
	 */
	function formatResultData($result) {
		$formattedData['Declaration Date']['startDate'] = $result['basic'][0]['startDate'];
		$formattedData['Declaration Date']['endDate']   = $result['basic'][0]['endDate'];
		$formattedData['Declaration Date']['eventName'] = $result['basic'][0]['eventName'];
		$formattedData['Declaration Date']['articleId'] = $result['basic'][0]['articleId'];
		
		$formattedData['Exam Analysis']['label'] 			= 'Result Analysis';
		$formattedData['Exam Analysis']['description'] 	 	= $result['basic'][0]['exam_analysis'];
		$formattedData['Exam Analysis']['order'] 			= 1;
		$formattedData['Exam Analysis']['type'] 		 	= 'fixed';
		$formattedData['Exam Analysis']['expansionStatus']  = $result['basic'][0]['exam_analysis_show_expanded'];
		
		$formattedData['Exam Reaction']['label'] 			= 'Student Reaction';
		$formattedData['Exam Reaction']['description'] 	 	= $result['basic'][0]['exam_reaction'];
		$formattedData['Exam Reaction']['order'] 			= 2;
		$formattedData['Exam Reaction']['type'] 		 	= 'fixed';
		$formattedData['Exam Reaction']['expansionStatus']  = $result['basic'][0]['exam_reaction_show_expanded'];
		
		$formattedData['Topper interview'][]= array();
		
		foreach($result['interview'] as $key => $interview) {
			$formattedData['Topper interview'][$key]['label'] 		  	= 'Topper interview';
			$formattedData['Topper interview'][$key]['description'] 	= $interview['description'];
			$formattedData['Topper interview'][$key]['order'] 		  	= $interview['order'];
			$formattedData['Topper interview'][$key]['expansionStatus'] = $interview['expansionStatus'];
		}
		
		return array($formattedData);
	}
	
	/*
	 * Fetch information about section/tile
	 */
	function _getSection() {
		$examPageId =  $this->examPageId;
		if(empty($examPageId)) {
			return false;
		}
		$sql =  "SELECT section_name as name, section_description as description, section_priority as priority, show_link_in_menu ".
			    "FROM exampage_section_description ".
                "WHERE status = ? AND exampage_id = ? ".
				"ORDER BY priority";
		
		$result = $this->dbHandle->query($sql, array($this->status,$examPageId))->result_array();
		
		foreach($result as $key => $section) {
			$resultFinal[$section['name']] = $section;
		}
		return array($resultFinal);
	}

	/**
	 * get similar exams based on hierarichy and base course using groupId
	 * @param  string $categoryName
	 * @param  string $examName
	 * @return array list of exam names
	 */
	function getSimilarExamsByGroupId($groupId,$excludeExamIds,$filterQuery=0)
	{
		
		//Obtaing Read-Mode on DB
		$this->initiateModel('read');
		if(empty($groupId)) {
			return false;
		}
		if(!empty($excludeExamIds)){
			$excludeExamIdsQuery = " AND eam.examId not in (?)";
			//not in ($excludeGroupIds);
		}else {
			$excludeExamIdsQuery = '';
		}

		$query = "select entityId from examAttributeMapping  where groupId = ? and entityType in ('hierarchy', 'primaryHierarchy') and status = 'live'";

		$hierarchyList = $this->dbHandle->query($query,array($groupId))->result_array();

		$hierarchyIds = array();
		foreach ($hierarchyList as $key => $value) {
			$hierarchyIds[] = $value['entityId'];
		}

		if(!is_array($hierarchyIds) || count($hierarchyIds) <= 0)
		{
			return array();
		}

		if($filterQuery == 0){

			$query = "select entityId from examAttributeMapping  where groupId = ? and entityType='course' and status = 'live'";

			$courseList = $this->dbHandle->query($query,array($groupId))->result_array();

			$baseCourseIds = array();
			foreach ($courseList as $key => $value) {
				$baseCourseIds[] = $value['entityId'];
			}

			if(!is_array($baseCourseIds) || count($baseCourseIds) <= 0)
			{
				return array();
			}


			$sql = "select eam.groupId as groupId,eam.examId as examId from examAttributeMapping eam, exampage_master em where eam.entityId In (?) AND eam.entityType in ('hierarchy', 'primaryHierarchy') AND EXISTS ( select entityId from examAttributeMapping b where b.entityType='course' and eam.groupId=b.groupId and b.status='live' and b.entityId in (?)) and em.groupId = eam.groupId and eam.status = 'live' and em.status = 'live' and  eam.groupId != ? ".$excludeExamIdsQuery;
			$result = $this->dbHandle->query($sql, array($hierarchyIds,$baseCourseIds,$groupId,$excludeExamIds))->result_array();
			
		}else {
			$sql = "select eam.groupId as groupId,eam.examId as examId from examAttributeMapping eam, exampage_master em where eam.entityId In (?) AND eam.entityType in ('hierarchy', 'primaryHierarchy') and em.groupId = eam.groupId and eam.status = 'live' and em.status = 'live' and  eam.groupId != ? ".$excludeExamIdsQuery;
			$result = $this->dbHandle->query($sql, array($hierarchyIds,$groupId,$excludeExamIds))->result_array();
		}
		
		$rs = array();
		foreach ($result as $key => $value) {
			$rs[$value['groupId']] = $value['examId'];
		}
		return $rs;
	}
	
	/**
	 * fetch article according to Exam Id
	 * 
	 * @param string $type whether news or other articles        	
	  * @param string $blogIds 
	  * @param integer $count        	
	 * @param integer $offset        	
	 * @return array
	 */
	function getExamArticles($type,$blogIds, $count = null, $offset = null) {
		// Obtaing Read-Mode on DB
		$this->initiateModel ( 'read' );
		if (empty ( $count )) {
			$limitCond = "";
		} else {
			if (! empty ( $offset )) {
				$limitCond = " limit $offset,$count";
			} else {
				$limitCond = " limit $count";
			}
		}
		
		if($type == 'news'){
			$typeCond = " and blogType = 'news' ";
		}else if($type == 'testPrep'){
			$typeCond = " and blogType = 'testPrep' ";
		}else {
			$typeCond = " and blogType NOT IN ('exam','examstudyabroad') ";
		}
		$sql = "SELECT " . "SQL_CALC_FOUND_ROWS blogTitle, " . 
							"url, " . 
							"summary, " . 
							"lastModifiedDate, " .
							"blogImageURL, " .  
							" blogId " .
							"FROM blogTable " . 
				"WHERE status = 'live' and blogId in (?) $typeCond" . 
				"ORDER BY lastModifiedDate DESC $limitCond";
		$blogIdsArr = explode(',',$blogIds);	
		$result = $this->dbHandle->query ( $sql , array($blogIdsArr))->result_array ();
		
		// query to get total number of results
		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
		$queryTotal = $this->dbHandle->query ( $queryCmdTotal );
		$queryResults = $queryTotal->result ();
		$totalRows = $queryResults [0]->totalRows;
		
		return array (
				'articles' => $result,
				'totalNumRows' => $totalRows 
		);
	}

	/**
	 * get article info from blog table
	 * @param  integer $articleId
	 * @return array
	 */
	function getArticleInfo($articleId){
		//Obtaing Read-Mode on DB
		$this->initiateModel('read');
		
		if(empty($articleId) && !is_numeric($articleId)) {
			return false;
		}

	 	$sql =  "SELECT ".
	 			 "blogTitle, ".
	 			 "url, ".
	 			 "summary, ".
	 			 "creationDate, ".
	 			 "blogImageURL, ".
	 			 "blogId ".
			    "FROM blogTable ".
                "WHERE status = 'live' AND blogId = ?";

        $result = $this->dbHandle->query($sql,$articleId)->row_array();
		return $result;

	}
        
        function getCategoriesWithExamNames() {
            $this->initiateModel('read');
            $examListQuery = "SELECT category_name, exam_name, is_featured ".
                                              "FROM 	 exampage_master ".
                                              "WHERE  status = 'live' ".
                                              "GROUP BY exam_name ".
                                              "ORDER BY ".
                                              "CASE WHEN category_name = 'MBA' THEN FALSE ELSE TRUE END, ".
                                              "category_name asc, ".
                                              "CASE WHEN exam_order = -1 THEN TRUE ELSE FALSE END , ".
                                              "exam_order asc";
            return $this->dbHandle->query($examListQuery,$categoryName)->result_array();
        }


        function updateExamFullForm($key,$value){
        	$this->initiateModel('write');
        	if(!empty($key)){
	        	//$updateQuery = "UPDATE exampage_master SET exam_full_form = '$value' WHERE exam_name = '$key' and status in ('live','draft')";
	        	
	        	$updateData['exam_full_form'] = $value;
	        	$this->dbHandle->where("status in ('live','draft')");
	        	$this->dbHandle->where('exam_name',$key);
	        	return $this->dbHandle->update('exampage_master',$updateData);
	        	//return $this->dbHandle->query($updateQuery);
        	}
        	return false;
        }

        function updateExamArticleTag($key,$value){
        	$this->initiateModel('write');
        	if(!empty($key)){
	        	//$updateQuery = "UPDATE exampage_master SET exam_article_tags = '$value' WHERE exam_name = '$key' and status in ('live','draft')";
	        	
	        	$updateData['exam_article_tags'] = $value;
	        	$this->dbHandle->where("status in ('live','draft')");
	        	$this->dbHandle->where('exam_name',$key);
	        	return $this->dbHandle->update('exampage_master',$updateData);
	        	//return $this->dbHandle->query($updateQuery);
        	}
        	return false;
        }
	
	function getEvents($examIds,$examFilter){
		$this->initiateModel('read');
		$examSubQuery = '';
		if(!empty($examIds)){
			$examSubQuery = ' and epm.id in (?) ';
		}
		$sql = "
			SELECT eam.examId,epm.name as title ,epd.start_date as start, epd.end_date as end, epd.event_name as description, epd.article_id, epm.url, em.groupId, (SELECT entityId FROM examAttributeMapping WHERE status='live' AND entityType='year' and groupId = em.groupId) year, (SELECT isPrimary FROM exampage_groups WHERE status='live' AND groupId = em.groupId) isPrimary  			
			FROM examAttributeMapping eam
			JOIN exampage_main epm on epm.id = eam.examId
			JOIN exampage_master em on em.groupId = eam.groupId
			JOIN exampage_content_dates epd on epd.page_id=em.exampage_id
			WHERE
				eam.entityId = ? and eam.entityType = 'course' and eam.status = 'live' $examSubQuery 
				and EXISTS
					( 	SELECT entityId
        				FROM examAttributeMapping b
        				WHERE
            				b.entityType = 'otherAttribute' and eam.groupId = b.groupId and b.status = 'live' and b.entityId = ?
            		) 
				and epd.status = 'live' and epm.status = 'live' and em.status = 'live' and epd.section_name = 'importantdates' and epd.start_date BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND (CURRENT_DATE() + INTERVAL 3 MONTH) order by epd.start_date ";
		if(!empty($examIds)){
			$result = $this->dbHandle->query($sql,array($examFilter['courseId'],$examIds,$examFilter['educationTypeId']))->result_array();
		}
		else{
			$result = $this->dbHandle->query($sql,array($examFilter['courseId'],$examFilter['educationTypeId']))->result_array();
		}
		return $result;	
	}


	/**
	 * LF-3301
	 * Get a list of exams for a given course.
	 *
	 * @param string $courseName The name of the course for which the list has to be obtained
	 *
	 * @return array The exampage id, the exam name, the exam section description and the earliest next two dates
	 */
	public function getListOfExams($courseName)
	{

		$this->initiateModel('read');

		$sql = "SELECT esd.exampage_id as exampageId, em.exam_name as examName, esd.section_description as examDescription ";
		$sql .= "FROM shiksha.exampage_section_description AS esd INNER JOIN shiksha.exampage_master AS em ";
		$sql .= "ON esd.exampage_id = em.exampage_id ";
		$sql .= "WHERE esd.section_name = 'home' AND em.status = 'live' AND em.category_name = ? ";
		$sql .= "GROUP BY em.exam_name ";
		$sql .= "ORDER BY ";
		$sql .= "CASE WHEN category_name = 'MBA' THEN FALSE ELSE TRUE END, category_name ASC, ";
		$sql .= "CASE WHEN exam_order = -1 THEN TRUE ELSE FALSE END , exam_order ASC";

		$result = $this->dbHandle->query($sql, array($courseName));

		$resultSet = $result->result_array();

		return count($resultSet) > 0 ? $resultSet : false;
	}


	/**
	 * LF-3301
	 * Fetch 2 dates from the exampage_dates table which are greater than today's date and which corresponds to an exampageId
	 *
	 * @param string $exampageIds The comma separated exampage ids
	 * @param int $count The number of results which have to be displayed. Essentially, this controls the MySQL limit for this particular query
	 *
	 * @return array The two dates as fetched from the datastore
	 *
	 */
	public function getDates($exampageIds, $count=2,$sectionName = 'importantdates', $dateCheck ='startDate')
	{   if(empty($exampageIds)){return;}
		$this->initiateModel('read');
		$sql = "SELECT page_id as exampageId, start_date as startDate, end_date as endDate, event_name as description ";
		$sql .= "FROM exampage_content_dates ";
		$sql .= "WHERE status = 'live' AND page_id in (?) ";
		if(!empty($sectionName))
		{
			$sql .= " AND section_name = ? ";
		}
		if($dateCheck == 'endDate'){
			$sql .= " AND start_date < CURDATE()";
			$sql .= " ORDER BY end_date desc";
		}else {
			$sql .= " AND start_date >= CURDATE()";
			$sql .= " ORDER BY start_date ASC ";
		}
//		$sql .= "LIMIT 0 , ?;";
		$exampageIdsArr = explode(',',$exampageIds);
		$dates = $this->dbHandle->query($sql, array($exampageIdsArr, $sectionName))->result_array();

		return count($dates) > 0 ? $dates : false;
	}

	public function getRedirectExams(){
		$this->initiateModel('read');
		$this->dbHandle->select('oldName,newName');
		$this->dbHandle->where('status','live');
		$query = $this->dbHandle->get('exampage_redirects');
		return $query->result_array();
	}

	public function getRedirectExamOfOldExam($oldName){
		$this->initiateModel('read');
		$this->dbHandle->select('oldName, newName');
		$this->dbHandle->where('status', 'live');
		$this->dbHandle->where('oldName', $oldName);
		$this->dbHandle->order_by('id', 'desc');
		$this->dbHandle->limit('1');
		$query = $this->dbHandle->get('exampage_redirects');
		$redirectExams = array();
		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				$redirectExams[$row['oldName']] = $row['newName'];
			}
		}
		return $redirectExams;
	}

	public function getMetaTitleAndDescription($sectionName,$examId){
		$this->initiateModel('read');
		$handle = $this->dbHandle;
		if(empty($sectionName) || empty($examId)){
			return;
		}
		$sql = "SELECT title,description from exampage_metatags where section_name = ? and exampage_id = ?";
		$res = $handle->query($sql,array($sectionName,$examId))->result_array();
		return $res[0];
	}

	public function getExamMappingData($examId, $groupId){
		$this->initiateModel('read');
		$handle = $this->dbHandle;
		if(empty($examId) || empty($groupId)){
			return;
		}
		$sql = "SELECT entityType,entityId from examAttributeMapping  where examId = ? and groupId = ? and entityType in ('primaryHierarchy','course','otherAttribute')and status = 'live'";
		$res = $handle->query($sql,array($examId, $groupId))->result_array();
		return $res;
	}

	function getExamMainUrlsById($examIds = array()){
		$this->initiateModel('read');
		$handle = $this->dbHandle;
		$examMainUrls = array();
        if(count($examIds)>0){
			// $sql = "select distinct em.id,em.url from exampage_main em join exampage_master ems on em.id=ems.exam_id where em.status='live' and ems.status='live' and em.id in (?)";
			$sql = "select distinct em.id,em.name,em.url from exampage_main as em where em.status='live' and em.id in (?)";
			$resultSet = $handle->query($sql,array($examIds))->result_array();
			foreach ($resultSet as $row) {
				// if(!empty($row['url'])){
					$examMainUrls[$row['id']] = array('url'=>$row['url'],'name'=>$row['name']);
				// }
			}
		}
		return $examMainUrls;
	}

	function getExamMappingForURL($examId){
		$this->initiateModel('read');
		$handle = $this->dbHandle;
		if(empty($examId) && !is_numeric($examId)){
			return;
		}
		$sql = "SELECT attr.entityType, attr.entityId FROM examAttributeMapping attr JOIN exampage_groups grp On grp.groupId = attr.groupId 
				WHERE grp.examId = ? 
				AND grp.isPrimary = 1 
				AND attr.entityType IN ('primaryHierarchy','course') 
				AND attr.status = 'live';";
		$res = $handle->query($sql,array($examId))->result_array();
		return $res;
	}

	function getExamMappingForBeaconData($examId){
		if(empty($examId)){
			return;
		}
		$this->initiateModel('read');
		$handle = $this->dbHandle;
		$sql = "SELECT entityType,entityId from examAttributeMapping  where examId = ? and entityType in ('primaryHierarchy','course','otherAttribute','hierarchy')and status = 'live'";
		$res = $handle->query($sql,array($examId))->result_array();
		return $res;
	}

	public function getAllBlogIdsMappedToExamId($examId, $limit = 10){

		$this->initiateModel('read');
		$handle = $this->dbHandle;
		if(empty($examId)){
			return;
		}
		$query = "SELECT bt.blogId as articleId FROM articleAttributeMapping aam, shiksha.blogTable bt WHERE aam.articleId = bt.blogId AND aam.entityId = ? AND aam.entityType = 'exam' AND aam.status = 'live' ORDER BY bt.lastModifiedDate DESC LIMIT $limit";
		$result = $handle->query($query, array($examId))->result_array();
		return $result;
	}

	public function getExamHierarchyMappingData(){
		$this->initiateModel();
		$this->dbHandle->select('ea.examId,ea.groupId,ea.entityId,ea.entityType');
		$this->dbHandle->from('examAttributeMapping ea');
		$this->dbHandle->join('exampage_master epm','epm.groupId = ea.groupId');
		$this->dbHandle->where('ea.status','live');
		$this->dbHandle->where('epm.status','live');
		$this->dbHandle->where_in('entityType',array('primaryHierarchy','hierarchy','course'));
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getExamDetails($examIds = array()){
		if(!(is_array($examIds) && count($examIds) > 0)){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('distinct em.id,em.name');
		$this->dbHandle->from('exampage_main em');
		$this->dbHandle->join('exampage_master epm','epm.exam_id = em.id');
		$this->dbHandle->where_in('em.id',$examIds);
		$this->dbHandle->where('epm.status','live');
		$this->dbHandle->where('em.status','live');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result ;
	}

	public function getExamOrder(){
		$this->initiateModel();
		$this->dbHandle->select('examId,streamId,subStreamId,courseId,exam_order,is_featured');
		$this->dbHandle->from('exampage_order');
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result ;
	}

	function getExamSectionDescription($examId){
		if(empty($examId)){return;}
		$this->initiateModel('read');
		$handle = $this->dbHandle;
		$sql = "SELECT em.exam_id as examId, esd.exampage_id as exampageId, em.exam_name, esd.section_description as examDescription, em.url
                FROM shiksha.exampage_section_description AS esd 
                INNER JOIN exampage_master as em 
                ON esd.exampage_id = em.exampage_id
                where em.exam_id in (?) 
				and esd.section_name = 'home' 
				AND esd.status = 'live'
				AND em.status  = 'live'";
		$examIdArr = explode(',',$examId);
		return $handle->query($sql, array($examIdArr))->result_array();
	}

	 /*This function is the part of migration script. We will remove this function from this file once migration is done on production.*/
        function getExamToBaseCourseMapping(){
                $this->initiateModel('read');
                $query = "select id,examId,entityId from examAttributeMapping where entityType='course' and status='live' order by id asc";
                $result = $this->dbHandle->query($query)->result_array();
                $tempArr = array();
                foreach($result as $key => $section) {
                        if(!array_key_exists($section['examId'],$tempArr)){
                                $tempArr[$section['examId']] = $section['entityId'];
                        }
                }
                return $tempArr;
        }

        /*This function is the part of migration script. We will remove this function from this file once migration is done on production.*/
	
	function createGroupsAndMappingWithExams($mappingData , $flag=false){
                $this->initiateModel('write');
                $this->dbHandle->trans_start();
                $this->addDataIntoExamGroupTable($mappingData);
                $examToGroupMappingArr = $this->getDataFromExamGroupMappingTable($mappingData,$flag);
                $this->updateExamMasterTableWithGroupId($examToGroupMappingArr);
                $this->updateExamAttributeMapping($examToGroupMappingArr);
                $this->dbHandle->trans_complete();
                if ($this->dbHandle->trans_status() === FALSE) {
                        throw new Exception('Transaction Failed');
                }
                echo 'Done';
        }
	
	function addDataIntoExamGroupTable($mappingData){
                $tableName    = 'exampage_groups';
                $dataToInsert = array();
                $count = 0;
                foreach($mappingData as $examId=>$groupName){
                        $dataToInsert[$count]['groupName']        = $groupName;
                        $dataToInsert[$count]['examId']           = $examId;
                        $dataToInsert[$count]['lastModifiedDate'] = date('Y-m-d H:i:s');
                        $dataToInsert[$count]['isPrimary']       = '1';
                        $count++;
                }
                $this->dbHandle->insert_batch($tableName,$dataToInsert);
        }
	
	function getDataFromExamGroupMappingTable($mappingData,$flag){
                $this->initiateModel('read');
                $examArr = array();
                foreach($mappingData as $examId=>$groupName){
                        $examArr[] = $examId;
                }
                if(!empty($examArr)){
                        $examStr = implode(',',$examArr);
                }
                if(!$flag){
                        $query  = "select groupId, examId from exampage_groups where status='live'";
                }else{
                        $query  = "select groupId, examId from exampage_groups where status='live' and examId in ($examStr)";
                }
                $result = $this->dbHandle->query($query)->result_array();
                return $result;
        }

        function updateExamMasterTableWithGroupId($examToGroupMappingArr){
                $this->initiateModel('write');
                $tableName    = 'exampage_master';
                $count = 0;
                foreach($examToGroupMappingArr as $index=>$value){
                        $dataToInsert[$count]['groupId']        = $value['groupId'];
                        $dataToInsert[$count]['exam_id']         = $value['examId'];
                        $count++;
                }
                $this->dbHandle->update_batch($tableName, $dataToInsert, 'exam_id');
        }

        function updateExamAttributeMapping($examToGroupMappingArr){
                $this->initiateModel('write');
                $tableName    = 'examAttributeMapping';
                $count = 0;
                foreach($examToGroupMappingArr as $index=>$value){
                        $dataToInsert[$count]['groupId']        = $value['groupId'];
                        $dataToInsert[$count]['examId']         = $value['examId'];
                        $count++;
                }
                $this->dbHandle->update_batch($tableName, $dataToInsert, 'examId');
        }

	function getHiearchyWithExams(){
		$this->initiateModel('read');
//                $query  = "select distinct(examId),entityId, name from exampage_mains em, examAttributeMapping where em.id = examId and examId not in (select distinct examId from examAttributeMapping where entityType='course' and status='live') and entityType='primaryHierarchy' and status='live'";
		$query = "select distinct(examId),entityId, name from exampage_main em, examAttributeMapping eam where em.id = eam.examId and eam.examId not in (select distinct examId from examAttributeMapping where entityType='course' and status='live') and eam.entityType='primaryHierarchy' and em.status='live' and eam.status='live'";
                $result = $this->dbHandle->query($query)->result_array();
                return $result;

	}

	function migrateExamFullName(){
                $examFullNameInfo = array();
                $examFullNameInfo = $this->getExamFullNameFromExamMasterTable();
                $this->addExamFullNameIntoExamMainTable($examFullNameInfo);
        }

        function getExamFullNameFromExamMasterTable(){
                $this->initiateModel('read');
                $query = "select exam_id, exam_name, exam_full_form, url, exampage_id from exampage_master where status='live' order by exam_id";
                $result = $this->dbHandle->query($query)->result_array();
                return $result;
        }

        function addExamFullNameIntoExamMainTable($examFullNameInfo){
                $this->initiateModel('write');
                $tableName = 'exampage_main';
                $finalData = array();
                $count     = 0;
                foreach($examFullNameInfo as $key=>$value){     
                        $finalData[$count]['id']        = $value['exam_id'];
                        $finalData[$count]['fullName']  = $value['exam_full_form'];
                        $count++;
                }
                $this->dbHandle->update_batch($tableName, $finalData, 'id');
        }

	function updateData(){
		$mappingData = array('12044'=>'Post Graduate Diploma','12995'=>'Design Courses','13174'=>'Post Graduate Diploma','13176'=>'Post Graduate Diploma','13265'=>'Post Graduate Diploma','13381'=>'Design Courses','13547'=>'Dual Degree','13561'=>'Accontancy Courses');
                $this->createGroupsAndMappingWithExams($mappingData);
        }

    function migrateExamContentUrl(){
    	$this->initiateModel('write');
    	$examData = $this->getExamFullNameFromExamMasterTable();
        $tableName    = 'exampage_main';
        $count = 0;
        foreach($examData as $index=>$value){
        	if($value['url']!=''){
                $dataToUpdate[$count]['exampageId']     = $value['exampage_id'];
                $dataToUpdate[$count]['url']            = '/'.$value['url'];
                $count++;
            }
        }
        $this->dbHandle->update_batch($tableName, $dataToUpdate, 'exampageId');
     }
	
     public function getUpdateDetails($examId, $groupId, $count='', $updateIds=array(), $isAmp=false){
        $this->initiateModel('write');
        if(empty($examId) || empty($groupId)){
            return;
        }

        if(!empty($count)){
        	$limit = 'LIMIT 0,'.$count;
        }

        if(!is_array($groupId)){
        	$groupId = explode(',',$groupId);
        }

        if(!empty($updateIds)){ 
        	if(!is_array($updateIds)){
        		$updateIds = explode(',',$updateIds);
        	}
        	$updateFilter = 'AND upm.update_id in (?)';
        }
        

        $sql = "SELECT SQL_CALC_FOUND_ROWS distinct up.id, up.update_text,up.announce_url, up.isMailSent FROM exampage_updates up JOIN exampage_updates_mapping upm ON (up.id = upm.update_id) WHERE up.status = 'live' AND upm.status='live' AND upm.exam_id = ? AND upm.group_id in (?) $updateFilter ORDER BY up.creation_date desc $limit";

        $updateList = $this->dbHandle->query($sql,array($examId, $groupId, $updateIds))->result_array();
        $isAmp = ($isAmp == true) ? true : false;

        foreach($updateList as $key=>$val){

        	if(empty($val['announce_url'])){
        		$updateList[$key]['update_text'] = nl2br(makeURLAsHyperlink(htmlentities($val['update_text']),$isAmp));
        	}
        	else{
        		$updateList[$key]['update_text'] = nl2br(htmlentities($val['update_text']));
        	}
        	$updateList[$key]['announce_url'] = $val['announce_url'];
        }
       
        $result['updateList'] = $updateList;

        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
        $query = $this->dbHandle->query($queryCmd);
        $totalRows = 0;
        foreach ($query->result() as $row) {
              $result['totalUpdates'] = $row->totalRows;
        }

        return $result;
    }
    function getFeaturedExams($examId,$groupId)
    {
    	if(empty($examId) || empty($groupId))
    		return array();
    	
    	$todayDate  = date("Y-m-d");
    	$this->initiateModel();
    	$this->dbHandle->select('dest_exam_id as examId,dest_group_id as groupId,redirection_url,CTA_text');
    	$this->dbHandle->from('exampage_featured_exam');
    	$this->dbHandle->where('orig_exam_id',$examId);
    	$this->dbHandle->where('orig_group_id',$groupId);
    	$this->dbHandle->where('start_date <=',$todayDate);
    	$this->dbHandle->where('end_date >=',$todayDate);
    	$this->dbHandle->where('status','live');
    	$result = $this->dbHandle->get()->result_array();
    	$rs = array();
    	$extraData = array();
    	foreach ($result as $key => $value) {
    		$rs[$value['groupId']] = $value['examId'];
            if(false === strpos($value['redirection_url'], '://')){
                if(false === strpos($value['redirection_url'], 'shiksha.com')){
                    $domain = 'http://';
                }else{
                    $domain = 'https://';
                }
                $value['redirection_url']  = $domain.$value['redirection_url'];
            }
    		$extraData[$value['groupId']] = array('redirection_url' => $value['redirection_url'], 'CTA_text' =>$value['CTA_text']);
    	}
    	return array('rs' => $rs , 'extraData' => $extraData);
    }
    function updateViewCount($groupId)
    {
    	$this->initiateModel('write');
    	$sql = 'update exampage_master set view_count=view_count+1 where groupId= ? and status in ("live","draft")';
    	return $this->dbHandle->query($sql,array($groupId));
    }
    function fetchAllExamViewCount($type=array(),$durationInDays="365")
    {

        if(empty($type)) 
            return array();

        $this->initiateModel('read');
        $sql = "SELECT listing_id, SUM( no_Of_Views ) AS view_count
                FROM view_Count_Details
                WHERE DATE_SUB( CURDATE() , INTERVAL ".$durationInDays." DAY ) <= view_Date
                AND listingType IN ('".implode("','", $type)."')
                GROUP BY listing_id
                ORDER BY view_count DESC ";


        $query = $this->dbHandle->query($sql);
        $result = $query->result_array();
        
        $finalResult = array();
        foreach ($result as $key => $value) {
            $finalResult[$value['listing_id']] = $value['view_count'];
        }
        return $finalResult;
    
    }
    /**
    * below function is used for get all exam id those are in live state
    */
    function getExamIds()
    {
    	$this->initiateModel('read');
    	$this->dbHandle->select('id as examId');
    	$this->dbHandle->from('exampage_main');
    	$this->dbHandle->where('status','live');
    	$result = $this->dbHandle->get()->result_array();
    	$rs = array();
    	foreach ($result as $key => $value) {
    		$rs[] = $value['examId'];
    	}
    	return $rs;
    }

	function getCourseIdByGroupId($examId,$groupId){
    	if(empty($examId) || empty($groupId)){
    		return;
    	}
		$this->initiateModel('read');
    	$this->dbHandle->select('courseId');
    	$result = $this->dbHandle->get_where('exampage_applyOnline',array('examId'=>$examId,'groupId'=>$groupId))->result_array();
    	return $result[0]['courseId'];
    }
    /**
    * @param $examIds = array of examIds 
    * @return array : consist of key value pair (i.e key is examId and value is examPageId)
    */
    function getPrimaryExamPageIdsForExams($examIds)
    {
    	if(!is_array($examIds) || count($examIds) == 0)
    	{
    		return array();
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('epm.exampage_id,epm.exam_id');
    	$this->dbHandle->from('exampage_master epm');
    	$this->dbHandle->join('exampage_groups eg','epm.groupId = eg.groupId');
    	$this->dbHandle->where('epm.status','live');
    	$this->dbHandle->where('eg.status','live');
    	$this->dbHandle->where('eg.isPrimary',1);
    	$this->dbHandle->where_in('epm.exam_id',$examIds);
    	$result = $this->dbHandle->get()->result_array();

    	$rs = array();

    	foreach ($result as $key => $value) {
    		$rs[$value['exam_id']] = $value['exampage_id'];
    	}
    	return $rs;
    }	

    // use only one time for ep migration
    function getExamIdByExampageId(){
    	$this->initiateModel('read');
    	$sql = "SELECT em.id, em.exampageId from exampage_main em, exampage_redirects er where em.exampageId = er.examPageId";
    	$res = $this->dbHandle->query($sql)->result_array();
    	foreach ($res as $key => $value) {
    		$data[$value['id']] = $value['exampageId'];
    	}
    	return $data;
    }
    // use only one time for ep migration
    function updateExamIdByExampageId($examId, $examPageId){
    	$this->initiateModel('write');
    	$sql = "update exampage_redirects set examId = ? where examPageId = ?";
    	$this->dbHandle->query($sql,array($examId, $examPageId));
    }
	function getAllExamsInLiveStatus()
    {
    	$this->initiateModel('read');
    	$this->dbHandle->select('id');
    	$this->dbHandle->from('exampage_main');
    	$this->dbHandle->where('status','live');
    	$result = $this->dbHandle->get()->result_array();
    	$rs = array();
    	foreach ($result as $key => $value) {
    		$rs[] = $value['id'];
    	}
    	return $rs;
    }

    function getNonEducationModeExam(){
    	$this->initiateModel('read');
    	$sql = "select em.id, epm.groupId, em.name FROM exampage_main em JOIN exampage_master epm ON epm.exam_id = em.id WHERE em.status = 'live'
         AND epm.status = 'live'
         AND em.id NOT in (select distinct
             (examId)
         from
             examAttributeMapping
         where
             entityType = 'otherAttribute'
                 and status = 'live');";
    	$res = $this->dbHandle->query($sql)->result_array();
    	foreach ($res as $key => $value) {
    		$data[$value['id']] = $value['groupId'];
    	}
    	return $data;	
    }
	
	function addEductionMode($attrData){
		$this->initiateModel('write');
		$this->dbHandle->insert_batch('examAttributeMapping', $attrData);
	}    

    function removeExtraSpace($table, $examId){
        $this->initiateModel('write');
        if($examId > 0){
            $sqlUpdate = " AND em.exam_id = '$examId' ";
        }
        $sql = "SELECT em.exampage_id,em.exam_id,em.groupId,ec.id,ec.section_name,ec.entity_type,ec.entity_value from exampage_master em, $table ec where em.exampage_id = ec.page_id AND em.status IN ('live','draft') AND ec.status IN ('live','draft') $sqlUpdate";
        error_log("Query == $sql");
        $res = $this->dbHandle->query($sql)->result_array();
        foreach ($res as $valArray) {
                $contentId = $valArray['id'];
                error_log("Section Name::::".$valArray['section_name']."::::".$valArray['entity_type']);
                error_log("Value to be Fixed::::".$valArray['entity_value']);
                $contentValue = $this->fixEPValue($valArray['entity_value']);
                error_log("FIXED VALUE::::::::::::::::$contentValue");
                $sqlQueryUpdate = "UPDATE $table SET entity_value = ? WHERE id = ?";
                $this->dbHandle->query($sqlQueryUpdate, array($contentValue,$contentId));
        }
    }

    function fixEPValue($value){
        $value = str_replace('&lt;p&gt;&nbsp;&nbsp;&lt;/p&gt;','',$value);
        $value = str_replace('&lt;p&gt;&nbsp;&lt;/p&gt;','',$value);
        $value = str_replace('&lt;p&gt;&lt;/p&gt;','',$value);
        $value = str_replace('<p class="f14__clr3"></p>','',$value);
        $value = str_replace('<p class="f14__clr3">&nbsp;</p>','',$value);
        $value = str_replace('<p>&nbsp;&nbsp;</p>','',$value);
        $value = str_replace('<p>&nbsp;</p>','',$value);
        $value = str_replace('<p></p>','',$value);
        return $value;
    }

    function convertRelativeURLToAbsoluteURL($table, $examId){
        $this->initiateModel('write');
        if($examId > 0){
            $sqlUpdate = " AND em.exam_id = '$examId' ";
        }
        $sql = "SELECT em.exampage_id,em.exam_id,em.groupId,ec.id,ec.section_name,ec.entity_type,ec.entity_value from exampage_master em, $table ec where em.exampage_id = ec.page_id AND em.status IN ('live','draft') AND ec.status IN ('live','draft') $sqlUpdate";
        error_log("Query == $sql");
        $res = $this->dbHandle->query($sql)->result_array();
        foreach ($res as $valArray) {
                $contentId = $valArray['id'];
                error_log("Section Name::::".$valArray['section_name']."::::".$valArray['entity_type']);
                error_log("Value to be Fixed::::".$valArray['entity_value']);
                $contentValue = $this->fixURLValue($valArray['entity_value']);
                error_log("FIXED VALUE::::::::::::::::$contentValue");
		if($contentValue != $valArray['entity_value']){
	                $sqlQueryUpdate = "UPDATE $table SET entity_value = ? WHERE id = ?";
        	        $this->dbHandle->query($sqlQueryUpdate, array($contentValue,$contentId));
		}
        }
    }

    function fixURLValue($value){
        $value = str_replace(' href="/',' href="https://www.shiksha.com/',$value);
        $value = str_replace(' href = "/',' href = "https://www.shiksha.com/',$value);
        $value = str_replace(' href ="/',' href ="https://www.shiksha.com/',$value);
        $value = str_replace(' href= "/',' href= "https://www.shiksha.com/',$value);
        $value = str_replace(" href='/"," href='https://www.shiksha.com/",$value);
        $value = str_replace(" href = '/"," href = 'https://www.shiksha.com/",$value);
        $value = str_replace(" href ='/"," href ='https://www.shiksha.com/",$value);
        $value = str_replace(" href= '/"," href= 'https://www.shiksha.com/",$value);
        return $value;
    }

    function getExamRankingPageId($examId){
    	if(empty($examId)){
    		return;
    	}
    	$this->initiateModel('read');
        $sql = "select ranking_page_id from ranking_non_zero_pages where exam_id = ? and status = 'live' order by result_count desc limit 1";
        $res = $this->dbHandle->query($sql,array($examId))->result_array();
        return $res[0]['ranking_page_id'];
    }

    function getExamYearsValue(){
        $this->initiateModel('read');
        $sql = "SELECT e.id, e.name, ea.entityId as year FROM exampage_main e, exampage_groups g, examAttributeMapping ea WHERE e.id=g.examId AND e.status='live' AND g.status='live' AND g.isPrimary=1 AND g.groupId = ea.groupId AND ea.entityType='year' and ea.status='live';";
        $res = $this->dbHandle->query($sql)->result_array();
        return $res;
    }
    function getUpcomingExamDates($entityIds,$eventType,$limit){
    	$this->initiateModel('read');
    	if(empty($limit))
    		$limit = 5;
    	$sql = 'select epm.exam_id,epd.eventCategory eventCategory,epd.page_id,epm.groupId,min(CONCAT((epd.end_date),",",epd.start_date,",",epd.event_name)) as endDate_and_eventName 
    		from 
    		examAttributeMapping eam inner join exampage_master epm 
    		on 
    		eam.examId = epm.exam_id and eam.status = "live" and epm.status = "live" and eam.groupId = epm.groupId 
    		inner join exampage_groups epg 
    		on 
    		eam.groupId = epg.groupId and epg.status = "live" and epg.isPrimary = 1 
    		inner join exampage_content_dates epd 
    		on 
    		epd.page_id = epm.exampage_id and epd.status = "live" 
    		where 
    		eam.entityId IN (?) and eam.entityType IN (?) and epd.end_date >= current_date() 
    		group by epm.exam_id 
    		order by endDate_and_eventName asc 
    		limit ?';
    	$result = $this->dbHandle->query($sql , array($entityIds,$eventType,$limit))->result_array();
 		return $result;
    }
    function getExamDatesForMEAMailer($hierIds, $groupIds){
    	$NO_OF_DAYS = 14;
    	$this->initiateModel('read');
    	$this->dbHandle->select('ed.id, epmain.name examName, attr.entityId hier, epm.groupId, epg.groupName, ed.event_name, ed.start_date, ed.end_date, epmain.url', false);
    	$this->dbHandle->from('exampage_master epm');
    	$this->dbHandle->join('exampage_main epmain', 'epm.exam_id = epmain.id', 'inner');
    	$this->dbHandle->join('exampage_groups epg', 'epm.groupId = epg.groupId', 'inner');
    	$this->dbHandle->join('exampage_content_dates ed', 'ed.page_id = epm.exampage_id', 'inner');
    	$this->dbHandle->join('examAttributeMapping attr', 'attr.groupId = epg.groupId', 'inner');
    	$this->dbHandle->where('ed.status', 'live');
    	$this->dbHandle->where('epm.status', 'live');
    	$this->dbHandle->where('epmain.status', 'live');
    	$this->dbHandle->where('epg.status', 'live');
    	$this->dbHandle->where('attr.status', 'live');
    	$this->dbHandle->where('ed.eventCategory !=', '11');
    	$this->dbHandle->where('attr.entityType', 'primaryHierarchy');
    	$this->dbHandle->where_in('attr.entityId', $hierIds);
    	$this->dbHandle->where_in('epg.groupId', $groupIds);
    	$this->dbHandle->where('(ed.start_date >= CURRENT_DATE() and ed.start_date < (CURRENT_DATE() + INTERVAL '.$NO_OF_DAYS.' DAY) or ed.end_date >= CURRENT_DATE() and ed.end_date < (CURRENT_DATE() + INTERVAL '.$NO_OF_DAYS.' DAY))', '', false);
    	$this->dbHandle->order_by('start_date, ed.end_date');
    	return $this->dbHandle->get()->result_array();
    }

}
