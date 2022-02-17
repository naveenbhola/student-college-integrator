<?php

class ListingsCronsLib{
	function __construct() {
		$this->CI =& get_instance();

		// load dependencies
		$this->institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
		$this->listingCommonLib      = $this->CI->load->library("listingCommon/ListingCommonLib");
		$this->nationalinstitutecache = $this->CI->load->library('nationalInstitute/cache/NationalInstituteCache');
		$this->courseDetailLib       = $this->CI->load->library("nationalCourse/CourseDetailLib");
	}

	public function populateAdmissionPageCoursesData($listingId){
       
        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        $instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');
        $listingObj = $instituteRepo->find($listingId,'full');
        $coursesData = $instituteDetailLib->getInstituteCourseIds($listingObj->getId(), $listingObj->getType());     
        $this->coursedetailmodel = $this->CI->load->model("nationalCourse/coursedetailmodel");

        $courseIds = $coursesData['courseIds'];
         $affiliatedCourseIds = $instituteDetailLib->getAllAffiliatedCoursesForUniversities($listingId);
        $courseIds = array_unique(array_merge($courseIds, $affiliatedCourseIds)); 
        $courseIds = $this->coursedetailmodel->filterCoursesWithAdmissionDetails($courseIds);
        if(empty($courseIds)){
            return $result;
        }

       
        $mostPopularStream     = "";
        $streamCourseMapping =  array();
       $courseStreams =  array();
        if($courseIds){
            
            $this->CI->load->builder("nationalCourse/CourseBuilder");
            $builder          = new CourseBuilder();
            $courseRepository = $builder->getCourseRepository();
            $courseObjects = $courseRepository->findMultiple($courseIds);

            foreach ($courseObjects as $courseObj) {
                
                $courseTypeInfo = $courseObj->getCourseTypeInformation();
                foreach ($courseTypeInfo as $courseTypeObj) {
                    if($courseTypeObj){
                        $hierarchy = $courseTypeObj->getHierarchies();
                        foreach ($hierarchy as $hierarchyRow) {
                            $courseStreams[] = $hierarchyRow['stream_id'];

                            $streamCourseMapping[$hierarchyRow['stream_id']][] = $courseObj->getId();
                        }
                    }
                }
            }
        }
        $courseStreams = array_unique($courseStreams);
        $sortedCachedStreamsIds = $this->nationalinstitutecache->getInstituteCourseWidgetNew($listingId);
        $sortedIds =json_decode($sortedCachedStreamsIds,true);
       
        $finalSortedStreams =  array();
        foreach($sortedIds['streamIds'] as $value){
            if(in_array($value,$courseStreams)){
                $finalSortedStreams[]=$value;
            }
        }
        
        $result['streams']               = $finalSortedStreams;
        $result['courseIds']             = $courseIds;
        $mostPopularStream = reset($finalSortedStreams);
        $popularCourseIds = $instituteDetailLib->getCourseViewCount($streamCourseMapping[$mostPopularStream]);
        $popularCourseIds = array_keys($popularCourseIds);
        $mostPopularCourse = reset($popularCourseIds);

        $result['mostPopularCourse']     = $mostPopularCourse;
        $result['mostPopularStream']     = $mostPopularStream;
        $result['streamCourseMapping'] = $streamCourseMapping;
        if(!empty($courseIds)){
            $this->nationalinstitutecache->storeAdmissionCoursesData($listingId,$result);
        }
         error_log("Admission Cron institute id done ".$listingId);
     }

