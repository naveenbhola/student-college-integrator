<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ContentExamPages extends ShikshaMobileWebSite_Controller
{
    private $userStatus;
    private $validateuser;
    private $examPageObj;
    private $abroadExamPageBuilder;
    private $abroadExamPageCommonLib;
    private $abroadExamPageRepository;
    private $sectionName;
    private $SAContentModel;
    
    public function __construct()
    {
    	parent::__construct();
	    $this->userStatus = $this->prepareLoggedInUserData();
		$this->config->load('abroadExamPageConfig');
		$this->load->builder('AbroadExamPageBuilder', 'abroadExamPages');
		$this->abroadExamPageBuilder = new AbroadExamPageBuilder;
		// common abroad exam page library 
		$this->abroadExamPageCommonLib 	= $this->load->library('abroadExamPages/AbroadExamPageCommonLib');
		$this->contentPageLib 	= $this->load->library('contentPage/ContentPageLib');
		// abroad exam page repository
		$this->abroadExamPageRepository = $this->abroadExamPageBuilder->getAbroadExamPageRepository($this->abroadExamPageCommonLib);
		$this->load->helper('/shikshautility_helper');
		$this->load->model('blogs/sacontentmodel');
		$this->SAContentModel = new SAContentModel();
    }
    
    /**
     * function to get exam page (home/section)
     * params: contentId / url as a string
     */
    function abroadExamPage($paramString, $contentId)
    {   	
    	$saContentLib = $this->load->library('blogs/saContentLib');
		$saContentLib->checkMigratedExamContentRedirection(getCurrentPageURLWithoutQueryParams());
        show_404_abroad();
		// clean up contentId
		$contentId = preg_replace('/[^0-9]/','',$contentId);
		// Get section related data(ID and Name for url verification and identifying ID for section)
		$sectionData = $this->abroadExamPageCommonLib->getExamPageSectionData($paramString);
		$displayData = array();
		$displayData['trackForPages'] = true; //For JSB9 Tracking
		$displayData['contentId']			= $contentId;
				
		//get complete exampage object
		$examPage = $this->abroadExamPageRepository->find($contentId,$sectionData['sectionId']); // second param will be section name/index
		if(empty($examPage) || $examPage == false)
		{
			show_404_abroad();
		}
		$recommendedUrl 				 	= $this->abroadExamPageCommonLib->examPageSectionURL($examPage,$sectionData['sectionName'],true);
		$seoData 						 	= $this->_getMetaDataAbroadExamPages($examPage,$sectionData['sectionId']);
		$seoData['canonicalUrl'] 		 	= $recommendedUrl;
		$displayData['validateuser'] 	 	= $this->validateuser;
		$displayData['loggedInUserData'] 	= $this->userStatus;
		$displayData['examPageObj'] 	 	= $examPage;
		$displayData['seoData'] 		 	= $seoData;
		$displayData['sectionData'] 	 	= $sectionData;
		$displayData['subSectionDetails'] 	= $this->getSubSectionDetails($sectionData,$examPage);	
		$examSections 						= $examPage->getSections();
		$sectionLinks			   			= $this->getExamPageSectionLinks($examPage);
		$displayData['sectionLinks'] 		= $this->removeCurrentSectionLinks($sectionLinks,$sectionData);		
		$displayData['guideDownloadCount']	= $this->abroadExamPageCommonLib->getExamPageDownloadCount($examPage->getExamPageId());
		$displayData['contentType']			= 'examPage';
		
		//Comment Section Data Start Here
		//$this->config->load('studyAbroadSpamKeywordsConfig');
		//$spamKeywordsList 			= $this->config->item('spamKeywordsList');
        $saCMSToolsLib = $this->load->library('saCMSTools/SACMSToolsLib');
        $spamKeywordsList = $saCMSToolsLib->getSpamKeywordsList();

        $displayData['spamKeywordsList']	= json_encode($spamKeywordsList);
		$displayData['content']['data']['content_id'] = $contentId;
		$displayData['content']['data']['type']       = 'examPage';
		$displayData['comments'] = $this->contentPageLib->getCommentsForContent($contentId, 1,$sectionData['sectionId']);
		$displayData['authorId'] = $examPage->getAuthorId();
		
		//for facebook widget we need the current page url
		$displayData['currentPageUrl'] = $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		//for MIS tracking
		$this->_prepareTrackingData($displayData);
		$this->load->view("contentPage/examPageOverview",$displayData);
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
	
	public function getExamPageSectionLinks($examPage){
		$sections = $this->config->item('abroad_exam_page_section_details');
		$examId = $examPage->getExamPageId();
		$examName = $examPage->getExamName();
		$urlList = array();
		foreach($sections as $key => $section){
			$urlList[$section['name']]['link'] =  $this->abroadExamPageCommonLib->examPageSectionURL($examPage,$section['urlPattern']);
			$urlList[$section['name']]['title'] = $section['title'];
		}
		return $urlList;
	}
	
	public function getSubSectionDetails($sectionData,$examPage){
	    
		if($sectionData['sectionId'] == 8){		
			$subSectionData = $this->_getCollegeExamData($examPage);
		}
	    elseif($sectionData['sectionId']==9)
	    {
			$subSectionData = $this->abroadExamPageCommonLib->getExamRelatedArticles($examPage);
	    }
	    else
	    {
			$subSectionData = $examPage->getSections();
	    }
	    
	    return $subSectionData;
	}

	public function removeCurrentSectionLinks($sectionLinks,$sectionData)
	{
		switch($sectionData['sectionId'])
		{
			case '1' :	unset($sectionLinks['about section']);
						break;
			case '2' : unset($sectionLinks['exam pattern']);
						break;
			case '3' : unset($sectionLinks['scoring section']);
						break;
			case '4' : unset($sectionLinks['important dates']);
						break;
			case '5' : unset($sectionLinks['prepration tips']);
						break;
			case '6' : unset($sectionLinks['practice and sample paper']);
						break;
			case '7' : unset($sectionLinks['syllabus']);
						break;
		}
		return $sectionLinks;
	}
	public function totalGuideDownloaded()
	{
		$guideId = $this->input->post('guideId');
		$guideId = base64_decode($guideId);
		$downloadCount	= $this->abroadExamPageCommonLib->getExamPageDownloadCount($guideId);
		echo $downloadCount;
	}

	private function _prepareTrackingData(& $displayData)
	{
		$this->examPageDetails($displayData);

		$displayData['beaconTrackData'] = array(
					'pageIdentifier' 	=> 'examPage',
					'pageEntityId' 		=> $displayData['contentId'],
					'extraData' 		=> array(
											'categoryId'	=> $displayData['category_id'],
											'subCategoryId'	=> $displayData['subcategory_id'],
											'LDBCourseId'	=> $displayData['ldb_course_id'],
											'countryId' 	=> $displayData['country_id']
											)
			);
	}
	/*This is to fetch exam page details */
	public function examPageDetails(& $displayData)
	{
		$tempcountryMappingData = array();
		$templdbMappingData = array();
		$tempcourseMappingData = array();
		// common abroad exam page library 
		$this->contentPageLib 	= $this->load->library('contentPage/ContentPageLib');
		$data = $this->contentPageLib->contentPageDetails($displayData['contentId']);
		
		//break the array
		$tempcountryMappingData = $data['countryMappingData'];			
		$templdbMappingData = $data['ldbMappingData'];
		$tempcourseMappingData = $data['courseMappingData'];
		
		//populate country mapping data
		foreach ($tempcountryMappingData as $key => $value) {
			if($key =="country_id")
			$displayData['country_id'] = $value['country_id'];
		}
		$auxarray = array();
		//populate ldb mapping data
		foreach ($templdbMappingData as $key => $value) {
			foreach($value as $key => $ldbId)
			{
				if($key=='ldb_course_id')
				$auxarray[] = $ldbId;
			}
				
		}
		$displayData['ldb_course_id'] = $auxarray;
		//course mapping data
		foreach ($tempcourseMappingData as $key => $value) {
			if($key =="parent_category_id")
			{
				$displayData['category_id'] = $value['parent_category_id'] ;
			}	
			if($key =="subcategory_id")
			{
				$displayData['subcategory_id'] = $value['subcategory_id'];
			}
		}

		unset($tempcountryMappingData);
		unset($templdbMappingData);
		unset($tempcourseMappingData);		
	}  
}
