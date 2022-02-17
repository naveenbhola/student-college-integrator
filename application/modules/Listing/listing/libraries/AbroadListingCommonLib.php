<?php
class AbroadListingCommonLib {

    private $CI;
    private $exchangeRate;

    function __construct() {
        $this->CI =& get_instance();
        $this->exchangeRate = array();

        $this->abroadCourseModel  = $this->CI->load->model('listing/abroadcoursemodel');
        // $this->abroadCourseModel  = new AbroadCourseModel();
        $this->abroadListingModel = $this->CI->load->model('listing/abroadlistingmodel');
    }

    public function getHighestRankOfListing($listingTypeId , $listingType = 'course')
    {
        if(empty($listingTypeId)){
            return 0;
        }

        $highestRankRows = $this->abroadCourseModel->getHighestRankOfListing($listingTypeId, $listingType);
        $ranks = array();
        if(empty($highestRankRows))
            return 0;
        else
        {
            foreach($highestRankRows as $highestRank){
                $ordinalSuffix = $this->getOrdinalSuffix($highestRank['rank']);
                $ranks[$highestRank['listing_id']] = array('rank' => $highestRank['rank'].$ordinalSuffix,
                    'rankPageId' => $highestRank['ranking_page_id']
                );
            }
        }
        return $ranks;
    }

    function getOrdinalSuffix($num)
    {
        if($num < 11 || $num > 13){
            switch($num % 10){
                case 1: return 'st';
                case 2: return 'nd';
                case 3: return 'rd';
            }
        }
        return 'th';
    }

    public function convertCurrency($sourceCurrencyId = NULL, $destinationCurrencyId = NULL, $amount = NULL) {
        $convertedCurrency = 0;
        if($destinationCurrencyId == $sourceCurrencyId)
            return $amount;
        if(!empty($sourceCurrencyId) && !empty($destinationCurrencyId) && !empty($amount)) {
            $this->CI->load->library('listing/cache/AbroadListingCache');
            $abroadListingCacheLib  = new AbroadListingCache();
            if(empty($this->exchangeRate[$sourceCurrencyId][$destinationCurrencyId])){
                $exchangeRate = $this->exchangeRate[$sourceCurrencyId][$destinationCurrencyId] = $abroadListingCacheLib->getCurrencyConversionFactor($sourceCurrencyId, $destinationCurrencyId);
            }
            else{
                $exchangeRate = $this->exchangeRate[$sourceCurrencyId][$destinationCurrencyId];
            }
            if(empty($exchangeRate)){
                $currencyDetails = $this->abroadCourseModel->getCurrencyExchangeRateDump();
                $data = array();
                foreach($currencyDetails as $details) {
                    if(!array_key_exists($details['source_currency_id'], $data)){
                        $data[$details['source_currency_id']] = array();
                    }
                    if(!array_key_exists($details['destination_currency_id'], $data[$details['source_currency_id']])){
                        $data[$details['source_currency_id']][$details['destination_currency_id']] = array();
                    }
                    $data[$details['source_currency_id']][$details['destination_currency_id']]['factor'] = $details['conversion_factor'];
                }
                //$this->CI->load->library('listing/cache/AbroadListingCache');
                //$abroadListingCacheLib  = new AbroadListingCache();
                $abroadListingCacheLib->storeCurrencyConversionFactor($data);
                $exchangeRate = $data[$sourceCurrencyId][$destinationCurrencyId]['factor'];
            }
            //$exchangeRate = $this->abroadCourseModel->getCurrencyExchangeRate($sourceCurrencyId, $destinationCurrencyId);
            if(!empty($exchangeRate)) {
                $convertedCurrency = $amount * $exchangeRate;
            }
        }
        return $convertedCurrency;
    }

    public function getContactNumberForAbroadListing($data=NULL, $applyHierarchyCheck = FALSE)
    {
        $return = array('contact_number' => FALSE, 'contact_hierarchy_level' => FALSE);
        if(empty($data['listingObj']) || empty($data['listingType']))
        {
            return $return;
        }
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        switch($data['listingType']){
            case 'course':
                if($applyHierarchyCheck) {
                    $abroadInstituteRepository 	= $listingBuilder->getAbroadInstituteRepository();
                    $courseObj 				= $data['listingObj'];
                    $departmentId 				= $courseObj->getInstId();
                    $departmentObj 				= $abroadInstituteRepository->find($departmentId);
                    $mainLocationObj			= $departmentObj->getMainLocation();
                    $contactDetails 			= $departmentObj->getMainLocation()->getContactDetail();
                    $contactNumber 				= $contactDetails->getContactMainPhone();
                    if(!empty($contactNumber)) {
                        $return = array('contact_number' => $contactNumber, 'contact_hierarchy_level' => 'department');
                    } else if(empty($contactNumber) && $applyHierarchyCheck){
                        $universityId 				= $courseObj->getUniversityId();
                        $abroadUniversityRepository = $listingBuilder->getUniversityRepository();
                        $universityObject 			= $abroadUniversityRepository->find($universityId);
                        $contactDetails 			= $universityObject->getContactDetails();
                        $contactNumber 				= $contactDetails->getContactMainPhone();
                        $return = array('contact_number' => $contactNumber, 'contact_hierarchy_level' => 'university');
                    }
                }
                break;
            case 'department':
                $departmentObj				= $data['listingObj'];
                $mainLocationObj			= $departmentObj->getMainLocation();
                $contactDetails 			= $departmentObj->getMainLocation()->getContactDetail();
                $contactNumber 				= $contactDetails->getContactMainPhone();
                if(!empty($contactNumber)) {
                    $return = array('contact_number' => $contactNumber, 'contact_hierarchy_level' => 'department');
                } else if(empty($contactNumber) && $applyHierarchyCheck) {
                    $universityId 				= $departmentObj->getUniversityId();
                    $abroadUniversityRepository = $listingBuilder->getUniversityRepository();
                    $universityObject 			= $abroadUniversityRepository->find($universityId);
                    $contactDetails 			= $universityObject->getContactDetails();
                    $contactNumber 				= $contactDetails->getContactMainPhone();
                    $return = array('contact_number' => $contactNumber, 'contact_hierarchy_level' => 'university');
                }
                break;
            case 'university':
                $universityObject 			= $data['listingObj'];
                $contactDetails 			= $universityObject->getContactDetails();
                $contactNumber 				= $contactDetails->getContactMainPhone();
                $return = array('contact_number' => $contactNumber, 'contact_hierarchy_level' => 'university');
                break;
        }
        return $return;
    }

    public function parseWebsiteLinkForView($url) {
        $hostEntry = parse_url($url);
        $URL = $url;
        if(empty($hostEntry['scheme'])) {
            $URL = "http://" . $url;
        }
        return $URL;
    }

    function getIndianDisplableAmount($amount, $decimalPointPosition = 1,$handleZero=false)
    {
        if($amount < 1000)
            $finalAmount = number_format($amount, $decimalPointPosition, '.', '');
        else if($amount < 100000)
        {
            $finalAmount = $amount / 1000;
            $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
            $finalAmount .= " Thousand";//($finalAmount == 1)? " Thousand" : " Thousands";
        }
        else if($amount < 10000000)
        {
            $finalAmount = $amount / 100000;
            $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
            $finalAmount .= ($finalAmount == 1)? " Lakh" : " Lakhs";
        }
        else
        {
            $finalAmount = $amount / 10000000;
            $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
            $finalAmount .= ($finalAmount == 1)? " Crore" : " Crores";
        }
        if($handleZero && !($finalAmount > 0))
        {
            $finalAmount = "No tuition fees";
        }
        else
        {
            $finalAmount = "Rs ".$finalAmount;
        }
        return $finalAmount;
    }

    function formatMoneyAmount($amount, $currencyId, $roundOffFlag = 0)
    {
        if($currencyId == 1)
            $currencyType = 'en_IN';
        else
            $currencyType = 'en_US.UTF-8';
        setlocale(LC_MONETARY, $currencyType);
        if($roundOffFlag)
        {
            $amount = money_format('%!.0n', $amount);
        }
        else if (ctype_digit($amount) )
        {
            // if whole number use this format
            $amount = money_format('%!.0n', $amount);
        }
        else
        {
            // if not whole number
            $amount = money_format('%!i', $amount);
        }
        return $amount;
    }

    public function getUniversityCourses($universityId = NULL, $groupBy = 'all') {
        if(empty($universityId)){
            return array();
        }

        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder 			= new ListingBuilder;

        $returnData = array();
        $courseIds 	= array();
        if($groupBy == "department")
        {
            $data = $this->getUniversityCoursesGroupByDepartments($universityId);
            $coursesOfferedByDepartment = $data['department']['courses'];
            $courseIds 					= $data['department']['course_ids'];
            $returnData['department']['courses'] 	= $coursesOfferedByDepartment;

            $departmentIds = array();
            foreach($coursesOfferedByDepartment as $departmentId => $courses) {
                $departmentIds[] = $departmentId;
            }

            $abroadInstituteRepository 	= $listingBuilder->getAbroadInstituteRepository();
            $departmentDetails = array();
            if(!empty($departmentIds)){
                $departmentDetails 			= $abroadInstituteRepository->findMultiple($departmentIds);
            }
            foreach($departmentDetails as $department){
                $returnData['department']['department_titles'][$department->getId()] = $department->getName();
                $returnData['department']['department_urls'][$department->getId()] = $department->getURL();
            }
        } else if($groupBy == "stream") {
            $data = $this->getUniversityCoursesGroupByStream($universityId);
            $coursesOfferedByStream 			= $data['stream']['courses'];
            $courseIds							= $data['stream']['course_ids'];
            $returnData['stream']['courses'] 				= $coursesOfferedByStream;
            $returnData['stream']['course_ids'] 			= $courseIds;
        }

        $urls = $exams = $fees = array();
        $courseDetails = array();
        if(!empty($courseIds)) {
            $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
            if(!empty($courseIds)){
                $courseDetails 			= $abroadCourseRepository->findMultiple($courseIds);
            }
            foreach($courseDetails as $courseObj){
                $urls['courses'][$courseObj->getId()] = $courseObj->getURL();
                if($groupBy == "stream")
                {
                    $exams[$courseObj->getId()] = $courseObj->getEligibilityExams();
                    $fees[$courseObj->getId()] = $this->getIndianDisplableAmount($this->convertCurrency($courseObj->getTotalFees()->getCurrency(), 1, $courseObj->getTotalFees()->getValue()),1);
                }
            }
        }
        $returnData['urls'] = $urls;
        if($groupBy == "stream")
        {
            $returnData['exams'] = $exams;
            $returnData['fees'] = $fees;
            $returnData['courses'] = $courseDetails;
        }
        return $returnData;
    }

    public function getUniversityCoursesGroupByStream($universityId = NULL) {
        if(empty($universityId)){
            return array();
        }
        $this->CI->load->model('listing/abroadcoursefindermodel');
        $abroadCourseFinderModel = new abroadcoursefindermodel();

        $courses 							= $abroadCourseFinderModel->getCoursesOfferedByUniversity($universityId, 'stream');
        $courseOfferedByStream 				= $courses['courses'];
        $courseIdsByStream 					= $courses['course_ids'];

        //$snapshotCourses 					= $abroadCourseFinderModel->getSnapShotCoursesOfferedByUniversity($universityId, 'stream');
        $snapshotCourses = array();
        $snapshotCoursesOfferedByStream		= $snapshotCourses['courses'];
        $snapshotCourseIdsByStream			= $snapshotCourses['course_ids'];

        $data = array();
        $data['stream']  = array();
        $data['stream']['courses'] 				= $courseOfferedByStream;
        $data['stream']['course_ids'] 			= $courseIdsByStream;
        $data['stream']['snapshot_courses'] 	= $snapshotCoursesOfferedByStream;
        $data['stream']['snapshot_course_ids'] 	= $snapshotCourseIdsByStream;
        return $data;
    }


