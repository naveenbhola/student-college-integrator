<?php

class examResponseAccess extends MX_Controller{
	private $userStatus = 'false';

	function __constuct(){
	}

	private function _init(){
		$this->userStatus = $this->checkUserValidation();
		if(($this->userStatus == "false") || ($this->userStatus == "")){
			header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
		}
 
		if ($this->userStatus[0]['usergroup'] != 'cms') {
            header("location:/enterprise/Enterprise/unauthorizedEnt");
        }

		$this->ERAccessLib = $this->load->library('enterprise/examResponseAccessLib');
	}

	private function getHeaderData(){
		$data['prodId'] = 1020;
		$data['validateuser'] = $this->userStatus;
		$this->load->library("enterprise_client");
		$entObj = new Enterprise_client();
        $data['headerTabs'] = $entObj->getHeaderTabs(1,$this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        return $data;
	}

	function manageManualAccess(){
		$data = array();
		
		$this->_init();
		$data = $this->getHeaderData();	

		$this->ERAccessLib->getUserLocation($data);
		// get all exam having live exampages
		$data['examList'] = $this->ERAccessLib->getAllExamDetails();

		$this->load->view('examResponseAccess/manageManualAccess',$data);
	}

	function getExamGroups(){
		$this->_init();
		$examId = $this->input->post('examId',true);
		if(!empty($examId) && $examId >0){
			//get complete exampage object
        	$this->load->builder('ExamBuilder','examPages');
	        $examBuilder          = new ExamBuilder();
	        $this->examRepository = $examBuilder->getExamRepository();
			$examPageData = $this->examRepository->find($examId);
			if(!is_object($examPageData)){
				return ;
			}
            $examGroups = $examPageData->getGroupMappedToExam();
		}else{
			return ;
		}
        $data['examGroups'] = $examGroups;
        $this->load->view('examResponseAccess/examGroups',$data);
	}

	function checkValidEnterpriseUser(){
		$this->_init();
		$userId = $this->input->post('userId',true);
		if(!empty($userId) && $userId >0){
			echo $this->ERAccessLib->checkValidEnterpriseUser($userId);
		}else{
			echo '0';
		}
	}

	function getResponseCount(){
		$this->_init();
		$this->load->helper('security');
    	$data = xss_clean($_POST);
    	$data = $this->ERAccessLib->validateResponseCountFields($data);
    	//_p($data);die;
		if($data == false){
			echo json_encode(array("status"=>"FAIL"));
		}else{
			// fetch response count
			$responseProcessorLib = $this->load->library('response/responseProcessorLib');
			$dateRange['from'] = $data['fromDate'];
			$dateRange['to'] = ($data['campaignType'] == "quantity") ?date("Y-m-d"):$data['timeRangeDurationTo'];
			$data['userCityIds'] = array_values($data['userCityIds']);
			$result = $responseProcessorLib->getResponseCount($data['groupIds'],$data['userCityIds'],$dateRange);
			if($result != 0 && $result == false){
				echo json_encode(array("status"=>"FAIL"));
			}else{
				echo json_encode(array("status"=>"SUCCESS","count"=>$result));	
			}
		}
	}

	function saveSubscription(){
		$this->_init();
		$this->load->helper('security');
    	$data = xss_clean($_POST);
    	$data = $this->ERAccessLib->validateSubscriptionFields($data);
    	if($data == false){
    		echo json_encode("FAIL");
    	}else{
    		// save data
    		$data['createdBy'] = $this->userStatus[0]['userid'];
    		$response = $this->ERAccessLib->saveSubscription($data);
    		echo json_encode($response);
    	}
	}

	function subscription($type =""){
		$this->load->helper('security');
    	$type = xss_clean($type);
		if(!in_array($type, array("active","expired"))){
			return false;
		}

		$data = array();
		$this->_init();
		$data = $this->getHeaderData();

		$data['type'] = $type;
		$data['subscriptionDetails'] = $this->ERAccessLib->getSubscriptionData($type);
		$this->load->view('examResponseAccess/subscriptionDetails',$data);
		//_p($data['campaignData']);die;
	}

	function deleteSubscription(){
		$this->_init();
		$subscriptionId = $this->input->post('subscriptionId',true);
		if(empty($subscriptionId) || $subscriptionId <=0){
			echo json_encode("FAIL");
		}else{
			$response = $this->ERAccessLib->updateSubscriptionStatus($subscriptionId,"deleted");
			echo json_encode($response);
		}
	}
}

?>