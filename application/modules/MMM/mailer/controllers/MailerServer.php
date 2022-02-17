<?php

class MailerServer extends MX_Controller {

    function index(){
        set_time_limit(0);
        ini_set("memory_limit","4096M");
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library(array('mailerconfig','Alerts_client','subscription_client','sums_product_client'));
        $this->load->helper('date');
	$this->load->helper('url');
	$this->dbLibObj = DbLibCommon::getInstance('Mailer');	

	$config['functions']['getAllTemplates'] = array('function' => 'MailerServer.getAllTemplates');
        $config['functions']['getTemplateInfo'] = array('function' => 'MailerServer.getTemplateInfo');
        $config['functions']['insertOrUpdateTemplate'] = array('function' => 'MailerServer.insertOrUpdateTemplate');
        $config['functions']['getTemplateVariables'] = array('function' => 'MailerServer.getTemplateVariables');
        $config['functions']['setTemplateVariables'] = array('function' => 'MailerServer.setTemplateVariables');
        $config['functions']['getVariablesKey'] = array('function' => 'MailerServer.getVariablesKey');
        $config['functions']['getAllLists'] = array('function' => 'MailerServer.getAllLists');
        $config['functions']['getListInfo'] = array('function' => 'MailerServer.getListInfo');
        $config['functions']['submitList'] = array('function' => 'MailerServer.submitList');
        $config['functions']['getListCsv'] = array('function' => 'MailerServer.getListCsv');
        $config['functions']['updateListInfo'] = array('function' => 'MailerServer.updateListInfo');
        $config['functions']['sendTestMailer'] = array('function' => 'MailerServer.sendTestMailer');
        $config['functions']['saveMailer'] = array('function' => 'MailerServer.saveMailer');
        $config['functions']['updateMailerDetails'] = array('function' => 'MailerServer.updateMailerDetails');
        $config['functions']['s_getSearchFormData'] = array('function' => 'MailerServer.s_getSearchFormData');
        $config['functions']['s_getSearchFormData1'] = array('function' => 'MailerServer.s_getSearchFormData1');
        $config['functions']['s_submitSearchQuery'] = array('function' => 'MailerServer.s_submitSearchQuery');
        $config['functions']['checkTemplateCsv'] = array('function' => 'MailerServer.checkTemplateCsv');
        $config['functions']['submitOpenMail'] = array('function' => 'MailerServer.submitOpenMail');
        $config['functions']['runCronMailer'] = array('function' => 'MailerServer.runCronMailer');
        $config['functions']['runCronSms'] = array('function' => 'MailerServer.runCronSms');
        $config['functions']['getMailersList'] = array('function' => 'MailerServer.getMailersList');
        $config['functions']['getMailerTrackingUrls'] = array('function' => 'MailerServer.getMailerTrackingUrls');
        $config['functions']['getAllSmsTemplates'] = array('function' => 'MailerServer.getAllSmsTemplates');
        $config['functions']['autoLogin'] = array('function' => 'MailerServer.autoLogin');
        $config['functions']['captureMisData'] = array('function' => 'MailerServer.captureMisData');
        $config['functions']['unsubscribe'] = array('function' => 'MailerServer.unsubscribe');

        $config['functions']['addListInMailer'] = array('function' => 'MailerServer.addListInMailer');

        /* Code for Polls and Feedback within the email */
        $config['functions']['registerFeedback'] = array('function' => 'MailerServer.registerFeedback');
        $config['functions']['registerLead'] = array('function' => 'MailerServer.registerLead');
        $config['functions']['registerData'] = array('function' => 'MailerServer.registerData');
        $config['functions']['registerPoll'] = array('function' => 'MailerServer.registerPoll');
        $config['functions']['createPoll'] = array('function' => 'MailerServer.createPoll');

        /* End of Code for Polls and Feedback within the email */

        $config['functions']['supdateCmsItem'] = array('function' => 'EnterpriseServer.supdateCmsItem');
        $config['functions']['sgetItems'] = array('function' => 'EnterpriseServer.sgetItems');
        $config['functions']['sgetKeyPages'] = array('function' => 'EnterpriseServer.sgetKeyPages');
        $config['functions']['supdateAssignNewInstitute'] = array('function' => 'EnterpriseServer.supdateAssignNewInstitute');
        $config['functions']['supdateOldInstitute'] = array('function' => 'EnterpriseServer.supdateOldInstitute');
        $config['functions']['sEditUpdateCourse'] = array('function' => 'EnterpriseServer.sEditUpdateCourse');
        $config['functions']['sremoveInstiLogoCMS'] = array('function' => 'EnterpriseServer.sremoveInstiLogoCMS');
        $config['functions']['sremoveFeaturedPanelLogo'] = array('function' => 'EnterpriseServer.sremoveFeaturedPanelLogo');
        $config['functions']['sremoveCourseMediaCMS'] = array('function' => 'EnterpriseServer.sremoveCourseMediaCMS');
        $config['functions']['sEditNotification'] = array('function' => 'EnterpriseServer.sEditNotification');
        $config['functions']['sRemoveNotificationDoc'] = array('function' => 'EnterpriseServer.sRemoveNotificationDoc');
        $config['functions']['getNotificationEvents'] = array('function' => 'EnterpriseServer.getNotificationEvents');

        $config['functions']['sGetHeaderTabs'] = array('function' => 'EnterpriseServer.sGetHeaderTabs');
        $config['functions']['saddEnterpriseUser'] = array('function' => 'EnterpriseServer.saddEnterpriseUser');
        $config['functions']['sgetInstituteList'] = array('function' => 'EnterpriseServer.sgetInstituteList');
        $config['functions']['sgetCitiesWithCollege'] = array('function' => 'EnterpriseServer.sgetCitiesWithCollege');
        $config['functions']['supdateScholarship'] = array('function' => 'EnterpriseServer.supdateScholarship');
        $config['functions']['sRemoveScholarshipDoc'] = array('function' => 'EnterpriseServer.sRemoveScholarshipDoc');
        /****** Different Product APIs *********/
        $config['functions']['sgetEventDetailCMS'] = array('function' => 'EnterpriseServer.sgetEventDetailCMS');
        $config['functions']['sgetPopularTopicsCMS'] = array('function' => 'EnterpriseServer.sgetPopularTopicsCMS');
        $config['functions']['getSearchSubCategories'] = array('function' => 'EnterpriseServer.getSearchSubCategories');
        $config['functions']['getEnterpriseUserDetails'] = array('function' => 'EnterpriseServer.getEnterpriseUserDetails');
        $config['functions']['updateEnterpriseUserDetails'] = array('function' => 'EnterpriseServer.updateEnterpriseUserDetails');
        $config['functions']['changePassword'] = array('function' => 'EnterpriseServer.changePassword');
        $config['functions']['updateUserGroup'] = array('function' => 'EnterpriseServer.updateUserGroup');
        $config['functions']['getViewCountForUserFedListings'] = array('function' => 'EnterpriseServer.getViewCountForUserFedListings');
	$config['functions']['sgetMediaData'] = array('function' => 'EnterpriseServer.sgetMediaData');
	$config['functions']['sgetcountofMedia'] = array('function' => 'EnterpriseServer.sgetcountofMedia');
	$config['functions']['sdeleteMediaData'] = array('function' => 'EnterpriseServer.sdeleteMediaData');
    $config['functions']['getReportedChangesForBlogs'] = array('function' => 'EnterpriseServer.getReportedChangesForBlogs');
    $config['functions']['getReportedChangesById'] = array('function' => 'EnterpriseServer.getReportedChangesById');
    $config['functions']['saddMainCollegeLink'] = array('function' => 'EnterpriseServer.saddMainCollegeLink');
    $config['functions']['sgetListingsByClient'] = array('function' => 'EnterpriseServer.sgetListingsByClient');
    $config['functions']['scancelSubscription'] = array('function' => 'EnterpriseServer.scancelSubscription');
    $config['functions']['scheckUniqueTitle'] = array('function' => 'EnterpriseServer.scheckUniqueTitle');
    $config['functions']['supdateMainCollegeLink'] = array('function' => 'EnterpriseServer.supdateMainCollegeLink');
        $config['functions']['encryptCsv'] = array('function' => 'MailerServer.encryptCsv');
        $config['functions']['encryptkey'] = array('function' => 'MailerServer.encryptkey');
        $config['functions']['sendRegistrationQuestionMailer'] = array('function' => 'MailerServer.sendRegistrationQuestionMailer');
        $config['functions']['getQuestionsforCategory'] = array('function' => 'MailerServer.getQuestionsforCategory');
        $config['functions']['generateAutoLoginLink'] = array('function' => 'MailerServer.generateAutoLoginLink');
        $config['functions']['generateWeeklyMailer'] = array('function' => 'MailerServer.generateWeeklyMailer');
        $config['functions']['generateDailyMailer'] = array('function' => 'MailerServer.generateDailyMailer');
        $config['functions']['generateBestAnswerMailer'] = array('function' => 'MailerServer.generateBestAnswerMailer');
//        $config['functions']['getDigUpUsers'] = array('function' => 'MailerServer.getDigUpUsers');
//        $config['functions']['insertPostAnAnswerUsers'] = array('function' => 'MailerServer.insertPostAnAnswerUsers');
//        $config['functions']['getBestAnsUsers'] = array('function' => 'MailerServer.getBestAnsUsers');

		$config['functions']['fireListingAnADailyMailer'] = array('function' => 'MailerServer.fireListingAnADailyMailer');
		$config['functions']['generateAutoLoginLinkS'] = array('function' => 'MailerServer.generateAutoLoginLinkS');
        /* End: Shashwat CMS Server code */
	$config['functions']['updateMailer'] = array('function' => 'MailerServer.updateMailer');
	$config['functions']['updateMailerList'] = array('function' => 'MailerServer.updateMailerList');
	$config['functions']['incrementCounter'] = array('function' => 'MailerServer.incrementCounter');
	$config['functions']['externalMassQueueAdd'] = array('function' => 'MailerServer.externalMassQueueAdd');
	$config['functions']['externalMassQueueAddImmediate'] = array('function' => 'MailerServer.externalMassQueueAddImmediate');
	$config['functions']['createMassAttachment'] = array('function' => 'MailerServer.createMassAttachment');
	$config['functions']['getMassAttachmentId'] = array('function' => 'MailerServer.getMassAttachmentId');

        $this->xmlrpc->set_debug(1);
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }







    // creates temporary list( List of users searched or uploaded) .
    function createTempList($userIdsArr, $user) {
        /*if(count($userIdsArr) > 0) {
            $finalafterAllIds = array();
            $numEmail = count($userIdsArr);
            $k = 0;
            for($i = 0 ; $i < $numEmail; $i++) {
                $finalafterAllIds[$k][$i] = $userIdsArr[$i];
                if($i == ($k+1)*100000) {
                    $k++;
                } 
            }
            $listId=-1;
            $this->mailerconfig->getDbConfig($appID,$dbConfig);
            $dbHandle  = $this->_loadDatabaseHandle();
            for($jkl = 0; $jkl < count($finalafterAllIds); $jkl ++) {
                $finalUserIds = implode(",",$finalafterAllIds[$jkl]);
                $countMail = count($userIdsArr);
                error_log("TYUI TempName111111111111111 ".$jkl);
                if($jkl == 0) {
                    $queryCmdInsert = "insert into list values('','TempName', 'TempDesc', '$finalUserIds', 'false', ".$countMail.", 'false' , NOW(), $user)";
                }else {
                    //$queryCmdInsert = "update list set userIds=concat(userIds,',$finalUserIds') where id=$listId ";
		    //Add entires in list for every 1 lakh user id and maintain the same masterListId
                    $queryCmdInsert = "insert into list values('','TempName', 'TempDesc', '$finalUserIds', 'false', ".$countMail.", 'false' , NOW(), $user,$listId)";
                } 
                try{
                    $queryTemp = $dbHandle->query($queryCmdInsert);
                    error_log("TYUI Done4".$queryCmdInsert);
                    if($jkl == 0){
                        $listId = $dbHandle->insert_id();
			$queryCmdUpdate = "update list set masterListId=$listId where id=$listId ";
			$queryTempUpdate = $dbHandle->query($queryCmdUpdate);
                    }
                }catch(Exception $e) {
                    error_log("TYUI Done3". $dbHandle->_error_message());
                }
            }
        }*/
	$listId = $this->insertIntoList($userIdsArr,$user);
        $response = $this->getListInfoFunc($appID, $listId, $userId, $userGroup, "false");
        return $response;
    }


    // creates CSV list with all the attributes to be used for mail merge.
    function createCsvList($csvArr,$count, $user, $userGroup) {
        $queryCmdInsert = "insert into list values('','TempName', 'TempDesc', '', 'true', ?, 'true' , NOW(), ? ,0)";
//         $this->mailerconfig->getDbConfig($appID,$dbConfig);
	$dbHandle  = $this->_loadDatabaseHandle();
        $queryTemp = $dbHandle->query($queryCmdInsert, array($count, $user));
        $listId = $dbHandle->insert_id();
	//After inserting the row in list table, update its masterListId. For a csv upload, there will be a single list entry
	$queryCmdUpdate = "update list set masterListId=? where id=? ";
	$queryTempUpdate = $dbHandle->query($queryCmdUpdate, array($listId, $listId));
    $queryCmdInsert = "insert into csvKeyValue values";
        foreach ($csvArr as $key=>$val) {
            for($i = 0 ; $i < count($val); $i++) {
                $queryCmdInsert.= "('', $listId, '".mysql_escape_string($key)."', $i, '".mysql_escape_string($val[$i])."'),";
                
            }
        }
        $queryCmdInsert = substr($queryCmdInsert, 0,-1);
        $queryTemp = $dbHandle->query($queryCmdInsert);

        $response = $this->getListInfoFunc($appID, $listId, $user, $userGroup, "flase");
        return $response;
    }

    function cookie($value)
    {
        setcookie('user',$value,0,'/',COOKIEDOMAIN);
        //error_log("SENDMAILS1".$value);
        return($this->checkUserValidation($value));
    }

    function registerData($request) {
	$parameters = $request->output_parameters(FALSE,FALSE);
	$appId=$parameters['0'];
	$data = $parameters['1'];
	$cityId = $parameters['2'];
	$localityId = $parameters['3'];
	$dataArr = explode("_",$data);
	
	if (count($dataArr) <= 1 ) {
	    return;
	}
	
	$finalData = array();
        for($i = 0 ; $i < count($dataArr); $i++) {
	    $tempArr = explode("~",$dataArr[$i]);
	    $finalData[$tempArr[0]] = $tempArr[1];
        }
        
	$email = $finalData["email"];
        $mailer_id = $finalData["mailerId"];
	$instituteCityId = $cityId > 0 ? $cityId : $finalData["instituteCityId"];
	$instituteLocalityId = $localityId > 0 ? $localityId : $finalData["instituteLocalityId"];
	
	$action = $finalData["action"];
        if($action=='') {
            $action='mailerAlert';
        }
	
	if(isset($finalData["instituteId"])) {
	    $typeId = $finalData["instituteId"];
	    $type = "institute";
	}
	else if(isset($finalData["courseId"])) {
	    $typeId = $finalData["courseId"];
	    $type = "course";
	}
	
	$typeIdArr = split(",",$typeId);
	
	$dbHandle  = $this->_loadDatabaseHandle('read','User');
        $queryCmdInsert = "select * from tuser where email=DECODE(UNHEX(?),'ShikSha')";
        $query = $dbHandle->query($queryCmdInsert, array($email));
	foreach($query->result() as $row) {
	    $displayname = $row->displayname;
	    $mobile = $row->mobile;
	    $userid = $row->userid;
	    $password = $row->ePassword;
	    $email = $row->email;
	}
	
        $cookie = $email."|".$password;
	$userStatus = $this->cookie($cookie);
	if ($userStatus == 'false') {
	    return;
	}
	
    if($userid > 0 && $userid != '') {
        $usermodel = $this->load->model('user/usermodel');
        $usermodel->trackUserLogin($userid);
    } 

	$this->load->library(array('lmsLib'));
	$LmsClientObj = new LmsLib();		 
	for($jkl = 0; $jkl < count($typeIdArr); $jkl++) {
	    $addReqInfo['listing_type'] = $type;
	    $addReqInfo['listing_type_id'] = $typeIdArr[$jkl];
	    $addReqInfo['preferred_city'] = $instituteCityId;
	    $addReqInfo['preferred_locality'] = $instituteLocalityId;
	    $addReqInfo['displayName'] = $userStatus[0]['displayname'];
	    $addReqInfo['contact_cell'] = $userStatus[0]['mobile'];
	    $addReqInfo['userId'] = $userStatus[0]['userid'];
	    $addReqInfo['contact_email'] = $email;
	    $addReqInfo['action'] = $action;
        
        if(isMobileRequest()){
            $addReqInfo['action']="MOB_".$addReqInfo['action'];
        }

	    $addReqInfo['userInfo'] = json_encode($userStatus);
	    $addReqInfo['sendMail'] = false;
        $addReqInfo['listing_subscription_type'] = 'free';
        if($this->_isCoursePaid($typeIdArr[$jkl])){
            $addReqInfo['listing_subscription_type'] = 'paid';
        }                          

        if($addReqInfo['listing_type_id'] > 0)  {						
			$national_course_lib = $this->load->library('listing/NationalCourseLib');
			$course_type = $national_course_lib->getCourseTypeById($addReqInfo['listing_type_id']);
			$trackingPageKeyId = "";
			if(isMobileRequest()){
				
				if($course_type == 'testprep') {
					$trackingPageKeyId = 687;
				} else if($course_type == 'domestic') {
					$trackingPageKeyId = 676;
				} else if($course_type == 'abroad') {
					$trackingPageKeyId = 685;
				}
				
			} else {
				
				if($course_type == 'testprep') {
					$trackingPageKeyId = 686;
				} else if($course_type == 'domestic') {
					$trackingPageKeyId = 675;
				} else if($course_type == 'abroad') {
					$trackingPageKeyId = 684;
				}
			}							
        }

        Modules::run('responseAbroad/ResponseAbroad/createResponseCallForAbroadListings', $addReqInfo['listing_type'], $addReqInfo['listing_type_id'],$addReqInfo['action'],array('trackingKey' =>$trackingPageKeyId,'moduleRunFrom' => 'mailer' ));


     //    $addReqInfo['trackingPageKeyId'] = $trackingPageKeyId;   
	    // $addLeadStatus1 = $LmsClientObj->insertTempLead($appId,$addReqInfo);	
	    // $addReqInfo['tempLmsRequest'] = $addLeadStatus1['leadId'];	 
	    // $addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);
	}
    }


    function autoLogin($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appId      = $parameters['0'];
        $email      = $parameters['1'];
        // $typeId  = $parameters['2']; // unused
        $this->load->library('mailerconfig');
        $this->mailerconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->_loadDatabaseHandle('read','User');
        if($dbHandle == '')
        {
            log_message('error','autoLogin can not create db handle');
        }

        $queryCmd = "select * from tuser, tuserflag where tuser.email=DECODE(UNHEX(?),'ShikSha') and tuser.userid=tuserflag.userId limit 1";
		
		$query = $dbHandle->query($queryCmd, array($email));

        $data =  "";
        $rowArr = array();
        foreach($query->result_array() as $row) {
            $rowArr = $row;
        }
        $cookie              = $rowArr['email']."|".$rowArr['ePassword'];
        $value               = $cookie;
        $pendingverification = $rowArr['pendingverification'];
        $hardbounce          = $rowArr['hardbounce'];
        $ownershipchallenged = $rowArr['ownershipchallenged'];
        $softbounce          = $rowArr['softbounce'];
        $abused              = $rowArr['abused'];
        $emailsentcount      = $rowArr['emailsentcount'];
		
        if($abused == 1 || $ownershipchallenged == 1)
        {
            echo 'invalid';

        }
        else
        {
            if($Validate[0]['emailverified'] == 1)
                $value .= "|verified";
            else
            {
                if($hardbounce == 1)
                    $value .= "|hardbounce";
                if($softbounce == 1)
                    $value .= "|softbounce";
                if($pendingverification == 1)
                    $value .= '|pendingverification';
            }
        }

        $userId = $rowArr['userid'];
        if($userId > 0 && $userId != '') {
            $usermodel = $this->load->model('user/usermodel');
            $usermodel->trackUserLogin($userId);
        }

        $response = array($value,'string');
        return $this->xmlrpc->send_response($response);
    }





    function registerLead($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        //error_log_shiksha(print_r($parameters,true));
        $appId=$parameters['0'];
        $mailer_id = $parameters['1'];
        $email = $parameters['2'];
        $feedback = $parameters['3'];
        $typeId = $parameters['4'];
        $type = $parameters['5'];
        $this->load->library('mailerconfig');
        $this->mailerconfig->getDbConfig($appId,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();

        if($dbHandle == '')
        {
            log_message('error','registerLead can not create db handle');
        }

        $data=array('mailer_id'=>$mailer_id,'email'=>"DECODE(UNHEX(".$email."),'ShikSha')",'feedback_comment'=>$feedback, 'typeId'=>$typeId, 'type'=>$type );

        $queryCmdInsert = "insert into mailer_feedback_table values(?, DECODE(UNHEX(?),'ShikSha'), '".mysql_escape_string($feedback)."', now(),'',?,?)";
        $queryTemp = $dbHandle->query($queryCmdInsert, array($mailer_id, $email, $typeId, $type));

        $feedback_id = $dbHandle->insert_id();
        $queryCmdInsert = "select email from mailer_feedback_table where feedback_id=?"; 
        $query = $dbHandle->query($queryCmdInsert, array($feedback_id));
        foreach($query->result() as $row) {
            $email = $row->email;
	}
        $queryCmdInsert = "select * from shiksha.tuser where shiksha.tuser.email=?";
        $dbHandle  = $this->_loadDatabaseHandle('read','User');
        $query = $dbHandle->query($queryCmdInsert, array($email));
        foreach($query->result() as $row) {
            $displayname = $row->displayname;
            $mobile = $row->mobile;
            $userid = $row->userid;
            $password = $row->password;
        }
        $cookie = $email."|".$password;
        //error_log("123".$cookie);
        $userStatus = $this->cookie($cookie);
        //error_log(print_r($userStatus,true));

        

        $addReqInfo['listing_type'] = $type;
        $addReqInfo['listing_type_id'] = $typeId;
        $addReqInfo['displayName'] = $userStatus[0]['displayname'];
        $addReqInfo['contact_cell'] = $userStatus[0]['mobile'];
        $addReqInfo['userId'] = $userStatus[0]['userid'];
        $addReqInfo['contact_email'] = $email;
        $addReqInfo['action'] = "mailerForm";
        $addReqInfo['userInfo'] = json_encode($userStatus);
        $addReqInfo['sendMail'] = false;
        $this->load->library(array('lmsLib'));
        $LmsClientObj = new LmsLib();
	$addLeadStatus1 = $LmsClientObj->insertTempLead($appId,$addReqInfo);	
	$addReqInfo['tempLmsRequest'] = $addLeadStatus1['leadId'];
        $addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);


        $response = array(
                array(
                    'result'=>0,
                    'feedback_id'=>$feedback_id
                    ),
                'struct');
        return $this->xmlrpc->send_response($response);
    }



