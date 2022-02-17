<?php
class applyContent extends MX_Controller
{
	private $applyContentLib;
	private $abroadExamPageCommonLib;
	
	function __construct(){
		$this->applyContentLib = $this->load->library('applyContent/ApplyContentLib');
		$this->load->helper(array('shikshautility','blogs/article'));
		$this->saContentLib 	= $this->load->library('blogs/saContentLib');
	}
	
	private function _initApplyContentPage($typeid,& $displayData){
		$typeId = substr($typeid,0,1);
		$id = substr($typeid,1);
		$this->config->load('abroadApplyContentConfig');
		$masterList = $this->config->item('applyContentMasterList');
		$type = strtoupper($masterList[$typeId]['type']);
		if(empty($type)){
			show_404_abroad();
		}
		if(empty($id) || (integer)($id) == 0){
			show_404_abroad();
		}
		$displayData['contentId'] = $id; 
		$displayData['contentType'] = $type;
		$displayData['contentTypeId'] = $typeId;
		$displayData['contentHeading'] = $masterList[$typeId]['heading'];

		$masterList = array_map(function($value){return strtoupper($value['type']);},$masterList);
		
		$displayData['contentData'] = $this->applyContentLib->getContentData(array_search($type,$masterList),$id);
		if(empty($displayData['contentData']['contentURL']))
		{
			show_404_abroad();
		}
		$displayData['contentData']['totalGuideDownloaded'] = $this->applyContentLib->getApplyContentGuideDownloadCount($id);
		if($displayData['contentData']['contentURL'] != getCurrentPageURLWithoutQueryParams()){
			redirect($displayData['contentData']['contentURL'], 'location', 301);
		}
		$this->_getSeoDetails($displayData);
	}
	
	private function _getSeoDetails(& $displayData){
		$displayData['canonicalURL'] = $displayData['contentData']['contentURL'];
		$seoDetails = array(
			'title'=>($displayData['contentData']['seoTitle'] == ""?$displayData['contentData']['strip_title']:$displayData['contentData']['seoTitle']),
			'description' => ($displayData['contentData']['seoDescription'] == ""?substr(strip_tags($displayData['contentData']['details']),0,150):$displayData['contentData']['seoDescription']),
			'keywords'=> $displayData['contentData']['seoKeywords']
		);
		$displayData['seoDetails'] = $seoDetails;
		
	}
	
