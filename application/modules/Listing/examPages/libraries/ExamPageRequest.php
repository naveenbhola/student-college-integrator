<?php
/*
desc 	: the below code is used to validate exampage url's
author  : akhter
*/
class ExamPageRequest
{
	private $examName;
	private $examId;
	private $url;
	private $sectionName;
	private $isRootUrl;

	public function __construct($examPageURLQueryString = '') {
		$this->CI = &get_instance();
		$this->CI->load->config("examPages/examPageConfig");
		if(!empty($examPageURLQueryString)){
			$this->buildFromURLQueryString($examPageURLQueryString);
		}
		
	}

	/**
	 * extract exam name and section name from query string
	 * @param  string $examPageURLQueryString
	 * @return 
	 */
	public function buildFromURLQueryString($examPageURLQueryString)
	{
		if(empty($examPageURLQueryString )){
			return 0;
		}
		$examPageURLQueryString = $this->sanitizeURL($examPageURLQueryString);		
		//parse url string
		$this->parse($examPageURLQueryString);
	}

	/**
	 * removing unexpected character
	 * @param  string $urlString
	 * @return string
	 */
	private function sanitizeURL( $urlString ) {

		$uri = htmlspecialchars_decode($urlString);
        $uri = htmlspecialchars_decode($uri);
		$uri = preg_replace("`\[.*\]`U","",$uri);
		$uri = preg_replace('`&(amp;)%#!?#?[^A-Za-z0-9]+;`i','-',$uri);
		$uri = preg_replace(array("`[^a-z0-9@]`i","`[-]+`") , "-", $uri);
		$uri = strtolower(trim($uri, '-'));
        $uri_array = explode('-',$uri);
        $uri_array = array_slice($uri_array,0,(30-1));
        $uri = ucwords(implode(' ',$uri_array));
        $uri = str_replace(" ", '-', $uri);

		$urlString = strtolower($uri);
        return $urlString;

	}

	private function parse($urlQueryString){
		$urlQueryStringArr = explode('@',$urlQueryString);
		$urlQueryString    = $urlQueryStringArr[0];
		$remainUrlString   = $urlQueryStringArr[1];

		$this->examId      = '';
		$this->examName    = '';
		$this->url         = '';
		$this->sectionName = '';
		$this->isRootUrl   = '';

		$this->examPageLib = $this->CI->load->library('examPages/ExamPageLib');
    	$examData = $this->examPageLib->getExamBasicByName($urlQueryString);
    	
    	if(empty($examData)){
    		$urlQueryString = $urlQueryString.'-exam'; // for those exams who have "exam" keywords in exam name
    		$examData = $this->examPageLib->getExamBasicByName($urlQueryString);
    	}
  		
  		$this->examId   = $examData['examId'];
  		$this->examName = $examData['examName'];
  		$this->url      = $examData['url'];
  		$this->isRootUrl= $examData['isRootUrl'];
  		
  		if($remainUrlString == 'sample-papers'){
  			$remainUrlString = 'question-papers';
  		}
		if(empty($remainUrlString)) {
			$this->sectionName = 'homepage';
		}else{
			$remainUrlString = ($remainUrlString == 'important-dates') ? 'dates' : $remainUrlString; 
			$validSectionNames = $this->CI->config->item('examPagesActiveSections');
			$this->sectionName = array_search($remainUrlString,$validSectionNames);
		}
		
		if($this->sectionName == '') {
			$this->sectionName = 'homepage';
		}
	}

	public function getNewExamByOldExam($oldExamName){
		$this->ExamPageCache = $this->CI->load->library('examPages/cache/ExamPageCache');
		$newExamName = $this->ExamPageCache->getRedirectExamsList($oldExamName);
		if(empty($newExamName)){
			$this->exampagemodel = $this->CI->load->model('examPages/exampagemodel');
			$redirectExamsList   = $this->exampagemodel->getRedirectExams();
			$redirectExams = array();
			if(count($redirectExamsList) > 0){
				foreach($redirectExamsList as $row){
					$oldName = strtolower(seo_url($row['oldName']));
					$redirectExams[$oldName] = $row['newName'];
				}
			}
        	$this->ExamPageCache->storeRedirectExamsList($redirectExams);
        	return $redirectExams[$oldExamName];
		}
		return $newExamName;	
	}

