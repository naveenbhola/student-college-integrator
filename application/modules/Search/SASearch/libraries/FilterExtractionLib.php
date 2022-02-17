<?php
class FilterExtractionLib{

	public function extractFeesFilterValues($facets, $request, $updateFilterCallFlag = false) {
        $ci = &get_instance();
        $feesUppervalueFixed=$ci->config->item('SA_SEARCH_FEES_UPPER_VALUE');
		$filterUpdateSuffix = ($updateFilterCallFlag == 1?"_parent":"");
        $feesVals = array_keys($facets['saCourseFees'.$filterUpdateSuffix]);
        $sanatizedFeesVals = $this->sanatizeArrayToGetNumericValues($feesVals);
        if(count($sanatizedFeesVals)==0){
            return array();
        }
        $minSanataizedFeeInLakhs=(min($sanatizedFeesVals)/100000);
        $roundedMinFee=  $this->_roundMinValueToImmediateLowerValue($minSanataizedFeeInLakhs,0.5);
        $maxSanataizedFeeInLakhs=(max($sanatizedFeesVals)/100000);
        $roundedMaxFee=  $this->_roundMaxValueToImmediateHigherValue($maxSanataizedFeeInLakhs,0.5);
        if($minSanataizedFeeInLakhs==$maxSanataizedFeeInLakhs){
             return array('max' => 1, 'min' =>1, 'scale' => 0.5);
        }
        if($roundedMaxFee>$feesUppervalueFixed){
            $roundedMaxFee=$feesUppervalueFixed;
        }
        $showPlus=0;
        if($maxSanataizedFeeInLakhs>$feesUppervalueFixed){
            $showPlus=1;
        }
        $this->equateMinAndMaxIfMinGreater($roundedMinFee,$roundedMaxFee);
        return array('max' => $roundedMaxFee, 'min' =>$roundedMinFee, 'scale' => 0.5,'showPlus'=>$showPlus);
        
    }
    private function _roundMinValueToImmediateLowerValue($minValue,$step){
        $lowestNearInteger=floor($minValue); 
        $decimalPart=$minValue-$lowestNearInteger;
        if($decimalPart>=$step){
            return $lowestNearInteger+$step;
        }else{
            return $lowestNearInteger;
        }
    }
    private function equateMinAndMaxIfMinGreater(&$minimumVal,&$maximumVal){
        if($minimumVal>$maximumVal){
            $minimumVal=$maximumVal;
        }
    }
    private function _roundMaxValueToImmediateHigherValue($maxValue,$step){
        $lowestNearInteger=floor($maxValue);
        $decimalPart=$maxValue-$lowestNearInteger;
        if($decimalPart==0){
            return $lowestNearInteger;
        }
        if($decimalPart<=$step){
            return $lowestNearInteger+$step;
        }else{
            return $lowestNearInteger +1;
        }
    }
    

