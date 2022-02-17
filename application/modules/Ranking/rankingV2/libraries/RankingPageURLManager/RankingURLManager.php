<?php

class RankingURLManager {
	
	private $rankingModel;
	private $locationRepo;
	private $rankingPageSpecializationIdMapping = array(2=>array(7,8,9,10,11,12,13,14,15,16,17));
	private $UgSubCategoryIds = array(18,28,32,33,64,69,83,84,100,133);
	private $rankingPgeIdToNameMapping = array(2 => "Full time MBA/PGDM");
	public function __construct($locationBuilder, $rankingModel){
		if(!empty($locationBuilder) && !empty($rankingModel)){
			$this->locationRepo	= $locationBuilder->getLocationRepository();
			$this->rankingModel = $rankingModel;
			$this->CI = &get_instance();
			$this->rankingCache = $this->CI->load->library(RANKING_PAGE_MODULE.'/cache/RankingPageCache');
			$this->CI->load->builder('ListingBaseBuilder','listingBase');
			$builder = new ListingBaseBuilder;
			$this->baseCourseRepo = $builder->getBaseCourseRepository();
		}
	}
	
	/**
	 *@method: Take the URL identifier e.g 12-2-0-0-0 and extract ranking page id, country id, state id, city id, exam id from identifier and
	 *Create a request object with the URL param values
	 */
	public function getRankingPageRequest($urlIdentifierParam = NULL) {
		$rankingPageRequest = false;
		$urlIdentifier = $urlIdentifierParam;
		if(empty($urlIdentifier)){
			return $rankingPageRequest;
		}
		$urlIdentifier = trim($urlIdentifier, "-");
		$urlParams 	   = explode("-", $urlIdentifier);
		$params 	   = array();
		if(count($urlParams) >= 5){
			if(
			   is_numeric($urlParams[0]) &&
			   is_numeric($urlParams[1]) &&
			   is_numeric($urlParams[2]) &&
			   is_numeric($urlParams[3]) &&
			   is_numeric($urlParams[4]) 
			){
				$params['rankingPageId'] 	= (int)$urlParams[0];
				$params['countryId'] 	   	= (int)$urlParams[1];
				$params['stateId'] 	   		= (int)$urlParams[2];
				$params['cityId'] 	   		= (int)$urlParams[3];
                                $examId                                 = (int)$urlParams[4];
                                $examId = $this->_checkIfExamIdChanged($examId);
				$params['examId'] 	   	   	= $examId;
                                
				$rankingPageRequest = $this->getRankingPageRequestFromDataArray($params);	
			}
		}
		return $rankingPageRequest;
	}
        
        private function _checkIfExamIdChanged($examId){
            $this->CI->load->config(RANKING_PAGE_MODULE.'/rankingConfig');
            $mapping = $this->CI->config->item('examIdOldToNewMapping');
            if(!empty($mapping[$examId])){
                return $mapping[$examId];
            }
            return $examId;
        }


        /**
	 * @method: This function takes a data array with keys like cityId, stateId, countryId, rankingPageId etc.
	 * If the values like statename, cityname etc are empty but their associated ids are non empty than this method will
	 * try to fetch their values and make a request object.
	 */
	public function getRankingPageRequestFromDataArray($paramArray = array()){
		$rankingPageId 		= $paramArray['rankingPageId'];
		$rankingPageRequest = false;
		if(empty($rankingPageId)){ //Only RankingPageId is must for a page to display, other params can be empty or default
			return $rankingPageRequest;
		}
		
		$cityId 			= $paramArray['cityId'];
		$stateId 			= $paramArray['stateId'];
		$countryId 			= $paramArray['countryId'];
		$examId 			= $paramArray['examId'];
		$cityName   		= $paramArray['cityName'];
		$stateName  		= $paramArray['stateName'];
		$countryName   		= $paramArray['countryName'];
		$examName   		= $paramArray['examName'];
		$rankingPageName    = $paramArray['rankingPageName'];
		
		$rankingPageRequest = new RankingPageRequest();
		$rankingPageRequest->setPageId($rankingPageId);
		$rankingPageRequest->setCountryId($countryId);
		$rankingPageRequest->setStateId($stateId);
		$rankingPageRequest->setCityId($cityId);
		$rankingPageRequest->setExamId($examId);	
		$params = array();
		$params['id'] 			 	= $rankingPageId;
		$params['status']		 	= array('live');
		if(!empty($_REQUEST['skipstatuscheck']) && $_REQUEST['skipstatuscheck'] == "true"){
			$params['status']		 	= array('live', 'disable', 'draft');
		}
		$rankingPages 				= $this->getRankingPagesUsingCache($params);
		if(!empty($rankingPages)){
			$rankingPageName 	= $rankingPages[0]['ranking_page_text'];
		} else {
			$params['status']		 	= array('disable', 'delete');
			$rankingPages 				= $this->rankingModel->getRankingPages($params);
			$rankingPageRequest->setIsRankingPageLive(false);
		}
		$rankingPageRequest->setStreamId($rankingPages[0]['stream_id']);
		$rankingPageRequest->setSubstreamId($rankingPages[0]['substream_id']);
		$rankingPageRequest->setSpecializationId($rankingPages[0]['specialization_id']);
		$rankingPageRequest->setEducationType($rankingPages[0]['education_type']);
		$rankingPageRequest->setDeliveryMethod($rankingPages[0]['delivery_method']);
		$rankingPageRequest->setCredential($rankingPages[0]['credential']);
		$rankingPageRequest->setBaseCourseId($rankingPages[0]['base_course_id']);
		$rankingPageRequest->setPageName($rankingPageName);
		
		if(!empty($cityId)){
			if(empty($cityName)){
				$cityObject = $this->locationRepo->findCity($cityId);
				$cityName   = $cityObject->getName();
			}
			$rankingPageRequest->setCityName($cityName);
		}
		
		if(!empty($stateId)){
			if(empty($stateName)){
				$stateObject = $this->locationRepo->findState($stateId);
				$stateName   = $stateObject->getName();
			}
			$rankingPageRequest->setStateName($stateName);
		}
		
		if(!empty($countryId)){
			if(empty($countryName)){
				$countryObject = $this->locationRepo->findCountry($countryId);
				$countryName   = $countryObject->getName();
			}
			$rankingPageRequest->setCountryName($countryName);
		}
		
		if(!empty($examId)){
			if(empty($examName)){
				$examDetails = $this->rankingModel->getExamById($examId);
				if(!empty($examDetails)){
					$examName 	 = $examDetails['name'];
				}
			}
			$rankingPageRequest->setExamName($examName);
		}
		return $rankingPageRequest;
	}

