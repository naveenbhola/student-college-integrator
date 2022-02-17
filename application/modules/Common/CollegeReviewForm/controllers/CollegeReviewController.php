<?php
/*

   Copyright 2015-16 Info Edge India Ltd

   $Author: Ankur Gupta

   $Id: College Review Controller

 */

class CollegeReviewController extends MX_Controller
{

        function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array(  $helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();

		$this->load->helper('coursepages/course_page');
		
		$this->load->model('collegereviewmodel');
		$this->crmodel = new CollegeReviewModel();	

		$this->load->library('CollegeReviewLib'); 
		$this->CollegeReviewLib = new CollegeReviewLib();
		
	}

	/**
	 *
	 * Show College review mobile Homapage
	 *
	 * @param    None
	 * @return   View with the Homepage
	 *
	 */
	function collegeReviewsHomepage($pageNo = 1, $stream = '', $baseCourse = '', $educationType = '', $substream = ''){
		ini_set("memory_limit","200M");
		if(is_numeric($pageNo) ==false){
			show_404();
			return;
		}
		$this->init();
		Modules::run('common/Redirection/validateRedirection',array('pageName'=>'collegeReviews','oldUrl'=>"mba-colleges-reviews-cr",'oldDomainName'=>array(SHIKSHA_MANAGEMENT_HOME),'newUrl'=>SHIKSHA_HOME.'/mba/resources/college-reviews','redirectRule'=>301));
		$appId = 12;
		
		global $engineeringtStreamMR;
		global $mbaBaseCourse;
		global $managementStreamMR;
        global $btechBaseCourse;
		global $defaultSubstream;
		global $fullTimeEdType;

        
		if($stream == ''){
			$stream = $managementStreamMR;
		}

		if($baseCourse == '' && $stream == ''){
        	$baseCourse = $mbaBaseCourse;
		}

        if($stream == $engineeringtStreamMR){
            $baseCourse = $btechBaseCourse;
        }

        if($educationType == ''){
        	$educationType = $fullTimeEdType;
        }

		if($substream == ''){
			$substream = $defaultSubstream;
		}

		
		$displayData = array();  

		$reviewURL = "";
		$pageFor = "MBA";
		
		if($stream == $managementStreamMR){
			$reviewURL = SHIKSHA_HOME."/".MBA_COLLEGE_REVIEW;
			$pageFor = "MBA";
			$displayData['revURL'] = SHIKSHA_HOME.'/management/resources/reviews/';

		} else if($stream == $engineeringtStreamMR){
			$reviewURL = SHIKSHA_HOME."/".ENGINEERING_COLLEGE_REVIEW;
			$pageFor = "B.Tech";
			$displayData['revURL'] = SHIKSHA_HOME.'/engineering/resources/reviews/';
       	
		}
		
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['sessionId'] = sessionId();
		
		//Get User Session Data
		$userSessionData = $this->getUserSessionData($displayData['userId'], $displayData['sessionId']);
		if(is_array($userSessionData)){
			$displayData['userSessionData'] = $userSessionData;
		}
		
		// Get Tile Data
		$tileData = $this->getTileData($stream);

		if(is_array($tileData) && count($tileData)>0 ){
			$displayData['primaryTile'] = $tileData['top'];
			$displayData['secondaryTile'] = $tileData['bottom'];			
		}

		// Get Reviews Data
		$start = ($pageNo>1)? (($pageNo-1)*10) : 0;
		$count = 10;
		$orderOfReview = (isset($_COOKIE['collegeReviewOrder']) && $_COOKIE['collegeReviewOrder']!='')?$_COOKIE['collegeReviewOrder']:'latest';

		$reviewData = $this->getReviewsForHomepage($start, $count,$stream,'homepage',$orderOfReview,$baseCourse,$educationType,$substream);

		if(count($reviewData['results'])<=0){
			$body = "Details :<br/> Order of review : ".$orderOfReview."<br/>";
			$body .= "Page No. = ".$pageNo."<br/> Stream : ".$stream."<br/> BaseCourse :".$baseCourse."<br/> EducationType".$educationType."</br> Substream : ".$substream."<br/>";
			$body .= "$ SERVER : ".print_r($_SERVER,true)."<br/> Visitor Session Id: ".getVisitorSessionId()."<br/> POst Data : ".print_r($_POST, true)."<br/> Get Data".print_r($_GET,true) ;
			mail("pranjul.raizada@shiksha.com,abhinav.pandey@shiksha.com","College Review : Zero result page.",$body);
		}

		if(is_array($reviewData) && count($reviewData)>0 ){
			$displayData['reviewData'] = $reviewData;
		}

        $displayData['course_pages_tabselected'] = '';
		$displayData['pageNo'] = $pageNo;
		$displayData['orderOfReview'] = $orderOfReview;

		//Google Remarketing Code
		$displayData['gtmParams'] = array(
                        "pageType"    => 'collegeReviewPage',
                        "stream"    => $stream
                );

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_AllCollegeReview','entity_id'=>$stream);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        //Get the Total number of Reviews from Backend
        $totalReviewCount = $reviewData['totalReviewCount'];

        $updatedReviewCount = $this->CollegeReviewLib->checkReviewCount($totalReviewCount);
        $displayData['totalReviewCount'] = $updatedReviewCount;
		
		//Redirect to shiksha Domain               

        if('https://'.$_SERVER['HTTP_HOST']==SHIKSHA_SCIENCE_HOME){
			$url = SHIKSHA_HOME."/".ENGINEERING_COLLEGE_REVIEW;
                        header("Location: $url",TRUE,301);
        }        
        
		//If Page no is greater than total review count, then redirect to Homepage
        $totalReviewCountPageCheck = $displayData['reviewData']['totalCollegeCards'];
		if($totalReviewCountPageCheck>0 && (ceil($totalReviewCountPageCheck/10) < $pageNo)){
			$url = $reviewURL;
			header("Location: ".SHIKSHA_HOME,TRUE,301);
            exit;
		}

        //Get the Total number of Colleges having Reviews from Backend
        /* $totalInstituteCount = $this->CollegeReviewLib->getReviewInstituteCount($stream);
        $updatedInstituteCount = $this->CollegeReviewLib->checkReviewInstituteCount($totalInstituteCount);
        $displayData['totalInstituteCount'] = $updatedInstituteCount; */

        //Get SEO Details
        $seo_details = $this->getHomepageSEOData($displayData['reviewData']['totalCollegeCards'], $pageNo,$stream, $count);
        $displayData['m_meta_title'] = $seo_details['m_meta_title'];
        $displayData['m_meta_description'] = $seo_details['m_meta_description'];
        $displayData['canonicalURL'] = $seo_details['canonicalURL'];
        $displayData['previousURL'] = $seo_details['prevURL'];
        $displayData['nextURL'] = $seo_details['nextURL'];
       	$displayData['stream'] = $stream;
       	$displayData['baseCourse'] = $baseCourse;
       	$displayData['substream'] = $substream;
       	$displayData['educationType'] = $educationType;
       	$displayData['pageFor'] = $pageFor;
       	$displayData['view'] = 'default';
       	$displayData['basePageUrl'] = $reviewURL;
       	$displayData['subTitle'] = 'Check out this college review. This might be helpful for you.';

        $displayData['m_meta_keywords'] = $seo_details['m_meta_keywords'];
		//$displayData['showHomePageView'] = $this->getCRHomePageView($subcatId);
		if($stream == $managementStreamMR)
			$paginationURL = $reviewURL.'/@pageNum';
		else
			$paginationURL = $reviewURL.'/@pageNum';
		$displayData['paginationHTML'] = doPaginationCollegeReview($displayData['reviewData']['totalCollegeCards'],$paginationURL,$start,10,10);

		//below code used for beacon tracking
		$this->CollegeReviewLib->prapareBeaconData("collegeReviewPage",$displayData,$stream,$substream,$baseCourse,$educationType);
		//below line is used for conversion tracki purpose
		$displayData['regTrackingPageKeyId']=192;
		$displayData['reviewTrackingPageKeyId']=193;



		//preparing breadcrumbs
		if($baseCourse == 101){
			$breadcrumbOptions = array('generatorType' 	=> 'CollegeReviewsHomePage',
									'options' 		=> array('base_course_id'	=>	$baseCourse,
																'education_type' => $educationType
															));
			$BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);
			$displayData['breadcrumbHtml'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();
		}

		

		$this->load->view('showReviewHomepage',$displayData);
	}

