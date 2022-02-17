<?php
/*
 * controller class for consultant generation & related fuctionalities
 */ 
class consultantEnquiry extends MX_Controller
{
    // construct
    function __construct()
    {
        parent::__construct();
        $this->userStatus           = $this->checkUserValidation();
        $this->consultantEnquiryLib = $this->load->library('ConsultantEnquiryLib');
    }
    
    /*
     * controller function to get consultant enquiry form
     */
    public function getConsultantEnquiryForm()
    {
        $data = array();
        $dropdownRegions = array();
        $data['source'          ] = $this->input->post('source');
        $data['regionId'        ] = $this->input->post('regionId');
        $consultant               = $this->input->post('consultant');
        $data['consultantId'    ] = $consultant['consultantId'];
        $data['consultantName'  ] = $consultant['consultantName'];
        $data['logoUrl'         ] = $consultant['logoUrl'];
        $data['consultantUrl'   ] = $consultant['consultantUrl'];
        $data['universityName'  ] = $consultant['universityName'];
        
        $consultantPageLib = $this->load->library('consultantProfile/ConsultantPageLib');
        $allRegionDefaultOfficeData = $data;
        unset($allRegionDefaultOfficeData['regionId']);
        $dropdownRegions = $this->consultantEnquiryLib->getDefaultBranchesForRegions($allRegionDefaultOfficeData);
        
        // get last inserted id & its submit time from db if any enquiry made by the user exists in cookie
        $cookieVal = $this->consultantEnquiryLib->getPreviousEnquiryFromCookie();
        // .. also there exists a cookie nregv that will tell if an email|mobile combination has been recently verified via OTP
        $cookieValOTP = isset($_COOKIE['nregv']) && $_COOKIE['nregv'] != "" ? $_COOKIE['nregv']: '';
        // get user details 
        $userFormData = $this->consultantEnquiryLib->getUserDataForEnquiryFormPopulation($this->userStatus);
        // get location details for these default branches
        $consultantLocationIds = array_map(function($a){return $a['consultantLocationId'];},$dropdownRegions);
        $consultantLocations = $consultantPageLib->getConsultantLocationsById($data['consultantId'],$consultantLocationIds);
        // if region id is not available (consultant page)..
        if(!$data['regionId']){
            // get it from ip location
            $consultantPageLib = $this->load->library('consultantProfile/ConsultantPageLib');
            $regionByIP = $consultantPageLib->getRegionBasedOnIP();
            $data['regionId'] = $regionByIP['regionId'];
        }
        $dataArr = array('dropdownRegions'      => $dropdownRegions,
                         'consultantName'       => $data['consultantName'],
                         'consultantId'         => $data['consultantId'],
                         'logoUrl'              => $data['logoUrl'],
                         'consultantUrl'        => $data['consultantUrl'],
                         'regionId'             => $data['regionId'],
                         'consultantLocations'  => $consultantLocations[$data['consultantId']],
                         'source'               => $data['source'],
                         'userFormData'         => $userFormData,
                         'cookieValOTP'         => $cookieValOTP,
                         'universityName'       => $data['universityName'],
                         'consultantTrackingPageKeyId' => $this->input->post('consultantTrackingPageKeyId'));
        
        echo $this->load->view('consultantEnquiry/consultantEnquiryForm',$dataArr, true);
    }
    
