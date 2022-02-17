<?php

class AbroadCourseRepositoryV1 extends EntityRepository
{
    private $courseFinderDao;
    private $nationalCourseRepo;
    function __construct($dao,$cache,$courseFinderDao)
    {
        parent::__construct($dao,$cache);
        $this->courseFinderDao = $courseFinderDao;

        /*
         * Load entities required
         */
        $this->CI->load->entities(array('AbroadCourse','CourseAttribute','RecruitingCompany','CourseFees','CourseDuration','ClassProfile','JobProfile','InstituteLocation', 'Currency'),'listing');
        $this->CI->load->entities(array('restructuredClasses/AbroadCourseV1','restructuredClasses/CourseClassProfile',
            'restructuredClasses/CourseCustomFeesV1','restructuredClasses/CourseDurationV1',
            'restructuredClasses/CourseExamCustomV1','restructuredClasses/CourseExamV1',
            'restructuredClasses/CourseJobProfile','restructuredClasses/CourseSEODetailsV1',
            'restructuredClasses/RecruitingCompanyV1','restructuredClasses/CourseDurationRange'),'listing');


        $this->CI->load->entities(array('OldObjectCopy/AbroadCourseV2'),'listing');
        $this->CI->load->entities(array('AbroadExam'),'common');
        $this->CI->load->entities(array('Event'),'events');
        $this->CI->load->entities(array('Exam'),'common');
        $this->CI->load->entities(array('Locality','Zone','City','State','Country', 'Region'),'location');
        // $this->CI->load->entities(array('Course','SalientFeature','Ranking'	,'CourseDescriptionAttribute','ContactDetail','CourseLocationAttribute','ListingViewCount'),'listing');

    }

    /**
     * return base course object
     *
     * @param integer $courseId course id, fieldlist, additional data
     * where you can pass data like already fetched univ objects so you wont have to fetch again
     * @return Object
     */
    function find($courseId,$fields=array(),$additionalData = array())
    {
        Contract::mustBeNumericValueGreaterThanZero($courseId,'Course ID');
        // check if univObj was passed already
        $univObj = null;
        if(!empty($additionalData) && is_object($additionalData['univObj']))
        {
            $univObj = $additionalData['univObj'];
        }

        //if cached
        if($this->caching && $cachedCourse = $this->cache->getCourse($courseId, $fields)) {
           // _p("getting from cache");

            $cachedCourse  = $this->convertAbroadCourseV1ToAbroadCourse($cachedCourse,$univObj);
            if($cachedCourse instanceof AbroadCourse){
                return $cachedCourse;
            }
            return false;
        }
        //check if its a national course
        $resultantCourse = $this->dao->checkIfCourseIdBelongsToAbroad($courseId);
        if($resultantCourse['course_id'] >0)
        {
            //_p("inside resultant course");
            if(in_array($resultantCourse['institute_type'],array('Department','Department_Virtual')) === false)
            {
                return false;
            }
            else{
                //fetch data for abroad course
                $courseDataResults = $this->dao->getData($courseId);
                $course = $this->_loadOne($courseDataResults);

                $course = $this->object_to_array($course);

                if(/*!is_object($course) || */$course['courseId'] == "") {
                    $isInvalidCourse= 1;
                } else {
                    $isInvalidCourse= 0;
                }

                if($isInvalidCourse) {
                    $this->cache->storeCourse($course, $courseId);
                }else{
                    $this->cache->storeCourse($course);
                }
                // make the course object from this data
                $course = $this->convertAbroadCourseV1ToAbroadCourse($course,$univObj);
                return $course;
            }
        }
        else{
            return false;
        }
    }
    /**
     * return course objects indexed with course id
     *
     * @param array $courseIds course ids
     * @return Object
     */
    public function findMultiple($courseIds,$fields=array(), $additionalData=array())
    {
        $courseIds = array_filter($courseIds);
        if(count($courseIds)==0)
        {
            return;
        }
        Contract::mustBeNonEmptyArrayOfIntegerValues($courseIds,'Course IDs');
        $univObjs = array();
        if(!empty($additionalData) && !empty($additionalData['univObjs']))
        {
            $univObjs = $additionalData['univObjs'];
        }
        $orderOfCourseIds = $courseIds;
        $coursesFromCache = array();
        //if cached
        if($this->caching) {
            $coursesFromCache 	= $this->cache->getMultipleCourses($courseIds,$fields);
            // _p($coursesFromCache);die("dio");
            //$coursesFromCache 	= array_filter($coursesFromCache,function($ele){if($ele instanceof AbroadCourse) return true; return false;});
            $foundInCache 		= array_keys($coursesFromCache);
            $courseIds 			= array_diff($courseIds,$foundInCache);
        }
//        _p($coursesFromCache);die;
        $abroadCourseIds = array();
        if(!empty($courseIds)){
            $abroadCourseIds = $this->dao->fetchDiffOfValidAbroadCourseIds($courseIds);
            // }
            $courseIds = $abroadCourseIds;
        }

        if(count($courseIds) > 0) {

            $courseDataResults = $this->dao->getDataForMultipleCourses($courseIds);
//        _p($courseDataResults);die;
            $coursesFromDB = $this->_load($courseDataResults);
//            _p($coursesFromDB);die;
            $coursesFromDB = $this->object_to_array($coursesFromDB);
//            _p($coursesFromDB);die;
            foreach($coursesFromDB as $course) {
//                _p($course);
                if($course['courseId'] == ""){
                    $this->cache->storeCourse($course, $courseId);
                }else{
                    $this->cache->storeCourse($course);
                }
            }
        }

        $courses = array();
        foreach($orderOfCourseIds as $courseId) {
            if(isset($coursesFromCache[$courseId])) {
                $courses[$courseId] = $coursesFromCache[$courseId];
            }
            else if(isset($coursesFromDB[$courseId])) {
                $courses[$courseId] = $coursesFromDB[$courseId];
            }
        }
        //convert AbroadCourseV1 to AbroadCourse
        $finalCourseObjects = array();
        foreach ($courses as $courseId=>$course) {
            error_log("DebugcourseIds = ".print_r($courseId,true));
            $courseObj = $this->convertAbroadCourseV1ToAbroadCourse($course,$univObjs[$course['universityId']]);
//            _p($courseObj);die;
            $finalCourseObjects[$courseId] = $courseObj;
        }
        return $finalCourseObjects;
    }