	public function getRankingPagesUsingCache($params){
		if($this->CI->enableRankingPageCache && isset($params['status']) && count($params['status']) == 1){
			$rankingPages = $this->rankingCache->getRankingPages($params['id'],implode(',', $params['status']));
			if(empty($rankingPages)){
				$rankingPages = $this->rankingModel->getRankingPages($params);
				if(!empty($rankingPages)){
					$this->rankingCache->storeRankingPages($params['id'],implode(',', $params['status']),$rankingPages);
				}
			}
		}
		else{
			$rankingPages = $this->rankingModel->getRankingPages($params);
		}
		return $rankingPages;
	}
	
	/**
	 * @method: This function corrects the ranking page request with filter values. It handles the wrongly typed URL identifier value.
	 * This function corrects the current ranking page request object if there is any anomaly in the request object
	*/
	public function correctRankingPageRequestUsingFilterValues(RankingPageRequest $rankingPageRequest, $filters = array()){
		if(empty($rankingPageRequest) || empty($filters)){
			return;
		}
		$requestCityId  	= $rankingPageRequest->getCityId();
		$requestStateId 	= $rankingPageRequest->getStateId();
		$requestExamId  	= $rankingPageRequest->getExamId();
		$requestCountryId 	= $rankingPageRequest->getCountryId();
		
		$cityFilters 	= $filters['city'];
		$stateFilters 	= $filters['state'];
		$examFilters 	= $filters['exam'];
		
		$citySelectedFilter = $this->getSelectedFilterFromFiltersList($cityFilters);
		if(!empty($citySelectedFilter)){
			$selectedCityName 	= $citySelectedFilter->getName();
			$selectedCityId 	= $citySelectedFilter->getId();
			if($selectedCityId != $requestCityId){
				if(strtolower($selectedCityName) == 'all'){
					$rankingPageRequest->setCityId(0);
					$rankingPageRequest->setCityName("");
				} else {
					$cityObject = $this->locationRepo->findCity($selectedCityId);
					$rankingPageRequest->setCityName($cityObject->getName());
					$rankingPageRequest->setCityId($selectedCityId);
				}
			}	
		} else {
			$rankingPageRequest->setCityId(0);
			$rankingPageRequest->setCityName("");
		}
		
		$stateFilters = $filters['state'];
		$stateSelectedFilter = $this->getSelectedFilterFromFiltersList($stateFilters);
		if(!empty($stateSelectedFilter)){
			$selectedStateName 	= $stateSelectedFilter->getName();
			$selectedStateId 	= $stateSelectedFilter->getId();
			if($selectedStateId != $requestStateId){
				if(strtolower($selectedStateName) == 'all'){
					$rankingPageRequest->setStateId(0);
					$rankingPageRequest->setStateName("");
				} else {
					$stateObject = $this->locationRepo->findState($selectedStateId);
					$rankingPageRequest->setStateName($stateObject->getName());
					$rankingPageRequest->setStateId($selectedStateId);
				}
			}	
		} else {
			$rankingPageRequest->setStateId(0);
			$rankingPageRequest->setStateName("");
		}
		
		if(!empty($requestCountryId)){
			if($requestCountryId != 2){
				$rankingPageRequest->setCountryId(2);
				$rankingPageRequest->setCountryName('India');
			}
		}
		
		$tempRequestCityId 		= $rankingPageRequest->getCityId();
		$tempRequestStateId 	= $rankingPageRequest->getStateId();
		$tempRequestCountryId 	= $rankingPageRequest->getCountryId();
		
		if(empty($tempRequestCityId) && empty($tempRequestStateId) && empty($tempRequestCountryId)){ //Fail-Safe
			$rankingPageRequest->setCountryId(2);
			$rankingPageRequest->setCountryName('India');
		}
		
		$examFilters = $filters['exam'];
		$examSelectedFilter = $this->getSelectedFilterFromFiltersList($examFilters);
		if(!empty($examSelectedFilter)){
			$selectedExamName 	= $examSelectedFilter->getName();
			$selectedExamId 	= $examSelectedFilter->getId();
			if($selectedExamId != $requestExamId){
				if(strtolower($selectedExamName) == 'all'){
					$rankingPageRequest->setExamId(0);
					$rankingPageRequest->setExamName("");
				} else {
					$examDetails = $this->rankingModel->getExamById($selectedExamId);
					if(!empty($examDetails)){
						$rankingPageRequest->setExamName($examDetails['name']);
						$rankingPageRequest->setExamId($selectedExamId);
					} else {
						$rankingPageRequest->setExamId(0);
						$rankingPageRequest->setExamName("");
					}
				}
			}	
		} else {
			$rankingPageRequest->setExamId(0);
			$rankingPageRequest->setExamName("");
		}
	}
	