    /*
     * function to get consultant enquiry data from post & save consultant enquiry into the database.
     * (works as a landing controller for enquiry submitting ajax)
     */
    public function saveConsultantEnquiry()
    {
        $data = array();
        // get user data to check for user id
        if($this->userStatus != 'false')
        {
            $data['userid'] = $this->userStatus[0]['userid'];
        }
        else{
            $data['userid'] = NULL;
        }
        // get post data to be inserted/updated as consultant enquiry
        $data['source'      ] = $this->input->post('source'     );
        $data['email'       ] = $this->input->post('email'      );
        $data['mobile'      ] = $this->input->post('mobile'     );
        $data['firstName'   ] = $this->input->post('firstName'  );
        $data['lastName'    ] = $this->input->post('lastName'   );
        $data['message'     ] = $this->input->post('message'    );
        $data['regionId'    ] = $this->input->post('region'     );
        $data['tempLmsId'   ] = $this->input->post('tempLmsId'  );
        $data['consultantId'] = array($this->input->post('consultant' )); // because we accept multiple consultants
        $data['university'  ] = json_decode(base64_decode($this->input->post('university')));
        $data['tracking_keyid'] = $this->input->post('consultantTrackingPageKeyId');
        $lastInsertedId = $this->consultantEnquiryLib->saveConsultantEnquiry($data);
        echo $lastInsertedId;
    }
    /*
     * function to show thank you message after saving consultant enquiry
     */
    public function getConsultantEnquiryThankYouLayer()
    {
        echo $this->load->view('consultantEnquiryThankYouLayer');
    }
    /*
     * function to get existing user data like preferred destination, desired course etc.
     */
    public function getLoggedInUserDetailsForMail()
    {
        if($this->userStatus == 'false')
        {
            return false;
        }
        $responseAbroadLib = $this->load->library('responseAbroad/ResponseAbroadLib');
        $userData = $responseAbroadLib->getUserStartTimePrefWithExamsTaken($this->userStatus);
        //$userData['LDBDetails']['userData']['desiredCourseName'];
        $this->load->builder("LocationBuilder", "location");
        $locationBuilder 	= new LocationBuilder();
        $locationRepo  		= $locationBuilder->getLocationRepository();
        if($userData['userCity']>0){
                $city = $locationRepo->findCity($userData['userCity']);
                $userData['residenceCity'] = $city->getName();
        }
        if(count($userData['userPreferredDestinations'])>0){
                $country = $locationRepo->findCountry(reset($userData['userPreferredDestinations']));
                $userData['preferredDestination'] = $country->getName();
        }
        return $userData;
    }
    
    public function vendorConsultantEnquiryPush(){
        $data = $this->_fetchVendorData();// Safely fetch the data from the vendor
        $consultantMinData = $this->_getConsultantDataFromPRINumber($data['calledNumber']);
        if(empty($consultantMinData['regionId']) || !is_numeric($consultantMinData['regionId'])){
            $this->_alertCurrentStatusOfEnquiryPush($data,$consultantMinData);
            header("Content-type: text/xml; charset=utf-8");
            echo "<response><status>Error : The specified PRI Number is not mapped to a shiksha counsellor.</status></response>";
            return false;
        }
        $this->_alertCurrentStatusOfEnquiryPush($data,$consultantMinData);
        $enquiryData = array();
        $enquiryData['source'      ] = 'vendorInfo';
        $enquiryData['mobile'      ] = $data['callerNumber'];
        $enquiryData['regionId'    ] = $consultantMinData['regionId'];
        $enquiryData['consultantId'] = array($consultantMinData['consultantId']);
        $enquiryData['submitTime'] = $data['submitTime'];
        $enquiryData['tracking_keyid'] = 392;

        // First run the normal enquiry stuff
        $lastInsertedId = $this->consultantEnquiryLib->saveConsultantEnquiry($enquiryData,true);
        
        $data['consultantEnquiryId'] = $lastInsertedId;
        // Now we need to download the recording from the vendor's server.
        //$data['shikshaRecordingUrl'] = 'www.shiksha.com/somethingwillcomeherewhenitcan';
        //Now to send the required SMS
        $this->sendVendorResponseSMS($data);
        // Store the data in our own database now
        $vendorStoreId = $this->consultantEnquiryLib->saveVendorConsultantEnquiryData($data);
        
        if(is_numeric($vendorStoreId) && ($vendorStoreId > 0 || $vendorStoreId == -1)){
            header("Content-type: text/xml; charset=utf-8");
            echo "<response><status>Data Successfully Updated</status></response>";
        }else{
            header("Content-type: text/xml; charset=utf-8");
            echo "<response><status>An Error Occurred</status></response>";
        }
    }
    
    private function _fetchVendorData(){
        $data['submitTime'] = trim($this->input->get('date')).' '.trim($this->input->get('time'));
        $data['clientName'] = trim($this->input->get('client_name'));
        $data['calledNumber'] = trim($this->input->get('called_number'));
        $data['callerNumber'] = trim($this->input->get('caller_number'));
        $data['callerCircle'] = trim($this->input->get('caller_circle'));
        $data['isWorking'] = trim($this->input->get('is_working'));
        $data['isHoliday']= trim($this->input->get('is_holiday'));
        $data['isBlacklisted'] = trim($this->input->get('is_blacklisted'));
        $data['totalCallDuration'] = trim($this->input->get('total_call_duration'));
        $data['conversationDuration'] = trim($this->input->get('conversation_duration'));
        $data['agentList'] = trim($this->input->get('agent_list'));
        $data['agentConnected'] = trim($this->input->get('agent_connected'));
        $data['agentHangupCause'] = trim($this->input->get('agent_hangup_cause'));
        $data['disconnectedBy'] = trim($this->input->get('disconnected_by'));
        $data['vendorRecordingUrl'] = trim($this->input->get('recording_url'));
        $data['callUUID'] = trim($this->input->get('call_uuid'));
        return $data;
    }
    
