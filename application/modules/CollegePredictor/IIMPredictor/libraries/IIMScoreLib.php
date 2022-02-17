<?php

/*
   Copyright 2015 Info Edge India Ltd
	
	Note:- The main idea behind developing ICP in this way was to make things configurable(Special requirement from product) 
	because IIM data will change every year. So code is made in such a way to entertain most of changes without much of code change.
 */

class IIMScoreLib{

	/* Function to calculate score for eligible IIM's
	 * @params: $eligibleIIMS => array having list of IIM's in which user is eligible
	 *			$data => User data sent from POST
	 * @return: required CAT data
	 */

	function __construct(){
		$this->CI = & get_instance();
		$this->iimpredictormodel = $this->CI->load->model('IIMPredictor/iimpredictormodel');
		$this->CI->load->config('IIMPredictor/newPredictorConfig');
	}

	public function scorePredictor($eligibleIIMS, $data){
		/* cutOffConfig contains the cut-off data of each IIM */
		require_once APPPATH.'modules/CollegePredictor/IIMPredictor/config/cutOffConfig.php';
		/* percentileConfig contains the marks to percentile mapping, provided by Saurabh Gupta */
		require_once APPPATH.'modules/CollegePredictor/IIMPredictor/config/percentileConfig.php';
		/* scorePredictorConfig contains formula's to calculate score for each IIM*/
		require_once APPPATH.'modules/CollegePredictor/IIMPredictor/config/scorePredictorConfig.php';

		/* Holds data to be returned */
		$returnData = array();

		/* In the current scenerio, we have 4 IIM's for which we are not calculating score, 
			list of those IIM's will be stored in $IIMWithOutData */
		$IIMWithData = array(); 
		$IIMWithOutData = array();
		/* Loop through each eligible IIM */
		foreach($eligibleIIMS as $key=>$IIM){
			/* Loading IIM score data variable dynamically from scorePredictorConfig.php */	
			$currentIIMScoreData = $IIM.'_ScoreData';
			$currentIIMScoreData = ${$currentIIMScoreData};
			/* Checking if data is available or not, score_calculated is the key which holds this value */
			if(isset($currentIIMScoreData) && $currentIIMScoreData['score_calculated'] == 'YES'){
				/* this check if calculated required score is less then eligibilty score then make required equal to eligibility */
				if($currentIIMScoreData['cutoff_percentile']['Total_Percentile'] < $currentIIMScoreData['required_percentile']){
					$currentIIMScoreData['cutoff_percentile']['Total_Percentile'] = $currentIIMScoreData['required_percentile'];
				}
				/* variable holds the cut-off data */
				$IIMWithData[$IIM] = $currentIIMScoreData['cutoff_percentile'];
				
				/* Chances will be high if user cat-percentile is higher then equal to required */
				if($data['cat_percentile'] >= 0){
					if( $data['cat_percentile'] >= $currentIIMScoreData['cutoff_percentile']['Total_Percentile']){
						$IIMWithData[$IIM]['chances'] = 'High';
					}else{
						$IIMWithData[$IIM]['chances'] = 'Low';
					}
				}

				$IIMWithData[$IIM]['score_calculated'] = 'YES';

			}else{
				$IIMWithOutData[$IIM]['score_calculated'] = 'No';
			}
		}

		/* ICPConfig holds the helper variables*/
		require APPPATH.'modules/CollegePredictor/IIMPredictor/config/ICPConfig.php';
		/* Sort IIM's on the basis of rank  */
		$IIMWithData = sortArrayByArray($IIMWithData, $IIM_rank);
		$IIMWithOutData = sortArrayByArray($IIMWithOutData, $IIM_rank);
		
		$returnData['IIMWithData'] = $IIMWithData;
		$returnData['IIMWithOutData'] = $IIMWithOutData;
		
		return $returnData;
	}
	function getScorePredictorForColleges($eligibilityList,$data,$year,$pageNo,$limit) {
		$eligibleCollegesList = array();
		if(!empty($eligibilityList) && !empty($data['category'])) {
			$defaultInstitutesDataList = $this->getDefaultSortedData($eligibilityList,$data['category'],$year,$pageNo,$limit);

			$defaultInstitutesData = $defaultInstitutesDataList['defaultInstitutesData'];
			$eligibilityListRemain = $defaultInstitutesDataList['remainingIds'];
			$eligibilitydefaultIdsCount = $defaultInstitutesDataList['eligibilitydefaultIds'];
			if(empty($eligibilitydefaultIdsCount)){
				$eligibilitydefaultIdsCount = 0;
			}	
			if(!empty($defaultInstitutesData)){
				$defaultInstitutesCount = count($defaultInstitutesData);
				$limit = $limit - $defaultInstitutesCount;	
			}
			if(!empty($eligibilityListRemain) && $limit > 0) {
				$eligibleInstitutesData = $this->getSortedInstitutes($eligibilityListRemain,$data['category'],$year,$pageNo,$limit,$eligibilitydefaultIdsCount);
			}
			else{
				$eligibleInstitutesData = array();
			}
			
			

			if(!empty($defaultInstitutesData) && count($defaultInstitutesData) > 0){
				$eligibleInstitutesData = $defaultInstitutesData + $eligibleInstitutesData;
			}


			foreach ($eligibleInstitutesData as $insKey => $insValue) {
				$eligibleCollegesList[$insValue['instituteId']] = $insValue;
				if(!empty($data['cat_percentile']) && $data['cat_percentile'] >= $insValue['Total_Percentile']){
					$eligibleCollegesList[$insValue['instituteId']]['chances'] = "High";
				}
				else {
					$eligibleCollegesList[$insValue['instituteId']]['chances'] = "Low";	
				}
			}
		}
		return $eligibleCollegesList;
	}

