<?php
/*
Purpose       : To compute all possible combinations of ranking pages possible with non-zero results and populate a denormalized table(ranking_non_zero_pages)
	
Author 	      : Romil Goel

Creation Date : 20-01-2015

To Do 	      : none
*/

class RankingNonZeroPagesLib {
    
    function __construct(){
        $this->CI = &get_instance();
        
        $this->CI->config->load('ranking_config');
		$this->CI->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		$this->ranking_model         = $this->CI->load->model("ranking_model");
		$this->alertClient           = $this->CI->load->library('alerts_client');        
		$this->rankingPageRepository = RankingPageBuilder::getRankingPageRepository();
        $this->CI->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder    = new CourseBuilder();
		$this->courseRepo = $courseBuilder->getCourseRepository();
        $this->CI->load->builder('LocationBuilder','location');
		$locationBuilder    = new LocationBuilder;
		$this->locationRepo = $locationBuilder->getLocationRepository();
    }

    function populateRankingNonZeroData(){
    	$this->createLog('-----------------------CRON START-----------------------');
    	$this->createLog("Getting all live ranking pages");
    	$params = array('status' => 'live','orderBy' => 'ranking_page_id');
    	$rankingPages = $this->ranking_model->getRankingPages($params);
    	$publishers = $this->ranking_model->getAllPublishersMappedToRankingPages();
    	$insertData = array();
    	$scriptStartTime = time();
    	$sourcePublisherMapping = array();
        $sourcePublisherMappingAll = array();
    	
    	foreach ($rankingPages as $row) {
            $sourcePublisherMapping = array();
    		$rankingPageId = $row['id'];
    		$rankingPageParams = $row;
    		$this->createLog("Getting data for ranking page id: ".$rankingPageId);
    		foreach ($publishers[$rankingPageId] as $publisherId) {
    			$sources = $this->ranking_model->getRecentSourcesForPublisher($rankingPageId,$publisherId);
    			foreach ($sources as $sourceId => $sourceName) {
                    $sourcePublisherMapping[$sourceId] = $publisherId;
    				$sourcePublisherMappingAll[$sourceId] = $publisherId;
    			}
    		}
    		// _p($rankingPageId);
    		// _p($sourcePublisherMapping);die;
            if(empty($sourcePublisherMapping)){
                continue;
            }
    		$rankingPageData = $this->ranking_model->getRankingPageDataWithSourceByRankingId($rankingPageId,array_keys($sourcePublisherMapping));
    		$sourceData = array();
    		$courseIds = array();
    		foreach ($rankingPageData as $data) {
    			$sourceData[$data['source_id']][] = $data['course_id'];
    			$courseIds[$data['course_id']] = $data['course_id'];
    		}
    		if(empty($courseIds)){
    			continue;
    		}
    		$courseObjs = $this->courseRepo->findMultiple($courseIds,array('eligibility'));
	    	$courseObjs = array_filter($courseObjs,function($a){if(!empty($a) && $a->getId()){return true;}return false;});

			$sourceData[0] = array_keys($courseObjs);
			$examData      = array(0 => array_keys($courseObjs));
			$cityData      = array(0 => array_keys($courseObjs));
			$stateData     = array(0 => array_keys($courseObjs));

    		foreach ($courseObjs as $courseId => $courseObj) {
    			$exams = $courseObj->getAllEligibilityExams();
    			foreach ($exams as $examId => $examName) {
    				$examData[$examId][] = $courseId;
    			}
    			$cityData[$courseObj->getMainLocation()->getCityId()][] = $courseId;
    			$stateData[$courseObj->getMainLocation()->getStateId()][] = $courseId;
    		}
    		// add virtual cities if present any
    		$cityIds = array_keys($cityData);
    		$cityObjs = $this->locationRepo->findMultipleCities($cityIds);
    		foreach ($cityObjs as $cityId => $cityObj) {
    			$virtualCityId = $cityObj->getVirtualCityId();
    			if(!empty($virtualCityId)){
    				$cityData[$virtualCityId] = array_merge((array)$cityData[$virtualCityId],$cityData[$cityId]);
    			}
    		}

    		$this->createLog("Preparing Combinations city data for ranking page id: ".$rankingPageId);

    		foreach ($sourceData as $sourceId => $sourceCourses) {
    			foreach ($cityData as $cityId => $cityCourses) {
    				foreach ($examData as $examId => $examCourses) {
    					$courseIds = array_intersect($sourceCourses,$cityCourses,$examCourses);
    					if(count($courseIds) > 0){
    						$this->getInsertRow($insertData,$rankingPageParams,$rankingPageId,$sourceId,$cityId,0,$examId,$courseIds);
    					}
    				}
    			}
    		}

    		$this->createLog("Preparing Combinations state data for ranking page id: ".$rankingPageId);

    		foreach ($sourceData as $sourceId => $sourceCourses) {
    			foreach ($stateData as $stateId => $stateCourses) {
    				foreach ($examData as $examId => $examCourses) {
    					$courseIds = array_intersect($sourceCourses,$stateCourses,$examCourses);
    					if(count($courseIds) > 0){
    						$this->getInsertRow($insertData,$rankingPageParams,$rankingPageId,$sourceId,0,$stateId,$examId,$courseIds);
    					}
    				}
    			}
    		}
    	}
    	$rowsInserted = 0;
    	if(!empty($insertData)){
    		$insertData = $this->mapSourceRowsToPublisherRows($insertData,$sourcePublisherMappingAll);
    		// _p($insertData);die;
    		$rowsInserted = $this->ranking_model->populateRankingNonZeroResultTable($insertData);
    		$this->createLog($rowsInserted.' rows inserted into the table');
    	}
    	$this->createLog("-----------------------DONE-----------------------\n");
    	$scriptEndTime = time();
    	$timeTaken = ($scriptEndTime - $scriptStartTime);
    	modules::run('rankingV2/RankingMain/removeCompleteRankingCache');
    	// send the Ending mail
    	$text = "Ranking Non-zero Cron Completed.<br/>Time Taken : ".$timeTaken." sec<br/>Rows Computed : ".count($insertData)."<br/>Rows Inserted : ".$rowsInserted;
    	$emailIdarray = array('listingstech@shiksha.com');
    	$this->sendCronAlert('Ranking non-zero cron status',$text,$emailIdarray);
    }

