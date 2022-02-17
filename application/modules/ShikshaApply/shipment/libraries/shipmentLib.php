<?php

class shipmentLib{
    private $CI;
    private $shipmentRequestResponseManager;
    function __construct() {
        $this->CI  = &get_instance();
        $this->CI->load->config('shipment/shipmentConfig');
        $this->shipmentRequestResponseManager = $this->CI->load->library('shipment/shipmentRequestResponseManager');
    }
    public function shipmentCapabilityandQuote($requestData){
        $requiredField = array(
            'PickupDate'=>true,
            'ReadyTime'=>true,
            'From'=>true,
            'To'=>true,
            'Weight'=>true,
            'userId'=>true,
        );
        // require name of the key for postal code data as 'Postalcode'.
        if(!$this->_validateInput($requestData, $requiredField)
           || !$this->_validLocationForQuote($requestData['From']) 
           || !$this->_validLocationForQuote($requestData['To'])
          ){
            $description = "Unexpected error occurred. Try again with correct information.";
            return $this->_invalidInputDataError($description);
        }
        return $this->shipmentRequestResponseManager->shipmentCapabilityandQuote($requestData);
    }
    public function shipmentValidation($requestData){
        $requiredField = array(
            "Consignee"=> array(
                'UniversityName'=>true,
                'DepartmentName'=>true,
                'AddressLine1'  =>true,
                'City'          =>true,
//                'PostalCode'    =>true,
                'CountryCode'   =>true,
                'CountryName'   =>true,
                'Contact'       =>array(
                    'PersonName'=>true,
                    'PhoneNumber'=>true,
                ),
            ),
            "ShipmentDetails"=>array(
              'Date'=>true,
              'ShipmentCharges'=>true,
              'Weight'=>true,
            ),
            "ShipperDetails"=>array(
                'City' =>true,
                'State'=>true,
                'AddressLine1'=>true,
                'PostalCode'=>true,
                'Contact'=>array(
                    'PersonName'=>true,
                    'PhoneNumber'=>true,
                )
            ),
            'userId'=>true,
        );
        if(!$this->_validateInput($requestData, $requiredField) || !$this->_checkIfDivisionRequiredandGiven($requestData)
            || !$this->_checkIfPostalCodeRequiredandGiven($requestData)
          ){
            $description = "Unexpected error occurred. Try again with correct information.";
            return $this->_invalidInputDataError($description);
        }
        return $this->shipmentRequestResponseManager->shipmentValidation($requestData);        
    }

    
    public function schedulePickup($requestData){
        $requiredField = array(
            'pickup' =>array(
                'AddressLine1'  =>true,
                'AddressLine2'  =>true,
                'City'   =>true,
                'State'  =>true,
                'PostalCode'=>true,
                'PickupDate'=>true,
                'ReadyByTime'=>true,
                'CloseTime'=>true,
                'PersonName'=>true,
                'Phone'=>true,                
                'AWBNumber'=>true,
                'Weight'=>true
            ),
            'userId'=>true,
        );
        if(empty($requestData['AddressLine2'])){
            unset($requiredField['pickup']['AddressLine2']);
        }
        if(!$this->_validateInput($requestData, $requiredField)){
            $description = "Unexpected error occurred. Try again with correct information.";
            return $this->_invalidInputDataError($description);
        }
        return $this->shipmentRequestResponseManager->schedulePickup($requestData);
    }
    
    
    public function shipmentTracking($requestData){
        $requiredField = array(
            'AWBNumber'=>true,
            'userId'=>true
        );
        if(!$this->_validateInput($requestData, $requiredField) || strlen($requestData['AWBNumber'])>10 ){
            $description = "Unexpected error occurred. Try again with correct information.";
            return $this->_invalidInputDataError($description);
        }
        return $this->shipmentRequestResponseManager->shipmentTracking($requestData);
    }
    
    private function _invalidInputDataError($description){
        $returnArray = array();
        $returnArray['status']          = SHIPMENT_INVALID_INPUT_ERROR;
        $returnArray['statusCode']      = SHIPMENT_INVALID_INPUT_ERROR_CODE;
        $returnArray['description']     = $description;
        return $returnArray;
    }
    private function _validLocationForQuote($location){
        if(empty($location) || empty($location['CountryCode'])){
            return false;
        }
        $postalCodeRequired = $this->postalCodeRequired($location['CountryCode']);
        if($postalCodeRequired && !empty($location['Postalcode']) 
          || !$postalCodeRequired && empty($location['Postalcode']) && !empty($location['City'])
          ){
               return true;
        }
        return false;
    }
    private function _validateInput($data,$fieldArray){
        foreach ($fieldArray as $fieldName=>$field){
            
            if(empty($data[$fieldName])){
                return false;
            }
            if(is_array($field)){
                if(!$this->_validateInput($data[$fieldName],$field)){
                    return false;
                }
            }
        }
        return true;
    }
    private function _checkIfDivisionRequiredandGiven($requestData){
        if($requestData['Consignee']['CountryCode']=='US' && empty($requestData['Consignee']['StateCode'])){
            return false;
        }
        return true;
    }
    private function _checkIfPostalCodeRequiredandGiven($requestData){
        if($this->postalCodeRequired($requestData['Consignee']['CountryCode'])
            && empty($requestData['Consignee']['PostalCode'])
          ){
            return false;
        }
        return true;
    }
    
    private function postalCodeRequired($countryCode){
        $this->shipmentmodel = $this->CI->load->model('shipment/shipmentmodel');
        if($this->shipmentmodel->postalCodeRequired($countryCode)){
            return true;
        }
        return false;
    }
    
}


