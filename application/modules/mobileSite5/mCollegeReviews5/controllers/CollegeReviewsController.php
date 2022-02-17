<?php

class CollegeReviewsController extends ShikshaMobileWebSite_Controller {
	
	private $userStatus;
	
	function init(){
		$this->load->model('QnAModel');
		$this->load->config('mcommon5/mobi_config');
		$this->load->helper('mcommon5/mobile_html5');
		$this->userStatus = $this->checkUserValidation();
		
		$this->load->library('CollegeReviewForm/CollegeReviewLib');
		$this->CollegeReviewLib = new CollegeReviewLib();
	
		$this->load->model('CollegeReviewForm/collegereviewmodel');
		$this->crmodel = new CollegeReviewModel();
	}

	/**
	 *
	 * Show College review mobile Homapage
	 *
	 * @param    None
	 * @return   View with the Homepage
	 *
	 */
	function collegeReviewsHomepage($stream = '', $baseCourse = '', $educationType = '', $substream = ''){
		ini_set("memory_limit","200M");
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

		$pageFor = "MBA";
		
		if($stream == $managementStreamMR){
			$pageFor = "MBA";
		} else if($stream == $engineeringtStreamMR){
			$pageFor = "B.Tech";
		}

		Modules::run('common/Redirection/validateRedirection',array('pageName'=>'collegeReviews','oldUrl'=>"mba-colleges-reviews-cr",'oldDomainName'=>array(SHIKSHA_MANAGEMENT_HOME),'newUrl'=>SHIKSHA_HOME.'/mba/resources/college-reviews','redirectRule'=>301));
        
        $enteredURL = getCurrentPageURL();

		if(!$this->input->is_ajax_request()){
			if($url!='' && $url!=$enteredURL){
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
		}else{
			$stream = isset($_POST['stream']) ? $this->input->post('stream',true) : $managementStreamMR;
		}
		
		//Redirect to shiksha Domain
		if('https://'.$_SERVER['HTTP_HOST']==SHIKSHA_SCIENCE_HOME){			
            header('location:'.SHIKSHA_HOME,301);
        }

		$this->init();
		$displayData = array();  
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;		
		$displayData['boomr_pageid'] = 'College_review_homepage';
		$displayData['pageName'] = 'collegeReviews';
		$displayData['sessionId'] = sessionId();
		
		$displayData['subTitle'] = 'Check out this college review. This might be helpful for you.';
		//Get User Session Data
		$userSessionData = Modules::run("CollegeReviewForm/CollegeReviewController/getUserSessionData",$displayData['userId'], $displayData['sessionId']);
		if(is_array($userSessionData)){
			$displayData['userSessionData'] = $userSessionData;
		}

		$data = $this->crmodel->getTileData("",$stream);
		$formattedData = $this->CollegeReviewLib->formatTileData($data);

		$displayData['primaryTile'] = $formattedData['top'];
		$displayData['secondaryTile'] = $formattedData['bottom'];
		$displayData['stream'] = $stream;
       	$displayData['baseCourse'] = $baseCourse;
		$displayData['educationType'] = $educationType;
		$displayData['substream'] = $substream;

		$start = isset($_POST['start'])?$this->input->post('start'):0;
		$count = isset($_POST['count'])?$this->input->post('count'):5;
		
		$orderOfReview = isset($_POST['orderOfReview'])?$this->input->post('orderOfReview'):'latest';
		
		$checkForCriteria = true;

        $reviewData = $this->getReviewsForHomepageMobile($start, $count,$stream,'homepage',$orderOfReview,$baseCourse,$educationType,$substream);

        if(count($reviewData['results'])<=0){
			$body = "Details :<br/> Order of review : ".$orderOfReview."<br/>";
			$body .= "Stream : ".$stream."<br/> BaseCourse :".$baseCourse."<br/> EducationType".$educationType."</br> Substream : ".$substream."<br/>";
			$body .= "$ SERVER : ".print_r($_SERVER,true)."<br/> Visitor Session Id: ".getVisitorSessionId()."<br/> POst Data : ".print_r($_POST, true)."<br/> Get Data".print_r($_GET,true) ;
			mail("teamldb@shiksha.com","College Review : Zero result page.",$body);
		}
        
        $displayData['totalCollegeCards'] = $reviewData['totalCollegeCards'];
		
		$displayData['totalReviews']=$displayData['totalCollegeCards'];
		$displayData['ReviewPerPage'] = $count;
		$displayData['reviewerDetails'] = $result['reviewerDetails'];
		$displayData['pageFor'] = $pageFor;

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_AllCollegeReview','entity_id'=>$stream);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');
		
		$this->load->library('CollegeReviewLib');
		$this->CollegeReviewLib = new CollegeReviewLib();
		
		$displayData['orderOfReview'] = $orderOfReview;
		$displayData['reviewData'] = $reviewData;

	    //Google Remarketing Code
	    $displayData['gtmParams'] = array(
                        "pageType"    => 'collegeReviewPage',
                        "stream"    => $stream
                );

		$displayData['ajaxCall'] = (isset($_POST['ajaxCall']) && $_POST['ajaxCall'] == 'yes')?'yes':'no';

		if($this->input->is_ajax_request()) {
			$view = $this->load->view('showReviewPopularLatestWidget',$displayData, true);
			$view = base64_encode($view);
			echo json_encode(array('html'=>$view, 'totalReviews'=>$displayData['totalReviews']));
		} else {
		//Get the Total number of Reviews from Backend
            $totalReviewCount = $reviewData['totalReviewCount'];
            $updatedReviewCount = $this->CollegeReviewLib->checkReviewCount($totalReviewCount);
            $displayData['totalReviewCount'] = $updatedReviewCount;

		//Get SEO Data of Homepage
            $seo_details = $this->getHomepageSEOData($displayData['totalReviewCount'], $stream);
	        $displayData['m_meta_title'] = $seo_details['m_meta_title'];
        	$displayData['m_meta_description'] = $seo_details['m_meta_description'];
            $displayData['canonicalURL'] = $seo_details['canonicalURL'];
	        $displayData['m_meta_keywords'] = $seo_details['m_meta_keywords'];
       		//below code used for tracking purpose
	        $displayData['trackingpageIdentifier']='collegeReviewPage';
			$displayData['trackingcountryId']=2;

			//below code used for beacon tracking
			$this->CollegeReviewLib->prapareBeaconData("collegeReviewPage",$displayData,$stream,$substream,$baseCourse,$educationType);	

			$this->load->view('showReviewHomepage',$displayData);
		}

	}


