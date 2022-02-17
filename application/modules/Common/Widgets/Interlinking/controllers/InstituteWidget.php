<?php

class InstituteWidget extends MX_Controller {

    public function __construct(){
        $this->interlinkingLibrary = $this->load->library('Interlinking/InterlinkingLibrary');
        
        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();

        $this->load->helper(array('image'));
    }

    private function _init() {
        $this->anarecommendationlib = $this->load->library('ContentRecommendation/AnARecommendationLib');
        $this->articlerecommendationlib = $this->load->library('ContentRecommendation/ArticleRecommendationLib');
        $this->reviewrecommendationlib = $this->load->library('ContentRecommendation/ReviewRecommendationLib');
		
		$this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();
        $this->load->helper(array('mAnA5/ana'));
    }

    /*  Input Format -
        entityIds['exam'] => array of unique exam ids
        entityIds['course'] => array of unique base course ids
        entityIds['university'] => array of unique university ids
        entityIds['college'] => array of unique college ids

        Output -
        View of widget
     */
    public function getRelatedInstituteWidget($entityIds, $pageType, $entityId = null,$entityType = null,$ampViewFlag = false) {
        /*$entityIds['exam'] = array('3275', '306', '307', '309', '327', '9211');
        $entityIds['course'] = array('101', '26', '30', '10', '102');
        $entityIds['university'] = array('20576', '24642', '24752', '4026');
        $entityIds['college'] = array('19333', '843', '23539', '25138');
        */
        $this->benchmark->mark('Bottom_Institute_Interlinking_Widget_start');

        $data['instituteIds'] = $this->interlinkingLibrary->getRelatedInstitutes($entityIds);
        
        $formattedData = $this->formatData($data, $pageType);
        $formattedData['entityId'] = $entityId;
        $formattedData['entityType'] = $entityType;

        if(!empty($data)) {
            //View load mobile
            if(isMobileRequest()) {
                if($ampViewFlag){
                    $this->load->view('mobile_listing5/institute/widgets/ampRecommendation',$formattedData);
                }
                else{
                    $this->load->view('mobile_listing5/institute/widgets/recommendation',$formattedData);
                }
            }
            else { //View load desktop
                $this->load->view('nationalInstitute/InstitutePage/RecommendedCollegeWidget',$formattedData);
            }
        }
        $this->benchmark->mark('Bottom_Institute_Interlinking_Widget_end');
    }

