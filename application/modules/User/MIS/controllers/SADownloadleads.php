<?php
    /**
     *
     *  
     * 
     * @category LDB
     * @author Shiksha Team
     * @link https://www.shiksha.com
     */
    
    class SADownloadleads extends MX_Controller {
    	
    	private $columnListArray = array();
	
		function init($leadGenieScope, $searchAgentType)
		{
		    if($leadGenieScope == 'abroad') {
			
			$this->columnListArray[]='Name';
			$this->columnListArray[]='First Name';
			$this->columnListArray[]='Last Name';
			$this->columnListArray[]='Email';
			$this->columnListArray[]='Mobile';
			$this->columnListArray[]='Field of Interest';
			$this->columnListArray[]='Desired Course Level';
			$this->columnListArray[]='Desired Specialization';
			$this->columnListArray[]='Desired Countries';
			$this->columnListArray[]='Exams Taken';
			$this->columnListArray[]='Valid Passport';
			$this->columnListArray[]='Plan to start';
			$this->columnListArray[]='Current Location';
			//$this->columnListArray[]='Budget';
			$this->columnListArray[]='Date of Registration';
			$this->columnListArray[]='Is in NDNC list';
			
		    }else {
			if($searchAgentType == 'lead'){
				$this->columnListArray[]='First Name';
				$this->columnListArray[]='Last Name';
			    $this->columnListArray[]='Full Name';
			    // $this->columnListArray[]='Date Of Interest';
			    $this->columnListArray[]='Email';
			    
			    $this->columnListArray[]='ISD Code';
			    $this->columnListArray[]='Mobile';
				$this->columnListArray[]='NDNC Status';

				// $this->columnListArray[]='Stream';
				// $this->columnListArray[]='Sub Stream';
				// $this->columnListArray[]='Specialization';
				// $this->columnListArray[]='Course';
				// $this->columnListArray[]='Mode';

			    $this->columnListArray[]='Exams Taken';
			    $this->columnListArray[]='Work Experience';
			   
			   	$this->columnListArray[]='Current Country';
			    $this->columnListArray[]='Current City';
			    $this->columnListArray[]='Current Locality';
			   
			}
			if($searchAgentType == 'response'){
				//$this->columnListArray[]='Name';
				$this->columnListArray[]='Full Name';
			    $this->columnListArray[]='First Name';
				$this->columnListArray[]='Last Name';
				$this->columnListArray[]='Matched Response For';
			    $this->columnListArray[]='Response Date';
			    $this->columnListArray[]='NDNC Status';
			    $this->columnListArray[]='Email';
			    $this->columnListArray[]='Mobile';
			    $this->columnListArray[]='Exams Taken';
			    $this->columnListArray[]='Work Experience';
			    $this->columnListArray[]='Current City';
				$this->columnListArray[]='Preferred Study Locations';
			}
		    }
			$this->load->helper(array('form', 'url','date','image','shikshaUtility'));
			//$this->load->helper(array('url','form','shikshautility'));
			$this->load->library(array('SearchAgents_client','ajax','enterprise_client','LDB_Client','ajax','category_list_client','alerts_client'));
			$this->userStatus = $this->checkUserValidation();
		}
	
	//Controller default API to ask for specific API call
        function index()
	    {
		    $searchagentid = $this->input->post('SearchagentId');
		    $searchAgentStatus = $this->input->post('SearchagentStatus');
		    	    
		    $status = 'live';
		    if($searchAgentStatus == 'deleted')
		    	$status = 'history';

		    $endDate = $this->input->post('timerangeTill_'.$searchagentid);		    
		    $startDate = $this->input->post('timerangeFrom_'.$searchagentid);

		    $data['from'] = $startDate;
		    $data['to'] = $endDate;

		    $data = modules::run('enterprise/enterpriseSearch/convertDateFormat', $data);

		    $startDate = $data['from'];
		    $endDate = $data['to'];

		    $startDate = date('Y-m-d 00:00:00',strtotime($startDate ));
		    $endDate = date('Y-m-d 23:59:59',strtotime($endDate));

		    $tracking_data = $this->formatEnterpriseTrackingData($searchagentid, $searchAgentStatus, $startDate, $endDate);
		  

		    $this->load->model('ldbmismodel');
		    $ldbMisModel = new Ldbmismodel();
		    $this->load->library('SearchAgents_client');
		    $SAClientObj = new SearchAgents_client();
			
		    $leadGenieScope = $ldbMisModel->getLeadGenieScope($searchagentid);
		    $searchAgentType = $ldbMisModel->getSearchAgentType($searchagentid,$status);

		    $this->init($leadGenieScope, $searchAgentType);
		    
		    $appId = 1;
		    $search_agents_display_array = $SAClientObj->getSADisplayData($appId, $searchagentid);
			
		    $inputArray = json_decode(base64_decode($search_agents_display_array[0]['inputdata']), true);
		    $displayArray = json_decode(base64_decode($search_agents_display_array[0]['displaydata']), true);
		   	
		   	if(count($inputArray['course_id'])) {
				$dataArrayMR = array();
				$dataArrayMR['courses'] = $inputArray['course_id'];
				$dataArrayMR['startDate'] = date('Y-m-d',strtotime('-1 Month'));
				$dataArrayMR['endDate'] = date('Y-m-d');
		    }
		    
		    
		    //fix
		 //    if(count($dataArrayMR['courses']) && !empty($dataArrayMR['startDate']) && !empty($dataArrayMR['endDate'])) {	

			// $responseData = modules::run('lms/lmsServer/getMatchedResponses', $dataArrayMR['courses'], array(), $dataArrayMR['startDate'], $dataArrayMR['endDate'], FALSE);
			// $responseUsers = $responseData['users'];
			// $matchedCourses = $responseData['courses'];
		 //    }
		    
		    if(($this->userStatus == "false" )||($this->userStatus == "")) {
		    	header('location:/enterprise/Enterprise/loginEnterprise');
		    	exit();
		    }
		    
		    if($this->userStatus[0]['usergroup'] != 'enterprise')
		    {
				header("location:/enterprise/Enterprise/unauthorizedEnt");
		    }
		    
		    // $month = $this->input->post('month');
		    // $year = $this->input->post('year');

		    $this->load->library('Ldbmis_client');
		    $misObj = new Ldbmis_client();
		    
		    $leadsarray = $misObj->getleadsallocatedtosearchagent($appId,$searchagentid,$startDate,$endDate,$this->userStatus[0]['userid'],$status);

		    for($i=0;$i < count($leadsarray);$i++)
		    {
		    	if($leadsarray[$i]['userid']) {
					$array[] = $leadsarray[$i]['userid'];
					$responseUsers[$leadsarray[$i]['userid']]['matchedFor'] = $leadsarray[$i]['matchedFor'];
					$responseUsers[$leadsarray[$i]['userid']]['submitDate'] = $leadsarray[$i]['submitDate'];
				}
		    }
		    
            $csv[] = $this->csvheadings();

            if($displayArray['ExtraFlag'] == 'studyabroad'){
            	$csv[] = $this->getLeadsData($array, '', '', $responseUsers, $displayArray);
            } else{
            	Global $managementStreamMR;
            	Global $engineeringtStreamMR;

            	if($searchAgentType == 'lead') {
            		$csvData = $this->getLeadDataFromSolrForGenie($array);
            	} else if($searchAgentType == 'response') {
            		if($displayArray['DesiredCourse'] == 'Full Time MBA/PGDM'){
            			$displayArray['stream'] = $managementStreamMR;
            		} else if ($displayArray['DesiredCourse'] == 'B.E./B.Tech') {
            			$displayArray['stream'] = $engineeringtStreamMR;
            		}
            		
            		$csvData = $this->getLeadDataFromSolr($array,$displayArray,false,$responseUsers);
            	}
            	
				

           		if($displayArray['stream'] == $managementStreamMR || $displayArray['stream'] == $engineeringtStreamMR){
            		// $csvData = Modules::run('enterprise/enterpriseSearch/filterUserFullTimeMode', $csvData);
            		$csvData = Modules::run('enterprise/enterpriseSearch/filterMrProfiles', $displayArray, $csvData);
            	}

            	$csv[] = $this->convertArrayTocsv($csvData);

            }

            $tracking_data['records_fetched'] = count($csvData);
		    $enterpriseTrackingLib = $this->load->library('enterprise/enterpriseDataTrackingLib');
            $enterpriseTrackingLib->trackEnterpriseData($tracking_data);
            unset($tracking_data);

            
            $data = join('', $csv);
            
            $filename = date('Y-m-d h-i-s').'.csv';
            $mime = 'text/x-csv';
            
            if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
            {
                header('Content-Type: "'.$mime.'"');
                header('Content-Disposition: attachment; filename="'.$filename.'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header("Content-Transfer-Encoding: binary");
                header('Pragma: public');
                header("Content-Length: ".strlen($data));
            }
            else
            {
                header('Content-Type: "'.$mime.'"');
                header('Content-Disposition: attachment; filename="'.$filename.'"');
                header("Content-Transfer-Encoding: binary");
                header('Expires: 0');
                header('Pragma: no-cache');
                header("Content-Length: ".strlen($data));
            }
            echo ($data);

	    }

	    function convertArrayTocsv($csvData){
	    	$ColumnList = $this->columnListArray;

	    	foreach ($csvData as $lead){
		   		
                foreach ($ColumnList as $ColumnName){
                    $csv .= '"'.$lead[$ColumnName].'",';
                }
                $csv .= "\n";
            }
            return $csv;
	    }

	    function getLeadDataFromSolr($userIds,$searchCriteria,$idsRequiredFlag=FALSE,$responseUsers,$portingFlag=FALSE){

			if(empty($userIds) || count($userIds) <= 0){
	    		return ;
	    	}

	    	$this->load->builder('SearchBuilder','search');
        	$this->solrServer = SearchBuilder::getSearchServer();

	    	$this->load->library('LDB/searcher/SearchAgentRequestGenerator');
       		$requestGenerator = new SearchAgentRequestGenerator;

       		if($searchCriteria['stream'] >0){
       			$criteria['streamId'] = $searchCriteria['stream'];
       		}
       		
       		//if(isset($searchCriteria['subStream']) && !empty($searchCriteria['subStream'])){
            //      $criteria['subStreamId'] = $searchCriteria['subStream'];
            //}
       		foreach ($searchCriteria['subStream'] as $key => $value) {
       			if($value != 0) {
       				$subStreamArray[] = $value;
       			}
       		}
	    	if(isset($subStreamArray) && !empty($subStreamArray)){
	    		$criteria['subStreamId'] = $subStreamArray;
	    	}
	    	if($portingFlag == TRUE && $searchCriteria['subStream'] == 0){
	    		$criteria['subStreamId'] = 0;
	    	}

	    	if($searchCriteria['excludeMRPRofile']){
	    		$criteria['excludeMRPRofile'] = true;
	    	}
	    	
	    	if(count($searchCriteria['leadProfileTypeMap'])>0 ){
	    		$criteria['leadProfileTypeMap'] = $searchCriteria['leadProfileTypeMap'];
	    	}

	    	if(count($searchCriteria['leadProfileDataMap'])>0 ){
	    		$criteria['leadProfileDataMap'] = $searchCriteria['leadProfileDataMap'];
	    	}
	    	
	    	if(isset($searchCriteria['ProfileType']) && !empty($searchCriteria['ProfileType'])){
	    		$criteria['ProfileType'] = $searchCriteria['ProfileType'];
	    	}

	    	if(isset($searchCriteria['Viewed']) && !empty($searchCriteria['Viewed'])){
	    		$criteria['Viewed'] = $searchCriteria['Viewed'];
	    	}
	    	if(isset($searchCriteria['Emailed']) && !empty($searchCriteria['Emailed'])){
	    		$criteria['Emailed'] = $searchCriteria['Emailed'];
	    	}
	    	if(isset($searchCriteria['Smsed']) && !empty($searchCriteria['Smsed'])){
	    		$criteria['Smsed'] = $searchCriteria['Smsed'];
	    	}
	    	
	    	$request = $requestGenerator->getUserDataForCSV($userIds,$criteria,$portingFlag);
	    	
	    	$request_array = explode("?",$request); 
	        $response = $this->solrServer->leadSearchCurl($request_array[0],$request_array[1]); 

    	    $response = unserialize($response);

    	    $docResponse = $response['grouped']['userId']['groups'];

    	    unset($response);
    	    $uniqueUserDoc = array();
    	    foreach ($docResponse as $res) {
    	    	$doc = $res['doclist']['docs'][0];
    	    	$uniqueUserDoc[$doc['userId']] = $doc;
    	    }

    	    unset($docResponse);

	        foreach ($uniqueUserDoc as $doc) {
	        	if($criteria['streamId'] == '' || $criteria['streamId']<1){
	        		$criteria['streamId'] = $doc['streamId'];
	        	}
		        $userDataCSV[] =  $this->formatUserDisplayData($doc,$criteria['streamId'],$idsRequiredFlag,$searchCriteria,$responseUsers);
	        }
	        
	        unset($uniqueUserDoc);
	        
    	    return $userDataCSV;

	    }

	    function formatUserDisplayData($doc,$streamId,$idsRequiredFlag,$displayArray,$responseUsers){
	    	global $noSpecName;

	    	$displaydata = json_decode($doc['displayData'],true);

			/*
				Exams Taken
			*/	
            $doc['interestTime'] = str_replace('T', ' ', $doc['interestTime']);
            $doc['interestTime'] = str_replace('Z', ' ', $doc['interestTime']);
			$interestTime = explode(' ', $doc['interestTime']);

            $userDisplayData['userid'] = $displaydata['userId'];
            $userDisplayData['userId'] = $displaydata['userId'];	//put two value to cater the impact
	    	$userDisplayData['First Name'] = $displaydata['firstname'];
	    	$userDisplayData['Last Name'] = $displaydata['lastname'];
	    	$userDisplayData['Full Name'] = $displaydata['firstname'].' '.$displaydata['lastname'];
	    	$userDisplayData['Email'] = $displaydata['email'];
	    	$userDisplayData['ISD Code'] = $displaydata['isdCode'];

	    	if($displaydata['isNDNC'] == 'YES'){
				$isNDNC = 'Do not call';
			} else if($displaydata['isNDNC'] == 'NO'){
				$isNDNC = 'Can call';
			} else{
				$isNDNC = '';
			}
	    	$userDisplayData['NDNC Status'] = $isNDNC;

	    	$workex = '';
			if(isset($displaydata['experience'])){
				$value = $displaydata['experience'];
				if($value == -1){
					$workex = 'No Experience';
				}else if($value == 10){
					$workex = '> 10 Years';
				} else if($value == 0){
					$workex = $value.'-'.(intval($value+1)).' Year';
				} else {
					$workex = $value.'-'.(intval($value+1)).' Years';
				}
			}
			
			$userDisplayData['Work Experience'] = $workex;

	    	$countryName = $this->getCountryName($displaydata['country']);
	    	$userDisplayData['Current Country'] = $countryName;
	    	unset($countryName);

	    	$userDisplayData['Current City'] = $displaydata['CurrentCity'];
	    	$userDisplayData['Current Locality'] = $displaydata['localityName'];
	    	$userDisplayData['Mobile'] = $displaydata['mobile'];
	    	// asort($doc['educationName']);
	    	// $userDisplayData['Exams Taken'] = implode(', ', $doc['educationName']);
	    	$examsArray = array();
	    	foreach ($displaydata['EducationData'] as $educationDetails) {
	    		if($educationDetails['Level'] == 'Competitive exam'){
	    			$examsArray[] = $educationDetails['Name'];
	    		}
	    	}
		    
		    asort($examsArray);
	    	$userDisplayData['Exams Taken'] = implode(', ', $examsArray);
	    	unset($examsArray);

		    $this->load->builder('listingBase/ListingBaseBuilder');
	        $listingBase = new \ListingBaseBuilder();
	        $HierarchyRepository = $listingBase->getHierarchyRepository();
            $BaseCourseRepository = $listingBase->getBaseCourseRepository();
            $this->load->library('listingBase/BaseAttributeLibrary');
        	$baseAttributeLibrary = new BaseAttributeLibrary();

	        unset($listingBase);
	        if(!empty($doc['subStreamId'])){
	        	$subStreamObj = $HierarchyRepository->findMultipleSubstreams($doc['subStreamId']);
	        }
	        
            foreach ($subStreamObj as $key => $value) {
                $value = $value->getObjectAsArray();
                $userDisplayData['Sub Stream'] = $value['name'];              
            }
        

            unset($subStreamObj);

            if($streamId > 0){
            	$streamObj = $HierarchyRepository->findStream($streamId);
            }

            if(is_object($streamObj)){
        		$streamData = $streamObj->getObjectAsArray();
            }

        	$userDisplayData['Stream'] = $streamData['name'];   
        	
        	if($idsRequiredFlag){
        		$userDisplayData['StreamId'] = $streamId;
        		$userDisplayData['SubStreamId'] = implode(',',$doc['subStreamId']); 
        	}

        	if(!empty($doc['specialization'])){
		    	$specializationObj = $HierarchyRepository->findMultipleSpecializations($doc['specialization']);
        	}
        	
            foreach ($specializationObj as $key => $value) {
            	$value = $value->getObjectAsArray();
                $spez[] = $value['name'];
            }

            //text added with config for user data with no specialization
            $noSpectext = $noSpecName['noSpecMapping_data'];

            if($displayArray['spezFormat'] == 'noSpecMapping'){
            	$noSpectext = $noSpecName['noSpecMapping'];
            }

            if(count($spez)<1){
            	$spez[] = $noSpectext;
            }
        
            asort($spez);
            $userDisplayData['Specialization'] = implode(', ', $spez);

            if(count($doc['courseId']) == 1 && $doc['courseId'][0] == 0){
            	$userDisplayData['Course'] = 'Not Available';
            } else {
            	if ($displayArray['isPorting']){
					$userDisplayData['BaseCourseIds'] = $doc['courseId'];
	            }
	            $coursesObj = $BaseCourseRepository->findMultiple($doc['courseId']);
	            foreach ($coursesObj as $key => $value) {
	                $value = $value->getObjectAsArray();
	               	$courseNames[] = $value['name'];
	            }
	            asort($courseNames);
	            $userDisplayData['Course'] = implode(', ', $courseNames);
            }

           	// $userDisplayData['Mode']= implode(', ',$attributesValueNamePair);
            $mode = $doc['attributeValues'];
            if(is_array($mode) && count($mode) > 0) {
		        
		        $parentChildrenMapping = array();
		        $modeData = array();
		        $attributeParentValueIdMapping = $baseAttributeLibrary->getParentValueIdByValueId($mode);
		        
		        foreach ($attributeParentValueIdMapping as $key => $parentValues) {
                    foreach ($parentValues as $parentValue) {
                        
                        if($parentValue == 20 && $key == 33) {
                            $parentChildrenMapping[$parentValue][] = ''; 
                        } else if($parentValue == 21 && !in_array($key,array(33,34,35,36,37,39))) {
                            $parentChildrenMapping[$parentValue][] = ''; 
                        } else {
                        	if(!in_array($parentValue, $mode)){
	                            $mode[] = $parentValue;
	                        }
	                        $parentChildrenMapping[$parentValue][] = $key; 
                        }
                        
                    }
                }

		        $attributesValueNamePair = $baseAttributeLibrary->getValueNameByValueId($mode);

		        foreach ($attributesValueNamePair as $key => $value) {
		            if(array_key_exists($key, $parentChildrenMapping)){
                        $modeData[$value] = '';
                        foreach ($parentChildrenMapping[$key] as $childKey => $childId) {
                            if($childId){
                            	$childArray[] = $attributesValueNamePair[$childId];
                            }
                        }
                        asort($childArray);
                        $modeData[$value] = implode(', ',$childArray);
                    }
		            else if(array_key_exists($key, $attributeParentValueIdMapping)){
		                continue;
		            }
		            else{
		                $modeData[$value] = '';
		            }
		        }

		        $modeDataDisplay = '';
		        foreach ($modeData as $key => $value) {
		            $modeDataDisplay .= $key;
		            if($value != '') 
		                $modeDataDisplay .= ' - '.$value;
		            $modeDataDisplay .= ', ';
		        }
		        $modeDataDisplay = rtrim($modeDataDisplay, ", ");

		        $userDisplayData['Mode'] = $modeDataDisplay;
		        $userDisplayData['modeData'] = $modeData;

		        unset($childArray);
		        unset($modeData);
		        unset($parentChildrenMapping);
		        unset($attributeParentValueIdMapping);
		        unset($attributesValueNamePair);

		    }

		    $userDisplayData['Date Of Interest'] = $interestTime[0];
		    $userDisplayData['View Count'] = $doc['ViewCount'];
		    $userDisplayData['View Credit'] = $doc['ViewCredit'];
		    $userDisplayData['Email Credit'] = $doc['EmailCredit'];
		    $userDisplayData['Sms Credit'] = $doc['SmsCredit'];
		    $userDisplayData['responseCourse'] = $doc['responseCourse'];
		    foreach ($userDisplayData['responseCourse'] as $course) {
		    	$userDisplayData['response_time_'.$course] = $doc['response_time_'.$course];
		    }


		    $userDisplayData['Response Date'] = ($responseUsers[$displaydata['userId']]['submitDate']) ? date('d M Y',strtotime($responseUsers[$displaydata['userId']]['submitDate'])) : "";
		    $matchedForCourseId = ($responseUsers[$displaydata['userId']]['matchedFor']) ? ($responseUsers[$displaydata['userId']]['matchedFor']) : '';

		    if(!empty($matchedForCourseId)){
				
				$matchedCourses[$matchedForCourseId]['CourseName'] = $displayArray['matchedCourses'][$matchedForCourseId];
				$matchedCourses[$matchedForCourseId]['InstituteName'] = $displayArray['matchedCoursesInstitute'][$matchedForCourseId];
			    
		    	foreach($matchedCourses as $matchedCourseId=>$matchedCourse) {
					$displayMatchedCourses[] = $matchedCourse['CourseName'].', '.$matchedCourse['InstituteName'];
				}
		    	$userDisplayData['Matched Response For'] = implode('; ',array_values($displayMatchedCourses));
		    }

		    $this->load->model('LDB/leadsearchmodel');
			$leadSearchModel = new LeadSearchModel;
		    $responseLocations = $leadSearchModel->getResponseLocations(array($displaydata['userId']));
		    $userResponseLocations = $responseLocations[$displaydata['userId']];

		    if($displayArray['MRLocation']) {
				$selectedMRLocations = explode(",", $displayArray['MRLocation']);
				for($i=0;$i<count($selectedMRLocations);$i++) {
					$selectedMRLocations[$i] = trim($selectedMRLocations[$i]);
				}
				// $userResponseLocations = $responseLocations[$displaydata['userId']];
				$matchingLocations = array_intersect($selectedMRLocations, $userResponseLocations);
				$userDisplayData['Preferred Study Locations'] = implode(", ", $matchingLocations);
			}
			else {
				$userDisplayData['Preferred Study Locations'] = implode(", ", $userResponseLocations);
			}
			
		    return $userDisplayData;
		     
	    }

	    function getCountryName($countryId){
	    	$this->articlesAbroadWidgetsModel = $this->load->model('studyAbroadArticleWidget/articlesabroadwidgetsmodel');

	    	$countryName = $this->articlesAbroadWidgetsModel->getCountryNameById($countryId);

	    	return $countryName;
	    }

	    function getLeadDataFromSolrForGenie($userIds){

			if(empty($userIds) || count($userIds) <= 0){
	    		return ;
	    	}

	    	$this->load->builder('SearchBuilder','search');
        	$this->solrServer = SearchBuilder::getSearchServer();

	    	$this->load->library('LDB/searcher/SearchAgentRequestGenerator');
       		$requestGenerator = new SearchAgentRequestGenerator;

	    	$request = $requestGenerator->getUserDataForGenieCSV($userIds);
	    	
	    	$request_array = explode("?",$request); 
	        $response = $this->solrServer->leadSearchCurl($request_array[0],$request_array[1]); 

    	    $response = unserialize($response);

    	    $docResponse = $response['grouped']['userId']['groups'];

    	    unset($response);
    	    $uniqueUserDoc = array();
    	    foreach ($docResponse as $res) {
    	    	$doc = $res['doclist']['docs'][0];
    	    	$uniqueUserDoc[$doc['userId']] = $doc;
    	    }
    	    
    	    unset($docResponse);

	        foreach ($uniqueUserDoc as $doc) {	        	
		        $userDataCSV[] =  $this->formatUserDisplayDataForGenie($doc);
	        }
	        
	        unset($uniqueUserDoc);
    	    return $userDataCSV;

	    }

	    function formatUserDisplayDataForGenie($doc){

	    	$displaydata = json_decode($doc['displayData'],true);

            $userDisplayData['userid'] = $displaydata['userId'];
            $userDisplayData['userId'] = $displaydata['userId'];	//put two value to cater the impact
	    	$userDisplayData['First Name'] = $displaydata['firstname'];
	    	$userDisplayData['Last Name'] = $displaydata['lastname'];
	    	$userDisplayData['Full Name'] = $displaydata['firstname'].' '.$displaydata['lastname'];
	    	$userDisplayData['Email'] = $displaydata['email'];
	    	$userDisplayData['ISD Code'] = $displaydata['isdCode'];

	    	if($displaydata['isNDNC'] == 'YES'){
				$isNDNC = 'Do not call';
			} else if($displaydata['isNDNC'] == 'NO'){
				$isNDNC = 'Can call';
			} else{
				$isNDNC = '';
			}
	    	$userDisplayData['NDNC Status'] = $isNDNC;

	    	$workex = '';
			if(isset($displaydata['experience'])){
				$value = $displaydata['experience'];
				if($value == -1){
					$workex = 'No Experience';
				}else if($value == 10){
					$workex = '> 10 Years';
				} else if($value == 0){
					$workex = $value.'-'.(intval($value+1)).' Year';
				} else {
					$workex = $value.'-'.(intval($value+1)).' Years';
				}
			}
			
			$userDisplayData['Work Experience'] = $workex;

			$countryName = $this->getCountryName($displaydata['country']);
	    	$userDisplayData['Current Country'] = $countryName;
	    	unset($countryName);

	    	$userDisplayData['Current City'] = $displaydata['CurrentCity'];
	    	$userDisplayData['Current Locality'] = $displaydata['localityName'];
	    	$userDisplayData['Mobile'] = $displaydata['mobile'];
	    	
	    	$examsArray = array();
	    	foreach ($displaydata['EducationData'] as $educationDetails) {
	    		if($educationDetails['Level'] == 'Competitive exam'){
	    			$examsArray[] = $educationDetails['Name'];
	    		}
	    	}
		    asort($examsArray);
	    	$userDisplayData['Exams Taken'] = implode(', ', $examsArray);
	    	unset($examsArray);

		    return $userDisplayData;
		     
	    }

        function downloadLeads()
	    {
            $this->init();
		   	
		    if(($this->userStatus == "false" )||($this->userStatus == "")) {
		    	header('location:/enterprise/Enterprise/loginEnterprise');
		    	exit();
		    }
		    
		    if($this->userStatus[0]['usergroup'] != 'enterprise')
		    {
				header("location:/enterprise/Enterprise/unauthorizedEnt");
		    }
		    
		    $data = $this->getClientLeads($this->userStatus[0]['userid']);
            

            $filename = date('Y-m-d h-i-s').'.csv';
            $mime = 'text/x-csv';
            
            if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
            {
                header('Content-Type: "'.$mime.'"');
                header('Content-Disposition: attachment; filename="'.$filename.'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header("Content-Transfer-Encoding: binary");
                header('Pragma: public');
                header("Content-Length: ".strlen($data));
            }
            else
            {
                header('Content-Type: "'.$mime.'"');
                header('Content-Disposition: attachment; filename="'.$filename.'"');
                header("Content-Transfer-Encoding: binary");
                header('Expires: 0');
                header('Pragma: no-cache');
                header("Content-Length: ".strlen($data));
            }
            echo ($data);

	    }
	 
	 function leadsbyCron($clientid, $days)
	    {
		
		$this->init();
		$data = $this->getClientLeads($clientid, $days);
		$ldbObj = new LDB_client();
	    
		$UserIDstoEmailMap = $this->UserIdsEmailMap();
	    
		foreach($UserIDstoEmailMap as $key=>$ClientMailValues){
		    if($key == $clientid){
			$to = $ClientMailValues['email'];
			$name = $ClientMailValues['name'];
		    }
		}
		  		
        	$from = "leads@shiksha.com";
        	$cc = "rithik.haldar@shiksha.com,abhishek.jain@naukri.com,vikas.k@shiksha.com";
        	$filename = date('Y-m-d h-i-s').'.csv';
        	$ldbObj->sendCSVMail($data,$name,$from,$to,$cc,$filename);
	    }

	    
	    function getClientLeads($clientid, $days)
	    {
	    	$csv[] = $this->csvheadings(TRUE, TRUE);
	    	$appId = 1;
		    $this->load->library('Ldbmis_client');
		    $misObj = new Ldbmis_client();
		    $leadsarray = $misObj->getleadsallocatedtoclient($appId,$clientid,$days);
		    
	        for($i=0;$i < count($leadsarray);$i++)
		{
		    $searchagent_leads[$leadsarray[$i]['searchagentid']]['userids'][] = $leadsarray[$i]['userid'];
		    $searchagent_leads[$leadsarray[$i]['searchagentid']]['searchagentName'] = $leadsarray[$i]['searchagentName'];
		}
		    
		foreach ($searchagent_leads as $searchagentid => $value)
		{
		    $searchagentName = $value['searchagentName'];
		    $csv[] = $this->getLeadsData($value['userids'], $searchagentid, $searchagentName);
		}
		
		$data = join('', $csv);
		
		return $data;
            
	    }
	    
        function getLeadsData($array, $searchagentid, $searchagentName, $responseUsers=NULL, $displayArray=NULL)
        {
            $ldbObj = new LDB_client();
            
            for($i=0;$i < count($array);$i++)
            {

            	$UserDetailsArray[$i] = $ldbObj->sgetUserDetails(1,$array[$i]);

            }
                      
            for($i=0;$i < count($UserDetailsArray);$i++)
            {
            	$UserDataArray[$i] = $this->createUserDataArray(json_decode($UserDetailsArray[$i],true), $responseUsers, $displayArray);
            }
                   
            for($i=0;$i < count($UserDataArray);$i++)
            {
            	$leads[] = $UserDataArray[$i][0];
            }
            
	  		//  echo "<pre>";print_r($leads); echo "</pre>";
            $ColumnList = $this->columnListArray;
            $csv = '';
            
	   		foreach ($leads as $lead){
		   		if ($searchagentid !== '')
	            {
	            	$csv .= '"'.$searchagentid.'",';
	            }
	   		    if ($searchagentName !== '')
	            {
	            	$csv .= '"'.$searchagentName.'",';
	            }
                foreach ($ColumnList as $ColumnName){
                    $csv .= '"'.$lead[$ColumnName].'",';
                }
                $csv .= "\n";
            }
			
            return $csv;

     	}
     
	    function csvheadings($searchagentid, $searchagentName)
	    {
            $ColumnList = $this->columnListArray;
            $csv = '';
            
            if (isset($searchagentid))
            {
            	$csv .= '"Lead Genie Id",';
            }
            
            if (isset($searchagentName))
            {
            	$csv .= '"Lead Genie",';
            }
            
            foreach ($ColumnList as $ColumnName){
            	if($ColumnName == 'Date of Registration'){
                    $ColumnName ='Lead Processed Date';
                }
                $csv .= '"'.$ColumnName.'",';
            }
            $csv .= "\n";
            
            return $csv;
     	}
     
	/**
	* API for generating User Data Array
	*/


	function createUserDataArray($UserDetailsArray, $responseUsers=NULL, $displayArray=NULL)
	   {
		// error_log("inside createUserDataArray ".print_r($UserDetailsArray,true));
	       $LocalCourseArray = array();
		   $this->load->model('LDB/leadsearchmodel');
			$leadSearchModel = new LeadSearchModel;
			$responseLocations = $leadSearchModel->getResponseLocations(array_keys($UserDetailsArray));
		
	       foreach($UserDetailsArray as $userDetails)
	       {
		   $formattedUserDetails = array();
			$formattedUserDetails['Name'] = $userDetails['firstname']." ".$userDetails['lastname'];
		   $formattedUserDetails['First Name'] = $userDetails['firstname'];
		   $formattedUserDetails['Last Name'] = $userDetails['lastname'];
		   $formattedUserDetails['Gender'] = $userDetails['gender'];
		   $formattedUserDetails['Age'] = $userDetails['age'];
		   $formattedUserDetails['Desired Course'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['CourseName'];
		   //For Study Abroad Desired Course is Desired Course Level
		   $formattedUserDetails['Desired Course Level'] = $formattedUserDetails['Desired Course'];
		   if ($userDetails['PrefData'][0]['ExtraFlag'] == 'studyabroad') {
			    $courseLevel = $userDetails['PrefData'][0]['SpecializationPref'][0]['CourseLevel1'];
			    if($courseLevel == 'UG') {
				    $formattedUserDetails['Desired Course Level'] = 'Bachelors';
			    }
			    if($courseLevel == 'PG') {
				    $formattedUserDetails['Desired Course Level'] = 'Masters';
			    }
		    }
		    $formattedUserDetails['Field of Interest'] =  $userDetails['PrefData'][0]['SpecializationPref'][0]['CategoryName'];
		    if($userDetails['PrefData'][0]['ExtraFlag']=='testprep'){
			    $formattedUserDetails['Desired Course'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['CourseName'];
		    } else if($userDetails['PrefData'][0]['ExtraFlag']=='studyabroad') {
			    global $studyAbroadPopularCourses;
				if($formattedUserDetails['Field of Interest'] == "All") {
					$desiredCourseId = $userDetails["PrefData"][0]['DesiredCourse'];
					if(array_key_exists($desiredCourseId,$studyAbroadPopularCourses)) {
						$formattedUserDetails['Field of Interest'] = $studyAbroadPopularCourses[$desiredCourseId];
					}
					//$formattedUserDetails['Desired Course'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['CategoryName'];
				}
				$formattedUserDetails['Desired Specialization'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['SpecializationName'];
				for($i=1;$i<count($userDetails['PrefData'][0]['SpecializationPref']);$i++)
					$formattedUserDetails['Desired Specialization'] .= ", ".$userDetails['PrefData'][0]['SpecializationPref'][$x]['SpecializationName'];
		    } else {
			    $formattedUserDetails['Desired Specialization'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['SpecializationName'];
			    for($i = 1;$i<count($userDetails['PrefData'][0]['SpecializationPref']);$i++)
				$formattedUserDetails['Desired Specialization'] .= ", ".$userDetails['PrefData'][0]['SpecializationPref'][$i]['SpecializationName'];
		    }
		   //display full time, part time or both based on user selection
		   //below modification is done for bug id 40313
		   if((($userDetails['PrefData'][0]['ModeOfEducationFullTime']=="yes") && ($userDetails['PrefData'][0]['ModeOfEducationPartTime']=="yes")))
		       $formattedUserDetails['Mode'] = "Full Time, Part Time";
		   else
		   $formattedUserDetails['Mode'] = ($userDetails['PrefData'][0]['ModeOfEducationFullTime']=="yes")?"Full Time":(($userDetails['PrefData'][0]['ModeOfEducationPartTime']=="yes")?"Part Time":"");
		   $prefDetails=$userDetails['PrefData'][0];
		   $datediff=$this->datediff($prefDetails['TimeOfStart'],$prefDetails['SubmitDate']);
		   $formattedUserDetails['Plan to start'] = ($prefDetails['YearOfStart']!='0000')?(($datediff!=0)?"Within ".$datediff:"Immediately"):"Not Sure";
		   if($prefDetails['ExtraFlag'] == 'studyabroad') {
			if($prefDetails['YearOfStart'] == date('Y'))
			    $formattedUserDetails['Plan to start'] = 'Current Year';
			else if($prefDetails['YearOfStart'] == date('Y')+1)
			    $formattedUserDetails['Plan to start'] = 'Next Year';
			else if($prefDetails['YearOfStart'] > date('Y')+1)
			    $formattedUserDetails['Plan to start'] = 'Later';
		    }
		   $formattedUserDetails['Plan to go'] = $formattedUserDetails['Plan to start'];
		   $formattedUserDetails['Work Experience'] = ($userDetails['experience']>0)?($userDetails['experience']." Years"):(($userDetails['experience']==="0")?"Less Than 1 Year":"No Experience");
		    //making check if current city is empty then set LocationPref as current city
		   //below modification is done for bug id 40228
		    if(!empty($userDetails['CurrentCity'])) {
			$formattedUserDetails['Current Location'] = $userDetails['CurrentCity'];
		    }
		   if(!empty($userDetails['localityName'])) {
			$formattedUserDetails['Current Locality'] = $userDetails['localityName'];
		    }
		  /* else {
			$currentCity = '';
			$count = count($userDetails['PrefData'][0]['LocationPref']);
			if($count>=1) {
			$currentCity = $currentCity.$userDetails['PrefData'][0]['LocationPref'][0]['CityName'];
		    for($index=0;$index<$count;$index++) {
			if(($index>0 && ($userDetails['PrefData'][0]['LocationPref'][$index]['CityId']
			!= $userDetails['PrefData'][0]['LocationPref'][$index-1]['CityId']))) {
				$currentCity = $currentCity.', ';
				$currentCity = $currentCity.$userDetails['PrefData'][0]['LocationPref'][$index]['CityName'];
			}
			$formattedUserDetails['Current Location'] = $currentCity;
			error_log('current city'.$currentCity);
		    }
		   }
		   }*/
		    $userBudget = (int)$userDetails['PrefData'][0]['program_budget'];
		    global $budgetToTextArray;
		    if(key_exists($userBudget, $budgetToTextArray)) {
			    $userBudget = $budgetToTextArray[$userBudget];
		    }
		    $formattedUserDetails['Budget'] = $userBudget;
		    $formattedUserDetails['Valid Passport'] = $userDetails['passport'];
		    $sourceoffunding = array();
		    if($userDetails['PrefData'][0]['UserFundsOwn']=="yes"){
			$sourceoffunding[]="Own";
		    }
		    if($userDetails['PrefData'][0]['UserFundsBank']=="yes"){
			$sourceoffunding[]="Bank";
		    }
		    if($userDetails['PrefData'][0]['UserFundsNone']=="yes")
		    {
			$sourceoffunding[]="Other:".$userDetails['PrefData'][0]['otherFundingDetails'];
		    }
		    $formattedUserDetails['Source of Funding'] =implode("/",$sourceoffunding);
		    $preferenceCallTimeArray = array('0'=>'Do not call','1'=>'Anytime','2'=>'Morning', '3'=>'Evening');
		    $formattedUserDetails['Preferred Time to call'] = (is_numeric($prefDetails['suitableCallPref']))?($preferenceCallTimeArray[$prefDetails['suitableCallPref']]):"";
		    $i=0;
		    foreach ($userDetails['EducationData'] as $educationData)
		    {
		       if($educationData['Level']=='UG')
		       {
			   $formattedUserDetails['Graduation Status'] = ($educationData['OngoingCompletedFlag']==1)?"Pursuing":"Completed";
			   $formattedUserDetails['Graduation Course'] = $educationData['Name'];
			   $formattedUserDetails['Graduation Marks'] =  ($educationData['OngoingCompletedFlag']==1)?"":$educationData['Marks'];
			   list($formattedUserDetails['Graduation Month'],$formattedUserDetails['Graduation Year']) = split(" ",$educationData['Course_CompletionDate']);
		       }
		       else if ($educationData['Level']=='12')
		       {
			   $formattedUserDetails['Std XII Stream'] = $educationData['Name'];
			   $formattedUserDetails['Std XII Marks'] = $educationData['Marks'];
			   $XIICompletionDate = split(" ",$educationData['Course_CompletionDate']);
			   $formattedUserDetails['Std XII Year'] = $XIICompletionDate[1];
		       }
			else if ($educationData['Level']=='Competitive exam')
			{
				if($formattedUserDetails['Exams Taken']) {
					$formattedUserDetails['Exams Taken'] .= ', ';
				}
				
			    if($prefDetails['ExtraFlag'] == 'studyabroad') {
					$formattedUserDetails['Exams Taken'] .= $educationData['Name'] . (($educationData['Marks'] != 0) ? "(" . $educationData['Marks'] . ")" : "");
				}
				else {
					$examObj = \registration\builders\RegistrationBuilder::getCompetitiveExam($educationData['Name'],$educationData);
					$formattedUserDetails['Exams Taken'] .= $examObj->displayExam();
				}
			}
	
		   }
		   $locationPrefData=$userDetails['PrefData'][0]['LocationPref'];
		       $formattedUserDetails['Preferred Locations']= '';
		       $formattedUserDetails['Desired Countries'] = '';
		    $specialisationPrefData = $userDetails['PrefData'][0]['SpecializationPref'][0];
		   //corrected bad code :)
		   $count = count($locationPrefData);
		   for($i=0;$i<$count;$i++)
		   {
		       $key='Location Preference '.($i+1);
		       //added check for local course
		      if((25<=$specialisationPrefData['SpecializationId'] && $specialisationPrefData['SpecializationId']<=34)
		      || ($specialisationPrefData['CourseReach']=='local') ||
		      ($specialisationPrefData['CourseName']== 'Distance BCA') || ($specialisationPrefData['CourseName']=='Distance MCA')
		      || ($userDetails['PrefData'][0]['ExtraFlag']=='testprep')){
		       $formattedUserDetails[$key] = $locationPrefData[$i]['CityName'];
		       if(!empty($locationPrefData[$i]['LocalityName'])){
		       $formattedUserDetails[$key] = $formattedUserDetails[$key]."-".$locationPrefData[$i]['LocalityName'];
		       }
		       }
		       if(!empty($locationPrefData[$i]['CityName'])){
			   $cityName = $locationPrefData[$i]['CityName'];
			   if($i==0)
			   $formattedUserDetails['Preferred Locations'] = $cityName;
			   if($i>=1 && ($cityName!=$locationPrefData[$i-1]['CityName']))
		       $formattedUserDetails['Preferred Locations'].= (($i>0)?",":"").$cityName;
		       }
			   if($userDetails['PrefData'][0]['ExtraFlag']=='studyabroad')
		       $formattedUserDetails['Desired Countries'] .=  (($i>0)?",":"").$locationPrefData[$i]['CountryName'];
		    }
		    $formattedUserDetails['User Comments'] = $userDetails['PrefData'][0]['UserDetail'];

	//chnage for abroad, now tUserPref date will be sent as creation date
		    $formattedUserDetails['Date of Registration'] = $userDetails['CreationDate'];
		    $formattedUserDetails['Creation Date'] = $userDetails['CreationDate'];

		    if ($userDetails['PrefData'][0]['ExtraFlag'] == 'studyabroad') {
		    	$formattedUserDetails['Date of Registration'] = date("jS M Y",strtotime($userDetails['PrefData'][0]['SubmitDate']));
			    $formattedUserDetails['Creation Date'] = date("jS M Y",strtotime($userDetails['PrefData'][0]['SubmitDate']));	
		    }

		    /*$formattedUserDetails['Date of Registration'] = $userDetails['CreationDate'];
		    $formattedUserDetails['Creation Date'] = $userDetails['CreationDate'];*/
		    
		    $formattedUserDetails['Is in NDNC list'] = $userDetails['isNDNC'];
		    $formattedUserDetails['Email'] = $userDetails['email'];
		    $formattedUserDetails['Mobile'] = $userDetails['mobile'];
		    $formattedUserDetails['Extra Flag'] = $userDetails['PrefData'][0]['ExtraFlag'];
		    $formattedUserDetails['Response Date'] = ($responseUsers[$userDetails['userid']]['submitDate']) ? date('d M Y',strtotime($responseUsers[$userDetails['userid']]['submitDate'])) : "";
		    $matchedForCourseId = ($responseUsers[$userDetails['userid']]['matchedFor']) ? ($responseUsers[$userDetails['userid']]['matchedFor']) : '';

		    if(!empty($matchedForCourseId)){
				
				$matchedCourses[$matchedForCourseId]['CourseName'] = $displayArray['matchedCourses'][$matchedForCourseId];
				$matchedCourses[$matchedForCourseId]['InstituteName'] = $displayArray['matchedCoursesInstitute'][$matchedForCourseId];
			    
			    // error_log("###matchedCourses ".print_r($matchedCourses,true));
		    	foreach($matchedCourses as $matchedCourseId=>$matchedCourse) {
					$displayMatchedCourses[] = $matchedCourse['CourseName'].', '.$matchedCourse['InstituteName'];
				}
		    	$formattedUserDetails['Matched Response For'] = implode('; ',array_values($displayMatchedCourses));
		    }
			
			if($displayArray['MRLocation']) {
				$selectedMRLocations = explode(",", $displayArray['MRLocation']);
				for($i=0;$i<count($selectedMRLocations);$i++) {
					$selectedMRLocations[$i] = trim($selectedMRLocations[$i]);
				}
				$userResponseLocations = $responseLocations[$userDetails['userid']];
				$matchingLocations = array_intersect($selectedMRLocations, $userResponseLocations);
				$formattedUserDetails['Preferred Study Locations'] = implode(", ", $matchingLocations);
			}
			else {
				$formattedUserDetails['Preferred Study Locations'] = "NA";
			}
			
			
		    $LocalCourseArray[] = $formattedUserDetails;
	       }
	       return $LocalCourseArray;
	   }
		       
	function UserIdsEmailMap()
	{
	    
	    $UserIdsArrayMap = array(
				'643152'  => array( 'email' => 'admission@asbm.ac.in',
						    'name' => 'Asian School of Business Management'
						   ),
				'2078103'  => array( 'email' => 'careeratosbm@gmail.com',
						    'name' => 'Odisha School of Business Management'
						   ),
				'1835112'  => array( 'email' => 'itpstudies@gmail.com',
						    'name' => 'itps'
						   
						   )
	    );
	    
	    return $UserIdsArrayMap;   
	}

	function formatEnterpriseTrackingData($searchagentid, $searchAgentStatus, $startDate, $endDate){


		if($searchAgentStatus == 'activated'){
            $trackingParams['page_tab']         = 'ManageGenieSummary_Active';                
        }elseif ($searchAgentStatus == 'deleted') {
            $trackingParams['page_tab']         = 'ManageGenieSummary_Deleted';
        }elseif ($searchAgentStatus == 'deactive') {
            $trackingParams['page_tab']         = 'ManageGenieSummary_Deactivated';
        }    
        
        $trackingParams['cta']              = 'Download';    
        $trackingParams['product']  		= 'GenieManager';
        $trackingParams['entity_id']  		=  $searchagentid;

      
        $tracking_data['start_date'] 		= $startDate;
        $tracking_data['end_date'] 			= $endDate;


        $trackingParams['search_criteria'] =json_encode($tracking_data);
        
        return $trackingParams;
	}
}