    public function getUniversityCourseNames($universityId = NULL) {
        if(empty($universityId)){
            return array();
        }
        $this->CI->load->model('listing/abroadcoursefindermodel');
        $abroadCourseFinderModel = new abroadcoursefindermodel();

        $courses = $abroadCourseFinderModel->getCoursesOfferedByUniversity($universityId, 'list');
        $result = array();
        foreach ($courses['courses'] as $key => $value) {
            $result['courses'][$value['courseID']] = $value['courseName'];
        }
        $result['courseIds'] = $courses['course_ids'];
        return $result;
    }

    public function getUniversityCoursesGroupByDepartments($universityId = NULL,$includeVirtualDept = false) {
        if(empty($universityId)){
            return array();
        }
        $this->CI->load->model('listing/abroadcoursefindermodel');
        $abroadCourseFinderModel = new abroadcoursefindermodel();

        $courses = array();
        $courses 						= $abroadCourseFinderModel->getCoursesOfferedByUniversity($universityId, 'department',$includeVirtualDept);
        $courseOfferedByDepartment 		= $courses['courses'];
        $courseIdsByDepartment 			= $courses['course_ids'];
        uasort($courseOfferedByDepartment, function($a, $b) {
            $aCount = 0;
            $bCount = 0;
            foreach($a as $key => $values) {
                $aCount += count($values);
            }
            foreach($b as $key => $values) {
                $bCount += count($values);
            }
            if($aCount == $bCount){
                return 0;
            }
            return ($aCount < $bCount) ? 1 : -1;
        });

        $data = array();
        $data['department']  = array();
        $data['department']['courses'] 			= $courseOfferedByDepartment;
        $data['department']['course_ids'] 		= $courseIdsByDepartment;
        return $data;
    }

    public function getAbroadExamsMasterListFromCache($hideNewExam =false) {
        $this->CI->load->library('listing/cache/AbroadListingCache');
        $abroadListingCacheLib  = new AbroadListingCache();
        $examsList 				= $abroadListingCacheLib->getAbroadExams();
        if(empty($examsList)) {
            $abroadCMSModelObj 	= $this->CI->load->model('listingPosting/abroadcmsmodel');
            $examsList 			= $abroadCMSModelObj->getAbroadExamsMasterList();
            $abroadListingCacheLib->storeAbroadExams($examsList);
        }

        if($hideNewExam == true) {
            $this->CI->config->load('studyAbroadCommonConfig');
            $hideExamIds = $this->CI->config->item('HIDE_EXAM_IDS');
            foreach ($examsList as $key => $exam) {
                if(in_array($exam['examId'], $hideExamIds)) {
                    unset($examsList[$key]);
                }
            }
        }

        return $examsList;
    }

    public function getDepartmentCourses($departmentId) {
        if(empty($departmentId)){
            return array();
        }

        $this->CI->load->model('listing/abroadcoursefindermodel');
        $abroadCourseFinderModel = new abroadcoursefindermodel();

        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;

        $courses = $abroadCourseFinderModel->getCoursesOfferedByInstitute($departmentId);

        $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
        foreach($courses as $key=>$courseByCategory) {
            $courseIds = array();
            foreach($courseByCategory as $course) {
                $courseIds[] = $course['course_id'];
            }

            if(!empty($courseIds)) {

                $courseDetails = array();
                if(!empty($courseIds)){
                    $courseDetails = $abroadCourseRepository->findMultiple($courseIds);
                }
                $i = 0;
                foreach($courseDetails as $courseObj){
                    $courses[$key][$i]['course_url'] = $courseObj->getURL();
                    $i++;
                }
            }
        }

        return ($courses);
    }


    public function getSimilarCourses($universityId, $categoryId, $courseObject, $abroadCourseRepository, $courseType) {

        $abroadListingCacheLib = $this->CI->load->library('listing/cache/AbroadListingCache');
        if($courseType == 'snapshot'){
            $listingId = $courseObject['course_id'];
            $similarCoursesData = $abroadListingCacheLib->getSimilarCoursesDataOfUniversity('snapshot_'.$listingId);
        }else{
            $listingId = $courseObject->getId();
            $similarCoursesData = $abroadListingCacheLib->getSimilarCoursesDataOfUniversity('regular_'.$listingId);
        }
        $similarCoursesData =array();
        if(empty($similarCoursesData) ) {
            $similarCourseIds = array();
            $similarCourses = $this->abroadListingModel->getSimilarCoursesOfUniversity($universityId, $categoryId);
            if($courseType == 'snapshot'){
                $courseIdChk = -1;
            }else{
                $courseIdChk = $courseObject->getId();
            }
            foreach($similarCourses as $courseIdObj)
            {
                if($courseIdChk != $courseIdObj["course_id"])
                    $similarCourseIds[] = $courseIdObj["course_id"];
            }

            //$similarCourseIds = array_slice($similarCourseIds, 0, 6);

            if(!empty($similarCourseIds)) {
                $similarCoursesObj = $abroadCourseRepository->findMultiple($similarCourseIds);
            }

            $i = 0;

            foreach($similarCoursesObj as $courseObj)
            {
                $courseId 					= $courseObj->getId();
                $courseIdsList[]				= $courseId;
                $similarCoursesData[$courseId]["viewcount"] = 0;
                $similarCoursesData[$courseId]["id"] 	= $courseId;
                $similarCoursesData[$courseId]["url"] 	= $courseObj->getURL();
                $similarCoursesData[$courseId]["name"] 	= $courseObj->getName();
                $i++;
            }

            // get the view of the courses given
            if(!empty($courseIdsList))
            {
                $listingsViewCount = $this->abroadListingModel->getLisitngViewCountForLastNnoOfDays("course", $courseIdsList, 7);
                // assign the viewcount of the courses in the similarcoursesdata array
                foreach($listingsViewCount as $courseId=>$count)
                {
                    $similarCoursesData[$courseId]["viewcount"] = $count;
                }
                // get all the viewcounts of the courses with index as course-id for the purpose of sorting
                foreach($similarCoursesData as $key=>$value)
                {
                    $viewCountWithKey[$key] = $value["viewcount"];
                }
                // sort the data according to the viewcount
                array_multisort($viewCountWithKey, SORT_DESC, $similarCoursesData);
            }
            if($courseType == 'snapshot'){
                $abroadListingCacheLib->storeSimilarCoursesDataOfUniversity('snapshot_'.$listingId, $similarCoursesData);
            }else{
                $abroadListingCacheLib->storeSimilarCoursesDataOfUniversity('regular_'.$listingId, $similarCoursesData);
            }
        }
        $similarCoursesData = array_slice($similarCoursesData,0,6);
        return $similarCoursesData;
    }


    public function getVisaGuideDetailForCountry($countryId) {
        $abroadListingCacheLib = $this->CI->load->library('listing/cache/AbroadListingCache');
        $visaGuideDetails = $abroadListingCacheLib->getVisaGuideDetail($countryId);

        if($visaGuideDetails == "") {
            $visaGuideDetails = $this->abroadListingModel->getVisaGuideDetail($countryId);
            if($visaGuideDetails == "") {
                $visaGuideDetails = "NO_GUIDE_FOUND";
            }
            $abroadListingCacheLib->storeVisaGuideDetail($countryId, $visaGuideDetails);
        } elseif($visaGuideDetails == "NO_GUIDE_FOUND") {
            $visaGuideDetails = "";
        }

        return $visaGuideDetails;
    }

    public function addSnapshotCourseRequest($sessionId,$userId,$snapshotCourseId){
        return $this->abroadListingModel->addSnapshotCourseRequest($sessionId,$userId,$snapshotCourseId);
    }

    public function getViewCountForCourseListByDays($courseList = array(),$days=7) {
        $abroadListingCacheLib 	= $this->CI->load->library('listing/cache/AbroadListingCache');
        $viewCountForCourseList = $abroadListingCacheLib->getViewCountForCourses($days);
        if(empty($viewCountForCourseList)) {
            $viewCountForCourseList = $this->abroadListingModel->getViewCountDumpForListingType('course', $days);
            if(!empty($viewCountForCourseList)) {
                $abroadListingCacheLib->storeViewCountForCourses($viewCountForCourseList,$days);
            }
        }
        if($courseList == -1) {
            return $viewCountForCourseList;
        } else {
            $data = array();
            foreach($courseList as $courseId) {
                if(array_key_exists($courseId, $viewCountForCourseList)) {
                    $data[$courseId] = $viewCountForCourseList[$courseId];
                } else {
                    $data[$courseId] = 0;
                }
            }
            return $data;
        }
    }

    public function getCourseCountOfUniversities( $universityIds )
    {
        // return empty resultset if no university id is provided
        if(empty($universityIds))
            return array();

        // get course count for provided universities
        $resultset = $this->abroadCourseModel->getCourseCountOfUniversities( $universityIds );

        $courseCountArr = array();
        foreach( $resultset as $universityRow )
        {
            $courseCountArr[$universityRow["university_id"]] = $universityRow["course_num"];
        }

        return $courseCountArr;
    }

    public function getSnapshotRequestFlag($userId,$snapshotCourseId){
        return $this->abroadListingModel->getSnapshotRequestFlag($userId,$snapshotCourseId);
    }
    /*
     * changelog : added courseObj as optional second param to avoid a cache hit to load that,
     * also kept the courseId because the function is quite old and being used already in various places
     */
    public function getCourseBrochureUrl($courseId, $courseObj = NULL){
        if(empty($courseId)){
            return '';
        }
        if(is_null($courseObj) || !($courseObj instanceof AbroadCourse))
        {
            $this->CI->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder;
            $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
            $courseObj = $abroadCourseRepository->find($courseId);
            $brochureUrl = $courseObj->getRequestBrochure();
        }
        // if not available get from db
        if(empty($brochureUrl)){
            $brochureUrl = $this->abroadCourseModel->getShikshaCourseBrochureUrl($courseId);
            if(!empty($brochureUrl)){
                $brochureUrl = MEDIAHOSTURL.$brochureUrl;
            }
        }
        return $brochureUrl;
    }

    public function getViewCountForListingsByDays($listingTypeIds = array(), $listingType = 'course', $pastDays=21,$cacheData=false){
        $listingTypeIds = array_filter($listingTypeIds);
        if($cacheData === false)
        {
            $abroadListingCacheLib 	= $this->CI->load->library('listing/cache/AbroadListingCache');
            $viewCountForListing = $abroadListingCacheLib->getViewCountForListings($listingType, $pastDays);
            if(empty($viewCountForListing)){
                //$viewCountForListing = $this->abroadListingModel->getViewCountDumpForListingType($listingType, $pastDays, $listingTypeIds);
                $viewCountForListing = $this->abroadListingModel->getViewCountDumpForListingType($listingType, $pastDays);
                if(!empty($viewCountForListing)){
                    $abroadListingCacheLib->storeViewCountForListings($viewCountForListing, $listingType, $pastDays);
                }
            }
        }
        if($listingTypeIds == -1){
            return $viewCountForListing;
        }else{
            $data = array();
            foreach($listingTypeIds as $listingTypeId) {
                if(array_key_exists($listingTypeId, $viewCountForListing)) {
                    $data[$listingTypeId] = $viewCountForListing[$listingTypeId];
                } else {
                    $data[$listingTypeId] = 0;
                }
            }
            return $data;
        }
        //return $this->abroadListingModel->getViewCountDumpForListingType($listingType, $pastDays, $listingTypeIds);
    }

