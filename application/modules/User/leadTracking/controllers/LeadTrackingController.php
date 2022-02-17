<?php 

//Class to index Lead Tracking Data

class LeadTrackingController extends MX_Controller {
	private $clientConn;
	private $reasonLimit;

	private $index_name = 'lead_tracking'; //move to constants
	private $index_type = 'lead_debug';
	private $validUsers = array(5137653);	//check it's usage

	private function _init(){
  	  	$ESConnectionLib = $this->load->library('trackingMIS/elasticSearch/ESConnectionLib');
    	$this->clientConn = $ESConnectionLib->getESServerConnection();

    	$this->load->model('leadTracking/leadtrackingmodel');
		$this->leadTrackingModel = new LeadTrackingModel();
	}

	function displayLeadTrackingHomePage(){
		$this->checkUserLoginState();
		
		$this->load->view('leadTracking/leadTrackingMainPage');
	}

	function displayMissingGenieForm(){

		echo $this->load->view('leadTracking/leadMissingAgentForm');
	}

	function findMissingCriteria(){
		$this->load->config('leadTracking/leadDebugConfig');
		$reasonMappingArray = $this->config->item('reasonMappingArray');

		$user_id = $this->input->post('leadId');
		$search_agent_id = $this->input->post('searchAgentId');

		$unpicked_reasons = $this->checkForUnpickedReason($user_id);

		if($unpicked_reasons['is_processed'] == 'no'){
			echo $this->load->view('leadTracking/leadTrackingUnallocationReason',array('unpicked_reasons' => $unpicked_reasons,'picked_exclusion' => true));

			return;
		}

		$unallocation_reason = $this->getLeadUnallocationReason($user_id, $search_agent_id);

		


		echo $this->load->view('leadTracking/leadTrackingUnallocationReason',array('unallocation_reason' => $unallocation_reason,'reasonMappingArray' => $reasonMappingArray,'matching_exclusion'=>true));
	}

	public function checkForUnpickedReason($user_id){
		$this->_init();

		//$is_processed_flag = $this->leadTrackingModel->checkIsProcessedFlag($user_id);
		$user_flag = $this->leadTrackingModel->getUserFlag($user_id);

		return $user_flag;	

	}


	public function getLeadUnallocationReason($user_id, $genie_id){
		
		$this->_init();
		

		$search_query  = $this->getLeadUnallocationGenieQuery($user_id, $genie_id);		
		
		//_P($search_query);
		
		$response      = $this->clientConn->search($search_query);	
	
		return $response['hits']['hits'];
	}

	public function getLeadUnallocationGenieQuery($user_id, $genie_id){
		$query_generator = $this->load->library('leadTracking/LeadDebuggingRequestGenerator');
		$document_size = 100;

		$request_param['index'] = $this->index_name;
		$request_param['type'] = $this->index_type;
		$request_param['size'] = $document_size;

		$request_param['match']['should'][] = array('exclude_genie_ids'=> $genie_id);
		$request_param['match']['should'][] = array('excluded_insufficient_credits'=> $genie_id);
		$request_param['match']['should'][] = array('excluded_porting'=> $genie_id);
		$request_param['match']['should'][] = array('excluded_already_allocated'=> $genie_id);
		$request_param['match']['should'][] = array('excluded_old_genie'=> $genie_id);
		$request_param['match']['should'][] = array('excluded_final_genie'=> $genie_id);

		$request_param['minimum_should_match'] = 1;
		$request_param['filterTerms'] = array('user_id'=> $user_id);
		$request_param['matched_field_flag'] = true;
		$request_param['_source'] = array('user_pick_time','profile','stream_id','substream_id'); //stream and substream kept only for testing
		
		$search_query = $query_generator->generateSearchRequest($request_param);

		return $search_query;
	}

	public function displayAllocationToGenieForm(){

		echo $this->load->view('leadTracking/leadAllocationToGenieform');		
	}

	public function findLeadsAllocatedToGenie(){
		$search_agent_id = $this->input->post('searchAgentId');

		echo $this->load->view('leadTracking/leadTrackingUnallocationReason',array('unallocation_reason' => $unallocation_reason,'reasonMappingArray' => $reasonMappingArray,'matching_exclusion'=>true));

		//error_log($leadId.'========================='.$search_agent_id);
	}
}

?>