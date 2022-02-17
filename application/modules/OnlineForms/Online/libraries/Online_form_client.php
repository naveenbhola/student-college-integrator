<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: manishz $:  Author of last commit
$Date: 2010/02/19 06:18:53 $:  Date of last commit

message_board_client.php makes call to server using XML RPC calls.
$Id: Message_board_client.php,v 1.73 2010/02/19 06:18:53 manishz Exp $: 
*/
class Online_form_client  {

	var $CI;
	var $CI_operation;
	var $cacheLib;
	function init($what='read')
	{
		$this->CI_operation = & get_instance();
		$this->CI = & get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		$server_url = OF_READ_SERVER;
		$server_port = OF_READ_SERVER_PORT;
		if($what=='write'){
	        $server_url = OF_WRITE_SERVER;
		$server_port = OF_WRITE_SERVER_PORT;
		}
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,$server_port );
		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
	}

        function getOnlineInstituteInfo($appId,$courseId,$isOtherCourse){
                $this->init();
                $this->CI->xmlrpc->method('getOnlineInstituteInfo');
                $request = array($appId,$courseId,$isOtherCourse);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
        }

	function getPageDataForTemplate($appId, $pageId){
                $this->init();
                $this->CI->xmlrpc->method('getPageDataForTemplate');
                $request = array($appId,$pageId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
	}


	function getPagesWithNoTemplate($appId){
                $this->init();
                $this->CI->xmlrpc->method('getPagesWithNoTemplate');
                $request = array($appId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
	}
	
	function getPageDetails($appId,$pageId){
                $this->init();
                $this->CI->xmlrpc->method('getPageDetails');
                $request = array($appId,$pageId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
	}

	function updateTemplatePath($appId,$pageId,$templatePath){
                $this->init('write');
                $this->CI->xmlrpc->method('updateTemplatePath');
                $request = array($appId,$pageId,$templatePath);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return $this->CI->xmlrpc->display_response();
                }
	}

	function getFieldValidations($appId, $fieldList){
                $this->init();
                $this->CI->xmlrpc->method('getFieldValidations');
                $request = array($appId,$fieldList);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
	}

	function getInfoForPageToBeDisplayed($appId, $userId, $courseId = 0){
                $this->init('write');
                $this->CI->xmlrpc->method('getInfoForPageToBeDisplayed');
                $request = array($appId,$userId, $courseId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
	}

	function getPageDataForEdit($appId,$courseId,$userId,$pageId){
                $this->init();
                $this->CI->xmlrpc->method('getPageDataForEdit');
                $request = array($appId,$courseId,$userId,$pageId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
	}

	function getTemplatePath($appId,$pageId){
                $this->init();
                $this->CI->xmlrpc->method('getTemplatePath');
                $request = array($appId,$pageId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return $this->CI->xmlrpc->display_response();
                }
	}

	function setUserStartingForm($appId, $userId, $courseId=0, $formId=0){
                $this->init('write');
                $this->CI->xmlrpc->method('setUserStartingForm');
                $request = array($appId,$userId, $courseId,$formId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return $this->CI->xmlrpc->display_response();
                }
	}

	function setFormData($appId, $data, $userId){
                $this->init('write');
                $this->CI->xmlrpc->method('setFormData');
                $request = array($appId,$data,$userId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return $this->CI->xmlrpc->display_response();
                }
	}
	
	function setFormStatus($appId, $onlineFormId, $userId, $status){
                $this->init('write');
                $this->CI->xmlrpc->method('setFormStatus');
                $request = array($appId, $onlineFormId, $userId, $status);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return $this->CI->xmlrpc->display_response();
                }
	}

	function updateFormData($appId, $data, $userId,$onlineFormId, $pageId, $action =""){
                $this->init('write');
                $this->CI->xmlrpc->method('updateFormData');
                $request = array($appId,$data,$userId,$onlineFormId, $pageId,$action);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return $this->CI->xmlrpc->display_response();
                }
	}

	function getOnlineFormId($appId,$userId,$courseId,$pageId){
                $this->init();
                $this->CI->xmlrpc->method('getOnlineFormId');
                $request = array($appId,$userId,$courseId,$pageId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return $this->CI->xmlrpc->display_response();
                }
	}

	function getPageIdFromPageNumber($appId, $userId, $courseId, $pageNumber){
                $this->init();
                $this->CI->xmlrpc->method('getPageIdFromPageNumber');
                $request = array($appId,$userId, $courseId,$pageNumber);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return $this->CI->xmlrpc->display_response();
                }
	}

	function getInstitutesForOnlineHomepage($appId,$showExternalForms='false',$filterArray=array(),$department){
                $this->init();
                $this->CI->xmlrpc->method('getInstitutesForOnlineHomepage');
                $request = array($appId,$showExternalForms,array($filterArray,'struct'),$department);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return $this->CI->xmlrpc->display_response();
                }
	}

	function getFormsForInstitute($appId,$instituteId){
                $this->init();
                $this->CI->xmlrpc->method('getFormsForInstitute');
                $request = array($appId,$instituteId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
	}

	function getFormListForInstitute($appId,$instituteId, $entityId, $type,$arr=array(),$startFrom,$count,$tab){
                $this->init();
                $this->CI->xmlrpc->method('getFormListForInstitute');
                $request = array($appId,$instituteId, $entityId, $type,array($arr,'struct'),$startFrom,$count,$tab);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
	}

	function getFormForInstitute($appId,$instituteId,$userId,$formId){
		$this->init();
                $this->CI->xmlrpc->method('getFormForInstitute');
                $request = array($appId,$instituteId,$userId,$formId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
	}

	function getPagesUserHasFilled($appId, $userId, $onlineFormId,$courseId){
                $this->init();
                $this->CI->xmlrpc->method('getPagesUserHasFilled');
                $request = array($appId,$userId, $onlineFormId,$courseId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
	}

	//There will be four cases in which this will be called
	// 1. By logged in user, after filling up the Course form and from the Payment page
	// 2. By logged in user to view/edit his profile page
	// 3. By logged in user to view/edit his course form
	// 4. By enterprise user to view the form applied on his institute
	function getFormCompleteData($appId, $userId, $courseId=0){
                $this->init();
                $this->CI->xmlrpc->method('getFormCompleteData');
                $request = array($appId, $userId, $courseId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);

			// We need to take care of few cases
			// 1. Change CityId->CityName, countryId->countryName, Ccity->CityName and Ccountry->countryName
			// 2. If company data not set, then do not display I work here.
			// 3. Take care of Multiple allowed fields
			// 4. Also, calculate the Form filled percentage in case of Master form
			$returnData = array();
			$returnData['percentComplete'] = 0;
			$returnData['onlineFormId'] = 0;
			$this->CI->load->library('cacheLib');
			$cacheLib  =  new CacheLib();
			if(is_array($response) && isset($response[0]['data'])){
			    foreach ($response[0]['data'] as $fieldArr){
					$fieldName = $fieldArr['fieldName'];
					$onlineFormId = $fieldArr['onlineFormId'];
					$returnData[$fieldName] = $fieldArr['value'];
					
					// For each field filled in Master form except for the Multiple cases, increment 1.4 percentage 
					if($courseId<=0 && strpos($fieldName,'_mul_')===false ){
				        	$bug_value = $fieldArr['value'];
						$bug_value = trim($bug_value);
					  	if(!empty($bug_value)) {
				      			$returnData['percentComplete'] = $returnData['percentComplete']+1.4;
					  	}
					}
			    }
			    $returnData['onlineFormId'] = $onlineFormId;
			}
            return $returnData;
        }
	}
        function getFormListForUser($userId,$formId=''){
		$this->init();
		$this->CI->xmlrpc->method('getFormListForUser');
		$request = array($userId,$formId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
	
	function getFormData($userId,$onlineFormId)
	{
		$this->init();
		$this->CI->xmlrpc->method('getFormData');
		$request = array($userId,$onlineFormId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
	
	function getFormDataByCourseId($userId,$courseId)
	{
		$this->init();
		$this->CI->xmlrpc->method('getFormDataByCourseId');
		$request = array($userId,$courseId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
	
	
	function getPaymentDetailsByUserId($userId,$formId){
		$this->init();
		$this->CI->xmlrpc->method('getPaymentDetailsByUserId');
		$request = array($userId,$formId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
	
	function getPaymentDetailsById($paymentId){
		$this->init();
		$this->CI->xmlrpc->method('getPaymentDetailsById');
		$request = array($paymentId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
	
	function addPayment($paymentData){
		$this->init('write');
		$this->CI->xmlrpc->method('addPayment');
		//$request = $paymentData;
		$request = array(array($paymentData,'struct'));
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error(); 
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			
			if(is_array($response) && intval($response[0]) >0) {
				return $response[0];
			}
			else {
				return FALSE;
			}
		}
	}
	
	function updatePayment($paymentId,$paymentData){
		$this->init('write');
		$this->CI->xmlrpc->method('updatePayment');
		//$request = $paymentData;
		$request = array($paymentId,array($paymentData,'struct'));
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error(); 
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			
			if(is_array($response) && $response[0] == 1) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
	
	function addPaymentLog($paymentLogData){
		$this->init('write');
		$this->CI->xmlrpc->method('addPaymentLog');
		//$request = $paymentData;
		$request = array(array($paymentLogData,'struct'));
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error(); 
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
	
	function addNotification($onlineFormId,$userId,$instituteId,$msgId,$status=''){
		$this->init('write');
		$this->CI->xmlrpc->method('addNotification');
		//$request = $paymentData;
		$request = array($onlineFormId,$userId,$instituteId,$msgId,$status);
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error(); 
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
	
	function deletePreviousAttachments($userId,$onlineFormId)
	{
		$this->init('write');
		$this->CI->xmlrpc->method('deletePreviousAttachments');
		$request = array($userId,$onlineFormId);
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error(); 
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
	
	function attachDocuments($userId,$onlineFormId,$documentIds)
	{
		$this->init('write');
		$this->CI->xmlrpc->method('attachDocuments');
		$request = array($userId,$onlineFormId,$documentIds);
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error(); 
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
	
	function getAttachedDocuments($userId,$onlineFormId)
	{
		$this->init('write');
		$this->CI->xmlrpc->method('getAttachedDocuments');
		$request = array($userId,$onlineFormId);
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error(); 
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
	
	function getPageFieldList($page_array){
		$this->init();
		$this->CI->xmlrpc->method('getPageFieldList');
		//$request = $paymentData;
		$request = array(json_encode($page_array));
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
	
	function getDocumentDetails($documentId,$sharingDetails=0){
		$this->init('write');
		$this->CI->xmlrpc->method('getDocumentDetails');
		$request = array($documentId,$sharingDetails);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
		}else{
				$response = $this->CI->xmlrpc->display_response();
				$response = json_decode(gzuncompress(base64_decode($response)),true);
				return $response;
		}
	}
	
	function getGDPILocations($appId,$courseId){
		$this->init();
		$this->CI->xmlrpc->method('getGDPILocations');
		$request = array($appId,$courseId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
		}else{
				$response = $this->CI->xmlrpc->display_response();
				$response = json_decode(gzuncompress(base64_decode($response)),true);
				return $response;
		}
	}
	
	function updateGDPILocation($onlineFormId,$userId,$gdpiLocation){
		$this->init('write');
		$this->CI->xmlrpc->method('updateGDPILocation');
		$request = array($onlineFormId,$userId,$gdpiLocation);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
		}else{
				$response = $this->CI->xmlrpc->display_response();
				$response = json_decode(gzuncompress(base64_decode($response)),true);
				return $response;
		}
	}
	
	function getUsersForDeadlineNotifications(){
		$this->init();
		$this->CI->xmlrpc->method('getUsersForDeadlineNotifications');
		$request = array();
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
		}else{
				$response = $this->CI->xmlrpc->display_response();
				$response = json_decode(gzuncompress(base64_decode($response)),true);
				return $response;
		}
	}
	
	function downloadDocument($filename)
	{
		$this->init('write');
		$this->CI->xmlrpc->method('downloadDocument');
		$request = array($filename);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}

	function setInstituteSpecId($onlineFormId,$userId,$instituteId)
	{
                $this->init('write');
                $this->CI->xmlrpc->method('setInstituteSpecId');
                $request = array($onlineFormId,$userId,$instituteId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return $this->CI->xmlrpc->display_response();
                }
	}

	function checkIfUserCameOnOnlineForms($userId){
                $this->init();
                $this->CI->xmlrpc->method('checkIfUserCameOnOnlineForms');
                $request = array($userId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        return $this->CI->xmlrpc->display_response();
                }
	}
	
	//*************************************/
	//Cron to get Daily Data Start
	/*************************************/
	 function cronToGetDailyInformation($time='hourly'){
                $this->init();
                $this->CI->xmlrpc->method('cronToGetDailyInformation');
                $request = array($time);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
        }

       //*************************************/
	//Cron to get every fifteenth Day Data Start
	/*************************************/
	function cronToGetEveryFifteentDayInformation(){
		$this->init();
		$this->CI->xmlrpc->method('cronToGetEveryFifteentDayInformation');
		$request = array();
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode($response,true);
			return $response;
		}
	}
	//*************************************/
	//Cron to get every fifteenth Day Data End
	/*************************************/
	function formHasExpired($courseId) {
		$this->init();
		$this->CI->xmlrpc->method('formHasExpired');
		$request = array($courseId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}
	function cronToGetDailyPaidInformation($time='hourly'){
		$this->init();
		$this->CI->xmlrpc->method('cronToGetDailyPaidInformation');
		$request = array($time);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}

	/***************************
	Name: checkIfListingHasOnlineForm
	Purpose: Check if the Institute or course contains Online form. This is required to check the Online form availablity before deleting listing
	Input: Listing type (Institute or Course) and Listing Id
	Output: True (if the listing contains online form) or False
	***************************/
	function checkIfListingHasOnlineForm($listingType,$listingId){
		$this->init();
		$this->CI->xmlrpc->method('checkIfListingHasOnlineForm');
		$request = array($listingType,$listingId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}
	}
        //*******************************************/
	//Cron to handle failed transaction Starts
	/********************************************/
	function cronToHandleFailedTransactions(){
		$this->init('write');
		$this->CI->xmlrpc->method('cronToHandleFailedTransactions');
		$request = array();
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode($response,true);
			return $response;
		}
	}
}
