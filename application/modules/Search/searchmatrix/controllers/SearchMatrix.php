<?php

/*
 SearchMatrix FrontEnd controller
*/

class SearchMatrix extends MX_Controller {
  
	public function init() {
		$this->load->library('SearchMatrixLib');
		$this->load->library('LDB_Client');
	}
  
	public function updateLogResultClickedStatusById() {
		$this->init();
		if(TRACK_SEARCH_RESULTS) {
			$paramArray = array();
			$paramArray['result_clicked_id'] 				= (urldecode($this->input->get_post('result_clicked_id')) != false) ? urldecode($this->input->get_post('result_clicked_id')) : '';

			$paramArray['result_clicked_type'] 				= (urldecode($this->input->get_post('result_clicked_type')) != false) ? urldecode($this->input->get_post('result_clicked_type')) : '';

			$paramArray['result_clicked_row_count'] 		= (urldecode($this->input->get_post('result_clicked_row_count')) != false) ? urldecode($this->input->get_post('result_clicked_row_count')) : '';

			$paramArray['result_search_id'] 				= (urldecode($this->input->get_post('result_search_id')) != false) ? urldecode($this->input->get_post('result_search_id')) : '';

			$paramArray['result_type'] 						= (urldecode($this->input->get_post('result_type')) != false) ? urldecode($this->input->get_post('result_type')) : '';

			$paramArray['page_id'] 							= (urldecode($this->input->get_post('page_id')) != false) ? urldecode($this->input->get_post('page_id')) : 1;

			
			$source = urldecode($this->input->get_post('source'));
			$source = $source ? $source : 'desktop';
			
			$searchMatrixLibObject = new SearchMatrixLib();
			$result = $searchMatrixLibObject->updateLogResultClickedStatusById($paramArray);
			if($source == 'mobile') {
				$this->_renderImage();
			} else {
				echo $result;
			}
		}
	}
  
