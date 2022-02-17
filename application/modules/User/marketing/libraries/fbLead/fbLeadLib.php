<?php

class fbLeadLib{

	private $CI;
    
    /**
     * Constructor
     */ 
    function __construct()
    {
        $this->CI = & get_instance();
        $this->FBLeadModel = $this->CI->load->model('marketing/fbleadmodel');
        $this->FBLeadCommonLib = $this->CI->load->library('marketing/fbLead/FBLeadCommon');
    }

    function create($input){
    	$pushData                  = $this->_parseFacebookPushCall($input);

    	// check fb_form_id exist in our system
    	$activeFBFormIds = $this->FBLeadModel->getActiveFormIds($pushData['fb_form_id']);
    	
    	if(count($activeFBFormIds)>0){
	    	// storing leadid and formid in db
			$insertResponse = $this->FBLeadModel->insertFBLeadId($pushData);
			if($insertResponse > 0){
				if(!empty($pushData['lead_id'])){
					$this->createFbLead($pushData);    						
				}
			}else{
				mail("teamldb@shiksha.com", "Duplicate lead id", print_r($pushData,true));
			}   		
    	}else{
    		$this->CI->load->library('marketing/FBLeadCommon');
    		$fb_lead_common = new FBLeadCommon();
    		
    		$toMail = 'md.ansari@shiksha.com,teamldb@shiksha.com'; 
    		$subject= 'FB Lead - Form Id not found';
    		$errorContent = 'Form Id not present in the system while registering the lead. Lead Id- '.$pushData['lead_id'];
    		mail("md.ansari@shiksha.com,teamldb@shiksha.com", $subject, $errorContent);
    		//$fb_lead_common->sendMail($toMail, $subject, $errorContent);
    	}

    }

    function createFbLeadForTesting($pushData){
    	// check fb_form_id exist in our system
    	$activeFBFormIds = $this->FBLeadModel->getActiveFormIds($pushData['fb_form_id']);
    	if(count($activeFBFormIds)>0){    		
	    	// storing leadid and formid in db			
			$this->FBLeadModel->insertFBLeadId($pushData);		

			if(!empty($pushData['lead_id'])){
				$this->createFbLead($pushData);    						
			}
    	}else{
    		$this->CI->load->library('marketing//FBLeadCommon');
    		$fb_lead_common = new FBLeadCommon();
    		
    		$toMail = 'teamldb@shiksha.com'; 
    		$subject= 'FB Lead - Form Id not found';
    		$errorContent = 'Form Id not present in the system while registering the lead. Lead Id- '.$pushData['lead_id'];
    		$fb_lead_common->sendMail($toMail, $subject, $errorContent);
    	}    	
    }

    private function _validateMandatoryFields($pullData){
    	$fields = array(
			'email'           =>	1,
			'phone_number'    =>	1,
			'first_name'      =>	1,
			'last_name'       =>	1,
			'city'            =>	1,
			'course_name'     =>	1,
			'course_location' =>	-1,
			'work_ex'         =>	-1
    	);

    	$mandatoryFieldsCount = 0;
    	$hasExtraFields = false;
    	$hasMandatoryFields = true;
    	foreach ($pullData as $key => $data) {
    		if($fields[$data['name']]){
    			if($fields[$data['name']] == 1){
	    			$mandatoryFieldsCount++;
	    		}
    		}else{
    			$hasExtraFields = true;
    		}
    	}

    	if($mandatoryFieldsCount != 6){
    		$errorContent = print_r($pullData,true);
    		$this->FBLeadCommonLib->sendMail("md.ansari@shiksha.com,teamldb@shiksha.com", "Mandatory fields not found", $errorContent);
    		return false;
    	}else if($mandatoryFieldsCount == 6 && $hasExtraFields == true){
    		$errorContent = print_r($pullData,true);
    		$this->FBLeadCommonLib->sendMail("teamldb@shiksha.com", "Extra fields found", $errorContent);
    		return true;
    	}else if($mandatoryFieldsCount == 6 && $hasExtraFields == false){
    		return true;
    	}
    }


