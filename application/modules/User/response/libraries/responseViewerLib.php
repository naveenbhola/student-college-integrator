<?php


class responseViewerLib {
	private $responseViewerModel;
	private $examResponseAccessLib;
	private $userLib;
	function __construct() {
		$this->CI = & get_instance();	
		$this->responseViewerModel   = $this->CI->load->model('response/responseviewermodel');
		$this->examResponseAccessLib = $this->CI->load->library('enterprise/examResponseAccessLib');
		$this->userLib               = $this->CI->load->library('user/UserLib');
	}

	function getExamResponsesForClient($status,$clientId){
		if($status == 'live'){
			$statusType = "active";
		}else if($status == 'expired'){
			$statusType = "inactive";
		}
		$data  = $this->examResponseAccessLib->getSubscriptionData($statusType,$clientId);
		return $data;		
	}

	function getExamResponseDataForCSV($subscriptionId,$allocationIds){
		$this->CI->load->helper('shikshaUtility');
		// _p($subscriptionId);
		// _p($allocationIds);die;
		$responses = $this->getAllocatedResponsesForSubscription($subscriptionId,'','','none',$allocationIds);
		$responses = $responses['responses'];
		if(count($responses) >0){
			foreach ($responses as $key => $response) {
				$userIds[]      = $response['userId'];
			}
			$userData         = $this->userLib->getUserDataFromSolr($userIds);
			unset($userIds);
			
			foreach ($responses as $key => $value) {
				$formattedArray[$key]['name']         = $userData[$value['userId']]['Full Name'];
				$formattedArray[$key]['firstname']    = $userData[$value['userId']]['First Name'];
				$formattedArray[$key]['lastname']     = $userData[$value['userId']]['Last Name'];
				$formattedArray[$key]['exam']         = $value['examName'];
				$formattedArray[$key]['groupName']    = $value['groupName'];				
				$formattedArray[$key]['currentCity']  = (!empty($userData[$value['userId']]['Current City']))?$userData[$value['userId']]['Current City'] : 'N.A.';
				$formattedArray[$key]['localityName'] = (!empty($userData[$value['userId']]['Current Locality']))?$userData[$value['userId']]['Current Locality'] : 'N.A.';
				$responseDate 						  = new DateTime($value['submit_date']);
				$formattedArray[$key]['submit_date']  = $responseDate->format('d-M-Y H:i:s');
				$formattedArray[$key]['action']       = $value['action'];
				$formattedArray[$key]['email']        = (!empty($userData[$value['userId']]['Email']))?$userData[$value['userId']]['Email'] : 'N.A.';
				$formattedArray[$key]['IsdCode']      = (!empty($userData[$value['userId']]['ISD Code']))?$userData[$value['userId']]['ISD Code'] : 'N.A.';
				$formattedArray[$key]['mobile']       = (!empty($userData[$value['userId']]['Mobile']))?$userData[$value['userId']]['Mobile'] : 'N.A.';				
				$formattedArray[$key]['isNDNC']       = ($userData[$value['userId']]['NDNC Status'] == 'Can call')?'NO':'YES';
				$formattedArray[$key]['exams_taken']  = (!empty($userData[$value['userId']]['Exams Taken']))?$userData[$value['userId']]['Exams Taken'] : 'N.A.';
				$formattedArray[$key]['experience']   = (!empty($userData[$value['userId']]['Work Experience']))?$userData[$value['userId']]['Work Experience'] : 'N.A.';
			}
			unset($responses);			

			return $formattedArray;
		}else{
			return array();
		}
	}

	function getAllocatedResponsesForSubscription($subscriptionId,$start ='', $count ='', $timeInterval="none", $responseIds = array()){

		// get exam response data
		$responses = $this->responseViewerModel->getAllocatedResponsesForSubscriptionByCriteria($subscriptionId, $start, $count, $timeInterval, $responseIds);
		
		if(!empty($responses['responses']) && count($responses['responses']) > 0){
			
			// get user data for these response user
			$examGroupIds = array();
			foreach ($responses['responses'] as $key => $examResponse) {
				$examGroupIds[$examResponse['entityValue']] = $examResponse['entityValue'];
			}
			
			$groupExamDetails = $this->getGroupExamDetails($examGroupIds);
			
			foreach ($responses['responses'] as $key => $examResponse) {
				$responses['responses'][$key]['groupName'] = $groupExamDetails[$examResponse['entityValue']]['groupName'];
				$responses['responses'][$key]['examName'] = $groupExamDetails[$examResponse['entityValue']]['name'];
				unset($responses['responses'][$key]['entityValue']);
			}
			unset($groupExamDetails);

			return $responses;
		}else{
			return array();
		}
	}

