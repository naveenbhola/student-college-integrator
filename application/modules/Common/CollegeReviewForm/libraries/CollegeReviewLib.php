<?php
class CollegeReviewLib
{

function __construct()
{
		$this->CI = & get_instance();
		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();

	    // get institute repository with all dependencies loaded
	    $this->instituteRepo = $instituteBuilder->getInstituteRepository();
		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepo = $courseBuilder->getCourseRepository();

}

private function _init(){
		$this->CI->load->model('CollegeReviewForm/collegereviewmodel');
		$this->crmodel = new CollegeReviewModel();

		$this->CI->load->builder('CollegeReviewForm/CollegeReviewsBuilder');
		$this->collegeReviewsBuilder = new CollegeReviewsBuilder;
}

public function formatCollegeReviewData($result){
    $returnArr = array();
	$count = 0;
	foreach($result as $key=>$value){
		if(is_integer($key)){
				foreach($value as $k=>$v){
						$returnArr[$count]['status'] = $v['profileStatus'];
						$returnArr[$count]['userId'] = $v['userId'];
				}
				$count++;
		}

	}
	return $returnArr;
}

public function mergeData($data1, $data2){
        foreach($data2 as $key=>$value){
                $yearOfGraduation = $value['yearOfGraduation'];
		if(!empty($value)){
				if($value['isShikshaInstitute']=='YES' && $value['courseId']>0){
						$res = $this->courseRepo->find($value['courseId'], array('location'));
						 if(is_object($res) && $res->getId()>0){
						$instituteName = $res->getInstituteName();
						$courseName = $res->getName();
						$allLocations = $res->getLocations();
						$locName = '';$cityName='';$stateName='';
                        $countryName = 'India';
                                            if(is_object($allLocations[$value['locationId']])){
                                                        $locName = $allLocations[$value['locationId']]->getLocalityName();                                                                      $cityName = $allLocations[$value['locationId']]->getCityName();
                                                        $stateName = $allLocations[$value['locationId']]->getStateName();
                                                        $countryName = 'India';
                                                }
						$data2[$key]['courseInformation'] = $instituteName.(($locName)?', '.$locName:"").(($cityName)?' ,'.$cityName:"").(($stateName)? ', '.$stateName:"").', '.$countryName.', '.$courseName.', Graduation Year:'.$yearOfGraduation;
						}
				}else{
					$data2[$key]['courseInformation'] = $value['instituteId'].', '.$value['locationId'].', '.$value['courseId'].', Graduation Year:'.$yearOfGraduation;
				}
		}else{
				$data2[$key]= '';
		}


        }

	foreach($data1 as $key=>$value){
		if(is_integer($key)){
				$data1[$key]['collegeReviewData'] = $data2[$key];
		}

	}
        return $data1;
}

	function encodeReviewFormEditURL($email, $id, $reviewId)
	{
		return base64_encode('email~'.base64_encode($email).'_reviewerId~'.base64_encode($id).'_reviewId~'.base64_encode($reviewId));
	}

	function decodeReviewFormEditURL($str)
	{
		$parameters = array();
		$tempStr = base64_decode($str);
		$arr = explode('_', $tempStr);
		foreach($arr as $param)
		{
			$val = explode('~', $param);
			$parameters[$val[0]] = base64_decode($val[1]);
		}
		return $parameters;
	}

	function formatTileData($data){
		$result = array();
		$i = 0;$j=0;
		foreach($data as $key=>$value){
				if($value['tilePlacement']=='top'){
						$result[$value['tilePlacement']][$i] = $value;
						$i++;
				}else{
						$result[$value['tilePlacement']][$j] = $value;
						$j++;
				}

		}
		return $result;
	}

	function formatReviewData($data){
		$result = array();
		$i=0;
		foreach($data['result'] as $key=>$value){
				$res 			         = $this->courseRepo->find($value['courseId'],array('location'));
				$resinst			 = $this->instituteRepo->find($value['instituteId']);

				$course_id = $res->getId();
				$inst_id = $resinst->getId();

				if(!empty($course_id) && !empty($inst_id)){
						$result[$i]['reviewId']           = $value['id'];
						$result[$i]['postedDate']           = $value['postedDate'];
						$result[$i]['ratings']           = $value['ratings'];
						$result[$i]['recommendations']   = $value['recommendations'];
						$result[$i]['anonymousFlag']   = $value['anonymousFlag'];
						$result[$i]['reviewDescription'] = $value['reviewDescription'];
						$result[$i]['placementDescription'] = $value['placementDescription'];
						$result[$i]['infraDescription'] = $value['infraDescription'];
						$result[$i]['facultyDescription'] = $value['facultyDescription'];
						$result[$i]['totalReviews']      = $value['totalReviews'];
						$result[$i]['moneyRating']       = $value['moneyRating'];
						$result[$i]['crowdCampusRating']           = $value['crowdCampusRating'];
						$result[$i]['avgSalaryPlacementRating']    = $value['avgSalaryPlacementRating'];
						$result[$i]['campusFacilitiesRating']      = $value['campusFacilitiesRating'];
						$result[$i]['facultyRating']           = $value['facultyRating'];
						$result[$i]['recommendCollegeFlag']    = $value['recommendCollegeFlag'];
						$result[$i]['yearOfGraduation']    = $value['yearOfGraduation'];
						$result[$i]['reviewAvgRating']	  =($value['moneyRating']+$value['crowdCampusRating']+$value['avgSalaryPlacementRating']+$value['facultyRating']+$value['campusFacilitiesRating'])/5;
						$result[$i]['courseId']        = $value['courseId'];
						$result[$i]['courseName']        = $res->getName();
						$result[$i]['courseUrl']        = $res->getUrl();
						$result[$i]['instituteName']     = $res->getInstituteName();
						$result[$i]['instituteUrl']        = $resinst->getUrl();
						$result[$i]['abbreviation']     = $resinst->getAbbreviation();

						$allLocations                    = $res->getLocations();
						if($value['locationId']>0 && is_object($allLocations[$value['locationId']])){
								$result[$i]['locationName']      = $allLocations[$value['locationId']]->getLocalityName();
								$result[$i]['cityName']          = $allLocations[$value['locationId']]->getCityName();
								$result[$i]['stateName']         = $allLocations[$value['locationId']]->getStateName();
								$result[$i]['countryName']       = 'India';
						}
						$i++;
				}

		}
		return $result;
	}

