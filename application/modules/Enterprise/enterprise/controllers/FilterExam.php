<?php

class FilterExam extends MX_Controller {

	private function init() {
		$this->load->helper(array('form', 'url','date','image','shikshaUtility'));
		$this->load->library(array('miscelleneous','message_board_client','blog_client','event_cal_client','ajax','category_list_client','listing_client','register_client','enterprise_client','sums_manage_client','alerts_client'));
		$this->userStatus = $this->checkUserValidation();
		$this->filterexamodel = $this->load->model('enterprise/filterexammodel');
		require APPPATH.'modules/User/registration/config/examConfig.php';

	}
        
        // check pre selected exam and load view for Filtering Exam
	function selectExamCMS(){
		$this->init();
		$userStatus = $this->checkUserValidation();
		if (($userStatus == "false" ) || ($userStatus == "")) {
		header('location:/enterprise/Enterprise/loginEnterprise');
		exit();
		}
		$validity = $this->userStatus;
		$entObj = new Enterprise_client();
		$headerTabs = $entObj->getHeaderTabs(1, $this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
		$this->userStatus[0]['headerTabs'] = $headerTabs;
		$data['validateuser'] = $this->userStatus;
		$data['headerTabs'] = $this->userStatus[0]['headerTabs'];
		$allExam=$this->filterexamodel->getAllExams();
		$selected=$this->filterexamodel->getSelectedExamListDB();
		$dropDown = $this->input->post('c_courseExam',true);
		$data['errorMessage']="";	
		$data['selectedExam']=$selected;
		$data['allExam']=$allExam;
		$this->load->view("enterprise/selectExamCMS", $data);	
	}
	
	// function to make changes in DB
	function getSelectedExamCMS(){
		$this->init();
		$courses = $this->input->post('course',true);
	        $this->filterexamodel->resetExamList();
		foreach($courses as $index=>$value){
		$this->filterexamodel->updateExamList($value);	
		}
		$this->selectExamCMS();
	}
 
}
