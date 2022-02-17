<?php
/*
 * Author - Nikita Jain
 * Purpose - Solr result parsing used in autosuggestor
 */
require_once (APPPATH.'modules/Search/search/libraries/Solr/SolrResponseParser.php');

class AutoSuggestorSolrResponseParser extends SolrResponseParser {
    function __construct() {
    	parent::__construct();
    	$this->CI = & get_instance();

        global $statesToIgnore;
        $this->statesToIgnore = $statesToIgnore;
    }

    public function parseEntitiesAutosuggestionResults($solrEntityContent) {
    	$solrDocs = $solrEntityContent['response']['docs'];
    	foreach ($solrDocs as $key => $solrDoc) {
    		$value = $solrDoc[$this->FIELD_ALIAS['nl_entity_count_name_id_type_map']];
    		$valueArr = explode('::', $value);
    		$resultCount = $valueArr[0];
    		$entityName = $this->sanatizeInputText(html_entity_decode($valueArr[1]));
    		$entityId = $valueArr[2];
    		$entityType = $valueArr[3];

    		$entityNameIdMap[$entityName] = $this->FIELD_ALIAS[$entityType].'_'.$entityId;
    	}

    	$solrResults['data']['course_ldb_course_name_facet'] = '';
		if(!empty($entityNameIdMap)) {
			$solrResults['data']['course_ldb_course_name_facet'] = $entityNameIdMap;
		}

    	return $solrResults;
    }

    public function parseExamSearchAutoSuggestionResults($solrEntityContent){
    	$groups = $solrEntityContent['grouped']['nl_entity_type']['groups'];
    	$solrResults['examResults']['data']['course_ldb_course_name_facet'] = array();
    	$solrResults['allExamResults']['data']['course_ldb_course_name_facet'] = array();
    	$examContent = array();$allExamContent = array();
    	foreach ($groups as $row) {
    		if($row['groupValue'] == 'exam'){
    			$examContent = $row['doclist']['docs'];
    		}
    		else if($row['groupValue'] == 'allexam'){
    			$allExamContent = $row['doclist']['docs'];
    		}
    	}
    	foreach ($examContent as $key => $solrDoc) {
    		$valueArr = explode('::', $solrDoc[$this->FIELD_ALIAS['nl_entity_count_name_id_type_map']]);
    		$temp = array();
    		$temp['count'] = $valueArr[0];
    		$temp['name'] = $this->sanatizeInputText(html_entity_decode($valueArr[1]));
    		$temp['id'] = $valueArr[2];
    		$temp['type'] = $valueArr[3];
    		$temp['url'] = $solrDoc[$this->FIELD_ALIAS['nl_entity_url']];
    		$solrResults['examResults']['data']['course_ldb_course_name_facet'][$temp['name']] = $temp;
    	}
    	foreach ($allExamContent as $key => $solrDoc) {
    		$valueArr = explode('::', $solrDoc[$this->FIELD_ALIAS['nl_entity_count_name_id_type_map']]);
    		$temp = array();
    		$temp['count'] = $valueArr[0];
    		$temp['name'] = $this->sanatizeInputText(html_entity_decode($valueArr[1]));
    		$temp['subType'] = $valueArr[2];
    		$temp['id'] = $valueArr[3];
    		$temp['type'] = $valueArr[4];
    		$temp['url'] = $solrDoc[$this->FIELD_ALIAS['nl_entity_url']];
    		$solrResults['allExamResults']['data']['course_ldb_course_name_facet'][$temp['name']] = $temp;
    	}
    	return $solrResults;
    }

    public function parseQuestionAutosggestionResults($solrContent){
        foreach ($solrContent['response']['docs'] as $solrDoc) {
            $valueArr = explode('::', $solrDoc[$this->FIELD_ALIAS['nl_entity_quality_name_id_type_map']]);
            $temp = array();
            $temp['quality'] = $valueArr[0];
            $temp['name'] = $this->sanatizeInputText(html_entity_decode($valueArr[1]));
            $temp['id'] = $valueArr[2];
            $temp['type'] = $valueArr[3];
            $temp['url'] = $solrDoc[$this->FIELD_ALIAS['nl_entity_url']];
            if(empty($solrResults[$temp['name']]) || $solrResults[$temp['name']] < $temp['quality']) {
                $solrResults[$temp['name']] = $temp;
            }
        }
        return $solrResults;
    }

