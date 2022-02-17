<?php 
class alumniSpeak extends MX_Controller {
        function _init() {
		$this->load->library('dbLibCommon');
        	$this->dbLibObj = DbLibCommon::getInstance('Listing');
        }

	function index() {
                $this->_init();
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('eventcalconfig'); 
		$this->load->helper(array('url','image'));
		$config['functions']['sgetFeedbackCriterias'] = array('function' => 'alumniSpeak.sgetFeedbackCriterias');
		$config['functions']['sinsertAlumnusFeedBack'] = array('function' => 'alumniSpeak.sinsertAlumnusFeedBack');
		$config['functions']['sgetFeedbackList'] = array('function' => 'alumniSpeak.sgetFeedbackList');
		$config['functions']['sgetFeedbacksForInstitute'] = array('function' => 'alumniSpeak.sgetFeedbacksForInstitute');
		$config['functions']['supdateReviewStatus'] = array('function' => 'alumniSpeak.supdateReviewStatus');
		$config['functions']['sgetExcludedCourses'] = array('function' => 'alumniSpeak.sgetExcludedCourses');
		$config['functions']['ssetExcludedCourses'] = array('function' => 'alumniSpeak.ssetExcludedCourses');
		$config['functions']['scheckReviewStatusMail'] = array('function' => 'alumniSpeak.scheckReviewStatusMail');
		$config['functions']['sinsertThreadId'] = array('function' => 'alumniSpeak.sinsertThreadId');
		$config['functions']['sgetRepliesForInstitute'] = array('function' => 'alumniSpeak.sgetRepliesForInstitute');
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}

    function sgetFeedbackCriterias($request) {
        // commented code for db load distribution 
        /*$dbConfig = array( 'hostname'=>'localhost');
        $this->eventcalconfig->getDbConfig($appId,$dbConfig);	
        $dbHandle = $this->load->database($dbConfig,TRUE);*/
        $dbHandle = $this->dbLibObj->getReadHandle();
        if($dbHandle == ''){
            error_log_shiksha('getEventUrl can not create db handle','events');
        }
        $queryCmd = 'SELECT * FROM talumnus_feedback_criteria';
        $query = $dbHandle->query($queryCmd);
        $feedbackCriterias = array();
        foreach ($query->result() as $row){
            $feedbackCriterias[] = $row;
        }
        $response = array(base64_encode(json_encode($feedbackCriterias)));
        return $this->xmlrpc->send_response($response);		
    }

    function sinsertAlumnusFeedBack($request) {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$data = json_decode(base64_decode($parameters['1']), true);
        // commented code for db load distribution 
        /*$dbConfig = array( 'hostname'=>'localhost');
        $this->eventcalconfig->getDbConfig($appId,$dbConfig);	
        $dbHandle = $this->load->database($dbConfig,TRUE);*/
        $dbHandle = $this->dbLibObj->getWriteHandle();
        if($dbHandle == ''){
            error_log_shiksha('getEventUrl can not create db handle','events');
        }
        if($data['email'] == '') {
            $response = array('Email Id not available');
            return $this->xmlrpc->send_response($response);
        }
        if($data['instituteId'] == '') {
            $response = array('Institute not available');
            return $this->xmlrpc->send_response($response);
        }
        $queryCmd = 'SELECT count(*) AS ALUMCNT FROM talumnus_details WHERE email = '. $dbHandle->escape($data['email']) .' AND institute_id = '. $dbHandle->escape($data['instituteId']) .'';
        // error_log("ASHISHHHH::". $queryCmd);
        $query = $dbHandle->query($queryCmd);
        $response = '';
        foreach ($query->result_array() as $row){
            $response = $row['ALUMCNT'] >= 1 ? 'Failure' : 'Success';
        }
        if($response === 'Failure') {
            return $this->xmlrpc->send_response($response);
        }

        $talumnusDetailData = array(
            'email' => $data['email'],
            'name' => $data['name'],
	    'course_id' => $data['course_id'],
            'course_name' => $data['course_completed'],
            'course_comp_year' => $data['course_comp_year'],
            'designation' => $data['designation'],
            'organisation' => $data['organisation'],
            'institute_id' => $data['instituteId'],
            'institute_name' => $data['instituteName'],
            'legalFlag' => $data['legalFlag'],
            'showShikshaFlag' => $data['showOnShikshaFlag'],
            'feedbackTime' => date('Y-m-d')
            );
        $queryCmd  = $dbHandle->insert_string('talumnus_details', $talumnusDetailData);
        $query = $dbHandle->query($queryCmd);
        if($dbHandle->affected_rows() < 1) {
            return;
        }
        foreach($data['feedback'] as $criteriaId => $criteria) {
            $queryCmd = 'INSERT INTO talumnus_feedback_rating SET email = '. $dbHandle->escape($data['email']) .', criteria_id = '. $dbHandle->escape($criteriaId) .', criteria_rating = '. $dbHandle->escape($criteria['criteriaRating']) .', criteria_desc = '. $dbHandle->escape($criteria['criteriaDescription']) .', institute_id= '. $dbHandle->escape($data['instituteId']) ;
            #error_log("ASHISHHHH:CRI:". $queryCmd);
            $query = $dbHandle->query($queryCmd);

        }
        $response = 'Success';
        return $this->xmlrpc->send_response($response);
    }