	private function getSelectedFilterFromFiltersList($filters = array()){
		$selectedFilter = NULL;
		if(!empty($filters)){
			foreach($filters as $filter){
				$selected = $filter->isSelected();
				if($selected == true){
					$selectedFilter = $filter;
					break;
				}
			}	
		}
		return $selectedFilter;
	}

	public function validateURL(RankingPageRequest $rankingPageRequest){
		if($rankingPageRequest==false){
			show_404();
		}
		if(in_array($rankingPageRequest->getStateId(),array(134))) { //301 FOR  chandigarh AS state to chandigarh tricity  
           $pageIdentifier = $rankingPageRequest->getPageId()."-".$rankingPageRequest->getCountryId()."-0-12292-".$rankingPageRequest->getExamId();
	       $request = $this->getRankingPageRequest($pageIdentifier);
	       $url = $this->buildURL($request);
	       redirect($url,'location', 301);
	    }
		$seourl = $this->buildURL($rankingPageRequest);
		// _p($seourl);
		// $currentUrl = $this->CI->input->server('SCRIPT_URI',true);
		$currentUrl = getCurrentPageURLWithoutQueryParams();
		// _p($currentUrl);_p(getCurrentPageURL());die;
	 	// split current url 
	 	$splitCurrentUrl   = explode('?',$currentUrl);
	 	
	 	// if url contains query paramater then append query paramater to correct url
	    if(isset($splitCurrentUrl[1]) && !empty($splitCurrentUrl[1])) {
	    	$redirectUrl = $seourl."?".$splitCurrentUrl[1];
	    } else {
	    	$redirectUrl = $seourl;	
	    }

	    if(!$rankingPageRequest->isRankingPageLive()) {	//We will have category page URL in the seoUrl field
	    	header("Location: ".SHIKSHA_HOME.'/'.$redirectUrl,TRUE,301);
			exit;
		}

		if(strcmp($currentUrl, SHIKSHA_HOME.'/'.$redirectUrl) !== 0) {
			header("Location: ".SHIKSHA_HOME.'/'.$redirectUrl,TRUE,301);
			exit;
		}
	}
	
