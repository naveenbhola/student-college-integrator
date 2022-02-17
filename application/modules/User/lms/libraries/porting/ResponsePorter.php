<?php
include_once('AbstractPorter.php');
class ResponsePorter extends AbstractPorter{

    public function port(){
        
        $responses = array();
        $flagFirstTime = 'regular';
		$last_ported_ids = array();

		//$file = '/tmp/porting_time_log'.date('Y-m-d').".txt";
        //$fp = fopen($file,'a');

  		//fwrite($fp,'Porting Id == '.$this->portingEntity->getId()."\n");
  		//fwrite($fp,'before response = '.time()."\n");
        if($this->portingEntity->IsRunFirsttime() == 'no'){

            $responses = $this->getBacklogResponses();
            $flagFirstTime = 'firsttime';

        } else {
            
            $responses = $this->getResponses();

        }

        //fwrite($fp,'after responses = '.time()."\n");
        
        foreach($responses as $tempId=>$data){
            $userIds[] = $data['userid'];
	    	$last_ported_ids[$data['userid']] = $tempId;
        }
		
        if(count($responses) > 0){
            
            $responsesData = array();
            $responsesWithData = $this->getDataForPorting($userIds,$last_ported_ids = array());
	    	
	    	$course_ids = array();
	    	$abroadCourseIds = array();
	    	$nationalCourseIds = array();
		    foreach($responses as $response) {
				
				if($response['listing_type'] == 'course') {

					$listingToCheckForStudyAbroad[] = $response['listing_type_id'];
					$isAbroadCourse = $this->coursemodel->isStudyAboradListing($response['listing_type_id'], 'course');
					if($isAbroadCourse){
						$abroadCourseIds[$response['listing_type_id']]   = $response['listing_type_id'];
					} else {
						$nationalCourseIds[$response['listing_type_id']] = $response['listing_type_id'];
					}

				    $course_ids[$response['listing_type_id']] = $response['listing_type_id'];

				}

		    }
/*		    $studyAbroadCourse = $this->coursemodel->getLocationFromListingTypeId($listingToCheckForStudyAbroad);
		    $studyAbroadCourse = array_column($studyAbroadCourse,'country_id','course_id');

		    foreach ($course_ids as $key => $listing_type_id) {
		      	if($studyAbroadCourse[$listing_type_id] != 2 && $studyAbroadCourse[$listing_type_id] > 0 )
		      	{
					$abroadCourseIds[$listing_type_id]   = $listing_type_id;
				} 
				else 
				{
					$nationalCourseIds[$listing_type_id] = $listing_type_id;
				}
	     	}
*/	   
	    	$customized_mapping_array = array();
	    	$customized_dummy_mapping_array = array();
		    if(count($course_ids)>0) {
				$portingCustomizationMapping = $this->getAllPortingCustomizedFields($this->portingEntity->getClientId());
				$customCourseMappingArray = $portingCustomizationMapping['course_name'];
				$customLevelMappingArray = $portingCustomizationMapping['course_level'];

				/*$customCourseMappingArray      = $this->portingmodel->getCustomizedMappedFields(implode(",",$course_ids),"course_name",'single');
				$customLevelMappingArray       = $this->portingmodel->getCustomizedMappedFields(implode(",",$course_ids),"course_level",'single');
				$customDummyCourseMappingArray = $this->portingmodel->getDummyCustomizedMappedFields($this->portingEntity->getClientId(),"course_name",'single');
				$customDummyLevelMappingArray  = $this->portingmodel->getDummyCustomizedMappedFields($this->portingEntity->getClientId(),"course_level",'single');
				*/

				$customIVRMappingArray = $portingCustomizationMapping['course_name_ivr'];
				$customIVRLevelMappingArray = $portingCustomizationMapping['course_level_ivr'];

								
				$mappings = $this->portingEntity->getMappings();

				if( !empty($abroadCourseIds) && count($abroadCourseIds) > 0 ){
					$this->CI->load->builder('ListingBuilder','listing');
					$listingBuilder = new ListingBuilder();
					$abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
					$coursesData = $abroadCourseRepository->findMultiple($abroadCourseIds);
					unset($abroadCourseIds);

				    foreach($responses as $respId=>$data){
						
		                $responsesData[$respId] = $responsesWithData[$data['userid']];
						$instituteLocationId    = $data['instituteLocationId'];

		                if($coursesData[$data['listing_type_id']]->getMainLocation()->getCountry()->getId() == 2){
		                    $courseLocations = $coursesData[$data['listing_type_id']]->getLocations();
		                }

						$locationObj = !empty($courseLocations[$instituteLocationId]) ? $courseLocations[$instituteLocationId] : $coursesData[$data['listing_type_id']]->getMainLocation();

						$responsesData[$respId]['RES_SA_Course_Country'] = ($locationObj->getCountry()->getName())?($locationObj->getCountry()->getName()):'N.A.';
		                $responsesData[$respId]['tempLMSId']             = $respId;
		                $responsesData[$respId]['Response_ID']           = "R".$respId;
		                $responsesData[$respId]['RES_Course']            = $coursesData[$data['listing_type_id']]->getName();
		                $responsesData[$respId]['RES_Course_City']       = $locationObj->getCity()->getName();
		                $responsesData[$respId]['RES_Course_Locality']   = ($locationObj->getLocality()->getName())?($locationObj->getLocality()->getName()):'N.A.';
		                $responsesData[$respId]['RES_Course_State']      = $locationObj->getState()->getName();
						
						if($data['listing_type'] == 'course') {
						    
				    		if(array_key_exists('Response_Type',$mappings['responsecourse']) ) {
								$responsesData[$respId]['Response_Type'] = $data['action'];
							}

						    $customized_course_level = $customLevelMappingArray[$data['listing_type_id']];
						    $customized_course_name  = $customCourseMappingArray[$data['listing_type_id']];

						    if(is_object($coursesData[$data['listing_type_id']])) {
								
								$default_course_level1 = $coursesData[$data['listing_type_id']]->getCourseLevel1Value();
								$default_course_level2 = $coursesData[$data['listing_type_id']]->getCourseLevel2Value();
								$default_course_name   = $coursesData[$data['listing_type_id']]->getName();

						    }

						    if(array_key_exists('course_level',$mappings)) {
								if($customized_course_level) {
								    $responsesData[$respId]['RES_CUS_Course_Level'] = $customized_course_level;
								} else if($default_course_level2) {
								    $responsesData[$respId]['RES_CUS_Course_Level'] = $default_course_level2;
								} else {
								    $responsesData[$respId]['RES_CUS_Course_Level'] = $default_course_level1;
								}
							}
					    
					        if(array_key_exists('course_name',$mappings)) {
				                if($customized_course_name) {
									$responsesData[$respId]['RES_CUS_Course'] = $customized_course_name;
				                } else {
									$responsesData[$respId]['RES_CUS_Course'] = $default_course_name;
				                }
				            }
				    		
						}

					}

				}

				if( !empty($nationalCourseIds) && count($nationalCourseIds) > 0 ){
					$this->CI->load->builder("CourseBuilder","nationalCourse");
			        $courseBuilder = new CourseBuilder();
			        $courseRepo = $courseBuilder->getCourseRepository();
			        $coursesData = $courseRepo->findMultiple($nationalCourseIds,array('location'));
			        unset($nationalCourseIds);


			        foreach($responses as $respId => $data){
			        	
			        	if($coursesData[$data['listing_type_id']] =='' || empty($coursesData[$data['listing_type_id']])){
			        		continue;
			        	}
						
		                $responsesData[$respId] = $responsesWithData[$data['userid']];
						$instituteLocationId    = $data['instituteLocationId'];
						$courseLocations 		= $coursesData[$data['listing_type_id']]->getLocations();
						$locationObj 			= !empty($courseLocations[$instituteLocationId]) ? $courseLocations[$instituteLocationId] : $coursesData[$data['listing_type_id']]->getMainLocation();
		                //$parentInstituteId = $coursesData[$data['listing_type_id']]->getParentInstituteId();

						//it is primary institute id now
		                $parentInstituteId = $coursesData[$data['listing_type_id']]->getInstituteId();


		                $responsesData[$respId]['RES_Course_City']     = $locationObj->getCityName();
		                $responsesData[$respId]['RES_Course_Locality'] = ($locationObj->getLocalityName())?($locationObj->getLocalityName()):'N.A.';
		                $responsesData[$respId]['RES_Course_State']    = $locationObj->getStateName();
		                $responsesData[$respId]['tempLMSId']           = $respId;
		                
		                global $IVR_Action_Types;
                        if(in_array($data['action'], $IVR_Action_Types)){
                            $responsesData[$respId]['RES_Course']          = Inst_Viewed_Action_Course;
                        }else{
                        	$responsesData[$respId]['RES_Course']          = $coursesData[$data['listing_type_id']]->getName();
                        }

		                
						$responsesData[$respId]['Response_ID']         = "R".$respId;
						
						if($data['listing_type'] == 'course') {
						    if(in_array($data['action'], $IVR_Action_Types)){	         
						    	if($customIVRMappingArray[$parentInstituteId]){
	                            	$customized_course_name  = $customIVRMappingArray[$parentInstituteId];						    		
						    	}else{
						    		$customized_course_name  = Inst_Viewed_Action_Course;
						    	}
						    	if($customIVRLevelMappingArray[$parentInstituteId]){
	                            	$customized_course_level  = $customIVRLevelMappingArray[$parentInstituteId];						    		
						    	}else{
						    		$customized_course_level  = Inst_Viewed_Action_Course;
						    	}
	                        }else{

	                        	$customized_course_level = $customLevelMappingArray[$data['listing_type_id']];
						    	$customized_course_name  = $customCourseMappingArray[$data['listing_type_id']];
						    	
	                        }
						    
						    if(is_object($coursesData[$data['listing_type_id']])) {

						    	$default_course_name = $coursesData[$data['listing_type_id']]->getName();
								$courseTypeInfo      = $coursesData[$data['listing_type_id']]->getCourseTypeInformation();
								
								if(!empty($courseTypeInfo)) {
		       						$courseLevel = $courseTypeInfo['entry_course']->getCourseLevel()->getName();
		       					}

						    }
				    		if(array_key_exists('Response_Type',$mappings['responsecourse']) ) {
								$responsesData[$respId]['Response_Type'] = $data['action'];
							}

							if(array_key_exists('Primary_Institute',$mappings['responsecourse']) ) {
								$responsesData[$respId]['Primary_Institute'] = $coursesData[$data['listing_type_id']]->getInstituteName();
							}

							if(array_key_exists('RES_CUS_Primary_Institute',$mappings['responsecourse']) ) {

								$primaryInstituteId                                  = $coursesData[$data['listing_type_id']]->getInstituteId();
								
							// $customPrimaryInstituteMapping = $portingCustomizationMapping['primary_institute'];
								$customPrimaryInstituteMapping                       = $this->portingmodel->getCustomizedMappedFields($primaryInstituteId,"primary_institute",'single');

								if ($customPrimaryInstituteMapping)
								{
									$responsesData[$respId]['RES_CUS_Primary_Institute'] = $customPrimaryInstituteMapping[$primaryInstituteId];
								}
								else{
									$responsesData[$respId]['RES_CUS_Primary_Institute'] = $coursesData[$data['listing_type_id']]->getInstituteName();;
								}
							}

							if(array_key_exists('Parent_Institute',$mappings['responsecourse']) ) {
								$responsesData[$respId]['Parent_Institute'] = $coursesData[$data['listing_type_id']]->getParentInstituteName();
							}

							if(array_key_exists('RES_CUS_Parent_Institute',$mappings['responsecourse']) ) {

								$parentInstituteId                                  = $coursesData[$data['listing_type_id']]->getParentInstituteId();
								
								// $customParentInstituteMapping = $portingCustomizationMapping['parent_institute'];
								$customParentInstituteMapping                       = $this->portingmodel->getCustomizedMappedFields($parentInstituteId,"parent_institute",'single');

								if ($customParentInstituteMapping){
								$responsesData[$respId]['RES_CUS_Parent_Institute'] = $customParentInstituteMapping[$parentInstituteId];
								}
								else{
									$responsesData[$respId]['RES_CUS_Parent_Institute']=$coursesData[$data['listing_type_id']]->getParentInstituteName();
								}

							}

						    if(array_key_exists('course_level',$mappings)) {
								if($customized_course_level) {
								    $responsesData[$respId]['RES_CUS_Course_Level'] = $customized_course_level;
								} else {
								    $responsesData[$respId]['RES_CUS_Course_Level'] = $courseLevel;
								}
							}
					    
					        if(array_key_exists('course_name',$mappings)) {
				                if($customized_course_name) {
									$responsesData[$respId]['RES_CUS_Course'] = $customized_course_name;
				                } else {
									$responsesData[$respId]['RES_CUS_Course'] = $default_course_name;
				                }
				            }
				    		
						}

					}
			        
			    }

	    	}
	    	
	    	$vendor = $this->portingEntity->getVendorName();

		    foreach($responsesData as $respId => $data){
						    	
		    	if (!empty($vendor)){
		    		$vendorCity[] = $data['Residence_City'];
		    		$vendorState[] = $data['Residence_State'];
		    	}
			    foreach($mappings as $group => $mapping){
				    foreach($mapping as $k=>$v){
					    if($group == 'other'){
						    $responsesData[$respId][$k] = $v;
					    }
				    }
			    }
		    }

		    if (!empty($vendor)){
		    	$responsesData = $this->formatDataAccordingtoVendor($vendorCity,$vendorState,$vendor,$responsesData);
		    }
		    
		    if($this->portingEntity->getFormatType() == 'json'){
				$fieldMap = $this->createJSON($responsesData);
		    } else if($this->portingEntity->getFormatType() == 'XML' || $this->portingEntity->getFormatType() == 'SOAP'){
				$fieldMap = $this->createXML($responsesData);
		    } else {
				$fieldMap = $this->createFieldMap($responsesData);
				ksort($fieldMap);
		    }
		    
	        $this->portToApi($fieldMap, $flagFirstTime , $responsesData);
	    }

	    if($flagFirstTime == 'firsttime'){
	        $this->portingmodel->updateFirstTimePortingStatus($this->portingEntity->getId(), 'yes');
	    }

        //fwrite($fp,'in end responses = '.time()."\n");

	}
	    
