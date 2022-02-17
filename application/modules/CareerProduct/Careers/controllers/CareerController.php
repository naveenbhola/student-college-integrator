<?php
/*

   Copyright 2013 Info Edge India Ltd

   $Author: Pranjul

   $Id: CareerController.php

 */

class CareerController extends MX_Controller
{
	private $careerRepository;
	private $expressInterestFirst;
	private $expressInterestSecond;

        function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();
		}

	/*
	 @name: getCareerIds
	 @description: this is for getting Career Ids by passing in it 3 arguments.This function gets call inside applyAlgorithm function.
	 @param string $userInput: expressInterestFirst,expressInterestSecond,stream
	*/
	public function getCareerIds($expressInterestFirst,$expressInterestSecond,$stream)
	{	
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$this->careerRepository = $careerBuilder->getCareerRepository();
		$careerIds = $this->careerRepository->getCareerIds($expressInterestFirst,$expressInterestSecond,$stream);
		return $careerIds;
	}
	/*
	 @name: recommendCareerOption
	 @description: this is for Suggest Different Career Options.We are also setting some cookie in this function,they will use on career detail page.
	 @param string $userInput: expressInterestFirst,expressInterestSecond,stream
	*/
	public function recommendCareerOption()
        {    	
		$this->init();

		//Redirect to new url
		$currenturl = $_SERVER['SCRIPT_URI'];
		$seo_params = explode('-',$currenturl);
		if($seo_params[1] == 'counselling'){
			$newUrl = SHIKSHA_HOME.'/careers/counselling';
             header("Location: $newUrl",TRUE,301);

		}

		$finalArr['validateuser'] = $this->userStatus;
		
		$expressInterestObjectParams=(json_decode($_COOKIE['expressInterestDetail']));
		$expressInterestDetailsArray=array();
		if (is_object($expressInterestObjectParams)) {
		    $expressInterestDetailsArray = get_object_vars($expressInterestObjectParams);
		}
		
		if(!isset($_COOKIE['streamSelected'])){
			header('location:'.CAREER_HOME_PAGE);
			exit;
		}
		if(!isset($_COOKIE['expressInterestDetail'])){
			header('location:'.CAREER_EXPRESSINTEREST_PAGE);
			exit;
		}
		$stream = $_COOKIE['streamSelected'];
		$expressInterestFirst = $expressInterestDetailsArray['ei1'];
		$expressInterestFirstName = $expressInterestDetailsArray['display1'];
		$expressInterestSecond = $expressInterestDetailsArray['ei2'];
		$expressInterestSecondName = $expressInterestDetailsArray['display2'];

		$expressInterestIds = array();
		$careerIds = array();
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$this->careerRepository = $careerBuilder->getCareerRepository();
		$this->careerServiceRepository = $careerBuilder->getCareerService();
		$this->load->model('Careers/careermodel');
		$this->careerModel = new Careermodel();	
		$suggestedCareerDetails = $this->careerModel->getSuggestedCareerDetails();
		$this->load->library('CareerUtilityLib');
		$careerUtilityLib =  new CareerUtilityLib;
		$formatedSuggestedCareerData = $careerUtilityLib->getFormatedSuggestedCareerData($suggestedCareerDetails,$expressInterestFirst,$expressInterestSecond,$expressInterestFirstName,$expressInterestSecondName,$stream);
		$finalArr['result'] = $this->careerServiceRepository->getSuggestedCareers($formatedSuggestedCareerData);
		$finalArr['expressInterestFirstName'] = $expressInterestFirstName;
		$finalArr['expressInterestSecondName'] = $expressInterestSecondName;
		$finalArr['stream'] = $stream;
		$finalArr['trackForPages']         = true; //For JSB9
		$finalArr['current_page_url'] =  CAREER_SUGGESTION_PAGE;

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_CareerHome','pageType'=>'counselling','entity_id'=>$stream);
        $finalArr['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		//below line id used to store the required infromation in beacon varaible for tracking purpose    		
		$finalArr['beaconTrackData'] = $this->_prepareBeaconData('careerCounselling');
    	$finalArr['beaconTrackData']['extraData']['careerInterestFirst'] = $expressInterestFirstName;
    	if(!empty($expressInterestSecondName)){
    		$finalArr['beaconTrackData']['extraData']['careerInterestSecond'] = $expressInterestSecondName;	
    	}
    	$this->load->library('CareerUtilityLib');
		$careerUtilityLib =  new CareerUtilityLib;
		$finalArr['gtmParams'] = $careerUtilityLib->getGTMArray($finalArr['beaconTrackData']);

		//to get featured college
		$featuredColleges = $this->getFeaturedColleges();
		$finalArr['featuredColleges'] = $featuredColleges;
    		
		$this->load->view('Careers/career-suggestion',$finalArr);
        }
	
	/*
	 @name: getCareerDetialPage
	 @description: this is for getting Career Details and this function gets call from inside Apply Algorithm function.
	 @param string $userInput: expressInterestFirst,expressInterestSecond,stream
	*/
	public function getCareerDetailPage($careerId){
		if(is_numeric($careerId)=='' || $careerId <=0){
            show_404();die();
        }

        //Redirect to new url
        $currenturl = $_SERVER['SCRIPT_URI'];
		$seo_params = explode('-',$currenturl);
		$careerTitle = '';
		if($seo_params[count($seo_params)-2] == 'cc'){
			for($i=2;$i<count($seo_params)-2;$i++){
				$careerTitle[] = $seo_params[$i];
			}
			$careerTitle = implode('-',$careerTitle);
			if($careerTitle != ''){
				$newUrl = SHIKSHA_HOME.'/careers/'.$careerTitle.'-'.$careerId;
             	header("Location: $newUrl",TRUE,301);
			}			
		}
		$this->init();
		$finalArr['validateuser'] = $this->userStatus;
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$this->careerRepository = $careerBuilder->getCareerRepository();
		$this->careerData = $this->careerRepository->find($careerId);
		if(empty($this->careerData)){
			redirect(CAREER_HOME_PAGE,'location',301);exit();
        }
		$hierarchyIds = $this->careerData->getHierarchyId();
		$courseIds    = $this->careerData->getCourseId();
		$mappedCategories = array();
		$mappedSubCategories = array();
		if(!empty($hierarchyIds)){
			$this->rankingCommonLib = $this->load->library('rankingV2/RankingCommonLibv2');
			$this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
			$rankingURLManager  = RankingPageBuilder::getURLManager();
			$countVal = 0;
			$this->load->builder('listingBase/ListingBaseBuilder');
	        $ListingBaseBuilder = new ListingBaseBuilder();
	        $this->hierarchyRepo = $ListingBaseBuilder->getHierarchyRepository();
	        $hierarchyMappingData = $this->hierarchyRepo->getBaseEntitiesByHierarchyId($hierarchyIds);
			$index = 0;
			$counterRank = 0;
			$beaconTrackData = array();
			foreach($hierarchyIds as $hierarchyId){
				$filters = array();
				$filters['stream_id'] = intval($hierarchyMappingData[$hierarchyId]['stream_id'])?intval($hierarchyMappingData[$hierarchyId]['stream_id']) : 0;
				$filters['substream_id'] = intval($hierarchyMappingData[$hierarchyId]['substream_id'])?intval($hierarchyMappingData[$hierarchyId]['substream_id']) : 0;
				$filters['specialization_id'] = intval($hierarchyMappingData[$hierarchyId]['specialization_id'])?intval($hierarchyMappingData[$hierarchyId]['specialization_id']) : 0;
				$filters['base_course_id'] = intval($courseIds[$index]) ? intval($courseIds[$index]) : 0;
				$index ++;
				//_p($filters);die;
				$rankPageData = $this->rankingCommonLib->getRankingDetailsByFilter($filters);
				$beaconTrackData[] = $filters;
				foreach($rankPageData as $key=>$value){
					$finalArr['rankingPageDetails'][$counterRank]['NAME'] = $value['ranking_page_text'];
					$finalArr['rankingPageDetails'][$counterRank]['URL'] = $rankingURLManager->getRankingPageURLByRankingPageId($value['id'], $value['ranking_page_text']);
					$counterRank++;
				}
				$countVal++;
			}
		}	
		$googleRemarketingParams = array(
			'categoryId' => $mappedCategories,
			'subcategoryId' => $mappedSubCategories,
			'countryId' => 2
		);
		$finalArr['googleRemarketingParams'] = $googleRemarketingParams;

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_CareerDetailPage','entity_id'=>$careerId);
        $finalArr['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');
		
		$indiawhereToStudyCountArr = array();
		$abroadwhereToStudyCountArr = array();
		$this->load->library('CareerUtilityLib');
		$careerUtilityLib =  new CareerUtilityLib;
		if(empty($this->careerData)){
			//			$careerUtilityLib->careerErrorPage('404');
			header("Sorry, we couldn't find the page you requested.", true, 404);
			$this->load->view('Careers/404Page');
		}else{
			if(isset($_COOKIE['expressInterestDetail']) && isset($_COOKIE['streamSelected'])){
				$expressInterestObjectParams=(json_decode($_COOKIE['expressInterestDetail']));
				$expressInterestDetailsArray=array();
				if (is_object($expressInterestObjectParams)) {
				    $expressInterestDetailsArray = get_object_vars($expressInterestObjectParams);
				}
				if(!isset($_COOKIE['streamSelected'])){
					header('location:'.CAREER_HOME_PAGE);
					exit;
				}
				if(!isset($_COOKIE['expressInterestDetail'])){
					header('location:'.CAREER_EXPRESSINTEREST_PAGE);
					exit;
				}
			  } 
				$stream = $_COOKIE['streamSelected'];
				$expressInterestFirst = $expressInterestDetailsArray['ei1'];
				$expressInterestSecond = $expressInterestDetailsArray['ei2'];
				//if((isset($expressInterestFirst) || isset($expressInterestSecond)) && isset($stream)){
					$this->careerServiceRepository = $careerBuilder->getCareerService();
					//$finalArr['recommendedOptions'] = $this->careerServiceRepository->getRecommendedCareers($expressInterestFirst,$expressInterestSecond,$stream,$careerId);
					$finalArr['recommendedOptions'] = $this->careerServiceRepository->getRecommendedCareers($careerId);
				//}
				//else{
					//$finalArr['recommendedOptions']=array();
				//}
			//}
			$careerInformation = $this->careerData->getOtherCareerInformation();
			$finalArr['metaTagsDescription'] = $careerInformation['metaTagsDescription'];
			$finalArr['metaTagsKeywords'] = $careerInformation['metaTagsKeywords'];
			$finalArr['instituteDetailIndia'] = $careerUtilityLib->formatCareerSectionAndCourseIds('india',$careerInformation);
			$finalArr['instituteDetailAbroad'] = $careerUtilityLib->formatCareerSectionAndCourseIds('abroad',$careerInformation);
			$finalArr['result'] = $this->careerData;
			$finalArr['trackForPages']         = true; //For JSB9
			$finalArr['current_page_url'] = SHIKSHA_HOME.$finalArr['result']->getCareerUrl();
			$this->load->config('CP/CollegePredictorConfig',TRUE);
			$finalArr['collegePredictorConfig'] = $this->config->item('settings','CollegePredictorConfig');
			$finalArr['categoryPageURLData'] = $this->getCategoryPageUrls($careerId);

    		//below line is used to store the required infromation in beacon varaible for tracking purpose
    		$beaconTrackData = $careerUtilityLib->prepareBeaconTrackData($beaconTrackData,$careerId);
			$finalArr['beaconTrackData'] = $beaconTrackData;
    		$finalArr['gtmParams'] = $careerUtilityLib->getGTMArray($beaconTrackData);

    		//below line is used for conversion tracking purpose for registration in carrer page
    		$finalArr['careerTrackingPageKeyId']=68;
    		
    		// get shiksha banner criteria to show different banners to different career pages
    		$criteriaArray = $this->_getShikshaBannerCriteria($finalArr['result']);
    		$finalArr['criteriaArray'] = $criteriaArray;

			//to get featured college
			$featuredColleges = $this->getFeaturedColleges($careerId);
			$finalArr['featuredColleges'] = $featuredColleges;
			//_p($finalArr);die;
			$this->load->view('Careers/careerDetailPage',$finalArr);
		}

	}
	
	function getFeaturedColleges($careerId){
		
		// fetch the featured institutes from DB and rotate it on every 20 mins
		$allFeaturedCollegeData = $this->getFeaturedCollegesData($careerId);

		foreach ($allFeaturedCollegeData as $id => $featuredCollegeData) {
			
			$currentMinute = date("i");
			$rotationSlot = ceil($currentMinute/20);
			
			if (empty($featuredCollegeData)) {
			 	return array();
			}
			
			for($i = 1; $i < $rotationSlot; $i++){

				$firstEle = $featuredCollegeData[0];
				unset($featuredCollegeData[0]);
				array_push($featuredCollegeData, $firstEle);
				$featuredCollegeData = array_values($featuredCollegeData);
			}

			$allFeaturedCollegeData[$id] = $featuredCollegeData;
		}

		return $allFeaturedCollegeData;
	}

	function setCache($key,$data,$timePeriod){
		$this->load->builder('CollegeReviewForm/CollegeReviewsBuilder');
		$this->collegeReviewsBuilder = new CollegeReviewsBuilder;


		$reviewPageObject = $this->collegeReviewsBuilder->getReviewsPageRepository();
		$reviewPageObject->setCacheData($key,$data,$timePeriod);

		unset($reviewPageObject);
	}

	function getDataFromCache($key){
		$this->load->builder('CollegeReviewForm/CollegeReviewsBuilder');
		$this->collegeReviewsBuilder = new CollegeReviewsBuilder;


		$reviewPageObject = $this->collegeReviewsBuilder->getReviewsPageRepository();
		$cacheData = $reviewPageObject->getCacheData($key);

		unset($reviewPageObject);
		return $cacheData;

	}

	function rotateFeatureCollege($featuredCollegeData,$rotationCacheKey){
		$rotationSequence = $this->getDataFromCache($rotationCacheKey);

		if($rotationSequence == false){

			foreach ($featuredCollegeData as $key => $value) {
				$rotationSequence[] = $value['title'];
			}

		} else {

			foreach ($featuredCollegeData as $key => $value) {
				$tempArrayForTitle[] = $value['title'];
			}

			foreach ($rotationSequence as $sequence => $title) {
				if(!in_array($title, $tempArrayForTitle)){
					$rotationSequence[$sequence] = "";
					$newFeature[$sequence] = $title;
				}
			}


			foreach ($rotationSequence as $sequence => $title) {
				if(array_key_exists($sequence, $newFeature)){
					$rotationSequence[$sequence] = $featuredCollegeData[$sequence]['title'];
				}
			}

			$firstValue = array_shift($rotationSequence);
			
			array_push($rotationSequence,$firstValue);
		}

		return  $rotationSequence;

	}	

	function checkFeaturedCollegeStatusSUMS($clientId){
		
		$sumsmodel = $this->load->model('sumsmodel','sums');

		//$DerivedProductId,$BaseProductId define values for them
        $subscriptionStatus = $sumsmodel->getSubscriptionStatusForClient($clientId,$DerivedProductId,$BaseProductId);


        if($subscriptionStatus>0){
        	return true;
        } else{
        	return false;
        }
	}

	function getFeaturedCollegesData($careerId){
		$this->load->model('Careers/careermodel');
		$this->careerModel = new Careermodel();
		$featuredCollegeData = $this->careerModel->getFeaturedCollegesData($careerId);

		return $featuredCollegeData;

	}

	function updateFeaturedCollegeStatus($clientId){
		$this->load->model('Careers/careermodel');
		$this->careerModel = new Careermodel();
		$featuredCollegeData = $this->careerModel->updateClientData($clientId);
	}

	function checkForClientStatus($featuredCollegeData){

		foreach ($featuredCollegeData as  $key => $value) {
				if(!$this->checkFeaturedCollegeStatusSUMS($value['clientId'])){
					$this->updateFeaturedCollegeStatus($value['clientId']);
					unset($featuredCollegeData[$key]);   //check for this logic
				}
		}

		return $featuredCollegeData;

	}


		/*******************************************************************************************************
    This function is used to call model to get career list for auto suggestor and get streams from the config file
    and display the CAREER HOMEPAGE. 
    *****************************************************************************************/
    
    function getCareerHomepage(){
		$this->init();

		$currenturl = $_SERVER['SCRIPT_URI'];
		$seo_params = explode('-',$currenturl);
		$careerTitle = '';
		if($seo_params[1] == 'options' && $seo_params[2] == 'after' && $seo_params[3] == '12th'){
			$newUrl = SHIKSHA_HOME.'/careers';
            header("Location: $newUrl",TRUE,301);

		}

		$this->load->library('CareerConfig');
		$streamArray=CareerConfig::$streamVariables;
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$this->careerRepository = $careerBuilder->getCareerRepository();
		$careersList = $this->careerRepository->getCareerList('BASIC_INFO');
		$stringOfCareerTitles = '';
		$stringOfCareersSeoUrl = '';
		
		foreach ($careersList as $career){
		    $careerTitle = addslashes($career->getName());
		    $stringOfCareerTitles .= ($stringOfCareerTitles=='')?"'$careerTitle'":",'$careerTitle'";
		    $careerSeoUrl = $career->getCareerUrl();
		    $stringOfCareersSeoUrl .= ($stringOfCareersSeoUrl=='')?"'$careerSeoUrl'":",'$careerSeoUrl'";
		}
		
		$data['stringOfCareerTitles'] = $stringOfCareerTitles;
		$data['stringOfCareerUrl'] = $stringOfCareersSeoUrl;
		$data['validateuser'] = $this->userStatus;
		$data['streamArray']=$streamArray;
		$data['streamArrayLength'] =count($streamArray);
		$data['trackForPages']         = true; //For JSB9
		$data['current_page_url'] = CAREER_HOME_PAGE; 
		$this->load->library('CareerUtilityLib');
		$careerUtilityLib =  new CareerUtilityLib;

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_CareerHome');
        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');
		
		//below line id used to store the required infromation in beacon varaible for tracking purpose
		$beaconTrackData = $this->_prepareBeaconData('careerHomePage');
		$data['gtmParams'] = $careerUtilityLib->getGTMArray($beaconTrackData);
		$data['beaconTrackData'] = $beaconTrackData;
		$this->load->view('Careers/career-homepage',$data);
        }

        function _prepareBeaconData($pageIdentifier){
        	$beaconTrackData = array(
    		'pageIdentifier'=> $pageIdentifier,
    		'pageEntityId'	=> 0,
    		'extraData'		=> array(
    			'countryId' => 2
    			)
    		);
    		return $beaconTrackData;
        }

    /******************************************************************
    This function is used to call model to get express interest details
    and display the CAREER EXPRESS PAGE. 
    *******************************************************************/
     
       function getExpressInterestPage() {
		$this->init();
		$this->load->model('Careers/careermodel');
		$this->careerModel = new Careermodel();	
		$expressInterestDetails = $this->careerModel->getExpressInterestDetails();
		
		$data['validateuser'] = $this->userStatus;
		$data['expressInterestDetails'] = $expressInterestDetails;
		$stream = $_COOKIE['streamSelected'];
		if($stream==''){
			header('location:'.CAREER_HOME_PAGE);
			exit;
		}

		//Redirect to new url
		$currenturl = $_SERVER['SCRIPT_URI'];
		$seo_params = explode('-',$currenturl);
		if($seo_params[1] == 'opportunities'){
			$newUrl = SHIKSHA_HOME.'/careers/opportunities';
             header("Location: $newUrl",TRUE,301);

		}

		$data['stream']=$stream;
		$data['trackForPages']         = true; //For JSB9
		$data['current_page_url'] =  CAREER_EXPRESSINTEREST_PAGE;

		//below line id used to store the required infromation in beacon varaible for tracking purpose
		$data['beaconTrackData'] = $this->_prepareBeaconData('careerOpportunities');
		$data['beaconTrackData']['extraData']['careerStream'] = $stream;

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_CareerHome','pageType'=>'opportunities','entity_id'=>$stream);
        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$this->load->library('CareerUtilityLib');
		$careerUtilityLib =  new CareerUtilityLib;
		$data['gtmParams'] = $careerUtilityLib->getGTMArray($data['beaconTrackData']);

		$this->load->view('Careers/career-express',$data);
	}

	function errorPage($page='')
	{
		$this->load->view('Careers/'.$page.'Page');
	}
	
	function getCareersListForFooterOnHomePage($startCountSearch=0,$countOffsetSearch=60){
		$this->init();
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$this->careerRepository = $careerBuilder->getCareerRepository();
		$careersList = $this->careerRepository->getCareerList('BASIC_INFO');
		
		$i=1;
		foreach ($careersList as $career){
		    $careerName = $career->getName();
		    $careerSeoUrl = $career->getCareerUrl();
		    $arrayOfCareersSeoUrl[$i]['name']=$careerName;
		    $arrayOfCareersSeoUrl[$i]['url']=$careerSeoUrl;
		    $i++;
		}
		$totalRows=count($arrayOfCareersSeoUrl);
		$arrayOfCareersSeoUrl=$this->getLimitedCarrersAccordingToPagination($startCountSearch,$countOffsetSearch,$arrayOfCareersSeoUrl);
	
		$data['arrayOfCareersSeoUrl'] = $arrayOfCareersSeoUrl;
		$data['validateuser'] = $this->userStatus;
		$paginationURL = SHIKSHA_HOME."/careers-after-12th-list/@start@/@count@";
		$totalCount = $totalRows;
		$paginationHTML = doPagination($totalCount,$paginationURL,$startCountSearch,$countOffsetSearch,3);
		$data['paginationHTML'] = $paginationHTML;

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_CareerHome','pageType'=>'careerlist');
        $data['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		//below code used for beacon tracking
        $data['trackingpageIdentifier'] = 'careersListPage';
        $data['trackingcountryId']=2;

        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);
        $data['beaconTrackData'] = $this->_prepareBeaconData('careersListPage');
        $this->load->library('CareerUtilityLib');
		$careerUtilityLib =  new CareerUtilityLib;
		$data['gtmParams'] = $careerUtilityLib->getGTMArray($data['beaconTrackData']);

		$this->load->view('Careers/careersList',$data);
	}
	
	function getLimitedCarrersAccordingToPagination($startCountSearch,$countOffsetSearch,$arrayOfCareersSeoUrl){
		$i=$startCountSearch+1;
		foreach($arrayOfCareersSeoUrl as $key=>$value){
			if($i<=$startCountSearch+$countOffsetSearch && $i<=count($arrayOfCareersSeoUrl)){
				$url=$value['url'];
					$resultArrayOfCareersSeoUrl[$i]['url']=$arrayOfCareersSeoUrl[$i]['url'];
					$resultArrayOfCareersSeoUrl[$i]['name']=$arrayOfCareersSeoUrl[$i]['name'];
					$i++;
			}
		}
		return $resultArrayOfCareersSeoUrl;
	}
	
	function getAllCareerUrls(){
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$this->careerRepository = $careerBuilder->getCareerRepository();
		$careersList = $this->careerRepository->getCareerList('BASIC_INFO');
		$i=1;
		foreach ($careersList as $career){
		    $careerSeoUrl = $career->getCareerUrl();
		    $arrayOfCareersSeoUrl[$i]['url']=$careerSeoUrl;
		    echo $arrayOfCareersSeoUrl[$i]['url'].'<br/>';
		    $i++;
		}
	}
	
	function createCareerUrls(){
		$this->init();
		$this->load->model('Careers/careermodel');
		$this->careerModel = new Careermodel();	
		$this->careerModel->createCareerUrls();
	}

	function storeUserIdToCareerMapping(){
		$this->init();
		$url_self = $this->input->post('urlOfCareerPage');
		$explodeArr = explode('-',$url_self);
		$careerId = $explodeArr[count($explodeArr)-1];
		$signedInUser = $this->userStatus;
		$userId = $signedInUser[0]['userid'];
		$this->load->model('Careers/careermodel');
		$this->careerModel = new Careermodel();
		$this->careerModel->storeUserIdToCareerMapping($userId,$careerId);
	}


	function mailer($string){
		$this->init();
		$explodeString  = explode('@',$string);
		$decodedMainCareerId = base64_decode($explodeString[0]);
		$decodedSuggestedCareerId = base64_decode($explodeString[1]);
		$decodedUserId = base64_decode($explodeString[2]);
		$this->load->model('Careers/careermodel');
		$this->careerModel = new Careermodel(); 
		$this->careerModel->setMappingForSimiliarCareerMailer($decodedMainCareerId,$decodedSuggestedCareerId,$decodedUserId);
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$careerRepository = $careerBuilder->getCareerRepository();
		$careerData = $careerRepository->find($decodedSuggestedCareerId);
		header('location:'.$careerData->getCareerUrl());
	}
	
function careerRecommendationCron($cronType){
		$this->validateCron();
		$this->init();
		$this->load->model('Careers/careermodel');
		$this->careerModel = new Careermodel();
		$contentArr = array();
		if($cronType=='registration'){
			$mappingData = $this->careerModel->getUserIdAndCareerIdMapping('registration');
			$contentArr['type'] = "registration";
		}else{
			$mappingData = $this->careerModel->getRecentClickedCareer();
			$contentArr['type'] = "clickedRecom";
		}
		if(empty($mappingData)){
			return;
		}
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$careerServiceRepository = $careerBuilder->getCareerService();
		$this->load->model('user/usermodel');
		$careerRepository = $careerBuilder->getCareerRepository();
		foreach($mappingData as $key=>$value){
			if($cronType=='registration'){
				$careerData = $careerRepository->find($value['mainCareerId']);
				$careerRecommendationObj = $careerServiceRepository->getRecommendedCareers($value['mainCareerId']);
				$mainCareerId = $value['mainCareerId'];
			}else{
				$careerData = $careerRepository->find($value['suggestedCareerId']);
				$careerRecommendationObj = $careerServiceRepository->getRecommendedCareers($value['suggestedCareerId']);
				$mainCareerId = $value['suggestedCareerId'];
			}

			if(empty($careerData)){
				continue;
			}

			$mainCareerName = $careerData->getName();
			$contentArr['mainCareerName'] = $mainCareerName;
			$count = 0;
			$firstRecommendedCareer = '';
			foreach($careerRecommendationObj as $k=>$careerObj){
				//if($mainCareerId == $k){
				//	continue;
				//}
				if($count==0){
					$firstRecommendedCareer = $careerObj->getName();
				}else{
					continue;
				}
				$count++;
			}
			$contentArr['firstRecommendedCareer'] = $firstRecommendedCareer;
			$userInfo = $this->usermodel->getUsersBasicInfo(array($value['userId']));
			$userEmail = $userInfo[$value['userId']]['email'];
			$contentArr['mainCareerId'] = $value['mainCareerId'];
			$contentArr['userId'] = $value['userId'];
			$contentArr['firstname'] = $userInfo[$value['userId']]['firstname'];
			$contentArr['lastname'] = $userInfo[$value['userId']]['lastname'];
			$encodedEmail = $this->usermodel->getEncodedEmail($userEmail);
			$j = 0;
			$finalArr = array();
			foreach($careerRecommendationObj as $index=>$obj){
				//if($mainCareerId == $index){
				//	continue;
				//}
				if($j>3){
					break;
				}
				$recomCareerId = $obj->getCareerId();
				$viewMailerURL = SHIKSHA_HOME.'/Careers/CareerController/mailer/'.base64_encode($value['mainCareerId']).'@'.base64_encode($recomCareerId).'@'.base64_encode($value['userId']);
				$autoLoginData = 'email~'.$encodedEmail.'_url~'.base64_encode($viewMailerURL);
				//$finalArr[$j]['autoLoginURL'] = SHIKSHA_HOME.'/mailer/Mailer/autoLogin/'.$autoLoginData;
				$finalArr[$j]['autoLoginURL'] = $viewMailerURL;
				$finalArr[$j]['careerName'] = $obj->getName();
				$finalArr[$j]['imageUrl'] = $obj->getImage();
				$finalArr[$j]['shortDescription'] = $obj->getShortDescription();
				$finalArr[$j]['minSalInLacs'] = $obj->getMinimumSalaryInLacs();
				$finalArr[$j]['minSalInThousand'] = $obj->getMinimumSalaryInThousand();
				$finalArr[$j]['maxSalInLacs'] = $obj->getMaximumSalaryInLacs();
				$finalArr[$j]['maxSalInThousand'] = $obj->getMaximumSalaryInThousand();
				$finalArr[$j]['difficulityLevel'] = $obj->getDifficultyLevel();
				$j++;
			}
			$contentArr['finalArr'] = $finalArr;
			Modules::run('systemMailer/SystemMailer/careerSimilarRecommendationMailer',$userEmail,$contentArr);
			if($contentArr['type'] == "registration"){
				$this->careerModel->updateStatusForRecomReg($value['userId'],$value['mainCareerId']);
			}else{
				$this->careerModel->updateStatusSimilarRecom($value['userId'],$value['mainCareerId']);	
			}
		}
	} 

	function getCareerMailerInfomation($fromDate, $toDate){
		$this->init();
		$this->load->model('Careers/careermodel');
		$this->careerModel = new Careermodel();
		if(trim($fromDate)=='' && trim($toDate)==''){
			$fromDate = date("Y-m-d", time() - 86400);
			$toDate = date('Y-m-d');
		}
		$data = $this->careerModel->getSimilarCareerClicked($fromDate, $toDate);
		$arr = '';
		$this->load->builder('CareerBuilder','Careers');
		$careerBuilder = new CareerBuilder;
		$careerRepository = $careerBuilder->getCareerRepository();
		$totalNumberOfMailers = '';
		//$dataCD = $this->careerModel->similarMailerSentOnCurrentDay($fromDate, $toDate);
		//echo "<table border='1'><tr><td><strong>Number Of Mailers</strong></td><td><strong>Date Time</strong></td></tr>";
		//foreach($dataCD as $key=>$value){
		//	echo "<tr><td>".count($value)."</td><td>".date('Y-m-d',$key)."</td></tr>";
		//	$totalNumberOfMailers += count($value);
		//}
		//echo "</table>";
		//echo "<hr/>";
		
		echo "<table border='1'><tr><td><strong>From Date</strong></td><td><strong>To Date</strong></td></tr>";
			echo "<tr><td>".$fromDate."</td><td>".$toDate."</td></tr>";
		echo "</table>";
		echo "<hr/>";
		
		foreach($data as $key=>$value){
			$explodeString = explode('@',$value);
			$clickedCareerId = base64_decode($explodeString[1]);
			$careerData = $careerRepository->find($clickedCareerId);
			$careerName = $careerData->getName();
			$arr[$clickedCareerId][] = $careerName;
		}
		
		
		$totalNumberOfMailOpened = $this->careerModel->totalNumberOfMailOpened($fromDate, $toDate);
                echo "<table border='1'><tr><td><strong>Total Number Of Mailers</strong></td><td><strong>Opened Mailers</strong></td></tr>";
                echo "<tr><td>".$totalNumberOfMailers."</td><td>".$totalNumberOfMailOpened."</td></tr>";
                echo "</table>";
                echo "<hr/>";
                echo "<table border='1'><tr><td><strong>Career Id</strong></td><td><strong>Career Name</strong></td><td><strong>Number Of Clicked</strong></td></tr>";
                foreach($arr as $k=>$v){
                        echo "<tr><td>";
                        echo $k;
                        echo "</td><td>";
                        echo $v[0];
                        echo "</td><td>";
                        echo count($v);
                        echo "</td></tr>";
                }
                echo "</table>";		
        }

        function _getShikshaBannerCriteria($result){
        	$careerName = $result->getName();
        	
        	$careerName = strip_tags($careerName);
        	$careerName = seo_url($careerName);
        	$careerName = strtolower($careerName);
        	$careerName = str_replace(array("-"), "_", $careerName);
        	$careerName = trim($careerName);

        	$criteriaArray = array(     
			        'category' => '',     
			        'country' => '',      
			        'city' => '',     
			        'keyword'=>  $careerName
			        );

        	return $criteriaArray;
        }

        function getCategoryPageUrls($careerId){
    		$CategoryPageLib = $this->load->library('nationalCategoryList/NationalCategoryPageLib');
        	$hierarchyData = $this->getCareerHierarchyMapping($careerId);
        	$hierarchyArray = array();
        	foreach ($hierarchyData as $key => $value) {
        		if(!in_array($value['hierarchyId'], $hierarchyArray)){
        			$hierarchyArray[] = $value['hierarchyId'];
        		}
        	}
        	if(!empty($hierarchyArray)){
        		$mappingData = Modules::run('common/commonHierarchyForm/getBaseEntityIdsByHierarchyId',$hierarchyArray,1);	
        	}
        	
        	foreach ($hierarchyData as $key => $value) {
        		$categoryData['stream_id'] = $mappingData[$value['hierarchyId']]['stream']['id'];
        		$categoryData['substream_id'] = $mappingData[$value['hierarchyId']]['substream']['id'];
        		$categoryData['specialization_id'] = $mappingData[$value['hierarchyId']]['specialization']['id'];
        		$categoryData['base_course_id'] = $value['courseId'];
        		$newKey = $value['hierarchyId']."_".$value['courseId'];
        		$urls = $CategoryPageLib->getUrlByParams($categoryData);
        		if(!empty($urls)){
        			$result[$newKey]['URL'] = $urls;
        		}
        		if(!empty($mappingData[$value['hierarchyId']]['specialization']['name']) && !empty($result[$newKey]['URL'])){
        			$result[$newKey]['NAME'] = $mappingData[$value['hierarchyId']]['specialization']['name'];
        		}elseif(!empty($mappingData[$value['hierarchyId']]['substream']['name']) && !empty($result[$newKey]['URL'])){
        			$result[$newKey]['NAME'] = $mappingData[$value['hierarchyId']]['substream']['name'];
        		}elseif(!empty($mappingData[$value['hierarchyId']]['stream']['name']) && !empty($result[$newKey]['URL'])){
        			$result[$newKey]['NAME'] = $mappingData[$value['hierarchyId']]['stream']['name'];
        		}
        	}
        	return $result;
        }

        function getCareerHierarchyMapping($careerId){
        	$careerEnterpriseModel = $this->load->model('CareerProductEnterprise/careerenterprisemodel');
        	return $careerEnterpriseModel->getCareerHierarchyData($careerId);
        }
    		
}
?>