	function showFullReview(){
		$reviewId = isset($_POST['reviewId'])?$this->input->post('reviewId'):'';
		$reviewDetails = array();
		if($reviewId > 0){
			$this->init();
			$reviewDetails = $this->crmodel->getReviewDetailbyReviewId($reviewId);
			$reviewDetails['personalInfo'] = $this->crmodel->getReviewerDetailsByReviewId($reviewId);
		}
	
		echo $this->load->view('reviewDetailLayer',$reviewDetails);
	}
	
	/**
	 *
	 * Show College review Intermediate Page
	 *
	 * @param    Title. This title will decide which data to fetch from Backend
	 * @return   View with the Page
	 *
	 */
	function collegeIntermediatePage($str, $pageNo = 1){
		$this->init();
		$appId = 12;
		$displayData = array();  
		global $engineeringtStreamMR;
		global $managementStreamMR;

		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['sessionId'] = sessionId();
		
		//Get User Session Data
		$userSessionData = $this->getUserSessionData($displayData['userId'], $displayData['sessionId']);
		if(is_array($userSessionData)){
			$displayData['userSessionData'] = $userSessionData;
		}
		
		//On the intermediate page, first fetch the information about the Current page.
		//This will also give us the SEO details
       if(strstr(getCurrentPageURL(), 'https://mba.') !== FALSE) {
                $redirectUrl = str_replace('https://mba.','https://', getCurrentPageURL());
                header("Location: $redirectUrl", TRUE, 301);
                exit;
        }
	
		$seoUrl='';
		$oldSeoUrl = '';
        if(preg_match('/(.*)-crpage(-)?/',getCurrentPageURL(), $matches)){
	        	$seo = explode('/', $matches[0]);
	        	$seoUrl = '/'.$seo[3];
                $oldSeoUrl = $seoUrl;
        }
        elseif(preg_match('/mba(.*)/',getCurrentPageURL(), $matches) || preg_match('/btech(.*)/',getCurrentPageURL(), $matches)){
        	$seoUrl = '/'.$matches[0];
        	$oldSeoUrl = '/'.$str.'-crpage';
        }

        if(preg_match('/(.*)(\\/reviews)(\\/)(.*)(\\/)/',getCurrentPageURL(), $matches)){
        	$seo = explode('/', $matches[0]);
        	if($seo[3] == 'mba' || $seo[3] == 'btech'){
        		$seoUrl = rtrim($matches[0],'/');
        	}else{
	        	$seoUrl = '/'.$seo[3];
	        }
        	$oldSeoUrl = '/'.$str.'-crpage';
   		}

		$seoUrl = parse_url($seoUrl);
        $seoUrl = $seoUrl['path'];
        $tileData = $this->getTileData('',$seoUrl);
        if(empty($tileData)){
                $oldSeoUrl = parse_url($oldSeoUrl);
                $oldSeoUrl = $oldSeoUrl['path'];
                $tileData = $this->getTileData('',$oldSeoUrl);
        }

		if(is_array($tileData) && count($tileData)>0 ){
			$displayData['currentTileData'] = $tileData;
		}
		else{
			//Redirect it to College Review Homepage
						//$url = SHIKSHA_HOME."/".MBA_COLLEGE_REVIEW;
                        $url = SHIKSHA_HOME;
                        header("Location: $url",TRUE,301);
                        exit;
		}
		foreach ($displayData['currentTileData'] as $tileData){
			$basePageUrl = $tileData[0]['seoUrl'];
			$stream = $tileData[0]['streamId'];
			$baseCourse = $tileData[0]['baseCourseId'];
			$substream = $tileData[0]['substreamId'];
			$educationType = $tileData[0]['educationType'];
			$deliveryMethod = $tileData[0]['deliveryMethod'];
			if($oldSeoUrl != '')
				$paginationURL = $seoUrl."/@pageNum";
			else
				$paginationURL = $oldSeoUrl."/@pageNum";
			
			$courseIdList = $tileData[0]['courseIds'];
			
		}
	
		$reviewURL = "";
		$pageFor = "MBA";

		if($stream == $managementStreamMR){
			$pageFor = "MBA";
			$stream = $managementStreamMR;
			$reviewURL = SHIKSHA_HOME."/".MBA_COLLEGE_REVIEW;

			Modules::run('common/Redirection/validateRedirection',array('pageName'=>'collegeReviews','oldUrl'=>"-crpage",'oldDomainName'=>array(SHIKSHA_MANAGEMENT_HOME),'newUrl'=>SHIKSHA_HOME.'/mba/resources/reviews/'.$str.'/'.$pageNo,'redirectRule'=>301));
			$displayData['revURL'] = SHIKSHA_HOME.'/management/resources/reviews/';
		} else if($stream == $engineeringtStreamMR){
			$pageFor = "Engineering";
			$stream = $engineeringtStreamMR;
			$reviewURL = SHIKSHA_HOME."/".ENGINEERING_COLLEGE_REVIEW;
			Modules::run('common/Redirection/validateRedirection',array('pageName'=>'collegeReviews','oldUrl'=>"-crpage",'oldDomainName'=>array(SHIKSHA_SCIENCE_HOME),'newUrl'=>SHIKSHA_HOME.'/btech/resources/reviews/'.$str.'/'.$pageNo,'redirectRule'=>301));
			$displayData['revURL'] = SHIKSHA_HOME.'/engineering/resources/reviews/';
		}

		// Get All Tiles Data
		$tileData = $this->getTileData($stream);
		if(is_array($tileData) && count($tileData)>0 ){
			$displayData['primaryTile'] = $tileData['top'];
			$displayData['secondaryTile'] = $tileData['bottom'];			
		}

		//Now, get the Review data for this tile
                // $tileDataFormatted = $this->CollegeReviewLib->getDataForSeoUrl($displayData['currentTileData']);
		$displayData['pageNo'] = $pageNo;
		$displayData['count'] = $count = 10;
		$displayData['stream'] = $stream;
		$displayData['baseCourse'] = $baseCourse;
		$displayData['substream'] = $substream;
		$displayData['educationType'] = $educationType;
		
       	$displayData['pageFor'] = $pageFor;
       	$displayData['view'] = 'default';
       	$displayData['subTitle'] = 'Check out this college review. This might be helpful for you.';

       	$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_AllCollegeReview','pageType'=>'ReviewIntermediate','stream_id'=>$stream,'substream_id'=>$substream,'baseCourse'=>$baseCourse,'entity_id'=>$str);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$start = ($pageNo>1)?(($pageNo-1)*$count):0;
		$yearSortFlag = true;
        $displayData['reviewData'] = $this->getReviewsForSlider($start, $count, $courseIdList, $stream,$baseCourse,$educationType,$substream);

		//If Page no is greater than total review count, then redirect to Homepage
		$reviewData = $displayData['reviewData'];
		if(is_array($reviewData['results']) && count($reviewData['results'])==0){
				mail("pranjul.raizada@shiksha.com,abhinav.pandey@shiksha.com,abhijit.bhowmick@shiksha.com","College Review (Intermediate Page) : Zero result page.",print_r($_SERVER,true));
				$url = $reviewURL;
          	 	header("Location: ".SHIKSHA_HOME,TRUE,301);
        		exit;
        }

                // MBA Nav Bar, Hard code for MBA
                // $displayData['tab_required_course_page'] = checkIfCourseTabRequired($subCatId);
                // $displayData['subcat_id_course_page'] = $subCatId;
                $displayData['course_pages_tabselected'] = '';

		//Google Remarketing Code
		$displayData['gtmParams'] = array(
                        "pageType"    => 'collegeReviewPage',
                        "stream"    => $stream
                );

		$displayData['paginationHTML'] = doPaginationCollegeReview($displayData['reviewData']['totalCollegeCards'],$paginationURL,$start,$count,10);
		$totalPages = ceil($displayData['reviewData']['totalCollegeCards']/$count);
		$displayData['totalPages'] = $totalPages;
		$displayData['orderOfReview'] = 'latest';
		//$displayData['showIntermediatePage'] = $this->getCRIntermediatePageView($subCatId);
		//below code used for beacon tracking
	
	//below line is used for conversion tracking purpose
	$displayData['regTrackingPageKeyId']=194;

	//below code used for beacon tracking
	$this->CollegeReviewLib->prapareBeaconData('collegeReviewIntermediatePage',$displayData,$stream,$substream,$baseCourse,$educationType);

		$this->load->view('showReviewIntermediatePage',$displayData);
	}
	