	/**
	 *
	 * Show College review Intermediate Page
	 *
	 * @param    Title. This title will decide which data to fetch from Backend
	 * @return   View with the Page
	 *
	 */
	function collegeIntermediatePage($title){

		$this->init();
		$displayData = array();  
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['boomr_pageid'] = 'College_review_intermediate';
		
		$displayData['sessionId'] = sessionId();
		global $managementStreamMR;
		global $engineeringtStreamMR;
		
		//Get User Session Data
		$userSessionData = Modules::run("CollegeReviewForm/CollegeReviewController/getUserSessionData",$displayData['userId'], $displayData['sessionId']);
		if(is_array($userSessionData)){
			$displayData['userSessionData'] = $userSessionData;
		}
		
		//Based on the Title, we have to decide which Data to fetch from CMS Backend
		if(strstr(getCurrentPageURL(), 'https://mba.') !== FALSE) {
                        $redirectUrl = str_replace('https://mba.','https://', getCurrentPageURL());
                        header("Location: $redirectUrl", TRUE, 301);
                        exit;
                }
		
		 $seoUrl = isset($_POST['seoUrl'])?$this->input->post('seoUrl'):'';
		$oldSeoUrl = '';
        if(preg_match('/(.*)-crpage(-)?/',getCurrentPageURL(), $matches)){
                $seo = explode('/', $matches[0]);
	        	$seoUrl = '/'.$seo[3];
                $oldSeoUrl = $seoUrl;
        }
        elseif(preg_match('/mba(.*)/',getCurrentPageURL(), $matches) || preg_match('/btech(.*)/',getCurrentPageURL(), $matches)){
        	$seoUrl = '/'.$matches[0];
        	$oldSeoUrl = '/'.$title.'-crpage';
		}
		$checkForCriteria = true; 
		$seoUrl = parse_url($seoUrl);
        $seoUrl = $seoUrl['path'];
        $tileData = $this->crmodel->getTileData($seoUrl);
        if(empty($tileData)){
                $oldSeoUrl = parse_url($oldSeoUrl);
                $oldSeoUrl = $oldSeoUrl['path'];
                $tileData = $this->crmodel->getTileData($oldSeoUrl);
        }

		
		if($checkForCriteria && count($tileData)!= 0 && $tileData != NULL)
		{
			$courseDataTile = $this->crmodel->modifyTileDataForCriteria($tileData);
			$tileData[0]['courseIds'] = $this->CollegeReviewLib->getCollegeReviewsByCriteria($courseDataTile);
		}

		$stream = $tileData[0]['streamId'];
		$baseCourse = $tileData[0]['baseCourseId'];
		$substream = $tileData[0]['substreamId'];
		$educationType = $tileData[0]['educationType'];
		$deliveryMethod = $tileData[0]['deliveryMethod'];
		$pageFor = "MBA";

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_AllCollegeReview','pageType'=>'ReviewIntermediate','stream_id'=>$stream,'substream_id'=>$substream,'baseCourse'=>$baseCourse,'entity_id'=>$title);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		if($stream == $managementStreamMR){
			$reviewURL = SHIKSHA_HOME."/".MBA_COLLEGE_REVIEW; 
			$pageFor = "MBA";
			Modules::run('common/Redirection/validateRedirection',array('pageName'=>'collegeReviews','oldUrl'=>"-crpage",'oldDomainName'=>array(SHIKSHA_MANAGEMENT_HOME),'newUrl'=>SHIKSHA_HOME.'/mba/resources/reviews/'.$title,'redirectRule'=>301));
			$displayData['revURL'] = SHIKSHA_HOME.'/management/resources/reviews/';
		} else if($stream == $engineeringtStreamMR){
			$reviewURL = SHIKSHA_HOME."/".ENGINEERING_COLLEGE_REVIEW;
			$pageFor = "Engineering";
			Modules::run('common/Redirection/validateRedirection',array('pageName'=>'collegeReviews','oldUrl'=>"-crpage",'oldDomainName'=>array(SHIKSHA_SCIENCE_HOME),'newUrl'=>SHIKSHA_HOME.'/btech/resources/reviews/'.$title.'/'.$pageNo,'redirectRule'=>301));
			$displayData['revURL'] = SHIKSHA_HOME.'/engineering/resources/reviews/';
		}
		
