    <?php

/**
 * Class TrendsElasticLibrary
 * Library for shiksha analytics/trends home
 * @date    2017-09-25
 * @author  Romil Goel
 * @todo    none
 *
 */

require_once('vendor/autoload.php');

define("NUM_INST_PER_PAGE", 10);
define("NUM_UNIV_PER_PAGE", 10);
define("NUM_COURSE_PER_PAGE", 10);
define("NUM_EXAM_PER_PAGE", 10);
define("NUM_SPECIALIZATION_PER_PAGE", 10);
define("NUM_QUESTIONS_PER_PAGE", 6);
define("NUM_REGIONS_PER_PAGE", 10);


class TrendsElasticLibrary
{


    private $elasticParams;

    function __construct(){

        $this->CI = & get_instance();
        $this->init_elastic();
        $this->CI->load->model("trendsmodel","TrendsModel");
        //$this->load->model("trendsmodel","TrendsModel");

    }

    function init_elastic() {
    	$this->elasticParams = array();
        if(ENVIRONMENT == 'production' || ENVIRONMENT == 'beta') {
            $this->elasticParams['hosts'] = array(SHIKSHA_ELASTIC_HOST);
        }
        else{
            $this->elasticParams['hosts'] = array("172.16.3.248");
        }
    }

    function get_elastic_conn() {
    	return new Elasticsearch\Client($this->elasticParams);
    }

    function getBaseQueryArr() {
    	$elasticQuery = array();
        if(ENVIRONMENT == 'production' || ENVIRONMENT == 'beta') {
            $elasticQuery['index'] = ANALYTICS_ELASTIC_INDEX;    
        }
        else{
            $elasticQuery['index'] = ANALYTICS_ELASTIC_INDEX;
        }
        //$elasticQuery['index'] = "shiksha_analytics";
    	$elasticQuery['type'] = "default";
    	$elasticQuery['size'] = 0;
    	return $elasticQuery;
    }

    // If dateRangeRemoveFlag == true then don't put date ranges.
    function getElasticQueryResults($filtersList, $entityToGet, $entityToSumOn, $oneShardResultSize, $resultsOffset, $min_bucket_docs_count, $dateRangeRemoveFlag, $terms_query) {
        $elasticConn = $this->get_elastic_conn();

        $elasticQuery = $this->getBaseQueryArr();

        // Building the filter query filter object using all filters 
        $query_filter_cond = array();
        foreach($filtersList as $filter_hash_map) {
            array_push($query_filter_cond, ["term" => $filter_hash_map]);
        }
        if($terms_query) {
            array_push($query_filter_cond, ["terms" => $terms_query]);
        }

        if(!$dateRangeRemoveFlag) {
            $date_range = array();
            $date_range['range']['date']['gte'] = "2018-06";
            array_push($query_filter_cond, $date_range);
        } else {
            $date_range = array();
            $date_range['range']['date']['gte'] = "2017-06";
        }

        //$elasticQuery['body']['query']['filtered']['filter']['bool']['must']  = $query_filter_cond;
        $elasticQuery['body']['query']['bool']['filter']['bool']['must']  = $query_filter_cond;

        //negative filter to remove 0 values:-
        if($entityToGet != "date"){
            $query_negative_filter = array(array("term" => array($entityToGet => 0)));    
        }


        $elasticQuery['body']['query']['bool']['filter']['bool']['must_not'] = $query_negative_filter;

        $elasticQuery['body']['aggs']['group_by_entity']['terms']['field'] = $entityToGet;
        $elasticQuery['body']['aggs']['group_by_entity']['terms']['size'] = $oneShardResultSize;

        $elasticQuery['body']['aggs']['group_by_entity']['terms']['order']['entity_to_sum'] = "desc";

        if($min_bucket_docs_count) {
            $elasticQuery['body']['aggs']['group_by_entity']['terms']['min_doc_count'] = $min_bucket_docs_count;
        }

        $elasticQuery['body']['aggs']['group_by_entity']['aggs']['entity_to_sum']['sum']['field'] = $entityToSumOn;
        $result = $elasticConn->search($elasticQuery);
        return $result;
    }

    // A function to get the unique number of entities of any type.
    function getUniqueEntitiesNumber($filtersList, $entityToGet) {
        $elasticConn = $this->get_elastic_conn();

        $elasticQuery = $this->getBaseQueryArr();

        // Building the filter query filter object using all filters 
        $query_filter_cond = array();
        foreach($filtersList as $filter_hash_map) {
            array_push($query_filter_cond, ["term" => $filter_hash_map]);
        }

        $elasticQuery['body']['query']['bool']['filter']['bool']['must']  = $query_filter_cond;

        $elasticQuery['body']['size'] = 0;

        $elasticQuery['body']['aggs']['distinct_entity']['cardinality']['field'] = $entityToGet;

        $result = $elasticConn->search($elasticQuery);
        $unique_entities = $result["aggregations"]['distinct_entity']["value"];

        return intval($unique_entities/100)*100;
    }

    // This method is for taking a small result set (~10 univs/insts) and then putting in additional meta data
    function getMetaDataForUnivInst($final_filtered_result_small, $entity_type) {
        $entity_ids = [];
        foreach($final_filtered_result_small as $meta_data) {
           $entity_ids [] = $meta_data["id"];
        }

        $labelled_data = $this->CI->TrendsModel->getLabelsForUnivAndInst($entity_ids, $entity_type, false);

        foreach($final_filtered_result_small as $single_entity_meta) {
            $id = $single_entity_meta["id"];
            if(array_key_exists($id, $labelled_data)) {
                $share = $single_entity_meta["share"];
                $labelled_data[$id]["share"] = $share;
                $labelled_data[$id]["id"] = $id;
            }

        }
        return $labelled_data;
    }

