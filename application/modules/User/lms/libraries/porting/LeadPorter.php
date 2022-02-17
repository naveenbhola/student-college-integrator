<?php
include_once('AbstractPorter.php');
class LeadPorter extends AbstractPorter{

    public function port($portingIds){
        $leads = array();
	    $leads_data = array();
        $flagFirstTime = 'regular';

		//$file = '/tmp/porting_time_log'.date('Y-m-d').".txt";
        //$fp = fopen($file,'a');

  		//fwrite($fp,'Porting Id == '.$this->portingEntity->getId());
  		//fwrite($fp,'before lead = '.time()."\n");

        if($this->portingEntity->IsRunFirsttime() == 'no'){

            $leads_data = $this->getBacklogLeads();
            $flagFirstTime = 'firsttime';

		} else{
            $leads_data = $this->getLeads();
		}

        //fwrite($fp,'after leads = '.time()."\n");
		$originalBaseCoursesValues = $this->fetchBaseCourseValueFromRedis();
		foreach ($leads_data as $key => $leadData) {

			$interestCriteria = array();
			$interestCriteria['stream'] = $leadData['stream'];
			$interestCriteria['subStream'] = ($leadData['substream'])?(array($leadData['substream'])):0;
			$interestCriteria['ProfileType'] = $leadData['ProfileType'];
			$interestCriteria['spezFormat'] = 'noSpecMapping_data';

			$leadMapToSearchAgent[$leadData['leadid']] = $leadData['searchAgentid'];

			if($this->portingEntity->getType() == 'matched_response') {
				$interestCriteria['ProfileType'] = 'implicit';
			}
			
			if(!empty($leadData['ProfileType']) && $leadData['ProfileType'] !=''){
				$interestCriteria['leadProfileTypeMap'] = array($leadData['leadid']=>$leadData['ProfileType']);
			}
			$interestCriteria['isPorting'] = TRUE;
			$userDataArray = array();
			if(!empty($interestCriteria)) {
	            $userDataArray = Modules::run('MIS/SADownloadleads/getLeadDataFromSolr', array($leadData['leadid']), $interestCriteria, TRUE, NULL, TRUE);

	            $userDataArray[$leadData['leadid']] = $userDataArray[0];
	            unset($userDataArray[0]);
	            
	            Global $managementStreamMR;
                Global $engineeringtStreamMR;
	            if($interestCriteria['stream'] == $managementStreamMR || $interestCriteria['stream'] == $engineeringtStreamMR){

	            	$search_agents_all_display_array = $this->search_agent_main_model->getAllSADisplayData(1,array($leadData['searchAgentid']));
	            	$tempDisplay = $search_agents_all_display_array[0];
	            	$inputData = json_decode(base64_decode($tempDisplay[0]['inputdata']),true);
	            	
	            	$userDataArray = Modules::run('enterprise/enterpriseSearch/filterMrProfiles', $inputData, $userDataArray);
                }

	        }

	        $leads_data[$key]['UserData'] = $userDataArray[$leadData['leadid']];

		}
		// _p($leads_data);die;
		if(count($leads_data) >0) {

       		$final_lead_data = array();
			$final_lead_data = $this->filterData($leads_data);
			$leads = $final_lead_data['leads'];
        	$credits = $final_lead_data['credits'];
        	$userPrefArray = $final_lead_data['userPrefArray'];

		}
		// _p($leads_data);die;
        if(count($leads) > 0){

            $leadsWithData = $this->getDataForPorting(array_values($leads), array_keys($leads));
    		$mappings = $this->portingEntity->getMappings();
	        $customizations = $this->getAllPortingCustomizedFields($this->portingEntity->getClientId());
	        $leadsData = array(); $matchedResponseCourseIds = array();
	        $courseObjsToFetch = array();
            foreach($leads as $matchId=>$userId) {
                $leadsData[$userId] = $leadsWithData[$userId];
                $leadsData[$userId]['matchId'] = $matchId;
               	
				if(array_key_exists('hierarchy',$mappings) || array_key_exists('basecourse',$mappings) || 
					array_key_exists('ldb_stream', $mappings)) {
			        $leadsData = $this->createMappings($leadsData,$leads_data,$matchId,$userId,'basecourse');				
				}

				if(array_key_exists('matched_response',$mappings) || array_key_exists('matched_response_custom',$mappings) || array_key_exists('course_level',$mappings)) {
				    $returnData['matched_response'] = $this->portingmodel->getMatchedResponseDataForPorting($userId, $matchId);
				    $leadsData = $this->createMappings($leadsData,$leads_data,$matchId,$userId,'matched_response',$returnData);
				    $courseObjsToFetch[]= $returnData['matched_response'][$userId]['MAT_RES_CourseId'];

				    if(array_key_exists('matched_response_custom',$mappings)) {
				    	$matchedResponseCourseIds[] = $returnData['matched_response'][$userId]['MAT_RES_CourseId'];
					}
				}

	        }
	       	
	       	$this->CI->load->builder("nationalCourse/CourseBuilder");
        	$builder          = new CourseBuilder();
        	$courseRepository = $builder->getCourseRepository();
        	
        	if(array_key_exists('matched_response',$mappings) || array_key_exists('matched_response_custom',$mappings) || array_key_exists('course_level',$mappings)) {
        		$courseObjs =  $courseRepository->findMultiple($courseObjsToFetch , array('basic'), false, false);
        	}

	       /* if(!empty($matchedResponseCourseIds)) {
	        	$customCourseMappingArray = $this->portingmodel->getCustomizedMappedFields(implode(",",$matchedResponseCourseIds),"mr_course",'single');
	    	}*/
	        $vendor = $this->portingEntity->getVendorName();

	        foreach($leadsData as $userId=>$data){
	        	/*if($customCourseMappingArray[$data['MAT_RES_CourseId']] != '') {
	        		$leadsData[$userId]['MAT_RES_CUS_COURSE'] = $customCourseMappingArray[$data['MAT_RES_CourseId']];
	        	}*/
	        	$courseId = $leadsData[$userId]['MAT_RES_CourseId'];
	        	if(!empty($leadsData[$userId]['MAT_RES_CourseId'])){
		        	if(array_key_exists($courseId, $courseObjs)){
		        		$leadsData[$userId]['MAT_RES_Course_Level'] = $courseObjs[$courseId]->getCourseTypeInformation()['entry_course']->getCourseLevel()->getName();
		        		$leadsData[$userId]['MAT_RES_Primary_Institute'] = $courseObjs[$courseId]->getInstituteName();
		        		$leadsData[$userId]['MAT_RES_Parent_Institute'] = $courseObjs[$courseId]->getParentInstituteName();
	        			$leadsData[$userId]['MAT_RES_CUS_Course_Level'] = $courseObjs[$courseId]->getCourseTypeInformation()['entry_course']->getCourseLevel()->getName();
	        			$leadsData[$userId]['MAT_RES_CUS_Parent_Institute'] = $courseObjs[$courseId]->getParentInstituteName();
	        			$leadsData[$userId]['MAT_RES_CUS_Primary_Institute'] = $courseObjs[$courseId]->getInstituteName();
		
		        	}
		        	if(array_key_exists('matched_response',$mappings) || array_key_exists('course_level',$mappings) || array_key_exists('matched_response_custom',$mappings) ) {
		        		$parentInstituteId = $courseObjs[$courseId]->getParentInstituteId();
		        		$instituteId = $courseObjs[$courseId]->getInstituteId();
		        		if ($customizations['ldb_course_level'][$courseId]){
		        			$leadsData[$userId]['MAT_RES_CUS_Course_Level'] =$customizations['ldb_course_level'][$courseId];
		        		}
		        		if ($customizations['ldb_parent_institute'][$parentInstituteId]){
		        			$leadsData[$userId]['MAT_RES_CUS_Parent_Institute'] =$customizations['ldb_parent_institute'][$parentInstituteId];
		        		}
		        		if ($customizations['ldb_primary_institute'][$instituteId]){
		        			$leadsData[$userId]['MAT_RES_CUS_Primary_Institute'] =$customizations['ldb_primary_institute'][$instituteId];
		        		}
		        		if ($customizations['mr_course'][$courseId]){
		        			$leadsData[$userId]['MAT_RES_CUS_COURSE'] = $customizations['mr_course'][$courseId];
		        		}
		        		
					}

		        }

		        if(array_key_exists('hierarchy',$mappings) || array_key_exists('basecourse',$mappings) || array_key_exists('ldb_stream',$mappings)){
		        		$streamId = $leadsData[$userId]['LDB_CUS_StreamId'];
		        		$baseCourseArray = $leadsData[$userId]['LDB_CUS_BaseCourseIds'];
		        		if ($customizations['stream'][$streamId]){
		        			  $leadsData[$userId]['LDB_CUS_Stream'] = $customizations['stream'][$streamId];			
		        		}
		        		if ($customizations['base_course']){
		        			$leadsData[$userId]['LDB_CUS_BaseCourse'] = $this->getCustomizedBaseCourse($customizations['base_course'],$baseCourseArray,$originalBaseCoursesValues);
		        		}
		        }

	        	unset($leadsData[$userId]['MAT_RES_CourseId']);

	        	if (!empty($vendor)){
		    		$vendorCity[] = $data['Residence_City'];
		    		$vendorState[] = $data['Residence_State'];
		    	}

			    foreach($mappings as $group=>$mapping){
				    foreach($mapping as $k=>$v){
					    if($group == 'other'){
						    $leadsData[$userId][$k] = $v;
					    }
				    }
			    }
		    	$searchAgentDeliveryCount[$leadMapToSearchAgent[$userId]] += 1;
		    }
		   
		    if (!empty($vendor)){
		    	$leadsData = $this->formatDataAccordingtoVendor($vendorCity,$vendorState,$vendor,$leadsData);
		    }

		    

		    if($this->portingEntity->getFormatType() == 'json'){
				$fieldMap = $this->createJSON($leadsData);
		    } else if($this->portingEntity->getFormatType() == 'XML' || $this->portingEntity->getFormatType() == 'SOAP'){
				$fieldMap = $this->createXML($leadsData);
		    } else {
				$fieldMap = $this->createFieldMap($leadsData);
				ksort($fieldMap);
		    }
		    unset($courseObjs);
            $this->portToApi($fieldMap, $flagFirstTime, $leadsData, $credits, $portingIds);
        }

        //update count


        if(count($searchAgentDeliveryCount)>0){
        	foreach ($searchAgentDeliveryCount as $searchAgentId => $deliveryCount) {
        		$this->portingmodel->updateSearchAgentDeliveryCount($searchAgentId, $deliveryCount);
        	}
        }

        if($flagFirstTime == 'firsttime'){
            $this->portingmodel->updateFirstTimePortingStatus($this->portingEntity->getId(), 'yes');
        }

        //fwrite($fp,'in end leads = '.time()."\n");

    }
    
