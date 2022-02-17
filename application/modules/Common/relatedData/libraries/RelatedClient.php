<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: shirish $:  Author of last commit
$Date: 2009-09-23 03:56:48 $:  Date of last commit


$Id: RelatedClient.php,v 1.7 2009-09-23 03:56:48 shirish Exp $: 

*/

class RelatedClient{
    var $CI_Instance;
	function init(){
        $this->CI_Instance = & get_instance();
		$this->CI_Instance->load->library('xmlrpc');
		$this->CI_Instance->xmlrpc->set_debug(0);
		$this->CI_Instance->xmlrpc->server(RELATED_SERVER_URL,RELATED_SERVER_PORT);			
	}




    function getAllRelatedData($appID, $productName,$productId){
		$this->init();	
		error_log_shiksha("in getrelatedData of client library");
		$this->CI_Instance->xmlrpc->method('getAllRelatedData');
		$request = array($appID, $productName,$productId);
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
	}

    function mergeRelatedData($appID, $productName,$productId,$relatedProduct,$relatedData){
		$this->init();	
		$this->CI_Instance->xmlrpc->method('mergeRelatedData');
		$request = array($appID, $productName,$productId,$relatedProduct,$relatedData);
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
	}


    function getIncludeQuestionList($productId,$productName)
    {
		$this->init();	
		$this->CI_Instance->xmlrpc->method('getFilterQuestionList');
		$request = array($productId,$productName,"include");
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
    }

    function updateIncludeQuestionList($productId,$productName,$newIncludeList)
    {
		$this->init();	
		$this->CI_Instance->xmlrpc->method('updateFilterQuestionList');
		$request = array($productId,$productName,"include",$newIncludeList);
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
    }

    function getExcludeQuestionList($productId,$productName)
    {
		$this->init();	
		$this->CI_Instance->xmlrpc->method('getFilterQuestionList');
		$request = array($productId,$productName,"exclude");
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
    }

    function updateExcludeQuestionList($productId,$productName,$newIncludeList)
    {
		$this->init();	
		$this->CI_Instance->xmlrpc->method('updateFilterQuestionList');
		$request = array($productId,$productName,"exclude",$newIncludeList);
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
    }

    function getKeywordForQues($institute_id,$listing_type,$relatedProductType="ask"){

                $this->init();
                $this->CI_Instance->xmlrpc->method('getQueryStringForRelatedQuestion');
		$request = array($institute_id,$listing_type);

		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
    }



    function getQueryStringForRelatedQuestion($productId,$productName)
    {
		$this->init();	
		$this->CI_Instance->xmlrpc->method('getQueryStringForRelatedQuestion');
		$request = array($productId,$productName);
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
    }

    function updateQueryStringForRelatedQuestion($productId,$productName,$queryString)
    {
		$this->init();	
		$this->CI_Instance->xmlrpc->method('updateQueryStringForRelatedQuestion');
		$request = array($productId,$productName,$queryString);
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
    }

    function insertUpdateRelatedQuestion($productName,$productId,$relatedProduct,$relatedData,$keyword=''){
		$this->init();	
		error_log_shiksha("in getrelatedData of client library");
		$this->CI_Instance->xmlrpc->method('insertUpdateRelatedQuestion');
		$request = array(
                    array($productName,'string'),
                    array($productId,'string'),
                    array($relatedProduct,'string'),
                    array($relatedData,'base64'),
                    array($keyword,'base64')
                    );
		$this->CI_Instance->xmlrpc->request($request);		
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			error_log_shiksha("error123");
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}	
	}


    function getrelatedData($appID, $productName,$productId,$relatedProduct){
		$this->init();	
		error_log_shiksha("in getrelatedData of client library");
		$this->CI_Instance->xmlrpc->method('getrelatedData');
		$request = array($appID, $productName,$productId,$relatedProduct);
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

}
?>