    // Registers the newsletter feedback.
    function registerFeedback($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        //error_log_shiksha(print_r($parameters,true));
        $appId=$parameters['0'];
        $mailer_id = $parameters['1'];
        $email = $parameters['2'];
        $feedback = $parameters['3'];
        $this->load->library('mailerconfig');
        $this->mailerconfig->getDbConfig($appId,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();

        if($dbHandle == '')
        {
            log_message('error','registerFeedback can not create db handle');
        }

        $data=array('mailer_id'=>$mailer_id,'email'=>$email,'feedback_comment'=>$feedback);
        $queryCmd = $dbHandle->insert_string('mailer_feedback_table',$data);
        $queryCmd .= " on duplicate key update modified_date=now();";
        //error_log_shiksha('registerFeedback query cmd is ' . $queryCmd);

        $query = $dbHandle->query($queryCmd);
        $feedback_id = $dbHandle->insert_id();
        $response = array(
                array(
                    'result'=>0,
                    'feedback_id'=>$feedback_id
                    ),
                'struct');
        return $this->xmlrpc->send_response($response);
    }

    // registers the user response to the poll in news letter.
    function registerPoll($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        //error_log(print_r($parameters,true));
        $appId=$parameters['0'];
        $mailer_id = $parameters['1'];
        $email = $parameters['2'];
        $poll_id = $parameters['3'];
        $option = $parameters['4'];
        $this->load->library('mailerconfig');
        $this->mailerconfig->getDbConfig($appId,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();

        if($dbHandle == '')
        {
            log_message('error','registerPoll can not create db handle');
        }

        $data=array('mailer_id'=>$mailer_id, 'email'=>$email, 'poll_id'=>$poll_id, 'poll_option'=>$option);
        $queryCmd = $dbHandle->insert_string('poll_user_opinion',$data);
        $queryCmd .= " on duplicate key update poll_option = ".$option.", modified_date=now();";
        //error_log('registerFeedback query cmd is ' . $queryCmd);

        $query = $dbHandle->query($queryCmd);
        $feedback_id = $dbHandle->insert_id();
        $response = array(
                array(
                    'result'=>0,
                    'feedback_id'=>$feedback_id
                    ),
                'struct');
        return $this->xmlrpc->send_response($response);
    }


    //creates the poll to be added to the newsletter. 
    function createPoll($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        //error_log_shiksha(print_r($parameters,true));
        $appId=$parameters['0'];
        $pollName = $parameters['1'];
        $pollTitle = $parameters['2'];
        $options = $parameters['3'];
        $this->load->library('mailerconfig');
        $this->mailerconfig->getDbConfig($appId,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();

        if($dbHandle == '')
        {
            log_message('error','createPoll cannot create db handle');
        }

        $data=array('pollName'=>$pollName,'pollTitle'=>$pollTitle);
        $queryCmd = $dbHandle->insert_string('pollTable',$data);
        //error_log_shiksha('pollTable query cmd is ' . $queryCmd);
        $query = $dbHandle->query($queryCmd);
        $query = $dbHandle->query($queryCmd);
        $poll_id = $dbHandle->insert_id();
        $pollOption = array();

        for($i=0;$i<count($options);$i++)
        {
            $data_array = array('poll_id'=>$poll_id,'poll_option'=>$options[$i]);
            $queryCmd = $dbHandle->insert_string('pollOptionTable',$data_array);
            //error_log_shiksha('pollOptionTable query cmd is ' . $queryCmd);
            $query = $dbHandle->query($queryCmd);
            array_push($pollOption,array(
                        array(
                            'optionName'=>array($options[$i],'string'),
                            'optionId'=>array($dbHandle->insert_id(),'string'),
                            ),'struct'));//close array_push
        }
        
                $response = array(
                array(
                    'result'=>0,
                    'poll_id'=>$poll_id,
                    'pollOption'=>array($pollOption,'struct')
                    ),
                'struct');
               
        //$response = json_encode($response);
        //error_log_shiksha('response query cmd is ' .print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }



    // checks whether the csv uploaded is compatible to the template if yes saves the list of csv.
    function checkTemplateCsv($request) {
        //error_log("IN server vibhu mailer");

        $parameters = $request->output_parameters(FALSE,FALSE);
        //error_log("mailer ".print_r($parameters,true));
        $appID = $parameters['0'];
        $templateId = $parameters['1'];
        $csvArr = json_decode($parameters['2'],true);

        $user = $parameters['3'];
        $userGroup = $parameters['4'];
        $skipEmailValidation = $parameters['5'];
        //error_log(print_r($negateListArr,true)."vibhu123");
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        //error_log("IN server vibhu mailer".$templateId);
        $queryCmd = 'select * from mailerTemplate where id=?';
        $query = $dbHandle->query($queryCmd, array($templateId));
        $templateType = "mail";
        foreach ($query->result() as $row) {
            $templateType = $row->templateType;
        }
        //error_log("IN server vibhu mailer".$templateType);

        $queryCmd = 'select * from templateVariable where templateId=?';
        $query = $dbHandle->query($queryCmd, array($templateId));
        //error_log($queryCmd." CSV");
        $totalList = array();
        $afterAllIds = array();
        $k = 0;
        //error_log("DSA ".print_r($csvArr,true));
        /*
        foreach ($query->result() as $row) {
            //error_log("CSV ".$row->varName);
            if(isset($csvArr[$row->varValue])) {
            }else {
                if(($row->varName != "tracker")&&($row->varName != "mailerId")&&($row->varName != "templateId")) {
                    //error_log("DSA ".$row->varValue);
                    $msgArray = array();
                    $msgArray[0] = "-1";
                    $response = array($msgArray,'struct');
                    return $this->xmlrpc->send_response($response);
                }
            }
        }
        */
        if($templateType == "mail") {
            if(!(isset($csvArr["email"]))) {
                $msgArray = array();
                $msgArray[0] = "-1";
                $response = array($msgArray,'struct');
                return $this->xmlrpc->send_response($response);
            }
        }else {
            if(!(isset($csvArr["phone"]))) {
                $msgArray = array();
                $msgArray[0] = "-1";
                $response = array($msgArray,'struct');
                return $this->xmlrpc->send_response($response);
            } 
        }
        $emailPresent = 0;

        if($templateType == "mail") {
            if($skipEmailValidation){
                    
                $emailPresent = 0;
            } else{
      
                for($i = 0 ; $i < count($csvArr["email"]); $i++) {
                    $emailID = $csvArr['email'][$i];
                   
                //below pattern is copied from registration.
                $pattern = "/^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/";
                if (preg_match($pattern, $emailID) ? true : false){
                }else{
                   
                    $emailPresent = 1;
                    break;
                } 
            }
            }
            
        }else {
            for($i = 0 ; $i < count($csvArr["phone"]); $i++) {
                if (!eregi("^[0-9]+$", $csvArr["phone"][$i])){
                }else{
                    $emailPresent = 1;
                    break;
                } 
            }
        }


        //error_log("CSV = ".$emailPresent);
        if($emailPresent == 1) {
            $msgArray = array();
            $msgArray[0] = "-2";
            $response = array($msgArray,'struct');
            return $this->xmlrpc->send_response($response);
        }
        //error_log("CSV = 02 ".$emailPresent);
        if($templateType == "mail") {
            $response = $this->createCsvList($csvArr, count($csvArr["email"]) , $user, $userGroup);
        }else {
            $response = $this->createCsvList($csvArr, count($csvArr["phone"]) , $user, $userGroup);
        }
            
        return $this->xmlrpc->send_response($response);
    }


    // submits the new list created, if list already present then uses that list only.
    function submitList($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $oldListId = $parameters['1'];
        $negateListArr = json_decode($parameters['2']);
        $numEmail = $parameters['3'];
        $user = $parameters['4'];
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = 'select * from list where masterListId=?';
        $query = $dbHandle->query($queryCmd, array($oldListId));
        $afterAllIds = array();
	$completeUserArr = array();
	$arrCounter = 0;
        $k = 0;
        foreach ($query->result() as $row) {
	    $totalList = array();
            $uids = $row->userIds;
            $userIdsOrigList = split(",",$uids);
            for($i = 0 ; $i < count($userIdsOrigList); $i++) {
                $totalList[$userIdsOrigList[$i]] = 1;
		$completeUserArr[$arrCounter] = $userIdsOrigList[$i];
		$arrCounter++;
            }
            $negateList = implode(",",$negateListArr);
            $negateList = rtrim($negateList);
            if(strlen($negateList) > 0) {

                $queryCmdInternal = 'select * from list where masterListId in ('.$negateList.')  and createdOn > "2011-08-28"';
                $queryInternal = $dbHandle->query($queryCmdInternal);

                foreach ($queryInternal->result() as $rowInternal) {
                    $uids = $rowInternal->userIds;
                    $userIdsNegate = split(",",$uids);
                    for($i =0; $i < count($userIdsNegate); $i++) {
                        $totalList[$userIdsNegate[$i]] = 0;
                    }
                }
            }

            foreach($totalList as $key=>$val) {
                if($val == 1) {
                    $afterAllIds[$k] = $key;
                    $k++;
                }
            }

        }

        $listId = -1;
        if((count($afterAllIds) == count($completeUserArr))&&($numEmail == -1)) {
            $listId = $oldListId;
        }else {
            if(count($afterAllIds) > 0) {
                if($numEmail == -1) {
                    $finalUserIds = implode(",",$afterAllIds);
                    $countMail = count($afterAllIds);
                }else {
                    $finalafterAllIds = array();
                    if($numEmail > count($afterAllIds)) {
                        $numEmail = count($afterAllIds);
                    }
                    for($i = 0 ; $i < $numEmail; $i++) {
                        $finalafterAllIds[$i] = $afterAllIds[$i];
                    }
                    $finalUserIds = implode(",",$finalafterAllIds);
                    $countMail = count($finalafterAllIds);
                }
                //$queryCmdInsert = "insert into list values('','TempName', 'TempDesc', '$finalUserIds', 'false', ".$countMail.", 'false' , NOW(), $user)";
                //$queryTemp = $dbHandle->query($queryCmdInsert);
                //$listId = $dbHandle->insert_id();
		$listId = $this->insertIntoList(explode(",",$finalUserIds),$user);
            }
        }
        $response = $this->getListInfoFunc($appID, $listId, $userId, $userGroup);
        return $this->xmlrpc->send_response($response);
    }


    // gets the csv list being stored after uploading the list using a csv.
    function getListCsv($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $oldListId = $parameters['1'];
        $negateListArr = json_decode($parameters['2']);
        $numEmail = $parameters['3'];
        $user = $parameters['4'];
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = 'select * from list where masterListId=?';
        $query = $dbHandle->query($queryCmd, array($oldListId));
        $afterAllIds = array();
	$completeUserArr = array();
	$arrCounter = 0;
        $k = 0;
        foreach ($query->result() as $row) {
	    $totalList = array();
            $uids = $row->userIds;
            $userIdsOrigList = split(",",$uids);
            for($i = 0 ; $i < count($userIdsOrigList); $i++) {
                $totalList[$userIdsOrigList[$i]] = 1;
		$completeUserArr[$arrCounter] = $userIdsOrigList[$i];
		$arrCounter++;
            }
            $negateList = implode(",",$negateListArr);
            $negateList = rtrim($negateList);
            if(strlen($negateList) > 0) {

                $queryCmdInternal = 'select * from list where masterListId in ('.$negateList.') ';
                $queryInternal = $dbHandle->query($queryCmdInternal);

                foreach ($queryInternal->result() as $rowInternal) {
                    $uids = $rowInternal->userIds;
                    $userIdsNegate = split(",",$uids);
                    for($i =0; $i < count($userIdsNegate); $i++) {
                        $totalList[$userIdsNegate[$i]] = 0;
                    }
                }
            }

            foreach($totalList as $key=>$val) {
                if($val == 1) {
                    $afterAllIds[$k] = $key;
                    $k++;
                }
            }
        }
        $listId = -1;
        $finalNumberMails = count($afterAllIds);
        if((count($afterAllIds) == count($completeUserArr))&&($numEmail == -1)) {
            $listId = $oldListId;
        }else {
            if(count($afterAllIds) > 0) {
                if($numEmail == -1) {
                    $finalUserIds = implode(",",$afterAllIds);
                    $countMail = count($afterAllIds);
                }else {
                    $finalafterAllIds = array();
                    if($numEmail > count($afterAllIds)) {
                        $numEmail = count($afterAllIds);
                    }
                    for($i = 0 ; $i < $numEmail; $i++) {
                        $finalafterAllIds[$i] = $afterAllIds[$i];
                    }
                    $finalUserIds = implode(",",$finalafterAllIds);
                    $countMail = count($finalafterAllIds);
                }
                $finalNumberMails = $countMail;
                //$queryCmdInsert = "insert into list values('','TempName', 'TempDesc', '$finalUserIds', 'false', ".$countMail.", 'false' , NOW(), $user)";
                //$queryTemp = $dbHandle->query($queryCmdInsert);
                //$listId = $dbHandle->insert_id();
		$listId = $this->insertIntoList(explode(",",$finalUserIds),$user);
            }
        }
        $response = $this->getListCsvInfoFunc($appID, $listId, $userId, $userGroup,"true",$finalNumberMails);
        return $this->xmlrpc->send_response($response);
    }

    /**
    * Added by Ankur on 31 Aug
    * This function will insert values in list table while searching for user or while saving the list
    * We will insert a row in list table for every 1 lakh userId's. The relation between these list entires will be identified with MasterListId which will be common
    * Input : userIdsArr,user
    * Output : none
    */
    function insertIntoList($userIdsArr,$user){
	$listId=0;
        if(count($userIdsArr) > 0) {
            $finalafterAllIds = array();
            $numEmail = count($userIdsArr);
            $k = 0;
            for($i = 0 ; $i < $numEmail; $i++) {
                $finalafterAllIds[$k][$i] = $userIdsArr[$i];
                if($i == ($k+1)*($this->mailerconfig->listUserMaximumLimit)) {
                    $k++;
                } 
            }
            $listId=-1;
            $this->mailerconfig->getDbConfig($appID,$dbConfig);
            $dbHandle  = $this->_loadDatabaseHandle();
            for($jkl = 0; $jkl < count($finalafterAllIds); $jkl ++) {
                $finalUserIds = implode(",",$finalafterAllIds[$jkl]);
                $countMail = count($userIdsArr);
                if($jkl == 0) {
                    $queryCmdInsert = "INSERT INTO list VALUES('','TempName', 'TempDesc', ".$dbHandle->escape($finalUserIds).", 'false', ".$dbHandle->escape($countMail).", 'false' , NOW(), ".$dbHandle->escape($user).",0)";
                }else {
                    $queryCmdInsert = "INSERT INTO list VALUES('','TempName', 'TempDesc', ".$dbHandle->escape($finalUserIds).", 'false', ".$dbHandle->escape($countMail).", 'false' , NOW(), ".$dbHandle->escape($user).",".$dbHandle->escape($listId).")";
                } 
                try{
                    $queryTemp = $dbHandle->query($queryCmdInsert);
                    if($jkl == 0){
                        $listId = $dbHandle->insert_id();
			$queryCmdUpdate = "update list set masterListId=$listId where id=$listId ";
			$queryTempUpdate = $dbHandle->query($queryCmdUpdate);
                    }
                }catch(Exception $e) {
                    error_log("TYUI Done3". $dbHandle->_error_message());
                }
            }
        }
	return $listId;
    }

// gets the list info. similar to function getListInfoFunc()
    function getListInfo($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $listId = $parameters['1'];
        $userId = $parameters['2'];
        $userGroup = $parameters['3'];
        $response = $this->getListInfoFunc($appID, $listId, $userId, $userGroup);
        return $this->xmlrpc->send_response($response);
    }

    // gets the csv list info.
    function getListCsvInfoFunc($appID, $listId, $userId, $userGroup, $getUserList = "true", $num=100) {
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        $shikshadbHandle  = $this->_loadDatabaseHandle('read','User');
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $userArray = array();
        $queryCmd = 'select * from list where masterListId=?';
        $query = $dbHandle->query($queryCmd, array($listId));
        foreach ($query->result() as $row) {
            if($row->flagGeneric == "false") {
                if($getUserList == "true") {
                    $uids = $row->userIds;
                    $queryCmd = 'select * from shiksha.tuser where userid in ('.$uids.') limit '.$num;
                    $query = $shikshadbHandle->query($queryCmd);
                    foreach ($query->result() as $userRow) {
                        array_push($userArray, array('displayname'=>$userRow->displayname, 'profession' =>$userRow->profession, 'userId' =>$userRow->userid, 'email'=>$userRow->email, 'mobile'=>$userRow->mobile));
                    }
                }
            }else {
                $queryCmd = 'select * from csvKeyValue where listId=? and keyName="email"';
                $query = $dbHandle->query($queryCmd, array($listId));
                $count = 0;
                $userArray = array();
                foreach ($query->result() as $userRow) {
                    $count++;
                    array_push($userArray, array('displayname'=>$userRow->value));
                }
                if($count == 0){
                    $queryCmd = 'select * from csvKeyValue where listId=? and keyName="phone" limit 100';
                    $query = $dbHandle->query($queryCmd, array($listId));
                    $userArray = array();
                    foreach ($query->result() as $userRow) {
                        array_push($userArray, array('displayname'=>$userRow->value));
                    }
                }
            }
        }
        $userArrayStr = json_encode($userArray);
        $msgArray = array();
        array_push($msgArray,array(
                array(
                    'id'=>array($row->id,'string'),
                    'name'=>array($row->name,'string'),
                    'description'=>array($row->description,'string'),
                    'createdOn'=>array($row->createdOn,'string'),
                    'createdBy'=>array($row->createdBy,'string'),
                    'isActive'=>array($row->isActive,'string'),
                    'isCsv'=>array($row->flagGeneric,'string'),
                    'numUsers'=>array($row->numUsers,'string'),
                    'usersArr'=>array($userArrayStr,'string')
                    ),'struct'));//close array_push
        $response = array($msgArray,'struct');
        return $response;
    }


    // gets the list info. 
    function getListInfoFunc($appID, $listId, $userId, $userGroup, $getUserList = "true", $num=100) {
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = 'select * from list where masterListId=? and id=masterListId';
        $query = $dbHandle->query($queryCmd, array($listId));
        foreach ($query->result() as $row) {
                if($getUserList == "true") {
                if($row->flagGeneric == "false") {
                    $uids = $row->userIds;
                    $queryCmd = 'select * from shiksha.tuser where userid in ('.$uids.') limit '.$num;
                    $query = $shikshadbHandle->query($queryCmd);
                    $userArray = array();
                    foreach ($query->result() as $userRow) {
                        array_push($userArray, array('displayname'=>$userRow->displayname, 'profession' =>$userRow->profession, 'userId' =>$userRow->userid));
                    }
                    $userArrayStr = json_encode($userArray);
                }
                else {
                $queryCmd = 'select * from csvKeyValue where listId=? and keyName="email" limit 100';
                $query = $dbHandle->query($queryCmd, array($listId));
                $count = 0;
                $userArray = array();
                foreach ($query->result() as $userRow) {
                    $count++;
                    array_push($userArray, array('displayname'=>$userRow->value));
                }
                if($count == 0){
                    $queryCmd = 'select * from csvKeyValue where listId=? and keyName="phone" limit 100';
                    $query = $dbHandle->query($queryCmd, array($listId));
                    $userArray = array();
                    foreach ($query->result() as $userRow) {
                        array_push($userArray, array('displayname'=>$userRow->value));
                    }
                }
                $userArrayStr = json_encode($userArray);
                }
            }
            else {
                $userArrayStr = "";
            }
        }
        $msgArray = array();
        array_push($msgArray,array(
                array(
                    'id'=>array($row->id,'string'),
                    'name'=>array($row->name,'string'),
                    'description'=>array($row->description,'string'),
                    'createdOn'=>array($row->createdOn,'string'),
                    'createdBy'=>array($row->createdBy,'string'),
                    'isActive'=>array($row->isActive,'string'),
                    'isCsv'=>array($row->flagGeneric,'string'),
                    'numUsers'=>array($row->numUsers,'string'),
                    'usersArr'=>array($userArrayStr,'string')
                    ),'struct'));//close array_push
        $response = array($msgArray,'struct');
        return $response;
    }





    // gets the template info.

    function getTemplateInfo($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $templateId = $parameters['1'];
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = 'select * from mailerTemplate where isActive="true" and id=?';
        $query = $dbHandle->query($queryCmd, array($templateId));

        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                array(
                    'id'=>array($row->id,'string'),
                    'name'=>array($row->name,'string'),
                    'description'=>array($row->description,'string'),
                    'subject'=>array($row->subject,'string'),
                    'htmlTemplate'=>array($row->htmlTemplate,'string'),
                    'createdOn'=>array($row->createdOn,'string'),
                    'updatedOn'=>array($row->updatedOn,'string'),
                    'createdBy'=>array($row->createdBy,'string'),
                    'isActive'=>array($row->isActive,'string'),
                    'templateType'=>array($row->templateType,'string')
                ),'struct'));//close array_push
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);

    }

// submit to the search query which a person made while creating a list.
    function s_submitSearchQuery($request) {
        error_log("TYUI 1234");
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $dataArrJson = $parameters['1'];
        $user = $parameters['2'];
        $userGroup = $parameters['3'];
        $dataArr = json_decode($dataArrJson,true);
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        $shikshadbHandle  = $this->_loadDatabaseHandle('read','User');
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $finalQuery = "select shiksha.tuser.userid as userid from shiksha.tuser,shiksha.tuserflag where newsletteremail=1 and tuserflag.userId=tuser.userid and tuserflag.ownershipchallenged='0' and abused='0'";
 
        error_log("TYUI1 ".$finalQuery);
        error_log("TYUI ".print_r($dataArr,true));
        foreach ($dataArr as $key=>$val)  {
            if(isset($val[0]['value'])) {
                if(empty($val[0]['value'])) {
                    continue;
                }
            }
            $queryCmd = 'select * from filterTable where filterTable.filterId=? limit 1';
            error_log("TYUI12 ".$queryCmd);
            $query = $dbHandle->query($queryCmd, array($key));
            error_log("TYUI12 ".$queryCmd);
            foreach ($query->result() as $row){
                $filterQuery = $row->query;
                error_log("TYUIQWE111 = ".print_r($val,true));
                error_log("TYUIQWE111 = ".print_r($val[0],true));
                if(isset($val['0']['value']) && is_array($val['0'])) {
                    //error_log("IN VALUE ".$key);
                    $tempArr = array();
                    error_log("IN TYUIQUE hahaa123");
                    error_log("TYUIQWE = ".print_r($val,true));
                    for($jkl = 0 ;$jkl < count($val);$jkl++) {
                        $tempArr[$jkl] = $val[$jkl]['value'];
                    }
                    $allfilterValIds = implode(",", $tempArr);
                    $finalFilterQuery = $this->replace("ALL", $allfilterValIds, $filterQuery);
                    $finalFilterQuery = $this->replace("0", $tempArr[0], $finalFilterQuery);
                    $finalFilterQuery = $this->replace("1", $tempArr[1], $finalFilterQuery);
                }else {
                    $allfilterValIds = implode(",", $val);
                    $finalFilterQuery = $this->replace("ALL", $allfilterValIds, $filterQuery);
                    $finalFilterQuery = $this->replace("0", $val[0], $finalFilterQuery);
                    $finalFilterQuery = $this->replace("1", $val[1], $finalFilterQuery);
                }
                if(strlen($finalQuery) > 0){
                    $finalQuery = $this->replace("Other", $row->tableName." (".$finalQuery.")", $finalFilterQuery);
                }else {
                    $finalQuery = $this->replace("Other", "", $finalFilterQuery);
                }
            }
            error_log("TYUI2 ".$finalQuery);
        }
        error_log("TYUI3 ".$finalQuery);
        $query = $dbHandle->query($finalQuery);
        error_log("TYUI4 ".$finalQuery);
        $userIds = array();
        $k = 0;
        $userAssoc = array();
        foreach ($query->result() as $row){
            if($userAssoc[$row->userid] != 1) {
                $userIds[$k]=$row->userid;
                $userAssoc[$row->userid] = 1;
                $k++;
            }
        }
        error_log("TYUI5 Done");
        //error_log("CHECK".print_r($userIds,true));
        if(count($userIds) == 0) {
            $msgArray = array();
            $msgArray[0] = "-1";
            $response = array($msgArray,'struct');
        }else {
            error_log("TYUI Done1");
            $response = $this->createTempList($userIds, $user); 
        }
        error_log("TYUI ".print_r($response,true));
        return $this->xmlrpc->send_response($response);


    }
// to create the search form.
    function s_getSearchFormData($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = 'select * from filterTable left join filterValueTable  on  filterTable.filterId=filterValueTable.filterId';
        $query = $dbHandle->query($queryCmd);

        $returnArr = array();
        $filterDoneArr = array();
        $k = 0;
        $checkArr = array();
        foreach ($query->result() as $row){
            //error_log("DODO1 Filter Id = ".$filterDoneArr[$row->filterId]);
            if(strlen($filterDoneArr[$row->filterId]) > 0) {
                //error_log("DODO1 Inside Id = ".$filterDoneArr[$row->filterId]);
// Hacky code 
                $returnArr[$filterDoneArr[$row->filterId]][$checkArr[$filterDoneArr[$row->filterId]]]['filterValueName'] = $row->filterValueName;
                $returnArr[$filterDoneArr[$row->filterId]][$checkArr[$filterDoneArr[$row->filterId]]]['filterValueId'] = $row->filterValueId;
                $returnArr[$filterDoneArr[$row->filterId]][$checkArr[$filterDoneArr[$row->filterId]]]['filterId'] = $row->filterId;
                $checkArr[$filterDoneArr[$row->filterId]]++;

            }else {
                //error_log("DODO Filter Id = ".$row->filterId);
                //error_log("DODO1 ELSE Id = ".$filterDoneArr[$row->filterId]);
                $filterDoneArr[$row->filterId] = count($returnArr);

                $returnArr[$filterDoneArr[$row->filterId]]["filterName"] = $row->filterName;
                $returnArr[$filterDoneArr[$row->filterId]]["filterType"] = $row->filterType;
                $returnArr[$filterDoneArr[$row->filterId]]["filterId"] = $row->filterId;
                $returnArr[$filterDoneArr[$row->filterId]][0]['filterValueName'] = $row->filterValueName;
                $returnArr[$filterDoneArr[$row->filterId]][0]['filterValueId'] = $row->filterValueId;
                $returnArr[$filterDoneArr[$row->filterId]][0]['filterId'] = $row->filterId;
                $checkArr[$filterDoneArr[$row->filterId]] = 1;
            }

        }
//        //error_log("DODO ".print_r($returnArr,true));
        //error_log("DODO ".print_r($filterDoneArr,true));
        $returnArrStr = json_encode($returnArr);
        $response = array(array($returnArrStr,"string"),'struct');
        return $this->xmlrpc->send_response($response);

    }

// test function not used now.
    function s_getSearchFormData1($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $returnArr = array();
        $returnArr[0]['filterName'] = "City";
        $returnArr[0]['filterType'] = "MultiSelect";
        $returnArr[0]['filterId'] = "1";
        $returnArr[0][0]['filterValueName'] = "Delhi";
        $returnArr[0][0]['filterValueId'] = "1";
        $returnArr[0][0]['filterId'] = "1";
        $returnArr[0][1]['filterValueName'] = "Noida";
        $returnArr[0][1]['filterValueId'] = "2";
        $returnArr[0][1]['filterId'] = "1";
        $returnArr[0][2]['filterValueName'] = "Kanpur";
        $returnArr[0][2]['filterValueId'] = "3";
        $returnArr[0][2]['filterId'] = "1";

        $returnArr[0]['filterName'] = "Category";
        $returnArr[0]['filterType'] = "MultiSelect";
        $returnArr[0]['filterId'] = "2";
        $returnArr[0][0]['filterValueName'] = "Management";
        $returnArr[0][0]['filterValueId'] = "1";
        $returnArr[0][0]['filterId'] = "2";
        $returnArr[0][1]['filterValueName'] = "IT";
        $returnArr[0][1]['filterValueId'] = "2";
        $returnArr[0][1]['filterId'] = "2";
        $returnArr[0][2]['filterValueName'] = "Engineering";
        $returnArr[0][2]['filterValueId'] = "3";
        $returnArr[0][2]['filterId'] = "2";
        $returnArrStr = json_encode($returnArr);
        $response = array(array($returnArrStr,"string"),'struct');
        return $this->xmlrpc->send_response($response);

    }


//    Get All the tracking urls of the mailer.

    function getMailerTrackingUrls($request) {
        $parameters   = $request->output_parameters(FALSE,FALSE);
        $appID        = $parameters['0'];
        $userId       = $parameters['1'];
        $userGroup    = $parameters['2'];
        $mailerId     = $parameters['3'];
        $origMailerId = $mailerId;
        $mailerId     = $mailerId;
        $trackerId    = $parameters['4'];
        $startTime    = $parameters['5'];
        $endTime      = $parameters['6'];
        if(trim($startTime) == "") {
            $startTime = "2000-01-01";
        }
        if(trim($endTime) == "") {
            $endTime = "2200-01-01";
        }
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        if($trackerId != ""){
            $queryCmd = "select *,count(*) as count from mailerMis where mailerId=".$dbHandle->escape($mailerId)." and trackerId=".$dbHandle->escape($trackerId)." and date(date) >= ".$dbHandle->escape($startTime)." and date(date) <= ".$dbHandle->escape($endTime)." group by trackerId";
        }else {
            $queryCmd = "select *,count(*) as count from mailerMis where mailerId=".$dbHandle->escape($mailerId)." and date(date) >= ".$dbHandle->escape($startTime)." and date(date) <= ".$dbHandle->escape($endTime)." group by trackerId";
        }

        error_log('MAILTHEQUERY'.$queryCmd);
        error_log('MAILTHEQUERY_'.THIS_CLIENT_IP);
        $query = $dbHandle->query($queryCmd);

		$openRate = 0;
		$clickRate = 0;
        $total_click_rate = 0;
        $spam = 0;
        $unsubscribe = 0;
		
        $msgArray = array();
        foreach ($query->result() as $row){		  

            $row->trackerId = parseUrlFromContent($row->trackerId);

            if(!$row->trackerId) {
                $openRate = $row->count;
            }

            else if($row->trackerId && strpos($row->trackerId,'mailReportSpam=1') !== FALSE){
                $spam = $spam + $row->count;
            }

            else if($row->trackerId && strpos($row->trackerId,'encodedUnsubscribeURL') !== FALSE){
                $unsubscribe = $unsubscribe + $row->count;
            }
            else {
                $total_click_rate = $total_click_rate + $row->count;
            }

            if($row->trackerId) {
                array_push($msgArray,array(
                       array(
                            'id'=>array($row->id,'string'),
                            'mailerId'=>array($row->mailerId,'string'),
                            'trackerId'=>array($row->trackerId,'string'),
                            'count'=>array($row->count,'string'),
                        ),'struct'));//close array_push
            }

        }		

	    //error_log(print_r($queryCmd, true));
        //$queryCmd = "select numUsers from list, mailer where mailer.id='$origMailerId' and mailer.listId=list.id";
        $queryCmd = "select count(*) as numUsers  from mailQueue where mailerid = ? and issent = 'yes'";
        $query = $dbHandle->query($queryCmd, array($origMailerId));

        foreach ($query->result() as $row){
            $numUsers = $row->numUsers;                
        }

		if($total_click_rate) {	
			array_unshift($msgArray,array(
				array(
					'id'=>array("-3",'string'),
					'mailerId'=>array($mailerId,'string'),
					'trackerId'=>array("Click Rate",'string'),
					'count'=>array($total_click_rate,'string'),
				),'struct'));//close array_push				
		}	
        if($spam) { 
            array_unshift($msgArray,array(
                array(
                    'id'=>array("-4",'string'),
                    'mailerId'=>array($mailerId,'string'),
                    'trackerId'=>array("Spam Rate",'string'),
                    'count'=>array($spam,'string'),
                ),'struct'));//close array_push             
        }   
        if($unsubscribe) { 
            array_unshift($msgArray,array(
                array(
                    'id'=>array("-5",'string'),
                    'mailerId'=>array($mailerId,'string'),
                    'trackerId'=>array("Unsubscribe Rate",'string'),
                    'count'=>array($unsubscribe,'string'),
                ),'struct'));//close array_push             
        }   
		
		array_unshift($msgArray,array(
            array(
                'id'=>array("-2",'string'),
                'mailerId'=>array($mailerId,'string'),
                'trackerId'=>array("Open Rate",'string'),
                'count'=>array($openRate,'string'),
            ),'struct'));//close array_push
		
        array_unshift($msgArray,array(
            array(
                'id'=>array("-1",'string'),
                'mailerId'=>array($mailerId,'string'),
                'trackerId'=>array("Mails Sent",'string'),
                'count'=>array($numUsers,'string'),
            ),'struct'));//close array_push

        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

// Gets all the mailer list to show in the MIS.
    function getMailersList($request) {
        ini_set('display_errors',1);
        ini_set("memory_limit", -1);

        $parameters  = $request->output_parameters(FALSE,FALSE);
        $appID       = $parameters['0'];
        $userId      = $parameters['1'];
        $userGroup   = $parameters['2'];
        $range       = $parameters['3'];
        $groupId     = $parameters['4'];
        $adminType   = $parameters['5'];
        $groupFilter = $parameters['6'];
        $timeStart   = $parameters['7']." 00:00:00";
        $timeEnd     = $parameters['8']." 23:59:59";
        $status      = $parameters['9'];


        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }

	    $msgArray = array();        
        if(empty($range)) {
		    $range = 3;
        }
 
        $queryCmd = "select id,mailername, totalMailsToBeSent,time,userId  from mailer where mailertype = 'client' AND time >= ".$dbHandle->escape($timeStart)." AND time <= ".$dbHandle->escape($timeEnd);
        if($adminType == 'group_admin') {
            $queryCmd .= " and group_id = ".$dbHandle->escape($groupId);
        } else if ($adminType == 'normal_admin') {
            $queryCmd .= " and userId = ".$dbHandle->escape($userId);
        } else if($adminType == 'super_admin' && $groupFilter != ""){
            $queryCmd .= " and group_id = ".$dbHandle->escape($groupFilter);
        }
        if(!empty($status)) {
            $queryCmd .= " and mailsSent = ".$dbHandle->escape($status);
        }
        
        $startTime = microtime_float();  
        $query = $dbHandle->query($queryCmd);
        $mailers_ids = array(); 
        $result_array = $query->result_array();
        $startTime = microtime_float();       
        
        foreach($result_array as $row){
            $msgArray[$row['id']]['total'] = $row['totalMailsToBeSent'];
            $msgArray[$row['id']]['id'] = $row['id'];
            $msgArray[$row['id']]['mailername'] = $row['mailername'];
            $msgArray[$row['id']]['time'] = substr($row['time'],0,-9);
	        $mailers_ids[] = $row['id'];	
            $msgArray[$row['id']]['userId'] = $row['userId'];    
        }

        if(!empty($mailers_ids)) {
            $queryCmd = "SELECT mailerid,issent,count(*) as count FROM mailQueue WHERE mailerid IN (".implode(",",$mailers_ids).")  group by mailerid,issent";
    		
            $startTime = microtime_float();
            $query = $dbHandle->query($queryCmd);
            $result_array = $query->result_array();
            
            $startTime = microtime_float();
            foreach($result_array as $row){
                $msgArray[$row['mailerid']]['processed'][] = $row['count'];
                if($row['issent'] == 'yes') {
    		        $msgArray[$row['mailerid']]['sent'] = $row['count'];
    	        } 
            } 
            
            $startTime = microtime_float();
            
            foreach($msgArray as $key=>$val) {
                $count = array_sum($msgArray[$key]['processed']);
		        unset($msgArray[$key]['processed']);
                $msgArray[$key]['processed'] = $count;
		    } 
            
            $startTime = microtime_float();
            foreach($msgArray as $mailerid=>$valArr){
                if(!$valArr['sent'])
                    $msgArray[$mailerid]['sent'] = 0;
                if(!$valArr['processed'])
                    $msgArray[$mailerid]['processed'] = 0;
            }

            krsort($msgArray);
        }
        
        $response = utility_encodeXmlRpcResponse($msgArray);
        return $this->xmlrpc->send_response($response);
    }


    // the cron to run the  mailer, this gets curled whenever we want to send the mailer.
    function runCronMailer($request) {
        mail("naveen.bhola@shiksha.com", "Not in used function called.", "Function : runCronMailer");
        return;
		set_time_limit(0);
		$this->dbLibObj = DbLibCommon::getInstance('Mailer');
        $this->load->library(array('mailerconfig','Alerts_client','subscription_client','sums_product_client'));
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
	//Added a limit in this query to maintain the number of mails to be sent
        $queryCmd = "SELECT mailer.* FROM mailer, mailerTemplate WHERE mailsSent in ('false','inProgress') and mailer.templateId=mailerTemplate.id and mailerTemplate.templateType='mail'";
        $queryUp = $dbHandle->query($queryCmd);
        $insertedMailerIdArr = array();
        $i=0;
        foreach ($queryUp->result() as $rowUp){
            $insertedMailerIdArr[$i] = $rowUp->id;
            //$queryCmd = "update mailer set mailsSent='inProgress' where id='".$insertedMailerIdArr[$i]."'";
            //$dbHandle->query($queryCmd);
            $i++;
        }   
        $insertedMailerIdStr = implode(",",$insertedMailerIdArr);
        $queryCmd = "SELECT * FROM mailer WHERE id in ($insertedMailerIdStr)";
        $queryUp = $dbHandle->query($queryCmd);
        foreach ($queryUp->result() as $rowUp){
            $listId = $rowUp->listId;
            $templateId = $rowUp->templateId;
            $insertedMailerId = $rowUp->id;
            $loggedInUser = $rowUp->userId;
            $mailerId = $rowUp->id;
            $time = $rowUp->time;
            $senderMail = $rowUp->senderMail;
            $listIdReturn = $this->getListIds($mailerId,$listId);
	    if($listIdReturn!="-1"){
	      //Check if the mailer entry is false
	      $mailsSentStatus = 'false';
	      $queryCmdCheck = "select mailsSent from mailer where id=?";
	      $queryCheck = $dbHandle->query($queryCmdCheck, array($mailerId));
	      foreach ($queryCheck->result() as $rowCheck){
		  $mailsSentStatus = $rowCheck->mailsSent;
	      }
	      //Now update the mailer to inProgress since we need to send the mails for it
	      if($mailsSentStatus == 'false'){
		$this->_updateMailer(12,$mailerId,'inProgress');
	      }else{
		continue;
	      }
	    }
	    $numberMailsSent = $rowUp->numberMailsSent;	//Added to find the number of mails already sent 
            $flagMultipleListMailer = "false";
            if($listId <= 0) {
                $flagMultipleListMailer = "true";
            }
            $queryCmdT = 'SELECT * FROM mailerTemplate WHERE isActive="true" and id=?';
	    $dbHandle = $this->getDBHandle($dbHandle, array($templateId));	//Added to check the DB connection
            $queryT = $dbHandle->query($queryCmdT);

            foreach ($queryT->result() as $rowT){
                $subjectT = $rowT->subject;
                $htmlTemplateT = $rowT->htmlTemplate;
                $templateTypeT = $rowT->templateType;
            }
            $queryCmd = "SELECT * FROM list WHERE masterListId in ($listIdReturn)";
	    $dbHandle = $this->getDBHandle($dbHandle);	//Added to check the DB connection
            $query = $dbHandle->query($queryCmd);
	    try{
		foreach ($query->result() as $row){
		    $newListId = $row->id;
		    if($row->flagGeneric == "false") 
		    {
			$userArr = explode(",",$row->userIds);
			//Send mail to all the users in User array
			$numberMailsSent = $this->sendGenericCronMail($userArr,$numberMailsSent,$templateId, $loggedInUser, $emailId , $senderMail, $time, $insertedMailerId,$subjectT, $htmlTemplateT,$templateTypeT);
		    }
		    else 
		    {
			if($flagMultipleListMailer=="true"){
			    $k = 1;
			    while(1){
				$queryCmd = 'SELECT * FROM csvKeyValue WHERE listId=? and num < 10000*'.$k.' and num >= 10000*'.($k-1);
				$userDataArr = array(); 
				$dbHandle = $this->getDBHandle($dbHandle);	//Added to check the DB connection
				$query = $dbHandle->query($queryCmd, array($newListId));
				foreach ($query->result() as $userRow) {
				    if(!isset($userDataArr[$userRow->num])) {
					$userDataArr[$userRow->num] = array();
				    }
				    $userDataArr[$userRow->num][$userRow->keyName] = $userRow->value;
				}
				if(count($userDataArr) == 0) {
				    break;
				}
				$this->sendCsvMail($templateId, $loggedInUser, $emailId , $userDataArr , $senderMail, $time,"false", $insertedMailerId);
				$k++;
			    }
			}
			else{
			    //Send mail to all the users in User array
			    $this->sendCsvCronMail($newListId, $numberMailsSent,$templateId, $loggedInUser, $emailId , $senderMail, $time, $insertedMailerId);
			}
		    }
		    //$dbHandle = getDBHandle($dbHandle);	//Added to check the DB connection
		    //$queryCmd = "update mailer_list set mailsSent='true' where mailerId='$mailerId' and listId='".$newListId."'";
		    //$query123 = $dbHandle->query($queryCmd);
		    $this->_updateMailerList(12,$mailerId,$newListId,'true');
		}
	    }catch(Exception $e){			//In case of exception, reset the current mailer status
		error_log("An exception has occured:".$e->getMessage());
		$this->_updateMailer(12,$insertedMailerId,'false');
		return;
	    }

	    //$dbHandle = $this->getDBHandle($dbHandle);	//Added to check the DB connection
            if($flagMultipleListMailer != "true") {
                //$queryCmd = "update mailer set mailsSent='true' where id='$insertedMailerId'";
                //$queryUp1 = $dbHandle->query($queryCmd);
		$this->_updateMailer(12,$insertedMailerId,'true');
            }else {
                //$queryCmd = "update mailer set mailsSent='false' where id='$insertedMailerId'";
                //$queryUp1 = $dbHandle->query($queryCmd);
		$this->_updateMailer(12,$insertedMailerId,'false');
            }
        }
        $msgArray = array("-1");
        $response = array($msgArray,'struct');
    }

    // the cron to run the  mailer, this gets curled whenever we want to send the mailer.
    function runCronSms($request) {
        mail("naveen.bhola@shiksha.com", "Not in used function called.", "Function : runCronSms");
        return;
        $this->load->library(array('mailerconfig','Alerts_client','subscription_client','sums_product_client'));
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
	//Added a limit in this query to maintain the number of mails to be sent
        $queryCmd = "select mailer.* from mailer, mailerTemplate where mailsSent='false' and mailer.templateId=mailerTemplate.id and mailerTemplate.templateType='sms'";
        $queryUp = $dbHandle->query($queryCmd);
        $insertedMailerIdArr = array();
        $i=0;
        foreach ($queryUp->result() as $rowUp){
            $insertedMailerIdArr[$i] = $rowUp->id;
            //$queryCmd = "update mailer set mailsSent='inProgress' where id='".$insertedMailerIdArr[$i]."'";
            //$dbHandle->query($queryCmd);
            $i++;
        }   
        $insertedMailerIdStr = implode(",",$insertedMailerIdArr);
        $queryCmd = "select * from mailer where id in ($insertedMailerIdStr)";
        $queryUp = $dbHandle->query($queryCmd);
        foreach ($queryUp->result() as $rowUp){
            $listId = $rowUp->listId;
            $templateId = $rowUp->templateId;
            $insertedMailerId = $rowUp->id;
            $loggedInUser = $rowUp->userId;
            $mailerId = $rowUp->id;
            $time = $rowUp->time;
            $senderMail = $rowUp->senderMail;
            $listIdReturn = $this->getListIds($mailerId,$listId);
	    if($listIdReturn!="-1"){	//Now update the mailer to inProgress since we need to send the mails for it
	      //Check if the mailer entry is false
	      $mailsSentStatus = 'false';
	      $queryCmdCheck = "select mailsSent from mailer where id=?";
	      $queryCheck = $dbHandle->query($queryCmdCheck, array($mailerId));
	      foreach ($queryCheck->result() as $rowCheck){
		  $mailsSentStatus = $rowCheck->mailsSent;
	      }
	      if($mailsSentStatus == 'false'){
		$this->_updateMailer(12,$mailerId,'inProgress');
	      }else{
		continue;
	      }
	    }
	    $numberMailsSent = $rowUp->numberMailsSent;	//Added to find the number of mails already sent 
            $flagMultipleListMailer = "false";
            if($listId <= 0) {
                $flagMultipleListMailer = "true";
            }
            $queryCmdT = 'select * from mailerTemplate where isActive="true" and id=?';
            $queryT = $dbHandle->query($queryCmdT, array($templateId));

            foreach ($queryT->result() as $rowT){
                $subjectT = $rowT->subject;
                $htmlTemplateT = $rowT->htmlTemplate;
                $templateTypeT = $rowT->templateType;
            }
            $queryCmd = "select * from list where masterListId in ($listIdReturn) and createdOn > '2011-08-28'";
	    $dbHandle = $this->getDBHandle($dbHandle);	//Added to check the DB connection
            $query = $dbHandle->query($queryCmd);
	    try
	    {
		foreach ($query->result() as $row)
		{
		    $newListId = $row->id;
		    if($row->flagGeneric == "false") 
		    {
			$userArr = explode(",",$row->userIds);
			//Send mail to all the users in User array
			$numberMailsSent = $this->sendGenericCronMail($userArr,$numberMailsSent,$templateId, $loggedInUser, $emailId , $senderMail, $time, $insertedMailerId,$subjectT, $htmlTemplateT,$templateTypeT);
		    }
		    else
		    {
			if($flagMultipleListMailer=='true'){
			    $k = 1;
			    while(1)
			    {
				$dbHandle = $this->getDBHandle($dbHandle);	//Added to check the DB connection
				$queryCmd = 'select * from csvKeyValue where listId=? and num < 10000*'.$k.' and num >= 10000*'.($k-1);
				$userDataArr = array(); 
				$query = $dbHandle->query($queryCmd, array($newListId));
				foreach ($query->result() as $userRow) {
				    if(!isset($userDataArr[$userRow->num])) {
					$userDataArr[$userRow->num] = array();
				    }
				    $userDataArr[$userRow->num][$userRow->keyName] = $userRow->value;
				}
				if(count($userDataArr) == 0) {
				    break;
				}
				$this->sendCsvMail($templateId, $loggedInUser, $emailId , $userDataArr , $senderMail, $time,"false", $insertedMailerId);
				$k++;
			    }
			}
			else{
			    //Send mail to all the users in User array
			    $this->sendCsvCronMail($newListId, $numberMailsSent,$templateId, $loggedInUser, $emailId , $senderMail, $time, $insertedMailerId);
			}
		    }
		    //$dbHandle = getDBHandle($dbHandle);	//Added to check the DB connection
		    //$queryCmd = "update mailer_list set mailsSent='true' where mailerId='$mailerId' and listId='".$newListId."'";
		    //$query123 = $dbHandle->query($queryCmd);
		    $this->_updateMailerList(12,$mailerId,$newListId,'true');
		}
	    }catch(Exception $e){	//In case of exception, reset the current mailer status
		error_log("An exception has occured:".$e->getMessage());
		$this->_updateMailer(12,$insertedMailerId,'false');
		return;
	    }
	    //$dbHandle = $this->getDBHandle($dbHandle);	//Added to check the DB connection
            if($flagMultipleListMailer != "true") {
                //$queryCmd = "update mailer set mailsSent='true' where id='$insertedMailerId'";
                //$queryUp1 = $dbHandle->query($queryCmd);
		$this->_updateMailer(12,$insertedMailerId,'true');
            }else {
                //$queryCmd = "update mailer set mailsSent='false' where id='$insertedMailerId'";
                //$queryUp1 = $dbHandle->query($queryCmd);
		$this->_updateMailer(12,$insertedMailerId,'false');
            }
        }
        $msgArray = array("-1");
        $response = array($msgArray,'struct');
    }

    /**
    *
    * Function to check the DB handle. If it is empty, then create a connection and return
    * Input : dbHandle
    * Output : dbHandle
    */
    function getDBHandle($dbHandle,$appId=12) {
        //if($dbHandle == '')
	{
	    //$counter = 0;
	    //while(($dbHandle == '')||($counter<100)){	//We will keep trying to get the DB connection again and again
	      $this->load->library(array('mailerconfig'));
	      $this->mailerconfig->getDbConfig($appID,$dbConfig);
	      $dbHandle  = $this->_loadDatabaseHandle();
	      //$counter++;
	      //sleep(60);
	    //}
	      while($dbHandle == ''){
		  sleep(3);
		  $this->mailerconfig->getDbConfig($appID,$dbConfig);
		  $dbHandle  = $this->_loadDatabaseHandle();
	      }
        }
	return $dbHandle;
    }

    /**
    *
    * Function to increment the count in mailer for mails sent
    * Input : mailerId, number
    * Output : none
    */
    function incrementCounter($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $mailerId = $parameters['1'];
        $number = $parameters['2'];
	$ret = $this->_incrementCounter($appID, $mailerId, $number);
        $resp = array($ret,"string");
        return $this->xmlrpc->send_response ($resp);
    }

    /**
    *
    * Function to update the mailer table
    * Input : mailerId, status
    * Output : none
    */
    function updateMailer($request){
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $mailerId = $parameters['1'];
        $status = $parameters['2'];
        $ret = $this->_updateMailer($appID, $mailerId, $status);
        $resp = array($ret,"string");
        return $this->xmlrpc->send_response ($resp);
    }

    /**
    *
    * Function to update the mailer_list table
    * Input : mailerId, listId, status
    * Output : none
    */
    function updateMailerList($request){
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $mailerId = $parameters['1'];
        $listId = $parameters['2'];
        $status = $parameters['3'];
        $ret = $this->_updateMailerList($appID, $mailerId, $listId, $status);
        $resp = array("1",'string');
        return $this->xmlrpc->send_response ($resp);
    }

    /**
    *
    * Function to send generic mail from the cron. This will be called from mail and sms cron.
    * Input : userArr,numberMailsSent,templateId, loggedInUser, emailId , senderMail, time, insertedMailerId,subjectT, htmlTemplateT,templateTypeT
    * Output : numberMailsSent
    */
    function sendGenericCronMail($userArr,$numberMailsSent,$templateId, $loggedInUser, $emailId , $senderMail, $time, $insertedMailerId,$subjectT, $htmlTemplateT,$templateTypeT){
        $this->load->library(array('mailerconfig'));
	$randvalue=rand(0,100);
	$appId = 12;
	$listMailsAlreadySent = $numberMailsSent;
	for($i = $numberMailsSent; $i < count($userArr); $i++) {
	    //For every mailer, send a random mail to the shiksha members
	    $sendshikshadevmail=false;
	    if($i==$randvalue){
		$sendshikshadevmail=true;
	    }
	    $this->sendMail($templateId, $loggedInUser, $emailId , $userArr[$i], $senderMail, $time,"false", $insertedMailerId,$subjectT, $htmlTemplateT,$templateTypeT,$sendshikshadevmail); 
	    //In case the mail cron was crashed in this list entry, decrement the numberMailsSent value so that further list entries wll not be affected
	    if($numberMailsSent<($this->mailerconfig->listUserMaximumLimit) && $numberMailsSent>0){
	      $numberMailsSent = 0;
	    }
	    //After sending the mails, increment the counter after every 100 mails. This will help us in keeping the track of number of mails sent
	    if(($i%100)==99 && ($i!=0)){
	      $this->_incrementCounter($appId,$insertedMailerId,100);
	    }
	    else if($i==(count($userArr)-1)){	//This is the last mailer
	      $this->_incrementCounter($appId,$insertedMailerId,((($i - $listMailsAlreadySent)%100)+1));
	    }
	}
	if($numberMailsSent>=($this->mailerconfig->listUserMaximumLimit)){
	  $numberMailsSent -= ($this->mailerconfig->listUserMaximumLimit);
	}
	return $numberMailsSent;
    }

    /**
    *
    * Function to send non generic mail which was created using a csv upload from the cron. This will be called from mail and sms cron.
    * Input : newListId, templateId, loggedInUser, emailId , userDataArr , senderMail, time, insertedMailerId
    * Output : none
    */
    function sendCsvCronMail($newListId,$numberMailsSent,$templateId, $loggedInUser, $emailId , $senderMail, $time, $insertedMailerId){
        $this->load->library(array('mailerconfig'));
	if($numberMailsSent>0)
	  $k = ceil($numberMailsSent/100);
	else
	  $k = 1;
	while(1){
	    $queryCmd = 'SELECT * FROM csvKeyValue WHERE listId=? and num < 100*'.$k.' and num >= 100*'.($k-1);
	    $userDataArr = array(); 
	    $dbHandle = $this->getDBHandle($dbHandle);	//Added to check the DB connection
	    $query = $dbHandle->query($queryCmd, array($newListId));
	    foreach ($query->result() as $userRow) {
		if(!isset($userDataArr[$userRow->num])) {
		    $userDataArr[$userRow->num] = array();
		}
		$userDataArr[$userRow->num][$userRow->keyName] = $userRow->value;
	    }
	    if(count($userDataArr) == 0) {
		break;
	    }
	    $this->sendCsvMail( $templateId, $loggedInUser, $emailId , $userDataArr , $senderMail, $time,"false", $insertedMailerId);
	    //After sending the mails, increment the counter after every 100 mails. This will help us in keeping the track of number of mails sent
	    $this->_incrementCounter($appId,$insertedMailerId,count($userDataArr));
	    $k++;
	}
    }

    // add list to the mailer.
    function addListInMailer($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $mailerId = $parameters['1'];
        $listIdNew = $parameters['2'];
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = "select * from mailer where id=? limit 1";
        //error_log("Calling SEND MAIL CHECK".$queryCmd);
        $query = $dbHandle->query($queryCmd, array($mailerId));
        $listId = "";
        foreach ($query->result() as $row){
            $listId = $row->listId;
            $mailsSent = $row->mailsSent;
        }
        if($listId > 0) {
            $queryCmd = "update mailer set listId='-1' where id=?";
            //error_log("Calling SEND MAIL CHECK".$queryCmd);
            $query = $dbHandle->query($queryCmd, array($mailerId));
            $queryCmd = "insert into mailer_list values('',?,?,?, now())";
            //error_log("Calling SEND MAIL CHECK".$queryCmd);
            $query = $dbHandle->query($queryCmd, array($mailerId, $listId, $mailsSent));
        }
        $queryCmd = "insert into mailer_list values('',?,?,'false', now())";
        //error_log("Calling SEND MAIL CHECK".$queryCmd);
        $query = $dbHandle->query($queryCmd, array($mailerId, $listIdNew));
        $queryCmd = "update mailer set mailsSent='false' where id=?";
        $query = $dbHandle->query($queryCmd, array($mailerId));
        $resp = array("1",'string');
        return $this->xmlrpc->send_response ($resp);

    }
        




    // saves the maler and ready to be sent.
    function saveMailer($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $name = $parameters['1'];
        $templateId = $parameters['2'];
        $listId = $parameters['3'];
        $time = $parameters['4'];
        $senderMail = $parameters['5'];
        $loggedInUser = $parameters['6'];
        $userGroup = $parameters['7'];
        $encodedsumsData = $parameters['8'];
        $sumsData = base64_decode($encodedsumsData);
        $sumsData = json_decode($sumsData,true);
        $criteria = $parameters['9'];
        $numUsers = $parameters['10'];
        $sender_name = $parameters['11'];
        $groupId = $parameters['12'];
        $mailStatus = 'false';
        if($parameters['13'] != ''){
           $mailStatus = $parameters['13'];
        }
        $totalUsersInCriteria = $parameters['14'];
        $subject = '';
        if($parameters['15'] != '' && $mailStatus == 'false'){
           $subject = $parameters['15'];
        }

        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        
        $this->load->library('Subscription_client');
        $objSubs = new Subscription_client();
        $consumptionArr = array();
        $consumptionArr['consumptionQuant'] = $numUsers;
        $consumptionArr['clientUserId'] = $sumsData['clientUser'];
        $consumptionArr['sumsUserId'] = $loggedInUser;
        list($usec, $sec) = explode(" ", microtime());
        $timestamp = ((int)$usec + (int)$sec);
        $consumptionArr['consumedTypeId'] = $timestamp;
        $consumptionArr['consumedType'] = "mailer";
        $consumptionArr['startDate'] = date("Y-m-d");
        $consumptionArr['endDate'] = date("Y-m-d"); 
        $consumptionArr['subscriptionId'] = $sumsData['subscriptionId'];

        if((!($sumsData['subscriptionId'] == '' && $sumsData['clientUser'] == '')) && ($mailStatus == 'false')) {
            $return = $objSubs->consumeSubscriptionWithCount("1",$consumptionArr);
            if($return["ERROR"] == 1) {
                $response = array("ERROR"=>1);
                $resp = array($response,'struct');
                return $this->xmlrpc->send_response ($resp);
            }
        }
       


	    $queryCmd = "INSERT INTO `mailer` (`id` ,`mailerName` ,`templateId` ,`listId` ,`userId` ,`time` ,`userGroup` ,`mailsSent` ,`sendername` ,`senderMail` ,`criteria` ,`numberprocessed` ,`totalMailsToBeSent`,batch, group_id, subscriptionDetails, totalUsersInCriteria, subject, clientId) "; 
        $queryCmd.= " values('', '".$dbHandle->escape_str($name)."','".mysql_escape_string($templateId)."', '".mysql_escape_string($listId)."','".mysql_escape_string($loggedInUser)."', '".mysql_escape_string($time)."', '".mysql_escape_string($userGroup)."', '".mysql_escape_string($mailStatus)."','".$dbHandle->escape_str($sender_name)."' ,'".mysql_escape_string($senderMail)."','".mysql_escape_string($criteria)."','0','".trim($numUsers)."','".mysql_escape_string($groupId+2)."', '".mysql_escape_string($groupId)."', '".mysql_escape_string($encodedsumsData)."', '".mysql_escape_string(trim($totalUsersInCriteria))."', '".mysql_escape_string($subject)."','".mysql_escape_string($sumsData['clientUser'])."')";

        $query = $dbHandle->query($queryCmd);
        $insertedMailerId = $dbHandle->insert_id();
        $msgArray = array($insertedMailerId);
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }



// used to send a test mail.
    function sendTestMailer($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $templateId = $parameters['1'];
        $emailId = $parameters['2'];
        $loggedInUser = $parameters['3'];
        $userGroup = $parameters['4'];
        $tempClientId = '1064';

        $queryCmdT = 'select * from mailerTemplate where isActive="true" and id=?';
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }

        $queryT = $dbHandle->query($queryCmdT, array($templateId));

        foreach ($queryT->result() as $rowT){
            $subjectT = $rowT->subject;
            $htmlTemplateT = $rowT->htmlTemplate;
            $templateTypeT = $rowT->templateType;
        }

        $this->sendMail($templateId, $loggedInUser, $emailId , $tempClientId,"test-mail@shiksha.com","0000-00-00 00:00:00" ,"true","-1",$subjectT, $htmlTemplateT,$templateTypeT);
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = "insert into testMailer values('', '".mysql_escape_string($templateId)."','".mysql_escape_string($loggedInUser)."', NOW())";
        $query = $dbHandle->query($queryCmd);
        $msgArray = array($dbHandle->insert_id());
	$response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }


//    used to check wether the mail has been opened i.e. show images has been done or not.
    