	/**
	 *
	 * Get Tile Data
	 *
	 * @param    TileId
	 * @return   Array with the Tiles Data
	 *
	 */
	function getTileData($stream , $seoUrl = ''){
		$this->init();
		$checkForCriteria = true; // For Home page check for Number of Reviews for Paid / Free Course
        $data = $this->crmodel->getTileData($seoUrl,$stream);
        if($checkForCriteria && count($data)!= 0 && $data != NULL && $seoUrl != "")
		{
			$courseDataTile = $this->crmodel->modifyTileDataForCriteria($data);
			$data[0]['courseIds'] = $this->CollegeReviewLib->getCollegeReviewsByCriteria($courseDataTile);
		}
                $formattedData = $this->CollegeReviewLib->formatTileData($data);
                return $formattedData;
	}
	
	/**
	 *
	 * Get Reviews Data
	 *
	 * @param    Start, Count, Order (Latest/Toprated), List of courseIds (In case of Intermediate page)
	 * @return   Array with the Review Data
	 *
	 */
	function getReviews($start = 0, $count = 10, $orderOfReview ,$courseIds = ''){
		$this->init();
		$checkForCriteria = true; // For Home page check for Number of Reviews for Paid / Free Course
		// $data = $this->crmodel->getLatestAndPopularReviews($courseIds, $orderOfReview, $start, $count,$checkForCriteria);
		$data = $this->CollegeReviewLib->getLatestAndPopularReviews($courseIds, $orderOfReview, $start, $count,$checkForCriteria);
		
		// addon inst name and location, course name, url, abbreviation 
		$formattedData['results'] = $this->CollegeReviewLib->formatReviewData($data); 
		$formattedData['totalReviews'] = $data['totalReviews'];
		$formattedData['reviewerDetails'] = $data['reviewerDetails'];
		return $formattedData;
	}
	
