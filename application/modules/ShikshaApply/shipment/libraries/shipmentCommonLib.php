<?php
class shipmentCommonLib {

	private $CI;
	private $shipmentLib;
	function __construct() {
            $this->CI =& get_instance();
            $this->shipmentLib = $this->CI->load->library('shipment/shipmentLib');
	}

    public function validateURL($url){
        $userEnteredUrl = trim(getCurrentPageURLWithoutQueryParams());
        if($userEnteredUrl != $url){
            header('Location: '.$url,TRUE,301);
            exit();
        }
    }

	public function prepareTrackingData($pageIdentifier,$extraData = null){
        $beaconTrackData = array(
            'pageIdentifier' => $pageIdentifier,
            'pageEntityId' => 0,
            'extraData' => $extraData
            // for shipmentConfirmation page , we have countryId , university Id(optional), course name(optional)
        );
        return $beaconTrackData;
    }

    public function checkIfUserAlreadySchdulePickups($userId){
        if(!is_int($userId) || $userId <= 0){
            return false;
        }
        $this->shipmentModel = $this->CI->load->model('shipment/shipmentmodel');
        return $this->shipmentModel->checkIfUserAlreadySchdulePickups($userId);
    }
	
	/*
	 * function to get a user's all shipment records with current tracking status
	 */
	public function getAllShipmentTrackingByUser($userId, $userShipmentId)
	{
		$populateTopSectionFlag = false;
		$this->shipmentModel = $this->CI->load->model('shipmentmodel');
		$allShipments = $this->shipmentModel->getAllShipmentTrackingByUser($userId);
		if(count($allShipments) == 0)
		{
			// nothing to show for this user
			return false;
		}
		// check if this is the case of new booking ...
		if($_SERVER['HTTP_REFERER'] == SHIKSHA_STUDYABROAD_HOME."/apply/shipment/shipping-information" &&
		   $userShipmentId > 0)
		{
			// .. then remember to extract info for top section using given shipment id
			$populateTopSectionFlag = true;
		}
		$returnData = $failData = array();
		$this->shipmentLib = $this->CI->load->library("shipmentLib");
		foreach($allShipments as $shipment)
		{
			// last booking found...
			if($populateTopSectionFlag && $userShipmentId == $shipment['id'])
			{
				// set their data for top section
				$returnData['topSection'] = array('AWBNumber' => $shipment['AWBNumber'],
												  'shipmentId' => $shipment['shipmentId'],
												  'pickUpId' => $shipment['pickUpId'],
												  'price' => $shipment['price'],
												  'attachmentPDFUrl' => $shipment['attachmentPDFUrl']);
			}
			// get tracking status from DHL api
			$trackingResponse[$shipment['AWBNumber']] = $this->shipmentLib->shipmentTracking(array('AWBNumber'=>$shipment['AWBNumber'],'userId'=>$userId));
			
			$tableRow =	array( 	'AWBNumber' => $shipment['AWBNumber'],
								'pickUpId' => $shipment['pickUpId'],
								'destinationUniversityName' => $shipment['destinationUniversityName'],
								'lastModified' => $shipment['lastModified'],
								'shipmentStatus' => $shipment['shipmentStatus']);
			
			if($trackingResponse[$shipment['AWBNumber']]['statusCode'] == "200OK")
			{
				$returnData['tableData'][] = $tableRow;
			}
			else{
				$failData[] = $tableRow;
			}
		}
		// if(count($failData)>0)
		// {
		// 	$lib = $this->CI->load->library("common/studyAbroadCommonLib");
		// 	$lib->selfMailer("Shipment tracking status unavailable", print_r($failData,true));
		// }
		$returnData['trackingStatus'] = $trackingResponse;
		return $returnData;
	}
	/*
	 * function to get extra data for beacon track
	 */
	public function getExtraDataForBeaconTrack($shipmentId)
	{
		$data = array();
		if($shipmentId>0){
			$this->shipmentModel = $this->CI->load->model('shipmentmodel');
			$extraData = $this->shipmentModel->getTrackingDataForConfirmationPage($shipmentId);
			if($extraData['courseId']>0)
			{
				$data['courseId'] = $extraData['courseId'];
			}
			if($extraData['destinationCountryId']>0)
			{
				$data['countryId'] = $extraData['destinationCountryId'];
			}
			if($extraData['universityId']>0)
			{
				$data['universityId'] = $extraData['universityId'];
			}
		}
		return $data;
	}
//	public function postalCodeRequired($countryCode){
//            $countries = array('AO','BB','BJ');
////            _p($countryCode);
//            if(in_array($countryCode, $countries)){
//                return false;
//            }
//            return true;
//        }



