<?php 
class OAFPorter{

	function __construct(){
		$this->CI = & get_instance();
		$this->portingmodel = $this->CI->load->model('oafPorting/oafportingmodel');
		$this->CI->load->library('oafPorting/OAFPortingFactory');
		$this->CI->load->config('oafPorting/oafPortingConfig');

		$this->CI->load->builder("nationalCourse/CourseBuilder");
    	$builder = new CourseBuilder();
    	$this->courseRepo = $builder->getCourseRepository();
	}

	public function port($portingData){
		$responses = $portingData['responses'];
		$portings = $portingData['portings'];
		// _p($responses);die;
		if(empty($responses)){
			return;
		}
		// _p($portings);die;
		$formToPortingsMapping = array();
		$portingIds = array();

		$vendors = array();
		foreach ($portings as $row) {
			$vendors[] = $row->getVendorName();
			$criteria = $row->getPortingCriteria();
			foreach ($criteria as $criteriaRow) {
				$formToPortingsMapping[$criteriaRow['value']][] = $row->getId();
			}
		}
		// _p($formToPortingsMapping);die;

		$onlineFormIds = array();
		$responseIds = array();
		$userIds = array();

		foreach ($responses as $row) {
			$onlineFormIds[$row['onlineFormId']] = $row['onlineFormId'];
			$userIds[$row['userId']] = $row['userId'];
		}

		$onlineFormIds = array_keys($onlineFormIds);
		$onlineFormDataFromDB = $this->portingmodel->getOnlineFormData($onlineFormIds);
		// _p($onlineFormDataFromDB);die;

		$userData = $this->portingmodel->getUserData(array_keys($userIds));
		// _p($userData);

		$onlineFormData = array();
		foreach ($onlineFormDataFromDB as $onlineFormId => $formData) {
			if($formData['isMultipleCase'] == 0){
				$onlineFormData[$formData['onlineFormId']][$formData['fieldId']] = $formData['value'];
			}
		}
		// _p($onlineFormData);die;

		$configFieldMappings = $this->CI->config->item('fieldMapping');
		$userIds = array();
		$formIds = array();
		// _p($responses);
		foreach ($responses as $response)
		{
			$userIds[] = $response['userId'];
			$formIds[] = $response['onlineFormId'];
		}

		$lastprocessedId = 0;
		
		$vendorCityStateMapping = $this->getVendorMapping($vendors);
		
		$country = $this->portingmodel->getCountryMapping();
		$city = $this->portingmodel->getCityMapping();
		$countryMapping = array();
		$cityMapping = array();
		foreach ($country as $key => $value) {
			$countryMapping[$value['countryId']] = $value['name'];
			# code...
		}
		foreach ($city as $key => $value) {
			$cityMapping[$value['city_id']] = $value['city_name'];
			# code...
		}
		$payments = $this->portingmodel->getPaymentDetails($userIds,$formIds);
		$paymentMapping = array();

		$fileTypeFieldList = $this->portingmodel->getFileTypeFields();
		foreach ($payments as $payment)
		{
			$paymentMapping[$payment['userId']][$payment['onlineFormId']] = $payment;  
		}

		try{
			foreach ($responses as $response) {

				$formId = $response['formId'];
				$onlineFormId = $response['onlineFormId'];

				foreach ($formToPortingsMapping[$formId] as $portingId) {
					$payment = $paymentMapping[$response['userId']][$onlineFormId];
					$temp = array();
					$porting = $portings[$portingId];

					$mappings = $porting->getMappings();
					foreach ($mappings as $mapping) {
						if($mapping['master_field_id'] > 0){
							if(!empty($configFieldMappings['pattern'][$mapping['master_field_id']])){
								$parts = explode("^", $configFieldMappings['pattern'][$mapping['master_field_id']]);
								$str = "";
								foreach ($parts as $part) {

									$firstIndex = strpos($part, "#");
									$lastIndex = strrpos($part, "#");
									$fieldId = substr($part, $firstIndex+1, $lastIndex-$firstIndex-1);
									if(!empty($onlineFormData[$onlineFormId][$fieldId])){
										$str .= $onlineFormData[$onlineFormId][$fieldId];
										$str .= substr($part, $lastIndex+1);
									}
								}
								$temp[$mapping['client_field_name']] = $str;
							}
							else if(!empty($configFieldMappings['customized'][$mapping['master_field_id']])){
								$fieldType = $configFieldMappings['customized'][$mapping['master_field_id']]['field_type'];
								$entityId = '';
								if($fieldType == 'oaf_course') {
									$entityId = $response['clientCourseId'];
								} else if($fieldType == 'oaf_gender') {
									$fieldId = $configFieldMappings['customized'][$mapping['master_field_id']]['fieldId'];
									$entityId = $onlineFormData[$onlineFormId][$fieldId];
								} else if($fieldType == 'oaf_paymentmode') {
									$columnName = $configFieldMappings['customized'][$mapping['master_field_id']]['column_name'];
									$keyName = $configFieldMappings['customized'][$mapping['master_field_id']]['key_name'];
									parse_str($payment[$columnName], $paymentLog);				
									$entityId = $paymentLog[$keyName];
								}

								$customizedData = $this->portingmodel->getCustomizedMappedField($entityId, $fieldType, $response['clientId']);
								if(!empty($customizedData['entity_value'])){
									$temp[$mapping['client_field_name']] = $customizedData['entity_value'];
								}
								else {
									if($fieldType == 'oaf_course') {
										$courseObj = $this->courseRepo->find($response['clientCourseId']);
										if(!empty($courseObj)){
											$temp[$mapping['client_field_name']] = $courseObj->getName();
										}
									} else if($fieldType == 'oaf_gender' || $fieldType == 'oaf_paymentmode') {
										$temp[$mapping['client_field_name']] = $entityId;
									}
								}
							}  
							else if(!empty($configFieldMappings['table'][$mapping['master_field_id']])){
								if ($configFieldMappings['table'][$mapping['master_field_id']] == 'OF_UserForms'){
									$temp[$mapping['client_field_name']] = $response['onlineFormId'];
								}
								else if ($configFieldMappings['table'][$mapping['master_field_id']][0] == 'OF_Payments'){
									$temp[$mapping['client_field_name']] = $payment[$configFieldMappings['table'][$mapping['master_field_id']][1]];
								}

								else if ($configFieldMappings['table'][$mapping['master_field_id']][0] == 'OF_PaymentLog'){
									if($configFieldMappings['table'][$mapping['master_field_id']][2] == 'bank_ref_no') {
										$logKey = $configFieldMappings['table'][$mapping['master_field_id']][2];
										parse_str($payment[$configFieldMappings['table'][$mapping['master_field_id']][1]], $paymentLog);				
										$temp[$mapping['client_field_name']] = $paymentLog[$logKey]; 									
									}
								}

								else if ($configFieldMappings['table'][$mapping['master_field_id']] =='city'){
									//$temp[$mapping['client_field_name']] = $cityMapping[]
									if (!empty($porting->getVendorName()) && isset($vendorCityStateMapping[$porting->getVendorName()]['city'][$onlineFormData[$onlineFormId][$mapping['master_field_id']]])){
										$temp[$mapping['client_field_name']] = $vendorCityStateMapping[$porting->getVendorName()]['city'][$onlineFormData[$onlineFormId][$mapping['master_field_id']]];
									}
									else{
										$temp[$mapping['client_field_name']]=$cityMapping[$onlineFormData[$onlineFormId][$mapping['master_field_id']]];
									}
								}
								else if ($configFieldMappings['table'][$mapping['master_field_id']] =='country')
								{
									$temp[$mapping['client_field_name']]=$countryMapping[$onlineFormData[$onlineFormId][$mapping['master_field_id']]];

								}

								else if ($configFieldMappings['table'][$mapping['master_field_id']] =='state'){
									if (!empty($porting->getVendorName()) && isset($vendorCityStateMapping[$porting->getVendorName()]['state'][$onlineFormData[$onlineFormId][$mapping['master_field_id']]])){
										$temp[$mapping['client_field_name']] = $vendorCityStateMapping[$porting->getVendorName()]['state'][$onlineFormData[$onlineFormId][$mapping['master_field_id']]];
									}
									else{
										$temp[$mapping['client_field_name']]=$onlineFormData[$onlineFormId][$mapping['master_field_id']];
									}
							}

							}

							else{
								if (in_array($mapping['master_field_id'], $fileTypeFieldList)){
									if ($onlineFormData[$onlineFormId][$mapping['master_field_id']])
									$temp[$mapping['client_field_name']] = SHIKSHA_HOME.($onlineFormData[$onlineFormId][$mapping['master_field_id']]);
									else
										$temp[$mapping['client_field_name']] = $onlineFormData[$onlineFormId][$mapping['master_field_id']];
								}	
								else{
									$temp[$mapping['client_field_name']] = $onlineFormData[$onlineFormId][$mapping['master_field_id']];
								}
							}

						}
						else{
							$temp[$mapping['client_field_name']] = $mapping['other_value'];
						}
					}
					// _p($temp);die;
					if($porting->getFormatType() == 'json'){
						$fieldMap = $this->createJSON($porting, $temp);
					} 
					else if($porting->getFormatType() == 'XML' || $porting->getFormatType() == 'SOAP'){
						$fieldMap = $this->createXML($porting, $temp);
			    	} 
			    	else {
						$fieldMap = $this->createFieldMap($porting, $temp);
						ksort($fieldMap);
			    	}
			    	// _p($fieldMap);die;

			    	// skip porting for test user
			    	$userId = $response['userId'];
			    	if($userData[$userId]['isTestUser'] == 'NO'){
			    		$networkResponse = $this->portToApi($porting,$response['id'],$fieldMap);
			    		$this->portingmodel->updatePortingStatus($response['id'], $porting->getId(), $networkResponse, serialize($temp),'live');
			    	}

			    	$lastprocessedId = $response['id'];
			    	$responseIds[$response['id']] = $response['id'];
			    	$portingIds[$portingId] = $portingId;
				}
			}
		}
		catch(Exception $e){
			mail('teamldb@shiksha.com', 'OnlineForm Porting For Responses', 'Exception occured when porting new responses in online forms');
		}
		return array('lastprocessedId' => $lastprocessedId, 'portingIds' => $portingIds, 'responseIds' => $responseIds);
	}