		if(is_array($tileData) && count($tileData)>0 ){
			$displayData['currentTileData'] = $tileData[0];
			$tileData = $tileData[0];
		}
        else{
                //Redirect it to College Review Homepage
                //$url = SHIKSHA_HOME."/".MBA_COLLEGE_REVIEW;
                $url = SHIKSHA_HOME;
                header("Location: $url",TRUE,301);
                exit;
        }
        
        //Google Remarketing Code
		$displayData['gtmParams'] = array(
                        "pageType"    => 'collegeReviewPage',
                        "stream"    => $stream
                );
		
		//Now, get the Review data for this tile
	
		$displayData['seoUrl'] = $seoUrl;
		$ajaxCall = (isset($_POST['ajaxCall']) && $_POST['ajaxCall'] == 'yes')?'yes':'no';

		if($ajaxCall=='yes' || $tileData['courseIds'] != ''){
			$tileDataFormatted = $this->CollegeReviewLib->getDataForSeoUrl($displayData['currentTileData']);

			// get category Id of the tile
        	$displayData['stream'] = $stream;
        	$displayData['displayNumbers'] = $tileData['displayNumbers'];
			$start = isset($_POST['start'])?$this->input->post('start'):0;
			$count = isset($_POST['count'])?$this->input->post('count'):3;
		
			$displayData['reviewData'] = $this->getReviewsForSliderMobile($start, $count, $tileData['courseIds'],$stream,$baseCourse,$educationType,$substream);
			
			$reviewData = $displayData['reviewData'];
			
			if(is_array($reviewData['results']) && count($reviewData['results'])==0){
				mail("teamldb@shiksha.com","College Review (Intermediate Page) : Zero result page.",print_r($_SERVER,true));
				$url = $reviewURL;
				header("Location: $url",TRUE,301);
				exit;
			}
			//below code used for tracking purpose
			$this->CollegeReviewLib->prapareBeaconData('collegeReviewIntermediatePage',$displayData,$stream,$substream,$baseCourse,$educationType);
			$displayData['pageName'] = 'collegeReviews';

			$displayData['subTitle'] = 'Check out this college review. This might be helpful for you.';
			if($this->input->is_ajax_request()){
				
				$displayData['PageNo'] = ($start/$count)+1;
				$displayData['ajaxCall'] = $displayData['PageNo'];
				
				$this->load->view('intermediatePageReviewsWidget',$displayData);
				
			}else{
				
				//$displayData['showIntermediatePage'] = $this->getCRIntermediatePageView($subCatId);	
				$this->load->view('showReviewIntermediatePage',$displayData);
			}
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
    function getHomepageSEOData ($reviewCount, $stream){
                $this->init();
                $pageName = "MBA";
                $url = SHIKSHA_HOME.'/'.MBA_COLLEGE_REVIEW;
                global $managementStreamMR;
				global $engineeringtStreamMR;
				if($stream == $engineeringtStreamMR){
                	$pageName = "B.Tech";
                	$url = SHIKSHA_HOME.'/'.ENGINEERING_COLLEGE_REVIEW;
                }
                $displayData = array();

                //Get Number of reviews and number of Colleges having reviews
		
                $displayData['m_meta_title'] = 'Reviews of '.$pageName.' Colleges in India | Shiksha.com';
                $displayData['m_meta_description'] = 'Read thousands of verified reviews for '.$pageName.' colleges in India.';
            	$displayData['canonicalURL'] = $url;
            	$displayData['nextURL'] = $url.'-'.($pageNo+1);
		
                $displayData['m_meta_keywords'] = ' ';

                return $displayData;
        }
	
	function getCollegeUrl($instId = 0)
	{
		$instId = ($this->input->post('instituteId')) ? $this->input->post('instituteId') : $instId;
		if(isset($instId) && $instId>0)
		{
			$this->load->builder('nationalInstitute/InstituteBuilder');
            $instituteBuilder = new InstituteBuilder;
            $instituteRepo    = $instituteBuilder->getInstituteRepository();
			$res = $instituteRepo->find($instId,array('basic'));
			return $res->getURL(); 
		}	
	}
	
	// create index on solr
	function createIndexOnSolr()
	{
		$this->init();
		$courseArr = array();
		$course = $this->crmodel->getReviewCourses();
		if(count($course)>0)
		{
			foreach($course as $course)
			{
				Modules::run ( 'search/Indexer/delete', $course['courseId'], "course",false);
				Modules::run ( 'search/Indexer/index', $course['courseId'], "course",false);
				$courseArr[] = $course['courseId'];
			}
			echo json_encode($courseArr);
		}
		
	}
	function getReviewPageByInstituteId($instituteId=0){
            
                $instituteId = $this->input->post('instituteId')!=NULL?$this->input->post('instituteId'):$instituteId;
                $fromPage    = $this->input->post('instituteId')!=NULL?$this->input->post('fromPage'):'';
                if(isset($instituteId)&&$instituteId>0){
                    $this->load->builder('nationalInstitute/InstituteBuilder');
                    $this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
                    $instituteBuilder = new InstituteBuilder;
                    $instituteRepo    = $instituteBuilder->getInstituteRepository();
                    $institute = $instituteRepo->find($instituteId,array('basic'));
                    if(!is_object($institute)){
                        //write a return statement
                        return;
                    }
                    $instituteCourseIdData = $this->instituteDetailLib->getInstituteCourseIds($instituteId,$institute->getType(),'direct');
                    $this->collegeReviewModel = $this->load->model('CollegeReviewForm/collegereviewmodel');
                    $courseReviewsData = $this->collegeReviewModel->getAllReviewsByCourse($instituteCourseIdData['courseIds'],1);
                    $noOfCoursesWithReviews = count($courseReviewsData);
                    $url = '';
                    if($noOfCoursesWithReviews==0){
                        $url  = $institute->getURL();
                    }
                    else if($noOfCoursesWithReviews==1){
                        foreach($courseReviewsData as $courseIdWithReview=>$reviewOfCourse){
                            $this->load->builder("nationalCourse/CourseBuilder");
                            $courseBuilder  = new CourseBuilder();
                            $courseRepo     = $courseBuilder->getCourseRepository();
                            $course = $courseRepo->find($courseIdWithReview, array('basic'));
                            $url=$course->getURL();
                        }
                        $hashTag = '/reviews';
                        $url     = $url.$hashTag;
                    }
                    else{
                        $hashTag = "/reviews";
                        $url  = $institute->getURL().$hashTag;
                    }
                }
                echo json_encode(array('url'=>$url));
        }
        
	function getSearchReview($institute_id=0)
	{
		$this->init();
		$institute_id = isset($_POST['instituteId']) ? $this->input->post('instituteId') : $institute_id;
		$fromPage = isset($_POST['fromPage']) ? $this->input->post('fromPage') : '';
		$stream = isset($_POST['stream']) ? $this->input->post('stream') : '';
		$substream = isset($_POST['substream']) ? $this->input->post('substream') : '';
		$baseCourse = isset($_POST['baseCourse']) ? $this->input->post('baseCourse') : '';
		$educationType = isset($_POST['educationType']) ? $this->input->post('educationType') : '';
		$instType = isset($_POST['instType']) ? $this->input->post('instType') : '';
		
		if(isset($institute_id) && $institute_id>0)
		{
			$this->load->builder('nationalInstitute/InstituteBuilder');
            $this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
            $instituteBuilder = new InstituteBuilder;
            $instituteRepo    = $instituteBuilder->getInstituteRepository();
			$courseBuilder  = new CourseBuilder();
            $repo      = $courseBuilder->getCourseRepository();
	    
			$courses = $this->_getCourses($institute_id,$instType);

			$courses_sorted = array();
			
			if(!empty($courses)) {
				foreach ($courses as $key => $value) {
					$this->load->builder("nationalCourse/CourseBuilder");
            		
			        $courseObj = $repo->find($value, array('basic', 'course_type_information'));
			        $hierarchy = $courseObj->getPrimaryHierarchy();
			        $stream_id = $hierarchy['stream_id'];
			        $basecourse = $courseObj->getBaseCourse();
			        $base_course = $basecourse['entry'];
			        if($stream_id == $stream && $base_course == $baseCourse){
			        	$courses_sorted[] = $value;
			        }
				}
				
				$displayData['institute_course_list'] 	= $courses_sorted;
			}
			
			$instituteCourseList 	= $displayData['institute_course_list'];			
			
			$institute = $instituteRepo->find($institute_id);
			
			$displayData['instituteName'] = $institute->getName();
			$displayData['institute_id'] = $institute_id;
			$displayData['instituteUrl'] = $institute->getURL();

			$displayData['institute'] = $institute;
			$displayData['courseRepo'] = $repo;
			$courseReviews = $this->CollegeReviewLib->getCourseReviewsData($instituteCourseList,$stream,$baseCourse);
			$displayData['courseReviews'] = $courseReviews;

			foreach($displayData['courseReviews'] as $courseId=>$reviewData) {		
				if(count($displayData['courseReviews']) > 0){
					if($reviewData['overallAverageRating'] > 0 && $courseId > 0) {
						  $displayData['showCourseReviews'][$courseId]['overallAverageRating'] = $reviewData['overallAverageRating'];
						  $displayData['showCourseReviews'][$courseId]['ratingCount'] = $displayData['courseReviews']['ratingCount'];
					     }
				}
			}
	
			if(count($displayData['showCourseReviews']) == 0)
			{
				if(isset($fromPage) && $fromPage == 'D_review')
				{	
					$addHastag = '/reviews';
				}else if(isset($fromPage) && $fromPage == 'M_review')
				{
					$addHastag = '/reviews';
				}
				$url =  $this->getCollegeUrl($institute_id).$addHastag;
				
			}else if(count($displayData['showCourseReviews']) == 1){
				$getCourse = array_keys($displayData['showCourseReviews']);
				$course = $repo->find($getCourse[0], array('basic'));
				
				if(isset($fromPage) && $fromPage == 'D_review')
				{	
					$addHastag = '/reviews';
				}else if(isset($fromPage) && $fromPage == 'M_review')
				{
					$addHastag = '/reviews';
				}
				
				$url =  $this->getCollegeUrl($institute_id).$addHastag;
				
			}else if($fromPage == 'D_review'){
				if(count($displayData['showCourseReviews'])>3){
					$displayData['isScroll'] = 'overflow-y: scroll;';	
				}
				$html = $this->load->view('reviewSearchLayer',$displayData,true);
			}else if($fromPage == 'M_review'){
				$html = $this->load->view('reviewSearchLayerMobile',$displayData,true);
			}
			echo json_encode(array('url'=>$url,'html'=>$html));
		}
		
	}
	
	function _getCourses($institute_id,$instType){
	 
	    if($institute_id){
	    	$this->load->library('nationalCourse/InstituteDetailLib');
			$this->load->builder("nationalCourse/CourseBuilder");
	    	$this->instituteDetailLib = new InstituteDetailLib;
            $courseBuilder  = new CourseBuilder();
			$this->courses = $this->instituteDetailLib->getInstituteCourseIds($institute_id,$instType);
	    }
	
	    /*
	     * 	Firstly check if the Listing is still live OR not?
	     */
	    if(!(isset($this->courses->ERROR_MESSAGE) && $this->courses->ERROR_MESSAGE == "NO_DATA_FOUND")) {

			$courseList = array();
			foreach($this->courses as $course){
				if($this->courses['courseIds']){
				    $courseList = array_merge($courseList,$this->courses['courseIds']);
				}
			}
		return array_unique($courseList);
		}	    
	}
	
	function _checkMBATemplateEligibility($courseCategory, $course) {
		$flag = $this->national_course_lib->checkForMBATemplateEligibility($courseCategory, $course);
		return $flag;
	}

	function _checkEngTemplateEligibility($courseCategory) {
		$flag = $this->national_course_lib->checkForEngTemplateEligibility($courseCategory);
		return $flag;
	}
	
	/* New Implementation widget for mobile */
	function getReviewsForSliderMobile($start = 0, $count = 3 ,$courseIds = '', $stream, $baseCourse, $educationType, $substream){
		$this->init();
		
		$data = $this->crmodel->getLatestReviewsForSliderMobile($courseIds, $start, $count, $stream, $baseCourse, $educationType, $substream);
		
		$formattedData['results'] = $this->CollegeReviewLib->formatReviewDataForSliderMobile($data, $courseIds, $start, $count, 'normal');
		
		$formattedData['totalCollegeCards'] = $data['totalCollegeCards'];
		$formattedData['reviewerDetails'] = $data['reviewerDetails'];
		return $formattedData;
	}
	
	function getCollegeReviewMobile(/*$courseId,$start,$count,$subcatId,$fromPage*/)
	{
		$this->init();
		if(!$this->input->is_ajax_request()){
			echo "Invalid URL";
			return;
		}
		$displayData = array();
		if($this->input->is_ajax_request() && $fromPage != 'compare' && $fromPage != 'search'){
			$courseId = $this->input->post('courseId');
			$start = $this->input->post('start');
			$count = $this->input->post('count');
			$stream = $this->input->post('stream');
			$baseCourse = $this->input->post('baseCourse');
			$substream = $this->input->post('substream');
			$educationType = $this->input->post('educationType');
			$fromPage = $this->input->post('fromPage');
		}

		$data = $this->crmodel->getNextLatestReviewForSlider($courseId, $start, $count,$stream,$baseCourse,$substream,$educationType);
		$temp['results'] = $this->CollegeReviewLib->formatReviewDataForSlider($data, $courseId, $start, $count, 'ajax', $fromPage);
		$temp['reviewerDetails'] = $data['reviewerDetails'];
		$displayData['courseId'] = $courseId;
		$displayData['reviewData'] = $temp;
		if($fromPage == 'compare' || $fromPage == 'search'){
			$dataCompare['countReviews'] = $data['countReviews'];
			$dataCompare['reviewerDetails'] = $data['reviewerDetails'];
			$dataCompare['reviewDetails'] = $temp['results'][$courseId];
			return $dataCompare;
		}
		
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['sessionId'] = sessionId();
		//Get User Session Data
		$userSessionData = $this->getUserSessionData($displayData['userId'], $displayData['sessionId']);
		if(is_array($userSessionData)){
			$displayData['userSessionData'] = $userSessionData;
		}
		$userSessionData = Modules::run("CollegeReviewForm/CollegeReviewController/getUserSessionData",$displayData['userId'], $displayData['sessionId']);
		if(is_array($userSessionData)){
			$displayData['userSessionData'] = $userSessionData;
		}

		$displayData['stream'] = $stream;
		$displayData['educationType'] = $educationType;
		$displayData['substream'] = $substream;
		$displayData['baseCourse'] = $baseCourse;
		
		$this->load->view('ajaxSliderView',$displayData);
	}
	
	function getReviewDetailbyReviewId($reviewId)
	{
		$this->init();
		$reviewDetails = $this->crmodel->getReviewDetailbyReviewId($reviewId);
		$description['placementDescription'] = $reviewDetails['placementDescription'];
		$description['infraDescription'] = $reviewDetails['infraDescription'];
		$description['facultyDescription'] = $reviewDetails['facultyDescription'];
		$description['reviewDescription'] = $reviewDetails['reviewDescription'];
		echo json_encode($description);
	}


	function getReviewsForHomepageMobile($start = 0,$count = 10,$stream,$page='homepage',$orderOfReview = 'latest',$baseCourse,$educationType,$substream){

		
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

			$courseIds = implode(',', $courseIds);
		}
		
		

		if($orderOfReview == 'latest'){
			$courseIds = '';
			$data = $this->CollegeReviewLib->getLatestReviewsForSliderMobile($courseIds, $start, $count, $stream,$baseCourse,$educationType,$substream ,$page,$subQuery);
		} else { 

			$data = $this->CollegeReviewLib->getPopularReviewsForSliderMobile($courseIds, $start, $count, $stream ,$page,$subQuery,$courseRankMapping,$baseCourse,$educationType,$substream);		
		}

		$formattedData['results'] = $this->CollegeReviewLib->formatReviewDataForSliderMobile($data, $courseIds, $start, $count,'normal',$page);
		$formattedData['totalCollegeCards'] = $data['totalCollegeCards'];
		$formattedData['reviewerDetails'] = $data['reviewerDetails'];
		$formattedData['totalReviewCount'] = $data['totalReviewCount'];
		return $formattedData;
		
	}
	
}