    public function parseQuestionTopicAutosggestionResults($solrContent){
        foreach ($solrContent['response']['docs'] as $solrDoc) {
            $valueArr = explode('::', $solrDoc[$this->FIELD_ALIAS['nl_entity_quality_name_id_type_map']]);
            $countArr = explode('_', $solrDoc[$this->FIELD_ALIAS['nl_entity_tag_qna_count']]);
            $countQArr = explode(':', $countArr[0]);
            $countAArr = explode(':', $countArr[1]);
            
            $temp = array();
            $temp['id'] = $valueArr[2];
            $temp['name'] = $this->sanatizeInputText(html_entity_decode($valueArr[1]));
            $temp['quality'] = $valueArr[0];
            $temp['questionCount'] = $countQArr[1];
            $temp['answerCount'] = $countAArr[1];
            $temp['type'] = $valueArr[3];
            $temp['url'] = $solrDoc[$this->FIELD_ALIAS['nl_entity_url']];
            $solrResults[$temp['name']] = $temp;
        }
        return $solrResults;
    }

    public function parseInsttAutosuggestionResults($solrInsttContent) {
     	//parse institutes
		$instituteNameIdMap = array();
		$solrDocs = array();
		$solrDocs = $solrInsttContent['response']['docs'];

		if(!empty($solrInsttContent['stats'])){
			if(!empty($solrInsttContent['stats']['stats_fields']['nl_course_review_count']['facets'])) {
				$stats = $solrInsttContent['stats']['stats_fields']['nl_course_review_count']['facets'];
				
				foreach($stats['nl_insttId_courseId'] as $key => $stat){
					$instituteCourseId = explode(":",$key);
					$instituteId = $instituteCourseId[0];
					$statsData[$instituteId] += $stat['max'];
				}
				/*foreach($stats['nl_institute_id'] as $instituteId => $stat){
					$statsData[$instituteId] = $stat['sum'];
				}*/
			}
			
		}
		
		foreach ($solrDocs as $key => $solrDoc) {
			$instituteId 	= $solrDoc[$this->FIELD_ALIAS['nl_institute_id']];
			$instituteName 	= $this->sanatizeInputText(html_entity_decode($solrDoc[$this->FIELD_ALIAS['nl_institute_name']]));
			$instituteType 	= $this->sanatizeInputText(html_entity_decode($solrDoc[$this->FIELD_ALIAS['nl_institute_type']]));

			$similarNameMultipleInstitute[$instituteName] = 0;
			if(!empty($instituteNameIdMap[$instituteName])) {
				$similarNameMultipleInstitute[$instituteName] = 1;
			}

			if(isset($statsData[$instituteId])){
				$text = 'reviews';
				if($statsData[$instituteId] == 1){
					$text = 'review';
				}
				$instituteName .= " (".$statsData[$instituteId]." ".$text.")";
			}
			$instituteNameIdMap[$instituteName] = $instituteId;
			$instituteIdTypeMap[$instituteId] = $instituteType;
		}
		
		$solrResults['data']['institute_title_facet'] = '';
		if(!empty($instituteNameIdMap)) {
			//storing in key 'institute_title_facet' as it was used in old autosuggestor documents, without this certain checks in AutoSuggestor.js might fail
			$solrResults['data']['institute_title_facet'] = $instituteNameIdMap;
			$solrResults['institute_type_map'] = $instituteIdTypeMap;
		}

		if(!empty($similarNameMultipleInstitute)) {
			$solrResults['is_institute_multiple'] = $similarNameMultipleInstitute;
		}

		return $solrResults;
    }

