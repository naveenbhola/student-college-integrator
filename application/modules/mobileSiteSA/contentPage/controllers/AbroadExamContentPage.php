<?php
class AbroadExamContentPage extends MX_Controller
{
	function __construct(){
		parent::__construct();
		$this->saContentLib = $this->load->library('blogs/saContentLib');
		$this->abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
		$this->abroadExamPageCommonLib = $this->load->library('abroadExamPages/AbroadExamPageCommonLib');
		$this->contentpagelib = $this->load->library('contentPage/ContentPageLib');
		$this->applyContentLib = $this->load->library('applyContent/ApplyContentLib');

	}

	public function index($examName){
		$displayData = $this->_validateExamContentUrl($examName);
		if($displayData === false){
			show_404_abroad();
			return false;
		}
		$this->_initUser($displayData);
		$this->_prepareSEOData($displayData);
		$this->_prepareBeaconTrackData($displayData);
		$this->_prepareSameExamWidget($displayData);
		$this->_prepareCollegesAcceptingWidget($displayData);
		$this->_getCommentSectionDetails($displayData);
		$displayData['browsewidget']       = $this->contentpagelib->getBrowseWidgetData($displayData['contentDetails']['content_id'],true);
		//prepare right widget data
		$displayData['relatedArticles']    = $this->prepareDataForRelatedArticlesRightWidget($displayData);
		$displayData['guideDownloadCount'] =$this->abroadExamPageCommonLib->getExamPageDownloadCount($displayData['contentDetails']['content_id']);
		$displayData['trackForPages']      = true; //For JSB9 Tracking
		$this->contentpagelib->formatContentSectionDetails($displayData['contentDetails']['description2']);
		$this->contentpagelib->formatContentSectionDetails($displayData['contentDetails']['sections'][0]['details']);
		$displayData['jqMobileFlag']       = true;
		$displayData['topNavData']         = $this->abroadExamPageCommonLib->getContentNavigationLinks($displayData['examId'],'exam_content');
		$displayData['contentId']          = $displayData['contentDetails']['content_id'];
		$displayData['H1Title'] =$this->applyContentLib->getH1Title($displayData);
		$this->load->view('contentPage/examContentPageOverview',$displayData);
	}
	
	private function _getCommentSectionDetails(& $displayData){
		
		//load spamKeywords Config for filtering spam comments on article guide and exam page comment section
		//$this->config->load('studyAbroadSpamKeywordsConfig');
		//$spamKeywordsList 			= $this->config->item('spamKeywordsList');
        $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
        $spamKeywordsList = $saCMSToolsLib->getSpamKeywordsList();

		$displayData['spamKeywordsList']	= json_encode($spamKeywordsList);
		$displayData['content']['data']['content_id'] = $displayData['contentDetails']['content_id'];
		$displayData['content']['data']['type']       = 'examContent';
		$displayData['contentType']					  = 'examContent';
		$displayData['comments'] 	= $this->contentpagelib->getCommentsForContent($displayData['contentDetails']['content_id'],1);	
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
		$displayData['contentDetails'] = $contentDetails;
		return $displayData;
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

	//prepare right side widget
	public function prepareDataForRelatedArticlesRightWidget($displayData)
	{
		$data = array( 'examName'=>$displayData['examName']);

		return $this->saContentLib->prepareDataForRelatedArticlesRightWidget($data);
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
	    }
	}
	
	private function _prepareSameExamWidget(& $displayData){
    	$displayData['relatedArticleData'] = $this->saContentLib->getRandomContentDetailsByExam($displayData['examId'],$displayData['contentDetails']['content_id']);
    	$displayData['relatedArticleData'] = array_slice($displayData['relatedArticleData'],0, 2);
    }
	
	private function _prepareCollegesAcceptingWidget(& $displayData){
		
		//check if snippet is not present then simply return 
		if(strpos($displayData['contentDetails']['sections']['0']['details'],COLLEGEAACCEPTINGWIDGETSNIPPET) ===false
		   && strpos($displayData['contentDetails']['description2'],COLLEGEAACCEPTINGWIDGETSNIPPET) === false
		  )
		{
			return ;
		}
		$widgetHtml = '';
		if($displayData['examId'] !='' && $displayData['examName']!=''){
			$data = $this->saContentLib->examAcceptingCollegeWidget($displayData['examId'],$displayData['examName']);
			if(count($data)>0){
				$widget['exam'] 	  =	strtoupper($displayData['examName']);
				$widget['widgetData'] = $data;
				$widgetHtml = $this->load->view('widgets/collegesAcceptingExamWidget',$widget,true);
			}
		}
		$displayData['contentDetails']['sections']['0']['details'] = str_replace(COLLEGEAACCEPTINGWIDGETSNIPPET,$widgetHtml,$displayData['contentDetails']['sections']['0']['details']);
		$displayData['contentDetails']['description2'] = str_replace(COLLEGEAACCEPTINGWIDGETSNIPPET,$widgetHtml,$displayData['contentDetails']['description2']);
	}
}

