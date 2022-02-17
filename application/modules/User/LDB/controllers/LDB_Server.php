<?php

class LDB_Server extends MX_Controller {

    function index() {
        $this->dbLibObj = DbLibCommon::getInstance('LDB');
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->helper('url');
        $this->load->library(array('listingconfig', 'Alerts_client', 'subscription_client', 'sums_product_client'));
        $config['functions']['getCreditConsumedByGroup'] = array('function' => 'LDB_Server.getCreditConsumedByGroup');
        $config['functions']['getGroupForAcourse'] = array('function' => 'LDB_Server.getGroupForAcourse');
        $config['functions']['s_submitSearchQuery'] = array('function' => 'LDB_Server.s_submitSearchQuery');
        $config['functions']['s_searchLDB'] = array('function' => 'LDB_Server.s_searchLDB');
        $config['functions']['sgetSpecializationList'] = array('function' => 'LDB_Server.sgetSpecializationList');
        $config['functions']['sgetSpecializationListByParentId'] = array('function' => 'LDB_Server.sgetSpecializationListByParentId');
        $config['functions']['sgetCourseList'] = array('function' => 'LDB_Server.sgetCourseList');
        $config['functions']['sgetCourseForCriteria'] = array('function' => 'LDB_Server.sgetCourseForCriteria');
        $config['functions']['sgetCityStateList'] = array('function' => 'LDB_Server.sgetCityStateList');
        $config['functions']['sgetUserDetails'] = array('function' => 'LDB_Server.sgetUserDetails');
        $config['functions']['sUpdateContactViewed'] = array('function' => 'LDB_Server.sUpdateContactViewed');
        $config['functions']['sgetCreditToConsume'] = array('function' => 'LDB_Server.sgetCreditToConsume');
        $config['functions']['removeNdncUser'] = array('function' => 'LDB_Server.removeNdncUser');
        $config['functions']['isLDBUser'] = array('function' => 'LDB_Server.isLDBUser');
        $config['functions']['checkLDBUser'] = array('function' => 'LDB_Server.checkLDBUser');
        $config['functions']['sgetGroupList'] = array('function' => 'LDB_Server.sgetGroupList');
        $config['functions']['sgetCourseListByGroup'] = array('function' => 'LDB_Server.sgetCourseListByGroup');
        $config['functions']['sAddCoursesToGroup'] = array('function' => 'LDB_Server.sAddCoursesToGroup');
        $config['functions']['sRemoveCoursesFromGroup'] = array('function' => 'LDB_Server.sRemoveCoursesFromGroup');
        $config['functions']['saddGroupCreditConsumptionPolicy'] = array('function' => 'LDB_Server.saddGroupCreditConsumptionPolicy');
        $config['functions']['recordLDBActivity'] = array('function' => 'LDB_Server.recordLDBActivity');
        $config['functions']['getActivityDetails'] = array('function' => 'LDB_Server.getActivityDetails');
        $config['functions']['updateActivityStatus'] = array('function' => 'LDB_Server.updateActivityStatus');
        $config['functions']['sgetCreditConsumedForAction'] = array('function' => 'LDB_Server.sgetCreditConsumedForAction');
        $config['functions']['getCourseListByGroupTestPrep'] = array('function' => 'LDB_Server.getCourseListByGroupTestPrep');
        $config['functions']['getLeadsForInstitutes'] = array('function' => 'LDB_Server.getLeadsForInstitutes');
        $config['functions']['getResponsesByClientId'] = array('function' => 'LDB_Server.getResponsesByClientId');
        $config['functions']['getResponsesByMultiLocationClientId'] = array('function' => 'LDB_Server.getResponsesByMultiLocationClientId');
        $config['functions']['getResponseLocalities'] = array('function' => 'LDB_Server.getResponseLocalities');
        $config['functions']['getViewableUsers'] = array('function' => 'LDB_Server.getViewableUsers');
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }

    /**
      Function to check valid subscription
     * */
    function checkSubscription($user) {
        $this->load->library('sums_product_client');
        $objSumsProduct = new Sums_Product_client();
        $SubscriptionArray = $objSumsProduct->getAllSubscriptionsForUserLDB(1, array('userId' => $user));
        foreach ($SubscriptionArray as $subscription) {
            if ($subscription['BaseProdCategory'] == 'Lead-Search') {
                return true;
            }
        }
        return false;
    }

    /**
      Function For Searching Lead Database
     */
    function s_submitSearchQuery($request) {
        
        $parameters = $request->output_parameters(FALSE, FALSE);
        $appID = $parameters['0'];
        $dataArrJson = $parameters['1'];
        $user = $parameters['2'];
        $userGroup = $parameters['3'];
        $start = $parameters['4'];
        $end = $parameters['5'];
        $search_key = $parameters['6'];
        error_log("LDBX server key ".$search_key);
        $dataArr = json_decode($dataArrJson, true);
        /*
          $validSubscription=$this->checkSubscription($user);
          if($validSubscription==false)
          {
          $responseArray=array('error'=>'You dont have a valid subscription for this product');
          $response=array(base64_encode(json_encode($responseArray)),'string');
          return $this->xmlrpc->send_response($response);
          }
         */
        $this->load->library('LDB_Client');
        $ldbObj = new LDB_client();
        $searchResult = $ldbObj->searchLDB(1, $dataArr, $user, $start, $end,$search_key);
        //$searchResult = $this->searchLDB($dataArr, $user, $start, $end,$search_key);
        $this->trackSearch($user, $dataArr, $searchResult['totalRows']);
        if (count($searchResult['userIds']) == 0) {
            $responseArray = array('error' => 'No Results Found For Your Query');
            $response = array(base64_encode(json_encode($responseArray)), 'string');
        } else {
            $responseArray = array(
                'requestTime' => $searchResult['requestTime'],
                'numrows' => $searchResult['totalRows'],
                'result' => $this->createResultSet($searchResult['userIds'], $user)
            );
           // error_log("Ravi: Before json " . print_r($responseArray, true));
            $response = utility_encodeXmlRpcResponse($responseArray);
            error_log("LDBX ".strlen($response[0]));
#			$response=array((json_encode($responseArray)),'string');
        }

        return $this->xmlrpc->send_response($response);
    }

    function s_searchLDB($request) {
        $parameters = $request->output_parameters(FALSE, FALSE);
        $appID = $parameters['0'];
        $dataArrJson = $parameters['1'];
        $user = $parameters['2'];
        $start = $parameters['3'];
        $end = $parameters['4'];
        $search_key = $parameters['5'];
        
        $dataArr = json_decode($dataArrJson, true);
        $searchResult = $this->searchLDB($dataArr, $user, $start, $end,$search_key);
        $response = array(base64_encode(json_encode($searchResult)), 'string');
        return $this->xmlrpc->send_response($response);
    }

    function recordLDBActivity($request) {
        $parameters = $request->output_parameters(FALSE, FALSE);
        $appID = $parameters['0'];
        $SubscriptionArrJson = $parameters['1'];
        $ClientId = $parameters['2'];
        $UserIdList = $parameters['3'];
        $Action = $parameters['4'];
        $Status = $parameters['5'];
        error_log("sql I am here");
        $LDBActivityLogColumnList = array(
            'ClientId' => $ClientId,
            'UserIdLIst' => $UserIdList,
            'SubscriptionInfo' => $SubscriptionArrJson,
            'Action' => $Action,
            'Status' => $Status
        );
        $dbHandle = $this->_loadDatabaseHandle('write');
        // error_log("LDBActivityLog " . print_r($LDBActivityLogColumnList, true));
        $insertQuery = $dbHandle->insert_string('LDBActivityLog', $LDBActivityLogColumnList);
        $dbHandle->query($insertQuery);
        $ActivityId = $dbHandle->insert_id();
        $response = array($ActivityId, 'string');
        return $this->xmlrpc->send_response($response);
    }

    function getActivityDetails($request) {
        $parameters = $request->output_parameters(FALSE, FALSE);
        $appID = $parameters['0'];
        $activityId = $parameters['1'];
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "select * from LDBActivityLog where Id=?";
        error_log($queryCmd);
        $queryResult = $dbHandle->query($queryCmd, array($activityId));
        $result = $queryResult->result_array();
        $response = array(json_encode($result), 'string');
        return $this->xmlrpc->send_response($response);
    }

    function updateActivityStatus($request) {
        $parameters = $request->output_parameters(FALSE, FALSE);
        $appID = $parameters['0'];
        $activityId = $parameters['1'];
        $activityStatus = $parameters['2'];
        $dbHandle = $this->_loadDatabaseHandle('write');
        
        if($activityId >0 && !empty($activityStatus)) {
			$queryCmd = "update LDBActivityLog set Status=? where Id=?";
			error_log($queryCmd);
			$dbHandle->query($queryCmd, array($activityStatus, $activityId));
		}
		
        $result = '1';
        $response = array(json_encode($result), 'string');
        return $this->xmlrpc->send_response($response);
    }

