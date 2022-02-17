<?php

class AbroadExamPages extends MX_Controller {
    private $userStatus;
    private $validateuser;
    private $examPageObj;
    private $abroadExamPageBuilder;
    private $abroadExamPageCommonLib;
    private $abroadExamPageRepository;
    private $sectionName;
    private $SAContentModel;
    
    public function __construct(){
	parent::__construct();
	// prepare user data
    $this->userStatus = $this->prepareLoggedInUserData();
	$this->config->load('abroadExamPageConfig');
	$this->load->builder('AbroadExamPageBuilder', 'abroadExamPages');
	$this->abroadExamPageBuilder = new AbroadExamPageBuilder;
	$this->saContentLib 	= $this->load->library('blogs/saContentLib');
	// common abroad exam page library 
	$this->abroadExamPageCommonLib 	= $this->load->library('abroadExamPages/AbroadExamPageCommonLib');
	// abroad exam page repository
	$this->abroadExamPageRepository = $this->abroadExamPageBuilder->getAbroadExamPageRepository($this->abroadExamPageCommonLib);
	$this->load->helper('/shikshautility_helper');
	$this->load->model('blogs/sacontentmodel');
	$this->SAContentModel = new SAContentModel();
	$this->load->model('listing/abroadlistingmodel');
	$this->AbroadListingModel = new abroadlistingmodel();
    }
    
    /**
     * function to get exam page (home/section)
     * params: contentId / url as a string
     */
    function abroadExamPage($paramString, $contentId) {
    	$this->saContentLib->checkMigratedExamContentRedirection(getCurrentPageURLWithoutQueryParams());
    	show_404_abroad();
		// clean up contentId
		$contentId = preg_replace('/[^0-9]/','',$contentId);
		// Get section related data(ID and Name for url verification and identifying ID for section)
		$sectionData = $this->abroadExamPageCommonLib->getExamPageSectionData($paramString);
		$displayData = array();
		$displayData['trackForPages'] = true;
		if($_GET['sectionAbout'] === "1"){
			$displayData['scrollDown'] = "1";
		}
		
		//get complete exampage object
		$examPage = $this->abroadExamPageRepository->find($contentId,$sectionData['sectionId']); // second param will be section name/index
		//_p($examPage);die;
		if(empty($examPage) || $examPage == false)
		{
			show_404_abroad();
		}

		
		$recommendedUrl = $this->abroadExamPageCommonLib->examPageSectionURL($examPage,$sectionData['sectionName'],true);
		$seoData = $this->_getMetaDataAbroadExamPages($examPage,$sectionData['sectionId']);
		
		$seoData['canonicalUrl'] = $recommendedUrl;
		
		$displayData['validateuser'] = $this->validateuser;
		$displayData['loggedInUserData'] = $this->userStatus;
		
		$displayData['examPageObj'] = $examPage;
		$displayData['seoData'] = $seoData;
		$displayData['sectionData'] = $sectionData;
		
		//Get SubSectionData Depending on Section ID
		$displayData['subSectionDetails'] = $this->getSubSectionDetails($sectionData,$examPage);	
		$displayData['abroadExamPageTilesContent'] = $this->config->item('abroad_exam_page_section_details');
		$examSections = $examPage->getSections();
		$mainSectionContent = reset($examSections);
		$abroadExamPageTilesContent = $this->config->item('abroad_exam_page_tiles_content');
		
		// Prepare left side NavBar
		$displayData['sectionLinks'] = $this->getExamPageSectionLinks($examPage);
		$displayData['leftNavData'] = $this->_prepareNavBar($displayData,$examPage,$sectionData['sectionId']);
		
		$breadcrumbs = $this->_getBreadcrumbs($displayData,$sectionData);
		$displayData['breadcrumbs'] = $breadcrumbs;
		// Get download count of this guide
		$displayData['guideDownloadCount']=$this->abroadExamPageCommonLib->getExamPageDownloadCount($examPage->getExamPageId());
		// get comments & author Id
		$displayData['authorId'] = $examPage->getAuthorId();
		$displayData['commentData'] = $this->getComments(array('contentId'=>$contentId,'sectionId'=>$sectionData['sectionId'],'authorId'=>$displayData['authorId']));
		
		//load spamKeywords Config for filtering spam comments on article guide and exam page comment section
		//$this->config->load('studyAbroadSpamKeywordsConfig');
		//$spamKeywordsList = $this->config->item('spamKeywordsList');
        $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
        $spamKeywordsList = $saCMSToolsLib->getSpamKeywordsList();

		$displayData['spamKeywordsList']	= json_encode($spamKeywordsList);
		  
		$values = $this->AbroadListingModel->getUniversityCategorySubcategoryOfContent($contentId);

		//tracking  
		$this->_prepareTrackingData($displayData,$values);
		//load view
		$this->load->view("abroadExamPages/abroadExamPageOverview",$displayData);
    }
    
