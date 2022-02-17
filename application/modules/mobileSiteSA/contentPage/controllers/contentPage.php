<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ContentPage extends ShikshaMobileWebSite_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->contentpagelib = $this->load->library('contentPage/ContentPageLib');
		$this->load->config('studyAbroadMobileConfig');
	}
	
	function getArticleDetails($articleId)
	{
		$displayData = array();
		$displayData['trackForPages'] = true; //For JSB9 Tracking
		$this->_checkContentRedirection($articleId);
		$saContentLib = $this->load->library('blogs/saContentLib');
		$saContentLib->checkMigratedExamContentRedirection(getCurrentPageURLWithoutQueryParams());
		$displayData['contentId'] = $articleId;
		$contentDetails = $this->contentpagelib->getContentDetails($articleId);
        $this->contentpagelib->formatContentSectionDetails($contentDetails);
		$contentDetails['data']['contentImageURL'] = resizeImage($contentDetails['data']['contentImageURL'],"300x200"); // utility helper
		$saContentLib->prepareDownloadGuideWidget($contentDetails,true);
		$displayData['scholarshipCardData'] = $contentDetails['scholarshipCardData'];
		$displayData['scholarshipSliderTitle'] = $contentDetails['scholarshipSliderTitle'];
		unset($contentDetails['scholarshipCardData']);
		unset($contentDetails['scholarshipSliderTitle']);
		$displayData['content'] 	= $contentDetails;
		$feedbackCookie = $this->input->cookie('feedback_article_'.$articleId, TRUE);
		if(isset($feedbackCookie))
		{
		     $feedbackCookieArr = explode("|",$feedbackCookie);
		     $displayData['status'] = $feedbackCookieArr[1];		     
		}
		$displayData['comments'] 	= $this->contentpagelib->getCommentsForContent($articleId,1);
		$displayData['browsewidget'] 	= $this->contentpagelib->getBrowseWidgetData($articleId);
		$googleRemarketingData 		= $this->contentpagelib->getRemarketingDataForContent($articleId);
		//load spamKeywords Config for filtering spam comments on article guide and exam page comment section
		//$this->config->load('studyAbroadSpamKeywordsConfig');
		//$spamKeywordsList 			= $this->config->item('spamKeywordsList');
        $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
        $spamKeywordsList = $saCMSToolsLib->getSpamKeywordsList();

		$displayData['spamKeywordsList']	= json_encode($spamKeywordsList);
		$this->contentpagelib->getSeoDetailsForContent($displayData);
		$levelTwoNavBarData = $this->contentpagelib->getLevelTwoNavBarLinksByContentId($displayData['content']['data']['content_id']);
		if($levelTwoNavBarData !== false)
        {
            $displayData['levelTwoNavBarData'] = $levelTwoNavBarData;
        }
		//tracking
		$this->_prepareTrackingData($displayData,$googleRemarketingData);
		$this->load->view('contentPage/articlePageOverview',$displayData);
	}
	
	function getGuideDetails($guideId)
	{
		$displayData = array();
		$displayData['trackForPages'] = true; //For JSB9 Tracking
		$this->_checkContentRedirection($guideId);
		$contentDetails = $this->contentpagelib->getContentDetails($guideId);
		$this->contentpagelib->formatContentSectionDetails($contentDetails);
		$displayData['contentId'] = $guideId;
		$displayData['content'] = $contentDetails;
		$contentDetails['data']['contentImageURL'] = resizeImage($contentDetails['data']['contentImageURL'],"300x200");
		$feedbackCookie = $this->input->cookie('feedback_article_'.$guideId, TRUE);
		if(isset($feedbackCookie))
		{
		     $feedbackCookieArr = explode("|",$feedbackCookie);
		     $displayData['status'] = $feedbackCookieArr[1];		     
		}

		$displayData['comments'] 		= $this->contentpagelib->getCommentsForContent($guideId,1);
		$displayData['browsewidget'] 	= $this->contentpagelib->getBrowseWidgetData($guideId);
		
		$googleRemarketingData = $this->contentpagelib->getRemarketingDataForContent($guideId);

		//load spamKeywords Config for filtering spam comments on article guide and exam page comment section
		//$this->config->load('studyAbroadSpamKeywordsConfig');
		//$spamKeywordsList 			= $this->config->item('spamKeywordsList');
        $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
        $spamKeywordsList = $saCMSToolsLib->getSpamKeywordsList();

        $displayData['spamKeywordsList']	= json_encode($spamKeywordsList);
		
		$this->contentpagelib->getSeoDetailsForContent($displayData);
		$this->_getLoggedInUserDetails($displayData);
		
		//tracking
		$this->_prepareTrackingData($displayData,$googleRemarketingData);
		$displayData['guideDownloadCount'] = $this->contentpagelib->getContentDownloadCount($guideId);
        $levelTwoNavBarData = $this->contentpagelib->getLevelTwoNavBarLinksByContentId($displayData['content']['data']['content_id']);
        if($levelTwoNavBarData !== false)
        {
            $displayData['levelTwoNavBarData'] = $levelTwoNavBarData;
        }
		$displayData['stickyDownloadTrackingId'] = '';
		$this->load->view('contentPage/guidePageOverview',$displayData);
		
	}

	 private function _prepareTrackingData(&$displayData,$googleRemarketingData)   
        {
            $displayData['googleRemarketingParams'] = array(
														"categoryId" 		=> implode(',', array_unique($googleRemarketingData['categoryId'])),
														"subcategoryId" 	=> implode(',', array_unique($googleRemarketingData['subCategoryId'])),
														"desiredCourseId" 	=> implode(',', array_unique($googleRemarketingData['ldbCourseId'])),
														"countryId" 		=> implode(',', array_unique($googleRemarketingData['countryId'])), 
														"cityId" 		=> 0
														);    
			$displayData['beaconTrackData'] = array(
                                              'pageIdentifier' => $displayData['content']['data']['type'].'Page',
                                              'pageEntityId' => $displayData['content']['data']['content_id'],
                                              'extraData' => array(
									                                    'categoryId' => $googleRemarketingData['categoryId'][0],
									                                    'subCategoryId' =>$googleRemarketingData['subCategoryId'][0],
									                                    'LDBCourseId' => $googleRemarketingData['ldbCourseId'][0],
									                                    //'cityId' => $displayData['googleRemarketingParams']['cityId'],
									                                    'countryId' => $googleRemarketingData['countryId'][0]
									                                )
                                              );                                             
        }

	
	function _checkContentRedirection($contentId)
	{
		if($contentId=='' || !is_numeric($contentId)){
			show_404_abroad();
		}
		if($contentId=='22'){
			$url = SHIKSHA_STUDYABROAD_HOME.'/-articlepage-280';
			header("Location:$url ",TRUE,301);
			exit;                
		}
		$this->config->load('abroadApplyContentConfig');
		$mappedContent = $this->config->item('articleGuideToApplyContentMapping');
		if(!empty($mappedContent[$contentId])){
			$typeId = $mappedContent[$contentId];
			$contentId = substr($typeId,1);
			$this->load->model('sacontentmodel');
			$this->SAContentModel = new SAContentModel();
			$url = $this->SAContentModel->getURLForContent($contentId);
			if(!empty($url)){
				header("Location:$url ",TRUE,301);
			}else{
				show_404_abroad();
			}
		}
		//Old Exam guide to New Exam Page Mapping and 301 redirection
		$this->config->load('abroadExamPageConfig');
		$oldExamGuideToNewExamPageMappingIds = $this->config->item('abroad_oldExam_to_newExamPage_idMapping');
		if(array_key_exists($contentId,$oldExamGuideToNewExamPageMappingIds))
		{
		      $contentId = $oldExamGuideToNewExamPageMappingIds[$contentId];
		}
	}
	
	function _getLoggedInUserDetails(&$displayData){
	  
	  $displayData['validateuser'] = $this->checkUserValidation();
	  if($displayData['validateuser'] !== 'false') {
	       $this->load->model('user/usermodel');
	       $usermodel = new usermodel;
	       
	       $userId = $displayData['validateuser'][0]['userid'];
	       $user = $usermodel->getUserById($userId);
			if (!is_object($user)) {
				$displayData['loggedInUserData'] = false;
			}
			else {
				$name = $user->getFirstName().' '.$user->getLastName();
				$email = $user->getEmail();
				$userFlags = $user->getFlags();
				$isLoggedInLDBUser = $userFlags->getIsLDBUser();
				$displayData['loggedInUserData'] = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
				$displayData['checkIfLDBUser'] = $usermodel->checkIfLDBUser($userId);
				$pref = $user->getPreference();
				$loc = $user->getLocationPreferences();
				$isLocation = count($loc);
				if(is_object($pref)){
					$desiredCourse = $pref->getDesiredCourse();
				}else{
					 $desiredCourse = null;
				}
				$displayData['loggedInUserData']['desiredCourse'] = $desiredCourse;
				$displayData['loggedInUserData']['isLocation'] = $isLocation;
			}
	  }
	  else {
	       $displayData['loggedInUserData'] = false;
	       $displayData['checkIfLDBUser'] = 'NO';
	  }
		
	}
	
	public function loadMoreComments(){
		$contentId = $this->input->post('contentId');
		if($contentId <=0){
			echo json_encode(array('html' => '', 'totalCount' => 0));
		}
		$pageNumber = $this->input->post('pageNumber');
		if(empty($pageNumber)) $pageNumber = 1;
		$sectionId = $this->input->post('sectionId');
		if(empty($sectionId)) $sectionId = 0;
		$data = $this->contentpagelib->getCommentsForContent($contentId,$pageNumber,$sectionId);
		$html = $this->load->view('widgets/commentBlock',array('comments'=>$data),true);
		echo json_encode(array('html'=>$html,'totalCount'=>$data['total']));
	}
	
	public function submitComment(){
            
            $postData = json_decode($this->input->post("data"),true);
            $validateuser = $this->checkUserValidation();
            if($validateuser=="false" && $this->contentpagelib->checkIfRegistrationRequiredForComment($postData['contentId'])=="true"){
                echo "false";
                return;
            }
            if($validateuser!='false'){
                $postData['userId']     = $validateuser[0]['userid'];
                $postData['userName']   = $validateuser[0]['firstname'].' '.$validateuser[0]['lastname'];
				$postData['emailId']    = reset(explode("|",$validateuser[0]['cookiestr']));
				// remove cookie once a user has logged in
				if(!empty($_COOKIE['sacontent_userName'])){
					setcookie('sacontent_userName' ,'', time()-60*60*24*30,'/',COOKIEDOMAIN);
					setcookie('sacontent_userEmail','', time()-60*60*24*30,'/',COOKIEDOMAIN);
				}
			$userData = $this->contentpagelib->getUserInfoForComments(array($postData['userId']));
			$userData = reset($userData);
		}else{
			$this->userProfilePageLib = $this->load->library('userProfilePage/userProfilePageLib');
			$imageUrl = $this->userProfilePageLib->prependDomainToUserImageUrlInComments('/public/images/studyAbroadCounsellorPage/profileDefaultNew1_s.jpg');
			$userData = array('imageUrl'=>$imageUrl);
		}
              //check if comment was a spam comment before saving
              //$this->config->load('studyAbroadSpamKeywordsConfig');
              //$spamKeywordsList = $this->config->item('spamKeywordsList');
              $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
              $spamKeywordsList = $saCMSToolsLib->getSpamKeywordsList();

              $commentText = $postData['commentText'];
              for($i=0; $i< count($spamKeywordsList); $i++)
              {
                 $pos = strpos($commentText, $spamKeywordsList[$i]);   ////haystack,needle
                 if($pos!==false)
                 {
                    echo json_encode(array('result'=>'spam'));
                    return;
                 }
              }

            $commentId =  $this->contentpagelib->submitComment($postData);
            $postData['commentId'] = $commentId;
            $this->sendMailForComment($postData);
            if($commentId !== false && $commentId > 0){
                    echo json_encode(array('result'=>'success','data'=>$userData));
            }
            else{
                    echo json_encode(array('result'=>'model fail'));
            }
	}
    
    public function emailGuideToUser($guideId,$trackingPageKeyId){
        $displayData['validateuser'] = $this->checkUserValidation();
        if($displayData['validateuser'] !== 'false'){
            if($guideId == '' || empty($guideId)){
                echo "-1";
                exit(0);
            }
            $response = $this->contentpagelib->emailGuideToUser($guideId,$displayData['validateuser'],$trackingPageKeyId);
            if($response == 1){
                echo "1";
                exit(0);
            }else{
                echo "-1";
                exit(0);
            }
        }else{
            echo "-1";
            exit(0);
        }
    }
	
	private function sendMailForComment($postData){
                $dataArray = array();
                $postArray = array();
                $dataArray['parentId'] = $postArray['parentId'] = $postData['parentId'];
                $dataArray['contentId'] = $postArray['contentId'] = $postData['contentId'];
                $dataArray['commentId'] = $postData['commentId'];
                $this->userStatus = $this->checkUserValidation();
                if($this->userStatus != "false" && $this->userStatus !=false){
                        $this->userStatus = reset($this->userStatus);
                }
                $dataArray['userStatus'] = $this->userStatus;
                if($this->userStatus!="false" && $this->userStatus != false){
                        $dataArray['userName'] = $postArray['userName'] = $this->userStatus['displayname'];
                        $dataArray['emailId'] = $postArray['emailId'] = reset(explode('|',$this->userStatus['cookiestr']));
                        $dataArray['email']     = reset(explode('|',$this->userStatus['cookiestr']));
                        $dataArray['userId'] = $postArray['userId']= $this->userStatus['userid'];
                }else{
                        $dataArray['userName'] = $postArray['userName'] = $_COOKIE['sacontent_userName'];
                        $dataArray['emailId'] = $postArray['emailId']   = $_COOKIE['sacontent_userEmail'];
                        $dataArray['email']     = $_COOKIE['sacontent_userEmail'];
                        $dataArray['userId'] = $postArray['userId'] = 0;
                }
                $dataArray['commentText'] = $postArray['commentText'] = $postData['commentText'];
                $dataArray['status'] = $postArray['status'] = $postData['status'];
                if($postData['parentId'] == 0){
                        $dataArray['type'] = "comment";
                }else{
                        $dataArray['type'] = "reply";
                }
                Modules::run("blogs/SAContent/sendMailersForCommentOrReply",$dataArray,$postArray);
        }
	public function getContentDownloadCount($guideId){
		echo $this->contentpagelib->getContentDownloadCount($guideId);
	}
        
        public function  checkIfRegistrationRequiredForComment(){
            $contentId  = $this->input->post('contentId',true);
            echo $this->contentpagelib->checkIfRegistrationRequiredForComment($contentId);
        }
}