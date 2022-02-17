<?php

class RankingMain extends MX_Controller {
	
	private $rankingURLManager;
	private $rankingFilterManager;
	private $rankingSorterManager;
	private $rankingCommonLib;
	private $rankingRelatedLib;
	private $userStatus;
	private $useSolrForCategoryPageData = true;
	
	public function __construct(){
		$this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		$this->load->service("categoryList/CurrencyConverterService");
		// $this->cmpObj = $this->load->library('comparePage/comparePageLib');
    }

	private function _fetchUserShortlistedCourses(& $displayData){
		$courses = array();
		if($displayData['validateuser'] !== "false"){
			$courses = modules::run('myShortlist/MyShortlist/getShortlistedCourse', (integer)$displayData['validateuser'][0]['userid']);
			if(!is_array($courses)){
				$courses = array();
			}
		}
		$displayData['shortlistedCoursesOfUser'] = $courses;
	}
	
    private function _prepareDataForDownloadEbrochur($featuredInstitutes){

    		foreach($featuredInstitutes  as $institute) {
			$course = $institute->getFlagshipCourse();
			$Location = $course->getMainLocation();
			$featuredInstituteDetailsForDownloadEbrochue['institute'][$institute->getId()] = array(
					            'id' => $institute->getId(),
					            'name' => $institute->getName(),
					            'city_id' => $Location->getCity()->getId(),
					            'city_name' => $Location->getCity()->getName()					 
			);
			
			$featuredInstituteDetailsForDownloadEbrochue['course'][$institute->getId()][0] = array(
			     	'id' => $course->getId(),
					'name' => $course->getName()
			);
			
		}
		
		 return $featuredInstituteDetailsForDownloadEbrochue;
	} 

	

	private function prepareDataForFilterAndOtherRelatedRanking($filters){

		$cityFilters           = $filters['city'];
		$stateFilters          = $filters['state'];
		$examFilters           = $filters['exam'];
		$specializationFilters = $filters['specialization'];
		$totalSpecializationFilters = count($specializationFilters);
		$defaultSpecializationSelected = false;
		if(empty($specializationFilters)){
			$showSpecializationFilters = false;
		}else{
			$showSpecializationFilters = true;
		}

		$filterData['showSpecializationFilters'] = $showSpecializationFilters;
		$filterData['selectedSpecialization']    = $selectedSpecialization;

		$totalExamFilters = count($examFilters);
		$defaultExamSelected = false;
		foreach($examFilters as $filter){
			if($filter->isSelected() == true){
				$defaultExamSelected = true;
				$selectedExam = $filter;
			}
		}



		$showExamFilters = true;
		if($totalExamFilters <= 1 && $defaultExamSelected){
			$showExamFilters = false;
		}
		
		$filterData['showExamFilters'] = $showExamFilters;
		$filterData['selectedExam'] = $selectedExam;



		$citySelected = false;
		if(!empty($stateFilters) || !empty($cityFilters)){
			$tempCityFilterSelected = NULL;
			$tempStateFilterSelected = NULL;
			foreach($cityFilters as $filter){
				$isSelected = $filter->isSelected();
				if($isSelected){
					$tempCityFilterSelected = $filter;
					break;
				}
			}

			foreach($stateFilters as $filter){
				$isSelected = $filter->isSelected();
				if($isSelected){
					$tempStateFilterSelected = $filter;
					break;
				}
			}


			$useCityFilter = true;
			if(!empty($tempCityFilterSelected) && !empty($tempStateFilterSelected)){
				$tempCitySelected = $tempCityFilterSelected->getName();
				$tempStateSelected = $tempStateFilterSelected->getName();
				if(strtolower($tempCitySelected) == "all" && trim($tempCitySelected) != ""){
					$useCityFilter = false;
				}
			}
			

			$filterData['useCityFilter'] = $useCityFilter;
			if($useCityFilter == false){
				$filterData['selectedLocation'] = $tempStateFilterSelected;
			}else{
				$filterData['selectedLocation'] = $tempCityFilterSelected;
			}

			

		}

		return $filterData;
	}
	
	

      	
  private function _filterPaidFeaturedInstitutes($institutes,$alreadyDisplayedInstitutes){
   	$paidInstitutes;
  	foreach ($institutes as $institute)
  	{   $instID= $institute->getId();
  		if(!array_key_exists($instID, $alreadyDisplayedInstitutes))
  		{	
  		$course= $institute->getFlagshipCourse();
  		if($course->isPaid())
  		{	
  		$paidInstitutes [$institute->getId()] = $institute;
  		}  
  		}	
  	}
  	return $paidInstitutes;
  }	
     
