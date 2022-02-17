<?php
class SearchPageLib{
	private $CI;
	public function __construct(){
		$this->CI = &get_instance();
		$this->searchpagemodel = $this->CI->load->model('SASearch/sasearchmodel');
	}

	public function getSpecializationData($specializationIds,$catRepo){
		$data = $this->searchpagemodel->getSpecializationData($specializationIds);
        $subCatIds = array_map(function($ele){return $ele['subcategoryId'];},$data);
		$catIds = array_map(function($ele){return $ele['categoryId'];},$data);
		$ids = array_merge($subCatIds,$catIds);
		if(count($ids)>0){
		$cateData = $catRepo->findMultiple($ids);
		}
		$nameData = array();
		foreach ($cateData as $key => $cateObj) 
		{
			$nameData[$cateObj->getId()] = $cateObj->getName();
		}
		$structure = array();
		foreach($data as $row){
			$structure[$row['categoryId']]['name'] = $nameData[$row['categoryId']];
			$structure[$row['categoryId']][$row['subcategoryId']]['name'] = $nameData[$row['subcategoryId']];			
			$structure[$row['categoryId']][$row['subcategoryId']][$row['name']]=array('id'=>$row['id']);
		}
               return $structure;
	}
        public function getSubCatIdBySpecializationId($specializationID,$catRepo){
            $subCatIdData=$this->searchpagemodel->getSpecializationData($specializationID,$catRepo);
            $subCatIds=array();
            foreach ($subCatIdData as $sbDetails){
                array_push($subCatIds, $sbDetails['subcategoryId']);
            }
            return array_unique($subCatIds);
        }

        public function getSubcategoryData($subcatIds,$solrRequestData,$catRepo){

        if(count($subcatIds)>0){	
		$data = $this->searchpagemodel->getSubcategoryData($subcatIds);
		$cateData = $catRepo->findMultiple($subcatIds);
		}
		$nameData = array();
		foreach ($cateData as $key => $cateObj) 
		{
			$nameData[$cateObj->getId()] = $cateObj->getName();
		}
        $specNameMapping=$solrRequestData['specNameIdHash'];
        $structure = array();
		foreach($data as $row){
			$structure[$row['subcategoryId']]['name'] = $nameData[$row['subcategoryId']];
                        $specHash=$row['SpecializationName'].':'.$row['subcategoryId'];
                      
                        if(isset($specNameMapping[$specHash])){
                            $specId=$specNameMapping[$specHash];
                        }else{
                            $specId=$row['SpecializationId'];
                        }
                        $structure[$row['subcategoryId']][$row['SpecializationName']] = array('id'=>$specId);
		}
		return $structure;
	}

	public function getSubcategoriesForCategories($categoryIds,$catRepo){
		if(count($categoryIds)>0){
			$catData = $catRepo->getSubCategoriesByCategories($categoryIds,'newAbroad');
			foreach ($catData as $categoryId => $subcatData) 
			{
				foreach ($subcatData as $key => $subcateObj) 
				{
					$subcategories[]= $subcateObj->getId();
				}
			}
		}
		return $subcategories;
	}

	public function getCityDataByStateIds($stateIds,$locationRepo){
		
		if(count($stateIds)>0){
			$citiesByState = $locationRepo->getCitiesByMultipleStates($stateIds);
			$stateData = $locationRepo->findMultipleStates($stateIds);
			$finalResult = array();
			foreach ($citiesByState as $stateId => $citiesData) 
			{
				$finalResult[$stateId]['name'] = $stateData[$stateId]->getName();
				foreach ($citiesData as $key => $cityObj) 
				{
					$finalResult[$stateId][$cityObj->getId()] = array('name'=>$cityObj->getName());
				}
			}
		}
		return $finalResult;
	}

	public function getCountryStateStructureByCountryIds($countryIds,$locationRepo){
		
		$this->CI->config->load('SASearch/SASearchPageConfig');
        $countryWithState = $this->CI->config->item('COUNTRY_WITH_STATE');
        //country which don't have state are removed here
        $countryIds = array_intersect($countryIds, $countryWithState);

		$structure = array();
		foreach ($countryIds as $key => $countryId) 
		{
			if($countryId>0){
				$stateData = $locationRepo->getStatesByCountry($countryId);
				foreach ($stateData as $key => $stateObj) 
				{
					$structure[$stateObj->getId()] = $stateObj->getCountryId();
				}
			}
		}
		return $structure;
	}