    public function parseInsttAdvancedFilters($solrContent) {
    	//parse location facets
    	$locations = array('city');
    	foreach ($locations as $key => $value) {
    		$facetAlias = $this->FIELD_ALIAS[$value];
        	$facetContent = $solrContent['facet_counts']['facet_fields'][$facetAlias];
	    	$j = 0;
			foreach ($facetContent as $key => $count) {
				$keyArr = explode(':', $key);
				$location[$value][$j]['id'] = $keyArr[1];
				$location[$value][$j]['name'] = $keyArr[0];
				$location[$value][$j]['count'] = $count;
				$j++;
			}
		}
		$result = $this->sortCities($location['city'], 1);

		$i = 0;
		foreach ($solrContent['response']['docs'] as $key => $value) {
			$streamId = $value[$this->FIELD_ALIAS['nl_stream_id']];
			$courseId = $value[$this->FIELD_ALIAS['nl_course_id']];
			$result['instituteAdvOpts'][$streamId]['id'] 	= $streamId;
			$result['instituteAdvOpts'][$streamId]['name'] 	= $value[$this->FIELD_ALIAS['nl_stream_name']];
			$result['instituteAdvOpts'][$streamId]['courses'][$courseId]['id'] = $courseId;
			$result['instituteAdvOpts'][$streamId]['courses'][$courseId]['name'] = $value[$this->FIELD_ALIAS['nl_course_name']];
			$result['instituteAdvOpts'][$streamId]['courses'][$courseId]['url'] = base_url().'abc/course/def/'.$courseId;
			$result['instituteAdvOpts'][$streamId]['courses'][$courseId]['offered_by'] = $value[$this->FIELD_ALIAS['nl_course_offered_by']];
		}
		uasort($result['instituteAdvOpts'], array('SolrResponseParser','sortByName'));
		foreach ($result['instituteAdvOpts'] as $streamId => $value) {
			uasort($value['courses'], array('SolrResponseParser','sortByName'));
			$result['instituteAdvOpts'][$streamId]['courses'] = array_values($value['courses']);
		}
		$result['instituteAdvOpts'] = array_values($result['instituteAdvOpts']);
		
		//remove all virtual cities
		if(count($result['popular_city']) >= 1) {
			$virtualCities = $this->getAllVirtualCities();
			foreach ($result['popular_city'] as $key => $value) {
				if(in_array($value['id'], $virtualCities)) {
					unset($result['popular_city'][$key]);
				}
			}
		}
		
		$isMultilocation = 0;
		if((count($result['popular_city']) + count($result['city'])) > 1) {
			$isMultilocation = 1;
		}
		$result['isMultilocation'] 	= $isMultilocation;
		
		return $result;
    }

    public function parseLocationResult($solrContent) {
    	//parse location facets
    	$locations = array('city', 'state');
    	foreach ($locations as $key => $value) {
    		$facetAlias = $this->FIELD_ALIAS[$value];
        	$facetContent = $solrContent['facet_counts']['facet_fields'][$facetAlias];
	    	$j = 0;
			foreach ($facetContent as $key => $count) {
				$keyArr = explode(':', $key);
                if(empty($keyArr[0]) || (in_array($keyArr[1], $this->statesToIgnore) && $value == 'state')){
                    continue;
                }
				$location[$value][$j]['id'] = $keyArr[1];
				$location[$value][$j]['name'] = $keyArr[0];
				$location[$value][$j]['count'] = $count;
				$j++;
			}
		}
		if(!empty($location['city'])) {
			$result = $this->sortCities($location['city'], 1);
		}
		if(!empty($location['state'])) {
			$result['state'] = $this->sortStates($location['state']);
		}

		return $result;
    }