    function searchLDB($dataArr, $user, $start, $end,$search_key) {
      
		ini_set('memory_limit','2048M');
        $executionStartTime = microtime(true);
        error_log("LDBX key = ".$search_key);
        $this->load->library("CacheLib");
        $cache = new CacheLib();
        $requestTime = date("Y-m-d H:i:s");
        if($cache->get($search_key)=='ERROR_READING_CACHE'){
            error_log("LDBX memcache no key");
            /*
              FIXED BUG... #512 .... RESET REQUEST TIME...
              if(isset($dataArr['requestTime'])&&$dataArr['requestTime']!='')
              {
              $requestTime=$dataArr['requestTime'];
              }
              else
              {
              $requestTime = date("Y-m-d H:i:s");
              }
             */
            
            $dbHandle = $this->_loadDatabaseHandle('read', 'LDBSEARCH');
            
            $date_bulk_sms_banned_ayodhya_verdict = '';
            // error_log("**********" . print_r($dataArr, true));
            /**
              Mapping or SearchFields with table Columns with comparision operator
             */
            $tableKeyMapping = array(
                'DesiredCourse' => array('table' => 'tCourseSpecializationMapping', 'query' => 'tCourseSpecializationMapping.CourseName='),
                'search_category_id' => array('table' => 'tCourseSpecializationMapping', 'query' => 'tCourseSpecializationMapping.categoryId='),
                'Specialization' => array('table' => 'tUserSpecializationPref', 'query' => 'tUserSpecializationPref.SpecializationId='),
                'ModeFullTime' => array('table' => 'tUserPref', 'query' => 'tUserPref.ModeOfEducationFullTime='),
                'ModePartTime' => array('table' => 'tUserPref', 'query' => 'tUserPref.ModeOfEducationPartTime='),
                'ModeDistance' => array('table' => 'tUserPref', 'query' => 'tUserPref.ModeOfEducationDistance='),
                'CurrentLocation' => array('table' => 'tuser', 'query' => 'tuser.city='),
                //'currentLocalities' => array('table' => 'tuser', 'query' => 'tuser.Locality='),
                'CurrentCities'  =>  array('table' => 'tuser', 'query' => 'tuser.city='),
                'PreferredLocation'=>array('table'=>'tUserLocationPref','query'=>'tUserLocationPref.CityId='),
                'DegreePrefAny' => array('table' => 'tUserPref', 'query' => 'tUserPref.DegreePrefAny='),
                'DegreePrefAICTE' => array('table' => 'tUserPref', 'query' => 'tUserPref.DegreePrefAICTE='),
                'DegreePrefInternational' => array('table' => 'tUserPref', 'query' => 'tUserPref.DegreePrefInternational='),
                'DegreePrefUGC' => array('table' => 'tUserPref', 'query' => 'tUserPref.DegreePrefUGC='),
                'UGCourse' => array('table' => 'tUserEducation tue1', 'query' => 'tue1.Name='),
                'UGInstitute' => array('table' => 'tUserEducation tue1', 'query' => 'tue1.InstituteId='),
                'GraduationCompletedFrom' => array('table' => 'tUserEducation tue1', 'query' => 'tue1.CourseCompletionDate>='),
                'GraduationCompletedTo' => array('table' => 'tUserEducation tue1', 'query' => 'tue1.CourseCompletionDate<='),
                'XIICompletedFrom'  => array('table' => 'tUserEducation tue2', 'query' => 'tue2.CourseCompletionDate>='),
                'XIICompletedTo'  => array('table' => 'tUserEducation tue2', 'query' => 'tue2.CourseCompletionDate<='),
                'MinGradMarks' => array('table' => 'tUserEducation tue1', 'query' => 'tue1.Marks>='),
                'IncludeResultsAwaited' => array('table' => 'tUserEducation tue1', 'query' => 'tue1.OngoingCompletedFlag='),
                'MinXIIMarks' => array('table' => 'tUserEducation tue2', 'query' => 'tue2.Marks>='),
                'XIIStream' => array('table' => 'tUserEducation tue2', 'query' => 'tue2.Name='),
                'Gender' => array('table' => 'tuser', 'query' => 'tuser.Gender='),
                'MinAge' => array('table' => 'tuser', 'query' => 'tuser.age>='),
                'MaxAge' => array('table' => 'tuser', 'query' => 'tuser.age<='),
                'MinExp' => array('table' => 'tuser', 'query' => 'tuser.experience>='),
                'MaxExp' => array('table' => 'tuser', 'query' => 'tuser.experience<'),
                'Locality' => array('table' => 'tUserLocationPref', 'query' => 'tUserLocationPref.LocalityId='),
                'UserFundsOwn' => array('table' => 'tUserPref', 'query' => 'tUserPref.UserFundsOwn='),
                'UserFundsBank' => array('table' => 'tUserPref', 'query' => 'tUserPref.UserFundsBank='),
                'UserFundsNone' => array('table' => 'tUserPref', 'query' => 'tUserPref.UserFundsNone='),
                'otherFundingDetails' => array('table' => 'tUserPref', 'query' => 'tUserPref.otherFundingDetails='),
                'ExtraFlag' => array('table' => 'tUserPref', 'query' => 'tUserPref.ExtraFlag='),
                'DesiredCourseId' => array('table' => 'tUserPref', 'query' => 'tUserPref.DesiredCourse='),
                'Viewed' => array('table' => 'LDBLeadContactedTracking', 'query' => 'LDBLeadContactedTracking.ContactType="view" and LDBLeadContactedTracking.ClientId='),
                'Emailed' => array('table' => 'LDBLeadContactedTracking', 'query' => 'LDBLeadContactedTracking.ContactType="email" and LDBLeadContactedTracking.ClientId='),
                'Smsed' => array('table' => 'LDBLeadContactedTracking', 'query' => 'LDBLeadContactedTracking.ContactType="sms" and LDBLeadContactedTracking.ClientId='),
                //'DateFilterFrom' => array('table' => 'tuser', 'query' => 'tUserPref.submitDate>= '),
                //'DateFilterTo' => array('table' => 'tuser', 'query' => 'tUserPref.submitDate<= '),
                /* TEST PREP START */
                'testPrep_blogid' => array('table' => 'tUserPref_testprep_mapping', 'query' => 'tUserPref_testprep_mapping.blogid='),
                'passport' => array('table' => 'tuser', 'query' => 'tuser.passport='),
                //'budget' => array('table' => 'tUserPref', 'query' => 'tUserPref.program_budget='),
                //'planToStart' => array('table' => 'tUserPref', 'query' => 'tUserPref.TimeOfStart>='),
                'abroadSpecializations' => array('table' => 'tUserPref', 'query' => 'tUserPref.abroad_subcat_id=')
                /* TEST PREP END */
            );
            
            if (isset($dataArr['Unsubscribe']) && $dataArr['Unsubscribe'] == '0') {
                $unsubscribe = " and tuserflag.unsubscribe='0'";
            }
            else {
                $unsubscribe = "";
            }
            
            if (isset($dataArr['mobileVerified']) && $dataArr['mobileVerified'] == '0') {
                $mobileVerfied = "";
            }
            else {
                $mobileVerfied = " and tuserflag.mobileverified='1' ";
            }
            
            /*
              Mapping for Base Joiners or Tables
             */
            $tableBaseJoinerMapping = array(
                'tUserSpecializationPref' => 'tUserSpecializationPref.UserId=tuser.userid and tUserSpecializationPref.PrefId=tUserPref.PrefId and tUserSpecializationPref.Status="live"',
                'tUserLocationPref' => 'tUserLocationPref.UserId=tuser.userid and tUserLocationPref.PrefId=tUserPref.PrefId and tUserLocationPref.Status="live"',
                'tUserEducation tue1' => 'tue1.UserId=tuser.userid and tue1.Level="UG" and tue1.Status="live"',
                'tUserEducation tue2' => 'tue2.UserId=tuser.userid and tue2.Level="12" and tue2.Status="live"',
                'LDBLeadContactedTracking' => 'LDBLeadContactedTracking.UserId=tuser.userid',
                /* TEST PREP START */ 'tUserPref_testprep_mapping' => 'tUserPref_testprep_mapping.prefid=tUserPref.PrefId AND tUserPref_testprep_mapping.status="live"'
                    /* TEST PREP END */
            );


            if($dataArr['isMMM'] === 'yes'){
                $tableBaseJoinerMapping['tUserPref'] = 'tUserPref.UserId=tuser.userid and tUserPref.Status="live" and tuserflag.hardbounce="0" and tuserflag.ownershipchallenged="0" and tuserflag.abused="0" and tuserflag.softbounce="0" and tuserflag.isTestUser="NO" AND tuserflag.isResponseLead = "NO" and tUserPref.UserId=tuserflag.userId'.$mobileVerfied.$unsubscribe;

                $tableBaseJoinerMapping['tCourseSpecializationMapping'] = 'tCourseSpecializationMapping.SpecializationId=tUserPref.DesiredCourse and tCourseSpecializationMapping.Status="live" and tUserPref.UserId=tuser.userid and tUserPref.Status="live" and tuserflag.isTestUser="NO" AND tuserflag.isResponseLead = "NO" and tuserflag.hardbounce="0" and tuserflag.ownershipchallenged="0" and tuserflag.abused="0" and tuserflag.softbounce="0"  and tUserPref.UserId=tuserflag.userId'.$mobileVerfied.$unsubscribe;
            } else{
                 $tableBaseJoinerMapping['tUserPref'] = 'tUserPref.UserId=tuser.userid and tUserPref.Status="live" and tuserflag.isLDBUser="Yes" and tuserflag.hardbounce="0" and tuserflag.ownershipchallenged="0" and tuserflag.abused="0" and tuserflag.softbounce="0" and tuserflag.isTestUser="NO" AND tuserflag.isResponseLead = "NO" and tUserPref.UserId=tuserflag.userId'.$mobileVerfied.$unsubscribe;

                $tableBaseJoinerMapping['tCourseSpecializationMapping'] = 'tCourseSpecializationMapping.SpecializationId=tUserPref.DesiredCourse and tCourseSpecializationMapping.Status="live" and tUserPref.UserId=tuser.userid and tUserPref.Status="live" and tuserflag.isLDBUser="Yes" and tuserflag.isTestUser="NO" AND tuserflag.isResponseLead = "NO" and tuserflag.hardbounce="0" and tuserflag.ownershipchallenged="0" and tuserflag.abused="0" and tuserflag.softbounce="0"  and tUserPref.UserId=tuserflag.userId'.$mobileVerfied.$unsubscribe;
            }




            /*
              Mapping of Prerequisite Tables
             */
            $tableDependencyMapping = array(
                'tUserSpecializationPref' => array('tuser', 'tUserPref'),
                'tUserPref' => array('tuser', 'tuserflag'),
                'tUserEducation' => array('tuser'),
                'tUserLocationPref' => array('tuser', 'tUserPref'),
                'tCourseSpecializationMapping' => array('tuser', 'tUserPref', 'tuserflag'),
                'LDBLeadContactedTracking' => array('tuser'),
                /* TEST PREP START */
                'tUserPref_testprep_mapping' => array('tUserPref')
                    /* TEST PREP END */
            );

            /*
              Mapping of Fields which have OR in between
             */
            $keyOrMap = array(
                array('ModeFullTime', 'ModePartTime', 'ModeDistance'),
                array('MinGradMarks', 'IncludeResultsAwaited'),
                array('DegreePrefAny', 'DegreePrefAICTE', 'DegreePrefUGC', 'DegreePrefInternational'),
                array('UserFundsOwn', 'UserFundsBank', 'UserFundsNone', 'otherFundingDetails')
            );

            /*
              Handling of Location And OR
             */
            if (isset($dataArr['LocationAndOr']) && $dataArr['LocationAndOr'] == 1) {
                $keyOrMap[] = array('CurrentLocation', 'PreferredLocation');
            }

            $baseQuery = "select DISTINCT tuser.userid,tUserPref.SubmitDate from ";
            $tableMap = array();  //Array of required tables
            $tableBaseJoinerMap = array(); //Array or Base joiners
            $queryAppend = array(); //Array or where clauses
            $showContactQuery = ''; //Query Parameter for Already Viewed Contact Display on and off
            foreach ($dataArr as $key => $val) {
                if (isset($val)) {
                    if ((empty($val) || $val == '' || count($val) == 0) && !is_numeric($val)) {
                        continue;
                    }

                    if(is_array($val)) {
                        foreach($val as $valKey => $valValue) {
                            if(!is_array($valValue)) {
                                $val[$valKey] = $dbHandle->escape_str($valValue);
                            }
                        }
                    }
                    else {
                        $val = $dbHandle->escape_str($val);
                    }

                    if (array_key_exists($key, $tableKeyMapping)) {
                        $tableReq = $tableKeyMapping[$key]['table'];
                        if (!array_key_exists($tableReq, $tableMap)) {
                            $tableMap[$tableReq] = $tableReq;
                            if (isset($tableBaseJoinerMapping[$tableReq])) {
                                $tableBaseJoinerMap[$tableReq] = $tableBaseJoinerMapping[$tableReq];
                            }
                        }
                        if (is_array($val)) {
                            $query = $tableKeyMapping[$key]['query'];
                            $appended = false;
                            foreach ($keyOrMap as $id => $value) {
                                if (in_array($key, $value)) {
                                    foreach ($value as $newVal) {
                                        if (array_key_exists($newVal, $queryAppend)) {
                                            $queryAppend[$newVal].=" OR " . $query . "'" . implode("' OR " . $query . "'", $val) . "'";
                                            $appended = true;
                                        }
                                    }
                                }
                            }
                            if (!$appended) {
                                $queryAppend[$key] = " (" . $query . "'" . implode("' OR " . $query . "'", $val) . "'";
                            }
                        } else {
                            $appended = false;
                            foreach ($keyOrMap as $id => $value) {
                                if (in_array($key, $value)) {
                                    foreach ($value as $newVal) {
                                        if (array_key_exists($newVal, $queryAppend)) {
                                            $queryAppend[$newVal].=" OR " . $tableKeyMapping[$key]['query'] . "'" . $val . "'";
                                            $appended = true;
                                        }
                                    }
                                }
                            }
                            if (!$appended) {
                                //if ($key != 'DateFilter') {
                                    $queryAppend[$key] = " (" . $tableKeyMapping[$key]['query'] . "'" . $val . "'";
                                //} else {
                                //    $queryAppend[$key] = " (" . $tableKeyMapping[$key]['query'] . "" . $val . "";
                                //}
                            }
                        }
                    }
                    /*
                      Handling of Plan To Start
                     */
                    if($key == 'planToStart') {
                        $appendArray = array();
                        foreach ($val as $index => $value){
                            if($value == 'Later'){
                                $year = date('Y',strtotime('+2 year'));
                                $startDate = $year."-01-01 00:00:00";
                                $appendQuery = "(tUserPref.TimeOfStart >= '".$dbHandle->escape_str($startDate)."'";
                            }
                            else {
                                $startDate = $value."-01-01 00:00:00";
                                $endDate = $value."-12-31 23:59:59";
                                $appendQuery = "(tUserPref.TimeOfStart >= '".$dbHandle->escape_str($startDate)."' AND tUserPref.TimeOfStart <= '".$dbHandle->escape_str($endDate)."'";
                            }
                            $appendArray['PlanToStart '. $value] = $appendQuery;
                        }
                        if (!empty($appendQuery)) {
                            $queryAppend[$key] = "(" . implode(") OR ", $appendArray) . ")";
                        }
                    }
                    
                    /*
                      Special Handling of ExamScore
                     */
                    if ($key == 'ExamScore') {
                        $appendArray = array();
                        
                        foreach ($val as $examName => $value) {
                            $tableMap['tUserEducation ' . $examName] = 'tUserEducation ' . $examName;
                            $tableBaseJoinerMap['tUserEducation ' . $examName] = $examName . '.UserId=tuser.userid and ' . $examName . '.Status="live"';
                            $appendQuery = '(' . $examName . '.Name="' . $dbHandle->escape_str($examName) . '"';
                            if (!empty($value['min'])) {
                                $appendQuery.= " and " . $examName . ".Marks>='" . $dbHandle->escape_str($value['min'])."'";
                            }
                            if (!empty($value['max'])) {
                                $appendQuery.= " and " . $examName . ".Marks<='" . $dbHandle->escape_str($value['max'])."'";
                            }
                            if (!empty($value['year'])) {
                                $appendQuery.= " and " . $examName . ".CourseCompletionDate='" . $dbHandle->escape_str($value['year'])."'";
                            }
                            if (!empty($appendQuery)) {
                                $appendArray['tUserEducation ' . $examName] = $appendQuery;
                            }
                        }
                        if (!empty($appendQuery)) {
                            $queryAppend['ExamScore'] = "(" . implode(") OR ", $appendArray) . ")";
                        }
                    }
                    error_log($key);
                    if ($key == 'PreferredLocation') {
                        $prefArray = array();
                        foreach ($val as $testval) {
                            $jsonObj = base64_decode($testval);
                            $arrayObj = json_decode($jsonObj, true);
                            array_push($prefArray, $arrayObj);
                        }
                        // error_log(print_r($prefArray, true));
                        $queryAppendElement = array();
                        for ($i = 0; $i < count($prefArray); $i++) {
                            $queryPart = '';
                            if ($prefArray[$i]['cityId'] != 0) {
                                $queryPart = "((tUserLocationPref.CountryId=" . $dbHandle->escape($prefArray[$i]['countryId']) . " and tUserLocationPref.StateId=0 and tUserLocationPref.CityId=0) OR ";
                                $queryPart.="(tUserLocationPref.CountryId=" . $dbHandle->escape($prefArray[$i]['countryId']) . " and tUserLocationPref.StateId=" . $dbHandle->escape($prefArray[$i]['stateId']) . " and tUserLocationPref.CityId=0) OR ";
                                $queryPart.="(tUserLocationPref.CountryId=" . $dbHandle->escape($prefArray[$i]['countryId']) . " and tUserLocationPref.StateId=" . $dbHandle->escape($prefArray[$i]['stateId']) . " and tUserLocationPref.CityId=" . $dbHandle->escape($prefArray[$i]['cityId']) . "))";
                            } else if ($prefArray[$i]['stateId'] != 0) {
                                $queryPart = "((tUserLocationPref.CountryId=" . $dbHandle->escape($prefArray[$i]['countryId']) . " and tUserLocationPref.StateId=0 and tUserLocationPref.CityId=0) OR ";
                                $queryPart.="(tUserLocationPref.CountryId=" . $dbHandle->escape($prefArray[$i]['countryId']) . " and tUserLocationPref.StateId=" . $dbHandle->escape($prefArray[$i]['stateId']) . " and tUserLocationPref.CityId=0))";
                            } else {
                                $queryPart = "((tUserLocationPref.CountryId=" . $dbHandle->escape($prefArray[$i]['countryId']) . " and tUserLocationPref.StateId=0 and tUserLocationPref.CityId=0))";
                            }
                            $queryAppendElement[] = $queryPart;
                        }
                        $appended = false;
                        foreach ($keyOrMap as $id => $value) {
                            if (in_array($key, $value)) {
                                foreach ($value as $newVal) {
                                    if (array_key_exists($newVal, $queryAppend)) {
                                        $queryAppend[$newVal].=" OR (" . implode(" OR ", $queryAppendElement) . " ) ";
                                        $appended = true;
                                    }
                                }
                            }
                        }
                        if (!$appended) {
                            $queryAppend[$key] = "(" . implode(" OR ", $queryAppendElement);
                        }
                        $tableMap['tUserLocationPref'] = 'tUserLocationPref';
                        $tableReq = 'tUserLocationPref';
                        if (isset($tableBaseJoinerMapping[$tableReq])) {
                            $tableBaseJoinerMap[$tableReq] = $tableBaseJoinerMapping[$tableReq];
                        }
                    }
                   
                
                    if($key == 'CurrentCities'){
                        $query_loc="";
                        $complete_Query=array();
                    for($i=0;$i<count($dataArr['CurrentCities']); $i++ ){
                    
                    if($dataArr['CurrentCities'][$i] && !empty($dataArr['currentLocalities'][$i])){
                        $query_loc= " ( tuser.city=".$dbHandle->escape($dataArr['CurrentCities'][$i])." AND tuser.Locality IN (";
                        $query_loc.= implode(",",$dataArr['currentLocalities'][$i]);    
                         $query_loc.="))";
                        $complete_Query[]=$query_loc;
                    }else if($dataArr['CurrentCities'][$i] && empty($dataArr['currentLocalities'][$i])){
                        $complete_Query[] =" ( tuser.city=".$dbHandle->escape($dataArr['CurrentCities'][$i])." )";
                        }
                    }
                    $complete_Query = implode(" OR ",$complete_Query);
                    $complete_Query = "(".$complete_Query;
                    $queryAppend[$key]=$complete_Query;
                    }
                   
                    if ($key == 'DontShowContacted') {
                        if ($val == 1) {
                            $showContactQuery.=" and tuser.userid not in (select UserId from LDBLeadContactedTracking where LDBLeadContactedTracking.ClientId=" . $dbHandle->escape($user) . ") ";
                        }
                    }
                    if ($key == 'DontShowViewed') {
                        if ($val == 1) {
                            $showContactQuery.=" and tuser.userid not in (select UserId from LDBLeadContactedTracking where LDBLeadContactedTracking.ClientId=" . $dbHandle->escape($user) . " and LDBLeadContactedTracking.ContactType='view') ";
                        }
                    }
                    if ($key == 'DontShowEmailed') {
                        if ($val == 1) {
                            $showContactQuery.=" and tuser.userid not in (select UserId from LDBLeadContactedTracking where LDBLeadContactedTracking.ClientId=" . $dbHandle->escape($user) . " and LDBLeadContactedTracking.ContactType='email') ";
                        }
                    }
                    if ($key == 'DontShowSmsed') {
                        if ($val == 1) {
                            $showContactQuery.=" and tuser.userid not in (select UserId from LDBLeadContactedTracking where LDBLeadContactedTracking.ClientId=" . $dbHandle->escape($user) . " and LDBLeadContactedTracking.ContactType='sms') ";
                        }
                    }
                }
            }
            /*
              Handling of Missing Prerequisite Tables
             */
            foreach ($tableMap as $tableName) {
                $tableElement = split(" ", $tableName);
                $tableName = $tableElement[0];
                $tableRequired = $tableDependencyMapping[$tableName];
                if (is_array($tableRequired)) {
                    foreach ($tableRequired as $value) {
                        if (!array_key_exists($value, $tableMap)) {
                            $tableMap[$value] = $value;
                        }
                    }
                }
            }
            
            // Append Query
            if($dataArr['underViewedLimitFlagSet']){
                /*
                  Final Query Generater
                 */
                if (count($tableBaseJoinerMap) != 0) {
                    $finalQuery = $baseQuery . implode(",", $tableMap) . " where tuser.userid not in (select UserId from LDBLeadViewCount where ViewCount >= " . $dataArr['groupViewLimit'] . " ) and " . implode(" and ", $tableBaseJoinerMap) . " and " . implode(" ) and ", $queryAppend) . ") and tuser.usergroup NOT IN (\"sums\", \"enterprise\", \"cms\", \"experts\", \"lead_operator\", \"saAdmin\", \"saCMS\", \"saContent\", \"saSales\") " /*. $showContactQuery*/;
                    //. " order by tuser.userid desc limit " . $start . "," . $end;
                } else {
                    $finalQuery = $baseQuery . implode(",", $tableMap) . " where tuser.userid not in (select UserId from LDBLeadViewCount where ViewCount >= " . $dataArr['groupViewLimit'] . " ) and " . implode(" ) and ", $queryAppend) . ") and tuser.usergroup NOT IN (\"sums\", \"enterprise\", \"cms\", \"experts\", \"lead_operator\", \"saAdmin\", \"saCMS\", \"saContent\", \"saSales\") " /*. $showContactQuery*/;
                    //. " order by tuser.userid desc limit " . $start . "," . $end;
                }
            }
        	else{    
                /*
                  Final Query Generater
                 */
                if (count($tableBaseJoinerMap) != 0) {
                    $finalQuery = $baseQuery . implode(",", $tableMap) . " where " . implode(" and ", $tableBaseJoinerMap) . " and " . implode(" ) and ", $queryAppend) . ") and tuser.usergroup NOT IN (\"sums\", \"enterprise\", \"cms\", \"experts\", \"lead_operator\", \"saAdmin\", \"saCMS\", \"saContent\", \"saSales\") " /*. $showContactQuery*/;
                    //. " order by tuser.userid desc limit " . $start . "," . $end;
                } else {
                    $finalQuery = $baseQuery . implode(",", $tableMap) . " where " . implode(" ) and ", $queryAppend) . ") and tuser.usergroup NOT IN (\"sums\", \"enterprise\", \"cms\", \"experts\", \"lead_operator\", \"saAdmin\", \"saCMS\", \"saContent\", \"saSales\") " /*. $showContactQuery*/;
                    //. " order by tuser.userid desc limit " . $start . "," . $end;
                }
        } 
                    
		//echo $finalQuery;

           error_log('HusamQ'.$finalQuery);
            /**
             * Date clause
             */
            if($dataArr['DateFilterFrom'] && $dataArr['DateFilterTo']) {
                $finalQuery .= " AND (";
                $finalQuery .= "(tUserPref.submitDate >= '".$dbHandle->escape_str($dataArr['DateFilterFrom'])."' AND tUserPref.submitDate <= '".$dbHandle->escape_str($dataArr['DateFilterTo'])."')";
                if($dataArr['includeActiveUsers']) {
                    $finalQuery .= " OR (tuser.lastlogintime >= '".$dbHandle->escape_str($dataArr['DateFilterFrom'])."' AND tuser.lastlogintime <= '".$dbHandle->escape_str($dataArr['DateFilterTo'])."')";
                }
                $finalQuery .= " ) ";
            }

            else if($dataArr['NewDateFilter']) {
                //$finalQuery .= " AND tUserPref.submitDate >= '".$dataArr['NewDateFilter']."' ";
                $finalQuery .= " AND (";
                $finalQuery .= "tUserPref.submitDate >= '".$dbHandle->escape_str($dataArr['NewDateFilter'])."'";
                if($dataArr['includeActiveUsers']) {
                    $finalQuery .= " OR tuser.lastlogintime >= '".$dbHandle->escape_str($dataArr['NewDateFilter'])."'";
                }
                $finalQuery .= " ) ";
            }

            else if($dataArr['NewDateFilter']) {
                $finalQuery .= " AND tUserPref.submitDate >= '".$dbHandle->escape_str($dataArr['NewDateFilter'])."' ";
            }

            if($dataArr[ExtraFlag] == 'studyabroad'){                   //to exclude lead who has made response for consultant in last 30min
                $endTime = date('Y-m-d H:i:s', strtotime('-15 min'));
                $finalQuery .=" AND  tUserPref.submitDate <='". $endTime."' "; 
            }
            
            //$finalQuery .= " AND ((tUserPref.submitDate >= '".$dataArr['DateFilterFrom']."' AND tUserPref.submitDate <= '".$dataArr['DateFilterTo']."') ".($dataArr['includeActiveUsers'] ? " OR  " : "").") ";
            
            //$finalQuery .= " ORDER BY tuser.lastlogintime DESC,tuser.usercreationDate DESC";
            //$finalQuery .= " ORDER BY tUserPref.SubmitDate DESC";
            
            //error_log("LDBQUERY" . $finalQuery);
            try {
                $queryStartTime = microtime(true);
                $query = $dbHandle->query($finalQuery);
                $queryRunTime = microtime(true) - $queryStartTime;
                error_log("LDBQUERY" . $finalQuery." took ".$queryRunTime);
            } catch (Exception $e) {
                $responseArray = array('error' => 'Caught DB Exception ' . $e);
                $response = array(base64_encode(json_encode($responseArray)), 'string');
                return $this->xmlrpc->send_response($response);
            }
            
            $userIds = array();
            $k = 0;
            $return = array();
            $user_ids_list = array();
            
            foreach ($query->result() as $row) {
                //$userIds[$k] = $row->userid;
                $user_ids_list[$row->userid] = strtotime($row->SubmitDate);
                //$k++;
            }
            
            arsort($user_ids_list);
            $userIds = array_keys($user_ids_list);
            
            if($showContactQuery) {
            	 
            	$showContactQuery = ltrim($showContactQuery,"and tuser.userid not in");
            	$showContactQuery = str_replace("and tuser.userid not in", "UNION", $showContactQuery);
            	$contacted_user_ids = array();

             try {

             	$startTime = microtime_float();
             	$query = $dbHandle->query($showContactQuery);
             	error_log("LDBQUERYCONTACTED" . $showContactQuery." took ".(microtime_float() - $startTime));
             	
             	$startTime = microtime_float();
             	$result_array = $query->result_array();
             	foreach ($result_array as $row) {
             		$contacted_user_ids[$row['UserId']] = $row['UserId'];
             	}
             	//error_log("LDBQUERYCONTACTEDJHR1 RESULT ". print_r($contacted_user_ids,true));
             	error_log("LDBQUERYCONTACTEDJHR1 took ".(microtime_float() - $startTime));
             	$startTime = microtime_float();
             	$final_user_list = array();
             	if(count($contacted_user_ids) >0 && count($userIds)>0) {
             		foreach ($userIds as $key=>$user_id) {
             			if(!$contacted_user_ids[$user_id]) {
             				//unset($userIds[$key]);
             				$final_user_list[] = $user_id;
             			}
             		} 
			
				$userIds = $final_user_list;
             	$final_user_list = array(); 
 
             	}
             	
             	//if(count($final_user_list) >0) {
             			
             	//} 
             	
             	error_log("LDBQUERYCONTACTEDJHR2 took ".(microtime_float() - $startTime));

             } catch (Exception $e) {
             	// do nothing
             }
              
            }
            
            //rsort($userIds);
            
            $LibOject = $this->load->library('enterprise/EnterpriseLib');       //to exclude response on consultant profile
            $userIds = $LibOject->excludeLDBList($dataArr[ExtraFlag], $userIds); 
            unset($LibOject);
            

            if (empty($end)) {
                $return = $userIds;
            }
            else {
                for($i=$start;$i<$start+$end && $i<count($userIds);$i++){
                    $return[$i-$start] = $userIds[$i];
                }
            }
            
            //$totalRows = $query->num_rows();
            $totalRows = count($userIds);
            
            error_log("LDBX rows = $totalRows key length = ".strlen(json_encode($userIds))); //1mb key
            
            if (!empty($search_key)) {
                $cache->store($search_key,  base64_encode(gzcompress(json_encode($userIds))),1800);
            }
            
            $searchResult = array('requestTime' => $requestTime, 'userIds' => $return, 'totalRows' => $totalRows);
            
            //tracking LDB query execution time
            $totalExecutionTime = microtime(true) - $executionStartTime;
            $this->updateLDBQueryLog($finalQuery, $queryRunTime, $totalExecutionTime);
            
            return $searchResult;

        }
        else{
           
            $userIds = json_decode(gzuncompress(base64_decode($cache->get($search_key))),true);
            error_log("LDBX memcache found search key ".$search_key);
            $return = array();
            $totalRows = count($userIds);
            
            if (empty($end)) {
                $return = $userIds;
            }
            else {
                for($i=$start;$i<$start+$end && $i<count($userIds);$i++){
                    $return[$i-$start] = $userIds[$i];
                }
            }
            
            error_log("LDBX rows = $totalRows");
            $searchResult = array('requestTime' => $requestTime, 'userIds' => $return, 'totalRows' => $totalRows);
            return $searchResult;
        }
    }