	/**
	 * @method: This function builds URL based on the ranking page request
	 * @param $returnType : It takes two values "url" and "urltitle". default is url
	 * @return:
	 * if returnType is url it will only return url
	 * if returnType is urltitle, it will return SEO friendly URL + SEO friendly Title
	*/
	public function buildURL(RankingPageRequest $rankingPageRequest, $returnType = "url",$basecourses=''){
		$url 		 = "";
		$seoUrlTitle = "";
		if(empty($rankingPageRequest)){
			if($returnType == "urltitle"){
				return array('url' => $url, 'title' => $seoUrlTitle);
			} else {
				return $url;
			}
		}
		
		$rankingPageId 		= (integer)$rankingPageRequest->getPageId();
		$rankingPageName 	= $rankingPageRequest->getPageName();
		$cityId				= (integer)$rankingPageRequest->getCityId();
		$cityName			= $rankingPageRequest->getCityName();
		$stateId			= (integer)$rankingPageRequest->getStateId();
		$stateName			= $rankingPageRequest->getStateName();
		$countryId			= (integer)$rankingPageRequest->getCountryId();
		if(empty($countryId)) $countryId = 2;
		$countryName		= $rankingPageRequest->getCountryName();
		if(empty($countryName)) $countryName = 'india';
		$examId				= (integer)$rankingPageRequest->getExamId();
		$examName			= $rankingPageRequest->getExamName();
		
		if(!$rankingPageRequest->isRankingPageLive()) {
			return $this->getCategoryPageUrl($rankingPageRequest);
		}

		$url = '';
		$stream = $rankingPageRequest->getStreamId();
		$baseCourseId = $rankingPageRequest->getBaseCourseId();
		$ci = &get_instance();
		$ci->load->builder('ListingBaseBuilder','listingBase');
		$builder = new ListingBaseBuilder;
		$repo = $builder->getBaseCourseRepository();
        if($basecourses!=''){
			$data = &$basecourses;
        }
	    else{
	        $data = $repo->getAllBaseCourses('object');
	    }
		
		//case to check for base courses if popular
		if(!empty($baseCourseId) && (integer)$baseCourseId > 0 && $data[$baseCourseId]->getIsPopular() == 1){
			$returnArray = array();
			foreach($data as $key=>$singleObject) {
				$arr = $singleObject->getObjectAsArray();
				$returnArray[$arr['base_course_id']] = $arr;
			}
			$baseCourses  = array('data'=>$returnArray);
			$baseCourseName = $baseCourses['data'][$baseCourseId]['alias'];
			if(empty($baseCourseName)){
				$baseCourseName = $baseCourses['data'][$baseCourseId]['name'];
			}
			$url.= seo_url($baseCourseName).'/';
		}else{
			$repo = $builder->getHierarchyRepository();
			$substream = $rankingPageRequest->getSubstreamId();
			if(empty($substream)){
				$streamData = $repo->getSubstreamSpecializationByStreamId($stream);
				$substream = array_keys($streamData[$stream]['substreams']);
			}
			$data = $repo->getSubstreamSpecializationByStreamId($stream,1);
			$hierarchy = array('data'=>$data);
			$streamName = $hierarchy['data'][$stream]['url_name'];
			$url.= seo_url($streamName).'/';
			$substream = $rankingPageRequest->getSubstreamId();
			if(!empty($substream) && (integer)$substream > 0){
				$substreamName = $hierarchy['data'][$stream]['substreams'][$substream]['url_name'];
				$url .= seo_url($substreamName).'/';
			}
		}
		unset($ci);
		if(!empty($cityName)){
			$location = $cityName;
		}else if(!empty($stateName)){
			$location = $stateName;
		}else{
			$location = $countryName;
		}
		$url.= 'ranking/top-'.$this->_clean($rankingPageName).'-colleges-in-'.$location;
		if(!empty($examName)){
			$url.= '-accepting-'.$examName.'/';
			$seoUrlTitle = 'Top '.$this->_clean($rankingPageName).' colleges in '.$location.' accepting '.$examName;
		}else{
			$url.='/';
			$seoUrlTitle = 'Top '.$this->_clean($rankingPageName).' colleges in '.$location.' | Rank, Fees, Cut-offs, Placements';
		}
		$url.= $rankingPageId.'-'.$countryId.'-'.$stateId.'-'.$cityId.'-'.$examId;		
		$url = preg_replace("/[\s]/", "-", $url);
		$urlRemove = array('(',')');
		foreach($urlRemove as $char){
			$url = str_replace($char, '', $url);
		}
		$url = strtolower($url);
		if($returnType == "urltitle"){
			return array('url' => $url, 'title' => $seoUrlTitle);
		} else {
			return $url;
		}

	}

	private function _clean($string) {
		$string = preg_replace('/[^A-Za-z0-9\- ]/', '', $string); // Removes special chars.
		$string = preg_replace("/[\s]+/", " ", $string); // Replace multiple spaces with single space
   		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   		return $string;
	}

	private function getCategoryPageUrl($rankingPageRequest) {
		$lib = $this->CI->load->library('nationalCategoryList/NationalCategoryPageLib');
		$params = array();
		$params['stream_id'] = $rankingPageRequest->getStreamId();
		$params['substream_id'] = $rankingPageRequest->getSubstreamId();
		$params['specialization_id'] = $rankingPageRequest->getSpecializationId();
		$params['base_course_id'] = $rankingPageRequest->getBaseCourseId();
		$params['education_type'] = $rankingPageRequest->getEducationType();
		$params['delivery_method'] = $rankingPageRequest->getDeliveryMethod();
		$params['credential'] = $rankingPageRequest->getCredential();
		$params['exam_id'] = $rankingPageRequest->getExamId();
		$params['state_id'] = $rankingPageRequest->getStateId();
		$params['city_id'] = $rankingPageRequest->getCityId();
		$url = $lib->getUrlByParams($params);
		if(empty($url)){
			return '';
		}
		return $url;
	}
	
