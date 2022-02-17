<?php

class scholarshipCategoryPageSolrRequestGenerator
{
    private $CI;
    private $urlCategoryComponent; // because we have to take 'OR' between scholarshipCategory and scholarshipUniversity field filters
    function __construct(){
        $this->CI = & get_instance();
    }
    
    public function getScholarshipCategoryPageRequestUrl(scholarshipCategoryPageRequest $request,$tupleCount='')
    {
        // get various parameters from request
        $this->request = $request;
        /*
         * Get request attributes
         */ 
        
        $urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'fl=saScholarshipId';
        
        //Filters to be applied
        $urlComponents[] = 'fq=facetype:abroadScholarship';
        
        $filterTags = 'nlf,amf,pcf,stf,srf,scuf,aef,acf,iyf';
        if($request->getType()=="country"){
            $filterTags.=',clf';
        }else{
            $filterTags.=',ctf';
        }
        
        $this->_addAppliedFiltersToURLComponents($request, $urlComponents);
        
        global $scholarshipSortingCriteriaToSolrFieldMapping;
        $sortCriteria = $scholarshipSortingCriteriaToSolrFieldMapping[$request->getScholarshipSorting()];
        if($request->getScholarshipSorting() == 'deadline'){
            $this->_addBoostScoreToURLComponents($request, $urlComponents);
        }
        
        $urlComponents[] = 'start='.(($request->getPageNumber()-1)*SCHOLARSHIP_CATEGORY_PAGE_PAGE_SIZE);
        
        if($request->isAjaxCall() || $request->isFilterAjaxCall()){
            if($request->isFilterAjaxCall()){
                $urlComponents[] = 'rows=0';
            }else{
                $urlComponents[] = 'rows='.SCHOLARSHIP_CATEGORY_PAGE_PAGE_SIZE;
                $urlComponents[] = "sort=".$sortCriteria;
            }
            $urlComponents[] = 'facet=true';
            $urlComponents[] = 'facet.mincount=1';
            $urlComponents[] = 'facet.limit=-1';
            $urlComponents[] = 'facet.sort=count';
            
            $urlComponents[] = 'facet.field={!ex=pcf}saScholarshipCategoryId';
            $urlComponents[] = 'facet.field={!ex='.$filterTags.'%20key=saScholarshipCategoryId_parent}saScholarshipCategoryId';
            
            $urlComponents[] = 'facet.field={!ex=nlf}saScholarshipStudentNationality';
            $urlComponents[] = 'facet.field={!ex='.$filterTags.'%20key=saScholarshipStudentNationality_parent}saScholarshipStudentNationality';
            
            $urlComponents[] = 'facet.field={!ex=iyf}saScholarshipIntakeYear';
            $urlComponents[] = 'facet.field={!ex='.$filterTags.'%20key=saScholarshipIntakeYear_parent}saScholarshipIntakeYear';
            
            $urlComponents[] = 'facet.field={!ex=scuf}saScholarshipCategory';
            $urlComponents[] = 'facet.field={!ex='.$filterTags.'%20key=saScholarshipCategory_parent}saScholarshipCategory';
            
            $urlComponents[] = 'facet.field={!ex=stf}saScholarshipType';
            $urlComponents[] = 'facet.field={!ex='.$filterTags.'%20key=saScholarshipType_parent}saScholarshipType';
            
            $urlComponents[] = 'facet.field={!ex=srf}saScholarshipSpecialRestriction';
            $urlComponents[] = 'facet.field={!ex='.$filterTags.'%20key=saScholarshipSpecialRestriction_parent}saScholarshipSpecialRestriction';
            
            $urlComponents[] = 'facet.field={!ex=scuf}saScholarshipUnivIdNameMap';
            $urlComponents[] = 'facet.field={!ex='.$filterTags.'%20key=saScholarshipUnivIdNameMap_parent}saScholarshipUnivIdNameMap';
            
            if($request->getType()=='country'){
                $urlComponents[] = 'facet.field={!ex=clf}saScholarshipCourseLevel';
                $urlComponents[] = 'facet.field={!ex='.$filterTags.'%20key=saScholarshipCourseLevel_parent}saScholarshipCourseLevel';
            }
            else{
                $urlComponents[] = 'facet.field={!ex=ctf}saScholarshipCountryId';
                $urlComponents[] = 'facet.field={!ex='.$filterTags.'%20key=saScholarshipCountryId_parent}saScholarshipCountryId';
            }

            $urlComponents[] = 'stats=true';
            $urlComponents[] = 'stats.field={!ex=acf}saScholarshipAwardsCount';
            $urlComponents[] = 'stats.field={!ex='.$filterTags.'%20key=saScholarshipAwardsCount_parent}saScholarshipAwardsCount';
            
            $urlComponents[] = 'stats.field={!ex=aef}saScholarshipApplicationEndDate';
            $urlComponents[] = 'stats.field={!ex='.$filterTags.'%20key=saScholarshipApplicationEndDate_parent}saScholarshipApplicationEndDate';
            
            $urlComponents[] = 'stats.field={!ex=amf}saScholarshipAmount';
            $urlComponents[] = 'stats.field={!ex='.$filterTags.'%20key=saScholarshipAmount_parent}saScholarshipAmount';
        }
        else if($request->isCountFlag()){
            $urlComponents[] = 'rows=0';
        }
        else{
            if($tupleCount != '')
            {
                $urlComponents[] = 'rows='.$tupleCount;
            }
            else
            {
                $urlComponents[] = 'rows='.SCHOLARSHIP_CATEGORY_PAGE_PAGE_SIZE;
            }
            $urlComponents[] = "sort=".$sortCriteria;
        }
        if(!empty($this->urlCategoryComponent)){
            $urlComponents[] = 'fq={!tag=scuf}( ('.implode(') OR (',$this->urlCategoryComponent).') )';
        }
        
        
        return SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
    }