    public function getDHLCityId($userCity){
        $model = $this->CI->load->model('shipmentmodel');
        $id = $model->getDHLCityId($userCity);
        if(!empty($id)){
            return $id;
        }
        return -1;
    }

    public function getPickupDataForCity($dhlCityId){
        $model = $this->CI->load->model('shipmentmodel');
        $data = $model->getPickupDataForCity($dhlCityId);
        return $data;
    }

    public function getDHLPickupTimeDetails($cityDetails){
        $curHour =  (integer)date('G');   //24 hour format
        $curMinute = (integer)date('i');
        if($curHour < 15 || ($curHour == 15 && $curMinute <= 30)){  // Today if not sunday, else monday
            if(date('l') !== 'Sunday'){
                $pickupDate = date('l, d F Y');    
            }else{
                $pickupDate = date('l, d F Y','+1 day');    
            }
        }else{  // Tomorrow if not saturday, else monday
            if(date('l') !== 'Saturday'){
                $pickupDate = date('l, d F Y',strtotime('+1 day'));
            }else{
                $pickupDate = date('l, d F Y','+2 day');
            }
        }
        if($cityDetails['isDHLCity'] == "1"){
            $times['PT16H00M:PT18H00M'] = '4PM to 6PM';
            $times['PT18H00M:PT20H00M'] = '6PM to 8PM';
        }else{
            $times['PT16H00M:PT20H00M'] = '4PM to 8 PM';
        }
        $result = array('pickupDate' => $pickupDate, 'times' => $times);
        return $result;
    }

    public function getDHLCityData(){
        $model = $this->CI->load->model('shipmentmodel');
        $data = $model->getDHLCityData();
        return $data;
    }
    public function validateInput($data,$fieldArray){
            foreach ($fieldArray as $fieldName=>$field){
                if(empty($data[$fieldName])){
                    return false;
                }
                if(is_array($field)){
                    if(!$this->validateInput($data[$fieldName],$field)){
                        return false;
                    }
                }
            }
            return true;
   }

public function shipmentBookedEmailNotification($contentArray){
		$fieldArray = array(
				'AWBNumber'=>true,
				'name'=>true,
				'pickUpId'=>true,
				'pickUpDate'=>true,
				'pickUpTime'=>true,
				'price'=>true,
				'emailId'=>true,
				'attachment'=>array(	
				   'url'=>true,
				   'name'=>true,
				),
			);
		if($this->validateInput($contentArray,$fieldArray)){
			$contentArray['bccEmail'] = 'simrandeep.singh@shiksha.com';
			Modules::run('systemMailer/SystemMailer/bookedShipmentMailer',$contentArray);
			return true;
		}else{
			return false;
		}
	}

	public function shipmentDeliveredEmailNotification($contentArray){
		$fieldArray = array(
				'AWBNumber'=>true,
				'name'=>true,
				'lastDHLUpdate'=>true,
				'emailId'=>true
			);
		if($this->validateInput($contentArray,$fieldArray)){
			$contentArray['bccEmail'] = 'simrandeep.singh@shiksha.com';
			Modules::run('systemMailer/SystemMailer/deliveredShipmentMailer',$contentArray);
			return true;
		}else{
			return false;
		}
	}

	public function shipmentBookedSMSNotification($contentArray){
		$fieldArray = array(
				'AWBNumber'=>true,
				'pickUpId'=>true,
				'userId'=>true,
				'mobile'=>true
			);
		if($this->validateInput($contentArray,$fieldArray)){
			$this->alerts_client = $this->CI->load->library('alerts_client');
		    $smsText = "Your order is successfully placed with Shiksha-DHL express. Your waybill number is ".$contentArray['AWBNumber']." and pickup id is ".$contentArray['pickUpId'].". Check your email for details.";
	    	$smsResponse = $this->alerts_client->addSmsQueueRecord('12',$contentArray['mobile'],$smsText,$contentArray['userId']);
	    	return $smsResponse;	
		}else{
			return false;
		}
	}

