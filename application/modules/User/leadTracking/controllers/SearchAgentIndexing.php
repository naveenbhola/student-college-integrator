<?php 

//Class to index Lead Tracking Data

class SearchAgentIndexing extends MX_Controller {
	private $clientConn;
	private $reasonLimit;
	private $master_map_id_with_values;
	private $indexName = 'ldb_search_agent'; //move to constants
	private $indexType = 'search_agent_analytics';

	public function fetchSearchAgentData($search_agent_id, $start_date, $end_date,$attribute_master_data, $first_run, $custom_data, $clientId){
		//ini_set('memory_limit', '4048M');
		
		$ES_indexing_lib 	= $this->load->library('leadTracking/ESIndexingLib');
		$search_agent_lib 	= $this->load->library('leadTracking/SearchAgentIndexingLib');

		$search_agent_indexing_data = $search_agent_lib->fetchSearchAgentData($search_agent_id, $start_date, $end_date, $attribute_master_data, $first_run, $custom_data, $clientId);

		$index_id = $search_agent_id.'_'.strtotime($start_date);
		$search_agent_indexing_data['indexing_data']['index_id'] = $index_id;
		
		return $search_agent_indexing_data;
	}
	

    public function indexSearchAgentData(){
        $this->validateCron();

    	ini_set('memory_limit', '5048M');
    	ini_set("max_execution_time",-1); 

    	$ES_indexing_lib 	= $this->load->library('leadTracking/ESIndexingLib');
    	$searchagenttrackingmodel = $this->load->model('leadTracking/searchagenttrackingmodel');
    	$start_date =date('Y-m-d');
    	
    	$all_agent_ids = $searchagenttrackingmodel->getSearchAgentForIndexing($start_date);

    	$attribute_master_data = $this->getAllAttributeIdsWithNames();

    	$last_date_value = 0;      //increase the counter to index past date data
    	

    	foreach ($all_agent_ids as $agent_id) {
            
            $itr = 0;
    		$index_docs = array();
    		$first_run = true;
    		$custom_data = array();


    		while($itr <= $last_date_value){
                $dt = '-'.$itr.' days';
                $date = date('Y-m-d',strtotime($start_date.$dt));
                $end_date = $date.' 23:59:59';          
                


                $return_data = $this->fetchSearchAgentData($agent_id['agentid'], $date, $end_date,$attribute_master_data, $first_run, $custom_data, $agent_id['clientId']);

    			$itr++;
    			//error_log('############# inner loop ################### --> '.$itr);
    			
    			$index_docs[] = $return_data['indexing_data'];
    			$custom_data = $return_data['custom_data'];
    			
    			
    			$first_run = false;

    		}
	
            $response = $ES_indexing_lib->indexDataToElastic($index_docs,$this->indexName,$this->indexType, $index_id);
            
    		unset($index_docs);
    	}
    }

    public function getAllAttributeIdsWithNames(){
    	ini_set('memory_limit', '512M');

    	$lead_indexing_lib = $this->load->library('leadTracking/LeadIndexingLib');
    	$master_data = $lead_indexing_lib->getAllAttributeIdsWithNames();
  		$this->master_map_id_with_values = $master_data;  	
    	return $master_data;
    }
}

?>
