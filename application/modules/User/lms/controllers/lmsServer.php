<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: manishz $:  Author of last commit
$Date: 2010-03-29 09:44:02 $:  Date of last commit
$Id: lmsServer.php,v 1.48 2010-03-29 09:44:02 manishz Exp $:

*/
class lmsServer extends MX_Controller
{
        private $cacheLibObj;
	function index()
	{
		$this->dbLibObj = DbLibCommon::getInstance('LMS');		
        error_log_shiksha("IN LMS SERVER ");
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('userconfig');
                $this->load->library('cacheLib');
                $this->cacheLibObj = new cacheLib();
                $this->load->helper('url');
                $this->load->helper('date');

		$config['functions']['adduser'] = array('function' => 'lmsServer.adduser');
		$config['functions']['insertLead'] = array('function' => 'lmsServer.insertLead');
		$config['functions']['tempLmsRequest'] = array('function' => 'lmsServer.tempLmsRequest');
		$config['functions']['generateBulkLead'] = array('function' => 'lmsServer.generateBulkLead');
		$config['functions']['getLeadsByClient'] = array('function' => 'lmsServer.getLeadsByClient');
		$config['functions']['getLeadsByListing'] = array('function' => 'lmsServer.getLeadsByListing');
		$config['functions']['getLeadsByListingCSV'] = array('function' => 'lmsServer.getLeadsByListingCSV');
		$config['functions']['registerStudent'] = array('function' => 'lmsServer.registerStudent');
		$config['functions']['registerClient'] = array('function' => 'lmsServer.registerClient');
		$config['functions']['getRegisteredData'] = array('function' => 'lmsServer.getRegisteredData');
		$config['functions']['sgetInstituteResponseCountForClientId'] = array('function' => 'lmsServer.sgetInstituteResponseCountForClientId');
		$config['functions']['sgetResponsesForListingId'] = array('function' => 'lmsServer.sgetResponsesForListingId');
                $config['functions']['sgetAllResponsesForFreeCourses'] = array('function' => 'lmsServer.sgetAllResponsesForFreeCourses');
                $config['functions']['sgetAllLeadsForOperator'] = array('function' => 'lmsServer.sgetAllLeadsForOperator');
                $config['functions']['getResponseListbyUserId'] = array('function' => 'lmsServer.getResponseListbyUserId');
		$config['functions']['insertResponseForFreeCourse'] = array('function' => 'lmsServer.insertResponseForFreeCourse');
		
                $this->makeApcCountryMap();
                $this->makeApcCategoryNameMap();
		$args = func_get_args();		
		$method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}

    function makeApcCountryMap(){
        $dbConfig = array( 'hostname'=>'localhost');
        $appId = 12;
        if($this->cacheLibObj->get("country_flag") != "1"){
            $dbHandle = $this->_loadDatabaseHandle();

            $queryCmd = 'select * from countryTable';
            log_message('debug', 'query cmd is ' . $queryCmd);
            $query = $dbHandle->query($queryCmd);
            $counter = 0;
            $msgArray = array();
            foreach ($query->result() as $row){
                $key = "country_".$row->countryId;
                $val = $row->name;
                $this->cacheLibObj->store($key,$val);
            }
            $this->cacheLibObj->store("country_flag","1");
        }
    }

    function makeApcCategoryNameMap(){
        $boardId = 1;
        //connect DB
        $appId = 1;
        if($this->cacheLibObj->get("catname_flag") != "1"){
            $dbHandle = $this->_loadDatabaseHandle();
            $boardIdArray = array();
            $boardIdString='';
            if($dbHandle == ''){
                log_message('error','getRecentEvent can not create db handle');
            }

            $queryCmd = ' SELECT t1.boardId AS lev1,t1.name as name1, t2.boardId as lev2, t2.name as name2,
                t3.boardId as lev3, t3.name as name3, t4.boardId as lev4, t4.name as name4 FROM categoryBoardTable AS t1 '.
                    'LEFT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId '.
                    'LEFT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId '.
                    'LEFT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t1.boardId = ?';

            error_log_shiksha($queryCmd);
            $query = $dbHandle->query($queryCmd, array($boardId));
            $catArray = array();
            foreach ($query->result() as $row){
                $key = "catname_".$row->lev3;
                $val =$row->name2." / ".$row->name3 ;
                $this->cacheLibObj->store($key,$val);
            }
            foreach ($query->result() as $row){
                $key = "catname_".$row->lev2;
                $val = $row->name2 ;
                $this->cacheLibObj->store($key,$val);
            }

            $this->cacheLibObj->store("catname_flag","1");
        }
    }

    function getMailIds($dbHandle, $action , $type, $typeId){
		
		$dbHandle = $this->_loadDatabaseHandle();
		
        $this->load->model('ListingModel');
        $contact_email = '';
        switch($type){
            case 'institute':
            case 'course':
                $queryCmd = 'select contact_details_id from listings_main where status="live" and listing_type_id=? and listing_type=?';
                $query = $dbHandle->query($queryCmd, array($typeId, $type));
                foreach ($query->result() as $row){
                    $contactInfo = $this->ListingModel->getContactDetails($dbHandle,$row->contact_details_id);
                    $contact_email = $contactInfo['contact_email'];
                }
                break;
            case 'scholarship':
                $queryCmd = "select contact_email from listings_main where listing_type = ?  and listing_type_id=? and status='live'";
                $query = $dbHandle->query($queryCmd, array($type, $typeId));
                foreach ($query->result() as $row){
                    $contact_email = $row->contact_email;
                }
                break;
            case 'notification':
                $queryCmd = "select contact_email from listings_main where listing_type = ? and listing_type_id=? and status='live'";
                $query = $dbHandle->query($queryCmd, array($type, $typeId));
                foreach ($query->result() as $row){
                    $contact_email = $row->contact_email;
                }
                if(strlen($contact_email) <=0 ){
                    $queryCmd = "select contact_details_id from institute_examinations_mapping_table, listings_main where  listing_type = 'institute' and listing_type_id = institute_examinations_mapping_table.institute_id AND admission_notification_id=? and listings_main.status='live'";
                    error_log_shiksha("MAILSEND::".$queryCmd);
                    $queryTemp = $dbHandle->query($queryCmd, array($admission_notification_id));
                    foreach ($queryTemp->result() as $rowTemp) {
                        $contactInfo = $this->ListingModel->getContactDetails($dbHandle,$rowTemp->contact_details_id);
                        $contact_email = strlen($contactInfo['contact_email']) > 0 ?$contactInfo['contact_email']:$contact_email;
                    }
                }
                break;
        }
        return $contact_email;
    }

function getRegisteredData($request)
    {
        error_log_shiksha("IN LMS SERVER ADDUSER ");
        $parameters = $request->output_parameters();
        $regId = $parameters['0'];
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "select * from fairStudentTable where regId = ?";
        $query = $dbHandle->query($queryCmd, array($regId));
        $resultSet = $query->result_array();
        $response = array(base64_encode(serialize($resultSet)),'string');
        return $this->xmlrpc->send_response($response);
    }

	function registerStudent($request)
    {
        error_log_shiksha("IN LMS SERVER ADDUSER ");
        $parameters = $request->output_parameters();
        $formVal = unserialize(base64_decode($parameters['0']));
        $dbHandle = $this->_loadDatabaseHandle('write');
        $date = date($formVal['date']);
        error_log($date);

        $date = standard_date('DATE_ATOM',$formVal['date']);
        error_log($date);
        $data =array();
        $data = array(
                'name'=>$formVal['name'],
                'email'=>$formVal['email'],
                'cell'=>$formVal['mobile'],
                'educationLevel'=>$formVal['educationLevel'],
                'educationInterest'=>$formVal['categories'],
                'city'=>$formVal['city'],
                'date'=>$date
                );
        $queryCmd = $dbHandle->insert_string('fairStudentTable',$data);
        error_log($queryCmd);
        $query = $dbHandle->query($queryCmd);
        $recent_id = $dbHandle->insert_id();
        $response = array($recent_id,'string');
        return $this->xmlrpc->send_response($response);
    }


	function registerClient($request)
    {
        error_log_shiksha("IN LMS SERVER ADDUSER ");
        $parameters = $request->output_parameters();
        $formVal = unserialize(base64_decode($parameters['0']));
        $dbHandle = $this->_loadDatabaseHandle('write');
        $data =array();
        $data = array(
                'name'=>$formVal['name'],
                'email'=>$formVal['email'],
                'cell'=>$formVal['mobile'],
                'city'=>$formVal['city'],
                'instituteName'=>$formVal['instituteName'],
                'informationRequired'=>$formVal['informationRequired']
                );
        $queryCmd = $dbHandle->insert_string('fairClientTable',$data);
        $query = $dbHandle->query($queryCmd);
        $recent_id = $dbHandle->insert_id();
        $response = array($recent_id,'string');
        return $this->xmlrpc->send_response($response);
    }

	function adduser($request)
    {
        error_log_shiksha("IN LMS SERVER ADDUSER ");
        $parameters = $request->output_parameters();
        $data = $parameters['0'];
        error_log_shiksha(print_r($data,true));

        $dbHandle = $this->_loadDatabaseHandle('write');
        $date = date("y.m.d");
        $logintime = date("y.m.d:h:m:s");
        error_log_shiksha($logintime.'LOGINTIME');
        $sql = "insert into tlmsProfile values ('', ?, ?, ?, ?, ?, ?, ?, ?)";
        error_log_shiksha("LMS QUERY = ".$sql);


        $query = $dbHandle->query($sql, array($data['userID'], $data['displayname'], $data['mobile'], $data['email'], $data['educationlevel'], $data['city'], $data['experience'], $date));
        if($dbHandle->affected_rows() == 1)
        {
            $response = array(
                    array("status"=> array($userid)),
                    'struct');
            error_log_shiksha($response.'ReSPONSE');
            return $this->xmlrpc->send_response($response);

        }
        else
        {
            $response = array( array("status"=> array('0')), 'struct');
            error_log_shiksha($response['status']);
            return $this->xmlrpc->send_response($response);
        }

    }

    function mailToBeSent($dbHandle, $action , $type, $typeId){
		$dbHandle = $this->_loadDatabaseHandle();
        $returnArr = array();
        $mailToBeSentFlag = 0;
        switch($type){
            case 'course':
            case 'institute':
            case 'scholarship':
            case 'notification':
                $queryCmd = "select pack_type, listing_title from listings_main where listing_type = ?  and listing_type_id=? and status='live' limit 1";
                $query = $dbHandle->query($queryCmd, array($type, $typeId));
                foreach ($query->result() as $row){
                    if($row->pack_type > 0 && $row->pack_type != 7 ){
                        $mailToBeSentFlag = 1;
                    }
                    $returnArr['mailToBeSentFlag'] = $mailToBeSentFlag;
                    $returnArr['listing_title'] = $row->listing_title;
                }
                break;
        }
        return $returnArr;
    }

    function mailSubject($data,$action){
        switch($action){
            case 'requestinfo':
                    $subject ="Shiksha.com member's query for ".$data['listingTitle'];
                    break;
            case 'joinedgroup':
                    $subject ="Shiksha.com member interested in  ".$data['listingTitle'];
                    break;
            case 'savedlisting':
                    $subject ="Shiksha.com member interested in ".$data['listingTitle'];
                    break;
            default:
                    $subject ="Shiksha.com member interested in ".$data['listingTitle'];
                    break;
        }
        return $subject;
    }

    function tempLmsRequest($request)
    {
        
        $parameters = $request->output_parameters();
        $formVal = $parameters['1'];
        //connect DB
        //$dbConfig_test = array( 'hostname'=>'localhost');
        $appId = $parameters['0']['0'];
        //error_log('Hellos2 ' .$queryCmd);
        $dbHandle = $this->_loadDatabaseHandle('write');
        $formVal['submit_date'] = (!empty($formVal['submit_date'])) ? $formVal['submit_date'] : date('Y-m-d H:i:s');
        //Amit Singhal : Logic to avoid more than 5 course responses on an institute.
        if( ($formVal['action'] ==  "Viewed_Listing" || $formVal['action'] == "mobile_viewedListing") && $formVal['listing_type'] == 'course'){
                if($this->checkForSameCouseResponse($formVal,$dbHandle) == false){
                        $response = array(array('QueryStatus'=>'N.A.','leadId'=>1),'struct');
                        return $this->xmlrpc->send_response($response); 
                }
        }
        $listing_subscription_type = 'paid';
        if((!empty($formVal['listing_subscription_type'])) && (isset($formVal['listing_subscription_type']))) {
                $listing_subscription_type = $formVal['listing_subscription_type'];
        }
        
        /*
         *Check if userId is 0 and email id exists fetch the userid from tuser
         */
        if(empty($formVal['userId'])&& $formVal['contact_email']){
            $formVal['userId'] = $this->_getUserIdByEmail($formVal['contact_email'], 'write');
        }
        
        if(!isset($formVal['userId']) && $formVal['userId'] == '' ){
            array($response);
            return $this->xmlrpc->send_response($response);
        }   

        //Query Command for Insert in the Listing Main Table
        $data =array();
        $data = array(
                'listing_type_id'=>$formVal['listing_type_id'],
                'listing_type'=>$formVal['listing_type'],
                'userId'=>$formVal['userId'],
                'displayName'=>$formVal['displayName'],
                'email'=>$formVal['contact_email'],
                'contact_cell'=>$formVal['contact_cell'],
                'CounsellorId'=>isset($formVal['CounsellorId']) ? $formVal['CounsellorId'] : '',
                'listing_subscription_type'=>$listing_subscription_type,
                'submit_date'=>$formVal['submit_date']
                );
        $queryCmd = $dbHandle->insert_string('tempLmsRequest',$data);
        $queryCmd .= " on duplicate key update count=count+1, submit_date=?";
        $query = $dbHandle->query($queryCmd,array($formVal['submit_date']));
        $recent_id = $dbHandle->insert_id();
        
        $dbHandle->where('queue_id', $formVal['queue_id']);
        $dbHandle->where('response_from', 'abroad');
        $dbHandle->update('response_elastic_map', array('temp_lms_request_id' => $recent_id));

        $response = array(array
                ('QueryStatus'=>$query,
                 'leadId'=>$recent_id
                ),
                'struct'
                );
        return $this->xmlrpc->send_response($response);
    }
    
    private function _getUserIdByEmail($email, $dbhandle_type= 'read'){
	$this->load->model('user/usermodel');
	$userId = $this->usermodel->getUserIdByEmail($email, '', $dbhandle_type);
	return $userId;
    }

    private function _getContactDetails($listing_type, $listing_type_id, $institute_location_id){		       
        $returnResult = array();
        $locations = array();
        
        /*if($listing_type == 'institute'){
            $instituteRepository = $listingBuilder->getInstituteRepository();
            $institute = $instituteRepository->find($listing_type_id);
            $locations = $institute->getLocations(); 
        }*/
        
        if($listing_type == 'course'){
			
    		$this->load->model('listing/coursemodel');
    		$coursemodel = new coursemodel;
    		$isAbroadCourse = $coursemodel->isStudyAboradListing($listing_type_id, 'course');
    		
            if($isAbroadCourse) {
				$this->load->builder('ListingBuilder','listing');
				$listingBuilder = new ListingBuilder;
    			$abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
                $courseObj = $abroadCourseRepository->find($listing_type_id);
                if(is_object($courseObj)) {
					$listingLocations = $courseObj->getMainLocation();
				}
    			$returnResult['isStudyAbroadListing'] = true;
            }else {				
				$this->load->builder("nationalCourse/CourseBuilder");
				$courseBuilder = new CourseBuilder();			
    			$courseRepository = $courseBuilder->getCourseRepository();
    			$course = $courseRepository->find($listing_type_id,array('basic','location'));
    			//error_log('contaaaxxx'.print_r($course,true));
    			if(is_object($course)) {
					$locations = $course->getLocations();
				}
    			$returnResult['isStudyAbroadListing'] = false;
            }
        }
        
		if(is_array($locations) && count($locations)>0 && is_object($locations[$institute_location_id])) {
			
			$returnResult['city'] = $locations[$institute_location_id]->getCityName();
			$returnResult['locality'] = $locations[$institute_location_id]->getLocalityName();
			if(is_object($locations[$institute_location_id]->getContactDetail())) {
				$email = $locations[$institute_location_id]->getContactDetail()->getAdmissionEmail();
				if(!empty($email)) {
					$returnResult['email']  = $email;
				} else {
					$returnResult['email'] = $locations[$institute_location_id]->getContactDetail()->getGenericEmail();
				}
				$returnResult['cell'] = $locations[$institute_location_id]->getContactDetail()->getGenericContactNumber();
			}
			$returnResult['countryId'] = 2;
			
		}
		
        return $returnResult;
    }

