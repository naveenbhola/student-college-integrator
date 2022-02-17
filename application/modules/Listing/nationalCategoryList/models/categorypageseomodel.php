<?php

class categorypageseomodel extends MY_Model {
	private static $categoryPageSeoRules = 'category_page_seo_rules';
	private static $categoryPageSeo = 'category_page_seo';
	public static $anyStream = 'any';
	public static $anySubStream = 'any';
	public static $anySpecialization = 1;
	public static $anyPopularCourse = 'any';
	public static $anyNonPopularCourse = 'any';
	public static $anyEducationType = 'any';
	public static $anyDeliveryMethod = 'any';
	public static $anyCredential = 'any';
	public static $anyExam = 1;
	public static $anyLocation = 1;
	public static $notFound = 'not-found';
	public static $duplicateURL = 'duplicate-url';
	public static $pending = 'pending';
	public static $deleted = 'deleted';
	public static $live = 'live';

	function __construct() {
		parent::__construct('Listing');

		$this->logFileName = 'log_category_page_seo_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;
    }

	private function initiateModel($mode = "write") {
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

    function getLiveRulesToBeProcessed() {
    	$this->initiateModel('read');

    	$sql = "SELECT * FROM category_page_seo_rules WHERE status = 'live' AND process_state = 'pending' ORDER BY priority ASC ";
    	$result = $this->dbHandle->query($sql)->result_array();

    	return $result;
    }

    function getDeletedRulesToBeProcessed() {
    	$this->initiateModel('read');

    	$sql = "SELECT * FROM category_page_seo_rules WHERE status = 'deleted' AND process_state = 'pending' ";
    	$result = $this->dbHandle->query($sql)->result_array();

    	return $result;
    }

    function storeProcessedDataInDb($data) {
		$this->initiateModel('write');
		$this->dbHandle->trans_start();

		foreach ($data as $key => $value) {
			$time_start = microtime_float(); $start_memory = memory_get_usage();

			$value = $this->_initializeValues($value);

			$ruleIds[] = $value['ruleId'];
			$tempData = array(
			    'stream_id' 	=> $value['streamId'],
			    'substream_id' 		=> $value['substreamId'],
			    'specialization_id' => $value['specializationId'],
			    'base_course_id' => $value['baseCourseId'],
			    'education_type' => $value['educationTypeId'],
			    'delivery_method' => $value['deliveryMethodId'],
			    'credential' => $value['credentialId'],
			    'exam_id' => $value['examId'],
			    'city_id' => $value['cityId'],
			    'state_id' => $value['stateId'],
			    'url' => $value['url'],
			    'hash_url' => $value['hashUrl'],
			    'breadcrumb' => $value['breadcrumb'],
			    'meta_title' => $value['metaTitle'],
			    'meta_description' => $value['metaDescription'],
			    'heading_desktop' => $value['headingDesktop'],
			    'heading_mobile' => $value['headingMobile'],
			    'rule_id' => $value['ruleId'],
			    'added_on' => date('Y-m-d H:i:s')
			);
		    
		    $queryCmd = $this->dbHandle->insert_string('category_page_seo',$tempData);
		    $queryCmd = $queryCmd." ON DUPLICATE KEY UPDATE ".
                // "breadcrumb = ? ";
				"breadcrumb = ?, ".
				"meta_title = ?, ".
				"meta_description = ?, ".
				"heading_desktop = ?, ".
				"heading_mobile = ?, ".
				"rule_id = ?";

            $query = $this->dbHandle->query($queryCmd, array($value['breadcrumb'], $value['metaTitle'], $value['metaDescription'], $value['headingDesktop'], $value['headingMobile'], $value['ruleId']));
            $insert_id = $this->dbHandle->insert_id();
            
            error_log("Section: Inserted in DB, page id - ".$insert_id." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $this->logFilePath);
        }

		//change process_state in rules table
        if(!empty($ruleIds)) {
		    $this->changeProcessStateOfRules($ruleIds);
        }

		$this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            return 'failed';
        } else {
        	return 'pass';
        }
    }

    private function changeProcessStateOfRules($ruleIds) {
    	$time_start = microtime_float(); $start_memory = memory_get_usage();

        $ruleIds = array_unique($ruleIds);
    	$sql = "UPDATE category_page_seo_rules SET process_state = 'done' WHERE id IN (?)";
    	$query = $this->dbHandle->query($sql, array($ruleIds));
        
    	$queryResultFlag = true;
    	if($query !== true) {
			$queryResultFlag = false;
		}

		error_log("Section: Updating process status for rule id - ".implode(',', $ruleIds)." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $this->logFilePath);
		return $queryResultFlag;
    }

    function _initializeValues(& $value) {
    	if(empty($value['streamId'])) {
			$value['streamId'] = 0;
		}
		if(empty($value['substreamId'])) {
			$value['substreamId'] = 0;
		}
		if(empty($value['specializationId'])) {
			$value['specializationId'] = 0;
		}
		if(empty($value['baseCourseId'])) {
			$value['baseCourseId'] = 0;
		}
		if(empty($value['educationTypeId'])) {
			$value['educationTypeId'] = 0;
		}
		if(empty($value['deliveryMethodId'])) {
			$value['deliveryMethodId'] = 0;
		}
		if(empty($value['credentialId'])) {
			$value['credentialId'] = 0;
		}
		if(empty($value['examId'])) {
			$value['examId'] = 0;
		}
		if(empty($value['cityId'])) {
			$value['cityId'] = 0;
		}
		if(empty($value['stateId'])) {
			$value['stateId'] = 0;
		}

		return $value;
    }

    function getProcessedDataToBeDeleted($ruleId) {
    	if(empty($ruleId)) {
    		return;
    	}

    	$this->initiateModel('read');

    	$sql = "SELECT * FROM category_page_seo WHERE rule_id = ?";
    	$result = $this->dbHandle->query($sql, $ruleId)->result_array();

    	return $result;
    }

    function deleteProcessedDataWith301($store, $ruleId) {
    	$this->initiateModel('write');
		$this->dbHandle->trans_start();

		$updateArray = array();
		if(!empty($store)) {
			foreach ($store as $pageId => $value) {
				$updateArray[] = array (
			        'id' => $pageId,
			        'status' => 'deleted',
			        '301_url' => $value['301_url']
			    );
			}      
			$this->dbHandle->update_batch('category_page_seo', $updateArray, 'id');
		}

		//change process_state in rules table
		$this->changeProcessStateOfRules(array($ruleId));

		$this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            return 'failed';
        } else {
        	return 'pass';
        }
    }

    function deleteProcessedDataByRuleId($rule) {
    	$this->initiateModel('write');
		$this->dbHandle->trans_start();

		if($rule['show_404']) {
	    	$sql = "UPDATE category_page_seo ".
					" SET show_404 = 1, 301_url = '', status = 'deleted' ".
                    " WHERE rule_id = ?";
		    
            $result = $this->dbHandle->query($sql, $rule['id']);
        }
        elseif($rule['301_url']) {
            $sql = "UPDATE category_page_seo ".
                    " SET show_404 = 0, 301_url = ?, status = 'deleted' ".
                    " WHERE rule_id = ?";

            $result = $this->dbHandle->query($sql, array($rule['301_url'], $rule['id']));
        }


		//change process_state in rules table
		$this->changeProcessStateOfRules(array($rule['id']));

		$this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            return 'failed';
        } else {
        	return 'pass';
        }
    }

