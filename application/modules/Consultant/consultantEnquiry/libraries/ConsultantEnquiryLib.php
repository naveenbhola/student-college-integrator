<?php
/*
 * library to contain common user oriented function that get certain data/ submit enquiries
 * required for consultant enquiry forms
 */
class ConsultantEnquiryLib {
    private $CI;
    
    function __construct()
    {
        $this->CI =& get_instance();
        $this->consultantmodel      = $this->CI->load->model('consultantProfile/consultantmodel');
    }
    /*
     * function to save consultant enquiry into the database, performs necessary validations
     * to decide whether insertion is required or updation
     * 
     * $params : consultant enquiry data :['source','email','mobile','firstName','lastName'
     * ,message',regionId',consultantId(array)','tempLmsId','userid']
     * 
     * note: sessionId will determined here itself
     */
    public function saveConsultantEnquiry($data,$offsiteCallFlag = false)
    {
        // read from cookie
        $consultantEnquiryCookieVal = $this->getPreviousEnquiryFromCookie();
        // keep the university name wherever available separately
        $universityName = $data['university'];
        unset($data['university']);
        // cookie not available 
        if(!$consultantEnquiryCookieVal) {	// Offsite calls will always enter this section
			$insertFlags = array();
			if(!$offsiteCallFlag){
				foreach($data['consultantId'] as $k => $v){
					$insertFlags[$v] = true;
				}
			}else{
				$insertFlags = $this->consultantmodel->checkVendorDataIfInsertRequired($data);
			}
        }
        else{
            // pass current submission as well
            $consultantEnquiryCookieVal['newEnquiry'] = $data;
            // 24 check + check for new insert or updation?
            $insertFlags = $this->consultantmodel->checkIfInsertRequired($consultantEnquiryCookieVal);
        }
        session_start();
        $data['sessionId'] = session_id();
        $data['visitorSessionid'] = getVisitorSessionId();
        //keep an array for email information (consultant wise)
        $emailInfo =array();
        $insertedCount = 0;
        foreach($insertFlags as $consId => $insertFlag)
        {
            if($insertFlag === true && !$offsiteCallFlag){	// If we insert
                $data['submitTime'] = date('Y-m-d H:i:s');
            }else if($insertFlag === true && $offsiteCallFlag){ 	// If we are inserting from vendor, we don't want it to go to else
		$data['lastModifiedTime'] = $data['submitTime']; 
	    }
            else { // if we update
		if($offsiteCallFlag){ 
	 	    $data['lastModifiedTime'] = $data['submitTime'];			
		}
		$data['submitTime'      ] = $insertFlag['submitTime'];
		$data['lastInsertedId'  ] = $insertFlag['id'];
            }
	    
	    if(!$offsiteCallFlag){	// To set lastmodified time
		$data['lastModifiedTime'] = date('Y-m-d H:i:s');
	    }
            $data['consultantId'] = $consId;
            // consume credits
            $consumptionResult = $this->consumeConsultantSubscriptionCredits($data['consultantId'],$insertFlag);
            // if consumption unsuccessful..
            if($consumptionResult === 'consumption_failed')
            {   // skip that consultant's enquiry
                continue;
            }
            else{
                $insertedCount++;
            }
            // ..save the data
            $lastInsertedId = $this->consultantmodel->saveConsultantEnquiry($data,$insertFlag);
            $submitTime     = ($insertFlag === true?$data['lastModifiedTime']:$consultantEnquiryCookieVal['submitTime']);
            
            //collect user data to send mail to client & user if we insert
            if($insertFlag === true)
            {
                $emailInfo[$consId] = array('userEmail'  => $data['email'],
                                            'userMobile' => $data['mobile'],
                                            'userName'   => trim($data['firstName']." ".$data['lastName']),
                                            'regionId'   => $data['regionId']
                                            );
            }
        }
        // if there was just one consultant inserted & their credits expired , we cant show thank you layer to the user
        if($insertedCount == 0 && $consumptionResult == 'consumption_failed')
        {
            return $consumptionResult;
        }
        // get logged in user details
        $userInfo = $this->getLoggedInUserDetailsForMail($data['tempLmsId']);
        // pass on the universityName for client mails
        $userInfo['universityName'] = $universityName;
        // send mail
        $this->sendConsultantEnquiryMails($emailInfo,$userInfo,$offsiteCallFlag);
        
        // set the last inserted id into the cookie
	if(!$offsiteCallFlag){
	    setcookie('consultantEnquiry', json_encode($lastInsertedId."~".$submitTime), 0,'/',COOKIEDOMAIN);
	}
        return $lastInsertedId;
    }
    /*
     * function to get the user data that needs to be fetched in order to populate the consultant enquiry form
     * when a user who has already submitted an enquiry, tries to open the form.
     * @params : assoc. array having these: lastInsertedId, submitTime 
     */
    public function getUserDataForConsultantEnquiryForm($cookieData)
    {
        $result = $this->consultantmodel->getUserDataFromConsultantEnquiryCookieRecord($cookieData);
        return $result;
    }
    /*
     * function that reads cookie 'consultantEnquiry' & gets the last inserted id & its submitTime
     * returns false if it doesn't exists.
     */
    public function getPreviousEnquiryFromCookie()
    { 
        if(isset($_COOKIE['consultantEnquiry']) &&  $_COOKIE['consultantEnquiry'] != '')
        {
            $cookieVal= explode('~',json_decode($_COOKIE['consultantEnquiry']));
            $consultantEnquiryCookieVal = array('lastInsertedId' => $cookieVal[0],
                                                'submitTime'     => $cookieVal[1]);
            return $consultantEnquiryCookieVal;
        }
        else{
            return false;
        }
    }
    /*
     * function to get sales person who assign subscription to a consultant on a region
     * @params : $consultantId, $regionId
     */
    public function getRegionSalesPersonForConsultant($consultantId, $regionId)
    {
        $result = $this->consultantmodel->getRegionSalesPersonForConsultant($consultantId, $regionId);
        return $result[0];
    }
    /*
     *
     */
    public function sendConsultantEnquiryMails($emailInfo,$userInfo = array(),$offsiteCallFlag)
    {
        $alertsClient = $this->CI->load->library('alerts_client');
        
        // get consultant related data for mail
        if(count($emailInfo) >=1)
        {
            $this->getConsultantDataForMail($emailInfo);
        }
        else
        {
            return false;
        }
        $consultantPostingLib = $this->CI->load->library('consultantPosting/ConsultantPostingLib');
        $regions = $consultantPostingLib->getRegionsMappingData();
        
        foreach($emailInfo as $consultantId => $params)
        {
            $params['regions'] = $regions;
            $params['userInfo'] = $userInfo;
            
            // send mail to the client ..
            $params['template_type'] = 'client';
            // prepare email content for client's version of CP enquiry mail
            $emailContent = $this->getEmailContent($params);
            // send mail to client/consultant, sales person, BCC to simrandeep.singh@shiksha.com
            $response = $alertsClient->externalQueueAdd("12",SA_ADMIN_EMAIL,
                                                        $params['clientEmail'],
                                                        $emailContent['subject'],
                                                        $emailContent['content'],
                                                        "html",'','n',array(),
                                                        $params['salesPersonEmail'],
                                                        'simrandeep.singh@shiksha.com');
            if(!$offsiteCallFlag){
		// now.. to the student
		$params['template_type'] = 'user';
		// prepare email content for user's version of CP enquiry mail
		$emailContent = $this->getEmailContent($params);
		// send mail to user
		$response = $alertsClient->externalQueueAdd("12",SA_ADMIN_EMAIL,
                                                        $params['userEmail'],
                                                        $emailContent['subject'],
                                                        $emailContent['content'],
                                                        "html",'','n',array());
	    }
            
        }
    }
    /*
     * function to prepare email content for email
     */
    public function getEmailContent($params)
    {
        $emailData = array();
        if($params['template_type'] == 'client')
        {
            $emailData['subject'] = 'New CP Enquiry (mob: '.($params['userMobile']).')';
        }
        else if($params['template_type'] == 'user'){
            $emailData['subject'] = 'Message sent to '.($params['clientName']);
        }
        $emailData['content'] = $this->CI->load->view('consultantEnquiry/consultantEnquiryMail'.ucfirst($params['template_type']),$params,true);
        return $emailData;
    }
    /*
     * function to get location ids of default locations for a given number of consultant region combinations
     * currently uses a config.
     */
    public function getConsultantDataForMail(&$emailInfo)
    {
        $this->CI->load->builder("ConsultantPageBuilder", "consultantProfile");
        $consultantPageBuilder   = new ConsultantPageBuilder();
		$consultantRepository = $consultantPageBuilder->getConsultantRepository();
        $consultantLocationRepository = $consultantPageBuilder->getConsultantLocationRepository();

		// find consultants, without locations,profiles
        $consultantObjects = $consultantRepository->findMultiple(array_keys($emailInfo), array('skipLocation'=>TRUE,'skipProfile'=>TRUE));
        // get default branches for each consultant
        $defaultBranchData =array();
        foreach($consultantObjects as $consId => $consObj){
            $defaultBranches = $consObj->getDefaultBranches();
            $defaultBranchData[$consId] = $defaultBranches[$consId];
        }
        // now get location required for email
        $defaultLocations =array();
        $defaultLocationsForMail =array();
        foreach($emailInfo as $consultantId=>$emailData)
        {
            $regionId = $emailData['regionId'];
            $defaultLocations[$consultantId]  = $defaultBranchData[$consultantId][$regionId]['locationId'];
            // also get each region's default office details for user email
            $defaultLocationsForMail[$consultantId] = $defaultBranchData[$consultantId];
        }            

        // find locations for all regions of each consultant(required in mail)
        $defaultLocationsIdsForMail= array();
        foreach($defaultLocationsForMail as $consulID=>$defaultLocationsForMailObject)
        {
            if(is_null($defaultLocationsIdsForMail[$consulID]))
            {
                $defaultLocationsIdsForMail[$consulID] = array();
            }
            foreach($defaultLocationsForMailObject as $locObject)
            {
                array_push($defaultLocationsIdsForMail[$consulID], $locObject['locationId']);
            }
        }
        // now load location objects
        $defaultLocationObjectsForMail = $consultantLocationRepository->findMultipleLocations($defaultLocationsIdsForMail);
        // separate out location objects for default locations
        $defaultLocationObjects = array();
        foreach($defaultLocations as $consID=>$defLocationId)
        {
            if(is_null($defaultLocationObjects[$consID])){ //$defLocationId
                $defaultLocationObjects[$consID] = array();
            }
            $defaultLocationObjects[$consID][$defLocationId] = $defaultLocationObjectsForMail[$consID][$defLocationId];
        }
        // now get sales person email for CC from region subscription
        $salesPersons = $this->consultantmodel->getSalesPersonData(array_keys($emailInfo), $regionId);
        // consolidate all information
        foreach($emailInfo  as $consultantId => $emailData)
        {
            $emailInfo[$consultantId]['clientName'] =$consultantObjects[$consultantId]->getName();
            $defaultLocationObject = reset($defaultLocationObjects[$consultantId]);
            $emailInfo[$consultantId]['clientEmail'] = $defaultLocationObject->getEmail();
            $emailInfo[$consultantId]['salesPersonEmail'] =$salesPersons[$consultantId]['salesPersonEmail'];
            if($defaultLocationObjectsForMail[$consultantId]){
                $defaultLocationObjectsForMail[$consultantId] = $defaultLocationObjectsForMail[$consultantId] + $defaultLocationObjects[$consultantId];
            }
            else{
                $defaultLocationObjectsForMail[$consultantId] = $defaultLocationObjects[$consultantId];
            }
            $emailInfo[$consultantId]['defaultLocationsForMail'] = $defaultLocationObjectsForMail[$consultantId];
        }
    }
    /*
     * function to get user details like exam score, desired course etc.
     */
    public function getLoggedInUserDetailsForMail($tempLmsId)
    {
        $userInfo = array();
        // get last course on which response was generated by user:
        if($tempLmsId){
            $userInfo = $this->consultantmodel->getListingSubjectedToResponseGeneration($tempLmsId);
        }
        // get logged in user preference data
        $userData = Modules::run('consultantEnquiry/ConsultantEnquiry/getLoggedInUserDetailsForMail');
        if($userData != 'false'){
            // time of start
            $userInfo['timeOfStart'] = $userData['timeOfStart'];
            // desired course
            $userInfo['fieldOfInterest'] = $userData['LDBDetails']['userData']['desiredCourseName'];
            $userInfo['residenceCity'] = $userData['residenceCity'];
            $userInfo['preferredDestination'] = $userData['preferredDestination'];
        }
        return $userInfo;
    }
    