    public function portEmail(){

        $leads = array();
        $flagFirstTime = 'regular';
        $leads_data = array();
		$userPrefArray = array();

        if($this->portingEntity->IsRunFirsttime() == 'no'){
            $leads_data = $this->getBacklogLeads();
            $flagFirstTime = 'firsttime';
        }
        else{
            $leads_data = $this->getLeads();
        }

		foreach ($leads_data as $key => $leadData) {

			$interestCriteria = array();
			$interestCriteria['stream'] = $leadData['stream'];
			$interestCriteria['subStream'] = ($leadData['substream'])?(array($leadData['substream'])):0;
			$interestCriteria['ProfileType'] = $leadData['ProfileType'];
			$interestCriteria['spezFormat'] = 'noSpecMapping_data';
			
			if($this->portingEntity->getType() == 'matched_response') {
				$interestCriteria['ProfileType'] = 'implicit';
			}

			if(!empty($leadData['ProfileType']) && $leadData['ProfileType'] !=''){
				$interestCriteria['leadProfileTypeMap'] = array($leadData['leadid']=>$leadData['ProfileType']);
			}

			$userDataArray = array();
			if(!empty($interestCriteria)) {
	            $userDataArray = Modules::run('MIS/SADownloadleads/getLeadDataFromSolr', array($leadData['leadid']), $interestCriteria, TRUE, NULL, TRUE);

	            $userDataArray[$leadData['leadid']] = $userDataArray[0];
	            unset($userDataArray[0]);

	            Global $managementStreamMR;
                Global $engineeringtStreamMR;
	            if($interestCriteria['stream'] == $managementStreamMR || $interestCriteria['stream'] == $engineeringtStreamMR){

	            	$search_agents_all_display_array = $this->search_agent_main_model->getAllSADisplayData(1,array($leadData['searchAgentid']));
	            	$tempDisplay = $search_agents_all_display_array[0];
	            	$inputData = json_decode(base64_decode($tempDisplay[0]['inputdata']),true);
	            	
	            	$userDataArray = Modules::run('enterprise/enterpriseSearch/filterMrProfiles', $inputData, $userDataArray);
                }

	        }

	        $leads_data[$key]['UserData'] = $userDataArray[$leadData['leadid']];

	        if($this->portingEntity->getType() == 'matched_response') {
		        $matchedResponseData = $this->portingmodel->getMatchedResponseDataForPorting($leadData['leadid'], $key, 'email');
		        $leads_data[$key]['UserData']['Matched Response For'] = $matchedResponseData[$leadData['leadid']]['Matched Response For'];
		        $leads_data[$key]['UserData']['Response Date'] = $matchedResponseData[$leadData['leadid']]['Response Date'];
		    }

		}
		
		if(count($leads_data) >0){

			$final_lead_data = array();
			$final_lead_data = $this->filterData($leads_data);
			$leads = $final_lead_data['leads'];
        	$credits = $final_lead_data['credits'];
        	$userPrefArray = $final_lead_data['userPrefArray'];

		}

		if(count($leads) >0){
		    $csv = array();
		    $study_abroad_leads = array();
		    $national_leads = array();

		    // $userPrefArray = $this->usermodel->getUserPrefById($leads);
		    
		    if($this->portingEntity->getType() == 'matched_response') {

		    	$national_mr_leads = $leads;

		    	if(count($national_mr_leads) > 0){
					$userIdList = array_unique($national_mr_leads);
					$csvNationalMR = Modules::run('enterprise/shikshaDB/getCommonCSVForLeads', $userIdList, 'nationalMR', $leads_data);
					$csvFinal_indiaMR = $csvNationalMR;
			    }

			    $attachment_path = $this->_generateZip($csvFinal_indiaMR);

		    } else if($this->portingEntity->getType() == 'lead') {

				foreach($leads as $userId){
					if($userPrefArray[$userId]['ExtraFlag'] == "studyabroad"){
					    $study_abroad_leads[] = $userId;
					} else{
			    		$national_leads[] = $userId;
					}
	            }
	            
	            if(count($study_abroad_leads) > 0){
					$userIdList = array_unique($study_abroad_leads);
					$csvStudyAbroad = Modules::run('enterprise/shikshaDB/getCommonCSVForLeads', $userIdList, 'studyabroad');                  
					$csvFinal_abroad = $csvStudyAbroad;
			    }
			    
			    if(count($national_leads) > 0){
					$userIdList = array_unique($national_leads);
					$csvNational = Modules::run('enterprise/shikshaDB/getCommonCSVForLeads', $userIdList, 'national', $leads_data);
					$csvFinal_india = $csvNational;
			    }
			    
			    $attachment_path = $this->_generateZip($csvFinal_india,$csvFinal_abroad);

	        }

		    $date = date("M d, Y");
		    $content = '<pre><span style="font-size: small;">Hi,</span><br /><br /><span style="font-size: small;">Please find attached the details of the lead generated on Shiksha.com.</span><br /><br /><span style="font-size: small;">Regards,</span><br /><span style="font-size: small;">Shiksha.com</span><br /><br /><span style="font-size: x-small;">This email has been sent to you because you have activated Lead porting on Shiksha.com. If you want to deactivate the porting, please contact your Shiksha sales executive. You may also write to us at <a href="mailto:support@shiksha.com" target="_blank">support@shiksha.com</a>.</span></pre>';
		    $subject = "";
		    $subject .= 'Shiksha Porting: New Lead | ' . $date;

		    $mappings = $this->portingEntity->getMappings();
		    
		    $to = array();
		    $cc = array();
	        
	        error_log('mappingvalues'. print_r($mappings,true));
		    
		    foreach($mappings as $fieldGroup=>$fieldName)  {          
				if($fieldGroup == 'email_to') {
				    for($i=0; $i<count($mappings[$fieldGroup]['Email_To']); $i++) {
						$to[] =  $mappings[$fieldGroup]['Email_To'][$i];
				    }
                } else {
			    	for($i=0; $i<count($mappings[$fieldGroup]['Email_Cc']); $i++) {
                        $cc[] =  $mappings[$fieldGroup]['Email_Cc'][$i];
			    	}
                } 
            }

	    	$to = implode(",",$to);
	    	$cc = implode(",",$cc);
	    	$this->_sendEmailWithAttachement('info@shiksha.com',$to,$cc,$subject,$content,$attachment_path);

	    	$UserDetailsArray = $this->ldbObj->sgetUserDetails(1, implode(",",array_values($leads)));
	    	$UserDataArray = json_decode($UserDetailsArray, true);
	    	$this->portToEmail($leads, $flagFirstTime, $UserDataArray, $credits);

        }
	
        if($flagFirstTime == 'firsttime'){
	    	$this->portingmodel->updateFirstTimePortingStatus($this->portingEntity->getId(), 'yes');
        }
    }