    public function extractExamFilterValues($facets,$request,$updateFilterCallFlag=false){
                $ci = &get_instance();
                $lib = $ci->load->library('listingPosting/AbroadCommonLib');
                $examMasterList=$lib->getAbroadExamsMasterList('examId');
                $filter = array();
                $filterUpdateSuffix = ($updateFilterCallFlag == 1?"_parent":"");
                foreach($facets['saCourseEligibilityExamsIdMap'.$filterUpdateSuffix] as $nameId => $count){
				$nameId = explode(":",$nameId);
                        $examScoreKeyName='sa'.$nameId[0].'StrExamScore';
                        $examScoreSanatized=$this->sanatizeArrayToGetNumericValues(array_keys($facets[$examScoreKeyName]));
                        if($nameId[1]==7 ||$nameId[1]==8 || $nameId[1]==9 ){
                                continue;
                        }
                         
                        if (count($examScoreSanatized) == 0) {
                            $examScores = array('min' => 1, 'max' => 1, 'scale' => $examMasterList[$nameId[1]]['range']);
                            $filter[$nameId[0]] = array('name' => $nameId[0], 'id' => $nameId[1], 'count' => $count, 'scores' => $examScores);
                            continue;
                        }
                        if((integer)$nameId[1]>0){
                            
                            /**
                            if($nameId[1]==9){
                                $examScoreKeyName=$facets['sa'.$nameId[0].'ExamScore'];
                                $examScores=array('min'=>0,'max'=>2,'scale'=>1);
				$filter[] = array('name'=>$nameId[0],'id'=>$nameId[1],'count'=>$count,'scores'=>$examScores);
                                continue;
                            }
                             * 
                             */
                            $minimumScore=min($examScoreSanatized);
                            $maximumScore=max($examScoreSanatized);
                            $this->equateMinAndMaxIfMinGreater($minimumScore, $maximumScore);
                                $examScores=array('min'=>$minimumScore,'max'=>$maximumScore,'scale'=>$examMasterList[$nameId[1]]['range']);
				$filter[$nameId[0]] = array('name'=>$nameId[0],'id'=>$nameId[1],'count'=>$count,'scores'=>$examScores);
			}
		}
           	return $filter;
	} 
        public function extractCourseFilterValues($facets,$request,$solrRequestData,$filterUpdateCallFlag=false){
		$ci = &get_instance();
                $lib = $ci->load->library('SASearch/SearchPageLib');
        $ci->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $categoryRepository = $categoryBuilder->getCategoryRepository();        
		unset($ci);
                $originalSearchParams=$request->getInitialStateFilter();
                if(!$originalSearchParams){
                     $qerOutput = $solrRequestData['qerFilters'];
                }else{
                     $qerOutput=  $this->_getSpecializationArrayFromString($originalSearchParams);
                }
          	$filters = array();
                $categoryPresent=isset($qerOutput['categoryIds']) && count($qerOutput['categoryIds'])>0;
                $subCategoryPresent=isset($qerOutput['subCategoryIds']) && count($qerOutput['subCategoryIds'])>0;
                $specializationPresent=isset($qerOutput['specializationIds']) && count($qerOutput['specializationIds'])>0;
                $isDesiredCourse=isset($qerOutput['desiredCourse']) && count($qerOutput['desiredCourse'])>0;
                $showAll=($categoryPresent|| $subCategoryPresent || $specializationPresent);
                if($isDesiredCourse && !($showAll)){
                    $onlyDesiredCourse=1;
                }else{
                    $onlyDesiredCourse=0;
                }
                if($onlyDesiredCourse){
                    if(count(array_keys($facets['saCourseParentCategoryId']))==0){
                        $categoryIds = array_keys($facets['saCourseParentCategoryId_parent']);
                    }else{
                        $categoryIds = array_keys($facets['saCourseParentCategoryId']);
                    }
                        $subcatIds = $lib->getSubcategoriesForCategories($categoryIds,$categoryRepository);
			$data = $lib->getSubcategoryData($subcatIds,$solrRequestData,$categoryRepository);
			$data = $this->_processSubcategoryData($data,$facets,$filterUpdateCallFlag);
                        if(count($data)>0){
                            $filters['category'] = $data;
                        }
                        
                        return $filters;
                }
                if(!$showAll  || ($categoryPresent )){
			$categoryIds = $qerOutput['categoryIds'];
			$subcatIds = $lib->getSubcategoriesForCategories($categoryIds,$categoryRepository);
			$data = $lib->getSubcategoryData($subcatIds,$solrRequestData,$categoryRepository);
			$data = $this->_processSubcategoryData($data,$facets,$filterUpdateCallFlag);
                        if(count($data)>0){
                            $filters['category'] = $data;
                        }
			
		}
		if(!$showAll  || $subCategoryPresent){
                   	// Prepare specializations
			$subcatIds = $qerOutput['subCategoryIds'];
			$data = $lib->getSubcategoryData($subcatIds,$solrRequestData,$categoryRepository);
                        $data = $this->_processSubcategoryData($data,$facets,$filterUpdateCallFlag);
                        if(count($data)>0){
                           $filters['subcategory'] = $data;
                         }
		}
                /* *
		if(!$showAll  || $specializationPresent){
                	$specializationIds = $qerOutput['specializationIds'];
			$subcatIds = $lib->getSubCatIdBySpecializationId($specializationIds,$categoryRepository);
                        $data = $lib->getSubcategoryData($subcatIds,$solrRequestData,$categoryRepository);
                        $data = $this->_processSubcategoryData($data,$facets,$filterUpdateCallFlag);
                        if(count($data)>0){
                          if(isset($filters['category']) && is_array($filters['category'])){
                              $filters['category']=array_merge($filters['category'],$data);
                          }else{
                              $filters['category'] = $data;
                          }
                         
                        }
		} 
                 * 
                 */
                return $filters;
	}
        private function _getSpecializationInitialString($qerOutput,$facets){
                $categoryPresent=isset($qerOutput['categoryIds']) && count($qerOutput['categoryIds'])>0;
                $subCategoryPresent=isset($qerOutput['subCategoryIds']) && count($qerOutput['subCategoryIds'])>0;
                $specializationPresent=isset($qerOutput['specializationIds']) && count($qerOutput['specializationIds'])>0;
                $isDesiredCourse=isset($qerOutput['desiredCourse']) && count($qerOutput['desiredCourse'])>0;
                $categoryOrSubCatOrSpecPresent=($categoryPresent||$subCategoryPresent||$specializationPresent);
                if(!$categoryOrSubCatOrSpecPresent && $isDesiredCourse){
                   
                    $isOnlyDesiredCourse=1;
                }
                if($isOnlyDesiredCourse){
                    $qerOutput['categoryIds']=array_keys($facets['saCourseParentCategoryId']);
                    $categoryPresent=1;
                }
                if($categoryPresent){
                     $orignalFilterString=  $this->_getFilterString('c',$qerOutput['categoryIds']).'|';
                }
                if($subCategoryPresent){
                    $orignalFilterString.=  $this->_getFilterString('su',$qerOutput['subCategoryIds']).'|';
                }
                if($specializationPresent){
                    $orignalFilterString.=  $this->_getFilterString('sp',$qerOutput['specializationIds']).'|';
                }
                if($isDesiredCourse){
                     $orignalFilterString.=  $this->_getFilterString('sa',$qerOutput['desiredCourse']).'|';
                }
                if($orignalFilterString!=""){
                     $orignalFilterString = substr($orignalFilterString,0,strlen($orignalFilterString)-1);
                }
                return $orignalFilterString;

        }
        private function _getSpecializationArrayFromString($specializationString){
            $specializationSet=  explode('|', $specializationString);
            $finalSpecializationList=array();
            foreach($specializationSet as $specializations){
                $specializationNameAndId=  explode(':', $specializations);
                $specializationName=$specializationNameAndId[0];
                $specializationIds=$specializationNameAndId[1];
                switch ($specializationName){
                    case 'c':
                        $finalSpecializationList['categoryIds']=  explode(',', $specializationIds);
                        break;
                    case 'su':
                        $finalSpecializationList['subCategoryIds']=  explode(',', $specializationIds);
                        break;
                    case 'sp':
                        $finalSpecializationList['specializationIds']=  explode(',', $specializationIds);
                        break;
                     case 'sa':
                        $finalSpecializationList['desiredCourse']=  explode(',', $specializationIds);
                        break;
                }
            }
            return $finalSpecializationList;
        }
        private function _getFilterString($filterKey,$filterVal){
            if(isset($filterVal) && is_array($filterVal) && count($filterVal)>0){
                $filterString=$filterKey.":";  
                foreach ($filterVal as $filterValue){
                    $filterString.=$filterValue.',';
                }
                $filterString = substr($filterString,0,strlen($filterString)-1);
                return $filterString;
            }
            return "";
          
        }