	function formatReviewDataForSlider($data, $courseIdsFromDb, $begin, $end, $type='ajax',$page='intermediate'){

		$finalResult = array();
		foreach($data['result'] as $key=>$value){
			if((!empty($value['courseId'])) && (!empty($value['instituteId']))) {
				$result = $this->getReviewFormattedData($value);
				if($page == 'compare' || $page == 'search')
					$finalResult[$value['courseId']][] = $result;
				else
					$finalResult[$value['courseId']] = $result;
			}
		}

		if($page == 'homepage'){
			return $finalResult;
		}


		$courseIdsFromDb = explode(',', $courseIdsFromDb);
		$res = array();
		//$courseIdsFromDb = array_reverse($courseIdsFromDb);
		if($type == 'ajax')
		{
				for($i=$begin, $j=0; $i<($begin+$end); $i++, $j++)
				{
					if(is_array($finalResult[$courseIdsFromDb[$j]]) && !empty($finalResult[$courseIdsFromDb[$j]]))
					{
						$res[$courseIdsFromDb[$j]] = $finalResult[$courseIdsFromDb[$j]];
					}
				}
		}
		else{
				for($i=$begin; $i<($begin+$end); $i++)
				{
					if(is_array($finalResult[$courseIdsFromDb[$i]]) && !empty($finalResult[$courseIdsFromDb[$i]]))
					{
						$res[$courseIdsFromDb[$i]] = $finalResult[$courseIdsFromDb[$i]];
					}
				}
		}

		return $res;
	}

	function getReviewFormattedData($data) {
		$this->_init();
		$result = array();
		$courseData = $this->courseRepo->find($data['courseId'],array('location'));
		$instituteData = $this->instituteRepo->find($data['instituteId']);

		$course_id = $courseData->getId();
		$institute_id = $instituteData->getId();

		$count = 5;

		if(!empty($course_id) && !empty($institute_id)){
			$result['reviewId'] = $data['id'];
			$result['postedDate'] = $data['postedDate'];
			$result['ratings'] = $data['ratings'];
			$result['recommendations'] = $data['recommendations'];
			$result['anonymousFlag'] = $data['anonymousFlag'];
			$result['reviewDescription'] = $data['reviewDescription'];
			$result['placementDescription'] = $data['placementDescription'];
			$result['infraDescription'] = $data['infraDescription'];
			$result['facultyDescription'] = $data['facultyDescription'];
			$result['review_seo_url'] = $data['review_seo_url'];
			$result['review_seo_title'] = $data['review_seo_title'];
			$result['totalReviews'] = $data['revCount'];
			$result['moneyRating'] = $data['moneyRating'];
			$result['crowdCampusRating'] = $data['crowdCampusRating'];
			$result['avgSalaryPlacementRating'] = $data['avgSalaryPlacementRating'];
			$result['campusFacilitiesRating'] = $data['campusFacilitiesRating'];
			$result['facultyRating'] = $data['facultyRating'];
			$result['recommendCollegeFlag'] = $data['recommendCollegeFlag'];
			$result['yearOfGraduation'] = $data['yearOfGraduation'];
			//$result['reviewAvgRating'] =($data['moneyRating']+$data['crowdCampusRating']+$data['avgSalaryPlacementRating']+$data['facultyRating']+$data['campusFacilitiesRating'])/5;
			$result['reviewAvgRating'] = $data['averageRating'];
			$result['courseId'] = $data['courseId'];
			$result['courseName'] = $courseData->getName();
			$result['courseUrl'] = $courseData->getUrl();
			$result['instituteName'] = $courseData->getInstituteName();
			$result['instituteUrl'] = $instituteData->getUrl();
			$result['abbreviation'] = $instituteData->getAbbreviation();
			$result['ratingValue'] = $data['ratingValue'];
			$result['ratingParamCount'] = $count;			//to populate total on view

			$allLocations = $courseData->getLocations();
			if($data['locationId']>0 && is_object($allLocations[$data['locationId']])){
				$result['locationName'] = $allLocations[$data['locationId']]->getLocalityName();
				$result['cityName'] = $allLocations[$data['locationId']]->getCityName();
				$result['stateName'] = $allLocations[$data['locationId']]->getStateName();
				$result['countryName'] = 'India';
			}
		}
		return $result;
	}

	function getDataForSeoUrl($data){
		$result  = array();
		foreach($data as $key=>$value){
				foreach($value as $k=>$v){
						$result = $v;
				}
		}
		return $result;
	}

	function checkReviewCount($reviewCount){
		$count = 2500;
		if($reviewCount<2500){
				return $count;
		}else{
				return ceil($reviewCount/100)*100;
		}
	}

	function checkReviewInstituteCount($instituteCount){
                $count = 250;
                if($instituteCount<250){
                                return $count;
                }else{
                                return ceil($instituteCount/50)*50;
                }
	}

	// FOr Mobile Implementation
	function formatReviewDataForSliderMobile($data, $courseIdsFromDb, $begin, $end, $type='ajax',$page='intermediate'){
		$finalResult = array();
		$delim = md5("%^&*shiksha%^&*").',';
		foreach($data['result'] as $key=>$value){
				$result = array();

				if((!empty($value['courseId'])) && (!empty($value['instituteId']))) {

						$result = $this->getReviewFormattedData($value);

						$nextDescArray = explode($delim,$value['SecondReviewDetail']);
						$nextPlacArray = explode($delim,$value['SecondPlacementDetail']);
						$nextInfraArray = explode($delim,$value['SecondInfraDetail']);
						$nextFacArray = explode($delim,$value['SecondFacultyDetail']);
						$nextUserAnonymousArray = explode(",",$value['SecondUserAnonymousFlagDetail']);


						$nextUserecommendCollegeFlagArray = explode(",",$value['SecondUserRecommendCollegeFlag']);
						$nextUserYearOfGraduationArray = explode(",",$value['SecondUserYearOfGraduation']);

						$nextUserMoneyRatingArray = explode(",",$value['SecondUserMoneyRating']);
						$nextUserAvgSalaryPlacementRatingArray = explode(",",$value['SecondUserAvgSalaryPlacementRating']);
						$nextUserCrowdCampusRating = explode(",",$value['SecondUserCrowdCampusRating']);
						$nextUserAverageRating = explode(",",$value['SecondUserAverageRating']);
						$nextUserCampusFacilitiesRatingArray = explode(",",$value['SecondUserCampusFacilitiesRating']);

						$nextUserFacultyRatingArray = explode(",",$value['SecondUserFacultyRating']);
						$nextUserPostedDateArray = explode(",",$value['SecondUserPostedDate']);
						$nextUserUserIdArray = explode(",",$value['SecondUserUserId']);
						$nextReviewIdArray = explode(",",$value['SecondReviewId']);
						$nextReviewSeoUrlArray = explode($delim,$value['SecondReviewSeoUrl']);
						$nextReviewSeoTitleArray = explode($delim,$value['SecondReviewSeoTitle']);


						$result['nextTileDescription'] = $nextDescArray[1];
						$result['nextTilePlacement'] = $nextPlacArray[1];
						$result['nextTileInfra'] = $nextInfraArray[1];
						$result['nextTileFaculty'] = $nextFacArray[1];
						$result['nextAnonymousFlag'] = $nextUserAnonymousArray[1];
						$result['nextRecommendFlag'] = $nextUserecommendCollegeFlagArray[1];
						$result['nextRating'] = ($nextUserCrowdCampusRating[1] + $nextUserFacultyRatingArray[1] + $nextUserMoneyRatingArray[1] + $nextUserAvgSalaryPlacementRatingArray[1] + $nextUserCampusFacilitiesRatingArray[1])/5;
						if($result['nextRating'] == 0){
							$result['nextRating'] = $nextUserAverageRating[1];
						}
						$result['nextYearOfGraduation'] = $nextUserYearOfGraduationArray[1];
						$result['nextPostedDate'] = $nextUserPostedDateArray[1];
						$result['nextUserId'] = $nextUserUserIdArray[1];
						$result['nextReviewId'] = $nextReviewIdArray[1];
						$result['nextReviewSeoUrl'] = $nextReviewSeoUrlArray[1];
						$result['nextReviewSeoTitle'] = $nextReviewSeoTitleArray[1];


						$finalResult[$value['courseId']] = $result;

				}

		}

		if($page == "homepage"){
			return $finalResult;
		}

		$courseIdsFromDb = explode(',', $courseIdsFromDb);
		$res = array();
		//$courseIdsFromDb = array_reverse($courseIdsFromDb);
		if($type == 'ajax')
		{
				for($i=$begin, $j=0; $i<($begin+$end); $i++, $j++)
				{
					if(is_array($finalResult[$courseIdsFromDb[$j]]) && !empty($finalResult[$courseIdsFromDb[$j]]))
					{
						$res[$courseIdsFromDb[$j]] = $finalResult[$courseIdsFromDb[$j]];
					}
				}
		}
		else{
				for($i=$begin; $i<($begin+$end); $i++)
				{
					if(is_array($finalResult[$courseIdsFromDb[$i]]) && !empty($finalResult[$courseIdsFromDb[$i]]))
					{
						$res[$courseIdsFromDb[$i]] = $finalResult[$courseIdsFromDb[$i]];
					}
				}
		}
		return $res;
	}