    function submitOpenMail($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID      = $parameters['0'];
        $mailerId   = $parameters['1'];
        $emailId    = $parameters['2'];
        $trackerId  = $parameters['3'];
        $mailId     = $parameters['4'];
        $widget     = $parameters['5'];

        list($prefix,$mailerId) = explode('-',$mailerId);
        // check if mailer is valid.
        $mailerId = intval($mailerId);
        $mailerDetails = $this->getMailerDetailsById($mailerId);
        if(!is_array($mailerDetails)  ||  count($mailerDetails)<=0){
            return;
        }
 
        $trackerId = parseUrlFromContent($trackerId);
        $dbHandle  = $this->_loadDatabaseHandle('read','User');
        $query     = $dbHandle->query("SELECT userid FROM tuser WHERE email = ? ", array($emailId));
        $row       = $query->row_array();
        $userId    = (int) $row['userid'];
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle('write', 'Mailer');
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }

        if($userId <=0 && $mailerId >0){
            if($mailerDetails['listId'] ==0){
                return;
            }
        }

        if(trim($trackerId) != ''){
            $trackingType = 'click';
        } else {
            $trackingType = 'open';
        }

        $queryCmd = "insert into mailerMis (mailerid, userId, emailid, trackerid, widget, date, mailid, trackingType) values(?, ?, ?, ?, ?, NOW(), ?, ?) ";
        $query    = $dbHandle->query($queryCmd, array($mailerId,$userId,$emailId,$trackerId,$widget,$mailId,$trackingType));
        $msgArray = array($dbHandle->insert_id());
        $response = array($msgArray,'struct');
		/**
		 * Update userMailerSentCount table to reset triggers of product mailers when a product mail is opened
		 */
		$this->load->library('mailer/ProductMailerEventTriggers');
		$this->productmailereventtriggers->resetMailerTriggers($userId, 'mailOpened', $params=array('mailerId' => $mailerId));
        return $this->xmlrpc->send_response($response);
    }

    // used to send the csv mailer.
    function sendCsvMail($templateId, $loggedInUser, $emailId , $finalUserArr, $senderMail, $time,$testFlag, $mailerId = -1) {
        $dbConfig = array( 'hostname'=>'localhost');
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = 'select * from mailerTemplate where isActive="true" and id=?';
        $query = $dbHandle->query($queryCmd, array($templateId));

        foreach ($query->result() as $row){
            $subject = $row->subject;
            $htmlTemplate = $row->htmlTemplate;
        }

        $htmlTemplate = htmlspecialchars_decode($htmlTemplate);
        $subject = htmlspecialchars_decode($row->subject);
        $origHtmlTemplate = $htmlTemplate;
        $templateType = $row->templateType;
        if($templateType != "sms") {
            $origHtmlTemplate = $origHtmlTemplate."<div style='display:none'><img src='1<!-- #tracker --><!-- tracker# -->' /></div>";
        }
        $origHtmlSubject = $subject;

        $queryCmd = 'select * from templateVariable where templateId=?' ;
        $query = $dbHandle->query($queryCmd, array($templateId));
        $varArrayNotOther = array();
        $i = 0;
        $varArrayOnlyOther = array();
        $k = 0;
        foreach ($query->result() as $row){
            if($row->flagOther == "false") {
                $varArrayNotOther[$i]['varName'] = $row->varName;
                $varArrayNotOther[$i]['varValue'] = $row->varValue;
                $i++;
            }else {
                $varArrayOnlyOther[$k]['varName'] = $row->varName;
                $varArrayOnlyOther[$k]['varValue'] = $row->varValue;
                $k++;
            }
        }
        $varArrayNotOther[$i]['varName'] = "SendEmail";
        $varArrayNotOther[$i]['varValue'] = "SendEmail";
        $varArrayNotOther[$i+1]['varName'] = "PhoneNo";
        $varArrayNotOther[$i+1]['varValue'] = "PhoneNo";
        foreach($finalUserArr as $key=>$value) {
            $htmlTemplate = $origHtmlTemplate;
            $subject = $origHtmlSubject;
            foreach($finalUserArr[$key] as $key1=>$value1) {
                if(($key1 != "tracker")&&($key1 != "mailerId")&&($key1 != "templateId")) {
                    $htmlTemplate = $this->replace($key1, $value1, $htmlTemplate);
                    $subject = $this->replace($key1, $value1, $subject);
                }
            }
            $alertClient = new Alerts_client();

            if($testFlag == "true") {
                $htmlTemplate = $this->replace("mailerId", $mailerId, $htmlTemplate);
                $htmlTemplate = $this->replace("templateId", $templateId, $htmlTemplate);
                //$htmlTemplate = $this->replaceUrl("tracker", "mailer-".$mailerId."/".$emailId, $htmlTemplate, "https://".THIS_CLIENT_IP."/index.php/mailer/Mailer/blank");
                if($templateType == "sms") {
                    $response=$alertClient->addSmsQueueRecord("1", $finalUserArr[$key]["phone"], $htmlTemplate, $loggedInUser);
                }else{
                    $response=$this->_externalMassQueueAdd("1",$senderMail, $emailId, $subject,$htmlTemplate,"html",$time);
                }
            }else {
                $htmlTemplate = $this->replace("mailerId", $mailerId, $htmlTemplate);
                $htmlTemplate = $this->replace("templateId", $templateId, $htmlTemplate);
                $htmlTemplate = $this->replaceUrl("tracker", "mailer-".$mailerId."/".$finalUserArr[$key]["email"], $htmlTemplate, "https://".THIS_CLIENT_IP."/index.php/mailer/Mailer/blank");
                if($templateType == "sms") {
                    $response=$alertClient->addSmsQueueRecord("1", $finalUserArr[$key]["phone"], $htmlTemplate, $loggedInUser,$time);
                }else{
                    $response=$this->_externalMassQueueAdd("1",$senderMail, $finalUserArr[$key]["email"], $subject,$htmlTemplate,"html",$time);
                }
            }
        }
    }

    // used to send the normal mailer being called by runCronMailer
    function sendMail($templateId, $loggedInUser, $emailId , $userIds, $senderMail, $time,$testFlag, $mailerId = -1, $subject, $htmlTemplate,$templateType,$sendshikshadevmail=false) {
        $dbConfig = array( 'hostname'=>'localhost');
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        ////error_log("TYU ".$htmlTemplate);
        $htmlTemplate = htmlspecialchars_decode($htmlTemplate);
        $subject = htmlspecialchars_decode($subject);
        $origHtmlTemplate = $htmlTemplate;
        if($templateType != "sms") {
            $origHtmlTemplate = $origHtmlTemplate."<div style='display:none'><img src='1<!-- #tracker --><!-- tracker# -->' /></div>";
        }
        $origHtmlSubject = $subject;

        $queryCmd = 'select * from templateVariable where templateId=? order by id' ;
        $query = $dbHandle->query($queryCmd, array($templateId));
        $varArrayNotOther = array();
        $i = 0;
        $varArrayOnlyOther = array();
        $k = 0;
        $varArrayOther = array();
        foreach ($query->result() as $row){
            if($row->flagOther == "false") {
                $varArrayNotOther[$i]['varName'] = $row->varName;
                $varArrayNotOther[$i]['varValue'] = $row->varValue;
                $i++;
            }else {
                $varArrayOnlyOther[$k]['varName'] = $row->varName;
                $varArrayOnlyOther[$k]['varValue'] = $row->varValue;
                $k++;
                $varArrayOther[$row->varName] = $row->varValue;
            }
        }
        $varArrayNotOther[$i]['varName'] = "SendEmail";
        $varArrayNotOther[$i]['varValue'] = "SendEmail";
        $varArrayNotOther[$i+1]['varName'] = "PhoneNo";
        $varArrayNotOther[$i+1]['varValue'] = "PhoneNo";
        //error_log("HJKL ".print_r($varArrayNotOther,true));
        $flagDontSent = "false";
        $finalUserArr = array();
        for($l = 0; $l < count($varArrayNotOther) ; $l++) {
            if(isset($finalUserArr[$userId][$varArrayNotOther[$l]['varName']])) {
                continue;
            }
            //error_log("HJKL ".print_r($finalUserArr[$userId][$varArrayNotOther[$l]['varName']],true)." ".$varArrayNotOther[$l]['varName']);
            $queryCmd = 'select * from variableNames where varValue=? limit 1';
            $query = $dbHandle->query($queryCmd, array($varArrayNotOther[$l]['varValue']));
            foreach ($query->result() as $row){
                $presentQuery = $row->varClause;
            }
            $finalQuery = $this->replace("userIds", $userIds, $presentQuery);
            if($this->checkContainsVariable($finalQuery) == "true") {
                foreach($varArrayOther as $key=>$value) {
                    $finalQuery = $this->replace($key, $value, $finalQuery);
                }
            }

            $query = $dbHandle->query($finalQuery);
            $flagDontSent = "true";
            $tempArrii = array();
            foreach ($query->result() as $row){
                foreach ($row as $key=>$value){
                    if(($key == "userId")||($key == "userid") || ($key=="UserId")) {
                        $userId = $value;
                    }else {
                        $finalVal = $value;
                        for($kk=0;$kk<count($varArrayNotOther);$kk++) {
                            if($varArrayNotOther[$kk]['varValue'] ==  $key) {
                                $tempArrii[$varArrayNotOther[$kk]['varName']] = $finalVal;
                            } 
                        }
                    }
                }
                foreach ($tempArrii as $key=>$value) {
                    $finalUserArr[$userId][$key] = $value;
                    error_log("HJKL Setting $key as $value");
                }
                if(!isset($finalUserArr[$userId][$varArrayNotOther[$l]['varName']])) {
                    $finalUserArr[$userId][$varArrayNotOther[$l]['varName']] = $finalVal;
                }
                //error_log("HJKL1111111111111 Setting ".$varArrayNotOther[$l]['varName']." as ".$value);
                $flagDontSent = "false";
            }
            if($flagDontSent == "true") {
                return;
            }
        }
        if(count($finalUserArr) == 0) {
            $uidsArr = split(",",$userIds);
            for($l = 0; $l < count($uidsArr) ; $l++) {
               $finalUserArr[$uidsArr[$l]] = array();
            }
        }
        foreach($finalUserArr as $key=>$value) {
	    $count=0;
            $htmlTemplate = $origHtmlTemplate;
            $subject = $origHtmlSubject;
            foreach($varArrayOnlyOther as $key1=>$value1) {
                if(($key1 != "tracker")&&($key1 != "mailerId")&&($key1 != "templateId")) {
                    $finalUserArr[$key][$varArrayOnlyOther[$key1]['varName']] = $varArrayOnlyOther[$key1]['varValue'];
                }
            }
            foreach($finalUserArr[$key] as $key1=>$value1) {
                if(($key1 != "tracker")&&($key1 != "mailerId")&&($key1 != "templateId")) {
                    $htmlTemplate = $this->replace($key1, $value1, $htmlTemplate);
                    $subject = $this->replace($key1, $value1, $subject);
                }
            }
            if($testFlag == "true") {
                $htmlTemplate = $this->replace("mailerId", $mailerId, $htmlTemplate);
                $htmlTemplate = $this->replace("templateId", $templateId, $htmlTemplate);
                $htmlTemplate = $this->replaceUrl("tracker", "mailer-".$mailerId."/".$emailId, $htmlTemplate, "https://".THIS_CLIENT_IP."/index.php/mailer/Mailer/blank");
            }else {
                $htmlTemplate = $this->replace("mailerId", $mailerId, $htmlTemplate);
                $htmlTemplate = $this->replace("templateId", $templateId, $htmlTemplate);
                $htmlTemplate = $this->replaceUrl("tracker", "mailer-".$mailerId."/".$finalUserArr[$key]["SendEmail"], $htmlTemplate, "https://".THIS_CLIENT_IP."/index.php/mailer/Mailer/blank");
            }
            $alertClient = new Alerts_client();
            if($testFlag == "true") {
                if($templateType == "sms") {
                    $response=$alertClient->addSmsQueueRecord("1", $emailId, $htmlTemplate, $loggedInUser);
                }else{
                    $response=$this->_externalMassQueueAdd("1",$senderMail, $emailId, $subject,$htmlTemplate,"html",$time);
                }
            }else {
                if($templateType == "sms") {
                    $response=$alertClient->addSmsQueueRecord("1", $finalUserArr[$key]["PhoneNo"], $htmlTemplate, $loggedInUser,$time);
                }else{
                    $response=$this->_externalMassQueueAdd("1",$senderMail, $finalUserArr[$key]["SendEmail"], $subject,$htmlTemplate,"html",$time);
		    //sending a mail to few shiksha dev members if more than 100 mails are sent	
		    if($sendshikshadevmail)
		    {

			    $response=$this->_externalMassQueueAdd("1",$senderMail, "ankur.gupta@shiksha.com", $subject,$htmlTemplate,"html",$time);
			    $response=$this->_externalMassQueueAdd("1",$senderMail, "sachin.singhal@naukri.com", $subject,$htmlTemplate,"html",$time);
			    $response=$this->_externalMassQueueAdd("1",$senderMail, "vibhu.bhushan@naukri.com", $subject,$htmlTemplate,"html",$time);
		    }
                }
            }
            unset($finalUserArr[$key]);
	    $count++;
        }
    }


//get all the template variables to be used for mail merge facility.
    function getTemplateVariables($request) {
        //error_log("IN server vibhu");
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $templateId = $parameters['1'];

        $dbConfig = array( 'hostname'=>'localhost');
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }

        $queryCmd = 'select * from templateVariable where templateId=?' ;
        $query = $dbHandle->query($queryCmd, array($templateId));

        $msgArray = array();
        foreach ($query->result() as $row){
            if(($row->varName != "tracker")&&($row->varName != "widgettracker")&&($row->varName != "templateId")&&($row->varName != "mailerId")&&($row->varName != "mailId")&&($row->varName != "ip")&&($row->varName != "currentDate")) {
                array_push($msgArray,array(
                    array(
                        'id'=>array($row->id,'string'),
                        'templateId'=>array($row->templateId,'string'),
                        'varName'=>array($row->varName,'string'),
                        'varValue'=>array($row->varValue,'string'),
                        'flagOther'=>array($row->flagOther,'string'),
                    ),'struct'));//close array_push
            }
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);


    }


    // get all previously created list. To be shown to select the list of users which has already been created.

    function getAllLists($request) {
        //error_log("IN server vibhu");
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $userId = $parameters['1'];
        $userGroup = $parameters['2'];
        $clientId = $parameters['3'];
        $dbConfig = array( 'hostname'=>'localhost');
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }

        if($clientId > 0) {
            $queryCmd = 'select * from list  where isActive="true" and createdBy='.$dbHandle->escape($clientId).' and id=masterListId' ;
        }else {
            $queryCmd = 'select * from list  where isActive="true" and id=masterListId and createdOn > "2011-08-28"' ;
        }
        $query = $dbHandle->query($queryCmd);
        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                        array(
                            'id'=>array($row->id,'string'),
                            'name'=>array($row->name,'string'),
                            'description'=>array($row->description,'string'),
                            'createdOn'=>array($row->createdOn,'string'),
                            'numUsers'=>array($row->numUsers,'string'),
                            'isActive'=>array($row->isActive,'string')
                            ),'struct'));//close array_push
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);

    }



//    Gets All the sms templated created.

    function getAllSmsTemplates($request) {
        //error_log("IN server vibhu");
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];

        $dbConfig = array( 'hostname'=>'localhost');
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }

        //$queryCmd = 'select mailerTemplate.id, mailerTemplate.name, mailerTemplate.description, mailerTemplate.htmlTemplate, mailerTemplate.createdOn, mailerTemplate.updatedOn, mailerTemplate.subject, shiksha.tuser.displayname, mailerTemplate.isActive from mailerTemplate, shiksha.tuser  where isActive="true" and mailerTemplate.createdBy=shiksha.tuser.userid and mailerTemplate.templateType="sms"' ;
		
		$queryCmd = 'select mailerTemplate.id, mailerTemplate.name, mailerTemplate.description, mailerTemplate.htmlTemplate, mailerTemplate.createdOn, mailerTemplate.updatedOn, mailerTemplate.subject, mailerTemplate.isActive from mailerTemplate where isActive="true" and mailerTemplate.templateType="sms"' ;
		
        $query = $dbHandle->query($queryCmd);

        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                        array(
                            'id'=>array($row->id,'string'),
                            'name'=>array($row->name,'string'),
                            'description'=>array($row->description,'string'),
                            'subject'=>array($row->subject,'string'),
                            'htmlTemplate'=>array($row->htmlTemplate,'string'),
                            'createdOn'=>array($row->createdOn,'string'),
                            'updatedOn'=>array($row->updatedOn,'string'),
                            'createdBy'=>array($row->displayname,'string'),
                            'isActive'=>array($row->isActive,'string')
                            ),'struct'));//close array_push
        }
        $response = array($msgArray,'struct');
        //error_log("VIBHU ".print_r($productIdsArray,true));
        return $this->xmlrpc->send_response($response);

    }