    function deleteLocationCombinations($rule) {
    	$this->initiateModel('write');

    	$sql = "UPDATE category_page_seo SET status = 'deleted', show_404 = 1 WHERE ";

    	//stream
    	if($rule['stream'] == 'any') {
    		$where[] = " stream_id != ? ";
            $streamId = 0;
    	}
    	elseif(empty($rule['stream'])) {
    		$where[] = " stream_id = ? ";
            $streamId = 0;
    	}
    	else {
    		$where[] = " stream_id = ? ";
            $streamId = $rule['stream'];
    	}

    	//substream
    	if($rule['substream'] == 'any') {
    		$where[] = " substream_id != ? ";
            $substreamId = 0;
    	}
    	elseif(empty($rule['substream'])) {
    		$where[] = " substream_id = ? ";
            $substreamId = 0;
    	}
    	else {
    		$where[] = " substream_id = ? ";
            $substreamId = $rule['substream'];
    	}

    	//specialization
    	if($rule['specialization']) {
    		$where[] = " specialization_id != 0 ";
    	} else {
    		$where[] = " specialization_id = 0 ";
    	}

    	//popular_course, non_popular_course
    	if($rule['popular_course'] == 'any' || $rule['non_popular_course']) {
    		$where[] = " base_course_id != ? ";
            $baseCourseId = 0;
    	}
    	elseif(empty($rule['popular_course']) || empty($rule['non_popular_course'])) {
    		$where[] = " base_course_id = ? ";
            $baseCourseId = 0;
    	}
    	else {
    		$where[] = " base_course_id = ? ";
            $baseCourseId = $rule['popular_course'];
    	}

    	//education_type
    	if($rule['education_type'] == 'any') {
    		$where[] = " education_type != ? ";
            $educationType = 0;
    	}
    	elseif(empty($rule['education_type'])) {
    		$where[] = " education_type = ? ";
            $educationType = 0;
    	}
    	else {
    		$where[] = " education_type = ? ";
            $educationType = $rule['education_type'];
    	}

    	//delivery_method
    	if($rule['delivery_method'] == 'any') {
    		$where[] = " delivery_method != ? ";
            $deliveryMethod = 0;
    	}
    	elseif(empty($rule['delivery_method'])) {
    		$where[] = " delivery_method = ? ";
            $deliveryMethod = 0;
    	}
    	else {
    		$where[] = " delivery_method = ? ";
            $deliveryMethod = $rule['delivery_method'];
    	}

    	//credential
    	if($rule['credential'] == 'any') {
    		$where[] = " credential != ? ";
            $credential = 0;
    	}
    	elseif(empty($rule['credential'])) {
    		$where[] = " credential = ? ";
            $credential = 0;
    	}
    	else {
    		$where[] = " credential = ? ";
            $credential = $rule['credential'];
    	}

    	//exam
    	if($rule['exam']) {
    		$where[] = " exam_id != 0 ";
    	} else {
    		$where[] = " exam_id = 0 ";
    	}

    	$whereStatement = implode(' AND ', $where);

    	$sql = $sql.$whereStatement;
        
    	$result = $this->dbHandle->query($sql, array($streamId, $substreamId, $baseCourseId, $educationType, $deliveryMethod, $credential));
    }
	