	public function getCityFilteredResultsForRequest(){
		$REQ_resultBlockType = $this->input->get('resultBlockType',true);
		$resultBlock 	= (isset($REQ_resultBlockType) && $REQ_resultBlockType != '') ? $REQ_resultBlockType : "";
		$requestParams  = $this->readCommonRequestParamsFromURL();
		
		$rankingPageId = $requestParams['rankingPageId'];
		if(empty($rankingPageId)){
			return;
		}
		
		$rankingPageRequest 		= $this->rankingURLManager->getRankingPageRequestFromDataArray($requestParams);
		$rankingPageRepository  	= RankingPageBuilder::getRankingPageRepository();
		$rankingPage   				= $rankingPageRepository->find($rankingPageId);
		$rankingPageFilters 		= $this->rankingFilterManager->getFilters($rankingPage, $rankingPageRequest);
		$this->rankingFilterManager->applyFilters($rankingPage, $rankingPageRequest);
		$defaultSortRequest 		= $this->rankingSorterManager->getSortRequestObject("rank", "desc");
		
		$data = array();
		$data['ranking_page'] 	= $rankingPage;
		$data['request_object'] = $rankingPageRequest;
		$data['resultType'] 	= $resultBlock;
		$data['sorter'] 		= $defaultSortRequest;
		$data['filters'] 		= $rankingPageFilters;

		$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
		$data['brochureURL'] = $this->national_course_lib;
                $listingBuilder = new ListingBuilder;
                $instituteRepository = $listingBuilder->getInstituteRepository();
                $data['instituteRepository'] = $instituteRepository;

		$rankingTableHTML = $this->load->view("ranking/ranking_table", $data, true);
		
		$return = array();
		$return['html'] = $rankingTableHTML;
		echo json_encode($return);
	}
	
	public function getMoreRankingResultsForRequest(){
		$GLOBALS['partial_localityArray'] = array();
		$REQ_resultBlockType = $this->input->get('resultBlockType',true);
		$REQ_offset_reach = $this->input->get('offset_reach',true);
		$resultBlock 	= (isset($REQ_resultBlockType) && $REQ_resultBlockType != '') ? $REQ_resultBlockType : "";
		$offset_reach 	= (isset($REQ_offset_reach) && $REQ_offset_reach != '') ? $REQ_offset_reach : 0;
		$requestParams  = $this->readCommonRequestParamsFromURL();
		
		$rankingPageId = $requestParams['rankingPageId'];
		if(empty($rankingPageId)){
			return;
		}
		
		$rankingPageRequest 		= $this->rankingURLManager->getRankingPageRequestFromDataArray($requestParams);
		$rankingPageRepository  	= RankingPageBuilder::getRankingPageRepository();
		$rankingPage   				= $rankingPageRepository->find($rankingPageId);
		$rankingPageFilters 		= $this->rankingFilterManager->getFilters($rankingPage, $rankingPageRequest);
		$this->rankingFilterManager->applyFilters($rankingPage, $rankingPageRequest);
		
		$maxResultCountInExtraDataBlock = $this->config->item('MAXIMUM_RESULTS_COUNT_IN_EXTRA_DATA_BLOCK');
		$rankingPageDataCount = count($rankingPage->getRankingPageData());
		$this->rankingCommonLib->applyLimitOnRankingPage($rankingPage, $offset_reach, $maxResultCountInExtraDataBlock);
		$updateRankingPageDataCount = count($rankingPage->getRankingPageData());
		$offset_reach = $updateRankingPageDataCount;
		
		$brochureRelatedData['multiple_location_courses'] = array();
		$brochureRelatedData['study_abroad_courses'] 	  = array();
		$brochureRelatedData['institute_details'] 		  = array();
		$brochureRelatedData['course_details'] 	 		  = array();
		$brochureRelatedData['partial_localityArray'] 	  = array();
		
		if(!empty($rankingPage)){
			$rankingPageData 		= $rankingPage->getRankingPageData();
			$brochureRelatedData 	= $this->rankingCommonLib->getBrochureRelatedData($rankingPageData, 'partial');
		}
		
		$data = array();
		$data['ranking_page'] 	= $rankingPage;
		$data['request_object'] = $rankingPageRequest;
		$data['resultType'] 	= $resultBlock;
		$data['sorter'] 		= NULL;
		$data['filters'] 		= $rankingPageFilters;
		$data['offset_reach'] 	= $offset_reach;
		$data['max_rows'] 		= $maxResultCountInExtraDataBlock;
		$data['total_results'] 	= $rankingPageDataCount;

		$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
		$data['brochureURL'] = $this->national_course_lib;
                $listingBuilder = new ListingBuilder;
                $instituteRepository = $listingBuilder->getInstituteRepository();
                $data['instituteRepository'] = $instituteRepository;
		
		$rankingTableHTML = $this->load->view("ranking/ranking_table", $data, true);
		$return = array();
		$return['html'] = $rankingTableHTML;
		$return['brochure_related_data'] = $brochureRelatedData;
		echo json_encode($return);
	}
	
	public function readCommonRequestParamsFromURL(){
		$REQ_rankingPageId = $this->input->get('rankingPageId',true);
		$REQ_examId = $this->input->get('examId',true);
		$REQ_cityId = $this->input->get('cityId',true);
		$REQ_stateId = $this->input->get('stateId',true);
		$REQ_countryId = $this->input->get('countryId',true);

		$rankingPageId 	= (isset($REQ_rankingPageId) && $REQ_rankingPageId != '' && is_numeric($REQ_rankingPageId)) ? $REQ_rankingPageId : 0;
		$examId 		= (isset($REQ_examId) && $REQ_examId != '' && is_numeric($REQ_examId)) ? $REQ_examId : "";
		$cityId 		= (isset($REQ_cityId) && $REQ_cityId != '' && is_numeric($REQ_cityId)) ? $REQ_cityId : "";
		$stateId 		= (isset($REQ_stateId) && $REQ_stateId != '' && is_numeric($REQ_stateId)) ? $REQ_stateId : "";
		$countryId 		= (isset($REQ_countryId) && $REQ_countryId != '' && is_numeric($REQ_countryId)) ? $REQ_countryId : "";
		
		$requestParams = array();
		$requestParams['rankingPageId']  = $rankingPageId;
		$requestParams['examId'] 		 = $examId;
		$requestParams['cityId'] 		 = $cityId;
		$requestParams['stateId'] 		 = $stateId;
		$requestParams['countryId'] 	 = $countryId;
		
		return $requestParams;
	}