	function getInEligibityCollegesInfo($inEligibilityList,$data,$year,$pageNo,$limit){
		if($pageNo <=0)
			return;

		$inEligilityListValues = array();
		foreach ($inEligibilityList as $instituteKey => $inEligivalue) {
			foreach ($inEligivalue as $subKey => $subValue) {
				if(isset($data[$subKey]) && $data[$subKey] < $subValue){
					$inEligilityListValues[$instituteKey][$subKey] = $subValue;
				}
				if($subKey == "average_".strtolower($data['xiithStream']) && $data['X_XII_avg'] < $subValue){
					$inEligilityListValues[$instituteKey]['X_XII_avg'] = $subValue;
					unset($inEligilityListValues[$instituteKey]['xthPercentage']);
					unset($inEligilityListValues[$instituteKey]['xiithPercentage']);
				}
			}
		}

		$instituteIds = array_keys($inEligilityListValues);

		$offset = ($pageNo -1 ) * $limit;

		$instituteIds = array_slice($instituteIds, $offset,$limit);

		if(!empty($instituteIds)){
			$instituteDataMapping = $this->iimpredictormodel->getInstitutesMappingData($instituteIds);	
		}

		$dbData = array();

		foreach ($instituteIds as $inskey => $insvalue) {
			if(isset($inEligilityListValues[$insvalue])){
				$dbData[$insvalue] = $inEligilityListValues[$insvalue];
			}
			if(isset($instituteDataMapping[$insvalue])){
				$dbData[$insvalue]['instituteId'] = $instituteDataMapping[$insvalue]['instituteId'];
				$dbData[$insvalue]['courseId'] = $instituteDataMapping[$insvalue]['courseId'];
				$dbData[$insvalue]['articleId'] = $instituteDataMapping[$insvalue]['articleId'];
				$dbData[$insvalue]['articleLabel'] = $instituteDataMapping[$insvalue]['articleLabel'];
			}
		}
		return $this->getInfoForTuple($dbData);
	}

