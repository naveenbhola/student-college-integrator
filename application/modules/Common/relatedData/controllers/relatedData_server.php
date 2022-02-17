<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev:: 411           $:  Revision of last commit
$Author: shirish $:  Author of last commit
$Date: 2009-09-23 04:52:28 $:  Date of last commit

This class provides the Message Board Server Web Services. 
The message_board_client.php makes call to this server using XML RPC calls.

$Id: relatedData_server.php,v 1.18 2009-09-23 04:52:28 shirish Exp $: 

*/

class RelatedData_server extends MX_Controller {

	/*
	*	index function to recieve the incoming request
	*/
		
	function index(){
		
		//load XML RPC Libs
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('relateddataconfig');

		$this->load->helper('url');
	        $this->load->library('dbLibCommon');
        	$this->dbLibObj = DbLibCommon::getInstance('RelatedData');

		//Define the web services method
		$config['functions']['getrelatedData'] = array('function' => 'RelatedData_server.getrelatedData');
		$config['functions']['getAllRelatedData'] = array('function' => 'RelatedData_server.getAllRelatedData');
		$config['functions']['mergeRelatedData'] = array('function' => 'RelatedData_server.mergeRelatedData');
		$config['functions']['getFilterQuestionList'] = array('function' => 'RelatedData_server.getFilterQuestionList');
		$config['functions']['updateFilterQuestionList'] = array('function' => 'RelatedData_server.updateFilterQuestionList');
		$config['functions']['getQueryStringForRelatedQuestion'] = array('function' => 'RelatedData_server.getQueryStringForRelatedQuestion');
		$config['functions']['updateQueryStringForRelatedQuestion'] = array('function' => 'RelatedData_server.updateQueryStringForRelatedQuestion');
		$config['functions']['insertUpdateRelatedQuestion'] = array('function' => 'RelatedData_server.insertUpdateRelatedQuestion');

			
		//initialize
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}
	
    function getQueryStringForRelatedQuestion($request){
		$parameters = $request->output_parameters();
		$productId=$parameters['0'];
		$productName=$parameters['1'];

		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->relateddataconfig->getDbConfig($appID,$dbConfig);	
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->dbLibObj->getReadHandle();
		if($dbHandle == ''){
			log_message('error','postReply can not create db handle');
		}
		$queryCmd = "select queryString from relatedDataQueryString where listing_type_id=? and listing_type=? and related_listing_type=?";
		$query = $dbHandle->query($queryCmd, array($productId,$productName,"ask"));
        $output = "";
        foreach ($query->result_array() as $row){
            if($output == ""){
                $output = $row['queryString'];
            }
            else
            {
                $output .= ",".$row['queryString'];
            }
        }
        if($output=="")
        {
                $this->load->library('listing_client');
                $ListingClientObj = new Listing_client();
                /*$listingDetails = $ListingClientObj->getListingDetails('12',$productId , $productName);
                error_log("Shirish121 getListingDetail ".print_r($listingDetails,true));
                if($productName == "institute")
                {
                    $output = $listingDetails[0]['title'];
                }
                else
                {
                    $output = $listingDetails[0]['institute_name'];
                }*/
                $listingDetails = $ListingClientObj->getInstituteTitle('12',$productId , $productName);
                $output = $listingDetails;
        }
		$response = array($output,'string');	
		return $this->xmlrpc->send_response($response);	
   }

    function updateQueryStringForRelatedQuestion($request){
		$parameters = $request->output_parameters();
		$productId = $parameters['0'];
		$productName =$parameters['1'];
		$queryString = mysql_escape_string($parameters['2']);

		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->relateddataconfig->getDbConfig($appID,$dbConfig);	
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->dbLibObj->getWriteHandle();
		if($dbHandle == ''){
			log_message('error','postReply can not create db handle');
		}
		//$queryCmd = "update relatedDataQueryString set queryString = '".$queryString."' where listing_type_id='".$productId."' and listing_type='".$productName."' and related_listing_type='ask'";
        $queryCmd = "insert into relatedDataQueryString values ('',?,?,'ask','".$queryString."','') on duplicate key update queryString = '".$queryString."';";
		$query = $dbHandle->query($queryCmd, array($productId,$productName));
        $output = "updated";
		$response = array($output,'string');	
		return $this->xmlrpc->send_response($response);	
   }


