<?php

class scholarshipCategoryPageLib{
    private $CI;
    function __construct(){
        $this->CI = &get_instance();
        $this->CI->load->config('scholarshipCategoryPage/scholarshipCategoryPageConfig');
        $this->CI->load->model('scholarshipCategoryPage/scholarshipcategorypagemodel');
        $this->scholarshipcategorypagemodel = new scholarshipcategorypagemodel();
        $this->CI->load->builder('scholarshipCategoryPage/scholarshipCategoryPageBuilder');
        $this->scholarshipCategoryPageBuilder = new scholarshipCategoryPageBuilder();
        $this->scholarshipCategoryPageUrlLib = $this->CI->load->library('scholarshipCategoryPage/scholarshipCategoryPageUrlLib');
        //Location
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $this->locationRepository = $locationBuilder->getLocationRepository();
        //Category
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder    = new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();
    }

    public function prepareSeoData(&$displayData,$isMobile = false){
        $request = $displayData['request'];
        $seoMetaTemplateData = $this->getTemplateForInterlinkedPages($request);
        $displayData['interlinked'] = $seoMetaTemplateData['interlinked'];
        $desiredCourseStr = $seoMetaTemplateData['desiredCourseStr'];
        $metaData = $seoMetaTemplateData['data'];
        $placeholders = array('{desiredCourse}', '{country}', '{NumberOfScholarships}', '{stream}', '{level}',
            '{ScholarshipsCount}');
        $countStr=$displayData['totalTupleCount'].($displayData['totalTupleCount'] > 1 ? ' scholarships' : ' scholarship');
        $scholarshipsCount=$displayData['totalTupleCount'];
        if($request->getType()=='country'){
            $countryObj = $this->locationRepository->findCountry($request->getCountryId());
            $realValues = array($desiredCourseStr, $countryObj->getName(), $countStr, '', '',$scholarshipsCount);
        }else{
            if($request->getAppliedFilters()['saScholarshipCategoryId'][0] > 0){
                $categoryObj = $this->categoryRepository->find($request->getAppliedFilters()['saScholarshipCategoryId'][0]);
                $categoryName = ucfirst($categoryObj->getName());
            }
            if($request->getAppliedFilters()['saScholarshipCountryId'][0] > 0){
                $countryObj = $this->locationRepository->findCountry($request->getAppliedFilters()['saScholarshipCountryId'][0]);
                $countryName = ucfirst($countryObj->getName());
            }
            $realValues = array($desiredCourseStr, $countryName, $countStr, $categoryName, ucfirst($request->getLevel()));
        }
        $seoDetails = array();
        $seoDetails['seoTitle']       = str_replace($placeholders, $realValues, $metaData['title']);
        $seoDetails['seoDescription'] = str_replace($placeholders, $realValues, $metaData['description']);
        $seoDetails['h1TagString']    = str_replace($placeholders, $realValues, $metaData['h1TagString']);
        $seoDetails['lastBreadcrumb'] = str_replace($placeholders, $realValues, $metaData['breadcrumb']);

        if($isMobile){
            $seoDetails['h1TagString'] .= ' ('.($request->getTotalResults()?$request->getTotalResults():0).')';
        }
        if($request->getPageNumber() != 1){
            $seoDetails['seoTitle'] = 'Page '.$request->getPageNumber().' - '.$seoDetails['seoTitle'];
            $seoDetails['seoDescription'] = 'Page '.$request->getPageNumber().' - '.$seoDetails['seoDescription'];
        }
        if($displayData['interlinked'] === true){
            $seoDetails['url'] = $this->getCanonicalUrlForInterlinkedPages($displayData['request']);
        }else{
            $seoDetails['url']  = $request->getUrl();
        }
        $displayData['seoDetails']= $seoDetails;
        $this->_getPrevNextLinks($displayData);
        $displayData['breadcrumbData'] = $this->_prepareBreadCrumb($request, $seoDetails['lastBreadcrumb']);
    }
    private function _getPrevNextLinks(&$displayData){
        if($displayData['request']->getPageNumber()!=1){
            $displayData['relPrev'] = $displayData['request']->getPaginatedUrl($displayData['request']->getPageNumber()-1);
        }
        if(($displayData['request']->getPageNumber())*($displayData['request']->getSnippetsPerPage())<$displayData['totalTupleCount']){
            $displayData['relNext'] = $displayData['request']->getPaginatedUrl($displayData['request']->getPageNumber()+1);
        }

    }

    private function _prepareBreadCrumb(&$request, $lastBreadcrumb){
        $returnArray = array();
        $returnArray[] = array('title' => 'Home', 'url' => SHIKSHA_STUDYABROAD_HOME);
        $returnArray[] = array('title'=>'Scholarships','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships');
        if($request->getType()=='courseLevel'){
            if(empty($lastBreadcrumb)){
                $returnArray[] = array('title'=>  ucfirst($request->getLevel())." Courses");
            }else{
                $urlRequest = new scholarshipCategoryPageRequest();
                $urlRequest->__set('type',$request->getType());
                $urlRequest->__set('level',$request->getLevel());
                $baseUrl = $this->scholarshipCategoryPageUrlLib->prepareURLFromRequest($urlRequest);
                $returnArray[] = array('title'=>  ucfirst($request->getLevel())." Courses" , 'url'=>$baseUrl);
                $returnArray[] = array('title'=>$lastBreadcrumb);
            }
        }
        else if($request->getType()=='country'){
            $returnArray[] = array('title'=>$request->getCountryName());
        }
        return $returnArray;
    }

