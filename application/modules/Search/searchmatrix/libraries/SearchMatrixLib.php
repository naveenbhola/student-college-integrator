<?php 

class SearchMatrixLib
{
	private $_ci;
	
	function __construct()
	{
		$this->_ci = & get_instance();
		$this->_ci->load->library('xmlrpc');
	}
	
	private function _setMode($mode = 'read')
	{
		$server_url = SEARCH_MATRIX_READ_SERVER;
		$server_port = SEARCH_MATRIX_READ_SERVER_PORT;
		if($mode == 'write')
		{
			$server_url = SEARCH_MATRIX_WRITE_SERVER;
			$server_port = SEARCH_MATRIX_WRITE_SERVER_PORT;
		}
		$this->_ci->xmlrpc->set_debug(0);
		$this->_ci->xmlrpc->server($server_url,$server_port);
	}
	
	function logSearchQueries($parameters = array(), $search_results = array())
	{
		$this->_setMode('write');
		$this->_ci->xmlrpc->method('logSearchQueries');
		$request = array();
		$returnData = $this->getTrackDataFromSearchResult($search_results);
		
		$session_id = sessionId();
		if(empty($session_id)){
			$session_id = "";
		}
		$request['search_params'] = $parameters;
		$request['search_params']['PHPSESSID'] = $session_id;
		$request['search_result_ids'] = $returnData['result_ids'];
		$request['search_total_records'] = $returnData['total_records'];
		$request['page_id'] = $returnData['page_id'];
		$this->_ci->xmlrpc->request(array(json_encode($request)));
		if (!$this->_ci->xmlrpc->send_request()){
			return $this->_ci->xmlrpc->display_error();
        } else {
			return $this->_ci->xmlrpc->display_response();
        }
	}
	
	function getTrackDataFromSearchResult($finalDataDisplay = array()){
		$returnData = array();
		if(isset($finalDataDisplay["searchList"]) && !empty($finalDataDisplay["searchList"])){
			$track_search_results = $finalDataDisplay["searchList"];
			$track_results = $track_search_results["results"];
			$track_results_count = intval($track_search_results["numOfRecords"]);
			$track_extended_results = $track_search_results["extenedResults"];
			$track_extended_results_count = count($track_extended_results);
			$total_records = $track_results_count + $track_extended_results_count;
		
			$rows = $finalDataDisplay["countOffsetSearch"];
			$start = $finalDataDisplay["startOffSetSearch"];
			$track_total_pages = ceil($track_results_count / $rows);
			$track_selected_page =  ceil($start / $rows);
			
			$result_dataset_ids = array();
			foreach($track_results as $key => $val){
			  array_push($result_dataset_ids, $val['typeId']);
			}
			foreach($track_extended_results as $key => $val){
			  array_push($result_dataset_ids, $val['typeId']);
			}
			$result_dataset_ids = serialize($result_dataset_ids);
			$returnData['page_id'] = $track_selected_page;
			$returnData['result_ids'] = $result_dataset_ids;
			$returnData['total_records'] = $total_records;
		}
		return $returnData;
	}
	
	public function updateLogResultClickedStatusById($parameters = array()) {
		$this->_setMode('write');
		$this->_ci->xmlrpc->method('updateLogResultClickedStatusById');
		$request = $parameters;
		$this->_ci->xmlrpc->request(array(json_encode($request)));
		if (!$this->_ci->xmlrpc->send_request()){
			return $this->_ci->xmlrpc->display_error();
        } else {
			return $this->_ci->xmlrpc->display_response();
        }
	}
	
	public function updateSearchPageId($parameters = array()) {
		$this->_setMode('write');
		$this->_ci->xmlrpc->method('updateSearchPageId');
		$request = $parameters;
		$this->_ci->xmlrpc->request(array(json_encode($request)));
		if (!$this->_ci->xmlrpc->send_request()){
			return $this->_ci->xmlrpc->display_error();
        } else {
			return $this->_ci->xmlrpc->display_response();
        }
	}
	
	public function trackSearchQuery($parameters = array()){
		$this->_setMode('write');
		$this->_ci->xmlrpc->method('trackSearchQuery');
		$request = $parameters;
		$this->_ci->xmlrpc->request(array(json_encode($request)));
		if (!$this->_ci->xmlrpc->send_request()){
			return $this->_ci->xmlrpc->display_error();
        } else {
			return $this->_ci->xmlrpc->display_response();
        }
	}
	
	public function logASQueries($parameters = array()){
		error_log("30may : in searchMatrixLib logASQueries : ");
		$this->_setMode('write');
		$this->_ci->xmlrpc->method('logASQueries');
		$request = $parameters;
		$this->_ci->xmlrpc->request(array(json_encode($request)));
		if (!$this->_ci->xmlrpc->send_request()){
			return $this->_ci->xmlrpc->display_error();
        } else {
			return $this->_ci->xmlrpc->display_response();
        }
	}
	
}
?>