    // $entity_type is either a "university" or an "institute"
    // Here $entity_type signifies whether we want to find top universities or top institutes
    function getPopularUnivAndInst($entity_type, $page_num, $location_id, $ownership, $streamId, $entityNameToFilter = null, $IdOfEntityToFilter = null){

        $base_cache_key = "analytics_cons_";
        if($entityNameToFilter == null && $IdOfEntityToFilter == null) {
            $cache_key = $base_cache_key.$entity_type."widg_"."home";
        } else {
            $cache_key = $base_cache_key.$entity_type."widg_".$entityNameToFilter."_".$IdOfEntityToFilter;

        }

        $elasticConn = $this->get_elastic_conn();
        // This would get the top universities

        $entityNameToFilterOriginal = $entityNameToFilter;
        $resultsPerShard = 0;
        // This is the case where we will get top universities
        if($entity_type == "university") {
            $num_results_to_get = NUM_UNIV_PER_PAGE;
            $resultsOffset = NUM_UNIV_PER_PAGE * ($page_num - 1);
            $pageTypeFilter = "ulp_comb";
            $entityToGet = "entityId";
            $resultsPerShard = 250;

        // This would get the top institutes
        } else if($entity_type == "institute") {
            $num_results_to_get = NUM_INST_PER_PAGE;
            $resultsOffset = NUM_INST_PER_PAGE * ($page_num - 1);
            $resultsPerShard = 700;

            // The following is the case for displaying top institutes on the stream/substream/specialization/base_course ETP. 
            if($entityNameToFilter == "stream" || $entityNameToFilter == "substream" || $entityNameToFilter == "specialization" || $entityNameToFilter == "base_course" ) {
                $pageTypeFilter = "clp_comb";
                $entityToGet = "parent_univ_inst_ids";
            } else {
                $pageTypeFilter = "ilp_comb";
                $entityToGet = "entityId";
            }
        }

        $query_filters = array();
        array_push($query_filters, ["pageType" => $pageTypeFilter]);

        if($ownership) {
            array_push($query_filters, ["ownership" => $ownership]);
        }
        /*
        if($location_id) {
            array_push($query_filters, ["stateId" => $location_id]);
        }*/
        if($streamId) {
            array_push($query_filters, ["streamId" => $streamId]);
        }

        if($entityNameToFilter == "base_course") {
            $entityNameToFilter = "baseCourseId";
        // Following is the case where parent universities have to be filtered
        } else if($entityNameToFilter == "university") {
            $entityNameToFilter = "parent_univ_inst_ids";
        }  else if($entityNameToFilter == "exam") {
            $entityNameToFilter = "examsAccepted";
        } else {
            $entityNameToFilter = $entityNameToFilter."Id";
        }

        if($entityNameToFilter != null && $IdOfEntityToFilter != null) {
            array_push($query_filters, [$entityNameToFilter => $IdOfEntityToFilter]);
        }

         // NEW CODE TO FIND THE UNIQUE COUNT
        $result_uniq = $this->getElasticQueryResults($query_filters, $entityToGet, "num_pageviews", $resultsPerShard, null, 20);
        $result_uniq_small = $result_uniq["aggregations"]["group_by_entity"]["buckets"];
        
        $max_page_views = $result_uniq_small[0]["entity_to_sum"]["value"];
        $min_bucket_pageviews = ($max_page_views*0.5)/100;

/*
        $num_unique2 = 0;
        // This is the univ and inst ids with more than the minimum pageviews
        $univ_inst_ids_with_min_pv = [];
        foreach($result_uniq_small as $id => $value) {
            $pageviews = $value["entity_to_sum"]["value"];
            $univ_inst_id = $value["key"];
            if($pageviews > $min_bucket_pageviews) {
                $univ_inst_ids_with_min_pv [] = $univ_inst_id;
                // $num_unique2 += 1;
            }
        }*/
        // $num_unique_entities = $num_unique2;

        $result_small = $result_uniq_small;
        $univ_page_views = array();
        $univ_id_list = array();

        $startTime = microtime(true);

        // THIS IS ONLY FOR THE CASE WHERE SPECIALIZATION IS THE FILTER
        // $univ_inst_id_list_temp = array();
        // if($entityNameToFilterOriginal == "specialization") {
        //     foreach($result_small as $id => $value) {
        //         $univ_inst_id_list_temp[] = $value["key"];
        //     }
        //     $univ_inst_to_spec_map = $this->CI->TrendsModel->getSpecializationsForUnivInst2($univ_inst_id_list_temp);
        //     // $univ_inst_to_spec_map = $this->CI->TrendsModel->getSpecializationsForUnivInst2($univ_inst_ids_with_min_pv);

        // }
        //**********************************************

        foreach($result_small as $id => $value) {
            /*
            if($entityNameToFilterOriginal == "specialization") {
                $univ_inst_id = $value["key"];
                if(array_key_exists($univ_inst_id, $univ_inst_to_spec_map)) {
                    $valid_spec_ids = $univ_inst_to_spec_map[$univ_inst_id];
                    if(!in_array($IdOfEntityToFilter, $valid_spec_ids)) {
                        continue;
                    }
                }
            }*/
            array_push($univ_id_list, $value["key"]);
            $univ_page_views[$value["key"]] = $value["entity_to_sum"]["value"];
        }

        $univ_meta_arr = $this->CI->TrendsModel->getLabelsForUnivAndInst($univ_id_list, $entity_type, true, $cache_key);

        //********************NEW CODE **********************************************************
        $filtered_result_and_uniques = $this->CI->TrendsModel->getFilteredUnivInstDataAndUniques($univ_page_views, $univ_meta_arr, $location_id);

        $final_filtered_result = $filtered_result_and_uniques[0];


        $final_filtered_result_specs = [];

        // Removing all the univs/insts which don't offer this specialization
        $final_filtered_result_specs_removed = [];
        if($entityNameToFilterOriginal == "specialization") {

            $unique_univ_inst_ids = [];
            foreach($final_filtered_result as $key => $value) {
                $unique_univ_inst_ids [] = $value["id"];
            }
            // $univ_inst_to_spec_map = $this->CI->TrendsModel->getSpecializationsForUnivInst2($unique_univ_inst_ids);
            $valid_spec_id = $IdOfEntityToFilter;
            $filteredInstIdsSpecRemoved = $this->CI->TrendsModel->getFilteredInstIdsForSpecialization($unique_univ_inst_ids, $valid_spec_id);

            foreach($final_filtered_result as $key => $value) {
                $univ_inst_id = $value["id"];
                if(in_array($univ_inst_id, $filteredInstIdsSpecRemoved)) {
                    array_push($final_filtered_result_specs_removed, $value);
                }
                // if(array_key_exists($univ_inst_id, $univ_inst_to_spec_map)) {
                //     $valid_spec_ids = $univ_inst_to_spec_map[$univ_inst_id];
                //     if(in_array($IdOfEntityToFilter, $valid_spec_ids)) {
                //         array_push($final_filtered_result_specs_removed, $value);
                //     } 
                // } 

            }

            $final_filtered_result = $final_filtered_result_specs_removed;

            // foreach($final_filtered_result as $key => $value) {
            //     $univ_inst_id = $value["id"];
            //     if(array_key_exists($univ_inst_id, $univ_inst_to_spec_map)) {
            //         $valid_spec_ids = $univ_inst_to_spec_map[$univ_inst_id];
            //         if(!in_array($IdOfEntityToFilter, $valid_spec_ids)) {
            //             continue;
            //         }
            //     }
            // }
        }

        $final_filtered_result_small = array_slice($final_filtered_result, $resultsOffset, $num_results_to_get);

        //*** NOW GETTING THE ADDITIONAL META DATA FOR THE FINAL 10 RESULTS
        $final_filtered_result_small = $this->getMetaDataForUnivInst($final_filtered_result_small, $entity_type);

        return array($final_filtered_result_small, sizeof($final_filtered_result), $filtered_result_and_uniques[1]);
    }