    /***** LOAD National Course Repo If Required ******/

    private function _loadNationalCourseRepo(){
        if(empty($this->nationalCourseRepo)) {
            $this->CI->load->builder('ListingBuilder','listing');
            $listingBuilder 			= new ListingBuilder;
            $this->nationalCourseRepo = $listingBuilder->getCourseRepository();
        }
    }

    // returns single course object
    private function _loadOne($results)
    {
        //_p("Inside _loadOne");
        $courses = $this->_load(array($results));
        return $courses[0];
    }

    // filter out result for multiple courses
    private function _load($results)
    {
//        _p("Inside _load ");
//        _p($results);
        $courses = array();

        if(is_array($results) && count($results))
        {
            //_p("inside condition");
            foreach($results as $courseId => $result)
            {
                if((integer)$result['general']['courseId'] <=0){
                    continue;
                }
//                _p($result);die;
                $courseV1 = $this->_createCourseV1($result);
//                _p($result);die;
                $this->_loadChildren($courseV1,$result);
                $courses[$courseId] = $courseV1;
            }
        }

        return $courses;
    }


    private function _createCourseV1($result){
//        _p("Insdie _createCourseV1");
        $course = new AbroadCourseV1;
        $courseData = (array) $result['general'];
        $this->_handleZeroDateCase($courseData,'expiryDate','java');
        $this->_handleZeroDateCase($courseData,'lastModifiedDate','java');
        $this->fillObjectWithData($course,$courseData);
//        _p($course);die;
        $this->_loadSeoDetails($course,$courseData);
        $this->_loadDurationV1($course,$courseData);
        $this->_loadSpecializationIds($course,$result['specialization_ids']);
        //_p($course);die;
        return $course;
    }

    private function _loadSpecializationIds($course,$result){
        if(is_array($result) && count($result)>0){
            $abroadCommonLib 	= $this->CI->load->library('listingPosting/AbroadCommonLib');
            $desiredCourseIds = array_column($abroadCommonLib->getAbroadMainLDBCourses(),'SpecializationId');
            $ldbCourseIds = $result[0]['ldbCourseId'];
            $specializationIds = explode(",",$ldbCourseIds);
            $finalResult = array();
            $finalResult['specializationIds'] = array();
            $finalResult['categoryId'] = $result[0]['categoryId'];
            $finalResult['universityId'] = $result[0]['universityId'];

            foreach ($specializationIds as $specializationId){
                if(in_array($specializationId,$desiredCourseIds)){
                    $finalResult['desiredCourseId'] = $specializationId;
                }
                else{
                    array_push($finalResult['specializationIds'],$specializationId);
                }
            }
            //$finalResult['specializationIds'] = $finalSpecIds;
//            _p($finalResult);

            $this->fillObjectWithData($course,$finalResult);
        }
    }

