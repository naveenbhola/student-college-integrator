<?php
/**
 * ReviewRecommendationLib Library Class
 *
 *
 * @package     ContentRecommendation
 * @subpackage  Libraries
 *
 */

class ReviewRecommendationLib {

    private $_CI;
    private $reviewmodel;
    private $sortWeights;

    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->_CI->config->load('ContentRecommendation/ReviewRecommendation_config');
        $this->sortWeights = $this->_CI->config->item('sortWeights');
        
        $this->reviewmodel = $this->_CI->load->model('ContentRecommendation/reviewrecommendationmodel');
        $this->_CI->load->library('listingCommon/listingCommonLib');
        $this->_CI->load->library('nationalInstitute/InstituteDetailLib');
        $this->_CI->load->library('ContentRecommendation/ReviewSorter');
        $this->_CI->load->helper('ContentRecommendation/recommend');
        $this->cache = PredisLibrary::getInstance();
        $this->expireInSeconds = 86400;
        $this->countCacheExpiry = 86400;
    }

    /**
    * Function to get top reviews for a particular institute 
    * @param : $instituteId : Single institute Id (MANDATORY INTEGER)
    * @param : $exclusionList: Array of review id (Optional Array of INTEGER)
    * @param : $count : total no of reviews required (Optional INTEGER)
    * @param : $offset : Offset wrt to top reviews found (Optional INTEGER)
    * @param : $factor: 'RELEVANCY'(default)/'RECENCY'/'GRADUATION_YEAR'/'HIGHEST_RATING'/'LOWEST_RATING' 
    *             (Optional STRING) 
    *
    * @return : Array of 2 indices. 'topContent' is Sorted Array of review Ids corresponding to id in 
    *            CollegeReview_MainTable and 'numFound' is total content found 
    *
    * Usage  :  $this->reviewrecommendationlib->forInstitute(467, array(), 5);
    * Output : Array
    *     (
    *       [topContent] => Array
    *          (
    *              [0] => 3446974
    *              [1] => 3451373
    *              [2] => 3427948
    *              [3] => 3451890
    *              [4] => 3440073
    *          )
    *    
    *       [numFound] => 54
    *     )
    **/
    public function forInstitute($instituteId,$exclusionList=array(),$count=5,$offset=0,$factor = 'RELEVANCY', $selectedRatingFilter,$selectedTagId,$onlyPublished=true){
        if($count < 0 || $offset < 0){
            return array();
        }
        if(!is_numeric($instituteId) || $instituteId <= 0){
            return array();
        }
        $allInstitutesCourses=$this->_CI->institutedetaillib->getAllCoursesForInstitutes($instituteId);
        $filteredIds  = $allInstitutesCourses['courseIds'];
        if(empty($filteredIds)){
            return array();
        }
        //$filteredIds = $this->_CI->reviewrecommendationmodel->getListingsByListingsWithMinCourseReviews($allInstitutes,'institute','course');
      
        /*removing check for institute*/
        $response = $this->getRecommendedReviews($filteredIds,'course',$exclusionList,$count,$offset,$factor, 0, $selectedRatingFilter,$selectedTagId,$onlyPublished);
        return $response;
    }

    /**
    * Function to get top reviews for a particular university 
    * @param : $universityId : Single universityId (MANDATORY INTEGER)
    * @param : $exclusionList: Array of review id (Optional Array of INTEGER)
    * @param : $count : total no of reviews required (Optional INTEGER)
    * @param : $offset : Offset wrt to top reviews found (Optional INTEGER)
    * @param : $factor: 'RELEVANCY'(default)/'RECENCY'/'GRADUATION_YEAR'/'HIGHEST_RATING'/'LOWEST_RATING' 
    *
    * @return : Array of 2 indices. 'topContent' is Sorted Array of review Ids corresponding to id in 
    *            CollegeReview_MainTable and 'numFound' is total content found 
    *
    * Usage  :  $this->reviewrecommendationlib->forUniversity(467, array(), 5);
    * Output : Array
    *               (
    *                   [topContent] => Array
    *                       (
    *              [0] => 3446974
    *              [1] => 3451373
    *              [2] => 3427948
    *              [3] => 3451890
    *              [4] => 3440073
    *                       )
    *       
    *       [numFound] => 54
    *               )
    **/
    public function forUniversity($universityId,$exclusionList=array(),$count=5,$offset=0,$factor = 'RELEVANCY',$preFetchedCourseIds = array(), $selectedRatingFilter,$selectedTagId,$onlyPublished=true){
        if($count < 0 || $offset < 0){
            return array();
        }
        if(!is_numeric($universityId)){
            return array();
        }
        
        if(empty($preFetchedCourseIds)){
            $universityChildren = $this->_CI->institutedetaillib->getInstituteCourseIds($universityId,'university');
        }
        else{
            $universityChildren = $preFetchedCourseIds;
        }
        //$universityInstitutes = array_keys($universityChildren['type']);
        //$universityInstitutes[]=$universityId;
        //$filteredIds = $this->_CI->reviewrecommendationmodel->getListingsByListingsWithMinCourseReviews($universityInstitutes,'institute','course');
        
        $courseIds  = $universityChildren['courseIds'];
        if(empty($courseIds)){
            return array();
        }
        $response = $this->getRecommendedReviews($courseIds,'course',$exclusionList,$count,$offset,$factor, 0, $selectedRatingFilter,$selectedTagId,$onlyPublished);
        return $response;
    }

    /**
    * Function to get top reviews for a particular Course 
    * @param : $courseId : Single course Id (MANDATORY INTEGER)
    * @param : $exclusionList: Array of review Ids (Optional Array of INTEGER)
    * @param : $count : total no of reviews required (Optional INTEGER)
    * @param : $offset : Offset wrt to top reviews found (Optional INTEGER)
    * @param : $factor: 'RELEVANCY'(default)/'RECENCY'/'GRADUATION_YEAR'/'HIGHEST_RATING'/'LOWEST_RATING' 
    * 
    * @return : Array with 2 indices.'topContent' is Sorted Array of review Ids.
    *           'numFound' is total content found
    *
    * Usage  : $this->reviewrecommendationlib->forCourse(965, array(), 5);
    * Output : 
    *     Array
    *               (
    *                   [topContent] => Array
    *                       (
    *              [0] => 3446974
    *              [1] => 3451373
    *              [2] => 3427948
    *              [3] => 3451890
    *              [4] => 3440073
    *                       )
    *       
    *       [numFound] => 54
    *       )
    **/
    public function forCourse($courseId,$exclusionList=array(),$count=5,$offset=0,$factor = 'RELEVANCY', $courseObj, $selectedRatingFilter,$selectedTagId,$onlyPublished=true){
        if($count < 0 || $offset < 0){
            return array();
        }
        if(!is_array($courseId)){
            $filteredIds = array($courseId);
        }
        else{
            $filteredIds =  $courseId;  
        }

        
        $response = $this->getRecommendedReviews($filteredIds,'course',$exclusionList,$count,$offset,$factor, 0, $selectedRatingFilter,$selectedTagId,$onlyPublished);
        return $response;        
    }

    public function getRecommendedReviews($id,$type,$exclusionList=array(),$count=5,$offset=0,$factor = 'RELEVANCY', $prefetchedReviewCount = 0, $selectedRatingFilter = 0,$selectedTagId=0,$onlyPublished=true){
        
        if($factor!=null && $factor !='RELEVANCY'){
            $instituteReviews = $this->reviewmodel->getSortedListingReviewsByFactor($id,$type,$exclusionList,$count,$offset, $factor, $prefetchedReviewCount, $selectedRatingFilter,$selectedTagId,$onlyPublished);
            
            if($instituteReviews!=null && count($instituteReviews)>0){
                return $instituteReviews;
            }
            return array('topContent'=>array(),'numFound'=>0);
        }
        $instituteReviews = $this->reviewmodel->getListingReviews($id,$type,$exclusionList, $selectedRatingFilter,$selectedTagId,false);
        
        $allreviews = array();
        $unverifiedReviews = array();
        foreach ($instituteReviews as $reviews) {
            foreach ($reviews as $key => $review) {
                if($review['status'] == 'published'){
                    $allreviews[$key] = $review;
                }
                else if($review['status'] == 'unverified'){
                    $unverifiedReviews[$key] = $review;
                }
            }
        }

        //_p($allreviews);die;

        $courseIds = array();
        foreach ($allreviews as  $review) {
            $courseIds[]=$review['courseId'];
        }
        foreach ($unverifiedReviews as  $review) {
            $courseIds[]=$review['courseId'];
        }
        $coursePopularity = $this->_CI->listingcommonlib->listingViewCount('course',$courseIds);
        
        foreach ($allreviews as $reviewId => $review) {
            $allreviews[$reviewId]['coursePopularity'] = $coursePopularity[$review['courseId']];
        }
        foreach ($unverifiedReviews as $reviewId => $review) {
            $unverifiedReviews[$reviewId]['coursePopularity'] = $coursePopularity[$review['courseId']];
        }
        $response = $this->_CI->reviewsorter->sort($allreviews,$factor);
        $numFound = count($response);
        $unverifiedResponses = $this->_CI->reviewsorter->sort($unverifiedReviews,$factor);
        foreach ($unverifiedResponses as $key => $unverifiedResponse) {
            $response[] = $unverifiedResponse;
        }
        $data = getFormattedData($response, $offset, $count);
        $data['numFound'] = $numFound;
        $data['totalNumFound'] = $numFound + count($unverifiedResponses);
        return $data;
    }

    /**
    * Function to check if reviews exist for institutes 
    * @param : $instituteIds : Array of InstituteId (MANDATORY Array of INTEGER)
    * @return : Array which is a subset of input instituteIds on which reviews exist
    *
    **/
    public function checkContentExistForInstitute($instituteIds){
        if(count($instituteIds) <= 0 || !is_array($instituteIds)){
            return array();
        }
        //First check in cache, consider those results found in cache
        $keyPrefix="INS_REVW_EXIST#";
        $instituteIds = array_unique($instituteIds);
        
        $resultFromCacheWithContent=array();
        $resultFromSource=array();
        
        $idsForCache=array();
        foreach ($instituteIds as $instituteId) {
            $idsForCache[] = $keyPrefix.$instituteId;    
        }
        
        $responseFromCache = $this->cache->getMemberOfMultipleString($idsForCache);
        $idsForSource=array();
        foreach ($responseFromCache as $orderkey => $contentFlag) {
            if($contentFlag==NULL){
                $idsForSource[] = $instituteIds[$orderkey];
            }
            else{
                if($contentFlag=='1'){
                    $resultFromCacheWithContent[] = $instituteIds[$orderkey];
                }
            }
        }
        
        //Fetch rest from source
        if(!empty($idsForSource)) {
            $instituteChildren=array();
            $allInstitutes=array();
            
            //Fetch for all Institutes
            foreach ($idsForSource as $instituteId) {
                $institutesCourses=$this->_CI->institutedetaillib->getAllCoursesForInstitutes($instituteId);
                $instituteChildren[$instituteId]=array_keys($institutesCourses['type']);
                if(empty($instituteChildren[$instituteId])){
                    $instituteChildren[$instituteId]=array();
                }
                $allInstitutes=array_merge($allInstitutes,$instituteChildren[$instituteId]);
                $allInstitutes[] = $instituteId;
            }
                
            $filteredIds = $this->_CI->reviewrecommendationmodel->getListingsByListingsWithMinCourseReviews($allInstitutes,'institute','institute');
            $allInstitutesWithContent=array_flip($filteredIds);
            
            //Prepare cache keys
            $newIdsForCache = array();
            foreach ($instituteChildren as $parentInstituteId => $childInstitutes) {
                $newIdsForCache[$keyPrefix.$parentInstituteId] = '0';
                foreach ($childInstitutes as $instituteId) {
                    if(array_key_exists($instituteId, $allInstitutesWithContent)){
                        $resultFromSource[] = $parentInstituteId;
                        $newIdsForCache[$keyPrefix.$parentInstituteId] = '1';
                        break;
                    }
                }
            }
            //Update cache
            if(!empty($newIdsForCache)){
                $this->cache->setPipeline();
                foreach ($newIdsForCache as $cacheKey=>$contentFlag) {
                    $this->cache->addMemberToString($cacheKey,$contentFlag,$this->expireInSeconds,FALSE,TRUE);
                }
                $this->cache->executePipeline();
            }
        }
        $response=array_merge($resultFromCacheWithContent,$resultFromSource);
        return $response;
    }
    
    /**
    * Function to check if reviews exist for universities 
    * @param : $instituteId : Array of universityIds (MANDATORY Array of INTEGER)
    * @return : Array which is a subset of input universityIds on which reviews exist
    *
    **/
    public function checkContentExistForUniversity($universityIds, $preFetchedCourseIds=array()){
        if(count($universityIds) <= 0 || !is_array($universityIds)){
            return array();
        }
        
        $allUniversityChildren = array();
        $allInstitutes = array();
        foreach ($universityIds as $universityId) {

            if(empty($preFetchedCourseIds[$universityId])){
                $universityChildren = $this->_CI->institutedetaillib->getInstituteCourseIds($universityId,'university');
            }
            else{
                $universityChildren = $preFetchedCourseIds[$universityId];
            }

            $allUniversityChildren[$universityId] = array_keys($universityChildren['type']);
            if(empty($allUniversityChildren[$universityId])){
                $allUniversityChildren[$universityId]=array();
        }
            else{
                $allInstitutes = array_merge($allInstitutes,$allUniversityChildren[$universityId]);
            }
            $allInstitutes[]=$universityId;
        }

        $filteredIds = $this->_CI->reviewrecommendationmodel->getListingsByListingsWithMinCourseReviews($allInstitutes,'institute','institute');
        $institutesWithContent=array_flip($filteredIds);

        $contentFlag = array();
        foreach ($universityIds as $universityId) {
            foreach ($allUniversityChildren[$universityId] as $childId) {
                if(array_key_exists($childId, $institutesWithContent)){
                    $contentFlag[] = $universityId;
                    break;
                }
            }
        }
        return $contentFlag;
    }

    /**
    * Function to check if reviews exist for courses
    * @param : $courseIds : Array of courseIds (MANDATORY Array of INTEGER)
    * @return : Array which is a subset of input courseIds on which reviews exist
    *
    **/
    public function checkContentExistForCourse($courseIds){
        if(count($courseIds) <= 0 || !is_array($courseIds)){
            return array();
        }

        $filteredIds = $this->_CI->reviewrecommendationmodel->getListingsByListingsWithMinCourseReviews($courseIds,'course','course');
        return $filteredIds;
        }
        
    /**
    * Function to get courses that have reviews for institutes
    * @param : $instituteId : Single InstituteId (MANDATORY INTEGER)
    * @return : Array of courseIds on which reviews exist
    *
    **/
    public function getFiltersForInstitute($instituteId,$onlyPublished=true){
        if(!is_numeric($instituteId)){
            return array();
        }
        $allInstitutesCourses=$this->_CI->institutedetaillib->getAllCoursesForInstitutes($instituteId);
        $allInstitutes=array_keys($allInstitutesCourses['type']);
        if(empty($allInstitutes)){
            $allInstitutes=array();
        }
        $allInstitutes[] = $instituteId;

        $filteredIds = $this->_CI->reviewrecommendationmodel->getListingsByListingsWithMinCourseReviews($allInstitutes,'institute','course',$onlyPublished);
        return $filteredIds;
            }

    /**
    * Function to get institute-wise courses that have reviews for that university
    * @param : $universityId : Single UniversityId (MANDATORY INTEGER)
    * @return : Array of 2 indices. 
    *               'courseIds' is array 0f courseIds (mampped to university) on which reviews exist.
    *               'instituteWiseCourses' is Institutewise courseIds on which reviews exist.
    *
    * Output :
    *    Array
    *    (
    *        [courseIds] => Array
    *            (
    *                [0] => 4574
    *                [1] => 110580
    *                [2] => 110582   //courses directly mapped to university
    *            )
    *        [instituteWiseCourses] => Array
    *            (
    *                [3050] => Array
    *                    (
    *                        [0] => 457
    *                        [1] => 345
    **                       [2] => 83252
    *                        [3] => 75885
    *                    ),
    *        
    *                [467] => Array
    *                    (
    *                        [0] => 8363
    *                        [1] => 8768
    *                        [2] => 7896
    *                        [3] => 5688
    *                    )
    *         )
    *    )
    *
    **/
    public function getFiltersForUniversity($universityId, $preFetchedCourseIds = array(),$onlyPublished=true){
        if(!is_numeric($universityId) && $universityId < 1){
            return array();
        }

        if(empty($preFetchedCourseIds)){
            $universityCourses = $this->_CI->institutedetaillib->getInstituteCourseIds($universityId,'university');
        }
        else{
            $universityCourses = $preFetchedCourseIds;
        }

        $institutes=array_keys($universityCourses['type']);
        if(empty($institutes)){
            $institutes=array();
        }
        $institutes[]=$universityId;
        
        $filteredIds = $this->_CI->reviewrecommendationmodel->getListingsByListingsWithMinCourseReviews($institutes,'institute','course',$onlyPublished);
        $allCoursesWithContent=array_flip($filteredIds);
        
        $instituteWiseResponse=array();
        foreach ($universityCourses['instituteWiseCourses'] as $instituteKey => $courseArray) {
            foreach ($courseArray as $courseId) {
                if(array_key_exists($courseId, $allCoursesWithContent)){
                    $instituteWiseResponse[$instituteKey][]=$courseId;
                }
            }
        }

        $universityResponse['courseIds'] = $instituteWiseResponse[$universityId];
        if($universityResponse['courseIds'] == null){
            $universityResponse['courseIds'] = array();
    }
        unset($instituteWiseResponse[$universityId]);
        $universityResponse['instituteWiseCourses'] = $instituteWiseResponse;

        return $universityResponse;
    }
	
    /**
    * Function to get count of reviews for  multiple university/institutes 
    * @param : $instituteIds : array institute/university Ids (MANDATORY Array of INTEGER)
    * @param : $preFetchedCourseIds : Output of institutedetaillib->getallCoursesForInstitutes() 
    *                                  with institute Id as key as output as value.
    *
    * @return : Array of reivew counts with key as institute/univ Id
    *
    **/
    public function getInstituteReviewCounts($instituteIds,$preFetchedCourseIds = array()){
        if(count($instituteIds) <= 0 || !is_array($instituteIds)){
            return array();
        }
        $cacheKeyPrefix = getCachePrefix('institute','review');
        
        $responseFromCache = array();
        if($cacheKeyPrefix!=''){
            $responseFromCache = getCountsFromCache($instituteIds,$cacheKeyPrefix);
        }

        $idsForSource=array_diff($instituteIds, array_keys($responseFromCache));
        $responseFromSource = $this->getInstituteReviewCountsFromDB($idsForSource,$preFetchedCourseIds);

        updateCountsCache($responseFromSource,$cacheKeyPrefix,$this->countCacheExpiry);

        return $responseFromSource+$responseFromCache;

    }

    /**
    * Function to get count of reviews for a multiple university/institutes from db
    * @complexity : number of $instituteIds + 2 db queries
    **/
    public function getInstituteReviewCountsFromDB($instituteIds,$preFetchedCourseIds = array()){

        if(count($instituteIds) <= 0 || !is_array($instituteIds)){
            return array();
        }
        
        $instituteWiseInstitutes = array();
        $allInstitutes = array();
        $allCourses = array();
        foreach ($instituteIds as $instituteId) {

            if(empty($preFetchedCourseIds[$instituteId])){
                $instituteHierarchy = $this->_CI->institutedetaillib->getallCoursesForInstitutes($instituteId);
            }
            else{
                $instituteHierarchy = $preFetchedCourseIds[$instituteId];
            }
            $courses = $instituteHierarchy['courseIds'];
            $instituteWiseInstitutes[$instituteId] = array_keys($instituteHierarchy['type']);
            if(empty($instituteWiseInstitutes[$instituteId])){
                $instituteWiseInstitutes[$instituteId]=array();
            }
            else{
                $allInstitutes = array_merge($allInstitutes,$instituteWiseInstitutes[$instituteId]);
            }
            if(!empty($courses)){
                $allCourses = array_merge($allCourses,$courses);
            }
            $allInstitutes[]=$instituteId;
        }
        $allInstitutes = array_unique($allInstitutes);
        $allCourses = array_unique($allCourses);
        $reviewCounts = $this->_CI->reviewrecommendationmodel->getInstituteReviewCount($allCourses);
        $instituteWiseReviewCounts=array();
        foreach ($instituteIds as $instituteId) {
            $tempCount=0;
            foreach ($instituteWiseInstitutes[$instituteId] as $childInstituteId) {
                $tempCount+=$reviewCounts[$childInstituteId];
            }
            $instituteWiseReviewCounts[$instituteId] = $tempCount;
        }
        return $instituteWiseReviewCounts;
    }
}

?>
