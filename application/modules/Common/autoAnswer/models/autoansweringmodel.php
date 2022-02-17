<?php

class AutoAnsweringModel extends MY_Model {
	
	private $CI;
	function __construct(){
		parent::__construct('AnA');
		$this->CI = &get_instance();
		
	}

	private function initiateModel($operation='read'){
		$appId = 1;	
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}

	public function fetchTagsInfo($tags=array()){
		$result = array();
		if(!empty($tags)){
			$dbHandle = $this->initiateModel();
			$sql = "select id,tags,tag_entity from tags where id in(".implode(",", $tags).") and status = 'live'";
			$query = $this->dbHandle->query($sql);
			$result = $query->result_array();

		}
		return $result;
	}

	public function fetchTagsForListing($listingTagArray = array()){
		$result = array();
		if(!empty($listingTagArray)){
			$dbHandle = $this->initiateModel();
			$listingsTagString = implode(",", array_keys($listingTagArray));
        	$sql = "select entity_id,tag_id from tags_entity where tag_id in(".$listingsTagString.")";	
        	$query = $this->dbHandle->query($sql);
			$result = $query->result_array();
		}
		return $result;
	}

	function getCityIdByName($cityName){

		$result = 0;
		if(!empty($cityName)){
			$dbHandle = $this->initiateModel();
        	$sql = "SELECT city_id FROM `countryCityTable` WHERE city_name=? and enabled=0";	
        	$query = $this->dbHandle->query($sql, array($cityName));
			$rows = $query->result_array();
			foreach ($rows as  $row) {
				$result = $row['city_id'];
			}
		}
		return $result;
	}

	function getStateIdByName($stateName){

		$result = 0;
		if(!empty($stateName)){
			$dbHandle = $this->initiateModel();
        	$sql = "SELECT state_id FROM `stateTable` WHERE state_name=? and enabled=0";	
        	$query = $this->dbHandle->query($sql, array($stateName));
			$rows = $query->result_array();
			foreach ($rows as  $row) {
				$result = $row['state_id'];
			}
		}
		return $result;
	}
		
}

?>
