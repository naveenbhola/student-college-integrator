<?php
class saContentLib
{
    private $CI;
    private $sacontentmodel;
    
    // constructor defined here just for the reason to get CI instances for loading different classes in Codeigniter to perform business logic
    public function __construct() {
        $this->CI = & get_instance();
        $this->_setDependencies();
    }
    
    // to get model instance.
    private function _setDependencies(){
        $this->CI->load->model('blogs/sacontentmodel');
        $this->sacontentmodel = new sacontentmodel();
    }

    public function getRecommendedContents($contentId,$contentType,$noOfcontent)
    {
        $result = $this->sacontentmodel->getRecommendedContents($contentId,$contentType,$noOfcontent);
        
        if(empty($result)) { return array(); }
        return $result;
    }
    /*
     *  function to prepare download guide widget where a custom text(eg: _DOWNLOAD_WIDGET_127,132) is added in the 
     *      article content and it gets replaced by download guide wideget. Currently implemented for article pages
     *  
     */
    public function prepareDownloadGuideWidget(&$contentDetails,$isMobile){
        if($contentDetails['data']['type']!='article'){
            return;
        }
        if(strpos($contentDetails['data']['sections']['0']['details'],DOWNLOAD_GUIDE_WIDGET_SNIPPET_START) ===false){
            return;
        }
        $matchingStrings =  array();
        $pattern = '/'.DOWNLOAD_GUIDE_WIDGET_SNIPPET_START.'.*'.DOWNLOAD_GUIDE_WIDGET_SNIPPET_END.'/';
        preg_match($pattern,$contentDetails['data']['sections']['0']['details'],$matchingStrings);
        $contentIds = $this->extractIdsFromMatchedString($matchingStrings);
        $contentIds = array_slice($contentIds,0,DOWNLOAD_GUIDE_SLIDER_WIDGET_COUNT);
        if(count($contentIds)<DOWNLOAD_GUIDE_SLIDER_WIDGET_COUNT){
            $this->CI->load->domainClass('blogs/downloadGuideWidgetSnippet');
            $downloadGuideWidgetSnippet = new downloadGuideWidgetSnippet($contentDetails['data']['content_id'],$this->sacontentmodel);
            $requiredRecommendations    = DOWNLOAD_GUIDE_SLIDER_WIDGET_COUNT - count($contentIds);
            $recommendedContentIds      = $downloadGuideWidgetSnippet->getGuidesForDownloadWidget($requiredRecommendations,$contentIds);
        }
        $contentData = $this->sacontentmodel->getContentDataForDownloadGuideWidget($contentIds);
        $recommendedContentData = $this->sacontentmodel->getContentDataForDownloadGuideWidget($recommendedContentIds);
        for($i=0;$i<count($recommendedContentData);$i++){
            $contentData[] = $recommendedContentData[$i];
        }
        $contentData['widgetData'] = $contentData;
        for($i=0;$i<count($contentData);$i++){
            if($contentData[$i]['type']=='applyContent'){
                $downloadContentCount = $this->sacontentmodel->downloadCountForContent($contentIds);
            }else{
                $downloadCount = $this->sacontentmodel->downloadCountForGuide($contentIds);
            }
        }
        foreach($contentData['widgetData'] as $key=>$data){
            $contentId = $contentData['widgetData'][$key]['content_id'];
            if($contentData['widgetData'][$key]['type']=='applyContent'){
               $contentData['widgetData'][$key]['downloadCount'] = $downloadContentCount[$contentId]; 
            } else{
               $contentData['widgetData'][$key]['downloadCount'] = $downloadCount[$contentId];
            }
        }
        if($isMobile===true){
            $downloadGuideWidgetHTML = $this->CI->load->view('contentPage/widgets/alsoViewedSection',$contentData,true); 
        }else{
            $downloadGuideWidgetHTML = $this->CI->load->view('blogs/saContent/downloadGuideWidget',$contentData,true); 
        }
        $contentDetails['data']['sections']['0']['details'] = preg_replace($pattern,$downloadGuideWidgetHTML,$contentDetails['data']['sections']['0']['details'],1);
        $contentDetails['data']['sections']['0']['details'] = preg_replace($pattern,'',$contentDetails['data']['sections']['0']['details']);
        $contentDetails['data']['summary'] = preg_replace($pattern,'',$contentDetails['data']['summary']);
        
    }
    private function extractIdsFromMatchedString($matchingStrings){
        $matchingString = $matchingStrings[0]; // as we care only for first occurrence
        $matchingString = str_replace(DOWNLOAD_GUIDE_WIDGET_SNIPPET_START,'', $matchingString);
        $matchingString = str_replace(DOWNLOAD_GUIDE_WIDGET_SNIPPET_END,'', $matchingString);
        $contentIds = array_filter(explode('_', $matchingString));
        $downloadableIds = $this->sacontentmodel->checkIfContentDownloadable($contentIds);
        return $downloadableIds;
    }
    /*
     * function to check if currently logged in user(if any) is eligible to delete a comment/reply.
     * Checks for :
     * 1. user being study abroad admin
     * 2. user being author of the exam page
     * 3. user being a registered user at shiksha & is the owner of the comment
     * 4. user saved in cookie for commenting purposes & is the owner of the comment
     * @params : $userData (data from checkUserValidation()), array(author id, email id, user id [of the registered user who posted the comment])
     */
    public function checkUserEligibilityForCommentDeletion($userData, $commentData)
    {
        // user logged in ??
        //_p($commentData);
        if($userData != 'false')
        {
            if(// is the logged in user saAdmin ?? (1.)
               ($userData[0]['usergroup'] == 'saAdmin') ||
               // is the logged in user exam page author ?? (2.)
               ($userData[0]['userid'] == $commentData['authorId']) ||
               // is the logged in user, original comment/reply poster ?? (3.)
               ($commentData['userId'] != 0 && $commentData['userId'] == $userData[0]['userid'])
              )
            {   // capable of deleting the comment
            return true;
            }
            else // cannot delete
            {
            return false;
            }
        }
        else // user in cookie ?? ...
        {   // ...& is the same user who posted the comment ? (4.)
            if($commentData['emailId'] == $_COOKIE['sacontent_userEmail'])
            {   //  capable of deleting the comment
            return true;
            }
            else // cannot delete
            {
            return false;
            }
        }
    }

