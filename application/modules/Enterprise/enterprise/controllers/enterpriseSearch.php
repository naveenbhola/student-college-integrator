<?php

class enterpriseSearch extends MX_Controller {

    private $userStatus = 'false';
    private $numberOfResults = 100;
    private $defaultCreditCriteria = array('ViewCredit' => 60,
                                'SmsCredit' => 5,
                                'EmailCredit' => 3,
                                'SMSCount'=>15,
                                'EmailCount'=>20,
                                'ViewCount'=>8);
    private $searchTabs = array(
            array('ctab_name' =>'Study Abroad','course_name' => 'Study Abroad', 'tab_type' => 'studyAbroad'),
            array('ctab_name' =>'Domestic Leads','course_name' => 'National Courses', 'tab_type' => 'national'),
            array('ctab_name' =>'Domestic MR','course_name' => 'MBA (Full Time)', 'tab_type' => 'national'),
            array('ctab_name' =>'Domestic MR','course_name' => 'B.E./B.Tech (Full Time)', 'tab_type' => 'national')
        );

    function init() {
        // $this->load->helper(array('url', 'form'));
        $this->load->library(array('enterprise_client', 'LDB_Client'));
        $this->userStatus = $this->checkUserValidation();
        if (($this->userStatus == "false" ) || ($this->userStatus == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        if ($this->userStatus[0]['usergroup'] != 'enterprise') {
            header("location:/enterprise/Enterprise/unauthorizedEnt");
        }
        $validity = $this->userStatus;
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        $headerTabs[0]['selectedTab'] = 31;
        $this->userStatus[0]['headerTabs'] = $headerTabs;
    }
    
    private function _checkStremsClientHasAccessTo($client_id,$data) {
				    
			if(count($data) == 0) {
					return array();
			} 
			
		    if($client_id >0) {
				$model_object = $this->load->model('LDB/ldbmodel');
				$streams = $model_object->getManualLDBAccessDataByStream($client_id);
				$streams_ids = array();
								
				foreach($streams as $val) {
					$streams_ids[] = $val['streamId'];
				}
								
				foreach($data as $key=>$value) {
					if(in_array($value['id'],$streams_ids)) {
						unset($data[$key]);	
					}
				}
				
			}	
			
		 	return $data; 
    } 
    
    function index() {
		
        $this->init();
        $configFile = APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
        require $configFile;        
        $courseName = '';
        
        if(isset($_REQUEST['course_name'])) {
			$courseName = $_REQUEST['course_name'];
        }    
        
        $search_category_id = '';
        if(isset($_REQUEST['categoryId'])) {
            $search_category_id = $_REQUEST['categoryId'];
        }   
                
	if($courseName != '' && array_key_exists($courseName,$coursesList)){
		$searchFormViewFile = 'searchFormMatchedResponses';
	} else {
		$data = modules::run('userSearchCriteria/searchCriteria/getDataForSearchForm');
		$searchFormViewFile = 'userSearchCriteria/searchGenie';
		$streams_array = $this->_checkStremsClientHasAccessTo($this->userStatus[0]['userid'],$data['streams']);
		if(count($streams_array) == 0) {
			$searchFormViewFile='enterprise/unauthorizedLDBTabs';
		} else {
			$data['streams']=$streams_array;
		}
	}            
		
        $data['validateuser'] = $this->userStatus;
        $clientId = $data['validateuser'][0]['userid'];
        $data['viewFile'] = $searchFormViewFile;
        
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];    
        
        $data['searchTabs'] = $this->searchTabs;
        $data['prodId'] = 31;
        $data['course_name'] = $courseName;
        $data['actual_course_name'] = $courseName;
        $data['search_category_id'] = $search_category_id;

        if($courseName != '' && array_key_exists($courseName,$coursesList)){
            $course = ($_REQUEST['course_name']) ? ($_REQUEST['course_name']) : $courseName;
            // $matchedResponsesData = modules::run('enterprise/shikshaDB/getDataForMatchedResponses', $clientId, $courseName, $coursesList);
            
            $matchedResponsesData = $this->getFormDataForMatchedResponses($clientId, $courseName, $coursesList);

            if($clientHaveAuthorizationForLDBSearchTabs || 1){ // to be changed when decided
                $data['viewFile'] = $matchedResponsesData['viewFile'];
            }
            $data['message'] = $matchedResponsesData['message'];
            $data['actual_course_id'] = $matchedResponsesData['actual_course_id'];
            $data['course_level'] = $matchedResponsesData['course_level'];
            $data['instituteList'] = array();
            $data['instituteList'] = $matchedResponsesData['instituteList'];

            $examParams = array('national' => 'yes',
                                'baseEntityArr' => array( array('streamId' => $coursesList[$courseName]['stream_id'])),
                                'courseIds' => array( $coursesList[$courseName]['base_course'] ));

            $examsFieldValueSource = new \registration\libraries\FieldValueSources\Exams;
            $data['examValues'] = $examsFieldValueSource->getValues($examParams);

            $this->load->library('category_list_client');
            $categoryClient = new Category_list_client();
            $ldbObj = new LDB_client();
            $cityListTier1 = $categoryClient->getCitiesInTier($appId, 1, 2);
            $cityListTier2 = $categoryClient->getCitiesInTier($appId, 2, 2);
            $data['cityList'] = array_merge($cityListTier1, $cityListTier2);
            $data['cityList_tier1'] = $cityListTier1;
            $data['cityList_tier2'] = $cityListTier2;
            $data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12), true);
          
        }

        $this->load->library('SearchAgents_client');
        $categoryClient = new SearchAgents_client();
        $appId = 1;
        $search_agents_array = $categoryClient->getAllSearchAgents($appId, $clientId);
        $data['search_agents_array'] = $search_agents_array;
        
        // Start Online form change by pranjul 13/10/2011
        $this->load->library('OnlineFormEnterprise_client');
        $ofObj = new OnlineFormEnterprise_client();
        $data['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($clientId);
        // End Online form change by pranjul 13/10/2011
        
        $cmsuserinfo = $this->cmsUserValidation();
        $data['myProducts'] = $cmsuserinfo['myProducts'];
        $data['usergroup'] = 'enterprise';
        $data['streamId'] = $coursesList[$courseName]['stream_id'];
        

        $this->load->view('enterprise/studentSearchForm', $data);

    }


    function getFormDataForMatchedResponses($clientId, $course, $coursesList, $isallCourses = 'N', $is_selected_courses = 'N'){

        $this->load->model('listingCommon/listingcommonmodel');
        
        $returnData = array();
        foreach($coursesList as $name => $details){
            if($course == $name){
                $returnData['actual_course_id'] = $details['actual_course_id'];
                $returnData['course_level'] = $details['course_level'];
            }
        }
        $listingIds = $this->listingcommonmodel->getActiveListingsForClients(array($clientId));
        
        $instituteList = array();
        foreach ($listingIds as $key => $listing) {
            if( ($listing['listing_type'] == 'institute' || $listing['listing_type'] == 'university_national') && $listing['listing_type_id']>0) {  
                $instituteList[] = $listing['listing_type_id'];
            }
        }

        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();

        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $courseRepo = $courseBuilder->getCourseRepository();
        

        $this->load->library("nationalInstitute/InstituteDetailLib");
        $lib = new InstituteDetailLib();
 
        if(count($instituteList)>0) {
            $allInstitutes = $instituteRepo->findMultiple($instituteList);
        }

        $returnData = array();

        foreach ($allInstitutes as $instituteId => $instituteObject) {

            $type = $instituteObject->getType();
            $instituteCourseMap = $lib->getInstituteCourseIds($instituteId, $type);

            if(!empty($instituteCourseMap['courseIds'])) {

                $allCourses = $courseRepo->findMultiple($instituteCourseMap['courseIds']);
                $returnData['instituteList'][$instituteId]['courseList'] = array();

                foreach ($allCourses as $courseId => $courseObject) {

                    if(in_array($courseId, $instituteCourseMap['instituteWiseCourses'][$instituteId])){
                    
                        $courseTypeInformationObject = $courseObject->getCourseTypeInformation();
                        $entryCourseObj = $courseTypeInformationObject['entry_course'];

                        if(!empty($entryCourseObj)) {

                            $courseHierarchies = $entryCourseObj->getHierarchies();
                            $base_course = $entryCourseObj->getBaseCourse();
                            $educationTypeObj = $courseObject->getEducationType();
                            if(!empty($educationTypeObj)){
                                $education_type = $educationTypeObj->getId();
                            }
                            foreach ($courseHierarchies as $hierarchy) {
                                
                                //if($hierarchy['primary_hierarchy'] == 1) {
                                $stream_id = $hierarchy['stream_id'];
                                //}
                                if(($stream_id == $coursesList[$course]['stream_id']) && ($base_course == $coursesList[$course]['base_course']) && ($education_type == $coursesList[$course]['education_type'])){

                                    $courseArray = array();
                                    $courseArray['id'] = $courseObject->getId();
                                    $courseArray['name'] = $courseObject->getName();
                                    $returnData['instituteList'][$instituteId]['courseList'][] = $courseArray;
                                    break;

                                }
                                
                            }

                        }
                        unset($stream_id);
                        unset($courseArray);
                        unset($base_course);
                        unset($education_type);

                    }

                }

            }
            
            if(!empty($returnData['instituteList'][$instituteId]['courseList'])) {
                $returnData['instituteList'][$instituteId]['instituteData']['listing_type_id'] = $instituteId;
                $returnData['instituteList'][$instituteId]['instituteData']['listing_type'] = $instituteObject->getType();
                switch ($instituteObject->getType()) {
                    case 'institute':
                        $returnData['instituteList'][$instituteId]['instituteData']['listing_title'] = $instituteObject->getName()." (College)";
                        break;

                    case 'university':
                        $returnData['instituteList'][$instituteId]['instituteData']['listing_title'] = $instituteObject->getName()." (University)";
                        break;
                    
                    default:
                        $returnData['instituteList'][$instituteId]['instituteData']['listing_title'] = $instituteObject->getName();
                        break;
                }
                
                $returnData['instituteList'][$instituteId]['instituteData']['pack_type'] = $instituteObject->getPackType();
            }
            else {
                unset($returnData['instituteList'][$instituteId]);
            }

        }

        if(!empty($returnData['instituteList'])){
            $returnData['viewFile'] = 'searchFormMatchedResponses';
        }
        else{
            $returnData['viewFile'] = 'unauthorizedLDBTabs';
            $returnData['message'] = 'No course exists for '.$course;
        }
        return $returnData;
    }