    public function prepareTrackingData($pageIdentifier,$request){
        $extraData = array();
        if($request->getType()=='courseLevel'){
            $extraData['courseLevel'] = $request->getLevel();
        }
        else if($request->getType()=='country'){
            $extraData['countryId'] = $request->getCountryId();
        }
        $extraData['type'] = $request->getType();
        $beaconTrackData = array(
            'pageIdentifier' => $pageIdentifier,
            'pageEntityId' =>0,
            'extraData' => $extraData
        );
        return $beaconTrackData;
    }
    /*
     * formats data from scholarship objs for tuples
     */
    public function formatTupleData(& $data)
    {
        $this->scholarshipCommonLib = $this->CI->load->library('scholarshipsDetailPage/scholarshipCommonLib');
        $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
        $this->CI->load->config('studyAbroadCMSConfig');
        $this->allRestrictions = $this->CI->config->item('SCHOLARSHIP_SPECIAL_RESTRICTION');
        $this->CI->load->builder('location/LocationBuilder');
        $locationBuilder = new LocationBuilder();
        $this->locationRepo = $locationBuilder->getLocationRepository();
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder       = new ListingBuilder;
        $this->abroadUniversityRepository   = $listingBuilder->getUniversityRepository();
        $this->abroadCourseRepository   = $listingBuilder->getAbroadCourseRepository();
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder 	= new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();
        $data['tupleData'] = array();
        $data['relatedObjectsData'] = array();
        $this->_getRelatedDataForScholarshipTuples($data);
        foreach($data['scholarshipData']['scholarshipObjs'] as $scholarshipId=>$scholarshipObj) {
            $tuple = array();
            $tuple['name'] = $scholarshipObj->getName();
            $tuple['url'] = $scholarshipObj->getUrl();
            $tuple['category'] = $scholarshipObj->getCategory();
            // university &  country, categories
            $this->_getTupleStringForCountriesAndStream($tuple, $scholarshipObj,$data);
//            $this->_getTupleStringForCountriesAndStream_old($tuple, $scholarshipObj);
            $type = $scholarshipObj->getScholarshipType();
            $tuple['type'] = ucfirst(($type == 'both'?'Need & Merit':$type.' based'));

            if($scholarshipObj->getApplicationData()->getBrochureUrl() != ''){
                $tuple['brochureAvailable'] = true;
            }else{
                $tuple['brochureAvailable'] = false;
            }

            //amount,restriction,award
            $this->_getTupleStringForAmount($tuple, $scholarshipObj);
            $this->_getTupleStringForRestriction($tuple, $scholarshipObj);
            $this->_getTupleStringForAwardAndDeadline($tuple, $scholarshipObj);
            $data['tupleData'][$scholarshipId] = $tuple;
        }
    }
    private function _getRelatedDataForScholarshipTuples(&$data){
        $universities = array();
        $courses = array();
        $countries = array();
        $categories = array();
        foreach($data['scholarshipData']['scholarshipObjs'] as $scholarshipObj){
            if($scholarshipObj->getCategory()=="internal"){
                $universities[] = $scholarshipObj->getHierarchy()->getUniversity();
                $courseArray = $scholarshipObj->getHierarchy()->getCourses();
                $courses = array_merge($courses,$courseArray);
            }else{
                $countriesArray = $scholarshipObj->getApplicationData()->getApplicableCountries();
                if($countriesArray[0]!=-1){
                    $countries = array_merge($countries,$countriesArray);
                }
                $categoriesArray = $scholarshipObj->getHierarchy()->getParentCategory();
                if($categoriesArray[0]!='all'){
                    $categories = array_merge($categories,$categoriesArray);
                }

            }
        }
        $this->_getRelatedDataForInternalScholarshipTuples($data,$universities,$courses);
        $this->_getRelatedDataForExternalScholarshipTuples($data,$countries,$categories);
    }
    private function _getRelatedDataForInternalScholarshipTuples(&$data,&$universities,&$courses){
        $universities = array_unique($universities);
        $courses = array_unique($courses);
        if(!empty($universities)){
            $universities = $this->abroadUniversityRepository->findMultiple($universities);
        }
        if(!empty($courses)){
            $courses = $this->abroadCourseRepository->findMultiple($courses);
        }
        $subcategories = (array_map(function($course){ return $course->getCourseSubCategory(); },$courses));
        if(!empty($subcategories)){
            $subcategories = $this->categoryRepository->findMultiple($subcategories);
        }
        if(!empty($subcategories)){
            $categories = $this->categoryRepository->findMultiple(array_unique(array_map(function($a){ return $a->getParentId(); },$subcategories)));
        }
        $returnArray = array();
        $returnArray['courses'] = $courses;
        $returnArray['universities'] = $universities;
        $returnArray['subCategories'] = $subcategories;
        $returnArray['categories'] = $categories;
        $data['relatedObjectsData']['internal'] = $returnArray;
    }
    private function _getRelatedDataForExternalScholarshipTuples(&$data,&$countries,&$categories){
        $countries = array_unique($countries);
        if(!empty($countries)){
            $countryObjList = $this->locationRepo->findMultipleCountries($countries);/*getAbroadCountryByIds($countries,true);*/
            foreach($countryObjList as $countryObj)
            {
                $countryObjs[$countryObj->getId()] = $countryObj;
            }
        }
        $categories = array_unique($categories);
        if(!empty($categories)){
            $categories = $this->categoryRepository->findMultiple($categories);
        }
        $allCountries = $this->locationRepo->getAllCountries();
        unset($allCountries['All']);
        ksort($allCountries);
        $allCategories = $this->abroadCommonLib->getAbroadCategories();
        $returnArray = array();
        $returnArray['countries'] = $countryObjs;
        $returnArray['categories'] = $categories;
        $returnArray['allCountries'] = $allCountries;
        $returnArray['allCategories'] = $allCategories;
        $data['relatedObjectsData']['external'] = $returnArray;
    }
    /*
     * gets tuple information for "applicable for", applicable stream
     */
    private function _getTupleStringForCountriesAndStream(& $tuple, & $scholarshipObj,&$data)
    {
//        _p($data['relatedObjectsData']);die;
        if($tuple['category'] == "internal")
        {
            $universityId = $scholarshipObj->getHierarchy()->getUniversity();
            $university = $data['relatedObjectsData']['internal']['universities'][$universityId];
            $tuple['applicableForStr'] = $university->getName()." in ".reset($university->getLocations())->getCountry()->getName();
            // categories
            $courses = $scholarshipObj->getHierarchy()->getCourses();
            $courseObjs = array();
            foreach($courses as $courseId){
                if(!empty($data['relatedObjectsData']['internal']['courses'][$courseId])){
                    $courseObjs[$courseId] = $data['relatedObjectsData']['internal']['courses'][$courseId];
                }
            }
            $subcategories = (array_map(function($course){ return $course->getCourseSubCategory(); },$courseObjs));
            $subcategyObjs = array();
            unset($courseObjs);
            foreach($subcategories as $subcategoryId){
                if(!empty($data['relatedObjectsData']['internal']['subCategories'][$subcategoryId])){
                    $subcategyObjs[$subcategoryId] = $data['relatedObjectsData']['internal']['subCategories'][$subcategoryId];
                }
            }
            $subcategories = $subcategyObjs;
            $categoryIds = array_unique(array_map(function($a){ return $a->getParentId(); },$subcategories));
            foreach($categoryIds as $categoryId){
                if(!empty($data['relatedObjectsData']['internal']['categories'][$categoryId])){
                    $categyObjs[$categoryId] = $data['relatedObjectsData']['internal']['categories'][$categoryId];
                }
            }
            $categories = $categyObjs;
            unset($subcategories);
            unset($subcategyObjs);
            // categories
            $categories = array_map(function($a){ return $a->getName(); },$categories);
            $tuple['streamStr'] = implode(', ',array_slice($categories,0,3));
        }else{
            // countries
            $countries = $scholarshipObj->getApplicationData()->getApplicableCountries();
            if($countries[0] == -1){
                global $studyAbroadPopularCountries;
                $countryObjs = $data['relatedObjectsData']['external']['allCountries'];
                $tuple['applicableForStr'] = "All universities in ".implode(', ',array_slice($studyAbroadPopularCountries,0,2));
                $tuple['applicableForStr2'] =  "+".(count($countryObjs)-2)." more";
            }else{
                $countryObjs = array();
                foreach($countries as $countryId){
                    if(!empty($data['relatedObjectsData']['external']['countries'][$countryId])){
                        $countryObjs[$countryId] = $data['relatedObjectsData']['external']['countries'][$countryId];
                    }
                }
                $tuple['applicableForStr'] = "All universities in ".implode(', ',array_slice(array_map(function($a){ return $a->getName(); },$countryObjs),0,2));
                if(count($countryObjs)>2){
                    $tuple['applicableForStr2'] = "+".(count($countryObjs)-2)." more";
                }
            }
            foreach ($countryObjs as $countryId => $obj) {
                $tuple['allApplicableCountries'][] = $obj->getName();
            }
            // categories
            $categories = $scholarshipObj->getHierarchy()->getParentCategory();
            if($categories[0] == 'all')
            {
                $categories = $data['relatedObjectsData']['external']['allCategories'];
                $categories = array_map(function($a){ return $a['name']; },$categories);
            }else{
                //$categories = (!is_array($categories)?array($categories):$categories);
                $categoryObjs = array();
                foreach($categories as $categoryId){
                    if(!empty($data['relatedObjectsData']['external']['categories'][$categoryId])){
                        $categoryObjs[$categoryId] = $data['relatedObjectsData']['external']['categories'][$categoryId];
                    }
                }
                $categories = $categoryObjs;
                $categories = array_map(function($a){ return $a->getName(); },$categories);
            }
            $tuple['streamStr'] = implode(', ',array_slice($categories,0,3));
        }
        if(count($categories) > 3)
        {
            $tuple['allStreams'] = $categories;
            $tuple['streamStr2'] = "+".(count($categories)-3)." more";
        }
    }
    private function _getTupleStringForCountriesAndStream_old(& $tuple, & $scholarshipObj)
    {
        if($tuple['category'] == "internal")
        {
            $university = $this->abroadUniversityRepository->find($scholarshipObj->getHierarchy()->getUniversity());
            $tuple['applicableForStr'] = $university->getName()." in ".reset($university->getLocations())->getCountry()->getName();
            // categories
            $courses = $scholarshipObj->getHierarchy()->getCourses();
            $courseObjs = $this->abroadCourseRepository->findMultiple($courses);
            $subcategories = (array_map(function($course){ return $course->getCourseSubCategory(); },$courseObjs));
            unset($courseObjs);
            $subcategories = $this->categoryRepository->findMultiple($subcategories);
            $categories = $this->categoryRepository->findMultiple(array_unique(array_map(function($a){ return $a->getParentId(); },$subcategories)));
            unset($subcategories);
            // categories
            $categories = array_map(function($a){ return $a->getName(); },$categories);
            $tuple['streamStr'] = implode(', ',array_slice($categories,0,3));
        }
        else{
            // countries
            $countries = $scholarshipObj->getApplicationData()->getApplicableCountries();
            if($countries[0] == -1){
                global $studyAbroadPopularCountries;
                $countries = $this->locationRepo->getAllCountries();
                unset($countries['All']);
                ksort($countries);
                $countryObjs = $countries;
                $tuple['applicableForStr'] = "All universities in ".implode(', ',array_slice($studyAbroadPopularCountries,0,2));
                $tuple['applicableForStr2'] =  "+".(count($countryObjs)-2)." more";
            }else{
                $countryObjs = $this->locationRepo->getAbroadCountryByIds($countries,true);
                $tuple['applicableForStr'] = "All universities in ".implode(', ',array_slice(array_map(function($a){ return $a->getName(); },$countryObjs),0,2));
                if(count($countryObjs)>2){
                    $tuple['applicableForStr2'] = "+".(count($countryObjs)-2)." more";
                }
            }
            foreach ($countryObjs as $countryId => $obj) {
                $tuple['allApplicableCountries'][] = $obj->getName();
            }
            // categories
            $categories = $scholarshipObj->getHierarchy()->getParentCategory();
            if($categories[0] == 'all')
            {
                $categories = $this->abroadCommonLib->getAbroadCategories();
                $categories = array_map(function($a){ return $a['name']; },$categories);
            }else{
                //$categories = (!is_array($categories)?array($categories):$categories);
                $categories = $this->categoryRepository->findMultiple($categories);
                $categories = array_map(function($a){ return $a->getName(); },$categories);
            }
            $tuple['streamStr'] = implode(', ',array_slice($categories,0,3));
        }
        if(count($categories) > 3)
        {
            $tuple['allStreams'] = $categories;
            $tuple['streamStr2'] = "+".(count($categories)-3)." more";
        }
    }
    /*
     * tuple string for amount
     */
    private function _getTupleStringForAmount(& $tuple, & $scholarshipObj)
    {
        $amount = $scholarshipObj->getAmount();
        if($amount->getConvertedTotalAmountPayout() > 0){
            $currencyData = array('scholarshipObj'=>$scholarshipObj);
            $this->scholarshipCommonLib->getCurrencyConvertRate($currencyData);
            $tuple['amountStr1'] = "Rs ".moneyFormatIndia($amount->getConvertedTotalAmountPayout());
            if($amount->getTotalAmountPayout() > 0 && $amount->getAmountCurrency() > 1){
                $tuple['amountStr2'] = "(".number_format($amount->getTotalAmountPayout())." ".$currencyData['currencyName'].")";
            }
            unset($currencyData);
        }else{
            $tuple['amountStr1'] = "Not available";
        }
    }
    /*
     * tuple string for restriction
     */
    private function _getTupleStringForRestriction(& $tuple, & $scholarshipObj)
    {
        $splRestriction = $scholarshipObj->getSpecialRestrictions();
        $restrictions = $splRestriction->getRestrictions();
        $restriction = $this->allRestrictions[reset($restrictions)];
        if($restriction != "")
        {
            $tuple['restriction'] = $restriction;
            if(count($restrictions) >1){
                $tuple['restriction2'] = " + ".(count($restrictions)-1)." more";
                $tuple['allRestrictions'] = array();
                foreach($restrictions as $res)
                {
                    $tuple['allRestrictions'][] = $this->allRestrictions[$res];
                }
            }
        }
    }
    /*
     * tuple string for awards and deadline
     */
    private function _getTupleStringForAwardAndDeadline(& $tuple, & $scholarshipObj)
    {
        $deadline = $scholarshipObj->getDeadLine();
        $awards = $deadline->getNumAwards();
        if($awards > 0){
            $tuple['awards'] = moneyFormatIndia($awards);
        }else{
            $tuple['awards'] = ($awards == -1?"Varies":"Not Available");
        }
        // deadline date
        $finalDeadline = $deadline->getApplicationEndDate();
        if($finalDeadline!= ""){
            $deadlineTime = date_create_from_format('Y-m-d',$finalDeadline);
            $tuple['finalDeadline'] = date_format($deadlineTime,"j").ordinal(date_format($deadlineTime,"j")).date_format($deadlineTime," M Y");
        }else{
            $tuple['finalDeadline'] = "Not available";
        }
    }