        public function extractDurationFilterValues($facets,$request,$updateFilterCallFlag=false){
		$filterUpdateSuffix = ($updateFilterCallFlag == 1?"_parent":"");
          	$durationVals = array_keys($facets['saCourseDurationValue'.$filterUpdateSuffix]);
                $sanatizedDurationVals=$this->sanatizeArrayToGetNumericValues($durationVals);
                if(count($sanatizedDurationVals)>0){
                    if(count($sanatizedDurationVals)==1){
                   return array('max'=>1,'min'=>1,'scale'=>0.5);
                     }
                    $minimumVal=min($sanatizedDurationVals);
                    $maximumVal=max($sanatizedDurationVals);
                    $minimumVal=$this->_normalizeToLowerValue($minimumVal, 3);
                    $maximumVal=$this->_normalizeToHigherValue($maximumVal,3);
                    $this->equateMinAndMaxIfMinGreater($minimumVal, $maximumVal); // in months
                    return array('min'=>$minimumVal,'max'=>$maximumVal,'scale'=>3);
                }else{
                    return array();
                }
		
	}
        private function _normalizeToLowerValue($value,$step){
            $value=floor($value);
            if($value%$step>0){
                $value=$value-$value%$step;
            }
            return $value;
        }
        private function _normalizeToHigherValue($value,$step){
            $value=  ceil($value);
            if($value%$step>0){
                $value=((integer)($value/$step)+1)*$step;
            }
            return $value;
        }