    public function parseEntityAdvancedFilters($solrContent) {
    	//parse facets with their count
		$result = $this->parseFacetsOnSearch($solrContent);
		
		//parse location facets
    	$locations = array('city', 'state');
    	foreach ($locations as $key => $value) {
    		$facetAlias = $this->FIELD_ALIAS[$value];
        	$facetContent = $solrContent['facet_counts']['facet_fields'][$facetAlias];
	    	$j = 0;
			foreach ($facetContent as $key => $count) {
				$keyArr = explode(':', $key);
                if(empty($keyArr[0]) || (in_array($keyArr[1], $this->statesToIgnore) && $value == 'state')){
                    continue;
                }
				$location[$value][$j]['id'] = $keyArr[1];
				$location[$value][$j]['name'] = $keyArr[0];
				$location[$value][$j]['count'] = $count;
				$j++;
			}
		}

		$data = $this->sortCities($location['city'], 1);
		if(!empty($data)) {
			array_unshift($data['popular_city'], array('id'=>1, 'name'=>'All India'));
		}
		
		$data['state'] = $this->sortStates($location['state']);
		
		$data['fieldAlias'] = $result['fieldAlias'];
		unset($result['fieldAlias']);
		
		if(count($result['filters']) < 2 && $solrContent['requestType'] == 'advanced_filters') {
    		$data['advancedFilters'] = array();
    	} else {
    		$data['advancedFilters'] = $this->sortFilters($result['filters']);
    	}
		
		$data['numberOfResults'] = $solrContent['response']['numFound'];
		
		return $data;

		/*
    	//parse locations
    	if(in_array('asv2_city_name_id_map', $solrContent['responseHeader']['params']['facet.field'])) { //if we are getting location facets
    		$cityIdNameMap = $this->parseFacetsWithNameIdPair($solrContent, 'asv2_city_name_id_map');
    		$stateIdNameMap = $this->parseFacetsWithNameIdPair($solrContent, 'asv2_state_name_id_map');
    		
    		//sort locations namewise
	    	asort($cityIdNameMap);
	    	asort($stateIdNameMap);

	    	//separate popular cities
	    	$cityData = $this->getTier1Cities();
	    	$popularCities = array_intersect_key($cityIdNameMap, $cityData['tier1Cities']);
			$cityIdNameMap = array_diff_key($cityIdNameMap, $cityData['tier1Cities']);

			//add all India option 
            $allIndiaOption = array('city_1'=>'All India'); 
            $popularCities = $allIndiaOption + $popularCities;
            
			$data['popular_cities'] = $popularCities;
			$data['cities'] 		= $cityIdNameMap;
			$data['states'] 		= $stateIdNameMap;
    	}

    	if(!empty($solrContent['subcatId'])) {
    		$data['advancedFilters'] = $this->parseAdvancedFilters($solrContent);
    	}
    	
    	//check count of advanced filters
    	if(count($data['advancedFilters']) < 2) {
    		$data['advancedFilters'] = array();
    	}
    	$data['numberOfResults'] = $solrContent['response']['numFound'];
    	*/
    }

    private function parseAdvancedFilters($solrContent) {
    	$data = array();
    	global $subcatToAdvancedFiltersMapping;
		if(array_key_exists($solrContent['subcatId'], $subcatToAdvancedFiltersMapping)) {
        	foreach ($subcatToAdvancedFiltersMapping[$solrContent['subcatId']] as $key => $filter) {
	        	switch ($filter) {
	        		case 'specialization':
	        			$specList = array();
	        			$specList = $this->parseFacetsWithNameIdPair($solrContent, 'asv2_spec_name_id_map_subcat_'.$solrContent['subcatId']);
	        			
	        			//get ldb courses
	        			$ldbList = array();
                        $ldbList = $this->parseFacetsWithNameIdPair($solrContent, 'asv2_ldb_name_id_map_subcat_'.$solrContent['subcatId']);
                        $subcatName = $this->sanatizeInputText($solrContent['subcatName']);
                        foreach ($ldbList as $ldbId => $ldbName) { //these are those courses who's specialization is all
                        	if($ldbName == $subcatName) {
                        		unset($ldbList[$ldbId]);
                        	}
                        }
                        $specList = $ldbList + $specList;
	        			if(!empty($specList)) {
							$data['specializations']['data'] = $specList;
							$data['specializations']['isMultiSelect'] = 1; //was initially single select for non rnr, now it is multiselect for all
							// global $subcatWithMultiSelectSpec;
							// if(in_array($solrContent['subcatId'], $subcatWithMultiSelectSpec)) {
							// 	$data['specializations']['isMultiSelect'] = 1;
							// }
						}
	        			break;
	        		
	        		case 'exams':
	        			$examFacet = $solrContent['facet_counts']['facet_fields']['course_RnR_valid_exams'];
	        			if(!empty($examFacet)) {
	        				$finalExamFilter = $this->filterExamsCategoryWise($solrContent, array_keys($examFacet));
							if(!empty($finalExamFilter)) {
								$data['exams'] = $finalExamFilter;
							}
						}
	        			break;

	        		case 'fees':
        				$feesFacet = $solrContent['facet_counts']['facet_queries'];
	        			$data['fees'] = $this->getFeesFilter($feesFacet,$solrContent['subcatId']);
	        			break;
	        			
	        		case 'mode':
	        			$courseType = $solrContent['facet_counts']['facet_fields']['course_type_facet'];
						if(!empty($courseType)) {
							$data['mode'] = array_keys($courseType);
						}
	        			break;
	        			
	        		case 'courseLevel':
	        			$courseLevel = $solrContent['facet_counts']['facet_fields']['course_level_cp'];
						if(!empty($courseLevel)) {
							$data['courseLevel'] = array_keys($courseLevel);
						}
	        			break;

	        		case 'duration':
	        			break;
	        	}
	        }
        }
        return $data;
	}