	/**
	 * creating seo url for exam pages if empty argument then return current url
	 * @param  string $sectionName
	 * @return Array array('domain'=>'http://www.shiksha.com','url'=>'http://www.shiksha.com/cat-exampage')
	 */
	public function getUrl($sectionName = '',$onlyurl = false, $isAmp = false, $groupId=0, $isRootUrl) {
		$examName		   = $this->examName;
		$isRootUrl         = ($isRootUrl == 'Yes') ? 'Yes' : $this->isRootUrl;
		$validSectionNames = $this->CI->config->item('examPagesActiveSections');
		
		//validaty of $sectionName
		if(empty($sectionName) && !empty($this->sectionName)) {
			$sectionName   = $validSectionNames[$this->sectionName];
		}else{
			$sectionName   = $validSectionNames[$sectionName];
		}
		
		//sanitising section Name
	 	$sectionName4Url = $this->sanitizeURL($sectionName);
	 	if($sectionName4Url == 'homepage'){
	 		$sectionName4Url = '';
	 	}
	 	
		$url = ($this->url) ? SHIKSHA_HOME.$this->url : '';
		
		//generating url body
		if(!empty($isRootUrl) && $isRootUrl == 'Yes' && !empty($sectionName4Url) && !empty($url)){
			$url = $url.'/'.$sectionName4Url;
		}else if(!empty($sectionName4Url) && !empty($url)){
			$url = $url.'-'.$sectionName4Url;
		}
		
		if(!empty($isRootUrl) && $isRootUrl == 'Yes' && $isAmp){
			$url = str_replace('exams/', 'exams/amp/', $url);
		}else if($isAmp){	
			$urlArr    = split('/', $url);
			$ampPrefix = 'amp/'.end($urlArr);
			$url = str_replace(end($urlArr), $ampPrefix, $url);
		}

		if(isset($groupId) && !empty($groupId) && $groupId>0 && !empty($url)){
			$url = $url.'?course='.$groupId;
		}
        
		if($onlyurl){
			return $url;
		}else{
			$urlData['domain'] = SHIKSHA_HOME;
        	$urlData['url']    = $url;
			return $urlData;	
		}
	}


	/**
	 * fetch Exam Name
	 * @return string exam name
	 */
	public function getExamId()
	{
		return $this->examId;
	}

	public function getExamName()
	{
		return $this->examName;
	}

	/**
	 * fetch section name
	 * @return string section name
	 */
	public function getSectionName()
	{
		return $this->sectionName;
	}

	public function isRootUrl()
	{
		return $this->isRootUrl;
	}

	/**
	 * set exam name. if exam name is valid then set examName
	 * @param string $examName 
	 */
	public function setExamName($examName)
	{
		$this->examPageLib = $this->CI->load->library('examPages/ExamPageLib');
		$examData = $this->examPageLib->getExamBasicByName($examName);
		if(!empty($examData) && $examData['examId']){
			$this->examId   = $examData['examId'];
	  		$this->examName = $examData['examName'];
	  		$this->url      = $examData['url'];
	  		$this->isRootUrl= $examData['isRootUrl'];
		}
	}

	/**
	 * validate url
	 **/
	public function validateUrl($params, $isAmp=false, $isRootUrl) {
        //In case of davuet & davumet exams, we need to redirect to ULP
        $exam = explode('@', $params);
        $exnm = $exam[0];
        if(in_array($exnm, array('davuet','davumet'))){
                $redirectURL = SHIKSHA_HOME."/university/dav-university-jalandhar-42902";
                header("Location: $redirectURL",TRUE,301);exit;
        }

		$seoUrlArray =  $this->getUrl('', false, $isAmp, 0, $isRootUrl);

	 	$currentUrl  =  getCurrentPageURL();              // get Current Url
	 	$splitCurrentUrl   = explode('?',$currentUrl);  // split current url 

	 	// if url contains query paramater then append query paramater to correct url
	    if(isset($splitCurrentUrl[1]) && !empty($splitCurrentUrl[1])) {
	    	$redirectUrl = $seoUrlArray['url']."?".$splitCurrentUrl[1];
	    } else {
	    	$redirectUrl = $seoUrlArray['url'];	
	    }
		
		//redirecting to correct url
		if(!empty($seoUrlArray['url']) && strcmp($splitCurrentUrl[0], $seoUrlArray['url']) !== 0){
			header("Location: $redirectUrl",TRUE,301);exit;
		}else if(empty($seoUrlArray['url'])){
			//redirect old to new exam
			$data  = $this->prepareRedirectExamData($params);
			if(!empty($data['newExamName'])) {
				$this->setExamName($data['newExamName']);
				$url  = $this->getUrl($data['section'],true, $isAmp);
				$url  = ($splitCurrentUrl[1]) ? $url."?".$splitCurrentUrl[1] : $url;
				if($url){
					redirect($url, 'location', 301);
				}else{
					show_404();die();	
				}
			}else{
				show_404();die();	
			}
		}
	}

	function prepareRedirectExamData($params){
		$exam = explode('@', $params);
		$exnm = $exam[0];
		$ExamPageLib = $this->CI->load->library('examPages/ExamPageLib');
		$oldExamName = $this->sanitizeURL($exnm);
		$newExamName = $this->getNewExamByOldExam($oldExamName);
		if(empty($newExamName)){
			$oldExamName = $oldExamName.'-exam'; // for those exams who have "exam" keywords in exam name
			$newExamName = $this->getNewExamByOldExam($oldExamName);
		}
		$section  = $exam[1];
		$validSectionNames = $this->CI->config->item('examPagesActiveSections');
		$section = ($section == 'important-dates') ? 'dates' : $section; 
		$key = array_search($section,$validSectionNames);
		$data['section']     = $key;
		$data['newExamName'] = $newExamName;
		return $data;
	}
}
