<?php
class ApplyContentPage extends MX_Controller
{
	private $applyContentLib;
	
	function __construct(){
		$this->applyContentLib = $this->load->library('applyContent/ApplyContentLib');
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
		$displayData['contentId']   = $id; 
		$displayData['contentType'] = $type;
		$displayData['contentTypeId'] = $typeId;
		$displayData['contentDisplayValue'] = $masterList[$typeId]['heading'];
		$masterList = array_map(function($value){return strtoupper($value['type']);},$masterList);
		$displayData['contentData'] = $this->applyContentLib->getContentData(array_search($type,$masterList),$id);
        if(empty($displayData['contentData']['contentURL']))
        {
            show_404_abroad();
        }
		if($displayData['contentData']['contentURL'] != getCurrentPageURLWithoutQueryParams()){
			redirect($displayData['contentData']['contentURL'], 'location', 301);
		}
		$displayData['guideDownloadCount'] = $this->applyContentLib->getApplyContentGuideDownloadCount($id);
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
	
	private function _prepareTrackingData(& $displayData){
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
		$this->_prepareTrackingData($displayData);
		$this->_initUser($displayData);
		$masterList = $this->config->item('applyContentMasterList');

		//Unset Id for current apply content page type
		$contentTypeIdArray                           = array_keys($masterList);
		$displayData['learnApllicationProcessData']   = $this->applyContentLib->getApplyContentHomePageUrl($contentTypeIdArray);
		//Unset Id for current apply content page type
		unset($masterList[$displayData['contentTypeId']]);
		$contentTypeIdArray                           = array_keys($masterList);
		
		$displayData['trackForPages']                 = true; //For JSB9 Tracking
		$displayData['popularArticlesData']           = $this->applyContentLib->getPopularArticlesLastNnoOfDays($displayData['contentType'],$displayData['contentTypeId'],$displayData['contentId']);
		$displayData['alsoLikeInlineArticlesData']    = $this->applyContentLib->alsoLikeArticlesData($displayData['contentTypeId'],$contentTypeIdArray,1);
		$displayData['guideUrl']                      = Modules::run('applyContent/applyContent/getGuideURL',$displayData['contentId']);
		//load spamKeywords Config for filtering spam comments on article guide and exam page comment section
		//$this->config->load('studyAbroadSpamKeywordsConfig');
		//$spamKeywordsList                           = $this->config->item('spamKeywordsList');
		$saCMSToolsLib                                = $this->load->library('saCMSTools/SACMSToolsLib');
		$spamKeywordsList                             = $saCMSToolsLib->getSpamKeywordsList();
		
		$displayData['spamKeywordsList']              = json_encode($spamKeywordsList);
		$displayData['content']['data']['content_id'] = $displayData['contentId'];
		$displayData['content']['data']['type']       = 'applyContent';
		$this->contentpagelib                         = $this->load->library('contentPage/ContentPageLib');
		$displayData['comments']                      = $this->contentpagelib->getCommentsForContent($displayData['contentId'],1);
		$displayData['browsewidget']                  = $this->contentpagelib->getBrowseWidgetData($displayData['contentId'],true);
		$this->contentpagelib->formatContentSectionDetails($displayData['contentData']['description2']);
		$this->contentpagelib->formatContentSectionDetails($displayData['contentData']['details']);
		$this->abroadExamPageCommonLib                = $this->load->library('abroadExamPages/AbroadExamPageCommonLib');
		$displayData['topNavData']                    = $this->abroadExamPageCommonLib->getContentNavigationLinks($displayData['contentTypeId'],'apply_content');
		 $displayData['H1Title'] =$this->applyContentLib->getH1Title($displayData);
		$this->load->view('contentPage/applyContentOverview',$displayData);
	}
	
	public function downloadGuide($guideUrl,$contentId,$trackingPageKeyId){
		if($guideUrl != 'null'){
			$guideUrl = base64_decode($guideUrl);
		}
		$this->applyContentLib->sendGuideEmail($guideUrl,$contentId,$trackingPageKeyId);		
	}
	
}
?>
