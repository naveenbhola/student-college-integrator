<?php
class ArticleMobileController extends ShikshaMobileWebSite_Controller {

	private function init($library=array('ajax'),$helper=array('image','shikshautility','utility_helper')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		$this->load->builder('ArticleBuilder','article');
		$this->articleBuilder = new ArticleBuilder;
		$this->articleRepository = $this->articleBuilder->getArticleRepository();
		$this->validateuser = $this->logged_in_user_array; //empty array in logged out case
	}

	private function _setDependencies(){
		$this->load->builder('ArticleBuilder','article');
		$this->articleBuilder = new ArticleBuilder;
		$this->articleRepository = $this->articleBuilder->getArticleRepository();

		$this->validateuser = $this->logged_in_user_array; //empty array in logged out case

		//$this->load->library(array('ajax', 'blog_client', 'register_client'));
		$this->load->helper(array('article/article'));

		//$this->load->model('blogs/articlemodel', $this->articlemodel);
	}

	function getArticleDetailPage($blogId){
		$displayData = array();
		//URL parsing
		$blogId = $this->security->xss_clean($blogId);
		$this->parseCurrentURL($displayData, $blogId);
		if(empty($displayData['blogId'])){
			$blogId = explode('-',$blogId);
			$displayData['pageNum'] = $blogId[1];
			$displayData['blogId'] = $blogId[0];
			if(!$displayData['pageNum'] || $displayData['pageNum'] == ''){
				$displayData['pageNum'] = 0;
			}
		}
		//redirection rules
		$this->_redirectAnd404Rules($displayData);

		//initialize
		$this->_setDependencies($displayData);
		$this->load->helper('mcommon5/mobile_html5');
		//load blog object
		$blogObj = $this->articleRepository->find($displayData['blogId']);
		$displayData['blogObj'] = $blogObj;
		
		//$this->_otherRedirects($displayData);
		$this->load->library('ArticleUtilityLib');
		$blogId   = $blogObj->getId();
		$blogType = $blogObj->getType();
		$blogUrl  = $blogObj->getUrl();

		$displayData['country_id'] = 2;
		$displayData['validateuser'] = $this->validateuser;
		$displayData['userId'] = $userId = isset($this->validateuser['userid']) ? $this->validateuser['userid'] : '';
		$displayData['isNews'] = (isset($blogType) && $blogType == 'news') ? 1 : 0;

		//Related blogs from Solr with Catergory Subcategory logic, will be picked up later on
		$relatedBlogs = $this->articleutilitylib->showRelatedArticles($blogId);
	 	$cityStateClgData = $this->articleutilitylib->getCityAndStateMappedToArticle(array($blogId));

		$displayData['relatedBlogs'] = $relatedBlogs;
		$blogPagesIndexData = $blogObj->getDescription(); 
		$blogPagesIndex = array();
		if($blogObj->getBlogLayout() == 'general'){
			foreach ($blogPagesIndexData as $value) {
				$blogPagesIndex[$value->getDescriptionId()] = $value->getDescriptionTag();
			}
		}
		$displayData['blogPagesIndex'] = $blogPagesIndex;

		$totalPaginationCount = 1;
	 	if($blogObj->getBlogLayout() == 'general'){
	 		$totalPaginationCount = count($displayData['blogPagesIndex']);
	 	}

	 	$this->setCanonicalData($displayData, $blogUrl, $blogId, $totalPaginationCount);


	 	$blogUserId = $blogObj->getCreatorId();
	 	$blogUserData = $this->articlemodel->getUserDataOfBlogUserId($blogUserId);
	 	$displayData['blogUserData'] = $blogUserData;
	 	$username     = $blogUserData["displayname"];
	 	$displayname  = $blogUserData["displayname"];
	 	$viewedUserId = $blogUserData['userId'];

	 	$this->getAuthorInfo($displayData, $blogUserData);

		//load library to store information in beacon varaible for tracking purpose
		$blogMappingData = $displayData['blogObj']->getBlogMapping();
		$blogMappingDataForBeacon = array();
		foreach ($blogMappingData as $key => $value) {
			$blogMappingDataForBeacon[] = array(
					'entityType' => $value->getEntityType(),
					'entityId' =>	$value->getEntityId()
				);
		}
		$ExamPageLib = $this->load->library('examPages/ExamPageLib');
		$beaconTrackData = $ExamPageLib->getBeaconData($blogMappingDataForBeacon,$blogId,'articleDetailPage');
	 	$displayData['gtmParams'] = $this->articleutilitylib->getGTMArray($beaconTrackData, $cityStateClgData);
		$displayData['beaconTrackData'] = $beaconTrackData;
		$displayData['beaconTrackData']['extraData']['authorId'] = $viewedUserId;
		$displayData['beaconTrackData']['extraData']['viewedUserId'] = $displayData['userId'];
		
		//below line is used for conversion tracking purpose
		$this->setConversionTrackingParams($displayData);
		$displayData['streamCheck'] = $this->getOldCategoryToNewStreamMapping($displayData);

		//VITEEE result time	
		if($displayData['blogId'] == 13149){
			$resultDate = $this->checkResultDate();
			if($resultDate['expireFlag']){
				$showCounter = false;
			}else{
				$showCounter = true;
			}
			$displayData['showCounter'] = $showCounter;
			$displayData['jqueryUIRequired'] = true;
			$displayData['resultTime'] = $resultDate['resultTime'];
		}

		$displayData['ga_user_level'] = !empty($displayData['userId']) ? 'Logged In':'Non-Logged In';
		
		$displayData['removeJquery'] = true;
		$this->load->view('mJquery/articleDetails',$displayData);
	}