	public function trackSearchQuery($params= array()) {
		$this->init();
		// defined in shikshaConfig.php
		if(TRACK_SEARCH_RESULTS) {

			$paramArray = array();

			$paramArray['keyword'] 							= (urldecode($this->input->get_post('keyword')) != false) ? urldecode($this->input->get_post('keyword')) : $params['keyword'];

			//Clusters information
			$paramArray['course_level'] 					= (urldecode($this->input->get_post('course_level')) != false) ? urldecode($this->input->get_post('course_level')) : $params['course_level'];

			$paramArray['course_type'] 						= (urldecode($this->input->get_post('course_type')) != false) ? urldecode($this->input->get_post('course_type')) : $params['course_type'];

			//Clusters location information
			$paramArray['city_id'] 							= (urldecode($this->input->get_post('city_id')) != false) ? urldecode($this->input->get_post('city_id')) : $params['city_id'];

			$paramArray['country_id'] 						= (urldecode($this->input->get_post('country_id')) != false) ? urldecode($this->input->get_post('country_id')) : $params['country_id'];

			$paramArray['zone_id'] 							= (urldecode($this->input->get_post('zone_id')) != false) ? urldecode($this->input->get_post('zone_id')) : $params['zone_id'];

			$paramArray['locality_id'] 						= (urldecode($this->input->get_post('locality_id')) != false) ? urldecode($this->input->get_post('locality_id')) : $params['locality_id'];

			//Count information
			$paramArray['institute_count'] 					= (urldecode($this->input->get_post('institute_count')) != false) ? urldecode($this->input->get_post('institute_count')) : $params['institute_count'];

			$paramArray['course_count'] 					= (urldecode($this->input->get_post('course_count')) != false) ? urldecode($this->input->get_post('course_count')) : $params['course_count'];

			$paramArray['article_count'] 					= (urldecode($this->input->get_post('article_count')) != false) ? urldecode($this->input->get_post('article_count')) : $params['article_count'];

			$paramArray['question_count'] 					= (urldecode($this->input->get_post('question_count')) != false) ? urldecode($this->input->get_post('question_count')) : $params['question_count'];

			//Resultids information
			$paramArray['institute_type_result_ids'] 		= (urldecode($this->input->get_post('institute_type_result_ids')) != false) ? urldecode($this->input->get_post('institute_type_result_ids')) : $params['institute_type_result_ids'];

			$paramArray['content_type_result_ids'] 			= (urldecode($this->input->get_post('content_type_result_ids')) != false) ? urldecode($this->input->get_post('content_type_result_ids')) : $params['content_type_result_ids'];

			//Search type: institute or content
			$paramArray['search_type'] 						= (urldecode($this->input->get_post('search_type')) != false) ? urldecode($this->input->get_post('search_type')) : $params['search_type'];

			//Track search query value
			$paramArray['tsr'] 								= (urldecode($this->input->get_post('tsr')) != false) ? urldecode($this->input->get_post('tsr')) : $params['tsr'];

			//QER information
			$paramArray['result_step'] 						= (urldecode($this->input->get_post('result_step')) != false) ? urldecode($this->input->get_post('result_step')) : $params['result_step'];

			$paramArray['initial_qer'] 						= (urldecode($this->input->get_post('initial_qer')) != false) ? urldecode($this->input->get_post('initial_qer')) : $params['initial_qer'];

			$paramArray['final_qer'] 						= (urldecode($this->input->get_post('final_qer')) != false) ? urldecode($this->input->get_post('final_qer')) : $params['final_qer'];

			//Sort type information
			$paramArray['sort_type'] 						= (urldecode($this->input->get_post('sort_type')) != false) ? urldecode($this->input->get_post('sort_type')) : $params['sort_type'];

			//Autosuggestor
			$paramArray['suggestion_shown'] 				= (urldecode($this->input->get_post('suggestion_shown')) != false) ? urldecode($this->input->get_post('suggestion_shown')) : $params['suggestion_shown'];

			$paramArray['page_id'] 							= (urldecode($this->input->get_post('page_id')) != false) ? urldecode($this->input->get_post('page_id')) : $params['page_id'];
			if(empty($paramArray['page_id'])) {
				$paramArray['page_id'] =1;
			}
			
			$paramArray['page'] 							= (urldecode($this->input->get_post('from_page')) != false) ? urldecode($this->input->get_post('from_page')) : $params['from_page'];

			$source = urldecode($this->input->get_post('source')) ? urldecode($this->input->get_post('source')) : $params['source'];
                        $paramArray['source'] = $source ? $source : 'desktop';
			
			//Session id information
			$sessionId = sessionId();
			if(empty($sessionId)){
				$sessionId = "";
			}
			$paramArray['session_id'] 						= $sessionId;
			$paramArray['remote_ip'] 						= S_REMOTE_ADDR;
			
			$loggedInType = $this->getUserLoggedInInformation();
			$paramArray['loggedin_type'] = $loggedInType;
			//Valid search query check
			if( ($paramArray['search_type'] == "institute" || $paramArray['search_type'] == "content") && ($paramArray['session_id'] != '-1') ){
				$searchMatrixLibObject = new SearchMatrixLib();
				$result = $searchMatrixLibObject->trackSearchQuery($paramArray);
			}
			if($paramArray['source'] == 'mobile') {
				return $result;
			} else {
				echo $result;
			}
		} else {
			if($paramArray['source'] == 'mobile') {
				return -1;
			} else {
				echo "-1";
			} 
			
		}
	}

