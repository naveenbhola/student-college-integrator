<?php
class LDB_Client{

	var $CI;
	var $cacheLib;
	function init(){
		$this->CI = &get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server(LDB_SERVER_URL, LDB_SERVER_PORT);
		$this->cacheLib = new cacheLib();
	}

    /**
     * initread API for Read DB call
     */

    function initread()
    {
        set_time_limit(0);
        $this->CI = & get_instance();
        $this->CI->load->helper ('url');
        $this->CI->load->library('xmlrpc');
        $this->CI->load->library('cacheLib');
        $this->CI->xmlrpc->server(LDB_SERVER_READ_URL, LDB_SERVER_PORT);
        $this->CI->xmlrpc->set_debug(0);
        $this->CI->xmlrpc->timeout(6000);
    }

	function s_submitSearchQuery($appID,$submitArr,$user,$userGroup,$start=0,$end=20,$key=""){
            $xArr = $submitArr;
            unset($xArr['requestTime']);
            error_log("LDBX array ".json_encode($xArr)."-".$key);
            $search_key = md5(json_encode($xArr)."-".$key);
            error_log("LDBX libkey = ".$key);
            error_log("LDBX libsearchkey = ".$search_key);
		$this->init();
		$this->CI->xmlrpc->method('s_submitSearchQuery');
		$submitArrStr = json_encode($submitArr);
		$request = array($appID,$submitArrStr,$user,$userGroup,$start,$end,$search_key);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return utility_decodeXmlRpcResponse($this->CI->xmlrpc->display_response());
		}
	}

	function searchLDB($appID,$submitArr,$user,$start=0,$end=20,$key=""){
		$this->initread();
                //$search_key = md5(json_encode($submitArr)."-".$key);
		$search_key = $key;
        // $this->init();
		$this->CI->xmlrpc->method('s_searchLDB');
		$submitArrStr = json_encode($submitArr);
		$request = array($appID,$submitArrStr,$user,$start,$end,$search_key );
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return (json_decode(base64_decode($this->CI->xmlrpc->display_response()),true));
		}
	}

	function recordLDBActivity($appID,$subsciptionArray,$user,$userIdList,$action,$status){
		$this->init();
		$this->CI->xmlrpc->method('recordLDBActivity');
        // error_log(print_r($subsciptionArray,true));
		$subscriptionArrayJson = json_encode($subsciptionArray);
        // error_log($subscriptionArrayJson);
		$request = array($appID,$subscriptionArrayJson,$user,$userIdList,$action,$status);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}

	function getActivityDetails($appID,$activityId){
		$this->initread();
		$this->CI->xmlrpc->method('getActivityDetails');
		$request = array($appID,$activityId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return json_decode($this->CI->xmlrpc->display_response(),true);
		}
	}

    function updateActivityStatus($appID,$activityId,$activityStatus){
		$this->init();
		$this->CI->xmlrpc->method('updateActivityStatus');
		$request = array($appID,$activityId,$activityStatus);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return json_decode($this->CI->xmlrpc->display_response(),true);
		}
	}

	function sgetSpecializationList($appId,$courseName,$scope = ""){
		$this->init();
		$this->CI->xmlrpc->method('sgetSpecializationList');
		$request = array($appID,$courseName,$scope);
		$this->CI->xmlrpc->request($request);
	//	print_r($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return ($this->CI->xmlrpc->display_response());
		}
	}
	function sgetSpecializationListByParentId($appId,$parentId){
		$this->init();
		$this->init();
		$key = md5('sgetSpecializationListByParentId'.$appId." ".$parentId);
		error_log_shiksha("key for cache is : ".$key);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){


		$this->CI->xmlrpc->method('sgetSpecializationListByParentId');
		$request = array($appID,$parentId);
		$this->CI->xmlrpc->request($request);
	//	print_r($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
				$response=$this->CI->xmlrpc->display_response();
				$this->cacheLib->store($key,$response);
				return $response;
			}
		}
		else
		{
			return $this->cacheLib->get($key);
		}

	}

    function getCourseForCriteria($appId,$categoryId, $scope, $courseLevel) {
        $this->init();
        $this->CI->xmlrpc->method('sgetCourseForCriteria');
        $request = array($appID,$categoryId,$scope, $courseLevel);
        $this->CI->xmlrpc->request($request);
        if (! $this->CI->xmlrpc->send_request()) {
            return $this->CI->xmlrpc->display_error();
        } else {
            $response=$this->CI->xmlrpc->display_response();
            return $response;
        }
    }

	function sgetCourseList($appId,$categoryId='', $scope = 'india'){
		$this->init();
		$key = md5('sgetCourseList'.$appId." ".$categoryId);
		error_log_shiksha("key for cache is : ".$key);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){


			$this->CI->xmlrpc->method('sgetCourseList');
			$request = array($appID,$categoryId,$scope);
			$this->CI->xmlrpc->request($request);
			//	print_r($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				$response=$this->CI->xmlrpc->display_response();
				$this->cacheLib->store($key,$response);
				return $response;
			}
		}
		else
		{
			return $this->cacheLib->get($key);
		}
	}

	function sgetCityStateList($appId){
		$this->init();
		$key = md5('sgetCityStateList'.$appId);
		error_log_shiksha("key for cache is : ".$key);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){

			$this->CI->xmlrpc->method('sgetCityStateList');
			$request = array($appId);
			$this->CI->xmlrpc->request($request);
			//	print_r($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				$response=$this->CI->xmlrpc->display_response();
				$this->cacheLib->store($key,$response);
				return $response;
			}
		}
		else
		{
			return $this->cacheLib->get($key);
		}
	}

	function sgetUserDetails($appID,$userIdList){
		$this->initread();
		$this->CI->xmlrpc->method('sgetUserDetails');
		$request = array($appID,$userIdList);
		$this->CI->xmlrpc->request($request);
	//	print_r($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			//return ($this->CI->xmlrpc->display_response());
			return base64_decode($this->CI->xmlrpc->display_response());
		}

	}

	function sUpdateContactViewed($appID,$clientId,$userId,$contactType,$subscriptionId,$creditToConsume,$activity_flag='LDB', $actual_course_id = '', $userDataArray=array()){
		$this->init();
		$this->CI->xmlrpc->method('sUpdateContactViewed');
		$request = array($appID,$clientId,$userId,$contactType,$subscriptionId,$creditToConsume,$activity_flag, $actual_course_id,json_encode($userDataArray));
		$this->CI->xmlrpc->request($request);
	//	print_r($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return ($this->CI->xmlrpc->display_response());
		}

	}

	function getViewableUsers($appID,$userIdList,$clientId,$ExtraFlag="false",$excludeResponses = FALSE, $actual_course_id = ''){
		$this->init();
		$this->CI->xmlrpc->method('getViewableUsers');
		$request = array($appID,json_encode($userIdList),$clientId,$ExtraFlag,$excludeResponses, $actual_course_id);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return (json_decode($this->CI->xmlrpc->display_response(),true));
		}
	}

    function sgetCreditToConsume($appID,$userIdList,$contactType,$clientId,$ExtraFlag="false",$excludeResponses = FALSE, $actual_course_id = '', $creditToBeConsumed=array()){
		$this->init();
		$this->CI->xmlrpc->method('sgetCreditToConsume');
		$request = array($appID,json_encode($userIdList),$contactType,$clientId,$ExtraFlag,$excludeResponses,$actual_course_id,json_encode($creditToBeConsumed));
		$this->CI->xmlrpc->request($request);
		
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return (json_decode($this->CI->xmlrpc->display_response(),true));
		}

	}

     function sgetGroupList($appId){
		$this->init();
		$this->CI->xmlrpc->method('sgetGroupList');
		$request = array($appID);
		$this->CI->xmlrpc->request($request);
	//	print_r($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return (json_decode($this->CI->xmlrpc->display_response(),true));
		}

	}

     function sgetCourseListByGroup($appId,$categoryId='',$groupId=''){
		$this->init();
		$this->CI->xmlrpc->method('sgetCourseListByGroup');
		$request = array($appID,$categoryId,$groupId);
		$this->CI->xmlrpc->request($request);
	//	print_r($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return (json_decode($this->CI->xmlrpc->display_response(),true));
		}

	}
	function getCourseListByGroupTestPrep($appId,$groupId=''){
		$this->init();
		$this->CI->xmlrpc->method('getCourseListByGroupTestPrep');
		$request = array($appID,$groupId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return (json_decode($this->CI->xmlrpc->display_response(),true));
		}
	}
      function sAddCoursesToGroup($appId,$groupId,$courseIdArray,$ExtraFlag){
		$this->init();
		$this->CI->xmlrpc->method('sAddCoursesToGroup');
		$request = array($appID,$groupId,json_encode($courseIdArray),json_encode($ExtraFlag));
		$this->CI->xmlrpc->request($request);
	//	print_r($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return (json_decode($this->CI->xmlrpc->display_response(),true));
		}

	}

    function sRemoveCoursesFromGroup($appId,$groupId,$courseIdArray,$ExtraFlag){
		$this->init();
		$this->CI->xmlrpc->method('sRemoveCoursesFromGroup');
		$request = array($appID,$groupId,json_encode($courseIdArray),json_encode($ExtraFlag));
		$this->CI->xmlrpc->request($request);
	//	print_r($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return (json_decode($this->CI->xmlrpc->display_response(),true));
		}

	}

    function saddGroupCreditConsumptionPolicy($appId,$groupId,$viewCredits,$emailCredits,$smsCredits,$shared_view_limit,$premimum_view_credits,$premimum_view_limit,$email_limit,$sms_limit,$view_limit){
		$this->init();
		$this->CI->xmlrpc->method('saddGroupCreditConsumptionPolicy');
		$request = array($appID,$groupId,$viewCredits,$emailCredits,$smsCredits,$shared_view_limit,$premimum_view_credits,$premimum_view_limit,$email_limit,$sms_limit,$view_limit);
		$this->CI->xmlrpc->request($request);
	//	print_r($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return (json_decode($this->CI->xmlrpc->display_response(),true));
		}

	}

    function sgetCreditConsumedForAction($clientId,$userId,$action){
		$this->init();
		$this->CI->xmlrpc->method('sgetCreditConsumedForAction');
		$request = array($clientId,$userId,$action);
		$this->CI->xmlrpc->request($request);
	//	print_r($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return (json_decode($this->CI->xmlrpc->display_response(),true));
		}
	}
      function getGroupForAcourse($courseId,$extraFlag){
		$this->init();
		$this->CI->xmlrpc->method('getGroupForAcourse');
		$request = array($courseId,$extraFlag);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return json_decode($this->CI->xmlrpc->display_response(),true);
		}
	}

	function getCreditConsumedByGroup($groupId){
		$this->init();
		$this->CI->xmlrpc->method('getCreditConsumedByGroup');
		$request = array($groupId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return json_decode($this->CI->xmlrpc->display_response(),true);
		}
	}

	function isLDBUser($userId){
		error_log("inside isLDBUser....");
		$this->init();
                $this->CI->xmlrpc->method('isLDBUser');
                $request = array($userId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return json_decode($this->CI->xmlrpc->display_response(),true);
                }
	}

	function checkLDBUser($appID){
                $this->init();
                $this->CI->xmlrpc->method('checkLDBUser');
                $request = array($appID);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return json_decode($this->CI->xmlrpc->display_response(),true);
                }
        }

	function getLeadsForInstitutes($institute_ids){
                $this->init();
                $this->CI->xmlrpc->method('getLeadsForInstitutes');
                $request = array(array($institute_ids,'struct'));
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = ($this->CI->xmlrpc->display_response());
						$response = json_decode(gzuncompress(base64_decode($response)),true);
						return $response;
                }
        }

        function getResponsesByClientId($clientId){
                $this->init();
                $this->CI->xmlrpc->method('getResponsesByClientId');
                $request = array($clientId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return json_decode($this->CI->xmlrpc->display_response(),true);
                }

        }
        function getResponsesByMultiLocationClientId($clientId){
                $this->init();
                $this->CI->xmlrpc->method('getResponsesByMultiLocationClientId');
                $request = array($clientId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return json_decode($this->CI->xmlrpc->display_response(),true);
                }

        }


        function createCSV($responses){
                $data = "";
                foreach($responses[0] as $k=>$v){
			if($k != 'response_id' && $k != 'institute_id')
			{
				$data.= $k.",";
			}
                }
                $data.= "\n";
                foreach($responses as $row){
                        $line = "";
                        foreach($row as $key=>$value){
				if($key != 'response_id' && $key != 'institute_id')
				{
					if(!isset($value) || $value == ""){
						$value = ",";
					}else{
						$value = str_replace('"', '""', $value);
						$value = '"' . $value . '"' . ",";
					}
					$line.= $value;
				}
                        }
                        $data .= trim($line)."\n";
                }
                $data = str_replace("\r", "", $data);
                return $data;
        }

        function sendCSVMail($data,$name,$from,$to,$cc,$filename,$subject)
		{
            $fileatt_type = "application/xls"; // File Type
            $fileatt_name = $filename; // Filename that will be used for the file as the attachment

			if($subject) {
				$email_subject = $subject;
			}
			else {
				$email_subject = "Leads to ".$name." --date ".date("F j, Y, g:i a"); // The Subject of the email
			}
			
			$email_message = "Dear Customer, <br><br> Please find attached your leads for date ".date("F j, Y, g:i a").".<br><br> Regards,<br>Shiksha Team<br><br><br><br><br><br>"; // Message that the email has in it
            $email_message.= "**************** CAUTION - Disclaimer *****************
This e-mail contains PRIVILEGED AND CONFIDENTIAL INFORMATION intended solely
for the use of the addressee(s). If you are not the intended recipient, please delete the original message. Further, you are not
to copy, disclose, or distribute this e-mail or its contents to any other person and
any such actions are unlawful. This e-mail may contain viruses. Infoedge has taken
every reasonable precaution to minimize this risk, but is not liable for any damage
you may sustain as a result of any virus in this e-mail. You should carry out your
own virus checks before opening the e-mail or attachment. Infoedge reserves the
right to monitor and review the content of all messages sent to or from this e-mail
address. Messages sent to or from this e-mail address may be stored on the
Infoedge e-mail system.
***Infoedge******** End of Disclaimer ********Infoedge***";

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

			$data = chunk_split(base64_encode($data));

			$email_message .= "--{$mime_boundary}\n" .
			"Content-Type: {$fileatt_type};\n" .
			" name=\"{$fileatt_name}\"\n" .
			"Content-Transfer-Encoding: base64\n\n" .
			$data .= "\n\n" .
			"--{$mime_boundary}--\n";
			
			$ok = mail($to, $email_subject, $email_message, $headers);
        }

	function getResponseLocalities($responses_ids){
		$this->init();
		$this->CI->xmlrpc->method('getResponseLocalities');
		$request = $responses_ids;
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return json_decode($this->CI->xmlrpc->display_response(),true);
		}
	}
	
	function sendLDBCSVMail($data,$from,$to,$cc,$filename,$Content,$email_subject){
                $fileatt_type = "application/xls"; // File Type
                $fileatt_name = $filename; // Filename that will be used for the file as the attachment

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
                $Content .= "\n\n";

                $email_message .= "--{$mime_boundary}\n" .
                "Content-Type: {$fileatt_type};\n" .
                " name=\"{$fileatt_name}\"\n" .
                "Content-Transfer-Encoding: base64\n\n" .
                $data .= "\n\n" .
                "--{$mime_boundary}--\n";
                $ok = mail($to, $email_subject, $email_message, $headers);
        }

}
?>