	private function parseCurrentURL(&$displayData , $blogId)
	{
		$articleType = '';
		$urlseg = $this->uri->segment(1);
		if($urlseg=="news"){
			$articleType="news";
			$urlseg = $this->uri->segment(2);
		}
		$displayData['oldArticleUrl'] = 0;
		$url_segments = explode("-", $urlseg);
		if ($url_segments[0] != 'getArticleDetail') {
			foreach($url_segments as $arr){
				if($arr == 'article'){
					$displayData['oldArticleUrl'] = 1;
					break;
				}
			}
			$blogId   = explode('-',$blogId);
			$pageNum  = (int)$blogId[1];
			$blogId   = (int)$blogId[0];
			
		}else{
			$pageNum = $this->input->get('page');			
		}
				
		if(!$pageNum || $pageNum == ''){
			$pageNum = 1;
		}
		$pageNum--;
		$displayData['pageNum']     = $pageNum;
		$displayData['blogId']      = $blogId;
		$displayData['articleType'] = $articleType;
		$displayData['url_segments']= $url_segments;
		//In case, this is the first page of the article, then add Canonical URL in the Head tag in case of OLD URLs only	
		if($pageNum<=0 && $this->input->server('QUERY_STRING')!=''){
			if($this->uri->segment(1) == 'getArticleDetail'){
				$displayData['canonicalURL'] = SHIKSHA_HOME.substr($this->input->server('REQUEST_URI'), 0, strpos($this->input->server('REQUEST_URI'), '?'));
			}
		}
	}

	private function _redirectAnd404Rules(&$displayData){
		$this->load->library('article/ArticleUtilityLib');
		$this->articleutilitylib->blogRedirectRules($displayData);
		$this->articleutilitylib->blogShow404Rules($displayData);
	}

	private function _otherRedirects(&$displayData){
		//if object is blank, send to all article page
		if(empty($displayData['blogObj']) || !is_object($displayData['blogObj'])){
			$url = SHIKSHA_HOME_URL.'/articles-all';
            redirect($url, 'location', 301);
            exit;
		}
		if($displayData['oldArticleUrl'] == 1){
			$url = SHIKSHA_HOME.'/'.seo_url_lowercase($displayData['blogObj']->getTitle()).'-blogId-'.$displayData['blogObj']->getId().'-1';
			redirect($url, 'location', 301);
            exit;
		}
		//if page num is greater than last page, then send to first page
		$count = count($displayData['blogObj']->getDescription());
		if($displayData['pageNum'] >= $count){
			$url = SHIKSHA_HOME.'/'.seo_url_lowercase($displayData['blogObj']->getTitle()).'-blogId-'.$displayData['blogObj']->getId().'-1';
			redirect($url, 'location', 301);
            exit;
		}
	}