    public function getConsultantDataFromPRINumber($priNumber){
	$consultantPostingLib = $this->CI->load->library('consultantPosting/ConsultantPostingLib');
	$data = $this->consultantmodel->getConsultantDataFromPRINumber($priNumber);
	$regionData = $consultantPostingLib->getRegionsMappingData();
	$resultData = array();
	$resultData['consultantId'] = $data['consultantId'];
	$resultData['regionId'] = $regionData[$data['cityId']]['regionId'];
	return $resultData;
    }
    /*
     * function to consume consultant's subscription credits 
     */
    public function consumeConsultantSubscriptionCredits($consultantId, $insertFlag)
    {
        if($insertFlag !== true) // consumption would happen only in case of insert
        {
            return false;
        }
        // check for credits
        $consultantPageLib = $this->CI->load->library('consultantProfile/ConsultantPageLib');
        $consultantSubscription = $consultantPageLib->validateSubscriptionData($consultantId);
        // hardcoded for now its a Id of saAdmin in CMS       
        $sumsUserId = 3284455;
        if($consultantSubscription === false)
        {
            return 'consumption_failed';
        }
        else{
            $this->CI->load->library('subscription_client');
            $objSumsProduct =  new Subscription_client();
            $data       = $objSumsProduct->consumeLDBCredits(CONSULTANT_CLIENT_APP_ID,
                                                             $consultantSubscription['subscriptionId'],
                                                             $consultantSubscription['costPerResponse'],
                                                             $consultantSubscription['clientId'],$sumsUserId);
            return true;
        }
    }
    
