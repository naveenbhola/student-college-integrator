<?php

class MyShortlistLib {

    private $CI;

    function __construct() {
        $this->CI = & get_instance();
    }

    function prepareDataForRecommedations(&$displayData,$maxRecommendations){
        $myshortlistmodel  = $this->CI->load->model("myShortlist/myshortlistmodel");

        // initialization section
        $recommendationSeedCourses = array();
        $userId = 0;

        if(empty($displayData['validateuser']))
            $userValidationData = $this->CI->checkUserValidation();
        else
            $userValidationData = $displayData['validateuser'];

        if($userValidationData !== 'false')
            $userId = $userValidationData[0]['userid'];

        $displayData['validateuser'] = $userValidationData;
                
        $recommendations = $this->getRecommendationData($userValidationData,$maxRecommendations);
        $recommendedCoursesIds      = array();
        foreach($recommendations as $row){
            if(isset($row['courseId']) && isset($row['instituteId'])) {
                $recommendedCoursesIds[] = $row['courseId'];
            }
        }
        $recommendedCourses = array();
        if(!empty($recommendedCoursesIds)){
            $this->CI->load->builder("nationalCourse/CourseBuilder");
            $courseBuilder = new CourseBuilder();
            $this->courseRepository = $courseBuilder->getCourseRepository();
            $displayData['courseObject']     = $this->courseRepository->findMultiple($recommendedCoursesIds,array('basic', 'eligibility', 'course_type_information','location','placements_internships'));
            $displayData['courseShortListCounts']  = $myshortlistmodel->getShortlistCountOfCourses($recommendedCoursesIds);
        }
    }

    function getRecommendationData($userValidationData,$maxRecommendations) {
        $this->alsoviewed = $this->CI->load->library('recommendation/alsoviewed');

        //check with listing team
        $this->CI->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $courseRepository = $courseBuilder->getCourseRepository();

        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();

        $myshortlistmodel = $this->CI->load->model("myShortlist/myshortlistmodel");
        if($userValidationData !== 'false')
            $userId = $userValidationData[0]['userid'];
        
        // if user is logged-in then get the shortlisted courses and all those course on which user had made response
        if($userId) {
            // get the courses shortlisted by the user
            //$shortlistedCourses = $myshortlistmodel->getUserShortListedCourses($userId);
            // get the courses on which user made responses
            $courseOfResponses  = $myshortlistmodel->getCoursesOfResponses($userId);
            // combine the shortlisted courses and  courses on which response has been made
            //$recommendationSeedCourses = array_merge($shortlistedCourses, $courseOfResponses);
            $recommendationSeedCourses = $courseOfResponses;

            // get the also viewed recommendations for the computed courses-seed
            if(!empty($recommendationSeedCourses)){
                $recommendations = $this->alsoviewed->getAlsoViewedCourses($recommendationSeedCourses, $maxRecommendations);
            }
        }
        // get the courses-seed for recommendations from courses viewed by the user in the current session
        if(!$userId || empty($recommendations)) {
            $coursesViewed   = $myshortlistmodel->getCoursesViewedInSession(sessionId());
            $recommendations     = $this->alsoviewed->getAlsoViewedCourses($coursesViewed, $maxRecommendations);
        }
        return $recommendations;
    }
        
    function getInstitutesNaukriData($instituteIds) {
		
            $this->CI->load->model('listing/institutemodel');
            $course_model      = $this->CI->load->model('listing/coursemodel');  

            if(empty($instituteIds))
            return array();

            $institutemodel    = new institutemodel;
            $data              = array();	   
            $salaryDataResults = $institutemodel->getNaukriSalaryData($instituteIds, 'multiple');
            $instituteWiseNaukriData = array();

            // get the naukri salary data
            foreach($salaryDataResults as $naukriDataRow)
            {
                    if($naukriDataRow['exp_bucket'] == '2-5')
                            $instituteWiseNaukriData[$naukriDataRow['institute_id']] = $naukriDataRow;

                    $totalEmployees[$naukriDataRow['institute_id']] += $naukriDataRow['tot_emp'];
            }

            // unset the naukri data for institutes whose employee count is less than 30
            foreach($totalEmployees as $instituteId => $employeeCount)
            {
                    if($employeeCount < 30)
                            unset($instituteWiseNaukriData[$instituteId]);
            }

            return $instituteWiseNaukriData;
	}