	public function getRankingPageMetaData($rankingPageObject = NULL,$rankingPageRequest = NULL){
		$this->CI->load->library("rankingV2/RankingCommonLib");
		$metaDetails = array("title" => "", "description" => "",'breadcrumb' =>"",'h1' =>'');
		if(empty($rankingPageObject)){
			return $metaDetails;
		}
		$rankingPageId = $rankingPageObject->getId();
		$cityId = $rankingPageRequest->getCityId();
		$stateId = $rankingPageRequest->getStateId();
		$streamId = $rankingPageRequest->getStreamId();
		$substreamId = $rankingPageRequest->getSubstreamId();
		$specializationId = $rankingPageRequest->getSpecializationId();
		$baseCourseId = $rankingPageRequest->getBaseCourseId();
		$metaDetailsFromDB = $this->CI->rankingcommonlib->getRankingPageMetaData($rankingPageId,$cityId,$stateId);
		$metaDetails['title'] = $metaDetailsFromDB['ranking_page_title'];
		$metaDetails['description'] = $metaDetailsFromDB['ranking_page_description']; 
		$metaDetails['h1'] = $metaDetailsFromDB['h1'];
		$metaDetails['breadcrumb'] = $metaDetailsFromDB['breadcrumb'];

		if(empty($metaDetailsFromDB['h1'])){
			$metaDetails['h1'] = 'Top <Nickname> Colleges in <span><Location></span>';

		}
		if($examId = $rankingPageRequest->getExamId()){
			$metaDetails['title'] = $metaDetailsFromDB['ranking_page_title_exam'];
			$metaDetails['description'] = $metaDetailsFromDB['ranking_page_description_exam'];
			$examName = '';
			$examDetails = $this->rankingModel->getExamById($examId);
			if(!empty($examDetails)){
				$metaDetails['h1'] .= ' Accepting <Examname>';
				$examName 	 = $examDetails['name'];
			}
		}
		$location = '';
		if($rankingPageRequest->getCityName()){
			$location = $rankingPageRequest->getCityName();
		}else if($rankingPageRequest->getStateName()){
			$location = $rankingPageRequest->getStateName();
		}else{
			$location = $rankingPageRequest->getCountryName();
		}

		$this->CI->load->builder("listingBase/ListingBaseBuilder");
		$listingBaseBuilder = new ListingBaseBuilder();
		$baseCourseRepo = $listingBaseBuilder->getBaseCourseRepository();
		$streamRepo     = $listingBaseBuilder->getStreamRepository();	
		$subStreamRepo  = $listingBaseBuilder->getSubstreamRepository();	
		$specializationRepo = $listingBaseBuilder->getSpecializationRepository();

		if($streamId!=0){
			$streamObj = $streamRepo->find($streamId);
			$streamName = $streamObj->getName();
		}
		if($baseCourseId!=0){
			$baseCourseObj = $baseCourseRepo->find($baseCourseId);
			$baseCourseName = $baseCourseObj->getName();
		}
		if($substreamId!=0){
			$subStreamObj = $subStreamRepo->find($substreamId);
			$subStreamName = $subStreamObj->getName();
		}
		if($specializationId!=0){
			$specializationObj = $specializationRepo->find($specializationId);
			$specializationName = $specializationObj->getName();
		}

		$search = array('<Nickname>', '<Stream>', '<Substream>', '<Specialization>', '<Bcourse>','<Location>','<Examname>');
        $replace = array($rankingPageRequest->getPageName(),$streamName,$subStreamName,$specializationName, $baseCourseName, $location,$examName);

        $metaDetails['title'] = str_replace($search,$replace,$metaDetails['title']);
        $metaDetails['description'] = str_replace($search,$replace,$metaDetails['description']);
        $metaDetails['h1'] = str_replace($search,$replace,$metaDetails['h1']);
        $metaDetails['breadcrumb'] =  str_replace($search,$replace,$metaDetails['breadcrumb']);
     
		if(empty($metaDetailsFromDB['breadcrumb'])){
			$metaDetails['breadcrumb'] = $this->_prepareBreadcrumbsForPage($rankingPageRequest,$location,$examName);
		} 
		return $metaDetails;
	}

	private function _prepareBreadcrumbsForPage($rankingPageRequest,$location,$examName){
		$breadcrumb = array();
		$breadcrumb[] = array('title'=>'Home','url'=>SHIKSHA_HOME);
		$lib = $this->CI->load->library('coursepages/CourseHomePageUrlGenerator');
		$popularCourseId = $rankingPageRequest->getBaseCourseId();
		$stream = $rankingPageRequest->getStreamId();
		if($popularCourseId){
			$ci = &get_instance();
			$ci->load->builder('ListingBaseBuilder','listingBase');
			$builder = new ListingBaseBuilder;
			$repo = $builder->getBaseCourseRepository();
			$data = $repo->getAllBaseCourses('object');
			$returnArray = array();
			foreach($data as $key=>$singleObject) {
				$arr = $singleObject->getObjectAsArray();
				$returnArray[$arr['base_course_id']] = $arr;
			}
			$baseCourses  = array('data'=>$returnArray);
			$courseName = $baseCourses['data'][$popularCourseId]['alias'];
			if(empty($courseName)){
				$courseName = $baseCourses['data'][$popularCourseId]['name'];
			}
			$params = array(
				'base_course_id'=>$popularCourseId,
				'stream_id' => $stream,
				'substream_id' => $rankingPageRequest->getSubstreamId(),
				'education_type' => $rankingPageRequest->getEducationType(),
				'delivery_method' => $rankingPageRequest->getDeliveryMethod()
			);
			$url = $lib->getUrlByParams($params);
			$breadcrumb[] = array('title'=>$courseName,'url'=>$url);
		}else{
			$ci = &get_instance();
			$ci->load->builder('ListingBaseBuilder','listingBase');
			$builder = new ListingBaseBuilder;
			$repo = $builder->getHierarchyRepository();
			$substream = $rankingPageRequest->getSubstreamId();
			if(empty($substream)){
				$streamData = $repo->getSubstreamSpecializationByStreamId($stream);
				$substream = array_keys($streamData[$stream]['substreams']);
			}
			$data = $repo->getSpecializationTreeByStreamSubstreamId($stream,$substream,1);
			$hierarchy = array('data'=>$data);
			$streamName = $hierarchy['data'][$stream]['name'];
			$params = array(
				'base_course_id'=>$popularCourseId,
				'stream_id' => $stream,
				'substream_id' => $rankingPageRequest->getSubstreamId(),
				'education_type' => $rankingPageRequest->getEducationType(),
				'delivery_method' => $rankingPageRequest->getDeliveryMethod()
			);
			$url = $lib->getUrlByParams($params);
			$breadcrumb[] = array('title'=>$streamName,'url'=>$url);
			if($substream = $rankingPageRequest->getSubstreamId()){
				$substreamName = $hierarchy['data'][$stream]['substreams'][$substream]['name'];
				$params['substream_id'] = $substream;
				$url = $lib->getUrlByParams($params);
				$breadcrumb[] = array('title'=>$substreamName,'url'=>$url);
			}
		}

		$breadcrumb[] = array('title' => 'Rankings','url'=>'');

		if($examName){
			$breadcrumb[] = array('title' => 'Top '.$rankingPageRequest->getPageName().' colleges in '.$location.' accepting '.$examName,'url'=>'');	
		}else{
			$breadcrumb[] = array('title' => 'Top '.$rankingPageRequest->getPageName().' colleges in '.$location,'url'=>'');	
		}
		return $breadcrumb;
	}
	
