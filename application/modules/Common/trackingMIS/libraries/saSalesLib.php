<?php
class saSalesLib {
    private $CI;
    
    public function __construct(){
        $this->CI = & get_instance();
        $this->usergroupAllowed = array("shikshaTracking");
        $this->CI->load->model('trackingMIS/sasalesmodel');
        $this->saSalesModel = new sasalesmodel();
        $this->MISCommonLib = $this->CI->load->library('trackingMIS/MISCommonLib');
    }

    public function getUniversityDetails($univId,$dateRange,$courseType='all'){

    	$result = array();
    	if((int)$univId >0)
    	{
	    	$univDetails = $this->saSalesModel->getUniversityDetails($univId);
	    	if(count($univDetails)>0)
	    	{
		    	$this->CI->load->builder('ListingBuilder','listing');	
		    	$listingBuilder 					= new ListingBuilder;
			    $this->abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
			    $univObj 							= $this->abroadUniversityRepository->find($univId);
			    $abroadLisitingCommonLib 			= $this->CI->load->library('listing/AbroadListingCommonLib');
			    $courseData 						= $abroadLisitingCommonLib->getUniversityCourseNames($univId);
			    $result['courses']					= $courseData['courses'];
			    $result['courseIds']				= $courseData['courseIds'];
			    $result['univName']					= $univObj->getName();
			    $result['logoLink']					= $univObj->getLogoLink();
			    $locations							= reset($univObj->getLocations());
			    $result['city']						= $locations->getCity()->getName();
			    $result['state']					= $locations->getState()->getName();
			    $result['country']					= $locations->getCountry()->getName();
			    
			    $subscriptionData				= $this->_getUniversitySubscriptionDetails(array_values($courseData['courseIds']),$dateRange,$courseType);
			    if($courseType=='all')
				{
					$result['activeCourseIds'] = $result['allCourseIds'] = $result['courseIds'];
					$result['paidCourseIds'] 	= $subscriptionData['paidCourseIds'];
				}
				elseif($courseType =='paid')
				{
					$result['activeCourseIds']	= $subscriptionData['paidCourseIds'];
					$result['allCourseIds']	= $result['courseIds'];						
				}    
				else
				{
					$result['activeCourseIds']	= array_diff($result['courseIds'],$subscriptionData['paidCourseIds']);
					$result['allCourseIds']	= $result['courseIds'];						
				}
				unset($subscriptionData['paidCourseIds']);
			    $result['dates']					= $subscriptionData;
			}
    	}

    	return $result;
    }

    private function _getUniversitySubscriptionDetails($courseIds,$dateRange)
    {
    	
    	$resultData = $this->saSalesModel->getUniversitySubsciptionDetails($courseIds,$dateRange);
		$subscriptionDetails = reset($resultData['mainResult']);
    	$result = array();
    	$result['subscriptionStartDate'] = ($subscriptionDetails['startDate'] !='')?date('jS M Y',strtotime($subscriptionDetails['startDate'])):"";
    	$result['subscriptionEndDate']   = ($subscriptionDetails['endDate'] !='')?date('jS M Y',strtotime($subscriptionDetails['endDate'])):"";
    	$result['activationStartDate'] 	 = ($subscriptionDetails['activationStartDate'] !="")?date('jS M Y',strtotime($subscriptionDetails['activationStartDate'])):"";
		$result['paidCourseIds'] 		 = $resultData['paidCourseIds'];
    	return $result;
    	
    }

    private function _getHistogramInterValType($days)
    {
    	if($days<=31)
		{
			$intervalType = 'day';
			$intervalFormat = 'dd-MMM';
		}
		else if($days<=180)
		{
			$intervalType = 'week';
			$intervalFormat = 'dd-MMM';
		}
		else if($days<=365)
		{
			$intervalType = 'month';
			$intervalFormat = 'MMM';
		}
		else{
			$intervalType = 'month';
			$intervalFormat = 'MMM-yy';
		}

		return array('intervalType'=>$intervalType,'intervalFormat'=>$intervalFormat);
    }
	