    function generateBulkLead($request){
		
        $parameters = $request->output_parameters();
        $formVal = $parameters['1'];
        $appId = $parameters['0']['0'];
        
        $this->load->library(array('alerts_client'));
        $this->load->model('listing/coursemodel');
        $this->load->builder('ListingBuilder','listing');
        
        $mail_client = new Alerts_client();
        $listingBuilder = new ListingBuilder();
        $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
        $abroadInstituteRepository = $listingBuilder->getAbroadInstituteRepository();
        $universityRepository = $listingBuilder->getUniversityRepository();    
        $dbHandle = $this->_loadDatabaseHandle('write');
	
        /*
         * Response query to process, only made on courses
         */
        $queryCmd = "SELECT b.username, a.userId, b.listing_type_id, c.responseId, ".
                    "b.listing_type, b.listing_title, c.instituteLocationId, ".
                    "a.submit_date,t.email as ownerEmail ".
					"FROM tempLmsRequest a ".
			        "INNER JOIN listings_main b ON ".
			        "(b.listing_type = a.listing_type AND b.listing_type_id = a.listing_type_id) ".
					"INNER JOIN tuser t ON t.userid = b.username ".
					"INNER JOIN tuserflag tf ON tf.userid = a.userID ".
					"LEFT JOIN responseLocationTable c on c.requestResponseId = a.id ".
					"WHERE a.submit_date < DATE_SUB( now( ) , INTERVAL 1 HOUR ) ".
					"AND b.status = 'live' ".
					"AND a.listing_subscription_type ='paid' ".
					"AND a.listing_type='course' ".
					"AND tf.isTestUser = 'NO' ".
					"AND tf.ownershipchallenged = '0' ".
					"AND tf.abused = '0' ".
					"AND a.mailSent = 'no'";
	
        //error_log('BULKQUERIES'.$queryCmd);
        $query = $dbHandle->query($queryCmd);
        	
        $userIdArr = array();
        $locationIdArr = array();
        $usernameArr = array();
        $myArr = array();
    	$listingOwners = array();
    	$listingTitleArr = array();
	
    	//Stop study abroad UG responses
    	$userIds = array();
    	$courseIds = array();
    	$UGUsers = array();
    	$UGStudyAbroadCourses = array();
    
    	foreach ($query->result() as $row) {
    		$userIds[] = $row->userId;
    		if($row->listing_type == 'course') {
    			$courseIds[] = $row->listing_type_id;
    		}

            $tempLMSIds[] = $row->responseId;
    	}
    	
    	$userIds = array_unique($userIds);
    	$courseIds = array_unique($courseIds);
	
        if(count($userIds) && count($courseIds)) {
			
    		$queryCmd = "SELECT DISTINCT cd.course_id FROM course_details AS cd ".
    		            "INNER JOIN abroadCategoryPageData AS cpd ON cpd.course_id = cd.course_id ".
    		            "WHERE cd.course_id IN ( ".implode(',',$courseIds)." ) ".
    		            "AND cd.course_level_1 = 'Under Graduate' ".
    		            "AND cpd.country_id !=2 AND cd.status = 'live' AND cpd.status = 'live'";
    		            
    		$queryForUGCourses = $dbHandle->query($queryCmd);
    		foreach ($queryForUGCourses->result() as $row) {
    			$UGStudyAbroadCourses[$row->course_id] = 1;
    		}
		
            if(count($UGStudyAbroadCourses)){
                $queryCmd = "SELECT DISTINCT UserId FROM tUserEducation ".
                            "WHERE UserId IN ( ".implode(',',$userIds)." ) AND Level = 'UG'";
                $queryForUGUser = $dbHandle->query($queryCmd);
                foreach ($queryForUGUser->result() as $row) {
                	$UGUsers[$row->UserId] = 1;
                }
            }
        } /*  abroad check ends here*/
        
        $actionTypeMap = $this->getActionTypeForResponse($tempLMSIds);

        foreach ($query->result() as $row) {
			
    	    if(!($UGStudyAbroadCourses[$row->listing_type_id] == 1 AND $UGUsers[$row->userId] == 1 AND $row->listing_type == 'course')) {
        		$listingIdArr[$row->listing_type.'-'.$row->listing_type_id] = $row->listing_type_id;
        		$userIdArr[] = $row->userId;
        		$locationIdArr[] = $row->instituteLocationId; 
        		$myArr[$row->listing_type.'-'.$row->listing_type_id.'-'.$row->instituteLocationId]['userids'][] = array('userId' => $row->userId,'time' => $row->submit_date,'location' => $row->instituteLocationId,'action_type'=>$actionTypeMap[$row->responseId]);
        		$listingOwners[$row->listing_type.'-'.$row->listing_type_id.'-'.$row->instituteLocationId] = $row->ownerEmail;
        		$listingTitleArr[$row->listing_type.'-'.$row->listing_type_id] = $row->listing_title;
    	    }
    	    
        }

    	$abroad_university_course_level = array();
    	$abroadUniversityName = array();
    	foreach($courseIds as $courseId) {
			
            $isAbroadCourse = $this->coursemodel->isStudyAboradListing($courseId, 'course');		
            if($isAbroadCourse) {
    			$courseObj = $abroadCourseRepository->find($courseId);
    			$universityId = $courseObj->getUniversityId();
    			$listingName = html_entity_decode($courseObj->getUniversityName());
    			$courseName = $courseObj->getName();
                $course_level =  $courseObj->getCourseLevel();    			
    			$universityObj = $universityRepository->find($universityId);
    			$cityId = $universityObj->getLocation()->getCity()->getId();
    			$cityName = $universityObj->getLocation()->getCity()->getName();
    			$countryId = $universityObj->getLocation()->getCity()->getCountryId();
    			$countryName = $universityObj->getLocation()->getCountry()->getName();
    			
    			$locationDetails = array();
    			if($locationId > 1) {
    			    $locationDetails['cityId'] = $cityId;
    			    $locationDetails['cityName'] = $cityName;
    			    $locationDetails['countryId'] = $countryId;
    			    $locationDetails['countryName'] = $countryName;
    			}
    			//$listingTitleArr['course-'.$courseId] = $courseName;
    			$abroadUniversityName[$courseId] = $listingName;
    			$abroad_university_course_level[$courseId] = $course_level;
            }
            
        }
        
        $listingIdArr = array_unique($listingIdArr);
        $joinedListingIdArr = implode(',',$listingIdArr);
    	$userIdArr = array_unique($userIdArr);
    	$joinedUserIdArr = implode(',',$userIdArr);
	
        if($joinedUserIdArr == '' || $joinedListingIdArr == '') {
        	$queryCmd3 = "Update tempLmsRequest ".
        		     "set mailSent = 'yes' ".
        		     "where tempLmsRequest.submit_date  < DATE_SUB(now() , INTERVAL 1 HOUR) ".
        		     "and mailSent = 'no'";
        	$query = $dbHandle->query($queryCmd3);
        	$response = array(array('QueryStatus'=>$query),'struct');
        	return $this->xmlrpc->send_response($response);
        }
	
      //   $queryForSales = "SELECT a.email, c.clientuserid, lm.listing_type_id, lm.listing_title, lm.listing_type 
						// FROM tuser a, SUMS.Transaction c, SUMS.Sums_User_Details sud, listings_main lm 
						// WHERE lm.username = c.clientuserid 
						// AND c.SalesBy = sud.EmployeeId 
						// AND sud.userId = a.userid 
						// AND lm.status = 'live' 
						// AND lm.listing_type_id 
						// IN (".$joinedListingIdArr." )
						// ORDER BY c.TransactTime ASC";
												        
      //   $resultForSales = $dbHandle->query($queryForSales);
      //   foreach ($resultForSales->result() as $row1){
      //       $salesArr[$row1->listing_type.'-'.$row1->listing_type_id]['salesemail'] = $row1->email;
      //       $salesArr[$row1->listing_type.'-'.$row1->listing_type_id]['title'] = $row1->listing_title;
      //   }
        
        $courseIds = array();
        
        foreach($myArr as $k=>$v){
						
            list($listing_type, $listing_type_id,$locationId) = explode('-', $k);
            $cDetails = array();
            $toEmail = '';
            
            //error_log('contaaaxxx'.$locationId);
            // need to diffrentiate between india and abroad
            if($locationId != ''){
                $cDetails = $this->_getContactDetails($listing_type, $listing_type_id,$locationId);
                //error_log('contaaaxxx'.print_r($cDetails,true));
                $toEmail = $cDetails['email'];
            }
            
            if($toEmail == ''){
                $sql = "select a.email from tuser a, listings_main b ".
                       "where a.userid = b.username and b.listing_type_id = ? ".
                       "and b.listing_type=? and b.status = 'live'";
                $myrs = $dbHandle->query($sql, array($listing_type_id, $listing_type));
                $myRow = $myrs->row();
                $toEmail = $myRow->email;
            }
            
            if($listing_type == 'course'){
                $courseIds[] = $listing_type_id;
            }
            
            $myArr[$k]['toemail'] = $toEmail;
            $myArr[$k]['city'] = $cDetails['city'];
            $myArr[$k]['locality'] = $cDetails['locality'];
            $myArr[$k]['countryId'] = $cDetails['countryId'];
        }
	
	/*
	 * logic to replace emails of clients with RMS councellors email Id which are mapped to SQuares RMS councellors
	 * Otherwise if counsellor of type RMS is mapped, send mail to client keeping RMS type counsellor in bcc
	 */
	$courseIds = array_unique($courseIds);
	$councellorData = $this->_getCounsellorInfoForAbroadListings($courseIds);
	foreach($myArr as $k=>$v){
		list($listing_type, $listing_type_id,$locationId) = explode('-', $k);
		if($listing_type == 'course' && array_key_exists($listing_type_id,$councellorData)){
            /* Not in use so commenting. */
			/*if($councellorData[$listing_type_id]['counsellor_rms_type'] == 'SQuares'){
				$myArr[$k]['toemail'] = $councellorData[$listing_type_id]['counsellor_email'];
				$myArr[$k]['ccemail'] = $councellorData[$listing_type_id]['counsellor_manager_email'];
			}else*/
            if($councellorData[$listing_type_id]['counsellor_rms_type'] == 'RMS'){
				$myArr[$k]['bccemail'] = $councellorData[$listing_type_id]['counsellor_email'];
			}
		}
	}
		//error_log(print_r($myArr,true));
	
		$userDataArr = array();
	
		$userQuery = "SELECT t.userid,t.displayname,t.firstname,t.lastname,t.email,t.isdCode,t.mobile,t.landline,t.dateofbirth,t.age, ".
					"t.gender,t.experience, t.passport, tuf.isNDNC, cct.city_name as residenceCity, lct.localityName as Locality, ".
					"tup.UserFundsOwn,tup.UserFundsBank,tup.UserFundsNone,tup.otherFundingDetails,tup.TimeOfStart, DATE_FORMAT(tup.TimeOfStart,'%Y') as YearOfStart, ".
					"tup.DesiredCourse,tup.ExtraFlag,tup.SubmitDate,tup.program_budget, ".
					"tcsm.CourseName,tcsm.CourseLevel1,cbt.name as categoryOfInterest, ".
					"DATE_FORMAT(lastlogintime,'%D %b %Y') as LastLoginDate ".
					"FROM tuser t ".
					"LEFT JOIN countryCityTable cct ON cct.city_id = t.city ".
					"LEFT JOIN localityCityMapping lct ON lct.localityId = t.Locality ".
					"LEFT JOIN tuserflag tuf ON tuf.userid = t.userid ".
					"LEFT JOIN tUserPref tup ON tup.userid = t.userid ".
					"LEFT JOIN tCourseSpecializationMapping tcsm ON tcsm.SpecializationId = tup.DesiredCourse ".
					"LEFT JOIN categoryBoardTable cbt ON cbt.boardId = tcsm.categoryId ".
					"WHERE t.userid IN (".$joinedUserIdArr.") ";
					 
		$query2 = $dbHandle->query($userQuery);
		foreach($query2->result_array() as $result) {
			$userDataArr[$result['userid']]['general'] = $result;
		}
		
		$userQuery = "SELECT UserId,Name,Level,Marks,MarksType,OngoingCompletedFlag,CourseCompletionDate ".
					 "FROM tUserEducation ".
					 "WHERE UserId IN (".$joinedUserIdArr.") ";
		$query2 = $dbHandle->query($userQuery);
		foreach($query2->result_array() as $result) {
			$userDataArr[$result['UserId']]['education'][] = $result;
		}
		
		$userQuery = "SELECT ct.name as CountryName, tl.CountryId, tl.UserId ".
					 "FROM tUserLocationPref tl ".
					 "LEFT JOIN countryTable ct ON ct.countryId = tl.CountryId ".
					 "WHERE tl.UserId IN (".$joinedUserIdArr.") ";
		$query2 = $dbHandle->query($userQuery);
		foreach($query2->result_array() as $result) {
			$userDataArr[$result['UserId']]['location'][] = $result;
		}
		
		$userQuery = "SELECT tup.userid, name FROM categoryBoardTable cbt ".
						"LEFT JOIN tUserPref tup ON cbt.boardId = tup.abroad_subcat_id ".
						"WHERE tup.userid in (".$joinedUserIdArr.") " .
						"AND cbt.flag = 'studyabroad' AND cbt.isOldCategory = '0'";
		$query2 = $dbHandle->query($userQuery);
		foreach($query2->result_array() as $result) {
			$userDataArr[$result['userid']]['specialization'][] = $result;
		}				
		
		//error_log(print_r($userDataArr,true));
		global $IVR_Action_Types;
		foreach($myArr as $k=>$v) {
						
			list($listingType, $listingTypeId, $locationId) = explode('-',$k);
			$content = '';														
			
            $listingResponseLocations = array();
            $skipCourseNameFlag = true;
            $mailContent='';
            foreach($v['userids'] as $responseData) {
                $user = $userDataArr[$responseData['userId']];
                $isAbroadCourse = $this->coursemodel->isStudyAboradListing($listingTypeId, $listingType);
                $mailContent = $this->_getResponseMailContent($responseData,$v,$user,$listingType,$listingTypeId,$listingTitleArr,$isAbroadCourse,$abroad_university_course_level,$listingIdArr);                           

                $content .= $mailContent;

                $listingResponseLocations[] = $responseData['location'];

                if(!in_array($responseData['action_type'], $IVR_Action_Types) ){
                    $skipCourseNameFlag = false;
                }
            }
            
            
            $subject = $this->_getResponseMailSubject($listingTitleArr,$listingType,$listingTypeId, $skipCourseNameFlag);

			$ownerEmail = $listingOwners[$k];
			$sql = "SELECT HEX(ENCODE('".$ownerEmail."','ShikSha')) as encodedEmail";
			$squery = $dbHandle->query($sql);
			$srow = $squery->row_array();
			$encodedEmail= $srow['encodedEmail'];
			
			$redirectURL = SHIKSHA_HOME.'/enterprise/Enterprise/getResponsesForListing/'.$listingTypeId.'/'.$listingType;
			if($listingType == 'institute') {
				$redirectURL .= '/both';
			}
			else {
				$redirectURL .= '/courseOnly';
			}
			$redirectURL .= '/'.$locationId.'/none/0/10';
			
			$autoLoginLink = SHIKSHA_HOME.'/index.php/mailer/Mailer/autoLogin/email~'.$encodedEmail.'_url~'.base64_encode($redirectURL);
									
			$fullcontent = '';
			$fullcontent = $this->load->view("bulkMailer", array('content'=>$content), 'true');
			
            $blockedEmailids = array();
			if((strlen($v['toemail']) > 0) && (!in_array($v['toemail'], $blockedEmailids))) {
				$response = $mail_client->externalQueueAdd("12",ADMIN_EMAIL,$v['toemail'],$subject,$fullcontent,$contentType="html",'0000-00-00 00:00:00','n',array(),$v['ccemail'],$v['bccemail']);
			}
									
		}

        $queryCmd3 = "Update tempLmsRequest ".
                     "set mailSent = 'yes' ".
                     "where tempLmsRequest.submit_date  < DATE_SUB(now() , INTERVAL 1 HOUR) ".
                     "and mailSent = 'no'";
        
        $query = $dbHandle->query($queryCmd3);
        $response = array(array('QueryStatus'=>$query),'struct');
        return $this->xmlrpc->send_response($response);
    }
    
    private function _getResponseMailSubject($listingTitleArr,$listingType,$listingTypeId, $skipCourseNameFlag = false) {
		
		$title = $listingTitleArr[$listingType.'-'.$listingTypeId];
		if(!empty($title)) {				
			$subject = "Response to your listing - ".$title." on Shiksha.com";		
		} else {
			$subject = "Response to your listing on Shiksha.com";
		}
		
        if($skipCourseNameFlag){
            $subject = "Response to your listing on Shiksha.com";
        }

		return $subject;
	}
	
	private function _getResponseMailContent($responseData,$v,$user,$listingType,$listingTypeId,$listingTitleArr,$isAbroadCourse,$abroad_university_course_level,$listingIdArr) {
		global $IVR_Action_Types;
		$content = "";
        
        $content.="<b>Name : </b>".$user['general']['firstname']." ".$user['general']['lastname']."<br/>";
        $content.="<b>Email : </b>".$user['general']['email']."<br/>";
        
        if(isset($user['general']['isdCode'])) {
            $content.="<b>Mobile : </b>"."+".$user['general']['isdCode']."-".$user['general']['mobile']."<br/>";
        } else {                       
            $content.="<b>Mobile : </b>".$user['general']['mobile']."<br/>";
        }   
                                            
        if($listingType == 'course' && !$isAbroadCourse) {
            if(!in_array($responseData['action_type'], $IVR_Action_Types)){
                 $content.="<b>Course of Interest : </b>".$listingTitleArr[$listingType.'-'.$listingTypeId]."<br/>";
            }else{
                $content.="<b>Course of Interest : </b>Student has shown interest in your college/university and yet to make course specific action<br/>";
            }
        }
        
        if($listingType == 'course' && $isAbroadCourse){
            $content.="<b>Response To: </b>".$listingTitleArr[$listingType.'-'.$listingTypeId]."<br/>";
            $content .= "<b>Course ID: </b>".$listingIdArr[$listingType.'-'.$listingTypeId]."<br/>";
        }

					
		if($v['countryId'] == 2 && !in_array($responseData['action_type'], $IVR_Action_Types) ) {
				if($v['city']!= '') {
					$content.="<b>Course City : </b>".$v['city']."<br/>";
				}
				if($v['locality']!= '') {
					$content.="<b>Course Locality : </b>".$v['locality']."<br/>";
				}
		}
					
	    $content.="<b>Date of response : </b>".date('j<\s\up>S</\s\up> M Y',strtotime($responseData['time']))."<br/>";

        if($responseData['action_type'] != '') {
            $content.= "<b>Response Type : </b>".$responseData['action_type']."<br/>";
        }
					
		if($isAbroadCourse) {
				$content.="<b>Last login on : </b>".date('j<\s\up>S</\s\up> M Y',strtotime($user['general']['LastLoginDate']))."<br/>";
		}					
		$content.="<b>Is in NDNC list : </b>".trim($user['general']['isNDNC'])."<br/>";
					
		if(trim($user['general']['categoryOfInterest']) && $isAbroadCourse) {
			$educationInterest = trim($user['general']['categoryOfInterest']);
				
			if($isAbroadCourse) {
				global $studyAbroadPopularCourses;
				if($educationInterest == "All") {
					$desiredCourse = $user['general']['DesiredCourse'];
					if(array_key_exists($desiredCourse,$studyAbroadPopularCourses)) {
						$educationInterest = $studyAbroadPopularCourses[$desiredCourse];
					}
				}
			}
			if($user['specialization'][0]['name']) {
				$educationInterest.=" (".$user['specialization'][0]['name'].")";
			}					
			$content.="<b>Desired Course: </b>".$educationInterest."<br/>";
		}
				
		if($isAbroadCourse) {
			$courseLevel = $abroad_university_course_level[$listingTypeId];
			if(!empty($courseLevel)) {                     
				$content.="<b>Desired Course Level: </b>".trim($courseLevel)."<br/>";
			}	
		}
		
		if($isAbroadCourse) {
			if(trim($user['general']['TimeOfStart']) && trim($user['general']['TimeOfStart']) != '0000-00-00 00:00:00') {
				$datediff = datediff($user['general']['TimeOfStart'],$user['general']['SubmitDate']);
				$content.="<b>Plan to Start: </b>";
				if(!$isAbroadCourse) {
					if($datediff == 0) {
						$content .= "Immediately";
					}
					else {
						$content .= "Within ".$datediff;
					}
					$content .= " (as on ".date('j<\s\up>S</\s\up> M Y',strtotime($user['general']['SubmitDate'])).")<br/>";
				}
				else {
					if($user['general']['YearOfStart'] == date('Y'))
						$content .= 'Current Year<br/>';
					else if($user['general']['YearOfStart'] == date('Y')+1)
						$content .= 'Next Year<br/>';
					else if($user['general']['YearOfStart'] > date('Y')+1)
						$content .= 'Not Sure<br/>';
				}
			}
		}
				
		if($isAbroadCourse) {					
			$validPassport = null;															
			if($user['general']['passport']) {
				$validPassport = $user['general']['passport'];
			}
			if($validPassport) {
				$content .="<b>Valid Passport: </b>".$validPassport."<br/>";
			}
		}
				
		if($user['general']['residenceCity']) {
			$content.= "<b>Current Location : </b>".$user['general']['residenceCity']."<br/>";
		}
				
		if(!$isAbroadCourse) {
			if($user['general']['Locality']) {
				$content.= "<b>Current Locality : </b>".$user['general']['Locality']."<br/>";
			}
		}
								
		if($isAbroadCourse) {
			$prefCountryArray= array();
			if(is_array($user['location'])) {
				foreach($user['location'] as $location) {
					if($location['CountryId'] != 0 && $location['CountryName'] != ""){
						if(!in_array($location['CountryName'],$prefCountryArray))
							array_push($prefCountryArray,$location['CountryName']);
						}
				}
				$content .= "<b>Desired Countries: </b>".implode(', ',$prefCountryArray)."<br/>";
			}
		}
				
		if(is_array($user['education'])) {
			
			$examsTaken = array();
			if($isAbroadCourse) {
				
				foreach($user['education'] as $education) {
					if($education['Level'] == 12 && !$isAbroadCourse) {
						$content.= "<b>XII Year : </b>";							
						if($education['CourseCompletionDate'] && $education['CourseCompletionDate'] != '0000-00-00') {
							$content.= date('M Y',strtotime($education['CourseCompletionDate']));
						}
						$content .= '<br />';
					}
				}
							
				foreach($user['education'] as $education) {
					if($education['Level'] == 'UG' && !$isAbroadCourse) {
						$content.= "<b>Graduation Year : </b>";							
						if($education['CourseCompletionDate'] && $education['CourseCompletionDate'] != '0000-00-00') {
							$content.= date('M Y',strtotime($education['CourseCompletionDate']));
						}
						$content .= '<br />';
					}
				}
																				
				foreach($user['education'] as $education) {
					if($education['Level'] == 'Competitive exam') {
						$examObj = \registration\builders\RegistrationBuilder::getCompetitiveExam($education['Name'],$education);
						$examsTaken[] = $examObj->displayExam();
					}
				}
			
			} else {
				
				foreach($user['education'] as $education) {
					if($education['Level'] == 'Competitive exam') {
						$examsTaken[] = $education['Name'];
					}
				}
			}
			
			if(count($examsTaken) > 0) {
				$content.= '<b>Exams taken : </b>'.implode(', ',$examsTaken)."<br />";
			}
		}
		
		$content .= '<br><p>--------------------------------------------------------------------------------------</p>';	
		
		return $content;
	}