        public function extractCourseLevelFilterValues($facets,$request,$updateFilterCallFlag=false){
		$filter = array();
		$filterUpdateSuffix = ($updateFilterCallFlag == 1?"_parent":"");               
		foreach($facets['saCourseLevel1'.$filterUpdateSuffix] as $level => $resultCount){
                    $level=str_replace('-', ' ', $level);
			$filter[$level] = array('level'=>$level,'count'=>$resultCount);
		}
            	return $filter;
	}

	public function extractDeadlineFilterValues($facets, $request,$updateFilterCallFlag = false) {
        $filterUpdateSuffix = ($updateFilterCallFlag == 1?"_parent":"");
        $values = array_keys($facets['saCourseApplicationSubmissionDeadline'.$filterUpdateSuffix]);
        if(count($values)==0){
            return array();
        }
        $filterValues = array();

        foreach ($values as $val) {
            $filterValues['dates'][] = reset(explode('T', $val));
        }
        usort($filterValues['dates'], function($a, $b) {
            $dateTimestamp1 = strtotime($a);
            $dateTimestamp2 = strtotime($b);

            return $dateTimestamp1 < $dateTimestamp2 ? -1 : 1;
        });
        foreach ($facets['saCourseApplicationSubmissionIntake'.$filterUpdateSuffix] as $season => $count) {
            $filterValues['seasons'][$season] = array('season' => $season, 'count' => $count);
        }
        return $filterValues;
    }

    public function extractScholarshipFilterValues($facets,$request,$updateFilterCallFlag = false){
		$filterUpdateSuffix = ($updateFilterCallFlag == 1?"_parent":"");
		if(!empty($facets['saCourseScholarship'.$filterUpdateSuffix])){
			return $facets['saCourseScholarship'.$filterUpdateSuffix];
		}
		return 0;
	}

	public function extractRMCFilterValues($facets,$request){
		if(!empty($facets['saCourseShikshaApply']['Yes'])){
			return $facets['saCourseShikshaApply']['Yes'];
		}
		return 0;
	}

	public function extractSOPFilterValues($facets,$request){
		if(!empty($facets['saCourseSOPRequired']['1'])){
			return $facets['saCourseSOPRequired']['1'];
		}
		return 0;
	}

	public function extractLORFilterValues($facets,$request){
		if(!empty($facets['saCourseLORRequired']['1'])){
			return $facets['saCourseLORRequired']['1'];
		}
		return 0;
	}	

	public function extract12thCutoffFilterValues($facets,$request,$updateFilterCallFlag = false){
            $filterUpdateSuffix = ($updateFilterCallFlag == 1?"_parent":"");
            $sanatized12thCutOff=$this->sanatizeArrayToGetNumericValues(array_keys($facets['saCourse12thCutoff'.$filterUpdateSuffix]));
            if(count($sanatized12thCutOff)>0){
                if(count($sanatized12thCutOff)==1){
                   return array('max'=>1,'min'=>1,'scale'=>0.5);
                }
                $minimumVal=min($sanatized12thCutOff);
                $minimumVal=  floor($minimumVal);
                $maximumVal=  max($sanatized12thCutOff);
                $maximumVal=  ceil($maximumVal);
                $this->equateMinAndMaxIfMinGreater($minimumVal, $maximumVal);
                return array('max'=>$maximumVal,'min'=>$minimumVal,'scale'=>1);
            }else{
                return array();
            }
	    
	}