	public function createJSON($portingEntity, $responseData){
		$mappings = $portingEntity->getMappings();
		$retJSON = "";
		$json_array = array();

		if($portingEntity->getRequestType() == 'GET' || $portingEntity->getRequestType() == 'POST'){
			foreach ($responseData as $key => $value) {
				if(is_array($value)){
					foreach ($value as $index => $val) {
						$json_array[urlencode($key)][urlencode($index)] = urlencode($val);
					}
				}
				else{
					$json_array[urlencode($key)] = urlencode($value);
				}
			}

			$json_format = $portingEntity->getXmlFormat();
			if($portingEntity->getFormatType() == 'json' &&  trim($json_format)!=''){
			    
			    foreach ($json_array as $fieldName => $data) {
			        $key = '#'.$fieldName.'#';

			        $json_format = str_replace($key,$data,$json_format);
			    }
			    $json_array = (array) json_decode($json_format,true);    
			}

			if($portingEntity->getKeyName()) {
                if($portingEntity->getDataEncode()=='no'){
                    $retJSON = $portingEntity->getKeyName()."=".urldecode(json_encode($json_array));
                }
	            else{
                    $retJSON = $portingEntity->getKeyName()."=".json_encode($json_array);
                }
			}
			else{
				if($portingEntity->getDataEncode()=='no'){
				    $retJSON = urldecode(json_encode($json_array));
				}
				else {
				    $retJSON = json_encode($json_array);
				}
			}
		}
		return $retJSON;
	}