	function getRecatParamsForOldCategory($params) {
        $this->initiateModel('read');

        if(!isset($params['subcategory_id']) || $params['subcategory_id'] == 1) {
            $params['subcategory_id'] = 0;
        }
        if(!isset($params['ldb_course_id']) || $params['ldb_course_id'] == 1) {
            $params['ldb_course_id'] = 0;
        }
        $sql = "SELECT stream_id, substream_id, specialization_id, base_course_id, education_type, delivery_method, credential ".
                   "FROM base_entity_mapping ".
                   "WHERE oldcategory_id = ? AND oldsubcategory_id = ? AND oldspecializationid = ? ";

        $result = $this->dbHandle->query($sql, array($params['category_id'], $params['subcategory_id'], $params['ldb_course_id']))->row_array();
        
        return $result;
    }


	/**
	 * Get the category page URLs to be used in the sitemap generation cron
	 *
	 * @param bool|int $limit  The limit to be specified in the query
	 * @param bool|int $offset The offset to be used from where the <code>$limit</code> number of entries will be fetched
	 *
	 * @return array|Exception If the query produces result ; Exception in case some exception is encountered
	 */
	public function getCategorySitemapURLs($limit = false, $offset = false){

		$selectFields = array('url', 'stream_id', 'base_course_id');
		$whereClause = array(
			"status = 'live'",
			"result_count > 0"
		);

		$this->initiateModel('read');

		try{
			$this->dbHandle->select($selectFields);
			$this->dbHandle->from(categorypageseomodel::$categoryPageSeo);
			$this->dbHandle->where(implode(" AND ", $whereClause));

			if(!empty($limit) && !empty($offset)) {
				$this->dbHandle->limit($limit, $offset);
			}

			$result = $this->dbHandle->get()->result_array();

			if(count($result) > 0){
				return $result;
			} else {
				throw new Exception(categorypageseomodel::$notFound);
			}
		} catch(Exception $e){
			return $e->getMessage();
		}


	}


	/**
	 * Get SEO Details corresponding to some input combination of entities.
	 * 
	 * @param $combinationData
	 *
	 * @return string
	 */
	public function getSEODetails($combinationData){
		$this->initiateModel('read');
		$selectFields = array();
		$combinationData['status'] = 'live';

		$result =  $this->getDataForCombinations($selectFields, $combinationData, $this->dbHandle);
		if(empty($result)){
			return categorypageseomodel::$notFound;
		}
		return $result;
	}

	public function findMatchingSEODetails($combinationData){
		$this->initiateModel('read');
		$selectFields = array();

		$combinationData[] = "status = 'live'";
		$result =  $this->getDataForCombinations($selectFields, implode(" AND ", $combinationData), $this->dbHandle);
		if(empty($result)){
			return categorypageseomodel::$notFound;
		}
		return $result;
	}


	/**
	 * TODO this is the same as the \categorypageseomodel::getDataForCombinations
	 *
	 * @return string|array
	 */
	public function getCategoryURLs($combinationData){
		$this->initiateModel('read');
		$selectFields = array();

		if(count($combinationData) > 0){

			$combinationData = array_values($combinationData);
			$combinationData[] = "status = 'live'";
			$combinationData = implode(" AND ", $combinationData );
		} else {
			// Find combination data
			$combinationData['status'] = 'live';
		}

		$this->dbHandle->select($selectFields);
		$this->dbHandle->from(categorypageseomodel::$categoryPageSeoRules);
		$this->dbHandle->where($combinationData);
		$this->dbHandle->order_by("priority", "desc");

		$result = $this->dbHandle->get();
		$result = $result->result_array();
		if(count($result) > 0){
			return $result;
		} else {
			return categorypageseomodel::$notFound;
		}
	}


