<?php

class CoursePage extends MX_Controller {

    private $coursePagesRepository;
    private $coursePagesUrlRequest;
    private $courseHomePageList;
    private $coursePageCommonLib;
    private function _init() {
        $this->load->builder('CoursePagesBuilder', 'coursepages');
        $coursePagesBuilder = new CoursePagesBuilder;
        $this->coursePagesRepository = $coursePagesBuilder->getCoursePagesRepository();
        $this->coursePagesUrlRequest = $this->load->library('coursepages/CoursePagesUrlRequest', 0);
        $this->coursePageCommonLib = $this->load->library('coursepages/CoursePagesCommonLib');
        $this->courseHomePageList =  $this->coursePageCommonLib->getCourseHomePageDictionary(0);
        $this->load->helper('shikshaUtility');
    }

    /*public function buildCourseHomePage() {
        $CHPSubcategories = array(98, 28, 64, 100, 84, 139, 65, 18, 133);
        $categoryModel = $this->load->model('categoryList/categorymodel');
        $courseModel = $this->load->model('coursepages/coursepagemodel');

        $dataSubCategories = $categoryModel->getMultipleCategories($CHPSubcategories);
        //_p($dataSubCategories);die;
        foreach ($dataSubCategories as $key => $value) {
            $courseModel->insertCourseHomePageWidgetMapping($value);
        }
        _p($dataSubCategories);
        die;
    }*/

    public function coursePages($courseUrlKey, $pageNo = '') {
        // redirect old news and article page
        //added by akhter
        if(strpos($courseUrlKey, "-news-articles") !==  FALSE) {
            $artObj = $this->load->library('article/articleRedirectionLib');
            $artObj->redirectFromCoursePage($courseUrlKey,$pageNo);
        }
        
        $this->_init();
        //parsing the url and redirection logic
        $response = $this->coursePagesUrlRequest->getCourseHomePageFromCoursePageUrlKey($courseUrlKey, $pageNo);
        if($response['courseHomePageId'] == -1){
            $response = $this->_checkForOldUrlRedirection($courseUrlKey,$response);
        }
        $this->coursePagesUrlRequest->checkForRedirection($response);
        $courseHomePageId = $response['courseHomePageId'];
        $tagId = $response['tagId'];
        $coursePageType = $response['coursePageType'];
        $pageNo = 1;
        if (isset($response['pageNo'])) {
            $pageNo = $response['pageNo'];
        }
        if (!$this->_ifCoursePagesExistForCourseHomePage($courseHomePageId)) {
            show_404();
        }

        switch ($coursePageType) {
            case 'questions':
                $this->_questionDiscussionResourcePageRedirection($courseHomePageId, 'question');
                $start = 20 * ($pageNo - 1);
                $totalRows = 20;
                echo Modules::run('messageBoard/MsgBoard/discussionHome', $courseHomePageId, 1, 2, 'answer', 'default', $start, $totalRows);
                break;

            case 'discussions':
                $this->_questionDiscussionResourcePageRedirection($courseHomePageId, 'discussion');
                $start = 60 * ($pageNo - 1);
                $totalRows = 60;
                echo Modules::run('messageBoard/MsgBoard/discussionHome', $courseHomePageId, 6, 2, 'answer', 'default', $start, $totalRows);
                break;

            case 'news':
                $start = 20 * ($pageNo - 1);
                $totalRows = 20;
                $showTag = true; // this varibale is used to show tag to their title and combin the news and articles @akhter
                echo Modules::run('blogs/shikshaBlog/showArticlesList', $start, $totalRows, $courseHomePageId, 2, 'news_Articles', $showTag);
                break;

            case 'faq':
                $this->_checkAndRedirectCourseFaqPage($courseHomePageId);
                break;

            default:
                $this->_checkAndRedirectCourseHomePage($courseHomePageId);
                break;
        }
    }

    public function courseHomePage($homePageId){

        //https://infoedge.atlassian.net/browse/MAB-4713
        $this->load->config('CoursePageConfig');
        $oldChpToNewMapping = $this->config->item('301ChpMappings');
        if (is_numeric($homePageId) && $homePageId>0) { 
            $newURL = $oldChpToNewMapping[$homePageId];
            if(!empty($newURL)){
                $newURL = SHIKSHA_HOME.$newURL;
                redirect($newURL, 'location', 301);
            }
        }
        
        $this->benchmark->mark('Page_load_total_start');
        $this->benchmark->mark('Page_load_total_without_view_start');
        $this->_init();
        // $this->_checkAndRedirectCourseHomePage($homePageId);
        $this->coursePagesHome($homePageId,$this->courseHomePageList[$homePageId]['tagId']);
    }
    