	public function getCountryCityStructureByCountryIds($countryIds,$locationRepo){
		$structure = array();
		foreach ($countryIds as $key => $countryId) 
		{
			if($countryId>0)
			{
				$cityData = $locationRepo->getCities($countryId);
				foreach ($cityData as $key => $cityObj) 
				{
					$structure[$cityObj->getId()] = $cityObj->getCountryId(); 
				}
			}
		}
		return $structure;
	}

	public function getCountryNames($countryIds,$locationRepo){
		if(count($countryIds)>0){
			$countryData = $locationRepo->getAbroadCountryByIds($countryIds);
		}
		$result = array();
		foreach ($countryData as $key => $countryObj) {
				$result[$countryObj->getId()] = $countryObj->getName();
		}
		return $result;
	}

	public function getCityDataByIds($cityIds,$locationRepo){
		if(count($cityIds)>0){
			$cityData = $locationRepo->findMultipleCities($cityIds);
		}
		$result = array();
		foreach ($cityData as $key => $cityObj) {
				$result[$cityObj->getId()] = $cityObj->getName();
		}
		return $result;
	}

	public function getContinentCountryStructureByContinentIds($continentIds){
		return $this->searchpagemodel->getContinentCountryStructureByContinentIds($continentIds);
	}

	public function formatUnivDataForCourseTuple($univData){
        
        $abroadCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
        $abroadCategoryPageLib = $this->CI->load->library('categoryList/AbroadCategoryPageLib');
        $this->CI->config->load('SASearch/SASearchPageConfig');
        $examAliasArray = $this->CI->config->item('SolrDataFieldAliases');
        foreach ($univData as $univId => $coursesData) 
        {
            foreach ($coursesData as $courseId => $courseData) 
            {
            	$univData[$univId][$courseId]['h'] 	=  str_replace('not_for_profit','Not for Profit',$courseData['h']);
            	$univData[$univId][$courseId]['ad'] =  str_replace('_m','_135x90', $courseData['ad']);
            	
                $formattedFee = $abroadCommonLib->getIndianDisplableAmount($courseData['o'],2);
                if($formattedFee !=''){
                    $univData[$univId][$courseId]['o'] =  $formattedFee;
                }
                $examString = $this->prepareExamNameStringForCourse($courseData,$examAliasArray,$abroadCategoryPageLib);
                $univData[$univId][$courseId]['examString'] =  $examString;
                $univData[$univId][$courseId]['otherFields'] = $this->prepareFieldSByCourseLevelAndLDBId($courseData);
                $univData[$univId][$courseId]['bachelorString'] =(($courseData['r'] >0)? $courseData['r']." ".$courseData['ag']:'');
            }
        }
        //_p($univData);die;
        return $univData;
    }

    public function prepareExamNameStringForCourse($courseData,$examAlias,$abroadCategoryPageLib)
    {
    	$examString = '';
    	$examScore = array();

    	$requestData = array('LDBCourseId'=>$courseData['ae'],
							 'categoryId'=>$courseData['ah'],
							 'courseLevel'=>$courseData['t']);
		$examOrder = $abroadCategoryPageLib->getExamOrderByCategory($requestData);
		
		uasort($courseData['p'], function ($a,$b) use($examOrder){
			return ($examOrder[$a] > $examOrder[$b]?1:-1);
		});

		foreach ($courseData['p'] as $key => $value) 
		{
			$str = 'sa'.$value.'ExamScore';
			$score = $courseData[$examAlias[$str]];
			//if($score !='' && $score !='N/A')
			if($score !='' && $score !='-1' && $score!='-1.0')
			{
			$examScore[] = $value.":".$score;
			}else
			{
			$examScore[] = $value;	
			}

			if(count($examScore) >=2){
				break;
			}
		}
		$examString = implode(', ', $examScore);
		return $examString;	
	}

	public function prepareFieldSByCourseLevelAndLDBId($courseData){
		$otherFields = array();
		if($courseData['ae']==DESIRED_COURSE_MBA){ //MBA
			
			if($courseData['af']!='No'){
			$otherFields['workEx'] = true;
			}
			if($courseData['r'] > 0){
			$otherFields['bachelorsPercentage'] = true;	
			}

		}elseif($courseData['ae']==DESIRED_COURSE_MS || $courseData['t']=='Masters'){ //MS

			if($courseData['r'] >0){
			$otherFields['bachelorsPercentage'] = true;	
			}

		}elseif ($courseData['ae']==DESIRED_COURSE_BTECH || $courseData['t']=='Bachelors') { //Btech
			if($courseData['q'] >0){
			$otherFields['12th'] = true;	
			}
		}
		return $otherFields;
	}