	/*
		Function to filter final leads from all the leads
	*/
    function filterData($leads_data) {

    	$final_data = array();
    	if((!empty($leads_data)) && (count($leads_data)>0)) {

			$subscription_id = $this->portingEntity->getSubscriptionId();

			$this->CI->load->library('Subscription_client');
			$sumsObject = new Subscription_client();

			$subscriptionDetails =  $sumsObject->getSubscriptionDetails(1,$subscription_id);
			$subscriptionBaseProdRemainingQuantity = $subscriptionDetails[0]['BaseProdRemainingQuantity'];

			if($subscriptionBaseProdRemainingQuantity > 0) {

				$final_data = array();

				if($this->portingEntity->getType() == 'matched_response') {

					$final_data = $this->filterMRData($leads_data, $subscriptionBaseProdRemainingQuantity);

				} else if($this->portingEntity->getType() == 'lead') {

					$final_data = $this->filterLeadData($leads_data, $subscriptionBaseProdRemainingQuantity);

				}

			}

		}
		return $final_data;

    }
    
    /*
		Function to filter final MR leads from all the final leads data
	*/
    function filterMRData($leads_data, $subscriptionBaseProdRemainingQuantity) {
    	
		global $MRPricingArray;
		$final_data = array();
		foreach($leads_data as $id=>$lead_data){

			$required_credit = $MRPricingArray[$lead_data['UserData']['StreamId']]['view'];

			if($subscriptionBaseProdRemainingQuantity >= $required_credit) {
				$final_data['leads'][$id] = $lead_data['leadid'];
				$final_data['credits'][$id] = $required_credit;
				$subscriptionBaseProdRemainingQuantity = $subscriptionBaseProdRemainingQuantity-$required_credit;
			} else {
				break;
			}

		}

		if(!empty($final_data['leads'])) {
			$userPrefArray = $this->usermodel->getUserPrefById($final_data['leads']);		
			$final_data['userPrefArray'] = $userPrefArray;  		
		}

		return $final_data;
    }