    public function populateCourseWidgetCacheForInstitute($listingId){
       
        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        $instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');

        $instituteObj = $instituteRepo->find($listingId,'full');

        $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
        
        $this->CI->load->builder("nationalCourse/CourseBuilder");
        $builder          = new CourseBuilder();
        $courseRepository = $builder->getCourseRepository();
        $courseList = $instituteDetailLib->getInstituteCourseIds($listingId, "institute");
        $courseList    = $courseList['courseIds'];
        $allCourseList = $courseList;
        $affiliatedCourseIds = $instituteDetailLib->getAllAffiliatedCoursesForUniversities($listingId);
        $courseList = array_unique(array_merge($courseList, $affiliatedCourseIds));
        $allCourseList = $courseList;
        $courseCount = count($allCourseList);
        $allCourseList = $instituteDetailLib->getCourseViewCount($allCourseList);
        $courseViewCount = $allCourseList;

        $instituteHasPaidCourse = false;
        $allPaidCourses           = $institutedetailsmodel->checkPaidCourses($courseList);
        
        if(!empty($allPaidCourses)) {
            $instituteHasPaidCourse = true;
        }
        
        $this->CI->load->builder("nationalCourse/CourseBuilder");
        $builder          = new CourseBuilder();
        $courseRepository = $builder->getCourseRepository();
        $baseCourseIds        = array();
        $baseCourseObjects    = array();
        $streamIds            = array();
        $streamObjects        = array();
        $specializationIds    = array();
        $subStreamIds         = array();
        $countWiseStreams     = array();
        $countWiseBaseCourses = array();
        
        if($allCourseList){
            
            $allCourses  = $courseRepository->findMultiple($courseList, array('basic'), false, false);   
        }
        foreach($allCourses as $key=>$value){
            if(!$value->getId())
                   unset($allCourses[$key]);
        }
        
        $baseCourseWiseCount = array();
        $streamWiseCount = array();
        $mbaCourseId = 101;
        $flagshipCourseId =0;
        $nonBaseCourseCLP=array();
        $baseCourseWisePaidCourses = array();
        $baseCourseWiseCourses = array();
        foreach ($allCourses as $courseObj) {
            $courseTypeInfo = $courseObj->getCourseTypeInformation();
            $courseTypeObj  = $courseTypeInfo['entry_course'];
                if($courseTypeObj){
                    $baseCourseId = $courseTypeObj->getBaseCourse();
                    if($baseCourseId < 1){
                        $nonBaseCourseCLP[]=$courseObj->getId();
                    }
                    
                    if($baseCourseId){
                        $countWiseBaseCourses[$baseCourseId]++;
                        $baseCourseWiseCount[$baseCourseId] += $courseViewCount[$courseObj->getId()];
                        $baseCourseWiseCourses[$baseCourseId][] = $courseObj->getId();
                        if($courseObj->isPaid()){
                            $baseCourseWisePaidCourses[$baseCourseId][] = $courseObj->getId();
                        }

                    }
                    if($baseCourseId == $mbaCourseId){
                        $mbaIds[] = $courseObj->getId();
                    }
                    $hierarchy = $courseTypeObj->getHierarchies();
                    $excArray = array();
                    foreach ($hierarchy as $hierarchyRow) {
                        if($hierarchyRow['stream_id']){
                            $countWiseStreams[$hierarchyRow['stream_id']]++;
                            if(empty($excArray[$hierarchyRow['stream_id']])){
                                $streamWiseCount[$hierarchyRow['stream_id']] += $courseViewCount[$courseObj->getId()];
                                $excArray[$hierarchyRow['stream_id']] = 1;  
                            }
                            if($hierarchyRow['substream_id']){
                                $countWiseSubStreams[$hierarchyRow['substream_id']]++;
                                $subStreamInfo[$hierarchyRow['substream_id']]['stream'] = $hierarchyRow['stream_id'];
                            }

                            if($hierarchyRow['specialization_id']){
                                $countWiseSpecs[$hierarchyRow['specialization_id']]++;
                            }
                        }
                    }
                }
        }

        

        arsort($streamWiseCount); //sort in descending order
        arsort($baseCourseWiseCount); //sort in decreasing order
        
        $nonBaseCourseCLP = $instituteDetailLib->getCourseViewCount($nonBaseCourseCLP);
        $nonBaseCourseCLP = array_keys($nonBaseCourseCLP);
       
        $baseCourseIds = array_keys($baseCourseWiseCount);
        $bipResponseCourse = array();
        $count  = 0;
        foreach ($baseCourseIds as $baseCourseId) {
            if($count == 50){
                $mailSubject =  "Course Widget Cron Failure!!";
                $mailContent =  "No of redis hits Limit Reached for B-level Respose Course Cache for InstituteId : ".$listingId;
                $this->CI->load->library('alerts_client');
                $alertClient = new Alerts_client(); 
                $alertClient->externalQueueAdd("12", ADMIN_EMAIL, "listingstech@shiksha.com", $mailSubject, $mailContent, "html", '', 'n');
                return;
            }
            if(!empty($baseCourseWisePaidCourses[$baseCourseId])){
                $courseIds = $instituteDetailLib->getCourseViewCount($baseCourseWisePaidCourses[$baseCourseId]);
                $bipResponseCourse[$baseCourseId] = reset(array_keys($courseIds));
            } else {
                $courseIds = $instituteDetailLib->getCourseViewCount($baseCourseWiseCourses[$baseCourseId]);
                $bipResponseCourse[$baseCourseId] = reset(array_keys($courseIds));
            }
            $count ++;
        }
        
        $streamIds = array_keys($streamWiseCount); 
        
        $specializationIds = array_keys($countWiseSpecs);

        $courseWidgetData['courseCount'] = $courseCount;
        $courseWidgetData['baseCourseIds'] = $baseCourseIds;
        $courseWidgetData['streamIds'] = $streamIds;
        $courseWidgetData['specializationIds'] = $specializationIds;
        $courseWidgetData['instituteHasPaidCourse'] = $instituteHasPaidCourse;
        $courseWidgetData['subStreamInfo'] = $subStreamInfo;
        $courseWidgetData['mbaCourseIds'] = $mbaIds;
        $courseWidgetData['nonBaseCourseCLP'] =  $nonBaseCourseCLP;
        
        error_log("institute id done ".$listingId);
        $this->nationalinstitutecache->storeInstituteCourseWidgetNew($listingId, $courseWidgetData);
        $this->nationalinstitutecache->storeInstituteBIPResponseCourse($listingId, $bipResponseCourse);
         $this->nationalinstitutecache->storeInstCourseCount($listingId, $courseCount);
    } 