	private function setCanonicalData(&$displayData, $blogUrl, $blogId, $totalPaginationCount){
		if($displayData['url_segments'][0] != 'getArticleDetail'){ //New url
			if($totalPaginationCount < $displayData['pageNum']){
				redirect($blogUrl, 'location', 301);
				exit;
			}
			/**Canonical Url**/
			$type = 'new';
			$result = createSEOMetaTagsForArticle($blogUrl, $blogId, $displayData['pageNum']+1, $totalPaginationCount, $type);
			//$displayData['canonicalURL'] = $result['canonicalURL'];
			$displayData['nexturl']      = $result['nexturl'];
			$displayData['previousurl']  = $result['previousurl'];
			$displayData['canonicalURL'] = $result['canonicalURL'];	
	 	}else{ //Old url
	 		$type = 'old';
			if($displayData['pageNum'] <= 0) {
				$url = $blogUrl.'?token=aa';
				redirect($url, 'location', 301);
				exit();
			}else{
				$pageCount = $displayData['pageNum'];
			}

			if($totalPaginationCount < $pageCount){
				$url = $blogUrl.'?token=aa';
				redirect($url, 'location', 301);
				exit();
			}
			$result = createSEOMetaTagsForArticle($blogUrl, '', $pageCount+1, $totalPaginationCount, $type);
			//$displayData['canonicalURL'] = $result['canonicalURL'];
			$displayData['previousurl']  = $result['previousurl'];
			$displayData['nexturl']      = $result['nexturl'];
			$displayData['canonicalURL'] = $result['canonicalURL'];
	 	}
	}

	private function getAuthorInfo(&$displayData, $blogUserData){
		$username     = $blogUserData["displayname"];
	 	$displayname  = $blogUserData["displayname"];
	 	$viewedUserId = $blogUserData['userId'];
		//check if the author is external else read config if the author is internal to make author link
		if($blogUserData['userId'] == "3156629"){
			$displayData['authoruserName'] = $displayname;
			$displayData['externalUser']='true';
		}
		else{
			$this->load->library('messageBoard/AnAConfig');
			$author_details_array = AnAConfig::$author_details_array;
			$authorData = $author_details_array[$blogUserData['userId']];
			if(!empty($authorData)){
				$displayData['authoruserName'] = $blogUserData["displayname"];
				$displayData['displayname'] = $blogUserData["displayname"];
				$displayData['authorUrl'] = '/author/'.$displayname;
			}
		}
	}

	private function setConversionTrackingParams(&$displayData){
		$displayData['trackingPageKeyId'] = 286;
		$displayData['trackForPages']     = true;
	}

	private function getOldCategoryToNewStreamMapping(&$displayData){
		// Management = 4, Engineering = 5
		// MBA = 9, B.Tech = 3
		// Full Time = 20
		$mapping = $displayData['blogObj']->getBlogMapping();
		$mappingArr = array();
		foreach ($mapping as $mapObj) {
			switch ($mapObj->getEntityType()) {
				case 'primaryHierarchy':
					$mappingArr['hierarchIds'][] = $mapObj->getEntityId();
					break;
				case 'course':
					$mappingArr['courseIds'][] = $mapObj->getEntityId();
					break;
				case 'otherAttribute':
					$mappingArr['otherAttr'][] = $mapObj->getEntityId();
			}
		}
		if(!empty($mappingArr['hierarchIds'])){
			$this->load->builder('listingBase/ListingBaseBuilder');
			$listingBase = new ListingBaseBuilder();
			$hierarchyRepo = $listingBase->getHierarchyRepository();
			$baseEntities = $hierarchyRepo->getBaseEntitiesByHierarchyId($mappingArr['hierarchIds'], 0, 'array');
			foreach ($baseEntities as $value) {
				$mappingArr['streamIds'][] = $value['stream_id'];
			}
			unset($mappingArr['hierarchIds']);
		}

		$result = 'other';
		if(in_array(MANAGEMENT_STREAM, $mappingArr['streamIds']) && in_array(MANAGEMENT_COURSE, $mappingArr['courseIds']) && in_array(EDUCATION_TYPE, $mappingArr['otherAttr'])){
			$result = 'fullTimeMba';
		}else if(in_array(ENGINEERING_STREAM, $mappingArr['streamIds']) && in_array(ENGINEERING_COURSE, $mappingArr['courseIds'])){
			$result = 'beBtech';
		}
		return $result;
	}