	public function getCurrentPageURL(RankingPageRequest $rankingPageRequest = NULL){
		if(empty($rankingPageRequest)){
			return '';
		}
		$url = $this->buildURL($rankingPageRequest);
		if(!$rankingPageRequest->isRankingPageLive()) {
			return $url;
		}
		return SHIKSHA_HOME . "/". ltrim($url, "/");
	}
	
	public function getRankingPageURLByRankingPageId($rankingPageId = NULL, $rankingPageName = NULL){
		if(empty($rankingPageId) || empty($rankingPageName)){
			return "";
		}
		$param = array();
		$param['rankingPageId'] 	= $rankingPageId;
		$param['rankingPageName'] 	= $rankingPageName;
		$rankingPageRequest = $this->getRankingPageRequestFromDataArray($param);
		$url = $this->buildURL($rankingPageRequest);
		if(!$rankingPageRequest->isRankingPageLive()) {
			return $url;
		}
		return SHIKSHA_HOME . "/". ltrim($url, "/");
	}
	
	private function parseLocationAndExamFromMetaDetails($metaDetailsFromDB = NULL, $rankingPageRequestObject = NULL, $stream) {
		if(empty($rankingPageRequestObject) || empty($rankingPageRequestObject)){
			return false;
		}
		
		$cityName			= $rankingPageRequestObject->getCityName();
		$stateName 			= $rankingPageRequestObject->getStateName();
		$countryName 		= $rankingPageRequestObject->getCountryName();
		$examName 			= $rankingPageRequestObject->getExamName();
		
		$locationName = "";
		if(!empty($cityName)){
			$locationName = $cityName;
		} else if(!empty($stateName)){
			$locationName 	= $stateName;
		} else if(!empty($countryName)){
			$locationName 	= $countryName;
		}
		
		$updatedMetaDetails = false;
		if(!empty($examName)){
			$title 		 = $metaDetailsFromDB['title_exam'];
			$description = $metaDetailsFromDB['description_exam'];
		} else {
			$title 		 = $metaDetailsFromDB['title'];
			$description = $metaDetailsFromDB['description'];
		}
		if(!empty($title) && !empty($description)){
			$title = str_ireplace("<location>", $locationName, $title);
			$title = str_ireplace("<examname>", $examName, $title);
			
			$description = str_ireplace("<location>", $locationName, $description);
			$description = str_ireplace("<examname>", $examName, $description);
			$description = str_ireplace("<stream>", $stream, $description);

			$updatedMetaDetails['title'] = $title;
			$updatedMetaDetails['description'] = $description;
		}
		return $updatedMetaDetails;
	}

	function checkForSpecializationPage($rankingPageRequest) {
		$currentPageId = $rankingPageRequest->getPageId();
		$flag = false;
		foreach($this->rankingPageSpecializationIdMapping as $rankingPageId => $specializationRankingPageIds) {
			if(in_array($currentPageId, $specializationRankingPageIds)) {
				$rankingPageRequest->setPageId(2);
				$rankingPageRequest->setPageName($this->rankingPgeIdToNameMapping[$rankingPageId]);
				break;
			}
		}
		return $rankingPageRequest;
	}
	