    private function getInsertRow(&$insertData,$rankingPageParams,$rankingPageId,$sourceId,$cityId,$stateId,$examId,$courseIds){
    	if(empty($rankingPageId)){
    		throw new Exception('ranking page id cannot be blank');
    	}
    	$temp = array();
		$temp['ranking_page_id']   = $rankingPageId;
		$temp['stream_id']         = empty($rankingPageParams['stream_id']) ? 0 : $rankingPageParams['stream_id'];
		$temp['substream_id']      = empty($rankingPageParams['substream_id']) ? 0 : $rankingPageParams['substream_id'];
		$temp['specialization_id'] = empty($rankingPageParams['specialization_id']) ? 0 : $rankingPageParams['specialization_id'];
		$temp['base_course_id']    = empty($rankingPageParams['base_course_id']) ? 0 : $rankingPageParams['base_course_id'];
		$temp['education_type']    = empty($rankingPageParams['education_type']) ? 0 : $rankingPageParams['education_type'];
		$temp['delivery_method']   = empty($rankingPageParams['delivery_method']) ? 0 : $rankingPageParams['delivery_method'];
		$temp['credential']        = empty($rankingPageParams['credential']) ? 0 : $rankingPageParams['credential'];
		$temp['source_id']         = empty($sourceId) ? 0 : $sourceId;
		$temp['city_id']           = empty($cityId) ? 0 : $cityId;
		$temp['state_id']          = empty($stateId) ? 0 : $stateId;
		$temp['exam_id']           = empty($examId) ? 0 : $examId;
		$temp['courseIds']         = $courseIds;
		// $temp['result_count']      = $count;
		$temp['status']            = 'live';
    	
    	$key = implode('_',array($rankingPageId,$sourceId,$cityId,$stateId,$examId));
    	$insertData[$key] = $temp;
    }

    private function mapSourceRowsToPublisherRows($insertData,$sourcePublisherMapping){
    	$returnData = array();
    	$publisherCounts = array();
    	foreach ($insertData as $oldKey => $row) {
    		$publisherId = empty($sourcePublisherMapping[$row['source_id']]) ? 0 : $sourcePublisherMapping[$row['source_id']];
    		$newKey = implode('_',array($row['ranking_page_id'],$publisherId,$row['city_id'],$row['state_id'],$row['exam_id']));
    		$temp = array();
    		foreach ($row as $key => $value) {
    			if($key == 'source_id'){
    				$temp['publisher_id'] = $publisherId;
    			}
    			else if($key == 'courseIds'){
    				$temp['courseIds'] = array_unique(array_merge((array)$returnData[$newKey]['courseIds'], $value));
    			}
    			else{
    				$temp[$key] = $value;
    			}
    		}
    		$returnData[$newKey] = $temp;
    	}
    	foreach ($returnData as $key => $value) {
    		$returnData[$key]['result_count'] = count($value['courseIds']);
    		unset($returnData[$key]['courseIds']);
    	}
    	return $returnData;
    }

    function sendCronAlert($subject, $body, $emailIds){

    	if(empty($emailIds))
    		$emailIds = $this->mailingList;

    	foreach($emailIds as $key=>$emailId){
    		$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $body, "html", '', 'n');
    	}
    }
    
    function createLog($msg){
        error_log("\n".date("Y-m-d:H:i:s")."    ".$msg,3,"/tmp/rankingNonZero.log");
    }
    
}