    function createResultSet($userIds, $user='12',$migrationFlag=false,$extraflag) {
       
       
        if(!is_object($this->dbLibObj)) {
            $this->dbLibObj = DbLibCommon::getInstance('LDB');
        }
        
        if(count($userIds) == 0 || empty($userIds)) {
			return array();
		}
                
        $userIdList = implode(',', $userIds);

        $userIdList = trim($userIdList);
        if($userIdList == ''){
            return array();
        }
        
        $result = array();
        
        $queryCmd = "select tuser.*,tuserflag.*,sum(if(l.id is null,0,1)) as `ContactDisplay`,city_name as `CurrentCity`, localityName, 
            DATE_FORMAT(usercreationDate,'%D %b %Y') as CreationDate, DATE_FORMAT(lastlogintime,'%D %b %Y') as LastLoginDate
            from tuser left join countryCityTable on tuser.city = countryCityTable.city_id
            left join localityCityMapping on tuser.Locality = localityCityMapping.localityId,
            tuserflag left join LDBLeadContactedTracking l
            on tuserflag.userid = l.userid and l.clientid = ?
            where tuser.userid=tuserflag.userid and tuser.userid in (" . $userIdList . ") group by tuser.userid";
        //error_log($queryCmd);
        $dbHandle = $this->_loadDatabaseHandle();
        $query = $dbHandle->query($queryCmd, array($user));
        unset($queryCmd);
        
        
        $tuserFlagData = array();
        foreach ($query->result_array() as $baseuserData) {
            $tuserFlagData[$baseuserData["userid"]] = $baseuserData;
        }
        
        unset($query);
        
        $tuserPrefData = array();

        if($migrationFlag){
            if( $extraflag == 'national'){
                $queryPrefX = "select *,DATE_FORMAT(TimeOfStart,'%Y') as YearOfStart from tUserPref where UserId in ($userIdList) and Status='live' order by userid desc,SubmitDate asc";
            }else{
                $queryPrefX = "select *,DATE_FORMAT(TimeOfStart,'%Y') as YearOfStart from tUserPref where UserId in ($userIdList) and Status='live' order by userid desc,SubmitDate asc";
            }
        }else{
            $queryPrefX = "select *,DATE_FORMAT(TimeOfStart,'%Y') as YearOfStart from tUserPref where UserId in ($userIdList) and Status='live' order by userid,SubmitDate desc";
        }

        //$startX = microtime_float();
        $queryPref = $dbHandle->query($queryPrefX);
       
        unset($queryPrefX);
        //error_log("ASHISH MISHRA SQL1: " . $queryPrefX. " finished in " . (microtime_float() - $startX) . "sec.");
        $currentUserID = 0;
        foreach ($queryPref->result_array() as $baseData) {
			
            if ($currentUserID != $baseData["UserId"]) {
                $currentUserID = $baseData["UserId"];
                $coursePrefArray = array();
            }
            
            if ($baseData['ExtraFlag'] == 'testprep') {
                $queryCmd1 = "select distinct tUserPref_testprep_mapping.blogid as testPrepCourseId,blogTitle as CourseName from blogTable,tUserPref_testprep_mapping
                    where tUserPref_testprep_mapping.blogid = blogTable.blogid AND
                    blogTable.status != 'draft' AND
                    tUserPref_testprep_mapping.prefid=?";
                //$startX = microtime_float();
                $query1 = $dbHandle->query($queryCmd1, array($baseData['PrefId']));
                unset($queryCmd1);
                //error_log("ASHISH MISHRA SQL2: " . $queryCmd1 . " finished in " . (microtime_float() - $startX) . "sec.");
                
                foreach ($query1->result_array() as $CoursePrefData) {
                    $coursePrefArray[] = $CoursePrefData;
                }
                
                unset($query1);
                
            } else {
				
                if(!$migrationFlag || $baseData['ExtraFlag'] == 'studyabroad' ){

                    $queryCmd1 = "select
                        tUserSpecializationPref.*,tCourseSpecializationMapping.*, categoryBoardTable.name  as `CategoryName`
                        from tUserSpecializationPref, tCourseSpecializationMapping ,categoryBoardTable
                        where tUserSpecializationPref.SpecializationId=tCourseSpecializationMapping.SpecializationId and boardId=CategoryId
                        and tUserSpecializationPref.Status='live' and tUserSpecializationPref.PrefId=?";
                    //$startX = microtime_float();
                    $query1 = $dbHandle->query($queryCmd1, array($baseData['PrefId']));
                    //error_log("ASHISH MISHRA SQL3: " . $queryCmd1 . " finished in " . (microtime_float() - $startX) . "sec.");
                    unset($queryCmd1);
                    
                    foreach ($query1->result_array() as $CoursePrefData) {
                        $coursePrefArray[] = $CoursePrefData;
                    }
                    
                    unset($query1);
                    
                    if (count($coursePrefArray) == 0) {
    					
                        if (!empty($baseData['DesiredCourse'])) {
    						
                            $queryCmdx = "select tCourseSpecializationMapping.*,
                                categoryBoardTable.name as `CategoryName`
                                from tCourseSpecializationMapping left join categoryBoardTable
                                on boardId=CategoryId where SpecializationId=?";
                            //$startX = microtime_float();
                            $queryx = $dbHandle->query($queryCmdx, array($baseData['DesiredCourse']));
                            unset($queryCmdx);
                            //error_log("ASHISH MISHRA SQL4: " . $queryCmdx . " finished in " . (microtime_float() - $startX) . "sec.");
                            foreach ($queryx->result_array() as $CoursePrefData) {
                                $coursePrefArray[] = $CoursePrefData;
                            }
                            
                            unset($queryx);
                            
                        }
                        
                        unset($queryx);
                        
                    }
                    
                }
            }
            
            $baseData['SpecializationPref'] = $coursePrefArray;
            
            if ($baseData['ExtraFlag'] == 'studyabroad') {
				
                    if(trim($baseData['abroad_subcat_id'])) {
						
                        $queryCmd1 = "SELECT name FROM categoryBoardTable WHERE boardId = ? AND flag = 'studyabroad' AND isOldCategory = '0'";
                        //$startX = microtime_float();
                        $query1 = $dbHandle->query($queryCmd1, array($baseData["abroad_subcat_id"]));
                        //error_log("ASHISH MISHRA SQL2SA: " . $queryCmd1 . " finished in " . (microtime_float() - $startX) . "sec.");
                        unset($queryCmd1);
                        
                        $courseSpecArraySA = '';
                        foreach ($query1->result_array() as $CourseSpecData) {
                            $courseSpecArraySA = $CourseSpecData['name'];
                        }
                        
                        unset($query1);
                        $baseData['SpecializationPref'][0]['SpecializationName'] = $courseSpecArraySA;
                        
                    } else {
                        $baseData['SpecializationPref'][0]['SpecializationName'] = null;
                    }
                    
            }
            
            $queryCmd2 = "select t.*,cn.`name` as `CountryName`,
                s.state_name as `StateName`,
                ct.city_name as `CityName`,
                l.localityName as `LocalityName` from tUserLocationPref t
                left join countryTable cn on cn.countryId=t.countryId
                left join stateTable s on s.state_id=t.StateId
                left join countryCityTable ct on ct.city_id=t.CityId
                left join localityCityMapping l on l.localityId=t.LocalityId
                where t.Status='live' and t.PrefId=?";
            //$startX = microtime_float();
            $query2 = $dbHandle->query($queryCmd2, array($baseData['PrefId']));
            //error_log("ASHISH MISHRA SQL5: " . $queryCmd2 . " finished in " . (microtime_float() - $startX) . "sec.");
            unset($queryCmd2);
            
            $locationPrefArray = array();
            foreach ($query2->result_array() as $LocationPrefData) {
                $locationPrefArray[] = $LocationPrefData;
            }

            unset($query2);
            
            $baseData['LocationPref'] = $locationPrefArray;
            //error_log("APITEST setting PrefData with ".print_r($baseData,true));
            $tuserPrefData[$baseData['UserId']]['PrefData'][] = $baseData;
        }
        
        $queryCmd3 = "select tUserEducation.*, institute_name, countryCityTable.city_name, DATE_FORMAT(CourseCompletionDate,'%b %Y')
            as Course_CompletionDate from tUserEducation
            left join institute on institute.institute_id=tUserEducation.InstituteId and institute.status='live'
            left join institute_location_table on institute_location_table.institute_id=institute.institute_id
            and institute_location_table.status='live'
            left join countryCityTable on countryCityTable.city_id=institute_location_table.city_id
            where tUserEducation.Status='live' and tUserEducation.UserId in ($userIdList)
            order by userid,field(tUserEducation.Level, 'PG', 'UG', '12','10') desc";
            
        //$startX = microtime_float();
        $query3 = $dbHandle->query($queryCmd3);
        //error_log("ASHISH MISHRA SQL6: " . $queryCmd3 . " finished in " . (microtime_float() - $startX) . "sec.");
        unset($queryCmd3);
        
        $educationArray = array();
        $currentUserID = 0;
        foreach ($query3->result_array() as $EducationData) {
            $educationArray[$EducationData["UserId"]][] = $EducationData;
        }

        unset($query3);
        //$baseuserData['EducationData'] = $educationArray;

        if(!$migrationFlag){
            $queryCmd4 = "select *,DATE_FORMAT(ContactDate,'%D %b %Y') as FormattedContactDate from LDBLeadContactedTracking
                where LDBLeadContactedTracking.ClientId=? and LDBLeadContactedTracking.UserId in ($userIdList)
                order by userid,contactdate desc";
            
            //$startX = microtime_float();
           
            $query4 = $dbHandle->query($queryCmd4, array($user));
           
            //error_log("ASHISH MISHRA SQL7: " . $queryCmd4 . " finished in " . (microtime_float() - $startX) . "sec.");
            unset($queryCmd4);
            
            $viewArray = array();
            foreach ($query4->result_array() as $ViewData) {
                $viewArray[$ViewData['UserId']][$ViewData['ContactType']][] = $ViewData['FormattedContactDate'];
            }
            
            unset($query4);
            
            //$baseuserData['ContactData'] = $viewArray;
            $dbHandleMaster = $this->_loadDatabaseHandle('write');
            $queryCmd5 = "select * from LDBLeadViewCount where UserId in ($userIdList)";
            $query5 = $dbHandleMaster->query($queryCmd5);
            unset($queryCmd5);
            
            $LDBLeadViewCountDATA = array();
            foreach ($query5->result_array() as $row) {
                $LDBLeadViewCountDATA[$row["UserId"]]['ViewCountArray'][$row['DesiredCourse']] = $row;
            }
            
            unset($query5);
        }
        

        foreach($userIds as $key) {     
        //foreach ($tuserFlagData as $key => $val) {
            $val = $tuserFlagData[$key];
            $result[$key] = $val;
            if(array_key_exists($key,$tuserPrefData))
                $result[$key]["PrefData"] = $tuserPrefData[$key]["PrefData"];
                $result[$key]["EducationData"] = array_key_exists($key,$educationArray)?$educationArray[$key]:array();;
                if(!$migrationFlag){
                    $result[$key]["ContactData"] = array_key_exists($key,$viewArray)?$viewArray[$key]:array();
                    $result[$key]["ViewCountArray"] = isset($LDBLeadViewCountDATA[$key]['ViewCountArray'])?$LDBLeadViewCountDATA[$key]['ViewCountArray']:array();
                }
        }
        
        //error_log("ASHISH MISHRA CREATE RESULT RETURNINGXXX ".print_r(array_keys($result),true));
        return $result;
    }

