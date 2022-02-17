<?php
/**
 * ArticleRecommendationLib Library Class
 *
 * @package     ContentRecommendation
 * @subpackage  Libraries
 *
 */

class ArticleRecommendationLib {

    private $_CI;
    private $articlemodel;
    private $sortWeights;

    public function __construct()
    {
        $this->_CI = & get_instance();
        
        $this->_CI->config->load('ContentRecommendation/ArticleRecommendation_config');
        $this->sortWeights = $this->_CI->config->item('sortWeights');
        
        $this->articlemodel = $this->_CI->load->model('ContentRecommendation/articlerecommendationmodel');
        $this->_CI->load->library('ContentRecommendation/ArticleSorter');
        $this->_CI->load->library('nationalInstitute/InstituteDetailLib');
        $this->_CI->load->helper('ContentRecommendation/recommend');
        $this->cache = PredisLibrary::getInstance();
        $this->expireInSeconds = 86400;
        $this->countCacheExpiry = 86400;
    }

    /**
    * Function to get top articles for a particular institute 
    * @param : $instituteId : Single institute Id (MANDATORY INTEGER)
    * @param : $exclusionList: Array of article (Optional Array of INTEGER)
    * @param : $count : total no of articles required (Optional INTEGER)
    * @param : $offset : Offset wrt to top articles found (Optional INTEGER)
    *
    * @return : Array of 2 indices. 'topContent' is Sorted Array of articleIds corresponding to blogId
    *           in blogTable and 'numFound' is total content found
    *
    * Usage  :  $this->articlerecommendationlib->forInstitute(467, array(), 5);
    * Output : Array
    *       (
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
    *       )
    **/
    public function forInstitute($instituteId,$exclusionList=array(),$count=5,$offset=0,$factor = 'RELEVANCY'){
        if($count < 0 || $offset < 0){
            return array();
        }
        if(!is_numeric($instituteId) || $instituteId <= 0){
            return array();
        }
        $allInstitutesCourses=$this->_CI->institutedetaillib->getAllCoursesForInstitutes($instituteId);
        $allInstitutes=array_keys($allInstitutesCourses['type']);
        if(empty($allInstitutes)){
            $allInstitutes=array();
        }
        $allInstitutes[]= $instituteId;
        
        $articles = $this->getInstituteArticleDetails($allInstitutes,$exclusionList);
        $instituteArticles=array();
        foreach ($articles as $instituteIdkey => $artilcleArray) {
            $instituteArticles = $instituteArticles+$artilcleArray;
            }
        $response = $this->prepareArticlesForInstitute(array($instituteId=>$instituteArticles),$count,$offset,$factor);
        
        return $response[$instituteId];
        }

