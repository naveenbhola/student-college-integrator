<?php 
echo 'Not used anymore';
exit();
class LDBLeadTracking extends MX_Controller {
	
	private $messageForMissingMatch = array('matchCourse'=>'Desired Course',
											'matchPassport'=>'Passport Criteria','matchPlanToStart'=>'Plan To Start',
											'matchAbroadSpecialization'=>'Abroad Specialization','matchCompetition' => 'Match Competition', 'getLocationAgents' =>'Location Criteria','wsAddDumpMatchingLog' => 'Insert in SALeadMatchingLog', 'removePortingAgents' => 'Porting Agent Removed',
											'removeAgentsWithInsufficientCredits' => 'Insuffient Credits', 'removeAlreadyAllocatedAgents' => 'Already Allocated Before', 'getFinalClients' => 'Auto Responder Flags and Limit Quota', 'getDeduplicatedResult' => 'Allocated to other genie of same client', 'allocated' =>'allocated successfully',
											'match12Year' => '12th Passing Year',
											'removeAgentsNotOptedOldLeads' => 'Not opted for Old Leads'
											);

	private $validUsers = array(5137653);

	function init(){

		$this->checkUserLoginState();
		$this->load->model('ldbLeadTrackingModel');
		$this->leadTrackingModel = new LDBLeadTrackingModel();
	}

	function showLogin(){
		$this->load->view('LDB/loginPage');
	}

	function displayLeadTrackingHomePage(){
		$this->checkUserLoginState();
		
		$this->load->view('LDB/leadTrackingMainPage');
	}


	function displayAllocatedGenieForm(){
		$type = $this->input->post('type');

		echo $this->load->view('LDB/leadFindAllocatedAgent',array('type' => $type));
	}

	function displayMissingGenieForm(){
		echo $this->load->view('LDB/leadMissingAgentForm');
	}

	function getLeadDetails($leadId){
		$leadId = $this->input->post('leadId');
		
		if(empty($leadId)){
			return;
		}

		$this->init();

		$userDetails = $this->leadTrackingModel->getUserDetails($leadId);
	
		echo $this->load->view('LDB/leadDetails',array('leadDetails' => $userDetails[0]));
	}

	function leadTrackingLog($leadId){
		$leadId = $this->input->post('leadId');

		$this->init();

		$userDetails = $this->leadTrackingModel->getUserDetails($leadId);
		$leadLog = $this->leadTrackingModel->getLeadTrackingLog($leadId);
		
		$leadLog = unserialize($leadLog);

		$viewData = array();
		$viewData['userDetails'] = $userDetails[0];
		$viewData['leadLog'] = $leadLog;

		echo $this->load->view('LDB/leadMissingAgentForm',$viewData);

	}

	function findMissingCriteria(){
		$leadId = $this->input->post('leadId');
		$searchAgentId = $this->input->post('searchAgentId');
		$searchAgentId = 'agentid":"'.$searchAgentId.'"';

		$this->init();

		$leadLog = $this->leadTrackingModel->getLeadTrackingLog($leadId);
			
		$leadLog = unserialize($leadLog);
		$matchFlag = false;
		$missingCriteria;
		$previousKey='matchCourse';

		foreach ($leadLog['log'] as $key => $log) {

			if($key == 'matchingTime'){
				continue;
			}

			if($key == 'runDeliveryCronASAP' && $leadLog['log']['runDeliveryCronASAP'] ==1){
				$missingCriteria == 'allocated';

				break;
			}
			if(!strpos($log, $searchAgentId) !== false){
				//$missingCriteria = $previousKey;
				$missingCriteria = $key;	
				break;
			} 
		}

		$viewData['message'] = $this->messageForMissingMatch[$missingCriteria];

		echo '<div style="text-align: center"><span>Parameter due which Lead did not get allocated: <b>'.$viewData["message"].'</b></span></div>';
	}

	function getAllocatedSearchAgents($leadId){

		if(empty($leadId) ){
			$leadId = $this->input->post('leadId');
		}
		
		if(empty($leadId)){
			return;
		}

		$this->init();

		$searchAgentId = $this->leadTrackingModel->getAllocatedSearchAgentIds($leadId);


		echo $this->load->view('LDB/leadAllocatedTableView',array('searchAgentData'=>$searchAgentId));
	}

	function getSearchAgentDetails($searchAgentId){
		if(empty($searchAgentId) ){
			$searchAgentId = $this->input->post('searchAgentId');
		}

		if(empty($searchAgentId)){
			return;
		}

		$this->init();

		$searchAgentDetails = $this->leadTrackingModel->getSearchAgentDetails($searchAgentId);

		$searchAgentCriteria = $this->leadTrackingModel->getSearchAgentCriteria($searchAgentId);

		echo $this->load->view('LDB/showSearchAgentDetails',array('searchAgentDetails'=>$searchAgentDetails[0], 'searchAgentCriteria'=>$searchAgentCriteria));
		
	}

	function getSALeadMatchingData($leadId){
		if(empty($leadId) ){
			$leadId = $this->input->post('leadId');
		}

		if(empty($leadId)){
			return;
		}

		$this->init();

		$leadLog = $this->leadTrackingModel->getLeadTrackingLog($leadId);
		$leadLog = unserialize($leadLog);

		$saLeadMatchingLog =json_decode($leadLog['log']['wsAddDumpMatchingLog']);
		$matchingTime = $leadLog['log']['matchingTime'];
		unset($leadLog);

		echo $this->load->view('LDB/saLeadMatchingLogTable',array('matchingLog' =>$saLeadMatchingLog,'matchingTime' => $matchingTime));
		
	}

	function getAllocatedLeads($searchAgentId){
		if(empty($clientId) ){
			$searchAgentId = $this->input->post('searchAgentId');
		}

		if(empty($searchAgentId)){
			return;
		}

		$this->init();

		$allocatedLeads = $this->leadTrackingModel->getAllocatedLeads($searchAgentId);

		echo $this->load->view('LDB/leadAllocatedToGenie',array('allocatedLeads' =>$allocatedLeads));
		
	}

	function doLogin(){
		
		$userId = (int) Modules::run('user/Login/submit');

        if($userId > 0) {
            if(in_array($userId,$this->validUsers)) {
                echo $userId;
            }
            else {
                Modules::run('user/Login/signOut');
                echo '0';
            }
        }    
        else {
            echo '0';
        }		
        return;
	}

	function checkUserLoginState(){
		$userData = $this->getLoggedInUserData();
		$userId = $userData['userId'];
		unset($userData);

		if(!in_array($userId, $this->validUsers)){
			Header( "Location: https://www.shiksha.com/LDBLeadTracking/login");
			exit();
		} else{
			return true;
		}
	}
	
}

?>