    public function getInstitutesWidget($entityIds, $pageType, $instituteCardLimit, $courseLimitPerInstitute,$ampViewFlag=false,$entityId = null,$entityType = null) {
        $this->benchmark->mark('RHS_Institute_Interlinking_Widget_start');
        if(empty($entityIds)) {
            return;
        }

        $this->_init();

        if(!empty($entityIds['college']) && !empty($entityIds['university'])) {
            $instituteIds = array_merge($entityIds['college'], $entityIds['university']);
        }else if (!empty($entityIds['college'])) {
            $instituteIds = $entityIds['college'];
        }else if (!empty($entityIds['university'])) {
            $instituteIds = $entityIds['university'];
        }else if(empty($entityIds['exam'])){
            return;
        }
        
        // examIds for exam card limit 4
        if($pageType == 'questionDetailPage' && !empty($entityIds['exam'])){
            $examLimit = 4;
            $collegeCount  = count(array_slice($instituteIds, 0, $instituteCardLimit));
            $examCardLimit = ($collegeCount>0) ? ($examLimit - $collegeCount) : $examLimit;
            $entityExamIds = array_slice($entityIds['exam'], 0, $examCardLimit);
        }
        
        //Get AnA count
        $anaCount = $this->anarecommendationlib->getInstituteAnaCounts($instituteIds, 'question');

        //Get Article count
        $articleCount = $this->articlerecommendationlib->getInstituteArticleCounts($instituteIds);

        //Get Review count
        $reviewCount = $this->reviewrecommendationlib->getInstituteReviewCounts($instituteIds);

        //Get Admission link & prepare data
        foreach ($instituteIds as $key => $instituteId) {
            $instituteObj = $this->instituteRepo->find($instituteId,array('media'));
            $showAdmissionLink = $instituteObj->isAdmissionDetailsAvailable();

            if(empty($anaCount[$instituteId]) && empty($articleCount[$instituteId]) && $reviewCount[$instituteId] < 3 && !$showAdmissionLink) {
                //skip this institute
            } else {
                if($instituteCardLimit == $count) {
                    break;
                }
                $mainLocationObjCheck = $instituteObj->getMainLocation();
                if(empty($mainLocationObjCheck))
                    continue;
                $widgetInstituteIds[] = $instituteId;
                $widgetInstituteData[$instituteId]['instituteId'] = $instituteId;
                $widgetInstituteData[$instituteId]['instituteName'] = $instituteObj->getName();
                $widgetInstituteData[$instituteId]['instituteUrl'] = $instituteObj->getURL();
                $widgetInstituteData[$instituteId]['mainLocation']['locality'] = $instituteObj->getMainLocation()->getLocalityName();
                $widgetInstituteData[$instituteId]['mainLocation']['city'] = $instituteObj->getMainLocation()->getCityName();
                $widgetInstituteData[$instituteId]['mainLocation']['state'] = $instituteObj->getMainLocation()->getStateName();

                $widgetInstituteData[$instituteId]['allCoursesUrl'] = $instituteObj->getURL().'/courses';
                
                $widgetInstituteData[$instituteId]['anaCount'] = $anaCount[$instituteId];
                $widgetInstituteData[$instituteId]['anaUrl'] = $instituteObj->getURL().'/questions';
                
                $widgetInstituteData[$instituteId]['articleCount'] = $articleCount[$instituteId];
                $widgetInstituteData[$instituteId]['articleUrl'] = $instituteObj->getURL().'/articles';

                $widgetInstituteData[$instituteId]['reviewCount'] = $reviewCount[$instituteId];
                $widgetInstituteData[$instituteId]['reviewUrl'] = $instituteObj->getURL().'/reviews';
                
                $widgetInstituteData[$instituteId]['showAdmissionLink'] = $showAdmissionLink;
                $widgetInstituteData[$instituteId]['admissionUrl'] = $instituteObj->getURL().'/admission';
                $widgetInstituteData[$instituteId]['listingType'] = $instituteObj->getType();

                $headerImage = $instituteObj->getHeaderImage();
                // if header image exists get its variant otherwise use default image
                if($headerImage && $headerImage->getUrl()){
                    $imageLink = $headerImage->getUrl();
                    $imageUrl = getImageVariant($imageLink,6);
                }
                else{
                    if(isMobileRequest()){
                        $imageUrl = MEDIAHOSTURL."/public/mobile5/images/recommender_dummy.png";
                    }
                    else{
                        $imageUrl = MEDIAHOSTURL."/public/images/recommend_dummy.png";
                    }
                }

                $widgetInstituteData[$instituteId]['imageUrl'] = $imageUrl;

                $count++;
            }
        }

        //get top courses
        $instituteWiseCourses = $this->interlinkingLibrary->getInstituteTopCoursesByFilters($widgetInstituteIds, $entityIds, $courseLimitPerInstitute);
        
        foreach ($instituteWiseCourses['topCourses'] as $instituteId => $courseIds) {
            $courseObjs = $this->courseRepo->findMultiple($courseIds);
            foreach ($courseObjs as $key => $courseObj) {
                $courseId = $courseObj->getId();
                $courseName = $courseObj->getName();
                $courseUrl = $courseObj->getURL();
                $widgetInstituteData[$instituteId]['topCourses'][] = array('id' => $courseId, 'name' => $courseName, 'url' => $courseUrl);
                $widgetInstituteData[$instituteId]['allCoursesCount'] = $instituteWiseCourses['courseCount'][$instituteId];
            }
        }

        $displayData['widgetInstituteData'] = $widgetInstituteData;
        $displayData['pageType'] = $pageType;
        $displayData['entityId'] = $entityId; 
        $displayData['entityType'] = $entityType; 

		if($pageType == 'questionDetailPage' && !empty($entityExamIds)){
            $this->benchmark->mark('RHS_EXAM_CARD_Interlinking_Widget_start');
            $displayData['examCardData'] = $this->getExamCardData($entityExamIds);
            $this->benchmark->mark('RHS_EXAM_CARD_Interlinking_Widget_end');
        }
        
        $this->setTrackingKeyIdForEntityWidget($displayData,$pageType);
        if(!empty($widgetInstituteData) || !empty($displayData['examCardData'])){
            if(isMobileRequest()){
                if($ampViewFlag){
                    echo $this->load->view('Interlinking/ampInstituteCardWidget',$displayData);
                }
                else{
                    echo $this->load->view('Interlinking/minstituteCardWidget',$displayData);
                }
            }
            else{
                echo $this->load->view('Interlinking/InstituteCardWidget',$displayData);
            }
        }
        $this->benchmark->mark('RHS_Institute_Interlinking_Widget_end');
    }