	 private function _renderImage() {
        //  Send a BEACON image back to the user's browser
        header( 'Content-type: image/gif' );
        # The transparent, beacon image
        echo chr(71).chr(73).chr(70).chr(56).chr(57).chr(97).
          chr(1).chr(0).chr(1).chr(0).chr(128).chr(0).
          chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).
          chr(33).chr(249).chr(4).chr(1).chr(0).chr(0).
          chr(0).chr(0).chr(44).chr(0).chr(0).chr(0).chr(0).
          chr(1).chr(0).chr(1).chr(0).chr(0).chr(2).chr(2).
          chr(68).chr(1).chr(0).chr(59);
    	}
  
	public function getSelectedPageNumber($start_offset_search = 0, $count_offset_search = 0) {
		$selectedPage =  ceil($start_offset_search / $count_offset_search);
		if($selectedPage > 0){
		  $selectedPage = $selectedPage + 1;
		} else {
		  $selectedPage = 1;
		}
		return $selectedPage;
	}
  
	public function logASQueries(){
		$this->init();
		if(TRACK_AUTOSUGGESTOR_RESULTS) {
			$paramArray = array();
			$autosuggestor 					= (urldecode($this->input->post('autosuggestor')) != false) ? ($this->input->post('autosuggestor')) : -1;
			$autosuggestor_suggestion_shown = (urldecode($this->input->post('autosuggestor_suggestion_shown')) != false) ? urldecode($this->input->post('autosuggestor_suggestion_shown')) : -1;
			$page = (urldecode($this->input->post('page')) != false) ? urldecode($this->input->post('page')) : "";
			$suggestionShown = (urldecode($this->input->post('suggestionShown')) != false) ? urldecode($this->input->post('suggestionShown')) : 0;
			
			$autoSuggestorParams = $this->decodeAutoSuggestorParams($autosuggestor);
			$paramArray['autosuggestor_params'] = $autoSuggestorParams;
			$paramArray['autosuggestor_suggestion_shown'] = $autosuggestor_suggestion_shown;
			$paramArray['page'] = $page;
			$paramArray['suggestionShown'] = $suggestionShown;
			
			$sessionId = sessionId();
			if(empty($sessionId)){
				$sessionId = "";
			}
			$paramArray['session_id'] = $sessionId;
			$searchMatrixLibObject = new SearchMatrixLib();
			$result = $searchMatrixLibObject->logASQueries($paramArray);
			echo $result;
		}
	}
  
  
	public function decodeAutoSuggestorParams($params){
		$dictSeparator = "$!#!$";
		$paramValueSeparator = "!$!";
		$paramsSeparator = "$!$";
		$keyMappings = array('spn' => 'suggestion_no', 'ui'=>'user_input', 'at' => 'user_action', 'sp' => 'suggestion_picked');
		$paramsArray = array();
		if(strlen(trim($params)) > 0){
			$params = trim($params);
			$dictSplitData = explode($dictSeparator, $params);
			for($i=0; $i < count($dictSplitData); $i++){
				$tempA = array();
				$paramsSplitData = explode($paramsSeparator, $dictSplitData[$i]);
				for($j=0; $j < count($paramsSplitData); $j++){
					$paramValueSplitData = explode($paramValueSeparator, $paramsSplitData[$j]);
					if(count($paramValueSplitData) > 1){
						if(in_array($paramValueSplitData[0], array_keys($keyMappings))){
						  $tempA[$keyMappings[$paramValueSplitData[0]]] = $paramValueSplitData[1];  
						}
					}
				}
				if(count(array_values($tempA)) > 0){
					array_push($paramsArray, $tempA);  
				}
			}
		}
		return $paramsArray;
	}
	
	public function getUserLoggedInInformation(){
		$userLoggedInType = "notloggedin";
		$validateuser = $this->checkUserValidation();
		if($validateuser != "false"){
			$userLoggedInType = "loggedin";
			$ldbObj = new LDB_Client();
			$result = $ldbObj->isLDBUser($validateuser[0]['userid']);
			$isLDBUser = false;
			if(is_array($result) && isset($result[0]['UserId'])){
				  $isLDBUser = true;
				  $userLoggedInType = "ldbuser";
			}
		}
		return $userLoggedInType;
	}

	public function generateFilterKeyId($key='searchFilterTracking'){
		return Modules::run('common/IDGenerator/generateId',$key);
	}
}
?>