    function sgetFeedbackList($request) {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$criteria = json_decode($parameters['1'], true);
		$sort = json_decode($parameters['2'], true);
		$pageNum = $parameters['3'];
		$numRecords = $parameters['4'];
		$courseId = $parameters['5'];
		// error_log("AMIT: cid in sgetFeedbackList: ".$courseId);		
        // commented code for db load distribution 
        /*$dbConfig = array('hostname'=>'localhost');
        $this->eventcalconfig->getDbConfig($appId,$dbConfig);	
        $dbHandle = $this->load->database($dbConfig,TRUE);*/
        $dbHandle = $this->dbLibObj->getReadHandle();
        if($dbHandle == ''){
            error_log_shiksha('getEventUrl can not create db handle','events');
        }
	// error_log("AMIT: ".print_r($criteria, true));
        if(!empty($criteria)) {
            foreach($criteria as $criteriaKey => $criteriaValue) {
                //Assumed there won't be any case of '!='
		if($criteriaKey == 'institute_id') {
			$whereClauseArray[] = 'tad.'. $criteriaKey .' = '. $dbHandle->escape($criteriaValue);
		} else {
			$whereClauseArray[] = 'tad.'. $criteriaKey .' like ('. $dbHandle->escape($criteriaValue) .')';
		}
            }
        }
        $whereClauseArray[] = 'status != "discarded"';
	$whereClauseArray[] = 'tad.institute_id = tfr.institute_id';
        $whereClause = 'WHERE '. implode(' AND ', $whereClauseArray);
        $whereClause = str_replace('\')','%\')',$whereClause);
	
	if($courseId != "") {
		$whereClause .= ' AND course_id = '.$courseId;
	}

        $queryCmd = 'SELECT tfr.institute_id, tad.institute_name, COUNT(*) AS  total, SUM(IF(tfr.status="unpublished",1,0)) AS unpublished, MAX(tad.feedbackTime) AS lastRecieved FROM talumnus_feedback_rating AS tfr LEFT JOIN talumnus_details AS tad ON tad.email = tfr.email  '. $whereClause .' GROUP BY institute_id ';
	// 	error_log("AMIT: ".$queryCmd);
        $newQuery = $queryCmd;
        $sortReference = '';
        if(strpos($whereClause,'(') !== false) {
        $queryCmdUnion = 'SELECT tfr.institute_id, tad.institute_name, COUNT(*) AS  total, SUM(IF(tfr.status="unpublished",1,0)) AS unpublished, MAX(tad.feedbackTime) AS lastRecieved FROM talumnus_feedback_rating AS tfr LEFT JOIN talumnus_details AS tad ON tad.email = tfr.email  '. str_replace('(\'','(\'%',$whereClause) .' GROUP BY institute_id ';
            $newQuery = 'SELECT * FROM ('. $newQuery . ' UNION '. $queryCmdUnion .') as resultSet ';   
            $sortReference = 'resultSet.';
        }
        if(is_array($sort)) {
            $orderByClause = implode(','. $sortReference ,$sort);
        } else {
            $orderByClause =  $sortReference .'unpublished desc';
        }
        $newQuery.= 'ORDER BY '. $orderByClause .' LIMIT '. $pageNum .','. $numRecords;
    //error_log("ASHU::". $newQuery);
        $query = $dbHandle->query($newQuery);
        $feedbacks= array();
        foreach ($query->result() as $row) {
            $feedbacks[] = $row;
        }
        $response = array(base64_encode(json_encode($feedbacks)));
        return $this->xmlrpc->send_response($response);		
    }