	public function getRankingPageCoursePageWidget($subcategoryIds = array(), $specializationIds = array(), $returnFlag = false, $pageType = "listingpage",$rankingFiltersData=array()){
		if(!isset($this->rankingRelatedLib)){
			$this->rankingRelatedLib	= RankingPageBuilder::getRankingPageRelatedLib();
		}
		$rankingSeoUrls         = array();
		$rankingPageRelatedLib  = $this->load->library('RankingPageRelatedLib');  
		$rankingSeoUrls 		= $rankingPageRelatedLib->getRankingSeoLinkOnCoursePage($rankingFiltersData);
		
	
		if((is_array($subcategoryIds) && !empty($subcategoryIds)) || (is_array($specializationIds) && !empty($specializationIds))){
				$rankingSpecializationLink = $this->rankingRelatedLib->rankingSpecializationsLinkCoursePage($subcategoryIds, $specializationIds, $pageType);

				if(empty($rankingSpecializationLink))
					return $rankingSeoUrls;	

				$rankingSeoUrls[] = array(
										  'url'  => $rankingSpecializationLink->getUrl(),
										  'title' => $rankingSpecializationLink->getName()
										);		
		}
		return $rankingSeoUrls;
	}	
	//Removing Following widget as It does not exist anymore. This used to exist on ranking pages in left column .
//	public function loadRankingRegistrationWidget(){
//		$validate 	= $this->checkUserValidation();
//		$data = array();
//		$data['Validatelogged'] = $validate;
//		echo $this->load->view('ranking/rankingPageLeadForm', $data);
//	}
	
	public function shareRankingPageViaEmail(){
		$return = array();
		$email_ids 		 = trim($this->input->post('email_ids',true));
		$rankingPageURLIdentifier =  trim($this->input->post('url_identifier',true));
		if(empty($email_ids) || empty($rankingPageURLIdentifier)){
			$return['success'] 		= "false";
			$return['error_type'] 	= "INVALID_INPUT_PARAMS";
			echo json_encode($return);
			return;
		}
		
		$rankingPageRequest 	= $this->rankingURLManager->getRankingPageRequest($rankingPageURLIdentifier);
		if(empty($rankingPageRequest)){
			$return['success'] 		= "false";
			$return['error_type'] 	= "INVALID_INPUT_PARAMS";
			echo json_encode($return);
			return;
		}
		$rankingPageRepository  = RankingPageBuilder::getRankingPageRepository();
		$rankingPageId 			= $rankingPageRequest->getPageId();
		$rankingPage   			= $rankingPageRepository->find($rankingPageId);
		if(empty($rankingPage)){
			$return['success'] 		= "false";
			$return['error_type'] 	= "INVALID_INPUT_PARAMS";
			echo json_encode($return);
			return;
		}
		
		$rankingPageMetaDetails = $this->rankingURLManager->getRankingPageMetaData($rankingPage, $rankingPageRequest);
		if(empty($rankingPageMetaDetails)){
			$return['success'] 		= "false";
			$return['error_type'] 	= "INVALID_INPUT_PARAMS";
			echo json_encode($return);
			return;
		}
		
		$rankingPageFilters 	= $this->rankingFilterManager->getFilters($rankingPage, $rankingPageRequest);
		$this->rankingURLManager->correctRankingPageRequestUsingFilterValues($rankingPageRequest, $rankingPageFilters);
		$this->rankingFilterManager->applyFilters($rankingPage, $rankingPageRequest);
		
		$currentPageUrl 		= $this->rankingURLManager->getCurrentPageURL($rankingPageRequest);
		$email = false;
		$userName = false;
		if(!empty($this->userStatus) && $this->userStatus != "false"){
			$cookieString = $this->userStatus[0]['cookiestr'];
			$userName = $this->userStatus[0]['displayname'];
			if(!empty($cookieString)){
				$data = explode("|", $cookieString);
				$email = $data[0];
			}
		}
		$return = $this->rankingCommonLib->shareRankingPageViaEmail($email_ids, $rankingPage, $email, $rankingPageMetaDetails, $userName, $currentPageUrl);
		echo json_encode($return);
	}
	
	/**
	 *@method: Method to get naukri salary data for the provided institutes
	 *@params: 1. list of institute-ids
	 *	   2. list of course-ids
	 *	   3. mapping of courses and institute ids
	 *@return: Array containing naukri-salary data institute-wise
	 *@author: Romil
	 */
	// function getInstitutesNaukriData($rankingPage, $instituteIds, $courseIds, $instituteCourseMapping)
	// {
	// 	$this->load->model('listing/institutemodel');
	// 	$course_model = $this->load->model('listing/coursemodel');  
		
	// 	if(empty($instituteIds))
	// 		return array();

	// 	$institutemodel 	= new institutemodel;
	// 	$data 			= array();	   
		