	public function extractUgCutoffFilterValues($facets,$request,$updateFilterCallFlag = false){
             $filterUpdateSuffix = ($updateFilterCallFlag == 1?"_parent":"");
             $sanatizedUgCutOff=$this->sanatizeArrayToGetNumericValues(array_keys($facets['saCourseUgCutoffConverted'.$filterUpdateSuffix]));
             if(count($sanatizedUgCutOff)>0){
                if(count($sanatizedUgCutOff)==1){
                   return array('max'=>1,'min'=>1,'scale'=>0.5);
                }
                 $minimumVal=min($sanatizedUgCutOff);
                 $minimumVal=  floor($minimumVal);
                 $maximumVal=  max($sanatizedUgCutOff);
                 $maximumVal=  ceil($maximumVal);
                 $this->equateMinAndMaxIfMinGreater($minimumVal, $maximumVal);
                 return array('max'=>$maximumVal,'min'=>$minimumVal,'scale'=>1);
             }else{
                 return array();
             }
              
	}

	public function extractWorkExperienceFilterValues($facets,$request,$updateFilterCallFlag = false){
              $filterUpdateSuffix = ($updateFilterCallFlag == 1?"_parent":"");
              $ci = &get_instance();
              $maxFixedWorkExperienceValue=$ci->config->item('SA_SEARCH_WORK_EXPERIENCE_UPPER_VALUE');
              $sanatizedWorkExperience=$this->sanatizeArrayToGetNumericValues(array_keys($facets['saCourseWorkXP'.$filterUpdateSuffix]));
              if(($key = array_search(0, $sanatizedWorkExperience)) !== false) {
                unset($sanatizedWorkExperience[$key]);
              }
              if(count($sanatizedWorkExperience)==0){
                  return array();
              } 
              if(count($sanatizedWorkExperience)==1){
                   return array('max'=>1,'min'=>1,'scale'=>0.5);
              }
              $maxWorkExperience=max($sanatizedWorkExperience);
              if($maxWorkExperience>$maxFixedWorkExperienceValue){
                  $maxWorkExperience=$maxFixedWorkExperienceValue;
              }
              $this->equateMinAndMaxIfMinGreater($minimumWorkExperience, $maxWorkExperience);
              $minimumWorkExperience=min($sanatizedWorkExperience);
              $minimumWorkExperience=  floor($minimumWorkExperience);
              $maxWorkExperience=  ceil($maxWorkExperience);
            
              return array('max'=>$maxWorkExperience,'min'=>$minimumWorkExperience,'scale'=>0.5);
 	}

	public function extractTypeFilterValues($facets,$request){
		$filters = array();
		foreach($facets['saUnivType'] as $type=>$count){
			$filters[] = array('type'=>$type,'count'=>$count);
		}
		return $filters;
	}

	public function extractType2FilterValues($facets,$request){
		$filters = array();
		foreach($facets['saUnivType2'] as $type=>$count){
			$filters[] = array('type'=>$type,'count'=>$count);
		}
		return $filters;
	}

	public function extractLivingExpensesFilterValues($facets,$request){
		$expenseVals = array_keys($facets['saUnivLivingExpense']);
		return array('max'=>max($expenseVals), 'min'=>min($expenseVals));
	}

        public function computeOriginalFilterState($solrRequestData,$request,$facets) {
        $originalState=$request->getInitialStateFilter();
        if (!$originalState) {
            $qerOutput = $solrRequestData['qerFilters'];
            $locationString = $this->getLocationInitialStateString($qerOutput);
            $specializationString = $this->_getSpecializationInitialString($qerOutput,$facets);
            if ($locationString != "" && $specializationString != "") {
                return $locationString . '|' . $specializationString;
            }
            else if($locationString!=""){
                return $locationString;
            }
            else if($specializationString!=""){
                return $specializationString;
            }
        }
        return "";
    }