    public function getPlacementPageFlags($instituteIds = array()){
        $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
        if(!is_array($instituteIds) && !empty($instituteIds)) {
        $instituteIds = array($instituteIds);
        }
        error_log("placement parent cron starts");
        $placementPageFlags = array();
        foreach ($instituteIds as $key => $listingId){

            $placementPageFlags[$listingId] = $this->getPlacementPageFlagsForInstitute($listingId);
        }
       
        error_log("placement parent cron ends successfully");
        return $placementPageFlags;
   }

   public function getPlacementPageFlagsForInstitute($listingId){

        $dataFound = false;     //to check if placement page
        $placementFlagshipCourseExists = false; //to check if flagship course placement data exists
        $placementNaukriDataExists = false; //to check if naukri placement data exists

        $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
        $collegeReviewCache = $this->CI->load->library('CollegeReviewForm/cache/CollegeReviewCache');
        $nationalinstitutecache = $this->CI->load->library('nationalInstitute/cache/NationalInstituteCache');
        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        $instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');
        $instituteObj = $instituteRepo->find($listingId,array('location'));
        $instituteCurrentLocation = $instituteDetailLib->getInstituteCurrentLocation($instituteObj);
        
        //if institute doesn't have location, then institute doesn't exist
        if(empty($instituteCurrentLocation)) {
            $placementPageFlags = array( "placementPageExists" => $dataFound, "flagshipCoursePlacementDataExists" => $placementFlagshipCourseExists, "naukriPlacementDataExists" => $placementNaukriDataExists);
            return $placementPageFlags;
        }

        $instituteLocationId     = $instituteCurrentLocation->getLocationId();
        $flagshipCourseId = $instituteDetailLib -> getFlagshipCourseId($listingId, $instituteLocationId);
        $listingType = $instituteObj -> getType();
        error_log($listingId);
        error_log("placement cron begins");

        //load course obj of this flagship course id
        if(!empty($flagshipCourseId)){
            $this->CI->load->builder("nationalCourse/CourseBuilder");
            $courseBuilder = new CourseBuilder();
            $this->courseRepo = $courseBuilder->getCourseRepository();
            $flagshipCourseObj = $this->courseRepo->find($flagshipCourseId,array('placements_internships'));
            if(!empty($flagshipCourseObj->getPlacements())){
                $placementInfoFlagshipCourse = $flagshipCourseObj->getPlacements() -> getSalary();    
            }
        }
        
        //check if out of 4 types of salaries any 1 exist
        $count = 0;
        if($placementInfoFlagshipCourse['min']){
            $count++;
        }
        if($placementInfoFlagshipCourse['median']){
            $count++;
        }
        if($placementInfoFlagshipCourse['avg']){
            $count++;
        }
        if($placementInfoFlagshipCourse['max']){
            $count++;
        }
        if($count > 0){
            error_log('found salary data for flagship course');
            $placementFlagshipCourseExists = true;
            $dataFound = true;
        }

        //check for naukri data
        if($dataFound == false){
            
            $naukriDataCount = $institutedetailsmodel -> checkIfNaukriDataExistsForInstitute($listingId);
            if($naukriDataCount > 0){
                error_log('salaries and recruitment companies data not present, but found naukri data');
                $dataFound = true;
                $placementNaukriDataExists = true;
            }    
        }

        //check for review count
        if($dataFound == false){
            $allCourseIds = $instituteDetailLib->getInstituteCourseIds($listingId, $listingType);
            $reviewmodel = $this->CI->load->model('ContentRecommendation/reviewrecommendationmodel');
            $reviewCount = $reviewmodel -> getInstituteReviewCountForPlacementData($allCourseIds['courseIds']);
            if($reviewCount > 0){
                error_log('salaries, recruitment companies and naukri data not present, but found more than 0 reviews');
                $dataFound = true;
            }    
        }
        
        if($dataFound == true){
            error_log('placement cron ends, placement data present for this id');
        }
        else{
            error_log('placement cron ends, no placement data present for this id');
        }

        $placementPageFlags = array( "placementPageExists" => $dataFound, "flagshipCoursePlacementDataExists" => $placementFlagshipCourseExists, "naukriPlacementDataExists" => $placementNaukriDataExists);

        $data = array(
        'is_placement_page_exists' => $dataFound,
        'is_flagship_course_placement_data_exists' => $placementFlagshipCourseExists,
        'is_naukri_placement_data_exists' => $placementNaukriDataExists
        );

        $institutedetailsmodel ->updateShikshaInstitute($data, $listingId);

        return $placementPageFlags;

   }