	function getArticles($entity,$param1,$param2,$param3,$pageSize = 10){
		$isAjax = 0;
		$displayData = array();
		$displayData['boomr_pageid'] = 'article_list_page';
		if(!empty($_COOKIE["articleType"])){
			$displayData['displayType'] = $this->input->cookie('articleType');
		}else{
			$displayData['displayType'] = 'latest';
		}
		if($this->input->is_ajax_request()){
			$isAjax = 1;
		}
		switch ($displayData['displayType']) {
			case 'popular':
				$this->_getArticleList($displayData['displayType'],$entity,$param1,$param2,$param3,$pageSize,$displayData,$isAjax);
				break;
			
			default:
				$this->_getArticleList('latest',$entity,$param1,$param2,$param3,$pageSize,$displayData,$isAjax);
				break;
		}
		if($isAjax == 1){
			echo json_encode(array('html'=>$this->load->view('articleListInner',$displayData, true)));
		}else{
			$this->load->view('articleList',$displayData);
		}
	}

	private function _getArticleList($type,$entity,$param1,$param2,$param3,$pageSize = 10,&$displayData,$isAjax = 0){
		$this->init();
		$lib = $this->load->library('article/ArticleUtilityLib');
		$articleModel = $this->load->model('article/articlenewmodel');
		if(empty($pageSize)){
			$pageSize = 10;
		}
		$beaconTrackData = array(
			'pageIdentifier' => 'allArticlePage',
			'pageEntityId'   => 0,
			'extraData'      => array(
				'countryId' => 2
			)
		);
		switch ($entity) {
			case 'stream':
				$streamName = $param1;
				$params = explode('-', $param2);
				$streamId = $params[0];
				$currentPage = $params[1];
				$entityIdStr = $param1."/".$param2;
				$entityIdForLayer = $params[0];
				$entityName = $streamName;
				if(!is_numeric($streamId) || empty($streamId) || $streamId < 1 || (!is_numeric($currentPage) && !empty($currentPage))){
            		show_404();
        		}
				$limit = $lib->getLimitForPagination($currentPage,$pageSize);
				$hierarchyIds = $lib->getHierarchyId($streamId);
				$displayData['articleList'] = $this->articleRepository->getArticleListBasedOnHierarchy($hierarchyIds,$limit,$type);
				if($isAjax == 0){
					$paginationURL = SHIKSHA_HOME."/".$streamName."/articles-st-".$streamId.'-@pageno@';
					$paramForSEO = array('stream_id'=>$streamId);
					$displayData['totalArticles'] = $lib->getTotalArticlesBasedOnHierarchy($hierarchyIds,'',$articleModel);
				}
				$beaconTrackData['extraData']['hierarchy'] = array(
					'streamId' => $streamId,
					'substreamId' => 0,
					'specializationId' => 0,
				);
				break;

			case 'popularCourse':
				$popularCourseName = $param1;
				$params = explode('-', $param2);
				$popularCourseId = $params[0];
				$currentPage = $params[1];
				$entityIdStr = $param1."/".$param2;
				$entityIdForLayer = $params[0];
				$entityName = $popularCourseName;
				if(!is_numeric($popularCourseId) || empty($popularCourseId) || $popularCourseId < 1 || (!is_numeric($currentPage) && !empty($currentPage))){
            		show_404();
        		}
				$limit = $lib->getLimitForPagination($currentPage,$pageSize);
				$displayData['articleList'] = $this->articleRepository->getArticleListBasedOnPopularCourse($popularCourseId,$limit,$type);
				if($isAjax == 0){
					$paginationURL = SHIKSHA_HOME."/".$popularCourseName."/articles-pc-".$popularCourseId.'-@pageno@';
					$paramForSEO = array('courseId'=>$popularCourseId);
					$displayData['totalArticles'] = $lib->getTotalArticlesBasedOnCourse($popularCourseId,'',$articleModel);
				}
				$beaconTrackData['extraData']['baseCourseId'] = $popularCourseId;
				break;

			case 'substream':
				$streamName = $param1;
				$subStreamName = $param2;
				$params = explode('-', $param3);
				$streamId = $params[0];
				$substreamId = $params[1];
				$currentPage = $params[2];
				$entityIdStr = $param1."/".$param2."/".$param3;
				$entityIdForLayer = $params[1];
				$entityName = $subStreamName;
				if(!is_numeric($streamId) || empty($streamId) || $streamId < 1 || !is_numeric($substreamId) || empty($substreamId) || $substreamId < 1 || (!is_numeric($currentPage) && !empty($currentPage))){
            		show_404();
        		}
				$limit = $lib->getLimitForPagination($currentPage,$pageSize);
				$hierarchyIds = $lib->getHierarchyId($streamId,$substreamId);
				$displayData['articleList'] = $this->articleRepository->getArticleListBasedOnHierarchy($hierarchyIds,$limit,$type);
				if($isAjax == 0){
					$paginationURL = SHIKSHA_HOME."/".$streamName."/".$subStreamName."/articles-sb-".$streamId.'-'.$substreamId.'-@pageno@';
					$paramForSEO = array('stream_id'=>$streamId,'substream_id'=>$substreamId);
					$displayData['totalArticles'] = $lib->getTotalArticlesBasedOnHierarchy($hierarchyIds,'',$articleModel);
				}
				$beaconTrackData['extraData']['hierarchy'] = array(
					'streamId' => $streamId,
					'substreamId' => $substreamId,
					'specializationId' => 0,
				);
				break;

			case 'all':
				$params = explode('-', $param1);
				$entityIdStr = '';
				$currentPage = $params[0];
				$entityIdForLayer = '';
				if(!is_numeric($currentPage) && !empty($currentPage)){
					show_404();
				}
				$limit = $lib->getLimitForPagination($currentPage,$pageSize);
				$displayData['articleList'] = $this->articleRepository->getAllArticleList($limit,$type);
				if($isAjax == 0){
					$paginationURL = SHIKSHA_HOME."/articles-all-@pageno@";
					$displayData['totalArticles'] = $lib->getTotalArticles($articleModel);
				}
				break;
			
			default:
				show_404();
				break;
		}
		$displayData['beaconTrackData'] =$beaconTrackData;
		$displayData['gtmParams'] = array(
        "pageType" => 'allArticlePage',
     	"stream"=>$streamId,
	 	"substream"=>$substreamId,
	 	"baseCourseId"=>$popularCourseId,
	 	"countryId"=> 2
        );
        if($userStatus!='false' && $userStatus[0]['experience']!==""){
            $displayData['gtmParams']['workExperience'] = $userStatus[0]['experience'];
	    }
		if($isAjax == 0){
			if(empty($currentPage)){
				$currentPage = 1;
			}
			$displayData['limit'] = $limit;
			$displayData['seoURLS'] = $lib->getSEOUrls($currentPage,$displayData['totalArticles'],$pageSize,$paginationURL);
			$displayData['seoDetails'] = $this->getMetaDetailsForArticles($paramForSEO);
			$displayData['entityType'] = $entity;
			$displayData['paramForURL'] = $entityIdStr;
			$displayData['pageSize'] = $pageSize;
			$displayData['currentPage'] = $currentPage;
			$displayData['entityName'] = !empty($displayData['seoDetails']['entityName']) ? $displayData['seoDetails']['entityName'] : 'All Articles';
			$displayData['entityIdForLayer'] = $entityIdForLayer;
			if($displayData['totalArticles'] < 3){
				$displayData['noIndexMetaTag'] = 1;
			}
		}
	}

