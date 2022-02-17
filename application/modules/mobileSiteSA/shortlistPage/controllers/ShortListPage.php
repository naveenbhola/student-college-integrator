<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ShortlistPage extends ShikshaMobileWebSite_Controller
{
	function __construct(){
		parent::__construct();
	}
	
	private function _init(){
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder 					= new ListingBuilder;
	    $this->abroadCourseRepository 		= $listingBuilder->getAbroadCourseRepository();
	    $this->abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
		$this->abroadListingCommonLib   	= $this->load->library('listing/AbroadListingCommonLib');
		$this->shortlistingLib 				= $this->load->library("listing/ShortlistListingLib");
	}
	
	/*This function fetches data for courses the user has shortlisted */
	function getShortlistedCourses($sliceNumber = 0,$isAjax){
		$this->_init();
		$data = array('scope' => 'abroad');
		$validity = $this->checkUserValidation();
		if($validity !== "false")
		{
			$data['userId'] = $validity[0]['userid'];
		}

		$data 						= $this->shortlistingLib->getShortlistedCoursesDetail($data);
		$shortlistedCourses 		= $data['courses'];
		$universities 				= $data['universities'];

		$finalList 						= array();
        $remarketingCategoryIds 		= array();
        $remarketingSubCategoryIds 		= array();
        $remarketingDesiredCourseIds 	= array();
        $remarketingCountryIds 			= array();
        $remarketingCityIds 			= array();
        $shortListedCourseIds 			= array_keys($shortlistedCourses);

        if(count($shortListedCourseIds))
        {
            $coursesCategoryArray = $this->abroadListingCommonLib->getCategoryOfAbroadCourse($shortListedCourseIds);
        }
		
		foreach($shortlistedCourses as $course)
		{
			$tempUniv = clone($universities[$course->getUniversityId()]);
			$tempUniv->addCourse($course);
			$finalList[] 					= $tempUniv;
            $remarketingCategoryIds[] 		= $coursesCategoryArray[$course->getId()]['categoryId'];
            $remarketingSubCategoryIds[] 	= $coursesCategoryArray[$course->getId()]['subcategoryId'];
            $remarketingDesiredCourseIds[] 	= $course->getDesiredCourseId();
            $remarketingCountryIds[] 		= $course->getMainLocation()->getCountry()->getId();
            $remarketingCityIds[] 			= $course->getMainLocation()->getCity()->getId();
		}

		$displayData = array();
		
		//prepare data for rmc tab
		if(!empty($validity[0]['userid']))
		{
			$this->getRateMyChanceCourses($validity[0]['userid'],$displayData);
		}
		else
		{
			$displayData['rmcCourses'] = 0;
		}
		$displayData['counsellorData'] = $this->abroadListingCommonLib->checkIfUniversityHasCounsellor(array_keys($universities));
		$displayData['shortlistData'] = array_slice($finalList,$sliceNumber,RMC_TAB_TUPLE_COUNT);
		$displayData['userShortlistedCourses'] = $shortListedCourseIds;	// This is used within the tuple to determine which class to give the star filled status
		$displayData['isShortlistPage'] = true;
		$displayData['trackForPages'] = true; //For JSB9 Tracking
        // get course id whose tuple is to be focussed
		$displayData['courseToBeFocussed'] = $this->_getCourseToBeFocussed();

      
       
		//$courseObjs = $something->find($something);

        $this->_prepareTrackingData($displayData, array("remarketingCategoryIds" => $remarketingCategoryIds,
        												"remarketingSubCategoryIds" => $remarketingSubCategoryIds,
        												"remarketingDesiredCourseIds" => $remarketingDesiredCourseIds,
        												"remarketingCountryIds" => $remarketingCountryIds,
        												"remarketingCityIds" => $remarketingCityIds
        												)
        							);


		
		$displayData['rateMyChanceCtlr'] = Modules::load('rateMyChancePage/rateMyChance');
		$displayData['validateuser'] = $this->checkUserValidation();
		if($displayData['validateuser'] != "false"){
			$shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
			$displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
		}else{
			$displayData['userRmcCourses'] = array();
		}
		if($isAjax !== "true")
		{
			$displayData['showShortlistTab'] = (integer)$this->input->get('shortlistTab');
			$displayData['compareLayerTrackingSource'] = 607;
			$displayData['compareButtonTrackingId'] = 664;
			$displayData['compareCookiePageTitle'] = "Saved Courses Page";
			$this->load->view("shortlistPage/shortlistOverview",$displayData);
		}
		else
		{
			echo $this->load->view("shortlistPage/shortlistListings",$displayData,true);
		}
	
}
	private function _prepareTrackingData(&$displayData, $dataArray)   
    {    
            
            $displayData['beaconTrackData'] = array(
                                              'pageIdentifier' => 'savedCoursesPage',
                                              'pageEntityId' => '0',
                                              'extraData' => null
                                              );
            
              $displayData['googleRemarketingParams'] = array(
												"categoryId" => implode(',', array_unique($dataArray["remarketingCategoryIds"])),
												"subcategoryId" => implode(',', array_unique($dataArray["remarketingSubCategoryIds"])),
												"desiredCourseId" => implode(',', array_unique($dataArray["remarketingDesiredCourseIds"])),
												"countryId" => implode(',', array_unique($dataArray["remarketingCountryIds"])), 
												"cityId" => implode(',', array_unique($dataArray["remarketingCityIds"]))
												);
    }	

	/*
	 * this function reads the course id whose tuple is to be focussed/ any other action
	 * from Notification cookie.
	 */
	private function _getCourseToBeFocussed()
	{
		$abroadNotificationsLib = $this->load->library('studyAbroadCommon/AbroadNotificationsLib');
		$notificationCourseId = $abroadNotificationsLib->readRemoveNotificationCookie();
		return ($notificationCourseId>0?$notificationCourseId:FALSE);
	}

/*This function fetches data for courses the user has done rate my chance*/
	function getRateMyChanceCourses($userId ,&$displayData)
	{
		$this->shikshaApplyCommonLib	    = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
		$this->abroadListingCommonLib   	= $this->load->library('listing/AbroadListingCommonLib');  // for counsellor & category data
		$this->abroadCategoryPageLib	    = $this->load->library('categoryList/AbroadCategoryPageLib');
		$this->rmcPostingLib	    		= $this->load->library('shikshaApplyCRM/rmcPostingLib');

		///var/www/html/shiksha/application/modules/ShikshaApply/shikshaApplyCRM/libraries/rmcPostingLib.php
		// get RMC courses on which current user has made response
		$displayData['RMCCourseAndUnivObjs'] = $this->shikshaApplyCommonLib->getRMCCoursesAndUniversitiesByUser($userId);

		$rmcCourses 		= $displayData['RMCCourseAndUnivObjs']['courses'];
		//_p(count($rmcCourses));
		$displayData['rmcCourses'] = count($rmcCourses);

		// now prepare subcategories data for all courses
		if(!is_null($displayData['RMCCourseAndUnivObjs']['courses']))
		{
			//prepare all unique courses
			$allCourses = array_unique(array_keys($displayData['RMCCourseAndUnivObjs']['courses']));
			//if we have unique and nozero courseIds then fetch their category data
			if(count($allCourses)>0)
			{
				$displayData['categoryData'] = $this->abroadListingCommonLib->getCategoryOfAbroadCourse($allCourses);
			}
		}

 		// get counsellor data
		if(!is_null($displayData['RMCCourseAndUnivObjs']['universities']))
		{
			$counsellorData = $this->abroadListingCommonLib->checkIfUniversityHasCounsellor(array_keys($displayData['RMCCourseAndUnivObjs']['universities']));
			$displayData['rmcCounsellorData'] = $counsellorData;
		}
		
		//get Ratings for user
		$displayData['userRmcRatings']= $this->shikshaApplyCommonLib->getUserRmcRatings($userId);
		//get comments for user
		$displayData['userRmcComments'] = $this->abroadCategoryPageLib->getCounsellorRatingComments($allCourses,$userId);
		//slice data for pagination
		
		$displayData['RMCCourseAndUnivObjs']['courses'] = array_slice($displayData['RMCCourseAndUnivObjs']['courses'],0,RMC_TAB_TUPLE_COUNT);
	}


	/*This function fetches data for courses the user has done rate my chance*/
	function getRateMyChanceCoursesForAjax($offset = 0,$loadMore=false)
	{
		$displayData = array();

		$validity = $this->checkUserValidation();
		if($validity !== "false")
		{
			$userId = $validity[0]['userid'];
		}

		$this->shikshaApplyCommonLib	    = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
		$this->abroadListingCommonLib   	= $this->load->library('listing/AbroadListingCommonLib');  // for counsellor & category data
		$this->abroadCategoryPageLib	    = $this->load->library('categoryList/AbroadCategoryPageLib');
		$this->rmcPostingLib	    		= $this->load->library('shikshaApplyCRM/rmcPostingLib');
		$this->shortlistListingLib 			= $this->load->library('listing/ShortlistListingLib');
		///var/www/html/shiksha/application/modules/ShikshaApply/shikshaApplyCRM/libraries/rmcPostingLib.php
		// get RMC courses on which current user has made response
		
		$shortlistedRmc = $this->shortlistListingLib->fetchIfUserHasShortListedCourses(array('userId'=>$userId));
		$displayData['userShortlistedCourses'] =  $shortlistedRmc['courseIds'];
		
		$displayData['RMCCourseAndUnivObjs'] = $this->shikshaApplyCommonLib->getRMCCoursesAndUniversitiesByUser($userId);
		$rmcCourses 			   = $displayData['RMCCourseAndUnivObjs']['courses'];
		$displayData['rmcCourses'] = count($rmcCourses);
		
		// now prepare subcategories data for all courses
		if(!is_null($displayData['RMCCourseAndUnivObjs']['courses']))
		{
			//prepare all unique courses
			$allCourses = array_unique(array_keys($displayData['RMCCourseAndUnivObjs']['courses']));
			//if we have unique and nozero courseIds then fetch their category data
			if(count($allCourses)>0)
			{
				$displayData['categoryData'] = $this->abroadListingCommonLib->getCategoryOfAbroadCourse($allCourses);
			}
		}

 		// get counsellor data
		if(!is_null($displayData['RMCCourseAndUnivObjs']['universities']))
		{
			$counsellorData = $this->abroadListingCommonLib->checkIfUniversityHasCounsellor(array_keys($displayData['RMCCourseAndUnivObjs']['universities']));
			$displayData['rmcCounsellorData'] = $counsellorData;
		}
		
		//get Ratings for user
		$displayData['userRmcRatings']= $this->shikshaApplyCommonLib->getUserRmcRatings($userId);
		
		//get comments for user
		$displayData['userRmcComments'] = $this->abroadCategoryPageLib->getCounsellorRatingComments($allCourses,$userId); 
		$displayData['RMCCourseAndUnivObjs']['courses'] = array_slice($displayData['RMCCourseAndUnivObjs']['courses'],$offset,RMC_TAB_TUPLE_COUNT);
		
		$displayData['rateMyChanceCtlr'] = Modules::load('rateMyChancePage/rateMyChance');
		$displayData['validateuser'] = $this->checkUserValidation();
		if($displayData['validateuser'] != "false"){
			$shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
			$displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
		}else{
			$displayData['userRmcCourses'] = array();
		}
		
		echo $this->load->view("shortlistPage/rateMyChanceListings",$displayData,true);
	}
}