    public function generateScholarshipURL($data){

        if(empty($data)){
            return false;
        }
        $requestData = array();
        $requestData['type'] = 'courseLevel';
        $requestData['level'] = $data['scholarshipURLObj']['scholarshipLevel'];
        $requestData['pageNumber'] = 1;
        $requestData['destinationCountry'] = $data['scholarshipURLObj']['destinationCountry'];
        $requestData['scholarshipStream'] = $data['scholarshipURLObj']['scholarshipStream'];

        $scholarshipCategoryPageRepository = $this->scholarshipCategoryPageBuilder->getScholarshipCategoryPageRepository($requestData);
        $request = $scholarshipCategoryPageRepository->getScholarshipCategoryPageRequest();
        $this->scholarshipCategoryPageUrlLib = $this->CI->load->library('scholarshipCategoryPage/scholarshipCategoryPageUrlLib');
        $url = $this->scholarshipCategoryPageUrlLib->prepareURLFromRequest($request);
        if($url){
            $noOfRecords = $this->fetchNumberOfRecords($data);
            $insertData = array();
            $insertData['userId'] = $data['userId'];
            $insertData['MISTrackingId'] = $data['scholarshipURLObj']['MISTrackingId'];
            $insertData['courseLevel'] = $data['scholarshipURLObj']['scholarshipLevel'];
            $insertData['category'] = implode(",",$data['scholarshipURLObj']['scholarshipStream']);
            $insertData['country'] = implode(",",$data['scholarshipURLObj']['destinationCountry']);
            $insertData['catPageUrl'] = $url;
            $insertData['noOfRecords'] = $noOfRecords ? $noOfRecords : 0;
            $insertData['recordedAt'] = date('Y-m-d H:i:s');
            $insertData['sourceApplication'] = $data['device'];
            $this->scholarshipcategorypagemodel->insertSearchScholarshipTrackingData($insertData);
            return urlencode($url);
        }else{
            return false;
        }
    }