	public function getMetaDetailsForArticles($param){
		$articleLib = $this->load->library('common/UrlLib');
		return $articleLib->getSeoDetails($param);
	}

	public function showStreamSubstreamAndCoursesListHtml(){
		if($this->input->is_ajax_request()){
			$entityType = $this->input->post('entityType');
			$entityIdForLayer = $this->input->post('entityIdForLayer');
			$this->articleCache = $this->load->library('article/cache/articleCache');
			$data = $this->articleCache->getArticleStreamSubStreamCourseMapping();
			$data['entityType'] = $entityType;
			$data['entityId'] = $entityIdForLayer;
			$html = $this->load->view('streamSubStreamCourseLayer', $data, true);
			echo json_encode(array('html' => $html));
		}else{
			echo json_encode(array('html' => 'Some error has occured. Please reload the page.'));
		}
	}

	public function prepareArticleStreamSubStreamCourseMapping(){
		$this->validateCron();
		$this->load->model('article/articlenewmodel');
		$this->articleutilitylib = $this->load->library('article/ArticleUtilityLib');
		$articlesmodel = new articlenewmodel();
		$entityDetails = $articlesmodel->getArticleAttributeMapping();
		//_p(($entityDetails));die;
		if($entityDetails){
			$articleStreamSubStreamCourseMapping = array();
			$baseCourseIds = array();
			$hierarchyIds = array();
			foreach ($entityDetails as $key => $value) {
				if($value['entityType'] == 'course'){
					$baseCourseIds[] = $value['entityId'];
				}else{
					$hierarchyIds[] = $value['entityId'];
				}
			}
			$hierarchyIds = array_unique($hierarchyIds);
			unset($entityDetails);

			$this->load->builder('ListingBaseBuilder', 'listingBase');
			$listingBaseBuilder   = new ListingBaseBuilder();
			$basecourseRepository = $listingBaseBuilder->getBaseCourseRepository();
			$this->load->helper('article/article');

			if($baseCourseIds){
				$baseCourseDetails = $basecourseRepository->findMultiple($baseCourseIds);
				unset($baseCourseIds);
				foreach ($baseCourseDetails as $key => $value) {
					if($value->getIsPopular() == 1){
						//_p($value->getId());

						$urlArr = array('result' =>  array('popularCourseName' => $value->getUrlName(), 
							'base_course_id' => $value->getId()), 'case'=>'popularCourse');
						$this->articleutilitylib->createUrlForPopularCourseBasedArticleListingPage($urlArr);

						$articleStreamSubStreamCourseMapping['popularCourses'][$value->getId()] = array(
							'name' => $value->getName(),
							'url_name' => $value->getUrlName(),
							'url' => $urlArr['url']
							);
					}
				}
				$articleStreamSubStreamCourseMapping['popularCourses'] = sortArrayAlphabatically($articleStreamSubStreamCourseMapping['popularCourses']);
			}
			unset($baseCourseDetails);

			if($hierarchyIds){
				$hierarchyRepository = $listingBaseBuilder->getHierarchyRepository();
				$hierarchyDetails = $hierarchyRepository->getBaseEntitiesByHierarchyId($hierarchyIds,1,'array');
				unset($hierarchyIds);
				foreach ($hierarchyDetails as $key => $value) {
					if($value['substream']){

						$urlArr = array('result' =>  array('streamName' => $value['stream']['name'], 
							'subStreamName' => $value['substream']['url_name'],
							'stream_id' => $value['stream']['id'],
							'substream_id' => $value['substream']['id']));
						$this->articleutilitylib->createUrlForSubstreamBasedArticleListingPage($urlArr);

						$streamsToSubStreamDetails[$value['stream']['id']][$value['substream']['id']] = array(
							'name' => $value['substream']['name'],
							'url_name' => $value['substream']['url_name'],
							'url' => $urlArr['url']
						);
					}

					$urlArr = array('result' =>  array('streamName' => $value['stream']['url_name'], 
							'stream_id' => $value['stream']['id']));
					$this->articleutilitylib->createUrlForStreamBasedArticleListingPage($urlArr);

					$streamDetails[$value['stream']['id']] = array(
						'name' => $value['stream']['name'],
						'url_name' => $value['stream']['url_name'],
						'url' => $urlArr['url']
						);
				}
				unset($hierarchyDetails);
				$streamDetails = sortArrayAlphabatically($streamDetails);
				
				foreach ($streamDetails as $key => $value) {
					if($streamsToSubStreamDetails[$key]){
						$articleStreamSubStreamCourseMapping['streams'][$key] = array('all' => $streamDetails[$key])+ sortArrayAlphabatically($streamsToSubStreamDetails[$key]);
					}else{
						$articleStreamSubStreamCourseMapping['streams'][$key] = array('all' => $streamDetails[$key]);
					}
				}
			}
			unset($streamsToSubStreamDetails);

			if($articleStreamSubStreamCourseMapping){
				$this->articleCache 	= $this->load->library('article/cache/articleCache');
				$this->articleCache->storeArticleStreamSubStreamCourseMapping($articleStreamSubStreamCourseMapping);
			}
		}	
	}