    function showSimilarInstitutes(&$displayData,$courses,$maxRecommendations){
        // get also viewed recommendations of the given course
        $this->alsoviewed = $this->CI->load->library('recommendation/alsoviewed');
        $recommendations = $this->alsoviewed->getAlsoViewedCourses($courses, $maxRecommendations);
        foreach($recommendations as $row){
            if(isset($row['courseId']) && isset($row['instituteId'])) {
                $recommendedCoursesIds[] = $row['courseId'];
            }
        }

        // get data of all recommended courses
        $recommendedCourses = array();
        $courseShortListCounts = 0;
        if(!empty($recommendedCoursesIds))
        {
            $this->CI->load->builder("nationalCourse/CourseBuilder");
            $courseBuilder = new CourseBuilder();
            $this->courseRepository = $courseBuilder->getCourseRepository();
            $recommendedCourses = $this->courseRepository->findMultiple($recommendedCoursesIds,array('basic', 'eligibility', 'course_type_information','location','placements_internships'));
            $myshortlistmodel           = $this->CI->load->model("myShortlist/myshortlistmodel");
            $courseShortListCounts  = $myshortlistmodel->getShortlistCountOfCourses($recommendedCoursesIds);
        }
        $displayData['courseShortListCounts']   = $courseShortListCounts;
        $displayData['courseObject'] = $recommendedCourses;
    }

    public function processCourseRankData($courseRankBySource){
        $currentYear = date('Y');
        $filterYearArray = array();
        $filterYearArray[] = $currentYear;
        for ($i=1; $i<=5  ; $i++) {
            $filterYearArray[] = $currentYear + $i;
            $filterYearArray[] = $currentYear - $i;
        }
        foreach ($courseRankBySource as $courseId => $courseRankDetails){
            foreach ($courseRankDetails as $source => $rank) {
                unset($courseRankDetails[$source]);
                $courseRankDetails[$this->checkForYearInSource($source,$filterYearArray)] = $rank;
            }
            $courseRankBySource[$courseId] = $courseRankDetails;
        }
        return $courseRankBySource;
    }

    public function checkForYearInSource($source,$filterYearArray){
        foreach ($filterYearArray as $year){
            if (strpos($source, (string)$year) !== false) {
                $source = str_replace($year, '', $source);
                $source = rtrim($source,' ');
                break;
            }
        }
        return $source;
    }

    function migrateOrDeleteShortlistMappingForCourse($oldCourse, $newCourse, $dbHandle){
        if(empty($oldCourse)){
            return 'courseId can\'t be blank,not able to Migrate / Delete User Shortlisted Course Mapping.';
        }

        if($oldCourse <=0){
            return 'courseId is not valid,not able to Migrate / Delete User Shortlisted Course Mapping.\n';
        }

        $this->shortlistModel = $this->CI->load->model('myShortlist/myshortlistmodel');
        $result = $this->shortlistModel->checkIfShortlistCourseMappingExist($oldCourse, $dbHandle);

        if($result == true){
            if(!empty($newCourse)){
                // In this case we are not making previous row as deleted and adding new entry.
                // we simply update course id.
                if($newCourse <=0){
                    return 'new course is not valid,not able to Migrate / Delete User Shortlisted Course Mapping.\n';
                }
            }

            $response = $this->shortlistModel->migrateOrDeleteShortlistMappingForCourse($oldCourse, $newCourse, $dbHandle);

            if($response){
                return 'User Shortlisted Course Mapping is migrated / deleted.';
            }else{
                return 'User Shortlisted Course Mapping is not migrated / deleted.';
            }
        }else{
            return 'Shortlisted course migration not applicable.';
        }
    }

    function prepareBeaconTrackData(){
        $beaconTrackData = array(
            'pageIdentifier' => 'shortlistPage',
            'pageEntityId'   => '0', // No Page entity id for this one
            'extraData' => array('countryId' => 2)
        );
        return $beaconTrackData;
    }

    function checkPlacementDataForShortlist($shortlistedInstAndCourseIds=array()){
        if(empty($shortlistedInstAndCourseIds)){
            return array();
        }
        $coursesWithPlacementData = modules::run('listing/Naukri_Data_Integration_Controller/getCourseHavingNaukriData', $shortlistedInstAndCourseIds);
        return $coursesWithPlacementData;
    }
}
?>