<?php

class CourseDetailLib{
	function __construct() {
		$this->CI =& get_instance();
		
		$this->instituteModel = $this->CI->load->model('nationalInstitute/institutepostingmodel');
		$this->coursemodel    =$this->CI->load->model('nationalCourse/nationalcoursemodel');
     	$this->coursedetailmodel = $this->CI->load->model('nationalCourse/Coursedetailmodel');

        $this->CI->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();

        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();

        // get institute repository with all dependencies loaded
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();

        $this->CI->load->library('nationalInstitute/InstitutePostingLib');
        $this->institutePostingLib = new InstitutePostingLib;

        $this->CI->load->builder('listing/ListingBuilder');
        $listingBuilder = new ListingBuilder();
        $this->abroadUniversityRepo = $listingBuilder->getUniversityRepository();

        $this->CI->load->builder('ListingBaseBuilder', 'listingBase');
        $this->ListingBaseBuilder    = new ListingBaseBuilder();
        $this->hierarchyRepo = $this->ListingBaseBuilder->getHierarchyRepository();
        $this->baseCourseRepo = $this->ListingBaseBuilder->getBaseCourseRepository();

        $this->CI->load->config('nationalCourse/courseConfig');
        $this->CI->load->config('nationalCategoryList/nationalConfig');
        $this->CI->load->config('rankingV2/rankingConfig');
        
        $this->CI->load->config('nationalInstitute/instituteStaticAttributeConfig');

        $this->examLib = $this->CI->load->library('examPages/ExamMainLib');

        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder                                = new LocationBuilder;
        $this->locationRepo                       = $locationBuilder->getLocationRepository();

        $this->nationalCourseCache = $this->CI->load->library('nationalCourse/NationalCourseCache');
	}

	/**
	* Function to get the List of Courses for Particular Institutes by course Level
	* @param : $listingId (Integer Id ) - Institute / University Id
	* @param : $listingType - institute/university
	* @param : $courseLeve; - Integer COurse Level Id
	*
	* Usage : $this->lib->getCoursesForInstitutesByLevel(486,'institute',14);
	* Output : Array
	*			(
	*			    [0] => Array
	*			        (
	*			            [primary_id] => 486
	*			            [course_id] => 2364
	*			        )
	*
	*			    [1] => Array
	*			        (
	*			            [primary_id] => 486
	*			            [course_id] => 2364
	*			        )
	*			)
	*/

	function getCoursesForInstitutesByLevel($listingId, $listingType, $courseLevel){
         if(empty($listingId) || empty($courseLevel) || !is_numeric($courseLevel)){
             return;
         }
		 $result =$this->instituteModel->getChildData($listingId,$listingType);//49679//50214
	     foreach ($result as $value) {
            if(is_array($value)){
                foreach ($value as $innerValue) {
                    $institute = explode('_', $innerValue);
                    $instituteIds[] = $institute[1];
                }
            }else{
                    $institute = explode('_', $value);
                    $instituteIds[] = $institute[1];
            }
	     }
         
         $instituteId = array_unique($instituteIds);
		 $courseList = $this->coursemodel->getCoursesForInstitutesByLevel($instituteId,$courseLevel);
		 return $courseList;
     }


     
    /**
    * Function to get the List of Courses for Particular Institutes Location Wise
    * @param : $locationsIds (Integer Array ) - List of location Ids / dont send this param if need all courses for all location for particular Institute
    * @param : $instituteId; - Institute Id - Integer
    *
    * Usage : $this->lib1->getCourseForInstituteLocationWise(486, array(176,1039));
    * Output : Array
    *            (
    *               [176] => Array
    *                   (
    *                        [0] => 2364
    *                        [1] => 577
    *                    )
    *
    *                [1039] => Array
    *                    (
    *                        [0] => 2364
    *                    )
    *
    *            )
    *
    */
     function getCourseForInstituteLocationWise($instituteId,$locationsIds=array()){

        Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute Id');

        $result = $this->coursedetailmodel->getCourseForInstituteLocationWise($instituteId, $locationsIds);
        return $result;
     }

     /**
     * Function to get the list of client courses from Hierarchy(Stream Substream, Specialization)
     * @param : $hierarchies Array  : 
     *          $hierarchiesArr[0]['streamId'] = 3;
     *          $hierarchiesArr[0]['substreamId'] = 'any';
     *          $hierarchiesArr[0]['specializationId'] = 'none'; // courses mapped to - stream id 3, [any] substream, null specialization
     * ----------------- ------------------------------
     *          $hierarchiesArr[1]['streamId'] = 3;
     *          $hierarchiesArr[1]['substreamId'] = 'none';
     *          $hierarchiesArr[1]['specializationId'] = 2; // to get base courses mapped to - 3, null, 2
     *
     * @param : $hierarchyType : array with entry , exit as values
     *
     * Usage : $this->lib1->getClientCoursesFromHierarchies($hierarchiesArr,array('entry','exit')); 
     * Output :  Array
     *           (
     *               [2_1_20] => Array
     *                   (
     *                       [exit] => Array
     *                           (
     *                               [0] => 248998
     *                           )
     *
     *                       [entry] => Array
     *                           (
     *                               [0] => 249004
     *                           )
     *
     *                   )
     *
     *           )
     *
     * 
     */
    /*public function getClientCoursesFromHierarchies($hierarchiesArr,$hierarchyType=array('entry','exit')) {
        
        if(empty($hierarchiesArr)) {
            return;
        }
        $data = $this->coursedetailmodel->getClientCoursesFromHierarchies($hierarchiesArr,$hierarchyType);

        foreach ($data as $key => $value) {
            
            $substream_id = (!empty($value['substream_id'])) ? $value['substream_id']: 0; 
            $specialization_id = (!empty($value['specialization_id'])) ? $value['specialization_id']: 0; 
            $outPutKey = $value['stream_id']."_".$substream_id."_".$specialization_id;
            $result[$outPutKey][$value['type']][] = $value['course_id']; 
        }
        return $result;
    }*/


    /**
     * To Client COurses by a particular streamId,substreamId,specializationId where streamId is compulsary.
     * By default only ids of popular groups are returned. If object is passed as return type, then objects are returned.
     * @param  int  $streamId         streamId
     * @param  int $substreamId      substreamId
     * @param  int $specializationId specializationId
     * @param : $hierarchyType : array with entry , exit as values
     *
     * Usage : $this->lib1->getClientCoursesByBaseEntities($hierarchiesArr,array('entry','exit')); 
     * Output :  Array
     *           (
     *               [2_1_20] => Array
     *                   (
     *                       [exit] => Array
     *                           (
     *                               [0] => 248998
     *                           )
     *
     *                       [entry] => Array
     *                           (
     *                               [0] => 249004
     *                           )
     *
     *                   )
     *
     *           )
     */
    /*public function getClientCoursesByBaseEntities($streamId, $substreamId, $specializationId,$hierarchyType=array('entry','exit')){

        Contract::mustBeNumericValueGreaterThanZero($streamId,'Stream Id');

        $baseEntityArr[0]['streamId']            = $streamId;
        $baseEntityArr[0]['substreamId']         = $substreamId;
        $baseEntityArr[0]['specializationId']    = $specializationId;
        return $this->getClientCoursesFromHierarchies($baseEntityArr,$getIdNames,$outputFormat);
    }*/

    /**
    * Function to fetch the Course Status(Depends on Affiliations, University in Hierarchy and Primary Parent)
    * @param : $courseIds Array [MANDATORY]
    * @param: Objects of all the courseIds [Optional]
    *
    * @return : Array of Course Status
    *
    * Usage : $this->lib1->getCourseStatus(array(2364,250251));
    * 
    * Output : Array
            (
                [2364] => Array
                    (
                        [courseStatus] => Array
                            (
                                [0] => Array
                                    (
                                        [id] => 7
                                        [value] => Affiliated to Central University
                                    )
                            )

                        [courseStatusDependentId] => Array
                            (
                                [0] => 1
                                [1] => 486
                            )
                        [courseStatusDisplay] => Array
                            (
                                [0] => Offered by P C Univ 1
                            )

                    )

                [250251] => Array
                    (
                        [courseStatus] => Array
                            (
                                [0] => Array
                                    (
                                        [id] => 2
                                        [value] => Deemed University
                                    )
                            )

                        [courseStatusDependentId] => Array
                            (
                                [0] => 4
                                [1] => 486
                            )
                        [courseStatusDisplay] => Array
                            (
                                [0] => Affiliated to Amity University
                                [1] => Autonomous
                            )

                    )

            )

    */
    public function getCourseStatus($courseIds = array(), $courseObjectArray = array(),$hierarchyData=array(), $instituteObjectArray=array()){
        
        if(empty($courseIds)){
            return array();
        }

        // Fetch Objects if not present
        if(empty($courseObjectArray)){
            $courseObjectArray = $this->courseRepo->findMultiple($courseIds);
        }
        // Fetch Institute Objects from Course Objects
        
        if(!empty($instituteIds) && empty($instituteObjectArray)){
            $instituteIds = array();
            foreach ($courseObjectArray as $key => $courseObject) {
                $instituteIds[] = $courseObject->getInstituteId();
            }
            $instituteIds = array_filter($instituteIds);
            $instituteObjectArray = $this->instituteRepo->findMultiple($instituteIds);
        }
        if(empty($hierarchyData)){
            // Get Hierarchy Data
          //  $hierarchyData = $this->getCourseListingHierarchyData($courseIds, $courseObjectArray);  
            $hierarchyData = $this->getCourseListingHierarchyDataNew($courseIds, $courseObjectArray);

        }
        
        // Get Actual Course Status Values
        $courseStatus = $this->_getCourseStatus($courseIds, $courseObjectArray, $hierarchyData,$instituteObjectArray);
        return $courseStatus;
    }

    public function getCourseListingHierarchyDataNew($courseIds, $courseObjectArray){

        foreach ($courseObjectArray as $key => $courseObject) {
            $courseId = $courseObject->getId();
            if(empty($courseId)) continue;
            $courseInstituteMapping[$courseId] = $courseObject->getInstituteId();
        }
        if(empty($courseInstituteMapping)) return;
        $instituteHierarchy = $this->institutePostingLib->getInstituteParentHierarchyFromFlatTale($courseInstituteMapping);
        
        // This code mean we expect hierarchy from top to bottom & but was returned opposite by api
/*        foreach ($instituteHierarchy as $instId => $value) {
            if(count($value) > 1){
                $valuesToBeChecked = $value[0];
                if($valuesToBeChecked['listing_id'] == $instId){
                    $instituteHierarchy[$instId] = array_reverse($instituteHierarchy[$instId]);
                }
            }
        }*/
        
        $finalResult = array();
        foreach ($courseInstituteMapping as $courseId => $instituteId) {
            if(!empty($instituteHierarchy[$instituteId])){
                foreach ($instituteHierarchy[$instituteId] as $value) {
                    
                    list($listing_type) = explode("_", $value['id']);
                    $listing_id = $value['listing_id'];
                    if($listing_id ==  $instituteId){
                        $value['is_primary'] = 1;
                    }else{
                        $value['is_primary'] = 0;
                    }
                    if($value['type'] == "university"){
                        unset($finalResult[$courseId][$listing_type]);
                    }
                    
                    if(!empty($value['is_satellite'])){
                        unset($finalResult[$courseId][$listing_type]);
                        unset($finalResult[$courseId]['university']);
                    } 
                    $finalResult[$courseId][$listing_type][$listing_id] = $value;    
                }
                
            }
        }
        return $finalResult;
        
    }

    public function getCourseListingHierarchyData($courseIds, $courseObjectArray){

        $formattedCourseHierarchy = array();
        if(empty($courseIds)){
            return array();
        }

        // Fetch Objects if not present
        if(empty($courseObjectArray)){
            $courseObjectArray = $this->courseRepo->findMultiple($courseIds);
        }

        foreach ($courseIds as $courseId) {

            if(empty($courseObjectArray[$courseId])) continue;

            $primaryId = $courseObjectArray[$courseId]->getInstituteId();
            $primaryType = $courseObjectArray[$courseId]->getInstituteType();

            if(empty($primaryId) || empty($primaryType)) continue;
            $courseHierarchy = array();

            // Fetch Course Complete Hierachy
            $courseHierarchy = $this->institutePostingLib->getParentHierarchyById($primaryId,$primaryType);

            // Sort it based on key, so as to institute/university with less index are at top of hieracrchy
            ksort($courseHierarchy);
            
            // Format the Result
            foreach ($courseHierarchy as $key => $value) {
                $listingId = $value['listing_id'];
                $listingType = explode("_", $value['id']);
                $listingType = $listingType[0];
                $value['is_primary'] = 0;
                if($listingId == $primaryId){
                    $value['is_primary'] = 1;
                }

                if($value['type'] == "university"){
                    unset($formattedCourseHierarchy[$courseId][$listingType]);
                }
                if(!empty($value['is_satellite'])){
                    unset($formattedCourseHierarchy[$courseId][$listingType]);
                    unset($formattedCourseHierarchy[$courseId]['university']);
                }
                $formattedCourseHierarchy[$courseId][$listingType][$listingId] = $value;
            }
            
        }
        
        return $formattedCourseHierarchy;
    }


     private function _getCourseStatus($courseIds, $courseObjectArray, $hierarchyData, $instituteObjectArray){
        
        $finalCourseStatus = array();
        $allPossibleCourseStatus = $this->coursedetailmodel->fetchAllCourseStatus();

        foreach ($courseIds as $courseId) {
            if(empty($courseObjectArray[$courseId])) continue;

            $affiliations = $courseObjectArray[$courseId]->getAffiliations();

            $affiliatedUniversityId = $affiliations['university_id'];
            $affiliatedUniversityScope = $affiliations['scope'];
            $affiliatedUniversityName = $affiliations['name'];
            $courseStatus = null;
            $courseStatusId = null;
            $courseStatusAffiliationsId = null;
            $courseStatusDisplay = null;


            foreach ($hierarchyData[$courseId]['university'] as $universityId => $universityData) {
                $courseStatusId[] = $universityId;
            }

             // Get Primary Institute and Check Autonomous
            $primaryId = 0;
            foreach ($hierarchyData[$courseId]['institute'] as $instituteId => $instituteData) {
                if($instituteData['is_primary'] == 1){
                    $primaryId = $instituteId;
                }
                $courseStatusId[] = $instituteId;
            }
            if($primaryId > 0){
                // Check if it is autonomous or not
                $primaryInstituteObj = $instituteObjectArray[$primaryId];
                if(!empty($primaryInstituteObj)){
                    $isAutonomousData = $primaryInstituteObj->isAutonomous();
                    unset($primaryInstituteObj);
                    if($isAutonomousData == true){
                        $courseStatus[] = "Autonomous";
                        $courseStatusDisplay[] = "Autonomous";
                    }   
                }
                    
            }

            /* a) Check for Abroad Affiliation
               b) Check for Domestic Affiliations
               c) Check in Hierarchy
            */
            if($affiliatedUniversityScope == "abroad" && $affiliatedUniversityId > 0){

                $courseStatus[] = "Affiliated to Foreign University";
                $courseStatusAffiliationsId[] = -1;
                $aboradUnivData = $this->abroadUniversityRepo->find($affiliatedUniversityId);
                if(!empty($aboradUnivData)){
                    $aff_name = $aboradUnivData->getName();
                    $courseStatusDisplay[] = htmlentities("Affiliated to ".$aff_name);
                }
                
            }
            else if(isset($affiliatedUniversityId) && $affiliatedUniversityId > 0){
                $affiliatedUniversity = $this->instituteRepo->find($affiliatedUniversityId);
                $affId = 0;
                if(is_object($affiliatedUniversity)){
                    $affId = $affiliatedUniversity->getId();
                }
                if(!empty($affId)){
                    $courseStatusAffiliationsId[] = $affiliatedUniversityId;
                    $university_specification_type = ucfirst($affiliatedUniversity->getUniversitySpecificationType());
            
                    $courseStatus[] = "Affiliated to ".$university_specification_type." University";
                   // $courseStatusDisplay[] = preg_replace("/[^a-zA-Z ]/", "", "Affiliated to ".($affiliatedUniversity->getName()));
                    $courseStatusDisplay[] = htmlentities("Affiliated to ".$affiliatedUniversity->getName());
                    unset($affiliatedUniversity);    
                }
            }else if($affiliatedUniversityId == 0 && !empty($affiliatedUniversityName)){
                $courseStatusDisplay[] = htmlentities("Affiliated to ".$affiliatedUniversityName);
            }
            else if(isset($hierarchyData[$courseId]['university'])){
                foreach ($hierarchyData[$courseId]['university'] as $universityId => $universityData) {
                    $courseStatus[] = "Offered by ".ucfirst($universityData['university_specification_type'])." University";
                    // $courseStatusDisplay[] = preg_replace("/[^a-zA-Z ]/", "", "Offered by ".$universityData['name']);
                    $courseStatusDisplay[] = htmlentities("Offered by ".$universityData['name']);
                   // $courseStatusId[] = $universityId;
                    if(isset($universityData['is_open_university']) && $universityData['is_open_university'] == 1 ){
                        $courseStatus[] = "Open University";
                    }
                    break;
                }
            }   


           
            
            // Map Values of Course status with Ids from table shiksha_courses_status
            foreach ($courseStatus as $key => $value) {
                if(array_key_exists($value, $allPossibleCourseStatus)){
                    $courseStatus[$key] = array(
                                                'id' => $allPossibleCourseStatus[$value],
                                                'value'=>$value
                                                );
                }else{
                    unset($courseStatus[$key]);
                }
            }
            $finalCourseStatus[$courseId]['courseStatus'] = array_values($courseStatus);
            $finalCourseStatus[$courseId]['courseStatusDependentId'] = $courseStatusId;
            $finalCourseStatus[$courseId]['courseStatusDisplay'] = $courseStatusDisplay;
            $finalCourseStatus[$courseId]['courseStatusAffiliationsId'] = $courseStatusAffiliationsId;
        }
        return $finalCourseStatus;
    }


    public function getCourseCurrentLocation($courseObj, $cityIds, $localityIds){

        $locations             = $courseObj->getLocations();
        $mainLocation          = $courseObj->getMainLocation();
        $userDefinedCityId     = reset($cityIds);
        $userDefinedLocalityId = reset($localityIds);
        
        // Check if location send lies in the Main Location
        // Check for Locality then check for city if not matched in locality
        $localityId = $mainLocation->getLocalityId();
        if(!empty($localityId)){
            $tempLocalityId = $mainLocation->getLocalityId();
            if(!empty($localityId) && $tempLocalityId == $userDefinedLocalityId){
                $currentLocation = $mainLocation;
                return $currentLocation;
            }
        }else{
            $tempCityId = $mainLocation->getCityId();
            if(!empty($cityId) && $tempCityId == $userDefinedCityId){
                $currentLocation = $mainLocation;
                return $currentLocation;
            }            
        }

        

        // Match filters with all courses locations, return on first match
        // Check for Locality then check for city if not matched in locality
        foreach ($locations as $individualLocation) {
            $tempLocalityId = $individualLocation->getLocalityId();
            $tempCityId = $individualLocation->getCityId();
            
            if(!empty($userDefinedLocalityId) && !empty($userDefinedCityId) && $tempLocalityId == $userDefinedLocalityId && $tempCityId ==  $userDefinedCityId ){
                $currentLocation = $individualLocation;
                return $currentLocation;
            }

            
            if(!empty($userDefinedCityId) && empty($userDefinedLocalityId) && $tempCityId == $userDefinedCityId){
                $currentLocation = $individualLocation;
                return $currentLocation;
            }
            
        }        

        
        // If Location not found any how, just return the Main Location
        if(empty($currentLocation)){
            $currentLocation = $courseObj->getMainLocation();
        }

        return $currentLocation;
    }

     public function getCourseCurrentLocationWithMultipleLocationsInput($courseObj, $cityIds, $localityIds,$stateIds){

        $locations             = $courseObj->getLocations();
        $mainLocation          = $courseObj->getMainLocation();
        if(empty($mainLocation)){
            return;
        }
        $virtualCityMap = $this->CI->config->item('virtual_city_mapping');

        $newCityIds = $cityIds;

        // replace virtual cityIds with actual Ids
        foreach ($cityIds as $key=>$cityId) {
            if(!empty($virtualCityMap[$cityId])){
                $newCityIds = array_merge($newCityIds,$virtualCityMap[$cityId]);
            }
        }

        if(!empty($newCityIds)){
            $cityIds = $newCityIds;    
        }
        
        // Check if location send lies in the Main Location
        // Check for Locality then check for city if not matched in locality
        foreach ($localityIds as $localityId) {
            $tempLocalityId = $mainLocation->getLocalityId();
            if(!empty($tempLocalityId) && $tempLocalityId == $localityId){
                $currentLocation = $mainLocation;
                return $currentLocation;
            }
        }
    
        // Match filters with all courses locations, return on first match
        // Check for Locality then check for city the for state if not matched in locality
        foreach ($locations as $individualLocation) {
            $tempLocalityId = $individualLocation->getLocalityId();
            $tempCityId = $individualLocation->getCityId();
            $tempStateId = $individualLocation->getStateId();
            
            foreach ($localityIds as $localityId) {
                if(!empty($tempLocalityId) && $tempLocalityId == $localityId){
                    $currentLocation = $individualLocation;
                    return $currentLocation;
                }
            }
    
        }        


        foreach ($cityIds as $cityId) {
            $tempCityId = $mainLocation->getCityId();
            if(!empty($tempCityId) && $tempCityId == $cityId){
                $currentLocation = $mainLocation;
                return $currentLocation;
            }
        }

        foreach ($locations as $individualLocation) {
            $tempLocalityId = $individualLocation->getLocalityId();
            $tempCityId = $individualLocation->getCityId();
            $tempStateId = $individualLocation->getStateId();
      
            foreach ($cityIds as $cityId) {
                if(!empty($tempCityId) && $tempCityId == $cityId){
                    $currentLocation = $individualLocation;
                    return $currentLocation;
                }
            }
            
        }   


         foreach ($stateIds as $stateId) {
            $tempStateId = $mainLocation->getStateId();
            if(!empty($tempStateId) && $tempStateId == $stateId){
                $currentLocation = $mainLocation;
                return $currentLocation;
            }
        }

        foreach ($locations as $individualLocation) {
            $tempLocalityId = $individualLocation->getLocalityId();
            $tempCityId = $individualLocation->getCityId();
            $tempStateId = $individualLocation->getStateId();
            
            foreach ($stateIds as $stateId) {
                if(!empty($tempStateId) && $tempStateId == $stateId){
                    $currentLocation = $individualLocation;
                    return $currentLocation;
                }
            }
            
        }   

        
        // If Location not found any how, just return the Main Location
        if(empty($currentLocation)){
            $currentLocation = $courseObj->getMainLocation();
        }

        return $currentLocation;
    }