    /*
     * function used to populate region dropdown on consultant enquiry form
     */
    public function getDefaultBranchesForRegions($data)
    {   // get default branches & region ids..
        $this->CI->load->builder("ConsultantPageBuilder", "consultantProfile");
        $consultantPageBuilder   = new ConsultantPageBuilder();
		$consultantRepository = $consultantPageBuilder->getConsultantRepository();
		$consObj = $consultantRepository->find($data['consultantId']);
		if(is_object($consObj[$data['consultantId']])){
			$defaultBranchData = $consObj[$data['consultantId']]->getDefaultBranches();
		}
        // get region names
        $consultantPostingLib = $this->CI->load->library('consultantPosting/ConsultantPostingLib');
        $regions = $consultantPostingLib->getRegionsMappingData();
        $dropdownRegions = array();
        foreach($defaultBranchData[$data['consultantId']] as $k=>$v)
        {
                $dropdownRegions[$k] = array('consultantLocationId' => $v['locationId'],
                                             'regionId' => $k,
                                             'regionName' => $regions[$v['cityId']]['regionName']);
        }
        return $dropdownRegions;
    }
    /*
     * this function return the details required to populate consultant enquiry form for a registered user
     * @params: uservalidation value
     */
    public function getLoggedInUserDataForConsultantEnquiryForm($userStatus)
    {
        $cookieStr = explode('|',($userStatus[0]['cookiestr']));
        $userFormData = array('email' => $cookieStr[0],
                              'mobile'=> $userStatus[0]['mobile'],
                              'firstName'=> $userStatus[0]['firstname'],
                              'lastName'=> $userStatus[0]['lastname'],
                              );
        return $userFormData;
    }
    /*
     * function to get user email,mobile,fname,lname so that the enquiry form can be prefilled
     * @param : logged in user info returned from checkuservalidation()
     */
    public function getUserDataForEnquiryFormPopulation($validateUser)
    {
        // get last inserted id & its submit time from db if any enquiry made by the user exists in cookie
        $cookieVal = $this->getPreviousEnquiryFromCookie();
        
        // get user details that were saved with the last enquiry (userid,email,mob,fname,lname) with the help of cookie
        if($cookieVal != FALSE){
            
            $userFormDataFromCookie = $this->getUserDataForConsultantEnquiryForm($cookieVal);
            $oldUserId = $userFormDataFromCookie['userId'];
            // same tuser
            if($validateUser!= 'false' && $validateUser[0]['userid'] == $oldUserId)
            {
                // user form data generated from cookie
                $userFormData = $userFormDataFromCookie;
            }
            // user has logged in after previous enquiry
            else if($oldUserId == NULL && $validateUser!= 'false' && $validateUser[0]['userid'] >0){
                // get registered user's data & not from cookie
                $userFormData = $this->getLoggedInUserDataForConsultantEnquiryForm($validateUser);
            }
            // user logged out
            else if($oldUserId > 0 && $validateUser== 'false'){
                // get user form data generated from cookie
                $userFormData = array();
            }
            // last enquiry was by a logged in user & current one is by another logged in user
            else if($oldUserId > 0 && $validateUser != 'false' && $oldUserId != $validateUser[0]['userid'])
            {
                // get registered user's data & not from cookie
                $userFormData = $this->getLoggedInUserDataForConsultantEnquiryForm($validateUser);
            }
            // else pick whatever was available from the cookie
            else {
                $userFormData = $userFormDataFromCookie;
            }
        }
        // if nothing found in cookie, use currently logged in user (if any)
        else if($validateUser != 'false'){ //.. check if user is logged in get that logged in user's data
            $userFormData = $this->getLoggedInUserDataForConsultantEnquiryForm($validateUser);
        }
        return $userFormData;
    }
    