    function getExamCardData($entityIds, $pageType='', $cardLimit=4){
        if(empty($entityIds)){
            return;
        }
      
        $this->load->builder('ExamBuilder','examPages');
        $examBuilder          = new ExamBuilder();
        $this->examRepository = $examBuilder->getExamRepository();
        $this->examRequest    = $this->load->library('examPages/ExamPageRequest');
        $this->examPageLib    = $this->load->library('examPages/ExamPageLib');

        $examRepo = $this->examRepository->findMultiple($entityIds);
        $this->load->config('examPages/examPageConfig.php');
        $sectionNameMappings = $this->config->item('sectionNamesMapping');
        $examCardSection     = array('importantdates','syllabus','results','cutoff');
    
        foreach ($examRepo as $key => $examObj) {
            $isGetSamplePaper = false;
            $primaryGroup     = $examObj->getPrimaryGroup();
            $examId           = $examObj->getId();
            $examName         = $examObj->getName();

            $primaryGroupId   = $primaryGroup['id'];
            $examContentObj   = $this->examRepository->findContent($primaryGroupId);
            
            $groupObj  = $this->examRepository->findGroup($primaryGroupId);
            if(!empty($groupObj) && is_object($groupObj)){
                $mapping   = $groupObj->getEntitiesMappedToGroup();
                $groupYear = $mapping['year'][0];
            }

            $examSections     = $examContentObj['sectionname'];
            $this->examRequest->setExamName($examName);
            $rankingPageUrl         = $this->getExamRankingPageUrl($examId, $examName);
            $rankingPageUrl         = addingDomainNameToUrl(array('url'=>$rankingPageUrl,'domainName'=>SHIKSHA_HOME));
            $anaData = $this->examPageLib->getAnsweredQuestionCount($examId);
            $guideDownloaded = $this->examPageLib->checkActionPerformedOnGroup($primaryGroupId,'examGuide');
            if($rankingPageUrl){
                $colgAcceptingLabel = 'Top Colleges Accepting '.$examName;
                $colgAcceptingUrl   = $rankingPageUrl;
            }else{
                $collegeAcceptingData  = $this->getCollegeAcceptingUrl($examId, $examName);
                $colgAcceptingLabel = 'Top Colleges Accepting '.$examName.' ('.$collegeAcceptingData['headingText'].')';
                $colgAcceptingUrl   = $collegeAcceptingData['srpUrl'];
            }
            
            foreach ($examSections as $secKey => $secVal) {
                if($secVal == 'samplepapers'){
                    $isGetSamplePaper = true;
                }
                if(in_array($secVal, $examCardSection)){
                    $secUrl  = $this->examRequest->getUrl($secVal,true);
                    $secName = $sectionNameMappings[$secVal];
                    $sectionData[] = array('name'=>$examName.' '.$groupYear.' '.$secName,
                                            'url'=>$secUrl);
                }
            }
            $sectionData[] = array('name'=>$colgAcceptingLabel,
                                    'url'=>$colgAcceptingUrl);
            $data[] = array('examName'=> $examName.' '.$groupYear,
                                        'url'=>$examObj->getUrl(),
                                        'anaData'=>$anaData,
                                        'totalQuest'=>0,
                                        'sections'=>$sectionData,
                                        'groupId'=>$primaryGroupId,
                                        'isGetSamplePaper'=>$isGetSamplePaper,
                                        'isGuideDownloaded'=>$guideDownloaded
                                        );
            unset($sectionData);
        }
        return $data;
    }