    public function createFbLead($pushData){
    	$fbLeadResponseModel       = $this->CI->load->model('enterprise/fbleadresponsemodel');   	    	
		
		// pull lead data from facebook
		$pullData                  = $this->_pullLeadDataFromFacebook($pushData['lead_id']);

		if(isset($pullData['field_data'])){
			$response = $this->_validateMandatoryFields($pullData['field_data']);
			if($response == true){
				$formatedPullData       = $this->_formatPullData($pullData,$pushData);
				//die('Done');
				if (count($formatedPullData)>0 ) {
					$this->createResponseForFBLead($formatedPullData);	
					echo "Lead succesfully stored";
				}else{
					echo 'failed';
					$this->FBLeadCommonLib->sendMail('teamldb@shiksha.com', "Lead data not available", print_r($pushData,true));
					exit();
				}
			}
		}else{			
			$errorData             = $pullData['error'];
			$errorContent          = '';

			foreach ($errorData as $key => $value) {
				$errorContent .= $key." : ".$value."<br/>";
			}

			//load library to send mail
			$this->FBLeadCommonLib->sendMail('teamldb@shiksha.com', "FB Lead Error Lead Id".$pushData['lead_id'], $errorContent);
			die;
		}
		
		/*if($response == true){
			// create response
			$this->createResponseForFBLead($fomatedPullData);
			echo "Lead succesfully stored";
			die;
		}else{
			echo "failed";
			die;
		}*/
    }



    /**
	 * Extract information from facebook lead push call
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2017-11-08
	 * @param  $input facebook push data
	 * @return array containing leadId, formId, createdTime
	 */
	private function _parseFacebookPushCall($input){
		$data = array();

		$data['lead_id']     = $input['entry'][0]['changes'][0]['value']['leadgen_id'];
		$data['fb_form_id']  = $input['entry'][0]['changes'][0]['value']['form_id'];
		$createdTime         = $input['entry'][0]['changes'][0]['value']['created_time'];
		$date                = new DateTime();
		$date->setTimestamp($createdTime);
		$data['fb_lead_created_on'] = $date->format('Y-m-d H:i:s');

		// $data['lead_id']            = 1951180201808730;
		// $data['fb_form_id']         = 1951180101808740;
		// $createdTime                = 1510296685;
		// $date                       = new DateTime();
		// $date->setTimestamp($createdTime);
		// $data['fb_lead_created_on'] = $date->format('Y-m-d H:i:s');		

		return $data;
	}

	private function _pullLeadDataFromFacebook($leadgenId){
		// EAABfA7BEImEBAHG9kn9tFrZCaC7eiGxHWVtEaSboCSC9htDoanvcY0CLGfHrxHlXb78yoqO1IL6ZBRPA3kWlrp2ocGdaNdeXV6GAmPthz4vjk8688yE9sazwk6WmiZBbJ0ZBZCuHBCFAvvexGMMtCZBl0NHJuIdlPZBv0BSeZAIoSxZAdWJb8WV4GHVNifnIsUUKLgCtxe1ZB5rAZDZD
		$url = 'https://graph.facebook.com/v2.11/'.$leadgenId.'?access_token='.FACEBOOK_ACCESS_TOKEN;
		$data = $this->FBLeadCommonLib->sendCurlRequest($url);
		//_p($data);die;
		return $data;
	}

	private function _formatPullData($pullData,$pushData){
		$fieldData  = $pullData['field_data'];
		$formatData  = array();
		foreach ($fieldData as $key => $value) {
			$formatData[$value['name']] = $value['values'][0];
		}

		foreach ($pushData as $key => $value) {
			$formatData[$key] = $value;
		}

		return $formatData;

	}

	private function _formatJSONData($JSONData){
		$JSONData = json_decode($JSONData);
		$data = array();
		foreach ($JSONData as $key => $value) {
			$data[$key] = $value;
		}

		return $data;
	}

	private function _prepareCommonDataForResponse($inputData){
		if($inputData['FBLeadDataMapping']['courseId'] >0){
			$data = array();
			$data['tracking_keyId'] = 1356;
			$data['Flag'] = 'National';
			$data['referral'] = "https://www.facebook.com?t_source=paid&utm_source=facebook&utm_medium=cpm&utm_campaign=shikshafbscanresponses";
			$data['action_type'] = "FBDownloadBrochure";
			$data['context'] = "nationalResponse";
		}
		return $data;
	}

