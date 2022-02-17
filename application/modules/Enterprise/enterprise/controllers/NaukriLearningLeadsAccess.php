<?php

class NaukriLearningLeadsAccess extends MX_Controller{
	
	private function _init() {
		$this->NLAccessLib = $this->load->library('enterprise/NaukriLeadsLib');

		$this->userStatus = $this->checkUserValidation();
		if(($this->userStatus == "false") || ($this->userStatus == "")) {
		 	header('location:/enterprise/Enterprise/loginEnterprise');
             exit();
		 }
		
		if ($this->userStatus[0]['usergroup'] != 'cms') {
            header("location:/enterprise/Enterprise/unauthorizedEnt");
        }
		
		

	}

	private function getHeaderData() {

		$this->load->library("enterprise_client");
		$entObj               = new Enterprise_client();
		$data['prodId']       = 1020;
		$data['validateuser'] = $this->userStatus;
		$data['headerTabs']   = $entObj->getHeaderTabs(1,$this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        return $data;

	}

	function manageManualSubscriptionAccess() {
		
		$this->_init();
		$data               = array();
		$data               = $this->getHeaderData();
		$data['statesList'] = $this->NLAccessLib->getStates();
		$this->load->view('NaukriLearningLeadsAccess/subscriptionAccessPanel',$data);

	}

	function saveNaukriLeadsSubscription() {

		$this->_init();
		$this->load->helper('security');
    	$data = xss_clean($_POST);
    	
    	$this->ERAccessLib = $this->load->library('enterprise/examResponseAccessLib');
    	$data = $this->ERAccessLib->validateNLSubscriptionFields($data);

    	if($data == false){
    		echo json_encode("FAIL");
    	} else {
    		// save data
    		$data['createdBy'] = $this->userStatus[0]['userid'];
    		$response = $this->NLAccessLib->saveNLSubscriptionData($data);
    		echo json_encode($response);
    	}

	}

	function isNewSubscription() {

		$this->_init();
		$clientId = $this->input->post('clientId',true);
		if(!empty($clientId) && $clientId > 0){
			echo $this->NLAccessLib->isNewSubscription($clientId);
		} else {
			echo '0';
		}

	}

	function subscription($type =""){
		$this->load->helper('security');
    	$type = xss_clean($type);
		if(!in_array($type, array("active"))){
			return false;
		}

		$data = array();
		$this->_init();
		$data = $this->getHeaderData();

		$data['type'] = $type;
		$data['subscriptionDetails'] = $this->NLAccessLib->getSubscriptionData($type);
		$this->load->view('NaukriLearningLeadsAccess/subscriptionDetails',$data);
		//_p($data['campaignData']);die;
	}

	function deleteSubscription(){
		$this->_init();
		$subscriptionId = $this->input->post('subscriptionId',true);
		if(empty($subscriptionId) || $subscriptionId <=0){
			echo json_encode("FAIL");
		}else{
			$response = $this->NLAccessLib->updateSubscriptionStatus($subscriptionId,"deleted");
			echo json_encode($response);
		}
	}

	public function getUserAllocationData($leads){
		//$this->_init();
		$userIds = array();
		foreach ($leads as $key => $lead) {
			$userIds[$lead['userId']] = $lead['userId'];
		}
		
		$userAllocationMapping = $this->NLAccessLib->getUserAllocationData($userIds);
		return $userAllocationMapping;
	}


	public function allocateIndividualLeads($last_processed_id){
		//$this->_init();
		$leads = $this->naukri_model->getUnallocatedLeads($last_processed_id);

		try {

			if(count($leads) < 0){
				return;
				//$userAllocationMapping = $this->getUserAllocationData($leads);
			}

			foreach ($leads as $lead) {
				
				$recent_processed_id = $lead['id'];
				
				$matched_subscriptions = $this->NLAccessLib->getMatchedSubscriptions(array($lead['state_id']),$lead['creation_time']);
				
				if(count($matched_subscriptions)<1){
					continue;
				}
				
				$matched_subscriptions = $this->NLAccessLib->storeIndividualMatchedSubscription($lead,$matched_subscriptions);
				
				$update_subscription_quantity = array_keys($matched_subscriptions);
				$this->NLAccessLib->updateDeliveredCountForSubscription($update_subscription_quantity);
				
				unset($update_subscription_quantity);
				unset($matched_subscriptions);
			}	
		} catch (Exception $e) {
			mail('teamldb@shiksha.com','Exception in naukri Lead allocation', '<br/>Exception in naukri Lead Allocation <br/>');
			return $recent_processed_id-1;
		}
	
		return $recent_processed_id;
	}

	public function naukriLeadAllocationCron()
	{
		
		$this->validateCron();
		$this->load->model('enterprise/naukrileadsmodel');
		$this->naukri_model = new naukrileadsmodel();
		$this->NLAccessLib = $this->load->library('enterprise/NaukriLeadsLib');
		//$this->_init();

		
		$last_processed_id = $this->NLAccessLib->getLastProcessedId();


		/*Step 1*/
		$this->allocateLeadsToNewSusbcriptions($last_processed_id);

		/*Step 2*/		
		
		$recent_processed_id = $this->allocateIndividualLeads($last_processed_id);

		
		$this->NLAccessLib->updateLastProcessedId($recent_processed_id);		

		/*Step 3 */
		$this->markSubscriptionInactive();

		_p("Done");

		
	}

	function markSubscriptionInactive(){
		//$this->_init();
		$this->NLAccessLib->markSubscriptionInactive();
	}

	

	public function allocateLeadsToNewSusbcriptions($last_processed_id){
		$this->NLAccessLib = $this->load->library('enterprise/NaukriLeadsLib');

		$unprocessed_subscriptions = $this->naukri_model->getNewNaukriLeadSubscription();
		
		foreach ($unprocessed_subscriptions as $subscription) {

			if($subscription['campaign_type'] == 'quantity'){
				$this->NLAccessLib->processQuantityBasedSusbcription($subscription, $last_processed_id);
			}

			if($subscription['campaign_type'] == 'duration'){
				$this->NLAccessLib->processDurationBasedSusbcription($subscription, $last_processed_id);
			}

			$this->NLAccessLib->markSubscriptionProcessed($subscription['id']);

		}
		
	}

	public function deliverNaukriLeadsToClient(){
		$this->validateCron();

		$naukriLeadsLib = $this->load->library('enterprise/NaukriLeadsLib');
		$naukrileadsmodel = $this->load->model('enterprise/naukrileadsmodel');
		$this->load->library(array('alerts_client'));
		$mail_client = new Alerts_client();
		$user_lib = $this->load->library('user/UserLib');

		$distinct_subscription = $naukrileadsmodel->getDistinctSubscriptionsForDelivery();
		$subject = "Response to your subscription on Shiksha.com";

		foreach ($distinct_subscription as $subscription) {
			$responsesToDeliver = $naukrileadsmodel->getLeadsToDeliver($subscription['subscriptionId']);
			if(empty($responsesToDeliver)){
				continue;
			}

			$min_processed_id = 0;
			foreach ($responsesToDeliver as $key=>$response) {
                $last_processed_id = $response['id'];
                if($min_processed_id<1){
                    $min_processed_id = $response['id'];
                }

                $creation_time = explode(' ',$response['creation_time']);
                $responsesToDeliver[$key]['creation_time'] = $creation_time[0]; 
            }

			$contactDetails = $naukriLeadsLib->getSubscriptionContactDetails($subscription['subscriptionId']);

			$displayData = array();
			$displayData['responses'] = $responsesToDeliver;
			$displayData['contactDetails'] = $contactDetails;

			if($contactDetails['email'] != ''){
				$mailer_html = $this->load->view('response/NaukriLeadMailer',$displayData,true);
				_p($mailer_html);
				$response = $mail_client->externalQueueAdd("12",ADMIN_EMAIL,$contactDetails['email'],$subject,$mailer_html,$contentType="html",'0000-00-00 00:00:00','n',array(),'','');
			}

			if($contactDetails['mobile'] != ''){
				$this->sendSMSToNaukriLeadSubscription($contactDetails, $responsesToDeliver);
			}

			if($last_processed_id<1){
				continue;
			}
			$naukrileadsmodel->markResponsesProcessed($subscription['subscriptionId'], $last_processed_id, $min_processed_id);	
		}
		
	}

	private function sendSMSToNaukriLeadSubscription($contactDetails, $responsesToDeliver){
		$this->load->library('alerts_client');

		$mobileNumber = $contactDetails['mobile'];
		$clientId = $contactDetails['clientId'];

		foreach ($responsesToDeliver as $response) {
			$smsContent = "Naukri Learning Lead- ".$response['name'].", ".$response['mobile'].", ".$response['email'].", ".$response['course'];
			$this->alerts_client->addSmsQueueRecord("12",$mobileNumber,$smsContent,$clientId);
		}
	}

	function showLeadData()
	{
		$this->NLAccessLib = $this->load->library('enterprise/NaukriLeadsLib');
		$data = array();
		$this->userStatus = $this->checkUserValidation();
		$data = $this->getHeaderData();
		$data['clientId'] = $this->userStatus[0]['userid'];
		$data['leadData'] = $this->NLAccessLib->getLeadData($data['clientId']);
		$cityAndStateMapping = $this->NLAccessLib->getCityStateMapping();
		$data['city'] = $cityAndStateMapping[0];
		$data['state'] = $cityAndStateMapping[1];
		$this->load->view('NaukriLearningLeadsAccess/LeadDetails',$data);
	}


	function getLeadData()
	{
		$this->NLAccessLib = $this->load->library('enterprise/NaukriLeadsLib');
		$this->userStatus = $this->checkUserValidation();
		$this->load->helper('security');
    	$timeRangeDurationFrom = $this->input->post('timeRangeDurationFrom',true);
    	$timeRangeDurationTo = $this->input->post('timeRangeDurationTo',true);
    	$userid = $this->userStatus[0]['userid'];
		$response = $this->NLAccessLib->getLeadData($userid,$timeRangeDurationFrom,$timeRangeDurationTo);
		$cityAndStateMapping = $this->NLAccessLib->getCityStateMapping();
		$city = $cityAndStateMapping[0];
		$state = $cityAndStateMapping[1];
		//echo json_encode($response);
		$this->downloadLeadDetails($response,$city,$state);

	}

	public function downloadLeadDetails($data,$city,$state)
    {
    	$data  = $this->convertArrayTocsv($data,$city,$state);
    	$filename = "NaukriLearningLeadDetails.csv";
       	$mime = 'text/x-csv';
       	header('Content-Type: "'.$mime.'"');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header('Expires: 0');
        header('Pragma: no-cache');
        header("Content-Length: ".strlen($data));
        
        echo $data;
    }


    function convertArrayTocsv($inputData,$city,$state)
    {
    	$columnList = array('Name','Email','Mobile','Course Interested','City','State','Creation Date','Credits Deducted');

		$databaseName = array('name','email','mobile','course','city_name','state_name','creation_time','credits_deducted');

    	foreach ($columnList as $columnName)
    	{
			$csv .= '"'.$columnName.'",';
		}

		$csv .= "\n";

		foreach ($inputData as $lead)
		{
    		foreach ($databaseName as $key)
    		{
    			if ($key == 'creation_time')
    			{
    				$date = explode(' ',$lead[$key]);
    				$csv .=  '"'.$date[0].'",';
    			}
    			else if ($key == 'city_name')
    			{
    				$csv .= '"'.$city[$lead['city_id']].'",';
    			}
    			else if ($key =='state_name')
    			{
    				$csv .= '"'.$state[$lead['state_id']].'",';
    			}
    			else
    			{
    				$csv .= '"'.$lead[$key].'",';
    			}
    		}
    		$csv .= "\n";
    	}

    	return $csv;
    }
}

?>
