<?php 


class LeadTrackingIndexing extends MX_Controller {
	private $clientConn;
	private $reasonLimit;
	private $indexName = 'lead_tracking'; //move to constants
	private $indexType = 'lead_debug';
	
	private function _init(){

  	  	$ESConnectionLib = $this->load->library('trackingMIS/elasticSearch/ESConnectionLib');
    	$this->clientConn = $ESConnectionLib->getESServerConnectionWithCredentials();
	}

	public function fetchLeadTrackingData(){
		$this->validateCron();
		ini_set('memory_limit', '4048M');

		$ES_indexing_lib 		= $this->load->library('leadTracking/ESIndexingLib');
		$lead_indexing_lib 		= $this->load->library('leadTracking/LeadIndexingLib');
		$leadtrackingmodel 		= $this->load->model('leadTracking/leadtrackingmodel');

		$master_map_id_with_values = $this->getAllAttributeIdsWithNames();

		$tracking_data = $leadtrackingmodel->fetchLeadTrackingData();

		$counter = 0;
		
		foreach ($tracking_data as $track_data) {
			$counter++;
			error_log('========== counter == '.$counter);
			
			$update_flag = true;

			$index_tracking_data = unserialize($track_data['userTrackingData']);
			
			$indexingData = $lead_indexing_lib->formatTrackingData($index_tracking_data, $master_map_id_with_values);

			$response = $ES_indexing_lib->indexDataToElastic($indexingData,$this->indexName,$this->indexType);

			
			foreach ($response['items'] as $res) {
				if($res['index']['status'] != 201){
					$update_flag = false;
				}
			}

			if($update_flag){

				$bulk_update[] = $track_data['id'];

				//$leadtrackingmodel->markDataIndexed($track_data['id']); //mark row processed for indexing in db	
				
			}

			//this code is for one time indexing only
			if($counter%500 == 0){
				$leadtrackingmodel->markDataIndexed($bulk_update);
				$bulk_update = array();
			}
		}

		//this code is for one time indexing only
		if(count($bulk_update)>0){
			$leadtrackingmodel->markDataIndexed($bulk_update);
		}

	}


    public function getAllAttributeIdsWithNames(){
    	//ini_set('memory_limit', '512M');

    	$lead_indexing_lib = $this->load->library('leadTracking/LeadIndexingLib');
    	$master_data = $lead_indexing_lib->getAllAttributeIdsWithNames();
    	return $master_data;
    }
}

?>