	/*
	* Function filters the courseIds in correspondence with review count and pack type
	*
	* @params : $reviewsDat => array($courseIds, $revCount, $pack_type)
	* 			$countCriteria4PaidCourse => min count for paid course
	*			$countCriteria4FreeCourse => min count for free course
	*
	* @returns : string of courses seperated by comma's
	*/
	 function getCollegeReviewsByCriteria($reviewsData,$countCriteria4PaidCourse = 3,$countCriteria4FreeCourse = 1){

		foreach($reviewsData as $key=>$value )
		{
			if($value['packType'] == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $value['packType'] == SILVER_LISTINGS_BASE_PRODUCT_ID || $value['packType'] == GOLD_ML_LISTINGS_BASE_PRODUCT_ID){

				// case for paid course
				if($value['RevCount'] >= $countCriteria4PaidCourse)
				{
					$subQueryCriteria .= $value['courseId'].',';
				}
			}
			else{
				// case for free course
				if($value['RevCount'] >= $countCriteria4FreeCourse)
				{
					$subQueryCriteria .= $value['courseId'].',';
				}
			}

		}
		if($subQueryCriteria != "")
		{
			$subQueryCriteria = substr($subQueryCriteria,0,-1);
		}

		return $subQueryCriteria;
	 }

	/*
     * Get latest and popular college Reviews
     * @params:  $courseIds => array of course Ids, $start => for pagination data,
     *           $count => count of pagination data, $orderOfReview => sql clause containing couseIds
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     *  @returns => Returns review latest reviews, review count and reviewer details
     */

	function getLatestAndPopularReviews($courseIds, $orderOfReview, $start, $count,$checkForCriteria, $categoryId){
		$this->_init();

		$subQueryCriteria = "";
		if($checkForCriteria){
			// gets data having courseIds, pack_type and revCount
			$subQueryData = $this->getSubQueryCriteria();


			// filters the courseIds in correspondence with review count and pack type.
			$subQueryCriteria = $this->getCollegeReviewsByCriteria($subQueryData);
		}

		// Get Reviews according to the Selection (i.e. latest/Popular)
		if($orderOfReview=='latest'){
			$data = $this->getLatestCollegeReviews($courseIds, $start, $count,$subQueryCriteria, $categoryId);
		}else{
			$data = $this->getTopRatedCollegeReviews($courseIds, $start, $count,$subQueryCriteria, $categoryId);
		}
		return $data;
	}

	/*
     * function to get latest College Reviews, generally all published reviews from mainTable order by modificationDate desc
     * @params:  $courseIds => array of course Ids, $start => for pagination data,
     *           $count => count of pagination data, $subQueryCriteria => comma seperated course ids
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     *  @returns => Returns review latest reviews, review count and reviewer details
    */

	function getLatestCollegeReviews($courseIds, $start, $count,$subQueryCriteria, $categoryId){
		$this->_init();
		$criteria = "";
		if($courseIds!=''){
            $criteria = $courseIds;
        }else if($subQueryCriteria != ''){
			$criteria = $subQueryCriteria;
		}

		$reviewsPageRepo = $this->collegeReviewsBuilder->getReviewsPageRepository();
		$data = $reviewsPageRepo->getLatestCollegeReviews($start, $count,$criteria);
		return $data;

	}

	/*
     * function to get Popular College Reviews
     * @params:  $courseIds => array of course Ids, $start => for pagination data,
     *           $count => count of pagination data, $subQueryCriteria => comma seperated courseIds[flows criteria]
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     *  @returns => Returns review popular reviews, review count and reviewer details
    */

	function getTopRatedCollegeReviews($courseIds, $start, $count,$subQueryCriteria, $categoryId){
		$this->_init();
		$criteria = "";
		if($courseIds!=''){
            $criteria = $courseIds;
        }else if($subQueryCriteria != ''){
			$criteria = $subQueryCriteria;
		}

		$reviewsPageRepo = $this->collegeReviewsBuilder->getReviewsPageRepository();
		$data = $reviewsPageRepo->getPopularCollegeReviews($courseIds, $start, $count,$criteria);
		return $data;
	}

	/* function to get total Institute count
     * @returns => total Institute count
     */

	function getSubQueryCriteria(){
		$this->_init();
		$reviewsPageRepo = $this->collegeReviewsBuilder->getReviewsPageRepository();
		$data = $reviewsPageRepo->getSubQueryCriteria();
		return $data;
	}

	/* function to get all Reviews
     * @params: $categoryId => Reviews category (BTECH/MBA)
     *
     * @returns => total Institute count
     */

	function getTotalReviews($stream,$baseCourse,$substream,$educationType){
		$this->_init();

		$reviewsPageRepo = $this->collegeReviewsBuilder->getReviewsPageRepository();
		$data = $reviewsPageRepo->getTotalReviews($stream,$baseCourse,$substream,$educationType);
		return $data;
	}


    /* function to get total Institute count
     *@params: $categoryId => Reviews category (BTECH/MBA)
     *
     *@returns => total Institute count
    */

	function getReviewInstituteCount($stream){
		$this->_init();
		global $managementStreamMR;
		global $engineeringtStreamMR;
		if($stream == ''){
			$stream = $managementStreamMR;
		}
		$reviewsPageRepo = $this->collegeReviewsBuilder->getReviewsPageRepository();
		$data = $reviewsPageRepo->getReviewInstituteCount($stream);
		return $data;
	}

