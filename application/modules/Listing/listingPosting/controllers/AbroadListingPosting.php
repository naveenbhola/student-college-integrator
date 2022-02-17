<?php

class AbroadListingPosting extends MX_Controller
{
    /**
     * Class data member declaration section
     */
    private $usergroupAllowed;
    private $abroadCommonLib;
    private $abroadPostingLib;
    private $abroadCmsModelObj;
    private $saCMSToolsLib;

    /**
     * Constructor
     */
    public function __construct()
    {
        // load the config
        parent::__construct();
        $this->config->load('studyAbroadCMSConfig');
        // initialize the user group to be allowed to access Study abroad CMS
        $this->usergroupAllowed = array('saAdmin','saCMS','saContent','saSales','saRMS','saCMSLead');

        $this->abroadCommonLib 		= $this->load->library('listingPosting/AbroadCommonLib');
        $this->abroadPostingLib 	= $this->load->library('listingPosting/AbroadPostingLib');
        $this->abroadCmsModelObj	= $this->load->model('listingPosting/abroadcmsmodel');
        $this->expertProfileLib     = $this->load->library('expertPosting/expertProfileLib');


        $this->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $this->locationRepository = $locationBuilder->getLocationRepository();

        $this->load->repository('CategoryRepository','categoryList');
        $this->load->builder('CategoryBuilder','categoryList');
        // for common tools like abroad cms user validation
        $this->saCMSToolsLib= $this->load->library('saCMSTools/SACMSToolsLib');
    }

    /**
     * Purpose : Index function of the class
     * Params  :	none
     * Author  : none
     */
    function index()
    {
        $validity = $this->cmsAbroadUserValidation();

        $usergroup = $validity['usergroup'];
        if(in_array($usergroup,array('saAdmin','saCMS','saCMSLead')) !== false)
        {
            $this->viewUniversityListing();
        }elseif($usergroup=='saContent'){
            $this->viewContentListing();
        }elseif($usergroup=='saSales'){
            $this->viewPaidClient();
        }
        elseif($usergroup=='saRMS'){
            $this->viewRMSCounsellor();
        }
    }

    /**
     * Purpose : Method to validate the user and do the necessary action(s)
     * Params  :	none
     * Author  : none
     */
    function cmsAbroadUserValidation($noRedirectionButReturn = false)
    {
        $usergroupAllowed 	= $this->usergroupAllowed;
        $validity 		    = $this->checkUserValidation();
        $returnArr = $this->saCMSToolsLib->cmsAbroadUserValidation($validity, $usergroupAllowed,$noRedirectionButReturn);
        return $returnArr;
    }