    private function _addBoostScoreToURLComponents($request, & $urlComponents){
        $urlComponents[] = 'defType=edismax';
        $urlComponents[] = 'bq={!func}if(max(ms(saScholarshipApplicationEndDate,NOW),0),sqrt(sqrt(ms(saScholarshipApplicationEndDate,NOW))),if(exists(saScholarshipApplicationEndDate),ms(NOW,saScholarshipApplicationEndDate),sqrt(ms(NOW,saScholarshipApplicationEndDate))))';
    }

    private function _addAppliedFiltersToURLComponents($request, & $urlComponents)
    {
        $this->_addDefaultFilters($request, $urlComponents);
        $appliedFilters = $request->getAppliedFilters();
        if(count($appliedFilters)==0)
        {
            return false;
        }
        foreach($appliedFilters as $fieldName=>$fieldValue)
        {
            // add each field name & its value as url component
            switch($fieldName)
            {
                case 'saScholarshipCourseLevel': // masters must be translated to all master levels
                    $this->_addCourseLevelFilters($urlComponents,$fieldValue);
                    break;
                case 'saScholarshipCountryId': // countries
                    $this->_addCountryFilters($urlComponents,$fieldValue);
                    break;
                case 'saScholarshipStudentNationality':
                    $this->_addStudentNationalityFilters($urlComponents,$fieldValue);
                    break;
                case 'saScholarshipAmount': // min max range
                    $this->_addScholarshipAmountFilters($urlComponents,$fieldValue);
                    break;
                case 'saScholarshipCategoryId': //simple array
                    $this->_addScholarshipParentCategoryFilters($urlComponents,$fieldValue);
                    break;
                case 'saScholarshipCourseId': //simple array, Used for CLP interlinking
                    $this->_addScholarshipCourseFilters($urlComponents,$fieldValue);
                    break;
                case 'saScholarshipType':
                    $this->_addScholarshipTypeFilters($urlComponents,$fieldValue);
                    break;
                case 'saScholarshipSpecialRestriction': //simple array
                    $this->_addSpecialRestrictionFilters($urlComponents,$fieldValue);
                    break;
                case 'saScholarshipCategory':
                    $this->_addScholarshipCategoryFilters($urlComponents,$fieldValue);
                    break;
                case 'saScholarshipUnivId': //simple array
                    $this->_addScholarshipUniversityFilters($urlComponents,$fieldValue);
                    break;
                case 'saScholarshipApplicationEndDate': // date range
                    $this->_addApplicationEndDateFilters($urlComponents,$fieldValue);
                    break;
                case 'saScholarshipAwardsCount': // range
                    $this->_addAwardCountFilters($urlComponents,$fieldValue);
                    break;
                case 'saScholarshipIntakeYear': //date range
                    $this->_addScholarshipIntakeYearFilters($urlComponents,$fieldValue);
            }
        }
    }
    