    private function fetchNumberOfRecords(&$data){
        $requestData = array();
        $requestData['countFlag'] = 1;
        $requestData['type'] = 'courseLevel';
        $requestData['level'] = $data['scholarshipURLObj']['scholarshipLevel'];
        $requestData['pageNumber'] = 1;
        if($data['scholarshipURLObj']['destinationCountry']){
            $requestData['appliedFilters']['saScholarshipCountryId'] = $data['scholarshipURLObj']['destinationCountry'];
        }
        if($data['scholarshipURLObj']['scholarshipStream']){
            $requestData['appliedFilters']['saScholarshipCategoryId'] = $data['scholarshipURLObj']['scholarshipStream'];
        }
        $scholarshipCategoryPageRepository = $this->scholarshipCategoryPageBuilder->getScholarshipCategoryPageRepository($requestData);
        $request = $scholarshipCategoryPageRepository->getScholarshipCategoryPageRequest();
        $scholarshipData = $scholarshipCategoryPageRepository->getScholarships();
        return $scholarshipData['total'];
    }
    /*
     * common lib function (Desktop & mobile) to track filters
     */
    public function trackFilters($filterData)
    {
        // common fields
        $trackingData['pageDimension'] = $filterData['pageDimension']['name'];
        $trackingData['pageDimensionValue'] = $filterData['pageDimension']['value'];
        $trackingData['userId'] = $filterData['userId'];
        $trackingData['resultCount'] = $filterData['resultCount'];
        $trackingData['filterAppliedUrl'] = str_replace(SHIKSHA_STUDYABROAD_HOME,'',$filterData['filterAppliedUrl']);
        // data independent fields
        $trackingData['sessionId'] = sessionId();
        $trackingData['applicationSource'] = (isMobileRequest()?'mobile':'desktop');
        $trackingData['insertDate'] = date('Y-m-d');
        $trackingData['insertTime'] = date('H:i:s');
        $filterTrackingData = array();
        $insertId = $this->scholarshipcategorypagemodel->trackFilterApplication($trackingData);

        if($insertId === false)
        {
            return false;
        }
        global $queryStringFieldNameToAliasMapping;
        $aliasFieldNameMapping = array_flip($queryStringFieldNameToAliasMapping);

        foreach($filterData['appliedFilters'] as $alias=>$appliedFilterValues)
        {
            if(in_array($alias,array('sa','sd','ad')))
            {
                $filterTrackingData[] = array('filtersTrackingBaseTableId'=>$insertId,
                    'filterName' => str_replace('Max','',$aliasFieldNameMapping[$alias]),
                    'filterValue' => $appliedFilterValues
                );
            }else if($alias != 'so'){ // skip sorter
                foreach($appliedFilterValues as $value)
                {
                    $filterTrackingData[] = array('filtersTrackingBaseTableId'=>$insertId,
                        'filterName' => $aliasFieldNameMapping[$alias],
                        'filterValue' => $value
                    );
                }
            }
        }

        $this->scholarshipcategorypagemodel->trackFilterApplicationValues($filterTrackingData);
        return true;
    }
    /*
     * function to get seo related template data for pages that are among the interlinked ones(saved in config) 
     * 
     */
    public function getTemplateForInterlinkedPages($catPageRequestObj = null)
    {
        $metaDataTemplateConfig = $this->CI->config->item('metaDataTemplateConfig');
        // check if it's null OR if it is not an object of scholarshipCategoryPageRequest OR if page type is not course level
        if(is_null($catPageRequestObj) || !($catPageRequestObj instanceof scholarshipCategoryPageRequest))
        {
            return false;
        }
        if($catPageRequestObj->getType()!=='courseLevel')
        {
            return array('interlinked'=>false, 'data'=>$metaDataTemplateConfig['defaultTemplateForCountry']);
        }
        // prepare string of format : <lowercase level>_<countryId>_<comma separated categoryIds>
        $keyStr = array();
        $keyStr[] = $catPageRequestObj->getLevel();

        $appliedFilters = $catPageRequestObj->getAppliedFilters();
        sort($appliedFilters['saScholarshipCountryId']);
        $countryStr = implode(',',$appliedFilters['saScholarshipCountryId']);
        sort($appliedFilters['saScholarshipCategoryId']);
        $streamStr = implode(',',$appliedFilters['saScholarshipCategoryId']);
        if(!empty($countryStr)){
            $keyStr[] = $countryStr;
        }
        if(!empty($streamStr)){
            $keyStr[] = $streamStr;
        }
        $keyStr = implode('_', $keyStr);

        // load from config the list of interlinked pages
        $seoUrlConfig = $this->CI->config->item('seoUrlConfig');
        $seoTemplate = $seoUrlConfig[$keyStr];
        $desiredCourseStr = '';
        if($seoTemplate == 'LevelCountryDesiredCourseTemplate'){
            if($streamStr == '239' && $catPageRequestObj->getLevel() == 'masters'){
                $desiredCourseStr = 'MBA';
            }else if($streamStr == '240,241' && $catPageRequestObj->getLevel() == 'bachelors'){
                $desiredCourseStr = 'Engineering';
            }else if($streamStr == '240,241,242' && $catPageRequestObj->getLevel() == 'masters'){
                $desiredCourseStr = 'MS';
            }
        }
        if(is_null($seoTemplate))
        {
            return array('interlinked'=>false, 'data'=>$metaDataTemplateConfig['defaultTemplateForLevel']); // let the default template work
        }else{
            return array('interlinked'=>true, 'data'=>$metaDataTemplateConfig[$seoTemplate], 'desiredCourseStr'=>$desiredCourseStr);   // use the interlinked ones
        }
    }
    /*
     * function to get seo related template data for pages that are among the interlinked ones(saved in config) 
     * 
     */
    public function getCanonicalUrlForInterlinkedPages($catPageRequestObj = null)
    {
        if(is_null($catPageRequestObj))
        {
            return false;
        }else{
            // create another object with only type, level & (country, category) in applied filters 
            $request = new scholarshipCategoryPageRequest();
            // level
            $request->__set('type',$catPageRequestObj->getType());
            $request->__set('level',$catPageRequestObj->getLevel());
            // country
            $appliedFilters = $catPageRequestObj->getAppliedFilters();
            sort($appliedFilters['saScholarshipCountryId']);
            $request->__set('destinationCountry',$appliedFilters['saScholarshipCountryId']);
            //category
            sort($appliedFilters['saScholarshipCategoryId']);
            $request->__set('scholarshipStream',$appliedFilters['saScholarshipCategoryId']);
            // page number
            $request->__set('pageNumber',$catPageRequestObj->getPageNumber());
            //prepareURLFromRequest
            $this->scholarshipCategoryPageUrlLib = $this->CI->load->library('scholarshipCategoryPage/scholarshipCategoryPageUrlLib');
            $url = $this->scholarshipCategoryPageUrlLib->prepareURLFromRequest($request);
            return $url;
        }
    }