    public function getViewCountCacheData($listingType = 'course', $pastDays=21)
    {
        $abroadListingCacheLib 	= $this->CI->load->library('listing/cache/AbroadListingCache');
        $viewCountForListing = $abroadListingCacheLib->getViewCountForListings($listingType, $pastDays);
        if(empty($viewCountForListing)){
            //$viewCountForListing = $this->abroadListingModel->getViewCountDumpForListingType($listingType, $pastDays, $listingTypeIds);
            $viewCountForListing = $this->abroadListingModel->getViewCountDumpForListingType($listingType, $pastDays);
            if(!empty($viewCountForListing)){
                $abroadListingCacheLib->storeViewCountForListings($viewCountForListing, $listingType, $pastDays);
            }
        }
        return $viewCountForListing;
    }


    public function getCategoryOfAbroadCourse($courseId){
        if(empty($this->abroadListingModel)){
            $this->load->model('listing/abroadlistingmodel');
            $this->abroadListingModel = new abroadlistingmodel();
        }
        $subcategoryId 	= $this->abroadListingModel->getSubCategoryOfAbroadCourse($courseId);
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $builderObj	= new CategoryBuilder;
        $repoObj 	= $builderObj->getCategoryRepository();
        if(is_array($courseId)){
            foreach($subcategoryId as $key=>$value){
                if($value['subcat'] > 0 && $value['subcat']!=''){
                    $subCatObj	= $repoObj->find($value['subcat']);
                    $categoryId	= $subCatObj->getParentId();
                    $subcategoryId[$value['course_id']] = array('categoryId' => $categoryId, 'subcategoryId' => $subCatObj->getId());
                }
                unset($subcategoryId[$key]);
            }
            return $subcategoryId;

        }else{
            if($subcategoryId >0 && $subcategoryId!=''){
                $subCatObj 	= $repoObj->find($subcategoryId);
                $categoryId	= $subCatObj->getParentId();
            }
            return array('categoryId' => $categoryId, 'subcategoryId' => $subcategoryId);
        }
    }
    /*
     * function to just check if a given (single) university has a counsellor
     */
    public function checkIfUniversityHasCounsellor($universityIds)
    {
        //return $this->abroadListingModel->checkIfUniversityHasCounsellor($universityId);
        $counsellorData = $this->abroadListingModel->checkIfUniversityHasCounsellor($universityIds);
        $result;
        foreach($counsellorData as $data){
            $result[$data['university_id']] = $data['counsellor_id'];
        }
        return $result;
    }
    /*
     * function to get cousellor details of universities
     * (accepts single id as well as array)
     */
    public function getCounsellorsForUniversities($universityIds,$forRMSType = "")
    {
        $query_res = $this->abroadListingModel->getCounsellorsForUniversities($universityIds,$forRMSType);
        if(count($query_res) == 0)
        {
            return false;
        }
        $result = array();
        foreach($query_res as $res)
        {
            $result[$res['university_id']] = array(
                'counsellor_id' 		=> $res['counsellor_id' 	  ],
                'counsellor_name' 		=> $res['counsellor_name' 	  ],
                'counsellor_email' 		=> $res['counsellor_email' 	  ],
                'counsellor_mobile' 		=> $res['counsellor_mobile' 	  ],
                'counsellor_manager_id' 	=> $res['counsellor_manager_id'   ],
                'counsellor_manager_name'  	=> $res['counsellor_manager_name' ],
                'counsellor_manager_email' 	=> $res['counsellor_manager_email'],
                'counsellor_rms_type'		=> $res['RMSType']
            );
        }
        if(count($result) == 0)	{
            return false;
        }
        else {
            return $result;
        }
    }
    /*
     * this function calls the model to get the number of unique callback responses created today by given user
     */
    public function getUserCallbackResponseCountForToday($userId)
    {
        return $this->abroadListingModel->getUserCallbackResponseCountForToday($userId);
    }

    public function getUniversityNameOfDepartment($deptId){
        return $this->abroadListingModel->getUniversityNameOfDepartment($deptId);
    }
    /*
     * function to get download links for course/univ brochures in case of mails(i.e. DL links are sent instead of attachments)
     * @input: $data array that has user id , listing type (only course & univ supported for now as depts dont have brochures on them), listing type id
     */
    public function getAbroadListingBrochureDownloadURL($data)
    {
        // create an encrypted message that can be attached to url
        $encodedMsg = $this->getEncodedMsgForBrochureDownloadURL( $data );
        // add to url
        $url = SHIKSHA_STUDYABROAD_HOME."/listing/abroadListings/downloadAbroadListingsBrochureFromMailLink/".$encodedMsg."/response_abroad_mail_download";
        return $url;
    }
    /**
     * Function to get the encoded message for Brochure URL to be sent to the user in mails
     **/
    public function getEncodedMsgForBrochureDownloadURL( $data )
    {
        $salt 		= "noitpyrcneroftlasdaorbayduts";//studyabroadsaltforencryption
        $message 		= $data['userId'].$salt.$data['listingType'].$salt.$data['listingTypeId'];
        $encryptedMsg	= base64_encode($message);
        $encryptedMsg 	= urlencode($encryptedMsg);
        return $encryptedMsg;
    }

    /**
     * Function to get the original message out of encoded message
     **/
    public function getDecodedMsgForBrochureDownloadURL( $encryptedMsg )
    {
        $salt 		= "noitpyrcneroftlasdaorbayduts";
        $decryptedMsg 	= urldecode($encryptedMsg);
        $decryptedMsg	= base64_decode($decryptedMsg);
        $message		= explode($salt, $decryptedMsg);
        return array('userId' 	=> $message[0],
            'listingType' 	=> $message[1],
            'listingTypeId'=> $message[2]);
    }

    /*
     * Author	: Abhinav
     * Purpose	: To get other courses of university other than the current course refrenced
     */
    public function getOtherCoursesOfUniversity($universityObj,$courseObj,$categoryId,$subCategoryId)
    {
        if($universityObj == NULL || empty($universityObj) || $courseObj == NULL || empty($courseObj) || !($categoryId > 0) || !($subCategoryId > 0))
        {
            return array();
        }

        $otherCoursesArray 	=  array();
        $coursesArray 		=  array();
        $ldbCourseArray 	=  array();

        $courseLevel = $courseObj->getCourseLevel1Value();
        //echo "<br/>Course Level : ".$courseLevel;
        //echo "<br/>category_ID : ".$categoryId;
        //echo "<br/>sub-category_ID : ".$subCategoryId;
        //echo "<br/>LDB Course_ID : ".$courseObj->getLDBCourseId();
        //echo "<br/>Desired Course_ID : ".$courseObj->getDesiredCourseId();
        $ldbCourseId = $courseObj->getDesiredCourseId();
        $checkForCourses = array(
            'ldbCourseId'		=> $courseObj->getDesiredCourseId(),
            'categoryId'		=> $categoryId,
            //'subCategoryId'	=> $subCategoryId,
            'courseLevel'		=> $courseLevel,
            'univerityId'		=> $universityObj->getId()
        );

        //fetch other courses details i.e. courseId,categoryId,SubCatId,ldbCourseId

        $result = $this->abroadListingModel->getCoursesWithCategories($checkForCourses);
        //_p($result);
        foreach($result as $dataArray)
        {
            $coursesArray[] = $dataArray['course_id'];
        }
        // get top viewed courses in last 21 days
        $coursesArray = $this->getViewCountForListingsByDays($coursesArray,'course',21);

        foreach($result as &$dataArray)
        {
            $dataArray['viewCount'] = $coursesArray[$dataArray['course_id']];
        }


        // perform sorting on courses on the resultant array after fetching from db
        usort($result,function($a,$b) use($ldbCourseId,$subCategoryId,$categoryId)
        {
            global $studyAbroadPopularCourseToCategoryMapping;
            $desiredCourses = array_keys($studyAbroadPopularCourseToCategoryMapping);
            if($a['ldb_course_id'] == $ldbCourseId && $b['ldb_course_id'] != $ldbCourseId && in_array($ldbCourseId,$desiredCourses))
            {
                return -1;
            }
            //if b is a ldb course but a is not then place b in front
            elseif($b['ldb_course_id'] == $ldbCourseId && $a['ldb_course_id'] != $ldbCourseId && in_array($ldbCourseId,$desiredCourses))
            {
                return 1;
            }
            //if both are ldb courses
            elseif($a['ldb_course_id'] == $ldbCourseId && $b['ldb_course_id'] == $ldbCourseId && in_array($ldbCourseId,$desiredCourses))
            {
                //if view count of a is greater than b then place a
                if($a['viewCount'] > $b['viewCount'])
                {
                    return -1;
                }
                //if view count of b > a then place b infront
                elseif($a['viewCount'] < $b['viewCount'])
                {
                    return 1;
                }
                else //do nothing because both are equal
                {
                    return 0;
                }
            }
            //for non ldb courses sorting based on subcategory ids
            elseif($a['sub_category_id'] == $subCategoryId && $b['sub_category_id'] != $subCategoryId)
            {
                return -1;
            }
            elseif($b['sub_category_id'] == $subCategoryId && $a['sub_category_id'] != $subCategoryId)
            {
                return 1;
            }
            elseif($a['sub_category_id'] == $subCategoryId && $b['sub_category_id'] == $subCategoryId)
            {
                if($a['viewCount'] > $b['viewCount'])
                {
                    return -1;
                }
                elseif($a['viewCount'] < $b['viewCount'])
                {
                    return 1;
                }
                else
                {
                    return 0;
                }
            }
            //for non ldb courses sorting based on category ids
            elseif($a['category_id'] == $categoryId && $b['category_id'] != $categoryId)
            {
                return -1;
            }
            elseif($b['category_id'] == $categoryId && $a['category_id'] != $categoryId)
            {
                return 1;
            }
            elseif($a['category_id'] == $categoryId && $b['category_id'] == $categoryId)
            {
                if($a['viewCount'] > $b['viewCount'])
                {
                    return -1;
                }
                elseif($a['viewCount'] < $b['viewCount'])
                {
                    return 1;
                }
                else
                {
                    return 0;
                }
            }

        });

        $coursesToShow = array();
        //preparing array of course_ids to show, with max 5 courses to be shown
        foreach($result as $resultData)
        {
            if(!(in_array($resultData['course_id'],$coursesToShow)) && $courseObj->getId() != $resultData['course_id'])
            {
                $coursesToShow[] = $resultData['course_id'];
                if(count($coursesToShow) == 5)
                {
                    break;
                }
            }
        }

        $this->CI->load->builder('listing/ListingBuilder');
        $listingBuilder = new ListingBuilder;
        $abroadCourseRepo = $listingBuilder->getAbroadCourseRepository();

        //if we have available coursesids to be shown
        if(!empty($coursesToShow))
        {
            //fetch all the course objects for all the courseIds we have in the array
            $otherCoursesArray['courses'] = $abroadCourseRepo->findMultiple($coursesToShow);
            //foreach course object
            foreach($otherCoursesArray['courses'] as $courseDataObj)
            {
                //fetch fees details
                $feesCurrency 	= $courseDataObj->getTotalFees()->getCurrency();
                $feesValue 		= $courseDataObj->getTotalFees()->getValue();
                $feesValue 		= $this->convertCurrency($feesCurrency,1,$feesValue);
                if($feesValue)
                {
                    //set data into coursesData
                    $otherCoursesArray['coursesData'][$courseDataObj->getId()]['fees'] = $this->getIndianDisplableAmount($feesValue,2);
                }

                //fetch job details and salary details
                $jobProfile = $courseDataObj->getJobProfile();
                if($jobProfile->getAverageSalary() != "")
                {
                    $averageSalaryVal   	= $jobProfile->getAverageSalary();
                    $averageSalaryCurr  	= $jobProfile->getAverageSalaryCurrencyId();
                    $averageSalaryFinal 	= $this->convertCurrency($averageSalaryCurr, 1, $averageSalaryVal);
                    $averageSalaryFinal 	= $this->getIndianDisplableAmount($averageSalaryFinal, 2);
                    //set data into coursesData
                    $otherCoursesArray['coursesData'][$courseDataObj->getId()]['salary'] = $averageSalaryFinal;
                }


                //for each course object fetch all eligibility exams details in sorted order
                //since the object being processed is returned as an array we use reset
                $courseDataObj = reset($this->sortEligibilityExamForAbroadCourses(array($courseDataObj)));

                $courseDataObj = reset($this->rearrangeCustomExams(array($courseDataObj)));

                //populate array with top 2 non custom exams
                $i=0;
                foreach($courseDataObj->getEligibilityExams() as $examObj)
                {
                    if($i==2)break;

                    if($examObj->getId() != -1)
                    {
                        if($examObj->getCutoff() == "N/A")
                        {
                            $cutOffText = "Accepted";
                        }
                        else
                        {
                            $cutOffText = $examObj->getCutoff();
                        }
                        $otherCoursesArray['coursesData'][$courseDataObj->getId()]['eligibilityExam'][$examObj->getName()] = $cutOffText ;
                        $i++;
                    }
                }
            }
        }
        return $otherCoursesArray;
    }