	/**
	 * @param $combinations
	 * @param $dbHandle
	 *
	 * @return bool
	 */
	private function getDataForCombinations($selectFields=array(), $combinations, $dbHandle){
		$dbHandle->select($selectFields);
		$dbHandle->from(categorypageseomodel::$categoryPageSeoRules);
		$dbHandle->where($combinations);
		$dbHandle->order_by("priority", "desc");
		try{
			$result = $dbHandle->get();
			return $result->result_array();
		} catch(Exception $exception){
			return false;
		}
	}

	/**
	 * Save a new URL in the table <code>category_page_seo_rules</code> with the field <code>process_state</code> as pending
	 *
	 * @param stdClass $seoData An array containing the input data
	 * @param integer  $userId  The user id of the currently logged in user
	 *
	 * @param string   $mode
	 *
	 * @return array|bool|int If there was no matching data in the database, <b>the inserted id</b>; if there are some rules, the <b>id</b>, <b>priority</b> and the <b>url</b>; if the process of saving data fails, <b>false</b>.
	 */
	public function submitSEODetails($seoData, $userId, $mode='write'){

		$this->initiateModel('write');
		$categoryData = $seoData->filteredValue;
		$combinations = implode(" AND ", array_values($seoData->combinationData));
        $combinations .= " AND status = 'live'";

		if($mode == 'write'){
			if (!isset($categoryData['id'])) { // Insert Case
				$categoryData['process_state'] = 'pending';
				$categoryData['status'] = 'live';

				try{

					$priority = $this->getDataForCombinations(array('max(priority) priority'), $combinations, $this->dbHandle);
					if(empty($priority)){
						$priority = 1;
					}
					else{
						$priority = intval($priority[0]['priority'])+1;
					}

					$categoryData['added_by'] = $userId;
					$categoryData['added_on'] = date('Y-m-d H:i:s');
					$categoryData['priority'] = $priority;
					$this->dbHandle->insert(categorypageseomodel::$categoryPageSeoRules, $categoryData);
					if ($this->dbHandle->insert_id()) {
						$returnData['ruleId'] = $this->dbHandle->insert_id();

                        //only those combinations will be picked which are recently edited and not yet processed (i.e., are in pending state) and are clashing with current rule
                        $combinations .= " AND process_state = 'pending'";

						$returnData['combinations'] = $this->getDataForCombinations(array(), $combinations, $this->dbHandle);
						return $returnData;
					} else {
						return false;
					}
				} catch(Exception $e){
					return false;
				}
			} else { // Update case
				$fieldsToUpdate = array(
					'url', 'breadcrumb', 'meta_title', 'meta_description', 'heading_desktop', 'heading_mobile'
				);

				$id = $categoryData['id'];

				// remove all extra fields to be updated (typically the combination selectors)
				foreach($categoryData as $oneIndex => $oneValue){
					if(!in_array($oneIndex, $fieldsToUpdate)){
						unset($categoryData[$oneIndex]);
					}
				}

				$categoryData['process_state'] = 'pending';
				$categoryData['updated_by'] = $userId;

				try {
					$this->dbHandle->where('id', $id);
					$this->dbHandle->update(categorypageseomodel::$categoryPageSeoRules, $categoryData);

                    //only those combinations will be picked which are recently edited and not yet processed (i.e., are in pending state) and are clashing with current rule
                    $combinations .= " AND process_state = 'pending'";
                    
					$returnData['ruleId'] = $id;
					$returnData['combinations'] = $this->getDataForCombinations(array(), $combinations, $this->dbHandle);
					return $returnData;
					
				} catch (Exception $e) {
					return false;
				}
			}

		} else { // Create a new entry in the database for the deleted rule
			try{
				$categoryData['process_state'] = 'pending';
				$categoryData['status'] = 'deleted';

				$priority = $this->getDataForCombinations(array('max(priority) priority'), $combinations, $this->dbHandle);
				if(empty($priority)){
					$priority = 1;
				}
				else{
					$priority = intval($priority[0]['priority'])+1;
				}

				$categoryData['added_by'] = $userId;
				$categoryData['added_on'] = date('Y-m-d H:i:s');
				$categoryData['priority'] = $priority;
				$this->dbHandle->insert(categorypageseomodel::$categoryPageSeoRules, $categoryData);
				if ($this->dbHandle->insert_id()) {
					return $this->dbHandle->insert_id();
				} else {
					return false;
				}
			} catch(Exception $e){
				return false;
			}
		}
	}


	/**
	 * TODO Add documentation
	 *
	 * @param $seoData
	 * @param $userId
	 *
	 * @return bool
	 */
	public function deleteSEODetails($seoData, $userId){

		$this->initiateModel('write');
		try{
			$this->dbHandle->where('id', $seoData['id']);
			$updateData = array(
				'process_state' => categorypageseomodel::$pending,
				'status' => categorypageseomodel::$deleted,
				'updated_by' => $userId,
			);
			if($seoData['type'] == '404'){
				$updateData['show_404'] = 1;
			} else if($seoData['type'] == '301'){
				$updateData['301_url'] = $seoData['URL'];
			}

			$this->dbHandle->update(categorypageseomodel::$categoryPageSeoRules, $updateData);
			if($this->dbHandle->affected_rows() > 0){
				return true;
			} else {
				return false;
			}
		} catch(Exception $e){
			return false;
		}
	}