	/**
	 * Grading of responses
	 * If a response was made on a low priority action
	 * and again made on high priority action,
	 * we'll update the action
	 */ 
	private function _gradeResponse($response,$formVal)
    {
        $responseId = $response['id'];
        $currentAction = $response['action'];
        
        $newAction = $formVal['action'];
        $userId = $formVal['userId'];
        if($formVal['listing_type'] == 'course') {
            $courseId = $formVal['listing_type_id'];
        }
        
        $responseGrades = array(
            'Applied' => -1,
            'Applying' => -1,
            'TakenAdmission' => -1,
            'Clicked_on_SMS' => -1,
            'Online_Application_Started' => -1,
            'CollegePredictor' => 1,
            'RankPredictor' => 1,
            'rate_my_chances' => 1,
            'rate_my_chances_sa_mobile' => 1,
            'User_ShortListed_Course' => 2,
            'User_ShortListed_Course_sa_mobile' => 2,
            'MOB_Course_Shortlist' => 2,
            'ND_myshortlist_shortlist' => 2,
            'ND_category_shortlist' => 2,
            'ND_ranking_shortlist' => 2,
            'ND_course_shortlist' => 2,
            'NM_course_shortlist' =>2,
            'MOB_Category_Shortlist' => 2,
            'ND_CategoryReco_shortlist' => 2,
            'Compare_Email' => 3,
            'MOB_COMPARE_EMAIL' => 3,
            'Request_Callback' => 3, //for request a callback functionality under RMS (study abroad)
            'Request_Callback_sa_mobile' => 3, //for request a callback functionality under RMS (study abroad)
            'CP_Request_Callback' => 3, // Request call back from category page
            'CP_Request_Callback_sa_mobile' => 3, // Request call back from category page
            'Shortlist_Request_Callback' => 3, // Request call back from shortlist tab/page
            'Shortlist_Request_Callback_sa_mobile' => 3, // Request call back from shortlist tab/page
            'Asked_Question_On_Listing' => 4,
            'Asked_Question_On_Listing_MOB' => 4,
            'Asked_Question_On_CCHome' => 4,
            'Asked_Question_On_CCHome_MOB' => 4,
            'D_MS_Ask' => 4,
            'COMPARE_AskQuestion' => 4,
            'COMPARE_EBrochure' => 4,
            'MOB_COMPARE_EBrochure' => 4,
            'GetFreeAlert' => 4,
            'GetFreeAlert_sa_mobile' => 4,
            'LISTING_PAGE_EMAIL_SMS_CONTACT_DETAILS' => 4,
            'MOB_CareerCompass_Ebrochure'=> 4,
            'download_brochure_free_course' => 5,
            'download_brochure_free_course_sa_mobile' => 5,
            'MOBILEHTML5' => 5,
            'MOBILE5_CATEGORY_PAGE' => 5,
            'MOBILE5_INSTITUTE_DETAIL_PAGE' => 5,
            'MOBILE5_SIMILAR_INSTITUTE_DETAIL_PAGE' => 5,
            'MOBILE5_SIMILAR_COURSE_DETAIL_PAGE' => 5,
            'MOBILE5_COURSE_DETAIL_PAGE' => 5,
            'MOBILE5_COURSE_DETAIL_PAGE_OTHER' => 5,
            'MOBILE5_SEARCH_PAGE' => 5,
            'MOBILE5_COLLEGE_PREDICTOR_PAGE' => 5,
            'MOBILE5_RANK_PREDICTOR_PAGE' => 5,
            'MOBILE5_SHORTLIST_PAGE' => 5,
            'MOBILEHTML5_GETEB' => 5,
            'RANKING_MOB_ReqEbrochure' => 5,
            'mobilesite' => 5,
            'mobilesitesearch' => 5,
            'RANKING_PAGE_REQUEST_EBROCHURE' => 5,
            'Request_E-Brochure' => 5,
            'Wanted_to_talk' => 5,
            'Request_E-Brochure_sa_mobile' => 5,
            'D_MS_Request_e_Brochure' => 5,
            'OF_Request_E-Brochure' => 5,
            'brochureUnivSARanking' => 5,
            'brochureUnivSARanking_sa_mobile' => 5,
            'brochureCourseSARanking' => 5,
            'brochureCourseSARanking_sa_mobile' => 5,
            'SEARCH_REQUEST_EBROCHURE' => 5,
            'request_salaryData' => 6,
            'CoursePage_Reco' => 7,
            'CP_MOB_Reco_ReqEbrochure' => 7,
            'CP_Reco_divLayer' => 7,
            'CP_Reco_popupLayer' => 7,
            'CP_Reco_ReqEbrochure' => 7,
            'CP_Reco_ReqEbrochure_sa_mobile' => 7,
            'LP_MOB_Reco_ReqEbrochure' => 7,
            'LP_Reco_ ReqEbrochure' => 7,
            'LP_Reco_AlsoviewLayer' => 7,
            'LP_Reco_AlsoviewLayer_sa_mobile' => 7,
            'LP_Reco_ReqEbrochure' => 7,
            'LP_Reco_ReqEbrochure_sa_mobile' => 7,
            'LP_Reco_ShowRecoLayer' => 7,
            'LP_Reco_SimilarInstiLayer' => 7,
            'LP_Reco_SimilarInstiLayer_sa_mobile' => 7,
            'reco_also_view_layer_sa_mobile'=>7,
            'RP_Reco_AlsoviewLayer' => 7,
            'RP_Reco_AlsoviewLayer_sa_mobile' => 7,
            'RANKING_MOB_Reco_ReqEbrochure' => 7,
            'reco_after_category' => 7,
            'reco_widget_mailer' => 7,
            'reco_widget_mailer_sa_mobile' => 7,
            'SEARCH_MOB_Reco_ReqEbrochure' => 7,
            'similar_institute_deb' => 7,
            'COMPARE_VIEWED' => 7,
            'MOB_COMPARE_VIEWED' => 7,
            'Shortlist_Page_Reco_ReqEbrochure' => 7,
            'Shortlist_Page_Reco_ReqEbrochure_sa_mobile' => 7,
            'LP_AdmissionGuide' => 8,
            'LP_AdmissionGuide_sa_mobile' => 8,
            'LP_EligibilityExam' => 8,
            'LP_EligibilityExam_sa_mobile' => 8,
            'Viewed_Listing' => 10,
            'Viewed_Listing_sa_mobile' => 10,
            'Viewed_Listing_Pre_Reg' => 10,
            'Viewed_Listing_Pre_Reg_sa_mobile' => 10,
            'Mob_Viewed_Listing_Pre_Reg' => 10,
            'mobile_viewedListing' => 11,
            'Institute_Viewed' => 12,
            'Mailer_Alert' => 19,
            'mailerAlert' => 19
        );
        global $pageTypeResponseActionMapping;
        $shortlistResponseActions = array_values($pageTypeResponseActionMapping);
        foreach($shortlistResponseActions as $responseAction) {
                $shortlistResponseGrades[$responseAction] = 2;
        }
        $responseGrades = array_merge($responseGrades, $shortlistResponseGrades);
        
        $currentResponseGrade = $responseGrades[$currentAction];
        $newResponseGrade = $responseGrades[$newAction];
        
        $makeResponse = false;
        if($currentResponseGrade > 1) {
            if(stripos($newAction, 'client')) {
                $makeResponse = true;
            }
        }
        
        if(($currentResponseGrade && $newResponseGrade && $newResponseGrade < $currentResponseGrade) || $makeResponse) {
            $dbHandle = $this->_loadDatabaseHandle('write');

            if(!empty($formVal['tracking_page_key'])) {
                
                $queryCmd = "UPDATE tempLMSTable SET action = ?,submit_date=?,tracking_keyid= ?, visitorsessionid = ? WHERE id = ?";
                $dbHandle->query($queryCmd, array($newAction,$formVal['submit_date'], $formVal['tracking_page_key'],$formVal['visitorSessionid'],$responseId));
            } else {
                
                $queryCmd = "UPDATE tempLMSTable SET action = ?,submit_date=? WHERE id = ?";
                $dbHandle->query($queryCmd, array($newAction,$formVal['submit_date'],$responseId));
                
            }

            $insertQuery = "INSERT into upgradedResponseData (tempLMSId,isProcessed,responseDate) values(?,'no',?) on duplicate key update isProcessed='no', responseDate =? ";
            $dbHandle->query($insertQuery,array($responseId,$formVal['submit_date'],$formVal['submit_date']));
                
            if($userId > 0 && $courseId > 0) {
                $queryCmd = "UPDATE latestUserResponseData SET action = ?,modified_at = ? WHERE userId = ? AND courseId = ?";
                $dbHandle->query($queryCmd, array($newAction, $formVal['submit_date'], $userId, $courseId));
            }
            
            //add user to  tuserIndexingQueue
            $this->load->model('user/usermodel');
            $this->usermodel->addUserToIndexingQueue($formVal['userId']);

            $dbHandle->where('queue_id', $formVal['queue_id']);
            $dbHandle->where('response_from', 'abroad');
            $dbHandle->update('response_elastic_map', array('temp_lms_id' => $responseId,'temp_lms_time'=> date("Y-m-d H:i:s")));

            //insert data in elastic server queue
            $eData['action']   = 'sa_update_response';
            $eData['tempLMSId']= $responseId;
            $eDataEncoded      = json_encode($eData);

            $user_response_lib = $this->load->library('response/userResponseIndexingLib');      
            $user_response_lib->insertInResponseIndexLog($formVal['userId'],$eDataEncoded,$formVal['queue_id']);
        }
    }

    function insertLead($request)
    {
        $parameters = $request->output_parameters();
        $formVal = $parameters['1'];
        $listing_subscription_type = 'paid';
        
        if((!empty($formVal['listing_subscription_type'])) && (isset($formVal['listing_subscription_type']))) {
                $listing_subscription_type = $formVal['listing_subscription_type'];
        }
    
        if(!($formVal['listing_type_id'] > 0 && $formVal['userId'] > 0)) {
            return;
        }

        $formVal['visitorSessionid'] = (!empty($formVal['visitorSessionid'])) ? $formVal['visitorSessionid'] : getVisitorSessionId();
        $formVal['submit_date'] = (!empty($formVal['submit_date'])) ? $formVal['submit_date'] : date('Y-m-d H:i:s');
        
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        $appId = $parameters['0']['0'];
        $queryCmd = "select id,action,submit_date from tempLMSTable where listing_subscription_type=? and userId = ? and listing_type_id = ? and listing_type = ? and (UNIX_TIMESTAMP(?)- UNIX_TIMESTAMP(submit_date))<86400";
        $Result = $dbHandle->query($queryCmd, array($listing_subscription_type, $formVal['userId'], $formVal['listing_type_id'], $formVal['listing_type'],$formVal['submit_date']));
        $row = $Result->row_array();
	
        $userLib = $this->load->library('user/UserLib');

        $extraDataForExclusion = array(
            'tracking_keyid' => (!empty($formVal['trackingPageKeyId'])) ? $formVal['trackingPageKeyId'] :$formVal['tracking_page_key']
        );

        if($row['id']){
            $this->_gradeResponse($row,$formVal);
            $userLib->checkUserForLDBExclusion($formVal['userId'], "response", "course", $formVal['listing_type_id'],'','', $formVal['action'],$extraDataForExclusion);
            error_log("message 54  in old paid response");
            $response = array(array('QueryStatus'=>'N.A.','leadId'=>1),'struct');
            return $this->xmlrpc->send_response($response);
        }

        //Amit Singhal : Logic to avoid more than 5 course responses on an institute.
        if(($formVal['action'] ==  "Viewed_Listing" || $formVal['action'] == "mobile_viewedListing" || $formVal['action'] ==  "Viewed_Listing_Pre_Reg") && $formVal['listing_type'] == 'course'){
            
                if($this->checkForSameCouseResponse($formVal,$dbHandle) == false){
                        $response = array(array('QueryStatus'=>'N.A.','leadId'=>1),'struct');
                        return $this->xmlrpc->send_response($response); 
                }
        }
        
        /*
         *Check if userId is 0 and email id exists fetch the userid from tuser
         */
        if(empty($formVal['userId'])&& $formVal['contact_email']){
            $formVal['userId'] = $this->_getUserIdByEmail($formVal['contact_email']);
        }
        
        // Code to fetch ISD Code and NDNC status of the user
        $userIdArray[] = $formVal['userId'];

        $this->load->model('user/usermodel');
        $userFlagDetails = $this->usermodel->getUserBasicAndFlagDetails($userIdArray);

        //checking for trackingpagekey value
        if(!empty($formVal['trackingPageKeyId'])) {        
            $formVal['tracking_page_key']=$formVal['trackingPageKeyId'];
        }
        
        if(!empty($formVal['visitorSessionid'])){
            $visitorsessionid = $formVal['visitorSessionid'];
        }else{
            $visitorsessionid = getVisitorSessionId();
        }
        
        $formVal['isdCode'] = $userFlagDetails[$formVal['userId']]['isdCode'];
        $formVal['ndnc'] = $userFlagDetails[$formVal['userId']]['isNDNC'];
        
        $data = array(
                'listing_type_id'=>$formVal['listing_type_id'],
                'listing_type'=>$formVal['listing_type'],
                'userId'=>$formVal['userId'],
                'displayName'=>$formVal['displayName'],
                'message'=>$formVal['message'],
                'email'=>$formVal['contact_email'],
                'action'=>$formVal['action'],
                'contact_cell'=>$formVal['contact_cell'],
                'marketingFlagSent'=>isset($formVal['marketingFlagSent']) ? $formVal['marketingFlagSent'] : '',
                'marketingUserKeyId'=>isset($formVal['marketingUserKeyId']) ? $formVal['marketingUserKeyId'] : '',
                'listing_subscription_type'=>$listing_subscription_type,
                'tracking_keyid'=>$formVal['tracking_page_key'],
                'visitorsessionid'=>$visitorsessionid,
                'submit_date'=>$formVal['submit_date'] 
        );
        
        $flatArray = array(
            'userId'                    =>  $formVal['userId'],
            'courseId'                  =>  $formVal['listing_type_id'],
            'action'                    =>  $formVal['action'],
            'listing_subscription_type' =>  $listing_subscription_type,
            'modified_at'               =>  $formVal['submit_date']
        );
        
        $queryCmd = $dbHandle->insert_string('tempLMSTable',$data);
        //error_log("AMIT5".$queryCmd);
        $query = $dbHandle->query($queryCmd);
        $recent_id = $dbHandle->insert_id();

        $dbHandle->where('queue_id', $formVal['queue_id']);
        $dbHandle->where('response_from', 'abroad');
        $dbHandle->update('response_elastic_map', array('temp_lms_id' => $recent_id,'temp_lms_time'=> date("Y-m-d H:i:s"),'use_for_response'=>'y'));


        //insert data in elastic server queue
        $eData['action']   = 'sa_new_response';
        $eDataEncoded      = json_encode($eData);

        $user_response_lib = $this->load->library('response/userResponseIndexingLib');      
        $user_response_lib->insertInResponseIndexLog($formVal['userId'],$eDataEncoded,$formVal['queue_id']);
        
        //update recent response flat table
        $this->_insertLatestUserResponseData($dbHandle, $flatArray, $listing_subscription_type);
        
        /**
         * Update userMailerSentCount table to reset triggers of product mailers when a response is made
         */
        $this->load->library('mailer/ProductMailerEventTriggers');
        $this->productmailereventtriggers->resetMailerTriggers($formVal['userId'], 'responseCreated');
        
        /*
         * Register apply on recommendation
         */
        $this->load->library('recommendation_lib');
        $this->recommendation_lib->registerApplyOnRecommendation($formVal['userId'],$formVal['listing_type_id'],$formVal['listing_type'],$formVal['action']);

        /*
         If preferred city/locality specified
         By Vikas K, Aug 16, 2011
        */
        $locId = '';
        $locId = $this->_addResponseLocationInfo($recent_id,$formVal,$dbHandle);
        /*
         * Send SMS to client containing response info
         */
    
        //add user to  tuserIndexingQueue
        $this->load->model('user/usermodel');
        $this->usermodel->addUserToIndexingQueue($formVal['userId']);

        // add user to exclusion
        $userLib->checkUserForLDBExclusion($formVal['userId'], "response", "course", $formVal['listing_type_id'],'',$this->usermodel, $formVal['action'],$extraDataForExclusion);
        
        if($formVal['action'] != 'Request_Callback'){ // since SMS for request call back has already been sent, no need to send it now
            $this->sendResponseSMSToClient($dbHandle,$formVal, $locId);
        }

        //$this->sendResponseSMSToClient($dbHandle,$formVal, $locId);
        
        $this->_updateConsolidatedResponseData($dbHandle,$formVal);
        
        

        $response = array(
            array
                ('QueryStatus'=>$query,
                 'leadId'=>$recent_id
                ),
                'struct'
        );
        
        if( $formVal['sendMail'] != false){
            
            // Will remove once we find that whether this code is used or not
            //mail('aditya.roshan@shiksha.com','xxxxxxxxxxx','removecode');
                        
            if($formVal['action'] !='requestinfo' ){
                
                $queryCmd = "select count(*) as cnt from tempLMSTable where listing_subscription_type=? and userId = ? and listing_type=? and listing_type_id  = ?";
                $query = $dbHandle->query($queryCmd, array($listing_subscription_type, $formVal['userId'], $formVal['listing_type'], $formVal['listing_type_id']));
                $row = $query->row();
                $alreadyPresentLeads = $row->cnt;
                if($alreadyPresentLeads == 1){
                    $mailToBeSentArr = $this->mailToBeSent($dbHandle,$formVal['action'],$formVal['listing_type'],$formVal['listing_type_id']);
                    $mailIds='';
                    if($mailToBeSentArr['mailToBeSentFlag'] == 1){
                        $mailIds = $this->getMailIds($dbHandle,$formVal['action'],$formVal['listing_type'],$formVal['listing_type_id']);
                    }
                    //send mail
                    $sendMailArray = array();
                    $sendMailArray['mailViewName'] ='listing/interestMail';
                    $sendMailArray['userInfo'] = json_decode($formVal['userInfo'],true);
                    $sendMailArray['usernameemail'] = $formVal['contact_email'];
                    $sendMailArray['nameOfUser'] = $formVal['displayName'];
                    $sendMailArray['mobile'] = $formVal['contact_cell'];
                    $sendMailArray['educationLevel'] = $sendMailArray['userInfo'][0]['educationLevel']." ".$sendMailArray['userInfo'][0]['degree'];
                    $sendMailArray['educationInterest'] =$sendMailArray['userInfo'][0]['catofinterest'] ;
                    //$sendMailArray['age'] = date('Y') - date('Y',strtotime($sendMailArray['userInfo'][0]['DOB']));
                    $sendMailArray['age'] = $sendMailArray['userInfo'][0]['age'];
                    $sendMailArray['curDate'] = date('j F');
                    $sendMailArray['usercity'] =$sendMailArray['userInfo'][0]['cityname'];
                    $sendMailArray['listingTitle'] = $mailToBeSentArr['listing_title'];
                    $sendMailArray['tomail'] = $mailIds;
                    $sendMailArray['query'] = $formVal['message'];
                    $sendMailArray['action'] = $formVal['action'];
                    $mailSendStatus = $this->sendResponseMail($sendMailArray);
                }
            }
            else{
                $mailToBeSentArr = $this->mailToBeSent($dbHandle,$formVal['action'],$formVal['listing_type'],$formVal['listing_type_id']);
                $mailIds='';
                if($mailToBeSentArr['mailToBeSentFlag'] == 1){
                    $mailIds = $this->getMailIds($dbHandle,$formVal['action'],$formVal['listing_type'],$formVal['listing_type_id']);
                }
                //send mail
                $sendMailArray = array();
                $sendMailArray['mailViewName'] ='listing/responseMail' ;
                $sendMailArray['userInfo'] = json_decode($formVal['userInfo'],true);
                $sendMailArray['usernameemail'] = $formVal['contact_email'];
                $sendMailArray['nameOfUser'] = $formVal['displayName'];
                $sendMailArray['mobile'] = $formVal['contact_cell'];
                $sendMailArray['educationLevel'] = $sendMailArray['userInfo'][0]['educationLevel']." ".$sendMailArray['userInfo'][0]['degree'];
                $sendMailArray['educationInterest'] =$sendMailArray['userInfo'][0]['catofinterest'] ;
                //$sendMailArray['age'] = date('Y') - date('Y',strtotime($sendMailArray['userInfo'][0]['DOB']));
                $sendMailArray['age'] = $sendMailArray['userInfo'][0]['age'];
                $sendMailArray['curDate'] = date('j F');
                $sendMailArray['usercity'] =$sendMailArray['userInfo'][0]['cityname'];
                $sendMailArray['listingTitle'] = $mailToBeSentArr['listing_title'];
                $sendMailArray['tomail'] = $mailIds;
                $sendMailArray['query'] = $formVal['message'];
                $sendMailArray['action'] = $formVal['action'];
                $mailSendStatus = $this->sendResponseMail($sendMailArray);
            }
        }
        
        return $this->xmlrpc->send_response($response);
    }
	
	private function _addResponseLocationInfo($responseId,$formVal,$dbHandle)
	{
		
		global $listings_with_localities;
		global $SMULocalityMapping;
		
		$this->load->model('listing/coursemodel');
		$coursemodel = new coursemodel;
		
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;

        $instituteRepository = $listingBuilder->getAbroadInstituteRepository();
        $courseRepository = $listingBuilder->getAbroadCourseRepository();
		$abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
		
		$listingType = $formVal['listing_type'];
		$listingTypeId = $formVal['listing_type_id'];
		
		$instituteId = $listingTypeId;
		
		if($listingType == 'course') {
			
			$isAbroadCourse = $coursemodel->isStudyAboradListing($listingTypeId, 'course');
			
			if($isAbroadCourse) {
				$courseObj = $abroadCourseRepository->find($listingTypeId);
				$listingLocations = $courseObj->getMainLocation();
            } else {			
				$courseObj = $courseRepository->find($listingTypeId);
				$listingLocations = $courseObj->getLocations();
			}
            $instituteId = $courseObj->getInstId();
            
		} else {		
			
			if($listingTypeId >0) {
				$instituteObj = $instituteRepository->find($listingTypeId);
				$listingLocations = $instituteObj->getLocations();
			}
		}
		
		/**
		 * Check if the listing has custom locations
		 */
		$isSMUListings = FALSE;
		$hasCustomLocality = FALSE;
		if($listingType == 'course') {
			$this->load->model('registration/registrationmodel');
			$isCustom = $this->registrationmodel->checkCustomizedCity($listingTypeId);
		}
		
		/**
		 * For custom locations, store response location date in responseLocationPref table
		 */
		
		if($isCustom) {
			
			if($formVal['preferred_city']) {				

				if($isCustom){
					
					if(is_numeric($formVal['preferred_city'])){
						$this->load->library('category_list_client');
						$categoryClient = new Category_list_client();
						$formVal['preferred_city'] = $categoryClient->getCityName($formVal['preferred_city']);
					}
					
					if(is_numeric($formVal['preferred_locality'])){
						require_once FCPATH.'globalconfig/SMULocalityMapping.php';
						if(isset($SMULocalityMapping[$formVal['preferred_locality']])){
							$formVal['preferred_locality'] = $SMULocalityMapping[$formVal['preferred_locality']];
						}
					}
					
				}

				$Ldata = array(
						'response_id'=>$responseId,
						'city'=>$formVal['preferred_city'],
						'locality'=>$formVal['preferred_locality'],
						'processed'=>'No'
					      );
				$queryCmd = $dbHandle->insert_string('responseLocationPref',$Ldata);
				$query = $dbHandle->query($queryCmd);
			}
			
		} else {
			$responseCityId = 0;
			/**
			 * If location data is passed with response, retrieve location id from it
			 */ 
			if($formVal['preferred_city']) {
				
				$queryCmd = "SELECT institute_location_id FROM institute_location_table ".
					"WHERE institute_id = ? AND status = 'live' AND city_id = ? ".
					($formVal['preferred_locality'] ? "AND locality_id = ?" : "");

				if($formVal['preferred_locality']) {
					$query = $dbHandle->query($queryCmd,array($instituteId,$formVal['preferred_city'],$formVal['preferred_locality']));
				} else {
					$query = $dbHandle->query($queryCmd,array($instituteId,$formVal['preferred_city']));
				}
								
				$row = $query->row_array();
				$listingLocationId = $row['institute_location_id'];
				
				$responseCityId = $formVal['preferred_city'];
				
			} else if($formVal['institute_location_id']!=''){			
				// added for the case of response generation for abroad courses, where we pass the location id of university along with the data
				$listingLocationId = $formVal['institute_location_id'];
                                $sql = "SELECT city_id FROM institute_location_table WHERE institute_location_id = ? AND status = 'live'";
                                $query = $dbHandle->query($sql, array($formVal['institute_location_id']));
                                $row = $query->row_array();
                                $responseCityId = $row['city_id'];
			} else {	
				/**
				 * No location data is passed
				 * fetch the listing location yourself, if listing is single locations
				 * if listing is multilocation, do nothing as we do not know which location
				 * might have been selected
				 */
				if(is_array($listingLocations) && count($listingLocations) >0) {
					// Multi-location -- send alert
					$listingLocationId = $courseObj->getMainLocation()->getLocationId();
					$responseCityId = $courseObj->getMainLocation()->getCity()->getId();
				}
				
				if($isAbroadCourse) {
					if(is_object($listingLocations) && $listingLocations->getLocationId() > 0) {
						$listingLocationId = $listingLocations->getLocationId();
					}
				}
			}
			
			if($listingLocationId && !empty($responseId)) {
				
				$Ldata = array(
							'responseId' => $responseId,
							'instituteLocationId' => $listingLocationId,
							'requestResponseId' => $formVal['tempLmsRequest']

						);
				
				$queryCmd = $dbHandle->insert_string('responseLocationTable',$Ldata);
				$query = $dbHandle->query($queryCmd);	
				
				if($responseCityId) {
					$this->_updateResponseLocationAffinity($formVal['userId'], $responseCityId, $dbHandle);
				}
			}
			
			return $listingLocationId;
		}
	}
	