    public function saveVendorConsultantEnquiryData($data){
	return $this->consultantmodel->saveVendorConsultantEnquiryData($data);
    }
    
    public function trackConsultantSiteVisit($data){
	return $this->consultantmodel->trackConsultantSiteVisit($data);
    }
    
    public function getUnprocessedConsultantStudentRecordingUrls(){
	return $this->consultantmodel->getUnprocessedConsultantStudentRecordingUrls();
    }
    
    public function saveProcessedConsultantStudentRecordingUrls($savedUrls){
	$this->consultantmodel->saveProcessedConsultantStudentRecordingUrls($savedUrls);
    }
    
    public function sendConsultantMailToUsers($date){
        $dataChunksCount = 500;
        $lowerLimitForData = 0;
        while(TRUE){
            $resultSet = $this->consultantmodel->getConsultantMailerData($date,$lowerLimitForData,$dataChunksCount);
            
            $consultantIds = array();
            $courseIds = array();
            $universityIds = array();
            foreach($resultSet['data'] as $mailData){
                $consultantIds[] = $mailData['consultantId'];
                $courseIds[] = $mailData['courseId'];
            }
            $consultantIds = array_unique($consultantIds);
            $courseIds = array_unique($courseIds);
            if(empty($courseIds)){
                return;
            }
            $this->CI->load->builder('consultantProfile/ConsultantPageBuilder');
            $consultantPageBuilder = new ConsultantPageBuilder();
            $consultantRepository = $consultantPageBuilder->getConsultantRepository();
            $consultantData = $consultantRepository->findMultiple($consultantIds);
            
            $this->CI->load->builder('listing/ListingBuilder');
            $listingBuilder = new ListingBuilder();
            $abroadCourseRepo = $listingBuilder->getAbroadCourseRepository();
            $courseData = $abroadCourseRepo->findMultiple($courseIds);
            foreach($courseData as $abroadCourseObj){
                $universityIds[] = $abroadCourseObj->getUniversityId();
            }
            $universityIds = array_unique($universityIds);
            $universityRepo = $listingBuilder->getUniversityRepository();
            $universityData = $universityRepo->findMultiple($universityIds);
            
            //prepare cosultantMail related data
            $this->CI->load->library('consultantPosting/ConsultantPostingLib');
            $consultantPosting = new ConsultantPostingLib();
            $regionsData = $consultantPosting->getRegionsMappingData();
            $this->CI->load->model('user/usermodel');
            $usermodel = new usermodel;
            $consultantMailData = array();
            foreach($resultSet['data'] as $mailData){
                $user = $usermodel->getUserById($mailData['userId']);
                $userFlags = $user->getFlags();
                
                // skip users who have unsubscribed from shiksha mailers
                if($userFlags->getUnsubscribe() || !isset($courseData[$mailData['courseId']]) || !($courseData[$mailData['courseId']] instanceof AbroadCourse)){
                    continue;
                }
                $consultantMailDataTemp = array();
                $consultantMailDataTemp['userFirstName']        = $user->getFirstName();
                $consultantMailDataTemp['userEmail']            = $user->getEmail();
                $consultantMailDataTemp['fromEmail']            = SA_ADMIN_EMAIL;
                $consultantMailDataTemp['consultantMailerId']   = $mailData['consultantMailerId'];
                $consultantMailDataTemp['universityName']       = $universityData[$courseData[$mailData['courseId']]->getUniversityId()]->getName();
                $consultantMailDataTemp['consultantName']       = $consultantData[$mailData['consultantId']]->getName();
                $consultantMailDataTemp['consultantLogo']       = $consultantData[$mailData['consultantId']]->getLogo();
                $consultantMailDataTemp['consultantUrl']        = $consultantData[$mailData['consultantId']]->getURL();
                $consultantMailDataTemp['consultantDescription']= $consultantData[$mailData['consultantId']]->getDescription();
                $consultantLocations = $consultantData[$mailData['consultantId']]->getConsultantLocations();
                foreach($consultantLocations as $consultantLocationObj){
                    if($regionsData[$user->getCity()]['regionId'] == $regionsData[$consultantLocationObj->getCityId()]['regionId'] && $consultantLocationObj->getDisplayPRINumber() != ''){
                        $consultantMailDataTemp['consultantPRINumber']  = $consultantLocationObj->getDisplayPRINumber();
                        $consultantMailDataTemp['consultantCityName']   = $consultantLocationObj->getCityName();
                    }
                }
                $consultantMailDataTemp['mailSubject'] = ucfirst($consultantMailDataTemp['userFirstName']).', Get admission help on '.$consultantMailDataTemp['universityName'].'.';
                $consultantMailData[] = $consultantMailDataTemp;
            }
            unset($consultantData);
            unset($courseData);
            unset($universityData);
            
            $mailerSuccessfullySent = array();
            // prepare email content and send email to users
            foreach($consultantMailData as &$consultantMailDataTemp){
                $consultantMailDataTemp['CI'] = $this->CI;
                $consultantMailDataTemp['emailContent'] = $this->CI->load->view('consultantMailer',$consultantMailDataTemp,TRUE);
                $response = Modules::run('systemMailer/SystemMailer/sendAbroadConsultantMailToUsers',$consultantMailDataTemp);
                if($response > 1){
                    $mailerSuccessfullySent[] = $consultantMailDataTemp['consultantMailerId'];
                }
            }
            //_p($mailerSuccessfullySent);
            $response = $this->CI->consultantmodel->updateConsultantMailerScheduleForSuccessMailers($mailerSuccessfullySent);
            
            $lowerLimitForData += $dataChunksCount;
            if($lowerLimitForData >= $resultSet['totalResultCount']){
                break;
            }
        }
        return;
    }
    
