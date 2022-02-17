<?php

class scholarshipCategoryPageRequest
{
    private $countryId;
    private $type;
    private $pageNumber;
    private $level;
    private $url;
    private $countryName;
    private $isAjaxCall;
    private $isFilterAjaxCall;
    private $totalResults=0;
    private $resultsPerPage = SCHOLARSHIP_CATEGORY_PAGE_PAGE_SIZE;
    private $appliedFilters;
    private $queryString;
    private $requestCounter;
    private $countFlag;
    /** below params are to be set,  when category page url is requested(these will still be a part of url's query string (not the private member))
     * when a url is hit, these are stored as appied filters in $this->appiedFilters
     * Note : All of the names are taken from the keys of global array $queryStringFieldNameToAliasMapping (scholarshipCategoryPageConfig.php) so that they can be translated directly to their corresponding aliases which make up the url's query string
     * START : params required for url
     */
    private $scholarshipLevel; // array of level name
    private $destinationCountry; // array of ids
    private $schAmountMin; // lower limit of amount range (unformatted numeric value)
    private $schAmountMax; // upper limit of amount range (unformatted numeric value)
    private $scholarshipStream; // array of cat ids
    private $scholarshipType; // array of type names
    private $specialRestrictions; // array of restriction ids
    private $scholarshipCategory; // scholarship category name
    private $scholarshipUniversity; // array of univ ids
    private $schDeadlineMin; // lower limit of deadline range (date string : Y-m-d)
    private $schDeadlineMax; // upper limit of deadline range (date string : Y-m-d)
    private $studentNationality; // array of ids
    private $schAwardMin; // lower limit of awards range (unformatted numeric value)
    private $schAwardMax; // upper limit of awards range (unformatted numeric value)
    private $intakeYear; // array of years
    private $sortCriteria = 'popularity';
    /*
     * END : params required for url
     */
    public function getCountryId(){
        if($this->getType()=='country'){
            return $this->countryId;
        }
        else{
            $this->_invalidTypeException();
        }
    }
    public function getType(){
        return $this->type;
    }
    
    public function getUrl(){ 
        if(isset($this->url)){
            return SHIKSHA_STUDYABROAD_HOME.$this->url;
        }
    }
    public function getCountryName(){
        if($this->getType()=='country'){
            return $this->countryName;
        }
        else{
            $this->_invalidTypeException();
        }
    }

    public function getPageNumber(){
        return $this->pageNumber;
    }
    
    public function getSnippetsPerPage(){
        return $this->resultsPerPage;
    }

    public function getLevel(){
        if($this->getType()=='courseLevel'){
            return $this->level;
        }
        else{
            $this->_invalidTypeException();
        }
    }
    public function getPaginatedUrl($pageNumber=1,$withFilters=true){
        $url = '';
        if($this->getType()=='country'){
            $url = '/scholarships/'.seo_url_lowercase($this->getCountryName()).'-cp';
        }
        if($this->getType()=='courseLevel'){
            $url = '/scholarships/'.seo_url_lowercase($this->getLevel()).'-courses';
        }
        if($pageNumber>1){
            $url = $url.'-'.$pageNumber;
        }
        $url = SHIKSHA_STUDYABROAD_HOME.$url;
        if($this->getFilterUrl()!='' && $withFilters){
            $url = $url.'?'.$this->getFilterUrl();
        }
        return $url;
    }
    public function isAjaxCall(){ // get applied filter and tuple
        if($this->isAjaxCall==TRUE)
            return true;
        else return FALSE;
    }
    public function isFilterAjaxCall(){  //get filters html
        return ($this->isFilterAjaxCall===true ? true : false);
    }
    public function getTotalResults(){
        return $this->totalResults;
    }

    private function _invalidTypeException(){
        throw new Exception("Scholarship Category Page is of type '".$this->getType()."'", 1);
    }
    function __set($property,$value){
        $this->$property = $value;
    }
    public function getAppliedFilters()
    {
        return $this->appliedFilters;
    }
    public function getFilterUrl(){
        return $this->queryString;
    }
    public function getRequestCounter(){
        return $this->requestCounter;
    }
    public function isCountFlag(){
        return $this->countFlag;
    }
    /*
     * START : getters of params required for url
     */
    public function getScholarshipLevel(){
        return $this->scholarshipLevel; // array of level name
    }
    public function getDestinationCountry(){
        return $this->destinationCountry; // array of ids
    }
    public function getSchAmountMin(){
        return $this->schAmountMin; // lower limit of amount range
    }
    public function getSchAmountMax(){
        return $this->schAmountMax; // upper limit of amount range
    }
    public function getScholarshipStream(){
        return $this->scholarshipStream; // array of cat ids
    }
    public function getScholarshipType(){
        return $this->scholarshipType; // array of type names
    }
    public function getSpecialRestrictions(){
        return $this->specialRestrictions; // array of restriction ids
    }
    public function getScholarshipCategory(){
        return $this->scholarshipCategory; // scholarship category name
    }
    public function getScholarshipUniversity(){
        return $this->scholarshipUniversity; // array of univ ids
    }
    public function getSchDeadlineMin(){
        return $this->schDeadlineMin; // lower limit of deadline range
    }
    public function getSchDeadlineMax(){
        return $this->schDeadlineMax; // upper limit of deadline range
    }
    public function getStudentNationality(){
        return $this->studentNationality; // array of ids
    }
    public function getSchAwardMin(){
        return $this->schAwardMin; // lower limit of awards range
    }
    public function getSchAwardMax(){
        return $this->schAwardMax; // upper limit of awards range
    }
    public function getIntakeYear(){
        return $this->intakeYear; // array of years
    }
    public function getScholarshipSorting(){
        return $this->sortCriteria;
    }
    /*
     * END :getters params required for url
     */
}