    private function formatEligibilityData($data,$type,$category){
        $formatData = array();
        switch($type){
            case 'categoryWise':
            case 'examEligibility':
                switch($type){
                    case 'categoryWise':
                        $baseKey = 'standard';
                        break;
                    case 'examEligibility':
                        $baseKey = 'exam_name';
                        break;
                }
                $tempData = array();
                foreach ($data as $row) {
                    if(empty($row['category'])){
                        $formatData[] = $row;
                    }
                    else{
                        $tempData[$row[$baseKey]][$row['category']] = $row;
                    }
                }
                foreach ($tempData as $baseKey => $rows) {
                    if(!empty($rows[$category])){
                        $formatData[] = $rows[$category];
                    }
                    else if(!empty($rows['general'])){
                        $formatData[] = $rows['general'];
                    }
                }
                break;
            case 'cutoff':
                $examData = array();$t12Data = array();
                foreach ($data as $row) {
                    if($row['cut_off_type'] == 'exam'){
                        $exam = empty($row['exam_id']) ? $row['custom_exam'] : $row['exam_id'];
                        $examData[$exam][$row['round']][$row['quota']][$row['category']] = $row;
                    }
                    else{
                        $t12Data[$row['quota']][$row['category']] = $row;
                    }
                }
                foreach ($examData as $exam => $examRow) {
                    foreach ($examRow as $round => $roundRow) {
                        foreach ($roundRow as $quota => $quotaRow) {
                            if(!empty($quotaRow[$category])){
                                $formatData[] = $quotaRow[$category];
                            }
                            else if(!empty($quotaRow['general'])){
                                $formatData[] = $quotaRow['general'];
                            }
                        }
                    }
                }
                foreach ($t12Data as $quota => $quotaRow) {
                    if(!empty($quotaRow[$category])){
                        $formatData[] = $quotaRow[$category];
                    }
                    else if(!empty($quotaRow['general'])){
                        $formatData[] = $quotaRow['general'];
                    }
                }
                break;
            default:
                $formatData = $data;
        }
        return $formatData;
    }

    private function getCourseEligibilityDataNew($courseId){
        $eligibilityData = $this->nationalCourseCache->getCourseEligibility($courseId);
        if(!empty($eligibilityData)){
            return $eligibilityData;
        }

        $mainData = $this->coursedetailmodel->getEligibilityMainData($courseId);
        $cutOffData = $this->coursedetailmodel->getEligibilityExamCutOffs($courseId);

        if(empty($mainData) && empty($cutOffData)){
            return array();
        }

        $scoreData = $this->coursedetailmodel->getEligibilityScores($courseId);
        $baseEntitiesData = $this->coursedetailmodel->getEligibilityBaseEntities($courseId);
        $examScoreData = $this->coursedetailmodel->getEligibilityExamScores($courseId);

        $categories = array();
        $eligibilityData = array();
        $basecourseIds = array();
        $specIds = array();
        $examIds = array();

        foreach ($scoreData as $key => $row) {
            if($row['standard'] == 'X'){
                if($row['category']){
                    $categories[$row['category']] = $row['category'];
                }
                else{
                    $eligibilityData['tenthDetails']['description'] = $row['specific_requirement'];
                    unset($scoreData[$key]);
                }
            }
            else if($row['standard'] == 'XII'){
                if($row['category']){
                    $categories[$row['category']] = $row['category'];
                }
                else{
                    $eligibilityData['twelthDetails']['description'] = $row['specific_requirement'];
                    unset($scoreData[$key]);
                }
            }
            else if($row['standard'] == 'graduation'){
                if($row['category']){
                    $categories[$row['category']] = $row['category'];
                }
                else{
                    $eligibilityData['graduationDetails']['description'] = $row['specific_requirement'];
                    unset($scoreData[$key]);
                }
            }
            else if($row['standard'] == 'postgraduation'){
                if($row['category']){
                    $categories[$row['category']] = $row['category'];
                }
                else{
                    $eligibilityData['postgraduationDetails']['description'] = $row['specific_requirement'];
                    unset($scoreData[$key]);
                }
            }
        }

        foreach ($baseEntitiesData as $row) {
            $basecourseIds[$row['base_course']] = $row['base_course'];
            if($row['specialization'] > 0){
                $specIds[$row['specialization']] = $row['specialization'];
            }
        }

        foreach ($examScoreData as $row) {
            if($row['category']){
                $categories[$row['category']] = $row['category'];
            }
            if($row['exam_id']){
                $examIds[$row['exam_id']] = $row['exam_id'];
            }
        }

        foreach ($cutOffData as $row) {
            if($row['category']){
                $categories[$row['category']] = $row['category'];
            }
        }

        // add categories to returndata
        $categoriesConfig                 = $this->CI->config->item('categories');
        $categoriesNameMapping            = array_merge($categoriesConfig['default'], $categoriesConfig['addmore']);
        foreach ($categories as $category) {
            $eligibilityData['categoryNameMapping'][$category] = $categoriesNameMapping[$category];
        }

        // add main data to returndata
        $eligibilityData['description'] = $mainData['description'];
        $eligibilityData['maxWorkEx'] = $mainData['work-ex_max'];
        $eligibilityData['minWorkEx'] = $mainData['work-ex_min'];
        $eligibilityData['maxAge'] = $mainData['age_max'];
        $eligibilityData['minAge'] = $mainData['age_min'];
        $eligibilityData['year'] = $mainData['batch_year'];
        $eligibilityData['internationalDescription'] = $mainData['international_students_desc'];
        if(!empty($mainData['subjects'])){
            $eligibilityData['twelthDetails']['subjects'] = json_decode($mainData['subjects'],true);
        }

        // add score data to returndata
        foreach ($scoreData as $row) {
            if($row['standard'] == 'X'){
                $eligibilityData['tenthDetails']['scoreType'] = $row['unit'];
                $eligibilityData['tenthDetails']['categoryWiseScores'][$row['category']] = array('score' => $row['value'],'category' => $row['category'], 'maxScore' => $row['max_value']);
            }
            else if($row['standard'] == 'XII'){
                $eligibilityData['twelthDetails']['scoreType'] = $row['unit'];
                $eligibilityData['twelthDetails']['categoryWiseScores'][$row['category']] = array('score' => $row['value'],'category' => $row['category'], 'maxScore' => $row['max_value']);
            }
            else if($row['standard'] == 'graduation'){
                $eligibilityData['graduationDetails']['scoreType'] = $row['unit'];
                $eligibilityData['graduationDetails']['categoryWiseScores'][$row['category']] = array('score' => $row['value'],'category' => $row['category'], 'maxScore' => $row['max_value']);
            }
            else if($row['standard'] == 'postgraduation'){
                $eligibilityData['postgraduationDetails']['scoreType'] = $row['unit'];
                $eligibilityData['postgraduationDetails']['categoryWiseScores'][$row['category']] = array('score' => $row['value'],'category' => $row['category'], 'maxScore' => $row['max_value']);
            }
        }
        foreach ($categories as $category) {
            if(!empty($eligibilityData['tenthDetails']) && empty($eligibilityData['tenthDetails']['categoryWiseScores'][$category]) && !empty($eligibilityData['tenthDetails']['categoryWiseScores']['general'])){
                $eligibilityData['tenthDetails']['categoryWiseScores'][$category] = $eligibilityData['tenthDetails']['categoryWiseScores']['general'];
            }
            if(!empty($eligibilityData['twelthDetails']) && empty($eligibilityData['twelthDetails']['categoryWiseScores'][$category]) && !empty($eligibilityData['twelthDetails']['categoryWiseScores']['general'])){
                $eligibilityData['twelthDetails']['categoryWiseScores'][$category] = $eligibilityData['twelthDetails']['categoryWiseScores']['general'];
            }
            if(!empty($eligibilityData['graduationDetails']) && empty($eligibilityData['graduationDetails']['categoryWiseScores'][$category]) && !empty($eligibilityData['graduationDetails']['categoryWiseScores']['general'])){
                $eligibilityData['graduationDetails']['categoryWiseScores'][$category] = $eligibilityData['graduationDetails']['categoryWiseScores']['general'];
            }
            if(!empty($eligibilityData['postgraduationDetails']) && empty($eligibilityData['postgraduationDetails']['categoryWiseScores'][$category]) && !empty($eligibilityData['postgraduationDetails']['categoryWiseScores']['general'])){
                $eligibilityData['postgraduationDetails']['categoryWiseScores'][$category] = $eligibilityData['postgraduationDetails']['categoryWiseScores']['general'];
            }
        }

        // add base entities data to returndata
        if(!empty($baseEntitiesData)){
            if(!empty($basecourseIds)){
                $basecourseObjs = $this->baseCourseRepo->findMultiple(array_values($basecourseIds));
            }
            if(!empty($specIds)){
                $specObjs = $this->hierarchyRepo->findMultipleSpecializations(array_values($specIds));
            }
            foreach ($baseEntitiesData as $row) {
                $temp = array();
                $temp['baseCourseId'] = $row['base_course'];
                $temp['baseCourseName'] = $basecourseObjs[$row['base_course']]->getName();
                if($row['specialization']){
                    $temp['specializationId'] = $row['specialization'];
                    $temp['specializationName'] = $specObjs[$row['specialization']]->getName();
                }
                if($row['type'] == 'graduation'){
                    $eligibilityData['graduationDetails']['courseSpecMapping'][] = $temp;
                }
                else{
                    $eligibilityData['postgraduationDetails']['courseSpecMapping'][] = $temp;
                }
            }
        }

        // add exam data to returndata
        if(!empty($examIds)){
            $examData = $this->examLib->getExamDataByExamIds($examIds);
        }
        foreach ($examScoreData as $row) {
            $examKey = $row['exam_id'] ? "exam:".$row['exam_id'] : $row['exam_name'];
            if(empty($eligibilityData['exams'][$examKey])){
                if($row['exam_id']){
                    if($examData[$row['exam_id']]){
                        $domainName = $examData[$row['exam_id']]['scope'] == 'national' ? SHIKSHA_HOME : SHIKSHA_STUDYABROAD_HOME;
                        $eligibilityData['exams'][$examKey] = array('examId' => $row['exam_id'],'examName' => $examData[$row['exam_id']]['name'],'examUrl' => addingDomainNameToUrl(array('url' => $examData[$row['exam_id']]['url'] ,'domainName' => $domainName)),'scoreType' => $row['unit']);
                    }
                }
                else{
                    $eligibilityData['exams'][$examKey] = array('examId' => 0, 'examName' => $row['exam_name'],'scoreType' => $row['unit']);
                }
            }
            if($row['category']){
                $eligibilityData['exams'][$examKey]['categoryWiseScores'][$row['category']] = array('score' => $row['value'],'category' => $row['category'], 'maxScore' => $row['max_value']);
            }
        }

        foreach ($categories as $category) {
            foreach ($eligibilityData['exams'] as $examKey => $row) {
                if(!empty($row['categoryWiseScores']) && empty($row['categoryWiseScores'][$category]) && !empty($row['categoryWiseScores']['general'])){
                    $eligibilityData['exams'][$examKey]['categoryWiseScores'][$category] = $eligibilityData['exams'][$examKey]['categoryWiseScores']['general'];
                }
            }
        }

        // add cutoff data to returndata
        foreach ($cutOffData as $cutoff) {
            if($cutoff['cut_off_type'] == '12th'){
                $eligibilityData['twelthDetails']['cutoff'][$cutoff['category']][$cutoff['quota']] = array('value' => $cutoff['cut_off_value'],'category' => $cutoff['quota'], 'maxScore' => 100);
            }
            else{
                $examKey = $cutoff['exam_id'] ? "exam:".$cutoff['exam_id'] : $cutoff['custom_exam'];
                $eligibilityData['exams'][$examKey]['cutOffData']['cutOffYear'] = $cutoff['exam_year'];
                $eligibilityData['exams'][$examKey]['cutOffData']['cutOffUnit'] = $cutoff['cut_off_unit'];
                if($cutoff['round'] < 0){
                    $eligibilityData['exams'][$examKey]['cutOffData']['roundsApplicable'] = false;
                    $round = 1;
                }
                else{
                    $eligibilityData['exams'][$examKey]['cutOffData']['roundsApplicable'] = true;
                    $round = $cutoff['round'] + 1;
                }
                if(strpos($cutoff['quota'],'related_states:') !== false){
                    if(empty($eligibilityData['exams'][$examKey]['cutOffData']['relatedStates'])){
                        $stateIdsString = substr($cutoff['quota'],strlen('related_states:'));
                        $stateObjs = $this->locationRepo->findMultipleStates(explode(",",$stateIdsString));
                        foreach ($stateObjs as $stateId => $stateObj) {
                            $eligibilityData['exams'][$examKey]['cutOffData']['relatedStates'][$stateId] = $stateObj->getName();
                        }
                    }
                    $quota = "related_states";
                }
                else{
                    $quota = $cutoff['quota'];
                }
                $eligibilityData['exams'][$examKey]['cutOffData']['roundsCutOffData'][$round][$cutoff['category']][$quota] = array('score' => $cutoff['cut_off_value'],'category' => $quota, 'maxScore' => $cutoff['exam_out_of']);
            }
        }
        foreach ($categories as $category) {
            foreach ($eligibilityData['exams'] as $examKey => $examDetails) {
                foreach ($examDetails['cutOffData']['roundsCutOffData'] as $round => $roundsCutOffData) {
                    if(!empty($roundsCutOffData) && empty($roundsCutOffData[$category]) && !empty($roundsCutOffData['general'])){
                        $eligibilityData['exams'][$examKey]['cutOffData']['roundsCutOffData'][$round][$category] = $eligibilityData['exams'][$examKey]['cutOffData']['roundsCutOffData'][$round]['general'];
                    }
                }
            }
        }
        foreach ($categories as $category) {
            if(!empty($eligibilityData['twelthDetails']['cutoff']) && !empty($eligibilityData['twelthDetails']['cutoff']['general'])){
                if(empty($eligibilityData['twelthDetails']['cutoff'][$category])){
                    $eligibilityData['twelthDetails']['cutoff'][$category] = $eligibilityData['twelthDetails']['cutoff']['general'];
                }
                else{
                    foreach ($eligibilityData['twelthDetails']['cutoff']['general'] as $key => $row) {
                        if(empty($eligibilityData['twelthDetails']['cutoff'][$category][$key])){
                            $eligibilityData['twelthDetails']['cutoff'][$category][$key] = $row;
                        }
                    }
                }
            }
        }

        // _p($eligibilityData);die;

        if(!empty($eligibilityData['tenthDetails']) && empty($eligibilityData['tenthDetails']['categoryWiseScores'])){
            $eligibilityData['tenthDetails']['categoryWiseScores'] = new stdClass();
        }
        if(!empty($eligibilityData['twelthDetails']) && empty($eligibilityData['twelthDetails']['categoryWiseScores'])){
            $eligibilityData['twelthDetails']['categoryWiseScores'] = new stdClass();
        }
        if(!empty($eligibilityData['twelthDetails']) && empty($eligibilityData['twelthDetails']['cutoff'])){
            $eligibilityData['twelthDetails']['cutoff'] = new stdClass();
        }
        if(!empty($eligibilityData['graduationDetails']) && empty($eligibilityData['graduationDetails']['categoryWiseScores'])){
            $eligibilityData['graduationDetails']['categoryWiseScores'] = new stdClass();
        }
        if(!empty($eligibilityData['graduationDetails']) && empty($eligibilityData['graduationDetails']['courseSpecMapping'])){
            $eligibilityData['graduationDetails']['courseSpecMapping'] = array();
        }
        if(!empty($eligibilityData['postgraduationDetails']) && empty($eligibilityData['postgraduationDetails']['categoryWiseScores'])){
            $eligibilityData['postgraduationDetails']['categoryWiseScores'] = new stdClass();
        }
        if(!empty($eligibilityData['postgraduationDetails']) && empty($eligibilityData['postgraduationDetails']['courseSpecMapping'])){
            $eligibilityData['postgraduationDetails']['courseSpecMapping'] = array();
        }
        foreach ($eligibilityData['exams'] as $examKey => $row) {
            if(empty($row['categoryWiseScores'])){
                $eligibilityData['exams'][$examKey]['categoryWiseScores'] = new stdClass();
            }
        }

        // _p($eligibilityData);die('aaa');
        $this->nationalCourseCache->storeCourseEligibility($courseId, $eligibilityData);
        $eligibilityData = $this->nationalCourseCache->getCourseEligibility($courseId);
        // _p($eligibilityData);die('bbbbb');
        return $eligibilityData;
    }