	function getReviewsForSlider($start = 0, $count = 10 ,$courseIds = '', $stream,$baseCourse,$educationType,$substream){
		$this->init();
		
		$data = $this->crmodel->getLatestReviewsForSlider($courseIds, $start, $count, $stream,$baseCourse,$educationType,$substream);
		$formattedData['results'] = $this->CollegeReviewLib->formatReviewDataForSlider($data, $courseIds, $start, $count,'normal');
		$formattedData['totalCollegeCards'] = $data['totalCollegeCards'];
		$formattedData['reviewerDetails'] = $data['reviewerDetails'];
		$formattedData['stream'] = $stream;
		
		return $formattedData;
	}
	
	function getCollegeReview()
	{
		if($this->input->is_ajax_request()){
			$this->init();
			$displayData = array();
			$courseId = $this->input->post('courseId');
			$start = $this->input->post('start');
			$count = $this->input->post('count');
			$stream = $this->input->post('stream');
			$baseCourse = $this->input->post('baseCourse');
			$substream = $this->input->post('substream');
			$educationType = $this->input->post('educationType');
			$data = $this->crmodel->getNextLatestReviewForSlider($courseId, $start, $count,$stream,$baseCourse,$substream,$educationType);
			$temp['results'] = $this->CollegeReviewLib->formatReviewDataForSlider($data, $courseId,$start, $count);
			$temp['reviewerDetails'] = $data['reviewerDetails'];
			$displayData['courseId'] = $courseId;
			$displayData['reviewData'] = $temp;
			$displayData['validateuser'] = $this->userStatus;
			$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
			$displayData['sessionId'] = sessionId();
			//$displayData['URL'] = SHIKSHA_HOME.'/management/resources/reviews/';
			$displayData['view'] = 'default';
			$displayData['subTitle'] = 'Check out this college review. This might be helpful for you.';
			//Get User Session Data
			$userSessionData = $this->getUserSessionData($displayData['userId'], $displayData['sessionId']);
			if(is_array($userSessionData)){
				$displayData['userSessionData'] = $userSessionData;
			}
			
			$this->load->view('getCollegeReviewSliderView',$displayData);
		}else{
			echo 'Unauthorized Access';
		}
	}
	
        /**
         *
         * Get College Review Homepage SEO Data
         *
         * @param    NONE
         * @return   Array with SEO Details
         *
         */
        function getHomepageSEOData ($reviewCount, $pageNo, $stream, $count=10){
                $this->init();
                $pageName = "MBA";
                $totRev = ceil($reviewCount/$count);
                global $managementStreamMR;
				global $engineeringtStreamMR;

                $url = SHIKSHA_HOME.'/'.MBA_COLLEGE_REVIEW;
                
                if($stream == $engineeringtStreamMR){
                	$pageName = "Engineering";
                	$url = SHIKSHA_HOME.'/'.ENGINEERING_COLLEGE_REVIEW;
                }
                $displayData = array();

                //Get Number of reviews and number of Colleges having reviews

                if($pageNo<=1){
	                $displayData['m_meta_title'] = 'Reviews of '.$pageName.' Colleges and courses in India | Shiksha.com';
	                $displayData['m_meta_description'] = 'Read thousands of verified reviews for '.$pageName.' colleges and courses in India.';
                	if($totRev >1){
						$displayData['nextURL'] = $url.'/'.($pageNo+1);
                	}
				} else {
	               	$displayData['m_meta_title'] = 'Page '.$pageNo.' - Reviews of '.$pageName.' Colleges and courses in India | Shiksha.com';
	               	$displayData['m_meta_description'] = 'Page '.$pageNo.' - Read thousands of verified reviews for '.$pageName.' colleges and courses in India.';
                	
						
					if($pageNo < $totRev){
						$displayData['nextURL'] = $url.'/'.($pageNo+1);
					}
					
					$displayData['prevURL'] = $url.'/'.($pageNo-1);
				}
				$displayData['canonicalURL'] = $url;
                $displayData['m_meta_keywords'] = ' ';

                return $displayData;
        }

        function markReviewRead(){
            $this->init();
            $reviewId = $this->input->post('reviewId');
            $source = $this->input->post('source');
            $pageName = $this->input->post('pageName');
            $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;    
            if($reviewId>0){
                $data = $this->crmodel->markReviewRead($reviewId, $userId, $source, $pageName);
            }
	    
        }
	
	function getUserSessionData($userId, $sessionId){
		$this->init();
		$userSessionData = $this->crmodel->getUserSessionData($userId, $sessionId);
		return $userSessionData;
	}

	/*
	 * Clear Cached Data in college Review Pages
	 */
	function clearCacheForCollegeReviews(){
		$this->load->library('CollegeReviewLib'); 
		$this->CollegeReviewLib = new CollegeReviewLib();

		$this->CollegeReviewLib->clearCacheForCollegeReviews();

	}
	
