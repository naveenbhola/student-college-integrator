<?php

include_once('UserSearchCriteriaEvaluator.php');

class ActivityBased implements UserSearchCriteriaEvaluator
{
	private $CI;
	private $model;
	private $extraCriteria = array();

	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model('user/activitybaseduserfindermodel');
		$this->model = new ActivityBasedUserFinderModel;
	}

	public function setExtraCriteria($extraCriteria)
	{
		$this->extraCriteria = $extraCriteria;
	}

	public function evaluate($criteriaSet)
	{
		if($criteriaSet['usersettype'] == 'Activity') {
			$count = $this->getActivityUsersByCriteria($criteriaSet);
			return $count;
		}

		foreach($criteriaSet as $key => $criteria) {
			$users = array();
			$valid_key = false;

			if($key == 'targetDB') {
				$users = $this->model->getActivityUsersByCriteria($criteriaSet);
				$valid_key = true;
			} 
			else if($key == 'timeRange') {
				$users = $this->model->getUsersForTimeRange($criteriaSet);
				$valid_key = true;
			} 
			else if($key == 'registration') {
				if($criteria['type'] == 'all') {
					continue;
				}
				else {
					$users = $this->_getUserSetForRegistrationActivity($criteria); 
					$valid_key = true;
				}
			}	
			else if($key == 'response') {
				$users = $this->_getUserSetForResponseActivity($criteria);
				$valid_key = true;
			}
			else if($key == 'responseRegistration') {
				$users = $this->model->getResponseRegistrations($criteria, $this->extraCriteria);
				$valid_key = true;
			}
			else if($key == 'mailerSentTrigger') {
				$this->CI->load->model('mailer/mailermodel');
				$mailermodel = new mailermodel;
				$users = $mailermodel->getUserMailerSentTrigger($criteria, $this->extraCriteria);
				$valid_key = true;
			}
			else if($key == 'reportSpam') {
				$this->CI->load->model('mailer/mailermodel');
				$mailermodel = new mailermodel;
				$users = $mailermodel->getUsersWhoReportedSpam($criteria, $this->extraCriteria);
				$valid_key = true;
			}

			if (!empty($users) && $valid_key) {
				if (isset($userSet)) {
					foreach ($userSet as $userid=>$flag) {
						if (!isset($users[$userid])) {
							unset($userSet[$userid]);
						}
					}
				}
				else {
					$userSet = $users;
				}
			}
			else if ($valid_key) {
				return array();
			}

		}

		unset($users);
		return isset($userSet) ? $userSet : array();
	}
	
	private function _getUserSetForRegistrationActivity($criteria)
	{
		$registrationType = $criteria['type'];
		if($registrationType == 'short') {
			$userSet = $this->model->getShortRegisteredUsers($criteria, $this->extraCriteria);
		}
		else if($registrationType == 'long') {
			if($criteria['source'] == 'abroad') {
				$userSet = $this->model->getStudyAbroadLeads($criteria, $this->extraCriteria);
			}
			else {
				$userSet = $this->model->getLDBUsers($criteria, $this->extraCriteria);
			}
		}
		
		return $userSet;
	}
	
	private function _getUserSetForResponseActivity($criteria)
	{
		foreach($criteria as $responseSource => $responseSourceCriteria) {
			$users = array();
			$users = $this->model->getResponseUsers($responseSourceCriteria, $this->extraCriteria);
			
			if (!empty($users)) {
				if (isset($userSet)) {
					foreach ($userSet as $userid=>$flag) {
						if (!isset($users[$userid])) {
							unset($userSet[$userid]);
						}
					}
				}
				else {
					$userSet = $users;
				}
			}
			else {
				return array();
			}
		}
		return isset($userSet) ? $userSet : array();
	}
	
	private function _getUserSetForOnlineFormsActivity($criteria)
	{
		$userSet = $this->model->getOnlineFormUsers($criteria, $this->extraCriteria);
		return $userSet;
	}
	
	private function _getUserSetForAnAActivity($criteria)
	{
		$userSet = $this->model->getAnAUsers($criteria, $this->extraCriteria);
		return $userSet;
	}

	private function getActivityUsersByCriteria($criteria) {

		if(empty($criteria)) {
			return;
		}

	    $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
	    $ESClientConn = $ESConnectionLib->getESServerConnectionWithCredentials();
		$status = $this->checkIfElasticsearchRunning($ESClientConn);		
		if($status == 'fail') {
			return;
		}

	    $queryGenerator   = $this->CI->load->library("response/responseProcessorElasticQueryGenerator");
        $responseParser   = $this->CI->load->library("response/responseProcessorElasticParser");

		$data = $this->formatQueryData($criteria);
		// $aggsName = 'usersCount';
		// $data['aggQuery'] = array(
		// 	$aggsName => array(
		// 		'terms' => array(
		// 			'field' => "user_id"
		// 		)									
		// 	)										
		// );
		try {
		    if($criteria['countFlag']) {

				$data['size'] = 0;
				$data['filterPath'] = 'hits.total';

	        	$queryParams = $queryGenerator->getResponsesDataByListingIds($data);
	        	$response    =  $ESClientConn->search($queryParams);
	    		$result      = $responseParser->getResponseCount($response);
	    		
    		} else {

	    		$scrollTime = '5m';
				$data['scroll'] = $scrollTime;
				$data['size'] = 10000;
				$data['sort']    = array('user_id'=> array('order'=>'desc'));
				$data['filterPath'] = '_scroll_id,hits.hits._source';

	        	$queryParams = $queryGenerator->getResponsesDataByListingIds($data);			
	   	        $response = $ESClientConn->search($queryParams);
	    		$result = $responseParser->parseResponsesData($response, $ESClientConn, $scrollTime);

			}
			return $result;
    	
		} 
		catch (Exception $e) {
				return array();
		}
     	
	}

	function checkIfElasticsearchRunning($ESClientConn){
		$status = 'pass';
        if(!($ESClientConn->ping() == true)){
        	$status = 'fail';
            usleep(100000);
            if(!($ESClientConn->ping() == true)){                
            	$status = 'fail';
                mail('teamldb@shiksha.com',"Elasticsearch is not running", "Elasticsearch is not running from Activitybased file");
            }
        }
        return $status;
    }

	private function formatQueryData($criteria) {
		$data = array();

		if($criteria['college_ids'] || $criteria['course_ids']) {
				
			$courseIds = $this->formatCourseIdsQueryData($criteria);
			
			$dateCriteria = $this->formatTimeFilterQueryData($criteria);
			
			$city = $this->formatCityQueryData($criteria);

			
	        $data['entityIds'] = $courseIds;
	        $data['cityIds'] = $city;
	        $data['entityType'] = 'course';
	        if(!empty($criteria['listingType'])){
	        	$data['entityType'] = $criteria['listingType'];
	        }
	        
	        $data['userGroups'] = array('sums', 'enterprise', 'cms', 'experts', 'lead_operator', 'saAdmin', 'saCMS', 'saContent', 'saSales');
	        $data['startDate'] = $dateCriteria['startDate'];
	        $data['endDate'] = $dateCriteria['endDate'];

	    }
	    return $data;
	}

	private function formatCourseIdsQueryData($criteria) {

		$courseIds = array();
		if($criteria['college_ids']) {
			
			$this->CI->load->builder("nationalInstitute/InstituteBuilder");
	        $instituteBuilder = new InstituteBuilder();
	        $instituteRepo = $instituteBuilder->getInstituteRepository();
	        $this->CI->load->library("nationalInstitute/InstituteDetailLib");
	        $lib = new InstituteDetailLib();

	        $collegeIdsArray = array();
	        $collegeIdsArray = explode(', ', $criteria['college_ids']);
	        
	        $allInstitutes = $instituteRepo->findMultiple($collegeIdsArray);
	        foreach ($allInstitutes as $instituteId => $instituteObject) {
				$type = $instituteObject->getType();
	            $instituteCourseMap = $lib->getInstituteCourseIds($instituteId, $type,'all', null, false);
	            foreach ($instituteCourseMap['courseIds'] as $key => $course_id) {
	            	$responseCourseIds[$course_id] = TRUE;
					$courseIds[] = $course_id;
	            }
	            
	        }

		}
		
		if($criteria['course_ids']) {
			$paramCourseIds = explode(', ', $criteria['course_ids']);
			foreach ($paramCourseIds as $paramCourseId) {
				$responseCourseIds[trim($paramCourseId)] = TRUE;
				$courseIds[] = trim($paramCourseId);
			}
		}
		
		return $courseIds;
	}

	private function formatTimeFilterQueryData($criteria) {

		$dateCriteria  = array();
		if($criteria['timeRange']) {
			if($criteria['timeRange'] == 'all') {

				$dateCriteria['startDate'] = date('Y-m-d',strtotime('-2 years'));				
				$dateCriteria['endDate'] = date('Y-m-d');

			} else if ($criteria['timeRange'] == 'duration') {
				if (!empty($criteria['timeRangeDurationFrom'])) {
					list($day, $month, $year) = split('/', $criteria['timeRangeDurationFrom']);
					$dateCriteria['startDate'] = $year.'-'.$month.'-'.$day;
				}
				if (!empty($criteria['timeRangeDurationTo'])) {
					list($day, $month, $year) = split('/', $criteria['timeRangeDurationTo']);
					$dateCriteria['endDate'] = $year.'-'.$month.'-'.$day;
				}
			}
			else if ($criteria['timeRange'] == 'interval' && !empty($criteria['timeRangeIntervalDays']) && is_numeric($criteria['timeRangeIntervalDays'])) {
				$interval = intval($criteria['timeRangeIntervalDays']);
				$fromtime = time() - ($interval * 24 * 60 * 60);
				$dateCriteria['startDate'] = date('Y-m-d', $fromtime);
			}
		}
		

		return $dateCriteria;
	}

	private function formatCityQueryData($criteria) {

		$allFieldsData = json_decode($criteria['allFieldsData']);
		$allFieldsData = (array)$allFieldsData;
		if(!empty($allFieldsData)) {
			foreach($allFieldsData as $allCriteriaFields) {
				$city = $allCriteriaFields->city;
			}
		}	

		return $city;
	}
}
