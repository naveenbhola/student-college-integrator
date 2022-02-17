<?php
class InterlinkingLibrary {
    private $CI;
    private $maxCards = 16;

    public function __construct() {
        $this->CI = & get_instance();

        $this->alsoviewed = $this->CI->load->library('recommendation/alsoviewed');
        $this->instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');
        $this->examMainLib = $this->CI->load->library('examPages/ExamMainLib');
        $this->baseAttributeLibrary = $this->CI->load->library('listingBase/BaseAttributeLibrary');

        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $this->locationRepository = $locationBuilder->getLocationRepository();

        $this->CI->load->config("nationalCategoryList/nationalConfig");
        $this->fieldAlias = $this->CI->config->item('FIELD_ALIAS');
    }

    /*  Input Format -
        entityIds['exam'] => array of unique exam ids
        entityIds['course'] => array of unique base course ids
        entityIds['university'] => array of unique university ids
        entityIds['college'] => array of unique college ids

        Output -
        $data['instituteIds'] => array of unique institute ids
     */
    function getRelatedInstitutes($entityIds) {
        $instituteIds = array();

        //Institute Tag = Yes (LF-6882)
        if(!empty($entityIds['university']) || !empty($entityIds['college'])) {
            //get remaining institutes from also viewed
            $this->CI->benchmark->mark('Bottom_Institute_Interlinking_Also_Viewed_start');
            $instituteIds = $this->getInstitutesWithAlsoViewed($entityIds);
            $this->CI->benchmark->mark('Bottom_Institute_Interlinking_Also_Viewed_end');
        } else {
            //CASE 2: Exam Tag = Yes, Institute Tag = No
            if(!empty($entityIds['exam'])) {
                //get institutes from exam
                $this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Exam_start');
                $instituteIds = $this->instituteDetailLib->getInstitutesFromExams($entityIds['exam'], $this->maxCards);
                $this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Exam_end');
            }

            //CASE 4 : Exam Tag = No, Institute Tag = No, Base Course Tag = Yes (LF-6885)
            else if (!empty($entityIds['course'])) {
                //get institutes from base course
                $this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Base_Course_start');
                $instituteIds = $this->instituteDetailLib->getInstitutesFromBaseCourses($entityIds['course'], $this->maxCards);
                $this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Base_Course_end');
            }
        }
        
        return $instituteIds;
    }

    /*  Input Format -
        entityIds['exam'] => array of unique exam ids
        entityIds['course'] => array of unique base course ids
        entityIds['university'] => array of unique university ids
        entityIds['college'] => array of unique college ids

        Output -
        $data['examIds'] => array of unique exam ids
     */
    function getRelatedExams($entityIds) {
        $examIds = array();

        if(!empty($entityIds['exam'])) {
            $examIds = array_slice($entityIds['exam'], 0, $this->maxCards);
        }
        else if (!empty($entityIds['course'])) {
            //get exams from base course (LF-6885)
            $examIds = $this->examMainLib->getExamWithExamPagesByBaseCourses($entityIds['course']);
            $examIds = array_slice($examIds, 0, $this->maxCards);
        }
        
        return $examIds;
    }

    private function getInstitutesWithAlsoViewed($institutes) {
        foreach ($institutes['college'] as $key => $value) {
            if($key == 0) {
                $listingId = $value;
                $listingType = 'institute';
            }
            $instituteIds[] = $value;
        }

        foreach ($institutes['university'] as $key => $value) {
            if(!isset($listingId) && $key == 0) {
                $listingId = $value;
                $listingType = 'university';
            }
            $instituteIds[] = $value;
        }

        //get also viewed institutes, max 15
        if(count($instituteIds) < $this->maxCards) {
            if($listingType == 'institute') {
                $remainingInstitutes = $this->alsoviewed->getAlsoViewedInstitutes(array($listingId), '15');
            } else {
                $remainingInstitutes = $this->alsoviewed->getAlsoViewedUniversities(array($listingId), '15');
            }
        }
        
        //remove previous institutes from also viewed institutes
        $remainingInstitutes = array_diff($remainingInstitutes, $instituteIds);

        if(!empty($remainingInstitutes)) {
            $instituteIds = array_slice(array_merge($instituteIds, $remainingInstitutes), 0, $this->maxCards);
        } else {
            $instituteIds = array_slice($instituteIds, 0, $this->maxCards);
        }
        
        return $instituteIds;
    }