    private function parseFacetsWithNameIdPair($solrContent, $facetFieldName, $keyType) {
    	$facetNameId = array_keys($solrContent['facet_counts']['facet_fields'][$facetFieldName]);
    	$nameIdMap = array();
    	foreach ($facetNameId as $key=>$nameId) {
    		if($facetFieldName == 'asv2_subcat_name_id_map') {
				$nameId = $this->sanatizeInputText($nameId);
    		}
			$nameIdArr = explode(':', $nameId);
			if($keyType == 'name') {
				$nameIdMap[$nameIdArr[0]] = $nameIdArr[1]; //[name] = id
			} else {
				switch ($facetFieldName) {
					case 'asv2_city_name_id_map':
						$nameIdMap['city_'.$nameIdArr[1]] = $nameIdArr[0]; //[city_id] = name
						break;
					
					case 'asv2_state_name_id_map':
						$nameIdMap['state_'.$nameIdArr[1]] = $nameIdArr[0]; //[state_id] = name
						break;

					default:
						$nameIdMap[$nameIdArr[1]] = $nameIdArr[0]; //[id] = name
						break;
				}
			}
		}
		return $nameIdMap;
    }

	/*
	private function getTier1Cities() {
		if(empty($cityTier1List)) {
			$cityTierObjList = $this->locationRepository->getCitiesByMultipleTiers(array(1), 2);
			foreach ($cityTierObjList[1] as $key => $cityObj) {
				$cityTier1List['city_'.$cityObj->getId()] = '';
			}
		}
		return array('tier1Cities' => $cityTier1List);
	}

	private function getFeesFilter($feesFacet,$subCatId) {
		global $SEARCH_FEES_RANGE;
		$feesRange = $SEARCH_FEES_RANGE[$subCatId];
		$feesFilter = array();

		foreach ($feesFacet as $key => $count) {
			if($count > 0){
				$rangeKey = explode("_", $key);
				$feesFilter[$rangeKey[0]] =  $feesRange[$rangeKey[0]]['placeholder'];
			}
		}
		return $feesFilter;
	}

	private function filterExamsCategoryWise($solrContent, $exams) {
		$exam_list = $this->listingCache->getExamsList();
		if(empty($exam_list)){
			$exam_list = $this->categoryClient->getTestPrepCoursesList(1);
			$this->listingCache->storeExamsList($exam_list);
		}
		$final_exam_list = $this->_prepareExamList($exam_list);
		
		if($solrContent['subcatId'] == 23) {
			if(isset($final_exam_list['MBA'])){
				$final_exam_list['MBA'][] = "GMAT";
			}
        }
        if($solrContent['catId'] == 3) {
        	$solrContent['catName'] = 'MBA';
        }

        $finalExamFilter = array();
		foreach ($exams as $key => $examName) {
			if(in_array($examName, $final_exam_list[$solrContent['catName']])){
                $finalExamFilter[] = $examName;
            }
		}
		return $finalExamFilter;
	}

	private function _prepareExamList($exam_list = array()) {
		$final_exam_list = array();
		if(count($exam_list) >0) {
			foreach ($exam_list as $list) {
				foreach ($list as $list_child) {
					if($list_child['child']['acronym'] == "No Exam Required") {
						continue;
					}
					$final_exam_list[$list_child['acronym']][] = $list_child['child']['acronym'];
				}
			}
		}
		//Entry for no exam required in MBA
		if(!empty($final_exam_list['MBA'])){
			// $final_exam_list['MBA'][] = "No Exam Required";
		}
		return $final_exam_list;
	}

	public function parseSolrResultOnSearch($solrContent) {
		$result = array();

		//parse total number of institutes
		$result['numOfInstitutes'] = $solrContent['grouped']['institute_id']['ngroups'];

		//parse all institute ids and it's course ids
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		
		if(GET_SEARCH_RESULT_COURSES_AS_FACET) {
			$this->parseInstituteCoursesFromFacet($result, $solrContent);
		} else {
			$this->parseInstituteCoursesFromFieldList($result, $solrContent);
		}
		
		//parse facets with their count
		$resultantFacets = array();
		$resultantFacets = $this->parseFacetsOnSearch($solrContent, $instituteIds);
		$result = array_merge($result, $resultantFacets);
		
		return $result;
	}

	private function parseInstituteCoursesFromFacet(& $result, $solrContent) {
		$instituteIds = array();
		$insttGroup = $solrContent['grouped']['institute_id']['groups'];
		
		global $SOLR_DATA_FIELDS_ALIAS;
		foreach ($insttGroup as $key => $instt) {
			$instituteIds[] = $instt['groupValue'];
			if(!$solrContent['applySort']) {
				$instituteFirstCourse[$instt['groupValue']] = $instt['doclist']['docs'][0][$SOLR_DATA_FIELDS_ALIAS['course_id']];
			}
			if(DEBUGGER) {
				$instituteSortParamValue[$instt['groupValue']] = $instt['doclist']['docs'][0]['institute_popularity_subcat_'.$solrContent['subcatId']];
				$courseSortParamValue[$instt['doclist']['docs'][0][$SOLR_DATA_FIELDS_ALIAS['course_id']]] = $instt['doclist']['docs'][0]['sorter'];
			}
		}
		
		//get remaining courses
		// $time_start = microtime_float(); $start_memory = memory_get_usage();
		$insttId_courseId_yearViewCount_arr = array(); $courseViewCount = array();
		foreach ($solrContent['facet_counts']['facet_fields']['insttId_courseId_yearViewCount'] as $insttId_courseId_yearViewCount => $count) {
			$insttId_courseId_yearViewCount_arr = explode(':', $insttId_courseId_yearViewCount);
			$instituteId = $insttId_courseId_yearViewCount_arr[0];
			$courseId = $insttId_courseId_yearViewCount_arr[1];
			$courseViewCount[$instituteId][$courseId] = $insttId_courseId_yearViewCount_arr[2];
		}
		
		foreach ($instituteIds as $instituteId) {
			if($solrContent['applySort']) {
				arsort($courseViewCount[$instituteId]);
				$result['instituteIdCourseIdMap'][$instituteId] = array_keys($courseViewCount[$instituteId]);
			} else {
				$result['instituteIdCourseIdMap'][$instituteId] = array_unique(array_merge(array($instituteFirstCourse[$instituteId]), array_keys($courseViewCount[$instituteId])));
			}
		}
		
		// if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In AutoSuggestorSolrResponseParser, extra loop with sorting | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
		
		if(DEBUGGER) {
			$result['instituteDebugSortParam'] = $instituteSortParamValue;
			$result['courseDebugSortParam'] = $courseSortParamValue;
		}
	}

	private function parseInstituteCoursesFromFieldList(& $result, $solrContent) {
		//parse all institute ids and it's course ids
		global $SOLR_DATA_FIELDS_ALIAS;
		$insttGroup = $solrContent['grouped']['institute_id']['groups'];
		foreach ($insttGroup as $key => $instt) {
			foreach ($instt['doclist']['docs'] as $key => $course) {
				if(!in_array($course[$SOLR_DATA_FIELDS_ALIAS['course_id']], $result['instituteIdCourseIdMap'][$instt['groupValue']])) {
					$result['instituteIdCourseIdMap'][$instt['groupValue']][] = $course[$SOLR_DATA_FIELDS_ALIAS['course_id']];
				}
			}
		}
	}

	public function parseFacetsOnSearch($solrContent, $instituteIds) {
		$result = array();
        $FILTER_FACETS_ON_SUBCAT_SEARCH = $this->CI->config->item('FILTER_FACETS_ON_SUBCAT_SEARCH');
        if(empty($solrContent['subcatId'])) {
            $facets = array('subcat', 'location');
        } else {
            if(array_key_exists($solrContent['subcatId'], $FILTER_FACETS_ON_SUBCAT_SEARCH)) {
                $facets = $FILTER_FACETS_ON_SUBCAT_SEARCH[$solrContent['subcatId']];
            } else {
                $facets = $FILTER_FACETS_ON_SUBCAT_SEARCH['default'];
            }
        }
        
        foreach ($facets as $facetName) {
			switch ($facetName) {
				case 'exams':
        			$examFacet = $solrContent['facet_counts']['facet_fields']['course_RnR_valid_exams_current'];
        			if(!empty($examFacet)) {
        				$finalExamFilter = $this->filterExamsCategoryWise($solrContent, array_keys($examFacet));
						foreach ($examFacet as $exam => $count) {
							if(in_array($exam, $finalExamFilter)) {
								$result['filters']['exams']['current'][$exam] = $count;
							}
						}
					}
					$examFacet = $solrContent['facet_counts']['facet_fields']['course_RnR_valid_exams_parent'];
        			if(!empty($examFacet)) {
        				$finalExamFilter = $this->filterExamsCategoryWise($solrContent, array_keys($examFacet));
						foreach ($examFacet as $exam => $count) {
							if(in_array($exam, $finalExamFilter)) {
								$result['filters']['exams']['parent'][$exam] = $count;
							}
						}
					}
        			break;

        		case 'fees':
        			if(!empty($solrContent['facet_counts']['facet_queries'])) {
        				$result['filters']['fees'] = $solrContent['facet_counts']['facet_queries'];
        			}
        			break;
        			
        		case 'mode':
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_type_facet'])) {
        				$result['filters']['mode']['current'] = $solrContent['facet_counts']['facet_fields']['course_type_facet'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_type_facet_parent'])) {
        				$result['filters']['mode']['parent'] = $solrContent['facet_counts']['facet_fields']['course_type_facet_parent'];
        			}
        			break;
        			
        		case 'courseLevel':
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_level_cp'])) {
        				$result['filters']['courseLevel']['current'] = $solrContent['facet_counts']['facet_fields']['course_level_cp'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_level_cp_parent'])) {
        				$result['filters']['courseLevel']['parent'] = $solrContent['facet_counts']['facet_fields']['course_level_cp_parent'];
        			}
        			break;

        		case 'specialization':
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_sp_id_current'])) {
        				$result['filters']['specialization']['current'] = $solrContent['facet_counts']['facet_fields']['course_sp_id_current'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_ldb_id_current'])) {
        				$result['filters']['ldbCourse']['current'] = $solrContent['facet_counts']['facet_fields']['course_ldb_id_current'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_sp_id_parent'])) {
        				$result['filters']['specialization']['parent'] = $solrContent['facet_counts']['facet_fields']['course_sp_id_parent'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_ldb_id_parent'])) {
        				$result['filters']['ldbCourse']['parent'] = $solrContent['facet_counts']['facet_fields']['course_ldb_id_parent'];
        			}
        			break;

        		case 'subcat':
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_category_id_list'])) {
        				$result['filters']['subcat'] = $solrContent['facet_counts']['facet_fields']['course_category_id_list'];
        			}
        			break;

        		case 'location':
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_city_id_current'])) {
						//$result['filters']['location']['current']['city'] = $solrContent['facet_counts']['facet_fields']['course_city_id_current'];
						$city = array();
						foreach($solrContent['facet_counts']['facet_fields']['course_city_id_current'] as $cityData => $count) {
							if(!preg_match('/other/i', $cityData)) {
								$city[$cityData] = $count;
							}
						}
						$result['filters']['location']['current']['city'] = $city;
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_virtual_city_id_current'])) {
						$result['filters']['location']['current']['virtualCity'] = $solrContent['facet_counts']['facet_fields']['course_virtual_city_id_current'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_state_id_current'])) {
						$result['filters']['location']['current']['state'] = $solrContent['facet_counts']['facet_fields']['course_state_id_current'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_locality_id_current'])) {
						$result['filters']['location']['current']['locality'] = $solrContent['facet_counts']['facet_fields']['course_locality_id_current'];
        			}

        			//Parent location filters
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_city_id_parent'])) {
						//$result['filters']['location']['current']['city'] = $solrContent['facet_counts']['facet_fields']['course_city_id_current'];
						$city = array();
						foreach($solrContent['facet_counts']['facet_fields']['course_city_id_parent'] as $cityData => $count) {
							if(!preg_match('/other/i', $cityData)) {
								$city[$cityData] = $count;
							}
						}
						$result['filters']['location']['parent']['city'] = $city;
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_virtual_city_id_parent'])) {
						$result['filters']['location']['parent']['virtualCity'] = $solrContent['facet_counts']['facet_fields']['course_virtual_city_id_parent'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_state_id_parent'])) {
						$result['filters']['location']['parent']['state'] = $solrContent['facet_counts']['facet_fields']['course_state_id_parent'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_locality_id_parent'])) {
						$result['filters']['location']['parent']['locality'] = $solrContent['facet_counts']['facet_fields']['course_locality_id_parent'];
        			}
        			break;
        			
        		case 'degreePref':
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_degree_pref'])) {
        				$result['filters']['degreePref']['current'] = $solrContent['facet_counts']['facet_fields']['course_degree_pref'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_degree_pref_parent'])) {
        				$result['filters']['degreePref']['parent'] = $solrContent['facet_counts']['facet_fields']['course_degree_pref_parent'];
        			}
        			break;

        		case 'classTimings':
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_class_timings'])) {
        				$result['filters']['classTimings']['current'] = $solrContent['facet_counts']['facet_fields']['course_class_timings'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_class_timings_parent'])) {
        				$result['filters']['classTimings']['parent'] = $solrContent['facet_counts']['facet_fields']['course_class_timings_parent'];
        			}
        			break;

        		case 'affiliation':
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_affiliations'])) {
        				$result['filters']['affiliation']['current'] = $solrContent['facet_counts']['facet_fields']['course_affiliations'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['course_affiliations_parent'])) {
        				$result['filters']['affiliation']['parent'] = $solrContent['facet_counts']['facet_fields']['course_affiliations_parent'];
        			}
                    break;

                case 'facilities':
                	if(!empty($solrContent['facet_counts']['facet_fields']['institute_facilities'])) {
        				$result['filters']['facilities']['current'] = $solrContent['facet_counts']['facet_fields']['institute_facilities'];
        			}
        			if(!empty($solrContent['facet_counts']['facet_fields']['institute_facilities_parent'])) {
        				$result['filters']['facilities']['parent'] = $solrContent['facet_counts']['facet_fields']['institute_facilities_parent'];
        			}
                    break;
			}
		}

		return $result;
	}
	*/
	