    /**
     * Purpose : Method to validate the user and do the necessary action(s)
     * Params  :	none
     * Author  : Romil Goel
     */
    public function addCityForm()
    {
	$this->usergroupAllowed = array('saCMS','saAdmin','saCMSLead');
	// get the user data
	$displayData = $this->cmsAbroadUserValidation();

	$countryId = $this->input->get("countryId");
	$countryId = empty($countryId) ? 0 : (int)$countryId;

	// prepare the display date here
	$displayData['formName'] 	 = ENT_SA_FORM_ADD_CITY;
	$displayData['selectLeftNav']    = "CITY";
	$displayData['abroadStatesList'] = $this->abroadCommonLib->getAllAbroadStates();
	$displayData['countryIdAddCityForm']   = $countryId;
	$this->_populateAbroadCountries($displayData);

	// call the view
	$this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function editCityForm()
    {
        $this->usergroupAllowed = array('saCMS','saAdmin','saCMSLead');
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        $cityId = $this->input->get("cityId", true);
        $countryId = $this->input->get("countryId", true);
        $countryId = empty($countryId) ? 0 : (int)$countryId;
       // _p($cityId."  ---------  ".$countryId);
        // if city id is invalid, show 404 error page
        if(!($cityId > 0)) {
            show_404();
        }

        $displayData = $this->abroadCommonLib->getCityDataForEditMode($cityId);

        // prepare the display date here
        $displayData['formName'] 	 = ENT_SA_FORM_EDIT_CITY;
        $displayData['selectLeftNav']    = "CITY";
        $displayData['abroadStatesList'] = $this->abroadCommonLib->getAllAbroadStates();
        $displayData['cityId'] = $cityId;
        $displayData['countryIdAddCityForm'] = $countryId;
        $displayData['stateId'] = empty($displayData['stateId']) || $displayData['stateId'] < 0 ? 0 : $displayData['stateId'];
        $this->_populateAbroadCountries($displayData);

        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }
    /**
     * Purpose : Method for showing city listing
     * Params  :	none
     * Author  : Vinay Airan
     */
    public function viewCityListing()
    {
        $this->usergroupAllowed = array('saCMS','saAdmin','saCMSLead');
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();
        $searchCityString = trim($this->input->get('seachCitybox'));
        if(strcmp($searchCityString,"Search a City") ==0 || $searchCityString =="")
        {
            $searchCityString = "";

        }
        $URL = !empty($searchCityString) ? ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CITY."?seachCitybox=".$searchCityString : ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CITY;
        $this->load->library('listingPosting/Paginator');
        $displayData['paginator']  = new Paginator($URL);
        $cityArray = $this->abroadCommonLib->getAllCityList($searchCityString,$displayData['paginator']->getLimitOffset(), $displayData['paginator']->getLimitRowCount());
        $displayData['paginator']->setTotalRowCount($cityArray['totalCount']);

        // prepare the display date here
        $displayData['formName'] 	= ENT_SA_VIEW_LISTING_CITY;
        $displayData['searchCityString'] = $searchCityString;
        $displayData['selectLeftNav']   = "CITY";
        $displayData['totalCount'] = $cityArray['totalCount'];
        $displayData['cityArray'] = $cityArray['data'];

        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    /**
     * Purpose : Method to render the Universities MIS
     * Params  :	1. Status of the data to be shown, by default it is 'all' i.e live and draft both
     * Author  : Romil Goel
     */
    public function viewUniversityListing($displayDataStatus = 'all')
    {
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // get post parameters
        $searchUnivName = $this->input->get("q");
        $resultPerPage  = $this->input->get("resultPerPage");

        // data massaging
        $searchUnivName = ($searchUnivName == "Search University") ? "" : $searchUnivName;
        $resultPerPage  = ($resultPerPage) ? $resultPerPage : "";

        // prepare the query parameters coming
        $queryParams	= "1";
        $queryParams   .= ($searchUnivName ? "&q=".$searchUnivName : "");
        $queryParams   .= ($resultPerPage  ? "&resultPerPage=".$resultPerPage : "");
        $queryParams    = $queryParams 	   ? "?".$queryParams : "";

        // prepare the URL for view as well as for paginator
        $URL 		= ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_UNIVERSITY."/".$displayDataStatus;
        $URLPagination 	= ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_UNIVERSITY."/".$displayDataStatus.($queryParams ? $queryParams : "");

        // initialize the paginator instance
        $this->load->library('listingPosting/Paginator');
        $displayData['paginator']  	  = new Paginator($URLPagination);

        // fetch the universities data
        $result = $this->abroadPostingLib->getUniversityTableData($displayDataStatus, $displayData['paginator'], $searchUnivName);
        $displayData['paginator']->setTotalRowCount($result['totalCount']);

        // prepare the display date here
        $displayData['formName'] 	  = ENT_SA_VIEW_LISTING_UNIVERSITY;
        $displayData['selectLeftNav']     = "UNIVERSITY";
        $displayData['displayDataStatus'] = $displayDataStatus;
        $displayData['searchTerm'] 	  = $searchUnivName;
        $displayData['queryParams'] 	  = $queryParams;
        $displayData['totalResultCount']  = $result['dataCount'];
        $displayData['URL'] 	  	  = $URL;
        $displayData["reportData"] 	  = $result['data'];

        // load the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    /**
     * Purpose : none
     * Params  :	none
     * Author  : none
     */
    public function viewDepartmentListing($displayDataStatus = 'all')
    {
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // get post parameters
        $searchDeptName = $this->input->get("q");
        $resultPerPage  = $this->input->get("resultPerPage");

        // data massaging
        $searchDeptName = ($searchDeptName == "Search Department") ? "" : $searchDeptName;
        $resultPerPage  = ($resultPerPage) ? $resultPerPage : "";

        // prepare the query parameters coming
        $queryParams	= "1";
        $queryParams   .= ($searchDeptName ? "&q=".$searchDeptName : "");
        $queryParams   .= ($resultPerPage  ? "&resultPerPage=".$resultPerPage : "");
        $queryParams    = $queryParams 	   ? "?".$queryParams : "";

        // prepare the URL for view as well as for paginator
        $URL 		= ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_DEPARTMENT."/".$displayDataStatus;
        $URLPagination 	= ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_DEPARTMENT."/".$displayDataStatus.($queryParams ? $queryParams : "");

        // initialize the paginator instance
        $this->load->library('listingPosting/Paginator');
        $displayData['paginator']  	  = new Paginator($URLPagination);

        // fetch the universities data
        $result = $this->abroadPostingLib->getDepartmentTableData($displayDataStatus, $displayData['paginator'], $searchDeptName);
        //_p($result['totalCount']);
        $displayData['paginator']->setTotalRowCount($result['totalCount']);
        //_p($displayData['paginator']); die;

        // prepare the display date here
        $displayData['formName'] 	  = ENT_SA_VIEW_LISTING_DEPARTMENT;
        $displayData['selectLeftNav']     = "DEPARTMENT";
        $displayData['displayDataStatus'] = $displayDataStatus;
        $displayData['searchTerm'] 	  = $searchDeptName;
        $displayData['queryParams'] 	  = $queryParams;
        $displayData['totalResultCount']  = $result['dataCount'];
        $displayData['URL'] 	  	  = $URL;
        $displayData["deptData"] 	  = $result['data'];

        foreach($displayData["deptData"] as $key=>$value)
        {
            $universityLocationInfo = $this->abroadCmsModelObj->getUniversityLocationInfo($value['universityId'],$value["status"]);
            $locResult = $this->abroadCmsModelObj->getlocationDetailsByCityId($universityLocationInfo['city_id']);
            if($locResult) {
                $displayData["deptData"][$key]['cityName'] = $locResult['cityName'];
                $displayData["deptData"][$key]['stateName'] = $locResult['stateName'];
                $displayData["deptData"][$key]['countryName'] = $locResult['countryName'];
            }
            //$profileCompltnResult = $this->abroadCmsModelObj->getProfileCompletePercentage($value['deptId'], 'institute');
            //$displayData["deptData"][$key]['profileCompletion'] = $profileCompltnResult;
        }
        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    /**
     * Purpose : none
     * Params  :	none
     * Author  : none
     */
    public function viewCourseListing()
    {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // get Course And PageNo.
        $courseName = ($this->input->get("searchCourse"))?$this->input->get("searchCourse"):"";
        $status = ($this->input->get("status"))?$this->input->get("status"):"";
        $rowsPerPage = ($this->input->get("resultPerPage"))?$this->input->get("rowsPerPage"):"";

        if($courseName == 'Search Course'){
            $courseName = "";
        }

        // prepare the query parameters coming
        $queryParams	= "";
        $queryParams   .= ($courseName ? "&searchCourse=".$courseName : "");
        $queryParams   .= ($resultPerPage  ? "&resultPerPage=".$resultPerPage : "");
        $queryParams    = $queryParams 	   ? $queryParams : "";

        //Forming Relative URL
        $formURL = ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_COURSE."/";
        $relativeUrl = ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_COURSE."/?status=".$status.$queryParams;

        //Loading Libraries for Pagination
        $this->load->library('listingPosting/Paginator');
        $displayData['paginator']  = new Paginator($relativeUrl);

        // Get Lower Limit and Rows Per Page
        $lowerlimit = $displayData['paginator']->getLimitOffset();
        $rowsPerPage = $displayData['paginator']->getLimitRowCount();

        // library call for courses data
        $courseResultArr = $this->abroadPostingLib->viewCourseTable($courseName,$status,$lowerlimit,$rowsPerPage);
        $courseArr = $courseResultArr['course_data'];
        $totalRows = $courseResultArr['total_count'];
        $tabsArr = $courseResultArr['tabs'];

        if(!empty($courseArr)){
            //collect categoryIds for courses
            foreach($courseArr as $course){
                if($course['category_id']){
                    $categoryIds[] = $course['category_id'];
                }
                $instituteIds[] = $course['institute_id'];
            }

            //unique institute Ids
            array_unique($instituteIds);

            //fetch institute-university location details
            $intituteArr = $this->abroadPostingLib->getInstituteLocation($instituteIds);

            //unique categoryIds
            $categoryIds = array_unique($categoryIds);

            if(!empty($categoryIds)){
                //Loading CategoryBuilder for getting CategoryRepository
                $this->load->builder('CategoryBuilder','categoryList');
                $categoryBuilder = new CategoryBuilder;
                $categoryRepository = $categoryBuilder->getCategoryRepository();

                //fetch multiple objects for sub-category Ids
                $categoryIds = $categoryRepository->findMultiple($categoryIds);

                //retriving parent categoryIds
                foreach($categoryIds as $categoryIdObj){
                    $parentCategoryIds[] = $categoryIdObj->getParentId();
                }

                //unique parent categoryIds
                array_unique($parentCategoryIds);

                //fetch multiple objects for parent-category Ids
                $parentCategoryIds = $categoryRepository->findMultiple($parentCategoryIds);
            }
            //Arrange view-Data
            foreach($courseArr as &$courseObj){
                if($courseObj['category_id']){
                    $courseObj['subCategory_name'] = $categoryIds[$courseObj['category_id']]->getName();
                    $courseObj['parentCategory_name'] = $parentCategoryIds[$categoryIds[$courseObj['category_id']]->getParentId()]->getName();
                }
                $courseObj['department_name'] = $intituteArr[$courseObj['institute_id']]['department_name'];
                $courseObj['university_name'] = $intituteArr[$courseObj['institute_id']]['university_name'];
                $courseObj['city_name'] = $intituteArr[$courseObj['institute_id']]['city_name'];
                $courseObj['country_name'] = $intituteArr[$courseObj['institute_id']]['country_name'];

                $courseObj['date'] = date("j M Y",strtotime($courseObj['date']));
            }

        }

        //Setting Total RowCount for Pagination
        $displayData['paginator']->setTotalRowCount($totalRows[0]['totalRows']);

        // prepare the display date here
        $displayData['formName'] 	= ENT_SA_VIEW_LISTING_COURSE;
        $displayData['selectLeftNav']   = "COURSE";
        $displayData['courseArr']   	= $courseArr;
        $displayData['searchTerm'] 	= $courseName;
        $displayData['formURL'] 	= $formURL;
        $displayData['displayDataStatus'] = $status;
        $displayData['totalResultCount']  = $tabsArr;
        $displayData['queryParams'] 	  = $queryParams;

        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function showEditCourseForm($courseId = ""){
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        $displayData = $this->cmsAbroadUserValidation();

        if(!$this->_validateCourseId($courseId)) {
            show_404();
        }

        $displayData['formName'] = ENT_SA_FORM_EDIT_COURSE;
        $displayData['courseId'] = $courseId;

        $displayData['courseData'] = $this->abroadCommonLib->getCourseInfo($displayData['courseId']);
        if($displayData['courseData']['listings_main']['course_id'] == "") {
            show_404();
        }

        $displayData['EnglishORNonEnglishExam'] =  $this->config->item('EXAM_BY_TYPE_ENGLISH_OR_NON_ENGLISH');
        $this->_populateAbroadCountries($displayData);
        $displayData['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();

        $displayData['abroadMainLDBCourses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();
        $displayData['currencyData'] = $this->abroadCommonLib->getCurrencyList();
        $displayData['courseLevels'] = $this->abroadCommonLib->getAbroadCourseLevels();
        $displayData['selectLeftNav']   = "COURSE";

        $displayData['recruitingCompanies'] = $this->abroadCommonLib->getRecruitingCompanies();

        $displayData['abroadExamsMasterList'] = $this->abroadCommonLib->getAbroadExamsMasterList('', 0, true);

                // $universityDetails = $this->abroadCommonLib->getUniversityDetails($universityId, ENT_SA_PRE_LIVE_STATUS);
        $this->_prepareCourseFormFillingData($displayData);
        $displayData['formPostUrl'] = '/listingPosting/AbroadListingPosting/editCourse';
        $displayData['previewLinkFlag'] = $this->abroadPostingLib->checkListingExistinGivenState($courseId,'course','live');

        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function showAddCourseForm($type="",$typeId="")
    {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        $displayData = $this->cmsAbroadUserValidation();
        $displayData['isCloneFlag'] = false;
        if($type === "course") {
            if(!$this->_validateCourseId($typeId)) {
                show_404();
            }
            $displayData['courseId'] = $typeId;
            $displayData['isCloneFlag'] = true;
            $displayData['courseData'] = $this->abroadCommonLib->getCourseInfo($displayData['courseId']);
            if($displayData['courseData']['listings_main']['course_id'] == "") {
                show_404();
            }
        }

        $displayData['formName'] = ENT_SA_FORM_ADD_COURSE;
        $displayData['convertSnapshotToDetailFlag']=0;
        $displayData['EnglishORNonEnglishExam'] =  $this->config->item('EXAM_BY_TYPE_ENGLISH_OR_NON_ENGLISH');
        //Snapshotcourse_id is being passed as a query string parameter on
        //Convert to detail course action from Snapshot course listings page
        $snapshotcourse_id = $this->input->get('snapshotcourse_id');
        if($snapshotcourse_id!= "")
        {

            $displayData['convertSnapshotToDetailFlag']=1;
            //Get the snapshot course data
            $displayData['courseData'] = $this->abroadPostingLib->getSnapshotCourseDataForEdit($snapshotcourse_id);
            $displayData['countryId'] = $displayData['courseData']['country_id'];
            $universityDetails = $this->abroadCommonLib->getUniversityDetails($displayData['courseData']['university_id'], ENT_SA_PRE_LIVE_STATUS);
            $displayData['universityInfo']['universityId'] = $displayData['courseData']['university_id'];
            $displayData['universityInfo']['universityName'] = $universityDetails['name'];
            $displayData['externalLinks']['courseWebsite'] = $displayData['courseData']['website_link'];
            $subCatId = $displayData['courseData']['category_id'];

            $builderObj	= new CategoryBuilder;
            $repoObj 	= $builderObj->getCategoryRepository();
            $subCatObj 	= $repoObj->find($subCatId);
            //$displayData['parentCat'] = $subCatObj->getParentId();

            $displayData['mainCategoryIdOfCourse'] = $subCatObj->getParentId();
            $displayData['subCategoryIdofCourse'] = $subCatId;
            $displayData['courseData']['listings_main']['course_name']= $displayData['courseData']['course_name'];
            $displayData['courseData']['course_details']['course_level_1'] = $displayData['courseData']['course_type'];
            $displayData['courseData']['snapshotCourseId']= $snapshotcourse_id;

        }
        if($type == "department" && $typeId != "") {
            $departmentDetails = $this->abroadCommonLib->getDepartmentBasicInfo($typeId);
            //_p($departmentDetails); die;
            $displayData['countryId'] = $departmentDetails['country_id'];
            $displayData['deptInfo']['deptId'] = $typeId;
            $displayData['deptInfo']['deptName'] = $departmentDetails['listing_title'];

            $displayData['universityInfo']['universityId'] = $departmentDetails['university_id'];
            $displayData['universityInfo']['universityName'] = $departmentDetails['name'];
        }
        elseif($type == "university" && $typeId != ""){
            $universityDetails = $this->abroadCommonLib->getUniversityInfo($typeId);
            $displayData['countryId'] = $universityDetails['country_id'];
            $displayData['universityInfo']['universityId'] = $typeId;
            $displayData['universityInfo']['universityName'] = $universityDetails['university_name'];
        }

        $this->_populateAbroadCountries($displayData);
        $displayData['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();

        $displayData['abroadMainLDBCourses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();
        $displayData['currencyData'] = $this->abroadCommonLib->getCurrencyList();
        $displayData['courseLevels'] = $this->abroadCommonLib->getAbroadCourseLevels();
        $displayData['selectLeftNav']   = "COURSE";

        $displayData['recruitingCompanies'] = $this->abroadCommonLib->getRecruitingCompanies();

        $displayData['abroadExamsMasterList'] = $this->abroadCommonLib->getAbroadExamsMasterList('', 0, true);
        $this->_prepareCourseFormFillingData($displayData);
        $displayData['formPostUrl'] = '/listingPosting/AbroadListingPosting/addCourse';

        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    private function _prepareCourseFormFillingData(&$displayData)
    {
        $displayData['universityCourseProfileData'] = $this->abroadCommonLib->getShikshaApplyProfileForUniversity($displayData['courseData']['university_info']['university_id']);

        $displayData['countryId'] = $displayData['courseData']['course_location_attribute']['country_id'];
        $displayData['deptInfo']['deptId'] = $displayData['courseData']['course_details']['institute_id'];
        $displayData['deptInfo']['deptName'] = $displayData['courseData']['course_details']['deptName'];

        $displayData['universityInfo']['universityId'] = $displayData['courseData']['university_info']['university_id'];
        $displayData['universityInfo']['universityName'] = $displayData['courseData']['university_info']['universityName'];

        $displayData['mainCategoryIdOfCourse'] = $displayData['courseData']['listing_category_table']['parentId'];
        $displayData['subCategoryIdofCourse'] = $displayData['courseData']['listing_category_table']['category_id'];
        //since we store desired course ids as well as specialization ids in clientCourseToLDBCourseMapping we need this check for desired courses
        //_p($displayData['courseData']['clientCourseToLDBCourseMapping']);


        foreach($displayData['courseData']['listing_external_links'] as $key =>  $infoArray) {
            $displayData['externalLinks'][$infoArray['link_type']] = $infoArray['link'];
        }

        foreach($displayData['courseData']['listing_attributes_table'] as $key =>  $infoArray) {
            $displayData['listingAttributes'][str_replace(" ", "_", $infoArray['caption'])] = $infoArray['attributeValue'];
        }

        foreach($displayData['courseData']['course_attributes'] as $key =>  $infoArray) {
            $displayData['courseAttributes'][$infoArray['attribute']] = $infoArray['value'];
        }

        foreach($displayData['courseData']['course_start_date_info'] as $key =>  $infoArray) {
            $displayData['courseStartDateInfo'][] = $infoArray['start_date_month'];
        }

        foreach($displayData['courseData']['company_logo_mapping'] as $key =>  $infoArray) {
            $displayData['recruitingComapanies'][] = $infoArray['logo_id'];
        }

        foreach($displayData['courseData']['abroad_course_custom_values_mapping'] as $key =>  $infoArray)
        {
            $displayData['customValuesMapping'][$infoArray['valueType']][] = array('caption'=>$infoArray['caption'],'value'=>$infoArray['value']);
        }

        if(count($displayData['courseData']['listingExamAbroad'])) {
            $count = 0;
            foreach($displayData['courseData']['listingExamAbroad'] as $key =>  $infoArray) {
                if($infoArray['examId'] == -1) {
                    $displayData['customExamData'][$count]['examName'] = $infoArray['examName'];
                    $displayData['customExamData'][$count]['cutoff'] = $infoArray['cutoff'];
                    $displayData['customExamData'][$count]['comments'] = $infoArray['comments'];

                    $count++;
                } else {
                    $displayData['examData'][$infoArray['examId']]['cutOff'] = $infoArray['cutoff'];
                    $displayData['examData'][$infoArray['examId']]['comments'] = $infoArray['comments'];
                }
            }

        }

        $categoryId = $displayData['mainCategoryIdOfCourse'] ;
        $subCategoryId = $displayData['subCategoryIdofCourse'] ;
        $courseLevel = $displayData['courseData']['course_details']['course_level_1'];

        $displayData['abroadCourseSpecializations'] = $this->abroadCommonLib->getCourseSpecializations($categoryId,$courseLevel,$subCategoryId);

        $desiredCourseIdsArray = array_map(function($value){ return $value['SpecializationId'];},$displayData['abroadMainLDBCourses']);
        foreach($displayData['courseData']['clientCourseToLDBCourseMapping'] as $key =>  $ldbCourseInfo) {
            if(in_array($ldbCourseInfo['LDBCourseID'], $desiredCourseIdsArray))
            {
                $displayData['desiredCourseIdArray'][] = $ldbCourseInfo['LDBCourseID'];
            }
            else //its a specializationId
            {
                $displayData['courseSpecializationIdArray'][$ldbCourseInfo['LDBCourseID']]['Id'] = $ldbCourseInfo['LDBCourseID'];
                $displayData['courseSpecializationIdArray'][$ldbCourseInfo['LDBCourseID']]['desc']= $ldbCourseInfo['specializationDescription'];
            }
        }

    }

    private function _validateCourseId($courseId){
        if($courseId == "" || !is_numeric($courseId)) {
            return false;
        }
        return true;
    }

    public function editCourse() {
        $params['userInfo'] = $this->cmsAbroadUserValidation();

        $courseData = $this->_getPostDataForCourse(ENT_SA_FORM_EDIT_COURSE);
        $courseData['flow'] = "edit";

        if($this->abroadCmsModelObj->checkIfCourseAlreadyExists($courseData['courseName'], $courseData['departmentId'], $courseData['courseId'])) {
            $response['Fail']['courseAlreadyExists'] = "Course with same name already exists. Please enter a different Course Name.";
            echo json_encode($response);
            exit;
        }

        $requestBrochureResp = $this->listingBrochureUpload('abroadCourse','brochureLink');
        if(array_key_exists('Fail', $requestBrochureResp)) {
            echo json_encode($requestBrochureResp);
            exit;
        }

        $courseData['courseBrochureUrl'] = "";
        if($requestBrochureResp == "") {
            if($courseData['existingBrochureUrl'] != "") {
                $courseData['courseBrochureUrl'] = $courseData['existingBrochureUrl'];
            }
        } else {
            $courseData['courseBrochureUrl'] = $requestBrochureResp;
        }

        $courseData['courseBrochureUrl'] = str_replace(MEDIAHOSTURL, '', $courseData['courseBrochureUrl']);
        $courseBasicInfo = $this->abroadCmsModelObj->getCourseBasicInfo($courseData['courseId']);
        $courseData['submit_date'] = $courseBasicInfo['submit_date'];
        // when course is being edited get last cllientid/username,packtype,viewcount
        $result = $this->abroadCmsModelObj->getListingMainData('course',$courseData['courseId']);
        $courseData['username'] =  $result[0]['username'];//$courseBasicInfo['username'];
        $courseData['pack_type'] = $result[0]['pack_type'];//$courseBasicInfo['pack_type'];
        $courseData['viewCount'] = $result[0]['viewCount'];
        $courseData['subscriptionId'] = $result[0]['subscriptionId'];
        $courseData['approve_date'] = $result[0]['approve_date']; // date of subscription consumption (if any)
        $courseData['expiry_date'] = $result[0]['expiry_date'];// expiry date of subscription

        $courseData['courseUrl'] = $courseBasicInfo['listing_seo_url'];
        // error_log("\n course info = ".print_r($courseBasicInfo, true),3,'/home/amitkuksal/Desktop/log.txt'); die;

        $departmentLocationInfo = $this->abroadCmsModelObj->getDepartmentLocationInfo($courseData['departmentId']);

        $courseData['institute_location_id'] 	= $departmentLocationInfo[0]['institute_location_id'];
        $courseData['courseCityId'] 	= $departmentLocationInfo[0]['city_id'];
        $courseData['courseCountryId'] 	= $departmentLocationInfo[0]['country_id'];
        $params['courseData'] = $courseData;
        // error_log("courseId = ".$courseData['courseId']."\n dept info = ".print_r($departmentLocationInfo, true),3,'/home/amitkuksal/Desktop/log.txt');

        // calculate the percentage completion of course
        $highLowFieldValues 			= $this->abroadCommonLib->getCourseHighAndLowFields($courseData);
        $params['courseData']['percentageCompletion'] 	= $this->abroadCommonLib->calculatePercentageCompletion($highLowFieldValues['high_field_values'], $highLowFieldValues['low_field_values']);

        // Posting data now..
        $postingFlag = $this->abroadPostingLib->postCourseForm($params);

        if($postingFlag) {
            // purge BE nginx cache
            $shikshamodel = $this->load->model("common/shikshamodel");
            $arr = array("cache_type" => "htmlpage", "entity_type" => "saUlp", "entity_id" => $courseData['universityId'], "cache_key_identifier" => "");
            $shikshamodel->insertCachePurgingQueue($arr);
            $arr['entity_type']= "saAcp";
            $shikshamodel->insertCachePurgingQueue($arr);
            $arr['entity_type']= "saClp";
            $arr['entity_id']= $courseData['courseId'];
            $shikshamodel->insertCachePurgingQueue($arr);
            return json_encode($return_response_array['Success']['true']);
        }
    }

    public function convertSnapshotCourseToDetailCourse()
    {

        $this->cmsAbroadUserValidation();
        //snapshotcourse_id is being passed as a query string parameter on
        //Convert to detail course action from Snapshot course listings page

        $snapshotcourse_id = $this->input->get('snapshotcourse_id');
        $result = $this->abroadCmsModelObj->checkIfSnapshotCourseExistInMappings($snapshotcourse_id,'',"draft");

        //if draft entry exists, then redirect to edit course flow, otherwise redirect to
        //add course flow
        if(count($result) == 1){
            header("Location: /listingPosting/AbroadListingPosting/showEditCourseForm/".$result[0]['newcourse_id']); exit;
        }else{
            header("Location: /listingPosting/AbroadListingPosting/showAddCourseForm?snapshotcourse_id=".$snapshotcourse_id); exit;

        }
    }
    

    public function addCourse() {

        $params['userInfo'] = $this->cmsAbroadUserValidation();

        $courseData = $this->_getPostDataForCourse(ENT_SA_FORM_ADD_COURSE);
        $courseData['flow'] = "add";
        if($this->abroadCmsModelObj->checkIfCourseAlreadyExists($courseData['courseName'], $courseData['departmentId'])) {
            $response['Fail']['courseAlreadyExists'] = "Course with same name already exists. Please enter a different Course Name.";
            echo json_encode($response);
            exit;
        }

        $courseData['courseId'] = Modules::run('common/IDGenerator/generateId', 'course');

        $requestBrochureResp = $this->listingBrochureUpload('abroadCourse','brochureLink');
        if(array_key_exists('Fail', $requestBrochureResp)) {
            echo json_encode($requestBrochureResp);
            exit;
        }

        $courseData['courseBrochureUrl'] = $requestBrochureResp;

        $universityInfo = $this->abroadCommonLib->getUniversityDetails($courseData['universityId']);

        $departmentLocationInfo = $this->abroadCmsModelObj->getDepartmentLocationInfo($courseData['departmentId']);
        // error_log("dept info = ".print_r($departmentLocationInfo, true),3,'/home/amitkuksal/Desktop/log.txt');
        $courseData['institute_location_id'] = $departmentLocationInfo[0]['institute_location_id'];
        $courseData['courseCityId'] 	     = $departmentLocationInfo[0]['city_id'];
        $courseData['courseCountryId'] 	     = $departmentLocationInfo[0]['country_id'];
        $countryName = $departmentLocationInfo[0]['name'];
        // when course is being added get dept's cllientid/username
        $result = $this->abroadCmsModelObj->getListingMainData('institute',$courseData['departmentId']);
        $courseData['username'] =  $result[0]['username'];//$courseBasicInfo['username'];
        $courseData['pack_type']  = 0;
        $courseData['viewCount'] = 1;//$courseBasicInfo['pack_type'];
        $courseData['subscriptionId'] = 0;

        $courseData['courseUrl'] = $this->abroadPostingLib->getCourseUrl($courseData['courseId'], $courseData['courseName'], $universityInfo['name'], $countryName);
        $params['courseData'] = $courseData;

        // calculate the percentage completion of course
        $highLowFieldValues 			= $this->abroadCommonLib->getCourseHighAndLowFields($courseData);
        $params['courseData']['percentageCompletion'] 	= $this->abroadCommonLib->calculatePercentageCompletion($highLowFieldValues['high_field_values'], $highLowFieldValues['low_field_values']);
        // Posting data now..
        $postingFlag = $this->abroadPostingLib->postCourseForm($params);

        if($postingFlag) {
            // purge BE nginx cache
            $shikshamodel = $this->load->model("common/shikshamodel");
            $arr = array("cache_type" => "htmlpage", "entity_type" => "saUlp", "entity_id" => $courseData['universityId'], "cache_key_identifier" => "");
            $shikshamodel->insertCachePurgingQueue($arr);
            $arr['entity_type']= "saAcp";
            $shikshamodel->insertCachePurgingQueue($arr);
            $arr['entity_type']= "saClp";
            $arr['entity_id']= $courseData['courseId'];
            $shikshamodel->insertCachePurgingQueue($arr);
            return json_encode($return_response_array['Success']['true']);
        }
    }

    private function _getPostDataForCourse($formName)
    {
        $courseId = $this->input->post('courseId');
        if($courseId != "") {
            $data['courseId'] = $courseId;
        }
        $snapshotcourseId = $this->input->post('snapshotCourseId');

        if($snapshotcourseId != ""){
            $data['snapshotCourseId']= $snapshotcourseId;
        }

        $data['universityId'] = $this->input->post('university_'.$formName);
        $data['departmentId'] = $this->input->post('departments_'.$formName);

        $data['mainCatId'] = $this->input->post('parentCat_'.$formName);
        $data['subCatId'] = $this->input->post('childCat_'.$formName);
        $data['ldbCourseId'] = $this->input->post('ldbcourses_dropdown');

        $data['courseName'] = $this->input->post('courseName_'.$formName);
        $data['courseType'] = $this->input->post('courseType');
        $courseLevel = $this->input->post('courseLevel');
        if($courseLevel===false)
        {
            $data['courseLevel'] ='';
        }
        else
        {
            $data['courseLevel'] = $courseLevel;
        }
        $courseSpecializationIds = $this->input->post('coursespecialization_field_name');
        $courseSpecializationDescriptions = $this->input->post('coursespecialization_field_desc');
        $data['courseSpecializationDetails'] = array();

        for($i=0; $i<count($courseSpecializationIds); $i++)
        {
            if($courseSpecializationIds[$i]!=0)
            {
                array_push($data['courseSpecializationDetails'],array('Id'=>$courseSpecializationIds[$i],'desc'=>$courseSpecializationDescriptions[$i]));
            }
        }

        $data['affiliationDetails'] = $this->input->post('affiliationDetails');
        $data['accreditationDetails'] = $this->input->post('accreditationDetails');
        $data['courseWebsite'] = $this->input->post('website_'.$formName);
        $data['courseDuration'] = $this->input->post('courseDuration_'.$formName);
        $data['courseDurationUnit'] = $this->input->post('courseDuration2');
        $data['courseStartDateArray'] = $this->input->post('courseStartDate');
        $data['courseDurationLink'] = $this->input->post('courseDurationLink_'.$formName);
        $data['courseDescription'] = $this->input->post('courseDescription_'.$formName);
        $data['applicationDeadlineLink'] = $this->input->post('applicationDeadlineLink');
        $data['nzqfCategorization'] = $this->input->post('nzqfCategorization');
        $data['curriculum'] = $this->input->post('curriculum_'.$formName);
        $data['courseRanking'] = $this->input->post('courseRanking_'.$formName);

        $data['admissionWebsiteLink'] = $this->input->post('admissionWebsiteLink');
        $data['englishProficiencyLink'] = $this->input->post('englishProficiencyLink');
        $data['anyOtherEligibility'] = $this->input->post('anyOtherEligibility');
        $data['examsRequiredFreeText'] = $this->input->post('examsRequiredFreeText');
        $data['applyDocumentChecklist'] 	= $this->input->post('applyDocumentChecklist_'.$formName);
        $examRequiredArray = $this->input->post('examRequired');
        $i = 0;
        foreach($examRequiredArray as $key => $examId) {
            $examData[$i]['examId'] = $examId;
            $examData[$i]['examCutOff'] = $this->input->post('examRequiredCutOff'.$examId);
            $examData[$i]['examComments'] = $this->input->post('examComments'.$examId);
            $i++;
        }
        $data['examsRequiredDataArray'] = $examData;
        $customExam = $this->input->post('customExam');
        $i = 0;
        unset($examData);
        foreach($customExam as $key => $examName) {
            if($examName == "") {
                continue;
            }
            $examData[$i]['examName'] = $examName;
            $examData[$i]['examCutOff'] = $this->input->post('customExamCutOffs'.$key);
            $examData[$i]['examComments'] = $this->input->post('customExamComments'.$key);
            $i++;
        }
        $data['examsRequiredCustomDataArray'] = $examData;

        $data['averageWorkExp'] = $this->input->post('averageWorkExp');
        $data['averageBachelorsGPA'] = $this->input->post('averageBachelorsGPA');
        $data['averageClass12Percentage'] = $this->input->post('averageClass12Percentage');
        $data['averageGMATScore'] = $this->input->post('averageGMATScore');
        $data['averageAge'] = $this->input->post('averageAge');
        $data['internationalStudentsPercentage'] = $this->input->post('internationalStudentsPercentage');

        $data['feesPageLink'] = $this->input->post('feesPageLink_'.$formName);
        $data['tutionFee'] = $this->input->post('tutionFee_'.$formName);
        $data['tutionFeeCurrency'] = $this->input->post('tutionFeeCurrency_'.$formName);
        $data['isMealIncluded'] = $this->input->post('isMealIncluded');
        $data['scholarshipLinkCourseLevel'] = $this->input->post('scholarshipLinkCourseLevel');
        $data['scholarshipLinkDeptLevel'] = $this->input->post('scholarshipLinkDeptLevel');
        $data['scholarshipLinkUniversityLevel'] = $this->input->post('scholarshipLinkUniversityLevel');

        $data['careerServiceWebsiteLink'] = $this->input->post('careerServiceWebsiteLink_'.$formName);
        $data['percentageEmployed'] = $this->input->post('percentageEmployed');
        $data['avgSalary'] = $this->input->post('avgSalary');
        $data['avgSalaryCurrency'] = $this->input->post('avgSalaryCurrency');
        $data['popularSectors'] = $this->input->post('popularSectors');
        $data['isInternshipAvailable'] = $this->input->post('isInternshipAvailable');
        $data['internships'] = $this->input->post('internships');
        $data['internshipsLink'] = $this->input->post('internshipsLink');
        $data['recruitingCompaniesArray'] = $this->input->post('recruitingCompanies');
        $data['facultyInfoLink'] = $this->input->post('facultyInfoLink');
        $data['alumniInfoLink'] = $this->input->post('alumniInfoLink');
        $data['faqLink'] = $this->input->post('faqLink');

        //Data prepration for Shiksha Apply Section
        $data['shikshaApply'] = $this->input->post('shikshaApply');
        $data['isWorkExperinceRequired']   	= $this->input->post('isWorkExperinceRequired');
        $data['workExperniceValue']        	= $this->input->post('workExperniceValue');
        $data['workExperinceDescription'] 	= $this->input->post('workExpDescription_'.$formName);
        if($this->input->post('isRequired12thCutOff')==1){
            $data['12thCutoff'] 				= $this->input->post('12thCutoff');
            $data['12thcomments'] 				= $this->input->post('12thCutOffDescription_'.$formName);
        }else{
            $data['12thCutoff'] 				= '';
            $data['12thcomments'] 				= '';
        }
        if($this->input->post('isRequiredBachelorCutOff')==1){
            $data['bachelorScoreUnit'] 			= $this->input->post('bachelorScoreUnit');
            $data['bachelorCutoff'] 			= $this->input->post('bachelorCutoff');
            $data['bachelorComments'] 			= $this->input->post('bachelorCutOffDescription_'.$formName);
        }else{
            $data['bachelorScoreUnit'] 			= '';
            $data['bachelorCutoff'] 			= '';
            $data['bachelorComments'] 			= '';
        }
        if($this->input->post('isRequiredPgCutOff')==1){
            $data['pgCutoff'] 					= $this->input->post('pgCutOff');
            $data['pgComments'] 				= $this->input->post('pgCutOffDescription_'.$formName);
        }else{
            $data['pgCutoff'] 					= '';
            $data['pgComments'] 				= '';
        }
        $data['isThreeYearDegreeAccepted']      = $this->input->post('isThreeYearDegreeAccepted');
        $data['threeYearDegreeDescription'] 	= $this->input->post('threeYearDegreeDescription_'.$formName);

        $data['applicationEligibilityAddedOn']= $this->input->post('applicationEligibilityAddedOn');
        $data['applicationEligibilityAddedBy']= $this->input->post('applicationEligibilityAddedBy');
        if($data['shikshaApply']==1){
            $data['universityCourseProfileId'] 	= $this->input->post('universityCourseProfileId');
            $data['additionalRequirement'] 		= $this->input->post('additionalRequirement_'.$formName);
            $data['isInterviewRequired'] 		= $this->input->post('isInterviewRequired');
            $data['interviewMonth'] 			= $this->input->post('interviewMonth');
            $data['interviewYear'] 				= $this->input->post('interviewYear');
            $data['interviewprocessDetail']		= $this->input->post('interViewProcessDesc_'.$formName);
            $data['applicationFeeDetail'] 		= $this->input->post('applicationFeeDetail');
            $data['feeAmount'] 					= $this->input->post('feeAmount');
            $data['applicationDetailCurrencyId']= $this->input->post('applicationDetailCurrencyId');

            $data['isCreditCardAccepted'] 		= $this->input->post('isCreditCardAccepted');
            $data['isDebitCardAccepted'] 		= $this->input->post('isDebitCardAccepted');
            $data['iswiredMoneyTransferAccepted']= $this->input->post('iswiredMoneyTransferAccepted');
            $data['isPaypalAccepted'] 			= $this->input->post('isPaypalAccepted');
            $data['feeDetails'] 				= $this->input->post('tuitionFeeDesc_'.$formName);
            $examRequiredArray = $this->input->post('examRequiredAppDetail');
            $i = 0;
            foreach($examRequiredArray as $key => $examId) {
                $examData[$i]['examId'] = $examId;
                $examData[$i]['examCutOff'] = $this->input->post('examRequiredCutOffAppDetail'.$examId);
                $examData[$i]['examComments'] = $this->input->post('examCommentsAppDetail'.$examId);
                $i++;
            }
            $data['examsRequiredDataArray'] = $examData;
            $customExam = $this->input->post('customExamAppDetail');
            $i = 0;
            unset($examData);
            foreach($customExam as $key => $examName) {
                if($examName == "") {
                    continue;
                }
                $examData[$i]['examName'] = $examName;
                $examData[$i]['examCutOff'] = $this->input->post('customExamCutOffsAppDetail'.$key);
                $examData[$i]['examComments'] = $this->input->post('customExamCommentsAppDetail'.$key);
                $i++;
            }
            $data['examsRequiredCustomDataArray'] = $examData;

            $data['shikshaApplyAddedBy']= $this->input->post('shikshaApplyAddedBy');
            $data['shikshaApplyAddedOn']= $this->input->post('shikshaApplyAddedAt');
            $data['transcriptEvaluationNeeded'] 	= $this->input->post('transcriptEvaluationNeeded');
            $data['isApplicationViaCommonApplication'] 	= $this->input->post('isApplicationViaCommonApplication');
            $data['commonApplicationDescription'] 	= $this->input->post('commonApplicationDescription_'.$formName);
            $data['internationStudentAddress'] 	= $this->input->post('internationStudentAddress_'.$formName);
        }



        $data['userComments'] = $this->input->post('userComments_'.$formName);
        $data['listingStatus'] = $this->input->post('listingStatus');
        if($data['listingStatus'] == 'live') {
            $data['listingStatus'] = ENT_SA_PRE_LIVE_STATUS;
        }

        $existingBrochureUrl = $this->input->post('existingBrochureUrl');
        $data['existingBrochureUrl'] = "";
        if($existingBrochureUrl != '') {
            $data['existingBrochureUrl'] = $existingBrochureUrl;
        }
        // custom fees & scholarship
        $customFeeNames  	 = $this->input->post('fee_field_name');
        $customFeeValues 	 = $this->input->post('fee_field_value');
        $customScholarshipNames  = $this->input->post('scholarship_field_name');
        $customScholarshipValues = $this->input->post('scholarship_field_value');
        $customValuesData = array("fees"=>array(),"scholarship"=>array());
        for($i=0; $i<count($customFeeNames); $i++)
        {
            array_push($customValuesData['fees'],array('caption'=>$customFeeNames[$i],'value'=>$customFeeValues[$i]));
        }
        for($i=0; $i<count($customScholarshipNames); $i++)
        {
            array_push($customValuesData['scholarship'],array('caption'=>$customScholarshipNames[$i],'value'=>$customScholarshipValues[$i]));
        }
        $data['customValuesData'] = $customValuesData;
        $data['seoTitle'] = $this->input->post('seoTitle');
        $data['seoKeywords'] = $this->input->post('seoKeywords');
        $data['seoDescription'] = $this->input->post('seoDescription');

        $data['roomBoard'] = trim($this->input->post('feeRoomBoard'));
        $data['insurance'] = trim($this->input->post('feeInsurance'));
        $data['transportation'] = trim($this->input->post('feeTransportation'));
        $data['scholarship']['description'] = trim($this->input->post('scholarshipDescription'));
        $data['scholarship']['link'] = trim($this->input->post('scholarshipMainLink'));
        $data['scholarship']['amount'] = trim($this->input->post('scholarshipAmount'));
        $data['scholarship']['currency'] = trim($this->input->post('scholarshipCurrency'));
        $data['scholarship']['eligibility'] = trim($this->input->post('scholarshipEligibility'));
        $data['scholarship']['deadline'] = trim($this->input->post('scholarshipDeadline'));

        return $data;
    }

    public function addSnapshotCourseForm()
    {
        // get the user data
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        $displayData = $this->cmsAbroadUserValidation();

        $displayData['formName'] 	= ENT_SA_FORM_ADD_SNAPSHOT_COURSE;
        $displayData['selectLeftNav']   = "SNAPSHOT_COURSE";

        $displayData['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();
        $displayData['lastAddedOn'] = $this->abroadPostingLib->getSnapshotLastAddedOnDate();
        $displayData['courseType'] = $this->abroadCommonLib->getAbroadCourseLevels();
        $this->_populateAbroadCountries($displayData);

        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function editSnapshotCourseForm($courseId)
    {
        // get the user data
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        $displayData = $this->cmsAbroadUserValidation();

        $displayData['formName'] 	= ENT_SA_FORM_EDIT_SNAPSHOT_COURSE;
        $displayData['selectLeftNav']   = "SNAPSHOT_COURSE";

        $displayData['courseId'] = $courseId;
        $displayData['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();
        $displayData['lastAddedOn'] = $this->abroadPostingLib->getSnapshotLastAddedOnDate();
        $displayData['courseType'] = $this->abroadCommonLib->getAbroadCourseLevels();
        $this->_populateAbroadCountries($displayData);

        $displayData['courseData'] = $this->abroadPostingLib->getSnapshotCourseDataForEdit($courseId);
        $universityDetails = $this->abroadCommonLib->getUniversityDetails($displayData['courseData']['university_id'], ENT_SA_PRE_LIVE_STATUS);
        $displayData['universityName'] = $universityDetails['name'];
        $subCatId = $displayData['courseData']['category_id'];

        $userModel = $this->load->model('user/usermodel');
        $userData = $userModel->getUserById($displayData['courseData']['lastModifiedBy']);
        $displayData['lastModifiedBy'] = $userData->getFirstName()." ".$userData->getLastName();
        $displayData['lastModified'] =  date("d/m/Y",strtotime($displayData['courseData']['last_modified']));

        $builderObj	= new CategoryBuilder;
        $repoObj 	= $builderObj->getCategoryRepository();
        $subCatObj 	= $repoObj->find($subCatId);
        $displayData['parentCat'] = $subCatObj->getParentId();

        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function addBulkSnapshotCourseForm()
    {
        die;
        // get the user data
        ini_set('max_execution_time','-1');

        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        $displayData = $this->cmsAbroadUserValidation();
        $UserId = $displayData['userid'];
        $comments= trim($this->input->post('comment'));

        $abroadCMSModelObj = $this->load->model('listingPosting/abroadcmsmodel');
        $LastUploadedData =  $abroadCMSModelObj->getbulkSnapshotAdditionTrackingEntry();
        if(!empty($LastUploadedData)){
            $UserModel = $this->load->model('user/usermodel');
            $UserData = $UserModel->getUserById($LastUploadedData['userId']);
            $displayData['lastuploadedBy'] = $UserData->getFirstName()." ".$UserData->getLastName();
            $displayData['lastUploaded'] =  date("d/m/Y",strtotime($LastUploadedData['updatedAt']));
        }
        $countries = $abroadCMSModelObj->getAbroadCountriesId();

        $error_msg;
        if(!empty($_FILES["file"])) {
            if($_FILES["file"]["type"] != "text/csv" && $_FILES["file"]["type"] !="application/csv" && $_FILES["file"]["type"] !="application/vnd.ms-excel") {
                $error_msg = "Please upload a file in above mentioned format.";
            } else{
                $fileName = $_FILES["file"]["name"];
                $file = fopen($_FILES["file"]["tmp_name"],"r");
                $uploadedData ;
                $noOfSnapshotCoursesInserted = 0;
                $count = 1;
                $snapShotTitle;
                $errorEntries;
                $traverseCSV = TRUE;
                while(! feof($file))
                {  $snapShotCoursesdata = fgetcsv($file,0,"|");

                    if($count==1)
                    {
                        if(!empty($snapShotCoursesdata)){
                            $noOfTitle = 0;
                            foreach($snapShotCoursesdata as $snapTitleColumn)
                            {

                                $columnTitle = trim(strtoupper($snapTitleColumn));

                                switch($columnTitle)
                                {
                                    case "COUNTRY ID" :
                                        $snapShotTitle[]= "COUNTRY ID";
                                        $noOfTitle++;
                                        break;

                                    case "UNIVERSITY NAME" :
                                        $snapShotTitle[]= "UNIVERSITY NAME";
                                        $noOfTitle++;
                                        break;

                                    case "COURSE EXACT NAME" :
                                        $snapShotTitle[]= "COURSE EXACT NAME";
                                        $noOfTitle++;
                                        break;

                                    case "COURSE TYPE" :
                                        $snapShotTitle[]= "COURSE TYPE";
                                        $noOfTitle++;
                                        break;

                                    case "PARENT CATEGORY" :
                                        $snapShotTitle[]= "PARENT CATEGORY";
                                        $noOfTitle++;
                                        break;

                                    case "CHILD CATEGORY" :
                                        $snapShotTitle[]= "CHILD CATEGORY";
                                        $noOfTitle++;
                                        break;

                                    case "COURSE WEBSITE LINK" :
                                        $snapShotTitle[]= "COURSE WEBSITE LINK";
                                        $noOfTitle++;
                                        break;

                                    default :
                                        $traverseCSV = false;
                                        $error_msg = "Invalid File or First Line in csv is not according to format.<BR> Please use '|' as a seprator";

                                }


                            }
                            if($noOfTitle != 7)
                            {
                                $error_msg = "Invalid File or First Line in csv is not according to format.<BR> Please use '|' as a seprator";
                                $traverseCSV = FALSE;
                            }
                        }
                        else {
                            $error_msg = "Invalid File or First Line in csv is not according to format.<BR> Please use '|' as a seprator";
                            $traverseCSV = FALSE;
                        }


                    }
                    if($traverseCSV && ($count > 1)) {
                        if(count($snapShotCoursesdata) == 7)
                        {
                            $COUNTRY_ID = trim($snapShotCoursesdata[array_search("COUNTRY ID", $snapShotTitle)]);
                            $UNIVERSITY_NAME = trim($snapShotCoursesdata[array_search("UNIVERSITY NAME", $snapShotTitle)]);
                            $COURSE_EXACT_NAME = trim($snapShotCoursesdata[array_search("COURSE EXACT NAME", $snapShotTitle)]);
                            $COURSE_TYPE = trim($snapShotCoursesdata[array_search("COURSE TYPE", $snapShotTitle)]);
                            $PARENT_CATEGORY = trim($snapShotCoursesdata[array_search("PARENT CATEGORY", $snapShotTitle)]);
                            $CHILD_CATEGORY = trim($snapShotCoursesdata[array_search("CHILD CATEGORY", $snapShotTitle)]);
                            $COURSE_WEBSITE_LINK = trim($snapShotCoursesdata[array_search("COURSE WEBSITE LINK", $snapShotTitle)]);
                            if(
                                empty($COUNTRY_ID)
                                || empty($UNIVERSITY_NAME)
                                || empty($COURSE_EXACT_NAME)
                                || empty($COURSE_TYPE)
                                || empty($PARENT_CATEGORY)
                                || empty($CHILD_CATEGORY)
                                || empty($COURSE_WEBSITE_LINK)
                            )
                            {

                                $errorEntries[$count]["error_msg"] = "All the column should have value.";
                                $errorEntries[$count]["data"] = $snapShotCoursesdata;
                            }else{

                                $key= array_search("Country Id", $snapShotTitle);
                                if(!empty($snapShotCoursesdata)){

                                    if(in_array(trim($snapShotCoursesdata[array_search("COUNTRY ID", $snapShotTitle)]),explode(',',$countries['countriesId']))){
                                        $uploadedData[$snapShotCoursesdata[array_search("COUNTRY ID", $snapShotTitle)]][trim($snapShotCoursesdata[array_search("UNIVERSITY NAME", $snapShotTitle)])] [$count] = array(
                                            "Country_Id" => trim($snapShotCoursesdata[array_search("COUNTRY ID", $snapShotTitle)]),
                                            "University_Name" => trim($snapShotCoursesdata[array_search("UNIVERSITY NAME", $snapShotTitle)]),
                                            "Course_Exact_Name" =>	trim($snapShotCoursesdata[array_search("COURSE EXACT NAME", $snapShotTitle)]),
                                            "Course_Type"	 => trim($snapShotCoursesdata[array_search("COURSE TYPE", $snapShotTitle)]),
                                            "Parent_Category" => trim($snapShotCoursesdata[array_search("PARENT CATEGORY", $snapShotTitle)]),
                                            "Child_Category" => trim($snapShotCoursesdata[array_search("CHILD CATEGORY", $snapShotTitle)]),
                                            "Course_website_link" => trim($snapShotCoursesdata[array_search("COURSE WEBSITE LINK", $snapShotTitle)])
                                        );
                                    }else {
                                        $errorEntries[$count]["error_msg"] = "Invalid Country Id";
                                        $errorEntries[$count]["data"] = $snapShotCoursesdata;
                                    }
                                }
                            }
                        }
                        else{
                            $errorEntries[$count]["error_msg"] = "Invalid no of Values in Row";
                            $errorEntries[$count]["data"] = $snapShotCoursesdata;
                        }
                    }
                    $count++;
                }

                $result = $this->abroadPostingLib->addBulkSnapshotCourse($uploadedData,$UserId,$comments);
                fclose($file);
                if(empty($errorEntries)){
                    $errorEntries = $result['errorEntries'];
                }elseif(!empty($result['errorEntries'])){
                    $errorEntries =  $errorEntries + $result['errorEntries'];
                }

                $noOfSnapshotCoursesInserted = $result['noOfSnapshotCoursesInserted'];
                unset($result);
            }
        }
        else{
            $error_msg = "Please upload a CSV File";
        }
        $displayData['showErrorLogLink'] = count($errorEntries) >=1 ? true : false ;
        $logFilePath = "/var/www/html/shiksha/mediadata/text/".$fileName."_log";
        unlink($logFilePath); //delete log file if already exists
        $noOfCoursesFailed = 0;
        foreach($errorEntries as $errorLine => $errorEntry)
        { $errorString = " Line No : ".$errorLine;
            foreach($errorEntry['data'] as $errorEntryData)
            {
                $errorString = $errorString."|".$errorEntryData;
            }
            $errorString = $errorString."| Error Message - ".$errorEntry['error_msg']."\n";
            error_log($errorString,3,"/var/www/html/shiksha/mediadata/text/".$fileName."_log");
            $noOfCoursesFailed ++;

        }
        if($noOfCoursesFailed < 1 && $noOfSnapshotCoursesInserted < 1 && $traverseCSV){
            $error_msg = "No Data Found to upload.";
        }
        $displayData['noOfCoursesFailed'] = $noOfCoursesFailed;
        $displayData['uploadErrorMsg'] = $error_msg;
        $displayData['noOfSnapshotCoursesInserted'] = $noOfSnapshotCoursesInserted;
        $displayData['formName'] 	= ENT_SA_FORM_ADD_BULK_SNAPSHOT_SOURES;
        $displayData['fileName'] = $fileName;
        $displayData['selectLeftNav']   = "SNAPSHOT_COURSE";
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);

    }

    public function downloadBulkSnapshotUploadFile($filename) {

        $file = '/var/www/html/shiksha/mediadata/text/'.$filename;

        if (file_exists($file)) {
            ob_start();
            header('Content-Description: File Transfer');
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }



    public function getMainAbroadCategories() {
        $this->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $repository = $categoryBuilder->getCategoryRepository();
        $mainCategories =  $repository->getSubCategories(1,'newAbroad');   // Main Abroad Categories..
        return $mainCategories;
    }

    function submitSnapshotCourse()
    {
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        $userDetails = $this->cmsAbroadUserValidation(true);

        $userValidation = $this->cmsAbroadUserValidation(true);
        if(!empty($userValidation['error']) && !empty($userValidation['error_type'])) {
            echo json_encode($userValidation);
            return;
        }

        $snapshotData['country_id'] 	= $this->input->post('countryId');
        $snapshotData['university_id'] 	= $this->input->post('universityId');
        $snapshotData['course_name'] 	= trim($this->input->post('courseName'));
        $snapshotData['course_type'] 	= $this->input->post('courseType');
        $snapshotData['category_id'] 	= $this->input->post('childCatId');
        $snapshotData['website_link']	= $this->input->post('courseWebsite');
        $snapshotData['comments'] 		= urldecode($this->input->post('comment'));

        foreach($snapshotData as $data)
        {
            if(empty($data))
            {
                $resp['error']['message'] = "Please fill all the mandatory fields.";
                echo json_encode($resp);
                return;
            }
        }
        $snapshotData['action'] = $this->input->post('action');

        if($snapshotData['action'] == 'edit') {
            $snapshotData['course_id'] = $this->input->post('courseId');
            $snapshotData['created'] = $this->input->post('created');
            $snapshotData['createdBy'] = $this->input->post('createdBy');
        }
        else {
            $snapshotData['createdBy'] = $userDetails['userid'];
        }

        $snapshotData['lastModifiedBy'] = $userDetails['userid'];
        // $result = $this->abroadPostingLib->addEditSnapshotCourse($snapshotData);
        return $result;
    }

    function checkAvailabilitySnapshotCourse()
    {
        die;
        $checkData['countryId']		= $this->input->post('countryId');
        $checkData['universityId']	= $this->input->post('universityId');
        $checkData['courseName']	= $this->input->post('courseName');
        $checkData['courseId']		= $this->input->post('courseId');
        $checkData['courseType']	= $this->input->post('courseType');

        $result = $this->abroadPostingLib->checkAvailabilitySnapshotCourse($checkData);

        if($result)
        {
            $resp['error']['message'] = "Course name already exists. Please enter a different name.";
            echo json_encode($resp);
            return;
        }
        else
        {
            $resp['message'] = "Course name available.";
            echo json_encode($resp);
            return;
        }
    }

    private function _populateAbroadCountries(& $displayData)
    {
        $countries = $this->locationRepository->getAbroadCountries();

        foreach($countries as $key => $country){
            if($country->getId() == 1) {
                unset($countries[$key]);
                break;
            }
        }

        //sort countries by name ascending order
        usort($countries,function($c1,$c2){
            return (strcasecmp($c1->getName(),$c2->getName()));
        });
        $displayData['abroadCountries'] = $countries;
    }

    /**
     * Purpose : View SnapShot Courses
     * Params  :	none
     * Author  : Abhinav
     */
    public function viewSnapshotCourseListing()
    {
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // get SnapShot Course And PageNo.
        $snapshotCourse	= $this->input->get("snapshotCourse");

        // If SnapShot Course is Not Filtered then show all Courses
        $snapshotCourse	= $snapshotCourse?$snapshotCourse:"";
        if($snapshotCourse=='Select Course'){
            $snapshotCourse="";
        }

        //Forming Relative URL
        $relativeUrl =	"/listingPosting/AbroadListingPosting/viewSnapshotCourseListing";
        if($snapshotCourse != ""){
            $relativeUrl .="/?snapshotCourse=".$snapshotCourse;
        }

        //Loading Libraries for Pagination
        $this->load->library('listingPosting/Paginator');
        $displayData['paginator']  = new Paginator($relativeUrl);

        // Get Lower Limit and Rows Per Page
        $lowerlimit = $displayData['paginator']->getLimitOffset();
        $rowsPerPage = $displayData['paginator']->getLimitRowCount();

        // model call for snapshot courses data
        $this->abroadModel = new abroadcmsmodel();
        // $snapshotCourseArr = $this->abroadModel->getSnapshotCourse($snapshotCourse,$lowerlimit,$rowsPerPage);
        $totalRows = array_pop($snapshotCourseArr);

        if(!empty($snapshotCourseArr)){

            //Getting Unique-Ids for Sub-Category for fetched data
            foreach($snapshotCourseArr as $snapshotCourseObj) {
                $snapShotCategoryIds[] = $snapshotCourseObj['category_id'];
            }
            array_unique($snapShotCategoryIds);

            //Loading CategoryBuilder for getting CategoryRepository
            $this->load->builder('CategoryBuilder','categoryList');
            $categoryBuilder = new CategoryBuilder;
            $categoryRepository = $categoryBuilder->getCategoryRepository();

            //fetch multiple objects for sub-category Ids
            $snapShotCategoryIds = $categoryRepository->findMultiple($snapShotCategoryIds);

            //Getting Unique-Ids for Parent-Category for fetched data
            foreach($snapShotCategoryIds as $snapShotCategoryId){
                $parentCategoryIds[] = $snapShotCategoryId->getParentId();
            }
            array_unique($parentCategoryIds);

            //fetch multiple objects for parent-category Ids
            $parentCategoryIds = $categoryRepository->findMultiple($parentCategoryIds);

            //Adding CategoryName and ParentCategoryName in View-Data
            foreach($snapshotCourseArr as &$snapshotCourseObj){
                $snapshotCourseObj['subCategory_name'] = $snapShotCategoryIds[$snapshotCourseObj['category_id']]->getName();
                $snapshotCourseObj['parentCategory_name'] = $parentCategoryIds[$snapShotCategoryIds[$snapshotCourseObj['category_id']]->getParentId()]->getName();

                //date formatting
                $snapshotCourseObj['date'] = date("j M Y",strtotime($snapshotCourseObj['date']));
            }
        }

        //Setting Total RowCount for Pagination
        $displayData['paginator']->setTotalRowCount($totalRows['totalRows']);

        // prepare the display date here
        $displayData['formName'] 	= ENT_SA_VIEW_LISTING_SNAPSHOT_COURSE;
        $displayData['selectLeftNav']   = "SNAPSHOT_COURSE";
        $displayData['snapshotCourseArr'] = $snapshotCourseArr;
        if($snapshotCourse == ''){
            $snapshotCourse='Select Course';
        }
        $displayData['snapshotCourse']	= $snapshotCourse;
        $displayData['totalRecords']	= $totalRows['totalRows'];


        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    /**
     * Purpose : none
     * Params  :	none
     * Author  : none
     */
    public function viewRankingListing()
    {
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // get RankName And PageNo.
        $rankName = ($this->input->get("searchRank"))?$this->input->get("searchRank"):"";
        $status = ($this->input->get("status"))?$this->input->get("status"):"";
        $rowsPerPage = ($this->input->get("resultPerPage"))?$this->input->get("rowsPerPage"):"";

        if($rankName == 'Search Ranking'){
            $rankName = "";
        }

        // prepare the query parameters coming
        $queryParams	= "";
        $queryParams   .= ($rankName ? "&searchRank=".$rankName : "");
        $queryParams   .= ($resultPerPage  ? "&resultPerPage=".$resultPerPage : "");
        $queryParams    = $queryParams 	   ? $queryParams : "";

        //Forming Relative URL
        $formURL = ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_RANKING."/";
        $relativeUrl = ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_RANKING."/?status=".$status.$queryParams;

        //Loading Libraries for Pagination
        $this->load->library('listingPosting/Paginator');
        $displayData['paginator']  = new Paginator($relativeUrl);

        // Get Lower Limit and Rows Per Page
        $lowerlimit = $displayData['paginator']->getLimitOffset();
        $rowsPerPage = $displayData['paginator']->getLimitRowCount();

        // library call for rank data
        $rankResultArr = $this->abroadPostingLib->getRankingDetails($rankName,$status,$lowerlimit,$rowsPerPage);
        $rankArr = $rankResultArr['rank_data'];
        $totalRows = $rankResultArr['total_count'];
        $tabsArr = $rankResultArr['tabs'];

        //check if result-set obtained is not empty
        if(!empty($rankArr)){
            foreach($rankArr as $rankRow){
                if($rankRow['subcategory_id']){
                    $subCategoryIds[] = $rankRow['subcategory_id'];
                }
                if($rankRow['country_id']){
                    $countryIds[] = $rankRow['country_id'];
                }
                if($rankRow['parentcategory_id']){
                    $parentCategoryIds[] = $rankRow['parentcategory_id'];
                }
            }
            //Loading CategoryBuilder for getting CategoryRepository
            $this->load->builder('CategoryBuilder','categoryList');
            $categoryBuilder = new CategoryBuilder;
            $categoryRepository = $categoryBuilder->getCategoryRepository();

            if(!empty($subCategoryIds)){

                //fetch multiple objects for sub-category Ids
                $categoryIds = $categoryRepository->findMultiple($subCategoryIds);

                //retriving parent categoryIds
                foreach($categoryIds as $categoryIdObj){
                    $parentCategoryIds[] = $categoryIdObj->getParentId();
                }
            }

            if(count($parentCategoryIds) >0){
                //unique parent categoryIds
                $parentCategoryIds = array_unique($parentCategoryIds);

                //fetch multiple objects for parent-category Ids
                $parentCategoryIds = $categoryRepository->findMultiple($parentCategoryIds);
            }

            //Get Country Details
            $countryIds = $this->abroadCmsModelObj->getAbroadCountries($countryIds);

            //get LDB Course Mapping Data
            $ldbCourseMapping = $this->abroadCommonLib->getAbroadMainLDBCourses();


            foreach($rankArr as &$rankRowObj){
                if($rankRowObj['subcategory_id'] !=0){
                    $rankRowObj['category_name'] = $parentCategoryIds[$categoryIds[$rankRowObj['subcategory_id']]->getParentId()]->getName();
                }elseif($rankRowObj['parentcategory_id'] !=0){
                    $rankRowObj['category_name'] = $parentCategoryIds[$rankRowObj['parentcategory_id']]->getName();
                }else{
                    $rankRowObj['category_name'] = "";
                }


                $rankRowObj['country_name'] = ($rankRowObj['country_id'])?$countryIds[$rankRowObj['country_id']]['name']:"";
                if(!$rankRowObj['category_name']){
                    foreach($ldbCourseMapping as $ldbCourse){
                        if($ldbCourse['SpecializationId'] == $rankRowObj['ldb_course_id']){
                            $rankRowObj['category_name'] = $ldbCourse['CourseName'];
                            break;
                        }
                    }
                }
                $rankRowObj['last_date'] = date("j M Y",strtotime($rankRowObj['last_date']));

            }
        }
        // prepare the display date here
        $displayData['formName'] 	= ENT_SA_VIEW_LISTING_RANKING;
        $displayData['selectLeftNav']   = "RANKING";
        $displayData['rankArr']   	= $rankArr;
        $displayData['searchTerm'] 	= $rankName;
        $displayData['formURL'] 	= $formURL;
        $displayData['displayDataStatus'] = $status;
        $displayData['totalResultCount']  = $tabsArr;
        $displayData['queryParams'] 	  = $queryParams;
        //Setting Total RowCount for Pagination
        $displayData['paginator']->setTotalRowCount($totalRows[0]['totalRows']);

        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    /**
     * Purpose : none
     * Params  :	none
     * Author  : none
     */
    public function advanceSearchUniversityForm()
    {
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // prepare the display date here
        $displayData['formName'] 	= ENT_SA_FORM_ADVANCE_SEARCH_UNIVERSITY;
        $displayData['selectLeftNav']   = "ADVANCE_SEARCH";

        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);

    }

    /**
     * Purpose : render addUniversityForm
     * Params  :	none
     * Author  : SRB
     */
    public function addUniversityForm()
    {
        // get the user data
        $this->usergroupAllowed = array('saAdmin','saCMS', 'cms','saCMSLead');
        $displayData = $this->cmsAbroadUserValidation();
        $expert_criteria=array(sortField=>'name',
                               sortFieldOrder=>'ASC',
                               is_active=>'active'); 

        $displayData['expertsList']= $this->expertProfileLib->getAllExperts($expert_criteria);
       // _p($displayData['expertsList']);
        // prepare the display data here
        $displayData['formName'] 		= ENT_SA_FORM_ADD_UNIVERSITY;
        $displayData['selectLeftNav']   = "UNIVERSITY";
        //countries required for country drop down in the university form
        $this->_populateAbroadCountries($displayData);
        //get currencies
        $displayData['currencies'] = $this->abroadCommonLib->getCurrencyList();
       
	   $displayData['applyIntakeFields'] = $this->abroadCommonLib->getApplyIntakeFields();
	   $displayData['abroadExamsMasterList'] = $this->abroadCommonLib->getAbroadExamsMasterList();
        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    /**
     * Purpose : render editUniversityForm
     * Params  :	none
     * Author  : SRB
     */
    public function editUniversityForm()
    {

        // get the user data
        $displayData = $this->cmsAbroadUserValidation();
        //get the univ id
        $universityId = $this->input->get("uniId");
        if(!($universityId > 0)){
            // if university id is invalid, show 404 error page
            show_404();
        }
       
        $displayData['formData'] = $this->abroadCommonLib->getUniversityDataForEditMode($universityId);

        $expert_criteria=array(sortField=>'name',
                               sortFieldOrder=>'ASC',
                               is_active=>'active'); 
        $displayData['expertsList']= $this->expertProfileLib->getAllExperts($expert_criteria);

        $displayData['previewLinkFlag'] = $this->abroadPostingLib->checkListingExistinGivenState($universityId,'university','live');
        // prepare the display date here
        $displayData['formName'] 	= ENT_SA_FORM_EDIT_UNIVERSITY;
        $displayData['selectLeftNav']   = "UNIVERSITY";
        //countries required for country drop down in the university form
        $this->_populateAbroadCountries($displayData);
        //get currencies
        $displayData['currencies'] = $this->abroadCommonLib->getCurrencyList();
	    $displayData['applyIntakeFields'] = $this->abroadCommonLib->getApplyIntakeFields();
	    $displayData['abroadExamsMasterList'] = $this->abroadCommonLib->getAbroadExamsMasterList();
//	    _p($displayData);die;
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    /**
     * Purpose : Check if city exists or not (for ajax purpose)
     * Params  :	none
     * Author  : Romil Goel
     */
    public function isCityExists()
    {
        // get post parameters
        $countryId = $this->input->post("country");
        $stateId   = $this->input->post("state");
        $cityName  = $this->input->post("city");

        // data massaging
        $countryId = isset($countryId) ? $countryId : 0;
        $stateId   = isset($stateId)   ? $stateId   : 0;
        $cityName  = isset($cityName)  ? $cityName  : '';

        // call library function to check if the provided city exists or not
        $cityAlreadyExistsFlag = $this->abroadCommonLib->isCityExists($cityName, $countryId, $stateId);

        echo $cityAlreadyExistsFlag;
    }

    /**
     * Purpose : Save city(s) in database
     * Params  :	none
     * Author  : Romil Goel
     */
    public function addCityAction()
    {
	$this->usergroupAllowed = array('saCMS','saAdmin','saCMSLead');
	$userData = $this->cmsAbroadUserValidation();

	// get the post paramaters
	$countryArr 	= $this->input->post("countryPL");
	$stateArr   	= $this->input->post("statePL");
	$cityTierArr   	= $this->input->post("cityTierPL");
	$cityArr  		= $this->input->post("cityTB");
	$additionalPostParams = $this->_getAdditionalPostParams();
        if(!is_array($additionalPostParams)){
            echo json_encode(array("status" => 0,"msg" => $additionalPostParams));
            exit;
        }
	// save each city
	foreach( $countryArr as $key=>$countryId)
	{
	    $stateId  = empty($stateArr[$key]) ? -1 : $stateArr[$key];
	    $cityTier = empty($cityTierArr[$key]) ? 1 : $cityTierArr[$key];
	    $this->abroadCommonLib->addCity($countryId, $stateId, trim($cityArr[$key]), $userData['userid'], $cityTier, $additionalPostParams);
	}

	// redirect the user to the view city page
        echo json_encode(array("status" => 1,"msg" => "city successfully saved"));
        exit;
//	header('Location:'.ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CITY."?msgType=1");
    }

    private function _getAdditionalPostParams(){
    	$postData = array();

    	$postData['latitude']  		= $this->input->post("Latitude");
		$postData['longitude'] 		= $this->input->post("Longitude");

		$postData['latitudeDirection'] 	= $this->input->post("latitudeDir");
		$postData['longitudeDirection'] 	= $this->input->post("longitudeDir");

		$postData['citySize'] 	= $this->input->post("citySize");

		$postData['minTemp'] 	= $this->input->post("minTemp");
		$postData['maxTemp'] 	= $this->input->post("maxTemp");

		$postData['wikiPageUrl'] 	= $this->input->post("wikiUrl");

		$postData['offCampusAccommodationDesc']	= $this->input->post("offCampusAccommodationDesc");
		$postData['offCampusAccommodationUrl']	= $this->input->post("offCampusAccommodationUrl");

		$postData['cityPopulation']	= $this->input->post("cityPopulation");



		$postData['videoTitle']	= array_map('trim',$this->input->post('videoTitle'));
		$postData['videoUrl']	= array_map('trim',$this->input->post('videoUrl'));

		if(!empty($postData['videoUrl'][0])) {
            $data = $this->getCityVideoThumbUrl($postData);
            if(!is_array($data)){
                return $data;
            }
        }
        return $postData;
    }

    private function getCityVideoThumbUrl(&$postData){
        $this->load->library('upload_client');
        $uploadClient = new Upload_client();
        $appId = 1;
        $mediaDataType = "ytvideo";
        $FILES = $postData['videoUrl'];
        $finalThumbUrl = array();
//        $fileCaption
        $upload_forms = $uploadClient->uploadFile($appId,$mediaDataType,$FILES,array(),-1, 'city','videoUrls');
        if(is_array($upload_forms)) {
            if($upload_forms['status'] == 1){
                for($k = 0;$k < $upload_forms['max'] ; $k++){
                    $postData['thumbUrl'][$k] = $upload_forms[$k]['thumburl'];
                    $postData['videoUrl'][$k] = $upload_forms[$k]['imageurl'];
                    $postData['mediaId'][$k] = $upload_forms[$k]['mediaid'];
                }
            }
            else{
                return $upload_forms;
            }
        }
        else{
            return $upload_forms;
        }
        return $postData;
    }


    public function editCityAction()
    {
        $this->usergroupAllowed = array('saCMS','saAdmin','saCMSLead');
        $userData = $this->cmsAbroadUserValidation();
        // get the post paramaters
        $newInfo = array();
        $newInfo['countryId']  	    = $this->input->post("countryPL")[0];
        $newInfo['stateId']  	    = $this->input->post("statePL")[0];
        $newInfo['cityName']  		= $this->input->post("cityTB")[0];
        $newInfo['cityId']          = $this->input->post("cityId");
        $oldCityName                = $this->input->post("oldCityName");
        $oldStateId                 = $this->input->post("oldStateId");
        $oldCountryId               = $this->input->post("oldCountryId");
        $updateCountryCityTable     = false;
        if($newInfo['cityName'] != $oldCityName || $newInfo['stateId'] != $oldStateId || $newInfo['countryId'] != $oldCountryId){
            $updateCountryCityTable = true;
        }
        $newInfo['additionalCityFields'] = $this->_getAdditionalPostParams();
        if(!is_array($newInfo['additionalCityFields'])){
            echo json_encode(array("status" => 0,"msg" => $newInfo['additionalCityFields']));
            exit;
        }
        $newInfo['stateId']  = empty($newInfo['stateId']) ? -1 : $newInfo['stateId'];
        $newInfo['tier']  = empty($newInfo['tier']) ? 1 : $newInfo['tier'];
//        _p($newInfo);die;
        $this->abroadCommonLib->updateCity($newInfo, $userData['userid'], $updateCountryCityTable);

        //delete the cache for abroadCity whenever data is updated
        $this->load->builder('LocationBuilder','Common/location');
        $locationBuilder = new LocationBuilder();
        $locationBuilder->getLocationCache()->deleteAbroadCityCache($newInfo['cityId']);

        echo json_encode(array("status" => 1,"msg" => "city successfully saved"));
        exit;
    }
    //this function populates universities for consultant based on excluded courses
    public function getUniversitiesForCountry() {
		$countryId 			= $this->input->post("countryId");//$_POST['countryId'];
		$excludeCollege		= $this->input->post("excludeCollege");//$_POST['excludeCollege'];
		$excludeCollege		= empty($excludeCollege)?0:(int)$excludeCollege;
		$freeOnly			= $this->input->post("freeOnly");
		$freeOnly 			= empty($freeOnly)?0:(int)$freeOnly;

		$removeRmcCounsellorUniversityFlag		= $this->input->post("removeRmcCounsellorUniversity");
		$removeRmcCounsellorUniversityFlag 		= empty($removeRmcCounsellorUniversityFlag)?0:(int)$removeRmcCounsellorUniversityFlag;

		$this->abroadCMSModelObj 	= $this->load->model('listingPosting/abroadcmsmodel');
	    $universities 				= $this->abroadCMSModelObj->getUniversitiesForCountry($countryId,$excludeCollege);

		$dataArray = array();
		foreach($universities as $key => $university) {
			$dataArray[$university['university_id']] = $university['university_name'];
		}
		if($freeOnly == 1){
		    $dataArray = $this->_removePaidUniversities($dataArray,$countryId);
		}
		//whether that university has shiksha apply enabled on it
		if($removeRmcCounsellorUniversityFlag==1){
			$dataArray = $this->_removeRmcCounsellorMappedUniversities($dataArray);
		}

        $this->abroadCMSModelObj 	= $this->load->model('listingPosting/abroadcmsmodel');
        $universities 				= $this->abroadCMSModelObj->getUniversitiesForCountry($countryId,$excludeCollege);

        $dataArray = array();
        foreach($universities as $key => $university) {
            $dataArray[$university['university_id']] = $university['university_name'];
        }
        if($freeOnly == 1){
            $dataArray = $this->_removePaidUniversities($dataArray,$countryId);
        }
        //whether that university has shiksha apply enabled on it
        if($removeRmcCounsellorUniversityFlag==1){
            $dataArray = $this->_removeRmcCounsellorMappedUniversities($dataArray);
        }

        echo json_encode($dataArray);
    }

    public function checkDepartmentNameForUniquenessInUniversity() {
        $department = array();
        $departmentName 		= trim($this->input->post('departmentName'));
        $universityId 			= trim($this->input->post('universityId'));
        $departmentId 			= trim($this->input->post('departmentId'));
        if(empty($departmentName) || empty($universityId)){
            $department['error'] = "true";
            echo json_encode($department);
            return;
        }
        $abroadCMSModelObj 		= $this->load->model('listingPosting/abroadcmsmodel');
        $department 			= $this->abroadCmsModelObj->checkDepartmentNameForUniquenessInUniversity($departmentName, $universityId, array('draft', ENT_SA_PRE_LIVE_STATUS), $departmentId);
        echo json_encode($department);
    }

    public function editDepartmentForm($departmentId = NULL)
    {
        $this->init();
        $displayData = $this->cmsAbroadUserValidation();
        $departmentDetails 					= $this->abroadPostingLib->getDepartmentEditInformation($departmentId);
        $displayData['formName'] 			= ENT_SA_FORM_EDIT_DEPARTMENT;
        $displayData['selectLeftNav']   	= "DEPARTMENT";
        $displayData['action']   			= "EDIT";
        $displayData['department_details'] 	= $departmentDetails;
        $displayData['seo_url'] 	= $departmentDetails['listing_seo_url'];
        $displayData['previewLinkFlag'] = $this->abroadPostingLib->checkListingExistinGivenState($departmentId,'institute','live');
        $this->load->view('listingPosting/abroad/abroadCMSOverview', $displayData);
    }

    public function addDepartmentForm()
    {
        $this->init();
        $displayData = $this->cmsAbroadUserValidation();
        $displayData['university_from_url'] = "false";

        $this->_populateAbroadCountries($displayData);
        $universityId = $this->input->get('uniId');
        if(isset($universityId) && !empty($universityId)) {
            $universityDetails = $this->abroadCommonLib->getUniversityDetails($universityId, ENT_SA_PRE_LIVE_STATUS);
            if(!empty($universityDetails)) {
                $universityLocationInfo = $this->abroadCmsModelObj->getUniversityLocationInfo($universityId);
                $country = $this->abroadCmsModelObj->getAbroadCountries($universityLocationInfo['country_id']);
                $universityDetails['country_id'] 	= $universityLocationInfo['country_id'];
                $universityDetails['country_name'] 	= $country[$universityLocationInfo['country_id']]['name'];
                $displayData['university_from_url'] = "true";
                $displayData['university_details'] = $universityDetails;
            }
        }
        // prepare the display date here
        $displayData['formName'] 		= ENT_SA_FORM_ADD_DEPARTMENT;
        $displayData['selectLeftNav']   = "DEPARTMENT";
        $displayData['action']   		= "ADD";
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function postDepartmentForm() {
        $userValidation = $this->cmsAbroadUserValidation(true);
        if(!empty($userValidation['error']) && !empty($userValidation['error_type'])) {
            echo json_encode($userValidation);
            return;
        }

        $params = array();
        $params['countryId'] 					= trim($this->input->post('countryId'));
        $params['universityId'] 				= trim($this->input->post('universityId'));
        $params['departmentWebsite'] 			= trim($this->input->post('departmentWebsite'));
        $params['accreditationDetails'] 		= trim($this->input->post('accreditationDetails'));
        $params['schoolName']					= trim($this->input->post('schoolName'));
        $params['schoolAcronym'] 				= trim($this->input->post('schoolAcronym'));
        $params['schoolDescription'] 			= trim(str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post("schoolDescription"))));
        $params['contactPersonName'] 			= trim($this->input->post('contactPersonName'));
        $params['contactEmail'] 				= trim($this->input->post('contactEmail'));
        $params['contactPhoneNo'] 				= trim($this->input->post('contactPhoneNo'));
        $params['facultyPageUrl'] 				= trim($this->input->post('facultyPageUrl'));
        $params['alumniPageUrl'] 				= trim($this->input->post('alumniPageUrl'));
        $params['fbPageUrl'] 					= trim($this->input->post('fbPageUrl'));
        $params['action'] 						= trim($this->input->post('action')) == 'EDIT' ? 'EDIT' : 'ADD';
        $params['department_id'] 				= trim($this->input->post('department_id'));
        $params['userComments'] 				= trim($this->input->post('comments'));
        $params['userId'] 						= $userValidation['userid'];
        $btnPressed 							= trim($this->input->post('btnPressed'));
        $params['departmentSubmitDate'] 		= trim($this->input->post('submit_date'));
        $params['old_institute_location_id'] 		= trim($this->input->post('old_institute_location_id'));

        $params['seo_title'] 		= trim($this->input->post('seo_title'));
        $params['seo_keywords'] 		= trim($this->input->post('seo_keywords'));
        $params['seo_description'] 		= trim($this->input->post('seo_description'));
        $params['seo_url'] 		= trim($this->input->post('seo_url'));


        if($params['action'] == 'ADD'){
            $params['department_id'] = Modules::run('common/IDGenerator/generateId', 'institute');
            $university = reset($this->abroadCmsModelObj->getUniversityName($params['universityId'],$params['countryId']));
            $country = reset($this->abroadCmsModelObj->getAbroadCountries(array($params['countryId'])));
            $params['seo_url'] = $this->abroadPostingLib->getDepartmentUrl($params['department_id'], $params['schoolName'], $university['name'], $country['name']);
        }
        $params['seo_url'] = str_replace(SHIKSHA_STUDYABROAD_HOME,'', $params['seo_url']);
        if($btnPressed == 'save') {
            $params['status'] = 'draft';
        } else if($btnPressed == 'publish'){
            $params['status'] = 'live';
        }

        $returnArray = array();
        if(( $params['action'] == 'ADD' || $params['action'] == 'EDIT') && $params['status'] == 'draft') {
            if(	empty($params['universityId']) ||
                empty($params['schoolName']) ||
                empty($params['department_id'])
            ) {
                $returnArray['error'] 		= "true";
                $returnArray['error_type'] 	= "Please fill all the mandatory fields properly";
                echo json_encode($returnArray);
                return;
            }
        }

        if( $params['status'] == 'live') {
            if(
                empty($params['universityId']) ||
                empty($params['departmentWebsite']) ||
                empty($params['schoolName']) ||
                empty($params['department_id']) ||
                empty($params['userComments']) ||
                empty($params['schoolDescription'])) {
                $returnArray['error'] 		= "true";
                $returnArray['error_type'] 	= "Please fill all the mandatory fields properly";
                echo json_encode($returnArray);
                return;
            }
        }

        $highLowFieldValues 				= $this->abroadCommonLib->getDepartmentHighAndLowFields($params);
        $params['percentageCompletion'] 	= $this->abroadCommonLib->calculatePercentageCompletion($highLowFieldValues['high_field_values'], $highLowFieldValues['low_field_values']);
        $postingFlag = $this->abroadPostingLib->postDepartmentForm($params);
        if($postingFlag){
            $returnArray = array();
            $returnArray['success'] = "true";
        }
        echo json_encode($returnArray);
    }

    public function getDepartmentsForUniversity() {
        $universityId = $_POST['universityId'];
        $abroadCMSModelObj = $this->load->model('listingPosting/abroadcmsmodel');
        $institutes = $abroadCMSModelObj->getDepartmentsForUniversity($universityId);

        $dataArray = array();
        foreach($institutes as $key => $institute) {
            if($institute['is_dummy'] == 1){
                $dataArray[$institute['institute_id']] = "-1";
            }
            else{
                $dataArray[$institute['institute_id']] = $institute['institute_name'];
            }
        }

        echo json_encode($dataArray);
    }

    /**
     * Purpose : get cities for a country
     * Params  :	countryId (post)
     * Author  : SRB
     */
    public function getCitiesByCountry()
    {
        $countryId = $this->input->post('countryId');
        $cities = $this->abroadCommonLib->_getCitiesByCountry($countryId);
        $cityOptionHtml = "";
        foreach($cities as $city)
        {
            $cityOptionHtml .= '<option value="'.$city['city_id'].'">'.$city['city_name'].'</option>';
        }
        echo  json_encode($cityOptionHtml);
    }

    /**
     * Purpose : get states for a country
     * Params  :	countryId (post)
     * Author  : SRB
     */
    public function getStatesByCountry()
    {
        $countryId = $this->input->post('countryId');
        $states = $this->abroadCommonLib->_getStatesByCountry($countryId);
        $stateOptionHtml = "";
        foreach($states as $state)
        {
            $stateOptionHtml .= '<option value="'.$state['state_id'].'">'.$state['state_name'].'</option>';
        }
        echo  json_encode($stateOptionHtml);
    }

    /**
     * Purpose : get cities for a state
     * Params  :	stateId (post)
     * Author  : SRB
     */
    public function getCitiesByState()
    {
        $stateId = $this->input->post('stateId');
        $cities = $this->abroadCommonLib->_getCitiesByState($stateId);
        $cityOptionHtml = "";
        foreach($cities as $city)
        {
            $cityOptionHtml .= '<option value="'.$city['city_id'].'">'.$city['city_name'].'</option>';
        }
        echo  json_encode($cityOptionHtml);
    }

    /**
     * Purpose : check if University exists by the same name
     * Params  :	(univName)
     * Author  : SRB
     */
    function doesUniversityExists($univName,$univCountryId)
    {
        $univName = $this->input->post('univName');
        $univCountryId = $this->input->post('univCountry');
        $univId = $this->input->post('univId');
        echo json_encode($this->abroadCommonLib->doesUniversityExist($univName,$univCountryId,$univId ));
    }

    /**
     * Purpose : save University Form Data
     * Params  :	(post)
     * Author  : SRB
     */
    public function saveUniversityFormData()
    {
        // user data
        $userDetails = $this->cmsAbroadUserValidation();
        // app id for uploads kept 1 by default
        $appId = 1;

        $ListingClientObj = new Listing_client();
        $data = array();
        //get all data from form
        $data = $this->postDataForUniversity();
        
        if(strlen(strip_tags($data['announcementCallToAction'])) > 60) {
            $error['Fail']['callToActionText'] = "Please enter maximum 60 characters";
            echo json_encode($error);
            exit;
        }

        if($data['announcementCheck'] == 'on') {
            $startDate = new DateTime($data['announcementStartDate']);
            $endDate = new DateTime($data['announcementEndDate']);
            if($startDate > $endDate) {
                $error['Fail']['date'] = "Please select start date prior to end date";
                echo json_encode($error);
                exit;
            }
        }

        //$data['editedBy'] = $userDetails['userid']; edit mode
        $data['createdBy'] = $userDetails['userid'];
        $data['group_to_be_checked'] = $userDetails['usergroup'];

        // perform university logo , images , youtube video link
        // let the upload happen even in case of edit mode(if no file was choosen, the upload wont take place anyways)
        $logoAndPhotosResp = $this->univLogoPhotoUpload('university','univLogo','univPictures');//instiLogoAndPanelUpload();
        // perform brochure validation
        if($data['univBrochureSavedLink']==""){ //this field wil be available when we save a form in edit mode, & brochure link is not changed
            $requestBrochureResp = $this->listingBrochureUpload('university','univBrochureLink');
            if(array_key_exists('Fail', $requestBrochureResp)) {
                echo json_encode($requestBrochureResp);
                exit;
            }
            $data['institute_request_brochure_link'] = $requestBrochureResp;
        }
        else{ // previously saved brochure link saved again.
            $data['institute_request_brochure_link'] = $data['univBrochureSavedLink'];
        }

        $exitFlag = false;
        $logoPhotoRespArray = array();
        // if logo was not changed in edit mode, we will use the same logo url
        if($data['univLogoMediaUrl']!=""){
            $data['logoArr']['url']= $data['univLogoMediaUrl'];
        }
        else{ //other wise the uploaded file will be saved
            $data['logoArr'] = $logoAndPhotosResp['logoArr'];
            if(isset($logoAndPhotosResp['logoArr']['error'])) {
                $logoPhotoRespArray ["Fail"]['logo'] = 'Only '. $logoAndPhotosResp['logoArr']['error'];
                $exitFlag = true;
            }
            if ((isset($logoAndPhotosResp['logoArr'])) && (count($logoAndPhotosResp['logoArr']) > 0)) {

                $logoRespWidth = (int)$logoAndPhotosResp['logoArr']['width'];
                $logoRespHeight = (int)$logoAndPhotosResp['logoArr']['height'];

                if($logoAndPhotosResp['logoArr']['error'] != "")
                {
                    $logoPhotoRespArray["Fail"]['logo'] = $logoAndPhotosResp['logoArr']['error'];
                    $exitFlag = true;
                }
            }
        }

        // incase of pictures since even one can be removed & new can be added
        $data['pictureArr'] = $logoAndPhotosResp['pictureArr'];


        if(isset($logoAndPhotosResp['pictureArr']['sizecheckerror'])){
            $logoPhotoRespArray ["Fail"]['photo'] = $logoAndPhotosResp['pictureArr']['sizecheckerror'];
            $exitFlag = true;
        }else if(isset($logoAndPhotosResp['pictureArr']['error'])) {
            $logoPhotoRespArray ["Fail"]['photo'] = 'Only '. $logoAndPhotosResp['pictureArr']['error'];
            $exitFlag = true;
        }
        //keep the media ids for old images as well in ($data['univPicturesMediaId])
        if($exitFlag) {
            echo json_encode($logoPhotoRespArray);
            exit;
        }
        // we have recreated original links from the saved ones hence , reuploading shouldn't be a problem
        $data['videoArr'] = $this->uploadVideoLinkSA("form_".$data["univActionType"],"videos",$data['univVideoLink']);

        $highLowFieldValues 	= $this->abroadCommonLib->getUniversityHighAndLowFields($data);
        $percentage_completion 	= $this->abroadCommonLib->calculatePercentageCompletion($highLowFieldValues['high_field_values'], $highLowFieldValues['low_field_values']);
        $data['profile_percentage_completion'] = $percentage_completion;

        $result = $this->abroadPostingLib->addEditUniversityData($data);
        if($data['oldUnivId']>0)
        {
            // purge BE nginx cache
            $shikshamodel = $this->load->model("common/shikshamodel");
            $arr = array("cache_type" => "htmlpage", "entity_type" => "saUlp", "entity_id" => $data['oldUnivId'], "cache_key_identifier" => "");
            $shikshamodel->insertCachePurgingQueue($arr);
            $arr['entity_type']= "saAcp";
            $shikshamodel->insertCachePurgingQueue($arr);
        }
        echo $result ;

    }

    /*
     * function to upload media.. brochure,images,youtube videos..
     */
    function uploadVideoLinkSA($formId,$mediaType,$univVidLinks) {
        $this->init();
        $appId = 1;
        $this->load->library('listing_client');
        $ListingClientObj = new Listing_client();
        // caption for file name
        //$fileCaption= $this->input->post('fileNameCaption');

        //institute location id
        //$institute_location_id = $this->input->post('institute_location_id');
        $fileName = split("[/\\.]",$_FILES['univVideoLink']['name'][0]);
        $fileExtension = $fileName[count($fileName) - 1];
        $fileCaption .= $fileExtension == '' ? '' : '.'. $fileExtension;
        $listingId = $this->input->post('listingId');
        $listingType = $this->input->post('listingType');
        $this->load->library('upload_client');
        $uploadClient = new Upload_client();
        $this->load->library('Listing_media_client');
        $ListingMediaClientObj= new Listing_media_client();
        error_log(print_r($_POST,true));
        error_log(print_r($_FILES,true));


        $mediaDataType = 'ytvideo';
        $listingMediaType = 'videos';
        $FILES = $univVidLinks;//$_POST['univVideoLink'];

        $upload_forms = $uploadClient->uploadFile($appId,$mediaDataType,$FILES,array($fileCaption),-1, 'university','univVideoLink[]');
        // uploadFile($appId,'image',$_FILES,$inst_logo,"-1","institute",'i_insti_logo');
        $displayData =array();
        if(is_array($upload_forms) && !empty($upload_forms)) {
            $updateListingMedia = null;
            if($upload_forms['status'] == 1){
                for($k = 0;$k < $upload_forms['max'] ; $k++){
                    //It will always be 1 :-). Added for future cases if multiple uploads will be asked in one go.
                    $reqArr = array();
                    $reqArr['mediaId']=$upload_forms[$k]['mediaid'];
                    $reqArr['mediaUrl']=$upload_forms[$k]['imageurl'];
                    $reqArr['mediaName']=$upload_forms[$k]['title'];
                    $reqArr['mediaThumbUrl']=$upload_forms[$k]['thumburl'];
                    $reqArr['institute_location_id'] = $institute_location_id;
                    $updateListingMedia = $ListingMediaClientObj->mapMediaContentWithListing($appId,$listingId,$listingType,$listingMediaType,base64_encode(json_encode($reqArr)));
                    $displayData[$upload_forms[$k]['mediaid']] = array(
                        'fileId' => $reqArr['mediaId'],
                        'fileName' => $fileCaption,
                        'mediaType' => $mediaType,
                        'fileUrl' => $reqArr['mediaUrl'],
                        'fileThumbUrl' => $reqArr['mediaThumbUrl']
                    );
                }
            }
        } else {
            $displayData['error'] = $upload_forms;
        }
        //error_log("displayData::::".print_r($displayData,true));
        return ($displayData);
    }

    private function postDataForUniversity()
    {
		//receive form data
		$univFormData["oldSubmitDate"			] = $this->input->post("oldSubmitDate");
		$univFormData["oldUnivId"			] = $this->input->post("oldUnivId");
		$univFormData["oldUnivLocationId"		] = $this->input->post("oldUnivLocationId");
		$univFormData["univName"			] = $this->input->post("univName");
		$univFormData["univCountry"			] = $this->input->post("univCountry");
        $univFormData["univExpert"         ] = $this->input->post("univExpert");
		$univFormData["univLogo"			] = $this->input->post("univLogo");
		$univFormData["univLogoMediaUrl"		] = $this->input->post("univLogoMediaUrl");
		$univFormData["univEstablishedYear"		] = $this->input->post("univEstablishedYear");
		$univFormData["univAcronym"			] = $this->input->post("univAcronym");
        $univFormData["univWiki"             ] = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post("univWiki")));
		$univFormData["univUSP"				] = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post("univUSP")));
		$univFormData["instituteType1"			] = $this->input->post("instituteType1");
		$univFormData["instituteType2"			] = $this->input->post("instituteType2");
		$univFormData["univAffiliation"			] = $this->input->post("univAffiliation");
		$univFormData["univAccreditation"		] = $this->input->post("univAccreditation");
		$univFormData["univContactEmail"		] = $this->input->post("univContactEmail");
		$univFormData["univContactPhone"		] = $this->input->post("univContactPhone");
		$univFormData["univContactAddress"		] = $this->input->post("univContactAddress");
		$univFormData["univContactWebsite"		] = $this->input->post("univContactWebsite");
		$univFormData["univPictures"			] = $this->input->post("univPictures");
		$univFormData["univPicturesMediaId"		] = $this->input->post("univPicturesMediaId");
		$univFormData["univPicturesMediaUrl"		] = $this->input->post("univPicturesMediaUrl");
		$univFormData["univPicturesMediaThumbUrl"	] = $this->input->post("univPicturesMediaThumbUrl");
		$univFormData["univPictureCaptionSet"		] = $this->input->post("univPictureCaptionSet");
		$univFormData["univPictureCaption"		] = $this->input->post("univPictureCaption");
		$univFormData["univVideoLink"			] = array_values(array_filter($this->input->post("univVideoLink"),"trim"));
		$univFormData["univVideoCaption"			] = $this->input->post("univVideoCaption");
		$univFormData["univFBPage"			] = $this->input->post("univFBPage");
		$univFormData["univWebLink"			] = $this->input->post("univWebLink");
		$univFormData["univState"			] = $this->input->post("univState");
		$univFormData["univCity"			] = $this->input->post("univCity");
		$univFormData["univAdmissionContact"		] = $this->input->post("univAdmissionContact");
		$univFormData["univDeptName"			] = $this->input->post("univDeptName");
		$univFormData["univDeptWebsite"			] = $this->input->post("univDeptWebsite");
		$univFormData["univAccomodationDetail"		] = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post("univAccomodationDetail")));//html_entity_decode($this->input->post("univAccomodationDetail"));
		$univFormData["univAccomodationLink"		] = $this->input->post("univAccomodationLink");
		$univFormData["univLivingExpense"		] = $this->input->post("univLivingExpense");
		$univFormData["univCurrency"			] = $this->input->post("univCurrency");
		$univFormData["univLivingExpenseDescription"	] = $this->input->post("univLivingExpenseDescription");
		$univFormData["univLivingExpenseLink"		] = $this->input->post("univLivingExpenseLink");
		$univFormData["univBrochureLink"		] = $this->input->post("univBrochureLink");
		$univFormData["univBrochureSavedLink"		] = $this->input->post("univBrochureSavedLink");
		$univFormData["univCampusName"			] = $this->input->post("univCampusName");
		$univFormData["univCampusWebsite"		] = $this->input->post("univCampusWebsite");
		$univFormData["univCampusAddress"		] = $this->input->post("univCampusAddress");
		$univFormData["univIndianConsultant"		] = $this->input->post("univIndianConsultant");
		$univFormData["univInternationalStudentsLink"	] = $this->input->post("univInternationalStudentsLink");
		$univFormData["univAsianStudentsLink"	] = $this->input->post("univAsianStudentsLink");
		$univFormData["univUserComments"		] = $this->input->post("univUserComments");
		$univFormData["univSaveMode"			] = $this->input->post("univSaveMode");
		$univFormData["oldUnivSaveMode"			] = $this->input->post("oldUnivSaveMode");
		$univFormData["univActionType"			] = $this->input->post("univActionType");
		$univFormData["listings_main_id"		] = $this->input->post("listings_main_id");

		$univFormData["announcementCheck"] 		  = $this->input->post("addAnnouncement");
		$univFormData["announcementText"] 		  = $this->input->post("announcementText");
		$univFormData["announcementCallToAction"] = $this->input->post("announcementCallToAction");
		$univFormData["announcementStartDate"] 		  = $this->input->post("startDateDisplay");
		$univFormData["announcementEndDate"] 		  = $this->input->post("endDateDisplay");

		$univFormData["univSeoUrl"				] = $this->input->post("univSeoUrl");
		$univFormData["univSeoTitle"			] = $this->input->post("univSeoTitle");
		$univFormData["univSeoKeywords"			] = $this->input->post("univSeoKeywords");
		$univFormData["univSeoDescription"		] = $this->input->post("univSeoDescription");

		if($univFormData["announcementCheck"] != 'on') {
			$univFormData["announcementText"] 		      = "";
			$univFormData["announcementCallToAction"] 	  = "";
			$univFormData["announcementStartDate"] 		  = "";
			$univFormData["announcementEndDate"] 		  = "";
		}
        // get score reporting data from post
        $univFormData["scoreReportingExam"] = $this->input->post("scoreReportingExam");
        $univFormData["scoreReportingCode"] = $this->input->post("scoreReportingCode");

	$univFormData["conditionalOffer"] = $this->input->post("conditionalOffer");
	$univFormData["conditionalOfferDescription"	] = $this->input->post("conditionalOfferDescription");
	$univFormData["conditionalOfferLink"] = $this->input->post("conditionalOfferLink");

        //error_log(print_r($univFormData,true));
        // get university stats section data
        $this->_getUnivStatsPostData($univFormData);
        // get lat long data from post
        $this->_getUnivLatLongPostData($univFormData);
		// get shiksha apply data ($univFormData's reference is passed)
		$this->_getUnivShikshaApplyPostData($univFormData);
		$this->_getUnivCustomFieldsData($univFormData);
		return $univFormData;
    }

    private function _getUnivCustomFieldsData(&$univFormData){
        $customKbLabel = $this->input->post('kb_custom_field_name');
        $customKbValue = $this->input->post('kb_custom_field_value');
        $data = array();
        foreach ($customKbValue as $key=>$value){
            if(!empty($customKbLabel[$key]) && !empty($value)) {
                array_push($data, array(
                    'custom_label' => $customKbLabel[$key],
                    'custom_value' => $value
                ));
            }
        }
        $univFormData['customKbFields'] = $data;
    }

    /*
     * function to get lat long post data
     */
    private function _getUnivLatLongPostData(&$univFormData)
    {
        $univFormData["latitude"] = $this->input->post("univLatitude");
        $univFormData["longitude"] = $this->input->post("univLongitude");
        // no need to keep dir if the value is empty
        if($univFormData["latitude"] != '')
        {
            $univFormData["latitudeDir"] = $this->input->post("latitudeDir");
        }
        if($univFormData["longitude"] != '')
        {
            $univFormData["longitudeDir"] = $this->input->post("longitudeDir");
        }
    }

    /*
     * function to get university stats
     */
    private function _getUnivStatsPostData(&$univFormData)
    {
        $univStatsData["percentIntlStud"] = $this->input->post("percentIntlStud");
        $univStatsData["totalIntlStud"] = $this->input->post("totalIntlStud");
        $univStatsData["campusSize"] = $this->input->post("campusSize");
        $univStatsData["genderRatio"] = $this->input->post("genderRatio");
        $univStatsData["ugPgRatio"] = $this->input->post("ugPgRatio");
        $univStatsData["studentFacultyRatio"] = $this->input->post("studentFacultyRatio");
        $univStatsData["endowmentsCurr"] = $this->input->post("endowmentsCurr");
        $univStatsData["endowmentsVal"] = $this->input->post("endowmentsVal");
        $univStatsData["campusCount"] = $this->input->post("campusCount");
        $univStatsData["univRanking"] = $this->input->post("univRanking");
        // no need to keep dir if the value is empty
        if($univStatsData["endowmentsCurr"] == '' || $univStatsData["endowmentsVal"] == '') {
            unset($univStatsData["endowmentsCurr"]);
            unset($univStatsData["endowmentsVal"]);
        }

        $univFormData['univStatsData'] = $univStatsData;
    }

    /*
     * listingBrochureUpload
     * Function For uploading university e brochure
     * @access  private
     * @param   array $_POST
     * @param   array $_FILES
     * @param   $listing_type,$fieldName
     * @return  array
     * @ToDo
     */
    private function listingBrochureUpload($listing_type,$fieldName)
    {
        // check if institute brochure has been uploaded
        if(array_key_exists($fieldName, $_FILES) && !empty($_FILES[$fieldName]['tmp_name'][0])) {
            $return_response_array = array();
            // load client library
            $this->load->library('upload_client');
            $uploadClient = new Upload_client();
            // get file data and type check
            $type_doc = $_FILES[$fieldName]['type']['0'];
            $type_doc = explode("/", $type_doc);
            $type_doc = $type_doc['0'];
            $type = explode(".",$_FILES[$fieldName]['name'][0]);
            $type = strtolower($type[count($type)-1]);
            // display error if type doesn't match with the required file types(University : pdf only)
            if(!in_array($type, array('pdf'))) {//,'jpeg','doc','jpg
                $return_response_array['Fail'][$fieldName] = "Only document of type .pdf allowed";//,.doc and .jpeg
                return $return_response_array;
            }
            // all well, upload now
            if($type_doc == 'image') {
                $upload_array = $uploadClient->uploadFile($appId,'image',$_FILES,array(),"-1",$listing_type,$fieldName);
            } else {
                $upload_array = $uploadClient->uploadFile($appId,'pdf',$_FILES,array(),"-1",$listing_type,$fieldName);
            }
            // check the response from upload library
            if(is_array($upload_array) && $upload_array['status'] == 1) {
                $return_response_array = $upload_array[0]['imageurl'];
            } else {
                if($upload_array == 'Size limit of 50 Mb exceeded') {
                    $upload_array = "Please upload a brochure less than 50 MB in size";
                }
                $return_response_array['Fail'][$fieldName] = $upload_array;
            }
            return $return_response_array;
        } else {
            return "";
        }
    }

    /**
     * univLogoPhotoUpload
     * Function For Upload university Logo
     * @access  public
     * @param   array $_FILE, $logoFieldName, $pictureFieldName
     * @return  array univ Logo Array with 'removal' action information
     * @ToDo
     */
    function univLogoPhotoUpload($listing_type,$logoFieldName,$pictureFieldName){
        $appId =1;
        $logoArr = array(); //for logo
        $photoArr = array();// for pictures

        $this->load->library('upload_client');
        $uploadClient = new Upload_client();

        /******************************** Block to Upload univ Logo ***************************************/
        if($listing_type == 'consultant'){
            $arrCaption = array( $this->input->post('consultantName'));
        }else{
            $arrCaption = array( $this->input->post('univName'));
        }

        $univ_logo= array();
        for($i=0;$i<count($_FILES[$logoFieldName]['name']);$i++){
            $univ_logo[$i] = ($arrCaption[$i]!="")?mysql_escape_string($arrCaption[$i]):$_FILES[$logoFieldName]['name'][$i];
        }
        if(!(isset($_FILES[$logoFieldName]['tmp_name'][0]) && ($_FILES[$logoFieldName]['tmp_name'][0] != '')) ) //&& ($this->input->post('logoRemoved')==1))
        {
            $logoArr['thumburl'] = "";
        }else if(isset($_FILES[$logoFieldName]['tmp_name'][0]) && ($_FILES[$logoFieldName]['tmp_name'][0] != ''))
        {
            //before we upload, check for dimensions on the image file
            $imageDimension = getimagesize($_FILES[$logoFieldName]['tmp_name'][0]);
            if(!empty($imageDimension) && ($imageDimension[0]!=290 || $imageDimension[1]!=90) && $listing_type == 'university')
            {
                $logoArr['error'] = "Only 90 pixels of height and 290 pixels of width allowed for logo";
                $logoArr['thumburl'] = "";
            }elseif(!empty($imageDimension) && ($imageDimension[0]!=150 || $imageDimension[1]!=100) && $listing_type == 'consultant'){
                $logoArr['error'] = " 100 pixels of height and 150 pixels of width allowed for logo";
                $logoArr['thumburl'] = "";
            }
            else
            {
                $i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$univ_logo,"-1",$listing_type,$logoFieldName);
                if($i_upload_logo['status'] == 1)
                {
                    for($k = 0;$k < $i_upload_logo['max'] ; $k++)
                    {

                        $tmpSize = getimagesize($i_upload_logo[$k]['imageurl']);
                        list($width, $height, $type, $attr) = $tmpSize;
                        $logoArr['width']=$width;
                        $logoArr['height']=$height;
                        $logoArr['type']=$type;
                        $logoArr['mediaid']=$i_upload_logo[$k]['mediaid'];
                        $logoArr['url']=$i_upload_logo[$k]['imageurl'];
                        $logoArr['title']=$i_upload_logo[$k]['title'];
                        $logoArr['thumburl']=$i_upload_logo[$k]['imageurl'];
                    }
                } else{
                    $logoArr['error'] = $i_upload_logo;
                    $logoArr['thumburl'] = "";
                }
            }
        }

        /************************************ Block to Upload univ pictures ***************************************/
        $arrCaption .= "_image_";
        $univ_pictures= array();
        $pictureArr = array();
        // remove blank file inputs
        $_FILES[$pictureFieldName]['name'] 		= array_values(array_filter($_FILES[$pictureFieldName]['name'] 	,"trim"));
        $_FILES[$pictureFieldName]['type'] 		= array_values(array_filter($_FILES[$pictureFieldName]['type'] 	,"trim"));
        $_FILES[$pictureFieldName]['tmp_name'] 	= array_values(array_filter($_FILES[$pictureFieldName]['tmp_name'] ,"trim"));
        $_FILES[$pictureFieldName]['error'] 	= array_values(array_filter($_FILES[$pictureFieldName]['error'] 	,"trim"));
        $_FILES[$pictureFieldName]['size']		= array_values(array_filter($_FILES[$pictureFieldName]['size']	,"trim"));

        // error_log("file ".print_r($_FILES,true),3,'/home/naukri/Desktop/log.txt');
        $error_found = false;
        for($i = 0; $i < count ( $_FILES [$pictureFieldName] ['name'] ); $i ++) {
            $type = $_FILES [$pictureFieldName] ['type'] [$i];
            if (! ($type == "image/gif" || $type == "image/jpeg" || $type == "image/jpg" || $type == "image/png")) {
                $errorData [$i] = "Only Images of type jpeg,gif,png are allowed.";
                $error_found = true;
            } else {
                $sizeImage = $_FILES [$pictureFieldName] ['size'] [$i];
                $tmpDimensions = getimagesize ( $_FILES [$pictureFieldName] ['tmp_name'] [$i] );
                list ( $width, $height ) = $tmpDimensions;
                if ($width < 300 || $height < 200) {
                    $errorData [$i] = "Image size should be greater then or equal to 300pxX200px.";
                    $error_found = true;
                } elseif ($width / $height != 1.5) {

                    $errorData [$i] = "Image should be in 3:2 ratio.";
                    $error_found = true;
                } else if (intval ( $_FILES [$pictureFieldName] ['size'] [$i] ) > 5242880) {
                    $errorData [$i] = "Image size should be less then 5MB.";
                    $error_found = true;
                } else {
                    $errorData [$i] = "no error";
                }
            }
        }

        if ($error_found && ! empty ( $errorData )) {
            $pictureArr ['sizecheckerror'] = $errorData;
        }


        //		error_log("file ".print_r($_FILES,true),3,'/home/naukri/Desktop/log.txt');
        for($i=0;$i<count($_FILES[$pictureFieldName]['name']);$i++){
            $univ_pictures[$i] = ($arrCaption!="")?$arrCaption.($i+1):$_FILES[$pictureFieldName]['name'][$i];
        }
        if(!(isset($_FILES[$pictureFieldName]['tmp_name'][0])) && ($_FILES[$pictureFieldName]['tmp_name'][0] != '')){
            $photoArr['thumburl'] = "";
        }
        else if(!isset($pictureArr['sizecheckerror']) && isset($_FILES[$pictureFieldName]['tmp_name'][0]) && ($_FILES[$pictureFieldName]['tmp_name'][0] != ''))
        {
            $i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$univ_pictures,"-1",$listing_type,$pictureFieldName);
            //			error_log("logo array ".print_r($i_upload_logo,true),3,'/home/naukri/Desktop/log.txt');
            if($i_upload_logo['status'] == 1)
            {
                for($k = 0;$k < $i_upload_logo['max'] ; $k++)
                {
                    $tmpSize = getimagesize($i_upload_logo[$k]['imageurl']);
                    list($width, $height, $type, $attr) = $tmpSize;
                    $photoArr['width']=$width;
                    $photoArr['height']=$height;
                    $photoArr['type']=$type;
                    $photoArr['mediaid']=$i_upload_logo[$k]['mediaid'];
                    $photoArr['url']=$i_upload_logo[$k]['imageurl'];
                    $photoArr['title']=$i_upload_logo[$k]['title'];
                    $photoArr['thumburl']=$i_upload_logo[$k]['thumburl_m'];
                    $pictureArr[] = $photoArr;
                }
            }else{
                $pictureArr['error'] = $i_upload_logo;
                $pictureArr['thumburl'] = "";
            }
        }

        $response['logoArr'] = $logoArr;
        $response['pictureArr'] = $pictureArr;
        return $response;
    }

    function checkIfListingsMappedToScholarship($type,$id,$mode='read',$flagCount=false){
        $scholarshipPostingLib = $this->load->library('scholarshipPosting/ScholarshipPostingLib');
        $scholarshipIds = $scholarshipPostingLib->attributeMappedToScholarship($type,$id,$mode);
        if($flagCount)
        {
            $scholarshipIds = array_unique($scholarshipIds);
            $count = count($scholarshipIds);
            return $count;
        }
        if(count($scholarshipIds)>0){
            echo json_encode(array("Cannot delete this ". $type ." because one or more scholarships are tagged to it. Tagged scholarship ids are <". implode(",",$scholarshipIds).">"));
            return true;
        }
        return false;
    }

    /**
     * Purpose : Method to delete study abroad course
     * Params  :
     * Author  : Vinay
     */
    public function deleteDepartment()
    {
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        $departmentId = $this->input->post("parms");
        if(empty($departmentId))
        {
            echo "Invalid Department Id.";
            exit(0);
        }
        if($this->checkIfListingsMappedToScholarship('department',$departmentId)){
            return false;
        }

        // get the user data
        $userData = $this->cmsAbroadUserValidation();
        // delete the course
        try {
            $status = $this->abroadPostingLib->deleteDepartment(array($departmentId), $userData['userid']);
        } catch(Exception $e) {
            $status =0;
        }
        echo $status;
    }

    /**
     * Purpose : Method to delete study abroad course(ajax call)
     * Params  :	Course Id(Post)
     * Author  : Romil Goel
     */
    public function deleteCourseListing()
    {
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        $courseId = $this->input->post("parms");
        if(empty($courseId))
        {
            echo "Invalid Course Id.";
            return 0;
        }

        // get the user data
        $userData = $this->cmsAbroadUserValidation();
        $isPaid = $this->abroadPostingLib->isPaidCourse($courseId);
        if($isPaid == true) {
            echo json_encode(array("Deletion of paid courses is not allowed. Please downgrade this course before deletion."));
            return false;
        }

        if($this->checkIfListingsMappedToScholarship('course',$courseId)){
            return false;
        }
        //find uni
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder();
        $courseRepository = $listingBuilder->getAbroadCourseRepository();
        $course = $courseRepository->find($courseId);
        // delete the course
        $status = $this->abroadPostingLib->deleteCourse(array($courseId), $userData['userid']);
        //$this->deletedRowsInAllocatedUsersToCounselorsTable($courseId);
        // purge
        if($course->getId()>0 && $course->getUniversityId()>0)
        {
            $shikshamodel = $this->load->model("common/shikshamodel");
            $arr = array("cache_type" => "htmlpage", "entity_type" => "saUlp", "entity_id" => $course->getUniversityId(), "cache_key_identifier" => "");
            $shikshamodel->insertCachePurgingQueue($arr);
            $arr['entity_type'] = 'saAcp';
            $shikshamodel->insertCachePurgingQueue($arr);
            $arr['entity_type']= "saClp";
            $arr['entity_id']= $courseId;
            $shikshamodel->insertCachePurgingQueue($arr);
        }
        echo 1;

        //if( $status )
        //{
        //    echo "<script> alert(\"Successfully Deleted !!!\"); location.assign('".ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_COURSE."'); </script>";
        //}
        //else
        //{
        //    echo "<script> alert(\"Something went wrong !!!\"); location.assign('".ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_COURSE."'); </script>";
        //}
    }

    // private function deletedRowsInAllocatedUsersToCounselorsTable($courseId){
    //     $libObj = $this->load->library('shikshaApplyCRM/shikshaApplyLeadsAndResponsesLib');
    //     $libObj->deleteRecordsByCourseId($courseId);
    // }

    /**
     * Purpose : Method to delete study abroad university(ajax call)
     * Params  :	university Id(Post)
     * Author  : Romil Goel
     */
    public function deleteUniversity(){
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        $universityId = $this->input->post("parms");
        if(empty($universityId)){
            echo "Invalid University Id.";
            return 0;
        }
        $consultantPostingLib = $this->load->library("consultantPosting/ConsultantPostingLib");
        $check = $consultantPostingLib->checkIfConsultantAllowsUniversityDeletion($universityId);
        if(!$check){ // If check fails due to consultant subscription status
            echo json_encode(array("This university is mapped to active university it cannot be deleted."));;
            return false;	 // Don't go any further!
        }
        //Check for university if its already assign to RMC counsellor
        $rmcPostingLib = $this->load->library("shikshaApplyCRM/rmcPostingLib");
        $alreadyAddedCHeck =  $rmcPostingLib->checkUniversityHasRMCCounsellor(array($universityId));
        if($alreadyAddedCHeck[$universityId]>0){
            echo json_encode(array("This university is mapped to active RMC Counsellor. it cannot be deleted."));;
            return false;
        }

        if($this->checkIfListingsMappedToScholarship('university',$universityId)){
            return false;
        }
        // get the user data
        $userData = $this->cmsAbroadUserValidation();
        // delete the University
        $status = $this->abroadPostingLib->deleteUniversity(array($universityId), $userData['userid']);
        $shikshamodel = $this->load->model("common/shikshamodel");
        $arr = array("cache_type" => "htmlpage", "entity_type" => "saUlp", "entity_id" => $universityId, "cache_key_identifier" => "");
        $shikshamodel->insertCachePurgingQueue($arr);
        $arr['entity_type'] = 'saAcp';
        $shikshamodel->insertCachePurgingQueue($arr);
        echo $status;
    }

    public function deleteSnapshotCourse($snapShotCourseId){
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        if(empty($snapShotCourseId)){
            echo "Invalid SnapShot Course Id.";
            exit(0);
        }

        // get the user data
        $userData = $this->cmsAbroadUserValidation();

        // delete the course
        $status = $this->abroadPostingLib->deleteSnapshotCourse(array($snapShotCourseId), $userData['userid']);



        if( $status )
        {
            echo "<script> alert(\"Successfully Deleted !!!\"); location.assign('".ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_SNAPSHOT_COURSE."'); </script>";
        }
        else
        {
            echo "<script> alert(\"Something went wrong !!!\"); location.assign('".ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_SNAPSHOT_COURSE."'); </script>";
        }
    }

    /**
     * Purpose : Method to display table of content
     * Params  :	Status
     * Author  : vinay
     */

    public function viewContentListing($displayDataStatus = 'all')
    {
        $this->usergroupAllowed = array('saAdmin','saContent');
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // get post parameters
        $searchContentName = $this->input->get("q");
        $resultPerPage  = $this->input->get("resultPerPage");
        $searchType = $this->input->get("searchTyp");
        $searchType = empty($searchType) || $searchType == "" || !in_array(strtoupper($searchType),array("ALL","GUIDE","ARTICLE","EXAM PAGE","APPLY CONTENT","EXAM CONTENT")) ? 'ALL' : strtoupper($searchType);

        // data massaging
        $searchContentName = ($searchContentName == "Search Content") ? "" : $searchContentName;
        $resultPerPage  = ($resultPerPage) ? $resultPerPage : "";

        // prepare the query parameters coming
        $queryParams    ='1';
        $queryParams	.= ($searchType ? "&searchTyp=".$searchType : "");
        $queryParams   .= ($searchContentName ? "&q=".$searchContentName : "");
        $queryParams   .= ($resultPerPage  ? "&resultPerPage=".$resultPerPage : "");
        $queryParams    = $queryParams 	   ? "?".$queryParams : "";

        // prepare the URL for view as well as for paginator
        $URL 		= ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT."/".$displayDataStatus;
        $URLPagination 	= ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT."/".$displayDataStatus.($queryParams ? $queryParams : "");

        // initialize the paginator instance
        $this->load->library('listingPosting/Paginator');
        $displayData['paginator']  	  = new Paginator($URLPagination);

        // fetch the content data
        $result = $this->abroadPostingLib->getContentTableData($searchType,$displayDataStatus, $displayData['paginator'], $searchContentName);
        $displayData['paginator']->setTotalRowCount($result['totalCount']);

        if(count($result['data'])>0)
        {
            $this->_prepareContentTableData($result['data']);
        }


        // prepare the display date here
        $displayData['displayDataStatus'] 	= $displayDataStatus;
        $displayData['searchTerm'] 	  		= $searchContentName;
        $displayData['queryParams'] 	  	= $queryParams;
        $displayData['totalResultCount']  	= $result['dataCount'];
        $displayData['downloadInfoArray']  	= $result['downloadInfoArray'];
        $displayData['URL'] 	  	  		= $URL;
        $displayData["reportData"] 	  		= $result['data'];
        $displayData["searchTypeOptions"] 	= array('All','Article','Guide','Exam Page','Apply Content','Exam Content');
        $displayData["searchType"] 			= $searchType;
        $displayData['formName'] 			= ENT_SA_VIEW_LISTING_CONTENT;
        $displayData['selectLeftNav']   	= "CONTENT";

        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function addRankingForm()
    {
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // get post data here for first request then  make a ranking id and return it.

        // prepare the display date here
        $displayData['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();
        $displayData['formName'] 	= ENT_SA_FORM_ADD_RANKING;
        $displayData['selectLeftNav']   = "RANKING";
        $displayData['abroadMainLDBCourses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();
        $displayData['couresTypes'] = $this->abroadCommonLib->getAbroadCourseLevels();
        $this->_populateAbroadCountries($displayData);

        // Get Object for all country
        $allCountry = $this->locationRepository->findCountry(1);

        // Adding all country object in start of abroadCountries array
        $displayData['abroadCountries'] = array($allCountry) + $displayData['abroadCountries'];

        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }


    public function postFirstSectionOfRankingPage()
    {
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        $formData 				= $this->gatherRankingPageData();
        $formData['rankingLastModifiedBy'] 	= $displayData["userid"];

        $ranking_id = $this->abroadPostingLib->postRankingPage($formData);

        if($formData['rankingActionType'] == ENT_SA_FORM_ADD_RANKING)
            header('location:'.ENT_SA_FORM_EDIT_RANKING.'/'.$ranking_id."?state=add");
        else
            header('location:'.ENT_SA_VIEW_LISTING_RANKING);

        exit();
    }

    public function editRankingForm($rankPageId)
    {   $displayData = $this->cmsAbroadUserValidation();
        //fetch data from Ranking page table and fill in form.
        //Model Call to fetch Data;
        $displayData['formData'] = $this->abroadPostingLib->getRankingDataForEditMode($rankPageId);
        if(empty($displayData['formData']['id'])) {
            show_404();
        }
        $displayData['rankingId'] = $rankPageId;
        $displayData['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();
        $displayData['formName'] 	= ENT_SA_FORM_EDIT_RANKING;
        $displayData['selectLeftNav']   = "RANKING";
        $displayData['abroadMainLDBCourses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();
        $displayData['couresTypes'] = $this->abroadCommonLib->getAbroadCourseLevels();
        $this->_populateAbroadCountries($displayData);

        // Get Object for all country
        $allCountry = $this->locationRepository->findCountry(1);

        // Adding all country object in start of abroadCountries array
        $displayData['abroadCountries'] = array($allCountry) + $displayData['abroadCountries'];

        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);

    }

    public function gatherRankingPageData()
    {
        $formData['rankingId'] 		= $this->input->post('rankingId');
        $formData['rankingName'] 	= $this->input->post('rankingName');
        $formData['rankingType'] 	= $this->input->post('rankingType');
        $formData['countryId'] 		= $this->input->post('countryId');
        $formData['parentCategory'] 	= $this->input->post('parentCategory');
        $formData['childCategory'] 	= $this->input->post('childCategory');
        $formData['desiredCourse'] 	= $this->input->post('desiredCourse');
        $formData['couresType'] 	= $this->input->post('couresType');
        $formData['rankingActionType'] 	= $this->input->post('rankingActionType');

        $formData['rankingTitle'] 	= $this->input->post('rankingTitle');
        $formData['rankingSeoTitle'] 	= $this->input->post('rankingSeoTitle');
        $formData['rankingDesc'] 	= $this->input->post('rankingDesc');
        $formData['rankingKeywords'] 	= $this->input->post('rankingKeywords');
        $formData['submit_date'] 	= $this->input->post('submit_date');
        $formData['rankingStatus'] 	= $this->input->post('rankingStatus');
        $formData['listingIds'] 	= $this->input->post('listingIds');
        $formData['rankingUserComments']= $this->input->post('rankingUserComments');

        $formData['rankingCreatedBy'] 	= $this->input->post('rankingCreatedBy');

        return $formData;
    }


    public function addContentListing()
    {
        $this->usergroupAllowed = array('saAdmin','saContent');
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // prepare the display data here
        $displayData['action'] = 'add';
        $displayData['formName'] 	= ENT_SA_FORM_ADD_CONTENT;
        $displayData['selectLeftNav']   = "CONTENT";
        $expert_criteria=array(sortField=>'name',
                               sortFieldOrder=>'ASC',
                               is_active=>'active'); 
        $displayData['expertsList']= $this->expertProfileLib->getAllExperts($expert_criteria);
        $this->_populateAbroadCountries($displayData);
        $displayData['abroadCategories'] 		= $this->abroadCommonLib->getAbroadCategories();
        $displayData['abroadExamsMasterList'] 	= $this->abroadCommonLib->getAbroadExamsMasterList();
        $displayData['courseType'] 				= $this->abroadCommonLib->getAbroadCourseLevels();
        $displayData['tags'] 					= $this->abroadCommonLib->getTags();
        $displayData['lifecycleTags'] 			= $this->abroadCommonLib->getLifecycleTags($displayData);
        $displayData['desiredCourses'] 			= $this->abroadCommonLib->getAbroadMainLDBCourses();
        $displayData['examWithExamPage'] 		= $this->abroadCommonLib->getExamWithExamPage();
        $this->load->config("abroadApplyContentConfig");
        $displayData['applyContentTypes'] 		=  $this->config->item('applyContentMasterList');
        // different image button for content section to upload image in different folder
        $displayData['jbimagesButton']			= 'jbimages1';

        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function editContentListing($contentId)
    {
        $this->usergroupAllowed = array('saAdmin','saContent');
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        $displayData['action'] = 'edit';
        $expert_criteria=array(sortField=>'name',
                               sortFieldOrder=>'ASC',
                               is_active=>'active'); 
        $displayData['expertsList']= $this->expertProfileLib->getAllExperts($expert_criteria);
        //Get Content Data
        $contentData = $this->abroadPostingLib->getContentData($contentId);
        if($contentData === null){
            show_404();
        }
        $displayData['content']	= $contentData[$contentId];

        $saContentModel = $this->load->model('blogs/sacontentmodel');
        $status = $contentData[$contentId]['basic_info']['status'];
        $blogImages = $saContentModel->getContentImages($contentId,$status);
        $displayData['blogImages'] = $blogImages;

        //_p($displayData['content']);

        $userModel = $this->load->model('user/usermodel');
        $userData = $userModel->getUserById($contentData[$contentId]['basic_info']['last_modified_by']);
        $displayData['lastModifiedBy'] = $userData->getFirstName()." ".$userData->getLastName();
        $displayData['lastModified'] =  $contentData[$contentId]['basic_info']['last_modified'];
        if($contentData[$contentId]['basic_info']['type'] == 'applyContent')
        {
            $this->load->config("abroadApplyContentConfig");
            $displayData['applyContentTypes'] = $this->config->item('applyContentMasterList');
            $displayData['homepageAvailable'] = $this->abroadCommonLib->isHomepageAvailable($contentData[$contentId]['applyContentDetails']['applyContentType']);
        }
        if($contentData[$contentId]['basic_info']['type'] == 'examContent')
        {
            $hasHomepage = $this->abroadCommonLib->isExamHomepageAvailable($contentData[$contentId]['basic_info']['exam_type'],$contentId);
            $displayData['examHomepageAvailable'] = $hasHomepage;
        }


        $this->_populateAbroadCountries($displayData);
        $displayData['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();
        $displayData['abroadExamsMasterList'] = $this->abroadCommonLib->getAbroadExamsMasterList();
        $displayData['courseType'] = $this->abroadCommonLib->getAbroadCourseLevels();
        $displayData['tags'] = $this->abroadCommonLib->getTags();
        $displayData['lifecycleTags'] = $this->abroadCommonLib->getLifecycleTags($displayData);
        $displayData['desiredCourses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();
        $displayData['previewLinkFlag'] = $this->abroadPostingLib->checkListingExistinGivenState($contentId,'content','live');

        foreach($displayData['content']['ldbCourse_info'] as $key=>$desiredCourses) {
            $displayData['selectedDesiredCourses'][$key] = $desiredCourses['ldb_course_id'];
        }
        // prepare the display date here
        $displayData['formName'] 	= ENT_SA_FORM_EDIT_CONTENT;
        $displayData['selectLeftNav']   = "CONTENT";

        // different image button for content section to upload image in different folder
        $displayData['jbimagesButton']  = 'jbimages1';

        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    /*
     *Function to delete Ranking
     */
    function deleteRankingListing()
    {
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        $rankId = $this->input->post("parms");
        if(empty($rankId))
        {
            echo "Invalid Rank Id.";
            return 0;
        }

        // get the user data
        $userData = $this->cmsAbroadUserValidation();
        // delete the course
        $status = $this->abroadPostingLib->deleteRank(array($rankId), $userData['userid']);

        echo 1;
    }

    /**
     * Purpose : Method to get the name of the listing(ajax call)
     * Params  :	Course/University Id, listing type, country id(Post)
     * Author  : Romil Goel
     */
    public function getListingsName()
    {
        $listing_id 	= $this->input->post("listing_id");
        $listing_type 	= $this->input->post("listing_type");
        $country_id	= $this->input->post("country_id");
        $desiredCourse	= $this->input->post("desiredCourse");
        $couresType	= $this->input->post("couresType");
        $parentCategory	= $this->input->post("parentCategory");
        $childCategory	= $this->input->post("childCategory");

        $courseTypeDetails = array("desiredCourse" => $desiredCourse,
            "couresType"	   => $couresType,
            "parentCategory"=> $parentCategory,
            "childCategory" => $childCategory);

        if(empty($desiredCourse) && empty($couresType) && empty($parentCategory))
        {
            $courseTypeDetails = array();
        }

        // initialize local vars
        $draft_flag 	= 0;
        $name 		= "";

        // get the data
        $listings_name = $this->abroadCommonLib->getListingsName($listing_id, $listing_type, $country_id, $courseTypeDetails);

        $courseProperlyMappedFlag = 0;
        if(empty($listings_name['courseProperlyMappedFlag']))
            $courseProperlyMappedFlag = 0;
        else
            $courseProperlyMappedFlag = $listings_name['courseProperlyMappedFlag'];

        if(empty($listings_name[0]['name']))
            $resultArr = array("errorFlag" => 1, "draftedListing" => 0, "courseProperlyMappedFlag" => $courseProperlyMappedFlag);
        else
        {
            if(((count($listings_name) == 2 || $listing_type == 'course') || (count($listings_name) == 1 || $listing_type == 'university')) && $listings_name[0]['status'] == 'draft')
                $draft_flag = 1;

            $tempName = "";
            foreach($listings_name as $val)
            {
                if($val['name'])
                    $tempName = $val['name'];

                if($val['status'] == ENT_SA_PRE_LIVE_STATUS)
                {
                    $name = $val['name'];
                }
            }
            if(!$name)
                $name = $tempName;

            $resultArr = array("errorFlag" => 0, "draftedListing" => $draft_flag,"name" =>htmlspecialchars($name), "courseProperlyMappedFlag" => $courseProperlyMappedFlag);
        }
        echo json_encode($resultArr);
    }

    public function checkForDuplicateRankingPage()
    {
        $rankingType	 	= $this->input->post("rankingType");
        $desiredCourse		= $this->input->post("desiredCourse");
        $courseType 		= $this->input->post("courseType");
        $parentCategoryId 	= $this->input->post("parentCategoryId");
        $subCategory 		= $this->input->post("subCategory");
        $countryId 		= $this->input->post("countryId");
        $rankingId 		= $this->input->post("rankingId");

        $desiredCourse 	= empty($desiredCourse) ? 0 : $desiredCourse;
        $subCategory 	= empty($subCategory) 	? 0 : $subCategory;
        $countryId  	= empty($countryId) 	? 0 : $countryId;

        $val = $this->abroadCommonLib->checkForDuplicateRankingPage($rankingType, $desiredCourse, $courseType, $parentCategoryId, $subCategory, $countryId, $rankingId);

        echo json_encode($val);
    }

    public function saveContentListing(){
        //Get User Data
        $userData = $this->cmsAbroadUserValidation(true);
        if(!empty($userData['error']) && !empty($userData['error_type'])) {
            echo json_encode($userData);
            return;
        }

        $contentData = $this->getContentRequestData();
        $contentData['userId'] =$userData['userid'];
        
        //Model Call
        $this->abroadPostingLib->addContent($contentData);
        return 1;
    }

    public function getContentRequestData()
    {
        //_p($_POST);
        $contentData = array();

        $contentData['content_type'] =  $this->input->post('contentType');
        if($contentData['content_type'] == ''){
            $arr["error"] = array("Fail"=>array("file_size_exceeded" => ""));
            echo json_encode($arr);
            exit;
        }
        $contentData['content_country'] =  $this->input->post('country');
        $contentData['content_expert'] =  $this->input->post('contentExpert');
        $contentData['content_institute'] =  $this->input->post('university');
        $contentData['content_desiredCourse'] =  $this->input->post('desiredCourse');
        $contentData['formAction'] = $this->input->post('actionType');
        $contentData['content_contentId'] = $this->input->post('contentTypeId');

        $content_hidden =  $this->input->post('hidden');
        //error_log("content_hidden: ".print_r($content_hidden, true));
        foreach($content_hidden as $value) {
            if($this->input->post('r_'.$value) != "none") {
                $contentData['content_courseType'][] = $this->input->post('r_'.$value);
            }
        }
        //error_log("check if here courses: ".print_r($contentData['content_courseType'], true));
        $contentData['content_parentCategory'] =  $this->input->post('parentCat');

        $contentData['content_subCategory'] =  $this->input->post('subCat');
        $contentData['content_title'] =  str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post('title')));

        if($contentData['content_type'] != 'examPage')
        {
            $contentData['content_stripTitle'] = html_entity_decode(strip_tags($this->input->post('title')));
            $contentData['content_stripTitle'] = html_entity_decode($contentData['content_stripTitle'], ENT_NOQUOTES, 'UTF-8');
            $contentData['content_stripTitle'] = trim(preg_replace('/[^A-Za-z0-9!@#$%^&*()<>,?;{}.\'\":\/|]/',' ',$contentData['content_stripTitle']));
        }
        else
        {
            $contentData['content_stripTitle'] = $this->input->post('title');
        }

        if($contentData['content_type'] == 'article')
        {
            $contentData['content_details'] =  str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post('articleDetails')));
            $contentData['content_isDownloadable'] =  "no";
            $contentData['content_guide'] =  "";
            $arr["error"] = 0;
            echo json_encode($arr);
        }
        elseif(($contentData['content_type'] == 'guide') || ($contentData['content_type'] == 'examPage'))
        {
            if($contentData['content_type'] == 'examPage')
            {
                $contentData['content_heading'] =  str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post('exam-heading')));
                $contentData['content_details'] =  str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post('exam-detail')));

                $contentData = $this->_getContentRequestDataForExam($contentData);
            }
            else{
                $contentData['content_heading'] =  str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post('guide-heading')));
                $contentData['content_details'] =  str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post('guide-detail')));
            }

            $contentData['content_isDownloadable'] =  ($this->input->post('checkDownload')=='on')?"yes":"no";

            if($contentData['formAction'] == 'edit') {
                $contentData['content_guideURL'] = $this->input->post('uploadFileText');
            } else {
                $contentData['content_guideURL'] = "";
            }

            if($contentData['content_guideURL'] == ""){
                $contentData['content_guide'] = $this->listingBrochureUpload('content','uploadFile');
            } else {
                $contentData['content_guide'] = "";
            }
            if(array_key_exists('Fail', $contentData['content_guide'])) {
                $arr["error"] = $contentData['content_guide'];
                echo json_encode($arr);
                exit;
            }elseif($contentData['content_isDownloadable'] == "yes" && $contentData['content_guide'] == "" && $contentData['content_guideURL'] == ""){
                $arr["error"] = array("Fail"=>array("file_size_exceeded" => ""));
                echo json_encode($arr);
                exit;
            }
            else {
                $arr["error"] = 0;
                echo json_encode($arr);
            }
        }
        elseif($contentData['content_type'] == 'applyContent')
        {
            $contentData['applyContentType'] = $this->input->post("applyContentType");
            $contentData['isHomepage'] = $this->input->post("setHomepage");
            $applyContentDescriptions =  str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post('applyContentDesc')));
            $contentData['content_details'][0] = $applyContentDescriptions[0]; 	// description1
            $contentData['content_details2'] = $applyContentDescriptions[1];	// description2

            $contentData['content_isDownloadable'] =  ($this->input->post('checkDownload')=='on')?"yes":"no";
            if($contentData['formAction'] == 'edit') {
                $contentData['content_guideURL'] = $this->input->post('uploadFileText');
            } else {
                $contentData['content_guideURL'] = "";
            }

            if($contentData['content_guideURL'] == ""){
                $contentData['content_guide'] = $this->listingBrochureUpload('content','uploadFile');
            } else {
                $contentData['content_guide'] = "";
            }

            if($contentData['isHomepage'] == 1 && $contentData['content_guide'] == "" && $contentData['content_guideURL'] == "")
            {
                $arr["error"] = array("Fail"=>array("guide_mandatory" => ""));
                echo json_encode($arr);
                exit;
            }
            if(array_key_exists('Fail', $contentData['content_guide'])) {
                $arr["error"] = $contentData['content_guide'];
                echo json_encode($arr);
                exit;
            }elseif($contentData['content_isDownloadable'] == "yes" && $contentData['content_guide'] == "" && $contentData['content_guideURL'] == ""){
                $arr["error"] = array("Fail"=>array("file_size_exceeded" => ""));
                echo json_encode($arr);
                exit;
            }
            else {
                $arr["error"] = 0;
                echo json_encode($arr);
            }
            // clean string for url
            $applyContentSeoUrl = $this->input->post('SEOurl');
            //$applyContentSeoUrl = $this->abroadPostingLib->getApplyContentSeoUrl($applyContentSeoUrl, $contentData['applyContentType']);

        }elseif($contentData['content_type'] == 'examContent')
        {
            $examDetails = json_decode(base64_decode($this->input->post('examContent_examid')),true);
            $contentData['content_exam_type'] =  $examDetails['examId'];
            $contentData['content_exam_name'] =  $examDetails['exam'];
            $contentData['isHomepage'] = $this->input->post("setHomepage");
            $examContentDescriptions =  str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post('applyContentDesc')));
            $contentData['content_details'][0] = $examContentDescriptions[0]; // description1
            $contentData['content_details2'] = $examContentDescriptions[1];// description2

            if($contentData['isHomepage'] == 1){
                $hasHomepage = $this->abroadCommonLib->isExamHomepageAvailable($contentData['content_exam_type'],$contentData['content_contentId']);
                if(!$hasHomepage){
                    $arr["error"] = array("Fail"=>array("homePage" => "Home Page already exists"));
                    echo json_encode($arr);
                    exit;
                }
            }

            $contentData['content_isDownloadable'] =  ($this->input->post('checkDownload')=='on')?"yes":"no";
            if($contentData['formAction'] == 'edit') {
                $contentData['content_guideURL'] = $this->input->post('uploadFileText');
            } else {
                $contentData['content_guideURL'] = "";
            }

            if($contentData['content_guideURL'] == ""){
                $contentData['content_guide'] = $this->listingBrochureUpload('content','uploadFile');
            } else {
                $contentData['content_guide'] = "";
            }

            if($contentData['isHomepage'] == 1 && $contentData['content_guide'] == "" && $contentData['content_guideURL'] == "")
            {
                $arr["error"] = array("Fail"=>array("guide_mandatory" => ""));
                echo json_encode($arr);
                exit;
            }
            if(array_key_exists('Fail', $contentData['content_guide'])) {
                $arr["error"] = $contentData['content_guide'];
                echo json_encode($arr);
                exit;
            }elseif($contentData['content_isDownloadable'] == "yes" && $contentData['content_guide'] == "" && $contentData['content_guideURL'] == ""){
                $arr["error"] = array("Fail"=>array("file_size_exceeded" => ""));
                echo json_encode($arr);
                exit;
            }
            else {
                $arr["error"] = 0;
                echo json_encode($arr);
            }
            if($contentData['formAction'] == 'add')
            {
                $contentData['redirectContentId'] = (int)$this->input->post('redirectContentId');
                $contentData['redirectContentType'] = $this->input->post('redirectContentType');
                $contentData['redirectSectionId'] = (int) $this->input->post('redirectSectionId');
                $contentData['redirectSEOUrl'] = $this->input->post('redirectSEOUrl');
            }
            // clean string for url
            $examContentSeoUrl = $this->input->post('SEOurl');
            $examContentSeoUrl = $this->abroadPostingLib->getExamContentSeoUrl($examContentSeoUrl,$contentData);
        }
        $contentData['content_summary'] =  str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post('summary')));
        $contentData['content_tags'] =  $this->input->post('tag');