	function checkResultDate(){
    	$currentDate = date("Y/m/d h:i:s");
		$displayData['resultTime'] = 'April 24 2017 12:00:00';	
		$expireDate = date("Y/m/d h:i:s", strtotime($displayData['resultTime']));
		if($currentDate<$expireDate){
			$displayData['expireFlag'] = false;
		}else{
			$displayData['expireFlag'] = true;
		}
		return $displayData;
    }

   function checkForCorrectDataForCollegeResult(){
   		$resultDate = $this->checkResultDate();
		if(!$resultDate['expireFlag']){
			return;
		}else{
			$applicationNo = isset($_POST['applicationNo'])?$this->input->post('applicationNo'):'';
			$date_of_birth = isset($_POST['dateOfBirth'])?$this->input->post('dateOfBirth'):'';
			$date_of_birth = str_replace('/', '-', $date_of_birth);

			$formattedDate = date("Y-m-d", strtotime($date_of_birth));

			$articleModel = $this->load->model('article/articlenewmodel');
		    $result = $articleModel->checkCorrectDataForCollegeResult($applicationNo,$formattedDate);

		    if(!empty($result)){
		    	setcookie('collegeExamResult', json_encode($result), time() + (86400 * 30), "/");
		    	echo json_encode($result);
		    }else{
		    	echo 'No Data Available';
		    }
		}
    }