	// 	$instituteWiseNaukriData = array();
	// 	$not_mapped_to_full_time_mba = array();
		
	// 	$specialization_list = $course_model->getSpecializationIdsByClientCourse($courseIds,TRUE);
		
	// 	// get the naukri salary data
	// 	foreach($salaryDataResults as $naukriDataRow)
	// 	{
	// 		if($naukriDataRow['exp_bucket'] == '2-5')
	// 			$instituteWiseNaukriData[$naukriDataRow['institute_id']] = $naukriDataRow;
			
	// 		$totalEmployees[$naukriDataRow['institute_id']] += $naukriDataRow['tot_emp'];
	// 	}
		
	// 	// check which-all courses are not mapped to full-time mba
	// 	foreach($specialization_list as $courseId=>$rowArray) {
	// 		foreach($rowArray as $row)
	// 		{
	// 			if(!$not_mapped_to_full_time_mba[$courseId]) {
	// 				if(!($row['ParentId'] == 2 || $row['SpecializationId'] == 2)) {
	// 					$not_mapped_to_full_time_mba[$courseId] = TRUE;
	// 				} 
	// 			}
	// 		}
	// 	} 
		
	// 	// unset the naukri data for institutes whose employee count is less than 30
	// 	foreach($totalEmployees as $instituteId => $employeeCount)
	// 	{
	// 		if($employeeCount < 30)
	// 			unset($instituteWiseNaukriData[$instituteId]);
	// 	}
		
	// 	// unset the naukri data for institutes whose courses are not mapped to full-time mba
	// 	foreach($not_mapped_to_full_time_mba as $courseId=>$value)
	// 	{
	// 		if($value)
	// 			unset($instituteWiseNaukriData[$instituteCourseMapping[$courseId]]);
	// 	}
		
	// 	$rankingPageDetails = $rankingPage->getRankingPageData();
	// 	foreach($rankingPageDetails as $rankingPageRow)
	// 	{
	// 		$instituteId = $rankingPageRow->getInstituteId();
	// 		if($instituteWiseNaukriData[$instituteId])
	// 			$rankingPageRow->setNaukriSalaryData($instituteWiseNaukriData[$instituteId]);
	// 	}

	// 	return $instituteWiseNaukriData;
	// }
	
	private function _sortRankingPageData(&$rankingPage){
		if(!isset($this->rankingSorterManager)){
			$this->rankingSorterManager 	= RankingPageBuilder::getSorterManager();
		}
		$sortKey 		= $this->input->post('columnType',true);
		$sortOrder 		= $this->input->post('sortType',true);
		$sortKeyValue 		= $this->input->post('columnTypeVal',true);
		
		$sortRequestObject 		= $this->rankingSorterManager->getSortRequestObject($sortKey, $sortOrder, $sortKeyValue);
		
		$this->rankingSorterManager->applySorter($rankingPage, $sortRequestObject);
	}

	

	/**
	 * to fetch the cutoff exam wise
	 * @return string
	 */
	function getCutOffByExam(){

		// get global variables 
		global $appliedFilters;
		global $MBA_SCORE_RANGE;
		global $MBA_SCORE_RANGE_CMAT;
		global $MBA_SCORE_RANGE_GMAT;
		global $MBA_PERCENTILE_RANGE_MAT;
		global $MBA_PERCENTILE_RANGE_XAT;
		global $MBA_PERCENTILE_RANGE_NMAT;
		global $ENGINEERING_EXAMS_REQUIRED_SCORES;
		global $MBA_EXAMS_REQUIRED_SCORES;
		global $MBA_NO_OPTION_EXAMS;



		$examName      =  $this->input->post('examName',true);
		$subCategoryId =  $this->input->post('subCategoryId',true);

		//exam empty check
		if($examName == 'Select Exam' || empty($examName) || $examName == 'All'){
			$cutOffOptionHtml = "<option value=''>Select CutOff</option>";
			echo $cutOffOptionHtml;
			exit;
		}

		if($subCategoryId  == 23){
			if(in_array($examName, $MBA_NO_OPTION_EXAMS)){
				$examScoreContDisplayStyle = "display:none;";
			}
			
			$placeHolderText = "Cut-off percentile";
			$defaultDropdownValue = "Any";

			if(in_array($examName, $MBA_EXAMS_REQUIRED_SCORES)){
				$placeHolderText = "Cut-off score";
			}

			$scoreRanges = $MBA_SCORE_RANGE;
			if($examName == "CMAT"){
				$scoreRanges = $MBA_SCORE_RANGE_CMAT;
			} else if($examName == "GMAT"){
				$scoreRanges = $MBA_SCORE_RANGE_GMAT;
			} else if($examName == "MAT"){
				$scoreRanges = $MBA_PERCENTILE_RANGE_MAT;
			} else if($examName == "XAT") {
				$scoreRanges = $MBA_PERCENTILE_RANGE_XAT; 
			} else if($examName == "NMAT"){
				$scoreRanges = $MBA_PERCENTILE_RANGE_NMAT;
			}
		
			$cutOffOptionHtml .= "<option value='0'>$placeHolderText</option>";

			foreach($scoreRanges as $key => $value){
				$cutOffOptionHtml .="<option value='$value'>$key</option>";
			}
 		
	 		if(empty($cutOffOptionHtml)) {
	        	$cutOffOptionHtml = "NO CutOff FOUND";
	        }

		}elseif($subCategoryId == 56){
			$placeHolderText = "Enter Rank";
			if(in_array(trim($examName), $ENGINEERING_EXAMS_REQUIRED_SCORES)){
					$placeHolderText = "Enter Score";
			}

			echo $placeHolderText;
			exit;
		}
		
 		echo  $cutOffOptionHtml;
	}