    public function portEmail(){

		$responses = array();
        $flagFirstTime = 'regular';

        if($this->portingEntity->IsRunFirsttime() == 'no'){
            $responses = $this->getBacklogResponses();
            $flagFirstTime = 'firsttime';
	    } else {
            $responses = $this->getResponses();
	    }

		foreach($responses as $tempId=>$data){
            $userIds[] = $data['userid'];
	    }

		if(count($responses) >0){

		    $csv = array();
		    $returnArray = Modules::run('enterprise/shikshaDB/getCommonCSVForResponses', $userIds, $responses);
		    
		    $attachment_path = $this->_generateZip($returnArray['CSV']);
		    $date = date("M d, Y");
		    $content = '<pre><span style="font-size: small;">Hi,</span><br /><br /><span style="font-size: small;">Please find attached the details of the response generated on Shiksha.com.</span><br /><br /><span style="font-size: small;">Regards,</span><br /><span style="font-size: small;">Shiksha.com</span><br /><br /><span style="font-size: x-small;">This email has been sent to you because you have activated Response porting on Shiksha.com. If you want to deactivate the porting, please contact your Shiksha sales executive. You may also write to us at <a href="mailto:support@shiksha.com" target="_blank">support@shiksha.com</a>.</span></pre>';
		    $subject = "";
		    $subject .= 'Shiksha Porting: New Response | ' . $date;
		    
		    $mappings = $this->portingEntity->getMappings();
		    $to = array();
		    $cc = array();
		    
		    foreach($mappings as $fieldGroup=>$fieldName){          

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
		    $UserDetailsArray = $this->ldbObj->sgetUserDetails(1, implode(",",$userIds));
		    $UserDataArray = json_decode($UserDetailsArray, true);
		    $this->portToEmail($responses, $flagFirstTime, $UserDataArray);
		    
	    }
	
        if($flagFirstTime == 'firsttime'){
           $this->portingmodel->updateFirstTimePortingStatus($this->portingEntity->getId(), 'yes');
        }

    }
    
    function _generateZip($csv) {

		$name = '/tmp/ResponsePortingData'.time().'.csv';
		$data = $csv;
		$return_path = '/tmp/ResponsePortingData'.time().'.zip';
		$this->zip->add_data($name, $csv);
		$this->zip->archive($return_path);
		return $return_path;
    
    }
    
    function _sendEmailWithAttachement($from,$to,$cc,$subject,$message,$attachment_path) {
	
        $fileatt_type = "application/zip, application/octet-stream"; // File Type
        $fileatt_name = 'ResponsePortingData'.time().'.zip';
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
        $data = chunk_split(base64_encode(file_get_contents($attachment_path)));
		$email_message .= "--{$mime_boundary}\n" .
        "Content-Type: {$fileatt_type};\n" .
        " name=\"{$fileatt_name}\"\n" .
        "Content-Transfer-Encoding: base64\n\n" .
        $data .= "\n\n" .
        "--{$mime_boundary}--\n";

        $ok = mail($to,$subject, $email_message, $headers);
        error_log('mailthesend '.$ok);

    }

    private function getResponses(){

        $criteria = $this->portingEntity->getPortingCriteria();
        $courses = array();
        $responseTypes = array();
        $returnUserSet = array();

		$portingIds = array();
        foreach($criteria as $k=>$v){

            if($v['key'] == 'course'){
                $courses[] = $v['value'];
            }
            if($v['key'] == 'responsetype'){
                $responseTypes[] = $v['value'];
            }
	    	$portingIds = $v['porting_master_id'];

        }

        if(count($courses)>0){
	    	
            $courseUserSet = $this->portingmodel->getResponses($courses, $responseTypes, 'course', $this->portingEntity->getLastPortedId(), $this->portingEntity->getModifictaionDateTime(),$portingIds);

            if (!empty($returnUserSet)) {
            	
                foreach ($courseUserSet as $responseId=>$userData) {
                    $returnUserSet[$responseId]['userid'] = $userData['userid'];
                    $returnUserSet[$responseId]['course'] = $userData['course'];
                    $returnUserSet[$responseId]['listing_type'] = $userData['listing_type'];
                    $returnUserSet[$responseId]['listing_type_id'] = $userData['listing_type_id'];
				    $returnUserSet[$responseId]['action'] = $userData['action'];
				    $returnUserSet[$responseId]['submit_date'] = $userData['submit_date'];
				    $returnUserSet[$responseId]['custom_flag'] = $userData['custom_flag'];
                }

            } else {
                $returnUserSet = $courseUserSet;
            }

        }
        return $returnUserSet;

    }

    private function getBacklogResponses(){

        $returnUserSet = array();
        $startDate = $this->portingEntity->getFirsttimeStartdate();

        if((!empty($startDate)) && $startDate != '0000-00-00') {
            $criteria = $this->portingEntity->getPortingCriteria();
            $courses = array();
            $responseTypes = array();

            foreach($criteria as $k=>$v){

                if($v['key'] == 'course'){
                    $courses[] = $v['value'];
                }
                if($v['key'] == 'responsetype'){
                    $responseTypes[] = $v['value'];
                }
		
	    		$portingIds = $v['porting_master_id'];
	    	
        	}
	    	
            if(count($courses)>0){

                $courseUserSet = $this->portingmodel->getBackLogResponses($courses, $responseTypes, 'course', $startDate, $portingIds);
                if (!empty($returnUserSet)) {

                    foreach ($courseUserSet as $responseId=>$userData) {
                        
                        $returnUserSet[$responseId]['userid'] = $userData['userid'];
                        $returnUserSet[$responseId]['course'] = $userData['course'];
                        $returnUserSet[$responseId]['listing_type'] = $userData['listing_type'];
                    	$returnUserSet[$responseId]['listing_type_id'] = $userData['listing_type_id'];
						$returnUserSet[$responseId]['action'] = $userData['action'];
						$returnUserSet[$responseId]['submit_date'] = $userData['submit_date'];
						$returnUserSet[$responseId]['custom_flag'] = $userData['custom_flag'];

                    }

                } else {

                    $returnUserSet = $courseUserSet;

                }

            }

        }

        return $returnUserSet;

    }

}