    function formSubmitNonMR(){ 
         ini_set('memory_limit', '512M');
        $data = array();
        global $examGrades;
        
        $this->load->library('LDB_Client');
        $ldbObj = new LDB_client();
        $this->load->model('ldbmodel');

        $this->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new \ListingBaseBuilder();
        $HierarchyRepository = $listingBase->getHierarchyRepository();
        $BaseCourseRepository = $listingBase->getBaseCourseRepository();
        $this->load->library('listingBase/BaseAttributeLibrary');
        $baseAttributeLibrary = new BaseAttributeLibrary();

        $postArray = array();
        $displayArray = array();
        
        $streamId = $this->input->post('stream_1',true);
        $streamObj = $HierarchyRepository->findStream($streamId);
        $streamData = $streamObj->getObjectAsArray();
        $postArray['stream'] = $streamData['stream_id'];
        $displayArray['stream'] = $streamData['stream_id'];
        $displayArray['isActiveUser'] = $this->input->post('includeActiveUsers');
        $postArray['streamData'] = $streamData;
        $displayArray['streamData'][$streamData['stream_id']] = $streamData['name'];
        
        $criteriaNos = $this->input->post('criteriaNos',true);
        $allFieldsData = json_decode($this->input->post('allFieldsData',true));
        $allsubStreamSpecializationMapping = json_decode($this->input->post('subStreamSpecializationMapping',true));
        $allungroupedSpecializations = json_decode($this->input->post('ungroupedSpecializations',true));
        $hyperlocal = $this->input->post('hyperlocal_1',true);
        $isCourseSelected = $this->input->post('isCourseSelected',true);

        global $noSpecId;
        global $noSpecName;

        $fieldsData = array();$substream = '';$specialization = '';$courses = '';$mode = '';$city = '';$locality = '';

        foreach($criteriaNos as $criteriaNo) {
            if($allFieldsData->$criteriaNo != '') {
                $fieldsData = $allFieldsData->$criteriaNo;
                $courses = $fieldsData->courses;
                $mode = $fieldsData->mode;
                $city = $fieldsData->city;
                $locality = $fieldsData->locality;
            }
        }

        $subStreamSpecializationMapping = (array)$allsubStreamSpecializationMapping->$criteriaNo;
        $ungroupedSpecializations = $allungroupedSpecializations->$criteriaNo;

        foreach ($subStreamSpecializationMapping as $subStreamId => $specsValues) {
            $substream[] = $subStreamId;
            if(!empty($specsValues)){
                foreach ($specsValues as $key => $value) {
                    if(!in_array($value, $specialization)){
                        $specialization[] = $value;
                    }
                }
            }
        }

        foreach ($ungroupedSpecializations as $key => $value) {
            $specialization[] = $value;
        }

        if(is_array($substream) && count($substream) > 0) {

            $subStreamObj = $HierarchyRepository->findMultipleSubstreams($substream);
            
            foreach ($subStreamObj as $key => $value) {
                $value = $value->getObjectAsArray();
                $postArray['subStream'][] = $value['substream_id'];
                $displayArray['subStream'][] = $value['substream_id'];
                $postArray['subStreamData'][$value['substream_id']] = $value['name'];
                $displayArray['subStreamData'][$value['substream_id']] = $value['name'];
            }
        }

        if(is_array($specialization) && count($specialization) > 0) {

            if(in_array($noSpecId,$specialization)){
                $postArray['specializationId'][]               = $noSpecId;
                $displayArray['specializationId'][]            = $noSpecId;
                $postArray['specializationData'][$noSpecId]    = $noSpecName['noSpecMapping'];
                $displayArray['specializationData'][$noSpecId] = $noSpecName['noSpecMapping'];
            }
            $specializationObj = $HierarchyRepository->findMultipleSpecializations($specialization);
            
            foreach ($specializationObj as $key => $value) {
                $value = $value->getObjectAsArray();
                $postArray['specializationId'][] = $value['specialization_id'];
                $displayArray['specializationId'][] = $value['specialization_id'];
                $postArray['specializationData'][$value['specialization_id']] = $value['name'];
                $displayArray['specializationData'][$value['specialization_id']] = $value['name'];
            }

        }
        
        $postArray['subStreamSpecializationMapping'] = $subStreamSpecializationMapping; 
        $displayArray['subStreamSpecializationMapping'] = $subStreamSpecializationMapping; 

        $postArray['ungroupedSpecializations'] = $ungroupedSpecializations;
        $displayArray['ungroupedSpecializations'] = $ungroupedSpecializations;        

        if(is_array($courses) && count($courses) > 0) {

            $coursesObj = $BaseCourseRepository->findMultiple($courses);
            
            foreach ($coursesObj as $key => $value) {
                $value = $value->getObjectAsArray();
                $postArray['courseId'][] = $value['base_course_id'];
                $displayArray['courseId'][] = $value['base_course_id'];
                $postArray['courseData'][$value['base_course_id']] = $value['name'];         
                $displayArray['courseData'][$value['base_course_id']] = $value['name'];
            }
        }

        if($isCourseSelected == 0 && $hyperlocal != 'All') {
            $displayArray['courseId'] = '';
            $displayArray['courseData'] = '';
        }

        if(is_array($mode) && count($mode) > 0) {
            $attributeIds = array();
            $parentChildrenMapping = array();
            $modeData = array();
            $attributeParentValueIdMapping = $baseAttributeLibrary->getParentValueIdByValueId($mode);
            
            foreach ($attributeParentValueIdMapping as $key => $parentValues) {
                foreach ($parentValues as $parentValue) {
                    
                    if($parentValue == 20 && $key == 33) {
                        $parentChildrenMapping[$parentValue][] = ''; 
                    } else if($parentValue == 21 && !in_array($key,array(33,34,35,36,37,39))) {
                        $parentChildrenMapping[$parentValue][] = ''; 
                    } else {
                        if(!in_array($parentValue, $mode)){
                            $mode[] = $parentValue;
                        }
                        $parentChildrenMapping[$parentValue][] = $key; 
                    }

                }
            }
            
            $attributeIds = $baseAttributeLibrary->getAttributeIdByValueId($mode);
            $attributesValueNamePair = $baseAttributeLibrary->getValueNameByValueId($mode);

            foreach ($attributesValueNamePair as $key => $value) {
                if(array_key_exists($key, $parentChildrenMapping)){
                    $modeData[$value] = '';
                    foreach ($parentChildrenMapping[$key] as $childKey => $childId) {
                        if($childId){
                            $childArray[] = $attributesValueNamePair[$childId];
                        }
                    }
                    asort($childArray);
                    $modeData[$value] = implode(', ',$childArray);
                }
                else if(array_key_exists($key, $attributeParentValueIdMapping)){
                    continue;
                }
                else{
                    $modeData[$value] = '';
                }
            }
            $modeDataDisplay = '';
            foreach ($modeData as $key => $value) {
                $modeDataDisplay .= $key;
                if($value != '') {
                    $modeDataDisplay .= ' - '.$value;
                }
                $modeDataDisplay .= ', ';
            }
            $modeDataDisplay = rtrim($modeDataDisplay, ", ");
            $postArray['attributeIds'] = $attributeIds;
            $postArray['attributeValues'] = $mode;
            $postArray['attributesValueNamePair'] = $attributesValueNamePair;
            $postArray['modeDataDisplay'] = $modeDataDisplay;

            $displayArray['attributeIds'] = $attributeIds;
            $displayArray['attributeValues'] = $mode;
            $displayArray['attributesValueNamePair'] = $attributesValueNamePair;
            $displayArray['modeDataDisplay'] = $modeDataDisplay;

            unset($childArray);
            unset($modeData);
            unset($parentChildrenMapping);
            unset($attributeParentValueIdMapping);
            unset($attributesValueNamePair);
        }
        
        $cityLocalityArray = array();
        if(is_array($city) && count($city) > 0) {
			$allCitiesArray = array();
            $cityLocalityArray['CurrentCities'] = $city;
            $allCitiesArray = $cityLocalityArray['CurrentCities'];
        }

        if($locality != '') {
            $allLocalitiesArray = array();
            $cityLocalityArray['currentLocalities'] = $locality;
            foreach ($cityLocalityArray['currentLocalities'] as $cityId => $localitiesIds) {
                foreach ($localitiesIds as $value) {
                    $allLocalitiesArray[] = $value;
                }
            }
        }

        $citiesIdValues = $this->ldbmodel->getCityNamesFromCityIds($allCitiesArray); 
        foreach ($citiesIdValues as $key => $value) {
            $citiesIdValuesArray[$value['CityId']] = $value['CityName'];
        }

        $localitiesIdValues = $this->ldbmodel->getLocalitiesNamesFromLocalityIds($allLocalitiesArray);
        foreach ($localitiesIdValues as $key => $value) {
            $localitiesIdValuesArray[$value['localityId']] = $value['localityName'];
        }
        
        $this->load->library('userSearchCriteria/UserSearchCriteria');
        $searchCriteria = new UserSearchCriteria();
        $formattedCityLocalityArray = $searchCriteria->formatCityLocalities($cityLocalityArray);
        
        $currentCities = $formattedCityLocalityArray['CurrentCities'];
        $currentLocalities = $formattedCityLocalityArray['currentLocalities'];

        $cityLocalityDisplay = '';
        foreach ($currentCities as $cityKey => $cityId) {
            $cityLocalityDisplay .= $citiesIdValuesArray[$cityId];
            foreach ($cityLocalityArray['currentLocalities'] as $city => $localityArray) {
                if($cityId == $city) {
                    $cityLocalityDisplay .= ' - ';
                    foreach ($localityArray as $key => $localityId) {
                        $cityLocalityDisplay .= $localitiesIdValuesArray[$localityId].', ';
                    }
                    $cityLocalityDisplay = rtrim($cityLocalityDisplay, ", ");
                }
            }
            $cityLocalityDisplay .= ', ';
        }
        $cityLocalityDisplay = rtrim($cityLocalityDisplay, ", ");

        $postArray['CurrentCities'] = $formattedCityLocalityArray['CurrentCities'];
        $postArray['currentLocalities'] = $formattedCityLocalityArray['currentLocalities'];
        $displayArray['CurrentCities'] = $formattedCityLocalityArray['CurrentCities'];
        $displayArray['currentLocalities'] = $formattedCityLocalityArray['currentLocalities'];

        $displayArray['cityLocalityDisplay'] = $cityLocalityDisplay;
        
        $postArray['exams'] = $this->input->post('exams_1',true);
        $displayArray['exams'] = $this->input->post('exams_1',true);

        $timeFilterVar = $this->input->post('timefilter',true);
        $timeFilterVar['from'] = $this->input->post('timeRangeDurationFrom',true);
        $timeFilterVar['to'] = $this->input->post('timeRangeDurationTo',true);

        $displayArray['workex'] = "";
        $minExp_1 = $this->input->post('minExp_1',true);
        $maxExp_1 = $this->input->post('maxExp_1',true);

        $processWorkexArray = $this->processWorkex($minExp_1,$maxExp_1);
        $postArray['MinExp'] = $processWorkexArray['MinExp'];
        $postArray['MaxExp'] = $processWorkexArray['MaxExp'];
        $displayArray['workex'] = $processWorkexArray['workex'];

        $timeFilterVar = $this->convertDateFormat($timeFilterVar);

        if ( isset($timeFilterVar['from']) && isset($timeFilterVar['to']) ){
            $postArray['DateFilterFrom'] = $timeFilterVar['from'];
            $displayArray['DateFilterFrom'] = $timeFilterVar['from'];
            $postArray['DateFilterTo'] = $timeFilterVar['to'].' 23:59:59';
            $displayArray['DateFilterTo'] = $timeFilterVar['to'].' 23:59:59';
        }
        
        $postArray['includeActiveUsers'] = $this->input->post('includeActiveUsers',true);

        // check with adi, for below code
        $this->load->helper("string_helper");
        $search_key = random_string("alnum", 32).time();
        storeTempUserData('search_key',$search_key);
        
        $start = 0;
        $rows = $this->numberOfResults;

        $this->displayResults($postArray, $displayArray,$start,$rows,$search_key);

    }

