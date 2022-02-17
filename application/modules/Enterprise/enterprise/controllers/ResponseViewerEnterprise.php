<?php

class ResponseViewerEnterprise extends MX_Controller{

	private function _init(){
		$this->userStatus = $this->checkUserValidation();
		if(($this->userStatus == "false") || ($this->userStatus == "")){
			header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
		}
 
		if ($this->userStatus[0]['usergroup'] != 'enterprise') {
            header("location:/enterprise/Enterprise/unauthorizedEnt");
        }

		//$this->ERAccessLib = $this->load->library('enterprise/examResponseAccessLib');
	}

	private function getHeaderData(){
		$data['prodId']       = EXAM_RESPONSE_VIEWER_TAB_ID;
		$data['validateuser'] = $this->userStatus;
		$this->load->library("enterprise_client");
		$entObj               = new Enterprise_client();
		$data['headerTabs']   = $entObj->getHeaderTabs(1,$this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        return $data;
	}

	function getExamResponsesForClient($tabStatus = 'live') {
	    // if(strtolower($tabStatus) == 'deleted') {
	    //     echo "Currently this functionality is disabled"."  <a href='/enterprise/Enterprise/getListingsResponsesForClient'>Back</a>";
	    //     return;
	    // }

		$displayData                        = array();
		$this->_init();
		$displayData                        = $this->getHeaderData();	
		$displayData['validateuser']        = $this->userStatus;	
		
		$clientId                           = $this->userStatus[0]['userid'];
		$responseViewerLib                  = $this->load->library('response/responseViewerLib');    

		$displayData['subscriptionDetails'] = $responseViewerLib->getExamResponsesForClient($tabStatus,$clientId);
		$displayData['tabStatus']           = $tabStatus;    
		
		$displayData['prodId']              = EXAM_RESPONSE_VIEWER_TAB_ID;
		$displayData['usergroup']           = 'enterprise';

	    $this->load->view('enterprise/cms_homepage', $displayData);
	}

	function getExamDownloadResponses() {
        ini_set("memory_limit", '800M');
        $this->init();
		$subscriptionId = (int)$this->input->post('subscriptionId',true);       
		$allocationIds = $this->input->post('allocationIds',true);       
        //$subscriptionId = 1;
        $allocationIds = json_decode($allocationIds);
        error_log("AMAN ".print_r($allocationIds,true));
		$clientId          = $this->userStatus[0]['userid'];
		$responseViewerLib = $this->load->library('response/responseViewerLib');    
		//get responses
		$responses         = $responseViewerLib->getExamResponseDataForCSV($subscriptionId,$allocationIds);
		if(count($responses) >0){
	        $responseViewerLib->generateExamResponseCSV($responses,$subscriptionId);	
		}        
        exit();
    }

    function getResponseForExam($subscriptionId='', $timeInterval = '7 day', $start=0)
	{
    	$this->_init();
    	$count = 100;  // we are showing 100 default    	
    	if(empty($subscriptionId) || $subscriptionId<=0){
    		header("location:/enterprise/ResponseViewerEnterprise/getExamResponsesForClient");
	    	return false;
	    }

	    $this->load->config('enterprise/enterpriseConfig');
	    $timeIntervalValues = $this->config->item('timeIntervalFilter');
	    if(empty($timeInterval) || !isset($timeIntervalValues[$timeInterval])){
	    	header("location:/enterprise/ResponseViewerEnterprise/getExamResponsesForClient");
	    	return false;
	    }    

	    if($start < 0){
	    	$start = 0;
	    }

	    // check if subscription is valid for this client if yes than return subscription detatils
	    $displayData = array();
	    $displayData                        = $this->getHeaderData();
		$clientId = $displayData['validateuser'][0]['userid'];
		$responseViewerLib = $this->load->library('response/responseViewerLib');
		$subscriptionDetails =  $responseViewerLib->getSubscriptionDetails($subscriptionId);
		//_p($subscriptionDetails);die;
		if(empty($subscriptionDetails)){
			header("location:/enterprise/ResponseViewerEnterprise/getExamResponsesForClient");
	    	return false;
		}else{
			if($subscriptionDetails['clientId'] == $clientId){
				$this->load->helper('shikshaUtility');

				$displayData['startOffset'] = $start;
			    $displayData['countOffset'] = $count;
			    $displayData['timeInterval'] = $timeInterval;
			    $displayData['prodId'] = EXAM_RESPONSE_VIEWER_TAB_ID;
				$displayData['timeIntervalValues'] = $timeIntervalValues;
				$displayData['subscriptionId'] = $subscriptionId;
				$displayData['subscriptionStatus'] = $subscriptionDetails['status'];
				$displayData['examName'] = $subscriptionDetails['examName'];
			    $displayData['groupNames'] = $subscriptionDetails['examGroupNames'];

			    if($subscriptionDetails['quantityDelivered'] > 0){
					$result = $responseViewerLib->getAllocatedResponseDetailsForSubscription($subscriptionId, $clientId, $start, $count, $timeInterval);
					
					$displayData['resultResponse']['responses'] = $result['responses'];
					$displayData['userData'] = $result['userData'];
					$displayData['usersContactHistory'] = $result['usersContactHistory'];
					$displayData['resultResponse']['numrows'] = $result['totalResponses']?$result['totalResponses']:0;
					unset($result);
				}else{
					$displayData['resultResponse']['numrows'] = 0;
				}
			}else{
				header("location:/enterprise/ResponseViewerEnterprise/getExamResponsesForClient");
	    		return false;
			}
		}

		// Start Online form change by pranjul 13/10/2011
	    $this->load->library('OnlineFormEnterprise_client');
	    $ofObj = new OnlineFormEnterprise_client();
	    $displayData['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($clientId);

	    //_p($displayData);die;
	    $this->load->view('enterprise/examsResponsesView', $displayData);
	    
    }
}