    // Here $entityType can be -- baseCourse / stream, substream, specialization etc
    // $pageTypeFilter can be stream/ substream/ specialization/ institute/ university
    function getPopularCourseEntities($entityType, $page_num, $streamId, $locationId, $credential, $courseLevel, $entityCountFlag = false, $entityNameToFilter = null, $IdOfEntityToFilter = null, $returnUniqueEntities = null) {

        if($entityNameToFilter == "exam") {
            $pageTypeFilter = "exam";
            // TEMPORARY FIX . CHANGE THIS AFTER CLP_COMB HAS EXAMSACCEPTED
            $locationId = null;
        } else {
            $pageTypeFilter = "clp_comb";
        }
        $query_filters = array();

        array_push($query_filters, ["pageType" => $pageTypeFilter]);

        /*if($streamId) {
            array_push($query_filters, ["streamId" => $streamId]);
        }*/
        if($locationId) {
            array_push($query_filters, ["stateId" => $locationId]);
        }
        if($credential) {
            array_push($query_filters, ["credential" => $credential]);
        }
        //if($courseLevel) {
         //   array_push($query_filters, ["courseLevel" => $courseLevel]);
        //}
        $entityNameToFilterOriginal = $entityNameToFilter;
        if($entityNameToFilter == "base_course") {
            $entityNameToFilter = "baseCourseId";
        // Following is the case where parent universities or institutes have to be filtered
        } else if($entityNameToFilter == "university" || $entityNameToFilter == "institute") {
            $entityNameToFilter = "parent_univ_inst_ids";
        } else if($entityNameToFilter == "exam") {
            $entityNameToFilter = "entityId";
        } else {
            $entityNameToFilter = $entityNameToFilter."Id";
        }

        if($entityNameToFilter != null && $IdOfEntityToFilter != null) {
            array_push($query_filters, [$entityNameToFilter => $IdOfEntityToFilter]);
        }
        if($entityType == "base_course") {
            $entityType = "baseCourse";
        }
        $entityToGet = $entityType."Id";

        $num_results_to_get = NUM_COURSE_PER_PAGE;
        $resultsOffset = NUM_COURSE_PER_PAGE * ($page_num - 1);

        $num_unique_entities = 0;

        $result_uniq = $this->getElasticQueryResults($query_filters, $entityToGet, "num_pageviews", 160, null, 10);
        $result_uniq_small = $result_uniq["aggregations"]["group_by_entity"]["buckets"];
        
        // This is the maximum number of pageviews 
        $max_page_views = $result_uniq_small[0]["entity_to_sum"]["value"];

        // NEW CODE TO FIND THE UNIQUE COUNT
        $min_bucket_pageviews = ($max_page_views*0.5)/100;
        $num_unique2 = 0;
        foreach($result_uniq_small as $id => $value) {
            $pageviews = $value["entity_to_sum"]["value"];
            if($pageviews > $min_bucket_pageviews) {
                $num_unique2 += 1;
            }
        }
        $num_unique_entities = $num_unique2;
        
        //**************************************
        if($entityType == "baseCourse" || $entityType == "specialization") {
            $result_small_sliced = $result_uniq_small;
        } else {
            $result_small_sliced = array_slice($result_uniq_small, $resultsOffset, $num_results_to_get);
        }

        $entity_ids_list = array();
        $entity_page_views = array();

        foreach($result_small_sliced as $id => $value) {
            array_push($entity_ids_list, $value["key"]);
            $entity_page_views[$value["key"]] = $value["entity_to_sum"]["value"];
        }

        $entity_meta_arr = $this->CI->TrendsModel->getLabelsForCourseEntities($entityType, $entity_ids_list);
        if($entityType == "baseCourse") {
            $filtered_result_and_uniques = $this->CI->TrendsModel->getFilteredCourseDataAndUniques($entity_page_views, $entity_meta_arr, $entityType, $courseLevel);

            $final_filtered_result = $filtered_result_and_uniques[0];

            $final_filtered_result_small = array_slice($final_filtered_result, $resultsOffset, $num_results_to_get);
            // This is the total number of filtered ids without slicing
            return array($final_filtered_result_small, sizeof($final_filtered_result), $filtered_result_and_uniques[1], $filtered_result_and_uniques[2], $filtered_result_and_uniques[3]);
        } else if($entityType == "specialization") {
            if($entityNameToFilterOriginal == "university" || $entityNameToFilterOriginal == "institute") {
                $valid_spec_ids = $this->CI->TrendsModel->getSpecializationsForUnivInst2(array($IdOfEntityToFilter))[$IdOfEntityToFilter];
                foreach($entity_page_views as $spec_id => $page_views) {
                    if(!in_array($spec_id, $valid_spec_ids)) {
                        unset($entity_page_views[$spec_id]);
                        unset($entity_meta_arr[$spec_id]);
                    }
                }
            }
            $filtered_result_and_uniques = $this->CI->TrendsModel->getFilteredCourseDataAndUniques($entity_page_views, $entity_meta_arr, $entityType, null, $streamId);

            $final_filtered_result = $filtered_result_and_uniques[0];

            $final_filtered_result_small = array_slice($final_filtered_result, $resultsOffset, $num_results_to_get);

            // This is the total number of filtered ids without slicing
            return array($final_filtered_result_small, sizeof($final_filtered_result), $filtered_result_and_uniques[1]);
        }
        
        $final_entity_result = array();
        foreach($entity_ids_list as $entity_id) {
            if(array_key_exists($entity_id, $entity_meta_arr)) {
                $text = $entity_meta_arr[$entity_id]["name"];
                $share = round((intval($entity_page_views[$entity_id])/$max_page_views)*100);
                if($share > 0) {
                    array_push($final_entity_result, array("text" => $text, "share" => $share, "id" => $entity_id));
                }
            }
        }
        //_p($filtered_ids_and_uniques[1]);
        //if($entityType == "specialization") {
        //     return array($final_entity_result, $num_unique_entities, $unique_streams);} 
        //else {
        return array($final_entity_result, $num_unique_entities);
            //}
    }