    function sgetUserDetails($request,$migrationFlag=false,$userIdArray,$extraflag) {
        if($migrationFlag){
            $userIdList = $userIdArray;
        }else{
            $parameters = $request->output_parameters();
            $appID = $parameters['0'];
            $userIdList = $parameters['1'];
        }
        
        $userData = $this->createResultSet(explode(",", $userIdList),12,$migrationFlag,$extraflag);
        //return $this->xmlrpc->send_response(json_encode($userData));
        if($migrationFlag){
            return base64_encode(json_encode($userData));
        }else{
            return $this->xmlrpc->send_response(base64_encode(json_encode($userData)));
            
        }
    }

    function sgetSpecializationList($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $courseName = $parameters['1'];
        $scope = $parameters['2'];
        $this->load->model('LdbModel');
        $list = $this->LdbModel->getSpecializationList($courseName, $scope);
        return $this->xmlrpc->send_response(json_encode($list));
    }

    function sgetSpecializationListByParentId($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $parentId = $parameters['1'];
        $this->load->model('LdbModel');
        $list = $this->LdbModel->getSpecializationListByParentId($parentId);
        return $this->xmlrpc->send_response(json_encode($list));
    }

    function sgetCourseList($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $categoryId = $parameters['1'];
        $scope = $parameters['2'];
        $this->load->model('LdbModel');
        $list = $this->LdbModel->getCourseList($categoryId, $scope);
        return $this->xmlrpc->send_response(json_encode($list));
    }