	public function createXML($portingEntity, $responseData){
    	$finalXML = array();
	    $searchArray=array();
	    $replaceArray=array();
	
	    if($portingEntity->getRequestType() == 'GET' || $portingEntity->getRequestType() == 'POST'){
    	    $xmlstr = $portingEntity->getXmlFormat();
    	    $portingKey = '';
    	    if($portingEntity->getKeyName() != '') {
    	        $portingKey = $portingEntity->getKeyName().'=';
    	    }

    	    $finalXML = htmlspecialchars_decode(html_entity_decode(urldecode($xmlstr)));

		    foreach($responseData as $field => $value){
		    	$searchArray[$field] = '#'.htmlentities($field).'#';
		    	if($value=="" || $value==null){
		    	    $value = "NA";
		    	}
		    	$replaceArray[$field] = htmlentities($value);
		    }

            $finalXML = $portingKey.str_replace($searchArray,$replaceArray,htmlentities($finalXML));

    	    return $finalXML;
	    }
	}

	public function createFieldMap($portingEntity, $responseData){
        $retArr = "";
        if($portingEntity->getRequestType() == 'GET' || $portingEntity->getRequestType() == 'POST'){
            foreach($responseData as $key => $value){
            	$retArr .= urlencode($key)."=".urlencode($value)."&";
            }
            $retArr = substr($retArr, 0,-1);
        }
        return $retArr;
	}