    public function getCourseEligibilityDataWithCache($courseId,$category){
        $eligibilityData = $this->getCourseEligibilityDataNew($courseId);
        // _p($eligibilityData);die;
        if(empty($eligibilityData)){
            return array();
        }
        if(empty($eligibilityData['categoryNameMapping'][$category])){
            $category = 'general';
        }

        $returnData['eligibilitySelectedCategory'] = $category;
        $returnData['categories'] = array_keys($eligibilityData['categoryNameMapping']);
        if(!empty($eligibilityData['tenthDetails'])){
            if(!empty($eligibilityData['tenthDetails']['categoryWiseScores'][$category])){
                $returnData['table']['X']['eligibility'] = getFormattedScore($eligibilityData['tenthDetails']['categoryWiseScores'][$category]['score'],$eligibilityData['tenthDetails']['scoreType'],$eligibilityData['tenthDetails']['categoryWiseScores'][$category]['maxScore']);
                $showEligibilityVal = true;
            }
            $returnData['table']['X']['qualification'] = '10th';
            $returnData['table']['X']['type'] = 'section';
            $returnData['table']['X']['cutoff'] = '--';
            if(!empty($eligibilityData['tenthDetails']['description'])){
                $returnData['table']['X']['additionalInfo'] = $eligibilityData['tenthDetails']['description'];
                $showEligibilityAdditionalInfo = true;
            }
            else{
                $returnData['table']['X']['additionalInfo'] = '--';
            }
        }

        if(!empty($eligibilityData['twelthDetails'])){
            if(!empty($eligibilityData['twelthDetails']['categoryWiseScores'][$category])){
                $returnData['table']['XII']['eligibility'] = getFormattedScore($eligibilityData['twelthDetails']['categoryWiseScores'][$category]['score'],$eligibilityData['twelthDetails']['scoreType'],$eligibilityData['twelthDetails']['categoryWiseScores'][$category]['maxScore']);
                $showEligibilityVal = true;
            }
            $returnData['table']['XII']['qualification'] = '12th';
            $returnData['table']['XII']['type'] = 'section';
            if(!empty($eligibilityData['twelthDetails']['cutoff'][$category])){
                if(count($eligibilityData['twelthDetails']['cutoff'][$category]) == 1 && $eligibilityData['twelthDetails']['cutoff'][$category]['cutOff12th']){
                    $returnData['table']['XII']['cutoff'] = getFormattedScore($eligibilityData['twelthDetails']['cutoff'][$category]['cutOff12th']['value'],'percentage');
                }
                else{
                    $cutoffArray = array();
                    foreach ($eligibilityData['twelthDetails']['cutoff'][$category] as $key => $row) {
                        $cutoffArray[] = ($key == 'cutOff12th') ? '12th: '.getFormattedScore($row['value'],'percentage') : $row['category'].': '.getFormattedScore($row['value'],'percentage');
                    }
                    $returnData['table']['XII']['cutoff'] = implode('<br> ',$cutoffArray);
                }
                $showCutOff = true;
            }
            else{
                $returnData['table']['XII']['cutoff'] = '--';
            }
            $subjects = empty($eligibilityData['twelthDetails']['subjects']) ? array() : $eligibilityData['twelthDetails']['subjects'];

            if(!empty($subjects)){
                $subjectPrefixHeading = count($subjects) > 1 ? 'Mandatory Subjects :' : 'Mandatory Subject :';
            }
            if(!empty($eligibilityData['twelthDetails']['description']) || !empty($subjects)){
                if(!empty($subjects)){
                    $returnData['table']['XII']['additionalInfo'] = $subjectPrefixHeading." ".implode(", ",$subjects);
                    if(!empty($eligibilityData['twelthDetails']['description'])){
                        $returnData['table']['XII']['additionalInfo'] .= "\n".$eligibilityData['twelthDetails']['description'];
                    }
                }
                else{
                    $returnData['table']['XII']['additionalInfo'] = $eligibilityData['twelthDetails']['description'];
                }
                $showEligibilityAdditionalInfo = true;
            }
            else{
                $returnData['table']['XII']['additionalInfo'] = '--';
            }
        }

        if(!empty($eligibilityData['graduationDetails'])){
            if(!empty($eligibilityData['graduationDetails']['categoryWiseScores'][$category])){
                $returnData['table']['graduation']['eligibility'] = getFormattedScore($eligibilityData['graduationDetails']['categoryWiseScores'][$category]['score'],$eligibilityData['graduationDetails']['scoreType'],$eligibilityData['graduationDetails']['categoryWiseScores'][$category]['maxScore']);
                $showEligibilityVal = true;
            }
            $returnData['table']['graduation']['qualification'] = 'Graduation';
            $returnData['table']['graduation']['type'] = 'section';
            $returnData['table']['graduation']['cutoff'] = '--';
            if(!empty($eligibilityData['graduationDetails']['description']) || !empty($eligibilityData['graduationDetails']['courseSpecMapping'])){
                if(!empty($eligibilityData['graduationDetails']['courseSpecMapping'])){
                    $courseSpecMapping = array();
                    foreach ($eligibilityData['graduationDetails']['courseSpecMapping'] as $row) {
                        $courseSpecMapping[$row['baseCourseId']]['name'] = $row['baseCourseName'];
                        if(!empty($row['specializationName'])){
                            $courseSpecMapping[$row['baseCourseId']]['specializations'][] = $row['specializationName'];
                        }
                    }
                    $courseSpecMappingStringArr = array();
                    foreach ($courseSpecMapping as $row) {
                        $courseSpecMappingStringArr[] = empty($row['specializations']) ? $row['name'] : $row['name']."( ".implode(", ",$row['specializations']).") ";
                    }
                    $baseSpecStringHeading = count($courseSpecMapping) > 1 ? 'Mandatory Courses :' : 'Mandatory Course :';
                    $returnData['table']['graduation']['additionalInfo'] = $baseSpecStringHeading." ".implode(", ", $courseSpecMappingStringArr);
                    if(!empty($eligibilityData['graduationDetails']['description'])){
                        $returnData['table']['graduation']['additionalInfo'] .= "\n".$eligibilityData['graduationDetails']['description'];
                    }
                }
                else{
                    $returnData['table']['graduation']['additionalInfo'] = $eligibilityData['graduationDetails']['description'];
                }
                $showEligibilityAdditionalInfo = true;
            }
            else{
                $returnData['table']['graduation']['additionalInfo'] = '--';
            }
        }

        if(!empty($eligibilityData['postgraduationDetails'])){
            if(!empty($eligibilityData['postgraduationDetails']['categoryWiseScores'][$category])){
                $returnData['table']['postgraduation']['eligibility'] = getFormattedScore($eligibilityData['postgraduationDetails']['categoryWiseScores'][$category]['score'],$eligibilityData['postgraduationDetails']['scoreType'],$eligibilityData['postgraduationDetails']['categoryWiseScores'][$category]['maxScore']);
                $showEligibilityVal = true;
            }
            $returnData['table']['postgraduation']['qualification'] = 'Post Graduation';
            $returnData['table']['postgraduation']['type'] = 'section';
            $returnData['table']['postgraduation']['cutoff'] = '--';
            if(!empty($eligibilityData['postgraduationDetails']['description']) || !empty($eligibilityData['postgraduationDetails']['courseSpecMapping'])){
                if(!empty($eligibilityData['postgraduationDetails']['courseSpecMapping'])){
                    $courseSpecMapping = array();
                    foreach ($eligibilityData['postgraduationDetails']['courseSpecMapping'] as $row) {
                        $courseSpecMapping[$row['baseCourseId']]['name'] = $row['baseCourseName'];
                        if(!empty($row['specializationName'])){
                            $courseSpecMapping[$row['baseCourseId']]['specializations'][] = $row['specializationName'];
                        }
                    }
                    $courseSpecMappingStringArr = array();
                    foreach ($courseSpecMapping as $row) {
                        $courseSpecMappingStringArr[] = empty($row['specializations']) ? $row['name'] : $row['name']."( ".implode(", ",$row['specializations']).") ";
                    }
                    $baseSpecStringHeading = count($courseSpecMapping) > 1 ? 'Mandatory Courses :' : 'Mandatory Course :';
                    $returnData['table']['postgraduation']['additionalInfo'] = $baseSpecStringHeading." ".implode(", ", $courseSpecMappingStringArr);
                    if(!empty($eligibilityData['postgraduationDetails']['description'])){
                        $returnData['table']['postgraduation']['additionalInfo'] .= "\n".$eligibilityData['postgraduationDetails']['description'];
                    }
                }
                else{
                    $returnData['table']['postgraduation']['additionalInfo'] = $eligibilityData['postgraduationDetails']['description'];
                }
                $showEligibilityAdditionalInfo = true;
            }
            else{
                $returnData['table']['postgraduation']['additionalInfo'] = '--';
            }
        }

        

        if(!empty($eligibilityData['exams'])){
            foreach ($eligibilityData['exams'] as $examName => $examData) {
                $returnData['table'][$examData['examName']]['type'] = 'exam';
                if(!empty($examData['categoryWiseScores'][$category])){
                    $returnData['table'][$examData['examName']]['eligibility'] = getFormattedScore($examData['categoryWiseScores'][$category]['score'],$examData['scoreType'],$examData['categoryWiseScores'][$category]['maxScore']);
                    $showEligibilityVal = true;
                }
                else{
                    $returnData['table'][$examData['examName']]['eligibility'] = '--';
                }
                $returnData['table'][$examData['examName']]['url'] = $examData['examUrl'];
                $returnData['table'][$examData['examName']]['qualification'] = $examData['examName'];
                if(!empty($examData['cutOffData']['relatedStates'])){
                    $showEligibilityAdditionalInfo = true;
                    $returnData['table'][$examData['examName']]['relatedStates'] = implode(", ",$examData['cutOffData']['relatedStates']);
                    $returnData['table'][$examData['examName']]['additionalInfo'] = "Related States: ".implode(", ",$examData['cutOffData']['relatedStates']);
                }
                else{
                    $returnData['table'][$examData['examName']]['relatedStates'] = array();
                    $returnData['table'][$examData['examName']]['additionalInfo'] = '--';
                }

                if(empty($returnData['cutOffYear']) && !empty($examData['cutOffData']['cutOffYear'])){
                    $returnData['cutOffYear'] = $examData['cutOffData']['cutOffYear'];
                }

                $cutoffArray = array();
                $quotaCount = array();
                foreach ($examData['cutOffData']['roundsCutOffData'] as $round => $roundData) {
                    foreach ($roundData[$category] as $quota => $scoreData) {
                        $cutoffArray[$round][ucfirst($quota)] = getFormattedScore($scoreData['score'],$examData['cutOffData']['cutOffUnit'],$scoreData['maxScore']);
                    }
                }
                $returnData['examCutoffData'][$examData['examName']] = $cutoffArray;
                if(empty($cutoffArray)){
                    $returnData['table'][$examData['examName']]['cutoff'] = '--';
                }
                else{
                    foreach ($cutoffArray as $round => $roundData) {
                        $showQuotaText = (count($roundData) == 1 && key(reset($roundData)) == 'All_india') ? false : true;
                        foreach ($roundData as $quota => $value) {
                            if(stripos($quota,'Related_states') !== false){
                                $temp = explode(':',$quota);
                                $quota = $temp[0];                            
                            }

                            if(empty($quotaCount[$quota])){
                                $quotaCount[$quota] = 1;
                            }
                            else{
                                ++$quotaCount[$quota];
                            }

                            if($round == count($cutoffArray)){
                                $quotaText = ($showQuotaText) ? ucwords(implode(explode('_',$quota),' ')): '';
                                $returnData['table'][$examData['examName']]['cutoff'][] = array('cutoffstr'=>$quotaText.': '.$value,'quota'=>$quota,'round'=>$round);
                            }
                        }
                    }
                    $showCutOff = true;
                    $returnData['table'][$examData['examName']]['quotaCount'] = $quotaCount;
                }
            }
        }


        $returnData['batch_year'] = $eligibilityData['year'];
        $returnData['age_min'] = $eligibilityData['minAge'];
        $returnData['age_max'] = $eligibilityData['maxAge'];
        $returnData['work_min'] = $eligibilityData['minWorkEx'];
        $returnData['work_max'] = $eligibilityData['maxWorkEx'];
        $returnData['international_students_desc'] = $eligibilityData['internationalDescription'];
        $returnData['description'] = $eligibilityData['description'];
        $returnData['showEligibilityWidget'] = true;
        $returnData['showEligibilityVal'] = $showEligibilityVal;
        $returnData['showCutOff'] = $showCutOff;
        $returnData['showEligibilityAdditionalInfo'] = $showEligibilityAdditionalInfo;

        // echo json_encode($returnData);die;
        // _p($returnData);die;
        return $returnData;

    }

    /*public function getCourseEligibilityData($course_id,$category){
        $eligibilityData = array();
        $eligibleCategories = array();
        $data = $this->coursedetailmodel->getEligibilityData($course_id);
        if(empty($data)){
            return array();
        }

        array_walk_recursive($data, function($value,$key) use (&$eligibleCategories){
            if($key === 'category' && !empty($value)){                          
                $eligibleCategories[] = $value;
            }
        });
        $eligibleCategories = array_values(array_unique($eligibleCategories));
        sort($eligibleCategories);
        $eligibilityData['categories'] = $eligibleCategories;
        $eligibilityData['eligibilitySelectedCategory'] = (in_array($category,$eligibleCategories)) ? $category : 'general';

        foreach ($data as $key => $value) {
            if(!empty($data[$key])){
                $data[$key] = $this->formatEligibilityData($data[$key],$key,$category);
            }
        }

        $desiredIndexOrder = array('X'=>1,'XII'=>2,'graduation'=>3,'postgraduation'=>4);
        
        //_p($data);die;
        // don't override these variables
        $examData = array();$stateIds = array();$statesData = array();$customExams = array();
        $eligibilitySectionWithoutExam = array('X','XII','graduation','postgraduation');

        foreach ($eligibilitySectionWithoutExam as $val) {
            $eligibilityData['table'][$val]['eligibility']    = '--';
            $eligibilityData['table'][$val]['additionalInfo'] = '--';
            $eligibilityData['table'][$val]['cutoff']         = '--';
            $eligibilityData['table'][$val]['type']           = 'section';
        }
        $examIds = array();
        if(!empty($data['cutoff'])){
            foreach ($data['cutoff'] as $row) {
                $examIds[] = $row['exam_id'];
            }
        }
        if(!empty($data['examEligibility'])){
            foreach ($data['examEligibility'] as $row) {
                $examIds[] = $row['exam_id'];
            }
        }
        $examIds = array_unique(array_values(array_filter($examIds)));
        if(!empty($examIds)){
            $examData = $this->examLib->getExamDataByExamIds($examIds);                
            foreach ($examData as $examId=>$row) {
                $eligibilityData['table'][$row['name']]['eligibility']    = '--';
                $eligibilityData['table'][$row['name']]['additionalInfo'] = '--';
                $eligibilityData['table'][$row['name']]['cutoff']         = '--';
                if($row['scope'] == 'national') {
                    $examDomainName = SHIKSHA_HOME;
                }
                else {
                    $examDomainName = SHIKSHA_STUDYABROAD_HOME;
                }
                $eligibilityData['table'][$row['name']]['url']            = !empty($row['url']) ? addingDomainNameToUrl(array('url' => $row['url'], 'domainName' =>$examDomainName)) : NULL;
            }
        }

        if(!empty($data['categoryWise'])){
            foreach ($data['categoryWise'] as $key => $row) {                
                $eligibilityData['table'][$row['standard']]['qualification'] = getQualificationTextForEligibility($row['standard']);

                if($row['value']){
                    if(!$eligibilityData['showEligibilityVal']){
                        $eligibilityData['showEligibilityVal'] = true;
                    }

                    $eligibilityData['table'][$row['standard']]['eligibility']   = getFormattedScore($row['value'],$row['unit'],$row['max_value']);
                }

                if(!empty($row['specific_requirement'])){
                     if(!$eligibilityData['showEligibilityAdditionalInfo']){
                        $eligibilityData['showEligibilityAdditionalInfo'] = true;
                     }
                    $eligibilityData['table'][$row['standard']]['additionalInfo'] = $row['specific_requirement'];
                }
            }        
        }
        
        
        if(!empty($data['basic']['subjects'])){
            if(!$eligibilityData['showEligibilityAdditionalInfo']){
                $eligibilityData['showEligibilityAdditionalInfo'] = true;
            }  
            $subjectsData = json_decode($data['basic']['subjects']);
            if(count($subjectsData) > 1){
                $subjectPrefixHeading = 'Mandatory Subjects :';
            }else{
                $subjectPrefixHeading = 'Mandatory Subject :';
            }
            $eligibilityData['table']['XII']['additionalInfo'] = $eligibilityData['table']['XII']['additionalInfo'] == '--' ? $subjectPrefixHeading." ".implode(", ", $subjectsData) : $subjectPrefixHeading." ".implode(", ", $subjectsData)."\n".$eligibilityData['table']['XII']['additionalInfo'];
            $eligibilityData['table']['XII']['qualification']  = getQualificationTextForEligibility('XII');
        }

        $temp = array('graduation','postgraduation');
        foreach ($temp as $val) {
            if(!empty($data['graduationSubject'][$val])){
                $baseSpecStringArray = array();                
                foreach ($data['graduationSubject'][$val] as $baseCourseId => $specialization) {
                    $baseSpecString = $this->baseCourseRepo->find($baseCourseId)->getName();
                    $specializationsWithName = array();
                    foreach ($specialization as $key => $specializationId) {
                        if($specializationId > 0)
                            $specializationsWithName[] = $this->hierarchyRepo->findSpecialization($specializationId)->getName();
                    }
                    if(!empty($specializationsWithName)){
                        $baseSpecStringArray[] = $baseSpecString." (".implode(", ", $specializationsWithName).") ";
                    }
                    else{
                        $baseSpecStringArray[] = $baseSpecString;
                    }
                }
                 if(!$eligibilityData['showEligibilityAdditionalInfo']){
                        $eligibilityData['showEligibilityAdditionalInfo'] = true;
                 }

                 if(count($baseSpecStringArray)>1){
                    $baseSpecStringHeading = 'Mandatory Courses :';
                 }else{
                    $baseSpecStringHeading = 'Mandatory Course :';
                 }

                $eligibilityData['table'][$val]['additionalInfo'] = $eligibilityData['table'][$val]['additionalInfo'] == '--' ? $baseSpecStringHeading." ".implode(", ", $baseSpecStringArray) : $baseSpecStringHeading." ".implode(", ", $baseSpecStringArray)."\n".$eligibilityData['table'][$val]['additionalInfo'];
                $eligibilityData['table'][$val]['additionalInfo'] = $eligibilityData['table'][$val]['additionalInfo'];
                $eligibilityData['table'][$val]['qualification']  = getQualificationTextForEligibility($val);
            }

        }

        //_p($eligibilityData);die;
        // cutoff
        $cutoffArray = array();
        foreach ($data['cutoff'] as $cutoff) {
            $eligibilityData['showCutOff'] = true;            
            if($cutoff['cut_off_type'] == '12th'){
                if($cutoff['quota'] == 'cutOff12th'){
                    $cutoffArray['12th']['12th'] = getFormattedScore($cutoff['cut_off_value'],'percentage');
                }
                else{
                    $cutoffArray['12th'][ucfirst($cutoff['quota'])] = getFormattedScore($cutoff['cut_off_value'],'percentage');
                }
            }
            else if($cutoff['cut_off_type'] == 'exam'){
                if(empty($cutoff['exam_id'])){
                    $examName = $cutoff['custom_exam'];
                    if(empty($eligibilityData['table'][$examName])){
                        $customExams[] = $examName;
                        $eligibilityData['table'][$examName]['eligibility'] = '--';
                        $eligibilityData['table'][$examName]['additionalInfo'] = '--';
                        $eligibilityData['table'][$examName]['cutoff'] = '--';
                    }
                }
                else{
                    $examName = $examData[$cutoff['exam_id']]['name'];
                }
                if(empty($eligibilityData['cutOffYear']) && !empty($cutoff['exam_year'])){
                    $eligibilityData['cutOffYear'] = $cutoff['exam_year'];
                }
                if(empty($desiredIndexOrder[$examName])){
                    $desiredIndexOrder[$examName] = count($desiredIndexOrder)+1;
                }
                if($examName)
                    $cutoffArray['exam'][$examName][$cutoff['round']][ucfirst($cutoff['quota'])] = getFormattedScore($cutoff['cut_off_value'],$cutoff['cut_off_unit'],$cutoff['exam_out_of']);
            }
        }
        
        if(!empty($cutoffArray['12th'])){
            if(count($cutoffArray['12th']) == 1 && !empty($cutoffArray['12th']['12th'])){
                $eligibilityData['table']['XII']['cutoff'] = $cutoffArray['12th']['12th'];
            }
            else{
                foreach ($cutoffArray['12th'] as $key => $value) {
                    $cutoffString[] = $key.": ".$value;
                }
                $eligibilityData['table']['XII']['cutoff'] = implode(', ',$cutoffString);
            }
            $eligibilityData['table']['XII']['qualification']  = getQualificationTextForEligibility('XII');            
        }

        if(!empty($cutoffArray['exam'])){
            $eligibilityData['examCutoffData'] = $cutoffArray['exam'];
            foreach ($cutoffArray['exam'] as $examName => $examRow) {
                $maxRound      = max(array_keys($examRow))+1;
                $showRoundText = (count($examRow) > 1) ? "" :'';

                if($eligibilityData['table'][$examName]['cutoff'] == '--'){
                    $eligibilityData['table'][$examName]['cutoff'] = array();
                }
                if(empty($eligibilityData['table'][$examName]['qualification'])){
                    $eligibilityData['table'][$examName]['qualification'] = $examName;
                }

                $eligibilityData['table'][$examName]['relatedStates'] = array();
                $quotaCount = array();
                foreach ($examRow as $round => $roundData) {
                    $showQuotaText = (count($roundData) == 1 && key(reset($roundData)) == 'All_india') ? false : true;
                    foreach ($roundData as $quota => $value) {
                        if(stripos($quota,'Related_states') !== false){
                            $temp = explode(':',$quota);
                            if($temp[1] && empty($eligibilityData['table'][$examName]['relatedStates'])){
                                $eligibilityData['table'][$examName]['relatedStates'] = explode(',',$temp[1]);
                                $stateIds = array_merge($stateIds,explode(',',$temp[1]));                                
                            }
                            $quota = $temp[0];                            
                        }

                        if(empty($quotaCount[$quota])){
                            $quotaCount[$quota] = 1;
                        }
                        else{
                            ++$quotaCount[$quota];
                        }

                        if($round == $maxRound-1){
                            $quotaText = ($showQuotaText) ? ucwords(implode(explode('_',$quota),' ')): '';
                            if(stripos($quotaText,'Related States:') !== false){                                
                                $temp = explode(':',$quotaText);
                                $quotaText = $temp[0];
                            }
                            if(!empty($showRoundText) && !empty($quotaText)){
                                $eligibilityData['table'][$examName]['cutoff'][] = array('cutoffstr'=>$quotaText.' ('.$showRoundText.'): '.$value,'quota'=>$quota,'round'=>$round+1);
                            }
                            else{
                                $eligibilityData['table'][$examName]['cutoff'][] = array('cutoffstr'=>$quotaText.': '.$value,'quota'=>$quota,'round'=>$round);
                            }
                        }
                    }
                }
                $eligibilityData['table'][$examName]['quotaCount'] = $quotaCount;
            }
        }


        if(!empty($stateIds)){
            $statesData = $this->locationRepo->findMultipleStates($stateIds);
            foreach ($eligibilityData['table'] as $key => $row) {
                if(!empty($row['relatedStates'])){
                    $relatedStates = array_map(function($a) use ($statesData){
                        return $statesData[$a]->getName();
                    },$row['relatedStates']);
                    $eligibilityData['table'][$key]['relatedStates'] = implode($relatedStates,', ');
                    $temp = count($relatedStates) > 1 ? "Related States: " : "Related State: ";
                    if(!$eligibilityData['showEligibilityAdditionalInfo']){
                        $eligibilityData['showEligibilityAdditionalInfo'] = true;
                    }
                    $eligibilityData['table'][$key]['additionalInfo'] = $temp.implode($relatedStates,', ');
                }
            }
        }

        
        if(!empty($data['examEligibility'])){
            foreach ($data['examEligibility'] as $key => $row) {        
                $examName = empty($row['exam_id']) ? $row['exam_name'] : $examData[$row['exam_id']]['name'];
                
                if($examName){
                    if(empty($eligibilityData['table'][$examName])){
                        $customExams[] = $examName;
                        $eligibilityData['table'][$examName]['eligibility'] = '--';
                        $eligibilityData['table'][$examName]['additionalInfo'] = '--';
                        $eligibilityData['table'][$examName]['cutoff'] = '--';
                    }
                    $eligibilityData['table'][$examName]['qualification'] = $examName;
                    if(empty($desiredIndexOrder[$examName])){
                        $desiredIndexOrder[$examName] = count($desiredIndexOrder)+1;
                    }

                    if(!$eligibilityData['showEligibilityVal'] && $row['value']){
                        $eligibilityData['showEligibilityVal'] = true;
                    }
                    $eligibilityData['table'][$examName]['eligibility']   = getFormattedScore($row['value'],$row['unit'],$row['max_value']);
                }

            }
        }


        
        if(!empty($data['basic']['batch_year']))
            $eligibilityData['batch_year'] = $data['basic']['batch_year'];


        if(!empty($data['basic']['age_min']))
            $eligibilityData['age_min'] = $data['basic']['age_min'];

        if(!empty($data['basic']['age_max']))
            $eligibilityData['age_max'] = $data['basic']['age_max'];

        if(!empty($data['basic']['work-ex_min']))
            $eligibilityData['work_min'] = $data['basic']['work-ex_min'];

        if(!empty($data['basic']['work-ex_max']))
            $eligibilityData['work_max'] = $data['basic']['work-ex_max'];

        if(!empty($data['basic']['international_students_desc']))
            $eligibilityData['international_students_desc'] = $data['basic']['international_students_desc'];

        if(!empty($data['basic']['description']))
            $eligibilityData['description'] = $data['basic']['description'];

        
        if(!empty($eligibilityData['table'])){
            uksort($eligibilityData['table'], function($a, $b) use ($desiredIndexOrder) {
                return $desiredIndexOrder[$a] > $desiredIndexOrder[$b] ? 1 : -1;
            });
        }

        foreach ($eligibilitySectionWithoutExam as $key => $section) { 
            if(empty($eligibilityData['table'][$section]['qualification'])){
                unset($eligibilityData['table'][$section]);
            }
        }
        $eligibilityData['showEligibilityWidget'] = true;

        if(empty($eligibilityData['table']) && empty($eligibilityData['description']) && empty($eligibilityData['international_students_desc']) && empty($eligibilityData['work_min']) && empty($eligibilityData['work_max']) && empty($eligibilityData['age_min']) && empty($eligibilityData['age_max'])){
            $eligibilityData['showEligibilityWidget'] = false;
        }
        // echo json_encode($eligibilityData);die;
        return $eligibilityData;
    }*/

    public function getFeesDescription($courseId){
        return $this->coursedetailmodel->getFeesDescription($courseId);
    }