	/**
	 * submit category ranking widget
	 * @return string url
	 */
	function submitRankingCategoryPage(){
		$examName           =  $this->input->post('examValue',true);
		$otherExamScoreData =  (float)$this->input->post('cutOffValue',true);
		if($otherExamScoreData != 'Select Cutoff'){
			if(!empty($otherExamScoreData)){
				$otherExamScoreData = $otherExamScoreData;
			}else{
				$otherExamScoreData = 0;
			}
		}else{
			$otherExamScoreData = 0;
		}
		
		$locationValue      =  $this->input->post('locationValue',true);
		$categoryId         =  $this->input->post('categoryId',true);
		$subCategoryId      =  $this->input->post('subCategoryId',true);
		$LDBCourseId        =  $this->input->post('LDBCourseId',true);

		$req = $this->load->library("categoryList/CategoryPageRequest");
		$catClient = $this->load->library("categoryList/Category_list_client");




		$buildArray['categoryId'] = $categoryId;
		$buildArray['subCategoryId'] = $subCategoryId;
		// set 23,56 subcat
		if($subCategoryId == 23 || $subCategoryId == 56){
					$req->setNewURLFlag(1);
		}
		if($LDBCourseId > 0){
			$buildArray['LDBCourseId'] = $LDBCourseId;
		}
		if(!empty($examName) && $examName != 'All'){
			$buildArray['examName'] = $examName;
			if($subCategoryId == 23 || $subCategoryId == 56){
				$buildArray['otherExamScoreData'] = array(array("$examName", $otherExamScoreData));
			}
		}

		if(!empty($locationValue)){
			$locationString = explode("-", $locationValue);
			if(is_array($locationString)){
				if($locationString[1]=='city'){
					if($locationString[0] == 2){
						$buildArray['cityId'] = 1;	
					}else{
						$buildArray['cityId'] = $locationString[0];	
					}					
				}elseif($locationString[1]=='state'){
					$buildArray['stateId'] = $locationString[0];								
				}
			}
		}


		$req->setData($buildArray);
		
	

		if($subCategoryId == 23 || $subCategoryId == 56){
			if(!empty($examName)){
				$appliedFilters['courseexams']      = array($examName."_".$otherExamScoreData);
			}
			$appliedFilters['lastmodifieddate'] = '2012-10-01';
		}else{
			$appliedFilters['exams']            = array($examName);
		}
		
		$encoded_filters     = base64_encode(json_encode($appliedFilters));

		global $categoryURLPrefixMapping;
        $domainPrefix = $categoryURLPrefixMapping[$categoryId]; //http://mba.shiksha.com

		$catClient->setCookieCategoryPage('filters-'.$req->getPageKey(),$encoded_filters,0,'/',$domainPrefix);
 
 		$redirectUrl = $req->getUrl();
		if(!empty($redirectUrl)){
			echo $redirectUrl;
		}else{
			echo "No URL Found";
		}
		
		

	}
	
	public function getSessionViewedCourseListings(){
		$this->load->model('common/viewcountmodel');
		$lastViewdCourse = $this->viewcountmodel->getSessionViewedCourseListings();
		return isset($lastViewdCourse['0']['course_id']) ? $lastViewdCourse['0']['course_id'] : 0;
	
	}
	
	function populateNonZeroRankingData(){
		$this->validateCron();
		$RankingNonZeroPagesLib = $this->load->library("rankingV2/RankingNonZeroPagesLib");
		$RankingNonZeroPagesLib->populateRankingNonZeroData();
	}

	private function getMultilocationsForInstitute($rankingPage){

		$rankingPageData = $rankingPage->getRankingPageData();
		$multiLocationCourses = array();
		foreach ($rankingPageData as $pageData) {
			if($pageData->isMultilocation() == 'true'){
				$multiLocationCourses[] = $pageData->getCourseId();
			}
		}
		return $multiLocationCourses;

		// $listingebrochuregenerator = $this->load->library('ListingEbrochureGenerator');
			
		// $multiLocations = $listingebrochuregenerator->getMultilocationsForInstitute($courseList); 
			
		// 	return $multiLocations;
	}

	public function resetNaukriCacheForRankingPage($instituteIds){
		$this->rankingCommonLib->deleteNaukriData($instituteIds);
	}