    public function getExamContentDetails($curUrl){
        $data = $this->sacontentmodel->getExamContentDetailsByUrl($curUrl);
        return $data;
    }
     /*Following is the logic 
    * For GRE, show articles tagged on MS.
    *For GMAT, show articles tagged on MBA
    *For SAT, show articles tagged on BTech or undergraduate
    *For PTE, show articles tagged on Australia and New Zealand
    *For IELTS, show articles tagged on Canada, Australia, New Zealand, UK 'UK','Ireland','Germany','France','Netherlands','Italy','Spain','Sweden ','Switzerland','Poland','Russia','Lithuania','Turkey','Ukraine  ','Estonia  ','Portugal  ','Denmark  ','Greece  ','Cyprus  ','Hungary  ','Georgia  ','Luxembourg  ','Slovenia  ','Norway  ','Belgium  ','Monaco'
    *For TOEFL, show articles tagged on USA, Canada
    */
    public function prepareDataForRelatedArticlesRightWidget($data)
    {
        global $listOfValidExamAcceptedCPCombinations;
        //fetch exam
        $sacontentmodel = $this->CI->load->model('blogs/sacontentmodel');
        $examName = strtoupper($data['examName']);
        $data = array( 'articlesCount' => 6);
        $result = array();
        if(isset($listOfValidExamAcceptedCPCombinations[$examName]['courseMapForArticle']))
        {
            $data['ldbCourseId'] = $listOfValidExamAcceptedCPCombinations[$examName]['courseMapForArticle'];
            $result = $this->getArticlesOnLDBCourses($data,$sacontentmodel);
        }
        elseif (isset($listOfValidExamAcceptedCPCombinations[$examName]['countryMapForArticle']))
        {
            $data['countryId'] = $listOfValidExamAcceptedCPCombinations[$examName]['countryMapForArticle'];
            $result = $this->getArticlesOnCountries($data,$sacontentmodel);
        }
        foreach($result as $key=>$row){
            $result[$key]['contentImageURL'] = MEDIAHOSTURL.$result[$key]['contentImageURL'];
            $result[$key]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$result[$key]['contentURL'];
        }
        return $result;
    }

    public function getArticlesOnLDBCourses($data,$sacontentmodel)
    {
        $result = $sacontentmodel->getArticlesOnLDBCourses($data);
        if(!empty($result))
        {
            return $result;
        }
        else
        {
             return array();
        }
    }
    

    public function getArticlesOnCountries($data,$sacontentmodel)
    {
        $result = $sacontentmodel->getArticlesOnCountries($data);
        if(!empty($result))
        {
            return $result;
        }
        else
        {
             return array();
        }
    }

    public function getRandomContentDetailsByExam($examId,$contentId){
        $results = $this->sacontentmodel->getRandomContentDetailsByExam($examId,$contentId);
        foreach($results as $key=>$row){
            $results[$key]['contentImageURL'] = MEDIAHOSTURL.$results[$key]['contentImageURL'];
            $results[$key]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$results[$key]['contentURL'];
        }
        return $results;
    }