    private function _loadSeoDetails($course,$courseData){
        $seoDetail = new CourseSEODetailsV1;
        $seoDetail->setDescription($courseData['description'],$courseData['name']);
        $seoDetail->setKeyword($courseData['keyword']);
        $seoDetail->setTitle($courseData['title'],$courseData['name']);

//        $this->fillObjectWithData($seoDetail,$courseData);
        $course->setSeoDetails($seoDetail);
    }

    private function _loadApplicationEligibility($course,$courseData){
        foreach($courseData as $key=>$value)
        {
            if($key!="courseId"){
                if($key == "isWorkExperienceRequired")
                { $value=$value; }
                $course->__set($key,$value);
            }
        }
    }

    private function _loadDurationV1($course,$courseData)
    {
        $duration = new CourseDurationV1;
        $this->fillObjectWithData($duration, $courseData);
        $durationValueRange = new CourseDurationRange;
        //get range from entered duration value can be separated by '-' or 'to'
        $splitDurationArr = preg_split('/[\ \-to]/', $duration->getDurationValue(),-1,PREG_SPLIT_NO_EMPTY);
        $rangeArr = array();
        foreach ($splitDurationArr as $value){
            if(isset($value)){
                if(is_numeric($value)){
                    array_push($rangeArr,$value);
                }
                else{
                    $rangeArr = "false";
                }
            }
        }
        if (is_array($rangeArr) && count($rangeArr)) {
            if (isset($rangeArr[1])) {
                $durationValueRange->setMinDuration($rangeArr[0]);
                $durationValueRange->setMaxDuration($rangeArr[1]);
            } else {
                $durationValueRange->setMinDuration($rangeArr[0]);
                $durationValueRange->setMaxDuration($rangeArr[0]);
            }
        }
        else{
            $durationValueRange = null;
        }

        $duration->setDurationValueRange($durationValueRange);
        $course->setDuration($duration);
    }

    // load course object by given data
    public function getCourseByData($data)
    {
        return $this->_loadOne($data);
    }

    // populate course fees object
    private function _loadFees($course,$result)
    {
        $fees = new CourseFees;
        $this->fillObjectWithData($fees,$result);
        $currency = new Currency;
        $this->fillObjectWithData($currency, $result);
        $fees->setCurrencyEntity($currency);
        $course->setFees($fees);
    }

    // populate course duration object
    private function _loadDuration($course,$result)
    {
        $duration = new CourseDuration;
        $this->fillObjectWithData($duration,$result);
        $course->setDuration($duration);
    }

    // load all the childern
    private function _loadChildren($course,$result)
    {
        //$children = array('attributes','recruiting_companies','class_profile','job_profile','exams','locations','application_details','rmccounsellor_details','scholarship_details','customScholarship_details');
        $children = array('class_profile','job_profile','recruiting_companies','customFee_details','exams','rmccounsellor_details','application_details','application_eligibility');
        foreach($children as $child) {
            if(is_array($result[$child]) && count($result[$child]) > 0) {
                foreach($result[$child] as $childResult) {
                    $this->_loadChild($child,$course,$childResult);
                }
            }
            else {
                /*
                 * Load empty child
                 */
                $this->_loadChild($child,$course);
            }
        }
    }
    // based on the child of course it call appropriate method
    // which in turn populate child
    private function _loadChild($child,$course,$childResult = NULL)
    {
        switch($child) {
            case 'attributes':
                $this->_loadAttribute($course,$childResult);
                break;
            case 'exams':
                $this->_loadExamV1($course,$childResult);
                break;
            case 'recruiting_companies':
                $this->_loadRecruitingCompany($course,$childResult);
                break;
            case 'class_profile':
                $this->_loadClassProfile($course,$childResult);
                break;
            case 'job_profile':
                $this->_loadJobProfile($course,$childResult);
                break;
//            case 'locations':
//                $this->_loadLocation($course,$childResult);
//                break;
            case 'application_details':
                $this->_loadApplicationDetails($course,$childResult);
                break;
            case 'rmccounsellor_details':
                $this->_loadRmcCounsellorDetails($course,$childResult);
                break;
//            case 'scholarship_details':
//                $this->_loadCourseScholarshipDetails($course,$childResult);
//                break;
//            case 'customScholarship_details':
//                $this->_loadCourseCustomScholarshipDetails($course,$childResult);
//                break;
            case 'customFee_details':
                $this->_loadCustomFeesDetails($course,$childResult);
                break;
            case 'application_eligibility':
                $this->_loadApplicationEligibility($course,$childResult);
                break;
        }
    }

