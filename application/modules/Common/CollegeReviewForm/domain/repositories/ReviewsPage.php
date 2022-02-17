<?php

class ReviewsPage extends EntityRepository{
	protected $dao;                           // data access object
    private $reviewsCache;
    protected $cache;
    
    public function __construct($dao, $cache, $lib)
    {
        parent::__construct();
        $this->CI = & get_instance();
        if(!empty($cache)) {
            $this->dao           = $dao;
            $this->reviewsCache  = $cache;
            $this->cache         = true;
            $this->lib           = $lib;
        }
    }

    /* 
     * function to get Popular College Reviews
     * @params:  $courseIds => array of course Ids, $start => for pagination data, 
     *           $count => count of pagination data, $subQueryCriteria => sql clause containing couseIds
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     * @returns => Returns review popular reviews, review count and reviewer details
    */

    public function getPopularCollegeReviews($courseIds, $start, $count,$subQueryCriteria, $categoryId = '23'){
    	$key = 'popularcollegereviews-'.$categoryId.'-'.$start.'-'.$count;    


    	if($this->cache) $resultSet = $this->reviewsCache->get($key);
    	
    	if($resultSet=='ERROR_READING_CACHE' || !$this->cache){
            // returns array($courseId, Rating, pested Date) according to pagination
    		$CourseReviewsRatings = $this->dao->getCourseReviewsRatings($courseIds, $start, $count,$subQueryCriteria, $categoryId);

    		$courseCSV = '';
			foreach($CourseReviewsRatings['queryResult'] as $row) {
			    $courseCSV .= ($courseCSV=='')?$row['courseId']:','.$row['courseId'];
			}

			$PopularCollegeReviews = $this->dao->getPopularCollegeReviews($courseCSV);
			$finalResult = array();
			$i = 0;
			foreach($PopularCollegeReviews as $row) {
			    foreach ($CourseReviewsRatings['queryResult'] as $ratingRow){ // needed ?
					if($ratingRow['courseId'] == $row['courseId']){ // needed ?
					    $row['ratings'] = $ratingRow['ratings'];
					    $row['postedDate'] = $ratingRow['postedDate'];
					}
			    }
			    $finalResult[$i] = $row;
			    $i++;
			}

			$resultSet = array();
			if($CourseReviewsRatings['totalRows'] > 0){
				$resultSet['result'] = $finalResult;
                //reviewer details can be from tuser [in case review data has userId]/CollegeReview_PersonalInformation [reviewerId]
				$resultSet['reviewerDetails'] = $this->dao->getReviewerDetails($finalResult);
				$resultSet['totalReviews'] = $CourseReviewsRatings['totalRows'];        
			}

            $this->storeClearCacheKeys($key, '21600');
			$this->reviewsCache->store($key,$resultSet, 21600);
    	}

    	return $resultSet;
    }

    /* function to get all Reviews according to category
     * @params: $categoryId => Reviews category (BTECH/MBA)
     *
     * @returns => total Institute count
     */

    public function getTotalReviews($stream,$baseCourse,$substream,$educationType){
        
        $key = "totalReviews-".$stream."-".$baseCourse;

        if($this->cache) $finalResult = $this->reviewsCache->get($key);
        
        if($finalResult=='ERROR_READING_CACHE' || !$this->cache){
    		$data = $this->dao->getTotalReviewsNew($stream,$baseCourse,$substream,$educationType);

    		$totalCount = '';
    		foreach($data as $k=>$value){
				$totalCount +=$value['totalCount'];
			}

            $this->storeClearCacheKeys($key, '21600');
			$this->reviewsCache->store($key,$totalCount, 21600);
    	} else {
            $totalCount = $finalResult;
        }
        
    	return $totalCount;
    }

    /* function to get total Institute count
     * @params: $categoryId => Reviews category (BTECH/MBA)
     *
     * @returns => total Institute count
    */

    function getReviewInstituteCount($stream){
    	$key = "instituteReviewCount-".$stream;

        global $managementStreamMR;
        global $engineeringtStreamMR;
        if($stream == ''){
            $stream = $managementStreamMR;
        }

    	if($this->cache) $count = $this->reviewsCache->get($key);

    	if($count=='ERROR_READING_CACHE' || !$this->cache){
    		$data = $this->dao->getReviewInstituteCountNew($stream);
    		$count = $data[0]['totalInstituteCount'];

            $this->storeClearCacheKeys($key, '21600');
    		$this->reviewsCache->store($key,$count, 21600);
    	}
    	return $count;
    }

