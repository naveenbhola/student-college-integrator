<?php
/**
 * AnARecommendationLib Library Class
 *
 *
 * @package     ContentRecommendation
 * @subpackage  Libraries
 *
 */

class AnARecommendationLib {

    private $_CI;
    private $anarecommendmodel;
    
    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->anarecommendmodel = $this->_CI->load->model('ContentRecommendation/anarecommendationmodel');
        $this->_CI->load->library('ContentRecommendation/AnASorter');
        $this->_CI->load->library('nationalInstitute/InstituteDetailLib');
        $this->_CI->load->helper('ContentRecommendation/recommend');
        $this->cache = PredisLibrary::getInstance();
        $this->expireInSeconds = 86400;
        $this->countCacheExpiry = 28800; // 8 hrs
    }


    /**
    * Function to get top questions (or discussions) for a particular institute 
    * @param : $instituteId : Single institute Id (MANDATORY INTEGER)
    * @param : $exclusionList: Array of question (or discussion Ids) (Optional Array of INTEGER)
    * @param : $count : total no of questions required (Optional INTEGER)
    * @param : $offset : Offset wrt to top questions found (Optional INTEGER)
    * @param : $contentType: 'discussion' (MANDATORY) or 'question' (Optional STRING) 
    * @param : $factor: 'RELEVANCY' (default) or 'RECENCY' (Optional STRING) 
    *
    * @return : Array with 2 indices.'topContent' is Sorted Array of question (or discussion Ids)
    *            corresponding to msgId in messageTable. 'numFound' is total content found
    *
    * Usage  : $this->anarecommendationlib->forInstitute(3050, array(), 5);
    * Output : 
    *     Array
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
    public function forInstitute($instituteId,$exclusionList=array(),$count=5,$offset=0,$contentType='question',$factor = 'RELEVANCY'){
        if($count < 0 || $offset < 0){
            return array();
        }

        if(!is_numeric($instituteId)){
            return array();
        } 
        $allInstitutesCourses=$this->_CI->institutedetaillib->getAllCoursesForInstitutes($instituteId);
        $allInstitutes=array_keys($allInstitutesCourses['type']);
        if(empty($allInstitutes)){
            $allInstitutes=array();
        }
        $allInstitutes[] = $instituteId;
        
        $response = $this->anarecommendmodel->getRecommendedInstituteAnA($allInstitutes,$exclusionList,$contentType);
        
        $content = array();
        foreach ($response as $instituteArray) {
            $content=$content+$instituteArray;
        }

        $sortedIds = $this->_CI->anasorter->sort($content, $factor);
        
        return getformattedData($sortedIds, $offset, $count);
    }

    /**
    * Function to get (direct)questions for a multiple institutes sorted by recency 
    * @param : $instituteIds : array of instituteIds (MANDATORY Array of INTEGER)
    * @param : $exclusionList: Array of question (Optional Array of INTEGER)
    * @param : $count : total no of questions required (Optional INTEGER)
    * @param : $offset : Offset wrt to recent questions found (Optional INTEGER)
    *
    * @return : Array with 2 indices.'topContent' is Sorted Array of question 
    *            corresponding to msgId in messageTable. 'numFound' is total content found
    *
    * Usage  : $this->anarecommendationlib->forInstitutesByRecency(3050, array(), 5);
    * Output : 
    *     Array
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
    public function forInstitutesByRecency($instituteIds,$exclusionList=array(),$count=5,$offset=0){
        if($count < 0 || $offset < 0){
            return array();
        }
        if(!is_array($instituteIds)){
            return array();
        } 
        
        $response = $this->anarecommendmodel->getRecommendedInstituteAnaByFactor($instituteIds,$exclusionList,$count,$offset,'question','THREAD_RECENCY');
        
        if($response!=null && count($response)>0){
            return $response;
        }
        return array('topContent'=>array(),'numFound'=>0);
    }

    /**
    * Function to get top questions (or discussions) for a particular University 
    * @param : $universityId : Single institute Id (MANDATORY INTEGER)
    * @param : $exclusionList: Array of question (or discussion Ids) (Optional Array of INTEGER)
    * @param : $count : total no of questions required (Optional INTEGER)
    * @param : $offset : Offset wrt to top questions found (Optional INTEGER)
    * @param : $contentType: 'discussion' (MANDATORY) or 'question' (Optional STRING) 
    * @param : $factor: 'RELEVANCY' (default) or 'RECENCY' (Optional STRING) 
    *
    * @return : Array with 2 indices.'topContent' is Sorted Array of question (or discussion Ids)
    *            corresponding to msgId in messageTable. 'numFound' is total content found
    *
    * Usage  : $this->anarecommendationlib->forUniversity(1, array(), 5);
    * Output : 
    *     Array
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
    public function forUniversity($universityId,$exclusionList=array(),$count=5,$offset=0,$contentType='question',$factor = 'RELEVANCY', $preFetchedCourseIds = array()){
        if($count < 0 || $offset < 0){
            return array();
        }

        if(!is_numeric($universityId)){
            return array();
        }
        
        if(empty($preFetchedCourseIds))
            $universityChildren = $this->_CI->institutedetaillib->getInstituteCourseIds($universityId,'university');
        else
            $universityChildren = $preFetchedCourseIds;
        
        $universityChildrenId = array_keys($universityChildren['type']);

        $response = $this->anarecommendmodel->getRecommendedInstituteAnA($universityChildrenId,$exclusionList,$contentType);

        $content = array();
        
        foreach ($response as $instituteContent) {
            $content = $content + $instituteContent;
        }

        $sortedIds = $this->_CI->anasorter->sort($content, $factor);
        
        return getformattedData($sortedIds, $offset, $count);
    }

    /**
    * Function to get top questions for a particular Course 
    * @param : $courseId : Single institute Id (MANDATORY INTEGER)
    * @param : $exclusionList: Array of question Ids (Optional Array of INTEGER)
    * @param : $count : total no of questions required (Optional INTEGER)
    * @param : $offset : Offset wrt to top questions found (Optional INTEGER)
    * @param : $factor: 'RELEVANCY' (default) or 'RECENCY' (Optional STRING) 
    *
    * @return : Array with 2 indices.'topContent' is Sorted Array of question Ids
    *            corresponding to msgId in messageTable. 'numFound' is total content found
    *
    * Usage  : $this->anarecommendationlib->forCourse(965, array(), 5);
    * Output : 
    *     Array
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
    public function forCourse($courseId,$exclusionList=array(),$count=5,$offset=0,$factor = 'RELEVANCY'){
        if($count < 0 || $offset < 0){
            return array();
        }
        if(!is_numeric($courseId)){
            return array();
        }

        $questions = $this->anarecommendmodel->getRecommendedCourseQuestions(array($courseId),$exclusionList);

        $sortedIds = $this->_CI->anasorter->sort($questions,$factor);

        return getformattedData($sortedIds, $offset, $count);
    }

    /**
    * Function to get top questions for multiple Courses
    * @param : $courseIds : Array of Course Id (MANDATORY ARRAY)
    * @param : $exclusionList: Array of question Ids (Optional Array of INTEGER)
    * @param : $count : total no of questions required (Optional INTEGER)
    * @param : $offset : Offset wrt to top questions found (Optional INTEGER)
    * @param : $factor: 'RELEVANCY' (default) or 'RECENCY' (Optional STRING)
    *
    * @return : Array with 2 indices.'topContent' is Sorted Array of question Ids
    *            corresponding to msgId in messageTable. 'numFound' is total content found
    *
    * Usage  : $this->anarecommendationlib->forCourses(array(965,123), array(), 5);
    * Output :
    *     Array
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
    public function forCourses($courseIds,$exclusionList=array(),$count=5,$offset=0,$factor = 'RELEVANCY'){
        if($count < 0 || $offset < 0){
            return array();
        }
        if(!is_array($courseIds)){
            return array();
        }
        if(count($courseIds) <= 0){
            return array();
        }

        $questions = $this->anarecommendmodel->getRecommendedCourseQuestions($courseIds,$exclusionList);

        $sortedIds = $this->_CI->anasorter->sort($questions,$factor);

        return getformattedData($sortedIds, $offset, $count);
    }

    /**
    * Function to check if questions (or discussions) exist for institutes 
    * @param : $instituteIds : Array of InstituteIds (MANDATORY Array of INTEGER)
    * @param : $contentType: 'discussion' (MANDATORY) or 'question' (Optional STRING) 
    * @return : Array which is a subset of input instituteIds on which questions (or discussions) exist
    *
    **/
    public function checkContentExistForInstitute($instituteIds,$contentType='question'){
        if(count($instituteIds) <= 0 || !is_array($instituteIds)){
            return array();
        }
        //First check in cache, consider those results found in cache
        $keyPrefix='';
        if($contentType=='question'){
            $keyPrefix = "INS_QUES_EXIST#";
        }
        else{
            $keyPrefix = "INS_DISC_EXIST#";
        }
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
            
            //Fetch for all Institutes
            foreach ($idsForSource as $instituteId) {
                $allInstitutesCourses=$this->_CI->institutedetaillib->getAllCoursesForInstitutes($instituteId);
                $instituteChildren[$instituteId]=array_keys($allInstitutesCourses['type']);
                if(empty($instituteChildren[$instituteId])){
                    $instituteChildren[$instituteId]=array();
                }
                $instituteChildren[$instituteId][] = $instituteId;
            }
            $allInstitutes=array();
            foreach ($instituteChildren as $instituteArray) {
                $allInstitutes=array_merge($allInstitutes,$instituteArray);
            }
                
            $allInstitutesWithContent = $this->anarecommendmodel->checkContentExistForInstitute($allInstitutes,$contentType);
            $allInstitutesWithContent=array_flip($allInstitutesWithContent);       
        
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
    * Function to check if questions (or discussions) exist for universities 
    * @param : $universityIds : Array of universityId (MANDATORY Array of INTEGER)
    * @param : $contentType: 'discussion' (MANDATORY) or 'question' (Optional STRING) 
    * @return :Array which is a subset of input universityIds on which questions (or discussions) exist
    *
    **/
    public function checkContentExistForUniversity($universityIds,$contentType='question', $preFetchedCourseIds = array()){
        if(count($universityIds) <= 0 || !is_array($universityIds)){
            return array();
        }
        
        $allUniversityChildren = array();
        foreach ($universityIds as $universityId) {

            if(empty($preFetchedCourseIds[$universityId]))
                $universityChildren = $this->_CI->institutedetaillib->getInstituteCourseIds($universityId,'university');
            else
                $universityChildren = $preFetchedCourseIds[$universityId];

            $allUniversityChildren[$universityId] = array_keys($universityChildren['type']);
        }
        
        $allInstitutes = array();
        foreach ($allUniversityChildren as $value) {
             $allInstitutes = array_merge($allInstitutes,$value);
        }

        $allInstitutes = array_unique($allInstitutes);

        $content = $this->anarecommendmodel->checkContentExistForInstitute($allInstitutes,$contentType);
        $content = array_flip($content);


        $contentFlag = array();
        foreach ($universityIds as $universityId) {
            foreach ($allUniversityChildren[$universityId] as $childId) {
                if(array_key_exists($childId, $content)){
                    $contentFlag[] = $universityId;
                    break;
                }
            }
        }
        return $contentFlag;
    }

    /**
    * Function to check if questions exist for courses 
    * @param : $courseIds : Array of courseIds (MANDATORY Array of INTEGER)
    * @return : Array which is a subset of input courseIds on which questions exist
    *
    **/
    public function checkContentExistForCourse($courseIds){
        if(count($courseIds) <= 0 || !is_array($courseIds)){
            return array();
        }
        
        $content = $this->anarecommendmodel->checkContentExistForCourse($courseIds);

        return $content;
    }


    /**
    * Function to get courses that have questions for institutes 
    * @param : $instituteId : Single Institute (MANDATORY INTEGER)
    * @return : Array of courseIds on which questions exist
    *
    **/
    public function getFiltersForInstitute($instituteId, $preFetchedCourseIds = array()){
        if(!is_numeric($instituteId) && $instituteId < 1){
            return array();
        }
        
        if(empty($preFetchedCourseIds))
            $instituteCourses = $this->_CI->institutedetaillib->getInstituteCourseIds($instituteId,'institute');
        else
            $instituteCourses = $preFetchedCourseIds;

        $allCoursesWithContent = $this->checkContentExistForCourse($instituteCourses['courseIds']);

        return $allCoursesWithContent;
    }

    /**
    * Function to get institute-wise courses that have questions for that university. In case of discussion 
    *       this function is used to get institutes that have discussions on them.
    * @param : $universityId : Single UniversityId (MANDATORY INTEGER)
    * @param : $contentType : 'question'/'discussion'
    * @return : Array of 2 indices. 
    *               'courseIds' is array 0f courseIds (mapped to university) on which questions exist.
                        Blank array in case of discussion.
    *               'instituteWiseCourses' is Institutewise courseIds on which questions exist.
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
    *  For discussions 
    *   Output :
    *    Array
    *    (
    *        [courseIds] => Array()
    *        [instituteWiseCourses] => Array
    *            (
    *                [3050] => Array()
    *        
    *                [467] => Array()
    *         )
    *    )
    *
    **/
    public function getFiltersForUniversity($universityId,$contentType='question', $preFetchedCourseIds = array()){
        if(!is_numeric($universityId) && $universityId < 1){
            return array();
        }
        if(!in_array($contentType, array('question','discussion'))){
            return array();
        }

        if(empty($preFetchedCourseIds))
            $universityCourses = $this->_CI->institutedetaillib->getInstituteCourseIds($universityId,'university');
        else
            $universityCourses = $preFetchedCourseIds;

        $allCoursesWithContent = array();
        if($contentType=='question'){
        $allCoursesWithContent = $this->checkContentExistForCourse($universityCourses['courseIds']);
        }
        $allCoursesWithContent = array_flip($allCoursesWithContent);
        
        $institutesWithContent = $this->checkContentExistForInstitute(array_keys($universityCourses['type']),$contentType);
        $institutesWithContent = array_flip($institutesWithContent);
        
        $instituteWiseResponse = array();

        if($contentType=='question'){
		foreach ($universityCourses['instituteWiseCourses'] as $instituteKey => $courseArray) {
		    if(array_key_exists($instituteKey, $institutesWithContent)){
		        foreach ($courseArray as $course) {
		            if(array_key_exists($course, $allCoursesWithContent)){
		                $instituteWiseResponse[$instituteKey][] = $course;
		            }
		            }
		        }
		    }
		}
        else{
            foreach ($institutesWithContent as $instituteId => $value) {
                $instituteWiseResponse[$instituteId] = array();
            }
            $instituteWiseResponse[$universityId] = array();
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
    * Function to get count of ques/discussion for  multiple university/institutes 
    * @param : $instituteIds : array institute/university Ids (MANDATORY Array of INTEGER)
    * @param : $contentType : 'question' or 'discussion'
    * @param : $preFetchedCourseIds : Output of institutedetaillib->getallCoursesForInstitutes() 
    *                                  with institute Id as key as output as value.
    *
    * @return : Array of ana counts with key as institute/univ Id
    *
    **/
    public function getInstituteAnaCounts($instituteIds,$contentType='question',$preFetchedCourseIds = array()){
        if(count($instituteIds) <= 0 || !is_array($instituteIds)){
            return array();
        }
        if(!in_array($contentType, array('question','discussion'))){
            return array();
        }
        $cacheKeyPrefix = getCachePrefix('institute',$contentType);
        
        $responseFromCache = array();
        if($cacheKeyPrefix!=''){
            $responseFromCache = getCountsFromCache($instituteIds,$cacheKeyPrefix);
        }

        $idsForSource=array_diff($instituteIds, array_keys($responseFromCache));

        $responseFromSource = $this->getInstituteAnaCountsFromDB($idsForSource,$contentType,$preFetchedCourseIds);

        updateCountsCache($responseFromSource,$cacheKeyPrefix,$this->countCacheExpiry);

        return $responseFromSource+$responseFromCache;

    }

    /**
    * Function to get count of question/discussion for a multiple university/institutes from db
    * @complexity : number of $instituteIds + 1 db queries
    **/
    public function getInstituteAnaCountsFromDB($instituteIds,$contentType='question',$preFetchedCourseIds = array()){
        
        if(count($instituteIds) <= 0 || !is_array($instituteIds)){
            return array();
        }
        
        $instituteWiseInstitutes = array();
        $allInstitutes = array();
        foreach ($instituteIds as $instituteId) {

            if(empty($preFetchedCourseIds[$instituteId])){
                $instituteHierarchy = $this->_CI->institutedetaillib->getallCoursesForInstitutes($instituteId);
            }
            else{
                $instituteHierarchy = $preFetchedCourseIds[$instituteId];
            }

            $instituteWiseInstitutes[$instituteId] = array_keys($instituteHierarchy['type']);
            
            if(empty($instituteWiseInstitutes[$instituteId])){
                $instituteWiseInstitutes[$instituteId]=array();
            }
            else{
                $allInstitutes = array_merge($allInstitutes,$instituteWiseInstitutes[$instituteId]);
            }
            $allInstitutes[]=$instituteId;
        }
        
        $allInstitutes = array_unique($allInstitutes);
        
        $instituteAna = $this->_CI->anarecommendationmodel->getInstituteAnaIds($allInstitutes,$contentType);
        
        $instituteWiseAnaCounts=array();

        foreach ($instituteIds as $instituteId) {
            $instituteAnaSet=array();
            foreach ($instituteWiseInstitutes[$instituteId] as $childId) {
                if(!empty($instituteAna[$childId])){
                    $instituteAnaSet=array_merge($instituteAna[$childId],$instituteAnaSet);
                }
            }
            $instituteAnaSet = array_unique($instituteAnaSet);
            $instituteWiseAnaCounts[$instituteId] = count($instituteAnaSet);
        }

        return $instituteWiseAnaCounts;
        
    }

    /**
    * Function to get courses that have unanswered questions for institutes
    * @param : $instituteId : Single Institute (MANDATORY INTEGER)
    * @return : Array of courseIds on which questions exist
    *
    **/
    public function getFiltersForInstitute_Unanswered($instituteId, $preFetchedCourseIds = array()){
        if(!is_numeric($instituteId) && $instituteId < 1){
            return array();
        }

        if(empty($preFetchedCourseIds))
            $instituteCourses = $this->_CI->institutedetaillib->getInstituteCourseIds($instituteId,'institute');
        else
            $instituteCourses = $preFetchedCourseIds;

        $allCoursesWithContent = $this->checkContentExistForCourse_Unanswered($instituteCourses['courseIds']);

        return $allCoursesWithContent;
    }
    
    /**
    * Function to check if unanwered questions exist for courses
    * @param : $courseIds : Array of courseIds (MANDATORY Array of INTEGER)
    * @return : Array which is a subset of input courseIds on which questions exist
    *
    **/
    public function checkContentExistForCourse_Unanswered($courseIds){
        if(count($courseIds) <= 0 || !is_array($courseIds)){
            return array();
        }

        $content = $this->anarecommendmodel->checkContentExistForCourse_Unanswered($courseIds);

        return $content;
    }
    

    /**
    * Function to get institute-wise courses that have unanswered questions for that university
    * @param : $universityId : Single UniversityId (MANDATORY INTEGER)
    * @param : $contentType : 'question'/'discussion'
    * @return : Array of 2 indices.
    *               'courseIds' is array 0f courseIds (mampped to university) on which questions exist.
    *               'instituteWiseCourses' is Institutewise courseIds on which questions exist.
    **/
    public function getFiltersForUniversity_Unanswered($universityId, $preFetchedCourseIds = array()){
        if(!is_numeric($universityId) && $universityId < 1){
            return array();
        }

        if(empty($preFetchedCourseIds))
            $universityCourses = $this->_CI->institutedetaillib->getInstituteCourseIds($universityId,'university');
        else
            $universityCourses = $preFetchedCourseIds;

        $allCoursesWithContent = array();
        $allCoursesWithContent = $this->checkContentExistForCourse_Unanswered($universityCourses['courseIds']);
        $allCoursesWithContent = array_flip($allCoursesWithContent);

        $institutesWithContent = $this->checkContentExistForInstitute_Unanswered(array_keys($universityCourses['type']),$contentType);
        $institutesWithContent = array_flip($institutesWithContent);

        $instituteWiseResponse = array();

        foreach ($universityCourses['instituteWiseCourses'] as $instituteKey => $courseArray) {
            if(array_key_exists($instituteKey, $institutesWithContent)){
                foreach ($courseArray as $course) {
                    if(array_key_exists($course, $allCoursesWithContent)){
                        $instituteWiseResponse[$instituteKey][] = $course;
                    }
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
    * Function to check if unanswered questions exist for institutes
    * @param : $instituteIds : Array of InstituteIds (MANDATORY Array of INTEGER)
    * @param : $contentType: 'discussion' (MANDATORY) or 'question' (Optional STRING)
    * @return : Array which is a subset of input instituteIds on which unanswered questions exist
    *
    **/
    public function checkContentExistForInstitute_Unanswered($instituteIds){
        if(count($instituteIds) <= 0 || !is_array($instituteIds)){
            return array();
        }

        $instituteId = $this->anarecommendmodel->checkContentExistForInstitute_Unanswered($instituteIds,$contentType);

        return $instituteId;
    }

     /**
    * Function to get the count of questions wrt to courseId
    * @param : $courseIds : Array of CourseIds (MANDATORY Array of INTEGER
    * @return : One D Array with courseId as key & value as count
    *
    **/
    public function getRecommendedCourseQuestionsCount($courseIds){
        if(count($courseIds) <= 0 || !is_array($courseIds)){
            return array();
        }
        $questionsCount = $this->anarecommendmodel->getRecommendedCourseQuestionsCount($courseIds);
        return $questionsCount;           
    }


    function getAllQuestionsBasedOnTag($listingIds, $countOffset, $startOffset){
        if(count($listingIds) <= 0 || !is_array($listingIds)){
            return array();
        }
        $questionsIds = $this->anarecommendmodel->getAllQuestionBasedOnTag($listingIds, $startOffset, $countOffset);
        return $questionsIds;     

    }

}

?>
