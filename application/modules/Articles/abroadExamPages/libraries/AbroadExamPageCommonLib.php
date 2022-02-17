<?php
class AbroadExamPageCommonLib
{
    private $CI;
    //private $examCMSModelObj;
    
    function __construct()
    {
        $this->CI =& get_instance();
        $this->_setDependecies();
    }

    function _setDependecies()
    {
        //$this->CI->load->model('examPages/examcmsmodel');
        //$this->examCMSModelObj  = new examcmsmodel();
    }
    
    
    /*
     * Author	: Abhinav
     * Purpose	: Get URL for Abroad Exam Pages at run-time
     * Parameters : examId,urlString
     */
    function getExamPageSectionData($urlString='')
    {
		$this->CI->config->load('abroadExamPageConfig');
		$examPageConfig = $this->CI->config->item('abroad_exam_page_section_details');
		$sections = array();
		foreach($examPageConfig as $configItem){
			$sections[$configItem['urlPattern']] = 0;
		}
		$abroadListingCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
		$examList = $abroadListingCommonLib->getAbroadExamsMasterListFromCache();
		foreach($examList as $data){
			$urlString = str_ireplace(strtolower($data['exam']),'',$urlString);
		}
		$sectionId = 1;
		$sectionPart = '';
		if($urlString != ''){
			if($urlString[0] == '-'){
			$urlString = substr($urlString,1);
			}
			foreach($sections as $key=>$value){
			similar_text($key,$urlString,$sections[$key]);
			}
			arsort($sections);
			foreach($sections as $key=>$value){
			if($value>50){
				$sectionPart = $key;
			}
			break;
			}
		}
		foreach($examPageConfig as $key=>$data){
			if($data['urlPattern'] == $sectionPart){
			$sectionId = $key;
			break;
			}
		}
	
		return array('sectionId'	=> $sectionId,
				 'sectionName'		=> $sectionPart);
    }

	private function _validateExamPageSectionURLsInputs(&$examPage, &$contentId, &$examName) {
		if(!is_object($examPage)) {
			return 0;
		}

	    	if($contentId =='')
	    	{
	    		$contentId = $examPage->getExamPageId();
	    	}
	    	if($examName == '')
	    	{
	    		$examName = $examPage->getExamName();
	    	}

		return 1;
	}
    
    /*This function is used to prepare exam section urls and section name is section url pattern being defined in the config*/
    
    function examPageSectionURL($examPage,$sectionName,$redirect=false,$contentId='',$examName='')
    {

	if(!$this->_validateExamPageSectionURLsInputs($examPage, $contentId, $examName)) {
		return '';
	}
	/*
	if(!is_object($examPage)) {
		return "";
	}

    	if($contentId =='')
    	{
    		$contentId = $examPage->getExamPageId();
    	}
    	if($examName == '')
    	{
    		$examName = $examPage->getExamName();
    	}
	*/	
	if($sectionName != ''){
		$urlString = $examName.'-'.$sectionName.'-abroadexam'.$contentId;
	}else{
		$urlString = $examName.'-abroadexam'.$contentId;
	}
	$recommendedUrl = SHIKSHA_STUDYABROAD_HOME."/".seo_url_lowercase($urlString);
	$userEnteredURL = getCurrentPageURLWithoutQueryParams();
	if($userEnteredURL != $recommendedUrl && $redirect){
		redirect($recommendedUrl, 'location', 301);
	}
	return $recommendedUrl;
    }
    
    
    public function getExamRelatedArticles($examPage) {
	$model = $this->CI->load->model('blogs/sacontentmodel');
	$articlesData = $model->getExamRelatedArticles($examPage->getExamId());
	return $articlesData;	
    }
	
    public function getExamPageDownloadCount($examPageId){
	    $examPageModel = $this->CI->load->model('abroadExamPages/abroadexampagemodel');
	    $count = $examPageModel->getExamPageDownloadCount($examPageId);
	    return $count;
    }
    

    /*this function prepares section urls for more than one exampageids */
    public function prepareSectionUrls($examPageIds)
    {
       	
    	$this->CI->config->load('abroadExamPageConfig');
		$examPageConfig 	= $this->CI->config->item('abroad_exam_page_section_details');
		$sectionNames 		= array();
		$sectionUrls  		= array();
		$urlPattern = array();
		foreach($examPageConfig as $configItem)
		{
			$sectionNames[$configItem['name']] = $configItem['title'];
			$urlPattern[$configItem['name']] = $configItem['urlPattern'];
		}
    	foreach($examPageIds as $examName => $examPageId)
    	{
    		$i=0;
    		foreach($sectionNames as $sectionName=>$sectionTitle)
    		{
    			$sectionTitle = str_replace('<exam-name>', $examName,$sectionTitle);
    			$sectionUrls[$examName][$i]['sectionTitle'] = $sectionTitle;
				$sectionUrls[$examName][$i]['url'] 		   = $this->examPageSectionURL('',$urlPattern[$sectionName],'',$examPageId,$examName);
				$i++;
    		}
    		
    	
    	}
    	//_p($sectionUrls);
    	return $sectionUrls;
    }

    public function getContentNavigationLinks($contentTypeId,$contentType){    	
		$examPageModel  = $this->CI->load->model('abroadExamPages/abroadexampagemodel');
		$saContentCache = $this->CI->load->library('blogs/cache/saContentCache');
		$navData        = $saContentCache->getContentPageNavLinks($contentTypeId,$contentType);
		
        if(empty($navData)){        	
        	$navData = $examPageModel->getContentNavigationLinks($contentTypeId,$contentType);
        	$saContentCache->setContentPageNavLinks($contentTypeId,$contentType, $navData);
        }
        $allContentIds = array_keys($navData['links_data']);
        $contentURLs = $examPageModel->getAllContentURLs($allContentIds);
        foreach ($navData['links_data'] as $key=>&$value) {
        	$value = array('label'=>$value, 'url'=>SHIKSHA_STUDYABROAD_HOME.$contentURLs[$key]);
        }
        return $navData;
    }
}