    public function getInstituteTopCoursesByFilters($instituteIds, $filterEntityIds, $courseLimitPerInstitute) {
        if(empty($instituteIds)) {
            return;
        }
        if(!empty($filterEntityIds['primaryHierarchy']['stream'])) {
            $filterEntityIds['streamIds'] = array($filterEntityIds['primaryHierarchy']['stream']);

            if(count($filterEntityIds['primaryHierarchy']['specialization']) == 1 && !empty($filterEntityIds['primaryHierarchy']['specialization'])) {
                $filterEntityIds['specializationIds'] = array($filterEntityIds['primaryHierarchy']['specialization']);
            }
            
            if(!empty($filterEntityIds['specializationIds']) || (count($filterEntityIds['primaryHierarchy']['substream']) == 1 && !empty($filterEntityIds['primaryHierarchy']['substream']))) {
                $filterEntityIds['substreamIds'] = array($filterEntityIds['primaryHierarchy']['substream']);
            }
        }
        unset($filterEntityIds['primaryHierarchy']);
        unset($filterEntityIds['otherAttribute']);
        unset($filterEntityIds['university']);
        unset($filterEntityIds['college']);
        
        $courseData = $this->instituteDetailLib->getInstituteTopCoursesByFilters($instituteIds, $filterEntityIds, $courseLimitPerInstitute);
        
        return $courseData;
    }

    function getWidgetCTPinfoForStream($entityIds) {
        $customParamsCtp = array();
        $customParamsCtp['stream_id'] = $entityIds['primaryHierarchy']['stream'];
        $customParamsCtp['base_course_id'] = $entityIds['course'];
        $customParamsCtp['education_type'] = $entityIds['education_type'];
        $customParamsCtp['delivery_method'] = $entityIds['delivery_method'];
        $customParamsCtp['city_id'] = 1;
        $customParamsCtp['state_id'] = 1;
        $customParamsCtp['min_result_count'] = 1;
        $customParamsCtp["orderby"] = "result_count desc";

        return $this->getWidgetCTPinfo($customParamsCtp);
    }

    function getWidgetCTPinfoForSubstream($entityIds) {
        $customParamsCtp = array();
        $customParamsCtp['stream_id'] = $entityIds['primaryHierarchy']['stream'];
        $customParamsCtp['substream_id'] = $entityIds['primaryHierarchy']['substream'];
        $customParamsCtp['base_course_id'] = $entityIds['course'];
        $customParamsCtp['education_type'] = $entityIds['education_type'];
        $customParamsCtp['delivery_method'] = $entityIds['delivery_method'];
        $customParamsCtp['city_id'] = 1;
        $customParamsCtp['state_id'] = 1;
        $customParamsCtp['min_result_count'] = 1;
        $customParamsCtp["orderby"] = "result_count desc";

        return $this->getWidgetCTPinfo($customParamsCtp);
    }

    function getWidgetCTPinfoForPopularCourse($entityIds) {
        $customParamsCtp = array();
        $customParamsCtp['stream_id'] = 0;
        $customParamsCtp['substream_id'] = 0;
        $customParamsCtp['specialization_id'] = 0;
        $customParamsCtp['base_course_id'] = $entityIds['course'];
        $customParamsCtp['education_type'] = $entityIds['education_type'];
        $customParamsCtp['delivery_method'] = $entityIds['delivery_method'];
        $customParamsCtp['city_id'] = 1;
        $customParamsCtp['state_id'] = 1;
        $customParamsCtp['min_result_count'] = 1;
        $customParamsCtp["orderby"] = "result_count desc";

        return $this->getWidgetCTPinfo($customParamsCtp);
    }