    public function getScholarshipTupleDataForInterlinking($paramsArray,$tupleCount=8,$pageType='INTERLINKING')
    {
        $requestData = array();
        if(!$this->_scholarshipInterlinkingRequestData($requestData,$paramsArray,$pageType))
        {
            return false;
        }
        $coreCountryFlag = $this->_countryCatHandling($paramsArray);
        $this->CI->load->builder('scholarshipCategoryPage/scholarshipCategoryPageBuilder');
        $scholarshipCategoryPageBuilder = new scholarshipCategoryPageBuilder();
        $scholarshipCategoryPageRepository = $scholarshipCategoryPageBuilder->getScholarshipCategoryPageRepository($requestData);
        if($requestData['type'] == 'country' && $pageType =='ARTICLEPAGE' && $coreCountryFlag === 0){
            $tupleCount++;
            $skipViewAllURL = true;
        }
        $scholarshipData = $scholarshipCategoryPageRepository->getScholarships($pageType,$tupleCount);
        $scholarshipCount = count($scholarshipData['scholarshipObjs']);
        if($scholarshipCount>0)
        {
            $scholarshipData = $this->_scholarshipTupleDataForInterlinking($scholarshipData,$pageType);
            $request = $scholarshipCategoryPageRepository->getScholarshipCategoryPageRequest();
            if($skipViewAllURL !== true){
                $scholarshipData['viewAllUrl'] = $this->createUrlRequest($request);
            }
        }
        else
        {
            $scholarshipData = array();
        }
        $scholarshipData['coreCountryFlag']=$coreCountryFlag;
        $this->_pageTypeSpecificAction($scholarshipData,$scholarshipCategoryPageBuilder,$requestData,$pageType,$paramsArray);
        return $scholarshipData;
    }