    function sgetFeedbacksForInstitute($request) {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$instituteId = $parameters['1'];
		$sort = json_decode($parameters['2'], true);
		$courseId = $parameters['3'];
       
        $dbHandle = $this->dbLibObj->getReadHandle();
        if($dbHandle == ''){
            error_log_shiksha('getEventUrl can not create db handle','events');
        }
	
        $orderByClause = implode(',',$sort);
	// $removeEmptyEntries = "AND (tfr.institute_id, tfr.email, tfr.criteria_id) NOT IN (select tfrA.institute_id,tfrA.email,tfrA.criteria_id from talumnus_feedback_rating AS tfrA where tfrA.criteria_rating=0 AND tfrA.criteria_desc='') ";
	$removeEmptyEntries = "AND tfr.criteria_rating != 0 AND tfr.criteria_desc != '' ";
	
	/*
	if($courseId>0)
	  $excludeCourseList = 'AND (tfr.institute_id, tfr.email, tfr.criteria_id) NOT IN (select tecm.institute_id,tecm.email,tecm.criteria_id from talumnus_feedback_rating as tecm where FIND_IN_SET( '. $dbHandle->escape($courseId) .', tecm.excluded_course_id ))';
	else
	  $excludeCourseList = '';
	 */ 
	
	/*if($courseId != 0 && $courseId != "") {
		$courseClause = ' AND tad.course_id = '.$courseId;
	} else {
		$courseClause = "";
	}*/

    $dbHandle->select("tad.*, tfc.criteria_id, tfc.criteria_name, tfr.criteria_rating, tfr.criteria_desc,tfr.status, tfr.thread_id");
    $dbHandle->from("talumnus_details AS tad");
    $dbHandle->join("talumnus_feedback_rating AS tfr","tfr.institute_id = tad.institute_id AND tfr.email = tad.email");
    $dbHandle->join("talumnus_feedback_criteria AS tfc","tfc.criteria_id = tfr.criteria_id");
    $dbHandle->where("tad.institute_id",$instituteId);
    $dbHandle->where('tfr.status !="discarded"');
    $dbHandle->where('tfr.criteria_rating != 0');
    $dbHandle->where("tfr.criteria_desc != ''");
    if($courseId != 0 && $courseId != "") {
        $dbHandle->where("tad.course_id",$courseId);
    }
    $dbHandle->group_by("tad.email,tad.institute_id,tfr.criteria_id");
    $dbHandle->order_by($orderByClause);
	
	/*$queryCmd = 'SELECT tad.*, tfc.criteria_id, tfc.criteria_name, tfr.criteria_rating, tfr.criteria_desc,tfr.status, tfr.thread_id
	FROM talumnus_details AS tad INNER JOIN  talumnus_feedback_rating AS tfr ON (tfr.institute_id = tad.institute_id AND tfr.email = tad.email) INNER JOIN talumnus_feedback_criteria AS tfc ON tfc.criteria_id = tfr.criteria_id
	WHERE tad.institute_id='. $dbHandle->escape($instituteId) .' AND tfr.status !="discarded" '.$removeEmptyEntries.' '.$courseClause.' GROUP BY tad.email,tad.institute_id,tfr.criteria_id '. $orderByClause;
	*/	
	
	 // $queryCmd = 'SELECT tad.*, tfc.criteria_id, tfc.criteria_name, tfr.criteria_rating, tfr.criteria_desc,tfr.status FROM talumnus_details AS tad INNER JOIN  talumnus_feedback_rating AS tfr ON (tfr.institute_id = tad.institute_id AND tfr.email = tad.email) INNER JOIN talumnus_feedback_criteria AS tfc ON tfc.criteria_id = tfr.criteria_id WHERE tad.institute_id='. $dbHandle->escape($instituteId) .' AND tfr.status !="discarded" '.$removeEmptyEntries.' GROUP BY tad.email,tad.institute_id,tfr.criteria_id '. $orderByClause;
	// error_log("ASHU::". $queryCmd);
        //$query = $dbHandle->query($queryCmd);
        $query = $dbHandle->get();
	$feedbacks= array();
        foreach ($query->result() as $row) {
            $feedbacks[] = $row;
        }
        $response = array(base64_encode(json_encode($feedbacks)));
        return $this->xmlrpc->send_response($response);		
    }