	/*
		Function to filter Lead data from all the final lead data
	*/
    function filterLeadData($leads_data, $subscriptionBaseProdRemainingQuantity) {

    	$leads = array();$final_data = array();
		foreach($leads_data as $id=>$lead_data){
			$leads[] = $lead_data['leadid'];
		}
		
		$userPrefArray = $this->usermodel->getUserPrefById($leads);
        $this->CI->load->model('LDB/ldbmodel');
        $this->ldbmodel = new LdbModel();

		foreach($leads_data as $id=>$lead_data) {

			if(!empty($lead_data['leadid'])) {
				$requestArray = array();
				$requestArray = array($lead_data['leadid']);
				$creditInfo = array();
				$ExtraFlag = "";

				if($userPrefArray[$lead_data['leadid']]['ExtraFlag'] == 'testprep' || $userPrefArray[$lead_data['leadid']]['ExtraFlag'] == 'studyabroad') {

					if($userPrefArray[$lead_data['leadid']]['ExtraFlag'] == 'testprep') {
						$ExtraFlag = "true";
					}
					$DesiredCourse = '';
					$DesiredCourse = $userPrefArray[$lead_data['leadid']]['DesiredCourse'];
					$creditInfo = $this->ldbmodel->getCreditToConsume($requestArray, 'view', $ExtraFlag, $DesiredCourse);

					if(!empty($creditInfo[0]['deductcredit'])) {
						$required_credit = $creditInfo[0]['deductcredit'];

						if($subscriptionBaseProdRemainingQuantity >= $required_credit) {
	        				$final_data['leads'][$id] = $lead_data['leadid'];
	        				$final_data['credits'][$id] = $required_credit;
	        				$final_data['userPrefArray'][$lead_data['leadid']] = $userPrefArray[$lead_data['leadid']];
	        				$subscriptionBaseProdRemainingQuantity = $subscriptionBaseProdRemainingQuantity-$required_credit;
						} else {
							break;
						}
					}

				} else {

					$required_credit = $lead_data['UserData']['View Credit'];
					if($subscriptionBaseProdRemainingQuantity >= $required_credit) {
        				$final_data['leads'][$id] = $lead_data['leadid'];
        				$final_data['credits'][$id] = $required_credit;
        				$final_data['userPrefArray'][$lead_data['leadid']] = $userPrefArray[$lead_data['leadid']];
        				$subscriptionBaseProdRemainingQuantity = $subscriptionBaseProdRemainingQuantity-$required_credit;
					} else {
						break;
					}
				}
			}

		}

		return $final_data;

    }