// get all mail templates
    function getAllTemplates($request) {
        
       
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $userId = $parameters['1'];
        $userGroup = $parameters['2'];
        $temp = $parameters['3'];
        $groupId = $parameters['4'];
        $adminType = $parameters['5'];
        
        $dbConfig = array( 'hostname'=>'localhost');
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }

        
		$queryCmd = 'select mailerTemplate.id, mailerTemplate.name, mailerTemplate.description, mailerTemplate.htmlTemplate, mailerTemplate.createdOn, mailerTemplate.updatedOn, mailerTemplate.subject, mailerTemplate.isActive,mailerTemplate.spamscore,mailerTemplate.createdBy from mailerTemplate where isActive="true" and mailerTemplate.templateType="mail"' ;
		
        if($temp != "yes")
            $queryCmd = 'select mailerTemplate.id, mailerTemplate.name, mailerTemplate.description, mailerTemplate.createdOn, mailerTemplate.updatedOn, mailerTemplate.subject, mailerTemplate.isActive,mailerTemplate.spamscore,mailerTemplate.createdBy from mailerTemplate where isActive="true" and mailerTemplate.templateType="mail"' ;

        if($adminType == 'group_admin') {
            $queryCmd .= " and mailerTemplate.group_id = ".$dbHandle->escape($groupId);
        } else if ($adminType == 'normal_admin') {
            $queryCmd .= " and mailerTemplate.createdBy = ".$dbHandle->escape($userId);
        }

        $queryCmd .= " and parentTemplateId is NULL order by createdOn desc limit 500";
        
        
        $query = $dbHandle->query($queryCmd);
        
        $msgArray = array();
        foreach ($query->result() as $row){
            if($temp == "yes"){
                array_push($msgArray,
                        array(
                            'id'=>$row->id,
                            'name'=>$row->name,
                            'description'=>$row->description,
                            'subject'=>$row->subject,
                            'htmlTemplate'=>$row->htmlTemplate,
                            'createdOn'=>$row->createdOn,
                            'updatedOn'=>$row->updatedOn,
                            'createdBy'=>$row->displayname,
                            'isActive'=>$row->isActive,
							'spamScore'=>$row->spamscore,
                            'createdBy'=>$row->createdBy
                            ));//close array_push
            }
            else
                array_push($msgArray,
                        array(
                            'id'=>$row->id,
                            'name'=>$row->name,
                            'description'=>$row->description,
                            'subject'=>$row->subject,
                            'createdOn'=>$row->createdOn,
                            'updatedOn'=>$row->updatedOn,
                            'createdBy'=>$row->displayname,
                            'isActive'=>$row->isActive,
							'spamScore'=>$row->spamscore,
                            'createdBy'=>$row->createdBy
                            ));//close array_push
        }
        $response = utility_encodeXmlRpcResponse($msgArray);
        
        return $this->xmlrpc->send_response($response);

    }


    // get template variables.

    function getVariables($template) {
        //error_log( $template.". Template vibhu");
        //error_log(html_entity_decode($template)." vibhu");
        $template = html_entity_decode($template);
        preg_match_all('|<!-- #([a-zA-Z0-9]*) --><!-- ([a-zA-Z0-9]*)# -->|U',$template,$data,PREG_SET_ORDER);
        $returnArr = array();
        for($i = 0; $i < count($data); $i++) {
            if($data[$i][1] == $data[$i][2]) {
                array_push($returnArr,$data[$i][1]);
            }else {
            }
        }
        //error_log("Vibhu ".print_r($returnArr,true));
        return $returnArr;
    }



    function getListIds($mailerId, $listId) {
        if($listId > 0) {
            return $listId;
        }else {
            $this->mailerconfig->getDbConfig($appID,$dbConfig);
            $dbHandle  = $this->_loadDatabaseHandle();
            if($dbHandle == ''){
                log_message('error','can not create db handle');
            }
            //error_log("$templateId vibhu");
            $queryCmd = "select * from mailer_list where mailerId=? and mailsSent='false'";
            $query = $dbHandle->query($queryCmd, array($mailerId));
            $msgArray = array();
            foreach ($query->result() as $row){
                array_push($msgArray, $row->listId);
                $queryCmd = "update mailer_list set mailsSent='true' where mailerId=? and listId=?";
                $query123 = $dbHandle->query($queryCmd, array($mailerId, $row->listId));

            }
            $listIds = implode(",",$msgArray);
        }
        if($listIds != "")
            return $listIds;
        else
            return "-1";
    }
        


    //replaces the url used to convert link to redirection link so that we can track it in MIS.

    function replaceUrl($key, $value, $template, $url){
        $this->load->helper('utility');
        $pattern = '/\'([^"\']*#'.$key.'[^"\']*'.$key.'#[^"\']*)\'/';
        preg_match_all($pattern, $template, $matches);
        $matches = $matches[1];
        for($i=0;$i < count($matches); $i++) {
            $matches[$i] = str_replace("<!-- #".$key." --><!-- ".$key."# -->", '', $matches[$i]);
            $replacement = "'$url/".url_base64_encode($matches[$i])."/$value'";
            $template = preg_replace($pattern, $replacement, $template,1);
        }
        $pattern = '/"([^\'"]*#'.$key.'[^\'"]*'.$key.'#[^\'"]*)\"/i';
        preg_match_all($pattern, $template, $matches);
        $matches = $matches[1];
        for($i=0;$i < count($matches); $i++) {
            $matches[$i] = str_replace("<!-- #".$key." --><!-- ".$key."# -->", '', $matches[$i]);
            $replacement = "'$url/".url_base64_encode($matches[$i])."/$value'";
            $template = preg_replace($pattern, $replacement, $template,1);
        }
        return $template;
    }


//  simple replace for mail merge facility.

    function replace($key, $value, $template){
        $comment = "<!-- #".$key." --><!-- ".$key."# -->";
        return str_replace($comment,$value,$template);
    }
    function checkContainsVariable($template){
        preg_match_all('|<!-- #([a-zA-Z0-9]*) --><!-- ([a-zA-Z0-9]*)# -->|U',$template,$matches,PREG_SET_ORDER);
        if(count($matches) > 0) {
            return "true";
        }
        return "false";
    }



    // sets the template variables with the data that is needed to be filled. User specific data like name city etc.

    function setTemplateVariables($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $templateId = $parameters['1'];
        $keyValueArrJson = $parameters['2'];
        $keyValueArr = json_decode($keyValueArrJson);
        //error_log("vibhu ha calling setTemplateVariables ".print_r($keyValueArr,true));
        if(count($keyValueArr) == 0) {
            $this->mailerconfig->getDbConfig($appID,$dbConfig);
            $dbHandle  = $this->_loadDatabaseHandle();
            if($dbHandle == ''){
                log_message('error','can not create db handle');
            }
            $queryCmdTemp = "update mailerTemplate set isActive='true' where id=?";
            $query1 = $dbHandle->query($queryCmdTemp, array($templateId));
        }
        for($i = 0 ; $i<count($keyValueArr); $i++) {
            $this->insertVariable($templateId, $keyValueArr[$i][0], $keyValueArr[$i][1], $keyValueArr[$i][2], "false");
        }

        $response = array();
        return $this->xmlrpc->send_response($response);
    }


// updates the status of the list from inactive to active so that it can be shown in negation
    function updateListInfo($request){
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $listId = $parameters['1'];
        $name = $parameters['2'];
        $desc = $parameters['3'];
        $userId = $parameters['4'];
        $userGroup = $parameters['5'];

        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = "update list set name='".mysql_escape_string($name)."', description='".mysql_escape_string($desc)."', isActive='true' where masterListId=? and isActive='false'";
        $query = $dbHandle->query($queryCmd, array($listId));
        $response = $this->getListInfoFunc($appID, $listId, $userId, $userGroup,"false");
        ////error_log(print_r($response,true)."VIBHU HAAA");
        return $this->xmlrpc->send_response($response);
    }

// creation of template, insert or update.
    function insertOrUpdateTemplate($request){
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $templateId = $parameters['1'];
        $name = $parameters['2'];
        $desc = $parameters['3'];
        $subject = $parameters['4'];
        $html = gzuncompress(base64_decode($parameters['5']));
        $createdBy = $parameters['6'];
        $templateType = $parameters['7'];
        if($templateType == "") {
            $templateType = "mail";
        }
        $groupId = $parameters['8'];

        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        if($templateId == "") {
            $queryCmd = "insert into mailerTemplate values('".mysql_escape_string($templateId)."','".mysql_escape_string($name)."', '".mysql_escape_string($desc)."','".mysql_escape_string($subject)."', '".mysql_escape_string($html)."', NOW(), NOW(), ".mysql_escape_string($createdBy).", 'false', ".$dbHandle->escape($templateType).", '', ".$dbHandle->escape($groupId)." ,NULL)";
            $query = $dbHandle->query($queryCmd);
            $templateId = $dbHandle->insert_id();
            $variableArr = $this->getVariables($html);
            $variableArrSubject = $this->getVariables($subject);
            //error_log("HAAAAAAAAAA ".print_r($variableArr,true)." vibhu");
            for($i = 0 ; $i < count($variableArr); $i++) {
                //error_log("DSA templateId ".$variableArr[$i]);
                $this->insertVariable($templateId, $variableArr[$i], "", "true");
            }
            for($i = 0 ; $i < count($variableArrSubject); $i++) {
                //error_log("DSA templateId ".$variableArrSubject[$i]);
                $this->insertVariable($templateId, $variableArrSubject[$i], "","true");
            }
            //error_log("DSA templateId ".$templateId);
            $this->insertVariable($templateId, "templateId", $templateId,"true","false");
        } else {
            $queryCmd = "update mailerTemplate set htmlTemplate='".mysql_escape_string($html)."' ,updatedOn=NOW(),name='".mysql_escape_string($name)."', description='".mysql_escape_string($desc)."', subject='".mysql_escape_string($subject)."' where id=".$dbHandle->escape($templateId);
            $query = $dbHandle->query($queryCmd);
//            $queryCmd = "delete from templateVariable where templateId=$templateId";
  //          $query = $dbHandle->query($queryCmd);
            $variableArr = $this->getVariables($html);
            $variableArrSubject = $this->getVariables($subject);
            //error_log("HAAAAAAAAAA ".print_r($variableArr,true)." vibhu");
            for($i = 0 ; $i < count($variableArr); $i++) {
                $this->insertVariable($templateId, $variableArr[$i], "","true");
            }
            for($i = 0 ; $i < count($variableArrSubject); $i++) {
                $this->insertVariable($templateId, $variableArrSubject[$i], "","true");
            }
            $this->insertVariable($templateId, "templateId", $templateId,"true","false");

        }
        $msgArray = array();
//         foreach ($query->result() as $row){
            array_push($msgArray,array(
                        array(
                            'id'=>array($templateId,'string')
                            ),'struct'));//close array_push
//         }
        $response = array($msgArray,'struct');
        //error_log("VIBHU ".print_r($productIdsArray,true));
        return $this->xmlrpc->send_response($response);


    }

