<?php
//Main class for AnA Internal Controller
class MessageBoardInternal extends MX_Controller {

    function init(){
		$this->load->helper(array('form', 'url','date','image','shikshaUtility','utility_helper','CA/cr'));
		$this->userStatus = $this->checkUserValidation();
		if($this->userStatus!='false'){
			$cookiestr = explode('|', $this->userStatus[0]['cookiestr']);
			$this->userStatus[0]['email'] = trim($cookiestr[0]);
		}
    }

    function getAccessLevel($userId=0, $userGroup=''){
    	if($userId == 0 && $userGroup == '')
    	{
    		$userId    = $this->userStatus[0]['userid'];
    		$userGroup = $this->userStatus[0]['usergroup'];
    	}
    	$this->load->model('qnamoderationmodel');
    	$this->qnamoderationmodel = new qnaModerationModel();
        $hasModeratorAccess = 0;
        $moderatorInfo = $this->qnamoderationmodel->getModeratorInfo(array($userId));
        if($userGroup == "cms")
    		$hasModeratorAccess = 1; //cms user
    	else if(!empty($moderatorInfo) && $userId == $moderatorInfo[0]['userId'] && $moderatorInfo[0]['groupId'] == 1)
    		$hasModeratorAccess = 2; //moderator admin
        else if(!empty($moderatorInfo) && $userId == $moderatorInfo[0]['userId'] && $moderatorInfo[0]['groupId'] == 3)
    		$hasModeratorAccess = 2; //moderator admin
    	else if(!empty($moderatorInfo) && $userId == $moderatorInfo[0]['userId'] && $moderatorInfo[0]['groupId'] == 4)
    		$hasModeratorAccess = 3; //moderator
    	return $hasModeratorAccess;
    }

    /****************************
    Purpose: Function is use to show the login page for mapping interface.
    *****************************/
    public function showLoginPage(){ 
        $this->init();
        $appId = 12;
        $data['validateuser'] = $this->userStatus;			
        $data['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

        //below code used for beacon tracking
        $data['trackingpageIdentifier'] = 'cafeModerationPanelLoginPage';
        $data['trackingcountryId']=2;


        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);

        echo $this->load->view('anaInternal/showLoginForm', $data);
    }


    public function moderationPanel()
    {
        $this->init();
        if($this->userStatus!='false'){
            $usergroup = $this->userStatus[0]['usergroup'];
            if($usergroup == "cms"){
                $this->load->model('qnamodel');
				$this->load->model('CA/camodel');
		     	$this->camodel = new CAModel();
 				$programId = (isset($_POST['categorySelect']) && $_POST['categorySelect']!='')?$this->input->post('categorySelect',true):1;
		        $courseIdList = $this->camodel->getCourseIdsCampusRep($programId);
         		if($courseIdList != ''){
         			$_POST = $this->security->xss_clean($_POST);  // for xss issues
         			$anaInformation = $this->qnamodel->getAnAModerationData($_POST, $courseIdList);
   					$anaInformation['anaBasicInfo']=$anaInformation['anaInfo'];
					if(empty($anaInformation['anaInfo']))
			    		$anaInformation['anaBasicInfo']=$anaInformation['caProfileInfo'];
						$tempInfoArr = $anaInformation['anaBasicInfo']->result_array();
						$step = 0;
						$totalUserIdArr = array();
						foreach($tempInfoArr as $userInfo)
						{
						    $status = $this->qnamodel->checkIfUserIsRegisteredCampusRep($userInfo['userId']);
						    $anaInformation['anaBasicInfo']->result_array[$step]['isCampusRep'] = $status;
						    $step++;
						    $totalUserIdArr[] = $userInfo['userId'];
						}
						//added by akhter to prepare incentive as per user category if user is ca
						if(!empty($totalUserIdArr))
						{   $this->load->library('CA/Mywallet');
						    $totalUserIds = implode(',', $totalUserIdArr);
						    $incentiveUser = $this->mywallet->makeIncentive($totalUserIds);
						}
						$anaInformation['makeUserIncentive'] = $incentiveUser;
						
						$anaInformation['validateuser'] = $this->userStatus;
			
						if(!empty($anaInformation['caProfileInfo'])){
						    $dataArrayCaProfileInfo = $anaInformation['caProfileInfo']->result_array();
						    $caProfileInfo = array();
						    foreach($dataArrayCaProfileInfo as $key=>$caInfo){
							$userId=$caInfo['userId'];
							$caCourse[$userId][]=$caInfo['course_id'];
							$caProfileInfo[$userId]=$caCourse[$userId];
						    }
						    $anaInformation['caProfileInfo']=$caProfileInfo;
						}
			
			
		                $this->load->library('category_list_client');
		                $categoryClient = new Category_list_client();
		        
		                //Get Country List
		                $countryList = array();
		                $tempArray = $categoryClient->getCountries($appId);
		                foreach($tempArray as $temp){
		                        $countryList[$temp['countryID']] = $temp['countryName'];
		                }
		                $anaInformation['countryList'] = $countryList;
		         
	            }   
               	$anaInformation['topCategories'] = $this->qnamodel->getAllCCPrograms();
                
               	
                $this->load->view('anaInternal/moderationPanel', $anaInformation);

            }else{
               		$this->showLoginPage();
            }
        }else{ 
            		$this->showLoginPage();
        }
        
    }
    