    function getWidgetCTPinfo($params) {
        if(empty($params)) {
            return;
        }

        $this->CI->benchmark->mark('RHS_Interlinking_CTP_Query1_start');
        
        $this->CI->load->library('nationalCategoryList/NationalCategoryPageLib');
        $allIndiaCtp = $this->CI->nationalcategorypagelib->getMultipleUrlByMultipleParams($params,array(),1);
        
        $this->CI->benchmark->mark('RHS_Interlinking_CTP_Query1_end');
        
        //if allIndia ctp link exists, get city ctp links
        $customParamsCtp = array();
        $response = array();
        if(count($allIndiaCtp)>0){
            $exclusionList = array("city"=>1);

            $customParamsCtp['stream_id'] = $allIndiaCtp[0]['stream_id'];
            $customParamsCtp['substream_id'] = $allIndiaCtp[0]['substream_id'];
            $customParamsCtp['specialization_id'] = $allIndiaCtp[0]['specialization_id'];
            $customParamsCtp['base_course_id'] = $allIndiaCtp[0]['base_course_id'];

            if(!empty($allIndiaCtp[0]['delivery_method'])){
                $customParamsCtp['delivery_method'][] = $allIndiaCtp[0]['delivery_method'];
            }
            if(!empty($allIndiaCtp[0]['education_type'])){
                $customParamsCtp['education_type'][] = $allIndiaCtp[0]['education_type'];
            }
            $customParamsCtp['credential'][] = $allIndiaCtp[0]['credential'];
            $customParamsCtp['exam_id'][] = $allIndiaCtp[0]['exam_id'];

            $customParamsCtp['min_result_count'] = 1;
            $customParamsCtp["orderby"] = "result_count desc";

            $this->CI->benchmark->mark('RHS_Interlinking_CTP_Query2_start');
            $cityCtp = $this->CI->nationalcategorypagelib->getMultipleUrlByMultipleParams($customParamsCtp,$exclusionList,8); 
            $this->CI->benchmark->mark('RHS_Interlinking_CTP_Query2_end');
            
            $response['cityLinks'] = array();
            foreach ($cityCtp as $key => $value) {
                $cityInfo = $this->locationRepository->findCity($value['city_id']);
                $response['cityLinks'][$key] = array('url'=>$value['url'],'name'=>$cityInfo->getName());
            }
            $response['allIndiaLink'] = array('name'=>'allIndia','url'=>$allIndiaCtp[0]['url']);
        }

        return $response;
    }

    function getWidgetSRPinfo($linkParams) {

        $customParamsSrp = array();
        $customParamsSrp['entityType']  = $this->fieldAlias[$linkParams['entityType']]; 
        $customParamsSrp['keyword']        = $linkParams['keyword'];
        $customParamsSrp['entityId']     = $linkParams['entityId'];
        
        if(!empty($linkParams['stream']) && $linkParams['stream']!=0){
            $customParamsSrp['stream']  = $linkParams['stream']; 
        }
        if(!empty($linkParams['substream']) && $linkParams['substream']!=0){
            $customParamsSrp['substream']  = $linkParams['substream']; 
        }
        if(count($linkParams['course']) > 0){
            $customParamsSrp['course']  = $linkParams['course']; 
        }

        $customParamsSrp['requestFrom'] = $linkParams['widgetType'];

        foreach ($linkParams['education_type'] as $key => $attribute) {
            if($attribute == PART_TIME_MODE) {
                //get all child attributes of part time
                $dependentAttributes = $this->baseAttributeLibrary->getDependentAttributesByValueId($attribute);
                foreach ($linkParams['delivery_method'] as $deliveryId) {
                    //check if we have delivery method which is child of part time
                    if(!empty($dependentAttributes[DELIVERY_METHOD_ATTRIBUTE_ID]['values'][$deliveryId])) {
                        //skip part time from education type
                        unset($linkParams['education_type'][$key]);
                        break;
                    }
                }
            }
        }
        
        foreach ($linkParams['education_type'] as $valueId) {
            $customParamsSrp['mode'][] = $this->fieldAlias['education_type']."::".$valueId;
        }

        foreach ($linkParams['delivery_method'] as $valueId) {
            $customParamsSrp['mode'][] = $this->fieldAlias['delivery_method']."::".$valueId;
        }
        $customParamsSrp['donot_parse_current_url'] = 1;
        $this->CI->load->library("search/SearchV3/NationalSearchPageUrlGenerator");
        $urlGenerator  = new NationalSearchPageUrlGenerator();

        $closeSearchUrl = array();
        $allIndiaUrl = $urlGenerator->createClosedSearchUrl($customParamsSrp);
        $closeSearchUrl['allIndiaLink'] = array('url'=>$allIndiaUrl,'name'=>'allIndia');
        $closeSearchUrl['cityLinks'] = array();

        foreach ($linkParams['city'] as $key => $value) {
            $searchParams = $customParamsSrp;
            unset($searchParams['city']);
            $searchParams["locations"][] = 'city_'.$value;

            $url = $urlGenerator->createClosedSearchUrl($searchParams);
            $cityInfo = $this->locationRepository->findCity($value);
            $closeSearchUrl['cityLinks'][] = array('name'=>$cityInfo->getName(), 'url'=>$url);
        }
        return $closeSearchUrl;
    }