    public function extractLocationFilterValues($facets,$request,$solrRequestData,$filterUpdateCall=false){
		$ci = &get_instance();
		$lib = $ci->load->library('SASearch/SearchPageLib');

        $ci->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();


		unset($ci);
                $originalState=$request->getInitialStateFilter();
                if(!$originalState){
                    $qerOutput = $solrRequestData['qerFilters'];
                }else{
                    $qerOutput=  $this->_getLocationArrayFromInitialStringFilter($originalState);
                  
                }
		$qerTest = empty($qerOutput['city']) && empty($qerOutput['state']) && empty($qerOutput['country']) && empty($qerOutput['continent']) && empty($qerOutput['desiredcourse']);
		$structure = array();
                if(!empty($qerOutput['city'])){
                   	$structure = array();
		}
		if(!empty($qerOutput['state'])){
			$structure['state'] = $this->_getStateFilterData($facets,$lib,$qerOutput['state'],$filterUpdateCall,$locationRepository);
		}
		if(!empty($qerOutput['country'])){
			$countryIds = array_keys($facets['saUnivCountryId']);
                  	$structure['country'] = $this->_getCountryFilterData($facets,$lib,$qerOutput['country'],$filterUpdateCall,$locationRepository);
		}
		if(!empty($qerOutput['continent'])){
			$structure['continent'] = $this->_getContinentFilterData($facets,$lib,$qerOutput['continent'],$filterUpdateCall,$locationRepository);
		}
		if($qerTest){
			$countryIds = array_keys($facets['saUnivCountryId']);
                        $allData=$this->_getCountryFilterData($facets,$lib,$countryIds,$filterUpdateCall,$locationRepository);
                        if(count($allData)>0){
                            $structure['all'] = $allData;
                        }
		}
		return $structure;
	}
        private function getLocationInitialStateString($qerOutput){
            $locationString="";
            if(isset($qerOutput['city']) && is_array($qerOutput['city']) && count($qerOutput['city'])>0){
                $locationString.=$this->_getFilterString('ct', $qerOutput['city']).'|';
            }
            if(isset($qerOutput['state']) && is_array($qerOutput['state']) && count($qerOutput['state'])>0){
                 $locationString.=$this->_getFilterString('st', $qerOutput['state']).'|';
            }
            if(isset($qerOutput['country']) && is_array($qerOutput['country']) && count($qerOutput['country'])>0){
                 $locationString.=$this->_getFilterString('co', $qerOutput['country']).'|';
            }
            if(isset($qerOutput['continent']) && is_array($qerOutput['continent']) && count($qerOutput['continent'])>0){
                 $locationString.=$this->_getFilterString('cn', $qerOutput['continent']).'|';
            }
            $locationString=   substr($locationString,0,strlen($locationString)-1);
            return $locationString;
        }
        private function _getLocationArrayFromInitialStringFilter($locationString){
            $locationSet=  explode('|', $locationString);
            $finalLocationArray=array();
            foreach ($locationSet as $locations){
                $locationNameAndIds=  explode(':', $locations);
                $locationName=$locationNameAndIds[0];
                $locationIds=$locationNameAndIds[1];
                switch ($locationName){
                    case 'ct':
                        $finalLocationArray['city']=  explode(',', $locationIds);
                        break;
                    case 'st':
                        $finalLocationArray['state']=  explode(',', $locationIds);
                        break;
                    case 'co':
                        $finalLocationArray['country']=  explode(',', $locationIds);
                        break;
                    case 'cn':
                        $finalLocationArray['continent']=  explode(',', $locationIds);
                        break;
                    
                }
            }
            return $finalLocationArray;
        }


        public function extractDesiredCoursesFilterValues($facets,$request){
		$ci = &get_instance();
		$lib = $ci->load->library('listingPosting/AbroadCommonLib');
		$ldbCourses = $lib->getAbroadMainLDBCourses();
		foreach($ldbCourses as $ldbcourse){
			$courseMap[$ldbcourse['SpecializationId']] = $ldbcourse['CourseName'];
		}
		$courses = array();
		foreach($facets['saCourseDesiredCourseId'] as $id=>$count){
			$courses[$id] = array('name'=>$courseMap[$id],'count'=>$count);
		}
		return $courses;
	}
	
	public function extractAccommodationFilterValues($facets,$request){
		if(!empty($facets['saUnivAccommodationAvailable']['Yes'])){
			return $facets['saUnivAccommodationAvailable']['Yes'];
		}
		return 0;
	}