    function getPopularExams($page_num = 1, $streamId, $getFullExamData) {
        $pageTypeFilter = "exam";
        $query_filters = array();
        array_push($query_filters, ["pageType" => $pageTypeFilter]);

        /*if($streamId) {
            array_push($query_filters, ["streamId" => $streamId]);
        }*/
       
        $entityToGet = "entityId";

        $num_results_to_get = NUM_COURSE_PER_PAGE;
        $resultsOffset = NUM_COURSE_PER_PAGE * ($page_num - 1);

        //$num_unique_entities = $this->getUniqueEntitiesNumber($query_filters, $entityToGet);

        // Querying elasticsearch here
        //$result = $this->getElasticQueryResults($query_filters, $entityToGet, "num_pageviews", $num_results_to_get, $resultsOffset);
        $result = $this->getElasticQueryResults($query_filters, $entityToGet, "num_pageviews", 400, null, 10);

        $result_small = $result["aggregations"]["group_by_entity"]["buckets"];

        //_p($max_page_views);
        
        //$result_small_sliced = array_slice($result_small, $resultsOffset);

        $entity_ids_list = array();
        $entity_page_views = array();

        foreach($result_small as $id => $value) {
            array_push($entity_ids_list, $value["key"]);
            $entity_page_views[$value["key"]] = $value["entity_to_sum"]["value"];
        }

        //_p($entity_ids_list);
        $entity_meta_arr = $this->CI->TrendsModel->getLabelsForExams($entity_ids_list);
        
		// Adding up the page views of the unique Exam names. Summing up the repeated names.

        $exam_data_unique = array();
        foreach($entity_ids_list as $entity_id) {
            if(array_key_exists($entity_id, $entity_meta_arr['data'])) {
                $name = $entity_meta_arr['data'][$entity_id];
                $page_views = $entity_page_views[$entity_id];

                if(array_key_exists($name, $exam_data_unique)) {
                    $old_pv = $exam_data_unique[$name];
                    $exam_data_unique[$name] = $old_pv + $page_views;
                } else { 
                    $exam_data_unique[$name] = $page_views; 
                }
            }
        }

        arsort($exam_data_unique);
        
        $filtered_result_and_uniques = $this->CI->TrendsModel->getFilteredExamDataAndUniques($exam_data_unique, $entity_meta_arr, $streamId);

        $final_filtered_result = $filtered_result_and_uniques[0];

        if($getFullExamData) return $final_filtered_result;
        $final_filtered_result_small = array_slice($final_filtered_result, $resultsOffset, $num_results_to_get);

        // This is the total number of filtered ids without slicing
        return array($final_filtered_result_small, sizeof($final_filtered_result), $filtered_result_and_uniques[1]);
        /*
        //This is the maximum number of pageviews 
        $max_page_views = reset($exam_data_unique);

        //****************************************
        // NEW CODE TO FIND THE UNIQUE COUNT
        $min_bucket_pageviews = ($max_page_views*0.5)/100;
        $num_unique2 = 0;
        foreach($exam_data_unique as $name => $page_views) {
            if($page_views > $min_bucket_pageviews) {
                $num_unique2 += 1;
            }
        }
        $num_unique_entities = $num_unique2;
        
        //**************************************

        if($getFullExamData) {
            $exam_data_unique_sliced = $exam_data_unique;
        } else {
            $exam_data_unique_sliced = array_slice($exam_data_unique, $resultsOffset, NUM_COURSE_PER_PAGE );
        }

        $final_entity_result = array();
        foreach($exam_data_unique_sliced as $name => $page_views) {
                $share = round((intval($page_views)/$max_page_views)*100);
                if($share > 0) {
                    array_push($final_entity_result, array("name" => $name, "share" => $share, "id" => $reverse_entity_meta_arr[$name]));
                }
        }
        */
    }

    // UNIVERISTY OWNERSHIP PIECHART
    function get_ownership_pie_chart($location_id, $ownership) {
        $elasticQuery = $this->getBaseQueryArr();

        $query_filters = array();
        array_push($query_filters, ["pageType" => "ulp_comb"]);

        if($ownership) {
            array_push($query_filters, ["ownership" => $ownership]);
        }
        if($location_id) {
            array_push($query_filters, ["stateId" => $location_id]);
        }

        // group by ownership
        $entityToGet = "ownership";
        $result = $this->getElasticQueryResults($query_filters, $entityToGet, "num_pageviews", 10, 0);

        $result_small = $result["aggregations"]["group_by_entity"]["buckets"];

        $final_ownership_result = array();
        $total_pageviews = 0;
        // finding total pageviews ->
        foreach($result_small as $id => $value) {
            $total_pageviews += $value["entity_to_sum"]["value"];
        }

        foreach($result_small as $id => $value) {
            $entity_name = $value["key"];
            $text = $value["key"];
            $entity_pageviews = $value["entity_to_sum"]["value"];
            $final_ownership_result[$entity_name] = array(
                    'share' => round((intval($entity_pageviews)/$total_pageviews) * 100),
                    'text' => $text );
        }

        return $final_ownership_result;
    }

    // $entityType can be stream/substream/specialization/ institute/university
    function get_credential_pie_chart($credential = null, $courseLevel, $entityNameToFilter = null, $IdOfEntityToFilter = null, $validBaseCourseIdArr, $validCredentialArr) {
        $courseLevel = intval($courseLevel);
        $elasticQuery = $this->getBaseQueryArr();

        $query_filters = array();
        $pageTypeFilter = "";
        if($entityNameToFilter == "exam") {
            $pageTypeFilter = "exam";
        } else {
            $pageTypeFilter = "clp_comb";
        }
        array_push($query_filters, ["pageType" => $pageTypeFilter]);

        if($entityNameToFilter == "base_course") {
            $entityNameToFilter = "baseCourseId";
        // Following is the case where parent universities or institutes have to be filtered
        } else if($entityNameToFilter == "university" || $entityNameToFilter == "institute") {
            $entityNameToFilter = "parent_univ_inst_ids"; 
        } else if($entityNameToFilter == "exam") {
            $entityNameToFilter = "entityId";
        } else {
            $entityNameToFilter = $entityNameToFilter."Id";
        }

        if($courseLevel) {
            array_push($query_filters, ["courseLevel" => $courseLevel]);
        }
        if($entityNameToFilter != null && $IdOfEntityToFilter != null) {
            array_push($query_filters, [$entityNameToFilter => $IdOfEntityToFilter]);
        }

        $termsFilter = array("baseCourseId" => $validBaseCourseIdArr);


        // group by credentials_share
        $entityToGet = "credential";
        $result = $this->getElasticQueryResults($query_filters, $entityToGet, "num_pageviews", 10, 0, null, null, $termsFilter);

        $result_small = $result["aggregations"]["group_by_entity"]["buckets"];

        $final_ownership_result = array();
        $cred_ids_list = array();
        $total_pageviews = 0;
        // finding total pageviews ->
        foreach($result_small as $id => $value) {
            if(in_array($value["key"], $validCredentialArr)) {
                array_push($cred_ids_list, $value["key"]);
                $total_pageviews += $value["entity_to_sum"]["value"];
            }
        }

        if(!$result_small) {
            $baseCourseId = reset($termsFilter["baseCourseId"]);
            $cred_ids_list = $this->CI->TrendsModel->getCredentialsForBaseCourses($baseCourseId);
            $cred_id_returned = reset($cred_ids_list);
        }

        $cred_meta_arr = $this->CI->TrendsModel->getLabelsForCredentials($cred_ids_list);
        $final_entity_result = array();
        $sum_of_shares = 0;
        foreach($result_small as $id => $value) {
            $cred_id = $value["key"];
            $cred_pageviews = $value["entity_to_sum"]["value"];
            if(in_array($cred_id, $validCredentialArr)) {
                $share = round((intval($cred_pageviews)/$total_pageviews) * 100);
                $sum_of_shares += $share;
                array_push($final_entity_result, array(
                        'share' => $share,
                        'text' => $cred_meta_arr[$cred_id]." ($share%)"
                        ));
            }
        }

        if($sum_of_shares != 100) {
            $text = $final_entity_result[0]['text'];
            $cred_name = explode(" ", $text)[0];
            if($sum_of_shares < 100) 
                $final_entity_result[0]['share'] += 1;
            else 
                $final_entity_result[0]['share'] -= 1;
            $final_entity_result[0]['text'] = $cred_name." (".$final_entity_result[0]['share']."%)";
        } 
        
        if(!$result_small) {
            array_push($final_entity_result, array(
                        'share' => 100,
                        'text' => $cred_meta_arr[$cred_id_returned]));

        }

        return $final_entity_result;
    }

