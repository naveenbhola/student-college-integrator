<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: ankurg $:  Author of last commit
$Date: 2010/09/20 09:44:54 $:  Date of last commit


$Id: MailerClient.php,v 1.20.60.2 2010/09/20 09:44:54 ankurg Exp $:

*/

class MailerClient{
    var $CI_Instance;
	function init(){
	//set_time_limit(0);
	ini_set('max_execution_time', '1800');
        $this->CI_Instance = & get_instance();
		$this->CI_Instance->load->library('xmlrpc');
		$this->CI_Instance->xmlrpc->set_debug(0);
		$this->CI_Instance->xmlrpc->timeout(5400);
		$this->CI_Instance->xmlrpc->server(MAILER_SERVER_URL,MAILER_SERVER_PORT);
	}

    function unsubscribe($appID,$encodedMail) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('unsubscribe');
        $request = array($appID,$encodedMail);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return utility_decodeXmlRpcResponse($this->CI_Instance->xmlrpc->display_response());
        }
    }


    function submitOpenMail($appId, $mailerId, $emailId, $trackerId, $mailId, $widgetName) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('submitOpenMail');
        $request = array($appID, $mailerId, $emailId, $trackerId, $mailId, $widgetName);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function captureMisData($appId,$url,$widgetName,$mailerId, $emailId, $mailId){
        $this->init();
        $this->CI_Instance->xmlrpc->method('captureMisData');
        $request = array($appId,$url,$widgetName,$mailerId, $emailId, $mailId);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getMailersList($appId,$userId, $userGroup,$range =3, $groupId, $adminType,$groupFilter,$timeStart,$timeEnd, $status = '') {
        $this->init();
        $this->CI_Instance->xmlrpc->method('getMailersList');
        $request = array($appID,$userId, $userGroup,$range, $groupId, $adminType,$groupFilter,$timeStart,$timeEnd, $status);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return utility_decodeXmlRpcResponse($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getMailerTrackingUrls($appId,$userId, $userGroup, $mailerId,$trackerId,$startTime, $endTime) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('getMailerTrackingUrls');
        $request = array($appID,$userId, $userGroup, $mailerId,$trackerId,$startTime, $endTime);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }




    function runCronMailer($appId) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('runCronMailer');
        $request = array($appID);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }





    function saveMailer($appID,$name,$templateId,$listId,$time, $senderMail, $userId,$userGroup,$sumsData, $criteria, $numUsers, $sender_name, $groupId, $mailStatus, $totalUsersInCriteria, $subject = '') {
        error_log("HAAA vibhu");
        $this->init();
        $this->CI_Instance->xmlrpc->method('saveMailer');
        error_log("HAAA huhuu hurray vibhu");
        $request = array($appID, $name,$templateId,$listId, $time, $senderMail,$userId, $userGroup,$sumsData, $criteria, $numUsers, $sender_name, $groupId, $mailStatus, $totalUsersInCriteria, $subject);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }



    function getAllSmsTemplates($appID, $userId, $userGroup) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('getAllSmsTemplates');
        $request = array($appID, $userId, $userGroup);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }


    function getAllTemplates($appID, $userId, $userGroup,$template = "yes", $groupId, $adminType) {
        error_log("HAAA vibhu");
        $this->init();
        $this->CI_Instance->xmlrpc->method('getAllTemplates');
        error_log("HAAA huhuu hurray vibhu");
        $request = array($appID, $userId, $userGroup,$template, $groupId, $adminType);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            error_log("Time in client = ".(microtime_float())."sec");
            return utility_decodeXmlRpcResponse($this->CI_Instance->xmlrpc->display_response());
        }
    }


    function checkTemplateCsv($appID, $templateId, $csvArr, $user, $userGoup,$skipEmailValidation = FALSE) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('checkTemplateCsv');
        $csvArrStr = json_encode($csvArr);
        $request = array($appID, $templateId, $csvArrStr, $user, $userGoup,$skipEmailValidation);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function encryptCsv($appID,$csvArr, $field, $url, $eurl,$unsuburl)
    {
        $this->init();
          error_log("error1234 shivam");
        $this->CI_Instance->xmlrpc->method('encryptCsv');
        $csvArrStr = json_encode($csvArr);
        $request = array($appID,$csvArrStr, $field, $url, $eurl, $unsuburl);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 shivam");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getListInfo($appID, $listId, $userId, $userGroup){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getListInfo');
        $request = array($appID, $listId, $userId, $userGroup);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }

    function sendTestMailer($appID, $listId, $email, $userId, $userGroup){
        $this->init();
        $this->CI_Instance->xmlrpc->method('sendTestMailer');
        $request = array($appID, $listId, $email, $userId, $userGroup);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function s_submitSearchQuery($appID,$submitArr,$user,$userGroup){
        $this->init();
        $this->CI_Instance->xmlrpc->method('s_submitSearchQuery');
        $submitArrStr = json_encode($submitArr);
        error_log("VIBHU DODO ".$submitArrStr);
        $request = array($appID,$submitArrStr,$user,$userGroup);
        $this->CI_Instance->xmlrpc->request($request);
        //print_r($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }



    function s_getSearchFormData1($appID){
        $this->init();
        $this->CI_Instance->xmlrpc->method('s_getSearchFormData1');
        $request = array($appID);
        $this->CI_Instance->xmlrpc->request($request);
        print_r($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }


    function s_getSearchFormData($appID){
        $this->init();
        $this->CI_Instance->xmlrpc->method('s_getSearchFormData');
        $request = array($appID);
        $this->CI_Instance->xmlrpc->request($request);
        //print_r($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }



    function updateListInfo($appID, $listId, $name, $desc, $userId, $userGroup){
        $this->init();
        $this->CI_Instance->xmlrpc->method('updateListInfo');
        $request = array($appID, $listId, $name, $desc, $userId, $userGroup);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }



    function getTemplateInfo($appID, $templateId, $userId, $userGroup){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getTemplateInfo');
        $request = array($appID, $templateId);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }


    function getListCsv($appId, $oldListId, $negateListArr, $numEmail,$createdBy, $userGroup) {
        $this->init();
        error_log("DOOD IN get LISTCSV");
        $this->CI_Instance->xmlrpc->method('getListCsv');
        $negateListArrStr = json_encode($negateListArr);
        $request = array($appID, $oldListId, $negateListArrStr, $numEmail, $createdBy, $userGroup);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }



    function submitList($appId, $oldListId, $negateListArr, $numEmail,$createdBy, $userGroup) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('submitList');
        $negateListArrStr = json_encode($negateListArr);
        $request = array($appID, $oldListId, $negateListArrStr, $numEmail, $createdBy, $userGroup);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 rrrrrrrrrrrrrrvibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }



    function insertOrUpdateTemplate($appId, $id, $name, $desc, $subject, $html, $createdBy, $templateType, $groupId) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('insertOrUpdateTemplate');
        $request = array($appID, $id, $name, $desc, $subject, base64_encode(gzcompress($html)), $createdBy, $templateType, $groupId);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function setTemplateVariables($appId, $templateId, $keyValueArr, $userId, $userGroup){
        $this->init();
        $this->CI_Instance->xmlrpc->method('setTemplateVariables');
        $keyValueArrJson = json_encode($keyValueArr);
        $request = array($appID, $templateId, $keyValueArrJson);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getAllLists($appID, $userId, $userGroup, $clientId=-1) {
        error_log("HAAA vibhu");
        $this->init();
        $this->CI_Instance->xmlrpc->method('getAllLists');
        error_log("HAAA huhuu hurray vibhu");
        $request = array($appID, $userId, $userGroup,$clientId);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }



    function getTemplateVariables($appId, $templateId, $userId, $userGroup){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getTemplateVariables');
        $request = array($appID, $templateId);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }


    function getVariablesKey($appId){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getVariablesKey');
        $request = array($appID);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log("error123 vibhu");
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }








    function getProductData($appID, $productID = 0){
		$this->init();
        error_log_shiksha("in getProductData of client library");
		$this->CI_Instance->xmlrpc->method('getProductData');
		$request = array($appID, $productID);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

    function getProductForUser($appID, $userId){
		$this->init();
        error_log_shiksha("in getProductForUser of client library");
		$this->CI_Instance->xmlrpc->method('getProductForUser');
		$request = array($appID, $userId);
        error_log_shiksha("doooooommmmmmmmm".$userId);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

    function updateUserAsset($appID, $userId, $val, $type){
		$this->init();
        error_log_shiksha("in updateUserAsset of client library");
		$this->CI_Instance->xmlrpc->method('updateUserAsset');
		$request = array($appID, $userId, $val,$type);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}






    function addTransaction($appID, $productID,$userId,$paymentOption){
		$this->init();
        error_log_shiksha("in addTransaction of client library");
		$this->CI_Instance->xmlrpc->method('addTransaction');
		$request = array($appID, $productID,$userId,$paymentOption);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

        function getTransactionHistory($appID,$userId){
		$this->init();
                error_log_shiksha("in getTransactionHistory of client library");
		$this->CI_Instance->xmlrpc->method('sgetTransactionHistory');
                $request = array($appID, $userId);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

    function autoLogin($appID,$email)
    {
        $this->init();
        error_log_shiksha("in registerFeedback of client library");
        $this->CI_Instance->xmlrpc->method('autoLogin');
        $request = array($appID, $email);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request())
        {
            return $this->CI_Instance->xmlrpc->display_error();
        }
        else
        {
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function registerLead($appID,$mailer_id,$email,$feedback,$typeId, $type)
    {
        $this->init();
        error_log_shiksha("in registerFeedback of client library");
        $this->CI_Instance->xmlrpc->method('registerLead');
        $request = array($appID, $mailer_id , $email , $feedback,$typeId, $type);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request())
        {
            return $this->CI_Instance->xmlrpc->display_error();
        }
        else
        {
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }



    function registerFeedback($appID,$mailer_id,$email,$feedback)
    {
        $this->init();
        error_log_shiksha("in registerFeedback of client library");
        $this->CI_Instance->xmlrpc->method('registerFeedback');
        $request = array($appID, $mailer_id , $email , $feedback);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request())
        {
            return $this->CI_Instance->xmlrpc->display_error();
        }
        else
        {
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function registerPoll($appID,$mailer_id,$email,$poll_id,$option)
    {
        $this->init();
        error_log_shiksha("in registerPoll of client library");
        $this->CI_Instance->xmlrpc->method('registerPoll');
        $request = array($appID, $mailer_id , $email ,$poll_id , $option);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request())
        {
            return $this->CI_Instance->xmlrpc->display_error();
        }
        else
        {
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function createPoll($appId,$pollName,$pollTitle,$optionArray = array()){
        $this->init(); 
        $this->CI_Instance->xmlrpc->method('createPoll');
        $request = array(array($appId,'int'),array($pollName,'string'), array($pollTitle,'string'), array($optionArray,'struct'));
        error_log_shiksha(print_r($request,true));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("ERROR AT DATABASE". $this->CI_Instance->xmlrpc->display_error());
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function registerData($appId, $data, $cityId, $localityId){
        $this->init(); 
        $this->CI_Instance->xmlrpc->method('registerData');
        $request = array($appId, $data, $cityId, $localityId);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("ERROR AT DATABASE". $this->CI_Instance->xmlrpc->display_error());
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function addListInMailer($appId,$mailerId,$listId){
        $this->init(); 
        $this->CI_Instance->xmlrpc->method('addListInMailer');
        $request = array($appId,$mailerId,$listId);
        error_log_shiksha(print_r($request,true));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("ERROR AT DATABASE". $this->CI_Instance->xmlrpc->display_error());
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }
    
    function sendRegistrationQuestionMailer($appId,$userId,$displayname,$userCategory,$duration,$email){
        $this->init(); 
        $this->CI_Instance->xmlrpc->method('sendRegistrationQuestionMailer');
        $request = array($appId,$userId,$displayname,$userCategory,$duration,$email);
        error_log_shiksha(print_r($request,true));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("ERROR AT DATABASE". $this->CI_Instance->xmlrpc->display_error());
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

   function generateWeeklyMailer($appId){
        $this->init(); 
        $this->CI_Instance->xmlrpc->method('generateWeeklyMailer');
        $request = array($appId);
        error_log_shiksha(print_r($request,true));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("ERROR AT DATABASE". $this->CI_Instance->xmlrpc->display_error());
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

   function generateDailyMailer($appId){
        $this->init(); 
        $this->CI_Instance->xmlrpc->method('generateDailyMailer');
        $request = array($appId);
        error_log_shiksha(print_r($request,true));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("ERROR AT DATABASE". $this->CI_Instance->xmlrpc->display_error());
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }


  function generateBestAnswerMailer ($appId){
        $this->init(); 
        $this->CI_Instance->xmlrpc->method('generateBestAnswerMailer');
        $request = array($appId);
        error_log_shiksha(print_r($request,true));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("ERROR AT DATABASE". $this->CI_Instance->xmlrpc->display_error());
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function generateAutoLoginLink($appId,$email,$url,$encrypted=0){
        $this->init();
        $this->CI_Instance->xmlrpc->method('generateAutoLoginLinkS');
        $request = array($appId,$email,$url,$encrypted);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function updateMailer($appId,$mailerId,$status){
        $this->init();
        $this->CI_Instance->xmlrpc->method('updateMailer');
        $request = array($appId,$mailerId,$status);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function updateMailerList($appId,$mailerId,$listId,$status){
        $this->init();
        $this->CI_Instance->xmlrpc->method('updateMailerList');
        $request = array($appId,$mailerId,$listId,$status);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function incrementCounter($appId,$mailerId, $number){
        $this->init();
        $this->CI_Instance->xmlrpc->method('incrementCounter');
        $request = array($appId,$mailerId, $number);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function externalMassQueueAdd($appId,$fromEmail,$toEmail,$subject,$content,$contentType="text",$sendTime="0000-00-00 00:00:00",$attachment='n',$attachmentArray=array(),$ccEmail=null,$bccEmail=null)
    {
	$this->init();
	$this->CI_Instance->xmlrpc->method('externalMassQueueAdd');	
	$request = array ( array($appId, 'string'), array($fromEmail, 'string'), array($toEmail, 'string'), array($subject, 'string'), array($content, 'string'),   array($contentType, 'string'),    array($sendTime, 'string'),   array($attachment, 'string'),   array($attachmentArray, 'array'),  array($ccEmail, 'string'), array($bccEmail, 'string'), 'struct');
	$this->CI_Instance->xmlrpc->request($request);	
	if (!$this->CI_Instance->xmlrpc->send_request()){
	    return  $this->CI_Instance->xmlrpc->display_error();
	}
	else{
	    return $this->CI_Instance->xmlrpc->display_response();
	}
    }

    function createMassAttachment($appId, $type_id,$type, $attachmentType, $attachmentContent, $attachment_name, $attachment_file_type)
    {
	$this->init();
	$this->CI_Instance->xmlrpc->method('createMassAttachment');	
	$request = array (array($appId, 'string'),array($type_id, 'int'),   array($type, 'string'),   array($attachmentType, 'string'),   array($attachmentContent, 'string'),   array($attachment_name, 'string'),   array($attachment_file_type, 'string'),   'struct');
	$this->CI_Instance->xmlrpc->request($request);	
	if ( ! $this->CI_Instance->xmlrpc->send_request()){
	  return  $this->CI_Instance->xmlrpc->display_error();
	}
	else{
	  return $this->CI_Instance->xmlrpc->display_response();
	}
    }

    function getMassAttachmentId($appId, $type_id,$type, $document_type,$attachment_name='')
    {
	$this->init();
	$this->CI_Instance->xmlrpc->method('getMassAttachmentId');	
	$request = array (array($appId, 'string'),array($type_id, 'int'),   array($type, 'string'),   array($document_type, 'string'),  array($attachment_name, 'string'),  'struct');
	$this->CI_Instance->xmlrpc->request($request);	
	if ( ! $this->CI_Instance->xmlrpc->send_request()){
	  return  $this->CI_Instance->xmlrpc->display_error();
	}
	else{
	  return $this->CI_Instance->xmlrpc->display_response();
	}
    }

    function updateMailerDetails($appID, $name, $time, $senderMail, $userId, $sumsData, $numUsers, $sender_name, $mailStatus, $mailerId, $subject = '') {
        error_log("HAAA vibhu");
        $this->init();
        $this->CI_Instance->xmlrpc->method('updateMailerDetails');
        $request = array($appID, $name, $time, $senderMail, $userId, $sumsData, $numUsers, $sender_name, $mailStatus, $mailerId, $subject);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    
    /*
     * Author	:	Abhinav
     */
    function generateQnAMailer(){
    	$this->CI_Instance = & get_instance();
    	$predis = PredisLibrary::getInstance();
    	//$this->CI_Instance->load->model('mailermodel');
    	$this->CI_Instance->load->model('common/personalizationmodel');
    	$lowerDateLimit = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")));
    	$upperDateLimit = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
    	
    	$mailIds = array();
    	$userIdsToThread = array();
    	$offset = 0;
    	
    	$userIdsMailSentPreviousDate = $this->CI_Instance->personalizationmodel->getUserIdsSentMailForDailyAskAndAnswerMailer(date('Y-m-d',strtotime('-1 day')));

        // Fetch the User from LDBExclusion List and added them in the Exclusion List
        // MAB - 1345 - Change by ankit
        $ldbExclusionList = $this->CI_Instance->personalizationmodel->getLDBExclusionList();
        $combinedExclusionList = array_merge($userIdsMailSentPreviousDate,$ldbExclusionList);
        $combinedExclusionList = array_unique($combinedExclusionList);

    	while(TRUE){
    		$resultSet =  $this->CI_Instance->personalizationmodel->getUserIdsGeneratedConetentRecently($lowerDateLimit, $upperDateLimit, $offset, ($offset+500));
    		foreach($resultSet['data'] as $data){
    			if(!in_array($data['userId'], $combinedExclusionList)){
    				$userIdsToThread[$data['userId']][] = $data['threadId'];
    			}
    		}
    		if(($offset + 500) < $resultSet['rows']['rows']){
    			$offset += 500;
    		}else{
    			break;
    		}
    	}
    	
    	$lowerDateLimit = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - 3, date("Y")));
    	$offset = 0;
    	$recentThreads = array();
    	while(TRUE){
    		$resultSet = $this->CI_Instance->personalizationmodel->getThreadGeneratedRecently($lowerDateLimit, $upperDateLimit, $offset, ($offset + 500));
    		foreach ($resultSet['data'] as $data){
    			$recentThreads[$data['threadId']] = $data;
    			$recentThreads[$data['threadId']]['tags'] = $predis->getMembersInSortedSet('threadTags:thread:'.$data['threadId'], 0, -1, FALSE, TRUE);
    		}
    		
    		if(($offset + 500) < $resultSet[rows]['rows']){
    			$offset += 500;
    		}else{
    			break;
    		}
    	}
    	
    	$highLevelTags = $predis->getMembersOfSet('highLevelTagsForPersonalization');
    	$userThreadAffinityScore = array();
    	$score = 0;
    	$userIdsForWhichDetailsToBeFetched = array();
    	
    	foreach ($userIdsToThread as $userId => $userThreadIds){
    		//$userThreadAffinityScore	= array();
    		$userTagAffinityScore		= $predis->getMembersInSortedSet("userFollowsTag:user:".$userId, 0, -1, TRUE, TRUE);
    		$userTagTopTags				= array_values(array_diff(array_keys($userTagAffinityScore), $highLevelTags));
    		$userTagTopTags				= array_slice($userTagTopTags, 0, 25);
    		foreach ($recentThreads as $threadId => $threadDetails){
    			$score = 0;
    			// if user has already contributed on thread OR user himself has asked this question, then don't consider this thread
    			if(!in_array($threadId, $userThreadIds) && $userId != $threadDetails['userId']){
    				// check if there are common tags between top tags of user and thread
    				$commonTagsBetweenUserAndThread = array_intersect($userTagTopTags, array_keys($threadDetails['tags']));
    				// if no common tags then skip this thread
    				if(!empty($commonTagsBetweenUserAndThread)){
    					foreach ($threadDetails['tags'] as $tagId => $mappingValue){
    						if($mappingValue == 0){
    							$score += $userTagAffinityScore[$tagId];
    						}else{
    							$score += $userTagAffinityScore[$tagId] / 2;
    						}
    					}
    				}
    			}
    			if($score == 0){
    				continue;
    			}elseif($threadDetails['msgCount'] <= 0){
    				$userThreadAffinityScore[$userId][0][$threadId] = $score;
    				if(!in_array($threadDetails['userId'], $userIdsForWhichDetailsToBeFetched)){
    					$userIdsForWhichDetailsToBeFetched[] = $threadDetails['userId'];
    				}
    			}else {
    				$userThreadAffinityScore[$userId][1][$threadId] = $score;
    				if(!in_array($threadDetails['userId'], $userIdsForWhichDetailsToBeFetched)){
    					$userIdsForWhichDetailsToBeFetched[] = $threadDetails['userId'];
    				}
    			}
    		}
    		if(!empty($userThreadAffinityScore[$userId][0])){
    			arsort($userThreadAffinityScore[$userId][0], SORT_NUMERIC);
    			ksort($userThreadAffinityScore[$userId]);
    		}
    		if(!empty($userThreadAffinityScore[$userId][1])){
    			arsort($userThreadAffinityScore[$userId][1], SORT_NUMERIC);
    		}
    		
    		//_p($userThreadAffinityScore);
    	}
    	$userIdsForWhichDetailsToBeFetched = array_merge($userIdsForWhichDetailsToBeFetched, array_keys($userIdsToThread));
    	
    	$this->CI_Instance->load->model('user/usermodel');
    	$userDetailsData = $this->CI_Instance->usermodel->getUsersBasicInfoById($userIdsForWhichDetailsToBeFetched);
    	$systemMailerObj = Modules::load('systemMailer/SystemMailer/');
    	foreach ($userThreadAffinityScore as $userId => $threadData){
    		$i = 0;
    		$mailData = array();
    		foreach($threadData as $threadAnswerType => $threadIds){
    			foreach ($threadIds as $threadId => $score){
    				$temp = array();
    				$temp['threadText']				= $recentThreads[$threadId]['msgTxt'];
    				$temp['threadAnswers']			= $recentThreads[$threadId]['msgCount'];
    				$temp['threadOwnerFirstName']	= $userDetailsData[$recentThreads[$threadId]['userId']]['firstname'];
    				$temp['threadOwnerLastName']	= $userDetailsData[$recentThreads[$threadId]['userId']]['lastname'];
    				$temp['threadUrl']		= getSeoUrl($threadId, 'question', $recentThreads[$threadId]['msgTxt']);
    				$mailData['threadData'][] = $temp;
    				if(++$i == 5){
    					break 2;
    				}
    			}
    		}
    		$mailData['userId']			= $userId;
    		$mailData['userFirstName']	= $userDetailsData[$userId]['firstname'];
    		$mailData['subject']		= ucfirst($userDetailsData[$userId]['firstname']).', here are few questions that need your guidance';
    		//$mailIds[$userId] 		= Modules::run('systemMailer/SystemMailer/qnaDailyMailer', $userDetailsData[$userId]['email'],$mailData);
    		$mailIds[$userId]			= $systemMailerObj->qnaDailyMailer($userDetailsData[$userId]['email'],$mailData);
    	}
    	
    	if(!empty($mailIds)){
    		$this->CI_Instance->personalizationmodel->logDailyAskAndAnswerMailer($mailIds);
    	}
    	return $mailIds;
    }

}
?>
