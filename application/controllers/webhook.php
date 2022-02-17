<?php
class webhook extends MX_Controller {

	function leadCreation(){
		$challenge    = $_REQUEST['hub_challenge'];
		$verify_token = $_REQUEST['hub_verify_token'];

        if ($verify_token === 'shiksha123') {
          echo $challenge;
        }

		$input     = json_decode(file_get_contents('php://input'), true);
		if($input){
			$fbLeadLib = $this->load->library("marketing/fbLead/fbLeadLib");
			$fbLeadLib->create($input);
		}
		
	}

	function pullFBLeadData($fbLead,$formId){		
		$data['lead_id']            = $fbLead;
		$data['fb_form_id']         = $formId;
		$createdTime                = 1510296685;
		$date                       = new DateTime();
		$date->setTimestamp($createdTime);
		$data['fb_lead_created_on'] = $date->format('Y-m-d H:i:s');		

		$fbLeadLib = $this->load->library("marketing/fbLead/fbLeadLib");
		$fbLeadLib->createFbLeadForTesting($data);				
	}

	function getMCResponse(){		
		//error_log("missed call response".print_r($_POST,true));
		//mail('naveen.bhola@shiksha.com,aman.varshney@shiksha.com','Missed call response at '.date('Y-m-d H:i:s'), 'Missed call response: <br/>'.print_r($_POST, true));

		$this->load->model('LDBCommonmodel');
		$commonModel = new LDBCommonModel();
		$mcResponseData                       = array();
		$client_id					          = $this->input->post('client_id', true);
		if($client_id != '3848'){
			mail('naveen.bhola@shiksha.com,aman.varshney@shiksha.com','Missed call response from different client id at '.date('Y-m-d H:i:s'), 'Missed call response from different client id: <br/>'.print_r($_POST, true));		
			return;
		}

		$vmn               = $this->input->post('vmn',true);	
		if($vmn != '919212662010'){
			mail('naveen.bhola@shiksha.com,aman.varshney@shiksha.com','Missed call vmn is different at '.date('Y-m-d H:i:s'), 'Missed call response vmn is different: <br/>'.print_r($_POST, true));		
			return;
		}

		

		$mcResponseData['mobile']             = $this->input->post('mobile', true);
		$mcResponseData['missed_call_number'] = $this->input->post('to', true);
		
		
		if($mcResponseData['missed_call_number'] != '911244396303'){
			mail('naveen.bhola@shiksha.com,aman.varshney@shiksha.com','Missed call response from different missed call number at '.date('Y-m-d H:i:s'), 'Missed call response from different missed call number: <br/>'.print_r($_POST, true));		
			return;
		}


		$mcResponseData['time']    			  = $this->input->post('time', true);
		$mcResponseData['operator']           = $this->input->post('operator', true);
		$mcResponseData['circle']             = $this->input->post('circle', true);
		$mcResponseData['text']               = $this->input->post('text_message', true);
		$mcResponseData['vendor']             = 'vfirst';
		
		if($mcResponseData['mobile']){
			$mcResponseId = $commonModel->saveMCResponse($mcResponseData);		
		}else{
			mail('naveen.bhola@shiksha.com,aman.varshney@shiksha.com','Missed call empty response at '.date('Y-m-d H:i:s'), 'Missed call response: <br/>'.print_r($_POST, true));
			return;
		}
	}	

	function naukriLeads(){
		$name 		= $this->input->post('name');
		$email 		= $this->input->post('email');
		$mobile 	= $this->input->post('mobile');
		$city 		= $this->input->post('city');
		$course 	= $this->input->post('course');

		if(empty($name) || empty($email) || empty($mobile) || empty($city) || empty($course)){
			mail('teamldb@shiksha.com', 'Naukri Leads data is empty', print_r($_POST, true));
			echo 'failure';
			return 'failure';
		}

		/*validate email*/
		if(!validateEmailMobile('email',$email)){
			mail('teamldb@shiksha.com', 'Naukri Leads- Email is not valid', print_r($_POST, true));
			echo 'failure';
			return 'failure';
		}

		/*validate mobile*/
		$mobileLen = strlen($mobile);
		if(!is_numeric($mobile) || $mobileLen > 20 || $mobileLen < 6) {
			mail('teamldb@shiksha.com', 'Naukri Leads- mobile is not valid', print_r($_POST, true));
			echo 'failure';
			return 'failure';
		}

		$naukri_lead_data['name'] 		= $name;
		$naukri_lead_data['email'] 		= $email;
		$naukri_lead_data['mobile'] 	= $mobile;
		$naukri_lead_data['city'] 		= $city;
		$naukri_lead_data['course'] 	= $course;

		$naukri_leads_lib = $this->load->library('enterprise/NaukriLeadsLib');
		$naukri_leads_lib->saveNaukriLeadsData($naukri_lead_data);

		echo 'success';
		return 'success';
	}
}
?>