   		 private function _prepareTrackingData(&$displayData,$values)   
      	{  
         	$displayData['beaconTrackData'] = array(
                                              'pageIdentifier' => 'examPage',
                                              'pageEntityId' => $displayData['examPageObj']->getExamPageId(),
                                              'extraData' => array(
									                                    'categoryId' => $values['categoryId'],
									                                    'subCategoryId' => $values['subcatId'],
									                                    'LDBCourseId' => $values['ldb_course_id'],
									                                    'cityId' => $values['city_id'],
									                                    //'stateId' => '',
									                                    'countryId' => $values['country_id'],
									                                    //'courseLevel' => ''
									                                )
                                              );
      	} 

    private function prepareLoggedInUserData()
    {
		$this->validateuser = $this->checkUserValidation();
		if($this->validateuser !== 'false') {
			$this->load->model('user/usermodel');
			$usermodel = new usermodel;
			$userId 	= $this->validateuser[0]['userid'];
			$user 	= $usermodel->getUserById($userId);
			if(!is_object($user))
			{
				$loggedInUserData = false;
			}
			else
			{
				$name = $user->getFirstName().' '.$user->getLastName();
				$email = $user->getEmail();
				$userFlags = $user->getFlags();
				$isLoggedInLDBUser = $userFlags->getIsLDBUser();
				$loggedInUserData = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
				$pref = $user->getPreference();
				$loc = $user->getLocationPreferences();
				$isLocation = count($loc);
				if(is_object($pref)){
					$desiredCourse = $pref->getDesiredCourse();
				}else{
					$desiredCourse = null;
				}
				$loggedInUserData['desiredCourse'] = $desiredCourse;
				$loggedInUserData['isLocation'] = $isLocation;
			}
		}
		else {
			$loggedInUserData = false;
		}
		return $loggedInUserData;
    }
    
    
    private function _getBreadcrumbs(& $displayData,$sectionData)
    {
	$this->config->load('abroadExamPageConfig');
	$examPageConfig = $this->config->item('abroad_exam_page_section_details');
	$breadcrumbData = array();
	$breadcrumbData[] = array('title' => 'Home', 'url' => SHIKSHA_STUDYABROAD_HOME);
	if($sectionData['sectionId']==1){
	    $breadcrumbData[] = array('title' => $displayData['examPageObj']->getExamName().' Exam', 'url' => '');    
	}else{
	    $breadcrumbData[] = array('title' => $displayData['examPageObj']->getExamName().' Exam', 'url' => $displayData['examPageObj']->getExamPageURL());
	    
	    $currentTitle = $examPageConfig[$sectionData['sectionId']]['breadcrumbTitle'];
	    $currentTitle = str_ireplace('<exam-name>',$displayData['examPageObj']->getExamName(),$currentTitle);
	    $breadcrumbData[] = array('title' => $currentTitle, 'url' => '');    
	}	
	return $breadcrumbData;
	
    }
    
