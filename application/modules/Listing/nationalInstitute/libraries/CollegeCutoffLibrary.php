<?php 

class CollegeCutoffLibrary{
	function __construct(){
		$this->CI = & get_instance();
	}

	function init(){
		$this->userStatus = $this->CI->checkUserValidation();
		$this->CI->load->builder("nationalCourse/CourseBuilder");
	    $courseBuilder = new CourseBuilder();
	    $this->courseRepo = $courseBuilder->getCourseRepository();
		

		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();
	    $this->instituteRepo = $instituteBuilder->getInstituteRepository();

	    $this->CI->load->builder('BranchBuilder','CP');
	    $this->branchBuilder = new BranchBuilder;
	    $this->branchRepository = $this->branchBuilder->getBranchRepository();

	    $this->CI->load->config('nationalInstitute/CollegeCutoffConfig',True);
	    $this->instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');

	    $this->institutedetailmodel = $this->CI->load->model('nationalInstitute/institutedetailsmodel');

		$this->cpmodel = $this->CI->load->model('CP/cpmodel');

	    $this->CI->load->helper('image');
	}

	public function getDataForCutoffPage($listingId,$category = '', $start = 0){
		$this->init();
		$collegesData = $this->CI->config->item('colleges','CollegeCutoffConfig');
		$parentListingsIdsData = $this->CI->config->item('parentListingIds','CollegeCutoffConfig');
		$idToCollegeMapping = $this->CI->config->item('idToCollegeMapping','CollegeCutoffConfig');

		$specializationId = $this->CI->input->get('sp');
		$specializationId = $this->CI->security->xss_clean($specializationId);
		$instituteObj  = $this->instituteRepo->find($listingId);
		$instituteId   = $instituteObj->getId();
		$instituteType = $instituteObj->getType();
		$ownership     = ($instituteObj->getOwnership()) ? $instituteObj->getOwnership() : '';
		$isAjaxRequest = $this->CI->input->is_ajax_request();
		if(!$isAjaxRequest){
			$this->_checkForCommonRedirections($instituteObj, $instituteId, $instituteType, 'cutoff', $category);
		}

		$displayData = array();

		$currentLocationObj          = $this->instituteDetailLib->getInstituteCurrentLocation($instituteObj);
		$data['currentLocationObj']  = $currentLocationObj;
		$data['instituteLocations']  = $instituteObj->getLocations();
		$data['isMultilocation']     = count($data['instituteLocations']) > 1 ? true : false;

		if(empty($instituteObj) || empty($instituteId)){
		    show_404();
		    exit(0);
		}

		$data['coursesWidgetData']   = $this->instituteDetailLib->getInstitutePageCourseWidgetData($instituteObj, $currentLocationObj, $data['isMultilocation'], 2, 2, 'mobile');

		$flagshipCourseId = $data['coursesWidgetData']['flaghshipCourse'];
		
		$displayData['flagshipCourseId'] = $flagshipCourseId;   

		$isChildInstitutePage = true;

		if($parentListingsIdsData[$listingId]){
			$isChildInstitutePage = false;
			$parentListingId = $listingId;
		}
		else{
			$instituteHierarchy = $this->instituteDetailLib->getInstituteListingHierarchyDataNew(array($listingId));
			$instituteHierarchy = $instituteHierarchy[$listingId];

			foreach ($instituteHierarchy['university'] as $row) {
				if(!empty($parentListingsIdsData[$row['listing_id']])){
					$parentListingId = $row['listing_id'];
					break;
				}
			}
			if(empty($parentListingId)){
				foreach ($instituteHierarchy['institute'] as $row) {
					if(!empty($parentListingsIdsData[$row['listing_id']])){
						$parentListingId = $row['listing_id'];
						break;
					}
				}
			}
			if(empty($parentListingId)) {
				foreach($data['coursesWidgetData']['allCourses'] as $courseId => $courseObj) {
					$affiliationData = $courseObj->getAffiliations();
					if(!empty($parentListingsIdsData[$affiliationData['university_id']])) {
						$parentListingId = $parentListingsIdsData[$affiliationData['university_id']];
					}
				}
			}
		}

		if(empty($parentListingId)){
			show_404();
			exit(0);
		}

		$abbreviation = $idToCollegeMapping[$parentListingId];
		$seoUrl = $instituteObj->getAllContentPageUrl('cutoff');
		
		if(!empty($category) && !in_array($category, array_keys($collegesData[$abbreviation]['categoryData']))){
			if(in_array(strtolower($category), array_keys($collegesData[$abbreviation]['categoryData']))){
				$seoUrl .= '/'.strtolower($category);	
			}		
			
			header("Location: $seoUrl",TRUE,301);
			return;
		}
		if(empty($category)){
			$category = $collegesData[$abbreviation]['defaultCategory'];
		}
		$cutoffRange = $this->CI->input->get('cutoff', TRUE)?$this->CI->input->get('cutoff', TRUE):'';

		$displayData['instituteType'] = $instituteType;
		$displayData['ownership']     = $ownership;
		$displayData['canonicalURL'] = $seoUrl;
		$parentListingObj = $this->instituteRepo->find($parentListingId);
		$displayData['parentListingUrl'] = $parentListingObj->getURL();
		$displayData['admissionsUrl'] = $instituteObj->getAllContentPageUrl('admission');
		$displayData['isChildInstitutePage'] = $isChildInstitutePage;
		$displayData['instituteName'] = $instituteObj->getName();
		$displayData['instituteId'] = $listingId;
		// $displayData['categoryName'] =  in_array($category,array('general','minority'))?ucfirst($category):strtoupper($category);
		$displayData['categoryName'] = $category;
		if(!empty($collegesData[$abbreviation]['categoryData'][$category])) {
			$displayData['categoryName'] = $collegesData[$abbreviation]['categoryData'][$category]; 
		}
		$displayData['specializationId'] = $specializationId;
		$displayData['examName'] = $abbreviation;

		$appliedFilters = array();
		$appliedFilters['examName'] = $abbreviation;
		$appliedFilters['rank'] = $collegesData[$abbreviation]['maxValue'];
		$appliedFilters['invertLogic'] = $collegesData[$abbreviation]['invertLogic'];
		$appliedFilters['rankType'] = $collegesData[$abbreviation]['defaultRankType'];
		$appliedFilters['categoryName'] = $category;
		if(!empty($specializationId) && $specializationId>0){
			$appliedFilters['specializationId'] = $specializationId;
		}
		$appliedFilters['start'] = $start;
		$appliedFilters['offset'] = (isMobileRequest())?20:30;
		$appliedFilters['filterListingId'] = $parentListingId;
		if($cutoffRange !=''){
			$filterRange = $this->getCutoffFilterRange($appliedFilters);
			if($filterRange[$cutoffRange]){
				$displayData['cutoffRange'] =$cutoffRange ;
				$appliedFilters['cutoffRange']['start'] = $filterRange[$cutoffRange][0];
				$appliedFilters['cutoffRange']['end'] = $filterRange[$cutoffRange][1];
			}
		}
		if(empty($displayData['cutoffRange'])){
			$displayData['cutoffRange'] = 0;
		}
		if($isChildInstitutePage){
			$instituteWiseCourses = $this->instituteDetailLib->getAllCoursesForInstitutes($instituteId,'direct');
			if(empty($instituteWiseCourses['instituteWiseCourses'][$instituteId])){
				show_404();
				exit(0);
			}

			$appliedFilters['courseIds'] = $instituteWiseCourses['instituteWiseCourses'][$instituteId];
			$displayData['allCoursesUrl'] = $instituteObj->getAllContentPageUrl('courses');
		}
		else{
			$displayData['allCoursesUrl'] = $parentListingObj->getAllContentPageUrl('courses');
		}

		$branchObjData = $this->branchRepository->findMultiple($appliedFilters);
		if(empty($branchObjData['totalBranchCount'])){
			show_404();
			exit(0);
		}
		$branchObj = $branchObjData['branchEntityObj'];
		$branchCount = $branchObjData['totalBranchCount'];
		$branchData = (is_object($branchObj)) ? $branchObj->getPageData() : array();
		$courseIds = array();
		// remove all rounds containing only zeroes at the end
		
		foreach ($branchData as $row) {
			$roundInfo = $row->getRoundsInfo();
			$numberOfRounds = count($roundInfo);

			$courseIds[$row->getShikshaCourseId()] = $row->getShikshaCourseId();

			$startRound = null;
			$endRound = null;
			foreach ($roundInfo as $roundData) {
				if(!empty($roundData['closingRank'])){
					if(empty($startRound)){
						$startRound = $roundData['round'];
					}
					$endRound = $roundData['round'];
				}
			}
			$temp = array();
			foreach ($roundInfo as $roundData) {
				if($roundData['round'] >= $startRound && $roundData['round'] <= $endRound){
					$temp[] = $roundData;
				}
			}
			$row->setRoundsInfo($temp);
		}
		$displayData['filterData'] = $this->getFilters($appliedFilters,$data=array("allCategories" => array_keys($collegesData[$abbreviation]['categoryData']),'mainUrl'=>$seoUrl, 'categoryData' => $collegesData[$abbreviation]['categoryData']));
		$displayData['branchInformation'] = $branchData;
		$displayData['courseData'] = $this->getInterlinkingDataForTuples($branchData);
		
		if(!$isAjaxRequest){
			foreach($displayData['courseData'] as $data){
				$courseName[] = $data['courseName'];
				if(count($courseName)>=2)
					break;
			}

			$displayData['seoData'] = $this->getSeoDetails($instituteObj, $abbreviation, $courseName);
			$displayData['previewText'] = $this->getPreviewText($displayData, $instituteObj, $abbreviation, $isChildInstitutePage);
		}

		if(isMobileRequest()){
			$this->CI->load->helper('html'); 
			$displayData['shortText'] = getTextFromHtml($displayData['previewText'][0],140);
			$displayData['brochureTrackingKeyId'] = '1611';
			$displayData['compareTrackingKeyId'] = '1613';
			$displayData['shortlistTrackingKeyId'] = '1615';
		} else {
			$displayData['brochureTrackingKeyId'] = '1605';
			$displayData['compareTrackingKeyId'] = '1607';
			$displayData['shortlistTrackingKeyId'] = '1609';
		}

		if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
		    $this->userid = $this->userStatus[0]['userid'];
			$displayData['shortlistedCoursesOfUser']	= Modules::run('myShortlist/MyShortlist/getShortlistedCourse', $this->userid);
		} else {
		    $this->userid = -1;
		}
		$displayData['totalCount'] = $branchCount;
		$displayData['validateuser'] = $this->userStatus;

