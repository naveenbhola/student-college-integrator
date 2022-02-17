<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class shipmentRequestResponseManager{
    private $CI;
    function __construct() {
        $this->CI  = &get_instance();
        $this->CI->load->config('shipment/shipmentConfig');
        $this->CI->load->config('shipment/shipment');
    }
    //<------- Top Functions for DHL API starts  ------>
    
    public function shipmentCapabilityandQuote($requestData,$type='GetQuote'){
        $requestArray = array();
        $requestArray['Request']['ServiceHeader']    = $this->_getServiceHeader();
        $requestArray['From']                        = $requestData['From'];
        $requestArray['BkgDetails'] = $this->_getBkgDetails($requestData);
        $requestArray['To']                         = $requestData['To'];
        $requestArray                               = array($type=>$requestArray);
        $xml  = new SimpleXMLElement('<DCTRequest/>');
        to_xml($xml,$requestArray);
        $xml = $xml->asXML();
        
        $xml = str_replace('<DCTRequest>','<p:DCTRequest xmlns:p="http://www.dhl.com" xmlns:p1="http://www.dhl.com/datatypes" xmlns:p2="http://www.dhl.com/DCTRequestdatatypes" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.dhl.com DCT-req.xsd ">', $xml);
        $xml = str_replace('</DCTRequest>','</p:DCTRequest>',$xml);
        $response  = $this->_makeCurlCall($xml,SHIPMENT_SEVER,true);
        $response    = xml_to_array($response);
        $requestArray['userId'] = $requestData['userId'];
        return $this->_processShipmentCapabilityandQuoteResponse($response['GetQuoteResponse'],$requestArray);
    }
    public function shipmentValidation($requestData){
        
        $requestArray = array();
        $requestArray['Request']['ServiceHeader']    = $this->_getServiceHeader();
        $requestArray['RequestedPickupTime']    = 'Y';
        $requestArray['LanguageCode']           = 'EN';
        $requestArray['PiecesEnabled']          = 'Y';
        $requestArray['Billing']                = array('ShipperAccountNumber'  =>$this->_getAccountNumber(),
                                                        'ShippingPaymentType'   =>'S',
                                                        'BillingAccountNumber'  =>$this->_getAccountNumber(),
//                                                        'DutyPaymentType'       =>'R'  not required
                                                       );
        $requestArray['Consignee']              = $this->_getConsigneeElement($requestData['Consignee']);
        $requestArray['Reference']              = $this->_getValidationReferenceElement($requestData['ShipmentDetails']);
        $requestArray['ShipmentDetails']        = $this->_getShipmentDetailsElementForValidation($requestData['ShipmentDetails']);
        $requestArray['Shipper']                = $this->_getShipperElement($requestData['ShipperDetails']);
        $requestArray['LabelImageFormat']       = 'PDF';        
        $xml  = new SimpleXMLElement('<ShipmentRequest/>');
        to_xml($xml,$requestArray);
        $xml = $xml->asXML();
        
        $xml = str_replace('<ShipmentRequest>','<req:ShipmentRequest xmlns:req="http://www.dhl.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.dhl.com ship-val-global-req.xsd" schemaVersion="5.0">', $xml);
        $xml = str_replace('</ShipmentRequest>','</req:ShipmentRequest>',$xml);
        $xml = str_replace('AddressLine1','AddressLine',$xml);
        $xml = str_replace('AddressLine2','AddressLine',$xml);
        $response  = $this->_makeCurlCall($xml,SHIPMENT_SEVER,true);
        $response    = xml_to_array($response);
        $requestArray['userId'] = $requestData['userId'];
        return $this->_processValidationResponse($response,$requestArray);
    }
    public function schedulePickup($requestData){
        $requestArray = array();
        $requestArray['Request']['ServiceHeader']   = $this->_getServiceHeader();
        $requestArray['RegionCode']                 = SHIPMENT_PICKUP_REGION_CODE;
        $requestArray['Requestor']                  = array('AccountType'=>SHIPMENT_ACCOUNT_TYPE,'AccountNumber'=>$this->_getAccountNumber());
        $requestArray['Place']                      = $this->_getPlaceElement($requestData);
        $requestArray['Pickup']                     = $this->_getPickUpElement($requestData);
        $requestArray['PickupContact']              = $this->_getPickUpContactElement($requestData);
        $requestArray['ShipmentDetails']            = $this->_getShipmentDetailsElementForPickup($requestData);
        $xml  = new SimpleXMLElement('<BookPURequest/>');
        to_xml($xml,$requestArray);
        $xml = $xml->asXML();
        $xml = str_replace('<BookPURequest>','<req:BookPURequest xmlns:req="http://www.dhl.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.dhl.com book-pickup-global-req.xsd" schemaVersion="1.0">', $xml);
        $xml = str_replace('</BookPURequest>','</req:BookPURequest>',$xml);
        $response  = $this->_makeCurlCall($xml,SHIPMENT_SEVER,true);
        $response    = xml_to_array($response);
        $requestArray['userId'] = $requestData['userId'];
        return $this->_processPickupBookResponse($response,$requestArray);
    }
    public function shipmentTracking($requestData){
        $requestArray = array();
        $requestArray['Request']['ServiceHeader']    = $this->_getServiceHeader();
        $requestArray['LanguageCode']                = 'en';
        $requestArray['AWBNumber']                   = $requestData['AWBNumber'];
        $requestArray['LevelOfDetails']              = 'ALL_CHECK_POINTS';
        $xml  = new SimpleXMLElement('<KnownTrackingRequest/>');
        to_xml($xml,$requestArray);
        $xml = $xml->asXML();
        $xml = str_replace('<KnownTrackingRequest>','<req:KnownTrackingRequest xmlns:req="http://www.dhl.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.dhl.com TrackingRequestKnown.xsd">', $xml);
        $xml = str_replace('</KnownTrackingRequest>','</req:KnownTrackingRequest>',$xml);
        $response  = $this->_makeCurlCall($xml,SHIPMENT_SEVER,true);
        $response    = xml_to_array($response);
        $requestArray['userId'] = $requestData['userId'];
        return $this->_processTrackingResponse($response,$requestArray);
    }
    
    //<------- Top Functions for DHL API ends ------>
    
    //<------- Response Processing Functions for DHL API starts ------>
    
    private function _processShipmentCapabilityandQuoteResponse($response,$requestArray){
        $errorResponse = $this->_checkForError($response,$requestArray);
        $returnArray = array();
        if(!empty($errorResponse)){
            return $errorResponse;
        }
        $shippingCharges = '';
        $qtdShp = $response['BkgDetails']['QtdShp'];
        $shippingCharges = $qtdShp['ShippingCharge'];
        if($shippingCharges==''){
            $shippingCharges = $qtdShp[0]['ShippingCharge'];  // it is possible that there are multiple elements
        }
        
        if($shippingCharges==''){
            if(!empty($response['Note'])){
                $returnArray['statusCode']  = SHIPMENT_INVALID_DATA_CODE;
                $returnArray['status']      = SHIPMENT_INVALID_DATA_STATUS;
                $returnArray['description'] = "Postal Code/City could not be found in the selected country.";
                $returnArray['lastDHLUpdate']['statusCode'] = $response['Note']['Condition']['ConditionCode'];
                $returnArray['lastDHLUpdate']['status']     = htmlentities($response['Note']['Condition']['ConditionData']);
                return $returnArray;
            }
            return $this->_unexpectedError($requestArray);
        }
        
        $returnArray['shippingCharges'] = round($shippingCharges);
        $returnArray['statusCode']      = SHIPMENT_SUCCESS_CODE;
        $returnArray['status']          = SHIPMENT_SUCCESS;
        $returnArray['description']     = 'Price fetched successfully';
        return $returnArray;
    }
    private function _processValidationResponse($response,$requestArray){
        $errorResponse = $this->_checkForError($response,$requestArray);
        if(!empty($errorResponse)){
            return $errorResponse;
        }
        $awbNumber = $response['AirwayBillNumber'];
        $returnArray = array();
        if(empty($awbNumber)){
            $returnArray['statusCode'] = SHIPMENT_INVALID_DATA_CODE;
            $returnArray['status']     = SHIPMENT_INVALID_DATA_STATUS;
            $returnArray['description']= 'The DHL-API could not generate valid AWB number for given details';
            $returnArray['lastDHLUpdate']['statusCode'] = $response['Response']['Status']['Condition']['ConditionCode'];
            $returnArray['lastDHLUpdate']['status']     = htmlentities($response['Response']['Status']['Condition']['ConditionData']);
            return $returnArray;
        }
        $returnArray['awbNumber'] = $awbNumber;
        $returnArray['statusCode'] = SHIPMENT_SUCCESS_CODE;
        $returnArray['status'] = SHIPMENT_SUCCESS;
        $returnArray['description']= "AWB number generated successfully";
        $returnArray['attachment'] = $this->_saveAttachment($awbNumber,$response['LabelImage']['OutputImage']);
        if(!empty($response['Note']['Condition'])){
            $returnArray['lastDHLUpdate']['statusCode']     =  $response['Note']['Condition']['ConditionCode'];
            $returnArray['lastDHLUpdate']['status']    =  htmlentities($response['Note']['Condition']['ConditionData']);
        }
        
        return $returnArray;
    }
    private function _processPickupBookResponse($response,$requestArray){
        $errorResponse = $this->_checkForError($response,$requestArray);
        if(!empty($errorResponse)){
            return $errorResponse;
        }
        if(empty($response['Note']['ActionNote'])){
            return $this->_unexpectedError($requestArray);
        }
        if(strtolower($response['Note']['ActionNote'])!='success' || empty($response['ConfirmationNumber'])){
            $returnArray['statusCode'] = SHIPMENT_INVALID_DATA_CODE;
            $returnArray['status']     = SHIPMENT_INVALID_DATA_STATUS;
            $returnArray['description']= 'The DHL-API could not book a pickup for given details';
            $returnArray['lastDHLUpdate']['statusCode'] = $response['Note']['Condition']['ConditionCode'];
            $returnArray['lastDHLUpdate']['status']     = htmlentities($response['Note']['Condition']['ConditionData']);
            return $requestArray;
        }
        
        $returnArray = array();
        $returnArray['statusCode']  = SHIPMENT_SUCCESS_CODE;
        $returnArray['status']      = SHIPMENT_SUCCESS;
        $returnArray['description'] = 'Pickup booked successfully';
        $returnArray['ConfirmationNumber']  = $response['ConfirmationNumber'];
        $returnArray['NextPickupDate']      = $response['NextPickupDate'];
        $returnArray['OriginSvcArea']       = $response['OriginSvcArea'];
        if(!empty($response['Note']['Condition'])){
            $returnArray['lastDHLUpdate']['statusCode']     =  $response['Note']['Condition']['ConditionCode'];
            $returnArray['lastDHLUpdate']['status']    =  htmlentities($response['Note']['Condition']['ConditionData']);
        }
        return $returnArray;
    }
    private function _processTrackingResponse($response,$requestArray){
        $errorResponse = $this->_checkForError($response,$requestArray);
        if(!empty($errorResponse)){
            return $errorResponse;
        }        
        if($response['AWBInfo']['Status']['ActionStatus']=='No Shipments Found'){
            $eventCodes         = array_unique($eventCodes);
            $returnArray        = array();
            $returnArray['statusCode']      = $response['AWBInfo']['Status']['Condition']['ConditionCode'];
            $returnArray['status']          = 'No Shipments Found';
            $returnArray['description']     = $response['AWBInfo']['Status']['Condition']['ConditionData'];
            return $returnArray;
        }
        if(strtolower($response['AWBInfo']['Status']['ActionStatus'])!='success'){
            return $this->_unexpectedError($requestArray);
        }
        $shipmentEvent = $response['AWBInfo']['ShipmentInfo']['ShipmentEvent'];
        $eventCodes = array();
        foreach($shipmentEvent as $event){
            $eventCodes[] = $event['ServiceEvent']['EventCode'];
        }
        $eventCodes         = array_unique($eventCodes);
        $returnArray        = array();
        $returnArray['statusCode']      = SHIPMENT_SUCCESS_CODE;
        $returnArray['status']          = SHIPMENT_SUCCESS;
        $returnArray['description']     = 'Tracking success';
        $returnArray['eventCodes']      = array();
        $returnArray['eventCodes'][]    = PICKUP_BOOKED;
        foreach ($eventCodes as $code){
            switch ($code) {
                case 'PU':
                    $returnArray['eventCodes'][] = PICKUP_SUCCESS;
                break;
                case 'OK':
                    $returnArray['eventCodes'][] = DELIVERED;
                default:
                    if(in_array(PICKUP_SUCCESS,$returnArray['eventCodes']) && !in_array(IN_TRANSIT, $returnArray['eventCodes'])){
                        $returnArray['eventCodes'][] = IN_TRANSIT;
                    }
                    break;
            }
        }
        $lastEvent = end($shipmentEvent);
        $returnArray['lastDHLUpdate'] = array(
            'Date'          => $lastEvent['Date'],
            'Time'          => $lastEvent['Time'],
            'EventCode'     => $lastEvent['ServiceEvent']['EventCode'],
            'Description'   => $lastEvent['ServiceEvent']['Description'],
            'Signatory'     => $lastEvent['Signatory'],
            'Location'      => $lastEvent['ServiceArea']['Description']
        );
        return $returnArray;
        
    }
    
    //<------- Response Processing Functions for DHL API ends ------>
    
    
    //<------- Elements generating functions for DHL API starts ------>
    private function _getShipmentDetailsElementForPickup($requestData){
        $shipmentDetailsElement = array();
        $shipmentDetailsElement['AWBNumber']    = $requestData['pickup']['AWBNumber'];
//        $shipmentDetailsElement['NumberOfPieces'] = SHIPMENT_NUMBER_OF_PIECES;
        $shipmentDetailsElement['Weight']       = $requestData['pickup']['Weight'];
        $shipmentDetailsElement['WeightUnit']   = SHIPMENT_WEIGHT_UNIT;
        $shipmentDetailsElement['DoorTo']       = SHIPMENT_DOOR_TO;
        return $shipmentDetailsElement;
    }
    private function _getPickUpContactElement($requestData){
        $pickUpContactElement = array();
        $pickUpContactElement['PersonName'] = $requestData['pickup']['PersonName'];
        $pickUpContactElement['Phone']      = $requestData['pickup']['Phone'];
        return $pickUpContactElement;
    }

    private function _getPickUpElement($requestData){
        $pickUpElement = array();
        $pickUpElement['PickupDate']    = $requestData['pickup']['PickupDate'];
        $pickUpElement['ReadyByTime']   = $requestData['pickup']['ReadyByTime'];
        $pickUpElement['CloseTime']     = $requestData['pickup']['CloseTime'];
        $pickUpElement['Pieces']        = SHIPMENT_NUMBER_OF_PIECES;
        return $pickUpElement;
    }
    private function _getPlaceElement($requestData){
        $placeElement = array();
        $placeElement['LocationType'] = SHIPMENT_PICKUP_LOCATION_TYPE;
        $placeElement['Address1'] = $requestData['pickup']['AddressLine1'];
        if(!empty($requestData['pickup']['AddressLine2'])){
            $placeElement['Address2'] = $requestData['pickup']['AddressLine2'];
        }
        $placeElement['PackageLocation'] = 'Will be providing in person';
        $placeElement['City']            = $requestData['pickup']['City'];
        $placeElement['DivisionName']    = $requestData['pickup']['State'];
        $placeElement['CountryCode']     = 'IN';
        $placeElement['PostalCode']      = $requestData['pickup']['PostalCode'];
        return $placeElement;
    }
    private function _getShipperElement($shipperData){
        $shipperElement = array();
        $shipperElement['ShipperID']            = 'Shiksha';
        $shipperElement['CompanyName']          = 'Shiksha';
        $shipperElement['AddressLine1']         = $shipperData['AddressLine1'];
        if(!empty($shipperData['AddressLine2'])){
            $shipperElement['AddressLine2']         = $shipperData['AddressLine2'];
        }
        $shipperElement['City']                 = $shipperData['City'];
        $shipperElement['Division']             = $shipperData['State'];
        $shipperElement['PostalCode']           = $shipperData['PostalCode'];
        $shipperElement['CountryCode']          = 'IN';
        $shipperElement['CountryName']          = 'India';
        $shipperElement['Contact']              = $shipperData['Contact'];
        return $shipperElement;
    }
    private function _getValidationReferenceElement($requestShipmentDetails){
        $shipmentReferenceArray = array();
        $shipmentReferenceArray['ReferenceID'] = "SHPT CHARGES RS:".$requestShipmentDetails['ShipmentCharges'];
        $shipmentReferenceArray['ReferenceType'] = "FRT";
        return $shipmentReferenceArray;
    }
    private function _getShipmentDetailsElementForValidation($requestShipmentDetails){
        $shipmentDetails  = array();
        $shipmentDetails['NumberOfPieces']      = '1';
        $shipmentDetails['Pieces']              = '';
        $shipmentDetails['Pieces']              = $this->_getPieceDetails($requestShipmentDetails['Weight']);
        $shipmentDetails['Weight']              = $requestShipmentDetails['Weight'];
        $shipmentDetails['WeightUnit']          = SHIPMENT_WEIGHT_UNIT;
        $shipmentDetails['GlobalProductCode']   = SHIPMENT_PRODUCT_CODE;
        $shipmentDetails['Date']                =  $requestShipmentDetails['Date'];
        $shipmentDetails['Contents']            =  'Student Docuements for University Addmission';
        $shipmentDetails['DimensionUnit']       = 'C';
        $shipmentDetails['CurrencyCode']        = 'INR'; 
        $shipmentDetails['ShipmentCharges']     = $requestShipmentDetails['ShipmentCharges'];
        return $shipmentDetails;
    }
    private function _getConsigneeElement($requestConsigneeData){
        $consignee = array();
        $consignee['CompanyName'] = $requestConsigneeData['UniversityName'];
        if(!empty($requestConsigneeData['DepartmentName'])){
            $consignee['SuiteDepartmentName'] = $requestConsigneeData['DepartmentName'];
        }
        $consignee['AddressLine1']  = $requestConsigneeData['AddressLine1'];
        if(!empty($consignee['AddressLine2'])){
            $consignee['AddressLine2']  = $requestConsigneeData['AddressLine2'];
        }
        $consignee['City']          = $requestConsigneeData['City'];
        if(!empty($requestConsigneeData['StateCode'])){
            $consignee['Division']              = $requestConsigneeData['State'];
            $consignee['DivisionCode']          = $requestConsigneeData['StateCode'];
        }
        $consignee['PostalCode']     = $requestConsigneeData['PostalCode'];
        $consignee['CountryCode']    = $requestConsigneeData['CountryCode'];
        $consignee['CountryName']    = $requestConsigneeData['CountryName'];
        $consignee['Contact']        = $requestConsigneeData['Contact'];
        
        return $consignee;
    }
    private function _getServiceHeader(){
        $serviceHeader = array();
        $messageTime = date('Y-m-d H:i:s', time());
        $messageTime = str_replace(' ','T',$messageTime).'-05:30';
        $serviceHeader['MessageTime']       = $messageTime;
        $serviceHeader['MessageReference']  = 'shikshaShip'.time().rand().'';
        $siteId = $this->CI->config->item('DHLsiteId');
        $password = $this->CI->config->item('DHLpassword');

        $serviceHeader['SiteID']            = $siteId;
        $serviceHeader['Password']          = $password;
//        $serviceHeader['SiteID']            = 'InfoEdge';
//        $serviceHeader['Password']          = 'AZVh48kcCa';
        return $serviceHeader;
    }
    
    private function _getAccountNumber(){
        $accountNumber = $this->CI->config->item('DHLaccountNo');
//        $accountNumber = 'CASHINIEI';
        return $accountNumber;
    }
    
    private function _getBkgDetails(&$requestData){
        $bkgDetails = array();
        $bkgDetails['PaymentCountryCode']   = 'IN';
        $bkgDetails['Date']                 = $requestData['PickupDate'];
        $bkgDetails['ReadyTime']            = $requestData['ReadyTime'];
        $bkgDetails['DimensionUnit']        = 'CM';
        $bkgDetails['WeightUnit']           = SHIPMENT_WEIGHT_UNIT_FOR_QUOTE;
        $bkgDetails['Pieces']               = array(
                'Piece'=> array(
                    'PieceID'=>'1',
                    'Height'=>'1',
                    'Depth'=>'1',
                    'Width'=>'1',
                    'Weight'=>$requestData['Weight']
                )
        );
        $bkgDetails['PaymentAccountNumber'] = $this->_getAccountNumber();
        $bkgDetails['IsDutiable']           = 'N';
        $bkgDetails['QtdShp']               = array(
                'GlobalProductCode'=>SHIPMENT_PRODUCT_CODE,
                'LocalProductCode' =>SHIPMENT_PRODUCT_CODE
        );
        return $bkgDetails;
    }
    private function _getPieceDetails($weight){
        $piece = array(
                'Piece'=> array(
                    'PieceID'=>'1',
                    'Weight'=>$weight,
                    'Width'=>'1',
                    'Height'=>'1',
                    'Depth'=>'1',
                )
            );
        return $piece;
    }
    
    //<------- Elements generating functions for DHL API starts ------>
    
    //<------- Other Helper functions for DHL API starts ------>
    
    private function _makeCurlCall($post_array,$url,$useLive=false){
        $posst_array= '';
        $c = curl_init();
        if(!$useLive){
            curl_setopt($c, CURLOPT_URL,$url);
        }
        else{
            curl_setopt($c, CURLOPT_URL,'https://xmlpi-ea.dhl.com/XMLShippingServlet');
        }
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c,CURLOPT_POSTFIELDS,$post_array);
        /*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); */
        $output =  curl_exec($c);
        curl_close($c);
        return $output;
    }
    
    
    private function _checkForError($response,$requestArray){
        $errorResponse = array();
        if($response['Response']['Status']['Condition']['ConditionCode']==DHL_XML_PARSING_ERROR_CODE||$response['Response']['Status']['ActionStatus']=='Error' || $response['Response']['Status']['ActionStatus']=='Failure'){
            $errorResponse['statusCode']  = DHL_XML_PARSING_ERROR_CODE;
            $errorResponse['status'] = 'Failure';
            $errorResponse['description'] = 'Unexpected error occurred';
            $this->_errorTracking($requestArray,$errorResponse);
        }
        return $errorResponse;
    }
    
    private function _unexpectedError($requestArray){
        $errorResponse['statusCode']     = SHIPMENT_UNEXPECTED_ERROR_CODE;
        $errorResponse['status']         = SHIPMENT_UNEXPECTED_ERROR;
        $errorResponse['description']    = SHIPMENT_UNEXPECTED_ERROR_DESCRPTION;
        $this->_errorTracking($requestArray,$errorResponse);
        return $errorResponse;
    }
    private function _saveAttachment($awbNumber,$base64binaryData,$outputFormat = 'pdf'){
        $attachmentData = base64_decode($base64binaryData);
        $fileName = "/tmp/shipmentAttachment".$awbNumber.time().'.pdf';
        $fp = fopen($fileName, 'w');
	fwrite($fp, $attachmentData);
        fclose($fp);
        $fileUploadLib = $this->CI->load->library("common/fileUploadLib");
        $response = $fileUploadLib->uploadFileViaCurl($fileName,SHIKSHA_HOME."/shipment/shipment/uploadShipmentAttachment",'type=application/pdf');
        $savedUrls='';
        if($response['status'] == "1"){
                $savedUrls = $response[0]['imageurl'];
        }
        //$savedUrls = str_replace(MEDIAHOSTURL,'', $savedUrls);
        unlink($fileName);
        return $savedUrls;
    }
    
    private function _errorTracking($requestArray,$errorResponse){
        $this->CI->load->model('shipment/shipmentmodel');
        $this->shipmentmodel = new shipmentmodel();
        $insertData = array();
        $insertData['requestJSON']      = json_encode($requestArray);
        $insertData['errorCode']        = $errorResponse['statusCode'];
        $insertData['errorMessage']     = $errorResponse['status'];
        $insertData['errorMeaning']     = $errorResponse['description'];
        $insertData['userId']           = $requestArray['userId'];
        $insertData['errorDate']        = date('Y-m-d H:i:s', time());       
        $this->shipmentmodel->errorTracking($insertData);
    }
}