    function supdateReviewStatus($request) {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$instituteId = $parameters['1'];
		$criteriaId = $parameters['2'];
		$email = $parameters['3'];
		$status = $parameters['4'];
        // commented code for db load distribution 
        /*$dbConfig = array( 'hostname'=>'localhost');
        $this->eventcalconfig->getDbConfig($appId,$dbConfig);	
        $dbHandle = $this->load->database($dbConfig,TRUE);*/
        $dbHandle = $this->dbLibObj->getWriteHandle();
        if($dbHandle == ''){
            error_log_shiksha('getEventUrl can not create db handle','events');
        }
	if($status == "published"){
	  $queryCmd = 'UPDATE talumnus_feedback_rating SET status = '. $dbHandle->escape($status) .' ,publishedTime = NOW() WHERE institute_id ='. $dbHandle->escape($instituteId) .' AND criteria_id ='. $dbHandle->escape($criteriaId) .' AND email ='. $dbHandle->escape($email);
	}
	else
	  $queryCmd = 'UPDATE talumnus_feedback_rating SET status = '. $dbHandle->escape($status) .' WHERE institute_id ='. $dbHandle->escape($instituteId) .' AND criteria_id ='. $dbHandle->escape($criteriaId) .' AND email ='. $dbHandle->escape($email);
        //error_log("ASHU::". $queryCmd);
        $query = $dbHandle->query($queryCmd);
        $response = 1;
	
	//changes be Bhuvnesh Pratap on 21-04-2011, data to be edited on the de noramlized institute_mediacount_rating_info table.
//        if($criteriaId == 4)//if the criteria 4 was modified 
//        {
            //$calQ= "select avg(criteria_rating) as rating from talumnus_feedback_rating where criteria_id= 4 and institute_id = $instituteId and status='published'";
            $calQ= "select round(avg(criteria_rating),1) as rating,count(*) as num,criteria_id from talumnus_feedback_rating where institute_id = ? and status='published'  and criteria_rating <> 0 group by criteria_id";
            $rating = 0;
            $qu= $dbHandle->query($calQ,array($instituteId));
            $rating_array = array();
            foreach ($qu->result_array() as $row){
               $rating_array[$row["criteria_id"]]["n"] = $row["num"];
               $rating_array[$row["criteria_id"]]["r"] = $row["rating"];
               if($row["criteria_id"]==4)
                    $rating= $row["rating"];
            }
            $ratings_json = json_encode($rating_array);
            $upQ= "Update institute_mediacount_rating_info set alumni_rating = ?,ratings_json=? where institute_id=?";
            $que= $dbHandle->query($upQ,array($rating,$ratings_json,$instituteId));
//        }



        return $this->xmlrpc->send_response($response);		
    }

    function sgetExcludedCourses($request) {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$instituteId = $parameters['1'];
		$criteriaId = $parameters['2'];
		$email = $parameters['3'];
		$status = $parameters['4'];
        // commented code for db load distribution 
        /*$dbConfig = array( 'hostname'=>'localhost');
        $this->eventcalconfig->getDbConfig($appId,$dbConfig);	
        $dbHandle = $this->load->database($dbConfig,TRUE);*/
        $dbHandle = $this->dbLibObj->getReadHandle();
        if($dbHandle == ''){
            error_log_shiksha('sgetExcludedCourses can not create db handle','events');
        }
        $queryCmd = 'select excluded_course_id from talumnus_feedback_rating WHERE institute_id ='. $dbHandle->escape($instituteId) .' AND criteria_id ='. $dbHandle->escape($criteriaId) .' AND email ='. $dbHandle->escape($email);
        $query = $dbHandle->query($queryCmd);
        $courseList = array();
        foreach ($query->result() as $row){
            $courseList[] = $row;
        }
        $response = array(base64_encode(json_encode($courseList)));
	return $this->xmlrpc->send_response($response);		
    }

