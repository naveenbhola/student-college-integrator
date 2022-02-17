<?php
 class abroadlistingcronmodel extends MY_Model{
    private  $dbHandle;
    private $dbHandleMode;
    function __construct() {
        parent::__construct('Listing');
    }
    
    private function _initiateModel($mode = 'read'){
        if($this->dbHandle && $this->dbHandleMode == 'write'){
            return;
        }
        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($this->dbHandleMode == 'read'){
            $this->dbHandle = $this->getReadHandle();
        }elseif($this->dbHandleMode == 'write'){
            $this->dbHandle = $this->getWriteHandle();
        }
        return;
    }
    
    public function getUserMovementTotalRows($data,$useElasticForUserMovementData,$elasticClientCon){
        if($useElasticForUserMovementData){
            foreach($data['pageIdentifier'] as $pageIdentifier){
                $pageIdentifierClause[] = array("term"=> array("pageIdentifier"=> $pageIdentifier));
            }      
            $params = array();
            $params['index'] = PAGEVIEW_INDEX_NAME;
            $params['type'] = 'pageview';
            $params['body'] = array(
                                "size"  => 0,
                                "query" => array(
                                    "bool"=> array(
                                        "filter"=> array(
                                            "bool"=> array(
                                                "must"=> array(
                                                    array(
                                                        "range"=> array(
                                                            "visitTime"=> array(
                                                                "gte"=> "now-3M/d",
                                                                "time_zone"=>ELASTIC_TIMEZONE
                                                            )
                                                        )
                                                    ),
                                                    array(
                                                        "term"=> array(
                                                            "isStudyAbroad"=> "yes"
                                                        )
                                                    )
                                                ),
                                                "should"=> $pageIdentifierClause
                                            )
                                        )
                                    )
                                )
                            );
            $result = $elasticClientCon->search($params);
            if(!empty($result['hits']['total'])){
                return $result['hits']['total'];
            }
            else{
                return 0;
            }
        }        
    }

    /*
    Elastic search query : 

    http://172.16.3.111:9200/shiksha_trafficdata_pageviews/
        {
          "size": 50,
          "from": 0,
          "query": {
            "filtered": {
              "filter": {
                "bool": {
                  "must": [
                    {
                      "term": {
                        "isStudyAbroad": "yes"
                      }
                    },
                    {
                      "term": {
                        "pageIdentifier": "examContentPage"
                      }
                    },
                    {
                      "term": {
                        "pageEntityId": "666"
                      }
                    }
                  ]
                }
              }
            }
          },
          "sort": {
            "_id": {
              "order": "asc"
            }
          }
        }
    */

    public function updateApplyContentData($elasticClientCon){
        ini_set('memory_limit', '800M');
        $dataChunk = 50000;
        $lowerLimit = 0;
        $params = array();
        $params['index'] = PAGEVIEW_INDEX_NAME;
        $params['type'] = 'pageview';
        $params['search_type'] = 'scan';
        $params['scroll'] = '5m';
        $params['body'] = array(
                            "size"  => $dataChunk,
                            "sort" => ["_doc"],
                            "from"  => $lowerLimit,
                            "fields"=> array("pageURL"),
                            "query" => array(
                                "filtered"=> array(
                                    "filter"=> array(
                                        "bool"=> array(
                                            "must"=> array(
                                                array(
                                                    "term"=> array(
                                                        "isStudyAbroad"=> "yes",
                                                    ),
                                                ),
                                                array(
                                                    "term"=> array(
                                                        "pageIdentifier"=> "applyContentPage",
                                                    ),
                                                )
                                             )
                                        )
                                    )
                                )
                            ),
                            );
        $response = $elasticClientCon->search($params);
        $scroll_id = $response['_scroll_id'];
        while (true) {
            try{
                $response = $elasticClientCon->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => "5m" 
                    )
                 );
                $numOfUserRecords = count($response['hits']['hits']);
                $updateParams['index'] = PAGEVIEW_INDEX_NAME;
                $updateParams['type'] = 'pageview';
                $updateParams['body'] = array();
                if ($numOfUserRecords > 0) {
                    foreach($response['hits']['hits'] as $row){
                            $updateParams['body'][] = array(
                                    'update' => array(
                                    '_id' => $row['_id']
                                    )
                                );
                            $url = $row['fields']['pageURL'][0];
                            $url = explode('?', $url);
                            $url = $url[0];
                            $url = explode('#', $url);
                            $url = $url[0];
                            $url = explode('applycontent', $url);
                            $pageEntityId = substr($url[1],1);
                            // $pageEntityId = test;
                            $updateParams['body'][] = array('doc_as_insert' =>true ,
                                'doc'=> array('pageEntityId' => $pageEntityId));
                   }
                   $scroll_id = $response['_scroll_id'];
                   $response = $elasticClientCon->bulk($updateParams);
                }
                else{
                    break;
                }
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }
    
    public function getUserMovementData($data=array(),$lowerLimit=0,$dataChunk=500){
        if(empty($data) || !(isset($data['urlPattern'])) || !(isset($data['timePeriodForDataAnalysis']))){
            return array();
        }
        $dateCheck = date('Y-m-d',  strtotime('-'.$data['timePeriodForDataAnalysis']));
        $this->_initiateModel('write');
        $this->dbHandle->select('SQL_CALC_FOUND_ROWS sessionId,userId,url',FALSE);
        $this->dbHandle->from('studyAbroadUserMovementTracking');
        $this->dbHandle->where('timeStamp >=' , $dateCheck);
        $this->dbHandle->like('url',$data['urlPattern']);
        $this->dbHandle->limit($dataChunk,$lowerLimit);
        $resultSet['data'] = $this->dbHandle->get()->result_array();
        //echo '<br/>query :'.$this->dbHandle->last_query().PHP_EOL;
        
        $sql = 'SELECT FOUND_ROWS() as totalRows';
        $rowsResult = $this->dbHandle->query($sql)->row_array();
        $resultSet['totalRows'] = $rowsResult['totalRows'];
        //_p($resultSet);die;
        return $resultSet;
    }
    
    public function getUniversityUserMovementDataFromElastic($data,$lowerLimit=0,$dataChunk=500,$clientCon){
        
        if(empty($data)){
            return array();
        }
        $pageIdentifierClause = array();
        foreach($data['pageIdentifier'] as $pageIdentifier){
            $pageIdentifierClause[] = array("term"=> array("pageIdentifier"=> $pageIdentifier,));
        }
        $params = array();
        $params['index'] = PAGEVIEW_INDEX_NAME;
        $params['filter_path'] =array("hits.hits._source");
        $params['type'] = 'pageview';
        $params['body'] = array(
                            "size"  => $dataChunk,
                            "from"  => $lowerLimit,
                            "_source"=> array("sessionId","userId","pageEntityId"),
                            "query" => array(
                                "bool"=> array(
                                    "filter"=> array(
                                        "bool"=> array(
                                            "must"=> array(
                                                array(
                                                    "range"=> array(
                                                        "visitTime"=> array(
                                                            "gte"=> "now-3M/d",
                                                            "time_zone"=>ELASTIC_TIMEZONE
                                                        )
                                                    )
                                                ),
                                                array(
                                                    "term"=> array(
                                                        "isStudyAbroad"=> "yes"
                                                    )
                                                )
                                            ),
                                            "should"=> $pageIdentifierClause
                                        )
                                    )
                                )
                            )
                        );
        error_log('ELA1 : Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        $result = $clientCon->search($params);  
        error_log('ELA2 : Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        
        //make map of userids
        error_log('MAP1 : Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
//        $returnData = $this->_addToUserIdMap($result['hits']['hits']);
        error_log('MAP2 : Memory Usage '.((memory_get_peak_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/cooccurrenceLog.txt');
        $returnData = array();
        foreach ($result['hits']['hits'] as $rowNumber=>$row){
            $returnData[$rowNumber]['userId']         = $row['_source']['userId'];
            $returnData[$rowNumber]['universityId']   = $row['_source']['pageEntityId'];
            $returnData[$rowNumber]['sessionId']      = $row['_source']['sessionId'];
        }
        return $returnData;
    }
    
    
    
    
    public function insertLogLikelihoodData($dataArray,$entityType = '') {
        if(!is_array($dataArray) || count($dataArray) == 0 || $entityType == ''){
            return;
        }
        $this->_initiateModel('write');
        $this->dbHandle->trans_start();
        
        $this->dbHandle->insert_batch('studyAbroadLogLikelihoodAnalysis',$dataArray);
        
        // update old data
        $currentDate = date('Y-m-d');
        $updateData = array('status' => 'history');
        $this->dbHandle->where('dateUpdated <',$currentDate);
        $this->dbHandle->where('entityType ',$entityType);
        $this->dbHandle->update('studyAbroadLogLikelihoodAnalysis',$updateData);
        
        $this->dbHandle->trans_complete();
		
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }
        return TRUE;
    }
	/*
	 * insert single batch of cooccurrence based recommendation data
	 */
    public function insertCooccurrenceBasedRecommendationData($dataArray,$entityType = '') {
        if(!is_array($dataArray) || count($dataArray) == 0 || $entityType == ''){
            return;
        }
		$this->_initiateModel('write');
		// update old data
        $currentDate = date('Y-m-d');
        $updateData = array('status' => 'history');
        $this->dbHandle->where('date(dateUpdated) <=',$currentDate);
        $this->dbHandle->where('entityType',$entityType);
        $this->dbHandle->update('studyAbroadCooccurrenceAnalysis',$updateData);
		
        $this->dbHandle->trans_start();
        $this->dbHandle->insert_batch('studyAbroadCooccurrenceAnalysis',$dataArray);
        
        
        
        $this->dbHandle->trans_complete();
		
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }
        return TRUE;
    }
	/*
	 * function to get abroad course id, ldb course id , course level, parent category
     * from abroadCategoryPageData
	 */
	public function getAbroadCategoryPageData()
	{
	  $this->_initiateModel('read');
	  $this->dbHandle->select("course_id, ldb_course_id, course_level, category_id, country_id, university_id");
	  $this->dbHandle->from("abroadCategoryPageData");
	  $this->dbHandle->where("status","live");
	  return $this->dbHandle->get()->result_array();
	}
	/*
	 * function to get fees for all abroad courses
	 */
	public function getFirstYearFeesForAllAbroadCourses()
	{
	  $this->_initiateModel('read');
	  $this->dbHandle->select("distinct acpd.course_id, cd.fees_unit, cd.fees_value",false);
	  $this->dbHandle->from("abroadCategoryPageData acpd");
	  $this->dbHandle->join("course_details cd","acpd.course_id = cd.course_id and acpd.status = cd.status","inner");
	  $this->dbHandle->where("acpd.status","live");
	  //$this->dbHandle->get()->result_array();
	  //echo $this->dbHandle->last_query();die;
	  return $this->dbHandle->get()->result_array();
	 
	}
	/*
	 * function to get living expenses for all universities
	 */
	public function getLivingExpensesForAllUniversities()
	{
	  $this->_initiateModel('read');
	  $this->dbHandle->select("uca.university_id, uca.living_expenses, uca.currency, ult.country_id", false);
	  $this->dbHandle->from("university_campus_accommodation uca");
	  $this->dbHandle->join("university_location_table ult","uca.university_id = ult.university_id and uca.status = ult.status","inner");
	  $this->dbHandle->where("uca.living_expenses>0","",false);
	  $this->dbHandle->where("uca.status","live");
	  //$this->dbHandle->get()->result_array();
	  //echo $this->dbHandle->last_query();die;
	  return $this->dbHandle->get()->result_array();
	}
	/*
	 * function to get exam scores for all eligibility exams of all abroad courses
	 */
	public function getExamScoresForAllAbroadCourses()
	{
	  $this->_initiateModel('read');
	  $this->dbHandle->select("distinct acpd.course_id, lea.examId, lea.cutoff", false);
	  $this->dbHandle->from("abroadCategoryPageData acpd");
	  $this->dbHandle->join("listingExamAbroad lea","acpd.course_id = lea.listing_type_id and acpd.status = lea.status and lea.listing_type= 'course'","inner");
	  $this->dbHandle->where("lea.cutoff != 'N/A'","",false);
	  $this->dbHandle->where("lea.examId != -1","",false);
	  $this->dbHandle->where("acpd.status","live");
	  //$this->dbHandle->get()->result_array();
	  //echo $this->dbHandle->last_query();die;
	  return $this->dbHandle->get()->result_array();
	}
	
	public function getCoursesWithResposeCountByTime($startTime,$endTime)
	{
	  $this->_initiateModel('read');
	  if($startTime !='' && $endTime !=''){
	  
	  error_log("Course Response Count: fetching start for startTime=".$startTime.":: endTime=".$endTime." at ". date("H:i:s"));
	   
	  $this->dbHandle->select("count(distinct(t.id)) as responseCount,t.listing_type_id as courseId,t.action,acpd.university_id", false);
	  $this->dbHandle->from("abroadCategoryPageData acpd");
	  $this->dbHandle->join("tempLMSTable t","acpd.course_id = t.listing_type_id and t.listing_type= 'course'","inner");
	  $this->dbHandle->where("acpd.status","live");
	  $this->dbHandle->where("t.submit_date BETWEEN '".$startTime."' AND '".$endTime."'","",false);
	  $this->dbHandle->group_by("t.listing_type_id,t.action");
	  $result = $this->dbHandle->get()->result_array();
	  
	  error_log("Course Response Count: fetching complete for startTime=".$startTime.":: endTime=".$endTime." at". date("H:i:s"));
	  
	  }
	  //echo $this->dbHandle->last_query()."<br/>";//die;
	  return $result;
	}
	
	private function _getDataInChunks($pushArray,$batchsize = 500){
		$data = array();$count = count($pushArray);
		for($i=0,$z=0;$i<$count;$i++){
			if($i%$batchsize == 0 && $i!= 0){
				$z++;
			}
			$data[$z][] = $pushArray[$i];
		}
		return $data;
	}
	
	public function saveCoursesResposeCountByAction($dataArray){
		$this->_initiateModel('write');
		error_log("Course Response Count: insert trans start ".date("H:i:s"));
		$this->dbHandle->trans_start();
		
		$dataArray = array_values($dataArray);
		
		$data = $this->_getDataInChunks($dataArray);
		
		foreach($data as $pushArray)
		{
		 $valuesString = array();
		 foreach($pushArray as $pushVal){
			 $valuesString[] ="('".$pushVal['courseId']."','".$pushVal['universityId']."','".$pushVal['action']."','".$pushVal['count']."','".$pushVal['coursePostedDate']."','".$pushVal['popularityFlag']."')";
		 }
		 $sql="insert into abroadCourseResponseCountDetails (courseId,universityId,action,responseCount,coursePostedDate,popularityFlag) values ";
		 $sql.= implode(',',$valuesString);
		 $sql.= " on duplicate key update responseCount = responseCount + values(responseCount)";
		 $this->dbHandle->query($sql,array());
		 //echo $this->dbHandle->last_query()."<br/>";
		}
		
		$this->dbHandle->trans_complete();
		if($this->dbHandle->trans_status() === FALSE){
			throw new Exception("Transaction Failed");
		}
		error_log("Course Response Count: insert trans complete ".date("H:i:s"));
    }
	
	
	public function getCourseSubmitDateByCourseIds($courseIds){
		
		error_log("Course Response Count: submit date data fetching start ".date("H:i:s"));
		$this->_initiateModel('read');
		
		$data = $this->_getDataInChunks($courseIds);
		$result = array();
		foreach($data as $idArray)
		{
		 $this->dbHandle->select("submit_date,listing_type_id", false);
		 $this->dbHandle->from("listings_main");
		 $this->dbHandle->where_in("listing_type_id",$idArray);
		 $this->dbHandle->where("listing_type","course");
		 $this->dbHandle->where("status","live");
		 $result[] = $this->dbHandle->get()->result_array();
		 //echo $this->dbHandle->last_query()."<br/>";
		 
		}
		error_log("Course Response Count: submit date data fetching complete ".date("H:i:s"));
		return $result;
    }
	
	public function getLastDateWhenResponseCountDetailsTableUpdated(){
		 $this->_initiateModel('read');
		 $this->dbHandle->select("max(updatedOn) as lastDate", false);
		 $this->dbHandle->from("abroadCourseResponseCountDetails");
		 $result = $this->dbHandle->get()->result_array();
		 //echo $this->dbHandle->last_query()."<br/>";
		 return $result;
	 
	}

	public function getCourseIdAndPackTypeFromDb(){
	    $this->_initiateModel('read');
	    $this->dbHandle->select('cd.course_id,lm.pack_type');
	    $this->dbHandle->from('course_details cd');
	    $this->dbHandle->where_in('cd.status',array('live'));
	    $this->dbHandle->join('listings_main lm','lm.listing_type_id = cd.course_id AND lm.listing_type = "course" AND lm.status = "live"','left');
	    $result = $this->dbHandle->get()->result_array();
	    return $result;
    }

    public function getCityVideosUrls(){
	    $this->_initiateModel('read');
	    $this->dbHandle->select('scv.id, scv.cityId,scv.videoTitle,scv.videoUrl');
	    $this->dbHandle->from('SACityVideos scv');
	    $this->dbHandle->where('scv.status',"live");
	    $result = $this->dbHandle->get()->result_array();
	    return $result;
    }

    public function populateCityVideosData($postData){
        $this->dbHandle->trans_start();
        if(is_array($postData) && count($postData) > 0){
            $this->dbHandle->insert('SACityVideos', $postData);
        }
        $this->dbHandle->trans_complete();
        if($this->dbHandle->trans_status() === FALSE){
            throw new Exception("Transaction Failed");
        }
    }

    public function updateCityVideosData($primaryIds){
        $this->dbHandle->trans_start();
	    $data = array(
            'status' => ENT_SA_HISTORY_STATUS,
            'modificationDate' => date('Y-m-d H:i:s')
        );
	    $this->dbHandle->where_in("id", $primaryIds);
        $this->dbHandle->where("status", ENT_SA_PRE_LIVE_STATUS);
        $this->dbHandle->update('SACityVideos', $data);

        $this->dbHandle->trans_complete();
        if($this->dbHandle->trans_status() === FALSE){
            throw new Exception("Transaction Failed");
        }
    }
	
 }
?>