<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev:: 411           $:  Revision of last commit
$Author: ankurg $:  Author of last commit
$Date: 2010/07/14 05:27:16 $:  Date of last commit


This class provides the Message Board Server Web Services.
The message_board_client.php makes call to this server using XML RPC calls.

*/

class OnlineForms_server extends MX_Controller {

/**
 *	index function to recieve the incoming request
 */
    function init() {
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        //$this->load->library('OnlineFormConfig');
        $this->load->library('alerts_client');
        $this->load->helper('url');
        $this->load->helper('shikshautility');
		$this->load->model('onlineparentmodel');
        return true;
    }

    function index() {
	//load XML RPC Libs
        $this->init();
        //Define the web services method
        $config['functions']['getOnlineInstituteInfo'] = array('function' => 'OnlineForms_server.getOnlineInstituteInfo');
        $config['functions']['getPageDataForTemplate'] = array('function' => 'OnlineForms_server.getPageDataForTemplate');
        $config['functions']['getPagesWithNoTemplate'] = array('function' => 'OnlineForms_server.getPagesWithNoTemplate');
		$config['functions']['getPageDetails'] = array('function' => 'OnlineForms_server.getPageDetails');
        $config['functions']['updateTemplatePath'] = array('function' => 'OnlineForms_server.updateTemplatePath');
        $config['functions']['getFieldValidations'] = array('function' => 'OnlineForms_server.getFieldValidations');
        $config['functions']['getInfoForPageToBeDisplayed'] = array('function' => 'OnlineForms_server.getInfoForPageToBeDisplayed');
        $config['functions']['setUserStartingForm'] = array('function' => 'OnlineForms_server.setUserStartingForm');
        $config['functions']['setFormData'] = array('function' => 'OnlineForms_server.setFormData');
		$config['functions']['getFormData'] = array('function' => 'OnlineForms_server.getFormData');
		$config['functions']['getFormDataByCourseId'] = array('function' => 'OnlineForms_server.getFormDataByCourseId');
		$config['functions']['setFormStatus'] = array('function' => 'OnlineForms_server.setFormStatus');
        $config['functions']['updateFormData'] = array('function' => 'OnlineForms_server.updateFormData');
        $config['functions']['getPageDataForEdit'] = array('function' => 'OnlineForms_server.getPageDataForEdit');
        $config['functions']['getTemplatePath'] = array('function' => 'OnlineForms_server.getTemplatePath');
        $config['functions']['getOnlineFormId'] = array('function' => 'OnlineForms_server.getOnlineFormId');
        $config['functions']['getPageIdFromPageNumber'] = array('function' => 'OnlineForms_server.getPageIdFromPageNumber');
        $config['functions']['getInstitutesForOnlineHomepage'] = array('function' => 'OnlineForms_server.getInstitutesForOnlineHomepage');
        $config['functions']['getFormsForInstitute'] = array('function' => 'OnlineForms_server.getFormsForInstitute');
        $config['functions']['getFormListForInstitute'] = array('function' => 'OnlineForms_server.getFormListForInstitute');
	$config['functions']['getFormForInstitute'] = array('function' => 'OnlineForms_server.getFormForInstitute');
        $config['functions']['getPagesUserHasFilled'] = array('function' => 'OnlineForms_server.getPagesUserHasFilled');
        $config['functions']['getFormCompleteData'] = array('function' => 'OnlineForms_server.getFormCompleteData');
		$config['functions']['getFormListForUser'] = array('function' => 'OnlineForms_server.getFormListForUser');
		$config['functions']['getPaymentDetailsByUserId'] = array('function' => 'OnlineForms_server.getPaymentDetailsByUserId');
		$config['functions']['getPaymentDetailsById'] = array('function' => 'OnlineForms_server.getPaymentDetailsById');
		$config['functions']['addPayment'] = array('function' => 'OnlineForms_server.addPayment');
		$config['functions']['updatePayment'] = array('function' => 'OnlineForms_server.updatePayment');
		$config['functions']['addPaymentLog'] = array('function' => 'OnlineForms_server.addPaymentLog');
		$config['functions']['addNotification'] = array('function' => 'OnlineForms_server.addNotification');
		
		$config['functions']['deletePreviousAttachments'] = array('function' => 'OnlineForms_server.deletePreviousAttachments');
		$config['functions']['attachDocuments'] = array('function' => 'OnlineForms_server.attachDocuments');
		$config['functions']['getAttachedDocuments'] = array('function' => 'OnlineForms_server.getAttachedDocuments');
		
		$config['functions']['getPageFieldList'] = array('function' => 'OnlineForms_server.getPageFieldList');
		
		$config['functions']['getDocumentDetails'] = array('function' => 'OnlineForms_server.getDocumentDetails');
		$config['functions']['getGDPILocations'] = array('function' => 'OnlineForms_server.getGDPILocations');
		$config['functions']['updateGDPILocation'] = array('function' => 'OnlineForms_server.updateGDPILocation');
		
		$config['functions']['getUsersForDeadlineNotifications'] = array('function' => 'OnlineForms_server.getUsersForDeadlineNotifications');
		
		$config['functions']['downloadDocument'] = array('function' => 'OnlineForms_server.downloadDocument');
	$config['functions']['setInstituteSpecId'] = array('function' => 'OnlineForms_server.setInstituteSpecId');
	$config['functions']['checkIfUserCameOnOnlineForms'] = array('function' => 'OnlineForms_server.checkIfUserCameOnOnlineForms');
	$config['functions']['cronToGetDailyInformation'] = array('function' => 'OnlineForms_server.cronToGetDailyInformation');
        $config['functions']['cronToGetEveryFifteentDayInformation'] = array('function' => 'OnlineForms_server.cronToGetEveryFifteentDayInformation');
	$config['functions']['cronToGetDailyPaidInformation'] = array('function' => 'OnlineForms_server.cronToGetDailyPaidInformation');
		
	$config['functions']['formHasExpired'] = array('function' => 'OnlineForms_server.formHasExpired');	
	$config['functions']['checkIfListingHasOnlineForm'] = array('function' => 'OnlineForms_server.checkIfListingHasOnlineForm');	
        $config['functions']['cronToHandleFailedTransactions'] = array('function' => 'OnlineForms_server.cronToHandleFailedTransactions');
        //initialize
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }


