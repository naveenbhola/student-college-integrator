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

		$this->load->library(array('ajax'));
		$this->load->helper(array('article/article','image'));
	}

	public function getAmpArticleDetailPage($blogId){
                if(in_array($blogId, array('boards','courses-after-12th'))){
                        $currentUrl = trim($_SERVER['SCRIPT_URL']);
                        $currentUrl = str_replace('/amp','',$currentUrl);
                        $this->load->model('blogs/articlemodel', $this->articlemodel);
                        $blogId = $this->articlemodel->getBlogIdByUrl($currentUrl);
                }

		Modules::run('muser5/UserActivityAMP/validateBrowser', 'articleDetailPage', '',$blogId,'blog');
		$this->getArticleDetailPage($blogId,true);
	}

	function getArticleDetailPage($blogId,$ampViewFlag=false){
		$this->load->model('blogs/sacontentmodel');
		//Check for Study abroad article mapping. If this mapping is available, redirect article to new article. If not, redirect article to its country page
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
		if(is_bool($ampViewFlag) && $ampViewFlag===true){
			$ampViewFlag = true;
		}else{
			$ampViewFlag = false;
		}

		$displayData = array();
		if(in_array($blogId, array('boards','courses-after-12th'))){
			$currentUrl = trim($_SERVER['SCRIPT_URL']);
			$this->load->model('blogs/articlemodel', $this->articlemodel);
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
		
		$displayData['ampViewFlag'] = $ampViewFlag;
		//redirection rules
		$this->_redirectAnd404Rules($displayData);

		//initialize
		$this->_setDependencies($displayData);
		$this->load->helper('mcommon5/mobile_html5');
		//load blog object
		$blogObj = $this->articleRepository->find($displayData['blogId'], $ampViewFlag);
		
		$displayData['blogObj'] = $blogObj;
		
		$this->_otherRedirects($displayData);

		//redirect to new url in article detail page
		//$articleRedirectionLib = $this->load->library('article/articleRedirectionLib');
		//$articleRedirectionLib->redirectToNewArticleUrl($blogObj,$ampViewFlag);

		$this->load->library('ArticleUtilityLib');
		$blogId   = $blogObj->getId();
		$blogType = $blogObj->getType();
		$blogUrl  = addingDomainNameToUrl(array('url' => $blogObj->getUrl(),'domainName' => SHIKSHA_HOME));

		$displayData['country_id'] = 2;
		$displayData['validateuser'] = $this->validateuser;
		$displayData['userId'] = $userId = isset($this->validateuser['userid']) ? $this->validateuser['userid'] : '';
		$displayData['GA_userLevel'] = $userId > 0 ? 'Logged In': 'Non-Logged In';
		
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
		$displayData['blogEntitiesMapping'] = $this->articleutilitylib->getArticleEntitiesFromArticleId($blogObj->getBlogMapping());

		$totalPaginationCount = 1;
	 	if($blogObj->getBlogLayout() == 'general'){
	 		$totalPaginationCount = count($displayData['blogPagesIndex']);
	 	}

	 	/*Blog comment*/
	 	$commentLimit = 5;
		$topicId = $blogObj->getDiscussionTopicId();
		$this->getBlogComments($displayData, $topicId, $commentLimit);
		$displayData['commentLimit'] = $commentLimit;
		$displayData['commentTrackingKey'] = 2025;
		$displayData['replyTrackingKey'] = 2027;
		/*End blog comment*/

	 	$this->setCanonicalData($displayData, $blogUrl, $blogId, $totalPaginationCount);


	 	$blogUserId = $blogObj->getCreatorId();
	 	$blogUserData = $this->articlemodel->getUserDataOfBlogUserId($blogUserId);
	 	$displayData['blogUserData'] = $blogUserData;
	 	$username     = $blogUserData["name"];
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
		$beaconTrackData = $ExamPageLib->getBeaconData($blogMappingDataForBeacon,$blogId, null,'articleDetailPage');
	 	$displayData['gtmParams'] = $this->articleutilitylib->getGTMArray($beaconTrackData, $cityStateClgData);
		$displayData['beaconTrackData'] = $beaconTrackData;
		$displayData['beaconTrackData']['extraData']['authorId'] = $viewedUserId;
		$displayData['beaconTrackData']['extraData']['viewedUserId'] = $displayData['userId'];

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_ArticleDetailPage','entity_id'=>$blogId,'stream_id'=>$displayData['gtmParams']['stream'],'substream_id'=>$displayData['gtmParams']['substream'],'specialization_id'=>$displayData['gtmParams']['specialization'],'baseCourse'=>$displayData['gtmParams']['baseCourseId'],'cityId'=>$displayData['gtmParams']['cityId'],'stateId'=>$displayData['gtmParams']['stateId'],'educationType'=>$displayData['gtmParams']['educationType'],'deliveryMethod'=>$displayData['gtmParams']['deliveryMethod']);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->validateuser, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		//below line is used for conversion tracking purpose
		$this->setConversionTrackingParams($displayData);
		$displayData['streamCheck'] = $this->getOldCategoryToNewStreamMapping($displayData);

		//VITEEE result time	
		/*if($displayData['blogId'] == 13149 || $displayData['blogId'] == 13726){
			$resultDate = $this->checkResultDate($displayData['blogId']);
			if($resultDate['expireFlag']){
				$showCounter = false;
			}else{
				$showCounter = true;
			}
			$displayData['showCounter'] = $showCounter;
			$displayData['jqueryUIRequired'] = true;
			$displayData['resultTime'] = $resultDate['resultTime'];
		}*/
		
		$this->load->library('article/ArticleUtilityLib');
		if($this->validateuser == 'false' || empty($this->validateuser)){
			$displayData['entityCount'] = $this->articleutilitylib->getEntityCountsForRightRegnWidget();
                        switch ($blogObj->getType()){
                                case 'boards': $displayData['trackingKeyIdForRegnWidget'] = 1463;
                                                $displayData['trackingKeyIdForBottomStickyWidget'] = 1467;
                                                break;
                                case 'coursesAfter12th': $displayData['trackingKeyIdForRegnWidget'] = 1475;
                                                $displayData['trackingKeyIdForBottomStickyWidget'] = 1479;
                                                break;
                                default:        $displayData['trackingKeyIdForRegnWidget'] = 1221;
                                                $displayData['trackingKeyIdForBottomStickyWidget'] = 1259;
                                                break;
                        }
		}

		$displayData['ga_user_level'] = !empty($displayData['userId']) ? 'Logged In':'Non-Logged In';
		$displayData['GA_currentPage'] = 'Article_Detail_Page';
		$mappingObject = $displayData['blogObj']->getBlogMapping();
		$displayData['isShowIcpBanner'] = false;
		$blogPrimaryHierId = '';
		$blogPrimaryCourseId = '';
		$blogPrimaryStreamId = '';
		foreach ($mappingObject as $key) {
			if($key->getEntityType() == 'primaryHierarchy')
			{
				$blogPrimaryHierId = $key->getEntityId();
			}
			else if($key->getEntityType() == 'course' && $key->getEntityId() == CP_BANNER_COURSE_MBA){
				$courseIdsMapped[] = $key->getEntityId();
			}
			else if($key->getEntityType() == 'course' && $key->getEntityId() == CP_BANNER_COURSE_ENGG){
				$courseIdsMapped[] = $key->getEntityId();
			}
			if($key->getEntityType() == 'course'){
				$baseCourseList[] = $key->getEntityId();
			}
		}
		if(in_array(CP_BANNER_COURSE_ENGG, $courseIdsMapped)){
			$blogPrimaryCourseId = CP_BANNER_COURSE_ENGG ;				
		}else if(in_array(CP_BANNER_COURSE_MBA, $courseIdsMapped)) {
			$blogPrimaryCourseId = CP_BANNER_COURSE_MBA ;				
		}

	 	if(!empty($blogPrimaryHierId)){
            $this->load->builder('listingBase/ListingBaseBuilder');
            $listingBase = new ListingBaseBuilder();
            $hierarchyRepo = $listingBase->getHierarchyRepository();
            $baseEntities = $hierarchyRepo->getBaseEntitiesByHierarchyId(array($blogPrimaryHierId), 0, 'array');
            foreach ($baseEntities as $value) {
                $blogPrimaryStreamId = $value['stream_id'];
                $payload['streamId'] = $value['stream_id'];
                $payload['substreamId'] = $value['substream_id'];
                $payload['specId'] = $value['specialization_id'];
            }
            $displayData['disableAMPLink'] = true;// disable ampHTML
        }

        if(!empty($blogUrl) && $ampViewFlag){
            redirect($blogUrl, 'location', 301);
            exit;
		}

        $collegePredBannerDetails = getAndShowCollegePredBanner($blogPrimaryStreamId, $blogPrimaryCourseId);
        if(!empty($collegePredBannerDetails)){
	        $displayData['predBannerStream'] = $collegePredBannerDetails['predStream'];
	    	$displayData['isShowIcpBanner'] = true;
	    }

	    /* get recent articles for stream */
	    $payload['basecourseId'] = implode(',',$baseCourseList);
	    $apiData = $this->articleutilitylib->getHierarchyBasedRecentArticles($payload, 5);
	    $displayData['recentArticles'] = $apiData['recentArticles'];
        $displayData['chpInterLinking']['links']  = $apiData['chpInterLinking']; //chp interlinking
        $displayData['chpInterLinking']['gaPage'] = 'ADP';
        $displayData['chpInterLinking']['pageType'] = ($ampViewFlag) ? 'AMP' : 'MS';

		$displayData['metaDescription'] = $this->getMetaDescription($blogObj);
		if($blogObj->getSeoTitle() != '') {
		    $seoTitle = $blogObj->getSeoTitle();
		}
		else{
			$seoTitle = $blogObj->getTitle();
		}
		$displayData['seoTitle'] = $seoTitle;
		$displayData['noJqueryMobile'] = true;
		$displayData['boomr_pageid'] = "mobileArticlePage";
		$displayData['pageName'] = "mobileArticlePage"; 
		$displayData['currentUrlWithParams'] = SHIKSHA_HOME.$_SERVER["REQUEST_URI"];
		
		$displayData['articleImageData'] = $this->articleutilitylib->parseImageData($blogObj);

		$sharethumbnailUrl = ($blogObj->getBlogImageURL()) ? str_replace('_s', '', $blogObj->getBlogImageURL()) : '';
		$displayData['sharethumbnailUrl'] = addingDomainNameToUrl(array('url' => $sharethumbnailUrl,'domainName' => IMGURL_SECURE));

        $this->getBreadCrumbForDetailPage($displayData);
		if($ampViewFlag){
			$displayData['ampJsArray'] = array('carousel','form','iframe','socialShare');
			if($this->checkIfVideoExists($blogObj)){
				array_push($displayData['ampJsArray'], 'youtube');
			}
			if($this->checkIfSoundCloudExists($blogObj)){
				array_push($displayData['ampJsArray'], 'soundcloud');
			}
			$displayData['gaPageName'] = "AMP Article DETAIL PAGE";            
			$displayData['gaCommonName'] = '_AMP_ARTICLE_DETAIL_MOBILE';
			$displayData['articleInternalCss'] = $this->articleutilitylib->parseArticlePageInternalCss($blogObj);

                        $articlemodel = $this->load->model('article/articlenewmodel');
                        $result = $articlemodel->getArticleInterlinkingHTML($blogObj->getType());
                        if(!empty($result['ampHTML'])){
                                $displayData['ampHTML'] = $result['ampHTML'];
                        }
                        if(!empty($result['ampCSS'])){
                                $displayData['articleInternalCss'] = $displayData['articleInternalCss'].$result['ampCSS'];
                        }

			$this->load->view('marticle5/amp/views/articleDetails',$displayData);
		}
		else{
			$this->load->view('marticle5/articleDetails',$displayData);
		}
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

	private function checkIfSoundCloudExists($blogObj){
		$description = $blogObj->getDescription();
		$blogLayout = $blogObj->getBlogLayout();
		$soundCloudExists = false;
		foreach ($description as $descriptionObj) {
			if($blogLayout == 'qna'){
				if(strpos($descriptionObj->getAnswer(),'amp-soundcloud') !== FALSE){
					$soundCloudExists = true;
					break;
				}
			}
			else{
				if(strpos($descriptionObj->getDescription(),'amp-soundcloud') !== FALSE){
					$soundCloudExists = true;
					break;
				}
			}
		}
		return $soundCloudExists;
	}

	private function checkIfVideoExists($blogObj){
		$description = $blogObj->getDescription();
		$blogLayout = $blogObj->getBlogLayout();
		$videoExists = false;
		foreach ($description as $descriptionObj) {
			if($blogLayout == 'qna'){
				if(strpos($descriptionObj->getAnswer(),'amp-youtube') !== FALSE){
					$videoExists = true;
					break;
				}
			}
			else{
				if(strpos($descriptionObj->getDescription(),'amp-youtube') !== FALSE){
					$videoExists = true;
					break;
				}
			}
		}
		return $videoExists;
	}

	private function getMetaDescription($blogObj){
		if($blogObj->getSeoDescription() != '') {
		    $metaDescription = $blogObj->getSeoDescription();
		    $metaDescription = substr(trim($metaDescription), 0, 160);
		}
		else{
			$order   = array("\r\n", "\n", "\r","\t","<br>","<br />","<br/>","&nbsp;");
			$replace = '';
			$blogDescriptionObj = $blogObj->getDescription();
			if($blogObj->getBlogLayout() == 'qna'){
			    $text = $blogDescriptionObj[0]->getQuestion();
			}else{
			    $text = $blogDescriptionObj[0]->getDescription();
			}

			$text = str_replace($order, $replace, $text);
			$text = preg_replace("/(\<script)(.*?)(script>)/si", "", $text);
			$text = strip_tags($text);
			$text = str_replace("<!--", "&lt;!--", $text);
			$text = preg_replace("/(\<)(.*?)(--\>)/mi", "".nl2br("\\2")."", $text);
			$search = array('@<script[^>]*?>.*?</script>@si',
					'@<[\/\!]*?[^<>]*?>@si',
					'@<style[^>]*?>.*?</style>@siU',
					'@<![\s\S]*?--[ \t\n\r]*>@'
				    );
			$text = preg_replace($search, '', $text);
			$metaDescription = substr(trim($text), 0, 160);
		}
		return $metaDescription;
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
		if($this->userStatus == ""){
                $this->userStatus = $this->checkUserValidation();
        }
        $displayData['userStatus'] = $this->userStatus;
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
			$displayData['isShowIcpBanner'] = ($param2 == ICP_BANNER_COURSE) ? true : false;
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
				$dpfParam = array('parentPage'=>'DFP_AllArticle','stream_id'=>$streamId,'substream_id'=>0,'specialization_id'=>0,'entity_id'=>$param2,'pageType'=>'stream');
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
				$dpfParam = array('parentPage'=>'DFP_AllArticle','baseCourse'=>$popularCourseId,'entity_id'=>$param2,'pageType'=>'popularCourse');
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
				$dpfParam = array('parentPage'=>'DFP_AllArticle','stream_id'=>$streamId,'substream_id'=>$substreamId,'specialization_id'=>0,'entity_id'=>$param2,'pageType'=>'substream');
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
				$dpfParam = array('parentPage'=>'DFP_AllArticle','pageType'=>'homepage');
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
        if($displayData['userStatus']!='false' && $displayData['userStatus'][0]['experience']!==""){
            $displayData['gtmParams']['workExperience'] = $displayData['userStatus'][0]['experience'];
	    }

	    $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['userStatus'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

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

	function checkResultDate($blogId){
    	$currentDate = date("Y/m/d h:i:s");
    	if($blogId == '13149'){
    		$resultTime = 'April 24 2017 12:00:00';
    	}else if($blogId == '13726'){
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
		$userId  = isset($this->validateuser['userid'])?$this->validateuser['userid']:0;

		$displayData = array();
		if($urlParams[count($urlParams)-1] == 'vitResult'){
			$modifyUrl = SHIKSHA_HOME.'/b-tech/articles/viteee-2017-result-blogId-13149';
			$displayData['listing_id'] = 29714;
			$displayData['examType'] = 'VITEEE';
			$displayData['examName'] = 'VIT';
			$displayData['instUrl'] = SHIKSHA_HOME.'/university/vit-university-vellore-29714';
			$displayData['logoUrl'] = '/public/mobile5/images/vit_m.png';
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
			$displayData['logoUrl'] = '/public/mobile5/images/srm_m.png';
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

		 $displayData['modifyUrl'] = $modifyUrl;

		 if($userId > 0)
            {
                $userWorkExp = $this->validateuser['experience'];
                if($userWorkExp >= 0)
                    $displayData['gtmParams']['workExperience'] = $userWorkExp;
            }

		$this->load->view('thirdPartyResultPageMobile',$displayData);
	}

    public function getCustomInterlinkingWidget($blogType){
        $articlemodel = $this->load->model('article/articlenewmodel');
        $result = $articlemodel->getArticleInterlinkingHTML($blogType);
        if(!empty($result['interlinkingHTML'])){
                echo "<div id='customInterlinkingHTML'>".html_entity_decode($result['interlinkingHTML'])."</div>";
        }
    }

    private function getBlogComments(&$displayData, $topicId, $commentLimit){
		if($topicId>0 && $topicId!=''){	
			$commentLib  = $this->load->library('mAnA5/AnACommentLib');
			$result = $commentLib->getCommentEntity($topicId, 0, $commentLimit);
			$displayData['topicId'] = $result['topicId'];
			$displayData['commentCountForTopic'] = $result['commentCountForTopic'];
			$displayData['closeDiscussion'] = $result['closeDiscussion'];
			$displayData['topic_messages'] = $result['topic_messages'];
			$displayData['topic_replies'] = $result['topic_replies'];
			unset($result);
		}
	}
}
