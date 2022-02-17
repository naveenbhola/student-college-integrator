<?php

class AbroadCategoryPageSolrRequestGenerator
{
    private $CI;
    private $solrServer;
    private $request;
    private $citiesSetInURL;
      
    function __construct()
    {
        $this->CI = & get_instance();
		$this->abroadCommonLib = $this->CI->load->library("listingPosting/AbroadCommonLib");
    }
    
    public function generateUrlForCategoryPage(AbroadCategoryPageRequest $request, $applyFiltersFlag = 1)
    {
		// get various parameters from request
        $this->request = $request;
        /*
         * Get request attributes
         */ 
        $categoryId = (int) $request->getCategoryId();
        $subCategoryId = (int) $request->getSubCategoryId();
        $LDBCourseId = (int) $request->getLDBCourseId();
        $countryIds = $request->getCountryId();
		//$countryIds = array(3,4);
        $stateId = (int) $request->getStateId();
        $cityId = (int) $request->getCityId();
		$courseLevel = $request->getCourseLevel();
		// prepare url components
        $urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';
        
        //Filters to be applied
        $urlComponents[] = 'fq=facetype:abroadlisting';
        
		if($request->isLDBCourseSubCategoryPage() || $request->isCategorySubCategoryCourseLevelPage())
		{
			$subcatTag = '';
		}
		else
		{
			$subcatTag = '{!tag=fcat}';
		}
		if($request->isCategorySubCategoryCourseLevelPage() || $request->isCategoryCourseLevelPage())
        {
			if($courseLevel == 'certificate - diploma')
			{
				global $certificateDiplomaLevels;
				$urlComponents[] = 'fq=((saCourseLevel1:"'.(implode('") OR (saCourseLevel1:"',$certificateDiplomaLevels)).'"))';
			}else if($courseLevel == 'masters'){
				$urlComponents[] = 'fq=((saCourseLevel1:"masters") OR (saCourseLevel1:"Masters Diploma") OR (saCourseLevel1:"Masters Certificate"))';
			}
			else if($courseLevel == 'bachelors'){
				$urlComponents[] = 'fq=((saCourseLevel1:"bachelors") OR (saCourseLevel1:"Bachelors Diploma") OR (saCourseLevel1:"Bachelors Certificate"))';
			}else{
				$urlComponents[] = 'fq=saCourseLevel1:"'.$courseLevel.'"';
			}
		}

        //Category/subcategory/LDB course filters
        if($LDBCourseId > 1) {
            $urlComponents[] = 'fq=saCourseDesiredCourseId:'.$LDBCourseId;
			if($subCategoryId > 1) { // for ds pages
				$urlComponents[] = 'fq='.$subcatTag.'saCourseSubcategoryId:'.$subCategoryId;
			}
        }
        else if($subCategoryId > 1) {
            $urlComponents[] = 'fq='.$subcatTag.'saCourseSubcategoryId:'.$subCategoryId;
        }
        else {
            $urlComponents[] = 'fq=saCourseParentCategoryId:'.$categoryId;
        }
        
        //Location filters
        if($cityId && $cityId != 1) {
            $urlComponents[] = 'fq={!tag=floc}saUnivCityId:'.$cityId;
        }
        if($stateId && $stateId != 1) {
            $urlComponents[] = 'fq={!tag=floc}saUnivStateId:'.$stateId;
        }
        if(is_array($countryIds)) {
			if(count($countryIds)==1)
            {
                if($countryIds[0]!=1){
				    $urlComponents[] = 'fq=saUnivCountryId:'.$countryIds[0];
                }
			}else{
				$countryClause = "fq=(";
				foreach($countryIds as $countryId)
				{
					$countryClause .= "saUnivCountryId:".$countryId." OR ";
				}
				$countryClause = rtrim($countryClause," OR ").")";
				$urlComponents[] = $countryClause;
			}
        }

         
        //Applied filters

		$appliedFilters = $request->getAppliedFilters();

		$urlComponentsForAppliedFilters = $this->_getURLComponentsForAppliedFilters($appliedFilters,$courseLevel);
		$urlComponents = array_merge($urlComponents,$urlComponentsForAppliedFilters);

        $fieldsToFetch = array('saCourseId, saUnivId, saCourseViewCount, saUnivViewCount, saCourseInventoryType','saCourseSubcategoryId');
        $urlComponents[] 	= 'fl='.implode(',',$fieldsToFetch);
        //Get Sorting Component
        $sortingCriteria    = $this->request->getSortingCriteria();
		// get filters on ajaxcall after page load (all sort by types)
		if($this->request->isSolrFilterAjaxCall())
		{
			// get facet for filters
			$urlFacetComponents = $this->getFacetComponents();
			$urlComponents = array_merge($urlComponents, $urlFacetComponents);
			$urlComponents[] = "rows=0";
			$solrRequestUrl=  SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
			//$this->_getSortingComponent($sortingCriteria,$ajaxURLComponents);
		}
		else if($this->request->isAJAXCall()) // regular filter application
		{
			$urlFacetComponents = $this->getFacetComponents();
			$urlComponents = array_merge($urlComponents, $urlFacetComponents);
			$this->_getSortingComponent($sortingCriteria,$urlComponents);
			$solrRequestUrl=  SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
		}
		else // get results first on page load (all pages other will have facets after page load ... 1st case)
		{
		    // get subcat facet for fat footer on page load of non-subcat pages
		    if( !$this->request->isCategorySubCategoryCourseLevelPage() && 
			!$this->request->isLDBCourseSubCategoryPage())
		    {
			$urlFacetComponents = $this->getSubcatFacetComponents();
					$urlComponents = array_merge($urlComponents, $urlFacetComponents);
		    }
			$this->_getSortingComponent($sortingCriteria,$urlComponents);
			$solrRequestUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
		}
		
        
        // _p($urlComponents);die;
		//error_log('SUKH'. $solrRequestUrl);
        return $solrRequestUrl;
    }
    private function getSubcatFacetComponents(){
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.mincount=1';
        $urlComponents[] = 'facet.limit='.(isMobileRequest()?6:8);
        $urlComponents[] = 'facet.sort=true';
        $taggedFilters = 'ffees,floc,fcat,fmoreOption,fexam,fsaExamScore';
        $this->_getSubcategoryFacetComponent($urlComponents,$taggedFilters);                    
        return $urlComponents;
    }
    private function getFacetComponents(){
        
        $facets = array('fees','location','exams','subcategory','more option');
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.mincount=1';
        $urlComponents[] = 'facet.limit=-1';
        $urlComponents[] = 'facet.sort=true';
        $taggedFilters = 'ffees,floc,fcat,fmoreOption,fexam,fsaExamScore';
        foreach ($facets as $facetName) {
            switch ($facetName) 
            {
                case 'fees':
                    $this->_getFeeFacetComponent($urlComponents,$taggedFilters);
                    break;
                case 'location':
                    $this->_getLocationFacetComponent($urlComponents,$taggedFilters);
                    break;
                case 'exams':
                    $this->_getExamsFacetComponent($urlComponents,$taggedFilters);
                    break;
                case 'subcategory':
                    $this->_getSubcategoryFacetComponent($urlComponents,$taggedFilters);
                    break;
                case 'more option':
                    $this->_getMoreOptionFacetComponent($urlComponents,$taggedFilters);
                    break;    
            }
        }
        return $urlComponents;
    }
    private function _getFeeFacetComponent(&$urlComponents,$taggedFilters){
        foreach ($GLOBALS['CP_ABROAD_FEES_RANGE']['ABROAD_RS_RANGE_IN_LACS'] as $key => $value) 
        {
        $keys = explode('-', $key);
        $urlComponents[] = 'facet.query={!key=fee'.$key.'}saCourseTotal1stYearFees:['.$keys[0].' TO '.$keys[1].']';
        }

        foreach ($GLOBALS['CP_ABROAD_FEES_RANGE']['ABROAD_RS_RANGE_IN_LACS'] as $key => $value) 
        {
        $keys = explode('-', $key);
        $urlComponents[] = 'facet.query={!key=parentfee'.$key.' ex='.$taggedFilters.'}saCourseTotal1stYearFees:['.$keys[0].' TO '.$keys[1].']';
        }
    }