	function getReviewsForHomepage($start = 0,$count = 10,$stream ,$page='homepage',$orderOfReview = 'latest', $baseCourse,$educationType,$substream){
		
		$this->init();
		
		// Code Added By Mansi on 07-Aug-2015
		// Purpose : To include Ranking Page Based Ranking of courses for showing Reviews
		
		if($orderOfReview == 'TopRated'){
			$courseIds = array();

			$data['stream_id'] = $stream;
			$data['substream_id'] = $substream;
			$data['base_course_id'] = $baseCourse;
			$data['education_type'] = $educationType;


			$ranking_model = $this->load->model(RANKING_PAGE_MODULE."/ranking_model");

			$sourceInfo = $ranking_model->getRankingPageSourceByParams($data);
			$sourceId = $sourceInfo[0]['source_id'];

			$data = $ranking_model->getRankingPagesData(array(
														"stream_id" => $stream,
														"substream_id" => $substream,
														"base_course_id" => $baseCourse,
														"education_type" => $educationType,
													  	"status"=>array("live"),
													  	"source_id" => $sourceId
													  ));
			$courseRankMapping = array();

			foreach($data['results'] as $value){
				if($value['source_id'] == $sourceId && $value['param_name'] == OVERALL_PARAM){
					$courseIds[] = $value['course_id'];
					$courseRankMapping[$value['course_id']] = $value['rank'];
				}
			}
			
			//$totalCourseData = $this->crmodel->getCoursesInfo();
			$courseIds = implode(',', $courseIds);
		}

		//$data = $this->crmodel->getLatestReviewsForSlider($courseIds, $start, $count, $categoryId ,$page,$subQuery);
		if($orderOfReview == 'latest'){
			$courseIds = '';
			$data = $this->CollegeReviewLib->getLatestReviewsForSlider($courseIds, $start, $count, $stream ,$page,$subQuery,$baseCourse,$educationType,$substream);
		} else {
			$data = $this->CollegeReviewLib->getPopularReviewsForSlider($courseIds, $start, $count, $stream ,$page,$subQuery,$courseRankMapping,$baseCourse,$educationType,$substream);		
		}

		$formattedData['results'] = $this->CollegeReviewLib->formatReviewDataForSlider($data, $courseIds, $start, $count,'normal',$page);
		$formattedData['totalCollegeCards'] = $data['totalCollegeCards'];
		$formattedData['reviewerDetails'] = $data['reviewerDetails'];
		$formattedData['totalReviewCount'] = $data['totalReviewCount'];
		
		return $formattedData;
		
	}

	function redirectToNewURL($pageNo = 1){

		$url = SHIKSHA_HOME."/".MBA_COLLEGE_REVIEW."/".$pageNo;
        header("Location: $url",TRUE,301);

	}

	function redirectToNewBtechURL($pageNo = 1){

		$url = SHIKSHA_HOME."/".ENGINEERING_COLLEGE_REVIEW."/".$pageNo;
        header("Location: $url",TRUE,301);

	}

	/**
	 *
	 * Get College Review Page
	 *
	 * @param    review Id
	 * @return   view of particular review
	 *
	 */
	function showReviewPage($reviewId){

		show_404();

		if(empty($reviewId)) {
			header("Location: ".SHIKSHA_HOME,TRUE,301);exit;
		}
		$this->init();

		$displayData = array();
		$displayData = $this->getReviewInfo($reviewId);
		$subcatId = $displayData['subcatId'];
		$categoryId = $displayData['categoryId'];

		$categoryPageDomainMapping = array('3'=>'MBA', '2'=>'Engineering', '4'=>'Banking & Finance', '10'=>'IT');
		$pageFor = $categoryPageDomainMapping[$categoryId];
		$countryId = 2;
		$displayData['pageFor'] = $pageFor;
		
		// Get Tile Data
        $displayData['primaryTile'] = $this->crmodel->getTilesDataBySubCatId($subcatId);

        // Get Count of reviews on a Course
        $displayData['courseReviewsCount'] = $this->crmodel->getReviewsCountByCourseId($displayData['reviewData']['courseId']);

		// Logged In User and session Details
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['sessionId'] = sessionId();
		
		//Get User Session Data
		$userSessionData = $this->getUserSessionData($displayData['userId'], $displayData['sessionId']);
		if(is_array($userSessionData)){
			$displayData['userSessionData'] = $userSessionData;
		}

        $userName = '';
        if($displayData['reviewData']['anonymousFlag'] == 'NO') {
        	$userName = $displayData['reviewUserDetails']['firstname']."'s ";
        }
        $displayData['meta_title'] = $displayData['reviewData']['review_seo_title'];
        $displayData['meta_description'] = "Read more about ".$userName."comprehensive review of ".$displayData['reviewData']['courseName']." offered at ".$displayData['reviewData']['instituteName']." only at Shiksha.com | ".$displayData['reviewData']['reviewId'];
        //$displayData['canonicalURL'] = $displayData['reviewData']['review_seo_url'];
        $displayData['ratingNames'] = array('Poor','Below Average ','Average','Above Average','Excellent');
 		
        // Get category Page URL
 		$this->load->library('categoryList/CategoryPageRequest');
        $request = new CategoryPageRequest();
        $request->setData(array(
                                    'categoryId'    =>  $categoryId,
                                    'subCategoryId' =>  $subcatId,
                                    'countryId'     =>  $countryId
                                  ));
        $displayData['categoryPageURL'] = $request->getURL(); 

        //below code used for beacon tracking
		$displayData['trackingpageIdentifier'] = 'showReviewsPage';
		$displayData['trackingcountryId'] = 2;

		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($displayData);

        if(isMobileRequest()){
        		$displayData['pageName'] = 'collegeReview';
        		$displayData['share'] = array('facebook','twitter','linkedin','google','whatsapp');
        		$displayData['subTitle'] = 'Check out this college review. This might be helpful for you.';
        		$displayData['permURL'] = $displayData['reviewData']['review_seo_url'];
        		$ShikshaMobileWebSite = new ShikshaMobileWebSite_Controller();
                $displayData['userStatus'] = $ShikshaMobileWebSite->data['m_loggedin_userDetail'];

        		$this->load->view('mCollegeReviews5/showReviewPageMobile',$displayData);
        } else {
        		$displayData['socialSharingParams'] = array('share' => array('facebook','twitter','linkedin','google'),'permURL' => $displayData['reviewData']['review_seo_url'],'subTitle' => 'Check out this college review. This might be helpful for you.');

        		$this->load->view('showReviewPage',$displayData);
        }

	}