    function _generateZip($csv_india,$csv_abroad) {
		
        if(!empty($csv_india)) {
			$name_india = '/tmp/india_porting'.time().'.csv';	
			$this->zip->add_data($name_india, $csv_india);
		}

        if(!empty($csv_abroad)) {
            $name_abroad = '/tmp/abroad_porting'.time().'.csv';       
            $this->zip->add_data($name_abroad, $csv_abroad);
        }
        
		$return_path = '/tmp/LeadPortingData'.time().'.zip';
		$this->zip->archive($return_path);
		
		return $return_path;
	}
    
    function _sendEmailWithAttachement($from,$to,$cc,$subject,$message,$attachement_path) {
        $fileatt_type = "application/zip, application/octet-stream"; // File Type
        $fileatt_name = 'LeadPortingData'.time().".zip"; 
        $email_message = $message; 
        $headers = "From: ".$from."\r\n"."Cc: ".$cc;
		$semi_rand = md5(time());
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
		$headers .= "\nMIME-Version: 1.0\n" .
		"Content-Type: multipart/mixed;\n" .
		" boundary=\"{$mime_boundary}\"";
		$email_message .= "This is a multi-part message in MIME format.\n\n" .
		"--{$mime_boundary}\n" .
		"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
		"Content-Transfer-Encoding: 7bit\n\n" .
		$email_message .= "\n\n";
		$data = chunk_split(base64_encode(file_get_contents($attachement_path)));
		$email_message .= "--{$mime_boundary}\n" .
		"Content-Type: {$fileatt_type};\n" .
		" name=\"{$fileatt_name}\"\n" .
		"Content-Transfer-Encoding: base64\n\n" .
		$data .= "\n\n" .
		"--{$mime_boundary}--\n";
		error_log('toId '.$to);
		error_log('emailmessage '.$email_message); 
        $ok = mail($to,$subject, $email_message, $headers);
        error_log('mailthesend '.$ok); 
    }
    
