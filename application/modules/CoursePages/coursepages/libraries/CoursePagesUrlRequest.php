<?php
/**
 * CoursePagesUrlRequest Library Class
 *
 *
 * @package     Course Pages
 * @subpackage  Libraries
 * @author      Amit Kuksal
 *
 */

class CoursePagesUrlRequest {

	private $CI;
	private $availableTabsInfo;
	private $courseHomePageId;
    private $tagId;
    private $tagName;
	private $applyOnlineTabEnabledForSubCats = array(23);
	private $coursePagesSEODataIndia;
	private $COURSE_HOME_ARRAY;
	private $customizedTabsBar;
	private $coursePagesAllTabs;
	public $CategoryObject;
	private $defaultUrlKey = 'url';
    private $courseHomePageDictionary;
    function __construct($useCourseHomeOldFormat)
	{
		$this->CI = & get_instance();
		global $coursePagesSEODataIndia;
    	$this->coursePagesSEODataIndia = $coursePagesSEODataIndia;
        if(is_null($useCourseHomeOldFormat)){
            $useCourseHomeOldFormat=1;
        }
      	$this->COURSE_HOME_ARRAY = $this->setCourseHomePageDictionary($useCourseHomeOldFormat);
		// To load the categoryPageConfig file
		// $this->CI = & get_instance();
		$this->CI->config->load('categoryPageConfig');
	}
    public function setCourseHomePageDictionary($useCourseHomeOldFormat){
        $courseCommonLib=$this->CI->load->library('coursepages/CoursePagesCommonLib');
        return $courseCommonLib->getCourseHomePageDictionary($useCourseHomeOldFormat);
    }
    private function _getCoursePagesAllTabs() {
		$this->coursePagesAllTabs = array(
			"Home" => array("NAME" => "Home",'HELP_TXT'=>'Visit <> home page'),			   
			"Institutes" => array("NAME" => "Institutes",'HELP_TXT'=>'Search for colleges offering <>'),
			"Faq" => array("NAME" => "FAQ",'HELP_TXT'=>'Frequently asked questions about <>'),
			"AskExperts" => array("NAME" => "Q & A",'HELP_TXT'=>'Top questions about <>'),
			"Discussions" => array("NAME" => "Discussions",'HELP_TXT'=>'Popular discussions about <>'),
			"Rankings" => array("NAME" => "Rankings",'HELP_TXT'=>'Top <> Institutes'),
			"News" => array("NAME" => "Articles",'HELP_TXT'=>'Top articles about <>'),
			"CollegePredictor" => array("NAME" => "Tools",'HELP_TXT' => 'Find colleges for your rank'),
			"Exams" => array("NAME" => "Exams" , 'HELP_TXT' => 'Top articles for Exams'),
			"MyShortlist" => array("NAME" => "MyShortlist" , 'HELP_TXT' => 'Shortlist colleges to make an informed decision'),
			"StudentTools" => array("NAME" => "Tools" , 'HELP_TXT' => 'Student Tools'),
			"studentMentor" => array("NAME" => "Get A Mentor" , "HELP_TXT" => 'Get a current engineering student as your mentor')
		);
	}

	public function getCoursePagesSeoDetails($courseHomePageId, $page = 1)
	{
		$this->courseHomePageId = $courseHomePageId;
        $this->tagId    = $this->getTagId($courseHomePageId);
		$this->_getTabsOnPage();
		$this->_getTabsSeoInfo($page);
		return $this->availableTabsInfo;
	}
	