    /*
        * Author   : Abhinav
        * Purpose  : Sort Eligibility Exams as per new Order.
        * Params   : Course Objects array,course category,subcategory,desired course ID
        *
    */
    function sortEligibilityExamForAbroadCourses($courseObjectArray = array(),$categoryId = '', $reference = false)
    {
        //no course objects
        if(empty($courseObjectArray))
        {
            return array();
        }
        $this->CI->config->load('studyAbroadListingConfig');

        foreach($courseObjectArray as $courseId=>&$courseObject)
        {

            //if its not a Abroad Course Object
            if(!($courseObject instanceof AbroadCourse))
            {
                continue;
            }

            //if we have no categoryId
            if($categoryId == '')
            {
                //fetch subcategoryId from course Object
                $subCategory = $courseObject->getCourseSubCategory();
                // check added to prevent categoryId cannot be zero issues
                if(!($subCategory>0))
                {
                    $commonStudyAbroadLib   = $this->CI->load->library('common/studyAbroadCommonLib');
                    $subject = 'Subcategory not found';
                    $body = 'Subcategory not found in course: <br><pre>'.print_r($courseObject,true).'</pre><br/><br/>Regards,<br/>SA Team';
                    $commonStudyAbroadLib->selfMailer($subject,$body);
                    unset($courseObjectArray[$courseId]);
                    continue;
                }
                if(empty($this->categoryRepoObj)){
                    $this->CI->load->builder('CategoryBuilder','categoryList');
                    $builderObj	= new CategoryBuilder;
                    $this->categoryRepoObj 	= $builderObj->getCategoryRepository();
                }
                //from subcategoryId fetch the object from repo and then get its categoryId
                if($subCategory > 0){
                    $subcategoryObj = $this->categoryRepoObj->find($subCategory);
                    $courseCategoryId = $subcategoryObj->getParentId();
                }
            }
            else
            {
                $courseCategoryId = $categoryId;
            }

            if($courseCategoryId == '')
            {
                continue;
            }

            $requestData = array();
            $requestData['LDBCourseId'] = $courseObject->getDesiredCourseId();
            $requestData['categoryId'] = $courseCategoryId;
            $requestData['courseLevel'] = $courseObject->getCourseLevel1Value();

            $examOrder = $this->getExamOrderByDesiredCourseAndCategory($requestData);

            //get the eligibity exams
            $exams = $courseObject->getEligibilityExams();
            //sort the exams based on exam order as defined in the config
            usort($exams, function ($a,$b) use($examOrder)
            {
                if($examOrder[$a->getName()] > $examOrder[$b->getName()])
                {
                    return 1;
                }
                else
                {
                    return -1;
                }
            });
            $courseObject->__set('exams',$exams);
        }
        if(!$reference) return $courseObjectArray;
    }

    public function getPopularCourseVisibilityForCountryPage($countryId){
        $data = $this->abroadListingModel->getPopularCourseVisibilityForCountryPage($countryId);
        return $data;
    }

    public function getCourseIdsOfUniversities( $universityIds )
    {
        // return empty resultset if no university id is provided
        if(empty($universityIds))
            return array();
        // get course count for provided universities
        $resultset = $this->abroadCourseModel->getCourseIdOfUniversities( $universityIds );

        $courseCountArr = array();
        foreach( $resultset as $universityRow )
        {
            $courseCountArr[$universityRow["university_id"]][] = $universityRow["course_id"];
        }
        return $courseCountArr;
    }

    public function getConsultantData($listingType,$listingObj){

        $univId = '';

        if($listingType == "course" || $listingType == "department")
        {
            if($listingType == "course" && !isValidAbroadCourseObject($listingObj))
            {
                return array();
            }
            elseif($listingType == "department" && !isValidAbroadDepartmentObject($listingObj))
            {
                return array();
            }
            $univId = $listingObj->getUniversityId();
        }
        else
        {
            if(!isValidAbroadUniversityObject($listingObj))
            {
                return array();
            }
            $univId = $listingObj->getId();
        }

        if(empty($univId))
        {
            return array();
        }

        $data = $this->_getConsultantDataForUniversity($univId);
        /* if all the consultant university mappings for this universityId have been deleted,
         * then return blank array.
         */
        if(empty($data))
        {
            return array();
        }

        if($listingType == "university")
        {
            $data = $this->_cleanupConsultantDataForUniversity($listingObj,$data);
        }
        elseif($listingType == "department")
        {
            $data = $this->_cleanupConsultantDataForDepartment($listingObj,$data,$univId);
        }
        elseif($listingType == "course")
        {
            $data = $this->_cleanupConsultantDataForCourse($listingObj,$data,$univId);
        }

        //Get default Address
        $this->CI->load->entity("consultantProfile/Consultant");
        $consultantObj = new Consultant;

        foreach($data as $consultantId => $consultantData)
        {
            foreach($consultantData['regions'] as $regionId => $regionName)
            {
                $regionData['name'] = $regionName;
                $defaultOfficeData = $this->getDefaultOfficeForConsultantByRegion($consultantId,$regionId);
                $regionData['office'] = $defaultOfficeData;
                $data[$consultantId]['regions'][$regionId] = $regionData;
            }
            $consultantObj->__set('consultantId',$consultantId);
            $consultantObj->__set('name',$consultantData['consultantName']);
            $data[$consultantId]['consultantProfileUrl'] = $consultantObj->getCanonicalUrl();
        }
        return $data;
    }

    private function _getConsultantDataForUniversity($univId){

        $data = $this->abroadListingModel->getActiveConsultantsForUniversity($univId);
        $result = array();
        foreach($data as $row){
            if(empty($result[$row['consultantId']])){       //Just add the new region if this guy exists
                $result[$row['consultantId']] = array('regions'=>array());
            }
            $result[$row['consultantId']]['regions'][$row['regionId']]  = $row['regionName'];
            $result[$row['consultantId']]['consultantId']               = $row['consultantId'];
            $result[$row['consultantId']]['consultantName']             = $row['consultantName'];
            $result[$row['consultantId']]['consultantLogo']             = $row['consultantLogo'];
            $result[$row['consultantId']]['isOfficialRepresentative']   = $row['isOfficialRepresentative'];
        }
        return $result;
    }

    private function _cleanupConsultantDataForUniversity($universityObj,$data){
        $excludedCourses = $this->abroadListingModel->getExcludedCoursesForUniversity($universityObj->getId());
        $universityCourses = $this->abroadListingModel->getCourseIdsForUniversity($universityObj->getId());
        foreach($data as $consultantId => $consultantData){


            if(!empty($excludedCourses[$consultantId]) && count(array_diff($universityCourses,$excludedCourses[$consultantId])) == 0){
                unset($data[$consultantId]);
            }
            else{
                $data[$consultantId]['excludedCourses'] = $excludedCourses[$consultantId];
            }
        }
        return $data;
    }

    private function _cleanupConsultantDataForDepartment($departmentObj,$data,$univId){
        $excludedCourses = $this->abroadListingModel->getExcludedCoursesForUniversity($univId);
        $departmentCourses = $this->abroadListingModel->getCourseIdsForDepartment($departmentObj->getId());
        foreach($data as $consultantId => $consultantData){
            if(!empty($excludedCourses[$consultantId]) && count(array_diff($departmentCourses,$excludedCourses[$consultantId])) == 0){
                unset($data[$consultantId]);
            }
            else{
                $data[$consultantId]['excludedCourses'] = $excludedCourses[$consultantId];
            }
        }
        return $data;
    }

    private function _cleanupConsultantDataForCourse($courseObj,$data,$univId){
        $excludedCourses = $this->abroadListingModel->getExcludedCoursesForUniversity($univId);
        foreach($excludedCourses as $consultantId=>$courseIds){
            if(in_array($courseObj->getId(),$courseIds)){
                unset($data[$consultantId]);
            }
            else{
                $data[$consultantId]['excludedCourses'] = $excludedCourses[$consultantId];
            }
        }
        return $data;
    }

    public function getDefaultOfficeForConsultantByRegion($consultantId,$regionId){
        $this->CI->load->builder("ConsultantPageBuilder", "consultantProfile");
        $consultantPageBuilder   = new ConsultantPageBuilder();
        $consultantRepository = $consultantPageBuilder->getConsultantRepository();
        $consObj = $consultantRepository->find($consultantId);
        $officeData = $consObj[$consultantId]->getDefaultBranches();
        return $officeData[$consultantId][$regionId];
    }

    public function getExcludedCoursesForUniversity($univsersityId)
    {
        $result = $this->abroadListingModel->getExcludedCoursesForUniversity($univsersityId);
        return $result;
    }
    /*
    *This function takes the array of universities Id on the category page
    *and finds if the consultant is available for the universities to show the link for help
    */
    public function checkIfUniversityHasConsultants($universityIds)
    {
        $this->CI->load->library('subscription_client');
        $sumsProductObj   =  new Subscription_client();
        $consultantData   = $this->abroadListingModel->checkIfUniversityHasConsultants($universityIds ,$sumsProductObj);
        /*if($consultantData == false)
        {
           return false;
        }*/
        return $consultantData;
    }
    /*
    *This function takes universityID and consultantID for the consultant and fetches all the excluded courses
    */
    public function checkForConsUnivExcludedCourses($universityId ,&$displayData)
    {
        //fetch all the consultantIDs
        $consIds = array();
        $i=0;
        foreach($displayData['consultantData'] as $consId => $consInfo)
        {
            $consIds[$i++] = $consId;
        }

        $univIds[0] = $universityId;
        $consData = $this->abroadListingModel->checkForConsExcludedCourses($consIds,$universityId);

        //fill consultantData with excludedCourses for that consultant in that university
        foreach($displayData['consultantData'] as $consId => $consInfo)
        {
            $consInfo['excludedCourses'] =array();
            foreach($consData as $key=>$val)
            {
                if($key == $consId)
                {
                    $consInfo['excludedCourses'] = $consData[$consId][$universityId];
                }
                $displayData['consultantData'][$consId]['excludedCourses'] = $consInfo['excludedCourses'];
            }
        }
        return;
    }