    public function getSelectedCategory($selectedCategory=''){
        if($selectedCategory == '') {
            $selectedCategory = 'general';
            $cookieData = array(
                'name' => 'selectedCategory',
                'value' => $selectedCategory,
                'expire' => (24*60*60),
            );
            $this->CI->load->helper('cookie');
            $this->CI->input->set_cookie($cookieData, TRUE);
        }
        return $selectedCategory;
    }

    

    public function getCourseStructureData($courseId){
        //get data from model
        $formatCourseStructure  = array();
        $courseStructure =  $this->coursedetailmodel->getCourseStructureData($courseId);
        foreach ($courseStructure as $key => $value) {
           $formatCourseStructureTemp['courses_offered'] = htmlentities($value['courses_offered']);
           $formatCourseStructure[$value['period_value']]['structure'][]  = $formatCourseStructureTemp;
           $formatCourseStructure[$value['period_value']]['period']  = $value['period'];
        }
        
        return $formatCourseStructure;
    }

    public function getAdmissionsData($courseId){
        $formatAdmission = array();
        $admission =  $this->coursedetailmodel->getAdmissionsData($courseId);
        foreach ($admission as $key => $value) {
            $formatAdmissionTemp['admission_name']        = htmlentities(($value['admission_name'] == 'Others')?$value['admission_name_other'] : $value['admission_name']);
            $formatAdmissionTemp['admission_description'] = $value['admission_description'];
            $formatAdmission[$value['stage_order']]       = $formatAdmissionTemp;
        }
        return $formatAdmission;
    }

    public function getPlacementHeading($type,$typeId,$courseName,$instituteName,$batch_year){
        $placementHeading = '';

        switch ($type) {
              case 'clientCourse':
                  //$placementHeading = 'Showing placement details for '.$courseName.' programs of '.$instituteName;
                  $placementHeading = '';
                  break;
              case 'streamId':
                  $streamObj = $this->hierarchyRepo->findStream($typeId);
                  if($streamObj) {
                    $program = $streamObj->getName();
                  }
                  if($batch_year)
                    $placementHeading = 'Showing placement details for '.$program.' programs of '.$instituteName.' for '.$batch_year.' batch.';
                  else
                    $placementHeading = 'Showing placement details for '.$program.' programs of '.$instituteName;
                  break;
              case 'substreamId':
                  $program = $this->hierarchyRepo->findSubstream($typeId)->getName();
                  if($batch_year)
                    $placementHeading = 'Showing placement details for '.$program.' programs of '.$instituteName.' for '.$batch_year.' batch.';
                  else
                    $placementHeading = 'Showing placement details for '.$program.' programs of '.$instituteName;
                  break;
              case 'baseCourse':
                  $program = $this->baseCourseRepo->find($typeId)->getName();
                  if($batch_year)
                    $placementHeading = 'Showing placement details for '.$program.' programs of '.$instituteName.' for '.$batch_year.' batch.';
                  else
                    $placementHeading = 'Showing placement details for '.$program.' programs of '.$instituteName;
                  break;
              default:                  
                  break;
        }

        return $placementHeading;
    }

    public function getRecruitmentCompanies($courseId){
        return $this->coursedetailmodel->getCompaniesMapped($courseId);
    }

    public function getSeatsData($courseId,$totalSeats){
        $formatSeats = array();
        $domicileRelatedStates = array();
        $relatedStates = '';
        $seatsData = $this->coursedetailmodel->getCourseSeats($courseId);
        
        
        $categories = $this->CI->config->item('categories');
        $domicileCategories = $this->CI->config->item('domicileCategories');
        $combinedCategories = array_merge($categories['default'], $categories['addmore']);

        $examNameMapping = array();
        $examIds = array_values(array_filter(array_map(function($a){
            if($a['breakup_by'] == 'exam'){
                return $a['exam_id'];
            }
            return null;
        },$seatsData)));
        if(!empty($examIds)){
            $examNameMapping = $this->examLib->getExamDetailsByIds($examIds);
        }

        if(!empty($seatsData)){
            $formatCategories = array();
            $formatExams      = array();
            $formatDomicile   = array();
            foreach ($seatsData as $key => $value) {
                if($value['breakup_by'] == 'category'){
                    $temp               = array();
                    $temp['category']   = $combinedCategories[$value['category']];
                    $temp['seats']      = $value['seats'];
                    $formatCategories[] = $temp;                    
                }else if($value['breakup_by'] == 'exam'){
                    $temp          = array();
                    $examName = $examNameMapping[$value['exam_id']]['examName'];
                    if(!empty($examName)){
                        $temp['exam']  = $examName;
                        $temp['seats'] = $value['seats'];
                        $formatExams[] = $temp;                            
                    }
                }else if($value['breakup_by'] == 'domicile'){
                    $temp             = array();
                    
                    if(!empty($value['related_state_list']))
                        $relatedStates    = $value['related_state_list'];

                    $temp['category'] = $domicileCategories[$value['category']];
                    $temp['seats']    = $value['seats'];
                    $formatDomicile[] = $temp;    
                }
            }

            if(!empty($relatedStates)){
                $statesObj = $this->locationRepo->findMultipleStates(explode(",", $relatedStates));        
                $relatedStateToDisplay = '';
                if(count($statesObj) > 1){
                    $headingStates = 'States under this quota: ';
                }else{
                    $headingStates = 'State under this quota: ';
                }
                foreach ($statesObj as $key => $state) {
                    $relatedStatesToDisplay .= $state->getName().", ";
                }
                $relatedStatesToDisplay = rtrim($relatedStatesToDisplay,", ");
                if(!empty($relatedStatesToDisplay))
                    $relatedStatesToDisplay = $headingStates.$relatedStatesToDisplay;
            }
            $formatSeats['categoryWiseSeats'] = $formatCategories;
            $formatSeats['examWiseSeats']     = $formatExams;
            $formatSeats['domicileWiseSeats'] = $formatDomicile;
            $formatSeats['relatedStates']     = $relatedStatesToDisplay;
        }

        if(!empty($totalSeats)){
            $formatSeats['totalSeats'] = $totalSeats;
        }

       // _p($formatSeats);die;
        return $formatSeats;
    }

    /**
     * [_getCourseDates description]
     * @author Ankit Garg <g.ankit@shiksha.com>
     * @date   2016-11-29
     * @return [type]     [this function will return either course's online application form date or important date]
     */
    function getCourseDates($courseObj, $data) {
        
        $this->CI->load->library('Online/OnlineFormUtilityLib');
        $OnlineFormUtilityLib = new OnlineFormUtilityLib();
        $OnlineFormData = $OnlineFormUtilityLib->getOAFBasicInfo($courseObj->getId());        
        $returnArr = array();
        //if online application form date is less than current date
        if(strtotime(date('Y-m-d')) < strtotime($OnlineFormData[$courseObj->getId()]['of_last_date']) ) {
            if(!empty($OnlineFormData[$courseObj->getId()]['of_external_url'])) {
                $returnArr['url'] = $OnlineFormData[$courseObj->getId()]['of_external_url'];
            }
            else if(!empty($OnlineFormData[$courseObj->getId()]['of_seo_url'])) {
                $returnArr['url'] = $OnlineFormData[$courseObj->getId()]['of_seo_url'];
            }
            $returnArr['type'] = 'onlineForm';
            $returnArr['date'] = convertToFormattedDate($OnlineFormData[$courseObj->getId()]['of_last_date']);
            $returnArr['eventName'] = 'Last Date to Apply';
            $returnArr['externalFlag'] = ($OnlineFormData[$courseObj->getId()]['isExternal'] == 1) ? 0 : 1;
        }
        else {
            $importantDate = array();
            foreach($data['importantDatesData']['importantDates'] as $val) {
                if($val['showUpcoming']) {
                    $importantDate = $val;
                    break;
                }
            }
            if(!empty($importantDate)) {
                $returnArr['type'] = 'importantDates';
                $returnArr['date'] = getFormattedDate($importantDate);
                $returnArr['eventName'] = $importantDate['event_name'];
            }
        }
        return $returnArr;
    }

    public function getImportantDatesData($courseObj,$includeExamDates = true){
        $courseId = $courseObj->getId();
        $courseDates = array();$examDates = array();
        
        $courseDates = $this->coursedetailmodel->getCourseImportantDates($courseId);
        foreach ($courseDates as &$date) {
            $date['type'] = 'others';
        }

        if($includeExamDates){
            $examIds = array();
            $eligibleExams = $courseObj->getEligibility();
            foreach ($eligibleExams as $category => $exams) {
                foreach ($exams as $exam) {
                    $examIds[] = $exam->getExamId();
                }
            }
            $examIds = array_unique($examIds);
            if(!empty($examIds)){
                $examDates = $this->coursedetailmodel->getExamDates($examIds);
                foreach ($examDates as &$date) {
                    if(stripos($date['event_name'],$date['exam_name']) === false){
                        $date['event_name'] = $date['exam_name']. ' - '.$date['event_name'];
                    }
                }
            }
        }

        $courseDates = array_merge($courseDates,$examDates);
        $courseDates = array_map(function($a){
            $a['event_name'] = htmlentities($a['event_name']);
            return $a;
        }, $courseDates);
        $courseDates = $this->sortImportantDates($courseDates);
        // _p($courseDates);die;
        return $courseDates;
    }

    private function sortImportantDates($importantDates){
        $dates = json_decode(json_encode($importantDates),true);//_p($dates);die;

        foreach ($dates as &$date) {
            if(empty($date['end_year']) && !empty($date['start_year'])){
                $date['end_year'] = $date['start_year'];
            }

            if(empty($date['end_month']) && !empty($date['start_month'])){
                $date['end_month'] = $date['start_month'];
            }
            else if(!empty($date['end_month']) && empty($date['start_month'])){
                $date['start_month'] = $date['end_month'];
            }

            if(empty($date['start_date']) && !empty($date['end_date'])){
                $date['start_date'] = 1;
            }
            else if(empty($date['end_date']) && !empty($date['start_date'])){
                $date['end_date'] = $date['start_date'];
            }
            else if(empty($date['start_date']) && empty($date['end_date'])){
                $date['start_date'] = 1;
                if($date['end_month'] == 2){
                    $date['end_date'] = 28;
                }
                else{
                    $date['end_date'] = in_array($date['end_month'],array(1,3,5,7,8,10,12)) ? 31 : 30;
                }
            }
        }

        foreach ($dates as $key => &$date) {
            if(!empty($date['end_year'])){
                $currentYear = date('Y');$currentMonth = date('n');$currentDate = date('j');
                if(empty($date['start_date'])){
                    $date['start_date'] = 1;
                }
                if(empty($date['end_date'])){
                    if($date['end_month'] == 2){
                        $date['end_date'] = 28;
                    }
                    else{
                        $date['end_date'] = in_array($date['end_month'],array(1,3,5,7,8,10,12)) ? 31 : 30;
                    }
                }
                if(strtotime($date['start_year'].'-'.$date['start_month'].'-'.$date['start_date']) <= strtotime(date('Y-m-d')) && (strtotime($date['end_year'].'-'.$date['end_month'].'-'.$date['end_date']) >= strtotime(date('Y-m-d')))){
                    $date['start_date'] = $currentDate;$date['start_month'] = $currentMonth;$date['start_year'] = $currentYear;
                }
            }
        }
        // _p($dates);die;
        // sort by start date
        uasort($dates,function($a,$b){
            $first = mktime(0,0,0,$a['start_month'],$a['start_date'],$a['start_year']);
            $second = mktime(0,0,0,$b['start_month'],$b['start_date'],$b['start_year']);
            if($first == $second){
                $first = mktime(0,0,0,$a['end_month'],$a['end_date'],$a['end_year']);
                $second = mktime(0,0,0,$b['end_month'],$b['end_date'],$b['end_year']);
                if($first == $second){
                    return 0;
                }
                return ($first < $second) ? -1 : 1;
            }
            return ($first < $second) ? -1 : 1;
        });

        //remove all dates less than the past 8 months
        $checkDate = strtotime("-8 months");
        foreach ($dates as &$date) {
            if(strtotime($date['start_year'].'-'.$date['start_month'].'-'.$date['start_date']) < $checkDate){
                $date = null;
            }
        }
        $sortedDates = array();
        foreach ($dates as $key => $row) {
            if($row){
                $sortedDates[] = $importantDates[$key];
            }
        }
        // _p($sortedDates);die;
        return $sortedDates;
    }

    public function formatImportantDatesBySource($importantDates,$source){
        $upcomingShown = false;
        foreach ($importantDates as $key => &$date) {
            $date['showUpcoming'] = false;
            if(checkIfDateIsFutureDate($date)){
                $date['showUpcoming'] = true;
                if(!$upcomingShown){
                    $upcomingShown = true;
                    $upcomingIndex = $key;
                }
            }
        }
        if($source == 'page'){
            $maxToShow = $this->CI->config->item('maxImportantDatesToShow');
            if($upcomingShown){
                if(count($importantDates) >= $maxToShow){
                    if($upcomingIndex+$maxToShow > count($importantDates)){
                        $upcomingIndex = count($importantDates) - $maxToShow;
                    }
                    $importantDates = array_slice($importantDates, $upcomingIndex , $maxToShow);
                }
            }
            else{
                $importantDates = array_slice($importantDates,(0-$maxToShow),$maxToShow);
            }
        }
        else if($source == 'layer'){
            //show 3 past dates with in last 8 months
            $pastDates = array();
            foreach ($importantDates as $dateKey=>$dateValue) {
                
                if(!empty($dateValue['showUpcoming'])){
                    continue;
                }
                $startDate = $dateValue['start_date'];
                if(empty($dateValue['start_date'])){
                    $startDate = 1;
                }
                if(strtotime($dateValue['start_year'].'-'.$dateValue['start_month'].'-'.$startDate) < strtotime(date('Y-m-d'))){
                    $pastDates[] = $dateValue;
                    $importantDates[$dateKey] = null;
                }
            }
            $importantDates = array_values(array_filter($importantDates));
            if(empty($importantDates)){
                $importantDates = array_slice($pastDates,-3,3);
            }
            else{
                $importantDates = array_merge(array_slice($pastDates,-3,3),$importantDates);
            }
        }

        return $importantDates;
    }

    public function getComparedCourses(){
        $courseList = array();
        if(isset($_COOKIE['compare-global-data']) && !empty($_COOKIE['compare-global-data'])){
            $data = $_COOKIE['compare-global-data'];
            $data = explode("|", $data);
            foreach ($data as $key => $value) {
                $courseList[] = array_shift(explode("::", $value));
            }
        }
        return $courseList;
    }

    function getCourseRank($courseObj) {
        $courseId = $courseObj->getId();
        $this->CI->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
        $rankingConfig = $this->CI->config->item('rankingConfig');
        $this->rankingCommonLib = RankingPageBuilder::getRankingPageCommonLib();
        $courseRankData = $this->rankingCommonLib->getSourceWiseCourseRanks($courseId, 100);
        
        $this->CI->load->builder('rankingV2/RankingPageBuilder');
        $builder = new RankingPageBuilder;
        $this->rankingPageUrlManagerLib = $builder->getURLManager();

        $topRankingPageId = '';
        //_p($courseRankData);die;
        foreach($courseRankData as $key => $data){
            $id = $data['ranking_page_id'];
            if(empty($topRankingPageId)) {
                $topRankingPageId = $data['ranking_page_id'];
            }
            $url = $this->rankingPageUrlManagerLib->getRankingPageURLById($id);
            $courseRankData[$key]['url'] = $url;
            $courseRankData[$key]['type'] = 'course';
            if($courseRankData[$key]['publisher_id'] == $rankingConfig['NIRF_Publisher_Id']  && ($courseRankData[$key]['ranking_page_id'] ==  $rankingConfig['MBA_Ranking_Page_Id'] || $courseRankData[$key]['ranking_page_id'] == $rankingConfig['EXECUTIVE_MBA_Ranking_Page_Id']) && $courseRankData[$key]['rank'] > 50){
                 if( $courseRankData[$key]['rank'] > 50 &&  $courseRankData[$key]['rank'] < 76) {
                      $courseRankData[$key]['rank'] = "51-75";
                 }
                 else if( $courseRankData[$key]['rank'] > 75 &&  $courseRankData[$key]['rank'] < 101) {
                      $courseRankData[$key]['rank'] = "76-100";
                 }
             }
        }
        
        
        $courseRankInterlinkData = array();
        //getting location based ranking page for 1st ranking page id fetched from db and rank data is at least 2
        if(!empty($topRankingPageId) && count($courseRankData) > 1) {
            $mainLocation = $courseObj->getMainLocation();
            $cityId = $mainLocation->getCityId();
            $cityObj = $this->locationRepo->findCity($cityId);
            $cityName = $mainLocation->getCityName();
            $virtualCityId = $cityObj->getVirtualCityId();
            //show link for virtual city
            if(!empty($virtualCityId)) {
                $virtualCityObj = $this->locationRepo->findCity($virtualCityId);
                $cityId = $virtualCityId;
                $cityName = $virtualCityObj->getName();
            }
            $stateId = $mainLocation->getStateId();
            if(!empty($cityId)) {
                $courseRankInterlinkData[] = $this->_getRankingPageLocationBasedUrl($cityId, $cityName, $topRankingPageId, 'city');
            }

            //dont create url in case of union territories 
            if(($mainLocation->getStateName() != $cityName) && empty($virtualCityId)) {
                $courseRankInterlinkData[] = $this->_getRankingPageLocationBasedUrl($stateId, $mainLocation->getStateName(), $topRankingPageId, 'state');
            }
        }
        // $courseRankData = $this->rankingCommonLib->getSourceWiseCourseRanks(250866);
        //_p($courseRankData); die;
        return array('courseRankData' => $courseRankData, 'courseRankInterlinkData' => $courseRankInterlinkData);
    }

    private function _getRankingPageLocationBasedUrl($locationId, $locationName, $topRankingPageId, $type) {
        $urlParams = array();
        if($type == 'city') {
            $urlParams['cityId']        = $locationId;
        }
        else if($type == 'state') {
            $urlParams['stateId']        = $locationId;
        }
        $urlParams['rankingPageId'] = $topRankingPageId;
                                
        $url = $this->rankingPageUrlManagerLib->buildUrlUsingParams($urlParams);
        $courseRankInterlinkData = array();
        $courseRankInterlinkData['type'] = 'location';
        $courseRankInterlinkData['url'] = SHIKSHA_HOME.'/'.$url;
        $courseRankInterlinkData['locationName'] = $locationName;
        $courseRankInterlinkData['anchorText'] = 'Top Ranked colleges in';

        return $courseRankInterlinkData;
    }