   public function getCutoffPageFlag($instituteIds = array()){
    // $instituteIds
    $this->CI->load->config('nationalInstitute/CollegeCutoffConfig',True);
    // $parentListingsIdsData = $this->CI->config->item('parentListingIds','CollegeCutoffConfig');
    $parentListingsIdsData = $instituteIds;
    // $idToCollegeMapping = $this->CI->config->item('idToCollegeMapping','CollegeCutoffConfig');
    $instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');
    $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
    $cutoffPageFlag = array();
    foreach ($parentListingsIdsData as $key => $listingId) {
        error_log("processing cutoff data for institute = $listingId at postition $key");
        // $examName = $idToCollegeMapping[$listingId];
        $affiliatedCourseIds = $instituteDetailLib->getAllAffiliatedCoursesForUniversities($listingId);

        $instituteIds = $instituteDetailLib->getAllInstitutesInHierarchy($listingId);
        // $allInstituteIds = array_filter(array_unique(array_merge($affiliatedInstituteIds, $instituteIds)));
        $allInstituteIds = $instituteIds;
        /*foreach ($allInstituteIds as $key => $instituteId) {
            $cutoffPageFlag[$instituteId]['examName'] = '';
        }*/
        $cutoffPageFlag[$listingId]['cutoffPageExists'] = $this->getCutoffPageFlagForInstitute($allInstituteIds, $listingId, $affiliatedCourseIds);

    }
    error_log("cutoff data processing ends");
    return $cutoffPageFlag;

   }

