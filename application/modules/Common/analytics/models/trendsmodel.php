<?php
class trendsmodel extends MY_Model {
    private $dbHandle = '';


	function __construct() {
		parent::__construct();

        $this->trendsCache = $this->load->library('analytics/cache/TrendsCache');
    }

    private function initiateModel($mode = "write") {
		if($this->dbHandle && $this->dbHandleMode == 'write') {
		    return;
		}
        
        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        } else {
            $this->dbHandle = $this->getWriteHandle();
        }
    }

    // If $caching_enabled = false then just get the meta data and don't cache. This will be done 
    // only for the small id array (mostly 10 elements)
    // For bigger id array like 700+, it will be $caching_enabled = true
    function getLabelsForUnivAndInst($ids, $entity_type, $caching_enabled = false, $cache_key) {

        //Getting from the institute repository
        $result_arr_fin = array();
        if(empty($ids))
            return $result_arr_fin;

        if($caching_enabled) {
            $startTime = microtime(true);
            $cached_univ_inst_data = $this->trendsCache->getCachedStatesForUnivInstWidget($cache_key, $entity_type);
            $endTime = microtime(true);
            $diff = $endTime - $startTime;
            if($cached_univ_inst_data) {
                $decoded_cached_data = json_decode($cached_univ_inst_data, true);
                //error_log("DIFF 1 => $diff \n", 3, "/tmp/analytics.log");
                return $decoded_cached_data;
            }
        }

        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();       
        $instituteList = $this->instituteRepo->findMultiple($ids,array('basic', 'location'));
        foreach ($instituteList as $key => $instObj) {
            $mainLocObj = $instObj->getMainLocation();
            if($mainLocObj) {
                if($caching_enabled) {
                    $result_arr_fin[$instObj->getId()] = array(
                        "stateId" =>  $mainLocObj->getStateId()
                    );
                } else {
                    $result_arr_fin[$instObj->getId()] = array(
                        "name" => $instObj->getName(), 
                        "location" => $mainLocObj->getCityName(),
                        "type" => $instObj->getType(),
                        "stateId" =>  $mainLocObj->getStateId()
                    );
                }
            }
        }
        //error_log(print_r($db->last_query(),TRUE));
        //$result["entity_name"] = $entity_name;
        if($caching_enabled) {
            $this->trendsCache->putCachedStatesForUnivInstWidget($cache_key, $entity_type, $result_arr_fin);
        }
        return $result_arr_fin;
    }

    function getLabelsForCourseEntities($entity_name, $ids) {
        $result_arr_fin = array();

        if(empty($ids))
            return $result_arr_fin;

        $this->load->builder('ListingBaseBuilder', 'listingBase');
        $listingBaseBuilder   = new ListingBaseBuilder();
        //$streamRepoObj = $listingBaseBuilder->getStreamRepository();
        //_p($streamRepoObj->findMultiple($ids));
        //$x = "Stream";
        $repoGetFunction = "get".ucfirst($entity_name)."Repository";
        $entityRepoObj = $listingBaseBuilder->$repoGetFunction();
        $entityRepoObjList = $entityRepoObj->findMultiple($ids);

        $streamAliases = array(1 => "Management");
        foreach($entityRepoObjList as $index => $entityObj) {
            if($entity_name=='stream'){
                $result_arr_fin[$entityObj->getId()] = array("name" => $streamAliases[$entityObj->getId()] ? $streamAliases[$entityObj->getId($entityObj)] : $entityObj->getName());
            } else if($entity_name == 'baseCourse') {
                $result_arr_fin[$entityObj->getId()] = array(
                    "name" => $entityObj->getName(),
                    "credential" => $entityObj->getCredential(),
                    "course_level" => $entityObj->getLevel()
                );
            } else if($entity_name == 'specialization') {

                $result_arr_fin[$entityObj->getId()] = array("name" => $entityObj->getName(), 
                                                             "stream" => $entityObj->getPrimaryStreamId()
                                                            );
                //_p($entityObj);
            } else {
                $result_arr_fin[$entityObj->getId()] = array("name" => $entityObj->getName());
                //_p($entityObj);

            }
        }

       // _p($result_arr);
       // _p($result_arr_fin);

        return $result_arr_fin;
    }

    function getFilteredUnivInstDataAndUniques($entity_page_views, $entity_meta_data, $state_filter) {
        /*******************************************************************
         Finding entity_ids with >1 % score
         After that finding unique course_levels and credentials
        */
        $unique_state_ids= array();
        $filtered_entity_ids = array();

        $max_page_views = reset($entity_page_views);
        foreach($entity_page_views as $entity_id => $page_views) {
            if(array_key_exists($entity_id, $entity_meta_data)) {
                $share = round((intval($page_views)/$max_page_views)*100);
                if($share > 0) {
                    $meta_data = $entity_meta_data[$entity_id];
                    $stateId = $meta_data["stateId"];
                        if(!in_array($stateId, $unique_state_ids)) {
                            array_push($unique_state_ids, $stateId);
                        }

                        if(!$state_filter || ($state_filter && $stateId == $state_filter)) {
                            array_push($filtered_entity_ids, $entity_id);
                        }
                }
            }
        }
        //***************************************************************************

        // For the filtered entity ids now putting in the meta data for displaying
        $final_filtered_result = array();
        $max_page_views_filtered_data = $entity_page_views[$filtered_entity_ids[0]];
        foreach($filtered_entity_ids as $entity_id) {
            $page_views = $entity_page_views[$entity_id];
            $share = round((intval($page_views)/$max_page_views_filtered_data)*100);
            $meta_data = $entity_meta_data[$entity_id];
            // $name = $meta_data["name"];
            // $location = $meta_data["location"];
            // $type = $meta_data["type"];

            // array_push($final_filtered_result, array("name" => $name, 
            //                                          "share" => $share,
            //                                          "location" => $location,
            //                                          "id" => $entity_id,
            //                                          "type" => $type));
            array_push($final_filtered_result, array("share" => $share,
                                                     "id" => $entity_id));
        }

        asort($unique_state_ids);

        return array($final_filtered_result, $unique_state_ids);
    }

    /* 
    Inputs will be :- 
        1) Full page views data returned by Elasticsearch 
        2) Full labels data returned by DB
        3) Minimum threshold 
    This function will do the following from the data returned by Elasticsearch and Mysql Labels :-
        1) Get distinct courseLevels
        2) Get distinct credentials
        3) Filter data based on the course_level filter sent
        4) [good to have] Remove data below the threshold and also give share score here
    */
    function getFilteredCourseDataAndUniques($entity_page_views, $entity_meta_data, $entity_name, $course_level_filter, $stream_filter) {
        /*******************************************************************
         Finding entity_ids with >1 % score
         After that finding unique course_levels and credentials
        */
        $unique_course_levels = array();
        $unique_credentials = array();
        $unique_streams = array();
        $filtered_entity_ids = array();
        //_p("\n\n\n\\n");
        //_p(reset($entity_page_views));
        $max_page_views = reset($entity_page_views);
        foreach($entity_page_views as $entity_id => $page_views) {
            if(array_key_exists($entity_id, $entity_meta_data)) {
                $share = round((intval($page_views)/$max_page_views)*100);
                if($share > 0) {
                    $meta_data = $entity_meta_data[$entity_id];
                    $text = $meta_data["name"];
                    if($entity_name == "baseCourse") {
                        $credential_arr = $meta_data["credential"];
                        $course_level = $meta_data["course_level"];

                        /*
                        // Getting unique credentials
                        foreach($credential_arr as $cred) {
                            if(!in_array($cred, $unique_credentials)) {
                                array_push($unique_credentials, $cred);
                            }
                        }*/
           
                        // Getting unique course_levels
                        if(!in_array($course_level, $unique_course_levels)) {
                            array_push($unique_course_levels, $course_level);
                        }

                        if(!$course_level_filter || ($course_level_filter && $course_level == $course_level_filter)) {
                            array_push($filtered_entity_ids, $entity_id);

                            // Getting unique credentials
                            foreach($credential_arr as $cred) {
                                if(!in_array($cred, $unique_credentials)) {
                                    array_push($unique_credentials, $cred);
                                }
                            }
                        }
                    } else if ($entity_name == "specialization") {
                        $streamId = $meta_data["stream"];
                        if(!in_array($streamId, $unique_streams)) {
                            array_push($unique_streams, $streamId);
                        }

                        if(!$stream_filter || ($stream_filter && $streamId == $stream_filter)) {
                            array_push($filtered_entity_ids, $entity_id);
                        }
                    }
                }
            }
        }
        //***************************************************************************
        // For the filtered entity ids now putting in the meta data for displaying
        $final_filtered_result = array();
        $max_page_views_filtered_data = $entity_page_views[$filtered_entity_ids[0]];
        foreach($filtered_entity_ids as $entity_id) {
            $page_views = $entity_page_views[$entity_id];
            $share = round((intval($page_views)/$max_page_views_filtered_data)*100);
            $meta_data = $entity_meta_data[$entity_id];
            $text = $meta_data["name"];
            array_push($final_filtered_result, array("text" => $text, "share" => $share, "id" => $entity_id));
        }
        if($entity_name == "baseCourse") {
            asort($unique_course_levels);
            asort($unique_credentials);
            return array($final_filtered_result, $unique_course_levels, $unique_credentials, $filtered_entity_ids);
        } else if ($entity_name == "specialization")  {
            asort($unique_streams);
            return array($final_filtered_result, $unique_streams);
        }
    }

    function getSpecializationsForUnivInst2($inst_univ_id_list) {

        $course_id_res = $this->getCoursesForUnivInst2($inst_univ_id_list);
        $instWiseCourseIds = $course_id_res[0];
        $course_ids = $course_id_res[1];
        if(empty($course_ids)){
            return array();
        }
        $course_specialization_map = array();
         $startTime = microtime(true);

        $db = $this->getReadHandle();
        $db->select("course_id, specialization_id ");
        $db->distinct();
        $db->from(" shiksha_courses_type_information ");
    
        $db->where("course_id IN (".implode(",",$course_ids).") AND status = 'live' and specialization_id !=0");
        $sql_result = $db->get()->result();

        foreach($sql_result as $index => $value) {
            if(array_key_exists($value->course_id, $course_specialization_map)) {
                $specialization_arr = $course_specialization_map[$value->course_id];
                $specialization_arr [] = $value->specialization_id;
                $course_specialization_map[$value->course_id] = $specialization_arr;
            } else {
                $course_specialization_map[$value->course_id] = array($value->specialization_id);
            }
        }
        $finalInstWiseSpecIds = [];
        foreach($instWiseCourseIds as $inst_id => $course_ids) {
            $specialization_list = [];
            foreach($course_ids as $single_course) {
                if(array_key_exists($single_course, $course_specialization_map)) {
                    foreach($course_specialization_map[$single_course] as $spec_id) {
                        $specialization_list [] = $spec_id;
                    }
                }
            }
            $finalInstWiseSpecIds[$inst_id] = $specialization_list;
        }
        return $finalInstWiseSpecIds;
    }

    function getCoursesForUnivInst2($inst_univ_id_list) {
        $this->load->library('nationalInstitute/InstituteDetailLib');
        $institutePostingLib = new InstituteDetailLib;  
        $startTime = microtime(true);

        // _p(implode("+OR+",$inst_univ_id_list));

        $instWiseCourseIds = $institutePostingLib->getAllCoursesForMultipleInstitutes($inst_univ_id_list,"all", true, false);
        // _p($instWiseCourseIds );
        $instWiseCourseIdsSmall = [];
        $allCourseIds = [];
        foreach($instWiseCourseIds as $instId => $value) {
            $instWiseCourseIdsSmall[$instId] = $value["courseIds"];
            foreach($value["courseIds"] as $course_id) {
                $allCourseIds [] = $course_id;
            };
        }
        return array($instWiseCourseIdsSmall, $allCourseIds);

    }

    // function getSpecializationsForUnivInst($inst_univ_id) {
    //     $course_ids = $this->getCoursesForUnivInst($inst_univ_id);
    //     if(empty($course_ids)){
    //         return array();
    //     }
    //     $specialization_ids = array();

    //     $db = $this->getReadHandle();
    //     $db->select("distinct(specialization_id) ");
    //     $db->from("shiksha_courses_type_information ");
    
    //     $db->where("course_id IN (".implode(",",$course_ids).") AND status = 'live' and specialization_id !=0");
    //     $sql_result = $db->get()->result();

    //     foreach($sql_result as $index => $value) {
    //         array_push($specialization_ids, $value->specialization_id);       
    //     }
    //     return $specialization_ids;
    // }

    // // This function gets the course_ids
    // function getCoursesForUnivInst($inst_univ_id) {
    //     $this->load->library('nationalInstitute/InstituteDetailLib');
    //     $institutePostingLib = new InstituteDetailLib;  
    //     return $institutePostingLib->getInstituteCourseIds($inst_univ_id)["courseIds"];
    // }


    function getFilteredInstIdsForSpecialization($inst_univ_id_list, $valid_spec_id) {

        $this->load->library('search/Solr/SolrClient');
        $this->solrClient = new SolrClient;
        return $this->solrClient->getFilteredInstitutesForSpecialization($inst_univ_id_list, $valid_spec_id);
    }


    function getLabelsForCredentials($cred_ids_list) {

        if(empty($cred_ids_list))
            return array();

        $this->load->library('listingBase/BaseAttributeLibrary');
        $baseAttributeLibrary = new BaseAttributeLibrary();
        return $baseAttributeLibrary->getValueNameByValueId($cred_ids_list);
    }

    function getCredentialsForBaseCourses($base_course_id) {
        if(empty($base_course_id))
            return array();
        $this->load->builder('listingBase/ListingBaseBuilder');

        $ListingBaseBuilder = new ListingBaseBuilder();
        $basecourseRepo = $ListingBaseBuilder->getBaseCourseRepository();
        $courseObj = $basecourseRepo->find($base_course_id);
        return $courseObj->getCredential();
    }

    function getNamesForStates() {

        $this->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        //$stateObjList = $locationRepository->findMultipleStates($state_ids_list);
        
        // The list of all states
        $fullStateObjList = $locationRepository->getStatesByCountry(2);
        
        $allStateIdsList = array();
        $allStateNames = array();
        foreach($fullStateObjList as $index => $stateObj) {
            $state_id = $stateObj->getId();
            array_push($allStateIdsList, $state_id);
            $allStateNames[$state_id] = $stateObj->getName();
        }

        return array($allStateNames, $allStateIdsList);
    }

    function getLabelsForExams($exam_ids) {

        $result_arr = array();

        if(empty($exam_ids))
           return $result_arr;

        $db = $this->getReadHandle();
        $db->select(array("id", "exampageId", "name"));
        $db->from("exampage_main");
    
        $db->where("(exampageId IN (".implode(",",$exam_ids).") OR id IN (".implode(",",$exam_ids).")) AND status = 'live'");
        $sql_result = $db->get()->result();
        
        $this->load->builder('examPages/ExamBuilder');
        $examBuilder    = new ExamBuilder();
        $examRepository = $examBuilder->getExamRepository();
        $this->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new ListingBaseBuilder();
        $hierarchyRepository = $listingBase->getHierarchyRepository();

        $exam_ids_arr = array();

        foreach($sql_result as $index => $value) {
            // $result_arr['data'] is an ID => exam_name HashMap
            // $result_arr['mapping'] is a exam_name => ID HashMap
            $examStreams = array();

            $result_arr['data'][$value->exampageId] = $value->name;
            $result_arr['data'][$value->id] = $value->name;
            $result_arr['mapping'][$value->name] = $value->id;

            // creating a list of exam ids
            array_push($exam_ids_arr, $value->id);
        }

        $result_arr['data'][304] = "BITSAT";
        $result_arr['mapping']["BITSAT"] = 304;

        $result_arr['data'][306] = "MAT";
        $result_arr['mapping']["MAT"] = 306;

        $result_arr['data'][307] = "SNAP";
        $result_arr['mapping']["SNAP"] = 307;

        $result_arr['data'][309] = "XAT";
        $result_arr['mapping']["XAT"] = 309;
        
        $result_arr['data'][327] = "CAT";
        $result_arr['mapping']["CAT"] = 327;
        // NEW CODE ************************************
        // Now creating a mapping of the examName --> streamId
        $exam_name_stream_map = array();
        $exam_ids_arr_uniq = array_unique($exam_ids_arr);

        $examObjects = $examRepository->findMultiple($exam_ids_arr_uniq);
        $exam_name_hier_ids_map = array();
        $fullExamHierarchyIdList = array();

        $all_group_ids_list = array();
        foreach($examObjects as $singleExamObj) {
            $one_exam_group_ids = array();
            $examGroupMapping = $singleExamObj->getGroupMappedToExam();
            foreach ($examGroupMapping as $i => $groupValue) {
                $all_group_ids_list[] = $groupValue["id"];
                $one_exam_group_ids[] = $groupValue["id"];
            }
            $exam_id = $singleExamObj->getId();

            $exam_name = $result_arr['data'][$exam_id];
            $exam_name_group_ids_map[$exam_name] = $one_exam_group_ids;
        }


        $group_ids_uniq = array_unique($all_group_ids_list);
        $groupObjList = $examRepository->findMultipleGroup($group_ids_uniq);

        // Final output will be the $exam_name_hier_ids_map
        foreach($exam_name_group_ids_map as $exam_name => $group_id_list) {
            $oneExamHierarchyIdList = array();
            foreach($group_id_list as $group_id) {
                $groupObj = $groupObjList[$group_id];
                if($groupObj) {
                    $hierarchies = $groupObj->getHierarchy();
                    foreach($hierarchies as $id => $value) {
                        $oneExamHierarchyIdList[] = $id;
                        $fullExamHierarchyIdList[] = $id;
                    }
                }
            }
            $exam_name_hier_ids_map[$exam_name] = $oneExamHierarchyIdList;
        }

        error_log(print_r($exam_name_hier_ids_map, true));

        $fullExamHierarchyIdListUniq = array_unique($fullExamHierarchyIdList);
        $hierarchyMappingDataFull = $hierarchyRepository->getBaseEntitiesByHierarchyId($fullExamHierarchyIdListUniq);

        foreach($exam_name_hier_ids_map as $exam_name => $hier_id_list ) {
            $stream_list = [];
            foreach($hier_id_list as $heirarchy_id) {
                $hier_map = $hierarchyMappingDataFull[$heirarchy_id];
                $streamId = $hier_map['stream_id'] ? $hier_map['stream_id'] :0;
                array_push($stream_list, $streamId);
            }
            $exam_name_stream_map[$exam_name] = array_unique($stream_list);
        }

        $result_arr['stream_mapping'] = $exam_name_stream_map;
        $result_arr['stream_mapping']["CAT"] = array(1);
        return $result_arr;
    }

    function getFilteredExamDataAndUniques($entity_page_views, $entity_meta_data, $stream_filter) {
        /*******************************************************************
         Finding entity_ids with >1 % score
         After that finding unique course_levels and credentials
        */
        $unique_stream_ids= array();
        $filtered_exam_names = array();

        $exam_name_id_map = $entity_meta_data["mapping"];
        $exam_name_stream_map = $entity_meta_data["stream_mapping"];

        $max_page_views = reset($entity_page_views);
        foreach($entity_page_views as $exam_name => $page_views) {
            if(array_key_exists($exam_name, $exam_name_id_map) && array_key_exists($exam_name, $exam_name_stream_map)) {
                $share = round((intval($page_views)/$max_page_views)*100);
                if($share > 0) {
                    $exam_id = $exam_name_id_map[$exam_name];
                    $stream_id_list = $exam_name_stream_map[$exam_name];
                    foreach($stream_id_list as $stream_id_curr) {
                        if(!in_array($stream_id_curr, $unique_stream_ids)) {
                            array_push($unique_stream_ids, $stream_id_curr);
                        }
                        if(!array_key_exists($exam_name, $filtered_exam_names)) {
                            if(!$stream_filter || ($stream_filter && $stream_id_curr == $stream_filter)) {
                              //  _p($exam_name);
                                if(!in_array($exam_name, $filtered_exam_names))
                                    array_push($filtered_exam_names, $exam_name);
                            }
                        }
                    }
                }
            }
        }
        //***************************************************************************

        // For the filtered entity ids now putting in the meta data for displaying
        $final_filtered_result = array();
        $max_page_views_filtered_data = $entity_page_views[$filtered_exam_names[0]];
        foreach($filtered_exam_names as $exam_name) {
            $page_views = $entity_page_views[$exam_name];
            $share = round((intval($page_views)/$max_page_views_filtered_data)*100);
            $id = $exam_name_id_map[$exam_name];

            $location = $meta_data["location"];
            array_push($final_filtered_result, array("name" => $exam_name, 
                                                     "share" => $share,
                                                     "id" => $id));

        }
        asort($unique_stream_ids);

        return array($final_filtered_result, $unique_stream_ids);
    }

    function getExamIdByName($examName) {

        $examId = "";

        if(empty($examName))
           return $examId;

        $db = $this->getReadHandle();
        $db->select(array("id"));
        $db->from("exampage_main");
    
        $db->where("name", $examName);
        $db->where("status", "live");

        $sql_result = $db->get()->result();

        foreach($sql_result as $index => $value) {
            $examId = $value->id;
        }

        return $examId;
    }

    function getAllStatesForListing($listingType) {

        $cached_states_for_listing = $this->trendsCache->getCachedStates($listingType);
        // $cached_states_for_listing = null; 
        $result = array();
        if(!$cached_states_for_listing) {
            $db = $this->getReadHandle();

            $sql = "SELECT DISTINCT loc.state_id, state.state_name from shiksha_institutes inst inner join shiksha_institutes_locations loc ON(inst.listing_id = loc.listing_id and inst.listing_type = loc.listing_type and inst.status='live') inner join stateTable state ON(loc.state_id = state.state_id and state.enabled=0) where loc.status='live' and loc.listing_type = ? order by state.state_name";

            $data = $this->db->query($sql, array($listingType))->result_array();

            foreach ($data as $key => $value) {
                $result[$value['state_id']] = $value['state_name'];
            }
            $this->trendsCache->putCachedStates($listingType, $result);
        } else {
            $result = $cached_states_for_listing;
        }
        return $result;
    }


    function getPopularQnA($tags, $limit = 10){

        // _p("\n\n\n\n\n\n");
        // _p($tags);
        $cache_key_prefix = "analytics_cons_qna";
        $cache_key_qna = "";
        if(empty($tags)) {
            $cache_key_qna = $cache_key_prefix."_no_tags";
        } else {
            $tags_concat = implode("_", $tags);
            $cache_key_qna = $cache_key_prefix."_$tags_concat";
        }

        $cachedQnA = $this->trendsCache->getCachedQnA($cache_key_qna);
        // $cachedQnA = null;
        if($cachedQnA) {
            return $cachedQnA;
        } else {
            // _p("\n\n\n\n\n\n\n\n\n HIT THE DB");
            $db = $this->getReadHandle();

            $one_week_ago = date('Y-m-d', strtotime('-1 week'));

            if(empty($tags)){
                $sql = "SELECT msg.threadId, msg.msgTxt, msg.msgCount from threadQualityTable qua inner join messageTable msg ON(qua.threadId=msg.msgId and qua.threadType='question' and msg.fromOthers='user') where threadType='question' AND creationDate > '".$one_week_ago."' order by qualityScore desc limit ?";
                $data = $this->db->query($sql, array($limit))->result_array();

                $result = array();
                foreach ($data as $value) {
                    $result[$value['threadId']] = $value;
                }
            }
            else{
                // $sql    = "SELECT mt1.threadId, mt1.msgTxt "
                //         . " FROM messageTable mt1 INNER JOIN tags_content_mapping tcm ON (mt1.threadId = tcm.content_id)"
                //         . " LEFT JOIN threadQualityTable tqt ON(tcm.content_id = tqt.threadId) "
                //         . " WHERE mt1.status IN ('live' , 'closed') "
                //         . " AND tcm.status = 'live' AND tcm.content_type = 'question' AND tcm.tag_type IN('objective','manual')"
                //         . " AND tcm.tag_id IN (".implode(',', $tags).") AND mt1.fromOthers = 'user' AND mt1.parentId = 0 AND mt1.mainAnswerId = -1"
                //         . " AND (mt1.listingTypeId IS NULL OR mt1.listingTypeId = 0) AND mt1.msgCount > 0 ORDER BY tqt.qualityScore DESC limit ? ";
                $two_months_ago = date('Y-m-d', strtotime('-8 week'));

                $sql = "SELECT distinct tcm.content_id  FROM tags_content_mapping tcm LEFT JOIN threadQualityTable tqt ON(tcm.content_id = tqt.threadId)  WHERE tcm.status = 'live' AND tcm.content_type = 'question' AND tcm.tag_type IN('objective','manual') AND tcm.tag_id IN (".implode(',', $tags).") AND tcm.creationTime >= '".$two_months_ago."' ORDER BY tqt.qualityScore DESC limit ?";

                $data = $this->db->query($sql, array($limit*2))->result_array();

                $threadIds = array();
                foreach ($data as $value) {
                    $threadIds[] = $value['content_id'];
                }

                $threadDataMapping = array();
                if(!empty($threadIds)){
                    $sql = "SELECT mt1.threadId, mt1.msgTxt, mt1.msgCount  FROM messageTable mt1  WHERE mt1.status IN ('live' , 'closed')  AND mt1.fromOthers = 'user' AND mt1.parentId = 0 AND mt1.mainAnswerId = -1 AND mt1.msgCount > 0 and mt1.msgId IN (".implode(',', $threadIds).")";

                    $data = $this->db->query($sql)->result_array();
                    foreach ($data as $value) {
                        $threadDataMapping[$value['threadId']] = $value;
                    }
                }

                $result = array();
                foreach ($threadIds as $value) {
                    if($threadDataMapping[$value])
                        $result[$value] = $threadDataMapping[$value];
                }

                $result = array_slice($result, 0, $limit);

            }
            $this->trendsCache->putCachedQnA($cache_key_qna, $result);
        }

        return $result;
    }

    function getPopularArticles($data) {
        $cache_key= "analytics_cons_art";
        foreach($data as $key => $val) {
           $cache_key = $cache_key."_".$val;
        }

        $cached_articles = $this->trendsCache->getCachedArticles($cache_key);
        // _p("\n\n\n\n\n\n\n\n\n\n $cache_key");
        // _p($data);

        if($cached_articles) {
            return $cached_articles;
        } else {
            $ArticleUtilityLib = $this->load->library('article/ArticleUtilityLib');
            $cached_articles = $ArticleUtilityLib->getArticleBasedOnEntity($data, NUM_QUESTIONS_PER_PAGE);
            $this->trendsCache->putCachedArticles($cache_key, $cached_articles);

            return $cached_articles;
        }
    }


    function getCourseLevels(){

        $cached_course_level_data = $this->trendsCache->getCachedCourseLevels();
        $result = array();

        if(!$cached_course_level_data) {
            $db = $this->getReadHandle();

            $sql = "SELECT DISTINCT scti.course_level, bal.value_name from shiksha_courses_type_information scti inner join base_attribute_list bal ON(scti.course_level = bal.value_id and bal.attribute_name='Course Level' and bal.status='live') where scti.status='live' order by course_level";

            $data = $this->db->query($sql)->result_array();

            
            foreach ($data as $key => $value) {
                if($value['value_name'] == 'None')
                    $result[$value['course_level']] = 'Certificate';
                else
                    $result[$value['course_level']] = $value['value_name'];
            }
            $this->trendsCache->putCachedCourseLevels($result);
        } else {
            $result = json_decode($cached_course_level_data, true);
        }

        return $result;
    }
}