    function getTrendsOverallMetrics(){

        $cacheLib = $this->CI->load->library('cacheLib');
        $cntKey = md5('nationalHomepageCounters_json');
        $hpCounterResult = $cacheLib->get($cntKey);

    	$overallMetrics                           = array();


        if($hpCounterResult != 'ERROR_READING_CACHE'){
            $hpCounterResult = json_decode($hpCounterResult, true);
            $overallMetrics['totalListings']          = $hpCounterResult['national']['instCount'];
            $overallMetrics['examCount']              = $hpCounterResult['national']['examCount'];
            $overallMetrics['baseCourseCount']        = $hpCounterResult['national']['baseCourseCount'];
            $overallMetrics['specializationCount']    = $hpCounterResult['national']['specializationCount'];
        }
		return $overallMetrics;
    }

    function getPopularUniversities($location, $ownership, $pageNumber = 1){

		$popularUniversities = array();

        $popularUniversities['pageNumber']   = $pageNumber;
        $popularUniversities['itemsPerPage'] = NUM_UNIV_PER_PAGE;

        $popularUniversities['current_location'] = $location;
        $popularUniversities['current_ownership'] = $ownership;
    	// $popularUniversities['ownership_share'] = array('public' => array('share' => 6, 'text' => 'Public'),
    	// 												'private' => array('share' => 22,'text' => 'Private'),
    	// 												'deemed' => array('share' => 10, 'text' => 'Deemed'));
        
        // This works :-
        /*
        $popularUniversities['ownership_share'] = $this->get_ownership_pie_chart($location, $ownership);

        $ownershipData = array();
        foreach ($popularUniversities['ownership_share'] as $key => $value) {
            $ownershipData[] = array($value['text'], $value['share']);
        }
        $popularUniversities['ownership_data'] = $ownershipData;
        */

        $popUnivResult = $this->getPopularUnivAndInst("university", $pageNumber, $location, $ownership);
		$popularUniversities['universities'] = $popUnivResult[0];
        $popularUniversities['totalResults'] = $popUnivResult[1];
        $popularUniversities['statesForFilter'] = $popUnivResult[2];

        $popularUniversities['maxPages'] = ceil($popularUniversities['totalResults']/$popularUniversities['itemsPerPage']);

        /*
        array(array("name" => "University of Delhi",
														   "location" => "Delhi",
														   "share" => 90),
													array("name" => "University of Maharashtra",
														   "location" => "Mumbai",
														   "share" => 70),
													array("name" => "Jaipur University",
														   "location" => "Jaipur",
														   "share" => 30),
													array("name" => "St. Xaviers University",
														   "location" => "Ranchi",
														   "share" => 30),
													array("name" => "Jaypee University of Biotech",
														   "location" => "Greater Noida",
														   "share" => 30),
													array("name" => "University of Delhi",
														   "location" => "Delhi",
														   "share" => 90),
													array("name" => "University of Maharashtra",
														   "location" => "Mumbai",
														   "share" => 70),
													array("name" => "Jaipur University",
														   "location" => "Jaipur",
														   "share" => 30),
													array("name" => "St. Xaviers University",
														   "location" => "Ranchi",
														   "share" => 30),
													array("name" => "Jaypee University of Biotech",
														   "location" => "Greater Noida",
														   "share" => 30));
        */
		return $popularUniversities;
    }

    function getPopularInstitutesData($location, $stream=null, $pageNumber=1, $entityName = null, $entityId = null){
    	$popularInstitutes = array();

        $popularInstitutes['pageNumber']            = $pageNumber;
        $popularInstitutes['itemsPerPage']          = NUM_INST_PER_PAGE;
        $popularInstitutes['entityType']            = $entityName;
        $popularInstitutes['entityId']              = $entityId;
        $popularInstitutes['current_location']      = $location;
        $popularInstitutes['current_stream']        = $stream;
        $popularInstitutes['stream_share']          = $this->getPopularCourseEntities("stream", 1, $stream, $location, null, null, false,  $entityName, $entityId)[0]; 
        $streamShare = array();
        foreach ($popularInstitutes['stream_share'] as $key => $value) {
             // $streamShare[] = array($value['text'], $value['share'], "<div style = 'padding:5px;width:130px;'><b>".$value['text']."</b><br />Popularity Index : <b>".$value['share']."</b></div>");

             $streamShare[] = array($value['text'], $value['share']);
        }

        // $this->_padZeroValues('stream', $streamShare);
        $popularInstitutes['stream_data']  = $streamShare;

        $popInstResult                     = $this->getPopularUnivAndInst("institute", $pageNumber, $location, null, $stream, $entityName, $entityId);
        $popularInstitutes['institutes']   = $popInstResult[0];
        $popularInstitutes['totalResults'] = $popInstResult[1];
        $popularInstitutes['statesForFilter'] = $popInstResult[2];

        $popularInstitutes['maxPages']     = ceil($popularInstitutes['totalResults']/$popularInstitutes['itemsPerPage']);

		return $popularInstitutes;
    }

    // This is for the Popular Courses box which contains a pie chart and bar chart
    function getPopularCoursesData($levels, $credentials, $pageNumber=1, $entityType=null, $entityId=null){

        $popularCourses = array();

        $popularCourses['pageNumber']         = $pageNumber;
        $popularCourses['itemsPerPage']       = NUM_COURSE_PER_PAGE;
        $popularCourses['current_level']      = $levels;
        $popularCourses['entityType']         = $entityType;
        $popularCourses['entityId']           = $entityId;
        $popularCourses['current_credential'] = $credentials;

        // This is for generating the filters. --PUT FILTERS HERE
        $popularCourses['levels'] = $this->CI->TrendsModel->getCourseLevels();

        $popularCourses['credentials'] = array(22 => "Degree",
                                                 3 => "Diploma",
                                                 23 => "Certificates");


        error_log("CREDENTIAL = ".print_r($credentials, true). "LEVEL = ".print_r($levels, true));

        error_log("ENTITY TYPE = ".print_r($entityType, true). "ENTITY ID = ".print_r($entityId, true));

        $popCoursesRes = $this->getPopularCourseEntities("baseCourse", $pageNumber, null, null, $credentials, $levels, true, $entityType, $entityId, true);


        $popularCourses['courses'] = $popCoursesRes[0];
        $popularCourses['totalResults'] = $popCoursesRes[1];
        $valid_course_levels = $popCoursesRes[2];

        $valid_credential_ids = $popCoursesRes[3];
        $valid_base_course_ids = $popCoursesRes[4];

        $popularCourses['credentials_share']  = $this->get_credential_pie_chart(null, $levels, $entityType, $entityId, $valid_base_course_ids, $valid_credential_ids);

        $credentialsData = array();
        foreach ($popularCourses['credentials_share'] as $key => $value) {
            // $credentialsData[] = array($value['text'], $value['share'], "<div style = 'padding:5px;width:130px;'><b>".$value['text']."</b><br />Popularity Index : <b>".$value['share']."</b></div>");

            $credentialsData[] = array($value['text'], $value['share']);
        }


        $popularCourses['credentials_data'] = $credentialsData;

        // Removing invalid course_levels from filter drop-down
        foreach($popularCourses['levels'] as $level_id => $level_text) {
            if(!in_array($level_id, $valid_course_levels)) {
                unset($popularCourses['levels'][$level_id]);
            }
        }
        //_p($popularCourses['levels']);
        $popularCourses['maxPages']           = ceil($popularCourses['totalResults']/$popularCourses['itemsPerPage']);

        return $popularCourses;
    }