    /*
     * Author   : Abhinav
     * Purpose  : To get popular universities of country. Popularity decided on view count.
     * Params   : countryId => of country for universities are to be obtained, count => no of top universites to get
     * Checks   : will return blank array if university count is less than 5
     * Return   : array('data' => universities,'totalCount' => totalUniversityCount)
     *
     *
     * Currently not in use
     */
    public function getPopularUniversities($countryId,$universitiesCount=9,$minimumCountForUniversity=6){
        /*if(is_int()){
            echo 'A';die;
        }else{
            echo 'B';die;
        }*/
        $countryId = (int)$countryId;
        if(!is_integer($countryId)){
            return array();
        }
        $result = $this->abroadListingModel->getUniversitiesOfCountry($countryId);
        if(count($result) < $minimumCountForUniversity){ // check if count is less than four return blank array. Check if count is less than 5 return blank array
            return array();
        }
        foreach ($result as $data){
            $universityForViewCounts[] = $data['university_id'];
        }
        $universityForViewCounts = $this->getViewCountForListingsByDays($universityForViewCounts,'university',21);
        arsort($universityForViewCounts);
        $finalResult['universities'] = array_slice($universityForViewCounts, 0,$universitiesCount,TRUE);
        $listingBuilder = $this->CI->load->builder('listing/ListingBuilder');
        $listingBuilder = new ListingBuilder();
        $universityRepo = $listingBuilder->getUniversityRepository();
        $finalResult['universities'] = $universityRepo->findMultiple(array_keys($finalResult['universities']));
        $finalResult['totalUniversityCount'] = count($universityForViewCounts);
        $categoryPageRequest = $this->CI->load->library('categoryList/AbroadCategoryPageRequest');
        $finalResult['countryPageUrl'] = $categoryPageRequest->getURLForCountryPage($countryId);
        return $finalResult;
    }

    /*
     * Author   : Abhinav
     * Purpose  : To get student guide for particular country
     * Params   : countryId => of country for which guides are to obtained
     */
    public function getStudentGuides($countryId){
        if(empty($countryId) || $countryId <= 2 || is_array($countryId) ){
            return array();
        }
        $this->sacontentmodel = $this->CI->load->model('blogs/sacontentmodel');
        $resultData = $this->sacontentmodel->getArticlesByCountryAndType($countryId,'guide');

        $guideIds = array();
        global $studyAbroadStudentGuideCountrySpecific;
        usort($resultData, function($a,$b) /*use($studyAbroadStudentGuideCountrySpecific)*/{
            /*if($a['content_id'] == $studyAbroadStudentGuideCountrySpecific[$a['country_id']]){
                return 1;
            }elseif($b['content_id'] == $studyAbroadStudentGuideCountrySpecific[$b['country_id']]){
                return 1;
            }else*/if($a['popularityCount'] < $b['popularityCount']){
                return 1;
            }elseif($a['popularityCount'] > $b['popularityCount']){
                return -1;
            }else{
                return 0;
            }
        });
        foreach($resultData as $key=>$guideData){
            $guideIds[] = $guideData['content_id'];
            if($guideData['content_id'] == $studyAbroadStudentGuideCountrySpecific[$guideData['country_id']]){
                $resultData = array($key => $guideData) + $resultData;
            }
        }
        $downloadCount = $this->sacontentmodel->downloadCountForGuide($guideIds);

        //$viewCountGuides = $this->abroadListingModel->getGuidesViewCount($guideIds);
        foreach($resultData as $key=>$guideData){
            if($guideData['is_downloadable'] == 'no'){
                unset($resultData[$key]);
                continue;
            }
            if(strlen($guideData['summary'])>210){
                $resultData[$key]['summary'] = formatArticleTitle(strip_tags($guideData['summary']), 210);
            }else{
                $resultData[$key]['summary'] = strip_tags($guideData['summary']);
            }

            if($downloadCount[$guideData['content_id']] > 50){
                $resultData[$key]['downloadCount'] = $downloadCount[$guideData['content_id']];
            }
        }

        return $resultData;
    }

    /*
     *Function to compute the popular courses for a university
     *$courseObjs : Array of course objs
     *$referrerCourseId : Referrer from the url; can be left blank.
     */
    public function processPopularCourses($courseObjs, $referrerCourseId=0,$returnAllViewCounts = false){
        $courseIds = array_keys($courseObjs);
        $courseViewCounts = $this->getViewCountForListingsByDays($courseIds,'course',21);
        arsort($courseViewCounts);

        $finalCourseArray = array();
        if((int)$referrerCourseId > 0 && in_array($referrerCourseId,$courseIds)){
            $referrerCourseObj = $courseObjs[$referrerCourseId];
            unset($courseViewCounts[$referrerCourseId]);
            $finalCourseArray[$referrerCourseId] = $referrerCourseObj;
        }
        foreach(array_keys($courseViewCounts) as $courseId){
            $finalCourseArray[$courseId] = $courseObjs[$courseId];
            if(count($finalCourseArray) == 3){
                break;
            }
        }
        if(!empty($finalCourseArray)){
            $finalCourseArray = $this->sortEligibilityExamForAbroadCourses($finalCourseArray);
        }
        if($returnAllViewCounts === true)
        {
            return array('popularCourses'=>$finalCourseArray, 'allCourseViewCounts'=>$courseViewCounts);
        }else{
            return $finalCourseArray;
        }
    }

    public function getConsultantDataForTuples($courseObj){
        $check = $this->checkIfUniversityHasConsultants(array($courseObj->getUniversityId()));
        if($check == false){
            return array();
        }
        $displayData = array();
        $consultantPageLib 			= $this->CI->load->library('consultantProfile/ConsultantPageLib');
        $displayData['currentRegion'] 		= $consultantPageLib->getRegionBasedOnIP();
        $consultantData 			= $this->getConsultantData("course",$courseObj);
        $regionConsultantMapping = array();
        foreach($consultantData as $cData)
        {
            foreach($cData['regions'] as $regionId=>$regionData)
            {
                $regionConsultantMapping[$regionId]['consultantIds'][] 	= $cData['consultantId'];
                $regionConsultantMapping[$regionId]['regionName'] 		= $regionData['name'];
            }
        }
        $activeRegionData 		= $regionConsultantMapping[$displayData['currentRegion']['regionId']];
        if(empty($activeRegionData))
        {
            $activeRegionData 	 = array($displayData['currentRegion']['regionId']=>array('consultantIds'=>array(),'regionName'=>$displayData['currentRegion']['regionName']));
            $regionConsultantMapping = $activeRegionData + $regionConsultantMapping;
        }
        else
        {
            $val = $regionConsultantMapping[$displayData['currentRegion']['regionId']];
            unset($regionConsultantMapping[$displayData['currentRegion']['regionId']]);
            $regionConsultantMapping = array($displayData['currentRegion']['regionId'] => $val) + $regionConsultantMapping;
        }
        // We also need to rotate the consultantIds within the regionConsultantMapping here so that it works.
        $regionConsultantMapping 			= $this->_rotateConsultantIds($regionConsultantMapping);
        $displayData['activeRegionData'] 		= $activeRegionData;
        $displayData['consultantData'] 			= $consultantData;
        $displayData['regionConsultantMapping'] 	= $regionConsultantMapping;
        return $displayData;
    }

    private function _rotateConsultantIds($regionData){
        $curTime = intval(date('H'))*60+intval(date('i'));
        $rotationCount = intval(($curTime)/15);
        foreach($regionData as $regionId=>$data){
            $tConIds = $data['consultantIds'];
            $tRotationCount = $rotationCount % count($tConIds);
            for($i = 0; $i < $tRotationCount; $i++){
                $ele = array_pop($tConIds);
                $tConIds = array_merge(array($ele), $tConIds);
            }
            $regionData[$regionId]['consultantIds'] = $tConIds;
        }
        return $regionData;
    }

    public function getRecommendedCountryData($request){
        // if page is exam accepting then no need to fetch data
        if($request->isExamCategoryPage()){
            return array();
        }
        $countryId = '';
        $specializationId = '';
        $countryId = $request->getCountryId();

        if($request->isLDBCoursePage() || $request->isLDBCourseSubCategoryPage()){
            $specializationId = array($request->getLDBCourseId());
        }elseif($request->isCategorySubCategoryCourseLevelPage()){
            $subCategoryId = $request->getSubCategoryId();
            $this->CI->load->builder('CategoryBuilder','categoryList');
            $builderObj		= new CategoryBuilder;
            $categoryRepository 	= $builderObj->getCategoryRepository();
            if($subCategoryId >0 && $subCategoryId!=''){
                $subCatObj 				= $categoryRepository->find($subCategoryId);
                $categoryId 			= $subCatObj->getParentId();
            }
            $request->setData(array("subCategoryId"=>1,"categoryId"=>$categoryId));
        }elseif($request->isCategoryCourseLevelPage()){
            $categoryId = $request->getCategoryId();
        }else{
            return array();
        }
        if($specializationId == ''){
            $courseLevel = $request->getCourseLevel();
            $specializationId = $this->_getSpecializationId($categoryId,$courseLevel);
        }
        if($specializationId == '' || empty($specializationId)){ return array();}	// if we still don't have the data we need, return
        $rawData = $this->abroadListingModel->getRecommendedCountryData($specializationId,$countryId);

        $processedData = array();
        foreach($rawData as $row){
            if(empty($processedData[$row['relatedCountry']])){
                $processedData[$row['relatedCountry']] = $row['c'];
            }
        }
        $this->_prepareDataForCategoryPageRelatedCountryWidget($processedData,$request);
        // Now use this data to go further
        if(count($processedData) <2){
            return array();
        }
        return $processedData;

    }

    private function _getSpecializationId($categoryId,$courseLevel){
        return $this->abroadListingModel->getSpecializationId($categoryId,$courseLevel);
    }

    public function getDetailofAlsoViewedUniversityByUniversityId($universityId,$abroadUniversityRepo,$recordCount=10,$isMobile=false){
        $finalResult = array();
        $alsoViewedUniversityData = $this->abroadListingModel->getAbroadLogLikelihoodData(array($universityId),'university',$recordCount);
        $alsoViewedUniversityIds = array_map(function($id){
            return $id['secondayEntityId'];
        },$alsoViewedUniversityData);
        if(count($alsoViewedUniversityIds)>0){

            //$alsoViewedUniversityIds = array_slice($alsoViewedUniversityIds,0,8);
            $alsoViewedUniversityDetails = $abroadUniversityRepo->findMultiple($alsoViewedUniversityIds);

            $courseCountData = $this->getCourseCountOfUniversities($alsoViewedUniversityIds);
            foreach($alsoViewedUniversityDetails as $key=>$univDetails){

                $finalResult[$univDetails->getId()]['name'] 		=  $univDetails->getName();
                $finalResult[$univDetails->getId()]['cityName'] 	=  $univDetails->getLocation()->getCity()->getName();
                $finalResult[$univDetails->getId()]['countryName'] 	=  $univDetails->getLocation()->getCountry()->getName();
                $finalResult[$univDetails->getId()]['url'] 			=  $univDetails->getURL();

                $universityMedia = $univDetails->getMedia();
                foreach($universityMedia as $mediaObj){
                    if($mediaObj->getType() == 'photo'){
                        $resoltution = '172x115';
                        if($isMobile){
                            $resoltution = '75x50';
                        }
                        $university_image = $mediaObj->getThumbURL($resoltution);
                        $finalResult[$univDetails->getId()]['univImg'] =  $university_image;
                        break;
                    }
                }
                if(is_null($finalResult[$univDetails->getId()]['univImg']))
                {
                    $finalResult[$univDetails->getId()]['univImg'] = "/public/images/defaultCatPage1.jpg";
                }
                $finalResult[$univDetails->getId()]['courseCount'] 	=  $courseCountData[$univDetails->getId()];
            }
        }
        return $finalResult;
    }