    function sgetCourseForCriteria($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $categoryId = $parameters['1'];
        $scope = $parameters['2'];
        $courseLevel = $parameters['3'];
        // error_log('ASHISH :: in LDB COURSE ' . print_r($parameters, true));
        $this->load->model('LdbModel');
        $list = $this->LdbModel->getCourseForCriteria($categoryId, $scope, $courseLevel);
        return $this->xmlrpc->send_response(json_encode($list));
    }

    function sgetCourseListByGroup($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $categoryId = $parameters['1'];
        $groupId = $parameters['2'];
        $this->load->model('LdbModel');
        $list = $this->LdbModel->getCourseListByGroup($categoryId, $groupId);
        return $this->xmlrpc->send_response(json_encode($list));
    }

    function sgetGroupList($request) {
        $parameters = $request->output_parameters();
        $this->load->model('LdbModel');
        $list = $this->LdbModel->getGroupList();
        return $this->xmlrpc->send_response(json_encode($list));
    }

    function sgetCityStateList($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $this->load->model('LdbModel');
        $list = $this->LdbModel->getCityStateList();
        return $this->xmlrpc->send_response(json_encode($list));
    }

    function trackSearch($user, $dataArr, $totalRows) {
        $searchTrackingColumnList = array(
            'Specialization' => '',
            'ModeFullTime' => '',
            'ModePartTime' => '',
            'ModeDistance' => '',
            'CurrentLocation' => '',
            'PreferredLocation' => '',
            'DegreePrefAny' => '',
            'DegreePrefAICTE' => '',
            'DegreePrefUGC' => '',
            'DegreePrefInternational' => '',
            'UGCourse' => '',
            'UGInstitute' => '',
            'GraduationCompletedFrom' => '',
            'GraduationCompletedTo' => '',
            'MinGradMarks' => '',
            'IncludeResultsAwaited' => '',
            'MinXIIMarks' => '',
            'XIIStream' => '',
            'ExamScore' => '',
            'Gender' => '',
            'MinAge' => '',
            'MaxAge' => '',
            'MinExp' => '',
            'MaxExp' => '',
            'DontShowContacted' => '',
            'LocationAndOr' => '',
            'DateFilter' => '',
            'DesiredCourse' => '',
            'Locality' => '',
            'UserFundsOwn' => '',
            'UserFundsBank' => '',
            'UserFundsNone' => '',
            'otherFundingDetails' => '',
            'DesiredCourseId' => ''
        );
        foreach ($searchTrackingColumnList as $columName => $value) {
            error_log($columName);
            if (array_key_exists($columName, $dataArr)) {
                if ($columName == 'PreferredLocation') {
                    error_log("I am here");
                    $i = 0;
                    foreach ($dataArr[$columName] as $column) {
                        // error_log("am here" . print_r(json_decode(base64_decode($column), true), true));
                        $dataArr[$columName][$i] = json_decode(base64_decode($column), true);
                        $i++;
                        // error_log("an here" . print_r($dataArr[$columName], true));
                    }
                }
                $searchTrackingColumnList[$columName] = $this->getCsv($dataArr[$columName]);
            }
        }
        $searchTrackingColumnList['ClientId'] = $user;
       $dbHandle = $this->_loadDatabaseHandle('write');
        // error_log("LDBSearchTracking " . print_r($searchTrackingColumnList, true));
        $searchTrackingColumnList['ResultCount'] = $totalRows;
        $insertQuery = $dbHandle->insert_string('LDBSearchTracking', $searchTrackingColumnList);
        $dbHandle->query($insertQuery);
    }