	private function _getStateFilterData($facets,$lib,$stateIds,$filterUpdateCall,$locationRepository){
	//	$stateIds = array_keys($facets['saUnivStateId']);
		$data = $lib->getCityDataByStateIds($stateIds,$locationRepository);
		$data = $this->_processCityStateData($facets,$data,$filterUpdateCall);
		return $data;
	}

	private function _getCountryFilterData($facets,$lib,$countryIds,$filterUpdateCall,$locationRepository){
		
        $countryNames = $lib->getCountryNames($countryIds,$locationRepository);
		$countryStateMapping = $lib->getCountryStateStructureByCountryIds($countryIds,$locationRepository);
                $stateCountries = array_unique($countryStateMapping);
		$statelessCountryIds = array_diff($countryIds, $stateCountries);
		$filter = array();
                $parentSuffix="";
                if($filterUpdateCall){
                    $parentSuffix="_parent";
                }
		if(!empty($countryStateMapping)){
			$stateIds = array_keys($countryStateMapping);
			$data = $lib->getCityDataByStateIds($stateIds,$locationRepository);
			$data = $this->_processCityStateData($facets,$data,$filterUpdateCall);
			$finalData = array();
			foreach($data as $stateId => $values){
				$finalData[$countryStateMapping[$stateId]]['name'] = $countryNames[$countryStateMapping[$stateId]];
				$finalData[$countryStateMapping[$stateId]]['count'] = $facets['saUnivCountryId'.$parentSuffix][$countryStateMapping[$stateId]];				
				$finalData[$countryStateMapping[$stateId]][$stateId] = $values;
			}
			$filter['stateCountries'] = $finalData;
		}
		if(count($statelessCountryIds) > 0){	// When a country doesn't have states.
			$countryCityMapping = $lib->getCountryCityStructureByCountryIds($statelessCountryIds,$locationRepository);
			if(!empty($countryCityMapping)){
                                $parentSuffix="";
                                if($filterUpdateCall){
                                    $parentSuffix="_parent";
                                }
				$cityIds = array_keys($countryCityMapping);
				$data = $lib->getCityDataByIds($cityIds,$locationRepository);
				$finalData = array();
				foreach($data as $cityId => $name){
					if(empty($facets['saUnivCityId'.$parentSuffix][$cityId])){
						continue;
					}
					$finalData[$countryCityMapping[$cityId]]['name'] = $countryNames[$countryCityMapping[$cityId]];
					$finalData[$countryCityMapping[$cityId]]['count'] = $facets['saUnivCountryId'.$parentSuffix][$countryCityMapping[$cityId]];
					$finalData[$countryCityMapping[$cityId]][$cityId] = array('name'=>$name,'count'=>$facets['saUnivCityId'.$parentSuffix][$cityId]);
				}
				$filter['cityCountries'] = $finalData;
			}
			
		}
		return $filter;
	}

	private function _processCityStateData($facets,$data,$filterUpdateCall){
        if(!$filterUpdateCall){
               $stateFacet=$facets['saUnivStateId'];
               $cityFacet=$facets['saUnivCityId'];
        }else{
               $stateFacet=$facets['saUnivStateId_parent'];
               $cityFacet=$facets['saUnivCityId_parent'];
        }
            	foreach($data as $stateId => $stateData){
			if(empty($stateFacet[$stateId])){
				unset($data[$stateId]);
				continue;
			}
			$data[$stateId]['count'] = $stateFacet[$stateId];
			foreach($stateData as $cityId => $cityData){
				if((integer)$cityId > 0){
					if(empty($cityFacet[$cityId])){
						unset($data[$stateId][$cityId]);
						continue;
					}
					$data[$stateId][$cityId]['count'] = $cityFacet[$cityId];
				}
			}
		}
		return $data;
	}

	private function _getContinentFilterData($facets,$lib,$continentIds,$filterUpdateCall,$locationRepository){
		//$continentIds = array_keys($facets['saUnivContinentId']);
		$continentCountryMap = $lib->getContinentCountryStructureByContinentIds($continentIds);
		$countryIds = array_keys($continentCountryMap);
		if(empty($countryIds)){
			return array();
		}
		$data = $this->_getCountryFilterData($facets,$lib,$countryIds,$filterUpdateCall,$locationRepository);
		$filter = array();
		foreach($data as $countryType => $countriesData){
			foreach($countriesData as $countryId => $countryData){
				$filter[$continentCountryMap[$countryId]][$countryId] = $countryData;
			}
		}
		return $filter;
	}