    private function _prepareDataForCategoryPageRelatedCountryWidget(& $processedData,$request){
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder 	= new LocationBuilder;
        $locationRepository 	= $locationBuilder->getLocationRepository();
        $countryIds = array_keys($processedData);
        $countryCollegeCounts = $this->abroadListingModel->getCollegeCountsByCountry($countryIds,$request);
        $countryData = $locationRepository->getAbroadCountryByIds($countryIds);
        $returnData = array();
        foreach($countryIds as $countryId){
            $collegeCount = $countryCollegeCounts[$countryId];
            if($collegeCount >= 0){
                $request->setData(array("countryId"=>array($countryId)));
                $returnData[$countryId] = $request->getSeoInfo();
                $returnData[$countryId]['countryData'] = $countryData[$countryId];
                $returnData[$countryId]['collegeCount'] = $collegeCount;
            }
            if(count($returnData) == 8){
                break;
            }
        }
        $processedData = $returnData;
    }

    public function rearrangeCustomExams($courseObjectArray = array())
    {

        $courseObjectArray = array_filter($courseObjectArray);

        if(empty($courseObjectArray))
        {
            return array();
        }

        foreach($courseObjectArray as $courseId=>&$courseObject)
        {
            //if its not a Abroad Course Object
            if(!($courseObject instanceof AbroadCourse))
            {
                continue;
            }
            //get the eligibity exams
            $exams = $courseObject->getEligibilityExams();

            //make sure course atleast have one exam from the master table condition start
            $examIdArray = array();
            foreach ($exams as $key => $value) {
                $examIdArray[] = $value->getId();
            }
            $examIdArray = array_unique($examIdArray);
            $examIdArray = array_diff($examIdArray,array(-1));
            //make sure course atleast have one exam from the master table condition Ends to avoid below written deadloack condition

            if(count($examIdArray)>0){
                //this loop goes into deadlock incase we have all custom courses...but as per current software specification we have atleast one main course
                $i=0;
                while(1)
                {
                    if($exams[$i] instanceof AbroadExam && $exams[$i]->getId()==-1)
                    {
                        $tempObj =  array_shift($exams);
                        array_push($exams,$tempObj);
                        $i=0;
                    }
                    else
                    {
                        break;
                    }
                }
                $courseObject->__set('exams',$exams);
            }
        }
        return $courseObjectArray;
    }

    /*
    * This function prepares application process data for the course which is shiksha apply enabled
    */
    public function getApplicationProcessData($courseIds)
    {

        $applicationDetail = $this->abroadListingModel->getApplicationProcessData($courseIds);

        $profileIds = array();
        foreach ($applicationDetail as $key => $applicationData) {
            $profileIds[] =   $applicationData['universityCourseProfileId'];
        }

        if(count($applicationDetail) >0 && count($profileIds)>0)
        {
            $applicationDetail['submissionDateData'] = $this->abroadListingModel->getApplicationSubmissionDatesbyProfileId($profileIds);
        }
        else
        {	$applicationDetail = array(); }
        return $applicationDetail;
    }

    public function getCourseApplicationEligibilityData($courseIds){
        $courseApplicationEligibilityDetails=$this->abroadListingModel->getCourseApplicationEligibilityDetails($courseIds);
        return $courseApplicationEligibilityDetails;
    }

    /*
    * This function prepares application process right side widget data for the course which is shiksha apply enabled
    */
    public function getApplicationProcessRightWidgetData()
    {
        $this->CI->load->library('applyContent/ApplyContentLib');
        $applyContentLib  = new ApplyContentLib();
        //according to applyContentMasterList stored in abroadApplyContentConfig.php
        $this->CI->config->load('abroadApplyContentConfig');
        $applyContentTypes = $this->CI->config->item("applyContentMasterList");
        $dataArray = array_keys($applyContentTypes);
        $data = $applyContentLib->getApplyContentHomePageUrl($dataArray);
        return $data;
    }
    /*
    *	fetch courses fees details from course objects
    */
    public function getCourseFeesDetails($courseObjs= array())
    {
        $result = array();
        foreach ($courseObjs as  $courseObj)
        {
            //_p($courseObj);
            $crsId = $courseObj->getId();
            $result[$crsId]["courseFees"] 			= $courseObj->getFees()->getValue();
            $result[$crsId]["courseCurrency"] 		= $courseObj->getFees()->getCurrency();

            $courseFeeData["fromFormattedFees"] = $this->formatMoneyAmount($result[$crsId]["courseFees"],$result[$crsId]["courseCurrency"]);
            $courseFeeData["fromCurrency"] 		= $result[$crsId]["courseCurrency"];
            $courseFeeData["fromCurrenyObj"] 	= $courseObj->getFees()->getCurrencyEntity();

            $courseIndianFees 										= $this->convertCurrency($result[$crsId]["courseCurrency"], 1, $result[$crsId]["courseFees"]);
            $result[$crsId]['toFormattedFeesIndianDisplayableFormat'] 	= $this->getIndianDisplableAmount($courseIndianFees,2);
            $courseFeeData["toFormattedFees"] 						= $this->formatMoneyAmount(round($courseIndianFees), 1, 1);
            $courseFeeData["toFormattedCurrency"] 					= 1;

            $result[$crsId]["courseFeeData"] 		= $courseFeeData;
            $customFees 										= $courseObj->getCustomFees();

            // Calculate exchange rate
            $this->CI->load->library('listing/cache/AbroadListingCache');
            $abroadListingCacheLib  = new AbroadListingCache();
            $result[$crsId]['exchangeRate'] = round($abroadListingCacheLib->getCurrencyConversionFactor($result[$crsId]["courseCurrency"], 1),2);
            //The caption Room and Board has been renamed to Hostel and Meals
            if($courseObj->getRoomBoard() > 0){
                $customFees[] = array('caption'	=> 'Hostel & Meals',
                    'value'	=> $courseObj->getRoomBoard());
            }
            if($courseObj->getInsurance() > 0){
                $customFees[] = array('caption'	=> 'Insurance',
                    'value'	=> $courseObj->getInsurance());
            }
            if($courseObj->getTransportation() > 0){
                $customFees[] = array('caption'	=> 'Transportation',
                    'value'	=> $courseObj->getTransportation());
            }
            usort($customFees,function($a,$b){
                if($a['value'] == $b['value']){
                    return 0;
                }else{
                    return ($a['value'] < $b['value'])? 1 : -1;
                }
            });

            $totalCustomFees =0;
            foreach($customFees as &$cellData){
                $totalCustomFees += $cellData['value'];
                $cellData['fromFormattedValue'] = $this->formatMoneyAmount($cellData['value'], $result[$crsId]["courseCurrency"]);
                $convertedFees = $this->convertCurrency($result[$crsId]["courseCurrency"], 1, $cellData['value']);
                $cellData["toFormattedValue"] = $this->formatMoneyAmount(round($convertedFees), 1, 1);
            }
            $totalFirstYearAndCustomFees = $totalCustomFees + $result[$crsId]["courseFees"];
            $totalFirstYearAndCustomFees = $this->convertCurrency($result[$crsId]["courseCurrency"], 1, $totalFirstYearAndCustomFees);
            $result[$crsId]['totalFirstYearAndCustomFeesIndianDisplayableFormat'] = $this->getIndianDisplableAmount($totalFirstYearAndCustomFees,2);

            if($totalCustomFees > 0){
                //$totalCustomFees += $displayData["courseFees"];
                $totalFormattedFees = $this->formatMoneyAmount($totalCustomFees, $result[$crsId]["courseCurrency"]);
                $totalConvertedFees = $this->convertCurrency($result[$crsId]["courseCurrency"], 1, $totalCustomFees);
                $result[$crsId]['customFeesIndianDisplayableFormat'] = $this->getIndianDisplableAmount($totalConvertedFees,2);
                $totalConvertedFees = $this->formatMoneyAmount(round($totalConvertedFees), 1, 1);
            }
            $result[$crsId]['customFees'] = $customFees;
            $result[$crsId]['totalFormattedFees'] = $totalFormattedFees;
            $result[$crsId]['totalConvertedFees'] = $totalConvertedFees;
        }
        return $result;
    }

    public function getUniversityContactInfo($universityObjs = array()) {
        $result = array();
        foreach ($universityObjs as  $univObj)
        {
            $result[$univObj->getId()]['universityCountry'] =$univObj->getLocation()->getCountry()->getName();
            $result[$univObj->getId()]['universityCity']	  =$univObj->getLocation()->getCity()->getName();
            $result[$univObj->getId()]['universityName'] 	  = $univObj->getName();

            $contactDetails = $univObj->getContactDetails();
            $result[$univObj->getId()]['universityEmail'] 	  = $contactDetails->getContactEmail();
            $result[$univObj->getId()]['universityPhoneNumber'] = $contactDetails->getContactMainPhone();

            if($contactDetails->getContactWebsite()) {
                $result[$univObj->getId()]['universityContactWebsite'] = $this->parseWebsiteLinkForView($contactDetails->getContactWebsite());
            }
            if($univObj->getWebsiteLink()) {
                $result[$univObj->getId()]['universityWebsite'] = $this->parseWebsiteLinkForView($univObj->getWebsiteLink());
            }
            $locationDetails 		      = $univObj->getLocation();
            $result[$univObj->getId()]['universityAddress'] = $locationDetails->getAddress();
        }
        return $result;
    }