	public function getParticularPageSeoDetails($courseHomePageId,$page = 1,$tabName){
		$data = array();
		$subCatObj = $this->getCategoryInfo($courseHomePageId);
		$domainUrl = $this->getDomainUrl($subCatObj->getParentId());
		switch($tabName) {
			case "Home":
				$data["URL"] = $this->getHomeTabUrl($courseHomePageId, $domainUrl);
				$data["TITLE"] = $this->coursePagesSEODataIndia['Home'][$courseHomePageId]['title'];
				$data["DESCRIPTION"] = $this->coursePagesSEODataIndia['Home'][$courseHomePageId]['description'];
				$data["KEYWORDS"] = $this->coursePagesSEODataIndia['Home'][$courseHomePageId]['keywords'];
				break;
			
			case "Faq":
				$data["URL"] = $this->getFaqTabUrl($courseHomePageId, $domainUrl, $page);
				$data["TITLE"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['Faq'][$courseHomePageId]['title'];
				$data["DESCRIPTION"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['Faq'][$courseHomePageId]['description'];
				$data["KEYWORDS"] = $page == 1 ? ($this->coursePagesSEODataIndia['Faq'][$courseHomePageId]['keywords']) : "";
				break;
			
			case "Institutes":
				$data["URL"] = $this->getInstitutesTabUrl($courseHomePageId);
				break;
			
			case "Discussions":
				$data["URL"] = $this->getDiscussionsTabUrl($courseHomePageId, $domainUrl, $page);
				$data["TITLE"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['Discussions'][$courseHomePageId]['title'];
				$data["DESCRIPTION"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['Discussions'][$courseHomePageId]['description'];
				$data["KEYWORDS"] = $page == 1 ? ($this->coursePagesSEODataIndia['Discussions'][$courseHomePageId]['keywords']) : "";
				break;
			
			case "Rankings":
				$data["URL"] = $this->getRankingsTabUrl($courseHomePageId);
				break;
			
			case "AskExperts":
				$data["URL"] = $this->getAskExpertsTabUrl($courseHomePageId, $domainUrl, $page);
				$data["TITLE"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['AskExperts'][$courseHomePageId]['title'];
				$data["DESCRIPTION"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['AskExperts'][$courseHomePageId]['description'];
				$data["KEYWORDS"] = $page == 1 ? ($this->coursePagesSEODataIndia['AskExperts'][$courseHomePageId]['keywords']) : "";
				break;
			
			case "ApplyOnline":
				$data["URL"] = $this->getApplyOnlineTabUrl($courseHomePageId);
				break;

			case "News":
				$data["URL"] = $this->getNewsTabUrl($courseHomePageId, $domainUrl, $page);
				$data["TITLE"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['News'][$courseHomePageId]['title'];
				$data["DESCRIPTION"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['News'][$courseHomePageId]['description'];
				$data["KEYWORDS"] = $page == 1 ? ($this->coursePagesSEODataIndia['News'][$courseHomePageId]['keywords']) : "";
				break;
			case "CollegePredictor" :
				//$data["URL"] = $this->getJeeMainTabTabUrl($courseHomePageId, $domainUrl);
				//$data["TITLE"] = "JEE Mains 2014 College Predictor - Shiksha.com";
				//$data["DESCRIPTION"] = "JEE Mains 2014 college predictor tool to know college based on your rank in JEE Mains entrance exam.";
				break;
			case "Exams" : 
				$data["URL"] = $this->getExamsTabUrl($courseHomePageId);
				$data["TITLE"] = "";
				$data["DESCRIPTION"] = "";
				break;
		}
		return $data;
	}

	public function getCoursePagesTabsUrls($courseHomePageId, $customizedTabsBar = "") {
		$this->courseHomePageId = $courseHomePageId;
		$this->customizedTabsBar = $customizedTabsBar;
		$this->_getTabsOnPage();
		$this->_getUrls();
		return $this->availableTabsInfo;
	}

	private function _getTabsOnPage()
	{		
		if(! (is_array($this->customizedTabsBar) && count($this->customizedTabsBar))) {
		
			// $this->customizedTabsBar = array('Home', 'Institutes', 'Faq', 'AskExperts', 'Discussions', 'News');
			if($this->courseHomePageId == 1) {
				$this->customizedTabsBar = array('Home', 'Institutes','StudentTools', 'Rankings', 'Exams','ApplyOnline','MyShortlist'); // New ShortList link : uncomment this line and comment below line.
				//$this->customizedTabsBar = array('Home', 'Institutes','Faq','AskExperts', 'Rankings','Exams'); // New Exam Page link : uncomment this line and comment below line.
				//$this->customizedTabsBar = array('Home', 'Institutes', 'Faq', 'AskExperts', 'Rankings', 'News');
			}else if($this->courseHomePageId == 6){
				if(MENTORSHIP_PROGRAM_FLAG == 'true')
				{
					$this->customizedTabsBar = array('Home', 'Institutes', 'studentMentor', 'AskExperts','Rankings','CollegePredictor', 'Exams');
				}
				else {
					$this->customizedTabsBar = array('Home', 'Institutes', 'Faq', 'AskExperts','Rankings','CollegePredictor', 'Exams');
			}
			}else {
				$this->customizedTabsBar = array('Home', 'Institutes', 'Faq', 'AskExperts', 'Discussions', 'News');
			}
			/*
			$this->availableTabsInfo = array(
				   "Home" => array("NAME" => "Home",'HELP_TXT'=>'Visit <> home page'),			   
				   "Institutes" => array("NAME" => "Institutes",'HELP_TXT'=>'Search for colleges offering <>'),
				   "Faq" => array("NAME" => "FAQ",'HELP_TXT'=>'Frequently asked questions about <>'),
				   "AskExperts" => array("NAME" => "Q & A",'HELP_TXT'=>'Top questions about <>'),
				   "Discussions" => array("NAME" => "Discussions",'HELP_TXT'=>'Popular discussions about <>'),
				   "News" => array("NAME" => "Articles",'HELP_TXT'=>'Top articles about <>')
			);
			*/
		}
		
		$this->_getCoursePagesAllTabs();
		
		foreach($this->customizedTabsBar as $key => $tabId) {
			$this->availableTabsInfo[$tabId] = $this->coursePagesAllTabs[$tabId];
		}
		
		
		/*
		 * Implemented check, so that Apply Online tab would appear in only MBA Subcategory.
		 */
		if(in_array($this->courseHomePageId, $this->applyOnlineTabEnabledForSubCats)) {
			$this->availableTabsInfo["ApplyOnline"] =  array("NAME" => "Apply Online",'HELP_TXT'=>'Apply online to <> colleges');
		}
	}

	private function _getTabsSeoInfo($page)
	{
		$courseHomeNickname = $this->COURSE_HOME_ARRAY[$this->courseHomePageId]["Name"];

		foreach($this->availableTabsInfo as $tabName => $value) {

			switch($tabName) {
				case "Home":
                    $this->_getHomeTabSeoInfo($courseHomeNickname);
					break;

				case "Faq":
					$this->_getFaqTabSeoInfo($courseHomeNickname, $page);
					break;
				
				case "Institutes":
					$this->_getInstitutesTabSeoInfo();
					break;

                case "Discussions":
					$this->_getDiscussionsTabSeoInfo($courseHomeNickname, $page);
					break;
				
				case "Rankings":
					$this->_getRankingsTabSeoInfo();
					break;

				case "AskExperts":
					$this->_getAskExpertsTabSeoInfo($courseHomeNickname, $page);
					break;

				case "ApplyOnline":
					$this->_getApplyOnlineTabSeoInfo();
					break;

				case "News":
					$this->_getNewsTabSeoInfo($courseHomeNickname, $page);
					break;
 				case "CollegePredictor" :
 				//	$this->_getCollegePredictorTabSeoInfo($courseHomeNickname, $subCatObj);
					break;
 				case "Exams" : 
 					$this->_getExamsTabSeoInfo($courseHomeNickname);
 					break;	
 				case "MyShortlist" :
 				   $this->_getMyShortlistTabSeoInfo($courseHomeNickname);
 						break;
				case "StudentTools" : 
 					$this->_getStudentToolsTabSeoInfo($courseHomeNickname);
 					break;

 				case "studentMentor" :
 				$this->_getStudentMentorTabsSeoInfo($courseHomeNickname);
 				break;
			}
		}
	}

	
	
	private function _getMyShortlistTabSeoInfo($courseHomeNickname, $page)
	{
                $domainUrl=SHIKSHA_HOME;
		$this->availableTabsInfo["MyShortlist"]["URL"] = $this->getMyShortlistTabUrl($this->courseHomePageId, $domainUrl, $page);
		$this->availableTabsInfo["MyShortlist"]["TITLE"] = "Add to my Shortlist | Shiksha.com";
		$this->availableTabsInfo["MyShortlist"]["DESCRIPTION"] = "Add institutes to your shortlist to find placement data, read reviews, ask questions to current students and get alerts";
	}
	
	public function getAllTabSeoDetails($courseHomePageId, $page = 1){
		$this->courseHomePageId = $courseHomePageId;
		$this->_getCoursePagesAllTabs();
		$this->customizedTabsBar = array_keys($this->coursePagesAllTabs);
		$this->_getTabsOnPage();
		$this->_getTabsSeoInfo($page);
		$this->customizedTabsBar = "";
		return $this->availableTabsInfo;
	}

	private function _getHomeTabSeoInfo($courseHomeNickname)
	{
		$domainUrl = SHIKSHA_HOME;
		$this->availableTabsInfo["Home"]["URL"] = $this->getHomeTabUrl($this->courseHomePageId, $domainUrl);
		$this->availableTabsInfo["Home"]["TITLE"] = str_replace('{alias}', $courseHomeNickname, $this->coursePagesSEODataIndia['Home'][$this->courseHomePageId]['title']);
		$this->availableTabsInfo["Home"]["DESCRIPTION"] = str_replace('{alias}', $courseHomeNickname, $this->coursePagesSEODataIndia['Home'][$this->courseHomePageId]['description']);
		$this->availableTabsInfo["Home"]["KEYWORDS"] = $this->coursePagesSEODataIndia['Home'][$this->courseHomePageId]['keywords'];
	}

	private function _getInstitutesTabSeoInfo()
	{
		$this->availableTabsInfo["Institutes"]["URL"] = $this->getInstitutesTabUrl($this->courseHomePageId);
	}
	
	private function _getRankingsTabSeoInfo() {		
		$this->availableTabsInfo["Rankings"]["URL"] = $this->getRankingsTabUrl($this->courseHomePageId);
	}

	private function _getDiscussionsTabSeoInfo($courseHomeNickname, $page)
	{
		$domainUrl = SHIKSHA_HOME;
		//$this->availableTabsInfo["Discussions"]["URL"] = $this->getDiscussionsTabUrl($this->courseHomePageId, $domainUrl, $page);
		$this->availableTabsInfo["Discussions"]["URL"] = getSeoUrl($this->getTagId($this->courseHomePageId), 'tag', $this->getTagName()).'?type=discussion';
		$this->availableTabsInfo["Discussions"]["TITLE"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['Discussions'][$this->courseHomePageId]['title'];
		$this->availableTabsInfo["Discussions"]["DESCRIPTION"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['Discussions'][$this->courseHomePageId]['description'];
		$this->availableTabsInfo["Discussions"]["KEYWORDS"] = $page == 1 ? ($this->coursePagesSEODataIndia['Discussions'][$this->courseHomePageId]['keywords']) : "";
	}

	private function _getAskExpertsTabSeoInfo($courseHomeNickname, $page)
	{
		$domainUrl = SHIKSHA_HOME;
		//$this->availableTabsInfo["AskExperts"]["URL"] = $this->getAskExpertsTabUrl($this->courseHomePageId, $domainUrl, $page);
		$this->availableTabsInfo["AskExperts"]["URL"] = getSeoUrl($this->getTagId($this->courseHomePageId), 'tag', $this->getTagName());
		$this->availableTabsInfo["AskExperts"]["TITLE"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['AskExperts'][$this->courseHomePageId]['title'];
		$this->availableTabsInfo["AskExperts"]["DESCRIPTION"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['AskExperts'][$this->courseHomePageId]['description'];
		$this->availableTabsInfo["AskExperts"]["KEYWORDS"] = $page == 1 ? ($this->coursePagesSEODataIndia['AskExperts'][$this->courseHomePageId]['keywords']) : "";
	}

	private function _getApplyOnlineTabSeoInfo()
	{
		$this->availableTabsInfo["ApplyOnline"]["URL"] = $this->getApplyOnlineTabUrl($this->courseHomePageId);
	}

	private function _getNewsTabSeoInfo($courseHomeNickname, $page)
	{
		$domainUrl = SHIKSHA_HOME;
		$this->availableTabsInfo["News"]["URL"] = $this->getNewsTabUrl($this->courseHomePageId, $domainUrl, $page);
		$this->availableTabsInfo["News"]["TITLE"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['News'][$this->courseHomePageId]['title'];
		$this->availableTabsInfo["News"]["DESCRIPTION"] = ($page > 1 ? "Page $page - " : "").$this->coursePagesSEODataIndia['News'][$this->courseHomePageId]['description'];
		$this->availableTabsInfo["News"]["KEYWORDS"] = $page == 1 ? ($this->coursePagesSEODataIndia['News'][$this->courseHomePageId]['keywords']) : "";
	}
	
	private function _getFaqTabSeoInfo($courseHomeNickname) {
            
        if (!isset($this->coursePagesSEODataIndia['Faq'][$this->courseHomePageId])) {
            $this->availableTabsInfo["Faq"]["URL"] = $this->getFaqTabUrl($this->courseHomePageId);
            $this->availableTabsInfo["Faq"]["TITLE"] = str_replace('{alias}', $courseHomeNickname, $this->coursePagesSEODataIndia['Faq']['pattern']['title']);
            $this->availableTabsInfo["Faq"]["DESCRIPTION"] = str_replace('{alias}', $courseHomeNickname, $this->coursePagesSEODataIndia['Faq']['pattern']['description']);
            $this->availableTabsInfo["Faq"]["KEYWORDS"] = str_replace('{alias}', $courseHomeNickname, $this->coursePagesSEODataIndia['Faq']['pattern']['keywords']);
            return;
        }
        
        $this->availableTabsInfo["Faq"]["URL"] = $this->getFaqTabUrl($this->courseHomePageId);
        
        $this->availableTabsInfo["Faq"]["TITLE"] = str_replace('{alias}', $courseHomeNickname, $this->coursePagesSEODataIndia['Faq'][$this->courseHomePageId]['title']);
        $this->availableTabsInfo["Faq"]["DESCRIPTION"] = str_replace('{alias}', $courseHomeNickname, $this->coursePagesSEODataIndia['Faq'][$this->courseHomePageId]['description']);
        $this->availableTabsInfo["Faq"]["KEYWORDS"] = ($this->coursePagesSEODataIndia['Faq'][$this->courseHomePageId]['keywords']);
        }

	
	private function _getCollegePredictorTabSeoInfo($courseHomeNickname) {
		$domainUrl = SHIKSHA_HOME;
		$this->availableTabsInfo["CollegePredictorTab"]["URL"] = $this->getJeeMainTabTabUrl($this->courseHomePageId, $domainUrl);
		$this->availableTabsInfo["CollegePredictorTab"]["TITLE"] = "JEE Mains 2014 College Predictor - Shiksha.com";
		$this->availableTabsInfo["CollegePredictorTab"]["DESCRIPTION"] = "JEE Mains 2014 college predictor tool to know college based on your rank in JEE Mains entrance exam.";
	}

	private function _getExamsTabSeoInfo($courseHomeNickname) {
		$this->availableTabsInfo["Exams"]["URL"] = $this->getExamsTabUrl($this->courseHomePageId);
		$this->availableTabsInfo["Exams"]["TITLE"] = "";
		$this->availableTabsInfo["Exams"]["DESCRIPTION"] = "";
	
	}
	
	private function _getStudentToolsTabSeoInfo($courseHomeNickname) {
		$this->availableTabsInfo["StudentTools"]["URL"] = $this->getStudentToolsTabUrl($this->courseHomePageId);
		$this->availableTabsInfo["StudentTools"]["TITLE"] = "";
		$this->availableTabsInfo["StudentTools"]["DESCRIPTION"] = "";
	
	}

	private function _getStudentMentorTabsSeoInfo($courseHomeNickname) {
		$this->availableTabsInfo["studentMentor"]["URL"] = $this->getstudentMentorTabUrl($this->courseHomePageId);
		$this->availableTabsInfo["studentMentor"]["TITLE"] = "Get A Mentor";
		$this->availableTabsInfo["studentMentor"]["DESCRIPTION"] = "Get A Mentor";
	
	}
	
		
	private function _getUrls() {
		
		$domainUrl = SHIKSHA_HOME;

		foreach($this->availableTabsInfo as $tabName => $value) {
			$functionName = "get".$tabName."TabUrl";
			$this->availableTabsInfo[$tabName]["URL"] = $this->$functionName($this->courseHomePageId, $domainUrl);
		}
	}

	public function getHomeTabUrl($courseHomePageId) {
		if($this->isValidNumber($courseHomePageId)) {
                        return $this->COURSE_HOME_ARRAY[$courseHomePageId]['url'];
		}
	}

	public function getInstitutesTabUrl($courseHomePageId, $domainUrl = "") {
		if($this->isValidNumber($courseHomePageId)) {
			$this->CI->load->library('categoryList/CategoryPageRequest');
			$categoryPageRequest = new CategoryPageRequest;
			
			/* Added by : Romil Goel for RnR II
			* Purpose   : To change the URL of Institute Tab for Subcategories covered in RnR II i.e MBA,BE/B.tech
			*/
			$RNR_SUB_CATEGORY_IDS_LIST = array_keys($this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST"));
			if( in_array( $courseHomePageId, $RNR_SUB_CATEGORY_IDS_LIST ) )
				$categoryPageRequest->setNewURLFlag( _ID_FLAG_ON );

			$subCatObj = $this->getCategoryInfo($courseHomePageId);	
			$requestData['categoryId'] = $subCatObj->getParentId();	

				
			$requestData['subCategoryId'] = $courseHomePageId;
			$categoryPageRequest->setData($requestData);

			return $categoryPageRequest->getURL();
		}
	}

	public function getAskExpertsTabUrl($courseHomePageId, $domainUrl = "", $page = 1) {
		if($domainUrl == "") {
			$subCatObj = $this->getCategoryInfo($courseHomePageId);
			$domainUrl = $this->getDomainUrl($subCatObj->getParentId());
		}

		if($this->isValidNumber($courseHomePageId)) {
			$directoryName = $this->getDirectoryName($courseHomePageId);
			$url = $domainUrl."/".$directoryName.str_replace("{subcategory}", $this->COURSE_HOME_ARRAY[$courseHomePageId]['UrlKey'], $this->coursePagesSEODataIndia["AskExperts"][$this->defaultUrlKey]);
			$url = $this->getUrlWithPageNumber($page, $url, $courseHomePageId);
			return $url;
		}
	}
	
	public function getRankingsTabUrl($courseHomePageId) {
            return '';
		if($this->isValidNumber($courseHomePageId)) {
			$this->CI->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
			if($courseHomePageId == '56'){
				$urlIdentifier ="44-2-0-0-0";
			}else{
				$urlIdentifier = "2-2-0-0-0";
			}
			$rankingURLManager = RankingPageBuilder::getURLManager();
			$rankingPageRequest = $rankingURLManager->getRankingPageRequest($urlIdentifier);
			$url = $rankingURLManager->getCurrentPageURL($rankingPageRequest);
			return $url;
		}
		
	}

	public function getDiscussionsTabUrl($courseHomePageId, $domainUrl = "", $page = 1) {
		if($domainUrl == "") {
			$subCatObj = $this->getCategoryInfo($courseHomePageId);
			$domainUrl = $this->getDomainUrl($subCatObj->getParentId());
		}

		if($this->isValidNumber($courseHomePageId)) {
			$directoryName = $this->getDirectoryName($courseHomePageId);
			$url = $domainUrl."/".$directoryName.str_replace("{subcategory}", $this->COURSE_HOME_ARRAY[$courseHomePageId]['UrlKey'], $this->coursePagesSEODataIndia["Discussions"][$this->defaultUrlKey]);
			$url = $this->getUrlWithPageNumber($page, $url, $courseHomePageId);
			return $url;
		}
	}

	public function getNewsTabUrl($courseHomePageId, $domainUrl = "", $page = 1) {
		if($domainUrl == "") {
			$subCatObj = $this->getCategoryInfo($courseHomePageId);
			$domainUrl = $this->getDomainUrl($subCatObj->getParentId());
		}

		if($this->isValidNumber($courseHomePageId)) {
			$directoryName = $this->getDirectoryName($courseHomePageId);
			$url = $domainUrl."/".$directoryName.str_replace("{subcategory}", $this->COURSE_HOME_ARRAY[$courseHomePageId]['UrlKey'], $this->coursePagesSEODataIndia["News"][$this->defaultUrlKey]);
			$url = $this->getUrlWithPageNumber($page, $url, $courseHomePageId);
			return $url;
		}
	}

	public function getApplyOnlineTabUrl($courseHomePageId, $domainUrl = "") {
		if($this->isValidNumber($courseHomePageId)) {
			switch($courseHomePageId) {
				case 23 :
					$url = SHIKSHA_HOME."/mba/resources/application-forms";
					break;
				case 56 :
					$url = SHIKSHA_HOME."/college-admissions-engineering-online-application-forms";
					break;
			}

			return $url;
		}
	}
	
	public function getFaqTabUrl($courseHomePageId) {
        if ($this->isValidNumber($courseHomePageId)) {
            $faqUrl = $this->getFaqUrlFromCourseHomePageId($courseHomePageId, $this->COURSE_HOME_ARRAY);
            return $faqUrl;
        }
        return '';
    }

    public function getQuestionUrl($courseHomePageId, $questionId, $questionTitle, $domainUrl = "") {
		if($questionTitle == "" || $questionId == "") {
			return ;
		}
		
		$questionTitle = trim($questionTitle);		
		$questionTitle  = formatArticleTitle($questionTitle , 100);
		$questionTitle = seo_url_lowercase($questionTitle, "-");
		
		if($domainUrl == "") {
			$subCatObj = $this->getCategoryInfo($courseHomePageId);
			$domainUrl = $this->getDomainUrl($subCatObj->getParentId());
		}
		
		if($this->isValidNumber($courseHomePageId)) {
			$url = $domainUrl."/".str_replace("{subcategory}", $this->COURSE_HOME_ARRAY[$courseHomePageId]['UrlKey'], $this->coursePagesSEODataIndia["questionDetail"]['url']);
			$url = str_replace("{question_title}", $questionTitle, $url)."-".$questionId;
			return $url;
		}
	}
	
	public function getCollegePredictorTabUrl($courseHomePageId, $domainUrl = "")
	{
		
		$url = SHIKSHA_HOME."/jee-mains-college-predictor";
		
		return $url;
	}
	
	
	public function getExamsTabUrl($courseHomePageId, $domainUrl = "") {
		
		$url = "";
		
		return $url;
	}
	
	public function getStudentToolsTabUrl($courseHomePageId, $domainUrl = "") {
		
		$url = "";
		
		return $url;
	} 

	public function getstudentMentorTabUrl($courseHomePageId){
		if($courseHomePageId == 56)
		{
			$url = SHIKSHA_HOME."/mentors-engineering-exams-colleges-courses";
		}
		return $url;
	}
	
	public function getMyShortlistTabUrl($courseHomePageId, $domainUrl = "") {
	
		$url = SHIKSHA_HOME."/my-shortlist-home";
	
		return $url;
 	}
	
	public function getDomainUrl($categoryId)
	{
		if($categoryId > 1) {
			global $categoryURLPrefixMapping;
			return $categoryURLPrefixMapping[$categoryId];
		} else {
			return SHIKSHA_HOME;
		}
	}

	public function getCategoryInfo($courseHomePageId)
	{
		if(empty($this->CategoryObject[$courseHomePageId])) {
			$this->CI->load->builder('CategoryBuilder','categoryList');
			$categoryBuilder = new CategoryBuilder;
			$categoryRepository = $categoryBuilder->getCategoryRepository();
			$this->CategoryObject[$courseHomePageId] = $categoryRepository->find($courseHomePageId);
			return $this->CategoryObject[$courseHomePageId];
		}
		return $this->CategoryObject[$courseHomePageId];
	}

	public function getCourseHomePageFromCoursePageUrlKey($courseUrlKey, $pageNo)
	{
		//check for cases where new url is converted to old key for parsing
		if($pageNo != '') {
			$courseUrlKey = $courseUrlKey.'-'.$pageNo;
		}
		$response = $this->_parseUrlKey($courseUrlKey);
		if(isset($response['pageNo']) && !$this->isValidNumber($response['pageNo']) && $response['pageNo'] != 1) {
			show_404();
		}

		$data['courseHomePageId'] = $this->_parseCourseHomePageId($response['urlKey']);
        $data['tagId']    = $this->getTagId($data['courseHomePageId']);
		$data['coursePageType'] = $response['coursePageType'];
		if(isset($response['pageNo'])) {
			$data['pageNo'] = (int) $response['pageNo'];
		}

		return $data;
	}

	function checkForRedirection($data) {
		if($data['courseHomePageId'] < 0) {
			show_404();
		}
		$SubCategoryObject = $this->getCategoryInfo($data['courseHomePageId']);
		//old URL is coming
		if($SubCategoryObject->getSEOUrlDirectoryName() != ''){
			// $userInputURL = $_SERVER['SCRIPT_URI'];
			$userInputURL = getCurrentPageURLWithoutQueryParams();
			$userInputURL  = trim($userInputURL);
			$userInputURL  = trim($userInputURL,"/");
			$queryString = substr(strrchr($_SERVER['REQUEST_URI'], "?"), 0);
            $page 	  		= $data['pageNo'];
            $courseHomePageId 		= $data['courseHomePageId'];
            $coursePageType = $data['coursePageType'];
            switch($coursePageType) {
				case 'questions': 
					$actualURL = $this->getAskExpertsTabUrl($courseHomePageId, "", $page);
					break;
				case 'discussions':
					$actualURL = $this->getDiscussionsTabUrl($courseHomePageId, "", $page);
					break;
				case 'news':
					$actualURL = $this->getNewsTabUrl($courseHomePageId, "", $page);
					break;
				case 'faq':
					$actualURL = $this->getFaqTabUrl($courseHomePageId, "", $page = 1);
					break;
				default:
					$actualURL = $this->getHomeTabUrl($courseHomePageId);
					break;
			}
			if(!empty($actualURL) && $actualURL != $userInputURL) {
				redirect($actualURL.$queryString, 'location', 301);
			}
		}
		return;
	}

	private function _parseCourseHomePageId($urlKey)
	{
		$curUrl = $_SERVER['SCRIPT_URL'];
		foreach($this->COURSE_HOME_ARRAY as $courseHomePageId => $infoArray) {
			if($curUrl == $infoArray['oldUrl']) {
				return $courseHomePageId;
			}
		}
		return -1;
	}
    
    public function getTagId($courseHomePageId){
        if(isset($this->tagId)){
            return $this->tagId;
        }elseif($courseHomePageId > 0){
            if(key_exists($courseHomePageId, $this->COURSE_HOME_ARRAY) && $this->COURSE_HOME_ARRAY[$courseHomePageId]['tagId'] > 0){
                $this->tagId    = $this->COURSE_HOME_ARRAY[$courseHomePageId]['tagId'];
            }else{
                $this->tagId    = -1;
            }
            return $this->tagId;
        }else{
            $this->tagId    = NULL;
            return $this->tagId;
        }
    }
    
    public function getTagName(){
        if(isset($this->tagName)){
            return $this->tagName;
        }else{
            if(isset($this->tagId) && $this->tagId > 0){
                $this->CI->load->model('Tagging/taggingcmsmodel');
                $result = $this->CI->taggingcmsmodel->findTagById(array($this->tagId));
                $this->tagName = $result[0]['tags'];
                return $this->tagName;
            }else{
                return '';
            }
        }
    }

	private function _parseUrlKey($courseUrlKey)
	{		
		if(strpos($courseUrlKey, "-faq") !==  FALSE) {
			$coursePageType = "faq";
			$infoArray = split("-faq", $courseUrlKey);
		} elseif(strpos($courseUrlKey, "-news-articles") !==  FALSE) {
			$coursePageType = "news";
			$infoArray = split("-news-articles", $courseUrlKey);
		} elseif(strpos($courseUrlKey, "-discussions") !==  FALSE) {
			$coursePageType = "discussions";
			$infoArray = split("-discussions", $courseUrlKey);
		} elseif(strpos($courseUrlKey, "-questions") !==  FALSE) {
			$coursePageType = "questions";
			$infoArray = split("-questions", $courseUrlKey);
		} else {
			$coursePageType = "home";
			$infoArray[0] = $courseUrlKey;
		}

		$response['urlKey'] = $infoArray[0];
		$response['coursePageType'] = $coursePageType;
		if(isset($infoArray[1]) && $infoArray[1] != "") {
			$response['pageNo'] = str_replace("-", "", $infoArray[1]);
		}
		return $response;
	}

	public function isValidNumber($courseHomePageId) {
		if($courseHomePageId == "" || !is_numeric($courseHomePageId)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/*
		This function is deprecated, a new function has been made below it for future use
		@Rahul Bhatnagar
	*/
	public function getAllCoursePagesURL() {
		
		global $COURSE_HOME_ARRAY;
		$subcategories = array_keys($COURSE_HOME_ARRAY);		
		$url_list = array();
		
		foreach ($subcategories as $subcat_id) {
			
			$subcat_object = $this->getCategoryInfo($subcat_id);					
			$cat_id = $subcat_object->getParentId();
			$domain_url = $this->getDomainUrl($cat_id);				
			$home_url = $this->getHomeTabUrl($subcat_id,$domain_url);
			$news_url = $this->getNewsTabUrl($subcat_id,$domain_url);
			$discussion_url = $this->getDiscussionsTabUrl($subcat_id,$domain_url);
			$questions_url = $this->getAskExpertsTabUrl($subcat_id,$domain_url);
			
			$url_list[$cat_id][] = $home_url;
			$url_list[$cat_id][] = $news_url;
			$url_list[$cat_id][] = $discussion_url;
			$url_list[$cat_id][] = $questions_url;
			 
		}
		
		return $url_list;
	}	

	public function getAllCoursePagesUrlNew(){
		$courseCommonLib=$this->CI->load->library('coursepages/CoursePagesCommonLib');
        $allCourseData = $courseCommonLib->getCourseHomePageDictionary(0);
        $url_list = array();
        foreach($allCourseData as $homePageId => $courseData){
        	$home_url = $courseData['url'];
        	$url_list[] = $home_url;
        }
        return $url_list;
	}
	
	public function checkIfMobileDevice() {
		global $typeOfMachine;
		if(isset($_COOKIE['ci_mobile']) || (isset( $typeOfMachine) &&  $typeOfMachine=='mobile')){            
		    return TRUE;
		}
		
		return FALSE;
	}

	function getDirectoryName($courseHomePageId) {
		$this->defaultUrlKey = 'url';
		if(isset($this->CategoryObject[$courseHomePageId]) && strtolower($this->CategoryObject[$courseHomePageId]->getSEOUrlDirectoryName()) != '') {
			$this->defaultUrlKey = 'newSeoUrl';
			return $this->CategoryObject[$courseHomePageId]->getSEOUrlDirectoryName().'/';
		}
		return '';
	}

	function getUrlWithPageNumber($page, $url, $courseHomePageId) {
		if($page > 1) {
			if(isset($this->CategoryObject[$courseHomePageId]) && strtolower($this->CategoryObject[$courseHomePageId]->getSEOUrlDirectoryName()) != '') {
				$url = str_replace("/PAGE", "/$page", $url);
			}
			else {
				$url = str_replace("-PAGE-", "-$page-", $url);
			}
		} else {
			if(isset($this->CategoryObject[$courseHomePageId]) && strtolower($this->CategoryObject[$courseHomePageId]->getSEOUrlDirectoryName()) != '') {
				$url = str_replace("/PAGE", "", $url);
			}
			else {
				$url = str_replace("-PAGE-", "-", $url);
			}
		}

		return $url;
	}
    
    public function prepareRecentThreadsForCoursePages($subCategoryToTagMapping = array(), $returnData = FALSE){
        
        if(!is_array($subCategoryToTagMapping) || empty($subCategoryToTagMapping)){
            return array();
        }
        $subCategoryToTagMapping = array_filter($subCategoryToTagMapping);
        if(count($subCategoryToTagMapping) == 0){
            return array();
        }
        global $Question_DISCUSSION_COUNT_ARRAY;
        $questionDiscussionCountArray   = $Question_DISCUSSION_COUNT_ARRAY;
        $this->CI->load->model('messageBoard/QnAModel');
        $this->CI->load->library('coursepages/cache/CoursePagesCache');
        $dateToCheckForQuestions    = date('Y-m-d',  strtotime("- 6 month"));
        $dateToCheckForDiscussions  = date('Y-m-d',  strtotime("- 1 year"));
        $currentDateTimeEpoch       = strtotime('NOW');
        $finalDataToStoreInCache    = array();
        foreach($subCategoryToTagMapping as $subCategoryId => $tagId){
            $finalDataToStoreInCache[$subCategoryId][$tagId]    = array();
            /* Start Getting Questions */
            $questions = $this->CI->QnAModel->getQuestionByPopularityNew($subCategoryId, $dateToCheckForQuestions, $tagId, $questionDiscussionCountArray['countOfQuestionsToStoreInCache']);
            if(is_array($questions['boost']) && count($questions['boost']) > 0){
                $finalDataToStoreInCache[$subCategoryId][$tagId]['questions'] = array_slice($questions['boost'], 0, 5);
            }
            $questionsNeededFromSorting = $questionDiscussionCountArray['countOfQuestionsToStoreInCache'] - count($finalDataToStoreInCache[$subCategoryId][$tagId]['questions']);
            if($questionsNeededFromSorting > 0 && count($questions['normal']) > 0){
                $threadsRecentContentTime   = $this->CI->QnAModel->getTimeOfRecentContentOnThreads(array_keys($questions['normal']), 'question');
                foreach ($threadsRecentContentTime as $threadId => $creationDate){
                    $questions['normal'][$threadId]['creationDate'] = $creationDate;
                    $recencyScore   = $this->_getRecencyScore(($currentDateTimeEpoch - strtotime($creationDate)));
                    $questions['normal'][$threadId]['sortingScore'] = log($questions['normal'][$threadId]['qualityScore'], 2) + $recencyScore;
                }
                uasort($questions['normal'], function($a, $b){
                    if($a['sortingScore'] == $b['sortingScore']){
                        return 0;
                    }else {
                        return ($a['sortingScore'] < $b['sortingScore']) ? 1 : -1;
                    }
                });
                reset($questions['normal']);
                do{
                    $finalDataToStoreInCache[$subCategoryId][$tagId]['questions'][] = key($questions['normal']);
                    $questionsNeededFromSorting -= 1;
                }  while ($questionsNeededFromSorting > 0 && next($questions['normal']));
            }
            unset($questions);
            unset($threadsRecentContentTime);
            /* End Getting Questions */
            
            /* Start Getting Discussions */
            $discussions = $this->CI->QnAModel->getDiscussionsByPopularityNew($subCategoryId, $dateToCheckForDiscussions, $tagId, $questionDiscussionCountArray['countOfDiscussionsToStoreInCache']);
            if(is_array($discussions['boost']) && count($discussions['boost']) > 0){
                $finalDataToStoreInCache[$subCategoryId][$tagId]['discussions'] = array_slice($discussions['boost'], 0, 5);
            }
            $discussionsNeededFromSorting = $questionDiscussionCountArray['countOfDiscussionsToStoreInCache'] - count($finalDataToStoreInCache[$subCategoryId][$tagId]['discussions']);
            if($discussionsNeededFromSorting > 0 && count($discussions['normal']) > 0){
                $threadsRecentContentTime   = $this->CI->QnAModel->getTimeOfRecentContentOnThreads(array_keys($discussions['normal']), 'discussion');
                foreach ($threadsRecentContentTime as $threadId => $creationDate){
                    $discussions['normal'][$threadId]['creationDate'] = $creationDate;
                    $recencyScore   = $this->_getRecencyScore(($currentDateTimeEpoch - strtotime($creationDate)));
                    $discussions['normal'][$threadId]['sortingScore'] = log($discussions['normal'][$threadId]['qualityScore'], 2) + $recencyScore;
                }
                uasort($discussions['normal'], function($a, $b){
                    if($a['sortingScore'] == $b['sortingScore']){
                        return 0;
                    }else {
                        return ($a['sortingScore'] < $b['sortingScore']) ? 1 : -1;
                    }
                });
                reset($discussions['normal']);
                do{
                    $finalDataToStoreInCache[$subCategoryId][$tagId]['discussions'][] = key($discussions['normal']);
                    $discussionsNeededFromSorting -= 1;
                }while($discussionsNeededFromSorting > 0 && next($discussions['normal']));
            }
            unset($discussions);
            unset($threadsRecentContentTime);
            /* End Getting Discussions */
        }
        
        foreach($subCategoryToTagMapping as $subCategoryId => $tagId){
            if(key_exists($subCategoryId, $finalDataToStoreInCache)){
            	$this->CI->coursepagescache->storeQuestionsData($tagId, $finalDataToStoreInCache[$subCategoryId][$tagId]['questions']);
            	$this->CI->coursepagescache->storeDiscussionsData($tagId, $finalDataToStoreInCache[$subCategoryId][$tagId]['discussions']);
            }
        }
        if($returnData === TRUE){
            return $finalDataToStoreInCache;
        }
    }
    
    private function _getRecencyScore($durationInSeconds=1){
        $score = 0;
        $halfLifeForThreads = 86400;
        if($durationInSeconds <= 0){
            return $score;
        }
        $exponentFactor = log(2) * ($durationInSeconds / $halfLifeForThreads);
        $score = (1 / exp($exponentFactor)) * 100;
        return $score;
    }
    public function getFaqUrlFromCourseHomePageId($courseHomePageId,$courseHomePageDictionary){
       
        $courseHomePageUrl=$courseHomePageDictionary[$courseHomePageId]['url'];
        $courseHomePageUrl = substr($courseHomePageUrl, 0, strpos($courseHomePageUrl, "-chp"));
        $faqUrl=$courseHomePageUrl."/resources/faq-".$courseHomePageId;
        return $faqUrl;
        
    }
    public function getQuestionUrlByQuestionIdAndCourseHomePageId($courseHomePageId,$questionId,$courseHomePageDictionary){
        $faqUrl=$this->getFaqUrlFromCourseHomePageId($courseHomePageId, $courseHomePageDictionary);
        
        return $faqUrl.'#'.$questionId;
    }
}