	function getAllocatedResponseDetailsForSubscription($subscriptionId, $clientId, $start='', $count='', $timeInterval='none',$responseIds = array()){
		if(empty($subscriptionId) || $subscriptionId <=0){
			return false;
		}

		$finalData = array();
		$responses = $this->getAllocatedResponsesForSubscription($subscriptionId,$start, $count, $timeInterval, $responseIds = array());
		$finalData['totalResponses'] = $responses['totalResponses'];
		$finalData['responses'] = $responses['responses'];

		if(count($finalData['responses']) > 0){

			// get user data
			$userIds = array();
			foreach ($finalData['responses'] as $key => $examResponse) {
				$userIds[$examResponse['userId']] = $examResponse['userId'];
			}

			$finalData['userData']         = $this->userLib->getUserDataFromSolr($userIds);
			foreach ($finalData['userData'] as $key => $userDetail) {
				unset($finalData['userData'][$key]['userid']);
				unset($finalData['userData'][$key]['First Name']);
				unset($finalData['userData'][$key]['Last Name']);
				$finalData['userData'][$key]['Work Experience'] = (!empty($userDetail['Work Experience']))?$userDetail['Work Experience'] : 'N.A.';
			}

			$finalData['usersContactHistory'] = $this->getClientUserCommHistory($clientId, $userIds);
		}
		
		return $finalData;
	}

	function getClientUserCommHistory($clientId, $userIds){
		if(empty($clientId) || $clientId <0){
			return false;
		}

		if(!is_array($userIds) || count($clientId) <0){
			return false;
		}
		// get communication history
		$clientUsersContactHistory = array();
		$result = $this->responseViewerModel->getClientUserCommHistory($clientId, $userIds);
		foreach ($result as $key => $contactHistory) {
			$clientUsersContactHistory[$contactHistory['recipientId']] = $contactHistory['contactDate'];
		}
		return $clientUsersContactHistory;
	}
	
	function generateExamResponseCSV($responses,$subscriptionId,$showHeader = true){
		$this->CI->load->config('response/responseConfig');
		$csvFields = $this->CI->config->item('examCsvFields');	

		// output headers so that the file is downloaded rather than displayed
		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename="examResponses.csv"');
		 
		// do not cache the file
		header('Pragma: no-cache');
		header('Expires: 0');

		$file = fopen('php://output', 'w');
		
		fputcsv($file, $csvFields);

		// output each row of the data
		foreach ($responses as $row){
		    fputcsv($file, $row);
		}
		
		fclose($file);
		exit();
	}

	function getGroupExamDetails($examGroupIds){
		$formattedData = array();
		$data = $this->responseViewerModel->getGroupExamDetails($examGroupIds);		
		foreach ($data as $key => $value) {
			$formattedData[$value['groupId']] = $value;
		}
		unset($data);
		return $formattedData;
	}

	function getSubscriptionDetails($subscriptionId){
		if(empty($subscriptionId) || $subscriptionId <=0){
			return false;
		}

		$ERAccessModel = $this->CI->load->model('enterprise/examresponseaccessmodel');
		$result = $ERAccessModel->getSubscriptionData('', '', $subscriptionId);		
		if(empty($result)){
			return false;
		}else{
			$subscriptionDetails = $result[0];
			$examId = $subscriptionDetails['examId'];
			$groupIds = explode(",", $subscriptionDetails['groupIds']);
			$examDetails = $this->examResponseAccessLib->getExamName(array($examId));
			$groupsDetails = $this->examResponseAccessLib->getGroupName($groupIds);
			$subscriptionDetails['examName'] = $examDetails[$examId];
			$subscriptionDetails['examGroupNames'] = implode(", ", array_values($groupsDetails));
			return $subscriptionDetails;
		}
	}