    function getCollegeAcceptingUrl($examIds, $examName){
        $this->ExamPageCache = $this->load->library('examPages/cache/ExamPageCache');
        $courseInstitutesMapping = $this->ExamPageCache->getCourseAcceptingExam($examIds);
        
        if(!empty($courseInstitutesMapping)) 
        {
            $courseInstitutesMappingObject = json_decode($courseInstitutesMapping);
            $courseInstitutesMapping = array();
            $courseInstitutesMapping['instCourseMapping'] = (array) $courseInstitutesMappingObject->instCourseMapping;
            $courseInstitutesMapping['totalCount'] = $courseInstitutesMappingObject->totalCount;
        }else{
            $instituDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
            $courseInstitutesMapping = $instituDetailLib->getInstitutesFromExams($examIds,1,'examPage');
            $this->ExamPageCache->storeCourseAcceptingExam($examIds,json_encode($courseInstitutesMapping));
        }   

        $data = array();

        $examId = $examIds;
        if(!empty($courseInstitutesMapping['instCourseMapping']) && !empty($courseInstitutesMapping['totalCount']))
        {
            $data['totalCount'] = $courseInstitutesMapping['totalCount'];

            if($data['totalCount'] >= 1000)
            {
                $data['headingText'] = floor(($data['totalCount']/1000)).'k+'; 
            }
            elseif($data['totalCount'] >= 100)
            {
                $data['headingText'] = (floor(($data['totalCount']/100)) * 100) .'+';   
            }elseif($data['totalCount'] > 10)
            {
                $data['headingText'] = (floor(($data['totalCount']/10)) * 10 ).'+';
            }
            else
            {
                $data['headingText'] = $data['totalCount'];
            }

            if($data['totalCount'] > 0 && !empty($examName))
            {
                $data['srpUrl'] = $this->ExamPageCache->getLinkForAcceptingExam($examId);

                if(empty($data['srpUrl']))
                {
                    $data['srpUrl'] = Modules::run('search/SearchV3/createOpenSearchUrl',array('keyword' => $examName, 'requestFrom' => 'examAcceptingWidget','examId' => $examId,'isQuer' => false),true);
                    $this->ExamPageCache->storeLinkForAcceptingExam($examId,json_encode($data['srpUrl']));
                }
                else
                {
                    $data['srpUrl'] = json_decode($data['srpUrl']);                    
                }
            }
        }
        return $data;
    }

    function getExamRankingPageUrl($examId, $examName){
        if(empty($examId) || empty($examName)){
            return;
        }
        $this->exampagemodel = $this->load->model('examPages/exampagemodel');
        $rankingPageId       = $this->exampagemodel->getExamRankingPageId($examId);
        $paramArray['examId']   = $examId;
        $paramArray['examName'] = $examName;
        $paramArray['rankingPageId'] = $rankingPageId;
        $this->load->builder('rankingV2/RankingPageBuilder');
        $builder = new RankingPageBuilder();
        $rankingURLManager  = $builder->getURLManager();
        $rankingPageRequest = $rankingURLManager->getRankingPageRequestFromDataArray($paramArray);
        $rankingPageUrl     = $rankingURLManager->buildURL($rankingPageRequest);
        return $rankingPageUrl;
    }