	public function getReviewInfo($reviewId) {

		$reviewDetails = array();
		$reviewDetails = $this->crmodel->getReviewDetailbyReviewId($reviewId);

		$reviewRating = $this->crmodel->getRatingReviewId($reviewId);

		foreach ($reviewRating as $key => $value) {
			$tempArray[$value['description']] = $value['rating'];
		}

		unset($reviewRating);

		$reviewDetails['ratingValue'] = $tempArray;
		unset($tempArray);


		if((empty($reviewDetails)) || ($reviewDetails['status'] != 'published') || (empty($reviewDetails['review_seo_url'])) || ($reviewDetails['review_seo_url'] == NULL)) {
			header("Location: ".SHIKSHA_HOME,TRUE,301);exit;
		}

		$this->CollegeReviewLib->validateReviewURL($reviewDetails['review_seo_url']);

		// Get Review Mapped Shiksha Institute by review id
		$reviewInstituteDetails = array();
		$reviewInstituteDetails = $this->crmodel->getReviewInstituteDetailsByReviewId($reviewDetails['id']);
		unset($reviewInstituteDetails['id']); // for preventing from overwriting of $reviewDetails[id] with this id while array_merge below

		$reviewData = array();
		$reviewData = array_merge($reviewDetails, $reviewInstituteDetails);

		$reviewData['postedDate'] = $reviewDetails['modificationDate'];

		$formattedReviewData = $this->CollegeReviewLib->getReviewFormattedData($reviewData);

		$displayData = array(); 

		$displayData['reviewData'] = $formattedReviewData;

		// Get User details of the person who posted the review
		$displayData['reviewUserDetails'] = $this->crmodel->getReviewUserDetailsByUserId($reviewDetails['reviewerId'], $reviewDetails['userId']);

		$idsArray = $this->crmodel->getCatSubCatIdsByCourseId($reviewInstituteDetails['courseId']);
		$displayData['subcatId'] = $idsArray['subCategoryId'];
		$displayData['categoryId'] = $idsArray['categoryId'];

		return $displayData;

	}

	function updateSEODetailsForPublishedReviews(){

		$this->init();
		$startTime = time();
		$this->load->model('CAEnterprise/reviewenterprisemodel');
		$this->reviewenterprisemodel = new ReviewEnterpriseModel();	

		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$this->courseRepository = $listingBuilder->getCourseRepository();		

		$reviewsData = $this->crmodel->getAllCollegeReviews();

		$categoryDomainMapping = array('3'=>'management', '2'=>'engineering', '4'=>'banking', '10'=>'it');
		
		foreach ($reviewsData as $key => $reviewDetails) {
			$start = '';
			
			$start = time();
			_P("Start processing Review no. ".$key);
			$userDetails = $this->crmodel->getReviewUserDetailsByUserId($reviewDetails['reviewerId'],$reviewDetails['userId']);
			$courseDetails = $this->crmodel->getReviewInstituteDetailsByReviewId($reviewDetails['id']);
			$reviewerName = $userDetails['firstname'];
			//$subCategoryId = $this->crmodel->getSubCategoryIdByCourseId($courseDetails['courseId']);
			$idsArray = $this->crmodel->getCatSubCatIdsByCourseId($courseDetails['courseId']);
			$categoryId = $idsArray['categoryId'];
			//_P($categoryId);
			$domain = '';
			
			if($categoryDomainMapping[$categoryId]){
				$domain = $categoryDomainMapping[$categoryId];
			} 
			else {
				_P("End processing Review no. ".$key);
				$end = '';
				$timeElapsed = '';
				$end = time();
				$timeElapsed = $end - $start ;
				error_log("######Time taken to process review ".$reviewDetails['id']." : ".$timeElapsed);
				continue;
			}

			$courseObj = $this->courseRepository->find($courseDetails['courseId']);
			$courseName = $courseObj->getName();
			$instituteName = $courseObj->getInstituteName();

			$seoReviewURL = "/".$domain."/resources/reviews/".seo_url($courseName)."-".seo_url($instituteName)."-review/".$reviewDetails['id'];

			if($reviewDetails['anonymousFlag'] == 'NO')
				$seoReviewTitle =  "Read ".$reviewerName."’s review of ".$courseName." on Shiksha.com | ".$reviewDetails['id'];
			else
				$seoReviewTitle = "Read review of ".$courseName." on Shiksha.com | ".$reviewDetails['id'];

			$this->reviewenterprisemodel->updateReviewURLAndTitle($reviewDetails['id'],$seoReviewURL,$seoReviewTitle);

			_P("End processing Review no. ".$key);
			$end = '';
			$timeElapsed = '';
			$end = time();
			$timeElapsed = $end - $start ;
			error_log("######Time taken to process review id ".$reviewDetails['id']." : ".$timeElapsed);
			
		}
		$endTime = time();
		$diff = $endTime-$startTime;
		_P("Total time taken by Script : ".$diff);
		error_log("######Total time taken by Script ".$diff);
	}