        $contentData['contentImageURL'] =  $this->input->post('blogImageUrl');
        $contentData['contentImages'] = $this->input->post('blogImage');
        $contentData['content_exam'] =  $this->input->post('exam');
        $contentData['content_seoTitle'] =  $this->input->post('SEOtitle');
        $contentData['content_seoKeywords'] =  $this->input->post('SEOkeywords');
        $contentData['content_seoDescription'] =  $this->input->post('SEOdescription');
        $contentData['status'] =  $this->input->post('status');
        // apply content
        if($applyContentSeoUrl != "")
        {
            $contentData['content_contentURL'] = $applyContentSeoUrl;
        }elseif($examContentSeoUrl != "")
        {
            $contentData['content_contentURL'] = $examContentSeoUrl;
        }
        else{
            $contentData['content_contentURL'] = $this->input->post('contentURL');
        }
        $contentData['content_commentCount'] =  $this->input->post('commentCount');

        $contentData['content_seoMappingContentId'] =  $this->input->post('seoMappingContentId');

        $contentData['content_viewCount'] = $this->input->post('viewCount');


        $contentData['relatedDate'] = $this->input->post('relatedDate');

        if($contentData['status'] == "live"){
            $contentData['status'] = ENT_SA_PRE_LIVE_STATUS;
        }