    function displayResults($inputArray, $displayArray, $start, $rows,$search_key) {

        $this->init();
        Global $managementStreamMR;
        Global $engineeringtStreamMR;

        $data['validateuser'] = $this->userStatus;
        $Validity = $this->checkUserValidation();
        $this->load->model('ldbmodel');
        $ClientId = $Validity[0]['userid'];

        $data['ClientId'] = $ClientId;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        $data['searchTabs'] = $this->searchTabs;
        $data['prodId'] = 31;
        $data['course_name'] = $inputArray['tab_course_name'];
        $data['actual_course_name'] = $inputArray['actual_course_name'];       
    
        $ldbObj = new LDB_client();
         
        $groupData = $ldbObj->getCreditConsumedByGroup($inputArray['groupId']); 
        foreach($groupData as $limitData) { 
            if($limitData['actionType'] == 'view_limit') { 
                $inputArray['groupViewLimit'] = $limitData['deductcredit']; 
            }     
        }

        $todayDate = date('Y-m-d');
        $data['clientAccess'] = true;
        $clientAccessData = $this->ldbmodel->getManualLDBAccessData($ClientId, $todayDate);
        if(empty($clientAccessData)) {
            $inputArray['DontShowViewed'] =1;
            $data['clientAccess'] = false;
        }

        if($inputArray['Viewed'] || $inputArray['Emailed'] || $inputArray['Smsed']) {
            $response = $this->searchLDB($inputArray,$start, $rows);
        } else{

            if($inputArray['stream'] == $managementStreamMR || $inputArray['stream'] == $engineeringtStreamMR){
                $inputArray['excludeMRPRofile'] = true;
            }
            $response = $this->searchLDBSolr($inputArray,$start,$rows,$this->userStatus[0]['userid']);
        }
        
        $data['DontShowViewed'] = $inputArray['DontShowViewed']; 

        if($inputArray['DontShowViewed']){
            $data['tabFlag'] = 'DontShowViewed';
        } else if($inputArray['Viewed']){
            $data['tabFlag'] = 'Viewed';
        } else if($inputArray['Emailed']){
            $data['tabFlag'] = 'Emailed';
        } else if($inputArray['Smsed']){
            $data['tabFlag'] = 'Smsed';
        } else{
            $data['tabFlag'] = 'All';
        }

        // $data['course_specialization_list'] = json_decode($ldbObj->sgetSpecializationList(12, $courseName), true);
        $data['resultResponse'] = $response;
        $data['displayArray'] = $displayArray;
        $data['inputData'] = base64_encode(json_encode($inputArray));
        $data['displayData'] = base64_encode(json_encode($displayArray));
        $data['flag_manage_page'] = $inputArray['flag_manage_page'];
        
        $data['underViewedLimitFlagSet'] = $inputArray['underViewedLimitFlagSet'];
            
        $responses = $this->ldbmodel->getResponsesForClient($ClientId, $response['userIds']);
        $data['responses'] = $responses;
        
        $_POST['countOffsetSearch'] = $this->numberOfResults;

        $this->load->view("enterprise/searchResult", $data);
    }

    function processWorkex($minExp='', $maxExp='') {

        $returnArray = array();

        if ($minExp != '' && $maxExp != '' && $minExp == $maxExp) {
            if($minExp == -1){
                $returnArray['MinExp'] = $minExp;
                $returnArray['MaxExp'] = $minExp;
                $returnArray['workex'] = 'No Experience';
            }
            else {
                if($minExp == 0 || $minExp == 1) {
                    $year = ' year';
                }
                else if($minExp > 1){
                    $year = ' years';
                }
                $returnArray['MinExp'] = $minExp;
                $returnArray['MaxExp'] = $minExp;
                if($minExp == 10){
                    $returnArray['workex'] = 'greater than '. $minExp . $year;
                }
                else {
                    $returnArray['workex'] = $minExp . $year;
                }
            }
        } 
        else {
            if (isset($minExp)) {
                if (is_numeric($minExp)) {
                    if($minExp == -1){
                        $returnArray['MinExp'] = $minExp;
                        $returnArray['workex'] = 'any experience';
                    }
                    else {
                        if($minExp == 0 || $minExp == 1) {
                            $year = ' year';
                        }
                        else if($minExp > 1){
                            $year = ' years';
                        }
                        $returnArray['MinExp'] = $minExp;
                        $returnArray['workex'] = "greater than " . $minExp . $year;
                    }
                }
            }
            if (isset($maxExp)) {
                if (is_numeric($maxExp)) {
                    if($maxExp == 0 || $maxExp == 1) {
                        $year = ' year';
                    } else if($maxExp > 1) {
                        $year = ' years';
                    }
                    $returnArray['MaxExp'] = $maxExp;
                    if(is_numeric($minExp) && ($minExp == -1 || $minExp == 0 || $minExp == 1)){
                        if($maxExp == 10){
                            if($minExp == -1){
                                $returnArray['workex'] = "any experience";
                            } else if($minExp == 1) {
                                $returnArray['workex'] = "greater than 1 year";
                            } else {
                                $returnArray['workex'] = "greater than 0 year";
                            }
                        }
                        else {
                            if($minExp == -1 || $returnArray['workex'] == ''){
                                $returnArray['workex'] = "less than " . $maxExp . $year;
                            } else {
                                $returnArray['workex'] .= " and less than " . $maxExp . $year;
                            }
                        }
                    }
                    else {
                        if ($returnArray['workex'] == "") {
                            if($maxExp == -1 || $maxExp == 0){
                                $returnArray['workex'] = "no experience";
                            } else if($maxExp == 10){
                                $returnArray['workex'] = "greater than " . $maxExp . $year;
                            } else {
                                $returnArray['workex'] = "less than " . $maxExp . $year;
                            }
                        } else {
                            if($maxExp == 10){
                                $returnArray['workex'] = "greater than " . $minExp . $year;
                            } else {
                                $returnArray['workex'] .= " and less than " . $maxExp . $year;
                            }
                        }
                    }
                }
            }
        }

        return $returnArray;
    }
   
    function searchLDB($inputArray,$resultOffset,$numberOfResults){
          
        /*if($resultOffset == '' || $resultOffset <0)  {
            $resultOffset = 0;
        }

        if($numberOfResults == '' || !isset($numberOfResults)){
            $numberOfResults = $this->numberOfResults;
        }*/

        $clientId = $this->userStatus = $this->checkUserValidation();
        $clientId = $clientId[0]['userid'];
        
        if($inputArray['Viewed'] ) {
            $contactType = 'view';
        }    

        if($inputArray['Emailed']) {
            $contactType = 'email';
        }    

        if($inputArray['Smsed']) {
            $contactType = 'sms';
        }

        if(!empty($inputArray['flagType'])) {
            $flagType = $inputArray['flagType'];
        }

        $streamId = $inputArray['stream'];
        $subStreamId = array();
        foreach ($inputArray['subStream'] as $value) {
            if($value){
                $inputArray['subStreamSpecializationMapping'][$value] = array();
                $subStreamId[] = $value;
            }
        }
        if(empty($subStreamId)){
            $subStreamId = null;
        }
        $inputArray['subStream'] = $subStreamId;

        $count = $this->getContactCountOfClient($clientId, $streamId,$subStreamId,$contactType, $flagType);

        if($count < 1) {
            return false;
        }

        $userIdData = $this->getContactedUsers($clientId, $streamId,$subStreamId,$contactType,$resultOffset,$numberOfResults, $flagType);

        $users = $userIdData['userIds'];
        $userIdMap = $userIdData['userIdMap'];

        $this->load->library('LDB/searcher/LeadSearcher');
        $leadSearcher = new LeadSearcher;
        $isMMM = false;

        $inputArray['grouping'] =true;
        $inputArray['numResults'] =1000;
        $inputArray['resultOffset'] =0;
        $inputArray['excludeMRPRofile']  = true;

        $leads = $leadSearcher->getUserDetailsSolr($users,$inputArray);
        
        $userDetails = $this->getUserDetails($users,$clientId,$leads);
        
        Global $managementStreamMR;
        Global $engineeringtStreamMR;

        if($inputArray['stream'] == $managementStreamMR || $inputArray['stream'] == $engineeringtStreamMR){
            $userDetails = $this->filterMrProfiles($inputArray,$userDetails);

            //$userDetails = $this->filterUserFullTimeMode($userDetails);

        }

        $sortedUserDetails = array();
        foreach ($users as  $userId) {
            if($userDetails[$userId]){
                $sortedUserDetails[$userId] = $userDetails[$userId];
            }else{
                $count--;
            }
        }

        $userDetails = $sortedUserDetails;
        unset($sortedUserDetails);
        $userIds = array_keys($userDetails);

        $returnArray = array(
            'numrows' => $count,
            'result' => $userDetails,
            'userIds' => $userIds,
            'userIdMap'=>$userIdMap
        );

        unset($leads);
        unset($users);
        unset($userIds);
        unset($userDetails);

        return $returnArray;
    }

    function getContactCountOfClient($clientId, $streamId,$subStreamId,$contactType, $flagType){
       
        $this->load->model('ldbmodel');
        $count = $this->ldbmodel->getContactCountForClient($clientId, $streamId,$subStreamId,$contactType, $flagType);

        return $count;
    }