    function updateFilterQuestionList($request){
		$parameters = $request->output_parameters();

		$productId=$parameters['0'];
		$productName=$parameters['1'];
		$filterType=$parameters['2'];
		$newQuestionList=$parameters['3'];

		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->relateddataconfig->getDbConfig($appID,$dbConfig);	
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->dbLibObj->getWriteHandle();
		if($dbHandle == ''){
			log_message('error','postReply can not create db handle');
		}
        if($filterType != "exclude")
        {
           $queryCmd = "update relatedDataFilterTable set filter_type='ex-include' where  listing_type_id=? and listing_type=? and related_listing_type='ask' and filter_type='include'";
           $query = $dbHandle->query($queryCmd, array($productId,$productName));
        }
        if($filterType != "include")
        {
           $queryCmd = "update relatedDataFilterTable set filter_type='ex-exclude' where  listing_type_id=? and listing_type=? and related_listing_type='ask' and filter_type='exclude'";
           $query = $dbHandle->query($queryCmd, array($productId,$productName));
        }
        $questionArray = explode(',',$newQuestionList);
        foreach($questionArray as $value)
        {
            if(trim($value) != "")
            {
                $queryCmd = "insert into relatedDataFilterTable (listing_type_id,listing_type,related_listing_type_id,related_listing_type,filter_type) values (?,?,?,'ask','".mysql_escape_string($filterType)."')";
                $query = $dbHandle->query($queryCmd, array($productId,$productName,$value));
            }
        }
        $output = "updated";
		$response = array($output,'string');	
		return $this->xmlrpc->send_response($response);	
   }
 
   function getFilterQuestionList($request){
		$parameters = $request->output_parameters();

		$productId=$parameters['0'];
		$productName=$parameters['1'];
		$filterType=mysql_escape_string($parameters['2']);

		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->relateddataconfig->getDbConfig($appID,$dbConfig);	
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->dbLibObj->getReadHandle();
		if($dbHandle == ''){
			log_message('error','postReply can not create db handle');
		}
        $filterTypeClause ="";
        if($filterType != "")
        {
            $filterTypeClause = " and filter_type='$filterType'";
        }
		$queryCmd = "select related_listing_type_id from relatedDataFilterTable where listing_type_id = ? and listing_type = ? and related_listing_type=? ".$filterTypeClause;
		$query = $dbHandle->query($queryCmd, array($productId,$productName,"ask"));
        $output = "";
        foreach ($query->result_array() as $row){
            if($output == ""){
                $output = $row['related_listing_type_id'];
            }
            else
            {
                $output .= ",".$row['related_listing_type_id'];
            }
        }
		$response = array($output,'string');	
		return $this->xmlrpc->send_response($response);	
   }
   function getIncludeQuestionList($request){
		$productId=$parameters['0'];
		$productName=$parameters['1'];

   }

    function insertUpdateRelatedQuestion($request)
    {
        $parameters = $request->output_parameters();
		$productName=$parameters['0'];
		$productId=$parameters['1'];
		$relatedProductName=$parameters['2'];
		$relatedData=base64_decode($parameters['3']);
		$keyword=$parameters['4'];
        $this->load->library('relateddataconfig');
        //$dbConfig = array( 'hostname'=>'localhost');
        //$this->relateddataconfig->getDbConfig($appID,$dbConfig);	
        //$dbHandle = $this->load->database($dbConfig,TRUE);
        $dbHandle = $this->dbLibObj->getWriteHandle();
        if($dbHandle == ''){
            log_message('error','postReply can not create db handle');
        }
        $queryCmd = "insert into relatedData values('', ?, ?, ?, ?, ?, '') on duplicate key update relatedTags='".mysql_escape_string($keyword)."', relatedData='".mysql_escape_string($relatedData)."', relatedDataSponsored=''";

        $query = $dbHandle->query($queryCmd, array($productName,$productId,$keyword,$relatedData,$relatedProductName));
        $response = array('done','string');
		return $this->xmlrpc->send_response($response);	
    }


   function getAllRelatedData($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$productName=$parameters['1'];
		$productId=$parameters['2'];
//		$relatedProduct=$parameters['3'];

		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->relateddataconfig->getDbConfig($appID,$dbConfig);	
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->dbLibObj->getReadHandle();
		if($dbHandle == ''){
			log_message('error','postReply can not create db handle');
		}
		$queryCmd = "select * from relatedData where productName=? and productId=?";
		$query = $dbHandle->query($queryCmd, array($productName,$productId));

		$mainArr = array();		

		foreach ($query->result_array() as $row){
			array_push($mainArr,array($row,'struct'));
		}

		$response = array($mainArr,'struct');	
		return $this->xmlrpc->send_response($response);	
	}