    private function _countryCatHandling($paramsArray)
    {
        $type = isset($paramsArray['type']) ?$paramsArray['type']:'courseLevel';
        $countryArray = isset($paramsArray['country'])?$paramsArray['country']:'';
        if($type == 'country')
        {
            $coreCountryFlag = 0;
            global $scholarshipCategoryPageCountries;
            $requestData['countryId'] = reset($countryArray);
            if(in_array($requestData['countryId'],$scholarshipCategoryPageCountries))
            {
                $coreCountryFlag = 1;
            }
            return $coreCountryFlag;
        }
        else
        {
            return '';
        }
    }

    private function _pageTypeSpecificAction(&$scholarshipData,&$scholarshipCategoryPageBuilder,&$requestData,$pageType, $paramsArray)
    {
        switch ($pageType)
        {
            case 'ULP' :
            {
                $requestData['appliedFilters']['saScholarshipCategory'] = array('external');
                if(isset($scholarshipData['scholarshipData']) && count($scholarshipData['scholarshipData']) > 0)
                {
                    $scholarshipData['mapFlag'] =1;
                }
                else
                {
                    unset($requestData['appliedFilters']['saScholarshipUnivId']);
                    $scholarshipData['mapFlag'] =0;
                }
                if($scholarshipData['coreCountryFlag'] == 0)
                {
                    $scholarshipData['totalCount'] = 1;
                    $scholarshipData['viewAllUrl'] = SHIKSHA_STUDYABROAD_HOME.'/scholarships';
                }
                else
                {
                    $scholarshipCategoryPageRepository = $scholarshipCategoryPageBuilder->getScholarshipCategoryPageRepository($requestData);
                    $scholarshipDataExternal = $scholarshipCategoryPageRepository->getScholarships($pageType,1);
                    $scholarshipData['totalCount'] = $scholarshipDataExternal['total'];
                    $request = $scholarshipCategoryPageRepository->getScholarshipCategoryPageRequest();
                    $scholarshipData['viewAllUrl'] = $this->createUrlRequest($request);
                }
                break;
            }
            case 'CLP_INTERLINKING':
            {
                $requestData['appliedFilters']['saScholarshipCategory'] = array('external');
                if(isset($scholarshipData['scholarshipData']) && count($scholarshipData['scholarshipData']) > 0){
                    $scholarshipData['mapFlag'] = 1;
                }else{
                    $scholarshipData['mapFlag'] = 0;
                }
                unset($requestData['appliedFilters']['saScholarshipCourseId']);
                $type = isset($paramsArray['type']) ?$paramsArray['type']:'courseLevel';
                $level = isset($paramsArray['level']) ?$paramsArray['level']:'';
                $cateogryArray = isset($paramsArray['category'])?$paramsArray['category']:'';
                $countryArray = isset($paramsArray['country'])?$paramsArray['country']:'';

                if($level != '' && $type == 'courseLevel'){
                    $requestData['level'] = $level;
                }
                if(is_array($cateogryArray) && count($cateogryArray)>0){
                    $requestData['appliedFilters']['saScholarshipCategoryId'] = $cateogryArray;
                }
                if(!(count($countryArray)==1 && $countryArray[0] == 1)){
                    $requestData['appliedFilters']['saScholarshipCountryId'] = $countryArray;
                }
                $requestData['appliedFilters']['saScholarshipUnivId'] = $paramsArray['univId'];
                $scholarshipCategoryPageRepository = $scholarshipCategoryPageBuilder->getScholarshipCategoryPageRepository($requestData);
                $scholarshipDataExternal = $scholarshipCategoryPageRepository->getScholarships($pageType,1);
                $scholarshipData['totalCount'] = $scholarshipDataExternal['total'];
                $request = $scholarshipCategoryPageRepository->getScholarshipCategoryPageRequest();
                $scholarshipData['viewAllUrl'] = $this->createUrlRequest($request);
                break;
            }
            default :
            {
                return false;
            }
        }
    }