    private function _getLocationFacetComponent(&$urlComponents,$taggedFilters){
        $countryPrefixQuery = "{!ex=floc}";
        $statePrefixQuery = "{!ex=floc}";
        $cityPrefixQuery = "{!ex=floc}";

        $urlComponents[] = 'facet.field='.$countryPrefixQuery.'saUnivCountryId';
        $urlComponents[] = 'facet.field='.$statePrefixQuery.'saUnivStateId';
        $urlComponents[] = 'facet.field='.$cityPrefixQuery.'saUnivCityId';
        
        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivCountryId_parent}saUnivCountryId';
        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivStateId_parent}saUnivStateId';
        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivCityId_parent}saUnivCityId';
                        
    }

    private function _getExamsFacetComponent(&$urlComponents,$taggedFilters){
        $examsPrefixQuery = '{!ex=fexam}';
        $urlComponents[] = 'facet.field='.$examsPrefixQuery.'saCourseEligibilityExamsIdMap';
        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseEligibilityExamsIdMap_parent}saCourseEligibilityExamsIdMap';
        $this->_addExamScoreFacetUrlComponents($urlComponents);
    }

    private function _getSubcategoryFacetComponent(&$urlComponents,$taggedFilters){
        $subcategoryPrefixQuery = "{!ex=fcat}";
        $urlComponents[] = 'facet.field='.$subcategoryPrefixQuery.'saCourseSubcategoryId';
        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseSubcategoryId_parent}saCourseSubcategoryId';
    }

    private function _getMoreOptionFacetComponent(&$urlComponents,$taggedFilters){
        $moreOptionPrefixQuery = "{!ex=fmoreOption}";
        $urlComponents[] = 'facet.field='.$moreOptionPrefixQuery.'saUnivAccommodationAvailable';
        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivAccommodationAvailable_parent}saUnivAccommodationAvailable';

        $urlComponents[] = 'facet.field='.$moreOptionPrefixQuery.'saCourseScholarship';
        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saCourseScholarship_parent}saCourseScholarship';

        $urlComponents[] = 'facet.field='.$moreOptionPrefixQuery.'saUnivType';
        $urlComponents[] = 'facet.field={!ex='.$taggedFilters.'%20key=saUnivType_parent}saUnivType';

        global $certificateDiplomaLevels;
        $urlComponents[] = 'facet.query={!key=diplomaFlag ex=fmoreOption}((saCourseLevel1:"'.(implode('") OR (saCourseLevel1:"',$certificateDiplomaLevels)).'"))';
        $urlComponents[] = 'facet.query={!key=diplomaFlagParent ex='.$taggedFilters.'}((saCourseLevel1:"'.(implode('") OR (saCourseLevel1:"',$certificateDiplomaLevels)).'"))';
    }

    /*
	 * function to get solr url components for applied filters
	 */
    private function _getURLComponentsForAppliedFilters($appliedFilters,$courseLevel)
    {
        $urlComponents = array();
		// Fees Filter
        if(is_array($appliedFilters['fees']) && count($appliedFilters['fees']) > 0)
        {
            $feesComponents = array();
            $feesVal = $appliedFilters['fees'][0];
			$feesVal = explode('-',$feesVal);
			$range ="";
			if($feesVal[1] > 0)
			{
				$range = "[".$feesVal[0]."%20TO%20".$feesVal[1]."]";
			}
            if($range!="")
			{
                $urlComponents[] = 'fq={!tag=ffees}saCourseTotal1stYearFees:'.$range;
			}
        }

		// Degree course (exclude cert-diploma)
        global $certificateDiplomaLevels;
        if(in_array('DEGREE_COURSE',$appliedFilters['moreoption']) && $this->request->getFlagToGetCertDiplomaResults() == false) {
			if($courseLevel != 'certificate - diploma'){
	            $urlComponents[] = 'fq={!tag=fmoreOption}(-saCourseLevel1:"'.urlencode(implode('" AND -saCourseLevel1:"',$certificateDiplomaLevels).'")');
			}
        }
		if(in_array('PUB_FUND',$appliedFilters['moreoption'])) {
            $urlComponents[] = 'fq={!tag=fmoreOption}(saUnivType:"public")';
        }
		if(in_array('OFR_SCHLSHP',$appliedFilters['moreoption'])) {
            $urlComponents[] = 'fq={!tag=fmoreOption}(saCourseScholarship:"Yes")';
        }
		if(in_array('WTH_ACMDTN',$appliedFilters['moreoption'])) {
            $urlComponents[] = 'fq={!tag=fmoreOption}(saUnivAccommodationAvailable:"Yes")';
        }
		
		// specialization (subcategory actually)
		if(count($appliedFilters['specialization'])>0)
		{
			$urlComponents[] = 'fq={!tag=fcat}saCourseSubcategoryId:'.implode(' OR saCourseSubcategoryId:',$appliedFilters['specialization']);
		}
        
        // Exams
		$examMaster = $this->abroadCommonLib->getAbroadExamsMasterList('',0,true);
		$examList = array();
		foreach($examMaster as $exam)
		{
			$examList[$exam['examId']] = $exam['exam'];
		}
		// scores
        //_p($examList);
        //_p($appliedFilters);die;
		if(
		   (is_array($appliedFilters['examsScore']) && count($appliedFilters['examsScore']) > 0) ||
			(is_array($appliedFilters['examsMinScore']) && count($appliedFilters['examsMinScore']) > 0)
		   )
		{
			if(array_key_exists('examsMinScore',$appliedFilters))
			{
				$examMinScore = reset($appliedFilters['examsMinScore']);
				$examMinScoreData = explode('--',$examMinScore);
			}
			if(array_key_exists('examsScore',$appliedFilters))
			{
				$examMaxScore = reset($appliedFilters['examsScore']);
				$examMaxScoreData = explode('--',$examMaxScore);
			}
			if($examMinScoreData[1]>0 && $examMaxScoreData[1]>0){
				$urlComponents[] = 'fq={!tag=fsaExamScore}sa'.urlencode($examList[$appliedFilters['exam'][0]]).'ExamScore:['.$examMinScoreData[1].' TO '.$examMaxScoreData[1].']';
            }
			else if($examMaxScoreData[1]>0){
				$urlComponents[] = 'fq={!tag=fsaExamScore}sa'.urlencode($examList[$appliedFilters['exam'][0]]).'ExamScore:[* TO '.$examMaxScoreData[1].']';
			}
			else if($examMinScoreData[1]>0){
				$urlComponents[] = 'fq={!tag=fsaExamScore}sa'.urlencode($examList[$appliedFilters['exam'][0]]).'ExamScore:['.$examMinScoreData[1].' TO *]';
			}
			else {
				$urlComponents[] = 'fq={!tag=fsaExamScore}sa'.urlencode($examList[$appliedFilters['exam'][0]]).'ExamScore:[* TO *]';   
            }
		}
		else if(is_array($appliedFilters['exam']) && count($appliedFilters['exam']) > 0)
		{
			$examComponent = 'fq={!tag=fexam}saCourseEligibilityExams:(';
			foreach($appliedFilters['exam'] as $examId)
			{
				$examComponent .= $orCondition.'"'.$examList[$examId].'"';
				$orCondition = ' OR ';
			}
			if($examComponent!="") {
				$urlComponents[] = rtrim($examComponent, ' OR ').')';
			}
		}

        if(is_array($appliedFilters['state']) && count($appliedFilters['state']) > 0)
        {
           $stateClause = "fq={!tag=floc}(";
            foreach($appliedFilters['state'] as $stateId)
            {
                $stateClause .= "saUnivStateId:".$stateId." OR ";
            }
            $stateClause = rtrim($stateClause," OR ").")";
            $urlComponents[] = $stateClause;
        }

        if(is_array($appliedFilters['city']) && count($appliedFilters['city']) > 0)
        {
           $cityClause = "fq={!tag=floc}(";
            foreach($appliedFilters['city'] as $cityId)
            {
                $cityClause .= "saUnivCityId:".$cityId." OR ";
            }
            $cityClause = rtrim($cityClause," OR ").")";
            $urlComponents[] = $cityClause;
        }
		
        if(is_array($appliedFilters['country']) && count($appliedFilters['country']) > 0)
        {
           $countryClause = "fq={!tag=floc}(";
            foreach($appliedFilters['country'] as $countryId)
            {
                $countryClause .= "saUnivCountryId:".$countryId." OR ";
            }
            $countryClause = rtrim($countryClause," OR ").")";
            $urlComponents[] = $countryClause;
        }
		return $urlComponents;
		
	 /**
         * State Filter
         */ 
		/*if(is_array($appliedFilters['state']) && count($appliedFilters['state']) > 0) {
            
            foreach($appliedFilters['state'] as $stateRowId) {
                $locationComponents[] = 'course_state_id:'.$this->_escapeSolrQueryString($stateRowId);
            };
        }
	
        if(!empty($locationComponents))
            $urlComponents[] = 'fq={!tag=floc}('.implode('%20OR%20',$locationComponents).')';
        */
            
        
        // ------------------------  End : Location Filters -------------------------
        return $urlComponents;
    }
    
    private function _addExamScoreFacetUrlComponents(& $urlComponents){
        $lib = $this->CI->load->library('listing/AbroadListingCommonLib');
        $exams = $lib->getAbroadExamsMasterListFromCache(true);
        foreach($exams as $exam){
            $urlComponents[] = 'facet.field={!ex=fsaExamScore}sa'.$exam['exam'].'ExamScore';
        }
    }

    public function _escapeSolrQueryString($inputQueryString)
    {
        $match = array('+', ' ');
        $replace = array('%2B', '%20');
        $outputQueryString = str_replace($match, $replace, $inputQueryString);
        
        return $outputQueryString;
    }
    private function _getComponentForPagination(&$urlComponents){
        
        $pageNumber  = $this->request->getPageNumberForPagination();
        $perPageResult = $this->request->getSnippetsPerPage();

        $urlComponents[] = 'start='.(($pageNumber-1)*$perPageResult);
        $urlComponents[] = 'rows='.$perPageResult;

    }


    private function _getSortingComponent($sortingCriteria,&$urlComponents){

            $groupRowCount   = 100; 
            $urlComponents[] = 'group=true';
            $urlComponents[] = 'group.ngroups=true';
            $order = (strtoupper($sortingCriteria['params']['order'])=='ASC')?'asc':'desc';
            switch ($sortingCriteria['sortBy']) {
                case 'exam':
                    $urlComponents[] = 'group.field=saUnivId';
                    //CAE has values A,B,C so we need reverse sorting here
                    if($sortingCriteria['params']['exam']=='cae'){
                        $order = (strtoupper($sortingCriteria['params']['order'])=='ASC')?'desc':'asc';
                    }

                    if($sortingCriteria['params']['exam']!=''){
                    $urlComponents[] = 'sort=sa'.strtoupper($sortingCriteria['params']['exam']).ucfirst($order).'ExamScore+'.$order; 
                    }else{
                    //its just for if someone temper with values    
                    $urlComponents[] = 'sort=saTOEFLDescExamScore+desc';
                    }
                    $this->_getComponentForPagination($urlComponents);
                    break;
                case 'fees':
                    $urlComponents[] = 'group.field=saUnivId';
                    $urlComponents[] = 'sort=saCourseTotal1stYearFees+'.$order;
                    $this->_getComponentForPagination($urlComponents);
                    break;
                case 'viewCount':
                    $urlComponents[] = 'group.field=saUnivId';
                    $urlComponents[] = 'group.sort=saCoursePopularityIndex+desc';
                    //SA-4249: Changes done to bring category sponsor on top in case of popularity sorting
                    //$urlComponents[] = 'sort=saUnivPopularityIndex+desc';
                    $urlComponents[]='bq=saCourseInventoryType:1^1000';
                    $urlComponents[]='boost=saUnivPopularityIndex';
                    $urlComponents[]='defType=edismax';
                    $this->_getComponentForPagination($urlComponents);
                    break;    
                default:
                    $groupRowCount   = 20000;
                    $urlComponents[] = 'group.field=saCourseInventoryType';//,saUnivId,saCourseViewCount';    
                    $urlComponents[] = 'group.sort=saCourseInventoryType+asc,saUnivViewCount+desc,saCourseViewCount+desc';
                    break;
            }
            $urlComponents[] = 'group.limit='.$groupRowCount;
    }


    public function generateURLToGetMILByCourse($data){
        $subCategoryId   = (int) $data['subCategoryId'];
        $desiredCourseId = (int) $data['desiredCourse'];        
        $levelCourse     =  $data['levelCourse'];        
        $courseId        = (int) $data['courseId'];        
        $countryId        = (int) $data['countryId'];        

        // prepare url components
        $urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';

        //Filters to be applied
        $urlComponents[] = 'fq=facetype:abroadlisting';
        $urlComponents[] = 'fq=saCourseInventoryType:2';        
        $urlComponents[] = 'fq=saUnivCountryId:'.$countryId;        
        $urlComponents[] = 'fq=-saCourseId:'.$courseId;
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.ngroups=true';            
        $urlComponents[] = 'group.field=saUnivId';
        $urlComponents[] = 'group.sort=saCoursePopularityIndex+desc';
        $urlComponents[] ='boost=saUnivPopularityIndex';
        $urlComponents[] ='defType=edismax';
        $urlComponents[] = 'rows=3';

        if($desiredCourseId > 1) {
            $urlComponents[] = 'fq=saCourseDesiredCourseId:'.$desiredCourseId;
            if($subCategoryId > 1) { // for ds pages
                $urlComponents[] = 'fq=saCourseSubcategoryId:'.$subCategoryId;
            }
        }
        else if($subCategoryId > 1) {
            $urlComponents[] = 'fq=saCourseSubcategoryId:'.$subCategoryId;
        }

        if($levelCourse){
            $urlComponents[] = 'fq=saCourseLevel1:'.$levelCourse;
        }


        $fieldsToFetch = array('saCourseId,saUnivId');
        $urlComponents[]    = 'fl='.implode(',',$fieldsToFetch);

        $solrRequestUrl=  SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);      
        return $solrRequestUrl;  
    }
}