    function getPageDataForTemplate($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $pageId=$parameters['1'];
        $this->load->model('OnlineModel');
        $pageData = array();
        $pageData = $this->OnlineModel->getPageDataForTemplate($pageId);
        $pageData = is_array($pageData)?$pageData:array();

        $mainArr = array();
        $mainArr[0]['pageData'] = $pageData;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function getOnlineInstituteInfo($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $courseId=$parameters['1'];
        $isOtherCourse = $parameters['2'];
        $this->load->model('OnlineModel');
        $instituteInfo = array();
        $instituteInfo = $this->OnlineModel->getOnlineInstituteInfo($courseId,$isOtherCourse);
        $instituteInfo = is_array($instituteInfo)?$instituteInfo:array();
        $mainArr = array();
        $mainArr[0]['instituteInfo'] = $instituteInfo;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function getPagesWithNoTemplate($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $this->load->model('OnlineModel');
        $pageData = array();
        $pageData = $this->OnlineModel->getPagesWithNoTemplate();
        $pageData = is_array($pageData)?$pageData:array();

        $mainArr = array();
        $mainArr[0]['pageData'] = $pageData;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }
	
	function getPageDetails($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
		$pageId=$parameters['1'];
        $this->load->model('OnlineModel');
        $pageData = array();
        $pageData = $this->OnlineModel->getPageDetails($pageId);
        $pageData = is_array($pageData)?$pageData:array();
        $responseString = base64_encode(gzcompress(json_encode($pageData)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function updateTemplatePath($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $pageId=$parameters['1'];
        $templatePath=$parameters['2'];
        $this->load->model('OnlineModel');
        $this->OnlineModel->updateTemplatePath($pageId,$templatePath);
        $response = '1';
        return $this->xmlrpc->send_response($response);
    }

    function getFieldValidations($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $fieldList=$parameters['1'];
        $this->load->model('OnlineModel');
        $validateData = array();
        $validateData = $this->OnlineModel->getFieldValidations($fieldList);
        $validateData = is_array($validateData)?$validateData:array();

        $mainArr = array();
        $mainArr[0]['validateData'] = $validateData;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function getInfoForPageToBeDisplayed($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $courseId=$parameters['2'];
        $this->load->model('OnlineModel');
        $pageData = array();
        $pageData = $this->OnlineModel->getInfoForPageToBeDisplayed($userId, $courseId);
        $pageData = is_array($pageData)?$pageData:array();

        $mainArr = array();
        $mainArr[0]['pageData'] = $pageData;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function getPageDataForEdit($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $courseId=$parameters['1'];
        $userId=$parameters['2'];
        $pageId=$parameters['3'];
        $this->load->model('OnlineModel');
        $pageData = array();
        $pageData = $this->OnlineModel->getPageDataForEdit($courseId, $userId, $pageId);
        $pageData = is_array($pageData)?$pageData:array();

        $mainArr = array();
        $mainArr[0]['pageData'] = $pageData;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function setUserStartingForm($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $courseId=$parameters['2'];
        $formId=$parameters['3'];
        $this->load->model('OnlineModel');
        $onlineFormId = $this->OnlineModel->setUserStartingForm($userId, $courseId, $formId);
        $response = $onlineFormId;
        return $this->xmlrpc->send_response($response);
    }

    function getTemplatePath($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $pageId=$parameters['1'];
        $this->load->model('OnlineModel');
        $path = $this->OnlineModel->getTemplatePath($pageId);
        $response = $path;
        return $this->xmlrpc->send_response($response);
    }

    function setFormData($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $data=$parameters['1'];
        $userId=$parameters['2'];
        $this->load->model('OnlineModel');
        $this->OnlineModel->setFormData($data, $userId);
        $response = '1';
        return $this->xmlrpc->send_response($response);
    }
	
	function getFormData($request){
        $parameters = $request->output_parameters();
        $userId=$parameters['0'];
		$onlineFormId=$parameters['1'];
        $this->load->model('OnlineModel');
        $formData = $this->OnlineModel->getFormData($userId,$onlineFormId);
		
		$data = is_array($formData)?$formData:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
    }
	
	function getFormDataByCourseId($request){
        $parameters = $request->output_parameters();
        $userId=$parameters['0'];
		$courseId=$parameters['1'];
        $this->load->model('OnlineModel');
        $formData = $this->OnlineModel->getFormDataByCourseId($userId,$courseId);
		
		$data = is_array($formData)?$formData:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
    }
	
	function setFormStatus($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $onlineFormId=$parameters['1'];
        $userId=$parameters['2'];
		$status=$parameters['3'];
        $this->load->model('OnlineModel');
        $this->OnlineModel->setFormStatus($onlineFormId, $userId, $status);
        $response = '1';
        return $this->xmlrpc->send_response($response);
    }

    function updateFormData($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $data=$parameters['1'];
        $userId=$parameters['2'];
        $onlineFormId=$parameters['3'];
        $pageId=$parameters['4'];
        $action = $parameters['5'];
        $this->load->model('OnlineModel');
        $this->OnlineModel->updateFormData($data, $userId, $onlineFormId, $pageId, $action);
        $response = '1';
        return $this->xmlrpc->send_response($response);
    }

    function getOnlineFormId($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $courseId=$parameters['2'];
        $pageId=$parameters['3'];
        $this->load->model('OnlineModel');
        $onlineFormId = $this->OnlineModel->getOnlineFormId($userId, $courseId, $pageId);
        $response = $onlineFormId;
        return $this->xmlrpc->send_response($response);
    }

    function getPageIdFromPageNumber($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $courseId=$parameters['2'];
        $pageNumber=$parameters['3'];
        $this->load->model('OnlineModel');
        $pageId = $this->OnlineModel->getPageIdFromPageNumber($userId, $courseId, $pageNumber);
        $response = $pageId;
        return $this->xmlrpc->send_response($response);
    }

    function getInstitutesForOnlineHomepage($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
	$showExternalForms=$parameters[1];
	$filterArray=$parameters[2];
	$department=$parameters[3];
	
        $this->load->model('OnlineModel');
        $instituteIds = array();
        $instituteIds = $this->OnlineModel->getInstitutesForOnlineHomepage($showExternalForms,$filterArray,$department);
        $response = $instituteIds;
        return $this->xmlrpc->send_response($response);
    }

    function getFormsForInstitute($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $instituteId=$parameters['1'];
        $this->load->model('OnlineModel');
        $data = array();
        $data = $this->OnlineModel->getFormsForInstitute($instituteId);
        $data = is_array($data)?$data:array();

        $mainArr = array();
        $mainArr[0]['data'] = $data;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function getFormListForInstitute($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $instituteId=$parameters['1'];
        $entityId=$parameters['2'];
        $type=$parameters['3'];
        $searchParameters=$parameters['4'];
        $startFrom =$parameters['5'];
        $count = $parameters['6'];
        $tab  = $parameters['7'];
        $this->load->model('OnlineModel');
        $data = array();
        $data = $this->OnlineModel->getFormListForInstitute($instituteId, $entityId, $type,$searchParameters,$startFrom,$count,$tab);
        $data = is_array($data)?$data:array();

        $mainArr = array();
        $mainArr[0]['data'] = $data;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function getFormForInstitute($request){
        $parameters = $request->output_parameters(); error_log("getFormForInstitute server");
        $appID=$parameters['0'];
        $instituteId=$parameters['1'];
        $userId=$parameters['2'];
        $onlineFormId=$parameters['3'];

        $this->load->model('OnlineModel');
        $data = array();
        $data = $this->OnlineModel->getFormForInstitute($instituteId, $userId, $onlineFormId);
        $data = is_array($data)?$data:array();

        $mainArr = array();
        $mainArr[0]['data'] = $data;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }    

    function getPagesUserHasFilled($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $onlineFormId=$parameters['2'];
        $courseId=$parameters['3'];

        $this->load->model('OnlineModel');
        $data = array();
        $data = $this->OnlineModel->getPagesUserHasFilled($userId, $onlineFormId,$courseId);
        $data = is_array($data)?$data:array();

        $mainArr = array();
        $mainArr[0]['data'] = $data;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

    function getFormCompleteData($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $courseId=$parameters['2'];

        $this->load->model('OnlineModel');
        $data = array();
        $data = $this->OnlineModel->getFormCompleteData($userId, $courseId);
        $data = is_array($data)?$data:array();

        $mainArr = array();
        $mainArr[0]['data'] = $data;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }
    function getFormListForUser($request){
		$parameters = $request->output_parameters();
		$userid=$parameters['0'];
		$formid=$parameters['1'];
		$this->load->model('OnlineModel');
		$data = array();
		$data = $this->OnlineModel->getFormListForUser($userid,$formid);
		$data = is_array($data)?$data:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}

	function getPaymentDetailsByUserId($request){
		$parameters = $request->output_parameters();
		$userid=$parameters['0'];
		$formid=$parameters['1'];
		$this->load->model('OnlinePaymentModel');
		$data = array(); 
		$data = $this->OnlinePaymentModel->getPaymentDetailsByUserId($userid,$formid);
		$data = is_array($data)?$data:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}
	
	function getPaymentDetailsById($request){
		$parameters = $request->output_parameters();
		$paymentId=$parameters['0'];
		$this->load->model('OnlinePaymentModel');
		$data = array();
		$data = $this->OnlinePaymentModel->getPaymentDetailsById($paymentId);
		$data = is_array($data)?$data:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}
	
	function addPayment($request){ 
		$parameters = $request->output_parameters();
		
		$paymentData = $parameters['0'];
	
		$this->load->model('OnlinePaymentModel');
		$data = array();
		$paymentId = $this->OnlinePaymentModel->addPayment($paymentData);
		$data = array($paymentId);
		$data = is_array($data)?$data:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}
	
	function updatePayment($request){ 
		$parameters = $request->output_parameters();
		
		$paymentId = $parameters['0'];
		$paymentData = $parameters['1'];
	
		$this->load->model('OnlinePaymentModel');
		$data = array();
		$result = $this->OnlinePaymentModel->updatePayment($paymentId,$paymentData);
        if($result==1 && $paymentData['status']=="Success"){
            $data = $this->OnlinePaymentModel->getUserPaymentData($paymentId);
            $this->OnlinePaymentModel->insertData($data);
        }
		$data = array($result);
		$data = is_array($data)?$data:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}
	
	function addPaymentLog($request){
		$parameters = $request->output_parameters();
		
		$paymentLogData = $parameters['0'];
		$this->load->model('OnlinePaymentModel');
		$data = array();
		$logId = $this->OnlinePaymentModel->addPaymentLog($paymentLogData);
		$data = array($logId);
		$data = is_array($data)?$data:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}
	
	function addNotification($request){
		$parameters = $request->output_parameters();
		$onlineFormId = $parameters['0'];
		$userId = $parameters['1'];
		$instituteId = $parameters['2'];
		$msgId = $parameters['3'];
		$status = $parameters['4'];
		
		$this->load->model('OnlineModel');
		$data = array();
		$notificationId = $this->OnlineModel->addNotification($onlineFormId,$userId,$instituteId,$msgId,$status);
		
		$response = $notificationId;
        return $this->xmlrpc->send_response($response);
	}
	
	function deletePreviousAttachments($request)
	{
		$parameters = $request->output_parameters();
		$userId = $parameters['0'];
		$onlineFormId = $parameters['1'];
		$this->load->model('DocumentModel');
		$this->DocumentModel->deletePreviousAttachments($userId,$onlineFormId);
		$response = '1';
        return $this->xmlrpc->send_response($response);
	}
	
	function attachDocuments($request)
	{
		$parameters = $request->output_parameters();
		$userId = $parameters['0'];
		$onlineFormId = $parameters['1'];
		$documentIds = $parameters['2'];
		$this->load->model('DocumentModel');
		$this->DocumentModel->attachDocuments($userId,$onlineFormId,$documentIds);
		$response = '1';
        return $this->xmlrpc->send_response($response);
	}
	
	function getAttachedDocuments($request)
	{
		$parameters = $request->output_parameters();
		$userId = $parameters['0'];
		$onlineFormId = $parameters['1'];
		$this->load->model('DocumentModel');
		$attachedDocuments = $this->DocumentModel->getAttachedDocuments($userId,$onlineFormId);
		$data = is_array($attachedDocuments)?$attachedDocuments:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}
	
	function getDocumentDetails($request)
	{
		$parameters = $request->output_parameters();
		$documentId = $parameters['0'];
		$sharingDetails = $parameters['1']; error_log($documentId);
		$this->load->model('DocumentModel');
		$documentDetails = $this->DocumentModel->getDocumentDetails($documentId,$sharingDetails);
		$data = is_array($documentDetails)?$documentDetails:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}
	
	function getPageFieldList($request){
		$parameters = $request->output_parameters();
		$page_array=json_decode($parameters['0']);
		$this->load->model('OnlineModel');
		$data = array();
		$data = $this->OnlineModel->getPageFieldList($page_array);
		$data = is_array($data)?$data:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
		}
		
	function getGDPILocations($request){
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$courseId=$parameters['1'];
		$this->load->model('OnlineModel');
		$data = array();
		$data = $this->OnlineModel->getGDPILocations($courseId);
		$data = is_array($data)?$data:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
		}
		
	function updateGDPILocation($request){
		$parameters = $request->output_parameters();
		$onlineFormId=$parameters['0'];
		$userId=$parameters['1'];
		$gdpiLocation=$parameters['2'];
		$this->load->model('OnlineModel');
		$data = array();
		$data = $this->OnlineModel->updateGDPILocation($onlineFormId,$userId,$gdpiLocation);
		$data = is_array($data)?$data:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}
	
	function getUsersForDeadlineNotifications($request)
	{
		$this->load->model('OnlineModel');
		$data = array();
		$data = $this->OnlineModel->getUsersForDeadlineNotifications();
		$data = is_array($data)?$data:array();
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}
	
	function downloadDocument($request)
	{
		$parameters = $request->output_parameters();
		$fileName = $parameters['0'];
		
		$filepath = '/var/www/html/shiksha/mediadata/onlineforms/'.$fileName;
		
        $filesize = filesize($filepath);
        
		$data = array();
		$data['fileData'] = base64_encode(file_get_contents($filepath));
		$data['filesize'] = filesize($filepath);
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}

	function setInstituteSpecId($request)
	{
		$parameters = $request->output_parameters();
		$onlineFormId=$parameters['0'];
		$userId=$parameters['1'];
		$instituteId=$parameters['2'];
		$this->load->model('OnlineModel');
		$this->OnlineModel->setInstituteSpecId($onlineFormId,$userId,$instituteId);
		$response = '1';
		return $this->xmlrpc->send_response($response);
	}

	function checkIfUserCameOnOnlineForms($request){
		$parameters = $request->output_parameters();
		$userId=$parameters['0'];
		$this->load->model('OnlineModel');
		$res = $this->OnlineModel->checkIfUserCameOnOnlineForms($userId);
		$response = $res;
		return $this->xmlrpc->send_response($response);
	}

	//*************************************/
	//Cron to get Daily Data Start
	/*************************************/

	function cronToGetDailyInformation($request){
        $parameters = $request->output_parameters();
        $time=$parameters['0'];
		$this->load->model('OnlineModel');
		$res = $this->OnlineModel->cronToGetDailyInformation($time);
		$responseString = base64_encode(gzcompress(json_encode($res)));
		$response = $responseString;
		return $this->xmlrpc->send_response($response);
	}
	
	//*************************************/
	//Cron to get Daily Data End
	/*************************************/

        //*************************************/
	//Cron to get every fifteenth Day Data Start
	/*************************************/

	function cronToGetEveryFifteentDayInformation(){
		$this->load->model('OnlineModel');
		$res = $this->OnlineModel->cronToGetEveryFifteentDayInformation();
		$response = $res;
		return $this->xmlrpc->send_response(json_encode($response));
	}
	//*************************************/
	//Cron to get every fifteenth Day Data End
	/*************************************/
    function formHasExpired($request){

        $parameters = $request->output_parameters();
		$courseId=$parameters['0'];
		$this->load->model('OnlineModel');
		$res = $this->OnlineModel->formHasExpired($courseId);
		$res = array($courseId=>$res);
		$res = json_encode($res);
		return $this->xmlrpc->send_response($res);
	}

	function cronToGetDailyPaidInformation($request){
        $parameters = $request->output_parameters();
        $time=$parameters['0'];
		$this->load->model('OnlineModel');
		$res = $this->OnlineModel->cronToGetDailyPaidInformation($time);
		$responseString = base64_encode(gzcompress(json_encode($res)));
		$response = $responseString;
		return $this->xmlrpc->send_response($response);
	}

	/***************************
	Name: checkIfListingHasOnlineForm
	Purpose: Check if the Institute or course contains Online form. This is required to check the Online form availablity before deleting listing
	Input: Listing type (Institute or Course) and Listing Id
	Output: True (if the listing contains online form) or False
	***************************/
	function checkIfListingHasOnlineForm($request){
		$parameters = $request->output_parameters();
		$listingType=$parameters['0'];
		$listingId=$parameters['1'];
		$this->load->model('OnlineModel');
		$data = array();
		$data = $this->OnlineModel->checkIfListingHasOnlineForm($listingType,$listingId);
		$responseString = base64_encode(gzcompress(json_encode($data)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}
        //*******************************************/
	//Cron to handle failed transaction Starts
	/********************************************/
	function cronToHandleFailedTransactions(){
		$this->load->model('OnlineModel');
		$res = $this->OnlineModel->cronToHandleFailedTransactions();
		$response = $res;
		return $this->xmlrpc->send_response(json_encode($response));
	}

}