	public function shipmentDeliveredSMSNotification($contentArray){
		$fieldArray = array(
				'AWBNumber'=>true,
				'pickUpId'=>true,
				'userId'=>true,
				'mobile'=>true
			);
		if($this->validateInput($contentArray,$fieldArray)){
			$this->alerts_client = $this->CI->load->library('alerts_client');
		    $smsText = "Your waybill number ".$contentArray['AWBNumber']." is successfully delivered with Shiksha-DHL express. Please share your feedback with us at ".SHIPMENT_DHL_FEEDBACK;
	    	$smsResponse = $this->alerts_client->addSmsQueueRecord('12',$contentArray['mobile'],$smsText,$contentArray['userId']);
	    	return $smsResponse;
		}else{
			return false;
		}
}

	public function getIsdCodeData($destinationCountries){
        $destinationCountryIds = array();
        foreach ($destinationCountries as $key => $value) {
            $destinationCountryIds[] = $value['countryId'];
        }
        //_p($destinationCountryIds);die;
        unset($destinationCountries);
		$this->isdCodeLib = new \registration\libraries\FieldValueSources\IsdCode;
		$params = array();
		$params['source']='DB';
        $isdCodesResult = $this->isdCodeLib->getValues($params);
        //_p($isdCodesResult);die;
        $isdCodes = array();
        foreach ($isdCodesResult as $key => $value) {
            if(in_array($value['shiksha_countryId'], $destinationCountryIds)){
                $isdCodes[$value['shiksha_countryId']] = array(
                                                    'name' => $value['shiksha_countryName'].' (+'.$value['isdCode'].')',
                                                    'isdCode' => $value['isdCode']
                                                    );
            }
        }
        $isdCodeWithCountry = array();
        foreach ($destinationCountryIds as $key => $countryCode) {
            if(empty($isdCodes[$countryCode])){
                unset($destinationCountryIds[$key]);
                continue;
            }
            $isdCodeWithCountry[$countryCode] = $isdCodes[$countryCode];
        }
        return $isdCodeWithCountry;
	}