    private function setTrackingKeyIdForEntityWidget(&$data, $pageType){
        if(isMobileRequest()){
            switch($pageType){
                case 'articleDetailPage':
                    $data['widgetTrackingKeyId'] = 1277;
                    break;
                case 'questionDetailPage':
                    $data['widgetTrackingKeyId'] = 1367;
                    break;
            }
        }
        else{
            switch($pageType){
                case 'articleDetailPage':
                    $data['widgetTrackingKeyId'] = 1276;
                    break;
                case 'questionDetailPage':
                    $data['widgetTrackingKeyId'] = 1372;
                    break;
            }
        }
    }

    private function formatData($data,$pageType) {
        $formattedData = array();
        //Common part
        $instituteObjs = array();$result = array();
        if(!empty($data['instituteIds'])) {
            $instituteObjs = $this->instituteRepo->findMultiple($data['instituteIds'], array('basic','media'));
            foreach ($instituteObjs as $instituteId => $instituteObj) {
                $temp = $this->prepareRecommendedCollegeData($instituteObj);
                if(!empty($temp)){
                    $result[] = $temp;
                }
            }
            if(!empty($result)){
                $formattedData['RecommendedListingData'] = $result;
                $this->setWidgetHeading($formattedData,$pageType,'institute');
                $this->setTrackingKeyIds($formattedData,$pageType);

                $formattedData['widgetType'] = "alsoViewed";
                $formattedData['listing_type'] = "institute";
                $formattedData['fromPage'] = $pageType; 
                $formattedData['GA_Tap_On_Reco'] = "Similar_Institutes";
                $formattedData["GA_deb_attr"] = 'DEB_Similar_Institutes';
                $formattedData["pageType"] = $pageType;
            }
        }

        //Mobile only
        if(isMobileRequest()) {
            $formattedData['GA_Device'] = 'Mobile';
        } 
        //Desktop only
        else {
            $formattedData['GA_Device'] = 'Desktop';
        }

        $formattedData["GA_optlabel"] = $formattedData['GA_Device'].'_Similar_Institutes';

        return $formattedData;
    }

    private function prepareRecommendedCollegeData($instituteObj){
        $id = $instituteObj->getId(); $temp = array();
        if(empty($id)){
            return $temp;
        }

        $mainLocationObj = $instituteObj->getMainLocation();
        if(!empty($mainLocationObj)){
            $main_location = $instituteObj->getMainLocation()->getCityName();
        }

        $headerImage = $instituteObj->getHeaderImage();
        // if header image exists get its variant otherwise use default image
        if($headerImage && $headerImage->getUrl()){
            $imageLink = $headerImage->getUrl();
            $imageUrl = getImageVariant($imageLink,6);
        }
        else{
            if(isMobileRequest()){
            $imageUrl = MEDIAHOSTURL."/public/mobile5/images/recommender_dummy.png";
        }
            else{
                $imageUrl = MEDIAHOSTURL."/public/images/recommend_dummy.png";
            }
        }

        $result = array(
                'institute_name'       => $instituteObj->getName(),
                'institute_id'         => $instituteObj->getId(),
                'institute_url'        => $instituteObj->getURL(),
                'image_url'            => $imageUrl,
                'main_location'        => $main_location,
                'establish_year'       => $instituteObj->getEstablishedYear(),
                'is_autonomous'        => $instituteObj->isAutonomous(),
                'isNationalImportance' => $instituteObj->isNationalImportance(),
                'listingType'          => $instituteObj->getType()
                );

        return $result;
    }

    private function setTrackingKeyIds(&$data,$pageType){
        if(isMobileRequest()){
            switch ($pageType) {
                case 'articleDetailPage':
                    $data['widgetTrackingKeyId'] = 1266;
                    break;
            }
        }
        else{
            switch($pageType){
                case 'articleDetailPage':
                    $data['widgetTrackingKeyId'] = 1265;
                    break;
            }
        }
    }

    private function setWidgetHeading(&$data,$pageType,$widgetType){
        switch ($pageType) {
            case 'articleDetailPage':
                $widgetHeading = 'Colleges you may be interested in';
                break;
        }
        $data['widgetHeading'] = $widgetHeading;
    }
}
