<?php
class SASearchLayerLib{
	private $CI;
	public function __construct(){
		$this->CI = &get_instance();
	}

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
    
	public function formatUnivDataForUniversityTuple($univData,$flagDesktop=false){
        
        $finalUnivTupleData = array();
        $abroadCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
        $abroadCategoryPageLib = $this->CI->load->library('categoryList/AbroadCategoryPageLib');
        $this->CI->config->load('SASearch/SASearchPageConfig');
        $examAliasArray = $this->CI->config->item('SolrDataFieldAliases');
        $searchPageLibV2         =  $this->CI->load->library('searchPage/SearchPageLibV2');
        $announcementSection=$searchPageLibV2->addUnivAnnouncementInfo($univData);
        foreach ($univData as $univId => $coursesData) 
        {
        	$count=0;
            foreach ($coursesData as $courseId => $courseData) 
            {
                $count++;
                if($count>2 && !$flagDesktop){
                    break;
                }
                //course details needed
                $finalCourseData = array();
                $finalCourseData['cName'] 	 =  $courseData['i'];
                $finalCourseData['cSeoUrl']  =  $courseData['b'];
                $finalCourseData['cId']  	 =  $courseData['a'];
                $finalUnivTupleData[$univId]['course'][] = $finalCourseData;
                if($count>2 && $flagDesktop){
                    if($count == 30)
                    {
                        break;
                    }
                    continue;
                }
            	$finalUnivTupleData[$univId]['univType'] 	=  str_replace('not_for_profit','Not for Profit',$courseData['h']);
            	if($flagDesktop)
                {
                    $sizePicture = '_300x200';
                }
            	else
                {
                    $sizePicture = '_135x90';
                }
            	$finalUnivTupleData[$univId]['logoLink'] 	=  str_replace('_m',$sizePicture, $courseData['ad']);
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
                $finalUnivTupleData[$univId]['announcementSection']     =  $announcementSection[$univId]['announcement'];
            	
                $formattedFee = $abroadCommonLib->getIndianDisplableAmount($courseData['ak'],2);
                if($formattedFee !=''){
                    $finalUnivTupleData[$univId]['mealFee'] =  $formattedFee;
                }
            }
        }
        //_p($finalUnivTupleData);
        return $finalUnivTupleData;
    }

	public function formatUnivDataForCourseTuple($univData, $flagDesktop=false){
        $abroadCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
        $abroadCategoryPageLib = $this->CI->load->library('categoryList/AbroadCategoryPageLib');
        $searchPageLibV2         =  $this->CI->load->library('searchPage/SearchPageLibV2');
        $this->CI->config->load('SASearch/SASearchPageConfig');
        $examAliasArray = $this->CI->config->item('SolrDataFieldAliases');
        if($flagDesktop)
            $univData['courseSliderData'] = array();
        $announcementSection=$searchPageLibV2->addUnivAnnouncementInfo($univData);        
        foreach ($univData as $univId => $coursesData) 
        {
            $count =0;
            foreach ($coursesData as $courseId => $courseData) 
            {
                $count++;
                if($flagDesktop)
                {
                    $sizePicture = '_300x200';
                    if(count($coursesData)>1)
                    {
                        $univData['courseSliderData'][$univId][$courseId] = $this->_prepareCourseSliderData(array($courseData,$abroadCommonLib,$examAliasArray,$abroadCategoryPageLib,$announcementSection[$univId]['announcement']));
                        if($count>1)
                        {
                            continue;
                        }
                    }
                }
                else
                {
                    $sizePicture = '_135x90';
                }
                $univData[$univId][$courseId]['aS']=$announcementSection[$univId]['announcement'];
                $univData[$univId][$courseId]['h'] 	=  str_replace('not_for_profit','Not for Profit',$courseData['h']);
            	$univData[$univId][$courseId]['ad'] =  str_replace('_m',$sizePicture, $courseData['ad']);
                $formattedFee = $abroadCommonLib->getIndianDisplableAmount($courseData['o'],2,true);
                if($formattedFee !=''){
                    $univData[$univId][$courseId]['o'] =  $formattedFee;
                }
                $examString = $this->prepareExamNameStringForCourse($courseData,$examAliasArray,$abroadCategoryPageLib);
                $univData[$univId][$courseId]['examString'] =  $examString;
                if($flagDesktop){
                    $univData[$univId][$courseId]['otherFields'] = $this->prepareFieldSByCourseLevelAndLDBIdNew($courseData);
                }
                else{
                    $univData[$univId][$courseId]['otherFields'] = $this->prepareFieldSByCourseLevelAndLDBId($courseData);
                }
                $univData[$univId][$courseId]['bachelorString'] =(($courseData['r'] >0)? $courseData['r']." ".$courseData['ag']:'');
            }
        }
        return $univData;
    }