    private function _checkAndRedirectCourseFaqPage($courseHomePageId){
        $this->_init();
        if(empty($this->courseHomePageList[$courseHomePageId]['url'])){
            show_404();
        }
        $faqUrl=  $this->coursePagesUrlRequest->getFaqUrlFromCourseHomePageId($courseHomePageId,  $this->courseHomePageList);
        // $curUrl = $_SERVER['SCRIPT_URI'];
        $curUrl = getCurrentPageURL();
        if($curUrl!=$faqUrl){
            if( (strpos($faqUrl, "http") === false) || (strpos($faqUrl, "http") != 0) || (strpos($faqUrl, SHIKSHA_HOME) === 0) || (strpos($faqUrl,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($faqUrl,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($faqUrl,ENTERPRISE_HOME) === 0) ){
                header("Location: $faqUrl",TRUE,301);
            }
            else{
                header("Location: ".SHIKSHA_HOME,TRUE,301);
            }
        }
    }

    private function _checkAndRedirectCourseHomePage($courseHomePageId){
        if(empty($this->courseHomePageList[$courseHomePageId]['url'])){
            show_404();
        }
        // $curUrl = $_SERVER['SCRIPT_URI'];
        $curUrl = getCurrentPageURLWithoutQueryParams();
        if($curUrl != $this->courseHomePageList[$courseHomePageId]['url']){
            $redirectUrl = $this->courseHomePageList[$courseHomePageId]['url'];
            if(!empty($_SERVER['QUERY_STRING'])){
                $redirectUrl .= '?'.$_SERVER['QUERY_STRING'];
            }
            if( (strpos($redirectUrl, "http") === false) || (strpos($redirectUrl, "http") != 0) || (strpos($redirectUrl, SHIKSHA_HOME) === 0) || (strpos($redirectUrl,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($redirectUrl,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($redirectUrl,ENTERPRISE_HOME) === 0) ){
                header("Location: $redirectUrl",TRUE,301);
            }
            else{
                header("Location: ".SHIKSHA_HOME,TRUE,301);
            }
        }
    }

    public function coursePagesHome($courseHomePageId, $tagId) {
        $this->_init();
        if (!$this->_ifCoursePagesExistForCourseHomePage($courseHomePageId)) {
            show_404();
        }

        $loggedinUserInfo = $this->checkUserValidation();
        if ($loggedinUserInfo == 'false') {
            $isUserLoggedin = FALSE;
        } else {
            $isUserLoggedin = TRUE;
        }

        $displayData['COURSE_HOME_PAGES_LIST'] = $this->courseHomePageList;
        $this->benchmark->mark('Get_widget_list_start');
        $processedWidgetsList = $this->_getWidgetListInfoForPage($courseHomePageId, $isUserLoggedin);
        $this->benchmark->mark('Get_widget_list_end');

        $this->benchmark->mark('Get_SEO_details_start');
        $coursePagesSeoDetails = $this->_getSeoDetailsForPage($courseHomePageId);
        $allTabsSeoDetails = $this->coursePagesUrlRequest->getAllTabSeoDetails($courseHomePageId);
        $this->hideTabsifNoDataExists($coursePagesSeoDetails, $courseHomePageId);
        $this->benchmark->mark('Get_SEO_details_end');

        $this->benchmark->mark('Get_All_widgets_data_start');
        $widgetsData = $this->getDataForWidgets($courseHomePageId, $loggedinUserInfo, $processedWidgetsList, $tagId, $displayData['COURSE_HOME_PAGES_LIST'][$courseHomePageId]);
        $processedWidgetsList = $this->_removeWidgetIfNoDataExists($processedWidgetsList, $widgetsData);
        $this->benchmark->mark('Get_All_widgets_data_end');
        $displayData['selectedTab'] = 'Home';
        $displayData['ifGutterHelpTextRequired'] = $this->_ifGutterHelpTextRequired($loggedinUserInfo);
        $displayData['coursePagesSeoDetails'] = $coursePagesSeoDetails;
        $displayData['allTabsSeoDetails'] = $allTabsSeoDetails;
        $displayData['processedWidgetsList'] = $processedWidgetsList;
        $displayData['courseHomePageId'] = $courseHomePageId;
        $displayData['subCategoryObj'] = $this->coursePagesUrlRequest->getCategoryInfo($courseHomePageId);
        $displayData['subcatNameForGATracking'] = $displayData['subCategoryObj']->getName();
        $displayData['pageTypeForGATracking'] = 'HOME';
        $displayData['validateuser'] = $loggedinUserInfo;
        $displayData['widgets_data'] = $widgetsData;
        $displayData['subcat_id_course_page'] = $courseHomePageId;
        $displayData['showGutterBanner'] = 1;
        $displayData['course_pages_tabselected'] = 'Home';
        $displayData['js'] = array('coursepages');
        $displayData['trackForPages'] = true;
        // $displayData['googleRemarketingParams'] = array(
        //     "categoryId" => $displayData['subCategoryObj']->getparentId(),
        //     "subcategoryId" => $courseHomePageId,
        //     "countryId" => 2,
        //     "cityId" => ''
        // );
        //Data for Showing College Review Widget on MBA Course page
        $displayData['loadCareerWidget'] = true;
        $displayData['beaconTrackData'] = array(
            'pageIdentifier' => 'courseHomePage',
            'pageEntityId'   => $courseHomePageId, // No page entity id for this one
            'extraData'      => array(
                'url'           => get_full_url(),
                'hierarchy'     =>array(
                                    'streamId'      => $displayData['COURSE_HOME_PAGES_LIST'][$courseHomePageId]['streamId'],
                                    'substreamId'   => $displayData['COURSE_HOME_PAGES_LIST'][$courseHomePageId]['substreamId'],
                                    'baseCourseId'  => $displayData['COURSE_HOME_PAGES_LIST'][$courseHomePageId]['baseCourseId'],
                                ),
                "educationType" => $displayData['COURSE_HOME_PAGES_LIST'][$courseHomePageId]['educationType'],
                "deliveryMethod"=> $displayData['COURSE_HOME_PAGES_LIST'][$courseHomePageId]['deliveryMethod'],
                "pageType"      => "CourseHomePage",
                "countryId"     => 2,
            )
        );
        $displayData['beaconTrackData']['extraData']=array_filter($displayData['beaconTrackData']['extraData']);
        $displayData['beaconTrackData']['extraData']['hierarchy'] = array_filter($displayData['beaconTrackData']['extraData']['hierarchy']);
        $this->benchmark->mark('Get_scan_params_start');
        $displayData['gtmParams'] = $this->getScanParams($displayData['beaconTrackData']['extraData'],$displayData['validateuser']);
        $this->benchmark->mark('Get_scan_params_end');

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_CourseHomePage','entity_id'=>$courseHomePageId);
        $displayData['dfpData']  = $dfpObj->getDFPData($loggedinUserInfo, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        //preparing breadcrumbs
        $breadcrumbOptions = array('generatorType' => 'CourseHomePage',
            'options' => array('request' => $this->coursePagesUrlRequest,
                'courseHomePageArray' => $this->courseHomePageList,
                'courseHomePageId' => $courseHomePageId));
        $this->benchmark->mark('Prepare_breadcrumb_start');
        $BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);
        $displayData['breadcrumbHtml'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();
        $this->benchmark->mark('Prepare_breadcrumb_end');
        //below line is used for conversion tracking puprose
        $displayData['trackingPageKeyId'] = 447;
        $displayData['GA_currentPage'] = 'COURSEHOMEPAGE_DESKAnA';
        $displayData['GA_Tap_On_What_Question'] = 'QUESTION_CTA_COURSEHOMEPAGE_DESKAnA';
        $displayData['GA_userLevel'] = (($loggedinUserInfo == 'false') ? 'Non-Logged In' : 'Logged In');
        $displayData['GA_Tap_On_Disc_Link'] = 'DISCUSSION_LINK_COURSEHOMEPAGE_DESKAnA';
        $displayData['GA_Tap_On_Ques_Link'] = 'QUESTION_LINK_COURSEHOMEPAGE_DESKAnA';

        $this->benchmark->mark('Registration_form_populate_start');
        $pageParams = $this->_preparePageBaseParams($courseHomePageId);
        $recatCommonLib = $this->load->library('common/RecatCommonLib');
        $regFormPrefillValues = $recatCommonLib->getRegistrationFormPopulationDataByParams($pageParams);
        $this->benchmark->mark('Registration_form_populate_end');

        $displayData['regFormPrefillValues'] = $regFormPrefillValues;
        $displayData['suggestorPageName'] = "all_tags";

        $this->benchmark->mark('Page_load_total_without_view_end');
        $this->load->view('coursepages/homeTab', $displayData);
        $this->benchmark->mark('Page_load_total_end');
    }

    private function getScanParams($extraData,$userStatus){
        $gtmParams = array();
        $gtmParams['stream']            = $extraData['hierarchy']['streamId'];
        $gtmParams['substream']         = $extraData['hierarchy']['substreamId'];
        $gtmParams['baseCourseId']      = $extraData['hierarchy']['baseCourseId'];
        $gtmParams['cityId']            = $extraData['cityId'];
        $gtmParams['stateId']           = $extraData['stateId'];
        $gtmParams['countryId']         = $extraData['countryId'];
        $gtmParams['educationType']     = $extraData['educationType'];
        $gtmParams['deliveryMethod']    = $extraData['deliveryMethod'];
        $gtmParams['pageType']          = "CourseHomePage";
        $gtmParams = array_filter($gtmParams);
        if($userStatus!='false' && $userStatus[0]['experience']!==""){
            $gtmParams['workExperience'] = $userStatus[0]['experience'];
        }
        return $gtmParams;
    }

    private function _preparePageBaseParams($courseHomePageId){
        $curPage = $this->courseHomePageList[$courseHomePageId];
        $res = array();
        if($curPage['streamId']){
            $res['stream'] = $curPage['streamId'];
            if($curPage['substreamId']){
                $res['substream'] = $curPage['substreamId'];
            }
        }
        if($curPage['baseCourseId']){
            $res['baseCourseId'] = $curPage['baseCourseId'];
        }
        if($curPage['educationType']){
            $res['educationType'] = $curPage['educationType'];
        }
        if($curPage['deliveryMethod']){
            $res['deliveryMethod'] = $curPage['deliveryMethod'];
        }
        return $res;
    }

    public function coursePagesFaq($courseHomePageId) {
        $this->_init();
        $this->_checkAndRedirectCourseFaqPage($courseHomePageId);
        if (!$this->_ifCoursePagesExistForCourseHomePage($courseHomePageId)) {
            show_404();
        }

        $loggedinUserInfo = $this->checkUserValidation();
        $coursePagesSeoDetails = $this->_getSeoDetailsForPage($courseHomePageId);
        $model = $this->load->model('coursepages/coursepagemodel');
        $section_wise_details = $this->coursePageCommonLib->getFaqQuestionsListSortedBySectionAndQuestionOrder($model,$courseHomePageId, 
                                $this->coursePagesUrlRequest, $this->courseHomePageList);
        //_P($section_wise_details);
        $displayData['COURSE_HOME_PAGES_LIST'] = $this->courseHomePageList;
        $displayData['selectedTab'] = 'Faq';
        $displayData['coursePagesSeoDetails'] = $coursePagesSeoDetails;
        $displayData['allTabsSeoDetails'] = $this->coursePagesUrlRequest->getAllTabSeoDetails($courseHomePageId);
        $displayData['courseHomePageId'] = $courseHomePageId;
        $displayData['validateuser'] = $loggedinUserInfo;
        $displayData['ifGutterHelpTextRequired'] = $this->_ifGutterHelpTextRequired($loggedinUserInfo);
        $displayData['subCategoryObj'] = $this->coursePagesUrlRequest->getCategoryInfo($courseHomePageId);
        $displayData['subcatNameForGATracking'] = $displayData['subCategoryObj']->getName();
        $displayData['pageTypeForGATracking'] = 'FAQ';
        $displayData['trackForPages'] = true;
        $displayData['googleRemarketingParams'] = array(
            "categoryId" => $displayData['subCategoryObj']->getparentId(),
            "subcategoryId" => $courseHomePageId,
            "countryId" => 2,
            "cityId" => ''
        );
        
        $displayData['js'] = array('coursepages');
        $displayData['section_wise_details'] = $section_wise_details;

        //
        $index = intval(strip_tags($_REQUEST['index']));
        if ($index > 0 && $index <= (count($section_wise_details) - 1)) {
            $displayData['selected_index'] = $index;
        } else {
            $displayData['selected_index'] = 0;
        }
        global $courseHomeResources;
        //preparing breadcrumbs
          $breadcrumbOptions = array('generatorType' => 'FaqPage',
            'options' => array('request' => $this->coursePagesUrlRequest,
                'courseHomePageArray' => $this->courseHomePageList,
                'courseHomePageId' => $courseHomePageId,
                'resources'=>$courseHomeResources));
        $BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);
        $displayData['breadcrumbHtml'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();

        $displayData['beaconTrackData'] = array(
            'pageIdentifier' => 'courseFaqHomePage',
            'pageEntityId' => 0, // No page entity id for this one
            'extraData' => array(
                'url' => get_full_url(),
                "categoryId" => $displayData['subCategoryObj']->getparentId(),
                "subCategoryId" => $courseHomePageId,
            )
        );
        //   _p($displayData);exit;
        $this->load->view('coursepages/faqTab', $displayData);
    }

    public function permaLinkPage($courseUrlKey, $questionTextinUrl) {

        $this->_init();
        $pos = strrpos($questionTextinUrl, "-");
        if ($pos === false) {
            show_404();
        }

        $questionId = (int) substr($questionTextinUrl, $pos + 1);

        $model = $this->load->model('coursepages/coursepagemodel');
        $questionSectionDetails=$model->getDeletedFaqQuestionsDetails(array($questionId));
        if(empty($questionSectionDetails)){
            show_404();
        }
        $courseHomePageId=$questionSectionDetails[0]['courseHomePageId'];
        $finalFaqUrl=$this->getFaqQuestionUrlToRedirect($courseHomePageId, $questionSectionDetails);
        header("Location: ".$finalFaqUrl,true,301);
        
    }
    private function getFaqQuestionUrlToRedirect($courseHomePageId,$sectionQuestionDetails){
        $faqUrl=$this->coursePagesUrlRequest->getFaqUrlFromCourseHomePageId($courseHomePageId,  $this->courseHomePageList);
        switch ($sectionQuestionDetails[0]['question_status']){
            case 'deleted' :
                switch ($sectionQuestionDetails[0]['section_status']){
                    case 'live':
                        $finalUrl=$faqUrl.'?index='.($sectionQuestionDetails[0]['position']-1);
                        break;
                    default :
                        $finalUrl=$faqUrl;
                        break;
                }
                break;
            default :
                $finalUrl=$faqUrl.'#'.$sectionQuestionDetails[0]['question_id'];
                break;
        }
        return $finalUrl;
    }

    private function _ifGutterHelpTextRequired($loggedinUserInfo = "") {

        if ($loggedinUserInfo == "") {
            $loggedinUserInfo = $this->checkUserValidation();
        }

        // Need not to show for the loggedin user..
        if (is_array($loggedinUserInfo) && $loggedinUserInfo[0]['userid']) {
            return 0;
        }

        // Will show only if the user is first time visitor else not..
        if ($_COOKIE['CPGSFirstTimeVisitor'] != "") {
            return 0;
        } else {
            setcookie("CPGSFirstTimeVisitor", 0, time() + 60 * 60 * 24 * 30, "/", COOKIEDOMAIN);
        }

        return 1;
    }

    public function getDataForWidgets($courseHomePageId, $loggedinUserInfo, $processedWidgetsList, $tagId, $coursePageData) {
        $this->benchmark->mark('Load_widget_aggregator_classes_start');
        $this->load->aggregator('WidgetsAggregator', 'coursepages');
        $classesArray = array('FeaturedInstituteWidget', 'LatestNewsWidget', 'RegistrationWidget', 'TopDiscussionsWidget', 'TopQuestionsWidget', 'FaqWidget','PredictorWidget','CollegeReviewWidget','RankPredictorWidget', 'PopularEntranceExamWidget');
        $this->load->aggregatorClasses($classesArray, 'coursepages');
        $widgetAggregator = new WidgetsAggregator();
        $this->_setAggregatorSources($widgetAggregator, $courseHomePageId, $loggedinUserInfo, $processedWidgetsList, $tagId, $coursePageData);
        $this->benchmark->mark('Load_widget_aggregator_classes_end');
        
        return $widgetAggregator->getData();
    }

    private function _getSeoDetailsForPage($courseHomePageId) {
        $coursePagesSeoDetails = $this->coursePagesUrlRequest->getCoursePagesSeoDetails($courseHomePageId);
        return $coursePagesSeoDetails;
    }

    private function _removeWidgetIfNoDataExists($widgetsList, $widgetsData) {
        $coursePagesWidgetsLib = $this->load->library('coursepages/CoursePagesWidgetsLib');
        $processedWidgetsList = $coursePagesWidgetsLib->removeWidgetIfNoDataExists($widgetsList, $widgetsData);
        return $processedWidgetsList;
    }

    private function _getWidgetListInfoForPage($courseHomePageId, $isUserLoggedin) {
        $widgetsList = $this->coursePagesRepository->getWidgetListForCoursePage($courseHomePageId);
        //_P($widgetsList);

        $coursePagesWidgetsLib = $this->load->library('coursepages/CoursePagesWidgetsLib');
        $processedWidgetsList = $coursePagesWidgetsLib->processWidgetsList($widgetsList, $isUserLoggedin);
        return $processedWidgetsList;
    }

    public function loadCoursePageTabsHeader($courseHomePageId, $selectedTab, $showFloatingBar = TRUE, $backLinkArray = array(), $indentCPGSHeader = FALSE, $tabData = FALSE, $extraParams = array()) {
        $validCoursePageTabs = array('Home', 'Institutes', 'AskExperts', 'Discussions', 'News');
        $this->_init();
        if (!$this->_ifCoursePagesExistForCourseHomePage($courseHomePageId)) {
            return;
        }


        $coursePagesSeoDetails = $this->coursePagesUrlRequest->getCoursePagesTabsUrls($courseHomePageId, $backLinkArray['CUSTOMIZED_TABS_BAR']);

        $this->hideTabsifNoDataExists($coursePagesSeoDetails, $courseHomePageId);

        $displayData['coursePagesSeoDetails'] = $coursePagesSeoDetails;
        $displayData['COURSE_HOME_PAGES_LIST'] = $this->courseHomePageList;
        $displayData['selectedTab'] = $selectedTab;

        if (in_array($selectedTab, $validCoursePageTabs) && $indentCPGSHeader === TRUE) {
            $displayData['indentCPGSHeader'] = TRUE;
        } else {
            $displayData['indentCPGSHeader'] = FALSE;
        }

        $displayData['courseHomePageId'] = $courseHomePageId;
        $displayData['backLinkArray'] = $backLinkArray;
        $displayData['exampageExamNameLabel'] = (isset($extraParams['exampageExamNameLabel']) && $extraParams['exampageExamNameLabel'] != '') ? $extraParams['exampageExamNameLabel'] : '';
        $displayData['ifGutterHelpTextRequired'] = $this->_ifGutterHelpTextRequired();
        if (!$showFloatingBar) {
            $displayData['isFloatingBar'] = FALSE;
        }
        $displayData['tabData'] = $tabData;

        $coursePagesTabsHtml = $this->load->view('coursepages/coursePagesTabsBar', $displayData, TRUE);
        if ($showFloatingBar) {
            $coursePagesTabsHtml .= $this->load->view('coursepages/coursePagesFloatingTabsBar', $displayData, TRUE);
        }

        if (isset($backLinkArray['AUTOSCROLL']) && $backLinkArray['AUTOSCROLL'] == 1) {
            $coursePagesTabsHtml .= "<script>isCPGSAutoScrollEnabled = 1;</script>";
        }
        echo $coursePagesTabsHtml;
    }

    private function _ifCoursePagesExistForCourseHomePage($courseHomePageId) {

        $this->load->helper('coursepages/course_page');
        if (!$this->coursePagesUrlRequest->isValidNumber($courseHomePageId) || empty($this->courseHomePageList[$courseHomePageId])) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    private function _setAggregatorSources($object, $courseHomePageId, $loggedinUserInfo, $widget_list, $tagId, $coursePageData) {

        $cat_object = $this->coursePagesUrlRequest->getCategoryInfo($courseHomePageId);

        $params = array("courseHomePageId" => $courseHomePageId, "userInfo" => $loggedinUserInfo, "categoryId" => $cat_object->getParentId(), 'tagId' => $tagId, 'coursePageData'=>$coursePageData
                ,'courseHomePageList'=>  $this->courseHomePageList,
            'courseUrlRequestObject'=>  $this->coursePagesUrlRequest,
            'courseCommonLibObject'=>  $this->coursePageCommonLib);

        if (count($widget_list) > 0) {
            foreach ($widget_list as $value) {
                foreach ($value as $widget_object) {
                    $widget_class = $widget_object->getWidgetKey();
                    if (class_exists($widget_class)) {
                        $object->addDataSource(new $widget_class($params));
                    }
                }
            }
        }
    }

    public function loadRegistrationWidget($courseHomePageId) {
        $currentReferrerUrl = $_POST['currentReferrerUrl'];
        echo Modules::run('registration/Forms/LDB', NULL, NULL, array('registrationSource' => 'COURSEPAGES_HOME_RIGHTPANEL_REGISTRATION', 'referrer' => $currentReferrerUrl, 'coursePageSubcategoryId' => $courseHomePageId, 'tracking_keyid' => DESKTOP_NL_COURSE_HOME_PAGE_RIGHT_REG));
    }

    function hideTabsifNoDataExists(&$coursePagesSeoDetails, $courseHomePageId) {

        $subcat_array = array_keys($this->courseHomePageList);
        $cache = $this->load->library('coursepages/cache/CoursePagesCache');
        $ana_model = $this->load->model('messageBoard/qnamodel');
        $article_model = $this->load->model('blogs/articlemodel');

        // $questions_count = $cache->getQuestionsCount();
        $discussions_count = $cache->getDiscussionsCount();
        $articles_count = $cache->getArticlesCount();

        // if (empty($questions_count)) {
        //     $questions_count = $ana_model->getQuestionsCountForSubcategories($subcat_array);
        //     $cache->storeQuestionsCount($questions_count);
        // }

        // if (!array_key_exists($courseHomePageId, $questions_count) || empty($questions_count[$courseHomePageId])) {
        //     unset($coursePagesSeoDetails['AskExperts']);
        // }

        if ($discussions_count === false) {
            $discussions_count = $ana_model->getDiscussionsCountForSubcategories($subcat_array);
            $cache->storeDiscussionsCount($discussions_count);
        }

        if (!array_key_exists($courseHomePageId, $discussions_count) || empty($discussions_count[$courseHomePageId])) {
            unset($coursePagesSeoDetails['Discussions']);
        }

        if (empty($articles_count)) {
            $articles_count = $article_model->getArticlesCountForSubcategories($subcat_array);
            $cache->storeArticlesCount($articles_count);
        }

        if (!array_key_exists($courseHomePageId, $articles_count) || empty($articles_count[$courseHomePageId])) {
            unset($coursePagesSeoDetails['News']);
        }
    }

    private function _checkFor301RedirectOfPermaLinkPage($model, $questionId) {

        ob_start();

        if ($this->coursePagesUrlRequest->isValidNumber($questionId)) {

            $deleted_question_details = $model->getDeletedFaqQuestionsDetails(array($questionId));

            if (count($deleted_question_details) > 0) {

                $question_status = $deleted_question_details[$questionId]['question_status'];
                $section_status = $deleted_question_details[$questionId]['section_status'];
                $section_position = $deleted_question_details[$questionId]['position'];
                $courseHomePageId = $deleted_question_details[$questionId]['courseHomePageId'];
                $base_url = $this->coursePagesUrlRequest->getFaqTabUrl($courseHomePageId);

                if ($question_status == 'deleted') {

                    if ($section_status == 'deleted') {

                        $redirect_url = $base_url;
                        $faq_data =$this->coursePageCommonLib->getFaqQuestionsOnHomePageByCourseHomePageId($model,$courseHomePageId, 
                                $this->coursePagesUrlRequest, $this->courseHomePageList);
                        if (empty($faq_data)) {
                            $redirect_url = $this->coursePagesUrlRequest->getHomeTabUrl($courseHomePageId);
                        }
                    } else if ($section_status == 'live') {

                        $redirect_url = $base_url . "?index=" . ($section_position - 1);
                    }

                    ob_clean();
                    Header("HTTP/1.1 301 Moved Permanently");
                    Header("Location: $redirect_url");
                    exit();
                }
            }
        }
    }

    /*
     * Get All multilocation values for a course
     * @params : CourseId
     */

    public function getMultilocationsForCourse() {

        $courseId = $this->input->post('courseId', true);

        $this->load->model('listing/institutemodel');
        $institutemodel = new institutemodel;
        $LocationDetails = $institutemodel->getMultilocationsForSingleInstitute($courseId);

        foreach ($LocationDetails as $k => $v) {
            if ($v['locality_name'] == '') {
                $v['locality_name'] = 'All Localities';
            }
            $localityArray[$v['course_id']][$v['city_id']]['name'] = $v['city_name'];
            $localityArray[$v['course_id']][$v['city_id']]['localities'][$v['locality_id']]['name'] = $v['locality_name'];
        }

        echo json_encode($localityArray);
        return;
    }

    private function _questionDiscussionResourcePageRedirection($subCategoryId, $threadType) {
        if (empty($subCategoryId) || $subCategoryId <= 0 || !in_array($threadType, array('question', 'discussion'))) {
            return;
        }
        if ($this->coursePagesUrlRequest->getTagId($subCategoryId) == -1) {
            show_404();
        }
        $tagDetailPageSeoUrl = getSeoUrl($this->coursePagesUrlRequest->getTagId($subCategoryId), 'tag', $this->coursePagesUrlRequest->getTagName());
        if ($threadType == 'discussion') {
            $tagDetailPageSeoUrl .= '?type=discussion';
        }
        header('Location: ' . $tagDetailPageSeoUrl, TRUE, 301);
        exit();
    }

    public function deleteCoursePageCache($courseHomePageId, $type) {
        $cache = $this->load->library('coursepages/cache/CoursePagesCache');
        $cache->$type($courseHomePageId);
    }

    public function deleteCoursePageCacheByWidget($widget){
        $this->_init();
        $cache = $this->load->library('coursepages/cache/CoursePagesCache');
        
        $tagIds = array_map(function($ele){return $ele['tagId'];},$this->courseHomePageList);
        foreach ($tagIds as $id) {
            switch($widget){
                case 'TopQuestionsWidget':
                    $cache->deleteQuestionsData($id);
                    break;
                case 'LatestNewsWidget':
                    $cache->deleteArticlesData($id);
                    break;
                case 'TopDiscussionsWidget':
                    $cache->deleteDiscussionsData($id);
                    break;
            }
        }
        _p('Done');
    }

    public function getArticleWidgetForCourseHomePage($courseHomePageId){
        $this->_init();
        $this->load->aggregator('WidgetsAggregator', 'coursepages');
        $this->load->aggregatorClasses(array('LatestNewsWidget'),'coursepages');
        $class = new LatestNewsWidget(array('coursePageData' => $this->courseHomePageList[$courseHomePageId]));
        $widgetData = $class->getWidgetData();
        return $widgetData['data']['articleList'];
    }

    public function getRegistrationFormPopulationData(){
        $params = $this->input->post('params');
        $params = json_decode($params,true);
        $lib = $this->load->library('common/RecatCommonLib');
        $data = $lib->getRegistrationFormPopulationDataByParams($params);
        echo json_encode($data);
    }

    public function prepareClosedSearchUrl(){
        $mode = $this->input->post('Mode_of_study');
        $lib = $this->load->library('listingBase/BaseAttributeLibrary');
        $data = $lib->getAttributeNameByValueId($mode);
        $finalMode = array();
        foreach($data as $id => $type){
            if($type == "Education Type"){
                $finalMode[] = "et::".$id;
            }else{
                $finalMode[] = "dm::".$id;
            }
        }
        $this->load->config('CoursePageConfig');
        $remapCourses = $this->config->item('registrationBaseCourseRemapping');
        $courses = $_POST['Course'];
        $finalCourses = array();
        $courseLevels = array();
        $credentials = array();
        $changeRequired = false;
        foreach($courses as $course){
            if(in_array($course, array_keys($remapCourses))){
                $changeRequired = true;
                if(!empty($remapCourses[$course]['cr'])) $credentials[] = $remapCourses[$course]['cr'];
                if(!empty($remapCourses[$course]['cl'])) $courseLevels[] = $remapCourses[$course]['cl'];
            }else{
                $finalCourses[] = $course;
            }
        }
        if($changeRequired){
            $_POST['Course'] = $finalCourses;
            $_POST['Credential'] = $credentials;
            $_POST['Course_Level'] = $courseLevels;
        }

        $_POST['Mode_of_study'] = $finalMode;
        $_POST['requestFrom'] = "externalPage";
        if(empty($_POST['Course'])){
            unset($_POST['Course']);
        }
        modules::run('search/SearchV3/createClosedSearchUrl');
    }

    private function _checkForOldUrlRedirection($courseUrlKey,$response){
        $courseUrlKey = str_replace('-questions', '', str_replace('-faq', '', str_replace('-news', '', str_replace('-articles', '', $courseUrlKey))));
        switch($courseUrlKey){
            case 'mba':
                $response['courseHomePageId'] = 1;
                break;
            case 'distance-mba':
                $response['courseHomePageId'] = 3;
                break;
            case 'online-mba':
                $response['courseHomePageId'] = 2;
                break;
            case 'par-time-mba':
                $response['courseHomePageId'] = 4;
                break;
            case 'executive-mba':
                $response['courseHomePageId'] = 5;
                break;
            case 'engineering':
                $response['courseHomePageId'] = 6;
                break;
            case 'hotel-management':
                $response['courseHomePageId'] = 10;
                break;
        }
        return $response;
    }

}