	//one time script, delete once deployed on production
	function scriptToUpdateMaster(){
		$this->init();

		$masterRatingList = array('Value for Money','Crowd & Campus Life','Salary & Placements','Campus Facilities','Faculty','Campus Facilities (inc. Design Studio etc.)','Industry Exposure and Internships','Campus Facilities (inc. Library)','Campus Facilities (inc. Media Lab, Equipment etc.)','Willingness of Companies to Hire');

		$masterMotivationList = array('Campus Infrastructure','Crowd and Campus Life','Faculty','Fees','Placements and Salary','Ranking / Brand Name of College','Specific Course & Stream','Affiliation to 5/7 Star Hotels');

		$this->crmodel->addToMaster($masterRatingList,$masterMotivationList);

	}

	//one time script, delete once deployed on production  '' => '',

	//bug in script  -> resolve later
	function scriptToCateogryMasterRating(){
		$this->init();


		$mappingArray = array(
							'MBA' => array('Value for Money','Crowd & Campus Life','Salary & Placements','Campus Facilities','Faculty'),
							'Design' => array('Value for Money','Crowd & Campus Life','Salary & Placements','Campus Facilities (inc. Design Studio etc.)','Faculty'),
							'Law' => array('Value for Money','Crowd & Campus Life','Salary & Placements','Campus Facilities (inc. Library)','Faculty'),
							'Media' => array('Industry Exposure and Internships','Crowd & Campus Life','Willingness of Companies to Hire','Campus Facilities (inc. Media Lab, Equipment etc.)','Faculty')	
						);

		$mappingCategory = array(
							'MBA' => array('23','56','28'),
							'Design' => array('69','70','71','72','73'),
							'Law' => array('33'),
							'Media' => array('15','16','17','18','19','20','21','22')
						);


		$this->crmodel->addToCateogryMaster($mappingArray,$mappingCategory);

	}

	function scriptToCateogryMasterMotivation(){
		$this->init();


		$mappingArray = array(
							'MBA' => array('Campus Infrastructure','Crowd and Campus Life','Faculty','Fees','Placements and Salary','Ranking / Brand Name of College','Specific Course & Stream'),
							'Design' => array('Campus Infrastructure','Crowd and Campus Life','Faculty','Fees','Placements and Salary','Ranking / Brand Name of College','Specific Course & Stream'),
							'Law' => array('Campus Infrastructure','Crowd and Campus Life','Faculty','Fees','Placements and Salary','Ranking / Brand Name of College','Specific Course & Stream'),
							'Media' => array('Campus Infrastructure','Crowd and Campus Life','Faculty','Fees','Placements and Salary','Ranking / Brand Name of College','Specific Course & Stream')
						);

		$mappingCategory = array(
							'MBA' => array('23','56','28'),
							'Design' => array('69','70','71','72','73'),
							'Law' => array('33'),
							'Media' => array('15','16','17','18','19','20','21','22')
						);



		$this->crmodel->scriptToCateogryMasterMotivation($mappingArray,$mappingCategory);

	}

	function sendWeeklyDashboard(){
		$this->validateCron();
		$this->init();

		$reviewStatus = array('category','published', 'draft', 'accepted', 'later', 'rejected');

		//any sub cat added here should also be added to remainingCat config below
		$categoryIds = array('FT MBA'=>array(23),'B.Tech'=>array(56),'Design (Category)'=>array(69,70,71,72,73),'Hotel Management'=>array(84),'Media (Category)'=>array(15,16,17,18,19,20,21,22),'BBA'=>array(28),'Law'=>array(33),
			'M.Tech' => array(59), 'Executive MBA' => array(25));

		$columnName = array('','Reviews Sourced','Reviews Pending Moderation','Reviews Accepted(of Moderated)','Reviews Rejected(of Moderated)','Reviews Marked Later','Reviews Moderated (Accepted / Rejected)');
		
		$dashboardData = array();
		$remainingCategoryIds = array('Others - remaining categories' => array(23,56,69,70,71,72,73,84,15,16,17,18,19,20,21,22,28,33,59,25));

		foreach ($categoryIds as $catName => $cat) {
			$courses = $this->collegereviewmodel->getAllCourseForCategory($cat,false);
			$data = $this->getDashboardData($courses);
			$dashboardData[$catName] = $data;
		}

		$courses = $this->collegereviewmodel->getAllCourseForCategory($remainingCategoryIds['Others - remaining categories'],true);
		$dashboardData['Others - remaining categories'] = $this->getDashboardData($courses);

		unset($courses);

		$dashboardData['unmapped'] = $this->getDashboardUnMapped();

		$finalDashboardData = array();
		$finalDashboardData[0] = $columnName;

		foreach ($dashboardData as $key => $value) {
			
			$finalDashboardData[$key][$key] =$key;
			
			foreach ($reviewStatus as $status) {
				if($status == 'category'){
					continue;
				}

				if(!$value[$status]){
					$value[$status] = 0;
				}

				$totalReviews = $totalReviews + $value[$status];
				$finalDashboardData[$key][$status] =  $value[$status];
			}


			$finalDashboardData[$key]['Reviews Sourced'] = $totalReviews;
			$finalDashboardData[$key]['Reviews Pending Moderation'] = $finalDashboardData[$key]['draft'];
			$finalDashboardData[$key]['Reviews Accepted(of Moderated)'] = $finalDashboardData[$key]['accepted'] + $finalDashboardData[$key]['published'];
			$finalDashboardData[$key]['Reviews Rejected(of Moderated)'] = $finalDashboardData[$key]['rejected'];
			$finalDashboardData[$key]['Reviews Marked Later'] = $finalDashboardData[$key]['later'];
			$finalDashboardData[$key]['Reviews Moderated (Accepted / Rejected)'] = $finalDashboardData[$key]['Reviews Accepted(of Moderated)'] + $finalDashboardData[$key]['Reviews Rejected(of Moderated)'];
			
			unset($totalReviews);
			unset($finalDashboardData[$key]['accepted']);
			unset($finalDashboardData[$key]['published']);
			unset($finalDashboardData[$key]['draft']);
			unset($finalDashboardData[$key]['rejected']);
			unset($finalDashboardData[$key]['later']);
		}

		$body = 'college review dashboard';


		$fp = fopen('/tmp/CollegeReviewDashboard.csv', 'w');
		foreach ($finalDashboardData as $fields) {

		    fputcsv($fp, $fields);
		}
		
		fclose($fp);	

		$currentDate =  date("d/m/y");
		$d=strtotime("-1 Months");
		$previousDate = date("d/m/y", $d);

		$fromEmail ='info@shiksha.com';
		$toEmail = "pranjul.raizada@shiksha.com,abhinav.pandey@shiksha.com,abhijit.bhowmick@shiksha.com";
		$subject = "College Reviews Monthly Dashboard for ".$previousDate." to ".$currentDate;
		$content = "Hi,<br><br> PFA the monthly dashboard for College Reviews. <br><br>-Thanks & Regards,<br>Shiksha Team";
		$attachmentPath = '/tmp/CollegeReviewDashboard.csv' ;
		$fileName= 'CollegeReviewDashboard.csv';
		
		$this->load->library("common/Util");
        $utilObj = new Util();  
                
        $utilObj->sendEmailWithAttachement($fromEmail,$toEmail,$cc,$subject,$content,$attachmentPath,$fileName);

	}

