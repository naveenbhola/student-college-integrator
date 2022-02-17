<?php
class AbroadExamContent extends MX_Controller
{
	function __construct(){
		parent::__construct();
		$this->saContentLib = $this->load->library('blogs/saContentLib');
		$this->load->model('blogs/sacontentmodel');
		$this->SAContentModel = new SAContentModel();
		$this->abroadExamPageCommonLib = $this->load->library('abroadExamPages/AbroadExamPageCommonLib');
		$this->applyContentLib = $this->load->library('applyContent/ApplyContentLib');

		$this->load->helper('/shikshautility_helper');
	}

	public function index($examName){
		$this->userStatus = $this->_prepareLoggedInUserData();
		$displayData = $this->_validateExamContentUrl($examName);
		if($displayData === false){
			show_404_abroad();
			return false;
		}
		$this->_prepareSEOData($displayData);
		$this->_prepareBeaconTrackData($displayData);
        $this->contentPageLib = $this->load->library('contentPage/ContentPageLib');
        $displayData['browsewidget'] 	= $this->contentPageLib->getBrowseWidgetData($displayData['contentDetails']['content_id'],true);
		$this->_prepareCommentSectionData($displayData);
		$this->_prepareSameExamWidget($displayData);

		$this->_prepareCollegesAcceptingWidget($displayData);
		
		// Get download count of this guide
		$displayData['contentDetails']['guideDownloadCount']=$this->abroadExamPageCommonLib->getExamPageDownloadCount($displayData['contentDetails']['content_id']);
		//prepare right widget data
		$displayData['relatedArticles'] = $this->prepareDataForRelatedArticlesRightWidget($displayData);
		$displayData['loggedInUserData'] = $this->userStatus;
		$displayData['validateuser'] = $this->validateuser;
		$displayData['trackForPages'] 	= true;
        $displayData['breadCrumbData']=$this->_getBreadCrumbData($displayData);
        $displayData['contentType'] = "examContentPage";
		$displayData['contentId'] = $displayData['contentDetails']['content_id'];
		$displayData['topNavData'] = $this->abroadExamPageCommonLib->getContentNavigationLinks($displayData['examId'],'exam_content');
		 $displayData['H1Title'] =$this->applyContentLib->getH1Title($displayData);
		$this->load->view('examContentOverview',$displayData);
	}
        
        private function _getBreadCrumbData($displayData){
            $breadCrumbData=array();
            $contentDetails=$displayData['contentDetails'];
            array_push($breadCrumbData,array('url'=>SHIKSHA_STUDYABROAD_HOME,'title'=>'Home'));
            if(!$contentDetails['is_homepage']){
                array_push($breadCrumbData, array('url'=>SHIKSHA_STUDYABROAD_HOME.'/exams/'.$displayData['examName'],'title'=>  strtoupper($displayData['examName'])));
                array_push($breadCrumbData, array('url'=>'','title'=>strip_tags($contentDetails['title'])));
            }else{
                array_push($breadCrumbData, array('url'=>'','title'=>strip_tags($contentDetails['title'])));
            }
            return $breadCrumbData;
        }

        private function _prepareLoggedInUserData()
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
	private function _validateExamContentUrl($examName){
		$curUrl = getCurrentPageURLWithoutQueryParams();
		$this->saContentLib->checkMigratedExamContentRedirection($curUrl);
		$contentDetails = $this->saContentLib->getExamContentDetails(trim($curUrl,'/'));
		if($contentDetails['contentURL'] == ""){
			show_404_abroad();
		}
		if($curUrl !== $contentDetails['contentURL']){
			redirect($contentDetails['contentURL'],'location','301');
		}
		if($contentDetails === false){
			return false;
		}
		$displayData = array();
		$displayData['examName'] = $examName;
		$displayData['examId'] = $contentDetails['exam_type'];
		if($contentDetails['is_downloadable'] == no){
		    	//check if parent has a guide
			$guideFromParent = $this->SAContentModel->getParentExamContentGuide($contentDetails['exam_type']);
			if($guideFromParent[0]['download_link'] != '')
		    	{ 
				$contentDetails['download_link'] = MEDIAHOSTURL.$guideFromParent[0]['download_link'];
				$contentDetails['is_downloadable'] = 'yes';
			}
        	}
		
		$displayData['contentDetails'] = $contentDetails;
		$authorInfo =array(
            'firstname'=>$contentDetails['author_firstname'],
		    'lastname'=>$contentDetails['author_lastname'],
            'email'=>$contentDetails['email']
        );
		$displayData['contentDetails']['authorInfo'] = $authorInfo;
		return $displayData;
	}
	//prepare right side widget
	public function prepareDataForRelatedArticlesRightWidget($displayData)
	{
		$data = array( 'examName'=>$displayData['examName']);

		return $this->saContentLib->prepareDataForRelatedArticlesRightWidget($data);
	}

	private function _prepareSEOData(& $displayData){
		$seoDescription = $displayData['contentDetails']['seo_description'];
		$seoTitle = $displayData['contentDetails']['seo_title'];
		if(empty($seoTitle)){
			$seoTitle = $displayData['contentDetails']['strip_title'];
		}
		if(empty($seoDescription)){
			$details = reset($displayData['contentDetails']['sections']);
			$details = $details['details'];
			$seoDescription = substr(htmlentities(strip_tags($details)), 0,150);
		}
		$seoData = array(
			'seoTitle' => $seoTitle,
			'seoDescription' => $seoDescription,
			'canonicalUrl' => getCurrentPageURLWithoutQueryParams()
		);
		$displayData['seoData'] = $seoData;
	}