    public function prepareConsultantMailersForUsers($consultantIds=array(),$universityIds=array(),$regionIds=array(),$date='0000-00-00',$last2DayChecks='N'){
        $parsedDate = date_parse($date);
        if(!($parsedDate['error_count'] == 0 && checkdate($parsedDate['month'], $parsedDate['day'], $parsedDate['year']))){
            $dayDurationForMail = -6;
            $date = date("Y-m-d", strtotime("+".$dayDurationForMail."month"));
        }
        $consultantIds = array_filter($consultantIds, function ($a){
            return is_int((int)$a);
        });
        $universityIds = array_filter($universityIds, function ($a){
            return is_int((int)$a);
        });
        $regionIds = array_filter($regionIds, function ($a){
            return is_int((int)$a);
        });
        $excludedCourses = array();
        if(count($consultantIds) == 0){
            $this->CI->load->model('consultantPosting/consultantpostingmodel');
            $consultantPostingModel = new consultantpostingmodel();
            $consultantIds = $consultantPostingModel->getConsultantList();
            $consultantIds = array_map(function($a){
                return $a['consultantId'];
            }, $consultantIds);
        }
        $this->CI->load->builder('consultantProfile/ConsultantPageBuilder');
        $consultantPageBuilder = new ConsultantPageBuilder();
        $consultantRepository = $consultantPageBuilder->getConsultantRepository();
        $consultantData = $consultantRepository->findMultiple($consultantIds);
        $this->CI->load->library('consultantProfile/ConsultantPageLib');
        $consultantPageLib = new ConsultantPageLib();
        $this->CI->load->model('listing/abroadlistingmodel');
        $abroadListingModel = new abroadlistingmodel();
        foreach($consultantData as $consultantObject){
            $subscriptionData = $consultantPageLib->validateSubscriptionData($consultantObject->getId());
            if($subscriptionData === FALSE){
                continue;
            }
            $universityMapped = $consultantObject->getUniversitiesMapped();
            $excludedCourses[$consultantObject->getId()] = array();
            foreach($universityMapped as $mappingData){
                if((count($universityIds) > 0 && in_array($mappingData['universityId'], $universityIds) || count($universityIds) == 0)){
                    $universityIdsToCheck[] = $mappingData['universityId'];
                    $excludedCoursesForUniversity = explode(',', $mappingData['excludedCourses']);
                    if(count($excludedCoursesForUniversity) > 0){
                        $excludedCourses[$consultantObject->getId()] = array_merge($excludedCourses[$consultantObject->getId()],$excludedCoursesForUniversity);
                    }
                }
            }
        }
        if(empty($universityIdsToCheck)){
            return;
        }
        $universityIdsToCheck = array_unique($universityIdsToCheck);
        $consultantUniversityRegionMapping = array();
        foreach($universityIdsToCheck as $univId){
            $response = $abroadListingModel->getActiveConsultantsForUniversity($univId);
            foreach($response as $responseData){
                $consultantUniversityRegionMapping[$responseData['consultantId']][$responseData['universityId']][] = $responseData['regionId'];
            }
        }
        $this->CI->load->model('listing/abroadcoursefindermodel');
        $abroadCourseFinderModel = new AbroadCourseFinderModel();
        $courseOfUniversities = $abroadCourseFinderModel->getCoursesOfferedByMultipleUniversities($universityIdsToCheck);
        $universityIdToCourseMapping = array();
        foreach($courseOfUniversities['courses'] as $dataArray){
            $universityIdToCourseMapping[$dataArray['universityID']][] = $dataArray['courseID'];
        }
        $coursesToCheck = array_unique($courseOfUniversities['course_ids']);
        $this->CI->load->model('consultantProfile/consultantmodel');
        $consultantModel = new consultantmodel();
        $lowerLimitForData = 0;
        $dataChunksCount = 500;
        $universityToUserArray = array();
        while(TRUE){
            $resultSet = $consultantModel->getPastResponseData($date,$last2DayChecks, $coursesToCheck, $lowerLimitForData, $dataChunksCount);
            foreach($resultSet['data'] as $dataArr){
                foreach($courseOfUniversities['courses'] as $courseUniversityMapping){
                    if($dataArr['courseId'] ==  $courseUniversityMapping['courseID']){
                        $universityToUserArray[$courseUniversityMapping['universityID']][$dataArr['userId']] = array(   'tempLmsId'     => $dataArr['tempLMSId'],
                                                                                                                        'userId'        => $dataArr['userId'],
                                                                                                                        'courseId'      => $dataArr['courseId'],
                                                                                                                        'submit_date'   => $dataArr['submit_date']);
                        break;
                    }
                }
            }
            
            $lowerLimitForData += $dataChunksCount;
            if($lowerLimitForData >= $resultSet['totalResultCount']){
                break;
            }
        }
        $this->CI->load->model('user/usermodel');
        $usermodel = new usermodel;
        $this->CI->load->library('consultantPosting/ConsultantPostingLib');
        $consultantPostingLib = new ConsultantPostingLib();
        $regionMappingData = $consultantPostingLib->getRegionsMappingData();
        
        $usersDataValidForMailer = array();
        $dateAdded = date('Y-m-d H:i:s');
        $dateToBeProcessedAtCombination = array(date("Y-m-d", strtotime("+ 1 days")),date("Y-m-d", strtotime("+ 3 days")),date("Y-m-d", strtotime("+ 5 days")));
        $dateCheck = strtotime('2015-04-16 00:00:00');
        $userNotFoundMessageBody    = 'Hi,<br/>User IDs Not Found are : <br/>';
        foreach($consultantData as $consultantObject){
            $universityMapped = $consultantObject->getUniversitiesMapped();
            foreach($universityMapped as $mappingData){
                $excludedCourses = explode(',', $mappingData['excludedCourses']);
                foreach($universityToUserArray[$mappingData['universityId']] as $dataArray){
                    if(!in_array($dataArray['courseId'], $excludedCourses)){
                        $user = $usermodel->getUserById($dataArray['userId']);
                        if(!($user instanceof User)){
                            $userNotFoundMessageBody .= $dataArray['userId'].'<br/>';
                            continue;
                        }
                        $userPref = $user->getPreference();
                        $responseDate = strtotime($dataArray['submit_date']);
                        if((count($regionIds) > 0 && !in_array($regionMappingData[$user->getCity()]['regionId'], $regionIds)) || !(in_array($regionMappingData[$user->getCity()]['regionId'],$consultantUniversityRegionMapping[$consultantObject->getId()][$mappingData['universityId']])) || ($userPref->getContactByConsultant() == 'no' && $responseDate >= $dateCheck)){
                            continue;
                        }
                        $usersDataValidForMailerKey = $user->getId().'-'.$mappingData['universityId'];
                        $dateToBeProcessedAt = $dateToBeProcessedAtCombination[count($usersDataValidForMailer[$usersDataValidForMailerKey])];
                        $usersDataValidForMailer[$usersDataValidForMailerKey][] = array(  'tempLmsId'             => $dataArray['tempLmsId'],
                                                                                          'consultantId'          => $consultantObject->getId(),
                                                                                          'dateAdded'             => $dateAdded,
                                                                                          'emailToBeProcessedAt'  => $dateToBeProcessedAt
                                                                                        );

                    }
                }
                
            }
        }
        // unset data array to release memory  :: it may happen that memory exhaust because we have to deal with last 6 months data
        unset($consultantData);
        unset($courseOfUniversities);
        unset($regionMappingData);
        unset($universityToUserArray);
        
        if(substr_count($userNotFoundMessageBody, '<br/>') > 2){
            $commonStudyAbroadLib   = $this->CI->load->library('common/studyAbroadCommonLib');
            $userNotFoundMessageSubject = 'Consultant Mail To User Cron : Users not found';
            $userNotFoundMessageBody .= '<br/><br/>Regards,<br/>SA Team';
            $commonStudyAbroadLib->selfMailer($userNotFoundMessageSubject,$userNotFoundMessageBody);
        }
        
        // prepare mailer data to be inserted in DB
        $consultantMailerScheduleData = array();
        foreach($usersDataValidForMailer as $userUnivCombination=>$mailerDataArray){
            foreach($mailerDataArray as $key=>$value){
                $consultantMailerScheduleData[] = array('tempLmsId'             => $value['tempLmsId'],
                                                        'consultantId'          => $value['consultantId'],
                                                        'dateAdded'             => $value['dateAdded'],
                                                        'emailToBeProcessedAt'  => $value['emailToBeProcessedAt'],
                                                        'emailProcessedAt'      => NULL);
            }
        }
        
        // insert mail in DB
        $consultantModel->scheduleConsultantMailer($consultantMailerScheduleData);

            
        return TRUE;
    }
}
?>