	private function _processSubcategoryData($data,$facets,$filterUpdateCallFlag){
         
            $parentSuffix="";
            if($filterUpdateCallFlag){
                $parentSuffix="_parent";
            }
     
          	foreach($data as $subcatId => $values){
                   	if(empty($facets['saCourseSubcategoryId'.$parentSuffix][$subcatId])){
				unset($data[$subcatId]);
				continue;
			}
			$data[$subcatId]['count'] = $facets['saCourseSubcategoryId'.$parentSuffix][$subcatId];
                   	foreach($values as $specializationName => $vals){
                                $specializationName=trim($specializationName);
                                
                                if(empty($specializationName) || $specializationName=='name'){
					continue;
				}
                                $solrSpecializationName= str_replace('-',' ',strtolower($specializationName));
                                $solrSpecializationName = preg_replace('!\s+!', ' ', strtolower($solrSpecializationName));
                                $solrSpecializationName= str_replace(' ','-',strtolower($solrSpecializationName));
                                $solrSpecializationName= str_replace('&','amp',strtolower($solrSpecializationName));
                                $solrSpecializationName= str_replace(',','',strtolower($solrSpecializationName));
                                $solrSpecializationName.=$subcatId;
                            
                                if(empty($facets['saCourseSpecializationNameSubcatIdMap'.$parentSuffix][$solrSpecializationName])){
					unset($data[$subcatId][$specializationName]);
					continue;
				}
                                $specializationID=$vals['id'];
				$data[$subcatId][$specializationName]['count'] = $facets['saCourseSpecializationNameSubcatIdMap'.$parentSuffix][$solrSpecializationName];
			}
		}
           	return $data;
	}

	private function _processSpecializationData($data,$facets,$filterUpdateCallFlag){
            //_p($data);exit;
            $parentSuffix="";
            if($filterUpdateCallFlag){
                $parentSuffix="_parent";
            }
         	foreach($data as $categoryId => $categoryData){
			if((integer)$categoryId <= 0){
				continue;
			}
			if(empty($facets['saCourseParentCategoryId'.$parentSuffix][$categoryId])){
				unset($data[$categoryId]);
				continue;
			}
			$data[$categoryId]['count'] = $facets['saCourseParentCategoryId'.$parentSuffix][$categoryId];
			foreach($categoryData as $subcategoryId => $subcategoryData){
				if((integer)$subcategoryId <= 0){
					continue;
				}
				if(empty($facets['saCourseSubcategoryId'.$parentSuffix][$subcategoryId])){
					unset($data[$categoryId][$subcategoryId]);
					continue;
				}
				$data[$categoryId][$subcategoryId]['count'] = $facets['saCourseSubcategoryId'.$parentSuffix][$subcategoryId];
                
                                foreach($subcategoryData as $specializationName => $specializationData){
                                     $specializationName=trim($specializationName);
                                if(empty($specializationName) || $specializationName=='name'){
					continue;
				}
                               
                                $solrSpecializationName= str_replace(' ','-',strtolower($specializationName));
                         		if(empty($facets['saCourseSpecializationName'.$parentSuffix][$solrSpecializationName])){
						unset($data[$categoryId][$subcategoryId][$specializationName]);
						continue;
					}
					$data[$categoryId][$subcategoryId][$specializationName]['count'] = $facets['saCourseSpecializationName'.$parentSuffix][$solrSpecializationName];
				}
			}
		}
             
          	return $data;
	}
        private function sanatizeArrayToGetNumericValues($inputArray){
            $sanatizedArray=  array_filter($inputArray,function($elem){
                if(is_numeric($elem) && $elem>=0){
                    return true;
                }
                else{
                    return false;
                }
            });
            $uniqueInputArray=array_unique($sanatizedArray);
            if(count($uniqueInputArray)==1 && $uniqueInputArray[0]==0){
                 return array();
            }
            return $sanatizedArray;
        }
}