   public function getCutoffPageFlagForInstitute($listingId, $parentListingId, $affiliatedCourseIds = array()){

        $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
        $cpmodel = $this->CI->load->model('CP/cpmodel');
        $instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');

        $cutoffPagePageExists = false;
        $instituteWiseCourses = $instituteDetailLib->getAllCoursesForMultipleInstitutes($listingId,'direct');
        $courses = array();
        foreach ($instituteWiseCourses as $key => $value) {
            $courses = array_merge($courses, $value['courseIds']);
        }

        $courses = array_unique(array_merge($courses, $affiliatedCourseIds));
        
        if(empty($courses)) {
            return false;
        }
        $cutoffPagePageExists = $cpmodel->getCoursesHavingCutoffData($courses);
        
        $data = array(
            'is_cutoff_page_exists' => $cutoffPagePageExists
        );

        $institutedetailsmodel ->updateShikshaInstitute($data, $parentListingId);

        if(!$cutoffPagePageExists) {
            return false;
        }
        return true;
   }

   public function getReviewPageFlags($instituteIds = array()){
        $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
        if(!is_array($instituteIds) && !empty($instituteIds)) {
        $instituteIds = array($instituteIds);
        }
        
        _p("review parent cron starts");
        error_log("review parent cron starts");
        $reviewPageFlag = array();
        foreach ($instituteIds as $key => $listingId){

            $reviewPageFlag[$listingId] = $this->getReviewPageFlagForInstitute($listingId);
        }
       
        _p("review parent cron ends successfully");
        error_log("review parent cron ends successfully");
        return $reviewPageFlag;
   }

   public function getReviewPageFlagForInstitute($listingId){

        _p($listingId);
        _p("review cron begins");
        error_log($listingId);
        error_log("review cron begins");
        $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
        $reviewmodel = $this->CI->load->model('ContentRecommendation/reviewrecommendationmodel');
        $instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');
        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        $instituteObj = $instituteRepo->find($listingId,array('basic'));
        $listingType = $instituteObj -> getType();
        $allCourseIds = $instituteDetailLib->getInstituteCourseIds($listingId, $listingType);
        $reviewCount = $reviewmodel -> getInstituteReviewCountForPlacementData($allCourseIds['courseIds']);
        $reviewFlag = false;
        if($reviewCount > 0){
            $reviewFlag = true;
        }
        $data = array(
            'is_review_page_exists' => $reviewFlag
        );
        $institutedetailsmodel ->updateShikshaInstitute($data, $listingId);
        _p("review cron ends");
        error_log("review cron ends");
        return $reviewFlag;
   }

   public function getAllCoursePageFlags($instituteIds = array()){
        $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
        if(!is_array($instituteIds) && !empty($instituteIds)) {
        $instituteIds = array($instituteIds);
        }
        
        _p("all course parent cron starts");
        error_log("all course parent cron starts");
        $allCoursePageFlag = array();
        foreach ($instituteIds as $key => $listingId){

            $allCoursePageFlag[$listingId] = $this->getAllCoursePageFlagForInstitute($listingId);
        }
       
        _p("all course parent cron ends successfully");
        error_log("all course parent cron ends successfully");
        return $allCoursePageFlag;
   }

