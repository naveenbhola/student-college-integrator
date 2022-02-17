<?php

class FBLeadJobs extends MX_Controller{
	
	public function processExceptionLeads($value=''){
		$this->validateCron();
		$this->load->library('marketing/fbLead/FbExceptionLeadLib');
		$fb_exception_lead_lib = new FbExceptionLeadLib();

		$fbLeadLib = $this->load->library("marketing/fbLead/fbLeadLib");

		$resolved_exception = $fb_exception_lead_lib->getResolvedException();

		foreach ($resolved_exception as $exception) {
			$key = $exception['type'];
			$old_value = $exception['old_value'];
			$corrected_value = $exception['corrected_value'];

			$leads = $fb_exception_lead_lib->getLeadIdsWithExceptionValue($key, $old_value);
			
			$lead_ids  = array();
			$primary_ids  = array();

			foreach ($leads as $lead_data) {
				$primary_ids[] = $lead_data['id'];
				$lead_ids[] = $lead_data['lead_id'];
			}

			if(count($primary_ids)<1){
				continue;
			}
						
			$lead_ids = array_unique($lead_ids);

			$fb_exception_lead_lib->updateCorrectLeadData($primary_ids, $corrected_value);

			$update_lead_data_status = $fb_exception_lead_lib->getLeadsWithNoException($lead_ids);

			if (count($update_lead_data_status)<1) {
				continue;
			}
			
			$fb_exception_lead_lib->updateLeadDataStatus($update_lead_data_status);

		}
		
		$leads_to_register = $fb_exception_lead_lib->getLeadsToRegister();
		$fbLeadLib->registerBulkLeads($leads_to_register);
	}


	function FBUserResponseCreation(){
        //$this->CI = & get_instance();

         $FBData = array(
             'email'        => '12newfcoslseo23new@shiksha.com',
             'first_name'   => 'Acfdd',
             'last_name'    => 'dddd',
             'phone_number' => '+919898989898',
             'city'         => 'Kolkata',
             'course_name'  => 'course_016 in gera_inst_26102016',
             'fb_form_id'   => 123,
             'lead_id'      => 1212121212,
             'course_location'      => 'Mumbai',
             'work_ex'      => "No experience"
             );


         $FBUserValidationLib = $this->load->library('marketing/fbLead/fbLeadLib');
         $result = $FBUserValidationLib->createResponseForFBLead($FBData);

     }

     public function generatePasswordLink($requested_page, $email){
		$appId=1;

		$this->load->library('user/Register_client');
		$register_client = new Register_client();

    	$response = $register_client->getUserIdForEmail($appId, $email,'yes');
		$requested_page = SHIKSHA_HOME."/shiksha/index/";        
		$usergroup = 'fbuser';
 
		$id = base64_encode($response[0]['id'].'||'.$requested_page.'||'.$email.'||'.$usergroup);
      
      	$resetlink = SHIKSHA_HOME . '/user/Userregistration/ForgotpasswordNew/'.$id;
      

        $resetlink = '<a href='.$resetlink.'>Create your password now</a>';

        return $resetlink;
	}

	public function reVerifyLeads($noOfDays = 1){
		$this->validateCron();
		//get all active fb form Id
		$fbLeadLib = $this->load->library("marketing/fbLead/fbLeadLib");
		$fbFormIds = $fbLeadLib->getPreviousDayActiveFormIds($noOfDays);

		if(empty($fbFormIds)){
			die('No active form ids');
		}

		//get leadId present in our database 
		$shikshaRegisteredLeadData = $fbLeadLib->getPreviousDayLeadIdsByFormId($fbFormIds,$noOfDays);				
		foreach ($fbFormIds as $key => $formId) {			
			$this->_verifyLeadsByCriteria($formId,$noOfDays,$shikshaRegisteredLeadData[$formId]);
		}
	}

	public function reVerifyLeadsByFormId($fbFormId,$noOfDays = 1){
		if(empty($fbFormId)){
			die('No active form ids');
		}
		$fbLeadLib = $this->load->library("marketing/fbLead/fbLeadLib");

		//get leadId present in our database 
		$shikshaRegisteredLeadData = $fbLeadLib->getPreviousDayLeadIdsByFormId(array($fbFormId),$noOfDays);	
		$this->_verifyLeadsByCriteria($fbFormId,$noOfDays,$shikshaRegisteredLeadData[$fbFormId]);
	}