    private function getLeads(){
        $criteria = $this->portingEntity->getPortingCriteria();

        $searchAgents = array();
        $returnUserSet = array();
	
        foreach($criteria as $k=>$v){
            if($v['key'] == 'searchagent'){
                $searchAgents[] = $v['value'];
            }
	    
	    	$portingId=$v['porting_master_id'];
        }

		if(count($searchAgents)>0){
            $returnUserSet = $this->portingmodel->getLeads($this->portingEntity->getId(),$searchAgents,$this->portingEntity->getLastPortedId(),$this->portingEntity->getModifictaionDateTime(),$portingId);
        }
		return $returnUserSet;
    }

     private function getBacklogLeads(){
     	mail('teamldb@shiksha.com', 'getBacklogLeads in Lead Porter', 'We should not receive this email as functionality has been stopped');
     	return;
     	
        $returnUserSet = array();
        $startDate = $this->portingEntity->getFirsttimeStartdate();
        if((!empty($startDate)) && $startDate != '0000-00-00') {
            $criteria = $this->portingEntity->getPortingCriteria();
            $searchAgents = array();
	
            foreach($criteria as $k=>$v){
                if($v['key'] == 'searchagent'){
                    $searchAgents[] = $v['value'];
                }
				$portingId=$v['porting_master_id'];
            }
            if(count($searchAgents)>0){
                $returnUserSet = $this->portingmodel->getBackLogLeads($searchAgents,$startDate, $portingId);
            }
        }
        return $returnUserSet;
    }

    

