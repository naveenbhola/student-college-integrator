<?php

class ConsultantPosting extends MX_Controller
{
    private $usergroupAllowed;
    private $consultantPostingLib;
    private $saCMSToolsLib;
    public function __construct() {
        parent::__construct();
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        $this->config->load('studyAbroadCMSConfig');
        $this->consultantPostingLib = $this->load->library('consultantPosting/ConsultantPostingLib');
        // for common tools like abroad cms user validation
        $this->saCMSToolsLib= $this->load->library('saCMSTools/SACMSToolsLib');
    }
    
    public function index(){
        echo 'controller_working';
        $this->usergroupAllowed = array('saAdmin','saCMS','saSales','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        if($displayData['usergroup'] == 'saSales')
        {
            $displayData['selectLeftNav']   = "ASSIGN_CITIES";
        }
        else
        {
            $displayData['selectLeftNav']   = "CONSULTANTS";
        }
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }

    /**
    * Purpose : Method to validate the user and do the necessary action(s)
    * Params  :	none
    * Author  : none
    */
    function consultantAbroadUserValidation($noRedirectionButReturn = false)
    {
        $usergroupAllowed 	= $this->usergroupAllowed;
        $validity 		    = $this->checkUserValidation();	    
        $returnArr = $this->saCMSToolsLib->cmsAbroadUserValidation($validity, $usergroupAllowed,$noRedirectionButReturn);
        return $returnArr;
    }
    
    // function to handle Add Consultant Form
    public function addConsultantForm($param) {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        $displayData['formName'] = ENT_SA_FORM_ADD_CONSULTANT;
        $displayData['selectLeftNav']   = "CONSULTANTS";
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    // function to handle Edit Consultant Form
    public function editConsultantForm() {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        $consultantId = $this->input->get('consultantId');
        if(!($consultantId>0)){
            $this->consultantPostingLib->showErrorMessage();
        }
        
        $displayData['consultantData'] = $this->consultantPostingLib->getConsultantFormData($consultantId);
        if($displayData['consultantData'] == -1){
                $this->consultantPostingLib->showErrorMessage();
        }
        
        $displayData['formName'] = ENT_SA_FORM_EDIT_CONSULTANT;
        $displayData['selectLeftNav']   = "CONSULTANTS";
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    public function saveConsultantFormData() {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        
        $consutantFormData = $this->_postConsultantFormData();
        
        $logoPhotoResponseArr = array();
        $this->abroadListingPosting = modules::load('listingPosting/AbroadListingPosting/');
        $logoPhotoResponse = $this->abroadListingPosting->univLogoPhotoUpload('consultant','consultantLogo','consultantPhotos');
        if($consutantFormData['consultantLogoMediaUrl'] != ''){
            $consutantFormData['logoArr']['url'] = $consutantFormData['consultantLogoMediaUrl'];
        }else{
            $consutantFormData['logoArr'] = $logoPhotoResponse['logoArr'];
            if(isset($logoPhotoResponse['logoArr']['error'])){
                $logoPhotoResponseArr["Fail"]['logo'] = 'Only '. $logoPhotoResponse['logoArr']['error'];
                $exitFlag = true;
            }
        }
        
        
        $consutantFormData['pictureArr'] = $logoPhotoResponse['pictureArr'];
        if(isset($logoPhotoResponse['pictureArr']['sizecheckerror'])){
            $logoPhotoResponseArr["Fail"]['picture'] = $logoPhotoResponse['pictureArr']['sizecheckerror'];
            $exitFlag = true;
	}else if(isset($logoPhotoResponse['pictureArr']['error'])) {
            $logoPhotoResponseArr["Fail"]['picture'] = 'Only '. $logoPhotoResponse['pictureArr']['error'];
            $exitFlag = true;
	}
        if($exitFlag) {
		echo json_encode($logoPhotoResponseArr);
		exit;
	}
        
        $consutantFormData['consultantModifiedBy'] = $displayData['userid'];
        $result = $this->consultantPostingLib->saveConsultantFormData($consutantFormData);
        echo $result;
    }
    
    private function _postConsultantFormData() {
        $consultantData = array();
        $consultantData['consultantName']               = trim($this->input->post('consultantName'));
        $consultantData['consultantDescription']        = str_replace("&lt;iframe","<iframe",str_replace("&gt;&lt;/iframe","></iframe",$this->input->post('consultantDescription')));
        $consultantData['consultantLogo']               = $this->input->post('cosultantLogo');
        $consultantData['consultantLogoMediaUrl']       = $this->input->post('consultantLogoMediaUrl');
        $consultantData['consultantEstdYear']           = $this->input->post('consultantEstablishmentYear');
        $consultantData['facebookLink']                 = $this->input->post('facebookLink');
        $consultantData['linkedinLink']                 = $this->input->post('linkedinLink');
        $consultantData['consultantPhotos']             = $this->input->post('consultantPhotos');
        $consultantData['consultantPicturesMediaId']    = $this->input->post('consultantPicturesMediaId');
        $consultantData['consultantPicturesMediaUrl']   = $this->input->post('consultantPicturesMediaUrl');
        $consultantData['consultantPicturesMediaThumbUrl']  = $this->input->post('consultantPicturesMediaThumbUrl');
        $consultantData['consultantWebsite']            = $this->input->post('consultantWebsite');
        $consultantData['consultantOfferPaidService']   = $this->input->post('consultantOfferPaidService');
        $consultantData['consultantServiceDescription'] = $this->input->post('consultantServiceDescription');
        $consultantData['consultantOfferTestPrep']      = $this->input->post('consultantOfferTestPrep');
        $consultantData['consutantTestPrepService']     = $this->input->post('consutantTestPrepService');
        $consultantData['ceoName']                      = $this->input->post('ceoName');
        $consultantData['ceoDescription']               = $this->input->post('ceoDescription');
        $consultantData['consultantNumberOfEmployees']  = $this->input->post('consultantNumberOfEmployees');
        $consultantData['consultantUserComments']       = $this->input->post('consultantUserComments');
        $consultantData['consultantSaveMode']           = $this->input->post('consultantSaveMode');
        $consultantData['consultantActionType']         = $this->input->post('consultantActionType');
        $consultantData['consultantCreatedBy']          = $this->input->post('consultantCreatedBy');
        $consultantData['consultantCreatedAt']          = $this->input->post('consultantCreatedAt');
        $consultantData['consultantSaveModeOld']        = $this->input->post('consultantSaveModeOld');
        $consultantData['consultantId']                 = $this->input->post('consultantId');;
        return $consultantData;
    }
    
    // function to Handle Delete Consultant functionality
    public function deleteConsultant() {
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation(true);
        
        if(isset($displayData['error']) == "true"){
            echo $displayData['error_type'];
            exit(0);
        }
        $consultantId = $this->input->post('consultantId');
        if($consultantId <= 0 || is_nan($consultantId)){
            echo -1;
            exit(0);
        }
        $result = $this->consultantPostingLib->deleteConsultant($consultantId,$displayData['userid']);
        echo $result;
        exit(0);
    }
    
    // function to Handle View Consultant table functionality
   /**
    * Purpose : Method to render Consultant Tables
    * Params  :	Status of the data to be shown, by default it is 'all' i.e live and draft both
    * Author  :Shweta Singh 
    */
    public function viewConsultants($displayDataStatus = 'all')
    {
         $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
         // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        
        //get the values posted in the form
        $searchConsName    = $this->input->get("q");
    	$resultPerPage     = $this->input->get("resultPerPage");
    	        
        //check and set the values of the variables for db queries
    	$searchConsName = ($searchConsName == "Search Consultants") ? "" :$searchConsName;
    	$resultPerPage  = ($resultPerPage) ? $resultPerPage : "";
    
    	// prepare the url to retain the last call parameters as query string
    	$urlParams	= "?1";
    	$urlParams     .= ($searchConsName ? "&q=".$searchConsName : "");
    	$urlParams     .= ($resultPerPage  ? "&resultPerPage=".$resultPerPage : "");        

    	// prepare the URL for view as well as for paginator
    	$URL 		= ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_TABLE."/".$displayDataStatus;
    	$URLPagination 	= ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_TABLE."/".$displayDataStatus.$urlParams;
    	 	
        // initialize the paginator instance
    	$this->load->library('listingPosting/Paginator');
    	$displayData['paginator'] = new Paginator($URLPagination);
      // _p($displayData['paginator']);
		        
        //fetch consultant data here
        $result = $this->consultantPostingLib->getConsultantTableData($displayDataStatus , $displayData['paginator'],$searchConsName);
        $displayData['paginator']->setTotalRowCount($result['totalCount']);
	
        	
        // prepare the display date here
        $displayData['formName']          = ENT_SA_VIEW_CONSULTANT_TABLE;
    	$displayData['selectLeftNav']     = "CONSULTANTS";
    	$displayData['displayDataStatus'] = $displayDataStatus;
    	$displayData['searchTerm'] 	  = $searchConsName;
    	$displayData['urlParams'] 	  = $urlParams;
    	$displayData['resultCount']       = $result['dataCount'];
        $displayData['URL'] 	  	  = $URL;
	    $displayData["consultantData"] 	  = $result['data'];
 
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
        
    public function addConsultantUniversityMappingForm($param) {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        $displayData = $this->consultantAbroadUserValidation();
        
        $displayData['formName'] = ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING;
        $displayData['selectLeftNav']   = "MAP_UNIVERSITY";
        
        //data to populate consultant names
        $consultantId = $this->input->get('consutId');
        $rawConsultantList = $this->consultantPostingLib->getConsultantList();
        $consultantList = array();
        foreach($rawConsultantList as $consultantItem){
            $consultantList[$consultantItem['consultantId']] = $consultantItem['name'];
        }
        if(!empty($consultantId) &&!in_array($consultantId,array_keys($consultantList))){
            $this->consultantPostingLib->showErrorMessage();  //Someone's been tampering with the URL?
        }
        if(!empty($consultantId)){
            $displayData['consultantId']                  = $consultantId;
            $displayData['consultantName']                = $consultantList[$consultantId];
            $displayData['consultantUniversities']        = json_encode($this->getConsultantMappedUniversities($consultantId,true));
            $displayData['consultantUniversityTableData'] = $this->consultantPostingLib->getUniversityMappingDataForConsultant($consultantId);
            $displayData['consultantUniversityTableDataCount'] = count($displayData['consultantUniversityTableData']);
        }else{
            $displayData['consultantList'] = $consultantList;
        }
        
        $this->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        $countries = $locationRepository->getAbroadCountries();
        unset($countries['All']);
        $displayData['countryList'] = $countries;
        //data to populate sales persons list
        $consultantSalesList = $this->consultantPostingLib->getConsultantSalesPersons();
        $displayData['consultantSalesPersons'] = $consultantSalesList;
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    public function editConsultantUniversityMappingForm($param) {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        $displayData['formName'] = ENT_SA_FORM_EDIT_CONSULTANT_UNIVERSITY_MAPPING;
        $displayData['selectLeftNav']   = "MAP_UNIVERSITY";
        
        $consultantId = $this->input->get('consutId');
        $universityId = $this->input->get('univId');
        if(empty($consultantId)){
            $this->consultantPostingLib->showErrorMessage();  // Edit case ALWAYS needs a consultantId
        }
        $rawConsultantList = $this->consultantPostingLib->getConsultantList();
        $consultantList = array();
        foreach($rawConsultantList as $consultantItem){
            $consultantList[$consultantItem['consultantId']] = $consultantItem['name'];
        }
        if(!in_array($consultantId,array_keys($consultantList))){
            $this->consultantPostingLib->showErrorMessage();  //Someone's been tampering with the URL?
        }
        
        $displayData['consultantId'] = $consultantId;
        $displayData['consultantName'] = $consultantList[$consultantId];
        if($universityId){
            $displayData['universityId'] = $universityId;
            $disabledCourses = $this->getExcludedCoursesForUniversity($consultantId,$universityId);
            $displayData['disabledCourses'] = $disabledCourses;
            $displayData['mappingData'] = $this->consultantPostingLib->getDataForConsultantUniversityMapping($consultantId,$universityId);
            if(empty($displayData['mappingData'][0]['countryId'])){
                $this->consultantPostingLib->showErrorMessage();
            }
        }else{
            $this->consultantPostingLib->showErrorMessage();
        }
        //fetch the lastmodified time and username
        $displayData['lastModifierData'] = $this->consultantPostingLib->getLastEditorForUniversityConsultantMapping($consultantId,$universityId);
        $displayData['consultantUniversityTableData'] = $this->consultantPostingLib->getUniversityMappingDataForConsultant($consultantId);
        $displayData['consultantUniversityTableDataCount'] = count($displayData['consultantUniversityTableData']);
        
        $this->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        $countries = $locationRepository->getAbroadCountries();
        unset($countries['All']);
        $displayData['countryList'] = $countries;
        
        $consultantSalesList = $this->consultantPostingLib->getConsultantSalesPersons();
        $displayData['consultantSalesPersons'] = $consultantSalesList;
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    public function deleteConsultantUniversityMapping($consultantId,$universityId) {
        $this->usergroupAllowed = array('saAdmin','saCMSLead');        
        // get the user data
        $userCheck = $this->consultantAbroadUserValidation(true);
        if($userCheck['error'] == 'true'){
            echo $userCheck['error_type'];
            return false;
        }else{
            $displayData = $userCheck;
        }
        $result = $this->consultantPostingLib->deleteConsultantUniversityMapping($displayData['userid'],$consultantId,$universityId);
        if($result === true){
            echo "success";
        }else{
            echo $result['FAIL'];
        }
        return true;
    }
    public function updateConsultantUniversityMappingFormData($formName){
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        $universityId = $this->input->post("universityId");
        if(empty($universityId)){
            echo 'Please choose a university from the table below!';
            return false;
        }
        
        $dataArray = $this->postConsultantUniversityMappingData($formName);
        if(!empty($dataArray['fail'])){
            echo $dataArray['fail'];
            return false;
        }
        
        $dataArray[0]['createdAt'] = $this->input->post("createdAt");
        $dataArray[0]['createdBy'] = $this->input->post("createdBy");
        $mappingId = $this->input->post("mappingId");
        $this->consultantPostingLib->updateConsultantUniversityMappingFormData($dataArray,$mappingId);
        echo 'success';
        return true;
        
    }
    
    public function saveConsultantUniversityMappingFormData($formName){
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        $dataArray = $this->postConsultantUniversityMappingData($formName);
        if(!empty($dataArray['fail'])){
            echo $dataArray['fail'];
            return false;
        }
        $this->consultantPostingLib->saveConsultantUniversityMappingFormData($dataArray);
        echo 'success';
        return true;
    }
    
    private function postConsultantUniversityMappingData($formName){
        //let's grab that Data!
        $consultantId = $this->input->post("consultantId");
        $countries = $this->input->post("countryId");
        $universities = $this->input->post("universityId");
        $disabledCourses = $this->input->post('disabledCourses');
        $disabledCoursesComments = $this->input->post("disabledCourseComments");
        if($disabledCourses == false){
            $disabledCourses = array();
        }
        $indexes = $this->input->post("universityIndexes");
        $representatives = array();
        foreach($indexes as $index){
            $representatives[] = $this->input->post($index."_representative_".$formName);
        }
        $validFrom = $this->input->post("startDate");
        $validTill = $this->input->post("endDate");
        $proofType = $this->input->post("proofType");
        $proofWebsiteLink = $this->input->post("proofWebsiteLink");
        $proofPersonName = $this->input->post("proofPersonName");
        $proofPersonDetails = $this->input->post("proofPersonDetails");
        $salesPerson = $this->input->post("salesPerson");
        $proofEmailDocument = $this->input->post("proofEmailDocument");
        if($proofEmailDocument){     // This will only happen in the EDIT form where remove link is not clicked!
            //do nothing
        }else{
            //lets upload the files
            $appId = 1;
            $uploadType="email";
            $listing_type="consultantEmailProof";
            $fieldName="proofEmailDocument";
            $this->load->library('upload_client');
            $uploadClient = new Upload_client();
            if(!empty($_FILES['proofEmailDocument']['name'][0])){
                $upload_array = $uploadClient->uploadFile($appId,$uploadType,$_FILES,array(),"-1",$listing_type,$fieldName);
                if(gettype($upload_array) == "string"){
                    return array('fail' => $upload_array);
                }
            }else{
                $upload_array = array();
            }
        }
        // Now to make the data nicer
        $mediaCounter = 0;
        $dataArray = array();
        $entries = count($universities);
        $validity = $this->checkUserValidation();
        for($i = 0; $i<$entries; $i++){
            $data = array();
            $data['consultantId'] = $consultantId;
            $data['universityId'] = $universities[$i];            
            $data['excludedCourseComments'] = $disabledCoursesComments[$i];
            $data['disabledCourses'] = $disabledCourses[$i+1];
            $data['isOfficialRepresentative'] = $representatives[$i];
            if($data['isOfficialRepresentative'] == 'yes'){
                $sParts = explode('/',$validFrom[$i]);
                $sDate = mktime(0,0,0,$sParts[1],$sParts[0],$sParts[2]);
                $eParts = explode('/',$validTill[$i]);
                $eDate = mktime(0,0,0,$eParts[1],$eParts[0],$eParts[2]);
                $data['representativeValidFrom'] = date('Y-m-d H:i:s',$sDate);
                $data['representativeValidTo'] = date('Y-m-d H:i:s',$eDate);
            }else{
                $data['representativeValidFrom'] = '';
                $data['representativeValidTo'] = '';
            }
            
            $data['proofType'] = $proofType[$i];
            if($data['proofType'] == 'email'){
                $data['proofPersonName'] = $proofPersonName[$i];
                $data['proofPersonDetails'] = $proofPersonDetails[$i];
                if($proofEmailDocument){    // only triggers during edit mode when the existing file is not removed
                    $data['proofEmailDocumentUrl'] = $proofEmailDocument[$i];
                }else{
                    $data['proofEmailDocumentUrl'] = $upload_array[$mediaCounter]['imageurl'];
                    $mediaCounter++;
                }
                $data['proofWebsiteLink'] = '';
            }elseif($data['proofType'] == 'name'){
                $data['proofWebsiteLink'] = $proofWebsiteLink[$i];
                $data['proofPersonName'] = '';
                $data['proofPersonDetails'] = '';
                $data['proofEmailDocumentUrl'] = '';
            }
            $data['salesPerson'] = $salesPerson[$i];
            $data['createdAt'] = date("Y-m-d H:i:s");
            $data['modifiedAt'] = date("Y-m-d H:i:s");
            $data['createdBy'] = $validity[0]['userid'];
            $data['modifiedBy'] = $validity[0]['userid'];
            $data['status'] = "live";
            $dataArray[]  = $data;
        }
        return $dataArray;
    }
    
    public function viewConsultantUniversityMapping($param) {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
    	
    	$searchContentName = $this->input->get("q");
    	$resultPerPage     = $this->input->get("resultPerPage");
    	$searchType        = $this->input->get("searchTyp");
      	$searchType        = empty($searchType) || $searchType == "" || !in_array($searchType,array("Universities","Consultants")) ? 'Consultants' : $searchType;
    	
    	$searchContentName = ($searchContentName == "Search") ? "" : $searchContentName;
    	$resultPerPage  = ($resultPerPage) ? $resultPerPage : "";
    	
    	// prepare the query parameters coming
    	$queryParams    ='1';
    	$queryParams   .= ($searchType ? "&searchTyp=".$searchType : "");
    	$queryParams   .= ($searchContentName ? "&q=".$searchContentName : "");
    	$queryParams   .= ($resultPerPage  ? "&resultPerPage=".$resultPerPage : "");
    	$queryParams    = $queryParams 	   ? "?".$queryParams : "";
    	
    	// prepare the URL for view as well as for paginator
        $URL 		= ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_UNIVERSITY_MAPPING_TABLE."/".$displayDataStatus;
    	$URLPagination 	= ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_UNIVERSITY_MAPPING_TABLE."/".($queryParams ? $queryParams : "");
    	
        // initialize the paginator instance
    	$this->load->library('listingPosting/Paginator');
    	$displayData['paginator']  	  = new Paginator($URLPagination);
    	
    	// fetch the content data
    	$result = $this->consultantPostingLib->getConsultantUniversityMappingData($searchType, $displayData['paginator'], $searchContentName);
        $displayData['paginator']->setTotalRowCount($result['totalCount']);
    	
        $displayData['searchTerm'] 	  = $searchContentName;
        $displayData['queryParams'] 	  = $queryParams;
        $displayData['totalResultCount']  = $result['dataCount'];
        $displayData['URL'] 	  	  = $URL;
        $displayData["reportData"] 	  = $result['data'];
        $displayData["searchTypeOptions"] = array('Consultants','Universities');
        $displayData["searchType"]        = $searchType;
        $displayData['formName']          = ENT_SA_VIEW_CONSULTANT_UNIVERSITY_MAPPING_TABLE;
        $displayData['selectLeftNav']     = "MAP_UNIVERSITY";        
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    public function addStudentProfileForm() {
       
        $displayData                    = $this->consultantAbroadUserValidation();
        $this->prepareStudentProfileFormData($displayData);
        $displayData['bottomTableData'] = $this->consultantPostingLib->getStudentProfileDataForConsultant($displayData['consultantId']);
        $displayData['formName']        = ENT_SA_FORM_ADD_STUDENT_PROFILE;
        $displayData['selectLeftNav']   = "MAP_PROFILES";
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    function prepareStudentProfileFormData(& $displayData){
        
        $displayData['consultantList']          = $this->consultantPostingLib->getConsultantList();
        $this->abroadCommonLib             = $this->load->library('listingPosting/AbroadCommonLib');
        $displayData['abroadMainLDBCourses']    = $this->abroadCommonLib->getAbroadMainLDBCourses();
        $displayData['couresTypes']             = $this->abroadCommonLib->getAbroadCourseLevels();
        //Only Add the All option At top
        $displayData['couresTypes']             = array_merge(array(array('CourseName'=>'All')),$displayData['couresTypes']);
		$displayData['abroadCategories']        = $this->abroadCommonLib->getAbroadCategories();
        $displayData['abroadExamsMasterList']   = $this->abroadCommonLib->getAbroadExamsMasterList();
        $consultantId= $this->input->get('consultantId');
                    
        $consultantName = '';
        if($consultantId !=''){
            $consultantIdNotExists = true;
            foreach($displayData['consultantList'] as $consultantRow)
            {
                
                if($consultantRow['consultantId']==$consultantId){
                    
                    $consultantName = $consultantRow['name'];
                    $consultantIdNotExists = false;
                    break;
                }
            }
            
            if($consultantIdNotExists)
            {
               $this->consultantPostingLib->showErrorMessage(); 
            }
        }
        $displayData['consultantName'] =  $consultantName;
        $displayData['consultantId']   =  $consultantId;
        $displayData['universityId']    = $this->input->get('universityId');
        $this->load->builder('LocationBuilder','location');
        $locationBuilder                = new LocationBuilder;
        $locationRepository             = $locationBuilder->getLocationRepository();
        $displayData['cityList']        = $locationRepository->getCities(2,False);

    }
    
    
    public function saveStudentProfile(){
        
        $displayData             = $this->consultantAbroadUserValidation();
        $studentProfileFormData  = $this->_postStudentProfileFormData();
        $uploadResponse          = $this->consultantPostingLib->uploadDocument('proofImg');
        
        if(isset($uploadResponse['uploaderror']))
        {
                echo  json_encode($uploadResponse);
                return;
        }
        else
        {
                $studentProfileFormData['documentProofs'] = $uploadResponse;
        }
        
        $editFileslink = $this->input->post('proofImgHidden');
        
        if(isset($editFileslink) && !empty($editFileslink))
        {
            foreach($editFileslink as $filesName){
            $studentProfileFormData['documentProofs'][]['url'] = $filesName;
            }
        }
        
        $studentProfileFormData['studentProfileModifiedBy'] = $displayData['userid'];
        $result =  $this->consultantPostingLib->saveStudentProfileForm($studentProfileFormData);
        $resultArr = array("errorFlag" => 0,"result"=>$result);
        echo json_encode($resultArr);
        
    }
    
    public function getUniversityForConsultant(){
        $consultantId= $this->input->post('consultantId');
        $consultantUniversityList = $this->consultantPostingLib->getUniversityAndCountryByCounsltantId($consultantId);    
        echo json_encode($consultantUniversityList);
    }
    
    public function editStudentProfileForm()
    {
        $displayData = $this->consultantAbroadUserValidation();
        $this->prepareStudentProfileFormData($displayData);
        $errorFlag = false;
        $profileId= $this->input->get('profileId');
        if(!empty($profileId))
        {
            $displayData['formData'] = $this->consultantPostingLib->getStudentProfileDetails($profileId);
            if(!empty($displayData['formData']))
            {
                if($displayData['formData']['consultantId'] != $displayData['consultantId']){
                    foreach($displayData['consultantList'] as $consultantRow)
                    {
                        if($consultantRow['consultantId']==$displayData['formData']['consultantId'])
                        {
                            $displayData['consultantName'] =  $consultantRow['name'];
                            $displayData['consultantId']   =  $consultantRow['consultantId']; 
                            break;
                        }
                    }
                }
            }
            else
            {
                $errorFlag = true;
            }
        }
        else
        {
         $errorFlag = true;
        }
                
        if($errorFlag)
        {
            $this->consultantPostingLib->showErrorMessage();
        }
        
        $displayData['bottomTableData'] = $this->consultantPostingLib->getStudentProfileDataForConsultant($displayData['consultantId']);
        $displayData['formName'] = ENT_SA_FORM_EDIT_STUDENT_PROFILE;
        $displayData['selectLeftNav']   = "MAP_PROFILES";
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    
    private function _postStudentProfileFormData(){
        $studentProfileData = array();
        $studentProfileData['consultantId']             = $this->input->post('consultantId');
        $studentProfileData['universityCountry']        = $this->input->post('universityCountry');
        $studentProfileData['universityId']             = $this->input->post('universityId');
        $studentProfileData['courseName']               = $this->input->post('courseName');
        $studentProfileData['desiredCourse']            = $this->input->post('desiredCourse');
        $studentProfileData['couresType']               = $this->input->post('couresType');
        $studentProfileData['parentCategory']           = $this->input->post('parentCategory');
        $studentProfileData['childCategory']            = $this->input->post('childCategory');
        $studentProfileData['scholarship']              = $this->input->post('scholarship');
        $studentProfileData['scholarshipDetail']        = $this->input->post('scholarshipDetail');
        $studentProfileData['admissionMonth']           = $this->input->post('admissionMonth');
        $studentProfileData['admissionYear']            = $this->input->post('admissionYear');
        $studentProfileData['saExam']                   = $this->input->post('saExam');
        $studentProfileData['saExamScore']              = $this->input->post('saExamScore');
        $studentProfileData['studentName']              = $this->input->post('studentName');
        $studentProfileData['studentCity']              = $this->input->post('studentCity');
        $studentProfileData['marks10']                  = $this->input->post('marks10');
        $studentProfileData['year10Passing']            = $this->input->post('year10Passing');
        $studentProfileData['marks12']                  = $this->input->post('marks12');
        $studentProfileData['year12Passing']            = $this->input->post('year12Passing');
        $studentProfileData['graduationUniversity']     = $this->input->post('graduationUniversity');
        $studentProfileData['graduationCollege']        = $this->input->post('graduationCollege');
        $studentProfileData['graduationlocation']       = $this->input->post('graduationlocation');
        $studentProfileData['graduationGPA']            = $this->input->post('graduationGPA');
        $studentProfileData['graduationPercentage']     = $this->input->post('graduationPercentage');
        $studentProfileData['graduationPassing']        = $this->input->post('graduationPassing');
        $studentProfileData['graduationDesc']           = $this->input->post('graduationDesc');
        $studentProfileData['workex']                   = $this->input->post('workex');
        $studentProfileData['companyName']              = $this->input->post('companyName');
        $studentProfileData['companyDomain']            = $this->input->post('companyDomain');
        $studentProfileData['jobStart']                 = $this->input->post('jobStart');
        $studentProfileData['jobEnd']                   = $this->input->post('jobEnd');
        $studentProfileData['curricularAct']            = $this->input->post('curricularAct');
        $studentProfileData['linkedLink']               = $this->input->post('linkedLink');
        $studentProfileData['facebookLink']             = $this->input->post('facebookLink');
        $studentProfileData['phoneNo']                  = $this->input->post('phoneNo');
        $studentProfileData['studentEmail']             = $this->input->post('studentEmail');
        $studentProfileData['studentId']                = $this->input->post('studentId');
        $studentProfileData['studentSaveMode']          = $this->input->post('studentSaveMode');
        $studentProfileData['studentActionType']        = $this->input->post('studentActionType');
        $studentProfileData['studentProfileCreatedBy']  = $this->input->post('studentCreatedBy');
        $studentProfileData['studentProfileCreatedAt']  = $this->input->post('studentCreatedAt');
        $studentProfileData['studentSaveModeOld']       = $this->input->post('studentSaveModeOld');
        
        $courseLevelNames                               = $this->input->post('courseTypeName');
        $studentProfileData['courseLevel']              = array();
        foreach($courseLevelNames as $key=>$value){
                $studentProfileData['courseLevel'][] = $this->input->post($value);
        }
        
        $scholarshipNames                               = $this->input->post('scholarship_Name');
        $studentProfileData['scholarship']              = array();
        foreach($scholarshipNames as $key=>$value){
                $studentProfileData['scholarship'][] = $this->input->post($value);
        }
        
        return $studentProfileData;
    }


    public function deleteStudentProfile() {
        $result = false;
        $clearForDeletion = false;
        $this->usergroupAllowed = array('saAdmin','saCMSLead');    
        $displayData = $this->consultantAbroadUserValidation(true);
        if($displayData['error']!= true){
            $studentProfileId = $this->input->post('profileId');
            $universityId = $this->input->post('universityId');
            $consultantId = $this->input->post('consultantId');
            
            if(!empty($consultantId)){
                $consultantData = $this->consultantPostingLib->getCompleteConsultantDataForDeletion($consultantId);
                $profileCount = intval($consultantData[$consultantId]['profiles'][$universityId]);
                
                //Check if this profile is not mapped to any university which has active subscription
                $universities = $this->consultantPostingLib->getAllUniversitiesForStudentProfile($studentProfileId);
                $commonUniversity = array_intersect($consultantData[$consultantId]['universities'],$universities);
                if(!in_array($universityId,$consultantData[$consultantId]['universities']))
                {
                    if(count($commonUniversity) >0){
                            $clearForDeletion = false;
                            $result = "This student profile cannot be deleted because it is mapped with active university";
                    }else{
                        $clearForDeletion = true;
                    }
                }
                elseif($profileCount >1)
                {
                       $clearForDeletion = true; 
                }elseif($profileCount ==1 || $profileCount ==0)
                {
                       //this case will handle the draft case when profile is vaiavble in both live and draft status
                       $profileResult = $this->consultantPostingLib->getStudentProfileStatus($studentProfileId);
                       if($profileResult[0]['pCount']==1 && in_array($universityId,$consultantData[$consultantId]['universities'])){
                        $clearForDeletion = false;
                        $result = "This is profile can't be deleted because its last active profile for this consultant and university";
                       }else{
                         $clearForDeletion = true;
                       }
                }
                else{
                    $clearForDeletion = false;
                    $result = "Something went wrong, please try again";
                }
            }    
            if($clearForDeletion==true && $studentProfileId !=''){
            $result = $this->consultantPostingLib->deleteStudentProfile($displayData['userid'],$studentProfileId,$universityId,$consultantId);
            }
        }else{
            $result = $displayData['error_type'];
        }
        echo ($result == false)?"0":$result;
    }
    
    
   /**
    * Purpose : function to Handle View Student Profile Mapping table functionality
    * Params  :	Status of the data to be shown, by default is live
    * Author  :Shweta Singh 
    */
   
    public function viewStudentProfile() {
        
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');    
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        
    	$searchContentName = $this->input->get("q");
    	$resultPerPage     = $this->input->get("resultPerPage");
    	$searchType        = $this->input->get("searchType");
      	$searchType        = empty($searchType) || $searchType == "" || !in_array($searchType,array("universities","consultants")) ? 'consultants' : $searchType;
    	
    	$searchContentName = ($searchContentName == "Search Universities" || $searchContentName == "Search Consultants") ? "" : $searchContentName;
    	$resultPerPage  = ($resultPerPage) ? $resultPerPage : "";
    	
    	// prepare the query parameters coming
    	$queryParams    ='?1';
    	$queryParams   .= ($searchType ? "&searchTyp=".$searchType : "");
    	$queryParams   .= ($searchContentName ? "&q=".$searchContentName : "");
    	$queryParams   .= ($resultPerPage  ? "&resultPerPage=".$resultPerPage : "");
    	
    	// prepare the URL for view as well as for paginator
        $URL 		= ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_STUDENT_PROFILE."/";
    	$URLPagination 	= ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_STUDENT_PROFILE."/".$queryParams;
    	
        // initialize the paginator instance
    	$this->load->library('listingPosting/Paginator');
    	$displayData['paginator']  	  = new Paginator($URLPagination);
    	
    	// fetch the content data
    	$result = $this->consultantPostingLib->getStudentProfileMappingData($searchType, $displayData['paginator'], $searchContentName);
        $displayData['paginator']->setTotalRowCount($result['totalCount']);
    	
        $displayData['searchTerm'] 	    = $searchContentName;
        $displayData['queryParams'] 	    = $queryParams;
        $displayData['totalCount']          = $result['totalCount'];
        $displayData['universities']        = $result['universities'];
        $displayData['consultants']         = $result['consultants'];    
        $displayData['URL'] 	  	    = $URL;
        $displayData["querydata"] 	    = $result['querydata'];
        $displayData["searchTypeOptions"] = array('Consultants','Universities');
        $displayData["searchType"]        = $searchType;
        $displayData['formName']          = ENT_SA_VIEW_STUDENT_PROFILE;
        $displayData['selectLeftNav']     = "MAP_PROFILES";
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    public function assignRegionForm($consultantId = 0, $universityId = 0) {
        $this->usergroupAllowed = array('saSales','saAdmin','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        
        $displayData['formName'] = ENT_SA_FORM_ASSIGN_REGION;
        $displayData['selectLeftNav']   = "ASSIGN_CITIES";
        $displayData['requestedConsultantId']       = $consultantId;
        $displayData['requestedUniversityId']       = $universityId;
        $displayData['consultantList']              = $this->consultantPostingLib->getConsultantList();
        $displayData['consultantExists']            = in_array($consultantId,array_map(function($a){return $a['consultantId']; },$displayData['consultantList']));
        //$displayData['consultantLocationCities']    = $this->consultantPostingLib->getConsultantLocationCities();
        $displayData['consultantLocationRegions']   = $this->consultantPostingLib->getConsultantLocationRegions();
        $displayData['consultantUniversityList'] = $this->getUniversitiesMappedToConsultant($consultantId,true);
        $displayData['consultantSalesPersons'] = $this->consultantPostingLib->getConsultantSalesPersons();
        //$displayData['consultantCities'] = $displayData['consultantLocationCities'];
        $displayData['consultantBottomTableData'] = $this->consultantPostingLib->getConsultantUniversityCitySubscriptionDataForConsultant($consultantId);
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    public function editRegionForm($subscriptionId) {
        if(empty($subscriptionId)|| $subscriptionId == 0){
            $this->consultantPostingLib->showErrorMessage();        // All 3 are required for a proper edit form
        }
        $idsRequired = reset($this->consultantPostingLib->getBasicIdsForSubscription($subscriptionId));
        //_p($idsRequired);die;
        $consultantId = $idsRequired['consultantId'];
        $universityId = $idsRequired['universityId'];
        $cityId = $idsRequired['regionId'];
        $this->usergroupAllowed = array('saSales','saAdmin','saCMSLead');
        $displayData = $this->consultantAbroadUserValidation();
        if(empty($consultantId) || empty($universityId) || empty($cityId)){
            $this->consultantPostingLib->showErrorMessage();         // All 3 are required for a proper edit form
        }
        
        $displayData['formName'] = ENT_SA_FORM_EDIT_ASSIGNED_REGION;
        $displayData['selectLeftNav']               = "ASSIGN_CITIES";
        $displayData['subscriptionId']              = $subscriptionId;
        $displayData['requestedConsultantId']       = $consultantId;
        $displayData['requestedUniversityId']       = $universityId;
        $displayData['requestedCityId']             = $cityId;
        $displayData['consultantList']              = $this->consultantPostingLib->getConsultantList();
        //$displayData['consultantLocationCities']    = $this->consultantPostingLib->getConsultantLocationCities();
        $displayData['consultantLocationRegions']   = $this->consultantPostingLib->getConsultantLocationRegions();
        $displayData['consultantUniversityList'] = $this->getUniversitiesMappedToConsultant($consultantId,true);
        $displayData['consultantSubscriptionData'] = $this->consultantPostingLib->getConsultantSubscriptionData($subscriptionId);
        $displayData['consultantSalesPersons'] = $this->consultantPostingLib->getConsultantSalesPersons();
        $displayData['consultantCities'] = $displayData['consultantLocationCities'];
        $displayData['consultantBottomTableData'] = $this->consultantPostingLib->getConsultantUniversityCitySubscriptionDataForConsultant($consultantId);
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    /*function to view the table of assigned cities
     */
    public function viewAssignedRegions()
    {
        $this->usergroupAllowed = array('saSales','saAdmin','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        
        $searchContentName = $this->input->get("q");
    	$resultPerPage     = $this->input->get("resultPerPage");
    	$searchType        = $this->input->get("searchType");
      	
        if(empty($searchType) || $searchType == "" || !in_array($searchType,array("universities","consultants","regions")))
        {
            $searchType ='consultants';
        }
        
    	$searchContentName = ($searchContentName == "Search Universities" || $searchContentName == "Search Consultants" || $searchContentName == "Search Regions" ) ? "" : $searchContentName;
    	$resultPerPage  = ($resultPerPage) ? $resultPerPage : "";
    	
    	// prepare the query parameters coming
    	$queryParams    ='?1';
    	$queryParams   .= ($searchType ? "&searchType=".$searchType : "");
    	$queryParams   .= ($searchContentName ? "&q=".$searchContentName : "");
    	$queryParams   .= ($resultPerPage  ? "&resultPerPage=".$resultPerPage : "");
    	
    	// prepare the URL for view as well as for paginator
        $URL 		= ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_ASSIGNED_REGION."/";
    	$URLPagination 	= ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_ASSIGNED_REGION."/".$queryParams;
    	
        // initialize the paginator instance
    	$this->load->library('listingPosting/Paginator');
    	$displayData['paginator']  	  = new Paginator($URLPagination);
    	
    	// fetch the content data
    	$result = $this->consultantPostingLib->getAssignedCitiesData($searchType,$searchContentName, $displayData['paginator']);
        
        //_p($result);
        $displayData['paginator']->setTotalRowCount($result['totalCount']);
        $displayData['searchTerm'] 	    = $searchContentName;
        $displayData['queryParams'] 	    = $queryParams;
        $displayData['totalCount']          = $result['totalCount'];
        $displayData['URL'] 	  	    = $URL;
        $displayData["resultdata"] 	    = $result['resultdata'];
        $displayData["searchTypeOptions"]   = array('Consultants','Universities');
        $displayData["searchType"]          = $searchType;
        $displayData['formName']            = ENT_SA_VIEW_ASSIGNED_REGION;
        $displayData['selectLeftNav']       = "ASSIGN_CITIES";
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    /*
     * function to get universities mapped to a given consultant
     */
    public function getUniversitiesMappedToConsultant($consultantId,$returnValue)
    {
        if(empty($consultantId)){
            $consultantId = $this->input->post('consultantId');
        }
        $consultantUniversityList        = $this->consultantPostingLib->getConsultantUniversities($consultantId);
        if($returnValue){
            return $consultantUniversityList;
        }else{
            echo json_encode($consultantUniversityList);
        }
    }
    // function to handle Add Consultant Location Form
    public function addConsultantLocationForm($consultantId) {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        // prepare the display data here
        $displayData['formName']                    = ENT_SA_FORM_ADD_CONSULTANT_LOCATION;
        $displayData['selectLeftNav']               = "CONSULTANTS";
        $displayData['requestedConsultantId']       = $consultantId;
        $displayData['consultantList']              = $this->consultantPostingLib->getConsultantList();
        if(
            in_array($displayData['requestedConsultantId'], 
                     array_map(function($a){return $a['consultantId'];},$displayData['consultantList'])
                    )
          )
        {
            $displayData['consultantExists']  = true;
        }
        else{
            $displayData['consultantExists']  = false;
        }
        $displayData['consultantLocationCities']    = $this->consultantPostingLib->getConsultantLocationCities();
        $displayData['consultantLocations']         = $this->consultantPostingLib->getLocationsForConsultant($consultantId);
        $displayData['consultantRegionsData']       = $this->consultantPostingLib->getRegionsMappingData();
  
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    // function to handle Edit Consultant Location Form
    public function editConsultantLocationForm($consultantLocationId) {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        // prepare the display data here
        $displayData['formName']                        = ENT_SA_FORM_EDIT_CONSULTANT_LOCATION;
        $displayData['selectLeftNav']                   = "CONSULTANTS";
        $displayData['requestedConsultantLocationId']   = $consultantLocationId;
        $displayData['consultantLocationDetails']       = $this->consultantPostingLib->getConsultantLocationDetails($consultantLocationId);
        if(count($displayData['consultantLocationDetails'])==0 || !$displayData['requestedConsultantLocationId']) 
        {
            $this->consultantPostingLib->showErrorMessage();
        }
        $displayData['consultantList']                  = $this->consultantPostingLib->getConsultantList();
        $displayData['consultantLocationDetails']       = $displayData['consultantLocationDetails'][0];
        $displayData['requestedConsultantId']           = $displayData['consultantLocationDetails']['consultantId'];
        $displayData['consultantLocationCities']        = $this->consultantPostingLib->getConsultantLocationCities();
        $displayData['consultantLocations']             = $this->consultantPostingLib->getLocationsForConsultant($displayData['consultantLocationDetails']['consultantId']);
        $displayData['consultantRegionsData']           = $this->consultantPostingLib->getRegionsMappingData();
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    public function getDefaultLocationForConsultant(){
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        $consultantId   = $this->input->post('consultantId');
        $regionId       = $this->input->post('regionId');
        $result = $this->consultantPostingLib->getDefaultLocationForConsultant($consultantId,$regionId);
        echo json_encode($result);
    }
    
    public function getPRIRegions()
    {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        $priNumber = $this->input->post('priNumber');
        if(!is_array($priNumber)){
            $priNumber = explode(',', $priNumber);
        }
        $result = $this->consultantPostingLib->getPRIRegions($priNumber);
        echo json_encode($result);
    }
    
    /* function to Handle Delete Consultant Location functionality*/
    public function deleteConsultantLocation()
    {
        $this->usergroupAllowed = array('saAdmin','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation(true);
        //_p($displayData);
        //check error_type
        
        if($displayData['error_type']== 'notloggedin')
        {
            echo 'notloggedin';
        }
        if($displayData['error_type']== 'disallowedaccess')
        {
            echo 'disallowedaccess';
        }
        else
        {
        	$consultantLocationId = $this->input->post("consultantLocationId");
        	$consultantId = $this->input->post("consultantId");
	   
            // delete the location
            $result = $this->consultantPostingLib->deleteConsultantLocation($consultantLocationId,$consultantId,$displayData['userid']);
       
            echo $result;
            
        }
        
    }

	
    
    
    public function getConsultantMappedUniversities($consultantId, $inner = false){
        $universities = $this->consultantPostingLib->getConsultantMappedUniversities($consultantId);
        if($inner){
            return $universities;
        }else{
            echo json_encode($universities);
        }
        
    }


    public function saveConsultantLocationFormData()
    {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        $data = $this->collectPostDataFromConsultantLocation();
        //_p($data);
        // send data to model
        echo json_encode($this->consultantPostingLib->saveConsultantLocationFormData($data));
        
    }
    /*
     * get a city's localities, given a city id
     */ 
    public function getConsultantLocalitiesByCity($cityIds,$ajax = 0)
    {
        $consultantLocationCitiesWithLocalities = $this->consultantPostingLib->getConsultantLocalitiesByCity($cityIds);
        if($ajax == 1){
	    echo json_encode( $consultantLocationCitiesWithLocalities , false);
	}
	else{
	    return $consultantLocationCitiesWithLocalities;
	}
    }
    /*
     * check if location for a consultant exists in database, given a combination of city & locality
     */
    public function doesGivenConsultantLocationExists()
    {
        $data = array();
        $data['consultantId'      ] = $this->input->post('consultantId'      );
        $data['consultantCity'    ] = $this->input->post('consultantCity'    );
        $data['consultantLocality'] = $this->input->post('consultantLocality');
        
        $result = $this->consultantPostingLib->doesGivenConsultantLocationExists($data);
        echo json_encode($result);
    }
    /*
     * to collect POST data from consultant location form
     */
    public function collectPostDataFromConsultantLocation()
    {
        $data = array();
        $data['consultantId'              ] = $this->input->post('consultantId'                   );
        $data['contactName'               ] = $this->input->post('contactPersonName'              );
        $data['defaultPhone'              ] = $this->input->post('consultantLocationPhone'        );
        for($i =0;$i<count($data['defaultPhone']);$i++)
        {
            $data['defaultPhone'][$i] = array_values(array_filter($data['defaultPhone'][$i]));
        }
        $data['shikshaPRINumber'          ] = $this->input->post('shikshaPRI'                     );
        $data['displayPRINumber'          ] = $this->input->post('displayPRI'                     );
        $data['email'                     ] = $this->input->post('consultantLocationEmail'        );
        $data['cityId'                    ] = $this->input->post('consultantLocationCity'         );
        $data['localityId'                ] = $this->input->post('consultantLocationLocality'     );
        $data['regionId'                  ] = $this->input->post('regionId');
        $data['locationAddress'           ] = $this->input->post('consultantLocationAddress'      );
        $data['pincode'                   ] = $this->input->post('consultantLocationPincode'      );
        $data['latitude'                  ] = $this->input->post('consultantLocationLatitude'     );
        $data['longitude'                 ] = $this->input->post('consultantLocationLongitude'    );
        $data['defaultBranch'             ] = $this->input->post('consultantLocationDefaultBranch');
        $data['contactHours'              ] = $this->input->post('consultantContactHours'         );
        $data['consultantLocationComments'] = $this->input->post('consultantLocationComments'     );
        $data['createdAt'                 ] = $this->input->post('createdAt'                      );
        $data['createdBy'                 ] = $this->input->post('createdBy'                      );
        $data['consultantLocSaveMode'     ] = $this->input->post('consultantLocSaveMode'          );
        $data['oldConsultantLocationId'   ] = $this->input->post('oldConsultantLocationId'        );
        $data['oldDefaultBranchStatus'    ] = $this->input->post('oldDefaultBranchStatus'         );
        $data['modifiedBy'                ] = $this->input->post('modifiedBy'          );
        return $data;
    }
    /*
     * to check for a given city whether a default branch has been chosen 
     */
    public function checkIfDefaultBranchAlreadyChosen()
    {
        $data = array();
        $data['consultantId']                   = $this->input->post('consultantId');
        $data['regionId']                       = $this->input->post('regionId');
        $data['excludeConsultantLocationId']    = $this->input->post('excludeConsultantLocationId');
        
        $result = $this->consultantPostingLib->checkIfDefaultBranchAlreadyChosen($data);
        
        echo json_encode($result);
    }
    /*
     * function to set a location as head office
     */
    public function setLocationAsHeadOffice()
    {
        $this->usergroupAllowed = array('saAdmin','saCMS','saCMSLead');
         // get the user data
      
        $user = $this->consultantAbroadUserValidation();
               
        $params = array();
        $params['userId']               = $user[userid];
        $params['consultantId']         = $this->input->post('consultantId');
        $params['consultantLocationId'] = $this->input->post('consultantLocationId');
        
        $result = $this->consultantPostingLib->setLocationAsHeadOffice($params);
        
        echo json_encode($result);
    }
    /*
     *
     */
    public function saveConsultantRegionSubscriptionData()
    {
        $this->usergroupAllowed = array('saAdmin','saSales','saCMSLead');
        // get the user data
        $userCheck  = $this->consultantAbroadUserValidation(true);
        if($userCheck['error'] == 'true'){
            echo json_encode($userCheck);
            return false;
        }
        $displayData = $userCheck;
        $data = $this->collectPostDataFromCitySubscription($displayData['userid']);
        // call background process to prepare consultant mailers for users
        $command = "/usr/local/php/bin/php /var/www/html/shiksha/cron.php --run=/consultantEnquiry/ConsultantCrons/callToPrepareConsultantMailersForUsers/";
        $command .= base64_encode(json_encode(
                                              array(
                                                    'regionAssignment'=> array('consultantId'=>$data['consultantId'],
                                                                               'universityId'=>$data['universityId'],
                                                                               'regionId'    =>$data['regionId'],
                                                                               'mode'        =>$data['cityAssignmentSaveMode'])
                                                    )
                                            )
                                    );
        runProcessInBackground($command,'/tmp/consultantMailerForUserLog.txt');
        // send data to model
        echo json_encode($this->consultantPostingLib->saveCitySubscriptionFormData($data));
    }
    /*
     *function to get POST data for city subscription form
     */
    public function collectPostDataFromCitySubscription($userId)
    {
        $data = array();
        $data['consultantId'            ] = $this->input->post('consultantId'               );
        $data['universityId'            ] = $this->input->post('universityId'               );
        $data['regionId'                  ] = $this->input->post('regionId'                 );
        $data['startDate'               ] = $this->input->post('startdate'                  );
        $data['endDate'                 ] = $this->input->post('enddate'                    );
        $data['salesPerson'             ] = $this->input->post('salesPerson'                );
        $data['createdAt'               ] = $this->input->post('createdAt'                  );
        $data['createdBy'               ] = $this->input->post('createdBy'                  );
        $data['modifiedBy']               = $userId;
        $data['cityAssignmentSaveMode'  ] = $this->input->post('cityAssignmentSaveMode'     );
        $data['cityAssignmentComments'  ] = $this->input->post('cityAssignmentComments'     );
        $data['subscriptionId'          ] = $this->input->post('subscriptionId'             );
        return $data;
    }
    /*
     * check if subscription already given on a combination (consultant,city,univ)
     */
    public function checkIfSubscriptionAlreadyExists()
    {
        $data = array();
        $dateArray = array();
        $data['consultantId'    ] = $this->input->post('consultantId');
        $data['regionId'          ] = $this->input->post('regionId');
        $data['universityId'    ] = $this->input->post('universityId');
        $dateArray['startDate'  ] = $this->input->post('startDate');
        $dateArray['endDate'    ] = $this->input->post('endDate');
        $subscriptionId           = $this->input->post('subscriptionId');
        
        $result = $this->consultantPostingLib->checkIfSubscriptionAlreadyExists($data,$dateArray,$subscriptionId);
        
        echo json_encode($result);
    }

    
    public function viewLocality() {
        $displayData = $this->consultantAbroadUserValidation();
        
        $searchLocality = $this->input->get('searchLocality');
        $resultPerPage  = $this->input->get('resultPerPage');
        
        $searchLocality = ($searchLocality == 'Search a Location')?'':$searchLocality;
        $resultPerPage  = ($resultPerPage)?$resultPerPage:'';
        
        $urlParams = '?'.(($searchLocality)?'&searchLocality='.$searchLocality:'').(($resultPerPage)?'&resultPerPage='.$resultPerPage:'');
        
        $URL 		= ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_LOCALITY_TABLE."/";
        $URLPagination 	= ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_LOCALITY_TABLE."/".$urlParams;
	 //echo $URLPagination;die;	
        // initialize the paginator instance
    	$this->load->library('listingPosting/Paginator');
    	$displayData['paginator'] = new Paginator($URLPagination);
      	
        $result = $this->consultantPostingLib->getLocationTableData($searchLocality,$displayData['paginator']);
        $displayData['paginator']->setTotalRowCount($result['totalCount']['totalCount']);
        
        // prepare the display date here
        $displayData['formName']                = ENT_SA_VIEW_LOCALITY_TABLE;
        $displayData['selectLeftNav']           = "LOCATIONS";
        $displayData['locationsData']           = $result['data'];
        $displayData['resultCount']             = $result['totalCount'];
        $displayData['searchLocality']          = $searchLocality;
        $displayData['URL']                     = $URL;
        
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    public function addLocality() {
        // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        
        $displayData['consultantLocationCities']    = $this->consultantPostingLib->getConsultantLocationCities();
        //_p($displayData['consultantLocationCities']);die;
        $displayData['formName'] = ENT_SA_FORM_ADD_LOCALITY;
        $displayData['selectLeftNav']   = "LOCATIONS";
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    public function editLocality() {
        $displayData    = $this->consultantAbroadUserValidation();
        $locationId = $this->input->get('localityId');
        if(is_nan($locationId)){
            $this->consultantPostingLib->showErrorMessage();
        }
        
        $displayData['consultantLocationCities']    = $this->consultantPostingLib->getConsultantLocationCities();
        
        $result = $this->consultantPostingLib->getLocalityData($locationId);
        //_p(reset($result));die;
        
        $displayData['locationFormData']        = reset($result);
        $displayData['formName']        = ENT_SA_FORM_EDIT_LOCALITY;
        $displayData['selectLeftNav']   = "LOCATIONS";
        $this->load->view('consultantPosting/consultantOverview',$displayData);
    }
    
    public function saveLocalityFormData() {
        $displayData    = $this->consultantAbroadUserValidation(TRUE);
        
        if(isset($displayData['error']) == "true"){
            echo json_encode(array('accessDenied'   => $displayData['error_type']));
            exit(0);
        }
        
        $locationData['cityIds']            = $this->input->post('locationCity');
        $locationData['localityNames']      = array_map('trim',$this->input->post('localityName'));
        $locationData['localityActiontype'] = $this->input->post('localityActionType');
        $locationData['localityId']         = $this->input->post('localityId');
        $locationData['modifiedBy']         = $displayData['userid'];
        
        $checkExistingLocations = $this->consultantPostingLib->checkLocalityExistence($locationData['localityNames'],$locationData['cityIds']);
        //_p($checkExistingLocations);
        if(count($checkExistingLocations)){
            $checkExistingLocations = array('fail'  => $checkExistingLocations);
            $checkExistingLocations = json_encode($checkExistingLocations);
            echo $checkExistingLocations;
            exit(0);
        }
        
        $result = $this->consultantPostingLib->saveLocalityFormData($locationData);
        
        $result = array('success'   => $result);
        $result = json_encode($result);
        echo $result;
        exit(0);
    }

    /*
     * check if subscription already given on a combination (consultant,city,univ)
     */
    public function getCityUniversityCombinations()
    {
        $data = array();
        $dateArray = array();
        $data['regionId'          ] = $this->input->post('regionId');
        $data['universityId'    ] = $this->input->post('universityId');
        $dateArray['startDate'  ] = $this->input->post('startDate');
        $dateArray['endDate'    ] = $this->input->post('endDate');
        $subscriptionId           = $this->input->post('subscriptionId');
        
        $result = $this->consultantPostingLib->getCityUniversityCombinations($data, $dateArray, $subscriptionId);
        
        echo json_encode($result);
    }
    
    public function getExcludedCoursesForUniversity($consultantId,$univId){
        $ajaxFlag = false;
        if(empty($univId)){
            $data = $this->input->post('data');
            $data = json_decode($data,TRUE);
            $univId = intval($data["universityId"]);
            $consultantId = intval($data["consultantId"]);
            if(!empty($univId)){
                $ajaxFlag = true;
            }
        }
        if(!is_numeric($univId)){
            if($ajaxFlag){
                echo "Invalid University Id";
            }
            return false;
        }
        if(!is_numeric($consultantId)){
            if($ajaxFlag){
                echo "Invalid Consultant Id";
            }
            return false;
        }
        $data = $this->consultantPostingLib->getExcludedCoursesForUniversity($consultantId,$univId);
        if($ajaxFlag){
            echo json_encode($data);
        }else{
            return $data;
        }
    }

    public function viewClientConsultantSubscription($displayDataStatus ='all')
    {
        
         $this->usergroupAllowed = array('saAdmin','saSales','saCMSLead');
         // get the user data
        $displayData = $this->consultantAbroadUserValidation();
        
        //get the values posted in the form
        $searchConsId    = $this->input->get("q");
    	$resultPerPage     = $this->input->get("resultPerPage");
    	
        //check and set the values of the variables for db queries
    	$searchConsId   = ($searchConsId == "Search Consultant ID") ? "" :$searchConsId;
    	$resultPerPage  = ($resultPerPage) ? $resultPerPage : "";
    
    	// prepare the url to retain the last call parameters as query string
	$urlParams	= "?1";
	$urlParams     .= ($searchConsId ? "&q=".$searchConsId : "");
	$urlParams     .= ($resultPerPage  ? "&resultPerPage=".$resultPerPage : "");        
	    	
    	// prepare the URL for view as well as for paginator
	$URL 		= ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_UPGRADE_CONSULTANT."/".$displayDataStatus;
	$URLPagination 	= ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_UPGRADE_CONSULTANT."/".$displayDataStatus.$urlParams;
	 	
        // initialize the paginator instance
    	$this->load->library('listingPosting/Paginator');
    	$displayData['paginator'] = new Paginator($URLPagination);
       
		        
        //fetch consultant data here
        $result = $this->consultantPostingLib->getClientConsultantSubscription($displayData['paginator'],$searchConsId);
        
        // prepare the display date here
      
        $displayData['paginator']->setTotalRowCount($result['totalCount']);
        $displayData['subscriptionData']  =$result['subscriptionData'];
        $displayData['consultantData']    =$result['consultantData'];
        $displayData['totalCount']        =$result['totalCount'];
        $displayData['searchTerm'] 	    = $searchConsId;
        $displayData['urlParams'] 	    = $urlParams;
        $displayData['URL'] 	  	    = $URL;
        $displayData['formName']        = ENT_SA_VIEW_UPGRADE_CONSULTANT;
	$displayData['selectLeftNav']       = "UPGRADE_CONSULTANT";

        $this->load->view('consultantPosting/consultantOverview',$displayData);
        
        
        }
        
    public function addClientConsultantSubscription(){
        
        $this->usergroupAllowed = array('saAdmin','saSales','saCMSLead');
        $displayData = $this->consultantAbroadUserValidation();
       
        $displayData['formName'] = ENT_SA_FORM_ADD_CLIENT_CONSULTANT_SUBSCRIPTION;
        $displayData['selectLeftNav']   = "UPGRADE_CONSULTANT";
        $this->load->view('consultantPosting/consultantOverview',$displayData);
        
        }
    
    public function editClientConsultantSubscription(){
        
        $this->usergroupAllowed = array('saAdmin','saSales','saCMSLead');
        $displayData = $this->consultantAbroadUserValidation();
        $mappingId = $this->input->get('mappingId');
        if($mappingId !=''){
         $mappingData =  $this->consultantPostingLib->getClientConsultantSubscriptionData($mappingId);
            if($mappingData['mappingId']=='' || $mappingData['subscriptionData']['data']['userName']=='' || $mappingData['consultantName']=='')
            {
               $this->consultantPostingLib->showErrorMessage();
            }
            $displayData['mappingData'] = $mappingData;
            $displayData['clientName']  = $mappingData['subscriptionData']['data']['userName'];
        }else{
            $this->consultantPostingLib->showErrorMessage();
        }
        $displayData['formName'] = ENT_SA_FORM_EDIT_CLIENT_CONSULTANT_SUBSCRIPTION;
        $displayData['selectLeftNav']   = "UPGRADE_CONSULTANT";
        $this->load->view('consultantPosting/consultantOverview',$displayData);
        
        }
    
    
    public function saveClientConsultantSubscriptionData()
    {
        $this->usergroupAllowed = array('saAdmin','saSales','saCMSLead');
        $displayData = $this->consultantAbroadUserValidation();
        $data['consultantId'    ]    = $this->input->post('consultantId');
        $data['clientId'        ]    = $this->input->post('clientId');
        $data['subscriptionId']      = $this->input->post('subscriptionId');
        $data['costPerResponse']     = $this->input->post('costPerResponse');
        $data['createdAt'] = date("Y-m-d H:i:s");
        $data['modifiedAt'] = date("Y-m-d H:i:s");
        $data['status'] = 'live';
        $data['createdBy'] = $displayData['userid'];
        $data['modifiedBy'] = $displayData['userid'];
        // prepare command for background process to prepare consultant mailers for users
        $command = "/usr/local/php/bin/php /var/www/html/shiksha/cron.php --run=/consultantEnquiry/ConsultantCrons/callToPrepareConsultantMailersForUsers/";
        
        $mappingId           = $this->input->post('mappingId');    
        if($mappingId >0)
        {
            $data['createdAt'] = $this->input->post('createdAt');
            $data['createdBy'] = $this->input->post('createdBy');
        }else{
            $mappingId = 0;
            }
        $command .= base64_encode(json_encode(
                                          array(
                                                'subscriptionAssignment'=> array('consultantId'=>$data['consultantId'],
                                                                                 'mappingId'   =>$mappingId)
                                                )
                                        )
                                );
        $result =  $this->consultantPostingLib->saveClientConsultantSubscriptionData($data,$mappingId);
        runProcessInBackground($command,'/tmp/consultantMailerForUserLog.txt');
        if($result['error']==false){
            $resultArr = array("errorFlag" => 0,"result"=>$result);
        }else{
            $resultArr = array("errorFlag" => 1,"errorMsg"=>$result['errorMsg']);
        }
        echo json_encode($resultArr);
    }    
    
    public function fetchConsultantDetailforClientMapping(){
        
        $data['consultantId'    ]    = $this->input->post('consultantId');
        $data['mappingId'        ]    = $this->input->post('mappingId');
        $result = $this->consultantPostingLib->validateConsultantforClientMapping($data);
        echo  json_encode($result);
        
    }
    
    public function fetchClientDetailAndSubscriptions(){
        
        $data['clientId']    = $this->input->post('clientId');
        $finalData = $this->consultantPostingLib->fetchClientDetailAndSubscriptions($data);
        echo json_encode($finalData);
    }
}