	function _updateResponseLocationAffinity($userId, $cityId, $dbHandle)
	{
		$sql = "INSERT INTO userResponseLocationAffinity (userId, cityId, affinity)
			VALUES (?, ?, 1)
			ON DUPLICATE KEY UPDATE affinity = affinity+1";
		$dbHandle->query($sql, array($userId, $cityId));
	}
	
	
	function _updateConsolidatedResponseData($dbHandle,$data)
	{
		$sql =  "SELECT id,userIds ".
				"FROM tresponsedata ".
				"WHERE courseId = ? ".
				"AND action = ?";
				
		$query = $dbHandle->query($sql,array($data['listing_type_id'],$data['action']));
		$row = $query->row_array();
		
		if($row['id']) {	
			$userIds = trim($row['userIds'],', ');
			$userIds .= ','.$data['userId'];
			$sql = "UPDATE tresponsedata SET userIds = ? WHERE id = ?";
			$dbHandle->query($sql, array($userIds, $row['id']));
		}
		else {
			$responseData = array(
				'courseId' => $data['listing_type_id'],
				'action' => $data['action'],
				'userIds' => $data['userId']
			);
			$dbHandle->insert('tresponsedata',$responseData);
		}
	}
	
	function sendResponseSMSToClient($dbHandle,$data,$locId)
	{
        if($locId !=''){
            $cDetails = $this->_getContactDetails($data['listing_type'], $data['listing_type_id'], $locId);
            $multiLocMobile = $cDetails['cell'];
        }
        else{
            $multiLocMobile = '';
        }

		$dbHandle = $this->_loadDatabaseHandle();
	
		$sql =  "SELECT lcd.contact_cell,tu.mobile,tu.userid,lm.listing_title ".
				"FROM listings_main lm ".
				"LEFT JOIN listing_contact_details lcd ON lcd.contact_details_id = lm.contact_details_id ".
				"LEFT JOIN tuser tu ON tu.userid = lm.username ".
				"WHERE lm.listing_type_id = ?".
				"AND lm.listing_type = ?".
				"AND lm.status = 'live'";
				
		$query = $dbHandle->query($sql, array($data['listing_type_id'], $data['listing_type']));
		$row = $query->row();
		
		$contact_cell = trim($row->contact_cell);
		$mobile = trim($row->mobile);
		$userId = trim($row->userid);
		$listingName = trim($row->listing_title);
		error_log("preferred_locality " . $data['preferred_locality']);
		error_log("userId " . $userId);
		if($userId == '1958541') {
			require_once FCPATH.'globalconfig/MAACCentreContacts.php';
			if(isset($maacCentreContacts[$data['preferred_locality']]['mobile']))
			{
				$mobileNumber = $maacCentreContacts[$data['preferred_locality']]['mobile'];
			}
		}
		else {
			$mobileNumber = $multiLocMobile;
			if(!$mobileNumber || $mobileNumber =='') {
				$mobileNumber = $contact_cell;
			}
			if(!$mobileNumber || $mobileNumber =='') {
				$mobileNumber = $mobile;
			}
		}
		error_log("mobileNumber to addSmsQueueRecord " . $mobileNumber);
		if($mobileNumber && $mobileNumber != '') {
			

			$responseName = $data['displayName'];
			
			if($data['userInfo']) {
				$responseInfo = json_decode($data['userInfo'],true);
				if(is_array($responseInfo) && is_array($responseInfo[0]) && $responseInfo[0]['firstname']) {
					$responseName = $responseInfo[0]['firstname'];
				}
			}
			
			$smsContent = "New response detail\nName: ".$responseName;
			//$smsContent .= "\nMobile: ".$data['contact_cell'];
            if($data['isdCode'] == '91')
			    $smsContent .= "\nMobile: ".$data['contact_cell'];
            else
			    $smsContent .= "\nMobile: +".$data['isdCode']."-".$data['contact_cell'];
            $smsContent .= "\nNDNC: ".$data['ndnc'];

			if($data['listing_type'] == 'course') {
				if($rmsCouncellorAttached){
					$smsContent .= "\nCourse ID: ".$data['listing_type_id'];
				}
				$smsContent .= "\nCourse: ".$listingName;
			}
			
			$this->load->library('alerts_client');
			$this->alerts_client->addSmsQueueRecord("12",$mobileNumber,$smsContent,$userId);
		}
	}

    function checkForSameCouseResponse($formVal,$dbHandle){
		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = "select count(*) noOfEntries from tempLMSTable where listing_subscription_type='paid' and userId = ? and listing_type_id in(select b.course_id from course_details a, course_details b where a.course_id = ? and b.institute_id = a.institute_id and a.status = 'live' and b.status='live') and listing_type = 'course'";
		$Result = $dbHandle->query($queryCmd, array($formVal['userId'], $formVal['listing_type_id']));
		$row = $Result->row();
		if($row->noOfEntries > 5){
				return false;
		}else{
				return true;
		}
	}
    function sendResponseMail($data){
        $this->load->library(array('alerts_client'));
        $mail_client = new Alerts_client();
        $subject = $this->mailSubject($data,$data['action']);
        $content = $this->load->view($data['mailViewName'],$data,true);
        $ccmail = 'response@shiksha.com';
        if(strlen($data['tomail'])>0){
            $data['tomail'] =$data['tomail'];
            $response=$mail_client->externalQueueAdd("12",ADMIN_EMAIL,$data['tomail'],$subject,$content,$contentType="html");
            $data['tomail'] =$ccmail;
            $response=$mail_client->externalQueueAdd("12",ADMIN_EMAIL,$data['tomail'],$subject,$content,$contentType="html");
        }
        else{
            $data['tomail'] =$ccmail;
            $response=$mail_client->externalQueueAdd("12",ADMIN_EMAIL,$data['tomail'],$subject,$content,$contentType="html");
        }
        return $response;
    }