    public function prepareCourseData($courseObj,$validateuser, $customParam = array()){
        $this->CI->load->helper('listingCommon/listingcommon');
        $MPTMailerTrakingMapping  = $this->CI->config->item('MPTMailerTrakingMapping');
        $selectedCategory           = $this->CI->input->cookie('selectedCategory', TRUE);
        if(empty($selectedCategory))
            $selectedCategory = 'general';
        
        // $metaDescription                  = $this->getMetaDescription();
        $data['metaDescription']          = special_chars_replace($metaDescription);
        
        $categoriesConfig                 = $this->CI->config->item('categories');
        $categoriesNameMapping            = array_merge($categoriesConfig['default'], $categoriesConfig['addmore']);
        $data['categoriesNameMapping']    = $categoriesNameMapping;

        $data['selectedCategory']         = $selectedCategory;
        /*Course Current Location */
        $currentCityId                    = $this->CI->input->get("city");
        $currentLocalityId                = $this->CI->input->get("locality");
        
        $this->CI->benchmark->mark('preparing_current_loc_start');
        $currentLocationObj               = $this->getCourseCurrentLocation($courseObj,array($currentCityId),array($currentLocalityId));
        $this->CI->benchmark->mark('preparing_current_loc_end');
        
        if(!$customParam['instituteObj']) {
            $instituteObj                     = $this->instituteRepo->find($courseObj->getInstituteId(), array('scholarship'));
        }
        else {
            $instituteObj = $customParam['instituteObj'];
        }
        if(empty($instituteObj) || empty($instituteObj->getId())){
            show_404();
        }
        $courseAffiliatedData = $this->_getAffiliationName($courseObj);
        if(!empty($courseAffiliatedData)) {
            $data['affiliatedUniversityName']   = $courseAffiliatedData['name'];
            $data['affiliatedUniversityUrl']    = $courseAffiliatedData['url'];
            $data['affiliatedUniversityScope']  = $courseAffiliatedData['scope'];
        }

        $data['courseName']               = htmlentities($courseObj->getName());
        $data['courseId']                 = $courseObj->getId();
        $data['courseIsPaid']             = $courseObj->isPaid();

        $instituteTupleName               = getInstituteNameWithCityLocality($instituteObj->getName(),$instituteObj->getListingType(),$currentLocationObj->getCityName(),$currentLocationObj->getLocalityName());


        $data['currentLocationObj']       = $currentLocationObj;        
        
        $data['instituteName']            = htmlentities($instituteTupleName['instituteString']);
        
        $data['instituteURL']             = $instituteObj->getURL();
        $data['instituteObj']             = $instituteObj;
        $data['highlights']               = $courseObj->getUSP();
        $data['courseLocations']          = $courseObj->getLocations();
        if($customParam['source'] == 'autoGenerateBrochure') {
            $data['fees']                     = $this->getFeesDataForBrochure($courseObj);
            $data['eligibility']              = $this->getEligibilityAllCategories($courseObj->getId());
        }
        elseif(isset($customParam['ampViewFlag']) && $customParam['ampViewFlag'])
        {
            $data['fees']                     = $this->getFeesDataForAMP($courseObj,$selectedCategory,$currentLocationObj);

            $data['eligibility']              = $this->getEligibilityAllCategories($courseObj->getId());
        }
        else {
            $this->CI->benchmark->mark('fees_data_start');
            $data['fees']                     = $this->getFeesData($courseObj,$selectedCategory,$currentLocationObj);
            $this->CI->benchmark->mark('fees_data_end');

            $this->CI->benchmark->mark('eligibility_data_start');
            // $data['eligibility']              = $this->getCourseEligibilityData($courseObj->getId(),$selectedCategory);
            $data['eligibility']              = $this->getCourseEligibilityDataWithCache($courseObj->getId(),$selectedCategory);
            $this->CI->benchmark->mark('eligibility_data_end');
        }
        $data['scholarship']              = $instituteObj->getScholarships();

        $this->CI->benchmark->mark('course_structure_data_start');
        $data['courseStructure']          = $this->getCourseStructureData($courseObj->getId());
        $this->CI->benchmark->mark('course_structure_data_end');

        $this->CI->benchmark->mark('admission_data_start');
        $data['admissions']               = $this->getAdmissionsData($courseObj->getId());
        $this->CI->benchmark->mark('admission_data_end');

        $data['placements']               = $courseObj->getPlacements();
        $data['internships']              = $courseObj->getInternships();

        $this->CI->benchmark->mark('course_rank_start');
        $data['courseRankTopWidget']           = $this->getCourseRank($courseObj);
        $this->CI->benchmark->mark('course_rank_end');

        $this->CI->benchmark->mark('partner_data_start');
        $data['partners']                 = $this->getPartnersData($courseObj);
        $this->CI->benchmark->mark('partner_data_end');

        $data['recognitionData']          = $this->getRecognitionData($courseObj, $instituteObj);
        $data['predictorData']            = $this->getPredictorInfo($courseObj->getId());
        $data['cutOffData']            = $this->getCollegeCutOffInfo($courseObj->getId(), $instituteObj);
        $shortlistedCoursesOfUser = array();
  
        $data['isCourseShortlisted']      = (isset($validateuser[0]['userid'])) ? Modules::run('myShortlist/MyShortlist/getShortlistedCourse',$validateuser[0]['userid']) : array();
            // _p($data['isCourseShortlisted']); die;
        if(!empty($data['placements'])){
            $data['placementsHeading']        = $this->getPlacementHeading(
                                                                                    $data['placements']->getCourseType(),
                                                                                    $data['placements']->getCourseTypeId(),
                                                                                    $data['courseName'],
                                                                                    $data['instituteName'],
                                                                                    $data['placements']->getBatchYear()
                                                                                );
        }

        $this->CI->benchmark->mark('recruitment_companies_start');
        $data['placementsCompanies'] = $this->getRecruitmentCompanies($data['courseId']);
        $this->CI->benchmark->mark('recruitment_companies_end');

        $this->CI->benchmark->mark('seats_data_start');
        $data['seats']                    = $this->getSeatsData($courseObj->getId(),$courseObj->getTotalSeats());
        $this->CI->benchmark->mark('seats_data_end');

        $this->CI->benchmark->mark('imp_dates_start');
        $this->getImportantDates($courseObj,$data,$customParam['ampViewFlag']);
        $this->CI->benchmark->mark('imp_dates_end');
        //$data['OFData']                   = $this->getOFData($courseObj->getId());
        
        $data['isMultilocation']          = count($data['courseLocations']) > 1 ? true : false;

        $this->CI->benchmark->mark('course_dates_start');
        $data['courseDates']              = $this->getCourseDates($courseObj, $data);
        $this->CI->benchmark->mark('course_dates_end');
        
        
        // $data['instituteCourses'][] = array('course_id' => $courseObj->getId(),'course_name' => htmlentities($courseObj->getName()));

        $beaconPageName = 'CLP';

        $data['beaconTrackData'] = array(
                                        'pageIdentifier' => $beaconPageName,
                                        'pageEntityId' => $courseObj->getId(),
                                        'extraData' => array('url'=>get_full_url(), "childPageIdentifier"=> 'courseListingPage')
                                    );

        $courseTypeInfo =       $courseObj->getCourseTypeInformation();
        $data['gtmParams'] = array();
        
        if(!empty($courseTypeInfo)){
            $beaconEntryHieraries = array();
            $beaconExitHieraries = array();
            if(!empty($courseTypeInfo['entry_course'])){
                $entryHierarchies = $courseTypeInfo['entry_course']->getHierarchies();
                if(!empty($entryHierarchies)){
                    foreach ($entryHierarchies as $key => $value) {
                        $temp                     = array();
                        $temp['streamId']         = $value['stream_id'];
                        $temp['substreamId']      = $value['substream_id'];
                        $temp['specializationId'] = $value['specialization_id'];
                        $temp['primaryHierarchy'] = $value['primary_hierarchy'];                        
                        $beaconEntryHieraries[]   = $temp;
                        if(!empty($temp['streamId']))
                        $data['gtmParams']['stream']          = $data['gtmParams']['stream'].','.$temp['streamId'];
                        if(!empty($temp['substreamId']))
                        $data['gtmParams']['substream']       = $data['gtmParams']['substream'].','.$temp['substreamId'];
                        if(!empty($temp['specializationId']))
                        $data['gtmParams']['specialization']  = $data['gtmParams']['specialization'].','.$temp['specializationId'];
                    }
                    $data['beaconTrackData']['extraData']['hierarchy'] = $beaconEntryHieraries;
                }

                if($courseTypeInfo['entry_course']->getCourseLevel()){
                 $data['beaconTrackData']['extraData']['level']   = $courseTypeInfo['entry_course']->getCourseLevel()->getId();
                }

                if($courseTypeInfo['entry_course']->getCredential()){
                 $data['beaconTrackData']['extraData']['credential']   = $courseTypeInfo['entry_course']->getCredential()->getId();
                }
                if($courseTypeInfo['entry_course']->getBaseCourse()){
                    $data['beaconTrackData']['extraData']['baseCourseId'] = $courseTypeInfo['entry_course']->getBaseCourse();
                }
            }                        
        }
        if($customParam['ampViewFlag']){
            $data['beaconTrackData']['extraData']['isAmpPage'] = $customParam['ampViewFlag'];    
        }
        
        if($currentLocationObj){
            $data['beaconTrackData']['extraData']['cityId'] = $currentLocationObj->getCityId();
            $data['beaconTrackData']['extraData']['stateId'] = $currentLocationObj->getStateId();
            $data['beaconTrackData']['extraData']['countryId'] = 2;
            $data['gtmParams']['cityId']    = $data['beaconTrackData']['extraData']['cityId'];
            $data['gtmParams']['stateId']   = $data['beaconTrackData']['extraData']['stateId'];
            $data['gtmParams']['countryId'] = $data['beaconTrackData']['extraData']['countryId'];
        }

        $data['gtmParams'] = array_filter($data['gtmParams']);
        $data['gtmParams']['pageType'] = 'courseListing';
        if($courseTypeInfo['entry_course'] && $courseTypeInfo['entry_course']->getBaseCourse()){
            $data['gtmParams']['baseCourseId']                  = $courseTypeInfo['entry_course']->getBaseCourse();            
        }
        if($courseTypeInfo['entry_course'] && $courseTypeInfo['entry_course']->getCredential()){
            $data['gtmParams']['credential']                    = $courseTypeInfo['entry_course']->getCredential()->getId();           
        }
        if($courseObj->getEducationType()){
            $data['gtmParams']['educationType']                    = $courseObj->getEducationType()->getId();
            $data['beaconTrackData']['extraData']['educationType'] = $courseObj->getEducationType()->getId();
        }
        if($courseObj->getDeliveryMethod()){
            $data['gtmParams']['deliveryMethod']                    = $courseObj->getDeliveryMethod()->getId();
            $data['beaconTrackData']['extraData']['deliveryMethod'] = $courseObj->getDeliveryMethod()->getId();
        }
        if($courseObj->getInstituteId()){
            $data['gtmParams']['instituteId'] = $courseObj->getInstituteId();
        }
        $courseExams = $courseObj->getAllEligibilityExams();
        if(!empty($courseExams)){
            foreach($courseExams as $examId=>$examName){
                $data['gtmParams']['exams'] = $data['gtmParams']['exams'].','.$examId;
            }
        }
        if($validateuser!='false' && $validateuser[0]['experience']!==""){
                $data['gtmParams']['workExperience'] = $validateuser[0]['experience'];
        }
        
        foreach($data['gtmParams'] as $key=>$value){
            if($value[0]==','){
                $data['gtmParams'][$key] = substr($value,1);
            }
        }
        // Check and set the values is displayData array necessary for making the response eg. Institute_viewed
        $this->_checkAndSetDataForAutoResponseForCoursePage($validateuser, $data);        
        if($data['validResponseUser'] == 1){
            //create response from Recommendation mailers
            if($_REQUEST['fromRecoProdMailer'] == 1){
                $responseData['listing_id']       = $data['courseId'];
                $responseData['listing_type']     = 'course';
                $responseData['action_type']      = (isMobileRequest()) ? 'reco_widget_mailer_national_mobile' : 'reco_widget_mailer_national';
                $responseData['isViewedResponse'] = 'yes';
                $responseData['tracking_keyid']   = (isMobileRequest()) ? RECO_WIDGET_MAILER_NATIONAL_MOBILE : RECO_WIDGET_MAILER_NATIONAL;
                Modules::run("response/Response/createResponseByParams", $responseData);                
                //setting to stop javascript response call 
                $data['validResponseUser'] = 0;
            } else if($_REQUEST['fromwhere'] != null) {
                    $data['viewedResponseAction'] = 'Mailer_Promotion_Tuple';
                    $data['courseViewedTrackingPageKeyId'] = DESKTOP_NL_VIEWED_LISTING;
                    $data['courseViewedTrackingPageKeyId'] = $MPTMailerTrakingMapping[$_REQUEST['fromwhere']];
                }

        }

        if($customParam['source'] != 'autoGenerateBrochure') {
            $this->CI->benchmark->mark('clp_interlinking_start');
            $data['interLinkingLinks'] = $this->getInterLinkingForCourse($courseObj,$data['currentLocationObj']);
            $this->CI->benchmark->mark('clp_interlinking_end');
        }

        $data['sponsoredWidgetData'] = $this->getSponsoredWidgetData($data['courseId'], $courseObj);
        // _p($data['beaconTrackData']);die;

        return $data;
    }

    public function getInterLinkingForCourse($courseIdOrObj,$currentLocation){
        // _p($currentLocation);die;
        $categoryPageLinks = array();
        $returnData = array();

        if($courseIdOrObj instanceof Course){
            $courseObj = $courseIdOrObj;
            $courseId = $courseObj->getId();
        }
        else{
            $courseId = $courseIdOrObj;
            $courseObj = $this->courseRepo->find($courseId,array('location'));
        }
        if(empty($currentLocation)){
            $currentCityId     = $this->CI->input->get("city");
            $currentLocalityId = $this->CI->input->get("locality");
            $currentLocation   = $this->getCourseCurrentLocation($courseObj,array($currentCityId),array($currentLocalityId));
        }

        $this->CI->benchmark->mark('clp_interlinking_ranking_start');
        $this->CI->load->builder('rankingV2/RankingPageBuilder');
        $rankingBuilder = new RankingPageBuilder();
        $this->rankingURLManager = $rankingBuilder->getURLManager();

        $temp = $this->_getRankingPageLinksForCourse($courseObj,$currentLocation,$links,$links1);
        if(!empty($temp)){
            $returnData['rankingPageLinks'] = $temp;
        }
        $this->CI->benchmark->mark('clp_interlinking_ranking_end');
        
        $dataFromCache = $this->nationalCourseCache->getCourseInterLinking($courseObj->getId(),'category',array('cityId' => $currentLocation->getCityId()));
        if(!empty($dataFromCache['category'.':'.$currentLocation->getCityId()])){
            $returnData['categoryPageLinks'] = $dataFromCache['category'.':'.$currentLocation->getCityId()]['data'];
        }
        else{
            $this->CI->benchmark->mark('clp_interlinking_category_DB_start');

            $links = $this->_getCategoryPageLinksForCourse($courseObj,$currentLocation,true);
            $links1 = $this->_getCategoryPageLinksForCourse($courseObj,$currentLocation,false);

            $allLinks = array();
            foreach ($links as $key => $value) {
                $val1 = array_filter($value);$val2 = array_filter($links1[$key]);
                if(!empty($val1)){
                    $allLinks = array_merge($allLinks,$val1);
                }
                else if(!empty($val2)){
                    $allLinks = array_merge($allLinks,$val2);
                }
            }
            // _p($allLinks);die;
            $cityLinks = array();$stateLinks = array();
            foreach ($allLinks as $value) {
                if($value['type'] == 'city'){
                    $cityLinks[] = $value;
                }
                else{
                    $stateLinks[] = $value;
                }
            }
            $categoryPageLinks = array_merge($cityLinks,$stateLinks);
            $categoryPageLinks = array_slice($categoryPageLinks,0,12);
            // _p($categoryPageLinks);die;
            $this->nationalCourseCache->storeCourseInterLinking($courseObj->getId(),'category',$categoryPageLinks,array('cityId' => $currentLocation->getCityId()));
            if(!empty($categoryPageLinks)){
                $returnData['categoryPageLinks'] = $categoryPageLinks;
            }
            $this->CI->benchmark->mark('clp_interlinking_category_DB_end');
        }

        return $returnData;
    }

    private function _populateRankingLinks($links){
        if(empty($links)){
            return array();
        }
        $linksArrByLocationType = array();
        foreach ($links as $temp) {
            if($temp['state_id'] == -1){
                $temp['state_id'] = $temp['original_state_id'];
            }
            $linksArrByLocationType[$temp['type']][] = $temp;
        }

        $finalLinks = array();
        $keyToBeUsed = !empty($linksArrByLocationType['city']) ? 'city' : 'state';

        $finalLinks = $this->rankingURLManager->getRankingUrlsByMultipleParams($linksArrByLocationType[$keyToBeUsed]);

        return $finalLinks;
    }

    private function _getRankingPageLinksForCourse($courseObj,$currentLocation){

        $returnData = array();$beforeLinks = array();
        $mainLocation = $courseObj->getMainLocation();

        // show ranking links only on main location
        if($currentLocation->getCityId() != $mainLocation->getCityId()){
            return $returnData;
        }
        
        $dataFromCache = $this->nationalCourseCache->getCourseInterLinking($courseObj->getId(),'ranking');
        if(!empty($dataFromCache['ranking'])){
            $returnData = $dataFromCache['ranking']['data'];
        }
        else{

            $strictParamArray = $this->generatePermutationsForInterLinkingRules($courseObj,$currentLocation,true);
            $fallbackParamArray = $this->generatePermutationsForInterLinkingRules($courseObj,$currentLocation,false);
            $strictLinks = array();$fallbackLinks = array();

            for($i=1;$i<=4;$i++){
                if(!empty($strictParamArray['ruleArr'][$i])){
                    $strictLinks[$i] = array_slice($strictParamArray['paramArray'],$strictParamArray['ruleArr'][$i]['start'],($strictParamArray['ruleArr'][$i]['end']-$strictParamArray['ruleArr'][$i]['start'])+1);
                }
                else{
                    $strictLinks[$i] = array();
                }

                if(!empty($fallbackParamArray['ruleArr'][$i])){
                    $fallbackLinks[$i] = array_slice($fallbackParamArray['paramArray'],$fallbackParamArray['ruleArr'][$i]['start'],($fallbackParamArray['ruleArr'][$i]['end']-$fallbackParamArray['ruleArr'][$i]['start'])+1);
                }
                else{
                    $fallbackLinks[$i] = array();
                }
            }

            foreach ($strictLinks as $ruleId => $value) {
                $val = array_filter($value);
                // fetch links by this rule array links
                $beforeLinks[$ruleId] = $this->_populateRankingLinks($val);
                // if blank try the fall back links
                if(empty($beforeLinks[$ruleId])){
                    $val = array_filter($fallbackLinks[$ruleId]);
                    $beforeLinks[$ruleId] = $this->_populateRankingLinks($val);
                }
            }

            $finalLinks = array();
            foreach ($beforeLinks as $ruleId => $links) {
                $finalLinks = array_merge($finalLinks,$links);
            }

            $cityObj = $this->locationRepo->findCity($currentLocation->getCityId());
            $virtualCityId = $cityObj->getVirtualCityId();
            if(!empty($virtualCityId)){
                $tier = 1;
            }
            else{
                $tier = $cityObj->getTier();
            }

            $cityName  = $currentLocation->getCityName();
            $stateName = $currentLocation->getStateName();

            if(!in_array($tier,array(1,2))){
                $returnData = array_filter($finalLinks,function($ele){return ($ele['type'] == 'state');});
            }
            else{
                $temp1 = array_filter($finalLinks,function($ele){return ($ele['type'] == 'city');});
                $temp2 = array();
                // if state name is not same as city name
                if(strpos($cityName, $stateName) === false){
                    $temp2 = array_filter($finalLinks,function($ele){return ($ele['type'] == 'state');});
                }
                $returnData = array_merge($temp1,$temp2);
            }

            $returnData = array_slice($returnData,0,8);
            $this->nationalCourseCache->storeCourseInterLinking($courseObj->getId(),'ranking',array_values($returnData));
        }
        // _p($returnData);die;
        return $returnData;
    }

    private function generatePermutationsForInterLinkingRules($courseObj,$currentLocation,$includeOptionalParams = true){
        $cityObj = $this->locationRepo->findCity($currentLocation->getCityId());
        $virtualCityId = $cityObj->getVirtualCityId();
        if(!empty($virtualCityId)){
            $cityId = $virtualCityId;
            $stateId = -1;
            $tier = 1;
            $original_city_id = $currentLocation->getCityId();
            $original_state_id = $cityObj->getStateId();
        }
        else{
            $cityId = $cityObj->getId();
            $stateId = $cityObj->getStateId();
            $original_city_id = $cityObj->getId();
            $original_state_id = $cityObj->getStateId();
            $tier = $cityObj->getTier();
        }
        if(in_array($tier,array(1,2))){
            $baseArray = array(
                'city_id'=>array('city_id'=>$cityId,'state_id'=>$stateId,'min_result_count'=>2,'original_city_id' => $original_city_id, 'original_state_id' => $original_state_id),
                'state_id'=>array('state_id'=>$cityObj->getStateId(),'min_result_count'=>2, 'original_city_id' => $original_city_id, 'original_state_id' => $original_state_id)
            );
        }
        else{
            $baseArray = array(
                'state_id'=>array('state_id'=>$cityObj->getStateId(),'min_result_count'=>2, 'original_city_id' => $original_city_id, 'original_state_id' => $original_state_id)
            );
        }

        $tempParamArr = array();$ruleArr = array();$titleArr = array();
        $streamObjs = array();$substreamObjs = array();$specObjs = array();$baseCourseObjs = array();
        $linkMapping = array();

        $courseTypeInfo = reset($courseObj->getCourseTypeInformation());
        $hierarchies = $courseTypeInfo->getHierarchies();
        $baseCourse = $courseTypeInfo->getBaseCourse();
        
        if(!empty($baseCourse)){

            if(empty($baseCourseObjs[$baseCourse])){
                $baseCourseObjs[$baseCourse] = $this->baseCourseRepo->find($baseCourse);
            }
            $baseCourseName = $baseCourseObjs[$baseCourse]->getAlias() ? $baseCourseObjs[$baseCourse]->getAlias() : $baseCourseObjs[$baseCourse]->getName();
            $ruleArr[1]['start'] = count($tempParamArr);

            foreach ($baseArray as $key => $value) {
                $locationName = ($key == 'city_id') ? $currentLocation->getCityName() : $currentLocation->getStateName();
                $temp = $value;
                $temp['base_course_id'] = $baseCourse;
                if($includeOptionalParams){
                    $temp['education_type'] = $courseObj->getEducationType()->getId();
                    $temp['delivery_method'] = ($temp['education_type'] == FULL_TIME_MODE) ? NULL : $courseObj->getDeliveryMethod()->getId();
                }

                $temp['filterData'] = $this->collectFilterData($temp);
                if($baseCourseObjs[$baseCourse]->getIsPopular()){
                    $temp['type'] = ($key == 'city_id') ? 'city' : 'state';
                    $tempParamArr[] = $temp;
                    $linkMapping[$key][] = count($tempParamArr) - 1;
                    $titleArr[] = $baseCourseName.' colleges in '.$locationName;
                }
                else{
                    $mapArr = array();
                    foreach ($hierarchies as $hierarchy) {
                        $temp['stream_id'] = (empty($hierarchy['stream_id'])) ? 0 : $hierarchy['stream_id'];
                        $temp['substream_id'] = (empty($hierarchy['substream_id'])) ? 0 : $hierarchy['substream_id'];
                        if(!in_array($temp['stream_id'].'_'.$temp['substream_id'],$mapArr)){
                            $temp['type'] = ($key == 'city_id') ? 'city' : 'state';
                            $tempParamArr[] = $temp;
                            $linkMapping[$key][] = count($tempParamArr) - 1;
                            $titleArr[] = $baseCourseName.' colleges in '.$locationName;
                            $mapArr[] = $temp['stream_id'].'_'.$temp['substream_id'];
                        }
                    }
                }
            }

            $ruleArr[1]['end'] = count($tempParamArr)-1;
        }

        foreach($hierarchies as $hierarchy){
            if(!empty($hierarchy['substream_id']) && empty($substreamObjs[$hierarchy['substream_id']])){

                if(empty($ruleArr[2])){
                    $ruleArr[2]['start'] = count($tempParamArr);
                }
                $substreamObjs[$hierarchy['substream_id']] = $this->hierarchyRepo->findSubstream($hierarchy['substream_id']);
                $subStreamName = $substreamObjs[$hierarchy['substream_id']]->getAlias() ? $substreamObjs[$hierarchy['substream_id']]->getAlias() : $substreamObjs[$hierarchy['substream_id']]->getName();

                foreach ($baseArray as $key => $value) {
                    $locationName = ($key == 'city_id') ? $currentLocation->getCityName() : $currentLocation->getStateName();
                    $temp = $value;
                    $temp['substream_id'] = $hierarchy['substream_id'];
                    $temp['stream_id'] = $hierarchy['stream_id'];
                    if($includeOptionalParams){
                        $temp['education_type'] = $courseObj->getEducationType()->getId();
                        $temp['delivery_method'] = ($temp['education_type'] == FULL_TIME_MODE) ? NULL : $courseObj->getDeliveryMethod()->getId();
                    }

                    $temp['filterData'] = $this->collectFilterData($temp);
                    $temp['type'] = ($key == 'city_id') ? 'city' : 'state';
                    $tempParamArr[] = $temp;
                    $linkMapping[$key][] = count($tempParamArr) - 1;
                    $titleArr[] = $subStreamName.' colleges in '.$locationName;
                }

                $ruleArr[2]['end'] = count($tempParamArr)-1;
            }
        }
        
        foreach($hierarchies as $hierarchy){
            if(!empty($hierarchy['stream_id']) && empty($streamObjs[$hierarchy['stream_id']])){

                if(empty($ruleArr[3])){
                    $ruleArr[3]['start'] = count($tempParamArr);
                }
                $streamObjs[$hierarchy['stream_id']] = $this->hierarchyRepo->findStream($hierarchy['stream_id']);
                $streamName = $streamObjs[$hierarchy['stream_id']]->getAlias() ? $streamObjs[$hierarchy['stream_id']]->getAlias() : $streamObjs[$hierarchy['stream_id']]->getName();

                foreach ($baseArray as $key => $value) {
                    $locationName = ($key == 'city_id') ? $currentLocation->getCityName() : $currentLocation->getStateName();
                    $temp = $value;$temp['stream_id'] = $hierarchy['stream_id'];
                    if($includeOptionalParams){
                        $temp['education_type'] = $courseObj->getEducationType()->getId();
                        $temp['delivery_method'] = ($temp['education_type'] == FULL_TIME_MODE) ? NULL : $courseObj->getDeliveryMethod()->getId();
                    }

                    $temp['filterData'] = $this->collectFilterData($temp);
                    $temp['type'] = ($key == 'city_id') ? 'city' : 'state';
                    $tempParamArr[] = $temp;
                    $linkMapping[$key][] = count($tempParamArr) - 1;
                    $titleArr[] = $streamName.' colleges in '.$locationName;
                }

                $ruleArr[3]['end'] = count($tempParamArr)-1;
            }
        }
        
        $baseCourse = $courseTypeInfo->getBaseCourse();
        if(!empty($baseCourse)){
            foreach($hierarchies as $hierarchy){
                if(!empty($hierarchy['specialization_id']) && empty($specObjs[$hierarchy['specialization_id']])){

                    if(empty($ruleArr[4])){
                        $ruleArr[4]['start'] = count($tempParamArr);
                    }
                    $specObjs[$hierarchy['specialization_id']] = $this->hierarchyRepo->findSpecialization($hierarchy['specialization_id']);
                    $specName = $specObjs[$hierarchy['specialization_id']]->getAlias() ? $specObjs[$hierarchy['specialization_id']]->getAlias() : $specObjs[$hierarchy['specialization_id']]->getName();

                    foreach ($baseArray as $key => $value) {
                        $locationName = ($key == 'city_id') ? $currentLocation->getCityName() : $currentLocation->getStateName();
                        $temp = $value;
                        $temp['base_course_id'] = $baseCourse;$temp['specialization_id'] = $hierarchy['specialization_id'];
                        if($includeOptionalParams){
                            $temp['education_type'] = $courseObj->getEducationType()->getId();
                            $temp['delivery_method'] = ($temp['education_type'] == FULL_TIME_MODE) ? NULL : $courseObj->getDeliveryMethod()->getId();
                        }

                        $temp['filterData'] = $this->collectFilterData($temp);
                        $temp['type'] = ($key == 'city_id') ? 'city' : 'state';
                        $tempParamArr[] = $temp;
                        $linkMapping[$key][] = count($tempParamArr) - 1;
                        $titleArr[] = $baseCourseName.' in '.$specName.' colleges in '.$locationName;
                    }

                    $ruleArr[4]['end'] = count($tempParamArr)-1;                    
                }
            }
        }
        else{
            foreach($hierarchies as $hierarchy){
                if(!empty($hierarchy['specialization_id']) && empty($specObjs[$hierarchy['specialization_id']])){

                    if(empty($ruleArr[4])){
                        $ruleArr[4]['start'] = count($tempParamArr);
                    }
                    $specObjs[$hierarchy['specialization_id']] = $this->hierarchyRepo->findSpecialization($hierarchy['specialization_id']);
                    $specName = $specObjs[$hierarchy['specialization_id']]->getAlias() ? $specObjs[$hierarchy['specialization_id']]->getAlias() : $specObjs[$hierarchy['specialization_id']]->getName();

                    foreach ($baseArray as $key => $value) {
                        $locationName = ($key == 'city_id') ? $currentLocation->getCityName() : $currentLocation->getStateName();
                        $temp = $value;
                        $temp['stream_id'] = $hierarchy['stream_id'];
                        $temp['substream_id'] = $hierarchy['substream_id'];
                        $temp['specialization_id'] = $hierarchy['specialization_id'];
                        if($includeOptionalParams){
                            $temp['education_type'] = $courseObj->getEducationType()->getId();
                            $temp['delivery_method'] = ($temp['education_type'] == FULL_TIME_MODE) ? NULL : $courseObj->getDeliveryMethod()->getId();
                        }
                        
                        $temp['filterData'] = $this->collectFilterData($temp);
                        $temp['type'] = ($key == 'city_id') ? 'city' : 'state';
                        $tempParamArr[] = $temp;
                        $linkMapping[$key][] = count($tempParamArr) - 1;
                        $titleArr[] = $specName.' colleges in '.$locationName;
                    }

                    $ruleArr[4]['end'] = count($tempParamArr)-1;
                }
            }
        }
        return array('paramArray' => $tempParamArr, 'titleArr' => $titleArr, 'linkMapping' => $linkMapping, 'ruleArr' => $ruleArr);
    }