// inserts the variables found on to the table so that it can be accessed later.

    function insertVariable($templateId, $key , $value, $flag, $checkUpdateValue) {
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        //error_log("$templateId vibhu");
        //error_log("DSA ".$checkUpdateValue);
        if($checkUpdateValue == "false") {
            $queryCmd = "insert into templateVariable values('',".$dbHandle->escape($templateId).", '".mysql_escape_string($key)."', '".mysql_escape_string($value)."', '".mysql_escape_string($flag)."') on duplicate key update varValue='".mysql_escape_string($value)."' , flagOther='".mysql_escape_string($flag)."'";
            //error_log("DSA ".$queryCmd);
            $queryCmdTemp = "update mailerTemplate set isActive='true' where id=?";
            $query1 = $dbHandle->query($queryCmdTemp, array($templateId));
        }else{
            $queryCmd = "insert into templateVariable values('',".$dbHandle->escape($templateId).", '".mysql_escape_string($key)."', '".mysql_escape_string($value)."', '".mysql_escape_string($flag)."') on duplicate key update varName='".mysql_escape_string($key)."' ";
        }
        //error_log($queryCmd." vibhu");
        $query = $dbHandle->query($queryCmd);
    }


    // get  all the keys of the template. to be shown to the user so that user can fill it with specific data. 

    function getVariablesKey($appId) {
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        //error_log("$templateId vibhu");
        $queryCmd = "select * from variableNames where status = 'live'";
        $query = $dbHandle->query($queryCmd);
        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                        array(
                            'id'=>array($row->id,'string'),
                            'varValue'=>array($row->varValue,'string'),
                            'varClause'=>array($row->varClause,'string'),
                            'varTable'=>array($row->varTable,'string'),
                            'varField'=>array($row->varField,'string')
                            ),'struct'));//close array_push
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }










    //Server API to update CMS's Page-<Product> Table
    function supdateCmsItem($request)
    {
		$parameters = $request->output_parameters(FALSE,FALSE);
		$appID = $parameters['0'];
		////error_log("supdate_listing SERVER RECEIVED ARRAY ".print_r($parameters,true));

		$updateData = $parameters['1'];
		$updateKeyPage = $parameters['2'];
                ////error_log("parameter 1".print_r($parameters['1'],true));
                ////error_log("parameter 2".print_r($parameters['2'],true));

		$item_type = $updateData['item_type'];

                if($item_type == "msgboard")
                {
                    $categoryId = $updateData['categoryId'];
                    $topicId = $updateData['topicId'];
                }
                else
                {
		    $item_id = $updateData['item_id'];
                }

		$totalKeyPages = $updateData['totalKeyPages'];
/*
                $start_date = date('Y-m-d',$updateData['start_date']);
                $end_date = date('Y-m-d',$updateData['end_date']);
*/

                //connect DB
                $dbConfig = array( 'hostname'=>'localhost');
                $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
                $dbHandle  = $this->_loadDatabaseHandle();
                if($dbHandle == ''){
                        log_message('error','can not create db handle');
                }

		$query = '';

		switch($item_type)
                {
		case "blog":
		{
                    //Query Command for Deleting all existing rows for given EventID from Page-Blog (OR Article) Table
	            $deletionQuery= 'DELETE FROM '.$this->enterpriseconfig->pageBlogTable.' WHERE BlogId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($item_id));
                    //error_log("DELETION Query Execution Status==>".$deletionQueryStatus);

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$this->enterpriseconfig->pageBlogTable.'  (KeyId,BlogId,StartDate,EndDate) VALUES (?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            //error_log("INSERT QUERY Command at Server".$queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id, $item_id, $start_date, $end_date));
                            //error_log("INSERT Query Execution Status==>".$query);
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
		}
		case "msgboard":
		{
                    //Query Command for Deleting all existing rows for given EventID from Page-MessageBoard Table
	            $deletionQuery= 'DELETE FROM '.$this->enterpriseconfig->pageMsgBoardTable.' WHERE CategoryId = ? AND TopicId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($categoryId, $topicId));
                    //error_log("DELETION Query Execution Status==>".$deletionQueryStatus);

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$this->enterpriseconfig->pageMsgBoardTable.'  (KeyId,CategoryId,TopicId,StartDate,EndDate) VALUES (?,?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            //error_log("INSERT QUERY Command at Server".$queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id, $categoryId, $topicId, $start_date, $end_date));
                            //error_log("INSERT Query Execution Status==>".$query);
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
		}
		case "admission":
		{
                    //Query Command for Deleting all existing rows for given EventID from Page-Admission Table
	            $deletionQuery= 'DELETE FROM '.$this->enterpriseconfig->pageAdmissionTable.' WHERE AdmitId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($item_id));
                    //error_log("DELETION Query Execution Status==>".$deletionQueryStatus);

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$this->enterpriseconfig->pageAdmissionTable.'  (KeyId,AdmitId,StartDate,EndDate) VALUES (?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            //error_log("INSERT QUERY Command at Server".$queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id, $item_id, $start_date, $end_date));
                            //error_log("INSERT Query Execution Status==>".$query);
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
		}
		case "scholarship":
		{
                    //Query Command for Deleting all existing rows for given EventID from Page-Scholarship Table
	            $deletionQuery= 'DELETE FROM '.$this->enterpriseconfig->pageScholarshipTable.' WHERE ScholId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($item_id));
                    //error_log("DELETION Query Execution Status==>".$deletionQueryStatus);

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$this->enterpriseconfig->pageScholarshipTable.'  (KeyId,ScholId,StartDate,EndDate) VALUES (?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            //error_log("INSERT QUERY Command at Server".$queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id, $item_id, $start_date, $end_date));
                            //error_log("INSERT Query Execution Status==>".$query);
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
                }
                case "event":
                {
                    //Query Command for Deleting all existing rows for given EventID from Page-Event Table
                    $deletionQuery= 'DELETE FROM '.$this->enterpriseconfig->pageEventsTable.' WHERE EventId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($item_id));
                    //error_log("DELETION Query Execution Status==>".$deletionQueryStatus);

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$this->enterpriseconfig->pageEventsTable.'  (KeyId,EventId,StartDate,EndDate) VALUES (?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            //error_log("INSERT QUERY Command at Server".$queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id, $item_id, $start_date, $end_date));
                            //error_log("INSERT Query Execution Status==>".$query);
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
                }
		case "course":
		{
                    //Query Command for Deleting all existing rows for given CourseId from Page-Course Table
	            $deletionQuery= 'DELETE FROM '.$this->enterpriseconfig->pageCourseColTable.' WHERE CourseId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($item_id));
                    //error_log("DELETION Query Execution Status==>".$deletionQueryStatus);

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$this->enterpriseconfig->pageCourseColTable.'  (KeyId,CourseId,StartDate,EndDate) VALUES (?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            //error_log("INSERT QUERY Command at Server".$queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id, $item_id, $start_date, $end_date));
                            //error_log("INSERT Query Execution Status==>".$query);
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
		}
		case "institute":
		{
                    //Query Command for Deleting all existing rows for given CollegeId from Page-College Table
	            $deletionQuery= 'DELETE FROM '.$this->enterpriseconfig->pageCollegeTable.' WHERE CollegeId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($item_id));
                    //error_log("DELETION Query Execution Status==>".$deletionQueryStatus);

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$this->enterpriseconfig->pageCollegeTable.'  (KeyId,CollegeId,StartDate,EndDate) VALUES (?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            //error_log("INSERT QUERY Command at Server".$queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id, $item_id, $start_date, $end_date));
                            //error_log("INSERT Query Execution Status==>".$query);
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
		}
		case "network":
		{
                    //Query Command for Deleting all existing rows for given CollegeId from Page-Network Table
	            $deletionQuery= 'DELETE FROM '.$this->enterpriseconfig->pageNetworkTable.' WHERE CollegeId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($item_id));
                    //error_log("DELETION Query Execution Status==>".$deletionQueryStatus);

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$this->enterpriseconfig->pageNetworkTable.'  (KeyId,CollegeId,StartDate,EndDate) VALUES (?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            //error_log("INSERT QUERY Command at Server".$queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id, $item_id, $start_date, $end_date));
                            //error_log("INSERT Query Execution Status==>".$query);
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
		}
                default:
                        $response = array(array('QueryStatus'=>$query,'int'),array('Wrong_Product_Type'=>$item_type,'string'),'struct');
                        //error_log("Response Array==> ".print_r($response,TRUE));
                        return $this->xmlrpc->send_response($response);
                }

		$response = array(array('QueryStatus'=>$query,'int'),'struct');
		//error_log("FINAL Response Array==> ".print_r($response,TRUE));
		return $this->xmlrpc->send_response($response);
    }


	//Server API to get <Product>IDs and other details for some Key_ID from Page<Product>Db
    function sgetItems($request)
    {
		$parameters = $request->output_parameters(FALSE,FALSE);
//		//error_log("supdate_listing SERVER RECEIVED ARRAY ".print_r($parameters,true));
		$appID = $parameters['0'];

		$updateData = $parameters['1'];
		$key_id = $updateData['key_id'];
		$item_type = $updateData['item_type'];

		//connect DB
                $dbConfig = array( 'hostname'=>'localhost');
                $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
                $dbHandle  = $this->_loadDatabaseHandle();
                if($dbHandle == ''){
                        log_message('error','can not create db handle');
                }

		switch($item_type) {
		case "blog":
		{

			$queryCmd = 'SELECT * FROM '.$this->enterpriseconfig->pageBlogTable.' WHERE KeyId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			//error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($key_id));

			$productIdsArray = array();
			foreach ($query->result() as $row){
				array_push($productIdsArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'BlogId'=>array($row->BlogId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		case "msgboard":
		{

			$queryCmd = 'SELECT * FROM '.$this->enterpriseconfig->pageMsgBoardTable.' WHERE KeyId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			//error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($key_id));

			$productIdsArray = array();
			foreach ($query->result() as $row){
				array_push($productIdsArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'MsgBoardId'=>array($row->MsgBoardId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		case "event":
		{

			$queryCmd = 'SELECT * FROM '.$this->enterpriseconfig->pageEventsTable.' WHERE KeyId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			//error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($key_id));

			$productIdsArray = array();
			foreach ($query->result() as $row){
				array_push($productIdsArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'EventId'=>array($row->EventId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		default:
			$response = array(array('Wrong_Product_Type'=>$item_type,'string'),'struct');
			//error_log("Response Array==> ".print_r($response,TRUE));
			return $this->xmlrpc->send_response($response);

		}

	        $response = array($productIdsArray,'struct');
       		//error_log(print_r($productIdsArray,true));
	        return $this->xmlrpc->send_response($response);
	}


    //Server API to get Key-Pages for a item_id [and topicId in case of msgboard]
    function sgetKeyPages($request)
    {
		$parameters = $request->output_parameters(FALSE,FALSE);
		////error_log("SHASHWAT SERVER RECEIVED ARRAY ".print_r($parameters,true));
		$appID = $parameters['0'];

		$updateData = $parameters['1'];
		$item_type = $updateData['item_type'];

                if($item_type == "msgboard")
                {
                    $categoryId = $updateData['categoryId'];
                    $topicId = $updateData['topicId'];
                }
                else
                {
		    $item_id = $updateData['item_id'];
                }

		//connect DB
                $dbConfig = array( 'hostname'=>'localhost');
                $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
                $dbHandle  = $this->_loadDatabaseHandle();
                if($dbHandle == ''){
                        log_message('error','can not create db handle');
                }

		switch($item_type) {
		case "blog":
		{
			$queryCmd = 'SELECT * FROM '.$this->enterpriseconfig->pageBlogTable.' WHERE BlogId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			//error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;

		}
		case "msgboard":
		{
			$queryCmd = 'SELECT * FROM '.$this->enterpriseconfig->pageMsgBoardTable.' WHERE CategoryId = ? AND TopicId = ? ';
			log_message('debug', 'query cmd is ' . $queryCmd);
			//error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($categoryId, $topicId));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;

		}
		case "admission":
		{
			$queryCmd = 'SELECT * FROM '.$this->enterpriseconfig->pageAdmissionTable.' WHERE AdmitId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			//error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		case "scholarship":
		{
			$queryCmd = 'SELECT * FROM '.$this->enterpriseconfig->pageScholarshipTable.' WHERE ScholId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			//error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		case "event":
		{
			$queryCmd = 'SELECT * FROM '.$this->enterpriseconfig->pageEventsTable.' WHERE EventId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			//error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;

		}
		case "course":
		{
			$queryCmd = 'SELECT * FROM '.$this->enterpriseconfig->pageCourseColTable.' WHERE CourseId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			//error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		case "institute":
		{
			$queryCmd = 'SELECT * FROM '.$this->enterpriseconfig->pageCollegeTable.' WHERE CollegeId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			//error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		case "network":
		{
			$queryCmd = 'SELECT * FROM '.$this->enterpriseconfig->pageNetworkTable.' WHERE CollegeId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			//error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		default:
			$response = array(array('Wrong_Product_Type'=>$item_type,'string'),'struct');
			//error_log("Response Array==> ".print_r($response,TRUE));
			return $this->xmlrpc->send_response($response);
		}
	        $response = array($keyPageArray,'struct');
       		//error_log(print_r($keyPageArray,true));
	        return $this->xmlrpc->send_response($response);
	}


    function makeApcCityMap(){
        $dbConfig = array( 'hostname'=>'localhost');
        $appID = 12;
        $this->listingconfig->getDbConfig_test($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','getCountryTable can not create db handle');
        }

        $this->load->library('cacheLib');
        $cacheLibObj = new cacheLib();

        $queryCmd = 'select * from countryCityTable';
        log_message('debug', 'query cmd is ' . $queryCmd);
        $query = $dbHandle->query($queryCmd);
        $counter = 0;
        $msgArray = array();
        foreach ($query->result() as $row){
            $key = "city_".$row->city_id;
            $val = $row->city_name;
            $cacheLibObj->store($key,$val);
        }
    }


    function makeApcCountryMap(){
        $dbConfig = array( 'hostname'=>'localhost');

        $appID = 12;
        $this->listingconfig->getDbConfig_test($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','getCountryTable can not create db handle');
        }
        $queryCmd = 'select * from countryTable';
        log_message('debug', 'query cmd is ' . $queryCmd);
        $query = $dbHandle->query($queryCmd);
        $counter = 0;
        
        $this->load->library('cacheLib');
        $cacheLibObj = new cacheLib();

        $msgArray = array();
        foreach ($query->result() as $row){
            $key = "country_".$row->countryId;
            $val = $row->name;
            $cacheLibObj->store($key,$val);
        }
     }


     function supdateAssignNewInstitute($request)
    {
		$parameters = $request->output_parameters(FALSE,FALSE);
                //error_log("SHAS supdateAssignNewInstitute SERVER RECEIVED ARRAY ".print_r($parameters,true));
		$appID = $parameters['0'];
	        $courseId = $parameters['1'];
                $newInstId = $parameters['2'];

		//connect DB
        $dbConfig = array( 'hostname'=>'localhost');
        $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
                log_message('error','can not create db handle');
        }

		//$queryCmd = ' SELECT * FROM '.$this->enterpriseconfig->keyPageTable.' ';
                $queryCmd = ' UPDATE course_details SET institute_id = ? WHERE course_id =? ';
		log_message('debug', 'query cmd is ' . $queryCmd);
                //error_log($queryCmd);
		$query = $dbHandle->query($queryCmd, array($newInstId, $courseId));
                $instiResponse = array(array('QueryStatus'=>$query,'int'),'struct');
                //error_log("SHAS Update course_details Response Array==> ".print_r($instiResponse,TRUE));

		//$queryCmd = ' SELECT * FROM '.$this->enterpriseconfig->keyPageTable.' ';
                $queryCmd = ' UPDATE institute_courses_mapping_table SET institute_id = ? WHERE course_id =? ';
		log_message('debug', 'query cmd is ' . $queryCmd);
                //error_log($queryCmd);
		$query = $dbHandle->query($queryCmd, array($newInstId, $courseId));
                $courseMapResponse = array(array('QueryStatus'=>$query,'int'),'struct');
                //error_log("SHAS Update institute_courses_mapping_table Response Array==> ".print_r($courseMapResponse,TRUE));
/*
		//$queryCmd = ' SELECT * FROM '.$this->enterpriseconfig->keyPageTable.' ';
                $queryCmd = ' UPDATE listings_main SET listing_type_id = '.$newInstId.' WHERE listing_type_id ='.$courseId.' ';
		log_message('debug', 'query cmd is ' . $queryCmd);
                //error_log($queryCmd);
		$query = $dbHandle->query($queryCmd);
                $listingMainResponse = array(array('QueryStatus'=>$query,'int'),'struct');
                //error_log("SHAS Update listings_main Response Array==> ".print_r($listingMainResponse,TRUE));
*/
                //return $this->xmlrpc->send_response($response);
                return $this->xmlrpc->send_response($courseMapResponse);
	}



    function supdateOldInstitute($request)
    {
       $parameters = $request->output_parameters(FALSE,FALSE);
       ////error_log("SHAS supdateOldInstitute SERVER RECEIVED ARRAY ".print_r($parameters,true));
       $appID = $parameters['0'];
       ////error_log("SHAS supdateOldInstitute appID ".$appID);
       $formVal = $parameters['1'];
       //error_log("INSTITUTE EDIT Form Data ".print_r($formVal,TRUE));

       //connect DB
       $dbConfig = array( 'hostname'=>'localhost');
       $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
       $dbHandle  = $this->_loadDatabaseHandle();
       if($dbHandle == ''){
          log_message('error','can not create db handle');
       }

       if ($formVal['dataFromCMS']=="dataFromCMS") {
          $moderated = "moderated";
       } else {
          $moderated = "unmoderated";
       }

             $old_institute_id = $formVal['old_institute_id'];

             $data =array();
             $data = array(
                'institute_name'=>$formVal['institute_name'],
                'short_desc'=>$formVal['institute_desc'],
                'establish_year'=>$formVal['establish_year'],
                'no_of_students'=>$formVal['no_of_students'],
                'no_of_international_students'=>$formVal['no_of_int_students'],
                'long_description'=>$formVal['institute_desc'],
                'certification'=>$formVal['affiliated_to']
             );

             $where = "institute_id = $old_institute_id";

             $queryCmd = $dbHandle->update_string('institute',$data,$where);

             log_message('debug', 'query cmd is ' . $queryCmd);
             //error_log("Updating institute Table ".$queryCmd);
             $query = $dbHandle->query($queryCmd);

             $listingIdQuery = 'SELECT listing_id FROM listings_main WHERE listing_type_id=? AND listing_type="institute"';
             log_message('debug', 'query cmd is ' . $listingIdQuery);
             //error_log($listingIdQuery);
             $query = $dbHandle->query($listingIdQuery, array($old_institute_id));
             $changeListingId=0;
        foreach ($query->result() as $row){
           $changeListingId = $row->listing_id;
        }

        $data =array();
        $format = 'DATE_ATOM';
             $data = array(
                'listing_type_id'=>$old_institute_id,
                'listing_title'=>$formVal['institute_name'],
                'short_desc'=>$formVal['institute_desc'],
                'listing_type'=>'institute',
                'contact_email'=>$formVal['contact_email'],
                'contact_name'=>$formVal['contact_name'],
                'contact_cell'=>$formVal['contact_cell'],
                'last_modify_date'=>standard_date($format,time()),
                'moderation_flag' => $moderated,
                'url' => $formVal['url']
             );

             $where = "listing_id = $changeListingId";

             $queryCmd = $dbHandle->update_string('listings_main',$data,$where);
             //error_log($queryCmd);
             $query = $dbHandle->query($queryCmd);

             $response = array
             (array
             ('QueryStatus'=>$query,
             'institute_id'=>$old_institute_id,
             'listing_id'=>$changeListingId,
             'type_id'=>$old_institute_id,
             'listing_type'=>"institute",
          ),
          'struct'
       );

                 /*
		//update User point system
		$queryCmd = 'update userPointSystem set userPointValue=userPointValue+20 where userId='.$formVal['username'];

		log_message('debug', 'update_Blog_UserPointSystem query cmd is ' . $queryCmd);

		if(!$dbHandle->query($queryCmd)){
			log_message('error', 'update_Blog_UserPointSystem query cmd failed' . $queryCmd);
                     }
                     */


             //Query Command for Deletion then Insertion in the institute_location_table

             $deleteQueryCmd = "DELETE FROM institute_location_table WHERE institute_id= ?";
                    log_message('debug', 'query cmd is ' . $deleteQueryCmd);
                    //error_log($deleteQueryCmd);
                    $query = $dbHandle->query($deleteQueryCmd, array($old_institute_id));


           for($i = 0; $i < $formVal['numoflocations']; $i++){
                //Query Command for Insert in the course category Table
			 $data =array();
			 $data = array(
						  'institute_id'=>$old_institute_id,
						  'city_id'=>$formVal['city_id'.$i],
						  'country_id'=>$formVal['country_id'.$i],
						  'address'=>$formVal['address'.$i]
							  );
			 $queryCmd = $dbHandle->insert_string('institute_location_table',$data);

                log_message('debug', 'query cmd is ' . $queryCmd);
                //error_log($queryCmd);
                $query = $dbHandle->query($queryCmd);
            }

            /* Not for CMS Edit */
            /*
$testEmailIds = explode(',',$formVal['testimonial_emailids']);
            for($i = 0; $i < count($testEmailIds); $i++){
               //Query Command for Insert in the course category Table
               $queryCmd = 'INSERT into institute_testimonial_table (institute_id,email_id) values ('.$institute_id.',"'.$testEmailIds[$i].'")';
               log_message('debug', 'query cmd is ' . $queryCmd);
               //error_log($queryCmd);
               $query = $dbHandle->query($queryCmd);
            }
            */


            /* Not for CMS Edit */
            /*
//Alert Mobile integration
//FIXME:No need for this query, but HACKING for time being
	$queryCmd = 'select country_id from institute_location_table where institute_id = '.$institute_id.' group by country_id';
	log_message('debug', 'query cmd is ' . $queryCmd);
	//error_log($queryCmd);
	$query = $dbHandle->query($queryCmd);
	$countryArr = array();
	$i =0;
	foreach ($query->result() as $row){
		$countryArr[$i] = $row->country_id;
		$i++;
	}

	$alertStatus =  $this->alertMobileFeeder('institute',$institute_id,$formVal['institute_name'],$catArr,$countryArr);
        //        $response = array(array('QueryStatus'=>$query,'int'),'struct');
        */

        //error_log(print_r($response,true));
        return $this->xmlrpc->send_response($response);

	}


     function sremoveInstiLogoCMS($request)
    {
		$parameters = $request->output_parameters(FALSE,FALSE);
                //error_log("SHAS sremoveInstiLogoCMS SERVER RECEIVED ARRAY ".print_r($parameters,true));
		$appID = $parameters['0'];
	        $instituteId = $parameters['1'];

		//connect DB
        $dbConfig = array( 'hostname'=>'localhost');
        $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
                log_message('error','can not create db handle');
        }
           $queryCmd = ' UPDATE institute SET logo_link=NULL WHERE institute_id=? ';
		log_message('debug', 'query cmd is ' . $queryCmd);
                //error_log($queryCmd);
		$query = $dbHandle->query($queryCmd, array($instituteId));
                $instiResponse = array(array('QueryStatus'=>$query,'int'),'struct');
                //error_log("SHAS institute remove Logo Response Array==> ".print_r($instiResponse,TRUE));

                //return $this->xmlrpc->send_response($response);
                return $this->xmlrpc->send_response($instiResponse);
	}

     function sremoveFeaturedPanelLogo($request)
    {
		$parameters = $request->output_parameters(FALSE,FALSE);
                //error_log("SHAS sremoveFeaturedPanelLogo SERVER RECEIVED ARRAY ".print_r($parameters,true));
		$appID = $parameters['0'];
	        $instituteId = $parameters['1'];

		//connect DB
        $dbConfig = array( 'hostname'=>'localhost');
        $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
                log_message('error','can not create db handle');
        }
           $queryCmd = ' UPDATE institute SET featured_panel_link=NULL WHERE institute_id=? ';
		log_message('debug', 'query cmd is ' . $queryCmd);
                //error_log($queryCmd);
		$query = $dbHandle->query($queryCmd, array($instituteId));
                $instiResponse = array(array('QueryStatus'=>$query,'int'),'struct');
                //error_log("SHAS institute remove Insti Panel Response Array==> ".print_r($instiResponse,TRUE));

                //return $this->xmlrpc->send_response($response);
                return $this->xmlrpc->send_response($instiResponse);
	}


        function sremoveCourseMediaCMS($request){
		$parameters = $request->output_parameters(FALSE,FALSE);
                //error_log("SHAS sremoveCourseInstiMediaCMS SERVER RECEIVED ARRAY ".print_r($parameters,true));
		$appID = $parameters['0'];
                $removeData = $parameters['1'];

                $userid = $removeData['userid'];
                $usergroup = $removeData['usergroup'];
                $courseId = $removeData['courseId'];
                $mediaType = $removeData['mediaType'];
                $courseMediaId = $removeData['courseMediaId'];
                $listingType = $removeData['listingType'];


		//connect DB
        $dbConfig = array( 'hostname'=>'localhost');
        $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
                log_message('error','can not create db handle');
        }

        if($listingType == 'course'){
        switch($mediaType){
           case 'photo':
                $queryCmd = 'DELETE FROM course_photos_table WHERE course_id=? AND photo_id=? ';
                break;
           case 'video':
                $queryCmd = 'DELETE FROM course_videos_table WHERE course_id=? AND video_id=? ';
                break;
           case 'document':
                $queryCmd = 'DELETE FROM course_doc_table WHERE course_id=? AND doc_id=? ';
                break;
            }
        }

        if($listingType == 'institute'){
        switch($mediaType){
           case 'photo':
                $queryCmd = 'DELETE FROM institute_photos_table WHERE institute_id=? AND photo_id=? ';
                break;
           case 'video':
                $queryCmd = 'DELETE FROM institute_videos_table WHERE institute_id=? AND video_id=? ';
                break;
           case 'document':
                $queryCmd = 'DELETE FROM institute_doc_table WHERE institute_id=? AND doc_id=? ';
                break;
            }
        }
		log_message('debug', 'query cmd is ' . $queryCmd);
                //error_log($queryCmd);
		$query = $dbHandle->query($queryCmd, array($courseId, $courseMediaId));
                $instiResponse = array(array('QueryStatus'=>$query,'int'),'struct');
                //error_log("SHAS institute remove Media content Response Array==> ".print_r($instiResponse,TRUE));

                //return $this->xmlrpc->send_response($response);
                return $this->xmlrpc->send_response($instiResponse);
	}


        function sEditUpdateCourse($request)
        {
            $parameters = $request->output_parameters(FALSE,FALSE);
            //error_log("sEditUpdateCourse SERVER RECEIVED ARRAY ".print_r($parameters,true));
            $appID = $parameters['0'];
            $formVal = $parameters['1'];
	    $eligibility = $parameters['2'];
	    $tests = $parameters['3'];

            ////error_log("FORMval ".print_r($formVal,TRUE));

       //connect DB
       $dbConfig = array( 'hostname'=>'localhost');
       $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
       $dbHandle  = $this->_loadDatabaseHandle();
       if($dbHandle == ''){
          log_message('error','can not create db handle');
      }

      if ($formVal['dataFromCMS']=="dataFromCMS") {
          $moderated = "moderated";
      } else {
          $moderated = "unmoderated";
      }

      if (!isset($formVal['sourceUrl']) || (strlen($formVal['sourceUrl'])<=5)) {
          $formVal['sourceUrl'] = $formVal['url'];
      }
      $old_institute_id = $formVal['old_institute_id'];

/*        $queryCmd = "select course_id, courseTitle from course_details where institute_id = '".$formVal['institute_id']."' and courseTitle ='".mysql_escape_string($formVal['courseTitle'])."'";
        //error_log("Duplicate Course Name chk at Server-Side : ".$queryCmd);
        $queryCheckDup = $dbHandle->query($queryCmd);
        if($queryCheckDup->num_rows() <= 0){ */
      //Intermediate Course Duration
      $tempDuration = preg_replace('/[^A-Za-z0-9\-\/\.]/', ' ', $formVal['duration']);
      $intermediateDuration = exec("./duration.sh '".$tempDuration."'");
      if(strlen($intermediateDuration) <= 0){
          $intermediateDuration = $formVal['duration'];
      }


            //Query Command for Insert in the course details Table
			 $data =array();
			 $data = array(
						  'courseTitle'=>$formVal['courseTitle'],
						  'overview'=>$formVal['overview'],
                                                  'duration'=>$formVal['duration'],
                                                  'intermediateDuration'=>$intermediateDuration,
						  'objective'=>$formVal['objective'],
						  'contents'=>$formVal['contents'],
						  'eligibility'=>$formVal['eligibility'],
						  'selection_criteria'=>$formVal['selection_criteria'],
						  'scholarshipText'=>$formVal['scholarships'],
						  'placements'=>$formVal['placements'],
                                                  'hostel_facility'=>$formVal['hostel_facility'],
                                                  'fees'=>$formVal['fees'],
                                                  'course_type'=>$formVal['courseType'],
                                                  'course_level'=>$formVal['courseLevel'],
                                                  'course_strength'=>" ",
						  'tests_required'=>$formVal['tests_required'],
						  'start_date'=>$formVal['startDate'],
						  'end_date'=>$formVal['endDate'],
						  'emails_for_testimonials'=>$formVal['invite_emails']
                                               );

                                               $where = 'course_id = '.$formVal["update_course_id"].'';

                                               $queryCmd = $dbHandle->update_string('course_details',$data,$where);



            log_message('debug', 'query cmd is ' . $queryCmd);
            //error_log($queryCmd);
            $query = $dbHandle->query($queryCmd);
            //			//error_log("Query Execution Status ".$query);

            $course_id = $formVal['update_course_id'];

            $listingIdQuery = 'SELECT listing_id FROM listings_main WHERE listing_type_id= AND listing_type="course"';
             log_message('debug', 'query cmd is ' . $listingIdQuery);
             //error_log($listingIdQuery);
             $query = $dbHandle->query($listingIdQuery, array($course_id));
             $changeListingId=0;
        foreach ($query->result() as $row){
           $changeListingId = $row->listing_id;
        }


            //Query Command for Insert in the Listing Main Table
            $data =array();
            $format = 'DATE_ATOM';
            $data = array(
                    'listing_type_id'=>$course_id,
                    'listing_title'=>$formVal['courseTitle'],
                    'short_desc'=>$formVal['overview'],
                    'listing_type'=>'course',
                    'last_modify_date'=>standard_date($format,time()),
                    'moderation_flag' => $moderated,
                    'sourceURL' => $formVal['sourceUrl']
                    );
             $where = "listing_id = $changeListingId";

             $queryCmd = $dbHandle->update_string('listings_main',$data,$where);
             //error_log($queryCmd);
             $query = $dbHandle->query($queryCmd);


            $response = array(array
                    ('QueryStatus'=>$query,
                     'Course_id'=>$course_id,
                     'Listing_id'=>$changeListingId,
                     'type_id'=>$course_id,
                     'listing_type'=>"course",
                    ),
                    'struct'
                 );

                 	// Amit Singhal : Not needed as institute_category_table is unused now(Recat Sprint-2).
                    //$deleteQueryCmd = "DELETE FROM course_category_table WHERE course_id= $course_id";
                    //log_message('debug', 'query cmd is ' . $deleteQueryCmd);
                    ////error_log($deleteQueryCmd);
                    //$query = $dbHandle->query($deleteQueryCmd);
                    //
                    //
                    //$catArr = array();
                    //if(isset($formVal['category_id']) && $formVal['category_id'] != ""){
                    //    $categories = $formVal['category_id'];
                    //    $catArr = explode(',',$categories);
                    //    $numOfCats = count($catArr);
                    //    for($i = 0; $i < $numOfCats ; $i++){
                    //        //Query Command for Insert in the course category Table
                    //        $queryCmd = "INSERT into course_category_table (course_id,category_id) values ($course_id,$catArr[$i]) on duplicate key update category_id = ".$catArr[$i];
                    //        log_message('debug', 'query cmd is ' . $queryCmd);
                    //        //error_log($queryCmd);
                    //        $query = $dbHandle->query($queryCmd);
                    //    }
                    //}
                    //
                    //$this->load->model('ListingModel','',$dbConfig);
                    //$this->ListingModel->sanitizeInstituteCategories($dbHandle,'course',$course_id);

             //Query Command for Deletion in the course_eligibility_table

             $deleteQueryCmd = "DELETE FROM course_eligibility_table WHERE course_id=?";
                    log_message('debug', 'query cmd is ' . $deleteQueryCmd);
                    //error_log($deleteQueryCmd);
                    $query = $dbHandle->query($deleteQueryCmd, array($course_id));

//error_log(print_r($eligibility,true));

            if(isset($eligibility) && count($eligibility) > 0){
                foreach($eligibility as $key=>$val){
	$data =array();
	$data = array(
			'course_id'=>$course_id,
			'eligibility_criteria'=>$key,
			'eligibility_criteria_values'=>$val
		     );
	$queryCmd = $dbHandle->insert_string('course_eligibility_table',$data);

$queryCmd .= "on duplicate key update eligibility_criteria_values =  ".$dbHandle->escape($val);

                    //error_log($queryCmd);
                    $query = $dbHandle->query($queryCmd);
                }
            }


            //Query Command for Deletion of REQUIRED EXAMS
            $deleteQueryCmd = "DELETE FROM listingExamMap WHERE type='course' and typeId=? and typeOfMap='required' ";
            log_message('debug', 'query cmd is ' . $deleteQueryCmd);
            //error_log($deleteQueryCmd);
            $query = $dbHandle->query($deleteQueryCmd, array($course_id));

            $deleteQueryCmdOther = "DELETE FROM othersExamTable WHERE listingType='course' and listingTypeId=? and typeOfMap='required' ";
            log_message('debug', 'query cmd is ' . $deleteQueryCmdOther);
            //error_log($deleteQueryCmdOther);
            $query = $dbHandle->query($deleteQueryCmdOther, array($course_id));


            $this->load->model('ExamModel','',$dbConfig);
            //error_log("NEWEXAM::".print_r($formVal,true));
            if(isset($formVal['tests_required']) && strlen($formVal['tests_required']) > 0 )
            {
                $examsArr = explode(",",$formVal['tests_required']);
                $this->ExamModel->makeEntityExamsMapping($course_id, $examsArr,'course','required');
            }
            if($formVal['tests_required_other'] == 'true'){
                $examData =array();
                $examData['listingType'] = 'course';
                $examData['listingTypeId'] = $course_id;
                $examData['typeOfMap'] = 'required';
                $examData['exam_name'] = $formVal['tests_required_exam_name'];
                $examData['exam_desc'] = $formVal['tests_required_exam_desc'];
                $examData['numOfCentres'] = 0;
                $newExamId = $this->ExamModel->insertOtherExam($examData);
            }

            //Query Command for Deletion of TESTPREP EXAMS
            $deleteQueryCmd = "DELETE FROM listingExamMap WHERE type='course' and typeId=? and typeOfMap='testprep' ";
            log_message('debug', 'query cmd is ' . $deleteQueryCmd);
            //error_log($deleteQueryCmd);
            $query = $dbHandle->query($deleteQueryCmd, array($course_id));

            $deleteQueryCmdOther = "DELETE FROM othersExamTable WHERE listingType='course' and listingTypeId=? and typeOfMap='testprep' ";
            log_message('debug', 'query cmd is ' . $deleteQueryCmdOther);
            //error_log($deleteQueryCmdOther);
            $query = $dbHandle->query($deleteQueryCmdOther, array($course_id));


            if(isset($formVal['tests_preparation']) && strlen($formVal['tests_preparation']) > 0 )
            {
                $examsArr = explode(",",$formVal['tests_preparation']);
                $this->ExamModel->makeEntityExamsMapping($course_id, $examsArr,'course','testprep');
            }
            if($formVal['tests_preparation_other'] == 'true'){
                $examData =array();
                $examData['listingType'] = 'course';
                $examData['listingTypeId'] = $course_id;
                $examData['typeOfMap'] = 'testprep';
                $examData['exam_name'] = $formVal['tests_preparation_exam_name'];
                $examData['exam_desc'] = $formVal['tests_preparation_exam_desc'];
                $examData['numOfCentres'] = 0;
                //error_log("NEWEXAM::".print_r($examData,'true'));
                $newExamId = $this->ExamModel->insertOtherExam($examData);
            }


            /*
            $deleteQueryCmd = "DELETE FROM course_tests_required_table WHERE course_id= $course_id";
            log_message('debug', 'query cmd is ' . $deleteQueryCmd);
            //error_log($deleteQueryCmd);
            $query = $dbHandle->query($deleteQueryCmd);

            if(isset($tests) && count($tests) > 0)
            {
                foreach($tests as $key=>$val)
                {
                    $data =array();
                    $data = array(
                        'course_id'=>$course_id,
                        'test'=>$key,
                        'value'=>$val
                    );
                    $queryCmd = $dbHandle->insert_string('course_tests_required_table',$data);
                    $queryCmd .= "on duplicate key update value =  '".$val."'";
                    $query = $dbHandle->query($queryCmd);
                }
            }
            $examSelected = $formVal['examSelected']; //Ashish
            $this->makeListingExamsMapping($course_id, $examSelected, 'courses');
            */

            /* No Alert Supports from CMS Edit */
            /*
//Alert Mobile integration
$countryArr[0] =$formVal['country_id'];
$alertStatus =  $this->alertMobileFeeder('course',$course_id,$formVal['courseTitle'],$catArr,$countryArr);
*/

       /* }else{
            foreach ($queryCheckDup->result() as $row){
                $course_id = $row->course_id;
            }
            $response = array(array
                    ('QueryStatus'=>1,
                     'Course_id'=>$course_id,
                     'Listing_id'=>-1,
                     'type_id'=>$course_id,
                     'listing_type'=>"course",
                     'duplicate'=>1,
                    ),
                    'struct'
                    );
        } */
            //error_log("RESPONSE _server====>".print_r($response,TRUE));
            return $this->xmlrpc->send_response($response);
        }


        function supdateScholarship($request)
        {
        $parameters = $request->output_parameters(FALSE,FALSE);
        //error_log("EDIT SCHOL-LISTING Scholarship : ".print_r($parameters,true));
        $appId = $parameters['0']['0'];
        $formVal = $parameters['1'];
        $eligibility = $parameters['2'];
        //error_log("EDIT SCHOL-LISTING Scholarship : eligibility ".print_r($eligibility,TRUE));
        //error_log("EDIT SCHOL-LISTING Scholarship :FORMval ".print_r($formVal,TRUE));

        //connect DB
        $dbConfig = array( 'hostname'=>'localhost');
        $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
            //error_log("EDIT SCHOL-LISTING Scholarship : cannot create db handle");
        }
        if ($formVal['dataFromCMS']=="dataFromCMS") {
            $moderated = "moderated";
        } else {
            $moderated = "unmoderated";
        }

        $data =array();
        $data = array(
                'scholarship_name'=>$formVal['scholarship_name'],
                'short_desc'=>$formVal['short_desc'],
                'num'=>$formVal['num'],
                'levels'=>$formVal['levels'],
                'address_line1'=>$formVal['address_line1'],
                'address_line2'=>$formVal['address_line2'],
                'city_id'=>$formVal['city_id'],
                'country_id'=>$formVal['country_id'],
                'zip'=>$formVal['zip'],
                'application_procedure'=>$formVal['application_procedure'],
                'selection_process'=>$formVal['selection_process'],
                'segment'=>$formVal['segment'],
                'other_segment'=>$formVal['other_segment'],
                'value'=>$formVal['value'],
                'period_of_awards'=>$formVal['period_of_awards'],
                'institution'=>$formVal['institution'],
                'institute_id'=>$formVal['institute_id'],
                'last_date_submission' => $formVal['last_date_submission']
                );
                $where = 'scholarship_id = '.$formVal["update_schol_id"].'';

                $queryCmd = $dbHandle->update_string('scholarship',$data,$where);


        log_message('debug', 'query cmd is ' . $queryCmd);
        //error_log("EDIT SCHOL-LISTING Scholarship : ". $queryCmd);
        $query = $dbHandle->query($queryCmd);
        //			//error_log("Query Execution Status ".$query);

        $scholarship_id = $formVal["update_schol_id"];
        //error_log ("EDIT SCHOL-LISTING Scholarship : UPDATED scholarship id ".$scholarship_id);


        $listingIdQuery = 'SELECT listing_id FROM listings_main WHERE listing_type_id=? AND listing_type="scholarship"';
             log_message('debug', 'query cmd is ' . $listingIdQuery);
             //error_log($listingIdQuery);
             $query = $dbHandle->query($listingIdQuery, array($scholarship_id));
             $changeListingId=0;
        foreach ($query->result() as $row){
           $changeListingId = $row->listing_id;
        }


        //Query Command for Insert in the Listing Main Table
        $data =array();
        $format = 'DATE_ATOM';
        $data = array(
                'listing_type_id'=>$scholarship_id,
                'listing_title'=>$formVal['scholarship_name'],
                'short_desc'=>$formVal['short_desc'],
                'listing_type'=>'scholarship',
                'contact_email'=>$formVal['contact_email'],
                'contact_name'=>$formVal['contact_name'],
                'contact_cell'=>$formVal['contact_cell'],
                'contact_fax' => $formVal['contact_fax'],
                'address'=>$formVal['contact_address'],
                'requestIP'=>$formVal['requestIP'],
                'last_modify_date'=>standard_date($format,time()),
                'moderation_flag' => $moderated
                );
             $where = "listing_id = $changeListingId";

             $queryCmd = $dbHandle->update_string('listings_main',$data,$where);
        //error_log("EDIT SCHOL-LISTING Scholarship : ".$queryCmd);
        $query = $dbHandle->query($queryCmd);

        $response = array(array
                ('QueryStatus'=>$query,
                 'scholarship_id'=>$scholarship_id,
                 'Listing_id'=>$changeListingId,
                 'type_id'=>$scholarship_id,
                 'listing_type'=>"scholarship",
                ),
                'struct'
                );

                /* Not updating User-Points in CMS EDIT SCHOLARSHIP
                //update User point system
        $queryCmd = 'update userPointSystem set userPointValue=userPointValue+20 where userId='.$formVal['username'];
        //error_log("EDIT SCHOL-LISTING Scholarship : updating userpoint system");
        log_message('debug', 'update_Blog_UserPointSystem query cmd is ' . $queryCmd);

        if(!$dbHandle->query($queryCmd)){
            log_message('error', 'update_Blog_UserPointSystem query cmd failed' . $queryCmd);
            //error_log("EDIT SCHOL-LISTING Scholarship : updating user points system failed");
        }
        */



        //Query Command for Deletion in the scholarship_category_table

        $deleteQueryCmd = "DELETE FROM scholarship_category_table WHERE scholarship_id= ?";
        log_message('debug', 'query cmd is ' . $deleteQueryCmd);
        //error_log($deleteQueryCmd);
        $query = $dbHandle->query($deleteQueryCmd, array($scholarship_id));

        $catArr = array();
        if(isset($formVal['category_id']) && $formVal['category_id'] != ""){
            $categories = $formVal['category_id'];
            $catArr = explode(',',$categories);
            $numOfCats = count($catArr);
            for($i = 0; $i < $numOfCats ; $i++){
                //Query Command for Insert in the course category Table
                $queryCmd = "INSERT into scholarship_category_table (scholarship_id,category_id) values (?,?) on duplicate key update category_id =?";
                log_message('debug', 'query cmd is ' . $queryCmd);
                //error_log("EDIT SCHOL-LISTING Scholarship :".$queryCmd);
                $query = $dbHandle->query($queryCmd,array($scholarship_id,$catArr[$i], $catArr[$i]));
            }
        }

        //Query Command for Deletion in the institute_scholarship_mapping_table
/* FIXME To enable deletion once front end completed
        $deleteQueryCmd = "DELETE FROM institute_scholarship_mapping_table WHERE scholarship_id= $scholarship_id";
        log_message('debug', 'query cmd is ' . $deleteQueryCmd);
        //error_log($deleteQueryCmd);
        $query = $dbHandle->query($deleteQueryCmd);
        */
        //Query Command for Insert new Institute-Schol mappings
        if ($formVal['numoflocations']>0) {
            for ($i = 0;$i<$formVal['numoflocations'];$i++) {
                $queryCmd = 'INSERT into institute_scholarship_mapping_table (institute_id,scholarship_id) values (?,?)  on duplicate key update institute_id= ?';
                log_message('debug', 'query cmd is ' . $queryCmd);
                //error_log("EDIT SCHOL-LISTING Scholarship : ".$queryCmd);
                $query = $dbHandle->query($queryCmd, array($formVal['institute_id'.$i], $scholarship_id, $formVal['institute_id'.$i]));
            }
        }


        //Query Command for Deletion in the scholarship_eligibility_table

        $deleteQueryCmd = "DELETE FROM scholarship_eligibility_table WHERE scholarship_id= ?";
                    log_message('debug', 'query cmd is ' . $deleteQueryCmd);
                    //error_log($deleteQueryCmd);
                    $query = $dbHandle->query($deleteQueryCmd, array($scholarship_id));

//error_log(print_r($eligibility,true));
        if(isset($eligibility) && count($eligibility) > 0){
            foreach($eligibility as $key=>$val){
                $data =array();
                $data = array(
                        'scholarship_id'=>$scholarship_id,
                        'eligibility_criteria'=>$key,
                        'eligibility_criteria_values'=>$val
                        );
                $queryCmd = $dbHandle->insert_string('scholarship_eligibility_table',$data);
                //$queryCmd .= " on duplicate key update eligibility_criteria_values =  '".$val."'";
                //error_log("EDIT SCHOL-LISTING Scholarship : ".$queryCmd);
                $query = $dbHandle->query($queryCmd);
            }
        }
        //error_log("EDIT SCHOL-LISTING Scholarship : RESPONSE _server====>".print_r($response,TRUE));
        return $this->xmlrpc->send_response($response);
    }

/****************************************************************
DIFFERENT PRODUCTS APIs
****************************************************************/
	/*
	* XXX common lib method
	*/
	function getBoardChilds($categoryId){
		//connect DB
		$dbConfig = array( 'hostname'=>'localhost');
		$this->enterpriseconfig->getDbConfig(1,$dbConfig);
		$dbHandle  = $this->_loadDatabaseHandle();
		$categoryIdArray = array();
		$categoryIdString='';
		if($dbHandle == ''){
			log_message('error','getRecentEvent can not create db handle');
		}

		$queryCmd = ' SELECT t1.boardId AS lev1, t2.boardId as lev2, t3.boardId as lev3, t4.boardId as lev4 FROM categoryBoardTable AS t1 '.
				 'LEFT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId '.
				 'LEFT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId '.
				 'LEFT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t1.boardId = ?';

		log_message('debug', 'get board child query is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd, array($categoryId));
		foreach ($query->result() as $row){

			if(!array_key_exists($row->lev1,$categoryIdArray) && !empty($row->lev1)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev1]=$row->lev1;
				$categoryIdString .= $row->lev1;

			}
			if(!array_key_exists($row->lev2,$categoryIdArray) && !empty($row->lev2)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev2]=$row->lev2;
				$categoryIdString .= $row->lev2;

			}
			if(!array_key_exists($row->lev3,$categoryIdArray) && !empty($row->lev3)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev3]=$row->lev3;
				$categoryIdString .= $row->lev3;

			}
			if(!array_key_exists($row->lev4,$categoryIdArray) && !empty($row->lev4)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev4]=$row->lev4;
				$categoryIdString .= $row->lev4;

			}
		}
		if(strlen($categoryIdString)==0){
			$categoryIdString .= $categoryId;
		}
		return $categoryIdString;
	}

/** EVENT **/

	/*
	* This function returns the event detail for a event ID
	*/
	function sgetEventDetailCMS($request){

		$parameters = $request->output_parameters(FALSE,FALSE);
		$appId=$parameters['0'];
		$eventId=$parameters['1'];
		//connect DB
		$dbConfig = array( 'hostname'=>'localhost');
                $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
		$dbHandle  = $this->_loadDatabaseHandle();
		if($dbHandle == ''){
			log_message('error','getEventDetailCMS can not create db handle');
		}

		$queryCmd = 'select v.*,e.event_title,e.boardId subCategoryId,c.name subCategoryName,c.parentId categoryId,e.description,e.user_id,e.status_id,e.event_url,e.privacy,d.*, (select name from categoryBoardTable where boardId=c.parentId) categoryName from categoryBoardTable c,event e, event_venue v, event_date d where e.venue_id=v.venue_id and e.event_id=d.event_id and c.boardId=e.boardId and e.event_id=?';

		log_message('debug', 'getEventDetail query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd, array($eventId));
		//will only have one row
		$response=array();
		foreach ($query->result_array() as $row){
			$response = array($row,'struct');
		}
		return $this->xmlrpc->send_response($response);
	}
/** EVENT END **/

/** FORUMS **/

	/*
	*	Get the popular topics across all board's for a given country
	*/
	function sgetPopularTopicsCMS($request){

		$parameters = $request->output_parameters(FALSE,FALSE);
		$appID=$parameters['0'];
		$categoryId=$this->getBoardChilds($parameters['1']);
		$startFrom=$count=$parameters['2'];
		$count=$parameters['3'];
                $searchCriteria1=trim($parameters['4']);
                $searchCriteria2=trim($parameters['5']);
                $filter1=trim($parameters['6']);
		$showReportedAbuse=$parameters['7'];

		//connect DB
		$dbConfig = array( 'hostname'=>'localhost');
		$this->enterpriseconfig->getDbConfig($appID,$dbConfig);
		$dbHandle  = $this->_loadDatabaseHandle();
		if($dbHandle == ''){
			log_message('error','getPopularTopics can not create db handle');
		}

                if($searchCriteria1!=''){
                   $addSearch1 = 'AND messageTable.msgTitle LIKE "%'.$dbHandle->escape_like_str($searchCriteria1).'%"';
                }else{
                   $addSearch1 = '';
                }

                if($searchCriteria2!=''){
                   $addSearch2 = 'AND t.displayname LIKE "%'.$dbHandle->escape_like_str($searchCriteria2).'%"';
                }else{
                   $addSearch2 = '';
                }

                if($filter1!=''){
                   if($filter1 == 1){
                        $addFilter1 = '';
                     }else{
                        $addFilter1 = 'AND messageTable.countryId = '.$dbHandle->escape($filter1).'';
                     }
                }else{
                   $addFilter1 = '';
                }

		if($showReportedAbuse=="true"){
					$queryCmd = 'select SQL_CALC_FOUND_ROWS m1.boardId,m1.threadId,m1.msgTitle,m1.msgTxt,m1.creationDate,m1.viewCount popularityView,t.displayname,t.userid userId,t.lastlogintime,t.avtarimageurl userImage,(select level from userPointLevel where minLimit<=upv.userPointValue limit 1) level from messageTable m1,(select distinct boardId,threadId from messageTable where abuse>0 and status=0) m2, tuser t,userPointSystem upv where m1.boardId=m2.boardId and m1.threadId=m2.threadId and m1.parentId=0 and m1.userId=t.userid and m1.userId=upv.userId '. $addSearch1.' '.$addSearch2.' '.$addFilter1.' order by popularityView desc,creationDate asc LIMIT '.$startFrom.', '.$count;

		}else{
				$queryCmd = 'select SQL_CALC_FOUND_ROWS m1.boardId,m1.threadId,m1.msgTitle,m1.msgTxt,m1.creationDate,m1.viewCount popularityView,t.displayname,t.userid userId,t.lastlogintime,t.avtarimageurl userImage,(select level from userPointLevel where minLimit<=upv.userPointValue limit 1) level from messageTable m1,(select distinct boardId,threadId from messageTable where abuse=0 and status=0) m2, tuser t,userPointSystem upv where m1.boardId=m2.boardId and m1.threadId=m2.threadId and m1.parentId=0 and m1.userId=t.userid and m1.userId=upv.userId '. $addSearch1.' '.$addSearch2.' '.$addFilter1.' order by popularityView desc,creationDate asc LIMIT '.$startFrom.', '.$count;

		}

		log_message('debug', 'getPopularTopics query cmd is ' . $queryCmd);
                //error_log($queryCmd);
		$query = $dbHandle->query($queryCmd);
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
							array(
								'Count'=>array($row->count,'string'),
								'msgTitle'=>array($row->msgTitle,'string'),
								'msgThread'=>array($row->threadId,'string'),
								'msgTxt'=>array($row->msgTxt,'string'),
								'boardId'=>array($row->boardId,'string'),
                                                                'creationDate'=>array($row->creationDate,'string'),
								'DisplayName'=>array($row->displayname,'string'),
								'UserLevel'=>array($row->level,'string'),
								'userImage' => array($row->userImage,'string'),
								'popularityView' =>array($row->popularityView,'string'),
								'viewCount' => array($row->viewCount,'int'),
								'userId' => array($row->userId,'int')
							),'struct')
				   );//close array_push

		}
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
		$mainArr = array();
		array_push($mainArr,array(
				array(
					'results'=>array($msgArray,'struct'),
					'totalCount'=>array($totalRows,'string'),
				),'struct')
		);//close array_push
		$response = array($mainArr,'struct');
		return $this->xmlrpc->send_response($response);
	}

/** FORUMS END **/

	function sGetHeaderTabs($request)
	{
	   $parameters = $request->output_parameters(FALSE,FALSE);
	   //error_log(print_r($parameters,true));
	   $appID = $parameters['0'];
	   $userGroup = $parameters['1'];

	   $dbConfig = array( 'hostname'=>'localhost');
	   $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
	   $dbHandle  = $this->_loadDatabaseHandle();
	   if($dbHandle == ''){
	      //error_log('getPopularTopics can not create db handle');
	   }

	   $queryCmd = "select * from usergroupTabs where usergroup = ?";
	   $query = $dbHandle->query($queryCmd, array($userGroup));

	   $tabs = $query->first_row()->tabs;
	   $selectedTab = $query->first_row()->selected_tab;
	    
	    if(!(strlen($tabs) > 0)) {
		return array();
	    }
	    
	   $queryCmd = "select * from tabNames where tabId in ($tabs) order by tabOrder";
	   $query = $dbHandle->query($queryCmd);
	   $arrTabs = array();
	   foreach ($query->result() as $row)
	   {
	      array_push ($arrTabs, array(
		 		array(
				      'tabId' => array($row->tabId,'string'),
				      'tabName' => array($row->tabName,'string'),
				      'tabUrl' => array($row->tabUrl,'string')
				   ),'struct')
			);
	   }

	   $response = array();
	   array_push($response,array(
	      array(
		 'selectedTab'=>array($selectedTab,'string'),
		 'tabs' => array($arrTabs,'struct')
	      ),'struct')
	   );

	   $response1 = array($response,'struct');
	   return $this->xmlrpc->send_response($response1);
       }


       //Server API for Enterprise User Registration
       function saddEnterpriseUser($request)
       {
           $parameters = $request->output_parameters(FALSE,FALSE);
           //error_log("SHASHWAT SERVER RECEIVED ARRAY ".print_r($parameters,true));
           $userData = $parameters['0'];

           $appId = $userData['appId'];
           $userid = $userData['userid'];
           $sumsUserId = $userData['sumsUserId'];
           $busiCollegeName = $userData['busiCollegeName'];
           $busiType = $userData['busiType'];
           $contactAddress = $userData['contactAddress'];
           $pincode = $userData['pincode'];
           $categories = $userData['categories'];
           $executiveName = $userData['executiveName'];

           //connect DB
           $dbConfig = array( 'hostname'=>'localhost');
           $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
           $dbHandle  = $this->_loadDatabaseHandle();
           if($dbHandle == ''){
           	log_message('error','can not create db handle');
           }
           $data = array (
           			"userId"=>$userid,
           			"businessCollege"=>$busiCollegeName,
           			"businessType"=>$busiType,
           			"contactAddress"=>$contactAddress,
           			"pincode"=>$pincode,
           			"categories"=>$categories,
           			"executiveName"=>$executiveName
           			);
           	$queryCmd = $dbHandle->insert_string('enterpriseUserDetails',$data);
           	//error_log($queryCmd);
           	$query = $dbHandle->query($queryCmd);

                // The Bronze Listing Derived Product to be name 'BASIC BRONZE LISTING' Only
		$prodClient = new Sums_product_client();
		$result = $prodClient->getFreeDerivedId(1);

		$dervdProdId = $result['derivedProdId'];
                $param['derivedProdId'] = $dervdProdId;
           	$param['derivedQuantity'] = 1;
           	$param['clientUserId'] = $userid;
                $param['sumsUserId'] = $sumsUserId;
           	$param['subsStartDate'] = date(DATE_ATOM);
		//error_log("Array to addFreeSubscription ".print_r($param,true));
           	//$param['subsEndDate'] = date(DATE_ATOM,mktime(0, 0, 0, date("m")+1, date("d"), date("Y")));
                $objSumsClient = new Subscription_client();
                $respSubs = $objSumsClient->addFreeSubscription(1,$param);

                $response = array($respSubs,'struct');
           	//error_log(print_r($response,true).'ReSPONSE');
           	return $this->xmlrpc->send_response($response);
       }

	function sgetInstituteList($request)
	{
		$parameters = $request->output_parameters(FALSE,FALSE);
		$appId = $parameters['0'];
		$city_id = $parameters['1'];
                $usergroup = $parameters['2'];
                $userid = $parameters['3'];
                //connect DB
                $dbConfig = array( 'hostname'=>'localhost');
                $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
                $dbHandle  = $this->_loadDatabaseHandle();
                if($dbHandle == ''){
                    log_message('error','can not create db handle');
                }

                if(($usergroup=='cms') && ($userid < 12)){
                        $addUserid = '';
                    }else{
                        $addUserid = 'AND I.username = '.$dbHandle->escape($userid).' ';
                    }

		if($city_id <=0){
                    $queryCmd = 'SELECT * from institute I, institute_location_table L, listings_main M WHERE I.institute_id = L.institute_id AND M.status = "live" AND M.listing_type="institute" AND M.listing_type_id=I.institute_id '.$addUserid.' group by I.institute_id ORDER BY TRIM(I.institute_name)';
		}
		else{
                    $queryCmd = 'SELECT * from institute I, institute_location_table L, listings_main M WHERE I.institute_id = L.institute_id AND L.city_id ='.$city_id.' AND M.status = "live" and M.listing_type="institute" AND M.listing_type_id=I.institute_id '.$addUserid.' ORDER BY TRIM(I.institute_name)';
		}
		//error_log($queryCmd);
		log_message('debug', 'query cmd is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		$counter = 0;
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
						array(
							'instituteID'=>array($row->institute_id,'string'),
							'instituteName'=>array($row->institute_name,'string'),
							'cityId'=>array($row->city_id,'string'),
							'countryId'=>array($row->country_id,'string')
						     ),'struct'));//close array_push

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}


        function sgetCitiesWithCollege($request)
        {
            $parameters = $request->output_parameters(FALSE,FALSE);
            $appId = $parameters['0'];
            $countryId = $parameters['1'];
            $usergroup = $parameters['2'];
            $userid = $parameters['3'];
            //connect DB
            $dbConfig = array( 'hostname'=>'localhost');
            $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
            $dbHandle  = $this->_loadDatabaseHandle();
            if($dbHandle == ''){
                log_message('error','can not create db handle');
            }

            if(($usergroup=='cms') && ($userid < 12)){
                $addUserid = '';
            }else{
                $addUserid = "AND M.username = ".$dbHandle->escape($userid)." ";
            }

            $queryCmd = "select distinct(L.city_id),city_name from institute I,institute_location_table L,countryCityTable C,listings_main M where I.institute_id=L.institute_id AND L.country_id = ? AND L.city_id = C.city_id AND M.listing_type_id=I.institute_id AND M.listing_type='institute' AND M.status ='live' $addUserid order by trim(city_name)";
            //error_log("Listing Server : ".$queryCmd);
            $query = $dbHandle->query($queryCmd, array($countryId));
            $counter = 0;
            $msgArray = array();
            foreach ($query->result() as $row)
            {
	      array_push($msgArray,array(
		 array(
		    'cityID'=>array($row->city_id,'string'),
		    'cityName'=>array($row->city_name,'string')
		 ),'struct'));//close array_push

	   }
	   $response = array($msgArray,'struct');
	   return $this->xmlrpc->send_response($response);
       }

       function getSearchSubCategories($request)
       {
	  $parameters= $request->output_parameters(FALSE,FALSE);
	  $dbConfig = array( 'hostname'=>'localhost');
	  $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
	  $dbHandle  = $this->_loadDatabaseHandle();
	  if($dbHandle == ''){
	     log_message('error','can not create db handle');
	  }

	  $queryCmd = "select boardId,name from categoryBoardTable where parentId = 1";
	  $result = $dbHandle->query($queryCmd);
	  $arrCategories = array();
	  $arrCategories['foreign'] = array("Foreign Education",'string');
	  $arrCategories['testprep'] = array("Test Prepartion",'string');

	  foreach ($result->result() as $row)
	  {
	     $arrCategories["Category-".$row->boardId]= array($row->name,'string');
	  }
	  $response = array ($arrCategories,'struct');
	  return $this->xmlrpc->send_response($response);

       }

       function sEditNotification($request)
       {
           $parameters = $request->output_parameters(FALSE,FALSE);
           $appId = $parameters['0'];
           $formVal = $parameters['1'];
           $eligibility = $parameters['2'];
           //connect DB
           $dbConfig = array( 'hostname'=>'localhost');
           $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
           $dbHandle  = $this->_loadDatabaseHandle();
           $data = array(
               'admission_notification_name'=>$formVal['admission_notification_name'],
               'short_desc'=>$formVal['short_desc'],
               'admission_year'=>$formVal['admission_year'],
               'application_brochure_start_date'=>$formVal['application_brochure_start_date'],
               'application_brochure_end_date'=>$formVal['application_brochure_end_date'],
               'application_end_date'=>$formVal['application_end_date'],
               'application_procedure'=>$formVal['application_procedure'],
               'fees'=>$formVal['fees'],
               'entrance_exam'=>$formVal['entrance_exam']
           );
           $where = "admission_notification_id = ".$formVal['admission_notification_id'];
           $queryCmd = $dbHandle->update_string('admission_notification',$data,$where);
           //error_log("EDIT LISTING Admission : ".$queryCmd);
           $query = $dbHandle->query($queryCmd);

           $format = 'DATE_ATOM';
           $data = array(
               'listing_title'=>$formVal['admission_notification_name'],
               'short_desc'=>$formVal['short_desc'],
               'threadId'=>$formVal['threadId'],
               'contact_email'=>$formVal['contact_email'],
               'contact_name'=>$formVal['contact_name'],
               'address' =>$formVal['contact_address'],
               'contact_fax' =>$formVal['contact_fax'],
               'contact_email' => $formVal['contact_email'],
               'contact_cell'=>$formVal['contact_cell'],
               'last_modify_date'=>standard_date($format,time()),
               'requestIP'=>$formVal['requestIP']
           );

           $where = "listing_type_id=".$formVal['admission_notification_id']." and listing_type='notification'";
           $queryCmd = $dbHandle->update_string('listings_main',$data,$where);
           //error_log("EDIT LISTING Admission : ".$queryCmd);
           $query = $dbHandle->query($queryCmd);
           $response = array($query,int);
           $response = array(array
           ('QueryStatus'=>$query,
           'type_id'=>$formVal['admission_notification_id'],
           'listing_type'=>"notification",
           'title'=>$formVal['admission_notification_name']),'struct');

           $queryCmd = "delete from admission_notification_category_table where admission_notification_id = ?";
           $query = $dbHandle->query($queryCmd, array($formVal['admission_notification_id']));
           //error_log("EDIT LISTING Admission : ".$queryCmd);
           $catArr = array();
           if(isset($formVal['category_id']) && $formVal['category_id'] != ""){
               $categories = $formVal['category_id'];
               $catArr = explode(',',$categories);
               $numOfCats = count($catArr);
               for($i = 0; $i < $numOfCats ; $i++){
                   $queryCmd = "INSERT into admission_notification_category_table (admission_notification_id,category_id) values (?,?) on duplicate key update category_id =?";
                   //error_log("ADD LISTING Admission : ".$queryCmd);
                   $query = $dbHandle->query($queryCmd, array($formVal['admission_notification_id'], $catArr[$i], $catArr[$i]));
               }
           }

           $queryCmd = "delete from institute_examinations_mapping_table where admission_notification_id = ?";
           $query = $dbHandle->query($queryCmd, array($formVal['admission_notification_id']));
           //error_log("EDIT LISTING Admission : ".$queryCmd);
           $queryCmd = 'INSERT into institute_examinations_mapping_table (institute_id,admission_notification_id) values (?,?)';
           $query = $dbHandle->query($queryCmd, array($formVal['institute_id'], $formVal['admission_notification_id']));
           //error_log("EDIT LISTING Admission : ".$queryCmd);

           /*
           $queryCmd = "delete from admission_notification_exam_table where admission_notification_id = ".$formVal['admission_notification_id'];
           $query = $dbHandle->query($queryCmd);
           //error_log("EDIT LISTING Admission : ".$queryCmd);
           if($formVal['entrance_exam'] == "yes")
           {
               $data = array(
                   'admission_notification_id'=>$formVal['admission_notification_id'],
                   'exam_name'=>$formVal['exam_name'],
                   'exam_date'=>$formVal['exam_date'],
                   'exam_duration'=>$formVal['exam_duration'],
                   'exam_timings'=>$formVal['exam_timings']
               );
               $queryCmd = $dbHandle->insert_string('admission_notification_exam_table',$data);
               $queryCmd .= " on duplicate key update admission_notification_id =".$formVal['admission_notification_id'];
               //error_log("EDIT LISTING Admission : ".$queryCmd);
               $query = $dbHandle->query($queryCmd);

               $queryCmd = "delete from admission_notification_exam_centres_table where admission_notification_id = ".$formVal['admission_notification_id'];
               $query = $dbHandle->query($queryCmd);
               //error_log("EDIT LISTING Admission : ".$queryCmd);
               for($i = 0 ; $i < $formVal['numOfCentres']; $i++)
               {
                   $data =array();
                   $data = array(
                       'admission_notification_id'=>$formVal['admission_notification_id'],
                       'address_line1'=>$formVal['address_line1'.$i],
                       'address_line2'=>$formVal['address_line2'.$i],
                       'city_id'=>$formVal['city_id'.$i],
                       'country_id'=>$formVal['country_id'.$i],
                       'zip'=>$formVal['zip'.$i]
                   );
                   $queryCmd = $dbHandle->insert_string('admission_notification_exam_centres_table',$data);
                   //error_log("EDIT LISTING Admission : ".$queryCmd);
                   $query = $dbHandle->query($queryCmd);
               }
           }
           */

           $admission_not_id = $formVal['admission_notification_id'];

           $deleteQueryCmd = "DELETE FROM listingExamMap WHERE type='notification' and typeId=? and typeOfMap='required' ";
           log_message('debug', 'query cmd is ' . $deleteQueryCmd);
           //error_log($deleteQueryCmd);
           $query = $dbHandle->query($deleteQueryCmd, array($admission_not_id));

           $deleteQueryCmdOther = "DELETE FROM othersExamTable WHERE listingType='notification' and listingTypeId=? and typeOfMap='required' ";
           log_message('debug', 'query cmd is ' . $deleteQueryCmdOther);
           //error_log($deleteQueryCmdOther);
           $query = $dbHandle->query($deleteQueryCmdOther, array($admission_not_id));

           $deleteQueryCmdCenter = "DELETE FROM other_exam_centres_table WHERE other_exam_id in (select other_exam_id from othersExamTable where listingType='notification' and listingTypeId=? and typeOfMap='required') ";
           log_message('debug', 'query cmd is ' . $deleteQueryCmdCenter);
           //error_log($deleteQueryCmdCenter);
           $query = $dbHandle->query($deleteQueryCmdCenter, array($admission_not_id));


           if($formVal['entrance_exam'] == "yes"){
               $this->load->model('ExamModel','',$dbConfig);
               //error_log("NEWEXAM::".print_r($formVal,true));
               if(isset($formVal['tests_required']) && strlen($formVal['tests_required']) > 0 )
               {
                   $examsArr = explode(",",$formVal['tests_required']);
                   $this->ExamModel->makeEntityExamsMapping($admission_not_id, $examsArr,'notification','required');
               }
               if($formVal['tests_required_other'] == 'true'){
                   $examData =array();
                   $examData['listingType'] = 'notification';
                   $examData['listingTypeId'] = $admission_not_id;
                   $examData['typeOfMap'] = 'required';
                   $examData['exam_name'] = $formVal['exam_name'];
                   $examData['exam_date'] = $formVal['exam_date'];
                   $examData['exam_desc'] = $formVal['exam_desc'];
                   $examData['exam_duration'] = $formVal['exam_duration'];
                   $examData['exam_timings'] = $formVal['exam_timings'];
                   $examData['numOfCentres'] = $formVal['numOfCentres'];
                   for($i = 0 ; $i < $formVal['numOfCentres']; $i++)
                   {
                       $examData['address_line1'.$i] = $formVal['address_line1'.$i];
                       $examData['address_line2'.$i] = $formVal['address_line2'.$i];
                       $examData['city_id'.$i] = $formVal['city_id'.$i];
                       $examData['country_id'.$i] = $formVal['country_id'.$i];
                       $examData['zip'.$i] = $formVal['zip'.$i];
                   }
                   $newExamId = $this->ExamModel->insertOtherExam($examData);
               }
           }


           $queryCmd = "delete from admission_notification_eligibility_table where admission_notification_id = ?";
           $query = $dbHandle->query($queryCmd, array($formVal['admission_notification_id']));
           //error_log("EDIT LISTING Admission : ".$queryCmd);
           if(isset($eligibility) && count($eligibility) > 0){
               foreach($eligibility as $key=>$val){
                   $data =array();
                   $data = array(
                       'admission_notification_id'=>$formVal['admission_notification_id'],
                       'eligibility_criteria'=>$key,
                       'eligibility_criteria_values'=>$val
                   );
                   $queryCmd = $dbHandle->insert_string('admission_notification_eligibility_table',$data);
                   //error_log("EDIT LISTING Admission : ".$queryCmd);
                   $query = $dbHandle->query($queryCmd);
               }
           }
           return $this->xmlrpc->send_response($response);
       }

       function sRemoveNotificationDoc($request)
       {
			$parameters = $request->output_parameters(FALSE,FALSE);
			//connect DB
            $dbConfig = array( 'hostname'=>'localhost');
            $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
            $dbHandle  = $this->_loadDatabaseHandle();
            if($dbHandle == ''){
                log_message('error','can not create db handle');
            }
			$docId = $parameters['1'];
			$notificationId = $parameters['2'];
			$response = array();
			$queryCmd = "delete from admission_notification_doc_table where doc_id = ? and admission_notification_id= ?";
			$result = $dbHandle->query($queryCmd, array($docId, $notificationId));
			$response = array($query,'int');
			return $this->xmlrpc->send_response($response);
       }

       function sRemoveScholarshipDoc($request){
		$parameters = $request->output_parameters(FALSE,FALSE);
                //error_log("Schol DOC remove SERVER RECEIVED ARRAY ".print_r($parameters,true));
		$appID = $parameters['0'];
                $removeData = $parameters['1'];

                $scholarshipId = $removeData['scholarshipId'];
                $docId = $removeData['docId'];


                //connect DB
                $dbConfig = array( 'hostname'=>'localhost');
                $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
                $dbHandle  = $this->_loadDatabaseHandle();
                if($dbHandle == ''){
                    log_message('error','can not create db handle');
                }

                $queryCmd = 'DELETE FROM scholarship_doc_table WHERE scholarship_id=? AND doc_id=? ';
		log_message('debug', 'query cmd is ' . $queryCmd);
                //error_log($queryCmd);
		$query = $dbHandle->query($queryCmd, array($scholarshipId, $docId));
                $scholResponse = array(array('QueryStatus'=>$query,'int'),'struct');
                //error_log("SHAS institute remove Media content Response Array==> ".print_r($scholResponse,TRUE));

                //return $this->xmlrpc->send_response($response);
                return $this->xmlrpc->send_response($scholResponse);
	}
       function getNotificationEvents($request)
       {
       		$parameters = $request->output_parameters(FALSE,FALSE);
       		$dbConfig = array( 'hostname'=>'localhost');
            $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
            $dbHandle  = $this->_loadDatabaseHandle();
            if($dbHandle == ''){
                log_message('error','can not create db handle');
            }
			$notificationId = $parameters['1'];
			$response = array();
			$queryCmd = "select event_id from event where listing_type_id = ? and listingType = 'notification'";
			//error_log($queryCmd);
			$result = $dbHandle->query($queryCmd, array($notificationId));

			foreach($result->result() as $row)
			{
				array_push($response,array($row->event_id));
			}
			$response = array($response,'struct');
			return $this->xmlrpc->send_response($response);
       }

       function getEnterpriseUserDetails($request)
       {
           $parameters = $request->output_parameters(FALSE,FALSE);
           $dbConfig = array( 'hostname'=>'localhost');
           $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
           $dbHandle  = $this->_loadDatabaseHandle();
           if($dbHandle == ''){
               log_message('error','can not create db handle');
           }
           $userId = $parameters['1'];

           $queryCmd = "select CAST((select country from shiksha.tuser t where t.userid=?) AS SIGNED INTEGER) as countryId";
           //error_log_shiksha($queryCmd);
           $result = $dbHandle->query($queryCmd, array($userId));
           if ($result->result() != NULL) {
               $countryId = $result->first_row()->countryId;
           }

           $queryCmd = "select CAST((select city from shiksha.tuser t where t.userid=?) AS SIGNED INTEGER) as cityId";
           //error_log_shiksha($queryCmd);
           $result = $dbHandle->query($queryCmd, array($userId));
           if ($result->result() != NULL) {
               $cityId = $result->first_row()->cityId;
           }

           if($countryId != NULL && $countryId !=0){
               $countryClause = "(select name from shiksha.countryTable where countryId=".$dbHandle->escape($countryId).") as countryName";
           }else{
               $countryClause = "(select country from shiksha.tuser where shiksha.tuser.userid=".$dbHandle->escape($userId).") as countryName";
           }

           if($cityId != NULL && $cityId !=0){
               $cityClause = "(select city_name from shiksha.countryCityTable where city_id=".$dbHandle->escape($cityId).") as cityName";
           }else{
               $cityClause = "(select city from shiksha.tuser where shiksha.tuser.userid=".$dbHandle->escape($userId).") as cityName";
           }

           $queryCmd = "select t.userid, t.displayname,t.email,t.city,t.country,t.mobile,t.firstname,e.businessCollege,e.businessType,e.contactAddress,e.pincode,e.categories,$cityClause,$countryClause from tuser t, enterpriseUserDetails e where t.userid = e.userId and t.userid = ?";
           //error_log_shiksha($queryCmd);
           $result = $dbHandle->query($queryCmd, array($userId));

           $response = array (
               "userId"=>array($result->first_row()->userid,'string'),
               "displayname"=>array($result->first_row()->displayname,'string'),
               "email"=>array($result->first_row()->email,'string'),
               "city"=>array($result->first_row()->city,'string'),
               "country"=>array($result->first_row()->country,'string'),
               "businessCollege"=>array($result->first_row()->businessCollege,'string'),
               "businessType"=>array($result->first_row()->businessType,'string'),
               "contactName"=>array($result->first_row()->firstname,'string'),
               "contactAddress"=>array($result->first_row()->contactAddress,'string'),
               "pincode"=>array($result->first_row()->pincode,'string'),
               "mobile"=>array($result->first_row()->mobile,'string'),
               "cityName"=>array($result->first_row()->cityName,'string'),
               "countryName"=>array($result->first_row()->countryName,'string'),
               "categories"=>array($result->first_row()->categories,'string')
           );
           $response = array($response,'struct');
           return $this->xmlrpc->send_response($response);
       }


       function updateEnterpriseUserDetails($request)
       {
			$parameters = $request->output_parameters(FALSE,FALSE);
			$vals = $parameters['1'];
			//error_log("UPDATE user form array ".print_r($vals,true));
			$dbConfig = array( 'hostname'=>'localhost');
            $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
            $dbHandle  = $this->_loadDatabaseHandle();
            if($dbHandle == ''){
                log_message('error','can not create db handle');
            }

			$format = DATE_ATOM;
			$data = array(
					'mobile'=>$vals['mobile'],
					'country'=>$vals['country'],
					'city'=>$vals['city'],
					'firstname'=>$vals['contactName'],
					'lastModifiedOn'=>standard_date($format,time())
			);
			$where = "userid=".$vals['userId'];
			$queryCmd = $dbHandle->update_string('tuser',$data,$where);
			//error_log("Edit Enterprise Profile :".$queryCmd);
			$query = $dbHandle->query($queryCmd);

			$data = array(
					'contactAddress'=>$vals['contactAddress'],
					'pincode'=>$vals['pincode'],
					'categories'=>$vals['categories']
				);
			$where = "userId=".$vals['userId'];
			$queryCmd = $dbHandle->update_string('enterpriseUserDetails',$data,$where);
			//error_log("Edit Enterprise Profile :".$queryCmd);
			$query = $dbHandle->query($queryCmd);
			$response = array("query"=>array($query,'int'));
			return $this->xmlrpc->send_response($response);
       }

       function changePassword($request)
       {
       		$parameters = $request->output_parameters(FALSE,FALSE);
			$vals = $parameters['1'];
			//error_log("change password user form array ".print_r($vals,true));
			$dbConfig = array( 'hostname'=>'localhost');
            $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
            $dbHandle  = $this->_loadDatabaseHandle();
            if($dbHandle == ''){
                log_message('error','can not create db handle');
            }
			$format = DATE_ATOM;
			$data = array(
					'ePassword'=>sha256($vals['newPassword'])
			);
			$where = "userid=".$vals['userId']." and ePassword='".sha256($vals['oldPassword'])."'";
			$queryCmd = $dbHandle->update_string('tuser',$data,$where);

			//error_log("update password Profile :".$queryCmd);
			$query = $dbHandle->query($queryCmd);
            
            $usermodel = $this->load->model('user/usermodel');
            $usermodel->trackPasswordChange($userId);
			$response = array($query,'int');
			//error_log(print_r($response,true));
			return $this->xmlrpc->send_response($response);
       }

       function updateUserGroup($request)
       {
	       	$parameters = $request->output_parameters(FALSE,FALSE);
	       	$userId = $parameters['1'];
	       	$dbConfig = array( 'hostname'=>'localhost');
	       	$this->enterpriseconfig->getDbConfig($appId,$dbConfig);
	       	$dbHandle  = $this->_loadDatabaseHandle();
	       	if($dbHandle == ''){
	       		log_message('error','can not create db handle');
	       	}
	       	$data = array(
	       	'usergroup'=>"enterprise"
	       	);
	       	$where = "userid=".$userId;
	       	$queryCmd = $dbHandle->update_string('tuser',$data,$where);
	       	//error_log("update usergroup Profile :".$queryCmd);
	       	$query = $dbHandle->query($queryCmd);
	       	$response = array($query,'int');
	       	//error_log(print_r($response,true));
	       	return $this->xmlrpc->send_response($response);
       }

       function getViewCountForUserFedListings($request)
       {
       		$parameters = $request->output_parameters(FALSE,FALSE);
	       	$userId = $parameters['1'];
	       	$dbConfig = array( 'hostname'=>'localhost');
	       	$this->enterpriseconfig->getDbConfig($appId,$dbConfig);
	       	$dbHandle  = $this->_loadDatabaseHandle();
	       	if($dbHandle == ''){
	       		log_message('error','can not create db handle');
	       	}
	       	$queryCmd = "select sum(viewCount) views from listings_main where username = ? and status ='live'";
	       	$query = $dbHandle->query($queryCmd, array($userId));
	       	$response = array($query->first_row()->views,'int');
	       	return $this->xmlrpc->send_response($response);
       }
	function sgetcountofMedia($request)
{

			$parameters = $request->output_parameters(FALSE,FALSE);
			//connect DB
            $dbConfig = array( 'hostname'=>'localhost');
            $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
            $dbHandle  = $this->_loadDatabaseHandle();
            if($dbHandle == ''){
                log_message('error','can not create db handle');
            }
			$typeofmedia = $parameters['1'];
			$startDate = $parameters['3'];
			$endDate = $parameters['4'];
			$response = array();
			$queryCmd = "select userid as userid from tuser where usergroup not in('cms','enterprise')";
			if($startDate != 0 && $endDate != 0)
			$queryCmd .= " and usercreationDate between " .$dbHandle->escape($startDate)." and ".$dbHandle->escape($endDate);
			//error_log($queryCmd);
			$query = $dbHandle->query($queryCmd);
			$count = $query->num_rows();
			return $this->xmlrpc->send_response($count);

}


	function sdeleteMediaData($request)
       {
			$parameters = $request->output_parameters(FALSE,FALSE);
			//connect DB
            $dbConfig = array( 'hostname'=>'localhost');
            $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
            $dbHandle  = $this->_loadDatabaseHandle();
            if($dbHandle == ''){
                log_message('error','can not create db handle');
            }
			$type = $parameters['1'];
			$userids = $parameters['2'];
			$response = array();

$queryCmd = "insert into tuser_deleted(select *,'".$type."' from tuser where userid in(".$userids."))";
		  		    //error_log($queryCmd);
                                    $query = $dbHandle->query($queryCmd);
if($dbHandle->affected_rows() > 0)
{
if($type == "user")
{
$queryCmd = "delete from tuser where userid in(".$userids.")";
}
if($type == "image")
{
$queryCmd = "update tuser set avtarimageurl = '/public/images/photoNotAvailable.gif' where userid in(".$userids.")";
}
		  		    //error_log($queryCmd);
                                    $query = $dbHandle->query($queryCmd);
               			    if($dbHandle->affected_rows() > 0)
				    $response = 1;
				    else
				    $response = 0;
				    return $this->xmlrpc->send_response($response);
}
$response = 0;
				    return $this->xmlrpc->send_response($response);
       }
	function sgetMediaData($request)
       {
			$parameters = $request->output_parameters(FALSE,FALSE);
			//connect DB
            $dbConfig = array( 'hostname'=>'localhost');
            $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
            $dbHandle  = $this->_loadDatabaseHandle();
            if($dbHandle == ''){
                log_message('error','can not create db handle');
            }
			$typeofmedia = $parameters['1'];
			$start = $parameters['2'];
			$count = $parameters['3'];
			$startDate = $parameters['4'];
			$endDate = $parameters['5'];
			$response = array();

			$queryCmd = "select userid,email,displayname,avtarimageurl,usercreationdate from tuser where usergroup not in('cms','enterprise')";

if($startDate != 0 && $endDate != 0)
$queryCmd .= " and usercreationDate between " .$dbHandle->escape($startDate)." and ".$dbHandle->escape($endDate);
$queryCmd .= "limit ".$start.','.$count;
			//error_log($queryCmd);
                                    $query = $dbHandle->query($queryCmd);
                                    $msgArray = array();
                                    foreach ($query->result_array() as $row){
                                        array_push($msgArray,array($row,'struct'));
                                    }
                                    $response = array($msgArray,'struct');
			return $this->xmlrpc->send_response($response);
       }

    function getReportedChangesForBlogs($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appId = $parameters['0'];
        $type = $parameters['1'];
        $typeId = $parameters['2'];
        //connect DB
        $dbConfig = array( 'hostname'=>'localhost');
        $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = 'SELECT trc.*, bt.blogTitle, bt.blogType from tReportChanges trc INNER JOIN blogTable bt on bt.blogId = trc.listing_type_id WHERE  trc.listing_type = ? and bt.status = "live" ';
        if(strlen($typeId) > 0){
            $addWhere = " and trc.listing_type_id = ".$dbHandle->escape($typeId)." ";
        }
        $queryCmd  .= " $addWhere";
        //error_log($queryCmd);
        $query = $dbHandle->query($queryCmd, array($type));
        $counter = 0;
        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                        array(
                            'id'=>array($row->id,'string'),
                            'email'=>array($row->email,'string'),
                            'blogTitle'=>array($row->blogTitle,'string'),
                            'blogType'=>array($row->blogType,'string'),
                            'comments'=>array($row->comment,'string'),
                            'type'=>array($row->listing_type,'string'),
                            'typeId'=>array($row->listing_type_id,'string'),
                            'submit_date'=>array($row->submit_date,'string')
                            ),'struct'));//close array_push

        }
        ////error_log(print_r($msgArray,true));
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getReportedChangesById($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appId = $parameters['0'];
        $changeId = $parameters['1'];
        //connect DB
        $dbConfig = array( 'hostname'=>'localhost');
        $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = 'SELECT * from tReportChanges trc WHERE  trc.id = ?';
        //error_log($queryCmd);
        $query = $dbHandle->query($queryCmd, array($changeId));
        $counter = 0;
        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                        array(
                            'id'=>array($row->id,'string'),
                            'email'=>array($row->email,'string'),
                            'comments'=>array($row->comment,'string'),
                            'type'=>array($row->listing_type,'string'),
                            'typeId'=>array($row->listing_type_id,'string'),
                            'submit_date'=>array($row->submit_date,'string')
                            ),'struct'));//close array_push

        }
        ////error_log(print_r($msgArray,true));
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

function saddMainCollegeLink($request)
{
	$parameters = $request->output_parameters(FALSE,FALSE);
	//connect DB
	$dbConfig = array( 'hostname'=>'localhost');
	$this->enterpriseconfig->getDbConfig($appID,$dbConfig);
	$dbHandle  = $this->_loadDatabaseHandle();
	if($dbHandle == ''){
		log_message('error','can not create db handle');
	}
        $queryCmd="select count(*) count from ".$this->enterpriseconfig->pageCollegeTable." where KeyId=? and CollegeId=? and StartDate >=? and EndDate <=? and EndDate>now()";
	//error_log($queryCmd);
	$query=$dbHandle->query($queryCmd,array($parameters[1], $parameters[2], $parameters[4], $parameters[5]));
	foreach($query->result() as $row)
	{
		$count=$row->count;
		if($count>=1)
		{
			$response = array(
					array(
						'result'=>-1,
						'error'=>'already set'
					     ),
					'struct');
			return $this->xmlrpc->send_response($response);
		}
	}
	$queryCmd = 'INSERT INTO '.$this->enterpriseconfig->pageCollegeTable.'  (KeyId,CollegeId,StartDate,EndDate,subscriptionId) VALUES (?,?,?,?,?) ';
	log_message('debug', 'Insert query cmd is ' . $queryCmd);
	//error_log("INSERT QUERY Command at Server".$queryCmd);
	$query = $dbHandle->query($queryCmd,array($parameters[1], $parameters[2], $parameters[4], $parameters[5], $parameters[6]));
	//error_log("INSERT Query Execution Status==>".$query);
	$mainCollegeLinkId=$dbHandle->insert_id();
	$response = array(
			array(
				'result'=>$mainCollegeLinkId,
			     ),
			'struct');
	return $this->xmlrpc->send_response($response);

    }

function sgetListingsByClient($request)
{
    $parameters = $request->output_parameters(FALSE,FALSE);
    //error_log(print_r($parameters,true));
        $appId = $parameters['0'];
        $userArr = $parameters['1'];
        $userid = $userArr['userid'];
        $startFrom=$userArr['startFrom'];
        $countOffset=$userArr['countOffset'];

        //connect DB
        $dbConfig = array( 'hostname'=>'localhost');
        $this->enterpriseconfig->getDbConfig($appId,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','adduser can not create db handle');
        }
        $queryCmd = 'SELECT listing_id,listing_type,listing_type_id,listing_title,expiry_date,submit_date,last_modify_date from listings_main where listings_main.username = ? ORDER BY listing_type,last_modify_date desc LIMIT '.$startFrom.', '.$countOffset;
        log_message('debug', 'query cmd is ' . $queryCmd);
        //error_log(" sgetListingsByClient ".$queryCmd);
        $query = $dbHandle->query($queryCmd, array($userid));

        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                        array(
                            'listing_id'=>array($row->listing_id,'string'),
                            'listing_type'=>array($row->listing_type,'string'),
                            'listing_type_id'=>array($row->listing_type_id,'string'),
                            'listing_title'=>array($row->listing_title,'string'),
                            'expiry_date'=>array($row->expiry_date,'string'),
                            'submit_date'=>array($row->submit_date,'string'),
                            'last_modify_date'=>array($row->last_modify_date,'string')
                            ),'struct'));//close array_push
        }

        ////error_log(print_r($msgArray,true));
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