	/*
    * clear cache for college reviews page
    */

	function clearCacheForCollegeReviews(){
		$this->_init();
		$reviewsPageRepo = $this->collegeReviewsBuilder->getReviewsPageRepository();
		$data = $reviewsPageRepo->clearCacheForCollegeReviews();
		return $data;
	}

	/*
     * Get College reviews for Slider
     * @params:  $courseIds => array of course Ids, $start => for pagination data,
     *           $count => count of pagination data, $subQueryCriteria => sql clause containing couseIds
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     *  @returns => Returns review latest reviews, review count and reviewer details
     */

	function getLatestReviewsForSlider($courseIds, $start, $count, $stream ,$page='intermediate',$courseSubQueryForHomepage='',$baseCourse,$educationType,$substream){
		$this->_init();
		$reviewsPageRepo = $this->collegeReviewsBuilder->getReviewsPageRepository();
		$data = $reviewsPageRepo->getLatestReviewsForSlider($courseIds, $start, $count, $stream,$page,$courseSubQueryForHomepage,$baseCourse,$educationType,$substream);
		return $data;
	}

	/*
     * Get College reviews for Slider
     * @params:  $courseIds => array of course Ids, $start => for pagination data,
     *           $count => count of pagination data, $subQueryCriteria => sql clause containing couseIds
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     *  @returns => Returns review latest reviews, review count and reviewer details
     */

	function getPopularReviewsForSlider($courseIds='', $start, $count, $stream,$page='intermediate',$courseSubQueryForHomepage='',$courseRankMapping=array(),$baseCourse,$educationType,$substream){
		$this->_init();
		$reviewsPageRepo = $this->collegeReviewsBuilder->getReviewsPageRepository();
		$data = $reviewsPageRepo->getPopularReviewsForSlider($courseIds, $start, $count, $stream,$page,$courseSubQueryForHomepage,$courseRankMapping,$baseCourse,$educationType,$substream);
		return $data;
	}
	/*
     * Get College reviews for Slider Mobile
     * @params:  $courseIds => array of course Ids, $start => for pagination data,
     *           $count => count of pagination data, $subQueryCriteria => sql clause containing couseIds
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     *  @returns => Returns review latest reviews, review count and reviewer details
     */

	function getLatestReviewsForSliderMobile($courseIds, $start, $count, $stream, $baseCourse,$educationType,$substream, $page='intermediate',$courseSubQueryForHomepage=''){
		$this->_init();
		$reviewsPageRepo = $this->collegeReviewsBuilder->getReviewsPageRepository();
		$data = $reviewsPageRepo->getLatestReviewsForSliderMobile($courseIds, $start, $count, $stream, $page,$courseSubQueryForHomepage, $baseCourse,$educationType,$substream);
		return $data;
	}

		/*
     * Get College reviews for Slider
     * @params:  $courseIds => array of course Ids, $start => for pagination data,
     *           $count => count of pagination data, $subQueryCriteria => sql clause containing couseIds
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     *  @returns => Returns review latest reviews, review count and reviewer details
     */

	function getPopularReviewsForSliderMobile($courseIds='', $start, $count, $stream,$page='intermediate',$courseSubQueryForHomepage='',$courseRankMapping=array(),$baseCourse,$educationType,$substream){
		$this->_init();
		$reviewsPageRepo = $this->collegeReviewsBuilder->getReviewsPageRepository();
		//error_log("==shiksha== cat id 2 ==>".$categoryId);
		$data = $reviewsPageRepo->getPopularReviewsForSliderMobile($courseIds, $start, $count, $stream,$page,$courseSubQueryForHomepage,$courseRankMapping,$baseCourse,$educationType,$substream);
		return $data;
	}

	// Validatation of college review URL
	public function validateReviewURL($reviewURL) {
		$userInputURL = getCurrentPageURL();
		$userInputURL  = trim($userInputURL);
		$userInputURL  = trim($userInputURL,"/");
		$queryString = substr(strrchr($_SERVER['REQUEST_URI'], "?"), 0);

		if(!empty($reviewURL) && $reviewURL != $userInputURL) {
			redirect($reviewURL.$queryString, 'location', 301);
		}

	}

