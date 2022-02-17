<?php 
class citiesbulkcreationmodel extends MY_Model {
	
	function __construct() {
		parent::__construct('default');
	}

	private function initiateModel($mode = "write", $module = ''){
		if($mode == 'read') {
		    $this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
		    $this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	public function getStates(){
		$this->initiateModel('read');
		$sql =  "SELECT state_id, state_name FROM stateTable WHERE countryId = 2";
		return $this->dbHandle->query($sql)->result_array();
	}

	public function checkCityName($cityName){
		$this->initiateModel('read');
		$sql =  "SELECT * FROM countryCityTable WHERE countryId = 2 AND city_name = ?";
		return $this->dbHandle->query($sql, array($cityName))->result_array();
	}

	public function createCity($cityName, $stateId){	
		$this->initiateModel('write');

		$data               	       	= array();
		$data['city_name']  	       	= $cityName;
		$data['countryId']   		= 2;
		$data['state_id']      		= $stateId;
		$data['tier']      		= 3;
		$data['modificationDate']     	= date('Y-m-d H:i:s');
		$data['modifiedBy'] 		= 11;
		$this->dbHandle->insert('countryCityTable',$data);
	}
	

}
?>