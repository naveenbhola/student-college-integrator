<?php 

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: ankurg $:  Author of last commit
$Date: 2010/07/16 09:29:49 $:  Date of last commit

message_board_client.php makes call to server using XML RPC calls.

$Id: MsgBoard.php,v 1.205 2010/07/16 09:29:49 ankurg Exp $:

*/
class OnlineTests extends MX_Controller {

	var $userStatus = '';
	
	function init($library=array('Online_test_client','category_list_client','register_client','alerts_client','ajax','listing_client','LDB_Client'),$helper=array('url','image','shikshautility','validate','utility_helper')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();	
	}

	//Function to show the Online forms Homepage when someone has clicked from the Menu
	function showOnlineTestsHomepage(){
		$this->init(array('Online_test_client'),array('url','shikshautility_helper'));

		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

		//Check if the user has visited Online forms earlier and tried to fill a form
		//If yes, we will take him directly to the Student dashboard
		$onlineClient = new Online_test_client();

		//In case we are redirected from a Listing detail page, check if the user is logged.
		// If he is logged, redirect to the form page
		// If he is not, show the registration layer and the redirect to the form page
		$displayData['showRegistrationLayer'] = 'false';
		if(isset($_COOKIE['onlineCourseId']) && $_COOKIE['onlineCourseId']!=""){
		    if($displayData['userId']>0){
			  $onlineCourseId = $_COOKIE['onlineCourseId'];
			  setcookie("onlineCourseId", "", time() - 3600,'/',COOKIEDOMAIN);
			  $newLocation = SHIKSHA_HOME.'/Online/OnlineForms/showOnlineForms/'.$onlineCourseId;
				if( (strpos($newLocation, "http") === false) || (strpos($newLocation, "http") != 0) || (strpos($newLocation, SHIKSHA_HOME) === 0) || (strpos($newLocation,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($newLocation,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($newLocation,ENTERPRISE_HOME) === 0) ){
					header('LOCATION:'.$newLocation);
				}
				else{
				    header("Location: ".SHIKSHA_HOME,TRUE,301);
				}
			  exit;
		    }
		    else{	//Show the Login layer
			  $displayData['showRegistrationLayer'] = 'true';
			  $displayData['onlineCourseId'] = $_COOKIE['onlineCourseId'];
			  setcookie("onlineCourseId", "", time() - 3600,'/',COOKIEDOMAIN);
		    }
		}

		$displayData['page_type_for_identification'] = "ONLINE_HOME_PAGE";
		$displayData['total_number_pages'] = 0;
		$displayData['count_result'] = count($displayData['instituteList']);
		if($displayData['count_result']%INSTITUTE_PER_PAGE == 0) {
			$displayData['total_number_pages'] = (int)($displayData['count_result']/INSTITUTE_PER_PAGE);
		} else {
			$displayData['total_number_pages'] = (int)($displayData['count_result']/INSTITUTE_PER_PAGE)+1;
		}
		foreach ($displayData['instituteList'] as $inst_id1=>$instituteList_object1):
		$inst_id1_arry[] = $inst_id1;
		endforeach;
		$displayData['inst_id1_arry'] = $inst_id1_arry;
		$current_page = strip_tags($_REQUEST['start']);
		$current_page = intval($current_page,10);
		if(!empty($current_page)) {
			$current_page_new = ($current_page/INSTITUTE_PER_PAGE)+1;
		} else {
			$current_page_new = 1;
		}
		$paginationHTML = doPagination($displayData['count_result'],SHIKSHA_HOME."/college-admissions-online-mba-application-forms?start=@start@&num=@count@",$current_page,INSTITUTE_PER_PAGE,$displayData['total_number_pages']);
		$displayData['paginationHTML'] = $paginationHTML;
		$offset = ($current_page_new-1)*INSTITUTE_PER_PAGE;
		$return_array = $this->studentdashboardclient->handlePagination($inst_id1_arry,$displayData['total_number_pages'],$current_page_new,INSTITUTE_PER_PAGE) ;
		if(!empty($return_array) && count($return_array)>0) {
			$displayData['instituteList'] = $return_array['instituteList'];
			$displayData['institute_features'] = $return_array['institute_features'];
		}
		/* code ended for pagination */
		$this->load->view('Online/showOnlineHomepage',$displayData);

	}

	//Function to show the Registration page in case the user is not logged in
	function showRegistrationPage($courseId=0){
		$this->init(array('Online_test_client'),array('url'));
		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['urlToRedirect'] = '/OnlineT/OnlineTests/showOnlineTests';
		$this->load->view('OnlineT/showLoginFormWithHeader',$displayData);
	}

	//Main function to display the Online forms to the user for this Course.
	// This will display any page of the form including the Base pages, custom pages, payment or Dashboard
	function showOnlineTests($courseId=0,$edit=0,$pageNumber=0,$action=''){
		$this->init(array('Online_test_client'),array('url','shikshautility_helper'));
		$onlineClient = new Online_test_client();
		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$onlineClient = new Online_test_client();		
		
		//In case the user is not logged, take him to the Online form Homepage
		if($displayData['userId']==0){
		    $this->showRegistrationPage($courseId);
		}
		else{
			//Ask the user for Type of exam he wants to give and the Duration.
			$this->load->library('testconfig');
		    $displayData['test_array'] = TestConfig::$type_of_exams;
		    $displayData['duration_array'] = TestConfig::$duration_array;
		    $displayData['section_array'] = TestConfig::$section_array;
		    $displayData['level_array'] = TestConfig::$level_array;

			//Get user's previous online tests
			$result = $onlineClient->getUsersOnlineTest($appId,$displayData['userId']);
			if(is_array($result)){
				$displayData['tests'] = $result;
			}

			$this->load->view('OnlineT/showOnlinePage',$displayData);
		}
	}

	function showOnlineInstructions()
	{
		//var_dump($_POST);
		$this->init();
		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		//In case the user is not logged, take him to the Online form Homepage
		if($displayData['userId']==0){
		    $this->showRegistrationPage($courseId);
		}
		else{
			$displayData['duration'] = (int) $this->input->post('durationOption');
			$displayData['examtype'] = $this->input->post('testOption');
			$displayData['level'] = $this->input->post('levelOption');
			$displayData['section'] = $this->input->post('sectionOption');
			
			$this->load->library('testconfig');
			$displayData['test_array'] = TestConfig::$type_of_exams;
			$displayData['duration_array'] = TestConfig::$duration_array;
			$displayData['section_array'] = TestConfig::$section_array;
			$displayData['level_array'] = TestConfig::$level_array;
			$this->load->view('OnlineT/showOnlineInstructions',$displayData);
		}
	}
	
	function LoadTest()
	{
		//var_dump($_POST);
		$this->init();
		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		//In case the user is not logged, take him to the Online form Homepage
		if($displayData['userId']==0){
		    $this->showRegistrationPage($courseId);
		}
		else{
			$displayData['duration'] = $duration = (int) $this->input->post('duration');
			$displayData['exam'] = $exam = $this->input->post('exam');
			$displayData['level'] = $level = $this->input->post('level');
			$displayData['section'] = $section = $this->input->post('section');

			$this->load->library('testconfig');
			$displayData['test_array'] = TestConfig::$type_of_exams;
			$displayData['duration_array'] = TestConfig::$duration_array;
			$displayData['section_array'] = TestConfig::$section_array;
			$displayData['level_array'] = TestConfig::$level_array;
			$this->load->view('OnlineT/loadTest',$displayData);
		}
	}

	function getTestandStart()
	{
		//This function will be called to fetch the test from Backend, create a JSON string and then redirect to the Test page.
		$this->init(array('Online_test_client'),array('url','shikshautility_helper'));
		$onlineClient = new Online_test_client();
		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$duration = (int) $this->input->post('duration');
		$exam = $this->input->post('exam');
		$level = $this->input->post('level');
		$section = $this->input->post('section');

		//Now, check if the Exam, level and section are non integers, we have to get their Ids from Config and then call Backend.
		$this->load->library('testconfig');
		$test_array = TestConfig::$type_of_exams;
		foreach ($test_array as $key=>$value){
			if($key==$exam)
				$exam = $value['id'];
		}
		$section_array = TestConfig::$section_array;
		foreach ($section_array as $key=>$value){
			if($key==$section)
				$section = $value['id'];
		}
		$level_array = TestConfig::$level_array;
		foreach ($level_array as $key=>$value){
			if($key==$level)
				$level = $value['id'];
		}
		sleep(2);
    	$result = $onlineClient->getOnlineTestData($appId,$userId,$exam,$duration,$section,$level);
		if(is_array($result)){
		}

	}

	function showResults(){
		//var_dump($_POST);
		$this->init();
		$appId = 12;
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$this->load->view('OnlineT/showResults',$displayData);
	}
}
?>