    function getContactedUsers($clientId, $streamId,$subStreamId,$contactType,$resultOffset,$numberOfResults, $flagType){
       
        $this->load->model('ldbmodel');
        $user = $this->ldbmodel->getContactedUsers($clientId, $streamId,$subStreamId,$contactType,$resultOffset,$numberOfResults, $flagType);

        foreach ($user as $userId) {
            $userIdArray['userIds'][] = $userId['userId'];
            $userIdArray['userIdMap'][$userId['userId']] = $userId['FlagType'];
        }

        $userIdArray['userIds'] = array_unique($userIdArray['userIds']);
        return $userIdArray;
    }

    
    function searchLDBSolr($inputArray,$resultOffset,$numResults,$clientUserId,$isMMM=FALSE){
        $start = microtime(true);
        
        $this->load->library('LDB/searcher/LeadSearcher');
        $leadSearcher = new LeadSearcher;

        if(!$isMMM) {
            $inputArray['resultOffset'] = $resultOffset;
            $inputArray['numResults'] =  $numResults;
        }
        
        $inputArray['clientUserId'] = $clientUserId;

        if($isMMM) {
            $leads = $leadSearcher->search($inputArray,$isMMM);
        }else{
            
            $inputArray['isFTExclusion'] = $this->checkFTExclusionFlag($inputArray);
            $inputArray['isMRCourseExclusion'] = $this->checkMRCourseExclusionFlag($inputArray);

            $leadSearchResult = $leadSearcher->search($inputArray,$isMMM);
            $leads = $leadSearchResult['leadData'];
        }    

        if($inputArray['countFlag']){
            $totalResults = $leads;
        } else{
            $totalResults = count($leads);
        }
        
        if($totalResults == 0 && !$inputArray['countFlag']) {
            return array('error' => 'No Results Found For Your Query');
        }
       
        $end = microtime(true);

        $makeResultStart = microtime(TRUE); 

        if($isMMM) {
            $userIds = array_keys($leads);

            $return = array(
                'requestTime' => date("Y-m-d h:m:s",$start),
                'totalRows' => $totalResults,
                'userIds' => $leads
            );
        } else {

            if(!$inputArray['Viewed'] && !$inputArray['Emailed'] && !$inputArray['Smsed']){
                $totalResults = $leadSearchResult['numFound'];
            }

            $userIds = array_keys($leads);

            //handle for study abroad - for details
            $userDetails = $this->getUserDetails($userIds,$clientUserId,$leads);

            Global $managementStreamMR;
            Global $engineeringtStreamMR;

            if($inputArray['stream'] == $managementStreamMR || $inputArray['stream'] == $engineeringtStreamMR){
                $userDetails = $this->filterMrProfiles($inputArray,$userDetails);


                //$userDetails = $this->filterUserFullTimeMode($userDetails);
            }

            $userIdMap = $this->getUserContactMap($clientUserId, $inputArray['stream'],$inputArray['subStreamId'],'view',$userIds);

            $return = array(
                'requestTime' => ($end-$start),
                'numrows' => $totalResults,
                'result' => $userDetails,
                'userIds' => $userIds,
                'userIdMap' => $userIdMap
            );
        }

        $makeResultEnd = microtime(TRUE);       
        error_log("SolrSearch:: MakeResultTime:: ".($makeResultEnd-$makeResultStart));
        
        return $return;
    }

    public function getUserDetails($userIds,$clientId,$leadData)
    {   
        /**
         * Get latest view counts for each user
         */

        $this->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new \ListingBaseBuilder();
        $HierarchyRepository = $listingBase->getHierarchyRepository();
        $BaseCourseRepository = $listingBase->getBaseCourseRepository();
        $this->load->library('listingBase/BaseAttributeLibrary');
        $baseAttributeLibrary = new BaseAttributeLibrary();

        if(!empty($userIds)) {
            $this->load->model('LDB/leadsearchmodel');
            $this->leadSearchModel = new LeadSearchModel;

            $LDBViewCountArray = array();
            $LDBViewCountArray = $this->leadSearchModel->getLeadViewCountArray($userIds);
            $LeadContactedData = $this->leadSearchModel->getLeadContactedData($userIds,$clientId);

            foreach ($leadData as $userId => $data) {
                
                $userData = json_decode($data['displayData'],TRUE);

                $subStreamID = $data['subStreamId'];

                if(!$subStreamID){
                    $subStreamID = 0;
                }

                $ViewCount = $LDBViewCountArray[$userId][$data['streamId']][$subStreamID]['ViewCount'];
                if(!$ViewCount){
                    $ViewCount =0;
                }

                $userData['ViewCountArray'] = $LDBViewCountArray[$userId];
                $userData['ViewCountArray']['ViewCount'] = $ViewCount;

                $userData['ContactData'] = $LeadContactedData[$userId]['ContactType'];
                $userData['CreditConsumed'] = $LeadContactedData[$userId]['CreditConsumed'];

                foreach ($data as $dataKey => $dataValue) {
                    $userData[$dataKey] = $dataValue;
                }

                unset($userData['displayData']);

                $userDetails[$userId] = $userData;

                if($data['streamId'] != ''){
                    $streamObj = $HierarchyRepository->findStream($data['streamId']);
                    $streamData = $streamObj->getObjectAsArray();
                    $userDetails[$userId]['streamId'] = $data['streamId'];
                    $userDetails[$userId]['streamData'] = $streamData['name'];
                }

                if($data['subStreamId'] != ''){
                    $substreamIdsArray = array();
                    $subStreamData = array();
                    $substreamIdsArray = explode(',', $data['subStreamId']);
                    $subStreamObj = $HierarchyRepository->findMultipleSubstreams($substreamIdsArray);
                    foreach ($subStreamObj as $key => $value) {
                        $value = $value->getObjectAsArray();
                        $subStreamData[] = $value['name'];
                    }
                    asort($subStreamData);
                    $userDetails[$userId]['subStreamData'] = implode(', ', $subStreamData);
                }

                if($data['specialization'] != ''){
                    $specializationIdsArray = array();
                    $specializationData = array();
                    $specializationIdsArray = explode(',', $data['specialization']);
                    $specializationObj = $HierarchyRepository->findMultipleSpecializations($specializationIdsArray);
                    foreach ($specializationObj as $key => $value) {
                        $value = $value->getObjectAsArray();
                        $specializationData[] = $value['name'];
                    }
                    asort($specializationData);
                    $userDetails[$userId]['specializationData'] = implode(', ', $specializationData);
                }

                if($data['shikshaCourse'] != ''){
                    if($data['shikshaCourse'] == '0'){
                        $userDetails[$userId]['courseData'] = 'Not Available';
                    } else {
                        $coursesArray = array();
                        $courseData = array();
                        $coursesArray = explode(',', $data['shikshaCourse']);
                        $coursesObj = $BaseCourseRepository->findMultiple($coursesArray);
                        foreach ($coursesObj as $key => $value) {
                            $value = $value->getObjectAsArray();      
                            $courseData[] = $value['name']; 
                        }
                        asort($courseData);
                        $userDetails[$userId]['courseData'] = implode(', ', $courseData);
                    }
                }

                if($data['mode'] != ''){
                    $parentChildrenMapping = array();
                    $modeIdsArray = explode(',', $data['mode']);
                    $attributeParentValueIdMapping = $baseAttributeLibrary->getParentValueIdByValueId($modeIdsArray);
                    
                    foreach ($attributeParentValueIdMapping as $key => $parentValues) {
                        foreach ($parentValues as $parentValue) {
                            if($parentValue == 20 && $key == 33) {
                                $parentChildrenMapping[$parentValue][] = '';
                            } else if($parentValue == 21 && !in_array($key,array(33,34,35,36,37,39))) {
                                $parentChildrenMapping[$parentValue][] = '';
                            } else {
                                if(!in_array($parentValue, $modeIdsArray)){
                                    $modeIdsArray[] = $parentValue;
                                }
                                $parentChildrenMapping[$parentValue][] = $key;
                            }
                        }
                    }
                    
					$attributesValueNamePair = $baseAttributeLibrary->getValueNameByValueId($modeIdsArray);
                    foreach ($attributesValueNamePair as $key => $value) {
                        if(array_key_exists($key, $parentChildrenMapping)){
                            $modeData[$value] = '';
                            foreach ($parentChildrenMapping[$key] as $childKey => $childId) {
                                if($childId){
                                    $childArray[] = $attributesValueNamePair[$childId];
                                }
                            }
                            asort($childArray);
                            $modeData[$value] = implode(', ',$childArray);
                        }
                        else if(array_key_exists($key, $attributeParentValueIdMapping)){
                            continue;
                        }
                        else{
                            $modeData[$value] = '';
                        }
                    }
                    $userDetails[$userId]['modeData'] = $modeData;
                    unset($childArray);
                    unset($modeIdsArray);
                    unset($modeData);
                    unset($parentChildrenMapping);
                    unset($attributeParentValueIdMapping);
                    unset($attributesValueNamePair);
                }

                $userDetails[$userId]['ViewCredit'] = $data['ViewCredit'];
                $userDetails[$userId]['SmsCredit'] = $data['SmsCredit'];
                $userDetails[$userId]['EmailCredit'] = $data['EmailCredit'];
                
            }
            
            return $userDetails;
        }
    }

    function convertDateFormat($timeFilterVar){
        $var = $timeFilterVar['from'];
        $date = str_replace('/', '-', $var);
        $timeFilterVar['from'] = date('Y-m-d', strtotime($date));

        $var = $timeFilterVar['to'];
        $date = str_replace('/', '-', $var);
        $timeFilterVar['to'] = date('Y-m-d', strtotime($date));

        return $timeFilterVar;

    }


    function searchResults() {
        ini_set('memory_limit', '512M');
        $Validity = $this->checkUserValidation();
        $ClientId = $Validity[0]['userid'];

        $postInputData = $this->input->post('inputData');

        if (isset($postInputData)) {
            $inputArray = json_decode(base64_decode($postInputData), true);
        } else {
            $inputArray = array();
        }

        $postDisplayData = $this->input->post('displayData');
        if (isset($postDisplayData)) {
            $displayArray = json_decode(base64_decode($postDisplayData), true);
        } else {
            $displayArray = array();
        }
        $inputArray['underViewedLimitFlagSet'] = false;
            
        $ldb_underLimit_flag = $this->input->post('ldb_underLimit_flag');        
        if($ldb_underLimit_flag ){
            $inputArray['underViewedLimitFlagSet'] = true;      
        }
        

        $startOffSetSearch = $this->input->post('startOffSetSearch');  
        $start = isset($startOffSetSearch) ? $startOffSetSearch : 0;
        $rows = $this->numberOfResults;

        $filterOverride =  $this->input->post('filterOverride'); 
        if (isset($filterOverride)) {
            $inputArray['DateFilter'] = $filterOverride;
            $displayArray['DateFilter'] = $filterOverride;
        }

        $requestTime =  $this->input->post('requestTime'); 
        if (isset($requestTime) && $requestTime != "") {
            $inputArray['requestTime'] = $requestTime;
        }

        $ldb_unviewed_flag =  $this->input->post('ldb_unviewed_flag'); 
        $ldb_viewed_flag =  $this->input->post('ldb_viewed_flag'); 
        $ldb_emailed_flag =  $this->input->post('ldb_emailed_flag'); 
        $ldb_smsed_flag =  $this->input->post('ldb_smsed_flag'); 

        if (isset($ldb_unviewed_flag) && (!empty($ldb_unviewed_flag)) && ($ldb_unviewed_flag == '1')) {
            $inputArray['DontShowViewed'] = '1';
            unset($inputArray['Viewed']);
            unset($inputArray['DontShowEmailed']);
            unset($inputArray['DontShowSmsed']);
            unset($inputArray['Emailed']);
            unset($inputArray['Smsed']);
        } else if (isset($ldb_viewed_flag) && (!empty($ldb_viewed_flag)) && ($ldb_viewed_flag == '1')) {
            $inputArray['Viewed'] = $ClientId;
            unset($inputArray['DontShowViewed']);
            unset($inputArray['DontShowEmailed']);
            unset($inputArray['DontShowSmsed']);
            unset($inputArray['Emailed']);
            unset($inputArray['Smsed']);
        } else if (isset($ldb_emailed_flag) && (!empty($ldb_emailed_flag)) && ($ldb_emailed_flag == '1')) {
            $inputArray['Emailed'] = $ClientId;
            unset($inputArray['DontShowViewed']);
            unset($inputArray['DontShowEmailed']);
            unset($inputArray['DontShowSmsed']);
            unset($inputArray['Viewed']);
            unset($inputArray['Smsed']);
        } else if (isset($ldb_smsed_flag) && (!empty($ldb_smsed_flag)) && ($ldb_smsed_flag == '1')) {
            $inputArray['Smsed'] = $ClientId;
            unset($inputArray['DontShowViewed']);
            unset($inputArray['DontShowEmailed']);
            unset($inputArray['DontShowSmsed']);
            unset($inputArray['Viewed']);
            unset($inputArray['Emailed']);
        } else {
            unset($inputArray['Smsed']);
            unset($inputArray['DontShowViewed']);
            unset($inputArray['DontShowEmailed']);
            unset($inputArray['DontShowSmsed']);
            unset($inputArray['Viewed']);
            unset($inputArray['Emailed']);
        }

        $inputDataMR =  $this->input->post('inputDataMR'); 
        if(!empty($inputDataMR)) {
            $dataArrayMR = json_decode(base64_decode($_POST['inputDataMR']), true);
            $this->displayResultsMR($inputArray, $displayArray, $start, $rows, $dataArrayMR);
        } else {
            $this->displayResults($inputArray, $displayArray, $start, $rows, $search_key);
        }
    }