    function getCsv($val) {
        if (!is_array($val)) {
            return $val;
        } else {
            $result = array();
            foreach ($val as $key => $value) {
                // error_log($key . " " . print_r($value, true));
                if (is_numeric($key)) {
                    $result[] = $this->getCsv($value);
                } else {
                    $result[] = json_encode($val);
                    break;
                }
                // error_log(print_r($result, true));
            }
            return implode("#!#", $result);
        }
    }

    function sUpdateContactViewed($request) {
        global $excludeViewLimitForClients;
        $excludedClientIds = $excludeViewLimitForClients;
        
        $parameters = $request->output_parameters(FALSE, FALSE);
        $InsertArray['ClientId'] = $parameters['1'];
        $InsertArray['UserId'] = $parameters['2'];
        $InsertArray['ContactType'] = $parameters['3'];
        $InsertArray['SubscriptionId'] = $parameters['4'];
        $InsertArray['CreditConsumed'] = $parameters['5'];
        $InsertArray['activity_flag'] = $parameters['6'];
        $UserDataArray = json_decode($parameters['8'], true);

        $InsertMappingArray['userId'] = $parameters['2'];
        $InsertMappingArray['clientId'] = $parameters['1'];
        $InsertMappingArray['StreamId'] = $UserDataArray[0]['StreamId'];
        if (isset($UserDataArray[0]['SubStreamId'])) {
            $InsertMappingArray['SubStreamId'] = $UserDataArray[0]['SubStreamId'];
        }
        else {
            $InsertMappingArray['SubStreamId'] = 0;
        }
        $InsertMappingArray['ContactType'] = $parameters['3'];
        $InsertMappingArray['FlagType'] = $parameters['6'];
        
        $dbHandle = $this->_loadDatabaseHandle('write');
        
        $insertQuery = $dbHandle->insert_string('LDBLeadContactedTracking', $InsertArray);
        $dbHandle->query($insertQuery);
        unset($InsertArray['activity_flag']);
        $insertId = $dbHandle->insert_id();
        if(!empty($InsertMappingArray['StreamId'])){
            $insertMappingQuery = $dbHandle->insert_string('UserProfileMappingToClient', $InsertMappingArray);
            $insertMappingQuery .= " on duplicate key update submitTime = CURRENT_TIMESTAMP "; 
            $dbHandle->query($insertMappingQuery);
            $insertIdMapping = $dbHandle->insert_id();
        }
        
        if(!in_array($InsertArray['ClientId'], $excludedClientIds)) {
            
            //Insert Data in LDBLeadViewCount table
            $LeadViewArray = array();
            $LeadViewArray['UserId'] = $InsertArray['UserId'];
            $LeadViewArray['ContactType'] = $InsertArray['ContactType'];
            $LeadViewArray['CreditConsumed'] = $InsertArray['CreditConsumed'];
            $LeadViewArray['actual_course_id'] = $parameters['7'];
            $LeadViewArray['StreamId'] = $UserDataArray[0]['StreamId'];
            $LeadViewArray['SubStreamId'] = $UserDataArray[0]['SubStreamId'];
            $this->insertLeadVIewCountData($LeadViewArray);

        }
        
        $queryCmd = "select displayname, firstname, mobile, landline, email, userid from tuser where userid=?";
        $query = $dbHandle->query($queryCmd, array($InsertArray['UserId']));
        $resultArray = $query->result_array();
        $queryCmd = "select *,DATE_FORMAT(ContactDate,'%D %b %Y') as FormattedContactDate from LDBLeadContactedTracking where LDBLeadContactedTracking.UserId=? order by ContactDate desc";
        $query = $dbHandle->query($queryCmd, array($InsertArray['UserId']));
        $resultArray[0]['viewArray'] = $query->result_array();
        $resultArray[0]['insertId'] = $insertId;
        return $this->xmlrpc->send_response(json_encode($resultArray));
    }
    
    function insertLeadVIewCountData($LeadViewArray) {
        
        $dbHandle = $this->_loadDatabaseHandle('write');
        
        if(!empty($LeadViewArray['actual_course_id'])) {
            
            $LDBLeadViewCountInsert = array();
            $LDBLeadViewCountInsert['DesiredCourse'] = $LeadViewArray['actual_course_id'];
            $LDBLeadViewCountInsert['Flag'] = 'national';
            
        } 
        else if($LeadViewArray['StreamId'] != '') {
            $LDBLeadViewCountInsert['StreamId'] = $LeadViewArray['StreamId'];
            if(isset($LeadViewArray['SubStreamId'])){
                $LDBLeadViewCountInsert['SubStreamId'] = $LeadViewArray['SubStreamId'];
            }
            else {
                $LDBLeadViewCountInsert['SubStreamId'] = 0;
            }
            $LDBLeadViewCountInsert['Flag'] = 'national';
        }
        else {
            
            // Get user desired course for Lead View Count
            $LDBLeadViewCountInsert = $this->getDesiredCourseDetails($LeadViewArray['UserId']);
            
        }
            
        $LDBLeadViewCountInsert['UserId'] = $LeadViewArray['UserId'];
        $LDBLeadViewCountInsert['UpdateTime'] = date("Y-m-d H:i:s");
        if ((($LeadViewArray['ContactType'] == 'view') || ($LeadViewArray['ContactType'] == 'csv')) && !empty($LeadViewArray['CreditConsumed'])) {
            $LDBLeadViewCountInsert['ViewCount'] = 1;               
            $insertQueryforLDBLeadViewCount = $dbHandle->insert_string('LDBLeadViewCount', $LDBLeadViewCountInsert);
            $insertQueryforLDBLeadViewCount.=" on duplicate key update ViewCount=ViewCount+1, UpdateTime = '".$LDBLeadViewCountInsert['UpdateTime']."'";
        } else if ($LeadViewArray['ContactType'] == 'email') {
            $LDBLeadViewCountInsert['EmailCount'] = 1;
            $insertQueryforLDBLeadViewCount = $dbHandle->insert_string('LDBLeadViewCount', $LDBLeadViewCountInsert);
            $insertQueryforLDBLeadViewCount.=" on duplicate key update EmailCount=EmailCount+1, UpdateTime = '".$LDBLeadViewCountInsert['UpdateTime']."'";
        } else if ($LeadViewArray['ContactType'] == 'sms') {
            $LDBLeadViewCountInsert['SmsCount'] = 1;
            $insertQueryforLDBLeadViewCount = $dbHandle->insert_string('LDBLeadViewCount', $LDBLeadViewCountInsert);
            $insertQueryforLDBLeadViewCount.=" on duplicate key update SmsCount=SmsCount+1, UpdateTime = '".$LDBLeadViewCountInsert['UpdateTime']."'";
        }
        if(!empty($insertQueryforLDBLeadViewCount)) {
            $dbHandle->query($insertQueryforLDBLeadViewCount);
        }
    }
    
    function getDesiredCourseDetails($userId) {
        $this->load->model('ldbmodel');
        $userDesiredCourseDetails = $this->ldbmodel->getDesiredCourseDetailsbyUserId($userId);
        
        $LDBLeadViewCountInsert = array();
        if(!empty($userDesiredCourseDetails)) {
            $LDBLeadViewCountInsert['DesiredCourse'] = $userDesiredCourseDetails['DesiredCourse'];
            if($userDesiredCourseDetails['ExtraFlag'] == 'testprep') {
                $Flag = 'testprep';
            } else if($userDesiredCourseDetails['ExtraFlag'] == 'studyabroad') {
                $Flag = 'studyabroad';
            } else {
                $Flag = 'national';
            }
            $LDBLeadViewCountInsert['Flag'] = $Flag;
        }
        return $LDBLeadViewCountInsert;
    }

    function sgetCreditToConsume($request) {
        $parameters = $request->output_parameters(FALSE, FALSE);
        $userArray = json_decode($parameters['1'], true);
		$action = $parameters['2'];
        $clientId = $parameters['3'];
        $ExtraFlag = $parameters['4'];
        $excludeResponses = $parameters['5'];
        $course_id = $parameters['6'];
        $creditToBeConsumed = json_decode($parameters['7'], true);
        
        $creditConsumeArray = array();
        $viewedUsers = $this->removeAlreadyViewedUser($userArray, $clientId);
        $responseUsers = array();
        $this->load->model('ldbmodel');
        if($excludeResponses) {
            $responseUsers = $this->ldbmodel->getResponsesForClient($clientId, $userArray);
        }
        if ($action == 'sms') {
            $ndncUsers = $this->removeNdncUser($userArray);
            $countNdnc = count($ndncUsers);
            $reqestArray = array();
            foreach ($userArray as $user) {
                if (!in_array($user, $viewedUsers) && !in_array($user, $ndncUsers) && !in_array($user, $responseUsers)) {
                    $reqestArray[] = $user;
                } else if (in_array($user, $ndncUsers)) {
                    $creditConsumeArray[$user] = array($user => 0 ,"countNdnc" => 1);
                } else if ((in_array($user, $viewedUsers) || in_array($user, $responseUsers)) && !in_array($user, $ndncUsers)) {
                    $creditConsumeArray[$user] = array($user => 0 ,"countNdnc" => 0);
                }
            }
            
            if(count($reqestArray) > 0){
                if(is_array($creditToBeConsumed) && count($creditToBeConsumed) > 0){
                    foreach ($reqestArray as $user) {
                        if($creditToBeConsumed[$user]['sms'] != '')
                            $creditConsumeArray[$user] = array($user => $creditToBeConsumed[$user]['sms'], "countNdnc" => 0);
                    }
                }
                else {
                    $retArr = $this->ldbmodel->getCreditToConsume($reqestArray, $action, $ExtraFlag, $course_id);
                    foreach ($retArr as $arr) {
                        $creditConsumeArray[$arr["userid"]] = array($arr["userid"] => $arr["deductcredit"], "countNdnc" => 0);
                    }
                }
            }
        } 
        else {
            
            $reqestArray = array();
            foreach ($userArray as $user) {
                if (!in_array($user, $viewedUsers) && !in_array($user, $responseUsers)) {
                    $reqestArray[] = $user;
                } else if ((in_array($user, $viewedUsers) || in_array($user, $responseUsers)) && !in_array($user, $ndncUsers)) {
                    $creditConsumeArray[$user] = array($user => 0 ,"countNdnc" => 0);
                }
            }
            
            if(count($reqestArray) > 0){

                if(is_array($creditToBeConsumed) && count($creditToBeConsumed) > 0){
                    foreach ($reqestArray as $user) {
                        if($action == 'view' && $creditToBeConsumed[$user]['view'] != '')
                            $creditConsumeArray[$user] = array($user => $creditToBeConsumed[$user]['view'], "countNdnc" => null);
                        else if($action == 'email' && $creditToBeConsumed[$user]['email'] != '')
                            $creditConsumeArray[$user] = array($user => $creditToBeConsumed[$user]['email'], "countNdnc" => null);
                    }
                }
                else {
                    $retArr = $this->ldbmodel->getCreditToConsume($reqestArray, $action, $ExtraFlag, $course_id);
                    foreach ($retArr as $arr) {
                        $creditConsumeArray[$arr["userid"]] = array($arr["userid"] => $arr["deductcredit"], "countNdnc" => null);
                    }
                }
            }
        }

        unset($retArr);
        unset($userArray);
        unset($ndncUsers);
        unset($responseUsers);
        unset($viewedUsers);
        return $this->xmlrpc->send_response(json_encode($creditConsumeArray));
    }