	public function performanceAnalysis() {
		$handle = fopen(LOG_RANKING_PERFORMANCE_DATA_FILE_NAME, "r");
		// $handle = fopen('/home/hemanth131/Desktop/log_ranking_page.log', 'r');
	    $parentArr = array();
	    if ($handle) {
			while (($line = fgets($handle)) !== false) {
			    $line = trim($line);
			    if(!$line)
					continue;
			    
			    $line = explode("|",$line);
			    
			    $arr = array();
			    foreach($line as $row) {
					$row = explode(":", $row);
					$arr[trim($row[0])] = trim($row[1]);
			    }
			    
			    if($line)
					$parentArr[$arr['Section']][] = $arr;
			}
	    }
	    else {
			_p("Error opening file");
	    }

        echo "<style type='text/css'>
	    body {background:#eee; margin:0; padding:0; font:normal 14px arial;}
	    table {border-left:1px solid #ccc; border-top:1px solid #ccc;}
	    td {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:8px 5px; font-size:13px;}
	    th {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:5px; text-align:left; background:#f6f6f6; font-size:13px;}
	    h1 {font-size:30px; margin-top: 10px;}
	    a {text-decoration:none; color:#444;}
	    #overlay {
	                position: fixed;
	                top: 0;
	                left: 0;
	                width: 100%;
	                height: 100%;
	                background-color: #000;
	                filter:alpha(opacity=50);
	                -moz-opacity:0.5;
	                -khtml-opacity: 0.5;
	                opacity: 0.3;
	                z-index: 10;
	            }
		</style>";
		   
	    echo "<table width='1000' cellspacing='0' cellpadding='0'><tbody><tr><th>#</th><th> Function </th><th>No. of request</th><th>Avg time (MS)</th><th>Avg memory used (MB)</th><th>Memory limit, allocated (MB)</th></tr>";

		$count = 1;
	    foreach($parentArr as $key=>$row)
	    {
			$totalTime = 0;
			foreach($row as $typeRow)
			{
			    $totalTime += $typeRow['Time taken'];
			    $totalMemUsed += $typeRow['Memory used'];
			    $totalMemAllocated += $typeRow['Memory limit (allocated)'];
			}
			echo "<tr>
		    <td valign='top'>".$count++."</td>
		    <td valign='top'>$key</td>
		    <td valign='top'>".count($row)."</td>
		    <td valign='top'>".round($totalTime/count($row), 4) ."</td>
		    <td valign='top'>".round($totalMemUsed/count($row), 4) ."</td>
		    <td valign='top'>".round($totalMemAllocated/count($row), 4) ."</td>
		 	</tr>";
			//_p("Total time for ".$key." is ".$totalTime." for ".count($row)." entries. Average = ".($totalTime/count($row)) . " sec ==> " . ($totalTime/count($row)*1000) . " ms" );
	    }
	    echo "</tbody></table>";
		
	    fclose($handle);
	}

	public function invalidateRankingCache(){
		$this->rankingCommonLib->invalidateRankingObjectCache();
		$this->rankingCommonLib->invalidateRankingPagesCache();
		echo 'Done';
		exit();
	}

	public function removeCompleteRankingCache(){
		$rankingCommonLib = $this->load->library("rankingV2/RankingCommonLib");
		$rankingCommonLib->removeCompleteRankingCache();
		echo 'Done';
	}

	private function _addCHPUrls(& $metaDetails){
		for ($i=0; $i < count($metaDetails['breadcrumb']); $i++) { 
			$meta_tuple = $metaDetails['breadcrumb'][$i];
			$meta_title = $meta_tuple['title'];
			if($meta_title == 'MBA'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/mba-pgdm-chp';
			}
			if($meta_title == 'B.Tech'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/b-e-b-tech-chp';
			}
			if($meta_title == 'Law'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/law-chp';
			}
			if($meta_title == 'Design'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/design-chp';
			}
			if($meta_title == 'Fashion Design'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/design/fashion-design-chp';
			}
			if($meta_title == 'Hospitality & Travel'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/hospitality-travel-chp';
			}
			if($meta_title == 'Hotel / Hospitality Management'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/hospitality-travel/hotel-hospitality-management-chp';
			}
			if($meta_title == 'Mass Communication & Media'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/mass-communication-media-chp';
			}
			if($meta_title == 'BBA'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/bba-chp';
			}
			if($meta_title == 'BCA'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/bca-chp';
			}
			if($meta_title == 'Humanities & Social Sciences'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/humanities-social-sciences-chp';
			}
			if($meta_title == 'Science'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/science-chp';
			}
			if($meta_title == 'Accounting & Commerce'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/accounting-commerce-chp';
			}
			if($meta_title == 'Medicine & Health Sciences'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/medicine-health-sciences-chp';
			}
			if($meta_title == 'Executive MBA'){
				$metaDetails['breadcrumb'][$i]['url'] = SHIKSHA_HOME.'/executive-mba-pgdm-chp';
			}

		}
		return $metaDetails;
	}

	private function _prepareBreadcrumbHtml(& $metaDetails){
		$breadcrumb = $metaDetails['breadcrumb'];
		if(!is_array($breadcrumb)){
			$html = '<div class="breadcrumb2">';
			$html .='<span itemprop="title">'.$breadcrumb.'</span>';	
		    $html .= '</div>';
		    return $html;
		}
		unset($metaDetails['breadcrumb']);
		$html = '<div class="breadcrumb2">';
		$pieces = array();
		foreach($breadcrumb as $element){
			$temp = '<span itemscope="" itemtype="https://data-vocabulary.org/Breadcrumb">';
			if(!empty($element['url'])){
				$temp .= '<a href="'.$element['url'].'" itemprop="url">';
			}
			$temp .='<span itemprop="title">'.$element['title'].'</span>';
			if(!empty($element['url'])){
				$temp .= '</a>';	
			}
			$temp .="</span>";
			$pieces[] = $temp;
		}
		$pieces = implode('<span class="breadcrumb-arrow"> &rsaquo; </span>', $pieces);
		$html.= $pieces;
		$html .= '</div>';
		return $html;
	}

	function _loadMMPForm($displayData){
		if($displayData['rankingPageRequest']->getBaseCourseId() != 101){	// 101 is the base course ID for MBA
			return array();
		}
        $this->load->library('customizedmmp/customizemmp_lib');
        $customizemmpLib    = new customizemmp_lib();
        $mmpType = 'newmmpranking';
        $isLoggedIn          = true;
        if($displayData['validateuser'] == 'false') {
        	$isLoggedIn      = false;
        }
        //$mmpData = $customizemmpLib->seoMMPLayerFromOrganicTraffic($mmpType, $isLoggedIn);
        return $mmpData;
	}

	public function getCategoryPageUrlForRankingPage(){
		$stream 		= $this->input->post('stream');
		$substream 		= $this->input->post('substream');
		$specialization = $this->input->post('specialization');
		$baseCourse 	= $this->input->post('baseCourse');
		$exam 			= $this->input->post('exam');
		$location 		= $this->input->post('location');

		$lib = $this->load->library('nationalCategoryList/NationalCategoryPageLib');
		$params = array();
		$params['stream_id'] = $stream;
		$params['substream_id'] = $substream;
		$params['specialization_id'] = $specialization;
		$params['base_course_id'] = $baseCourse;
		$params['exam_id'] = $exam;
		$location = explode('-', $location);
		if($location[1] == "state"){
			$params['state_id'] = $location[0];
		}elseif($location[1] == "city" && $location[0] != 2){
			$params['city_id'] = $location[0];
		}
		$url = $lib->getUrlByParams($params);
		if(empty($url)){
			$filterParams = array();

			if(!empty($params['stream_id'])) { //if we have stream, retain it and remove base course, else, base course must be retained
				$filterParams['base_course'] = $params['base_course_id'];
				unset($params['base_course_id']);
			}
			
			$filterParams['substream'] = $params['substream_id'];
			unset($params['substream_id']);

			$filterParams['specialization'] = $params['specialization_id'];
			unset($params['specialization_id']);
			
			$filterParams['exam'] = $params['exam_id'];
			unset($params['exam_id']);
			
			$url = $lib->getUrlByParams($params,$filterParams);
		}
		echo $url;
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
	
		$this->userStatus = $this->checkUserValidation();
		if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
		    $this->userid = $this->userStatus[0]['userid'];
		} else {
		    $this->userid = -1;
		}
	}

	function getRankingPageCatData($rankingPageId = NULL){
		$this->_initRankingPage();
		if($rankingPageId ==NULL){
			$rankingPageId = $this->input->post('RankingPageId');
		}
		if(empty($rankingPageId)){
			return;
		}
		$params=array();
		$params['rankingPageId'] 	= (int)$rankingPageId;
		$params['countryId'] 	   	= 2;
		$params['stateId'] 	   		= 0;
		$params['cityId'] 	   		= 0;
		$params['examId'] 	   	   	= 0;
		
		$rankingPageRequest		= $this->rankingURLManager->getRankingPageRequestFromDataArray($params);

		$criteria = $this->rankingFilterManager->prepareRankingPageCriteria($rankingPageRequest);
		$availablePublishers = $this->rankingFilterManager->rankingModel->getFilters($criteria, 'publisher');
		$availablePublisherIds = array_keys($availablePublishers);
		$rankingPageRepository	= RankingPageBuilder::getRankingPageRepository();
		$rankingPage			= $rankingPageRepository->find($rankingPageId, null,null, $availablePublisherIds);
		$rankingPageFilters		= $this->rankingFilterManager->getFilters($rankingPage, $rankingPageRequest);
		$city=array();$state=array();$exam=array();
		foreach($rankingPageFilters['city'] as $cityData)
		{
			$city[]=array('id' => $cityData->getId(),'name'=>$cityData->getName());
		}
		foreach($rankingPageFilters['state'] as $stateData)
		{
			if(($stateData->getId()>=128 && $stateData->getId()<=131) || $stateData->getId()==134 ||
			$stateData->getId()==135 || $stateData->getId()==345)
			continue;
			$state[]=array('id' => $stateData->getId(),'name'=>$stateData->getName());
		}
		foreach($rankingPageFilters['exam'] as $examData)
		{
			$exam[]=array('id' => $examData->getId(),'name'=>$examData->getName());
		}			
		echo json_encode(array('city' => $city, 'state' => $state,'exam' => $exam));
	}

	function showRankingPage($urlIdentifier = null) {

		$this->_initRankingPage();
		
		$rankingPageRequest		= $this->rankingURLManager->getRankingPageRequest($urlIdentifier);

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

		$rankingPageRepository	= RankingPageBuilder::getRankingPageRepository();
		$rankingPage			= $rankingPageRepository->find($rankingPageId, null, $publisherId, $availablePublisherIds);
		$rankingPageSource		= $rankingPage->getPublisherData();
		$rankingPageDisclaimer 	=	strip_tags($rankingPage->getDisclaimer());
		if(empty($rankingPageSource)){
			show_404();
		}		
		$rankingPageFilters		= $this->rankingFilterManager->getFilters($rankingPage, $rankingPageRequest);
	
		$metaDetails               = $this->rankingURLManager->getRankingPageMetaData($rankingPage,$rankingPageRequest);
		$metaDetails = $this->_addCHPUrls($metaDetails);
		$breadcrumbHtml            = $this->_prepareBreadcrumbHtml($metaDetails);
		$rankingPageOf			= $this->rankingcommonlib->checkForRankingPageTupleWidget($rankingPage);
		$this->benchmark->mark('prepare_interlinking_data_start');
		$examWidgetData            = $this->rankingcommonlib->prepareExamWidgetData($rankingPageRequest);
		$articlesData              = $this->rankingcommonlib->prepareArticlesInterlinkingData($rankingPageRequest);
		$interlinkingWidgetHeading = $this->rankingcommonlib->getInterlinkingWidgetHeading($rankingPageFilters, $rankingPageRequest, $rankingPage);
		$this->benchmark->mark('prepare_interlinking_data_end');
		
		$displayData = array();
		$this->benchmark->mark('tuple_start');
		$this->rankingcommonlib->prepareRankingTupleData($rankingPage,$rankingPageRequest,$displayData);
		// _p(array_keys($displayData['instituteInfo']));die;
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
		$displayData['breadcrumbHtml']				= $breadcrumbHtml;
		$displayData['rankingPageOf']				= $rankingPageOf;
		$displayData['rankingPageMainSourceId']		= $rankingPageMainSourceId;
		$displayData['articleWidgetsData']			= $articlesData;
		$displayData['examWidgetData']				= $examWidgetData;
		$displayData['tuplesPerPage']				= 30;
		$displayData['interlinkingWidgetHeading']	= $interlinkingWidgetHeading;
		$displayData['beaconTrackData'] 			= $this->rankingcommonlib->getBecaonTrackData($rankingPageRequest);
        $displayData['beaconTrackData']['extraData'] = array_filter($displayData['beaconTrackData']['extraData']);
        $displayData['beaconTrackData']['extraData']['hierarchy'] = array_filter($displayData['beaconTrackData']['extraData']['hierarchy']);
        $displayData['gtmParams'] = $this->rankingcommonlib->getScanParams($displayData['beaconTrackData']['extraData'],$displayData['validateuser']);

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_RankingPage','entity_id'=>$rankingPageId,'stream_id'=>$displayData['gtmParams']['stream'],'substream_id'=>$displayData['gtmParams']['substream'],'specialization_id'=>$displayData['gtmParams']['specialization'],'baseCourse'=>$displayData['gtmParams']['baseCourseId'],'cityId'=>$displayData['gtmParams']['cityId'],'stateId'=>$displayData['gtmParams']['stateId'],'educationType'=>$displayData['gtmParams']['educationType'],'deliveryMethod'=>$displayData['gtmParams']['deliveryMethod']);

        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');
        
        $displayData['canonical'] = explode('?',getCurrentPageURL())[0];;

        $displayData['trackForPages'] = true; //For JSB9
        $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');
        if($rankingPage->getTupleType() != 'course'){
        	$displayData['websiteTourContentMapping']['Filters'] = 'Filter colleges by fees, exams, location, specialization etc.';
        }
		
		$displayData['mmpData']				= $this->_loadMMPForm($displayData);
		// _p($displayData['mmpData']);
        // die;
        if($isAjaxCall) {
			$yearHeadingSection = $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_year_section', $displayData, true);

			$tuple = $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_table', $displayData, true);
			echo json_encode(array('tuple' => $tuple, 'yearHeadingSection' => $yearHeadingSection));
			exit;
        }
        else {
			$this->benchmark->mark('view_start');
			$this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_overview', $displayData);
			$this->benchmark->mark('view_end');

        }
	}

	public function getInstituteCoursesForCTA() {
		$instituteId = $this->input->post('instituteId', true);
		$insttWisecourses = $this->input->post('insttWisecourses', true);

		$this->load->builder('nationalInstitute/InstituteBuilder');
		$instituteBuilder = new InstituteBuilder();
		$instituteRepository = $instituteBuilder->getInstituteRepository();

		$instituteIds = array_keys($insttWisecourses['instituteWiseCourses']);
		if(empty($instituteIds)) {
			return;
		}
		$instituteObjs = $instituteRepository->findMultiple($instituteIds);

		$this->load->builder('nationalCourse/CourseBuilder');
		$courseBuilder = new CourseBuilder();
		$courseRepository = $courseBuilder->getCourseRepository();

		$courseObjs = $courseRepository->findMultiple($insttWisecourses['courseIds']);

		foreach ($insttWisecourses['instituteWiseCourses'] as $instituteId => $courses) {
			$instituteObj=$instituteObjs[$instituteId];

			foreach ($courses as $key => $courseId) {
				$courseObj=$courseObjs[$courseId];
				$courseName = $courseObj->getName();
				
	                if($instituteObj->getListingType() == 'university'){
			        $instituteName = $courseObj->getOfferedByShortName();
		                $instituteName = $instituteName ? $instituteName : $instituteObj->getShortName();
		                $instituteName = $instituteName ? $instituteName : $instituteObj->getName();
	                        $courseName .= ", ".$instituteName;
	                }
				$courseList[] = array('course_id' => $courseId, 'course_name' => $courseName);
			}
		}
		
		echo json_encode($courseList);
	}



}