        $dontUpdatePublishdateFlag = $this->input->post('dontUpdatePublishdate');
        if($dontUpdatePublishdateFlag!="1" && $contentData['formAction'] == 'edit') {
            $updatedFlag = $this->_checkIfContentDataUpdated($contentData);
        }
        //error_log("check if here: ".print_r($contentData, true));
        if($updatedFlag==true || $contentData['formAction']!='edit'){
            $contentData['contentUpdatedAt'] = date('Y-m-d H:i:s');
        }
        // get lifecycle tags (in case of articles & guides)
        if(in_array($contentData['content_type'],array('article','guide')))
        {
            $contentData['level'] = $this->input->post('lifecycleTagL1');
            $contentData['value'] = $this->input->post('lifecycleTagL2');
            for($i = 0 ; $i<count($contentData['level']);$i++){ // remove empty values
                if($contentData['level'][$i] == '' && $contentData['value'][$i] == ''){
                    unset($contentData['level'][$i]);
                    unset($contentData['value'][$i]);
                }
            }
            //_p($contentData['level']);_p($contentData['value']);die;

        }
        return $contentData;
    }

    function _checkIfContentDataUpdated(& $contentData){
        $updatedFlag = false;
        //$fieldToCheck = array('content_title','content_summary','content_details','content_heading');
        $contentId= $contentData['content_contentId'];
        $updatedFlag = $this->abroadPostingLib->matchContentTitleAndSummary($contentId,$contentData);
        if($updatedFlag==false){
            $updatedFlag = $this->abroadPostingLib->matchContentSections($contentId,$contentData);
        }
        return $updatedFlag;
    }


    function _getContentRequestDataForExam($contentData)
    {
        $contentData['content_description'] =  $this->input->post('exam_desc');
        $contentData['content_exam_type'] =  $this->input->post('examPage_examid');
        $contentData['content_section_indexes'] = $this->input->post('sectionIndex');
        return $contentData;

    }

    //Function To Check If Content
    public function isContentExist(){
        if($this->input->post("type")!='examPage'){
            $searchTag = html_entity_decode($this->input->post("title"),ENT_NOQUOTES, 'UTF-8');
        }else{
            $searchTag = mysql_escape_string($this->input->post("title"));
        }
        //$searchTag = htmlentities(trim(utf8_decode($searchTag)));
        //$searchTag = trim(str_replace("&nbsp;", " ", ($searchTag)));
        $searchTag = trim($searchTag);
        $searchTag = trim(preg_replace('/[^A-Za-z0-9!@#$%^&*()<>,?;{}.\'\":\/|]/',' ',$searchTag));

        $searchTagType = $this->input->post("type");
        $searchTagID = $this->input->post("contentId");
        $searchContenturl = $this->input->post("SEOurl");
        $examTypeId = $this->input->post("examTypeId");

        $additionalData = array(
                            'searchContenturl' => $searchContenturl
                        );
        if(!empty($examTypeId)) {
            $additionalData['examTypeId'] = $examTypeId;
        }
        if($searchTag != '' && $searchTagType !='' ){
            $arr['exists'] = $this->abroadCommonLib->isContentExist($searchTagType, $searchTag, $searchTagID, $additionalData);
            //error_log("check if here: ".print_r($arr, true));
            echo json_encode($arr);
        }else{
            $arr['exists'] = 0;
            //error_log("check if here: ".print_r($arr, true));
            echo json_encode($arr);
        }
    }

    /**
     * Purpose : Method to delete study abroad Guide/Article(ajax call)
     * Params  :	Content Id(Post)
     * Author  : Romil Goel
     */
    public function deleteContentListing()
    {
        $this->usergroupAllowed = array('saAdmin','saContent');
        $guideArticleId 	= $this->input->post("parms");
        if(empty($guideArticleId))
        {
            echo "Invalid Content Id.";
            return 0;
        }

        // get the user data
        $userData = $this->cmsAbroadUserValidation();
        // delete the course
        $status = $this->abroadCmsModelObj->deleteGuideArticle(array($guideArticleId), $userData['userid']);

        echo 1;
    }
    public function testBrochureSize(){
        $url = $_REQUEST["link"];
        $format= $_REQUEST["format"];
        echo "link:".$url;
        echo "</br>format:".$format;
        echo "</br>size:".getRemoteFileSize($url, ($format=="true"?1:0));
    }
    public function fixInstituteLocationId()
    {
        if($_REQUEST['FIX']==1){
            $abroadCmsModelObj	= $this->load->model('listingPosting/abroadcmsmodel');
            $abroadCmsModelObj->fixInstituteLocationIds();
        }
    }

    /**
     * Purpose : Method to render the list of paid clients
     * Params  :	1. Status of the data to be shown, by default it is 'all' i.e live and draft both
     * Author  : SRB
     */
    public function viewPaidClient($displayDataStatus = 'all')
    {
        // get the user data
        $this->usergroupAllowed = array('saAdmin','saSales');
        $displayData = $this->cmsAbroadUserValidation();

        // get post parameters
        $courseId = $this->input->get("q");
        $resultPerPage  = $this->input->get("resultPerPage");

        // data massaging
        $courseId = ($courseId == "Search Paid Course") ? "" : $courseId;
        $resultPerPage  = ($resultPerPage) ? $resultPerPage : "";

        // prepare the query parameters coming
        $queryParams	= "1";
        $queryParams   .= ($courseId ? "&q=".$courseId : "");
        $queryParams   .= ($resultPerPage  ? "&resultPerPage=".$resultPerPage : "");
        $queryParams    = $queryParams 	   ? "?".$queryParams : "";

        // prepare the URL for view as well as for paginator
        $URL 		= ENT_SA_CMS_PATH.ENT_SA_VIEW_PAID_CLIENT."/".$displayDataStatus;
        $URLPagination 	= ENT_SA_CMS_PATH.ENT_SA_VIEW_PAID_CLIENT."/".$displayDataStatus.($queryParams ? $queryParams : "");

        // initialize the paginator instance
        $this->load->library('listingPosting/Paginator');
        $displayData['paginator']  	  = new Paginator($URLPagination);  //_p($displayData['paginator']);
        // fetch the universities data
        $result = $this->abroadPostingLib->getPaidCoursesWithClient($displayData['paginator'],$courseId);
        //echo $result['totalCount'];
        $displayData['paginator']->setTotalRowCount($result['totalCount']);
        $displayData['totalCount'] = $result['totalCount'];

        // prepare the display data here
        $displayData['formName'] 	  = ENT_SA_VIEW_PAID_CLIENT;
        $displayData['selectLeftNav']     = "PAID_CLIENT";
        $displayData['displayDataStatus'] = $displayDataStatus;
        $displayData['searchTerm'] 	  = $courseId;
        $displayData['queryParams'] 	  = $queryParams;
        $displayData['totalResultCount']  = $result['dataCount'];
        $displayData['URL'] 	  	  = $URL;
        $displayData["reportData"] 	  = $result['data'];

        // load the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }
    /**
     * Purpose : Method to add paid course
     * Params  :	1. Status of the data to be shown, by default it is 'all' i.e live and draft both
     * Author  : SRB
     */
    public function addPaidClient($displayDataStatus = 'all')
    {
        // get the user data
        $this->usergroupAllowed = array('saAdmin','saSales');
        $displayData= $this->cmsAbroadUserValidation();
        $displayData['displayname']  = $displayData['validateuser']['displayname'];
        // prepare the display data here
        $displayData['formName'] 	= ENT_SA_FORM_ADD_PAID_CLIENT;
        $displayData['selectLeftNav']   = "PAID_CLIENT";

        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    /**
     * Purpose : Method to edit course's paid client
     * Params  :	1. Status of the data to be shown, by default it is 'all' i.e live and draft both
     * Author  : SRB
     */
    public function editPaidClient($displayDataStatus = 'all')
    {
        // get the user data
        $this->usergroupAllowed = array('saAdmin','saSales');
        $displayData= $this->cmsAbroadUserValidation();
        // prepare the display data here
        $displayData['formName'] 	= ENT_SA_FORM_EDIT_PAID_CLIENT;
        $displayData['selectLeftNav']   = "PAID_CLIENT";
        $displayData['paramCourseId']   = $this->input->get('course_id');
        //echo "paramCourseId".$displayData['paramCourseId'] ;
        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }
    /*
     * ajax call to get course data for add paid client interface
     */
    public function getCourseWithClientData($courseId)
    {
        $data = $this->abroadCmsModelObj->getCourseWithClientData($courseId );
        echo json_encode($data);
    }
    /*
     * to get enterprise clients subscription data for subcription drop down in add paid client interface
     */
    public function getClientSubscriptionData($clientId)
    {
        $userRepository = \user\Builders\UserBuilder::getUserRepository();
        $userObj = $userRepository->find($clientId);
        if(empty($userObj)){
            echo "-1";
            return false;
        }
        $this->load->library('sums_product_client');
        $objSumsProduct =  new Sums_Product_client();
        $finalSubscriptionDetails = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$clientId));
        //_p($finalSubscriptionDetails);
        $optionsHtml = '';
        $goldFlag = false;
        foreach($finalSubscriptionDetails as $key=>$vals){
            if( ($vals['BaseProdCategory']=='Listing')){
                if($vals['BaseProdSubCategory']=='Gold' || strtolower($vals['BaseProdSubCategory']) == 'gold sl') {
                    $goldFlag = true;
                }
                if($vals['BaseProdSubCategory']=='Bronze'){
                    $bronzeFlag = true;
                }
                if($vals['BaseProdSubCategory']=='Silver'){
                    $silverFlag = true;
                }
            }
        }

        if($goldFlag){
            $i = 1;
            foreach($finalSubscriptionDetails as $key=>$vals){
                if($vals['BaseProdSubCategory']=='Gold' || strtolower($vals['BaseProdSubCategory']) == 'gold sl'){

                    $optionsHtml .= '<option value="'.$key.'" '.($goldMLFlag==false && $i==1?'selected="selected"':'').'>'.$vals['BaseProdCategory']."-".$vals['BaseProdSubCategory']." : ".$key.($i==1?" (Recommended Gold)":"").'</option>';
                    $i++;
                }else{
                    continue;
                }
            }
        }
        if($bronzeFlag){
            foreach($finalSubscriptionDetails as $key=>$vals){
                if($vals['BaseProdSubCategory']=='Bronze'){

                    $optionsHtml .= '<option value="'.$key.'" '.($goldFlag==false && $silverFlag==false?'selected="selected"':'').'>'.$vals['BaseProdCategory']."-".$vals['BaseProdSubCategory']." : ".$key.'</option>';
                    $i++;
                }else{
                    continue;
                }
            }
        }
        echo json_encode(array("name"=>$userObj->getDisplayName(),"html"=>$optionsHtml));
    }

    public function getSubscriptionDetails($subscriptionId){
        if($subscriptionId=='') {
            echo json_encode(array());
            return;
        }
        $this->load->library('subscription_client');
        $subscriptionClient = new Subscription_client();
        $details = $subscriptionClient->getSubscriptionDetails(1,$subscriptionId);
        echo json_encode($details);
    }

    /*
     * function to validate whether subscription has exhausted while saving
     */
    public function validateSubscriptionInfo($userGroup, $onBehalfOf, $subscriptionId, $clientId)
    {
        $this->abroadCommonLib 		= $this->load->library('listingPosting/AbroadCommonLib');
        $result = $this->abroadCommonLib->validateSubscriptionInfo($userGroup, $onBehalfOf, $subscriptionId, $clientId);
        echo json_encode($result);
    }
    /*
     * function to add Paid Client To Course in db
     */
    public function savePaidClient()
    {
        // get the user data
        $this->usergroupAllowed = array('saAdmin','saSales');
        $displayData['validateuser'] = $this->cmsAbroadUserValidation();
        // gather post data
        $paidClientFormData = $this->postDataForPaidClientForm();
        if(!($paidClientFormData['subscription']>0))
        {
            // mail data in case this is empty
            $lib = $this->load->library('common/studyAbroadCommonLib');
            $lib->selfMailer('SavePaidClient Failure',
                'SubscriptionId unavailable for following data <br>'.print_r($paidClientFormData,true),
                'teamldb@shiksha.com',
                'karundeep.gill@shiksha.com');
        }
        else{
            // send post data to posting lib for saving/updating
            $result = $this->abroadPostingLib->addPaidClientToCourse($paidClientFormData);
            //find uni
            $this->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder();
            $courseRepository = $listingBuilder->getAbroadCourseRepository();
            $course = $courseRepository->find($paidClientFormData['course_id']);
            if($course->getId()>0 && $course->getUniversityId()>0)
            {
                $shikshamodel = $this->load->model("common/shikshamodel");
                $arr = array("cache_type" => "htmlpage", "entity_type" => "saUlp", "entity_id" => $course->getUniversityId(), "cache_key_identifier" => "");
                $shikshamodel->insertCachePurgingQueue($arr);
                $arr['entity_type'] = 'saAcp';
                $shikshamodel->insertCachePurgingQueue($arr);
                $arr['entity_type']= "saClp";
                $arr['entity_id']= $course->getId();
                $shikshamodel->insertCachePurgingQueue($arr);

            }
        }
        header("Location:/listingPosting/AbroadListingPosting/viewPaidClient");
    }
    /*
     * function to gather data posted via addPaidClient form
     */
    private function postDataForPaidClientForm()
    {
        //_p($_REQUEST);
        $paidClientFormData = array();
        $paidClientFormData['course_id'		] = $this->input->post('course_id');
        $paidClientFormData['client_id'		] = $this->input->post('client_id');
        $paidClientFormData['subscription'	] = $this->input->post('subscription');
        $paidClientFormData['courseClientId'	] = $this->input->post('courseClientId');
        $paidClientFormData['editedBy'		] = $this->input->post('editedBy');
        $paidClientFormData['univActionType'	] = $this->input->post('univActionType');
        return $paidClientFormData;
    }



    /**
     * Purpose : Method for showing RMS Counsellor listing
     * Params  :	none
     * Author  : Abhay
     */
    public function viewRMSCounsellor()
    {
        $this->usergroupAllowed = array('saAdmin','saRMS');

        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // fetch the Counsellor data
        $result = $this->abroadCmsModelObj->getRMSCounsellorTableData();

        // prepare the display date here
        $displayData['formName'] 	  = ENT_SA_VIEW_RMS_COUNSELLOR;
        $displayData['selectLeftNav']     = "RMS_COUNSELLORS";
        $displayData["reportData"] 	  = $result['data'];

        // load the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    /**
     * Purpose : Method for showing RMS Universities mapping
     * Params  :	none
     * Author  : Abhay
     */
    public function viewRMSUniversities()
    {
        $this->usergroupAllowed = array('saAdmin','saRMS');

        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // fetch the Counsellor data
        $result = $this->abroadCmsModelObj->getRMSUniversitiesMappingTableData();

        // prepare the display date here
        $displayData['formName'] 	  = ENT_SA_VIEW_RMS_UNIVERSITIES;
        $displayData['selectLeftNav']     = "RMS_UNIVERSITIES";
        $displayData["reportData"] 	  = $result;

        //_p($result );//die;

        // load the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }


    public function convertCurrency($sourceCurrency, $destinationCurrency, $amount){
        $convertedValue = $this->abroadCommonLib->convertCurrency($sourceCurrency,$destinationCurrency,$amount);
        $convertedValue = round($convertedValue,2);
        $currencyList = $this->abroadCommonLib->getCurrencyList();
        $sourceCode = '';
        $destinationCode = '';
        foreach($currencyList as $currency){
            if($currency['id'] == $sourceCurrency){
                $sourceCode = $currency['currency_code'];
            }
            if($currency['id'] == $destinationCurrency){
                $destinationCode = $currency['currency_code'];
            }
        }
        echo base64_encode('<p class="dollar-width"> <strong>'.$sourceCode.' '.$amount.'</strong> </p> <p class="equal-width"> <strong>=</strong> </p> <p class="rupee-width"> <strong>'.$destinationCode.' '.$convertedValue.'</strong> </p><br>');
    }

    public function _populateRMSCounsellors(& $displayData)
    {
        $counsellorData =  $this->abroadCmsModelObj->getAllRMSCounsellor();
        $displayData['counsellorList'] = $counsellorData;
    }

    public function _getAllUnivertyMappedToCounsellor(& $displayData,$counsellorID)
    {
        $mappingData =  $this->abroadCmsModelObj->getAllUnivertyMappedToCounsellor($counsellorID);
        $universityWithCount = $this->abroadPostingLib->getRMSResponseCount($mappingData);
        $displayData['mappedUniversityList'] = $universityWithCount;
        $displayData['mappedUniversityCount'] = count($universityWithCount);
    }

    public function addRMSUniversities($counsellorID='',$mappingId='')
    {
        $this->usergroupAllowed = array('saAdmin','saRMS');
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();
        // prepare the display data here
        if($counsellorID !='' && $mappingId!='')
        {
            $displayData['formName']     = ENT_SA_FORM_EDIT_RMS_UNIVERSITY_COUNSELLOR_MAPPING;
            $displayData['mappingId']    = $mappingId;
            $this->_getRMSUniversityMappingDetail($mappingId,$displayData);
            //$displayData['counsellorID'] = $counsellorID;
            $this->_getAllUnivertyMappedToCounsellor($displayData,$displayData['counsellorID']);
        }elseif($counsellorID !=''){
            $displayData['formName'] 	 = ENT_SA_FORM_ADD_RMS_UNIVERSITY_COUNSELLOR_MAPPING;
            $displayData['counsellorID'] = $counsellorID;
            $displayData['mappingId']    = '';
            $this->_getAllUnivertyMappedToCounsellor($displayData,$displayData['counsellorID']);
        }
        else{
            $displayData['formName'] 	 = ENT_SA_FORM_ADD_RMS_UNIVERSITY_COUNSELLOR_MAPPING;
            $displayData['counsellorID'] = '';
            $displayData['mappingId']    = '';
        }
        $displayData['selectLeftNav']    = "RMS_UNIVERSITIES";
        $this->_populateRMSCounsellors($displayData);
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function _getRMSUniversityMappingDetail($mappingId,& $displayData)
    {
        if(is_numeric($mappingId)){
            $data =  $this->abroadCmsModelObj->rmsUniversityMappingDetail($mappingId);
            if(count($data)>0)
            {
                $displayData['counsellorID'] = $data[0]['counsellor_id'];
                $displayData['sessionDate'] = $data[0]['sDate'];
                $displayData['university_id'] = $data[0]['university_id'];
                $displayData['sessionDetails'] = $data[0]['sessionDetails'];
                $displayData['universityRepImageUrl'] = $data[0]['universityRepImageUrl'];
                $displayData['universityRepName'] = $data[0]['universityRepName'];
                $displayData['universityRepDesignation'] = $data[0]['universityRepDesignation'];
                $displayData['aboutSession'] = $data[0]['aboutSession'];
                $displayData['university_name'] = htmlentities($data[0]['UniName']);
                $displayData['mappingID'] = $mappingId;
                $displayData['created'] = $data[0]['created'];
                $displayData['created_by'] = $data[0]['created_by'];
                $displayData['modifiedname'] = $data[0]['displayname'];
                $displayData['last_modified'] = $data[0]['last_modified'];
                $displayData['last_modified_by'] = $data[0]['last_modified_by'];
            }
        }

    }

    public function checkUniversityIsMapped($data,$mappingID)
    {
        $final = array();
        foreach($data['universityId'] as $key=>$universityId){
            $result= $this->abroadCmsModelObj->checkUniversityIsMapped($universityId,$mappingID);
            if($result[0]['total'] >0){
                $final['already'][] = $data['sectionindex'][$key];
            }
        }
        return $final;

    }

    public function saveUniversityCounsellorMapping()
    {
        $userDetails = $this->cmsAbroadUserValidation();
        $appId = 1;
        $data = array();

        $formName = trim($this->input->post("formName"));
        $univRepImgUrl = $this->universityRepMediaUpload('image','universityRepImage','repImg_'.$formName);

        if(array_key_exists('Fail',$univRepImgUrl)){
            unset($univRepImgUrl['Fail']);
            $fileError = array('fileError'=>$univRepImgUrl);
            echo json_encode($fileError);
            exit;
        }
        $data['universityRepImageUrl'] = array_map(function($a){
            return $a['url'];},$univRepImgUrl);


        $data["counsellorId"] = $this->input->post("counsellorName");
        $data["universityId"] = $this->input->post("universityId");
        $data["sessionDetail"] = $this->input->post("sessionDetail");
        $data["sectionindex"] = $this->input->post("sectionindex");
        $data["sessionDate"] = $this->input->post("sessionDate");
        $data["universityRepName"] = $this->input->post("universityRepName_".$formName);
        $data["universityRepDesignation"] = $this->input->post("universityRepDesignation_".$formName);
        $data["aboutSession"] = $this->input->post("aboutSession_".$formName);
        $data["universityRepName"] = $this->input->post("universityRepName_".$formName);
        $data['created'] = date('Y-m-d H:i:s');
        $data['createdBy'] = $userDetails['userid'];
        $data['modifiedBy'] = $userDetails['userid'];

        //The same date check is removed so commenting this validation call
        $result =  $this->checkUniversityIsMapped($data,'');
        if(!empty($result))
        {
            echo json_encode($result);

        }else{
            $result =  $this->abroadCmsModelObj->saveRMSUniversityCounsellorMapping($data);
            echo json_encode($result);
        }
    }


    public function editUniversityCounsellorMapping()
    {
        $userDetails = $this->cmsAbroadUserValidation();
        $appId = 1;
        $data = array();

        $formName = trim($this->input->post("formName"));
        $univRepImgUrl = $this->universityRepMediaUpload('image','universityRepImage','repImg_'.$formName);

        if(array_key_exists('Fail',$univRepImgUrl)){
            unset($univRepImgUrl['Fail']);
            $fileError = array('fileError'=>$univRepImgUrl);
            echo json_encode($fileError);
            exit;
        }

        $data['universityRepImageUrl'] = array_map(function($a){
            return $a['url'];},$univRepImgUrl);

        if(count($data['universityRepImageUrl'])==0){
            $data['universityRepImageUrl'][] = $this->input->post("repImg_".$formName."_hidden");
        }

        $data["counsellorId"] = $this->input->post("counsellorName");
        $data["universityId"] = $this->input->post("universityId");
        $data["sessionDetail"] = $this->input->post("sessionDetail");
        $data["sessionDate"] = $this->input->post("sessionDate");
        $data["universityRepName"] = $this->input->post("universityRepName_".$formName);
        $data["universityRepDesignation"] = $this->input->post("universityRepDesignation_".$formName);
        $data["aboutSession"] = $this->input->post("aboutSession_".$formName);
        $data['createdBy'] = $this->input->post("created_by");
        $data['created'] = $this->input->post("created");
        $data['modifiedBy'] = $userDetails['userid'];
        $data['mappingId'] = $this->input->post("mappingID");

        $result =  $this->checkUniversityIsMapped($data,$data['mappingId']);
        if(!empty($result))
        {
            echo json_encode($result);

        }else{
            //make old mapping Record As history when Edited
            $this->abroadCmsModelObj->markHistoryOldUniversityCounsellorMapping($data);
            //Insert new Record Here
            $result =  $this->abroadCmsModelObj->saveRMSUniversityCounsellorMapping($data);
            echo json_encode($result);
        }
    }


    public function addRMSCounsellor(){
        $this->usergroupAllowed = array('saAdmin','saRMS');
        $displayData = $this->cmsAbroadUserValidation();
        $displayData['formName'] = ENT_SA_FORM_ADD_RMS_COUNSELLOR;
        $managers = $this->abroadCmsModelObj->getRMSManagersInfo();
        $displayData['managers'] = $managers;
        $displayData['saveFunctionName'] = "saveRMSCounsellorData/".ENT_SA_FORM_ADD_RMS_COUNSELLOR;
        $displayData['selectLeftNav'] = "RMS_COUNSELLORS";
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function editRMSCounsellor(){
        $counsellorId = $_REQUEST["RMSId"];
        $this->usergroupAllowed = array('saAdmin','saRMS');
        $displayData = $this->cmsAbroadUserValidation();
        $displayData['formName'] = ENT_SA_FORM_EDIT_RMS_COUNSELLOR;
        $managers = $this->abroadCmsModelObj->getRMSManagersInfo();
        $displayData['managers'] = $managers;
        $displayData['saveFunctionName'] = "saveRMSCounsellorData/".ENT_SA_FORM_EDIT_RMS_COUNSELLOR;
        $displayData['selectLeftNav'] = "RMS_COUNSELLORS";
        $displayData['counsellor'] = $this->abroadCmsModelObj->getRMSCounsellorInfo($counsellorId);
        $userModel = $this->load->model('user/usermodel');
        $userObj = $userModel->getUserById($displayData['counsellor']['last_modified_by']);
        $userName = $userObj->getFirstName()." ".$userObj->getLastName();
        $displayData['counsellor']['last_modified_by_name'] = $userName;

        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }


    public function saveRMSCounsellorData($formName){
        $this->usergroupAllowed = array('saAdmin','saRMS');
        if($formName == ENT_SA_FORM_ADD_RMS_COUNSELLOR){
            //$data['id'] = Modules::run('common/IDGenerator/generateId', 'RMS_counsellor');
            $data['id'] = $this->input->post('counsellorUserId');
        }else if($formName == ENT_SA_FORM_EDIT_RMS_COUNSELLOR){
            $data['id'] = $this->input->post("counsellorId");
            $data['created'] = $this->input->post("created");
            $data['created_by'] = $this->input->post("createdBy");
            $data['image'] = $this->input->post("counselorImageUrl");
        }
        // Also change data for space correction
        $data['name'] = trim($this->input->post("counsellorName_$formName",true));
        $data['email'] = trim($this->input->post("counsellorEmail_$formName",true));
        $data['mobile'] = trim($this->input->post("counsellorMobile_$formName",true));
        $data['bio'] = trim($this->input->post("counsellorBio_$formName",true));
        $data['expertise'] = trim($this->input->post("counsellorExpertise_$formName",true));
        $data['managerId'] = trim($this->input->post("manager_$formName",true));
        $data['isManager'] = trim($this->input->post("counsellorIsManager_$formName",true));

        if($data['image'] == ""){
            // upload
            $imageResp = $this->_counsellorImageUpload('saCounselorImage',"counsellorImage_$formName");
            if(isset($imageResp['error'])) {
                $imageRespArray ['fileError'] = 'Only '. $imageResp['error'];
                $exitFlag = true;
            }
            if ((isset($imageResp)) && (count($imageResp) > 0)) {

                $logoRespWidth = (int)$imageResp['width'];
                $logoRespHeight = (int)$imageResp['height'];

                if($imageResp['error'] != "")
                {
                    $imageRespArray['fileError'] = $imageResp['error'];
                    $exitFlag = true;
                }
            }
            if($exitFlag) {
                echo json_encode($imageRespArray);
                return false;
            }else{
                $data['image'] = $imageResp['url'];
            }
        }
        //rest
        $validity = $this->cmsAbroadUserValidation();
        $data['userid'] = $validity['userid'];
        if($formName == ENT_SA_FORM_ADD_RMS_COUNSELLOR){
            $urlData = $this->abroadPostingLib->generateCounsellorPageURL($data['name']);
            $data['seoUrl'] = $urlData['seoUrl'];
            $result = $this->abroadCmsModelObj->addRMSCounsellor($data);
        }else if($formName == ENT_SA_FORM_EDIT_RMS_COUNSELLOR){
            $data['seoUrl'] = trim($this->input->post("seoUrl"));
            $result = $this->abroadCmsModelObj->updateRMSCounsellor($data);
        }
        if($result === true){
            echo true;
        }
        else if(is_array($result) && count($result) > 0){
            echo json_encode($result);
        }


    }

    public function findUniversityName(){
        $universityId 	= $this->input->post("listing_id");
        $listing_type 	= $this->input->post("listing_type");

        $result =array();

        //$data = $this->abroadCommonLib->getUniversityDetails($universityId, ENT_SA_PRE_LIVE_STATUS);
        $data = $this->abroadCommonLib->getPaidUniversityDetails($universityId, ENT_SA_PRE_LIVE_STATUS);

        if(isset($data['name']))
        {
            $result['errorFlag'] = 0;
            $result['name'] = htmlentities($data['name']);
            $result['universityId'] = $universityId;
        }else{
            $result['errorFlag'] = 1;
            $result['errorMsg'] = 'No such paid university exist';
        }

        echo json_encode($result);
    }

    public function exportRmsCounsellorResponse($mappingId){
        $this->usergroupAllowed = array('saAdmin','saRMS');
        $displayData = $this->cmsAbroadUserValidation();
        if($mappingId !=''){
            $detailsArray = array();
            $ids = explode(',',$mappingId);
            $ids = array_unique($ids);
            foreach($ids as $id){
                $displayData = array();
                $this->_getRMSUniversityMappingDetail($id, $displayData);
                if(!empty($displayData)){
                    $detailsArray[] = $displayData;
                }
            }
            $responseDataResult = $this->abroadPostingLib->getRmsDataForExport($detailsArray);
            $this->_createCSVFromData($responseDataResult);
            exit();
        }
    }

    public function _createCSVFromData($responseDataResult){
        $this->load->library('common/PHPExcel');
        //_p($responseDataResult);
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A1','Name');
        $objPHPExcel->getActiveSheet()->setCellValue('B1','Email');
        $objPHPExcel->getActiveSheet()->setCellValue('C1','Mobile');
        $objPHPExcel->getActiveSheet()->setCellValue('D1','Institute Name');
        $objPHPExcel->getActiveSheet()->setCellValue('E1','Response Date');
        $objPHPExcel->getActiveSheet()->setCellValue('F1','Response Time');
        $objPHPExcel->getActiveSheet()->setCellValue('G1','Response to');
        $objPHPExcel->getActiveSheet()->setCellValue('H1','Field of Interest');
        $objPHPExcel->getActiveSheet()->setCellValue('I1','Desired Course Level');
        $objPHPExcel->getActiveSheet()->setCellValue('J1','Specialization');
        $objPHPExcel->getActiveSheet()->setCellValue('K1','Exams Taken');
        $objPHPExcel->getActiveSheet()->setCellValue('L1','Preferred Country');
        $objPHPExcel->getActiveSheet()->setCellValue('M1','Plan to Start');
        $objPHPExcel->getActiveSheet()->setCellValue('N1','Student Passport');
        $objPHPExcel->getActiveSheet()->setCellValue('O1','Current Location');
        $objPHPExcel->getActiveSheet()->setCellValue('P1','Is in NDNC List');
        $objPHPExcel->getActiveSheet()->setCellValue('Q1','Source');

        $count =1;
        foreach($responseDataResult as $responseData)
        {
            $count++;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$count, $responseData['displayName']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$count, $responseData['email']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$count, $responseData['contact_cell']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$count, $responseData['courseData']->getUniversityName());
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$count, $responseData['submit_date']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$count, $responseData['submit_time']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$count, $responseData['courseData']->getName());

            $objPHPExcel->getActiveSheet()->setCellValue('H'.$count, $responseData['parentCategoryData']);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$count, $responseData['courseData']->getCourseLevel1Value());
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$count, $responseData['categoryData']);

            $examString = '';
            global $examGrades;
            foreach($responseData['examdata'] as $tuple){
                if($tuple['Name']=='CAE'){
                    $examString.= $tuple['Name']." ( ".$examGrades[$tuple['Name']][(int)$tuple['Marks']]." grade ),";
                }else{
                    $examString.= $tuple['Name']." ( ".$tuple['Marks']." ".$tuple['MarksType']." ),";
                }
            }
            $examString = substr($examString,0,strlen($examString)-1);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$count, $examString);

            $prefCountry = '';
            foreach($responseData['locationData'] as $tuple){
                $prefCountry.= $tuple['name'].",";
            }
            $prefCountry = substr($prefCountry,0,strlen($prefCountry)-1);
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$count, $prefCountry);

            $timeOfStart = '';
            $timeOfStart = $responseData['prefData'][0]['TimeOfStart'];
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$count, $timeOfStart);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$count, $responseData['passport']);

            if($responseData['city_name']==''){
                $city =  $responseData['city'];
            }else{
                $city =  $responseData['city_name'];
            }
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$count, $city);

            $objPHPExcel->getActiveSheet()->setCellValue('P'.$count, $responseData['isNDNC']);
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$count, $responseData['source']);

        }
        $documentName = "RMS_COUNSELLOR_RESPONSE_".date('Y_m_d_h_i_s').'.csv';
        $documentURL = "/tmp/".$documentName;

        $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
        $objWriter->setDelimiter(',');
        $objWriter->setEnclosure('"');
        $objWriter->setLineEnding("\r\n");
        $objWriter->setSheetIndex(0);
        //$objWriter->save($documentURL);
        //die;
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="'.$documentName.'"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }

    /*public function getNotFoundExternalURL(){
        $this->benchmark->mark('getNotFoundExternalURL_start');
        set_time_limit(0);
        $this->config->load('studyAbroadCMSConfigForExternalLinks');
        $externalUrls = $this->config->item('EXTERNAL_URLS');
        $this->load->library('common/PHPExcel');
        $objPHPExcel = new PHPExcel();
        $sheetCount = 0;
        $executionTime = array();
        foreach($externalUrls as $entitySet=>$fieldNames){
            //++$sheetCount;
            $this->benchmark->mark($entitySet.'_start');
            $currentSheet = $objPHPExcel->createSheet($sheetCount++);
            $currentSheet->setTitle($entitySet);
            $currentSheet->setCellValue('A1',$entitySet.' ID');
            $currentNonCheckedUrlSheet  = $objPHPExcel->createSheet($sheetCount++);
            $currentNonCheckedUrlSheet->setTitle($entitySet."_urlsNotResponded");
            $currentNonCheckedUrlSheet->setCellValue('A1',$entitySet.' ID');
            $alpha = 'A';
            foreach($fieldNames as $formField=>$tableInfo){
                ++$alpha;
                $sheetRowCount = 1;
                $currentSheet->setCellValue($alpha.$sheetRowCount,$formField);
                $currentNonCheckedUrlSheet->setCellValue($alpha.$sheetRowCount,$formField);
                $tableName = $tableInfo['table'];
                $columnName= $tableInfo['column'];
                $entityFieldName = $tableInfo['entityFieldName'];
                error_log("tableName:".$tableName."  columnName".$columnName." entityFieldName".$entityFieldName);
                $whereClause = '';
                foreach($tableInfo as $key=>$value){
                    if(!in_array($key, array('table','column','entityFieldName'))){
                        $whereClause .= " AND ".$key."='".$value."' ";
                    }
                }
                $lowerLimit = 0;
                $upperLimit = 500;
                while(TRUE){
                    $dataResult = $this->abroadPostingLib->getNotFoundExternalURL($tableName,$columnName,$entityFieldName,$whereClause,$lowerLimit,$upperLimit);
                    $invalidUrls = $this->_checkInvalidUrls($dataResult['data'],$columnName,$entityFieldName);
                    foreach($invalidUrls as $key=>$value){
                        if($value['httpStatus'] == 404){
                            $rowNumber = $this->checkIfValueExist($currentSheet,'A',$key);
                            if($rowNumber <= 0){
                                $addRowNumber = $currentSheet->getHighestRow()+1;
                                $currentSheet->setCellValue('A'.$addRowNumber,$key);
                                $currentSheet->setCellValue($alpha.$addRowNumber,$value['url']);
                            }else{
                                $currentSheet->setCellValue($alpha.$rowNumber,$value['url']);
                            }
                        }elseif($value['httpStatus'] == 0){
                            $rowNumber = $this->checkIfValueExist($currentNonCheckedUrlSheet,'A',$key);
                            if($rowNumber <= 0){
                                $addRowNumber = $currentNonCheckedUrlSheet->getHighestRow()+1;
                                $currentNonCheckedUrlSheet->setCellValue('A'.$addRowNumber,$key);
                                $currentNonCheckedUrlSheet->setCellValue($alpha.$addRowNumber,$value['url']);
                            }else{
                                $currentNonCheckedUrlSheet->setCellValue($alpha.$rowNumber,$value['url']);
                            }
                        }
                    }
                    $lowerLimit += 500;
                    if($lowerLimit > $dataResult['rowsCount']['recordCount']){
                        break;
                    }
                }
            }
            $this->benchmark->mark($entitySet.'_end');
            $executionTime[$entitySet] = $this->benchmark->elapsed_time($entitySet.'_start', $entitySet.'_end');
        }

        /*header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"invalidUrls.xlsx\"");
        header("Cache-Control: max-age=0");
        /

        $this->benchmark->mark('getNotFoundExternalURL_end');

        $currentSheet = $objPHPExcel->createSheet($sheetCount++);
        $currentSheet->setTitle("Performance Measure");
        $currentSheet->setCellValue('A1','Entity');
        $currentSheet->setCellValue('B1','Total Execution Time');
        $i = 2;
        foreach($executionTime as $key=>$timeTaken){
            $currentSheet->setCellValue('A'.$i,$key);
            $currentSheet->setCellValue('B'.$i,$timeTaken);
            ++$i;
        }

        $currentSheet->setCellValue('A'.$i,'Function Execution');
        $currentSheet->setCellValue('B'.$i,$this->benchmark->elapsed_time('getNotFoundExternalURL_start', 'getNotFoundExternalURL_end'));

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

        ob_clean();
        $objWriter->save("/tmp/invalidUrls_".date('Y_m_d_h_i_s').".xlsx");
    }*/

    public function checkIfValueExist($workingSheet,$column,$value) {
        $lastRow = $workingSheet->getHighestRow();
        for($i=1;$i<$lastRow;$i++){
            if($workingSheet->getCell($column.$i)->getFormattedValue() == $value){
                return $i;
            }
        }
        return -1;
    }

    private function _checkInvalidUrls($data,$columnName,$entityFeildName){
        set_time_limit(0);
        $resultArray = array();
        $rollingWindow = 50;
        $rollingWindow = (count($data)<$rollingWindow)?count($data):$rollingWindow;
        $master = curl_multi_init();
        //$agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
        $curlArray = array();
        $headers = array('Content-type: charset=utf-8','Connection: Keep-Alive');
        $stdOptions = array(CURLOPT_RETURNTRANSFER  => TRUE,
            //CURLOPT_HTTPHEADER      => $headers
            //CURLOPT_FOLLOWLOCATION  => TRUE,
            //CURLOPT_MAXREDIRS       => 5,
            //                 CURLOPT_USERAGENT       => $agent,
            //                  CURLOPT_HEADER          => TRUE,
            //                CURLOPT_NOBODY          => TRUE,
            CURLOPT_TIMEOUT         => 60,
            CURLOPT_CONNECTTIMEOUT  => 60
        );
        $options = $stdOptions;

        for($i=0;$i<$rollingWindow;$i++){
            $ch = curl_init();
            $parseUrl = parse_url($data[$i][$columnName]);
            $urlToTest = "http://".$parseUrl['host']. str_replace(' ','%20',$parseUrl['path']).(!empty($parseUrl['query'])?"?".str_replace(' ','%20',$parseUrl['query']):"");
            $options[CURLOPT_URL] = $urlToTest;
            curl_setopt_array($ch, $options);
            curl_multi_add_handle($master, $ch);
        }
        error_log("CURL Execution Begins");
        $urlDone = 0;
        do{
            while(($execrun = curl_multi_exec($master, $running))== CURLM_CALL_MULTI_PERFORM);
            if($execrun != CURLM_OK)
                break;
            while($done = curl_multi_info_read($master)){
                $info = curl_getinfo($done['handle']);
                //_p($info);
                error_log("urls_executed:".++$urlDone." :: ".$info[http_code]." ".$info['url']);
                if($info['http_code'] == 404 || $info['http_code'] == 0){
                    $index = $this->arraySearch($data, $columnName, $info['url']);
                    if($index >= 0){
                        $resultArray[$data[$index][$entityFeildName]]=array('httpStatus'    => $info['http_code'],
                            'url'           => $data[$index][$columnName]
                        );
                    }
                    if($i<count($data)){
                        $ch = curl_init();
                        $parseUrl = parse_url($data[$i++][$columnName]);
                        $urlToTest = "http://".$parseUrl['host']. str_replace(' ','%20',$parseUrl['path']).(!empty($parseUrl['query'])?"?".str_replace(' ','%20',$parseUrl['query']):"");
                        $options[CURLOPT_URL] = $urlToTest;
                        curl_setopt_array($ch, $options);
                        curl_multi_add_handle($master, $ch);
                    }
                    //curl_multi_remove_handle($master, $done['handle']);
                }else{
                    if($i<count($data)){
                        $ch = curl_init();
                        $parseUrl = parse_url($data[$i++][$columnName]);
                        $urlToTest = "http://".$parseUrl['host']. str_replace(' ','%20',$parseUrl['path']).(!empty($parseUrl['query'])?"?".str_replace(' ','%20',$parseUrl['query']):"");
                        $options[CURLOPT_URL] = $urlToTest;
                        curl_setopt_array($ch, $options);
                        curl_multi_add_handle($master, $ch);
                    }
                    //curl_multi_remove_handle($master, $done['handle']);
                }
                curl_multi_remove_handle($master, $done['handle']);
            }
        }while($running);
        error_log("CURL Execution ENDS");
        curl_multi_close($master);
        return $resultArray;
    }

    function arraySearch($data,$indexToSearch,$valueToSearch){
        foreach($data as $key=>$value){
            $parseUrl = parse_url($value[$indexToSearch]);
            $urlToTest = "http://".$parseUrl['host']. str_replace(' ','%20',$parseUrl['path']).(!empty($parseUrl['query'])?"?".str_replace(' ','%20',$parseUrl['query']):"");
            //error_log("valueToSearch : ".$valueToSearch." :: urlToTest".$urlToTest);
            if($urlToTest == $valueToSearch){
                return $key;
            }
        }
        return -1;
    }
    /*
	 * function to add country along with currency & its exchange rates
	 * values required : countryName,currencyName,currencySymbol,conversionFactor,continentId,tier
	 */
    public function addCountry()
    {
        $validity = $this->cmsAbroadUserValidation();
        //echo "DDD:::".$validity['usergroup'];
        $usergroup = $validity['usergroup'];
        if($usergroup=='saAdmin')
        {
            $data = array(
                'countryName'		=>'Barbados',
                'currencyName'	=>'Barbados Dollars',
                'currencySymbol'	=>'BBD',
                'conversionFactor'=>'30.9',
                'continentId'		=>'5',
                'tier'			=>'2'
            );
            $res = $this->abroadCmsModelObj->addCountryWithCurrency($data);
            if($res)
            {
                // update location cache
                $countries = $this->locationRepository->getAbroadCountries();
                echo "Country added successfully. Please ensure addition of respective flags.";
            }
            else{
                echo "Something went wrong.";
            }
        }
        else{
            header("location:/enterprise/Enterprise/disallowedAccess");
        }
        exit();
    }

    private function _removePaidUniversities($dataArray,$countryId){
        $paidUniversities = $this->abroadCMSModelObj->getPaidUniversitiesOfCountry($countryId);
        foreach($paidUniversities as $univId){
            unset($dataArray[$univId]);
        }
        return $dataArray;

    }

    /*
	 * function to collect post data for shiksha apply section in university form
	 */
    private function _getUnivShikshaApplyPostData(&$univFormData)
    {
        $shikshaApplyUnivCheckbox = $this->input->post("shikshaApplyUnivCheckbox");
        if($shikshaApplyUnivCheckbox!='on')
        {
            return false;
        }
        $univFormData['applicationProfileId']			= $this->input->post("univApplicationProfileId");
        $univFormData["univApplicationProfileName"] 	= $this->input->post("univApplicationProfileName");
        $univFormData["univApplicationProfileAddedAt"] 	= $this->input->post("univApplicationProfileAddedAt");
        $univFormData["univApplicationProfileAddedBy"] 	= $this->input->post("univApplicationProfileAddedBy");
        $univFormData["univSOPRequired"] 				= $this->input->post("univSOPRequiredReal");
        $univFormData["univSOPComments"] 				= $this->input->post("univSOPComments");
        $univFormData["univLORRequired"] 				= $this->input->post("univLORRequiredReal");
        $univFormData["univLORComments"] 				= $this->input->post("univLORComments");
        $univFormData["univEssayRequired"] 				= $this->input->post("univEssayRequiredReal");
        $univFormData["univEssayComments"] 				= $this->input->post("univEssayComments");
        $univFormData["univCVRequired"] 				= $this->input->post("univCVRequiredReal");
        $univFormData["univCVComments"] 				= $this->input->post("univCVComments");
        $univFormData["univAllDocuments"] 				= $this->input->post("univAllDocuments");
        $univFormData["univAdmissionType"] 				= $this->input->post("univAdmissionType");
        $univFormData["univApplicationSubmissionName"] 	= $this->input->post("univApplicationSubmissionName");
        $univFormData["lastdate"] 						= $this->input->post("lastdate");
        $univFormData["intakeSeason"] 					= $this->input->post("univApplicationIntakeSeason");
        $univFormData["intakeYear"] 					= $this->input->post("univApplicationIntakeYear");
        $univFormData["intakeMonth"] 					= $this->input->post("univApplicationIntakeMonth");
        $univFormData["intakeRound"] 					= $this->input->post("univApplicationIntakeRound");
        $univFormData["univApplicationFAQLink"] 		= $this->input->post("univApplicationFAQLink");
        $univFormData["univApplyNowLink"] 				= $this->input->post("univApplyNowLink");

        $univFormData["univApplicationProcessUploadLink"]= $this->input->post("univApplicationProcessUploadLink");
        //_p($univFormData["univApplicationProcessUploadLink"]);
        $univApplicationProcess =$files= array();
        $files['univApplicationProcessUpload'] = $_FILES['univApplicationProcessUpload'];
        $_FILES['univApplicationProcessUpload'] = array();
        for($i=0;$i<count($files['univApplicationProcessUpload']['tmp_name']);$i++)
        {
            $_FILES['univApplicationProcessUpload']['tmp_name'][0] = $files['univApplicationProcessUpload']['tmp_name'][$i];
            $_FILES['univApplicationProcessUpload']['name'][0] = $files['univApplicationProcessUpload']['name'][$i];
            $_FILES['univApplicationProcessUpload']['type'][0] = $files['univApplicationProcessUpload']['type'][$i];
            $_FILES['univApplicationProcessUpload']['error'][0] = $files['univApplicationProcessUpload']['error'][$i];
            $_FILES['univApplicationProcessUpload']['size'][0] = $files['univApplicationProcessUpload']['size'][$i];
            //_p($_FILES['univApplicationProcessUpload']);
            $univApplicationProcess[$i] = $this->listingBrochureUpload('university','univApplicationProcessUpload');
        }
        //_p($univApplicationProcess);
        $uploadErrorDetected = false;
        $uploadErrors = array();
        foreach($univApplicationProcess as $k=>$v)
        {
            if(array_key_exists('Fail', $v)) {
                $uploadErrors[] = $v['Fail']['univApplicationProcessUpload'];
                $uploadErrorDetected = true;
                //echo json_encode($v);
                continue;
                //exit;
            }else{
                $uploadErrors[] = "";
            }

            if($univFormData["univApplicationProcessUploadLink"][$k]!=""){
                $univApplicationProcess[$k] = $univFormData["univApplicationProcessUploadLink"][$k];
            }
        }
        if ($uploadErrorDetected)
        {
            echo json_encode(array('Fail'=>array('univApplicationProcessUpload'=>$uploadErrors)));
            exit;
        }
        if(is_null($univApplicationProcess))
        {
            $content = array("universityFormData"=>$univFormData,
                "uploadResponse"=>$univApplicationProcess,
                "datetime"=>date("Y-m-d H:i:s"),
                "host"=>MEDIA_SERVER_IP,
                'filepath'=>"/var/www/html/shiksha/application/modules/Listing/listingPosting/controllers/AbroadListingPosting.php",
                'function'=>"_getUnivShikshaApplyPostData");
            sendMails(
                array('recipients'=>array('to'=>'satech@shiksha.com'),
                    'sender'=>SA_ADMIN_EMAIL,
                    'subject'=>"Invalid URL for uploaded file(s)",
                    'mailContent'=>"<pre>".print_r($content,true)."</pre>"
                )
            );
            echo json_encode(array('Fail'=>array('univApplicationProcessUpload'=>array('Unable to upload.'))));
            exit;
        }
        $univFormData['univApplicationProcess'] = $univApplicationProcess;
    }

    public function getShikshaApplyProfileForUniversity(){
        $universityId = $this->input->post('universityId');
        $profileData = array();
        if($universityId  >0){

            $profileData = $this->abroadCommonLib->getShikshaApplyProfileForUniversity($universityId);

        }
        echo json_encode($profileData);
    }

    public function _removeRmcCounsellorMappedUniversities($dataArray)
    {
        $this->rmcPostingLib 		= $this->load->library('shikshaApplyCRM/rmcPostingLib');
        $params['removeRmcCounsellorUniv'] =1;
        $rmcConsellorUnivIds = $this->rmcPostingLib->getRMCUniversityCounsellorMappingData($params);

        foreach($rmcConsellorUnivIds as $univId)
        {
            unset($dataArray[$univId]);
        }
        return $dataArray;

    }

    private function universityRepMediaUpload($uploadType,$listing_type,$fieldName)
    {
        $appId = 1;
        if(array_key_exists($fieldName, $_FILES) && !empty($_FILES[$fieldName]['tmp_name'][0])) {
            $return_response_array = array();
            $this->load->library('upload_client');
            $uploadClient = new Upload_client();

            $errorFlag = false;
            for($x=0;$x<count($_FILES [$fieldName] ['type']);$x++)
            {
                $type = $_FILES [$fieldName] ['type'] [$x];
                $type = trim($type,'"');

                if (! ($type == "image/gif" || $type == "image/jpeg" || $type == "image/jpg" || $type == "image/png")) {
                    $return_response_array[$x]['Fail'] = "Only Images of type jpeg,gif,png are allowed.";
                    $errorFlag = true;
                } else {
                    $sizeImage = $_FILES [$fieldName] ['size'] [$x];
                    $tmpDimensions = getimagesize ( $_FILES [$fieldName] ['tmp_name'] [$x] );
                    list ( $width, $height ) = $tmpDimensions;
                    if ($width != 172 || $height != 115)
                    {
                        $return_response_array[$x]['Fail']= "Image size should be equal to 172px X 115px.";
                        $errorFlag = true;
                    }else{
                        $return_response_array[$x]['OK']= true;
                    }
                }
            }
            //_p($return_response_array);
            // all well, upload now
            if($errorFlag==true)
            {
                $return_response_array['Fail'] = true;
                return $return_response_array;
            }
            else{
                $upload_array = $uploadClient->uploadFile($appId,$uploadType,$_FILES,array(),"-1",$listing_type,$fieldName);
            }
            //_p($upload_array);
            // check the response from upload library
            if(is_array($upload_array) && $upload_array['status'] == 1) {
                foreach($return_response_array as $key=>$fileDetail){
                    $return_response_array[$key]['url'] = $upload_array[$key]['imageurl'];
                }
            } else {
                if($upload_array == 'Size limit of 50 Mb exceeded') {
                    $upload_array = "Please upload a image less than 50 MB in size";
                }
                $return_response_array['Fail'][$fieldName] = $upload_array;
            }
            return $return_response_array;
        } else {
            return "";
        }
    }

    function deleteRMSUniversityCounsellorMapping(){
        $this->usergroupAllowed = array('saAdmin','saRMS');
        $mappingId = $this->input->post("mappingId");
        if(empty($mappingId))
        {
            echo "Invalid mapping Id.";
            exit(0);
        }
        $userData = $this->cmsAbroadUserValidation();
        try {
            $status = $this->abroadPostingLib->deleteRMSUniversityCounsellorMapping($mappingId, $userData['userid']);
        } catch(Exception $e) {
            $status =0;
        }
        echo "Mapping Successfully Deleted !!!";
    }
    /*
    * function to get a counsellor's details from tuser using their email id
    */
    public function getCounsellorUserDataByEmail()
    {
        $email = $this->input->post('counsellorEmail');
        $this->load->model('user/usermodel');
        $usermodel = new usermodel;
        $user = $usermodel->getUserByEmail($email);
        $counsellor = array();
        if(is_object($user)){
            if($user->getUsergroup() == 'saShikshaApply'){
                $counsellor['userId'] = $user->getId();
                $counsellor['name'] = $user->getFirstName();
                $counsellor['name'] .= " ".$user->getLastName();
                $counsellor['mobile'] = $user->getMobile();
                $counsellor['email'] = $user->getEmail();
            }else{
                $counsellor = false;
            }
        }
        else{
            $counsellor = false;
        }
        echo json_encode($counsellor);
    }


    private function _prepareContentTableData(&$result)
    {
        $parentCategoryIds = array();
        foreach ($result as $resultData)
        {
            if(!empty($resultData['parentCatgoryIds']))
            {
                $iDs =	explode(',',$resultData['parentCatgoryIds']);
                if(count($iDs)>0)
                {
                    $parentCategoryIds  = array_merge($parentCategoryIds,$iDs);
                }
            }
        }
        array_unique($parentCategoryIds);
        if(count($parentCategoryIds) > 0)
        {  	//Loading CategoryBuilder for getting CategoryRepository
            $this->load->builder('CategoryBuilder','categoryList');
            $categoryBuilder = new CategoryBuilder;
            $categoryRepository = $categoryBuilder->getCategoryRepository();

            //fetch multiple objects for sub-category Ids
            $categoryIds = $categoryRepository->findMultiple($parentCategoryIds);
        }
        //loading config for  Apply Content MasterList
        $this->config->load('abroadApplyContentConfig');
        $applyContentTypes = $this->config->item('applyContentMasterList');

        //get LDB Course Mapping Data
        $ldbCourseMapping = $this->abroadCommonLib->getAbroadMainLDBCourses();
        if(!empty($result))
        {
            foreach ($result as $key => $resultData)
            {
                $result[$key]['courseType'] = array();
                if(!empty($resultData['parentCatgoryIds']))
                {
                    $parentCatIDs =	explode(',',$resultData['parentCatgoryIds']);
                    foreach($parentCatIDs as $id)
                    {
                        if($id>0){
                            $result[$key]['courseType'][] = $categoryIds[$id]->getName();
                        }
                    }
                }
                if(!empty($resultData['ldbCourseIds']))
                {
                    $ldbCourseIds =	explode(',',$resultData['ldbCourseIds']);
                    foreach($ldbCourseIds as $id)
                    {
                        foreach($ldbCourseMapping as $ldbCourse)
                        {
                            if($ldbCourse['SpecializationId'] == $id)
                            {
                                $result[$key]['courseType'][] = $ldbCourse['CourseName'];
                                break;
                            }
                        }
                    }
                }

                if($resultData['ContentType']=="applyContent")
                {
                    $result[$key]['applyContentTag'] = $applyContentTypes[$resultData['appContId']]['type'];
                }
            }
        }
    }
    /*
	 * to check if given apply Content has a homepage, used for ajax
	 */
    public function isHomepageAvailable($applyContentType)
    {
        $hasHomepage = $this->abroadCommonLib->isHomepageAvailable($applyContentType);
        echo json_encode($hasHomepage);
    }

    public function isExamHomepageAvailable($examContentType,$contentId)
    {
        $examDetails = json_decode(base64_decode($examContentType),true);
        $hasHomepage = $this->abroadCommonLib->isExamHomepageAvailable($examDetails['examId'],$contentId);
        echo json_encode($hasHomepage);
    }

    public function populateAbroadSpecializations()
    {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        //check for user validation after making an ajax and redirect incase of logged out or in valid
        $userValidation = $this->cmsAbroadUserValidation(true);
        if(!empty($userValidation['error']) && !empty($userValidation['error_type'])) {
            echo json_encode($userValidation);
            return;
        }

        //sanitize the post request values for the strings we recieved
        $category = $this->input->post('category');
        $category = strip_tags($category);
        //check if they are non numeric
        if(!is_numeric($category)){ echo 0; return;}

        $courseLevel = $this->input->post('courseLevel');
        $courseLevel = strip_tags($courseLevel);
        if(is_numeric($courseLevel)){ echo 0; return; }

        $subCategory = $this->input->post('subCategory');
        $subCategory = strip_tags($subCategory);
        if(!is_numeric($subCategory)){ echo 0; return; }


        $courseSpecializations = $this->abroadCommonLib->getCourseSpecializations($category,$courseLevel,$subCategory);
        echo json_encode($courseSpecializations);
    }

    public function viewSpecializations(){
        $this->usergroupAllowed = array('saCMSLead','saAdmin');
//		$userValidation = $this->cmsAbroadUserValidation(false);
        $displayData = $this->cmsAbroadUserValidation();
        $displayData['formName'] = ENT_SA_VIEW_SPECIALIZATIONS;
        $displayData['selectLeftNav'] = 'SPECIALIZATIONS';
        $data = $this->abroadPostingLib->getSpecializationData();
        $displayData['tableData'] = $data;
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function addEditSpecializations($id){
        $this->usergroupAllowed = array('saCMSLead','saAdmin');
        $displayData = $this->cmsAbroadUserValidation(false);
        $displayData['selectLeftNav'] = 'SPECIALIZATIONS';
        $displayData['formName'] = ENT_SA_SPECIALIZATION_FORM;
        if((integer)($id) > 0){
            $data = $this->abroadPostingLib->getSpecializationData($id);
            $displayData['formData'] = reset($data);
            if(empty($displayData['formData'])){
                show_404_abroad();
                return false;
            }
        }
        $displayData['categorySubcategoryData'] = $this->abroadPostingLib->getCategorySubcategoryMappingData();
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function submitSpecializationForm(){
        $this->usergroupAllowed = array('saCMSLead','saAdmin');
        $userLoggedInData = $this->cmsAbroadUserValidation(true);
        if($userLoggedInData['error'] === "true"){
            echo json_encode(array('error' => 1, 'errorMessage' => $userLoggedInData['errorType']));
            return false;
        }
        $id = $this->input->post('oldCategoryId');
        if((integer)($id) > 0){
            $this->_editSpecializationForm();
        }else if((integer)($id) == 0){
            $this->_addSpecializationForm();
        }else{
            echo json_encode(array('error' => 1, 'errorMessage' => 'Form Error. Please refresh the form and try again.'));
            return false;
        }
        return true;
    }

    private function _editSpecializationForm(){
        $newData = array(
            'SpecializationName' 	=> $this->input->post('name'),
            'CourseDetail'		=> $this->input->post('description')
        );
        $oldData = array(
            'oldCategoryId'	=> (integer) $this->input->post('oldCategoryId'),
            'oldName'		=> $this->input->post('oldName'),
            'oldSubcategoryId' =>$this->input->post('oldSubcategoryId')
        );
        echo json_encode($this->abroadPostingLib->editSpecialization($newData,$oldData));
    }

    private function _addSpecializationForm(){
        $commonData = array(
            'categoryId' 	=> (integer) $this->input->post('categoryId'),
            'subcategoryId' => (integer) $this->input->post('subcategoryId'),
            'name' 			=> $this->input->post('name'),
            'description'	=> $this->input->post('description')
        );
        echo json_encode($this->abroadPostingLib->addNewSpecialization($commonData));
    }

    function checkSpecializationMappings()
    {
        $id = $this->input->post("parms");
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        $mode='read';
        if(!(isset($this->deleteFlag) && ($this->deleteFlag)))
            $this->cmsAbroadUserValidation();
        else
        {
            $mode='write';
        }
        if(empty($id))
        {
            echo json_encode("Invalid Specialization Id.");
        }
        $data = $this->_checkIfSpecializationMappedToListing($id,$mode);
        if($data['status'])
        {
            $scholarshipCount = $data['data']['scholarshipCount'];
            $courseCount = $data['data']['courseCount'];
            if($scholarshipCount > 0|| $courseCount > 0)
            {
                $str = 'This specialization is mapped to';
                $flagCourse = false;
                if($courseCount  > 0)
                {
                    $str .= ' '.$courseCount .' course'.(($courseCount==1)?'':'s');
                    $flagCourse = true;
                }
                if($scholarshipCount  > 0)
                {
                    $str .= $flagCourse ?(' & '.$scholarshipCount .' scholarship'.(($scholarshipCount==1)?'':'s'))
                        :(' '.$scholarshipCount .' scholarship'.(($scholarshipCount==1)?'':'s'));
                }
                $str .= '. Please remove the mapping first before deleting this specialization';
                echo json_encode($str);
            }
            else
            {
                if(!(isset($this->deleteFlag) && ($this->deleteFlag)))
                {
                    echo json_encode(1);
                }
                return 1;
            }
        }
        else
        {
            echo json_encode($data['data']);
        }
        return 0;
    }

    private function _checkIfSpecializationMappedToListing($id,$mode='read')
    {
        $data = $this->abroadPostingLib->getSpecializationData($id);
        if(empty($data))
        {
            return array('status'=>false,'data'=>'Invalid Specialization Id');
        }
        $params = array(
            'oldCategoryId'=>$data[0]['categoryId'],
            'oldName'=>$data[0]['name'],
            'oldSubcategoryId'=>$data[0]['subcategoryId']
        );
        $this->specializationIDs = $this->abroadPostingLib->getSpecializationIdsByNameAndCategory($params);
        $scholarshipCount = $this->checkIfListingsMappedToScholarship('specialization',$this->specializationIDs,$mode,true);
        $courseIDs = $this->abroadPostingLib->getCourseIdsMappedToSpecializations($this->specializationIDs,$mode);
        $courseCount = empty($courseIDs)?0:count($courseIDs);
        $scholarshipCount = empty($scholarshipCount)?0:count($scholarshipCount);
        return array('status'=>true,'data'=>array('scholarshipCount'=>$scholarshipCount,'courseCount'=>$courseCount));
    }

    public function deleteSpecializationListing()
    {
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        $id = $this->input->post("parms");
        if(empty($id))
        {
            echo json_encode("Invalid Specialization Id.");
            return 0;
        }
        $this->cmsAbroadUserValidation();
        $this->deleteFlag = true;
        $this->abroadPostingLib->transactionStart();
        $checkData = $this->checkSpecializationMappings();
        if($checkData ==1)
        {
            $this->abroadPostingLib->deleteSpecializations($this->specializationIDs);
        }

        $this->abroadPostingLib->transactionEnd();
        echo json_encode(1);

    }

    public function validateExamContentRedirection(){
        $url = $this->input->post('redirectionURL');
        echo json_encode($this->abroadPostingLib->validateExamContentRedirection($url));
    }

    public function refreshExamMasterList()
    {
        $examMasterList = $this->abroadCommonLib->getAbroadExamsMasterList('',1); // refreshFlag ==1
        _p($examMasterList);
        echo "Exam Master list cache refreshed"; exit();
    }

    private function _counsellorImageUpload($listing_type,$fieldName){
        $appId =1;
        $logoArr = array();
        $this->load->library('upload_client');
        $uploadClient = new Upload_client();
        $imageLogo= array();
        for($i=0;$i<count($_FILES[$fieldName]['name']);$i++){
            $imageLogo[$i] = $_FILES[$fieldName]['name'][$i];
            $type = $_FILES[$fieldName]['type'][$i];
            if (! ($type == "image/jpeg" || $type == "image/jpg")) {
                $logoArr['error'] = "Only Images of type jpg and jpeg are allowed.";
                $logoArr['thumburl'] = "";
                $errorFlag = true;
            }
        }
        if(!$errorFlag){
            if(!(isset($_FILES[$fieldName]['tmp_name'][0]) && ($_FILES[$fieldName]['tmp_name'][0] != ''))){
                $logoArr['thumburl'] = "";
            }else if(isset($_FILES[$fieldName]['tmp_name'][0]) && ($_FILES[$fieldName]['tmp_name'][0] != '')){
                $imageDimension = getimagesize($_FILES[$fieldName]['tmp_name'][0]);
                if(!empty($imageDimension) && ($imageDimension[0]!=1024 || $imageDimension[1]!=1024)){
                    $logoArr['error'] = "Only 1024 pixels of height and 1024 pixels of width allowed for counselor image";
                    $logoArr['thumburl'] = "";
                }else{
                    $i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$imageLogo,"-1",$listing_type,$fieldName);
                    if($i_upload_logo['status'] == 1){
                        for($k = 0;$k < $i_upload_logo['max'] ; $k++){
                            $tmpSize = getimagesize($i_upload_logo[$k]['imageurl']);
                            list($width, $height, $type, $attr) = $tmpSize;
                            $logoArr['width']=$width;
                            $logoArr['height']=$height;
                            $logoArr['type']=$type;
                            $logoArr['mediaid']=$i_upload_logo[$k]['mediaid'];
                            $logoArr['url']=$i_upload_logo[$k]['imageurl'];
                            $logoArr['title']=$i_upload_logo[$k]['title'];
                            $logoArr['thumburl']=$i_upload_logo[$k]['imageurl'];
                        }
                    }else{
                        $logoArr['error'] = $i_upload_logo;
                        $logoArr['thumburl'] = "";
                    }
                }
            }
        }
        $response = $logoArr;
        return $response;
    }

    public function examApplyNavbarLinks($contentType='exam_content', $contentTypeId=false)
    {
        $this->usergroupAllowed = array('saAdmin', 'saCMSLead', 'saContent');
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();
        if(!empty($contentTypeId))
        {
            $displayData['contentTypeId'] = $this->security->xss_clean($contentTypeId);
        }
        else{
            $displayData['contentTypeId'] = false;
        }
        if(empty($this->abroadListingCommonLib))
        {
            $this->load->library('listing/AbroadListingCommonLib');
            $this->abroadListingCommonLib = new AbroadListingCommonLib();
        }
        if($contentType=='exam_content') {
            $displayData['contentTypeList'] = $this->abroadListingCommonLib->getAbroadExamsMasterListFromCache();
        }
        elseif ($contentType=='apply_content'){
            $this->load->config("abroadApplyContentConfig");
            $displayData['contentTypeList'] 		=  $this->config->item('applyContentMasterList');
        }
        if($displayData['contentTypeId'] != false && $contentType == 'exam_content')
        {
            $examFound = false;
            foreach ($displayData['contentTypeList'] as $examTypeData)
            {
                if($examTypeData['examId'] == $displayData['contentTypeId'])
                {
                    $examFound=true;
                    break;
                }
            }
            if(!$examFound)
            {
                header("Location:".ENT_SA_CMS_PATH.ENT_SA_EXAM_NAVBAR_LINKS);die;
            }
        }
        if($displayData['contentTypeId'] != false && $contentType == 'apply_content')
        {
            $applyContentFound = false;
            foreach ($displayData['contentTypeList'] as $key=>$applyContentArr)
            {
                if($key == $displayData['contentTypeId'])
                {
                    $applyContentFound=true;
                    break;
                }
            }
            if(!$applyContentFound)
            {
                header("Location:".ENT_SA_CMS_PATH.ENT_SA_EXAM_NAVBAR_LINKS);die;
            }
        }
        $displayData['contentType'] = $this->security->xss_clean($contentType);
        $displayData['examApplyContentTupleCount'] = ENT_SA_EXAM_NAVBAR_MIN_TUPLE_COUNT; //default
        if($displayData['contentTypeId'] != false)
        {
            $this->abroadPostingLib->prepareExamApplyContentNavbarLinksDataToFill($displayData,$contentTypeId);
        }
        $displayData['formName'] 	= ENT_SA_EXAM_NAVBAR_LINKS;
        $displayData['selectLeftNav']   = "EXAM_LINKS";
        //_p($displayData);die;
        // call the view
        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    public function validateExamApplyContentIdForContentTypeId()
    {
        $examApplyContentId 	= $this->input->post("examApplyContentId",true);
        $contentTypeId 	= $this->input->post("contentTypeId",true);
        $contentType = $this->input->post("contentType",true);
        $contentTypeName = explode('_',$contentType);
        $result  = $this->abroadCommonLib->getContentIdByContentTypeId($contentTypeId,array($examApplyContentId),$contentType);
        if(!is_array($result) || count($result) < 1)
        {
            echo json_encode(array('errorFlag'=>1,'data'=>$contentTypeName[0].' '.$contentTypeName[1].' '.' id \''.$examApplyContentId.'\' does not exist for selected content'));die;
        }
        else
        {
            echo json_encode(array('errorFlag'=>0,'data'=>$result[0]['strip_title']));die;
        }
    }

    public function submitExamApplyNavBarLinksData()
    {
        // get the user data
        try
        {
            $displayData = $this->cmsAbroadUserValidation();
            $formData = array();
            $this->_validateAndPreparexamApplyNavBarLinksData($formData);
            $formData['userid'] 	= $displayData["userid"];
            $this->abroadPostingLib->postExamApplyContentNavbarLinksData($formData);
            echo json_encode(array('status'=>1,'message'=>'Content links saved successfully.'));die;
        }
        catch (Exception $e)
        {
            echo json_encode(array('status'=>0,'message'=>'Some error occured.'));die;
        }
    }

    private function _validateAndPreparexamApplyNavBarLinksData(&$formData)
    {
        $formData["examContentIds"] = $this->input->post("examContentIds",true);
        $formData["content_type_id"] = $this->input->post("contentTypeId",true);
        $formData["content_type_title"] = strval($this->input->post("contentTypeTitle",true));
        $formData["content_type"] = $this->input->post("contentType",true);
        if(empty($this->abroadListingCommonLib))
        {
            $this->load->library('listing/AbroadListingCommonLib');
            $this->abroadListingCommonLib = new AbroadListingCommonLib();
        }
        $examList = $this->abroadListingCommonLib->getAbroadExamsMasterListFromCache();
        //$examList = array_map(function ($examData){ return $examData['examId'];},$examList);
        if($formData['content_type'] == 'exam_content') {
            $examFound = false;
            foreach ($examList as $examTypeData) {
                if ($examTypeData['examId'] == $formData['content_type_id']) {
                    $examFound = true;
                    break;
                }
            }
            if (!$examFound) {
                echo json_encode(array('status' => 0, 'message' => 'Invalid exam id.'));
                die;
            }
        }
        $linkArray = array();
        if(!empty($formData["examContentIds"]))
        {
            foreach ($formData["examContentIds"] as $key => $val)
            {

                $key = explode('e_',$key);
                $key = $key[1];
                $linkArray[$key] = $val;
            }
        }
        else
        {
            echo json_encode(array('status'=>0,'message'=>'Content ids can not be empty.'));die;
        }
        $examContentData  = $this->abroadCommonLib->getContentIdByContentTypeId($formData["content_type_id"],array_keys($linkArray),$formData['content_type']);
        if(count($examContentData) != count($formData["examContentIds"]))
        {
            echo json_encode(array('status'=>0,'message'=>'Content ids are invalid or deleted.'));die;
        }
        $formData["links_data"] = json_encode($linkArray);
        unset($formData["examContentIds"]);
    }

    /**
     * This function get deleted course detail.
     */
    public function viewRestoreCourseListing() {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        // get the user data
        $displayData = $this->cmsAbroadUserValidation();

        // get Course id.
        $courseId = $this->input->get("courseId", true);
        if(!empty($courseId)) {
            $displayData['showResult']  = true;
        }
        $result = $this->abroadPostingLib->getDeletedCourse($courseId);
        if(empty($result)) {
            $displayData['errorMessage'] = "No deleted course found for given course id.";
        } else if(count($result)>1) {
            $displayData['errorMessage'] = "Multiple deleted entry found. Please contact SA tech team.";
        } else { // only one row present
            $result = reset($result);
            $courseData = array(
                'courseTitle' => $result['courseTitle'],
                'course_id' => $result['course_id'],
                'institute_id' => $result['institute_id'],
                'status' => $result['status'],
                'course_level_1' => $result['course_level_1'],
                'deleted_date' => date("j M Y",strtotime($result['last_modify_date']))
            );

            $instituteData = $this->abroadPostingLib->getInstituteLocation(array($result['institute_id']));

            //get category data
            $this->load->builder('CategoryBuilder','categoryList');
            $categoryBuilder = new CategoryBuilder;
            $categoryRepository = $categoryBuilder->getCategoryRepository();
            $categoryObj = $categoryRepository->find($result['category_id']);
            $parentCategoryObj = $categoryRepository->find($categoryObj->getParentId());

            $courseData['subCategory_name'] = $categoryObj->getName();
            $courseData['parentCategory_name'] = $parentCategoryObj->getName();
            $courseData['university_name'] = $instituteData[$result['institute_id']]['university_name'];
            $courseData['city_name'] = $instituteData[$result['institute_id']]['city_name'];
            $courseData['country_name'] = $instituteData[$result['institute_id']]['country_name'];
        }

        // prepare the display date here
        $displayData['formName'] 	= ENT_SA_VIEW_RESTORE_COURSE;
        $displayData['selectLeftNav']   = "RESTORE_COURSE";
        $displayData['courseData']  = $courseData;

        $this->load->view('listingPosting/abroad/abroadCMSOverview',$displayData);
    }

    /**
     * This function restore deleted course.
     */
    public function restoreCourseListing() {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        $this->cmsAbroadUserValidation();

        $courseId = $this->input->post("courseId", true);
        $status = $this->abroadPostingLib->restoreCourse($courseId);

        if($status == true){
            echo json_encode(array('status' => 1, 'message' => 'Course restored successfully.'));
        } else {
            echo json_encode(array('status' => 0, 'message' => 'Some run time error occurred.'));
        }
    }
}
