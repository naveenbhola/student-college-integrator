<?php

class RankingMain extends MX_Controller {
	
	private $rankingURLManager;
	private $rankingFilterManager;
	private $rankingSorterManager;
	private $rankingCommonLib;
	private $rankingRelatedLib;
	private $userStatus;
	
	public function __construct(){
		$this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		$this->load->service("categoryList/CurrencyConverterService");
	}

	private function _filterRankingPageData($rankingPage, $rankingPageRequest){
		$pageData = $rankingPage->getRankingPageData();
		$examId = $rankingPageRequest->getExamId();
		if($cityId = $rankingPageRequest->getCityId()){
		}elseif($stateId = $rankingPageRequest->getStateId()){}
		// Country ID is always 2 so no need to filter by country Id.
		$filteredData = array();
		foreach($pageData as $key=>$data){
			if(!empty($examId)){
				$exams = $data->getExams();
				$ids = array_map(function($ele){return $ele['id'];},$exams);
				if(!in_array($examId, $ids)){
					unset($pageData[$key]);
					continue;
				}
			}
			if(!empty($cityId)){
				$city = $data->getCityId();
				if($city != $cityId){
					unset($pageData[$key]);
					continue;
				}
			}elseif(!empty($stateId)){
				$state = $data->getStateId();
				if($state != $stateId){
					unset($pageData[$key]);
					continue;
				}
			}
		}
		$rankingPage->setRankingPageData($pageData);
		return $rankingPage;
	}

	private function _fetchUserShortlistedCourses(& $displayData){

		$courses = array();
		if($displayData['validateuser'] !== "false"){
			$courses = modules::run('myShortlist/MyShortlist/getShortlistedCourse', (integer)$displayData['validateuser'][0]['userid']);
			if(!is_array($courses)){
				$courses = array();
			}
		}
		$displayData['courseMBAShortlistArray'] = $courses;
	}