    /* 
     * function to get latest College Reviews,  generally all published reviews from mainTable order by modificationDate desc
     * @params:  $courseIds => array of course Ids, $start => for pagination data, 
     *           $count => count of pagination data, $subQueryCriteria => sql clause containing couseIds
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     * @returns => Returns review latest reviews, review count and reviewer details
    */

    function getLatestCollegeReviews($start, $count,$criteria, $categoryId = '23',$flagToSkipCache=true){
        
        if($flagToSkipCache){
            $key = "getLatestCollegeReviews-".$categoryId."-".$start."-".$count;
        }
       
        if($this->cache) $resultSet = $this->reviewsCache->get($key);

        if(!$this->cache || $resultSet=='ERROR_READING_CACHE'){
            $resultSet = "";
            $data = $this->dao->getLatestReviews($start, $count,$criteria, $categoryId);
            
            $totalRows = $data['rowCount'][0]->totalRows;
            if($totalRows>0){
                    $resultSet['result'] = $data['reviewData'];
                    //reviewer details can be from tuser [in case review data has userId]/CollegeReview_PersonalInformation [reviewerId]
                    $resultSet['reviewerDetails'] = $this->dao->getReviewerDetails($data['reviewData']);
                    $resultSet['totalReviews'] = $totalRows;
            }

            if($flagToSkipCache){
                $this->storeClearCacheKeys($key, '1500');
                $this->reviewsCache->store($key,$resultSet, 1500);
            }
            
        }

        return $resultSet;
    }

    /* 
    * function to get total Institute count
    * @returns => total Institute count
    */

    function getSubQueryCriteria(){
        $key = "getSubQueryCriteria";
        if($this->cache) $data = $this->reviewsCache->get($key);
        if($data=='ERROR_READING_CACHE' || !$this->cache){
            $resultSet = "";
            $data = $this->dao->getSubQueryCriteria();

            $this->storeClearCacheKeys($key, '1500');
            $this->reviewsCache->store($key,$data, 1500);
        }
        return $data;
    }

    /*
    * clear cache for college reviews page
    */
    
    function clearCacheForCollegeReviews(){
        echo "==1500==</br>";
        $key = $this->getClearCacheKey('1500');
        foreach($key as $r=>$k){ echo $k."</br>";
            $this->reviewsCache->clearCacheForKey($k);
        } 

        echo "==21600==";
        $key = $this->getClearCacheKey('21600');
        foreach($key as $r=>$k){ echo $k."</br>";
            $this->reviewsCache->clearCacheForKey($k);
        }    
        $this->reviewsCache->clearCacheForKey('CollegeReviewsKey-21600');
        $this->reviewsCache->clearCacheForKey('CollegeReviewsKey-1500');
    }

    /*
     * Get College reviews for Slider
     * @params:  $courseIds => array of course Ids, $start => for pagination data, 
     *           $count => count of pagination data, $subQueryCriteria => sql clause containing couseIds
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     *  @returns => Returns review latest reviews, review count and reviewer details
     */

    function getLatestReviewsForSlider($courseIds, $start, $count, $stream, $page='intermediate', $courseSubQueryForHomepage = '',$baseCourse,$educationType,$substream){
       
        $key = "LatestReviewsForSlider-"."-".$page."-".$stream."-".$baseCourse."-".$start."-".$count;

        if($this->cache) {
            $resultSet = $this->reviewsCache->get($key);
        }
        
        if($resultSet=='ERROR_READING_CACHE' || !$this->cache){

            if($page == 'homepage')
                $courseSubQueryForHomepage = $this->getCoursesInfo($stream,'latest',$baseCourse,$educationType,$substream);
            
            $resultSet = array();
            $resultSet['totalReviewCount'] = $courseSubQueryForHomepage['totalReviewCount'];
            $data = $this->dao->getLatestReviewsForSliderNew($courseIds, $start, $count, $stream, $page, $courseSubQueryForHomepage['reviewsByCriteria'],$baseCourse,$educationType,$substream);
           
            if($data['totalRows']>0){
                $resultSet['result'] = $data['results'];
                $resultSet['reviewerDetails'] = $this->dao->getReviewerDetails($data['results']);
                $resultSet['totalCollegeCards'] = $data['totalRows'];
               
 //              if(empty($courseIds) && !empty($courseSubQueryForHomepage)){
                    $this->storeClearCacheKeys($key, '1500');
                    $this->reviewsCache->store($key,$resultSet, 172800);
                    //            }
               unset($data);
            }
        }

        return $resultSet;
    }