	public function getUniversityTrafficFromES($univId, $courseIds, $dateRanges,$days)
	{
		$dateRanges['start'] = str_replace(" ", "T", convertDateISTtoUTC($dateRanges['start'].' 00:00:00'));
		$dateRanges['end'] = str_replace(" ", "T", convertDateISTtoUTC($dateRanges['end'].' 23:59:59'));
		$historgramDetails = $this->_getHistogramInterValType($days);
		$intervalType = $historgramDetails['intervalType'];
		$intervalFormat = $historgramDetails['intervalFormat'];

		$elasticQuery = array();
		$ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->clientCon = $ESConnectionLib->getShikshaESServerConnection();

        $elasticQuery['index'] = PAGEVIEW_INDEX_NAME;
		$elasticQuery['type'] = 'pageview';

		$elasticQuery['body'] =
		'{
			"size": 0,
			"query": {
			  "bool": {
				"filter": {
				  "bool": {
					"must": [
					  {
						"range": {
						  "visitTime": {
							"gte": "'.$dateRanges['start'].'",
							"lte": "'.$dateRanges['end'].'"
						  }
						}
					  },
					  {
						"term": {
						  "isStudyAbroad": "yes"
						}
					  }
					],
					"should": [
					  {
						"bool": {
						  "must": [
							{
							  "term": {
								"pageIdentifier": "universityPage"
							  }
							},
							{
							  "term": {
								"pageEntityId": "'.$univId.'"
							  }
							}
						  ]
						}
					  },
					  {
						"bool": {
						  "must": [
							{
							  "term": {
								"pageIdentifier": "coursePage"
							  }
							},
							{
							  "terms": {
								"pageEntityId": ['.implode(',',$courseIds).']
							  }
							}
						  ]
						}
					  }
					]
				  }
				}
			  }
			},
			"aggs": {
			  "trafficOverTime": {
				"date_histogram": {
				  "field": "visitTime",
				  "interval": "'.$intervalType.'",
				  "format": "'.$intervalFormat.'",
				  "time_zone" : "'.ELASTIC_TIMEZONE.'"
				}
			  }
			}
		  }';