	/**
	 * Update the entries in the database table <code>category_page_seo_rules</code> upon receipt of the processed data.
	 * Also, set the <code>process_state</code> of the updated entries as <b>pending</b>.
	 *
	 * @param array $orderedURL Containing the id, url, priority
	 * @param integer $userId The user id of the currently logged in user
	 *
	 * @return bool <code>true</code> if successful updation took place; <code>false</code> otherwise
	 */
	public function submitOrderedURLs($orderedURL, $userId){

		$this->initiateModel('write');
		try{
			foreach($orderedURL as $oneOrderedURL){
				$this->dbHandle->where('id', $oneOrderedURL['id']);
				$updateData = array('priority' => $oneOrderedURL['new_priority'], 'process_state' => categorypageseomodel::$pending, 'updated_by' => $userId);
				$this->dbHandle->update(categorypageseomodel::$categoryPageSeoRules, $updateData);
			}
			return true;
		} catch(Exception $e){
			return false;
		}
	}

	function getUrlParsedParams($scriptUrl) {
		if(empty($scriptUrl)) {
			return;
		}
		$this->initiateModel('read');

		$sql = "SELECT * FROM category_page_seo WHERE url = ?";
		$data = $this->dbHandle->query($sql, $scriptUrl)->row_array();

		return $data;
	}

	function getHashUrlParsedParams($scriptUrl) {
		if(empty($scriptUrl)) {
			return;
		}
		$this->initiateModel('read');

		$sql = "SELECT * FROM category_page_seo WHERE hash_url = ?";
		$data = $this->dbHandle->query($sql, $scriptUrl)->result_array();

		return $data;
	}

    function getCategoryPageBHSTData($pageId) {
        if(empty($pageId)) {
            return;
        }
        $this->initiateModel('read');

        $sql = "SELECT url, bhst_data, heading_mobile FROM category_page_seo WHERE id = ?";
        $data = $this->dbHandle->query($sql, $pageId)->result_array();
        if(empty($data))
            return $data;
        return reset($data);
    }

    function saveBhst($pageId, $bhst, $userId) {
        $this->initiateModel('write');
        $this->dbHandle->where("id",$pageId);
        $response = $this->dbHandle->update("category_page_seo", array("bhst_data" => $bhst, 'updated_by' => $userId));
        return $response;
    }

	function getUrlByParams($categoryData) {
		if(empty($categoryData)) {
			return;
		}

        $this->initiateModel('read');

        if(!isset($categoryData['stream_id']) || empty($categoryData['stream_id'])) {
            $categoryData['stream_id'] = 0;
        }
        if(!isset($categoryData['substream_id']) || empty($categoryData['substream_id'])) {
            $categoryData['substream_id'] = 0;
        }
        if(!isset($categoryData['specialization_id']) || empty($categoryData['specialization_id'])) {
            $categoryData['specialization_id'] = 0;
        }
        if(!isset($categoryData['base_course_id']) || empty($categoryData['base_course_id'])) {
            $categoryData['base_course_id'] = 0;
        }
        if(!isset($categoryData['education_type']) || empty($categoryData['education_type'])) {
            $categoryData['education_type'] = 0;
        }
        if(!isset($categoryData['delivery_method']) || empty($categoryData['delivery_method'])) {
            $categoryData['delivery_method'] = 0;
        }
        if(!isset($categoryData['credential']) || empty($categoryData['credential'])) {
            $categoryData['credential'] = 0;
        }
        if(!isset($categoryData['exam_id']) || empty($categoryData['exam_id'])) {
            $categoryData['exam_id'] = 0;
        }
        if((!isset($categoryData['state_id']) || empty($categoryData['state_id'])) && (!isset($categoryData['city_id']) || empty($categoryData['city_id']))) {
            $categoryData['state_id'] = 1;
        }
        if(!isset($categoryData['city_id']) || empty($categoryData['city_id'])) {
            $categoryData['city_id'] = 1;
        }
        if(!isset($categoryData['min_result_count'])) {
            $categoryData['min_result_count'] = 1;
        }

		$sql = "SELECT url FROM category_page_seo WHERE ";
		
		$where[] = " status = 'live' ";
		$where[] = " stream_id = ?";
		$where[] = " substream_id = ?";
		$where[] = " specialization_id = ?";
		$where[] = " base_course_id = ?";
		$where[] = " education_type = ?";
		$where[] = " delivery_method = ?";
		$where[] = " credential = ?";
		$where[] = " exam_id = ?";
		$where[] = " city_id = ?";
		$where[] = " result_count >= ?";
		
		if(isset($categoryData['state_id'])) {
			$where[] = " state_id = ?";
		}

		$whereStatement = implode(' AND ', $where);

		$sql = $sql.$whereStatement;

    	$result = $this->dbHandle->query($sql, array($categoryData['stream_id'], $categoryData['substream_id'], $categoryData['specialization_id'], $categoryData['base_course_id'], $categoryData['education_type'], $categoryData['delivery_method'], $categoryData['credential'], $categoryData['exam_id'], $categoryData['city_id'], $categoryData['min_result_count'], $categoryData['state_id']))->row_array();
        
    	return $result;
	}