	private function _verifyLeadsByCriteria($formId,$noOfDays,$shikshaRegisteredLeadData){

		$fbLeadLib = $this->load->library("marketing/fbLead/fbLeadLib");
		
		//fetch leads data from facebook server by facebook form id
		$data                          = $this->getBulkLeadsByFormId($formId,$noOfDays);
		$fbLeadData                    = array();		
		foreach($data['data'] as $key => $leadBucket) {
			$fbLeadData[$leadBucket['id']] = $leadBucket;			
		}
		unset($data);

		$fbRegisteredLeadIds = array_keys($fbLeadData);
		
		// _p($fbRegisteredLeadIds);
		// _p($shikshaRegisteredLeadData);
		// die;
		
		
		// list of lead ids which don't have facebook lead data
		$emptyLeadDataList         = $shikshaRegisteredLeadData['emptyLeadDataList'];		

		// list of lead ids entered through a given form ids
		$shikshaRegisteredLeadIds  = $shikshaRegisteredLeadData['leadIds'];

		unset($shikshaRegisteredLeadData);	

		foreach ($emptyLeadDataList as $key => $value) {
			if(in_array($value['lead_id'], $fbRegisteredLeadIds)){
				$pushData = array();
				$pushData['lead_id']            = $value['lead_id'];
				$pushData['fb_form_id']         = $formId;
				$pushData['fb_lead_created_on'] = $value['fb_created_on'];
				$fbLeadLib->createFbLead($pushData);										
			}
		}
		
		// comparing process
        $leadIdsDiff  = array();
        foreach($fbRegisteredLeadIds as $value){
            if(!in_array($value,$shikshaRegisteredLeadIds)){
                $leadIdsDiff[] = $value;
            }
        }   

        // _p($leadIdsDiff);die;

		foreach ($leadIdsDiff as $key => $value) {			
			$fbCreatedTime  = $fbLeadData[$value]['created_time'];
			$dateSplit      = explode('T', $fbCreatedTime);
			$timeSplit      = explode('+', $dateSplit[1]);
			
			$minutes_to_add = 330;
			$time           = new DateTime($dateSplit[0]." ".$timeSplit[0]);
			$time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
			$createdTime    = $time->format('Y-m-d H:i:s');

			$input['entry'][0]['changes'][0]['value']['leadgen_id']   = $value;
			$input['entry'][0]['changes'][0]['value']['created_time'] = $createdTime;
			$input['entry'][0]['changes'][0]['value']['form_id']      = $formId;
			$fbLeadLib->create($input);	
		}
	}

	public function getBulkLeadsByFormId($formId,$noOfDays,$noFilter = false){
		if($noOfDays == 0){			
			$greaterThanDate = strtotime(date("Y/m/d G:i:s",mktime(0,0,0)));			
			$lessThanDate    = strtotime(date("Y/m/d G:i:s"));
		}else{
	     	$greaterThanDate = strtotime("-$noOfDays day", strtotime(date("Y/m/d G:i:s", mktime(0,0,0))));
		    $lessThanDate    = strtotime("-0 day", strtotime(date("Y/m/d G:i:s", mktime(0,0,0)-1)));     		
		}		

		$filterData = [array(
			"field"    => "time_created",
			"operator" => "GREATER_THAN",
			"value"    => $greaterThanDate
		),array(
			"field"    => "time_created",
			"operator" => "LESS_THAN",
			"value"    => $lessThanDate
		)];
		$filterData = json_encode($filterData);

		if($noFilter){
			$url             = 'https://graph.facebook.com/v2.11/'.$formId.'/leads?access_token='.FACEBOOK_ACCESS_TOKEN;
		}else{
			$url             = 'https://graph.facebook.com/v2.11/'.$formId.'/leads?filtering='.$filterData.'&access_token='.FACEBOOK_ACCESS_TOKEN;			
		}

		$FBLeadCommonLib = $this->load->library('marketing/fbLead/FBLeadCommon');
		$data            = $FBLeadCommonLib->sendCurlRequest($url);
		return $data;
	}

	public function debugFormId($fbFormId,$noOfDays = 1){
		if(empty($fbFormId)){
			die('No active form ids');
		}

		$data = $this->getBulkLeadsByFormId($fbFormId,$noOfDays,true);
		_p($data);
		die;
	}

	public function debugLeadId($leadgenId){
		if(empty($leadgenId)){
			die('No active form ids');
		}
		$FBLeadCommonLib = $this->load->library('marketing/fbLead/FBLeadCommon');
		$url = 'https://graph.facebook.com/v2.11/'.$leadgenId.'?access_token='.FACEBOOK_ACCESS_TOKEN;
		$data = $FBLeadCommonLib->sendCurlRequest($url);		
		_p($data);
		die;
	}


	
}