    function ssetExcludedCourses($request) {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$instituteId = $parameters['1'];
		$criteriaId = $parameters['2'];
		$email = $parameters['3'];
		$courseList = $parameters['4'];
        // commented code for db load distribution 
        /*$dbConfig = array( 'hostname'=>'localhost');
        $this->eventcalconfig->getDbConfig($appId,$dbConfig);	
        $dbHandle = $this->load->database($dbConfig,TRUE);*/
        $dbHandle = $this->dbLibObj->getWriteHandle();
        if($dbHandle == ''){
            error_log_shiksha('ssetExcludedCourses can not create db handle','events');
        }
	$queryCmd = 'UPDATE talumnus_feedback_rating SET excluded_course_id = '. $dbHandle->escape($courseList) .' WHERE institute_id ='. $dbHandle->escape($instituteId) .' AND criteria_id ='. $dbHandle->escape($criteriaId) .' AND email ='. $dbHandle->escape($email);        $query = $dbHandle->query($queryCmd);
	//error_log($queryCmd);
        $response = 1;
        return $this->xmlrpc->send_response($response);		
    }

    function scheckReviewStatusMail($request) {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$instituteId = $parameters['1'];
		$status = $parameters['2'];
        // commented code for db load distribution 
        /*$dbConfig = array( 'hostname'=>'localhost');
        $this->eventcalconfig->getDbConfig($appId,$dbConfig);	
        $dbHandle = $this->load->database($dbConfig,TRUE);*/
        $dbHandle = $this->dbLibObj->getReadHandle();
        if($dbHandle == ''){
            error_log_shiksha('scheckReviewStatusMail can not create db handle','events');
        }
	$queryCmd = 'select TIMESTAMPDIFF(MINUTE, MAX(publishedTime), NOW()) as diff from talumnus_feedback_rating WHERE institute_id ='. $dbHandle->escape($instituteId) .' AND status ='. $dbHandle->escape($status);
        $query = $dbHandle->query($queryCmd);

        $response = array(base64_encode(json_encode($query->result())));
	return $this->xmlrpc->send_response($response);	
    }

    function sinsertThreadId($request) {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$instituteId = $parameters['1'];
		$criteriaId = $parameters['2'];
		$email = $parameters['3'];
		$thread_id = $parameters['4'];
        // commented code for db load distribution 
        /*$dbConfig = array( 'hostname'=>'localhost');
        $this->eventcalconfig->getDbConfig($appId,$dbConfig);	
        $dbHandle = $this->load->database($dbConfig,TRUE);*/
        $dbHandle = $this->dbLibObj->getWriteHandle();
        if($dbHandle == ''){
            error_log_shiksha('ssetExcludedCourses can not create db handle','events');
        }
	$queryCmd = 'UPDATE talumnus_feedback_rating SET thread_id = '. $dbHandle->escape($thread_id) .' WHERE institute_id ='. $dbHandle->escape($instituteId) .' AND criteria_id ='. $dbHandle->escape($criteriaId) .' AND email ='. $dbHandle->escape($email);        $query = $dbHandle->query($queryCmd);
	//error_log($queryCmd);
        $response = 1;
        return $this->xmlrpc->send_response($response);		
    }

    function sgetRepliesForInstitute($request) {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$threadIdCsv = $parameters['1'];
		$userId = $parameters['2'];
         // not required following code
        /*$dbConfig = array( 'hostname'=>'localhost');
        $this->eventcalconfig->getDbConfig($appId,$dbConfig);	
        $dbHandle = $this->load->database($dbConfig,TRUE);
        if($dbHandle == ''){
            error_log_shiksha('sgetRepliesForInstitute can not create db handle','events');
        }*/
        $dbHandle = $this->dbLibObj->getReadHandle();
	$this->load->model('QnAModel');
	$temptemp = $this->QnAModel->getTreeForMultipleThreads($dbHandle,$appID,$threadIdCsv,$userId,false);
        $response = array(base64_encode(json_encode($temptemp)));
        return $this->xmlrpc->send_response($response);		
    }

}
?>