    function updateCRAnswerStatus()
    {
	$this->init();
	$msgId = isset($_POST['msgId'])?$this->input->post('msgId'):0;
	$userId = isset($_POST['userId'])?$this->input->post('userId'):0;
	$status = isset($_POST['status'])?$this->input->post('status'):'draft';
	$reason = isset($_POST['reason'])?$this->input->post('reason'):'';
	$this->load->model('qnamodel');
	echo $this->qnamodel->updateCRAnswerStatus($status, $reason, $userId, $msgId);
    }
    function addCRAnswerEarning()
    {
	$this->init();
	$msgId = isset($_POST['msgId'])?$this->input->post('msgId'):0;
	$userId = isset($_POST['userId'])?$this->input->post('userId'):0;
//	$earning = isset($_POST['earning'])?$this->input->post('earning'):0;
	$type = isset($_POST['type'])?$this->input->post('type'):'';
	$status = isset($_POST['status'])?$this->input->post('status'):'';
	if($userId>0 && $msgId>0 && $type!='')
	{
	    $this->load->model('qnamodel');
	    if($type == 'approvedAnswer')
	    {
		if($status == 'approved')
		{
		    $this->qnamodel->addCRAnswerApproveEarning($type, $earning, $userId, $msgId);
		    echo 'approved';
		}
		else if($status == 'disapproved')
		{
		    $this->qnamodel->updateCRDisapproveAnswerEarning($userId, $msgId);
		    $this->sendMailForCRAnswerDisapproval($userId, $msgId);
		    echo 'disapproved';
		}
	    }
	    else if($type == 'featuredAnswer')
	    {
		$this->qnamodel->addCRAnswerFeatureEarning($earning, $type, $userId, $msgId);
		echo 'featured';
	    }
	}
	else
	{
	    echo 0;
	}
    }
    
    function makeAnswerModerated($msgId, $userId, $flag)
    {
	$this->init();
	$msgId = isset($_POST['msgId'])?$this->input->post('msgId'):0;
	$userId = isset($_POST['userId'])?$this->input->post('userId'):0;
	$flag = isset($_POST['flag'])?$this->input->post('flag'):'0';
	$this->load->model('qnamodel');
	echo $this->qnamodel->makeAnswerModerated($msgId, $userId, $flag);
    }
    
