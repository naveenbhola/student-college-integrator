<?php
/*

 Copyright 2007 Info Edge India Ltd

 $Rev::               $:  Revision of last commit
 $Author: ashishj $:  Manish zope
 $Date: 2010-06-24 08:18:25 $this->load->helper(array('form', 'url'));  $:  12/11/2007
 blog.php makes call to server using XML RPC calls.

 */


 class ShikshaBlog extends MX_Controller
 {
    private $userStatus	= '';
    function init()
     {
	$this->load->helper(array('form', 'url','image','shikshautility','article'));
	$this->load->library(array('miscelleneous','message_board_client','enterprise_client','blog_client','ajax','category_list_client'));
	if($this->userStatus == "")
		$this->userStatus = $this->checkUserValidation();
    }

    function index()
    {
	$this->init();
	show_404();
	$this->load->view('test/blogindex');
    }

  function blogHome($CategoryId = 1,$countryId = 1,$tabselected = 1)
   {
	$this->init();
	$appID = 12;
	$blogClient = new Blog_client();
	$msgbrdClient = new Message_board_client();
	$categoryClient = new Category_list_client();
	$countryList = $categoryClient->getCountries($appID); // in controller
	$catList = $msgbrdClient->getCategoryList($appID,$CategoryId);

	$discussionUrl = site_url('messageBoard/MsgBoard/disscussionHome');


	$count_arr = $blogClient->getBlogCountForBoards($appID,$countryId);
	$count = isset($count_arr[1])?$count_arr[1]:0;
	/*code for categories start here */
	$categoryList = $categoryClient->getCategoryTree($appID);
	$this->getCategoryTreeArray($category, $categoryList,0,'Root');
	$countryList = $categoryClient->getCountries($appID); // in controller
	$catTree = json_encode($category);
	$eventCategoryCount = array();
	$i=0;
	if(is_array($count_arr))
	{
	        foreach($count_arr as $categoryId => $categoryCount) {
	            $eventCategoryCount[$i]['categoryId'] = $categoryId;
	            $eventCategoryCount[$i]['categoryCount'] = $categoryCount;
	            $i++;
	        }
	}
	$categoryCount = json_encode($eventCategoryCount);
	/*code for categories ends here */
	$start = isset($_REQUEST['startOffset']) ? $_REQUEST['startOffset'] : "0";
	        $rows = isset($_REQUEST['numRows']) ? $_REQUEST['numRows'] : "15";

	$userResult = $msgbrdClient->getMostContributingUser($appID,$rows);

	$param_string = "";
	if(is_array($userResult))
	{
		foreach($userResult as $temp)
		{
		   if($param_string == "")
		   $param_string = $temp['UserId'];
		   else
		   $param_string .= ",".$temp['UserId'];

		}
	}
	//$mcUsers = $blogClient->getUserInfo($appID,'multiple',$param_string);
	$mcUsers = array();


	$data['mcUsers'] = $mcUsers;
	//$data['popularTopics'] = $popularTopics;
	$data['categoryCount'] = $categoryCount;
	$data['tabselected'] = $tabselected;
	//$data['recentTopics'] = $recentTopics;
	$data['CategoryId'] = $CategoryId;
	$data['catList'] = $catList;
	$data['country_id'] = $countryId;
	$data['start'] = $start;
	$data['rows'] = $rows;
	$data['countryList'] = $countryList;
	$data['categoryList'] = $categoryList;
	$data['category_tree'] = $catTree;
	$Validate = $this->checkUserValidation();
	$data['validateuser'] = $Validate;
	$this->load->view('blogs/blogHome',$data);

   }

  function popularBlogPage($CategoryId = 1,$countryId = 1,$start=0,$rows=15)
	{
		$this->init();
		$appID = 12;
		$msgbrdClient = new Message_board_client();
		$categoryClient = new Category_list_client();
		$blogClient = new Blog_client();

		$count_arr = $blogClient->getBlogCountForBoards($appID,$countryId);
		$count = isset($count_arr[$CategoryId])?$count_arr[$CategoryId]:0;
		$popularBlogs = $blogClient->getPopularBlogs($appID,$CategoryId,$start,$rows,$countryId);
		error_log_shiksha($appID."   ".$CategoryId."    ".$countryId);
		if(is_array($popularBlogs))
		{
			$param_arr =array();
			foreach($popularBlogs as $temp)
			{
			array_push($param_arr,array(array(0=>$temp['discussionTopic'],1=>$temp['boardId']),'array'));
			}

			for($i=0;$i<count($popularBlogs);$i++)
			{
			  $urlForBlog = site_url('blogs/shikshaBlog/blogDetails').'/'.$popularBlogs[$i]['boardId'].'/'.$popularBlogs[$i]['blogId'].'/'.$popularBlogs[$i]['countryId'].'/1';
			  $popularBlogs[$i]['urlForBlog'] = $urlForBlog;
			  $popularBlogs[$i]['creationDate'] = date("F j, Y, g:i a",strtotime($popularBlogs[$i]['creationDate']));
			   $found = 0;


			   for($j=0;is_array($commetsCount) && $j < count($commetsCount);$j++)
			   {

				if($popularBlogs[$i]['boardId'] == $commetsCount[$j]['boardId'])
				{
					$popularBlogs[$i]['Count'] = $commetsCount[$j]['msgCount'];
					$found = 1;
					break;
				}
			   }

			   if($found == 0)
			    {
				$popularBlogs[$i]['Count'] = 0;
			    }

			}

		}

		 $blogs = array('results' =>$popularBlogs,
                            'totalCount'=> $count);
	   if(is_array($popularBlogs) && count($popularBlogs))
	      echo json_encode($blogs);
	   else
	     echo "";
	}

	function recentBlogsPage($CategoryId = 1,$countryId = 1,$start=0,$rows=15)
	{
		$this->init();
		$appID = 12;
		$msgbrdClient = new Message_board_client();
		$categoryClient = new Category_list_client();
		$blogClient = new Blog_client();

		$count_arr = $blogClient->getBlogCountForBoards($appID,$countryId);
		$count = isset($count_arr[$CategoryId])?$count_arr[$CategoryId]:0;
		error_log_shiksha($appID."   ".$CategoryId."    ".$countryId);
		$recentBlogs = $blogClient->getRecentPostedBlogs($appID,$CategoryId,$start,$rows,$countryId);
		if(is_array($recentBlogs))
		{
			$param_arr =array();
			foreach($recentBlogs as $temp)
			{
			array_push($param_arr,array(array(0=>$temp['discussionTopic'],1=>$temp['boardId']),'array'));
			}

			for($i=0;$i<count($recentBlogs);$i++)
			{
			  $urlForBlog = site_url('blogs/shikshaBlog/blogDetails').'/'.$recentBlogs[$i]['boardId'].'/'.$recentBlogs[$i]['blogId'].'/'.$recentBlogs[$i]['countryId'].'/2';
			  $recentBlogs[$i]['urlForBlog'] = $urlForBlog;
			  $recentBlogs[$i]['creationDate'] = date("F j, Y, g:i a",strtotime($recentBlogs[$i]['creationDate']));
			   $found = 0;
			   for($j=0;is_array($commetsCount) && $j < count($commetsCount);$j++)
			   {
				if($recentBlogs[$i]['boardId'] == $commetsCount[$j]['boardId'])
				{
					$recentBlogs[$i]['Count'] = $commetsCount[$j]['msgCount'];
					$found = 1;
					break;
				}
			   }

			   if($found == 0)
			    {
				$recentBlogs[$i]['Count'] = 0;
			    }

			}

		}

		 $blogs = array('results' =>$recentBlogs,
                            'totalCount'=> $count);
	   if(is_array($recentBlogs) && count($recentBlogs))
	      echo json_encode($blogs);
	   else
	     echo "";
	}
	
	
	public function showPoll($blogId){
		$this->load->model('articlemodel');
		$displayData['pollJSON'] = $this->articlemodel->getPollsData($blogId);
		$displayData['blogId'] = $blogId;
		return $this->load->view('blogs/pollWidget',$displayData,true);
	}
	
	public function showArticleRecommendation($blogId,$relatedBlogs){
		$displayData['relatedBlogs'] = $relatedBlogs;
		return $this->load->view('blogs/relatedBlogs',$displayData,true);
	}
	
	public function votePoll($pollId,$option){
		$this->load->model('articlemodel');
		$displayData['pollJSON'] = $this->articlemodel->votePoll($pollId,$option);
	}
	
	function myBlogsPage($CategoryId = 1,$countryId = 1,$start=0,$rows=15)
	{
		$this->init();
		$appID = 12;
		$userId = 1;
		//Region Neha
		$userStatus = $this->checkUserValidation();
		if((!is_array($userStatus)) && ($userStatus == "false"))
	        {
	        $currentUrl = site_url('blogs/shikshaBlog/myBlogsPage').'/'.$appID;
	 	redirect('user/login/userlogin/'.base64_encode($currentUrl),'location');
	        }
		$userId = $userStatus[0]['userid'];
		#Region Neha Ends

		$msgbrdClient = new Message_board_client();
		$categoryClient = new Category_list_client();
		$blogClient = new Blog_client();

		$count_arr = $blogClient->getBlogCountForBoards($appID,$countryId);
		$count = isset($count_arr[$CategoryId])?$count_arr[$CategoryId]:0;
		$myBlogs = $blogClient->getMyBlogs($appID,$CategoryId,$start,$rows,$countryId,$userId);
		if(is_array($myBlogs))
		{
			$param_arr =array();
			foreach($myBlogs as $temp)
			{
			array_push($param_arr,array(array(0=>$temp['discussionTopic'],1=>$temp['boardId']),'array'));
			}

			for($i=0;$i<count($myBlogs);$i++)
			{
			  $urlForBlog = site_url('blogs/shikshaBlog/blogDetails').'/'.$myBlogs[$i]['boardId'].'/'.$myBlogs[$i]['blogId'].'/'.$myBlogs[$i]['countryId'].'/3';
			   $myBlogs[$i]['urlForBlog'] = $urlForBlog;
			   $myBlogs[$i]['creationDate'] = date("F j, Y, g:i a",strtotime($myBlogs[$i]['creationDate']));
			   $found = 0;
			   for($j=0;is_array($commetsCount) && $j < count($commetsCount);$j++)
			   {
				if($myBlogs[$i]['boardId'] == $commetsCount[$j]['boardId'])
				{
					$myBlogs[$i]['Count'] = $commetsCount[$j]['msgCount'];
					$found = 1;
					break;
				}
			   }

			   if($found == 0)
			    {
				$myBlogs[$i]['Count'] = 0;
			    }

			}

		}
		 $blogs = array('results' =>$myBlogs,
                            'totalCount'=> $count);
	   if(is_array($myBlogs) && count($myBlogs))
	      echo json_encode($blogs);
	   else
	     echo "";
	}
   function getNewsArticles($start){	
         redirect(SHIKSHA_HOME.'/news-articles', 'location', 301);
         exit();
	 $this->showArticlesList($start,20,'','','news');
	 return;
   }
   
  function blogDetails($blogId)
  {
  	if($blogId=='2115' || $blogId=='233' || $blogId=='284' || $blogId=='232'){
		redirect(SHIKSHA_HOME.'/careers/army-officer-14', 'location', 301);
		exit();
	}
	if($blogId=='154'){
			redirect(SHIKSHA_HOME.'/careers/hotel-manager-62', 'location', 301);exit();
	}
	$this->config->load('blogs/blogsConfig');
	$kumkumArticlesToCareerPageMap = $this->config->item("kumkumArticlesToCareerPageMap");
	$kumkumArticlesOnCareer = array_keys($kumkumArticlesToCareerPageMap);
	/*if ( $this->uri->segment(1) == 'getArticleDetail')
	{
			$this->load->library('Seo_client');
			$Seo_client = new Seo_client();
			$flag_seo_url = $Seo_client->getSeoUrlNewSchema($blogId,'blog');
			if ($flag_seo_url[0] == 'false')
			{
				$title = $flag_seo_url[1];
				$title = seo_url($title,"-");
				$pageNum = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
				$url=SHIKSHA_HOME_URL."/".$title."-article-".$blogId."-".$pageNum;
				header("Location: $url",TRUE,301);
				exit;
		    }
	}*/

	if (isset($_REQUEST['page']))
    {
		$pageNum = $_REQUEST['page'];
	}
    else
    {
		$pageNum = 1;
	}
	$urlseg = $this->uri->segment(1);
	if(preg_match('/alert\(/i', $urlseg)){
		$redirectUrl = $this->articlemodel->getUrlOfArticle($blogId);
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
	if(in_array($blogId, $kumkumArticlesOnCareer))
	{
		show_404();
		exit();
	}
	$this->load->model('sacontentmodel');
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
	$this->userStatus = $this->checkUserValidation();
	$this->load->library('upload_client');
	$this->load->library('relatedClient');
	$this->load->library('listing_client');
	$this->load->library('register_client');
	$appID = 12;
	$commetsCount	= array();
	$commentPosters = array();
	$displayData = array();
	$topic_reply = array();
	$topic_messages = array();
	$blogImages = array();

	$uplaodClient = new Upload_client();
	$msgbrdClient = new Message_board_client();
	$categoryClient = new Category_list_client();
	$blogClient = new Blog_client();
    $RelatedClient = new RelatedClient();
    $LisitngClient= new Listing_client();
    
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'';

	$userFriends = array();
	//foreach($resultArray as $temp)
	//{
	//	array_push($userFriends,$temp['senderuserid']);
	//}

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
	$CategoryId = $blogInfo[0]['boardId'];
        $unifiedCategoryId = $blogInfo[0]['catparentId'];
	$countryId = $blogInfo[0]['countryId'];
	
	// code added for course pages
	$this->load->helper('coursepages/course_page');	
	$displayData['tab_required_course_page'] = checkIfCourseTabRequired($CategoryId);
	$displayData['subcat_id_course_page'] = $CategoryId;
	if($displayData['tab_required_course_page']) {
		$this->load->library('coursepages/CoursePagesUrlRequest');
		$displayData['cat_id_course_page'] = $unifiedCategoryId;
		$displayData['course_pages_tabselected'] = 'News';
		$displayData['cpgs_backLinkArray'] = array("MESSAGE" => "View all articles", "LANDING_URL" => $this->coursepagesurlrequest->getNewsTabUrl($CategoryId));
	}
	
	//$blogsOfSameUser = $blogClient->getUserBlogs($appID,$userId,0,5);
	// $relatedBlogs = $blogClient->getRelatedBlogs($appID,$CategoryId,0,5,$countryId);
	$relatedBlogs = Modules::run('messageBoard/MsgBoard/showRelatedArticles',$blogId);
	
	//$blogImages = $uplaodClient->getImageInfo($appID,$blogId,"blog");
	$blogImages = array();
//	$chapterArticles = $blogClient->getChapterArticles($appID,$blogInfo[0]['chapterNumber'],$blogInfo[0]['chapterName'],$blogInfo[0]['bookName']);
	if(is_array($blogInfo) && (count($blogInfo) > 0) && (isset($blogInfo[0]['blogId'])) && is_numeric($blogInfo[0]['blogId']) )
	{
		// Code for fetching image url start here
		// Code for fetching image url ends here

		// Code for comments start here
	    $closeDiscussion = 0;
		$topicId = $blogInfo[0]['discussionTopic'];
		$param_arr = array(array(array(0=>$topicId,1=>$CategoryId),'array'));
		$loggedUserId = ($userId=='')?0:$userId;
		if($topicId>0 && $topicId!='')
			$ResultOfDetails = $msgbrdClient->getEntityComments(12,$topicId,0,10,$loggedUserId);
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
				if($topicComment['parentId']!=0){
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
	   $displayData['topic_messages'] = $topic_messages;
	   $displayData['topic_replies'] = $topic_replies;
		//End code for Blog comments

	}
	else{
               $url=SHIKSHA_HOME_URL.'/blogs/shikshaBlog/showArticlesList';
               header("Location: $url",TRUE,301);
               exit;		
	}
	//Commented because we no longer display related institutes on the Article detail page
	/*$relatedContent = $RelatedClient->getrelatedData($appId,'blogs',$blogId,'listing');
	if(is_array($relatedContent) && isset($relatedContent[0]['relatedData'])) {
		$relatedContent = $relatedContent[0]['relatedData'];
	} else {
	    $relatedContent = array();
    }
    $relatedData = json_decode($relatedContent,true);
    $relatedData = $relatedData['resultList'];
    $courseId = array();
    $relatedColllegeCount = 0;
    foreach($relatedData  as $course) {
        $courseId[] = $course['typeId'];
    }
    if($courseId!='' && count($courseId)>0 )
    	$instituteIds = $LisitngClient->getInstitutesForMultipleCourses($appId, json_encode($courseId));
    $instituteStatsData  = $LisitngClient->getInstituteDataDetails($appId, ($instituteIds));
    $displayData['instituteStatsData'] = json_decode($instituteStatsData, true);
    $displayData['instituteCourseMap'] = json_decode($instituteIds, true);*/

	$displayData['main_message'] = $main_message;
	$displayData['blogImages'] = $blogImages;
	$displayData['commentCountForTopic'] = isset($ResultOfDetails[0]['totalRows'])?($ResultOfDetails[0]['totalRows']):0;
	$displayData['blogInfo'] = $blogInfo;
	$displayData['chapterArticles'] = $chapterArticles;
	$displayData['closeDiscussion'] = $closeDiscussion;
	$displayData['blogsOfSameUser'] = $blogsOfSameUser;
	$displayData['relatedBlogs'] = $relatedBlogs;
	$displayData['blogId'] = $blogId;
	$displayData['userId'] = $userId;
	$displayData['userFriends'] = $userFriends;
	$displayData['CategoryId'] = $CategoryId;
	$displayData['country_id'] = $countryId;
	$displayData['tabselected'] = $tabselected;
        $displayData['unifiedCategoryId'] = $unifiedCategoryId;
	//$Validate = $this->checkUserValidation();
	$Validate = $this->userStatus;
	$displayData['validateuser'] = $Validate;
	$displayData['relatedListings'] = $relatedData;
   	$displayData['blogPagesIndex'] = $blogClient->getBlogPagesIndex($appId, $blogId);
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

	//Code added for Latest news and Quick links widgets
        $this->load->library('categoryList/categoryPageRequest');
        $requestURL = new CategoryPageRequest();
        if($unifiedCategoryId!='' || $CategoryId!=''){
	        //$quick_links = $blogClient->getArticleWidgetsData('quick_links',$unifiedCategoryId,$CategoryId,$countryId);
        	//$latest_news = $blogClient->getArticleWidgetsData('latest_news',$unifiedCategoryId,$CategoryId,$countryId);
	        //$displayData['quickLinks'] = $quick_links;
        	//$displayData['latestNews'] = $latest_news;
	        $requestURL->setData(array('categoryId'=>$unifiedCategoryId,'subCategoryId'=>$CategoryId));
        	$displayData['quickLinkURL'] = $requestURL->getURL();
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$categoryName = $categoryClient->get_category_name(12, $CategoryId);
	        $displayData['selectedSubCategoryName'] = $categoryName;
		$displayData['categoryId'] = $unifiedCategoryId;
        }

        //Code added by Ankur for GA Custom variable tracking
        $displayData['subcatNameForGATracking'] = $categoryName;
        $displayData['pageTypeForGATracking'] = 'ARTICLE_DETAIL';

	$googleRemarketingParams = array(
		  "categoryId" => $unifiedCategoryId,
		  "subcategoryId" => $CategoryId,
		  "countryId" => "2",
		  "cityId" => "",
		  "SpecializationID" => trim($blogInfo[0]['ldbCourses'],'"')
		  );
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

                        if(isset($blogInfo[0]['url']) && $blogInfo[0]['url']!=""){
                                $dbURL['URL'] = $blogInfo[0]['url'];
                        }
                        else{
                                $this->load->library('common/Seo_client');
                                $Seo_client = new Seo_client();
                                $dbURL = $Seo_client->getURLFromDB($blogId,'blog');
                        }

                        $urlArray = explode('-',$dbURL['URL']);
                        array_pop($urlArray);
                        $dbURL = implode('-',$urlArray);
                        $displayData['canonicalURL'] = $dbURL.'-'.$pageNum;
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

                        if(isset($blogInfo[0]['url']) && $blogInfo[0]['url']!=""){
                                $dbURL['URL'] = $blogInfo[0]['url'];
                        }
                        else{
                                $this->load->library('common/Seo_client');
                                $Seo_client = new Seo_client();
                                $dbURL = $Seo_client->getURLFromDB($blogId,'blog');
                        }

                        $displayData['canonicalURL'] = $dbURL['URL'].'?token=aa&page='.$pageCount;
	}
			/*Code end for Canonical Url,rel='next',rel='previous',301 redirect to base page if page doesn't exits*/
			$displayData['googleRemarketingParams'] = $googleRemarketingParams;
			$registerClient = new Register_client();
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
				 }
				 */
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
			$mmp_details = array();
			if((empty($loggedUserId)) && (strpos($_SERVER['HTTP_REFERER'], 'google') !== false) && (!empty($blogInfo[0]['boardId'])))  {
			   $this->load->model('customizedmmp/customizemmp_model');
			   /*$ldbCourses = json_decode($blogInfo[0]['ldbCourses']);
			   $exp_ldbCourses = explode(",",$ldbCourses);
			   $article_ldbCourse_id = $exp_ldbCourses[0];
			   if(!empty($article_ldbCourse_id)) {*/
				  $mmp_details = $this->customizemmp_model->getMMPFormbySubCategoryId($blogInfo[0]['boardId'], 'article');
			  // }
			}
			
			if(((strpos($_SERVER['HTTP_REFERER'], 'google') !== false) || ($_GET['showpopup'] != '')) && ((empty($mmp_details))) && ($_GET['resetpwd'] != 1) && ($displayData['validateuser'] == 'false')) {
			   $this->load->model('customizedmmp/customizemmp_model');
			   $mmp_details = $this->customizemmp_model->getMMPFormbySubCategoryId($blogInfo[0]['boardId'], 'newmmparticle', 'N');
			}
			
			$displayData['mmp_details'] = $mmp_details;
			$displayData['showpopup'] = $_GET['showpopup'];
			$displayData['widgetClickedPage'] = 'ArticleDetailPage_Desktop';
			$displayData['widgetClickedPageRHS'] = 'ArticleDetailPage_Desktop_RHS';

			$displayData['trackingpageIdentifier']='articleDetailPage';
			$displayData['trackingpageNo']=$blogId;
			$displayData['trackingviewedUserId']=$viewedUserId;
			$displayData['trackingcatID']=$unifiedCategoryId;
			$displayData['trackingsubCatID'] = $CategoryId;
			$displayData['trackingcountryId']=2;
			$displayData['trackingAuthorId'] = $displayData['userId'];
			
			//load library to store information in beacon varaible for tracking purpose
			$this->tracking=$this->load->library('common/trackingpages');
			$this->tracking->_pagetracking($displayData);

			//below line is used for conversion tracking purpose
			$displayData['regTrackingPageKeyId']=205;
			$displayData['qtrackingPageKeyId']=206;
			$displayData['regbottomTrackingPageKeyId']=208;
			$displayData['rtrackingPageKeyId']=451;
			$displayData['ratrackingPageKeyId']=455;
			
			$this->load->view('blogs/blogDetails',$displayData);
  }


  function createBlog($cmsFlag=0)
  {

      $this->init();
      $this->load->library('upload_client');
      $board_id = 1;
      $appID = 12;
      $msgbrdClient = new Message_board_client();
      $categoryClient = new Category_list_client();
      $blogClient = new Blog_client();
      $uplaodClient = new Upload_client();
      $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'';

      $AuthorName = "Manish";

      $rows = 5;
      $popularTopics = array();

      /*code for categories start here */
      $countryList = $categoryClient->getCountries($appID);
      $categoryList = $categoryClient->getCategoryTree($appID);
      $others = array();
      foreach($categoryList as $temp)
      {
          if((stristr($temp['categoryName'],'Others') == false))
          {
              $categoryForLeftPanel[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
          }
          else
          {
              $others[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
          }
      }
      foreach($others as $key => $temp)
      {
          $categoryForLeftPanel[$key] = array($temp[0],$temp[1]);
      }

      $catTree = json_encode($category);
      /*code for categories ends here */
      $data['countryList'] = $countryList;
      $data['categoryForLeftPanel'] = json_encode($categoryForLeftPanel);
      $betcrumb = array();
      if(isset($_REQUEST['blogtitle']))
      {
          $blogId = $this->input->post('blogId');
	  	  $addStatus = $this->input->post('addActionType');
          $blogTitle = $this->input->post('blogtitle');
          $mailerTitle = $this->input->post('mailertitle');
	  	  $mailerSnippet = $this->input->post('mailerSnippet');
	  
	      $blogdesc = $this->input->post('blogDesc');
		  $tempDesc = array();
		  foreach($blogdesc as $desc){
			 $tempDesc[] = htmlspecialchars_decode($desc);
		  }
		  $blogdesc = $tempDesc;
		  
		  $blogQnADesc = $this->input->post('blogQnADesc');
		  $tempQnADesc = array();
		  foreach($blogQnADesc as $QnADesc){
			 $tempQnADesc[] = htmlspecialchars_decode($QnADesc);
		  }
		  $blogQnADesc = $tempQnADesc;
		  $blogQnADescriptionTags = $this->input->post('blogQnADescTag');
		  $blogQnASequenceTags = $this->input->post('blogQnASequenceTag');
		  
		  $articleLayout  = $this->input->post('articleLayout');
		  
		  $blogslideshowDescTag = $this->input->post('blogslideshowDescTag');
		  $blogslideshowDescTagSub =  $this->input->post('blogslideshowDescTagSub');
		  $blogslideshowSequenceTag = $this->input->post('blogslideshowSequenceTag');
		  $blogslideshowDescImage = $this->input->post('blogslideshowDescImage');
		  $blogslideshowDescImageTemp = array();
		  foreach ($blogslideshowDescImage as $key => $url) {
		  	$blogslideshowDescImageTemp[] = addingDomainNameToUrl(array('domainName'=>MEDIA_SERVER, 'url'=>$url));
		  }
		  $blogslideshowDescImage = $blogslideshowDescImageTemp;
		  $blogslideshowDesc = $this->input->post('blogslideshowDesc');	  
		  $polls = json_decode(url_base64_decode($this->input->post('pollJSON')),true);
          $blogDescriptionTags = $this->input->post('blogDescTag');
          $chapterNumber = $this->input->post('chapterNumber');
          $chapterName = $this->input->post('chapterName');
          $bookName = $this->input->post('bookName');
          $selectedCategory = isset($_POST['board_id']) ? $this->input->post('board_id') : 1 ;
          $countryId = 2;
          //$secCode = $this->input->post('secCode');
          $blogType = $this->input->post('blogType');
          $boardName = $this->input->post('boardName');
          $boardClass = $this->input->post('boardClass');
          $blogTypeValue = $this->input->post('blogTypeValue');
          $parentId = $this->input->post('parentId');
          $summary = $this->input->post('summary');
          $seoTitle = $this->input->post('seoTitle');
          $seoDescription = $this->input->post('seoDescription');
          $seoKeywords = $this->input->post('seoKeywords');
          $seoUrl = $this->input->post('seoUrl');	//Added by Ankur on 1 March for adding URL in Blogs
          $applyRedirection = ($this->input->post('apply_301')=='on')?1:0;
          $tags = $this->input->post('tags');
          $acronym = $this->input->post('acronym');
          //$blogThumbnail = $this->input->post('blogImageUrl');
          //$blogThumbnail = str_replace('https://images.shiksha.com', '', $blogThumbnail);
          $noIndex = ($this->input->post('noIndexCheck')=='on')?1:0;
		  $mailerTags = $this->input->post('mailerTags');
		  $tagAllMailers = $this->input->post('tagAllMailers');
		  $updateDate = ($this->input->post('updateDate')=='on')?1:0;
          $ldbCourseList = $this->input->post('ldbCourseList');
	  //code added by pragya
	  $flavorLatestUpdate = $this->input->post('key_page_51');
	  $startDateFlavor = $this->input->post('startDate');
          $endDateFlavor = $this->input->post('endDate');
          $latestUpdate = $this->input->post('key_page_52');
	  $relatedDate = $this->input->post('relatedDate');
	  $showOnHome = $this->input->post('showOnHome');
	  $articleRelevancy = $this->input->post('articleRelevancy');
	  /*
	  if($showOnHome != 'on'){
	  	$homePageArticleImg = '';
	  }
	  else { 	 
 		$homePageArticleImg = $this->input->post('blogImageForHomePage');
	   }
	   */
	   
        //Get the First Image from the Wiki. This is required since it will be used for Thumbnail & Homepage slider
        if(count($blogdesc) > 0){
        	$firstImage = $this->getFirstImageFromHTML($blogdesc[0]);	
        }
        else if(count($blogQnADesc) > 0){
        	$firstImage = $this->getFirstImageFromHTML($blogQnADesc[0]);
        }
        
        $blogThumbnail = "";
        $homePageArticleImg = "";
        if($firstImage != ''){
        	$blogThumbnail = getSmallImage($firstImage);
        	$blogThumbnail = str_replace('https://images.shiksha.com', '', $blogThumbnail);
        }
        
        //If Homepage slider is set, get the Image from the First Image
        if($showOnHome == 'on' && $firstImage != ''){
        	$homePageArticleImg = getArticleSliderImage($firstImage);
        	$homePageArticleImg = str_replace('https://images.shiksha.com', '', $homePageArticleImg);
        }
        else{
        	//Add default Image for Homepage slider
        	$homePageArticleImg = "";
        }
	   
	 $appId = 1;
	 
	  
          foreach($categoryList as $temp)
          {
              if($temp['categoryID'] == $selectedCategory)
                  $catName = $temp['categoryName'];
          }

          foreach($countryList as $temp)
          {
              if($temp['countryID'] == $countryId)
                  $country = $temp['countryName'];
          }

          if($blogTitle=='')
          {
              $message =  "Please enter the blog title";
          }
          elseif($blogdesc == '')
          {
              $message =  "The url entered is not available";
          }
          elseif($selectedCategory == "")
          {
              $message =  "Please enter the category Id";
          }
          else
          {
              $topicDescription = "You can discuss on this blog below";
              $requestIp = S_REMOTE_ADDR;
              $reqArray = array(
                      'board_id'=>$selectedCategory,
                      'user_id'=>$userId,
                      'blogTitle'=>$blogTitle,
                      'mailerTitle'=>$mailerTitle,
                      'mailerSnippet'=>$mailerSnippet,		      
                      'blogTxt'=>$blogdesc,
                      'blogDescriptionTags'=>$blogDescriptionTags,
                      'chapterNumber'=>$chapterNumber,
                      'chapterName'=>$chapterName,
                      'bookName'=>$bookName,
                      'blogType'=>$blogType,
                      'parentId'=>$parentId,
                      'summary'=>$summary,
                      'seoTitle'=>$seoTitle,
                      'seoDescription'=>$seoDescription,
                      'seoKeywords'=>$seoKeywords,
                      'seoUrl'=>$seoUrl,
                      'tags'=>$tags,
                      'country'=>$countryId,
                      'acronym'=>$acronym,
                      'blogTypeValue'=>$blogTypeValue,
                      'blogImageURL'=>$blogThumbnail,
					  'noIndex'=>$noIndex,
					  'updateDate'=>$updateDate,
					  'blogQnADesc'=>$blogQnADesc,
					  'blogQnADescriptionTags'=>$blogQnADescriptionTags,
					  'blogQnASequenceTags'=>$blogQnASequenceTags,
					  'articleLayout'=>$articleLayout,
					  'blogslideshowDescTag'=>$blogslideshowDescTag,
					  'blogslideshowDescTagSub'=>$blogslideshowDescTagSub,
					  'blogslideshowSequenceTag'=>$blogslideshowSequenceTag,
					  'blogslideshowDescImage'=>$blogslideshowDescImage,
					  'blogslideshowDesc'=>$blogslideshowDesc,
                      'ldbCourseList'=>$ldbCourseList,
		      'status'=>$addStatus,
		      'relatedDate'=>$relatedDate,
		      'blogRelevancy'=>$articleRelevancy,
		      'homepageImgURL'=>$homePageArticleImg
                      );
			  
			$mailerTags = array();
			if($tagAllMailers) {
				$this->load->library('mailer/ProductMailerConfig');
				$mailersForArticleTagging = ProductMailerConfig::getMailersForArticleTagging();
			   	$mailerTags = array_keys($mailersForArticleTagging);
			}  
			  //add blog
              if($blogId == '') {
              	  // As disscussed with ankur . We are removing categoryId from article, In this case we pass default 1 (all)
                  $topicResult = $msgbrdClient->addTopic($appID,$userId,$topicDescription,$selectedCategory,$requestIp,'blog',0,'',0);
                  $reqArray1 = $reqArray;
                  $reqArray1['topicId'] = $topicResult['ThreadID'];
                  $blogId = $blogClient->createBlog($appID, $reqArray1);
				  if(is_array($mailerTags) && count($mailerTags) > 0) {
					 $this->_addMailerTags($blogId,$mailerTags,$addStatus); 	 
				  }
				if($blogType=='news'){
					modules::run('Seo/createShikshaSitemapForNews');
				}

				//Also, refresh Homepage Cache when an article has been Created
                $this->load->library('homepage/Homepageslider_client');
                $this->homepageslider_client->deleteHomepageCacheHTMLFile(true);

            	}else{
            		$fromMode = 'edit';
					$reqArray['blogId'] = $blogId;
					$dbStatus = $this->getBlogStatus($reqArray['blogId']);
					$blogId = $blogClient->updateBlog($appID, $reqArray);
					if(count($mailerTags)>0){
						$this->_updateMailerTags($blogId,$mailerTags,$addStatus);	
					}
              	}
			  
              $blogImages = $this->input->post('blogImage');
              $blogClient->updateBlogImages($appID, $blogId, json_encode($blogImages),$addStatus);

	      	 $updateCMSData = array();
	 $updateKeyPage=array();
	 
	 $updateCMSData['item_type']='blog';
	 $updateCMSData['item_id']=$blogId;
	 $updateCMSData['item_type']='blog';
	 $updateCMSData['totalKeyPages']='29';
	 
	 if(!isset($startDateFlavor))
	  $startDateFlavor= date('Y-m-d G:i:s');
	 if(!isset($endDateFlavor))
	    $endDateFlavor = date('Y-m-d G:i:s');
	 if($flavorLatestUpdate=='on'){
	    $updateKeyPage['51_key_id'] = '51';
	    $updateKeyPage['51_start_date'] = $startDateFlavor;
	    $updateKeyPage['51_end_date'] = $endDateFlavor;
	 }
	 if($latestUpdate == 'on'){
	    $updateKeyPage['52_start_date'] =  date('Y-m-d G:i:s');
	    $updateKeyPage['52_end_date'] =  date('Y-m-d G:i:s');
	    $updateKeyPage['52_key_id'] = '52';
	    $updateLatestUpdate = $this->input->post('updateLatestUpdate');
	    $latestUpdateWasOn = $this->input->post('latestUpdateWasOn');
	    if( ($updateLatestUpdate=='on' || $updateLatestUpdate==1) && ($latestUpdateWasOn=='1') ){
	    	$updateKeyPage['52_updateLatestUpdate'] = false;
	    }
	 }
	
     $EnterpriseClientObj = new Enterprise_client();
	 $response = $EnterpriseClientObj->updateCmsItem($appId,$updateCMSData,$updateKeyPage);
	 
	 modules::run('common/GlobalShiksha/insertIntoAmpRabbitMQueue',$blogId,array(), 'article');
	 // add/edit article hierarchy and attributes
	 if($blogId>0 && !empty($addStatus)){
	 	$this->mapArticleToHierarchyAndAttributes($blogId, $blogTitle, $fromMode, $addStatus, $dbStatus);
	 	$this->articleBoardMapping($blogId, $boardName, $boardClass, $addStatus, $fromMode);
	 }
              $this->indexBlog($blogId);
			 
			  if($polls){
				$this->addPoll($polls,$blogId,$addStatus);	
			  }
              if($cmsFlag == 1) {
                  echo "<script>alert('Article Updated');window.close();</script>";
                  exit(0);
              }
              if($this->userStatus[0]['usergroup'] == 'cms') {
                  redirect('enterprise/Enterprise/index/1/created','location');
              } else {
                  redirect('blogs/shikshaBlog/blogDetails/'.$blogId,'location');
              }
          }
      }
    
      $data['validateuser'] = $this->userStatus;
      $this->load->view('blogs/createBlog',$data);
  }
  
  private function articleBoardMapping($blogId, $boardName, $boardClass, $status, $fromMode){
  		$reqArray = array(
                      'blogId'=>$blogId,
                      'boardName'=>($boardName) ? $boardName : null ,
                      'class'=>($boardClass) ? $boardClass : null,
                      'status'=>$status,
                      'creationDate'=>date('Y-m-d H:i:s'),
					  'modificationDate'=> date('Y-m-d H:i:s')
              		);
  		$this->load->model('articlemodel');
  		$this->articlemodel->articleBoardMapping($reqArray, $blogId, $fromMode);
  }

  // Mapping attribute to article
  private function mapArticleToHierarchyAndAttributes($blogId, $blogTitle, $fromMode, $status='live', $dbStatus){
  
  	if(!is_numeric($blogId)){return;}

  	$urlParam = array(); // this variable is used for creating article URL
  	$urlParam['articleId']  = $blogId;
  	$urlParam['title']  = $blogTitle;
  	$stream    = $this->input->post('stream');
  	$substream = $this->input->post('subStream');
  	$spec      = $this->input->post('specialization');
  	$primary   = $this->input->post('primary');
  	$hierArr = array();
  	foreach ($stream as $key => $value) {
  		$primaryHierarchy = ($primary == $key) ? 'yes' : 'no';
  		if(empty($substream[$key])) {
  			$substream[$key] = 'none';
  		}
  		if(empty($spec[$key])) {
  			$spec[$key] = 'none';
  		}
  		if(!empty($value)){
  			$hierArr[] = array('streamId'=>$value, 'substreamId'=>$substream[$key], 'specializationId'=>$spec[$key],'primaryHierarchy'=>$primaryHierarchy);
  		}	
  	}
  	if(!empty($hierArr)){
  		$this->load->builder('listingBase/ListingBaseBuilder');
		$listingBase   = new ListingBaseBuilder();
		$hierarchyRepo = $listingBase->getHierarchyRepository();
		$hierarchyIds  = $hierarchyRepo->getHierarchiesByMultipleBaseEntities($hierArr);	
  	}

	$finalQryData = array();
	foreach ($hierarchyIds as $key=>$value) {
		$rowData['articleId'] = $blogId;
		$rowData['entityId'] = $value['hierarchy_id'];
		$rowData['status'] = $status;
		$rowData['creationDate'] = date('Y-m-d H:i:s');
		$rowData['modificationDate'] = date('Y-m-d H:i:s');
		$value['substream_id'] = !empty($value['substream_id']) ? $value['substream_id'] : 'none';
		$value['specialization_id'] = !empty($value['specialization_id']) ? $value['specialization_id'] : 'none';
		foreach ($hierArr as $key => $v) {
			if(($v['streamId'] == $value['stream_id']) && ($v['substreamId'] == $value['substream_id']) && ($v['specializationId'] == $value['specialization_id'])){
				$primaryValue = $v['primaryHierarchy'];

				if($primaryValue == 'yes'){
					$urlParam['stream_id'] = $value['stream_id'];
					$urlParam['substream_id'] = $value['substream_id'];
				}
			}
		}
		$rowData['entityType'] = ($primaryValue == 'yes') ? 'primaryHierarchy' : 'hierarchy';
		$finalQryData[] = $rowData;
	}

	$course = $this->input->post('course');	
	$urlParam['courseId'] = $course[0];
	$exam = $this->input->post('exam');
	$popularGrouping = $this->input->post('popularGrouping');
	$college = $this->input->post('college');
	$university = $this->input->post('university');
	$group = $this->input->post('group');
	$cityList = $this->input->post('cityList');
	$stateList = $this->input->post('stateList');
	$countryList = $this->input->post('countryList');
	$careers = $this->input->post('careers');
	$otherAttributes = $this->input->post('otherAttributes');
	$tag = $this->input->post('tag');

	//apply 301 redirection for articles
	$applyRedirection = ($this->input->post('apply_301')=='on')?1:0;

    // prepare array for insert data for all fields
	$inputNameArr = array('course'=>'course','exam'=>'exam','popularGrouping'=>'popularGrouping','college'=>'college','university'=>'university','group'=>'group','cityList'=>'city','stateList'=>'state','countryList'=>'country','careers'=>'career','otherAttributes'=>'otherAttribute','tag'=>'tag');
    foreach ($inputNameArr as $fieldName => $entityType) {
    	foreach ($$fieldName as $key => $value) {
    		if(!empty($value)):
    			foreach ($value as $attrId) {
					$rowData = array();	
					$rowData['articleId'] = $blogId;
					$rowData['entityId'] = $attrId;
					$rowData['entityType'] = $entityType;
					$rowData['status'] = $status;
					$rowData['creationDate'] = date('Y-m-d H:i:s');
					$rowData['modificationDate'] = date('Y-m-d H:i:s');
					$finalQryData[] = $rowData;
				}	
			endif;		
    	}
    }

	$this->load->model('articlemodel');
	$this->articlemodel->mapArticleToAttributesAndHierarchy($finalQryData, $blogId, $fromMode);
	// Note: article url will be updated when article is in draft mode, once it goes live, can't be update url until applyRedirection value is checked from cms.
	if((($status == 'draft') || ($status == 'live' && $fromMode !='edit') || ($status == 'live' && $fromMode =='edit') && $dbStatus !='live') || $applyRedirection) {

		if(in_array($this->input->post('blogType'),array('boards','coursesAfter12th'))){
			$typeForUrl = ($this->input->post('blogType') == 'coursesAfter12th') ? 'Courses After 12th' : $this->input->post('blogType');
			$param = array('articleId'=>$blogId,'blogType'=>$typeForUrl,'board'=>$this->input->post('boardName'),'class'=>$this->input->post('boardClass'));
			$this->addBoardUrl($param);			
		}else{
			$this->addArticleUrl($urlParam);
		}
	}
  }

   function addBoardUrl($param){

   		$articleUrlLib = $this->load->library('common/UrlLib');
		$articleUrl    = $articleUrlLib->getBoardUrl($param);
   		$this->load->model('articlemodel');
		$this->articlemodel->updateNewArticleUrl($param['articleId'], $articleUrl);
   }
  
   private function _addMailerTags($articleId,$mailerTags,$status)
   {
	  $this->load->model('mailer/mailermodel');
	  $this->mailermodel->createArticleMailerTagging($articleId,$mailerTags,$status);
   }
  
   private function _updateMailerTags($articleId,$mailerTags,$status)
   {
	  $this->load->model('mailer/mailermodel');
	  $this->mailermodel->updateArticleMailerTagging($articleId,$mailerTags,$status);
   }
  
  
  private function addPoll($polls,$blogId,$addStatus){
	$this->load->model('articlemodel');
	$this->articlemodel->addPoll($polls,$blogId,$addStatus);
  }

  function validateTitleAndCaptcha()
  {
      $this->init();
      $blogId = $this->input->post('blogId');
      $title = urldecode($this->input->post('title'));
      //$secCode = $this->input->post('captcha');
      $blogClient = new Blog_client();
      $appId = 12;
      $captchResult = "";
      $checkTitleResult = ($blogId == '' ? $blogClient->checkTitle($appId,$title) : '');

      $captchResult =  "successful";
      
      $result = array('titleRes' =>$checkTitleResult,
              'captchResult'=> $captchResult);
      echo json_encode($result);
  }

   function indexBlog($blogId)
   {
       $this->init();
       error_log_shiksha("Entering IndexBlog");
       $blog_client = new Blog_client();
       $result = $blog_client->getBlogForIndex(12,$blogId);
       $request=array('countryList'=>$result[0]['countryId'],'categoryList' => $result[0]['boardId'],'title' => $result[0]['blogTitle'],'Id' => $result[0]['blogId'],'type' => 'blog','facetype' => $result[0]['blogType'],'content'=>$result[0]['blogText'],'packtype'=>'-5','authorName'=>$result[0]['blogType'],'url'=>$result[0]['url'],'tags'=>$result[0]['summary'],'noOfComments'=>$result[0]['msgCount']);
       //error_log('Shirish'.print_r($request,true));
       $indexResult = $blog_client->indexIt(12,$request);
       //print_r($indexResult);
   }

   function getPopularBlogsForHomePage($count)
   {
       $this->init();
       error_log_shiksha("Entering getPopularBlogsForHomePage Client");
       $blog_client = new Blog_client();
       $result = $blog_client->getPopularBlogsForHomePage(12,$count);
   }

   private function uploadBlogImage($blogId, $blogTitle){
       $this->init();
       $appId = 1;
       if($_FILES['blogimages']['tmp_name'][0] != '') {
           $this->load->library('upload_client');
           $uploadclient = new upload_client();
           $uploadres = $uploadclient->uploadfile($appId,'image',$_FILES,$blogTitle,$blogId,"blog","blogimages");
           if(is_array($uploadres)) {
               $logolink = $uploadres[0]['thumburl_m'];
               return $logolink;
           }
       }
       return false;
   }

   function updateBlogsForUrls()
   {
       $this->init();
       $blog_client = new Blog_client();
       $result = $blog_client->updateBlogsForUrls(1);
   }

   function deleteArticle($articleId) {
      $appId = 1;
      $this->init();
      $blog_client = new Blog_client();
      $result = $blog_client->deleteArticle($appId, $articleId);
      $this->load->model('articlemodel');
      $this->articlemodel->updateArticleMapping($articleId);
      $this->articlemodel->updateArticleBoardMapping($articleId);
	  modules::run('search/Indexer/addToQueue', $articleId, 'article', 'delete');
       print_r($result);

       //Refresh Homepage cache if an article has been deleted
       $this->load->library('homepage/Homepageslider_client');
       $this->homepageslider_client->deleteHomepageCacheHTMLFile(true);

   }

    function showArticlesList($startOffset=0, $countOffset=20, $subCatId = "", $country = "", $type = "", $isShowTag=false, $subCatArr = array()) {
     	if($type==''){
                redirect(SHIKSHA_HOME.'/news-articles', 'location', 301);
                exit();
         } 
	 $pageNumberToBeDisplayed=($startOffset=="")?0:1;
         $startOffset=($startOffset=="" || $startOffset<=0 )?0:$startOffset;
       
         if($type=="news"){
            $pageNumber=$startOffset;
	    if($startOffset ==1){
		 $url=SHIKSHA_HOME_URL."/news";
		 header("Location: $url",TRUE,301);
		 exit;
	    }
	       
	    if($startOffset != 0) 
	       $startOffset = ($startOffset-1)*$countOffset;
         }

	 if(!is_numeric($startOffset) || !is_numeric($countOffset)){
	      $url=SHIKSHA_HOME_URL."/blogs/shikshaBlog/showArticlesList";
	      if(strpos($url, "https") === 0){
		      header("Location: $url",TRUE,301);
		  }
	      exit;
	 }
	
        //Get the domain of the URL. Redirect if it is not wwww
        $parsedURL = parse_url($_SERVER['SCRIPT_URI']);
        if(strpos($parsedURL['host'],'www')===false && REDIRECT_URL=='live' && $subCatId==''){
                $urlEntered = $_SERVER['SCRIPT_URI'];
                if($_SERVER['QUERY_STRING']!='' && $_SERVER['QUERY_STRING']!=NULL){
                        $urlEntered = $urlEntered."?".$_SERVER['QUERY_STRING'];
                }
                $urlEntered = preg_replace( '/[a-z0-9]+\.shiksha/i' , 'www.shiksha' , $urlEntered );
				if( (strpos($urlEntered, "http") === false) || (strpos($urlEntered, "http") != 0) || (strpos($urlEntered, SHIKSHA_HOME) === 0) || (strpos($urlEntered,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($urlEntered,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($urlEntered,ENTERPRISE_HOME) === 0)  ){
					header("Location: $urlEntered",TRUE,301);
				}
				else{
				    header("Location: ".SHIKSHA_HOME,TRUE,301);
				}
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
	
	if(count($subCatArr['subCatArr'])>0){
	    $criteria['subcatArr'] = $subCatArr['subCatArr'];
	}
        $blog_client = new Blog_client();
	
        $articlesList = $blog_client->getArticlesForCriteria($appId, $criteria, $orderBy, $startOffset, $countOffset);
        //$displayData = json_decode($articlesList, true);
	
	if(is_array($articlesList)){
		$displayData = $articlesList[0]['results'];
	}
        $displayData['subcatArr'] = $subCatArr;
	//Check to see if a page with no article displayed does not get created. If the start is greater than the total no. of articles, we will show a 404 page	
	// if(isset($displayData['totalArticles']) && $displayData['totalArticles']>0){
	// 	if($displayData['totalArticles'] < $startOffset){
	// 		show_404();
	// 	}
	// }

        if(
                (!isset($_REQUEST['type']) || empty($_REQUEST['type'])) && (
                    (isset($_REQUEST['country']) && !empty($_REQUEST['country'])) ||
                    (isset($_REQUEST['category']) && !empty($_REQUEST['category']))
                )
          ) {

            $categoryClient = new Category_list_client();
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
	
	$this->load->helper('coursepages/course_page');
	$displayData['isCoursePagesTabsEnabled'] = checkIfCourseTabRequired($criteria['subcat']);
	  if($displayData['isCoursePagesTabsEnabled']) {
	     $CPGSPageNo = ($startOffset / $countOffset) + 1;
		 $coursePagesUrlRequest = $this->load->library('coursepages/CoursePagesUrlRequest');
		 $coursePagesSeoDetails = $coursePagesUrlRequest->getCoursePagesSeoDetails($criteria['subcat'], $CPGSPageNo);
		 $pageSeoDetails = $coursePagesSeoDetails['News'];
		 if(empty($pageSeoDetails)){
			$pageSeoDetails = $coursePagesUrlRequest->getParticularPageSeoDetails($criteria['subcat'], $CPGSPageNo,'News');
		 }
		 
		 $displayData['metaTitle'] = $pageSeoDetails['TITLE'];
		 $displayData['metKeywords'] = $pageSeoDetails['KEYWORDS'];
		 $displayData['metaDescription'] = $pageSeoDetails['DESCRIPTION'];
		 $displayData['canonicalURL'] = $cpgsUrl = $pageSeoDetails['URL'];
		 if($CPGSPageNo > ceil($displayData['totalArticles'] / $countOffset) && $CPGSPageNo != 1) {			
			redirect($coursePagesUrlRequest->getNewsTabUrl($criteria['subcat']), 'location', 301);
		 }		
	     $data['myCanonical'] = trim($cpgsUrl);
	     $pos = strpos($cpgsUrl, "#");
		 if($pos !== false) {
			$len = strlen(substr($cpgsUrl, $pos));			
			$cpgsUrl = substr($cpgsUrl, 0, -$len);
			$data['myCanonical'] = trim($cpgsUrl);
		 }
		 $enteredURL = trim($_SERVER['SCRIPT_URI']);
		 if($enteredURL != $data['myCanonical'] && !empty($data['myCanonical'])) {
			redirect($data['myCanonical'], 'location', 301);
		 }
	  }	
           
	  $validate = $this->checkUserValidation();
	  $displayData['isShowTag'] = $isShowTag;
	  $displayData['validateuser'] = $validate;
	  $displayData['caption'] = $caption;
	  $displayData['startOffset'] = $startOffset;
	  $displayData['countOffset'] = $countOffset;
	  $displayData['subCategoryId'] = $criteria['subcat'];
	  
	  if($type =='news_Articles' && count($subCatArr['subCatArr'])>0){   // this is using for those course's who has not coursepage
		  $script_url = $_SERVER['SCRIPT_URL'];
		  $explode_array = explode("-news-articles", $script_url);
		  $part1 = $explode_array[0];
		  $course_page_url = str_replace($explode_array[1], "", $part1);
		  $course_page_url = rtrim($course_page_url,"-");
		  $course_page_url = $course_page_url."-news-articles";
	 }
	  
	// course page
	  $displayData['tab_required_course_page'] = checkIfCourseTabRequired($criteria['subcat']);
	  $displayData['subcat_id_course_page'] = $criteria['subcat'];
	  if($displayData['tab_required_course_page']) {
		 $displayData['course_pages_tabselected'] = 'News';
	  }
	  $displayData['articlePageType'] = (count($criteria)>0)?$criteria['blogType']:'All';
	  if($type != "news" && (($type !='news_Articles' && $type !='ALL_NEWS_ARTICLES') && (count($subCatArr['subCatArr']) <=0 || empty($subCatArr['subCatArr'])))){
		 $type='blogs/shikshaBlog/showArticlesList';
		 $queryString="/@start@/@count@";
	  }
	  else
 		 $queryString="-@start@";
	  if($_SERVER['QUERY_STRING']!='')
		 $displayData['paginationURL'] = ($type == 'news_Articles' && count($subCatArr['subCatArr'])>0) ? $course_page_url.$queryString .'?'.$_SERVER['QUERY_STRING'] : site_url($type).$queryString.'?'.$_SERVER['QUERY_STRING'];
	  else
	         if($type =='news_Articles' && count($subCatArr['subCatArr'])>0){   // this is using for those course's who has not coursepage
		  $displayData['paginationURL'] = $course_page_url.$queryString;
		 }else if($type =='ALL_NEWS_ARTICLES'){
                        $displayData['paginationURL'] = site_url('news-articles').'/@pageno@';
                 }else{
		  $displayData['paginationURL'] = site_url($type).$queryString; 
	       }
		 
		 
	 if($displayData['tab_required_course_page']) {
		 $script_url = $_SERVER['SCRIPT_URL'];
		 $explode_array = explode("-coursepage", $script_url);
		 $part1 = $explode_array[0];
		 $explode_array1 = explode("-news-articles-", $part1);	
		 $course_page_url = str_replace($explode_array1[1], "", $part1); 
		 $course_page_url = rtrim($course_page_url,"-");
		 $course_page_url = $course_page_url."-@pageno@-coursepage";	
		 if($_SERVER['QUERY_STRING']!='') {
			$displayData['paginationURL'] = $course_page_url."?".$_SERVER['QUERY_STRING'];
		 }else{
			$displayData['paginationURL'] = $course_page_url;
		 }
	  }
   
		if(is_object($coursePagesUrlRequest) && $coursePagesUrlRequest->getDirectoryName($criteria['subcat']) != '') {
			$courseHomePagePaginationUrl = '';
			$courseHomePagePaginationUrl = $coursePagesUrlRequest->getNewsTabUrl($subCatId);
			$displayData['paginationURL'] = $courseHomePagePaginationUrl.'/@pageno@';
			//preparing breadcrumbs
			$breadcrumbOptions = array('generatorType' 	=> 'NewsArticlesPage',
										'options' 		=> array('request'			=>	$coursePagesUrlRequest,
															     'subCategoryId'	=>	$criteria['subcat']));
			$BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);
			$displayData['breadcrumbHtml'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();
		}
		
	  if(!$displayData['isCoursePagesTabsEnabled']) {
		$baseUrl = site_url('blogs/shikshaBlog/showArticlesList');
		if($type=="news"){
	          $baseUrl = site_url('news');
		  $result = createSEOMetaTagsForAuthorProfilePage($startOffset,$countOffset,$baseUrl,$displayData['totalArticles'],'-',$pageNumberToBeDisplayed);
		}else if($type =='news_Articles' && count($subCatArr['subCatArr'])>0){
		  $baseUrl = site_url($course_page_url);
		  $result = createSEOMetaTagsForAuthorProfilePage($startOffset,$countOffset,$baseUrl,$displayData['totalArticles'],'-',$pageNumberToBeDisplayed);	
		}else if($type =='ALL_NEWS_ARTICLES'){
                  $baseUrl = site_url('news-articles');
                  $result = createSEOMetaTagsForAuthorProfilePage($startOffset,$countOffset,$baseUrl,$displayData['totalArticles'],'/',$pageNumberToBeDisplayed);
                }
	        else
		 $result = createSEOMetaTagsForFlavouredAndLatestArticles($startOffset,$countOffset,$baseUrl,$displayData['totalArticles']);
		 $displayData['canonicalURL'] = $result['canonicalURL'];
		if($type !='news' || ($type =='news_Articles' && count($subCatArr['subCatArr'])>0) || $type =='news'){
			$displayData['previousURL'] = $result['previousURL'];
			$displayData['nextURL'] = $result['nextURL'];
		}

		
	       $canonicalurl = $displayData['canonicalURL'];
		 /*Redirection Rule*/
		$enteredURL = $_SERVER['SCRIPT_URI'];
		if($_SERVER['QUERY_STRING']!='') { 
		     $enteredURL=$enteredURL.'?'.$_SERVER['QUERY_STRING'];
		     if($type == 'news') {
		     	$canonicalUrl=$canonicalUrl.'?'.$_SERVER['QUERY_STRING'];
		     }

		}
		
		 if($enteredURL!=$canonicalurl && REDIRECT_URL=='live'){
			if( (strpos($canonicalurl, "http") === false) || (strpos($canonicalurl, "http") != 0) || (strpos($canonicalurl, SHIKSHA_HOME) === 0) || (strpos($canonicalurl,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($canonicalurl,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($canonicalurl,ENTERPRISE_HOME) === 0)  ){
				header("Location: $canonicalurl",TRUE,301);
			}
			else{
			    header("Location: ".SHIKSHA_HOME,TRUE,301);
			}
		  exit;
		 }
	}
	if($pageNumberToBeDisplayed==1) {
		$displayData['pageNumber'] ='Page '.$pageNumber.' - ';
	}
        $displayData['criteria'] = implode('&', $criteriaArray);
		$displayData['blogType']= $type;


		$displayData['trackingpageIdentifier']='articlePage';
		
		$displayData['trackingpageNo']=($startOffset/$countOffset)+1;
		$displayData['trackingtype']=$type;
		
		$displayData['trackingsubCatID']=$displayData['subCategoryId'];
		$displayData['trackingcountryId']=$_REQUEST['country'];
		//load the library to store information in beacon varaible form tracking purpose
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($displayData);

        $this->load->view('blogs/showArticlesList', $displayData);

    }

    function getFlavorArticles($startOffset=0, $countOffset=20 ) {
	if(!is_numeric($startOffset) || !is_numeric($countOffset) || $startOffset<0 || $countOffset <0){
             $url=SHIKSHA_HOME_URL."/blogs/shikshaBlog/getFlavorArticles";
             header("Location: $url",TRUE,301);
             exit;	
	}
        $this->init();
        $appId = 1;
        $criteria = array('startDate'=> date('Y-m-d'));
        $criteria = array();
        $orderBy = 'startDate desc';

        $blog_client = new Blog_client();
        $articlesList = $blog_client->getFlavorArticles($appId, json_encode($criteria), $orderBy, $startOffset, $countOffset);
        $displayData = json_decode($articlesList, true);
        $validate = $this->checkUserValidation();
        $displayData['validateuser'] = $validate;
        $displayData['flavorFlag'] = true;
        $displayData['latestUpdates'] = false;
        $displayData['startOffset'] = $startOffset;
        $displayData['countOffset'] = $countOffset;
	$this->load->helper('blogs/article');
	$baseUrl = site_url('blogs/shikshaBlog/getFlavorArticles');
	$result = createSEOMetaTagsForFlavouredAndLatestArticles($startOffset,$countOffset,$baseUrl,$displayData['totalArticles']);
	$displayData['canonicalURL'] = $result['canonicalURL'];
	$displayData['previousURL'] = $result['previousURL'];
	$displayData['nextURL'] = $result['nextURL'];
	$enteredURL = $_SERVER['SCRIPT_URI'];
        /*Redirection Rule*/
	$canonicalurl = $displayData['canonicalURL'];
	 if($enteredURL!=$canonicalurl && REDIRECT_URL=='live'){
	 		if(strpos($canonicalurl, "https") === 0){
		       header("Location: $canonicalurl",TRUE,301);
		   }
	       exit;
	 }
      
        $displayData['paginationURL'] = site_url('blogs/shikshaBlog/getFlavorArticles')."/@start@/@count@";
        $this->load->view('blogs/showFlavorArticles', $displayData);
    }

    function getAllLatestArticles($startOffset=0, $countOffset=50) {
        if(!is_numeric($startOffset) || !is_numeric($countOffset) || $startOffset<0 || $countOffset <0){
             $url=SHIKSHA_HOME_URL."/blogs/shikshaBlog/getAllLatestArticles";
             header("Location: $url",TRUE,301);
             exit;
        }

        $this->init();
        $appId = 1;

        $blog_client = new Blog_client();
        $articlesList = $blog_client->getAllLatestArticles($appId, $startOffset, $countOffset);
        $displayData = json_decode($articlesList, true);
        $validate = $this->checkUserValidation();
        $displayData['validateuser'] = $validate;
        $displayData['flavorFlag'] = true;
        $displayData['latestUpdates'] = true;
        $displayData['startOffset'] = $startOffset;
        $displayData['countOffset'] = $countOffset;
        $displayData['paginationURL'] = site_url('blogs/shikshaBlog/getAllLatestArticles')."/@start@/@count@";
		$this->load->helper('blogs/article');
		$baseUrl = site_url('blogs/shikshaBlog/getAllLatestArticles');
        $result = createSEOMetaTagsForFlavouredAndLatestArticles($startOffset,$countOffset,$baseUrl,$displayData['totalArticles']);
        $displayData['canonicalURL'] = $result['canonicalURL'];
        $displayData['previousURL'] = $result['previousURL'];
        $displayData['nextURL'] = $result['nextURL'];
		$enteredURL = $_SERVER['SCRIPT_URI'];
        /*Redirection Rule*/
        $canonicalurl = $displayData['canonicalURL'];
        if($enteredURL!=$canonicalurl && REDIRECT_URL=='live'){
		if( (strpos($canonicalurl, "http") === false) || (strpos($canonicalurl, "http") != 0) || (strpos($canonicalurl, SHIKSHA_HOME) === 0) || (strpos($canonicalurl,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($canonicalurl,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($canonicalurl,ENTERPRISE_HOME) === 0)  ){
			header("Location: $canonicalurl",TRUE,301);
		}
		else{
		    header("Location: ".SHIKSHA_HOME,TRUE,301);
		}
              exit;
        }	
        $this->load->view('blogs/showFlavorArticles', $displayData);
   }
	
   function getUserBlogs($displayName,$startOffset=0, $countOffset=10){
   	$articleSecurity = $this->load->library('articleSecurity');
   	$articleSecurity->checkIntegerParameters(array($startOffset,$countOffset));

    if($displayName == 'Ipsita Sarkar Gupta85391'){
           $displayName = 'Ipsita Sarkar Gupta';
    }


	

       
	//Check if the Page URL is fine. If not redirect it to Correct page
	if($startOffset==0 && $countOffset==10){
		$correctURL = SHIKSHA_HOME."/author/".$displayName; 
	}
	else{
		$correctURL = SHIKSHA_HOME."/author/$displayName/$startOffset/$countOffset";
	}
		$currentPageUrl = $this->getPageUrl();
        if($correctURL != $currentPageUrl){
              header("Location: $correctURL",TRUE,301);
              exit;
        }
	//Code end for Checking correct URL

        $displayName=isset($_REQUEST['displayName']) ? $_REQUEST['displayName'] : $displayName;
	$expString = explode('-',$displayName);  //explode the displayname
	
	$count=count($expString); 	 //count length of array
	if($count!=1)$countIndex=$count-1;
	//take the last digit from thr url for page number
	$displayName = $expString[0];
        $pageNumberToBeDisplayed=($expString[$countIndex]=='')?0:1; 
        if($expString[$countIndex]=='' || $expString[$countIndex]<=0 ||  !is_numeric($startOffset)){
	     $startOffset=0;
	     $expString[$countIndex]=1;
	}
	 $startOffset = ($expString[$countIndex]-1)*$countOffset; 
         $pageNumber=$startOffset/$countOffset+1;
        
        $this->init();
	    $this->load->library('messageBoard/AnAConfig');
        $this->load->library('register_client');
	    $this->load->helper('blogs/article');
        $appId = 1;
        $author_details_array = AnAConfig::$author_details_array;
        $displayNamePage=$displayName;
        $displayName = "'".$displayName."'";
        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
        $registerClient = new Register_client();
        $userDetails = $registerClient->getDetailsforDisplayname($appId,$displayName);
	 if(is_array($userDetails) && count($userDetails)>0)
	 {
	    $viewedUserId = isset($userDetails[0]['userid'])?$userDetails[0]['userid']:0;
	    foreach($author_details_array as $authorId=>$authorData){
		  if($authorId==$viewedUserId){
			  $aboutMeText=$authorData['about_me_education'];
			  $aboutMeText=$authorData['about_me_current_position'];
			  $authorIdUser=$viewedUserId;
			  $authorDataUser = $authorData;
		  }
	    }
	    $lastTimeStamp = date('Y-m-d H:i:s');
	    $msgbrdClient = new Message_board_client();
	    $userInfoDetails = $msgbrdClient->getUserProfileDetails($appId,$viewedUserId);
	    if(is_array($userInfoDetails)){
		  $displayData['isValidUser'] = true;
	    }
       }
       else{

       		//check display name exist in sanitizeDisplayName
			$this->load->model('LDBCommonmodel');
		    $commonModel = new LDBCommonModel();
		    $userData  = $commonModel->getOldDisplayName($displayNamePage);	
		    if(isset($userData['userId']) && $userData['userId'] > 0){
		    	$newDisplayName = $commonModel->getDisplayNameByUserId($userData['userId']);
		    	if(!empty($newDisplayName)){
		        	$url=SHIKSHA_HOME."/author/".$newDisplayName; 
		            header("Location: $url",TRUE,301);
		            exit;        		
		    	}
		    }

                //If user is not found, redirect to Homepage
                $displayData['isValidUser'] = false;
                $url=SHIKSHA_HOME_URL;
                header("Location: $url",TRUE,301);
                exit;
       }
        $blog_client = new Blog_client();
        $articlesList = $blog_client->getUserBlogs($appId, $viewedUserId,$startOffset, $countOffset);
        $displayData = json_decode($articlesList, true);
        $validate = $this->checkUserValidation();
        $displayData['validateuser'] = $validate;
        $displayData['startOffset'] = $startOffset;
        $displayData['countOffset'] = $countOffset;
        $displayData['articlesList'] = $articlesList;
	if($pageNumberToBeDisplayed==1)
	 $displayData['pageNumber'] ='Page '.$pageNumber.' - ';
	 $displayData['paginationURL'] = site_url('/author')."/$displayNamePage-@start@";
	 $baseUrl = site_url('/author/'.$displayNamePage);
	 $result = createSEOMetaTagsForAuthorProfilePage($startOffset,$countOffset,$baseUrl,$displayData['totalArticles'],'-',$pageNumberToBeDisplayed);
	 $displayData['canonicalURL'] = $result['canonicalURL'];
        //$displayData['previousURL'] = $result['previousURL'];
        //$displayData['nextURL'] = $result['nextURL'];
	 
	 $enteredURL = $this->getPageUrl();
	 $canonicalurl = $displayData['canonicalURL'];
	 $maxPaginationCount = ceil( $displayData['totalArticles']/$countOffset);
         $filteredEnteredURL = array_shift(explode('-', $enteredURL));
         if(($filteredEnteredURL!=$canonicalurl || $pageNumber>$maxPaginationCount) && REDIRECT_URL=='live'){
              header("Location: $canonicalurl",TRUE,301);
              exit;
         }
         $userInfoDetails = $msgbrdClient->getUserProfileDetails($appId,$viewedUserId);
	
	    if(is_array($userInfoDetails)){
		  $vcardUserDetails = $userInfoDetails[0]['VCardDetails'];
		     if(isset($vcardUserDetails[0]['firstname'])){
			   if(isset($vcardUserDetails[0]['lastname']))
			      $vcardUserDetails[0]['userName'] = $vcardUserDetails[0]['firstname'].' '.$vcardUserDetails[0]['lastname'];
		     }
		     $userStatus = getUserStatus($vcardUserDetails[0]['lastlogintime']);
		     $userProfile = site_url('getUserProfile').'/'.$vcardUserDetails[0]['displayname'];
		     $vcardUserDetails[0]['userStatus'] = $userStatus;
		     $vcardUserDetails[0]['userProfile'] = $userProfile;
		     $displayData['userDetailsArray'] = $vcardUserDetails;
		     $displayData['viewedDisplayName'] = $displayNamePage;
		     $displayData['viewedUserName'] = $displayNamePage;
		     $displayData['firstname']= $vcardUserDetails[0]['firstname'];
	    }
	    
	    $vcardArray = array();
	    array_push($vcardArray,array($vcardUserDetails,'struct'));
	    $displayData['userDetailsArray'] = $vcardArray;
	    
	    if(isset($vcardArray) && is_array($vcardArray))
	       if($vcardArray[0][0][0]['userName']!=' ')
		  	$displayData['viewedUserName'] = $vcardArray[0][0][0]['userName'];
		    $displayData['start'] = $start;
		    $displayData['rows'] = $rows;
			$displayData['authorData'] = $authorDataUser;
			$displayData['authorId'] = $authorIdUser;

			//below code used for beacon tracking
	        $displayData['trackingpageIdentifier'] = 'articleAuthorProfilePage';
	        $displayData['trackingcountryId']=2;


	        //loading library to use store beacon traffic inforamtion
	        $this->tracking=$this->load->library('common/trackingpages');
	        $this->tracking->_pagetracking($displayData);

        	$this->load->view('blogs/showUserBlogArticles', $displayData);
   }

   /**
    * Must read articles page for users landing from personalized mailers
    */ 	
   function mustReadArticles($mailerId,$topArticles,$startOffset=0, $countOffset=50)
   {
	  $this->init();
	  $this->load->library('mailer/MailerFactory');
	  
	  /**
	   * Get logged-in user
	   */ 
	  $validate = $this->checkUserValidation();
	  if(!is_array($validate) || !is_array($validate[0]) || !$validate[0]['userid']) {
		 header('Location: /blogs/shikshaBlog/showArticlesList');
		 exit();
      }
 	 
	  $userId = $validate[0]['userid'];
	  
	  $mailerRepository = MailerFactory::getMailerRepository();
	  $mailer = $mailerRepository->find($mailerId);
	  
	  $mustReadWidget = MailerFactory::getWidgetObj('mustread');
	  $mustReadWidget->setMailer($mailer);
	  $mustReadWidget->setNumArticles(1000000);
	  $mustReadArticles = $mustReadWidget->getArticles(array($userId));
	  
	  /**
	   * Top articles i.e. articles sent in mailer will always be on top
	   */ 
	  $articleIds = $topArticles ? explode(',',$topArticles) : array();
	  foreach($mustReadArticles[$userId] as $article) {
		 if(!in_array($article['blogId'],$articleIds)) {
			$articleIds[] = $article['blogId'];
		 }
	  }
	  
	  $totalArticles = count($articleIds);
	  
	if(!$totalArticles) {
                 header('Location: /blogs/shikshaBlog/showArticlesList');
                 exit();
      }

	  /**
	   * paginate
	   */ 
	  $articleIds = array_slice($articleIds,$startOffset,$countOffset);
	  
	  /**
	   * Fetch data for articles on page
	   */ 
	  $this->load->model('blogs/articlemodel');
	  $articles = $this->articlemodel->getArticlesData($articleIds,FALSE);
	  
	  $indexedArticles = array();
	  foreach($articles as $article) {
		 $indexedArticles[$article['blogId']] = $article;
	  }
	  
	  $sortedArticles = array();
	  foreach($articleIds as $articleId) {
		 $sortedArticles[] = $indexedArticles[$articleId];
	  }
	  
	  $displayData = array();
	  $displayData['articles'] = $sortedArticles;
	  $displayData['startOffset'] = $startOffset;
	  $displayData['countOffset'] = $countOffset;
	  $displayData['paginationURL'] = site_url('blogs/shikshaBlog/mustReadArticles/'.$mailerId.'/'.$topArticles)."/@start@/@count@";;
	  $displayData['totalArticles'] = $totalArticles;
      $displayData['validateuser'] = $validate;
	  $displayData['flavorFlag'] = false;
      $displayData['latestUpdates'] = false;
	  $displayData['mustReadFlag'] = true;
	
	  $this->load->view('blogs/showFlavorArticles', $displayData);
   }

    //Function to show the CAT result in an iFrame
    function openLink($url=''){
      header('location:'.SHIKSHA_HOME.'/cat-results-exampage',301);die;
      $validate = $this->checkUserValidation();
      $displayData = array();
      $displayData['validateuser'] = $validate;
      $this->load->view('blogs/CATResultsPage', $displayData);
    }
	
	 //Function to show the CAT result in an iFrame
    function SNAPResultsPage($url=''){
      header('location:'.SHIKSHA_HOME.'/snap-results-exampage',301);die;
	  $validate = $this->checkUserValidation();
	  $displayData = array();
        $displayData['validateuser'] = $validate;
	$this->load->view('blogs/SNAPResultsPage', $displayData);
    }
	
	function IBSATResultsPage($url=''){
	 header('location:'.SHIKSHA_HOME.'/ibsat-results-exampage',301);die;
	  $validate = $this->checkUserValidation();
	  $displayData = array();
        $displayData['validateuser'] = $validate;
	$this->load->view('blogs/IBSATResultsPage', $displayData);
    }
	
   function XATResultsPage($url=''){
      header('location:'.SHIKSHA_HOME.'/xat-results-exampage',301);die;
	  $validate = $this->checkUserValidation();
	  $displayData = array();
        $displayData['validateuser'] = $validate;
	$this->load->view('blogs/XATResultsPage', $displayData);
    }
	
	function NMATResultsPage($url=''){
	 header('location:'.SHIKSHA_HOME.'/nmat-results-exampage',301);die;
	  $validate = $this->checkUserValidation();
	  $displayData = array();
        $displayData['validateuser'] = $validate;
	$this->load->view('blogs/NMATResultsPage', $displayData);
    }
	
	function MATResultsPage($url=''){
	 header('location:'.SHIKSHA_HOME.'/mat-results-exampage',301);die;
	  $validate = $this->checkUserValidation();
	  $displayData = array();
        $displayData['validateuser'] = $validate;
	$this->load->view('blogs/MATResultsPage', $displayData);
    }
	
	function MICATResultsPage($url=''){
	  $validate = $this->checkUserValidation();
	  $displayData = array();
        $displayData['validateuser'] = $validate;
	$this->load->view('blogs/MICATResultsPage', $displayData);
    }
	
	function CBSEResultsPage($url=''){
	  $validate = $this->checkUserValidation();
	  $displayData = array();
      $displayData['validateuser'] = $validate;
	  $this->load->view('blogs/CBSEResultsPage', $displayData);
    }

   function CMATResultsPage($url='')
   {
      header('location:'.SHIKSHA_HOME.'/cmat-results-exampage',301);die;
	  $validate = $this->checkUserValidation();
	  $displayData = array();
      $displayData['validateuser'] = $validate;
	  $this->load->view('blogs/CMATResultsPage', $displayData);
   }		
	
    function LDBWidget($abroad=0,$trackingPageKeyId=''){
            $referrer = $_SERVER['HTTP_REFERER'];
            if($abroad==0){
                echo Modules::run('registration/Forms/LDB',NULL,'findInstitute',array('registrationSource' => 'ARTICLE_PAGE_LDB_INDIA_FORM','referrer' => $referrer,'trackingPageKeyId'=>$trackingPageKeyId));
            }
    }

   function showEngineeringExams($examName = '', $startOffset = 0) {
	 $startOffset = trim($startOffset,"-");
	 $countOffset = 20;
	 $pageNumberToBeDisplayed=($startOffset=="")?0:1;
	 $startOffset=($startOffset=="" || $startOffset<=0 )?0:$startOffset;
       
	 $pageNumber=$startOffset;
	 if($startOffset != 0){
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

         //If no articles are found on this page, redirect the user to the Base page
         if(count($articlesList['articles'])<=0 && $startOffset>0){
              header("Location: $url",TRUE,301);
              exit;
         }

	 $caption = strtoupper($examProperName);
	
	 $this->load->helper('coursepages/course_page');
	 $displayData['isCoursePagesTabsEnabled'] = checkIfCourseTabRequired($criteria['subcat']);
	 if($displayData['isCoursePagesTabsEnabled']) {
	        $CPGSPageNo = ($startOffset / $countOffset) + 1;
		$coursePagesUrlRequest = $this->load->library('coursepages/CoursePagesUrlRequest');
		$coursePagesSeoDetails = $coursePagesUrlRequest->getCoursePagesSeoDetails($criteria['subcat'], $CPGSPageNo);
		$displayData['metaTitle'] = $coursePagesSeoDetails['News']['TITLE'];
		$displayData['metKeywords'] = $coursePagesSeoDetails['News']['KEYWORDS'];
		$displayData['metaDescription'] = $coursePagesSeoDetails['News']['DESCRIPTION'];
		$displayData['canonicalurl'] = $cpgsUrl = $coursePagesSeoDetails['News']['URL'];
	       //if($CPGSPageNo > ($displayData['totalArticles'] / $countOffset) && $CPGSPageNo != 1) {			
		//	redirect($coursePagesUrlRequest->getNewsTabUrl($criteria['subcat']), 'location', 301);
		//}		

	       $data['myCanonical'] = trim($cpgsUrl);
	       $pos = strpos($cpgsUrl, "#");
		if($pos !== false) {
			$len = strlen(substr($cpgsUrl, $pos));			
			$cpgsUrl = substr($cpgsUrl, 0, -$len);
			$data['myCanonical'] = trim($cpgsUrl);
		}
		
	 }	

	 $validate = $this->checkUserValidation();
	 $displayData['validateuser'] = $validate;
	 $displayData['caption'] = $caption;
	 $displayData['startOffset'] = $startOffset;
	 $displayData['countOffset'] = $countOffset;
	 $displayData['subCategoryId'] = $criteria['subcat'];

	 // course page
	 $displayData['tab_required_course_page'] = checkIfCourseTabRequired($criteria['subcat']);
	 $displayData['subcat_id_course_page'] = $criteria['subcat'];
	 if($displayData['tab_required_course_page']) {
		 $displayData['course_pages_tabselected'] = 'Exams';
	 }
	 $displayData['articlePageType'] = (count($criteria)>0)?$criteria['blogType']:'All';
	 $queryString="-@start@";
	 $displayData['paginationURL'] = $url.$queryString;
	

	/*if($displayData['tab_required_course_page']) {
		
		$script_url = $_SERVER['SCRIPT_URL'];
		$explode_array = explode("-coursepage", $script_url);
		$part1 = $explode_array[0];
		$explode_array1 = explode("-news-articles-", $part1);	
		$course_page_url = str_replace($explode_array1[1], "", $part1); 
		$course_page_url = rtrim($course_page_url,"-");
		$course_page_url = $course_page_url."-@pageno@-coursepage";	
		
		if($_SERVER['QUERY_STRING']!='') {
			$displayData['paginationURL'] = $course_page_url."?".$_SERVER['QUERY_STRING'];
		} else {
			//echo $course_page_url;
			$displayData['paginationURL'] = $course_page_url;
		}
	}*/
	
	 $displayData['metaTitle'] = $engineeringExams[$examName]['name']." - Notification, Syllabus, Study Material, Question Papers, Results, News";
	 $displayData['metaDescription'] = "All about ".$engineeringExams[$examName]['name']." exam like syllabus, cut offs, results, important dates, study materials, preparation guide, questions papers, and more.";
	 $displayData['metKeywords'] = "";
	 $displayData['doNotShowKeywords'] = "true";
	 if($pageNumber>1){
	    $canonicalURL = $engineeringExams[$examName]['url'].'-'.$pageNumber;
	    $displayData['metaTitle'] = 'Page '.$pageNumber.' - '.$displayData['metaTitle'];
	    $displayData['metaDescription'] = 'Page '.$pageNumber.' - '.$displayData['metaDescription'];
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
	 //$displayData['blogType']= "news";
	 $displayData['engineeringExamPage'] = 'true';
         $this->load->view('blogs/showArticlesList', $displayData);
   }

   /**
    * Purpose       : Get data for authors profilepage from Backend
    * Params        : none
    * Author        : Pragya Gupta
   */
   function getAuthorProfilePage(){
	 $this->init();
	 $userStatus = $this->checkUserValidation();
	 $this->load->library('messageBoard/AnAConfig');
	 $author_details_array = AnAConfig::$author_details_array;
	 foreach($author_details_array as $key=>$value) {
	       $userIds = ($userIds=='')?$key:$userIds.','.$key;
	 } 
	 $this->load->model('articlemodel');
	 $displayData['author_user_info'] = $this->articlemodel->getAuthorInfo($userIds);
	 $displayData['author_details_array'] = $author_details_array;
	 $displayData['validateuser'] = $userStatus;
     //below code used for beacon tracking
     $displayData['trackingpageIdentifier'] = 'shikshaAuthorsProfilePage';
     $displayData['trackingcountryId']=2;

    $this->benchmark->mark('dfp_data_start');
    $dfpObj   = $this->load->library('common/DFPLib');
    $dpfParam = array('parentPage'=>'DFP_AuthorProfile');
    $displayData['dfpData']  = $dfpObj->getDFPData($userStatus, $dpfParam);
    $this->benchmark->mark('dfp_data_end');

    //loading library to use store beacon traffic inforamtion
    $this->tracking=$this->load->library('common/trackingpages');
    $this->tracking->_pagetracking($displayData);
	$this->load->view('blogs/authorProfiles',$displayData);
   }

   public function updateArticleURLs($limit = 5000){
         $this->load->model('articlemodel');
         $this->articlemodel->updateArticleURLs($limit);
         echo "DONE";
   }
   
    /**
    * Purpose       : get new articles and news pages for 7 category
    * Params        : none
    * Author        : akhter
   */
   public function newsAndArticlePages($UrlKey, $pageNo = '')
   {    // redirect 301 all article page
   		$artObj = $this->load->library('article/articleRedirectionLib');
        $artObj->redirectFromCoursePage($UrlKey,$pageNo);
   }
   
   function parseArticleCategory($urlKey)
   {  	global $ARTICLES_CATEGORY_NAME_ARRAY;
	 foreach($ARTICLES_CATEGORY_NAME_ARRAY as $catID => $infoArray) {
	      if($urlKey == $ARTICLES_CATEGORY_NAME_ARRAY[$catID]['UrlKey']) {
		   return $catID;
	    }
	 }
      return -1;
   }
   
   function getSubCateForArticle($catId){
       
         if($catId == 3 || $catId == 2){
	       global $ARTICLES_CAT_SUBCAT_NAME_ARRAY;
	       $subcatList = $ARTICLES_CAT_SUBCAT_NAME_ARRAY[$catId];
		  foreach($subcatList as $subCatName=>$subCat)
		  {
		     $list['subCatArr'][] = $subCat;
		  }
		  $list['catId'] = $catId;
	 }else{
	       $this->load->builder('CategoryBuilder','categoryList');
	       $categoryBuilder = new CategoryBuilder;
	       $categoryRepository = $categoryBuilder->getCategoryRepository();
	       $subcatList = $categoryRepository->getSubCategories($catId);
	       if(count($subcatList)>0){
		  foreach($subcatList as $catObj)
		  {
		     $list['subCatArr'][] = $catObj->getId();
		  }
		  $list['catId'] = $catId;
	       }
	 }
	 return $list;
   }

   function getCategorySpecificNewsArticlesPage($categoryId, $subCatId){

   		if($categoryId == 2 && $subCatId == 56){
			$newsArticlesPageUrl = SHIKSHA_HOME.'/engineering-news-articles-coursepage';
		} 
		else if($categoryId == 3 && $subCatId == 23){
			$newsArticlesPageUrl = SHIKSHA_HOME.'/mba/resources/mba-news-articles';
		} 
		else {
			$UrlKey = $ARTICLES_CATEGORY_NAME_ARRAY[$categoryId]['UrlKey'];
			$newsArticlesPageUrl = SHIKSHA_HOME.'/'.$UrlKey.'-news-articles';
		}
		return $newsArticlesPageUrl;
   }

    function getStreamSpecificNewsArticlesPage($streamId, $streamName){
   		if(!empty($streamId) && !empty($streamName)){
   			$newsArticlesPageUrl = SHIKSHA_HOME.'/'.strtolower(seo_url($streamName, "-", 30)).'/articles-st-'.$streamId;
   		}
		return $newsArticlesPageUrl;
    }

   function redirect301($pageNo = ''){
   		$param['pageNo']  = $pageNo;
   		$param['urlStr'] =  'default';
   		$artObj = $this->load->library('article/articleRedirectionLib');
		$artObj->redirectDefault($param);
   }

/*
Desc     : 	addArticleUrl() function is used to get url and add in blogTable newUrl coloum. While displaying articles, we get the URL straight from DB and do not call this function. newUrl will be rename with url once we go live with new recat.
$param   :  type array
$courseId:  list of array 		
Author   :  akhter
*/
   function addArticleUrl($param){
		$articleUrlLib = $this->load->library('common/UrlLib',$param);
		$articleUrl    = $articleUrlLib->getUrl();
		$this->load->model('articlemodel');
		$this->articlemodel->updateNewArticleUrl($param['articleId'], $articleUrl);
   }

   function getBlogStatus($blogId){
   		$this->load->model('articlemodel');
   		return $this->articlemodel->getBlogStatus($blogId);
   }

   function articleMigrateData($blogIdArr){
   		// step 1- get subcat and ldb couse id for all articles
   		$res = $this->article->getArticleOldMapping($blogIdArr); 
   		// step 2- get categoryid of each subcategory
   		$this->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $categoryRepository = $categoryBuilder->getCategoryRepository();

        $this->load->builder('listingBase/ListingBaseBuilder');
        $listingObj = new ListingBaseBuilder();
        $repObj     = $listingObj->getHierarchyRepository();

        $this->load->library('common/UrlLib');

        foreach ($res as $key => $value) {
        	$obj = $categoryRepository->find($value['boardId']); // subCatId
       		$param = array(
       						'categoryId'    => $obj->getParentId(),
       						'subCategoryId' => $value['boardId'],
       						'ldbCourseId'   => (isset($value['ldbCourseId']) && $value['ldbCourseId']!='')?$value['ldbCourseId']:0
       			    );

       		// step 3- get new mapping based on these params for each article
       		$mappingData = $this->article->getNewMappingFromOldMapping($param);

    		$streamId = $mappingData[0]['stream_id'];
			
			$substreamId = $mappingData[0]['substream_id'];
			if($substreamId == 0){
				$substreamId = 'none';
			}

			$specializationId = $mappingData[0]['specialization_id'];
			if($specializationId == 0){
				$specializationId = 'none';
			}

 			$hierarchyType = 'hierarchy';
			// step 4- create url
 			if(!in_array($value['blogId'], $primaryArr)){
 				$hierarchyType = 'primaryHierarchy';
		 		$urlParam = array(); // this variable is used for creating article URL
	  			$urlParam['articleId']    = $value['blogId'];
	  			$urlParam['title']        = $value['blogTitle'];
	  			$urlParam['courseId']     = $mappingData[0]['base_course_id'];
				$urlParam['stream_id']    = $streamId;
				$urlParam['substream_id'] = $substreamId;
		 		$articleUrlLib = new UrlLib($urlParam);
				$articleUrl    = $articleUrlLib->getUrl();
				echo $articleUrl.'<br>';
				//$this->article->updateNewArticleUrl($urlParam['articleId'], $articleUrl); // do not delete updateNewArticleUrl function from model
 				$primaryArr[]  = $value['blogId'];
 			}

 			$hierarchyId = '';
	 		if(!empty($streamId) && $streamId>0){
	 			$hierarchyId = $repObj->getHierarchyIdByBaseEntities($streamId,$substreamId,$specializationId,'array');
	       		$hierarchyId = $hierarchyId[0];
	 		}
	 	
            //step 5- prepare data for new mapping
			$baseCourse     = $mappingData[0]['base_course_id'];
			$education_type = $mappingData[0]['education_type'];
			$delivery_method = $mappingData[0]['delivery_method'];
			$rowData = array();
			
				if(!empty($hierarchyId) && $hierarchyId>0){
        			$rowData['articleId']  = $value['blogId'];
					$rowData['entityId']   = $hierarchyId;
					$rowData['entityType'] = $hierarchyType;
					$rowData['status']     = $value['status'];
					$rowData['creationDate']     = $value['creationDate'];
					$rowData['modificationDate'] = $value['lastModifiedDate'];
					$finalQryData[] = $rowData;
        		}
        		if(!empty($baseCourse) && $baseCourse>0){
        			$rowData['articleId']  = $value['blogId'];
					$rowData['entityId']   = $baseCourse;
					$rowData['entityType'] = 'course';
					$rowData['status']     = $value['status'];
					$rowData['creationDate']     = $value['creationDate'];
					$rowData['modificationDate'] = $value['lastModifiedDate'];
					$finalQryData[] = $rowData;	
        		}
        		if(!empty($education_type) && $education_type>0){
        			$rowData['articleId']  = $value['blogId'];
					$rowData['entityId']   = $education_type;
					$rowData['entityType'] = 'otherAttribute';
					$rowData['status']     = $value['status'];
					$rowData['creationDate']     = $value['creationDate'];
					$rowData['modificationDate'] = $value['lastModifiedDate'];
					$finalQryData[] = $rowData;	
        		}
        		if(!empty($delivery_method) && $delivery_method>0){
        			$rowData['articleId']  = $value['blogId'];
					$rowData['entityId']   = $delivery_method;
					$rowData['entityType'] = 'otherAttribute';
					$rowData['status']     = $value['status'];
					$rowData['creationDate']     = $value['creationDate'];
					$rowData['modificationDate'] = $value['lastModifiedDate'];
					$finalQryData[] = $rowData;	
        		}
        }
        //insert mapping
       // $this->article->mapArticleToAttributesAndHierarchy($finalQryData); // Do not delete this mapArticleToAttributesAndHierarchy() from model
   }

   function articleTagMigrate(){
		$this->load->model('articlemodel','article');
   		// step 1- get all blogId, which article has tags like test prep
   		$blogId = $this->article->getArticleHavingTag();    	
   		foreach ($blogId as $key => $value) {
   			$blogIdArr[] = $value['blogId'];
   		}
   		if(count($blogIdArr)>0){
   			// mapped test prep to change blogType in testPrep
   			$res = $this->article->updateArticleType($blogIdArr);
   			if($res){
   				echo 'Article tag has been mapped successfully.';
   			}
   		}
   }

   //article migration script. run only one time once it has done then will remove it.
   //added by - akhter
   function articleCategoryMigrate(){
        ini_set('memory_limit','2000M');
        $this->load->model('articlemodel','article');
        $articleIdArr = $this->article->getTotalArticle();
        $totalArticle = count($articleIdArr);

        foreach ($articleIdArr as $key => $blogId) {
                $blogIdArr[] = $blogId['blogId'];
        }

        $num = $totalArticle;
        $divideby = 5000;
        $remainder=$num % $divideby;
        $number=explode('.',($num / $divideby));
        $answer=$number[0];
        $pages = ceil($num/$divideby);
        $pages = $pages - 1;
                for($i=0; $i <= $answer ; $i++){
                        $start = $i*$divideby;
                        if($i == $pages){
                                $blogIds = array_slice($blogIdArr, $start, $remainder);
                                $limit =  " limit $start , $remainder"; //limit 0,5000
                                echo $limit.'<br>';
                                $this->articleMigrateData($blogIds);
                        }else{
                                $blogIds = array_slice($blogIdArr, $start, 5000);
                                $limit = " limit $start , 5000";
                                echo $limit.'<br>';
                                $this->articleMigrateData($blogIds);
                        }
                }
   }

        public function updateArticleContentURL($blogId = 0){
                $this->load->model('articlemodel');
                $this->articlemodel->updateArticleContentURL($blogId);
        }


   // one time script
   function updateArticleContent(){
   	 	$contentObj = $this->load->library('common/httpContent');
   	 	
   	 	$tableName = 'blogDescriptions';
   	 	$primaryColumnName = 'descriptionId';
   	 	$contentColumnName = 'description';
   	 	$status = array();
   	 	
   	 	$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status);
   }

   function getPageUrl(){
   		$url = getCurrentPageURL();
   		$entities = array('%20');
    	$replacements = array(' ');
    	return str_replace($entities, $replacements, $url);
	}
	
    function getFirstImageFromHTML($html){
    	$dom = new DomDocument();
    	$dom->loadHTML($html);
    	$image = '';
    	foreach ($dom->getElementsByTagName('img') as $item) {
    		$image = $item->getAttribute('src');
    		break;
    	}
    	return $image;
    }	

    function saveArticleWikiContent() {
    	
    	$wiki = $this->input->post('wiki');
    	$blogId = $this->input->post('blogId');
		$this->load->model('articlemodel');
		$this->articlemodel->updateArticleWikiContent($blogId, $wiki);

    }
}
?>