    private function getCustomizedBaseCourse($mappings,$baseCourseArray, $originalBaseCourses){
    	foreach ($baseCourseArray as $key => $baseCourseId) {
			if ($mappings[$baseCourseId]){
				$retBaseCourse = $retBaseCourse.','.($mappings[$baseCourseId]);
			}
			else{
				$retBaseCourse = $retBaseCourse.','.$originalBaseCourses[$baseCourseId];
			}
    	}
 	   return substr($retBaseCourse,1);
    }

    private function createMappings($leadsData,$leads_data,$matchId,$userId,$condition,$returnData){
    	if ($condition == 'basecourse'){
    		$leadsData[$userId]['LDB_Stream'] = ($leads_data[$matchId]['UserData']['Stream'])?($leads_data[$matchId]['UserData']['Stream']):'N.A.';
	        $leadsData[$userId]['LDB_SubStream'] = ($leads_data[$matchId]['UserData']['Sub Stream'])?($leads_data[$matchId]['UserData']['Sub Stream']):'N.A.';
	        $leadsData[$userId]['LDB_Specialization'] = ($leads_data[$matchId]['UserData']['Specialization'])?($leads_data[$matchId]['UserData']['Specialization']):'N.A.';
	        $leadsData[$userId]['LDB_BaseCourse'] = ($leads_data[$matchId]['UserData']['Course'])?($leads_data[$matchId]['UserData']['Course']):'N.A.';
	        $leadsData[$userId]['LDB_EducationType'] = ($leads_data[$matchId]['UserData']['Mode'])?($leads_data[$matchId]['UserData']['Mode']):'N.A.';
	        $leadsData[$userId]['LDB_CUS_Stream'] = $leads_data[$matchId]['UserData']['Stream'];
	        $leadsData[$userId]['LDB_CUS_BaseCourse'] = $leads_data[$matchId]['UserData']['Course'];
	        $leadsData[$userId]['LDB_CUS_StreamId'] = $leads_data[$matchId]['UserData']['StreamId'];
	        $leadsData[$userId]['LDB_CUS_BaseCourseIds'] = $leads_data[$matchId]['UserData']['BaseCourseIds'];
	        return $leadsData;
    	}

    	if ($condition == 'matched_response'){
    		$leadsData[$userId]['MAT_RES_Course'] = $returnData['matched_response'][$userId]['MAT_RES_Course'];
			$leadsData[$userId]['MAT_RES_CUS_COURSE'] = $returnData['matched_response'][$userId]['MAT_RES_Course'];
			$leadsData[$userId]['MAT_RES_CourseId'] = $returnData['matched_response'][$userId]['MAT_RES_CourseId'];
			return $leadsData;	    
    	}
    }

}