	function getInfoForTuple($dbData){
		if (empty($dbData)) {
			return ;
		}
		$mbaCacheLib = $this->CI->load->library('IIMPredictor/cache/MBAPredictorCache');

		$nonCacheData = array();
		foreach ($dbData as $key => $value) {
			$cachedjsonData = $mbaCacheLib->getInstituteTupleInfo($key);
			//$cachedjsonData = null;
			if(!empty($cachedjsonData)) {
				$cachedData = json_decode($cachedjsonData,true);
				$dbData[$key]["instituteName"] = $cachedData['instituteName'];
				$dbData[$key]["instituteUrl"] = $cachedData['instituteUrl'];
				$dbData[$key]["courseExtraInfo"] = $cachedData['courseExtraInfo'];
				$dbData[$key]["reviewData"] = $cachedData['reviewData'];
				$dbData[$key]["allReviewsUrl"] = $cachedData['allReviewsUrl'];
				$dbData[$key]["articleUrl"] = $cachedData['articleUrl'];
			}
			else{
				$nonCacheData[$key] = $value;
			}
		}
		if(empty($nonCacheData))
			return $dbData;

		$instituteIds = array_keys($nonCacheData);

		if(empty($instituteIds))
			return $dbData;

		//$instituteMappingData = $this->iimpredictormodel->getInstitutesMappingData($instituteIds);

		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        $instituteObjs = $instituteRepo->findMultiple($instituteIds);

		$courseIds = array();

		$articleIds = array();
		$articleInstituteMapping = array();
		$courseInstituteMapping = array();

		foreach ($nonCacheData as $key => $value) {
			if(!empty($value['articleId']) && is_numeric($value['articleId'])){
				$articleInstituteMapping[$value['articleId']] = $value['instituteId'];
				$articleIds[] = $value['articleId'];
			}
			if(!empty($value['courseId'])){
				$courseIds[] = $value['courseId'];
				$courseInstituteMapping[$value['courseId']] = $value['instituteId'];
			}		
		}

		if(!empty($articleIds)){
			$this->CI->load->builder('ArticleBuilder','article');
			$articleBuilder = new ArticleBuilder;
			$articleRepository = $articleBuilder->getArticleRepository();
			$articlesObj = $articleRepository->findMultiple($articleIds);
			foreach ($articlesObj as $articleKey => $articleValue) {
				if(empty($articleValue))
					continue;
				$blogId = $articleValue->getId();
				$blogUrl = $articleValue->getUrl();
				if(!empty($blogId) && !empty($blogUrl)){
					$articleUrls[$blogId] = addingDomainNameToUrl(array('url' => $blogUrl,'domainName' => SHIKSHA_HOME));
				}
			}
		}
		if(!empty($courseIds)){
			$coursesData = $this->getCoursesData($courseIds,$courseInstituteMapping);
			$courseReviewRating = $this->getListingsRating($courseIds,'course');
		}
		foreach ($coursesData as $instituteKey => $courseValue) {

			if(!empty($nonCacheData[$instituteKey])){
				$nonCacheData[$instituteKey]['courseName'] = $courseValue['courseName'];
				if(isset($instituteObjs[$instituteKey])){
					$singleInstituteObj = $instituteObjs[$instituteKey];
					$singleInstituteId = $singleInstituteObj->getId();
					$singleInstituteUrl = $singleInstituteObj->getUrl();
					$singleInstituteName = $singleInstituteObj->getName();
					if(!empty($singleInstituteId) && !empty($singleInstituteUrl)){
						$nonCacheData[$instituteKey]['instituteUrl'] = $singleInstituteUrl;
						$nonCacheData[$instituteKey]['instituteName'] = $singleInstituteName;
					}
					else{
						$nonCacheData[$instituteKey]['instituteUrl'] = null;
					}
				}
				$nonCacheData[$instituteKey]['courseExtraInfo'] = $courseValue['courseExtraInfo'];
				if(!empty($nonCacheData[$instituteKey]['courseExtraInfo']['extraInfo']['duration'])){
					 /*$splitString = split("-", $nonCacheData[$instituteKey]['courseExtraInfo']['extraInfo']['duration']);*/
					 $nonCacheData[$instituteKey]['courseExtraInfo']['extraInfo']['duration'] = $nonCacheData[$instituteKey]['courseExtraInfo']['extraInfo']['duration'];
				}

				if(!empty($courseReviewRating[$courseValue['courseId']])) {
					$nonCacheData[$instituteKey]['reviewData'] = $courseReviewRating[$courseValue['courseId']];
					if(!empty($nonCacheData[$instituteKey]['instituteUrl'])){
						$nonCacheData[$instituteKey]['allReviewsUrl'] = $nonCacheData[$instituteKey]['instituteUrl'].'/reviews?course='.$courseValue['courseId'];	
					}
					
				}
			}
		}
		foreach ($articleInstituteMapping as $key => $value) {
			if(!empty($articleUrls[$key])){
				$nonCacheData[$value]['articleUrl'] = $articleUrls[$key];
			}
		}
		foreach ($nonCacheData as $key => $value) {
			$data = array();

			if(array_key_exists($key, $coursesData) && !empty($value["instituteName"]) && !empty($value["instituteUrl"])) {
				$dbData[$key]["instituteName"] = $data["instituteName"] = $value["instituteName"];
				$dbData[$key]["instituteUrl"] = $data["instituteUrl"] = $value["instituteUrl"];
				$dbData[$key]["courseExtraInfo"] = $data["courseExtraInfo"] = $value["courseExtraInfo"];
				$dbData[$key]["reviewData"] = $data["reviewData"] = $value["reviewData"];
				$dbData[$key]["allReviewsUrl"] = $data["allReviewsUrl"] = $value["allReviewsUrl"];
				$dbData[$key]["articleUrl"] = $data["articleUrl"] = $value["articleUrl"];
				if($dbData[$key]["articleUrl"] == null){
					unset($dbData[$key]["articleUrl"]);
				}
				$mbaCacheLib->storeInstituteTupleInfo($key,json_encode($data));	
			}
			else{
				unset($dbData[$key]);
			}
		}
		return $dbData;	
	}