    function getLeadsByClient($request)
    {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $clientId = $parameters['1'];
        $start=$parameters['2'];
        $count=$parameters['3'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = 'SELECT tempLMSTable.*, listing_title, tuser.displayname, tuser.email, tuser.mobile, tuser.city, (select CategoryId from tUserPref, tCourseSpecializationMapping where tCourseSpecializationMapping.SpecializationId=tUserPref.DesiredCourse and tUserPref.UserId = tuser.userid limit 1 ) as catOfInterest ,(select CountryId from tUserLocationPref where  tUserLocationPref.UserId = tuser.userid limit 1) as countryOfInterest from tuser, tempLMSTable , listings_main where listings_main.username = ? and listings_main.listing_type = tempLMSTable.listing_type and listings_main.listing_type_id = tempLMSTable.listing_type_id and tempLMSTable.listing_subscription_type="paid" and tuser.userid = tempLMSTable.userId and listings_main.status="live" order by tempLMSTable.submit_date desc  LIMIT '.$start.', '.$count;
        log_message('debug', 'query cmd is ' . $queryCmd);
        error_log_shiksha("LMS".$queryCmd);
        $query = $dbHandle->query($queryCmd, array($clientId));
        $crsToIns = array();
        $admToIns = array();
        $crsString = "-1";
        $admString = "-1";
        foreach ($query->result() as $row){
            switch($row->listing_type){
                case 'scholarship':
                case 'institute':
                    break;
                case 'course':
                    $crsString .=",".$row->listing_type_id;
                    break;
                case 'notification':
                    $admString .=",".$row->listing_type_id;
                    break;
            }
        }
        $queryCmdCrs = 'select course_id , institute_name from institute, institute_courses_mapping_table where institute_courses_mapping_table.course_id in ('.$crsString.') and institute.institute_id=institute_courses_mapping_table.institute_id';
        error_log_shiksha("LMS".$queryCmdCrs);
        $queryTemp = $dbHandle->query($queryCmdCrs);
        foreach ($queryTemp->result() as $rowTemp){
            $crsToIns[$rowTemp->course_id] = $rowTemp->institute_name;
        }

        $queryCmdAdm = 'select admission_notification_id, institute_name from institute, institute_examinations_mapping_table where institute_examinations_mapping_table.admission_notification_id in ('.$admString.')  and institute.institute_id=institute_examinations_mapping_table.institute_id';
        error_log_shiksha("LMS".$queryCmdAdm);
        $queryTemp = $dbHandle->query($queryCmdAdm);
        foreach ($queryTemp->result() as $rowTemp){
            $admToIns[$rowTemp->admission_notification_id] = $rowTemp->institute_name;
        }


        $msgArray = array();
        foreach ($query->result() as $row){
            $cityName = $row->city;
            if(is_numeric($row->city))
            {
                $cityName = $this->cacheLibObj->get("city_".$row->city);
            }
            $listing_title = $row->listing_title;
            switch($row->listing_type){
                case 'scholarship':
                case 'institute':
                    $listing_title = $row->listing_title;
                    $institute_name = "-";
                    break;
                case 'course':
                    $listing_title = $row->listing_title;
                    $institute_name = $crsToIns[$row->listing_type_id];
                    break;
                case 'notification':
                    $listing_title = $row->listing_title;
                    $institute_name = $admToIns[$row->listing_type_id];
                    break;
            }
            $url = getSeoUrl($row->listing_type_id,$row->listing_type,$row->listing_title,$optionalArgs);
            array_push($msgArray,array(
                            'displayName'=>$row->displayName,
                            'email'=>$row->email,
                            'contact_cell'=>$row->contact_cell,
                            'listing_title'=>$listing_title,
                            'institute_name'=>$institute_name,
                            'listing_type'=>$row->listing_type,
                            'listing_type_id'=>$row->listing_type_id,
                            'query'=>$row->message,
                            'url'=>$url,
                            'city'=>$cityName,
                            'action'=>$row->action,
                            'submit_date'=>$row->submit_date,
                            ));//close array_push
        }
        //error_log_shiksha(print_r($msgArray,true));
        $response = array(json_encode($msgArray),'string');
        return $this->xmlrpc->send_response($response);
    }

 function getLeadsByListing($request)
    {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $listing_type = $parameters['1'];
        $listing_type_id = $parameters['2'];
        $start=(int)$parameters['3'];
        $count=(int)$parameters['4'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

        $optionalArgs = array();
        $addType = '';
        $addTypeId = '';
        if(is_numeric($listing_type_id)){
            $addTypeId = ' and tempLMSTable.listing_type_id = '.$dbHandle->escape($listing_type_id).' ';
        }
        $listing_type = strtolower($listing_type);
        if($listing_type == 'course' || $listing_type == 'institute' || $listing_type == 'scholarship' || $listing_type == 'notification' || $listing_type == 'consultant' ){
            $addType = ' and tempLMSTable.listing_type = '.$dbHandle->escape($listing_type).' ';
        }
        $queryCmd = "SELECT tempLMSTable.*, listing_title , tuser.displayname, tuser.email, tuser.mobile, tuser.city, (select CategoryId from tUserPref, tCourseSpecializationMapping where tCourseSpecializationMapping.SpecializationId=tUserPref.DesiredCourse and tUserPref.UserId = tuser.userid  limit 1 ) as catOfInterest ,(select CountryId from tUserLocationPref where  tUserLocationPref.UserId = tuser.userid limit 1) as countryOfInterest from tuser, tempLMSTable , listings_main where  listings_main.listing_type = tempLMSTable.listing_type and listings_main.listing_type_id = tempLMSTable.listing_type_id and listings_main.status='live' and tempLMSTable.listing_subscription_type='paid' and tuser.userid = tempLMSTable.userId  $addType  $addTypeId  order by tempLMSTable.submit_date desc LIMIT $start, $count";
        error_log_shiksha("LMS".$queryCmd);
        $query = $dbHandle->query($queryCmd);
        $crsToIns = array();
        $admToIns = array();
        $crsString = "-1";
        $admString = "-1";
        foreach ($query->result() as $row){
            switch($row->listing_type){
                case 'scholarship':
                case 'institute':
                    break;
                case 'course':
                    $crsString .=",".$row->listing_type_id;
                    break;
                case 'notification':
                    $admString .=",".$row->listing_type_id;
                    break;
            }
        }
        $queryCmdCrs = 'select course_id , institute_name from institute, institute_courses_mapping_table where institute_courses_mapping_table.course_id in ('.$crsString.') and institute.institute_id=institute_courses_mapping_table.institute_id';
        error_log_shiksha("LMS".$queryCmdCrs);
        $queryTemp = $dbHandle->query($queryCmdCrs);
        foreach ($queryTemp->result() as $rowTemp){
            $crsToIns[$rowTemp->course_id] = $rowTemp->institute_name;
        }

        $queryCmdAdm = 'select admission_notification_id, institute_name from institute, institute_examinations_mapping_table where institute_examinations_mapping_table.admission_notification_id in ('.$admString.')  and institute.institute_id=institute_examinations_mapping_table.institute_id';
        error_log_shiksha("LMS".$queryCmdAdm);
        $queryTemp = $dbHandle->query($queryCmdAdm);
        foreach ($queryTemp->result() as $rowTemp){
            $admToIns[$rowTemp->admission_notification_id] = $rowTemp->institute_name;
        }

        $msgArray = array();
        foreach ($query->result() as $row){
            $cityName = $row->city;
            if(is_numeric($row->city))
            {
                $cityName = $this->cacheLibObj->get("city_".$row->city);
            }
            $listing_title = $row->listing_title;
            $institute_name = "";
            switch($row->listing_type){
                case 'scholarship':
                case 'institute':
                    $listing_title = $row->listing_title;
                    $institute_name = "-";
                    break;
                case 'course':
                    $listing_title = $row->listing_title;
                    $institute_name = $crsToIns[$row->listing_type_id];
                    break;
                case 'notification':
                    $listing_title = $row->listing_title;
                    $institute_name = $admToIns[$row->listing_type_id];
                    break;
            }
            $countries = explode(',',$row->countryOfInterest);
            for($i = 0; $i<count($countries); $i++){
                if(is_numeric($countries[$i]))
                {
                    $countries[$i] = $this->cacheLibObj->get('country_'.$countries[$i]);
                }
            }
            $countriesOfInterest = implode(',',$countries);

            $countries = explode(',',$row->catOfInterest);
            for($i = 0; $i<count($countries); $i++){
                if(is_numeric($countries[$i]))
                {
                    $countries[$i] = $this->cacheLibObj->get('catname_'.$countries[$i]);
                }
            }
            $categoriesOfInterest = implode(',',$countries);

            $url = getSeoUrl($row->listing_type_id,$row->listing_type,$row->listing_title,$optionalArgs);
            array_push($msgArray,array(
                            'displayName'=>$row->displayName,'string',
                            'email'=>$row->email,
                            'contact_cell'=>$row->contact_cell,
                            'listing_title'=>$listing_title,
                            'institute_name'=>$institute_name,
                            'listing_type'=>$row->listing_type,
                            'listing_type_id'=>$row->listing_type_id,
                            'query'=>$row->message,
                            'url'=>$url,
                            'city'=>$cityName,
                            'action'=>$row->action,
                            'categoriesOfInterest'=>$categoriesOfInterest,
                            'countriesOfInterest'=>$countriesOfInterest,
                            'submit_date'=>$row->submit_date,
                            ));//close array_push
        }
        //error_log_shiksha(print_r($msgArray,true));
        $response = array(json_encode($msgArray),'string');
        return $this->xmlrpc->send_response($response);
    }

 function getLeadsByListingCSV($request)
    {
        $parameters = $request->output_parameters();
        error_log_shiksha("LMS".print_r($parameters,true));
        $appId = $parameters['0'];
        $listing_type = $parameters['1'];
        $listing_type_id = $parameters['2'];
        $start=$parameters['3'];
        $count=$parameters['4'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $addType = '';
        $addTypeId = '';
        if(is_numeric($listing_type_id)){
            $addTypeId = ' and tempLMSTable.listing_type_id = '.$dbHandle->escape($listing_type_id).' ';
        }
        $listing_type = strtolower($listing_type);
        if($listing_type == 'course' || $listing_type == 'institute' || $listing_type == 'scholarship' || $listing_type == 'notification'){
            $addType = ' and tempLMSTable.listing_type = '.$dbHandle->escape($listing_type).' ';
        }
        $queryCmd = 'SELECT tuser.displayName, tuser.email, tempLMSTable.contact_cell, listing_title, listings_main.listing_type_id, listings_main.listing_type, city, action, tempLMSTable.submit_date , (select CategoryId from tCourseSpecializationMapping, tUserPref where tCourseSpecializationMapping.SpecializationId=tUserPref.DesiredCourse and tUserPref.UserId = tuser.userid limit 1 ) as catOfInterest ,(select CountryId from tUserLocationPref where  tUserLocationPref.UserId = tuser.userid limit 1) as countryOfInterest from tuser, tempLMSTable , listings_main where  listings_main.listing_type = tempLMSTable.listing_type and listings_main.listing_type_id = tempLMSTable.listing_type_id and listings_main.status="live" and tempLMSTable.listing_subscription_type="paid" and tuser.userid = tempLMSTable.userId '.$addTypeId.' '.$addType.' order by tempLMSTable.submit_date desc  LIMIT '.(int)$start.', '.(int)$count;
        error_log_shiksha("LMS".$queryCmd);
        $query = $dbHandle->query($queryCmd);
        $csv  = $this->dbutil->csv_from_result($query);
        error_log_shiksha($csv);

        $msgArray = array($csv,'string');
        /*$out ='';
        $columns = mysql_num_fields($fields);
        // Put the name of all fields
        for ($i = 0; $i < $columns; $i++) {
            $l=mysql_field_name($fields, $i);
            $out .= '"'.$l.'",';
        }
        $out .="\n";*/
        //error_log_shiksha(print_r($msgArray,true));
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getResponsesForClientId($request)
    {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $clientId = $parameters['1'];
        $start=$parameters['2'];
        $count=$parameters['3'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = 'SELECT tempLMSTable.*, listing_title, tuser.displayname, tuser.email, tuser.mobile, tuser.city, (select CategoryId from tUserPref, tCourseSpecializationMapping where tCourseSpecializationMapping.SpecializationId=tUserPref.DesiredCourse and tUserPref.UserId = tuser.userid limit 1 ) as catOfInterest ,(select CountryId from tUserLocationPref where  tUserLocationPref.UserId = tuser.userid limit 1) as countryOfInterest from tuser, tempLMSTable , listings_main where listings_main.username = ? and listings_main.listing_type = tempLMSTable.listing_type and listings_main.listing_type_id = tempLMSTable.listing_type_id and tempLMSTable.listing_subscription_type="paid" and tuser.userid = tempLMSTable.userId and listings_main.status="live" order by tempLMSTable.submit_date desc  LIMIT '.(int)$start.', '.(int)$count;
        log_message('debug', 'query cmd is ' . $queryCmd);
        error_log_shiksha("LMS".$queryCmd);
        $query = $dbHandle->query($queryCmd, array($clientId));
        $crsToIns = array();
        $admToIns = array();
        $crsString = "-1";
        $admString = "-1";

        $this->load->library('cacheLib');
        $this->cacheLibObj = new cacheLib();

        foreach ($query->result() as $row){
            switch($row->listing_type){
                case 'scholarship':
                case 'institute':
                    break;
                case 'course':
                    $crsString .=",".$row->listing_type_id;
                    break;
                case 'notification':
                    $admString .=",".$row->listing_type_id;
                    break;
            }
        }
        $queryCmdCrs = 'select course_id , institute_name from institute, institute_courses_mapping_table where institute_courses_mapping_table.course_id in ('.$crsString.') and institute.institute_id=institute_courses_mapping_table.institute_id';
        error_log_shiksha("LMS".$queryCmdCrs);
        $queryTemp = $dbHandle->query($queryCmdCrs);
        foreach ($queryTemp->result() as $rowTemp){
            $crsToIns[$rowTemp->course_id] = $rowTemp->institute_name;
        }

        $queryCmdAdm = 'select admission_notification_id, institute_name from institute, institute_examinations_mapping_table where institute_examinations_mapping_table.admission_notification_id in ('.$admString.')  and institute.institute_id=institute_examinations_mapping_table.institute_id';
        error_log_shiksha("LMS".$queryCmdAdm);
        $queryTemp = $dbHandle->query($queryCmdAdm);
        foreach ($queryTemp->result() as $rowTemp){
            $admToIns[$rowTemp->admission_notification_id] = $rowTemp->institute_name;
        }


        $msgArray = array();
        foreach ($query->result() as $row){
            $cityName = $row->city;
            if(is_numeric($row->city))
            {
                $cityName = $this->cacheLibObj->get("city_".$row->city);
            }
            $listing_title = $row->listing_title;
            switch($row->listing_type){
                case 'scholarship':
                case 'institute':
                    $listing_title = $row->listing_title;
                    $institute_name = "-";
                    break;
                case 'course':
                    $listing_title = $row->listing_title;
                    $institute_name = $crsToIns[$row->listing_type_id];
                    break;
                case 'notification':
                    $listing_title = $row->listing_title;
                    $institute_name = $admToIns[$row->listing_type_id];
                    break;
            }
            $url = getSeoUrl($row->listing_type_id,$row->listing_type,$row->listing_title,$optionalArgs);
            array_push($msgArray,array(
                            'displayName'=>$row->displayName,
                            'email'=>$row->email,
                            'contact_cell'=>$row->contact_cell,
                            'listing_title'=>$listing_title,
                            'institute_name'=>$institute_name,
                            'listing_type'=>$row->listing_type,
                            'listing_type_id'=>$row->listing_type_id,
                            'query'=>$row->message,
                            'url'=>$url,
                            'city'=>$cityName,
                            'action'=>$row->action,
                            'submit_date'=>$row->submit_date,
                            ));//close array_push
        }
        //error_log_shiksha(print_r($msgArray,true));
        $response = array(json_encode($msgArray),'string');
        return $this->xmlrpc->send_response($response);
    }

    function sgetInstituteResponseCountForClientId($request) {
	    global $listings_with_localities;
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $clientId = $parameters['1'];
        $tabStatus=$parameters['2'];
        error_log("###tabStatus".$tabStatus);
        if($tabStatus == 'live') {
            $statusClause = " AND status = 'live' ";
            //$statusClauseConditional = " AND status = 'live' ";
            $lmStatusClause = " AND lm.status = 'live' ";
        } else if($tabStatus == 'deleted') {
            $statusClause = " AND status in ( 'live' , 'deleted' ) ";
            //$statusClauseConditional = " AND CASE listing_type WHEN 'institute' THEN status in ( 'live' , 'deleted' ) ELSE status = 'deleted' END ";
            $lmStatusClause = " AND lm.status in ( 'live' , 'deleted' ) ";
        }
	
	//connect DB
	$dbHandle = $this->_loadDatabaseHandle('read','MISTracking');
	
	//Stop UG responses for study abroad
	$instituteIds = array();
	$queryCmd = "SELECT listing_type, listing_type_id, listing_title, status FROM listings_main WHERE username = ? ".$statusClause;

    $query = $dbHandle->query($queryCmd, array($clientId));
    error_log("###query 1 in getInstituteResponseCountForClientId ".$dbHandle->last_query());
    if($tabStatus == 'live'){
	   foreach($query->result_array() as $row) {
            if($row['listing_type'] == 'institute' || $row['listing_type'] == 'university_national'){
                $instituteIds[] = $row['listing_type_id'];
            } 
            else if($row['listing_type'] == 'course'){
                $courseIds[] = $row['listing_type_id'];
            }
            if($row['listing_type'] == 'university_national') {
				$listingTitles[$row['listing_type_id']] = $row['listing_title']."(University)";
			} else {
				$listingTitles[$row['listing_type_id']] = $row['listing_title'];	
			}
            
        }
    }
    else{
        foreach($query->result_array() as $row) {
            if($row['listing_type'] == 'institute' || $row['listing_type'] == 'university_national'){
                $instituteIds[] = $row['listing_type_id'];
                if($row['status'] == 'deleted'){
                    $deletedInstitutes[] = $row['listing_type_id'];
                }
            } 
            else if($row['listing_type'] == 'course' && $row['status'] == 'deleted'){
                $courseIds[] = $row['listing_type_id'];
            }
            if($row['listing_type'] == 'university_national') {
				$listingTitles[$row['listing_type_id']] = $row['listing_title']."(University)";
			} else {
				$listingTitles[$row['listing_type_id']] = $row['listing_title'];
			}
        }
	}
	
	// not working now ---- 
	$responseExclusionIds = $this->getResponseExclusionListForStudyAboard('institute', $instituteIds, $tabStatus);
	
	$exclusionClause = "";
	if(count($responseExclusionIds)) {
		//$exclusionClause = "and t.id NOT IN (".implode(',',$responseExclusionIds).")";
        $exclusionClause = "and tlms.id NOT IN (".implode(',',$responseExclusionIds).")";
	}
	

    /** Code written to optimize Response Viewer Query **/
    if($tabStatus == 'live') {
        $institutes = $instituteIds;
    } 
    else {
        $institutes = $deletedInstitutes;
    }

    $dbHandle = $this->_loadDatabaseHandle('read','MISTracking');
    if($clientId == '271920'){
        $queryCourseWiseResponseCount = "SELECT count(*) totalResponses, tlms.listing_type_id, tlms.listing_type,
                                        IFNULL(rlt.instituteLocationId,1) as locationId FROM tempLMSTable tlms 
                                        LEFT JOIN responseLocationTable rlt ON rlt.responseId = tlms.id
                                        WHERE  tlms.listing_type = 'course' AND tlms.submit_date > '2017-06-01 00:00:00' AND tlms.listing_type_id in (270192,270193,270200,228323,270675,202694,270664,270800,228243,270496,168148,228322,229124,228955,229678,229110,229120,230268,280629,230395,229172,229677,271229,233980,234186,271127,271102,283590,230384,229147,229129,233880,234175,271220,230381,271078,229264,229562,270087,229084,283559,279943,229973,230108,234223,202466,202383,202688,202650,168131,202595,202441,230349,230310,234169,229176,229152,229215,229174,229182,229186,229028,229078,168132,227950,227949,229272,229270,229220,229298,229299,229292,229538,229548,202689,228041,228043,228037,228046,228056,228075,228221,202715,270900,271017,202712,230149,230127,228558,228562,228556,205296,228551,228548,168331,270996,271001,270988,270978,271004,270993,229554,229567,229247,271250,271282,229243,230318,230313,229682,229683,230404,229262,229681,229679,229118,271276,271277,271263,271255,271280,271274,248007,248008,248004,228304,270847,234071,168145,168146,203487,270778,228311,270796,228316,270802,228309,228308,168239,270744,248774,248776,248773,229655,271236,233965,229080,229082,229261,271208,229669,271219,229674,271223,229310,229308,229098,271190,228245,228244,228242,228241,228040,228044,228047,228051,229976,229975,168237,229977,229974,202657,202704,202680,234222,234203,130505,229623,229620,229619,229615,229295,229364,229539,229545,230401,230352,229094,230353,230351,229189,229242,229217,234134,234125,229008,130514,270707,270704,270685,130511,130510,202421,248086,203482,203479,234067,168162,228227,228240,229021,271176,271174,271149,229017,229009,229171,248383,229256,229255,229254,233911,229089,229590,230379,233851,234035,270660,270650,234017,234066,168244,202411,270653,229969,229971,168397,202565,230063,230398,234063,248090,202432,228257,270648,168317,168314,168288,233857,271124,271119,233848,233905,229099,229160,271122,230178,229126,229179,230179,168157,233897,233963,229173,229630,271112,229116,229115,229125,228030,230064,228010,228011,228029,270573,202467,168281,228201,228061,202549,202606,202602,168151,168150,168427,229978,229282,230180,229273,229274,229277,271095,229177,229103,229557,229552,229178,229218,168158,229087,229086,229130,230355,228216,230125,270536,229985,234074,230312,271082,233974,229651,229105,229142,229138,234081,168361,130504,283587,299051,299050,299049,299048,299047,299046,298995,298993,298992,298989,298988,298983,298977,298976,298961,298951,298928,298920,298901,248670,248669,248667,248683,270097,229057,230269,230267,270058,229140,229141,229139,248658,248665,234049,234232,234240,168409,202174,202426,228292,248074,283558,247667,168287,168310,227802,270588,202076,202063,247648,247646,270088,247633,247630,270096,168068,230101,230066,270059,203477,234026,202066,202068,202065,230078,247632,247631,248002,280630,270822,202586,234195,202591,168309,168064,168083,202148,248077,202153,167558,274799,270301,228072,228317,228312,228222,227948,202693,202683,228291,270314,270306,247687,279423,248073,247684,247683,228289,234218,202690,270563,270895,228223,228080,228214,229180,234098,229181,229107,229037,168159,229032,248725,229034,229026,274801,270302,202149,229033,270313,229027,167564,270305,248513,279422,248721,248505,248504,229553,229675,229672,229570,229267,229227,229211,229648,229642,234164,229221,271088,271249,229573,229555,229561,234178,234237,271194,270767,167853,229056,202087,229145,271140,270676,202737,229253,279944,230177,229067,168088,230397,229979,202130,229030,229035,227805,247669,248487,202126,229029,234047,233876,233871,234045,271251,270951,280931,280930,271206,270776,228238,229610,279745,233982,234084,168473,229136,168494,229137,229072,168123,279342,234058,233893,228281,229637,229559,228202,228023,229275,229294,228038,271133,270671,248648,248748,168147,229083,233842,234002,168076,229059,168074,229058,202727,229251) AND tlms.action != 'marketingPage' 
                                         AND tlms.listing_subscription_type='paid' ".$exclusionClause."
                                        GROUP BY tlms.listing_type_id, locationId ";

        $queryCourse = $dbHandle->query($queryCourseWiseResponseCount);
    } else if($clientId == '4125690'){
        $queryCourseWiseResponseCount = "SELECT count(*) totalResponses, tlms.listing_type_id, tlms.listing_type,
                                        IFNULL(rlt.instituteLocationId,1) as locationId FROM tempLMSTable tlms 
                                        LEFT JOIN responseLocationTable rlt ON rlt.responseId = tlms.id
                                        WHERE  tlms.listing_type = 'course' AND tlms.submit_date > '2017-06-01 00:00:00' AND tlms.listing_type_id in (
                                        242638,242639,242640,242643,242644,242648,242649,242652,242653,242654,242655,242656,249940,249941,248988,166068,292095,292112,292109,292141,292101,259545,259542,259540,259554,259546,119265,299677,249197,249199,249201,241027,214578,168823,256427,256423,273113,273116,273120,273125,256424,256425,256426,119263,202913,119264,299673,119248,299674,119261,299675,229857,249956,249982,274890,249954,274907,227672,249977,249945,303867,152353,248979,248656,248650,303941,303945,229854,248651,248657,249950,249988,249986,249980,249943,249935,304017,304019,304021,304025,249990,249948,249938,249989,249987,249951,249971,249942,249936,304009,304007,304013,304015,249991,249949,249985,249983,249946,249976,249939,229707,249984,248989,229705,274795,229708,229858,249972,249981,248987,232009,248971,229856,229706,165176,15961,241774,241766,241770,241768,241769,164869,256219,267994,256222,256221,256220,165308,159822,152335,1916,1909,267641,110720,110723,164787,241674,164646,227320,227324,165313,227323,321599,193666,241771,241767,113276,164878,13280,165305,165302,165311,165307,85234,241735,267708,215836,242744,259527,141383,292041,292066,242275,242261,119358,292045,292103,242274,242273,259538,242262,259531,292100,242279,259539,141387,292136,291905,141389,119352,292110,291988,259552,141390,242276,292113,291989,292138,292062,141388,126573,141292,119354,141391,292108,292111,242278,141380,141381,141382,119355,141392,119356,141393,292098,292097,292046,292093,292056,141397,292054,292105,119360,141398,119361,141386,292107,119350,119351,119353,242268,119815,141385,141293,141378,292094,1902,292065,259551,292106,259549,292096,259550,293162,34291,320673,166141,130046,283668,321167,168672) AND tlms.listing_subscription_type='paid' ".$exclusionClause."
                                        GROUP BY tlms.listing_type_id, locationId ";

        $queryCourse = $dbHandle->query($queryCourseWiseResponseCount);

    }else{
        $queryCourseWiseResponseCount = "SELECT count(*) totalResponses, tlms.listing_type_id, tlms.listing_type, lm.listing_title, 
                                        IFNULL(rlt.instituteLocationId,1) as locationId FROM tempLMSTable tlms 
                                        LEFT JOIN listings_main lm ON tlms.listing_type_id = lm.listing_type_id 
                                        AND tlms.listing_type = lm.listing_type 
                                        LEFT JOIN responseLocationTable rlt ON rlt.responseId = tlms.id
                                        LEFT JOIN tuserflag tuf ON tuf.userId = tlms.userId
                                        WHERE lm.username = ? AND lm.status = ? AND tlms.action != 'marketingPage' 
                                        AND tuf.isTestUser = 'NO' AND tlms.listing_subscription_type='paid' ".$exclusionClause."
                                        GROUP BY tlms.listing_type_id, locationId ";

        $queryCourse = $dbHandle->query($queryCourseWiseResponseCount,array($clientId,$tabStatus));
                
    }
    error_log("###queryCourseWiseResponseCount ".$dbHandle->last_query());
    

    $queryCourseInstituteDetails = "SELECT cd.course_id, cd.institute_id FROM listings_main lm 
                                    LEFT JOIN course_details cd ON lm.listing_type_id = cd.course_id AND lm.status = cd.status 
                                    WHERE lm.listing_type='course' AND lm.username = ? AND lm.status = ?  
                                    UNION
                                    SELECT cd.course_id, cd.primary_id as institute_id FROM listings_main lm 
                                    LEFT JOIN shiksha_courses cd ON lm.listing_type_id = cd.course_id AND lm.status = cd.status 
                                    WHERE lm.listing_type='course' AND lm.username = ? AND lm.status = ?";
                                    
    /*$queryCourseInstituteDetails = "SELECT cd.course_id, cd.primary_id as institute_id FROM listings_main lm 
                                    LEFT JOIN shiksha_courses cd ON lm.listing_type_id = cd.course_id AND lm.status = cd.status 
                                    WHERE lm.listing_type='course' AND lm.username = ? AND lm.status = ?";*/                                
                                    
                                    

    $queryInstitute = $dbHandle->query($queryCourseInstituteDetails,array($clientId,$tabStatus,$clientId,$tabStatus));
    error_log("###queryCourseInstituteDetails ".$dbHandle->last_query());
    
    $instituteIdsArray = array();
    foreach ($queryInstitute->result_array() as $row) {
        $instituteCoursesMapping[$row['course_id']] = $row['institute_id'];
        if(!in_array($row['institute_id'], $instituteIdsArray))
            $instituteIdsArray[] = $row['institute_id'];
    }
    $courseIdsArray = array_keys($instituteCoursesMapping);
    
    $queryResultArray = array(); 
    $i=0;
    foreach($queryCourse->result_array() as $row1) {
        
        if(in_array($row1['listing_type_id'], $instituteIdsArray) && $row1['listing_type'] == 'institute') {
            $queryResultArray[$row1['listing_type_id']][$row1['locationId']]['totalResponses'] += $row1['totalResponses'];
            $queryResultArray[$row1['listing_type_id']][$row1['locationId']]['institute_id'] = $row1['listing_type_id'];
            $queryResultArray[$row1['listing_type_id']][$row1['locationId']]['locationId'] = $row1['locationId'];
            $queryResultArray[$row1['listing_type_id']][$row1['locationId']]['listing_title'] = $listingTitles[$row1['listing_type_id']];

        } 
        if(in_array($row1['listing_type_id'], $courseIdsArray) && $row1['listing_type'] == 'course') {
            $inst_id = $instituteCoursesMapping[$row1['listing_type_id']];
            $queryResultArray[$inst_id][$row1['locationId']]['totalResponses'] += $row1['totalResponses'];
            $queryResultArray[$inst_id][$row1['locationId']]['institute_id'] = $inst_id;
            $queryResultArray[$inst_id][$row1['locationId']]['locationId'] = $row1['locationId'];
            $queryResultArray[$inst_id][$row1['locationId']]['listing_title'] = $listingTitles[$inst_id];
        }
        
        $i++;
    }  

    $finalArray = array();
    $i=0;
    foreach ($queryResultArray as $instituteId => $details) {
        foreach ($details as $key => $responseValues) {
            $midArray['totalResponses'] = $responseValues['totalResponses'];
            $midArray['institute_id'] = $responseValues['institute_id'];
            $midArray['listing_title'] = $responseValues['listing_title'];
            $midArray['locationId'] = $responseValues['locationId'];
            $finalArray[$i] = $midArray;
            $i++;
        }   
    }

    
    	/*
		 * Get response count grouped by institute and location
		 */ 

// 		$queryCmd = "select total totalResponses,inst institute_id,listing_title,IFNULL(z.locationId,1) as locationId
// 		from (
// 				select sum(num) total,if(cd.course_id is null,y.listing_type_id,cd.institute_id) inst,IFNULL(y.locationId,1) as locationId
// 				from (
// 						select count(*) num,t.listing_type_id,t.listing_type,IFNULL(rlt.instituteLocationId,1) as locationId
// 						from tempLMSTable t
// 						inner join 
// 						(select DISTINCT listing_type,listing_type_id from listings_main where username = ?  AND status = '".$tabStatus."') x
// 						on (t.listing_type = x.listing_type and t.listing_type_id = x.listing_type_id)
// 						left join responseLocationTable rlt ON (rlt.responseId = t.id)
// 						inner join tuserflag tf ON tf.userId = t.userId
// 						where t.action != 'marketingPage' and tf.isTestUser = 'NO' and t.listing_subscription_type='paid' ".$exclusionClause."
// 						group by t.listing_type_id,t.listing_type,locationId
// 				) y
// 				left join course_details cd on y.listing_type_id = cd.course_id and y.listing_type = 'course' and cd.status = '".$tabStatus."' group by inst,locationId
// 		) z
// 		INNER JOIN listings_main lm ON (z.inst = lm.listing_type_id and lm.listing_type = 'institute' ". $lmStatusClause ." )";

//         error_log("LMS :: RESPONSE VIEWER : ASHISH :: ". $queryCmd);
//         $query = $dbHandle->query($queryCmd, array($clientId));
// 	    error_log("###query 2 in getInstituteResponseCountForClientId ".$dbHandle->last_query());
        
	/**
	 * Extract unique locations and listing ids present in result set
	 */

	$instituteLocationIds = array();
	$instituteIds = array();
	// foreach ($query->result_array() as $row) {
	// 	if($row['locationId'] > 1 && !isset($instituteLocationIds[$row['locationId']])) {
	// 		$instituteLocationIds[$row['locationId']] = TRUE;
	// 	}
	// 	$instituteIds[] = $row['institute_id'];
	// }

    foreach ($finalArray as $row) {
        if($row['locationId'] > 1 && !isset($instituteLocationIds[$row['locationId']])) {
            $instituteLocationIds[$row['locationId']] = TRUE;
        }
        $instituteIds[] = $row['institute_id'];
    }
	
	/**
	***Check for institute to university mapping
	**/
	$instituteToUniversityMapping = array();
	$universityLocationIds = array();
	if(count($instituteIds)) {
		$queryCmd = "select DISTINCT ium.institute_id,
				    ium.university_id,
				    lm.listing_title,
				    ult.university_location_id,
				    cct.city_name
			     from institute_university_mapping ium
			     inner join listings_main lm
			     on ium.university_id = lm.listing_type_id
			     and lm.listing_type = 'university'
			     $lmStatusClause
			     inner join university_location_table ult
			     on ium.university_id = ult.university_id
			     and ult.status = lm.status
			     inner join countryCityTable cct
			     on ult.city_id = cct.city_id
			     where ium.institute_id IN (".implode(',',$instituteIds).")
			     and ium.status = lm.status ";
		$universityQuery = $dbHandle->query($queryCmd);
		error_log("###query 3 in getInstituteResponseCountForClientId ".$dbHandle->last_query());
		foreach ($universityQuery->result_array() as $row) {		
			$instituteToUniversityMapping[$row['institute_id']] = array(
										    'id' => $row['university_id'],
										    'name' => $row['listing_title'],
										    'location' => $row['university_location_id'],
										    'city' => $row['city_name']
										);
			if($row['university_location_id'] > 1 && !isset($universityLocationIds[$row['university_location_id']])) {
				$universityLocationIds[$row['university_location_id']] = TRUE;
			}
		}
	}
	
	
	/**
	 * Create location map for locations present in result set
	 */ 
	$locationMap = array();	
	if(count($instituteLocationIds)) {
		
		/*$queryCmd = "SELECT ilt.institute_location_id,cct.city_name,lcm.localityName
		FROM institute_location_table ilt
		LEFT JOIN countryCityTable cct ON cct.city_id = ilt.city_id
		LEFT JOIN localityCityMapping lcm ON lcm.localityId = ilt.locality_id
		WHERE institute_location_id IN (".implode(',',array_keys($instituteLocationIds)).") 
		UNION 
		SELECT ilt.listing_location_id as institute_location_id,cct.city_name,lcm.localityName
		FROM shiksha_institutes_locations ilt
		LEFT JOIN countryCityTable cct ON cct.city_id = ilt.city_id
		LEFT JOIN localityCityMapping lcm ON lcm.localityId = ilt.locality_id
		WHERE listing_location_id IN (".implode(',',array_keys($instituteLocationIds)).")";*/
		
		$queryCmd = "SELECT ilt.listing_location_id as institute_location_id,cct.city_name,lcm.localityName
					FROM shiksha_institutes_locations ilt
					LEFT JOIN countryCityTable cct ON cct.city_id = ilt.city_id
					LEFT JOIN localityCityMapping lcm ON lcm.localityId = ilt.locality_id
					WHERE listing_location_id IN (".implode(',',array_keys($instituteLocationIds)).")";
		
		$locationQuery = $dbHandle->query($queryCmd);
		foreach($locationQuery->result_array() as $row) {
			$locationMap[$row['institute_location_id']] = array($row['city_name'],$row['localityName']);
		}
	}
	
	/**
	 * Response export pref
	 */ 
	$listingTypeClause = "";
	if(count($universityLocationIds) && count($instituteLocationIds)) {
		$listingTypeClause = "(listingType = 'university' AND listingLocationId IN (1,".implode(',',array_keys($universityLocationIds)).")) OR (listingType in ('institute','university_national') AND listingLocationId IN (1,".implode(',',array_keys($instituteLocationIds))."))";
	}
	else if(count($universityLocationIds)) {
		$listingTypeClause = "listingType = 'university' AND listingLocationId IN (1,".implode(',',array_keys($universityLocationIds)).")";
	}
	else if(count($instituteLocationIds)) {
		$listingTypeClause = "listingType in ('institute','university_national') AND listingLocationId IN (1,".implode(',',array_keys($instituteLocationIds)).")";
	}
	else {
		$listingTypeClause = "listingType in ('institute','university_national') AND listingLocationId = 1";
	}
	$queryCmd = "SELECT listingId,listingLocationId,emails FROM responseExportPref WHERE ".$listingTypeClause;
	error_log(" funny responseExportPref ".$queryCmd);
	$emailExportQuery = $dbHandle->query($queryCmd);
	foreach($emailExportQuery->result_array() as $row) {
		$emailExportMap[$row['listingId'].'_'.$row['listingLocationId']] = $row['emails'];
	}
	
	/**
	 * Institutes having custom locations (UTS, SMU etc)
	 */ 
	$customLocationInstitutes = array();
	foreach($listings_with_localities as $listings) {
		foreach($listings as $listing) {
			$customLocationInstitutes[$listing] = TRUE;
		}
	}
		
	$msgArray = array();
	$universityResponseCount = array();
        // foreach ($query->result_array() as $row) {
        foreach ($finalArray as $row) {
		/**
		 * Response without location - skip it
		 */
		if($row['locationId'] == 1 && !$customLocationInstitutes[$row['institute_id']]) {
			continue;
		}
		
		if($row['locationId'] > 1) {
			$row['city_name'] = $locationMap[$row['locationId']][0];
			$row['localityName'] = $locationMap[$row['locationId']][1];
		}
		
		$row['emailExport'] = $emailExportMap[$row['institute_id'].'_'.$row['locationId']];
		
		if(count($instituteToUniversityMapping)) {
			if(isset($instituteToUniversityMapping[$row['institute_id']])) {
				$row['listing_title'] = $instituteToUniversityMapping[$row['institute_id']]['name'];
				$row['university_id'] = $instituteToUniversityMapping[$row['institute_id']]['id'];
				$row['locationId'] = $instituteToUniversityMapping[$row['institute_id']]['location'];
				$row['city_name'] = $instituteToUniversityMapping[$row['institute_id']]['city'];
				$row['emailExport'] = $emailExportMap[$row['university_id'].'_'.$instituteToUniversityMapping[$row['institute_id']]['location']];
				$universityResponseCount[$row['university_id']] += $row['totalResponses'];
				unset($row['institute_id']);
				unset($row['localityName']);
			}
		}
		$msgArray[] =  $row;//close array_push
        }
	
	if(count($instituteToUniversityMapping)) {
		foreach($msgArray as $index => $row) {
			if(!empty($row['university_id'])) {
				if(!empty($universityResponseCount[$row['university_id']])) {
					$msgArray[$index]['totalResponses'] = $universityResponseCount[$row['university_id']];
					unset($universityResponseCount[$row['university_id']]);
				}
				else {
					unset($msgArray[$index]);
				}
			}
		}
	}
	
        $response = array(json_encode($msgArray),'string');
        return $this->xmlrpc->send_response($response);
    }


    function sgetResponsesForListingId($request) {
        $parameters     = $request->output_parameters();
        $appId          = $parameters['0'];
        $clientId       = $parameters['1'];
        $listingId      = $parameters['2'];
        $listingType    = $parameters['3'];
        $searchCriteria = $parameters['4'];
        $timeInterval   = $parameters['5'];
        $start          = $parameters['6'];
        $count          = $parameters['7'];
        $locationId     = $parameters['8'];
        $startDate      = $parameters['9'];
        $endDate        = $parameters['10'];
        $tabStatus      = $parameters['11'];
        $responseIds    = $parameters['12'];
        $responseIds    = json_decode($responseIds);
	
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('read','MISTracking');
	
	if($listingType == 'university') {
		$universityId = $listingId;
	}
        else if($listingType == 'course') {
		$courseIds = $listingId;
        }
	else if($listingType == 'institute') {
		$instituteId = $listingId;
        }
        
        if($timeInterval == 'none') {
		if(empty($startDate) && empty($endDate)) {
			$timeClause = ' AND tlmst.submit_date >= subdate(now(), DATEDIFF(NOW(), "2008-01-01")) '; //date before the start of shiksha
		}
		else {
			$dateClause = array();
			if(!empty($startDate)) {
				$dateClause[] = 'tlmst.submit_date >= "'.$startDate.' 00:00:00"';
			}
			if(!empty($endDate)) {
				$dateClause[] = 'tlmst.submit_date <= "'.$endDate.' 23:59:59"';
			}
			if(!empty($dateClause)) {
				$timeClause = " AND ".join(' AND ', $dateClause)." ";
			}
			else {
				$timeClause = " ";
			}
		}
        }
	else {
        	$timeClause = ' AND tlmst.submit_date >= subdate(now(), INTERVAL '. $timeInterval .') ';
        }
    $responseIdClause = '';
    if($responseIds){
        $responseIdClause = " and tlmst.id in (".implode(',',$responseIds).") ";
    }

	
	$locationClause = ($locationId > 1 ? 'AND rlt.instituteLocationId = "'.$locationId.'" ' : ' AND rlt.instituteLocationId IS NULL ');
	if($listingType == 'university') {
		$locationClause = '';
	}
	else if($searchCriteria == 'courseOnly') {
		$this->load->model('listing/coursemodel');
		$isAbroadCourse = $this->coursemodel->isStudyAboradListing($courseIds, 'course', $tabStatus);
		
		if($isAbroadCourse) {
			$locationClause = '';
		}
	}
	
        switch($searchCriteria) {
		case 'instituteOnly': $courseIds = "''"; break;
		case 'courseOnly': $instituteId = "''"; break;
		case 'both':
		default :
			if($listingType == 'institute') {
				$getCoursesQuery = 'SELECT course_id FROM shiksha_courses WHERE primary_id = ? and status= ? ';
				$getCoursesQueryResult = $dbHandle->query($getCoursesQuery, array($instituteId,$tabStatus));
    				foreach ($getCoursesQueryResult->result_array() as $row){
    				    $courseIdsArray[] = $row['course_id'];
				}
				$courseIds = join(',', $courseIdsArray);
				error_log('ASHISH : '. $getCoursesQuery .' : LMS::'. print_r($courseIds, true));
			}
			else if($listingType == 'university') {
				$this->load->model('listing/abroadcoursefindermodel');
				$courseIdsArray = $this->abroadcoursefindermodel->getCoursesOfferedByUniversity($universityId,'list',false,$tabStatus);
				$courseIdsArray = $courseIdsArray['course_ids'];
				$courseIds = join(',', $courseIdsArray);
				$instituteId = "''";
			}
			break;
        }
	
		//Stop UG responses for study abroad
		if($courseIds !== '') {
			$responseExclusionIds = $this->getResponseExclusionListForStudyAboard('course', explode(',',$courseIds), $tabStatus);
			
			$exclusionClause = "";
			if(count($responseExclusionIds)) {
				$exclusionClause = " and tlmst.id NOT IN (".implode(',',$responseExclusionIds).") ";
			}
		}
        
        if($courseIds != '') {
            
            $queryCmd = 'SELECT SQL_CALC_FOUND_ROWS tlmst.id, tlmst.userId, tlmst.action, tlmst.message, lm.listing_type, lm.listing_type_id,
                    lm.listing_title, DATE_FORMAT(tlmst.submit_date,"%d-%b-%Y %H:%i:%s") as submit_date
                		FROM tempLMSTable tlmst
                		INNER JOIN listings_main lm ON (tlmst.listing_type_id = lm.listing_type_id AND lm.listing_type = tlmst.listing_type AND lm.status = "'.$tabStatus.'")
                		LEFT JOIN responseLocationTable rlt ON (rlt.responseId = tlmst.id)
                		INNER JOIN tuserflag tf ON tf.userId = tlmst.userId
                		WHERE ((tlmst.listing_type="course" AND tlmst.listing_type_id IN ('. $courseIds .')) OR (tlmst.listing_type="institute" AND tlmst.listing_type_id  = '. $dbHandle->escape($instituteId) .'))
                		AND tlmst.action != "marketingPage" 
                                AND tlmst.listing_subscription_type = "paid" 
                		AND tf.isTestUser = "NO"'.
                		$timeClause.$exclusionClause.$responseIdClause.
                		'AND lm.username = '. $dbHandle->escape($clientId) .$locationClause. '
                		ORDER BY tlmst.submit_date DESC LIMIT '. $start .','. $count;
    		
    	
            error_log("LMS :: RESPONSE VIEWER : ASHISH :: ". $queryCmd);
            $query = $dbHandle->query($queryCmd);
            $msgArray = array();
            $userIds = '';
    	
            foreach ($query->result_array() as $row){
    			$msgArray['responses'][] = $row;
    			$userIds .= $row['userId'] .',';
            }

        }
	
        $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
        $msgArray['totalResponses'] = $totalRows;
	
        if(!empty($userIds)) {
				$appID = 1;
				$this->load->library(array('LDB_Client','MiscClient'));
				$ldbObj = new LDB_Client();
				$msgArray['userIdDetails'] = json_decode($ldbObj->sgetUserDetails($appID, trim($userIds,',')), true);
				$miscObj = new MiscClient();
				$msgArray['contactHistory'] = json_decode($miscObj->getCommunicationTracking($appID, $userIds, $clientId, true, ''), true);
        }
	
		$msgArray['userIds']=$userIds;
		$msgArray['courseIds']=$courseIds;
	
        $response = base64_encode(json_encode($msgArray));
	
        return $this->xmlrpc->send_response($response);
    }
    
    /*
     * Get all responses for free listings to retarget them
     */
    public function sgetAllResponsesForFreeCourses($request) {
        
        ini_set('memory_limit', '512M');
        $parameters = $request->output_parameters();
        $searchCriteria = $parameters['0'];
        $timeInterval = (int)$parameters['1'];
        $start = (int)$parameters['2'];
        $count = (int)$parameters['3'];
        $endDate = $parameters['4'];
        $catId = (int)$parameters['5'];
        $subCatId = (int)$parameters['6'];
        $ldbCourseId = (int)$parameters['7'];
        $preferredCity = (int)$parameters['8'];
        $email = $parameters['9'];
        
        $loggedInUserData = $this->getLoggedInUserData();
        $operatorId = $loggedInUserData['userId'];
        
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        
        // get all courses and their corresponding category name
        $courseMappingSql ='';
        if($searchCriteria == 'abroad'){
            
            $courseMappingSqlDefault = "SELECT course_id as courseId, a.city_id, name as categoryName FROM abroadCategoryPageData a left join categoryBoardTable b on a.category_id = b.boardId WHERE status ='live' ";
            if(isset($catId) && $catId !='' && $catId){
                $courseMappingSql .= "and b.boardId = ".$dbHandle->escape($catId);
                
                if(isset($subCatId) && $subCatId !='' && $subCatId){
                    $courseMappingSql .= "and a.sub_category_id = ".$dbHandle->escape($subCatId); 
                }
            }
        }
        else{
            $courseMappingSqlDefault = "SELECT a.course_id as courseId, a.city_id, c.name as categoryName FROM categoryPageData a inner join categoryBoardTable b on a.category_id = b.boardId left join categoryBoardTable c on b.parentId = c.boardId WHERE a.status ='live' ";
            
            if(isset($catId) && $catId !='' && $catId){
                $courseMappingSql .= "and c.boardId = ".$dbHandle->escape($catId);
                
                if(isset($subCatId) && $subCatId !='' && $subCatId){
                    $courseMappingSql .= "and a.category_id = ".$dbHandle->escape($subCatId); 
                    
                    if(isset($ldbCourseId) && $ldbCourseId !='' && $ldbCourseId){
                        $courseMappingSql .= "and a.ldb_course_id = ".$dbHandle->escape($ldbCourseId); 
                    }
                }
            }
            
            if($preferredCity && $preferredCity !== 0){
                $courseMappingSql .= "and a.city_id = ".$dbHandle->escape($preferredCity); 
            }
        }
        //echo $courseMappingSql;exit;
        if($courseMappingSql != '' && $email === '0'){
            $courseMappingSqlResult = $dbHandle->query($courseMappingSqlDefault.$courseMappingSql);
            $courseToCategoryMapping = array();
            $courseIdsArray = array();
            $num_rows = $courseMappingSqlResult->num_rows;
            foreach ($courseMappingSqlResult->result_array() as $result){
                $courseToCategoryMapping[$result['courseId']]['categoryName'] = $result['categoryName'];
                //$courseToCategoryMapping[$result['courseId']]['city_id'] = $result['city_id'];
                $courseIdsArray[$result['courseId']] = true;
            }
        }
        
        /**
         *  get data of all allotted users
         */
        $sql = "select userId, operatorId from offlineOperatorTracking where status = '0'";
        $sqlResult = $dbHandle->query($sql);
        
        $assignedToOtherUsersArray = array();
        $usersAssignedToMeArray = array();
        
        foreach ($sqlResult->result_array() as $result){
            if($result['operatorId'] != $operatorId){
                $assignedToOtherUsersArray[$result['userId']] = true;
            }else{
                $usersAssignedToMeArray[$result['userId']] = true;
            }
        }
        
        
        /* Main query builder - start */
        
        if($email === '0'){
            
            /**
             * additing condition for time interval 
             */
            $timeInterval = (int)$timeInterval;
            if(!$endDate){
                $date = DateTime::createFromFormat('Y-m-d', date("Y-m-d"));
                $date->modify('+1 day');
                $endDate = $date->format('Y-m-d');
                $date->modify('-'.$timeInterval.' days');
                $startDate = $date->format('Y-m-d');
                
            }else{
                $date = DateTime::createFromFormat('Y-m-d', $endDate);
                $date->modify('+1 day');
                $endDate = $date->format('Y-m-d');
                $date->modify('-'.$timeInterval.' days');
                $startDate = $date->format('Y-m-d');
                
            }
                
            if($searchCriteria == 'abroad'){
                $countryCheck = "and countryId != 2 ";
            }else{
                $countryCheck = "and (b.isLDBUser = 'NO' OR (b.isLDBUser = 'YES' and b.isResponseLead = 'YES')) and countryId = 2 ";
            }
                
                
            $queryMain = "select a.userId, a.courseId, a.action, a.countryId, a.modified_at as submit_date, c.mobile as contact_cell, c.email from latestUserResponseData a "
                    . "left join tuser c on a.userId = c.userid "
                    . "LEFT JOIN tuserflag b on a.userId = b.userId "
                    . ($searchCriteria == 'abroad'?"LEFT JOIN tUserPref d on a.userId = d.UserId ":"")
                    . "where a.listing_subscription_type = 'free' "
                    . "AND a.modified_at < ".$dbHandle->escape($endDate)." AND a.modified_at >= ".$dbHandle->escape($startDate)                    
                    //. "and (b.isLDBUser = 'NO' OR (b.isLDBUser = 'YES' and b.isResponseLead = 'YES')) "
                    . "$countryCheck"
                    . "and b.isTestUser = 'NO' and b.mobileverified = '1' "
                    //. ($searchCriteria == 'abroad'?"AND (d.DesiredCourse = 0 OR d.DesiredCourse is null) ":"")
                    . "order by a.modified_at desc ";
            
        }else{
            //case of emailId search only
            if($searchCriteria == 'abroad'){
                $countryCheck = "and countryId != 2 ";
            }else{
                $countryCheck = "and (b.isLDBUser = 'NO' OR (b.isLDBUser = 'YES' and b.isResponseLead = 'YES')) and countryId = 2 ";
            }
            
            $queryMain = "select a.userId, a.courseId, a.action, a.countryId, a.modified_at as submit_date, c.mobile as contact_cell, c.email from latestUserResponseData a "
                    . "left join tuser c on a.userId = c.userid "
                    . "LEFT JOIN tuserflag b on a.userId = b.userId "
                    . "where a.listing_subscription_type = 'free' "
                    . "and c.email = ".$dbHandle->escape(trim($email))." "
                    //. "and (b.isLDBUser = 'NO' OR (b.isLDBUser = 'YES' and b.isResponseLead = 'YES')) "
                    . "$countryCheck"
                    . "and b.isTestUser = 'NO' and b.mobileverified = '1' "
                    . "order by a.modified_at desc limit 1";
        }
        
        /* Main query builder - end */
        
        //error_log('####NKS'.$queryMain);echo $queryMain;exit;
        $query = $dbHandle->query($queryMain);
        $msgArray = array();
        $responseArray = array();
        $userIds = '';
        
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $courseRepository = $listingBuilder->getCourseRepository();
        $courseObjects = array();
        $doProcess = true;
        $i = 0;
	
        foreach ($query->result_array() as $row){
            
            //check if user is not assigned to other operator
            if($assignedToOtherUsersArray[$row['userId']]){
                continue;
            }
            
            //check if course is part of searched course
            if($courseMappingSql != '' && $email === '0'){
                if(!$courseIdsArray[$row['courseId']]){
                    continue;
                }
                $row['categoryName'] = $courseToCategoryMapping[$row['courseId']]['categoryName'];
            }
            
//            //check in case of city search, if city id is same as searched
//            if($preferredCity !== 0 && $searchCriteria != 'abroad' && $email === '0'){
//                $city_id = $courseToCategoryMapping[$row['courseId']]['city_id'];
//                if($city_id != $preferredCity){
//                    continue;
//                }
//            }
            
            //check if user is assigned to current operator
            if($usersAssignedToMeArray[$row['userId']]){
                $row['assigned'] = true;
            }
            
            if($doProcess){
                //load course object from repository
                if(empty($courseObjects[$row['courseId']])) {
                    $courseObj = $courseRepository->find($row['courseId']);
                    $courseObjects[$row['courseId']] = $courseObj;
                }

                // get listing tital
                $listing_title = $courseObjects[$row['courseId']]->getName();
                // get institute name
                $institute_name = $courseObjects[$row['courseId']]->getInstituteName();

                // get city name
                $city_name = $courseObjects[$row['courseId']]->getMainLocation()->getCity()->getName();

                
                if($searchCriteria == 'abroad'){
                        // get university name in case of SA
                        $universityName = $courseObjects[$row['courseId']]->getUniversityName();
                        $row['universityName'] = $universityName;
                    
                }

                //add additional fields required in frontend
                $row['city_name'] = $city_name;
                $row['listing_title'] = $listing_title;
                $row['institute_name'] = $institute_name;
                $maxLim = $start+$count;
                if($i >= $start && $i < $maxLim){
                    $responseArray[] = $row;
                }
                if($i >= $maxLim){
                    $doProcess = false;
                    //error_log('doprocess = false');
                }
                //error_log($row['courseId']);
            }
            $i++;
            
        }
        
        $totalRows = $i;//count($responseArray);
        
        if($courseMappingSql == '' && $i){
            $responseCourses = array();
            foreach($responseArray as $response){
                $responseCourses[] = $response['courseId'];
            }
            if($searchCriteria == 'abroad'){
                $responseMappingSql = "SELECT a.course_id as courseId, a.city_id, name as categoryName FROM abroadCategoryPageData a left join categoryBoardTable b on a.category_id = b.boardId WHERE status ='live' ";
            }
            else{
                $responseMappingSql = "SELECT a.course_id as courseId, a.city_id, c.name as categoryName FROM categoryPageData a inner join categoryBoardTable b on a.category_id = b.boardId left join categoryBoardTable c on b.parentId = c.boardId WHERE a.status ='live' ";
            }
            $responseMappingSql .=" and a.course_id IN (".  implode(',', $responseCourses).")";
            
            $responseMappingSqlResult = $dbHandle->query($responseMappingSql);
            $responsecourseToCategoryMapping = array();
            foreach ($responseMappingSqlResult->result_array() as $result){
                $responsecourseToCategoryMapping[$result['courseId']] = $result['categoryName'];
            }
            foreach($responseArray as $key=>$response){
                $responseArray[$key]['categoryName'] = $responsecourseToCategoryMapping[$response['courseId']];
            }
        }
        
        $msgArray['responses'] = $responseArray;//array_slice($responseArray, $start, $count);
        
        foreach($msgArray['responses'] as $resp){
            $userIds .= $resp['userId'] .',';
        }
        
        if(!empty($userIds)) {
            $appID = 1;
            $this->load->library('LDB_Client');
            $ldbObj = new LDB_Client();
            $msgArray['userIdDetails'] = json_decode($ldbObj->sgetUserDetails($appID, trim($userIds,',')), true);
        }
        
        $msgArray['userIds'] = rtrim($userIds, ',');
        $msgArray['totalResponses'] = $totalRows;
        
        $response = base64_encode(json_encode($msgArray));
        return $this->xmlrpc->send_response($response);
    }
    
    public function sgetAllLeadsForOperator($request) {
        $parameters = $request->output_parameters();
        $searchCriteria = $parameters['0'];
        $timeInterval = $parameters['1'];
        $start=$parameters['2'];
        $count=$parameters['3'];
        $startDate = $parameters['4'];
        $endDate = $parameters['5'];
        
        $loggedInUserData = $this->getLoggedInUserData();
        $operatorId = $loggedInUserData['userId'];
        
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        
        $sql = "select SQL_CALC_FOUND_ROWS * from offlineOperatorTracking where operatorId = ? and status = '1' order by updated_at desc ";
        
        $start = (int)$start; $count = (int)$count;
        $sql .= "LIMIT $start, $count";
        $sqlResult = $dbHandle->query($sql, array($operatorId));
        $assignedUsers = '';
        foreach ($sqlResult->result_array() as $result){
            $assignedUsers .= $result['userId'].',';
        }
        
        $countQuery = "SELECT FOUND_ROWS() as totalRows";
        $queryCount = $dbHandle->query($countQuery);
        $totalRows = 0;
        foreach ($queryCount->result() as $row) {
                $totalRows = $row->totalRows;
        }
        
        $msgArray['userIds'] = rtrim($assignedUsers, ',');
        $msgArray['totalResponses'] = $totalRows;
        
        $response = base64_encode(json_encode($msgArray));
        return $this->xmlrpc->send_response($response);
    }
      
    public function getResponseListbyUserId($request) {
		$parameters = $request->output_parameters();
        $userId = $parameters['0'];
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "SELECT listing_type_id from tempLMSTable where listing_subscription_type='paid' and listing_type = 'course'".
                    " AND userId = ? AND submit_date >= DATE_SUB(now(), INTERVAL 24 HOUR)";
        $query = $dbHandle->query($queryCmd, array($userId));
        $response_list = array();
        foreach ($query->result_array() as $row){
		$response_list[] = $row['listing_type_id'];
        }
        error_log('response_list'.$queryCmd);
        $response = base64_encode(json_encode($response_list));
        return $this->xmlrpc->send_response($response);
    }
    
    public function getResponseExclusionListForStudyAboard($listingType = '', $listingIds = array(), $tabStatus = 'live') {
	$dbHandle = $this->_loadDatabaseHandle();
	
	if($listingType == '' || count($listingIds) == 0 || implode(',', $listingIds) == '') {
		return array();
	}
	
	$courseIds = array();
	$responseExclusionIds = array();
	
	$getCoursesQuery = "SELECT DISTINCT course_details.course_id
			    FROM course_details, abroadCategoryPageData
			    WHERE course_details.".$listingType."_id IN (?)
			    AND course_details.course_id = abroadCategoryPageData.course_id
			    AND course_details.course_level_1 = 'Under Graduate'
			    AND abroadCategoryPageData.country_id !=2
			    AND course_details.status = ? 
			    AND abroadCategoryPageData.status = ?";
	
		$getCoursesQueryResult = $dbHandle->query($getCoursesQuery,array($listingIds,$tabStatus,$tabStatus));
        foreach ($getCoursesQueryResult->result_array() as $row){
                $courseIds[] = $row['course_id'];
        }
	
	if(count($courseIds)) {
		$getResponsesQuery = "SELECT tlmst.id
				      FROM tempLMSTable AS tlmst
				      INNER JOIN tuserflag tf ON tf.userId = tlmst.userId
				      INNER JOIN tUserEducation te ON te.UserId = tlmst.userId
				      WHERE tlmst.listing_type = 'course'
				      AND tlmst.listing_type_id IN (?)
				      AND tlmst.action != 'marketingPage'
				      AND tlmst.listing_subscription_type = 'paid'
				      AND tf.isTestUser = 'NO'
				      AND te.Level = 'UG'";
				      
		$getResponsesQueryResult = $dbHandle->query($getResponsesQuery,array($courseIds));
		foreach ($getResponsesQueryResult->result_array() as $row){
			$responseExclusionIds[] = $row['id'];
		}
		
		return $responseExclusionIds;
	}
	else {
		return array();
	}
    }
    
    public function insertResponseForFreeCourse($request) {
        $parameters = $request->output_parameters();    
        $data = $parameters['0'];
        $data = json_decode($data,true);    
        $response = array();
        
        if(isset($data['sourcePage']) && $data['sourcePage'] != '') {
            $action = $data['sourcePage'];
        }
        else {
            $action = 'download_brochure_free_course';
        }
        $data['action'] = $action;

        $data['visitorSessionid'] = (!empty($data['visitorSessionid'])) ? $data['visitorSessionid'] : getVisitorSessionId();
        $data['submit_date'] = (!empty($data['submit_date'])) ? $data['submit_date'] : date('Y-m-d H:i:s');
        
        $dbHandle = $this->_loadDatabaseHandle('write');
    
        $queryCmd = "select id,action,submit_date from tempLMSTable where listing_subscription_type='free' and userId = ? and listing_type_id = ? and listing_type = ? and (UNIX_TIMESTAMP(?)- UNIX_TIMESTAMP(submit_date))<86400";
        $Result = $dbHandle->query($queryCmd, array($data['userId'], $data['listing_type_id'], $data['listing_type'], $data['submit_date']));
        $row = $Result->row_array();

        $extraDataForExclusion = array(
            'tracking_keyid' => (!empty($data['trackingPageKeyId'])) ? $data['trackingPageKeyId'] :$data['tracking_page_key']
        );
        
        if($row['id']) {
            $this->_gradeResponse($row,$data);
            
            $queryCmd = "update tempLmsRequest set count = count + 1,submit_date=? where listing_subscription_type = 'free' and userId = ? and listing_type_id = ? and listing_type = ? and (UNIX_TIMESTAMP(?)- UNIX_TIMESTAMP(submit_date)) < 86400";
            $dbHandle->query($queryCmd, array($data['submit_date'],$data['userId'], $data['listing_type_id'], $data['listing_type'],$data['submit_date']));
            
            $this->load->library('user/UserLib');
            $userLib = new UserLib;
            $userLib->checkUserForLDBExclusion($data['userId'], "response", "course", $data['listing_type_id'],'','', $data['action'],$extraDataForExclusion);

            $response = array(array('QueryStatus'=>'N.A.','leadId'=>1),'struct');
            return $this->xmlrpc->send_response($response);
	}
        /*
         *Check if userId is 0 and email id exists fetch teh userid from tuser
         */
        if(empty($data['userId'])&& $data['contact_email']){
            $data['userId'] = $this->_getUserIdByEmail($data['contact_email'], 'write');
        }

        // insert into tempLMSTable table       
        //Query Command for Insert in the Listing Main Table        
        $temp_lms_request_data = array(
        'listing_type_id'=>$data['listing_type_id'],
        'listing_type'=>$data['listing_type'],
        'userId'=>$data['userId'],
        'displayName'=>$data['displayName'],
        'email'=>$data['contact_email'],
        'contact_cell'=>$data['contact_cell'],
        'CounsellorId'=>isset($data['CounsellorId']) ? $data['CounsellorId'] : '',
        'listing_subscription_type'=>"free",
        'submit_date'=>$data['submit_date'] 
        );
        
        $queryCmd = $dbHandle->insert_string('tempLmsRequest',$temp_lms_request_data);      
        $queryCmd .= " on duplicate key update count=count+1, submit_date=?";
        $query = $dbHandle->query($queryCmd,array($data['submit_date'] ));
        $recent_id = $dbHandle->insert_id();
        $response['temp_lms_request_id'] = $recent_id;

        $dbHandle->where('queue_id', $data['queue_id']);
        $dbHandle->where('response_from', 'abroad');
        $dbHandle->update('response_elastic_map', array('temp_lms_request_id' => $recent_id));


        //below line is used for conversion ttracking purpose
        if(isset($data['trackingPageKeyId']))
        {
            $data['tracking_page_key']=$data['trackingPageKeyId'];
        }
        
        // insert into tempLMSTable 
        $temp_lms_table_data = array(
                'listing_type_id'=>$data['listing_type_id'],
                'listing_type'=>$data['listing_type'],
                'userId'=>$data['userId'],
                'displayName'=>$data['displayName'],
                'message'=>$data['message'],
                'email'=>$data['contact_email'],
                'action'=>$action,
                'contact_cell'=>$data['contact_cell'],
                'marketingFlagSent'=>isset($data['marketingFlagSent']) ? $data['marketingFlagSent'] : '',
                'marketingUserKeyId'=>isset($data['marketingUserKeyId']) ? $data['marketingUserKeyId'] : '',
         'listing_subscription_type'=>"free",
         'tracking_keyid'=>$data['tracking_page_key'],
          'visitorsessionid'=>$data['visitorSessionid'],
          'submit_date'=>$data['submit_date'] 
        );
        $queryCmd = $dbHandle->insert_string('tempLMSTable',$temp_lms_table_data);      
        $query = $dbHandle->query($queryCmd);
        $recent_id = $dbHandle->insert_id();
        $response['temp_lms_table_id'] = $recent_id;        

        $dbHandle->where('queue_id', $data['queue_id']);
        $dbHandle->where('response_from', 'abroad');
        $dbHandle->update('response_elastic_map', array('temp_lms_id' => $recent_id,'temp_lms_time'=> date("Y-m-d H:i:s"),'use_for_response'=>'y'));

        
        //insert data in elastic server queue
        $eData['action']   = 'sa_new_response';        
        $eDataEncoded      = json_encode($eData);

        $user_response_lib = $this->load->library('response/userResponseIndexingLib');      
        $user_response_lib->insertInResponseIndexLog($data['userId'],$eDataEncoded,$data['queue_id']);


        //insert into responseLocationTable
        if($data['institute_location_id'] > 0) {
            $Ldata = array(
                'responseId' => $response['temp_lms_table_id'],
                'instituteLocationId' => $data['institute_location_id'],
                'requestResponseId' => $response['temp_lms_request_id']
            );
            $queryCmd = $dbHandle->insert_string('responseLocationTable',$Ldata);
            $query = $dbHandle->query($queryCmd);

                        $sql = "SELECT city_id FROM institute_location_table WHERE institute_location_id = ? AND status = 'live'";
                        $query = $dbHandle->query($sql, array($data['institute_location_id']));
                        $row = $query->row_array();
                        $responseCityId = $row['city_id'];
                        $this->_updateResponseLocationAffinity($data['userId'], $responseCityId, $dbHandle);
        }
        
        //add user to  tuserIndexingQueue
        $this->load->model('user/usermodel');
        $this->usermodel->addUserToIndexingQueue($data['userId']);
        
        //update recent response flat table
        $flatArray = array(
            'userId'=>$data['userId'],
            'courseId'=>$data['listing_type_id'],
            'action'=>$action,
            'listing_subscription_type'=>'free',
            'modified_at'=>$data['submit_date'] 
        );
        $this->_insertLatestUserResponseData($dbHandle, $flatArray, 'free');
        
        //update tresponsedata
        $this->_updateConsolidatedResponseData($dbHandle,$data);
        
        //update tuserdata
        $this->load->library('user/UserLib');
        $userLib = new UserLib;
        $userLib->updateUserData($data['userId']);

        $userLib->checkUserForLDBExclusion($data['userId'], "response", "course", $data['listing_type_id'],'',$this->usermodel, $data['action'],$extraDataForExclusion);
        
        return $this->xmlrpc->send_response($response);
    }
    
    private function _insertLatestUserResponseData($dbHandle, $data = array(), $listing_subscription_type = ''){
		if(!($data['userId'] > 0 && $data['courseId'] > 0)) {
			return;
		}
	
        $sql = "SELECT userId FROM latestUserResponseData WHERE userId = ?";
        $query = $dbHandle->query($sql,array($data['userId']));
        $Result = $query->row_array();
        $uId = intval($Result['userId']);
        
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $courseRepository = $listingBuilder->getAbroadCourseRepository();
        $courseObj = $courseRepository->find($data['courseId']);
        $data['countryId'] = $courseObj->getMainLocation()->getCountry()->getId();
        $data['instituteId'] = $courseObj->getInstId();
        
        if($data['countryId'] > 2) {
            $data['universityId'] = $courseObj->getUniversityId();
            $categorySql = "SELECT category_id as categoryId FROM abroadCategoryPageData WHERE status ='live' AND course_id = ? LIMIT 1";
        }else{
            $categorySql = "SELECT b.parentId as categoryId FROM categoryPageData a inner join categoryBoardTable b on a.category_id = b.boardId WHERE a.status ='live' AND course_id = ? LIMIT 1";
            
        }
        
        $catQuery = $dbHandle->query($categorySql,array($data['courseId']));
        $catResult = $catQuery->row_array();
        $categoryId = intval($catResult['categoryId']);
        $data['categoryId'] = $categoryId;
		
        if($uId) {
            if($data['action'] != 'Viewed_Listing_Pre_Reg'){
                $dbHandle->where('userId',$uId);
                $res = $dbHandle->update('latestUserResponseData',$data);
                if($res === false){
                    error_log('Error in updating response flat table - '.mysql_error().print_r($data,true));
                }
		
				if($data['countryId'] == 2 && $listing_subscription_type == 'free') {
					$sql = "UPDATE tuserflag SET isLDBUser = 'YES' WHERE userId = ?";
					$query = $dbHandle->query($sql,array($data['userId']));
				}
            }
        }
        else {
		    $queryCmd = $dbHandle->insert_string('latestUserResponseData', $data);
            $queryCmd .= " on duplicate key update courseId = '".$data['courseId']."', instituteId = '".$data['instituteId']."', action = '".$data['action']."', listing_subscription_type = '".$data['listing_subscription_type']."', countryId = '".$data['countryId']."', categoryId = '".$data['categoryId']."'";
            $res = $dbHandle->query($queryCmd);

            // $res = $dbHandle->insert('latestUserResponseData',$data);
		    if($res === false){
				error_log('Error in insert response flat table - '.mysql_error().print_r($data,true));
            }
        }

        if($data['countryId'] > 2 && $listing_subscription_type == 'paid'){
            $sql = "UPDATE tuserflag SET isLDBUser = 'NO' WHERE userId = ?";
            $query = $dbHandle->query($sql,array($data['userId']));
        }
    }


    private function _convertResponseToLead($dbHandle,$data)
	{
		/**
		 * If not a course, do nothing
		 */ 
		if(!$data['listing_type'] == 'course') {
			return FALSE;
		}
		
		/**
		 * If location is not specified, do nothing
		 */ 
		if(!$data['institute_location_id']) {
			return FALSE;
		}
		
		/**
		 *	Check if user is already a lead
		 *	If yes, do nothing
		 */
		$sql = "SELECT isLDBUser FROM tuserflag WHERE userId = ?";
		$query = $dbHandle->query($sql,array($data['userId']));
		$result = $query->row_array();
		
		if($result['isLDBUser'] == 'YES') {
			return FALSE;
		}
			
		/**
		 * We need to make this user a lead
		 * Update desired course and location pref
		 */ 
		$sql =  "SELECT a.locality_id,a.city_id,a.country_id,b.state_id ".
				"FROM institute_location_table a ".
				"LEFT JOIN countryCityTable b ON b.city_id = a.city_id ".
				"WHERE a.institute_location_id = ? ".
				"AND status = 'live'";
		
		$query = $dbHandle->query($sql,array($data['institute_location_id']));
		$locationResult = $query->row_array();
		
		$sql =  "SELECT a.LDBCourseID, c.SpecializationName, d.SpecializationId
				FROM clientCourseToLDBCourseMapping a
				INNER JOIN LDBCoursesToSubcategoryMapping b ON b.ldbCourseID = a.LDBCourseID
				INNER JOIN tCourseSpecializationMapping c ON c.SpecializationId = a.LDBCourseID
				INNER JOIN tCourseSpecializationMapping d ON d.SpecializationId = c.ParentId
				WHERE a.clientCourseID = ?
				AND a.status =  'live'
				AND b.status =  'live'
				AND c.Status =  'live'
				AND d.Status =  'live'
				AND b.categoryID != 56";
				
		$query = $dbHandle->query($sql,array($data['listing_type_id']));
		$desiredCourseResult = $query->row_array();
		
		$desiredCourse = intval($desiredCourseResult['LDBCourseID']);
		$specialization = 0;
		
		if($desiredCourseResult['SpecializationName'] != 'All') {
			$desiredCourse = intval($desiredCourseResult['SpecializationId']);
			$specialization = intval($desiredCourseResult['LDBCourseID']);
		}
	
		$countryId = intval($locationResult['country_id']);
		$stateId = intval($locationResult['state_id']);
		$cityId = intval($locationResult['city_id']);
		$localityId = intval($locationResult['locality_id']);
		
		if(!$desiredCourse || !$countryId || ($countryId == 2 && !$cityId)) {
			return FALSE;
		}
		
		/**
		 * Update tuserflag
		 */
		$sql = "UPDATE tuserflag SET isLDBUser = 'YES',isResponseLead = 'YES' WHERE userid = ?";
		$dbHandle->query($sql,array($data['userId']));
		
		/**
		 * tUserPref
		 */
		$prefData = array(
							'DesiredCourse' => $desiredCourse,
							'SubmitDate' => date('Y-m-d H:i:s'),
							'TimeOfStart' => date('Y-m-d'),
							'is_processed' => 'no'
						);
		if($countryId > 2) {
			$prefData['ExtraFlag'] = 'studyabroad';
		}
		
		$sql = "SELECT PrefId  FROM tUserPref WHERE UserId = ?";
		$query = $dbHandle->query($sql,array($data['userId']));
		$prefResult = $query->row_array();
		$prefId = intval($prefResult['PrefId']);
		
		if($prefId) {
			$dbHandle->where('PrefId',$prefId);
			$dbHandle->update('tUserPref',$prefData);
		}
		else {
			$prefData['UserId'] = $data['userId'];
			$dbHandle->insert('tUserPref',$prefData);
			$prefId = $dbHandle->insert_id();
		}
		
		if($specialization) {
			$specializationPrefData = array(
				'UserId' => $data['userId'],
				'PrefId' => $prefId,
				'SpecializationId' => $specialization,
				'SubmitDate' => date('Y-m-d H:i:s'),
				'Status' => 'live'
			);
			$dbHandle->insert('tUserSpecializationPref',$specializationPrefData);
		}
		
		/**
		 * Insert in tUserLocationPref
		 */ 
		$locationPrefData = array(
			'UserId' => $data['userId'],
			'PrefId' => $prefId,
			'CountryId' => $countryId,
			'StateId' => $stateId,
			'CityId' => $cityId,
			'LocalityId' => $localityId,
			'Status' => 'live',
			'SubmitDate' => date('Y-m-d H:i:s')
		);
		$dbHandle->insert('tUserLocationPref',$locationPrefData);
		
		return TRUE;	
	}
	
	public function getMatchedResponses($courseIds = array(), $quality = array(), $startDate = '', $endDate = '', $showTable = TRUE, $showDetailed = FALSE) {

        //ini_set('memory_limit','2048M');
		$this->dbLibObj = DbLibCommon::getInstance('LMS');
		$dbHandle = $this->_loadDatabaseHandle();
	
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$courseRepository = $listingBuilder->getCourseRepository();
		
		$users = array();
		
		if(count($courseIds) > 0 && empty($quality)) {
			
			//$course_id_string = implode(',', $courseIds);
			$sql = "SELECT course_id, qualitypercentage
				FROM clientCourseMatchedResponseCriteria
				WHERE course_id IN (?)
				AND status = 'live'";
				
			$result = $dbHandle->query($sql,array($courseIds))->result_array();
			unset($sql);
			
			$quality = array_fill_keys($courseIds, 100);
			foreach($result as $row) {
				$quality[$row['course_id']] = $row['qualitypercentage'];
			}
			
			unset($result);
		}
		
        $parentStartDate = $startDate;                      //added to cater multi level matched responses
        $parentEndDate = $endDate;

		//exclude last 30 min users
		$endTime = '';
		$date = date('Y-m-d');
		if($endDate == $date) {
			$endTime = date('Y-m-d H:i:s', strtotime('-30 min'));
		}
		else {
			$endTime = $endDate.' 23:59:59';
		}
		
		
		if(count($courseIds) > 0) {
			$matchedCoursesMapping = array();
			$matchedCourses = array();
	   
    	   if(ALSO_VIEWED_ALGO == 'COLLABORATIVE_FILTERING') {
    		$sql = "SELECT a.course_id, a.recommended_course_id
    			FROM collaborativeFilteredCourses a, listings_main b
    			WHERE a.course_id IN (?)
    			AND b.listing_type_id = a.recommended_course_id
    			AND b.listing_type = 'course'
    			AND b.pack_type NOT IN (1,2,375)
    			AND b.status = 'live'
                AND a.weight>100000";
    	   }
    	   else {
    		$sql = "SELECT alsoViewedFilteredCourses.course_id, alsoViewedFilteredCourses.recommended_course_id
    			FROM alsoViewedFilteredCourses, listings_main
    			WHERE alsoViewedFilteredCourses.course_id IN (?)
    			AND alsoViewedFilteredCourses.status = 'live'
    			AND listings_main.listing_type_id = alsoViewedFilteredCourses.recommended_course_id
    			AND listings_main.listing_type = 'course'
    			AND listings_main.pack_type NOT IN (1,2,375)
    			AND listings_main.status = 'live'";
    	   }	
            /*
            $sql = "SELECT alsoViewedFilteredCourses.course_id, alsoViewedFilteredCourses.recommended_course_id
                FROM alsoViewedFilteredCourses, listings_main
                WHERE (".implode(' OR ',$qualityClause).")
                AND alsoViewedFilteredCourses.weight_percentage >= 0
                AND alsoViewedFilteredCourses.status = 'live'
                AND listings_main.listing_type_id = alsoViewedFilteredCourses.recommended_course_id
                AND listings_main.listing_type = 'course'
                AND listings_main.pack_type NOT IN (1,2,375)
                AND listings_main.status = 'live'"; 
            */
			$result = $dbHandle->query($sql,array($courseIds))->result_array();
			unset($sql);
			
			foreach($result as $row) {
				$matchedCoursesMapping[$row['recommended_course_id']][] = $row['course_id'];
			}

            unset($result);
            
            if(ALSO_VIEWED_ALGO == 'SHIKSHA_ALSO_VIEWED') {
                //logic to include two level match response
                $firstLevelAlsoViewed = array();
                $firstLevelAlsoViewed = array_keys($matchedCoursesMapping);

                if(count($firstLevelAlsoViewed) > 0 && ALSO_VIEWED_ALGO == 'SHIKSHA_ALSO_VIEWED'){
                    
                    $secondLevelmatchedCoursesMapping = $this->getAlsoViewedCourses($firstLevelAlsoViewed,'',$parentStartDate, $parentEndDate, TRUE, FALSE, $courseIds);   
                    
                    foreach ($secondLevelmatchedCoursesMapping as $recommended => $course) {
                        $secondLevelmatchedCoursesMapping[$recommended] = $matchedCoursesMapping[$course[0]];
                    }
                    if(empty($secondLevelmatchedCoursesMapping)) {
    					$secondLevelmatchedCoursesMapping = array();
                    }
                    $matchedCoursesMapping = $matchedCoursesMapping + $secondLevelmatchedCoursesMapping;
                    unset($secondLevelmatchedCoursesMapping);
                }
                //logic ends

                unset($firstLevelAlsoViewed);
    			//$matchedCourses = array_keys($matchedCoursesMapping);
           }
            $matchedCourses = $matchedCoursesMapping;


		}
		

		return array('users' => $users, 'courses' => $matchedCourses);
	}
	
	public function getResponseSubmitDate($userIds = array()) {
		$this->dbLibObj = DbLibCommon::getInstance('LMS');
		$dbHandle = $this->_loadDatabaseHandle();
		
		$responseDates = array();
		
		if(count($userIds) > 0)  {
			$sql = "SELECT DISTINCT userId, DATE(submit_date) AS submit_date
				FROM tempLMSTable
				WHERE userId IN (?)";
			
			$result = $dbHandle->query($sql,array($userIds))->result_array();
			
			foreach($result as $row) {
				$responseDates[$row['userId']][] = $row['submit_date'].' 00:00:00';
			}
		}
		
		return $responseDates;
	}
	
	public function getResponseCourses($userIds = array()) {
		$this->dbLibObj = DbLibCommon::getInstance('LMS');
		$dbHandle = $this->_loadDatabaseHandle();
		
		$responseCourses = array();
		
		if(count($userIds) > 0)  {
			$sql = "SELECT DISTINCT userId, listing_type_id
				FROM tempLMSTable
				WHERE listing_type = 'course'
				AND userId IN (?)";
			
			$result = $dbHandle->query($sql,array($userIds))->result_array();
			
			foreach($result as $row) {
				$responseCourses[$row['userId']][] = $row['listing_type_id'];
			}
		}
		
		return $responseCourses;
	}

	/*
	 * Author	: Abhinav
	 * Parameters	: CourseIds Array, RMS Type
	 * Purpose	: Get Counsellor data for respective universities for given set of courseIds
	 */
	private function _getCounsellorInfoForAbroadListings($courseIds,$rmsType = ''){
		if(!(is_array($courseIds) && count($courseIds) > 0)){
			return false;
		}
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder();
		$abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
		
		$courseObjArr = $abroadCourseRepository->findMultiple($courseIds);
		$universityArr = array();
		// collect universityIds for abroad courses
		foreach($courseObjArr as $key=>$courseObj){
			if($courseObj instanceof AbroadCourse){
				$universityArr[] = $courseObj->getUniversityId();
			}
		}
		$universityArr = array_unique($universityArr);
		if(count($universityArr) == 0){
			return false; // return if no abroad universities
		}
		
		$this->load->library('listing/AbroadListingCommonLib');
		$abroadListingCommonLib = new AbroadListingCommonLib();
		
		// counsellor's data for abroad universities whose counsellor's type is SQuares
		$counsellorDataForUniversities = $abroadListingCommonLib->getCounsellorsForUniversities($universityArr,$rmsType);
		$resultArr = array();
		foreach($courseObjArr as $key=>$courseObj){
			if($courseObj instanceof AbroadCourse && array_key_exists($courseObj->getUniversityId(),$counsellorDataForUniversities)){
				$resultArr[$courseObj->getId()] = $counsellorDataForUniversities[$courseObj->getUniversityId()];
			}
		}
		
		return $resultArr;
		
	}
	
	public function getMultiLevelMatchedResponses($courseIds = array(), $quality = array(), $startDate = '', $endDate = '', $showTable = TRUE, $showDetailed = FALSE) {
		return;	
        $queryString = explode('&',$_SERVER['QUERY_STRING']);
            
        foreach($queryString as $param) {
            $param = explode('=', $param);
            $params[$param[0]] = explode(',', $param[1]);
        }

        $flagCheck = $params['flag'][0];        //added for POC to include/exclude paid responses

		$firstLevelMatchedCourses=array();
		$firstLevelMatchedCourses = $this->getMatchedCourses($courseIds, $quality, $startDate,$endDate, TRUE, FALSE);  		//To get all the matched courses for passed Course Ids
		
        if(count($firstLevelMatchedCourses) == 0){
            echo "Course has no matched courses at first level.";
            return;
        }

        $masterStartDate = $startDate;			//To restore the date once calculated and not be calculate again
		$masterEndDate = $endDate;	
		$firstLevelMRCount = $this->getMatchedResponse($firstLevelMatchedCourses, $startDate, $endDate, TRUE, FALSE,$flagCheck);			//To get all the matched responses for the passed course IDs
		
        error_log("@@@@@@@@@@ firstLevelMRCount".$firstLevelMRCount);


		if($firstLevelMRCount > 0){
			echo '<strong>Total first level unique response users: </strong>'.$firstLevelMRCount.'<br>';
		} else if($firstLevelMRCount == -1){
			echo 'Found '.count($firstLevelMatchedCourses).' first level matched free courses.<br><br>';
			echo 'Course has no first level matched response.';
		}else if($firstLevelMRCount == -2){
			echo 'Course has no matched courses at first level.<br>';
		}
	
		$secondLevelMatchedCourses = $this->getMatchedCourses($firstLevelMatchedCourses, $quality = array(),$masterStartDate, $masterEndDate, TRUE, FALSE); 	// $masterStartDate and $masterEndDate are passed as they are already calculated 
		$secondLevelMRCount = $this->getMatchedResponse($secondLevelMatchedCourses, $startDate, $endDate,TRUE, FALSE,$flagCheck);
			
        error_log("@@@@@@@@@@ firstLevelMRCount".$secondLevelMRCount);

		if($secondLevelMRCount > 0){
			echo '<strong>Total second level unique response users: </strong>'.$secondLevelMRCount;
		} else if($secondLevelMRCount == -1){
			echo 'Found '.count($secondLevelMatchedCourses).' second level matched free courses.<br><br>';
			echo 'Course has no second level matched response.';
		}else if($secondLevelMRCount == -2){
			echo 'Course has no matched courses at second level.';
		}

 		return array('users' => $users, 'courses' => $matchedCourses);
	}
	
	
	/*
	 * Function to get all the recommended course IDs for passed course IDs.
	 * Pass by reference is used to pass values of $startDate and $endDate. This value is used again in function getMatchedResponse().
	 */
	public function getMatchedCourses($courseIds = array(), $quality = array(), &$startDate = '', &$endDate = '', $showTable = TRUE, $showDetailed = FALSE){
		$this->dbLibObj = DbLibCommon::getInstance('LMS');
		$dbHandle = $this->_loadDatabaseHandle();
		
		if(count($courseIds) === 0)  {
			$params = array();
			$queryString = explode('&',$_SERVER['QUERY_STRING']);
			
			foreach($queryString as $param) {
				$param = explode('=', $param);
				$params[$param[0]] = explode(',', $param[1]);
			}
			
			$courseIds = $params['courseIds'];
			
			$quality = array();
			for($index = 0; $index < count($courseIds); $index++) {
				$quality[$courseIds[$index]] = empty($params['quality'][$index]) ? 100 : $params['quality'][$index];
			}
			
			$startDate = $params['startDate'][0];
			$endDate = $params['endDate'][0];
			$showDetailed = $params['showDetailed'][0] == 1 ? TRUE : FALSE;
		}
		else if(count($courseIds) > 0 && empty($quality)) {
			$sql = "SELECT course_id, qualitypercentage
				FROM clientCourseMatchedResponseCriteria
				WHERE course_id IN (?)
				AND status = 'live'";
				
			$result = $dbHandle->query($sql,array($courseIds))->result_array();
			
			$quality = array_fill_keys($courseIds, 100);
			foreach($result as $row) {
				$quality[$row['course_id']] = $row['qualitypercentage'];
			}
		}
		
		if(count($courseIds) > 0) {
			$matchedCoursesMapping = array();
			$matchedCourses = array();
			
			$qualityClause = '';
			if(count($quality)) {
				foreach($quality as $courseId => $qualityScore) {
					$qualityClause[] = "(alsoViewedFilteredCourses.course_id = ".$courseId." AND alsoViewedFilteredCourses.weight_percentage <= ".$qualityScore.")";
				}
			}
			
			$params = array();
			$queryString = explode('&',$_SERVER['QUERY_STRING']);
				
			foreach($queryString as $param) {				// to get the passed course IDs from URL
				$param = explode('=', $param);
				$params[$param[0]] = explode(',', $param[1]);
			}
				
			$parentCourseIds = $params['courseIds'];		//fetched to exclude the passed courseIDs from two level MR
			
			$sql = "SELECT alsoViewedFilteredCourses.course_id, alsoViewedFilteredCourses.recommended_course_id
				FROM alsoViewedFilteredCourses, listings_main
				WHERE (".implode(' OR ',$qualityClause).")
				AND alsoViewedFilteredCourses.weight_percentage >= 0
				AND alsoViewedFilteredCourses.status = 'live'											
				AND alsoViewedFilteredCourses.recommended_course_id NOT IN (?)
				AND listings_main.listing_type_id = alsoViewedFilteredCourses.recommended_course_id
				AND listings_main.listing_type = 'course'
				AND listings_main.pack_type NOT IN (1,2,375)
				AND listings_main.status = 'live'";

			$result = $dbHandle->query($sql,array($parentCourseIds))->result_array();
			foreach($result as $row) {
				$matchedCoursesMapping[$row['recommended_course_id']][] = $row['course_id'];
			}
			
			$matchedCourses = array_keys($matchedCoursesMapping);
			
			return $matchedCourses;
		}else {
			if($showTable === TRUE) {
				echo 'Enter a valid course.';
			}
		}		
}

/*
 * Function to get all the matched response for passed Course Ids	
 */
	public function getMatchedResponse($matchedCourses = array(), $startDate = '', $endDate = '', $showTable = TRUE, $showDetailed = FALSE, $flagCheck='free'){
		$this->dbLibObj = DbLibCommon::getInstance('LMS');
		$dbHandle = $this->_loadDatabaseHandle();
		
        error_log("@@@@@@@@ Value of flag showTable is".$showTable);
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$courseRepository = $listingBuilder->getCourseRepository();
		
		$users = array();
		
		//exclude last 30 min users
		$endTime = '';
		$date = date('Y-m-d');
		if($endDate == $date) {
			$endTime = date('Y-m-d H:i:s', strtotime('-30 min'));
		}
		else {
			$endTime = $endDate.' 23:59:59';
		}	

        if($flagCheck == 'paid'){
            $queryCheck = "AND EXISTS (SELECT id FROM tempLMSTable c WHERE c.userId = a.userId AND c.listing_subscription_type = 'free')";
        }else{
            $queryCheck = "AND listing_subscription_type = 'free'";
        }	


		if(count($matchedCourses)){
			$sql = "SELECT id, userId, listing_type_id AS courseId, submit_date
				FROM
				(
					SELECT id, userId, listing_type_id, submit_date
					FROM tempLMSTable a
					WHERE listing_type = 'course'
					AND listing_type_id IN (".implode(',', $matchedCourses).")".
					$queryCheck.
					"AND submit_date >= ".$dbHandle->escape($startDate." 00:00:00")." 
					AND submit_date <= ".$dbHandle->escape($endTime)." 
					ORDER BY id DESC
				) AS tmp
				GROUP BY userId, listing_type_id";
				
			$response = $dbHandle->query($sql)->result_array();
	
			if(count($response)) {
					
				foreach($response as $row) {
					if(empty($users[$row['userId']]['matchedFor'])) {
						$users[$row['userId']]['matchedFor'] = array();
					}
					$users[$row['userId']]['matchedFor'] = array_merge($users[$row['userId']]['matchedFor'], $matchedCoursesMapping[$row['courseId']]);
					array_unique($users[$row['userId']]['matchedFor']);
						
					if($row['id'] > $users[$row['userId']]['responseId']) {
							$users[$row['userId']]['responseId'] = $row['id'];
					}
						
					if($row['submit_date'] > $users[$row['userId']]['submitDate']) {
						$users[$row['userId']]['submitDate'] = $row['submit_date'];
					}
				}
				
				if($showTable === TRUE) {
					$responseCount = array();
						
				foreach($response as $row) {
					if(!empty($users[$row['userId']])) {
						$responseCount[$row['courseId']] += 1;
					}
				}
	
					
	    	/*$courseObjs = array();
			$courseObjs = $courseRepository->findMultiple($matchedCourses);*/
						
		/* 	if($showDetailed === TRUE) {
				echo 'Found '.count($matchedCourses).' matched free courses.<br><br>';
							
			arsort($responseCount);									//Done to show the values below in decreasing order
							
					echo 	'<table border="1">
							<tr>
								<th>Course ID</th>
								<th>Institute Name</th>
								<th>Course Name</th>
								<th>Response Users</th>
							</tr>';
								
						foreach($responseCount as $courseId => $count) {						
							echo 	'<tr>
									<td>'.$courseId.'</td>
									<td>'.$courseObjs[$courseId]->getInstituteName().'</td>
									<td>'.$courseObjs[$courseId]->getName().'</td>
									<td>'.$count.'</td>
								</tr>';
						}
						
						echo '</table><br>';
					} */
					//echo '<strong>Total unique response users: </strong>'.count($users);

                    error_log("@@@@@@@ returning user count: ".count($users));
					return count($users);
				}else{
                    error_log("@@@@@@@ else of show table === true");
                }
			}
				else {
                    error_log("@@@@@@@ No match response ");
					if($showTable === TRUE) {
						return -1; 		// no matched response exists for passed course IDs
					}
			}
	}  else {
			if($showTable === TRUE) {
				return -2;		//No matched courses exists for passed course IDs.
			}
		}	 
    }   

    /*  Function to get also viewed courses for passed course ids
    *
    */
    function getAlsoViewedCourses($courseIds = array(), $quality = array(), $startDate = '', $endDate = '', $showTable = TRUE, $showDetailed = FALSE,$parentCourseIds = array()){

        $this->dbLibObj = DbLibCommon::getInstance('LMS');
        $dbHandle = $this->_loadDatabaseHandle();
        
        if(count($courseIds) === 0)  {
            $params = array();
            $queryString = explode('&',$_SERVER['QUERY_STRING']);
            
            foreach($queryString as $param) {
                $param = explode('=', $param);
                $params[$param[0]] = explode(',', $param[1]);
            }
            
            $courseIds = $params['courseIds'];
            
            $quality = array();
            for($index = 0; $index < count($courseIds); $index++) {
                $quality[$courseIds[$index]] = empty($params['quality'][$index]) ? 100 : $params['quality'][$index];
            }
            
            $startDate = $params['startDate'][0];
            $endDate = $params['endDate'][0];
            $showDetailed = $params['showDetailed'][0] == 1 ? TRUE : FALSE;
        }
        else if(count($courseIds) > 0 && empty($quality)) {
            $sql = "SELECT course_id, qualitypercentage
                FROM clientCourseMatchedResponseCriteria
                WHERE course_id IN (?)
                AND status = 'live'";
                
            $result = $dbHandle->query($sql,array($courseIds))->result_array();
            
            $quality = array_fill_keys($courseIds, 100);
            foreach($result as $row) {
                $quality[$row['course_id']] = $row['qualitypercentage'];
            }
        }
        
        if(count($courseIds) > 0) {
            $matchedCoursesMapping = array();
            
            $qualityClause = '';
            if(count($quality)) {
                foreach($quality as $courseId => $qualityScore) {
                   // $qualityClause[] = "(alsoViewedFilteredCourses.course_id = ".$courseId." AND alsoViewedFilteredCourses.weight_percentage <= ".$qualityScore.")";
                    $qualityClause[] = $courseId;
                }
            }

            unset($quality);

            $countOfClauses = count($qualityClause);
            $qualityClauseChunk = array();

            for($i=1; $i<= $countOfClauses; $i++) {   
                $qualityClauseChunk[] = $qualityClause[$i-1];       
                
                //get also viewed in chunk
                if($i%500 == 0 || $i == $countOfClauses){         
                    $sql = "SELECT alsoViewedFilteredCourses.course_id, alsoViewedFilteredCourses.recommended_course_id
                            FROM alsoViewedFilteredCourses, listings_main
                            WHERE alsoViewedFilteredCourses.course_id IN (?)
                            AND alsoViewedFilteredCourses.status = 'live'                                           
                            AND alsoViewedFilteredCourses.recommended_course_id NOT IN (?)
                            AND listings_main.listing_type_id = alsoViewedFilteredCourses.recommended_course_id
                            AND listings_main.listing_type = 'course'
                            AND listings_main.pack_type NOT IN (1,2,375)
                            AND listings_main.status = 'live'";

                    $queryResult = $dbHandle->query($sql,array($qualityClauseChunk,$parentCourseIds))->result_array();
                    
                    unset($qualityClauseChunk);
                    $qualityClauseChunk = array();
                    $result[] = $queryResult;
                   
                    unset($queryResult);
                }    
            }

            unset($qualityClause);
            unset($countOfClauses);
            unset($qualityClauseChunk);          

            foreach ($result as $key => $value) {                   //convert $result in one dimensional array
                foreach ($value as $row) {
                    $finalResult[] = $row;
                }
            }

            unset($result);

            /*$sql = "SELECT alsoViewedFilteredCourses.course_id, alsoViewedFilteredCourses.recommended_course_id
                            FROM alsoViewedFilteredCourses, listings_main
                            WHERE (".implode(' OR ',$qualityClause).")
                            AND alsoViewedFilteredCourses.weight_percentage >= 0
                            AND alsoViewedFilteredCourses.status = 'live'                                           
                            AND alsoViewedFilteredCourses.recommended_course_id NOT IN (".implode(',',$parentCourseIds).")
                            AND listings_main.listing_type_id = alsoViewedFilteredCourses.recommended_course_id
                            AND listings_main.listing_type = 'course'
                            AND listings_main.pack_type NOT IN (1,2,375)
                            AND listings_main.status = 'live'";
            */               

            $queryResult = $dbHandle->query($sql)->result_array();      
            foreach($finalResult as $row) {
                $matchedCoursesMapping[$row['recommended_course_id']][] = $row['course_id'];
            }

            unset($finalResult);    
            return $matchedCoursesMapping;
        }else {
            if($showTable === TRUE) {
                //echo 'Enter a valid course.';
                return;
            }
        }
    }

    function getActionTypeForResponse($tempLMSIds){

        $dbHandle = $this->_loadDatabaseHandle();        
        $sql = "select action, id from tempLMSTable where id in (?)";
        $query = $dbHandle->query($sql,array($tempLMSIds));

        foreach ($query->result() as $row) {
            $result[$row->id] = $row->action;
        }

        return $result;
    }

}
?>
