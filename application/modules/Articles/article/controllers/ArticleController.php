<?php
/*

   Copyright 2016 Info Edge India Ltd

   $Author: Pranjul

   $Id: ArticleController.php

 */

class ArticleController extends MX_Controller
{
	private $articleRepository;

    function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == "")){
			$this->userStatus = $this->checkUserValidation();
		}
		$this->load->builder('ArticleBuilder','article');
		$this->articleBuilder = new ArticleBuilder;
		$this->articleRepository = $this->articleBuilder->getArticleRepository();
	}

	private function _setDependencies(){
		$this->load->builder('ArticleBuilder','article');
		$this->articleBuilder = new ArticleBuilder;
		$this->articleRepository = $this->articleBuilder->getArticleRepository();

		if(($this->userStatus == "")){
			$this->userStatus = $this->checkUserValidation();
		}
		$this->load->library(array('ajax', 'blog_client', 'register_client'));
		$this->load->helper(array('image'));

		$this->load->model('articlemodel', $this->articlemodel);
	}

	/*
	 @name: getArticleDetailPage
	 @description: This is use to get the detail of an article
	 @param string $userInput: $articleId
	*/
	public function getArticleDetailPage($blogId){

		$displayData = array();
		//Check for Study abroad article mapping. If this mapping is available, redirect article to new article. If not, redirect article to its country page
		$this->load->model('blogs/sacontentmodel');
        	$result = $this->sacontentmodel->checkMappingwithSAContent($blogId);
		if($result['countryId']!='' || $result['contentURL']!=''){
		 if($result['contentURL']!=''){
		      $url = $result['contentURL'];
		 }
		 else{
		    $this->load->library('categoryList/AbroadCategoryPageRequest');
		    $this->abroadCategoryPageRequest   = new AbroadCategoryPageRequest();
		    $url = $this->abroadCategoryPageRequest->getURLForCountryPage($result['countryId']);
		 }
		 header("Location:$url ",TRUE,301);
		 exit;
		}

		if(in_array($blogId, array('boards','courses-after-12th'))){
			$currentUrl = trim($_SERVER['SCRIPT_URL']);
			$this->load->model('articlemodel', $this->articlemodel);
			$blogId = $this->articlemodel->getBlogIdByUrl($currentUrl);
		}

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
		//$displayData['blogId'] = !empty($displayData['blogId']) ? $displayData['blogId'] : $this->security->xss_clean($blogId);

		//redirection rules
		$this->_redirectAnd404Rules($displayData);

		//initialize
		$this->_setDependencies($displayData);
		
		//load blog object
		$blogObj = $this->articleRepository->find($displayData['blogId']);
		$displayData['blogObj'] = $blogObj;


		$this->_otherRedirects($displayData);

		//redirect to new url in article detail page
		//$articleRedirectionLib = $this->load->library('articleRedirectionLib');
		//$articleRedirectionLib->redirectToNewArticleUrl($blogObj,false);



		$this->load->library('ArticleUtilityLib');
		$blogId   = $blogObj->getId();
		$blogType = $blogObj->getType();
		$blogUrl  = addingDomainNameToUrl(array('url' => $blogObj->getUrl(),'domainName' => SHIKSHA_HOME)); //$blogObj->getUrlNew();
		$displayData['userId'] = $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'';
		$displayData['country_id'] = 2;
		$displayData['validateuser'] = $this->userStatus;
		$displayData['isNews'] = (isset($blogType) && $blogType == 'news') ? 1 : 0;

		$relatedBlogs = $this->articleutilitylib->showRelatedArticles($blogId);
		
		$displayData['relatedBlogs'] = $relatedBlogs;
		
		$sharethumbnailUrl = ($blogObj->getBlogImageURL()) ? str_replace('_s', '', $blogObj->getBlogImageURL()) : '';
		$displayData['sharethumbnailUrl'] = addingDomainNameToUrl(array('url' => $sharethumbnailUrl,'domainName' => IMGURL_SECURE));

		//Blog's comment
		$topicId = $blogObj->getDiscussionTopicId();
		$this->getBlogComments($displayData, $topicId, $userId);
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
	 	/*$quickLinkData = array();
	 	$quickLinkData = $this->articlemodel->getMappingDataForQuickLinks($blogId);*/
		$this->load->library('ArticleUtilityLib');
	 	// $quickLinkArr = $this->articleutilitylib->getQuickLinksData($quickLinkData);
	 	// foreach ($quickLinkArr['examData'] as $index => $val) {
		 // 	$examIds[] = $val['id'];
	 	// }
	 	$cityStateClgData = $this->articleutilitylib->getCityAndStateMappedToArticle(array($blogId));
		// $displayData['quickLinkData'] = $quickLinkArr;
	 	$displayData['blogUserData'] = $blogUserData;
	 	$username     = $blogUserData["name"];
	 	$displayname  = $blogUserData["displayname"];
	 	$viewedUserId = $blogUserData['userId'];

	 	$this->getAuthorInfo($displayData, $blogUserData);
	 	
	 	$mmpType = 'newmmparticle';
	 	$isLoggedIn = ($this->userStatus == 'false') ? false : true;
		$this->load->library('customizedmmp/customizemmp_lib');
		$customizedMMPLib = new customizemmp_lib();
		//View for new MMP common/newMMPForm
		//$displayData['mmpData'] = $customizedMMPLib->seoMMPLayerFromOrganicTraffic($mmpType, $isLoggedIn);
		
	 	$displayData['widgetClickedPage'] = 'ArticleDetailPage_Desktop';
		$displayData['widgetClickedPageRHS'] = 'ArticleDetailPage_Desktop_RHS';

		//load library to store information in beacon varaible for tracking purpose
		//$this->beaconTracking($displayData);
		$blogMappingData = $displayData['blogObj']->getBlogMapping();
		$blogMappingDataForBeacon = array();
		foreach ($blogMappingData as $key => $value) {
			$blogMappingDataForBeacon[] = array(
					'entityType' => $value->getEntityType(),
					'entityId' =>	$value->getEntityId()
				);
		}
		$ExamPageLib = $this->load->library('examPages/ExamPageLib');
		$beaconTrackData = $ExamPageLib->getBeaconData($blogMappingDataForBeacon,$blogId, null,'articleDetailPage');
		foreach ($beaconTrackData['extraData']['hierarchy'] as $key => $value) {
			$stream[] = $value['streamId'];
			$substream[] = $value['substreamId'];
			$specialization[] = $value['specializationId'];
		}
		$displayData['beaconTrackData'] = $beaconTrackData;
		$displayData['beaconTrackData']['extraData']['authorId'] = $viewedUserId;
		$displayData['beaconTrackData']['extraData']['viewedUserId'] = $displayData['userId'];
	 	$displayData['gtmParams'] = $this->articleutilitylib->getGTMArray($beaconTrackData, $cityStateClgData);
	 	
	 	$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_ArticleDetailPage','entity_id'=>$blogId,'stream_id'=>$displayData['gtmParams']['stream'],'substream_id'=>$displayData['gtmParams']['substream'],'specialization_id'=>$displayData['gtmParams']['specialization'],'baseCourse'=>$displayData['gtmParams']['baseCourseId'],'cityId'=>$displayData['gtmParams']['cityId'],'stateId'=>$displayData['gtmParams']['stateId'],'educationType'=>$displayData['gtmParams']['educationType'],'deliveryMethod'=>$displayData['gtmParams']['deliveryMethod']);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        //_p($displayData);die;
		//below line is used for conversion tracking purpose
		$this->setConversionTrackingParams($displayData);

		$this->getBreadCrumbForDetailPage($displayData);

		$displayData['streamCheck'] = $this->getOldCategoryToNewStreamMapping($displayData);
		if($displayData['streamCheck'] == 'beBtech'){
	        $categoryName = 'Engineering';
       		$courseId = ENGINEERING_COURSE;
        	$educationTypeId = EDUCATION_TYPE;
    	}else if($displayData['streamCheck'] == 'fullTimeMba'){
	        $categoryName = 'MBA';
        	$courseId = MANAGEMENT_COURSE;
        	$educationTypeId = EDUCATION_TYPE;
    	}
    	$eventCalfilterArr['courseId'] = $courseId;
    	$eventCalfilterArr['educationTypeId'] = $educationTypeId;
		$eventCalfilterArr['categoryName'] = $categoryName;
    	$displayData['eventCalfilterArr'] = $eventCalfilterArr;
		$displayData['tab_required_course_page']=1;

		//VITEEE result time	
		if($displayData['blogId'] == 13149 || $displayData['blogId'] == 13726){
			$resultDate = $this->checkResultDate($displayData['blogId']);
			if($resultDate['expireFlag']){
				$showCounter = false;
			}else{
				$showCounter = true;
			}

			$displayData['resultTime'] = $resultDate['resultTime'];
			$displayData['showCounter'] = $showCounter;
		}
		if($this->userStatus == 'false'){
			$this->load->library('ArticleUtilityLib');
			$displayData['entityCount'] = $this->articleutilitylib->getEntityCountsForRightRegnWidget();
                        switch ($blogObj->getType()){
                                case 'boards': $displayData['trackingKeyIdForRightRegnWidget'] = 1461;
                                                $displayData['trackingKeyIdForBottomStickyWidget'] = 1465;
                                                break;
                                case 'coursesAfter12th': $displayData['trackingKeyIdForRightRegnWidget'] = 1473;
                                                $displayData['trackingKeyIdForBottomStickyWidget'] = 1477;
                                                break;
                                default:        $displayData['trackingKeyIdForRightRegnWidget'] = 1220;
                                                $displayData['trackingKeyIdForBottomStickyWidget'] = 1258;
                                                break;
                        }
		}
		$displayData['ga_user_level'] = !empty($displayData['userId']) ? 'Logged In':'Non-Logged In';

		$displayData['relatedEntityIds'] = $this->articleutilitylib->getArticleEntitiesFromArticleId($blogMappingData);

		$displayData['articleImageData'] = $this->articleutilitylib->parseImageData($blogObj);

		$displayData['GA_currentPage'] = 'Article_Detail_Page';

		$hierarchyData[] = $displayData['relatedEntityIds']['primaryHierarchy'];
		foreach ($hierarchyData as $key => $value) {
			$payload['streamId'] = $value['stream'];
            $payload['substreamId'] = $value['substream'];
            $payload['specId'] = $value['specialization'];
		}
		$payload['basecourseId'] = implode(',',$displayData['relatedEntityIds']['course']);
		$apiData = $this->articleutilitylib->getHierarchyBasedRecentArticles($payload, 5);
        $displayData['recentArticles'] = $apiData['recentArticles'];
		$displayData['chpInterLinking']['links']  = $apiData['chpInterLinking']; //chp interlinking
        $displayData['chpInterLinking']['gaPage'] = 'ADP';
 
		$this->load->view('article/articleDetailPage', $displayData);
	}

	private function _redirectAnd404Rules(&$displayData){
		$this->load->library('ArticleUtilityLib');
		$this->articleutilitylib->blogRedirectRules($displayData);
		$this->articleutilitylib->blogShow404Rules($displayData);
	}

	private function _otherRedirects(&$displayData){
		//if object is blank, send to all article page
		if(empty($displayData['blogObj']) || !is_object($displayData['blogObj'])){
			$url = SHIKSHA_HOME.'/articles-all';
            redirect($url, 'location', 301);
            exit;
		}
		if($displayData['oldArticleUrl'] == 1){
			$url = $displayData['blogObj']->getUrl();
			if(empty($url)){
				$url = SHIKSHA_HOME.'/articles-all';
			}
			redirect($url, 'location', 301);
            exit;
		}
		//if page num is greater than last page, then send to first page
		$count = count($displayData['blogObj']->getDescription());
		
		if($count<=0){
			show_404();exit();
		}

		if($displayData['pageNum'] >= $count){
			$url = $displayData['blogObj']->getUrl();
			if(empty($url)){
				$url = SHIKSHA_HOME.'/articles-all';
			}
			redirect($url, 'location', 301);
            exit;
		}
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

	private function getBlogComments(&$displayData, $topicId, $userId){
		$this->load->library('message_board_client');
		$closeDiscussion = 0;
		$loggedUserId = ($userId=='') ? 0 : $userId;
		if($topicId>0 && $topicId!=''){
			$msgbrdClient = new Message_board_client();
			$ResultOfDetails = $msgbrdClient->getEntityComments(12, $topicId, 0, 10, $loggedUserId);
		}
		$topic_reply = isset($ResultOfDetails[0]['MsgTree'])?$ResultOfDetails[0]['MsgTree']:array();
		$answerReplies = isset($ResultOfDetails[0]['Replies'])?$ResultOfDetails[0]['Replies']:array();
		$displayData['topicId'] = $topicId;
		if(is_array($topic_reply) && count($topic_reply) > 0)
		{
			$topic_messages = array();
			$topic_replies = array();
			$i = -1;
			foreach($topic_reply as $topicComment)
			{
				if($topicComment['parentId']!=0)
				{
					$found = 0;
					if(substr_count($topicComment['path'],'.') == 1)
					{
						$i++;
						$j = -1;
						$topic_messages[$i] = array();
						$topicComment['userStatus'] = getUserStatus($topicComment['lastlogintime']);
						$topicComment['creationDate'] = makeRelativeTime($topicComment['creationDate']);
						$answerId = $topicComment['msgId'];
						$topic_replies[$answerId] = array();
						array_push($topic_messages[$i],$topicComment);
						$comparison_string = $topicComment['path'].'.';
						$topic_replyInner = $answerReplies;
						foreach($topic_replyInner as $keyInner => $tempInner){
							if(strstr($tempInner['path'],$comparison_string)){
								$j++;
								$topic_replies[$answerId][$j] = array();
								$tempInner['userStatus'] = getUserStatus($topic_reply[$i]['lastlogintime']);
								$tempInner['creationDate'] = makeRelativeTime($tempInner['creationDate']);
								array_push($topic_replies[$answerId][$j],$tempInner);
							}
						}
					}
				}
			 }
		if($topic_reply[0]['blogStatus'] == 'closed')
			$closeDiscussion = 1;
	   }
	   $displayData['commentCountForTopic'] = isset($ResultOfDetails[0]['totalRows'])?($ResultOfDetails[0]['totalRows']):0;
	   $displayData['commentReplyCountForTopic'] = isset($ResultOfDetails[0]['commentReplyCount'])?($ResultOfDetails[0]['commentReplyCount']):0;
	   $displayData['closeDiscussion'] = $closeDiscussion;
	   $displayData['topic_messages'] = $topic_messages;
	   $displayData['topic_replies'] = $topic_replies;
	}

	private function setCanonicalData(&$displayData, $blogUrl, $blogId, $totalPaginationCount){
		$this->load->helper('article');
		if($displayData['url_segments'][0] != 'getArticleDetail'){ //New url
	 		if($totalPaginationCount < $displayData['pageNum']){
				redirect($blogUrl, 'location', 301);
				exit;
			}
			/**Canonical Url**/
			$type = 'new';
			$result = createSEOMetaTagsForArticle($blogUrl,$blogId, $displayData['pageNum']+1, $totalPaginationCount, $type);
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
		$username     = $blogUserData["name"];
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
				$displayData['authoruserName'] = $blogUserData["name"];
				$displayData['displayname'] = $blogUserData["displayname"];
				$displayData['authorUrl'] = SHIKSHA_HOME.'/author/'.$displayname;
			}
		}
	}

	private function setConversionTrackingParams(&$displayData){
		$displayData['regTrackingPageKeyId']       = 205;
		$displayData['qtrackingPageKeyId']         = 206;
		$displayData['regbottomTrackingPageKeyId'] = 208;
		$displayData['rtrackingPageKeyId']         = 451;
		$displayData['ratrackingPageKeyId']        = 455;
	}

	public function getArticles($entity,$param1,$param2,$param3){
		$pageSize = 20;
		$this->init();
		$displayData['validateuser'] = $this->userStatus;
		$lib = $this->load->library('article/ArticleUtilityLib');
		$getArray = array('bc'=>$this->input->get('bc'),'et'=>$this->input->get('et'),'dm'=>$this->input->get('dm'));
		$filters['result'] = $lib->parseFilters($getArray);
		$articleModel = $this->load->model('article/articlenewmodel');
		$this->load->helper('article');
		$validArticleIds = $lib->getFilteredArticles($filters['result'],$articleModel);
		$flagForNoResult = 0;
		if(!empty($filters['result']) && empty($validArticleIds)){
			$flagForNoResult = 1;
		}
		if(!empty($filters['result'])){
			$displayData['noIndexMetaTag'] = 1;
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
				if(!is_numeric($streamId) || empty($streamId) || $streamId < 1 || (!is_numeric($currentPage) && !empty($currentPage))){
            		show_404();
        		}
				$paginationURL = SHIKSHA_HOME."/".$streamName."/articles-st-".$streamId.'-@pageno@';
				$paramForBreadCrumb = array('stream_id'=>$streamId);
				$limit = $lib->getLimitForPagination($currentPage,$pageSize);
				if($flagForNoResult == 0){
					$hierarchyIds = $lib->getHierarchyId($streamId);
					$displayData['articleList'] = $this->articleRepository->getArticleListBasedOnHierarchy($hierarchyIds,$limit,'',$validArticleIds);
					$displayData['totalArticles'] = $lib->getTotalArticlesBasedOnHierarchy($hierarchyIds,$validArticleIds,$articleModel);
				}
				$beaconTrackData['extraData']['hierarchy'] = array(
					'streamId' => $streamId,
					'substreamId' => 0,
					'specializationId' => 0,
				);
				$dpfParam = array('parentPage'=>'DFP_AllArticle','stream_id'=>$streamId,'substream_id'=>0,'specialization_id'=>0,'entity_id'=>$param2,'pageType'=>'stream');
				break;

			case 'popularCourse':
				$popularCourseName = $param1;
				$params = explode('-', $param2);
				$popularCourseId = $params[0];
				$currentPage = $params[1];
				if(!is_numeric($popularCourseId) || empty($popularCourseId) || $popularCourseId < 1 || (!is_numeric($currentPage) && !empty($currentPage))){
            		show_404();
        		}
				$paginationURL = SHIKSHA_HOME."/".$popularCourseName."/articles-pc-".$popularCourseId.'-@pageno@';
				$paramForBreadCrumb = array('courseIds'=>$popularCourseId);
				$limit = $lib->getLimitForPagination($currentPage,$pageSize);
				if($flagForNoResult == 0){
					$displayData['articleList'] = $this->articleRepository->getArticleListBasedOnPopularCourse($popularCourseId,$limit,'',$validArticleIds);
					$displayData['totalArticles'] = $lib->getTotalArticlesBasedOnCourse($popularCourseId,$validArticleIds,$articleModel);
				}
				$beaconTrackData['extraData']['baseCourseId'] = $popularCourseId;
				$dpfParam = array('parentPage'=>'DFP_AllArticle','baseCourse'=>$popularCourseId,'entity_id'=>$param2,'pageType'=>'popularCourse');
				break;

			case 'substream':
				$streamName = $param1;
				$subStreamName = $param2;
				$params = explode('-', $param3);
				$streamId = $params[0];
				$substreamId = $params[1];
				$currentPage = $params[2];
				if(!is_numeric($streamId) || empty($streamId) || $streamId < 1 || !is_numeric($substreamId) || empty($substreamId) || $substreamId < 1 || (!is_numeric($currentPage) && !empty($currentPage))){
            		show_404();
        		}
				$paginationURL = SHIKSHA_HOME."/".$streamName."/".$subStreamName."/articles-sb-".$streamId.'-'.$substreamId.'-@pageno@';
				$paramForBreadCrumb = array('stream_id'=>$streamId,'substream_id'=>$substreamId);
				$limit = $lib->getLimitForPagination($currentPage,$pageSize);
				if($flagForNoResult == 0){
					$hierarchyIds = $lib->getHierarchyId($streamId,$substreamId);
					$displayData['articleList'] = $this->articleRepository->getArticleListBasedOnHierarchy($hierarchyIds,$limit,'',$validArticleIds);
					$displayData['totalArticles'] = $lib->getTotalArticlesBasedOnHierarchy($hierarchyIds,$validArticleIds,$articleModel);
				}
				$beaconTrackData['extraData']['hierarchy'] = array(
					'streamId' => $streamId,
					'substreamId' => $substreamId,
					'specializationId' => 0,
				);
				$dpfParam = array('parentPage'=>'DFP_AllArticle','stream_id'=>$streamId,'substream_id'=>$substreamId,'specialization_id'=>0,'entity_id'=>$param2,'pageType'=>'substream');
				break;

			case 'all':
				$params = explode('-', $param1);
				$currentPage = $params[0];
				if(!is_numeric($currentPage) && !empty($currentPage)){
					show_404();
				}
				$paginationURL = SHIKSHA_HOME."/articles-all-@pageno@";
				$limit = $lib->getLimitForPagination($currentPage,$pageSize);
				$displayData['articleList'] = $this->articleRepository->getAllArticleList($limit);
				$displayData['totalArticles'] = $lib->getTotalArticles($articleModel);
				if(!empty($filters['result']['base_course_id'])){
					$beaconTrackData['extraData']['baseCourseId'] = $filters['result']['base_course_id'];
				}
				if(!empty($filters['result']['education_type'])){
					$beaconTrackData['extraData']['educationType'] = $filters['result']['education_type'];
				}
				if(!empty($filters['result']['delivery_method'])){
					$beaconTrackData['extraData']['deliveryMethod'] = $filters['result']['delivery_method'];
				}
				$dpfParam = array('parentPage'=>'DFP_AllArticle','pageType'=>'homepage');
				break;
			
			default:
				show_404();
				break;
		}

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		if(empty($currentPage)){
			$currentPage = 1;
		}
		$lib->getQueryParam($filters);
		$discussionTopicIds = getDiscussionTopicIds($displayData['articleList']);
		$displayData['commentCount'] = $lib->getCommentCountForArticles(implode(',',$discussionTopicIds),$articleModel);
		$displayData['limit'] = $limit;
		$displayData['paginationHTML'] = doCoursepagePagination($displayData['totalArticles'],$paginationURL,$limit['lowerLimit'],$pageSize,4,$filters['queryParam']);
		$displayData['seoURLS'] = $lib->getSEOUrls($currentPage,$displayData['totalArticles'],$pageSize,$paginationURL);
	 	$res = $this->getBreadCrumbHTMLAndMetaDetailsForArticleListingPage($paramForBreadCrumb);
		$displayData['breadcrumbHtml'] = $res['breadcrumbHtml'];
		$displayData['seoDetails'] = $res['seoDetails'];
		if($displayData['totalArticles'] < 3){
			$displayData['noIndexMetaTag'] = 1;
		}
		$mmpType = 'newmmparticle';
	 	$isLoggedIn = ($this->userStatus == 'false') ? false : true;
		$this->load->library('customizedmmp/customizemmp_lib');
		$customizedMMPLib = new customizemmp_lib();
		//View for new MMP common/newMMPForm
		//$displayData['mmpData'] = $customizedMMPLib->seoMMPLayerFromOrganicTraffic($mmpType, $isLoggedIn);	
		$displayData['beaconTrackData'] =$beaconTrackData;
		$displayData['gtmParams'] = array(
        "pageType" => 'allArticlePage',
     	"stream"=>$streamId,
	 	"substream"=>$substreamId,
	 	"baseCourseId"=>$popularCourseId,
	 	"educationType"=>$filters['result']['education_type'],
	 	"deliveryMethod"=>$filters['result']['delivery_method'],
	 	"countryId"=> 2
        );
        if($userStatus!='false' && $userStatus[0]['experience']!==""){
            $displayData['gtmParams']['workExperience'] = $userStatus[0]['experience'];
	    }
		$this->load->view('articleList',$displayData);
	}

	public function updateTracking($trackingData){
		//load the library to store information in beacon varaible form tracking purpose
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($trackingData);
	}

	public function getBreadCrumbHTMLAndMetaDetailsForArticleListingPage($param){
		$articleLib = $this->load->library('common/UrlLib');
		$returnArray['breadcrumbHtml'] = $articleLib->getBreadCrumb($param); 
		$returnArray['seoDetails'] = $articleLib->getSeoDetails($param);
		return $returnArray;
	}

	private function getBreadCrumbForDetailPage(&$displayData){
		$param = array(
			'articleTitle' => $displayData['blogObj']->getTitle(),
			'courseIds'    => array(),
			'primaryHierarchy'=>'',
			'blogType'=>$displayData['blogObj']->getType()
			);
		$mapping = $displayData['blogObj']->getBlogMapping();
		foreach ($mapping as $mapObj) {
			$entityType = $mapObj->getEntityType();
			if($entityType == 'primaryHierarchy'){
				$param['primaryHierarchy'] = $mapObj->getEntityId();
			}else if($entityType == 'course'){
				$param['courseIds'][] = $mapObj->getEntityId();
			}
		}
		$articleLib = $this->load->library('common/UrlLib');
		$displayData['breadcrumbHtml'] = $articleLib->getBreadCrumb($param); 
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

	function uploadCollegeExamResultData(){
        ini_set('memory_limit',-1);
        $file_name = "/var/www/html/shiksha/public/SRMJEEE_Results_2016.xlsx";
        $this->load->library('common/PHPExcel');
        $objReader= PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel=$objReader->load($file_name);
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);
        for ($i=2;$i<=208163;$i++) {
            $applicationNo = utf8_encode($objWorksheet->getCellByColumnAndRow(0,$i)->getValue());
            $candidateName = utf8_encode($objWorksheet->getCellByColumnAndRow(1,$i)->getValue());
            $date_of_birth = date("Y-m-d",strtotime($objWorksheet->getCellByColumnAndRow(2,$i)->getValue()));//date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP(utf8_encode($objWorksheet->getCellByColumnAndRow(2,$i)->getValue())));
            $rank = utf8_encode($objWorksheet->getCellByColumnAndRow(3,$i)->getValue());
            $gender = utf8_encode($objWorksheet->getCellByColumnAndRow(4,$i)->getValue());
            $exam = 'SRMJEEE';

            if(empty($applicationNo)){
                break;
            }
        
            $data[]=array(
                    'application_no'=>$applicationNo,
                    'candidate_name'=>trim($candidateName),
                    'date_of_birth'=>$date_of_birth,
                    'rank'=>$rank,
                    'gender'=>trim($gender),
                    'exam'=>trim($exam),
                    'status'=>'live'
                    );


        }
        
        $articleModel = $this->load->model('article/articlenewmodel');
        $result = $articleModel->resultDataUploaderVITEEE('College_Exam_Result_Table',$data);
        echo $result;
    }

    function checkResultDate($blodId){
    	$currentDate = date("Y/m/d h:i:s");
    	if($blodId == 13149){
    		$resultTime = 'April 24 2017 12:00:00';
    	}elseif($blodId == 13726){
    		$resultTime = 'May 2 2017 12:00:00';
    	}

		$displayData['resultTime'] = $resultTime;	
		$expireDate = date("Y/m/d h:i:s", strtotime($displayData['resultTime']));
		if($currentDate<$expireDate){
			$displayData['expireFlag'] = false;
		}else{
			$displayData['expireFlag'] = true;
		}
		return $displayData;
    }

    function checkForCorrectDataForCollegeResult(){    	
    	$examName = isset($_POST['examName'])?$this->input->post('examName'):'';
    	$blogId = ($examName=='VITEEE')?'13149':'13726';
    	$resultDate = $this->checkResultDate($blogId);
		if(!$resultDate['expireFlag']){
			return;
		}else{		
	    	$applicationNo = isset($_POST['applicationNo'])?$this->input->post('applicationNo'):'';
	    	$date_of_birth = isset($_POST['dateOfBirth'])?$this->input->post('dateOfBirth'):'';
	    	$date_of_birth = str_replace('/', '-', $date_of_birth);

			$formattedDate = date("Y-m-d", strtotime($date_of_birth));

			$articleModel = $this->load->model('article/articlenewmodel');
	        $result = $articleModel->checkCorrectDataForCollegeResult($applicationNo,$formattedDate,$examName);
	        
	        if(!empty($result)){
	        	$cookieName = ($blogId ==13149)?'collegeExamResultVIT':'collegeExamResultSRM';
	        	setcookie($cookieName, json_encode($result), time() + (86400 * 30), "/");
	        	echo json_encode($result);
	        }else{
	        	echo 'No Data Available';
	        }
   		}
    }


	function thirdPartyResultPage()
	{
		$this->init();
		$currentUrl = $_SERVER['SCRIPT_URI'];
		$urlParams  = explode('/',$currentUrl);

		$userId  = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

		$displayData = array();
		if($urlParams[count($urlParams)-1] == 'vitResult'){
			$modifyUrl = SHIKSHA_HOME.'/b-tech/articles/viteee-2017-result-blogId-13149';
			$displayData['listing_id'] = 29714;
			$displayData['examType'] = 'VITEEE';
			$displayData['examName'] = 'VIT';
			$displayData['instUrl'] = SHIKSHA_HOME.'/university/vit-university-vellore-29714';
			$displayData['logoUrl'] = '/public/images/vit_d.png';
			$displayData['altTxt'] = 'vitee result 2017 jpg';
			$displayData['gaAttrCollegeLink'] = 'VIT_LINK';
			$displayData['gaAttrExamLink'] = 'VIT_WEBSITE';
			$displayData['cookieName'] = 'collegeExamResultVIT';
			$displayData['examUrl'] = 'http://www.vit.ac.in';
			$resultInfo = $_COOKIE[$displayData['cookieName']];
		}else{
			$modifyUrl = SHIKSHA_HOME.'/b-tech/articles/srmjeee-2017-results-blogId-13726';
			$displayData['listing_id'] = 24749;
			$displayData['examType'] = 'SRMJEEE';
			$displayData['examName'] = 'SRM';
			$displayData['instUrl'] = SHIKSHA_HOME.'/university/srm-university-chennai-kattankulathur-campus-24749';
			$displayData['logoUrl'] = '/public/images/srm_d.png';
			$displayData['altTxt'] = 'SRM result 2017 jpg';
			$displayData['gaAttrCollegeLink'] = 'SRM_LINK';
			$displayData['gaAttrExamLink'] = 'SRM_WEBSITE';
			$displayData['cookieName'] = 'collegeExamResultSRM';
			$displayData['examUrl'] = 'http://www.srmuniv.ac.in';
			$resultInfo = $_COOKIE[$displayData['cookieName']];
		}
		
		$resultInfo = json_decode($resultInfo);
		if((empty($resultInfo) && count($resultInfo) == 0) || $userId == 0 )
		{		
			header("Location: $modifyUrl");
			exit;
		}
																																																																																																																																																																																																																																																																																																																																																																																																	
		$studentInfo = array();
		$studentInfo['app_num']        = $resultInfo[0]->application_no;
		$studentInfo['dob']            = $resultInfo[0]->date_of_birth;
		$studentInfo['candidate_name'] = $resultInfo[0]->candidate_name;
		$studentInfo['gender']         = $resultInfo[0]->gender;
		$studentInfo['rank']           = $resultInfo[0]->rank;

		$displayData['studentInfo'] = $studentInfo;
		$displayData['listing_type'] = 'university';

		$displayData['validateuser'] = $this->userStatus;

		$displayData['beaconTrackData'] = array(
                                        'pageIdentifier' => 'vitResultPage',
                                    );
		 $displayData['gtmParams'] = array(
                        "pageType"    => 'vitResultPage',
                        "countryId"     => 2
                );
		 $displayData['modifyUrl'] = $modifyUrl;

		 if($userId > 0)
            {
                $userWorkExp = $this->userStatus[0]['experience'];
                if($userWorkExp >= 0)
                    $displayData['gtmParams']['workExperience'] = $userWorkExp;
            }

		$this->load->view('thirdPartyResultPage',$displayData);
	}

    public function getCustomInterlinkingWidget($blogType){
        $articlemodel = $this->load->model('articlenewmodel');
        $result = $articlemodel->getArticleInterlinkingHTML($blogType);
        if(!empty($result['interlinkingHTML'])){
                echo "<div class='clearFix'>&nbsp;</div>
                <div id='customInterlinkingHTML'>".html_entity_decode($result['interlinkingHTML'])."</div>";
        }
    }

}