	public function createResponseForFBLead($FBLeadData, $isJSONData = false){
		if($isJSONData == true){
			$FBLeadData = $this->_formatJSONData($FBLeadData);	
		}
		
		try {
            $FBUserValidationLib = $this->CI->load->library('marketing/fbLead/FBLeadDataValidation');
            $result = $FBUserValidationLib->validateFBData($FBLeadData);
            //_p($result);

            if($result['validFBData'] == true){
            	//die('validation true. In response flow');
            	$commonData = $this->_prepareCommonDataForResponse($result);
            	$result['FBLeadDataMapping'] = array_merge($result['FBLeadDataMapping'], $commonData);
                $FBLeadDataLib = $this->CI->load->library("marketing/fbLead/FBLeadResponse");
                $response = $FBLeadDataLib->createFBLeadResponse($FBLeadData, $result['FBLeadDataMapping']);
                $status = ($response['status'] == 'SUCCESS')?"response":"lead_data";
            	$this->updateFBLead($FBLeadData,$status, $response['userId']);
            }else{
            	$this->updateFBLead($FBLeadData,"exception");

                $exceptionData = array();
                $exceptionList = $result['exceptionList'];
                foreach ($exceptionList as $exceptionType => $exceptionValue) {
                    $exceptionData[] = array(
                        'exception_type'    =>  $exceptionType,
                        'old_value'         =>  $exceptionValue,
                        'lead_id'           =>  $FBLeadData['lead_id'],
                        );
                    $fieldMappingData[] = array(
                        'type'  => $exceptionType,
                        'old_value' => $exceptionValue
                        );
                }

                $response = $this->FBLeadModel->addToFBException($exceptionData, $fieldMappingData);

                if($response == false){
                	//load library to send mail
		            $errorContent = print_r($FBLeadData, true);
					$this->FBLeadCommonLib->sendMail('teamldb@shiksha.com', "Not able to log exception at '.date('Y-m-d H:i:s')", 'Exception data: <br/>'.print_r($exceptionData, true).'<br/>'.print_r($fieldMappingData, true));
                }
                die('validation false. In response flow');
            }
        } catch (Exception $e) {
            // add to fb_lead_data
            $this->updateFBLead($FBLeadData,"lead_data");

            //load library to send mail
            $errorContent = print_r($FBLeadData, true);
			$this->FBLeadCommonLib->sendMail('teamldb@shiksha.com', "FB Lead Error in response flow", $errorContent);
        }
	}

	public function updateFBLead($FBLeadData, $status = "", $userId =0){
		if(count($FBLeadData)<=0){
			return false;
		}

		if(empty($status)){
			return false;
		}

		$data = array(
			'lead_data' => json_encode($FBLeadData),
			'status' => $status
		);
		if($userId >0){
			$data['user_id'] = $userId;
		}

		$this->FBLeadModel->updateFBLead($data, $FBLeadData['lead_id']);
	}

	public function registerBulkLeads($lead_ids){
		$lead_data = $this->FBLeadModel->getLeadDataForResolvedLeads($lead_ids);

		foreach ($lead_data as $data) {
			$this->createResponseForFBLead($data['lead_data'], true);			
		}
	}

	public function getPreviousDayActiveFormIds($noOfDays){
		$fbFormIds = array();
		$data = $this->FBLeadModel->getPreviousDayActiveFormIds($noOfDays);		
		foreach ($data as $key => $val) {
			$fbFormIds[] = $val['fb_form_id'];
		}

		return $fbFormIds;		
	}

	public function getPreviousDayLeadIdsByFormId($formIds = array(),$noOfDays){
		$leadIds             = array();
		$data                = $this->FBLeadModel->getPreviousDayLeadIdsByFormId($formIds,$noOfDays);			
		$finalData           = array();
		foreach ($data as $key => $val) {
			$finalData[$val['fb_form_id']]['leadIds'][] = $val['lead_id'];
			if(empty($val['lead_data'])){
				$finalData[$val['fb_form_id']]['emptyLeadDataList'][] = array('lead_id' => $val['lead_id'], 'fb_created_on' => $val['fb_lead_created_on']);
			}
		}
		return $finalData;	
	}
}