    function _getMetaDataAbroadExamPages($examPageObj,$sectionId){
		$abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
		$examList = $abroadListingCommonLib->getAbroadExamsMasterListFromCache();
		$this->config->load('abroadExamPageConfig');
		$examPageConfig = $this->config->item('abroad_exam_page_section_details');
		$examPageSeoDesc = $this->config->item('seoDescription');
		$examName = $examPageObj->getExamName();
		$examShortName = '';
		$examFullName = '';
		$seoData = array();
		foreach($examList as $exam){
			if(strtolower($exam['exam']) == strtolower($examName)){
				if($examPageObj->getSeoTitle() != ''){
					$seoData['seoTitle'] = $examPageObj->getSeoTitle();
				}else{
					$seoData['seoTitle'] = $examPageConfig[$sectionId]['seoTitle'];
					$seoData['seoTitle'] = str_ireplace('<exam-name>',$exam['exam'],$seoData['seoTitle']);
					$seoData['seoTitle'] = str_ireplace('<exam-full-form>',$exam['examName'],$seoData['seoTitle']);
				}
				if($examPageObj->getSeoDescription() != ''){
					$seoData['seoDescription'] = $examPageObj->getSeoDescription();
				}else{
				    if(array_key_exists($exam['exam'],$examPageSeoDesc) && $sectionId==1)
				    {
					$seoData['seoDescription'] = $examPageSeoDesc[$exam['exam']];
				    }
				    else
				    {
					$seoData['seoDescription'] = $examPageConfig[$sectionId]['seoDescription'];
					$seoData['seoDescription'] = str_ireplace('<exam-name>',$exam['exam'],$seoData['seoDescription']);
					$seoData['seoDescription'] = str_ireplace('<exam-full-form>',$exam['examName'],$seoData['seoDescription']);
				    }
				}
				break;
			}
		}
		return $seoData;
    }

	private function _createLeftNavBar(& $displayData, $examPage){
		_p($examPage);die;
		
	}
	
	private function _prepareNavBar($displayData, $examPage, $sectionId){
		$sections = $this->config->item('abroad_exam_page_section_details');
		$leftNavData = array();
		foreach($sections as $key => $section){
			$leftNavData[$key] = array();
			$leftNavData[$key]['label'] = str_replace('<exam-name>',$examPage->getExamName(),$section['title']);
			if($key == $sectionId){
				$leftNavData[$sectionId]['url'] = '';
				$leftNavData[$sectionId]['active'] = 1;
				$subsections = $examPage->getSections();
				$leftNavData[$sectionId]['subsections'] = array();
				foreach($subsections as $subkey => $subsection){
					if($subsection == reset($subsections)) {continue;}	// The first is the main section, not a subsection
					$leftNavData[$sectionId]['subsections'][$subkey]['heading'] = $subsection['heading'];
				}
				
			}else{
				$leftNavData[$key]['url'] = $displayData['sectionLinks'][$key];
			}
		}
		$leftNavData = array_slice($leftNavData,0,count($leftNavData)-2);
		return $leftNavData;
	}
	
	public function getExamPageSectionLinks($examPage){
		$sections = $this->config->item('abroad_exam_page_section_details');
		$examId = $examPage->getExamPageId();
		$examName = $examPage->getExamName();
		$urlList = array();
		foreach($sections as $key => $section){
			$urlList[$key] =  $this->abroadExamPageCommonLib->examPageSectionURL($examPage,$section['urlPattern']);
		}
		return $urlList;
	}
	
	public function getSubSectionDetails($sectionData,$examPage){

		if($sectionData['sectionId'] == 8){		// Colleges Page
			$subSectionData = $this->_getCollegeExamData($examPage);
		}
	    elseif($sectionData['sectionId']==9)
	    {
			//Code for Article section will come here
			$subSectionData = $this->abroadExamPageCommonLib->getExamRelatedArticles($examPage);
	    }
	    else
	    {
			$subSectionData = $examPage->getSections();
	    }
	    
	    return $subSectionData;
	}
    