		$result = $this->clientCon->search($elasticQuery);
		$trafficData = $this->_formatElasticUnivTrafficData($result["aggregations"]["trafficOverTime"]["buckets"]);
		$trafficData['total'] = $result["hits"]["total"];
		return $trafficData;
	}
	/*
	 * function to format elastic searhc response for traffic graph
	 */
	private function _formatElasticUnivTrafficData($dataBuckets)
	{
		$numRecs = $duration = array();
		for($i=0;$i<count($dataBuckets);$i++)
		{
			$numRecs[$i] 	= $dataBuckets[$i]['doc_count'];
			$duration[$i] 	= $dataBuckets[$i]['key_as_string'];
		}
		if(count($numRecs)==1){
			$numRecs = array(null,reset($numRecs),null);
			$duration = array('',reset($duration),'');
		}
		return array('numRecs'=>$numRecs,'duration'=>$duration);
	}

	public function getUniversityResponseFromES($univId, $courseIds, $dateRanges,$days)
	{
		$historgramDetails = $this->_getHistogramInterValType($days);
		$intervalType = $historgramDetails['intervalType'];
		$intervalFormat = $historgramDetails['intervalFormat'];
		
		$elasticQuery = array();

		$this->ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->clientCon = $this->ESConnectionLib->getESServerConnectionWithCredentials();

		$elasticQuery['index'] = SHIKSHA_RESPONSE_INDEX_NAME;
		$elasticQuery['type'] = 'response';

		$elasticQuery['body'] =
		'{
			"size": 0,
			"query": {
			  "bool": {
				"filter": {
				  "bool": {
					"must": [
					  {
						"range": {
						  "response_time": {
							"gte": "'.$dateRanges['start'].'T00:00:00",
							"lte": "'.$dateRanges['end'].'T23:59:59"
						  }
						}
					  },
					  {
						"term": {
						  "site": "Study Abroad"
						}
					  },{
						"term": {
						  "considered_for_response": 1
						}
					  },{
						"term": {
						  "response_listing_type": "course"
						}
					  },
						{
						  "terms": {
							"response_listing_type_id": ['.implode(',',$courseIds).']
						  }
						}
					]
				  }
				}
			  }
			},
			"aggs": {
			  "responseOverTime": {
				"date_histogram": {
				  "field": "response_time",
				  "interval": "'.$intervalType.'",
				  "format": "'.$intervalFormat.'"
				},
				"aggs":{
					"rmcResponse":{
						"terms":{
							"field":"source"
						}
					}
				}
			  }
			}
		  }';
				//_p($elasticQuery);die;
		$result = $this->clientCon->search($elasticQuery);
		$responseData = $this->_formatElasticResponseData($result["aggregations"]["responseOverTime"]["buckets"]);
		
		$responseData['total'] = $result["hits"]["total"];
		return $responseData;
	}

	private function _formatElasticResponseData($dataBuckets)
	{
		$numRecs = $duration = $rmcResponseCount = $totalRMCResponse = array();
		$totalRMCResponse=0;
		for($i=0;$i<count($dataBuckets);$i++)
		{
			$numRecs[$i] 	= $dataBuckets[$i]['doc_count'];
			$duration[$i] 	= $dataBuckets[$i]['key_as_string'];

			$rmcCount = 0;
			foreach ($dataBuckets[$i]['rmcResponse']['buckets'] as $key => $value) 
			{
				if($value['key'] == "rateMyChance"){
					$rmcCount+= $value['doc_count'];	
				}
			}
			$rmcResponseCount[$i] 	= $rmcCount;
			$totalRMCResponse+=$rmcCount;
		}
		if(count($numRecs)==1){
			$numRecs = array(null,reset($numRecs),null);
			$duration = array('',reset($duration),'');
			$rmcResponseCount = array(null,reset($rmcResponseCount),null);
		}
		return array('numRecs'=>$numRecs,'duration'=>$duration,'rmcResponse'=>$rmcResponseCount,'totalRMCResponse'=>$totalRMCResponse);
	}
	
	public function getTopResponseCities($courseIds, $dateRanges)
	{
		$userIdsWithResponseDate = $this->getResponseUserIdFromES($courseIds, $dateRanges);
		//_p($userIdsWithResponseDate);
		$citiesWithDates  = $this->_getCitiesForUsersByDuration(array_keys($userIdsWithResponseDate),$dateRanges);
		//_p($citiesWithDates);
		// get current city for users whose city was not found in registrationTracking
		$remainingUserIds = array_diff(array_keys($userIdsWithResponseDate),array_keys($citiesWithDates));
		// get currentCity
		$remainingUserCities = $this->_getUserCurrentCity($remainingUserIds);
		$citiesWithCount = $this->_processUserResponsesForCities($userIdsWithResponseDate,$citiesWithDates,$remainingUserCities);
		//_p($citiesWithCount);
		$returnData = $this->_getCityNames($citiesWithCount);
		//_p($returnData);
		return $returnData;
	}
	private function getResponseUserIdFromES($courseIds, $dateRanges)
	{
		$elasticQuery = array();
		$this->ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->clientCon = $this->ESConnectionLib->getESServerConnectionWithCredentials();
		$elasticQuery['index'] = SHIKSHA_RESPONSE_INDEX_NAME;
		$elasticQuery['type'] = 'response';
		// get number of records first...
		$elasticQuery['body'] = $this->_getESqueryForResponseTopCities($courseIds, $dateRanges,0);
		$countResult = $this->clientCon->search($elasticQuery);
		$size = $countResult['hits']['total'];
		// get actual records...
		$elasticQuery['body'] = $this->_getESqueryForResponseTopCities($courseIds, $dateRanges,$size);
		$result = $this->clientCon->search($elasticQuery);
		$responseData = array();
		foreach($result['hits']['hits'] as $hits)
		{
			if(!array_key_exists($hits['_source']['user_id'],$responseData))
			{
				$responseData[$hits['_source']['user_id']] = array();
			}
			$responseData[$hits['_source']['user_id']][] = substr($hits['_source']['response_time'],0,10);
		}
		return $responseData;
	}
	private function _getCitiesForUsersByDuration($userIds,$dateRange)
	{
		$cities = $this->saSalesModel->getCitiesForUsersByDuration($userIds,$dateRange);
		return $cities;
	}
	private function _getUserCurrentCity($userIds)
	{
		$cities = $this->saSalesModel->getUserCurrentCity($userIds);
		return $cities;
	}
	private function _processUserResponsesForCities($userIdsWithResponseDate,$citiesWithDates,$remainingUserCities)
	{
		$cityCount = array();
		// loop each response row(userid, responsedate)
		foreach($userIdsWithResponseDate as $userId=>$responseDates)
		{
			foreach($responseDates as $respDate)
			{
				// check in registration tracking data
				$cityId  = $this->_getResponseCityByNearestDate($citiesWithDates[$userId],$respDate);
				// or check in remaining user cities (this one comes from tuser)
				if(is_null($cityId))
				{
					$cityId = $remainingUserCities[$userId];
				}
				if(!is_null($cityId))
				{
					// increment count
					if(!array_key_exists($cityId,$cityCount))
					{
						$cityCount[$cityId] = 0;
					}
					$cityCount[$cityId]++;
				}
				// still cant find it ? skip that respsonse !!
			}
		}
		arsort($cityCount);
		return $cityCount;
	}
	/*
	 * function to find most recent city before given response date
	 * @params:
	 * 1. citiesWithDates :
	 * Array
     *   (
     *       [0] => Array
     *           (
     *               [city] => 912
     *               [date] => 2016-05-28
     *           )
     *		 ...
     *   ) ###### Note: this is sorted in ascending order of dates
     *   2. response date 
	 */
	private function _getResponseCityByNearestDate($citiesWithDates,$respDate)
	{
		$returnCityId = null;
		foreach($citiesWithDates as $row)
		{
			// if date is before respdate, set as returnCityId & check next one
			if(strtotime($respDate)>=strtotime($row['date']))
			{
				$returnCityId = $row['city'];
			}else{
			//(if the next date is greater than resp date, there no need to look further because the array is sorted in ascending order of dates)
				break;				
			}
		}
		return $returnCityId;
	}
	/*
	 * function to get city names
	 */
	private function _getCityNames($citiesWithCount)
	{
		   if(count($citiesWithCount)==0)
		   {
				   return false;
		   }
		   $returnData = array();
		   $this->CI->load->builder('LocationBuilder','location');
		   $locationBuilder = new LocationBuilder;
		   $locationRepository = $locationBuilder->getLocationRepository();
		   $cityObjs = $locationRepository->findMultipleCities(array_keys($citiesWithCount));
		   $stateIds = array_filter(array_map(function($a){ if($a->getStateId() != -1){ return $a->getStateId();} },$cityObjs));
		   $stateObjs = $locationRepository->findMultipleStates($stateIds);
		   foreach($cityObjs as $cityId => $cityObj)
		   {
				   $stateName = "";
				   if($stateObjs[$cityObj->getStateId()] instanceof State)
				   {
						   $stateName = ", ".$stateObjs[$cityObj->getStateId()]->getName();
				   }
				   $returnData[$cityId]=array('name'=>$cityObj->getName().$stateName,'count'=>$citiesWithCount[$cityId]);
		   }
		   return $returnData;
	}
	/*
	 * to create the ES query for top cities widget
	 * @param: $courseIds, $dateRanges, $size
	 * @return: ES query body
	 */
	private function _getESqueryForResponseTopCities($courseIds,$dateRanges,$size=0)
	{
		return
		'{	"size" : '.$size.',
			"_source" : ["user_id","response_time"],
			"query": {
			  "bool": {
				"filter": {
				  "bool": {
					"must": [
					  {
						"range": {
						  "response_time": {
							"gte": "'.$dateRanges['start'].'T00:00:00",
							"lte": "'.$dateRanges['end'].'T23:59:59"
						  }
						}
					  },
					  {
						"term": {
						  "site": "Study Abroad"
						}
					  },
					  {
						"terms": {
							"response_listing_type_id": ['.implode(',',$courseIds).']
						}
					  },{
						  "term": {
							"considered_for_response": 1
						  }
						},
						{
						  "term": {
							"response_listing_type": "course"
						  }
						}
					]
				  }
				}
			  }
			}
		  }';
	}
	public function getPopularCourseResponsesFromES($courseIds, $dateRanges,$courseType, $paidCourseIds)
	{
		$elasticQuery = array();
		$ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->clientCon = $ESConnectionLib->getESServerConnectionWithCredentials();
		$elasticQuery['index'] = SHIKSHA_RESPONSE_INDEX_NAME;
		$elasticQuery['type'] = 'response';
		$elasticQuery['body'] =
		'{
			"size": 0,
			"query": {
			  "bool": {
				"filter": {
				  "bool": {
					"must": [
					  {
						"range": {
						  "response_time": {
							"gte": "'.$dateRanges['start'].'T00:00:00",
							"lte": "'.$dateRanges['end'].'T23:59:59"
						  }
						}
					  },
					  {
						"term": {
						  "site": "Study Abroad"
						}
					  },
						{
						  "terms": {
							"response_listing_type_id": ['.implode(',',$courseIds).']
						  }
						},
						{
						  "term": {
							"considered_for_response": 1
						  }
						},
						{
						  "term": {
							"response_listing_type": "course"
						  }
						}
					]
				  }
				}
			  }
			},
			"aggs": {
				"courseResponses": {
					"terms": {
						"field": "response_listing_type_id",
						"size" : '.count($courseIds).'
					}
				}
			}
		}';
				//_p($elasticQuery);die;
		$result = $this->clientCon->search($elasticQuery);
		$responses = $this->_addStatusToPopularCourseResponses($result["aggregations"]["courseResponses"]["buckets"],$courseType, $paidCourseIds);
		$responseData = array('total' => $result["hits"]["total"],
							  'responses' => $responses);
		return $responseData;
	}
	
	public function getComparedUniversities($courseIds,$exclusionIds,$dateRanges)
	{
		$comparedResults = $this->saSalesModel->getComparedUniversities($courseIds, $exclusionIds, $dateRanges);
		$comparisonData = array();
		if(count($comparedResults)>0)
		{
			$univIds = array_map(function($a){return $a['university_id'];},$comparedResults);
			$this->CI->load->builder('ListingBuilder','listing');	
			$listingBuilder 					= new ListingBuilder;
			$this->abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
			$univObjs 							= $this->abroadUniversityRepository->findMultiple($univIds);
			foreach($comparedResults as $comparison)
			{
				if(!($univObjs[$comparison['university_id']] instanceof University)){
					continue;
				}
				$location = $univObjs[$comparison['university_id']]->getMainLocation();
				$comparisonData[] = array('univId'=>$comparison['university_id'],
										  'count'=>$comparison['compCount'],
										  'location'=>$location->getCity()->getName().", ".$location->getCountry()->getName(),
										  'name'=>$univObjs[$comparison['university_id']]->getName(),
										  'logo'=>$univObjs[$comparison['university_id']]->getLogoLink());
			}
		}
		return $comparisonData;
	}
	
	private function _addStatusToPopularCourseResponses($buckets,$courseType, $paidCourseIds)
	{
		$responseData = array();
		foreach($buckets as $bucket)
		{
			$bucket['status'] = ($courseType != 'all'?ucfirst($courseType):(in_array($bucket['key'],$paidCourseIds)?'Paid':'Free'));
			$responseData[] = $bucket;
		}
		return $responseData;
	}
	public function getRMCWithExamsStudentData($courseIds,$dateRange)
	{
		if(count($courseIds)==0)
		{
			return array();
		}

		$userIds = $this->getUsersByCoursesES($courseIds,$dateRange);
		if(count($userIds)==0) {
			return array();
		}
		$result = $this->saSalesModel->getRMCNoteStatusByUserId($userIds,$dateRange);
		
		$userClassification = array('touched_spoken'=>array(),'touched_not_spoken'=>array(),'untouched'=>array());
		// process each note :
		foreach($result as $row)
		{
			switch($row['contacted_status'])
			{
				case 'touched_spoken':
						$userClassification['touched_spoken'][$row['user_id']] = true;
						unset($userClassification['touched_not_spoken'][$row['user_id']]);
						unset($userClassification['untouched'][$row['user_id']]);
						break;
				case 'touched_not_spoken':
						if($userClassification['touched_spoken'][$row['user_id']]) // 2nd best
						{ 
							break;                  //skip it, but we'll keep looking 
						}else{ 
							$userClassification['touched_not_spoken'][$row['user_id']] = true;
							unset($userClassification['untouched'][$row['user_id']]);
						} 
						break;
				case 'untouched':
						if($userClassification['touched_spoken'][$row['user_id']] ||
						   $userClassification['touched_not_spoken'][$row['user_id']]) // 2nd best
						{ 
							break;                  //skip it, but we'll keep looking 
						}else{ 
							$userClassification['untouched'][$row['user_id']] = true;
						}
						break;
			}
		}

		$this->CI->config->load('studyAbroadCMSConfig');
		$contactedStatus = $this->CI->config->item("RMC_USER_CONTACTED_STATUS");
		// return the counts:
		$userCount = array(
			$contactedStatus['touched_spoken'] => count($userClassification['touched_spoken']),
			$contactedStatus['touched_not_spoken'] => count($userClassification['touched_not_spoken']),
			$contactedStatus['untouched'] => count($userClassification['untouched'])
		);
		return $userCount;
	}
	public function getShortlistedStudent($courseIds,$dateRange){

		if(is_array($courseIds) && count($courseIds) > 0 && count($dateRange)>0){
			$studentDetails = $this->saSalesModel->getShortlistedStudent($courseIds,$dateRange);
		}
		$studentCount = 0;
		$result  = array();
		if(count($studentDetails)>0){
			foreach ($studentDetails as $key => $value) {
				$studentCount++;
				$studentIds[] = $value['userId'];
			}
		}
		$result['studentIds'] 	 = $studentIds;
		$result['studentCount'] = $studentCount;
		return $result;
	}

	public function getUniversityFinalizedStudent($courseIds,$dateRange,$onlySubmittedCourses=false){

		if(is_array($courseIds) && count($courseIds) > 0 && count($dateRange)>0){
			$studentDetails = $this->saSalesModel->getUniversityFinalizedStudent($courseIds,$dateRange,$onlySubmittedCourses);
		}
		$studentCount = 0;
		$result  = array();
		if(count($studentDetails)>0){
			foreach ($studentDetails as $key => $value) {
				$studentCount++;
				$studentIds[] = $value['userId'];
			}
		}
		$result['studentIds'] 	 = $studentIds;
		$result['studentCount'] = $studentCount;
		return $result;
	}

	public function getAppliedStudent($courseIds,$dateRange){

		if(is_array($courseIds) && count($courseIds) > 0 && count($dateRange)>0){
			$visaDetails 	 = $this->saSalesModel->getAppliedStudent('visa',$courseIds,$dateRange);
			$admittedDetails = $this->saSalesModel->getAppliedStudent('admitted',$courseIds,$dateRange);
		}
		$visaStudentCount =$admittedStudentCount= 0;
		$visaStudentIds   =$admittedStudentIds  = array();
		$result  = array();
		if(count($visaDetails)>0){
			foreach ($visaDetails as $key => $value) {
				$visaStudentCount++;
				$visaStudentIds[] = $value['userId'];
			}
		}
		if(count($admittedDetails)>0){
			foreach ($admittedDetails as $key => $value) {
				$admittedStudentCount++;
				$admittedStudentIds[] = $value['userId'];
			}
		}
		$result['totalCount'] 	 	= $visaStudentCount+$admittedStudentCount;
		$result['visaStudentIds'] 	 	= $visaStudentIds;
		$result['admittedStudentIds'] 	= $admittedStudentIds;
		$result['visaStudentCount'] 	= $visaStudentCount;
		$result['admittedStudentCount'] = $admittedStudentCount;
		return $result;
	}

	public function getAppliedStudentDetails($tabName,$studentIds){
		if(is_array($studentIds) && count($studentIds) > 0){
			$details 	 = $this->saSalesModel->getAppliedStudent($tabName,array(),array(),$studentIds);
		}
		$student   = array();
		$result  = array();
		if(count($details)>0){
			foreach ($details as $key => $value) {
				$student[$value['userId']] = $value;
			}
		}
		return $student;
	}

	public function getStudentDetailTabHeading($tabName,$count=0)
	{
		switch ($tabName) {
			case 'shortlist':
				$heading = "University Suggested in Shortlist to ".$count." Student".(($count>1)?'s':'');
				break;

			case 'finalized':
				$heading = "University Suggested in finalized stage to ".$count." Student".(($count>1)?'s':'');
				break;

			case 'application':
				$heading = "Application submitted  by ".$count." Student".(($count>1)?'s':'');
				break;

			case 'visa':
				$heading = $count." Student".(($count>1)?'s':'')." in ".$tabName;
				break;

			case 'admitted':
				$heading = $count." Student".(($count>1)?'s':'')." ".$tabName;
				break;				
			
			case 'Accepted':
			case 'Submitted':
			case 'Rejected':
				$heading = $count." Student".(($count>1)?'s':'')." in ".$tabName." stage";
				break;
			
			default:
				$heading = $tabName;
				break;
		}

		return $heading;
	}

	public function getStudentProfileDetails($studentIds)
	{
		if(is_array($studentIds) && count($studentIds) > 0)
		{
			
			$this->CI->load->model('user/usermodel');
	      	$usermodel = new usermodel;
	      	$studentDetails = array();

	      	$this->CI->load->builder('LocationBuilder','location');

	        $locationBuilder = new LocationBuilder;
	        $locationRepository = $locationBuilder->getLocationRepository();

	        $this->CI->load->builder('LDBCourseBuilder','LDB');
			$LDBCourseBuilder = new LDBCourseBuilder;
			$ldbRepository = $LDBCourseBuilder->getLDBCourseRepository();

			$abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
        	$allExams =  $abroadCommonLib->getAbroadExamsMasterList();

        	$shikshaApplyCommonLib = $this->CI->load->library('rateMyChances/ShikshaApplyCommonLib');
        	$rmcPostingLib = $this->CI->load->library('shikshaApplyCRM/rmcPostingLib');

        	$stages = $rmcPostingLib->getStageTypesForShikshaApplyCourses();
        	unset($stages[0]);

        	$allCounselorDetails = $rmcPostingLib->getAllRMCCounsellor();
        	$counselorNames = array(); 
        	foreach ($allCounselorDetails as $key => $value) 
        	{
        		$counselorNames[$value['counsellor_id']] = $value['counsellor_name'];
        	}
        	unset($allCounselorDetails);

        	$studentDetails = array();
	      	foreach ($studentIds as $key => $value) {
	      		$studentDetails[$value] = $this->_getStudenDetailById($value,$allExams,$usermodel,$locationRepository,$shikshaApplyCommonLib,$rmcPostingLib,$stages);
	      	}

	      	$shortlistDetail = $this->saSalesModel->getShortlistLatestRowByUserId($studentIds);
	      	foreach ($studentDetails as $key => $value) 
	      	{
	      		//userId,termSeason,termYear,courseName,addedOn
	      		if($shortlistDetail[$key] != '')
	      		{
		      		$studentDetails[$key]['courseName'] = $shortlistDetail[$key]['courseName'];
		      		$studentDetails[$key]['termSeason'] = $shortlistDetail[$key]['termSeason'];
		      		$studentDetails[$key]['termYear'] = $shortlistDetail[$key]['termYear'];
		      		$studentDetails[$key]['shortlistSentOn'] = date('jS M Y' ,strtotime($shortlistDetail[$key]['addedOn']));
	      		}
	      		$studentDetails[$key]['currentCounsellor'] = $counselorNames[$studentDetails[$key]['currentCounsellor']];
	      	}


		}
		//_p($studentDetails);
		return $studentDetails;
		
      	
	}



	private function _getStudenDetailById($candidateId,$allExams,$usermodel,$locationRepository,$shikshaApplyCommonLib,$rmcPostingLib,$stages)
    {     
    	$userData = array();   

		$user = $usermodel->getUserById($candidateId);
		if(is_object($user))
		{
	        $userData = array();
	        $userData['userId']     = $user->getId();
	        $userData['firstName']  = $user->getFirstName();
	        $userData['lastName']   = $user->getLastName();
		    $userData['email']      = $user->getEmail();
	        $userData['ISDCode']    = $user->getISDCode();
	        $userData['countryId']  = $user->getCountry();
		    $userData['mobile']     = $user->getMobile();
		    $workExArr = array(
	            "-1" => "No Experience",
	            "0"  => "< 1 year",
	            "1"  => "1 - 2 years",
	            "2"  => "2 - 3 years",
	            "3"  => "3 - 4 years",
	            "4"  => "4 - 5 years",
	            "5"  => "5 - 6 years",
	            "6"  => "6 - 7 years",
	            "7"  => "7 - 8 years",
	            "8"  => "8 - 9 years",
	            "9"  => "9 - 10 years",
	            "10"  => "> 10 years"
	        );
	        $userData['workXP']     = $workExArr[$user->getExperience()];

	        $userCity = $user->getCity();
	        if(!empty($userCity))
	        {
	            $cityObj = $locationRepository->findCity($userCity);
	            $userData['cityName']         = $cityObj->getName();
	            if($cityObj->getStateId()!=1 && $cityObj->getStateId()!=-1)
	            {
	            $stateObj = $locationRepository->findState($cityObj->getStateId());
	            $userData['stateName']         = $stateObj->getName();	
	            }    
	        }            
	        $loc = $user->getLocationPreferences();
	        $userPreferredDestinations = array();
	        foreach($loc  as $location) {
	            $countryId = $location->getCountryId();
	            if($countryId > 2)
	            {
	                array_push($userPreferredDestinations, $countryId);
	            }
	        }
	        $userData['countries'] = $userPreferredDestinations;
	        
	        $masterExamCutoff = array();
	        foreach($allExams as $masterExam)
	        {
	            $masterExamCutoff[$masterExam['exam']] = array("cutOff"=>$masterExam['maxScore'],
	                                                           "range"=>$masterExam['range']);
	        }
	        $userEducationData = $userExamData = array();
		    $userEducation = $user->getEducation();
	        if($userEducation) {// if there is any education data... 
	            foreach($userEducation as $education) {// ... get exams
	                $data = array();
	                $data['name']       = $education->getName();
	                $data['level']      = $education->getLevel();
	                $data['marks']      = $education->getMarks();
	                $data['marksType']  = $education->getMarksType();
	                $data['board']      = $education->getBoard();
	                $data['stream']     = $education->getSubjects();
	                if($education->getLevel() == 'Competitive exam' && array_key_exists($education->getName(),$masterExamCutoff))
	                {
	                        $userExamData[$data['name']] = $data['marks'];
	                }
	                else{
	                    if(array_key_exists($data['name'],$userEducationData))
	                    {
	                        $userEducationData[$data['name']]['stream'] = $userEducationData[$data['name']]['stream'].', '.$data['stream'];
	                    }
	                    else{
	                        $userEducationData[$data['name']] = $data;
	                    }
	                }
	            }
	        }
	        $userData['education']        = $userEducationData;
	        $userData['city']             = $userCity;            
	        $userData['exams']            = $userExamData;
	        $userData['imageUrl']  		  = $user->getAvatarImageURL();
	    	if(is_object($user->getUserAdditionalInfo()))
	    	{
	    		$userData['graduation'] = $user->getUserAdditionalInfo()->getGradCollege();
	    		$userData['currentClass'] = $user->getUserAdditionalInfo()->getCurrentClass();
	    		$userData['currentSchool'] = $user->getUserAdditionalInfo()->getCurrentSchool();
			}


            $userData['userCurrentStage'] =  $stages[$shikshaApplyCommonLib->getUserStage($candidateId)];
            
            $currentCounsellor = $rmcPostingLib->getCounsellorForStudent($candidateId);
            $userData['currentCounsellor'] = $currentCounsellor[0]['counsellorId'];
            	
        }
        return $userData;
    }
	
	public function getApplicationProcessWidgetData($courseIds,$dateRange)
	{
		if(count($courseIds)==0)
		{
			return array();
		}
		$userApplicationProcessData = array();
		$result = $this->saSalesModel->getApplicationProcessDataFromFinalizedCourses($courseIds,$dateRange);

		$studentWiseRows= array();
		$admissionStatusPriority = array('accepted'=>1,'rejected'=>2,'completed'=>3,'submitted'=>4,'inProgress'=>5);
		$admissionStatusCount = array('Accepted'=>0,'Submitted'=>0,'Rejected'=>0,'Editorial'=>0,'Completed'=>0);
		foreach($result as $row)
		{
			if(is_null($studentWiseRows[$row['userId']]))
			{
				$studentWiseRows[$row['userId']] = array();
			}
			if(
			   (is_null($studentWiseRows[$row['userId']]['admissionOffered']) ||
			    $admissionStatusPriority[$studentWiseRows[$row['userId']]['admissionOffered']]>$admissionStatusPriority[$row['admissionOffered']]) &&
			   $row['admissionOffered'] != 'inProgress'  // remove this to include editorial case if required
			  )
			{
				// if a higher priority is encountered, we need to reduce the count of previous status
				if($admissionStatusPriority[$studentWiseRows[$row['userId']]['admissionOffered']]>$admissionStatusPriority[$row['admissionOffered']])
				{
					$admissionStatusCount[ucfirst($studentWiseRows[$row['userId']]['admissionOffered'])]--;
				}
				$studentWiseRows[$row['userId']] =
				array(	
						'admissionOffered'=>$row['admissionOffered'],
						'scholarshipReceived'=>$row['scholarshipReceived'],
						'scholarshipDetails'=>$row['scholarshipDetails']
						);
				// increment current row's status' count
				$admissionStatusCount[ucfirst($row['admissionOffered'])]++;
			}
		}
		$statusWiseStudentIds = array();
		foreach($studentWiseRows as $studentId => $studentDetail)
		{
			$statusWiseStudentIds[$studentDetail['admissionOffered']][] = $studentId;
		}
		$userApplicationProcessData = array(
											'data'=>$studentWiseRows,
											'studentIds'=>$statusWiseStudentIds,
											'countData'=>$admissionStatusCount
											);
		return $userApplicationProcessData;
	}

	function getCandidatesDocumentsByDocumentType($candidateIds,$documentTypes)
	{
        $rmcPostingLib = $this->CI->load->library('shikshaApplyCRM/rmcPostingLib');
        $documents = $rmcPostingLib->getCandidatesDocumentsByDocumentType($candidateIds,$documentTypes);
        return $documents;
	}

	private function getUsersByCoursesES($courseIds, $dateRanges) {
		$elasticQuery = array();

		$this->ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
		$this->clientCon = $this->ESConnectionLib->getESServerConnectionWithCredentials();

		$elasticQuery['index'] = SHIKSHA_RESPONSE_INDEX_NAME;
		$elasticQuery['type'] = 'response';
		$elasticQuery['body'] =
			'{
			"size": 0,
			"query": {
			  "bool": {
				"filter": {
				  "bool": {
					"must": [
					  {
						"range": {
						  "response_time": {
							"gte": "'.$dateRanges['start'].'T00:00:00",
							"lte": "'.$dateRanges['end'].'T23:59:59"
						  }
						}
					  },
					  {
						"term": {
						  "site": "Study Abroad"
						}
					  },{
						"term": {
						  "considered_for_response": 1
						}
					  },{
						"term": {
						  "response_listing_type": "course"
						}
					  },
						{
						  "terms": {
							"response_listing_type_id": ['.implode(',',$courseIds).']
						  }
						}
					]
				  }
				}
			  }
			},
			"aggs": {
			  "uniq_user" : {
				"terms" : { 
					"field" : "user_id"
				}
			  }
			}
		  }';
		//_p($elasticQuery);die;
		$result = $this->clientCon->search($elasticQuery);
		$responseData = $result["aggregations"]["uniq_user"]["buckets"];
		$userIds = array_map(function($u){return $u['key']; },$responseData);
		return $userIds;
	}
}

?>    