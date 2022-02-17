<?php
/*
 * Purpose - Solr queries used in search
 */
class AutoSuggestorSolrRequestGenerator {
    private $filters;

	function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->config("SASearch/SASearchPageConfig");
		$this->filters = array('city', 'state', 'country','continent','category',
                    'subcategory','desiredCourse', 'exams', 'specialization', 'specializationIds', 'level',
                    'institute','universities','courseFee','examScore',
                    'workExperience','ugMarks','class12Marks',/*'applicationDeadline',
                    'intakeSeason',*/'scholarship','sop','lor',
                    'courseDuration');
		$this->solrDataFieldAliases = $this->CI->config->item('SolrDataFieldAliases');
    }

    public function generateUrlOnSearch(&$solrRequestData,$searchType) {
        $urlComponents = array();
        
        //Add query term
        $solrRequestData['isQEmpty'] = false;
        $urlComponents[] = $this->getQueryTerm($solrRequestData);

        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';

        //Filters to apply
        $urlComponents[] = 'fq=facetype:abroadlisting';
        $originalSolrData=$solrRequestData;
        $urlFqComponents = $this->getFieldQueryComponents($solrRequestData);
        $urlComponents = array_merge($urlComponents, $urlFqComponents);
        
        
        //group by saUnivId
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.field=saUnivId';
        $urlComponents[] = 'group.ngroups=true'; //total number of results
		
		//if($searchType=='university'){
        $urlComponents[] = 'group.facet=true';
		//}
        //Add sorting
        $otherComponents = array();
        $otherComponents = $this->getFieldListSortingComponentsAndQueryFields($solrRequestData);
        
        $urlComponents = array_merge($urlComponents, $otherComponents);

        //paginate results
		//_p($solrRequestData);die;
		if($solrRequestData['filterUpdateCallFlag']==1)
		{
             //Facets
            $urlFacetComponents = $this->getFacetComponents($originalSolrData,$searchType);
            $urlComponents = array_merge($urlComponents, $urlFacetComponents);
            if(isMobileRequest()){
			 $urlComponents[] = 'rows=0'; // we need to get only facets in case of filter update call (dynamic filters while switching)
            }else if($solrRequestData['filterWithDataFlag'] == 1){
                $urlComponents[] = 'rows='.SA_SEARCH_PAGE_LIMIT;
            }
		}else{
            
            if($searchType=='course'){
                $urlFacetComponents = $this->getInitialFacetComponent();
                $urlComponents = array_merge($urlComponents, $urlFacetComponents);
            }
			$urlComponents[] = 'start='.(($solrRequestData['pageNum']-1)*$solrRequestData['pageLimit']);
			$urlComponents[] = 'rows='.$solrRequestData['pageLimit'];
		}

        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        //_p($solrUrl);die;
        return $solrUrl;
    }

    function getUniversityCountBasedOnLocation(){
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'q=*:*';

        //Filters to apply
        $urlComponents[] = 'fq=facetype:abroadlisting';        
        
        //group by saUnivId
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'group.field=saUnivId';        
                
        $urlComponents[] = 'group.facet=true';
        $urlComponents[] = 'facet.field=saUnivCityId';
        $urlComponents[] = 'facet.field=saUnivStateId';
        $urlComponents[] = 'facet.limit=10000';
        
        $urlComponents[] = 'rows=0';
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);        
        return $solrUrl;
    }
	
	private function getFieldQueryComponents(&$solrRequestData, $applyOnlyQerFilter = 0) {
        $urlComponents    = array();
		$locationComponents = array();
		$cateComponents = array();
                $sopOrLorFound=0;
                $this->searchpagemodel = $this->CI->load->model('SASearch/sasearchmodel');
                $subCatAndCatId = $this->searchpagemodel->getSubCatIdAndCatIdBySpecializationId($solrRequestData['qerFilters']['specializationIds']);
                $this->_getSpecNameFromFilterParam($solrRequestData,$subCatAndCatId);
           //    _p($solrRequestData);exit;
                 $this->_normalizeLocationFilterUnsetWhichAreSame($solrRequestData);
                 $specAndCondition=$this->_normalizeCatSubCatFilterUnsetWhichAreSame($solrRequestData,$subCatAndCatId);
             
               foreach ($this->filters as $key => $filterName) {
           
            switch($filterName) {
            
                case 'city': //user + qer
                    $cityFilterValues = array();
                    if(is_array($solrRequestData['qerFilters']['city']) && count($solrRequestData['qerFilters']['city']) > 0) {
                        $cityFilterValues = $solrRequestData['qerFilters']['city'];
                    }
                     foreach($cityFilterValues as $key=>$id) {
						$cityFilterValues[$key] = (int)$id;
                    }
					if(!empty($cityFilterValues) && count($cityFilterValues)>0)
					{
						$locationComponents[] = 'saUnivCityId:('.implode('%20OR%20',$cityFilterValues).')';
					}
                    break;

                case 'state' : //user + qer
                    $stateFilterValues = array();
                    if(is_array($solrRequestData['qerFilters']['state']) && count($solrRequestData['qerFilters']['state']) > 0) {
                        $stateFilterValues = $solrRequestData['qerFilters']['state'];
                    }
                    
                    foreach($stateFilterValues as $key=>$stateRowId) {
						$stateFilterValues[$key] = (int)$stateRowId;
                    }
					if(!empty($stateFilterValues) && count($stateFilterValues)>0)
					{
						$locationComponents[] = 'saUnivStateId:('.implode('%20OR%20',$stateFilterValues).')';
					}
                    break;
				
				case 'country' : //user + qer
                    $countryFilterValues = array();
                    if(is_array($solrRequestData['qerFilters']['country']) && count($solrRequestData['qerFilters']['country']) > 0) {
                        $countryFilterValues = $solrRequestData['qerFilters']['country'];
                    }
                    
                    foreach($countryFilterValues as $key=>$id) {
						$countryFilterValues[$key] = (int)$id;
                    }
					if(!empty($countryFilterValues) && count($countryFilterValues)>0)
					{
						$locationComponents[] = 'saUnivCountryId:('.implode('%20OR%20',$countryFilterValues).')';
					}
                    break;
				
				case 'continent' : //user + qer
                    $continentFilterValues = array();
                    if(is_array($solrRequestData['qerFilters']['continent']) && count($solrRequestData['qerFilters']['continent']) > 0) {
                        $continentFilterValues = $solrRequestData['qerFilters']['continent'];
                    }
                    
                    foreach($continentFilterValues as $key=>$id) {
						$continentFilterValues[$key] = (int)$id;
                    }
					if(!empty($continentFilterValues) && count($continentFilterValues)>0)
					{
						$locationComponents[] = 'saUnivContinentId:('.implode('%20OR%20',$continentFilterValues).')';
					}
                    break;
				
				case 'category':
					$cateFilterValues = array();
                    if(is_array($solrRequestData['qerFilters']['categoryIds']) && count($solrRequestData['qerFilters']['categoryIds']) > 0) {
                        $cateFilterValues = $solrRequestData['qerFilters']['categoryIds'];
                    }
                    
                    foreach($cateFilterValues as $key=>$id) {
						$cateFilterValues[$key] = (int)$id;
                    }
					if(!empty($cateFilterValues) && count($cateFilterValues)>0)
					{
						$filterTag='';
						$cateComponents[] = $filterTag.'saCourseParentCategoryId:('.implode('%20OR%20',$cateFilterValues).')';
					}
                    
					break;
				
				case 'subcategory':
					$subcateFilterValues = array();
                    if(is_array($solrRequestData['qerFilters']['subCategoryIds']) && count($solrRequestData['qerFilters']['subCategoryIds']) > 0) {
                        $subcateFilterValues = $solrRequestData['qerFilters']['subCategoryIds'];
                    }
                    
                    foreach($subcateFilterValues as $key=>$id) {
						$subcateFilterValues[$key] = (int)$id;
                    }
					if(!empty($subcateFilterValues) && count($subcateFilterValues)>0)
					{
						$filterTag='';
						$cateComponents[] = $filterTag.'saCourseSubcategoryId:('.implode('%20OR%20',$subcateFilterValues).')';
                    }
					break;
				
				case 'desiredCourse':
					$ldbCourseFilterValues = array();
                    if(is_array($solrRequestData['qerFilters']['desiredCourse']) && count($solrRequestData['qerFilters']['desiredCourse']) > 0) {
                        $ldbCourseFilterValues = $solrRequestData['qerFilters']['desiredCourse'];
                    }
                    
                    foreach($ldbCourseFilterValues as $key=>$id) {
						$ldbCourseFilterValues[$key] = (int)$id;
                    }
					if(!empty($ldbCourseFilterValues) && count($ldbCourseFilterValues)>0)
					{
						$filterTag='{!tag=f'.$this->solrDataFieldAliases['saCourseDesiredCourseId'].'}';
						$urlComponents[] ='fq='.$filterTag.'saCourseDesiredCourseId:('.implode('%20OR%20',$ldbCourseFilterValues).')';
					}
                    
					break;
				
				case 'exams':
					$examsFilterValues = array();
                                    if(!is_array($solrRequestData['qerFilters']['exams']) && $solrRequestData['qerFilters']['exams']!=''){
                                        $solrRequestData['qerFilters']['exams']=array($solrRequestData['qerFilters']['exams']);
                                    }
                    if(is_array($solrRequestData['qerFilters']['exams']) && count($solrRequestData['qerFilters']['exams']) > 0) {
                        $examsFilterValues = $solrRequestData['qerFilters']['exams'];
                    }
                    
                    foreach($examsFilterValues as $key=>$name) {
						$examsFilterValues[$key] = strtoupper($name);
                    }
					if(!empty($examsFilterValues) && count($examsFilterValues)>0)
					{
						$filterTag='{!tag=f'.$this->solrDataFieldAliases['saCourseEligibilityExams'].'}';
						$urlComponents[] = 'fq='.$filterTag.'saCourseEligibilityExams:('.implode('%20OR%20',$examsFilterValues).')';
					}
					break;
	
				case 'level' : //user + qer
                    $levelFilterValues = array();
                    if(is_array($solrRequestData['qerFilters']['level']) && count($solrRequestData['qerFilters']['level']) > 0) {
                        $levelFilterValues = $solrRequestData['qerFilters']['level'];
                    }
                    
                    foreach($levelFilterValues as $key=>$name) {
						$levelFilterValues[$key] = '"'.ucwords($name).'"';
                    }
					if(!empty($levelFilterValues) && count($levelFilterValues)>0)
					{
						$filterTag='{!tag=f'.$this->solrDataFieldAliases['saCourseLevel1'].'}';
						$urlComponents[] = 'fq='.$filterTag.'saCourseLevel1:('.implode('%20OR%20',$levelFilterValues).')';
					}
                    break;
				
				case 'universities':
					$univFilterValues = array();
                    if(is_array($solrRequestData['qerFilters']['universities']) && count($solrRequestData['qerFilters']['universities']) > 0) {
                        $univFilterValues = $solrRequestData['qerFilters']['universities'];
                    }
                    
                    foreach($univFilterValues as $key=>$id) {
						$univFilterValues[$key] = (int)$id;
                    }
			if(!empty($univFilterValues) && count($univFilterValues)>0)
			{
		                //$filterTag='{!tag=fsaUnivId}'; // facet not made on univ id
		                $filterTag = '';
				$urlComponents[] = 'fq='.$filterTag.'saUnivId:('.implode('%20OR%20',$univFilterValues).')';
			}
                    break;
				
				case 'courseFee':
					$courseFeeValues = array();
                    if(is_array($solrRequestData['qerFilters']['courseFee']) && count($solrRequestData['qerFilters']['courseFee']) > 0) {
                        $courseFeeValues = $solrRequestData['qerFilters']['courseFee'];
                    }
                    foreach($courseFeeValues as $key=>$id) {
						$courseFeeValues[$key] = (float)$id;
                    }
					// check for 50 lakh in maxscore & convert to actual lakhs
					if(!empty($courseFeeValues) && count($courseFeeValues)>0){
						$courseFeeValues[0]=$courseFeeValues[0]*100000;
						$courseFeeValues[1]=$courseFeeValues[1]*100000;
						$feeString=($courseFeeValues[1] ==5000000||$courseFeeValues[1] ==''?min($courseFeeValues[0],$courseFeeValues[1])." TO *":$courseFeeValues[0]." TO ".$courseFeeValues[1]);
						$filterTag='{!tag=f'.$this->solrDataFieldAliases['saCourseTotal1stYearFees'].'}';
						$urlComponents[] = 'fq='.$filterTag.'saCourseTotal1stYearFees:['.$feeString.']';
						//_p($urlComponents);die;
					}
                    break;
				
				case 'examScore':
					$examScoreValues = array();
					// get examName first
					$examName = reset($solrRequestData['qerFilters']['exams']);
					
                    if(is_array($solrRequestData['qerFilters']['examScore']) && count($solrRequestData['qerFilters']['examScore']) > 0) {
                        $examScoreValues = $solrRequestData['qerFilters']['examScore'];
                    }
                    foreach($examScoreValues as $key=>$id) {
						$examScoreValues[$key] = (int)$id;
                    }
					if(!empty($examScoreValues) && count($examScoreValues)>0)
					{
						$filterTag='{!tag=fexamscore}'; 
						$urlComponents[] = 'fq='.$filterTag.'sa'.$examName.'ExamScore:['.$examScoreValues[0].' TO '.$examScoreValues[1].']';
					}
                    break;
				
				case 'institute':
					$insFilterValues = array();
                    if(is_array($solrRequestData['qerFilters']['institute']) && count($solrRequestData['qerFilters']['institute']) > 0) {
                        $insFilterValues = $solrRequestData['qerFilters']['institute'];
                    }
                    
                    foreach($insFilterValues as $key=>$id) {
						$insFilterValues[$key] = (int)$id;
                    }
					if(!empty($insFilterValues) && count($insFilterValues)>0)
					{
						$filterTag='{!tag=f'.$this->solrDataFieldAliases['saDeptId'].'}';
						$urlComponents[] = 'fq='.$filterTag.'saDeptId:('.implode('%20OR%20',$insFilterValues).')';
					}
					break;	
                                        
                case 'workExperience':
                    $this->_getWorkExperienceFilterSolrCondtion($solrRequestData,$urlComponents);
                    break;
                case 'ugMarks':
                    $this->_getUgFilterSolrCondition($solrRequestData,$urlComponents);
                    break;
                case 'class12Marks':
                    $this->_getClass12FilterSolrCondition($solrRequestData,$urlComponents);
                    break;
                case 'applicationDeadline':
                    //$this->_getApplicationDeadlineFilterSolrCondition($solrRequestData,$urlComponents);
                    break;
                case 'intakeSeason':
                    //$this->_getIntakeSeasonFilterSolrCondition($solrRequestData,$urlComponents);
                    break;
                case 'scholarship':
                    $this->_getScholarShipFilterSolrCondition($solrRequestData,$urlComponents);
                    break;
                case 'sop':
                    if (!$sopOrLorFound) {
                        $this->_handleSOPAndLorFilterCondition($solrRequestData, $urlComponents);
                        $sopOrLorFound = 1;
                    }
                    break;
                case 'lor':
                      if (!$sopOrLorFound) { 
                        $this->_handleSOPAndLorFilterCondition($solrRequestData, $urlComponents);
                        $sopOrLorFound = 1;
                    }
                    break;
                case 'courseDuration':
                    $this->_getCourseDurationFilterSolrCondition($solrRequestData,$urlComponents);
                    break;
                
            }
        }
        if (!empty($specAndCondition) && is_array($specAndCondition) && count($specAndCondition) > 0) {

            $conditionString = "";
            $orCondition = array();
            foreach ($specAndCondition as $specSingleAndCond) {
                $andCond = array();
                foreach ($specSingleAndCond as $solrKey => $solrValue) {
                    $conditionString = $solrKey . ':' . urlencode($solrValue);
                    array_push($andCond, $conditionString);
                }
                $andConditionString = '(' . implode('%20AND%20', $andCond) . ')';
                array_push($orCondition, $andConditionString);
            }
            $orCondtionString = '(' . implode('%20OR%20', $orCondition) . ')';
            $cateComponents[]=$orCondtionString;
        }

        if(!empty($locationComponents)){
			$urlComponents[] = 'fq={!tag=floc}('.implode('%20OR%20',$locationComponents).')';
		}
		
		if(!empty($cateComponents)){
			$urlComponents[] = 'fq={!tag=fcat}('.implode('%20OR%20',$cateComponents).')';
		}
		

        return $urlComponents;
    }
    private function _getSpecNameFromFilterParam(&$solrRequestData, $specializationNameArray) {
        $specNameIdHash = array();
        $specIdNameHash = array();
      

        $specializationNameList = array();

        foreach ($specializationNameArray as $specilizationName) {
            $specializationHashKey=$specilizationName['SpecializationName'].':'.$specilizationName['subCatId'];
            $specNameIdHash[$specializationHashKey] = $specilizationName['specializationId'];
            $specIdNameHash[$specilizationName['specializationId']] = $specilizationName['SpecializationName'];
            array_push($specializationNameList, $specilizationName['SpecializationName']);
        }
        $solrRequestData['specNameIdHash'] = $specNameIdHash;
        $solrRequestData['specIdNameHash'] = $specIdNameHash;
        return $specializationNameList;
    }

    private function _normalizeLocationFilterUnsetWhichAreSame(&$solrRequestData) {
        $this->searchpagemodel = $this->CI->load->model('SASearch/sasearchmodel');
        $locationFiltersArray = array('city', 'state', 'country', 'continent');
        foreach ($locationFiltersArray as $locationFilter) {
            switch ($locationFilter) {
                case 'continent' :
                    break;
                case 'country' :
                    $this->_unsetParentForCountryFilter($solrRequestData);
                    break;
                case 'state' :
                    $this->_unsetParentForStateFilter($solrRequestData);
                     break;
                case 'city' :
                    $this->_unsetParentForCityFilter($solrRequestData);
                    break;
            }
        }
    }
    private function _unsetParentForStateFilter(&$solrRequestData) {
        $qerFilters = $solrRequestData['qerFilters'];
        $continentIdsArray = $qerFilters['continent'];
        $countryIdsArray = $qerFilters['country'];
        $stateIdsArray = $qerFilters['state'];
        // calculate continent id and country id corresponding to all states 
        $continentsPresent = isset($continentIdsArray) && count($continentIdsArray) > 0;
        $countriesPresent = isset($countryIdsArray) && count($countryIdsArray) > 0;
        if ($continentsPresent || $countriesPresent) {
            $continentAndCountryList = $this->searchpagemodel->getStateContinentCountryIdByCityStateID($stateIdsArray, 'state');
        } else {
            
        }
        foreach ($continentAndCountryList as $continentCountryId) {
            $countryId = $continentCountryId['countryId'];
            $continentId = $continentCountryId['continentId'];
            $stateId = $continentCountryId['stateId'];
            if ($continentsPresent) {
                $this->_removeValueFromArray($continentId, $solrRequestData['qerFilters']['continent']);
            }
            if ($countriesPresent) {
                $this->_removeValueFromArray($countryId, $solrRequestData['qerFilters']['country']);
            }
        }
    }

    private function _unsetParentForCityFilter(&$solrRequestData){
                    $qerFilters = $solrRequestData['qerFilters'];
                    $continentIdsArray = $qerFilters['continent'];
                    $countryIdsArray = $qerFilters['country'];
                    $stateIdsArray = $qerFilters['state'];
                    $cityIdsArray = $qerFilters['city'];
                    //calculate continent id , country id and state id corresponding to cities
                    $continentsPresent = isset($continentIdsArray) && count($continentIdsArray) > 0;
                    $countriesPresent = isset($countryIdsArray) && count($countryIdsArray) > 0;
                    $statesPresent = isset($stateIdsArray) && count($stateIdsArray) > 0;
                    if ($countriesPresent || $continentsPresent || $statesPresent) {
                        $continentCountryStateList = $this->searchpagemodel->getStateContinentCountryIdByCityStateID($cityIdsArray, 'city');
                        foreach ($continentCountryStateList as $continentCountryId) {
                            $countryId = $continentCountryId['countryId'];
                            $continentId = $continentCountryId['continentId'];
                            $stateId = $continentCountryId['stateId'];
                            if ($continentsPresent) {
                                $this->_removeValueFromArray($continentId, $solrRequestData['qerFilters']['continent']);
                            }
                            if ($countriesPresent) {
                                $this->_removeValueFromArray($countryId, $solrRequestData['qerFilters']['country']);
                            }
                            if ($statesPresent) {
                                $this->_removeValueFromArray($stateId, $solrRequestData['qerFilters']['state']);
                            }
                        }
                    }
                  
    }
    private function _unsetParentForCountryFilter(&$solrRequestData){
                    $qerFilters = $solrRequestData['qerFilters'];
                    $continentIdsArray = $qerFilters['continent'];
                    $countryIdsArray = $qerFilters['country'];
                    // get continent id
                    if (isset($continentIdsArray) && count($continentIdsArray) > 0) {
                        $continentIdCorrespondingToCountry = $this->searchpagemodel->getContinentIdByCountryId($countryIdsArray);
                        foreach ($continentIdCorrespondingToCountry as $continentId) {
                            $this->_removeValueFromArray($continentId['continentId'], $solrRequestData['qerFilters']['continent']);
                        }
                        //disable these continents from array
                    }
                  
    }

    private function _normalizeCatSubCatFilterUnsetWhichAreSame(&$solrRequestData,$subCatIdDetail) {
        $this->searchpagemodel = $this->CI->load->model('SASearch/sasearchmodel');
        $entityArray = array('specializationIds', 'subCategoryIds', 'categoryIds');
        foreach ($entityArray as $entityName) {
            switch ($entityName) {
                case 'specializationIds':
                    $specAndCondition=$this->_unsetParentForSpecializationFilter($solrRequestData,$subCatIdDetail);
                    // calculate category and subcat of specialization , if found remove them 
                    break;
                case 'subCategoryIds':
                    $this->_unsetParentForSubCatFilter($solrRequestData);
                    break;
                case 'categoryIds' :
                    break;
            }
        }
        return $specAndCondition;
    }

    private function _unsetParentForSubCatFilter(&$solrRequestData) {
        $qerFilters = $solrRequestData['qerFilters'];
        $categoryIdArray = $qerFilters['categoryIds'];
        $subCategoryArray = $qerFilters['subCategoryIds'];

        // calculate category id of subcategory array if same remove them
        $categoryIdPresent = (isset($categoryIdArray) && count($categoryIdArray) > 0);
        if ($categoryIdPresent) {
            $categoryIdList = $this->searchpagemodel->getCategoryIdBySubCatId($subCategoryArray);
            foreach ($categoryIdList as $categoryId) {
                $categoryIdPresent = $categoryId['catId'];
                $this->_removeValueFromArray($categoryIdPresent, $solrRequestData['qerFilters']['categoryIds']);
            }
        }
    }

    private function _unsetParentForSpecializationFilter(&$solrRequestData,$subCatAndCatId) {
        $qerFilters = $solrRequestData['qerFilters'];
        $specializationIdArray = $qerFilters['specializationIds'];
        $categoryIdArray = $qerFilters['categoryIds'];
        $subCategoryArray = $qerFilters['subCategoryIds'];
        $categoryIdPresent = (isset($categoryIdArray) && count($categoryIdArray) > 0);
        $subCatIdPresent = (isset($subCategoryArray) && count($subCategoryArray) > 0);
        $specAndCondition=array();
       
        
            foreach ($subCatAndCatId as $subCatCatDetails) {
                $subCatIdP = $subCatCatDetails['subCatId'];
                $categoryIdP = $subCatCatDetails['categoryId'];
                $specializationID=$subCatCatDetails['specializationId'];
                $specializationName=$subCatCatDetails['SpecializationName'];
               
                $andConditions=array();
                $andConditions['saCourseSpecializationName']='"'.ucwords(htmlentities($specializationName)).'"';
                $andConditions['saCourseParentCategoryId']=$categoryIdP;
                $andConditions['saCourseSubcategoryId']=$subCatIdP;
                if ($categoryIdPresent) {
                    $this->_removeValueFromArray($categoryIdP, $solrRequestData['qerFilters']['categoryIds']);
                }
                if ($subCatIdPresent) {
                    $this->_removeValueFromArray($subCatIdP, $solrRequestData['qerFilters']['subCategoryIds']);
                }
                array_push($specAndCondition, $andConditions);
            
        }
        return $specAndCondition;
    }

    private function _getLowestEntitySpecializationFilterInRequest($qerFilters){
        if (isset($qerFilters['specializationIds'])) {
            return 'specializationIds';
        }
        if (isset($qerFilters['subCategoryIds'])) {
            return 'subcategory';
        }
        if (isset($qerFilters['categoryIds'])) {
            return 'category';
        }
        return '';
    }

    private function _getIfAnyEntitySetInLocation($qerFilters) {
        if (isset($qerFilters['city'])) {
            return 'city';
        }
        if (isset($qerFilters['state'])) {
            return 'state';
        }
        if (isset($qerFilters['country'])) {
            return 'country';
        }
        if (isset($qerFilters['continent'])) {
            return 'continent';
        }
        return '';
    }
    private function _removeValueFromArray($valueToBeDeleted,&$inpArray){
       // _p($valueToBeDeleted);_p($inpArray);exit;
        if(($key = array_search($valueToBeDeleted, $inpArray)) !== false) {
            unset($inpArray[$key]);
        }
    }