    public function getHigherPricedProfile($streamId,$courseIdArray = array(),$modeArray = array(),$cache=false){
        
        $profile = array('stream' =>$streamId,'courseId'=>$courseId,'mode'=>$mode);
        $firstLoopFlag = true;

        global $managementStreamMR;
        global $engineeringtStreamMR;
        global $mbaBaseCourse;
        global $btechBaseCourse;
        global $fullTimeEdType;        

        foreach ($courseIdArray as $courseId) {
            foreach ($modeArray as $mode) {
                
                if( ($streamId == $managementStreamMR || $streamId == $engineeringtStreamMR) && ($courseId == $mbaBaseCourse || $courseId == $btechBaseCourse )  &&  ($mode == $fullTimeEdType) ){
                    continue;
                }

                if($cache){     //added for migration

                    $this->predisLibrary = PredisLibrary::getInstance();
                    $data = $this->predisLibrary->getAllMembersOfHashWithValue('cachedPricingData');
                    $credit = unserialize($data['pricingData']);
                    $credit = $credit[$streamId][$courseId][$mode];
                }else{
                    $credit = $this->getCreditToBeConsumed($streamId,$courseId,$mode);
                }

                if($firstLoopFlag && count($credit)>0){
                    $creditToDeduct = $credit;
                    $firstLoopFlag = false;
                }

                if($credit['ViewCredit'] <= $creditToDeduct['ViewCredit']){
                    $creditToDeduct = $credit;
                    $profile = array('stream' =>$streamId,'courseId'=>$courseId,'mode'=>$mode);
                }
            }
        }

        if(empty($creditToDeduct) || count($creditToDeduct) == 0){
            $creditToDeduct = $this->defaultCreditCriteria;
        }

        $returnArray = array('creditToDeduct'=> $creditToDeduct,'profile'=>$profile);

        return $returnArray;

    }

    public function getCreditToBeConsumed($streamId,$courseId,$mode){

        $defaultCredit = $this->defaultCreditCriteria;
        
        $this->load->model('ldbmodel');
        $credit = $this->ldbmodel->getCreditToBeConsumed($streamId,$courseId,$mode);
        
        if(empty($credit) || $credit =='' || !isset($credit)){
            $credit = $defaultCredit;
        }
        
        return $credit;
    }

    function getCreditConsumedForMultipleUsers() {
        
        $userIdCSV = $this->input->post('userlist');
        $creditConsumeArray = array();

        if(empty($userIdCSV) || $userIdCSV == ''){
            echo json_encode($creditConsumeArray);
            return;
        }

        $this->init();
        $objLDBClient = new LDB_Client();
        $userIDArray = explode(",", $userIdCSV);
        
        foreach($userIDArray as $userid){
            $creditConsumeArray[$userid]['view'][0]['CreditConsumed'] = null;
        }
        $Validity = $this->checkUserValidation();
        $clientId = $Validity[0]['userid'];
        $retArray = $objLDBClient->sgetCreditConsumedForAction($clientId, $userIdCSV, 'view');
        foreach ($retArray as $userIdArr) {
            $creditConsumeArray[$userIdArr['userid']]['view'][0]['CreditConsumed'] = $userIdArr['CreditConsumed'];
        }
        echo json_encode($creditConsumeArray);

    }

    function showContactDetails() {
        $this->init();
        $this->load->helper("string_helper");
        $search_key = random_string("alnum", 32).time();
        storeTempUserData('search_key',$search_key);

        $userIdToView = $this->input->post('userId');
        $creditToBeConsumed[$userIdToView]['view'] = $this->input->post('viewCredit');
        $creditToBeConsumed[$userIdToView]['email'] = $this->input->post('emailCredit');
        $creditToBeConsumed[$userIdToView]['sms'] = $this->input->post('smsCredit');

        $postDisplayData = $this->input->post('displayData');
        if (isset($postDisplayData)) {
            $displayArray = json_decode(base64_decode($postDisplayData), true);
        } else {
            $displayArray = array();
        }

        $ExtraFlag = 'false';
        $actual_course_id = '';

        if(isset($displayArray['DesiredCourse']) && $displayArray['DesiredCourse'] != '' && $displayArray['stream'] == ''){
            require APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
            $displayArray['stream'] = $coursesList[$displayArray['DesiredCourse']]['stream_id'];
            $actual_course_id = $coursesList[$displayArray['DesiredCourse']]['actual_course_id'];
        }

        $ldbObj = new LDB_client();
        $viewAbleList = $ldbObj->getViewableUsers(12, array('0' => $userIdToView), $this->userStatus[0]['userid'], $ExtraFlag, TRUE, $actual_course_id);
        if(count($viewAbleList) == 0) {
            $responseUserDetails = array('result' => 'noview');
        }
        else {
            $responseUserDetails = $this->consumeLDBSubscription($this->userStatus[0]['userid'], array('0' => $userIdToView), 'view', $ExtraFlag, $actual_course_id, $creditToBeConsumed, $displayArray);
        }
        echo json_encode($responseUserDetails);
    }