        public function placeOrderWithDHL($data){
            $quoteResponse = $this->getQuote($data);
            $returnArray = array();
            if($quoteResponse['status']!=SHIPMENT_SUCCESS){
                return $quoteResponse;
            }
            $returnArray['shippingCharges'] = $quoteResponse['shippingCharges'];
            $validationResponse        = $this->getValidation($data, $returnArray);
            if($validationResponse['status']!=SHIPMENT_SUCCESS){
                return $validationResponse;                
            }

            $returnArray['AWBNumber']  = $validationResponse['awbNumber'];
            $returnArray['attachmentUrl'] = $validationResponse['attachment'];
//            $returnArray['AWBNumber']  = '2972517531';
//            $returnArray['attachmentUrl'] = 'asdasasdasdasas';
            $pickupResponse    = $this->bookPickup($data, $returnArray);
            if($pickupResponse['status']!='success'){
                return $pickupResponse;
            }
            $returnArray['pickupId'] = $pickupResponse['ConfirmationNumber'];
            $returnArray['NextPickupDate'] = $pickupResponse['NextPickupDate'];
            $returnArray['status']   = SHIPMENT_SUCCESS;
            $returnArray['statusCode']   = SHIPMENT_SUCCESS_CODE;
            $returnArray['description'] = 'Order placed successfully';
            return $returnArray;
        }
        private function _prepareRequestDataForQuote(&$data){
            $requestData = array();
            $requestData['From']['CountryCode'] = 'IN';
            $requestData['From']['Postalcode']  = $data['sender']['PostalCode'];
            $requestData['To']['CountryCode']   = $data['receiver']['CountryCode'];
            // small 'c' in postalCode is required
            if(!empty($data['receiver']['PostalCode'])){
                $requestData['To']['Postalcode']    = $data['receiver']['PostalCode'];
            }
            if(!empty($data['receiver']['City'])){
                $requestData['To']['City'] = $data['receiver']['City'];
            }
            $requestData['Weight']      = $data['shipmentDetails']['Weight'];
            $requestData['PickupDate']  = $data['shipmentDetails']['PickupDate'];
            $requestData['ReadyTime']   = $data['shipmentDetails']['ReadyTime']['quote'];
            $requestData['userId']      = $data['userId'];
            return $requestData;
        }
        public function _prepareRequstDataForValidation(&$data,$previousResponseData){
            $requestData = array();
            $requestData['Consignee']['UniversityName'] = $data['receiver']['UniversityName'];
            $requestData['Consignee']['DepartmentName'] = $data['receiver']['DepartmentName'];
            $requestData['Consignee']['AddressLine1']   = $data['receiver']['AddressLine1'];
            $requestData['Consignee']['AddressLine2']   = $data['receiver']['AddressLine2'];
            $requestData['Consignee']['City']           = $data['receiver']['City'];
            $requestData['Consignee']['State']          = $data['receiver']['State'];
            if(!empty($data['receiver']['StateCode'])){
                $requestData['Consignee']['StateCode']          = $data['receiver']['StateCode'];
            }
            if(!empty($data['receiver']['PostalCode'])){
                $requestData['Consignee']['PostalCode']          = $data['receiver']['PostalCode'];
            }
            $requestData['Consignee']['CountryCode']   = $data['receiver']['CountryCode'];
            $requestData['Consignee']['CountryName']   = $data['receiver']['CountryName'];
            $requestData['Consignee']['Contact']['PersonName']    = $data['receiver']['Name'];
            $requestData['Consignee']['Contact']['PhoneNumber']   = $data['receiver']['PhoneNumber'];
            if(!empty($data['receiver']['PhoneExtension'])){
                $requestData['Consignee']['Contact']['PhoneExtension']= $data['receiver']['PhoneExtension'];
            }
            $requestData['ShipmentDetails']['Date']               = $data['shipmentDetails']['PickupDate'];
            $requestData['ShipmentDetails']['ShipmentCharges']    = $previousResponseData['shippingCharges'];
            $requestData['ShipmentDetails']['Weight']             = $data['shipmentDetails']['Weight'];
            
            $requestData['ShipperDetails']['City']                = $data['sender']['City'];
            $requestData['ShipperDetails']['State']               = $data['sender']['State'];
            $requestData['ShipperDetails']['AddressLine1']        = $data['sender']['AddressLine1'];
            $requestData['ShipperDetails']['AddressLine2']        = $data['sender']['AddressLine2'];
            $requestData['ShipperDetails']['PostalCode']          = $data['sender']['PostalCode'];
            $requestData['ShipperDetails']['Contact']['PersonName']= $data['sender']['Name'];
            $requestData['ShipperDetails']['Contact']['PhoneNumber']= $data['sender']['Contact'];
            
            $requestData['userId']  = $data['userId'];
            return $requestData;
        }
        
        private function _prepareRequestDataForPickUp($data,$previousResonseData){
            $pickup = array();
            $pickup['AddressLine1'] = $data['sender']['AddressLine1'];
            $data['sender']['AddressLine2'] = trim($data['sender']['AddressLine2']);
            if(!empty($data['sender']['AddressLine2'])){
                $pickup['AddressLine2'] = $data['sender']['AddressLine2'];
            }
            $pickup['City']         = $data['sender']['City'];
            $pickup['State']        = $data['sender']['State'];
            $pickup['PostalCode']   = $data['sender']['PostalCode'];
            $pickup['PickupDate']   = $data['shipmentDetails']['PickupDate'];
            $pickup['ReadyByTime']  = $data['shipmentDetails']['ReadyTime']['pickup'];
            $pickup['CloseTime']    = $data['shipmentDetails']['CloseTime'];
            $pickup['PersonName']   = $data['sender']['Name'];
            $pickup['Phone']        = $data['sender']['Contact'];
            $pickup['AWBNumber']    = $previousResonseData['AWBNumber'];
            $pickup['Weight']       = $data['shipmentDetails']['Weight'];
            
            return array('pickup'=>$pickup,'userId'=>$data['userId']);
        }
        
        
        public function getQuote($data){
            
            $requestData = $this->_prepareRequestDataForQuote($data);
            $response    = $this->shipmentLib->shipmentCapabilityandQuote($requestData);
            return $response;
        }
        