    public function populateRankInfo($listingObjs,$type)
    {
        $result = array();
        $ranks = $this->getHighestRankOfListing(array_map(function($a){return $a->getId();},$listingObjs),$type);
        foreach ($listingObjs as  $listingObj)
        {
            $rank = $ranks[$listingObj->getId()];
            if($rank)
            {
                $result[$listingObj->getId()]['rank'] 		= $rank['rank'];
                $this->CI->load->builder('RankingPageBuilder', 'abroadRanking');
                $this->rankingPageBuilder 			= new RankingPageBuilder;
                $this->rankingLib 					= $this->rankingPageBuilder->getRankingLib();
                $this->rankingPageRepository 		= $this->rankingPageBuilder->getRankingPageRepository($this->rankingLib);
                $rankingPageObject 					= $this->rankingPageRepository->find($rank['rankPageId'],true);
                $rankingPageObject 					= reset($rankingPageObject);
                $result[$listingObj->getId()]['rankName']	= $rankingPageObject->getTitle();
                $result[$listingObj->getId()]['rankURL']	= $this->rankingLib->getRankingUrl($rankingPageObject);
            }
        }
        return $result;
    }
    /*
     * function to get living expense & campus accomodation
     */
    public function getUniversityCampusAccomodation($universityObjs = array())
    {	$result = array();
        foreach($universityObjs as $univObj)
        {
            $campusAccomodation = $univObj->getCampusAccommodation();

            $livingExpense = ($campusAccomodation->getLivingExpenses())/12;
            $result[$univObj->getId()]['livingExpense'] = $this->formatMoneyAmount(round($livingExpense), 1, 1);
            $result[$univObj->getId()]['livingExpenseCurrencyId'] = $campusAccomodation->getLivingExpenseCurrency();
            $result[$univObj->getId()]['livingExpenseCurrencyObj'] = $campusAccomodation->getCurrencyEntity();
            $mapping = $this->CI->config->item("ENT_SA_CURRENCY_SYMBOL_MAPPING");
            $result[$univObj->getId()]['currencyMapping'] = $mapping;

            if($campusAccomodation->getLivingExpenseWebsiteURL()) {
                $result[$univObj->getId()]['livingExpenseURL'] = $this->parseWebsiteLinkForView($campusAccomodation->getLivingExpenseWebsiteURL());
            }
            //$this->CI->config->load('studyAbroadListingConfig');

            if($campusAccomodation->getLivingExpenseWebsiteURL())
            {
                $result[$univObj->getId()]['livingExpenseURL'] = $this->parseWebsiteLinkForView($campusAccomodation->getLivingExpenseWebsiteURL());
            }

            if($result[$univObj->getId()]['livingExpense'] && $result[$univObj->getId()]['livingExpenseCurrencyId']) {
                $currencyId = $result[$univObj->getId()]['livingExpenseCurrencyId'];
                $destinationCurrencyId = 1;
                $expenseVal = $livingExpense;
                $livingExpenseInRupees = $this->convertCurrency($currencyId, $destinationCurrencyId, $expenseVal);
                $result[$univObj->getId()]['livingExpenseInRupees'] = $this->formatMoneyAmount(round($livingExpenseInRupees), 1, 1);
            }
            if($result[$univObj->getId()]['livingExpense'] && $result[$univObj->getId()]['livingExpenseCurrencyId'])
            {
                //prepare data for cost of living
                $livingExpenseCurrencyObj 			= $result[$univObj->getId()]['livingExpenseCurrencyObj'];
                $currencyMap					= $result[$univObj->getId()]['currencyMapping'] ;
                $livingExpenseCurrencyId				= $result[$univObj->getId()]['livingExpenseCurrencyId'];
                $result[$univObj->getId()]['livingExpenseCurrencySign'] 	= $currencyMap[$livingExpenseCurrencyId];
                $result[$univObj->getId()]['livingExpenseCurrencyCode']	= $livingExpenseCurrencyObj->getCode();
                unset($result[$univObj->getId()]['currencyMapping']);
                unset($result[$univObj->getId()]['livingExpenseCurrencyId']);
            }

            $livingExpenseAnnuallyInRupees = round($this->convertCurrency($currencyId, $destinationCurrencyId, $campusAccomodation->getLivingExpenses()));
            $result[$univObj->getId()]['livingExpenseAnnuallyInRupees'] = $this->formatMoneyAmount($livingExpenseAnnuallyInRupees, 1, 1);
            $result[$univObj->getId()]['livingExpenseAnnually'] = $this->formatMoneyAmount($campusAccomodation->getLivingExpenses(), $currencyId, 1);

            //prepare accommodation details
            $result[$univObj->getId()]['universityAccomodationDetails'] = $campusAccomodation->getAccommodationDetails();
            if($campusAccomodation->getAccommodationWebsiteURL())
            {
                $result[$univObj->getId()]['universityAccomodationURL'] = $this->parseWebsiteLinkForView($campusAccomodation->getAccommodationWebsiteURL());
            }
        }
        return $result;
    }
    /*
     * get class profile for one or more courses
     * @params: array of course objects
     */
    public function getCourseClassProfile($courseObjs = array())
    {
        $classProfiles = array();
        foreach($courseObjs as $courseObj)
        {
            $classProfile = $courseObj->getClassProfile();
            $classProfileData = array();

            if($classProfile)
            {
                $classProfileData["average_work_experience"] 	= $classProfile->getAverageWorkExperience();
                $classProfileData["average_gpa"] 				= $classProfile->getAverageGPA();
                $classProfileData["average_xii_percentage"] 	= $classProfile->getAverageXIIPercentage();
                $classProfileData["average_gmat_score"] 		= $classProfile->getAverageGMATScore();
                $classProfileData["average_age"] 				= $classProfile->getAverageAge();
                $classProfileData["percentage_international_students"] 	= $classProfile->getPercenatgeInternationalStudents();
            }
            $classProfileFilteredData = array_filter($classProfileData);
            $classProfiles[$courseObj->getId()]["showClassProfileData"] = empty($classProfileFilteredData) ? 0 : 1;
            $classProfiles[$courseObj->getId()]['classProfileData'] = $classProfileData;
        }
        return $classProfiles;
    }

    public function prepareDataForScholarshipTab(& $displayData,$courseObj){

        $displayData['scholarshipTabFlag'] = true;
        if(	$courseObj->getScholarshipLink()==''
            && $courseObj->getScholarshipLinkCourseLevel()==''
            && $courseObj->getScholarshipLinkDeptLevel()==''
            && $courseObj->getScholarshipLinkUniversityLevel()==''
            && $courseObj->getScholarshipDescription()==''
            && $courseObj->getScholarshipEligibility()==''
            && $courseObj->getScholarshipAmount()=='0'
            && $courseObj->getScholarshipDeadLine()==''
            && count($courseObj->getCustomScholarship())==0
        )
        {
            $displayData['scholarshipTabFlag'] = false;
            return;
        }
        $scholarAmount = $courseObj->getScholarshipAmount();
        if($scholarAmount!='' && $scholarAmount!=0)
        {
            $scholarAmountData = $this->convertCurrency($courseObj->getScholarshipCurrency(), 1, $scholarAmount);
            if($scholarAmountData)
            {
                $displayData['scholarshipAmountDetail'] = $this->getIndianDisplableAmount($scholarAmountData, 2);
            }
        }

        $scholarshiplLink   = $courseObj->getScholarshipLink();
        $courseLevelLink    = $courseObj->getScholarshipLinkCourseLevel();
        $deptLevelLink      = $courseObj->getScholarshipLinkDeptLevel();
        $univLevelLink      = $courseObj->getScholarshipLinkUniversityLevel();

        if(!(0===strpos($scholarshiplLink,'http')) && $scholarshiplLink )
            $scholarshiplLink = "http://".$scholarshiplLink;
        $displayData['scholarshiplLink'] = $scholarshiplLink;

        if(!(0===strpos($courseLevelLink,'http')) && $courseLevelLink)
            $courseLevelLink = "http://".$courseLevelLink;
        $displayData['courseLevelLink'] = $courseLevelLink;

        if(!(0===strpos($deptLevelLink,'http')) && $deptLevelLink )
            $deptLevelLink = "http://".$deptLevelLink;
        $displayData['deptLevelLink'] = $deptLevelLink;

        if(!(0===strpos($univLevelLink,'http')) && $univLevelLink )
            $univLevelLink = "http://".$univLevelLink;
        $displayData['univLevelLink'] = $univLevelLink;
    }

    public function getCollegeCountsByLDBCourseAndExam($ldbCourseIds,$examId){
        $this->CI->load->library('listing/cache/AbroadListingCache');
        $abroadListingCacheLib  = new AbroadListingCache();
        $data = $abroadListingCacheLib->getCollegeCountsByExamAndLDBCourse($examId);
        if(empty($data)){
            $data = $this->abroadListingModel->getCollegeCountsByLDBCourseAndExam($ldbCourseIds,$examId);
            $abroadListingCacheLib->storeCollegeCountsByExamAndLDBCourse($examId, $data);
        }
        return $data;
    }

    public function getCurrencyCodeById($currencyId){

        $this->CI->load->library('listing/cache/AbroadListingCache');
        $abroadListingCacheLib  = new AbroadListingCache();
        $currencyData 			= $abroadListingCacheLib->getCurrencyCodeById($currencyId);
        if(!$currencyData){
            $currencyData = $this->abroadListingModel->getCurrencyCodeById($currencyId);
            $abroadListingCacheLib->storeCurrencyData($currencyId,$currencyData);
        }
        return $currencyData['currency_code'];
    }

    public function getListingIdByUrl($listingUrl){
        $result = array();
        if($listingUrl!=''){
            $result = $this->abroadListingModel->getListingIdByUrl($listingUrl);
        }else{
            $result['error'] = "Please enter URL";
        }
        if(count($result)==0 || empty($result)){
            $result['error'] = "Please enter valid URL";
        }
        return $result;
    }
    /*
     * function to get abroad listings: course, dept, university, that have been modified in past 24 hours
     */
    public function getAbroadListingsModifiedInPast24Hours()
    {
        // get lst of univ, course, dept that have been modified in past 24 hours
        $listingIds = $this->abroadListingModel->getAbroadListingsModifiedInPast24Hours();
        $university = $course = array('delete'=>array(),'index'=>array());
        // separate those that are to be deleted only
        foreach ($listingIds as $type => $listings)
        {
            foreach($listings as $row)
            {
                switch($type)
                {
                    case 'univs':
                        if($row['status'] == "live")
                        {
                            $university['index'][] = $row['listing_type_id'];
                        }
                        $university['delete'][] = $row['listing_type_id'];
                        break;
                    case 'depts':
                        if($row['status'] == "live")
                        {
                            $course['index'][] = $row['course_id'];
                        }
                        $course['delete'][] = $row['course_id'];
                        break;
                    case 'courses':
                        if($row['status'] == "live")
                        {
                            $course['index'][] = $row['listing_type_id'];
                        }
                        $course['delete'][] = $row['listing_type_id'];
                        break;
                }
            }
        }
        return array('university'=>$university,'course'=>$course);
    }
    /*
     * function to get category,level,subcat,desired course for all courses
     */
    public function getAllCourseCategoryDetails()
    {        
        return $this->abroadCourseModel->getAllCourseCatSubcatLevelDesiredCourse();
    }
    /*
     * get tracking params required for new signup form using the tracking id
     */
    public function getInlineSignupFormTrackingParams($trackingPageKeyId)
    {
        $abroadSignupModel = $this->CI->load->model('studyAbroadCommon/abroadsignupmodel');
        $MISTrackingDetails = $abroadSignupModel->getMISTrackingDetails($trackingPageKeyId);
        $MISTrackingDetails =  $MISTrackingDetails[0];
        $MISTrackingDetails['trackingPageKeyId'] = $trackingPageKeyId;
        return $MISTrackingDetails;
    }