      /**
     * @param varchar  $msgId , $isFeaturedFlag
     * Marks the answer as featured / non - featured
     * @author Pragya
     */
   function makeFeaturedAnswer(){
	$this->init();
	$msgId = $this->input->post('msgId');
	$isFeaturedAnswer = $this->input->post('flag');
	$this->load->model('QnAModel');
	$cacheLib = $this->load->library('cacheLib');
	$cacheKey = md5('getAllAnswersWithFeaturedFlag');
	if($cacheLib->get($cacheKey)!='ERROR_READING_CACHE'){
	    $cacheLib->clearCacheForKey($cacheKey);
	}
	echo $this->QnAModel->makeFeaturedAnswer($msgId,$isFeaturedAnswer);
	echo $result;
    }
    function editAnswerByModerator()
    {
    	$this->init();
		if($this->userStatus!='false'){
			$hasModeratorAccess = $this->getAccessLevel();
        	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3){
            	$msgId = isset($_POST['answerId'])?$this->input->post('answerId'):0;
				$msgTxt = isset($_POST['replyText'.$msgId])?$this->input->post('replyText'.$msgId):'';
				$entityType = isset($_POST['entityType'])?$this->input->post('entityType'):'';
				$fromOthers = isset($_POST['fromOthers'.$msgId])?$this->input->post('fromOthers'.$msgId):'';
				$threadId = isset($_POST['threadid'.$msgId])?$this->input->post('threadid'.$msgId):'';
				$this->load->library('v1/AnACommonLib');
		        $this->anaCommonLib = new AnACommonLib();
				$this->anaCommonLib->trackEditOperation($msgId, $entityType, $userId);
				$this->load->model('QnAModel');
				$res = $this->QnAModel->editAnswerByModerator($msgId,$msgTxt);
				/*if($entityType == 'answer' || ($entityType == 'comment' && $fromOthers == 'discussion')){
			    $this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
			    if($entityType == 'answer'){
				$this->appNotification->addNotificationForAppInRedis('edit_answer',$threadId,'question',$userId,$entityType,$msgId);
			    }else{
				$this->appNotification->addNotificationForAppInRedis('edit_comment',$threadId,'discussion',$userId,$entityType,$msgId);
			    }
		}*/

				echo $res;
	    	}
		    else
		    {
				echo 0;
		    }
		}
		else
		{
		    echo 0;
		}
    }

    function editCommentByModerator(){
    	$this->init();
    	if($this->userStatus!='false'){
    		$hasModeratorAccess = $this->getAccessLevel();
        	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3){
            	$msgId = isset($_POST['commentId'])?$this->input->post('commentId'):0;
				$msgTxt = isset($_POST['replyText'.$msgId])?$this->input->post('replyText'.$msgId):'';
				$entityType = isset($_POST['entityType'])?$this->input->post('entityType'):'';
				$this->load->library('v1/AnACommonLib');
		        $this->anaCommonLib = new AnACommonLib();
				$this->anaCommonLib->trackEditOperation($msgId, $entityType, $userId);
				$this->load->model('QnAModel');
				echo $this->QnAModel->editAnswerByModerator($msgId, $msgTxt);
            }else{
            	echo 0;
            }
    	}else{
    		echo 0;
    	}
    }
    
    function sendMailForCRAnswerDisapproval($userId, $msgId)
    {
	$this->init();
	$this->load->model('qnamodel');
	$CRAnsData = $this->qnamodel->getCRDetailsForDisapproveMail($userId, $msgId);
	if(is_array($CRAnsData[0]) && isset($CRAnsData[0]['courseId']) && $CRAnsData[0]['courseId']>0)
	{
	    $this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$courseRepository = $courseBuilder->getCourseRepository();
		$courseObj = $courseRepository->find($CRAnsData[0]['courseId']);
	    $courseName = $courseObj->getName();
	}
	$contentArr = array();
	$userEmail = '';
	$attachment = array();
	$guidelineLink = SHIKSHA_HOME.'/shikshaHelp/ShikshaHelp/campusRepGuideline';
	$dashboard_dis = SHIKSHA_HOME.'/CA/CRDashboard/getDisapprovedAnswer';
	if(is_array($CRAnsData[0]) && !empty($CRAnsData[0]))
	{
	    $userEmail = $CRAnsData[0]['email'];
	    $contentArr['crName'] = $CRAnsData[0]['fname'].' '.$CRAnsData[0]['lname'];
	    $contentArr['courseName'] = $courseName;
	    $contentArr['guidelineLink'] = $guidelineLink;
	    $contentArr['dashboardDisapproveLink'] = $dashboard_dis;
	    $contentArr['type'] = 'CAAnswerDisapprove';
	    $contentArr['quesTxt'] = $CRAnsData[0]['quesTxt'];
	    
	    $this->load->library('mailerClient');
	    $MailerClient = new MailerClient();
	    $contentArr['urlOfLandingPage'] = $dashboard_dis;
	    $contentArr['autoLoginUrl'] = $MailerClient->generateAutoLoginLink(1, $userEmail, $dashboard_dis);
	    Modules::run('systemMailer/SystemMailer/CRAnswerDisapprovalMail',$userEmail, $contentArr, $attachment);
	}
    }

    /* Not in use anymore */
    /*        
    public function cafeModerationPanel()
    {
        $this->init();
		ini_set("memory_limit","-1");
        if($this->userStatus!='false'){
            $usergroup = $this->userStatus[0]['usergroup'];
            if($usergroup == "cms"){
                $this->load->model('qnamodel');
		
		if(isset($_POST['tag_search']) && $_POST['tag_search']!=''){
		    $_POST['tagValue'] = $this->input->post('tagValue');
		}else{
		    $_POST['tagValue'] = '';
		}
		
                $anaInformation = $this->qnamodel->getAnACafeModerationData($_POST);
		$anaInformation['start'] = isset($_POST['start'])?$this->input->post('start'):0;
		$anaInformation['anaBasicInfo']=$anaInformation['anaInfo'];
		
		$tempInfoArr = $anaInformation['anaBasicInfo'];
		
		foreach($tempInfoArr as $userInfo)
		{
		    $entityIdArr[] = $userInfo['threadId'];
		}
		
		//added by yamini to get tag details assciated with entity
		
		if(!empty($entityIdArr)){
		    $entityIds = implode(',', $entityIdArr);
		    $associatedTags = $this->qnamodel->getTagDetails($entityIds);
		    $anaInformation['associatedTags'] =$associatedTags;
		}
		
		if(!empty($anaInformation['associatedTags'])){
		    $this->load->library('Tagging/TaggingLib');
		    $this->TaggingLib = new TaggingLib();
		    
		    foreach($anaInformation['associatedTags'] as $key=>$val){
			      $finalTags[$key] = $this->TaggingLib->getAllTagsOfEntity($key, $val['contentType']);
			 }
		}
		
		$anaInformation['finalTags'] = $finalTags;
		
                $this->load->library('category_list_client');
                $categoryClient = new Category_list_client();
        
                //Get Country List
                $countryList = array();
                $tempArray = $categoryClient->getCountries($appId);
                foreach($tempArray as $temp){
                        $countryList[$temp['countryID']] = $temp['countryName'];
                }
                $anaInformation['countryList'] = $countryList;
                
                //Get Category List
                $categoryList = $categoryClient->getCategoryTree($appId);
		$others = array();
                $categoryForLeftPanel = array();
		$topCategories = array();
                foreach($categoryList as $temp)
                {
                        if($temp['parentId'] == 1)
			{
			    $topCategories[] = array($temp['categoryID'], $temp['categoryName']);
			}
			if((stristr($temp['categoryName'],'Others') == false))
                        {
                        $categoryForLeftPanel[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
                        }
			else
                        {
                        $others[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
                        }
                }
                foreach($others as $key => $temp)
                {
                        $categoryForLeftPanel[$key] = array($temp[0],$temp[1]);
                }
                $anaInformation['categoryList'] = $categoryForLeftPanel;
		$anaInformation['topCategories'] = $topCategories;
                $this->load->view('anaInternal/cafeModerationPanel', $anaInformation);

            }else{
               $this->showLoginPage();
            }
        }else{ 
            $this->showLoginPage();
        }
        
    }
    */

    public function newCafeModerationPanel()
    {
	$dataStr = '';
    	$this->benchmark->mark('controller_start');
    	$initialMemory = memory_get_usage();
        $this->init();
        ini_set("memory_limit","-1");
        if($this->userStatus!='false'){
        	$this->load->model('qnamodel');
        	$this->load->model('Tagging/taggingcmsmodel');

        	$hasModeratorAccess = $this->getAccessLevel();
            if($hasModeratorAccess > 0){				
				if(isset($_POST['tag_search']) && $_POST['tag_search']!=''){
				    $_POST['tagValue'] = $this->input->post('tagValue',true);
				}else{
				    $_POST['tagValue'] = '';
				}
				$this->config->load('messageBoard/MessageBoardInternalConfig');
				$_POST['timeFlag'] = $this->config->item("timeOfNewModerationStart"); //date from which new panel will go live
				$this->benchmark->mark('query_start');
				$_POST = $this->security->xss_clean($_POST);  // for xss issues
				$anaInformation = $this->qnamodel->getAnACafeModerationData_v1($_POST, $this->userStatus[0]['userid'], $hasModeratorAccess);
				foreach ($anaInformation['anaBasicInfo'] as $key => $eData) {
					$allMsgIds[] = $eData['msgId']; 
				}
				$checkIfEditRequested = $this->qnamodel->checkIfEditRequested($allMsgIds);
				$this->benchmark->mark('query_end');
				//error_log(':::::modPanel::::main query time - '.$this->benchmark->elapsed_time('query_start', 'query_end'));
				$dataStr .= '<pre>'.'Query time = <b>'.$this->benchmark->elapsed_time('query_start', 'query_end').'</b>';
				$isSomeEntityLocked = $this->qnamoderationmodel->checkIfSomeEntityIsAlreadyLocked($this->userStatus[0]['userid']);
				if($isSomeEntityLocked>0){
					setcookie('cafeModerationStart', $isSomeEntityLocked, time()+(24*7*3600), '/', COOKIEDOMAIN);
				}else{
					setcookie('cafeModerationStart', $isSomeEntityLocked, time()-(24*7*3600), '/', COOKIEDOMAIN);
				}
				$anaInformation['start'] = isset($_POST['start'])?$this->input->post('start'):0;
				$anaInformation['hasModeratorAccess'] = $hasModeratorAccess;
		        	$entityIdArr = array();
				foreach($anaInformation['anaBasicInfo'] as $userInfo)
				{
				    $entityIdArr[] = $userInfo['threadId'];
				}
				
				if(!empty($entityIdArr)){
				    $entityIds = implode(',', $entityIdArr);
				    $associatedTags = $this->qnamodel->getTagDetails($entityIds);
				    $anaInformation['associatedTags'] =$associatedTags;
				}
				
				if(!empty($anaInformation['associatedTags'])){
				    $this->load->library('Tagging/TaggingLib');
				    $this->TaggingLib = new TaggingLib();
				    
				    /*
				    foreach($anaInformation['associatedTags'] as $key=>$val){
					      $finalTags[$key] = $this->TaggingLib->getAllTagsOfEntity($key, $val['contentType']);
					 }*/
				    $finalTags = $this->TaggingLib->getAllTagsOfEntity($entityIdArr);
				}
				
				$anaInformation['finalTags'] = $finalTags;
				
				if($hasModeratorAccess == 1 || $hasModeratorAccess == 2){
					$anaInformation['tabSelected'] = 3;
				}else{
					$anaInformation['tabSelected'] = 1;
				}
		
                $this->load->library('category_list_client');
                $categoryClient = new Category_list_client();
        
                //Get Country List
                $countryList = array();
                $tempArray = $categoryClient->getCountries($appId);
                foreach($tempArray as $temp){
                        $countryList[$temp['countryID']] = $temp['countryName'];
                }
                $anaInformation['countryList'] = $countryList;
                
                // Get Tags List
                $streamList = $this->taggingcmsmodel->getTagsArray('Stream');

                $dummyArray = array();
                foreach ($streamList as $key => $value) {
                	$dummyArray[] = $value['tags'];
                }
                array_multisort($dummyArray,SORT_ASC,$streamList);
                
                $anaInformation['streamList'] = $streamList;

                //Get Category List
                $categoryList = $categoryClient->getCategoryTree($appId);
				$others = array();
                $categoryForLeftPanel = array();
				$topCategories = array();
                foreach($categoryList as $temp)
                {
                    if($temp['parentId'] == 1)
					{
					    $topCategories[] = array($temp['categoryID'], $temp['categoryName']);
					}
					if((stristr($temp['categoryName'],'Others') == false))
                    {
                    	$categoryForLeftPanel[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
                    }
					else
                    {
                    	$others[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
                    }
            	}
                foreach($others as $key => $temp)
                {
                        $categoryForLeftPanel[$key] = array($temp[0],$temp[1]);
                }
                $anaInformation['categoryList'] = $categoryForLeftPanel;
				$anaInformation['topCategories'] = $topCategories;

				$anaInformation['entityTypeArray'] = array( 'user'=>'Question',
															'discussion'=>'Discussion',
															'answer'=>'Answer',
															'answercomment'=>'Answer Comment',
															'discussioncomment'=>'Discussion Comment',
															'discussionreply'=>'Discussion Reply',
															'Unansweredquestion'=>'Unanswered Question' );
                $anaInformation['validateuser']  = $this->userStatus;
                if($hasModeratorAccess == 1 || $hasModeratorAccess == 2)
                {
                	$anaInformation['allModerators'] = $this->qnamoderationmodel->getModeratorsInfo($hasModeratorAccess);
                	$moderatorEmailMap = array();
                	foreach ($anaInformation['allModerators'] as $key => $value) {
                		$moderatorEmailMap[$value['userid']] = $value['email'];
                	}
                	$anaInformation['moderatorEmailMap'] = $moderatorEmailMap;
                	$anaInformation['moderatorEmailMap']['11'] = 'cmsadmin@shiksha.com';
                }
                //$anaInformation['moderatedEntityInfo'] = $this->getModeratedEntityInfo();
                $anaInformation['editRequestReasons'] = $this->config->item("editRequestReasons");
                $anaInformation['checkIfEditRequested'] = $checkIfEditRequested;
                $this->load->view('anaInternal/newModeration/newCafeModerationPanel', $anaInformation);
                $finalMemory = memory_get_usage();
				//error_log(':::::modPanel::::'.'overall memory usage = '.($finalMemory-$initialMemory).'bytes');
		        $this->benchmark->mark('controller_end');
				//error_log(':::::modPanel::::overall controller time - '.$this->benchmark->elapsed_time('controller_start', 'controller_end'));
				$dataStr .= ', Overall time = <b>'.$this->benchmark->elapsed_time('controller_start', 'controller_end').'</b>';
		        $dataStr .= ', Overall memory = <b>'.($finalMemory-$initialMemory).' bytes</b><br/>';
				//$dataStr .= "<br/>".$anaInformation['sqlQuery']."<br/>";
				$dataStr .= print_r($_POST, true).'</pre>';
				$dataStr .= '<hr/>';
				$this->moderationPanelBenchmark($dataStr);
            }else{
               $this->showMPLoginPage();
            }
        }else{ 
            $this->showMPLoginPage();
        }
    }

    public function getModeratedEntityInfo()
    {
    	$this->init();
    	if($this->userStatus!='false')
    	{
    		$hasModeratorAccess = $this->getAccessLevel();
    		if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3){
    			$userids = '';
	    		if($hasModeratorAccess == 3){
	    			$userids = $this->userStatus[0]['userid'];
	    		}
	    		else if($hasModeratorAccess == 1 || $hasModeratorAccess == 2){
	    			$userids = 11;
	    			$accessFlag = false;
	    			$myModerators = $this->qnamoderationmodel->getModeratorsInfo($hasModeratorAccess, $accessFlag);
	    			foreach ($myModerators as $value) {
	    				$userids .= ','.$value['userid'];
	    			}
	    		}
	    		$this->config->load('messageBoard/MessageBoardInternalConfig');
	    		$timeFlag = $this->config->item("timeOfNewModerationStart");
	    		$this->load->library('moderationPanelLib');
    			$moderationLib = new moderationPanelLib();
    			$filter = array();
    			$filter['userids']  = $userids;
    			$filter['timeFlag'] = $timeFlag;
    			$result = $moderationLib->getFormattedModeratedEntityInfo($filter);
	    		return $result;
    		}
    		else
    			echo 'unauthorised';
    	}else{
    		die('error');
    	}
    }
    
    public function moderationPanelBenchmark($dataStr){
    	$file = 'public/test.html';
    	//$file = '/var/www/html/shiksha/public/test.txt';
    	file_put_contents($file, $dataStr, FILE_APPEND | LOCK_EX);
    }

    public function getLockedEntities()
    {
    	$this->init();
    	if($this->userStatus!='false' && $this->input->is_ajax_request() )
    	{
    		$hasModeratorAccess = $this->getAccessLevel();
    		if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3){
    			$userids = '';
	    		if($hasModeratorAccess == 3){
	    			$userids = $this->userStatus[0]['userid'];
	    		}
	    		else if($hasModeratorAccess == 1 || $hasModeratorAccess == 2){
	    			$myModerators = $this->qnamoderationmodel->getModeratorsInfo($hasModeratorAccess);
	    			foreach ($myModerators as $value) {
	    				$userids .= ($userids=='')?$value['userid']:','.$value['userid'];
	    			}
	    			$userids .= ','.$this->userStatus[0]['userid'];
	    		}
	    		$this->load->model('qnamodel');
	    		if($userids != '')
	    		{
	    			$this->config->load('messageBoard/MessageBoardInternalConfig');
	    			$filter = array();
	    			$filter['userids']          = $userids;
	    			$filter['moderationStatus'] = 'locked';
	    			$filter['timeFlag']         = $this->config->item("timeOfNewModerationStart");
	    			$anaInformation = array();
	    			$anaInformation = $this->qnamodel->getAnACafeModerationData_v1($filter, $this->userStatus[0]['userid'], $hasModeratorAccess);
	    			$anaInformation['validateuser']  = $this->userStatus;
	    			$anaInformation['hasModeratorAccess'] = $hasModeratorAccess;
	    			echo $this->load->view('anaInternal/newModeration/moderationDataInfoPanel', $anaInformation, true);
	    		}
    		}
    		else
    			echo 'unauthorised';
    	}else{
    		die('error');
    	}
    }

    public function getEntitiesModeratedByMe(){
    	$this->init();
    	if($this->userStatus!='false' && $this->input->is_ajax_request() )
    	{
    		$start  = isset($_POST['start']) && $_POST['start']!='' ? $this->input->post('start') : 0;
    		$count  = isset($_POST['count']) && $_POST['count']!='' ? $this->input->post('count') : 20;
    		$first  = isset($_POST['firstCall']) && $_POST['firstCall']!='' ? $this->input->post('firstCall') : 'no';
    		$this->load->model('qnamoderationmodel');
			$hasModeratorAccess = $this->getAccessLevel();
    		if($hasModeratorAccess == 3)
    		{
    			$this->load->library('moderationPanelLib');
    			$moderationLib = new moderationPanelLib();
    			$result = $moderationLib->getAllEntitiesModeratedByModerator($this->userStatus[0]['userid'], $start, $count);
    			$displayData = array();
    			$displayData['myModeratedData'] = $result;
    			if(empty($result) && $first == 'yes')
    				echo 'FailFirst';
    			else if(empty($result) && $first == 'no')
    				echo 'FailNext';
    			else
    				echo $this->load->view('anaInternal/newModeration/myModeratedEntities', $displayData, true);
    		}
    		else
    			echo 'unauthorised';
    	}else{
    		die('error');
    	}
    }

    public function checkAndLockEntity()
    {
    	$this->init();
    	if($this->userStatus!='false' && $this->input->is_ajax_request() )
    	{
    		$this->load->model('qnamoderationmodel');
    		$this->load->library('moderationPanelLib');
    		$moderationLib = new moderationPanelLib();
    		$hasModeratorAccess = $this->getAccessLevel();
			$msgId  = isset($_POST['msgId']) && $_POST['msgId']!='' ? $this->input->post('msgId') : 0;
    		if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3)
    			echo $moderationLib->getEntityLockedForModerator($msgId, $this->userStatus[0]['userid']);
    		else
    			echo 'unauthorised';
    		
    	}else{
    		die('error');
    	}
    }

    public function unlockEntity(){
    	$this->init();
    	if($this->userStatus!='false' && $this->input->is_ajax_request() )
    	{
    		$this->load->model('qnamoderationmodel');
    		$this->load->library('moderationPanelLib');
    		$moderationLib = new moderationPanelLib();
    		$hasModeratorAccess = $this->getAccessLevel();
        	$msgId  = isset($_POST['msgId']) && $_POST['msgId']!='' ? $this->input->post('msgId') : 0;
    		if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3){
    			//index question for autosuggestor
    			$returnValue = $moderationLib->getEntityUnlockedForModerator($msgId, $this->userStatus[0]['userid']);

				ob_start();
				$this->benchmark->mark('index_question_auto_sugestor_start');

	    			//Modules::run('indexer/NationalIndexer/indexQuestionForAutosuggestor',$msgId);
                                $this->load->model('messageBoard/AnAModel');
                                $typeOfEntity = $this->AnAModel->getEntityType($msgId);
                                $entityType = 'question';
                                if($typeOfEntity == 'discussion'){
                                    $entityType = $typeOfEntity;
                                }

				if($this->AnAModel->getEntityStatus($msgId) != 'deleted'){
	                                Modules::run('search/Indexer/addToQueue', $msgId, $entityType);
				}

				$this->benchmark->mark('index_question_auto_sugestor_end');
				$output = ob_get_contents();
				ob_end_clean();
				echo $returnValue;
    			
    		}else{
    			echo 'unauthorised';
    		}
    	}else{
    		die('error');
    	}
    }

    public function entitiesInCache(){
    	$this->init();
    	if($this->userStatus!='false')
    	{
    		$this->load->model('qnamoderationmodel');
    		$hasModeratorAccess = $this->getAccessLevel();
        	if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3)
    		{
    			$entityData = $this->qnamoderationmodel->getModeratedEntities();
    			$cacheLib  = $this->load->library('cacheLib');
    			echo 'User IDs<br/>';
    			foreach ($entityData['moderatorIdArr'] as $value) {
    				if($cacheLib->get('userId-'.$value) != 'ERROR_READING_CACHE')
    				{
	    				echo "user $value => ".$cacheLib->get('userId-'.$value);
	    				echo '<br/>';
	    			}
    			}
    			echo '<br/>Entity IDs<br/>';
    			foreach ($entityData['entityIdArr'] as $value) {
    				if($cacheLib->get('msgId-'.$value) != 'ERROR_READING_CACHE')
    				{
	    				echo "entity $value => ".$cacheLib->get('msgId-'.$value);
	    				echo '<br/>';
    				}
    			}
    		}
    		else
    			echo 'unauthorised';
    	}else{
    		die('error');
    	}
    }

    public function reputationIndexPanel()
    {
    	$this->init();
    	if($this->userStatus!='false'){
        	$this->load->model('qnamoderationmodel');
        	$hasModeratorAccess = $this->getAccessLevel();
        	$data = array();
        	$data['validateuser']  = $this->userStatus;
            $data['moderatorInfo'] = $moderatorInfo;
            if($hasModeratorAccess == 1 || $hasModeratorAccess == 2){ 
               	if($this->input->is_ajax_request()){
               		$page_number = ($_POST['page']>0) ? $_POST['page'] : 0;
               	}
            	$data['hasModeratorAccess'] = $hasModeratorAccess;
            	$data['userPointDetail'] = $this->qnamoderationmodel->getUserPoints($page_number);
            	$data['totalPointUser'] = $data['userPointDetail']['total'];
            	$data['item_per_page'] = 10;
            	if($this->input->is_ajax_request()){
            		echo $this->load->view('anaInternal/newModeration/innerUserPointDetailPage',$data,true);exit();
            	}else{
            		$data['tabSelected'] = 4;
            		$this->load->view('anaInternal/newModeration/reputationIndexPanel', $data);
            	}
            }else{
               $this->showMPLoginPage();
            }
        }else{ 
            $this->showMPLoginPage();
        }
    }

    function showMPLoginPage()
    {
    	$this->init();
        $appId = 12;
        $data['validateuser'] = $this->userStatus;
        $data['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $this->load->view('anaInternal/newModeration/showMPLoginForm_v1', $data);
    }

    function moderatorLockedEntities()
    {
    	$this->init();
    	if($this->userStatus!='false'){
        	$this->load->model('qnamoderationmodel');
        	$hasModeratorAccess = $this->getAccessLevel();
        	$data = array();
        	$data['validateuser']  = $this->userStatus;
            $data['moderatorInfo'] = $moderatorInfo;
            if($hasModeratorAccess == 1 || $hasModeratorAccess == 2){
            	$data['hasModeratorAccess'] = $hasModeratorAccess;
            	$data['tabSelected'] = 6;
            	$data['allModerators'] = $this->qnamoderationmodel->getModeratorsInfo($hasModeratorAccess);
            	array_push($data['allModerators'], array('userid'=>'11', 'email'=>'cmsadmin@shiksha.com'));
            	$this->load->view('anaInternal/newModeration/moderatorLockedEntities', $data);
            }else{
               $this->showMPLoginPage();
            }
        }else{ 
            $this->showMPLoginPage();
        }
    }
    function getLockedEntityForModerator(){
    	$this->init();
    	if($this->userStatus!='false' && $this->input->is_ajax_request() )
    	{
    		$this->load->library('moderationPanelLib');
    		$moderationLib = new moderationPanelLib();
    		$hasModeratorAccess = $this->getAccessLevel();
        	$inputModeratorId  = isset($_POST['moderatorId']) && $_POST['moderatorId']!='' ? $this->input->post('moderatorId') : 0;
    		if($hasModeratorAccess == 1 || $hasModeratorAccess == 2){
    			$result['entityLockData'] = $moderationLib->getLockedEntitiesForModerator($inputModeratorId);
    			if(!empty($result['entityLockData'])){
    				$this->load->view('anaInternal/newModeration/moderatorLockedEntityList', $result);
    			}else{
    				echo 'empty';
    			}
    		}
    		else
    			echo 'unauthorised';
    	}else{
    		die('error');
    	}
    }
    function releaseLockOfEntityByCms(){
    	$this->init();
    	if($this->userStatus!='false' && $this->input->is_ajax_request() )
    	{
    		$this->load->library('moderationPanelLib');
    		$moderationLib = new moderationPanelLib();
    		$hasModeratorAccess = $this->getAccessLevel();
        	$inputModeratorId  = isset($_POST['moderatorId']) && $_POST['moderatorId']!='' ? $this->input->post('moderatorId') : 0;
        	$inputLockId  = isset($_POST['lockId']) && $_POST['lockId']!='' ? $this->input->post('lockId') : 0;
        	$inputMsgId  = isset($_POST['msgId']) && $_POST['msgId']!='' ? $this->input->post('msgId') : 0;
    		if($hasModeratorAccess == 1 || $hasModeratorAccess == 2){
    			$status = $moderationLib->releaseLockOfEntityByCms($inputModeratorId, $inputLockId, $inputMsgId);
    			if($status){
    				echo 'success';
    			}else{
    				echo 'fail';
    			}
    		}
    		else
    			echo 'unauthorised';
    	}else{
    		die('error');
    	}
    }

    function userPointHistory($userId)
    {
    	$this->init();
    	if($this->userStatus!='false'){
    		$displayData = array();
    		$hasModeratorAccess = $this->getAccessLevel();
    		$displayData['validateuser']  = $this->userStatus;
    		if($hasModeratorAccess == 1 || $hasModeratorAccess == 2){
    			if(isset($_POST['showData']) && $_POST['showData']=='Get Data')
		    	{
		    		$userName = isset($_POST['userName'])?$this->input->post('userName',true):0;
			    	$userId = isset($_POST['userId'])?$this->input->post('userId',true):0;
			    	$emailId = isset($_POST['emailAddress'])?$this->input->post('emailAddress',true):0;
			    	$startDate = isset($_POST['startDate'])?$this->input->post('startDate',true):'';
			    	$endDate = isset($_POST['endDate'])?$this->input->post('endDate',true):'';
			    	$this->load->model('qnamodel');
			    	$result = array();
			    	if($userId == '' || (is_numeric($userId) && $userId >0))
			    	{
				    	$result = $this->qnamodel->getUserPointHistory($userName,$userId,$emailId,$startDate,$endDate);
				    	$urlEntityArray = array();
				    	foreach ($result as $key => $value) {
				    		$reference = '';
				    		if($value['entityId'] == 0 || $value['action'] == 'tagFollow' || $value['action'] == 'userFollow' || $value['action'] == 'deleteDiscussion' || $value['action'] == 'deleteQuestion'){
				    			$urlEntityArray[$key] = $value['entityId'];
				    		}
				    		else
				    		{
				    			if($value['fromOthers'] == 'user')
				    				$type = 'question';
				    			elseif($value['fromOthers'] == 'discussion')
				    				$type = 'discussion';
				    			if($value['entityId'] != $value['threadId'])
				    				$reference = '?referenceEntityId='.$value['entityId'];
				    			$urlEntityArray[$key]  = getSeoUrl($value['threadId'], $type, $value['msgTxt']).$reference;
				    		}
				    	}
				    }
			    	if(!empty($result)){
			    		$displayData['userHistory'] = $result;
			    		$displayData['url'] = $urlEntityArray;
			    	}
			    	else
			    		$displayData['userHistory'] = "No result found.";
		    	}
		    	$displayData['tabSelected'] = 5;
		    	$displayData['hasModeratorAccess'] = $hasModeratorAccess;
		    	$this->load->view('anaInternal/newModeration/userPointHistoryPanel', $displayData);
    		}else{
    			$this->showMPLoginPage();
            }
    	}else{
    		$this->showMPLoginPage();
        }
    }    

    function getSearchUserInfo()
    {
        $key = ($_POST['key'] !='') ? $this->input->post('key') : '';
        $filterBy = ($_POST['filter'] !='') ? $this->input->post('filter') : '';
        if($filterBy !='' && $key !='')
        {
        	$this->load->model('qnamoderationmodel');
        	$data['userPointDetail']['result'] = $this->qnamoderationmodel->getUserPointsOnSearch($key,$filterBy);
        	echo $this->load->view('anaInternal/newModeration/innerUserPointDetailPage',$data,true);
        }
    }

    function updatePoint(){
    	$userid = ($_POST['id'] !='') ? $this->input->post('id') : 0;
        $prevPoints = ($_POST['p'] !='') ? $this->input->post('p') : '';
        $currentPoints = ($_POST['c'] !='') ? $this->input->post('c') : '';
        if($userid != 0 && $prevPoints !='' && $currentPoints !=''){
        	$this->load->model('qnamoderationmodel');
        	echo $this->qnamoderationmodel->updateUserPoint($prevPoints,$currentPoints,$userid);
        }
    }

    public function deleteEntityLockFromCache($moderatorId){
    	$cacheLib = $this->load->library('cacheLib');
    	$key = 'userId-'.$moderatorId;
    	if($cacheLib->get($key) != 'ERROR_READING_CACHE'){
    		$cacheLib->clearCacheForKey($key);
    		echo 'key "'.$key.'" deleted';
    	}else{
    		echo 'key "'.$key.'" not found';
    	}
    }

    public function automoderationViewChangeData(){
    	$entityId = isset($_POST['entityId'])?$this->input->post('entityId'):'';
    	$automoderationFlag = isset($_POST['automoderationFlag'])?$this->input->post('automoderationFlag'):'';
    	$this->load->model('qnamoderationmodel');
    	$result  = $this->qnamoderationmodel->getAutomoderationMsgChange($entityId,$automoderationFlag);
    	echo json_encode($result);
   
    }

    function MISForListingANA()
    {
    	$this->init();
    	if($this->userStatus!='false'){
        	$this->load->model('qnamoderationmodel');
        	$hasModeratorAccess = $this->getAccessLevel();
        	$data = array();
        	$data['validateuser']  = $this->userStatus;
            $data['moderatorInfo'] = $moderatorInfo;
            if($hasModeratorAccess == 1){
            	$data['hasModeratorAccess'] = $hasModeratorAccess;
            	$param = array();
            	$param['startDate']      = $this->input->post('startDate');
        		$param['endDate']        = $this->input->post('endDate');
        		$param['quesType']       = $this->input->post('quesType');
        		$param['crAvailability'] = $this->input->post('crAvailability');
        		if(empty($param['startDate']) && empty($param['endDate'])){
        			$data['tabSelected'] = 7;
            		$this->load->view('anaInternal/newModeration/MISForListingANA', $data);
        		}else{
        			$this->getListingQuesDataForParams($param);
        		}
            }else{
               $this->showMPLoginPage();
            }
        }else{ 
            $this->showMPLoginPage();
        }
    }

    private function getListingQuesDataForParams($param){
    	$this->userStatus = $this->checkUserValidation();
    	if($this->userStatus!='false'){
        	$listingAnALib = $this->load->library('ListingAnALib');
        	$hasModeratorAccess = $this->getAccessLevel();
        	if($hasModeratorAccess == 1){
        		//set default values if not set
        		$param = $listingAnALib->setDefaultValues($param);

        		//get lisiting questions data tree and questions ids with posting time
        		$quesData = $listingAnALib->getQuestionsBasedOnParams($param);

        		//get answers count
        		$ansData  = $listingAnALib->getAnswerData($quesData['questions'], $param);
        		
        		//output in excel
        		$listingAnALib->generateDataInCSV($quesData['institutes'], $ansData, $param);
            }else{
               $this->showMPLoginPage();
            }
        }else{ 
            $this->showMPLoginPage();
        }
    }

    function checkAutoModeration(){
    	$msgId = $this->input->post('entityId');

    	$this->load->library(array('v1/AnACommonLib'));
        $this->anaCommonLib = new AnACommonLib();  
        $this->anamodel = $this->load->model('messageBoard/AnAModel');
        $userId = '0';

        $result = $this->anamodel->getMsgTextById($msgId);
        
        $msgTxt = $result['msgTxt'];
        $description = '';//$result['description'];

        $keyWord_Data = $this->anamodel->getAutoModerationKeywordData();
        $this->config->load('messageBoard/SuperlativeConfig');
        $superlativeList    = $this->config->item('superlativeList');
        $data = array();
        //check entity title for automoderation
        if(!empty($msgTxt)){
        	$updatedMsgTxt = $this->anaCommonLib->refineElementFromString($msgTxt,true);
	        $updatedMsgTxt = $this->anaCommonLib->autoModerationKeywordReplace($updatedMsgTxt, $keyWord_Data);
	        $updatedMsgTxt = $this->anaCommonLib->findSuperlative($updatedMsgTxt, $superlativeList);

	        $finalUpdatedMsgTxt = $this->anaCommonLib->runCleaningProcess($updatedMsgTxt);
	        if(empty($finalUpdatedMsgTxt)){
	            error_log('==ANA==Crons==messageAutoModerationCron==msgTxt=='.$updatedMsgTxt);
	        }
	        $updatedMsgTxt = empty($finalUpdatedMsgTxt) ? $updatedMsgTxt : $finalUpdatedMsgTxt;
	        $updatedMsgTxt  = $this->anaCommonLib->spellCheckString($updatedMsgTxt);   
        }
        $data['originalTitle'] = ($msgTxt) ? $msgTxt : ' NA';
	    $data['editedTitle']   = ($updatedMsgTxt) ? $updatedMsgTxt : ' NA';	

        // check description
        if(!empty($description)){
        	$updatedDesc = $this->anaCommonLib->refineElementFromString($description,true);
	        $updatedDesc = $this->anaCommonLib->autoModerationKeywordReplace($updatedDesc, $keyWord_Data);
	        $updatedDesc = $this->anaCommonLib->findSuperlative($updatedDesc, $superlativeList);


		    $finalUpdatedMsgTxt = $this->anaCommonLib->runCleaningProcess($updatedDesc);
	        if(empty($finalUpdatedMsgTxt)){
	            error_log('==ANA==Crons==messageAutoModerationCron==description=='.$updatedDesc);
	        }
	        $updatedDesc = empty($finalUpdatedMsgTxt) ? $updatedDesc : $finalUpdatedMsgTxt;

	        $updatedDesc  = $this->anaCommonLib->spellCheckString($updatedDesc); 
	        $data['originalDescription'] = $description;
	        $data['editedDescription']   = $updatedDesc;
	    }
        echo json_encode($data);
    }

    function saveReasonToRequest(){
    	$msgId = $this->input->post('msgId');
    	$reasonIds = $this->input->post('reasonIds');
    	$commentText = $this->input->post('commentText');
    	$userId = $this->input->post('userId');
    	$questionId = $this->input->post('questionId');
	$this->load->library('mailerClient');
       	$MailerClient = new MailerClient();

    	foreach ($reasonIds as $key => $value) {
    		if(!empty($value)){
    			$finalReasonIds[] = $value; 
    		}
    	}
    	$finalReasonIdsStr = implode(',', $finalReasonIds);
    	if(!empty($msgId) && !empty($finalReasonIds)){
	    	$this->load->model('qnamoderationmodel');
	    	$this->qnamoderationmodel = new qnaModerationModel();
    		$finalDataToInsert['entityId'] = $msgId;
    		$finalDataToInsert['reasonToEdit'] = $finalReasonIdsStr;	
    		$finalDataToInsert['comment'] = $commentText;	
    	}
		if(!empty($msgId) && !empty($finalReasonIdsStr) && !empty($userId)){
			$resultArr = $this->qnamoderationmodel->getEmailIdAndNameOfUser($userId);
			$userEmailId = $resultArr['email'];
			$userName = $resultArr['firstname'];
			if(empty($msgId) || empty($userId) || empty($reasonIds) || empty($questionId) || empty($userEmailId)){
				return;
			}
			$urlOfLandingPage = getSeoUrl($questionId, 'question').'?answerId='.$msgId;
			$questionTextArr = $this->qnamoderationmodel->getQuestionText($questionId);
			$questionText = $questionTextArr[0]['msgTxt'];
			if(!empty($userEmailId) && !empty($urlOfLandingPage)){
				$contentArr['seoURL'] = $urlOfLandingPage;
		        $contentArr['userEmailId'] = $userEmailId;
		        $contentArr['userName'] = $userName;
		        $contentArr['userProfileUrl'] = SHIKSHA_HOME.'/userprofile/'.$userId;
		        $contentArr['subject'] = 'Shiksha - Request to Improve your Answer';
		        $contentArr['commentText'] = $commentText;
		        $contentArr['questionText'] = $questionText;
		        $contentArr['reasonToEdit'] = $finalReasonIdsStr;
		        $contentArr['userId'] = $userId;
		        $contentArr['timeLimit'] = 48;

		        $mailStatus = Modules::run('systemMailer/SystemMailer/sendRequestToEditAnswerMailer',$contentArr);
		    }
		}
		if($mailStatus == 'Inserted Successfully'){
			$insertId = $this->qnamoderationmodel->saveEditReasons($finalDataToInsert);
		}
		if(!empty($insertId) && $insertId > 0){
			echo 'success';
		}else{
			echo 'fail';
		}
    }
}