	public function getStreamWiseRankingPageUrl() {
		//get from cache
		$this->CI = &get_instance();
		$rankingPageCache = $this->CI->load->library(RANKING_PAGE_MODULE."/cache/RankingPageCache");
		$rankingUrl = $rankingPageCache->getStreamWiseRankingPageUrl();
		
		if(empty($rankingUrl)) {
			$params['status'] = 'live';
			$params['specialization_id'] = 0;
			$params['orderBy'] = 'ranking_page_text';
			$rankingPages = $this->rankingModel->getRankingPages($params);
			
			foreach ($rankingPages as $key => $rankingPage) {
				if($rankingPage['stream_id'] > 0){
					$params = array();
					$params['rankingPageId'] 	= $rankingPage['id'];
					$params['rankingPageName']  = $rankingPage['ranking_page_text'];
					$this->CI->load->builder('rankingV2/RankingPageBuilder');
        			$builder = new RankingPageBuilder;
        			$lib = $builder->getURLManager();
                 	$url = $lib->getRankingPageURLById($rankingPage['id']);
					if(!empty($url)){
						$rankingUrl[$rankingPage['stream_id']][$key]['title'] = $rankingPage['ranking_page_text'];
						$rankingUrl[$rankingPage['stream_id']][$key]['url'] = $url;	
					}
					
				}
					
			}
			ksort($rankingUrl);
     		foreach ($rankingUrl[2] as $key => $value) {  //To add 'All Engineering Ranking' links at the starting of College Rankings Engineering in Hamburger
     			if($value['title'] == 'Engineering'){
     				$value['title'] = 'All Engineering';
					unset($rankingUrl[2][$key]);
					array_unshift($rankingUrl[2], $value);
				}
     		}
     		$rankingPageCache->storeStreamWiseRankingPageUrl($rankingUrl);
		}
		
		return $rankingUrl;
	}

	public function getAllRankingPageUrls(){
		$urls = array();
		$ids = $this->rankingModel->getAllLiveRankingPageIds();
		foreach($ids as $id){
			$req = $this->getRankingPageRequest($id.'-2-0-0-0');
			$url = $this->buildURL($req);
			if($req->isRankingPageLive()){
				$urls[] = SHIKSHA_HOME . "/". ltrim($url, "/");
			}
		}
		return $urls;
	}

	public function getRankingPageURLById($pageId){
		if((integer)$pageId < 0){
			return '';
		}
		$pageBaseUrl = $this->_getRankingPageUrlFromCache($pageId);
		if(!empty($pageBaseUrl)){
			return $pageBaseUrl;
		}
		$urlString = $pageId."-2-0-0-0";
		$req = $this->getRankingPageRequest($urlString);
		$url = $this->buildURL($req);
		$url = SHIKSHA_HOME . "/". ltrim($url, "/");
		$this->_storeRankingPageUrlToCache($url,$pageId);
		return $url;
	}

	private function _getRankingPageUrlFromCache($pageId){
		$url = $this->rankingCache->getRankingPageUrl($pageId);
		return $url;
	}

	private function _storeRankingPageUrlToCache($url,$pageId){
		$this->rankingCache->storeRankingPageUrl($url,$pageId);
	}

	public function getRankingUrlsByMultipleParams($paramsArr){
		// _p($paramsArr);die;
		$returnData = array();
		$finalParams = array();

    	if(empty($paramsArr)) {
	    	return;
	    }
	    // _p($paramsArr);die;
	    $cityToVirtualCityMapping = array();
	    $validKeys = array('stream_id' => 'stream_id','substream_id' => 'substream_id','specialization_id' => 'specialization_id','base_course_id' => 'base_course_id','education_type' => 'education_type','delivery_method' => 'delivery_method','credential' => 'credential','city_id' => 'city_id','state_id' => 'state_id');

	    foreach ($paramsArr as $key => $params) {
	    	$temp = array();
			foreach ($validKeys as $value) {
				$temp[$value] = empty($params[$value]) ? 0 : $params[$value];
			}

			// if(!empty($params['original_city_id'])){
			// 	$mapping = array('city_id' => $temp['city_id']);
			// 	$mapping['original_state_id'] = empty($params['original_state_id']) ? $params['state_id'] : $params['original_state_id'];
			// 	$cityToVirtualCityMapping[$params['original_city_id']] = $mapping;
			// 	$temp['city_id'] = $params['original_city_id'];
			// }
			// if(!empty($params['original_state_id'])){
			// 	$temp['state_id'] = $params['original_state_id'];
			// }
			
			if(!empty($temp['stream_id']) || !empty($temp['base_course_id'])) {
	    		if(!empty($temp['city_id'])){
	    			$finalParams[] = $temp;
	    		}
	    		if(!empty($temp['state_id'])){
	    			$temp['city_id'] = 0;
	    			$finalParams[] = $temp;
	    		}
	    	}
	    }
	    // _p($finalParams);die;
	    // _p($cityToVirtualCityMapping);die;
	    $data = $this->rankingModel->getRankingPagesByMultipleFilterParams($finalParams);
	    // _p($data);die('aaaa');
	    foreach ($data as $key => $value) {
	    	if($value['city_id'] > 0){
	    		$value['type'] = 'city';
	    		$params = array('cityId' => $value['city_id'] ,'rankingPageId' => $value['ranking_page_id']);
	    	}
	    	else{
	    		$value['type'] = 'state';
	    		$params = array('stateId' => $value['state_id'] ,'rankingPageId' => $value['ranking_page_id']);
	    	}
	    	$value['url'] = addingDomainNameToUrl(array('url' => $this->buildUrlUsingParams($params), 'domainName' => SHIKSHA_HOME));
	    	$value['title'] = $this->getRankingPageNameByParams($params);
	    	// $value['type'] = 'city';
	    	// if(empty($cityToVirtualCityMapping[$value['city_id']])){
	    	// 	$cityId = $value['city_id'];
	    	// }
	    	// else{
	    	// 	$cityId = $cityToVirtualCityMapping[$value['city_id']]['city_id'];
	    	// 	$value['state_id'] = $cityToVirtualCityMapping[$value['city_id']]['original_state_id'];
	    	// }
	    	// $value['city_id'] = $cityId;
	    	$dataKey = $this->_generateKeyBasedOnParams($value);
	    	
	    	
	    	$returnData[$dataKey] = $value;

	    	// if($value['state_id'] > 0){
	    	// 	$value['type'] = 'state';
	    	// 	$value['city_id'] = 1;
	    	// 	$dataKey = $this->_generateKeyBasedOnParams($value);
	    		
	    	// 	$value['title'] = $this->getRankingPageNameByParams(array('stateId' => $value['state_id'], 'rankingPageId' => $value['ranking_page_id']));
	    	// 	$returnData[$dataKey] = $value;
	    	// }
	    }

	    // _p($returnData);die('aaa');

    	return $returnData;
	}