	public function validatePaginatedSearchPageUrl($url,$totalResult,$currentPageNum){
        if($currentPageNum >1 && $currentPageNum > ceil($totalResult/SA_SEARCH_PAGE_LIMIT))
        {
           $url = $this->_genrateSearchUrl($url,1); 
           redirect($url);
        }

        return true;
    }

	public function getPrevNextPageLinksForSearchPage($url,$totalResult,$currentPageNum){
		
		$result = array();
		if($totalResult > ($currentPageNum*SA_SEARCH_PAGE_LIMIT))
		{
			$nextPage = $currentPageNum+1;
			$result['relNext'] = $this->_genrateSearchUrl($url,$nextPage);
		}
		if($currentPageNum > 1)
		{
			$prePage = $currentPageNum-1;
			$result['relPrev'] = $this->_genrateSearchUrl($url,$prePage);
		}
		return $result;
	}

	private function _genrateSearchUrl($url,$newPageNumber){
		$a = parse_url($url);
		if(isset($a['query'])){
			parse_str($a['query'],$output);
			$output['pn'] = $newPageNumber;
		}
		$a['query'] = http_build_query($output);
		$newUrl = $a['scheme']."://".$a['host'].$a['path']."?".$a['query'];
		return $newUrl; 
	}

	public function checkIfOnlyExamIsSearched($searchedData,$isAjaxCall=false)
	{

		if(!isset($searchedData['exams']) || count($searchedData['exams'])!=1){
			return false;
		}elseif($searchedData['exams'][0]['name']!=''){
			$examDetails = $searchedData['exams'];
			unset($searchedData['keyword']);
			unset($searchedData['exams']);
			if(count($searchedData)>0){
				return false;
			}
			else{

				$examName = strtoupper($examDetails[0]['name']);
				$saContentLib =  $this->CI->load->library('blogs/saContentLib');
                $examPageUrl = $saContentLib->getSAExamHomePageURLByExamNames(array($examName));
                $url = $examPageUrl[$examName]['contentURL'];
                if($url !=''){
	                if($isAjaxCall){
	                	echo json_encode(array('url'=>$url,'type'=>'exam'));
	            		exit;
	                }else{
	                	return $url;
	                }
				}
			}
		}
		return false;
	}

	public function formatUnivDataForUniversityTuple($univData){
        
        $finalUnivTupleData = array();
        $abroadCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
        $abroadCategoryPageLib = $this->CI->load->library('categoryList/AbroadCategoryPageLib');
        $this->CI->config->load('SASearch/SASearchPageConfig');
        $examAliasArray = $this->CI->config->item('SolrDataFieldAliases');
        foreach ($univData as $univId => $coursesData) 
        {
        	$count=0;
            foreach ($coursesData as $courseId => $courseData) 
            {
            	$count++;
            	$finalUnivTupleData[$univId]['univType'] 	=  str_replace('not_for_profit','Not for Profit',$courseData['h']);
            	$finalUnivTupleData[$univId]['logoLink'] 	=  str_replace('_m','_135x90', $courseData['ad']);
            	$finalUnivTupleData[$univId]['cityName'] 	=  $courseData['g'];
            	$finalUnivTupleData[$univId]['univId'] 		=  $courseData['ai'];
            	$finalUnivTupleData[$univId]['countryName'] =  $courseData['e'];
            	$finalUnivTupleData[$univId]['estYear'] 	=  $courseData['aj'];
            	$finalUnivTupleData[$univId]['univSeoUrl'] 	=  $courseData['c'];
            	$finalUnivTupleData[$univId]['univName'] 	=  $courseData['d'];
            	$finalUnivTupleData[$univId]['mealRawValue']=  $courseData['ak'];
            	$finalUnivTupleData[$univId]['rank']		=  $courseData['al'];
            	$finalUnivTupleData[$univId]['rankName']	=  $courseData['am'];
            	$finalUnivTupleData[$univId]['rankURL']		=  $courseData['an'];
            	
                $formattedFee = $abroadCommonLib->getIndianDisplableAmount($courseData['ak'],2);
                if($formattedFee !=''){
                    $finalUnivTupleData[$univId]['mealFee'] =  $formattedFee;
                }
                //course details needed
                $finalCourseData = array();
                $finalCourseData['cName'] 	 =  $courseData['i'];
                $finalCourseData['cSeoUrl']  =  $courseData['b'];
                $finalCourseData['cId']  	 =  $courseData['a'];

                $finalUnivTupleData[$univId]['course'][] = $finalCourseData;

				if($count==2){
					break;
				}                
            }
        }
       // _p($finalUnivTupleData);die;
        return $finalUnivTupleData;
    }