function scancelSubscription($request)
{
	$parameters = $request->output_parameters(FALSE,FALSE);
	//connect DB
	$dbConfig = array( 'hostname'=>'localhost');
	$this->enterpriseconfig->getDbConfig($appID,$dbConfig);
	$dbHandle  = $this->_loadDatabaseHandle();
	if($dbHandle == ''){
		log_message('error','can not create db handle');
	}
	$queryCmd="update ".$this->enterpriseconfig->pageCollegeTable." set status='deleted' where subscriptionId=?";
	//error_log($queryCmd);
	$query = $dbHandle->query($queryCmd, array($parameters[1]));
	//error_log("INSERT Query Execution Status==>".$query);
	$response = array(
			array(
				'result'=>1,
			     ),
			'struct');
	return $this->xmlrpc->send_response($response);

}
	private function makeListingExamsMapping($listingId, $exams, $listing){
//error_log("ASHISH::::MODEL CALL");
		$this->load->model('ArticleModel');
		$this->ArticleModel->makeEntityExamsMapping($listingId, $exams,$listing);
            }

            function scheckUniqueTitle($request)
            {
                $parameters = $request->output_parameters(FALSE,FALSE);
                //error_log("SHAS sremoveFeaturedPanelLogo SERVER RECEIVED ARRAY ".print_r($parameters,true));
                $appID = $parameters['0'];
                $title = $parameters['1']['title'];

                //connect DB
                $dbConfig = array( 'hostname'=>'localhost');
                $this->enterpriseconfig->getDbConfig($appID,$dbConfig);
                $dbHandle  = $this->_loadDatabaseHandle();
                if($dbHandle == ''){
                    log_message('error','can not create db handle');
		}
                $queryCmd = 'select institute_id from institute where institute_name = ?';
                log_message('debug', 'query cmd is ' . $queryCmd);
                //error_log($queryCmd);
                $arrResults = $dbHandle->query($queryCmd, array($title));
                if(count($arrResults->result_array())>0)
                {
                    $instiResponse = array(array('result'=>1,'int'),'struct');
                }else{
                    $instiResponse = array(array('result'=>0,'int'),'struct');
                }
                //error_log("SHAS institute remove Insti Panel Response Array==> ".print_r($instiResponse,TRUE));

                //return $this->xmlrpc->send_response($response);
                return $this->xmlrpc->send_response($instiResponse);
            }
