<?php
class searchmatrixmodel extends MY_Model {
	private $dbHandle = '';
	static $searchTypes = array('open','close','interim');
   
    function __construct(){
      parent::__construct('default');
      $this->load->config('nationalCategoryList/nationalConfig');
    }
	
	private function initiateModel($mode = "write", $module = ''){
		if($mode == 'read') {
		    $this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
		    $this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}
	
	public function logSearchQueries($modelParams) {
		$this->initiateModel();
		$data = array (
					"keyword"					=> 	$modelParams['keyword'],
           			"country_id"				=>	$modelParams['country_id'],
           			"city_id"					=>	$modelParams['city_id'],
					"locality_id"				=>	$modelParams['locality_id'],
					"zone_id"					=>	$modelParams['zone_id'],
           			"course_type"				=>	$modelParams['course_type'],
           			"course_level"				=>	$modelParams['course_level'],
           			"search_type"				=>	$modelParams['search_type'],
					"institute_count"			=>	$modelParams['institute_count'],
					"course_count"				=>	$modelParams['course_count'],
					"article_count"				=>	$modelParams['article_count'],
					"question_count"			=>	$modelParams['question_count'],
					"institute_type_result_ids" =>	$modelParams['institute_type_result_ids'],
					"content_type_result_ids" 	=>	$modelParams['content_type_result_ids'],
					"session_id"				=>	$modelParams['session_id'],
					"remote_ip"					=>	$modelParams['remote_ip'],
					"tsr" 						=> 	$modelParams['tsr'],
					"result_step" 				=> 	$modelParams["result_step"],
					"initial_qer" 				=> 	$modelParams["initial_qer"],
					"final_qer" 				=> 	$modelParams["final_qer"],
					"sort_type" 				=> 	$modelParams["sort_type"],
					"suggestion_shown" 			=> 	$modelParams["suggestion_shown"],
					"max_page_id" 				=> 	$modelParams["page_id"],
					"page" 						=> 	$modelParams["page"],
					"loggedin_type" 			=> 	$modelParams["loggedin_type"],
					'source'                    =>	$modelParams["source"]
				);
		
		$queryCmd 			= $this->dbHandle->insert_string('track_searchqueries_newui', $data);
		$query 				= $this->dbHandle->query($queryCmd); 
        $unique_insert_id 	= $this->dbHandle->insert_id();
		return $unique_insert_id;
	}
	
	public function logAutoSuggestQueries($modelParams) {
		$this->initiateModel();
		if(count($modelParams) > 0){
			foreach($modelParams as $param){
				$data = array();
				foreach($param as $key => $value){
					$data[trim($key)] = trim($value);
				}
				if(array_key_exists('user_input', $data) && array_key_exists('user_action', $data)){
					$queryCmd       = $this->dbHandle->insert_string('track_autosuggest', $data);
					$query          = $this->dbHandle->query($queryCmd);
				}
			}
		}
	}
	
	public function updateLogResultClickedStatusById($modelParams){
		$this->initiateModel();
		$queryCmd = "UPDATE track_searchqueries_newui SET result_type = ?, result_clicked_page_id = ?, result_clicked_id = ?, result_clicked_type = ?, result_clicked_row_count = ?  WHERE id = ?;";
		$query = $this->dbHandle->query($queryCmd, array($modelParams['result_type'], $modelParams['page_id'], $modelParams['result_clicked_id'], $modelParams['result_clicked_type'], $modelParams['result_clicked_row_count'], (int)$modelParams['result_search_id']));
		return 1;
	}
	
	public function updateSearchPageId($modelParams){
		$this->initiateModel();
		$queryCmd = "UPDATE track_searchqueries_newui SET max_page_id = ? WHERE id = ?;";
		$query = $this->dbHandle->query($queryCmd, array($modelParams['page_id'], (int)$modelParams['result_search_id']));
		return 1;
	}
	
	public function updateASRows($search_query_id, $session_id){
		$this->initiateModel();
		$queryCmd = "UPDATE track_autosuggest SET search_query_id = ? WHERE session_id = ? AND search_query_id = 0;";
		$query = $this->dbHandle->query($queryCmd, array($search_query_id, $session_id));
		return 1;
	}

	public function trackExamSearch($userId){
		$this->initiateModel();
		$temp = array('url'=>'url','examName'=>'examName','stream'=>'stream');
		foreach ($temp as $columnName => $label) {
			$value = $this->input->post($label,true);
			// filter out non alphanumeric characters in examName
			if($columnName == 'examName' || $columnName == 'typedKeyword'){
				$value = preg_replace("/[^a-zA-Z0-9 ]+/", "", $value);
				if(empty($value)){
					$value = 'EMPTY';
				}
			}
			if(!empty($value)){
				$data[$columnName] = $value;
			}
		}
		$type = $this->input->post('type',true);
		$data['isTrending'] = $this->input->post('isTrending',true);
		if($type == 'exam'){
			$data['type'] = $type;
			$data['examId'] = $this->input->post('examId',true);
		}
		else if($type == 'allexam'){
			$data['type'] = $type.':'.$this->input->post('subType',true);
			$data['examId'] = $this->input->post('examId',true);
		}
		$typedKeyword = $this->input->post('typedKeyword',true);
		$data['typedKeyword'] = empty($typedKeyword) ? NULL : preg_replace("/[^a-zA-Z0-9 ]+/", "", $typedKeyword);
		
		if(!empty($data)){
			$data['referrer'] = $_SERVER['HTTP_REFERER'];
			$data['sessionId'] = getVisitorSessionId();
			if(!empty($userId)){
				$data['userId'] = $userId;
			}
			$data['device'] = 'desktop';
			if(isMobileRequest()){
				$data['device'] = 'mobile';
			}
			$this->dbHandle->trans_start();

			$this->dbHandle->where($data);
			$query = $this->dbHandle->get('searchExamTracking');
			if($query->num_rows() == 0){
				$this->dbHandle->insert('searchExamTracking',$data);
			}

			$this->dbHandle->trans_complete();
			if ($this->dbHandle->trans_status() === FALSE) {
			    throw new Exception('Transaction Failed');
		    }
		}
	}
	
	public function trackCareerSearch($userId){
		$this->initiateModel();
		$temp = array('keyword'=>'keyword','url'=>'url', 'isTrending' => 'isTrending', 'careerId' => 'id');
		foreach ($temp as $columnName => $label) {
			$value = $this->input->post($label,true);
			if(!empty($value)){
				$data[$columnName] = $value;
			}
		}
		
		if(!empty($data)){
			$data['referrer'] = $_SERVER['HTTP_REFERER'];
			$data['sessionId'] = getVisitorSessionId();
			if(!empty($userId)){
				$data['userId'] = $userId;
			}
			$typedKeyword = $this->input->post('typedKeyword',true);
			$data['typedKeyword'] = empty($typedKeyword) ? NULL : $typedKeyword;
			
			$this->dbHandle->where($data);
			$query = $this->dbHandle->get('searchCareerTracking');
			if($query->num_rows() == 0){
				$this->dbHandle->insert('searchCareerTracking',$data);
			}
		}
	}

	public function trackQuestionSearch($userId) {
		$this->initiateModel();

		if(!empty($userId)){
			$data['userId'] = $userId;
		}
		$data['device'] = 'desktop';
		if(isMobileRequest()){
			$data['device'] = 'mobile';
		}
		$data['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
		$data['referrer'] = $_SERVER['HTTP_REFERER'];
		$data['sessionId'] = getVisitorSessionId();
		
		$typedKeyword = $this->input->post('typedKeyword',true);
		$typedKeyword = str_replace("\xef\xbb\xbf", "?", $typedKeyword);
		$data['typedKeyword'] = empty($typedKeyword) ? NULL : preg_replace("/[^a-zA-Z0-9 ]+/", "", $typedKeyword);

		$selectedKeyword = $this->input->post('keyword',true);
		$data['selectedKeyword'] = empty($selectedKeyword) ? NULL : preg_replace("/[^a-zA-Z0-9 ]+/", "", $selectedKeyword);
		
		$data['selectedKeywordId'] = $this->input->post('id',true);
		$data['selectedKeywordType'] = $this->input->post('type',true);
		$data['searchType'] = $this->input->post('searchType',true);
		$data['url'] = $this->input->post('url',true);
		$data['isTrending'] = $this->input->post('isTrending',true);

		$this->dbHandle->where($data);
		$query = $this->dbHandle->get('searchQuestionTracking');
		
		if($query->num_rows() == 0) {
			$this->dbHandle->insert('searchQuestionTracking',$data);
			$id = $this->dbHandle->insert_id();

			return $id;
		} else {
			return '0';
		}
	}

	public function getQuestionTrackingId($data) {
		$this->initiateModel();

		$trackData['sessionId'] = getVisitorSessionId();
		$trackData['userId'] = $data['userId'];
		$trackData['typedKeyword'] = empty($data['keyword']) ? NULL : preg_replace("/[^a-zA-Z0-9 ]+/", "", $data['keyword']);
		$trackData['searchType'] = $data['searchType'];
		
		$this->dbHandle->where($trackData);
		$this->dbHandle->order_by('id desc');
		$query = $this->dbHandle->get('searchQuestionTracking');
		
		$result = $query->result_array();
		$trackId = $result[0]['id'];
		
		return $trackId;
	}

	public function updateQuestionResultCount($trackIds, $count) {
		if($count == NULL) {
			$count = '0';
		}
		$this->initiateModel();
		$data = array();
		$data['resultCount'] = $count;

		$this->dbHandle->where_in('id', $trackIds);
		$this->dbHandle->update('searchQuestionTracking',$data);
	}

	public function getTrackingIdForCourse($data){
		$this->initiateModel();
		$SEARCH_FEES_RANGE = $this->config->item('FEES_RANGE');

		$filtersPossible = array(
			'stream'=>'stream','substream'=>'substream','specialization'=>'specialization','baseCourse'=>'baseCourse',
			'popularGroup'=>'popularGroup','certificateProvider'=>'certificateProvider','city'=>'city','state'=>'state',
			'fees'=>'fees','exams'=>'exams','courseLevel'=>'courseLevel','educationType'=>'educationType',
			'deliveryMethod'=>'deliveryMethod'
		);
		
		$filtervalues = array();
		foreach($filtersPossible as $key => $val){
			${$key} = (!empty($data[$val])) ? $data[$val] : array();
		}
		
		$keyword = $data['searchKeyword'];
		if($data['page'] == 'search'){
			$pageType = 'open';

			if($data['isInterim']) {
				$pageType = 'interim';
			} else {
				$closedSearchTypes = array('substream','stream','baseCourse');
				foreach ($closedSearchTypes as $type) {
					if(!empty(${$type}) && count(${$type}) == 1){
						$entityId = reset(${$type});
						$entityType = $type;
						$pageType = 'close';
						break;
					}
				}
			}
		}
		else if($data['page'] == 'category'){
			$pageType = 'category';
		}
		else if($data['page'] == 'allCourses'){
			$pageType = 'allCourses';
		}
		// $subcategory = (!empty($data['subCategoryId'])) ? $data['subCategoryId'] : NULL;
		$requestFrom = empty($data['requestFrom']) ? NULL : $data['requestFrom'];
		$referrer = (!empty($data['referrer'])) ? $data['referrer'] : $_SERVER['HTTP_REFERER'];

		if(empty($referrer)){
			$referrer = NULL;
		}
		
		$botTraffic = 'no';
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		// detect bot traffic
		global $botsAvoidedForSearch;

		foreach($botsAvoidedForSearch as $bot){
			if(strpos(strtolower($userAgent), $bot) !== false){
				$botTraffic = 'yes';
				break;
			}
		}

		$arr = array(
						'keyword' => $keyword,
						'sessionId' => getVisitorSessionId(),
						'pageType' => $pageType,
						'referrer'=>$referrer,
						'requestFrom'=>$requestFrom,
						'userAgent' => $userAgent,
						'botTraffic' => $botTraffic
					);

		$arr['userId'] = empty($data['userId']) ? NULL : $data['userId'];
		$arr['device'] = isMobileRequest() ? 'mobile' : 'desktop';
		$arr['entityId'] = empty($entityId) ? NULL : $entityId;
		$arr['entityType'] = empty($entityType) ? NULL : $entityType;
		$arr['created_date'] = date('Y-m-d');
		$arr['isTrending'] = ($data['isTrending'] == 1) ? $data['isTrending'] : 0;
		$arr['resultCount'] = (!empty($data['resultCount'])) ? $data['resultCount'] : 0;
		$arr['typedKeyword'] = empty($data['typedKeyword']) ? NULL : $data['typedKeyword'];

		if(!empty($entityType)){
			unset($filtersPossible[$entityType]);
		}

		$this->dbHandle->insert('searchCourseTracking',$arr);
		
		$id = $this->dbHandle->insert_id();
		$arr = array();
		foreach($filtersPossible as $key=>$val){
			if(!empty(${$key})){
				if(is_array(${$key})){
					foreach(${$key} as $keyval){
						$arr1 = array();
						$arr1['searchId'] = $id;
						$arr1['filterType'] = $key;

						if($key == 'fees'){
							$arr1['filterValue'] = $SEARCH_FEES_RANGE[$keyval]['placeholder'];
						}
						else{
							$arr1['filterValue'] = $keyval;
						}
						$arr[] = $arr1;
					}
				}
				else{
					$arr1 = array();
					$arr1['searchId'] = $id;
					$arr1['filterType'] = $key;
					$arr1['filterValue'] = ${$key};
					$arr[] = $arr1;
				}
			}
		}

		if(!empty($arr)){
			$this->dbHandle->insert_batch('searchMultivalueTracking',$arr);
		}
		
		return $id;
	}

	public function updateResultCount($arr){
		$this->initiateModel();
		$data = array();
		$data['resultCount'] = $arr['count'];
		
		if(!empty($arr['newKeyword'])){
			$data['newKeyword'] = $arr['newKeyword'];
		}
		if(!empty($arr['criteriaApplied'])){
			$data['criteriaApplied'] = $arr['criteriaApplied'];
		}
		if(!empty($arr['pageType'])){
			$data['pageType'] = $arr['pageType'];
			$data['entityId'] = $arr['entityId'];
			$data['entityType'] = $arr['entityType'];
		}
		
		$this->dbHandle->where('id',$arr['trackingSearchId']);
		$this->dbHandle->update('searchCourseTracking',$data);
	}

	public function trackInstituteSearch($userId){
		$this->initiateModel();
		$temp = array('keyword'=>'keyword','stream'=>'stream','clientCourse'=>'clientCourse','url'=>'url','cityId'=>'cityId','instituteId'=>'instituteId','isTrending'=>'isTrending');
		foreach ($temp as $columnName => $label) {
			$value = $this->input->post($label);
			if(!empty($value)){
				$data[$columnName] = $value;
			}
		}
		$typedKeyword = $this->input->post('typedKeyword',true);
		$data['typedKeyword'] = empty($typedKeyword) ? NULL : $typedKeyword;

		if(!empty($data)){
			$data['referrer'] = $_SERVER['HTTP_REFERER'];
			$data['sessionId'] = getVisitorSessionId();

			if(!empty($userId)){
				$data['userId'] = $userId;
			}

			$data['device'] = 'desktop';
			if(isMobileRequest()){
				$data['device'] = 'mobile';
			}

			$this->dbHandle->insert('searchInstituteTracking',$data);
		}
	}

	public function trackTupleClick(){
		$this->initiateModel();
		$trackingSearchId = $this->input->post('trackingSearchId');
		if($trackingSearchId){
			$filtersearchid = $this->input->post('trackingFilterId');
			if(empty($filtersearchid)){
				$filtersearchid = 0;
			}
			// if($filtersearchid != 0){
			// 	$this->dbHandle->where('filtersearchid',$filtersearchid);
			// 	$query = $this->dbHandle->get('searchFilterTracking');
			// 	if($query->num_rows() == 0){
			// 		error_log('ERROR: Insert into searchResultTracking failed due to violation of foreign key constraint on filtersearchid to searchFilterTracking');
			// 		return;
			// 	}
			// }
			$tuplenum = explode('|', $this->input->post('tuplenum'));
			$morecourse = '';
			
			$data = array(
							'searchId'=>$this->input->post('trackingSearchId'),
							'pagenum'=>$this->input->post('pagenum'),
							'tuplenum'=>$tuplenum[0],
							'clicktype'=>$this->input->post('clicktype'),
							'listing_type_id'=>$this->input->post('listingtypeid'),
							'filtersearchid'=>$filtersearchid
						);
			if(!empty($tuplenum[1])){
				$data['fromMoreCourses'] = $tuplenum[1];
			}

			$data['device'] = 'desktop';
			if(isMobileRequest()){
				$data['device'] = 'mobile';
				$temp = (($data['pagenum'] - 1)*SEARCH_PAGE_LIMIT_MOBILE+$data['tuplenum'])%(SEARCH_PAGE_LIMIT_MOBILE*4);
				$data['tuplenum'] = (empty($temp)) ? SEARCH_PAGE_LIMIT_MOBILE*4 : $temp;
				$data['pagenum'] = ceil($data['pagenum']/4);
			}

			$this->dbHandle->where($data);
			$query = $this->dbHandle->get('searchResultTracking');
			if($query->num_rows() == 0){
				$this->dbHandle->insert('searchResultTracking',$data);
			}
		}
	}

	public function trackFilterClick($request){
		$searchId = $request->getTrackingSearchId();
		if(empty($searchId)){
			return;
		}

		$data 	  			= (method_exists($request, 'getUserAppliedFilters')) ? $request->getUserAppliedFilters() : $request->getAppliedFilters();
		$searchedAttributes = method_exists($request, 'getSearchedAttributes') ? $request->getSearchedAttributes() : array();
		$this->initiateModel();

		$combinedFilters = array('sub_spec','et_dm');
		foreach ($combinedFilters as $filter) {
			switch ($filter) {
				case 'sub_spec':
					$filterData = array();
					foreach ($data[$filter] as $value) {
						$temp = explode('::', $value);
						$temp1 = explode('_', $temp[0]);
						if($temp1[0] == 'sb'){
							if(!in_array($temp1[1], $searchedAttributes['substream'])){
								if(empty($temp[1])){
									$data['substream'][] = $temp1[1];
								}
								else{
									$filterData[] = $value;
								}
							}
						}
						else{
							$data['specialization'][] = $temp1[1];
						}
					}
					$data['sub_spec'] = $filterData;
					break;
				case 'et_dm':
					foreach ($data[$filter] as $value) {
						$temp = explode('::', $value);
						$temp1 = explode('_', $temp[0]);
						if($temp1[1] == FULL_TIME_MODE && !in_array($temp1[1],$searchedAttributes['education_type'])){
							$data['education_type'][] = $temp1[1];
						}
						else if($temp1[1] == PART_TIME_MODE){
							$temp1 = explode('_', $temp[1]);
							if(!in_array($temp1[1],$searchedAttributes['delivery_method'])){
								$data['delivery_method'][] = $temp1[1];
							}
						}
					}
					$data['et_dm'] = array();
					break;
			}
		}

		foreach($data as $key => $val){
			if(is_array($val) && !empty($searchedAttributes[$key])){
				$data[$key] = array_values(array_diff($data[$key],$searchedAttributes[$key]));
			}
		}
		
		$SEARCH_FEES_RANGE = $this->config->item('FEES_RANGE');

		$trackFilterKey = $this->generateFilterKeyId('searchFilterTracking');
		$filterAttribute = array(
					'stream','substream','specialization','base_course','education_type','delivery_method','credential',
					'level_credential','exam','locality','state','city','fees','popular_group','certificate_provider',
					'course_level','approvals','grants','facilities','college_ownership','accreditation','offered_by_college',
					'course_status','sub_spec','et_dm'
				);

		$arr = array();
		foreach($data as $key => $val){
			if($key == 'stream'){
				if(method_exists($request,'getSingleStreamClosedSearch')){
					$temp = $request->getSingleStreamClosedSearch();
					if(!empty($temp)){
						continue;
					}
				}
			}
			if(!empty($val) && in_array($key, $filterAttribute)){
				$arr1 = array();
				if(is_array($val)){
					foreach($val as $val1){
						$arr1 = array();

						$arr1['filtersearchid'] = $trackFilterKey;
						$arr1['searchId']       = $searchId;
						$arr1['filterType']     = $key;

						if($key == 'fees'){
							$arr1['filterValue'] = $SEARCH_FEES_RANGE[$val1]['placeholder'];
						}
						else{
							$arr1['filterValue'] = $val1;
						}
						$arr1['device'] = (isMobileRequest()) ? 'mobile' : 'desktop';

						$arr[] = $arr1;
					}
				}
			}
		}
	//	_p($arr);die;
		if(!empty($arr)){
			$this->dbHandle->insert_batch('searchFilterTracking',$arr);
			return $trackFilterKey;
		}
		return;
	}

	public function getWhereString($key,$value,$op = '='){
		if(is_array($value)){
			$value = '('.implode(',',$value).')';
			$op = ($op == '=') ? 'in' : $op;
			$op = ($op == '!=') ? 'not in' : $op;
		}
		return $key.' '. $op .' ' . $value;
	}

	public function generateFilterKeyId($key){
		return Modules::run('common/IDGenerator/generateId',$key);
	}

	private function getPageType($pageType){
		switch ($pageType) {
			case 'search':
				return array('open','close','interim');
			case 'close':
				return array('close','interim');
			default:
				return $pageType;
		}
	}

	public function getDayWiseUniqueVisits($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		$pageType = $this->getPageType($data['pageType']);
		switch($data['pageType']){
			case 'open':
			case 'close':
				if(!empty($data['fromDate'])){
					$where[] = $this->getWhereString('created_date','?','>=');$params[] = $data['fromDate'];
				}
				if(!empty($data['toDate'])){
					$where[] = $this->getWhereString('created_date','?','<=');$params[] = $data['toDate'];
				}
				if(!empty($data['device'])){
					$where[] = $this->getWhereString('device','?');$params[] = $data['device'];
				}
				$where[] = $this->getWhereString('botTraffic','?');$params[] = 'no';
				$where[] = $this->getWhereString('resultCount','?','>=');$params[] = 0;
				if(!empty($pageType)){
					if(is_array($pageType)){
						$where[] = $this->getWhereString('pageType',array_fill(0, count($pageType), '?'));$params = array_merge($params,$pageType);
					}
					else{
						$where[] = $this->getWhereString('pageType','?');$params[] = $pageType;
					}
				}
				$sql = "SELECT count(distinct id) as count,created_date as date from searchCourseTracking ";
				if(!empty($where)){
					$sql .= 'where '.implode(' AND ',$where);
				}
				$sql .= ' group by date order by date';
				
				// _p($sql);die;
				$query = $this->dbHandle->query($sql,$params)->result_array();
				// _p($this->dbHandle->last_query());die;
				foreach ($query as $row) {
					$returnData[$row['date']] = $row['count'];
				}
				break;
			case 'career':
				if(!empty($data['fromDate'])){
					$where[] = $this->getWhereString('created','?','>=');$params[] = $data['fromDate'].' 00:00:00';
				}
				if(!empty($data['toDate'])){
					$where[] = $this->getWhereString('created','?','<=');$params[] = $data['toDate'].' 23:59:59';
				}
				$sql = "SELECT count(distinct id) as count,date(created) as date from searchCareerTracking ";
				if(!empty($where)){
					$sql .= 'where '.implode(' AND ',$where);
				}
				$sql .= ' group by date order by date';
				
				$query = $this->dbHandle->query($sql,$params)->result_array();
				foreach ($query as $row) {
					$returnData[$row['date']] = $row['count'];
				}
				break;
			case 'college':
				if(!empty($data['fromDate'])){
					$where[] = $this->getWhereString('created','?','>=');$params[] = $data['fromDate'].' 00:00:00';
				}
				if(!empty($data['toDate'])){
					$where[] = $this->getWhereString('created','?','<=');$params[] = $data['toDate'].' 23:59:59';
				}
				if(!empty($data['device'])){
					$where[] = $this->getWhereString('device','?');$params[] = $data['device'];
				}
				$sql = "SELECT count(distinct id) as count,date(created) as date from searchInstituteTracking ";
				if(!empty($where)){
					$sql .= 'where '.implode(' AND ',$where);
				}
				$sql .= ' group by date order by date';
				
				$query = $this->dbHandle->query($sql,$params)->result_array();
				foreach ($query as $row) {
					$returnData[$row['date']] = $row['count'];
				}
				break;
			case 'exam':
				if(!empty($data['fromDate'])){
					$where[] = $this->getWhereString('created','?','>=');$params[] = $data['fromDate'].' 00:00:00';
				}
				if(!empty($data['toDate'])){
					$where[] = $this->getWhereString('created','?','<=');$params[] = $data['toDate'].' 23:59:59';
				}
				if(!empty($data['device'])){
					$where[] = $this->getWhereString('device','?');$params[] = $data['device'];
				}
				$sql = "SELECT count(distinct id) as count,date(created) as date from searchExamTracking ";
				if(!empty($where)){
					$sql .= 'where '.implode(' AND ',$where);
				}
				$sql .= ' group by date order by date';
				
				$query = $this->dbHandle->query($sql,$params)->result_array();
				foreach ($query as $row) {
					$returnData[$row['date']] = $row['count'];
				}
				break;
		}
		return $returnData;
	}

	public function getDayWiseZrps($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		$pageType = $this->getPageType($data['pageType']);
		switch($data['pageType']){
			case 'open':
			case 'close':
				if(!empty($data['fromDate'])){
					$where[] = $this->getWhereString('created_date','?','>=');$params[] = $data['fromDate'];
				}
				if(!empty($data['toDate'])){
					$where[] = $this->getWhereString('created_date','?','<=');$params[] = $data['toDate'];
				}
				if(!empty($data['device'])){
					$where[] = $this->getWhereString('device','?');$params[] = $data['device'];
				}
				$where[] = $this->getWhereString('botTraffic','?');$params[] = 'no';
				$where[] = $this->getWhereString('resultCount','?');$params[] = 0;
				if(!empty($pageType)){
					if(is_array($pageType)){
						$where[] = $this->getWhereString('pageType',array_fill(0, count($pageType), '?'));$params = array_merge($params,$pageType);
					}
					else{
						$where[] = $this->getWhereString('pageType','?');$params[] = $pageType;
					}
				}
				$sql = "SELECT count(distinct id) as count,created_date as date from searchCourseTracking ";
				if(!empty($where)){
					$sql .= 'where '.implode(' AND ',$where);
				}
				$sql .= ' group by date order by date';
				
				// _p($sql);die;
				$query = $this->dbHandle->query($sql,$params)->result_array();
				// _p($this->dbHandle->last_query());die;
				foreach ($query as $row) {
					$returnData[$row['date']] = $row['count'];
				}
				break;
			case 'career':
				if(!empty($data['fromDate'])){
					$where[] = $this->getWhereString('created','?','>=');$params[] = $data['fromDate'].' 00:00:00';
				}
				if(!empty($data['toDate'])){
					$where[] = $this->getWhereString('created','?','<=');$params[] = $data['toDate'].' 23:59:59';
				}
				$sql = "SELECT count(distinct id) as count,date(created) as date from searchCareerTracking ";
				$where[] = "url is null";
				if(!empty($where)){
					$sql .= 'where '.implode(' AND ',$where);
				}
				$sql .= ' group by date order by date';
				
				$query = $this->dbHandle->query($sql,$params)->result_array();
				foreach ($query as $row) {
					$returnData[$row['date']] = $row['count'];
				}
				break;
			case 'college':
				break;
			case 'exam':
				break;
		}
		return $returnData;
	}

	public function getDayWiseFilterAppliedCounts($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		if(!empty($data['fromDate'])){
			$where[] = $this->getWhereString('sc.created_date','?','>=');$params[] = $data['fromDate'];
		}
		if(!empty($data['toDate'])){
			$where[] = $this->getWhereString('sc.created_date','?','<=');$params[] = $data['toDate'];
		}
		$where[] = $this->getWhereString('sc.botTraffic','?');$params[] = 'no';
		$where[] = $this->getWhereString('sc.resultCount','?','>=');$params[] = 0;
		$pageType = $this->getPageType($data['pageType']);
		switch($data['pageType']){
			case 'open':
			case 'close':
				if(!empty($pageType)){
					if(is_array($pageType)){
						$where[] = $this->getWhereString('pageType',array_fill(0, count($pageType), '?'));$params = array_merge($params,$pageType);
					}
					else{
						$where[] = $this->getWhereString('pageType','?');$params[] = $pageType;
					}
				}
				if(!empty($data['device'])){
					$where[] = $this->getWhereString('sc.device','?');$params[] = $data['device'];
				}
				$sql = "SELECT count(distinct searchId) as count,sc.created_date as date from searchCourseTracking sc join searchFilterTracking sf on sc.id = sf.searchId ";
				if(!empty($where)){
					$sql .= 'where '.implode(' AND ',$where);
				}
				$sql .= ' group by date order by date';
				$query = $this->dbHandle->query($sql,$params)->result_array();
				// _p($this->dbHandle->last_query());die;
				foreach ($query as $row) {
					$returnData[$row['date']] = $row['count'];
				}
				break;
			case 'college':
			case 'career':
			case 'exam':
				break;
		}
		return $returnData;
	}

	public function getDayWiseAdvancedOptionsAppliedCounts($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		$pageType = $this->getPageType($data['pageType']);
		switch($data['pageType']){
			case 'close':
				if(!empty($data['fromDate'])){
					$where[] = $this->getWhereString('sc.created_date','?','>=');$params[] = $data['fromDate'];
				}
				if(!empty($data['toDate'])){
					$where[] = $this->getWhereString('sc.created_date','?','<=');$params[] = $data['toDate'];
				}
				if(!empty($data['device'])){
					$where[] = $this->getWhereString('sc.device','?');$params[] = $data['device'];
				}
				if(!empty($pageType)){
					if(is_array($pageType)){
						$where[] = $this->getWhereString('pageType',array_fill(0, count($pageType), '?'));$params = array_merge($params,$pageType);
					}
					else{
						$where[] = $this->getWhereString('pageType','?');$params[] = $pageType;
					}
				}
				$where[] = $this->getWhereString('sc.botTraffic','?');$params[] = 'no';
				$where[] = $this->getWhereString('sc.resultCount','?','>=');$params[] = 0;

				$sql = "SELECT count(distinct searchId) as count,sc.created_date as date from searchCourseTracking sc join searchMultivalueTracking sm on sc.id = sm.searchId ";
				if(!empty($where)){
					$sql .= 'where '.implode(' AND ',$where);
				}
				$sql .= ' group by date order by date';
				// _p($sql);die;
				$query = $this->dbHandle->query($sql,$params)->result_array();
				// _p($this->dbHandle->last_query());die;
				$query = $this->dbHandle->query($sql,$params)->result_array();
				foreach ($query as $row) {
					$returnData[$row['date']] = $row['count'];
				}
				break;
			case 'college':
				if(!empty($data['fromDate'])){
					$where[] = $this->getWhereString('created','?','>=');$params[] = $data['fromDate']." 00:00:00";
				}
				if(!empty($data['toDate'])){
					$where[] = $this->getWhereString('created','?','<=');$params[] = $data['toDate']." 23:59:59";
				}
				if(!empty($data['device'])){
					$where[] = $this->getWhereString('device','?');$params[] = $data['device'];
				}
				$where[] = "stream is not null";
				$sql = "SELECT count(distinct id) as count,date(created) as date from searchInstituteTracking ";
				if(!empty($where)){
					$sql .= 'where '.implode(' AND ',$where);
				}
				$sql .= ' group by date order by date';
				$query = $this->dbHandle->query($sql,$params)->result_array();
				foreach ($query as $row) {
					$returnData[$row['date']] = $row['count'];
				}
				break;
			case 'exam':
			case 'career':
			case 'open':
				break;
		}
		return $returnData;
	}

	public function getDayWiseFilterCounts($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		if(!empty($data['fromDate'])){
			$where[] = $this->getWhereString('sc.created_date','?','>=');$params[] = $data['fromDate'];
		}
		if(!empty($data['toDate'])){
			$where[] = $this->getWhereString('sc.created_date','?','<=');$params[] = $data['toDate'];
		}
		if(!empty($data['device'])){
			$where[] = $this->getWhereString('sc.device','?');$params[] = $data['device'];
		}
		$where[] = $this->getWhereString('sc.botTraffic','?');$params[] = 'no';
		$where[] = $this->getWhereString('sc.resultCount','?','>=');$params[] = 0;
		$pageType = $this->getPageType($data['pageType']);
		if(!empty($pageType)){
			if(is_array($pageType)){
				$where[] = $this->getWhereString('sc.pageType',array_fill(0, count($pageType), '?'));$params = array_merge($params,$pageType);
			}
			else{
				$where[] = $this->getWhereString('sc.pageType','?');$params[] = $pageType;
			}
		}
		$sql = "SELECT count(distinct searchId) as count,sc.created_date as date,sf.filterType from searchCourseTracking sc join searchFilterTracking sf on sc.id = sf.searchId ";
		if(!empty($where)){
			$sql .= 'where '.implode(' AND ',$where);
		}
		$sql .= ' group by date,sf.filterType order by date';
		
		// _p($sql);die;
		$query = $this->dbHandle->query($sql,$params)->result_array();
		// _p($this->dbHandle->last_query());die;
		foreach ($query as $row) {
			$returnData[$row['filterType']][$row['date']] = $row['count'];
			$returnData['overAll'][$row['date']] = intval($returnData['overAll'][$row['date']]) + $row['count'];
		}
		return $returnData;
	}

	public function getDayWiseTotalFilterCounts($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		if(!empty($data['fromDate'])){
			$where[] = $this->getWhereString('sc.created_date','?','>=');$params[] = $data['fromDate'];
		}
		if(!empty($data['toDate'])){
			$where[] = $this->getWhereString('sc.created_date','?','<=');$params[] = $data['toDate'];
		}
		if(!empty($data['device'])){
			$where[] = $this->getWhereString('sc.device','?');$params[] = $data['device'];
		}
		$where[] = $this->getWhereString('sc.botTraffic','?');$params[] = 'no';
		$where[] = $this->getWhereString('sc.resultCount','?','>=');$params[] = 0;
		$pageType = $this->getPageType($data['pageType']);
		if(!empty($pageType)){
			if(is_array($pageType)){
				$where[] = $this->getWhereString('sc.pageType',array_fill(0, count($pageType), '?'));$params = array_merge($params,$pageType);
			}
			else{
				$where[] = $this->getWhereString('sc.pageType','?');$params[] = $pageType;
			}
		}
		$sql = "SELECT count(distinct(concat(searchId,'::',filterType,'::',filterValue))) as count,sc.created_date as date,sf.filterType from searchCourseTracking sc join searchFilterTracking sf on sc.id = sf.searchId ";
		if(!empty($where)){
			$sql .= 'where '.implode(' AND ',$where);
		}
		$sql .= ' group by date,sf.filterType order by date';
		// _p($sql);die;
		$query = $this->dbHandle->query($sql,$params)->result_array();
		// _p($this->dbHandle->last_query());die;
		foreach ($query as $row) {
			$returnData[$row['filterType']][$row['date']] = $row['count'];
			$returnData['overAll'][$row['date']] = intval($returnData['overAll'][$row['date']]) + $row['count'];
		}
		return $returnData;
	}

	public function getDayWiseTupleClicksAfterFiltersCounts($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		if(!empty($data['fromDate'])){
			$where[] = $this->getWhereString('sc.created_date','?','>=');$params[] = $data['fromDate'];
		}
		if(!empty($data['toDate'])){
			$where[] = $this->getWhereString('sc.created_date','?','<=');$params[] = $data['toDate'];
		}
		if(!empty($data['device'])){
			$where[] = $this->getWhereString('sc.device','?');$params[] = $data['device'];
		}
		$where[] = $this->getWhereString('sc.botTraffic','?');$params[] = 'no';
		$where[] = $this->getWhereString('sc.resultCount','?','>=');$params[] = 0;
		$pageType = $this->getPageType($data['pageType']);
		if(!empty($pageType)){
			if(is_array($pageType)){
				$where[] = $this->getWhereString('sc.pageType',array_fill(0, count($pageType), '?'));$params = array_merge($params,$pageType);
			}
			else{
				$where[] = $this->getWhereString('sc.pageType','?');$params[] = $pageType;
			}
		}
		$sql = "SELECT count(distinct searchId) as count,sc.created_date as date from searchCourseTracking sc join searchResultTracking sr on sc.id = sr.searchId and sr.filtersearchid is not null ";
		if(!empty($where)){
			$sql .= 'where '.implode(' AND ',$where);
		}
		$sql .= ' group by date order by date';
		
		$query = $this->dbHandle->query($sql,$params)->result_array();
		// _p($this->dbHandle->last_query());die;
		foreach ($query as $row) {
			$returnData[$row['date']] = $row['count'];
		}
		return $returnData;
	}

	public function getDayWiseTotalTupleClickCounts($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		if(!empty($data['fromDate'])){
			$where[] = $this->getWhereString('sc.created_date','?','>=');$params[] = $data['fromDate'];
		}
		if(!empty($data['toDate'])){
			$where[] = $this->getWhereString('sc.created_date','?','<=');$params[] = $data['toDate'];
		}
		if(!empty($data['device'])){
			$where[] = $this->getWhereString('sc.device','?');$params[] = $data['device'];
		}
		$where[] = $this->getWhereString('sc.botTraffic','?');$params[] = 'no';
		$where[] = $this->getWhereString('sc.resultCount','?','>=');$params[] = 0;
		$pageType = $this->getPageType($data['pageType']);
		if(!empty($pageType)){
			if(is_array($pageType)){
				$where[] = $this->getWhereString('sc.pageType',array_fill(0, count($pageType), '?'));$params = array_merge($params,$pageType);
			}
			else{
				$where[] = $this->getWhereString('sc.pageType','?');$params[] = $pageType;
			}
		}
		$sql = "SELECT count(distinct(concat(searchId,'::',clicktype,'::',listing_type_id))) as count,sc.created_date as date,sr.clicktype from searchCourseTracking sc join searchResultTracking sr on sc.id = sr.searchId ";
		if(!empty($where)){
			$sql .= 'where '.implode(' AND ',$where);
		}
		$sql .= ' group by date,sr.clicktype order by date';
		// _p($sql);die;
		$query = $this->dbHandle->query($sql,$params)->result_array();
		// _p($this->dbHandle->last_query());die;
		foreach ($query as $row) {
			if(empty($row['clicktype'])){
				continue;
			}
			$returnData[$row['clicktype']][$row['date']] = $row['count'];
			$returnData['overAll'][$row['date']] = intval($returnData['overAll'][$row['date']]) + $row['count'];
		}
		return $returnData;
	}

	public function getDayWiseAdvancedFiltersCounts($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		switch ($data['pageType']) {
			case 'college':
				if(!empty($data['fromDate'])){
					$where[] = $this->getWhereString('created','?','>=');$params[] = $data['fromDate']." 00:00:00";
				}
				if(!empty($data['toDate'])){
					$where[] = $this->getWhereString('created','?','<=');$params[] = $data['toDate']." 23:59:59";
				}
				if(!empty($data['device'])){
					$where[] = $this->getWhereString('device','?');$params[] = $data['device'];
				}
				$where[] = "stream is not null";
				$sql = "SELECT sum(case when stream is null then 0 else 1 end) as streamCount,sum(case when clientCourse is null then 0 else 1 end) as clientCourseCount,date(created) as date from searchInstituteTracking ";
				if(!empty($where)){
					$sql .= 'where '.implode(' AND ',$where);
				}
				$sql .= ' group by date order by date';
				$query = $this->dbHandle->query($sql,$params)->result_array();
				foreach ($query as $row) {
					$returnData['stream'][$row['date']] = $row['streamCount'];
					$returnData['clientCourse'][$row['date']] = $row['clientCourseCount'];
				}
				break;
			case 'course':
				if(!empty($data['fromDate'])){
					$where[] = $this->getWhereString('sc.created_date','?','>=');$params[] = $data['fromDate'];
				}
				if(!empty($data['toDate'])){
					$where[] = $this->getWhereString('sc.created_date','?','<=');$params[] = $data['toDate'];
				}
				if(!empty($data['device'])){
					$where[] = $this->getWhereString('sc.device','?');$params[] = $data['device'];
				}
				$where[] = $this->getWhereString('sc.botTraffic','?');$params[] = 'no';
				$where[] = $this->getWhereString('sc.resultCount','?','>=');$params[] = 0;

				$sql = "SELECT count(distinct(concat(searchId,'::',filterType,'::',filterValue))) as count,sc.created_date as date,sm.filterType from searchCourseTracking sc join searchMultivalueTracking sm on sc.id = sm.searchId ";
				if(!empty($where)){
					$sql .= 'where '.implode(' AND ',$where);
				}
				$sql .= ' group by date,sm.filterType order by date';
				// _p($sql);die;
				$query = $this->dbHandle->query($sql,$params)->result_array();
				// _p($this->dbHandle->last_query());die;
				foreach ($query as $row) {
					$returnData[$row['filterType']][$row['date']] = $row['count'];
					$returnData['overAll'][$row['date']] = intval($returnData['overAll'][$row['date']]) + $row['count'];
				}
				break;
		}
		return $returnData;
	}

	public function getDayWiseCriterionUsed($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		if(!empty($data['fromDate'])){
			$where[] = $this->getWhereString('created_date','?','>=');$params[] = $data['fromDate'];
		}
		if(!empty($data['toDate'])){
			$where[] = $this->getWhereString('created_date','?','<=');$params[] = $data['toDate'];
		}
		if(!empty($data['device'])){
			$where[] = $this->getWhereString('device','?');$params[] = $data['device'];
		}
		$where[] = $this->getWhereString('botTraffic','?');$params[] = 'no';
		$where[] = $this->getWhereString('resultCount','?','>=');$params[] = 0;
		if(!empty($data['pageType'])){
			$where[] = $this->getWhereString('criteriaApplied','?');$params[] = $data['pageType'];
		}
		else{
			$where[] = ' criteriaApplied is not null ';
		}

		$sql = "SELECT count(criteriaApplied) as criteriaCount,sum(case when resultCount=0 then 1 else 0 end) as zrpCount, created_date as date from searchCourseTracking ";
		if(!empty($where)){
			$sql .= 'where '.implode(' AND ',$where);
		}
		$sql .= ' group by date order by date';

		$query = $this->dbHandle->query($sql,$params)->result_array();
		// _p($this->dbHandle->last_query());die;
		foreach ($query as $row) {
			$returnData['criteria'][$row['date']] = $row['criteriaCount'];
			$returnData['zrpCount'][$row['date']] = $row['zrpCount'];
		}
		return $returnData;
	}

	public function getZRPDetails($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		if(!empty($data['fromDate'])){
			$where[] = $this->getWhereString('created_date','?','>=');$params[] = $data['fromDate'];
		}
		if(!empty($data['toDate'])){
			$where[] = $this->getWhereString('created_date','?','<=');$params[] = $data['toDate'];
		}
		if(!empty($data['device'])){
			$where[] = $this->getWhereString('device','?');$params[] = $data['device'];
		}
		$where[] = $this->getWhereString('botTraffic','?');$params[] = 'no';
		$where[] = $this->getWhereString('resultCount','?');$params[] = 0;
		$pageType = $this->getPageType('search');
		if(!empty($pageType)){
			if(is_array($pageType)){
				$where[] = $this->getWhereString('pageType',array_fill(0, count($pageType), '?'));$params = array_merge($params,$pageType);
			}
			else{
				$where[] = $this->getWhereString('pageType','?');$params[] = $pageType;
			}
		}
		$sql = "SELECT group_concat(id) as searchIds,keyword,pageType,criteriaApplied,newKeyword,device,created,count(keyword) as count from searchCourseTracking ";
		if(!empty($where)){
			$sql .= 'where '.implode(' AND ',$where);
		}
		$sql .= ' group by keyword ';
		if(!empty($data['orderBy'])){
			$sql .= " order by ".$data['orderBy']." desc";
		}
		$query = $this->dbHandle->query($sql,$params)->result_array();
		// _p($this->dbHandle->last_query());die;
		return $query;
	}

	public function getCriteriaReductionDetails($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		if(!empty($data['fromDate'])){
			$where[] = $this->getWhereString('created_date','?','>=');$params[] = $data['fromDate'];
		}
		if(!empty($data['toDate'])){
			$where[] = $this->getWhereString('created_date','?','<=');$params[] = $data['toDate'];
		}
		if(!empty($data['device'])){
			$where[] = $this->getWhereString('device','?');$params[] = $data['device'];
		}
		$where[] = $this->getWhereString('botTraffic','?');$params[] = 'no';
		$where[] = $this->getWhereString('resultCount','?','>=');$params[] = 0;
		$pageType = $this->getPageType('search');
		if(!empty($pageType)){
			if(is_array($pageType)){
				$where[] = $this->getWhereString('pageType',array_fill(0, count($pageType), '?'));$params = array_merge($params,$pageType);
			}
			else{
				$where[] = $this->getWhereString('pageType','?');$params[] = $pageType;
			}
		}
		if(empty($data['criteria'])){
			$where[] = ' criteriaApplied is not null';
		}
		else{
			$where[] = $this->getWhereString('criteriaApplied','?');$params[] = $data['criteria'];
		}
		$sql = "SELECT keyword,pageType,criteriaApplied,newKeyword,(case when resultCount=0 then 'yes' else 'no' end) as isZRP,device,created from searchCourseTracking ";
		if(!empty($where)){
			$sql .= 'where '.implode(' AND ',$where);
		}
		$sql .= ' order by created desc';
		$query = $this->dbHandle->query($sql,$params)->result_array();
		// _p($this->dbHandle->last_query());die;
		return $query;
	}

	public function getFiltersAppliedWhenSearched($data){
		$this->initiateModel('read','MISTracking');
		$where = array();$params = array();$returnData = array();
		if(!empty($data['fromDate'])){
			$where[] = $this->getWhereString('created','?','>=');$params[] = $data['fromDate']." 00:00:00";
		}
		if(!empty($data['toDate'])){
			$where[] = $this->getWhereString('created','?','<=');$params[] = $data['toDate']." 23:59:59";
		}
		$where[] = $this->getWhereString('searchId',array_fill(0,count($data['searchIds']),'?'));$params = array_merge($params,$data['searchIds']);
		$sql = "SELECT group_concat(concat(filterType,'_',filterValue)) as filters,searchId from searchMultivalueTracking ";
		if(!empty($where)){
			$sql .= 'where '.implode(' AND ',$where);
		}
		$sql .= ' group by searchId ';
		if(!empty($data['orderBy'])){
			$sql .= " order by ".$data['orderBy']." desc";
		}
		$query = $this->dbHandle->query($sql,$params)->result_array();
		// _p($this->dbHandle->last_query());die;
		return $query;
	}

	public function trackTagSearch($userId){
		$this->initiateModel();
		$temp = array('keyword'=>'keyword','landing_page'=>'url','device'=>'device');
		foreach ($temp as $columnName => $label) {
			$value = $this->input->post($label,true);
			if(!empty($value)){
				$data[$columnName] = $value;
			}
		}
		if(!empty($data)){
			$data['referrer'] = $_SERVER['HTTP_REFERER'];
			$data['sessionId'] = getVisitorSessionId();
			if(!empty($userId)){
				$data['userId'] = $userId;
			}
			$this->dbHandle->where($data);
			$query = $this->dbHandle->get('searchTagTracking');
			if($query->num_rows() == 0){
				$this->dbHandle->insert('searchTagTracking',$data);
			}
		}
	}
} ?>