	function getAllIndiaPageByStreamId($streamId) {
		if(empty($streamId)) {
			return;
		}

        $this->initiateModel('read');

        $this->dbHandle->where('stream_id',$streamId);
        $this->dbHandle->where('city_id',1)->where('state_id',1);
        $this->dbHandle->order_by('result_count','desc')->limit(1);

        $data = $this->dbHandle->get('category_page_seo')->row_array();
        return $data;
	}

	public function getUrlByMultipleParams($categoryDataArr) {
		if($_REQUEST['debug']){
			_p($categoryDataArr);die;
		}
		if(empty($categoryDataArr)) {
			return;
		}
		$this->initiateModel('read');

		$sql = "SELECT stream_id, substream_id, specialization_id, base_course_id, education_type, delivery_method, credential, exam_id, city_id, state_id, result_count, url ".
				"FROM category_page_seo WHERE status = 'live' AND ";

		$whereStatements = array(); $params = array();
		foreach ($categoryDataArr as $key => $categoryData) {
			$where = array();
			$where[] = " stream_id = ?";
            $params[] = $categoryData['stream_id'];

			$where[] = " substream_id = ?";
            $params[] = $categoryData['substream_id'];

			$where[] = " specialization_id = ?";
            $params[] = $categoryData['specialization_id'];

			$where[] = " base_course_id = ?";
            $params[] = $categoryData['base_course_id'];

			$where[] = " education_type = ?";
            $params[] = $categoryData['education_type'];

			$where[] = " delivery_method = ?";
            $params[] = $categoryData['delivery_method'];

			$where[] = " credential = ?";
            $params[] = $categoryData['credential'];

			$where[] = " exam_id = ?";
            $params[] = $categoryData['exam_id'];

			$where[] = " city_id = ?";
            $params[] = $categoryData['city_id'];

			$where[] = " result_count >= ?";
            $params[] = $categoryData['min_result_count'];

			if(isset($categoryData['state_id'])) {
				$where[] = " state_id = ?";
                $params[] = $categoryData['state_id'];
			}
			$whereStatements[] = implode(' AND ', $where);
		}

		if(empty($whereStatements)) {
			return;
		}
		$finalWhereStatement = implode(' OR ', $whereStatements);

		$sql = $sql.$finalWhereStatement;

    	$result = $this->dbHandle->query($sql, $params)->result_array();
    	return $result;
	}

	public function getUrlByCustomParams($categoryData,$exclusionList=array(),$limit) {
		if(empty($categoryData)) {
			return;
		}
		$this->initiateModel('read');

		$this->dbHandle->select("stream_id, substream_id, specialization_id, base_course_id, education_type, delivery_method, credential, exam_id, city_id, state_id, result_count, url");
		$this->dbHandle->from("category_page_seo");

		$this->dbHandle->where("status","live");

		if(isset($categoryData['stream_id'])) {
			$this->dbHandle->where("stream_id",$categoryData['stream_id']);
		}
		if(isset($categoryData['substream_id'])) {
			$this->dbHandle->where("substream_id",$categoryData['substream_id']);
		}
		if(isset($categoryData['specialization_id'])) {
			$this->dbHandle->where("specialization_id",$categoryData['specialization_id']);
		}
		if(isset($categoryData['base_course_id'])) {
			$this->dbHandle->where_in("base_course_id",$categoryData['base_course_id']);
		}
		if(isset($categoryData['education_type'])) {
			$this->dbHandle->where_in("education_type",$categoryData['education_type']);
		}
		if(isset($categoryData['delivery_method'])) {
			$this->dbHandle->where_in("delivery_method",$categoryData['delivery_method']);
		}
		if(isset($categoryData['credential'])) {
			$this->dbHandle->where_in("credential",$categoryData['credential']);
		}
		if(isset($categoryData['exam_id'])) {
			$this->dbHandle->where_in("exam_id",$categoryData['exam_id']);
		}
		if(isset($categoryData['city_id'])) {
			$this->dbHandle->where("city_id",$categoryData['city_id']);
		}
		if(isset($categoryData['min_result_count'])) {
			$this->dbHandle->where("result_count >= ",$categoryData['min_result_count']);
		}
		if(isset($categoryData['state_id'])) {
			$this->dbHandle->where("state_id",$categoryData['state_id']);
		}
		//exclusion list
		if(!empty($exclusionList)) {
			if(!empty($exclusionList['city'])) {
				$this->dbHandle->where("city_id != ",$exclusionList['city']);
			}
		}
		if(isset($categoryData['orderby'])) {
			$this->dbHandle->order_by($categoryData['orderby']);
		}
		if(isset($limit)) {
			$this->dbHandle->limit($limit);
		}
    	$result = $this->dbHandle->get()->result_array();
        
    	return $result;
	}

