<?php 
class BreakStateModel extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';

    private $mvCitiesIds = array('702','998','214','699','713','160');

    private $activityLogFile = '/tmp/telanganaActivityLog.txt';
    
	function __construct() {
		parent::__construct('Listing');
    }

    private function initiateModel($mode = "write"){
		if($this->dbHandle && $this->dbHandleMode == 'write')
		    return;
		
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }


    public function update() {
    	$currentDate = date('Y-m-d-H-i-s');
    	rename("/tmp/telanganaActivityLog.txt", "/tmp/telanganaActivityLog$currentDate.txt");
        unlink('/tmp/telanganaActivityLog.txt');


		//Obtaing Read-Mode on DB
		$this->initiateModel('write');
		

		// add state in StateTable
		$stateId = $this->addState();
		//countryCityTable changes
		$this->countryCityTableChanges($stateId);

		//changes in categoryPageData
		$this->categoryPageDataChanges($stateId);
		
		
		//changes in SAPreferedLocationSearchCriteria
		$this->SAPreferedLocationSearchCriteria($stateId);

		// changes in tUserLocationPref
		$this->tUserLocationPrefChanges($stateId);
	}

	public function addState(){
		
		$query       = "select state_id from stateTable where state_name='Telangana'";
		$stateResult = $this->dbHandle->query($query)->row_array();

	
		// check state already exist
		if(!isset($stateResult['state_id'])){
			$data       = array('state_name' => 'Telangana',
							    'countryId'  => 2,
							    'tier'       => 2);
			
			$this->dbHandle->insert('stateTable', $data); 
			$stateId = $this->dbHandle->insert_id();
			error_log("Table Name: stateTable".PHP_EOL,3,$this->activityLogFile);
        	error_log("StateId Inserted:".$stateId.PHP_EOL,3,$this->activityLogFile);
        	error_log("Rows Affected:".$this->dbHandle->affected_rows().PHP_EOL,3,$this->activityLogFile);
		}else{
			$stateId = $stateResult['state_id'];
			error_log("StateId:".$stateId.PHP_EOL,3,$this->activityLogFile);
		}
       
        error_log("-----------------------------------------------".PHP_EOL,3,$this->activityLogFile);

		return $stateId;
	}

	public function countryCityTableChanges($stateId){

		
		// add in Telangana  and A.P. 
		
		error_log("Table Name: countryCityTable".PHP_EOL,3,$this->activityLogFile);


		$cityToAdd = array($stateId => array('Medak','Nalgonda','Telangana-Other'),
						   100 => array('Kakinada','Rajahmundry','Vizianagaram')
						  );

		error_log("cities Added in  countryCityTable".PHP_EOL,3,$this->activityLogFile);

		foreach ($cityToAdd as $key => $cityNameArray) {
			foreach($cityNameArray as $cityName){
			//check city Already Exist
			$query = "select city_id from countryCityTable where city_name= ? ";
				$cityResult = $this->dbHandle->query($query,array($cityName))->row_array();
				//if city not added
				if(!isset($cityResult['city_id'])){
					//add city
					$cityAddedArray = array(
								      'city_name' => $cityName,
								      'countryId' => 2 ,
								      'state_id'  => $key,
								      'tier'      => 3,
								      'addedBy'   =>11
								   );
					$this->dbHandle->insert('countryCityTable', $cityAddedArray);
					$cityId = $this->dbHandle->insert_id();
  				    error_log("$cityName Added Ids is: $cityId".PHP_EOL,3,$this->activityLogFile);

  				    $virtualCityData = array('virtualCityId' => $cityId ,'city_id'=> $cityId);
  				    // add city in virtual City mapping
  				    $this->dbHandle->insert('virtualCityMapping', $virtualCityData);
  				    error_log("Added in virtual City Mapping: $cityId".PHP_EOL,3,$this->activityLogFile);
				}
			}		
		}

		
		error_log("Cities moved to telangana in countryCityTable".PHP_EOL,3,$this->activityLogFile);


		//mv cities from ap to telangana
		foreach ($this->mvCitiesIds as $value) {
			$this->dbHandle->where('city_id', $value);
			$data1 = array('state_id'=>$stateId);
			$this->dbHandle->update('countryCityTable', $data1);
			error_log("Moving CityIds:".$value.PHP_EOL,3,$this->activityLogFile);
			error_log("Rows afftected after update ".$value." Id: ".$this->dbHandle->affected_rows().PHP_EOL,3,$this->activityLogFile);         			
		}

        error_log("-----------------------------------------------".PHP_EOL,3,$this->activityLogFile);
	}


	public function categoryPageDataChanges($stateId){
		

		error_log("Table Name: categoryPageData".PHP_EOL,3,$this->activityLogFile);
		foreach ($this->mvCitiesIds as $value) {
			$this->dbHandle->where('city_id', $value);
			$this->dbHandle->where('status', 'live');
			$data = array('state_id'=>$stateId);
			$this->dbHandle->update('categoryPageData', $data);
			error_log("Moving CityIds:".$value.PHP_EOL,3,$this->activityLogFile);
			error_log("Rows afftected after update ".$value." Id: ".$this->dbHandle->affected_rows().PHP_EOL,3,$this->activityLogFile);
		}
		error_log("-----------------------------------------------".PHP_EOL,3,$this->activityLogFile);
	}

	public function SAPreferedLocationSearchCriteria($stateId){

	

		error_log("Table Name: SAPreferedLocationSearchCriteria".PHP_EOL,3,$this->activityLogFile);
		foreach ($this->mvCitiesIds as $value) {
			$this->dbHandle->where('city', $value);
			$this->dbHandle->where('is_active', 'live');
			$data = array('state'=>$stateId);
			$this->dbHandle->update('SAPreferedLocationSearchCriteria', $data);
			error_log("Moving CityIds:".$value.PHP_EOL,3,$this->activityLogFile);
			error_log("Rows afftected after update ".$value." Id: ".$this->dbHandle->affected_rows().PHP_EOL,3,$this->activityLogFile);
		}
		error_log("-----------------------------------------------".PHP_EOL,3,$this->activityLogFile);
	}	

	public function tUserLocationPrefChanges($stateId){


		error_log("Table Name: tUserLocationPref".PHP_EOL,3,$this->activityLogFile);
		foreach ($this->mvCitiesIds as $value) {
			$this->dbHandle->where('CityId', $value);
			$this->dbHandle->where('Status', 'live');
			$this->dbHandle->where('StateId', 100);
			$data = array('StateId'=>$stateId);
			$this->dbHandle->update('tUserLocationPref', $data);
			error_log("Moving CityIds:".$value.PHP_EOL,3,$this->activityLogFile);
			error_log("Rows afftected after update ".$value." Id: ".$this->dbHandle->affected_rows().PHP_EOL,3,$this->activityLogFile);
		}
		error_log("-----------------------------------------------".PHP_EOL,3,$this->activityLogFile);

	}



	//function to fetch institute ids under a state id
	function institutesUnderAState($stateId) {
		$this->initiateModel('write');
		$sql= "SELECT distinct loc.institute_id as institute_id
			   FROM institute_location_table loc 
			   JOIN countryCityTable city ON city.city_id = loc.city_id 
			   LEFT JOIN stateTable state ON state.state_id = city.state_id 
			   WHERE loc.status IN ('live','draft') 
               AND state.state_id = ?";
		
		$result = $this->dbHandle->query($sql,array($stateId))->result_array();
		return  $result;
	}

	function addACityUnderAState($stateId,$cityName,$userId) {
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		if(isset($stateId) && isset($cityName)) {
		$query = "select city_id from countryCityTable where city_name= ? AND state_id= ?";
		$cityResult = $this->dbHandle->query($query,array($cityName,$stateId))->row_array();
		
		if(!isset($cityResult['city_id'])){
			//add city
			$cityAddedArray = array(
					'city_name' => $cityName,
					'countryId' => 2 ,
					'state_id'  => $stateId,
					'tier'      => 3,
					'addedBy'   =>$userId
			);
			$this->dbHandle->insert('countryCityTable', $cityAddedArray);
			$cityId = $this->dbHandle->insert_id();
			error_log("$cityName Added Ids is: $cityId".PHP_EOL,3,$this->activityLogFile);
		
			$virtualCityData = array('virtualCityId' => $cityId ,'city_id'=> $cityId);
			// add city in virtual City mapping
			$this->dbHandle->insert('virtualCityMapping', $virtualCityData);
			if($cityId > 0) {
		    $data['status'] = "City :".$cityName." created with city id ".$cityId."under state  id:".$stateId;
			} else {
				$data['status'] = "State Id doesn't exists";
			} 
		}
		}
		if(isset($cityResult['city_id'])) {
			$data['status'] = "City :".$cityName." already exists with Id ".$cityResult['city_id']." under state id: ".$stateId;
		}
		if(!isset($stateId) || !isset($cityName)) {
			$data['status'] = "City or state Id is missing. Add it as /StateId/CityName";
		}
		$this->dbHandle->trans_complete();
		
		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
		}
		
		
		return $data;
	}
	
}