        public function getValidation($data,$previousResponseData){
            $requestData = $this->_prepareRequstDataForValidation($data, $previousResponseData);
            $response    = $this->shipmentLib->shipmentValidation($requestData);
            return $response;
        }
        public function bookPickup($data,$previousResonseData){
            $requestData = $this->_prepareRequestDataForPickUp($data, $previousResonseData);
            $response    = $this->shipmentLib->schedulePickup($requestData);
            return $response;
        }
        
        public function prepareOrderRequest($postData,$validateuser){
            //trim everything
            $requestData = array();
            $requestData['sender']['Name']          = $postData['sender']['firstname'].$postData['sender']['lastname'];
            $requestData['sender']['AddressLine1']  = $postData['sender']['addrLine1'];
            $requestData['sender']['AddressLine2']  = $postData['sender']['addrLine2'];
            $requestData['sender']['City']          = $postData['sender']['pickupCity'];
            $requestData['sender']['State']         = $postData['sender']['pickupState'];
            $requestData['sender']['PostalCode']    = $postData['sender']['pickupPincodes'];
            $requestData['sender']['Contact']       = $validateuser['mobile'];
            
            $requestData['receiver']['Name']          = $postData['receiver']['departmentName'];
            $requestData['receiver']['PhoneNumber']   = $postData['receiver']['isdCode'].'-'.$postData['receiver']['receiverPhoneNo'];
            $requestData['receiver']['PhoneExtension']= $postData['receiver']['extension'];
            $requestData['receiver']['UniversityName']= $postData['receiver']['universityName'];
            $requestData['receiver']['DepartmentName']= $postData['receiver']['departmentName'];
            $requestData['receiver']['AddressLine1']  = $postData['receiver']['destAddrLine1'];
            $requestData['receiver']['AddressLine2']  = $postData['receiver']['destAddrLine2'];
            $requestData['receiver']['PostalCode']    = $postData['receiver']['destPostalCode'];
            $requestData['receiver']['City']          = $postData['receiver']['destCity'];
            
            $requestData['receiver']['State']         = $postData['receiver']['destState'];
            $requestData['receiver']['StateCode']     = $postData['receiver']['stateCode'];
            $requestData['receiver']['CountryCode']   = $postData['receiver']['countryCode'];
            $requestData['receiver']['CountryName']   = $postData['receiver']['countryName'];
            $requestData['receiver']['UniversityId']  = $postData['receiver']['universityId'];
            $requestData['receiver']['CourseId']      = $postData['receiver']['courseId'];
            date('Y-m-d',strtotime(trim($fromFields['pickupDate'])));
            $requestData['shipmentDetails']['PickupDate'] = date('Y-m-d',strtotime(trim($postData['sender']['pickupDate'])));
            $time = explode(':',$postData['sender']['pickupTime']);
//            PT10H21M
            $requestData['shipmentDetails']['ReadyTime']['quote']  = $time[0];
            $requestData['shipmentDetails']['ReadyTime']['pickup'] = $this->getValidTime($time[0]);
            $requestData['shipmentDetails']['CloseTime']           = $this->getValidTime($time[1]);
            $requestData['shipmentDetails']['Weight']              = trim(str_replace('KG', '', $postData['receiver']['weight']));
            $requestData['userId']                                 = $validateuser['userid'];
            
            return $requestData;
        }
        //convert PT10H21M to 10:21
        private function getValidTime($time){
            return $time[2].$time[3].":".$time[5].$time[6];
        }

