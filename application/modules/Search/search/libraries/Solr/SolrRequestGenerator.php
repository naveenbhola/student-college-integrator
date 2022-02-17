<?php
/*
 * Author - Nikita Jain
 * Purpose - Solr queries
 */
class SolrRequestGenerator {
    private $filters;

	function __construct() {
        $this->CI = & get_instance();

        // $this->CI->load->library('search/Common/SearchCommon');
        // $this->searchCommon = new SearchCommon;

        $this->filters = array('institute', 'stream', 'substream', 'specialization', 'sub_spec', 'base_course', 'popular_group', 'certificate_provider', 'exams', 'locality', 'city', 'state', 'fees', 'course_level', 'credential', 'education_type', 'delivery_method', 'et_dm', 'course_type', 'college_ownership', 'accreditation', 'cr_exists', 'min_course_review', 'facilities', 'offered_by_college', 'course_status','approvals','review');

        $this->CI->load->config("nationalCategoryList/nationalConfig");
        $this->CI->load->config("search/StopWords");
        $this->STOP_WORDS = $this->CI->config->item('STOP_WORDS');
        $this->FIELD_ALIAS = $this->CI->config->item('FIELD_ALIAS');
    }

    function generateUrlOnSearch(& $solrRequestData, $secondChance = 0, $forNonSearchPage = false) {

        $urlComponents = array();        
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';

        //Add query term
        $solrRequestData['isQEmpty'] = false;
        $urlComponents[] = $this->getQueryTerm($solrRequestData, $secondChance);

        //fl
        $flComponents = $this->getFieldListComponents($solrRequestData);
        $urlComponents = array_merge($urlComponents, $flComponents);

        //Filters to apply
        $urlComponents[] = 'fq=facetype:course';
        //$urlComponents[] = 'fq=-(nl_stream_name:"Test%20Preparation")';
        $urlFqComponents = $this->getFieldQueryComponents($solrRequestData);
        $urlComponents = array_merge($urlComponents, $urlFqComponents);
        //Facets
        if($forNonSearchPage == false){
            $urlFacetComponents = $this->getFacetComponents($solrRequestData);
            $urlComponents = array_merge($urlComponents, $urlFacetComponents);    
        }
        
        //group by institute id
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.field=nl_institute_id';
        $urlComponents[] = 'group.ngroups=true'; //total number of results
        $urlComponents[] = 'group.facet=true';
        $urlComponents[] = 'group.limit=1';
        
        //Add sorting
        $sortComponents = $this->sortGroups($solrRequestData);
        $urlComponents = array_merge($urlComponents, $sortComponents);

        $sortGroupComponents = $this->sortDataWithinGroups($solrRequestData);
        $urlComponents = array_merge($urlComponents, $sortGroupComponents);

        //paginate results
        $urlComponents[] = 'start='.(($solrRequestData['pageNum']-1)*$solrRequestData['pageLimit']);
        if($solrRequestData['getFiltersOnly']) { //on mobile, when getting filters and not applying, no need to get institutes
            $urlComponents[] = 'rows=0';
        } 
        else {
            $urlComponents[] = 'rows='.$solrRequestData['pageLimit'];
        }
        
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);

        if(DEBUGGER) { _p($urlComponents); _p($solrUrl); }
        