    // adds course application details
    private function _loadApplicationDetails($course,$result = NULL)
    {
        $courseApplicationDetailId = $this->_createCourseApplicationDetail($result);
        $course->setCourseApplicationDetail($courseApplicationDetailId);
        $course->setAdditionalRequirement($result['additionalRequirement']);
    }
    // populates institute location and it's children
    private function _createCourseApplicationDetail($result)
    {
        return $result['id'];
    }
    // creates location object
    private function _loadLocation($course,$result = NULL)
    {
        $location = $this->_createLocation($result);
        $course->addLocation($location);
    }

    // populates institute location and it's children
    private function _createLocation($result)
    {
        $result['entities'] = array();

        $region = new Region;
        $this->fillObjectWithData($region,$result);
        $result['entities']['region'] = $region;

        $country = new Country;
        $this->fillObjectWithData($country,$result);
        $result['entities']['country'] = $country;

        $state = new State;
        $this->fillObjectWithData($state,$result);
        $result['entities']['state'] = $state;

        $city = new City;
        $this->fillObjectWithData($city,$result);
        $result['entities']['city'] = $city;

        $zone = new Zone;
        $this->fillObjectWithData($zone,$result);
        $result['entities']['zone'] = $zone;

        $locality = new Locality;
        $this->fillObjectWithData($locality,$result);
        $result['entities']['locality'] = $locality;

        $location = new InstituteLocation;
        $this->fillObjectWithData($location,$result);

        return $location;
    }

    // populate course attribute object
    private function _loadAttribute($course,$result)
    {
        $attribute = new CourseAttribute;
        $this->fillObjectWithData($attribute,$result);
        $course->addAttribute($attribute);
    }
    // populate load custom fee details
    private function _loadCustomFeesDetails($course,$result){
        if(isset($result)) {
            $customFees = new CourseCustomFeesV1;
            $this->fillObjectWithData($customFees, $result);
            $course->addCustomFees($customFees);
        }
    }

    // populate course exam object
    private function _loadExam($course,$result)
    {
        $exam = new AbroadExam;
        $this->fillObjectWithData($exam,$result);
        $course->addExam($exam);
    }

    // populate course exam object
    private function _loadExamV1($course,$result)
    {
        if($result['examId'] == -1){
            $exam = new CourseExamCustomV1;
            $this->fillObjectWithData($exam,$result);
            $course->addCourseCustomExams($exam);
        }
        else{
            $exam = new CourseExamV1;
            if($result['examCutoff'] == 'N/A'){
                $result['accepted'] = 'true';
            }
            else{
                $result['accepted'] = 'false';
            }
            $this->fillObjectWithData($exam,$result);
            if($result['examCutoff'] == 'N/A'){
                $exam->setExamCutoff();
            }
            $course->addCourseExams($exam);
        }
    }

    // populate course recruiting company object
    private function _loadRecruitingCompany($course,$result)
    {
        $company = new RecruitingCompanyV1;
        $this->fillObjectWithData($company,$result);
        $course->addRecruitingCompany($company);
    }

    // populate course class profile object
    private function _loadClassProfile($course,$result)
    {
        $classProfile = new CourseClassProfile;
        $this->fillObjectWithData($classProfile,$result);
        $course->setClassProfile($classProfile);
    }

    // populate course job profile object
    private function _loadJobProfile($course,$result)
    {
        $jobProfile = new CourseJobProfile;
        $this->fillObjectWithData($jobProfile,$result);
        $course->setJobProfile($jobProfile);
    }