    private function _getCategoryPageLinksForCourse($courseObj,$currentLocation,$includeOptionalParams = true){

        $categoryPageLinks = array();

        $cityObj = $this->locationRepo->findCity($currentLocation->getCityId());
        $virtualCityId = $cityObj->getVirtualCityId();

        $data = $this->generatePermutationsForInterLinkingRules($courseObj,$currentLocation,$includeOptionalParams);
        $tempParamArr = $data['paramArray'];
        $titleArr = $data['titleArr'];
        $linkMapping = $data['linkMapping'];
        $ruleArr = $data['ruleArr'];

        $this->CI->load->library("nationalCategoryList/NationalCategoryPageLib");
        $nationalCategoryPageLib = new NationalCategoryPageLib();

        if(!empty($tempParamArr)){
            $links = $nationalCategoryPageLib->getUrlByMultipleParams($tempParamArr);
            $links = array_values($links);
            foreach ($links as $key => $value) {
                if(!empty($value)){
                    $links[$key]['title'] = $titleArr[$key];
                    $links[$key]['type'] = in_array($key,$linkMapping['city_id']) ? 'city' : 'state';
                    if(!empty($virtualCityId)){
                        $links[$key]['original_city_id'] = $currentLocation->getCityId();
                        $links[$key]['original_state_id'] = $cityObj->getStateId();
                    }
                }
            }

            for($i=1;$i<=4;$i++){
                if(!empty($ruleArr[$i])){
                    $categoryPageLinks[$i] = array_slice($links,$ruleArr[$i]['start'],($ruleArr[$i]['end']-$ruleArr[$i]['start'])+1);
                }
                else{
                    $categoryPageLinks[$i] = array();
                }
            }
        }
        // _p($categoryPageLinks);die;
        return $categoryPageLinks;
    }

    private function collectFilterData($data){
        $returnData = array();
        if(!empty($data['city_id'])){
            $returnData['city'] = $data['city_id'];
        }
        else if(!empty($data['state_id']) && $data['state_id'] != -1){
            $returnData['state'] = $data['state_id'];
        }
        if(!empty($data['credential'])){
            $returnData['credential'] = $data['credential'];
        }
        if(!empty($data['education_type'])){
            $returnData['education_type'] = $data['education_type'];
        }
        if(!empty($data['delivery_method'])){
            $returnData['delivery_method'] = $data['delivery_method'];
        }
        return $returnData;
    }

