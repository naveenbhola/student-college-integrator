<?php
/*
 * Model for study abroad consultant pages
 *
 */

class consultantmodel extends MY_Model{
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct(){
        parent::__construct('Consultant');
    }
    
    private function initiateModel($mode = "write"){
        if($this->dbHandle && $this->dbHandleMode == 'write')
            return;

        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($mode == 'read') {
                $this->dbHandle = $this->getReadHandle();
        } else {
                $this->dbHandle = $this->getWriteHandle();
        }
    }
    
    /*
     * function to get consultant data from db
     * @params : Consultant Id
     */
    public function getConsultantData($consultantId){
        if(!($consultantId)){
            return array();
        }
        //get read DB handle
        $this->initiateModel('read');
        //$consultantId = array(1,5);
        // query to get consultant general data
        $this->dbHandle->select('consultantId, name, logo, description, establishmentYear, facebookLink,
                                 linkedInLink, website, offersPaidServices, paidServicesDetails, offersTestPrepServices,
                                 testPrepServicesDetails, ceoName, ceoQualification, employeeCount');
        $this->dbHandle->from('consultant');
	$this->dbHandle->where('status','live');
	$this->dbHandle->where_in('consultantId',$consultantId);
	$result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();
        // indexed according to consultant Id
        foreach($result as $key=>$value){
            $resultSet[$value['consultantId']] = $value;
        }
        
        // query to get media (photos) for consultant
        $this->dbHandle->select('consultantId, media_type, name, url, thumburl');
        $this->dbHandle->from('consultantUploadedMedia');
	$this->dbHandle->where('status','live');
	$this->dbHandle->where_in('consultantId',$consultantId);
        $result = $this->dbHandle->get()->result_array();
        // indexed according to consultant Id
        foreach($result as $key=>$value){
            if(!is_array($resultSet[$value['consultantId']]['media']))
            {
                $resultSet[$value['consultantId']]['media'] = array($value['media_type'] => array($value));
            }
            else{
                $resultSet[$value['consultantId']]['media'][$value['media_type']][] = $value;
            }
        }

        // query to get universities mapped to consultant
        $this->dbHandle->select('CUM.consultantId, CUM.universityId, CUM.isOfficialRepresentative, CUM.representativeValidFrom, 
                                 CUM.representativeValidTo, CUM.proofType, CUM.proofWebsiteLink, CUM.proofPersonName,
                                 CUM.proofPersonDetails, CUM.proofEmailDocumentUrl, CUM.excludedCourseComments,
                                 group_concat(CUECM.courseId) as excludedCourses');
        $this->dbHandle->select('CUM.salesPerson, CSP.name as salesPersonName, CSP.email as salesPersonEmail');
        $this->dbHandle->from('consultantUniversityMapping CUM');
        $this->dbHandle->join('consultantSalesPersons CSP', 'CUM.salesPerson = CSP.id and CSP.status = CUM.status','inner');
        $this->dbHandle->join('consultantUniversityExcludedCourseMapping CUECM', 'CUM.consultantId = CUECM.consultantId and CUM.status = CUECM.status and CUM.universityId = CUECM.universityId','left');
	$this->dbHandle->where('CUM.status','live');
	$this->dbHandle->where_in('CUM.consultantId',$consultantId);
	$this->dbHandle->group_by('CUM.consultantId,CUM.universityId');
        $result = $this->dbHandle->get()->result_array();
        // indexed according to consultant Id
        foreach($result as $key=>$value){
            if(!is_array($resultSet[$value['consultantId']]['universityMappings']))
            {
                $resultSet[$value['consultantId']]['universityMappings'] = array($value);
            }
            else{
                $resultSet[$value['consultantId']]['universityMappings'][] = $value;
            }
        }
        
        $defaultBranches = $this->getDefaultBranchLocations(array('consultantId'=>$consultantId));
        foreach($defaultBranches as $consId => $branches)
        {
            if(!is_array($resultSet[$consId]['defaultBranches']))
            {
                $resultSet[$consId]['defaultBranches'] = array($consId=>$branches);
            }
            else{
                $resultSet[$consId]['defaultBranches'][] = $branches;
            }
        }
        //_p($resultSet);echo "<br>==========================";
        return $resultSet;
    }
    /*
     * function to get consultant location data from db
     * @params : Consultant Location Ids OR consultant ids, optional findByConsultant to denote which one is used
     */
    public function getConsultantLocationData($entityId, $findByConsultant = FALSE){
        if(!($entityId)){
            return array();
        }
        //$findByConsultant = FALSE;
        //get read DB handle
        $this->initiateModel('read');
        //$entityId = array(1,5);
        // query to get consultant location data
        $sql = 'select 
                    CL.consultantId, CL.consultantLocationId, CL.contactName, CL.defaultPhone,
                    CL.shikshaPRINumber,CL.displayPRINumber, CL.email, CL.cityId, CCT.city_name as cityName, CL.localityId,
                    CLL.name as localityName, CL.locationAddress, CL.pincode, CL.latitude, CL.longitude,
                    CL.defaultBranch, CL.headOffice, CL.contactHours, CL.phone1, CL.phone2, CL.phone3, CL.phone4
                from
                    consultantLocation CL
                inner join countryCityTable CCT
                    on (CL.cityId = CCT.city_id and CCT.countryId = 2)
                inner join consultantLocationLocality CLL
                    on (CL.cityId = CLL.cityId and CL.localityId = CLL.id and CLL.status = CL.status)
                where
                    CL.status = "live" ';
        // if we are searching by consultant then, change the where clause
        if($findByConsultant){
            $sql .= ' and CL.consultantId in( '.$this->dbHandle->escape_str(implode(',',$entityId)).' )';
        }
        else{
            $sql .= ' and CL.consultantLocationId in( '.$this->dbHandle->escape_str(implode(',',$entityId)).' )';
        }
        
        $result = $this->dbHandle->query($sql)->result_array();
        //echo $this->dbHandle->last_query();
        // indexed according to consultant Id
        foreach($result as $key=>$value){
            if(!is_array($resultSet[$value['consultantId']]))
            {
                $resultSet[$value['consultantId']] = array($value['consultantLocationId']=>$value);
            }
            else{
                $resultSet[$value['consultantId']][$value['consultantLocationId']] = $value;
            }
        }
        //_p($resultSet);
        return $resultSet;
    }
    
    
    /*
     * function to get consultant student profile data from db
     * @params : Consultant Ids OR student profile ids, optional findByConsultant to denote which one is used
     */
    public function getConsultantStudentProfileData($entityId, $findByConsultant = FALSE){
        if(!($entityId)){
            return array();
        }
        //get read DB handle
        $this->initiateModel('read');
        //$entityId = array(1,5);
        // query to get consultant student profile general data
        $this->dbHandle->select('profileId, consultantId, admissionDate, studentName, residenceCityId,
                                 classXPercentage, classXYear, classXIIPercentage, classXIIYear, totalWorkExperienceMonths,
                                 extraCurricularActivities, linkedInLink, facebookLink, studentEmail, studentPhone');
        $this->dbHandle->from('consultantStudentProfile');
	$this->dbHandle->where('status','live');
        // if we are searching by consultant then, change the where clause
        if($findByConsultant){
            $this->dbHandle->where_in('consultantId',$entityId);
        }
        else{
            $this->dbHandle->where_in('profileId',$entityId);
        }
	$result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();
        $profileIdSet = array();
        // indexed according to consultant Id
        foreach($result as $key=>$value){
            if(!is_array($resultSet[$value['consultantId']]))
            {
                $resultSet[$value['consultantId']] = array($value['profileId']=>$value);
            }
            else{
                $resultSet[$value['consultantId']][$value['profileId']] = $value;
            }
            // this will be used later in the flow for consolidating profile to doc/grad/company mappings
            $profileIdSet[$value['profileId']] = $value['consultantId'];
        }
        // get an array of profile ids for which other types of mappings need to be fetched
        $profileIds =array_keys($profileIdSet);
        $profileIds = (count($profileIds)==0?array(0):$profileIds);
        // 1. query to get consultant student profile to university mappings
        $this->dbHandle->select('profileId, universityId, courseName, desiredCourseId, courseLevel,
                                 subcategory, scholarshipRecieved, scholarshipDetails');
        $this->dbHandle->from('consultantStudentProfileToUniversityMapping');
	$this->dbHandle->where('status','live');
        $this->dbHandle->where_in('profileId',$profileIds);
	$result = $this->dbHandle->get()->result_array();
        foreach($result as $key => $value){
            if(!is_array($resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileUniversityMapping']))
            {
                $resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileUniversityMapping'] = array($value);
            }
            else{
                $resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileUniversityMapping'][] = $value;
            }
        }
        
        // 2. query to get consultant student profile to exam mappings
        $this->dbHandle->select('profileId, examId, examScore');
        $this->dbHandle->from('consultantStudentProfileToExamMapping');
	$this->dbHandle->where('status','live');
        $this->dbHandle->where_in('profileId',$profileIds);
	$result = $this->dbHandle->get()->result_array();
        foreach($result as $key => $value){
            if(!is_array($resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileExamMapping']))
            {
                $resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileExamMapping'] = array($value);
            }
            else{
                $resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileExamMapping'][] = $value;
            }
        }
        
        // 3. query to get consultant student profile to graduation mappings
        $this->dbHandle->select('profileId, universityName, collegeName, graduationCityId, graduationGPA, graduationPercentage, passingYear, description');
        $this->dbHandle->from('consultantStudentProfileToGraduationMapping');
	$this->dbHandle->where('status','live');
        $this->dbHandle->where_in('profileId',$profileIds);
	$result = $this->dbHandle->get()->result_array();
        foreach($result as $key => $value){
            if(!is_array($resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileGraduationMapping']))
            {
                $resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileGraduationMapping'] = array($value);
            }
            else{
                $resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileGraduationMapping'][] = $value;
            }
        }
        
        // 4. query to get consultant student profile to company mappings
        $this->dbHandle->select('profileId, companyName, companyDomain, startYear, endYear');
        $this->dbHandle->from('consultantStudentProfileToCompanyMapping');
	$this->dbHandle->where('status','live');
        $this->dbHandle->where_in('profileId',$profileIds);
	$result = $this->dbHandle->get()->result_array();
        foreach($result as $key => $value){
            if(!is_array($resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileCompanyMapping']))
            {
                $resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileCompanyMapping'] = array($value);
            }
            else{
                $resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileCompanyMapping'][] = $value;
            }
        }
        
        // 5. query to get consultant student profile to proof mappings
        $this->dbHandle->select('profileId, proofUrl');
        $this->dbHandle->from('consultantStudentProfileToDocumentProofMapping');
	$this->dbHandle->where('status','live');
        $this->dbHandle->where_in('profileId',$profileIds);
	$result = $this->dbHandle->get()->result_array();
        foreach($result as $key => $value){
            if(!is_array($resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileDocumentMapping']))
            {
                $resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileDocumentMapping'] = array($value);
            }
            else{
                $resultSet[$profileIdSet[$value['profileId']]][$value['profileId']]['profileDocumentMapping'][] = $value;
            }
        }
        //_p($resultSet);
        return $resultSet;
    }
    /*
     * function to:-
     *      get default branches for a given consultant,
     *      optionally get default branches for a given region only for a given consultant,
     *      also, gets the number of unique consultants active on the location queried
     * @params : array having -
     * 1. consultant id, 2. region id (optional)
     * NOTE: this function replaces the use of consultantRegionOfficeMappingConfig for getting default branches
     */
    public function getDefaultBranchLocations($data)
    {
        $consultantId = $data['consultantId'];
        $regionId     = $data['regionId'];
        //get read DB handle
        $this->initiateModel('read');
        $this->dbHandle->select('cl.consultantId, cl.consultantLocationId, cl.regionId, cl.cityId, cl.localityId, cl.locationAddress, cl.shikshaPRINumber,cl.displayPRINumber');
        $this->dbHandle->from('consultantLocation cl');
        $this->dbHandle->join('consultantRegionSubscription crs','crs.consultantId = cl.consultantId and cl.regionId = crs.regionId and cl.status = crs.status and crs.startDate < NOW() and crs.endDate > NOW()','inner');
        $this->dbHandle->where('cl.defaultBranch','yes');
        $this->dbHandle->where('cl.status','live');
        $this->dbHandle->where_in('cl.consultantId',$data['consultantId']);
        if($data['regionId'])
        {
            $this->dbHandle->where_in('cl.regionId',$data['regionId']);
        }
        $result = $this->dbHandle->get()->result_array();
        //_p($result);
        // structure its same as the config
        $resArray = array();
        foreach($result as $row)
        {
            if(!$resArray[$row['consultantId']])
            {
                $resArray[$row['consultantId']] = array();
            }
            $resArray[$row['consultantId']][$row['regionId']]
                = array(
                            'locationId'    => $row['consultantLocationId'],
                            'localityId'    => $row['localityId'],
                            'cityId'        => $row['cityId'],
                            'officeAddress' => $row['locationAddress'],
                            'phoneNumber'   => $row['shikshaPRINumber'],
                            'displayNumber'   => $row['displayPRINumber']
                        );
        }
        return $resArray;
    }

   public function prepareDataForCountriesRepresentedTab($countryRep)
    {
	//find all university Ids mapped to a consultant foreach Country ID
	$countryIds=array();
	$consCountperCountry = array();
	foreach($countryRep as $countryId => $countryName)
	{
	    $countryIds[] = $countryId;
	}
	
	//$countryIds = array('20',"zxvzx'sf",546);
	//To avoid the case of array defind in comment we are using loop with mysql_escape_string
	// foreach($countryIds as $key=>$value){
	//     $countryIdsStr.= mysql_escape_string($value)."','";
	// }

	$sql ="SELECT COUNT( DISTINCT CUM.consultantId) as consCount , UNIV.country_id as countryId
	       FROM university_location_table UNIV
	       INNER JOIN consultantUniversityMapping CUM
	       ON CUM.universityId = UNIV.university_id
	       AND CUM.status = 'live'
	       WHERE UNIV.status ='live'
	       AND UNIV.`country_id` IN (?) GROUP BY UNIV.country_id";
		
	$result =  $this->dbHandle->query($sql,array($countryIds))->result_array();
	//echo $this->dbHandle->last_query();
	//die;

	
	foreach($result as $value)
	{
	    $consCountperCountry [$value['countryId']] = $value['consCount'];
	}

	//sort the associative array of consultant count in all the countries in descending order based on count
	arsort($consCountperCountry);

	
	$sortedCountryRep = array();
	// using the above sorted array sort the countryRep array
	foreach($consCountperCountry as $countryId => $consCount)
	{
	   $sortedCountryRep[$countryId] = $countryRep[$countryId];
	   unset($countryRep[$countryId]);
	}
	unset($consCountperCountry);

	return $sortedCountryRep;
    }

    /*
     * function to save (insert/update consultant enquiry information)
     * @params : $data to be inserted into the table, insertFlag [(optional) default:true, send false to update)]
     */
    public function saveConsultantEnquiry($data, $insertFlag = TRUE)
    {
        $this->initiateModel('write');
        // if we need to insert..
        if($insertFlag === true){
            unset($data['lastInsertedId']);
            $this->dbHandle->insert('consultantProfileEnquiries',$data);
            $lastRecordId = $this->dbHandle->insert_id();
        }
        // else update
        else{
            $lastRecordId = $data['lastInsertedId'];
            $this->dbHandle->where('id',$lastRecordId);
            unset($data['lastInsertedId']);
            $this->dbHandle->update('consultantProfileEnquiries',$data);
        }
        return $lastRecordId;
    }

    /*
     * function to determine whether the consultant enquiry made by the user needs to be inserted or updated
     * when the user has already created an enquiry first. It checks if any user information is changed or not.
     * In case they haven't then it checks whether user is submitting an enquiry after 24 hours(new insert) or
     * before (update enquiry)
     * @params : array containing id of the existing consultant enquiry ,submit time of last enquiry
     * & new consultant enquiry data.
     * @return : boolean(true if insert required, else false)
     */
    public function checkIfInsertRequired($data)
    {
        $this->initiateModel('read');
        // get last record as per cookie..
        $oldEnquiry = $this->getUserDataFromConsultantEnquiryCookieRecord($data, true);
        $resArray = array();
        // check if user has changed their info ??
        if($oldEnquiry['userId']     != $data['newEnquiry']['userid'] ||
           $oldEnquiry['email']     != $data['newEnquiry']['email'] ||
           $oldEnquiry['mobile']    != $data['newEnquiry']['mobile'] ||
           $oldEnquiry['firstName'] != $data['newEnquiry']['firstName'] ||
           $oldEnquiry['lastName']  != $data['newEnquiry']['lastName']
           )
        {
            foreach($data['newEnquiry']['consultantId'] as $k=>$v)
            {
                $resArray[$v] = true;
            }
            return $resArray; // must be inserted
        }
        
        // get last enquiry from this user against currently enquired consultant id,
        $this->dbHandle->select('id,consultantId,email,mobile,firstName,lastName,submitTime,userId');
        $this->dbHandle->from('consultantProfileEnquiries');
        $this->dbHandle->where_in('consultantId', $data['newEnquiry']['consultantId']);
        $this->dbHandle->where('email', $oldEnquiry['email']);
        $this->dbHandle->where('mobile', $oldEnquiry['mobile']);
        $this->dbHandle->where('firstName', $oldEnquiry['firstName']);
        $this->dbHandle->where('lastName', $oldEnquiry['lastName']);
        $this->dbHandle->where('userId', $oldEnquiry['userId']);
        $this->dbHandle->where('submitTime >= now() - INTERVAL '.CONSULTANT_ENQUIRY_INTERVAL_IN_DAYS.' DAY'); 
        $result = $this->dbHandle->get()->result_array();
        foreach($result as $k=>$row)
        {
            $resArray[$row['consultantId']] = $row; 
        }
        
        foreach($data['newEnquiry']['consultantId'] as $k=>$v)
        {
            // if no results found for this consultantId, then new record would be inserted
            if(count($resArray[$v]) == 0)
            {
                $resArray[$v] = true;
            }
            //otherwise it will have details of enquiry fetched for consultant $v
        }
        return $resArray;
    }
    /*
     * gets the user data: email,mobile,firstName,lastName,userId from the last enquiry that was captured in the cookie
     */
    public function getUserDataFromConsultantEnquiryCookieRecord($data , $checkForUserId = false)
    {
        $this->initiateModel('read');
        // get last record as per the enquiry id stored in cookie..
        $this->dbHandle->select('email,mobile,firstName,lastName,userId');
        $this->dbHandle->from('consultantProfileEnquiries');
        if($checkForUserId && $data['newEnquiry']['userid'] > 0)
        {   // incase of user we need to check if same user with same details has made any enquiry in last 24 hrs 
            $this->dbHandle->where('userId', $data['newEnquiry']['userid']);
            $this->dbHandle->where('email', $data['newEnquiry']['email']);
            $this->dbHandle->where('mobile', $data['newEnquiry']['mobile']);
            $this->dbHandle->where('firstName', $data['newEnquiry']['firstName']);
            $this->dbHandle->where('lastName', $data['newEnquiry']['lastName']);
        }
        else{
            $this->dbHandle->where('id', $data['lastInsertedId']);
            $this->dbHandle->where('submitTime', $data['submitTime']);
        }
        $result = $this->dbHandle->get()->result_array();
        return $result[0];
    }
    /*
     * function to get sales person who assign subscription to a consultant on a region
     * @params : $consultantId, $regionId
     */
    public function getRegionSalesPersonForConsultant($consultantId, $regionId)
    {
        $this->initiateModel('read');
        
        $this->dbHandle->select('crs.consultantId,crs.regionId,csp.name,csp.email');
        $this->dbHandle->from('consultantRegionSubscription crs');
        $this->dbHandle->join('consultantSalesPersons csp','crs.salesPerson = csp.id and crs.status = csp.status', 'inner');
        $this->dbHandle->where('crs.consultantId',$consultantId);
        $this->dbHandle->where('crs.regionId',$regionId);
        $this->dbHandle->where('crs.status','live');
        
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    function fetchUserResponseOnConsultant(){
        $this->initiateModel('read');

        $queryTofetchUserIdsFromConsultant = "select distinct userId from consultantProfileEnquiries";
        $result = $this->dbHandle->query($queryTofetchUserIdsFromConsultant)->result_array();

        foreach ($result as $key => $value) {
            $finalResult[] = $value['userId'];
        }

        return $finalResult;
    }
    /*
     * function to get sales person name & email (for email purposes) on a region
     * note: can be used for multiple consultants
     */
    public function getSalesPersonData($consultantIds, $regionId)
    {
        $this->initiateModel('read');
        $this->dbHandle->select('crs.consultantId,csp.name as salesPersonName, csp.email as salesPersonEmail');
        $this->dbHandle->from('consultantRegionSubscription crs');
        $this->dbHandle->join('consultantSalesPersons csp','csp.id = crs.salesPerson and crs.status = csp.status','inner');
        $this->dbHandle->where_in('crs.consultantId',$consultantIds);
        $this->dbHandle->where('crs.regionId',$regionId);
        $this->dbHandle->where('crs.status','live');
        $result = $this->dbHandle->get()->result_array();
        $resArray = array();
        foreach($result as $k=>$row)
        {
            $resArray[$row['consultantId']] = $row;
        }
        return $resArray;
    }
    /*
     * function to get course name for which response was made(used in client mail)
     */
    public function getListingSubjectedToResponseGeneration($tempLMSId)
    {
        $this->initiateModel('write');
        $this->dbHandle->select('lms.listing_type_id as courseId,cd.courseTitle as courseName, lms.submit_date as submitTime ');
        $this->dbHandle->from('tempLMSTable lms');
        $this->dbHandle->join('course_details cd',"lms.listing_type_id = cd.course_id and cd.status = 'live'","inner");
        $this->dbHandle->where('lms.listing_type', 'course');
        $this->dbHandle->where('lms.id',$tempLMSId);
        $result = $this->dbHandle->get()->result_array();
        return $result[0];
    }
    
    public function getConsultantDataFromPRINumber($priNumber){
		$this->initiateModel("read");
		$this->dbHandle->select("cityId, consultantId");
		$this->dbHandle->from("consultantLocation");
		$this->dbHandle->where("status","live");
		$this->dbHandle->where("shikshaPRINumber",$priNumber);
		return reset($this->dbHandle->get()->result_array());
    }
    
    public function getConsultantSubscriptionDetails($consultantId) {
	$this->initiateModel('read');
        $this->dbHandle->select('consultantId, clientId, subscriptionId, costPerResponse');
        $this->dbHandle->from('consultantClientSubscriptionDetail');        
        $this->dbHandle->where('status','live');
	$this->dbHandle->where('consultantId',$consultantId);
        $result = $this->dbHandle->get()->result_array();
	
        return (reset($result));
    }
    
    public function checkVendorDataIfInsertRequired($data){
	$this->initiateModel('read');
	// First we need to check if this person has called within the past 24 hours
	$this->dbHandle->select('id, submitTime');
	$this->dbHandle->from("consultantProfileEnquiries");
	$this->dbHandle->where("mobile",$data['mobile']);
	$this->dbHandle->where('source','vendorInfo');
	$this->dbHandle->where("submitTime > NOW() - INTERVAL ".CONSULTANT_ENQUIRY_INTERVAL_IN_DAYS." DAY");
	$checkData = reset($this->dbHandle->get()->result_array());
	if(empty($checkData)){
	    return array(reset($data['consultantId']) => true);
	}
	//This person has data in the enquiry table, in this case.
	$this->dbHandle->select('id,consultantId,email,mobile,firstName,lastName,submitTime');
        $this->dbHandle->from('consultantProfileEnquiries');
        $this->dbHandle->where("id",$checkData['id']);
        $result = $this->dbHandle->get()->result_array();
	$resArray = array();
        foreach($result as $k=>$row)
        {
            $resArray[$row['consultantId']] = $row; 
        }
	return $resArray;
    }
    
    public function saveVendorConsultantEnquiryData($data){
	$this->initiateModel("write");
	$this->dbHandle->select("id");
	$this->dbHandle->from("consultantCallEnquiryData");
	$this->dbHandle->where("callerNumber",$data['callerNumber']);
	$this->dbHandle->where("submitTime",$data['submitTime']);
	$this->dbHandle->where('agentConnected',$data['agentConnected']);
	$this->dbHandle->where('callUUID',$data['callUUID']);
	$tres = reset($this->dbHandle->get()->result_array());
	if(empty($tres)){
	    $this->dbHandle->insert("consultantCallEnquiryData",$data);
	    $id = $this->dbHandle->insert_id();
	}else{
	    $id = -1;
	}
	return $id;
    }
    
    public function trackConsultantSiteVisit($data){
	$this->initiateModel('write');
	if(!empty($data)){
	    $this->dbHandle->insert('consultantWebsiteVisitTracking',$data);
	}
	return $this->dbHandle->insert_id();
    }
    
    public function getUnprocessedConsultantStudentRecordingUrls(){
	$this->initiateModel("read");
	$sql = "select id, vendorRecordingUrl from consultantCallEnquiryData where shikshaRecordingUrl is null and vendorRecordingUrl != 'None'";
	$res = $this->dbHandle->query($sql)->result_array();
	$result = array();
	foreach($res as $row){
	    $result[$row['id']] = $row['vendorRecordingUrl'];
	}
	return $result;
    }
    
    public function saveProcessedConsultantStudentRecordingUrls($savedUrls){
	$this->initiateModel("write");
	foreach($savedUrls as $id=>$url){
	    $sql = "update consultantCallEnquiryData set shikshaRecordingUrl = ? where id = ?";
	    $this->dbHandle->query($sql,array($url,$id));
	}
    }
    
    public function scheduleConsultantMailer($data){
        if(!is_array($data) || count($data) == 0){
            return;
        }
        $this->initiateModel('write');
        $this->dbHandle->insert_batch('consultantMailerSchedule',$data);
        return TRUE;
    }
    
    public function getConsultantMailerData($date,$lowerLimitForData=0,$dataLimit=500){
        $parsedDate = date_parse($date);
        if(!($parsedDate['error_count'] == 0 && checkdate($parsedDate['month'], $parsedDate['day'], $parsedDate['year']))){
            return;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('SQL_CALC_FOUND_ROWS cmq.id as consultantMailerId,cmq.consultantId,cmq.tempLmsId,tlms.userId,tlms.email,tlms.listing_type_id AS courseId',FALSE);
        $this->dbHandle->from('consultantMailerSchedule cmq, tempLMSTable tlms');
        $this->dbHandle->where('cmq.tempLmsId = tlms.id');
        //$this->dbHandle->where('cmq.emailToBeProcessedAt <=',$date);
        $this->dbHandle->where(array('cmq.emailToBeProcessedAt' => $date,'tlms.listing_type' => 'course' , 'cmq.emailProcessedAt' => NULL));
        $this->dbHandle->limit($dataLimit,$lowerLimitForData);
        $resultSetData['data'] = $this->dbHandle->get()->result_array();
        //echo 'sql : '.$this->dbHandle->last_query();
        $sql = "SELECT FOUND_ROWS() AS totalCount";
        $rowResult = $this->dbHandle->query($sql)->row_array();
        $resultSetData['totalResultCount'] = $rowResult['totalCount'];
        return $resultSetData;
    }
    
    public function updateConsultantMailerScheduleForSuccessMailers($successMailersIds = array()) {
        if(count($successMailersIds) == 0){
            return;
        }
        $this->initiateModel('write');
        $updateData = array('emailProcessedAt' => date('Y-m-d H:i:s'));
        $this->dbHandle->where_in('id',$successMailersIds);
        $result = $this->dbHandle->update('consultantMailerSchedule',$updateData);
        if($result !== FALSE){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    public function getPastResponseData($date='0000-00-00',$last2DayChecks,$courseIds=array(),$lowerLimit=0,$dataLimit=500) {
        $parsedDate = date_parse($date);
        if(!($parsedDate['error_count'] == 0 && checkdate($parsedDate['month'], $parsedDate['day'], $parsedDate['year']))){
            return;
        }
        if(!(is_array($courseIds) && count($courseIds) > 0)){
            return;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('SQL_CALC_FOUND_ROWS id as tempLMSId,userId,listing_type_id as courseId,date(submit_date) as submit_date',FALSE);
        $this->dbHandle->from('tempLMSTable');
        $this->dbHandle->where(array(   'listing_type'              => 'course',
                                        'date(submit_date) >'       => $date
                                    )
                                );
        if($last2DayChecks == 'Y'){
            $backDateCheck = date("Y-m-d", strtotime("- 2 days"));
            $this->dbHandle->where(array('submit_date <' => $backDateCheck));
        }
        $this->dbHandle->where_in('listing_type_id',$courseIds);
        $this->dbHandle->limit($dataLimit,$lowerLimit);
        $resultSet['data'] = $this->dbHandle->get()->result_array();
        //echo 'SQL : '.$this->dbHandle->last_query();
        $sql = "SELECT FOUND_ROWS() AS totalCount";
        $rowResult = $this->dbHandle->query($sql)->row_array();
        $resultSet['totalResultCount']  = $rowResult['totalCount'];
        return $resultSet;
    }
}
