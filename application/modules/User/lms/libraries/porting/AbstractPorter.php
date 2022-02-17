<?php

abstract class AbstractPorter{
    protected $CI;
    protected $portingmodel;
    protected $portingEntity;
    
    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->model('lms/portingmodel');
        $this->portingmodel = new portingmodel();
        $this->CI->load->model('user/usermodel');
	    $this->usermodel = new UserModel();
        $this->CI->load->model('listing/coursemodel');
        $this->coursemodel = new coursemodel();
        $this->CI->load->model('searchAgents/search_agent_main_model');
        $this->search_agent_main_model = new search_agent_main_model();
        $this->CI->load->library('Zip');
	    $this->zip = new CI_Zip();
        $this->CI->load->library('LDB_Client');
	    $this->ldbObj = new LDB_client();
        $this->CI->load->library('Email');
	    $this->email = new CI_Email();
	    $this->CI->load->config('portingConfig',TRUE);
	
    }

    function setPorting(PortingEntity $portingEntity){
        $this->portingEntity = $portingEntity;
    }
    
    function createJSON($responsesWithData){
    	$mappings = $this->portingEntity->getMappings();
    	$retJSON = "";
    	$json_array = array();
    	
    	$customizedPortingIds = $this->CI->config->item('customizedPortingIds', 'portingConfig');
        if($this->portingEntity->getRequestType() == 'GET' || $this->portingEntity->getRequestType() == 'POST'){
    	    foreach($responsesWithData as $userId=>$data){
    		    if(in_array($this->portingEntity->getId(), $customizedPortingIds)){
    		        switch($this->portingEntity->getId()){
    			        case '45':
                        case '46':
			                    $i=0;
			                    foreach($mappings as $group=>$mapping){
				                    foreach($mapping as $k=>$v){
				                        if($group == 'other'){
					                       $json_array[$i]['Attribute'] = $k;
					                       $json_array[$i]['Value'] = $v;
				                        } else {
					                       $json_array[$i]['Attribute'] = $v;
					                       $json_array[$i]['Value'] = $data[$k];
				                        }
				                        $i++;
				                    }
			                    }
                                break;
		            }
		        } else {
		            foreach($mappings as $group=>$mapping){
			            foreach($mapping as $k=>$v){
			                if($group == 'other'){
				                $json_array[urlencode($k)] = urlencode($v);
			                }
			                else{
                                if(!isset($data[$k])){
                                    $data[$k] = 'N.A.';
                                }
				                $json_array[urlencode($v)] = urlencode($data[$k]);
			                }
			            }
		            }
		        }

                $json_format = $this->portingEntity->getXmlFormat();
            
                if($this->portingEntity->getFormatType()=='json' &&  trim($json_format)!=''){
                    
                    foreach ($json_array as $fieldName => $data) {
                        $key = '#'.$fieldName.'#';

                        $json_format = str_replace($key,$data,$json_format);
                    }
                    $json_array = (array) json_decode($json_format,true);    
                }
                
            
                if($this->portingEntity->getKeyName()) {
                    if($this->portingEntity->getDataEncode()=='no')
                    {
                        $retJSON[$userId]['str'] = $this->portingEntity->getKeyName()."=".urldecode(json_encode($json_array));
                    }
		            else
                    {
                        $retJSON[$userId]['str'] = $this->portingEntity->getKeyName()."=".json_encode($json_array);
                    }
		        } else {
				    if($this->portingEntity->getId() == 449){
                        $retJSON[$userId]['str'] = urldecode(json_encode([$json_array]));
                    }else if($this->portingEntity->getDataEncode()=='no')
                    {
                        $retJSON[$userId]['str'] = urldecode(json_encode($json_array));
                    }
                    else {
                        $retJSON[$userId]['str'] = json_encode($json_array);
                    }
		        }
	        }
        }
        if($this->portingEntity->getId() == 316){
            //mail('neha.maurya@shiksha.com',$this->portingEntity->getId(),$retJSON);
        }
	    
	    return $retJSON;
    }
    
    /**
     * Function to read XML format and create data XML to be ported
     * Params : responses with data
     * Return : XML
    **/
    function createXML($responsesWithData){
    	$mappings = $this->portingEntity->getMappings();
    	$finalXML = array();
        $newArrayForUserDetails = array();	    
	
	    if($this->portingEntity->getRequestType() == 'GET' || $this->portingEntity->getRequestType() == 'POST'){
    	    $xmlstr = $this->portingEntity->getXmlFormat();
    	    
            foreach($responsesWithData as $userId=>$data){
    		    foreach($mappings as $group=>$mapping){
    		        foreach($mapping as $k=>$v){
    			        if($group == 'other'){
    			            $newArrayForUserDetails[$userId][$k]=$v;
    			        }
    			        $newArrayForUserDetails[$userId][$v] = $data[$k];
    		        }
    	    	}
    	    }

            $decodedXMLString = htmlspecialchars_decode(html_entity_decode(urldecode($xmlstr)));

            $portingKey = '';
            if($this->portingEntity->getKeyName() != '') {
                $portingKey = $this->portingEntity->getKeyName().'=';
            }

    	    foreach($newArrayForUserDetails as $userId=>$dataToInsert)
            {
                $searchArray=array();
                $replaceArray=array();
    		    foreach($dataToInsert as $field=>$value)
                {
                    $searchArray[$field] = htmlentities($field);
                    if($value=="" || $value==null)
                    {
                        $value = "NA";
                    }
                    $replaceArray[$field] = htmlentities($value);
    		    }                

                $finalXML[$userId]['str'] = $portingKey.str_replace($searchArray,$replaceArray,$decodedXMLString);
                
    	    }

    	    return $finalXML;
	    }
    }

    function createFieldMap($responsesWithData){
        $mappings = $this->portingEntity->getMappings();
        $retArr = array();
        if($this->portingEntity->getRequestType() == 'GET' || $this->portingEntity->getRequestType() == 'POST'){
            foreach($responsesWithData as $userId=>$data){
                $retArr[$userId]['str'] = "";
                foreach($mappings as $group=>$mapping){
                    foreach($mapping as $k=>$v){
                        if($group == 'other'){
                            $retArr[$userId]['str'].= urlencode($k)."=".urlencode($v)."&";
                        }
                        else{
                            if(!isset($data[$k])){
                                $data[$k] = 'N.A.';
                            }
                            $retArr[$userId]['str'].= urlencode($v)."=".urlencode($data[$k])."&";
                        }
                    }
                }
                $retArr[$userId]['str'] = substr($retArr[$userId]['str'], 0,-1);
            }
        }
	    //_P($this->portingEntity->getId());
        //_P($retArr);
        return $retArr;
    }

    function getDataForPorting($ids,$ported_item_id = array()){
	
        $mappings = $this->portingEntity->getMappings();
        $this->CI->load->library(array('PortingFactory'));
        $userData = array();
        $excludeKeysArray = array('ldb_stream','ldb_basecourse','other','responsecourse','course_level','course_name','custom_location','custom_location_code','matched_response','hierarchy','basecourse','exam_response','matched_response_custom');
        
        foreach($mappings as $k=>$v){
            if(!(in_array($k,$excludeKeysArray))){
                $porterObj = PortingFactory::getPortingDataObject($k);
                $userData[$k] = $porterObj->getData($ids,$v,$ported_item_id);
            }
        }
        return $this->getMergedData($userData);

    }

    private function getMergedData($data)
    {
        $returnData = array();
        // First iteration with main key
        foreach ($data as $key => $mainData) {
            // Second iteration with userData
            foreach($mainData as $userId => $userData) {
                // Checking whther userId exists in returnData
                if (! isset($returnData[$userId])) {
                    $returnData[$userId] = array();
                }
                $returnData[$userId] = array_merge($returnData[$userId], $userData);
            }
        }
        return $returnData;
    }

    private function _makeCurlRequest($url, $requestType, $dataParams=array(), $str="",$extraHeader=array()){

        echo "URL:::::".$url."<br/>";
        echo "REQUESTTYPE ::::::".$requestType."</br/>";
        echo "dataParams :::::::::"._P($dataParams);
        echo "strigndata ::::::::::::".$str;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $url );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT,        10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

        if($requestType == 'POST'){
            curl_setopt ($ch, CURLOPT_POST, 1);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $str);
        }

        $headers = array();

        $custom_header = $this->portingEntity->getCustomHeader();   

        if(!empty($custom_header)){            
            $custom_header = str_replace('#custom_length#', strlen($str), $custom_header);
            $custom_header = explode(',', $custom_header);
            if(count($custom_header)>0 && $custom_header[0]!=''){
                $headers = $custom_header;            
            }
        }else if ($dataParams['dataType'] == 'json' && !($dataParams['dataKey'])) {            
            $headers = array('Content-Type: application/json','Content-Length: ' . strlen($str)); 
        }

        if(!empty($extraHeader)) {
            $headers = array_merge($headers,$extraHeader);
        }

        if(!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

	    
        $response = curl_exec($ch);

        // error_log("==============abhi============  ".print_r($headers,true));
        // error_log("==============abhi============  ".print_r($response,true));
        curl_close($ch);
        return $response;
    }
    
    function portToApi($fieldMap, $flagFirstTime, $data, $credits, $portingIds) {
        $type = $this->portingEntity->getType();
        $response = "";
        $this->CI->load->library('Subscription_client');
        $sumsObject = new Subscription_client();
        $deductCreditArray = array();
        $portedUserIds = array();
        if($this->portingEntity->getType() == 'lead' || $this->portingEntity->getType() == 'matched_response'){

            if(is_array($portingIds) && count($portingIds)>0){
                $result = $this->portingmodel->getUserIdsPortedForPortingAgentByClient($portingIds,array_keys($fieldMap));
                foreach ($result as $key => $value) {
                    $portedUserIds[] = $value['leadid'];
                }
            }else{
                mail('naveen.bhola@shiksha.com',$this->portingEntity->getId(),"client data is missing");
                $portedUserIds = $this->portingmodel->getUserIdsPortedForPortingAgent($this->portingEntity->getClientId());    
            }

            foreach($fieldMap as $userId=>$UserData){
                if(in_array($userId, $portedUserIds)){
                    $deductCreditArray[$userId]['deductCredit'] = false;
                }
                else{
                    $deductCreditArray[$userId]['deductCredit'] = true;
                }
            }
        }

    	$idForTest = array_keys($fieldMap);
        
        $extraHeader = array();
        $accessTokenPortings = array(729,731,733,735,903,905);
        if(in_array($this->portingEntity->getId(),$accessTokenPortings))
        {
            $url = "https://login.salesforce.com/services/oauth2/token?grant_type=password&client_id=3MVG9YDQS5WtC11o3nWKYc5yc6Fwz41GoUAofUpd98yRoaQLvx5w3ckvlyLtL5ad.7REfXiZRXVKrLt2HlQb9&client_secret=8339281080191541729&username=application@mitwpu.edu.in&password=mitwpu@123";

	    $result = $this->getAccessToken($url);
	    if($result['access_token'] != '') {
		    $extraHeader[] = 'Authorization: Bearer '.$result['access_token'];
	    }
        }


    	if($this->portingEntity->getStatus() == 'intest'){
	        $dataParams['dataType'] = $this->portingEntity->getFormatType();
		    $dataParams['dataKey'] = $this->portingEntity->getKeyName();
	        $serializedData = serialize($data[$idForTest[0]]);
            $str_test = $fieldMap[$idForTest[0]];

		    if($this->portingEntity->getRequestType() == 'GET'){
                $response = $this->_makeCurlRequest($this->portingEntity->getApi().'?'.$str_test['str'], $this->portingEntity->getRequestType(), $dataParams, '',$extraHeader);
            } else if ($this->portingEntity->getRequestType() == 'POST'){
                if($this->portingEntity->getFormatType() == "SOAP"){
                    $response = $this->_makeSOAPRequest($this->portingEntity->getApi(), $str_test['str']);
                }else{
                    $response = $this->_makeCurlRequest($this->portingEntity->getApi(), $this->portingEntity->getRequestType(), $dataParams, $str_test['str'],$extraHeader);    
                }
            }
		
		    if($this->portingEntity->getType() == 'response'){
                $this->portingmodel->updatePortingStatus($data[$idForTest[0]]['tempLMSId'], $this->portingEntity->getId(), $response, $flagFirstTime, $serializedData,'stopped');
            } else if ($this->portingEntity->getType() == 'lead' || $this->portingEntity->getType() == 'matched_response'){
                $this->portingmodel->updatePortingStatus($data[$idForTest[0]]['matchId'], $this->portingEntity->getId(), $response, $flagFirstTime, $serializedData,'stopped');
		    }else if($this->portingEntity->getType() == 'examResponse'){
                $this->portingmodel->updatePortingStatus($data[$idForTest[0]]['examResponseAllocationId'], $this->portingEntity->getId(), $response, $flagFirstTime, $serializedData,'stopped');
            }
		    return;   
	    }
        
        foreach($fieldMap as $userId=>$str){
            if($this->portingEntity->getType() == "examResponse"){
                $serializedData = serialize($data[$userId]);

                $dataParams['dataType'] = $this->portingEntity->getFormatType();
                $dataParams['dataKey'] = $this->portingEntity->getKeyName();

                if($this->portingEntity->getRequestType() == 'GET'){
                    $response = $this->_makeCurlRequest($this->portingEntity->getApi().'?'.$str['str'], $this->portingEntity->getRequestType(), $dataParams, '',$extraHeader);
                } else if ($this->portingEntity->getRequestType() == 'POST'){
                    if($this->portingEntity->getFormatType() == "SOAP"){
                        $response = $this->_makeSOAPRequest($this->portingEntity->getApi(), $str['str']);
                    }else{
                        $response = $this->_makeCurlRequest($this->portingEntity->getApi(), $this->portingEntity->getRequestType(), $dataParams, $str['str'],$extraHeader);
                    }
                }

                $this->portingmodel->updatePortingStatus($data[$userId]['examResponseAllocationId'], $this->portingEntity->getId(), $response, $flagFirstTime, $serializedData,'live');
            }else{
                $required_credit = 1;
                if ($this->portingEntity->getType() == 'matched_response' || $this->portingEntity->getType() == 'lead') {

                    if(!empty($credits[$data[$userId]['matchId']])) {
                        $required_credit = $credits[$data[$userId]['matchId']];
                    }

                }
                
                if( $this->portingEntity->getType() == 'response'  ||  ($sumsObject->sgetValidSubscriptions(array($this->portingEntity->getSubscriptionId()), 1, $required_credit) == 'valid')) {

                    $serializedData = serialize($data[$userId]);
                    $dataParams['dataType'] = $this->portingEntity->getFormatType();
                    $dataParams['dataKey'] = $this->portingEntity->getKeyName();
                    
                    if($this->portingEntity->getRequestType() == 'GET'){
                        $response = $this->_makeCurlRequest($this->portingEntity->getApi().'?'.$str['str'], $this->portingEntity->getRequestType(), $dataParams, '',$extraHeader);
                    } else if ($this->portingEntity->getRequestType() == 'POST'){

                        if($this->portingEntity->getFormatType() == "SOAP"){
                            $response = $this->_makeSOAPRequest($this->portingEntity->getApi(), $str['str']);
                        }else{
                            $response = $this->_makeCurlRequest($this->portingEntity->getApi(), $this->portingEntity->getRequestType(), $dataParams, $str['str'],$extraHeader);
                        }
                    }

                    if($this->portingEntity->getType() == 'response'){
                        // $responses[]  = $response;
                        // $sent_data[] = $serializedData;
                        // $portedItemId[] = $data[$userId]['tempLMSId'];
                        $this->portingmodel->updatePortingStatus($data[$userId]['tempLMSId'], $this->portingEntity->getId(), $response, $flagFirstTime, $serializedData,'live');
                    } else if ($this->portingEntity->getType() == 'lead' || $this->portingEntity->getType() == 'matched_response'){
                        $this->portingmodel->updatePortingStatus($data[$userId]['matchId'], $this->portingEntity->getId(), $response, $flagFirstTime, $serializedData,'live');
                    }
                
                    if ($this->portingEntity->getType() == 'lead' || $this->portingEntity->getType() == 'matched_response'){
                        if(isset($deductCreditArray[$userId]['deductCredit']) && $deductCreditArray[$userId]['deductCredit']){
                            $sumsObject->sdeductLeadPortingCredits($this->portingEntity->getSubscriptionId(), $required_credit);
                        }
                    }

                }

            }
        }

        if($this->portingEntity->getType() == 'response'){        
            // $query = $this->generateBulkQueryForPortingStatus($responses,$sent_data,$this->portingEntity->getId(),$flagFirstTime,$portedItemId);
        }
    }

    private function _makeSOAPRequest($wsdl_url, $xml=""){

        $SOAPData =  json_decode(json_encode(simplexml_load_string($xml)),true);

        foreach ($SOAPData['soapData'] as $key => $value) {
            $functionName = $key;
            break;
        }

        $functionName = trim($functionName);
        $response = '';

        if((!empty($functionName)) && (!empty($SOAPData))) {
            //mail('teamldb@shiksha.com,neha.maurya@shiksha.com','SUCCESS::Response received for SOAP Porting at '.date('Y-m-d H:i:s'), 'URL='.$wsdl_url.'<br/>XML='.$xml.'<br/>SOAP DATA='.print_r($SOAPData, true).'<br/>functionName='.$functionName);

            $soapHeader = $SOAPData['soapHeader'];
            $client = new SOAPClient($wsdl_url, array("trace" => true,"exceptions" => true));

            $this->CI->load->domainClass('WsseAuthHeader');
            $client->__setSoapHeaders(Array(new WsseAuthHeader($soapHeader)));

            $params = $SOAPData['soapData'][$functionName];
            $response=$client->$functionName($params);
        } else {
            mail('teamldb@shiksha.com,neha.maurya@shiksha.com','FAILED::Response Not received for SOAP Porting at '.date('Y-m-d H:i:s'), 'URL='.$wsdl_url.'<br/>XML='.$xml.'<br/>SOAP DATA='.print_r($SOAPData, true).'<br/>functionName='.$functionName);
        }
        return json_encode($response);
    }
    
    function portToEmail($fieldMap, $flagFirstTime, $data, $credits) {

        $type = $this->portingEntity->getType();
        $response = "";
        $this->CI->load->library('Subscription_client');
        $sumsObject = new Subscription_client();
        $deductCreditArray = array();

        if($this->portingEntity->getType() == 'lead' || $this->portingEntity->getType() == 'matched_response') {
	    
            $portedUserIds = $this->portingmodel->getUserIdsPortedForPortingAgent($this->portingEntity->getClientId());
            foreach($fieldMap as $matchId=>$userId){
                if(in_array($userId, $portedUserIds)){
                    $deductCreditArray[$userId]['deductCredit'] = false;
                }
                else{
                    $deductCreditArray[$userId]['deductCredit'] = true;
                }
            }
        }

        foreach($fieldMap as $matchId=>$userId){

            $required_credit = 1;
            if ($this->portingEntity->getType() == 'matched_response' || $this->portingEntity->getType() == 'lead') {

                if(!empty($credits[$matchId])) {
                    $required_credit = $credits[$matchId];
                }

            }

            if($sumsObject->sgetValidSubscriptions(array($this->portingEntity->getSubscriptionId()), 1, $required_credit) == 'valid') {
                
                if($this->portingEntity->getType() == 'response') {
                
                    $serializedData = serialize($data[$userId['userid']]);
                
                } else if ($this->portingEntity->getType() == 'lead' || $this->portingEntity->getType() == 'matched_response') {
                
                    $serializedData = serialize($data[$userId]);

                }
                
                $this->portingmodel->updatePortingStatus($matchId, $this->portingEntity->getId(), $response, $flagFirstTime, $serializedData, 'live');
            
                if ($this->portingEntity->getType() == 'lead' || $this->portingEntity->getType() == 'matched_response'){
                    if(isset($deductCreditArray[$userId]['deductCredit']) && $deductCreditArray[$userId]['deductCredit']){
                        $sumsObject->sdeductLeadPortingCredits($this->portingEntity->getSubscriptionId(), $required_credit);
                    }
                }

            }

        }
    }

    function formatDataAccordingtoVendor($vendorCity,$vendorState,$vendor,$responsesData)
    {
        $vendorCityMapping = $this->portingmodel->getCityForVendor($vendorCity,$vendor);
        $vendorStateMapping = $this->portingmodel->getStateForVendor($vendorState,$vendor);
        $vendorCityMapping = array_column($vendorCityMapping, "vendor_city","city_name");
        $vendorStateMapping = array_column($vendorStateMapping, "vendor_state","state_name");
        // _p($vendorStateMapping);die;
        foreach ($responsesData as $respId => $data) {
            // _p($vendorStateMapping);
            if ($vendorStateMapping[$data['Residence_State']]){
                $responsesData[$respId]['Residence_State'] = $vendorStateMapping[$data['Residence_State']];
            }
            if ($vendorCityMapping[$data['Residence_City']]){
                $responsesData[$respId]['Residence_City'] = $vendorCityMapping[$data['Residence_City']];
            }
        }
        return $responsesData;
    }

    function getAccessToken($url)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT,        10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                            'Content-Type: application/json'
                                            ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result,true);
    }

    public  function fetchBaseCourseValueFromRedis(){
        $this->CI->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new \ListingBaseBuilder();
        $BaseCourseRepository = $listingBase->getBaseCourseRepository();
        $baseCourseCache = $BaseCourseRepository->getAllBaseCourses();
        $baseCourseCache = array_column($baseCourseCache,'name','id');
        return $baseCourseCache;    
    }

    public  function getAllPortingCustomizedFields($clientId){

        $customData = $this->portingmodel->getAllPortingCustomizedFields($clientId);
        foreach ($customData as $key => $data ) {
            $retData[$data['entity_type']][$data['entity_id']] =  $data['entity_value']; 
        }
        return $retData;
    }

    private function generateBulkQueryForPortingStatus($responses,$serializedData,$portingMasterId,$flag,$portedItemId){
        if(empty($responses) || empty($serializedData) || empty($portedItemId) || empty($flag) || empty($portingMasterId)){
            return;
        }
        $this->portingmodel->generateBulkQueryForPortingStatus($responses,$serializedData,$portingMasterId,$flag,$portedItemId);
    }

}