    private function _initStaticLinks() {
        //loading dependencies
        $this->CI->load->builder('rankingV2/RankingPageBuilder');
        $rankingBuilder              = new RankingPageBuilder();
        $this->rankingURLManager     = $rankingBuilder->getURLManager();
        $this->ArticleUtilityLib = $this->CI->load->library('article/ArticleUtilityLib');
        //dependencies to fetch widget heading
        $this->CI->load->builder('ListingBaseBuilder', 'listingBase');
        $this->ListingBaseBuilder    = new ListingBaseBuilder();
        $this->hierarchyRepo = $this->ListingBaseBuilder->getHierarchyRepository();
        //dependencies to fetch questions url
        $this->AnALibrary = $this->CI->load->library('messageBoard/AnALibrary');
        $this->CI->load->helper(array('mAnA5/ana'));
    }

    /**
     * [getStaticLinks this function will return links for ranking, news articles and qna]
     * @author Ankit Garg <g.ankit@shiksha.com>
     * @date   2017-08-18
     * @return [$entityIds]     [description]
     * @return [$entityIds]     [description]
     * @return [$statisLinks]     [will contain ranking page + new articles + qna urls]
     */
    function getStaticLinks($entityIds, $type, $params) {
        // _p($entityIds); die;
        $staticLinks                    = array();
        $filters                        = array();
        $filters['streamId']            = $entityIds['primaryHierarchy']['stream'];
        $filters['substreamId']         = $entityIds['primaryHierarchy']['substream'];
        // $filters['specializationId']    = $entityIds['primaryHierarchy']['specialization'];
        $filters['baseCourseId']        = $entityIds['course'];
        
        $this->_initStaticLinks();

         $articleCache = $this->CI->load->library('article/cache/articleCache');
        $rankingPageUrl = $articleCache->getRankingURL($filters['streamId'],$filters['substreamId'],$filters['baseCourseId']);
        if(!empty($rankingPageUrl))
        {
            $staticLinks['rankingUrl'] =  $rankingPageUrl;
        }
        else
        {
            $staticLinks['rankingUrl']      = $this->rankingURLManager->getRankingPageUrlByFilters($filters, 1);    
            if(!empty($staticLinks['rankingUrl']))
            {
                $articleCache->storeRankingURL($filters['streamId'],$filters['substreamId'],$filters['baseCourseId'],$staticLinks['rankingUrl']);
            }
        }

        // _p($entityIds);
        switch($type) {
            case 'course':
                if(count($entityIds['course']) == 1 && is_object($params['baseCourseObj'])) {
                    $filters['baseCourseId']        = current($filters['baseCourseId']);
                                        
                    $staticLinks['newsArticlesUrl'] = $this->ArticleUtilityLib->getAllArticlePageUrlAndCount('course', array('course'=>$filters['baseCourseId']));
                    $staticLinks['questionsUrl']    = $this->AnALibrary->getEntityCountAndUrl($filters['baseCourseId'], 'Course', 'question');
                    $staticLinks['widgetHeading']   = $params['baseCourseObj']->getName();
                }
                break;
            case 'substream':
                if(!empty($entityIds['primaryHierarchy']['substream'])) {
                    $staticLinks['newsArticlesUrl'] = $this->ArticleUtilityLib->getAllArticlePageUrlAndCount('substream', array('stream_id'=>$filters['streamId'], 'substream_id' => $filters['substreamId']));
                    if(empty($staticLinks['newsArticlesUrl'])) {
                        $staticLinks['newsArticlesUrl'] = $this->ArticleUtilityLib->getAllArticlePageUrlAndCount('stream', array('stream_id'=>$filters['streamId']));
                    }
                    $staticLinks['questionsUrl']    = $this->AnALibrary->getEntityCountAndUrl($filters['substreamId'], 'Sub-Stream', 'question');
                    if(empty($staticLinks['questionsUrl'])) {
                        $staticLinks['questionsUrl']    = $this->AnALibrary->getEntityCountAndUrl($filters['streamId'], 'Stream', 'question');
                    }
                    $substreamObj = $this->hierarchyRepo->findSubstream($entityIds['primaryHierarchy']['substream']);
                    $staticLinks['widgetHeading'] = $substreamObj->getName();
                }
                break;
            case 'stream':
                if(!empty($entityIds['primaryHierarchy']['stream'])) {
                    $this->CI->benchmark->mark('RHS_Interlinking_Static_Links_Ranking_start');
                    $this->CI->benchmark->mark('RHS_Interlinking_Static_Links_Ranking_end');

                    $this->CI->benchmark->mark('RHS_Interlinking_Static_Links_Article_start');
                    $staticLinks['newsArticlesUrl'] = $this->ArticleUtilityLib->getAllArticlePageUrlAndCount('stream', array('stream_id'=>$filters['streamId']));
                    $this->CI->benchmark->mark('RHS_Interlinking_Static_Links_Article_end');

                    $this->CI->benchmark->mark('RHS_Interlinking_Static_Links_AnA_start');
                    $staticLinks['questionsUrl'] = $this->AnALibrary->getEntityCountAndUrl($filters['streamId'], 'Stream', 'question');
                    $this->CI->benchmark->mark('RHS_Interlinking_Static_Links_AnA_end');
                    
                    $streamObj = $this->hierarchyRepo->findStream($entityIds['primaryHierarchy']['stream']);
                    $staticLinks['widgetHeading'] = $streamObj->getName();
                }
                break;
        }

        if($staticLinks['questionsUrl']['count'] > 0)  {
            $staticLinks['questionsUrl']['count'] = formatNumber($staticLinks['questionsUrl']['count']);
        }
        return $staticLinks;
    }

    function getUpcomingExamDatesEntityId($blogEntitiesMapping){
        $this->CI->load->config('Interlinking/InterlinkingConfig');
        $basecourseOrder = $this->CI->config->item('basecourseOrder');

        $upcomingExamDateEntityId = 0;
        $upcomingExamDateEntityType = "";
        $upcomingExamHeading = "";

        if(!empty($blogEntitiesMapping['course'])) {
            foreach ($basecourseOrder as $key => $value) {
                if(in_array($key, $blogEntitiesMapping['course'])){
                    $upcomingExamDateEntityId = $key;
                    $upcomingExamDateEntityType = "course";
                    $upcomingExamHeading = $value." ";
                    break;
                }
            }
        }
        if(empty($upcomingExamDateEntityId) && !empty($blogEntitiesMapping['primaryHierarchy']) && !empty($blogEntitiesMapping['primaryHierarchy']['stream'])) {
            $upcomingExamDateEntityId = $blogEntitiesMapping['primaryHierarchy']['stream'];
            $upcomingExamDateEntityType = "stream";
        }
        return array('entityId' => $upcomingExamDateEntityId, 'entityType' => $upcomingExamDateEntityType,'upcomingExamHeading' => $upcomingExamHeading);
   }
}