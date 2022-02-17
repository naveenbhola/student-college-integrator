<?php

class ArticleMobile extends ShikshaMobileWebSite_Controller {
	
	private $userStatus = 'false';
	function __construct() {
		parent::__construct();
		$this->load->config('mcommon5/mobi_config');
		$this->load->helper('mcommon5/mobile_html5');
		$this->load->helper(array('form', 'url','image','shikshautility','blogs/article'));
		$this->load->library(array('message_board_client','blog_client','category_list_client'));
		if($this->userStatus == "")
			$this->userStatus = $this->checkUserValidation();	
	}
	

    function showArticlesList($startOffset=0, $countOffset=10, $categoryId = "", $country = "", $type = "") {
    	if($type==''){
                redirect(SHIKSHA_HOME.'/news-articles', 'location', 301);
                exit();
         } 
		if($_REQUEST['category'] && $_REQUEST['category']>0){
			$_REQUEST['subcat'] = $_REQUEST['category'];
		}
		else if($_REQUEST['subcat'] && $_REQUEST['subcat']>0){
			$_REQUEST['category'] = $_REQUEST['subcat'];
		}

		$pageNumberToBeDisplayed=($startOffset=="")?0:1;
		$startOffset=($startOffset=="" || $startOffset<=0 )?0:$startOffset;
		
		if(isset($_REQUEST['type']) && $_REQUEST['type'] != ''){
			
			$type = $_REQUEST['type'];
		}
		
		if($type == 'news') {
			setcookie('articleType','news',time() + 2592000 ,'/',COOKIEDOMAIN);			
		}else if($type == 'kumkum'){
			setcookie('articleType','kumkum',time() + 2592000 ,'/',COOKIEDOMAIN);
		}else {
		 if(empty($_COOKIE["articleType"])) {
			$type = 'allArticles';
			setcookie('articleType','allArticles',time() + 2592000 ,'/',COOKIEDOMAIN);
		  }
        }
		
		if(empty($type)) {
			if(isset($_COOKIE["articleType"]) && !empty($_COOKIE["articleType"]) && $_COOKIE["articleType"]!='news' && $_COOKIE["articleType"]!='kumkum') {
				$type = $_COOKIE["articleType"];
			}
		}

		if(empty($type)) {
			$type = "allArticles";
		}
		
		if($type=="news"){
			$pageNumber=$startOffset;
			if($startOffset ==1){
				$url=SHIKSHA_HOME."/news";
				header("Location: $url",TRUE,301);
				exit;
			}
			if($startOffset != 0) 
				$startOffset = ($startOffset-1)*$countOffset;
        }
		if(!is_numeric($startOffset) || !is_numeric($countOffset)){
		     $url=SHIKSHA_HOME."/blogs/shikshaBlog/showArticleList";
		     header("Location: $url",TRUE,301);
		     exit;
		}
		
		$this->init();
		$appId = 1;
		$criteria = array();
		$orderBy = ' lastModifiedDate desc ';
		
		if($subCatId != "") {
		    $_REQUEST['subcat'] = $subCatId;	    
		}
		if($country != "") {
		    $_REQUEST['country'] = $country;	    
		}
		if($type != "") {
		    $_REQUEST['type'] = $type;	    
		}
		if($categoryId != '') {
			$_REQUEST['category'] = $categoryId;
			$_SERVER['QUERY_STRING'] = 'category='. $_REQUEST['category'];
		}

		foreach($_REQUEST as $requestKey => $requestVal) {
		    switch(strtolower($requestKey)) {
			case 'type' :
				    $criteria['blogType'] = $_REQUEST[$requestKey];
				    $criteriaArray[] = $requestKey .'='. $_REQUEST[$requestKey];
				    break;
			case 'parent' :
				    $criteria['parentId'] = $_REQUEST[$requestKey];
				    $criteriaArray[] = $requestKey .'='. $_REQUEST[$requestKey];
				    break;
			case 'category' :
				    $criteria['boardId'] = $_REQUEST[$requestKey];
	                            if(!is_numeric($criteria['boardId'])){
        	                        show_404();
                	            }
				    $criteriaArray[] = $requestKey .'='. $_REQUEST[$requestKey];
				    break;
			case 'country' :
				    $criteria['countryId'] = $_REQUEST[$requestKey];
	                            if(!is_numeric($criteria['countryId'])){
        	                        show_404();
                	            }
				    $criteriaArray[] = $requestKey .'='. $_REQUEST[$requestKey];
				    break;
			case 'subcat' :
				    $criteria['subcat'] = $_REQUEST[$requestKey];
				    $criteriaArray[] = $requestKey .'='. $_REQUEST[$requestKey];
				    break;
			case 'orderby' : $orderBy = $_REQUEST[$requestKey]; break;
			case 'countoffset' : $countOffset =  $_REQUEST[$requestKey]; break;
			case 'startoffset' : $startOffset =  $_REQUEST[$requestKey]; break;
		    }
		}
	
		$blog_client = new Blog_client();
		$articlesList = $blog_client->getArticlesForCriteria($appId, $criteria, $orderBy, $startOffset, $countOffset);
		//$displayData = json_decode($articlesList, true);
		if(is_array($articlesList)){
			$displayData = $articlesList[0]['results'];
		}
		$categoryClient = new Category_list_client();
		
		$displayData['categoryPresent'] = false;
		if(!empty($criteria['boardId'])) {
			$displayData['categoryName'] = $categoryClient->get_category_name($appId,$criteria['boardId']);
			$displayData['categoryPresent'] = true; 
		}
		if(!empty($criteria['subcat'])){
			$subCategoryName = $categoryClient->get_category_name($appId,$criteria['subcat']);
			$displayData['subCategoryName'] = $subCategoryName;
		}
		
		$categoryClient = new Category_list_client();
		$displayData['HomePageData'] = $categoryClient->getTabsContentByCategory();
		
		if(
			(!isset($_REQUEST['type']) || empty($_REQUEST['type'])) && (
			    (isset($_REQUEST['country']) && !empty($_REQUEST['country'])) ||
			    (isset($_REQUEST['category']) && !empty($_REQUEST['category']))
			)
		  ) {
	
		if(!empty($_REQUEST['category'])) {
		    $categoryDetails = $categoryClient->getCategoryDetailsById($appId, $criteria['boardId']);
		    $caption[] = $categoryDetails['name'];
		}
		if(!empty($_REQUEST['subcat'])) {
		    $categoryDetails = $categoryClient->getCategoryDetailsById($appId, $criteria['subcat']);
		    
		    $caption[] = $categoryDetails['name'];
		}
		
	
		if(!empty($_REQUEST['country'])) {
			global $countries;
			foreach($countries as $country) {
			    if($country['id'] == $_REQUEST['country']) {
				if(is_array($caption)) {
				    $caption[] =  ' in '. $country['name'];
				} else {
				    $caption[] = $country['name'];
				}
				break;
			    }
			}
		    }
		}
		if(is_array($caption)) {
		    $caption = 'Articles related to '. implode(' ',$caption);
		} else {
		    $caption = '';
		}
		$validate = $this->checkUserValidation();
		$displayData['validateuser'] = $validate;
		$displayData['caption'] = $caption;
		$displayData['startOffset'] = $startOffset;
		$displayData['countOffset'] = $countOffset;
		 $currentPage = $startOffset/10;
		 $currentPage += 1; 
		 $displayData['currentPage'] = $currentPage;

		$displayData['subCategoryId'] = $criteria['subcat'];
		$displayData['subcat_id_course_page'] = $criteria['subcat'];
		$displayData['articlePageType'] = (count($criteria)>0)?$criteria['blogType']:'All';
		if($type != "news"){
	 		 $urlType ='blogs/shikshaBlog/showArticlesList';
		     $queryString="/@start@/@count@";
		}
		else
		    $queryString="-@start@";
		  
		 
		if($_SERVER['QUERY_STRING']!='')
		     $displayData['paginationURL'] = site_url($urlType).$queryString.'?'.$_SERVER['QUERY_STRING'];
		else
		     $displayData['paginationURL'] = site_url($type).$queryString;
		 

		if(!$displayData['isCoursePagesTabsEnabled']) {
		       $baseUrl = site_url('blogs/shikshaBlog/showArticlesList');
			if($type=="news"){
				$baseUrl=site_url('news');
				$result = createSEOMetaTagsForAuthorProfilePage($startOffset,$countOffset,$baseUrl,$displayData['totalArticles'],'-',$pageNumberToBeDisplayed);
			}
			else
				$result = createSEOMetaTagsForFlavouredAndLatestArticles($startOffset,$countOffset,$baseUrl,$displayData['totalArticles']);
			$displayData['canonicalURL'] = $result['canonicalURL'];
			if($type!='news'){
				$displayData['previousURL'] = $result['previousURL'];
				$displayData['nextURL'] = $result['nextURL'];
			}		
			  /*Redirection Rule*/
			$enteredURL = getCurrentPageURL();
			if($_SERVER['QUERY_STRING']!='')
			     $enteredURL=$enteredURL.'?'.$_SERVER['QUERY_STRING'];
			
			$canonicalurl = $displayData['canonicalURL'];
                        if($_SERVER['QUERY_STRING']!='' && $type=='news')
                             $canonicalurl=$canonicalurl.'?'.$_SERVER['QUERY_STRING'];

			if($enteredURL!=$canonicalurl && REDIRECT_URL=='live'){
				if( (strpos($canonicalurl, "http") === false) || (strpos($canonicalurl, "http") != 0) || (strpos($canonicalurl, SHIKSHA_HOME) === 0) || (strpos($canonicalurl,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($canonicalurl,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($canonicalurl,ENTERPRISE_HOME) === 0) ){
					header("Location: $canonicalurl",TRUE,301);
				}
				else{
				    header("Location: ".SHIKSHA_HOME,TRUE,301);
				}
				exit;
			}
		}
		if($pageNumberToBeDisplayed==1)
		$displayData['pageNumber'] ='Page '.$pageNumber.' - ';
		$displayData['criteria'] = implode('&', $criteriaArray);
		$displayData['blogType']= $type;
		$displayData['baseUrl'] = $baseUrl;
		$displayData['categoryId'] = $_REQUEST['category'];
		$displayData['boomr_pageid'] = "article_list_page";
		$displayData['type']=$_REQUEST['type'];

		//for tracking purpose
		$displayData['trackingpageIdentifier']='articlePage';
		$displayData['pageNo']=($startOffset/100)+1;
		$displayData['trackingtype']=$_REQUEST['type'];
		$displayData['trackingcatID']=$_REQUEST['category'];
		$displayData['trackingsubCatID'] = $_REQUEST['subcat']; 

		$displayData['trackingcountryId']= $country !='' ?$country:2;
		

		//load libary for storing information in beacon varaible for tracking purpose
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($displayData);

		if(($_POST['postType']) &&  ($_POST['postType'] == 'fetchAjax')){
			$this->load->view('marticle5/mobileArticleSection', $displayData);
		}else {
			$this->load->view('marticle5/mobileShowArticlesList', $displayData);
		}
	}
	
	function getNewsArticles($start = 0){
		$param['pageNo']  = $start;
		$param['urlStr']  =  'default';
   		$artObj = $this->load->library('article/articleRedirectionLib');
		$artObj->redirectDefault($param);
	}
   
	function getLatestArticles($start = 0){
		$this->showArticlesList($start,20,'','','allArticels');
		return;
	}	
	
	function getPopularArticles($start = 0){
		$this->showArticlesList($start,20,'','','popular');
		return;
	}
	
	
	function blogDetails($blogId)
	{
		if (isset($_REQUEST['page']))
		{
			$pageNum = $_REQUEST['page'];
		}
		else
		{
			$pageNum = 1;
		}
		$this->load->model('articlesmodel');
		$articlesmodel = new articlesmodel();
		
		$urlseg = $this->uri->segment(1);
		if(preg_match('/alert\(/i', $urlseg)){
			$redirectUrl = $articlesmodel->getUrlOfArticle($blogId);
       	    $url = $redirectUrl['url'];
       	    header("Location:$url",TRUE,301);
		}
		$urlseg = $this->security->xss_clean($urlseg);

		if($urlseg=="news"){
		    $articleType="news";
		    $urlseg = $this->uri->segment(2);
		    
		}
		$url_segments = explode("-", $urlseg);
		if ($url_segments[0] != 'getArticleDetail') {
				$i = 0;
			    $value = 1;
				foreach ($url_segments as $arr)
				{
					if ($arr == 'article')
					{
							$value = $i;
							break;
					}
					$i++;
				}

				$blogId   = (int)$url_segments[($value)+1];
				$pageNum  = (int)$url_segments[($value)+2];
				if(!isset($pageNum)){$pageNum=1;}
				   $displayData['canonicalURL'] = $base_url.'-'.$blogId.'-'.$pageNum;
				
		}
		$pageNum--;
		if($blogId=='' || !is_numeric($blogId)){
		     show_404();
		}

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

                //Ankur: Redirect to Hard-coded Articles for JEE Main Exam
                if($blogId >= 7825 && $blogId <= 7828 ){
                        switch($blogId){
                                case 7825: $url = SHIKSHA_HOME.'/jee-main-college-predictor-find-college-branch-based-on-your-rank-article-10276-1'; break;
                                case 7826: $url = SHIKSHA_HOME.'/jee-main-college-predictor-know-college-cut-offs-article-10277-1'; break;
                                case 7827: $url = SHIKSHA_HOME.'/jee-main-college-predictor-find-college-for-a-branch-article-10279-1'; break;
                                case 7828: $url = SHIKSHA_HOME.'/shiksha-com-launches-jee-main-college-predictor-tool-article-10275-1'; break;
                        }
                        header("Location:$url ",TRUE,301);
                        exit;
                }

		$this->init();
	
		//In case of Articles, get the URL from the DB.
		//Then, check if the entered URL is same as this one. If yes, then OK. If no, then perform a 301 redirect to the correct one
		//P.S. This will be done only in case of no pagination i.e. for the first page only.
		if (!isset($_REQUEST['page']) && REDIRECT_URL=='live' && $pageNum <= 0){
				$this->load->library('common/Seo_client');
				$Seo_client = new Seo_client();
				$dbURL = $Seo_client->getURLFromDB($blogId,'blog');
				$enteredURL = $_SERVER['SCRIPT_URI'];
				if($dbURL['URL']!='' && $dbURL['URL']!=$enteredURL){
					$url = $dbURL['URL'];
					if($_SERVER['QUERY_STRING']!='' && $_SERVER['QUERY_STRING']!=NULL){
					    $url = $url."?".$_SERVER['QUERY_STRING'];
						if( (strpos($url, "http") === false) || (strpos($url, "http") != 0) || (strpos($url, SHIKSHA_HOME) === 0) || (strpos($url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($url,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($url,ENTERPRISE_HOME) === 0) ){
							header("Location: $url",TRUE,301);
						}
						else{
						    header("Location: ".SHIKSHA_HOME,TRUE,301);
						}
					}
					else{
						if( (strpos($url, "http") === false) || (strpos($url, "http") != 0) || (strpos($url, SHIKSHA_HOME) === 0) || (strpos($url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($url,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($url,ENTERPRISE_HOME) === 0) ){
							header("Location: $url",TRUE,301);
						}
						else{
						    header("Location: ".SHIKSHA_HOME,TRUE,301);
						}
					}
					exit;
				}
		}
		//End code for Checking URLs
		//$this->userStatus = $this->checkUserValidation();
		$appID = 12;
		$blogImages = array();
		$this->load->library('upload_client');
		$msgbrdClient = new Message_board_client();
		$categoryClient = new Category_list_client();
		$blogClient = new Blog_client();
		$uplaodClient = new Upload_client();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'';
		/*code for categories start here */
		$blogCategoryCount = array();
		$i=0;
		if(is_array($count_arr))
		{
			foreach($count_arr as $categoryId => $categoryCount) {
			    $blogCategoryCount[$i]['categoryId'] = $categoryId;
			    $blogCategoryCount[$i]['categoryCount'] = $categoryCount;
			    $i++;
			}
		}
		$categoryCount = json_encode($blogCategoryCount);
		/*code for categories ends here */
		$blogInfo = $blogClient->getBlogInfo($appID,$blogId, $pageNum);
		//_P($blogInfo);
		$CategoryId = $blogInfo[0]['boardId'];
		//$courseId = $categoryClient->getPopularCourses()

		$coursesJson = $blogInfo[0]['ldbCourses'];
		$courses = json_decode($coursesJson);
		$coursesArr = explode(',',$courses);
		$courseId =  $coursesArr[0];

		
		$unifiedCategoryId = $blogInfo[0]['catparentId'];
		$countryId = $blogInfo[0]['countryId'];
	
		$excludeBlogId =   $blogId ;
		
		
		$subCatName = $articlesmodel->getSubcategoryName($CategoryId);
		$displayData['subCategoryName'] = $subCatName;

		
		// $relatedBlogs = $blogClient->getRelatedBlogs($appID,$CategoryId,0,4,$countryId,$excludeBlogId);		
		$relatedBlogs = Modules::run('messageBoard/MsgBoard/showRelatedArticles',$blogId);
		//$blogImages = $uplaodClient->getImageInfo($appID,$blogId,"blog");
		$blogImages = array();

                if(! (is_array($blogInfo) && (count($blogInfo) > 0) && (isset($blogInfo[0]['blogId'])) && is_numeric($blogInfo[0]['blogId']) ) ){
                       $url=SHIKSHA_HOME_URL.'/blogs/shikshaBlog/showArticlesList';
                       header("Location: $url",TRUE,301);
                       exit;
                }
		
		$displayData['blogImages'] = $blogImages;
		$displayData['blogInfo'] = $blogInfo;
		$displayData['chapterArticles'] = $chapterArticles;
		$displayData['closeDiscussion'] = $closeDiscussion;
		$displayData['blogsOfSameUser'] = $blogsOfSameUser;
		$displayData['relatedBlogs'] = $relatedBlogs;
		$displayData['blogId'] = $blogId;
		$displayData['userId'] = $userId;
		$displayData['CategoryId'] = $CategoryId;
		$displayData['country_id'] = $countryId;
		$displayData['tabselected'] = $tabselected;
		$displayData['unifiedCategoryId'] = $unifiedCategoryId;
		//$Validate = $this->checkUserValidation();
		$Validate = $this->userStatus;
		$displayData['validateuser'] = $Validate;
		$displayData['relatedListings'] = $relatedData;
		$displayData['blogPagesIndex'] = $blogClient->getBlogPagesIndex($appId, $blogId);
		$displayData['reg_catId'] = $CategoryId;
		$displayData['reg_courseId'] = $courseId;
		$displayData['boomr_pageid'] = "article_detail_page";

		//In case, this is the first page of the article, then add Canonical URL in the Head tag in case of OLD URLs only
		if($pageNum<=0 && $_SERVER['QUERY_STRING']!=''){
		    if($this->uri->segment(1) == 'getArticleDetail')
			$displayData['canonicalURL'] = SHIKSHA_HOME.substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'?'));
		}
	
		//Code added for Bread crumb. We need to find if the article is a featured one or latest news or none
		/*$isFeatureOrNews = $blogClient->checkIfFeatured($appID,$blogId);
		if(is_array($isFeatureOrNews)){
		   //     $displayData['isFeature'] = $isFeatureOrNews['articles'][0]['featureCheck'];
			$displayData['isNews'] = $isFeatureOrNews['articles'][0]['newsCheck'];
		}*/
		$displayData['isNews'] = (isset($blogInfo[0]['blogType']) && $blogInfo[0]['blogType']=='news')?'1':'0';
		
		if($unifiedCategoryId!='' || $CategoryId!=''){
			$this->load->library('category_list_client');
			$categoryClient = new Category_list_client();
			$categoryName = $categoryClient->get_category_name(12, $CategoryId);
			$displayData['selectedSubCategoryName'] = $categoryName;
			$displayData['categoryId'] = $unifiedCategoryId;
		}

        	//Code added by Ankur for GA Custom variable tracking
	        $displayData['subcatNameForGATracking'] = $categoryName;
	        $displayData['pageTypeForGATracking'] = 'ARTICLE_DETAIL_MOBILE';

		
		/*Code start for Canonical Url,rel='next',rel='previous',301 redirect to base page if page doesn't exits*/
		 if($articleType== 'news')
		    $base_url = SHIKSHA_HOME.'/news/'.seo_url_lowercase($displayData['blogInfo'][0]['blogTitle']);
		 else 
		    $base_url = SHIKSHA_HOME.'/'.seo_url_lowercase($displayData['blogInfo'][0]['blogTitle']);
		 $totalPaginationCount = 1;
		 $tmpCount = 0;
		 if(($displayData['blogInfo'][0]['blogLayout'])=='general'){
			 $totalPaginationCount = 0;
			 foreach($displayData['blogPagesIndex'] as $key=>$value){
			//  if(!empty($value)){
				  $totalPaginationCount++;
				  $tmpCount++;
			//  }
			}
		 } 
	       /*  if($tmpCount>1){
			 $totalPaginationCount = $totalPaginationCount - 1;
		 }*/
		 if ($url_segments[0] != 'getArticleDetail') { 
				$i = 0;
				$value = 1;
				foreach ($url_segments as $arr)
				{
					if ($arr == 'article')
					{
							$value = $i;
							break;
					}
					$i++;
				}
				$blogId  = (int)$url_segments[($value)+1];
				$pageNum  = (int)$url_segments[($value)+2];
				if(empty($pageNum)){
				   $pageNum = 1;
				}
				/**If total number of page count is less then current page number then redirect page to base
				   page url**/
				//Commenting because of redirection issue on Production
				//For QnA and Slideshow type articles, the page description is not stored in blogDescriptino table. Hence, pageNum is coming as 0 and the page is redirecting indefinitely
				if($totalPaginationCount<$pageNum){
					$url = $base_url.'-article-'.$blogId.'-1';
					header("Location: $url",TRUE,301);
					exit;
				}
	
				/**Canonical Url**/
				$type = 'new';
				$this->load->helper('blogs/article');
				$result = createSEOMetaTagsForArticle($base_url,$blogId,$pageNum,$totalPaginationCount,$type);
				$displayData['canonicalURL'] = $result['canonicalURL'];
				$displayData['nexturl'] = $result['nexturl'];
				$displayData['previousurl'] = $result['previousurl'];
		}else{
				$type = 'old';
				if(!isset($_GET['page']) || $_GET['page'] <=0) {
					 $url = $displayData['blogInfo'][0]['url'].'?token=aa&page=1';
					 header("Location: $url",TRUE,301);
				}else{
					$pageCount = $_GET['page'];
				}
				/**If total number of page count is less then current page number then redirect page to base
				  page url**/
				if($totalPaginationCount < $pageCount){
				  $url = $displayData['blogInfo'][0]['url'].'?token=aa&page=1';
				  header("Location: $url",TRUE,301);
				  exit;
				}
				$this->load->helper('blogs/article');
				$result = createSEOMetaTagsForArticle($displayData['blogInfo'][0]['url'],'',$pageCount,$totalPaginationCount,$type);
				$displayData['canonicalURL'] = $result['canonicalURL'];
				$displayData['previousurl'] = $result['previousurl'];
				$displayData['nexturl'] = $result['nexturl'];
		}
				/*Code end for Canonical Url,rel='next',rel='previous',301 redirect to base page if page doesn't exits*/
				$username=$blogInfo[0]["displayname"];
				$displayname=$blogInfo[0]["displayname"];
				$userId=$blogInfo[0]['userId'];
				/*
				$userDetails = $msgbrdClient->getUserProfileDetails($appId,$userId);
				
				if(is_array($userDetails) && count($userDetails)>0)
				{
				      $vcardUserDetails = $userDetails[0]['VCardDetails'];
					 if(isset($vcardUserDetails[0]['firstname'])){
					       if(isset($vcardUserDetails[0]['lastname'])){
						  $username = $vcardUserDetails[0]['firstname'].' '.$vcardUserDetails[0]['lastname'];
					       }
					 }*/
					 $viewedUserId = $userId;
					 //check if the author is external else read config if the author internal to make author link
					 if($userId=="3156629"){
					    $displayData['authoruserName'] = $username;
					    $displayData['externalUser']='true';
					 }
					else{
					    $this->load->library('messageBoard/AnAConfig');
					    $author_details_array = AnAConfig::$author_details_array;
					    foreach($author_details_array as $authorId=>$authorData){
						    if($authorId==$viewedUserId){
							    $authorIdUser=$viewedUserId;
							    $authorDataUser = $authorData;
							    $userUrl='/author/'.$displayname;
							    $displayData['authoruserName'] = $username;
							    $displayData['displayname'] = $displayname;
							    $displayData['authorUrl'] = $userUrl;
						    }
					    }
					}
				//}

				$this->load->model('articlemodel');

		        if($blogInfo[0]['blogLayout'] == 'slideshow'){
					$displayData['relatedBlogs'] = $this->articlemodel->getReleatedSlideShows($blogId,$CategoryId,$countryId,$excludeBlogId);
				}

				$displayData['pollJSON'] = $this->articlemodel->getPollsData($blogId);
				
				if($displayData['pollJSON']['options'][0] && $displayData['pollJSON']['options'][0]['value'] !== "") {
					$displayData['relatedBlogs'] = $this->articlemodel->getReleatedPolls($blogId,$CategoryId,$countryId,$excludeBlogId);
				}
				
				
				if(isset($_COOKIE['articleType']) && !empty($_COOKIE['articleType'])) {
					if($_COOKIE['articleType'] == 'news') {
						$baseUrl = site_url('news');
					}else {
						$baseUrl = site_url('blogs/shikshaBlog/showArticlesList');
					}
				}else {
					$baseUrl = site_url('blogs/shikshaBlog/showArticlesList');
				}

				$displayData['baseUrl'] = $baseUrl;
				$baseUrl = site_url('blogs/shikshaBlog/showArticlesList');
				$displayData['userStatus'] = $this->userStatus;

				// Code added to add College Review Widget for Full time MBA
				$displayData['subCatIdForWidgetCheck'] = $blogInfo[0]['boardId'];
				if($displayData['subCatIdForWidgetCheck'] == "23"){
					//$displayData['collegeReviewWidget'] = Modules::run('common/CommonReviewWidget/homePageWidget','mobile','ARTICLEPAGE_MOBILE');
					$displayData['collegeReviewWidget'] = "";
				}
				
				//below code used for beacon tracking purpose
				$displayData['trackingpageIdentifier']='articleDetailPage';
			$displayData['trackingpageNo']=$blogId;
			$displayData['trackingviewedUserId']=$viewedUserId;
			$displayData['trackingsubCatID']=$displayData['CategoryId'];
			$displayData['trackingAuthorId'] = $displayData['userId'];
			$displayData['trackingcountryId'] = $displayData['country_id'];
			$displayData['trackingcountryId']=2;
			//load library to store information in beacon varaible for tracking purpose
			$this->tracking=$this->load->library('common/trackingpages');
			$this->tracking->_pagetracking($displayData);

			//below line is used for conversion tracking purpose
			$displayData['trackingPageKeyId']=286;
			$displayData['trackForPages'] = true;

				$this->load->view('marticle5/articleDetails',$displayData);
		}
		
	public function votePoll($pollId,$option){
		$this->load->model('articlemodel');
		$displayData['pollJSON'] = $this->articlemodel->votePoll($pollId,$option);
	}


    function showEngineeringExams($examName = '' , $startOffset = 0) {
	        
		$startOffset = trim($startOffset,"-");
		$countOffset = 10;
		
		$pageNumberToBeDisplayed=($startOffset=="")?0:1;
		$startOffset=($startOffset=="" || $startOffset<=0 )?0:$startOffset;
		
		setcookie('articleType','news',time() + 2592000 ,'/',COOKIEDOMAIN);			
		
		$type = "allArticles";
		
		$pageNumber=$startOffset;
		if($startOffset != 0) {
			$startOffset = ($startOffset-1)*$countOffset;
		}
                 
		 
		/**** Redirection to New Exam Pages :START ****/
		// loading config file
		$this->load->config("examPages/examPageConfig");
		$mappingRedirectToNewExamPages = $this->config->item('engExamRedirectedToNewExamPages');
		
		$examsToRedirect = array_keys($mappingRedirectToNewExamPages);
		
		if(in_array($examName,$examsToRedirect)) {
		       //load exampage request
		       $this->examPageRequest = $this->load->library('examPages/ExamPageRequest');
		       $this->examPageRequest->setExamName($mappingRedirectToNewExamPages[$examName]);
		       $redirectionURL = $this->examPageRequest->getUrl();
		       $url = $redirectionURL['url'];
		       
		       if(empty($url)) {
			       show_404();
		       }
		       header("Location: $url",TRUE,301);
		       exit;
		}
		/**** Redirection to New Exam Pages :Ends ****/
		 
		global $engineeringExams;   
		$url = $engineeringExams[$examName]['url'];
	        if($examName=='uptu-see'){
	            $url = $engineeringExams['upsee']['url'];
        	    header("Location: $url",TRUE,301);
	            exit;
        	}

		if(!is_numeric($startOffset) || !is_numeric($countOffset)){
		     header("Location: $url",TRUE,301);
		     exit;
		}
		
		$this->init();
		$appId = 1;
		$criteria = array();
		$criteria['subcat'] = '56';
		
		$blog_client = new Blog_client();
		$examProperName = str_replace('-',' ',$examName);
		$this->load->model('articlemodel');
		$articlesList = $this->articlemodel->getEngineeringExams($appId, $examProperName, $startOffset, $countOffset);
		if(is_array($articlesList)){
		   $displayData = $articlesList;
		}
       
		$caption = strtoupper($examProperName);
		
		$categoryClient = new Category_list_client();
		$displayData['HomePageData'] = $categoryClient->getTabsContentByCategory();
		
		$validate = $this->checkUserValidation();
		$displayData['validateuser'] = $validate;
		$displayData['caption'] = $caption;
		$displayData['startOffset'] = $startOffset;
		$displayData['countOffset'] = $countOffset;
		 $currentPage = $startOffset/10;
		 $currentPage += 1; 
		 $displayData['currentPage'] = $currentPage;

		$displayData['subCategoryId'] = $criteria['subcat'];
		$displayData['subcat_id_course_page'] = $criteria['subcat'];
		$displayData['articlePageType'] = (count($criteria)>0)?$criteria['blogType']:'All';
		$queryString="-@start@";
		$displayData['paginationURL'] = site_url($type).$queryString;

		$displayData['m_meta_title'] = $engineeringExams[$examName]['name']." - Notification, Syllabus, Study Material, Question Papers, Results, News";
		$displayData['m_meta_description'] = "All about ".$engineeringExams[$examName]['name']." exam like syllabus, cut offs, results, important dates, study materials, preparation guide, questions papers, and more.";
		$displayData['metKeywords'] = "";
		$displayData['pageType'] = "articlePage";
		if($pageNumber>1){
		   $canonicalURL = $engineeringExams[$examName]['url'].'-'.$pageNumber;
		   $displayData['m_meta_title'] = 'Page '.$pageNumber.' - '.$displayData['m_meta_title'];
		   $displayData['m_meta_description'] = 'Page '.$pageNumber.' - '.$displayData['m_meta_description'];
		}
		else{
		   $canonicalURL = $engineeringExams[$examName]['url'];	    
		}
		$displayData['canonicalURL'] = $canonicalURL;
       
		/*Redirection Rule*/
		$enteredURL = $_SERVER['SCRIPT_URI'];			  
		if($enteredURL!=$canonicalURL && REDIRECT_URL=='live'){
		   header("Location: $canonicalURL",TRUE,301);
		   exit;
		}

		if($pageNumberToBeDisplayed==1){
			$displayData['pageNumber'] ='Page '.$pageNumber.' - ';
		}
		//$displayData['criteria'] = implode('&', $criteriaArray);
		$displayData['baseUrl'] = $url;
		$displayData['categoryId'] = 2;
		$displayData['blogType']= 'examPage';
		$displayData['engineeringExamPage'] = 'true';
		$displayData['boomr_pageid'] = "entrance_exam_page";

		if(($_POST['postType']) &&  ($_POST['postType'] == 'fetchAjax')){
			if(count($displayData['articles']) <= 0){
				echo "noresults";
			}
			else{
				$this->load->view('marticle5/mobileArticleSection', $displayData);
			}
		}else {
			$this->load->view('marticle5/mobileShowArticlesList', $displayData);
		}
		
	}

	function showSubCategoriesHTMLForArticles(){

		//Get Sub Categories from Backend
                $this->load->library('category_list_client');
                $getTabsContentByCategory = $this->category_list_client->getTabsContentByCategory();
		
		$data = array();
                $data['HomePageData'] = $getTabsContentByCategory;
                $data['HomePageType'] = 'india';
		$data['url'] = $_POST['url'];
		$data['blogType'] = $_POST['blogType'];
		
		//Check for each Sub-cat that number of articles should be more than or equal to 5
                $this->load->model('articlesmodel');
                $articleExistsArray= $this->articlesmodel->getSubcategoriesHavingArticles(5, $data['blogType']); //Get Subcategories having more than 5 articles in last 1 year

                $data['articleExistsArray'] = $articleExistsArray;
		
		//Create view of these sub cats		
		echo $this->load->view('subcategoryLayerForArticles',$data);
	}
	


	
}