    function removeNdncUser($userArray) {

        $queryCmd = "select userId from tuserflag where userId in (" . implode(",", $userArray) . ") and isNDNC='YES'";
        error_log("ndnc query" . $queryCmd);
        $dbHandle = $this->_loadDatabaseHandle();
        $query = $dbHandle->query($queryCmd);
        $resultArray = $query->result_array();
        $ndncUsers = array();
        foreach ($resultArray as $result) {
            $ndncUsers[] = $result['userId'];
        }

        return $ndncUsers;
    }

    function removeAlreadyViewedUser($userArray, $clientId) {
        
        if(!(is_array($userArray) && count($userArray)>0 && intval($clientId)>0)) {
			return array();
		}
		
        if(!empty($userArray)){
            $queryCmd = "select LDBLeadContactedTracking.UserId from LDBLeadContactedTracking where LDBLeadContactedTracking.UserId in (" . implode(",", $userArray) . ") and ContactType='view' and ClientId=?";
            $dbHandle = $this->_loadDatabaseHandle('write');
            $query = $dbHandle->query($queryCmd, array($clientId));
            $resultArray = $query->result_array();
            $viewedUsers = array();
            foreach ($resultArray as $result) {
                $viewedUsers[] = $result['UserId'];
            }
            return $viewedUsers;
        }
        
    }

    function sAddCoursesToGroup($request) {
        $parameters = $request->output_parameters(FALSE, FALSE);
        $groupId = $parameters[1];
        $courseIdArray = json_decode($parameters[2]);
        $ExtraFlag = json_decode($parameters[3]);
        $this->load->model('LdbModel');
        $i = 0;
        foreach ($courseIdArray as $courseId) {
            $result = $this->LdbModel->addCourseToGroup($groupId, $courseId, $ExtraFlag[$i]);
            $i++;
        }
        return $this->xmlrpc->send_response(json_encode($result));
    }

    function sRemoveCoursesFromGroup($request) {
        $parameters = $request->output_parameters(FALSE, FALSE);
        $groupId = $parameters[1];
        $courseIdArray = json_decode($parameters[2]);
        $ExtraFlag = json_decode($parameters[3]);
        $this->load->model('LdbModel');
        $i = 0;
        foreach ($courseIdArray as $courseId) {
            $result = $this->LdbModel->removeCourseFromGroup($groupId, $courseId, $ExtraFlag[$i]);
            $i++;
        }
        return $this->xmlrpc->send_response(json_encode($result));
    }

    function saddGroupCreditConsumptionPolicy($request) {
        $parameters = $request->output_parameters();
        $groupId = $parameters[1];
        $viewCredits = $parameters[2];
        $emailCredits = $parameters[3];
        $smsCredits = $parameters[4];
        $shared_view_limit = $parameters[5];
        $premimum_view_credits = $parameters[6];
        $premimum_view_limit = $parameters[7];
        $email_limit = $parameters[8];
        $sms_limit = $parameters[9];
        $view_limit = $parameters[10];
        $this->load->model('LdbModel');
        $result = $this->LdbModel->addGroupCreditConsumptionPolicy($groupId, 'view', $viewCredits);
        $result = $this->LdbModel->addGroupCreditConsumptionPolicy($groupId, 'email', $emailCredits);
        $result = $this->LdbModel->addGroupCreditConsumptionPolicy($groupId, 'sms', $smsCredits);
        $result = $this->LdbModel->addGroupCreditConsumptionPolicy($groupId, 'shared_view_limit', $shared_view_limit);
        $result = $this->LdbModel->addGroupCreditConsumptionPolicy($groupId, 'premimum_view_cr', $premimum_view_credits);
        $result = $this->LdbModel->addGroupCreditConsumptionPolicy($groupId, 'premimum_view_limit', $premimum_view_limit);
        $result = $this->LdbModel->addGroupCreditConsumptionPolicy($groupId, 'email_limit', $email_limit);
        $result = $this->LdbModel->addGroupCreditConsumptionPolicy($groupId, 'sms_limit', $sms_limit);
        $result = $this->LdbModel->addGroupCreditConsumptionPolicy($groupId, 'view_limit', $view_limit);
        return $this->xmlrpc->send_response(json_encode($result));
    }

    function populateLDBLeadViewCount() {
        $dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = "select UserId, ContactType, count(*) as count from LDBLeadContactedTracking group by UserId,ContactType order by 1";
        $query = $dbHandle->query($queryCmd);
        $result = $query->result_array();
        $pastUserId = '';
        $InsertArray = array();
        foreach ($result as $resultelement) {
            if ($pastUserId != '' && ($pastUserId != $resultelement['UserId'])) {
                $insertstring = $dbHandle->insert_string('LDBLeadViewCount', $InsertArray);
                $dbHandle->query($insertstring);
                reset($InsertArray);
            }
            $pastUserId = $resultelement['UserId'];
            if ($resultelement['ContactType'] == 'view') {
                $InsertArray['ViewCount'] = $resultelement['count'];
            }
            if ($resultelement['ContactType'] == 'email') {
                $InsertArray['EmailCount'] = $resultelement['count'];
            }
            if ($resultelement['ContactType'] == 'sms') {
                $InsertArray['SmsCount'] = $resultelement['count'];
            }
            $InsertArray['UserId'] = $pastUserId;
        }
    }

    function sgetCreditConsumedForAction($request) {
        $parameters = $request->output_parameters();
        $clientId = $parameters[0];
        $userIdCSV = $parameters[1];
        $action = $parameters[2];
        //error_log("R2R  input".print_r($parameters,true));
        $dbHandle = $this->_loadDatabaseHandle();
        if($userIdCSV != ''){
            $queryCmd = "select userid,sum(CreditConsumed) as CreditConsumed from LDBLeadContactedTracking where UserId in ($userIdCSV) and ClientId=? and ContactType=? group by userid";
            //error_log("R2R".print_r($queryCmd,true));
            $query = $dbHandle->query($queryCmd, array($clientId, $action));
            $result = $query->result_array();
            return $this->xmlrpc->send_response(json_encode($result));
        } else {
            return null;
        }
    }

    function getCourseListByGroupTestPrep($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $groupId = $parameters['1'];
        $list = array();
        $this->load->model('LdbModel');
        error_log(" sql getCourseListByGroupTestPrep::" . print_r($parameters, true));
        $list = $this->LdbModel->getCourseListByGroupTestPrep($groupId);
        return $this->xmlrpc->send_response(json_encode($list));
    }

    function getGroupForAcourse($request) {
        $parameters = $request->output_parameters();
        $courseId = $parameters['0'];
        $extraFlag = $parameters['1'];
        $dbHandle = $this->_loadDatabaseHandle();
        $query = 'select * from tCourseGrouping where status="live" and courseId=? and extraFlag=?';
        $query = $dbHandle->query($query, array($courseId, $extraFlag));
        $result = $query->result_array();
        return $this->xmlrpc->send_response(json_encode($result));
    }

    function getCreditConsumedByGroup($request) {
        $parameters = $request->output_parameters();
        $groupId = $parameters['0'];
        $dbHandle = $this->_loadDatabaseHandle();
        $query = 'select * from tGroupCreditDeductionPolicy where actionType!="" and status="live" and groupId=?';
        $query = $dbHandle->query($query, array($groupId));
        $result = $query->result_array();
        return $this->xmlrpc->send_response(json_encode($result));
    }

    /* 	function isLDBUser($request){
      $parameters = $request->output_parameters();
      $userId = $parameters['0'];
      $this->listingconfig->getDbConfig($appID,$dbConfig);
      $dbHandle = $this->load->database($dbConfig,TRUE);
      $query = "select isLDBUser from tuserflag where userId=$userId";
      $query = $dbHandle->query($query);
      $result = $query->result_array();
      foreach ($result as $temp)
      {
      $isLDBUser=$temp['isLDBUser'];
      }
      return $this->xmlrpc->send_response(json_encode($isLDBUser));

      }
     */

    function isLDBUser($request) {
        $parameters = $request->output_parameters();
        $userid = $parameters['0'];
        $dbHandle = $this->_loadDatabaseHandle();
        $query = "select UserId, PrefId from tUserPref where DesiredCourse is not NULL and UserId=? and DesiredCourse not in(1,35,36,37,38,39,40,41,42,43,44,45,382) order by PrefId desc limit 1";
        error_log("query for isLDBUser is as " . $query);
        $query = $dbHandle->query($query, array($userid));
        $result = $query->result_array();
        if (empty($result)) {
            $query = "select PrefId from tUserPref where UserId=? order by PrefId desc limit 1";
            error_log("query for isLDBUser is as " . $query);
            $query = $dbHandle->query($query, array($userid));
            $result = $query->result_array();
        }
        // error_log("result is finally as " . print_r($result, true));
        return $this->xmlrpc->send_response(json_encode($result));
    }

    function checkLDBUser($request) {
        error_log("inside checkLDBUser....");
        $dbHandle = $this->_loadDatabaseHandle();
        $query = "update tuserflag inner join tUserPref on ( tuserflag.userId = tUserPref.UserId) set tuserflag.isLDBUser='YES' where tUserPref.DesiredCourse is not NULL and tUserPref.DesiredCourse not in(1,35,36,37,38,39,40,41,42,43,44,45,382)";
        $query = $dbHandle->query($query);
        $query = "update tuserflag t1  join tUserSpecializationPref t2 on t1.userId=t2.UserId join tusersourceInfo t3 on t1.UserId=t3.userid set t1.isLDBUser='YES' where t3.referer like '%marketing/Marketing%'";
        $query = $dbHandle->query($query);
        $query = "update tuserflag t1  join tUserSpecializationPref t2 on t1.userId=t2.UserId join tusersourceInfo t3 on t1.UserId=t3.userid set t1.isLDBUser='YES' where t3.referer like '%#home%'";
        $query = $dbHandle->query($query);
        $query = "update tuserflag t1  join tUserPref t2 on t1.userId=t2.UserId join tusersourceInfo t3 on t1.UserId=t3.userid set t1.isLDBUser='YES' where t2.ExtraFlag in ('testprep','studyabroad') and t3.referer like '%marketing/Marketing%'";
        $query = $dbHandle->query($query);
        $query = "update tuserflag t1  join tUserPref t2 on t1.userId=t2.UserId join tusersourceInfo t3 on t1.UserId=t3.userid set t1.isLDBUser='YES' where t2.ExtraFlag in ('testprep','studyabroad') and t3.referer like '%#home%'";
        $query = $dbHandle->query($query);
        return $this->xmlrpc->send_response(json_encode("All izzz well"));
    }

    function getLeadsForInstitutes($request) {
        $parameters = $request->output_parameters();
        $institute_ids = $parameters[0];

        $this->load->model('ldbmodel');
        $leads = $this->ldbmodel->getLeadsForInstitutes($institute_ids);

        $response_string = base64_encode(gzcompress(json_encode($leads)));
        $response = array($response_string, 'string');
        return $this->xmlrpc->send_response($response);
    }

