<?php

class ShortlistMobile extends ShikshaMobileWebSite_Controller
{     	
	function __construct()
	{
		parent::__construct();
		$this->load->config('mcommon5/mobi_config');
		$this->load->helper('mcommon5/mobile_html5');
		$this->load->library('category_list_client');
	}
	
	
	function shortlistPage($params, $newUrlFlag = false)
	{
		ini_set("memory_limit","850M");
		ob_start();
		global $checkCmpIdVal;
		// check compare btn is added or not
		$cookieCmp = 'compare-mobile-global-categoryPage';
		$courseCmpTot = array();
		$checkCmpIdVal = array();
		if($_COOKIE[$cookieCmp] !=''){
			$courseCmpTot = explode('|||',$_COOKIE[$cookieCmp]);
			foreach($courseCmpTot as $courseCmpTot)
			{
			$expVal = explode('::',$courseCmpTot);
			array_push($checkCmpIdVal,$expVal[1]);	
			}
		}
		
		//request brochureUrl from courseId
		$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
		$brochureURL = $this->national_course_lib;
		
		$this->load->model('listing/shortlistlistingmodel');
                $shortlistModel = new shortlistlistingmodel;
		$validity = $this->checkUserValidation();
				
		//1. Fetch the Shortlisted courses for the user
		//A. Get the course list from Cookie
		//B. If not found in Cookie, get the Course list from DB
		$courseShortArray[] = '' ;
		$cookieShrt = 'mobile-shortlisted-courses';
		if($_COOKIE[$cookieShrt] !='' && count($_COOKIE[$cookieShrt]) >0 && $validity == "false"){
			$courseShortArray = explode('|',$_COOKIE[$cookieShrt]);
		}else{
			if($validity != "false")
			{ 
				$resultArray = $shortlistModel->getUserShortlistedCoursesMyShortlist(array('userId'=>$validity[0]['userid'],'scope'=>'national'));
				$oldShortlistedCourseIds = array();
				//Processing shortlisted course ids
				foreach($resultArray as $shortlistedCourses) {
					$resultArray['courseIds'][] = $shortlistedCourses['courseId'];
					//condition to check if new my shortlist is not present in course ids array
					if(!((strpos($shortlistedCourses['pageType'], "NM_") !== false) || (strpos($shortlistedCourses['pageType'], "ND_") !== false))) {
						$oldShortlistedCourseIds['courseIds'][] = $shortlistedCourses['courseId'];
					}
					else {
						$newShortlistedCourses['courseIds'][] = $shortlistedCourses['courseId'];
					}
				}
				if(isset($resultArray['courseIds']) && $resultArray['courseIds'][0] !=''){
					$courseShortArray = $resultArray['courseIds'];
					setcookie($cookieShrt,implode('|',$courseShortArray),time()+3600*30*24,'/',COOKIEDOMAIN); 
				}else{
					unset($_COOKIE[$cookieShrt]);
					setcookie($cookieShrt,'',time()-3600);
				      }
				
			}
		}
		
		if(!empty($oldShortlistedCourseIds['courseIds']) || (count($newShortlistedCourses['courseIds']) == count($resultArray['courseIds']))) {
			$courseShortArray = $oldShortlistedCourseIds['courseIds'];
		}
		
		$displayData['brochureURL'] = $brochureURL;	
                $displayData['courseShortArray']  = $courseShortArray;

		//2. Once the courses are fetched, get the Objects for them and their institute
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$courseRepository = $listingBuilder->getCourseRepository();
		$instituteIDs = array();
		$i = 0;
		foreach($courseShortArray as $element){
			if($element>0){
				$course = $courseRepository->find($element);
				$this->load->model('listing/coursemodel');
				$isAbroadCourse = $this->coursemodel->isStudyAboradListing($element, 'course');
				if($isAbroadCourse){
					show_404();
					exit;
				}
				$institute = $course->getInstId();
				$data[$i]['courseId'] = $course->getId();
				$data[$i]['instituteName'] =  $course->getInstituteName();
				$instituteIDs[$i][$institute] = $data[$i]['courseId'];
	
				$i++;
			}
		}

		$appliedInstitutes = array();
		for($j=0;$j<count($instituteIDs);$j++){
			$instituteArr = array();
			$instituteArr = $instituteRepository->findWithCourses($instituteIDs[$j]);
			foreach($instituteArr as $instituteObj){
				$appliedInstitutes[] = $instituteObj;
			}
		}

		$displayData['institutes'] = $appliedInstitutes;
		$displayData['instituteRepository'] = $instituteRepository;
		$displayData['courseRepository'] = $courseRepository;

		$validity = $this->checkUserValidation();
		if($validity != "false")
		{
			$displayData['validateuser'] = $validity;
			$displayData['userStatus'] = $validity;
		}
		$this->getSEODetails($displayData);

		$displayData['boomr_pageid'] = "ShortlistPage";

		$displayData['beaconTrackData'] = array(
			'pageIdentifier' => 'shortlistedColleges',
			'pageEntityId' => '0',
			'extraData' => array(
				'url'=>get_full_url(),
			)
		);

		//below line is used for conversion tracking purpose for compare tool
		$displayData['comparetrackingPageKeyId'] = 511;
		$displayData['tracking_keyid'] = MOBILE_NL_SHORTLIST_HOME_TUPLE_SETLAYER_DEB;
		//3. Display the Courses
                $this->load->view('shortlistHomepage',$displayData);
	}
	
	
	function getSEODetails(& $displayData){
		$validity = $this->checkUserValidation();
		if($validity != "false")
		{
			$displayName = $validity[0]['displayname'];
			$displayData['m_meta_title'] = "Shortlisted Colleges by $displayName";
			$displayData['m_meta_description'] = "List of colleges shortlisted by $displayName to pursue graduate, post graduate, professionals, degree, diploma, and certification courses.";
		}
		else{
			$displayData['m_meta_title'] = "Shortlisted Colleges";
			$displayData['m_meta_description'] = "List of colleges shortlisted to pursue graduate, post graduate, professionals, degree, diploma, and certification courses.";
		}
		$displayData['pageType'] = "articlePage";
		$displayData['addNoFollow'] = "true";
		$displayData['m_canonical_url'] = SHIKSHA_HOME."/shortlisted-colleges";					
	}
	
	
	function addNewMShortlistedCourse()    // add New shortlisted course in Db with new sessionId
	{
		$cookieShrtName = 'mobile-shortlisted-courses';
		$courseId = $_COOKIE[$cookieShrtName];
		if($courseId !='')
		{
			$courseId = explode('|',$courseId);
			$pageType = 'mobileCategoryPage';
			foreach($courseId as $courseId)
			{
			      Modules::run('categoryList/AbroadCategoryList/updateShortListedCourse',$courseId,'add',$pageType,'national');
			}
		}
		
	}
	
	/**
	* Put shortList Cookie data into table
	*
	**/
	function updateShortlistedUser()
	{       
		$userId = $this->input->post('userId');
                    if($userId)
                    {
                        $data[0]['userId'] = $userId;
                        $data[0]['status'] = 'live';
                        $data[0]['sessionId'] = sessionId ();
                        $shortlistlistingmodel = $this->load->model( 'listing/shortlistlistingmodel' );
                        $shortlistlistingmodel->putShortListCouresFromCookieToDB($data,false);
                    }
	}

	function redirectToMBA(){
               $url = SHIKSHA_MANAGEMENT_HOME;
               header("Location: $url",TRUE,301);
               exit;
	}

	//Go to new shortlist page for all courses
	function redirect301(){
		$url = SHIKSHA_HOME.'/resources/colleges-shortlisting';
	    header("Location: $url",TRUE,301);
	    exit;	
	}

}