    public function checkMigratedExamContentRedirection($url){
        $newUrl = $this->sacontentmodel->checkMigratedExamContentRedirection($url);
        if($newUrl !== false){
            redirect(SHIKSHA_STUDYABROAD_HOME.$newUrl,true,301);
            exit;
        }
        return false;
    }
    
    private function _getSeoInfoForexamAcceptingCollegeWidget($examId,$examName,$lbsCourseList){
        global $listOfValidExamAcceptedCPCombinations;
        $this->CI->load->builder('AbroadCategoryPageBuilder','categoryList');
        $detailArray = array();
        $requestData = array();
        if($listOfValidExamAcceptedCPCombinations[$examName] !=''){
            $requestData['examId'] = $examId;
            $requestData['examName'] = $examName;
            $requestData['createExamCategoryPageUrlFlag'] = true;
            foreach($lbsCourseList as $key=>$val){
                $requestData['examAcceptingCourseName'] = $val;
                $requestData['LDBCourseId'] = $key;
                
                $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);
                $request = $categoryPageBuilder->getRequest();
                $detailArray[$examName][$key] = $request->getSeoInfo();
            }
        }
        return $detailArray;
    }
    
    public function examAcceptingCollegeWidget($examId,$examName){
        global $listOfValidExamAcceptedCPCombinations;
        $detailArray = array();
        $examName = strtoupper($examName);
        $lbsCourseList = $listOfValidExamAcceptedCPCombinations[$examName]['ldbNameIdMap'];
        $detailArray = $this->_getSeoInfoForexamAcceptingCollegeWidget($examId,$examName,$lbsCourseList);
        if(count($detailArray)==0){
            return $detailArray;
        }
        $lbsCourseIds = array_keys($lbsCourseList);
        if(count($lbsCourseIds)>0){
            $abroadListingCommonLib =  $this->CI->load->library('listing/AbroadListingCommonLib');
            $countData = $abroadListingCommonLib->getCollegeCountsByLDBCourseAndExam($lbsCourseIds,$examId);
        }
        foreach($lbsCourseIds as $desiredID){
            if(isset($countData[$desiredID]) && $countData[$desiredID] >0)
            {
                $detailArray[$examName][$desiredID]['totalCount'] = $countData[$desiredID];
            }else{
                unset($detailArray[$examName][$desiredID]);
            }
            //due to pat comment we are changing title of Be/Btech
            $detailArray[$examName][$desiredID]['title'] = str_ireplace('BE/Btech','B Tech/BE',$detailArray[$examName][$desiredID]['title']);
            $detailArray[$examName][$desiredID]['title'] = str_replace('Score - Study Abroad','',$detailArray[$examName][$desiredID]['title']);
            $detailArray[$examName][$desiredID]['title'] = str_replace('scores - Study Abroad','',$detailArray[$examName][$desiredID]['title']);
            $detailArray[$examName][$desiredID]['title'] = str_ireplace('abroad ','',$detailArray[$examName][$desiredID]['title']);
        }
        return $detailArray;
    }
    
    public function getSAExamHomePageURLByExamNames($examNames){
        $returnData = array();
        if(count($examNames)<1){
            $returnData['error'] = 'Please provide valid exam name';
        }
        else{
            $returnDataRaw = $this->sacontentmodel->getSAExamHomePageURLByExamNames($examNames);
            if(is_array($returnDataRaw) && count($returnDataRaw)>0){
                foreach($returnDataRaw as $key=>$val){
                    $returnData[$val['examName']] = $val;
                }
            }else{
                $returnData['error'] = 'No home page url found for exam names given';
            }
        }   
        return $returnData;
    }
    
    public function validateContentURL($contenturl){
        $details = array();
        if($contenturl !=''){
            $details = $this->sacontentmodel->validateContentURL($contenturl);
        }else{
            $details['error'] = "invalid url";
        }
        return $details;
    }
    
    public function getPopularArticlesLastNnoOfDays($contentId,$noOfDays=30,$noOfarticles=6,$contentType="")
    {
        $saContentCache = $this->CI->load->library('blogs/cache/saContentCache');
        $popularArticleDetails = $saContentCache->getPopularArticles($noOfDays);
        if(empty($popularArticleDetails))
        {
            $popularArticleDetails = $this->sacontentmodel->getPopularArticlesLastNnoOfDays($noOfDays,$noOfarticles,$contentType);
            $saContentCache->storePopularArticles($noOfDays,$popularArticleDetails);
        }

        $key = array_search($contentId,array_map(function($a){ return $a['contentId'];},$popularArticleDetails));
        if($key !== false){
            unset($popularArticleDetails[$key]);
        }
        $popularArticleDetails = array_slice($popularArticleDetails,0,$noOfarticles);
        return $popularArticleDetails;
    }
    
    
}
