<?php
include_once('AbstractPorter.php');
class examResponsePorter extends AbstractPorter{

    public function port($data){
        $responses = array();
        $flagFirstTime = 'regular';
        if($this->portingEntity->IsRunFirsttime() == 'no'){            
            $flagFirstTime = 'firsttime';
        }

        //fwrite($fp, "Get Exam Response Details for porting Id : ".$this->portingEntity->getId()." Start ==".time()."\n");
        $responses = $this->getResponses($data);
        //fwrite($fp, "Get Exam Response Details for porting Id : ".$this->portingEntity->getId()." End ==".time()."\n");
        $last_ported_ids = array();
        $examGroupIds = array();
        if(is_array($responses) && count($responses) >0){
	        foreach($responses as $tempId=>$data){
	            $userIds[] = $data['userId'];
	            $last_ported_ids[$data['userId']] = $data['id'];
	            $examGroupIds[] = $data['entityValue'];
	        }
            $responsesData = array();
            $userIds = array_unique($userIds);
            $responsesWithData = $this->getDataForPorting($userIds,array());

	    	// get user rxam response details
	    	$examGroupIds = array_unique($examGroupIds);
	    	$result = $this->portingmodel->getUserExamResponseDetails($examGroupIds);
	    	unset($examGroupIds);
	    	$groupDetails = array();
	    	foreach ($result as $row) {
	    		$groupId = $row['groupId'];
	    		unset($row['groupId']);
	    		$groupDetails[$groupId] = $row;
	    	}
	    	unset($result);
	    	$responsePortingData = array();
	    	$mappings = $this->portingEntity->getMappings();

	    	$vendor = $this->portingEntity->getVendorName();

	    	foreach ($responses as $key => $value) {
	    		$responsePortingData[$value['tempLmsId']] = $responsesWithData[$value['userId']];

	    		if (!empty($vendor)){
		    		$vendorCity[] = $responsesWithData[$value['userId']]['Residence_City'];
		    		$vendorState[] = $responsesWithData[$value['userId']]['Residence_State'];
		    	}

	    		$responsePortingData[$value['tempLmsId']]['tempLMSId'] = $value['tempLmsId'];
	    		$responsePortingData[$value['tempLmsId']]['examResponseAllocationId'] = $value['id'];
	    		$responsePortingData[$value['tempLmsId']]['Response_ID'] = 'ER'.$value['tempLmsId'];
	    		$responsePortingData[$value['tempLmsId']]['EXAM_Name'] = $groupDetails[$value['entityValue']]['examName'];
	    		$responsePortingData[$value['tempLmsId']]['Exam_Course_Group'] = $groupDetails[$value['entityValue']]['groupName'];

	    		if(array_key_exists('Exam_Response_Type', $mappings['exam_response'])){
	    			$responsePortingData[$value['tempLmsId']]['Exam_Response_Type'] = $value['action'];
	    		}

	    		foreach($mappings['other'] as $fieldName => $fieldValue){
	    			$responsePortingData[$value['tempLmsId']][$fieldName] = $fieldValue;
			    }
	    	}

	    	if (!empty($vendor)){
		    	$responsePortingData = $this->formatDataAccordingtoVendor($vendorCity,$vendorState,$vendor,$responsePortingData);
		    }
	    	unset($responsesWithData);
	    	unset($groupDetails);

		    if($this->portingEntity->getFormatType() == 'json'){
				$fieldMap = $this->createJSON($responsePortingData);
		    } else if($this->portingEntity->getFormatType() == 'XML' || $this->portingEntity->getFormatType() == 'SOAP'){
				$fieldMap = $this->createXML($responsePortingData);
		    } else {
				$fieldMap = $this->createFieldMap($responsePortingData);
				ksort($fieldMap);
		    }
		    
	        $this->portToApi($fieldMap, $flagFirstTime , $responsePortingData);
		}else{
			$this->portingmodel->updateLastPortedId($this->portingEntity->getId(), $data['maxERAllocationId']);
		}

	    if($flagFirstTime == 'firsttime' && $this->portingEntity->getStatus() == "live"){
	        $this->portingmodel->updateFirstTimePortingStatus($this->portingEntity->getId(), 'yes');
	    }
	    //fwrite($fp, "Data ported for exam resposne porting for porting Id : ".$this->portingEntity->getId()." End ==".time()."\n");
	}

    private function getResponses($data){
    	
    	$data['lastPortedId'] = $this->portingEntity->getLastPortedId();
    	$data['criteria'] = $this->portingEntity->getPortingCriteria();
    	$data['startDate'] = $this->portingEntity->getFirsttimeStartdate();
    	$data['isRunFirstTime'] = $this->portingEntity->IsRunFirsttime();
    	$data['portingStatus'] = $this->portingEntity->getStatus();
    	$data['lastModificationDate'] = $this->portingEntity->getModifictaionDateTime();
    	$criteria = $this->portingEntity->getPortingCriteria();
    	$data['subscriptionIds'] = $criteria['examSubscription'];
    	$data['responseTypes'] = $criteria['responseTypes'];

        if($data['isRunFirstTime'] == "no"){
        	if((empty($startDate)) || $startDate == '0000-00-00') {
        		if($data['lastPortedId'] <0){
        			return array();
        		}
        	}
        }

        if(count($data['subscriptionIds'])>0){
        	$returnUserSet = $this->portingmodel->getExamResponses($data);
			return $returnUserSet;        	
        }
        return array();
    }
}


