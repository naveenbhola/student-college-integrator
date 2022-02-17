<?php

class SolrResponseParser {
	private $CI;
    private $searchWrapper;

    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->library('search/Solr/SolrRequestGenerator');
        $this->solrRequestGenerator = new SolrRequestGenerator;

        $this->CI->load->builder('LocationBuilder','location');
        $this->locationBuilder = new LocationBuilder();
		$this->locationRepository = $this->locationBuilder->getLocationRepository();
        
	    $this->CI->load->config("nationalCategoryList/nationalConfig");
	    $this->FIELD_ALIAS = $this->CI->config->item('FIELD_ALIAS');
	    $this->FEES_RANGE = $this->CI->config->item('FEES_RANGE');
	    $this->RATING_RANGE = $this->CI->config->item('RATING_RANGE');


	    $this->CI->load->builder('SearchBuilder', 'search');
    	$this->searchCommon = SearchBuilder::getSearchCommon();

    	global $statesToIgnore;
    	$this->statesToIgnore = $statesToIgnore;
    }

    public function parseSolrResultOnSearch($solrContent) {
		$result = array();

		//parse all institute ids and it's course ids
		$result = $this->parseInstituteCoursesFromFacet($solrContent);
		//parse facets with their count
		$resultantFacets = array();
		$resultantFacets = $this->parseFacetsOnSearch($solrContent);
		$result = array_merge($result, $resultantFacets);
		
		return $result;
	}

	public function parseSolrResultAllCourses($solrContent) {
		$result = array();

		//parse all course ids
		$result = $this->parseGroupedCourses($solrContent);

		//parse facets with their count
		$resultantFacets = array();
		$resultantFacets = $this->parseFacetsOnSearch($solrContent);
		$result = array_merge($result, $resultantFacets);

		// _p($result['filters']);die;
		
		//Refer LF-5347
		if(!isMobileRequest()){
			if(count($result['filters']['stream']) == 1) {
				unset($result['filters']['stream']);
			} else if(count($result['filters']['stream']) > 1) {
				unset($result['filters']['sub_spec']);
			}
		}

		//remove all virtual cities
		$cityCount = count($result['filters']['location']['city']);
		if(count($result['filters']['location']['city']) >= 1) {
			$virtualCities = $this->getAllVirtualCities();
			foreach ($result['filters']['location']['city'] as $key => $value) {
				if(in_array($value['id'], $virtualCities)) {
					$cityCount--;
				}
			}
		}
		if($cityCount <= 1) {
			unset($result['filters']['location']);
			unset($result['selectedFilters']['city']);
			unset($result['selectedFilters']['state']);
		}
		
		return $result;
	}

	private function parseGroupedCourses($solrContent) {
		//parse total number of courses
		$result['numOfCourses'] = $solrContent['grouped']['nl_course_id']['ngroups'];

		$courseGroup = $solrContent['grouped']['nl_course_id']['groups'];
		foreach ($courseGroup as $key => $courses) {
			$result['courseIds'][] = $courses['groupValue'];
		}

		return $result;
	}

	private function parseInstituteCoursesFromFacet($solrContent) {
		$result = array(); $instituteIds = array();

		//parse total number of institutes
		$result['numOfInstitutes'] = $solrContent['grouped']['nl_institute_id']['ngroups'];
		
		$insttGroup = $solrContent['grouped']['nl_institute_id']['groups'];
		foreach ($insttGroup as $key => $instt) {
			$instituteIds[] = $instt['groupValue'];
			$instituteFirstCourse[$instt['groupValue']] = $instt['doclist']['docs'][0][$this->FIELD_ALIAS['nl_course_id']];
			
			if(DEBUGGER) {
				// $instituteSortParamValue[$instt['groupValue']] = $instt['doclist']['docs'][0]['institute_popularity_subcat_'.$solrContent['subcatId']];
				// $courseSortParamValue[$instt['doclist']['docs'][0][$SOLR_DATA_FIELDS_ALIAS['course_id']]] = $instt['doclist']['docs'][0]['sorter'];
			}
		}
		
		//get remaining courses
		$insttId_courseId_arr = array(); $remainingCourseIds = array(); $facetCourseOrder = array(); $facetCourseViewCount = array(); $facetCourseViewCountForAll = array();
		foreach ($solrContent['facet_counts']['facet_fields'][$this->FIELD_ALIAS['instt_course']] as $insttId_courseId => $count) {
			$insttId_courseId_arr = explode(':', $insttId_courseId);
			$facetInstituteId = $insttId_courseId_arr[0];
			$facetCourseId = $insttId_courseId_arr[1];
			if(!in_array($facetCourseId, $instituteFirstCourse)) {
				if(!empty($insttId_courseId_arr[2])) {
					$facetCourseOrder[$facetInstituteId][$facetCourseId] = $insttId_courseId_arr[2];
				} else {
					$facetCourseViewCount[$facetInstituteId][$facetCourseId] = $insttId_courseId_arr[3];
				}
			}
			$facetCourseViewCountForAll[$facetInstituteId][$facetCourseId] = $insttId_courseId_arr[3];

			if($instituteFirstCourse[$facetInstituteId] != $facetCourseId) {
				$remainingCourseIds[$facetInstituteId][] = $facetCourseId;
			}
		}
		
		foreach ($instituteIds as $instituteId) {
			if(!empty($remainingCourseIds[$instituteId])) {

				switch ($solrContent['requestType']) {
					case 'category_result':
						asort($facetCourseOrder[$instituteId]);
						arsort($facetCourseViewCount[$instituteId]);
						$mergeArray1 = array_keys($facetCourseOrder[$instituteId]);
						$mergeArray2 = array_keys($facetCourseViewCount[$instituteId]);
						if(empty($mergeArray1)){
							$result['instituteIdCourseIdMap'][$instituteId] = $mergeArray2;	
						}else if(empty($mergeArray2)){
							$result['instituteIdCourseIdMap'][$instituteId] = $mergeArray1;	
						}else{
							$result['instituteIdCourseIdMap'][$instituteId] = array_merge(array_keys($facetCourseOrder[$instituteId]), array_keys($facetCourseViewCount[$instituteId]));	
						}
						$result['instituteIdCourseIdMap'][$instituteId] = array_merge((array) $instituteFirstCourse[$instituteId], $result['instituteIdCourseIdMap'][$instituteId]);
						break;

					case 'search_result':
						arsort($facetCourseViewCountForAll[$instituteId]);
						$result['instituteIdCourseIdMap'][$instituteId] = array_keys($facetCourseViewCountForAll[$instituteId]);
						break;
				}
				//$result['instituteIdCourseIdMap'][$instituteId] = array_merge(array($instituteFirstCourse[$instituteId]), $remainingCourseIds[$instituteId]);
			} else {
				$result['instituteIdCourseIdMap'][$instituteId][] = $instituteFirstCourse[$instituteId];
			}
		}
		$result['totalCourseCountForAllInstitutes'] = count($solrContent['facet_counts']['facet_fields'][$this->FIELD_ALIAS['instt_course']]);
		
		if(DEBUGGER) {
			// $result['instituteDebugSortParam'] = $instituteSortParamValue;
			// $result['courseDebugSortParam'] = $courseSortParamValue;
		}

		return $result;
	}

	public function parseFacetsOnSearch($solrContent) {
		//_p($solrContent);die;
		$result = array('selectedFilters'=>array());
        $facets = $this->solrRequestGenerator->getFacetList($solrContent);
        $result['fieldAlias'] = $this->FIELD_ALIAS;
        foreach ($facets as $facetName) {
        	$facetAlias = $this->FIELD_ALIAS[$facetName];
        	$facetContent = $solrContent['facet_counts']['facet_fields'][$facetAlias];

        	switch ($facetName) {
				case 'location':
					$locations = array('city', 'state');
					foreach ($locations as $key => $value) {
						$facetAlias = $this->FIELD_ALIAS[$value];
						$indexMapping = array();
						$facetContent = $solrContent['facet_counts']['facet_fields'][$facetAlias];
						$j = 0;
						foreach ($facetContent as $key => $count) {
							$keyArr = explode(':', $key);
							
							if(empty($keyArr[0]) || (in_array($keyArr[1], $this->statesToIgnore) && $value == 'state')){
								continue;
							}
							$result['filters']['location'][$value][$j]['id'] = $keyArr[1];
							$result['filters']['location'][$value][$j]['name'] = $keyArr[0];
							$result['filters']['location'][$value][$j]['count'] = $count;
							$indexMapping[$keyArr[1]] = $j;
							//save names of selected filters
							if(in_array($keyArr[1], $solrContent['userAppliedFilters'][$value])) {
								$result['selectedFilters'][$value][$keyArr[1]] = $keyArr[0];
							}
							$j++;
						}

						$parentAlias = $this->FIELD_ALIAS[$value.'_parent'];
						if(!empty($parentAlias)){
							$parentContent = $solrContent['facet_counts']['facet_fields'][$parentAlias];
							foreach ($parentContent as $key => $count) {
								$keyArr = explode(':', $key);
								if(empty($keyArr[0])){
									continue;
								}
								if(array_key_exists($keyArr[1],$indexMapping)){
									$index = $indexMapping[$keyArr[1]];
								}
								else{
									$index = $j;
									$j++;
								}
								$result['filters']['location'][$value][$index]['enabled'] = array_key_exists($keyArr[1],$indexMapping) ? true : false;
								if(!$result['filters']['location'][$value][$index]['enabled']){
									$result['filters']['location'][$value][$index]['name'] = $keyArr[0];
									$result['filters']['location'][$value][$index]['id'] = $keyArr[1];
								}
							}
						}
					}

					if(!empty($result['filters']['location']['city'])){
						$result['filters']['location']['city'] = $this->sortCities($result['filters']['location']['city']);
					}
					if(!empty($result['filters']['location']['state'])){
						$result['filters']['location']['state'] = $this->sortStates($result['filters']['location']['state']);
					}
					
					//get localities
					$facetAlias = $this->FIELD_ALIAS['locality'];
					$facetContent = $solrContent['facet_counts']['facet_fields'][$facetAlias];
					foreach ($facetContent as $key => $count) {
						$keyArr = explode('::', $key);
						$cityId = $keyArr[0];
						$localityStr = $keyArr[1];
						$localityArr = explode(':', $localityStr);
						$result['filters']['location']['cityWiseLocality'][$cityId][] = $localityArr[1];
						$result['filters']['location']['localityCount'][$cityId][$localityArr[1]] = $count;
					}
					break;

				case 'review':
					if(!empty($solrContent['facet_counts']['facet_queries'])) {
			        	$i = 0;
			        	foreach ($solrContent['facet_counts']['facet_queries'] as $reviewId => $count) {
			        		if(!empty($this->RATING_RANGE[$reviewId]) && $count > 0) {
			        			$result['filters']['review'][$i]['id'] = $reviewId;
			        			$result['filters']['review'][$i]['name'] = $this->RATING_RANGE[$reviewId]['placeholder'];
			        			$result['filters']['review'][$i]['count'] = $count;
			        			$result['filters']['review'][$i]['enabled'] = true;

			        			//save names of selected filters
								if(in_array($reviewId, $solrContent['userAppliedFilters']['review'])) {
									$result['selectedFilters']['review'][$reviewId] = $this->RATING_RANGE[$reviewId]['placeholder'];
								}
			        			$i++;
			        		}
			        	}
					}
					break;

				case 'fees':
					if(!empty($solrContent['facet_counts']['facet_queries'])) {
			        	$i = 0;
			        	foreach ($solrContent['facet_counts']['facet_queries'] as $feesId => $count) {
			        		if(!empty($this->FEES_RANGE[$feesId]) && $count > 0) {
			        			$result['filters']['fees'][$i]['id'] = $feesId;
			        			$result['filters']['fees'][$i]['name'] = $this->FEES_RANGE[$feesId]['placeholder'];
			        			$result['filters']['fees'][$i]['count'] = $count;
			        			$result['filters']['fees'][$i]['enabled'] = true;

			        			//save names of selected filters
								if(in_array($feesId, $solrContent['userAppliedFilters']['fees'])) {
									$result['selectedFilters']['fees'][$feesId] = $this->FEES_RANGE[$feesId]['placeholder'];
								}

			        			$i++;
			        		}
			        	}
			        	if(isMobileRequest()){
			        		foreach ($solrContent['facet_counts']['facet_queries'] as $feesId => $count) {
			        			if(!empty($this->FEES_RANGE[$feesId]) && $count == 0 && !empty($solrContent['facet_counts']['facet_queries'][$feesId.'_p'])) {
			        				$result['filters']['fees'][$i]['id'] = $feesId;
			        				$result['filters']['fees'][$i]['name'] = $this->FEES_RANGE[$feesId]['placeholder'];
			        				$result['filters']['fees'][$i]['enabled'] = false;

			        				$i++;
			        			}
			        		}
			        	}
					}	
					break;

				case 'exam':
				case 'facilities':
				case 'course_level':
				case 'credential':
				case 'level_credential':
				case 'stream':
				case 'substream':
				case 'specialization':
				case 'base_course':
				case 'popular_group':
				case 'offered_by_college':
				case 'course_status':
				case 'course_type':
				case 'certificate_provider':
				case 'accreditation':
				case 'approvals':
				case 'college_ownership':
					$indexMapping = array();
					$i = 0;
					// _p($facetContent);
					foreach ($facetContent as $key => $count) {
						$keyArr = explode(':', $key);
						$result['filters'][$facetName][$i]['id'] = $keyArr[1];
						$result['filters'][$facetName][$i]['name'] = $keyArr[0];
						$result['filters'][$facetName][$i]['count'] = $count;
						$indexMapping[$keyArr[1]] = $i;

						//save names of selected filters
						if(in_array($keyArr[1], $solrContent['userAppliedFilters'][$facetName])) {
							$result['selectedFilters'][$facetName][$keyArr[1]] = $keyArr[0];
						}
						$i++;
					}
					$parentAlias = $this->FIELD_ALIAS[$facetName.'_parent'];
					if(!empty($parentAlias)){
						$parentContent = $solrContent['facet_counts']['facet_fields'][$parentAlias];
						// _p($parentContent);die('aaa');
						foreach ($parentContent as $key => $count) {
							$keyArr = explode(':', $key);
							if(array_key_exists($keyArr[1],$indexMapping)){
								$index = $indexMapping[$keyArr[1]];
							}
							else{
								$index = $i;
								$i++;
							}
							$result['filters'][$facetName][$index]['enabled'] = array_key_exists($keyArr[1],$indexMapping) ? true : false;
							if(!$result['filters'][$facetName][$index]['enabled']){
								$result['filters'][$facetName][$index]['id'] = $keyArr[1];
								$result['filters'][$facetName][$index]['name'] = $keyArr[0];
							}
						}
					}
					break;

				case 'sub_spec':
					$specializationWithSubstream = array();

					$parentAlias = $this->FIELD_ALIAS[$facetName.'_parent'];
					$parentContent = $solrContent['facet_counts']['facet_fields'][$parentAlias];
					if(!empty($parentContent)){
						foreach ($parentContent as $key => $count) {
							if(empty($facetContent[$key])){
								$facetContent = $facetContent + array($key => 0);
							}
						}
					}

					foreach ($facetContent as $key => $count) {
						$keyArr = explode('::', $key);
						if(!empty($keyArr[0]) && !empty($keyArr[1])) {

							$substreamKey = explode(':', $keyArr[0]);
							$specializationKey = explode(':', $keyArr[1]);

							$temp            = array('id'=>$specializationKey[1],'name'=>$specializationKey[0],'count'=>$count,'type'=>'specialization');
							$temp['enabled'] = ($count > 0) ? true : false;
							$specializationWithSubstream[$substreamKey[1]][] = $temp;

							//save names of selected filters
							$spId = $this->FIELD_ALIAS['substream'].'_'.$substreamKey[1].'::'.$this->FIELD_ALIAS['specialization'].'_'.$specializationKey[1];
							$spIdAnySub = $this->FIELD_ALIAS['substream'].'_any::'.$this->FIELD_ALIAS['specialization'].'_'.$specializationKey[1];
							if($temp['enabled'] && (in_array($spId, $solrContent['userAppliedFilters']['sub_spec']) || in_array($spIdAnySub, $solrContent['userAppliedFilters']['sub_spec']))) {
								$result['selectedFilters']['sub_spec'][$spId] = $specializationKey[0];
							}
						}
						else if(empty($keyArr[0]) && !empty($keyArr[1])) {
							$specializationKey = explode(':', $keyArr[1]);

							$temp            = array('id'=>$specializationKey[1],'name'=>$specializationKey[0],'count'=>$count,'type'=>'specialization','alias'=>$this->FIELD_ALIAS['specialization']);
							$temp['enabled'] = ($count > 0) ? true : false;
							$specializationsAlone[] = $temp;

							//save names of selected filters
							$spId = $this->FIELD_ALIAS['specialization'].'_'.$specializationKey[1];
							$spIdAnySub = $this->FIELD_ALIAS['substream'].'_any::'.$this->FIELD_ALIAS['specialization'].'_'.$specializationKey[1];
							if($temp['enabled'] && (in_array($spId, $solrContent['userAppliedFilters']['sub_spec']) || in_array($spIdAnySub, $solrContent['userAppliedFilters']['sub_spec']))) {
								$result['selectedFilters']['sub_spec'][$spId] = $specializationKey[0];
							}
						}
					}

					$parentContent = $solrContent['facet_counts']['facet_fields'][$this->FIELD_ALIAS['substream_parent']];
					$childContent = $solrContent['facet_counts']['facet_fields'][$this->FIELD_ALIAS['substream']];
					if(!empty($parentContent)){
						foreach ($parentContent as $key => $count){
							if(empty($childContent[$key])){
								$childContent = $childContent + array($key => 0);
							}
						}
					}

					foreach ($childContent as $substreamKey => $count) {
						$subExists = 1;
						$keyArr = explode(':', $substreamKey);
						if(empty($result['filters'][$facetName])){
							$result['filters'][$facetName] = array();
						}

						$temp            = array('id'=>$keyArr[1],'name'=>$keyArr[0],'count'=>$count,'type'=>'substream','alias'=>$this->FIELD_ALIAS['substream']);
						$temp['enabled'] = ($count > 0) ? true : false;
						if(!empty($specializationWithSubstream[$keyArr[1]])) {
							$temp['specialization'] = array_values($specializationWithSubstream[$keyArr[1]]);
						}

						$result['filters'][$facetName][] = $temp;

						//save names of selected filters
						if($temp['enabled'] && (in_array($this->FIELD_ALIAS['substream'].'_'.$keyArr[1], $solrContent['userAppliedFilters']['sub_spec']))) {
							$sbId = $this->FIELD_ALIAS['substream'].'_'.$keyArr[1];
							$result['selectedFilters']['sub_spec'][$sbId] = $keyArr[0];
							if(!empty($specializationWithSubstream[$keyArr[1]])){
								foreach ($specializationWithSubstream[$keyArr[1]] as $value) {
									if($value['enabled']){
										$spId = $sbId.'::'.$this->FIELD_ALIAS['specialization'].'_'.$value['id'];
										$result['selectedFilters']['sub_spec'][$spId] = $value['name'];
									}
								}
							}
						}
					}

					if(!empty($specializationsAlone)) {
						$specExists = 1;
						if(empty($result['filters'][$facetName])){
							$result['filters'][$facetName] = array();
						}
						$result['filters'][$facetName] = array_merge($result['filters'][$facetName], $specializationsAlone);
					}
					if($subExists && $specExists) {
						$result['filters'][$facetName] = $this->sortSubstreamSpecializations($result['filters'][$facetName]);
					}
					// _p($result['filters'][$facetName]);die;
					break;

				case 'et_dm':
					$parentAlias = $this->FIELD_ALIAS[$facetName.'_parent'];
					$parentContent = $solrContent['facet_counts']['facet_fields'][$parentAlias];
					if(!empty($parentContent)){
						foreach ($parentContent as $key => $count) {
							if(empty($facetContent[$key])){
								$facetContent = $facetContent + array($key => 0);
							}
						}
					}
					// _p($facetContent);die;
					foreach ($facetContent as $facet => $count) {
						$facetArr = explode('::', $facet);
						$etArr = explode(':', $facetArr[0]);
						$dmArr = explode(':', $facetArr[1]);
						
						if(!empty($etArr[0]) && $etArr[1] == 20) {
							$temp = array('id'=>$etArr[1],'name'=>$etArr[0],'count'=>$count,'type'=>'education_type','alias'=>$this->FIELD_ALIAS['education_type'],'education_type'=>$etArr[1],'delivery_method'=>$dmArr[1]);
							$temp['enabled'] = ($count > 0) ? true : false;

							$result['filters']['et_dm'][] = $temp;

							//save names of selected filters
							if($temp['enabled'] && (in_array($this->FIELD_ALIAS['education_type'].'_'.$etArr[1], $solrContent['userAppliedFilters']['et_dm']))) {
								$result['selectedFilters']['et_dm'][$this->FIELD_ALIAS['education_type'].'_'.$etArr[1]] = $etArr[0];
							}
						}
						else if(!empty($dmArr[0]) && $dmArr[1] != 37) { //in case of full time, dm is not required
							if($dmArr[1] == 33) {
								$name = $etArr[0].' - '.$dmArr[0];
							} else {
								$name = $dmArr[0];
							}

							$temp = array('id'=>$dmArr[1],'name'=>$name,'count'=>$count,'type'=>'delivery_method','alias'=>$this->FIELD_ALIAS['delivery_method'],'education_type'=>$etArr[1],'delivery_method'=>$dmArr[1]);
							$temp['enabled'] = ($count > 0) ? true : false;

							$result['filters']['et_dm'][] = $temp;
							$dmId = $this->FIELD_ALIAS['education_type'].'_'.$etArr[1].'::'.$this->FIELD_ALIAS['delivery_method'].'_'.$dmArr[1];
							$dmIdAny = $this->FIELD_ALIAS['education_type'].'_'.$etArr[1].'::'.$this->FIELD_ALIAS['delivery_method'].'_any';

							//save names of selected filters
							if($temp['enabled'] && (in_array($dmId, $solrContent['userAppliedFilters']['et_dm']) || in_array($dmIdAny, $solrContent['userAppliedFilters']['et_dm']))) {
								$result['selectedFilters']['et_dm'][$dmId] = $name;
							}
						}
					}
					break;
			}
		}
		return $result;
	}

	function sortSubstreamSpecializations($array) {
		uasort($array, array('SolrResponseParser','sortByCount'));
		return array_values($array);
	}

	function sortByCount($a, $b) {
		if($a['count'] < $b['count']) {
			return 1;
		} else {
			return -1;
		}
	}

	function sortCities($cities, $returnPopularSeparate = false) {
		if(!empty($cities)) {
			$disabled = array();
			$tier1Cities = $this->getTier1Cities();
			$tier1CitiesInFacet = array();
			foreach ($cities as $key => $value) {
				if(isset($value['enabled']) && empty($value['enabled'])){
					$disabled[] = $value;
					unset($cities[$key]);
					continue;
				}
				if(in_array($value['id'], $tier1Cities)) {
					$tier1CitiesInFacet[] = $value;
					unset($cities[$key]);
				}
			}
			uasort($tier1CitiesInFacet, array('SolrResponseParser','sortByName'));
			uasort($cities, array('SolrResponseParser','sortByName'));
			uasort($disabled, array('SolrResponseParser','sortByName'));
			
			if($returnPopularSeparate) {
				return array('popular_city' => array_values($tier1CitiesInFacet), 'city' => array_values($cities));
			} else {
				return array_merge(array_values($tier1CitiesInFacet), array_values($cities), array_values($disabled));
			}
		}
	}

	function sortStates($states) {
		$disabled = array();
		foreach ($states as $key => $value) {
			if(isset($value['enabled']) && empty($value['enabled'])){
				$disabled[] = $value;
				unset($states[$key]);
				continue;
			}
		}

		uasort($states, array('SolrResponseParser','sortByName'));
		uasort($disabled, array('SolrResponseParser','sortByName'));
		$test = array_values((array)$states) + array_values((array)$disabled);
		return $test;
	}

	function sortByName($a, $b) {
		if(strtolower($a['name']) < strtolower($b['name'])) {
			return -1;
		} else {
			return 1;
		}
	}

	private function getTier1Cities() {
		$cityTierObjList = $this->locationRepository->getCitiesByMultipleTiers(array(1), 2);
		foreach ($cityTierObjList[1] as $key => $cityObj) {
			$cityTier1List[] = $cityObj->getId();
		}
		
		return $cityTier1List;
	}

	public function parseSpellCorrection($solrContent) {
		
		$result[$solrContent['spellcheck']['collations']['collation']['collationQuery']] = $solrContent['spellcheck']['collations']['collation']['hits'];
        $result[$solrContent['spellcheck']['collations']['collation 1']['collationQuery']] = $solrContent['spellcheck']['collations']['collation 1']['hits'];
        $result[$solrContent['spellcheck']['collations']['collation 2']['collationQuery']] = $solrContent['spellcheck']['collations']['collation 2']['hits'];
        $result[$solrContent['spellcheck']['collations']['collation 3']['collationQuery']] = $solrContent['spellcheck']['collations']['collation 3']['hits'];
        $result[$solrContent['spellcheck']['collations']['collation 4']['collationQuery']] = $solrContent['spellcheck']['collations']['collation 4']['hits'];
        arsort($result);

        return key($result);
    }

    public function getAllVirtualCities() {
		if(empty($this->virtualCityList)) {
			$virtualCities = array_keys($this->searchCommon->getVirtualCityMappingForSearch());
			foreach ($virtualCities as $key => $value) {
				$this->virtualCityList[] = $value;
			}
		}
		return $this->virtualCityList;
	}

	public function parseQuestionResults($solrContent){
		foreach ($solrContent['response']['docs'] as $solrDoc) {
            $valueArr = explode('::', $solrDoc[$this->FIELD_ALIAS['nl_entity_quality_name_id_type_map']]);
            $temp = array();
            $temp['quality'] = $valueArr[0];
            $temp['name'] = $this->sanatizeInputText(html_entity_decode($valueArr[1]));
            $temp['id'] = $valueArr[2];
            $temp['type'] = $valueArr[3];
            $temp['creationDate'] = $solrDoc[$this->FIELD_ALIAS['nl_entity_creation_date']];
            //$temp['url'] = $solrDoc[$this->FIELD_ALIAS['nl_entity_url']];
            //if(empty($solrResults[$temp['id']]) || $solrResults[$temp['id']] < $temp['quality']) {
                $solrResults[$temp['id']] = $temp; //will let multiple question, with same text, appear on QSRP
            //}
        }

        $data['resultCount'] = $solrContent['response']['numFound'];
        $data['result'] = $solrResults;

        if(!empty($data['result'])) {
	        foreach ($solrContent['facet_counts']['facet_queries'] as $key => $value) {
	        	if(!empty($value)) {
	        		$data['filters'][] = $key;
	        	}
	        }
	    }
	    
        return $data;
    }

    public function parseQuestionTopicResults($solrContent){
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
            $solrResults[$temp['id']] = $temp;
        }
        
        $data['resultCount'] = $solrContent['response']['numFound'];
        $data['result'] = $solrResults;
        
        return $data;
    }

    public function sanatizeInputText($value){
		$updateValue = $value;
		$updateValue = preg_replace("/\//", " / ", $updateValue);
		//$updateValue = preg_replace("/\./", "", $updateValue);
		$updateValue = preg_replace("/\(/", " ( ", $updateValue);
		$updateValue = preg_replace("/\)/", " ) ", $updateValue);
		$updateValue = preg_replace("/[^a-zA-Z0-9:.@?+&'\s\\(\\)\\/]/", " ", $updateValue);
		$updateValue = preg_replace("/(\s)+amp(\s)+/", " and ", $updateValue);
		$updateValue = preg_replace("/quot/", "", $updateValue);
		$updateValue = preg_replace("/\s+/", " ", $updateValue);
		$updateValue = trim($updateValue);
		return $updateValue;
	}
}