     /*
     * Get College reviews for Slider
     * @params:  $courseIds => array of course Ids, $start => for pagination data, 
     *           $count => count of pagination data, $subQueryCriteria => sql clause containing couseIds
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     *  @returns => Returns review latest reviews, review count and reviewer details
     */

    function getPopularReviewsForSlider($courseIds='', $start, $count, $stream, $page='intermediate', $courseSubQueryForHomepage = '',$courseRankMapping=array(),$baseCourse,$educationType,$substream){
        $key = "PopularReviewsForSlider-"."-".$page."-".$stream."-".$baseCourse."-".$start."-".$count;

        if($this->cache) $resultSet = $this->reviewsCache->get($key);
        if($resultSet=='ERROR_READING_CACHE' || !$this->cache){
            if($page == 'homepage')
                $courseSubQueryForHomepage = $this->getCoursesInfo($stream,'TopRated',$baseCourse,$educationType,$substream);
            $resultSet = array();
             $resultSet['totalReviewCount'] = $courseSubQueryForHomepage['totalReviewCount'];
                unset($totalCourseData['totalReviewCount']);
            $data = $this->dao->getPopularReviewsForSlider($courseIds, $start, $count, $page, $courseSubQueryForHomepage['reviewsByCriteria'], $courseRankMapping);
            if($data['totalRows']>0){
                $resultSet['result'] = $data['results'];
                $resultSet['reviewerDetails'] = $this->dao->getReviewerDetails($data['results']);
                $resultSet['totalCollegeCards'] = $data['totalRows'];
               
                $this->storeClearCacheKeys($key, '1500');
                $this->reviewsCache->store($key,$resultSet, 172800);
            }
        }
        return $resultSet;
    }

    /*
     * function to stores the cache key for CR pages
     */ 
    function storeClearCacheKeys($key, $time){
        $keyData = $this->getClearCacheKey($time);

        if(!$this->cache){
            return;
        }

        if(empty ($keyData) || !isset($keyData) || $keyData == 'ERROR_READING_CACHE'){
            $keyData = array($key);
        }else{
            $keyData[] = ($key);
        }

        if($time == '21600'){
            $key = 'CollegeReviewsKey-21600';
            $this->reviewsCache->store($key,$keyData, 21600);
        }else{
            $key = 'CollegeReviewsKey-1500';
            $this->reviewsCache->store($key,$keyData, 1500);
        }
    }

    /*
     * function to get the cache key for CR pages
     */ 
    function getClearCacheKey($time){ 
        if($time == '21600'){
            $key = 'CollegeReviewsKey-21600';
            $data = $this->reviewsCache->get($key);
        }else{
            $key = 'CollegeReviewsKey-1500';
            $data =  $this->reviewsCache->get($key);
        }

        return $data;
    }
    
    function getCoursesInfo($stream, $type = "latest", $baseCourse,$educationType,$substream, $courseIds = ''){
        $courseIds  = trim($courseIds);
        $courses_array = explode(",",$courseIds);
        $count_courses = count($courses_array);
        if($count_courses>0 && $courses_array[0] > 0){

            $totalCourseData = $this->dao->getCoursesInfo($stream,$courseIds, $baseCourse,$educationType,$substream);
            $subQuery['totalReviewCount'] = $totalCourseData['totalReviewCount'];
            unset($totalCourseData['totalReviewCount']);
            
            $subQuery['reviewsByCriteria'] = $this->lib->getCollegeReviewsByCriteria($totalCourseData); 

        }else{

            $key = "CoursesInfo-".$type."-".$stream;

            if($this->cache) $subQuery = $this->reviewsCache->get($key);

            if(!$this->cache || $subQuery=='ERROR_READING_CACHE' || empty($subQuery)){
                $totalCourseData = $this->dao->getCoursesInfo($stream,$courseIds, $baseCourse,$educationType,$substream);

                if(!empty($totalCourseData)){
                    $subQuery = array();
                    $subQuery['totalReviewCount'] = $totalCourseData['totalReviewCount'];
                    unset($totalCourseData['totalReviewCount']);
            
                    $subQuery['reviewsByCriteria'] = $this->lib->getCollegeReviewsByCriteria($totalCourseData); 

	                $this->storeClearCacheKeys($key, '1500');
                    $this->reviewsCache->store($key,$subQuery, 1500);
                }
            }
        }
        
        return $subQuery ;
    }