    public function portToApi($portingEntity, $id, $responseData) {
        $dataParams['dataType'] = $portingEntity->getFormatType();
        $dataParams['dataKey'] = $portingEntity->getKeyName();
        $dataParams['portingId'] = $portingEntity->getId();
        // _p($dataParams);die;
        if($portingEntity->getRequestType() == 'GET'){
            $response = $this->_makeCurlRequest($portingEntity->getApi().'?'.$responseData, $portingEntity->getRequestType(), $dataParams, '');
        } 
        else if ($portingEntity->getRequestType() == 'POST'){
            if($portingEntity->getFormatType() == "SOAP"){
                $response = $this->_makeSOAPRequest($portingEntity->getApi(), $responseData);
            }else{
                $response = $this->_makeCurlRequest($portingEntity->getApi(), $portingEntity->getRequestType(), $dataParams, $responseData);
            }
        }
        // remove before going live
        if(ENVIRONMENT != 'development' && ENVIRONMENT != 'production'){
        	mail('mohd.alimkhan@shiksha.com', 'porting Data', $responseData);
        	mail('mohit.k1@shiksha.com', 'porting Data', $responseData);
        }

        return $response;
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
        	$soapHeader = $SOAPData['soapHeader'];
        	$client = new SOAPClient($wsdl_url, array("trace" => true,"exceptions" => true));

        	$this->CI->load->domainClass('WsseAuthHeader');
        	$client->__setSoapHeaders(Array(new WsseAuthHeader($soapHeader)));

        	$params = $SOAPData['soapData'][$functionName];
        	$response=$client->$functionName($params);
        }
        else{
        	mail('teamldb@shiksha.com,neha.maurya@shiksha.com','FAILED::Response Not received for SOAP Porting at '.date('Y-m-d H:i:s'), 'URL='.$wsdl_url.'<br/>XML='.$xml.'<br/>SOAP DATA='.print_r($SOAPData, true).'<br/>functionName='.$functionName);
        }
        return json_encode($response);
    }

    private function _makeCurlRequest($url, $requestType, $dataParams=array(), $str=""){

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

    	if($dataParams['dataType'] == 'json' && !($dataParams['dataKey'])){
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: '.strlen($str)));
        }

        if ($dataParams['portingId'] == 1055 || $dataParams['portingId'] == 1033 || $dataParams['portingId'] == 655){
        	$headers = array('Password: cFMeSQN621AMzbL0u5qv9A==','Username: 4wEawgC8mTJoFpuldaRdQQ==','Content-Type: application/json','Site_ID: Shiksha','Content-Length: '.strlen($str));
        }

     	if(!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function getVendorMapping($vendors){
    	$this->__construct();
    	$vendorMapping  = $this->portingmodel->getVendorMapping($vendors);
		foreach ($vendorMapping as $key => $value) {
			if (!empty($value['state_name'])){
				$vendorCityStateMapping[$value['vendor_name']]['state'][strtoupper($value['state_name'])] = $value['vendor_entity'];
			}
			else{
				$vendorCityStateMapping[$value['vendor_name']]['city'][$value['city_id']] = $value['vendor_entity'];
			}
		}
		
		
		// _p($vendorCityStateMapping);die;
		return $vendorCityStateMapping;
    }
}
?>