        return $solrUrl;
    }

    function generateUrlForClientCourses($solrRequestData) {
        $urlComponents = array();        
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';

        $urlComponents[] = $this->getQueryTerm($solrRequestData);

        //fl
        $flComponents = $this->getFieldListComponents($solrRequestData);
        $urlComponents = array_merge($urlComponents, $flComponents);

        //Filters to apply
        $urlComponents[] = 'fq=facetype:course';
        $urlComponents[] = 'fq=nl_course_paid:1';

        // exclude specific institutes from MPT (SS-850)
        $mptExclusionList = $this->CI->config->item('MPT_EXCLUSION_LIST');
        $urlComponents[] = 'fq=-nl_institute_id:('.implode('%20OR%20',$mptExclusionList).')';

        $urlFqComponents = $this->getFieldQueryComponents($solrRequestData);
        $urlComponents = array_merge($urlComponents, $urlFqComponents);
        
        //group by institute id
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.field=nl_institute_id';
        $urlComponents[] = 'group.ngroups=true'; //total number of results
        $urlComponents[] = 'group.limit=1';

        //sort courses within institute
        $sortGroupComponents = $this->sortDataWithinGroups($solrRequestData);
        $urlComponents = array_merge($urlComponents, $sortGroupComponents);

        $urlComponents[] = 'rows=100000';

        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);

        if(DEBUGGER) { _p($urlComponents); _p($solrUrl); }
        
        return $solrUrl;
    }

    private function sortGroups($solrRequestData) {
        $urlComponents = array();
        switch ($solrRequestData['sort_by']) {
            case 'sponsored':
                global $randomSolrTrackingKey;
                srand($randomSolrTrackingKey);
                
                //bucketing CS and MIL by sorting on the basis of boost
                foreach ($solrRequestData['sponsoredInstitutes']['cs'] as $instituteId) {
                    $boost = rand(20000, 30000);
                    $urlComponents[] = 'bq=nl_institute_id:'.$instituteId.'^='.$boost;
                }
                foreach ($solrRequestData['sponsoredInstitutes']['main'] as $instituteId) {
                    $boost = rand(5000, 10000);
                    $urlComponents[] = 'bq=nl_institute_id:'.$instituteId.'^='.$boost;
                }

                //bucket paid institutes
                $urlComponents[] = 'bq=nl_course_paid:1^=2000';

                //give very less boost to view count
                $urlComponents[] = 'bq={!func}if(exists(query({!v=%27nl_course_paid:0%27})),sqrt(nl_institute_view_count_year),0)';

                //buckets are sorted now, randomize the data within bucket
                $urlComponents[] = 'sort=score%20desc,random_'.$solrRequestData['randomKey'].'%20desc';
                break;

            case 'relevance':
                //if single stream and/or substream and/or base course are detected from QER, the sort on popularity of detected entity. Priority order - stream, substream, base course
                if(!empty($solrRequestData['filters']['stream']) && count($solrRequestData['filters']['stream']) == 1){
                    //sort on popularity of stream
                    $urlComponents[] = 'sort=nl_institute_popularity_score_stream+desc';

                    $queryFields = $this->getqueryFields(0); //for qf if any --- for garbage word in case of closed search
                } 
                else if(!empty($solrRequestData['filters']['substream']) && count($solrRequestData['filters']['substream']) == 1){
                    //sort on popularity of substream
                    $urlComponents[] = 'sort=nl_institute_popularity_score_substream+desc';

                    $queryFields = $this->getqueryFields(0); //for qf if any --- for garbage word in case of closed search
                } 
                else if(!empty($solrRequestData['filters']['base_course']) && count($solrRequestData['filters']['base_course']) == 1){
                    //sort on popularity of base course
                    $urlComponents[] = 'sort=nl_institute_popularity_score_base_course+desc';

                    $queryFields = $this->getqueryFields(0); //for qf if any --- for garbage word in case of closed search
                }
                else if(!empty($solrRequestData['filters']['stream']) ||
                     !empty($solrRequestData['filters']['substream']) ||
                     !empty($solrRequestData['filters']['specialization']) ||
                     !empty($solrRequestData['filters']['certificate_provider']) ||
                     !empty($solrRequestData['filters']['base_course']) ||
                     !empty($solrRequestData['filters']['popular_group']) ||
                     !empty($solrRequestData['filters']['institute'])
                    ) {
                    //will cause the groups to be sorted according to the highest course view count document in each group
                    $urlComponents[] = 'sort=nl_course_view_count_year+desc';
                    
                    //get fields for qf
                    $queryFields = $this->getqueryFields(0); //for qf if any --- for garbage word in case of closed search
                } else {
                    //get fields for qf with boosting
                    $queryFields = $this->getqueryFields(1);
                    
                    $boost = 1;
                    if(isset($_REQUEST['boost'])) {
                        $boost = $_REQUEST['boost'];
                    }
                    $urlComponents[] = 'bf=field(nl_course_view_count_year)^'.$boost;
                }
                break;
            
            case 'view_count':
                //bucket all popular courses together, sorted in the order of their occurrence amongst themselves
                $solrRequestData['popularCourses'] = array_reverse($solrRequestData['popularCourses']);
                foreach ($solrRequestData['popularCourses'] as $key => $courseId) {
                    $boost = ($key+1)*100000;
                    $urlComponents[] = 'bq=nl_course_id:'.$courseId.'^='.$boost;
                }

                $urlComponents[] = 'bf=field(nl_course_view_count_year)^10';
                break;

            default:
                # code...
                break;
        }
        if(!empty($queryFields)) {
            $urlComponents[] = 'qf='.implode('+', $queryFields);
        }
        return $urlComponents;
    }

    private function getqueryFields($addBoost = 0) {
        $queryFields = array();
        
        if($addBoost) {
            $queryFields[] = 'nl_institute_name^50';
            $queryFields[] = 'nl_institute_synonyms^50';
            $queryFields[] = 'nl_course_name^50';

            $queryFields[] = 'nl_stream_name^5';
            $queryFields[] = 'nl_substream_name^5';
            $queryFields[] = 'nl_specialization_name^5';
            $queryFields[] = 'nl_popular_group_name^5';
            $queryFields[] = 'nl_certificate_provider_name^5';
            $queryFields[] = 'nl_base_course_name^5';
        } else {
            $queryFields[] = 'nl_institute_name';
            $queryFields[] = 'nl_institute_synonyms';
            $queryFields[] = 'nl_course_name';

            $queryFields[] = 'nl_stream_name';
            $queryFields[] = 'nl_substream_name';
            $queryFields[] = 'nl_specialization_name';
            $queryFields[] = 'nl_popular_group_name';
            $queryFields[] = 'nl_certificate_provider_name';
            $queryFields[] = 'nl_base_course_name';
        }

        $queryFields[] = 'nl_institute_facilities_name';
        $queryFields[] = 'nl_course_exam_name';
        $queryFields[] = 'nl_course_credential_name';
        $queryFields[] = 'nl_course_education_type_name';
        $queryFields[] = 'nl_course_delivery_method_name';
        $queryFields[] = 'nl_course_type_name';
        $queryFields[] = 'nl_course_approval_name';
        $queryFields[] = 'nl_course_level_name';

        return $queryFields;
    }

    private function sortDataWithinGroups($solrRequestData) {
        $urlComponents = array();
        switch ($solrRequestData['requestType']) {
            case 'category_result':
                //sort courses within institutes on the basis of course order. If course order does not exists, show them at the end on the basis of view count
                $urlComponents[] = 'group.sort=nl_course_paid%20desc,exists(nl_course_order)%20desc,nl_course_order%20asc,nl_course_view_count_year%20esc';
                break;

            case 'search_result':
                $urlComponents[] = 'group.sort=nl_course_view_count_year%20desc';
                break;
            
            default:
                $urlComponents[] = 'group.sort=nl_course_view_count_year%20desc';
                break;
        }
        return $urlComponents;
    }

    private function getQueryTerm(& $solrRequestData, $secondChance) {
        switch ($solrRequestData['requestType']) {
            case 'category_result':
                $urlQueryTerm = 'q=*:*';
                break;

            case 'search_result':
                    $QERkeyword = $this->cleanKeyword($solrRequestData['qerFilters']['q']);
                    $keyword = $this->cleanKeyword($solrRequestData['keyword']);
                    
                    if(!empty($solrRequestData['requestFrom']) && !in_array($solrRequestData['requestFrom'], array('filterBucket')) 
                    && $solrRequestData['isAutosuggestorClosedSearch'] === true){
                        if($solrRequestData['isOpenToClose'] && !empty($QERkeyword) && !$secondChance) {
                            $urlQueryTerm = 'q='.$QERkeyword;
                            $solrRequestData['isQEmpty'] = false;
                        } else {
                            $urlQueryTerm = 'q=*:*';
                            $solrRequestData['isQEmpty'] = true; // set flag if q is already *:* in first chance itself
                        }
                    }
                    else{ // open search
                        if(!$secondChance) { // check secondchance flag to set q parameter, if second chance q is always *:*
                            if(!empty($QERkeyword)){
                                $urlQueryTerm = 'q='.$QERkeyword;
                                $solrRequestData['isQEmpty'] = false;
                            }
                            elseif(empty($solrRequestData['qerFilters']['stream']) &&
                                   empty($solrRequestData['qerFilters']['substream']) &&
                                   empty($solrRequestData['qerFilters']['specialization']) &&
                                   empty($solrRequestData['qerFilters']['certificate_provider']) &&
                                   empty($solrRequestData['qerFilters']['base_course']) &&
                                   empty($solrRequestData['qerFilters']['popular_group']) &&
                                   empty($solrRequestData['qerFilters']['institute']) &&
                                   !empty($keyword)
                                ) {
                                $urlQueryTerm = 'q='.$keyword;
                                $solrRequestData['isQEmpty'] = false;
                            }
                            else{
                                $urlQueryTerm = 'q=*:*';
                                $solrRequestData['isQEmpty'] = true; // set flag if q is already *:* in first chance itself
                            }
                        }
                        else{
                            $urlQueryTerm = 'q=*:*';
                            $solrRequestData['isQEmpty'] = true; // set flag if q is already *:* in first chance itself
                        }
                    }
                // $urlQueryTerm = 'q=*:*';        
                break;

            default:
                $urlQueryTerm = 'q=*:*';
                break;
        }
        
        return $urlQueryTerm;
    }

    private function getFieldListComponents() {
        $urlFlComponent = array();
        
        $urlFlComponent[] = "fl=".$this->FIELD_ALIAS['nl_course_id'].":nl_course_id";
        return $urlFlComponent;
    }

    public function getFieldQueryComponents($solrRequestData) {
        $urlComponents = array();
        //_p($solrRequestData); die;
        // $facetList = $this->getFacetList($solrRequestData);
        // $COMBINED_FILTERS = $this->CI->config->item('COMBINED_FILTERS');
        // $combinedFilterNames = array_keys($COMBINED_FILTERS);
        // foreach ($facetList as $key => $value) {
        //     if(in_array($value, $combinedFilterNames)) {
        //         $filtersToRemove = $COMBINED_FILTERS[$value];
        //         $this->filters = array_diff($this->filters, $filtersToRemove);
        //         $this->filters[] = $value;
        //     }
        // }
        foreach ($this->filters as $key => $filterName) {
            switch($filterName) {
                case 'institute':
                    $instituteComponents = array();
                    foreach ($solrRequestData['filters']['institute'] as $instituteId) {
                        $instituteComponents[] = 'nl_course_hierarchy_institute_id:'.$instituteId;
                    }
                    if($instituteComponents) {
                        $urlComponents[] = 'fq={!tag=finstitute}('.implode('%20OR%20',$instituteComponents).')';
                    }
                    break;

                case 'offered_by_college':
                    $instituteComponents = array();
                    foreach ($solrRequestData['filters']['offered_by_college'] as $instituteId) {
                        $instituteComponents[] = 'nl_course_offered_by_id:'.$instituteId;
                    }
                    if($instituteComponents) {
                        $urlComponents[] = 'fq={!tag=fcolg}('.implode('%20OR%20',$instituteComponents).')';
                    }
                    break;

                case 'stream':
                    $streamFilterValues = array();
                    if(is_array($solrRequestData['filters']['stream']) && count($solrRequestData['filters']['stream']) > 0) {
                        $streamFilterValues = $solrRequestData['filters']['stream'];
                    }

                    $streamComponents = array();
                    foreach($streamFilterValues as $streamId) {
                        $streamComponents[] = 'nl_stream_id:'.$streamId;
                    }
                    
                    if($streamComponents) {
                        $urlComponents[] = 'fq={!tag=fstream}('.implode('%20OR%20',$streamComponents).')';
                    }
                    break;

                case 'accreditation':
                    $filterValues = array();
                    if(is_array($solrRequestData['filters']['accreditation']) && count($solrRequestData['filters']['accreditation']) > 0){
                        $filterValues = $solrRequestData['filters']['accreditation'];
                    }
                    $components = array();
                    foreach ($filterValues as $value) {
                        $components[] = 'nl_course_accrediation_id:'.$value;
                    }
                    if(!empty($components)){
                        $urlComponents[] = 'fq={!tag=faccred}('.implode('%20OR%20',$components).')';
                    }
                    break;

                case 'college_ownership':
                    $filterValues = array();
                    if(is_array($solrRequestData['filters']['college_ownership']) && count($solrRequestData['filters']['college_ownership']) > 0){
                        $filterValues = $solrRequestData['filters']['college_ownership'];
                    }
                    $components = array();
                    foreach ($filterValues as $value) {
                        $components[] = 'nl_course_ownership_id:'.$value;
                    }
                    if(!empty($components)){
                        $urlComponents[] = 'fq={!tag=fownership}('.implode('%20OR%20',$components).')';
                    }
                    break;

                case 'approvals':
                    $filterValues = array();
                    if(is_array($solrRequestData['filters']['approvals']) && count($solrRequestData['filters']['approvals']) > 0){
                        $filterValues = $solrRequestData['filters']['approvals'];
                    }
                    $components = array();
                    foreach ($filterValues as $value) {
                        $components[] = 'nl_course_approval_id:'.$value;
                    }
                    if(!empty($components)){
                        $urlComponents[] = 'fq={!tag=fapproval}('.implode('%20OR%20',$components).')';
                    }
                    break;

                case 'substream':
                    $substreamFilterValues = array();
                    if(is_array($solrRequestData['filters']['substream']) && count($solrRequestData['filters']['substream']) > 0) {
                        $substreamFilterValues = $solrRequestData['filters']['substream'];
                    }

                    $substreamComponents = array();
                    foreach($substreamFilterValues as $substreamId) {
                        $substreamComponents[] = 'nl_substream_id:'.$substreamId;
                    }
                    
                    if($substreamComponents) {
                        $urlComponents[] = 'fq={!tag=fsubstream}('.implode('%20OR%20',$substreamComponents).')';
                    }
                    break;

                case 'specialization' : //user + qer
                    $specFilterValues = array();
                    if(is_array($solrRequestData['filters']['specialization']) && count($solrRequestData['filters']['specialization']) > 0) {
                        $specFilterValues = $solrRequestData['filters']['specialization'];
                    }

                    $specializationComponents = array();
                    foreach($specFilterValues as $specialization) {
                        $specializationComponents[] = 'nl_specialization_id:'.$specialization;
                    }
                    
                    if($specializationComponents) {
                        $urlComponents[] = 'fq={!tag=fspec}('.implode('%20OR%20',$specializationComponents).')';
                    }
                    break;

                case 'sub_spec':
                    $subSpecComponents = array();
                    
                    if(is_array($solrRequestData['filters']['sub_spec']) && count($solrRequestData['filters']['sub_spec']) > 0) {
                        foreach ($solrRequestData['filters']['sub_spec'] as $key => $filterValue) {
                            $filterValueArr = explode('::', $filterValue);
                            
                            $deepFilterValueArr1 = explode('_', $filterValueArr[0]);
                            $filterType1 = array_search($deepFilterValueArr1[0], $this->FIELD_ALIAS);
                            $filterValue1 = $deepFilterValueArr1[1];

                            if(!empty($filterValue1)) {
                                if($filterType1 == 'substream') {
                                    if($filterValue1 != 'any') {
                                        $component1 = 'nl_substream_id:'.$filterValue1;
                                    }
                                } else {
                                    $component1 = 'nl_specialization_id:'.$filterValue1;
                                }
                            }

                            $component2 = '';
                            if(!empty($filterValueArr[1])) {
                                $deepFilterValueArr2 = explode('_', $filterValueArr[1]);
                                $filterType2 = array_search($deepFilterValueArr2[0], $this->FIELD_ALIAS);
                                $filterValue2 = $deepFilterValueArr2[1];

                                if(!empty($filterValue2)) {
                                    if($filterType2 == 'substream') {
                                        $component2 = 'nl_substream_id:'.$filterValue2;
                                    } else {
                                        $component2 = 'nl_specialization_id:'.$filterValue2;
                                    }
                                }
                            }
                            if(!empty($component1) && !empty($component2)) {
                                $subSpecComponents[] = '('.$component1.' AND '.$component2.')';
                            } elseif(!empty($component1)) {
                                $subSpecComponents[] = $component1;
                            } elseif(!empty($component2)) {
                                $subSpecComponents[] = $component2;
                            }
                        }
                    }

                    if(!empty($subSpecComponents)) {
                        $urlComponents[] = 'fq={!tag=fsubspec}('.implode('%20OR%20',$subSpecComponents).')';
                    }
                    break;

                case 'base_course' : //user + qer
                    $baseCourseFilterValues = array();
                    if(is_array($solrRequestData['filters']['base_course']) && count($solrRequestData['filters']['base_course']) > 0) {
                        $baseCourseFilterValues = $solrRequestData['filters']['base_course'];
                    }

                    $baseCourseComponents = array();
                    foreach($baseCourseFilterValues as $baseCourse) {
                        $baseCourseComponents[] = 'nl_base_course_id:'.$baseCourse;
                    }
                    
                    if($baseCourseComponents) {
                        $urlComponents[] = 'fq={!tag=fbasecourse}('.implode('%20OR%20',$baseCourseComponents).')';
                    }
                    break;

                case 'popular_group':
                    $filterValues = array();
                    if(is_array($solrRequestData['filters']['popular_group']) && count($solrRequestData['filters']['popular_group']) > 0) {
                        $filterValues = $solrRequestData['filters']['popular_group'];
                    }

                    $filterComponents = array();
                    foreach($filterValues as $value) {
                        $filterComponents[] = 'nl_popular_group_id:"'.urlencode($value).'"';
                    };
                    
                    if(!empty($filterComponents)) {
                        $urlComponents[] = 'fq={!tag=fpopgrp}('.implode('%20OR%20',$filterComponents).')';
                    }
                    break;

                case 'certificate_provider':
                    $filterValues = array();
                    if(is_array($solrRequestData['filters']['certificate_provider']) && count($solrRequestData['filters']['certificate_provider']) > 0) {
                        $filterValues = $solrRequestData['filters']['certificate_provider'];
                    }

                    $filterComponents = array();
                    foreach($filterValues as $value) {
                        $filterComponents[] = 'nl_certificate_provider_id:"'.urlencode($value).'"';
                    };
                    
                    if(!empty($filterComponents)) {
                        $urlComponents[] = 'fq={!tag=fcertprov}('.implode('%20OR%20',$filterComponents).')';
                    }
                    break;

                case 'state' : //user + qer
                    $stateFilterValues = array();
                    if(is_array($solrRequestData['filters']['state']) && count($solrRequestData['filters']['state']) > 0 && $applyOnlyQerFilter == 0) {
                        $stateFilterValues = $solrRequestData['filters']['state'];
                    }
                    
                    foreach($stateFilterValues as $stateId) {
                        $urlLocComponents[] = 'nl_course_state_id:'.$stateId;
                    }
                    break;

                case 'city': //user + qer
                    $cityFilterValues = array();
                    if(is_array($solrRequestData['filters']['city']) && count($solrRequestData['filters']['city']) > 0) {
                        $cityFilterValues = $solrRequestData['filters']['city'];
                        if($cityFilterValues[0] == 1) { //all India
                            break;
                        }
                    }
                    
                    foreach($cityFilterValues as $cityId) {
                        if(!in_array($cityId, $this->citiesWithAppliedLocalities)) {
                            $urlLocComponents[] = 'nl_course_city_id:'.$cityId;
                        }
                    }
                    break;

                case 'locality' : //user + qer
                    $localityFilterValues = array();
                    if(is_array($solrRequestData['filters']['locality']) && count($solrRequestData['filters']['locality']) > 0) {
                        $localityFilterValues = $solrRequestData['filters']['locality'];
                    }

                    if(is_array($localityFilterValues) && count($localityFilterValues) > 0) {
                        $this->CI->load->builder('LocationBuilder','location');
                        $locationBuilder = new LocationBuilder;
                        $locationRepository = $locationBuilder->getLocationRepository();
                        $localityObjs = $locationRepository->findMultipleLocalities($localityFilterValues);
                        
                        foreach($localityObjs as $localityObj) {
                            $localityCityMapping[$localityObj->getCityId()][] = $localityObj->getId();
                        }
                        $this->citiesWithAppliedLocalities = array_keys($localityCityMapping);
                        foreach($localityCityMapping as $cityId => $localities) { //need to use 'AND' for localities with it's cities, and 'OR' within cities and states
                            $urlLocComponents[] = "(nl_course_city_id:".$cityId."%20AND%20nl_course_locality_id:(".implode("%20",$localities)."))";
                        }
                    }
                    break;

                case 'exams': //only user
                    if(is_array($solrRequestData['filters']['exam']) && count($solrRequestData['filters']['exam']) > 0) {
                        $examFilterValues = $solrRequestData['filters']['exam'];
                    }

                    $examComponents = array();
                    foreach($examFilterValues as $examId) {
                        $examComponents[] = 'nl_course_exam_id:'.$examId;
                    }

                    if($examComponents) {
                        $urlComponents[] = 'fq={!tag=fexam}('.implode('%20OR%20',$examComponents).')';
                    }
                    break;

                case 'fees' : //only user
                    if(array_key_exists('fees', $solrRequestData['filters']) && $solrRequestData['filters']['fees'] != 'NoLimit' && !empty($solrRequestData['filters']['fees'])) {
                        $FEES_RANGE = $this->CI->config->item('FEES_RANGE');
                        $feesComponents = array();
                        foreach ($solrRequestData['filters']['fees'] as $value) {
                            $range = "[".$FEES_RANGE[$value]['min']."%20TO%20".$FEES_RANGE[$value]['max']."]";
                            $feesComponents[] = 'nl_course_normalised_fees:'.$range;
                        }
                        
                        if($feesComponents){
                            $urlComponents[] = 'fq={!tag=ffee}('.implode('%20OR%20',$feesComponents).')';    
                        }
                    }
                    break;
                 case 'review' : //only user
                    if(array_key_exists('review', $solrRequestData['filters']) && $solrRequestData['filters']['review'] != 'NoLimit' && !empty($solrRequestData['filters']['review'])) {
                        $RATING_RANGE = $this->CI->config->item('RATING_RANGE');
                        $ratingComponents = array();
                        foreach ($solrRequestData['filters']['review'] as $value) {
                            $range = "[".$RATING_RANGE[$value]['min']."%20TO%20".$RATING_RANGE[$value]['max']."]";
                            $ratingComponents[] = 'nl_course_review_rating_agg:'.$range;
                        }
                        
                        if($ratingComponents){
                            $urlComponents[] = 'fq={!tag=freview}('.implode('%20OR%20',$ratingComponents).')';    
                        }
                    }
                    break;    
                    
                case 'course_level': //qer
                    $courseLevelFilterValues = array();
                    if(is_array($solrRequestData['filters']['course_level']) && count($solrRequestData['filters']['course_level']) > 0) {
                        $courseLevelFilterValues = $solrRequestData['filters']['course_level'];
                    }
                    
                    $courseLevelComponents = array();
                    foreach($courseLevelFilterValues as $courseLevel) {
                        $courseLevelComponents[] = 'nl_course_level_id:'.$courseLevel;
                    };
                    
                    if(!empty($courseLevelComponents)) {
                        $urlComponents[] = 'fq={!tag=flevel}('.implode('%20OR%20',$courseLevelComponents).')';
                    }
                    break;

                case 'credential': //qer
                    $courseLevelFilterValues = array();
                    if(is_array($solrRequestData['filters']['credential']) && count($solrRequestData['filters']['credential']) > 0) {
                        $courseLevelFilterValues = $solrRequestData['filters']['credential'];
                    }
                    
                    $courseLevelComponents = array();
                    foreach($courseLevelFilterValues as $courseLevel) {
                        $courseLevelComponents[] = 'nl_course_credential_id:'.$courseLevel;
                    };
                    
                    if(!empty($courseLevelComponents)) {
                        $urlComponents[] = 'fq={!tag=fcred}('.implode('%20OR%20',$courseLevelComponents).')';
                    }
                    break;

                case 'level_credential':
                    $filterValues = array();
                    if(is_array($solrRequestData['filters']['level_credential']) && count($solrRequestData['filters']['level_credential']) > 0) {
                        $filterValues = $solrRequestData['filters']['level_credential'];
                    }
                    
                    $filterComponents = array();
                    foreach($filterValues as $levelCred) {
                        $filterComponents[] = 'nl_course_level_credential_id:'.$levelCred;
                    }
                    
                    if(!empty($filterComponents)) {
                        $urlComponents[] = 'fq={!tag=flevelcred}('.implode('%20OR%20', $filterComponents).')';
                    }
                    break;

                case 'delivery_method': //user + qer
                    $deliveryFilterValues = array();
                    if(is_array($solrRequestData['filters']['delivery_method']) && count($solrRequestData['filters']['delivery_method']) > 0) {
                        $deliveryFilterValues = $solrRequestData['filters']['delivery_method'];
                    }

                    $deliveryComponents = array();
                    foreach($deliveryFilterValues as $delivery) {
                        $deliveryComponents[] = 'nl_course_delivery_method_id:'.$delivery;
                    }
                    
                    if(!empty($deliveryComponents)) {
                        $urlComponents[] = 'fq={!tag=fdelivery}('.implode('%20OR%20',$deliveryComponents).')';
                    }
                    break;

                case 'education_type': //only user
                    $educationFilterValues = array();
                    if(is_array($solrRequestData['filters']['education_type']) && count($solrRequestData['filters']['education_type']) > 0) {
                        $educationFilterValues = $solrRequestData['filters']['education_type'];
                    }

                    $educationComponents = array();
                    foreach($educationFilterValues as $education) {
                        $educationComponents[] = 'nl_course_education_type_id:'.$education;
                    }
                    
                    if(!empty($educationComponents)) {
                        $urlComponents[] = 'fq={!tag=fedutype}('.implode('%20OR%20',$educationComponents).')';
                    }
                    break;

                case 'et_dm':
                    $etdmComponents = array();
                    
                    if(is_array($solrRequestData['filters']['et_dm']) && count($solrRequestData['filters']['et_dm']) > 0) {
                        foreach ($solrRequestData['filters']['et_dm'] as $key => $filterValue) {
                            $filterValueArr = explode('::', $filterValue);
                            
                            $deepFilterValueArr1 = explode('_', $filterValueArr[0]);
                            $filterType1 = array_search($deepFilterValueArr1[0], $this->FIELD_ALIAS);
                            $filterValue1 = $deepFilterValueArr1[1];

                            if($filterType1 == 'education_type') {
                                $component1 = 'nl_course_education_type_id:'.$filterValue1;
                            } else {
                                if($filterValue1 != 'any') {
                                    $component1 = 'nl_course_delivery_method_id:'.$filterValue1;
                                }
                            }

                            $component2 = '';
                            if(!empty($filterValueArr[1])) {
                                $deepFilterValueArr2 = explode('_', $filterValueArr[1]);
                                $filterType2 = array_search($deepFilterValueArr2[0], $this->FIELD_ALIAS);
                                $filterValue2 = $deepFilterValueArr2[1];

                                if($filterType2 == 'education_type') {
                                    $component2 = 'nl_course_education_type_id:'.$filterValue2;
                                } else {
                                    if($filterValue2 != 'any') {
                                        $component2 = 'nl_course_delivery_method_id:'.$filterValue2;
                                    }
                                }
                            }
                            if(!empty($component1) && !empty($component2)) {
                                $etdmComponents[] = '('.$component1.' AND '.$component2.')';
                            } elseif(!empty($component1)) {
                                $etdmComponents[] = $component1;
                            } else {
                                $etdmComponents[] = $component2;
                            }
                        }
                    }

                    if(!empty($etdmComponents)) {
                        $urlComponents[] = 'fq={!tag=fetdm}('.implode('%20OR%20',$etdmComponents).')';
                    }
                    break;

                case 'course_type': //user + qer
                    $courseFilterValues = array();
                    if(is_array($solrRequestData['filters']['course_type']) && count($solrRequestData['filters']['course_type']) > 0) {
                        $courseFilterValues = $solrRequestData['filters']['course_type'];
                    }

                    $courseComponents = array();
                    foreach($courseFilterValues as $courseType) {
                        $courseComponents[] = 'nl_course_type_id:"'.urlencode($courseType).'"';
                    };
                    
                    if(!empty($courseComponents)) {
                        $urlComponents[] = 'fq={!tag=fcoursetype}('.implode('%20OR%20',$courseComponents).')';
                    }
                    break;

                case 'facilities':
                    if(is_array($solrRequestData['filters']['facilities']) && count($solrRequestData['filters']['facilities']) > 0) {
                        foreach ($solrRequestData['filters']['facilities'] as $facilitiesComponent) {
                            $facilitiesComponents[] = 'nl_institute_facilities_id:'.$facilitiesComponent;
                        }
                        $urlComponents[] = 'fq={!tag=ffac}('.implode('%20OR%20', $facilitiesComponents).')';
                    }
                    break;

                case 'course_status':
                    if(is_array($solrRequestData['filters']['course_status']) && count($solrRequestData['filters']['course_status']) > 0) {
                        foreach ($solrRequestData['filters']['course_status'] as $statusComponent) {
                            $statusComponents[] = 'nl_course_status_id:'.$statusComponent;
                        }
                        $urlComponents[] = 'fq={!tag=fcoursestatus}('.implode('%20OR%20', $statusComponents).')';
                    }
                    break;

                case 'cr_exists':
                    if(isset($solrRequestData['filters']['cr_exists'])) {
                        $urlComponents[] = 'fq={!tag=fca}(nl_course_cr_exist:'.$solrRequestData['filters']['cr_exists'].')';
                    }
                    break;

                case 'min_course_review':
                    if(isset($solrRequestData['filters']['min_course_review'])) {
                        $urlComponents[] = 'fq={!tag=fcr}(nl_course_review_count:['.$solrRequestData['filters']['min_course_review'].'%20TO%20*])';
                    }
                    break;
            }
        }

        if(!empty($urlLocComponents)) {
            $urlComponents[] = 'fq={!tag=floc}('.implode('%20OR%20',$urlLocComponents).')';
        }

        //filter for last modified data as (current year - 2)
        $year = date("Y",strtotime("-2 year"));
        $formattedDate = date("Y-m-d\T00:00:00\Z",strtotime($year.'-01-01'));
        //$urlComponents[] = 'fq=nl_course_last_modify_date:['.$formattedDate.'%20TO%20*]';

        return $urlComponents;
    }

    private function getFiltersToExcludeForParentFilters($solrRequestData){
        $returnArr = array();
        $mapping = array('stream'=>'fstream','substream'=>'fsubstream','specialization'=>'fspec','sub_spec'=>'fsubspec','base_course'=>'fbasecourse','education_type'=>'fedutype','delivery_method'=>'fdelivery','et_dm'=>'fetdm','credential'=>'fcred','level_credential'=>'flevelcred','exam'=>'fexam','locality'=>'floc','state'=>'floc','city'=>'floc','fees'=>'ffee','popular_group'=>'fpopgrp','certificate_provider'=>'fcertprov','course_level'=>'flevel','approvals'=>'fapproval','facilities'=>'ffac','college_ownership'=>'fownership','accreditation'=>'faccred','offered_by_college'=>'fcolg','course_status'=>'fcoursestatus','course_type'=>'fcoursetype','review'=>"freview");
        foreach ($solrRequestData['filters'] as $key => $value) {
            if(!empty($value) && !empty($mapping[$key])){
                $returnArr[$mapping[$key]] = $mapping[$key];
            }
        }
        switch($solrRequestData['facetCriterion']['type']){
            case 'stream':
            case 'substream':
            case 'base_course':
                unset($returnArr[$mapping[$solrRequestData['facetCriterion']['type']]]);
                break;
        }
        return array_values($returnArr);
    }

    public function getFacetComponents($solrRequestData) {
        //get required facet fields
        $facetList = $this->getFacetList($solrRequestData);
        $getLocalityFacet = 1;
        if(in_array($solrRequestData['requestType'], array('advanced_filters'))) {
            $getLocalityFacet = 0;
        }

        if(isMobileRequest()){
            $this->parentFiltersToExclude = $this->getFiltersToExcludeForParentFilters($solrRequestData);
        }

        //get facet field queries
        $urlFacetComponents = array();
        if($solrRequestData['requestType'] == 'search_result' && empty($solrRequestData['getFilters'])){
            $facetList = array();
            $facetList[] = "instt_course";
            $urlFacetComponents = $this->getFacetComponentsByFacetList($facetList,0, 0);
            return $urlFacetComponents;
        }
        //if(!empty($facetList)) {
            $urlFacetComponents = $this->getFacetComponentsByFacetList($facetList,$solrRequestData['getParentFilters'] , $getLocalityFacet);
            // if($solrRequestData['getParentFilters']) {
            //     $urlFacetComponents = $this->getFacetComponentsByFacetList($facetList, 1, $getLocalityFacet);
            // } else {
            //     $urlFacetComponents = $this->getFacetComponentsByFacetList($facetList, 0, $getLocalityFacet);
            // }
       //}
        return $urlFacetComponents;
    }

    public function getFacetList($solrRequestData) {
        if(in_array($solrRequestData['requestType'], array('category_result', 'search_result', 'all_courses'))) {
            $FACETS_MAPPING = $this->CI->config->item('FACETS_FILTER_MAPPING');
        }
        if(in_array($solrRequestData['requestType'], array('advanced_filters'))) {
            $FACETS_MAPPING = $this->CI->config->item('FACETS_ADVANCED_FILTER_MAPPING');
        }
        if(in_array($solrRequestData['requestType'], array('hamburger_filters'))) {
            $FACETS_MAPPING = $this->CI->config->item('FACETS_GNB_FILTER_MAPPING');
        }
        
        if(!empty($solrRequestData['facetCriterion'])) {
            $facetList = $FACETS_MAPPING[$solrRequestData['facetCriterion']['type']][$solrRequestData['facetCriterion']['id']];
            if(empty($facetList)) {
                if(!empty($solrRequestData['facetCriterion']['type'])) {
                    $facetList = $FACETS_MAPPING[$solrRequestData['facetCriterion']['type']]['default'];
                } else {
                    $facetList = $FACETS_MAPPING[$solrRequestData['facetCriterion']['default']];
                }
            }
            if($solrRequestData['requestType'] == 'category_result' || $solrRequestData['requestType'] == "search_result") {
                $facetList[] = 'instt_course';
            }
        } else {
            $facetList = $FACETS_MAPPING['default'];
            if($solrRequestData['requestType'] == 'category_result' || $solrRequestData['requestType'] == "search_result") {
                $facetList[] = 'instt_course';
            }
        }

        if(!empty($solrRequestData['additionalFacetsToFetch'])){
            $facetList = array_merge($facetList,(array)$solrRequestData['additionalFacetsToFetch']);
        }

        return $facetList;
    }

    private function getFacetComponentsByFacetList($facetList, $getParentFilters = 0, $getLocalityFacet = 1) {
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.mincount=1';
        $urlComponents[] = 'facet.limit=-1';
        $urlComponents[] = 'facet.sort=true';
        $urlComponents[] = 'facet.threads=12';

        $taggedFilters = implode(',',$this->parentFiltersToExclude);
        
        foreach ($facetList as $key => $facet) {
            switch ($facet) {
                //(ex)  - exclude applied filter of same dimension while fetching it's own facet
                //(key) - give that facet a new name
                case 'stream':
                    $prefixQuery = "{!ex=fstream%20key=".$this->FIELD_ALIAS['stream']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_stream_name_id_map';
                    break;

                case 'sub_spec':
                    $prefixQuery = "{!ex=fsubspec%20key=".$this->FIELD_ALIAS['sub_spec']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_substream_specialization_name_id_map';

                    if($getParentFilters){
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['sub_spec_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_substream_specialization_name_id_map';
                    }

                    //to get substream count separately
                    $prefixQuery = "{!ex=fsubspec%20key=".$this->FIELD_ALIAS['substream']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_substream_name_id_map';

                    if($getParentFilters){
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['substream_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_substream_name_id_map';
                    }
                    break;

                case 'specialization':
                    $prefixQuery = "{!ex=fspec%20key=".$this->FIELD_ALIAS['specialization']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_specialization_name_id_map';
                    if($getParentFilters){
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['specialization_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_specialization_name_id_map';
                    }
                    break;

                case 'substream':
                    $prefixQuery = "{!ex=fsubstream%20key=".$this->FIELD_ALIAS['substream']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_substream_name_id_map';
                    if($getParentFilters){
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['substream_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_substream_name_id_map';
                    }
                    break;

                case 'base_course':
                    $prefixQuery = "{!ex=fbasecourse%20key=".$this->FIELD_ALIAS['base_course']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_base_course_name_id_map';
                    if($getParentFilters){
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['base_course_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_base_course_name_id_map';
                    }
                    break;

                case 'college_ownership':
                    $prefixQuery = "{!ex=fownership%20key=".$this->FIELD_ALIAS['college_ownership']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_ownership_name_id_map';
                    if($getParentFilters){
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['college_ownership_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_ownership_name_id_map';
                    }
                    break;

                case 'approvals':
                    $prefixQuery = "{!ex=fapproval%20key=".$this->FIELD_ALIAS['approvals']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_approval_name_id_map';
                    if($getParentFilters){
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['approvals_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_approval_name_id_map';
                    }
                    break;

                case 'accreditation':
                    $prefixQuery = "{!ex=faccred%20key=".$this->FIELD_ALIAS['accreditation']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_accrediation_name_id_map';
                    if($getParentFilters){
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['accreditation_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_accrediation_name_id_map';
                    }
                    break;
                
                case 'popular_group':
                    $prefixQuery = "{!ex=fpopgrp%20key=".$this->FIELD_ALIAS['popular_group']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_popular_group_name_id_map';
                    if($getParentFilters){
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['popular_group_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_popular_group_name_id_map';
                    }
                    break;

                case 'certificate_provider':
                    $prefixQuery = "{!ex=fcertprov%20key=".$this->FIELD_ALIAS['certificate_provider']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_certificate_provider_name_id_map';
                    if($getParentFilters){
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['certificate_provider_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_certificate_provider_name_id_map';
                    }
                    break;

                case 'location':
                    //add city facet
                    $prefixQuery = "{!ex=floc%20key=".$this->FIELD_ALIAS['city']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_city_name_id_map';
                    
                    if($getParentFilters) {
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['city_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_city_name_id_map';
                    }
                    
                    //add state facet
                    $prefixQuery = "{!ex=floc%20key=".$this->FIELD_ALIAS['state']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_state_name_id_map';
                    
                    if($getParentFilters) {
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['state_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_state_name_id_map';
                    }
                    
                    //add locality facet
                    if(!isMobileRequest() && $getLocalityFacet) { //locality filter not shown on mobile and advanced filters
                        $prefixQuery = "{!ex=floc%20key=".$this->FIELD_ALIAS['locality']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_city_id_locality_id_map'; //This field will give city id of particular locality, and will have value only if locality is present. Hence, separate fields need to be used for locality and city.
                        
                        if($getParentFilters) {
                            $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['locality_parent']."}";
                            $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_city_id_locality_id_map';
                        }
                    }
                    break;
                
                case 'exam':
                    $prefixQuery = "{!ex=fexam%20key=".$this->FIELD_ALIAS['exam']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_exam_name_id_map';
                    
                    if($getParentFilters) {
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['exam_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_exam_name_id_map';
                    }
                    break;
                
                case 'fees':
                    $FEES_RANGE = $this->CI->config->item('FEES_RANGE');
                    foreach ($FEES_RANGE as $key => $value) {
                        $urlComponents[] = 'facet.query={!ex=ffee%20key='.$key.'}nl_course_normalised_fees:['.$value['min'].'%20TO%20'.$value['max'].']';
                    }

                    if($getParentFilters) {
                        foreach ($FEES_RANGE as $key => $value) {
                            $urlComponents[] = 'facet.query={!ex='.$taggedFilters.'%20key='.$key.'_p}nl_course_normalised_fees:['.$value['min'].'%20TO%20'.$value['max'].']';
                        }
                    }
                    break;
                case 'review':
                    $RATING_RANGE = $this->CI->config->item('RATING_RANGE');
                    foreach ($RATING_RANGE as $key => $value) {
                        $urlComponents[] = 'facet.query={!ex=freview%20key='.$key.'}nl_course_review_rating_agg:['.$value['min'].'%20TO%20'.$value['max'].']';
                    }

                    if($getParentFilters) {
                        foreach ($RATING_RANGE as $key => $value) {
                            $urlComponents[] = 'facet.query={!ex='.$taggedFilters.'%20key='.$key.'_p}nl_course_review_rating_agg:['.$value['min'].'%20TO%20'.$value['max'].']';
                        }
                    }
                    break;    
                
                case 'credential':
                    $prefixQuery = "{!ex=fcred%20key=".$this->FIELD_ALIAS['credential']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_credential_name_id_map';
                    
                    if($getParentFilters) {
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['credential_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_credential_name_id_map';
                    }
                    break;

                case 'course_level':
                    $prefixQuery = "{!ex=flevel%20key=".$this->FIELD_ALIAS['course_level']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_level_name_id_map';
                    
                    if($getParentFilters) {
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['course_level_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_level_name_id_map';
                    }
                    break;

                case 'level_credential':
                    $prefixQuery = "{!ex=flevelcred%20key=".$this->FIELD_ALIAS['level_credential']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_level_credential_name_id_map';
                    
                    if($getParentFilters) {
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['level_credential_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_level_credential_name_id_map';
                    }
                    break;

                case 'et_dm':
                    $prefixQuery = "{!ex=fetdm%20key=".$this->FIELD_ALIAS['et_dm']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_et_dm_name_id_map';

                    if($getParentFilters) {
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['et_dm_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_et_dm_name_id_map';
                    }
                    break;

                case 'course_type':
                    $prefixQuery = "{!ex=fcoursetype%20key=".$this->FIELD_ALIAS['course_type']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_type_name_id_map';

                    if($getParentFilters) {
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['course_type_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_type_name_id_map';
                    }
                    break;

                case 'course_status':
                    $prefixQuery = "{!ex=fcoursestatus%20key=".$this->FIELD_ALIAS['course_status']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_status_name_id_map';

                    if($getParentFilters) {
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['course_status_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_status_name_id_map';
                    }
                    break;

                case 'facilities':
                    $prefixQuery = "{!ex=ffac%20key=".$this->FIELD_ALIAS['facilities']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_institute_facilities_name_id_map';

                    if($getParentFilters) {
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['facilities_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_institute_facilities_name_id_map';
                    }
                    break;

                case 'offered_by_college':
                    $prefixQuery = "{!ex=fcolg%20key=".$this->FIELD_ALIAS['offered_by_college']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_offered_by_name_id_map';

                    if($getParentFilters) {
                        $prefixQuery = "{!ex=".$taggedFilters."%20key=".$this->FIELD_ALIAS['offered_by_college_parent']."}";
                        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_offered_by_name_id_map';
                    }
                    break;

                case 'instt_course':
                    $prefixQuery = "{!key=".$this->FIELD_ALIAS['instt_course']."}";
                    $urlComponents[] = 'facet.field='.$prefixQuery.'nl_insttId_courseId_cOrder_viewCnt';
                    break;

                case 'date':
                    $formattedDateLastWeek = date("Y-m-d\T00:00:00\Z",strtotime('-1 week'));
                    $urlComponents[] = 'facet.query={!ex=fdate%20key=week}nl_entity_creation_date:['.$formattedDateLastWeek.'%20TO%20*]';

                    $formattedDateLastMonth = date("Y-m-d\T00:00:00\Z",strtotime('-1 month'));
                    $urlComponents[] = 'facet.query={!ex=fdate%20key=month}nl_entity_creation_date:['.$formattedDateLastMonth.'%20TO%20*]';

                    $formattedDateLastYear = date("Y-m-d\T00:00:00\Z",strtotime('-1 year'));
                    $urlComponents[] = 'facet.query={!ex=fdate%20key=year}nl_entity_creation_date:['.$formattedDateLastYear.'%20TO%20*]';
                    break;
            }
        }

        return $urlComponents;
    }

    private function cleanKeyword($keyword) {
        $stopWords = array('institutes', 'institute', 'colleges', 'college', 'university', 'universities', 'bschools', 'bschool', 'schools', 'school', 'courses', 'course', 'top', 'best', 'private', 'government', 'public', 'offering', 'lists', 'list');

        $keyword = htmlentities(strip_tags($keyword));
        $keyword = trim(str_replace($stopWords, "", $keyword));
        $keyword = preg_replace('/[0-9]+/', '', $keyword);
        $keyword = preg_replace("/\./", "", $keyword);

        return $keyword;
    }

    public function getSpellCheckSuggestions($solrRequestData) {
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        
        $keyword = trim($solrRequestData['keyword']);
        $urlComponents[] = 'spellcheck.q='.urlencode($keyword);
        $solrUrl = SOLR_SPELL_URL.implode('&',$urlComponents);

        return $solrUrl;
    }

    public function generateAllCoursesUrl($solrRequestData) {
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';

        //Add query term
        $urlComponents[] = $this->getQueryTerm($solrRequestData);

        //Filters to apply
        $urlComponents[] = 'fq=facetype:course';
        $urlFqComponents = $this->getFieldQueryComponents($solrRequestData);
        $urlComponents = array_merge($urlComponents, $urlFqComponents);

        //Facets
        $urlFacetComponents = $this->getFacetComponents($solrRequestData);
        $urlComponents = array_merge($urlComponents, $urlFacetComponents);
        
        //group by institute id
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.field=nl_course_id';
        $urlComponents[] = 'group.ngroups=true'; //total number of results
        $urlComponents[] = 'group.facet=true';
        $urlComponents[] = 'group.limit=0';
        
        //Add sorting
        $sortComponents = $this->sortGroups($solrRequestData);
        $urlComponents = array_merge($urlComponents, $sortComponents);

        //paginate results
        $urlComponents[] = 'start='.(($solrRequestData['pageNum']-1)*$solrRequestData['pageLimit']);
        if($solrRequestData['getFiltersOnly']) { //on mobile, when getting filters and not applying, no need to get institutes
            $urlComponents[] = 'rows=0';
        } 
        else {
            $urlComponents[] = 'rows='.$solrRequestData['pageLimit'];
        }
        
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        
        if(DEBUGGER) { _p($urlComponents); _p($solrUrl); }
        
        return $solrUrl;
    }

    public function getQuestionSuggestionUrl($solrRequestData){
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';

        /*
         * The number of other words permitted between words in query phrase is called “Slop“. 
         * We can use the tilde, “~”, symbol at the end of our Phrase for this. 
         * The lesser the distance between two terms the higher the score will be. 
         * A sloppy phrase query specifies a maximum “slop”, or the number of positions tokens need to be moved to get a match. 
         * The slop is zero by default, requiring exact matches.
         */
        $solrRequestData['text'] = htmlentities(strip_tags($solrRequestData['keyword']));
        //$urlComponents[] = 'q='.urlencode($solrRequestData['text']).'+OR+"'.urlencode($solrRequestData['text']).'"^20';
        $urlComponents[] = 'q='.urlencode($solrRequestData['text']);
        
        //add filter
        $urlComponents[] = 'fq=facetype:ugc';
        $urlComponents[] = 'fq=nl_entity_type:question';
        $urlComponents[] = 'fq=nl_entity_moderated:1';
        $urlComponents[] = 'fq=-(nl_entity_answer_count:0)'; //answered questions only

        if($solrRequestData['currentTab'] == 'answered') {
            switch ($solrRequestData['filterBy']) {
                case 'week':
                    $formattedDate = date("Y-m-d\T00:00:00\Z",strtotime('-1 week'));
                    $urlComponents[] = 'fq={!tag=fdate}(nl_entity_creation_date:['.$formattedDate.'%20TO%20*])';
                    $urlComponents[] = 'sort=nl_entity_creation_date%20desc'; //sort within group
                    break;

                case 'month':
                    $formattedDate = date("Y-m-d\T00:00:00\Z",strtotime('-1 month'));
                    $urlComponents[] = 'fq={!tag=fdate}(nl_entity_creation_date:['.$formattedDate.'%20TO%20*])';
                    $urlComponents[] = 'sort=nl_entity_creation_date%20desc'; //sort within group
                    break;

                case 'year':
                    $formattedDate = date("Y-m-d\T00:00:00\Z",strtotime('-1 year'));
                    $urlComponents[] = 'fq={!tag=fdate}(nl_entity_creation_date:['.$formattedDate.'%20TO%20*])';
                    $urlComponents[] = 'sort=nl_entity_creation_date%20desc'; //sort within group
                    break;
                
                default:
                    $urlComponents[] = 'sort=score%20desc,nl_entity_quality_factor%20desc'; //sort within group
                    //$urlComponents[] = 'sort=nl_entity_quality_factor%20desc,score%20desc'; //sort within group
                    break;
            }
        }
        
        $urlComponents[] = 'qf=nl_entity_name_edgeNGram+'.
                            'nl_entity_name_keywordEdgeNGram+'.
                            'nl_entity_name_autosuggest+'.
                            'nl_entity_synonyms_autosuggest+'.
                            'nl_entity_synonyms_keywordEdgeNGram+';

        //q with pf, uses positional information of the term that is stored in an index
        $urlComponents[] = 'ps=100';
        $urlComponents[] = 'pf=nl_entity_name_edgeNGram+'.
                            'nl_entity_name_keywordEdgeNGram+'.
                            'nl_entity_name_autosuggest+'.
                            'nl_entity_synonyms_autosuggest+'.
                            'nl_entity_synonyms_keywordEdgeNGram+';

        $fieldsToFetch = array('nl_entity_quality_name_id_type_map','nl_entity_url');

        foreach($fieldsToFetch as $field) {
            $fieldsToFetchWithAlias[] = $this->FIELD_ALIAS[$field].":".$field;
        }
        
        $urlComponents[] = 'fl='.implode(',', $fieldsToFetchWithAlias);

        //facet
        $facetList[] = "date";
        $urlFacetComponents = $this->getFacetComponentsByFacetList($facetList, 0, 0);
        $urlComponents = array_merge($urlComponents, $urlFacetComponents);

        $urlComponents[] = 'start='.(($solrRequestData['pageNum']-1)*$solrRequestData['pageLimit']);
        $urlComponents[] = 'rows='.$solrRequestData['pageLimit'];

        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);

        if(DEBUGGER) { _p($urlComponents); _p($solrUrl); }
        
        return $solrUrl;
    }

    public function getUnansweredQuestionSuggestionUrl($solrRequestData){
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';

        /*
         * The number of other words permitted between words in query phrase is called “Slop“. 
         * We can use the tilde, “~”, symbol at the end of our Phrase for this. 
         * The lesser the distance between two terms the higher the score will be. 
         * A sloppy phrase query specifies a maximum “slop”, or the number of positions tokens need to be moved to get a match. 
         * The slop is zero by default, requiring exact matches.
         */
        $solrRequestData['text'] = htmlentities(strip_tags($solrRequestData['keyword']));
        $solrRequestData['text'] = $this->removeEnglishStopWords($solrRequestData['text']);
        
        $urlComponents[] = 'q='.urlencode($solrRequestData['text']);
        
        $urlComponents[] = 'fq=facetype:ugc';
        $urlComponents[] = 'fq=nl_entity_type:question';
        $urlComponents[] = 'fq=nl_entity_moderated:1';
        $urlComponents[] = 'fq=nl_entity_status:live';
        $urlComponents[] = 'fq=nl_entity_answer_count:0'; //unanswered questions only

        if($solrRequestData['currentTab'] == 'unanswered') {
            switch ($solrRequestData['filterBy']) {
                case 'week':
                    $formattedDate = date("Y-m-d\T00:00:00\Z",strtotime('-1 week'));
                    $urlComponents[] = 'fq={!tag=fdate}(nl_entity_creation_date:['.$formattedDate.'%20TO%20*])';
                    break;

                case 'month':
                    $formattedDate = date("Y-m-d\T00:00:00\Z",strtotime('-1 month'));
                    $urlComponents[] = 'fq={!tag=fdate}(nl_entity_creation_date:['.$formattedDate.'%20TO%20*])';
                    break;

                case 'year':
                    $formattedDate = date("Y-m-d\T00:00:00\Z",strtotime('-1 year'));
                    $urlComponents[] = 'fq={!tag=fdate}(nl_entity_creation_date:['.$formattedDate.'%20TO%20*])';
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        $urlComponents[] = 'qf=nl_entity_name_edgeNGram+'.
                            'nl_entity_name_keywordEdgeNGram+'.
                            'nl_entity_name_autosuggest+'.
                            'nl_entity_synonyms_autosuggest+'.
                            'nl_entity_synonyms_keywordEdgeNGram+';

        //q with pf, uses positional information of the term that is stored in an index
        $urlComponents[] = 'ps=100';
        $urlComponents[] = 'pf=nl_entity_name_edgeNGram+'.
                            'nl_entity_name_keywordEdgeNGram+'.
                            'nl_entity_name_autosuggest+'.
                            'nl_entity_synonyms_autosuggest+'.
                            'nl_entity_synonyms_keywordEdgeNGram+';

        $fieldsToFetch = array('nl_entity_quality_name_id_type_map','nl_entity_url','nl_entity_creation_date');
        foreach($fieldsToFetch as $field) {
            $fieldsToFetchWithAlias[] = $this->FIELD_ALIAS[$field].":".$field;
        }
        
        $urlComponents[] = 'fl='.implode(',', $fieldsToFetchWithAlias);

        //facet
        $facetList[] = "date";
        $urlFacetComponents = $this->getFacetComponentsByFacetList($facetList, 0, 0);
        $urlComponents = array_merge($urlComponents, $urlFacetComponents);

        //$urlComponents[] = 'bf=recip(ms(NOW,nl_entity_creation_date),3.16e-11,1,1)';

        $urlComponents[] = 'sort=score%20desc,nl_entity_creation_date%20desc'; //sort within group
        //$urlComponents[] = 'sort=score%20desc'; //sort within group
        //$urlComponents[] = 'sort=nl_entity_quality_factor%20desc,score%20desc'; //sort within group

        $urlComponents[] = 'start='.(($solrRequestData['pageNum']-1)*$solrRequestData['pageLimit']);
        $urlComponents[] = 'rows='.$solrRequestData['pageLimit'];

        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);

        if(DEBUGGER) { _p($urlComponents); _p($solrUrl); }
        
        return $solrUrl;
    }

    public function getQuestionTopicSuggestionUrl($solrRequestData){
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';

        $solrRequestData['text'] = htmlentities(strip_tags($solrRequestData['keyword']));
        $solrRequestData['text'] = $this->removeEnglishStopWords($solrRequestData['text']);

        $urlComponents[] = 'q="'.urlencode($solrRequestData['text']).'"';
        
        $urlComponents[] = 'fq=facetype:autosuggestor';
        $urlComponents[] = 'fq=nl_entity_type:question_tag';

        $urlComponents[] = 'qf=nl_entity_name_edgeNGram+'.
                            'nl_entity_name_keywordEdgeNGram+'.
                            'nl_entity_name_en_keywordEdgeNGram+'.
                            'nl_entity_name_autosuggest+'.
                            'nl_entity_synonyms_autosuggest+'.
                            'nl_entity_synonyms_keywordEdgeNGram+'.
                            'nl_entity_synonyms_spkeywordEdgeNGram';

        //q with pf, uses positional information of the term that is stored in an index
        // $urlComponents[] = 'ps=100';
        // $urlComponents[] = 'pf=nl_entity_name_edgeNGram+'.
        //                     'nl_entity_name_keywordEdgeNGram+'.
        //                     'nl_entity_name_autosuggest+';

        $fieldsToFetch = array('nl_entity_quality_name_id_type_map', 'nl_entity_tag_qna_count', 'nl_entity_url');
        foreach($fieldsToFetch as $field) {
            $fieldsToFetchWithAlias[] = $this->FIELD_ALIAS[$field].":".$field;
        }
        
        $urlComponents[] = 'fl='.implode(',',$fieldsToFetchWithAlias);

        $urlComponents[] = 'sort=nl_entity_quality_factor%20desc'; //sort groups wrt each other

        $urlComponents[] = 'start='.(($solrRequestData['pageNum']-1)*$solrRequestData['pageLimit']);
        $urlComponents[] = 'rows='.$solrRequestData['pageLimit'];
        
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);

        if(DEBUGGER) { _p($urlComponents); _p($solrUrl); }
        
        return $solrUrl;
    }

    public function removeEnglishStopWords($keyword) {
        $keywordArr = explode(' ', $keyword);
        foreach ($keywordArr as $key => $value) {
            if(in_array($value, $this->STOP_WORDS)) {
                unset($keywordArr[$key]);
            }
        }
        $updatedKeyword = implode(' ', $keywordArr);

        // if(empty($updatedKeyword)) {
        //     $updatedKeyword = $keyword; //retaining original keyword
        // }

        return $updatedKeyword;
    }

    public function getFilteredInstitutesForSpecializationUrl($instIdsToFilter, $valid_spec_id) {
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';

        $urlComponents[] = 'q=*:*';
        
        $urlComponents[] = 'fq=facetype:course';
        $urlComponents[] = 'fq=nl_course_hierarchy_institute_id:(' .implode("+", $instIdsToFilter). ')';
        $urlComponents[] = 'fq=nl_specialization_id:'.$valid_spec_id;
        $urlComponents[] = 'facet=true&facet.field=nl_course_hierarchy_institute_id&facet.mincount=1&facet.limit=-1';
        $urlComponents[] = 'fl=nl_specialization_id';
        $urlComponents[] = 'rows=0';
        
        $urlComponents[] = 'omitHeader=true';

        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);

        if(DEBUGGER) { _p($urlComponents); _p($solrUrl); }
        
        return $solrUrl;
    }


}
