<?php
/*
 SearchMatrix_Server backend controller
*/

class SearchMatrix_Server extends MX_Controller {
  
  function init() {
	$this->load->library('xmlrpc');
	$this->load->library('xmlrpcs');
	$this->load->model('searchmatrixmodel');
  }

  function index() {
	$this->init();
	$this->load->library('xmlrpc');
	$this->load->library('xmlrpcs');
	
	$config['functions']['logSearchQueries'] = array('function' => 'SearchMatrix_Server.logSearchQueries');
	$config['functions']['updateLogResultClickedStatusById'] = array('function' => 'SearchMatrix_Server.updateLogResultClickedStatusById');
	$config['functions']['trackSearchQuery'] = array('function' => 'SearchMatrix_Server.trackSearchQuery');
	$config['functions']['logASQueries'] = array('function' => 'SearchMatrix_Server.logASQueries');
	$config['functions']['updateSearchPageId'] = array('function' => 'SearchMatrix_Server.updateSearchPageId');
	
	
	$args = func_get_args(); $method = $this->getMethod($config,$args);
	return $this->$method($args[1]);
  }
	
  public function logSearchQueries($request) {
	  $this->init();
	  $params = $request->output_parameters();
	  $req_params =  json_decode($params[0], true);
	  $params_array = $req_params["search_params"];
	  
	  $modelParams = array();
	  $modelParams['keyword'] = (isset($params_array['keyword'])) ?  $params_array['keyword'] : '';
	  $modelParams['location'] = (isset($params_array['location'])) ?  $params_array['location'] : '';
	  $modelParams['country_id'] = (isset($params_array['countryId']) && $params_array['countryId'] != "-1" ) ?  $params_array['countryId'] : '';
	  $modelParams['city_id'] = (isset($params_array['cityId']) && $params_array['cityId'] != "-1" ) ?  $params_array['cityId'] : '';
	  $modelParams['course_type'] = (isset($params_array['cType']) && $params_array['cType'] != "-1" ) ?  $params_array['cType'] : '';
	  $modelParams['course_level'] = (isset($params_array['courseLevel']) && $params_array['courseLevel'] != "-1" ) ?  $params_array['courseLevel'] : '';
	  $search_type = isset($params_array['searchType']) ?  $params_array['searchType'] : '';
	  $modelParams['session_id'] = $params_array['PHPSESSID'];
	  
	  if($search_type == "blog"){
		$search_type = "article";
	  } else if($search_type == "question"){
		$search_type = "ana";
	  } else if($search_type == "course"){
		$search_type = "institute";
	  }
	  $modelParams['search_type'] = $search_type;
	  $modelParams['search_result_ids'] = $req_params["search_result_ids"];
	  $modelParams['total_records'] = $req_params["search_total_records"];
	  $modelParams['page_id'] = $req_params["page_id"];
	  $modelParams['autosuggestor'] = $req_params["autosuggestor"];
	  
	  $searchMatrixModelObject = new SearchMatrixModel();
	  $unique_insert_id = $searchMatrixModelObject->logSearchQueries($modelParams);
	  
	  $return_data = json_encode($unique_insert_id);
	  $response = array($return_data,'int');
	  return $this->xmlrpc->send_response($response);
  }
  
  public function trackSearchQuery($request){
	$this->init();
	$params = $request->output_parameters();
	$req_params =  json_decode($params[0], true);
	
	$searchMatrixModelObject = new SearchMatrixModel();
	$unique_insert_id = $searchMatrixModelObject->logSearchQueries($req_params);
	
	if(TRACK_AUTOSUGGESTOR_RESULTS){
		$searchMatrixModelObject->updateASRows($unique_insert_id, $req_params['session_id']);
	}
	
	$return_data = json_encode($unique_insert_id);
	$response = array($return_data,'int');
	return $this->xmlrpc->send_response($response);
  }
  
  public function updateLogResultClickedStatusById($request) {
	  $this->init();
	  $params = $request->output_parameters();
	  $req_params =  json_decode($params[0], true);
	  $searchMatrixModelObject = new SearchMatrixModel();
	  $return = $searchMatrixModelObject->updateLogResultClickedStatusById($req_params);
	  
	  $return_data = json_encode($return);
	  $response = array($return_data,'int');
	  return $this->xmlrpc->send_response($response);
  }

  public function updateSearchPageId($request) {
	  $this->init();
	  $params = $request->output_parameters();
	  $req_params =  json_decode($params[0], true);
	  $searchMatrixModelObject = new SearchMatrixModel();
	  $return = $searchMatrixModelObject->updateSearchPageId($req_params);
	  $return_data = json_encode($return);
	  $response = array($return_data,'int');
	  return $this->xmlrpc->send_response($response);
  }  
  
  
  public function prepareAutoSuggestDataForInsert($data, $session_id, $page, $suggestionShown){
	  $updatedData = array();
	  if(count($data) > 0){
		for($i=0; $i < count($data); $i++){
		  $tempA = $data[$i];
		  $tempA['page'] = $page;
		  $tempA['session_id'] = $session_id;
		  $tempA['suggestion_shown'] = $suggestionShown;
		  array_push($updatedData, $tempA);
		}
	  }
	  return $updatedData;
  }
  
  public function logASQueries($request){
	$this->init();
	$params = $request->output_parameters();
	$req_params =  json_decode($params[0], true);
	
	$searchMatrixModelObject = new SearchMatrixModel();
	$autoSuggestorData = $this->prepareAutoSuggestDataForInsert($req_params['autosuggestor_params'], $req_params['session_id'], $req_params['page'], $req_params['suggestionShown']);
	$searchMatrixModelObject->logAutoSuggestQueries($autoSuggestorData);
	$response = array(1,'int');
	return $this->xmlrpc->send_response($response);
  }

}

?>