    /*
     * function to verify captcha & create cookie for username & email
     */
    public function addUserValues()
    {
	$sacontent_userName	= $this->input->post('sacontent_userName');
	$sacontent_userEmail	= $this->input->post('sacontent_userEmail');
	$captcha 		= $this->input->post('captchaVal');
	$secCodeIndex = 'secCodeForContentComment';
    	if(!empty($captcha)) {
    		$secCode = $captcha ;
    		$captchaRes = verifyCaptcha($secCodeIndex, $secCode);
    	}
	if($captchaRes)
	{
	    //set cookie for username & email
	    setcookie('sacontent_userName', $sacontent_userName, time()+60*60*24*30,'/',COOKIEDOMAIN);
	    setcookie('sacontent_userEmail',$sacontent_userEmail,time()+60*60*24*30,'/',COOKIEDOMAIN);
	    $_COOKIE['sacontent_userName'] =  $sacontent_userName;
	    $_COOKIE['sacontent_userEmail'] = $sacontent_userEmail;
	    echo json_encode(array('sacontent_userName'=>$sacontent_userName, 'sacontent_userEmail'=>$sacontent_userEmail));
	}
	else
	{
	    echo json_encode('error');
	}
	
    }
    /*
     * function to prepare comment & other values to be inserted into the database
     * @dependency : sacontentmodel
     */
    public function addCommentForExamPage()
    {
		$postArray = $this->prepareDataForCommentInsertion();
		$authorId = $postArray['authorId'];
		unset($postArray['authorId']);
		
		//check if comment was a spam comment before saving
		//$this->config->load('studyAbroadSpamKeywordsConfig');
		//$spamKeywordsList = $this->config->item('spamKeywordsList');
        $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
        $spamKeywordsList = $saCMSToolsLib->getSpamKeywordsList();

		$commentText = $postArray['commentText'];
		for($i=0; $i< count($spamKeywordsList); $i++)
		{
		   $pos = strpos($commentText, $spamKeywordsList[$i]);   ////haystack,needle
		   if($pos!= false)
		   {
			echo json_encode(array("status"=>"spam"));
			return;
		   }
		}
		// add comment to db
		$saveAsDraft = $this->input->post('saveAsDraft',true);
		$commentId = $this->SAContentModel->addComment($postArray,$saveAsDraft);
		// we have user but still username & email are null
		if(($this->userStatus !== 'false' && $this->userStatus !== false)&& 
			(
			is_null($postArray['userName']) || 
			$postArray['userName'] =='' || 
			is_null($postArray['emailId']) || 
			$postArray['emailId'] == ''
			)
		)
		{
			echo json_encode(array('status'=>'missinguser'));
			return false;
		}
		if($saveAsDraft){
			echo json_encode(array('status'=>"draft",'commentInsertId'=>$commentId));
			return;
		}
		//_p($postArray);
		$displayData = $postArray;
		$displayData['commentId'] = $commentId;
		$email = ""; 
		if($this->userStatus != 'false') {
			$cookieStr =  $this->userStatus[0]['cookiestr'];
			$cookieStrArray = explode('|',$cookieStr);
			$email = $cookieStrArray[0];
			// remove cookie once a user has logged in
			if(!empty($_COOKIE['sacontent_userName'])){
				setcookie('sacontent_userName' ,'', time()-60*60*24*30,'/',COOKIEDOMAIN);
				setcookie('sacontent_userEmail','', time()-60*60*24*30,'/',COOKIEDOMAIN);
			}
		}
		$displayData['email'] = $email;
		$displayData['userStatus'] = $this->userStatus;
		$this->sendMailsForCommentOrReply($displayData);
		// incase of replies we do not refresh the page..
		if($postArray['parentId']>0){
			$replies = $this->getComments(array('contentId'=>$postArray['contentId'],'authorId'=>$authorId,'sectionId'=>$postArray['sectionId']),0,array($postArray['parentId']));
			$replyData = reset($replies['data']);
			$userIds = array_map(function($a){return $a['userId'];},$replyData['replies']);
			$contentPageLib = $this->load->library('contentPage/ContentPageLib');
			$userData = $contentPageLib->getUserInfoForComments(array_unique($userIds));
			$html = $this->load->view('abroadExamPages/widget/abroadExamPageCommentReply',array('replies'=>$replies['data'][$postArray['parentId']]['replies'],'commentsUserData'=>$userData),true);
                        echo json_encode(array('status'=>'success','html'=> ($html)));
                        return;
		}
                echo json_encode(array('status'=>'success'));
		
    }
    /*
     * function to get & prepare data for comment insertion
     */
    private function prepareDataForCommentInsertion()
    {
	$dataArray = array();
	$dataArray['contentId'] = $this->input->post('contentId');
	$dataArray['parentId'] = $this->input->post('parentId');
	$dataArray['sectionId'] = $this->input->post('sectionId');
	$dataArray['authorId'] = $this->input->post('authorId');
	$dataArray['visitorSessionid'] = getVisitorSessionId();
	$dataArray['tracking_keyid'] = $this->input->post('trackingPageKeyId');
	
	if($this->userStatus != false)
	{
	    if(!empty($_COOKIE['sacontent_userName'])){
		setcookie('sacontent_userName', $sacontent_userName, time()-60*60*24*30,'/',COOKIEDOMAIN);
		setcookie('sacontent_userEmail',$sacontent_userEmail,time()-60*60*24*30,'/',COOKIEDOMAIN);
	    } // remove cookie once a user has logged in
	    $dataArray['userName'] 	= $this->userStatus['name'];
	    $dataArray['emailId'] 	= $this->userStatus['email'];
	    $dataArray['userId'] 	= $this->userStatus['userId'];
	}
	else{
	    $sacontent_userName = $this->input->post('sacontent_userName');
	    $sacontent_userEmail = $this->input->post('sacontent_userEmail');
	    $dataArray['userName'] 	= ($sacontent_userName == ''?$_COOKIE['sacontent_userName']:$sacontent_userName);
	    $dataArray['emailId'] 	= ($sacontent_userEmail == ''?$_COOKIE['sacontent_userEmail']:$sacontent_userEmail);
	    $dataArray['userId'] 	= 0;
	}
	$dataArray['commentText'] = base64_decode($this->input->post('commentText'));
	$dataArray['status'] = 'live';
	return $dataArray;
    }
    /*
     * function to get comments
     * @params: array of ($contentId, $authorId, $sectionId) , page no (optional), $commentIds(to get replies using this function -optional)
     */
    private function getComments($examPageData, $pageNo = 0,$commentIds = array())
    {
	$contentId = $examPageData['contentId'];
	$authorId  = $examPageData['authorId'] ;
	$sectionId = $examPageData['sectionId'];
	
    	$returnArray = array();
    	$commentsArray = array();
    	$replyArray = array();
	
    	$userStatus = $this->checkUserValidation();
	
    	$pageStart = $pageNo*50;
	
	if(count($commentIds) == 0)
	{
	    $comments = $this->SAContentModel->getComments($contentId,'comment',array(),$pageStart,$sectionId);
	    foreach($comments['data'] as $comment)
	    {
		$commentsArray[$comment['commentId']] = $comment;
		$commentsArray[$comment['commentId']]['replies'] = array(); // add an assoc key for replies within comment
		// add deletion flags
		$commentsArray[$comment['commentId']]['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($userStatus,array('authorId'=>$authorId,'userId'=>$comment['userId'],'emailId'=>$comment['emailId']));
	    }
	    $commentIds = array_keys($commentsArray);
	}
	else
	{   // set the comment ids as key so that replies can be inserted as an array
	    foreach($commentIds as $key => $value)
	    {
		$commentsArray[$value]= array('replies'=>array());
	    }
	}
	
    	if(!empty($commentIds))
	{
	    $replies = $this->SAContentModel->getComments($contentId,'reply',$commentIds);
	    $replyArray = $replies['data'];
	    // add deletion flags
	    foreach($replyArray as $key => $value)
	    { 
		$replyArray[$key]['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($userStatus,array('authorId'=>$authorId,'userId'=>$value['userId'],'emailId'=>$value['emailId']));
	    }
    	}
	foreach($replyArray as $reply)
	{
	    array_push($commentsArray[$reply['parentId']]['replies'],$reply) ;
	}
    	$returnArray['data'] = $commentsArray;
    	$returnArray['total'] = $comments['total'];
    	return $returnArray;
    }
    /*
     * function to load more comments
     */
    public function loadMoreComments() {
    	$contentId = $this->input->post('contentId');
    	$sectionId = $this->input->post('sectionId');
    	$authorId  = $this->input->post('authorId');
    	$pageNo = $this->input->post('pageNum');
	$commentsArray = $this->getComments(array('authorId'=>$authorId,'contentId'=>$contentId,'sectionId'=>$sectionId),$pageNo);
	$userData = array();
	if(count($commentsArray['data'])>0)
	{
		$userIds = array();
		foreach($commentsArray['data'] as $comment)
		{
			// get comment userids
			if(is_numeric($comment['userId']) && $comment['userId']>0){
				$userIds[] = $comment['userId'];
			}
			// get reply userids
			if(count($comment['replies'])>0){
				$replyUserIds = array();
				$replyUserIds = array_filter(array_map(function($a){ if($a['userId']>0){ return $a['userId']; } }, $comment['replies']));
				$userIds = array_merge($userIds,$replyUserIds);
			}
			//_p($userIds);
		}

		$contentPageLib = $this->load->library('contentPage/ContentPageLib');
		$userData = $contentPageLib->getUserInfoForComments(array_unique($userIds));
	}
	
	//_p($userData);
	echo $this->load->view('abroadExamPages/widget/abroadExamPageComments',array('commentData'=>$commentsArray,'commentsUserData'=>$userData));
    }
    /*
     * function to send mails whenever a user comments on exam page or replies to a comment on the exam page
     */
    public function sendMailsForCommentOrReply($dataArray)
    {	// get a mailer client library
	$this->load->library('MailerClient');
	$MailerClient = new MailerClient();
	// get author details
	$authorInfo = $this->SAContentModel->getAuthorInfo($dataArray);
	$contentEmailIds = array('harleen.bedi@shiksha.com','swameeka.medhi@shiksha.com', 'soma.chaturvedi@shiksha.com','shruti.maheshwari@shiksha.com', 'k.akhil@shiksha.com', 'iqra.khan@shiksha.com');
	if($dataArray['parentId']== 0)
	{
	   $emailIdarray= array_merge($contentEmailIds,array($authorInfo[0]['email']));
	}
	else
	{   // get details of user that posted the (non-reply)comment
	    $commentUserInfoArray = $this->SAContentModel->getCommentUserInfo($dataArray['parentId']);
	    $emailIdarray= array_merge($contentEmailIds,array($commentUserInfoArray[0]['emailId']));
	}
	$emailIdarray = array_unique($emailIdarray);// for author being in the list already
	// since we do not store the section wise url anywhere, we generate it now & for this..
	$contentArray = array();
	//$examPage = $this->abroadExamPageRepository->find($dataArray['contentId'],$dataArray['sectionId']); // second param will be section name/index
	// .. get section name using config & section Id ..
	//$sectionData = $this->config->item('abroad_exam_page_section_details');
	// get exam content
	$examContent = $this->SAContentModel->getContentUrlAndTitleForMailer($dataArray['contentId']);
	$examContent = reset($examContent);
	foreach($emailIdarray as $key=>$emailId)
	{
	    $contentArray['articleType']    = $examContent['type'];
	    $contentArray['articleTitle']   = $examContent['strip_title'];
	    $contentArray['name']	    = $authorInfo[0]['firstname'];
	    $contentArray['toEmail'] 	    = $authorInfo[0]['email'];
	    $contentArray['commentText']    = $dataArray['commentText'];
	    $contentArray['contentId']	    = $dataArray['contentId'];
	    if($dataArray['parentId']== 0)
	    {
		$autoLoginUrl = $MailerClient->generateAutoLoginLink(1,$emailId,$examContent['contentURL']);
		$contentArray['articleUrl'] = $autoLoginUrl;
		$contentArray['userName'] = $dataArray['userName'];
		Modules::run('systemMailer/SystemMailer/articleCommentMail',$emailId,$contentArray);
	    }
	    else
	    {   // if the user is a registered user or owner of the (parent) comment (but not among the content team/author)
		if($commentUserInfoArray[0]['userId'] != 0 || $commentUserInfoArray[0]['emailId']!= $emailId){
		    $autoLoginUrl = $MailerClient->generateAutoLoginLink(1,$emailId,$examContent['contentURL']);
		}
		else // other wise simply open the page(when the comment being replied is made by a non registered user(the one we keep in cookies)
		{   
		    $autoLoginUrl = $examContent['contentURL'];
		}
		// when mail is going to author (reply is being made to a comment made by author of the exam page)
		if($commentUserInfoArray[0]['emailId'] != $emailId){
		     $contentArray['replyUserName'] = $authorInfo[0]['firstname'];
		     $contentArray['type'] = 'author';
		     $contentArray['userName'] = $dataArray['userName'];
		} 
		// when mail goes to the user
		else
		{
		    $contentArray['userName'] =  $dataArray['userName'];
		    $contentArray['type'] = '';
		    $contentArray['replyUserName'] = $commentUserInfoArray[0]['userName'];
		}
		$contentArray['articleUrl'] = $autoLoginUrl;
		$contentArray['replyText'] = $commentUserInfoArray[0]['commentText'];
		Modules::run('systemMailer/SystemMailer/articleReplyMail',$emailId,$contentArray);
	    } //end: if-else
	}//end: foreach
    }
    /*
     * function to delete comment & track deletion (separate table)
     */
    function deleteComment()
    {
	$userStatus = $this->checkUserValidation();
	if($userStatus != 'false')
	{
	    $userId = $userStatus[0]['userid'];
	    $userName = $userStatus[0]['firstname'].' '.$userStatus[0]['lastname'];
	    $cookieStrArray = explode('|',$userStatus[0]['cookiestr']);
	    $userEmail = $cookieStrArray[0];
	}
	if($userEmail == '' )
	{
	    $userEmail = $_COOKIE['sacontent_userEmail'];
	    $userName  = $_COOKIE['sacontent_userName'];
	}
    	$data['type'	 ]  = $this->input->post('type');
    	$data['commentId']  = $this->input->post('commentId');
    	$data['sectionId']  = $this->input->post('sectionId');
    	$data['contentId']  = $this->input->post('contentId');
    	$data['authorId']   = $this->input->post('authorId');
    	$data['userId']     = $this->input->post('userId');
    	$data['emailId']    = $this->input->post('emailId');
		$isUserEligible     = $this->saContentLib->checkUserEligibilityForCommentDeletion($userStatus,$data);
	if(!$isUserEligible){
	    echo 'error';
	    return false;
	}
    	// delete call to the model
    	$this->SAContentModel->deleteComment($data['type'], $data['commentId'], $data['contentId'], $data['sectionId']);
	// delete call to delete any underlying replies in case of comment deletion
	$abroadExamPageModel = $this->load->model('abroadexampagemodel');
	if($data['type'] == 'comment')
	{
	    $replyIds = $abroadExamPageModel->deleteReplies($data['commentId']);
	}_p($replyIds);
	// track comment deletion
	$dataToBeTracked = array(
				 'userId'=>	$userId,
				 'userName'=>	$userName,
				 'userEmail'=>	$userEmail,
				 'commentId'=>	$data['commentId'],
				 'replies'=>	$replyIds
				 );
	$abroadExamPageModel->trackCommentDeletion($dataToBeTracked);
    	echo 1;
    }
}