    private function _addDefaultFilters($request,&$urlComponents){
        if($request->getType()=='country'){
            $countryId = $request->getCountryId();
            $urlComponents[] = 'fq={!tag=ctf}((saScholarshipCountryId:'.$countryId.") OR(saScholarshipCountryId:1) )";
        }
        else{
            $courseLevel = $request->getLevel();
            if($courseLevel=='masters'){
                global $scholarshipMasterLevels;
                $urlComponents[] = 'fq={!tag=clf}((saScholarshipCourseLevel:"'.(implode('") OR (saScholarshipCourseLevel:"',$scholarshipMasterLevels)).'"))';
            }
            else{
                global $scholarshipBachelorLevels;
                $urlComponents[] = 'fq={!tag=clf}((saScholarshipCourseLevel:"'.(implode('") OR (saScholarshipCourseLevel:"',$scholarshipBachelorLevels)).'"))';
            }
        }
    }
    private function _addCourseLevelFilters(&$urlComponents,&$filterValue){
        $urlComponent = array();
        foreach($filterValue as $courseLevel){
            if(strtolower($courseLevel)=='masters'){
                global $scholarshipMasterLevels;
                foreach($scholarshipMasterLevels as &$levelName){
                    if($levelName!='all'){
                        $urlComponent[] = '"'.$levelName.'"';
                    }
                }
            }
            else{
                global $scholarshipBachelorLevels;
                foreach($scholarshipBachelorLevels as &$levelName){
                    if($levelName!='all'){
                        $urlComponent[] = '"'.$levelName.'"';
                    }
                }
            }
        }
        $urlComponent[] = '"all"';
        $urlComponent = implode(' ', $urlComponent);
        $urlComponents[] = 'fq={!tag=clf}'.'saScholarshipCourseLevel:('.$urlComponent.')';
    }
    private function _addCountryFilters(&$urlComponents,&$filterValue){
        $urlComponent = $filterValue;
        $urlComponent[] = '1';  //all countries
        $urlComponent = implode(' ', $urlComponent);
        $urlComponents[] = 'fq={!tag=ctf}'.'saScholarshipCountryId:('.$urlComponent.')';
    }
    private function _addStudentNationalityFilters(&$urlComponents,&$filterValue){
        $urlComponent = $filterValue;
        $urlComponent[] = '1';  //all nationalities
        $urlComponent = implode(' ', $urlComponent);
        $urlComponents[] = 'fq={!tag=nlf}'.'saScholarshipStudentNationality:('.$urlComponent.')';
    }
    
    private function _addScholarshipAmountFilters(&$urlComponents,&$filterValue){
        $urlComponents[] = 'fq={!tag=amf}'.'saScholarshipAmount:['.$filterValue[0].' TO '.$filterValue[1].']';
    }
    private function _addScholarshipParentCategoryFilters(&$urlComponents,&$filterValue){
        $urlComponent = $filterValue;
        $urlComponent[] = 'all';
        $urlComponent = implode(' ', $urlComponent);
        $urlComponents[] = 'fq={!tag=pcf}'.'saScholarshipCategoryId:('.$urlComponent.')';
    }
    //Used for CLP interlinking only
    private function _addScholarshipCourseFilters(&$urlComponents,&$filterValue){
        $urlComponent = $filterValue;
        $urlComponent = implode(' ', $urlComponent);
        $urlComponents[] = 'fq={!tag=pcf}'.'saScholarshipCourseId:('.$urlComponent.')';
    }
    private function _addScholarshipTypeFilters(&$urlComponents,&$filterValue){
        $urlComponent = $filterValue;
        $urlComponent[] = 'both';
        $urlComponent = implode(' ', $urlComponent);
        $urlComponents[] = 'fq={!tag=stf}'.'saScholarshipType:('.$urlComponent.')';
    }
    private function _addSpecialRestrictionFilters(&$urlComponents,&$filterValue){
        $urlComponent = $filterValue;
        $urlComponent = implode(' ', $urlComponent);
        $urlComponents[] = 'fq={!tag=srf}'.'saScholarshipSpecialRestriction:('.$urlComponent.')';
    }
    private function _addScholarshipCategoryFilters(&$urlComponents,&$filterValue){
        $urlComponent = $filterValue;
        $urlComponent = implode(' ', $urlComponent);
        $this->urlCategoryComponent[] = 'saScholarshipCategory:('.$urlComponent.')';
    }
    private function _addScholarshipUniversityFilters(&$urlComponents,&$filterValue){
        $urlComponent = $filterValue;
        $urlComponent = implode(' ', $urlComponent);
        $this->urlCategoryComponent[] = 'saScholarshipUnivId:('.$urlComponent.')';
    }
    private function _addApplicationEndDateFilters(&$urlComponents,&$filterValue){
        $urlComponents[] = 'fq={!tag=aef}'.'saScholarshipApplicationEndDate:['.$filterValue[0].' TO '.$filterValue[1].']';
    }
    private function _addAwardCountFilters(&$urlComponents,&$filterValue){
        $urlComponents[] = 'fq={!tag=acf}'.'saScholarshipAwardsCount:(['.$filterValue[0].' TO '.$filterValue[1].'])';
    }
    private function _addScholarshipIntakeYearFilters(&$urlComponents,&$filterValue){
        $urlComponent = implode(' ', $filterValue);
        $urlComponents[] = 'fq={!tag=iyf}saScholarshipIntakeYear:('.$urlComponent.')';
    }
}