	private function _prepareBeaconTrackData(& $displayData){
		$displayData['beaconTrackData'] = array(
          'pageIdentifier' => 'examContentPage',
          'pageEntityId' => $displayData['contentDetails']['content_id'],
          'extraData' => array(
          	'LDBCourseId' => $displayData['contentDetails']['ldbCourses']
          	)
        );
	}

	private function _prepareCommentSectionData(& $displayData){

		//$this->config->load('studyAbroadSpamKeywordsConfig');
		//$spamKeywordsList = $this->config->item('spamKeywordsList');
        $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
        $spamKeywordsList = $saCMSToolsLib->getSpamKeywordsList();

		$displayData['spamKeywordsList'] = json_encode($spamKeywordsList);
		$authorId = $displayData['contentDetails']['created_by'];
		$contentId = $displayData['contentDetails']['content_id'];
		$commentsArray = $this->_getComments($contentId, $authorId);
		$displayData['commentsUserData']   = $commentsArray['userData'];
		unset($commentsArray['userData']);
		$displayData['commentData'] = $commentsArray;
	}

	private function _getComments($contentId, $authorId, $pageNo = 0,$commentIds = array())
    {
    	$returnArray = $commentsArray = $replyArray = array();
    	$userStatus = $this->checkUserValidation();
		$pageStart = $pageNo*50;
		$userIds = array();
		if(count($commentIds) == 0){	//i.e. we want comments
	    	$comments = $this->SAContentModel->getComments($contentId,'comment',$commentIds,$pageStart);
	    	foreach($comments['data'] as $comment){
				$commentsArray[$comment['commentId']] = $comment;
				$commentsArray[$comment['commentId']]['replies'] = array(); // add an assoc key for replies within comment
				$commentsArray[$comment['commentId']]['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($userStatus,array('authorId'=>$authorId,'userId'=>$comment['userId'],'emailId'=>$comment['emailId']));
				$userIds[] = $comment['userId'];
	    	}
	    	$commentIds = array_keys($commentsArray);
		}	
    	if(!empty($commentIds)){		//i.e. we want replies
	    	$replies = $this->SAContentModel->getComments($contentId,'reply',$commentIds);
	    	$replyArray = $replies['data'];
	    	foreach($replyArray as $key => $value){ 
				$value['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($userStatus,array('authorId'=>$authorId,'userId'=>$value['userId'],'emailId'=>$value['emailId']));
				$commentsArray[$value['parentId']]['replies'][] = $value;
				$userIds[] = $value['userId'];
	    	}
		}
		$contentPageLib = $this->load->library('contentPage/ContentPageLib');
		$returnArray['userData'] = $contentPageLib->getUserInfoForComments(array_unique($userIds));

    	$returnArray['data'] = $commentsArray;
    	$returnArray['total'] = $comments['total'];
    	return $returnArray;
    }

    private function _prepareSameExamWidget(& $displayData){
    	$displayData['relatedArticleData'] = $this->saContentLib->getRandomContentDetailsByExam($displayData['examId'],$displayData['contentDetails']['content_id']);
    }
	
	private function _prepareCollegesAcceptingWidget(& $displayData){
        $collegeWidgetSectionFlag = true;
        $collegeWidgetDesc2Flag = true;
        if(strpos($displayData['contentDetails']['sections']['0']['details'],COLLEGEAACCEPTINGWIDGETSNIPPET) ===false)
        {
            $collegeWidgetSectionFlag = false;
        }
        if(strpos($displayData['contentDetails']['description2'],COLLEGEAACCEPTINGWIDGETSNIPPET) === false)
        {
            $collegeWidgetDesc2Flag = false;
        }
        //check if snippet is not present then simply return
		if(!$collegeWidgetSectionFlag && !$collegeWidgetDesc2Flag)
		{
			return ;
		}
		$widgetHtml = '';
		if($displayData['examId'] !='' && $displayData['examName']!=''){
			$data = $this->saContentLib->examAcceptingCollegeWidget($displayData['examId'],$displayData['examName']);
			if(count($data)>0){
				$widget['exam'] 	  =	strtoupper($displayData['examName']);
				$widget['widgetData'] = $data;
				$widgetHtml = $this->load->view('widget/collegesAcceptingExamWidget',$widget,true);
			}
		}
		if($collegeWidgetSectionFlag)
        {
            $displayData['contentDetails']['sections']['0']['details'] = str_replace(COLLEGEAACCEPTINGWIDGETSNIPPET,$widgetHtml,$displayData['contentDetails']['sections']['0']['details']);
        }
        if($collegeWidgetDesc2Flag)
        {
            $displayData['contentDetails']['description2'] = str_replace(COLLEGEAACCEPTINGWIDGETSNIPPET,$widgetHtml,$displayData['contentDetails']['description2']);
        }
	}
	/*
	 * get exam content home page by exam name
	 */
	public function getSAExamHomePageURLByExamName($examName="", $isAjax = 0)
	{
		if($examName == "")
		{
			$examName = $this->input->post('examName');
			$isAjax = $this->input->post('isAjax');
			if($examName=="")
			{
				return false;
			}
		}
		$urls = $this->saContentLib->getSAExamHomePageURLByExamNames(array($examName));
		$url = $urls[$examName]['contentURL'];
		if($isAjax == 1)
		{
			echo json_encode($url);
		}
		else{
			return $url;
		}
	}
}