private function _getCourseDurationFilterSolrCondition($solrRequestData,&$urlComponents){
        $courseDurationFilter=$solrRequestData['qerFilters']['courseDuration'];
        if(is_array($courseDurationFilter) && count($courseDurationFilter)>0){
            $urlComponents[]=  $this->_getSolrRangeQueryCondition('saCourseDurationValue', $courseDurationFilter);
        }
    }
        private function _handleSOPAndLorFilterCondition($solrRequestData, &$urlComponents) {
        if (isset($solrRequestData['qerFilters']['sop']) && isset($solrRequestData['qerFilters']['lor'])) {
            $sopFilter = $solrRequestData['qerFilters']['sop'];
            $lorFilter = $solrRequestData['qerFilters']['lor'];
            $urlComponents[] = 'fq=(saCourseSOPRequired:' . $sopFilter[0].' OR saCourseLORRequired:'.$lorFilter[0].')';
        } else if (isset($solrRequestData['qerFilters']['sop'])) {
            $sopFilter = $solrRequestData['qerFilters']['sop'];
            if (is_array($sopFilter) && count($sopFilter) > 0) {
                $urlComponents[] = 'fq=saCourseSOPRequired:' . $sopFilter[0];
            }
        } else if (isset($solrRequestData['qerFilters']['lor'])) {
            $lorFilter = $solrRequestData['qerFilters']['lor'];
            if (is_array($lorFilter) && count($lorFilter) > 0) {
                $urlComponents[] = 'fq=saCourseLORRequired:' . $lorFilter[0];
            }
        }
    }
    private function _getClass12FilterSolrCondition($solrRequestData,&$urlComponents){
        $class12Filter=$solrRequestData['qerFilters']['class12Marks'];
        if(is_array($class12Filter)&& count($class12Filter)>0){
            $urlComponents[]=  $this->_getSolrRangeQueryCondition('saCourse12thCutoff', $class12Filter);
        }
    }
    private function _getUgFilterSolrCondition($solrRequestData,&$urlComponents){
        $ugFilter=$solrRequestData['qerFilters']['ugMarks'];
        if(is_array($ugFilter) && count($ugFilter)>0){
            $urlComponents[]=  $this->_getSolrRangeQueryCondition('saCourseUgCutoffConverted', $ugFilter);
        }
    }

    private function _getIntakeSeasonFilterSolrCondition($solrRequestData, &$urlComponents) {
        $intakeSeason=$solrRequestData['qerFilters']['intakeSeason'];
        if(!empty($intakeSeason) ){
            $urlComponents[]='fq={!tag=f'.$this->solrDataFieldAliases['saCourseApplicationSubmissionIntake'].'}saCourseApplicationSubmissionIntake:"'.urlencode($intakeSeason).'"';
        }
    }

    private function _getApplicationDeadlineFilterSolrCondition($solrRequestData,&$urlComponents){
       
        if (!empty($solrRequestData['qerFilters']['applicationDeadline'])) {
            if (!strtotime($solrRequestData['qerFilters']['applicationDeadline'])) {
                return;
            }
            $applicationDeadline = $solrRequestData['qerFilters']['applicationDeadline'] . "T00:00:00Z";
            $urlComponents[] = 'fq={!tag=f'.$this->solrDataFieldAliases['saCourseApplicationSubmissionDeadline'].'}saCourseApplicationSubmissionDeadline:[* TO ' . $applicationDeadline . ']';
        }
    }
    private function _getWorkExperienceFilterSolrCondtion($solrRequestData,&$urlComponents){
        $workExperience=$solrRequestData['qerFilters']['workExperience'];
        if(is_array($workExperience) && count($workExperience)>0){
            $urlComponents[]=  $this->_getSolrRangeQueryCondition('saCourseWorkXP', $workExperience);
            $urlComponents[]='fq={!tag=f'.$this->solrDataFieldAliases['saCourseWorkXPRequired'].'}saCourseWorkXPRequired:Yes' ;
        }
      
    }
    private function _getSolrRangeQueryCondition($solrFieldName,$solrFieldValues){
        if(count($solrFieldValues)==0){
            return '';
        }
        if(count($solrFieldValues)==1){
            $solrString=$solrFieldValues[0]." TO *";
        }else{
            $minEleVal=min($solrFieldValues[0],$solrFieldValues[1]);
            $maxEleVal=max($solrFieldValues[0],$solrFieldValues[1]);
            $solrString=$minEleVal." TO ".$maxEleVal;
        }
        //$solrUrl='fq='.$solrFieldName.':['.$solrString.']';
		$solrFieldNameFilter = $solrFieldName."Filter"; // because range query is done on a separate "Filter"suffixed field that is float, unlike the other one. 
        $solrUrl='fq={!tag=f'.$this->solrDataFieldAliases[$solrFieldName].'}'.$solrFieldNameFilter.':['.$solrString.']';
        return $solrUrl;
    }
    private function _getScholarShipFilterSolrCondition($solrRequestData, &$urlComponents) {
        $scholarShip=$solrRequestData['qerFilters']['scholarship'];
        if(is_array($scholarShip)&& count($scholarShip)>0){
            if(count($scholarShip)==1){
                $urlComponents[]='fq={!tag=f'.$this->solrDataFieldAliases['saCourseScholarship'].'}saCourseScholarship:"'.urlencode($scholarShip[0]).'"';
            }
        }
    }
    private function getFieldListSortingComponentsAndQueryFields($solrRequestData) {
        $urlComponents = array();

        //aliasing fl fields
        
        $solrDataFiledAliases = $this->CI->config->item('SolrDataFieldAliases');
        $fieldsToFetch = array('saCourseId','saUnivId','saCourseSeoUrl','saUnivSeoUrl','saUnivName','saUnivCountryName','saUnivStateName','saUnivCityName','saUnivType','saCourseName','saCourseDurationValue','saCourseDurationUnit','saCourseRank','saCourseRankName','saCourseRankURL','saCourseTotal1stYearFees','saCourseEligibilityExams','saCourse12thCutoff','saCourseUgCutoff','saCourseUgCutoffUnit','saCourseWorkXP','saCourseLevel1','saUnivLogoLink','saCourseDesiredCourseId','saCourseWorkXPRequired','saCourseParentCategoryId','saUnivEstablishyear','saUnivLivingExpense','saUnivRanking','saUnivRankingName','saUnivRankingURL','saCourseInventoryType',
        'saCourseSubcategoryId');
        $this->_addExamScoreFieldUrlComponents($fieldsToFetch);
        foreach($fieldsToFetch as $fields) {
            if($solrDataFiledAliases[$fields]) {
                $fieldsToFetchWithAlias[] = $solrDataFiledAliases[$fields].":".$fields;
            } else {
                $fieldsToFetchWithAlias[] = $fields;
            }
        }
		if($solrRequestData['filterUpdateCallFlag']!=1){
			$urlComponents[] = 'fl='.implode(',',$fieldsToFetchWithAlias);
		}
        if($solrRequestData['filterWithDataFlag']==1){
            $urlComponents[] = 'fl='.implode(',',$fieldsToFetchWithAlias);
        }

        $urlComponents[] = 'group.limit=100'; //get all documents within an university
        
        //get fields for qf with boosting
        $queryFields = $this->getqueryFields(0);
        $urlComponents[] = 'qf='.implode('+', $queryFields);

		$this->_getSortingComponent($solrRequestData,$urlComponents);
        
        return $urlComponents;
    }

    private function _getSortingComponent($solrRequestData,&$urlComponents){
        
            $sortingArr = explode('_', $solrRequestData['sortingParam']);
            $order = (strtoupper($sortingArr[1])=='ASC')?'asc':'desc';
            switch ($sortingArr[0]) {
                case 'exam':
                    //CAE has values A,B,C so we need reverse sorting here
                    if($sortingArr[2]=='cae'){
                        $order = (strtoupper($sortingArr[1])=='ASC')?'desc':'asc';
                    }

                    if($sortingArr[2]!=''){
					$urlComponents[] = 'sort=sa'.strtoupper($sortingArr[2]).ucfirst($order).'ExamScore+'.$order; 
                    }else{
                    //its just for if someone temper with values    
					$urlComponents[] = 'sort=saTOEFLDescExamScore+desc';
                    }
                    break;
                case 'fees':
                    //$urlComponents[] = 'sort=saCourseTotal1stYearFees+'.$order;
		    $urlComponents[] = 'sort=saCourseTotalFees+'.$order;
                        
                    break;
                default:
                   $urlComponents[] = 'sort=saUnivPopularityIndex+desc';
                   $urlComponents[] = 'group.sort=saCoursePopularityIndex+desc';
                    break;
            }
    }
	
	private function getqueryFields($addBoost = 0) {
        $queryFields = array();
        
        //institute title
        if($addBoost) {
            $queryFields[] = 'saUnivName^50'; //with synonyms.txt in solr
            $queryFields[] = 'saUnivAcronym^5';

            //course title
            $queryFields[] = 'saCourseName^5';
            $queryFields[] = 'saCourseDescription^5';
        } else {
            $queryFields[] = 'saUnivName';
            $queryFields[] = 'saDeptName';

            //course title
            $queryFields[] = 'saCourseName';
            //$queryFields[] = 'saCourseDescription';
        }
        return $queryFields;
    }

    private function getQueryTerm(&$solrRequestData) {
      
        $QERkeyword = $this->cleanKeyword($solrRequestData['remainingKeyword']);
        if($solrRequestData['textSearchFlag'] && $QERkeyword!=''){
		// fix for accented characters in query term
            $QERkeyword = iconv("ISO-8859-1","UTF-8", $QERkeyword);
            $urlQueryTerm = 'q="'.urlencode($QERkeyword).'"';
        }else{
            $urlQueryTerm = 'q=*:*';
    		$solrRequestData['isQEmpty'] = true; // set flag if q is already *:* in first chance itself
        }   
        return $urlQueryTerm;
    }

    private function cleanKeyword($keyword) {
        $stopWords = array();

        $keyword = trim(str_replace($stopWords, "", $keyword));
        $keyword = preg_replace('/[0-9]+/', '', $keyword);
        $keyword = preg_replace("/\./", "", $keyword);

        return $keyword;
    }
    private function getInitialFacetComponent()
    {
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.mincount=1';
        $urlComponents[] = 'facet.limit=-1';
        $urlComponents[] = 'facet.sort=true';
        $urlComponents[] = 'facet.field=saCourseEligibilityExamsIdMap';
        return $urlComponents;
    }
    

    private function getFacetComponents($solrRequestData,$searchType = 'course') {
        $SA_SEARCH_FILTER_FACETS = $this->CI->config->item('SA_SEARCH_FILTER_FACETS');
        $facets = $SA_SEARCH_FILTER_FACETS[$searchType];  
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.mincount=1';
        $urlComponents[] = 'facet.limit=-1';
        $urlComponents[] = 'facet.sort=true';
        $taggedFilters = 'f'.$this->solrDataFieldAliases['saCourseTotal1stYearFees'].',f'.$this->solrDataFieldAliases['saCourseEligibilityExams'].',f'.$this->solrDataFieldAliases['saCourseLevel1'].',f'.$this->solrDataFieldAliases['saCourseDurationValue']
                //.',f'.$this->solrDataFieldAliases['saCourseApplicationSubmissionDeadline'].',f'.$this->solrDataFieldAliases['saCourseApplicationSubmissionIntake']
                .',f'.$this->solrDataFieldAliases['saCourseShikshaApply'].','
                . 'f'.$this->solrDataFieldAliases['saCourseSOPRequired'].',f'.$this->solrDataFieldAliases['saCourseLORRequired'].',f'.$this->solrDataFieldAliases['saCourse12thCutoff'].',f'.$this->solrDataFieldAliases['saCourseUgCutoffConverted'].',f'.$this->solrDataFieldAliases['saCoursePgCutoff'].','
                . 'f'.$this->solrDataFieldAliases['saCourseWorkXP'].',f'.$this->solrDataFieldAliases['saCourseWorkXPRequired'].',f'.$this->solrDataFieldAliases['saCourseScholarship'].',f'.$this->solrDataFieldAliases['saUnivType2'].',f'.$this->solrDataFieldAliases['saUnivType'].',f'.$this->solrDataFieldAliases['saUnivLivingExpense'].','
                . 'f'.$this->solrDataFieldAliases['saUnivAccommodationAvailable'].',f'.$this->solrDataFieldAliases['saIELTSExamScore'].',f'.$this->solrDataFieldAliases['saTOEFLExamScore'].',f'.$this->solrDataFieldAliases['saCAELExamScore'].',f'.$this->solrDataFieldAliases['saMELABExamScore'].','
                . 'f'.$this->solrDataFieldAliases['saGREExamScore'].',f'.$this->solrDataFieldAliases['saGMATExamScore'].',f'.$this->solrDataFieldAliases['saPTEExamScore'].',f'.$this->solrDataFieldAliases['saSATExamScore'].',f'.$this->solrDataFieldAliases['saCAEExamScore'].','
                . 'fcat,floc,f'.$this->solrDataFieldAliases['saCourseDesiredCourseId'];//.',f'.$this->solrDataFieldAliases['saCourseSpecializationName']; name is not used in query anymore
            foreach ($facets as $facetName) {
                switch ($facetName) {
					
					case 'fees':
						/*
						 * Notice that there are 2 fields for fees: saCourseFees & saCourseTotal1stYearFees
						 * In solr schema, saCourseFees is a string field used primarily for facets, which saCourseTotal1stYearFees 
						 * works for querying & sorting only. refer changeset : 93581 to see wherever saCourseFees is used for faceting purposes.
						 * In the code below, we have used aliases for saCourseTotal1stYearFees but actual facet is again made on saCourseFees 
						 */
                        $courseFeePrefixQuery = '{!ex=f'.$this->solrDataFieldAliases['saCourseTotal1stYearFees'].'}';
                        $urlComponents[] = 'facet.field='.$courseFeePrefixQuery.'saCourseFees';
						$urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseFees_parent}saCourseFees';
                        break;
					
                    case 'location':
                        $continentPrefixQuery = "{!ex=floc}";
                        $countryPrefixQuery = "{!ex=floc}";
                        $statePrefixQuery = "{!ex=floc}";
                        $cityPrefixQuery = "{!ex=floc}";

                        $urlComponents[] = 'facet.field='.$continentPrefixQuery.'saUnivContinentId';
                        $urlComponents[] = 'facet.field='.$countryPrefixQuery.'saUnivCountryId';
                        $urlComponents[] = 'facet.field='.$statePrefixQuery.'saUnivStateId';
                        $urlComponents[] = 'facet.field='.$cityPrefixQuery.'saUnivCityId';
                        
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivContinentId_parent}saUnivContinentId';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivCountryId_parent}saUnivCountryId';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivStateId_parent}saUnivStateId';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivCityId_parent}saUnivCityId';
                        
                        break;
                    		case 'category':
                        $saCourseDesiredCourseIdPrefixQuery = "{!ex=fcat}";
                        $saCourseParentPrefixQuery = "{!ex=fcat}";
                        $saCourseSubcategoryIdQuery = "{!ex=fcat}";
                       
                        
                        $urlComponents[] = 'facet.field=' . $saCourseDesiredCourseIdPrefixQuery . 'saCourseDesiredCourseId';
                        $urlComponents[] = 'facet.field=' . $saCourseParentPrefixQuery . 'saCourseParentCategoryId';
                        $urlComponents[] = 'facet.field=' . $saCourseSubcategoryIdQuery . 'saCourseSubcategoryId';
                        
                        
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseDesiredCourseId_parent}saCourseDesiredCourseId';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseParentCategoryId_parent}saCourseParentCategoryId';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseSubcategoryId_parent}saCourseSubcategoryId';
               
                       break;
			
                    case 'exams':
                        $examsPrefixQuery = '{!ex=f'.$this->solrDataFieldAliases['saCourseEligibilityExams'].'}';
                        $urlComponents[] = 'facet.field='.$examsPrefixQuery.'saCourseEligibilityExamsIdMap';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseEligibilityExamsIdMap_parent}saCourseEligibilityExamsIdMap';
                        $this->_addExamScoreFacetUrlComponents($urlComponents);
                        break;
                    
                    case 'specialization':
                        $specializationPrefixQuery = "{!ex=fcat}";
                        $urlComponents[] = 'facet.field='.$specializationPrefixQuery.'saCourseSpecializationNameSubcatIdMap';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseSpecializationNameSubcatIdMap_parent}saCourseSpecializationNameSubcatIdMap';
                        break;
                    
                    case 'courselevel':
                        $courseLevelPrefixQuery = '{!ex=f'.$this->solrDataFieldAliases['saCourseLevel1'].'}';
                        $urlComponents[] = 'facet.field='.$courseLevelPrefixQuery.'saCourseLevel1';
					    $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseLevel1_parent}saCourseLevel1';
                        break;
                    
					
					case 'duration':
                        $courseDurationPrefixQuery = '{!ex=f'.$this->solrDataFieldAliases['saCourseDurationValue'].'}';
                        $urlComponents[] = 'facet.field='.$courseDurationPrefixQuery.'saCourseDurationValue';
						$urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseDurationValue_parent}saCourseDurationValue';

					break;
					
					case 'deadline':
                        // $deadlinePrefixQuery = '{!ex=f'.$this->solrDataFieldAliases['saCourseApplicationSubmissionDeadline'].'}';
						// $urlComponents[] = 'facet.field='.$deadlinePrefixQuery.'saCourseApplicationSubmissionDeadline';
                        // $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseApplicationSubmissionDeadline_parent}saCourseApplicationSubmissionDeadline';
					break;
					
					case 'intakeseason':
                        // $intakePrefixQuery = '{!ex=f'.$this->solrDataFieldAliases['saCourseApplicationSubmissionIntake'].'}';
                        // $urlComponents[] = 'facet.field='.$intakePrefixQuery.'saCourseApplicationSubmissionIntake';
                        // $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseApplicationSubmissionIntake_parent}saCourseApplicationSubmissionIntake';
					break;
					
					case 'shikshapply':
                        $shikshaApplyPrefixQuery = '{!ex=f'.$this->solrDataFieldAliases['saCourseShikshaApply'].'}';
                        $SOPPrefixQuery = '{!ex=f'.$this->solrDataFieldAliases['saCourseSOPRequired'].'}';
                        $LORPrefixQuery = '{!ex=f'.$this->solrDataFieldAliases['saCourseLORRequired'].'}';
                        $xiiPrefixQuery 	= '{!ex=f'.$this->solrDataFieldAliases['saCourse12thCutoff'].'}';
                        $ugPrefixQuery 	= '{!ex=f'.$this->solrDataFieldAliases['saCourseUgCutoffConverted'].'}';
                        $pgPrefixQuery 	= '{!ex=f'.$this->solrDataFieldAliases['saCoursePgCutoff'].'}';
                        $xpPrefixQuery 	= '{!ex=f'.$this->solrDataFieldAliases['saCourseWorkXP'].'}';
                        $xpReqPrefixQuery  = '{!ex=f'.$this->solrDataFieldAliases['saCourseWorkXPRequired'].'}';
                        
						$urlComponents[] = 'facet.field='.$shikshaApplyPrefixQuery.'saCourseShikshaApply';
						$urlComponents[] = 'facet.field='.$SOPPrefixQuery .'saCourseSOPRequired';
						$urlComponents[] = 'facet.field='.$LORPrefixQuery .'saCourseLORRequired';
						$urlComponents[] = 'facet.field='.$xiiPrefixQuery 	.'saCourse12thCutoff';
						$urlComponents[] = 'facet.field='.$ugPrefixQuery 	.'saCourseUgCutoffConverted';
						$urlComponents[] = 'facet.field='.$pgPrefixQuery 	.'saCoursePgCutoff';
						$urlComponents[] = 'facet.field='.$xpPrefixQuery 	.'saCourseWorkXP';
                        $urlComponents[] = 'facet.field='.$xpReqPrefixQuery .'saCourseWorkXPRequired';

                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseShikshaApply_parent}saCourseShikshaApply';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseSOPRequired_parent}saCourseSOPRequired';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseLORRequired_parent}saCourseLORRequired';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourse12thCutoff_parent}saCourse12thCutoff';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseUgCutoffConverted_parent}saCourseUgCutoffConverted';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCoursePgCutoff_parent}saCoursePgCutoff';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseWorkXP_parent}saCourseWorkXP';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseWorkXPRequired_parent}saCourseWorkXPRequired';
					break;

                    case 'scholarship':
                        $scholarshipPrefixQuery 	= '{!ex=f'.$this->solrDataFieldAliases['saCourseScholarship'].'}';
                        $urlComponents[] = 'facet.field='.$scholarshipPrefixQuery.'saCourseScholarship';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseScholarship_parent}saCourseScholarship';
                    break;
                    
                    case 'type':
                        $univType2PrefixQuery  	= '{!ex=f'.$this->solrDataFieldAliases['saUnivType2'].'}';
                        $urlComponents[] = 'facet.field='.$univType2PrefixQuery .'saUnivType2';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivType2_parent}saUnivType2';
                        $univTypePrefixQuery 	= '{!ex=f'.$this->solrDataFieldAliases['saUnivType'].'}';
                        $urlComponents[] = 'facet.field='.$univTypePrefixQuery .'saUnivType';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivType_parent}saUnivType';
                    break;
                    
                    case 'livingexpense':
                        $livExpPrefixQuery 	= '{!ex=f'.$this->solrDataFieldAliases['saUnivLivingExpense'].'}';
                        $urlComponents[] = 'facet.field='.$livExpPrefixQuery.'saUnivLivingExpense';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivLivingExpense_parent}saUnivLivingExpense';
                    break;
                    
                    case 'desiredcourse':
                        $saCourseDesiredCourseIdPrefixQuery = '{!ex=f'.$this->solrDataFieldAliases['saCourseDesiredCourseId'].'}';
                        $urlComponents[] = 'facet.field='.$saCourseDesiredCourseIdPrefixQuery.'saCourseDesiredCourseId';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseDesiredCourseId_parent}saCourseDesiredCourseId';
                    break;
                    
                    case 'accomodation':
                        $accoPrefixQuery 	= '{!ex=f'.$this->solrDataFieldAliases['saUnivAccommodationAvailable'].'}';
                        $urlComponents[] = 'facet.field='.$accoPrefixQuery.'saUnivAccommodationAvailable';
                        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivAccommodationAvailable_parent}saUnivAccommodationAvailable';
                    break;
            }
        }
        return $urlComponents;
    }
	/*
	 * to generate autosuggestor solr query url: univs 
	 */
	public function generateUnivAutoSuggestionUrl($solrRequestData) {
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';
        $urlComponents[] = 'fq=facetype:saUnivAutosuggestor';

		$urlComponents[] = 'q="'.urlencode(htmlspecialchars(strip_tags(str_replace('&',' and ',$solrRequestData['text'])))).'"';
        
        $urlComponents[] = 'qf=saAutosuggestUnivNameEdgeNGram+'.//^2000+'.
										'saAutosuggestUnivNameKeywordEdgeNGram+'.//^50+'.
										'saAutosuggestUnivNameAutosuggest+'.//^30+'.
										'saAutosuggestUnivSynonymAutosuggest+'.//^1000+'.
										'saAutosuggestUnivAccronymAutosuggest+'.//^1000+'.
										'saAutosuggestUnivSynonymKeywordEdgeNGram+'.//^100+'.
										'saAutosuggestUnivAccronymKeywordEdgeNGram+'.//^100+'.
										'saAutosuggestUnivSynonymSpKeywordEdgeNGram+'.//^100+'.
										'saAutosuggestUnivAccronymSpKeywordEdgeNGram+'//^100'
										;

        $fieldsToFetch = array('saAutosuggestUnivNameFacet', 'saAutosuggestUnivId');
        
        $urlComponents[] = 'fl='.implode(',',$fieldsToFetch);
        
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.main=true';
        $urlComponents[] = 'group.field=saAutosuggestUnivId';

        $urlComponents[] = 'sort=saAutosuggestUnivViewCount%20DESC';
        
		$urlComponents[] = 'rows='.$solrRequestData['eachfacetResultCount'];
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        return $solrUrl;
    }
	/*
	 * to generate autosuggestor solr query url for course
	 */
	public function generateCourseAutoSuggestionUrl($solrRequestData)
	{
		$urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';
        $urlComponents[] = 'fq=facetype:saCourseAutosuggestor';
        $urlComponents[] = 'q="'.urlencode(htmlspecialchars(strip_tags(str_replace('&',' and ',$solrRequestData['text'])))).'"';
        
		$urlComponents[] = 'qf=saAutosuggestDesiredCourseSpecializationNameEdgeNGram+'.//^500+'.
										'saAutosuggestDesiredCourseSpecializationNameKeywordEdgeNGram+'.
										'saAutosuggestDesiredCourseSubcategoryNameEdgeNGram+'.//^500+'.
										'saAutosuggestDesiredCourseSubcategoryNameKeywordEdgeNGram+'.
										'saAutosuggestCourseLevelSpecializationNameEdgeNGram+'.//^500+'.
										'saAutosuggestCourseLevelSpecializationNameKeywordEdgeNGram+'.
										'saAutosuggestCourseLevelSubcategoryNameEdgeNGram+'.//^500+'.
										'saAutosuggestCourseLevelSubcategoryNameKeywordEdgeNGram+'.
										'saAutosuggestCourseLevelCategoryNameEdgeNGram+'.//^500+'.
										'saAutosuggestCourseLevelCategoryNameKeywordEdgeNGram+'.
										'saAutosuggestSynonymEdgeNGram+'.//^500+'.
										'saAutosuggestSynonymKeywordNGram'.
										'saAutosuggestDesiredCourseNameEdgeNGram+'.//^500+'.
										'saAutosuggestDesiredCourseNameKeywordEdgeNGram';

        $fieldsToFetch = array('saAutosuggestCourseFacet','saAutosuggestDesiredCourseSubcategoryNameIdMap','saAutosuggestDesiredCourseSpecializationNameIdMap','saAutosuggestCourseLevelSpecializationNameIdMap','saAutosuggestCourseLevelSubcategoryNameIdMap','saAutosuggestCourseLevelCategoryNameIdMap','saAutosuggestDesiredCourseNameIdMap');
        
        $urlComponents[] = 'fl='.implode(',',$fieldsToFetch);
        $urlComponents[] = 'group=true';
        //$urlComponents[] = 'group.main=true'; // without this we will see the numFound too
        $urlComponents[] = 'group.field=saAutosuggestCourseFacet';
		$urlComponents[] = 'sort=saAutosuggestCourseCount%20DESC';

        $urlComponents[] = 'rows='.$solrRequestData['eachfacetResultCount'];
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        return $solrUrl;
	}
	/*
	 * to generate autosuggestor solr query url for country, city state
	 */
	public function generateLocationAutoSuggestionUrl($solrRequestData)
	{
		$urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';
        $urlComponents[] = 'fq=facetype:saLocationAutosuggestor';
		if(!is_null($solrRequestData['courseFilter']) &&$solrRequestData['courseFilter']['type']=="course")
		{
			foreach($solrRequestData['courseFilter'] as $key=>$value)
			{
				switch($key)
				{
					case 'courseLevel':
							$urlComponents[] = 'fq=saCourseLevel1:"'.urlencode($value).'"';
							break;
					case 'specializationId':
							$urlComponents[] = 'fq=saAutosuggestSpecializationId:'.$value;
							break;
					case 'subcategoryId':
							$urlComponents[] = 'fq=saAutosuggestSubcategoryId:'.$value;
							break;
					case 'categoryId':
							$urlComponents[] = 'fq=saAutosuggestCategoryId:'.$value;
							break;
					case 'desiredCourse':
							$urlComponents[] = 'fq=saAutosuggestDesiredCourseId:'.$value;
							break;
				}
			}
        }
        if(is_array($solrRequestData['locationIds']) && count($solrRequestData['locationIds'])>0)
        {
            $urlComponents[] = 'fq=(-saAutosuggestLocationId:('.urlencode(implode(' ',$solrRequestData['locationIds'])).'))';
        }
        
        $urlComponents[] = 'q="'.urlencode($solrRequestData['text']).'"';
		$urlComponents[] = 'qf=saAutosuggestCountryNameSynonyms^250+'.
                                'saAutosuggestCountryNameEdgeNGram^200+'.
                                'saAutosuggestCityNameSynonyms^150+'.
                                'saAutosuggestCityNameEdgeNGram^100+'.
								'saAutosuggestStateNameSynonyms^75+'.
                                'saAutosuggestStateNameEdgeNGram^50+'.
                                'saAutosuggestContinentNameSynonyms^35+'.
								'saAutosuggestContinentNameEdgeNGram^20';

        $fieldsToFetch = array('saAutosuggestLocationFacet','saAutosuggestCityNameIdMap','saAutosuggestStateNameIdMap','saAutosuggestCountryNameIdMap','saAutosuggestContinentNameIdMap','saAutosuggestUnivId');
        
        $urlComponents[] = 'fl='.implode(',',$fieldsToFetch);
        
        $urlComponents[] = 'group=true';
        //$urlComponents[] = 'group.main=true'; // without this we will see the numFound too
        $urlComponents[] = 'group.field=saAutosuggestLocationFacet';
        
		$urlComponents[] = 'sort=score%20DESC';

        $urlComponents[] = 'rows=50';//.$solrRequestData['eachfacetResultCount'];
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        return $solrUrl;
	}
	
	/*
	 * to generate autosuggestor solr query url for exams
	 */
	public function generateExamAutoSuggestionUrl($solrRequestData)
	{
		$urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';
        $urlComponents[] = 'fq=facetype:saExamAutosuggestor';

        $urlComponents[] = 'q="'.urlencode($solrRequestData['text']).'"';
        
		$urlComponents[] = 'qf=saAutosuggestExamNameEdgeNGram';

        $fieldsToFetch = array('saAutosuggestExamName','saAutosuggestExamNameIdMap');
        
        $urlComponents[] = 'fl='.implode(',',$fieldsToFetch);
        
        $urlComponents[] = 'group=true';
        //$urlComponents[] = 'group.main=true'; // without this we will see the numFound too
        $urlComponents[] = 'group.field=saAutosuggestExamName';
        
		$urlComponents[] = 'sort=score%20DESC';

        $urlComponents[] = 'rows='.$solrRequestData['eachfacetResultCount'];
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        return $solrUrl;
	}

    public function getSelectedUniversityDetailsUrl($universityId){
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'fq=facetype:abroadlisting';
        $urlComponents[] = 'fq=saUnivId:'.$universityId;
        $fieldsToFetch = array('saUnivCountryId','saUnivCountryName','saCourseLevel1','saCourseParentCategoryId','saCourseParentCategoryName','saUnivCityId','saUnivCityName','saUnivSeoUrl');
        $urlComponents[] = 'fl='.implode(',',$fieldsToFetch);
        $urlComponents[] = 'rows=10000';
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        return $solrUrl;
    }

    public function getSelectedCourseDetailsUrl($courseData, $locationData = array()){
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'fq=facetype:abroadlisting';
		// add course filter in url components
		$this->_addCourseFilterQueryInSolrUrl($urlComponents,$courseData);
		// add location filter in url components
		if(count($locationData)>0){
			$this->_addLocationFilterQueryInSolrUrl($urlComponents,$locationData);
		}
        $urlComponents[] = 'fl=saCourseId';
        $urlComponents[] = 'rows=0';
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.mincount=1';
        $urlComponents[] = 'facet.limit=-1';
        $urlComponents[] = 'facet.sort=true';
        $urlComponents[] = 'facet.field=saCourseFees';
        $urlComponents[] = 'facet.field=saCourseEligibilityExamsIdMap';
        $this->_addExamScoreFacetUrlComponents($urlComponents);
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
		
        return $solrUrl;
    }
	private function _addCourseFilterQueryInSolrUrl(& $urlComponents, $courseData){
		 if($courseData['saAutosuggestDesiredCourseSubcategoryNameIdMap']){
			$nameIdMap = explode(':',$courseData['saAutosuggestDesiredCourseSubcategoryNameIdMap']);
            $urlComponents[] = 'fq=saCourseSubcategoryId:'.end($nameIdMap);
            $urlComponents[] = 'fq=saCourseDesiredCourseId:'.reset($nameIdMap);
        }else if($courseData['saAutosuggestDesiredCourseSpecializationNameIdMap']){
			$nameIdMap = explode(':',$courseData['saAutosuggestDesiredCourseSpecializationNameIdMap']);
            $urlComponents[] = 'fq=saCourseSpecializationId:'.end($nameIdMap);
            $urlComponents[] = 'fq=saCourseDesiredCourseId:'.reset($nameIdMap);
        }else if($courseData['saAutosuggestCourseLevelSpecializationNameIdMap']){
			$nameIdMap = explode(':',$courseData['saAutosuggestCourseLevelSpecializationNameIdMap']);
            $urlComponents[] = 'fq=saCourseSpecializationId:'.end($nameIdMap);
            $urlComponents[] = 'fq=saCourseLevel1:"'.urlencode(reset($nameIdMap)).'"';
        }else if($courseData['saAutosuggestCourseLevelSubcategoryNameIdMap']){
			$nameIdMap = explode(':',$courseData['saAutosuggestCourseLevelSubcategoryNameIdMap']);
            $urlComponents[] = 'fq=saCourseSubcategoryId:'.end($nameIdMap);
            $urlComponents[] = 'fq=saCourseLevel1:"'.urlencode(reset($nameIdMap)).'"';
        }else if($courseData['saAutosuggestCourseLevelCategoryNameIdMap']){
			$nameIdMap = explode(':',$courseData['saAutosuggestCourseLevelCategoryNameIdMap']);
            $urlComponents[] = 'fq=saCourseParentCategoryId:'.end($nameIdMap);
            $urlComponents[] = 'fq=saCourseLevel1:"'.urlencode(reset($nameIdMap)).'"';
        }else if($courseData['saAutosuggestDesiredCourseNameIdMap']){
			$nameIdMap = explode(':',$courseData['saAutosuggestDesiredCourseNameIdMap']);
            $urlComponents[] = 'fq=saCourseDesiredCourseId:'.end($nameIdMap);
        }
		// when location is selected via autosuggestor, course fields are available directly
		else{
			$this->_addRefinedCourseFilterQueryInSolrUrl($urlComponents,$courseData);
			
		}
	}
	private function _addRefinedCourseFilterQueryInSolrUrl(& $urlComponents,$courseData){
		if($courseData['courseLevel']){
			$urlComponents[] = 'fq=saCourseLevel1:"'.urlencode($courseData['courseLevel']).'"';
		}
		if($courseData['categoryId']){
			$urlComponents[] = 'fq=saCourseParentCategoryId:'.$courseData['categoryId'];
		}
		if($courseData['subcategoryId']){
			$urlComponents[] = 'fq=saCourseSubcategoryId:'.$courseData['subcategoryId'];
		}
		if($courseData['desiredCourse']){
			$urlComponents[] = 'fq=saCourseDesiredCourseId:'.$courseData['desiredCourse'];
		}
		if($courseData['specializationId']){
			$urlComponents[] = 'fq=saCourseSpecializationId:'.$courseData['specializationId'];
		}
	}
	private function _addLocationFilterQueryInSolrUrl(& $urlComponents, $locationData){
		$skipData = $this->_removeLocationParents($locationData);
		$locationQueryString = "";
		foreach($locationData as $locData){
			switch($locData['locType']){
				case "city":
					$locationQueryString .= ($locationQueryString==""?"":"+OR+").'saUnivCityId:'.$locData['locId'];
					break;
				case "country":
					if(in_array($locData['locId'],$skipData['skipCountry'])=== false)
					{
						$locationQueryString .= ($locationQueryString==""?"":"+OR+").'saUnivCountryId:'.$locData['locId'];
					}
					break;
				case "state":
					if(in_array($locData['locId'],$skipData['skipState'])=== false)
					{
						$locationQueryString .= ($locationQueryString==""?"":"+OR+").'saUnivStateId:'.$locData['locId'];
					}
				break;
				case "continent":
					if(in_array($locData['locId'],$skipData['skipContinent'])=== false)
					{
						$locationQueryString .= ($locationQueryString==""?"":"+OR+").'saUnivContinentId:'.$locData['locId'];
					}
					break;
			}
		}
		$urlComponents[] = "fq=".$locationQueryString;
	}
	private function _removeLocationParents(& $locationData)
	{
		$this->searchPageModel = $this->CI->load->model('SASearch/sasearchmodel');
		$skipState = $skipCountry = $skipContinent = array();
		$cityIdsArray = array_filter(array_map(function($a){if($a['locType']=="city"){return $a['locId'];}},$locationData));
		$res1 = $this->searchPageModel->getStateContinentCountryIdByCityStateID($cityIdsArray, 'city');
		foreach($res1 as $row)
		{
			if($row['stateId']>0)
			{
				$skipState[] = $row['stateId'];
			}else if($row['countryId']>0)
			{
				$skipCountry[] = $row['countryId'];
			}else if($row['continentId']>0)
			{
				$skipContinent[] = $row['continentId'];
			}
		}
		$stateIdsArray = array_filter(array_map(function($a){if($a['locType']=="state"){return $a['locId'];}},$locationData));
		$res2 = $this->searchPageModel->getStateContinentCountryIdByCityStateID($stateIdsArray, 'state');
		foreach($res2 as $row)
		{
			if($row['countryId']>0)
			{
				$skipCountry[] = $row['countryId'];
			}else if($row['continentId']>0)
			{
				$skipContinent[] = $row['continentId'];
			}
		}
		return array('skipState'=>$skipState,'skipCountry'=>$skipCountry,'skipContinent'=>$skipContinent);
	}
    private function _addExamScoreFacetUrlComponents(& $urlComponents){
        $lib = $this->CI->load->library('listing/AbroadListingCommonLib');
        $exams = $lib->getAbroadExamsMasterListFromCache();
        foreach($exams as $exam){
            $urlComponents[] = 'facet.field={!ex=fexamscore}sa'.$exam['exam'].'StrExamScore';
        }
    }

    private function _addExamScoreFieldUrlComponents(&$fieldsToFetch){
        $lib = $this->CI->load->library('listing/AbroadListingCommonLib');
        $exams = $lib->getAbroadExamsMasterListFromCache();
        foreach($exams as $exam){
            $fieldsToFetch[] = 'sa'.$exam['exam'].'ExamScore';
        }
    }
	/*
	 * to generate list of courses in a university
	 */
	public function generateUnivCourseListUrl($solrRequestData) {
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'q=*%3A*';
        $urlComponents[] = 'fq=facetype:abroadlisting';
        $urlComponents[] = 'fq=saUnivId:'.$solrRequestData['universityId'];
        $fieldsToFetch = array('saCourseId', 'saCourseName');
        $urlComponents[] = 'fl='.implode(',',$fieldsToFetch);
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        return $solrUrl;
    }

    //to generate list of popular 3 courses in all universitites in a country
    public function generateUnivGroupedCourseListUrl($solrRequestData){
	    $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'q=*%3A*';
        $urlComponents[] = 'fq=facetype:abroadlisting';
        $urlComponents[] = 'fq=saUnivCountryId:'.$solrRequestData['countryId'];
        $urlComponents[] = 'sort=saUnivPopularityIndex+desc';
        $urlComponents[] = 'sort=saCoursePopularityIndex+desc';
        $urlComponents[] = 'fl=saUnivId,saCourseId';
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.field=saUnivId';
        $urlComponents[] = 'group.limit=3';
        $urlComponents[] = 'group.ngroups=true';
        $urlComponents[] = 'start='.$solrRequestData['LimitOffset'];
        $urlComponents[] = 'rows='.$solrRequestData['LimitRowCount'];
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        return $solrUrl;
    }
}