    /*
     * Get College reviews for Slider Mobile
     * @params:  $courseIds => array of course Ids, $start => for pagination data, 
     *           $count => count of pagination data, $subQueryCriteria => sql clause containing couseIds
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     *  @returns => Returns review latest reviews, review count and reviewer details
     */

    function getLatestReviewsForSliderMobile($courseIds, $start, $count, $stream, $page='intermediate', $courseSubQueryForHomepage = '', $baseCourse,$educationType,$substream){
        $key = "LatestReviewsForSliderMobile-".$page."-".$stream."-".$baseCourse."-".$start."-".$count;

        if($this->cache) {
            $resultSet = $this->reviewsCache->get($key);
        }
        if(!$this->cache || $resultSet == 'ERROR_READING_CACHE'){
            
            if($page == 'homepage'){
                $courseSubQueryForHomepage = $this->getCoursesInfo($stream,'latest',$baseCourse,$educationType,$substream);
            }
            $resultSet = array();
            $resultSet['totalReviewCount'] = $courseSubQueryForHomepage['totalReviewCount'];
            $data = $this->dao->getLatestReviewsForSliderNewMobile($courseIds, $start, $count, $stream, $baseCourse, $educationType, $substream, $page, $courseSubQueryForHomepage['reviewsByCriteria']);

            if($data['totalRows']>0){
                $resultSet['result'] = $data['results'];
                $resultSet['reviewerDetails'] = $this->dao->getReviewerDetailsMobile($data['results']);
                $resultSet['totalCollegeCards'] = $data['totalRows'];
               
               //if(empty($courseIds) && !empty($courseSubQueryForHomepage)){
                    $this->storeClearCacheKeys($key, '1500');
                    $this->reviewsCache->store($key,$resultSet, 172800);
               //}

            }
        }
        return $resultSet;
    }


     /*
     * Get College reviews for Slider
     * @params:  $courseIds => array of course Ids, $start => for pagination data, 
     *           $count => count of pagination data, $subQueryCriteria => sql clause containing couseIds
     *           $categoryId => Reviews category (BTECH/MBA)
     *
     *  @returns => Returns review latest reviews, review count and reviewer details
     */

    function getPopularReviewsForSliderMobile($courseIds='', $start, $count, $stream,$page,$courseSubQueryForHomepage,$courseRankMapping,$baseCourse,$educationType,$substream){
        $key = "PopularReviewsForSliderMobile-"."-".$page."-".$stream."-".$baseCourse."-".$start."-".$count;

        if($this->cache) $resultSet = $this->reviewsCache->get($key);
        if(!$this->cache || $resultSet=='ERROR_READING_CACHE'){
            if($page == 'homepage')
                $courseSubQueryForHomepage = $this->getCoursesInfo($stream,'TopRated',$baseCourse,$educationType,$substream);
            $resultSet = array();
            $resultSet['totalReviewCount'] = $totalCourseData['totalReviewCount'];
                unset($totalCourseData['totalReviewCount']);
            $data = $this->dao->getPopularReviewsForSliderMobileNew($courseIds, $start, $count, $page, $courseSubQueryForHomepage['reviewsByCriteria'], $courseRankMapping);
            if($data['totalRows']>0){
                $resultSet['result'] = $data['results'];
                $resultSet['reviewerDetails'] = $this->dao->getReviewerDetailsMobile($data['results']);
                $resultSet['totalCollegeCards'] = $data['totalRows'];
               
                $this->storeClearCacheKeys($key, '1500');
                $this->reviewsCache->store($key,$resultSet, 172800);
            }
        }
        return $resultSet;
    }


    function getCacheData($key){
        return $this->reviewsCache->get($key);
    }

    function setCacheData($key,$data,$timePeriod){
        $this->reviewsCache->store($key,$data, $timePeriod);
    }
}