    private function _prepareCourseSliderData($parameters)
    {
        $courseData = &$parameters[0];$abroadCommonLib = &$parameters[1];$examAliasArray = &$parameters[2];$announcementSection=&$parameters[4];
        $abroadCategoryPageLib = &$parameters[3];
        $data = array();
        $data['cN'] 	 =  ($courseData['i']);
        $data['cId']     =  $courseData['a'];
        $data['cS']  =  $courseData['b'];
        $data['aS']  =  $announcementSection;
        $data['cL'] = $courseData['t']." ".(($courseData['t']=='Masters' || $courseData['t']=='Bachelors')?"Degree":"");
//        $trimDomain = parse_url($data['cS']);
//        $data['cS'] = $trimDomain['path'].'?'.$trimDomain['query'];
        $formattedFee = $abroadCommonLib->getIndianDisplableAmount($courseData['o'],2,true);
        if($formattedFee !=''){
            $data['cF'] =  $formattedFee;
        }
        $data['cM']=  htmlentities($courseData['j'])." ".(($courseData['j']==1)?str_replace('s', '', $courseData['k']):($courseData['k']));
        $examString = $this->prepareExamNameStringForCourse($courseData,$examAliasArray,$abroadCategoryPageLib,true);
        if(isset($examString['examStr']) && !is_null($examString['examStr']))
        {
            $data['cE'] =  $examString['examStr'];
            if(isset($examString['examMore']) && !empty($examString['examMore']))
            {
                $data['cME'] =  $examString['examMore'];
            }
        }
        $data['otherFields'] = $this->prepareFieldSByCourseLevelAndLDBIdNew($courseData);
        if(isset($data['otherFields']['wE']) && $data['otherFields']['wE'] ==1)
        {
            $data['cW'] = (($courseData['s'])==0)?"Required":$courseData['s'].' '.(($courseData['s'] ==1)?"year":($courseData['s']!=0 && $courseData['s']!='')?"years":'');
        }
        if(isset($data['otherFields']['bP']) && $data['otherFields']['bP'] ==1)
        {
            $data['cB'] =(($courseData['r'] >0)? $courseData['r']." ".$courseData['ag']:'');
        }
        if(isset($data['otherFields']['t']) && $data['otherFields']['t'] ==1)
        {
            $data['cT'] =$courseData['q'];
        }
        unset($data['otherFields']);
        return $data;
    }

    public function prepareFieldSByCourseLevelAndLDBIdNew($courseData){ 
        $otherFields = array();
        $courseData['t'] = getCourseLevel($courseData['t']);
        if($courseData['ae']==DESIRED_COURSE_MBA){ //MBA

            if($courseData['af']!='No'){
                $otherFields['wE'] = 1;
            }
        }
        if($courseData['t']==STUDY_ABROAD_MASTERS){

            if($courseData['r'] >0){
                $otherFields['bP'] = 1;
            }

        }
        if ($courseData['t']==STUDY_ABROAD_BACHELORS) {
            if($courseData['q'] >0){
                $otherFields['t'] = 1;
            }
        }
        return $otherFields;
    }