function supdateMainCollegeLink($request)
{
	$parameters = $request->output_parameters(FALSE,FALSE);
	$id = $parameters[0];
	$updateArray = $parameters[1];
	//connect DB
	$dbConfig = array( 'hostname'=>'localhost');
	$this->enterpriseconfig->getDbConfig($appID,$dbConfig);
	$dbHandle  = $this->_loadDatabaseHandle();
	if($dbHandle == ''){
		log_message('error','can not create db handle');
	}
	$update = "";
			$i=0;
			foreach($updateArray as $key=>$val)
			{
				if($i==0)
				{
				$update.=" ".$key."='".$val."'";
				}
				else
				{
					$update.=", ".$key."='".$val."'";
				}
				$i++;
			}
	$queryCmd="update ".$this->enterpriseconfig->pageCollegeTable." set $update where Id=?";
	//error_log($queryCmd);
	$query=$dbHandle->query($queryCmd, array($id));
	$response = array(
			array(
				'result'=>1,
			     ),
			'struct');
	return $this->xmlrpc->send_response($response);
}

function encryptCsv($request) {
    $parameters = $request->output_parameters(FALSE,FALSE);
    $appId=$parameters['0'];
    $csvArr = json_decode($parameters['1']);
    $field = $parameters[2];
    $efield = "encrypted".$field;
    $url = $parameters[3];
    $eurl= $parameters[4];
    $unsuburl= $parameters[5];
    $urlVariables=$this->getVariables($url);
    //connect DB
    $dbConfig = array( 'hostname'=>'localhost');
    $this->mailerconfig->getDbConfig($appId,$dbConfig);
    $dbHandle  = $this->_loadDatabaseHandle();
    if($dbHandle == ''){
        log_message('error','adduser can not create db handle');
    }
   
    $newCsvArray=array();
    $loopcount=0;
    foreach($csvArr as $key=>$value)
    {
        $newCsvArray[$key]=$value;
        if($field!='' && $efield!='')
        {
            if(trim($key)==$field)
            {
                $i=0;
                foreach($value as $newval)
                {
                    $newCsvArray[$efield][$i]=$this->encryptkey($newval,$dbHandle);
                    $i++;
                }
            }
        }
        if($url!='')
        {
            if(count($urlVariables)>0)
            {
                foreach($urlVariables as $variable)
                {
                    if($key==$variable)
                    {

                        $i=0;
                        foreach($value as $newval)
                        {
                            $newCsvArray['URL'][$i]=$this->replace($variable,$newval,$url);
                            $i++;
                        }
                    }
                }
            }
            else
            {
                if($loopcount==0)
                {
                    $i=0;
                    foreach($value as $newval)
                    {
                        $newCsvArray['URL'][$i]=$url;
                        $i++;
                    }
                }

            }
        }
        if($loopcount==0 && $unsuburl!='')
        {
            for($i=0;$i<count($newCsvArray[$key]);$i++)
            {
                $newCsvArray[$unsuburl][$i]="aHR0cHM6Ly93d3cuc2hpa3NoYS5jb20vdXNlcnByb2ZpbGUvZWRpdD91bnNjcj01";
            }

        }

        $loopcount++;
    }
        if($url!='' && $eurl!='')
    {
        for($i=0;$i<count($newCsvArray['URL']);$i++)
        {
            $newCsvArray[$eurl][$i]=base64_encode($newCsvArray['URL'][$i]);
        }
    }
    $newCsvArray=json_encode($newCsvArray);
    $response = array(
            array(
                'result'=>$newCsvArray,
                ),
            'struct');
    return $this->xmlrpc->send_response($response);
}

function encryptkey($value,$dbHandle)
{
   $queryCmd = 'SELECT hex(encode(?,"ShikSha")) as encryptValue';
    error_log("SSSSSSSSS".$queryCmd." ".$value);
    log_message('debug', 'query cmd is ' . $queryCmd);
    $query = $dbHandle->query($queryCmd,array($value));
    $result = '';
    foreach ($query->result() as $row){
        $result=$row->encryptValue;
    }
    return $result;

}
function sendRegistrationQuestionMailer($request)
{
    $parameters   = $request->output_parameters();
    $appId        = $parameters[0];
    $userId       = $parameters[1];
    $displayname  = $parameters[2];
    $userCategory = $parameters[3];
    $duration     = $parameters[4];
    $email        = $parameters[5];
    $this->load->library('alertconfig');
    $this->alertconfig->getDbConfig($appID,$dbConfig);
    $dbHandle     = $this->_loadDatabaseHandle('read','AnA');
	error_log("SSSSSS".$email);
	if(isset($userCategory) && ($userCategory!=''))
	{
		$questionData=$this->getQuestionsforCategory($userCategory,$duration,0,MAILER_NUM_QUESTIONS,MAILER_MIN_QUESTIONS,$dbHandle);
		error_log("SSSSSS".print_r($questionData,true));
	}
	else
	{
		$queryCmd="select messageTable.*, tuser.displayname, (select count(*) from messageTable m where m.threadId=messageTable.threadId and m.parentId=m.threadId and m.fromOthers='user' and status='live') as answerCount  from messageTable , tuser where tuser.userid=messageTable.userId and parentId=0 and fromOthers='user' and status='live' and creationDate> now() - INTERVAL ? day order by creationDate desc limit ".MAILER_NUM_QUESTIONS;
		$query = $dbHandle->query($queryCmd, array($duration));
		$questionData=array();
		$count=0;
		foreach ($query->result() as $row)
		{
			$questionData[$count]['questionId']=$row->threadId;
			$questionData[$count]['questiontext']=$row->msgTxt;	
			$questionData[$count]['askedBy']=$row->displayname;		
			$questionData[$count]['numAnswers']=$row->answerCount;			
			$count++;
		}
		$query->free_result();

	}
	$encryptedEmail=$this->encryptkey($email,$dbHandle);
	for($i=0;$i<count($questionData);$i++)
	{
		$questionData[$i]['questionLink']=$this->generateAutoLoginLink($encryptedEmail,getSeoUrl($questionData[$i]['questionId'],'question',$questionData[$i]['questiontext']),$dbHandle,1);
		$questionData[$i]['AnswerLink']=$this->generateAutoLoginLink($encryptedEmail,getSeoUrl($questionData[$i]['questionId'],'question',$questionData[$i]['questiontext']),$dbHandle,1);
	}
	$dataArr['StartAnsweringLink']=$this->generateAutoLoginLink($encryptedEmail,SHIKSHA_ASK_HOME,$dbHandle,1);
	$dataArr['questionData']=$questionData;
	$dataArr['displayname']=$displayname;
	$count=count($questionData);
/**Special Block
**/
	$this->load->library('message_board_client');
	$msgbrdClient = new Message_board_client();
	if(is_int($userCategory))
	{
 		$categoryId=$userCategory;
	}
	else
	{
		$categoryId=1;
	}
	$popularQArray[$categoryId]=$msgbrdClient->getPopularTopics(1,$categoryId,0,WEEKLY_MAILER_POPULAR_QUESTION_COUNT,1,0,'normal',0);
	for($k=0;$k<count($popularQArray[$categoryId][0]['results']);$k++)
	{
		$popularQArray[$categoryId][0]['results'][$k]['bestAnsArray']=$msgbrdClient->getDataForRelatedQuestions(1,$popularQArray[$categoryId][0]['results'][$k]['threadId'],'normal',0);
	}			
	for($k=0;$k<count($popularQArray[$categoryId][0]['results']);$k++)
	{
		$popularQArray[$categoryId][0]['results'][$k]['questionLink']=$this->generateAutoLoginLink($encryptedEmail,$popularQArray[$categoryId][0]['results'][$k]['url'],$dbHandle,1);
	}
	$dataArr['popularQuestions']=$popularQArray[$categoryId];
//Special Block End
	$html='';
	//$this->load->library('Alerts_client');
	//$alertClient = new Alerts_client();
	$subject = "Become a Shiksha Expert";
	if($count>=MAILER_MIN_QUESTIONS)
	{
		$html=$this->load->view('mailer/New_Reg_mailer',$dataArr,true);
		$response=$this->_externalMassQueueAdd("12",'info@shiksha.com',$email,$subject,$html,$contentType="html");
	}
	$msgArray = array();
	array_push($msgArray,array(
				array(
					'html'=>array($html,'string'),
				     ),'struct'));//close array_push
	$response = array($msgArray,'struct');
	return $this->xmlrpc->send_response($response);

}

function generateAutoLoginLinkS($request)
{
        $parameters = $request->output_parameters();
        $appId=$parameters[0];
        $email=$parameters[1];
        $url=$parameters[2];
        $encrypted=$parameters[3];
        $this->load->library('alertconfig');
        $this->alertconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        $url = $this->generateAutoLoginLink($email,$url,$dbHandle);
        $response = array($url,"string");
        return $this->xmlrpc->send_response($response);
}