	function getDashboardData($courses){


		if(empty($courses )){
			continue;
		}

		$totalCourse = count($courses);
		$itr=0;
		$courseIdArray = array();
		$dashboardData = array();

		foreach ($courses as $course) {
			$courseIdArray [] = $course['course_id'];
			$itr++;

			if($itr%500 == 0 || $itr ==  $totalCourse){
				$data[] = $this->collegereviewmodel->getDashboardData($courseIdArray);
				
				$courseIdArray= array();
			}
		}

		foreach ($data as $val) {
			foreach ($val as $temp) {
				$dashboardData[$temp['status']] = $temp['count'] + $dashboardData[$temp['status']];
			}
		}

		return $dashboardData;
	}

	function getDashboardUnMapped(){
		$data  = $this->collegereviewmodel->getDashboardUnMapped();
		
		$dashboardData = array();
		foreach ($data as $key => $value) {
			$dashboardData[$value['status']] = $value['count'];
		}

		return $dashboardData;
	}

	function migrateTileData(){
		$this->init();
		$this->collegereviewmodel->updateEngineeringTiles();
		$this->collegereviewmodel->updateMBATiles();
		return;
	}

	function migrateRatingParam(){
		$this->init();
		$distinctSubCat = $this->collegereviewmodel->getAllDistinctSubcat();
		$newhierarchy = $this->collegereviewmodel->getNewHierarchy($distinctSubCat['subCategoryId']);
		$this->collegereviewmodel->updateRatingMappingByStream($newhierarchy);
		echo 'done';
	}

	function getAverageRatingAndCountByCourseId($courseId){
	
		if(!isset($courseId) || $courseId == ''){
			return false;
		}

		$this->load->model('collegereviewmodel');
		$this->crmodel = new CollegeReviewModel();	

		$data = $this->crmodel->getAverageReviewRatingandCount($courseId);

		$returnResult['totalReview'] = $data['totalReview'];
		$returnResult['averageRating'] = round($data['sum']/$data['totalReview'],2);
		
		return $returnResult;
	}
	function migrateTileURL(){
		$this->init();
		$tileUrls = $this->collegereviewmodel->getTileURL();

		foreach ($tileUrls as $key => $value) {
			if(preg_match('/(.*)-crpage(-)?/',$value['seoUrl'], $matches)){
				$url = explode('/', $matches[1]);
				$newURL = SHIKSHA_HOME.'/btech/resources/reviews/'.$url[3];

				$this->collegereviewmodel->updateTileURL($value['seoUrl'],$newURL);
			}
		}
		echo 'updated';
		die;
	}


	function migrateCollegeReviews(){
		$filePath	= '/tmp/reviews.csv';
		$csvFile = file($filePath);

		$firstRow = 1;
		$reviewIds = array();
		$oldCourseId = 170061;
		$newCourseId = 295890;
		foreach ($csvFile as $line) {
	    	$row= str_getcsv($line);
	    	if($firstRow == 1){
	    		$firstRow =0;
	    		continue;
	    	}

	    	$reviewIds[] = $row[1];
	    }
	    //_p($reviewIds);die;

	    // filter review ids
	    $crlib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
	    $reviewIds = $crlib->filterReviewsForCourse($reviewIds, $oldCourseId);
	    //_p($reviewIds);die;

	    // update course
	    $this->load->model('collegereviewmodel');
		$crmodel = new CollegeReviewModel();	


	    $crmodel = $this->load->model('CollegeReviewForm/collegereviewmodel');

	    // add these reviews to indeing log
		$crmodel->insertBulkReviewForIndex($reviewIds);

	    // migrate reviews to new course
		$crmodel->migrateReviewsToNewCourse($reviewIds, $newCourseId);

		// update review status as published in main table
		$data = array('status' => 'published');
		$crmodel->updateReviewsStatus($reviewIds, $data);
		
		// add these reviews to tracking table
		foreach ($reviewIds as $reviewId) {
			$CRTrackingData[] = array(
				"reviewId" => $reviewId, 
				"addedBy" => 11, 
                "action" => 'statusUpdated',
                "data" => json_encode(array("status"=>"published"))
				);
			$CRTrackingData[] = array(
				"reviewId" => $reviewId, 
				"addedBy" => 11, 
                "action" => 'courseDetailsUpdated',
                "data" => json_encode(array("courseId"=>$newCourseId))
				);
		}

		$this->load->model('CAEnterprise/reviewenterprisemodel');
		$reviewenterprisemodel = new ReviewEnterpriseModel();
		$reviewenterprisemodel->trackCollegeReview($CRTrackingData,true);
	}
}
?>