	public function getSortedInstitutes($institutes,$category,$year,$pageNo,$limit,$eligibilitydefaultIds,$isDefaultIdsCall = false){

		if($pageNo <=0 || empty($institutes)) {
			return;
		}

		$offset = ($pageNo -1 ) * $limit;

		if(empty($eligibilitydefaultIds)){
			$eligibilitydefaultIds = 0;
		}
		if(!$isDefaultIdsCall) {
			if($offset - $eligibilitydefaultIds < 0) {
				$offset = 0;
			}
			else{
				$offset = $offset - $eligibilitydefaultIds;
			}	
		}
		else{
			$offset = 0;
		}
		
		$dbData = $this->iimpredictormodel->getSortedInstitutes($institutes,$category,$year,$offset,$limit);

		return $this->getInfoForTuple($dbData);
	}

	function getCoursesData($courseIds,$courseInstituteMapping){
		if(empty($courseIds))
			return;

		$this->CI->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $courseRepo = $courseBuilder->getCourseRepository();   
        $courseObjects = $courseRepo->findMultiple($courseIds,array('basic','location'));

        $courseInfo = array();

        $this->CI->load->helper('listingCommon/listingcommon');

        foreach ($courseObjects as $courseKey => $courseValue) {
        	if(empty($courseValue))
        		continue;
        	$courseId = $courseValue->getId();
        	$url = $courseValue->getURL();
        	$instituteId = $courseInstituteMapping[$courseId];
			
        	if(empty($courseId) || empty($url) || empty($instituteId))
        		continue;
        	$courseExtraInfo = getExtraInfo($courseValue);
        	

        	$mainLocation = $courseValue->getMainLocation();
        	
        	if(!empty($mainLocation)){
        		$mainLocationId = $mainLocation->getLocationId();
        		$fees = $courseValue->getFees($mainLocationId);
        	}

        	if($fees && $fees->getFeesValue()) {
        		$feesValue = $fees->getFeesValue();
        		$feesValue = getRupeesDisplableAmount($feesValue, 2);
    			$courseExtraInfo['fees'] = $feesValue;
    			$courseExtraInfo['feesUnit'] = $fees->getFeesUnitName();
        	}

        	$courseInfo[$instituteId] = array('instituteId' => $instituteId,'courseName' => $courseValue->getName(),'courseId' => $courseId,'courseExtraInfo' => $courseExtraInfo);
        }
        return $courseInfo;
	}
	function getListingsRating($listingIds,$lsitingType = 'course'){
		$collegeReviewLib = $this->CI->load->library('CollegeReviewForm/CollegeReviewLib');
		$aggregateReviews = $collegeReviewLib->getAggregateReviewsForListing($listingIds,$lsitingType);
		return $aggregateReviews;
	}

	function getDefaultSortedData($instituteIds = array(),$category,$year,$pageNo,$limit){
		$defaultInstituteIds = $this->CI->config->item('default_institute_ids');
		$result = array(); 

		if($pageNo <= 0){
			return;
		}
		$offset = ($pageNo -1 ) * $limit;
		$defaultInstitutesInter = array_intersect($instituteIds,$defaultInstituteIds);

		if(!empty($defaultInstitutesInter)){
			$defaultInstituteIdsSlice = array_slice($defaultInstitutesInter, $offset,$limit);	
		}
		if(!empty($defaultInstituteIdsSlice)){
			$defaultInstitutesData = $this->getSortedInstitutes($defaultInstituteIdsSlice,$category,$year,$pageNo,$limit,0,true);
		}

		foreach ($defaultInstituteIdsSlice as $key => $defValue) {
			if(isset($defaultInstitutesData[$defValue])){
				$result['defaultInstitutesData'][$defValue] = $defaultInstitutesData[$defValue];
			}
		}
		if(!empty($defaultInstituteIds)){
			$result['remainingIds'] = array_diff($instituteIds,$defaultInstituteIds);
			$result['eligibilitydefaultIds'] = count($defaultInstitutesInter);
		}
		else{
			$result['remainingIds'] = $instituteIds;	
		}
		return $result;
	}

	
}


