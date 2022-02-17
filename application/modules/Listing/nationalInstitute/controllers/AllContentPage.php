<?php

class AllContentPage extends MX_Controller {

    private function _init(){
        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }
        $this->institutedetailmodel = $this->load->model('nationalInstitute/institutedetailsmodel');

        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();       
        $this->load->helper('listingCommon/listingcommon');
        $this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib'); 
        $this->load->helper(array('shikshautility','url','string'));
        $this->intitutedetaillib = $this->load->library("nationalInstitute/InstituteDetailLib");
    }

     /**
     * Method to show all article/reviews/questions page on desktop
     * @author Yamini Bisht
     * @date   2016-10-10
     * @param  [type]     $listingId   [institute/university id]
     * @param  [type]     $pageType    [article/review/question]
     * @return [type]                  [displays all article/reviews/questions page]
     */

    public function getAllContentPage($listingId,$pageType,$pageNumber=1){
        $displayData = array();

        if(empty($listingId)){
            show_404();
            exit(0);
        }
        $this->_init();
        $instituteObj  = $this->instituteRepo->find($listingId,array('scholarship'));

        $instituteId   = $instituteObj->getId();
        $instituteType = $instituteObj->getType();

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

        $base_url = $instituteObj->getURL();
        if(empty($instituteObj) || empty($instituteId)){
            show_404();
            exit(0);
        }
        $this->_checkForCommonRedirections($instituteObj, $instituteId, $instituteType, $pageType, $pageNumber);

        $displayData['instituteObj'] = $instituteObj;
        if($pageType=='articles'){
            $countIndex = 20;
        }else if($pageType =='reviews'){
            $countIndex = 15;
        }else{
            $countIndex = 10;
        }
           
        $startIndex = 0;
        if($startIndex >=0 && $countIndex >0 && $pageNumber == ''){
            $pageNumber = ceil($startIndex/$countIndex)+1;
        }

        if($pageNumber >0){
            $startIndex= ($pageNumber-1)*($countIndex);
        }
            
        $start = isset($_POST['start'])?$this->input->post('start'):$startIndex;
        $count = isset($_POST['count'])?$this->input->post('count'):$countIndex;
        $pageNumber = isset($_POST['pageNumber'])?$this->input->post('pageNumber'):$pageNumber;
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In':'Non-Logged In'; 

        $displayData['pageNumber']  = $pageNumber;
        
        //getting query parameters
        $selectedInstituteId = !empty($_GET['institute'])?$this->input->get('institute'):'';
        $selectedCourseId = !empty($_GET['course'])?$this->input->get('course'):'';

        if($pageType =='reviews'){
            $sortingOption = !empty($_GET['sort_by'])?strtoupper($this->input->get('sort_by')):'YEAR OF GRADUATION';
            $selectedFilterRating = !empty($_REQUEST['rating'])? (int) $this->input->get('rating'):'5';
            if($selectedFilterRating > 5) {
                $selectedFilterRating = 5;
            }
            $selectedTagId = !empty($_REQUEST['tagId'])? (int) $this->input->get('tagId'):0;
        }
        else{
            $sortingOption = !empty($_GET['sort_by'])?strtoupper($this->input->get('sort_by')):'RELEVANCE';
        }
        $selectedStreamId = !empty($_GET['stream'])?strtoupper($this->input->get('stream')):'';
        $displayData['contentType'] = !empty($_GET['type'])?$this->input->get('type'):'question'; 

        if( (!empty($selectedInstituteId) && !is_numeric($selectedInstituteId)) || (!empty($selectedCourseId) && !is_numeric($selectedCourseId)) || !in_array(strtoupper($sortingOption), array('RELEVANCE','RECENCY','YEAR OF GRADUATION','HIGHEST RATING','LOWEST RATING')) || !in_array(strtolower($displayData['contentType']), array('question','discussion','unanswered'))  ){
            show_404();
            exit(0);
        }
        
        $displayData['isAjax'] = ($_SERVER['HTTP_X_REQUESTED_WITH'] != '') ? 1 : 0;

        //get which listing type of content we required (preference order: course,institute,university)
        $fetchListingContentType = $instituteType;
        $fetchListingContentId = $instituteId;
        if(!empty($selectedCourseId))
        {
            $fetchListingContentId = $selectedCourseId;
            $fetchListingContentType = 'course';
        }
        elseif (!empty($selectedInstituteId)) {
            $fetchListingContentId = $selectedInstituteId;
            $fetchListingContentType = 'institute';   
        }

        $displayData['selectedInstituteId'] = $selectedInstituteId;
        $displayData['selectedCourseId'] = $selectedCourseId;
        $displayData['selectedStreamId'] = $selectedStreamId;
        $displayData['fetchListingContentType'] = $fetchListingContentType;
        //default order willl be relevance

        $sortingMapping = array('RECENCY' => 'RECENCY', 'YEAR OF GRADUATION' => 'GRADUATION_YEAR' , 'HIGHEST RATING' => 'HIGHEST_RATING' ,'LOWEST RATING' => 'LOWEST_RATING','RELEVANCE' => 'RELEVANCY');

        $displayData['sort_by'] = $sortingMapping[$sortingOption];
        $displayData['selectedSortOption'] = strtolower($sortingOption);
        $displayData['listing_id'] = $instituteId;

        // get all courses mapped to this listing
        $displayData['courseIdsMapping'] = $this->intitutedetaillib->getAllCoursesForInstitutes($instituteId);
        // in case no course is present in institute/university, show 404
        if(empty($displayData['courseIdsMapping']) || empty($displayData['courseIdsMapping']['courseIds'])){
            show_404();
            exit(0);
        }
        $scholarships = $instituteObj->getScholarships();
       /* if($pageType == 'scholarships' && empty($scholarships)){
            show_404();
            exit(0);
        }*/

        $displayData['instituteAbbrev'] = $instituteObj->getAbbreviation();
        //seo related data
        $mainLocation                             = $displayData['instituteObj']->getMainLocation();

        $displayData['locationText']              = getInstituteNameWithCityLocality($displayData['instituteObj']->getName(), $displayData['instituteObj']->getType(), $mainLocation->getCityName(), $mainLocation->getLocalityName());
        $displayData['instituteNameWithLocation'] = $displayData['locationText']['instituteString'];

        $displayData['instituteUrl']              = $displayData['instituteObj']->getUrl();
        
        $displayData['instituteToolTipData']      = $this->config->item('instituteToolTipData');
        $displayData['topWidgetData']             = $this->intitutedetaillib->getTopWidgetData($displayData['instituteObj'],$displayData['locationText']['cityString']);
        
        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam =array('instituteObj'=>$instituteObj,'parentPage'=>'DFP_InstituteDetailPage','pageType'=>$instituteType.'_'.$pageType,'cityId'=>$mainLocation->getCityId(),'stateId'=>$mainLocation->getStateId());
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        $this->getAllCoursesList($displayData);
        
        switch ($pageType) {
            case 'articles':
                $scanPageName = 'allArticlesPage';
                $paginationPage = 'ALL_ARTICLES_PAGE'; 
                $base_url = $base_url.'/articles';
                $sortingOptions = array('Relevance','Recency');
                $this->getAllArticleData($fetchListingContentId,$fetchListingContentType,$start,$count,$displayData);
                $this->getAllArticleSeoDetailsNew($displayData);
                break;

            case 'questions':
                $scanPageName = 'allQuestionsPage';
                $paginationPage = 'ALL_QUESTIONS_PAGE';
                $base_url = $base_url.'/questions';
                $displayData['contentType'] = 'question';

                if(isset($_REQUEST['type'])){
                    $contentType = $_REQUEST['type'];
                    $displayData['contentType'] = $contentType;
                }
                if($displayData['contentType'] == 'question'){                        
                    $sortingOptions = array('Relevance','Recency');
                }
                else{
                    //$displayData['sortingOption'] = '';
                    $displayData['sort_by'] = '';
                    $displayData['selectedSortOption'] = '';
                }
                $this->getAllQuestionData($fetchListingContentId,$fetchListingContentType,$start,$count,$displayData);
                $this->getAllQuestionSeoDataNew($displayData);
                $this->getAllQuestionMISData($displayData,$contentType,$instituteType);
                $displayData['suggestorPageName'] = "all_tags";
                $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','desktop');
                break;

            case 'reviews':
                $scanPageName = 'allReviewsPage';
                $paginationPage = 'ALL_REVIEWS_PAGE';
                $base_url = $base_url.'/reviews';
                $sortingOptions = array('Recency',"Year of Graduation",'Highest Rating','Lowest Rating','Relevance');
                $displayData['selectedFilterRating']    = $selectedFilterRating;
                $displayData['selectedTagId']    = $selectedTagId;
                $this->getAllReviewData($fetchListingContentId,$fetchListingContentType,$start,$count,$displayData,$selectedFilterRating,$selectedTagId,false);
                if($displayData['allReviewsCount'] ==0){
                    show_404();
                }
                $this->getAllReviewsSeoDetailsNew($displayData);
                $displayData['suggestorPageName']    = 'allReviewsPage';
                break;

            case 'admission':
                $scanPageName = 'admissionPage';
                $paginationPage = 'ADMISSION_PAGE';
                $base_url = $base_url.'/admission';
                $this->getAdmissionPageData($fetchListingContentId,$fetchListingContentType,$start,$count,$displayData);
                $this->getAdmissionPageSeoDetailsNew($displayData);
                $selectedCourseId = $displayData['selectedCourseId'];
                $selectedStreamId = $displayData['selectedStreamId'];
                break;
            case 'scholarships':
                $scanPageName = 'scholarshipPage';
                $paginationPage = 'SCHOLARSHIP_PAGE';
                $base_url = $base_url.'/scholarships';
                $this->getScholarshipData($instituteObj,$displayData);
                $this->getScholarshipSeoDetails($displayData);
                $displayData['suggestorPageName'] = "all_tags";
                break;

             default:
                 # code...
                 break;
        }    

        $displayData['isAdmissionDetailsAvailable'] = 0;
        if($pageType == 'admission' || $pageType == 'scholarships')
        {
            $displayData['examList'] = $this->getExamsMappedToUniversity($instituteId);
            $displayData['isAdmissionDetailsAvailable'] = $instituteObj->isAdmissionDetailsAvailable(); 
        }

        //getting Filters for all content page
        $displayData['sortingOptions'] = $sortingOptions;
        $displayData['filtersArray'] = $this->prepareFilters($instituteId,$instituteType,$pageType,$contentType,$displayData);

        $displayData['validateuser'] = $this->userStatus;
        $displayData['pageType']     = $pageType;
        $displayData['listing_type'] = $instituteType;
        $displayData['instituteName'] = $instituteObj->getName();

        $displayData['showContactCTA'] = $this->showContactDetailCTA($instituteObj);
        
        if(!empty($selectedInstituteId)){
            $queryParameters['institute'] = $selectedInstituteId;
        }
        if(!empty($sortingOption)){
            $queryParameters['sort_by'] = strtolower($sortingOption);
        }
        if(!empty($selectedCourseId)){
            $queryParameters['course'] = $selectedCourseId;
        }
         if(!empty($selectedTagId)){
            $queryParameters['tagId'] = $selectedTagId;
        }
        if(isset($_REQUEST['type'])){
            $queryParameters['type'] = $_REQUEST['type'];
        }

        $appendStringtoURL = count($queryParameters) > 0 ? ('?'.http_build_query($queryParameters)) : '';
        $currentUrl = $base_url.'-@pagenum@'.$appendStringtoURL;
        if($pageType=='reviews'){
            $totalCount = $displayData['allReviewsCount'];
        }else{
        $totalCount = $displayData['totalElements'];
        }
        

        if($totalCount > $count) {
            $displayData['paginationHtml'] = doPagination_AnA($totalCount,$currentUrl,$pageNumber,$count,$numPages=10,$paginationPage,$displayData['GA_userLevel'],$instituteType);
        }

        $totalpages = ceil($totalCount/$count);
        if($pageNumber<=1){
            if($totalpages >1){
                $displayData['nextURL'] = $base_url.'-'.($pageNumber+1);
            }
            $displayData['canonicalURL'] = $base_url;
        }else{

            if($pageNumber < $totalpages){
                $displayData['nextURL'] = $base_url.'-'.($pageNumber+1);
                if($pageNumber > 2)
                    $displayData['prevURL'] = $base_url.'-'.($pageNumber-1);
                else
                    $displayData['prevURL'] = $base_url;
            }else if($pageNumber == $totalpages){
                if($pageNumber > 2)
                    $displayData['prevURL'] = $base_url.'-'.($pageNumber-1);
                else
                    $displayData['prevURL'] = $base_url;
            }
            $displayData['canonicalURL'] = $base_url;
        }

        $displayData['base_url'] = $base_url;

        if(isset($_REQUEST['type'])){
            //$displayData['base_url'] .= '?type='.$displayData['contentType'];
            if($displayData['nextURL']){
                    $displayData['nextURL'] .= '?type='.$displayData['contentType'];
            }
            if($displayData['prevURL']){
                    $displayData['prevURL'] .= '?type='.$displayData['contentType'];
            }
        }

        $displayData['gtmParams'] = array(
                        "pageType"    => $scanPageName,
                        "countryId"     => 2,
                        'listingType' => $instituteType
                );
        if($userId > 0){
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }
        if(!empty($selectedInstituteId)) {
            $displayData['gtmParams']['instituteId'] = $selectedInstituteId;
        }
        if(!empty($selectedCourseId) && (!empty($displayData['filtersArray']['baseCourseMapping'][$selectedCourseId]) || !empty($displayData['coursesData']['baseCourseMapping'][$selectedCourseId]) )){
            $displayData['gtmParams']['courseFilter'] = $displayData['filtersArray']['baseCourseMapping'][$selectedCourseId];
        }

        $displayData['beaconTrackData'] = array(
                                        'pageIdentifier' => "UILP",
                                        'pageEntityId' => $instituteId,
                                        'extraData' => array("childPageIdentifier"=>$scanPageName,'url'=>get_full_url())
                                    );
        $displayData['beaconTrackData']['extraData']['listingType'] = $instituteType;
        $displayData['beaconTrackData']['extraData']['countryId'] = 2;

        $displayData['trackForPages'] = true;
        //to identify whether it is all content reviews page/articles/admission/question
        $displayData['productName'] = "ND_AllContentPage_".ucfirst($pageType);
        $displayData['contactToastMsg'] = "Contact details have been mailed to you.";
        $displayData['seoHeadingText'] = $this->intitutedetaillib->getSeoDataForAllChildPages($displayData);

        $displayData['canonicalURL'] = $this->intitutedetaillib->getCanonnicalUrl($listingId,$displayData['canonicalURL']);

        global $MESSAGE_MAPPING,$INSTITUTE_MESSAGE_KEY_MAPPING;
            $displayData['SRM_DATA'] = $MESSAGE_MAPPING[$INSTITUTE_MESSAGE_KEY_MAPPING[$listingId]];
        
        $displayData['showToastMsg'] = false;
        if(!empty($displayData['SRM_DATA'])){
            $displayData['showToastMsg'] = true;
        }

        if($displayData['isAjax']){
            $data = array();
            $data['html'] = $this->load->view('nationalInstitute/AllContentPage/AllContentHomePage',$displayData,true);
            $data['seoTitle'] = $displayData["seoTitle"];
            $data['seoDesc'] = $displayData["seoDesc"];
            $data['backBtn'] = 1;
            echo json_encode($data);die;
        }
        else{
            $this->load->view('nationalInstitute/AllContentPage/AllContentHomePage',$displayData);
        }
    }

    /**
     * Method to fetch all article data
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2016-11-16
     * @param  [type]     $listingId    [institute/university id]
     * @param  [type]     &$displayData [input display data]
     * @return [type]                   [description]
     */
    function getAllArticleData($instituteId,$instituteType,$start,$count,&$displayData){
        $this->load->library('ContentRecommendation/ArticleRecommendationLib');
        if($instituteType == 'institute'){
            $articleArray = $this->articlerecommendationlib->forInstitute($instituteId,array(),$count,$start,$displayData['sort_by']);
            $articleIds = $articleArray['topContent'];
        }else{
            $articleArray = $this->articlerecommendationlib->forUniversity($instituteId,array(),$count,$start,$displayData['sort_by'], $displayData['courseIdsMapping']);
            $articleIds = $articleArray['topContent'];
        }
        

        //get article details from article repository
        if(!empty($articleArray)){
            $this->load->builder('ArticleBuilder','article');
            $this->articleBuilder = new ArticleBuilder;
            $this->articleRepository = $this->articleBuilder->getArticleRepository();
            $articleObj = $this->articleRepository->findMultiple($articleIds);
            if(!empty($articleObj)){
                foreach($articleObj as $key =>$article){
                    $id = $article->getId();
                    $articleData[$id] = array('id'=>$id,
                        'url'=>$article->getUrl(),
                        'blogTitle'=>$article->getTitle(),
                        'summary'=>$article->getSummary(),
                        );
                    }
            }

            foreach($articleArray['topContent'] as $val){
                $result[] = $articleData[$val];
             }

            $displayData['totalElements'] = $articleArray['numFound'];
            $displayData['articleInfo'] = $result;
        }
        else{
            $displayData['totalElements']  = 0;
        }

    
    }
    function getAllArticleSeoDetails(&$displayData)
    {
        $institutehtmlName=special_chars_replace(htmlentities($displayData['instituteNameWithLocation']));
        $displayData['sectionText']               = "Articles";
        $displayData['seoTitle']                  = "Latest News and Articles on ".$institutehtmlName." - Shiksha";
        $displayData['seoDesc']                   = "Read the latest articles and news about ".$institutehtmlName.". Find out about ".$institutehtmlName." eligibility, important dates, fees and much more on Shiksha.com.";
        if($displayData['pageNumber'] > 1){
            $displayData['seoTitle'] = "Page ".$displayData['pageNumber']." - ".$displayData['seoTitle'];
            $displayData['seoDesc']  = "Page ".$displayData['pageNumber']." - ".$displayData['seoDesc'];
        }
    }
    function getAllArticleSeoDetailsNew(&$displayData)
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        if($currentMonth > 10 ){
            $currentYear = $currentYear + 1;
        }
        $displayData['currentYear']=$currentYear;
        $secondaryName = $displayData['instituteObj']->getSecondaryName();
        if($secondaryName != ''){
            $secondaryName = "(".$secondaryName.")";
        }
        $institutehtmlName=special_chars_replace(htmlentities($displayData['instituteNameWithLocation']));
        $displayData['sectionText']               = "Articles";
        $displayData['seoTitle']                  = $institutehtmlName." Latest News and Notifications for ".$currentYear;
        $displayData['seoDesc']                   = "Read the latest news, notifications and articles about cutoffs, placements, courses, fees, admission, ranking, selection criteria & eligibility for ".$institutehtmlName." ".$secondaryName.".";
        if($displayData['pageNumber'] > 1){
            $displayData['seoTitle'] = "Page ".$displayData['pageNumber']." - ".$displayData['seoTitle'];
            $displayData['seoDesc']  = "Page ".$displayData['pageNumber']." - ".$displayData['seoDesc'];
        }
    }

    private function _checkForCommonRedirections($instituteObj, $listingId, $listingType, $pageType, $pageNumber){

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

            if($pageNumber > 1)
                $seo_url     = $instituteObj->getURL()."/".$pageType."-".$pageNumber;
            else
                $seo_url     = $instituteObj->getURL()."/".$pageType;

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

    function getAllQuestionData($listingId, $listingType, $start, $count, &$displayData){
        $this->_init();

        $this->load->library('ContentRecommendation/AnARecommendationLib');
        $contentType = $displayData['contentType'];
        $displayData['instituteUrl']              = $displayData['instituteObj']->getUrl();
        if($listingType== 'institute' && ($contentType == 'question' || $contentType == 'discussion')){
            $data = $this->anarecommendationlib->forInstitute($listingId,array(),$count,$start,$contentType,$displayData['sort_by']);
        }else if($listingType== 'course' && ($contentType == 'question' || $contentType == 'discussion')){
            $data = $this->anarecommendationlib->forCourse($listingId,array(),$count,$start,$displayData['sort_by']);
        }else if($contentType == 'question' || $contentType == 'discussion'){
            $data = $this->anarecommendationlib->forUniversity($listingId,array(),$count,$start,$contentType,$displayData['sort_by'], $displayData['courseIdsMapping']);
        }else if($contentType == 'unanswered'){
        $data = $this->getUnansweredQuestions($listingId, $listingType, $start, $count, $displayData['courseIdsMapping']);
    }

        $contentIdsArray = $data['topContent'];

        if(!empty($contentIdsArray)){
        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            $tagsmodel = $this->load->model('v1/tagsmodel');
            $contentData = $tagsmodel->getContentDetails(implode(',',$contentIdsArray), $displayData['contentType'], 0, 10, $userId);
        if(is_array($contentData) && count($contentData)>0){
                $displayData['data']['homepage'] = $contentData;
        }
            $displayData['totalElements'] = $data['numFound'];
        }else{
            $displayData['totalElements'] = 0;
        }
    }

    function getAllQuestionMISData(&$displayData,$contentType,$listingType)
    {
        $displayData['qtrackingPageKeyId']      = 949;
        switch ($contentType) {
            case 'discussion':
                    $displayData['GA_Tap_On_ViewMore_Com']  = 'VIEWMORE_COMMENT';
                    $displayData['GA_Tap_On_Com_CTA']       = 'WRITECOMMENT_DISC';
                    $displayData['GA_Tap_On_Comment']       = 'VIEW_ALL_COMMENT_DISC';
                    $displayData['GA_Tap_On_Follow_Disc']   = 'FOLLOW_DISC';
                    $displayData['GA_Tap_On_Share_Disc']    = 'SHARE_DISC';
                    $displayData['GA_Tap_On_Owner_Com']     = 'PROFILE_COMMENT';
                    $displayData['GA_Tap_On_Tag_Disc']      = 'TAG_DISC';
                    $displayData['GA_Tap_On_Disc']          = 'DISCTITLE_DISC';
                    $displayData['GA_Tap_On_Upovte_Com']    = 'UPVOTE_COMMENT';
                    $displayData['GA_Tap_On_Downvote_Com']  = 'DOWNVOTE_COMMENT';
                    $displayData['GA_Tap_On_Follow_List_DISC']   = 'FOLLOWERLIST_DISC'; 
                    $displayData['tupdctrackingPageKeyId']  = 1050;
                    $displayData['tdowndctrackingPageKeyId'] = 1051;
                    $displayData['dfollowTrackingPageKeyId'] = 1052;
                    $displayData['flistfollowTrackingPageKeyId'] = 1055;
                    break;
            case 'unanswered':
                    $displayData['GA_Tap_On_Answer_CTA_UN']    = 'WRITEANSWER_UNANQUEST';
                    $displayData['GA_Tap_On_Follow_QUES_UN']   = 'FOLLOW_UNANQUEST';
                    $displayData['GA_Tap_On_Share_Ques_UN']    = 'SHARE_UNANQUEST';
                    $displayData['GA_Tap_On_Tag_Ques_UN']      = 'TAG_UNANQUEST';
                    $displayData['GA_Tap_On_Ques_UN']          = 'QUESTTITLE_UNANQUEST';
                    $displayData['GA_Tap_On_Follow_List_QUES_UN']   = 'FOLLOWERLIST_UNANQUEST';
                    $displayData['qfollowTrackingPageKeyId'] = 1053;
                    $displayData['flistfollowTrackingPageKeyId'] = 1056;
            break;
            
            default:
                $displayData['GA_Tap_On_Answer_CTA']    = 'WRITEANSWER_QUEST';
                    $displayData['GA_Tap_On_Answer']        = 'VIEW_ALL_ANSWERS_QUEST';
                    $displayData['GA_Tap_On_ViewMore_Ans']  = 'VIEWMORE_ANSWER';
                    $displayData['GA_Tap_On_ViewMore_Com']  = 'VIEWMORE_COMMENT';
                    $displayData['GA_Tap_On_Follow_QUES']   =  'FOLLOW_QUEST';
                    $displayData['GA_Tap_On_Share_Ques']    = 'SHARE_QUEST';
                    $displayData['GA_Tap_On_Owner_Ans']     = 'PROFILE_ANSWER';
                    $displayData['GA_Tap_On_Tag_Ques']      = 'TAG_QUEST';
                    $displayData['GA_Tap_On_Ques']          = 'QUESTTITLE_QUEST';
                    $displayData['GA_Tap_On_Upovte_Ans']    = 'UPVOTE_ANSWER';
                    $displayData['GA_Tap_On_Downvote_Ans']  = 'DOWNVOTE_ANSWER';
                    $displayData['GA_Tap_On_Com_CTA']       = 'WRITECOMMENT_DISC';
                    $displayData['GA_Tap_On_Comment']       = 'VIEW_ALL_COMMENT_DISC';
                    $displayData['GA_Tap_On_Follow_Disc']   = 'FOLLOW_DISC';
                    $displayData['GA_Tap_On_Share_Disc']    = 'SHARE_DISC';
                    $displayData['GA_Tap_On_Owner_Com']     = 'PROFILE_COMMENT';
                    $displayData['GA_Tap_On_Tag_Disc']      = 'TAG_DISC';
                    $displayData['GA_Tap_On_Disc']          = 'DISCTITLE_DISC';
                    $displayData['GA_Tap_On_Upovte_Com']    = 'UPVOTE_COMMENT';
                    $displayData['GA_Tap_On_Downvote_Com']  = 'DOWNVOTE_COMMENT';

                    $displayData['GA_Tap_On_Follow_List_QUES_UN']   = 'FOLLOWERLIST_UNANQUEST';
                    
                    $displayData['GA_Tap_On_Follow_List_QUES']   = 'FOLLOWERLIST_QUEST';
                    $displayData['GA_Tap_On_Follow_List_DISC']   = 'FOLLOWERLIST_DISC'; 

                    $displayData['tupdctrackingPageKeyId']  = 967;
                    $displayData['tdowndctrackingPageKeyId'] = 968;
                    $displayData['tupatrackingPageKeyId']   = 961;
                    $displayData['tdownatrackingPageKeyId'] = 962;
                    $displayData['qfollowTrackingPageKeyId'] = 963;
                    $displayData['dfollowTrackingPageKeyId'] = 966;
                    $displayData['flistfollowTrackingPageKeyId'] = 1054;          
                break;

        }
    }

    function getAllQuestionSeoData(&$displayData){
        $institutehtmlName=special_chars_replace(htmlentities($displayData['instituteNameWithLocation']));
        $displayData['seoTitle']                  = "Questions Asked about ".$institutehtmlName." - Shiksha";
        $displayData['seoDesc']                   = "See ".special_chars_replace(htmlentities($displayData['totalElements']))." questions for ".$institutehtmlName." answered by students and experts. Find out about ".$institutehtmlName." eligibility, important dates, fees and much more on Shiksha.com";
        $displayData['sectionText']               = "Questions";

        if($displayData['pageNumber'] > 1){
            $displayData['seoTitle'] = "Page ".$displayData['pageNumber']." - ".$displayData['seoTitle'];
            $displayData['seoDesc']  = "Page ".$displayData['pageNumber']." - ".$displayData['seoDesc'];
        }
    }

    function getAllQuestionSeoDataNew(&$displayData){
        $institutehtmlName=special_chars_replace(htmlentities($displayData['instituteNameWithLocation']));
        $secondaryName = $displayData['instituteObj']->getSecondaryName();
        if($secondaryName != ''){
            $secondaryName = "(".$secondaryName.")";
        }
        $displayData['seoTitle']                  = $institutehtmlName." Q&A on Cutoffs, Placements, Fees & Admission";
        $displayData['seoDesc']                   = "See ".special_chars_replace(htmlentities($displayData['totalElements']))." Answered Questions on cutoffs, placements, courses, fees, admission, ranking & eligibility for ".$institutehtmlName." ".$secondaryName." answered by students and experts.";
        $displayData['sectionText']               = "Questions";

        if($displayData['pageNumber'] > 1){
            $displayData['seoTitle'] = "Page ".$displayData['pageNumber']." - ".$displayData['seoTitle'];
            $displayData['seoDesc']  = "Page ".$displayData['pageNumber']." - ".$displayData['seoDesc'];
        }
    }
    function getAllReviewData($instituteId, $instituteType, $start, $count, &$displayData, $selectedFilterRating = 0,$selectedTagId = 0){
        $this->load->library('ContentRecommendation/ReviewRecommendationLib');
        $this->load->helper(array('messageBoard/ana'));
        $this->load->config('CollegeReviewForm/collegeReviewConfig');
        $displayData['instituteUrl']              = $displayData['instituteObj']->getUrl();
        if($instituteType== 'institute'){
            $data = $this->reviewrecommendationlib->forInstitute($instituteId,array(),$count,$start,$displayData['sort_by'], $selectedFilterRating,$selectedTagId,false);
        }else if($instituteType== 'course'){
            $data = $this->reviewrecommendationlib->forCourse($instituteId,array(),$count,$start,$displayData['sort_by'], null, $selectedFilterRating,$selectedTagId,false);
            if(is_array($data['numFound'])>1){
                $displayData['totalElements'] = 0;
                return;
            }
        }else{
            $data = $this->reviewrecommendationlib->forUniversity($instituteId,array(),$count,$start,$displayData['sort_by'], $displayData['courseIdsMapping'], $selectedFilterRating,$selectedTagId,false);
        }
        $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $placementsTopicTagsIds = $collegeReviewLib->getPlacementTopicTagsForReviews($instituteId,$instituteType,$selectedFilterRating);
        $collegereviewmodel = $this->load->model('CollegeReviewForm/collegereviewmodel');
        $displayData['placementsTopicTagsIds'] = $placementsTopicTagsIds;
        $displayData['placementsTopicTagsName']  = $collegereviewmodel->getReviewTopicTagsName( $placementsTopicTagsIds,'placements');
        //_P($displayData['placementsTopicTags']);die();
       // _P( $placementsTopicTagsIds );die("Desktop");
        $aggregateReviews = $collegeReviewLib->getAggregateReviewsForListing($instituteId, $instituteType);

        // _p($aggregateReviews); die; 
        $displayData['aggregateReviewsData'] = $aggregateReviews[$instituteId];
        $displayData['intervalsDisplayOrder'] = $this->config->item("intervalsDisplayOrder");
        $displayData['aggregateRatingDisplayOrder'] = $this->config->item('aggregateRatingDisplayOrder');

        $ratingIdToDisplayNameMapping = array();
        $crMasterMappingToName = $this->config->item('crMasterMappingToName');
        foreach ($crMasterMappingToName as $ratingId => $ratingName) {
            $ratingIdToDisplayNameMapping[$ratingId] = $displayData['aggregateRatingDisplayOrder'][$ratingName];
        }
        $displayData['ratingIdToDisplayNameMapping'] = $ratingIdToDisplayNameMapping;
        $displayData['crMasterMappingToName'] = $crMasterMappingToName;

        $reviewIdsArray = $data['topContent'];


        if(!empty($reviewIdsArray)){
            
            $reviewData = $collegereviewmodel->getReviewsDetails($reviewIdsArray,false);
            $reviewRating = $collegereviewmodel->getRatingMultipleReviews($reviewIdsArray); 
            $reviewReplies = $collegereviewmodel->getRepliesForReviewIds($reviewIdsArray);
            
            if($reviewData && $reviewData['reviews']){
                $reviewData = $reviewData['reviews'];
            }
            if($selectedTagId > 0){
                //_P($reviewIdsArray); die;
                $placementData = $collegereviewmodel->getPlacementDatafromTag($reviewIdsArray,$selectedTagId,'placements');
               foreach ($placementData as $key => $reviewRow) {
                    $reviewData[$reviewRow['review_id']]['placementDescription'] = $reviewRow['highlighted_review'];     
                  //  $reviewData[$reviewRow['review_id']]['reviewDescription'] = '';
                  //  $reviewData[$reviewRow['review_id']]['facultyDescription'] ='';
                   // $reviewData[$reviewRow['review_id']]['infraDescription'] = '';
               }

            }


            foreach ($reviewData as $key => $reviewRow) {
                

                $courseIds[] = $reviewRow['courseId'];
            }
            
            $reviewRepliesData = array();
            foreach ($reviewReplies as $reply) {
                $reviewRepliesData[$reply['reviewId']] = $reply['replyTxt'];
            }
            $displayData['reviewReplies'] = $reviewRepliesData;


            $this->load->builder('nationalCourse/CourseBuilder');
            $builder = new CourseBuilder();
            $courseRepository = $builder->getCourseRepository();

            if(!empty($courseIds)){
                $courseObjs = $courseRepository->findMultiple($courseIds);
            }
            foreach ($courseObjs as $key => $courseObj) {
                $displayData['courseInfo'][$key]['courseName']   = $courseObj->getName();
                $displayData['courseInfo'][$key]['clientId']   = $courseObj->getClientId();
                $institeteName= $courseObj->getInstituteName();
                $offeredByShortName= $courseObj->getOfferedByShortName();
                $offeredByName= $courseObj->getOfferedByName();
                if($displayData['courseInfo'][$key]['courseName']){
                    $courseNameSuffix="";
                    $courseOfferedByShortName = trim($offeredByShortName);
                    $courseOfferedByName = trim($offeredByName);
                    $courseInstituteFullName=trim($institeteName);
                    if($listingType=="university"){
                        if($courseOfferedByShortName!=""){
                            if($courseOfferedByShortName!=$displayData['listingName']){
                                $courseNameSuffix=$courseOfferedByShortName;
                            }  
                        }
                        elseif($courseOfferedByName!=""){
                            if($courseOfferedByName!=$displayData['listingName']){
                                $courseNameSuffix=$courseOfferedByName;
                            }
                        }
                        elseif ($courseInstituteFullName!="") {
                            if($courseInstituteFullName!=$displayData['listingName']){
                                $courseNameSuffix=$courseInstituteFullName;
                            }
                        }
                        if($courseNameSuffix!=""){
                            $courseNameSuffix=", ".htmlentities($courseNameSuffix);
                        }
                        else{
                            $courseNameSuffix="";
                        }
                    }
                }   
                $displayData['courseInfo'][$key]['courseNameSuffix'] = $courseNameSuffix;
            }
            foreach($reviewIdsArray as $reviewIds){
                if($reviewData[$reviewIds]["status"] == "published"){
                    $finalResult[] = $reviewData[$reviewIds];
                }
                else if ($reviewData[$reviewIds]["status"] == "unverified") {
                    $unverifiedReviews[] = $reviewData[$reviewIds];
                }
            }

            $displayData['reviewsData'] = $finalResult;
            $displayData['unverifiedReviewsData'] = $unverifiedReviews;
            $displayData['reviewRating'] = $reviewRating;
            $displayData['reviewReplies'] = $reviewRepliesData;
            $displayData['ratingDisplayOrder'] =  array('Placements','Infrastructure','Faculty & Course Curriculum','Crowd & Campus Life','Value for Money');
            //Get User Session Data
            $displayData['validateuser'] = $this->userStatus;
            $displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            $displayData['sessionId'] = sessionId();
            $userSessionData = $collegereviewmodel->getUserSessionData($displayData['userId'], $displayData['sessionId']);
            if(is_array($userSessionData)){
                $displayData['userSessionData'] = $userSessionData;
            }
        }
        $displayData['totalElements'] = $data['numFound'];
        $displayData['allReviewsCount'] = $data['totalNumFound'];        
    }
    function getAllReviewsSeoDetails(&$displayData)
    {   
        $institutehtmlName=special_chars_replace(htmlentities($displayData['instituteNameWithLocation']));
        $displayData['seoTitle'] = htmlentities(str_replace(", "," ",$displayData['instituteNameWithLocation']))." Reviews - Shiksha";
        $displayData['seoDesc']                   = "Read ".special_chars_replace(htmlentities($displayData['totalElements']))." latest reviews of ".$displayData['instituteNameWithLocation']." from current students and alums. You'll get all verified and genuine reviews of ".$displayData['instituteNameWithLocation']." based on courses and placement at Shiksha.com.";
        $displayData['sectionText']               = "Reviews";

        if($displayData['pageNumber'] > 1){
            $displayData['seoTitle'] = "Page ".$displayData['pageNumber']." - ".$displayData['seoTitle'];
            $displayData['seoDesc']  = "Page ".$displayData['pageNumber']." - ".$displayData['seoDesc'];
        }
    }
    function getAllReviewsSeoDetailsNew(&$displayData)
    {   
        $institutehtmlName=special_chars_replace(htmlentities($displayData['instituteNameWithLocation']));
        $secondaryName = $displayData['instituteObj']->getSecondaryName();
        if($secondaryName != ''){
            $secondaryName = "(".$secondaryName.")";
        }
        $displayData['seoTitle'] = htmlentities(str_replace(", "," ",$displayData['instituteNameWithLocation']))." Reviews on Placements, Faculty and Facilities";
        $displayData['seoDesc']                   = "Read ".special_chars_replace(htmlentities($displayData['totalElements']))." Reviews on Placements, Faculty, Facilities & Infrastructure of ".$displayData['instituteNameWithLocation']." ".$secondaryName." given by students and alumni across courses and streams.";
        $displayData['sectionText']               = "Reviews";

        if($displayData['pageNumber'] > 1){
            $displayData['seoTitle'] = "Page ".$displayData['pageNumber']." - ".$displayData['seoTitle'];
            $displayData['seoDesc']  = "Page ".$displayData['pageNumber']." - ".$displayData['seoDesc'];
        }
    }

    function getScholarshipData($instituteObj,&$displayData){
        $displayData['scholarships'] = $instituteObj->getScholarships();
        $displayData['anaWidget'] = modules::run('nationalInstitute/InstituteDetailPage/getAnAWidget',$instituteObj->getId(),$instituteObj->getType(),$displayData['courseIdsMapping'], array('getCount' => 1));
    }
    function getScholarshipSeoDetails(&$displayData){
        $displayData['sectionText'] = "Scholarships";
        $this->instituteDetailLib = $this->load->library("nationalInstitute/InstituteDetailLib");
        $seoMetaData = $this->instituteDetailLib->getSeoDataForScholarshipPage($displayData['instituteObj']);
        $displayData['seoTitle'] = $seoMetaData['seoTitle'];
        $displayData['seoDesc']  = $seoMetaData['seoDesc'];
    }

    function getAdmissionPageData($listingId, $listingType, $start, $count, &$displayData){

        $this->courseDetailLib = $this->load->library('nationalCourse/CourseDetailLib');
        $this->load->config("nationalCourse/courseConfig");
        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();   

        // get streams and courses data
        $displayData['coursesData'] = $this->intitutedetaillib->getAdmissionPageCoursesData($displayData['instituteObj'], $displayData['selectedStreamId'], $displayData['selectedCourseId'], $displayData['courseIdsMapping']);

        if($displayData['coursesData']['mostPopularCourse']){
            $course_id = $displayData['coursesData']['mostPopularCourse'];
            $courseObj = $this->courseRepo->find($course_id,'full');

            $selectedCategory = $this->input->cookie('selectedCategory', TRUE);
            $selectedCategory = $selectedCategory ? $selectedCategory : 'general';
            $eligibilityData             = $this->courseDetailLib->getCourseEligibilityDataWithCache($course_id,$selectedCategory);
            /*_P($eligibilityData); die;*/
            $displayData['eligibility'] = $eligibilityData;


            $categoriesConfig              = $this->config->item('categories');
            $categoriesNameMapping         = array_merge($categoriesConfig['default'], $categoriesConfig['addmore']);
            $displayData['categoriesNameMapping'] = $categoriesNameMapping;
            $displayData['courseListingUrl'] = $courseObj->getURL();
            $displayData['onlineFormData']   = $this->getOnlineFormInfoForCourse($course_id);
            $displayData['predictorData']    = $this->courseDetailLib->getPredictorInfo($course_id);

            $displayData['admissions'] = $this->courseDetailLib->getAdmissionsData($course_id);
            $this->courseDetailLib->getImportantDates($courseObj, $displayData);

            $displayData['selectedCourseId'] = empty($displayData['selectedCourseId'])? $displayData['coursesData']['mostPopularCourse'] : $displayData['selectedCourseId'];
            $displayData['selectedStreamId'] = empty($displayData['selectedStreamId'])? $displayData['coursesData']['mostPopularStream'] : $displayData['selectedStreamId'];
        }
    }

    function getAdmissionPageSeoDetails(&$displayData){
        if(!empty($displayData['instituteAbbrev']) && $displayData['instituteAbbrev']!=''){
            $mainLocation = $displayData['instituteObj']->getMainLocation();
            $locationText = getInstituteNameWithCityLocality($displayData['instituteAbbrev'], $displayData['instituteObj']->getType(), $mainLocation->getCityName(), $mainLocation->getLocalityName());
            $displayData['instituteNameWithLocation'] = $locationText['instituteString'];
        }
        $currentYear = date('Y');
        $currentMonth = date('m');
        if($currentMonth > 8 ){
            $currentYear = $currentYear + 1;
        }
        $displayData['totalCountSubtext'] = " Admission ".$currentYear;
        $institutehtmlName=special_chars_replace(htmlentities($displayData['instituteNameWithLocation']));
        $displayData['seoTitle']                  = $institutehtmlName." Admission ".$currentYear.'  - Application Process, Eligibility &  Dates';
        $displayData['seoDesc']                   = "See ".$institutehtmlName." ".$currentYear." admission process for admission to ".special_chars_replace(htmlentities(count($displayData['courseIdsMapping']['courseIds'])))." courses. Find out about eligibility, application form, how to apply, exams required, important dates, fees and much more on Shiksha.com";
        $displayData['sectionText']               = "Admission";

        if($displayData['pageNumber'] > 1){
            $displayData['seoTitle'] = "Page ".$displayData['pageNumber']." - ".$displayData['seoTitle'];
            $displayData['seoDesc']  = "Page ".$displayData['pageNumber']." - ".$displayData['seoDesc'];
        }
    }
    function getAdmissionPageSeoDetailsNew(&$displayData){
        $currentYear = date('Y');
        $currentMonth = date('m');
        if($currentMonth > 10 ){
            $currentYear = $currentYear + 1;
        }

        $secondaryName = $displayData['instituteObj']->getSecondaryName();
        $displayData['totalCountSubtext'] = " Admission ".$currentYear." - Cutoffs, Eligibility & Dates";
        $displayData['sectionText']               = "Admission";
        $institutehtmlName=special_chars_replace(htmlentities($displayData['instituteNameWithLocation']));
        $displayData['seoTitle'] =$institutehtmlName." Admission ".$currentYear." - Cutoffs, Eligibility & Dates";
        $displayData['seoDesc'] = "See Admission Process, Cutoffs, Eligibility, Selection Criteria & Dates at ". $institutehtmlName;
        if(!empty($secondaryName)){
           $displayData['seoDesc']=$displayData['seoDesc']. "(".$secondaryName.")";
        }
    }
    /**
    * Function is used for preparing filters for all content page
    * @param [type:Integer] $listingId [InstituteId/Unviersity Id]
    * @param [type:String] $listingType [Institute/University]
    * @param [type:String] $pageType [articles/questions/reviews]
    * @param [type:String] $contentType [question/discussion/unanswered tab all questions page]
    */

    function prepareFilters($listingId,$listingType,$pageType,$contentType,$displayData)
    {
        //$this->load->library("nationalInstitute/InstituteDetailLib");
        if(empty($listingId) || empty($listingType))
            return;
        $courseIds = array();
        $instituteIds = array();
        $contentType = !empty($contentType)?$contentType:'question';
        switch ($pageType) {
            case 'questions':
                $this->load->library('ContentRecommendation/AnARecommendationLib');
                if($listingType == 'university')
                {
                    if(in_array($contentType, array('question','discussion')))
                    {
                        $data =  $this->anarecommendationlib->getFiltersForUniversity($listingId,$contentType, $displayData['courseIdsMapping']);    
                    }
                    else if($contentType == 'unanswered'){
                     $data =  $this->anarecommendationlib->getFiltersForUniversity_Unanswered($listingId, $displayData['courseIdsMapping']);
                    }
                    $courseIds = $data['courseIds'];
                }
                else
                {
                    if($contentType == 'question')
                    {
                        $data = $this->anarecommendationlib->getFiltersForInstitute($listingId, $displayData['courseIdsMapping']);
                    }
                    else if($contentType == 'unanswered')
                    {
                        $data = $this->anarecommendationlib->getFiltersForInstitute_Unanswered($listingId, $displayData['courseIdsMapping']);
                    }
                    
                    $courseIds = $data;
                }
                break;
            case 'articles':
                if($listingType == 'university')
                {
                    $this->load->library('ContentRecommendation/ArticleRecommendationLib');
                    $data = $this->articlerecommendationlib->getFiltersForUniversity($listingId, $displayData['courseIdsMapping']);    
                    $instituteIds = $data;
                }
                break;
            case 'reviews':
                $this->load->library('ContentRecommendation/ReviewRecommendationLib');
                if($listingType == 'university')
                    {
                        $data = $this->reviewrecommendationlib ->getFiltersForUniversity($listingId, $displayData['courseIdsMapping'],false);
                        $courseIds = $data['courseIds'];
                    }
                    else
                    {
                        $data = $this->reviewrecommendationlib->getFiltersForInstitute($listingId,false);
                        $courseIds = $data;
                    }
                break;
            default:
                # code...
                break;
        }
        /*$InstituteDetailLib = new InstituteDetailLib();
        $data = $InstituteDetailLib->getInstituteCourseIds(50305,'university');//50305,'university'*/
    
        if($pageType != 'articles')
        {
            $instituteCoursesMapping = $data['instituteWiseCourses'];
            foreach ($data['instituteWiseCourses'] as $instituteKey => $instituteCourses) {
                $instituteIds[] = $instituteKey;
                foreach ($instituteCourses as $courseKey => $courseValue) {
                    $courseIds[]   = $courseValue;
                }   
            }
        }
        $courseIds = array_unique($courseIds);
        $filterCourses = array();
        $filterInstitutes = array();
        $baseCourseMapping = array();
        if(!empty($courseIds) && count($courseIds) >0)
        {
            $this->load->builder("nationalCourse/CourseBuilder");
            $courseBuilder = new CourseBuilder();
            $this->courseRepo = $courseBuilder->getCourseRepository();
            $coursesInfo = $this->courseRepo->findMultiple($courseIds);
            foreach ($coursesInfo as $courseKey => $courseValue) {
                $courseObject = $coursesInfo[$courseKey];
                $courseId = $courseObject->getId();
                $baseCourseId = $courseObject->getBaseCourse();
                $courseName = htmlentities($courseObject->getName());
                if($listingType == 'university')
                {
                    $offeredByName = htmlentities($courseObject->getOfferedByShortName());
                    if(empty($offeredByName))
                    {
                        $offeredByName = htmlentities($courseObject->getOfferedByName());    
                    }
                    if(!empty($offeredByName))
                    {
                        $courseName .= ', '.$offeredByName;
                    }
                }
                if(!empty($courseId))
                    $filterCourses[$courseId] = special_chars_replace($courseName);
                if(!empty($baseCourseId) && !empty($courseId))
                    $baseCourseMapping[$courseId] = $baseCourseId['entry'];
            }
        }
        if(!empty($instituteIds) && count($instituteIds) > 0 )
        {
            $this->load->builder("nationalInstitute/InstituteBuilder");
            $instituteBuilder = new InstituteBuilder();
            $this->instituteRepo = $instituteBuilder->getInstituteRepository();       
            $instituteInfo = $this->instituteRepo->findMultiple($instituteIds);
            foreach ($instituteInfo as $instituteKey => $instituteValue) {
                $instituteObject = $instituteInfo[$instituteKey];
                $instituteId   = $instituteObject->getId();
                
                $instituteName = htmlentities($instituteObject->getShortName());
;                if(empty($instituteName))
                    $instituteName = htmlentities($instituteObject->getName());
                if(!empty($instituteId))
                    $filterInstitutes[$instituteId] = special_chars_replace($instituteName);
            }
        }
         function ArrayValueSort($a,$b)
         {
            if ($a ==$b ) return 0;
              return (strtolower($a)<strtolower($b))?-1:1;
         }
         uasort($filterCourses, 'ArrayValueSort');
         uasort($filterInstitutes,'ArrayValueSort');
         $filtersArray['filterCourses'] = $filterCourses;
         $filtersArray['filterInstitutes'] = $filterInstitutes;
         $filtersArray['instituteCoursesMapping'] = json_encode($instituteCoursesMapping);
         $filtersArray['baseCourseMapping'] = json_encode($baseCourseMapping);
         return $filtersArray;
    }

    function getRelatedLinks($listingId, $listingType, $pageType, $prefetchedCourseIds = array()){
        if($listingId <= 0){
                return;
        }
        $this->_init();
        if($listingType != 'course'){
            $instituteObj  = $this->instituteRepo->find($listingId,array('scholarship','childPageExists'));
            $listingName = ($instituteObj->getShortName()!='')?$instituteObj->getShortName():$instituteObj->getName();
            $displayData['listingName'] = $listingName;
            $courseIdArray = $this->instituteRepo->getCoursesListForInstitutes(array($listingId));
            if(!empty($courseIdArray)){
                if(count($courseIdArray[$listingId]) > 0){
                    $coursesAvailable = true;

                    // used in course count
                    if(empty($prefetchedCourseIds)){
                        $this->load->library('nationalInstitute/InstituteDetailLib');
                        $prefetchedCourseIds = $this->institutedetaillib->getallCoursesForInstitutes($listingId);
                    }
                }
            }
        }
        
        switch ($pageType){
            case 'articles':
                //Fetch if Questions exists on this Listing
                $questionData = $this->checkIfQuestionsExists($listingId, $listingType, $prefetchedCourseIds);
                $displayData['questionURL'] = $questionData['questionURL'];
                $displayData['questionCount'] = $questionData['questionCount'];

                //Fetch if Reviews exists on this listing
                $reviewData = $this->checkIfReviewsExists($listingId, $listingType, $prefetchedCourseIds);
                $displayData['reviewURL'] = $reviewData['reviewURL'];
                $displayData['reviewCount'] = $reviewData['reviewCount'];
                
                // scholarships
                $displayData['scholarshipURL'] = "";
                if(count($instituteObj->getScholarships())>0){
                    $displayData['scholarshipURL'] = $instituteObj->getAllContentPageUrl('scholarships');
                }
                break;
            case 'questions':
                //Fetch if Articles exists on this Listing
                $articleData = $this->checkIfArticlesExists($listingId, $listingType, $prefetchedCourseIds);
                $displayData['articleURL'] = $articleData['articleURL'];
                $displayData['articleCount'] = $articleData['articleCount'];

                //Fetch if Reviews exists on this listing
                $reviewData = $this->checkIfReviewsExists($listingId, $listingType, $prefetchedCourseIds);
                $displayData['reviewURL'] = $reviewData['reviewURL'];
                $displayData['reviewCount'] = $reviewData['reviewCount'];

                // scholarships
                $displayData['scholarshipURL'] = "";
                if(count($instituteObj->getScholarships())>0){
                    $displayData['scholarshipURL'] = $instituteObj->getAllContentPageUrl('scholarships');
                }
                break;
            case 'reviews':
                //Fetch if Questions exists on this Listing
                $questionData = $this->checkIfQuestionsExists($listingId, $listingType, $prefetchedCourseIds);
                $displayData['questionURL'] = $questionData['questionURL'];
                $displayData['questionCount'] = $questionData['questionCount'];

                //Fetch if Articles exists on this Listing
                $articleData = $this->checkIfArticlesExists($listingId, $listingType, $prefetchedCourseIds);
                $displayData['articleURL'] = $articleData['articleURL'];
                $displayData['articleCount'] = $articleData['articleCount'];

                // scholarships
                $displayData['scholarshipURL'] = "";
                if(count($instituteObj->getScholarships())>0){
                    $displayData['scholarshipURL'] = $instituteObj->getAllContentPageUrl('scholarships');
                }
                break;
            case 'admission':
                //Fetch if Questions exists on this Listing
                $questionData = $this->checkIfQuestionsExists($listingId, $listingType, $prefetchedCourseIds);
                $displayData['questionURL'] = $questionData['questionURL'];
                $displayData['questionCount'] = $questionData['questionCount'];

                //Fetch if Articles exists on this Listing
                $articleData = $this->checkIfArticlesExists($listingId, $listingType, $prefetchedCourseIds);
                $displayData['articleURL'] = $articleData['articleURL'];
                $displayData['articleCount'] = $articleData['articleCount'];

                //Fetch if Reviews exists on this listing
                $reviewData = $this->checkIfReviewsExists($listingId, $listingType, $prefetchedCourseIds);
                $displayData['reviewURL'] = $reviewData['reviewURL'];
                $displayData['reviewCount'] = $reviewData['reviewCount'];

                // scholarships
                $displayData['scholarshipURL'] = "";
                if(count($instituteObj->getScholarships())>0){
                    $displayData['scholarshipURL'] = $instituteObj->getAllContentPageUrl('scholarships');
                }
                break;
            case 'scholarships':
                //Fetch if Questions exists on this Listing
                $questionData = $this->checkIfQuestionsExists($listingId, $listingType, $prefetchedCourseIds);
                $displayData['questionURL'] = $questionData['questionURL'];
                $displayData['questionCount'] = $questionData['questionCount'];

                //Fetch if Articles exists on this Listing
                $articleData = $this->checkIfArticlesExists($listingId, $listingType, $prefetchedCourseIds);
                $displayData['articleURL'] = $articleData['articleURL'];
                $displayData['articleCount'] = $articleData['articleCount'];

                //Fetch if Reviews exists on this listing
                $reviewData = $this->checkIfReviewsExists($listingId, $listingType, $prefetchedCourseIds);
                $displayData['reviewURL'] = $reviewData['reviewURL'];
                $displayData['reviewCount'] = $reviewData['reviewCount'];
                break;
        }

        //Check if Courses exists on this Institute/University
        if($coursesAvailable){
            $displayData['courseURL'] = getSeoUrl($listingId,'all_content_pages','',array('typeOfPage'=>'courses','typeOfListing'=>$listingType));
            $displayData['courseCount'] = count($prefetchedCourseIds['courseIds']);
        }

        //Check if Admission URL exists on this University
        if($pageType!='admission'){
            if($instituteObj->isAdmissionDetailsAvailable()){
                $displayData['admissionURL'] = getSeoUrl($listingId,'all_content_pages','',array('typeOfPage'=>'admission','typeOfListing'=>$listingType));;
            }
        }

        //Check if PlacementPage Exists 
        if($instituteObj->isPlacementPageExists()){
            $displayData['placementURL'] = $instituteObj->getAllContentPageUrl('placement');
        }

        $this->load->config('nationalInstitute/CollegeCutoffConfig');
        $parentListingIds = $this->config->item('parentListingIds');
        if($parentListingIds[$instituteObj->getId()]){
            $displayData['cutoffURL'] = getSeoUrl($listingId,'all_content_pages','',array('typeOfPage'=>'cutoff','typeOfListing'=>$listingType));;
        }

        $displayData['listingType'] = $listingType;

        $this->load->view("AllContentPage/widgets/relatedLinks",$displayData);
    }

    function checkIfQuestionsExists($listingId, $listingType, $preFetchedCourseIds=array()){
        $questionURL = '';
        $questionCount = 0;
        $this->load->library('ContentRecommendation/AnARecommendationLib');

        $courseIdsMapping = array($listingId => $preFetchedCourseIds);
        switch ($listingType){
            case 'institute': 
            case 'university': $quesCheck = $this->anarecommendationlib->getInstituteAnaCounts(array($listingId),'question',$courseIdsMapping); break;
            // case 'course': $quesCheck = $this->anarecommendationlib->checkContentExistForCourse(array($listingId)); break;
        }
        if(isset($quesCheck[$listingId]) && $quesCheck[$listingId]>0){
            //If question exists, fetch the listing name, and create URL and text
            $questionURL = getSeoUrl($listingId,'all_content_pages','',array('typeOfPage'=>'questions','typeOfListing'=>$listingType));
            $questionCount = $quesCheck[$listingId];
        }
        return array('questionURL'=>$questionURL,'questionCount'=>$questionCount);
    }

    function checkIfReviewsExists($listingId, $listingType, $prefetchedCourseIds = array()){
        $reviewURL = '';
        $reviewCount = 0;
        $this->load->library('ContentRecommendation/ReviewRecommendationLib');

        $courseIdsMapping = array($listingId => $prefetchedCourseIds);
        switch ($listingType){
            case 'institute': 
            case 'university': $reviewCheck = $this->reviewrecommendationlib->getInstituteReviewCounts(array($listingId), $courseIdsMapping); break;
            // case 'course': $reviewCheck = $this->reviewrecommendationlib->checkContentExistForCourse(array($listingId)); break;
        }
        if(isset($reviewCheck[$listingId]) && $reviewCheck[$listingId]>0){
            //If reviews exists, fetch the listing name, and create URL and text
            $reviewURL = getSeoUrl($listingId,'all_content_pages','',array('typeOfPage'=>'reviews','typeOfListing'=>$listingType));
            $reviewCount = $reviewCheck[$listingId];
        }
        return array('reviewURL'=>$reviewURL,'reviewCount'=>$reviewCount);
    }

    function checkIfArticlesExists($listingId, $listingType, $prefetchedCourseIds = array()){
        $articleURL = '';
        $articleCount = '';
        $this->load->library('ContentRecommendation/ArticleRecommendationLib');

        $courseIdsMapping = array($listingId => $prefetchedCourseIds);
        switch ($listingType){
            case 'institute': 
            case 'university': $articleCheck = $this->articlerecommendationlib->getInstituteArticleCounts(array($listingId), $courseIdsMapping); break;
            // case 'course': $articleCheck = $this->articlerecommendationlib->checkContentExistForCourse(array($listingId)); break;
        }
        if(isset($articleCheck[$listingId]) && $articleCheck[$listingId]>0){
            //If articles exists, fetch the listing name, and create URL and text
            $articleURL = getSeoUrl($listingId,'all_content_pages','',array('typeOfPage'=>'articles','typeOfListing'=>$listingType));
            $articleCount = $articleCheck[$listingId];
        }
        return array('articleURL'=>$articleURL,'articleCount'=>$articleCount);
    }
    
    function getRecommendations($listingId, $listingType, $pageType, $prefetchedCourseIds = array()){
        //Fetch the Also viewed institutes
        if($listingId <= 0){
                return;
        }
        $this->_init();

        $courseIdsMapping = array($listingId => $prefetchedCourseIds);
        $this->load->library('recommendation/alsoviewed');
        if($listingType == 'institute'){
            $alsoViewedListing = $this->alsoviewed->getAlsoViewedInstitutes(array($listingId),'16', array(), $courseIdsMapping);
        }else if($listingType == 'university'){
            $alsoViewedListing = $this->alsoviewed->getAlsoViewedUniversities(array($listingId),'16', array(), $courseIdsMapping);
        }

        if(!empty($alsoViewedListing)){

            $finalArray = array();
            $instituteObj = $this->instituteRepo->findMultiple($alsoViewedListing);

            //Now, for these institutes, check if Content exists.
            switch ($pageType){
                case 'questions':
                    $this->load->library('ContentRecommendation/AnARecommendationLib');
                    switch ($listingType){
                        case 'institute': 
                        case 'university': $quesCheck = $this->anarecommendationlib->getInstituteAnaCounts($alsoViewedListing, 'question', $courseIdsMapping); break; 
                        case 'course': $quesCheck = $this->anarecommendationlib->checkContentExistForCourse($alsoViewedListing); 
                            $quesCheck=array_fill_keys($quesCheck, 1);
                            break;
                    }
                    foreach ($quesCheck as $listingIdForQues=>$questionCount){
                        if(isset($instituteObj[$listingIdForQues]) && $questionCount>0){
                            $questionURL = getSeoUrl($listingIdForQues,'all_content_pages','',array('typeOfPage'=>'questions','typeOfListing'=>$listingType));
                            $listingName = ($instituteObj[$listingIdForQues]->getShortName()!='')?$instituteObj[$listingIdForQues]->getShortName():$instituteObj[$listingIdForQues]->getName();
                            $finalArray[] = array('listingName'=>$listingName,'URL'=>$questionURL,'contentCount'=>$questionCount);
                        }
                    }
                    break;
                case 'articles':
                    $this->load->library('ContentRecommendation/ArticleRecommendationLib');
                    switch ($listingType){
                        case 'institute': 
                        case 'university': $articleCheck = $this->articlerecommendationlib->getInstituteArticleCounts($alsoViewedListing);break;
                        case 'course': $articleCheck = $this->articlerecommendationlib->checkContentExistForCourse($alsoViewedListing);
                            $articleCheck=array_fill_keys($articleCheck, 1);
                            break;
                    }
                    foreach ($articleCheck as $listingIdForArticle=>$articleCount){
                        if(isset($instituteObj[$listingIdForArticle]) && $articleCount>0){
                            $articleURL = getSeoUrl($listingIdForArticle,'all_content_pages','',array('typeOfPage'=>'articles','typeOfListing'=>$listingType));
                             $listingName = ($instituteObj[$listingIdForArticle]->getShortName()!='')?$instituteObj[$listingIdForArticle]->getShortName():$instituteObj[$listingIdForArticle]->getName();
                            $finalArray[] = array('listingName'=>$listingName,'URL'=>$articleURL,'contentCount'=>$articleCount);
                        }
                    } 
                    break;
                case 'reviews':
                    $this->load->library('ContentRecommendation/ReviewRecommendationLib');
                    switch ($listingType){
                        case 'institute': 
                        case 'university': $reviewCheck = $this->reviewrecommendationlib->getInstituteReviewCounts($alsoViewedListing);break;
                        case 'course': $reviewCheck = $this->reviewrecommendationlib->checkContentExistForCourse($alsoViewedListing); 
                            $reviewCheck=array_fill_keys($reviewCheck, 1);
                            break;
                    }
                    foreach ($reviewCheck as $listingIdForReview=>$reviewCount){
                        if(isset($instituteObj[$listingIdForReview]) && $reviewCount>0){
                            $reviewURL = getSeoUrl($listingIdForReview,'all_content_pages','',array('typeOfPage'=>'reviews','typeOfListing'=>$listingType));
                            $listingName = ($instituteObj[$listingIdForReview]->getShortName()!='')?$instituteObj[$listingIdForReview]->getShortName():$instituteObj[$listingIdForReview]->getName();
                            $finalArray[] = array('listingName'=>$listingName,'URL'=>$reviewURL,'contentCount'=>$reviewCount);
                        }
                    } 
                    break;
            }
            $displayData['dataArray'] = $finalArray;
            $displayData['pageType'] = $pageType;
            $this->load->view("AllContentPage/widgets/recommendations",$displayData);
        }

    }
    function updateAllContentPageContent()
    {
        $this->_init();

        $listingId = !empty($_POST['listingId'])?$this->input->post('listingId'):'';

        $listingType = !empty($_POST['listingType'])?$this->input->post('listingType'):'';
        $pageType = !empty($_POST['pageType'])?$this->input->post('pageType'):'';
        $selectedInstituteId = !empty($_POST['selectedInstituteId'])?$this->input->post('selectedInstituteId'):'';
        $selectedFilterRating = !empty($_REQUEST['rating'])? (int) $this->input->post('rating'):'5';
        $selectedTagId = !empty($_REQUEST['TagId'])? (int) $this->input->post('tagId'):0;
        $sortingOption = !empty($_POST['sorting'])?strtoupper($this->input->post('sorting')):'RELEVANCE';
        $selectedCourseId = !empty($_POST['selectedCourseId'])?$this->input->post('selectedCourseId'):'';
        $contentType = !empty($_POST['contentType'])?$this->input->post('contentType'):'';
        $selectedStreamId = !empty($_POST['stream'])?strtoupper($this->input->post('stream')):'';

        $selectedTagId = !empty($_POST['selectedTagId'])?$this->input->post('selectedTagId'):'';
        if( empty($listingId) ||  !is_numeric($listingId)  || (!empty($selectedInstituteId) && !is_numeric($selectedInstituteId)) || (!empty($selectedCourseId) && !is_numeric($selectedCourseId)) || !in_array(strtoupper($sortingOption), array('RELEVANCE','RECENCY','YEAR OF GRADUATION','HIGHEST RATING','LOWEST RATING'))  ){
            show_404();
            exit(0);
        }

        $displayData = array();
        $displayData['listing_type'] = $listingType;

        $sortingMapping = array('RELEVANCE' => 'RELEVANCY', 'YEAR OF GRADUATION' => 'GRADUATION_YEAR' , 'HIGHEST RATING' => 'HIGHEST_RATING' ,'LOWEST RATING' => 'LOWEST_RATING','RECENCY' => 'RECENCY');

        if($pageType =='reviews'){
            $sortingOption = !empty($_POST['sorting'])?strtoupper($this->input->post('sorting')):'YEAR OF GRADUATION';
        }
        else{
            $sortingOption = !empty($_POST['sorting'])?strtoupper($this->input->post('sorting')):'RELEVANCE';
        }

        $displayData['sort_by'] = $sortingMapping[$sortingOption];
        $fetchListingContentType = $listingType;
        $fetchListingContentId = $listingId;

        if(!empty($selectedCourseId))
        {
            $fetchListingContentId = $selectedCourseId;
            $fetchListingContentType = 'course';
        }
        else if(!empty($selectedInstituteId))
        {   
            $fetchListingContentId = $selectedInstituteId;
            $fetchListingContentType = 'institute';
        }

        $instituteObj  = $this->instituteRepo->find($listingId,array('basic'));
        $displayData['instituteObj'] = $instituteObj;
        $displayData['listing_id'] = $instituteObj->getId();
        $base_url = $instituteObj->getURL();
        $displayData['selectedCourseId'] = $selectedCourseId;
        $displayData['selectedStreamId'] = $selectedStreamId;
        $displayData['fetchListingContentType'] = $fetchListingContentType;
        $displayData['instituteName'] = $instituteObj->getName();
        $displayData['selectedTagId']=  $selectedTagId;
        // get all courses mapped to this listing

        $displayData['courseIdsMapping'] = $this->intitutedetaillib->getAllCoursesForInstitutes($listingId);

        $start = 0;

        $data = array();

        switch($pageType)
        {
            case 'articles':
                $paginationPage = 'ALL_ARTICLES_PAGE'; 
                $count = 20;
                $base_url = $base_url.'/articles';
                $this->getAllArticleData($fetchListingContentId,$fetchListingContentType,$start,$count,$displayData);
                $totalCount = $displayData['totalElements'];
                $this->getAllArticleSeoDetailsNew($displayData);
                $html =  $this->load->view("nationalInstitute/AllContentPage/articleDetailsSection",$displayData,true);
                break;
            case 'questions':
                $paginationPage = 'ALL_QUESTIONS_PAGE'; 
                $count = 10;
                $base_url = $base_url.'/questions';
                $displayData['contentType'] = $contentType;
                $this->getAllQuestionData($fetchListingContentId,$fetchListingContentType,$start,$count,$displayData);
                $this->getAllQuestionSeoDataNew($displayData);

                $this->getAllQuestionMISData($displayData,$contentType,$listingType);
                $totalCount = $displayData['totalElements'];
                
                $html = $this->load->view("nationalInstitute/AllContentPage/widgets/anaTuple",$displayData,true);
                break;
            case 'reviews':
                $this->load->config('CollegeReviewForm/collegeReviewConfig');
                $paginationPage = 'ALL_REVIEWS_PAGE'; 
                $count = 15;
                $base_url = $base_url.'/reviews';
                $displayData['sortingOptions'] = array('Recency',"Year of Graduation",'Highest Rating','Lowest Rating','Relevance');
                $this->getAllReviewData($fetchListingContentId,$fetchListingContentType,$start,$count,$displayData, $selectedFilterRating,$selectedTagId);
                $this->getAllReviewsSeoDetailsNew($displayData);
                $displayData['selectedFilterRating'] = $selectedFilterRating;
                $displayData['selectedTagId'] = $selectedTagId;

                $displayData['selectedSortOption'] = strtolower($sortingOption);
                $totalCount = $displayData['allReviewsCount'];
                $displayData['pageType'] = 'reviews';
                $data['reviewFilterHtml'] = $this->load->view('nationalInstitute/AllContentPage/widgets/reviewRatingFilter',$displayData,true);
                // _p($displayData['aggregateReviewsData']);
                // die;
                // _p($reviewFilterHtml); die;
                $html = $this->load->view('nationalInstitute/AllContentPage/reviewDetailsSection',$displayData,true);
                break;
            case 'admission':
                $scanPageName = 'admissionPage';
                $paginationPage = 'ADMISSION_PAGE';
                $base_url = $base_url.'/admission';
                $this->getAdmissionPageData($fetchListingContentId,$fetchListingContentType,$start,$count,$displayData);
                $this->getAdmissionPageSeoDetailsNew($displayData);
                $html = $this->load->view('nationalInstitute/AllContentPage/widgets/admissionLowerLeftSection',$displayData,true);
                $data['selectedCourseId'] = $displayData['selectedCourseId'];
                $data['selectedStreamId'] = $displayData['selectedStreamId'];
                
                break;
        }

        
        if(!empty($selectedInstituteId))
        {
            $queryParameters['institute'] = $selectedInstituteId;
        }
        if(!empty($selectedCourseId))
        {
            $queryParameters['course'] = $selectedCourseId;
        }
        if(!empty($selectedTagId)){
            $queryParameters['tagId'] = $selectedTagId;
        }
        if(!empty($sortingOption))
        {
            $queryParameters['sort_by'] = strtolower($sortingOption);
        }
        if(!empty($contentType))
        {
            $queryParameters['type'] = strtolower($contentType);
        }

        $appendStringtoURL = count($queryParameters) > 0 ? ('?'.http_build_query($queryParameters)) : '';
        $currentUrl = $base_url.'-@pagenum@'.$appendStringtoURL;

        
        $pageNumber = 1;

        if($totalCount > 0){
            $totalCountText = "($totalCount)";
        }


        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $GA_userLevel = $userId > 0 ? 'Logged In':'Non-Logged In';

        $data['paginationHtml'] = doPagination_AnA($totalCount,$currentUrl,$pageNumber,$count,$numPages=10,$paginationPage,$GA_userLevel,$listingType);
         /*$totalpages = ceil($totalCount/$count);
        if($totalpages >1){
            $data['nextURL'] = $base_url.'-'.($pageNumber+1);
        }*/

        $data['html'] = $html;
        $data['seoTitle'] = $displayData['seoTitle'];
        $data['totalCountText'] = $totalCountText;
        echo json_encode($data);die;;
    }

    function getUnansweredQuestions($listingId, $listingType, $start, $count, $prefetchedCourseIds= array()){
    $data = array();
    $allcontent = $this->load->model("nationalInstitute/allcontentmodel");
    switch ($listingType){
        case 'course': $data = $allcontent->getCourseUnansweredAnA($listingId, $start, $count);
                   break;   
        case 'institute': $data = $allcontent->getInstituteUnansweredAnA($listingId, $start, $count);
                               break;
        case 'university': 
                $this->load->library('nationalInstitute/InstituteDetailLib');
                if(empty($prefetchedCourseIds))
                    $universityChildren = $this->institutedetaillib->getAllCoursesForInstitutes($listingId);
                else
                    $universityChildren = $prefetchedCourseIds;
                $universityChildrenId = array_keys($universityChildren['type']);
                $data = $allcontent->getInstituteUnansweredAnA($universityChildrenId, $start, $count);
                               break;
    }
    return $data;
    }

    /**
    * @param: $listingId : university id 
    * @return : list of exams directly mapped to university listing with sorted order
    */
    function getExamsMappedToUniversity($listingId)
    {
        $this->_init();
        $examList = $this->institutedetailmodel->getExamsMappedToUniversity($listingId);

        function examSort($a,$b)
                 {
                    if ($a['name']==$b['name']) return 0;
                      return (strtolower($a['name'])<strtolower($b['name']))?-1:1;
                 }

        usort($examList,"examSort");
        return $examList;
    }
    /**
    * get Online form info for courseId
    */
    function getOnlineFormInfoForCourse($courseId)
    {
        $this->load->library('Online/OnlineFormUtilityLib');
        $OnlineFormUtilityLib = new OnlineFormUtilityLib();
        $OnlineFormData = $OnlineFormUtilityLib->getOAFBasicInfo($courseId);
        $OnlineFormData = $OnlineFormData[$courseId];
        $returnArr = array();
        if(!empty($OnlineFormData['of_external_url'])) {
            $returnArr['url'] = $OnlineFormData['of_external_url'];
        }
        else if(!empty($OnlineFormData['of_seo_url'])) {
            $returnArr['url'] = $OnlineFormData['of_external_url'];
        }
        //if online application form date is greater than current date
        if(strtotime(date('Y-m-d')) <= strtotime($OnlineFormData['of_last_date']) ) {
            $returnArr['date'] = convertToFormattedDate($OnlineFormData['of_last_date']);
            //$returnArr['eventText'] = 'Applications close on '.$returnArr['date'];
        }
        return $returnArr;
    }

    function postingReviewReply()
    {
        $this->_init();

        $reviewId = !empty($_POST['reviewId'])? $this->input->post('reviewId') : '';
        $courseId = !empty($_POST['courseId'])? $this->input->post('courseId') : '';
        $clientId = !empty($_POST['clientId'])? $this->input->post('clientId') : '';

        $replyText = !empty($_POST['replyText'])? $this->input->post('replyText') : '';

        if(!empty($reviewId) && !empty($courseId) && !empty($clientId) && !empty($replyText))
        {
            $this->load->helper(array('messageBoard/ana'));
            $collegereviewmodel = $this->load->model('CollegeReviewForm/collegereviewmodel');
            $reviewData = $collegereviewmodel->insertReplyinTable($reviewId,$courseId,$clientId,$replyText);
            if($reviewData)            
            {
              echo json_encode(array('replyText' => nl2br_Shiksha(sanitizeAnAMessageText($replyText,'review')),'error'=>false));die;
            }    
            else{
                echo json_encode(array('error'=>true));die;
            }
                
        }
        else
        {
            echo json_encode(array('error'=>true));die;
        }
        
    }
    
    function getAllCoursesList(&$displayData) {
        //get all courses
        $listingId  = $displayData['listing_id'];
        $courseIdArray[$listingId] = $displayData['courseIdsMapping']['courseIds'];
        if(!empty($courseIdArray)){

            $this->coursedetailmodel = $this->load->model('nationalCourse/Coursedetailmodel');
            $displayData['instituteCourses'] = $this->coursedetailmodel->getCourseName($courseIdArray[$listingId]);
/*
            $instituteCourses = $this->coursedetailmodel->getCourseName($courseIdArray[$listingId]);
            $courseBuilder = new CourseBuilder();
            $this->courseRepo = $courseBuilder->getCourseRepository();
            $coursesInfo = $this->courseRepo->findMultiple($courseIdArray[$listingId], array('basic'), false, false);
            $instituteCourses = array();
            foreach ($coursesInfo as $courseKey => $courseValue){
                $courseId = $courseValue->getId();
                $courseName = $courseValue->getName();
                $instituteName = $courseValue->getOfferedByShortName();
                $instituteName = $instituteName ? $instituteName : $displayData['instituteObj']->getShortName();
                $instituteName = $instituteName ? $instituteName : $displayData['instituteObj']->getName();
                
                if($listingType == 'university'){
                 
                    $courseName .= ", ".$instituteName;
                }
                
                $instituteCourses[] = array('course_id' => $courseId,'course_name' => htmlentities($courseName));

            } 
            //sort course alphabetically
             function course_sort($a,$b)
             {
                if ($a['course_name']==$b['course_name']) return 0;
                  return (strtolower($a['course_name'])<strtolower($b['course_name']))?-1:1;
             }

             usort($instituteCourses,"course_sort");

            $displayData['instituteCourses'] = $instituteCourses;
*/
        }
        // echo "real: ".(memory_get_peak_usage(true)/1024/1024)." MiB\n\n";
         //die;
    }   


    function showContactDetailCTA($instituteObj){
        $location       = $instituteObj->getMainLocation();
        $contactDetails = $location->getContactDetail();

        $admission_contact_number = $contactDetails->getAdmissionContactNumber();
        $generic_contact_number   = $contactDetails->getGenericContactNumber();

        $admission_email          = $contactDetails->getAdmissionEmail();
        $generic_email             = $contactDetails->getGenericEmail();
        
        if(!empty($admission_contact_number) || 
            !empty($generic_contact_number) ||
            !empty($admission_email) || 
            !empty($generic_email)){   
            return true;
        }

        return false;
    }

}
    
