<?php
/*
 * this lib is to be used when one needs to find a scholarship cat page url when they have values of various parameters
 * 
 */ 
class scholarshipCategoryPageUrlLib{
    private $CI;
    function __construct(){
        $this->CI = &get_instance();
    }
    /*
     * @params : scholarshipCategoryPageRequest Obj
     * @returnVal : url if algoes well, else false
     */
    public function prepareURLFromRequest($request)
    {
        // return false if request obj isn't valid
        if(is_null($request) || !($request instanceOf scholarshipCategoryPageRequest))
        {
            return false;
        }
        $finalURL = '';
        // first we create the base url (without any query parameter)
        if($request->getType()=='country'){
            
            $baseURL = '/scholarships/'.seo_url_lowercase($request->getCountryName()).'-cp';
        }
        else if($request->getType()=='courseLevel'){
            $baseURL = '/scholarships/'.seo_url_lowercase($request->getLevel()).'-courses';
        }
        // then we check for pagination, if any
        $pageNumber = $request->getPageNumber();
        if($pageNumber>1){
            $paginationSuffix = '-'.$pageNumber;
        }
        // then we process the mapping of field name to alias name which we will be using in our url's query string
        $queryString = $this->_generateURLQueryStringFromRequest($request);
        // combine...
        $finalURL = SHIKSHA_STUDYABROAD_HOME.$baseURL.$paginationSuffix.$queryString;
        return $finalURL;
    }
    /*
     * @params : scholarshipCategoryPageRequest Obj 
     */
    private function _generateURLQueryStringFromRequest($request)
    {
        $queryStringComponents = array();
        global $queryStringFieldNameToAliasMapping;
        foreach($queryStringFieldNameToAliasMapping as $fieldName => $alias)
        {
            $fname = 'get'.ucfirst($fieldName);
            $val = $request->$fname();
            if(!is_null($val))
            {
                switch($fieldName)
                {
                    case 'scholarshipLevel'      :
                    case 'destinationCountry'    :
                    case 'scholarshipStream'     : 
                    case 'scholarshipType'       : 
                    case 'specialRestrictions'   : 
                    case 'scholarshipCategory'   : 
                    case 'scholarshipUniversity' : 
                    case 'studentNationality'    : 
                    case 'intakeYear'            : 
                        if(!is_null($val))
                        {
                            $queryStringComponents[$alias] = implode(',',$val);
                        }
                        break;
                    case 'schAmountMin'          :
                    case 'schAwardMin'           : 
                        if($queryStringComponents[$alias]=="")
                        {
                            $queryStringComponents[$alias] = $val;
                        }
                        break;
                    case 'schAmountMax'          :
                    case 'schAwardMax'           : 
                        if($queryStringComponents[$alias]!="") 
                        {
                            $queryStringComponents[$alias] .= "-".$val;
                        }
                        break;
                    case 'schDeadlineMin'        :
                        if($queryStringComponents[$alias]=="") 
                        {
                            $dateObj = date_create_from_format("Y-m-d",$val);
                            if(!is_null($dateObj))
                            {
                                $queryStringComponents[$alias] = $dateObj->format('m-Y');
                            }
                        }
                        break;
                    case 'schDeadlineMax'        :
                        if($queryStringComponents[$alias]!="") 
                        {
                            $dateObj = date_create_from_format("Y-m-d",$val);
                            if(!is_null($dateObj))
                            {
                                $queryStringComponents[$alias] .= "_".$dateObj->format('m-Y');
                            }
                        }
                        break;
                } // switch ends
            } // if ends
        } // foreach ends
        if(count($queryStringComponents) > 0){
            return '?'.urldecode(http_build_query($queryStringComponents));
        }else{
            return '';
        }
    }

    public function prepareScholarsipURLsForSiteMap(){
        $url = array();
        $this->CI->load->builder('scholarshipCategoryPageBuilder','scholarshipCategoryPage');
        $scholarshipCategoryPageBuilder = new scholarshipCategoryPageBuilder;
        $url = $this->getScholarshipCountryURLs($scholarshipCategoryPageBuilder);
        $url = array_merge($url,$this->getScholarshipCourseURLs($scholarshipCategoryPageBuilder));
        $url[] = $this->getScholarshipHomeUrl();
        return $url;
    }

    private function getScholarshipHomeUrl(){
        return SHIKSHA_STUDYABROAD_HOME.'/scholarships';
    }

    private function getScholarshipCourseURLs(&$scholarshipCategoryPageBuilder){
        $url = array();
        $courseLevels = array("bachelors","masters");
        foreach ($courseLevels as $courseLevel) {
            $requestData = array();
            $requestData['type'] = 'courseLevel';
            $requestData['level'] = $courseLevel;
            $requestData['pageNumber'] = 1;
            $scholarshipCategoryPageRepository = $scholarshipCategoryPageBuilder->getScholarshipCategoryPageRepository($requestData);
            $request = $scholarshipCategoryPageRepository->getScholarshipCategoryPageRequest();
            $url[] = $request->getPaginatedUrl();
        }
        return $url;
    }

    private function getScholarshipCountryURLs(&$scholarshipCategoryPageBuilder){
        $url = array();
        $this->CI->load->config('scholarshipCategoryPage/scholarshipCategoryPageConfig');
        $this->CI->load->builder('LocationBuilder','location');
        $this->locationBuilder = new LocationBuilder;
        $this->locationRepository = $this->locationBuilder->getLocationRepository();
        global $scholarshipCategoryPageCountries; 
        $countryObj = $this->locationRepository->findMultipleCountries($scholarshipCategoryPageCountries);
        foreach ($countryObj as $country) {
            $requestData = array();
            $requestData['pageNumber'] = 1;
            $requestData['countryId'] = $country->getId();
            $requestData['type'] = 'country';
            $requestData['countryName'] = $country->getName();
            $scholarshipCategoryPageRepository = $scholarshipCategoryPageBuilder->getScholarshipCategoryPageRepository($requestData);
            $request = $scholarshipCategoryPageRepository->getScholarshipCategoryPageRequest();
            $url[] = $request->getPaginatedUrl();
            unset($request);
            unset($requestData);
        }
        return $url;
    }
}