   public function getAllCoursePageFlagForInstitute($listingId){

        $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
        _p($listingId);
        _p("all course cron begins");
        error_log($listingId);
        error_log("all course cron begins");
        $allCoursePageFlag = false;
        $courseCount = $this->nationalinstitutecache->getInstCourseCount($listingId);
        if($courseCount > 0){
            $allCoursePageFlag = true;
        }
        $data = array(
            'is_all_course_page_exists' => $allCoursePageFlag
        );
        $institutedetailsmodel ->updateShikshaInstitute($data, $listingId);
        _p("all course cron ends");
        error_log("all course cron ends");
        return $allCoursePageFlag;
   }

   /*public function getScholarshipPageFlags($instituteIds = array()){
        $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
        $listingIds = array();
        if(!is_array($instituteIds) && !empty($instituteIds)) {
        $instituteIds = array($instituteIds);
        }
        if(empty($instituteIds)){
            $data = $institutedetailsmodel->getAllinstitutes();
            foreach ($data as $key => $instituteData){
                $listingIds[] = $instituteData['listing_id'];
            }    
        }
        else {
            $listingIds = $instituteIds;
        }
        _p("scholarship parent cron starts");
        error_log("scholarship parent cron starts");
        $scholarshipPageFlag = array();
        foreach ($listingIds as $key => $listingId){

            $scholarshipPageFlag[$listingId] = $this->getScholarshipPageFlagForInstitute($listingId);
        }
       
        _p("scholarship parent cron ends successfully");
        error_log("scholarship parent cron ends successfully");
        return $scholarshipPageFlag;
   }

   public function getScholarshipPageFlagForInstitute($listingId){
        $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        $instituteObj = $instituteRepo->find($listingId,array('scholarship'));
        $scholarshipObj = $instituteObj->getScholarships();
        $scholarshipPageFlag = true;
        if(is_null($scholarshipObj)){
            $scholarshipPageFlag = false;
        }
        $data = array(
            'is_scholarship_page_exists' => $scholarshipPageFlag
        );
        $institutedetailsmodel ->updateShikshaInstitute($data, $listingId);
        _p("scholarship cron ends");
        error_log("scholarship cron ends");
        return $scholarshipPageFlag;
   }*/

   public function getAdmissionPageFlags($instituteIds = array()){
        $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
        if(!is_array($instituteIds) && !empty($instituteIds)) {
        $instituteIds = array($instituteIds);
        }
        
        _p("admission parent cron starts");
        error_log("admission parent cron starts");
        $admissionPageFlag = array();
        foreach ($instituteIds as $key => $listingId){

            $admissionPageFlag[$listingId] = $this->getAdmissionPageFlagForInstitute($listingId);
        }
       
        _p("admission parent cron ends successfully");
        error_log("admission parent cron ends successfully");
        return $admissionPageFlag;
   }

   public function getAdmissionPageFlagForInstitute($listingId){
        $instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');
        $institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
        $courseDetailLib = $this->CI->load->library("nationalCourse/CourseDetailLib");
        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        $instituteObj = $instituteRepo->find($listingId,array('basic'));
        $instituteType = $instituteObj->getType();
        $admissionDetail = $instituteObj->getAdmissionDetails();
        $admissionPageFlag = false;
        if(empty($admissionDetail)){
            $courseArray = $instituteDetailLib->getInstituteCourseIds($listingId, $instituteType);
                    foreach ($courseArray['courseIds'] as $courseId){
                        $courseAdmission = $courseDetailLib->getAdmissionsData($courseId);
                        if(!empty($courseAdmission)){
                            $admissionPageFlag = true;
                            break;
                        }
                    }
        }
        else{
            $admissionPageFlag = true;
        }
        $data = array(
            'is_admission_page_exists' => $admissionPageFlag
        );
        $institutedetailsmodel ->updateShikshaInstitute($data, $listingId);
        _p("admission cron ends");
        error_log("admission cron ends");
        return $admissionPageFlag;
   }

}