    // 	If there are more than 1 course in "also viewed courses" recommendation algo, the following priority order logic is used:
    // 1. Course B&C should have same course level
    // 2. Course B&C should be from same country
    // 3. Course B&C should have same parent category
    // 4. Course B&C should have same subcategory
    public function getCompareCourseWidgetData($compareWidgetData){

        $isMobile = $compareWidgetData['isMobile'];
        $courseCategoryId = $compareWidgetData['courseCategoryId'];
        $courseSubCategoryId = $compareWidgetData['courseSubCategoryId'];
        $courseLevel = $compareWidgetData['courseLevel'];
        $countryId = $compareWidgetData['countryId'];
        $increment = $noOfCoursesToDisplay = ($isMobile) ? 1 : 2;
        $courseData = $compareWidgetData['courseData'];

        if(count($courseData)>$noOfCoursesToDisplay){
            foreach ($courseData as $key => $course) {
                if($course['courseLevel']==$courseLevel && $course['countryId']==$countryId && $course['parentcategory']==$courseCategoryId && $course['subcategory']==$courseSubCategoryId){
                    $i = 0*$increment;
                }else if($course['courseLevel']==$courseLevel && $course['countryId']==$countryId && $course['parentcategory']==$courseCategoryId){
                    $i = 1*$increment;
                }else if($course['courseLevel']==$courseLevel && $course['countryId']==$countryId){
                    $i = 2*$increment;
                }else if($course['courseLevel']==$courseLevel){
                    $i = 3*$increment;
                }else{
                    $i = 4*$increment;
                }
                if($isMobile){
                    if(!$recommendedCompareCourses[$i]){
                        $recommendedCompareCourses[$i] = $key;
                    }
                    if($recommendedCompareCourses[0]){
                        break;
                    }
                }
                else{
                    if(!($recommendedCompareCourses[$i] && $recommendedCompareCourses[$i+1])){
                        $recommendedCompareCourses[!$recommendedCompareCourses[$i] ? $i : $i+1] = $key;
                    }
                    if($recommendedCompareCourses[0] && $recommendedCompareCourses[1]){
                        break;
                    }
                }
            }
            ksort($recommendedCompareCourses);
            $recommendedCompareCourses = array_splice($recommendedCompareCourses,0,$noOfCoursesToDisplay);
        }else{
            $recommendedCompareCourses = array_keys($courseData);
        }

        $data = array();
        $data['compareCourseIds'] = $compareWidgetData['courseId']."-".join('-',$recommendedCompareCourses);
        for($i=0;$i<$noOfCoursesToDisplay;$i++){
            $recommendedCompareCourseData[$recommendedCompareCourses[$i]] = $courseData[$recommendedCompareCourses[$i]];
        }
        $data['recommendedCompareCourseData'] = array_filter($recommendedCompareCourseData);
        return $data;
    }

    public function getDataForAdmittedUserByUnivOrCourse($universityOrCourseId,$listingType)
    {
        $courseIdArray = array();
        if($listingType == 'university')
        {
            $courseByStream = $this->getUniversityCoursesGroupByStream($universityOrCourseId);
            if(empty($courseByStream['stream']['course_ids']))
            {
                return false;
            }
            $courseIdArray = $courseByStream['stream']['course_ids'];
            $fromPage = 'universityPage';
        }
        else
        {
            $courseIdArray[] = $universityOrCourseId;
            $fromPage = 'coursePage';
        }
        if(!empty($courseIdArray))
        {
            $listingPageWidgetLib = $this->CI->load->library('listingPage/listingPageWidgetLib');
            $userAdmittedProfiles = $listingPageWidgetLib->getUserProfileWidgetData($courseIdArray,$fromPage);
            if(count($userAdmittedProfiles)<3)
            {
                return false;
            }
            return $userAdmittedProfiles;
        }
        else
        {
            return false;
        }
    }

    public function getScholarshipsWidgetData(&$displayData, $tupleCount = 4){
        $this->scholarshipCategoryPageLib = $this->CI->load->library('scholarshipCategoryPage/scholarshipCategoryPageLib');
        $paramsArray = array();
        $paramsArray['type'] = 'courseLevel';
        $paramsArray['level'] = strtolower($this->_getProperLevel($displayData['courseObj']->getCourseLevel1Value()));
        $paramsArray['category'] = array($displayData['courseCategoryId']);
        $paramsArray['country'] = array($displayData['courseObj']->getCountryId());
        $paramsArray['courseId'] = array($displayData['courseObj']->getId());
        $paramsArray['univId'] = array($displayData['courseObj']->getUniversityId());
        $scholarshipCardData = $this->scholarshipCategoryPageLib->getScholarshipTupleDataForInterlinking($paramsArray, $tupleCount, 'CLP_INTERLINKING');
        if(empty($scholarshipCardData['scholarshipData'])){
            $scholarshipCardData['genericScholarshipsText'] = $this->_createCourseStringToBeShown($displayData);
        }
        return $scholarshipCardData;
    }

    private function _createCourseStringToBeShown(&$displayData){
        $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
        $abroadCategories = $this->abroadCommonLib->getAbroadCategories();
        $genericText = '';
        if($displayData['courseCategoryId'] == 239 && $displayData['courseObj']->getCourseLevel1Value() == 'Masters' && $displayData['courseObj']->getDesiredCourseId() == DESIRED_COURSE_MBA){
            $genericText = 'MBA in '.ucfirst($displayData['courseObj']->getCountryName());
        }else if(in_array($displayData['courseCategoryId'], array(240,241)) && $displayData['courseObj']->getCourseLevel1Value() == 'Bachelors' && $displayData['courseObj']->getDesiredCourseId() == DESIRED_COURSE_BTECH){
            $genericText = 'Engineering in '.ucfirst($displayData['courseObj']->getCountryName());
        }else if(in_array($displayData['courseCategoryId'], array(240,241,242)) && $displayData['courseObj']->getCourseLevel1Value() == 'Masters' && $displayData['courseObj']->getDesiredCourseId() == DESIRED_COURSE_MS){
            $genericText = 'MS in '.ucfirst($displayData['courseObj']->getCountryName());
        }else{
            $genericText = $this->_getProperLevel($displayData['courseObj']->getCourseLevel1Value()).' of '.$abroadCategories[$displayData['courseCategoryId']]['name'].' in '.ucfirst($displayData['courseObj']->getCountryName());
        }
        return $genericText;
    }

    private function _getProperLevel($level){
        if(strpos($level, 'Bachelors') !== false){
            return 'Bachelors';
        }else{
            //if(strpos($level, 'Masters') !== false || strpos($level, 'PhD') !== false){
            return 'Masters';
        }
    }

    private function _initFatFooter(){
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();

        $this->CI->load->library('categoryList/AbroadCategoryPageRequest');
        $this->categoryPageRequest = new AbroadCategoryPageRequest();

        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $this->locationRepository = $locationBuilder->getLocationRepository();

        $countriesOrder = array(3,8,5,4,7,21,9,12); //refer SA-3452
        $countryObjs = $this->locationRepository->findMultipleCountries($countriesOrder);
        $countryData = array();
        foreach ($countryObjs as $key => $countryObj) {
            $countryData[$countryObj->getId()] = $countryObj->getName();
        }
        $this->countryList = array();
        foreach ($countriesOrder as $countryId) {
            $this->countryList[$countryId] = $countryData[$countryId];
        }
    }

    public function getFatFooterWidgetData(&$displayData, $maxLinkCount = 6){
        $this->_initFatFooter();
        unset($this->countryList[$displayData['courseObj']->getCountryId()]);
        $this->countryList = array($displayData['courseObj']->getCountryId() => $displayData['courseObj']->getCountryName()) + $this->countryList;

        $subCategoryObj = $this->categoryRepository->find($displayData['courseObj']->getCourseSubCategory());
        
        $widgetData = array(); $linkCount = 0;
        foreach ($this->countryList as $countryId => $countryName) {
            $params = array('countryId' => array($countryId), 'subCategoryId' => $subCategoryObj->getId());
            global $studyAbroadPopularCourseToCategoryMapping;
            $desiredCourses = array_keys($studyAbroadPopularCourseToCategoryMapping);
            if(in_array($displayData['courseObj']->getDesiredCourseId(), $desiredCourses)){
                $params['LDBCourseId'] = $displayData['courseObj']->getDesiredCourseId(); 
            }else{
                $params['categoryId'] = $subCategoryObj->getParentId();
                $params['courseLevel'] = $displayData['courseObj']->getCourseLevel1Value(); 
                if(strpos(strtolower($params['courseLevel']), 'diploma') !== false || strpos(strtolower($params['courseLevel']), 'certificate') !== false){
                    $params['courseLevel'] = 'certificate - diploma';
                }
            }
            $this->categoryPageRequest->setData($params);
            $widgetData[$linkCount]['url']   = $this->categoryPageRequest->getURL();
            $widgetData[$linkCount]['label'] = Modules::run('categoryList/AbroadCategoryList/getCategoryPageTitle', $this->categoryPageRequest, $this->locationRepository, $this->categoryRepository);
            $linkCount++;
            if($linkCount == $maxLinkCount){
                $labelParts = explode(' ', $widgetData[1]['label']);
                array_pop($labelParts);
                $returnData['heading'] = implode(' ', $labelParts). ' Popular Countries';
                break;
            }
        }
        $returnData['widgetData'] = $widgetData;
        return $returnData;
    }
    // sets a flag for course page to show/hide placement data
    public function getPlacementTab(&$displayData)
    {
        $recruitmentCompanies = $displayData['courseObj']->getRecruitingCompanies();
        $hasRecruitmentCompanies = (!is_null($recruitmentCompanies) && reset($recruitmentCompanies)->getName() !== '');
        $jobProfile = $displayData['courseObj']->getJobProfile();
        $hasJobProfile = (!is_null($jobProfile) && 
                            $jobProfile->getPopularSectors() !=='' &&
                            $jobProfile->getInternships() !=='' &&
                            $jobProfile->getPercentageEmployed() !=='' &&
                            $jobProfile->getCareerServicesLink() !=='' );
        if( $displayData['toAverageSalary'] == 0 &&  // doesn't have avg salary
            !$hasRecruitmentCompanies &&    // doesn't have recruitment companies
            !$hasJobProfile     // doesn't have job profiles
        )
        {
            $displayData['isPlacementFlag'] = false;
        }else{
            $displayData['isPlacementFlag'] = true;
        }
    }
    /**
     * function not in use anymore
     */
    public function getShikshaApplyCoursesForUniversity($univId)
    {
        $shikshaApplyCourseIds = array();
        if($univId>0)
        {
            $abroadCMSModelObj 	= $this->CI->load->model('listingPosting/abroadcmsmodel');            
            $shikshaApplyCourseIds = $abroadCMSModelObj->getShikshaApplyCoursesForUniversity($univId);
        }
        return $shikshaApplyCourseIds;
    }

    /*
	 * get exam order based on whether the category page is of
	 * a certain ldb course or belongs to a particular set of categories
	 */
    public function getExamOrderByDesiredCourseAndCategory($requestData)
    {
        $this->CI->config->load('studyAbroadListingConfig');
        $allExamOrder = $this->CI->config->item("ENT_COURSE_WISE_EXAM_ORDER");

        if(isset($requestData['LDBCourseId']) && !empty($requestData['LDBCourseId'])) {
            $examOrder = $allExamOrder[$requestData['LDBCourseId']];
        }
        else if($requestData['categoryId'] == CATEGORY_BUSINESS){
            $examOrder = $allExamOrder[DESIRED_COURSE_MBA];
        }elseif ((in_array ($requestData['categoryId'], array(CATEGORY_ENGINEERING,CATEGORY_COMPUTERS,CATEGORY_SCIENCE)) && $requestData['courseLevel'] != 'Bachelors')) {
            $examOrder = $allExamOrder[DESIRED_COURSE_MS];
        }elseif ((in_array ($requestData['categoryId'], array(CATEGORY_ENGINEERING,CATEGORY_COMPUTERS,CATEGORY_SCIENCE)) && $requestData['courseLevel'] == 'Bachelors')) {
            $examOrder = $allExamOrder[DESIRED_COURSE_BTECH];
        }else{
            $examOrder = $allExamOrder['OTHERS'];
        }

        return $examOrder;
    }
}