    function getPopularExamsData($stream, $pageNumber=1, $entityType=null, $entityId=null){
        //_p("\n\n\n\n\n\n\n\nentered");
        //die();
        $popularExams = array();

        $popularExams['pageNumber']   = $pageNumber;
        $popularExams['itemsPerPage'] = NUM_EXAM_PER_PAGE;

        $popularExams['current_stream']      = $stream;

        $popExamsRes = $this->getPopularExams($pageNumber, $stream);
        $popularExams['exams']  = $popExamsRes[0];
        $popularExams['totalResults'] = $popExamsRes[1];
        $popularExams['filterStreamIds'] = $popExamsRes[2];
        $popularExams['maxPages'] = ceil($popularExams['totalResults']/$popularExams['itemsPerPage']);
        /*$popularExams['exams'] = array(      array("name" => "CAT",
                                                           "share" => 90),
                                                    array("name" => "MAT",
                                                           "share" => 70),
                                                    array("name" => "CMAT",
                                                           "share" => 30),
                                                    array("name" => "IIT-JEE",
                                                           "share" => 30),
                                                    array("name" => "Bachelor in Pharmacy",
                                                           "share" => 30),
                                                    array("name" => "B.Com",
                                                           "share" => 90),
                                                    array("name" => "M.B.B.S",
                                                           "share" => 70),
                                                    array("name" => "B.A",
                                                           "share" => 30),
                                                    array("name" => "B.Sc",
                                                           "share" => 30),
                                                    array("name" => "LLB",
                                                           "share" => 30));
        */
        return $popularExams;
    }

    function getPopularSpecializationsData($stream, $pageNumber=1, $entityName = null, $entityId = null){
        $popularSpecialization = array();

        $popularSpecialization['pageNumber']     = $pageNumber;
        $popularSpecialization['itemsPerPage']   = NUM_SPECIALIZATION_PER_PAGE;
        $popularSpecialization['current_stream'] = $stream;
        $popularSpecialization['entityType']     = $entityName;
        $popularSpecialization['entityId']       = $entityId;

        if($entityName == "base_course") {$entityName = "baseCourse";}


        $popSpecRes = $this->getPopularCourseEntities("specialization", $pageNumber, $stream, null, null, null, true, $entityName, $entityId);
        //_p($popSpecRes);
        $popularSpecialization['specialization'] = $popSpecRes[0];
        $popularSpecialization['totalResults'] = $popSpecRes[1];
        $popularSpecialization['filterStreamIds'] = $popSpecRes[2];


        $popularSpecialization['maxPages']       = ceil($popularSpecialization['totalResults']/$popularSpecialization['itemsPerPage']);

        /*
        $popularSpecialization['specialization'] = array(array("name" => "Fashion Designing",
                                                           "share" => 90),
                                                    array("name" => "Advertising",
                                                           "share" => 70),
                                                    array("name" => "Finance",
                                                           "share" => 30),
                                                    array("name" => "St. Xaviers University",
                                                           "share" => 30),
                                                    array("name" => "Bachelor in Pharmacy",
                                                           "share" => 30),
                                                    array("name" => "B.Com",
                                                           "share" => 90),
                                                    array("name" => "M.B.B.S",
                                                           "share" => 70),
                                                    array("name" => "B.A",
                                                           "share" => 30),
                                                    array("name" => "B.Sc",
                                                           "share" => 30),
                                                    array("name" => "LLB",
                                                           "share" => 30));
*/
        return $popularSpecialization;
    }

    function getPopularQuestionsData($entityType = null, $entityId= null){

        $popularQuestions = array();

        $popularQuestions['pageNumber']     = 1;
        $popularQuestions['itemsPerPage']   = NUM_QUESTIONS_PER_PAGE;

        $this->taggingcmsmodel = $this->CI->load->model("Tagging/taggingcmsmodel");

        $tagEntityType = "";
        switch ($entityType) {
            case 'institute':
                $tagEntityType = 'institute';
                break;
            case 'university':
                $tagEntityType = 'National-University';
                break;

            case 'base_course':
                $tagEntityType = 'Course';
                break;

            case 'specialization':
                $tagEntityType = 'Specialization';
                break;

            case 'exam':
                $tagEntityType = 'Exams';
                break;

            case 'stream':
                $tagEntityType = 'Stream';
                break;

            case 'substream':
                $tagEntityType = 'Sub-Stream';
                break;

            default:
                $tagEntityType = '';
                break;
        }

        if(!empty($tagEntityType))
            $tagMappingData = $this->taggingcmsmodel->fetchShikshaEntityToTagsMapping($entityId, $tagEntityType);

        $tags = array();
        if(!empty($tagMappingData))
            $tags[] = $tagMappingData['tagId'];

        $data = $this->CI->TrendsModel->getPopularQnA($tags, NUM_QUESTIONS_PER_PAGE);

        foreach ($data as $key => $value) {

            $tempList = array();            
            $tempList['name'] = $value['msgTxt'];
            $tempList['answers'] = $value['msgCount'];

            $tempList['link'] = getSeoUrl($value['threadId'], 'question', $value['msgTxt'],array(),'NA',$value['creationDate']);
            $popularQuestions['questions'][] = $tempList;
        }

        return $popularQuestions;
    }