    // adds rmc counsellor details
    private function _loadRmcCounsellorDetails($course,$result = NULL)
    {
        $rmcCounsellorEnabled = $this->_createRmcCounsellorDetails($result);
        $course->setRmcEnabledDetail($rmcCounsellorEnabled);
    }
    // populates institute location and it's children
    private function _createRmcCounsellorDetails($result)
    {
        if($result['status']=='live')
        {
            return $result['counsellorCount'];
        }
        else
        {
            return 0;
        }

    }


    // get all live abroad courses ids
    public function getLiveAbroadCourses()
    {
        return $this->dao->getLiveAbroadCourses();
    }


    /**
     * to convert object to array
     */
    function object_to_array($obj) {
        if(is_object($obj)) $obj = (array) $this->dismount($obj);
        if(is_array($obj)) {
            $new = array();
            foreach($obj as $key => $val) {
                $new[$key] = $this->object_to_array($val);
            }
        }
        else $new = $obj;
        return $new;
    }

    function dismount($object) {
        $reflectionClass = new ReflectionClass(get_class($object));
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }
        return $array;
    }

    private function convertAbroadCourseV1ToAbroadCourse($courseData, $univObj = null){
        $courseObj = new AbroadCourse;

        //fetch universityObject here....and add field in result accordingly
        $this->addLocationDataToCourseData($courseData);
        $this->addUniversityDataToCourseData($courseData,$univObj);
        $this->loadCurrencyObject($courseData);
        $this->_handleZeroDateCase($courseData,'expiryDate','php');
        $this->_handleZeroDateCase($courseData,'lastModifiedDate','php');
        $Flatfields = array("courseId","name","city_id","city_name","country_id","country_name","universityId","university_name","university_type","instituteId","institute_name","level","subCategoryId","desiredCourseId","specializationIds","brochureURL","seoURL","clientId","packtype","lastModifiedDate","expiryDate","durationURL","roomBoard","mealFlag","insurance","transportation","courseDescription","cumulativeViewCount","courseWebsiteURL","admissionWebsiteURL","feesPageURL","alumniInfoURL","scholarshipURLUniversityLevel","applicationDeadlineURL","faqURL","scholarshipURLCourseLevel","scholarshipURLDeptLevel","facultyInfoURL","englishProficiencyURL","anyOtherEligibilityURL","applicationDetailId","isRmcEnabled");
        $flatData = array();
        foreach ($Flatfields as $field){
            $flatData[$field] = $courseData[$field];
        }
//        _p($flatData);die;
        $this->fillObjectWithData($courseObj,$flatData);
//        _p($courseObj);die;
        $this->_loadChildrenForOldCourse($courseObj,$courseData);
//        _p($courseObj);die;
        return $courseObj;
    }

    private function loadCurrencyObject(&$courseData){
//        _p($courseData);die;
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder 			= new ListingBuilder;
        $currencyRepo = $listingBuilder->getCurrencyRepository();
        if($courseData['feeCurrency']) {
            $currencyObj = $currencyRepo->findCurrency($courseData['feeCurrency']);
            $courseData['currency'] = $currencyObj;
        }
    }

    private function addLocationDataToCourseData(&$courseData){
        // _p($courseData);
        error_log("Loading institute repository for course object ......");
        error_log(print_r($courseData['instituteId'],true));
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder 			= new ListingBuilder;
        $abroadInstRepo 	= $listingBuilder->getAbroadInstituteRepository();
        if(isset($courseData['instituteId'])) {
            $instObj = $abroadInstRepo->find($courseData['instituteId']);
            $instituteLocation = $instObj->getLocations();
            // _p($instituteLocation);die;
            $instituteLocation = reset($instituteLocation);
            $courseData['locationData'] = $instituteLocation;
            $cityObj = $instituteLocation->getCity();
            $courseData['city_id'] = $cityObj->getId();
            $courseData['city_name'] = $cityObj->getName();
            $countryObj = $instituteLocation->getCountry();
            $courseData['country_id'] = $countryObj->getId();
            $courseData['country_name'] = $countryObj->getName();
            $courseData['institute_name'] = $instObj->getName();
        }
    }

    private function addUniversityDataToCourseData(&$courseData,$univObj = null){
        if(is_null($univObj)){ // if univObj wasn't fetched then fetch its values from cache
            $this->CI->load->builder('ListingBuilder','listing');
            $listingBuilder 			= new ListingBuilder;
            $abroadUnivRepo = $listingBuilder->getUniversityRepository();
            error_log("Loading university repository for course object ......");
            if(isset($courseData['universityId'])) {
                $univFields = $abroadUnivRepo->findMultipleFieldsByUniversityId($courseData['universityId'],array('name','type'));
                $courseData['university_name'] = $univFields['name'];
                $courseData['university_type'] = $univFields['type'];
            }
        }else{ // use information from the object itself
            $courseData['university_name'] = $univObj->getName();
            $courseData['university_type'] = $univObj->getTypeOfInstitute();
        }
    }

    private function _loadChildrenForOldCourse($courseObj,$courseData){
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder 			= new ListingBuilder;
        $currencyRepo = $listingBuilder->getCurrencyRepository();
//        _p($courseData);die;
        $children = array('seoDetails','duration','attributes','courseCustomExams','courseExams','recruitingCompanies',
            'classProfile','jobProfile','customFees');

        foreach($children as $child) {
            if(is_array($courseData[$child]) && count($courseData[$child]) > 0) {
                $this->_loadChildForOldCourse($child,$courseObj,$courseData[$child],$currencyRepo);
            }
            else {
                $this->_loadChildForOldCourse($child,$courseObj);
            }
        }

        //load data which is flat in new object but different structure in old object
        $this->_loadOldCourseFees($courseObj,$courseData,$currencyRepo);
        $this->_loadOldCourseAttribute($courseObj,$courseData);
        $this->_loadOldCourseLocations($courseObj,$courseData);
    }

    private function _loadChildForOldCourse($child,$course,$childData=null,$currencyRepo){
//        _p("Inside _loadChildForOldCourse");
//        _p($child);
//        _p($childData);
        switch($child){
            case "seoDetails":
//                $this->_loadOldCourseSeoDetails($course,$childData);
                $this->fillObjectWithData($course,$childData);
                break;
            case "duration":
                $data["duration_value"] = $childData['durationValue'];
                $data["duration_unit"]  =$childData['durationUnit'];
                $this->_loadOldDuration($course,$data);
                break;
            case "courseExams":
                //fetch examList from cache
                $this->CI->load->library('listing/AbroadListingCommonLib');
                $this->abroadListingCommonLib = new AbroadListingCommonLib();
                $examList = $this->abroadListingCommonLib->getAbroadExamsMasterListFromCache();
                $examMap = array();
                foreach ($examList as $key=>$exam){
                    $examMap[$exam['examId']] = $exam;
                }
                unset($examList);
                if(is_array($childData) && count($childData) > 0) {
                    foreach($childData as $data) {
                        if($data['accepted'] == 'true'){
                            $data['cutoff'] = 'N/A';
                        }
                        else{
                            $data['cutoff'] = $data['examCutoff'];
                        }
                        $data['comments'] = $data['examComments'];
                        $this->_loadOldCourseExams($course, $data, $examMap);
                    }
                }
                break;
            case "courseCustomExams":
                if(is_array($childData) && count($childData) > 0) {
                    foreach($childData as $data) {
                        $data['comments'] = $data['examComments'];
                        $data['cutoff'] = $data['examCutoff'];
                        $data['examId'] = -1;
                        $this->_loadOldCourseExams($course, $data);
                    }
                }
                break;
            case "recruitingCompanies":
                if(is_array($childData) && count($childData) > 0) {
                    foreach($childData as $data) {
                        $data['company_id'] = $data['companyId'];
                        $data['company_name'] = $data['companyName'];
                        $data['company_order'] = $data['companyOrder'];
                        $data['logo_url'] = $data['logoUrl'];
                        $this->_loadOldRecruitingCompany($course, $data);
                    }
                }
                else{
                    $course->addRecruitingCompany(new RecruitingCompany);
                }
                break;
            case "classProfile":
                $this->_loadOldClassProfile($course,$childData);
                break;
            case "jobProfile":
                $this->_loadOldJobProfile($course,$childData,$currencyRepo);
                break;
            case "customFees":
                $this->_loadOldCourseCustomFees($course,$childData);
                break;

        }
    }

    private function _loadOldCourseCustomFees($course,$childData){
//        _p($childData);die;
        foreach ($childData as $key=>&$val){
            $val['caption'] = $val['customFeeName'];
            $val['value'] = $val['customFeeValue'];
            unset($val['customFeeName']);
            unset($val['customFeeValue']);
        }
        $course->customFees = $childData;
    }

    private function _loadOldCourseLocations($course,$courseData){
        if(isset($courseData['locationData']))
        $course->addLocation($courseData['locationData']);
    }

    private function _loadOldCourseAttribute($course,$courseData){
        $fieldMapping = array(
            'examRequiredDetails'=>'examRequired',
            'accreditation'=>'courseAccreditation',
            'affiliation'=>'AffiliatedTo',
            'rankingDetails'=>'courseRanking',
            'curriculum'=>'curriculum',
            'nzqfCategorization'=>'nzqfCategorization'
        );

        foreach ($fieldMapping as $key=>$val){
            if(isset($courseData[$key])) {
                $attrObj = new CourseAttribute;
                $attrObj->attribute = $val;
                $attrObj->value = $courseData[$key];
                $course->addAttribute($attrObj);
            }
        }
    }

    private function _loadOldCourseExams($course,$data, $examMap = null){
        $examObj = new AbroadExam;
        $this->fillObjectWithData($examObj,$data);
        if(isset($examMap)){
            $examMap = $examMap[$data['examId']];
//            _p("inside examMap loop");
            $examObj->examName = $examMap['exam'];
            $examObj->examDescription = $examMap['examName'];
            $examObj->minScore = $examMap['minScore'];
            $examObj->maxScore = $examMap['maxScore'];
            $examObj->range = $examMap['range'];
            $examObj->type = $examMap['type'];
            $examObj->priority = $examMap['priority'];
            $examObj->listingPriority = $examMap['listingPriority'];

        }
        $course->addExam($examObj);
    }

    private function _loadOldCourseFees($course,$data,$currencyRepo){
        if(isset($data['feeCurrency']) &&  $data['feeCurrency'] != "" && $data['feeCurrency']>0) {
            $data['currency'] = $currencyRepo->findCurrency($data['feeCurrency']);
        }
        else{
            $data['currency'] = new Currency;
        }
        $data['fees_unit'] = $data['feeCurrency'];
        $data['fees_value'] = $data['tuition'];
        $fees = new CourseFees;
        $this->fillObjectWithData($fees,$data);
        $course->fees = $fees;
    }

    private function _loadOldDuration($course,$data){
        $durationObj = new CourseDuration;
        $this->fillObjectWithData($durationObj,$data);
        $course->duration = $durationObj;
    }

    private function _loadOldRecruitingCompany($course, $data){
        $recruitmentObj = new RecruitingCompany;
        $this->fillObjectWithData($recruitmentObj,$data);
        $course->addRecruitingCompany($recruitmentObj);
    }

    private function _loadOldClassProfile($course,$childData){
        $data['average_work_experience'] = $childData['averageWorkExperience'];
        $data['average_gpa'] = $childData['averageGpa'];
        $data['average_xii_percentage'] = $childData['averageXiiPercentage'];
        $data['average_gmat_score'] = $childData['averageGmatScore'];
        $data['average_age'] = $childData['averageAge'];
        $data['percentage_international_students'] = $childData['percentageInternationalStudents'];
        $classProfileObj = new ClassProfile;
        $this->fillObjectWithData($classProfileObj,$data);
        $course->classProfile = $classProfileObj;
    }

    private function _loadOldJobProfile($course,$result,$currencyRepo){
        $jobProfile = new JobProfile;
        if(isset($result['averageSalaryCurrencyId']) &&  $result['averageSalaryCurrencyId'] != "" && $result['averageSalaryCurrencyId']>0) {
            $data['currency'] = $currencyRepo->findCurrency($result['averageSalaryCurrencyId']);
        }
        else{
            $data['currency'] = new Currency;
        }
        $data['percentage_employed'] = $result['percentageEmployed'];
        $data['average_salary'] = $result['averageSalary'];
        $data['average_salary_currency_id'] = $result['averageSalaryCurrencyId'];
        $data['popular_sectors'] = $result['popularSectors'];
        $data['internships'] = $result['internships'];
        $data['internships_link'] = $result['internshipsLink'];
        $data['career_services_link'] = $result['careerServicesLink'];

        $this->fillObjectWithData($jobProfile,$data);
        $course->setJobProfile($jobProfile);
    }

    private function _handleZeroDateCase(&$result,$dateKey,$objectType){

        if($result[$dateKey] == '0000-00-00 00:00:00' && $objectType=='java'){
            $result[$dateKey] = null;
        }
        elseif (empty($result[$dateKey]) && $objectType == 'php'){
            $result[$dateKey] = '0000-00-00 00:00:00';
        }
    }

}