    private function _scholarshipInterlinkingRequestData(&$requestData,$paramsArray,$pageType)
    {
        $type = isset($paramsArray['type']) ?$paramsArray['type']:'courseLevel';
        $countryName = ($type == 'country' ?$paramsArray['countryName']:'');
        $level = isset($paramsArray['level']) ?$paramsArray['level']:'';
        $cateogryArray = isset($paramsArray['category'])?$paramsArray['category']:'';
        $countryArray = isset($paramsArray['country'])?$paramsArray['country']:'';
        $universityArray = isset($paramsArray['university'])?$paramsArray['university']:'';
        $courseArray = isset($paramsArray['courseId'])?$paramsArray['courseId']:'';
        if($pageType=='INTERLINKING' && (empty($level) || !is_array($cateogryArray) || !is_array($countryArray)))
        {
            return false;
        }
        $requestData = array(
            'pageNumber' => 1,
            'type' => $type,
            'appliedFilters' => array()
        );
        if($level != '' && $type == 'courseLevel'){
            $requestData['level'] = $level;
        }
        if(is_array($courseArray))
        {
            $requestData['appliedFilters']['saScholarshipCourseId'] = $courseArray;
            if($pageType == 'CLP_INTERLINKING'){
                return true;
            }
        }
        if(is_array($cateogryArray) && count($cateogryArray)>0)
        {
            $requestData['appliedFilters']['saScholarshipCategoryId'] = $cateogryArray;
        }
        if(count($countryArray)==1 && $countryArray[0] != 1 && $type=='country'){
            $requestData['countryName'] = reset($countryName);
            $requestData['countryId'] = reset($countryArray);
        }else if(!(count($countryArray)==1 && $countryArray[0] == 1))
        {
            $requestData['appliedFilters']['saScholarshipCountryId'] = $countryArray;
        }
        if(is_array($universityArray))
        {
            $requestData['appliedFilters']['saScholarshipUnivId'] = $universityArray;
//            $requestData['scholarshipCategory'] = array('external');
        }
        return true;
    }

