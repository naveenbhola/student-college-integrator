<?php

class OfflineResponse extends MX_Controller {

	private function init(){
		$this->load->library(array('category_list_client','listing_client','register_client','enterprise_client','sums_manage_client','alerts_client'));
		$this->userStatus = $this->checkUserValidation();
	}

	/* function to display cms offline response interface 
	*/
	public function showOfflineResponse(){
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
		
		$this->load->view("enterprise/showOfflineResponse", $data);
	}


	/*
	* Ajax call to show all user details
	* @param: userId
	*/
	public function extractUserData(){
		
		$userInfo = $this->input->post('userInfo',true);

		//In case of invalid email id, return 0
		if(!is_numeric($userInfo) && !filter_var($userInfo, FILTER_VALIDATE_EMAIL)){			
			echo 0;
			return;
		}else if($userInfo < 1 && is_numeric($userInfo)){ // incase of invalid userId
			echo 0;
			return;
		}else if ($userInfo == '') { // In case of invalid value
			echo 0;
			return;
		}	

		if(is_numeric($userInfo)){
			$field = 'userid';
		}else{
			$field = 'email';
		}

		$this->load->model('user/userinfomodel');
		$userInfo = $this->userinfomodel->extractOfflineResponse($userInfo, $field);

		$userData = array();

		if(!empty($userInfo) && $userInfo.count()){
			$userData['fname'] = $userInfo[0]['firstname'];
			$userData['lname'] = $userInfo[0]['lastname'];
			$userData['email'] = $userInfo[0]['email'];
			$userData['password'] = $userInfo[0]['password'];
			$userData['mobile'] = $userInfo[0]['mobile'];
			
			$index = 0;
			foreach ($userInfo as $key => $value) {
				$userData[$index]['course'] = $value['courseTitle'];
				$userData[$index]['institute'] = $value['institute_name'];
				$index++;
			}

			echo json_encode($userData);
			return;
		}
		echo 0;
		return ;
	}

	/*
	* This function make's response for all type of user
	* @params: userData, array having basic user info
	*		 : courseList, array having course id's on which response is made 
	*/

	function storeOfflineResponse(){

		$userBasicInfo = $this->input->post('userData');
		$courseList = $this->input->post('coursesList');
		$actionType = $this->input->post('actionType');

		$cookieData = $_COOKIE['user'];
		
		unset($_POST['userData']);
		unset($_POST['coursesList']);

		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$courseRepository = $listingBuilder->getCourseRepository();

		$courseObjs = $courseRepository->findMultiple($courseList);
		
		foreach($courseList as $index=>$courseId){
			
			$courseObj = $courseObjs[$courseId];
		
			$countryId = $courseObj->getMainLocation()->getCountry()->getId();

			//National
			
			$instituteId = $courseObj->getInstId();
			$instituteName = $courseObj->getInstituteName();
			$cityId = $courseObj->getMainLocation()->getCity()->getId();
			$localityId = $courseObj->getMainLocation()->getLocality()->getId();
			
			$listingType = 'course';
			
			$courseJSON = json_encode(array($instituteId => array("null", $instituteName, "null", $courseId, $listingType)));
			$courseLocationJSON = json_encode(array($instituteId => array("'".$cityId."'", "'".$localityId."'")));

			$_POST['sourcePage'] = $actionType;
			$_POST['reqInfoEmail'] = $userBasicInfo['email'];
			$_POST['reqInfoPhNumber'] = $userBasicInfo['mobile'];
			$_POST['reqInfofirstName'] = $userBasicInfo['fname'];
			$_POST['reqInfolastName'] = $userBasicInfo['lname'];
			$_POST['jSON'] = $courseJSON;
			$_POST['localityJSON'] = $courseLocationJSON;
			
			unset($_POST['source_page']);
			
			$_POST['cookieString'] = $userBasicInfo['email'].'|'.$userBasicInfo['password'].'|pendingverification';
			$_COOKIE['user'] = $userBasicInfo['email'].'|'.$userBasicInfo['password'].'|pendingverification';
			
			Modules::run('MultipleApply/MultipleApply/getBrochureRequest',$_POST);
			unset($_POST);
							
		}
		$_COOKIE['user'] = $cookieData;
		echo 0;
		return ;
	}

	/*
	* This gets all courses of a institute
	* @param : instId [institute id]
	*/

	function getAllCourses(){
		$instId = $this->input->post('instId', true);

		$this->load->model('listing/institutemodel');
		$coursesDetail = $this->institutemodel->getCoursesForInstitute($instId);

		echo json_encode($coursesDetail);
		return;
	}
}