    function thirdPartyResultPage()
	{
		$this->init();
		$userId  = isset($this->validateuser['userid'])?$this->validateuser['userid']:0;

		$resultInfo = $_COOKIE['collegeExamResult'];
		$resultInfo = json_decode($resultInfo);

		if((empty($resultInfo) && count($resultInfo) == 0) || $userId == 0 )
		{
			$newUrl = SHIKSHA_HOME.'/b-tech/articles/viteee-2017-result-blogId-13149';
			header("Location: $newUrl");
			exit;
		}

		$displayData = array();
		$studentInfo = array();
		$studentInfo['app_num']        = $resultInfo[0]->application_no;
		$studentInfo['dob']            = $resultInfo[0]->date_of_birth;
		$studentInfo['candidate_name'] = $resultInfo[0]->candidate_name;
		$studentInfo['gender']         = $resultInfo[0]->gender;
		$studentInfo['rank']           = $resultInfo[0]->rank;

		$displayData['studentInfo'] = $studentInfo;
		$displayData['listing_id'] = 29714;
		$displayData['listing_type'] = 'university';

		$displayData['validateuser'] = $this->validateuser;

		$displayData['beaconTrackData'] = array(
                                        'pageIdentifier' => 'vitResultPage',
                                    );
		 $displayData['gtmParams'] = array(
                        "pageType"    => 'vitResultPage',
                        "countryId"     => 2
                );

		 if($userId > 0)
            {
                $userWorkExp = $this->validateuser['experience'];
                if($userWorkExp >= 0)
                    $displayData['gtmParams']['workExperience'] = $userWorkExp;
            }

		$this->load->view('thirdPartyResultPageMobile',$displayData);
	}
}