	function getrelatedData($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$productName=$parameters['1'];
		$productId=$parameters['2'];
		$relatedProduct=$parameters['3'];

		//connect DB
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->relateddataconfig->getDbConfig($appID,$dbConfig);	
		//$dbHandle = $this->load->database($dbConfig,TRUE);
		$dbHandle = $this->dbLibObj->getReadHandle();
		if($dbHandle == ''){
			log_message('error','postReply can not create db handle');
		}
		$queryCmd = "select * from relatedData where productName=? and productId=? and relatedProductName=?";
		$query = $dbHandle->query($queryCmd, array($productName,$productId,$relatedProduct));

		$mainArr = array();		

	        foreach ($query->result_array() as $row){
			array_push($mainArr,array($row,'struct'));
		}

		$response = array($mainArr,'struct');	
		return $this->xmlrpc->send_response($response);	
	}


    function mergeRelatedData($request) {
        $parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$productName=$parameters['1'];
		$productId=$parameters['2'];
		$relatedProductName=$parameters['3'];
		$relatedData=$parameters['4'];
        $this->load->library('relateddataconfig');
        //$dbConfig = array( 'hostname'=>'localhost');
        //$this->relateddataconfig->getDbConfig($appID,$dbConfig);	
        //$dbHandle = $this->load->database($dbConfig,TRUE);
        $dbHandle = $this->dbLibObj->getWriteHandle();
        if($dbHandle == ''){
            log_message('error','postReply can not create db handle');
        }
        $queryCmd = "select * from relatedData where productName=? and productId=? and relatedProductName=?";
        $query = $dbHandle->query($queryCmd, array($productName,$productId,$relatedProductName));
        $flagHasEntry = "0";
        foreach ($query->result_array() as $row){
            $flagHasEntry = "1";
            $oldRelatedData = $row['relatedData'];

        }
        if($flagHasEntry == "0") {
            $queryCmd = "insert into relatedData values('', '".mysql_escape_string($productName)."', '".mysql_escape_string($productId)."', '".mysql_escape_string($relatedTags)."', '".mysql_escape_string($relatedData)."', '".mysql_escape_string($relatedProductName)."', '".mysql_escape_string($relatedDataSponsored)."')";
        }else {
            //            $_POST['relatedData'] =
            $jasonOld = $oldRelatedData;
            $jasonNew = $relatedData;
            $oldArr = json_decode($jasonOld,true);
            $newArr = json_decode($jasonNew,true);
            $oldArrList = $oldArr['resultList'];
            $flagGotAlready = "0";
            for($i = 0; $i < count($oldArrList); $i++) {
                if($oldArrList[$i]['typeId'] == $newArr['resultList'][0]['typeId']) {
                    $oldArr['resultList'][$i] = $newArr['resultList'][0];
                    $flagGotAlready = "1";
                    break;
                }
            }

            if($flagGotAlready == "0") {
                $oldArr['resultList'] = array_merge($newArr['resultList'],$oldArr['resultList']);
            }

            $relatedData = json_encode($oldArr); 
            $queryCmd = "update relatedData set relatedData='".mysql_escape_string($relatedData)."' where productId='".mysql_escape_string($productId)."' and productName='".mysql_escape_string($productName)."' and relatedProductName = '".mysql_escape_string($relatedProductName)."'";
        }
        $query = $dbHandle->query($queryCmd);
//        echo "DONE";
    }



	/*
	* Insert related data in to DB
	*/	
    function insertRelatedData() {
        $appID=1;
        $this->load->library('relateddataconfig');
        //$dbConfig = array( 'hostname'=>'localhost');
        //$this->relateddataconfig->getDbConfig($appID,$dbConfig);	
        //$dbHandle = $this->load->database($dbConfig,TRUE);
        $dbHandle = $this->dbLibObj->getWriteHandle();
        if($dbHandle == ''){
            log_message('error','postReply can not create db handle');
        }
        $queryCmd = "insert into relatedData values('', ?, ?, ?, ?, ?, ?) on duplicate key update relatedTags='".mysql_escape_string($_POST['relatedTags'])."', relatedData='".mysql_escape_string($_POST['relatedData'])."', relatedDataSponsored='".mysql_escape_string($_POST['relatedDataSponsored'])."'";
        $query = $dbHandle->query($queryCmd, array($_POST['productName'],$_POST['productId'],$_POST['relatedTags'],$_POST['relatedData'],$_POST['relatedProductName'],$_POST['relatedDataSponsored']));
    }

    function mergeJsons() {
        $jasonOld = $_POST['json1'];
        $jasonNew = $_POST['json2'];
        $oldArr = json_decode($jasonOld,true);
        $newArr = json_decode($jasonNew,true);
        $oldArr['resultList'] = array_merge($newArr['resultList'],$oldArr['resultList']);
        echo json_encode($oldArr);
    }


}
?>