    private function _getConsultantDataFromPRINumber($priNumber){
        $data = $this->consultantEnquiryLib->getConsultantDataFromPRINumber($priNumber);
        return $data;
    }
    
    
    /*
     * function to get the layer for otp verification
     */
    public function getConsultantUserVerificationLayer()
    {
        $data = array();
        $data['mobile'] = $this->input->post('mobile');
        echo $this->load->view('consultantUserVerificationLayer',$data);
    }
    
    private function sendVendorResponseSMS($data){
        $template = '';
        if($data['conversationDuration'] != "0:00:00"){
            $studentMessage = 'You spoke to '.$data['clientName'].' in '.$data['callerCircle'].' at +'.$data['calledNumber'].'. Save this number for future use. Good luck, studyabroad.shiksha.com';
            $consultantMessage = 'You spoke with student at +'.$data['callerNumber'].' at '.$data['submitTime'].'. This call was forwarded by studyabroad.shiksha.com';
            $studentNumber = $data['callerNumber'];
            $consultantNumber = $data['agentConnected'];
        }else{
            $studentMessage = 'Your call to '.$data['clientName'].' at +'.$data['calledNumber'].' did not get connected. We have shared your number so that they can get back to you. studyabroad.shiksha.com';
            $consultantMessage = 'You have a missed call from a student at +'.$data['callerNumber'].' at '.$data['submitTime'].'. This call was forwarded by studyabroad.shiksha.com';
            $studentNumber = $data['callerNumber'];
            $consultantNumber = reset(explode(',',$data['agentList']));
        }
        $alertsClient = $this->load->library('alerts_client');
        $alertsClient->addSmsQueueRecord(1,preg_replace('/^91/','',$studentNumber),$studentMessage,3284455);    // 3284455 is SA Admin id and id is required to send sms
        $alertsClient->addSmsQueueRecord(1,preg_replace('/^91/','',$consultantNumber),$consultantMessage,3284455);
    }
    
    
    public function trackConsultantSiteVisit($consultantId){
        $userData = $this->userStatus;
        if($userData == 'false')
        {
            $userId = -1;
        }else{
            $userId = $userData[0]['userid'];
        }
        session_start();
        $sessionId = session_id();
        $timestamp = date('Y-m-d H:i:s');
        $data = array('userId'=>$userId,'timestamp'=>$timestamp,'sessionId'=>$sessionId,'consultantId'=>$consultantId);
        $id = $this->consultantEnquiryLib->trackConsultantSiteVisit($data);
        return $id;
    }
    
    public function getConsultantStudentRecordings(){
        $fileUploadLib = $this->load->library("common/fileUploadLib");
        $urls = $this->consultantEnquiryLib->getUnprocessedConsultantStudentRecordingUrls();
        $savedUrls = array();
        foreach($urls as $enquiryId=>$url){
            $file = fopen($url, 'rb');
            $fileName = md5($enquiryId).".mp3";
            $newf = "/tmp/".$fileName;
            if($file && $newf){
                file_put_contents($newf,$file);
                fclose($newf);
                $response = $fileUploadLib->uploadFileViaCurl($newf,SHIKSHA_HOME."/consultantEnquiry/ConsultantCrons/uploadRecording");
                if($response['status'] == "success"){
                    $savedUrls[$enquiryId] = $response['url'];
                }
            }
        }
        $this->consultantEnquiryLib->saveProcessedConsultantStudentRecordingUrls($savedUrls);
    }
    
    private function _alertCurrentStatusOfEnquiryPush($data,$consultantMinData){
        $shikshaInternalNumbers = array("911130052727","911130052737","911140469620","911140469621"); //We don't want to send any email in this case.
        if(in_array($data['calledNumber'], $shikshaInternalNumbers)){
            return true;
        }
        $fData = "";
        foreach($data as $k=>$v){
            $fData.=$k." : ".$v."<br/>";
        }
        $content = "<html><body> We recieved a call with the following params.<br/>".$fData;
        $content.= "<br/> And the consultant Min Data is : <br/>".json_encode($consultantMinData);
        $content.= "<br/></body></html>";
        $alertsClient = $this->load->library('alerts_client');
        $alertsClient->externalQueueAdd("12",SA_ADMIN_EMAIL,
                                                        'satech@shiksha.com',
                                                        "Report: Consultant Enquiry Call Made",
                                                        $content,
                                                        "html",'','n',array(),
                                                        "",
                                                        "");
    }
    
}