	public function _generateKeyBasedOnParams($data){
		if(empty($data)){
			return "";
		}
		/*if(!empty($data['base_course_id'])){
			$popularCourses = $this->baseCourseRepo->getAllPopularCourses('object');
			if(!empty($popularCourses[$data['base_course_id']])){
				$data['stream_id'] = 0;
			}
		}*/
		$keyData = array();
		$temp = array('stream_id','substream_id','specialization_id','base_course_id','education_type','delivery_method','city_id','state_id');
		// $temp[] = empty($data['original_state_id']) ? 'state_id' : 'original_state_id';
		foreach ($temp as $value) {
			$keyData[] = empty($data[$value]) ? 0 : $data[$value];
		}
		$key = implode("_", $keyData);
		return $key;
	}

	public function getRankingPageNameByParams($urlParams){
		$rankingPageRequest = $this->getRankingPageRequestFromDataArray($urlParams);
		if($rankingPageRequest->getCityName()){
			$location = $rankingPageRequest->getCityName();
		}else if($rankingPageRequest->getStateName()){
			$location = $rankingPageRequest->getStateName();
		}else{
			$location = $rankingPageRequest->getCountryName();
		}
		$h1 = 'Top '.$rankingPageRequest->getPageName().' Colleges in '.$location;
		return $h1;
	}

	function buildUrlUsingParams($urlParams) {
		$RankingPageRequest = $this->getRankingPageRequestFromDataArray($urlParams);
		return $this->buildURL($RankingPageRequest);
	}

	/**
	 * [getRankingPageByFilters description]
	 * @author Ankit Garg <g.ankit@shiksha.com>
	 * @date   2017-08-21
	 * @param  [type]     $filters [could be any of these: stream_id, substream_id, specialization_id, base_course_id, credential]
	 * @param  [type]     $limit [number of ranking page urls in return]
	 * @return [type]              [will return ranking_page_id]
	 */
	function getRankingPageUrlByFilters($filters, $limit) {
		if(!is_array($filters['streamId']) && (!empty($filters['streamId']) || ($filters['streamId'][0] == 0 && count($filters['streamId']) == 1)) ) {
			$filters['streamId'] = array($filters['streamId']);
		}
		if(!is_array($filters['substreamId']) && (!empty($filters['substreamId']) || ($filters['substreamId'][0] == 0 && count($filters['substreamId']) == 1)) ) {
			$filters['substreamId'] = array($filters['substreamId']);
		}
		if(!is_array($filters['specializationId']) && (!empty($filters['specializationId']) || ($filters['specializationId'][0] == 0 && count($filters['specializationId']) == 1)) ) {
			$filters['specializationId'] = array($filters['specializationId']);
		}
		if(!is_array($filters['baseCourseId']) && (!empty($filters['baseCourseId']) || ($filters['baseCourseId'][0] == 0 && count($filters['baseCourseId']) == 1)) ) {
			$filters['baseCourseId'] = array($filters['baseCourseId']);
		}
		if(!is_array($filters['credential']) && (!empty($filters['credential']) || ($filters['credential'][0] == 0 && count($filters['credential']) == 1)) ) {
			$filters['credential'] = array($filters['credential']);
		}
		if(!is_array($filters['delivery_method']) && (!empty($filters['delivery_method']) || ($filters['delivery_method'][0] == 0 && count($filters['delivery_method']) == 1)) ) {
			$filters['delivery_method'] = array($filters['delivery_method']);
		}
		
		$rankingPageData = $this->rankingModel->getRankingPageByFilters($filters, $limit);
		// _p($rankingPageIds); die('aaa1');
		foreach ($rankingPageData as $rankingPage) {
			$urlParams = array('rankingPageId' => $rankingPage['id']);
			$rankingPageUrl[] = SHIKSHA_HOME."/".$this->buildUrlUsingParams($urlParams);
		}
		return $rankingPageUrl;
	}
}