    function getPopularArticlesData($entityType = null, $entityId= null){

        $popularArticles = array();

        // $ArticleUtilityLib = $this->CI->load->library('article/ArticleUtilityLib'); // load library
        
        switch ($entityType) {

            case 'stream':
                $data = array('entityType'=>'stream', 'streamId'=>$entityId); //input params
                break;

            case 'substream':
                $data = array('entityType'=>'substream', 'substreamId'=>$entityId); //input params
                break;

            case 'base_course':
                $data = array('entityType'=>'popularCourse', 'popularCourseId'=>$entityId); //input params
                break;

            case 'institute':
                $data = array('entityType'=>'college', 'popularCourseId'=>$entityId); //input params
                break;
            case 'university':
                $data = array('entityType'=>'university', 'popularCourseId'=>$entityId); //input params
                break;

            case 'exam':
                $data = array('entityType'=>'exam', 'popularCourseId'=>$entityId); //input params
                break;

            case 'specialization':

                $this->CI->load->builder('ListingBaseBuilder', 'listingBase');
                $listingBaseBuilder   = new ListingBaseBuilder();
                $spezRepo = $listingBaseBuilder->getSpecializationRepository();
                $spezObj = $spezRepo->find($entityId);

                if(empty($spezObj)){
                    $data = array('entityType'=>$entityType, 'entityId'=>$entityId); //input params
                }
                else{
                    $steamId = $spezObj->getPrimaryStreamId();
                    $substeamId = $spezObj->getPrimarySubStreamId();
                    $data = array('entityType'=>'specialization', 'streamId' => $steamId,'substreamId'=>$substeamId, 'specializationId' => $entityId); //input params
                }
                break;
            default:
                $data = array('entityType'=>$entityType, 'entityId'=>$entityId); //input params
                break;
        }

        $result = $this->CI->TrendsModel->getPopularArticles($data);
        // $result = $ArticleUtilityLib->getArticleBasedOnEntity($data, NUM_QUESTIONS_PER_PAGE);

        $popularArticles['pageNumber']     = 1;
        $popularArticles['itemsPerPage']   = NUM_QUESTIONS_PER_PAGE;

        foreach ($result['articleDetail'] as $key => $value) {
            $popularArticles['articles'][] = array('name' => $value['title'], 'link' => $value['url']);
        }

        return $popularArticles;
    }

    function getInterestByTimeData($entityType, $entityId){
        //_p($entityType."***".$entityId);
        $pageTypeFilter = "";
        $entityToCheck = "";
        if($entityType == "university") {
            $pageTypeFilter = "ulp_comb";
            $entityToCheck = "entityId";
        } else if($entityType == "institute") {
            $pageTypeFilter = "ilp_comb";
            $entityToCheck = "entityId";
        } else if($entityType == "exam") {
            $pageTypeFilter = "exam";
            $entityToCheck = "entityId";
        } else if($entityType == "base_course") {
            $pageTypeFilter = "clp_comb";
            $entityToCheck = "baseCourseId";
        } else {
            $pageTypeFilter = "clp_comb";
            $entityType = $entityType."Id";
            $entityToCheck = $entityType;
        }

        $query_filters = array();
        array_push($query_filters, ["pageType" => $pageTypeFilter]);
        array_push($query_filters, [$entityToCheck => $entityId]);

        $result = $this->getElasticQueryResults($query_filters, "date", "num_pageviews", 30, 0, null, true);

        $result_small = $result["aggregations"]["group_by_entity"]["buckets"];

        $date_wise_pv = array();

        $max_page_views = 0;
        foreach($result_small as $id => $value) {
            $page_views = $value["entity_to_sum"]["value"];
            $date_wise_pv[$value["key_as_string"]] = $page_views;
            if($page_views > $max_page_views) {
                $max_page_views = $page_views;
            }
        }

        ksort($date_wise_pv);
        $final_res_array = array();

        if(!empty($date_wise_pv)){
            $oldDate = new DateTime("2017-06");
            $newDate = new DateTime("2018-06"); 

            for ($i=0; $i < 12; $i++) { 

                $oldDate_text1 = $oldDate->format('Y-m');
                $oldDate_text2 = $oldDate->format('M-Y');

                $page_views = $date_wise_pv[$oldDate_text1];
                $page_score_old = round((intval($page_views)/$max_page_views) * 100);

                // $date_str_old = "<div style='padding:5px;width:130px;'><b>".$oldDate_text2."</b><br />Popularity Index:<b>$page_score_old</b></div>";
                $date_str = $newDate->format('M');

                $newDate_text1 = $newDate->format('Y-m');
                $newDate_text2 = $newDate->format('M-Y');

                $page_views = $date_wise_pv[$newDate_text1];
                $page_score_new = round((intval($page_views)/$max_page_views) * 100);
     
                // $date_str_new = "<div style='padding:5px;width:130px;'><b>".$newDate_text2."</b><br />Popularity Index:<b>$page_score_new</b></div>";
        
                array_push($final_res_array, array($date_str, $page_score_old, $oldDate_text2 ,$page_score_new, $newDate_text2));

                $oldDate->add(new DateInterval('P1M'));
                $newDate->add(new DateInterval('P1M'));
            }
        }

        $data = array();
        $data['chart_data'] = $final_res_array;
        return $data;
    }