    public function checkToByPassQer($searchStr)
    {
    	$final = array();
    	$searchStr = str_replace('.', '', $searchStr);
    	$searchStrArr = explode(' ', $searchStr);

    	$this->CI->config->load('SASearch/SASearchIndexConfig');
    	$synonyms = $this->CI->config->item('SYNONYM_AUTOSUGGESTOR_MAPPING');

    	$matched = array();
    	$keywordToUnset = array();
    	foreach ($synonyms as $key => $value) 
    	{
    		foreach ($searchStrArr as $searchTermKey => $searchTerm) 
    		{
    			if(strtolower($value['synonym'])==strtolower($searchTerm))
    			{
    				$matched[] = $value;
    				$keywordToUnset[] = $searchTerm;
    			}
    		}
    	}
    	$remainingTextArr = array_diff($searchStrArr, $keywordToUnset);
    	$remainingString  = implode(' ', $remainingTextArr);
    	if(count($remainingTextArr)>0 && strlen(trim($remainingString))>0){
    		$final['qerRequired'] = true;
    		$final['remainingString'] = trim($remainingString);
    	}else{
    		$final['qerRequired'] = false;
    		$final['remainingString'] = '';
    	}

    	$entities = array();
    	if(count($matched)>0){
    		foreach ($matched as $key => $value) 
    		{
    			if($value['courseLevel']!='All'){
    			$entities['level'][] 		= array('name'=>$value['courseLevel']);
    			}

    			if($value['categoryId']!='All'){
    			$entities['categories'][] 	= array('id'=>$value['categoryId']);
    			}
    			
    			if($value['subCatId']!='All'){
    			$entities['subcategories'][]= array('id'=>$value['subCatId']);
    			}
    			if($value['specialization']!='All')
    			{
	    			foreach ($value['specialization'] as $k => $v) 
	    			{
	    				$entities['specializationIds'][] = array('id'=>$v);
	    			}	
    			}
    		}
    		$final['entities'] = $entities;
    	}
    	//_p($searchStrArr);
    	//_p($matched);
    	//_p($keywordToUnset);
 
    	//_p($entities);
    	//_p($final);
    	//die;
    	return $final;
    }
     public function getSpecializationNameById($specializationIds){
        $model = $this->CI->load->model('SASearch/sasearchmodel');
        return $model->getSpecializationNameByID($specializationIds);
    }
/**************************Abroad Desktop Search V2 *****************************/
	// function to merge autosuggested location enities with QER entities
	public function mergeAutosuggestedLocationWithQERResult(&$qerResultWithEntities,$locSearchStringClosed)
	{
		if($locSearchStringClosed !="")
		{
			$locations = json_decode(base64_decode($locSearchStringClosed),true);
			foreach($locations as $location)
			{
				$qerResultWithEntities[$location['locType']][] = array('id'=>$location['locId'], 'name'=>$location['locName']);
			}
		}else{
			return ;
		}
	}
	/*
     * combine location string with main search string if location search is open
     */
    public function getFinalSearchStringParameters($xssCleanedSearchKeyWord)
    {
        // check if location search is open
        $locSearchStringOpen = $this->CI->security->xss_clean($this->CI->input->post('lqOpen'));
        if($locSearchStringOpen !="")
        {   // note that since location has a separate box, for open search we need to combine it with main search string so that it can be processed by QER at once
            $finalSearchString = $xssCleanedSearchKeyWord." ".$locSearchStringOpen;
        }
        else{
            // if location was closed, we only process main search string via QER
            $finalSearchString = $xssCleanedSearchKeyWord;                  
        }
        return $finalSearchString;
    }
}