function generateAutoLoginLink($email,$url,$dbHandle,$encrypted=0)
{
//	error_log("SSSSSS am here".print_r($dbHandle,true));
	if($encrypted==0)
	{
		$email=$this->encryptkey($email,$dbHandle);
	}
	return SHIKSHA_HOME."/index.php/mailer/Mailer/autoLogin/email~".$email."_url~".base64_encode($url);
}
function getQuestionsforCategory($userCategory,$duration,$start,$end,$minquestions,$dbHandle)
{
	$queryCmd="select messageTable.*, tuser.displayname, (select count(*) from messageTable m where m.threadId=messageTable.threadId and m.parentId=m.threadId and m.fromOthers='user' and status='live') as answerCount from messageTable,messageCategoryTable, tuser where tuser.userid=messageTable.userId and parentId=0 and fromOthers='user' and status='live' and messageCategoryTable.threadId=messageTable.threadId and messageCategoryTable.categoryId=? and creationDate> now() - INTERVAL ? day order by creationDate desc limit $start,$end";
	error_log("SSSSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd, array($userCategory, $duration));
	$questionData=array();
	$count=0;
	foreach ($query->result() as $row)
	{
		$questionData[$count]['questionId']=$row->threadId;
		$questionData[$count]['questiontext']=$row->msgTxt;		
		$questionData[$count]['askedBy']=$row->displayname;		
		$questionData[$count]['numAnswers']=$row->answerCount;		
		$count++;
	}
	$query->free_result();
	if($count<$minquestions)
	{
		if($start==0)
		{
		if($userCategory==1)
		{
			return $questionData;
		}
		$queryCmd="select parentId from categoryBoardTable where boardId=?";
		error_log("SSSSSSS".$queryCmd);
		$query = $dbHandle->query($queryCmd, array($userCategory));
		foreach ($query->result() as $row)
		{
			$categoryId=$row->parentId;
		}
		if($categoryId=='')
		{
			$categoryId=1;
		}
		$query->free_result();
		return $this->getQuestionsforCategory($categoryId,$duration,$start,$end,$minquestions,$dbHandle);
		}
		else
		{
			$restQdata=$this->getQuestionsforCategory($userCategory,$duration,0,($end-$count),($minquestions-$count),$dbHandle);
			foreach($restQdata as $question)
			{
					$questionData[$count]['questionId']=$question['questionId'];
					$questionData[$count]['questiontext']=$question['questiontext'];	
					$questionData[$count]['askedBy']=$row->displayname;		
					$questionData[$count]['numAnswers']=$row->answerCount;				
					$count++;
			}
			return $questionData;
		}
	}
	else
	{
		return $questionData;
	}
}
function generateWeeklyMailer($request)
{
        $this->dbLibObj = DbLibCommon::getInstance('Mailer');
	$markthreadstarttime=time();
	$duration=WEEKLY_MAILER_USER_IGNORE_DURATION;
	$minquestions=MAILER_MIN_QUESTIONS;
	$dbName='weeklyAskMailer';
	$html='';
	static $parentMapping=array();
	static $popularQArray=array();
	$startTime=date ("Y-m-d H:i:s", time()-($duration*24*60*60));
	$endTime=date ("Y-m-d H:i:s", time());
	$this->load->library('alertconfig');
	$this->alertconfig->getDbConfig($appID,$dbConfig);
	$dbHandle  = $this->_loadDatabaseHandle();
	$this->clearUserList($dbHandle,$dbName);	
	$this->insertPostAnAnswerUsers($startTime,$endTime,'ignore',$dbHandle,$dbName);
	$this->insertDigUpUsers($startTime,$endTime,'ignore',$dbHandle,$dbName);
	$this->insertBestAnsUsers($startTime,$endTime,'ignore',$dbHandle,$dbName);
	$endTime=$startTime;
	$startTime='';
	$this->insertPostAnAnswerUsers($startTime,$endTime,'weekly',$dbHandle,$dbName);
	$this->insertDigUpUsers($startTime,$endTime,'weekly',$dbHandle,$dbName);
	$this->insertBestAnsUsers($startTime,$endTime,'weekly',$dbHandle,$dbName);
//	$this->insertUsersWithSubCategory($startTime,$endTime,'weekly',$dbHandle,$dbName);
	$this->insertUsersWithCategory($startTime,$endTime,'weekly',$dbHandle,$dbName);
	$this->insertUsers($startTime,$endTime,'weekly',$dbHandle,$dbName);
	$this->load->library('message_board_client');
	$msgbrdClient = new Message_board_client();
	$dataArr=array();
	$userstart=0;
	$userArray=$this->getUserList($dbHandle,'weekly',$userstart,$dbName);
	while(count($userArray)>0)
	{
		$categoryId=0;
		$counter=0;
		$questionArray=array();	
		$startTime=date ("Y-m-d H:i:s", time()-(WEEKLY_MAILER_QUESTION_POOL_DURATION*24*60*60));
		$endTime=date ("Y-m-d H:i:s", time());
		$html='';
		//$myFile = "/var/www/html/shiksha/mediadata/testFile.html";
		//$fh = fopen($myFile, 'w') or die("can't open file");	
		for($i=0;$i<count($userArray);$i++)
		{
			$email=$userArray[$i]['encyptedEmail'];
			$categoryId=$userArray[$i]['CategoryId'];
			$displayname=$userArray[$i]['displayName'];
			$baseCategoryId = $categoryId;
			if(isset($parentMapping[$categoryId]))
			{
				$categoryId=$parentMapping[$categoryId];
			}
			$questionArray=$this->getQuestionsforCategorywithCache($categoryId,$startTime,$endTime,MAILER_NUM_QUESTIONS,$dbHandle);
			while((count($questionArray)<$minquestions) && ($categoryId!=1))
			{
				//$categoryId=0;
				$queryCmd="select parentId from categoryBoardTable where boardId=?";
				error_log("SSSSSSS".$queryCmd." ".$userArray[$i]['userId']);
				$query = $dbHandle->query($queryCmd, array($categoryId));
				$categoryId=0;
				foreach ($query->result() as $row)
				{
					$categoryId=$row->parentId;
				}
				if($categoryId==0)
				{
					$categoryId=1;
				}
				$query->free_result();
				$parentMapping[$baseCategoryId]=$categoryId;	
				$questionArray=$this->getQuestionsforCategorywithCache($categoryId,$startTime,$endTime,MAILER_NUM_QUESTIONS,$dbHandle);
			}
			if(!isset($popularQArray[$categoryId]))
			{
				$popularQArray[$categoryId]=$msgbrdClient->getPopularTopics(1,$categoryId,0,WEEKLY_MAILER_POPULAR_QUESTION_COUNT,1,0,'normal',0);
				for($k=0;$k<count($popularQArray[$categoryId][0]['results']);$k++)
				{
					$popularQArray[$categoryId][0]['results'][$k]['bestAnsArray']=$msgbrdClient->getDataForRelatedQuestions(1,$popularQArray[$categoryId][0]['results'][$k]['threadId'],'normal',0);
				}			
			}
			for($k=0;$k<count($popularQArray[$categoryId][0]['results']);$k++)
			{
				$popularQArray[$categoryId][0]['results'][$k]['questionLink']=$this->generateAutoLoginLink($email,$popularQArray[$categoryId][0]['results'][$k]['url'],$dbHandle,1);
			}
			$dataArr['popularQuestions']=$popularQArray[$categoryId];
			for($j=0;$j<count($questionArray);$j++)
			{
				$questionArray[$j]['questionLink']=$this->generateAutoLoginLink($email,getSeoUrl($questionArray[$j]['questionId'],'question',$questionArray[$j]['questiontext']),$dbHandle,1);
				$questionArray[$j]['AnswerLink']=$this->generateAutoLoginLink($email,getSeoUrl($questionArray[$j]['questionId'],'question',$questionArray[$j]['questiontext']),$dbHandle,1);
			}
			$dataArr['StartAnsweringLink']=$this->generateAutoLoginLink($email,SHIKSHA_ASK_HOME,$dbHandle,1);
			$dataArr['questionData']=$questionArray;
			$dataArr['displayname']=$displayname;
			//fwrite($fh,$html);
			$count=count($questionArray);	
			$this->load->library('Alerts_client');
			$alertClient = new Alerts_client();
			$subject = "Top ".WEEKLY_MAILER_POPULAR_QUESTION_COUNT." Questions of the Week";
			if($count>=MAILER_MIN_QUESTIONS)
			{
				$html=$this->load->view('mailer/WeeklyMailer',$dataArr,true);
				$response=$alertClient->externalQueueAdd("12",'info@shiksha.com',$userArray[$i]['email'],$subject,$html,$contentType="html");
			}	


		}
		$userstart+=MAILER_USER_CACHE;
		$userArray=$this->getUserList($dbHandle,'weekly',$userstart,$dbName);
	}
	//fclose($fh);
	$msgArray = array();
	array_push($msgArray,array(
				array(
					'html'=>array($html,'string')
				     ),'struct'));//close array_push
	$response = array($msgArray,'struct');
	unset($popularQArray);
	$markthreadendtime=time();
	echo ("SSSSS Weekly AskMailer Time Taken ".($markthreadendtime-$markthreadstarttime)." seconds");
	return $this->xmlrpc->send_response($response);
}

function getQuestionsforCategorywithCache($categoryId,$startTime,$endTime,$numQuestion,$dbHandle)
{
	static $categoryCounter=array();
	static $questionCache=array();
	static $questionCount=array();
	static $cacheStart=array();
	$questionArray=array();
	if(!isset($categoryCounter[$categoryId]))
	{
		$categoryCounter[$categoryId]=0;
	}	
	else
	{
		$categoryCounter[$categoryId]=$categoryCounter[$categoryId]+1;
	}
	if(!isset($questionCount[$categoryId]))
	{
		$questionCount[$categoryId]=$this->getQuestionCountForCategory($categoryId,$startTime,$endTime,$dbHandle);
	}
	if(!isset($cacheStart[$categoryId]))
	{
		$cacheStart[$categoryId]=0;
	}
	if(!isset($questionCache[$categoryId]))
	{
		if(count($questionCache)==MAILER_CATEGORY_CACHE)
		{
			$key=max(array_keys($questionCache));
			unset($questionCache[$key]);		
		}
		$questionCache[$categoryId]=$this->getQuestionCacheForCategory($categoryId,$startTime,$endTime,$cacheStart[$categoryId],PER_CATEGORY_QUESTION_CACHE,$dbHandle);	
	}
	if($questionCount[$categoryId]!=0)
	{
		$countstart=$categoryCounter[$categoryId]%$questionCount[$categoryId];
		if(floor($countstart/PER_CATEGORY_QUESTION_CACHE)<=floor($cacheStart[$categoryId]/PER_CATEGORY_QUESTION_CACHE))
		{
			for($i=0;$i<$numQuestion;$i++)
			{
				if($i<$questionCount[$categoryId])
				{
					if(floor(($i+$countstart)/PER_CATEGORY_QUESTION_CACHE)==floor($cacheStart[$categoryId]/PER_CATEGORY_QUESTION_CACHE))
					{
						$questionArray[$i]=$questionCache[$categoryId][(($i+$countstart)%$questionCount[$categoryId])%PER_CATEGORY_QUESTION_CACHE];
					}
					else 
					{
						$oldCacheStart=$countstart[$categoryId];
						$cacheStart[$categoryId]=$cacheStart[$categoryId]+PER_CATEGORY_QUESTION_CACHE*(floor(($countstart+$i)/PER_CATEGORY_QUESTION_CACHE)-floor($cacheStart[$categoryId]/PER_CATEGORY_QUESTION_CACHE));
						if($cacheStart[$categoryId]!=$oldCacheStart)
						{
							$questionCache[$categoryId]=$this->getQuestionCacheForCategory($categoryId,$startTime,$endTime,$cacheStart[$categoryId],PER_CATEGORY_QUESTION_CACHE,$dbHandle);
						}
						$questionArray[$i]=$questionCache[$categoryId][(($i+$countstart)%$questionCount[$categoryId])%PER_CATEGORY_QUESTION_CACHE];
					}
				}
			}
		}
		else
		{
			$cacheStart[$categoryId]=$cacheStart[$categoryId]+PER_CATEGORY_QUESTION_CACHE;
			$questionCache[$categoryId]=$this->getQuestionCacheForCategory($categoryId,$startTime,$endTime,$cacheStart[$categoryId],PER_CATEGORY_QUESTION_CACHE,$dbHandle);
			$questionArray=$this->getQuestionsforCategorywithCache($categoryId,$startTime,$endTime,$numQuestion,$dbHandle);
		}
	}
	return $questionArray;
}

function getQuestionCountForCategory($categoryId,$startTime,$endTime,$dbHandle)
{
	$queryCmd="select count(*) as count from messageTable,messageCategoryTable where parentId=0 and fromOthers='user' and status='live' and messageCategoryTable.threadId=messageTable.threadId and messageCategoryTable.categoryId=? and creationDate>? and creationDate<?";	
	error_log("SSSSSSS".$queryCmd);
	$count=0;
	$query = $dbHandle->query($queryCmd, array($categoryId, $startTime, $endTime));
	foreach ($query->result() as $row)
	{
		$count=$row->count;
	}
	$query->free_result();
	return $count;
}

function getQuestionCacheForCategory($categoryId,$startTime,$endTime,$startNum,$count,$dbHandle)
{
	$queryCmd="select messageTable.*, tuser.displayname, (select count(*) from messageTable m where m.threadId=messageTable.threadId and m.parentId=m.threadId and m.fromOthers='user' and status='live') as answerCount from messageTable,messageCategoryTable, tuser where tuser.userid=messageTable.userId and parentId=0 and fromOthers='user' and status='live' and messageCategoryTable.threadId=messageTable.threadId and messageCategoryTable.categoryId=? and creationDate>? and creationDate<? order by creationDate desc limit $startNum, $count";
	error_log("SSSSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd, array($categoryId, $startTime, $endTime));
	$questionData=array();
	$counter=0;
	foreach ($query->result() as $row)
	{
		$questionData[$counter]['questionId']=$row->threadId;
		$questionData[$counter]['questiontext']=$row->msgTxt;		
		$questionData[$counter]['askedBy']=$row->displayname;		
		$questionData[$counter]['numAnswers']=$row->answerCount;				
		$counter++;
	}	
	$query->free_result();
	return $questionData;
}
function clearUserList($dbHandle,$dbName)
{
	$queryCmd="delete from $dbName";
	error_log("SSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd);
	return 1;
}

function getUserList($dbHandle, $mailType,$start,$dbName)
{
	$queryCmd="select * from $dbName where mailType=? order by userCategory limit ".$start.", ".MAILER_USER_CACHE;
//	$queryCmd="select * from $dbName where mailType='$mailType' order by userCategory limit $start,3";
	error_log("SSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd, array($mailType));
	$count=0;
	$userData=array();
	foreach ($query->result() as $row)
	{
		$userData[$count]['userId']=$row->userId;
		$userData[$count]['encyptedEmail']=$row->encyptedEmail;
		$userData[$count]['email']=$row->email;
		$userData[$count]['displayName']=$row->displayName;
		$userData[$count]['CategoryId']=$row->userCategory;		
		$userData[$count]['action']=$row->action;		
		$count++;
	}
	$query->free_result();
	return $userData;
}

function insertPostAnAnswerUsers($startTime,$endTime,$mailType, $dbHandle, $dbName)
{	
	$STARTSTR='';
	$ENDSTR='';
	if($startTime!='')
	{
		$STARTSTR=" and creationDate>".$dbHandle->escape($startTime)." ";
	}
	if($endTime!='')
	{
		$ENDSTR=" and creationDate<".$dbHandle->escape($endTime)." ";
	}
	$queryCmd="select b.userId,b.email,b.encyptedEmail,b.displayName,max(a.categoryId) as userCategory, b.action,b.mailType from messageCategoryTable a inner join (select mt.threadId,mt.userId,tuser.email,hex(encode(tuser.email,'ShikSha')) as encyptedEmail,tuser.displayname as displayName,max(mt.creationDate) as mxdate,'posting an answer' as action,'$mailType' as mailType from messageTable mt,tuser where threadId=parentId and fromOthers='user' and status='live' and tuser.userId=mt.userId and newsletteremail=1 $STARTSTR $ENDSTR  group by mt.userId)b where a.threadId = b.threadId group by b.userId";
#	$queryCmd="select mt.userId,tuser.email,hex(encode(tuser.email,'ShikSha')) as encyptedEmail,tuser.displayname as displayName, (select max(categoryId) from messageTable m, messageCategoryTable where messageCategoryTable.threadId=m.threadId and m.creationDate=max(mt.creationDate)) as userCategory,'posting an answer' as action,'$mailType' as mailType from messageTable mt,tuser where threadId=parentId and fromOthers='user' and status='live' and tuser.userId=mt.userId and newsletteremail=1 $STARTSTR $ENDSTR group by mt.userId";
	error_log("SSSSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd);
	foreach($query->result_array() as $row)
	{
		$queryCmd=$dbHandle->insert_string($dbName,$row);
		$query1 = $dbHandle->query($queryCmd." on duplicate key update userId=userId");
//		error_log("SSSSSSS".$queryCmd);
	}
	return 1;
}
function insertDigUpUsers($startTime,$endTime,$mailType,$dbHandle,$dbName)
{
		$STARTSTR='';
	$ENDSTR='';
	if($startTime!='')
	{
		$STARTSTR=" and digTime>".$dbHandle->escape($startTime)." ";
	}
	if($endTime!='')
	{
		$ENDSTR=" and digTime<".$dbHandle->escape($endTime)." ";
	}
	$queryCmd="select d.userId,tuser.email,hex(encode(tuser.email,'ShikSha')) as encyptedEmail,tuser.displayname as displayName,(select max(categoryId) from messageCategoryTable, messageTable, digUpUserMap du where  messageTable.msgId=du.productId and du.digUpStatus='live' and messageTable.threadId=messageCategoryTable.threadId and du.digTime=max(d.digTime)) as userCategory,'rating an answer' as action, '$mailType' as mailType  from digUpUserMap d,  tuser where tuser.userId=d.userId and newsletteremail=1 and product='qna' and digUpStatus='live' $STARTSTR $ENDSTR group by d.userId";
	error_log("SSSSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd);
	foreach($query->result_array() as $row)
	{
		$queryCmd=$dbHandle->insert_string($dbName,$row);
		$query1 = $dbHandle->query($queryCmd." on duplicate key update userId=userId");
//		error_log("SSSSSSS".$queryCmd);
	}
///	error_log("SSSSS".$queryCmd);
        return 1;
}
function insertBestAnsUsers($startTime,$endTime,$mailType,$dbHandle,$dbName)
{
	$STARTSTR='';
	$ENDSTR='';
	if($startTime!='')
	{
		$STARTSTR=" and creation_time>".$dbHandle->escape($startTime)." ";
	}
	if($endTime!='')
	{
		$ENDSTR=" and creation_time<".$dbHandle->escape($endTime)." ";
	}
	$queryCmd="select messageTable.userId,tuser.email,hex(encode(tuser.email,'ShikSha')) as encyptedEmail,tuser.displayname as displayName, (select max(categoryId) from messageCategoryTable, messageTableBestAnsMap BAM where BAM.threadId=messageCategoryTable.threadId and BAM.creation_time=max(b.creation_time)) as userCategory,'rating an answer' as action , '$mailType' as mailType   from  messageTable, messageTableBestAnsMap b, tuser where tuser.userId= messageTable.userId and newsletteremail=1 and messageTable.threadId = b.threadId and messageTable.parentId = 0 $STARTSTR $ENDSTR group by messageTable.userId";
	error_log("SSSSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd);
	foreach($query->result_array() as $row)
	{
		$queryCmd=$dbHandle->insert_string($dbName,$row);
		$query1 = $dbHandle->query($queryCmd." on duplicate key update userId=userId");
///		error_log("SSSSSSS".$queryCmd);
	}
	
//	error_log("SSSSS".$queryCmd);
        return 1;
}
/*function insertUsersWithSubCategory($startTime,$endTime,$mailType,$dbHandle,$dbName)
{
			$STARTSTR='';
	$ENDSTR='';
	if($startTime!='')
	{
		$STARTSTR=" and tu.usercreationDate>'$startTime' ";
	}
	if($endTime!='')
	{
		$ENDSTR=" and tu.usercreationDate<'$endTime' ";
	}
	
	$queryCmd="select distinct tu.userid as userId,tu.email,hex(encode(tu.email,'ShikSha')) as encyptedEmail,tu.displayname as displayName, keyValue as userCategory,'' as action,'$mailType' as mailType  from tuser tu, tuserInterest where tu.userid = tuserInterest.userId and tuserInterest.keyofInterest ='subCategory' and tu.newsletteremail=1 $STARTSTR $ENDSTR";
	error_log("SSSSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd);
	foreach($query->result_array() as $row)
	{
		$queryCmd=$dbHandle->insert_string($dbName,$row);
		$query1 = $dbHandle->query($queryCmd." on duplicate key update userId=userId");
	//	error_log("SSSSSSS".$queryCmd);
	}
//	error_log("SSSSS".$queryCmd);
        return 1;

}*/
function insertUsersWithCategory($startTime,$endTime,$mailType,$dbHandle,$dbName)
{
			$STARTSTR='';
	$ENDSTR='';
	if($startTime!='')
	{
		$STARTSTR=" and tv.usercreationDate>".$dbHandle->escape($startTime)." ";
	}
	if($endTime!='')
	{
		$ENDSTR=" and tv.usercreationDate<".$dbHandle->escape($endTime)." ";
	}
	$count=0;
	do
	{
	$prevcount=$count;
	$queryCmd="select distinct tv.userid as userId,tv.email,hex(encode(tv.email,'ShikSha')) as encyptedEmail,tv.displayname as displayName, CategoryId as userCategory, '' as action,'$mailType' as mailType  from tuser tv, tUserPref, tCourseSpecializationMapping where tv.userid = tUserPref.UserId and tUserPref.DesiredCourse=tCourseSpecializationMapping.SpecializationId and tv.newsletteremail=1 $STARTSTR $ENDSTRi limit $count,10000";
	error_log("SSSSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd);
	foreach($query->result_array() as $row)
	{
		$queryCmd=$dbHandle->insert_string($dbName,$row);
		$query1 = $dbHandle->query($queryCmd." on duplicate key update userId=userId");
		$count++;
//		error_log("SSSSSSS".$queryCmd);
	}
	}
	while ($count==$prevcount+10000);

//	error_log("SSSSS".$queryCmd);
        
//	error_log("SSSSS".$queryCmd);
        return 1;

}
function insertUsers($startTime,$endTime,$mailType,$dbHandle,$dbName)
{
			$STARTSTR='';
	$ENDSTR='';
	if($startTime!='')
	{
		$STARTSTR=" and usercreationDate>".$dbHandle->escape($startTime)." ";
	}
	if($endTime!='')
	{
		$ENDSTR=" and usercreationDate<".$dbHandle->escape($endTime)." ";
	}
	$count=0;
	do{
	$prevcount=$count;
	
	$queryCmd="select distinct userid as userId,tuser.email,hex(encode(tuser.email,'ShikSha')) as encyptedEmail,tuser.displayname as displayName, '1' as userCategory,'' as action,'$mailType' as mailType  from tuser where tuser.newsletteremail=1 $STARTSTR $ENDSTR limit $count,10000";
//	error_log("SSSSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd);
	foreach($query->result_array() as $row)
	{
		$queryCmd=$dbHandle->insert_string($dbName,$row);
		$query1 = $dbHandle->query($queryCmd." on duplicate key update userId=userId");
//		error_log("SSSSSSS".$queryCmd);
	}
	}
	while ($count==$prevcount+10000);
	
//	error_log("SSSSS".$queryCmd);
        return 1;

}

function generateBestAnswerMailer($request)
{
        $this->dbLibObj = DbLibCommon::getInstance('AnA');
	$markthreadstarttime=time();
	$this->load->library('alertconfig');
	$this->alertconfig->getDbConfig($appID,$dbConfig);
	$dbHandle  = $this->_loadDatabaseHandle('write');

	$this->addBestAnswerUsers($dbHandle);
	$queryCmd="select * from bestAnsMailer where nummailsent<".BEST_ANSWER_MAIL_COUNT;
	error_log("SSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd);
	foreach ($query->result_array() as $row){
		$dataArr=array();
		$queryCmd1="select messageTable.*, displayname, (select level from userPointLevel, userPointSystem where userPointSystem.userId=messageTable.userid and userPointSystem.userPointValue > minLimit order by minLimit desc limit 1) as userLevel from messageTable, tuser where tuser.userid=messageTable.userId and fromOthers='user' and status!='deleted' and (parentId=0 or parentId=threadId) and threadId=?";	
		error_log("SSSSS".$queryCmd1);
		$query1 = $dbHandle->query($queryCmd1, array($row['threadId']));
		$numAnswers=($query1->num_rows() -1);
		$dataArr['numAnswer']=$numAnswers;
		$dataArr['displayName']=$row['displayName'];
		foreach ($query1->result_array() as $row1){
			if($row1['parentId']==0)
			{
				$dataArr['questiontext']=$row1['msgTxt'];
				$dataArr['questionId']=$row1['threadId'];
				$dataArr['questionLink']=$this->generateAutoLoginLink($row['encyptedEmail'],getSeoUrl($row1['threadId'],'question',$row1['msgTxt']),$dbHandle,1);
			}
			else
			{
				$dataArr['answerText'][]=$row1['msgTxt'];
				$dataArr['answerBy'][]=$row1['displayname'];
				$dataArr['userLevel'][]=$row1['userLevel'];
				$dataArr['answerId'][]=$row1['msgId'];
				$dataArr['digUp'][]=$row1['digUp'];
				$dataArr['digDown'][]=$row1['digDown'];
				$dataArr['bestAnswerLink'][]=$this->generateAutoLoginLink($row['encyptedEmail'],getSeoUrl($row1['threadId'],'question',$row1['msgTxt'])."/mailer/bestAnswerSelectPopUp/".base64_encode("answerId~".$row1['msgId']),$dbHandle,1);
			}
		}
		$query1->free_result();
		$html=$this->load->view('mailer/BestAnswerMailer',$dataArr,true);
		$this->load->library('Alerts_client');
		$alertClient = new Alerts_client();
		$subject = $row['displayName'].", Judge the best answer for your question on Shiksha.com";
                $response=$alertClient->externalQueueAdd("12",'info@shiksha.com',$row['email'],$subject,$html,$contentType="html");
		$queryCmd2="update bestAnsMailer set nummailsent=nummailsent+1 where threadId=?";
		error_log("SSSSS".$queryCmd2);
		$query2 = $dbHandle->query($queryCmd2, array($row['threadId']));	
	}
		$msgArray = array();
	array_push($msgArray,array(
				array(
					'html'=>array($html,'string')
				     ),'struct'));//close array_push
	$response = array($msgArray,'struct');
	$markthreadendtime=time();
	echo ("SSSSS Best Answer Mailer TIme Taken".($markthreadendtime-$markthreadstarttime)." seconds");
	return $this->xmlrpc->send_response($response);

}
function addBestAnswerUsers($dbHandle)
{
	$queryCmd="insert into bestAnsMailer (select tuser.userId, tuser.email, hex(encode(tuser.email,'ShikSha')), displayname, threadId,0 from (select count(*) as count, threadId, userId from messageTable where messageTable.fromOthers ='user' and messageTable.status not in ('deleted') and (parentId=0 or parentId=threadId) and threadId not in (select distinct threadId from messageTableBestAnsMap) group by threadId having count>5) Res, tuser where tuser.userId=Res.userId and tuser.newsletteremail=1) on duplicate key update threadId=Res.threadId";
	error_log("SSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd);
	$queryCmd="delete from bestAnsMailer where threadId in (select distinct threadId from messageTableBestAnsMap)";
	error_log("SSSSS".$queryCmd);
	$query = $dbHandle->query($queryCmd);
        return 1;
}

function generateDailyMailer($request)
{
        $this->dbLibObj = DbLibCommon::getInstance('AnA');
	$markthreadstarttime=time();
	$minquestions=MAILER_MIN_QUESTIONS;
	$dbName='dailyAskMailer';
	$html='';
	static $parentMapping=array();
	$startTime=date ("Y-m-d", time()-(DAILY_MAILER_USER_SET_START_TIME*24*60*60));
	$endTime=date ("Y-m-d", time()-(DAILY_MAILER_USER_SET_END_TIME*24*60*60));
	error_log("SSSS".date("Y-m-d H:i:s",time())." ".date("Y-m-d H:i:s",(floor(floor(time()/60)/60))*60*60));
	$this->load->library('alertconfig');
	$this->alertconfig->getDbConfig($appID,$dbConfig);
	$dbHandle  = $this->_loadDatabaseHandle('write');
	$this->clearUserList($dbHandle,$dbName);	
	$this->insertPostAnAnswerUsers($startTime,$endTime,'daily',$dbHandle,$dbName);	
	$this->insertDigUpUsers($startTime,$endTime,'daily',$dbHandle,$dbName);
	$this->insertBestAnsUsers($startTime,$endTime,'daily',$dbHandle,$dbName);
	$this->load->library('message_board_client');
	$msgbrdClient = new Message_board_client();
	$dataArr=array();
	$userstart=0;
	$userArray=$this->getUserList($dbHandle,'daily',$userstart,$dbName);
	while(count($userArray)>0)
	{
		$categoryId=0;
		$counter=0;
		$questionArray=array();	
		$startTime=date ("Y-m-d H:i:s", time()-(DAILY_MAILER_QUESTION_POOL_DURATION*24*60*60));
		$endTime=date ("Y-m-d H:i:s", time());
		$html='';
		//$myFile = "/var/www/html/shiksha/mediadata/testFile.html";
		//$fh = fopen($myFile, 'w') or die("can't open file");	
		for($i=0;$i<count($userArray);$i++)
		{
			$categoryId=$userArray[$i]['CategoryId'];
			$email=$userArray[$i]['encyptedEmail'];
			$displayname=$userArray[$i]['displayName'];
			$action=$userArray[$i]['action'];
			$baseCategoryId = $categoryId;
			if(isset($parentMapping[$categoryId]))
			{
				$categoryId=$parentMapping[$categoryId];
			}
			$questionArray=$this->getQuestionsforCategorywithCache($categoryId,$startTime,$endTime,MAILER_NUM_QUESTIONS,$dbHandle);
			while((count($questionArray)<$minquestions) && ($categoryId!=1))
			{
				$queryCmd="select parentId from categoryBoardTable where boardId=?";
				error_log("SSSSSSS".$queryCmd." ".$userArray[$i]['userId']);
				$query = $dbHandle->query($queryCmd, array($categoryId));
				$categoryId=0;
				foreach ($query->result() as $row)
				{
					$categoryId=$row->parentId;
				}
				if($categoryId==0)
				{
					$categoryId=1;
				}
				$parentMapping[$baseCategoryId]=$categoryId;	
				$questionArray=$this->getQuestionsforCategorywithCache($categoryId,$startTime,$endTime,MAILER_NUM_QUESTIONS,$dbHandle);
			}
			for($j=0;$j<count($questionArray);$j++)
			{
				$questionArray[$j]['questionLink']=$this->generateAutoLoginLink($email,getSeoUrl($questionArray[$j]['questionId'],'question',$questionArray[$j]['questiontext']),$dbHandle,1);
				$questionArray[$j]['AnswerLink']=$this->generateAutoLoginLink($email,getSeoUrl($questionArray[$j]['questionId'],'question',$questionArray[$j]['questiontext']),$dbHandle,1);
			}
			$dataArr['StartAnsweringLink']=$this->generateAutoLoginLink($email,SHIKSHA_ASK_HOME,$dbHandle,1);
			$dataArr['questionData']=$questionArray;
			$dataArr['displayname']=$displayname;
			$dataArr['action']=$action;
			$html=$this->load->view('mailer/DailyMailer',$dataArr,true);
			//fwrite($fh,$html);
			$count=count($questionArray);	
			$this->load->library('Alerts_client');
			$alertClient = new Alerts_client();
			$subject = "Your recent activity on Shiksha.com Ask & Answers";
			if($count>=MAILER_MIN_QUESTIONS)
			{
				$html=$this->load->view('mailer/DailyMailer',$dataArr,true);
				$response=$alertClient->externalQueueAdd("12",'info@shiksha.com',$userArray[$i]['email'],$subject,$html,$contentType="html");
			}	


		}
		$userstart+=MAILER_USER_CACHE;
		$userArray=$this->getUserList($dbHandle,'daily',$userstart,$dbName);
	}
	$msgArray = array();
	array_push($msgArray,array(
				array(
					'html'=>array($html,'string')
				     ),'struct'));//close array_push
	$response = array($msgArray,'struct');
	$markthreadendtime=time();
	echo ("SSSSS Daily Ask Mailer Time Taken ".($markthreadendtime-$markthreadstarttime)." seconds");	
	//return $this->xmlrpc->send_response($response);
	return 1;
}

/**
* getListingAnADailyMailer to fire the mail to institutes for number of questions posted to them.
*/

function fireListingAnADailyMailer($appID=1,$forEnterpriseUserListings=1){
	$this->validateCron();
        $this->dbLibObj = DbLibCommon::getInstance('AnA');
	if($forEnterpriseUserListings == 1){
		echo "cron to send mails for questions for enterprise users start at.\n".date("Y-m-d H:i:s")."\n\n\n";
	}else{
		echo "cron to send mails for questions for non-enterprise users start at.\n".date("Y-m-d H:i:s")."\n\n\n";
	}	
	$this->load->library('messageboardconfig');
	//connect DB
	$dbConfig = array( 'hostname'=>'localhost');
	$this->messageboardconfig->getDbConfig($appID,$dbConfig);
	$dbHandle  = $this->_loadDatabaseHandle();
	if($dbHandle == ''){
		error_log_shiksha('getListingAnADailyMailer can not create db handle','qna');
	}
	/*and lm.isApproved = 'yes' and lm.moderation_flag = 'moderated'*/
	$startTime=date ("Y-m-d", time()-(DAILY_MAILER_USER_SET_START_TIME*24*60*60));
	$endTime=date ("Y-m-d", time()-(DAILY_MAILER_USER_SET_END_TIME*24*60*60));
	if($forEnterpriseUserListings == 1){
		$mailType="listingAnADailyMailer";
		$userGroupCondition = "t.userGroup = 'enterprise' and ";
	}else{
		$mailType="listingAnADailyMailerForNonEnterpriseUsers";
		$userGroupCondition = "t.userGroup != 'enterprise' and ";
	}
	$queryCmd = "select m1.*,lm.listing_type_id,lm.listing_title,lm.listing_type,lcd.*,t.email,t.displayname,t.textpassword from messageTable m1,listings_main lm,listing_contact_details lcd,tuser t  where $userGroupCondition m1.listingTypeId != 0 and m1.listingTypeId = lm.listing_type_id and m1.listingType = lm.listing_type and m1.listingType = 'institute' and lm.status in ('live') and lcd.listing_type_id = lm.listing_type_id and lcd.listing_type = lm.listing_type and lm.username = t.userid and m1.status in ('live') and lcd.status = 'live' and m1.fromOthers='user' and m1.parentId = 0 and creationDate >= ? and creationDate  <= ? order by lm.listing_type_id";
	
	$Result = $dbHandle->query($queryCmd, array($startTime, $endTime));
	$msgArray = array();
	foreach ($Result->result_array() as $row){
		if(!isset($msgArray[$row['listingTypeId']])){
			$msgArray[$row['listingTypeId']] = array();
		}
		$row['url'] = getSeoUrl($row['threadId'],'question',$row['msgTxt']);
		array_push($msgArray[$row['listingTypeId']],$row);
	}
	//$this->load->library(array('Alerts_client'));
	//$alertClient = new Alerts_client();
	foreach($msgArray as $tempRec){
		$noOfQuestions = count($tempRec);
		$urlToBeSentInMail = SHIKSHA_HOME_URL;
		if($forEnterpriseUserListings == 1){
			$toEmail= $tempRec[0]['email'];
			$urlOfLandingPage= SHIKSHA_HOME_URL.'/enterprise/Enterprise/enterpriseUserQuestions/36';
			$urlToBeSentInMail = $this->generateAutoLoginLink($toEmail,$urlOfLandingPage,$dbHandle);
		}
		$dataArr = array('type' => $mailType,'records' => $tempRec,'noOfQuestions' => $noOfQuestions,'urlToBeSentInMail' => $urlToBeSentInMail);
		$html=$this->load->view('search/searchMail',$dataArr,true);
		if($noOfQuestions > 1){	
			$subjectLine = $noOfQuestions." new questions about your institute on Shiksha";
		}else{
			$subjectLine = $noOfQuestions." new question about your institute on Shiksha";
		}
		$dataArr['mail_subject'] = $subjectLine;
		if(is_array($tempRec[0]) && ($tempRec[0]['email'] != "") && ($forEnterpriseUserListings == 1)){
			//$response=$alertClient->externalQueueAdd('12','no-reply@shiksha.com',$tempRec[0]['email'],$subjectLine,$html,'html',time(),'n',array());
			Modules::run('systemMailer/SystemMailer/dailyMailerToEnterpriseUser', $tempRec[0]['email'], $dataArr);
		}
		if(is_array($tempRec[0]) && ($tempRec[0]['contact_email'] != "") && ($tempRec[0]['contact_email'] != $tempRec[0]['email']) && ($forEnterpriseUserListings == 0)){
			//$response=$alertClient->externalQueueAdd('12','no-reply@shiksha.com',$tempRec[0]['contact_email'],$subjectLine,$html,'html',time(),'n',array());
			Modules::run('systemMailer/SystemMailer/dailyMailerToNonEnterpriseUser', $tempRec[0]['email'], $dataArr);
		}
	}
	if($forEnterpriseUserListings == 1){
		echo "cron to send mails for questions for enterprise users ends at.\n".date("Y-m-d H:i:s")."\n\n\n";
	}else{
		echo "cron to send mails for questions for non-enterprise users ends at.\n".date("Y-m-d H:i:s")."\n\n\n";
	}
	return true;
}


/**
* This controller is used only in case of MMM 
* The controller that takes in the email to be sent. The controller simply takes all the input elements and stores it in the database. The table contains all mails, and the mails to be sent have isSent = "unsent". The emails may also be scheduled for a later data using the sendTime parameter.
* Input : fromAddress, toAddress, subject, content , contentType(html/text), sendTime
* Output : A message stating that the mail has been inserted properly.
*/
function externalMassQueueAdd($request) 
{ 
        $parameters = $request->output_parameters(FALSE,FALSE); 
        $appId = $parameters['0']; 
        $fromAddress = $parameters['1']; 
        $toAddress = $parameters['2']; 
        $subject = $parameters['3']; 
        $content = $parameters['4']; 
        $contentType = $parameters['5']; 
        $sendTime = $parameters['6']; 
        $attachment = $parameters['7']; 
        $attachmentArray = $parameters['8'];
		$ccEmail = $parameters['9'];
		$bccEmail = $parameters['10'];
    	$message = $this->_externalMassQueueAdd($appId, $fromAddress, $toAddress, $subject,$content, $contentType, $sendTime,$attachment,$attachmentArray,$ccEmail,$bccEmail);
	$response=array($message,'string'); 
        return $this->xmlrpc->send_response($response); 
}

/**
* This controller is used only in case of MMM 
* The controller that takes in the email to be sent. The controller simply takes all the input elements and stores it in the database. The table contains all mails, and the mails to be sent have isSent = "unsent". The emails may also be scheduled for a later data using the sendTime parameter.
* Input : fromAddress, toAddress, subject, content , contentType(html/text), sendTime
* Output : A message stating that the mail has been inserted properly.
*/
function externalMassQueueAddImmediate($request) 
{ 
    error_log("Gearman");
        $parameters = $request->output_parameters(FALSE,FALSE); 
        $appId = $parameters['0']; 
        $fromAddress = $parameters['1']; 
        $toAddress = $parameters['2']; 
        $subject = $parameters['3']; 
        $content = $parameters['4']; 
        $contentType = $parameters['5']; 
        $sendTime = $parameters['6']; 
        $attachment = $parameters['7']; 
        $attachmentArray = $parameters['8'];
        $ccEmail = $parameters['9'];
        $bccEmail = $parameters['10'];
    	error_log("Gearman: Before adding job");
        $mailerId = $this->_externalMassQueueAdd($appId, $fromAddress, $toAddress, $subject,$content, $contentType, $sendTime,$attachment,$attachmentArray,$ccEmail,$bccEmail);
        $client= new GearmanClient();
        $client->addServer();
        $task1 = $client->addTask("test", $mailerId);
        error_log("Gearman: After adding job");
        $result = $client->runTasks();
        return $this->xmlrpc->send_response($response); 
}

/**
* The controller update the email address status of all the users who have been sent an email over the noOfDays duration 
* The controller is called as a cron 
* the function takes in the number of days as a parameter and updates the tuserflag table for the hardbouce and soft bounce verfied column.
* the email (hard/soft bounce) is stored in tuserflag table, which is updated by this function
* Input: Number of days
* Output: void 
*/
function createMassAttachment($request)
{
    $parameters = $request->output_parameters(FALSE,FALSE);
    $appId=$parameters['0'];
    $type_id=$parameters['1'];
    $type=$parameters['2'];
    $attachmentType=$parameters['3'];
    $attachmentContent=$parameters['4'];
    $attachment_name=$parameters['5'];
    $attachment_file_type=$parameters['6'];

    $this->mailerconfig->getDbConfig($appId,$dbConfig);	
    $dbConfig['database'] = 'mailer';
    $dbHandle  = $this->_loadDatabaseHandle();
    $data=array('listing_type_id'=>$type_id,'listing_type'=>$type,'attachment_file_type'=>$attachment_file_type,'attachment_content'=>$attachmentContent,'document_type'=>$attachmentType,'name'=>$attachment_name);
    $queryCmd = $dbHandle->insert_string('attachmentTable',$data);
    $queryCmd = $queryCmd." on duplicate key update attachment_content = '".mysql_escape_string($attachmentContent)."' , attachment_file_type = '".mysql_escape_string($attachment_file_type)."' , name = '".mysql_escape_string($attachment_name)."';";
    error_log('Query for Alert addAttachment is : '.$queryCmd);
    log_message('error',$queryCmd);
    //$result = $dbHandle->query($queryCmd, array($attachmentContent,$attachment_file_type,$attachment_name));
    $result = $dbHandle->query($queryCmd);
    //error_log("Query for Alert addAttachment : " .$result);

    $message="inserted successfully";
    $response=array($message,'string');
    return $this->xmlrpc->send_response($response);
}

/**
* The controller update the email address status of all the users who have been sent an email over the noOfDays duration 
* The controller is called as a cron 
* the function takes in the number of days as a parameter and updates the tuserflag table for the hardbouce and soft bounce verfied column.
* the email (hard/soft bounce) is stored in tuserflag table, which is updated by this function
* Input: Number of days
* Output: void 
*/
function getMassAttachmentId($request)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $type_id=$parameters['1'];
    $type=$parameters['2'];
    $document_type=$parameters['3'];
    $attachment_name=$parameters['4'];

    $this->mailerconfig->getDbConfig($appId,$dbConfig);	
    $dbConfig['database'] = 'mailer';
    $dbHandle  = $this->_loadDatabaseHandle();
    $data=array('listing_type_id'=>$type_id,'listing_type'=>$type,'attachment_file_type'=>$attachment_file_type,'attachment_content'=>$attachmentContent,'document_type'=>$attachmentType,'name'=>$attachment_name);
    if($attachment_name==''){
      $queryCmd = "select id  from attachmentTable where listing_type_id=? and listing_type=? and document_type=?;";
      $query=$dbHandle->query($queryCmd, array($type_id,$type,$document_type));
    }
    else{
      $queryCmd = "select id  from attachmentTable where document_type=? and name=?;";
      $query=$dbHandle->query($queryCmd, array($document_type, $attachment_name));
    }

    $msgArray=array();
    foreach ($query->result_array() as $row){
        array_push($msgArray,array($row,'struct'));
    }
    $response = array($msgArray,'struct');
    return $this->xmlrpc->send_response($response);
}



    private function _incrementCounter($appId,$mailerId,$number){
        if($mailerId>0 && $number>0){
                $this->load->library(array('mailerconfig'));
                $this->mailerconfig->getDbConfig($appID,$dbConfig);
                $dbHandle  = $this->_loadDatabaseHandle();
                $queryCmd = "UPDATE mailer SET numberMailsSent = (numberMailsSent+?) WHERE id=?";
                $query = $dbHandle->query($queryCmd, array($number, $mailerId));
            }
            return $resp = "1";
    }

    private function _updateMailer($appID,$mailerId,$status){
        $this->load->library(array('mailerconfig'));
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = "UPDATE mailer SET mailsSent=? WHERE id=?";
        $dbHandle->query($queryCmd, array($status, $mailerId));
        return "1";
    }

    private function _updateMailerList($appID,$mailerId,$listId,$status){
        $this->load->library(array('mailerconfig'));
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        $queryCmd = "UPDATE mailer_list SET mailsSent=? WHERE mailerId=? and listId=?";
        $query = $dbHandle->query($queryCmd, array($status, $mailerId, $listId));
        return "1";
    }

    private function _externalMassQueueAdd($appId, $fromAddress, $toAddress, $subject,$content, $contentType, $sendTime = 0,$attachment= "",$attachmentArray=array(),$ccEmail="",$bccEmail=""){
        $this->load->library(array('mailerconfig'));
        $dbConfig = array( 'hostname'=>'localhost');
        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle('write','Mailer');
        $this->load->helper('url');
        $content = xmlrpcHtmlDeSanitize($content,array());

        // Filter mails for Amazon SES
        $mailerServiceType = 'shiksha';
        global $domainsUsingAmazonMailService;
        global $emailidsUsingAmazonMailService;
        $toDomainName = explode("@", $toAddress);
        $ccDomainName = explode("@", $ccEmail);
        $bccDomainName = explode("@", $bccEmail);
        if( (in_array($toDomainName[1], $domainsUsingAmazonMailService)) || (in_array($ccDomainName[1], $domainsUsingAmazonMailService)) || (in_array($bccDomainName[1], $domainsUsingAmazonMailService)) || (in_array($toAddress, $emailidsUsingAmazonMailService)) || (in_array($ccEmail, $emailidsUsingAmazonMailService)) || (in_array($bccEmail, $emailidsUsingAmazonMailService)) ) {
            $mailerServiceType = 'amazon';
        }

        $data=array('fromEmail'=> $fromAddress, 'toEmail'=> $toAddress, 'subject'=>$subject, 'content'=> $content, 'contentType'=>$contentType,'sendTime' => $sendTime,'attachment'=>$attachment,'ccEmail'=>$ccEmail,'bccEmail'=>$bccEmail, 'mailerServiceType'=>$mailerServiceType); 
        $queryCmd = $dbHandle->insert_string('tMailQueue',$data);
        error_log_shiksha('Query for Alert externalMassQueueAdd is : '.$queryCmd); 

        $dbHandle->query($queryCmd);
        $mailerId=$dbHandle->insert_id();
        foreach($attachmentArray as $value)
        {
            $data = array('mailer_id'=>$mailerId, 'attachment_id'=>$value);
            $queryCmd1 = $dbHandle->insert_string('mailerAttachmentMappingTable',$data); 
            $dbHandle->query($queryCmd1);
        }
error_log("Gearman ".$mailerId);
        return $mailerId;
        //return $message="Inserted Successfully";
    }

    public function captureMisData($request){
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appId      = $parameters['0'];
        $trackerId  = $parameters['1'];
        $widget     = $parameters['2'];
        $mailerId   = $parameters['3'];
        $email      = $parameters['4'];
        $mailId     = $parameters['5'];

        list($prefix,$mailerId) = explode('-',$mailerId);
        // check if mailer is valid.
        $mailerId = intval($mailerId);
        $mailerDetails = $this->getMailerDetailsById($mailerId);
        if(!is_array($mailerDetails)  ||  count($mailerDetails)<=0){
            return;
        }
        
        $trackerId = parseUrlFromContent($trackerId);
        $dbHandle  = $this->_loadDatabaseHandle('read','User');
        $query     = $dbHandle->query("SELECT userid,email FROM tuser WHERE email = DECODE(UNHEX(?),'ShikSha') ", array($email));
        $row       = $query->row_array();
        $userId    = (int) $row['userid'];
        $decodedEmail = $row['email'];

        if($userId <=0 && $mailerId >0){
            if($mailerDetails['listId'] ==0){
                return;    
            }else{
                $query     = $dbHandle->query("SELECT DECODE(UNHEX(?),'ShikSha') as email", array($email)); // for CSV data email ids
                $row       = $query->row_array();
                if($row['email'] == ''){
                    return false;
                }
                $decodedEmail = $row['email'];
            }
        }

        if(trim($trackerId) != ''){
            $trackingType = 'click';
        } else {
            $trackingType = 'open';
        }
        

        $sql = "insert into mailerMis(mailerid, userId, emailid, trackerid, widget, date, mailid, trackingType) values(?,?,?,?,?,NOW(),?,?) ";
        error_log($sql);
        $dbHandle  = $this->_loadDatabaseHandle('write','Mailer');
        $dbHandle->query($sql, array($mailerId, $userId, $decodedEmail, $trackerId, $widget, $mailId, $trackingType));
        /**
		 * Update userMailerSentCount table to reset triggers of product mailers when a product mail is clicked
		 */
		$this->load->library('mailer/ProductMailerEventTriggers');
		$this->productmailereventtriggers->resetMailerTriggers($userId, 'mailClicked', $params=array('mailerId' => $mailerId));
		
    }
    
    public function unsubscribe($request){
        mail('teamldb@shiksha.com', 'function -unsubscribe(File : MailerServer) after Unsubscribe removed from tuserflag', print_r($_SERVER,true));
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appId=$parameters['0'];
        $encodedMail = $parameters['1'];
        
        if(!is_string($encodedMail)) {
				return "";
		}
	
        $dbHandle  = $this->_loadDatabaseHandle('write','User');
        if($dbHandle == '')
        {
            log_message('error','autoLogin can not create db handle');
        }
        $unsubSql = "update tuserflag a, tuser b set a.unsubscribe = '1' , b.newsletteremail = '0' where a.userid = b.userid and b.email=DECODE(UNHEX(?),'ShikSha')";
        error_log('777777'.print_r($unsubSql, true));
        $dbHandle->query($unsubSql, array($encodedMail));
		
		$sql = "SELECT userid FROM tuser WHERE email = DECODE(UNHEX(?),'ShikSha')";
		$query = $dbHandle->query($sql, array($encodedMail));
		$row = $query->row_array();
		
		$this->load->library('user/UserLib');
		$userLib = new UserLib;
		$userLib->updateUserData($row['userid']);
    }

    private function _isCoursePaid($courseId){
        $this->load->builder('ListingBuilder','listing');
        $listingbuilder = new ListingBuilder();
        $courseRepo = $listingbuilder->getCourseRepository();
        $courseObj = $courseRepo->find($courseId);
        $isPaid=$courseObj->isPaid();
        return $isPaid;
    }

        // saves the maler and ready to be sent.
    function updateMailerDetails($request) {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $mailerName = $parameters['1'];
        $time = $parameters['2'];
        $senderMail = $parameters['3'];
        $loggedInUser = $parameters['4'];
        $encodedsumsData = $parameters['5'];
        $sumsData = base64_decode($encodedsumsData);
        $sumsData = json_decode($sumsData,true);
        $numUsers = $parameters['6'];
        $sender_name = $parameters['7'];
        $mailStatus = 'false';
        if($parameters['8'] != ''){
           $mailStatus = $parameters['8'];
        }
        $mailerId = $parameters['9'];
        $subject = '';
        if($parameters['10'] != '' && $mailStatus == 'false'){
           $subject = $parameters['10'];
        }

        $this->mailerconfig->getDbConfig($appID,$dbConfig);
        $dbHandle  = $this->_loadDatabaseHandle();
        if($dbHandle == ''){
            log_message('error','can not create db handle');
        }
        
        $this->load->library('Subscription_client');
        $objSubs = new Subscription_client();
        $consumptionArr = array();
        $consumptionArr['consumptionQuant'] = $numUsers;
        $consumptionArr['clientUserId'] = $sumsData['clientUser'];
        $consumptionArr['sumsUserId'] = $loggedInUser;
        list($usec, $sec) = explode(" ", microtime());
        $timestamp = ((int)$usec + (int)$sec);
        $consumptionArr['consumedTypeId'] = $timestamp;
        $consumptionArr['consumedType'] = "mailer";
        $consumptionArr['startDate'] = date("Y-m-d");
        $consumptionArr['endDate'] = date("Y-m-d"); 
        $consumptionArr['subscriptionId'] = $sumsData['subscriptionId'];

        if((!($sumsData['subscriptionId'] == '' && $sumsData['clientUser'] == '')) && ($mailStatus == 'false')) {
            $return = $objSubs->consumeSubscriptionWithCount("1",$consumptionArr);
            if($return["ERROR"] == 1) {
                $response = array("ERROR"=>1);
                $resp = array($response,'struct');
                return $this->xmlrpc->send_response ($resp);
            }
        } 
       
        $queryCmd = "UPDATE mailer set mailerName = ?, time = ?, mailsSent = ?, sendername = ?, senderMail = ?,totalMailsToBeSent = ?, subject = ? where id = ?";
        $query = $dbHandle->query($queryCmd, array($mailerName, $time, $mailStatus, $sender_name, $senderMail, $numUsers, $subject, $mailerId));
        $msgArray = array('success');
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getMailerDetailsById($mailerId){
        if(empty($mailerId) || $mailerId <=0){
            return array();
        }

        $dbHandle = $this->_loadDatabaseHandle('read','Mailer');
        $sql = "select id, listId from mailer where id = ?";
        $result = $dbHandle->query($sql,array($mailerId))->row_array();
        return $result;
    }
}
?>