	public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
    	return $randomString;
	}

	/*
     * Create data in CSV format
     * @params:  array in the format array(array(1,2,3,4), array(1,2,3,4), array(1,2,3,4))
     *
     *  @returns => return data in the CSV format
     */
	function create_csv_string($data) {

	  if (!$fp = fopen('php://temp', 'w+')) return FALSE;

	  foreach ($data as $line) fputcsv($fp, $line);

	  rewind($fp);

	  return stream_get_contents($fp);
	}

	/*
     * send mail with attached CSV file
     * @params:  $csvData => array of having data to be sent in the CSV,
     *           $body => Message Body, $to => Send to
     *           $subject => Mail Subject, $from => mail from
     *
     *  @returns => bool (true => if mail is sent, false => mail sent is failed)
     */
	function send_csv_mail($csvData ,$body, $to, $subject, $from) {

		$fp = fopen('/tmp/file.csv', 'w');
		foreach ($csvData as $row) {

		    fputcsv($fp, $row);
		}
		fclose($fp);

		$fileatt_type = "application/zip, application/octet-stream"; // File Type
    	$fileatt_name = "file.csv";

    	$email_message = $body;
    	$headers = "From: ".$from."\r\n"."Cc: ".$cc;
    	$semi_rand = md5(time());

    	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
    	$headers .= "\nMIME-Version: 1.0\n" .
    			"Content-Type: multipart/mixed;\n" .
    			" boundary=\"{$mime_boundary}\"";

    	$email_message .= "This is a multi-part message in MIME format.\n\n" .
    			"--{$mime_boundary}\n" .
    			"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
    			"Content-Transfer-Encoding: 7bit\n\n" .
    			$email_message .= "\n\n";

    	$data = chunk_split(base64_encode(file_get_contents('/tmp/file.csv')));
    	$email_message .= "--{$mime_boundary}\n" .
    	"Content-Type: {$fileatt_type};\n" .
    	" name=\"{$fileatt_name}\"\n" .
    	"Content-Transfer-Encoding: base64\n\n" .
    	$data .= "\n\n" .
    	"--{$mime_boundary}--\n";

    	$response = mail($to,$subject, $email_message, $headers);
    	unlink('/tmp/file.csv');
    	return $response;
	}

	/*
     * seperate review id from the input data
     * @params:  $reviewData{array} => contain reveiew related info and id as a index having review id
     *
     *  @returns => {array}: having review ids
     */
	function getReviewIdsFromSourceData($reviewData){
		$returnData = array();
		foreach ($reviewData as $key => $value) {
			$returnData['reviewIds'][] = $value['id'];
			$returnData['reviewIPMapping'][$value['id']] = $value['clientIP'];
			$returnData['emailReviewMapping'][$value['id']] = $value['email'];
		}

		return $returnData;
	}

	/*
     * provides institue and course name for a mapped review
     * @params:  $reviewIds{array} => contains the review Id
     *
     *  @returns => {array}: having reviewid, instituteName, courseName
     */
	function getCRDataFromMappedInstitute($reviewIds){
		if(empty($reviewIds)){
			return array();
		}

		$this->CI->load->model('CollegeReviewForm/collegereviewmodel');
		$this->crmodel = new CollegeReviewModel();

		$crInstituteData = $this->crmodel->getCRDataFromMappedInstitute($reviewIds);

		/*Seperates out course id from crInstituteData, also create a review to CoureId mapping variable */
		$courseIds = array();
		$reviewCoureIdmapping = array();
		foreach ($crInstituteData as $key => $value) {
			$courseIds[] = $value['courseId'];
			$reviewCoureIdmapping[$value['reviewid']] = $value['courseId'];
		}

		/*Getting course name and institute name from course object */
		$courseData = array();
		$courseObj = $this->courseRepo->findMultiple($courseIds,array('basic_info'));
		foreach ($courseObj as $coureId => $object) {
			$courseData[$coureId]['courseName'] = $object->getName();
			$courseData[$coureId]['InstituteName'] = $object->getInstituteName();
		}

		/*making data in the required form */
		$returnData = array();
		foreach($reviewCoureIdmapping as $reviewId=>$courseId){
			$returnData[$reviewId] = $courseData[$courseId];
		}

		return $returnData;
	}


	/*
     * provides institue and course name for a non mapped college review
     * @params:  $reviewIds{array} => contains the review Id
     *
     *  @returns => {array}: having reviewid, instituteName, courseName
     */
	function getCRDataFromNonMappedInstitute($reviewIds){
		if(empty($reviewIds)){
			return array();
		}

		$this->CI->load->model('CollegeReviewForm/collegereviewmodel');
		$this->crmodel = new CollegeReviewModel();

		$crInstituteData = $this->crmodel->getCRDataFromNonMappedInstitute($reviewIds);

		$returnData = array();
		foreach($crInstituteData as $key=>$value){
			$returnData[ $value['reviewid']]['courseName'] =  $value['courseName'];
			$returnData[$value['reviewid']]['InstituteName'] =  $value['instituteName'];
		}

		return $returnData;
	}

	/*Code to check multiple review from same Ip on a perticular course
	 *	@Params: $formedData{array} => Variable having review id and Ip address
	 *			 $reviewInstituteData{array} => Variable having course name and institute name
	 *  @return: return review ids for a perticular course uploaded from same IP
	 */
	function processCRSourceData($formedData, $reviewInstituteData){
		$courseIpHolder = array();
		foreach($formedData['reviewIds'] as $key=>$reviewId){
			if(empty($courseIpHolder[$reviewInstituteData[$reviewId]['courseName'].'-'.$reviewInstituteData[$reviewId]['InstituteName']][$formedData['reviewIPMapping'][$reviewId]])){
				$courseIpHolder[$reviewInstituteData[$reviewId]['courseName'].'-'.$reviewInstituteData[$reviewId]['InstituteName']][$formedData['reviewIPMapping'][$reviewId]][] = $reviewId;
			}else{
				$courseIpHolder[$reviewInstituteData[$reviewId]['courseName'].'-'.$reviewInstituteData[$reviewId]['InstituteName']][$formedData['reviewIPMapping'][$reviewId]][] = $reviewId;
				$courseIpHolder['reviewsFromSameIP'][] = $courseIpHolder[$reviewInstituteData[$reviewId]['courseName'].'-'.$reviewInstituteData[$reviewId]['InstituteName']][$formedData['reviewIPMapping'][$reviewId]];
			}
		}

		return $courseIpHolder['reviewsFromSameIP'];
	}

	/*
	 * API to make mail content for college review IP tracking.
	 *
	 */
	function IpTrackingMailContentMaker($reviewData, $instituteDetails, $reviewsFromSameIPOnCourse){
		$reviewIds = $reviewData['reviewIds'];
		$mailContent = 'Hi, <br/><br/>';
		if(empty($reviewsFromSameIPOnCourse)){
			$mailContent .= 'No reviews are submitted from same IP address.';
		}else{
			$sno = 0;
			$mailContent .= 'Following are the details of college reviews submitted from the same IP,<br/><br/>';
			$mailContent .= '<table border="1" style="width:70%"> <tr> <td>S.No</td> <td>Review Id</td><td>Email</td> <td>College Name</td> <td>Institute Name</td> <td>IP Address</td> </tr>';
			foreach($reviewsFromSameIPOnCourse as $key=>$reviewCollection){
				foreach ($reviewCollection as $k => $reviewId) {
					$sno++;
					$mailContent .= '<tr><td>'.$sno.'</td>';
					$mailContent .= '<td>'.$reviewId.'</td>';
					$mailContent .= '<td>'.$reviewData['emailReviewMapping'][$reviewId].'</td>';
					$mailContent .= '<td>'.$instituteDetails[$reviewId]['courseName'].'</td>';
					$mailContent .= '<td>'.$instituteDetails[$reviewId]['InstituteName'].'</td>';
					$mailContent .= '<td>'.$reviewData['reviewIPMapping'][$reviewId].'</td></tr>';
				}
			}
			$mailContent .= '</table><p>Please review them in CMS dashboard.</p>';
		}

		$mailContent .= '<br/>Regards,<br/>Shiksha College Reviews Team';
		return $mailContent;
	}

	function getRatingValues($reviewId){
		$this->_init();
		$reviewDetail = $this->crmodel->getRatingReviewId($reviewId);
		$reviewDetail = $this->formatRatingDetail($reviewDetail);
		return $reviewDetail;
	}


	function formatRatingDetail($reviewArray){
   		foreach ($reviewArray as $value) {
   			$temp[$value['ratingId']] = $value['rating'];
   		}
   		return $temp;

   }

   /**
    *	Api to update courseId, instituteId, locationId
    *	@param : array[oldCourseId] = [newCourseId]
    */
   function updateCourseIdForReview($courses = array()){
		$this->_init();
		$insertData['operation'] = 'index';
        $insertData['listing_type'] = 'collegereview';

   		if(count($courses) > 0){
   			$courseData = array();
   			$newCourseOldCourseMapping = array();
   			foreach ($courses as $oldCourse => $newCourse) {
   				$newCourseOldCourseMapping[$newCourse] = $oldCourse;
   			}
			$courseObjArr = $this->courseRepo->findMultiple(array_values($courses));
			$CRTrackingData = array();
			$reviewIds = array();
			foreach ($courseObjArr as $key => $courseObj) {
				$CollegeReviewsToIndex = array();
				$CollegeReviewsToIndex = $this->crmodel->getAllReviewIdsForCourse(array($newCourseOldCourseMapping[$key]));

				$courseData =	array(
									'oldCourseId' => $newCourseOldCourseMapping[$key],
									'newCourseId' => $key,
									'instituteId' => $courseObj->getInstituteId(),
									'locationId' => $courseObj->getMainLocation()->getLocationId()
							);
				$this->crmodel->updateCourseIdForReview($courseData);
				foreach ($CollegeReviewsToIndex as $key => $value) {
					$reviewIds[] = $value['reviewId'];

        			$CRTrackingData[] = array(
						"reviewId" => $value['reviewId'],
	 	                "addedBy" => 11,
	 	                "action" => 'courseDetailsUpdated',
	 	                "data" => json_encode(array("courseId" => $courseData['newCourseId'], "instituteId"=> $courseData['instituteId'], "locationId"=> $courseData['locationId']))
					);
				}

				if(count($CRTrackingData) >0){
					$caModel = $this->CI->load->model('CAEnterprise/reviewenterprisemodel');
					$caModel->trackCollegeReview($CRTrackingData,true);
				}
			}
			//add reviews to index log 
			$reviewIds = array_unique($reviewIds);
			$this->crmodel->insertBulkReviewForIndex($reviewIds);
   		}
   }

   /**
    *   Api to update courseId, instituteId, locationId
    *   @param : courseId, instituteId, locationId
    */
   function updateInstituteIdAndLocationForReview($courseId, $instituteId, $locationId){
                $this->_init();
                $insertData['operation'] = 'index';
                $insertData['listing_type'] = 'collegereview';

                $CollegeReviewsToIndex = array();
                $CollegeReviewsToIndex = $this->crmodel->getAllReviewIdsForCourse(array($courseId));

                $courseData =   array(
                                                        'oldCourseId' => $courseId,
                                                        'newCourseId' => $courseId,
                                                        'instituteId' => $instituteId,
                                                        'locationId' => $locationId
                                        );

                $this->crmodel->updateCourseIdForReview($courseData);
                foreach ($CollegeReviewsToIndex as $key => $value) {
                        $insertData['listing_id'] = $value['reviewId'];
                        $this->crmodel->insertReviewForIndex($insertData);

                        $CRTrackingData[] = array(
                                    "reviewId" => $value['reviewId'],
                                    "addedBy" => 11,
                                    "action" => 'courseDetailsUpdated',
                                    "data" => json_encode(array("courseId" => $courseId, "instituteId"=> $instituteId, "locationId"=> $locationId))
                                );
                }

                if(count($CRTrackingData) >0){
                        $caModel = $this->CI->load->model('CAEnterprise/reviewenterprisemodel');
                        $caModel->trackCollegeReview($CRTrackingData,true);
                }
   }

   function deleteCourseIdForReview($courseIds = array()){
   		$insertData['operation'] = 'delete';
        $insertData['listing_type'] = 'collegereview';

   		$this->_init();
   		if(count($courseIds)>0){
   			$trackingData = array();
   			$result = $this->crmodel->getAllReviewIdsForCourse($courseIds);
   			if(count($result)){
   				$reviewIds = array();
   				foreach ($result as $key => $value) {
   					$trackingData[] = array(
   						"reviewId" => $value['reviewId'],
	 	                "addedBy" => 11,
	 	                "action" => 'statusUpdated',
	 	                "data" => json_encode(array("status"=>"deleted"))
   						);
   					$reviewIds[] = $value['reviewId'];
   				}
				$reviewIds = array_unique($reviewIds);

   				$data = array('status' => 'deleted');
   				$this->crmodel->updateReviewsStatus($reviewIds, $data);

   				//add reviews to index log 
	        	$this->crmodel->insertBulkReviewForIndex($reviewIds);
   				if(count($trackingData) >0){
   					$caModel = $this->CI->load->model('CAEnterprise/reviewenterprisemodel');
 	        		$caModel->trackCollegeReview($trackingData,true);
   				}

   			}
   		}
   }

   /**
    *	Api to update Institute Id
    *	@param : array[oldInstituteId] = [newInstituteId]
    *	Note: Only institute id will be updated. Location will not be updated.
    */
   function updateInstituteIdForReview($oldInstituteIds,$newInstitute){
		$this->_init();

   		$this->crmodel->updateMultipleInstituteIdForReview($oldInstituteIds,$newInstitute);
   	}

   function getCourseReviewsData($courseIds,$stream,$baseCourse){
   		$this->_init();
	    $collegereviewmodel = $this->CI->load->model('CollegeReviewForm/collegereviewmodel');

   		if(count($courseIds) > 0) {
	        $reviewData = $collegereviewmodel->getReviewRatingsByCourse($courseIds);
   		}

   		$reviewData['ratingCount'] = $collegereviewmodel->getRatingParamCount($stream,$baseCourse);

   		return $reviewData;
   }

   public function prapareBeaconData($pageIdentifier,& $displayData,$stream,$substream,$baseCourse,$educationType){
		$displayData['beaconTrackData'] = array(
			"pageIdentifier" 	=> empty($pageIdentifier)? $pageIdentifier: "collegeReviewPage",
			"pageEntityId"		=>	0,
			'extraData'			=> array(
					'hierarchy' => array(
						'streamId' => empty($stream)?0:$stream,
						'substreamId'	=> empty($substreamId)?0:$substreamId,
						'specializationId' => 0
						),
					'baseCourseId' => $baseCourse,
					'countryId' => 2,
					'educationType' => $educationType
				)
			);
	}

   	public function getAllCRIds($collegeReviewType){
   		$this->CRModel 	= $this->CI->load->model('CollegeReviewForm/collegereviewmodel');
   		$result = $this->CRModel->getAllCRIds($collegeReviewType);
   		//_p($result);die;
   		$crIds = array();
   		foreach ($result as $key => $value) {
   			$crIds[] = $value['id'];
   		}
   		return $crIds;
   	}

   	public function indexCollegeReviews($crIds = array(), $collegeReviewType){
   		// validate cr ids   		
   		if(is_array($crIds) && count($crIds) <=0){
   			return false;
   		}

   		//_p($crIds);die;
   		$this->CRModel 	= $this->CI->load->model('CollegeReviewForm/collegereviewmodel');

		// get last moderator by for college review
   		$result =  $this->CRModel->getLastModeratedBy($crIds);
   		$reviewToModeratorMapping = array();
   		foreach ($result as $key => $value) {
   			$reviewToModeratorMapping[$value['reviewId']] = array(
										'moderatorId' => $value['userId'],
										'moderateDate' => str_replace(' ', 'T', $value['moderationTime']).'Z',
										'moderatorEmail' => $value['moderatorEmail']
   										);
   		}
   		unset($result);

   		$crDetails = $this->CRModel->getCRDetails($crIds, $collegeReviewType);
   		   		
   		if(empty($crDetails) || count($crDetails) <= 0){
   			return;
   		}

   		// get rating params
   		$ratingparams = array();
   		$this->_getRatingParams($crIds , $crDetails);

   		return $this->_indexCRData($crDetails, $reviewToModeratorMapping);

   	}

   	private function _getRatingParams($crIds, &$crDetails){
   		$this->CRModel 	= $this->CI->load->model('CollegeReviewForm/collegereviewmodel');
   		$result =  $this->CRModel->getRatingParams($crIds);
   		$mappedRatingParams = array();
   		foreach ($result as $key => $value) {
   			$mappedRatingParams[$value['reviewId']][$value['ratingId']] = $value['rating'];
   		}

   		$oldToNewRatingMapping = array(
					'moneyRating'				=>	1,
					'crowdCampusRating' 		=>	2,
					'avgSalaryPlacementRating'	=>	3,
					'campusFacilitiesRating'	=>	4,
					'facultyRating'				=>	5
					);

   		foreach ($crDetails as $key => $crDetail) {
   			if($mappedRatingParams[$crDetail['reviewId']]){
   				$crDetails[$key]['mappedRatingParams'] = json_encode($mappedRatingParams[$crDetail['reviewId']]);
   			}else{
   				$mappedRating = array();
		$mappedRating[$oldToNewRatingMapping['moneyRating']] = $collegeReview['moneyRating'];
   				$mappedRating[$oldToNewRatingMapping['crowdCampusRating']] = $collegeReview['crowdCampusRating'];
   				$mappedRating[$oldToNewRatingMapping['avgSalaryPlacementRating']] = $collegeReview['avgSalaryPlacementRating'];
   				$mappedRating[$oldToNewRatingMapping['campusFacilitiesRating']] = $collegeReview['campusFacilitiesRating'];
   				$mappedRating[$oldToNewRatingMapping['facultyRating']] = $collegeReview['facultyRating'];
   				$crDetails[$key]['mappedRatingParams'] = json_encode($mappedRating);
   			}
   		}
   	}

   	private function _indexCRData(&$crDetails, &$reviewToModeratorMapping){
   		$solrServerLib = $this->CI->load->library('indexer/SolrServerLib');
   		$this->CI->load->config('CollegeReviewForm/collegeReviewConfig');
   		$reviewMasterMappingToName = $this->CI->config->item('crMasterMappingToName');
   		$crIndexingData = array();
   		$index = 0;
   		$listingPaidStatus = array(
   			GOLD_SL_LISTINGS_BASE_PRODUCT_ID =>1,
   			SILVER_LISTINGS_BASE_PRODUCT_ID => 1,
   			GOLD_ML_LISTINGS_BASE_PRODUCT_ID => 1
   			);
   		foreach ($crDetails as $key => $collegeReview) {

   			$mappedRatingParams = json_decode($collegeReview['mappedRatingParams'], true);
   			$reviewMapping = array();
   			foreach ($mappedRatingParams as $reviewMasterId => $value) {
   				$reviewMapping[$reviewMasterMappingToName[$reviewMasterId]] = $value;
   			}

   			//_p($collegeReview);die;
   			$crIndexingData[$index] = array(
					'id'                       =>	$collegeReview['reviewId'],
					'reviewId'                 =>	$collegeReview['reviewId'],
					'reviewQuality'            =>	$collegeReview['reviewQuality'],
					'creationDate'             =>	str_replace(' ', 'T', $collegeReview['creationDate']).'Z',
					'modificationDate'         =>	str_replace(' ', 'T', $collegeReview['modificationDate']).'Z',
					'averageRating'            =>	$collegeReview['averageRating'],
					'facultyRating'            =>	$reviewMapping['facultyRating'],
					'campusFacilitiesRating'   =>	$reviewMapping['campusFacilitiesRating'],
					'avgSalaryPlacementRating' =>	$reviewMapping['avgSalaryPlacementRating'],
					'crowdCampusRating'        =>	$reviewMapping['crowdCampusRating'],
					'moneyRating'              =>	$reviewMapping['moneyRating'],
					'reviewStatus'             =>	$collegeReview['status'],
					'isAnonymous'              =>	($collegeReview['anonymousFlag'] == "YES")?'true':'false',
					'helpfulCount'             =>	$collegeReview['helpfulFlagCount'],
					'notHelpfulCount'          =>	$collegeReview['notHelpfulFlagCount'],
					'isInstituteMapped'        =>	($collegeReview['isShikshaInstitute'] == "YES")?'true':'false',
					'incentiveFlag'            =>	empty($collegeReview['incentiveFlag']) ? 0 :1,
					'recommendCollegeFlag'     =>	($collegeReview['recommendCollegeFlag'] == "YES")?'true':'false',
					'userId'                   =>	$collegeReview['userId'],
					'reviewerId'               =>  $collegeReview['reviewerId']
   				);
   			$crIndexingData[$index]['ratingParams']		=$collegeReview['mappedRatingParams'];

			// reviewIdRightDigits
			$crIndexingData[$index]['reviewIdRightDigits'] = $collegeReview['reviewId'] % 100;
			if($crIndexingData[$index]['reviewIdRightDigits'] < 10){
				$crIndexingData[$index]['reviewIdRightDigits'] = '0'.$crIndexingData[$index]['reviewIdRightDigits'];
			}

			if($collegeReview['qualityScore']>=0){
				$crIndexingData[$index]['qualityScore'] = $collegeReview['qualityScore'];
			}

			if(!empty($collegeReview['firstname'])){
				$crIndexingData[$index]['firstname'] = $collegeReview['firstname'];
			}

			if(!empty($collegeReview['lastname'])){
				$crIndexingData[$index]['lastname'] = $collegeReview['lastname'];
			}

			if(!empty($collegeReview['email'])){
				$crIndexingData[$index]['email'] = $collegeReview['email'];
			}

			if(!empty($collegeReview['mobile'])){
				$crIndexingData[$index]['mobile'] = $collegeReview['mobile'];
			}

			if(!empty($collegeReview['isdCode'])){
				$crIndexingData[$index]['isdCode'] = $collegeReview['isdCode'];
			}

			if(!empty($collegeReview['placementDescription'])){
				$crIndexingData[$index]['reviewContent']['placementDescription'] = urlencode($collegeReview['placementDescription']);
			}

			if(!empty($collegeReview['infraDescription'])){
				$crIndexingData[$index]['reviewContent']['infraDescription'] = urlencode($collegeReview['infraDescription']);
			}

			if(!empty($collegeReview['reviewDescription'])){
				$crIndexingData[$index]['reviewContent']['reviewDescription'] = urlencode($collegeReview['reviewDescription']);
			}

			if(!empty($collegeReview['facultyDescription'])){
				$crIndexingData[$index]['reviewContent']['facultyDescription'] = urlencode($collegeReview['facultyDescription']);
			}

			if(!empty($collegeReview['reviewTitle'])){
				$crIndexingData[$index]['reviewContent']['reviewTitle'] = urlencode($collegeReview['reviewTitle']);
			}

			if(!empty($crIndexingData[$index]['reviewContent'])){
				$crIndexingData[$index]['reviewContent'] = json_encode($crIndexingData[$index]['reviewContent']);
			}

			if(!empty($collegeReview['reviewSource'])){
				$crIndexingData[$index]['reviewSource'] = $collegeReview['reviewSource'];
				$utmParams = $this->_getUTMParams($collegeReview['reviewSource']);
				foreach ($utmParams as $name => $value) {
					$crIndexingData[$index][$name] = $value;
				}
			}

			if(!empty($collegeReview['reason'])){
				$crIndexingData[$index]['moderationReason'] = $collegeReview['reason'];
			}

			if(!empty($collegeReview['facebookURL'])){
				$crIndexingData[$index]['socialProfile']['facebookURL'] = $collegeReview['facebookURL'];
			}

			if(!empty($collegeReview['linkedInURL'])){
				$crIndexingData[$index]['socialProfile']['linkedInURL'] = $collegeReview['linkedInURL'];
			}

			if(!empty($crIndexingData[$index]['socialProfile'])){
				$crIndexingData[$index]['socialProfile'] = json_encode($crIndexingData[$index]['socialProfile']);
			}

			if(!empty($collegeReview['mappedReviewId'])){
				$crIndexingData[$index]['yearOfGraduation'] = $collegeReview['yearOfGraduation'];
				$crIndexingData[$index]['reviewPackType'] = $listingPaidStatus[$collegeReview['pack_type']] ? 1 :0;

				if(!empty($collegeReview['base_course'])){
					$crIndexingData[$index]['baseCourse'] = $collegeReview['base_course'];
				}

				if(!empty($collegeReview['stream_id'])){
					$crIndexingData[$index]['streamId'] = $collegeReview['stream_id'];
				}

				if(!empty($collegeReview['courseId'])){
					$crIndexingData[$index]['courseId'] = $collegeReview['courseId'];
				}

				if(!empty($collegeReview['instituteId'])){
					$crIndexingData[$index]['instituteId'] = $collegeReview['instituteId'];
				}

				if(!empty($collegeReview['locationId'])){
					$crIndexingData[$index]['locationId'] = $collegeReview['locationId'];
				}
			}else{
				$crIndexingData[$index]['yearOfGraduation'] = $collegeReview['yearOfGraduation1'];
				if(!empty($collegeReview['courseName'])){
					$crIndexingData[$index]['courseName'] = $collegeReview['courseName'];
				}

				if(!empty($collegeReview['instituteName'])){
					$crIndexingData[$index]['instituteName'] = $collegeReview['instituteName'];
				}

				if(!empty($collegeReview['locationName'])){
					$crIndexingData[$index]['locationName'] = $collegeReview['locationName'];
				}
			}

			if(!empty($reviewToModeratorMapping[$collegeReview['reviewId']])){
				$crIndexingData[$index]['lastModeratedBy'] = $reviewToModeratorMapping[$collegeReview['reviewId']]['moderatorId'];
				$crIndexingData[$index]['lastModerateDate'] = $reviewToModeratorMapping[$collegeReview['reviewId']]['moderateDate'];
				$crIndexingData[$index]['lastModeratorEmail'] = $reviewToModeratorMapping[$collegeReview['reviewId']]['moderatorEmail'];
			}

			$index ++;

			unset($crDetails[$key]);
			$collegeReview = array();
   		}
   		//_p($crIndexingData);die;
   		$response = $solrServerLib->indexFinalData($crIndexingData, false,'collegereview');
   		return $response;
   		//_p($response);die;
   	}

   	private function _getUTMParams($string){
   		$resultSet = array();
   		if(!empty($string)){
   			$utmParams = explode("&", $string);
   			foreach ($utmParams as $utmParam) {
	    		$utmParam = explode("=", $utmParam);
	    		$utmName = $utmParam[0];
	    		unset($utmParam[0]);
	    		$utmValue = implode("", $utmParam);

	    		if($utmName == "utm_source"){
	    			$resultSet['utmSource'] = $utmValue;
	    		}else if($utmName == "utm_campaign"){
	    			$resultSet['utmCampaign'] = $utmValue;
	    		}else if($utmName == "utm_medium"){
	    			$resultSet['utmMedium'] = $utmValue;
	    		}
	    	}
   		}
   		return $resultSet;
   	}

   	public function filterReviewsForCourse($reviewIds, $courseId){
   		$this->CRModel 	= $this->CI->load->model('CollegeReviewForm/collegereviewmodel');
   		$result = $this->CRModel->filterReviewsForCourse($reviewIds, $courseId);
   		$filteredReviewIds = array();
   		foreach ($result as $key => $value) {
   			$filteredReviewIds[] = $value['reviewId'];
   		}
   		return $filteredReviewIds;
   	}

   	function getAggregateReviewsForListing($listingId, $listingType='institute') {
			if(empty($listingId)){
				return array();
			}

   		if(!is_array($listingId)) {
   			$listingIds = array($listingId);
   		}
   		else {
   			$listingIds = $listingId;
   		}

   		$this->CI->benchmark->mark('aggregate_rating_from_cache_start');

   		$CollegeReviewCache = $this->CI->load->library('CollegeReviewForm/cache/CollegeReviewCache');
   		
   		$aggregateRating = $CollegeReviewCache->getAggregateReviewsForListingFromCache($listingIds, $listingType);
   		// _p($aggregateRating);die('aaa');

   		$this->CI->benchmark->mark('aggregate_rating_from_cache_end');

   		$returnData = array();
   		foreach ($aggregateRating as $id => $ratingData) {
   			if(!empty($ratingData)){
   				$returnData[$id]['totalCount'] = $ratingData['totalCount'];
   				foreach ($ratingData['aggregateRating'] as $ratingName => $row) {
   					$returnData[$id]['aggregateRating'][$ratingName] = $row['mean'];
   				}
   				$returnData[$id]['intervalRatingCount'] = $ratingData['intervalRatingCount'];
   			}
   		}

        return $returnData;
   	}

   	function getPlacementTopicTagsForReviews($listingId,$listingType,$selectedFilterRating){
   		$collegeReviewSolrClient = $this->CI->load->library('CollegeReviewForm/solr/CollegeReviewSolrClient');
   		$instituteDetaiLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');
   		$solrRequestData= array();
   		if($listingType =='course'){
   			$solrRequestData['courseId']=$listingId;
   		} else {
   			$returnData = $instituteDetaiLib->getAllCoursesForInstitutes(array($listingId));
   			$allInstituteIds = array_keys($returnData['instituteWiseCourses']);
   			$solrRequestData['instituteId'] = $allInstituteIds ;
   		}
   		if(!empty($selectedFilterRating)){
   			$solrRequestData['averageRating'] = $selectedFilterRating;
   		}
   		$placementTopicTagIds = $collegeReviewSolrClient->getPlacementTopicTagsForReviews($solrRequestData);
   		return $placementTopicTagIds ;
   	}

   	public function checkReviewType($reviewId){
   		// validate review id   		
   		if($reviewId <=0 || empty($reviewId)){
   			return false;
   		}

   		$this->CI->load->model('CollegeReviewForm/collegereviewmodel');
		$this->crmodel = new CollegeReviewModel();
		$reviewType = $this->crmodel->checkReviewType($reviewId);
		return $reviewType;
   	}
}
?>