    public function createUrlRequest($catPageRequestObj = null)
    {
        if(is_null($catPageRequestObj))
        {
            return false;
        }else{
            // create another object with only type, level & (country, category) in applied filters
            $request = new scholarshipCategoryPageRequest();
            $type = $catPageRequestObj->getType();
            $request->__set('type',$type);
            if($type == 'country')
            {
                $request->__set('countryId',$catPageRequestObj->getCountryId());
                $request->__set('countryName',$catPageRequestObj->getCountryName());
            }
            else
            {
                // level
                $level = $catPageRequestObj->getLevel();
                if(isset($level))
                {
                    $request->__set('level',$level);
                }
            }
            $appliedFilters = $catPageRequestObj->getAppliedFilters();
            if(isset($appliedFilters['saScholarshipCountryId']))
            {
                sort($appliedFilters['saScholarshipCountryId']);
                $request->__set('destinationCountry',$appliedFilters['saScholarshipCountryId']);
            }
            //category
            if(isset($appliedFilters['saScholarshipCategoryId']))
            {
                sort($appliedFilters['saScholarshipCategoryId']);
                $request->__set('scholarshipStream',$appliedFilters['saScholarshipCategoryId']);
            }
            //university
            if(isset($appliedFilters['saScholarshipUnivId']))
            {
                sort($appliedFilters['saScholarshipUnivId']);
                $request->__set('scholarshipUniversity',$appliedFilters['saScholarshipUnivId']);
            }
            //scholarshipCategory
            if(isset($appliedFilters['saScholarshipCategory']))
            {
                $request->__set('scholarshipCategory',$appliedFilters['saScholarshipCategory']);
            }
            // page number
            $request->__set('pageNumber',$catPageRequestObj->getPageNumber());
            //prepareURLFromRequest
            $this->scholarshipCategoryPageUrlLib = $this->CI->load->library('scholarshipCategoryPage/scholarshipCategoryPageUrlLib');
            $url = $this->scholarshipCategoryPageUrlLib->prepareURLFromRequest($request);
            return $url;
        }
    }

    private function _scholarshipTupleDataForInterlinking($scholarshipData,$pageType)
    {
        $data = array();
        $data['totalCount'] = $scholarshipData['total'];
        $this->scholarshipCommonLib = $this->CI->load->library('scholarshipsDetailPage/scholarshipCommonLib');
        $this->abroadCommonLib = $this->CI->load->library('listingPosting/abroadCommonLib');
        $abroadCategories = $this->abroadCommonLib->getAbroadCategories();
        //Course Repo
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder       = new ListingBuilder;
        $this->abroadCourseRepository   = $listingBuilder->getAbroadCourseRepository();
        $courseIdArray = array();
        $courseIdArray['total'] = array();
        foreach ($scholarshipData['scholarshipObjs'] as $sId => $sData)
        {
            $data['scholarshipData'][$sId]['name'] = $sData->getName();
            $data['scholarshipData'][$sId]['url'] = $sData->getUrl();
            $deadline = $sData->getDeadLine();
            $data['scholarshipData'][$sId]['awards'] = $deadline->getNumAwards();
            $this->_getTupleStringForAmount($data['scholarshipData'][$sId],$sData);
            if($pageType == "ARTICLEPAGE" || $pageType == "ULP")
            {
                $categoryIds = $sData->getHierarchy()->getParentCategory();
                if(empty($categoryIds))
                {
                    $tempCourseIdArray = $sData->getHierarchy()->getCourses();
                    $tempCourseIdArray = array_unique($tempCourseIdArray);
                    $courseIdArray[$sId] = $tempCourseIdArray;
                    $courseIdArray['total'] = array_merge($courseIdArray['total'],$tempCourseIdArray);
                }
                else
                {
                    $data['scholarshipData'][$sId]['category'] = $this->_getTupleStringForCategory($categoryIds,$abroadCategories);
                }
            }
        }
        $this->_getCategoryFromCourseId($data,$courseIdArray,$abroadCategories);
        return $data;
    }

    private function _getCategoryFromCourseId(&$data,$courseIdArray,$abroadCategories)
    {
        if(empty($courseIdArray['total']))
        {
            return false;
        }
        $courseObjArray = $this->abroadCourseRepository->findMultiple($courseIdArray['total']);
        $subcategories = (array_map(function($courseObjArray){ return $courseObjArray->getCourseSubCategory(); },$courseObjArray));
        if(!empty($subcategories)){
            $subcategories1 = $this->categoryRepository->findMultiple($subcategories);
        }
        if(!empty($subcategories1)){
            $categoryIds = array_map(function($a){ return $a->getParentId(); },$subcategories1);
        }
        unset($courseIdArray['total']);
        foreach ($courseIdArray as $sId => $courseIdArray)
        {
            $categoryId = array();
            foreach ($courseIdArray as $val)
            {
                if(isset($subcategories[$val]))
                {
                    $subcat = $subcategories[$val];
                    if(isset($categoryIds[$subcat]))
                    {
                        $categoryId[] = $categoryIds[$subcat];
                    }
                }
            }
            $categoryId = array_unique($categoryId);
            $data['scholarshipData'][$sId]['category'] = $this->_getTupleStringForCategory($categoryId,$abroadCategories);
        }
    }

    private function _getTupleStringForCategory($categoryIds,$abroadCategories)
    {
        if(count($categoryIds)==0)
        {
            return '';
        }
        $strComponents = array();
        $str='';
        if($categoryIds[0]=='all')
        {
            // all streams
            $categoryIds = array_map(function($a){return $a['id'];},$abroadCategories);
        }
        foreach($categoryIds as $catId)
        {
            if(count($strComponents)>=3 && (count($categoryIds)-3)>0)
            {
                $str = " + ".(count($categoryIds)-3)." more";
                break;
            }else{
                $strComponents[] = $abroadCategories[$catId]['name'];
            }
        }
        return implode(', ',$strComponents).$str;
    }
}