        function getResponsesByClientId($request){
                $parameters = $request->output_parameters();
                $clientId = $parameters[0];
                $dbHandle = $this->_loadDatabaseHandle();
                $sql = " select tlt.id as response_id, ins.institute_id as institute_id, t.userid,t.firstname as name,t.lastname,ifnull(t.gender,'') as gender,ifnull(t.age,'') as age,t.email,t.mobile,if(STRCMP(tuf.mobileverified,'1'),'No','Yes') as mob_verify,tuf.isNDNC, lm.listing_type, cd.courseTitle as courseName, ins.institute_name as instituteName, tlt.action,date(tlt.submit_date) as date,ifnull(cct.city_name,'') as city,ifnull(tel.EducationLevel,'') as educationlevel,ifnull(tel.Options,'') as options

                from shiksha.tempLMSTable tlt join shiksha.listing_category_table lct on tlt.listing_type=lct.listing_type and tlt.listing_type_id=lct.listing_type_id and lct.status='live'  left join shiksha.tuser t on t.userid=tlt.userId left join tuserflag tuf on tuf.userId=t.userid left join countryCityTable cct on cct.city_id=t.city left join tEducationLevel tel on tel.EducationId=t.educationlevel, listings_main lm, course_details cd , institute ins

                where tlt.listing_subscription_type='paid' AND tlt.submit_date>date_sub(curdate(),INTERVAL 1 day) and tuf.isTestUser = 'NO' and tlt.listing_type=lm.listing_type and lm.listing_type_id = tlt.listing_type_id and lm.listing_type = 'course' and lm.status ='live' and cd.course_id = tlt.listing_type_id and cd.status = 'live' and ins.institute_id = cd.institute_id and ins.status = 'live'";

                if( ! empty($clientId)) {
                    $sql = $sql."and lm.username = ".$dbHandle->escape($clientId);
                }

                $sql = $sql."union ";

                $sql = $sql."select tlt.id as response_id, lm.listing_type_id as institute_id, t.userid,t.firstname as name,t.lastname,ifnull(t.gender,'') as gender,ifnull(t.age,'') as age,t.email,t.mobile,if(STRCMP(tuf.mobileverified,'1'),'No','Yes') as mob_verify,tuf.isNDNC,lm.listing_type,'NONE' as courseName,lm.listing_title as instituteName,tlt.action,date(tlt.submit_date) as date,ifnull(cct.city_name,'') as city,ifnull(tel.EducationLevel,'') as educationlevel,ifnull(tel.Options,'') as options

                from shiksha.tempLMSTable tlt join shiksha.listing_category_table lct on tlt.listing_type=lct.listing_type and tlt.listing_type_id=lct.listing_type_id and lct.status='live'  left join shiksha.tuser t on t.userid=tlt.userId left join tuserflag tuf on tuf.userId=t.userid left join countryCityTable cct on cct.city_id=t.city left join tEducationLevel tel on tel.EducationId=t.educationlevel, listings_main lm

                where tlt.listing_subscription_type='paid' AND tlt.submit_date>date_sub(curdate(),INTERVAL 1 day)  and tuf.isTestUser = 'NO' and tlt.listing_type=lm.listing_type and lm.listing_type_id = tlt.listing_type_id and lm.listing_type = 'institute' and lm.status ='live'";

                if( ! empty($clientId)) {
                    $sql = $sql."and lm.username = ".$dbHandle->escape($clientId);
                }

                error_log($sql);

                $queryResult = $dbHandle->query($sql);
                $result = $queryResult->result_array();
                
                $rows = array();
                foreach($result as $row) {
                    $row['name'] = $row['name'].' '.$row['lastname'];
                    unset($row['lastname']);
                    $rows[] = $row;
                }
                
                $response = array(json_encode($rows), 'string');
                return $this->xmlrpc->send_response($response);

        }
        function getResponsesByMultiLocationClientId($request){
                $parameters = $request->output_parameters();
                $clientId = $parameters[0];
/*
                $sql = " select b.id as response_id, a.listing_type_id as listing_id,a.listing_type as listing_type,  t.userid,t.displayname,ifnull(t.gender,'') as gender,ifnull(t.age,'') as age,t.email,t.mobile,a.listing_title as listing_title,b.action,date(b.submit_date) as date ,f.localityname, e.city_name ";

                $sql.= "FROM `listings_main` a, tempLMSTable b, `responseLocationTable` c, `institute_location_table` d, `countryCityTable` e, `localityCityMapping` f,                     tuser t 
                    WHERE b.submit_date>date_sub(curdate(),INTERVAL 1 day)
                    AND a.listing_type_id = b.listing_type_id
                    and t.userid = b.userid
                    AND a.listing_type = b.listing_type
                    AND b.id = c.responseid
                    AND c.instituteLocationId = d.institute_location_id
                    AND d.city_id = e.city_id
                    AND d.locality_id = f.localityid
                    AND a.`username` ='$clientId'
                    AND a.status = 'live'
                    GROUP BY b.userid";
*/

		$sql = "select b.id as response_id, a.listing_type_id as listing_id,a.listing_type as listing_type,  t.userid,t.firstname as name,t.lastname,ifnull(t.gender,'') as gender,ifnull(t.age,'') as age,t.email,t.mobile,a.listing_title as listing_title,b.action,date(b.submit_date) as date ,f.localityname, e.city_name, g.city_name as ResidenceCity

FROM `listings_main` a, tempLMSTable b, `responseLocationTable` c, `institute_location_table` d left join  `countryCityTable` e on (d.status = 'live' and d.city_id = e.city_id) left join  `localityCityMapping` f on  d.locality_id = f.localityid , tuserflag tf, tuser t left join `countryCityTable` g on t.city = g.city_id
                    WHERE b.submit_date>date_sub(curdate(),INTERVAL 1 day)
                    AND b.listing_subscription_type ='paid'
                    AND a.listing_type_id = b.listing_type_id
                    and t.userid = b.userid
                    and t.userid = tf.userId
                    AND a.listing_type = b.listing_type
                    AND b.id = c.responseid
                    AND c.instituteLocationId = d.institute_location_id
                    AND a.`username` =?
                    AND a.status = 'live'
                    AND d.status = 'live'
                    AND tf.isTestUser = 'NO'
                    GROUP BY b.userid, b.listing_type_id";

                error_log($sql);

                $dbHandle = $this->_loadDatabaseHandle();
                $queryResult = $dbHandle->query($sql, array($clientId));
                $result = $queryResult->result_array();
                $rows = array();
                foreach($result as $row) {
                    $row['name'] = $row['name']." ".$row['lastname'];
                    unset($row['lastname']);
                    $rows[] = $row;
                }
                $response = array(json_encode($rows), 'string');
                return $this->xmlrpc->send_response($response);

        }


	function getResponseLocalities($request){
		$parameters = $request->output_parameters();
		$responses_ids = $parameters;
		
		$sql = "select response_id, locality from shiksha.responseLocationPref where response_id in (".join(',', $responses_ids).")";
		error_log($sql);
		
		$dbHandle = $this->_loadDatabaseHandle();
		$queryResult = $dbHandle->query($sql);
		$result = $queryResult->result_array();
		$response = array(json_encode($result), 'string');
		return $this->xmlrpc->send_response($response);
	}

	function getViewableUsers($request) {
		$parameters = $request->output_parameters(FALSE, FALSE);
		$userArray = json_decode($parameters['1']);
		$clientId = $parameters['2'];
		$ExtraFlag = $parameters['3'];
		$excludeResponses = $parameters['4'];
        $actual_course_id = $parameters['5'];
		$result = array();
		$viewedUsers = $this->removeAlreadyViewedUser($userArray, $clientId);
		$responseUsers = array();
		if($excludeResponses) {
			$this->load->model('ldbmodel');
			$responseUsers = $this->ldbmodel->getResponsesForClient($clientId, $userArray);
		}
		
		$requestArray = array();
		foreach ($userArray as $user) {
			if (!in_array($user, $viewedUsers) && !in_array($user, $responseUsers)) {
				$requestArray[] = $user;
			}
		}
		if(count($requestArray) > 0){
            global $excludeViewLimitForClients;                        
            $excludedClientIds = $excludeViewLimitForClients;                        
            $retArr = array();
            $limit_check = 'Y';
            if(!empty($actual_course_id)) {
                require_once APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
                foreach ($coursesList as $course_name=> $details) {
                    if($details['actual_course_id'] == $actual_course_id) {
                        $limit_check = 'N';break;
                    }					
                }
            }
            if($limit_check == 'Y') {
                if(!in_array($clientId, $excludedClientIds)) {
                    $retArr = $this->checkUserViewLimit($requestArray, $ExtraFlag);
                }
            }
			
			foreach ($userArray as $user) {
				if (!in_array($user, $retArr)) {
					$result[] = $user;
				}
			}
		}
		else {
			$result = $userArray;
		}
		
		error_log("getViewableUsers is as " . print_r($result, true));
		return $this->xmlrpc->send_response(json_encode($result));
	}

    //need to change this API
	function checkUserViewLimit($userIds, $ExtraFlag="false") {
		$userIDCSV = implode(",", $userIds);
		$dbHandle = $this->_loadDatabaseHandle('write', 'LDB');
		if($ExtraFlag == 'true') {
			$sql = "SELECT tup.UserId FROM LDBLeadViewCount ldb, tUserPref tup, tUserPref_testprep_mapping ttm, tCourseGrouping tcg, tGroupCreditDeductionPolicy tgd ".
					"WHERE ldb.UserId = tup.UserId ".
					"AND tup.prefid = ttm.prefid ".
					"AND ttm.blogid = tcg.courseId ".
					"AND tcg.groupId = tgd.groupId ".
                    "AND ttm.blogid = ldb.DesiredCourse ".
					"AND tup.status = 'live' ".
					"AND ttm.Status = 'live' ".
					"AND tcg.status = 'live' ".
					"AND tgd.status = 'live' ".
					"AND tup.extraflag = 'testprep' ".
					"AND tgd.deductcredit > 0 ".
					"AND ldb.ViewCount >= tgd.deductcredit ".
					"AND tgd.actionType = 'view_limit' ".
					"AND tup.UserId in ($userIDCSV)";
		}
		else {
			$sql = "SELECT tup.UserId FROM LDBLeadViewCount ldb, tUserPref tup, tCourseGrouping tcg, tGroupCreditDeductionPolicy tgd ".
					"WHERE ldb.UserId = tup.UserId ".
					"AND tup.DesiredCourse = tcg.courseId ".
					"AND tcg.groupId = tgd.groupId ".
                    "AND tup.DesiredCourse = ldb.DesiredCourse ".
					"AND tup.status = 'live' ".
					"AND tcg.Status = 'live' ".
					"AND tgd.status = 'live' ".
					"AND (tup.extraflag != 'testprep' OR tup.extraflag is NULL) ".
					"AND tgd.deductcredit > 0 ".
					"AND ldb.ViewCount >= tgd.deductcredit ".
					"AND tgd.actionType = 'view_limit' ".
					"AND tup.UserId in ($userIDCSV)";
		}
		error_log("checkUserViewLimit ".$sql);
		$result = $dbHandle->query($sql)->result_array();
		$retArry = array();
		foreach ($result as $row) {
			$retArry[] = $row["UserId"];
		}
		return $retArry;
	}
        
        function updateLDBQueryLog($query, $queryRunTime, $totalExecutionTime) {
            $dbHandle = $this->_loadDatabaseHandle('write');
            $data = array('query' => $query, 'query_run_time' => $queryRunTime, 'total_execution_time' => $totalExecutionTime);
            $insertQuery = $dbHandle->insert_string('LDBQueryLog', $data);
            $dbHandle->query($insertQuery);
        }

}

?>