	public function fetchHierachiesData(){
		$this->initiateModel('read');
		$sql = "SELECT stream_id,substream_id,specialization_id,base_course,course_id,credential from shiksha_courses_type_information where status = 'live'";
		$query = $this->dbHandle->query($sql);
		$result  = $query->result_array();
		return $result;
	}

	public function fetchBasicData(){
		$this->initiateModel('read');
		$sql  = "select education_type, delivery_method,course_id,primary_id from shiksha_courses where status = 'live'";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}

	public function fetchExamsData(){
		$this->initiateModel('read');
		$sql  = "select exam_id, course_id from shiksha_courses_eligibility_exam_score where status = 'live'";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;	
	}

	public function fetchLocationsData(){
		$this->initiateModel('read');
		$sql = "select distinct b.city_id,b.state_id,a.course_id from shiksha_courses_locations a
				JOIN shiksha_institutes_locations b 
				ON a.listing_location_id = b.listing_location_id
				WHERE a.status = 'live' and b.status = 'live'";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}

	public function findCategoryPageData($start, $limit){
        if(!isset($start)) {
            $start = 0;
        }
        if(!isset($limit)) {
            return;
        }

		$this->initiateModel('read');
		$sql = "SELECT 	
				id,
				stream_id,
				substream_id, 
				specialization_id, 
				base_course_id, 
				education_type, 
				delivery_method, 
				credential, 
				exam_id, 
				city_id, 
				state_id,
				url,
				result_count
				FROM category_page_seo 
				WHERE status IN ('live')
				LIMIT ?, ?";
		$query = $this->dbHandle->query($sql, array((int)$start, (int)$limit));
		$result = $query->result_array();
		return $result;
	}

    public function findCategoryPageNonZeroData($start, $limit){
        if(!isset($start)) {
            $start = 0;
        }
        if(!isset($limit)) {
            return;
        }

        $this->initiateModel('read');
        $sql = "SELECT  
                id,
                stream_id,
                substream_id, 
                specialization_id, 
                base_course_id, 
                education_type, 
                delivery_method, 
                credential, 
                exam_id, 
                city_id, 
                state_id,
                url,
                result_count
                FROM category_page_seo 
                WHERE status IN ('live') and result_count > 0
                LIMIT ?, ?";
        $query = $this->dbHandle->query($sql, array((int)$start, (int)$limit));
        $result = $query->result_array();
        return $result;
    }

	public function findCategoryPageDataForId($id){
        if(empty($id)) {
            return;
        }

		$this->initiateModel('read');
		$sql = "SELECT 	
				id,
				stream_id,
				substream_id, 
				specialization_id, 
				base_course_id, 
				education_type, 
				delivery_method, 
				credential, 
				exam_id, 
				city_id, 
				state_id,
				url
				FROM category_page_seo 
				WHERE status IN ('live')
				and id = ?";
		$query = $this->dbHandle->query($sql, $id);
		$result = $query->result_array();
		return $result;
	}

	function updateCountForCategoryPage($finalResult){
		if(empty($finalResult)) return;
		
        $this->initiateModel('write');
		
        $updateQuery = "UPDATE category_page_seo SET result_count = CASE ";
        $inClause = "WHERE id IN (";
        
        foreach ($finalResult as $id=> $value) {
		    $updateQuery .= " WHEN id = ? THEN '?'";
            $inClause .= "$id ,";
            $params[] = $id;
            $params[] = $value['count'];
            $idParams[] = $id;
        }
        $params = array_merge($params, $idParams);
        $inClause = substr($inClause, 0,-1).")";
        $updateQuery .= " END $inClause";
      	$this->dbHandle->query($updateQuery, $params);
	}

	function updateHashForCategoryPage($finalResult){
		if(empty($finalResult)) return;
		
        $this->initiateModel('write');
		
        $updateQuery = "UPDATE category_page_seo SET hash_url = CASE";
        $inClause = "WHERE id IN (";
        foreach ($finalResult as $id => $value) {
		    $updateQuery .= " WHEN id = ? THEN '?'";
            $params[] = $id;
            $params[] = $value['hash_url'];
            $inClause .= $id.",";
            $idParams[] = $id;
        }
        $params = array_merge($params, $idParams);
        
        $inClause = substr($inClause, 0,-1).")";
        $updateQuery .= " END $inClause";
      	$this->dbHandle->query($updateQuery, $params);
	}

	function getMultipleUrlByParams($categoryData) {
		if(empty($categoryData)) {
			return;
		}
		$this->initiateModel('read');

		$sql = "SELECT * FROM category_page_seo WHERE ";
		
		$where[] = " status = 'live' ";
		if(isset($categoryData['stream_id'])) {
			$where[] = " stream_id = ?";
            $params[] = $categoryData['stream_id'];
		}
		if(isset($categoryData['substream_id'])) {
			$where[] = " substream_id = ?";
            $params[] = $categoryData['substream_id'];
		}
		if(isset($categoryData['specialization_id'])) {
			$where[] = " specialization_id = ?";
            $params[] = $categoryData['specialization_id'];
		}
		if(isset($categoryData['base_course_id'])) {
			$where[] = " base_course_id = ?";
            $params[] = $categoryData['base_course_id'];
		}
		if(isset($categoryData['education_type'])) {
			$where[] = " education_type = ?";
            $params[] = $categoryData['education_type'];
		}
		if(isset($categoryData['delivery_method'])) {
			$where[] = " delivery_method = ?";
            $params[] = $categoryData['delivery_method'];
		}
		if(isset($categoryData['credential'])) {
			$where[] = " credential = ?";
            $params[] = $categoryData['credential'];
		}
		if(isset($categoryData['exam_id'])) {
			$where[] = " exam_id = ?";
            $params[] = $categoryData['exam_id'];
		}
		if(isset($categoryData['city_id'])) {
			$where[] = " city_id = ?";
            $params[] = $categoryData['city_id'];
		}
		if(isset($categoryData['state_id'])) {
			$where[] = " state_id = ?";
            $params[] = $categoryData['state_id'];
		}
		if(isset($categoryData['min_result_count'])) {
			$where[] = " result_count >= ?";
            $params[] = $categoryData['min_result_count'];
		} else {
			$where[] = " result_count >= 1";
		}

		if(empty($where)) {
			return;
		}
		$whereStatement = implode(' AND ', $where);

		$sql = $sql.$whereStatement;

    	$result = $this->dbHandle->query($sql, $params)->result_array();

    	return $result;
	}


	public function findCategoryPageDataForRuleId($ruleIds){
		$this->initiateModel('read');
		$sql = "SELECT 	
				id
				FROM category_page_seo 
				WHERE status IN ('live')
				and rule_id IN (?)";
		$query = $this->dbHandle->query($sql, array($ruleIds));
        
		$result = $query->result_array();
		return $result;
	}

    function getURLByMultipleCriteria($criteriaArray) {
        $this->initiateModel('read');

        $sql = "SELECT url from category_page_seo where ";
        $params = array();
        
        if(!empty($criteriaArray['stream_id'])) {
            $where[] = 'stream_id IN (?)';
            $params[] = $criteriaArray['stream_id'];
        }

        if(!empty($criteriaArray['substream_id'])) {
            $where[] = 'substream_id IN (?)';
            $params[] = $criteriaArray['substream_id'];
        }

        if(!empty($criteriaArray['specialization_id'])) {
            $where[] = 'specialization_id IN (?)';
            $params[] = $criteriaArray['specialization_id'];
        }

        if(!empty($criteriaArray['base_course'])) {
            $where[] = 'base_course_id IN (?)';
            $params[] = $criteriaArray['base_course'];
        }

        if(!empty($criteriaArray['education_type'])) {
            $where[] = 'education_type IN (?)';
            $params[] = $criteriaArray['education_type'];
        }

        if(!empty($criteriaArray['delivery_method'])) {
            $where[] = 'delivery_method IN (?)';
            $params[] = $criteriaArray['delivery_method'];
        }
        
        if(!empty($criteriaArray['credential'])) {
            $where[] = 'credential IN (?)';
            $params[] = $criteriaArray['credential'];
        }

        if(!empty($criteriaArray['exam_id'])) {
            $where[] = 'exam_id IN (?)';
            $params[] = $criteriaArray['exam_id'];
        }

        if(!empty($criteriaArray['city_id'])) {
            $where[] = 'city_id IN (?)';
            $params[] = $criteriaArray['city_id'];
        }

        if(!empty($criteriaArray['state_id'])) {
            $where[] = 'state_id IN (?)';
            $params[] = $criteriaArray['state_id'];
        }
        
        $whereClause = implode(' AND ', $where);

        $sql = $sql.$whereClause;
        
        $result = $this->dbHandle->query($sql, $params)->result_array();
        //_p($this->dbHandle->last_query());
        
        foreach ($result as $key => $value) {
            $urls[] = $value['url'];
        }
        
        return $urls;
    }
}