	function getResponsesForListingId($clientId,$listingId,$listingType,$searchCriteria,$timeInterval,$start,$count,$locationId,$startDate, $endDate, $tabStatus,$responseIds){		

		if(empty($tabStatus)){
			$tabStatus = 'live';
		}
		$responses            = array();
		$userIds              = array();
		$listingIds           = array();
		$courses              = array();
		
		$courseList           = array();
		// _p($listingType);		
		// _p($searchCriteria);		die;
		if($listingType == 'institute'){
			$instituteDetailLib = $this->CI->load->library('nationalInstitute/instituteDetailLib');
			//get course list based on tabStatus
			$allCoursesResponse = $instituteDetailLib->getAllCoursesForMultipleInstitutes(array($listingId),'direct');
			$courseList         = $allCoursesResponse[$listingId]['courseIds'];	
		}else if($listingType == 'university') {                
			$abroadcoursefindermodel = $this->CI->load->model('listing/abroadcoursefindermodel');
			$courseIdsArray = $abroadcoursefindermodel->getCoursesOfferedByUniversity($listingId,'list',false,$tabStatus);			
			$courseList = $courseIdsArray['course_ids'];			
			$listingId  = "";
			$locationId = "";
		}else if($listingType == 'course') {
			$courseList = array($listingId);
        }

		if($searchCriteria == 'courseOnly') {
			$courseModel = $this->CI->load->model('listing/coursemodel');
			$isAbroadCourse = $courseModel->isStudyAboradListing($courseList, 'course', $tabStatus);
			
			if($isAbroadCourse) {
				$locationId = '';
			}
			$listingId = "";
		}
		
		/* Fetching responses based on clientId, listingId and search Criteria */
		$responseProcessorLib = $this->CI->load->library('response/responseProcessorLib',true);
		$responsesData        = $responseProcessorLib->getResponsesForListingId($clientId,$listingId,$listingType,$searchCriteria,$timeInterval,$start,$count,$locationId,$startDate, $endDate,$courseList,$responseIds);    
		
		
        /* preparing the bucket of userid and listingIds to get user and listing info */
       	$userIds    = $responsesData['userIdsList'];
       	$listingIds = $responsesData['listingsList'];        

        /* preparing course wise title bucket */
        if(count($listingIds) > 0){
        	$listingsInfo = $this->responseViewerModel->getListingData(array_keys($listingIds),'course',array('live','deleted'));
       
	        foreach ($listingsInfo as $key => $value) {

	        	if($courses[$value['listing_type_id']] && $value['status'] =='deleted'){
	        		continue;
	        	}
	        	

	        	$courses[$value['listing_type_id']] = $value['listing_title'];
	        }
	        unset($listingsInfo);
        }
        
        foreach ($responsesData['responses'] as $key => $value) {	
			$date                                              = new DateTime($value['submit_date']);
			$responsesData['responses'][$key]['submit_date']   = $date->format('d-M-Y H:i:s');
			$responsesData['responses'][$key]['listing_title'] = $courses[$value['listing_type_id']];
        }
        
        $responses['responses']      = $responsesData['responses'];
        $responses['totalResponses'] = $responsesData['totalResponses'];
        
        unset($responsesData);

		$implodedUserIds = implode(",", $userIds);

        if(!empty($userIds)) {
            $appID = 1;
            $this->CI->load->library(array('LDB_Client','MiscClient'));
			$ldbObj  = new LDB_Client();
			$miscObj = new MiscClient();

			$userLib = $this->CI->load->library('user/UserLib');
			
            $responses['userIdDetails'] = $userLib->getUserDataFromSolr($userIds,true);
            $responses['contactHistory'] = json_decode($miscObj->getCommunicationTracking($appID, $implodedUserIds, $clientId, true, ''), true);
        }
    
        $responses['userIds']   = $implodedUserIds;
        $responses['courseIds'] = implode(",", array_keys($listingIds));

        unset($userIds);
        unset($listingIds);
        
        return $responses;
	}