	private function _initRankingPage() {
		$this->load->helper('url');
		$this->load->helper('string');
		$this->load->library("rankingV2/RankingCommonLib");
		$this->load->library('rankingV2/RankingCommonLibv2');
		$this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		
		$builder = new RankingPageBuilder;
		$this->rankingURLManager		= $builder->getURLManager();
		$this->rankingFilterManager	= $builder->getFilterManager();

		$this->rankingPageRepository	= $builder->getRankingPageRepository();
	
		$this->userStatus = $this->checkUserValidation();
		if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
		    $this->userid = $this->userStatus[0]['userid'];
		} else {
		    $this->userid = -1;
		}
	}

	function showRankingPage($urlIdentifier = NULL) {
		$this->_initRankingPage();

		$rankingPageRequest = $this->rankingURLManager->getRankingPageRequest($urlIdentifier);
		$currentPageUrl     = $this->rankingURLManager->buildURL($rankingPageRequest);
		$this->rankingURLManager->validateURL($rankingPageRequest);

		$rankingPageId			= $rankingPageRequest->getPageId();
		$isAjaxCall 			= $this->input->is_ajax_request();

		$publisherId = null;
		if($isAjaxCall) {
			$publisherId	= $this->input->post("publisherId",true);
		}
		else {
			$criteria = $this->rankingFilterManager->prepareRankingPageCriteria($rankingPageRequest);
			$availablePublishers = $this->rankingFilterManager->rankingModel->getFilters($criteria, 'publisher');
			$availablePublisherIds = array_keys($availablePublishers);
			if(empty($availablePublisherIds)) {
				show_404();
			}
		}

		$rankingPage			= $this->rankingPageRepository->find($rankingPageId, null, $publisherId, $availablePublisherIds);
		$rankingPageSource		= $rankingPage->getPublisherData();
		$rankingPageDisclaimer 	=	strip_tags($rankingPage->getDisclaimer());
		if(empty($rankingPageSource)){
			show_404();
		}		
		$rankingPageFilters		= $this->rankingFilterManager->getFilters($rankingPage, $rankingPageRequest);

		// $breadcrumbHtml            = $this->_prepareBreadcrumbHtml($metaDetails);
		$metaDetails               = $this->rankingURLManager->getRankingPageMetaData($rankingPage,$rankingPageRequest);
		$rankingPageOf			= $this->rankingcommonlib->checkForRankingPageTupleWidget($rankingPage);
		$this->benchmark->mark('prepare_interlinking_data_start');
		$examWidgetData            = $this->rankingcommonlib->prepareExamWidgetData($rankingPageRequest);
		$articlesData              = $this->rankingcommonlib->prepareArticlesInterlinkingData($rankingPageRequest);
		$interlinkingWidgetHeading = $this->rankingcommonlib->getInterlinkingWidgetHeading($rankingPageFilters, $rankingPageRequest, $rankingPage);
		$this->benchmark->mark('prepare_interlinking_data_end');

		$displayData = array();
		$this->benchmark->mark('tuple_start');
		$this->rankingcommonlib->prepareRankingTupleData($rankingPage,$rankingPageRequest,$displayData);
		$this->benchmark->mark('tuple_end');
        
        $this->benchmark->mark('fetching_banner_start');
        $bannerDetails 			= $this->rankingcommonlib->getRankingPageBannerDetails($rankingPage, $rankingPageRequest);
        $this->benchmark->mark('fetching_banner_end');
		$this->benchmark->mark('shortlist_start');
		if($rankingPage->getTupleType() == 'course') {
		 	$displayData['shortlistedCoursesOfUser']	= Modules::run('myShortlist/MyShortlist/getShortlistedCourse', $this->userid);
        }
        $this->benchmark->mark('shortlist_end');

        $displayData['rankingPageSource']           = $rankingPageSource;
        $displayData['rankingPageDisclaimer']		= $rankingPageDisclaimer;
        $displayData['previousRankFlag']            = count($rankingPageSource)==2?true:false;
        $displayData['banner_details']              = $bannerDetails;
		$displayData['rankingPageId']				= $rankingPageId;
		$displayData['validateuser']				= $this->checkUserValidation();
		$displayData['filters']						= $rankingPageFilters;
		$displayData['examName']					= $rankingPageRequest->getExamName();
		$displayData['rankingPage']					= $rankingPage;
		$displayData['rankingPageRequest']			= $rankingPageRequest;
		$displayData['meta_details']				= $metaDetails;
		// $displayData['breadcrumbHtml']				= $breadcrumbHtml;
		$displayData['rankingPageOf']				= $rankingPageOf;
		$displayData['rankingPageMainSourceId']		= $rankingPageMainSourceId;
		$displayData['articleWidgetsData']			= $articlesData;
		$displayData['examWidgetData']				= $examWidgetData;
		$displayData['tuplesPerPage']				= 30;
		$displayData['interlinkingWidgetHeading']	= $interlinkingWidgetHeading;
		$displayData['noJqueryMobile'] 				= true;
		// $displayData['mobilecss'] 					= array('ranking_page_mobile');
		$displayData['boomr_pageid']				= 'mRanking5';
		$displayData['mobilePageName']				= 'mRanking5';
		$displayData['beaconTrackData'] 			= $this->rankingcommonlib->getBecaonTrackData($rankingPageRequest);
        $displayData['beaconTrackData']['extraData'] = array_filter($displayData['beaconTrackData']['extraData']);
        $displayData['beaconTrackData']['extraData']['hierarchy'] = array_filter($displayData['beaconTrackData']['extraData']['hierarchy']);
        $displayData['gtmParams'] = $this->rankingcommonlib->getScanParams($displayData['beaconTrackData']['extraData'],$displayData['validateuser']);
        $displayData['canonical'] = explode('?',getCurrentPageURL())[0];;
        $displayData['trackForPages'] = true; //For JSB9
        $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');
        if($rankingPage->getTupleType() != 'course'){
        	$displayData['websiteTourContentMapping']['Filters'] = 'Filter colleges by fees, exams, location, specialization etc.';
        }
        $displayData['IIMPredictorWidget'] = Modules::Run('mIIMPredictor5/IIMPredictor/getIIMCallPredictorWidget','rankingPage');
        $displayData['shortlistTrackingKeyId'] = MOBILE_NL_RNKINGPGE_TUPLE_COURSESHORTLIST;
        $displayData['userStatus'] 		    = $this->checkUserValidation();
        
        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_RankingPage','entity_id'=>$rankingPageId,'stream_id'=>$displayData['gtmParams']['stream'],'substream_id'=>$displayData['gtmParams']['substream'],'specialization_id'=>$displayData['gtmParams']['specialization'],'baseCourse'=>$displayData['gtmParams']['baseCourseId'],'cityId'=>$displayData['gtmParams']['cityId'],'stateId'=>$displayData['gtmParams']['stateId'],'educationType'=>$displayData['gtmParams']['educationType'],'deliveryMethod'=>$displayData['gtmParams']['deliveryMethod']);
        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');
        
        if($isAjaxCall) {
			$tuple = $this->load->view('mranking5/ranking_page/ranking_page_table', $displayData, true);
			$publisher = reset($rankingPage->getPublisherData());
			$year = $publisher['year'];
			echo json_encode(array('tuple' => $tuple, 'year' => $year));
			exit;
        }
        else {
        	$this->benchmark->mark('view_start');
            $collegePredBannerDetails = getAndShowCollegePredBanner($rankingPageRequest->getStreamId(), $rankingPageRequest->getBaseCourseId());
	        if(!empty($collegePredBannerDetails)){
	            $displayData['predBannerStream'] = $collegePredBannerDetails['predStream'];
	            $displayData['isShowIcpBanner'] = true;
	        }else {
                $displayData['isShowIcpBanner'] = false;
            }
			$this->load->view("mranking5/ranking_page/ranking_page_overview", $displayData);
			$this->benchmark->mark('view_end');
		}	
	}
	
}