		$beaconPageName = "cutOffPage";
		$displayData['beaconTrackData'] = array(
                                        'pageIdentifier' => "UILP",
                                        'pageEntityId' => $instituteObj->getId(),
                                        'extraData' => array("childPageIdentifier"=>$beaconPageName,'url'=>get_full_url())
                                    );

		$displayData['mainLocationObj'] = $instituteObj->getMainLocation();
        $displayData['beaconTrackData']['extraData']['cityId'] = $displayData['mainLocationObj']->getCityId();
        $displayData['beaconTrackData']['extraData']['stateId'] = $displayData['mainLocationObj']->getStateId();
        $displayData['beaconTrackData']['extraData']['countryId'] = 2;
		return $displayData;
	}


	private function getFilters($filterData,$data){
		$categoryFilters=array();
		$filterResponse = array();
		$shikshaCourseIds = array(); 
		$categoryData = $this->cpmodel->getCutoffCategoryByCourseIds($filterData['examName'],$filterData['courseIds']);
		
		if(count($filterData['courseIds'])>0){ // child page
			$shikshaCourseIds = $filterData['courseIds']; 
		}
		else if(count($data['allCategories'])>0){ // parent univ
			// $categoryData = $data['allCategories'];
		}
		
		//check to get category data on DU filters
		if($filterData['examName'] == 'DU') {
			$isDuPage = 1;
			$data['categoryData'] = array_flip($data['categoryData']);
		}
		
		foreach ($categoryData as $cat) {
			if($isDuPage && !empty($data['categoryData'][$cat])) {
				$categoryFilters[$cat] = $data['mainUrl']."/".strtolower($cat);
			}
			else {
				$categoryFilters[$data['categoryData'][$cat]] = $data['mainUrl']."/".strtolower($cat);
			}
		}
		$cutoffInstitutes = $this->cpmodel->getCutoffInstitutes($filterData['examName'],array('filterListingId'=>$filterData['filterListingId'])); 
		function cmp($a, $b)
		{
		    return strcmp($a["name"], $b["name"]);
		}
		usort($cutoffInstitutes, "cmp");
		$cutoffSpecializations = $this->cpmodel->getCutoffSpecializations($filterData['examName'],$filterData['categoryName'],$shikshaCourseIds); 
		uasort($cutoffSpecializations, "cmp");

		$filterResponse['categoryFilters'] = $categoryFilters;
		$filterResponse['collegeFilters'] = $cutoffInstitutes;
		$filterResponse['specializationFilters'] = $cutoffSpecializations;
		
		if(empty($filterData['specializationId']) || $filterData['specializationId'] == 'null'){
			$selectedSpecialization = array();	
		}
		else{
			$selectedSpecialization = array($filterData['specializationId']);	
		}
		$this->CI->benchmark->mark('cutoff_filter_start');
		$closingRankList = $this->cpmodel->getAllClosingRank($filterData['examName'],$filterData['categoryName'],$selectedSpecialization,$shikshaCourseIds);
		$filterResponse['cutoffFiltersCompleteList'] = $this->getCutoffFilterRange($filterData);
		$filterResponse['bucketToShow'] = $this->getBucketNumberToShow($filterResponse['cutoffFiltersCompleteList'],$closingRankList);
		$this->CI->benchmark->mark('cutoff_filter_end');
		return $filterResponse;
	}

	private function getInterlinkingDataForTuples($result){
		$courseIds = array();$courseObjs = array();
		$instituteIds = array();$instituteObjs = array();
		foreach ($result as $branchObj) {
			if($branchObj->getShikshaCourseId() > 0){
				$courseIds[$branchObj->getShikshaCourseId()] = $branchObj->getShikshaCourseId();
			}
		}
		$courseIds = array_keys($courseIds);

		if(!empty($courseIds)){
			$courseObjs = $this->courseRepo->findMultiple($courseIds);
			foreach ($courseObjs as $courseId => $courseObj) {
				if($courseObj && $courseObj->getId() > 0){
					$instituteIds[$courseObj->getInstituteId()] = $courseObj->getInstituteId();
				}
			}
		}

		if(!empty($instituteIds)){
			$instituteObjs = $this->instituteRepo->findMultiple(array_values($instituteIds),array('media'));
			foreach ($instituteObjs as $instituteId => $instituteObj) {
				if($instituteObj && $instituteObj->getId() > 0){
					$instituteData[$instituteId]['url'] = $instituteObj->getURL();

					$instituteThumbURL = $instituteObj->getHeaderImage();
					if(!empty($instituteThumbURL)){
						$instituteData[$instituteId]['thumbUrl'] = getImageVariant($instituteThumbURL->getUrl(),2);
					}
					else{
						$instituteData[$instituteId]['thumbUrl'] = IMGURL_SECURE."/public/images/ranking_default_desktop.png";
					}
				}
			}
		}

		foreach ($courseObjs as $courseId => $courseObj) {
			if($courseObj && $courseObj->getId() > 0){
				$instituteId = $courseObj->getInstituteId();
				$courseData[$courseId]['courseUrl'] = $courseObj->getURL();
				$courseData[$courseId]['courseName'] = $courseObj->getName();
				$courseData[$courseId]['cityName'] = $courseObj->getMainLocation()->getCityName();
				$courseData[$courseId]['localityName'] = $courseObj->getMainLocation()->getLocalityName();
				$courseData[$courseId]['instituteName'] = $courseObj->getInstituteName();
				$courseData[$courseId]['instituteId'] = $instituteId;
				$courseData[$courseId]['instituteUrl'] = $instituteData[$instituteId]['url'];
				$courseData[$courseId]['thumbUrl'] = $instituteData[$instituteId]['thumbUrl'];
			}
		}
		return $courseData;
	}

	private function _checkForCommonRedirections($instituteObj, $listingId, $listingType, $pageType,$category){

	    $currentUrl = getCurrentPageURLWithoutQueryParams();
	    /*If institute id does'nt exist, check whether the status of institute is deleted,
	      if yes then 301 redirect to migrated institute page Or show 404 */
	    if(empty($instituteObj) || $instituteObj->getId() == ''){
	           $newUrl = $this->institutedetailmodel->checkForDeletedInstitute($listingId,$listingType);
	           if(!empty($newUrl)){
	                header("Location: $newUrl",TRUE,301);
	                exit;
	           }else{
	                show_404();
	                exit(0);
	           }
	    }

	     //check for dummy institute.If true, show 404 error 
	    if($instituteObj->isDummy() == TRUE){
	            show_404();
	            exit(0);
	    }

	    if(!empty($instituteObj) && ($instituteObj->getId() != '')){

	        $seo_url     = $instituteObj->getURL()."/".$pageType;
	        if(!empty($category)){
	        	$seo_url .= "/".$category;
	        }
	            

	        $allContentPageUrl = $seo_url;
	        $disable_url = $instituteObj->getDisableUrl();

	        $queryParams = $_GET;
	        if(!empty($queryParams) && count($queryParams) > 0)
	        {
	            $seo_url .= '?'.http_build_query($queryParams);
	        }

	        //check if url is different from original url, 301 redirect to main url
	        if((($currentUrl != $allContentPageUrl) || ($instituteObj->getType() != $listingType))){           
				if( (strpos($seo_url, "http") === false) || (strpos($seo_url, "http") != 0) || (strpos($seo_url, SHIKSHA_HOME) === 0) || (strpos($seo_url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($seo_url,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($seo_url,ENTERPRISE_HOME) === 0) ){
					header("Location: $seo_url",TRUE,301);
				}
				else{
				    header("Location: ".SHIKSHA_HOME,TRUE,301);
				}
	            exit;
	        }

	        //Redirect to disabled url
	        if($disable_url != ''){
				if( (strpos($disable_url, "http") === false) || (strpos($disable_url, "http") != 0) || (strpos($disable_url, SHIKSHA_HOME) === 0) || (strpos($disable_url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($disable_url,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($disable_url,ENTERPRISE_HOME) === 0) ){
					header("Location: $disable_url",TRUE,301);
				}
				else{
				    header("Location: ".SHIKSHA_HOME,TRUE,301);
				}
	            exit;
	        }
	    }
	}

	function getPreviewText($displayData, $instituteObj, $abbreviation, $isChildInstitutePage) {
		switch ($abbreviation) {
			case 'DU':
			case 'MU':
				//preview text
		    	$this->CI->load->builder("listingBase/ListingBaseBuilder");
				$listingBaseBuilder = new ListingBaseBuilder();
				$baseCourseRepo = $listingBaseBuilder->getBaseCourseRepository();

		    	$cachedData = $this->CI->nationalinstitutecache->getInstituteCourseWidgetNew($instituteObj->getId());
				$cachedData = json_decode($cachedData,true);
				if(empty($cachedData)){
					$show404 = true;
				}
				else{
					$baseCourseIds = $cachedData['baseCourseIds'];
					if($baseCourseIds){
						$baseCourseObjects = $baseCourseRepo->findMultiple($baseCourseIds);
						foreach ($baseCourseObjects as $key => $baseCourseObject) {
							$baseCourseNames[] = $baseCourseObject->getAlias() ? $baseCourseObject->getAlias() : $baseCourseObject->getName();
						}
						$bips = implode(', ', $baseCourseNames);
					}
				}
				break;
			
			default:
				break;
		}

		$instituteShortName = !empty($instituteObj->getShortName()) ? $instituteObj->getShortName() : $instituteObj->getName();
		$collegeCutOffConfig = $this->CI->config->item('colleges','CollegeCutoffConfig');
		if($isChildInstitutePage){
			$previewTextTemplate = $collegeCutOffConfig[$abbreviation]['childCollegesPreviewText'];
			
			$search = array('<shortName>', '<institute_name>','<courseCount>','<allCoursesUrl>','<admissionsUrl>','<parentListingUrl>', '<bips>');
			$replace = array($instituteShortName, $instituteObj->getName(),count($displayData['courseData']),$displayData['allCoursesUrl'],$displayData['admissionsUrl'],$displayData['parentListingUrl'], $bips);
			foreach ($previewTextTemplate as $key => $row) {
				if(empty($bips) && $key == 1) {
					continue;
				}
				$previewText[] = str_replace($search,$replace,$row);
			}
		}
		else{
			$previewTextTemplate = $collegeCutOffConfig[$abbreviation]['previewText'];
			$search = array('<shortName>', '<institute_name>','<courseCount>','<allCoursesUrl>','<admissionsUrl>','<parentListingUrl>', '<bips>');
			$replace = array($instituteShortName, $instituteObj->getName(),count($displayData['courseData']),$displayData['allCoursesUrl'],$displayData['admissionsUrl'],$displayData['parentListingUrl'], $bips);
			foreach ($previewTextTemplate as $key => $row) {
				if(empty($bips) && $key == 1) {
					continue;
				}
				$previewText[] = str_replace($search,$replace,$row);
			}
		}
    	return $previewText;
	}

	function getSeoDetails($instituteObj, $abbreviation, $courseName) {
		switch ($abbreviation) {
			case 'DU':
				$seoData = $this->getSeoDetailsForDU($instituteObj,$courseName);
				break;

			case 'MU':
				$seoData = $this->getSeoDetailsForMU($instituteObj);
				break;
			
			default:
				$seoData = $this->getSeoDetailsForMU($instituteObj);
				break;
		}
		
    	return $seoData;
	}

	function getSeoDetailsForMU($instituteObj) {
		$collegeCutOffConfig = $this->CI->config->item('colleges','CollegeCutoffConfig');
		$titleTemplate = $collegeCutOffConfig['MU']['metaTitle'];
		$descriptionTemplate = $collegeCutOffConfig['MU']['metaDescription'];

		$instituteName = !empty($instituteObj->getShortName()) ? $instituteObj->getShortName() : $instituteObj->getName();
		$currentYear = date('Y');

		$search = array('<shortName>', '<currentYear>');
		$replace = array($instituteName, $currentYear);

		$seoData['seoTitle'] = str_replace($search, $replace, $titleTemplate);
    	$seoData['seoDesc'] = str_replace($search, $replace, $descriptionTemplate);

    	return $seoData;
	}

	function getSeoDetailsForDU($instituteObj,$courseName) {
		$metaTitleConfig = $this->CI->config->item('metaTitle','CollegeCutoffConfig');
		$metaDescriptionConfig = $this->CI->config->item('metaDescription','CollegeCutoffConfig');
    	$abbreviation = $instituteObj->getAbbreviation();
    	$instituteName = $instituteObj->getName();
    	$title = empty($abbreviation)? $metaTitleConfig['noAbbreviation'] : $metaTitleConfig['withAbbreviation'];
    	if(empty($abbreviation)){
    		$description = sizeof($courseName)==1 ? $metaDescriptionConfig['noAbbreviationOneCourse'] : $metaDescriptionConfig['noAbbreviation'];
    	}
    	else{
    		$description = sizeof($courseName)==1 ? $metaDescriptionConfig['withAbbreviationOneCourse'] : $metaDescriptionConfig['withAbbreviation'];
    	}
    	if(sizeof($courseName)==1){
    		$search = array('<fullName>','<abbr>','<coursName-cutoff-1>');
    		$replace = array($instituteName,$abbreviation,$courseName[0]);
    	}
    	else{
    		$search = array('<fullName>','<abbr>','<coursName-cutoff-1>','<coursName-cutoff-2>');
    		$replace = array($instituteName,$abbreviation,$courseName[0],$courseName[1]);
    	}
    	//$baseUrl = $instituteObj->getUrl();
    	$seoData['seoTitle'] = str_replace($search, $replace, $title);
    	$seoData['seoDesc'] = str_replace($search, $replace, $description);

    	return $seoData;
	}


	function getBucketNumberToShow($bucketList, $closingRankData){
		$bucketToShow = array();
		$bucketToShow[0] = 1;
		$bucketNumber = 1;
		$closingRankIndex = 0;
		while($bucketNumber < count($bucketList) && $closingRankIndex < count($closingRankData)){
			$bucketRange = $bucketList[$bucketNumber];
			$lowerRange  = (int)$bucketRange[0];
			$upperRange  =  (int)$bucketRange[1];
			$currentRank = (int)$closingRankData[$closingRankIndex];
			if($currentRank>=$lowerRange && $currentRank < $upperRange){
				$bucketToShow[$bucketNumber] = 1;
				$bucketNumber++;
				$closingRankIndex++;
			}
			else if ($currentRank<$lowerRange){
				$bucketNumber++;	
			}
			else{
				$closingRankIndex++;
			}
		}
		return $bucketToShow;
	}

	function getCutoffFilterRange($filterData){
		$cutoffFilters = array();
		$cutoffFilters[0] = 'All';
		$cutOffRangeStart =100; 
		$range = 2;
		$index = 1;
		$startRange = 40;
		if($filterData['examName'] == 'MU') {
			$startRange = 45;
		}
		while($cutOffRangeStart>$startRange) {
			if($cutOffRangeStart == 80){
				$range = 5;
			}	
			$cutoffFilters[$index][0] = $cutOffRangeStart-$range;
			$cutoffFilters[$index++][1] = $cutOffRangeStart;
			$cutOffRangeStart = $cutOffRangeStart-$range;
		}
		return $cutoffFilters;
	}

}
?>