	public function getInstituteResponseCountForClientId($tabStatus,$clientId){
		//fetch all the live courses
        $courseModel = $this->CI->load->model('nationalCourse/nationalcoursemodel');
        $listingData = $courseModel->getListingsByClientId($tabStatus,$clientId);

        if(empty($listingData)){
        	return 'no_listing_found';
        }
        // fetching responses
        $responseProcessorLib = $this->CI->load->library('response/responseProcessorLib',true);
        $listingResponseData  = $responseProcessorLib->getListingResponseCountByClientId($clientId,$listingData['courseIds']);     

        $listingsResponse     = $listingResponseData['data'];

        if(empty($listingsResponse)){
        	return array();
        }

        // create location map for current locations
        $locationMap = $courseModel->getListingLocationDetails($listingResponseData['listingLocationIds']);

        if(empty($locationMap)){
        	//studyabroad responses
        	$instituteIds       = array_keys($listingResponseData['data']);
        	$studyAbroadMapping = $courseModel->getInstituteToUniversityMapping($tabStatus,$instituteIds);
        }
        
        ////////////////////////////////////////
        //Get response export pref data start //
        ////////////////////////////////////////

        $responseModel = $this->CI->load->model('response/responsemodel');
        $listingTypeClause = "";
		if(count($studyAbroadMapping['universityLocationIds']) && count($listingResponseData['instituteLocationIds'])){
			$orCond = "(listingType = 'university' AND listingLocationId IN (1,".implode(',',array_keys($studyAbroadMapping['universityLocationIds'])).")) OR (listingType in ('institute','university_national') AND listingLocationId IN (1,".implode(',',$listingResponseData['listingLocationIds'])."))";
			$listingTypeClause = '';
			$locIds            = '';
		}else if(count($studyAbroadMapping['universityLocationIds'])) {
			$listingTypeClause = array('university');
			$locIds            = array_keys($studyAbroadMapping['universityLocationIds']);			
		}else if(count($listingResponseData['listingLocationIds'])) {
			$listingTypeClause = array('institute','university_national');
			$locIds            = $listingResponseData['listingLocationIds'];			
		}else {
			$listingTypeClause = array('institute','university_national');
			$locIds            = array(1);
		}
        $emailExportMap = $responseModel->getResponseExportPref($listingTypeClause, $locIds,$orCond);     

        //////////////////////////////////////
        //Get response export pref data end //
        //////////////////////////////////////
     	
	     ////////////////////////////////////
     	//preparing listing data start    //
	     ////////////////////////////////////
        $finalData = array();
        $universityResponseCount = array();
        foreach ($listingsResponse as $instituteId => $rows) {
        	foreach ($rows as $locationId => $value) {
        		if($locationMap[$locationId]){
        			$temp                   = array();
	                $temp['locationId']     = $locationId;
	                $temp['totalResponses'] = $value['totalResponses'];
	                $temp['institute_id']   = $instituteId;
	                $temp['listing_title']  = $listingData['listingTitles'][$instituteId];
	                $temp['city_name']      = $locationMap[$locationId]['city'];
	                $temp['localityName']   = $locationMap[$locationId]['locality'];
	                $temp['emailExport']    = $emailExportMap[$instituteId.'_'.$locationId];
	                $finalData[]            = $temp;        			
        		}
        		
        		if(count($studyAbroadMapping['instituteToUniversityMapping'])) {
					if(isset($studyAbroadMapping['instituteToUniversityMapping'][$instituteId])) {
						$temp                   = array();
						$temp['listing_title']  = $studyAbroadMapping['instituteToUniversityMapping'][$instituteId]['name'];
						$temp['university_id']  = $studyAbroadMapping['instituteToUniversityMapping'][$instituteId]['id'];
						$temp['locationId']     = $studyAbroadMapping['instituteToUniversityMapping'][$instituteId]['location'];
						$temp['city_name']      = $studyAbroadMapping['instituteToUniversityMapping'][$instituteId]['city'];
						$temp['emailExport']    = $emailExportMap[$studyAbroadMapping['instituteToUniversityMapping'][$instituteId]['id'].'_'.$studyAbroadMapping['instituteToUniversityMapping'][$instituteId]['location']];;
						$temp['totalResponses'] = $value['totalResponses'];
						$finalData[]            = $temp;
						$universityResponseCount[$temp['university_id']] += $value['totalResponses'];
					}
				}
        	}
        }     

        if(count($studyAbroadMapping['instituteToUniversityMapping'])) {
        	foreach($finalData as $index => $row) {
				if(!empty($row['university_id'])) {
					if(!empty($universityResponseCount[$row['university_id']])) {
						$finalData[$index]['totalResponses'] = $universityResponseCount[$row['university_id']];
						unset($universityResponseCount[$row['university_id']]);
					}
					else {
						unset($finalData[$index]);
					}
				}
			}
        }

        ///////////////////////////////
        //preparing listing data end //
        ///////////////////////////////

        return $finalData;
	}

}
