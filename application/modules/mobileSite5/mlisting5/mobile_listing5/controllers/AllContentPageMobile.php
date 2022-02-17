<?php
class AllContentPageMobile extends ShikshaMobileWebSite_Controller{
    
    //initialize data
    private function _init(){
    
        $this->load->helper(array('mcommon5/mobile_html5'));
        if($this->userStatus == ""){
            $this->userStatus = $this->checkUserValidation();
        }
        $this->load->config('mcommon5/mobi_config');
	    $this->load->helper(array('string','image','shikshautility'));
        $this->load->helper('html');
        $this->institutedetailmodel = $this->load->model('nationalInstitute/institutedetailsmodel');
        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();      
        $this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib'); 
        $this->load->helper('listingCommon/listingcommon');
        $this->load->helper(array('shikshautility'));

    }

    /**
     * Method to show all article/reviews/questions page on desktop
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2016-10-25
     * @param  [type]     $listingId   [institute/university id]
     * @param  [type]     $listingType [institute/university]
     * @return [type]                  [displays institute page]
     */
    function getAllContentPage($listingId,$pageType,$pageNumber=1){
    
        $displayData = array();

        if(empty($listingId)){
            show_404();
            exit(0);
        }
        $this->_init();
      
        $instituteObj  = $this->instituteRepo->find($listingId, array('scholarship'));
        $instituteId   = $instituteObj->getId();
        $instituteType = $instituteObj->getType();
        $base_url = $instituteObj->getURL();
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
         
        $this->_checkForCommonRedirections($instituteObj, $instituteId, $instituteType, $pageType, $pageNumber);

        $currentUrl = getCurrentPageURLWithoutQueryParams();
        if(strpos($currentUrl, '/pdf') !== false){
               $displayData['pdf'] = true; 
        }

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
            
        $start                     = isset($_POST['start'])?$this->input->post('start'):$startIndex;
        $count                     = isset($_POST['count'])?$this->input->post('count'):$countIndex;
        $pageNumber                = isset($_POST['pageNumber'])?$this->input->post('pageNumber'):$pageNumber; 

        if($displayData['pdf']){
            $count = 30;
        }

        $userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $displayData['GA_userLevel'] = $userId > 0 ? 'Logged In':'Non-Logged In'; 

        $displayData['pageNumber'] = $pageNumber;


        //getting query parameters
        $selectedInstituteId = !empty($_GET['institute'])?$this->input->get('institute'):'';
        $selectedCourseId = !empty($_GET['course'])?$this->input->get('course'):'';


        if($pageType =='reviews'){
                $sortingOption = !empty($_GET['sort_by'])?strtoupper($this->input->get('sort_by')):'YEAR OF GRADUATION';
                $selectedFilterRating = !empty($_REQUEST['rating'])? (int) $this->input->get('rating'):'';
                if($selectedFilterRating > 5) {
                    $selectedFilterRating = 5;
                }
                $selectedTagId = !empty($_REQUEST['tagId'])? (int) $this->input->get('tagId'):0;
            if($selectedCourseId){
                $this->courseDetailLib = $this->load->library('nationalCourse/CourseDetailLib');
                $courseBuilder = new CourseBuilder();
                $this->courseRepo = $courseBuilder->getCourseRepository();
                
                $courseObj = $this->courseRepo->find($selectedCourseId,'full');
                $location  = $courseObj->getLocations();
                $displayData['multiLocationCourse'] = count($location)>1?1:0;
                            
            }
        }
        else{
                $sortingOption = !empty($_GET['sort_by'])?strtoupper($this->input->get('sort_by')):'RELEVANCE';
        }

        $selectedStreamId = !empty($_GET['stream'])?strtoupper($this->input->get('stream')):'';
	$displayData['contentType'] = !empty($_GET['type'])?$this->input->get('type'):'';

         if($pageType == 'questions' && empty($displayData['contentType']))
        {
            $displayData['contentType'] = 'question';
        }

        if( (!empty($selectedInstituteId) && !is_numeric($selectedInstituteId)) || (!empty($selectedCourseId) && !is_numeric($selectedCourseId)) || !in_array(strtoupper($sortingOption), array('RELEVANCE','RECENCY','YEAR OF GRADUATION','HIGHEST RATING','LOWEST RATING')) || !in_array(strtolower($displayData['contentType']), array('question','discussion','unanswered',''))  ){
            show_404();
            exit(0);
        }

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
        $displayData['fetchListingContentType'] = $fetchListingContentType;
        //default order willl be relevance

        $sortingMapping = array('RELEVANCE' => 'RELEVANCY','RECENCY' => 'RECENCY', 'YEAR OF GRADUATION' => 'GRADUATION_YEAR' , 'HIGHEST RATING' => 'HIGHEST_RATING' ,'LOWEST RATING' => 'LOWEST_RATING');

        $displayData['sort_by'] = $sortingMapping[$sortingOption];
        $displayData['selectedSortOption'] = strtolower($sortingOption);
        $displayData['listing_id'] = $instituteId;
        $displayData['selectedStreamId'] = $selectedStreamId;

        // get all courses mapped to this listing
        $this->intitutedetaillib = $this->load->library("nationalInstitute/InstituteDetailLib");
        $displayData['courseIdsMapping'] = $this->intitutedetaillib->getInstituteCourseIds($instituteId, $instituteType);

        // in case no course is present in institute/university, show 404
        if(empty($displayData['courseIdsMapping']) || empty($displayData['courseIdsMapping']['courseIds'])){
            show_404();
            exit(0);
        }

        $displayData['instituteAbbrev'] = $instituteObj->getAbbreviation();
        //seo related data
        $mainLocation                             = $displayData['instituteObj']->getMainLocation();
        $displayData['instituteNameWithLocation'] = getInstituteNameWithCityLocality($displayData['instituteObj']->getName(), $displayData['instituteObj']->getType(), $mainLocation->getCityName(), $mainLocation->getLocalityName());
        $displayData['locationText']              = $displayData['instituteNameWithLocation'];
        $displayData['instituteNameWithLocation'] = $displayData['instituteNameWithLocation']['instituteString'];
        $displayData['instituteUrl']              = $displayData['instituteObj']->getUrl();

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam =array('instituteObj'=>$instituteObj,'parentPage'=>'DFP_InstituteDetailPage','pageType'=>$instituteType.'_'.$pageType,'cityId'=>$mainLocation->getCityId(),'stateId'=>$mainLocation->getStateId());
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        switch ($pageType) {
                case 'articles':
                    $scanPageName = 'allArticlesPage';
                    $paginationPage = 'ALL_ARTICLES_PAGE_MOB'; 
                     $base_url = $base_url.'/articles';
                     $sortingOptions = array('Relevance','Recency');
                     $this->getAllArticleData($fetchListingContentId,$fetchListingContentType,$start,$count,$displayData);
                     $this->getAllArticleSeoDetailsNew($displayData);
                     break;

                case 'questions':
                     $scanPageName = 'allQuestionsPage';
                     $paginationPage = 'ALL_QUESTIONS_PAGE_MOB';
                     $base_url = $base_url.'/questions';
                     $displayData['contentType'] = 'question';
                     if(isset($_REQUEST['type'])){
                            $contentType = $_REQUEST['type'];
                            $displayData['contentType'] = $contentType;
                     }
                    if($displayData['contentType'] == 'question')
                    {                        
                        $sortingOptions = array('Relevance','Recency');
                    }
                    else
                    {
                        //$displayData['sortingOption'] = '';
                        $displayData['sort_by'] = '';
                        $displayData['selectedSortOption'] = '';
                    }
                    
                    $displayData['websiteTourContentMapping'] = Modules::run('common/WebsiteTour/getContentMapping','cta','mobile');

                     $this->getAllQuestionData($fetchListingContentId,$fetchListingContentType,$start,$count,$displayData);
                     $this->getAllQuestionSeoDataNew($displayData);
                     $this->getAllQuestionMISData($displayData,$contentType);

                     break;

                case 'reviews':
                     $displayData['selectedFilterRating']    = $selectedFilterRating;
                     
                     $displayData['selectedTagId'] = $selectedTagId;

                     $scanPageName = 'allReviewsPage';
                     $paginationPage = 'ALL_REVIEWS_PAGE_MOB';
                     $base_url = $base_url.'/reviews';
                     $displayData['all_review_url'] = getCurrentPageURL();
					 $sortingOptions = array('Recency',"Year of Graduation",'Highest Rating','Lowest Rating','Relevance');
                     $this->getAllReviewData($fetchListingContentId,$fetchListingContentType,$start,$count,$displayData,$selectedFilterRating,$selectedTagId);
                     // _p($displayData['aggregateReviewsData']); die;
                     $this->getAllReviewsSeoDetailsNew($displayData);
                     break;


                case 'admission':
                    $scanPageName = 'admissionPage';
                    $paginationPage = 'ADMISSION_PAGE_MOB';
                    $base_url = $base_url.'/admission';
                     $this->getAdmissionPageData($fetchListingContentId,$fetchListingContentType,$start,$count,$displayData);
                     $this->getAdmissionPageSeoDetailsNew($displayData);
                     $selectedCourseId = $displayData['selectedCourseId'];
                     $selectedStreamId = $displayData['selectedStreamId'];
                     break;
                case 'scholarships':
                    $scanPageName = 'scholarshipPage';
                    $paginationPage = 'SCHOLARSHIP_PAGE_MOB';
                    $base_url = $base_url.'/scholarships';
                    $this->getScholarshipData($instituteObj,$displayData);
                    $this->getScholarshipSeoDetails($displayData);
                    break;

                 default:
                     # code...
                     break;
             }     

	$displayData['m_meta_keywords']            = " ";
	$displayData['isAdmissionDetailsAvailable'] = 0;

        if($pageType == 'admission')
        {
	    $displayData['isAdmissionDetailsAvailable'] = $instituteObj->isAdmissionDetailsAvailable();
        }

        
        //getting Filters for all content page
        $displayData['sortingOptions'] = $sortingOptions;

        $displayData['filtersArray'] = $this->prepareFilters($instituteId,$instituteType,$pageType,$contentType,$displayData);

        $displayData['isShowIcpBanner'] = $displayData['filtersArray']['isShowIcpBanner'];
        $displayData['validateuser'] = $this->userStatus;
        $displayData['pageType'] = $pageType;
        $displayData['listing_type'] = $instituteType;
        $displayData['instituteName'] = $instituteObj->getName();
        
	if($pageType=='reviews'){
            $totalCount = $displayData['allReviewsCount'];
        }else{
            $totalCount = $displayData['totalElements'];    
        }
	$displayData['totalCount'] = $totalCount;

	$displayData['recoWidget'] = modules::run('mobile_listing5/AllContentPageMobile/getRecommendations',$fetchListingContentId,$fetchListingContentType,$pageType, $displayData['courseIdsMapping']);

        if(!empty($selectedInstituteId))
        {
            $queryParameters['institute'] = $selectedInstituteId;
        }
        if(!empty($selectedCourseId))
        {
            $queryParameters['course'] = $selectedCourseId;
        }
        if(!empty($sortingOption))
        {
            $queryParameters['sort_by'] = strtolower($sortingOption);
        }
        if(isset($_REQUEST['type'])){
            $queryParameters['type'] = $_REQUEST['type'];
        }

         if(!empty($selectedStreamId))
        {
            $queryParameters['stream'] = $selectedStreamId;
        }
        if(!empty($selectedFilterRating)) {
            $queryParameters['rating'] = $selectedFilterRating;
        }
        if(!empty($selectedTagId)) {
            $queryParameters['tagId'] = $selectedTagId;
        }
        $displayData['queryParameters'] = $queryParameters;
       
        $appendStringtoURL = count($queryParameters) > 0 ? ('?'.http_build_query($queryParameters)) : '';
        
        $currentUrl = $base_url.'-@pagenum@'.$appendStringtoURL;
         
        $displayData['base_url'] = $base_url;
        $displayData['paginationHTMLForGoogle'] = doPagination_AnA($totalCount,$currentUrl,$pageNumber,$count,$numPages=4,$paginationPage,$displayData['GA_userLevel'],$instituteType);

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
                $displayData['canonicalURL'] = $base_url;
            }else if($pageNumber = $totalpages){
                if($pageNumber > 2)
                    $displayData['prevURL'] = $base_url.'-'.($pageNumber-1);
                else
                    $displayData['prevURL'] = $base_url;
                $displayData['canonicalURL'] = $base_url;
            }
        }

        if(isset($_REQUEST['type'])){
            if($displayData['nextURL']){
                $displayData['nextURL'] .= '?type='.$displayData['contentType'];
            }
            if($displayData['prevURL']){
                $displayData['prevURL'] .= '?type='.$displayData['contentType'];
            }
        }

        $displayData['boomr_pageid'] = 'mobilesite_AllContent_Page';

         $displayData['beaconTrackData'] = array(
                                        'pageIdentifier' => "UILP",
                                        'pageEntityId' => $instituteId,
                                        'extraData' => array("childPageIdentifier"=>$scanPageName,'url'=>get_full_url())
                                    );
        $displayData['beaconTrackData']['extraData']['listingType'] = $instituteType;
        $displayData['beaconTrackData']['extraData']['countryId'] = 2;
        $displayData['seoHeadingText'] = $this->intitutedetaillib->getSeoDataForAllChildPages($displayData);
        
          $displayData['gtmParams'] = array(
                        "pageType"    => $scanPageName,
                        "countryId"     => 2,
                        'listingType' => $instituteType
                );
        if($userId > 0)
        {
            $userWorkExp = $this->userStatus[0]['experience'];
            if($userWorkExp >= 0)
                $displayData['gtmParams']['workExperience'] = $userWorkExp;
        }
        if(!empty($selectedInstituteId))
        {
            $displayData['gtmParams']['instituteId'] = $selectedInstituteId;
        }
        if(!empty($selectedStreamId))
        {
            $displayData['gtmParams']['stream'] = $selectedStreamId;
        }
        if(!empty($selectedCourseId) && (!empty($displayData['filtersArray']['baseCourseMapping'][$selectedCourseId]) || !empty($displayData['coursesData']['baseCourseMapping'][$selectedCourseId])))
        {
            $displayData['gtmParams']['courseFilter'] = $displayData['filtersArray']['baseCourseMapping'][$selectedCourseId];
        }
        
        $displayData['trackForPages'] = true;


        $this->institutedetaillib = $this->load->library("nationalInstitute/InstituteDetailLib");

        $displayData['canonicalURL'] = $this->institutedetaillib->getCanonnicalUrl($listingId,$displayData['canonicalURL']);

        // load view
        global $MESSAGE_MAPPING,$INSTITUTE_MESSAGE_KEY_MAPPING;
        $displayData['SRM_DATA'] = $MESSAGE_MAPPING[$INSTITUTE_MESSAGE_KEY_MAPPING[$listingId]];
        $displayData['showToastMsg'] = false;
        if(!empty($displayData['SRM_DATA'])){
            $displayData['showToastMsg'] = true;
        }    
        if($displayData['pdf']){
            if($pageType != 'scholarships' &&  $displayData['totalElements'] == 0){
                echo "";
            }else{
                $this->load->view('nationalInstitute/AllContentPDF/AllContentHomePDF',$displayData);
            }
        }else{
            $this->load->view("mobile_listing5/allcontent/allContentHome", $displayData);
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
        }else{
            $articleArray = $this->articlerecommendationlib->forUniversity($instituteId,array(),$count,$start,$displayData['sort_by'], $displayData['courseIdsMapping']);
        }
        $articleIds = $articleArray['topContent'];

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
        $displayData['m_meta_title']                  = "Latest News and Articles on ".$institutehtmlName." - Shiksha";
        $displayData['m_meta_description']                   = "Read the latest articles and news about ".$institutehtmlName.". Find out about ".$institutehtmlName." eligibility, important dates, fees and much more on Shiksha.com.";
        if($displayData['pageNumber'] > 1){
            $displayData['m_meta_title'] = "Page ".$displayData['pageNumber']." - ".$displayData['m_meta_title'];
            $displayData['m_meta_description']  = "Page ".$displayData['pageNumber']." - ".$displayData['m_meta_description'];
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
        $displayData['m_meta_title']                  = $institutehtmlName." Latest News and Notifications for ".$currentYear;
        $displayData['m_meta_description']                   = "Read the latest news, notifications and articles about cutoffs, placements, courses, fees, admission, ranking, selection criteria & eligibility for ".$institutehtmlName." ".$secondaryName;
        if($displayData['pageNumber'] > 1){
            $displayData['m_meta_title'] = "Page ".$displayData['pageNumber']." - ".$displayData['m_meta_title'];
            $displayData['m_meta_description']  = "Page ".$displayData['pageNumber']." - ".$displayData['m_meta_description'];
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


            if(strpos($currentUrl, '/pdf') !== false){
                $seo_url .= '/pdf'; 
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
                    header("Location: $disable_url",TRUE,302);
                }
                else{
                    header("Location: ".SHIKSHA_HOME,TRUE,301);
                }
                exit;
            }
        }
    }

    function getUnansweredQuestions($listingId, $listingType, $start, $count, $prefetchedCourseIds=array()){
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
                                    $universityChildren = $this->institutedetaillib->getInstituteCourseIds($listingId,'university');
                                else
                                    $universityChildren = $prefetchedCourseIds;
                                $universityChildrenId = array_keys($universityChildren['type']);
                                $data = $allcontent->getInstituteUnansweredAnA($universityChildrenId, $start, $count);
                               break;
        }
        return $data;
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
            $contentData = $tagsmodel->getContentDetails(implode(',',$contentIdsArray), $displayData['contentType'], 0, $count, $userId);
            if(is_array($contentData) && count($contentData)>0){
                $displayData['data']['homepage'] = $contentData;
            }
            $displayData['totalElements'] = $data['numFound'];
        }else{
            $displayData['totalElements'] = 0;
        }


        //get all courses
        $listingId  = $displayData['listing_id'];
        $courseIdArray[$listingId] = $displayData['courseIdsMapping']['courseIds'];
        if(!empty($courseIdArray)){
             $this->coursedetailmodel = $this->load->model('nationalCourse/Coursedetailmodel');
            $displayData['instituteCourses'] = $this->coursedetailmodel->getCourseName($courseIdArray[$listingId]);
            /*$this->load->builder("nationalCourse/CourseBuilder");
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

            $displayData['instituteCourses'] = $instituteCourses;*/
        }

    }

    function getAllQuestionSeoData(&$displayData){
        $institutehtmlName=special_chars_replace(htmlentities($displayData['instituteNameWithLocation']));
        $displayData['m_meta_title']                  = "Questions Asked about ".$institutehtmlName." - Shiksha";
        $displayData['m_meta_description']            = "See ".special_chars_replace(htmlentities($displayData['totalElements']))." questions for ".$institutehtmlName." answered by students and experts. Find out about ".$institutehtmlName." eligibility, important dates, fees and much more on Shiksha.com";
        $displayData['sectionText']               = "Questions";

        if($displayData['pageNumber'] > 1){
            $displayData['m_meta_title'] = "Page ".$displayData['pageNumber']." - ".$displayData['m_meta_title'];
            $displayData['m_meta_description']  = "Page ".$displayData['pageNumber']." - ".$displayData['m_meta_description'];
        }
    }
    function getAllQuestionSeoDataNew(&$displayData){
        $institutehtmlName=special_chars_replace(htmlentities($displayData['instituteNameWithLocation']));
        $secondaryName = $displayData['instituteObj']->getSecondaryName();
        if($secondaryName != ''){
            $secondaryName = "(".$secondaryName.")";
        }
        $displayData['m_meta_title']                  = $institutehtmlName." Q&A on Cutoffs, Placements, Fees & Admission";
        $displayData['m_meta_description']            = "See ".special_chars_replace(htmlentities($displayData['totalElements']))." Answered Questions on cutoffs, placements, courses, fees, admission, ranking & eligibility for ".$institutehtmlName." ".$secondaryName." answered by students and experts.";
        $displayData['sectionText']               = "Questions";

        if($displayData['pageNumber'] > 1){
            $displayData['m_meta_title'] = "Page ".$displayData['pageNumber']." - ".$displayData['m_meta_title'];
            $displayData['m_meta_description']  = "Page ".$displayData['pageNumber']." - ".$displayData['m_meta_description'];
        }
    }

    /**
    * @param : $displayData =array 
    * @param : $contentType = question(default)/discussion/unanswered
    */
    function getAllQuestionMISData(&$displayData,$contentType)
    {
        switch ($contentType) {
            case 'discussion':
                $displayData['tupdctrackingPageKeyId'] = 1057;
                $displayData['tdowndctrackingPageKeyId'] = 1058;
                $displayData['followDiscTrackingPageKeyId'] = 1059;
                break;
            case 'unanswered':
                $displayData['followUnQuesTrackingPageKeyId'] = 1060;
            default:
                $displayData['tupdctrackingPageKeyId'] = 984;
                $displayData['tdowndctrackingPageKeyId'] = 985;
                $displayData['tupatrackingPageKeyId'] = 980;
                $displayData['tdownatrackingPageKeyId'] = 981;
                $displayData['followQuesTrackingPageKeyId'] =982;
                $displayData['followDiscTrackingPageKeyId'] = 983;
                break;
        }
    }

    function getScholarshipData($instituteObj,&$displayData){
        $displayData['scholarships'] = $instituteObj->getScholarships();
        $displayData['anaWidget'] = modules::run('mobile_listing5/InstituteMobile/getAnAWidget',$instituteObj->getId(),$instituteObj->getType(),$displayData['courseIdsMapping'], false, array('getCount' => 1));

        $this->institutedetaillib = $this->load->library("nationalInstitute/InstituteDetailLib");
        $topWidgetData['instituteImportantData'] = $this->institutedetaillib->getTopWidgetData($displayData['instituteObj']);

        $displayData['topCardData'] = $topWidgetData;
        $currentLocationObj = $displayData['instituteObj']->getMainLocation();
        $displayData['currentLocationObj'] = $currentLocationObj;
        $displayData['instituteToolTipData'] = $this->config->item('instituteToolTipData');
    }

    function getScholarshipSeoDetails(&$displayData){
        $displayData['sectionText'] = "Scholarships";
        $this->instituteDetailLib = $this->load->library("nationalInstitute/InstituteDetailLib");
        $seoMetaData = $this->instituteDetailLib->getSeoDataForScholarshipPage($displayData['instituteObj']);
        $displayData['m_meta_title'] = $seoMetaData['seoTitle'];
        $displayData['m_meta_description']  = $seoMetaData['seoDesc'];
    }

    function getAdmissionPageData($listingId, $listingType, $start, $count, &$displayData){
        $this->courseDetailLib = $this->load->library('nationalCourse/CourseDetailLib');
        $this->load->config("nationalCourse/courseConfig");
        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository(); 

        $this->intitutedetaillib = $this->load->library("nationalInstitute/InstituteDetailLib");
        $topWidgetData['instituteImportantData'] = $this->intitutedetaillib->getTopWidgetData($displayData['instituteObj']);

        $displayData['topCardData'] = $topWidgetData;
        $currentLocationObj = $displayData['instituteObj']->getMainLocation();
        $displayData['currentLocationObj'] = $currentLocationObj;
        $displayData['instituteToolTipData'] = $this->config->item('instituteToolTipData');
        
        // get streams and courses data
        $displayData['coursesData'] = $this->intitutedetaillib->getAdmissionPageCoursesData($displayData['instituteObj'], $displayData['selectedStreamId'], $displayData['selectedCourseId'], $displayData['courseIdsMapping']);

        $displayData['courseId']=(!empty($displayData['selectedCourseId']))?$displayData['selectedCourseId']:$displayData['coursesData']['mostPopularCourse'];
        $displayData['stream_id'] =(!empty($displayData['selectedStreamId']))?$displayData['selectedStreamId']:$displayData['coursesData']['mostPopularStream'];

        if(!empty($displayData['courseId']) && $displayData['courseId']>0){
            $course_id = $displayData['courseId'];
            $courseObj = $this->courseRepo->find($course_id,'full');

            $selectedCategory = $this->input->cookie('selectedCategory', TRUE);
            $selectedCategory = $selectedCategory ? $selectedCategory : 'general';
            $eligibilityData             = $this->courseDetailLib->getCourseEligibilityDataWithCache($course_id,$selectedCategory);
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
        $displayData['m_meta_title']                  = $institutehtmlName." Admission ".$currentYear.'  - Application Process, Eligibility &  Dates';
        $displayData['m_meta_description']            = "See ".$institutehtmlName." ".$currentYear." admission process for admission to ".special_chars_replace(htmlentities(count($displayData['courseIdsMapping']['courseIds'])))." courses. Find out about eligibility, application form, how to apply, exams required, important dates, fees and much more on Shiksha.com";
        
        $displayData['sectionText']               = "Admission";

        if($displayData['pageNumber'] > 1){
            $displayData['m_meta_title'] = "Page ".$displayData['pageNumber']." - ".$displayData['seoTitle'];
            $displayData['m_meta_description']  = "Page ".$displayData['pageNumber']." - ".$displayData['seoDesc'];
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
        $displayData['m_meta_title'] =$institutehtmlName." Admission ".$currentYear." - Cutoffs, Eligibility & Dates";
        $displayData['m_meta_description'] = "See Admission Process, Cutoffs, Eligibility, Selection Criteria & Dates at ". $institutehtmlName;
        if(!empty($secondaryName)){
           $displayData['m_meta_description']=$displayData['m_meta_description']. "(".$secondaryName.")";
        }
    }
    /**
    * Function is used for preparing filters for all content page
    * @param [type:Integer] $listingId [InstituteId/Unviersity Id]
    * @param [type:String] $listingType [Institute/University]
    * @param [type:String] $pageType [articles/questions/reviews]
    */

    function prepareFilters($listingId,$listingType,$pageType,$contentType,$displayData)
    {
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
                        $data = $this->reviewrecommendationlib ->getFiltersForUniversity($listingId, $displayData['courseIdsMapping']);
                        $courseIds = $data['courseIds'];
                    }
                    else
                    {
                        $data = $this->reviewrecommendationlib->getFiltersForInstitute($listingId);
                        $courseIds = $data;
                    }
                    $data['Rating'] = $this->createReviewRatingFilterArr($displayData);
                break;
            default:
                # code...
                break;
        } 
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
        $isShowIcpBanner = false;
       
        if(!empty($courseIds) && count($courseIds) >0)
        {
            $i = 0 ;
            $this->load->builder("nationalCourse/CourseBuilder");
            $courseBuilder = new CourseBuilder();
            $this->courseRepo = $courseBuilder->getCourseRepository();
            $coursesInfo = $this->courseRepo->findMultiple($courseIds);
            foreach ($coursesInfo as $courseKey => $courseValue) {
                $courseObject = $coursesInfo[$courseKey];
                $courseId = $courseObject->getId();
                $baseCourseId = $courseObject->getBaseCourse();
                $courseName = htmlentities($courseObject->getName(),ENT_QUOTES);
                if($listingType == 'university')
                {
                    $offeredByName = htmlentities($courseObject->getOfferedByShortName(),ENT_QUOTES);
                    if(empty($offeredByName))
                    {
                        $offeredByName = htmlentities($courseObject->getOfferedByName(),ENT_QUOTES);    
                    }
                    if(!empty($offeredByName))
                    {
                        $courseName .= ', '.$offeredByName;
                    }
                }

                $mappedBaseCourseIds = $courseValue->getBaseCourse();

                $isShowIcpBanner = ($mappedBaseCourseIds['entry'] == ICP_BANNER_COURSE) ? true : $isShowIcpBanner; 

                if(!empty($courseId))
                {
                    $filterCourses[$i++] = array('name' => $courseName,'id' => $courseId);
                }
                    
                 if(!empty($baseCourseId) && !empty($courseId))
                    $baseCourseMapping[$courseId] = $baseCourseId['entry'];
            }
        }
        if(!empty($instituteIds) && count($instituteIds) > 0 )
        {
            $i = 0;
            $this->load->builder("nationalInstitute/InstituteBuilder");
            $instituteBuilder = new InstituteBuilder();
            $this->instituteRepo = $instituteBuilder->getInstituteRepository();       
            $instituteInfo = $this->instituteRepo->findMultiple($instituteIds);
            foreach ($instituteInfo as $instituteKey => $instituteValue) {
                $instituteObject = $instituteInfo[$instituteKey];
                $instituteId   = $instituteObject->getId();

                $instituteName = htmlentities($instituteObject->getShortName(),ENT_QUOTES);
                if(empty($instituteName))
                    $instituteName = htmlentities($instituteObject->getName(),ENT_QUOTES);
                if(!empty($instituteId))
                    $filterInstitutes[$i++] = array('name' => $instituteName,'id' => $instituteId );
            }
        }
         function ArrayValueSort($a,$b)
         {
            if ($a['name'] ==$b['name'] ) return 0;
              return (strtolower($a['name'])<strtolower($b['name']))?-1:1;
         }
         usort($filterCourses, 'ArrayValueSort');
         usort($filterInstitutes,'ArrayValueSort');
         $filtersArray['Institutes'] = $filterInstitutes;
         $filtersArray['Courses'] = $filterCourses;
         $filtersArray['instituteCoursesMapping'] = $instituteCoursesMapping;
         $filtersArray['baseCourseMapping'] = $baseCourseMapping;
        if(!empty($data['Rating'])){
            $filtersArray['Rating'] = $data['Rating'];
        }
         $filtersArray['isShowIcpBanner'] = $isShowIcpBanner;
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
            $this->load->config('nationalInstitute/CollegeCutoffConfig');
            $parentListingIds = $this->config->item('parentListingIds');
            if($parentListingIds[$instituteObj->getId()]){
                $displayData['cutoffURL'] = getSeoUrl($listingId,'all_content_pages','',array('typeOfPage'=>'cutoff','typeOfListing'=>$listingType));;
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

        //Check if PlacementPage Exists 
        if($instituteObj->isPlacementPageExists()){
            $displayData['placementURL'] = $instituteObj->getAllContentPageUrl('placement');
        }


        //Check if Admission URL exists on this University
        if($pageType!='admission'){
            if($instituteObj->isAdmissionDetailsAvailable()){
                $displayData['admissionURL'] = getSeoUrl($listingId,'all_content_pages','',array('typeOfPage'=>'admission','typeOfListing'=>$listingType));;
            }
        }
	$displayData['listingType'] = $listingType;

	if($pageType == 'scholarships'){
        $this->load->view("allcontent/widgets/relatedLinksNew",$displayData);
    }
    else{
        $this->load->view("allcontent/widgets/relatedLinks",$displayData);
    }
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
        $reviewURL = '';
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
            $alsoViewedListing = $this->alsoviewed->getAlsoViewedInstitutes(array($listingId),'16',array(), $courseIdsMapping);
        }else if($listingType == 'university'){
            $alsoViewedListing = $this->alsoviewed->getAlsoViewedUniversities(array($listingId),'16', array(), $courseIdsMapping);
        }
        if(!empty($alsoViewedListing)){

            $finalArray = array();
            $instituteObj = $this->instituteRepo->findMultiple($alsoViewedListing,array('basic','media'));

            //Now, for these institutes, check if Content exists.
            switch ($pageType){
                    case 'questions':
                            $displayData['GA_Tap_On_Link'] = 'RECO_VIEW_LISTING_LINK';
                            $this->load->library('ContentRecommendation/AnARecommendationLib');
                            switch ($listingType){
                                case 'institute': $quesCheck = $this->anarecommendationlib->checkContentExistForInstitute($alsoViewedListing); break;
                                case 'university': $quesCheck = $this->anarecommendationlib->checkContentExistForUniversity($alsoViewedListing,'question', $courseIdsMapping); break;
                                case 'course': $quesCheck = $this->anarecommendationlib->checkContentExistForCourse($alsoViewedListing); break;
                            }
                            foreach ($quesCheck as $listingIdForQues){
                                if(isset($instituteObj[$listingIdForQues])){
                                    $questionURL = getSeoUrl($listingIdForQues,'all_content_pages','',array('typeOfPage'=>'questions','typeOfListing'=>$listingType));
                                    $listingName = ($instituteObj[$listingIdForQues]->getShortName()!='')?$instituteObj[$listingIdForQues]->getShortName():$instituteObj[$listingIdForQues]->getName();
			            $headerImage = $instituteObj[$listingIdForQues]->getHeaderImage();
				    // if header image exists get its variant otherwise use default image
			            if($headerImage && $headerImage->getUrl()){
				            $imageLink = $headerImage->getUrl();
				            $imageUrl = getImageVariant($imageLink,6);
				    }
			            else{
				            $imageUrl = MEDIAHOSTURL."/public/mobile5/images/recommender_dummy.png";
				    }
                                    $finalArray[] = array('listingName'=>$listingName,'listingImage'=>$imageUrl,'URL'=>$questionURL);
                                }
                            }
                            break;
                    case 'articles':
                            $displayData['GA_Tap_On_Link'] = 'RECO_VIEW_LISTING_LINK';
                            $this->load->library('ContentRecommendation/ArticleRecommendationLib');
                            switch ($listingType){
                                case 'institute': $articleCheck = $this->articlerecommendationlib->checkContentExistForInstitute($alsoViewedListing); break;
                                case 'university': $articleCheck = $this->articlerecommendationlib->checkContentExistForUniversity($alsoViewedListing); break;
                                case 'course': $articleCheck = $this->articlerecommendationlib->checkContentExistForCourse($alsoViewedListing); break;
                            }
                            foreach ($articleCheck as $listingIdForArticle){
                                if(isset($instituteObj[$listingIdForArticle])){
                                    $articleURL = getSeoUrl($listingIdForArticle,'all_content_pages','',array('typeOfPage'=>'articles','typeOfListing'=>$listingType));
                                    $listingName = ($instituteObj[$listingIdForArticle]->getShortName()!='')?$instituteObj[$listingIdForArticle]->getShortName():$instituteObj[$listingIdForArticle]->getName();
                                    $headerImage = $instituteObj[$listingIdForArticle]->getHeaderImage();
                                    // if header image exists get its variant otherwise use default image
                                    if($headerImage && $headerImage->getUrl()){
                                            $imageLink = $headerImage->getUrl();
                                            $imageUrl = getImageVariant($imageLink,6);
                                    }
                                    else{
                                            $imageUrl = MEDIAHOSTURL."/public/mobile5/images/recommender_dummy.png";
                                    }
                                    $finalArray[] = array('listingName'=>$listingName,'listingImage'=>$imageUrl,'URL'=>$articleURL);
                                }
                            }
                            break;
                    case 'reviews':
                            $displayData['GA_Tap_On_Link'] = 'RECO_VIEW_LISTING_LINK';
                            $this->load->library('ContentRecommendation/ReviewRecommendationLib');
                            switch ($listingType){
                                case 'institute': $reviewCheck = $this->reviewrecommendationlib->checkContentExistForInstitute($alsoViewedListing); break;
                                case 'university': $reviewCheck = $this->reviewrecommendationlib->checkContentExistForUniversity($alsoViewedListing); break;
                                case 'course': $reviewCheck = $this->reviewrecommendationlib->checkContentExistForCourse($alsoViewedListing); break;
                            }
                            foreach ($reviewCheck as $listingIdForReview){
                                if(isset($instituteObj[$listingIdForReview])){
                                    $reviewURL = getSeoUrl($listingIdForReview,'all_content_pages','',array('typeOfPage'=>'reviews','typeOfListing'=>$listingType));
                                    $listingName = ($instituteObj[$listingIdForReview]->getShortName()!='')?$instituteObj[$listingIdForReview]->getShortName():$instituteObj[$listingIdForReview]->getName();
                                    $headerImage = $instituteObj[$listingIdForReview]->getHeaderImage();
                                    // if header image exists get its variant otherwise use default image
                                    if($headerImage && $headerImage->getUrl()){
                                            $imageLink = $headerImage->getUrl();
                                            $imageUrl = getImageVariant($imageLink,6);
                                    }
                                    else{
                                            $imageUrl = MEDIAHOSTURL."/public/mobile5/images/recommender_dummy.png";
                                    }
                                    $finalArray[] = array('listingName'=>$listingName,'listingImage'=>$imageUrl,'URL'=>$reviewURL);
                                }
                            }
                            break;
            }
            $displayData['dataArray'] = $finalArray;
            $displayData['pageType'] = $pageType;
            $this->load->view("allcontent/widgets/alsoViewedReco",$displayData);
        }

    }


    function getAllReviewData($instituteId, $instituteType, $start, $count, &$displayData,$selectedFilterRating = 0,$selectedTagId = 0){

        $this->load->library('ContentRecommendation/ReviewRecommendationLib');
        $this->load->config('CollegeReviewForm/collegeReviewConfig');
        
        if($instituteType== 'institute'){
            $allReviews = $this->reviewrecommendationlib->forInstitute($instituteId,array(),300,0,$displayData['sort_by'], $selectedFilterRating,$selectedTagId,false);                  
        }else if($instituteType== 'course'){
            $allReviews = $this->reviewrecommendationlib->forCourse($instituteId,array(),300,0,$displayData['sort_by'], null, $selectedFilterRating,$selectedTagId,false);  
            if(isset($allReviews['totalNumFound']) && $allReviews['totalNumFound']<1){
                $displayData['totalElements'] = 0;
                return;
            }
        }else{
            $allReviews = $this->reviewrecommendationlib->forUniversity($instituteId,array(),300,0,$displayData['sort_by'], $displayData['courseIdsMapping'], $selectedFilterRating,$selectedTagId,false);
        }


        $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $placementsTopicTagsIds = $collegeReviewLib->getPlacementTopicTagsForReviews($instituteId,$instituteType,$selectedFilterRating);
        $collegereviewmodel = $this->load->model('CollegeReviewForm/collegereviewmodel');
        $displayData['placementsTopicTagsIds'] = $placementsTopicTagsIds;
        $displayData['placementsTopicTagsName']  = $collegereviewmodel->getReviewTopicTagsName( $placementsTopicTagsIds,'placements');
        
        $aggregateReviews = $collegeReviewLib->getAggregateReviewsForListing($instituteId, $instituteType);
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

        $reviewIdsArray = array_slice($allReviews['topContent'],$start,$count);
        if(!empty($reviewIdsArray)){
            $displayData = $this->prepareReviewData($reviewIdsArray,$displayData,$selectedTagId);
            $displayData['allReviews'] = $allReviews['topContent'];
        }

		$displayData['totalElements'] = $allReviews['numFound'];
        $displayData['allReviewsCount'] = $allReviews['totalNumFound'];

    }

    function prepareReviewData($reviewIdsArray,$displayData,$selectedTagId =0){
            if(!empty($reviewIdsArray)){
                $collegereviewmodel = $this->load->model('CollegeReviewForm/collegereviewmodel');
                $reviewData = $collegereviewmodel->getReviewsDetails($reviewIdsArray,false);
                if($reviewData && $reviewData['reviews']){
                    $reviewData = $reviewData['reviews'];
                }
                if($selectedTagId > 0){
                    //_P($reviewIdsArray); die;
                    $placementData = $collegereviewmodel->getPlacementDatafromTag($reviewIdsArray,$selectedTagId,'placements');
                   foreach ($placementData as $key => $reviewRow) {

                        $reviewData[$reviewRow['review_id']]['placementDescription'] = $reviewRow['highlighted_review'];
                   }
                }
                $reviewRating = $collegereviewmodel->getRatingMultipleReviews($reviewIdsArray); 
                $reviewReplies = $collegereviewmodel->getRepliesForReviewIds($reviewIdsArray);

                foreach ($reviewData as $key => $reviewRow) {
                    $courseIds[] = $reviewRow['courseId'];
                }
                
                $reviewRepliesData = array();
                foreach ($reviewReplies as $reply) {
                    $reviewRepliesData[$reply['reviewId']] = $reply['replyTxt'];
                }

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
      return $displayData;
    }

    function getAllReviewsSeoDetails(&$displayData)
    {   
        $instituteObj=$displayData['instituteObj'];
        $mainLocation = $instituteObj->getMainLocation();
        $cityName=$mainLocation->getCityName();
        $institutehtmlName=special_chars_replace(htmlentities($displayData['instituteNameWithLocation']));
        $displayData['m_meta_title']                  = htmlentities(str_replace(", "," ",$displayData['instituteNameWithLocation']))." Reviews - Shiksha";
        $displayData['m_meta_description']            = "Read ".special_chars_replace(htmlentities($displayData['totalElements']))." latest reviews of ".$displayData['instituteNameWithLocation']." from current students and alums. You'll get all verified and genuine reviews of ".$displayData['instituteNameWithLocation']." based on courses and placement at Shiksha.com.";
        $displayData['sectionText']               = "Reviews";

         if($displayData['pageNumber'] > 1){
                $displayData['m_meta_title'] = "Page ".$displayData['pageNumber']." - ".$displayData['m_meta_title'];
                $displayData['m_meta_description']  = "Page ".$displayData['pageNumber']." - ".$displayData['m_meta_description'];
            }

    }
    function getAllReviewsSeoDetailsNew(&$displayData)
    {   
        $instituteObj=$displayData['instituteObj'];
        $mainLocation = $instituteObj->getMainLocation();
        $secondaryName = $displayData['instituteObj']->getSecondaryName();
        if($secondaryName != ''){
            $secondaryName = "(".$secondaryName.")";
        }
        $cityName=$mainLocation->getCityName();
        $institutehtmlName=special_chars_replace(htmlentities($displayData['instituteNameWithLocation']));
        $displayData['m_meta_title']                  = $institutehtmlName." Reviews on Placements, Faculty and Facilities";
        $displayData['m_meta_description']            = "Read ".special_chars_replace(htmlentities($displayData['totalElements']))." Reviews on Placements, Faculty, Facilities & Infrastructure of ".$displayData['instituteNameWithLocation']." ".$secondaryName." given by students and alumni across courses and streams.";
        $displayData['sectionText']               = "Reviews";

         if($displayData['pageNumber'] > 1){
                $displayData['m_meta_title'] = "Page ".$displayData['pageNumber']." - ".$displayData['m_meta_title'];
                $displayData['m_meta_description']  = "Page ".$displayData['pageNumber']." - ".$displayData['m_meta_description'];
            }

    }

    function getReviewDetailLayerwidget(){
        $this->_init();

        $reviewId = isset($_POST['reviewId'])?$this->input->post('reviewId'):'';
        $allReviews = isset($_POST['allReviews'])?$this->input->post('allReviews'):'';

        if(!empty($reviewId)){
            $index = array_search($reviewId, $allReviews);
            if($index !== FALSE)
            {
              $displayData['nextReview'] = $allReviews[$index + 1];
              $displayData['prevReview'] = $allReviews[$index - 1];
            }
            $displayData['index'] = $index;
            $result = $this->prepareReviewData(array($reviewId),$displayData);
            $displayData['reviewRow'] =$result['reviewsData'][0];
            $displayData['reviewRating'] = $result['reviewRating'];
            $displayData['reviewReplies'] = $result['reviewReplies'];

            //Get User Session Data
            $displayData['validateuser'] = $this->userStatus;
            $displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            $displayData['sessionId'] = sessionId();
            $collegereviewmodel = $this->load->model('CollegeReviewForm/collegereviewmodel');
            $userSessionData = $collegereviewmodel->getUserSessionData($displayData['userId'], $displayData['sessionId']);
            if(is_array($userSessionData)){
                $displayData['userSessionData'] = $userSessionData;
            }
 
            echo $this->load->view('allcontent/widgets/reviewDetailLayer',$displayData);
        }

    }

    /**
    * @param: $listingId : university id
    * @return : list of exams directly mapped to university listing with sorted order
    */
    function getExamsMappedToUniversity($listingId, $listingType)
    {
        $this->_init();
        $examList = $this->institutedetailmodel->getExamsMappedToUniversity($listingId);

        function examSort($a,$b)
                 {
                    if ($a['name']==$b['name']) return 0;
                      return (strtolower($a['name'])<strtolower($b['name']))?-1:1;
                 }

        usort($examList,"examSort");
	$displayData = array();
    $displayData['examList'] = $examList;
	$displayData['listingType'] = $listingType;
	echo $this->load->view("allcontent/widgets/admissionExams", $displayData);
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
        /*if(isset($OnlineFormData['isExternal']) && $OnlineFormData['isExternal'] == 1)
        {
            $returnArr['isInternal'] = false;
        }
        else
        {
            $returnArr['isInternal'] = true;
        }*/
        return $returnArr;
    }

    /*
    *   Ajax - get compare widget course data,
    *   @params - POST: listingType,listingId
    *             COOKIE: mob-compare-global-data
    *   @return - courses for compare info
    */
    function getCompareWidgetData(){
        $this->intitutedetaillib = $this->load->library("nationalInstitute/InstituteDetailLib");
        
        if(!isset($_POST['listingType']) || !isset($_POST['listingId'])){
            echo json_encode(array('courseList' => array(), 'status' => false));
            exit(0);
        }
        $listingType=$_POST['listingType'];
        $listingId=$_POST['listingId'];

        
        $response=array();
        $status=false;
        switch ($listingType) {
            case 'institute':
            case 'university':
                $allCourses=$this->intitutedetaillib->getInstitutePageCompareWidgetData($listingId, $listingType);

                $compareCourses = $_COOKIE['mob-compare-global-data'];
                $compareCourses = explode("|", $compareCourses);

                foreach ($compareCourses as $key => $value) {
                    $compareCourses[$key] = explode("::", $value);
                    $compareCourses[$key] = $compareCourses[$key][0];
                }
                foreach ($allCourses as $courseObj) {
                        $instituteName = $courseObj->displayInstituteName;
                        
                        $courseName = $courseObj->getName();
                        if($listingType == 'university'){
                          $courseName .= ", ".$instituteName;
                        }
                        $tempCourse=array();
                        $tempCourse['id']=$courseObj->getId();
                        $tempCourse['checked']=in_array($courseObj->getId(), $compareCourses) ? "checked" : "";
                        $tempCourse['name']=htmlentities($courseName);
                        $response[] = $tempCourse;
                }
                $status=true;
                break;
            default:
                # code...
                break;
        }

        echo json_encode(array('courseList' => $response, 'status' => $status));
        exit(0);    
    }

    function updateRatingFilter() {
        $listingId = $this->input->post('listingId');
        $listingType = $this->input->post('listingType');
        if(empty($listingId) || !is_numeric($listingId) || !in_array($listingType,array('course', 'institute', 'university'))){
            show_404();
            exit(0);
        }
        $collegeReviewLib = $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $this->load->config('CollegeReviewForm/collegeReviewConfig');
        $aggregateReviews = $collegeReviewLib->getAggregateReviewsForListing($listingId, $listingType);
        $displayData['aggregateReviewsData'] = $aggregateReviews[$listingId];
        $ratingFilter = $this->createReviewRatingFilterArr($displayData);        
        echo json_encode($ratingFilter);
    }

    function createReviewRatingFilterArr($displayData) {
        $aggregateReviewsData = $displayData['aggregateReviewsData'];
        if(!empty($aggregateReviewsData['intervalRatingCount'])) {
            $ratingFilterDisplayMapping = $this->config->item('ratingFilterDisplayMapping');
            foreach ($ratingFilterDisplayMapping as $interval => $intervalDisplayName) {
                if(array_key_exists($interval.'-'.($interval+1), $aggregateReviewsData['intervalRatingCount']) && $aggregateReviewsData['intervalRatingCount'][$interval.'-'.($interval+1)] == 0) {
                    continue;
                }
                else {
                    $data['Rating'][] = array('name' => $intervalDisplayName, 'id' => $interval);
                }
            }
            return $data['Rating'];
        }
    }

}

?>