	private function _initUser(& $displayData){
		$displayData['validateuser'] = $this->checkUserValidation();
		if($displayData['validateuser'] !== 'false') {
			$this->load->model('user/usermodel');
			$usermodel = new usermodel;
			$userId = $displayData['validateuser'][0]['userid'];
			$user = $usermodel->getUserById($userId);
			if (!is_object($user)) {
				$displayData['loggedInUserData'] = false;
			}else {
				$name = $user->getFirstName().' '.$user->getLastName();
				$email = $user->getEmail();
				$userFlags = $user->getFlags();
				$isLoggedInLDBUser = $userFlags->getIsLDBUser();
				$displayData['loggedInUserData'] = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
				$displayData['checkIfLDBUser'] 		= $usermodel->checkIfLDBUser($userId);
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
			$displayData['checkIfLDBUser'] = "NO";
	    }
	}
	
	private function _getTrackingData(& $displayData){
		$displayData['beaconTrackData'] = array(
			'pageIdentifier' => 'applyContentPage',
			'pageEntityId' => $displayData['contentData']['id'],
			'extraData' => array(
				'categoryId'=>$displayData['contentData']['category_id'],
				'subCategoryId'=>$displayData['contentData']['subcategory_id'],
				'LDBCourseId'=>$displayData['contentData']['ldb_course_id'],
				'countryId' => $displayData['contentData']['country_id']
			)
		);
	}
	
	public function applyContentPage($typeid){
		$displayData = array();
		$this->_initApplyContentPage($typeid,$displayData);
		$this->_getTrackingData($displayData);
		$this->_initUser($displayData);
		$displayData['guideURL'] 		= $this->getGuideURL($displayData['contentData']['id'],$displayData);
		$displayData['trackForPages'] 	= true;
		
		$masterList = $this->config->item('applyContentMasterList');

		$contentTypeIdArray  						= array_keys($masterList);
        $displayData['learnApllicationProcessData'] = $this->applyContentLib->getApplyContentHomePageUrl($contentTypeIdArray);
        //Unset Id for current apply content page type
        unset($masterList[$displayData['contentTypeId']]);
        $contentTypeIdArray  						= array_keys($masterList);
		//$articlesData		 		= $this->applyContentLib->getPopularArticlesLastNnoOfDays($displayData['contentType'],$displayData['contentTypeId'],$displayData['contentId']);
		$articlesData		 		= $this->applyContentLib->getRecommendedContents($displayData['contentType'],$displayData['contentTypeId'],$displayData['contentId'],4);
		/* if(count($articlesData)==4 && !empty($displayData['guideURL']))
		 { unset($articlesData[3]);    	//unset the last one  }*/
		
		$displayData['popularArticlesData'] 	   = $articlesData;
		$displayData['alsoLikeInlineArticlesData'] = $this->applyContentLib->alsoLikeArticlesData($displayData['contentTypeId'],$contentTypeIdArray,1);
        //browse widget code
		$this->contentPageLib = $this->load->library('contentPage/ContentPageLib');
        $displayData['browsewidget'] 	= $this->contentPageLib->getBrowseWidgetData($displayData['contentId'],true);
		//Code and data for Comment section
		//$this->config->load('studyAbroadSpamKeywordsConfig');
		//$spamKeywordsList 				   = $this->config->item('spamKeywordsList');
        $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
        $spamKeywordsList = $saCMSToolsLib->getSpamKeywordsList();

		$displayData['spamKeywordsList']   = json_encode($spamKeywordsList);
		$commentsArray 					   = $this->applyContentLib->getComments($displayData['contentId']);
		$displayData['comments'] 		   = $commentsArray['data'];
		$displayData['totalComments'] 	   = $commentsArray['total'];
		$displayData['commentsUserData']   = $commentsArray['userData'];
		$commentsArrayTemp = array();
		foreach($commentsArray['data'] as $key=>$comment1)
	    {
			$comment = $comment1['data'];
			$commentsArrayTemp[$comment['commentId']]['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($displayData['validateuser'],array('authorId'=>$authorId,'userId'=>$comment['userId'],'emailId'=>$comment['emailId']));
			if(count($comment1['replies'])>0){
				foreach($comment1['replies'] as $reply){
					$commentsArrayTemp[$reply['commentId']]['userEligibleForCommentDeletion'] = $this->saContentLib->checkUserEligibilityForCommentDeletion($displayData['validateuser'],array('authorId'=>$authorId,'userId'=>$reply['userId'],'emailId'=>$reply['emailId']));
				}
			}
	    }
		$displayData['deletionPermissionArray'] = $commentsArrayTemp;
		$displayData['userStatus']         = $displayData['validateuser'];
		$displayData['content']['data']['commentCount'] = $commentsArray['total'];
		$displayData['content']['data']['type']         = 'applyContent';
        $this->abroadExamPageCommonLib = $this->load->library('abroadExamPages/AbroadExamPageCommonLib');
        $displayData['topNavData'] 						= $this->abroadExamPageCommonLib->getContentNavigationLinks($displayData['contentTypeId'],'apply_content');
        $displayData['breadCrumbData'] = $this->applyContentLib->prepareBreadCrumbData($displayData);
        //_p($displayData);die;
        $displayData['H1Title'] =$this->applyContentLib->getH1Title($displayData);
		$this->load->view('applyContent/applyContentOverview',$displayData);
	}

	
	/*
	 * Function : Returns the URL of an applyContent article, given the content id.
	 * @param integer $contentId Integral ID of the content piece.
	 * @param Array $displayData Optional, this can be used to get the download link for homepage.
	 *
	 * Either returns or echoes url based on ajax call status.
	 */
	public function getGuideURL($contentId, $displayData = array()){
		if(!empty($displayData['contentData']['is_downloadable']) && $displayData['contentData']['is_downloadable'] == "yes"){
			return $displayData['contentData']['download_link'];
		}
		if((integer)$contentId <= 0){
			return '';
		}
		$url = $this->applyContentLib->getGuideURL($contentId);
		if($this->input->is_ajax_request()){
			echo $url;
			return true;
		}
		return $url;
	}
	
	public function downloadGuide($guideUrl,$contentId,$trackingPageKeyId){
		$this->load->helper('download');
		downloadFileInChunks(base64_decode($guideUrl), 400000);
		$user = $this->checkUserValidation();
		if($user == "false"){
			$userId = 0;
		}else{
			$userId = $user[0]['userid'];
		}
		if(!($contentId > 0))
		{
			return false;
		}
		$dataArray = array(
			'contentId'=>$contentId,
			'guideUrl'=>base64_decode($guideUrl),
			'pageUrl'=>$_SERVER['HTTP_REFERER'],
			'sourceSite'=> 'desktop',
			'userId'=>$userId,
			'sessionId'=>sessionId(),
			'downloadedAt'=>date('Y-m-d H:i:s'),
			'tracking_keyid' => $trackingPageKeyId,
			'visitorSessionid' => getVisitorSessionId()
		);
        if(is_null($_SERVER['HTTP_REFERER'])){
             $this->applyContentLib->sendErrorMailForDownloadGuideTracking($dataArray);
        }
		$this->applyContentLib->trackDownloadGuide($dataArray);
	}
	/*
	 * to get Apply Content Guide Download Count
	 */
	public function totalGuideDownloaded()
	{
		$contentId = $this->input->post('contentId');
		$val = $this->applyContentLib->getApplyContentGuideDownloadCount($contentId);
		if($this->input->is_ajax_request()){
			echo $val;
		}
		return $val;
	}
	/*
	 * function  to load find colleges widget on article/guide/applyContent page
	 * NOTE: this function is used as a module run call
	 */
	public function loadFindCollegesWidgetOnContentPage($data)
	{
		// get "find colleges" widget data
		$findCollegesWidgetData = $this->_getFindCollegesWidgetData();
		$findCollegesWidgetData['contentType'] = $data['contentType'];
		// load view
		$this->load->view('applyContent/widget/applyContentFindCollege',$findCollegesWidgetData);
	}
	
	/*
	 * function to get the desired courses, level of study, categories for college search form.
	 */
	private function _getFindCollegesWidgetData()
	{
		// load dependencies for find colleges widget data
		$this->abroadCommonLib  	= $this->load->library('listingPosting/AbroadCommonLib');
		$findCollegesWidgetData = array();
			
		//1.  get desired courses and categories
			$findCollegesWidgetData['desiredCourses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();
			$findCollegesWidgetData['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();
			
		//2.  get levels
			$findCollegesWidgetData['levelOfStudy'] = $this->abroadCommonLib->getAbroadCourseLevelsForFindCollegeWidgets();
			
		//3. get fees ranges ????????
			$findCollegesWidgetData['fees'] = $GLOBALS['CP_ABROAD_FEES_RANGE']['ABROAD_RS_RANGE_IN_LACS'];
			
		return $findCollegesWidgetData;
	}

	
}
?>