	public function getDestinationCountries(){
		$this->shipmentModel = $this->CI->load->model('shipmentmodel');
		$result = $this->shipmentModel->getDestinationCountries();
		$shikshaCountryCodes = array();
		foreach ($result as $key => $value) {
            if($value['shikshaCountryId'] == 2){
                unset($result[$key]);
                continue;
            }
			$shikshaCountryCodes[] = $value['shikshaCountryId'];
		}
		$countriesName = $this->shipmentModel->getCountriesName($shikshaCountryCodes);
		$countryIdToNameMapping = array();
		foreach ($countriesName as $key => $countryDetails) {
			$countryIdToNameMapping[$countryDetails['countryId']] = $countryDetails['name'];
		}
		$destinationCountriesDetails = array();
		foreach ($result as $key => $value) {
			$destinationCountriesDetails[] = array(
				'countryId' 	=> $value['shikshaCountryId'],
				'countryCode'	=> $value['countryCode'],
				'postCodeRequired'	=> $value['postCodeRequired'],
				'countryName'		=> $countryIdToNameMapping[$value['shikshaCountryId']]
			);
		}
        usort($destinationCountriesDetails, function($a, $b) {
            return ($a['countryName'] <= $b['countryName']) ?-1:1;
        });
		return $destinationCountriesDetails;
	}
        
        public function saveShipmentData($postData,$response,$validateuser){
            if(!empty($response['AWBNumber']) && !empty($response['pickupId']) && !empty($response['shippingCharges'])){
                $postData['sender']['pickupDate'] = date('Y-m-d',strtotime(trim($postData['sender']['pickupDate'])));
                $time = explode(':',$postData['sender']['pickupTime']);
                $pickupFrom  = $this->getValidTime($time[0]);
                $pickupUpto  = $this->getValidTime($time[1]);
                $this->CI->load->config('shipment/shipmentConfig');
                
                $pickupTimeToDbEnumMapping = $this->CI->config->item('pickupTimeToDbEnumMapping');
                
                $postData['sender']['pickupTime'] = $pickupTimeToDbEnumMapping[$pickupFrom.'-'.$pickupUpto];
                $this->shipmentModel = $this->CI->load->model('shipmentmodel');
                $userShipmentInsertId = $this->shipmentModel->saveShipmentData($postData,$response,$validateuser);
                if($userShipmentInsertId){
                    return $userShipmentInsertId;
                }
            }
        }
        
        public function sendNotifications($postData,$response,$validateuser){
            $contentArray = array();
            $contentArray['AWBNumber'] = $response['AWBNumber'];
            $contentArray['name']      = $postData['sender']['firstname']." ".$postData['sender']['lastname'];
            $contentArray['pickUpId']  = $response['pickupId'];
            $contentArray['pickUpDate']= date('d F Y',strtotime(trim($postData['sender']['pickupDate'])));
            $time = explode(':',$postData['sender']['pickupTime']);
            $contentArray['pickUpTime']= 'between '.((int)$time[0][3]-2).'pm and '.((int)$time[0][3]).'pm';
            $cookiestr = $validateuser['cookiestr'];
            $cookiestr = explode("|",$cookiestr);
            $contentArray['emailId']   = $cookiestr[0];
            $contentArray['mobile']    = $validateuser['mobile'];
            $contentArray['attachment']['url']= MEDIAHOSTURL.$response['attachmentUrl'];
	    $contentArray['attachment']['name']= 'ShipmentAirwayBillNumber';
            $contentArray['userId']    = $validateuser['userid'];
            $contentArray['price']     = $response['shippingCharges'];
            $this->shipmentBookedEmailNotification($contentArray);
            $this->shipmentBookedSMSNotification($contentArray);
        }

    public function getUSStateDetails(){
    	$this->shipmentModel = $this->CI->load->model('shipmentmodel');
		$result = $this->shipmentModel->getUSStateDetails();
		$USStateDetails = array();
		foreach ($result as $key => $value) {
			$USStateDetails[$value['stateCode']] = $value['stateName'];
		}
		return $USStateDetails;
    }
    public function validCountryOfUniversity($countryId,$universityId){
        $this->CI->load->builder('listing/ListingBuilder');
        $listingBuilder = new ListingBuilder();
        $universityRepo = $listingBuilder->getUniversityRepository();
        $universityObj  = $universityRepo->find($universityId);
        $location       = $universityObj->getLocation();
        if(!empty($universityObj) && !empty($location)){
            $countryIdFromRepo = $location->getCountry()->getId();
        }
        if($countryIdFromRepo==$countryId){
            return true;
        }
        return false;
    }
}