    public function checkForCommonRedirections($courseObj, $courseId, $ampViewFlag=false){
        $currentUrl = getCurrentPageURLWithoutQueryParams();

         /*If course id does'nt exist, check whether the status of course is deleted,
          if yes then 301 redirect to migrated course page Or show 404 */
        if(empty($courseObj) || $courseObj->getId() == ''){
               $newUrl = $this->coursedetailmodel->checkForDeletedCourse($courseId);
               if(!empty($newUrl)){
                    header("Location: $newUrl",TRUE,301);
                    exit;
               }else{
                    show_404();
                    exit(0);
               }
        }

        if(!empty($courseObj) && ($courseObj->getId() != '')){
            $seo_url     = ($ampViewFlag)?$courseObj->getAmpURL():$courseObj->getURL();         
            $disable_url = $courseObj->getDisableUrl();
            
            $queryParams = array();

            $queryParams = $_GET;

            if(!empty($queryParams) && count($queryParams) > 0)
            {
                $seo_url .= '?'.http_build_query($queryParams);
            }

            //check if url is different from original url, 301 redirect to main url
            $courseUrl     = ($ampViewFlag)?$courseObj->getAmpURL():$courseObj->getURL();
            if($currentUrl != $courseUrl){     
                if( (strpos($seo_url, "http") === false) || (strpos($seo_url, "http") != 0) || (strpos($seo_url, SHIKSHA_HOME) === 0) || (strpos($seo_url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($seo_url,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($seo_url,ENTERPRISE_HOME) === 0) ){
                    header("Location: $seo_url",TRUE,301);
                }
                else{
                    header("Location: ".SHIKSHA_HOME,TRUE,301);
                }
                exit;
            }

            //Redirect to disabled url
            if($disable_url != ''){
                if( (strpos($disable_url, "http") === false) || (strpos($disable_url, "http") != 0) || (strpos($disable_url, SHIKSHA_HOME) === 0) || (strpos($disable_url,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($disable_url,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($disable_url,ENTERPRISE_HOME) === 0) ){
                    header("Location: $disable_url",TRUE,301);
                }
                else{
                    header("Location: ".SHIKSHA_HOME,TRUE,301);
                }
                exit;
            }
        }

    }

    public function seoFormOrganicTraffic(){
        $mmp_details = array();

        if($this->userStatus == ""){
            $this->userStatus = $this->CI->checkUserValidation();
        }
        
        $SER_HTTP_REFERER = $this->CI->input->server('HTTP_REFERER',true);
        $REQ_showpopup = $_REQUEST['showpopup'];
        $REQ_resetpwd = $_REQUEST['resetpwd'];
        if(((strpos($SER_HTTP_REFERER, 'google') !== false) || ($REQ_showpopup != '')) && ((empty($mmp_details))) && ($REQ_resetpwd != 1) && ($this->userStatus == false)) {


            $this->customizemmp_model = $this->CI->load->model('customizedmmp/customizemmp_model');
            $mmp_details = $this->customizemmp_model->getMMPDetailsByType('newcategory');

            $this->CI->load->library('customizedmmp/customizemmp_lib');
            $customizedMMPLib = new customizemmp_lib();
            $newMMPDetails = $customizedMMPLib->getCustomizedDataForMMPForm($mmp_details);

           return $newMMPDetails;
        }
        return null;
    }

    private function _getAffiliationName($courseObj) {
        $affiliation = $courseObj->getAffiliations();
        $affiliatedUniversityName = '';
        $affiliatedUniversityUrl  = '';
        if(!empty($affiliation['university_id'])) {
            switch ($affiliation['scope']) {
                case 'abroad':
                    $this->CI->load->builder('ListingBuilder','listing');
                    $ListingBuilder = new ListingBuilder;
                    $AbroadRepo = $ListingBuilder->getUniversityRepository();
                    $university = $AbroadRepo->find($affiliation['university_id']);
                    break;
                case 'domestic':
                    $university = $this->instituteRepo->find($affiliation['university_id']);
                    break;
            }
            $affiliatedUniversityName = htmlentities($university->getName());
            $affiliatedUniversityUrl  = $university->getURL();
            $affiliatedUniversityScope  = $affiliation['scope'];
        }
        else if(!empty($affiliation['name'])) {
            $affiliatedUniversityScope  = $affiliation['scope'];
            $affiliatedUniversityName = htmlentities($affiliation['name']);
        }
        // _p($affiliation); 
        // _p($affiliatedUniversityName); die;
        return array('name' => $affiliatedUniversityName, 'url' => $affiliatedUniversityUrl, 'scope' => $affiliatedUniversityScope);
    }

    public function getOFData($courseId){
        $this->CI->load->library('Online/OnlineFormUtilityLib');
        $obj = new OnlineFormUtilityLib();
        $OFData = $obj->getOAFBasicInfo($courseId);
        return $OFData;
    }


    public function getFeesData($courseObj,$selectedCategory,$currentLocation){
        if(!$selectedCategory){
            $selectedCategory = 'general';
        }


        //fees from object
        $data                         = array();
        $feesData                     = array();
        $feesAllCatData               = array();
        $feesLocWiseData              = $courseObj->getFeesCategoryLocationWise($currentLocation->getLocationId());
        $isLocationLevelFeesAvailable = false;
        if($feesLocWiseData){
            $categoryLocationFees         = $feesLocWiseData[$selectedCategory];
            $data['feesSelectedCategory'] = $selectedCategory;
            if(!$categoryLocationFees){
                $categoryLocationFees         = $feesLocWiseData['general'];
                $data['feesSelectedCategory'] = 'general';
            }
            $feesData                     = $categoryLocationFees;
            $feesAllCatData               = $feesLocWiseData;
            $isLocationLevelFeesAvailable = true;
            $data['feesFilterOption']     = array_keys($feesLocWiseData);
            $temp = array();
            foreach ($feesAllCatData as $key => $value) {
                $temp[$key]  = $value->getFeesUnitName()." ".getRupeesDisplableAmount($value->getFeesValue());                
            }
            $data['categoryFeesMapping']  = json_encode($temp);
        }else{
            $feesWithoutLocData       = $courseObj->getFeesCategoryWise();       
            $data['feesSelectedCategory'] = $selectedCategory;
            $categoryFees             = $feesWithoutLocData[$selectedCategory];
            if(!$categoryFees){
                $categoryFees                 = $feesWithoutLocData['general'];   
                $data['feesSelectedCategory'] = 'general';
            }
            $feesData                 = $categoryFees;
            $feesAllCatData           = $feesWithoutLocData;
            $data['feesFilterOption'] = array_keys($feesWithoutLocData);
        }


        if(empty($feesData)){
            return;
        }
        
        $feesInformation                      = getCourseFeesIncludesAndDisclaimer($feesData);
        $totalIncludes                        = $feesInformation['totalIncludes'];
        $feesDisclaimer                       = $feesInformation['disclaimer'];
        
        $data['totalFees']                    = $feesData->getFeesUnitName() . " ". getRupeesDisplableAmount($feesData->getFeesValue());                               
        if($feesAllCatData['general']){
            $data['totalFeesBasicSection']        = $feesAllCatData['general']->getFeesUnitName() . " ". getRupeesDisplableAmount($feesAllCatData['general']->getFeesValue());            
        }
        $data['isLocationLevelFeesAvailable'] = $isLocationLevelFeesAvailable;


        if(!empty($totalIncludes))
            $data['totalFeesIncludes'] = implode(", ", $totalIncludes);;
        
        if(!empty($feesDisclaimer))
            $data['feesDisclaimer']    = $feesDisclaimer;

        if(!$isLocationLevelFeesAvailable){
            $otpAndHostel             = json_decode($this->getFeesBasedOnSelection($courseObj->getId(), $data['feesSelectedCategory']), true);
            
            if(!empty($otpAndHostel['data'])){
                $data['otpAndHostelFees'] = $otpAndHostel['data'];            
            }
            if(!empty($otpAndHostel['feesDescription'])){
                $data['feesDescription']  = $otpAndHostel['feesDescription'];
            }
        }
        else{
            //fees description
            $feesDescription           = $this->getFeesDescription($courseObj->getId());
            if(!empty($feesDescription))
                $data['feesDescription']  = $feesDescription;
        }
        

        //tooltip Message for basic info fees
        if(count($data['feesFilterOption']) > 1 && $data['totalFeesBasicSection']){
            $tooltip1Data = 'The shown fees is for the general category and will vary for other reservation categories. Please refer to the fees section below for category specific fees information.';
            $tooltip2Data = 'Total fees has been calculated bases year 1 fees provided by the college. The actual fees may be revised next year.';
            if($feesDisclaimer)
                $data['feesTooltipBasicInfo']  = $tooltip1Data."<br/><br/>".$tooltip2Data;
            else
                $data['feesTooltipBasicInfo']  = $tooltip1Data;
        }

        if(!$data['totalFees']){
            return array();
        }
        
        return $data;        
    }
    function getFeesDataForAMP($courseObj,$selectedCategory,$currentLocation)
    {
        if(!empty($selectedCategory))
        {
            $selectedCategory = 'general';
        }

        //fees from object
        $data                         = array();
        $feesData                     = array();
        $feesAllCatData               = array();
        $feesLocWiseData              = $courseObj->getFeesCategoryLocationWise($currentLocation->getLocationId());
        $isLocationLevelFeesAvailable = false;
        if($feesLocWiseData){
            $categoryLocationFees         = $feesLocWiseData;
            $data['selectedCategory'] = $selectedCategory;
            $feesData                     = $categoryLocationFees;
            $feesAllCatData               = $feesLocWiseData;
            $isLocationLevelFeesAvailable = true;
            $data['feesFilterOption']     = array_keys($feesLocWiseData);
            $temp = array();
            foreach ($feesAllCatData as $key => $value) {
                $temp[$key]  = $value->getFeesUnitName()." ".getRupeesDisplableAmount($value->getFeesValue());                
            }
            $data['categoryFeesMapping']  = json_encode($temp);
        }else{
            $feesWithoutLocData       = $courseObj->getFeesCategoryWise();       
            //_p($feesWithoutLocData);die;
            //$data['feesSelectedCategory'] = $selectedCategory;
            //$categoryFees             = $feesWithoutLocData[$selectedCategory];
//            $feesData                 = $feesWithoutLocData;
            $feesAllCatData           = $feesWithoutLocData;
            $data['feesFilterOption'] = array_keys($feesWithoutLocData);
        }
        if(empty($feesAllCatData) && count($feesAllCatData) == 0){
            return;
        }
        $feesInformation = array();
        foreach ($feesAllCatData as $feeKey => $feeValue) {    
            $data['feesInfo'][$feeKey]['totalFees'][$feeKey]                    = $feesAllCatData[$feeKey]->getFeesUnitName() . " ". getRupeesDisplableAmount($feesAllCatData[$feeKey]->getFeesValue());
            $data['feesInfo'][$feeKey]['totalFeesBasicSection']       = $feesAllCatData[$feeKey]->getFeesUnitName() . " ". getRupeesDisplableAmount($feesAllCatData[$feeKey]->getFeesValue());            
        }

        $feesInformation   = getCourseFeesIncludesAndDisclaimer($feesAllCatData[$feeKey]);
        $totalIncludes                        = $feesInformation['totalIncludes'];
        $feesDisclaimer                       = $feesInformation['disclaimer'];

        $data['isLocationLevelFeesAvailable'] = $isLocationLevelFeesAvailable;

        if(!empty($totalIncludes))
            $data['totalFeesIncludes'] = implode(", ", $totalIncludes);;
        
        if(!empty($feesDisclaimer))
            $data['feesDisclaimer']    = $feesDisclaimer;

        if(!$isLocationLevelFeesAvailable){
            $otpAndHostel = $this->getFeesAllCategories($courseObj->getId(),$data['feesFilterOption']);
            if(!empty($otpAndHostel['fees'])){
                $data['otpAndHostelFees'] = $otpAndHostel['fees'];
            }
            if(!empty($otpAndHostel['feesDescription'])){
                $data['feesDescription'] = $otpAndHostel['feesDescription'];
            }
        }
        else{
            //fees description
            $feesDescription           = $this->getFeesDescription($courseObj->getId());
            if(!empty($feesDescription)){
                $data['feesDescription']  = $feesDescription;
            }
        }

        

        //tooltip Message for basic info fees
        if(count($data['feesFilterOption']) > 1 && $data['feesInfo']['general']['totalFeesBasicSection']){
            $tooltip1Data = 'The shown fees is for the general category and will vary for other reservation categories. Please refer to the fees section below for category specific fees information.';
            $tooltip2Data = 'Total fees has been calculated bases year 1 fees provided by the college. The actual fees may be revised next year.';
            if($feesDisclaimer)
                $data['feesTooltipBasicInfo']  = $tooltip1Data."<br/><br/>".$tooltip2Data;
            else
                $data['feesTooltipBasicInfo']  = $tooltip1Data;
        }

        $data['selectedCategory'] = $selectedCategory;
/*
        if(!$data['totalFees']){
            return array();
        }*/
        return $data;           
    }

    /**
     * Get information such as One Time Payment, Hostel Fees, Total Fees for a given course ID and a selected fees
     * category
     *
     * @param int    $courseId
     * @param string $selectedCategory
     *
     * @return string
     */
    public function getFeesBasedOnSelection($courseId, $selectedCategory)
    {
        $feesInformation = $this->coursedetailmodel->getFeesBasedOnSelection($courseId, $selectedCategory);
        // _p($feesInformation); die;
        $fees = array();
        if (!$feesInformation) {            
            if($selectedCategory != 'general')
                $feesInformation = $this->coursedetailmodel->getFeesBasedOnSelection($courseId, 'general');        

            if (!$feesInformation) {
                return json_encode(array(
                    'status'  => 'error',
                    'message' => 'No Data Found'
                ));
            }
        }

        
        foreach ($feesInformation as $oneFees) {
            $feesValue = $oneFees['currency_code'] . " " . getRupeesDisplableAmount($oneFees['fees_value']);
            $feesType  = '';
            if ($oneFees['period'] == 'otp') {
                $feesType = 'otp';
                $fees[ $feesType ] = $feesValue;
            }else if($oneFees['period'] == 'year' && $oneFees['fees_type'] == 'hostel'){
                $feesType = 'hostel';
                $fees[ $feesType ] = $feesValue;
            }else if($oneFees['period'] == 'overall'){
                $feesType = 'total';
                $fees[ $feesType ] = $feesValue;
            }
            else if(empty($oneFees['category']) && empty($oneFees['period']) && !empty($oneFees['other_info'])){
                $feesDescription = $oneFees['other_info'];
            }
            else{
                $temp   = array();
                $fees["durationWise"][intval($oneFees['order']) -1] = array(
                                'value'=>$feesValue,
                                'duration'=>ucfirst($oneFees['period'])
                               );                
            }            
        }
        // _p($fees); die;
        return json_encode(array(
            'status' => 'success',
            'data'   => $fees,
            'feesDescription' => $feesDescription
        ));
    }

    public function getImportantDates($courseIdOrObj,&$data,$ampViewFlag=false){
        if($courseIdOrObj instanceof Course){
            $courseObj = $courseIdOrObj;
            $courseId = $courseObj->getId();
        }
        else{
            $courseId = $courseIdOrObj;
            $courseObj = $this->courseRepo->find($courseId,array('eligibility'));
        }
        $selectedExam = $this->CI->input->post('selectedExam');
        $source = $this->CI->input->post('source') ? $this->CI->input->post('source') : 'page';

        $importantDates = $this->getImportantDatesData($courseObj);
        $showViewMore = false;
        if(count($importantDates) > 3){
            $showViewMore = true;
        }
        $isCourseDates = false;
        $examsHavingDates = array();
        foreach ($importantDates as $date) {
            if($date['type'] == 'exam'){
                $examsHavingDates[$date['exam_id']] = array('exam_id'=>$date['exam_id'],'exam_name'=>$date['exam_name']);
            }else{
                if(!$isCourseDates)
                    $isCourseDates = true;
            }
        }
        $data['importantDatesData']['source'] = $source;
        $data['importantDatesData']['examsHavingDates'] = $examsHavingDates;
        $data['importantDatesData']['isCourseDates'] = $isCourseDates;

        if($ampViewFlag && !empty($data['importantDatesData']['examsHavingDates']) && count($data['importantDatesData']['examsHavingDates']) > 0)
        {
            foreach ($data['importantDatesData']['examsHavingDates'] as $examKey => $examValue) {
                $this->prepareImportantDatesForAMPLayer($importantDates,$examKey,$data);
            }
        }
        if(!empty($selectedExam)){
            $importantDates = array_filter($importantDates,function($a) use($selectedExam){
                if($a['exam_id'] && $a['exam_id'] == $selectedExam){
                    return true;
                }
                return false;
            });
            $importantDates = array_values($importantDates);
        }

        if(!empty($importantDates)){
            $data['importantDatesData']['importantDates'] = $this->formatImportantDatesBySource($importantDates,$source);

            //below line is used for AMP Page
            if($ampViewFlag)
            {

                $data['importantDatesData']['importantDatesLayer']['All'] = $this->formatImportantDatesBySource($importantDates,'layer');
            }

            if($showViewMore){
                foreach ($data['importantDatesData']['importantDates'] as $date) {
                    if(!empty($date['showUpcoming'])){
                        $data['showImportantViewMore'] = true;
                        break;
                    }
                }
            }
        }
        else{
            $data['importantDatesData']['importantDates'] = array();
        }
        return $data;
    }
    //below function is used for preparing data for important date for available exams on course
    function prepareImportantDatesForAMPLayer($importantDates,$selectedExam,&$data)
    {
        if(empty($selectedExam)){
            return ;
        }
        $importantDates = array_filter($importantDates,function($a) use($selectedExam){
                if($a['exam_id'] && $a['exam_id'] == $selectedExam){
                    return true;
                }
                return false;
            });
            $importantDates = array_values($importantDates);
        if(!empty($importantDates)){
           //below line is used for AMP Page

            $data['importantDatesData']['importantDatesLayer'][$selectedExam] = $this->formatImportantDatesBySource($importantDates,'layer');
        }
    }

    function getPartnersData($courseObj){
        $formatData = array();
        $data       = $courseObj->getPartner();
        $checkDurationExistForOne = false;
        // _p($data);die;
        if($data){
            foreach ($data as $key => $value) {
                if($value->getScope()  == 'domestic'){
                    $instituteObj     = $this->instituteRepo->find($value->getId());
                }elseif($value->getScope()  == 'studyAbroad'){
                    $this->CI->load->builder("listing/ListingBuilder");
                    $listingBuilder = new ListingBuilder();
                    $this->saUniRepo = $listingBuilder->getUniversityRepository();
                    $instituteObj = $this->saUniRepo->find($value->getId());
                }
                $temp             = array();                
                $temp['name']     = htmlentities($instituteObj->getName());
                $temp['url']      = $instituteObj->getURL();
                if($value->getDurationValue()){
                    $temp['duration'] = $value->getDurationValue()." ".$value->getDurationUnit();
                    $checkDurationExistForOne = true;
                }
                $formatData[]     = $temp;
            }        
            return array('data'=>$formatData,'checkDurationExistForOne'=>$checkDurationExistForOne);
        }else{
            return;
        }
    }

    function getRecognitionData($courseObj, $instituteObj) {
        $arr = array();
        $recognitionToolTip = $this->CI->config->item('recognitionToolTip');
        foreach ($courseObj->getRecognition() as $key => $recognition) {
            $arr['approvals'][] = array(
                                        'name'   =>$recognition->getName(),
                                        'tooltip'=>$recognitionToolTip[$recognition->getName()]
                                        );
        }
        if($courseObj->isNBAAccredited()) {
            $arr['institute'][] = array('name'   =>$courseObj->getNBAAccreditation()->getName(),
                                        'tooltip'=>$recognitionToolTip[$courseObj->getNBAAccreditation()->getName()]
                                        );
        }
        

        $accreditation = $instituteObj->getAccreditation();
        if(!empty($accreditation)) {
            $arr['institute'][] = array('name' => 'NAAC '."'".$instituteObj->getAccreditation()."' Grade",
                                        'tooltip'=>'National Assessment and Accreditation Council  (NAAC) - An autonomous body established by the UGC to evaluate quality of higher education institutes in India. It grades an institution from A++ (best rating) to C. Rating D indicates that the institution is not accredited.'
                                        );
        }

       // _p($arr);
        return $arr;
    }

    /**
    * Purpose : Function to check and set the values is data array necessary for making the response for course page eg. course_viewed
    * 
    **/
    private function _checkAndSetDataForAutoResponseForCoursePage($validateuser, &$data)
    {
        
        $this->CI->load->model('qnAmodel');
        $this->qnamodel = new QnAModel();
        $validResponseUser = 0;
        if(($validateuser != "false") && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums"))) && (!($this->qnamodel->checkIfAnAExpert($dbHandle,$validateuser[0]['userid']))) && ($validateuser[0]['mobile'] != ""))
        {
            $validResponseUser         = 1;
            $data['validResponseUser'] = $validResponseUser;
        }
        $data['viewedResponseAction'] = 'Viewed_Listing';

        $data['courseViewedTrackingPageKeyId'] = DESKTOP_NL_VIEWED_LISTING;
    }

    public function getCollegeCutOffInfo($courseId,$instituteObj){
        $instituteId = $instituteObj->getId();
        $this->CI->load->config('nationalInstitute/CollegeCutoffConfig',True);
        $parentListingsIdsData = $this->CI->config->item('parentListingIds','CollegeCutoffConfig');
        $idToCollegeMapping = $this->CI->config->item('idToCollegeMapping','CollegeCutoffConfig');

        $instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');

        $instituteHierarchy = $instituteDetailLib->getInstituteListingHierarchyDataNew(array($instituteId));
        $instituteHierarchy = $instituteHierarchy[$instituteId];

        foreach ($instituteHierarchy['university'] as $row) {
            if(!empty($parentListingsIdsData[$row['listing_id']])){
                $parentListingId = $row['listing_id'];
                break;
            }
        }
        if(empty($parentListingId)){
            foreach ($instituteHierarchy['institute'] as $row) {
                if(!empty($parentListingsIdsData[$row['listing_id']])){
                    $parentListingId = $row['listing_id'];
                    break;
                }
            }
        }
        
        $returnData = array();
        if(!empty($parentListingId)){
            $cpmodel = $this->CI->load->model('CP/cpmodel');
            $abbreviation = $idToCollegeMapping[$parentListingId];
            $courseIds = $cpmodel->getCoursesHavingPredictors($abbreviation,array($courseId));
            if(!empty($courseIds)){
                if($parentListingId != $instituteId){
                    $parentInstObj = $this->instituteRepo->find($parentListingId);
                    $name = $parentInstObj->getAbbreviation() ? $parentInstObj->getAbbreviation() : $parentInstObj->getName();
                    $returnData[] = array('text' => 'Get All '.$name.' Cut-offs','url' => $parentInstObj->getURL().'/cutoff');
                }
                $name = $instituteObj->getAbbreviation() ? $instituteObj->getAbbreviation() : $instituteObj->getName();
                $returnData[] = array('text' => 'Get All '.$name.' Cut-offs','url' => $instituteObj->getURL().'/cutoff');
            }
        }
        
        return $returnData;
    }

    public function getPredictorInfo($courseId){
        
        $iimPredictorConfig = $this->CI->config->item('iimPredictor');
        if(in_array($courseId, $iimPredictorConfig)){
            return array('0'=>array('url'=>SHIKSHA_HOME.'/mba/resources/iim-call-predictor','name'=>'iim predictor'));
        }
        
        //courseId 2021
        $collegepredictorlibrary = $this->CI->load->library('CP/CollegePredictorLibrary');
        $result                  = $collegepredictorlibrary->getCollegePredictorInfoBasedOnCourseId($courseId);
        return $result;
    }

    function prepareCourseScholarship($InstituteObj) {
        return $InstituteObj->getScholarships();
    }

    private function _checkIfCourseShortlisted($courseObj,$validateuser){
        if($validateuser === "false"){
            return 0;
        }
        $userId = $validateuser[0]['userid'];
        $courseId = $courseObj->getId();
        $courseIds = Modules::run('myShortlist/MyShortlist/getShortlistedCourse', $userId, 'national');
        if(in_array($courseId, $courseIds)){
            return 1;
        }
        return 0;
    }

    function getFeesDataForBrochure($courseObj) {
        //fees from object
        $data                         = array();
        $feesData                     = array();
        
        $feesWithoutLocData       = $courseObj->getFeesCategoryWise();
        // _p($feesWithoutLocData); 
        // $data['feesSelectedCategory'] = $selectedCategory;
        // $categoryFees             = $feesWithoutLocData[$selectedCategory];
        // if(!$categoryFees){
        //     $categoryFees                 = $feesWithoutLocData['general'];   
        //     $data['feesSelectedCategory'] = 'general';
        // }
        // $feesData                 = $categoryFees;
        // $feesAllCatData           = $feesWithoutLocData;
        // $data['feesFilterOption'] = array_keys($feesWithoutLocData);
        
        foreach ($feesWithoutLocData as $category => $feesData) {
            $feesInformation                      = getCourseFeesIncludesAndDisclaimer($feesData);
            $totalIncludes                        = $feesInformation['totalIncludes'];
            $feesDisclaimer                       = $feesInformation['disclaimer'];
            $data[$category]['totalFees']                    = $feesData->getFeesUnitName() . " ". getRupeesDisplableAmount($feesData->getFeesValue());                               
            if(!empty($totalIncludes))
                $data[$category]['totalFeesIncludes'] = implode(", ", $totalIncludes);
            
            if(!empty($feesDisclaimer))
                $data[$category]['feesDisclaimer']    = $feesDisclaimer;
        }

        $categories = array_keys($data);
        $otpAndHostelFees        = $this->getFeesAllCategories($courseObj->getId(), $categories);

        foreach ($categories as $category) {
            $data[$category]['otpAndHostelFees'] = $otpAndHostelFees['fees'][$category];
        }
        //fees description
        // $feesDescription           = $this->getFeesDescription($courseObj->getId());
        $feesDescription = $otpAndHostelFees['feesDescription'];

        return array('feesData' => $data, 'description' => htmlentities($feesDescription));
    }

    /**
     * Get information such as One Time Payment, Hostel Fees, Total Fees for a given course ID and a selected fees
     * category
     *
     * @param int    $courseId
     * @param string $selectedCategory
     *
     * @return string
     */
    public function getFeesAllCategories($courseId, $selectedCategory)
    {
        $feesInformation = $this->coursedetailmodel->getFeesBasedOnSelection($courseId, $selectedCategory);
        // _p($feesInformation); 
        $fees = array();
        if (!$feesInformation) {            
            return array();
        }

        
        foreach ($feesInformation as $oneFees) {
            $feesValue = $oneFees['currency_code'] . " " . getRupeesDisplableAmount($oneFees['fees_value']);
            $feesType  = '';
            if ($oneFees['period'] == 'otp') {
                $feesType = 'otp';
                $fees[$oneFees['category']][ $feesType ] = $feesValue;
            }else if($oneFees['period'] == 'year' && $oneFees['fees_type'] == 'hostel'){
                $feesType = 'hostel';
                $fees[$oneFees['category']][ $feesType ] = $feesValue;
            }else if($oneFees['period'] == 'overall'){
                $feesType = 'total';
                $fees[$oneFees['category']][$feesType] = $feesValue;
            }
            else if(empty($oneFees['category']) && empty($oneFees['period']) && !empty($oneFees['other_info'])){
                $feesDescription = $oneFees['other_info'];
            }
            else{
                $temp   = array();
                $fees[$oneFees['category']]["durationWise"][intval($oneFees['order']) -1] = array(
                                'value'=>$feesValue,
                                'duration'=>ucfirst($oneFees['period'])
                               );                
            }            
        }
        // _p($fees); die;
        return array('fees' => $fees, 'feesDescription' => $feesDescription);
    }

    public function getEligibilityAllCategories($course_id){
        $eligibilityData = array();
        $eligibleCategories = array();
        $dataFromDb = $this->coursedetailmodel->getEligibilityData($course_id);
        if(empty($dataFromDb)){
            return array();
        }

        array_walk_recursive($dataFromDb, function($value,$key) use (&$eligibleCategories){
            if($key === 'category' && !empty($value)){                          
                $eligibleCategories[] = $value;
            }
        });
        $eligibleCategories = array_values(array_unique($eligibleCategories));
        sort($eligibleCategories);

        foreach($dataFromDb as $data){
            foreach ($data as $value) {

                if(isset($value['exam_id']) && !empty($value['exam_id'])){
                    $examId [] = $value['exam_id'];
                }
            }
        }
        $examIds = array_values(array_unique($examId));
        if(!empty($examIds)){
            $examDataById = $this->examLib->getExamDataByExamIds($examIds);
        }
        $allCategoryData = array();
        $data = array();
        $eligibilitySectionWithoutExam = array('X','XII','graduation','postgraduation');
        foreach ($eligibleCategories as $categoryName) {
            $eligibilityData = array();
            // $data = array();
            foreach ($dataFromDb as $key => $value) {
                if(!empty($dataFromDb[$key])){
                    // echo "romil==".$key.'<br/>';
                    $data[$key] = $this->formatEligibilityData($dataFromDb[$key],$key,$categoryName);
                }
            }
            // _p($data); die;
            $desiredIndexOrder = array('X'=>1,'XII'=>2,'graduation'=>3,'postgraduation'=>4);
        
            // don't override these variables
            $examData = array();$stateIds = array();$statesData = array();$customExams = array();

            foreach ($eligibilitySectionWithoutExam as $val) {
                $eligibilityData['table'][$val]['eligibility']    = 'N/A';
                $eligibilityData['table'][$val]['additionalInfo'] = 'N/A';
                $eligibilityData['table'][$val]['cutoff']         = 'N/A';
                $eligibilityData['table'][$val]['type']           = 'section';
            }
            
            if(!empty($data['cutoff'])){
                foreach ($data['cutoff'] as $row) {
                    $examIds[] = $row['exam_id'];
                }
            }
            // _p($eligibilityData); 
            // _p($data['examEligibility']); die;
            $examData = $this->addExamDetailsForEligibility($data['examEligibility'], $eligibilityData, $desiredIndexOrder,$examDataById);
            // _p($eligibilityData); die;

            $this->addStandardInfoForEligibility($data['categoryWise'], $eligibilityData);
            
            if(!empty($data['basic']['subjects'])){
                if(!$eligibilityData['showEligibilityAdditionalInfo']){
                    $eligibilityData['showEligibilityAdditionalInfo'] = true;
                }  
                $subjectsData = json_decode($data['basic']['subjects']);
                if(count($subjectsData) > 1){
                    $subjectPrefixHeading = 'Mandatory Subjects :';
                }else{
                    $subjectPrefixHeading = 'Mandatory Subject :';
                }
                $eligibilityData['table']['XII']['additionalInfo'] = $eligibilityData['table']['XII']['additionalInfo'] == 'N/A' ? $subjectPrefixHeading." ".implode(", ", $subjectsData) : $subjectPrefixHeading." ".implode(", ", $subjectsData)."\n".$eligibilityData['table']['XII']['additionalInfo'];
                $eligibilityData['table']['XII']['qualification']  = getQualificationTextForEligibility('XII');
            }

            $temp = array('graduation','postgraduation');
            foreach ($temp as $val) {
                if(!empty($data['graduationSubject'][$val])){
                    $baseSpecStringArray = array();                
                    foreach ($data['graduationSubject'][$val] as $baseCourseId => $specialization) {
                        $baseSpecString = $this->baseCourseRepo->find($baseCourseId)->getName();
                        $specializationsWithName = array();
                        foreach ($specialization as $key => $specializationId) {
                            if($specializationId > 0)
                                $specializationsWithName[] = $this->hierarchyRepo->findSpecialization($specializationId)->getName();
                        }
                        if(!empty($specializationsWithName)){
                            $baseSpecStringArray[] = $baseSpecString." (".implode(", ", $specializationsWithName).") ";
                        }
                        else{
                            $baseSpecStringArray[] = $baseSpecString;
                        }
                    }
                     if(!$eligibilityData['showEligibilityAdditionalInfo']){
                            $eligibilityData['showEligibilityAdditionalInfo'] = true;
                     }

                     if(count($baseSpecStringArray)>1){
                        $baseSpecStringHeading = 'Mandatory Courses :';
                     }else{
                        $baseSpecStringHeading = 'Mandatory Course :';
                     }

                    $eligibilityData['table'][$val]['additionalInfo'] = $eligibilityData['table'][$val]['additionalInfo'] == 'N/A' ? $baseSpecStringHeading." ".implode(", ", $baseSpecStringArray) : $baseSpecStringHeading." ".implode(", ", $baseSpecStringArray)."\n".$eligibilityData['table'][$val]['additionalInfo'];
                    $eligibilityData['table'][$val]['additionalInfo'] = $eligibilityData['table'][$val]['additionalInfo'];
                    $eligibilityData['table'][$val]['qualification']  = getQualificationTextForEligibility($val);
                }

            }

            // _p($data['cutoff']);die;
            // cutoff
            $cutoffArray = array();
            foreach ($data['cutoff'] as $cutoff) {
                $eligibilityData['showCutOff'] = true;            
                if($cutoff['cut_off_type'] == '12th'){
                    if($cutoff['quota'] == 'cutOff12th'){
                        $cutoffArray['12th']['12th'] = getFormattedScore($cutoff['cut_off_value'],'percentage');
                    }
                    else{
                        $cutoffArray['12th'][ucfirst($cutoff['quota'])] = getFormattedScore($cutoff['cut_off_value'],'percentage');
                    }
                }
                else if($cutoff['cut_off_type'] == 'exam'){
                    if(empty($cutoff['exam_id'])){
                        $examName = htmlentities($cutoff['custom_exam']);
                        if(empty($eligibilityData['table'][$examName])){
                            $customExams[] = $examName;
                            $eligibilityData['table'][$examName]['eligibility'] = 'N/A';
                            $eligibilityData['table'][$examName]['additionalInfo'] = 'N/A';
                            $eligibilityData['table'][$examName]['cutoff'] = 'N/A';
                        }
                    }
                    else{
                        $examName = $examData[$cutoff['exam_id']]['name'];
                    }
                    if(empty($eligibilityData['cutOffYear']) && !empty($cutoff['exam_year'])){
                        $eligibilityData['cutOffYear'] = $cutoff['exam_year'];
                    }
                    if(empty($desiredIndexOrder[$examName])){
                        $desiredIndexOrder[$examName] = count($desiredIndexOrder)+1;
                    }
                    
                    if($examName) {
                        $cutoffArray['exam'][$examName][$cutoff['round']][ucfirst($cutoff['quota'])] = getFormattedScore($cutoff['cut_off_value'],$cutoff['cut_off_unit'],$cutoff['exam_out_of']);
                    }
                }
            }
            
            if(!empty($cutoffArray['12th'])){
                if(count($cutoffArray['12th']) == 1 && !empty($cutoffArray['12th']['12th'])){
                    $eligibilityData['table']['XII']['cutoff'] = $cutoffArray['12th']['12th'];
                }
                else{
                    $cutoffString = array();
                    foreach ($cutoffArray['12th'] as $key => $value) {
                        $cutoffString[] = $key.": ".$value;
                    }
                    $eligibilityData['table']['XII']['cutoff'] = implode('<br> ',$cutoffString);
                }
                $eligibilityData['table']['XII']['qualification']  = getQualificationTextForEligibility('XII');            
            }

            if(!empty($cutoffArray['exam'])){
                $eligibilityData['examCutoffData'] = $cutoffArray['exam'];
                foreach ($cutoffArray['exam'] as $examName => $examRow) {
                    $maxRound      = max(array_keys($examRow))+1;
                    // $showRoundText = (count($examRow) > 1) ? "Round $maxRound" :'';

                    if($eligibilityData['table'][$examName]['cutoff'] == 'N/A'){
                        $eligibilityData['table'][$examName]['cutoff'] = array();
                    }
                    if(empty($eligibilityData['table'][$examName]['qualification'])){
                        $eligibilityData['table'][$examName]['qualification'] = $examName;
                    }

                    $eligibilityData['table'][$examName]['relatedStates'] = array();
                    $quotaCount = array();
                    foreach ($examRow as $round => $roundData) {
                        $showQuotaText = (count($roundData) == 1 && key(reset($roundData)) == 'All_india') ? false : true;
                        foreach ($roundData as $quota => $value) {
                            if(stripos($quota,'Related_states') !== false){
                                $temp = explode(':',$quota);
                                if($temp[1] && empty($eligibilityData['table'][$examName]['relatedStates'])){
                                    $eligibilityData['table'][$examName]['relatedStates'] = explode(',',$temp[1]);
                                    $stateIds = array_merge($stateIds,explode(',',$temp[1]));                                
                                }
                                $quota = $temp[0];                            
                            }

                            if(empty($quotaCount[$quota])){
                                $quotaCount[$quota] = 1;
                            }
                            else{
                                ++$quotaCount[$quota];
                            }

                            if($round == $maxRound-1){
                                $quotaText = ($showQuotaText) ? ucwords(implode(explode('_',$quota),' ')): '';
                                if(stripos($quotaText,'Related States:') !== false){                                
                                    $temp = explode(':',$quotaText);
                                    $quotaText = $temp[0];
                                }
                                // if(!empty($showRoundText) && !empty($quotaText)){
                                //     $eligibilityData['table'][$examName]['cutoff'][] = array('cutoffstr'=>$quotaText.' ('.$showRoundText.'): '.$value,'quota'=>$quota,'round'=>$round+1);
                                // }
                                // else{
                                    $eligibilityData['table'][$examName]['cutoff'][] = array('cutoffstr'=>$quotaText.': '.$value,'quota'=>$quota,'round'=>$round);
                                // }
                            }
                        }
                    }
                    $eligibilityData['table'][$examName]['quotaCount'] = $quotaCount;
                }
            }


            if(!empty($stateIds)){
                $statesData = $this->locationRepo->findMultipleStates($stateIds);
                foreach ($eligibilityData['table'] as $key => $row) {
                    if(!empty($row['relatedStates'])){
                        $relatedStates = array_map(function($a) use ($statesData){
                            return $statesData[$a]->getName();
                        },$row['relatedStates']);
                        $eligibilityData['table'][$key]['relatedStates'] = implode($relatedStates,', ');
                        $temp = count($relatedStates) > 1 ? "Related States: " : "Related State: ";
                        if(!$eligibilityData['showEligibilityAdditionalInfo']){
                            $eligibilityData['showEligibilityAdditionalInfo'] = true;
                        }
                        $eligibilityData['table'][$key]['additionalInfo'] = $temp.implode($relatedStates,', ');
                    }
                }
            }
            
            if(!empty($eligibilityData['table'])){
                uksort($eligibilityData['table'], function($a, $b) use ($desiredIndexOrder) {
                    return $desiredIndexOrder[$a] > $desiredIndexOrder[$b] ? 1 : -1;
                });
            }

            foreach ($eligibilitySectionWithoutExam as $key => $section) { 
                if(empty($eligibilityData['table'][$section]['qualification'])){
                    unset($eligibilityData['table'][$section]);
                }
            }
            $eligibilityData['showEligibilityWidget'] = true;
            // _p($eligibilityData['table']);
            if(empty($eligibilityData['table']) && empty($eligibilityData['description']) && empty($eligibilityData['international_students_desc']) && empty($eligibilityData['work_min']) && empty($eligibilityData['work_max']) && empty($eligibilityData['age_min']) && empty($eligibilityData['age_max'])){
                $eligibilityData['showEligibilityWidget'] = false;
            }
            if($eligibilityData['showEligibilityWidget']) {
                $allCategoriesShowEligibilityWidget = true;
            }
            if(!empty($eligibilityData['table'])) {
                $allCategoryData['tableDataExist'] = 1;
            }
            $allCategoryData['categoryData'][$categoryName] = $eligibilityData;
        }
        if(empty($eligibleCategories) && !empty($dataFromDb['examEligibility'])) {
            $examEligibilityData = $this->formatEligibilityData($dataFromDb['examEligibility'],'examEligibility','');
            $formattedExamEligibilityData = array();
            $this->addExamDetailsForEligibility($examEligibilityData, $formattedExamEligibilityData);
            $allCategoryData['categoryData']['noneAvailable']['table'] = $formattedExamEligibilityData['table'];
            $allCategoriesShowEligibilityWidget = true;
            $allCategoryData['tableDataExist'] = 1;
        }


        if(empty($eligibleCategories) && !empty($dataFromDb['categoryWise'])) {
            $categoryWiseEligibilityData = $this->formatEligibilityData($dataFromDb['categoryWise'],'categoryWise','');
            $formattedCategoryWiseData = array();
            $this->addStandardInfoForEligibility($categoryWiseEligibilityData, $formattedCategoryWiseData);
            // _p($formattedCategoryWiseData);
            if(empty($allCategoryData['categoryData']['noneAvailable']['table'])) {
                $allCategoryData['categoryData']['noneAvailable']['table'] = $formattedCategoryWiseData['table'];
            }
            else {
                $allCategoryData['categoryData']['noneAvailable']['table'] = array_merge($formattedCategoryWiseData['table'], $allCategoryData['categoryData']['noneAvailable']['table']);
            }
            $allCategoryData['tableDataExist'] = 1;
            if($formattedCategoryWiseData['showEligibilityAdditionalInfo']) {
                $allCategoryData['categoryData']['noneAvailable']['showEligibilityAdditionalInfo'] = 1;
            }
            $allCategoriesShowEligibilityWidget = true;
        }

        if(!empty($dataFromDb['basic']['batch_year'])) {
            $allCategoryData['batch_year'] = $dataFromDb['basic']['batch_year'];
        }

        if(!empty($dataFromDb['basic']['age_min'])) {
            $allCategoriesShowEligibilityWidget = true;
            $allCategoryData['age_min'] = $dataFromDb['basic']['age_min'];
        }

        if(!empty($dataFromDb['basic']['age_max'])) {
            $allCategoriesShowEligibilityWidget = true;
            $allCategoryData['age_max'] = $dataFromDb['basic']['age_max'];
        }

        if(!empty($dataFromDb['basic']['work-ex_min'])) {
            $allCategoriesShowEligibilityWidget = true;
            $allCategoryData['work_min'] = $dataFromDb['basic']['work-ex_min'];
        }

        if(!empty($dataFromDb['basic']['work-ex_max'])) {
            $allCategoriesShowEligibilityWidget = true;
            $allCategoryData['work_max'] = $dataFromDb['basic']['work-ex_max'];
        }

        if(!empty($dataFromDb['basic']['international_students_desc'])) {
            $allCategoriesShowEligibilityWidget = true;
            $allCategoryData['international_students_desc'] = htmlentities($dataFromDb['basic']['international_students_desc']);
        }

        if(!empty($dataFromDb['basic']['description'])) {
            $allCategoriesShowEligibilityWidget = true;
            $allCategoryData['description'] = htmlentities($dataFromDb['basic']['description']);
        }
        $allCategoryData['showEligibilityWidget'] = ($allCategoriesShowEligibilityWidget) ? $allCategoriesShowEligibilityWidget : false;
        // _p($allCategoryData); die;
        return $allCategoryData;


    }

    function checkPlacementDataForCourseAMP($instId, $courseId){
        if(empty($instId) || empty($courseId)){
            return array();
        }
        $inst_course_array = array();
        $inst_course_array[$courseId] = $instId;
        $coursesWithPlacementData = modules::run('listing/Naukri_Data_Integration_Controller/getCourseHavingNaukriData', $inst_course_array);
        return count($coursesWithPlacementData);
    }

    function getSeoData($courseObj, $instituteObj, $questionCount, $reviewCount) {
        $seoTitle = $courseObj->getSeoTitle();
        $description = $courseObj->getSeoDescription();

        if(empty($seoTitle)) {
            $metaTitleConfig = $this->CI->config->item('courseMetaTitle');
            $primaryHierarchy = $courseObj->getPrimaryHierarchy();
            $baseCourses = $courseObj->getBaseCourse();
            if($primaryHierarchy['specialization_id'] > 0) {
                $specializationName = $this->hierarchyRepo->findSpecialization($primaryHierarchy['specialization_id'])->getName();
            }
            if($baseCourses['entry'] > 0) {
                $baseCourseName = $this->baseCourseRepo->find($baseCourses['entry'])->getName();
            }
            $metaTitleConfig;
            $currentLocation = $instituteObj->getMainLocation(); 
            
            if(!empty($instituteObj->getShortName())){
                $instName = $instituteObj->getShortName();
            }
            else {
                $instName = $instituteObj->getName();
            }

            // $instituteNameData = getInstituteNameWithCityLocality($instName,$instituteObj->getListingType(),$currentLocation->getCityName());
            $courseName = $courseObj->getName();
            
            //base course and specialization is available
            if(!empty($baseCourseName) && !empty($specializationName)) {
                $title = $metaTitleConfig['allAvailable'];                
            }
            //specialization is available
            else if(!empty($specializationName)) {
                $title = $metaTitleConfig['noneAvailable'];
            }
             //base course and specialization is available
            else if(!empty($baseCourseName)) {
                $title = $metaTitleConfig['baseCourse'];                
            }
            else{
                $title = $metaTitleConfig['noneAvailable'];
            }

            // $title = $metaTitleConfig;
            $search = array('<fullNameWithLocation>','<courseName>', '<baseCourseName>', '<specName>');
            $replace = array($instName, $courseName, $baseCourseName, $specializationName);
            
            $seoTitle = str_replace($search, $replace, $title);
        }
        
        if(empty($description)) {
            $metaDescriptionConfig = $this->CI->config->item('courseMetaDescription');
            $courseIdsToExclude  = $this->CI->config->item('courseIdsToExclude');
            $priorityList =  $this->CI->config->item('priorityList');
            $currentLocation = $instituteObj->getMainLocation();    
            $city = $courseObj->getMainLocation()->getCityName();
            $instituteName = $instituteObj->getShortName();
            if(!empty($instituteObj->getShortName())){
                $instName = $instituteObj->getShortName();
            }
            else {
                $instName = $instituteObj->getName();
            }
            // $instituteNameData = getInstituteNameWithCityLocality($instName,$instituteObj->getListingType(),$currentLocation->getCityName());
            $courseName = $courseObj->getName();
            $secondaryName = $instituteObj->getSecondaryName();
            $metaDescriptionConfig = $metaDescriptionConfig;
            $search = array('<reviewCount>', '<questionCount>', '<courseName>', '<fullNameWithLocation>', '<secondaryName>');
            if($reviewCount < 1)  {
                $reviewCount = '';
            }
            if($questionCount < 1) {
                $questionCount = '';
            }
            $replace = array($reviewCount, $questionCount, $courseName,$instName,$secondaryName);

            //all available
            $description = str_replace($search, $replace, $metaDescriptionConfig['allAvailable']);
            
            /*if($questionCount > 0 && $reviewCount > 0) {
            }
            else if($questionCount > 0 && empty($reviewCount)) {
                $description = str_replace($search, $replace, $metaDescriptionConfig['questions']);
            }
            else if($reviewCount > 0 && empty($questionCount)) {
                $description = str_replace($search, $replace, $metaDescriptionConfig['reviews']);
            }
            else {
                $description = str_replace($search, $replace, $meta`Config['noneAvailable']);
            }*/
        }

        //get canonical
        if($courseIdsToExclude[$courseObj->getId()] == 1){
            $canonicalURL = $courseObj->getURL();
        }
        else if($instituteObj->getType() == "university"){
            $canonicalURL = $instituteObj->getURL();
        }
        else if($instituteObj->getType() == "institute"){
            $instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');
            $instituteId = $instituteObj->getId();
            $instituteHierarchy = $instituteDetailLib->getInstituteListingHierarchyDataNew(array($instituteId));
            $instituteHierarchy = $instituteHierarchy[$instituteId];

            if((count($instituteHierarchy['university']) + count($instituteHierarchy['institute'])) == 1){
                $canonicalURL = $instituteObj->getURL();
            }
            else{
               
                $counterForPriority = 0;
                $priorityULPArray = array();
                $priorityILPArray = array();
                $ULPArray = array();
                $ILPArray = array();
                foreach ($instituteHierarchy['university'] as $row) {
                    if($priorityList[$row['listing_id']] == 1){
                        $counterForPriority++;
                        $priorityULPArray[] = $row['listing_id'];
                    }
                    else{
                        $ULPArray[] =  $row['listing_id'];
                    }
                }
                foreach ($instituteHierarchy['institute'] as $row) {
                    if($priorityList[$row['listing_id']] == 1){
                        $counterForPriority++;
                        $priorityILPArray[] = $row['listing_id'];
                    }
                    else{
                        $ILPArray[] =  $row['listing_id'];
                    }
                }
                if($counterForPriority == 1){
                    $priorityListingId = 0;
                    if(count($priorityULPArray) == 1){
                        $priorityListingId = $priorityULPArray[0];   
                    }
                    else{
                        $priorityListingId = $priorityILPArray[0];
                    }
                    $priorityInstituteObj = $this->instituteRepo->find($priorityListingId);
                    $canonicalURL = $priorityInstituteObj->getURL();
                }
                else if($counterForPriority > 1){
                    $priorityListingId = 0;
                    if(count($priorityULPArray) >= 1){
                        $priorityListingId = $priorityULPArray[0];   
                    }
                    else if(count($priorityILPArray) >= 1){
                        $priorityListingId = $priorityILPArray[0];
                    }
                    $priorityInstituteObj = $this->instituteRepo->find($priorityListingId);
                    $canonicalURL = $priorityInstituteObj->getURL();
                }
                else if($counterForPriority == 0){
                    $listing_id = 0;
                    if(count($ULPArray)>0){
                        $listing_id = $ULPArray[count($ULPArray)-1];
                    }
                    else{
                        $listing_id = $ILPArray[0];
                    }
                    $institute_obj = $this->instituteRepo->find($listing_id);
                    $canonicalURL = $institute_obj->getURL();
                }
            }
        }

        return array('title' => special_chars_replace($seoTitle),'description' => special_chars_replace($description),'canonicalURL' => $canonicalURL);
    }

    function addExamDetailsForEligibility($eligibilityExams, &$eligibilityData, &$desiredIndexOrder,$examDataById = array()) {
        if(empty($examDataById)){
            $examIds = array();
            if(!empty($eligibilityExams)){
                foreach ($eligibilityExams as $row) {
                    $examIds[] = $row['exam_id'];
                }
            }
            $examIds = array_unique(array_values(array_filter($examIds)));
        }
        if(!empty($examIds) || !empty($examDataById)){
            if(empty($examDataById)){
                $examData = $this->examLib->getExamDataByExamIds($examIds);                
            }
            else{
             $examData = $examDataById;   
            }
            foreach ($examData as $examId=>$row) {
                $eligibilityData['table'][$row['name']]['eligibility']    = 'N/A';
                $eligibilityData['table'][$row['name']]['additionalInfo'] = 'N/A';
                $eligibilityData['table'][$row['name']]['cutoff']         = 'N/A';
                if($row['scope'] == 'national') {
                    $examDomainName = SHIKSHA_HOME;
                }
                else {
                    $examDomainName = SHIKSHA_STUDYABROAD_HOME;
                }
                $eligibilityData['table'][$row['name']]['url']            = !empty($row['url']) ? addingDomainNameToUrl(array('url' => $row['url'], 'domainName' =>$examDomainName)) : NULL;
            }
        }

        if(!empty($eligibilityExams)){
            foreach ($eligibilityExams as $key => $row) {        
                $examName = empty($row['exam_id']) ? htmlentities($row['exam_name']) : $examData[$row['exam_id']]['name'];
                
                if($examName){
                    if(empty($eligibilityData['table'][$examName])){
                        $customExams[] = $examName;
                        $eligibilityData['table'][$examName]['eligibility'] = 'N/A';
                        $eligibilityData['table'][$examName]['additionalInfo'] = 'N/A';
                        $eligibilityData['table'][$examName]['cutoff'] = 'N/A';
                    }
                    $eligibilityData['table'][$examName]['qualification'] = $examName;
                    if(empty($desiredIndexOrder[$examName])){
                        $desiredIndexOrder[$examName] = count($desiredIndexOrder)+1;
                    }

                    if(!$eligibilityData['showEligibilityVal'] && $row['value']){
                        $eligibilityData['showEligibilityVal'] = true;
                    }
                    $eligibilityData['table'][$examName]['eligibility']   = getFormattedScore($row['value'],$row['unit'],$row['max_value']);
                }

            }
        }

        return $examData;
    }

    function addStandardInfoForEligibility($categoryWiseData, &$eligibilityData) {
        if(!empty($categoryWiseData)){
            foreach ($categoryWiseData as $key => $row) {                
                $eligibilityData['table'][$row['standard']]['qualification'] = getQualificationTextForEligibility($row['standard']);

                if($row['value']){
                    if(!$eligibilityData['showEligibilityVal']){
                        $eligibilityData['showEligibilityVal'] = true;
                    }

                    $eligibilityData['table'][$row['standard']]['eligibility']   = getFormattedScore($row['value'],$row['unit'],$row['max_value']);
                }

                if(!empty($row['specific_requirement'])){
                     if(!$eligibilityData['showEligibilityAdditionalInfo']){
                        $eligibilityData['showEligibilityAdditionalInfo'] = true;
                     }
                    $eligibilityData['table'][$row['standard']]['additionalInfo'] = $row['specific_requirement'];
                }
            }
        }
    }

    function getCoursesFromExams($examIds) {
        if(empty($examIds)) {
            return;
        }

        $this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Exam_Cache_start');
        $examCourseMappingFromCache = $this->nationalCourseCache->getExamCourseMapping($examIds);
        $this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Exam_Cache_end');
        
        // Determine what all exams' data need to be fetched from DB
        $examsFromCache = array_keys($examCourseMappingFromCache);
        
        if(!empty($examsFromCache)) {
            $examsToBeFetchedFromDB = array_diff($examIds, $examsFromCache);
        } else {
            $examsToBeFetchedFromDB = $examIds;
        }
        
        // Get data from DB
        if(!empty($examsToBeFetchedFromDB)) {
            $this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Exam_DB_start');
            $dataFromDb = $this->coursedetailmodel->getCoursesFromExams($examsToBeFetchedFromDB);
            $this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Exam_DB_end');

            foreach ($dataFromDb as $key => $value) {
                $examIdsWithData[] = $value['exam_id'];
                $examCourseMappingFromDb[$value['exam_id']][] = array('course_id' => $value['course_id'], 'institute_id' => $value['primary_id']);
            }
            $examIdsWithoutData = array_diff($examsToBeFetchedFromDB, $examIdsWithData);
            
            foreach ($examIdsWithoutData as $key => $examId) {
                $examCourseMappingFromDb[$examId] = 'NA';
            }

            $this->nationalCourseCache->setExamCourseMapping($examCourseMappingFromDb);
        }

        if(!empty($examCourseMappingFromCache) && !empty($examCourseMappingFromDb)) {
            $result = array_merge($examCourseMappingFromCache, $examCourseMappingFromDb);
        }
        else if(!empty($examCourseMappingFromCache)) {
            $result = $examCourseMappingFromCache;
        } else {
            $result = $examCourseMappingFromDb;
        }

        $result = array_filter($result, function($k) { return $k != 'NA'; });
        
        return $result;
    }

    function getCoursesFromBaseCourses($baseCourseIds) {
        if(empty($baseCourseIds)) {
            return;
        }

        $this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Course_Cache_start');
        $baseCourseMappingFromCache = $this->nationalCourseCache->getBaseCourseToCourseMapping($baseCourseIds);
        $this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Course_Cache_end');
        
        // Determine what all exams' data need to be fetched from DB
        $baseCoursesFromCache = array_keys($baseCourseMappingFromCache);
        
        if(!empty($baseCourseMappingFromCache)) {
            $baseCoursesToBeFetchedFromDB = array_diff($baseCourseIds, $baseCoursesFromCache);
        } else {
            $baseCoursesToBeFetchedFromDB = $baseCourseIds;
        }
        
        // Get data from DB
        if(!empty($baseCoursesToBeFetchedFromDB)) {
            $this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Course_DB_start');
            $dataFromDb = $this->coursedetailmodel->getCoursesFromBaseCourses($baseCoursesToBeFetchedFromDB);
            $this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Course_DB_end');
            
            foreach ($dataFromDb as $key => $value) {
                $baseCourseIdsWithData[] = $value['base_course'];
                $baseCourseMappingFromDb[$value['base_course']][] = array('course_id' => $value['course_id'], 'institute_id' => $value['primary_id']);
            }
            $baseCourseIdsWithoutData = array_diff($baseCoursesToBeFetchedFromDB, $baseCourseIdsWithData);
            
            foreach ($baseCourseIdsWithoutData as $key => $baseCourseId) {
                $baseCourseMappingFromDb[$baseCourseId] = 'NA';
            }

            $this->nationalCourseCache->setBaseCourseToCourseMapping($baseCourseMappingFromDb);
        }

        if(!empty($baseCourseMappingFromCache) && !empty($baseCourseMappingFromDb)) {
            $result = array_merge($baseCourseMappingFromCache, $baseCourseMappingFromDb);
        }
        else if(!empty($baseCourseMappingFromCache)) {
            $result = $baseCourseMappingFromCache;
        } else {
            $result = $baseCourseMappingFromDb;
        }

        $result = array_filter($result, function($k) { return $k != 'NA'; });
        
        return $result;
    }

    function filterCoursesBasedOnHeirarchy($courseIds, $criteria) {
        if(empty($courseIds)) {
            return;
        }

        $filteredCourseIds = $this->coursedetailmodel->filterCoursesBasedOnHeirarchy($courseIds, $criteria);

        $finalCourseIds=array();
        foreach ($filteredCourseIds as $key => $value) {
            $finalCourseIds[] = $value['course_id'];
        }

        return $finalCourseIds;
    }

    function getSponsoredWidgetData($courseId, $courseObj) {
        $sponsoredWidgetData = array();
        if(!$courseObj->isPaid()) {
            $sponsoredWidgetFreeInstitutes = $this->CI->config->item('sponsoredWidgetFreeCourses');
            $sponsoredWidgetConfigData = $this->CI->config->item('sponsoredWidgetPaidData');
            if(!empty($sponsoredWidgetFreeInstitutes[$courseId]) && !empty($sponsoredWidgetConfigData[$sponsoredWidgetFreeInstitutes[$courseId]])) {
                $sponsoredWidgetData = $sponsoredWidgetConfigData[$sponsoredWidgetFreeInstitutes[$courseId]];
            }
        }

        return $sponsoredWidgetData;
    }

}