    /**
    * Function to get top articles for a particular University 
    * @param : $universityId : Single institute Id (MANDATORY INTEGER)
    * @param : $exclusionList: Array of article (Optional Array of INTEGER)
    * @param : $count : total no of articles required (Optional INTEGER)
    * @param : $offset : Offset wrt to top articles found (Optional INTEGER)
    * @param : $factor: 'RELEVANCY' (default) or 'RECENCY' (Optional STRING) 
    *
    * @return : Array of 2 indices. 'topContent' is Sorted Array of articleIds corresponding to blogId in blogTable
    *            and 'numFound' is total content found
    *
    * Usage  :  $this->articlerecommendationlib->forUniversity(467, array(), 5);
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
    public function forUniversity($universityId,$exclusionList=array(),$count=5,$offset=0,$factor = 'RELEVANCY', $preFetchedCourseIds = array()){
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

        $content = $this->getInstituteArticleDetails($universityChildrenId,$exclusionList);

        $articles = array();

        foreach ($content as $instituteArticles) {
            $articles = $articles + $instituteArticles;
        }
        
        $response = $this->prepareArticlesForInstitute(array($universityId => $articles),$count,$offset,$factor);
        
        return $response[$universityId];
    }

    public function prepareArticlesForInstitute($instituteArticles,$count=5,$offset=0,$factor = 'RELEVANCY'){
        $content = array();
        
        foreach ($instituteArticles as $instituteId => $articles) {
            $response = $this->_CI->articlesorter->sort($articles,$factor);
            $content[$instituteId] = getformattedData($response, $offset, $count);
        }
        return $content;
    }

    /**
    * Function to check if articles exist for institutes 
    * @param : $instituteIds : Array of InstituteId (MANDATORY Array of INTEGER)
    * @return :Array which is a subset of input instituteIds on which articles exist
    *
    **/
    public function checkContentExistForInstitute($instituteIds){
        if(count($instituteIds) <= 0 || !is_array($instituteIds)){
            return array();
        }
        //First check in cache, consider those results found in cache
        $keyPrefix="INS_ARTC_EXIST#";
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
                
            $allInstitutesWithContent = $this->articlemodel->checkContentExistForInstitute($allInstitutes);
            $allInstitutesWithContent=array_flip($allInstitutesWithContent);
            
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
    * Function to check if articles exist for universities 
    * @param : $instituteId : Array of universityIds (MANDATORY Array of INTEGER)
    * @return : Array which is a subset of input universityIds on which articles exist
    **/
    public function checkContentExistForUniversity($universityIds, $preFetchedCourseIds = array()){
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

       $content = $this->articlemodel->checkContentExistForInstitute($allInstitutes);
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
    * Function to get institutes that have articles for that university
    * @param : $universityId : Single UniversityId (MANDATORY INTEGER)
    * @return : Array of instituteIds on which articles exist
    *
    **/
    public function getFiltersForUniversity($universityId, $preFetchedCourseIds = array()){
        if(!is_numeric($universityId) && $universityId < 1){
            return array();
        }
        
        if(empty($preFetchedCourseIds))
            $universityCourses = $this->_CI->institutedetaillib->getInstituteCourseIds($universityId,'university');
        else
            $universityCourses = $preFetchedCourseIds;
        
        $institutesWithContent = $this->checkContentExistForInstitute(array_keys($universityCourses['type']));
        
        if(($key = array_search($universityId, $institutesWithContent)) !== false) {
            unset($institutesWithContent[$key]);    //remove the universityid
        }

        return $institutesWithContent;
    }

    public function getInstituteArticleDetails($instituteId,$exclusionList=array()){

        $instituteArticles = $this->articlemodel->getInstituteArticles($instituteId,$exclusionList);
        
        $allIds=array();
        foreach ($instituteArticles as $value) {
            $allIds=array_merge($allIds,array_keys($value));
        }
        
        $articleCommentCount = $this->articlemodel->getArticleCommentCount($allIds);
        
        $articleTags = $this->articlemodel->getArticleInstituteCount($allIds);
        
        foreach ($instituteArticles as $instituteIdKey => $articleArray) {
            foreach ($articleArray as $articleIdKey => $articleArray) {
                $threadQualityScore = 0;
                $score = log($articleArray['blogView']);
            if($score>0){
                    $threadQualityScore+=$score;
            }
                $score = 1.2*($articleCommentCount[$articleIdKey]); //comments
            if($score>0){
                    $threadQualityScore+=$score;
        }
        
                $instituteArticles[$instituteIdKey][$articleIdKey]['threadQualityScore']=$threadQualityScore;
                $instituteArticles[$instituteIdKey][$articleIdKey]['comments']=$articleCommentCount[$key];
                $instituteArticles[$instituteIdKey][$articleIdKey]['tagCount']=$articleTags[$articleIdKey];
        }
            }
        
        return $instituteArticles;
    }
    
    /**
    * Function to get count of articles for multiple university/institutes 
    * @param : $instituteIds : array institute/university Ids (MANDATORY Array of INTEGER)
    * @param : $preFetchedCourseIds : Output of institutedetaillib->getallCoursesForInstitutes() 
    *                                  with institute Id as key as output as value.
    *
    * @return : Array of article counts with key as institute/univ Id
    *
    **/
    public function getInstituteArticleCounts($instituteIds,$preFetchedCourseIds = array()){
        if(count($instituteIds) <= 0 || !is_array($instituteIds)){
            return array();
        }
        $cacheKeyPrefix = getCachePrefix('institute','article');
        
        $responseFromCache = array();
        if($cacheKeyPrefix!=''){
            $responseFromCache = getCountsFromCache($instituteIds,$cacheKeyPrefix);
        }

        $idsForSource=array_diff($instituteIds, array_keys($responseFromCache));

        $responseFromSource = $this->getInstituteArticleCountsFromDB($idsForSource,$preFetchedCourseIds);

        updateCountsCache($responseFromSource,$cacheKeyPrefix,$this->countCacheExpiry);

        return $responseFromSource+$responseFromCache;

    }

     /**
    * Function to get count of articles for a multiple university/institutes from db
    * @complexity : number of $instituteIds + 1 db queries
    **/
    public function getInstituteArticleCountsFromDB($instituteIds,$preFetchedCourseIds = array()){
        
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
        
        $instituteArticles = $this->_CI->articlerecommendationmodel->getInstituteArticleIds($allInstitutes);

        $instituteWiseArticleCounts=array();

        foreach ($instituteIds as $instituteId) {
            $instituteArticleSet=array();
            foreach ($instituteWiseInstitutes[$instituteId] as $childId) {
                if(!empty($instituteArticles[$childId])){
                    $instituteArticleSet=array_merge($instituteArticles[$childId],$instituteArticleSet);
                }
            }
            $instituteArticleSet = array_unique($instituteArticleSet);
            $instituteWiseArticleCounts[$instituteId] = count($instituteArticleSet);
        }

        return $instituteWiseArticleCounts;
        
    }
    
}

?>