    public function prepareExamNameStringForCourse($courseData,$examAlias,$abroadCategoryPageLib,$flagMore = false)
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
        $examMore = array();
		foreach ($courseData['p'] as $key => $value) 
		{
            $str = 'sa'.$value.'ExamScore';
            $score = $courseData[$examAlias[$str]];
            if(count($examScore) >= 2){
                if($flagMore)
                {
                    $examMore[] = $this->createExamStr($value,$score);
                    continue;
                }
                else
                {
                    break;
                }
            }
            $examScore[] = $this->createExamStr($value,$score);
			//if($score !='' && $score !='N/A')

		}
		$examString = implode(', ', $examScore);
		if($flagMore)
        {
            return array('examStr' =>$examString,'examMore'=>$examMore);
        }
        else
        {
            return $examString;
        }

	}

	private function createExamStr($value,$score)
    {
        if($score !='' && $score !='-1' && $score!='-1.0')
        {
            $examStr = $value.":".$score;
        }else
        {
            $examStr = $value;
        }
        return $examStr;
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
           $url = $this->_generateSearchUrl($url,1);
           redirect($url);
        }

        return true;
    }

	public function getPrevNextPageLinksForSearchPage($url,$totalResult,$currentPageNum){
		
		$result = array();
		if($totalResult > ($currentPageNum*SA_SEARCH_PAGE_LIMIT))
		{
			$nextPage = $currentPageNum+1;
			$result['relNext'] = $this->_generateSearchUrl($url,$nextPage);
		}
		if($currentPageNum > 1)
		{
			$prePage = $currentPageNum-1;
			$result['relPrev'] = $this->_generateSearchUrl($url,$prePage);
		}
		return $result;
	}

	private function _generateSearchUrl($url,$newPageNumber){
		$a = parse_url($url);
		if(isset($a['query'])){
			parse_str($a['query'],$output);
			$output['pn'] = $newPageNumber;
		}else{
            $output['pn'] = $newPageNumber;
        }
		$a['query'] = http_build_query($output);
		$newUrl = $a['scheme']."://".$a['host'].$a['path']."?".$a['query'];
		return $newUrl; 
	}

    public function getAutosuggestorHTML($solrResults)
    {
        extract($solrResults,EXTR_SKIP);
        $uSuggs = array();
        $cSuggs = array();
        while (count($uSuggs) + count($cSuggs) < 6) {
            if(!empty($univSuggestions)){
                $uSuggs[] = array_shift($univSuggestions);
            }
            if(!empty($courseSuggestions)){
                $cSuggs[] = array_shift($courseSuggestions);
            }
            if(empty($univSuggestions) && empty($courseSuggestions)){
                break;
            }
        }
        $html = "<ul ".(!isMobileRequest()?'class="srchFilterUl"':'').">";
        $html.= $this->_getUnivSuggestions($uSuggs,$searchText);
        $html.= $this->_getCourseSuggestions($cSuggs,$searchText);
        $html.= $this->_getExamSuggestions($examSuggestions,$searchText);
        $html.= "</ul>";
        return $html;
    }
    private function _getUnivSuggestions($uSuggs,$searchText)
    {
        $html = "";
        foreach($uSuggs as $univSuggestion)
        {
            $univSuggestion['saAutosuggestUnivNameFacet'] = str_replace('&amp;',' and ',$univSuggestion['saAutosuggestUnivNameFacet']);
            $searchText = str_replace('&','and',$searchText);
            $searchText=trim($searchText);
            $start = stripos($univSuggestion['saAutosuggestUnivNameFacet'],$searchText);
            if($start === false)
            {
                $suggestionLabel = $univSuggestion['saAutosuggestUnivNameFacet'];
            }else{
                $replacement = substr($univSuggestion['saAutosuggestUnivNameFacet'],$start, strlen($searchText));
                $suggFrags =  explode($replacement, $univSuggestion['saAutosuggestUnivNameFacet'],2);
                $suggestionLabel = $suggFrags[0].'<strong>'.$replacement.'</strong>'.$suggFrags[1];
            }
            $displayData = array('class'=>'uSug',
                                'univSuggestion'=>$univSuggestion,
                                'title'=>$suggestionLabel,
                                'label'=>'College');
            if(isMobileRequest()){
                $html .= $this->CI->load->view('commonModule/layers/searchLayerWidgets/mainSearchSuggestions',$displayData,true);
            }else{
                $html .= $this->CI->load->view('studyAbroadCommon/searchLayerWidgets/mainSearchSuggestions',$displayData,true);
            }
        }
        return $html;
    }
    private function _getCourseSuggestions($cSuggs,$searchText)
    {
        $html = "";
        foreach($cSuggs as $courseSuggestion){
            $searchText = str_replace('.','',$searchText);
            $searchText = preg_replace('!\s+!', ' ', $searchText);
            $searchText=trim($searchText);
            $start = stripos($courseSuggestion['saAutosuggestCourseFacet'],$searchText);
            if($start===FALSE){
                $suggestionText=$courseSuggestion['saAutosuggestCourseFacet'];
            }else{
                $replacement = substr($courseSuggestion['saAutosuggestCourseFacet'],$start, strlen($searchText));
                $suggFrags =  explode($replacement, $courseSuggestion['saAutosuggestCourseFacet'],2);
                $suggestionText=$suggFrags[0].'<strong>'.$replacement.'</strong>'.$suggFrags[1];

            }
            $displayData = array('class'=>'cSug',
                                'courseSuggestion'=>base64_encode(json_encode($courseSuggestion)),
                                'title'=>$suggestionText,
                                'label'=>'Course');
            if(isMobileRequest()){
                $html .= $this->CI->load->view('commonModule/layers/searchLayerWidgets/mainSearchSuggestions',$displayData,true);
            }else{
                $html .= $this->CI->load->view('studyAbroadCommon/searchLayerWidgets/mainSearchSuggestions',$displayData,true);
            }
        }
        return $html;
    }
    private function _getExamSuggestions($examSuggestions,$searchText)
    {
        $html = "";
        foreach(array_slice($examSuggestions,0,3) as $examSuggestion){
            $searchText=trim($searchText);
            $examNameIdMap = explode(':',$examSuggestion['saAutosuggestExamNameIdMap']);
            
            $start = stripos($examSuggestion['saAutosuggestExamName'],$searchText);
            $replacement = substr($examSuggestion['saAutosuggestExamName'],$start, strlen($searchText));
            $suggFrags =  explode($replacement, $examSuggestion['saAutosuggestExamName'],2);
            $displayData = array('class'=>'eSug',
                                'examNameIdMap'=>$examNameIdMap,
                                'title'=>$suggFrags[0].'<strong>'.$replacement.'</strong>'.$suggFrags[1],
                                'label'=>'Exam');
            if(isMobileRequest()){
                $html .= $this->CI->load->view('commonModule/layers/searchLayerWidgets/mainSearchSuggestions',$displayData,true);
            }else{
                $html .= $this->CI->load->view('studyAbroadCommon/searchLayerWidgets/mainSearchSuggestions',$displayData,true);
            }
        }
        return $html;
    }
    
    public function getLocationAutosuggestorHTML($solrResults,$searchText)
    {
        $countrySuggestions = $citySuggestions = $stateSuggestions = $continentSuggestions = array();
        foreach($solrResults as $locationSuggestion)
        {
            if(!is_null($locationSuggestion['saAutosuggestCountryNameIdMap']))
            {		$nameAndId = explode(':',$locationSuggestion['saAutosuggestCountryNameIdMap']);
                    $nameAndId[0] = $locationSuggestion['saAutosuggestLocationFacet'];
                    $start = stripos($nameAndId[0],$searchText);
                    $replacement = substr($nameAndId[0],$start, strlen($searchText));
                    $suggFrags =  explode($replacement, $nameAndId[0], 2);
                    $countrySuggestions[]= array(	'name' => $suggFrags[0].'<strong>'.$replacement.'</strong>'.$suggFrags[1],
                                                                    'id' => $nameAndId[1]
                                                            );
            }
            else if(!is_null($locationSuggestion['saAutosuggestCityNameIdMap']))
            {		$nameAndId = explode(':',$locationSuggestion['saAutosuggestCityNameIdMap']);
                    $nameAndId[0] = $locationSuggestion['saAutosuggestLocationFacet'];
                    $start = stripos($nameAndId[0],$searchText);
                    $replacement = substr($nameAndId[0],$start, strlen($searchText));
                    $suggFrags =  explode($replacement, $nameAndId[0], 2);
                    $citySuggestions[]= array(		'name' => $suggFrags[0].'<strong>'.$replacement.'</strong>'.$suggFrags[1],
                                                                    'id' => $nameAndId[1]
                                                            );
            }
            else if(!is_null($locationSuggestion['saAutosuggestStateNameIdMap']))
            {		$nameAndId = explode(':',$locationSuggestion['saAutosuggestStateNameIdMap']);
                    $nameAndId[0] = $locationSuggestion['saAutosuggestLocationFacet'];
                    $start = stripos($nameAndId[0],$searchText);
                    $replacement = substr($nameAndId[0],$start, strlen($searchText));
                    $suggFrags =  explode($replacement, $nameAndId[0], 2);
                    $stateSuggestions[]= array(		'name' => $suggFrags[0].'<strong>'.$replacement.'</strong>'.$suggFrags[1],
                                                                    'id' => $nameAndId[1]
                                                            );
            }
            else if(!is_null($locationSuggestion['saAutosuggestContinentNameIdMap']))
            {		$nameAndId = explode(':',$locationSuggestion['saAutosuggestContinentNameIdMap']);
                    $nameAndId[0] = $locationSuggestion['saAutosuggestLocationFacet'];
                    $start = stripos($nameAndId[0],$searchText);
                    $replacement = substr($nameAndId[0],$start, strlen($searchText));
                    $suggFrags =  explode($replacement, $nameAndId[0], 2);
                    $continentSuggestions[]= array('name' => $suggFrags[0].'<strong>'.$replacement.'</strong>'.$suggFrags[1],
                                                                    'id' => $nameAndId[1]
                                                            );
            }
		}
        $html = "<ul ".(!isMobileRequest()?'class="srchFilterUl"':'').">";
        $html.= $this->_getCountrySuggestions($countrySuggestions);
        $html.= $this->_getCitySuggestions($citySuggestions);
        $html.= $this->_getStateSuggestions($stateSuggestions);
        $html.= $this->_getContinentSuggestions($continentSuggestions);
        $html.= "</ul>";
        return $html;
    }
    private function _getCountrySuggestions($countrySuggestions)
    {
        $html = "";
        foreach($countrySuggestions as $countrySuggestion){
            $displayData = array('class'=>'countrySug',
                                'locId'=>$countrySuggestion['id'],
                                'title'=>$countrySuggestion['name'],
                                'label'=>'Country');
            if(isMobileRequest()){
                $html .= $this->CI->load->view('commonModule/layers/searchLayerWidgets/locationSearchSuggestions',$displayData,true);
            }else{
                $html .= $this->CI->load->view('studyAbroadCommon/searchLayerWidgets/locationSearchSuggestions',$displayData,true);
            }
        }
        return $html;
    }
    private function _getCitySuggestions($citySuggestions)
    {
        $html = "";
        foreach($citySuggestions as $citySuggestion){
            $displayData = array('class'=>'citySug',
                                'locId'=>$citySuggestion['id'],
                                'title'=>$citySuggestion['name'],
                                'label'=>'City');
            if(isMobileRequest()){
                $html .= $this->CI->load->view('commonModule/layers/searchLayerWidgets/locationSearchSuggestions',$displayData,true);
            }else{
                $html .= $this->CI->load->view('studyAbroadCommon/searchLayerWidgets/locationSearchSuggestions',$displayData,true);
            }
        }
        return $html;
    }
    private function _getStateSuggestions($stateSuggestions)
    {
        $html = "";
        foreach($stateSuggestions as $stateSuggestion){
            $displayData = array('class'=>'stateSug',
                                'locId'=>$stateSuggestion['id'],
                                'title'=>$stateSuggestion['name'],
                                'label'=>'State');
            if(isMobileRequest()){
                $html .= $this->CI->load->view('commonModule/layers/searchLayerWidgets/locationSearchSuggestions',$displayData,true);
            }else{
                $html .= $this->CI->load->view('studyAbroadCommon/searchLayerWidgets/locationSearchSuggestions',$displayData,true);
            }
        }
        return $html;
    }
    private function _getContinentSuggestions($continentSuggestions)
    {
        $html = "";
        foreach($continentSuggestions as $continentSuggestion){
            $displayData = array('class'=>'continentSug',
                                'locId'=>$continentSuggestion['id'],
                                'title'=>$continentSuggestion['name'],
                                'label'=>'Continent');
            if(isMobileRequest()){
                $html .= $this->CI->load->view('commonModule/layers/searchLayerWidgets/locationSearchSuggestions',$displayData,true);
            }else{
                $html .= $this->CI->load->view('studyAbroadCommon/searchLayerWidgets/locationSearchSuggestions',$displayData,true);
            }
        }
        return $html;
    }

    public function createPaginationData(& $displayData){
        $pageData = $displayData['pageData'];
        $totalResults = $pageData['totalResultCount'];
        $currentPage = $pageData['currentPageNum'];
        $resultsPerPage = $pageData['resultsLimit'];
        $paginationDetails = array();
        $paginationDetails['noData'] = false;
        if($totalResults <= SA_SEARCH_PAGE_LIMIT){
            $paginationDetails['noData'] = true;
            return;
        }
        $totalPages = ceil($totalResults/$resultsPerPage);
        $minPage = 0;
        $maxPage = 0;
        switch($currentPage){
            case 1:
            case 2:
            case 3:
            case 4:
                $minPage = 1; $maxPage = min(9,$totalPages);
                break;
            default:
                $minPage = $currentPage-4;
                $maxPage = min($currentPage+4,$totalPages);
        }
        if($totalPages-$currentPage < 4){
            $minPage = max(1,$totalPages-8);
            $maxPage = $totalPages;
        }

        //$display = ($totalResults==0 ?'style="display:none;"':'');
        $url = getCurrentPageURL();
        $prevNextLink = $this->getPrevNextPageLinksForSearchPage($url, $totalResults, $currentPage);
        $paginationDetails['prevLink'] = urldecode($prevNextLink['relPrev']);
        $paginationDetails['nextLink'] = urldecode($prevNextLink['relNext']);
        for($i=$minPage;$i<=$maxPage;$i++){
            $pageLink = "";
            if($i == $currentPage){
                $pageLink .= "<li class='active'> <a class='active' >".$i."</a></li>";
            }
            else {
                $pageLink .= "<li><a href='" . urldecode($this->_generateSearchUrl($url, $i)) . "'>" . $i . "</a></li>";
            }
            $paginationDetails['allLinks'] .= $pageLink;
        }
        $paginationDetails['prevActive'] = false;
        $paginationDetails['nextActive'] = false;
        if($currentPage > 1){
            $paginationDetails['prevActive'] = true;
        }
        if($currentPage < $totalPages){
            $paginationDetails['nextActive'] = true;
        }
        //$paginationDetails['activePage'] = $currentPage;
        $displayData['paginationDetails'] = $paginationDetails;
    }

}