    function getInterestByRegion($entityType, $entityId, $pageNumber=1){

        $data                 = array();
        $data['pageNumber']   = $pageNumber;
        $data['itemsPerPage'] = NUM_REGIONS_PER_PAGE;
        
        $data['entityType']   = $entityType;
        $data['entityId']     = $entityId;

        $num_results_to_get = NUM_REGIONS_PER_PAGE;
        $resultsOffset = NUM_REGIONS_PER_PAGE * ($pageNumber - 1);

        $pageTypeFilter = "";
        $entityToCheck = "";
        if($entityType == "university") {
            $pageTypeFilter = "ulp_comb";
            $entityToCheck = "entityId";
        } else if($entityType == "institute") {
            $pageTypeFilter = "ilp_comb";
            $entityToCheck = "entityId";
        } else if($entityType == "exam") {
            $pageTypeFilter = "exam";
            $entityToCheck = "entityId";
        } else if($entityType == "base_course") {
            $pageTypeFilter = "clp_comb";
            $entityToCheck = "baseCourseId";
        } else {
            $pageTypeFilter = "clp_comb";
            $entityToCheck = $entityType."Id";
        }

        $query_filters = array();
        array_push($query_filters, ["pageType" => $pageTypeFilter]);
        array_push($query_filters, [$entityToCheck => $entityId]);
        $result = $this->getElasticQueryResults($query_filters, "visitor_state_id", "num_pageviews", 28, 0);
        $result_small = $result["aggregations"]["group_by_entity"]["buckets"];

        $max_page_views = $result_small[0]["entity_to_sum"]["value"];

        $state_page_views_arr = array();
        $state_id_list = array();
        foreach($result_small as $id => $value) {
            array_push($state_id_list, $value["key"]);
            $state_page_views_arr[$value["key"]] = $value["entity_to_sum"]["value"];
        }

        $state_res = $this->CI->TrendsModel->getNamesForStates();

        $all_state_names = $state_res[0];

        $all_state_ids = $state_res[1];
        $state_ids_with_res = array();

        $state_result_map = array();
        $state_result_paginated = array();
        $state_result_to_paginate = array();
        $num_unique_entities = 0;
        $max_state_share = 0;
        foreach($state_id_list as $state_id) {
            if(array_key_exists($state_id, $all_state_names)) {
                $state_name = $all_state_names[$state_id];
                $state_views = $state_page_views_arr[$state_id];
                $state_share = round((intval($state_views)/$max_page_views)*100);
                array_push($state_result_map, array($state_name, $state_share));
                if($state_share > 0) {
                    // This is the list of state ids with non-zero share
                    array_push($state_ids_with_res, $state_id);
                    array_push($state_result_map, array($state_name, $state_share));
                    array_push($state_result_to_paginate, 
                        array("name" => $state_name, "share" => $state_share));
                    $num_unique_entities += 1;
                    $max_state_share = $state_share;
                }
            }
        }
        $data['totalResults'] = $num_unique_entities;
        $data['maxPages']     = ceil($data['totalResults']/$data['itemsPerPage']);
        $state_result_paginated = array_slice($state_result_to_paginate, $resultsOffset, $num_results_to_get);
        $data['states'] = $state_result_paginated;
        /*
        $data['states'] = array(    array("name" => "Karanataka", "share" => 100),
                                    array("name" => "Maharashtra", "share" => 90),
                                    array("name" => "Kerala", "share" => 86),
                                    array("name" => "Rajasthan", "share" => 71),
                                    array("name" => "Delhi NCR", "share" => 22),
                                    array("name" => "Jammu & Kashmir", "share" => 11),
                                    array("name" => "Telangana", "share" => 10),
                                    array("name" => "Andhra Pradesh", "share" => 4),
                                    array("name" => "Assam", "share" => 3),
                                    array("name" => "Goa", "share" => 1));

        */
        if($max_state_share == 0) {
            $state_result_map = null; 
        } else {
            $states_no_res = array_diff($all_state_ids , $state_ids_with_res);
            foreach($states_no_res as $state_id) {
                $state_name = $all_state_names[$state_id];
                array_push($state_result_map, array($state_name, 0));
            }
        }

        //array_push($state_result_map, array("Uttaranchal", 0));

        $data['chart_data'] = $state_result_map;
        /*
        $data['chart_data'] = array(   array('Uttar Pradesh', 199581477),
                                            array('Maharashtra', 112372972),
                                            array('Bihar', 103804637),
                                            array('West Bengal', 91347736),
                                            array('Madhya Pradesh', 72597565),
                                            array('Tamil Nadu', 72138958),
                                            array('Rajasthan', 68621012),
                                            array('Karnataka', 61130704),
                                            array('Gujarat', 60383628),
                                            array('Andhra Pradesh', 49386799),
                                            array('Odisha', 41947358),
                                            array('Telangana', 35286757),
                                            array('Kerala', 33387677),
                                            array('Jharkhand', 32966238),
                                            array('Assam', 31169272),
                                            array('Punjab', 27704236),
                                            array('Chhattisgarh', 25540196),
                                            array('Haryana', 25353081),
                                            array('Jammu and Kashmir', 12548926),
                                            array('Uttarakhand', 10116752),
                                            array('Himachal Pradesh', 6856509),
                                            array('Tripura', 3671032),
                                            array('Meghalaya', 2964007),
                                            array('Manipur', 2721756),
                                            array('Nagaland', 1980602),
                                            array('Goa', 1457723),
                                            array('Arunachal Pradesh', 1382611),
                                            array('Mizoram', 1091014),
                                            array('Sikkim', 607688),
                                            array('Delhi', 16753235),
                                            array('Puducherry', 1244464),
                                            array('Chandigarh', 1054686),
                                            array('Andaman and Nicobar Islands', 379944),
                                            array('Dadra and Nagar Haveli', 342853),
                                            array('Daman and Diu', 242911),
                                            array('Lakshadweep', 6442));
*/
         return $data;
    }

    function getPopularityIndex($entityType, $entityId){
        // Deciding the pageTypeFilter
        $pageTypeFilter = "";
        $entityToCheck = "";
        if($entityType == "university") {
            $pageTypeFilter = "ulp_comb";
            $entityToGet = "entityId";
        } else if($entityType == "institute") {
            $pageTypeFilter = "ilp_comb";
            $entityToGet = "entityId";
        } else if($entityType == "exam") {
            $pageTypeFilter = "exam";
        } else if($entityType == "base_course") {
            $pageTypeFilter = "clp_comb";
            $entityToGet = "baseCourseId";
        } else {
            $pageTypeFilter = "clp_comb";
            $entityToGet = $entityType."Id";
        }

        $entityNameToFilter = $entityToGet;
        $IdOfEntityToFilter = $entityId;
        $page_score = 0;
        if($pageTypeFilter == "exam") {
            $popularExams = $this->getPopularExams(null, null, true);
           // _p($popularExams);
            foreach($popularExams as $single_exam_arr) {
                //_p($single_exam_arr["id"]);
                if(intval($single_exam_arr["id"]) == intval($entityId)) {
                    $page_score = $single_exam_arr["share"];
                }
            }
        } else {
            // For finding the max value's pageviews
            $query_filters_max = array();
        
            // For finding that particular entity's pageviews
            $query_filters_entity = array();

            array_push($query_filters_max, ["pageType" => $pageTypeFilter]);
            array_push($query_filters_entity, ["pageType" => $pageTypeFilter]);

            if($entityNameToFilter != null && $IdOfEntityToFilter != null) {
                array_push($query_filters_entity, [$entityNameToFilter => $IdOfEntityToFilter]);
            }

            // For max pageviews
            $result_uniq = $this->getElasticQueryResults($query_filters_max, $entityToGet, "num_pageviews", 3, null, 10);
            $result_uniq_small = $result_uniq["aggregations"]["group_by_entity"]["buckets"];
            // This is the maximum number of pageviews 
            $max_page_views = $result_uniq_small[0]["entity_to_sum"]["value"];

            // For entity's pageview's
            $result_uniq = $this->getElasticQueryResults($query_filters_entity, $entityToGet, "num_pageviews", 1, null, 10);
            $result_uniq_small = $result_uniq["aggregations"]["group_by_entity"]["buckets"];
            // This is the maximum number of pageviews 
            $entity_page_views = $result_uniq_small[0]["entity_to_sum"]["value"];
            $page_score = round((intval($entity_page_views)/$max_page_views) * 100);
        }

        return $page_score;
        //_p("entity views = $entity_page_views");

        //_p("max views = $max_page_views");

        //_p("Score is = $page_score");

        //_p($result_uniq_small);

    }
    
    function _padZeroValues($entityType, &$data){

        switch ($entityType) {
            case 'stream':
                $this->CI->load->builder('ListingBaseBuilder', 'listingBase');
                $listingBaseBuilder   = new ListingBaseBuilder();
                $streamRepo = $listingBaseBuilder->getStreamRepository();
                $allStreams = $streamRepo->getAllStreams();
                $assoList = array();
                foreach ($data as $value) {
                    $assoList[$value[0]] = 1;
                }

                foreach ($allStreams as $value) {
                    if(empty($assoList[$value['name']]))
                        $data[] = array($value['name'],0);
                }
                break;
            
            default:
                # code...
                break;
        }

    }
}
