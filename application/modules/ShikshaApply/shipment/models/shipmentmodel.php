<?php
class shipmentmodel extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct() {
		parent::__construct('Listing');
    }

    private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}

    public function checkIfUserAlreadySchdulePickups($userId){
    	if(!is_int($userId) || $userId <= 0){
			return false;
		}
		$this->initiateModel();
		$this->dbHandle->select('id');
		$this->dbHandle->from('userShipments');
		$this->dbHandle->where('userId',$userId);
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		if(count($result)){
			return true;
		}else{
			return false;
		}
    }
	
	/*
	 * function to get shipments details for all shipments of a given user
	 * @params : $userId
	 */
	public function getAllShipmentTrackingByUser($userId)
	{
		$this->initiateModel("read");
		$this->dbHandle->select('us.id, us.shipmentId, us.userId, us.shipmentStatus, us.creationDate, us.lastModified');
		$this->dbHandle->select('sd.AWBNumber, sd.pickUpId, sd.price, sd.attachmentPDFUrl, sd.destinationUniversityName');
		$this->dbHandle->from('userShipments us');
		$this->dbHandle->join('shipmentDetails sd','us.shipmentId=sd.shipmentId','inner');
		$this->dbHandle->where('us.userId',$userId);
		$this->dbHandle->where('us.status','live');
		$this->dbHandle->order_by('us.creationDate','desc');
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}
	/*
	 * to get beacon track data params for confirmation page:
	 * 	courseId, universityId, countryId (if available)
	 * 	@params : shipmentId
	 */
	public function getTrackingDataForConfirmationPage($shipmentId)
	{
		$this->initiateModel("read");
		$this->dbHandle->select('sd.shipmentId, sd.destinationCountryId, sat.universityId, sat.courseId');
		$this->dbHandle->from('shipmentDetails sd');
		$this->dbHandle->join('shipmentAutosuggestorTracking sat','sd.shipmentId=sat.shipmentId','left');
		$this->dbHandle->where('sd.shipmentId',$shipmentId);
		$result = $this->dbHandle->get()->result_array();
		return reset($result);
	}
        
        
        public function errorTracking($insertData){
                $this->initiateModel('write');
                $this->dbHandle->insert('shipmentResponseErrorTracking',$insertData);
        }

    public function getDHLCityId($userCity){
    	if(empty($userCity)){
    		return '';
    	}
    	$this->initiateModel();
    	$this->dbHandle->select('id');
    	$this->dbHandle->from('shipmentDHLPickupCities');
    	$this->dbHandle->where('cityName',$userCity);
    	return reset(reset($this->dbHandle->get()->result_array()));
    }

    public function getPickupDataForCity($dhlCityId){
    	if(empty($dhlCityId)){
    		return array();
    	}
    	$this->initiateModel();
    	$this->dbHandle->select("st.state_name");
    	$this->dbHandle->select("st.state_id");
    	$this->dbHandle->select("c.cityName");
    	$this->dbHandle->select("c.isDHLCity");
    	$this->dbHandle->from("stateTable st");
    	$this->dbHandle->join("shipmentDHLPickupCities c","c.stateId = st.state_id","inner");
    	$this->dbHandle->where("c.id",$dhlCityId);
    	$cityStateDetails = reset($this->dbHandle->get()->result_array());
    	$this->dbHandle->select("pincode");
    	$this->dbHandle->from("shipmentCityPincodeMappingDHL");
    	$this->dbHandle->where("dhlCityId",$dhlCityId);
    	$pincodeMap = $this->dbHandle->get()->result_array();
    	$res = array(
    		'id' => $dhlCityId, 
    		'name' =>$cityStateDetails['cityName'],
    		'stateName'=>$cityStateDetails['state_name'],
    		'stateId' => $cityStateDetails['state_id'],
    		'isDHLCity' => $cityStateDetails['isDHLCity'],
    		'pincodes' => array_map(function($ele){return reset($ele);}, $pincodeMap)
    	);
    	return $res;
    }

    public function getDHLCityData(){
    	$this->initiateModel();
    	$this->dbHandle->select("st.state_name");
    	$this->dbHandle->select("st.state_id");
    	$this->dbHandle->select("c.cityName");
    	$this->dbHandle->select("c.isDHLCity");
    	$this->dbHandle->select("c.id");
    	$this->dbHandle->from("stateTable st");
    	$this->dbHandle->join("shipmentDHLPickupCities c","c.stateId = st.state_id","inner");
    	$cityStateDetails = $this->dbHandle->get()->result_array();

    	$this->dbHandle->select("pincode");
    	$this->dbHandle->select("dhlCityId");
    	$this->dbHandle->from("shipmentCityPincodeMappingDHL");
    	$pincodeMap = $this->dbHandle->get()->result_array();

    	$finalResult = array();
    	foreach($cityStateDetails as $row){
    		$finalResult[$row['id']] = array(
    			'id' => $row['id'],
    			'name' => $row['cityName'],
    			'stateName' => $row['state_name'],
    			'stateId' => $row['state_id'],
    			'isDHLCity' => $row['isDHLCity']
    		);
    	}

    	foreach($pincodeMap as $row){
    		$finalResult[$row['dhlCityId']]['pincodes'][] = $row['pincode'];
    	}
    	return $finalResult;
    }

    public function getDestinationCountries(){
        $this->initiateModel('read');
        $this->dbHandle->select('*');
        $this->dbHandle->from('shipmentCountryCodes');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

     public function getCountriesName($shikshaCountryCodes){
        if(!is_array($shikshaCountryCodes) || !(count($shikshaCountryCodes) >0)){
            return array();
        }
        $this->initiateModel('read');
        $this->dbHandle->select('countryId,name');
        $this->dbHandle->from('countryTable');
        $this->dbHandle->where_in('countryId',$shikshaCountryCodes);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }
    public  function saveShipmentData($postData,$response,$validateuser){
        
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        //shipmentDetails
        $insertData = array();
        $insertData['firstName']                    = $postData['sender']['firstname'];
        $insertData['lastName']                     = $postData['sender']['lastname'];
        $insertData['AWBNumber']                    = $response['AWBNumber'];
        $insertData['pickUpId']                     = $response['pickupId'];
        $insertData['price']                        = $response['shippingCharges'];
        $insertData['weight']                       = $postData['receiver']['weight'];
        $insertData['attachmentPDFUrl']             = $response['attachmentUrl'];
        $insertData['pickUpAddress1']               = $postData['sender']['addrLine1'];
        if(!empty($postData['sender']['addrLine2'])){
            $insertData['pickUpAddress2']               = $postData['sender']['addrLine2'];
        }
        
        $insertData['pickUpCityId']                 = $postData['sender']['pickupCityId'];
        $insertData['pickUpStateId']                = $postData['sender']['pickupStateId'];
        $insertData['pickUpPostalCode']             = $postData['sender']['pickupPincodes'];
        $insertData['pickUpDate']                   = $postData['sender']['pickupDate'];
        $insertData['pickUpTime']                   = $postData['sender']['pickupTime'];
        $insertData['destinationCountryId']         = $postData['receiver']['countryId'];
        $insertData['destinationUniversityName']    = $postData['receiver']['universityName'];
        $insertData['destinationDepartment']        = $postData['receiver']['departmentName'];
        $insertData['courseName']                   = $postData['receiver']['courseName'];
        $insertData['destinationAddress1']          = $postData['receiver']['destAddrLine1'];
        if(!empty($postData['receiver']['destAddrLine2'])){
            $insertData['destinationAddress2']          = $postData['receiver']['destAddrLine2'];
        }
        $insertData['destinationCity']              = $postData['receiver']['destCity'];
        $insertData['destinationState']             = $postData['receiver']['destState'];
        if(!empty($postData['receiver']['destPostalCode'])){
            $insertData['destinationPostalCode']        = $postData['receiver']['destPostalCode'];
        }
        $insertData['destinationIsdCode']           = $postData['receiver']['isdCode'];
        $insertData['destinationPhoneNumber']       = $postData['receiver']['receiverPhoneNo'];
        if(!empty($postData['receiver']['extension'])){
            $insertData['destinationExtensionNumber']   = $postData['receiver']['extension'];
        }
       
        $this->dbHandle->insert('shipmentDetails',$insertData);
        $shipmentId = $this->dbHandle->insert_id();
        
        
        //user shipments
        $insertData = array();
        $insertData['shipmentId'] = $shipmentId;
        $insertData['userId'] = $validateuser['userid'];
        $insertData['shipmentStatus'] = 'booked';
        $insertData['creationDate']   = date('Y-m-d H:i:s');
        $insertData['lastModified']   = date('Y-m-d H:i:s');
        $insertData['trackingKeyId']  = $postData['trackingId'];
        $insertData['visitorSessionId']= $postData['visitorSessionId'];
        
        $this->dbHandle->insert('userShipments',$insertData);
        $insertId = $this->dbHandle->insert_id();
        
        
        
        if(!empty($postData['receiver']['universityId'])){
            
            //shipmentAutosuggestorTracking
            $insertData = array();
            $insertData['shipmentId']       = $shipmentId;
            $insertData['universityId']     = $postData['receiver']['universityId'];
            if(!empty($postData['receiver']['courseId'])){
                $insertData['courseId']       = $postData['receiver']['courseId'];
            }
            $this->dbHandle->insert('shipmentAutosuggestorTracking',$insertData);
        }
        
        $this->dbHandle->trans_complete();
        if($this->dbHandle->trans_status()==true){
            return $insertId;
        }
    }

	public function getUSStateDetails(){
        $this->initiateModel();
        $this->dbHandle->select('*');
        $this->dbHandle->from('shipmentUSStateCodes');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function postalCodeRequired($countryCode){
        if(empty($countryCode))
            return false;
        $this->initiateModel('read');
        $this->dbHandle->select('postCodeRequired');
        $this->dbHandle->from('shipmentCountryCodes');
        $this->dbHandle->where('countryCode',$countryCode);
        
        $result = $this->dbHandle->get()->result_array();
        if($result[0]['postCodeRequired']==1)
            return true;
        else
            return false;
    }

    public function fetchShipments($status){
        $this->initiateModel("read");
        $this->dbHandle->select('us.shipmentId, us.userId, us.shipmentStatus, DATEDIFF(now(),lastModified)<7 as dateDiff',false);
        $this->dbHandle->select('sd.AWBNumber, sd.pickUpId, sd.firstName, sd.lastName');
        $this->dbHandle->from('userShipments us');
        $this->dbHandle->join('shipmentDetails sd','us.shipmentId=sd.shipmentId','inner');
        $this->dbHandle->where('us.status','live');
        $this->dbHandle->where('us.shipmentStatus !=',$status);
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function updateShipmentStatus($shipmentId,$status){
        $this->initiateModel("write");
        $lastModified = date('Y-m-d H:i:s');
        $data = array(
                'lastModified' => $lastModified,
                'shipmentStatus' => $status
            );
        $this->db->where('shipmentId', $shipmentId);
        $result = $this->db->update('userShipments', $data); 
        return $result;
    }

}
?>