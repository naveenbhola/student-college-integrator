<?php

class NationalCategoryPageRepository {
    private $request;
    
    function __construct(NationalCategoryPageRequest $request) {
        $this->CI = & get_instance();
        $this->request = $request;

        $this->solrClient = $this->CI->load->library('search/Solr/SolrClient');
        $this->nationalCategoryPageLib = $this->CI->load->library('nationalCategoryList/NationalCategoryPageLib');

        // get institute repository with all dependencies loaded
        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepository = $instituteBuilder->getInstituteRepository();

        $this->solrRequestGenerator = $this->CI->load->library('search/Solr/SolrRequestGenerator');

        $this->CI->load->config("nationalCategoryList/nationalConfig");
        $this->field_alias = $this->CI->config->item('FIELD_ALIAS');
    }
    
    function getFiltersAndInstitutes() {
        $time_start = microtime_float(); $start_memory = memory_get_usage();
        $solrResults = $this->getDataFromSolr();
        if(LOG_CL_PERFORMANCE_DATA)
            error_log("Section: NationalCategoryPageRepository, Get data from Solr | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
        
        $time_start = microtime_float(); $start_memory = memory_get_usage(); 
        $this->CI->benchmark->mark('Load_institute_courses_object_start');

        if($solrResults['numOfInstitutes'] == 0) {
          //  _p('ZERO RESULT');
        }

        //set result count in description
        $this->request->setCountInDescription($solrResults['numOfInstitutes']);

        //get details of institutes to show on tuple
        $data['institutes'] = $this->getInstituteData($solrResults['instituteIdCourseIdMap']);

        $this->CI->benchmark->mark('Load_institute_courses_object_end');
        
        if(LOG_CL_PERFORMANCE_DATA)
            error_log("Section: NationalCategoryPageRepository, Load institute courses object | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
        
        $time_start = microtime_float(); $start_memory = memory_get_usage();

        //get details to help render view easily
        $data['totalInstituteCount'] = $solrResults['numOfInstitutes'];
        $data['filters'] = $solrResults['filters'];
        $data['fieldAlias'] = $solrResults['fieldAlias'];
        $data['selectedFilters'] = $solrResults['selectedFilters'];
        $data['appliedFilters'] = $solrResults['appliedFilters'];
        $data['totalCourseCountForAllInstitutes'] = $solrResults['totalCourseCountForAllInstitutes'];

        unset($solrResults);
        return $data;
    }

    function getDataFromSolr() {
        $time_start = microtime_float(); $start_memory = memory_get_usage(); $this->CI->benchmark->mark('Fetch_sponsored_institutes_from_DB_start');
        $solrRequestData = array();
        $solrRequestData['requestType'] = 'category_result';
        $solrRequestData['sponsoredInstitutes'] = $this->getSponsoredInstitutes();

        $this->CI->benchmark->mark('Fetch_sponsored_institutes_from_DB_end');
        if(LOG_CL_PERFORMANCE_DATA)
            error_log("Section: NationalCategoryPageRepository, Fetch sponsored institutes from DB | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
        
        if($this->request->isSubstreamPage()) {
            $solrRequestData['facetCriterion']['type'] = 'substream';
            $solrRequestData['facetCriterion']['id'] = $this->request->getSubstream();
        }
        elseif($this->request->isStreamPage()) {
            $solrRequestData['facetCriterion']['type'] = 'stream';
            $solrRequestData['facetCriterion']['id'] = $this->request->getStream();
        }
        elseif($this->request->isPopularCoursePage()) {
            $solrRequestData['facetCriterion']['type'] = 'base_course';
            $solrRequestData['facetCriterion']['id'] = $this->request->getBaseCourse();
        }
        $filterListOnPage = $this->solrRequestGenerator->getFacetList($solrRequestData);
        $solrRequestData['filters'] = $this->request->getAppliedFilters();
        
        $this->mergeSubSpecFilters($filterListOnPage, $solrRequestData);

        $solrRequestData['userAppliedFilters'] = $solrRequestData['filters'];
        $solrRequestData['pageNum'] = $this->request->getPageNumber();
        $solrRequestData['pageLimit'] = $this->request->getPageLimit();
        $solrRequestData['sort_by'] = $this->request->getSortBy();
        $solrRequestData['randomKey'] = $this->getRandomSortKey();

        $solrRequestData['getParentFilters'] = $this->request->getParentFiltersFlag();
        
        $solrResults = $this->solrClient->getFiltersAndInstitutes($solrRequestData);
        $solrResults['appliedFilters'] = $solrRequestData['userAppliedFilters'];
        return $solrResults;
    }

    function mergeSubSpecFilters($filterListOnPage, & $solrRequestData) {
        $filterValues = $solrRequestData['filters']['specialization'];
        if(in_array('sub_spec', $filterListOnPage) && !empty($filterValues)) {
            foreach ($filterValues as $key => $value) {
                $filterValues[$key] = $this->field_alias['substream'].'_any::'.$this->field_alias['specialization']."_".$value;
            }
            if(empty($solrRequestData['filters']['sub_spec'])) {
                $solrRequestData['filters']['sub_spec'] = $filterValues;
            } else {
                $solrRequestData['filters']['sub_spec'] = array_merge($solrRequestData['filters']['sub_spec'], $filterValues);
            }
            unset($solrRequestData['filters']['specialization']);
        }
        
        $filterValues = $solrRequestData['filters']['substream'];
        if(in_array('sub_spec', $filterListOnPage) && !empty($filterValues)) {
            foreach ($filterValues as $key => $value) {
                $filterValues[$key] = $this->field_alias['substream']."_".$value;
            }
            if(empty($solrRequestData['filters']['sub_spec'])) {
                $solrRequestData['filters']['sub_spec'] = $filterValues;
            } else {
                $solrRequestData['filters']['sub_spec'] = array_merge($solrRequestData['filters']['sub_spec'], $filterValues);
            }
            unset($solrRequestData['filters']['substream']);
        }
    }

    function getRandomSortKey() {
        global $randomSolrTrackingKey;
        
        if(isset($_COOKIE['ctpgRandom'])){
            $randomKey = $_COOKIE['ctpgRandom'];
        }else{
            $randomKey = rand(1001, 9999);
            setcookie("ctpgRandom", $randomKey);
        }

        //eg., $randomKey = '1189, $Version=0';
        if(strpos($randomKey, ', $Version=0') > 0) {
            $randomKey = str_replace(', $Version=0', '', $randomKey);
        }
        
        $randomSolrTrackingKey = $randomKey;
        return $randomKey;
    }
    
    function getSponsoredInstitutes() {
        /*
         * Output Format -
         * ['cs']
         *      [0]
         *      [1]
         *      ..
         *
         * ['main']
         *      [0]
         *      [1]
         *      ..
         */
        $criteria = $this->request->getCustomAppliedFilters();
        $result = $this->nationalCategoryPageLib->getCategoryPageMainSponsoredInstitutes($criteria);
        return $result;
    }

    public function getInstituteData($instituteData) {

        $institutes                   = array();
        $instituteWithPopularCourses  = array();
        $instituteWithLoadMoreCourses = array();
        $instituteWithCourseCount     = array();

        foreach ($instituteData as $instituteId => $courses) {
            $courseCount = count($courses);
            $instituteWithCourseCount[$instituteId] = $courseCount;
            foreach ($courses as $key => $value) {
                if($key == 0) {
                    $instituteWithPopularCourses[$instituteId][] = $value;          
                    $popularCourses[] = $value;                     
                }else{
                    $instituteWithLoadMoreCourses[$instituteId][] = $value;                 
                }
            }
        }

        //lookup with respect to performance
        $institutes['instituteData']                = $this->instituteRepository->findWithCourses($instituteWithPopularCourses, array('basic', 'facility', 'media', 'location'), array('basic' , 'location', 'eligibility'));
        $institutes['popularCourses']               = $popularCourses;
        $institutes['instituteLoadMoreCourses']     = $instituteWithLoadMoreCourses;
        $institutes['instituteCourseCount']         = $instituteWithCourseCount;
        $institutes['instituteCountInCurrentPage']  = count($instituteWithPopularCourses);

        return $institutes;
    }
}