	function sortFilters($filters) {
		foreach ($filters as $key => $value) {
			if(!in_array($key, array('fees'))) {
				uasort($value, array('SolrResponseParser','sortByName'));
				$filters[$key] = array_values($value);
			}
		}

		return $filters;
	}

	public function parseRecognisedEntitites($solrFilterContent) {
		$results = $solrFilterContent['response']['docs'];
		foreach ($results as $value) {
			$entities['institute'][] = $value['institute_id'];
		}
		return $entities;
	}

	private function addSolrFacetToHeadingMapping($solrResults) {
		$type = 'desktop';
		if(isMobileRequest()) {
			$type = 'mobile';
		}
		foreach($solrResults['data'] as $solrFacet => $data) {
			$returnData[$solrFacet] = $this->solrFacetToHeadingMapping[$solrFacet][$type];
		}
		
		return $returnData;
	}
	
	/*public function parseSpellCorrection($solrContent) {
		$result[$solrContent['spellcheck']['suggestions']['collation']['collationQuery']] = $solrContent['spellcheck']['suggestions']['collation']['hits'];
        $result[$solrContent['spellcheck']['suggestions']['collation 1']['collationQuery']] = $solrContent['spellcheck']['suggestions']['collation 1']['hits'];
        $result[$solrContent['spellcheck']['suggestions']['collation 2']['collationQuery']] = $solrContent['spellcheck']['suggestions']['collation 2']['hits'];
        $result[$solrContent['spellcheck']['suggestions']['collation 3']['collationQuery']] = $solrContent['spellcheck']['suggestions']['collation 3']['hits'];
        $result[$solrContent['spellcheck']['suggestions']['collation 4']['collationQuery']] = $solrContent['spellcheck']['suggestions']['collation 4']['hits'];
        arsort($result);
        
        return key($result);
    }*/
    public function parseSpellCorrection($solrContent) {
		
		$result[$solrContent['spellcheck']['collations']['collation']['collationQuery']] = $solrContent['spellcheck']['collations']['collation']['hits'];
        $result[$solrContent['spellcheck']['collations']['collation 1']['collationQuery']] = $solrContent['spellcheck']['collations']['collation 1']['hits'];
        $result[$solrContent['spellcheck']['collations']['collation 2']['collationQuery']] = $solrContent['spellcheck']['collations']['collation 2']['hits'];
        $result[$solrContent['spellcheck']['collations']['collation 3']['collationQuery']] = $solrContent['spellcheck']['collations']['collation 3']['hits'];
        $result[$solrContent['spellcheck']['collations']['collation 4']['collationQuery']] = $solrContent['spellcheck']['collations']['collation 4']['hits'];
        arsort($result);

        return key($result);
    }
}