    function consumeLDBSubscription($clientId, $userIds, $contactType, $ExtraFlag='false', $actual_course_id = '', $creditToBeConsumed = array(), $displayArray = array()) {
        
        global $userGlobalViewLimit;
        global $MRPricingArray;
        
        $userDataArray = array();
        $nonViewableList = array();
        $newUserDataArray = array();
        $viewableUsers = array();
        if(!empty($displayArray)) {
            $userDataArray = Modules::run('MIS/SADownloadleads/getLeadDataFromSolr', $userIds, $displayArray, TRUE);
        }
        
		foreach ($userDataArray as $userData) {

            if(isset($displayArray['DesiredCourse']) && $displayArray['DesiredCourse'] != ''){
                switch ($contactType) {
                    case 'view':
                        $creditToBeConsumed[$userData['userid']]['view'] = $MRPricingArray[$userData['StreamId']]['view'] ;
                        break;
                    
                    case 'email':
                        $creditToBeConsumed[$userData['userid']]['email'] = $MRPricingArray[$userData['StreamId']]['email'] ;
                        break;

                    case 'sms':
                        $creditToBeConsumed[$userData['userid']]['sms'] = $MRPricingArray[$userData['StreamId']]['SMS'] ;
                        break;
                }
            }
            else {
                switch ($contactType) {
                    case 'view':
                        $creditToBeConsumed[$userData['userid']]['view'] = $userData['View Credit'];
                        break;
                    
                    case 'email':
                        $creditToBeConsumed[$userData['userid']]['email'] = $userData['Email Credit'];
                        break;

                    case 'sms':
                        $creditToBeConsumed[$userData['userid']]['sms'] = $userData['Sms Credit'];
                        break;
                }
            }

            $userViewData = Modules::run('searchAgents/searchAgents_Server/getUserViewCount', $userData['userid']);
            if(!$userData['SubStreamId']){
                $substreamId = 0;
            }
            else {
                $substreamId = $userData['SubStreamId'];
            }
            $count = ($userViewData[$userData['StreamId']][$substreamId]) ? ($userViewData[$userData['StreamId']][$substreamId]) : 0;
            
            if(!empty($actual_course_id) && (($userViewData['totalViewCount'] >= $userGlobalViewLimit) || ($count >= $userData['View Count']))){
                // return array('result' => 'noview');
                $nonViewableList[] = $userData['userid'];
            }
            else {
                $newUserDataArray[$userData['userid']][] = $userData;
            }

        }

        $viewableUsers = array_diff($userIds, $nonViewableList);

        $subscriptionList = $this->getCreditDetails($clientId, $viewableUsers, $contactType, $ExtraFlag, $actual_course_id, $creditToBeConsumed);
		
		if($actual_course_id == 2 || $actual_course_id == 52) {
            $flagType = 'LDB_MR';
        }
        else {
            $flagType = 'LDB';
        }

        if (count($subscriptionList['subArray']) != 0) {
            $returnArray = array();
            $this->load->library(array('subscription_client', 'LDB_Client'));
            $subsObj = new Subscription_client();
            $ldbObj = new LDB_Client();
            $start = 0;
            foreach ($subscriptionList['subArray'] as $subscription) {

                $returnval = $subsObj->consumeLDBCredits(12, $subscription['subscriptionId'], $subscription['creditToConsume'], $clientId, $clientId);

                foreach ($subscription['userList'] as $userId => $creditdeduct) {
                    
                    $returnval = $ldbObj->sUpdateContactViewed(12, $clientId, $userId, $contactType, $subscription['subscriptionId'], $creditdeduct, $flagType, '', $newUserDataArray[$userId]);
                    $returnArrayElement = json_decode($returnval, true);
                    $returnArray[] = $returnArrayElement;
                    $returnval = $subsObj->updateSubscriptionLog(12, $subscription['subscriptionId'], $creditdeduct, $clientId, $clientId, $subscription['BaseProductId'], $returnArrayElement[0]['insertId'], $contactType, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));

                }

            }
            return array('result' => $returnArray, 'CreditsForAction' => $subscriptionList['CreditsForAction'], 'CreditCount' => ($subscriptionList['CreditCount'] - $subscriptionList['CreditsForAction']));
        } else if(empty($viewableUsers) && $contactType == 'view') {
            return array('result' => 'noview');
        } else {
            return array('result' => "You don't have sufficient credit to perform the given action");
        }
    }

    function getCreditDetails($clientId, $userIdList, $action, $ExtraFlag='false', $actual_course_id = '', $creditToBeConsumed = array()) {
        
        $this->load->library('sums_product_client');
        $objSumsProduct = new Sums_Product_client();
        $SubscriptionArray = $objSumsProduct->getAllSubscriptionsForUserLDB(1, array('userId' => $clientId));
        
        $objLDBClient = new LDB_Client();
        $creditConsumeArray1 = $objLDBClient->sgetCreditToConsume($appID, $userIdList, $action, $clientId, $ExtraFlag,TRUE, $actual_course_id, $creditToBeConsumed);
        
    
        $countNdnc = 0;
        $countToConsume = 0;
        $creditConsumeArray = array();
        foreach($creditConsumeArray1 as $key => $creditConsumex){
            $countNdnc += (array_key_exists("countNdnc", $creditConsumex)&&isset($creditConsumex['countNdnc'])&&!empty($creditConsumex['countNdnc']))?$creditConsumex['countNdnc']:0;
            if($creditConsumex['countNdnc'] && $action == 'sms'){
                continue;
            } else {
                $countToConsume += $creditConsumex[$key];
                $creditConsumeArray[$key] = $creditConsumex[$key];
            }
        }

        
        $creditCount = 0;
        $subscriptionDetailMapping = array();
        foreach ($creditConsumeArray as $userId => $userCreditdeduct) {
            foreach ($SubscriptionArray as $subscription) {
                if ($subscription['BaseProdCategory'] == 'Lead-Search') {
                    
                    if(!empty($subscriptionDetailMapping[$subscription['SubscriptionId']]['countConsumed'])) {
                            $countLeft = $subscription['BaseProdRemainingQuantity'] - $subscriptionDetailMapping[$subscription['SubscriptionId']]['countConsumed'];
                    } else {
                            $countLeft = $subscription['BaseProdRemainingQuantity'];
                    }                  
                    
                    if ($userCreditdeduct <= $countLeft) {
                        $subscriptionDetailMapping[$subscription['SubscriptionId']]['BaseProdRemainingQuantity'] = $subscription['BaseProdRemainingQuantity'];
                        $subscriptionDetailMapping[$subscription['SubscriptionId']]['BaseProductId'] = $subscription['BaseProductId'];
                        $subscriptionDetailMapping[$subscription['SubscriptionId']]['countLeft'] = $countLeft;
                        $subscriptionDetailMapping[$subscription['SubscriptionId']]['countConsumed'] += $userCreditdeduct;
                        $subscriptionDetailMapping[$subscription['SubscriptionId']]['userList'][$userId] = $userCreditdeduct;
                        break;
                    }
                    
                }
            }
        }
        $sumConsumed = 0;
        $sumTotal = 0;
        foreach ($subscriptionDetailMapping as $subscriptionId => $details) {
            $subscriptionConsumptionArray[] = array('subscriptionId' => $subscriptionId, 'creditToConsume' => $details['countConsumed'], 'BaseProductId' => $details['BaseProductId'], 'userList' => $details['userList']);
            $sumConsumed+=$details['countConsumed'];
        }
        foreach ($SubscriptionArray as $subscription) {
            if ($subscription['BaseProdCategory'] == 'Lead-Search'){
                $sumTotal+=$subscription['BaseProdRemainingQuantity'];
            }
        }
        foreach ($subscriptionDetailMapping as $subscriptionId => $details) {
            $subid = $subscriptionId;
        }
        if ($countToConsume > $sumTotal) {
            $resultText = "You don't have sufficient Credits for this action";
             // if(count($subscriptionDetailMapping) == 0) {
             //    $sumTotal = 0;
             // }
            return array('result' => $resultText, 'subArray' => array(), 'CreditCount' => $sumTotal, 'CreditsForAction' => $countToConsume, 'countNdnc' => $countNdnc);
        } else {
            $resultText = "You have $sumTotal Credits. $sumConsumed credits will be used for this action.";
            return array('subid' => $subid, 'result' => $resultText, 'subArray' => $subscriptionConsumptionArray, 'CreditCount' => $sumTotal, 'CreditsForAction' => $sumConsumed, 'countNdnc' => $countNdnc);
        }
    }

    public function formSubmitMR() {

        $postArray = array();
        $displayArray = array();
        $dataArrayMR = array();
        
        //matched response search ldb course
        $displayArray['DesiredCourse'] = $this->input->post('desiredCourse',true);
        $postArray['actual_course_name'] = $this->input->post('actual_course_name',true);
        
        //get course ids for matching
        $courseIds = array();
        $courseNames = array();
        $displayArray['type'] = "response";
        $displayArray['matchedCourses'] = '';

        $courses = $this->input->post('course_id',true);

        if (isset($courses) && !empty($courses)) {
            foreach($courses as $course) {
                $courseData = explode('|',$course);
                if($course > 0) {
                    $courseIds[] = $courseData[0];
                    $courseNames[$courseData[0]] = $courseData[1];
                    $instituteNames[$courseData[0]] = $courseData[2];
                }
            }

            $dataArrayMR['courses'] = $courseIds;
            $displayArray['matchedCourses'] = $courseNames;
            $displayArray['matchedCoursesInstitute'] = $instituteNames;

        }
        
        $totalCurrLocation = $this->input->post('totalCities',true);
        $curLocArr = $this->input->post('CurLocArr',true);

        if(isset($totalCurrLocation)){
            $allCurrLocArray = count($curLocArr);
        }
         
        if(isset($totalCurrLocation) && $allCurrLocArray < $totalCurrLocation ){
            $ALLcoursesSelected = 1;
        }
        else{
            $ALLcoursesSelected = 0;
        }
        
        //get current locations
        if (isset($curLocArr) && !empty($curLocArr) && ($ALLcoursesSelected)){
            $this->load->builder('LocationBuilder','location');
            $locationBuilder = new \LocationBuilder;
            $locationRepository = $locationBuilder->getLocationRepository();
            
            $cityObjs = $locationRepository->findMultipleCities($curLocArr);
            foreach($cityObjs as $cityId => $cityObj) {
                if($cityObj->isVirtualCity()) {
                    $citiesByVitualCity = $locationRepository->getCitiesByVirtualCity($cityId);
                    foreach($citiesByVitualCity as $city) {
                        $curLocArr[] = $city->getId();
                    }
                }
                $hiddenCurrentCity[] = $cityObj->getName();
            }
        
            unset($locationRepository);
            unset($cityObjs);
            
            $postArray['CurrentLocation'] = array_unique($curLocArr);
            // $hiddenCurrentCity = $this->input->post('hiddenCurrentCity',true);
            if (isset($hiddenCurrentCity)) {
                $displayArray['currentLocation'] = implode(', ', $hiddenCurrentCity);
            }
        }

        $totalMRLocation = $this->input->post('MRTotalCities',true);
        $MRLocArr = $this->input->post('MRLocArr',true);

        if(isset($totalMRLocation)){
            $allMRLocArray = count($MRLocArr);
        }
         
        if(isset($totalMRLocation) && $allMRLocArray < $totalMRLocation ){
            $ALLMRcoursesSelected = 1;
        }
        else{
            $ALLMRcoursesSelected = 0;
        }
        
        //get mr preferred locations
        if (isset($MRLocArr) && !empty($MRLocArr) && ($ALLMRcoursesSelected)){
            $this->load->builder('LocationBuilder','location');
            $locationBuilder = new \LocationBuilder;
            $locationRepository = $locationBuilder->getLocationRepository();
            
            $cityObjs = $locationRepository->findMultipleCities($MRLocArr);
            foreach($cityObjs as $cityId => $cityObj) {
                //commented for bug fix- only parent id will go in search and not child
                /*if($cityObj->isVirtualCity()) {
                    $citiesByVitualCity = $locationRepository->getCitiesByVirtualCity($cityId);
                    foreach($citiesByVitualCity as $city) {
                        $MRLocArr[] = $city->getId();
                    }
                }*/
                $hiddenMRCity[] = $cityObj->getName();
            }
        
            unset($locationRepository);
            unset($cityObjs);
            
            $postArray['MRLocation'] = array_unique($MRLocArr);
            // $hiddenMRCity = $this->input->post('hiddenMRCity',true);
            if (isset($hiddenMRCity)) {
                $displayArray['MRLocation'] = implode(', ', $hiddenMRCity);
            }
        }

        //get exams
        $exams = $this->input->post('exams',true);
        if(is_array($exams) && count($exams) > 0) {
            $displayArray['exams'] = '';
            $examDisplayArray = array();
            
            foreach($exams as $examId) {
                $examDisplay = $this->input->post($examId.'_displayname',true);
                $examDisplayArray[] = $examDisplay;
            }
            
            $displayArray['exams'] = $examDisplayArray;
            $postArray['exams'] = $examDisplayArray;
        }
        
        //get time filter
        $startDate = null;
        $endDate = null;
        $timeFilterVar = $this->input->post('timefilter',true);
        $timeFilterVar = $this->convertDateFormat($timeFilterVar);

        if (isset($timeFilterVar['from']) && isset($timeFilterVar['to']) ) {
            $startDate = $timeFilterVar['from'];
            $endDate = $timeFilterVar['to'];
            $endTime = '';
            $date = date('Y-m-d');
            
            $displayArray['DateFilterFrom'] = $timeFilterVar['from'];
            
            if($startDate > $endDate) {
                return;
            }
            else if($endDate >= $date) {
                $endDate = $date;
                $displayArray['DateFilterTo'] = date('Y-m-d H:i:s', strtotime('-30 min'));
            }
            else {
                $displayArray['DateFilterTo'] = $endDate.' 23:59:59';
            }
            
            $dataArrayMR['startDate'] = $startDate;
            // $dataArrayMR['endDate'] = $endDate;
            $dataArrayMR['endDate'] = $displayArray['DateFilterTo'];
        }
        
        $postArray['DontShowViewed'] = '1';
        $postArray['resultOffset'] =0;
        $postArray['numResults'] =100;
        $postArray['streamId'] = $this->input->post('streamId',true);
  		
		$postArray['stream'] = $postArray['streamId'];
        $displayArray['stream'] = $postArray['streamId'];
	
		$this->displayResultsMR($postArray, $displayArray, 0, 50, $dataArrayMR);
    }
    
    function displayResultsMR($inputArray, $displayArray, $start, $rows, $dataArrayMR) {
        
        $this->init();
        $data['validateuser'] = $this->userStatus;
        $Validity = $this->checkUserValidation();
        $ClientId = $Validity[0]['userid'];
        $data['ClientId'] = $ClientId;
        $data['headerTabs'] = $this->userStatus[0]['headerTabs'];
        $data['searchTabs'] = $this->searchTabs;
        $data['prodId'] = 31;
        $data['course_name'] = $inputArray['tab_course_name'];
        $data['actual_course_name'] = $inputArray['actual_course_name'];
        $data['DontShowViewed'] = $inputArray['DontShowViewed'];
        $data['flag_manage_page'] = $inputArray['flag_manage_page'];
        $data['displayArray'] = $displayArray;
        $data['inputData'] = base64_encode(json_encode(array_merge(array('course_id' => $dataArrayMR['courses']), $inputArray)));
        $data['displayData'] = base64_encode(json_encode($displayArray));
        $data['inputDataMR'] = base64_encode(json_encode($dataArrayMR));
        $data['resultResponse'] = array();
        $data['responses'] = array();

        //to exclude credit deduction, when MR is itself a response for client
        $this->load->model('ldbmodel');
     /*   $responses = $this->ldbmodel->getResponsesForClient($ClientId);
        $data['responses'] = $responses; 
        unset($responses);*/

        if($inputArray['DontShowViewed']){
            $data['tabFlag'] = 'DontShowViewed';
        }

        if($inputArray['Viewed'] || $inputArray['Emailed'] || $inputArray['Smsed']) {

            $inputArray['flagType'] = array('LDB_MR','SA_MR');
            $inputArray['courses'] = $dataArrayMR['courses'];
            $this->loadMRActionTypeResults($ClientId, $inputArray, $displayArray, $data, $start, $rows);
            return;

        } 
        
        if(count($dataArrayMR['courses']) && !empty($dataArrayMR['startDate']) && !empty($dataArrayMR['endDate'])) {        
            $responseData = modules::run('lms/lmsServer/getMatchedResponses', $dataArrayMR['courses'], array(), $dataArrayMR['startDate'], $dataArrayMR['endDate'], FALSE);
            //$responseUsers = $responseData['users'];
            $matchedCourses = array_keys($responseData['courses']);
            
            if(count($matchedCourses)) {        
                $this->load->model('ldbmodel');
                
                $inputArray['responseSubmitDateStart'] = $dataArrayMR['startDate'].' 00:00:00';
                // $inputArray['responseSubmitDateEnd'] = $dataArrayMR['endDate'].' 23:59:59';
                $inputArray['responseSubmitDateEnd'] = $dataArrayMR['endDate'];
                $inputArray['matchedCourses'] = $matchedCourses;
                $inputArray['resultOffset'] = $start;

                $searchResult = $this->searchMRSolr($inputArray, $this->userStatus[0]['userid']);

                $userIds =  array_keys($searchResult['leadData']);
                $totalRows = $searchResult['numFound'];
                
                $responses = $this->ldbmodel->getResponsesForClient($ClientId, $userIds);
                $data['responses'] = $responses;
                unset($responses);


                if (count($userIds) == 0) {
                    $responseArray = array('error' => 'No Results Found For Your Query');
                }
                else {
                     /*$userToBeExcluded = $this->ldbmodel->getExcludedList();

                     foreach ($userIds as $userId) {
                         if(!in_array($userId, $userToBeExcluded)){
                             $excludedUserId[]=$userId;
                         }
                     }

                     $userIds = $excludedUserId;

                     unset($excludedUserId);
                     unset($userToBeExcluded);*/

                    /*$finalUsers = array();
                    
                    foreach($userIds as $index => $userId) {
                        unset($userIds[$index]);
                        $userIds[$responseUsers[$userId]['responseId']] = $userId;
                    }*/
                    
                    /*krsort($userIds);
                    $userIds = array_values($userIds)*/;
                    
                    //pagination code below - Old code
                    /*if (empty($rows)) {
                        $finalUsers = $userIds;
                    }
                    else {
                        for($i = $start; $i < $start + $rows && $i < count($userIds); $i++) {
                            $finalUsers[$i-$start] = $userIds[$i];
                        }
                    }*/
                    
                    $this->load->library('LDB/searcher/LeadSearcher');
                    $leadSearcher = new LeadSearcher;               
                    //$resultSet = $leadSearcher->getUserDetails($finalUsers, $this->userStatus[0]['userid']);

                    $resultSet = $this->getUserDetails($userIds, $this->userStatus[0]['userid'], $searchResult['leadData']);

                    $responseArray = array(
                        'numrows' => $totalRows,
                        'result' => $resultSet
                    );
                    
                    unset($resultSet);
                    $displayArray['matchedCourses']['dummyCourseId'] = "Course doesn't exist";
                    $data['matchedResponseData'] = array();

                    foreach($userIds as $userId) {

                        $skipDummyCourse = false;
                        $CreditConsumed = array();
                        $CreditConsumed = $responseArray['result'][$userId]['CreditConsumed']['view'];
                        rsort($CreditConsumed);
                        $responseArray['result'][$userId]['CreditConsumed'] = $CreditConsumed[0];
                        
                        $responseDetails = $searchResult['leadData'][$userId]['responseCourse'];
                        
                        foreach($responseDetails as $courseId) {
                            $clientCourseId = $responseData['courses'][$courseId][0];
                            if(empty($clientCourseId) && !$skipDummyCourse){
                                $clientCourseId = 'dummyCourseId';
                            }else{
                                $skipDummyCourse = true;
                            }

                            if($clientCourseId){

                                $responseTimeArray = explode('T', $searchResult['leadData'][$userId]['response_time_'.$courseId]);
                                $data['matchedResponseData'][$userId]['date'] = $responseTimeArray[0];
                                
                                unset($data['matchedResponseData'][$userId]['matchedCourses']);
                                $data['matchedResponseData'][$userId]['matchedCourses'][$courseId]['CourseName'] = $displayArray['matchedCourses'][$clientCourseId];
                                $data['matchedResponseData'][$userId]['matchedCourses'][$courseId]['InstituteName'] = $displayArray['matchedCoursesInstitute'][$clientCourseId];

                            }
                        }

                        if(!array_key_exists($userId, $data['matchedResponseData'])) {
                            
                         /*   mail('mansi.gupta@shiksha.com',"Saving data empty for userId ".$userId,"Data we got userId - \n ".print_r($responseArray['result'][$userId],true));
                            mail('mohd.alimkhan@shiksha.com',"Saving data empty for userId ".$userId,"Data we got userId - \n".print_r($responseArray['result'][$userId],true));*/
                            //unset($responseArray['result'][$userId]);
                            // $responseArray['numrows']--;
                        }
                    }
                }

                $data['resultResponse'] = $responseArray;
                $todayDate = date('Y-m-d');
                $data['clientAccess'] = true;
                $clientAccessData = $this->ldbmodel->getManualLDBAccessData($ClientId, $todayDate);
                if(empty($clientAccessData)) {
                    $data['clientAccess'] = false;
                }
            }
        }
        unset($userIds);
        unset($responseArray);
        unset($matchedCourses);
        unset($responseUsers);
         
        require APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
        $data['coursesList'] = $coursesList;
        $data['actual_course_id'] = $coursesList[$displayArray['DesiredCourse']]['actual_course_id'];
        
        $this->load->view("enterprise/searchResult", $data);

    }

    function loadMRActionTypeResults($clientId, $inputArray, $displayArray, $data, $start, $rows) {
        $responseData = modules::run('lms/lmsServer/getMatchedResponses', $inputArray['courses'], array(), '', '', FALSE);

        $matchedCourses = array_keys($responseData['courses']);

        $searchResult = $this->searchMR($inputArray, $clientId, $start, $rows);
        $inputArray['matchedCourses'] = $matchedCourses;
        
        if($searchResult['numFound'] == 0) {

            $responseArray = array('error' => 'No Results Found For Your Query');
            
        } 
        else {

            $userIds = array_keys($searchResult['leadData']);
            $userDetails = $this->getUserDetails($userIds, $clientId, $searchResult['leadData']);
            
            $responses = $this->ldbmodel->getResponsesForClient($clientId, $userIds);
            $data['responses'] = $responses;
            unset($responses);
                
			$sortedUserDetails = array();
            foreach ($userIds as  $userId) {
                $sortedUserDetails[$userId] = $userDetails[$userId];
            }

            $userDetails = $sortedUserDetails;
            
            unset($sortedUserDetails);

            $responseArray = array(
                'numrows' => $searchResult['contactCountOfClient'],
                'result' => $userDetails
            );
          
            unset($userDetails);

            //$responseData = modules::run('lms/lmsServer/getMatchedResponses', $inputArray['courses'], array(), '', '', FALSE);
            $displayArray['matchedCourses']['dummyCourseId'] = "Course doesn't exist";
            

            $data['matchedResponseData'] = array();                
            foreach($userIds as $userId) {

                $CreditConsumed = array();
                $CreditConsumed = $responseArray['result'][$userId]['CreditConsumed']['view'];
                rsort($CreditConsumed);
                $responseArray['result'][$userId]['CreditConsumed'] = $CreditConsumed[0];

                $responseDetails = $searchResult['leadData'][$userId]['responseCourse'];
                $skipDummyCourse = false;

                if(!empty($responseDetails)) {
                    foreach($responseDetails as $courseId) {
                        $clientCourseId = $responseData['courses'][$courseId][0];
                        if(empty($clientCourseId) && !$skipDummyCourse){
                            $clientCourseId = 'dummyCourseId';
                        }else{
                            $skipDummyCourse = true;
                        }

                        if($clientCourseId){
                            $responseTimeArray = explode('T', $searchResult['leadData'][$userId]['response_time_'.$courseId]);

                            $data['matchedResponseData'][$userId]['date'] = $responseTimeArray[0];
                        
                            unset($data['matchedResponseData'][$userId]['matchedCourses']);
                            $data['matchedResponseData'][$userId]['matchedCourses'][$courseId]['CourseName'] = $displayArray['matchedCourses'][$clientCourseId];
                            $data['matchedResponseData'][$userId]['matchedCourses'][$courseId]['InstituteName'] = $displayArray['matchedCoursesInstitute'][$clientCourseId];

                        }
                    }
                }

                if(!array_key_exists($userId, $data['matchedResponseData'])) {
                  /*  mail('mansi.gupta@shiksha.com',"Saving data empty for userId ".$userId,"Data we got userId - \n ".print_r($responseArray['result'][$userId],true));
                    mail('mohd.alimkhan@shiksha.com',"Saving data empty for userId ".$userId,"Data we got userId - \n".print_r($responseArray['result'][$userId],true));*/
                    //unset($responseArray['result'][$userId]);

                    //$responseArray['numrows']--;          //commenting to keep count same on pagination
                }

            }         

        }


        $data['flag_manage_page'] = $inputArray['flag_manage_page'];
        $data['resultResponse'] = $responseArray;

        unset($userIds);
        unset($responseArray);

        $todayDate = date('Y-m-d');
        $data['clientAccess'] = true;
        $clientAccessData = $this->ldbmodel->getManualLDBAccessData($clientId, $todayDate);
        if(empty($clientAccessData)) {
            $data['clientAccess'] = false;
        }
         
        require APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
        $data['coursesList'] = $coursesList;
        $data['actual_course_id'] = $coursesList[$displayArray['DesiredCourse']]['actual_course_id'];
        
        $this->load->view("enterprise/searchResult", $data);
    }
    
    function searchMRSolr($inputArray,$clientUserId){
        
        $this->load->library('LDB/searcher/LeadSearcher');
        $leadSearcher = new LeadSearcher;
        
        $inputArray['isMR'] = true;
        $inputArray['clientUserId'] = $clientUserId;
        $userIds = $leadSearcher->search($inputArray);
                
        return $userIds;
    }

    function searchMR($inputArray, $clientId, $start, $rows) {

        $streamId = $inputArray['streamId'];
        $subStreamId = $inputArray['subStream'];
        $searchCriteria = array();

        if($inputArray['Viewed'] ) {
            $contactType = 'view';
            $searchCriteria['tabFlag'] = 'Viewed';
        }    
            
        if($inputArray['Emailed']) {
            $contactType = 'email';
            $searchCriteria['tabFlag'] = 'Emailed';
        }    

        if($inputArray['Smsed']) {
            $contactType = 'sms';
            $searchCriteria['tabFlag'] = 'Emailed';
        }

        if(!empty($inputArray['flagType'])) {
            $flagType = $inputArray['flagType'];
        }

        $count = $this->getContactCountOfClient($clientId, $streamId, $subStreamId, $contactType, $flagType);

        if($count < 1) {

            $searchResult = array('numFound' => 0);
            
        } else {

            $userIdData = $this->getContactedUsers($clientId, $streamId, $subStreamId, $contactType, $start, $rows, $flagType);

            $userIds = $userIdData['userIds'];
            unset($userIdData);

            if(empty($userIds)) {

                $searchResult = array('numFound' => 0);

            } else {
                                
                $this->load->library('LDB/searcher/LeadSearcher');
                $leadSearcher = new LeadSearcher;
                
                $searchCriteria['userChunkFlag'] = true;
                $searchCriteria['userChunk'] = $userIds;
                $searchCriteria['stream'] = $streamId;
                $searchCriteria['isMR'] = true;
                $searchCriteria['isMRViewed'] = true;
                $searchCriteria['numResults'] = $inputArray['numResults'];
                $searchCriteria['resultOffset'] = $inputArray['resultOffset'];
                $searchCriteria['MRCourseSearch'] = true;
                $searchCriteria['matchedCourses'] = $inputArray['matchedCourses'];
                $searchCriteria['ProfileType'] = 'implicit';

                $searchResult = $leadSearcher->search($searchCriteria);

                foreach ($userIds as $userId) {
                    $leadData[$userId] = $searchResult['leadData'][$userId];
                }

                $searchResult['leadData'] = $leadData;
                unset($leadData);
                $searchResult['contactCountOfClient'] = $count;

            }

        }
        return $searchResult;

    }

    function filterUserFullTimeMode($userDetails){        

        foreach ($userDetails as $data) {
            if(count($data['modeData']) >1 && array_key_exists('Full Time', $data['modeData'])){
                unset($data['modeData']['Full Time']);
            }

            $modeData = $data['modeData'];

            $modeDataDisplay = '';
            foreach ($modeData as $key => $value) {
                $modeDataDisplay .= $key;
                if($value != '') 
                    $modeDataDisplay .= ' - '.$value;
                $modeDataDisplay .= ', ';
            }
           
            $modeDataDisplay = rtrim($modeDataDisplay, ", ");

            $data['Mode'] = $modeDataDisplay;
            $userData[$data['userId']] = $data;
        }
        
        return $userData;
    }

    function getUserContactMap ($clientUserId, $stream,$subStreamId,$contactType='view',$userIdArray){
        $this->load->model('ldbmodel');
        $user = $this->ldbmodel->getUserContactMap($clientUserId, $stream,$subStreamId,$contactType,$userIdArray);

        foreach ($user as $userId) {
            $userIdMap[$userId['userId']] = $userId['FlagType'];
        }

        return $userIdMap;
    }

    function filterMrProfiles($searchCritera,$userDetails){
        global $managementStreamMR;
        global $engineeringtStreamMR;
       

        if($searchCritera['stream'] == $managementStreamMR){
            $userData = $this->filterManagementStreamData($searchCritera,$userDetails);
        }

        if($searchCritera['stream'] == $engineeringtStreamMR){
            $userData = $this->filterEnngStreamData($searchCritera,$userDetails);
        }


        return  $userData;

    }


    function filterManagementStreamData($searchCritera,$userDetails){
        global $mbaBaseCourse;
        global $fullTimeEdType;

        /*case 1 - only MBA*/
        if(count($searchCritera['courseId'])==1 && $searchCritera['courseId'][0] == $mbaBaseCourse && count($searchCritera['attributeValues']) >0 ){
            $returnData = $this->filterUserFullTimeMode($userDetails);
            return $returnData;
        }

        /*case 2 - other courses and no MBA*/
        if(!in_array($mbaBaseCourse, $searchCritera['courseId']) && count($searchCriteria['courseId'])>=1){
            $returnData = $this->filterMRUserData($userDetails,$mbaBaseCourse);      
            return $returnData;
        }

        /*case 3 - MBA and other course with FT mode ONLY*/
        if( ( in_array($mbaBaseCourse, $searchCritera['courseId']) && count($searchCritera['courseId'])>1 ) &&  count($searchCritera['attributeValues']) ==1 &&  $searchCritera['attributeValues'] == $fullTimeEdType ){
            $returnData = $this->filterMRUserData($userDetails,$mbaBaseCourse);             
            return $returnData;
        }

        /*case 4 - MBA and other courses with PT only*/
         if( (in_array($mbaBaseCourse, $searchCritera['courseId']) && count($searchCritera['courseId'])>1 ) &&  (!in_array($fullTimeEdType, $searchCritera['attributeValues'])  && count($searchCritera['attributeValues'])>0) ){
            $returnData = $this->filterUserFullTimeMode($userDetails);
            return $returnData;
        }

        /*case 6 - MBA course and no Mode*/
        if( (in_array($mbaBaseCourse, $searchCritera['courseId']) ) &&  (count($searchCritera['attributeValues'])==0) ){
            $returnData = $this->filterUserFullTimeMode($userDetails);
            return $returnData;
        }


        /*case 5 - MBA and other courses with FT and PT mode both*/
        /*have kept this case as default*/
        $returnData = $this->filterMRUserData($userDetails,$mbaBaseCourse);
        return $returnData;

    }

    function filterEnngStreamData($searchCritera,$userDetails){
        
        global $btechBaseCourse;
        global $fullTimeEdType;
        
        /*case 1 - only MBA*/
        if(count($searchCritera['courseId'])==1 && $searchCritera['courseId'][0] == $btechBaseCourse){
            $returnData = $this->filterUserFullTimeMode($userDetails);
            return $returnData;
        }

        /*case 2 - other courses and no MBA*/
        if(!in_array($btechBaseCourse, $searchCritera['courseId']) && count($searchCriteria['courseId'])>=1){
            $returnData = $this->filterMRUserData($userDetails,$btechBaseCourse);
            return $returnData;
        }

        /*case 3 - MBA and other course with FT mode ONLY*/
        if( ( in_array($btechBaseCourse, $searchCritera['courseId']) && count($searchCritera['courseId'])>1 ) &&  count($searchCritera['attributeValues']) == 1 &&  $searchCritera['attributeValues'][0] == $fullTimeEdType ){
            $returnData = $this->filterMRUserData($userDetails,$btechBaseCourse);             
            return $returnData;
        }

        /*case 4 - MBA and other courses with PT only*/
         if( (in_array($btechBaseCourse, $searchCritera['courseId']) && count($searchCritera['courseId'])>1 ) && (!in_array($fullTimeEdType, $searchCritera['attributeValues']) && count($searchCritera['attributeValues'])>0 ) ){
            $returnData = $this->filterUserFullTimeMode($userDetails);
            return $returnData;
        }

        /*case 6 - MBA course and no Mode*/
        if( (in_array($btechBaseCourse, $searchCritera['courseId']) ) &&  (count($searchCritera['attributeValues'])==0) ){
            $returnData = $this->filterUserFullTimeMode($userDetails);
            return $returnData;
        }

        /*case 5 - MBA and other courses with FT and PT mode both*/
        /*have kept this case as default*/
        $returnData = $this->filterMRUserData($userDetails,$btechBaseCourse);
        return $returnData;        
    }

    /*function filterBaseCourse($userDetails,$mbaBaseCourse){
        $userData = array();

        foreach ($userDetails as $data) {

            $courseDataArray = explode(',', $data['courseData']);
            unset($data['courseData']);
            
            $courseNewArray = array();

            foreach ($courseDataArray as  $value) {
                
                if($value != 'B.E. / B.Tech' && $value != 'MBA/PGDM' ){
                    $courseNewArray[] = $value;
                }
            }


            $data['courseData'] = implode(', ', $courseNewArray);
            unset($courseNewArray);
            $userData[$data['userId']] = $data;
        }
        
        return $userData;
    }*/

    function filterMRUserData($userDetails,$baseCourse){
        global $mbaBaseCourse;
        global $fullTimeEdType;
        global $btechBaseCourse;
        $userData = array();

        foreach ($userDetails as $key => $data) {
            
           /* $userBaseCourseArray = explode(',', $data['shikshaCourse']);*/
           
            if(isset($data['courseData'])){
                $userBaseCourseArray = explode(', ', $data['courseData']);
            } else if(isset($data['Course'])){
                $userBaseCourseArray = explode(', ', $data['Course']);
            }

            if(count($userBaseCourseArray)==1 && ($userBaseCourseArray[0] == 'B.E. / B.Tech'||$userBaseCourseArray[0] =='MBA/PGDM')){

                if(count($data['modeData']) >1 && array_key_exists('Full Time', $data['modeData'])){
                    unset($data['modeData']['Full Time']);
                }

                $modeData = $data['modeData'];

                $modeDataDisplay = '';
                foreach ($modeData as $key => $value) {
                    $modeDataDisplay .= $key;
                    if($value != '') 
                        $modeDataDisplay .= ' - '.$value;
                    $modeDataDisplay .= ', ';
                }
               
                $modeDataDisplay = rtrim($modeDataDisplay, ", ");

                $data['Mode'] = $modeDataDisplay;
                
            }else{
                if(isset($data['courseData'])){
                    $courseDataArray = explode(', ', $data['courseData']);
                } else if(isset($data['Course'])){
                    $courseDataArray = explode(', ', $data['Course']);
                }

                $courseNewArray = array();

                foreach ($courseDataArray as  $value) {
                    
                    if($value != 'B.E. / B.Tech' && $value != 'MBA/PGDM' ){
                        $courseNewArray[] = $value;
                    }
                }

                if(isset($data['courseData'])){
                    $data['courseData'] = implode(', ', $courseNewArray);
                } else if(isset($data['Course'])){
                    $data['Course'] = implode(', ', $courseNewArray);
                }
                
            }

            $userData[$data['userId']] = $data;
            
        }
        
        return $userData;
    }

    public function checkFTExclusionFlag($inputArray){
        Global $managementStreamMR;
        Global $engineeringtStreamMR;
        global $fullTimeEdType;
        global $totalEducationMode;

        $isFTExclusionFlag = false;

        if( ($inputArray['stream'] == $managementStreamMR || $inputArray['stream'] == $engineeringtStreamMR) && ( ($inputArray['attributeValues'][0] == $fullTimeEdType && count($inputArray['attributeValues']) ==1) || (in_array($fullTimeEdType, $inputArray['attributeValues']) && count($inputArray['attributeValues'])<$totalEducationMode ) ) ){
            $isFTExclusionFlag = true;
        }

        return $isFTExclusionFlag;
    }

    public function checkMRCourseExclusionFlag($inputArray){
       
        Global $managementStreamMR;
        Global $engineeringtStreamMR;
        global $fullTimeEdType;
        global $totalEducationMode;
        global $mbaBaseCourse;
        global $btechBaseCourse;

        $isMRCourseExclusion = false;

        if( ($inputArray['stream'] == $managementStreamMR || $inputArray['stream'] == $engineeringtStreamMR) && ( ( in_array($mbaBaseCourse, $inputArray['courseId']) || in_array($btechBaseCourse, $inputArray['courseId'])) && count($inputArray['attributeValues'])==0) ) {
            $isMRCourseExclusion = true;
        }

        return $isMRCourseExclusion;
    }
}
?>
